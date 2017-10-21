<?php
global $_W,$_GPC;
$id = intval($_GPC['id']);
$weid = $_W['uniacid'];
$mobile_table = 'weixin_mobile_manage';
		   $pindex = max(1, intval($_GPC['page']));
	     $psize = 20;
		   $op = empty($_GPC['op']) ? 'list' : $_GPC['op'];
			 
			if($op == 'list'){
			 if(checksubmit('delall')){
						 pdo_delete($mobile_table,array('weid'=>$_W['uniacid'],'rid'=>$id));
						 message('全部清空成功',$this->createWebUrl("signcheck",array('id'=>$id)),"success");
				}
			  $params = array();
        $where = " weid = :weid AND rid=:rid";
				$params[':weid'] = $_W['uniacid'];
				$params[':rid'] = $id;
				if(!empty($_GPC['mobile'])){
					$where .= " AND mobile LIKE '%{$_GPC['mobile']}%'";
			  }
			  if(!empty($_GPC['realname'])){
					$where .= " AND realname LIKE '%{$_GPC['realname']}%'";
			  }
			  
				$sql = "SELECT * FROM ".tablename($mobile_table)." WHERE {$where} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
				$lists = pdo_fetchall($sql,$params);
				$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($mobile_table) . " WHERE {$where} ", $params);
			  $pager = pagination($total, $pindex, $psize);
		   }elseif($op == 'post'){
			   
					$the_id = intval($_GPC['the_id']);
					if(!empty($the_id)){
		           $check = pdo_fetch("SELECT * FROM ".tablename($mobile_table)." WHERE weid = :weid AND id = :id",array(':weid'=>$_W['uniacid'],':id'=>$the_id));
					}
					if(checksubmit('submit')){
						 $data = array(
						    'weid'=>$_W['uniacid'],		 
                'mobile'=>$_GPC['mobile'],
								 'realname'=>$_GPC['realname'],
								 'rid'=>$id,

						 );
					   if(empty($the_id)){
								 
								 //$data['createtime'] = time();
						    pdo_insert($mobile_table,$data);
								message('新增成功',$this->createWebUrl('signcheck',array('id'=>$id)),'success');
						 }else{
						    pdo_update($mobile_table,$data,array('weid'=>$_W['uniacid'],'id'=>$the_id));
								message('编辑成功',$this->createWebUrl('signcheck',array('id'=>$id)),'success');
						 }
					}
					
			 }elseif($op == 'delete'){
			   
					$the_id = intval($_GPC['the_id']);
					if(empty($the_id)){
		           message('删除项目不存在',$this->createWebUrl("signcheck",array('id'=>$id,'page'=>$pindex)),"error");
					}
					pdo_delete($mobile_table,array('id'=>$the_id,'weid'=>$_W['uniacid']));
					message('删除成功',$this->createWebUrl("signcheck",array('id'=>$id,'page'=>$pindex)),"success");
			 }else{
			   message('访问错误');
			 }

				if(checksubmit('delete')){
					//批量删除
					$select = $_GPC['select'];
					if(empty($select)){
						message('请选择删除项',$this->createWebUrl("signcheck",array('id'=>$id,'page'=>$pindex)),"error");
					}
					foreach ($select as $se) {
						pdo_delete($mobile_table,array('id'=>$se,'weid'=>$_W['uniacid']));
					}
					message('批量删除成功',$this->createWebUrl("signcheck",array('id'=>$id,'page'=>$pindex)),"success");
				}
				/*if(checksubmit('upload')){
						$select = $_GPC['select'];
						if(empty($select)){
						   message('请选择导出项');
						}
						foreach($select as $row){
								$sql = "SELECT * FROM ".tablename($mobile_table)." WHERE  weid = :weid AND id = :id";
								$params = array(':weid'=>$_W['uniacid'],':id'=>$row);
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
							$activityname = pdo_fetchcolumn("SELECT title FROM".tablename($mobile_table)." WHERE weid=:weid",array(':weid'=>$_W['uniacid']));
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
					$sql = "SELECT * FROM ".tablename($mobile_table)." WHERE  weid = :weid";
					$params = array(':weid'=>$_W['uniacid']);
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
							$activityname = pdo_fetchcolumn("SELECT title FROM".tablename($mobile_table)." WHERE  weid=:weid",array(':weid'=>$_W['uniacid']));
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
				}*/

		include $this->template('mobilemanage');