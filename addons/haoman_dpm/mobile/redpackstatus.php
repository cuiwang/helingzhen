<?php
global $_GPC,$_W;

$rid = intval($_GPC['rid']);
$uid = $_GPC['uid'];
$from_user = $_W['fans']['from_user'];
$hbid = $_GPC['id'];
//$lt = $_GPC['lt'];
$uniacid = $_W['uniacid'];

load()->model('account');
$_W['account'] = account_fetch($_W['acid']);
$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
if ($_W['account']['level'] != 4) {
    $from_user = $cookie['openid'];
}


if($uid!=$from_user||empty($hbid)){
    $tp = '<p class="money"><span>数据异常！</span></p>';
}else{

    $reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));

    $fans = pdo_fetch("select id,from_user,is_back,avatar,nickname,totalnum,fanshbnum from " . tablename('haoman_dpm_fans') . " where rid =:rid and from_user = :from_user ", array(':from_user' => $from_user,':rid'=>$rid));

    $hbaward = pdo_fetch("select id,credit from " . tablename('haoman_dpm_hb_award') . " where  rid =:rid and from_user = :from_user and prize=:prize", array(':from_user' => $from_user,':prize'=>$hbid,':rid'=>$rid));

    $hb = pdo_fetch("select * from " . tablename('haoman_dpm_hb_log') . " where  id=:id", array(':id'=>$hbid));
    $hbs = pdo_fetch("select * from " . tablename('haoman_dpm_hb_setting') . " where  id=:id", array(':id'=>$hbid));
    if(empty($hbaward)){

        if(empty($hb)){
            $tp = '<p class="money"><span>来晚了</span></p>';
        }else{


            if($hb['hbnum']-$hb['usesnum']<1){
                $tp = '<p class="money"><span>红包已派完!</span></p>';
            }else{

                if($hb['desknum']==1){
                    $money = $hb['actual_money']-$hb['usemoney'];
                    $money= $money*100;
                    if($money>0){
                        if($hb['hbnum']-$hb['usesnum']==1){
                            $credit  =$money/100;
                        }else{
                            $credit = (mt_rand(1, $money))/100;
                        }

                    }else{
                        $tp = '<p class="money"><span>红包已派完!!</span></p>';
                    }

                }else{

                    if($hb['actual_money']>0.01&&$hb['hbnum']!=0){
                        $a=$hb['actual_money']*100;
                        $b=$hb['hbnum'];
                        $credit = intval(intval($a)/intval($b));
                        $credit = $credit/100;
                        if($credit<0.01){
                            $credit=0.01;
                        }
                    }else{
                        $credit=0.01;
                    }
                }
                if($credit>0){

                    pdo_update('haoman_dpm_hb_log', array('usesnum' => $hb['usesnum'] + 1,'usemoney'=>$hb['usemoney'] + $credit), array('id' => $hb['id']));

                    if($hbs['hbtype']==1){
                        $insert = array(
                            'uniacid' => $uniacid,
                            'rid' => $rid,
                            'from_user' => $from_user,
                            'avatar' => $fans['avatar'],
                            'nickname' => $fans['nickname'],
                            'awardname' => 1,
                            'awardsimg' => 0,
                            'prizetype' => 0,
                            'credit' => $credit,
                            'prize' => $hb['id'],
                            'createtime' => time(),
                            'consumetime' => 0,
                            'status' => 2,
                        );

                        $temps = pdo_update('haoman_dpm_fans', array('fanshbnum' => $fans['fanshbnum'] + $credit), array('id' => $fans['id']));
                        if($temps){
                            $temp = pdo_insert('haoman_dpm_hb_award', $insert);
                            $tp = '<p class="money"><span>￥</span>'.$credit.'元</p>';
                        }else{
                            $tp = '<p class="money"><span>红包已派完!?</span></p>';
                        }
//                        $temp = pdo_insert('haoman_dpm_hb_award', $insert);
//                        $tp = '<p class="money"><span>￥</span>'.$credit.'元</p>';


                    }else{
                        if ($credit < 1) {
                            //中奖记录保存
                            $insert = array(
                                'uniacid' => $uniacid,
                                'rid' => $rid,
                                'from_user' => $from_user,
                                'avatar' => $fans['avatar'],
                                'nickname' => $fans['nickname'],
                                'awardname' => 1,
                                'awardsimg' => 0,
                                'prizetype' => 0,
                                'credit' => $credit,
                                'prize' => $hb['id'],
                                'createtime' => time(),
                                'consumetime' => 0,
                                'status' => 1,
                            );

//                        $this->message(array("success" => 2, "msg" =>"4343214324324"), "");
                            $temps = pdo_update('haoman_dpm_fans',array('totalnum' => ($fans['totalnum']+$credit*100)),array('id' => $fans['id']));

                            if($temps){
                                $temp = pdo_insert('haoman_dpm_hb_award', $insert);
                                $tp = '<p class="money"><span>￥</span>'.$credit.'元</p>';
                            }else{
                                $tp = '<p class="money"><span>红包已派完!?</span></p>';
                            }


                            //保存中奖人信息到fans中
                            //保存中奖人信息到fans中
                        } else {
                            //中奖记录保存
                            $insert = array(
                                'uniacid' => $uniacid,
                                'rid' => $rid,
                                'from_user' => $from_user,
                                'avatar' => $fans['avatar'],
                                'nickname' => $fans['nickname'],
                                'awardname' => 0,
                                'awardsimg' => 0,
                                'prizetype' => 0,
                                'credit' => $credit,
                                'prize' => $hb['id'],
                                'createtime' => time(),
                                'consumetime' => 0,
                                'status' => 2,
                            );


                            $record['fee'] = $credit; //红包金额；
                            $record['openid'] = $from_user;
                            $user['nickname'] = $fans['nickname'];

                            /*红包新商户订单号生成方式*/
                            $user['fansid'] = $rid.$fans['id'];
                            /*红包新商户订单号生成方式*/

                            $sendhongbao = $this->sendhb($record, $user);


                            $temp = pdo_insert('haoman_dpm_hb_award', $insert);
                            $awardid = pdo_insertid();


                            if ($sendhongbao['isok']) {
                                //更新提现状态
                                $tp = '<p class="money"><span>￥</span>'.$credit.'元</p>';
                                //保存中奖人信息到fans中

                            } else {
                                pdo_update('haoman_dpm_hb_award', array('status' => 1), array('id' => $awardid));
                                pdo_update('haoman_dpm_fans', array('totalnum' => ($fans['totalnum']+$credit*100)), array('id' => $fans['id']));

                                $inserts = array(
                                    'uniacid' => $uniacid,
                                    'rid' => $rid,
                                    'from_user' => $from_user,
                                    'money' => $credit,
                                    'why_error' => $sendhongbao['error_msg']."**".$sendhongbao['code'],
                                    'createtime' => time(),
                                );

                                pdo_insert('haoman_dpm_whyerror', $inserts);

                                if(!empty($reply['hb_lose_openid'])){
                                    $actions = "亲爱的管理员，有粉丝红包领取失败！\n原因：".$sendhongbao['error_msg'];
                                    $this->sendText($reply['hb_lose_openid'],$actions);
                                }
                                $tp = '<p class="money"><span>￥</span>'.$credit.'元</p>';
//                           $data = array(
//                               'success' => 3,
//                               //  'msg'=>$sendhongbao['error_msg'],
//                               'msg' => '红包发送失败,已为您存入我的奖品中！',
//                           );
//
//                           $this->message($data);
//                           exit();
                            }

                        }
                    }
                }



            }
        }

    }else{
        $tp = '<p class="money"><span>￥</span>'.$hbaward['credit'].'元</p>';
    }

}




if(empty($tp)){
    $tp='<p class="money">参数错误！</p>';
}
    $aa='';
    $aa .='<div class="mzh_modal_alert" style="display: block;">';
    $aa .='<div class="mzh_modal_alert_dialog" style="background-color:rgba(0,0,0,0);box-shadow:none;">';
    $aa .='<span class="am-icon-close close"  style="color: #FFFFFF;"></span>';
    $aa .='<div class="hb_panel">';
    $aa .='<div class="hb_status_panel">';
    $aa .='<p>';
    $aa .='<img src="'.tomedia($hb['avatar']).'" class="avatar" >';
    $aa .='</p>';
    $aa .='<p class="nickname">'.$hb['nickname'].'的红包</p>';
    $aa .='<p class="hb_ctx">'.$hb['says'].'</p>';
    $aa .=$tp;
    $aa .='<p class="show_log">看看大家手气</p>';
    $aa .='<p class="hbb_tip">红包金额可从“账户余额”中提现到您的微信零钱</p>';
    $aa .='</div>';
    $aa .='</div>';
    $aa .='</div>';
    $aa .='</div>';

$result = $aa;

$this->message($result);