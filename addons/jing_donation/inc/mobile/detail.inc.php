<?php
defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;
$this->checkuser();
$id = intval($_GPC['id']);
$item = pdo_fetch("SELECT * FROM ".tablename($this->t_donation)." WHERE id=:id",array(':id'=>$id));
if (empty($item)) {
	message('您访问的活动不存在',referer(),'error');
}
$orders = pdo_fetchall("SELECT *,sum(price) as pricetotal FROM " . tablename($this->t_order) . " WHERE uniacid=:uniacid AND did=:did AND status=1 GROUP BY openid ORDER BY pricetotal DESC LIMIT {$item['numbers']}",array(':uniacid'=>$_W['uniacid'],':did'=>$id));
$title = $item['title'];
include $this->template('detail');

?>