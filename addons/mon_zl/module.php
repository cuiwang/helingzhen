<?php
/**
 */
defined('IN_IA') or exit('Access Denied');

define("MON_ZL", "mon_zl");
define("MON_ZL_RES", "../addons/" . MON_ZL . "/");
require_once IA_ROOT . "/addons/" . MON_ZL . "/dbutil.class.php";
require_once IA_ROOT . "/addons/" . MON_ZL . "/monUtil.class.php";

class Mon_ZlModule extends WeModule
{

	public $weid;

	public function __construct()
	{
		global $_W;
		$this->weid = IMS_VERSION < 0.6 ? $_W['weid'] : $_W['uniacid'];
	}

	public function fieldsFormDisplay($rid = 0)
	{
		global $_W;

		if (!empty($rid)) {
			$reply = DBUtil::findUnique(DBUtil::$TABLE_ZL, array(":rid" => $rid));

			$reply['starttime'] = date("Y-m-d  H:i", $reply['starttime']);
			$reply['endtime'] = date("Y-m-d  H:i", $reply['endtime']);
			$rule_items=unserialize($reply['zl_rule']);
		}

		load()->func('tpl');
		include $this->template('form');


	}

	public function fieldsFormValidate($rid = 0)
	{
		global $_GPC, $_W;


		return '';
	}

	public function fieldsFormSubmit($rid)
	{
		global $_GPC;
		$zid = $_GPC['zid'];


		$zl_rules=array();
		$rule_ids=$_GPC['rule_id'];
		$rule_points=$_GPC['rule_point'];
		$rule_point_starts=$_GPC['rule_point_start'];
		$rule_point_ends = $_GPC['rule_point_end'];

		if(is_array($rule_ids)){

			foreach($rule_ids as $key=>$value){
				$d=array(
					'rule_point'=>$rule_points[$key],
					'rule_point_start'=>$rule_point_starts[$key],
					'rule_point_end'=>$rule_point_ends[$key]
				);
				$zl_rules[]=$d;
			}

		}


		$data = array(
			'rid' => $rid,
			'weid' => $this->weid,
			'title' => $_GPC['title'],
			'starttime' => strtotime($_GPC['starttime']),
			'endtime' => strtotime($_GPC['endtime']),
			'follow_url' => $_GPC['follow_url'],
			'follow_btn_name' =>$_GPC['follow_btn_name'],
			'title_bg' => $_GPC['title_bg'],
			'share_bg' =>$_GPC['share_bg'],
			'copyright' => $_GPC['copyright'],
			'randking_count' =>$_GPC['randking_count'],
			'follow_dlg_tip' =>$_GPC['follow_dlg_tip'],
			'new_title' => $_GPC['new_title'],
			'new_icon' => $_GPC['new_icon'],
			'new_content' => $_GPC['new_content'],
			'share_title' => $_GPC['share_title'],
			'share_icon' => $_GPC['share_icon'],
			'share_content' => $_GPC['share_content'],
			'rule' => htmlspecialchars_decode($_GPC['rule']),
			'award' => htmlspecialchars_decode($_GPC['award']),
			'content' => htmlspecialchars_decode($_GPC['content']),
			'awardaddress' => $_GPC['awardaddress'],
			'top_banner' => $_GPC['top_banner'],
			'top_banner_url' => $_GPC['top_banner_url'],
			'top_banner_title' => $_GPC['top_banner_title'],
			'top_banner_show' =>$_GPC['top_banner_show'],
			'updatetime' => TIMESTAMP,
			'zl_follow_enable' => $_GPC['zl_follow_enable'],
			'join_follow_enable' =>$_GPC['join_follow_enable'],
			'udetail_eable' => $_GPC['udetail_eable'],
			'telname' => '手机号',
			'top_tag' => $_GPC['top_tag'],
			'contact_tel' =>$_GPC['contact_tel'],
			'contact_name' =>$_GPC['contact_name'],
			'startp' => $_GPC['startp'],
			'maxp' =>$_GPC['maxp'],
			'join_btn_name' =>$_GPC['join_btn_name'],
			'uzl_btn_name' =>$_GPC['uzl_btn_name'],
			'fzl_btn_name' => $_GPC['fzl_btn_name'],
			'f_zl_limit' => $_GPC['f_zl_limit'],
			'zlunit' =>$_GPC['zlunit'],
			'syncredit' => $_GPC['syncredit'],
			'zl_rule'=>serialize($zl_rules),
			'f_zl_limit_tip' => $_GPC['f_zl_limit_tip'],
			'f_day_limit' => $_GPC['f_day_limit'],
			'f_day_limit_tip' => $_GPC['f_day_limit_tip'],
			'f_diff_limt' => $_GPC['f_diff_limt'],
			'f_diff_tip' => $_GPC['f_diff_tip'],
			'ip_limit' => $_GPC['ip_limit'],
			'ip_limit_tip' => $_GPC['ip_limit_tip'],
			'tmp_enable' => $_GPC['tmp_enable'],
			'tmpId' => $_GPC['tmpId']
		);

		if (empty($zid)) {
			$data['createtime'] = TIMESTAMP;
			DBUtil::create(DBUtil::$TABLE_ZL, $data);
		} else {
			DBUtil::updateById(DBUtil::$TABLE_ZL, $data, $zid);
		}



		return true;
	}

	public function ruleDeleted($rid)
	{
		$zl = DBUtil::findUnique(DBUtil::$TABLE_ZL, array(":rid" => $rid));
		pdo_delete(DBUtil::$TABLE_ZL_USER, array("zid" => $zl['id']));
		pdo_delete(DBUtil::$TABLE_ZL_FRIEND, array('zid' => $zl['id']));
	}


}