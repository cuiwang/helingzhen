<?php
/**
 * 微房产
 * QQ：792454007
 *
 * @author 大路货
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class Lxy_buildproModule extends WeModule {
	public $table_reply  = 'lxy_buildpro_reply';
	public $table_list  = 'lxy_buildpro_head';
	
	public function fieldsFormDisplay($rid = 0) {
		global $_W;
		if($rid) {
			$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid", array(':rid' => $rid));
			$sql = 'SELECT * FROM ' . tablename($this->table_list) . ' WHERE `weid`=:weid AND `hid`=:hid';
			$activity = pdo_fetch($sql, array(':weid' => $_W['uniacid'], ':hid' => $reply['hid']));
			$showpicurl=$this->getpicurl($activity['pic']);
		}
		include $this->template('form');
			
	}
	
	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		global $_W, $_GPC;
		$hid = intval($_GPC['activity']);
		if(!empty($hid)) {
			$sql = 'SELECT * FROM ' . tablename($this->table_list) . " WHERE `hid`=:hid";
			$params = array();
			$params[':hid'] = $hid;
			$activity = pdo_fetch($sql, $params);
			return ;
			if(!empty($activity)) {
				return '';
			}
		}
		return '没有选择合适的楼盘';
	}
	
	public function fieldsFormSubmit($rid) {
		global $_GPC;
		$hid = intval($_GPC['activity']);
		$record = array();
		$record['hid'] = $hid;
		$record['rid'] = $rid;
		$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid", array(':rid' => $rid));
		if($reply) {
			pdo_update($this->table_reply, $record, array('id' => $reply['id']));
		} else {
			pdo_insert($this->table_reply, $record);
		}
	
	}
	
	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		pdo_delete($this->table_reply, array('rid' => $rid));
	}
	private  function getpicurl($url)
	{
		global $_W;
		if($url)
		{
			return tomedia($url);
		}
		else
		{
			return $_W['siteroot'].'addons/lxy_buildpro/template/img/build_home.png';
		}
	}	
	
}
