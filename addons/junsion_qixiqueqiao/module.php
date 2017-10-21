<?php
/**
 * 七夕鹊桥模块定义
 *
 * @author junsion
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Junsion_qixiqueqiaoModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W,$_GPC;
		load()->func('tpl');
		$rule = pdo_fetch('select * from '.tablename($this->modulename."_rule")." r join ".tablename($this->modulename."_prize")
						." p on p.rid=r.rid where r.rid='{$rid}'");
		if (empty($rule)){
			$rule['starttime'] = time();
			$rule['endtime'] = time()+7*24*3600;
		}else{
			$limit = explode(',',$rule['birds_limit']);
			if (count($limit) == 2){
				$rule['birds_limit1'] = $limit[0];
				$rule['birds_limit2'] = $limit[1];
				$rule['birds_limit'] = 0;
			}
		}
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		global $_W,$_GPC;
		if (empty($_GPC['stitle'])){
			return '请输入活动名称';
		}
		if (!empty($_GPC['limit_type'])){
			if ($_GPC['birds_limit1'] >= $_GPC['birds_limit2']) return '朋友助力的下限不能大于上限！';
		}
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_W,$_GPC;
		$birdlimit = $_GPC['birds_limit'];
		if (!empty($_GPC['limit_type'])){
			$birdlimit = $_GPC['birds_limit1'].",".$_GPC['birds_limit2'];
		}
		$data = array(
			'rid'=>$rid,
			'weid'=>$_W['weid'],
			'stitle'=>$_GPC['stitle'],
			'sthumb'=>$_GPC['sthumb'],
			'sdesc'=>$_GPC['sdesc'],
			'starttime'=>strtotime($_GPC['datelimit']['start']),
			'endtime'=>strtotime($_GPC['datelimit']['end']),
			'describe_limit'=>$_GPC['describe_limit'],
			'describe_limit2'=>$_GPC['describe_limit2'],
			'birds_success'=>$_GPC['birds_success'],
			'birds_limit'=>$birdlimit,
			'content'=>htmlspecialchars_decode($_GPC['content']),
			'niulang'=>$_GPC['niulang'],
			'zhinv'=>$_GPC['zhinv'],
			'bg'=>$_GPC['bg'],
			'prize_mode'=>$_GPC['prize_mode'],
			'prize_limit'=>$_GPC['prize_limit'],
			'sharetitle'=>$_GPC['sharetitle'],
			'sharethumb'=>$_GPC['sharethumb'],
			'sharedesc'=>$_GPC['sharedesc'],
			'isinfo'=>$_GPC['isinfo'],
			'awardtips'=>$_GPC['awardtips'],
			'isrealname'=>$_GPC['isrealname'],
			'ismobile'=>$_GPC['ismobile'],
			'isqq'=>$_GPC['isqq'],
			'isemail'=>$_GPC['isemail'],
			'rank'=>$_GPC['rank'],
			'isaddress'=>$_GPC['isaddress'],
			'isfans'=>$_GPC['isfans'],
		);
		$prize = array(
			'rid'=>$rid,
			'weid'=>$_W['weid'],
			'title'=>$_GPC['title'],
			'thumb'=>$_GPC['thumb'],
			'description'=>$_GPC['description'],
		);
		$rule = pdo_fetch('select * from '.tablename($this->modulename."_rule")." where rid='{$rid}'");
		if (!empty($rule)){
			pdo_update($this->modulename."_prize",$prize,array('rid'=>$rid));
			pdo_update($this->modulename."_rule",$data,array('id'=>$rule['id']));
		}else{
			pdo_insert($this->modulename."_rule",$data);
			pdo_insert($this->modulename."_prize",$prize);
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		pdo_delete($this->modulename."_rule",array('rid'=>$rid));
		pdo_delete($this->modulename."_player",array('rid'=>$rid));
		pdo_delete($this->modulename."_share",array('rid'=>$rid));
		pdo_delete($this->modulename."_prize",array('rid'=>$rid));
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
			//字段验证, 并获得正确的数据$dat
			$dat = array('describeurl'=>$_GPC['describeurl']);
			if ($this->saveSettings($dat)){
				message('保存成功','refresh');
			}
		}
		//这里来展示设置项表单
		include $this->template('setting');
	}

}