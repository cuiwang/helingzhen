<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 赞木 作者QQ 284099857
 */
global $_W, $_GPC;
$weid = $_W['uniacid'];
$id = $rid = intval($_GPC['id']);
load()->func('tpl');
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
if($op == 'list') {
	$list = pdo_fetchall("SELECT * FROM " . tablename($this->lottory_award_table) . " WHERE weid = :weid AND rid = :rid AND type=:type ORDER BY displayid ASC",array(':weid'=>$weid,':rid'=>$rid,':type'=>'0'));
}elseif ($op == 'post') {
	$award_id = intval($_GPC['award_id']);
	if(!empty($award_id)){
		$cj = pdo_fetch("SELECT * FROM ".tablename($this->lottory_award_table)." WHERE rid=:rid AND id=:id",array(':rid'=>$rid,':id'=>$award_id));
	}
	if (checksubmit('submit')) {
		$data = array(
			'displayid'=>intval($_GPC['displayid']),
			'weid' => $weid,
			'rid'=>$rid,
			'luck_name' => trim($_GPC['luck_name']),//奖品名称
			'luck_img' =>$_GPC['luck_img'],
			'tag_name' => trim($_GPC['tag_name']),//奖项名称
			'tag_num' => intval($_GPC['tag_num']),
			
		);
		
		if (!empty($award_id)) {
			$data['nd_id'] = $_GPC['nd_id'];
			
			pdo_update($this->user_table,array('nd_id'=>0),array('nd_id'=>$award_id,'rid'=>$rid));
			if(!empty($data['nd_id'])){
				$where = "rid = '{$rid}' AND id  IN  (".trim($data['nd_id']).")";
				pdo_query("UPDATE ".tablename($this->user_table)." SET  nd_id= '{$award_id}' WHERE {$where}");
			}
			pdo_update($this->lottory_award_table, $data, array('id' => $award_id));
			
			message('保存成功！', $this->createWebUrl('lottory_manage', array('id'=>$id)), 'success');
		} else {
			pdo_insert($this->lottory_award_table,$data);
			message('新增奖品成功！', $this->createWebUrl('lottory_manage', array('id'=>$id,'op' => 'post')), 'success');
		}
	}
}elseif($op=='del'){
	$award_id = intval($_GPC['award_id']);
	pdo_update($this->user_table,array('nd_id'=>0),array('nd_id'=>$award_id,'rid'=>$rid));
	pdo_delete($this->lottory_user_table,array('lottory_id'=>$award_id,'rid'=>$rid));
	pdo_delete($this->lottory_award_table,array('id'=>$award_id));
	message('删除奖品成功！', $this->createWebUrl('lottory_manage', array('id'=>$id)), 'success');
}else {
	message('请求方式不存在');
}

include $this->template('lottory_manage');