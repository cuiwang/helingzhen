<?php
global $_GPC, $_W;

$rid = intval($_GPC['rid']);
$uniacid = $_W['uniacid'];
$openid = $_GPC['openid'];
$key = $_GPC['key'];


$user_agent = $_SERVER['HTTP_USER_AGENT'];
if (strpos($user_agent, 'MicroMessenger') === false) {

    header("HTTP/1.1 301 Moved Permanently");
    header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
    exit();
}

//网页授权借用开始（特殊代码）

$from_user = $_W['fans']['from_user'];
$avatar = $_W['fans']['tag']['avatar'];
$nickname = $_W['fans']['nickname'];

load()->model('account');
$_W['account'] = account_fetch($_W['acid']);
$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
if ($_W['account']['level'] != 4) {
    $from_user = $cookie['openid'];
    $avatar = $cookie['avatar'];
    $nickname = $cookie['nickname'];
}

//网页授权借用结束（特殊代码）
if (empty($from_user)) {
    $this->message(array("success" => 0, "msg" => '获取不到您的OpenID,请从新进入活动页面'), "");
}
if($from_user!=$openid){
    $data = array(
        'success' => 5,
        'msg' => '获取资料出错，请重新进入！',
    );
    echo json_encode($data);
    exit();
}

$now = time();

$toupiao = pdo_fetchall("select * from " . tablename('haoman_dpm_jiabing') . " where rid = :rid and `name` LIKE :keyword and status =0 order by `id` desc", array(':rid' => $rid,':keyword' => "%{$key}%"));

if($toupiao==false){
    $data = array(
        's' => 1,
        'msg' => '没搜索到',
        'list' => '没搜索到,您想要的结果',
    );
    echo json_encode($data);
    exit();
}else{
    $aa='';
    foreach($toupiao as $v){
        if(empty($v['img'])){
            $v['img']="../addons/haoman_dpm/img9/582c1db1c84c3.jpg";
            $v['avatar']="../addons/haoman_dpm/img9/ava_default.jpg";
        }else{
            $v['img'] = tomedia($v['img']);
            $v['avatar'] = tomedia($v['avatar']);
        }
        $aa .='<div class="ml">';
        $aa .='<div class="sh">';
        $aa .='<li class="p" style="margin-top: 5px;"><img src="'.$v['img'].'"></li>';
        $aa .='<li class="na" style="height:40px;margin: 4px;"><span style="display: inline-block;height: auto">'.$v['description'].'</span></li>';
        $aa .='<li class="no" style="height:60px;text-align:left;margin-left: 5px;line-height: 60px;"><img src="'.$v['avatar'].'" style="height: 44px;width: 44px;border-radius: 100%;"><span style="display: inline-block;margin-left: 10px;">'.$v['name'].'</span></li>';
        $aa .='</div>';
        $aa .='</div>';
    }

    $data = array(
        's' => 0,
        'list' => $aa,
    );
    echo json_encode($data);
    exit();
}