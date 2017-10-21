<?php
defined ( 'IN_IA' ) or exit('Access Denied,your ip is:'.$_SERVER['REMOTE_ADDR'].',We have recorded the source of attack.');
$uid=mc_openid2uid($_W['fans']['from_user']);
$credit = mc_credit_fetch($uid);
$originCredit=$credit['credit1'];
include $this->template('confirm');