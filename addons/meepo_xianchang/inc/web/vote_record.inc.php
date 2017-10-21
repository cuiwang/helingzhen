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
$vote_id = intval($_GPC['vote_id']);
if(empty($vote_id)){
	message('轮数错误',referer(),'error');
}
$psize = 20;
$where = " weid = :weid AND rid = :rid AND fid=:fid";
if($op=='list'){
	$vote_xms = pdo_fetchall("SELECT `id`,`name` FROM ".tablename($this->vote_xms_table)." WHERE rid=:rid AND fid=:fid AND weid=:weid",array(':rid'=>$rid,':fid'=>$vote_id,':weid'=>$weid));
	if(!empty($_GPC['nickname'])){
		$where .= " AND nick_name LIKE '%{$_GPC['nickname']}%'";
	}
	if(!empty($_GPC['vote_xm_id'])){
		$where .= " AND vote_xm_id = '{$_GPC['vote_xm_id']}'";
	}
	$list = pdo_fetchall("SELECT * FROM ".tablename($this->vote_record)." WHERE {$where} ORDER BY createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,array(':weid'=>$weid,':rid'=>$rid,':fid'=>$vote_id));
	$lists = array();
	if(!empty($list) && is_array($list)){
		foreach($list as $row){
			$row['name'] = pdo_fetchcolumn("SELECT `name` FROM ".tablename($this->vote_xms_table)." WHERE rid=:rid AND id=:id",array(':rid'=>$rid,':id'=>$row['vote_xm_id']));
			$lists[] = $row;
		}
	}
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->vote_record) . " WHERE {$where} ", array(':weid'=>$weid,':rid'=>$rid,':fid'=>$vote_id));
	$pager = pagination($total, $pindex, $psize);
}elseif($op=='del'){
	$vote_record_id = intval($_GPC['vote_record_id']);
	if($vote_record_id){
		$the_vote = pdo_fetch("SELECT * FROM ".tablename($this->vote_record)." WHERE id=:id AND rid=:rid",array(':id'=>$vote_record_id,':rid'=>$rid));
		if(!empty($the_vote)){
			$nums = pdo_fetchcolumn('SELECT `nums` FROM ' . tablename($this->vote_xms_table) . " WHERE weid = :weid AND rid = :rid AND id=:id", array(':weid'=>$weid,':rid'=>$rid,':id'=>$the_vote['vote_xm_id']));
			if($nums>0){
				pdo_query("UPDATE ".tablename($this->vote_xms_table)." SET nums = nums - 1 WHERE weid = 
			:weid AND id=:id",array(':weid'=>$weid,':id'=>$the_vote['vote_xm_id']));
			}
		}
		pdo_delete($this->vote_record,array('id'=>$vote_record_id,'rid'=>$rid));
		message('删除成功',referer(),'success');
	}
}
if(checksubmit('delete')){
	//批量删除
	$select = $_GPC['select'];
	if(empty($select)){
		message('请选择删除项',referer(),"error");
	}
	foreach ($select as $se) {
		$the_vote = pdo_fetch("SELECT * FROM ".tablename($this->vote_record)." WHERE id=:id AND rid=:rid",array(':id'=>$se,':rid'=>$rid));
		$nums = pdo_fetchcolumn('SELECT `nums` FROM ' . tablename($this->vote_xms_table) . " WHERE weid = :weid AND rid = :rid AND id=:id", array(':weid'=>$weid,':rid'=>$rid,':id'=>$the_vote['vote_xm_id']));
		if($nums>0){
			pdo_query("UPDATE ".tablename($this->vote_xms_table)." SET nums = nums - 1 WHERE weid = 
		:weid AND id=:id",array(':weid'=>$weid,':id'=>$the_vote['vote_xm_id']));
		}
		pdo_delete($this->vote_record,array('id'=>$se,'rid'=>$id,'weid'=>$weid));

	}
	message('批量删除成功',referer(),"success");
}
if(checksubmit('down')){
			if(empty($_GPC['select'])){
					message("请先选择导出项",referer(),'error');
			}
			$up_list = pdo_fetchall("SELECT * FROM ".tablename($this->vote_record)." WHERE  weid=:weid  AND rid=:rid AND fid=:fid AND  id  IN  ('".implode("','", $_GPC['select'])."')", array(':weid'=>$weid,':rid'=>$rid,':fid'=>$vote_id));
					

					
					//导出
						include_once ('../framework/library/phpexcel/PHPExcel.php');
						$objPHPExcel = new PHPExcel();
						$objDrawing = new PHPExcel_Worksheet_Drawing();

						$objPHPExcel->getProperties()->setCreator("Meepo");
						$objPHPExcel->getProperties()->setLastModifiedBy("Meepo");
						$objPHPExcel->getProperties()->setTitle("Meepo");

						$objPHPExcel->getActiveSheet()->setCellValue('A1', '粉丝昵称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
						$objPHPExcel->getActiveSheet()->setCellValue('B1', '投票项目');
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$objPHPExcel->getActiveSheet()->setCellValue('C1', '投票时间');
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
						foreach ($up_list as $key => $value) {
							$xm_name = pdo_fetchcolumn("SELECT `name` FROM ".tablename($this->vote_xms_table)." WHERE rid=:rid AND id=:id",array(':rid'=>$rid,':id'=>$value['vote_xm_id']));
							$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), ' '.$value['nick_name']);
							$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $xm_name);
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
						header('Content-Disposition:attachment;filename="投票名单.xls"');
						header("Content-Transfer-Encoding:binary");
						$objWriter->save('php://output');

						exit();
}
if(checksubmit('downall')){
			$where = '';
			$xm_name = '';
			if(!empty($_GPC['vote_xm_id'])){
				$where .= " AND vote_xm_id = '{$_GPC['vote_xm_id']}'";
				$xm_name = pdo_fetchcolumn("SELECT `name` FROM ".tablename($this->vote_xms_table)." WHERE rid=:rid AND id=:id",array(':rid'=>$rid,':id'=>$_GPC['vote_xm_id']));
			}
			$up_list = pdo_fetchall("SELECT * FROM ".tablename($this->vote_record)." WHERE  weid=:weid  AND rid=:rid AND fid=:fid {$where}", array(':weid'=>$weid,':rid'=>$rid,':fid'=>$vote_id));
			
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
						$objPHPExcel->getActiveSheet()->setCellValue('B1', '投票项目');
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$objPHPExcel->getActiveSheet()->setCellValue('C1', '投票时间');
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
						foreach ($up_list as $key => $value) {
							$xm_name = pdo_fetchcolumn("SELECT `name` FROM ".tablename($this->vote_xms_table)." WHERE rid=:rid AND id=:id",array(':rid'=>$rid,':id'=>$value['vote_xm_id']));
							$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), ' '.$value['nick_name']);
							$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $xm_name);
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
						header('Content-Disposition:attachment;filename="'.$xm_name.'投票名单.xls"');
						header("Content-Transfer-Encoding:binary");
						$objWriter->save('php://output');

						exit();
}
include $this->template('vote_record');
 
      
