<?php

		global $_W;
		$weid = $_W['uniacid'];
		$result = array();	
		if (empty($_FILES['imgFile']['name'])) {
			$result = error(-1,'请先选择文件');
			die(json_encode($result));
		}
    $back = fileUpload2($_FILES['imgFile'], $type = 'image');//{$_W['attachment']}
		if(is_array($back) && !empty($back['path'])){
				$result = error(0,$back['path']);
				die(json_encode($result));
		}else{
				$result = error(-1,'上传失败');
				die(json_encode($result));
		}
function fileUpload2($file, $type = 'image', $name = '') {
			if(empty($file)) {
				return '-1';
			}
			global $_W;
			if(empty($cfg['size'])){
			   $defsize = 10;
			}
		    $deftype = array('jpg','png','jpeg');
			if (empty($_W['uploadsetting'])) {
				$_W['uploadsetting'] = array();
				$_W['uploadsetting'][$type]['folder'] = 'images';
				$_W['uploadsetting'][$type]['extentions'] = $deftype;
				$_W['uploadsetting'][$type]['limit'] = 1024*$defsize;
			}
			$settings = $_W['uploadsetting'];
			if(!array_key_exists($type, $settings)) {
				return '-1';
			}
			$extention = pathinfo($file['name'], PATHINFO_EXTENSION);
			if(!in_array(strtolower($extention), $settings[$type]['extentions'])) {
				return '-1';
			}
			if(!empty($settings[$type]['limit']) && $settings[$type]['limit'] * 1024 < $file['size']) {
				return '-2';
			}
			$result = array();
			load()->func('file');
			if(empty($name) || $name == 'auto') {
				$result['path'] = "uploadfile/".$_W['uniacid']."/". date('Y/m/');
				mkdirs(MODULE_ROOT . '/' . $result['path']);
				do {
					
					$filename = random(30) . ".{$extention}";
				   
				} while(file_exists(MODULE_ROOT . '/' . $result['path'] . $filename));
				$result['path'] .= $filename;
			} else {
				$result['path'] = $name . '.' .$extention;
			}
			if(!file_move($file['tmp_name'], MODULE_ROOT . '/' . $result['path'])) {
				return '-3';
			}
			return $result; 
     }