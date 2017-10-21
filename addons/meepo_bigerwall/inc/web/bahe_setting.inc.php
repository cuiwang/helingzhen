<?php
global $_W,$_GPC;
$id = intval($_GPC['id']);
$weid = $_W['uniacid'];
$table = 'weixin_bahe_prize';
//pdo_query("DROP TABLE " . tablename($table) . " ");

if(checksubmit('submit')){
	
	
	
	if(is_array($_GPC['rank_start']) && !empty($_GPC['rank_start'])){
		for($i=0;$i<count($_GPC['rank_start']);$i++){
				$start = intval($_GPC['rank_start'][$i]);
				$end = intval($_GPC['rank_end'][$i]);
				if(!$start || !$end){
						message('名次不能出现0');
				}else{
					
					if($start > $end){
							message('名次前一个值不能大于后面的值');
					}
					$gap = $i+1;
					$next_start = intval($_GPC['rank_start'][$gap]);
					if($next_start){
						
						if($end >= $next_start){
							message('前一个奖项的结束名次不能大于等于下一个奖项的开始名次');
						}
					}
					
				}

		}
	}
	$insert = array(
			'prizetype'=>$_GPC['prizetype'],
			'prizename'=>$_GPC['prizename'],
			'rank_start'=>$_GPC['rank_start'],
			'rank_end'=>$_GPC['rank_end'],
			'prize_img'=>$_GPC['prize_img'],
			'prize_geturl'=>$_GPC['prize_geturl'],
			'prize_getaddress'=>$_GPC['prize_getaddress'],
			'prize_getcode'=>$_GPC['prize_getcode']);
	$prize = iserializer($insert);
	if(!empty($_GPC['row_id'])){
		$row_id = intval($_GPC['row_id']);
		pdo_update($table,array('prize'=>$prize),array('weid'=>$weid,'rid'=>$id,'id'=>$row_id));
		message('更新成功',$this->createWebUrl('bahe_setting',array('id'=>$id)));
	}else{
		$data = array();
		$data['rid'] = $id;
		$data['weid'] = $weid;
		$data['createtime'] = time();
		$data['prize'] = $prize;
		pdo_insert($table,$data);
		message('新增成功',$this->createWebUrl('bahe_setting',array('id'=>$id)));
	}
}
$list  = pdo_fetch("SELECT * FROM ".tablename($table)." WHERE rid=:rid AND weid=:weid",array(':rid'=>$id,':weid'=>$weid));

if(!empty($list)){
	$prize = iunserializer($list['prize']);

}
load()->func('tpl');
include $this->template('bahe_setting');