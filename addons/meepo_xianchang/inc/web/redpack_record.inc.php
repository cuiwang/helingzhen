<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 赞木 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$id = $rid = $_GPC['id'];
$pindex = max(1, intval($_GPC['page']));
$op = empty($_GPC['op'])? 'list':$_GPC['op'];
$rotate_id = intval($_GPC['rotate_id']);
if(empty($rotate_id)){
	message('轮数错误',referer(),'error');
}
$psize = 20;
$where = " weid = :weid AND rid = :rid AND rotate_id=:rotate_id";
if($op=='list'){
	if(!empty($_GPC['nickname'])){
		$where .= " AND nick_name LIKE '%{$_GPC['nickname']}%'";
	}
	if(!empty($_GPC['money'])){
		$money = $_GPC['money'];
		$where .= " AND money!='{$money}'";
	}
	$lists = pdo_fetchall("SELECT * FROM ".tablename($this->redpack_user_table)." WHERE {$where} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,array(':weid'=>$weid,':rid'=>$rid,':rotate_id'=>$rotate_id));
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->redpack_user_table) . " WHERE {$where} ", array(':weid'=>$weid,':rid'=>$rid,':rotate_id'=>$rotate_id));
	$pager = pagination($total, $pindex, $psize);
}elseif($op=='del'){
	$redpack_user_id = intval($_GPC['redpack_user_id']);
	if($redpack_user_id){
		pdo_delete($this->redpack_user_table,array('id'=>$redpack_user_id,'rid'=>$rid));
		message('删除成功',referer(),'success');
	}
}elseif($op=='reset'){
		pdo_delete($this->redpack_user_table,array('weid'=>$weid,'rid'=>$rid,'rotate_id'=>$rotate_id));
		message('清空成功',referer(),'success');
}
if(checksubmit('delete')){
	//批量删除
	$select = $_GPC['select'];
	if(empty($select)){
		message('请选择删除项',referer(),"error");
	}
	foreach ($select as $se) {
		pdo_delete($this->redpack_user_table,array('id'=>$se,'rid'=>$id,'weid'=>$weid));

	}
	message('批量删除成功',referer(),"success");
}
$where = '';
if(checksubmit('down')){
			if(empty($_GPC['select'])){
					message("请先选择导出项",referer(),'error');
			}
			if(!empty($_GPC['money'])){
				$money = $_GPC['money'];
				$where .= " AND money!='{$money}'";
			}
			$up_list = pdo_fetchall("SELECT * FROM ".tablename($this->redpack_user_table)." WHERE  weid=:weid  AND rid=:rid AND rotate_id=:rotate_id {$where} AND  id  IN  ('".implode("','", $_GPC['select'])."')", array(':weid'=>$weid,':rid'=>$rid,':rotate_id'=>$rotate_id));
					//导出
						include_once ('../framework/library/phpexcel/PHPExcel.php');
						$objPHPExcel = new PHPExcel();
						$objDrawing = new PHPExcel_Worksheet_Drawing();

						$objPHPExcel->getProperties()->setCreator("Meepo");
						$objPHPExcel->getProperties()->setLastModifiedBy("Meepo");
						$objPHPExcel->getProperties()->setTitle("Meepo");

						$objPHPExcel->getActiveSheet()->setCellValue('A1', '粉丝昵称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
						$objPHPExcel->getActiveSheet()->setCellValue('B1', '红包金额');
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$objPHPExcel->getActiveSheet()->setCellValue('C1', '时间');
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						foreach ($up_list as $key => $value) {
							$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), ' '.$value['nick_name']);
							$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $value['money']=='0.0'? '未中奖':$value['money'].'元');
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
						header('Content-Disposition:attachment;filename="抢红包中奖全部名单.xls"');
						header("Content-Transfer-Encoding:binary");
						$objWriter->save('php://output');

						exit();
}
if(checksubmit('downall')){
			if(!empty($_GPC['money'])){
				$money = $_GPC['money'];
				$where .= " AND money!='{$money}'";
			}
			$up_list = pdo_fetchall("SELECT * FROM ".tablename($this->redpack_user_table)." WHERE  weid=:weid  AND rid=:rid AND rotate_id=:rotate_id {$where}", array(':weid'=>$weid,':rid'=>$rid,':rotate_id'=>$rotate_id));
			
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
						$objPHPExcel->getActiveSheet()->setCellValue('B1', '红包金额');
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$objPHPExcel->getActiveSheet()->setCellValue('C1', '时间');
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						foreach ($up_list as $key => $value) {
							$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), ' '.$value['nick_name']);
							$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $value['money']=='0.0'? '未中奖':$value['money'].'元');
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
						header('Content-Disposition:attachment;filename="抢红包中奖全部名单.xls"');
						header("Content-Transfer-Encoding:binary");
						$objWriter->save('php://output');

						exit();
}
include $this->template('redpack_record');
 
      
