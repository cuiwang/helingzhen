<?php
       load()->func('file'); 
		   global $_W,$_GPC;
			 $id = intval($_GPC['id']);
       $weid = $_W['uniacid'];
			 $mobile_table = 'weixin_mobile_manage';
			 $upload_table = 'weixin_mobile_upload';
		   $pindex = max(1, intval($_GPC['page']));
	     $psize = 20;
		   $op = empty($_GPC['op']) ? 'list' : $_GPC['op'];
			$upload = '';
			if($op == 'list'){
			  $params = array();
        //$where = " weid = :weid ";
				$where = " weid = :weid AND rid=:rid";
				$params[':weid'] = $_W['uniacid'];
				$params[':rid'] = $id;
				
			  
			  
				$sql = "SELECT * FROM ".tablename($upload_table)." WHERE {$where} ORDER BY createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
				$lists = pdo_fetchall($sql,$params);
				$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($upload_table) . " WHERE {$where} ", $params);
			  $pager = pagination($total, $pindex, $psize);
		   }elseif($op == 'post'){
					  $upload = 'upload';
			       if(checksubmit('fileupload')){
								  if(is_array($_FILES['file']) && !empty($_FILES['file'])){
											$file = $_FILES['file'];
									    $previous_name = $file['name'];
                      $upload = file_upload2($_W['uniacid'],$file);
											if(is_error($upload)){
											   message($upload['message']);
											}else{
													$data = readexl(MODULE_ROOT."/".$upload['path']);
													
											    if(is_array($data) && !empty($data)){
													    foreach($data as $row){
																	pdo_insert($mobile_table,array('weid'=>$_W['uniacid'],'realname'=>$row[0],'mobile'=>$row[1],'rid'=>$id));
															}
															$insert = array(
																	'weid'=>$_W['uniacid'],
																	'previous_name'=>$previous_name,
																	'now_name'=>$upload['nowname'],
																	'file_path'=>$upload['path'],
																	'rid'=>$id,
																	'createtime'=>time()
															);
															pdo_insert($upload_table,$insert);
															message('导出成功！','referer','success');
													}else{
															message('读取xls文件失败！');
													}
											}
									}else{
									  message('请先选择excel类文件');
									}
				     }
			 }elseif($op == 'delete'){
			   
					$the_id = intval($_GPC['the_id']);
					if(empty($id)){
		           message('删除项目不存在',$this->createWebUrl("mobileupload",array('id'=>$id,'page'=>$pindex)),"error");
					}
					$sql = "SELECT file_path FROM ".tablename($upload_table)." WHERE  weid = :weid AND id = :id";
					$params = array(':weid'=>$_W['uniacid'],':id'=>$the_id);
					$file_path = pdo_fetchcolumn($sql,$params);
					if (file_exists(MODULE_ROOT."/".$file_path)) {
		         @unlink(MODULE_ROOT. '/'  . $file_path);
	        }
					pdo_delete($upload_table,array('id'=>$the_id,'weid'=>$_W['uniacid']));
					message('删除成功',$this->createWebUrl("mobileupload",array('id'=>$id,'page'=>$pindex)),"success");
			 }else{
			   message('访问错误');
			 }

				if(checksubmit('delete')){
					//批量删除
					$select = $_GPC['select'];
					if(empty($select)){
						message('请选择删除项',$this->createWebUrl("mobileupload",array('id'=>$id,'page'=>$pindex)),"error");
					}
					foreach ($select as $se) {
							$sql = "SELECT file_path FROM ".tablename($upload_table)." WHERE  weid = :weid AND id = :id";
					    $params = array(':weid'=>$_W['uniacid'],':id'=>$se);
					    $file_path = pdo_fetchcolumn($sql,$params);
								if (file_exists(MODULE_ROOT."/".$file_path)) {
								 @unlink(MODULE_ROOT. '/'  . $file_path);
								}
						pdo_delete($upload_table,array('id'=>$se,'weid'=>$_W['uniacid']));
					}
					message('批量删除成功',$this->createWebUrl("mobileupload",array('id'=>$id,'page'=>$pindex)),"success");
				}


		include $this->template('mobileupload');

function file_upload2($weid,$file,$attname='', $name = '') {
	if(empty($file)) {
		return error(-1, '没有上传内容');
	}
	$deftype = array('xls','xlsx','cvs');
	$extention = pathinfo($file['name'], PATHINFO_EXTENSION);
	if(!in_array(strtolower($extention), $deftype)) {
		return error(-1, '不允许上传此类文件');
	}
	$result = array();
	
	if(empty($name) || $name == 'auto') {
		$result['path'] = "uploadfile/".$_W['uniacid']."/". date('Y/m/');
		mkdirs(MODULE_ROOT . '/' . $result['path']);
		do {
			if(empty($attname)){
					$nowname = random(20);
			    $filename =  $nowname. ".{$extention}";
		  }else{
					$file['name'] = str_replace(".".$extention,'',$file['name']);
					$filename = $file['name'] .random(2). ".{$extention}";
			}
		} while(file_exists(MODULE_ROOT . '/' . $result['path'] . $filename));
		$result['path'] .= $filename;
	} else {
		$result['path'] = $name . '.' .$extention;
	}
	
	if(!file_move($file['tmp_name'], MODULE_ROOT . '/' . $result['path'])) {
		return error(-1, '保存上传文件失败');
	}
	$result['nowname'] = $nowname;
	$result['success'] = true;
	return $result; 
}
function readexl($filename){
            date_default_timezone_set('Asia/ShangHai');
						/** PHPExcel_IOFactory */
						include_once ('../framework/library/phpexcel/PHPExcel/IOFactory.php');
						if (!file_exists($filename)) {
							die($filename.".\n");
						}
						$reader = PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
						$PHPExcel = $reader->load($filename); // 载入excel文件
						$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
						$highestRow = $sheet->getHighestRow(); // 取得总行数
						$highestColumm = $sheet->getHighestColumn(); // 取得总列数
						$highestColumm= PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27
						/** 循环读取每个单元格的数据 */
						for ($row = 2; $row <= $highestRow; $row++){//行数是以第2行开始 去掉代表各列名称的行
								for ($column = 0; $column < $highestColumm; $column++) {//列数是
											$data[$row][] = $sheet->getCellByColumnAndRow($column, $row)->getValue();
								}
						}
						return $data;
}