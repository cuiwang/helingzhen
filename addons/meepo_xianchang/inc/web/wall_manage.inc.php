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
$where = " weid = :weid AND rid = :rid AND status = :status";;
if($op=='list'){
	if(!empty($_GPC['nickname'])){
		$where .= " AND nick_name LIKE '%{$_GPC['nickname']}%'";
	}
	$walls = pdo_fetchall("SELECT * FROM ".tablename($this->wall_table)." WHERE {$where} ORDER BY createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,array(':weid'=>$weid,':rid'=>$rid,':status'=>$status));
	if(!empty($walls)){
		foreach($walls as &$row){
			$row['isblacklist'] = pdo_fetchcolumn("SELECT `isblacklist` FROM ".tablename($this->user_table)." WHERE weid=:weid AND rid=:rid AND openid=:openid",array(':weid'=>$weid,':rid'=>$rid,':openid'=>$row['openid']));
		}
		unset($row);
	}
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->wall_table) . " WHERE {$where} ", array(':weid'=>$weid,':rid'=>$rid,':status'=>$status));
	$pager = pagination($total, $pindex, $psize);
}elseif($op=='post'){
	$wall_id = $_GPC['wall_id'];
	if(empty($wall_id)){
		message('消息id错误');
	}else{
		$data = array('status'=>'1');
		pdo_update($this->wall_table,$data,array('id'=>$wall_id));
		message('审核成功',$this->createWebUrl('wall_manage',array('id'=>$id,'status'=>$status,'page'=>$pindex)),"success");
	}
}elseif($op=='del'){
	$wall_id = $_GPC['wall_id'];
	if(empty($wall_id)){
		message('消息id错误');
	}else{
		$data = array('nick_name'=>$_GPC['nick_name']);
		pdo_delete($this->wall_table,array('id'=>$wall_id));
		 message('删除成功',$this->createWebUrl('wall_manage',array('id'=>$id,'status'=>$status,'page'=>$pindex)),"success");
	}
}elseif($op=='drop_black'){
	$openid = $_GPC['openid'];
	if(empty($openid)){
		message('用户openid错误');
	}else{
		if($_GPC['isblacklist']==2){
			$isblacklist = 1;
		}else{
			$isblacklist = 2;
		}
		pdo_update($this->user_table,array('isblacklist'=>$isblacklist),array('openid'=>$openid,'rid'=>$id,'weid'=>$_W['uniacid']));
		message('操作成功',$this->createWebUrl('wall_manage',array('id'=>$id,'status'=>$status,'page'=>$pindex)),"success");
	}
}else{
	message('非法访问');
}
if(checksubmit('delete')){
	//批量删除
	$select = $_GPC['select'];
	if(empty($select)){
		message('请选择删除项',$this->createWebUrl("wall_manage",array('id'=>$id,'status'=>$status,'page'=>$pindex)),"error");
	}
	foreach ($select as $se) {
		pdo_delete($this->wall_table,array('id'=>$se,'rid'=>$id,'weid'=>$_W['uniacid']));
	}
	message('批量删除成功',$this->createWebUrl("wall_manage",array('id'=>$id,'status'=>$status,'page'=>$pindex)),"success");
}
if(checksubmit('signs')){
	//批量删除
	$select = $_GPC['select'];
	if(empty($select)){
		message('请选择审核项',$this->createWebUrl("wall_manage",array('id'=>$id,'status'=>$status,'page'=>$pindex)),"error");
	}
	foreach ($select as $se) {
		pdo_update($this->wall_table,array('status'=>1),array('id'=>$se,'rid'=>$id,'weid'=>$_W['uniacid']));
	}
	message('批量审核成功',$this->createWebUrl("wall_manage",array('id'=>$id,'status'=>$status,'page'=>$pindex)),"success");
}
if(checksubmit('down')){
			if(empty($_GPC['select'])){
					message("请先选择导出项",referer(),'error');
			}
			$up_list = pdo_fetchall("SELECT * FROM ".tablename($this->wall_table)." WHERE  weid=:weid  AND rid=:rid AND status=:status  AND  id  IN  ('".implode("','", $_GPC['select'])."')", array(':weid'=>$weid,':rid'=>$rid,':status'=>$status));
					//导出
						include_once ('../framework/library/phpexcel/PHPExcel.php');
						$objPHPExcel = new PHPExcel();
						$objDrawing = new PHPExcel_Worksheet_Drawing();

						$objPHPExcel->getProperties()->setCreator("Meepo");
						$objPHPExcel->getProperties()->setLastModifiedBy("Meepo");
						$objPHPExcel->getProperties()->setTitle("Meepo");

						$objPHPExcel->getActiveSheet()->setCellValue('A1', '粉丝昵称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$objPHPExcel->getActiveSheet()->setCellValue('B1', '上墙内容');
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(100);
						$objPHPExcel->getActiveSheet()->setCellValue('C1', '时间');
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						foreach ($up_list as $key => $value) {
							//{if $item['type']==1}{php echo $this->emo($item['content'])}{else}
							$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), ' '.$value['nick_name']);
							$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $value['type']=='1'? $value['content']:'图片消息'.$value['content']);
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
						header('Content-Disposition:attachment;filename="上墙记录.xls"');
						header("Content-Transfer-Encoding:binary");
						$objWriter->save('php://output');

						exit();
}
if(checksubmit('downall')){
			
			$up_list = pdo_fetchall("SELECT * FROM ".tablename($this->wall_table)." WHERE  weid=:weid  AND rid=:rid AND status=:status", array(':weid'=>$weid,':rid'=>$rid,':status'=>$status));
					//导出
						include_once ('../framework/library/phpexcel/PHPExcel.php');
						$objPHPExcel = new PHPExcel();
						$objDrawing = new PHPExcel_Worksheet_Drawing();

						$objPHPExcel->getProperties()->setCreator("Meepo");
						$objPHPExcel->getProperties()->setLastModifiedBy("Meepo");
						$objPHPExcel->getProperties()->setTitle("Meepo");

						$objPHPExcel->getActiveSheet()->setCellValue('A1', '粉丝昵称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$objPHPExcel->getActiveSheet()->setCellValue('B1', '上墙内容');
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(100);
						$objPHPExcel->getActiveSheet()->setCellValue('C1', '时间');
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						foreach ($up_list as $key => $value) {
							//{if $item['type']==1}{php echo $this->emo($item['content'])}{else}
							$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), ' '.$value['nick_name']);
							$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $value['type']=='1'? $value['content']:'图片消息'.$value['content']);
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
						header('Content-Disposition:attachment;filename="全部上墙记录.xls"');
						header("Content-Transfer-Encoding:binary");
						$objWriter->save('php://output');

						exit();
}
include $this->template('wall_manage');