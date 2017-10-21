<?php
/**
 * 
 *
 * @author  codeMonkey
 * qq:631872807
 * @url
 */
defined('IN_IA') or exit('Access Denied');

define("MON_FOOL", "mon_fool");

require_once IA_ROOT . "/addons/" . MON_FOOL . "/CRUD.class.php";

class Mon_FoolModule extends WeModule {

	public $weid;
	public function __construct() {
		global $_W;
		$this->weid = IMS_VERSION<0.6?$_W['weid']:$_W['uniacid'];
	}
	

	public function fieldsFormDisplay($rid = 0) {
		global $_W;

		if(!empty($rid)){
			$reply=CRUD::findUnique(CRUD::$table_fool,array(":rid"=>$rid));




		}


		load()->func('tpl');


		include $this->template('form');


	}
	public function fieldsFormValidate($rid = 0) {



		return '';
	}
	public function fieldsFormSubmit($rid) {
		global $_GPC, $_W;


		$fid=$_GPC['fid'];

		$data=array(
			'title'=>$_GPC['title'],
			'rid'=>$rid,
			'follow_url'=>$_GPC['follow_url'],
			'weid'=>$this->weid,
			'new_icon'=>$_GPC['new_icon'],
			'new_title'=>$_GPC['new_title'],
			'new_content'=>$_GPC['new_content'],
			'share_icon'=>$_GPC['share_icon'],
			'share_title'=>$_GPC['share_title'],
			'share_content'=>$_GPC['share_content'],
			'createtime'=>TIMESTAMP
		);

		if(empty($fid)){

			CRUD::create(CRUD::$table_fool,$data);


		}else{

			CRUD::updateById(CRUD::$table_fool,$data,$fid);
		}


		return true;
	}
	public function ruleDeleted($rid) {

		$sin=CRUD::findUnique(CRUD::$table_fool,array(":rid"=>$rid));


	}
    
    
    
   

}