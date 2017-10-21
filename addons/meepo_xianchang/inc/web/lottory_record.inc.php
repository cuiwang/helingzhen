<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 赞木 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$id = $rid = $_GPC['id'];
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
if($op == 'list') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$sql = "SELECT * FROM ".tablename($this->lottory_user_table)." WHERE  weid=:weid AND rid=:rid AND type=:type ORDER BY lottory_id DESC,createtime ASC LIMIT ".($pindex - 1) * $psize.",{$psize}";
	$list = pdo_fetchall($sql, array(':weid'=>$weid,':rid'=>$id,':type'=>0));
	if(!empty($list) && is_array($list)){
		foreach($list as &$row){
				  $luckinfo = pdo_fetch("SELECT `tag_name`,`luck_name` FROM ".tablename($this->lottory_award_table)."WHERE id = :id AND weid = :weid AND rid=:rid",array(':id'=>$row['lottory_id'],':weid'=>$weid,':rid'=>$rid));
				  $row['tag_name'] = $luckinfo['tag_name'];
				  $row['luck_name'] = $luckinfo['luck_name'];
						   
		 }
		 unset($row);
	}
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->lottory_user_table) . " WHERE  rid=:rid AND weid=:weid AND type=:type", array(':rid' =>$rid,':weid'=>$weid,':type'=>0));
	$pager = pagination($total, $pindex, $psize);
}elseif($op=='del'){
	$list_id = $_GPC['list_id'];
	if(!empty($list_id)){
		pdo_delete($this->lottory_user_table,array('id'=>$list_id,'rid'=>$rid));
	}
	message('删除成功',$this->createWebUrl('lottory_record', array('id'=>$id)),'success');
}
if(checksubmit('delete')){
	//批量删除
	$select = $_GPC['select'];
	if(empty($select)){
		message('请选择删除项',$this->createWebUrl("lottory_record",array('id'=>$id,'page'=>$pindex)),"error");
	}
	foreach ($select as $se) {
		pdo_delete($this->lottory_user_table,array('id'=>$se,'rid'=>$rid));
	}
	message('批量删除成功',$this->createWebUrl("lottory_record",array('id'=>$id,'page'=>$pindex)),"success");
}
$upload_arr = array('F','G','H','I','J','K','L','M','N','O','P','Q');
if(checksubmit('down')){
			if(empty($_GPC['select'])){
					message("请先选择导出项",referer(),'error');
			}
			$up_list = pdo_fetchall("SELECT * FROM ".tablename($this->lottory_user_table)." WHERE  weid=:weid  AND rid=:rid AND  id  IN  ('".implode("','", $_GPC['select'])."')", array(':weid'=>$weid,':rid'=>$rid));
					

					
					//导出
						include_once ('../framework/library/phpexcel/PHPExcel.php');
						$objPHPExcel = new PHPExcel();
						$objDrawing = new PHPExcel_Worksheet_Drawing();

						$objPHPExcel->getProperties()->setCreator("Meepo");
						$objPHPExcel->getProperties()->setLastModifiedBy("Meepo");
						$objPHPExcel->getProperties()->setTitle("Meepo");

						$objPHPExcel->getActiveSheet()->setCellValue('A1', '粉丝昵称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
						$objPHPExcel->getActiveSheet()->setCellValue('B1', '奖项名称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$objPHPExcel->getActiveSheet()->setCellValue('C1', '奖品名称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
						$objPHPExcel->getActiveSheet()->setCellValue('D1', '中奖时间');
						$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
						$objPHPExcel->getActiveSheet()->setCellValue('E1', '领奖sn码');
						$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
						$bd_xm2 = iunserializer(pdo_fetchcolumn("SELECT `xm` FROM ".tablename($this->bd_manage_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid)));
						if(is_array($bd_xm2) && count($bd_xm2)>0){
							$bd_i = 0;
							foreach($bd_xm2 as $v){
								$objPHPExcel->getActiveSheet()->setCellValue($upload_arr[$bd_i].'1', $v['bd_name']);
						        $objPHPExcel->getActiveSheet()->getColumnDimension($upload_arr[$bd_i])->setWidth(20);
								$bd_i++;
							}
						}
						foreach ($up_list as $key => $value) {
							$temp_tag = pdo_fetch("SELECT `tag_name`,`luck_name` FROM ".tablename($this->lottory_award_table)." WHERE id=:id AND weid=:weid",array(':id'=>$value['lottory_id'],':weid'=>$weid));
							$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), ' '.$value['nick_name']);
							$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $temp_tag['tag_name']);
							$objPHPExcel->getActiveSheet()->setCellValue('C'.($key+2), $temp_tag['luck_name']);
							$objPHPExcel->getActiveSheet()->setCellValue('D'.($key+2),date('Y-m-d H:i:s',$value['createtime']));
						    $objPHPExcel->getActiveSheet()->setCellValue('E'.($key+2), $value['createtime']);
							$openid = $value['openid'];
							if(is_array($bd_xm2) && count($bd_xm2)>1){
								$fans_bd_data = iunserializer(pdo_fetchcolumn("SELECT `data` FROM ".tablename($this->bd_data_table)." WHERE weid=:weid AND rid=:rid AND openid = :openid",array(':weid'=>$weid,':rid'=>$rid,':openid'=>$openid)));
								$bd_i = 0;
								foreach($bd_xm2 as $v){
									$objPHPExcel->getActiveSheet()->setCellValue($upload_arr[$bd_i].($key+2),empty($fans_bd_data[$v['zd_name']]) ? '未录入':$fans_bd_data[$v['zd_name']]);
									$bd_i++;
								}
							}
				
						}

						$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

						header("Pragma: public");
						header("Expires: 0");
						header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
						header("Content-Type:application/force-download");
						header("Content-Type:application/vnd.ms-execl");
						header("Content-Type:application/octet-stream");
						header("Content-Type:application/download");;
						header('Content-Disposition:attachment;filename="中奖名单.xls"');
						header("Content-Transfer-Encoding:binary");
						$objWriter->save('php://output');

						exit();
}
if(checksubmit('downall')){
			$up_list = pdo_fetchall("SELECT * FROM ".tablename($this->lottory_user_table)." WHERE  weid=:weid  AND rid=:rid", array(':weid'=>$weid,':rid'=>$rid));
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
						$objPHPExcel->getActiveSheet()->setCellValue('B1', '奖项名称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$objPHPExcel->getActiveSheet()->setCellValue('C1', '奖品名称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
						$objPHPExcel->getActiveSheet()->setCellValue('D1', '中奖时间');
						$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
						$objPHPExcel->getActiveSheet()->setCellValue('E1', '领奖sn码');
						$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
						$bd_xm2 = iunserializer(pdo_fetchcolumn("SELECT `xm` FROM ".tablename($this->bd_manage_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid)));
						if(is_array($bd_xm2) && count($bd_xm2)>0){
							$bd_i = 0;
							foreach($bd_xm2 as $v){
								$objPHPExcel->getActiveSheet()->setCellValue($upload_arr[$bd_i].'1', $v['bd_name']);
						        $objPHPExcel->getActiveSheet()->getColumnDimension($upload_arr[$bd_i])->setWidth(20);
								$bd_i++;
							}
						}
						foreach ($up_list as $key => $value) {
							$temp_tag = pdo_fetch("SELECT `tag_name`,`luck_name` FROM ".tablename($this->lottory_award_table)." WHERE id=:id AND weid=:weid",array(':id'=>$value['lottory_id'],':weid'=>$weid));
							$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), ' '.$value['nick_name']);
							$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $temp_tag['tag_name']);
							$objPHPExcel->getActiveSheet()->setCellValue('C'.($key+2), $temp_tag['luck_name']);
							$objPHPExcel->getActiveSheet()->setCellValue('D'.($key+2),date('Y-m-d H:i:s',$value['createtime']));
						    $objPHPExcel->getActiveSheet()->setCellValue('E'.($key+2), $value['createtime']);
							$openid = $value['openid'];
							if(is_array($bd_xm2) && count($bd_xm2)>1){
								$fans_bd_data = iunserializer(pdo_fetchcolumn("SELECT `data` FROM ".tablename($this->bd_data_table)." WHERE weid=:weid AND rid=:rid AND openid = :openid",array(':weid'=>$weid,':rid'=>$rid,':openid'=>$openid)));
								$bd_i = 0;
								foreach($bd_xm2 as $v){
									$objPHPExcel->getActiveSheet()->setCellValue($upload_arr[$bd_i].($key+2),empty($fans_bd_data[$v['zd_name']]) ? '未录入':$fans_bd_data[$v['zd_name']]);
									$bd_i++;
								}
							}
						}

						$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

						header("Pragma: public");
						header("Expires: 0");
						header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
						header("Content-Type:application/force-download");
						header("Content-Type:application/vnd.ms-execl");
						header("Content-Type:application/octet-stream");
						header("Content-Type:application/download");;
						header('Content-Disposition:attachment;filename="中奖名单.xls"');
						header("Content-Transfer-Encoding:binary");
						$objWriter->save('php://output');

						exit();
}
include $this->template('lottory_record');