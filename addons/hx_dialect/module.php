<?php
/**
 * 方言听力版模块定义
 *
 * @author 华轩科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Hx_dialectModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W;
		load()->func('tpl');
		if($rid==0){
			$reply = array(
				's_sucai'=> 'http://mp.weixin.qq.com/s?__biz=MzA4MDYyNzgyOA==&mid=200533876&idx=1&sn=e3280d89341bdeeef9610c9527f5bb6e#rd',
				'num'	=>	'10',
				'py_5'	=>	'屁的不听清，我看你只吧是蒙滴哦',
				'py_4'	=>	'屁的有卖的，不歹天门蛮多年了吧。',
				'py_3'	=>	'你真敢和别人说你是天门人，错打这多？',
				'py_2'	=>	'马虎象，打这个分只有这评价了！',
				'py_1'	=>	'果然深知我大天门话的精髓，么么哒，奖励一个！',
			);
		}else{
			$reply = pdo_fetch("SELECT * FROM ".tablename('hx_dialect_reply')." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		}
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_GPC, $_W;
		$id = intval($_GPC['reply_id']);
		$insert = array(
			'rid'		=> $rid,
			'uniacid'	=> $_W['uniacid'],
			'r_name'	=> $_GPC['r_name'],
			'r_title'	=> $_GPC['r_title'],
			'thumb'		=> $_GPC['thumb'],
			'num'		=> $_GPC['num'],
			's_title'	=> $_GPC['s_title'],
			's_icon'	=> $_GPC['s_icon'],
			's_des'		=> $_GPC['s_des'],
			's_cancel'	=> $_GPC['s_cancel'],
			's_share'	=> $_GPC['s_share'],
			's_sucai'	=> $_GPC['s_sucai'],
			'py_1'		=> $_GPC['py_1'],
			'py_2'		=> $_GPC['py_2'],
			'py_3'		=> $_GPC['py_3'],
			'py_4'		=> $_GPC['py_4'],
			'py_5'		=> $_GPC['py_5'],
		);
		//print_r($insert);
		if (empty($id)) {
			pdo_insert('hx_dialect_reply', $insert);

		}else{
			pdo_update('hx_dialect_reply', $insert, array('id' => $id));
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		global $_W;
		$replies = pdo_fetchall("SELECT id,rid FROM ".tablename('hx_dialect_reply')." WHERE rid = '$rid'");
		$deleteid = array();
		if (!empty($replies)) {
			foreach ($replies as $index => $row) {
				$deleteid[]	=	$row['id'];
				$ridid[]	=	$row['rid'];
			}
		}
		pdo_delete('hx_dialect_reply', "id IN ('".implode("','", $deleteid)."')");
		return true;
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
			//字段验证, 并获得正确的数据$dat
			$dat = array(
				'num' => intval($_GPC['num']),
				);
			if($this->saveSettings($dat)){
				message('保存成功', 'refresh');
			}
		}
		if(!isset($settings['num'])) {
			$settings['num'] = '182031';
		}
		//这里来展示设置项表单
		include $this->template('settings');
	}

}