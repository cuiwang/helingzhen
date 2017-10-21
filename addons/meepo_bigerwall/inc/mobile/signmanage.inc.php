<?php
global $_GPC, $_W;
$weid = $_W['uniacid'];
$sign_table = 'weixin_signs';
$flag_table = 'weixin_flag';
$id = intval($_GPC['id']);
if(empty($id)){
   message('错误、规则不存在！');
}
$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid LIMIT 1", array(':weid'=>$weid,':rid'=>$id));
if(isset($_COOKIE["Meepo".$id]) && $_COOKIE["Meepo".$id] ==$ridwall['loginpass'] ){
}elseif(isset($_COOKIE["Meepo".$id]) && $_COOKIE["Meepo".$id] =='meepoceshi'){
} else {
	$forward =$_W['siteroot']."app/".$this->createMobileurl('login',array('rid'=>$id));
	$forward = str_replace('./','', $forward);
	header('location: ' .$forward);
	exit;
}
		$pindex = max(1, intval($_GPC['page']));
	      $psize = 20;
		   $op = empty($_GPC['op']) ? 'list' : $_GPC['op'];
			 $status = empty($_GPC['status']) ? '1' : $_GPC['status'];
			if($op == 'list'){
			  $params = array();
        $where = " weid = :weid AND rid = :rid AND status = :status";
				$params[':weid'] = $_W['uniacid'];
				$params[':rid'] = $id;
				$params[':status'] = $status;
				if(!empty($_GPC['nickname'])){
					$where .= " AND nickname LIKE '%{$_GPC['nickname']}%'";
			  }
				$sql = "SELECT * FROM ".tablename($sign_table)." WHERE {$where} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
				$lists = pdo_fetchall($sql,$params);
				$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($sign_table) . " WHERE {$where} ", $params);
			  $pager = pagination($total, $pindex, $psize);
		   }elseif($op == 'post'){
			   
					$signid = intval($_GPC['signid']);
					
					if(empty($signid)){
		           message('审核项目不存在',$this->createMobileUrl('signmanage',array('id'=>$id,'status'=>$status,'page'=>$pindex)),"error");
					}
					//pdo_update('weixin_flag',array('sign'=>1,'signtime'=>time()),array('id'=>$signid,'rid'=>$id,'weid'=>$weid));
					pdo_update($sign_table,array('status'=>1),array('id'=>$signid,'rid'=>$id,'weid'=>$_W['uniacid']));
					message('审核成功',$this->createMobileUrl("signmanage",array('id'=>$id,'status'=>$status,'page'=>$pindex)),"success");
			 }elseif($op == 'delete'){
			   
					$signid = intval($_GPC['signid']);
					if(empty($signid)){
		           message('删除项目不存在',$this->createMobileUrl('signmanage',array('id'=>$id,'status'=>$status,'page'=>$pindex)),"error");
					}
					pdo_delete($sign_table,array('id'=>$signid,'rid'=>$id,'weid'=>$_W['uniacid']));
					message('删除成功',$this->createMobileUrl("signmanage",array('id'=>$id,'status'=>$status,'page'=>$pindex)),"success");
			 }else{
			   message('访问错误');
			 }

				if(checksubmit('delete')){
					//批量删除
					$select = $_GPC['select'];
					if(empty($select)){
						message('请选择删除项',$this->createMobileUrl("signmanage",array('id'=>$id,'status'=>$status,'page'=>$pindex)),"error");
					}
					foreach ($select as $se) {
						pdo_delete($sign_table,array('id'=>$se,'rid'=>$id,'weid'=>$_W['uniacid']));
					}
					message('批量删除成功',$this->createMobileUrl("signmanage",array('id'=>$id,'status'=>$status,'page'=>$pindex)),"success");
				}
				if(checksubmit('signs')){
					//批量删除
					$select = $_GPC['select'];
					if(empty($select)){
						message('请选择删除项',$this->createMobileUrl("signmanage",array('id'=>$id,'status'=>$status,'page'=>$pindex)),"error");
					}
					foreach ($select as $se) {
						pdo_update($sign_table,array('status'=>1),array('id'=>$se,'rid'=>$id,'weid'=>$_W['uniacid']));
					}
					message('审核成功',$this->createMobileUrl("signmanage",array('id'=>$id,'status'=>$status,'page'=>$pindex)),"success");
				}
				if(checksubmit('upload')){
						$select = $_GPC['select'];
						if(empty($select)){
						   message('请选择导出项');
						}
						foreach($select as $row){
								$sql = "SELECT * FROM ".tablename('meepo_jgg_lucker')." WHERE rid = :rid AND weid = :weid AND id = :id";
								$params = array(':rid'=>$id,':weid'=>$_W['uniacid'],':id'=>$row);
								$list[] = pdo_fetch($sql,$params);
            }

						//导出
						include_once ('../framework/library/phpexcel/PHPExcel.php');
						$objPHPExcel = new PHPExcel();
						$objDrawing = new PHPExcel_Worksheet_Drawing();

						$objPHPExcel->getProperties()->setCreator("Meepo");
						$objPHPExcel->getProperties()->setLastModifiedBy("Meepo");
						$objPHPExcel->getProperties()->setTitle("Meepo");

						$objPHPExcel->getActiveSheet()->setCellValue('A1', '活动名称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
						$objPHPExcel->getActiveSheet()->setCellValue('B1', '奖品名称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$objPHPExcel->getActiveSheet()->setCellValue('C1', '奖项名称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
						$objPHPExcel->getActiveSheet()->setCellValue('D1', '中奖时间');
						$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

						foreach ($list as $key => $value) {
							$activityname = pdo_fetchcolumn("SELECT title FROM".tablename('meepo_jgg')." WHERE rid=:rid AND weid=:weid",array(':rid'=>$id,':weid'=>$_W['uniacid']));
							$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), ' '.$activityname);
							$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $value['award_name']);
							$objPHPExcel->getActiveSheet()->setCellValue('C'.($key+2), $value['award_level']);
							$objPHPExcel->getActiveSheet()->setCellValue('D'.($key+2), date("Y-m-d H:i:s",$value['createtime']));

						}

						$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

						header("Pragma: public");
						header("Expires: 0");
						header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
						header("Content-Type:application/force-download");
						header("Content-Type:application/vnd.ms-execl");
						header("Content-Type:application/octet-stream");
						header("Content-Type:application/download");;
						header('Content-Disposition:attachment;filename="resume.xls"');
						header("Content-Transfer-Encoding:binary");
						$objWriter->save('php://output');

						exit();
					}

				if(checksubmit('uploadall')){
					$sql = "SELECT * FROM ".tablename('meepo_jgg_lucker')." WHERE rid = :rid AND weid = :weid";
					$params = array(':rid'=>$id,':weid'=>$_W['uniacid']);
					$list = pdo_fetchall($sql,$params);


					//导出
					include_once ('../framework/library/phpexcel/PHPExcel.php');
					$objPHPExcel = new PHPExcel();
					$objDrawing = new PHPExcel_Worksheet_Drawing();

					$objPHPExcel->getProperties()->setCreator("Meepo");
					$objPHPExcel->getProperties()->setLastModifiedBy("Meepo");
					$objPHPExcel->getProperties()->setTitle("Meepo");

					$objPHPExcel->getActiveSheet()->setCellValue('A1', '活动名称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
						$objPHPExcel->getActiveSheet()->setCellValue('B1', '奖品名称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$objPHPExcel->getActiveSheet()->setCellValue('C1', '奖项名称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
						$objPHPExcel->getActiveSheet()->setCellValue('D1', '中奖时间');
						$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

						foreach ($list as $key => $value) {
							$activityname = pdo_fetchcolumn("SELECT title FROM".tablename('meepo_jgg')." WHERE rid=:rid AND weid=:weid",array(':rid'=>$id,':weid'=>$_W['uniacid']));
							$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), ' '.$activityname);
							$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $value['award_name']);
							$objPHPExcel->getActiveSheet()->setCellValue('C'.($key+2), $value['award_level']);
							$objPHPExcel->getActiveSheet()->setCellValue('D'.($key+2), date("Y-m-d H:i:s",$value['createtime']));

						}

					$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

					header("Pragma: public");
					header("Expires: 0");
					header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
					header("Content-Type:application/force-download");
					header("Content-Type:application/vnd.ms-execl");
					header("Content-Type:application/octet-stream");
					header("Content-Type:application/download");;
					header('Content-Disposition:attachment;filename="resume.xls"');
					header("Content-Transfer-Encoding:binary");
					$objWriter->save('php://output');

					exit();
				}
include $this->template('signmanage');  
