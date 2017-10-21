<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 赞木 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$id = $rid = $_GPC['id'];
$op = empty($_GPC['op'])? 'list':$_GPC['op'];
$level = empty($_GPC['level']) ? '1' : $_GPC['level'];
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$where = " weid = :weid AND rid = :rid AND level = :level";;
if($op=='list'){
	if(!empty($_GPC['nickname'])){
		$where .= " AND nick_name LIKE '%{$_GPC['nickname']}%'";
	}
	$qds = pdo_fetchall("SELECT * FROM ".tablename($this->qd_table)." WHERE {$where} ORDER BY createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,array(':weid'=>$weid,':rid'=>$rid,':level'=>$level));
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->qd_table) . " WHERE {$where} ", array(':weid'=>$weid,':rid'=>$rid,':level'=>$level));
	$pager = pagination($total, $pindex, $psize);
}elseif($op=='post'){
	$qd_id = $_GPC['qd_id'];
	if(empty($qd_id)){
		message('签到id错误');
	}else{
		$qqd = pdo_fetch("SELECT `openid` FROM ".tablename($this->qd_table)." WHERE  weid = :weid AND rid = :rid AND id = :id ",array(':weid'=>$weid,':rid'=>$rid,':id'=>$qd_id));
		if(!empty($qqd)){
			pdo_update($this->user_table,array('qd_status'=>1),array('rid'=>$rid,'openid'=>$qqd['openid']));
		}
		$data = array('level'=>'1');
		pdo_update($this->qd_table,$data,array('id'=>$qd_id));
		message('审核成功',$this->createWebUrl('qd_manage',array('id'=>$id,'level'=>$level,'page'=>$pindex)),"success");
	}
}elseif($op=='del'){
	$qd_id = $_GPC['qd_id'];
	if(empty($qd_id)){
		message('签到id错误');
	}else{
		$qqd = pdo_fetch("SELECT `openid` FROM ".tablename($this->qd_table)." WHERE  weid = :weid AND rid = :rid AND id = :id ",array(':weid'=>$weid,':rid'=>$rid,':id'=>$qd_id));
		if(!empty($qqd)){
			pdo_update($this->user_table,array('qd_status'=>0),array('rid'=>$rid,'openid'=>$qqd['openid']));
		}
		$data = array('nick_name'=>$_GPC['nick_name']);
		pdo_delete($this->qd_table,array('id'=>$qd_id));
		 message('删除成功',$this->createWebUrl('qd_manage',array('id'=>$id,'level'=>$level,'page'=>$pindex)),"success");
	}
}elseif($op=='reset'){
	pdo_delete($this->qd_table,array('rid'=>$rid));
	pdo_update($this->user_table,array('qd_status'=>0),array('rid'=>$rid));
	message('清空签到数据成功',$this->createWebUrl('qd_manage',array('id'=>$id,'level'=>$level,'page'=>$pindex)),"success");
}else{
	message('非法访问');
}
if(checksubmit('delete')){
	//批量删除
	$select = $_GPC['select'];
	if(empty($select)){
		message('请选择删除项',$this->createWebUrl("qd_manage",array('id'=>$id,'level'=>$level,'page'=>$pindex)),"error");
	}
	foreach ($select as $se) {
		$qqd = pdo_fetch("SELECT `openid` FROM ".tablename($this->qd_table)." WHERE  weid = :weid AND rid = :rid AND id = :id ",array(':weid'=>$weid,':rid'=>$rid,':id'=>$se));
		if(!empty($qqd)){
			pdo_update($this->user_table,array('qd_status'=>0),array('rid'=>$rid,'openid'=>$qqd['openid']));
		}
		pdo_delete($this->qd_table,array('id'=>$se,'rid'=>$id,'weid'=>$_W['uniacid']));
	}
	message('批量删除成功',$this->createWebUrl("qd_manage",array('id'=>$id,'level'=>$level,'page'=>$pindex)),"success");
}
if(checksubmit('signs')){
	//批量删除
	$select = $_GPC['select'];
	if(empty($select)){
		message('请选择审核项',$this->createWebUrl("qd_manage",array('id'=>$id,'level'=>$level,'page'=>$pindex)),"error");
	}
	foreach ($select as $se) {
		$qqd = pdo_fetch("SELECT `openid` FROM ".tablename($this->qd_table)." WHERE  weid = :weid AND rid = :rid AND id = :id ",array(':weid'=>$weid,':rid'=>$rid,':id'=>$se));
		if(!empty($qqd)){
			pdo_update($this->user_table,array('qd_status'=>1),array('rid'=>$rid,'openid'=>$qqd['openid']));
		}
		pdo_update($this->qd_table,array('level'=>1),array('id'=>$se,'rid'=>$id,'weid'=>$_W['uniacid']));
	}
	message('批量审核成功',$this->createWebUrl("qd_manage",array('id'=>$id,'level'=>$level,'page'=>$pindex)),"success");
}
if(checksubmit('down')){
			if(empty($_GPC['select'])){
					message("请先选择导出项",referer(),'error');
			}
			$up_list = pdo_fetchall("SELECT * FROM ".tablename($this->qd_table)." WHERE  weid=:weid  AND rid=:rid AND  id  IN  ('".implode("','", $_GPC['select'])."')", array(':weid'=>$weid,':rid'=>$rid));
					

					
					//导出
						include_once ('../framework/library/phpexcel/PHPExcel.php');
						$objPHPExcel = new PHPExcel();
						$objDrawing = new PHPExcel_Worksheet_Drawing();

						$objPHPExcel->getProperties()->setCreator("Meepo");
						$objPHPExcel->getProperties()->setLastModifiedBy("Meepo");
						$objPHPExcel->getProperties()->setTitle("Meepo");

						$objPHPExcel->getActiveSheet()->setCellValue('A1', '粉丝昵称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
						$objPHPExcel->getActiveSheet()->setCellValue('B1', '签到状态');
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$objPHPExcel->getActiveSheet()->setCellValue('C1', '签到时间');
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
						foreach ($up_list as $key => $value) {
							$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), ' '.$value['nick_name']);
							$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $value['level']==1?'审核通过':'未通过审核');
							$objPHPExcel->getActiveSheet()->setCellValue('C'.($key+2), date('Y-m-d H:i:s',$value['createtime']));


						}

						$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

						header("Pragma: public");
						header("Expires: 0");
						header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
						header("Content-Type:application/force-download");
						header("Content-Type:application/vnd.ms-execl");
						header("Content-Type:application/octet-stream");
						header("Content-Type:application/download");;
						header('Content-Disposition:attachment;filename="签到名单.xls"');
						header("Content-Transfer-Encoding:binary");
						$objWriter->save('php://output');

						exit();
}
if(checksubmit('downall')){
			$up_list = pdo_fetchall("SELECT * FROM ".tablename($this->qd_table)." WHERE  weid=:weid  AND rid=:rid", array(':weid'=>$weid,':rid'=>$rid));
					
			if(empty($up_list)){
				message("没有数据、导出失败",referer(),'error');
			}
					
					//导出
						include_once ('../framework/library/phpexcel/PHPExcel.php');
						$objPHPExcel = new PHPExcel();
						$objDrawing = new PHPExcel_Worksheet_Drawing();

						$objPHPExcel->getProperties()->setCreator("Meepo");
						$objPHPExcel->getProperties()->setLastModifiedBy("Meepo");
						$objPHPExcel->getProperties()->setTitle("Meepo");

						$objPHPExcel->getActiveSheet()->setCellValue('A1', '粉丝昵称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
						$objPHPExcel->getActiveSheet()->setCellValue('B1', '签到状态');
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$objPHPExcel->getActiveSheet()->setCellValue('C1', '签到时间');
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
						foreach ($up_list as $key => $value) {
							$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), ' '.$value['nick_name']);
							$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $value['level']==1?'审核通过':'未通过审核');
							$objPHPExcel->getActiveSheet()->setCellValue('C'.($key+2), date('Y-m-d H:i:s',$value['createtime']));


						}

						$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

						header("Pragma: public");
						header("Expires: 0");
						header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
						header("Content-Type:application/force-download");
						header("Content-Type:application/vnd.ms-execl");
						header("Content-Type:application/octet-stream");
						header("Content-Type:application/download");;
						header('Content-Disposition:attachment;filename="签到名单.xls"');
						header("Content-Transfer-Encoding:binary");
						$objWriter->save('php://output');

						exit();
}
include $this->template('qd_manage');