<?php
defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;
$this->checkuser();
$id = intval($_GPC['id']);
$item = pdo_fetch("SELECT * FROM ".tablename($this->t_donation)." WHERE id=:id and enabled=1",array(':id'=>$id));
if (empty($item)) {
	message('您访问的活动不存在',referer(),'error');
}
$user = pdo_fetch("SELECT * FROM ".tablename($this->t_user)." WHERE uniacid=:uniacid AND openid=:openid",array(':uniacid'=>$_W['uniacid'],':openid'=>$_W['openid']));
//$orders = pdo_fetchall("SELECT o.*,sum(o.price) as price,u.nickname,u.avatar,u.city FROM " . tablename($this->t_order) . " o LEFT JOIN " . tablename($this->t_user) . " u ON u.openid=o.openid WHERE o.uniacid=:uniacid AND u.uniacid=:uniacid AND o.did=:did AND o.status=1 Group by o.openid ORDER BY price DESC LIMIT {$item['numbers']}",array(':uniacid'=>$_W['uniacid'],':did'=>$id));
$orders = pdo_fetchall("SELECT *,sum(price) as pricetotal FROM " . tablename($this->t_order) . " WHERE uniacid=:uniacid AND did=:did AND status=1 GROUP BY openid ORDER BY pricetotal DESC LIMIT {$item['numbers']}",array(':uniacid'=>$_W['uniacid'],':did'=>$id));
//print_r($orders);exit();
$yxz_list = pdo_fetchall("SELECT y.*,u.nickname,u.avatar,u.city FROM " . tablename($this->t_yxz) . " y LEFT JOIN " . tablename($this->t_user) . " u ON u.openid=y.openid WHERE y.uniacid=:uniacid AND y.did=:did ORDER BY yxz DESC LIMIT 10",array(':uniacid'=>$_W['uniacid'],':did'=>$id));
$dynamic_list = pdo_fetchall("SELECT * FROM " . tablename($this->t_dynamic) . " WHERE uniacid=:uniacid AND did=:did ORDER BY createtime DESC LIMIT 10",array(':uniacid'=>$_W['uniacid'],':did'=>$id));
$type = isset($_GPC['type']) ? intval($_GPC['type']) : 2;
$title = $item['title'];
include $this->template('info');
?>