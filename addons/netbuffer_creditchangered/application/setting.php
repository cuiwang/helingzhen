<?php
defined ( 'IN_IA' ) or exit('Access Denied,your ip is:'.$_SERVER['REMOTE_ADDR'].',We have recorded the source of attack.');
require_once APP_CLASS_PATH."lib/PHPZIP.php";
class Setting extends WeModule{
	protected function sets($settings) {
		global $_GPC,$_W;
		load()->func('file');
		if (checksubmit()) {
			$arr_json="";
			if(!empty($_FILES['nbfwpaycert']['tmp_name'])){
				$ext=pathinfo($_FILES['nbfwpaycert']['name'], PATHINFO_EXTENSION);
				if(strtolower($ext)!="zip"){
					message("[文件格式错误]请上传您的微信支付证书哦~", '', 'error');
				}
				$wxcertdir=APP_CLASS_PATH."cert/".$_W["uniacid"];
				if(!is_dir($wxcertdir)){
					mkdir($wxcertdir);
				}
				if(is_dir($wxcertdir)){
					if(!is_writable($wxcertdir)){
						message("请保证目录：[".APP_CLASS_PATH."]可写");
					}
				}
				$save_file=$wxcertdir."/".$_W["uniacid"].".".$ext;
				file_move($_FILES['nbfwpaycert']['tmp_name'], $save_file);
				$archive   = new PHPZIP();
				$archive->unzip($save_file,$wxcertdir); // 把zip中的文件解压到目录中
				$certpath=$wxcertdir."/apiclient_cert.pem";
				$keypath=$wxcertdir."/apiclient_key.pem";
				$arr=array("certpath"=>$certpath,"keypath"=>$keypath);
				$arr_json=json_encode($arr);
				file_delete($save_file);
			}
			if($arr_json==""){
				$arr_json=$settings["nbfwxpaypath"];
			}
			$cfg = array(
				'nbfchangemoney' => $_GPC['nbfchangemoney'],
				'nbfhelpgeturl'=>$_GPC['nbfhelpgeturl'],
				'nbfwxpaypath'=>$arr_json,
				'nbfmaxchangemoney'=>$_GPC['nbfmaxchangemoney']
			);
			if ($this->saveSettings($cfg)) {
				message('保存成功', 'refresh');
			}
		}
		include $this->template('web/settings');
	}
}
