<?php
/**
 * 短信管理
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */
 
$weid = $_W['uniacid'];
if($op=='display'){
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_sms_template). " WHERE weid='{$weid}' ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $paras);

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM" . tablename($this->table_sms_template) . " WHERE weid='{$weid}'");
	$pager = pagination($total, $pindex, $psize);

}elseif($op=='post'){
	$tid = intval($_GPC['tid']);
	if(!empty($tid)){
		$template = pdo_fetch("SELECT * FROM " .tablename($this->table_sms_template). " WHERE weid='{$weid}' AND id='{$tid}'");
		if(empty($template)){
			message("该短信模版不存在或已被删除！", "", "error");
		}
	}

	if(checksubmit('submit')){
		$data = array(
			'weid'	    => $weid,
			'title'     => trim($_GPC['title']),
			'content'   => trim($_GPC['content']),
			'userscene' => intval($_GPC['userscene']),
			'status'	=> intval($_GPC['status']),
			'addtime'	=> time(),
		);
		if(empty($data['title'])){
			message("请输入模版名称");
		}
		if(empty($data['content'])){
			message("请输入模版内容");
		}
		if($data['userscene']==0){
			message("请选择模版使用场景");
		}
		if(!in_array($data['status'], array('0','1'))){
			message("请选择模版状态");
		}

		$exist = pdo_fetch("SELECT * FROM " .tablename($this->table_sms_template). " WHERE weid='{$weid}' AND userscene='{$data['userscene']}'");
		if(!empty($tid)){
			unset($data['addtime']);

			if($exist && $data['userscene']!=$template['userscene']){
				message("该使用场景短信模版已存在");
			}
			pdo_update($this->table_sms_template, $data, array('weid'=>$weid,'id'=>$tid));
			message("更新短信模版成功", $this->createWebUrl("sms"), "success");
		}else{
			if($exist){
				message("该使用场景短信模版已存在");
			}
			pdo_insert($this->table_sms_template, $data);
			message("新增短信模版成功", $this->createWebUrl("sms"), "success");
		}
	}

}elseif($op=='del'){
	$tid = intval($_GPC['tid']);
	if(!empty($tid)){
		$template = pdo_fetch("SELECT * FROM " .tablename($this->table_sms_template). " WHERE weid='{$weid}' AND id='{$tid}'");
		if(empty($template)){
			message("该短信模版不存在或已被删除！", "", "error");
		}
	}
	pdo_delete($this->table_sms_template, array('id'=>$tid));
	message("删除短信模版成功", $this->createWebUrl("sms"), "success");
}



include $this->template('sms');