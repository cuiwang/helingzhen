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
$status = empty($_GPC['status']) ? '1' : $_GPC['status'];
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$where = " weid = :weid AND rid = :rid AND status = :status";
if($op=='list'){
	if(!empty($_GPC['nickname'])){
		$where .= " AND nick_name LIKE '%{$_GPC['nickname']}%'";
	}
	$users = pdo_fetchall("SELECT * FROM ".tablename($this->user_table)." WHERE {$where} ORDER BY createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,array(':weid'=>$weid,':rid'=>$rid,':status'=>$status));
	foreach($users as &$row){
		if($row['nd_id']>0){
			$row['tag_name']  = pdo_fetchcolumn("SELECT `tag_name` FROM ".tablename($this->lottory_award_table)." WHERE id=:id",array(':id'=>$row['nd_id']));
		}
	}
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->user_table) . " WHERE {$where} ", array(':weid'=>$weid,':rid'=>$rid,':status'=>$status));
	$pager = pagination($total, $pindex, $psize);
}elseif($op=='post'){
	$user_id = $_GPC['user_id'];
	if(!empty($user_id)){
		$user = pdo_fetch("SELECT * FROM ".tablename($this->user_table)." WHERE id=:id ",array(':id'=>$user_id));
		
	}else{
		$user['sex'] = 1;
		$user['can_lottory'] = 1;
		$user['isblacklist'] = 1;
	}
	if(checksubmit('submit')){
		$data = array();
		$data['nick_name'] = $_GPC['nick_name'];
		$data['avatar'] = strexists($_GPC['avatar'],'images') ? tomedia($_GPC['avatar']):$_GPC['avatar'];
		$data['sex'] = $_GPC['sex'];
		$data['can_lottory'] = intval($_GPC['can_lottory']);
		$data['isblacklist'] = intval($_GPC['isblacklist']);
		$data['mobile'] = $_GPC['mobile'];
		if(!empty($user_id)){
			pdo_update($this->user_table,$data,array('id'=>$user_id));
			pdo_update($this->qd_table,array('nick_name'=>$data['nick_name'],'avatar'=>$data['avatar']),array('openid'=>$user['openid'],'rid'=>$rid,'weid'=>$weid));
			pdo_update($this->wall_table,array('nick_name'=>$data['nick_name'],'avatar'=>$data['avatar']),array('openid'=>$user['openid'],'rid'=>$rid,'weid'=>$weid));
			message('保存成功',$this->createWebUrl('user_manage',array('id'=>$rid)),'success');
		}else{
			$data['weid'] = $weid;
			$data['rid'] = $rid;
			$data['openid'] = random(32);
			$data['createtime'] = time();
			pdo_insert($this->user_table,$data);
			if($_GPC['had_sign']==1){
				pdo_insert($this->qd_table,array('nick_name'=>$data['nick_name'],'avatar'=>$data['avatar'],'openid'=>$data['openid'],'weid'=>$weid,'rid'=>$rid,'createtime'=>time(),'level'=>1));
			}
			message('新增用户成功',$this->createWebUrl('user_manage',array('id'=>$rid)),'success');
		}
	}
}elseif($op=='shenhe'){
	$user_id = $_GPC['user_id'];
	if(empty($user_id)){
		message('粉丝id错误');
	}else{
		$data = array('status'=>'1');
		pdo_update($this->user_table,$data,array('id'=>$user_id));
		message('审核成功',$this->createWebUrl('user_manage',array('id'=>$id,'status'=>$status,'page'=>$pindex)),"success");
	}
}elseif($op=='can_lottory'){
	$user_id = $_GPC['user_id'];
	if(empty($user_id)){
		message('粉丝id错误');
	}else{
		$can_lottory = intval($_GPC['can_lottory']);
		if($can_lottory==1){
			$can_lottory  = 2;
			$message = '限制中奖成功';
		}else{
			$can_lottory = 1;
			$message = '移除限制成功';
		}
		$data = array('can_lottory'=>$can_lottory);
		pdo_update($this->user_table,$data,array('id'=>$user_id));
		message($message,$this->createWebUrl('user_manage',array('id'=>$id,'status'=>$status,'page'=>$pindex)),"success");
	}
}elseif($op=='isblacklist'){
	$user_id = $_GPC['user_id'];
	if(empty($user_id)){
		message('粉丝id错误');
	}else{
		$isblacklist = intval($_GPC['isblacklist']);
		if($isblacklist==1){
			$isblacklist  = 2;
			$message = '加入黑名单成功';
		}else{
			$isblacklist = 1;
			$message = '移除黑名单成功';
		}
		$data = array('isblacklist'=>$isblacklist);
		pdo_update($this->user_table,$data,array('id'=>$user_id));
		message($message,$this->createWebUrl('user_manage',array('id'=>$id,'status'=>$status,'page'=>$pindex)),"success");
	}
}elseif($op=='del'){
	$user_id = $_GPC['user_id'];
	if(empty($user_id)){
		message('粉丝id错误');
	}else{
		$data = array('nick_name'=>$_GPC['nick_name']);
		$openid = pdo_fetchcolumn("SELECT `openid` FROM ".tablename($this->user_table)."WHERE id=:id AND rid=:rid",array(':id'=>$user_id,':rid'=>$rid));
		pdo_delete($this->xysjh_record_table,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
		pdo_delete($this->qd_table,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
		pdo_delete($this->wall_table,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
		pdo_delete($this->lottory_user_table,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
		pdo_delete($this->vote_record,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
		pdo_delete($this->bd_data_table,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
		pdo_delete($this->shake_user_table,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
		pdo_delete($this->redpack_user_table,array('openid'=>$openid,'rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->user_table,array('id'=>$user_id));
		message('删除成功',$this->createWebUrl('user_manage',array('id'=>$id,'status'=>$status,'page'=>$pindex)),"success");
	}
}elseif($op=='upload_user'){
	//load()->func('file');
    if(checksubmit('fileupload')){
		  if(is_array($_FILES['file']) && !empty($_FILES['file'])){
					$file = $_FILES['file'];
					$upload = $this->file_upload2($weid,$file);
					if(is_error($upload)){
					   message($upload['message']);
					}else{
						$data = $this->readexl(MODULE_ROOT."/".$upload['path']);
						if(is_array($data) && !empty($data)){
							foreach($data as $row){
									$insert_data = array();
									$insert_data['weid'] = $weid;
									$insert_data['rid'] = $rid;
									$insert_data['openid'] = !empty($row[6]) ? $row[6]:random(32);
									$insert_data['nick_name'] = $row[0];
									$insert_data['sex'] = $row[2];
									$rand = mt_rand(1, 12 );
									$insert_data['avatar'] = !empty($row[1]) ? tomedia($row[1]):$_W['siteroot'].'attachment/images/global/avatars/avatar_'.$rand.'.jpg';
									$insert_data['isblacklist'] = intval($row[3]);
									$insert_data['can_lottory'] = intval($row[4]);
									$insert_data['status'] = intval($row[5]);
									$insert_data['mobile'] = trimall($row[8]);
									$insert_data['group'] = 0;
									$insert_data['createtime'] = time();
									if($row[7]==1){
										$insert_data['qd_status'] = 1;
									}
									pdo_insert($this->user_table,$insert_data);
									if($row[7]==1){
										pdo_insert($this->qd_table,array('nick_name'=>$insert_data['nick_name'],'avatar'=>$insert_data['avatar'],'openid'=>$insert_data['openid'],'weid'=>$weid,'rid'=>$rid,'createtime'=>time(),'level'=>1));
										
									}
							}
							message('导入成功！','referer','success');
						}else{
							message('读取excel文件失败！');
						}
					}
		}else{
			 message('请先选择excel类文件');
		}
   }	
}elseif($op=='confirm_user'){
	//$confirm_user = pdo
}elseif($op=='show_bd_data'){
	$openid = $_GPC['openid'];
	$rid = $_GPC['rid'];
	$bd_xm = iunserializer(pdo_fetchcolumn("SELECT `xm` FROM ".tablename($this->bd_manage_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid)));
	$fans_bd_data = iunserializer(pdo_fetchcolumn("SELECT `data` FROM ".tablename($this->bd_data_table)." WHERE weid=:weid AND rid=:rid AND openid = :openid",array(':weid'=>$weid,':rid'=>$rid,':openid'=>$openid)));
	$html = '<div class="panel panel-default" style="height:200px;overflow: auto;">
					<div class="panel-heading">
						<div style="height:40px;text-align:Center;color:#000;line-height:40px;font-size:20px;">录入数据</div>
					</div>
					<div class="panel-body table-responsive">
						<table class="table table-hover" style="display:auto;">
							<thead class="navbar-inner">';

								if(!empty($bd_xm)){
									foreach($bd_xm as $row){
										$html .= '<th style="text-align:center">'.$row['bd_name'].'</th>';
									}
								}
							$html .= '</thead><tbody><tr>';
	if(!empty($bd_xm) && !empty($fans_bd_data)){
		foreach($bd_xm as $row){
			$html .= '<td class="row-hover" style="text-align:center">'.$fans_bd_data[$row['zd_name']].'</td>';
		}
	}else{
		$html .= '<td style="text-align:center;color:Red;width:100%">暂未录入数据</td>';
	}
	$html .= '</tr></tbody></table></div></div>';
	
   $data = array();
	$data['ret'] = 0;
	$data['data'] = $html;
	die(json_encode($data));
	
}elseif($op=='reset'){
	$del_users = pdo_fetchall("SELECT * FROM ".tablename($this->user_table)." WHERE weid = :weid AND rid = :rid  ORDER BY createtime DESC",array(':weid'=>$weid,':rid'=>$rid));
	if(!empty($del_users)){
		foreach ($del_users as $val) {
			$openid = $val['openid'];
			pdo_delete($this->lottory_user_table,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
			pdo_delete($this->qd_table,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
			pdo_delete($this->wall_table,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
			pdo_delete($this->vote_record,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
			pdo_delete($this->bd_data_table,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
			pdo_delete($this->shake_user_table,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
			pdo_delete($this->redpack_user_table,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
			pdo_delete($this->xysjh_record_table,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
		}
		pdo_delete($this->user_table,array('weid'=>$weid,'rid'=>$rid));
		
	}
	message('清空用户数据成功',$this->createWebUrl('qd_manage',array('id'=>$id,'level'=>$level,'page'=>$pindex)),"success");
}else{
	message('非法访问');
}
if(checksubmit('delete')){
	//批量删除
	$select = $_GPC['select'];
	if(empty($select)){
		message('请选择删除项',$this->createWebUrl("user_manage",array('id'=>$id,'status'=>$status,'page'=>$pindex)),"error");
	}
	foreach ($select as $se) {
		$openid = pdo_fetchcolumn("SELECT `openid` FROM ".tablename($this->user_table)."WHERE id=:id AND rid=:rid",array(':id'=>$se,':rid'=>$rid));
		pdo_delete($this->xysjh_record_table,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
		pdo_delete($this->lottory_user_table,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
		pdo_delete($this->qd_table,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
		pdo_delete($this->wall_table,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
		pdo_delete($this->vote_record,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
		pdo_delete($this->bd_data_table,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
		pdo_delete($this->shake_user_table,array('openid'=>$openid,'weid'=>$weid,'rid'=>$rid));
		pdo_delete($this->redpack_user_table,array('openid'=>$openid,'rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete($this->user_table,array('id'=>$se,'rid'=>$id,'weid'=>$_W['uniacid']));
		
	}
	message('批量删除成功',$this->createWebUrl("user_manage",array('id'=>$id,'status'=>$status,'page'=>$pindex)),"success");
}
if(checksubmit('user')){
	//批量删除
	$select = $_GPC['select'];
	if(empty($select)){
		message('请选择审核项',$this->createWebUrl("user_manage",array('id'=>$id,'status'=>$status,'page'=>$pindex)),"error");
	}
	foreach ($select as $se) {
		pdo_update($this->user_table,array('status'=>1),array('id'=>$se,'rid'=>$id,'weid'=>$_W['uniacid']));
	}
	message('批量审核成功',$this->createWebUrl("user_manage",array('id'=>$id,'status'=>$status,'page'=>$pindex)),"success");
}
$upload_arr = array('D','E','F','G','H','I','J','K','L','M','N','O','P','Q');
if(checksubmit('down')){
			if(empty($_GPC['select'])){
					message("请先选择导出项",referer(),'error');
			}
			$up_list = pdo_fetchall("SELECT * FROM ".tablename($this->user_table)." WHERE  weid=:weid  AND rid=:rid AND  id  IN  ('".implode("','", $_GPC['select'])."')", array(':weid'=>$weid,':rid'=>$rid));
					

					
					//导出
						include_once ('../framework/library/phpexcel/PHPExcel.php');
						$objPHPExcel = new PHPExcel();
						$objDrawing = new PHPExcel_Worksheet_Drawing();
						
						$objPHPExcel->getProperties()->setCreator("Meepo");
						$objPHPExcel->getProperties()->setLastModifiedBy("Meepo");
						$objPHPExcel->getProperties()->setTitle("Meepo");

						$objPHPExcel->getActiveSheet()->setCellValue('A1', '粉丝昵称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
						$objPHPExcel->getActiveSheet()->setCellValue('B1', '状态');
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$objPHPExcel->getActiveSheet()->setCellValue('C1', '加入时间');
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
						$bd_xm2 = iunserializer(pdo_fetchcolumn("SELECT `xm` FROM ".tablename($this->bd_manage_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid)));
						if(is_array($bd_xm2) && count($bd_xm2)>0){
							$bd_i = 0;
							foreach($bd_xm2 as $v){
								$objPHPExcel->getActiveSheet()->setCellValue($upload_arr[$bd_i].'1', $v['bd_name']);
						        $objPHPExcel->getActiveSheet()->getColumnDimension($upload_arr[$bd_i])->setWidth(20);
								$bd_i++;
							}
						}
						
						//$objPHPExcel->getActiveSheet()->getStyle('E0:E2000')->getAlignment()->setWrapText(TRUE);
						foreach ($up_list as $key => $value) {
							$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), ' '.$value['nick_name']);
							$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $value['status']==1?'审核通过':'未通过审核');
							$objPHPExcel->getActiveSheet()->setCellValue('C'.($key+2), date('Y-m-d H:i:s',$value['createtime']));
							$openid = pdo_fetchcolumn("SELECT `openid` FROM ".tablename($this->user_table)."WHERE id=:id AND rid=:rid",array(':id'=>$value['id'],':rid'=>$rid));
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
						header('Content-Disposition:attachment;filename="用户名单.xls"');
						header("Content-Transfer-Encoding:binary");
						$objWriter->save('php://output');

						exit();
}
if(checksubmit('downall')){
			$up_list = pdo_fetchall("SELECT * FROM ".tablename($this->user_table)." WHERE  weid=:weid  AND rid=:rid", array(':weid'=>$weid,':rid'=>$rid));
					
			if(empty($up_list)){
				message("没有数据、导出失败",referer(),'error');
			}
					
					include_once ('../framework/library/phpexcel/PHPExcel.php');
						$objPHPExcel = new PHPExcel();
						$objDrawing = new PHPExcel_Worksheet_Drawing();
						
						$objPHPExcel->getProperties()->setCreator("Meepo");
						$objPHPExcel->getProperties()->setLastModifiedBy("Meepo");
						$objPHPExcel->getProperties()->setTitle("Meepo");

						$objPHPExcel->getActiveSheet()->setCellValue('A1', '粉丝昵称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
						$objPHPExcel->getActiveSheet()->setCellValue('B1', '状态');
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$objPHPExcel->getActiveSheet()->setCellValue('C1', '加入时间');
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
						$bd_xm2 = iunserializer(pdo_fetchcolumn("SELECT `xm` FROM ".tablename($this->bd_manage_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid)));
						if(count($bd_xm2)>0){
							$bd_i = 0;
							foreach($bd_xm2 as $v){
								$objPHPExcel->getActiveSheet()->setCellValue($upload_arr[$bd_i].'1', $v['bd_name']);
						        $objPHPExcel->getActiveSheet()->getColumnDimension($upload_arr[$bd_i])->setWidth(20);
								$bd_i++;
							}
						}
						
						//$objPHPExcel->getActiveSheet()->getStyle('E0:E2000')->getAlignment()->setWrapText(TRUE);
						foreach ($up_list as $key => $value) {
							$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), ' '.$value['nick_name']);
							$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $value['status']==1?'审核通过':'未通过审核');
							$objPHPExcel->getActiveSheet()->setCellValue('C'.($key+2), date('Y-m-d H:i:s',$value['createtime']));
							$openid = pdo_fetchcolumn("SELECT `openid` FROM ".tablename($this->user_table)."WHERE id=:id AND rid=:rid",array(':id'=>$value['id'],':rid'=>$rid));
							if(count($bd_xm2)>0){
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
						header('Content-Disposition:attachment;filename="用户名单.xls"');
						header("Content-Transfer-Encoding:binary");
						$objWriter->save('php://output');

						exit();
}
function trimall($str){
    $qian=array(" ","　","\t","\n","\r");$hou=array("","","","","");
    return str_replace($qian,$hou,$str);    
}
include $this->template('user_manage');