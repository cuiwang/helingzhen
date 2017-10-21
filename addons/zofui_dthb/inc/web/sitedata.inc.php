<?php 
	global $_W,$_GPC;
	load() -> func('tpl');
	$operation = !empty($_GPC['op']) ? $_GPC['op'] :'answer';

	
	if ($operation == 'answer') {
		//分页数据
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$total = pdo_fetchcolumn(" SELECT COUNT(*) FROM " . tablename('zofui_dthb_answerlog') . " WHERE uniacid ={$_W['uniacid']} ");
		//分页数据结束
		//查询文章
		$answerinfo = pdo_fetchall("select * from" . tablename('zofui_dthb_answerlog') . "where uniacid ={$_W['uniacid']} ORDER BY id DESC " . "LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		//分页函数
		$pager = pagination($total, $pindex, $psize);
	
	}elseif ($operation == 'prize') {
		//分页数据
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$total = pdo_fetchcolumn(" SELECT COUNT(*) FROM " . tablename('zofui_dthb_hblog') . " WHERE uniacid ={$_W['uniacid']}");
		//分页数据结束
		//查询文章
		$prizedinfo = pdo_fetchall("select * from" . tablename('zofui_dthb_hblog') . "where uniacid ='{$_W['uniacid']}' ORDER BY id DESC " . "LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		//分页函数
		$pager = pagination($total, $pindex, $psize);
	}

	

	
	if(checksubmit('delateanswer')){
		$id = $_GPC['checkbox'];
		$success_num =0;
		$fail_num =0;
		foreach($id as $k=>$value){
			$row = pdo_delete("zofui_dthb_answerlog",array('id'=>$value));
			if($row>0){
				$success_num+=1;
			}else{
				$fail_num+=1;
			}
		}
		message('操作成功,删除'.$success_num.'条,失败'.$fail_num.'条', referer(), 'success');
	}
	
	if(checksubmit('delateprize')){
		$id = $_GPC['checkbox'];
		$success_num =0;
		$fail_num =0;
		foreach($id as $k=>$value){
			$row = pdo_delete("zofui_dthb_hblog",array('id'=>$value));
			if($row>0){
				$success_num+=1;
			}else{
				$fail_num+=1;
			}
		}
		message('操作成功,删除'.$success_num.'条,失败'.$fail_num.'条', referer(), 'success');
	}
	
	
include $this->template('web/sitedata');
?>