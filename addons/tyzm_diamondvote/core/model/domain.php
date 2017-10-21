<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
} 
class Tyzm_Domain{
	public function __construct() {
		global $_W;
	}

	public function get_domain($rid,$type=0){
		global $_W;
		//随机取IP地址
		if(empty($type)){
			$list = pdo_fetch("SELECT domain,extensive FROM ".tablename('tyzm_diamondvote_domainlist')." WHERE rid = :rid AND status=:status AND type=:type   order by rand() limit 1", array(':rid' => $rid,':status'=>0,':type'=>0));
			if($list['extensive']){
				$list['domain']=random(6).".".$list['domain'];
			}
		}else{
			$list = pdo_get('tyzm_diamondvote_domainlist', array('rid' => $rid,'status'=>0,'type'=>1), array('domain'));
		}
		return $list['domain'];

	}


	public function randdomain($rid,$type=0){
		global $_W;
		//随机跳转域名
		$mdomain=$this->get_domain($rid,1);
		$host=$_SERVER['HTTP_HOST'];
		if(!empty($mdomain)){
				if($_W['openid'] && empty($type)){
					$randd=$this->get_domain($rid);
		            if($host==$mdomain && !empty($randd)){
		            	$newUrl = "http://" . $randd . $_SERVER['REQUEST_URI'];
		            	header("location: " . $newUrl);
		            }
				}
				if($type && $host!=$mdomain){
					$newUrl = "http://" . $mdomain . $_SERVER['REQUEST_URI'];
		            	header("location: " . $newUrl);
				}
		}
	}
}



