<?php 
if(!defined('IN_IA')){
	exit('Access Denied');
}

class Welian_Indiana_Log{
	public function WL_log($filename,$param,$filedata,$uniacid=''){
		global $_W;
		if($uniacid != ''){
			$_W['uniacid'] =$uniacid;
		}
		$url_log = WELIAM_INDIANA."log/".date('Y-m-d',time())."/".$filename.".log";
		$url_dir = WELIAM_INDIANA."log/".date('Y-m-d',time());
		if (!file_exists($url_dir)) {
			mkdir($url_dir);
		}
		file_put_contents($url_log, var_export('/========================================='.date('Y-m-d H:i:s',time()).'============================================/', true).PHP_EOL, FILE_APPEND);
		file_put_contents($url_log, var_export('******公众号【'.$_W['uniacid'].'】记录'.$param.'*****', true).PHP_EOL, FILE_APPEND);
		file_put_contents($url_log, var_export($filedata, true).PHP_EOL, FILE_APPEND);
	}
}