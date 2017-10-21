<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$uid = $_GPC['uid'];
$from_user = $_W['fans']['from_user'];
$maxid = $_GPC['maxid'];
$lt = $_GPC['lt'];
$uniacid = $_W['uniacid'];
$isAdmin =0;//1表示是管理员，0表示不是
$isBack =0;//1表示是拉黑，0表示不是

load()->model('account');
$_W['account'] = account_fetch($_W['acid']);
$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
if ($_W['account']['level'] != 4) {
    $from_user = $cookie['openid'];
}

if($uid==$from_user){
    $admin = pdo_fetch("select id from " . tablename('haoman_dpm_bpadmin') . "  where admin_openid=:admin_openid and status=0 and rid=:rid", array(':admin_openid' => $from_user,':rid'=>$rid));
   if($admin){
       $isAdmin =1;//1表示是管理员，0表示不是
   }
}

$fanss = pdo_fetch("select id,from_user,is_back from " . tablename('haoman_dpm_fans') . " where rid =:rid and  from_user = :from_user ", array(':rid'=>$rid,':from_user' => $uid));

if($fanss['is_back']==1){
    $isAdmin =0;
    $isBack=1;
}

$bp = pdo_fetch("select isbp,isds,ismbp,isvo from " . tablename('haoman_dpm_bpreply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
if($bp['isbp']==1||$bp['isds']==1||$bp['isvo']){
    $isopend =1;
}else{
    $isopend =0;
}
if($bp['isbp']==1){
    $ismbp=1;
}else{
    $ismbp=0;
}
$list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_messages') . " WHERE rid = :rid and uniacid = :uniacid and status = 1 and is_back !=1 and is_xy !=1 and id > :id and createtime > :createtime ORDER BY id desc limit 20",array(':rid'=>$rid,':uniacid'=>$uniacid,':id'=>$maxid,':createtime'=>$lt));
$minid = $list[0]['id'];
$list = array_reverse($list);

$aa='';
$maxid = $list[0]['id'];
foreach($list as $k=>$v){
    $v['wordimg'] = tomedia($v['wordimg']);
    if($v['is_bp']==1&&$v['is_bpshow']==1){

        $newlist[$k] =$v;
    }

    $v['createtime2'] = $v['createtime'] ;
    $v['createtime'] = date("m-d H:i:s", $v['createtime']) ;


    if(empty($v['avatar'])){
        $v['avatar']="../addons/haoman_dpm/img9/ava_default.jpg";
    }else{
        $v['avatar2'] = $v['avatar'];
        $v['avatar'] = tomedia($v['avatar']);


    }

    $aa .='<div class="timestr" val="'.$v['createtime2'].'">'.$v['createtime'].'</div>';
    if($v['from_user']==$uid){
        $aa .='<div class="msg_box mine" id="msg_box_'.$v['id'].'" mId="'.$v['id'].'">';
    }else{
        $aa .='<div class="msg_box" id="msg_box_'.$v['id'].'" mId="'.$v['id'].'">';
    }

    $aa .='<div class="avatar" style="background-image: url('.$v['avatar'].')" avatar="'.$v['avatar2'].'" nickname="'.$v['nickname'].' "uid="'.$v['from_user'].'"></div>';
    $aa .='<div style="overflow: auto">';
    $aa .='<div class="nickname_line">';
    if($v['from_user']==$uid){
        $aa .='<div class="nickname">';
    }else{
        $aa .='<div class="nickname"> '.$v['nickname'].'';
    }

    if($v['wordimg']&&$v['from_user']!=$uid){
        $aa .='<span class="bp-font-color" onclick="bpForOther(this)"  img="'.$v['wordimg'].'" n="'.$v['nickname'].'"> 为TA霸屏</span>';
    }

    $aa .='</div>';
    $aa .='</div>';



    if($v['is_bp']==1&&$v['gift']==0){
        $aa .='<div class="content" style="background-image: url(../addons/haoman_dpm/imgs/ds_msg_bg.png);">';
        $aa .='<span class="say_point"></span>';
        if($v['type']==5){
            $aa .='<span style="color:#FFDD1B;font-weight: 900;">为"'.$v['for_nickname'].'"重金霸屏'.$v['bptime'].'秒：</span>';
        }else{
            $aa .='<span style="color:#FFDD1B;font-weight: 900;">重金霸屏'.$v['bptime'].'秒：</span>';
        }

        $aa .='<span class="bp-font-color2">'.$v['word'].'</span>';
        if($v['wordimg']){
            if($v['type']==4){
                $aa .='<video src="'.$v['wordimg'].'" data-url="'.$v['wordimg'].'" class="msg_ctx_image" wmode="transparent" style="width: 100%;display: inline-block;z-index:1" autoplay></video>';
            }else{
                $aa .='<img src="'.$v['wordimg'].'" _src="'.$v['wordimg'].'" class="msg_ctx_image" />';
            }

        }
        $aa .='<img style="float: right;width: 100%;" src="../addons/haoman_dpm/imgs/bp_footer.png">';
    }elseif($v['is_bp']==1&&$v['type']==2){
        $aa .='<div class="content" style="background-image: url(../addons/haoman_dpm/imgs/ds_msg_bg.png);">';
        $aa .='<span class="say_point"></span>';
        $aa .='<span style="color:#FFDD1B;font-weight: 900;">重金打赏：</span>';
        $aa .='<span class="bp-font-color2">'.$v['word'].'</span>';
        $aa .='<br />';
        $aa .='<span class="bp-font-color2">'.$v['says'].'</span>';
        if($v['wordimg']){
            $aa .='<img src="'.$v['wordimg'].'" _src="'.$v['wordimg'].'" class="msg_ctx_image"/>';
        }
        $aa .='<img style="float: right;width: 100%;" src="../addons/haoman_dpm/imgs/ds_footer.png">';
    }else if($v['type']==3&&$v['gift_id']!=0){
        $aa .= '<div class="hb_box" rpid="'.$v['gift_id']. '">';
        $aa .= '<div class="hb_box_ctx">' .$v['word']. '</div>';
        $aa .= '</div>';
    }
    else{
        $aa .='<div class="content">';
        $aa .='<span class="say_point"></span>'.$v['word'].'';
        if($v['wordimg']){
            $aa .='<img src="'.$v['wordimg'].'" _src="'.$v['wordimg'].'" class="msg_ctx_image"/>';
        }
    }



    $aa .='</div>';


    $aa .='</div>';

    if (1 ==$isAdmin||$v['from_user']==$uid) {
        //或才是，与是错的
        $aa .='<div class="admin_del_msgs">';
        if($v['from_user']==$uid){
            $aa .='<span class="aDel" onclick="del(this,'.$v['id'].')" data-id="'.$v['id'].'">删除</span>';
        }else{
            if($isAdmin){
                $aa .='<span class="aDel" onclick="del(this,'.$v['id'].')" data-id="'.$v['id'].'">删除</span>';

                $aa .='<span class="aBlack" onclick="toBlack(this)" data-id="'.$v['from_user'].'">拉黑</span>';
            }

        }


        $aa .='</div>';
    }


    $aa .='</div>';
}
if($list){

    $result = array(
        'maxid' => $maxid,
        'isOpened' => $isopend,
        'list' => array_values($newlist),
        'count' => count($newlist),
        'del' => '',
        'data' => $aa,
        'isback' => $isBack,
        'ismbp' => $ismbp,
    );
}else{
    $result = array(
        'maxid' => $maxid,
        'isOpened' => $isopend,
        'list' => '',
        'count' => 0,
        'del' => '',
        'data' => $aa,
        'isback' => $isBack,
        'ismbp' => $ismbp,
    );
}


$this->message($result);