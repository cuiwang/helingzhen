<?php
/**
 * 粉丝自动分组模块
 * @author FantasyMoons Team
 * @url http://www.fmoons.com
 */
defined('IN_IA') or exit('Access Denied');

include_once IA_ROOT . '/addons/fm_autogroup/model.php';
class Fm_autogroupModuleSite extends WeModuleSite {

	public function doWebIndex() {
		global $_GPC, $_W;
		$fans_updates = pdo_fetchall("SELECT openid, follow FROM " . tablename('mc_mapping_fans') . " WHERE uniacid = :uniacid ", array(':uniacid' => $_W['uniacid']));
		foreach ($fans_updates as $value) {
			# code...
			
			pdo_update('fm_autogroup_members', array('follow' => $value['follow']), array('uniacid' => $_W['uniacid'], 'from_user'=> $value['openid']));
		}

		$foo = !empty($_GPC['foo']) ? $_GPC['foo'] : 'display';
		
		if ($foo == 'display') {
			include IA_ROOT . '/framework/model/reply.mod.php';
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$sql = "uniacid = :uniacid AND `module` = :module";
			$params = array();
			$params[':uniacid'] = $_W['uniacid'];
			$params[':module'] = 'fm_autogroup';

			if (isset($_GPC['keywords'])) {
				$sql .= ' AND `name` LIKE :keywords';
				$params[':keywords'] = "%{$_GPC['keywords']}%";
			}
			$list = reply_search($sql, $params, $pindex, $psize, $total);
			$pager = pagination($total, $pindex, $psize);

			if (!empty($list)) {
				foreach ($list as &$item) {
					$condition = "`rid`={$item['id']}";
					$item['keywords'] = reply_keywords_search($condition);
					$fm_autogroup = pdo_fetch("SELECT * FROM " . tablename('fm_autogroup_reply') . " WHERE rid = :rid ", array(':rid' => $item['id']));
					$item['title'] = $fm_autogroup['title'];
					$item['description'] = $fm_autogroup['description'];
					$item['fansnum'] = $fm_autogroup['fansnum'];
					$item['viewnum'] = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('fm_autogroup_members')." WHERE uniacid = :uniacid ", array(':uniacid' => $_W['uniacid']));;
					$item['createtime'] = date('Y-m-d H:i', $fm_autogroup['createtime']);
				}
			}
			
		} elseif ($foo == 'userlist') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$condition = '';
			$params = array();
				if (!empty($_GPC['keyword'])) {
					$condition .= " AND nickname LIKE :keyword";
					$params[':keyword'] = "%{$_GPC['keyword']}%";
				}
				if (!empty($_GPC['gname'])) {
					$condition .= " AND gname = :gname";
					$params[':gname'] = $_GPC['gname'];
				
				
				}
				
				if ($_GPC['follow'] == '0') {
					
						$condition .= " AND follow = :follow";
						$params[':follow'] = '0';
					
				}
				if ($_GPC['follow'] == '1') {
					
						$condition .= " AND follow = :follow";
						$params[':follow'] = '1';
					
				}
				
				//if (!empty($_GPC['tag'])) {
				//	$condition .= " AND tag LIKE :tag";
				//	$params[':tag'] = "%{$_GPC['tag']}%";
				//}
				load()->model('mc');

