<?php
error_reporting(0);
!defined('GARCIA_PREFIX') && define('GARCIA_PREFIX', 'jy_qsc_');
require '../../../../framework/bootstrap.inc.php';
load()->web('common');
load()->classs('coupon');
// pdo_insert("glogs",array('log'=>json_encode($_POST)));
$trade_no = $_POST['trade_no'];
$tid = $_POST['out_trade_no'];
	$_W['uniacid'] = $_W['weid'] = intval($_POST['body']);
$sql ="SELECT a.*,b.nickname,c.name as t1,d.openid as touser,b.id as mid FROM ".tablename(GARCIA_PREFIX."paylog")." a
LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." c on a.fid=c.id
LEFT JOIN ".tablename(GARCIA_PREFIX."member")." d on c.mid=d.id
where a.weid=".$_W['uniacid']." and a.tid='".$tid."'";
 $config = pdo_fetch($sql);
 if($config['status']==0){
    $fee = $config['fee'];
    $fb = pdo_fetchcolumn('SELECT has_money FROM '.tablename(GARCIA_PREFIX.'fabu')." where weid=".$_W['uniacid']." and id=".$config['fid']);
    $_fee = $fb+$fee;
    pdo_update(GARCIA_PREFIX."fabu",array('has_money'=>$_fee),array('id'=>$config['fid']));
    pdo_update(GARCIA_PREFIX."paylog",array('upbdate'=>time(),'status'=>1,'isalipay'=>1,'transaction_id'=>$trade_no),array('id'=>$config['id']));
 }

 ?>
