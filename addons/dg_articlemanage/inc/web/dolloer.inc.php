<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/8/18
 * Time: 10:49
 */
global $_GPC, $_W;
$op= $_GPC['op'] ? $_GPC['op'] : 'display';
$uniacid= $_W['uniacid'];
$pindex= max(1, intval($_GPC['page']));
$condition="";
if(!empty($_GPC['keyword'])) {
    $condition .= " AND title LIKE '%".$_GPC['keyword']."%'";
}
$psize= 20; //每页显示
$sql="SELECT A1.*,A2.title FROM ".tablename('dg_article_payment')." A1 INNER JOIN ".tablename('dg_article')." A2 ON A1.article_id=A2.id WHERE order_status=1 AND A2.uniacid=:uniacid ".$condition." ORDER BY ID DESC LIMIT ".($pindex -1) * $psize.','.$psize;
$list=pdo_fetchall($sql,array(':uniacid' => $uniacid));
load()->model('mc');

foreach($list as &$v) {
    if(!empty($v['openid'])) {
        $user = mc_fetch($v['openid'], array('realname', 'nickname', 'mobile', 'email', 'avatar'));
    }
    if(empty($user)){
        $user=mc_fansinfo($v['openid']);
    }
        $v['nickname']=$user['nickname'];


    unset($user);
}

$amount=pdo_fetch("SELECT COUNT(0) mcount,IFNULL(SUM(A1.pay_money),0) mmoney FROM ".tablename('dg_article_payment')." A1 INNER JOIN ".tablename('dg_article')." A2 ON A1.article_id=A2.id WHERE order_status=1 AND A2.uniacid=:uniacid ".$condition,array(':uniacid' => $uniacid));
$total=$amount['mcount'];
$sum=$amount['mmoney'];
$pager= pagination($total, $pindex, $psize);

include $this->template('dolloer');
