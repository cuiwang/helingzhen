<?php
/**
 * 米波现场模块定义
 *
 * @author 赞木
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
class Meepo_xianchangModule extends WeModule {
	public $basic_config_table = 'meepo_xianchang_basic_config';
	public $user_table = 'meepo_xianchang_user';
	public $xc_table = 'meepo_xianchang_rid';
	public $wall_table = 'meepo_xianchang_wall';
	public $wall_config_table = 'meepo_xianchang_wall_config';
	public $cookie_table = 'meepo_xianchang_cookie';
	public $qd_table = 'meepo_xianchang_qd';
	public $qd_config_table = 'meepo_xianchang_qd_config';
	public $lottory_award_table = 'meepo_xianchang_lottory_award';
	public $lottory_user_table = 'meepo_xianchang_lottory_user';
	public $lottory_config_table = 'meepo_xianchang_lottory_config';
	public $jb_table = 'meepo_xianchang_jb';
	public $vote_table = 'meepo_xianchang_vote';
	public $vote_xms_table = 'meepo_xianchang_vote_xms';
	public $vote_record = 'meepo_xianchang_vote_record';
	public $shake_rotate_table = 'meepo_xianchang_shake_rotate';
	public $shake_user_table = 'meepo_xianchang_shake_user';
	public $shake_config_table = 'meepo_xianchang_shake_config';
	public $xc2_table = 'meepo_xianchang_xc';
	public $bd_manage_table = 'meepo_xianchang_bd';
	public $bd_data_table = 'meepo_xianchang_bd_data';
	public $sd_config_table = 'meepo_xianchang_3d_config';
	public $redpack_config_table = 'meepo_xianchang_redpack_config';
	public $redpack_user_table = 'meepo_xianchang_redpack_user';
	public $redpack_rotate_table = 'meepo_xianchang_redpack_rotate';
	public $ddp_config_table = 'meepo_xianchang_ddp_config';
	public $cjx_config_table = 'meepo_xianchang_cjx_config';
	public $zjd_config_table = 'meepo_xianchang_zjd_config';
	public $ddp_record_table = 'meepo_xianchang_ddp_record';
	public $xysjh_record_table = 'meepo_xianchang_xysjh_record';
	public function settingsDisplay($settings) {
		global $_GPC, $_W;
		load()->func('tpl');
		
		if(checksubmit()) {
           
			$cfg = array();
			$cfg['controls'] = iserializer($_GPC['controls']);
			$cfg['special_control_on'] = intval($_GPC['special_control_on']);
			$cfg['shake_max_man']=intval($_GPC['shake_max_man']);
			$cfg['redpack_max_man'] = intval($_GPC['redpack_max_man']);
			$cfg['founder_control_time'] = intval($_GPC['founder_control_time']);
			$cfg['founder_control_shake_man'] = intval($_GPC['founder_control_shake_man']);
			$cfg['founder_control_redpack_man'] = intval($_GPC['founder_control_redpack_man']);
			$cfg['activity_hours'] = intval($_GPC['activity_hours']);
			$cfg['user_avatar'] = $_GPC['user_avatar'];
			$cfg['socket_url'] = $_GPC['socket_url'];
			if($this->saveSettings($cfg)) {
				message('保存成功', 'refresh');
			}
		}
		if(!isset($settings['redpack_max_man'])) {
			$settings['redpack_max_man'] = 200;
		}
		if(!isset($settings['shake_max_man'])) {
			$settings['shake_max_man'] = 200;
		}
		if(!isset($settings['activity_hours'])) {
			$settings['activity_hours'] = 2;
		}
		if(!isset($settings['founder_control_time'])) {
			$settings['founder_control_time'] = 1;
		}
		if(!isset($settings['founder_control_redpack_man'])) {
			$settings['founder_control_redpack_man'] = 1;
		}
		if(!isset($settings['founder_control_shake_man'])) {
			$settings['founder_control_shake_man'] = 1;
		}
		
		if(!isset($settings['user_avatar'])) {
			$settings['user_avatar'] = $_W['siteroot'].'addons/meepo_xianchang/cdhn80.jpg';
		}
		if(!isset($settings['special_control_on'])) {
			$settings['special_control_on'] = 0;
		}
		if(!isset($settings['controls'])) {
			$settings['controls'] = array();
		}else{
			$settings['controls'] = iunserializer($settings['controls']);
		}
		
		load()->func('tpl');
		include $this->template('setting');
	}
	public function fieldsFormDisplay($rid = 0) {
		global $_W;
		load()->func('tpl');
		if (!empty($rid)) {
			$reply = pdo_fetch("SELECT * FROM ".tablename($this->xc_table)." WHERE rid = :rid", array(':rid' => $rid));
			$reply['controls'] = iunserializer($reply['controls']);
		}else{
			$reply['controls'] = array();
			$reply['pass_word'] = '5678';
			$reply['is_share'] = 1;
		}
		
		if(!$reply['status']){
			$reply['status'] = 1;
		}
		if(!$reply['gz_must']){
			$reply['gz_must'] = 0;
		}
		
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		global $_W,$_GPC;
		$id = intval($_GPC['reply_id']);
		$insert = array();
		$insert = array(
			'rid' => $rid,
			'weid'=>$_W['uniacid'],
			'title' => $_GPC['title'],
			'status' =>intval($_GPC['status']),
			'pass_word' =>$_GPC['pass_word'],
			'gz_url'=>$_GPC['gz_url'],
			'gz_must'=>intval($_GPC['gz_must']),
			'is_share'=>intval($_GPC['is_share']),
		);
		$insert['controls'] = iserializer($_GPC['controls']);
		$insert['start_time'] = strtotime($_GPC['ac_times']['start']);
		$insert['end_time'] = strtotime($_GPC['ac_times']['end']);
		if (empty($id)) {
			pdo_insert($this->xc_table, $insert);
		} else {
			pdo_update($this->xc_table, $insert, array('id' => $id));
		}
	}

	public function ruleDeleted($rid) {
		global $_W;
		pdo_delete($this->xc_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->basic_config_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->user_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->qd_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->qd_config_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->wall_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->wall_config_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->lottory_award_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->lottory_config_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->jb_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->vote_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->vote_xms_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->vote_record,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->shake_rotate_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->shake_user_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->shake_config_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->xc2_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->bd_manage_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->bd_data_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->sd_config_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->redpack_config_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->redpack_user_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->redpack_rotate_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->ddp_config_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->ddp_record_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->xysjh_record_table,array('rid'=>$rid,'weid'=>$_W['uniacid']));
		return true;
	}


}