			$list = pdo_fetchall("SELECT * FROM ".tablename('fm_autogroup_members')." WHERE uniacid = '{$_W['uniacid']}' $condition ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm_autogroup_members') . " WHERE uniacid = '{$_W['uniacid']}' $condition", $params);
			$pager = pagination($total, $pindex, $psize);
		} elseif ($foo == 'group') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$condition = '';
			$params = array();
				if (!empty($_GPC['keyword'])) {
					$condition .= " AND gname LIKE :keyword";
					$params[':keyword'] = "%{$_GPC['keyword']}%";
				}
				
				
			$list = pdo_fetchall("SELECT * FROM ".tablename('fm_autogroup_group')." WHERE uniacid = '{$_W['uniacid']}' $condition ORDER BY gname ASC, id DESC LIMIT ".($pindex - 1) * $psize.','.$psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm_autogroup_group') . " WHERE uniacid = '{$_W['uniacid']}' $condition", $params);
			$pager = pagination($total, $pindex, $psize);			
		}
		include $this->template('index');
	}
	
	public function doWebdownload() {
        require_once 'download.php';
    }
	
	public function doWebGroup() {
		global $_GPC, $_W;
		load()->func('tpl');
		load()->func('file');
		$foo = !empty($_GPC['foo']) ? $_GPC['foo'] : 'display';
		
		if ($foo == 'display') {
			if (!empty($_GPC['displayorder'])) {
				foreach ($_GPC['displayorder'] as $id => $displayorder) {
					pdo_update('fm_autogroup_grouplist', array('displayorder' => $displayorder), array('id' => $id));
				}
				message('分组排序更新成功！', 'refresh', 'success');
			}
			$children = array();
			$group = pdo_fetchall("SELECT * FROM ".tablename('fm_autogroup_grouplist')." WHERE uniacid = '{$_W['uniacid']}' ORDER BY parentid ASC, displayorder ASC, id ASC ");
			foreach ($group as $index => $row) {
				if (!empty($row['parentid'])){
					$children[$row['parentid']][] = $row;
					unset($group[$index]);
				}
			}
			if (checksubmit('submit')) {
				if($_GPC['leadExcel'] == "true") {
										
					
					
					$filename = $_FILES['inputExcel']['name'];
					$tmp_name = $_FILES['inputExcel']['tmp_name'];	
					
					$msg = $this->uploadFile($filename,$tmp_name);
					message($msg,referer(),'success');
				}				
			}
			include $this->template('group');
		} elseif ($foo == 'post') {
			$parentid = intval($_GPC['parentid']);
			$id = intval($_GPC['id']);
			//微站风格模板
			//$template = account_template();

			if(!empty($id)) {
				$group = pdo_fetch("SELECT * FROM ".tablename('fm_autogroup_grouplist')." WHERE id = '$id'");
				if (!empty($group['css'])) {
					$group['css'] = iunserializer($group['css']);
				} else {
					$group['css'] = array();
				}
				if (!empty($group['template'])) {
					$files = array();
					if ($group['ishomepage']) {
						$path = IA_ROOT . '/themes/mobile/' . $group['template'];
						$strexists = 'index';
					} else {
						$path = IA_ROOT . '/themes/mobile/' . $group['template'] . '/site';
						$strexists = '.html';
					}
					if (is_dir($path)) {
						if ($handle = opendir($path)) {
							while (false !== ($filepath = readdir($handle))) {
								if ($filepath != '.' && $filepath != '..' && strexists($filepath, $strexists)) {
									$files[] = $filepath;
								}
							}
						}
					}
				}
			} else {
				$group = array(
					'displayorder' => 0,
					'css' => array(),
				);
			}
			if (!empty($parentid)) {
				$parent = pdo_fetch("SELECT id, name FROM ".tablename('fm_autogroup_grouplist')." WHERE id = '$parentid'");
				if (empty($parent)) {
					message('抱歉，上级分组不存在或是已经被删除！', $this->createWebUrl('group', array('foo' => 'display')), 'error');
				}
			}
			if (checksubmit('fileupload-delete')) {
				file_delete($_GPC['fileupload-delete']);
				pdo_update('site_nav', array('icon' => ''), array('id' => $group['nid']));
				message('删除成功！', referer(), 'success');
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['cname'])) {
					message('抱歉，请输入分组名称！');
				}
				if (empty($_GPC['daihao'])) {
					message('抱歉，请输入分组代号！');
				}
				$pathinfo = pathinfo($_GPC['file']);
				$data = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_GPC['cname'],
					'daihao' => $_GPC['daihao'],
					'displayorder' => intval($_GPC['displayorder']),
					'parentid' => intval($parentid),
					'description' => $_GPC['description'],
					'template' => $_GPC['template'],
					'templatefile' => $pathinfo['filename'],
					'linkurl' => $_GPC['linkurl'],
					'ishomepage' => intval($_GPC['ishomepage']),
				);
				
				$data['icontype'] = intval($_GPC['icontype']);
				$data['css'] = serialize(array(
					'icon' => array(
						'font-size' => $_GPC['icon']['size'],
						'color' => $_GPC['icon']['color'],
						'width' => $_GPC['icon']['size'],
						'icon' => $_GPC['icon']['icon'],
					),
				));
				if (!empty($_FILES['icon']['tmp_name'])) {
					file_delete($_GPC['icon_old']);
					$upload = file_upload($_FILES['icon']);
					if (is_error($upload)) {
						message($upload['message'], '', 'error');
					}
					$data['icon'] = $upload['path'];
				}
				if (!empty($id)) {
					unset($data['parentid']);
					pdo_update('fm_autogroup_grouplist', $data, array('id' => $id));
				} else {
					pdo_insert('fm_autogroup_grouplist', $data);
					$id = pdo_insertid();
				}
				message('更新分组成功！', $this->createWebUrl('group'), 'success');
			}
			include $this->template('group');
		} elseif ($foo == 'fetch') {
			$group = pdo_fetchall("SELECT id, name FROM ".tablename('fm_autogroup_grouplist')." WHERE parentid = '".intval($_GPC['parentid'])."' ORDER BY id ASC, displayorder ASC, id ASC ");
			message($group, '', 'ajax');
		} elseif ($foo == 'delete') {
			$id = intval($_GPC['id']);
			$group = pdo_fetch("SELECT id, parentid, nid FROM ".tablename('fm_autogroup_grouplist')." WHERE id = '$id'");
			if (empty($group)) {
				message('抱歉，分组不存在或是已经被删除！', $this->createWebUrl('group'), 'error');
			}
			$navs = pdo_fetchall("SELECT icon, id FROM ".tablename('site_nav')." WHERE id IN (SELECT nid FROM ".tablename('fm_autogroup_grouplist')." WHERE id = {$id} OR parentid = '$id')", array(), 'id');
			if (!empty($navs)) {
				foreach ($navs as $row) {
					file_delete($row['icon']);
				}
				pdo_query("DELETE FROM ".tablename('site_nav')." WHERE id IN (".implode(',', array_keys($navs)).")");
			}
			pdo_delete('fm_autogroup_grouplist', array('id' => $id, 'parentid' => $id), 'OR');
			message('分组删除成功！', $this->createWebUrl('group'), 'success');
		} elseif ($foo == 'templatefiles') {
			$result = array('status' => -1, 'message' => '');
			$template = $_GPC['template'];
			$ishomepage = intval($_GPC['ishomepage']);
			if ($ishomepage) {
				$path = IA_ROOT . '/themes/mobile/' . $template;
				$strexists = 'index';
			} else {
				$path = IA_ROOT . '/themes/mobile/' . $template . '/site';
				$strexists = '.html';
				if (!is_dir($path)) {
					$result['message'] = '请在当前风格下新建“fm_autogroup”目录，并新建分组模板。例如：list_1.html';
					message($result, '', 'ajax');
				}
			}
			$files = array();
			$path .= '';
			if (is_dir($path)) {
				if ($handle = opendir($path)) {
					while (false !== ($filepath = readdir($handle))) {
						if ($filepath != '.' && $filepath != '..' && strexists($filepath, $strexists)) {
							$files[] = $filepath;
						}
					}
				}
			}
			$result['status'] = 0;
			$result['message'] = $files;
			message($result, '', 'ajax');

		}
	
	}
	
	//导入Excel文件
	public function uploadFile($file,$filetempname) {
		global $_GPC,$_W;
		load()->func('file');
		//自己设置的上传文件存放路径
		$filePath = '../attachment/groups/';
		$str = "";   
		//下面的路径按照你PHPExcel的路径来修改
		require_once '../framework/library/phpexcel/PHPExcel.php';
		require_once '../framework/library/phpexcel/PHPExcel/IOFactory.php';
		require_once '../framework/library/phpexcel/PHPExcel/Reader/Excel5.php';
		//注意设置时区
		$time=date("Y-m-d-H-i-s");//去当前上传的时间 
		//获取上传文件的扩展名
		$extend=strrchr ($file,'.');
		//上传后的文件名
		$name=$_W['account']['name'].$time.$extend;
		$uploadfile=$filePath.$name;//上传后的文件名地址 
		//move_uploaded_file() 函数将上传的文件移动到新位置。若成功，则返回 true，否则返回 false。
		$result= move_uploaded_file($filetempname, $uploadfile);		//move_uploaded_file($filetempname,$uploadfile);//假如上传到当前目录下
		
		//print_r($uploadfile);
		//exit;
		//echo $result;
		if($result) {	//如果上传文件成功，就执行导入excel操作
			
			$objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format 
			$objPHPExcel = $objReader->load($uploadfile); 
			$sheet = $objPHPExcel->getSheet(0); 
			$highestRow = $sheet->getHighestRow();           //取得总行数 
			$highestColumn = $sheet->getHighestColumn(); //取得总列数
			
			
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			//echo 'highestRow='.$highestRow;
			//echo "<br>";
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//总列数
			//echo 'highestColumnIndex='.$highestColumnIndex;
			//echo "<br>";
			$headtitle=array(); 
			$uniacid = $_W['uniacid'];
			for ($row = 1;$row <= $highestRow;$row++) {
				$strs=array();
				//注意highestColumnIndex的列数索引从0开始
				for ($col = 0;$col < $highestColumnIndex;$col++) {
					$strs[$col] =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
				}
				//print_r($strs);
				//exit;
				$id = $strs[0]; 					
				$aname = $strs[1];//
				$name = $strs[2];
				$pname = $strs[3];
				$daihao = $strs[4]; 
				$daihaot = substr($daihao,0,3);
				$daihaop = substr($daihao,0,6);
				//strstr($daihao,'005');
				if (empty($aname)) {
					if (!empty($name)) {
						$ag = pdo_fetch("SELECT id,name,daihao,parentid FROM ".tablename('fm_autogroup_grouplist')." WHERE uniacid = '{$_W['uniacid']}' AND daihao = '{$daihaot}' LIMIT 1 ");
						$parentid = $ag['id'];					
						
						
						$g = pdo_fetch("SELECT id,name,daihao,parentid FROM ".tablename('fm_autogroup_grouplist')." WHERE uniacid = '{$_W['uniacid']}' AND daihao = '{$daihao}' LIMIT 1 ");
						
						if (empty($g)) {
							
							//$query = pdo_query("insert into ".tablename('fm_autogroup_grouplist')." (`uniacid`,`name`,`daihao`,`parentid`) values ('{$uniacid}','{$name}','{$daihao}','{$parentid}')");//插入数据表中
							//die($sql);
							$datap = array(
								'uniacid' => $uniacid,
								'displayorder' => $row + 1,
								'name' => $name,
								'daihao' => $daihao,
								'parentid' => $parentid
							);
							$group = pdo_insert('fm_autogroup_grouplist', $datap);
							
							if($group){
								$msg =  '导入成功！';
							}else{ 
								return false;
								$msg =  '导入失败！1';
							} 
						} 
					} else {
						$ag = pdo_fetch("SELECT id,name,daihao,parentid FROM ".tablename('fm_autogroup_grouplist')." WHERE uniacid = '{$_W['uniacid']}' AND daihao = '{$daihaop}' LIMIT 1 ");
						$parentid = $ag['id'];					
						
						
						$g = pdo_fetch("SELECT id,name,daihao,parentid FROM ".tablename('fm_autogroup_grouplist')." WHERE uniacid = '{$_W['uniacid']}' AND daihao = '{$daihao}' LIMIT 1 ");
						
						if (empty($g)) {
							
							//$query = pdo_query("insert into ".tablename('fm_autogroup_grouplist')." (`uniacid`,`name`,`daihao`,`parentid`) values ('{$uniacid}','{$name}','{$daihao}','{$parentid}')");//插入数据表中
							//die($sql);
							$datap = array(
								'uniacid' => $uniacid,
								'displayorder' => $row + 1,
								'name' => $pname,
								'daihao' => $daihao,
								'parentid' => $parentid
							);
							$group = pdo_insert('fm_autogroup_grouplist', $datap);
							
							if($group){
								$msg =  '导入成功！';
							}else{ 
								return false;
								$msg =  '导入失败！2';
							} 
						} 
					}
				}else {
					$ag = pdo_fetch("SELECT id,name,daihao,parentid FROM ".tablename('fm_autogroup_grouplist')." WHERE uniacid = '{$_W['uniacid']}' AND daihao = '{$daihaot}' LIMIT 1 ");					
					if (empty($ag)) {						
						//$query = pdo_query("insert into ".tablename('fm_autogroup_grouplist')." (`uniacid`,`name`,`daihao`) values ('{$uniacid}','{$aname}','{$daihaot}')");
						$data = array(
							'uniacid' => $uniacid,
							'displayorder' => $row,
							'name' => $aname,
							'daihao' => $daihaot
						);
						$group = pdo_insert('fm_autogroup_grouplist', $data);
						$id = pdo_insertid();
						//插入数据表中
						//die($sql);
						if($group){
							$msg =  '导入成功！';
							$datap = array(
								'uniacid' => $uniacid,
								'displayorder' => $row + 1,
								'name' => $name,
								'daihao' => $daihao,
								'parentid' => $id
							);
							pdo_insert('fm_autogroup_grouplist', $datap);
						}else{ 
							return false;
							$msg =  '导入失败！3';
						} 
					} 
					
					
					
				}
				
				
			}
		}else	{
		   $msg = "导入失败！4";
		} 
		return $msg;
	}
	
	public function _getgrouplist($daihao) {
		global $_W;			
		return pdo_fetch("SELECT id,name,daihao,parentid FROM ".tablename('fm_autogroup_grouplist')." WHERE uniacid = '{$_W['uniacid']}' AND daihao = '{$daihao}' LIMIT 1 ");
	}
	public function _getgrouplistid($parentid) {
		global $_W;			
		return pdo_fetch("SELECT id,name FROM ".tablename('fm_autogroup_grouplist')." WHERE uniacid = '{$_W['uniacid']}' AND id = '{$parentid}' LIMIT 1 ");
	}
	public function _users($openid) {
		global $_W;			
		return pdo_fetch("SELECT follow FROM ".tablename('mc_mapping_fans')." WHERE uniacid = '{$_W['uniacid']}' AND openid = '{$openid}' LIMIT 1 ");
	}
	public function _getfollow($gname) {
		global $_GPC, $_W;	
		$follow = array();
		$follow['follow'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm_autogroup_members') . " WHERE uniacid = '{$_W['uniacid']}' AND gname = '{$gname}' AND follow = '1' ");
		$follow['unfollow'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm_autogroup_members') . " WHERE uniacid = '{$_W['uniacid']}' AND gname = '{$gname}' AND follow = '0' ");
		return $follow;
	}
	public function _fansfollow() {
		global $_GPC, $_W;	
		$follow = array();
		$follow['follow'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm_autogroup_members') . " WHERE uniacid = '{$_W['uniacid']}' AND follow = '1' ");
		$follow['unfollow'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm_autogroup_members') . " WHERE uniacid = '{$_W['uniacid']}' AND follow = '0' ");
		return $follow;
	}
	public function _getfscount($gname, $from_user = '') {
		global $_GPC, $_W;	
		
		$fscount = array();
		$fscount['total'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm_autogroup_log') . " WHERE uniacid = '{$_W['uniacid']}' AND gname = '{$gname}' AND from_user = '{$from_user}' ");
		$fscount['totalall'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm_autogroup_log') . " WHERE uniacid = '{$_W['uniacid']}' AND gname = '{$gname}' ");
		
		return $fscount;
	}
	
	public function _getgroup($gname) {
		global $_GPC, $_W;	
		
		//$fscount = array();
		$fscount = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm_autogroup_members') . " WHERE uniacid = '{$_W['uniacid']}' AND gname = '{$gname}' ");
		
		return $fscount;
	}
	



	/*
	 * 登记
	 */
	public function doWebRegister() {
		global $_GPC, $_W;
		$title = '自动分组登记';
		$member = fans_search($_GPC['from'], array('nickname', 'avatar'));
		if (!empty($_GPC['submit'])) {
			$data = array(
				'uniacid' => $_W['uniacid'],
				'nickname' => $_GPC['nickname'],
			);
			if (empty($data['nickname'])) {
				die('<script>alert("请填写您的昵称！");location.reload();</script>');
			}
			$data['avatar'] = $_GPC['avatar_radio'];
			if (!empty($_FILES['avatar']['tmp_name'])) {
				$_W['uploadsetting'] = array();
				$_W['uploadsetting']['fm_autogroup']['folder'] = 'fm_autogroup/avatar';
				$_W['uploadsetting']['fm_autogroup']['extentions'] = $_W['config']['upload']['image']['extentions'];
				$_W['uploadsetting']['fm_autogroup']['limit'] = $_W['config']['upload']['image']['limit'];
				$upload = file_upload($_FILES['avatar'], 'fm_autogroup', $_GPC['from']);
				if (is_error($upload)) {
					die('<script>alert("登记失败！请重试！");location.reload();</script>');
				}
				$data['avatar'] = $upload['path'];
			}
			fans_update($_GPC['from'], $data);
			die('<script>alert("登记成功！现在进入话题发表内容！");location.href = "' . $this->createWebUrl('register', array('from' => $_GPC['from'])).'";</script>');

		}
		include $this->template('register');
	}


	public function doMobileList() {
		global $_GPC, $_W;
		$cid = intval($_GPC['cid']);
		$category = pdo_fetch("SELECT * FROM ".tablename('fm_autogroup_grouplist')." WHERE id = '{$cid}' ");
		if (empty($category)) {
			message('分类不存在或是已经被删除！');
		}
		if (!empty($category['linkurl'])) {
			header('Location: '.$category['linkurl']);
			exit;
		}
		$title = $category['name'];
		if (empty($category['ishomepage'])) {
			//独立选择分类模板
			if(!empty($category['template'])) {
				$_W['account']['template'] = $category['template'];
			}
			if (!empty($category['templatefile'])) {
				include $this->template($category['templatefile']);
				exit;
			} else {
				include $this->template('list');
				exit;
			}
		} else {
			if(!empty($category['template'])) {
				$_W['account']['template'] = $category['template'];
			}
			$navs = pdo_fetchall("SELECT * FROM ".tablename('fm_autogroup_grouplist')." WHERE uniacid = '{$_W['uniacid']}' AND parentid = '$cid' ORDER BY displayorder ASC");
			if (!empty($navs)) {
				foreach ($navs as &$row) {
					$row['url'] = $this->createMobileUrl('list', array('cid' => $row['id']));
					if (!empty($row['icontype']) && $row['icontype'] == 1) {
						$row['css'] = iunserializer($row['css']);
						$row['icon'] = '';
					} 
					if (!empty($row['icontype']) && $row['icontype'] == 2) {
						$row['css'] = '';
					} 
				}
			}
			if (!empty($category['templatefile'])) {
				include $this->template($category['templatefile']);
				exit;
			} else {
				include $this->template('index');
				exit;
			}
		}
	}
	
	public function doMobileRegister() {
		global $_GPC, $_W;
		$title = '自动分组登记';
		$openid = !empty($_W['fans']['from_user']) ? $_W['fans']['from_user'] : $_GPC['openid'];
		//echo $_W['fans']['from_user'];
		$uniacid = $_W['uniacid'];
		//$member = fans_search($_W['fans']['from_user'], array('nickname', 'avatar'));
		$member = pdo_fetch("SELECT * FROM ".tablename('fm_autogroup_members')." WHERE from_user = '{$openid}' AND uniacid = '{$_W['uniacid']}' LIMIT 1");
		
		if (!empty($_GPC['submit'])) {
			$data = array(
				'uniacid' => $_W['uniacid'],
				'nickname' => $_GPC['nickname'],
				'mobile' => $_GPC['mobile'],
				//'gname' =>  $_GPC['gname'],
			);
			if (empty($data['nickname'])) {
				die('<script>alert("请填写您的昵称！");location.reload();</script>');
			}

			//if (!empty($_FILES['avatar']['tmp_name'])) {
			//	$data['avatar'] = $member['avatar'];
			//} else {
			//	$data['avatar'] = $_GPC['avatar_radio'];
			//}
			if (!empty($member)) {
				pdo_update('fm_autogroup_members', $data, array(
					'from_user' => $openid,
					'uniacid' => $uniacid,
				));
			} else {
				
				$date = array(
					'from_user' => $openid,
					'rid' => $_GPC['rid'],
					'uniacid' => $uniacid,
					'mobile' => $_GPC['mobile'],
					'nickname' => $_GPC['nickname'],
					'lastupdate' => TIMESTAMP,
				);
				//if (!empty($_FILES['avatar']['tmp_name'])) {
				//	$date['avatar'] = $member['avatar'];
				//} else {
				//	$date['avatar'] = $_GPC['avatar_radio'];
				//}
				pdo_insert('fm_autogroup_members', $date);
			}
			fans_update($_W['fans']['from_user'], $data);
			die('<script>alert("恭喜您进入 '.$member['gname'].' 成功！");location.href = "'.$this->createMobileUrl('register').'";</script>');
		}
		include $this->template('register');
	}
}
