<?php
		// 
		//  machine.inc.php
		//  <project>
		//  机器人列表，添加，删除，修改，ip段上传，ip段下载，名称上传，名称下载
		//  Created by Administrator on 2016-06-07.
		//  Copyright 2016 Administrator. All rights reserved.
		// 
		global $_GPC,$_W;
		load()->func('communication');
		
		$op = !empty($_GPC['op'])?$_GPC['op']:'display';
		
		if($op == 'display'){
			//进入页面机器人列表
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$first = 1;
			
			$sql_goods = "select id,title from".tablename('weliam_indiana_goodslist')."where uniacid = :uniacid and status = :status order by createtime desc";
			$data_goods = array(
				':status'=>2,
				':uniacid'=>$_W['uniacid']
			);
			$result_goods = pdo_fetchall($sql_goods,$data_goods);
			
			$sql_machineset = "select id,uniacid,period_number,machine_num,createtime,start_time,end_time,next_time,status,max_num,timebucket,is_followed,goodsid,all_buy from".tablename('weliam_indiana_machineset')."where uniacid = :uniacid and period_number like '201%'". " LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$data_machineset = array(
				':uniacid'=>$_W['uniacid']
			);
			$result_machineset = pdo_fetchall($sql_machineset,$data_machineset);
			$total = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename('weliam_indiana_machineset') . " WHERE uniacid = '{$_W['uniacid']}'");
			$pager = pagination($total, $pindex, $psize);

			foreach($result_machineset as $key => $value){
				$sql_sele_goods = "select id,title,periods,maxperiods,picarr,price,init_money from".tablename('weliam_indiana_goodslist')."where uniacid = :uniacid and id = :id";
				$data_sele_goods = array(
					':uniacid'=>$_W['uniacid'],
					':id'=>$value['goodsid']
				);
				$result_sele_goods = pdo_fetch($sql_sele_goods,$data_sele_goods);
				$result_machineset[$key]['title'] = $result_sele_goods['title'];
				$result_machineset[$key]['init_money'] = $result_sele_goods['init_money'];
				$result_machineset[$key]['price'] = $result_sele_goods['price'];
				$result_machineset[$key]['times'] = $result_sele_goods['price']/$result_sele_goods['init_money'];
				$result_machineset[$key]['periods'] = $result_sele_goods['periods'];
				$result_machineset[$key]['maxperiods'] = $result_sele_goods['maxperiods'];
				$result_machineset[$key]['picarr'] = tomedia($result_sele_goods['picarr']);
				$result_machineset[$key]['sort'] = ($pindex - 1) * $psize + $first;
				$first++;
			}
			/*echo '<pre>';print_r($result_machineset);exit;*/
			include $this->template('machine_display');
		}

		if($op == 'import_nickname'){
			//导入虚拟用户名称
			$file = $_FILES['file'];
			$max_size = "2000000";
			$fname = $file['name'];
			$ftype = strtolower(substr(strrchr($fname, '.'), 1));
			//文件格式
			$all_number = 0;		//统计总导入数量
			$success_number = 0;	//统计成功数量
			$error_number = 0;		//统计失败数量
			
			$uploadfile = $file['tmp_name'];
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				if (is_uploaded_file($uploadfile)) {
					if ($file['size'] > $max_size) {
						message("导入文件过大!",referer(),'error');
						exit ;
					}
					if ($ftype == 'xls') {
						//导入xls文件
						require_once '../framework/library/phpexcel/PHPExcel.php';
						$objReader = PHPExcel_IOFactory::createReader('Excel5');
						$objPHPExcel = $objReader -> load($uploadfile);
						$sheet = $objPHPExcel -> getSheet(0);
						$highestRow = $sheet -> getHighestRow();
						$data = array();
						for ($j = 2; $j <= $highestRow; $j++) {
							$data['data'] = trim($objPHPExcel -> getActiveSheet() -> getCell("A$j") -> getValue());
							$data['type'] = 1;
							$data['uniacid'] = $_W['uniacid'];
							$all_number++;
							if($data['data'] != '' && $data['data'] != 1){
								$result = pdo_insert("weliam_indiana_in",$data);
								if($result == 1){
									$success_number++;
								}else{
									$error_number++;
								}
							}
						}
						message("导入成功！总共检测到【".$all_number."】个名称，成功导入【".$success_number."】个，导入失败【".$error_number."】个（失败原因：格式不规范，名称有重复）",referer(),'success');
					}elseif($ftype == 'csv'){
						//导入cvs文件
						$handle = fopen($uploadfile, 'r'); 
						$n = 0; 
						$datam = array();
					    while ($data = fgetcsv($handle, 10000)) { 
					        $num = count($data); 
					        for ($i = 0; $i < $num; $i++) { 
					            $out[$n][$i] = $data[$i]; 
					        } 
					        $n++; 
					    } 
					    $result = $out; //解析csv 
					    $len_result = count($result); 
					    if($len_result==0){ 
					        echo '没有任何数据！'; 
					        exit; 
					    } 
						$succ_result = 0;
						$error_result = 0;
					    for ($i = 1; $i < $len_result; $i++) { //循环获取各字段值 
					        $datam['data'] = trim(iconv('gb2312', 'utf-8', $result[$i][0])); //中文转码 
							$datam['type'] = 1;
							$datam['uniacid'] = $_W['uniacid'];
							$all_number++;
							if($data['data'] != '' && $data['data'] != 1){
								$result = pdo_insert("weliam_indiana_in",$data);
								if($result == 1){
									$success_number++;
								}else{
									$error_number++;
								}
							}
					    } 
					    fclose($handle); //关闭指针
					    message("导入成功！总共检测到【".$all_number."】个名称，成功导入【".$success_number."】个，导入失败【".$error_number."】个（失败原因：格式不规范，名称有重复）",referer(),'success');
					}else{
						message("文件后缀格式必须为xls或者csv!",referer(),'success');
						exit ;
					}
				} else {
					message("文件不能为空!",referer(),'error');
					exit ;
				}
			}
		}

		if($op == 'import_IP'){
			//导入虚拟用户IP段
			$file = $_FILES['file'];
			$max_size = "2000000";
			$fname = $file['name'];
			$ftype = strtolower(substr(strrchr($fname, '.'), 1));
			//文件格式
			$all_number = 0;		//统计总导入数量
			$success_number = 0;	//统计成功数量
			$error_number = 0;		//统计失败数量
			
			$uploadfile = $file['tmp_name'];
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				if (is_uploaded_file($uploadfile)) {
					if ($file['size'] > $max_size) {
						message("导入文件过大!",referer(),'error');
						exit ;
					}
					if ($ftype == 'xls') {
						//导入xls文件
						require_once '../framework/library/phpexcel/PHPExcel.php';
						$objReader = PHPExcel_IOFactory::createReader('Excel5');
						$objPHPExcel = $objReader -> load($uploadfile);
						$sheet = $objPHPExcel -> getSheet(0);
						$highestRow = $sheet -> getHighestRow();
						$data = array();
						for ($j = 2; $j <= $highestRow; $j++) {
							$ip_start = preg_match('/^((?:(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)))\.){3}(?:25[0-5]|2[0-4]\d|((1\d{2})|([1 -9]?\d))))$/', trim($objPHPExcel -> getActiveSheet() -> getCell("A$j") -> getValue()));
							$ip_end = preg_match('/^((?:(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)))\.){3}(?:25[0-5]|2[0-4]\d|((1\d{2})|([1 -9]?\d))))$/', trim($objPHPExcel -> getActiveSheet() -> getCell("B$j") -> getValue()));
							$data['data'] = trim($objPHPExcel -> getActiveSheet() -> getCell("A$j") -> getValue()).'-'.trim($objPHPExcel -> getActiveSheet() -> getCell("B$j") -> getValue());
							$data['type'] = 2;
							$data['uniacid'] = $_W['uniacid'];
							$all_number++;
							$result = pdo_fetch("select id from ".tablename('weliam_indiana_in')."where type = 2 and data = '{$data['data']}' and uniacid='{$_W['uniacid']}'");
							if(empty($result)&&$ip_start==1&&$ip_end==1){
								pdo_insert("weliam_indiana_in",$data);
								$success_number++;
							}else{
								$error_number++;
							}
						}
						message("导入成功！总共检测到【".$all_number."】个IP段，成功导入【".$success_number."】个，导入失败【".$error_number."】个（失败原因：格式不规范，名称有重复）",referer(),'success');
					}elseif($ftype == 'csv'){
						//导入cvs文件
						$handle = fopen($uploadfile, 'r'); 
						$n = 0; 
						$datam = array();
					    while ($data = fgetcsv($handle, 10000)) { 
					        $num = count($data); 
					        for ($i = 0; $i < $num; $i++) { 
					            $out[$n][$i] = $data[$i]; 
					        } 
					        $n++; 
					    } 
					    $result = $out; //解析csv 
					    $len_result = count($result); 
					    if($len_result==0){ 
					        echo '没有任何数据！'; 
					        exit; 
					    } 
						$succ_result = 0;
						$error_result = 0;
					    for ($i = 1; $i < $len_result; $i++) { //循环获取各字段值 
					        $datam['data'] = trim(iconv('gb2312', 'utf-8', $result[$i][0])); //中文转码 
							$datam['type'] = 2;
							$datam['uniacid'] = $_W['uniacid'];
							$all_number++;
							$result = pdo_fetch("select id from ".tablename('weliam_indiana_in')."where type = 2 and data = '{$data['data']}'");
							if(empty($result)){
								pdo_insert("weliam_indiana_in",$data);
								$success_number++;
							}else{
								$error_number++;
							}
					    } 
					    fclose($handle); //关闭指针
					    message("导入成功！总共检测到【".$all_number."】个IP段，成功导入【".$success_number."】个，导入失败【".$error_number."】个（失败原因：格式不规范，名称有重复）",referer(),'success');
					}else{
						message("文件后缀格式必须为xls或者csv!",referer(),'success');
						exit ;
					}
				} else {
					message("文件不能为空!",referer(),'error');
					exit ;
				}
			}
		}

		if($op == 'download_nickname'){
			//下载名称模板
			$filename=realpath("../addons/weliam_indiana/static/download/module_nickname.xls"); //文件名 
			Header( "Content-type:  application/octet-stream ");  
			Header( "Accept-Ranges:  bytes "); 
			Header( "Accept-Length: " .filesize($filename)); 
			header( "Content-Disposition:  attachment;  filename= module_nickname.xls");  
			echo file_get_contents($filename); 
			readfile($filename); 
		}
					
		if($op == 'download_IP'){
			//下载生成ip模板
			$filename=realpath("../addons/weliam_indiana/static/download/module_IP.xls"); //文件名 
			Header( "Content-type:  application/octet-stream ");  
			Header( "Accept-Ranges:  bytes "); 
			Header( "Accept-Length: " .filesize($filename)); 
			header( "Content-Disposition:  attachment;  filename= module_IP.xls");  
			echo file_get_contents($filename); 
			readfile($filename); 
		}
		
		if($op == 'create_machine'){
			//创建机器人
			$url='../addons/weliam_indiana/static/head_imgs'; //放图片的文件夹路径名称
			$start_number = $_GPC['start_number'];		//开始检测数量
			$all_number = $_GPC['all_number'];			//总数量
			$left_number = $all_number - $start_number;	//剩余创建个数
			
			if($left_number < 25){
				//剩余一次创建结束
				$head_imgs_array = m('order')->get_head_img($url, $left_number);
				$nickname_arr = m('machine')->get_machine_name($left_number);
				$this->WL_log('machine','nickname_arr',$nickname_arr);
				$ip_arr = m('machine')->get_machine_IP($left_number);
				for($i=0;$i<$left_number;$i++){
					$api = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip={$ip_arr[$i]}"; 
					$json = @file_get_contents($api);//调用新浪IP地址库 
					$arr = json_decode($json,true);
					pdo_insert('weliam_indiana_member',array('nickname'=>$nickname_arr[$i],'avatar'=>$head_imgs_array[$i],'type'=>-1,'uniacid'=>$_W['uniacid'],'ip'=>$ip_arr[$i]));
					$openid = pdo_insertid();
					pdo_update('weliam_indiana_member',array('openid'=>'machine'.$openid),array('mid'=>$openid));
				}
				message("机器人创建成功",$this->createWebUrl('machine'),'success');
			}else{
				$head_imgs_array = m('order')->get_head_img($url, 25);
				$nickname_arr = m('machine')->get_machine_name(25);
				$ip_arr = m('machine')->get_machine_IP(25);
				
				for($i=0;$i<25;$i++){
					$api = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip={$ip_arr[$i]}"; 
					$json = @file_get_contents($api);//调用新浪IP地址库 
					$arr = json_decode($json,true);
					pdo_insert('weliam_indiana_member',array('nickname'=>$nickname_arr[$i],'avatar'=>$head_imgs_array[$i],'type'=>-1,'uniacid'=>$_W['uniacid'],'ip'=>$ip_arr[$i]));
					$openid = pdo_insertid();
					pdo_update('weliam_indiana_member',array('openid'=>'machine'.$openid),array('mid'=>$openid));
				}
				$start_number = $start_number + 25;
				message("机器人创建中：总需创建【".$all_number."】，现已创建【".$start_number."】。（请勿关闭浏览器）",$this->createWebUrl('machine',array('start_number'=>$start_number,'all_number'=>$all_number,'op'=>'create_machine')),'success');
			}
		}

		/************************************************************************************************************************************/
		//
		//
		//使用机器人的商品的创建、修改、删除
		//
		//
		/***********************************************************************************************************************************/
		
		if($op == 'create_goods'){
			//创建使用使用机器人的商品
			$data['goodsid'] = $_GPC['goodsid'];		//商品id
			if($data['goodsid'] == '--选择--'){
				message('未选择商品',referer(),'error');exit;		//判定是否未选择
			}else{
				//判定是否已经创建过机器人
				$sql = "select id from".tablename('weliam_indiana_machineset')."where uniacid=:uniacid and goodsid=:goodsid and status in(0,1)";
				$datam  =  array(
					':uniacid'=>$_W['uniacid'],
					':goodsid'=>$data['goodsid']
				);
				$result = pdo_fetchcolumn($sql,$datam);
				if(!empty($result)){
					message('该商品正在使用机器人，不能重复创建！',referer(),'error');exit;
				}
			}
			$data['start_time'] = ((strtotime($_GPC['start_time']))+28800) % 86400;	//每天开始时间
			$data['end_time'] = ((strtotime($_GPC['end_time']))+28800) % 86400;		//每天结束时间
			if($data['start_time'] >= $data['end_time']){
				message('购买开始时间必须小于购买结束时间！！',referer(),'error');exit;
			}
			
			$data['max_num'] = !empty($_GPC['max_number'])?intval($_GPC['max_number']):9999999;	//最大购买量(默认9999999)
			$this->WL_log('machine','data',$data['max_num']);
			if(!is_int($data['max_num']) || $data['max_num'] < 1){
				message('最大购买量默认9999999个；但是填写数只能是正整数，或者不填写！！',referer(),'error');exit;
			}
			
			$data['machine_num'] = !empty($_GPC['machine_num'])?intval($_GPC['machine_num']):3;//购买数量范围（默认购买范围3）
			if(!is_int($data['machine_num']) || $data['machine_num'] < 1){
				message('购买数量范围默认为3；但是填写数只能是正整数，或者不填写！！',referer(),'error');exit;
			}
			
			
			$data['timebucket'] = !empty($_GPC['timebucket'])?intval($_GPC['timebucket']):300;//购买时间范围(默认购买时间300秒)
			if(!is_int($data['timebucket']) || $data['timebucket'] < 1){
				message('购买时间范围默认为300；但是填写数只能是正整数，或者不填写！！',referer(),'error');exit;
			}
			
			$data['is_followed'] = $_GPC['is_followed'];//是否连期
			if($data['is_followed'] == 'on'){
				$data['is_followed'] = 1;
			}else{
				$data['is_followed'] = 0;
			}
			
			$data['uniacid'] = $_W['uniacid'];			//公共号id
			$data['createtime'] = time();
			$data['status'] = 1;
			$data['all_buy'] = 0;
			
			$data['period_number'] = m('machine')->get_new_period_number($data['goodsid']);		//获取商品期号
			$data['next_time'] = time() + rand(1, $data['timebucket']);						//下次购买时间
			if(empty($data['period_number'])){
				message('商品id为【'.$data['goodsid'].'】的商品可能已售空或者未处于上架状态。',referer(),'error');exit;
			}
			$result_insert = pdo_insert('weliam_indiana_machineset',$data);						//添加商品机器人
			if($result_insert == 1){
				message('期号为【'.$data['period_number'].'】，商品id为【'.$data['goodsid'].'】的商品使用机器人创建成功。如果未开启执行程序请开启执行程序。',referer(),'success');exit;
			}else{
				message('期号为【'.$data['period_number'].'】，商品id为【'.$data['goodsid'].'】的商品使用机器人创建失败。请重新创建。',referer(),'error');exit;
			}
		}

		if($op == 'delete_goods'){
			//删除使用机器人商品
			$id = $_GPC['id'];
			if(empty($id)){
				message('该商品不存在或者已经被删除',referer(),'error');
			}
			$result = pdo_delete('weliam_indiana_machineset',array('id'=>$id));
			if($result == 1){
				message('商品删除成功,删除时间【'.date('Y-m-d H:i:s',time()).'】',referer(),'success');
			}else{
				message('商品删除失败,删除时间【'.date('Y-m-d H:i:s',time()).'】',referer(),'error');
			}
		}
		
		if($op == 'update_goods'){
			//修改商品使用机器人状态
			$status = $_GPC['status'];
			$id = $_GPC['id'];
			switch($status){
				case 1 : $status=0; break;
				case 0 : $status=1; break;
			}
			$result = pdo_update('weliam_indiana_machineset',array('status'=>$status),array('id'=>$id));
			if($result == 1){
				die(json_encode(array('status'=>'true','msg'=>$status)));
			}else{
				die(json_encode(array('status'=>'false','msg'=>'修改失败')));
			}
		}
		/************************************************************************************************************************************/
		//
		//
		//启动和判断机器人进程
		//
		//
		/***********************************************************************************************************************************/
		if($op == 'open_machine'){
			//开启机器人进程
			$machine_status = $_GPC['machine_status'];
			/*if($machine_status == 'on'){
				//申请远程进程
				$tenlent['uniacid'] = $_W['uniacid'];
				$tenlent['period_number'] = 'tenlent'.time().$_W['uniacid'];;
				$tenlent['status'] = 1;								//0表示
				$tenlent['goodsid'] = -2;					//机器人远程进程双标记之一
				$tenlent['createtime'] = time();
				$check = pdo_fetch("select id from".tablename('weliam_indiana_machineset')." where uniacid=:uniacid and period_number like '%tenlent%'",array(':uniacid'=>$_W['uniacid']));
				if(empty($check)){
					$result_tenlent = pdo_insert('weliam_indiana_machineset',$tenlent);
				}
				if($result_tenlent == 1){
					$url_file = dirname(__FILE__);
					$url_file_b = $_SERVER["REQUEST_URI"];
					$url_o = explode('/web', $url_file_b);
					$all_a = explode('/addons', $url_file);
					$all_b = explode('/inc', $all_a[1]);
					$url = 'http://'.$_SERVER['SERVER_NAME'].$url_o[0].'/addons'.$all_b[0];			//路径组合
					
					$url_weliam = "http://weixin.012wz.cn/addons/weliam_manage/api.php";
					$http = ihttp_request($url_weliam, array('uniacid' => $_W['uniacid'],'url'=>$url,'type'=>'apply','module'=>'weliam_indiana'),array('Content-Type' => 'application/x-www-form-urlencoded'),5);
					$result_http = @json_decode($http['content'], true);
					if($result_http['status'] == 1){
						message('机器人远程进程开启成功!开启时间【'.date('Y-m-d H:i:s',time()).'】!',referer(),'success');
					}else{
						message('机器人远程进程开启失败!失败时间【'.date('Y-m-d H:i:s',time()).'】!请等待一段时间后重新启动！失败原因【'.$result_http['message'].'】',referer(),'error');
					}
				}else{
					message('机器人远程进程开启失败!失败时间【'.date('Y-m-d H:i:s',time()).'】!请等待一段时间后重新启动！',referer(),'error');
				}
			}else{*/
				//开启本地进程
				$sql = "select id,uniacid,period_number,status,createtime from".tablename('weliam_indiana_machineset')."where uniacid=:uniacid and period_number LIKE '%openmachine%'";
				$data = array(
					':uniacid'=>$_W['uniacid']
				);
				$result = pdo_fetch($sql,$data);
				if(empty($result)){
					$data_openmachine['uniacid'] = $_W['uniacid'];
					$data_openmachine['period_number'] = 'openmachine'.time().$_W['uniacid'];
					$data_openmachine['status'] = 0;
					$data_openmachine['goodsid'] = -1;					//机器人进程双标记之一
					$data_openmachine['createtime'] = time();
					$result_openmachine = pdo_insert('weliam_indiana_machineset',$data_openmachine);
					if($result_openmachine == 1){
						$http = ihttp_request($_W["siteroot"].'addons/weliam_indiana/core/api/robot.api.php', array('uniacid' => $_W['uniacid']),array('Content-Type' => 'application/x-www-form-urlencoded'),1);
						$this->WL_log('machine','开启$http',$http);
						message('机器人本地进程开启成功!开启时间【'.date('Y-m-d H:i:s',time()).'】!',referer(),'success');
					}else{
						message('机器人本地进程开启失败!失败时间【'.date('Y-m-d H:i:s',time()).'】!请等待一段时间后重新启动！',referer(),'error');
					}
				}else{
					$difference_time = time() - $result['createtime'];
					if($difference_time < 600){
						message('机器人本地进程开启失败,上次关闭时间为【'.date('Y-m-d H:i:s',$result['createtime']).'】，不能再关闭后10分钟内再次开启',referer(),'error');
					}
					pdo_update("weliam_indiana_machineset",array('goodsid'=>-1),array('id'=>$result['id']));
					$http = ihttp_request($_W["siteroot"].'addons/weliam_indiana/core/api/robot.api.php', array('uniacid' => $_W['uniacid']),array('Content-Type' => 'application/x-www-form-urlencoded'),1);
					$this->WL_log('machine','重启$http',$http);
					message('机器人本地进程开启成功!开启时间【'.date('Y-m-d H:i:s',time()).'】!',referer(),'success');
				}
			//}
		}

		if($op == 'close_machine'){
			//关闭机器人进程
			$sql_tenlent = "select id,uniacid,period_number,status from".tablename('weliam_indiana_machineset')."where uniacid=:uniacid and period_number LIKE '%tenlent%'";
			$data_tenlent = array(
				':uniacid'=>$_W['uniacid']
			);
			$result_tenlent = pdo_fetch($sql_tenlent,$data_tenlent);
			/*if(!empty($result_tenlent)){
				//远程进程关闭
				$url_file = dirname(__FILE__);
				$all_a = explode('/addons', $url_file);
				$all_b = explode('/inc', $all_a[1]);
				$url = 'http://'.$_SERVER['SERVER_NAME'].'/addons'.$all_b[0];			//路径组合
				
				$url_weliam = "http://weixin.012wz.cn/addons/weliam_manage/api.php";
				$http = ihttp_request($url_weliam, array('uniacid' => $_W['uniacid'],'url'=>$url_weliam,'type'=>'delete_apply','module'=>'weliam_indiana'),array('Content-Type' => 'application/x-www-form-urlencoded'),5);
				$result_http = @json_decode($http['content'], true);
				if($result_http['status'] == 1){
					$result_delete = pdo_delete("weliam_indiana_machineset",array('uniacid'=>$_W['uniacid'],'goodsid'=>-2));
					if($result_delete == 1){
						message('机器人远程进程关闭成功!关闭时间【'.date('Y-m-d H:i:s',time()).'】!,关闭后10分钟内不能重启',referer(),'success');
					}else{
						message('机器人远程进程关闭失败!失败时间【'.date('Y-m-d H:i:s',time()).'】!,关闭后10分钟内不能重启',referer(),'error');
					}
				}else{
					message('机器人远程进程关闭失败!失败时间【'.date('Y-m-d H:i:s',time()).'】!,失败原因【'.$result_http['message'].'】',referer(),'error');
				}
			}*/
			
			$sql = "select id,uniacid,period_number,status from".tablename('weliam_indiana_machineset')."where uniacid=:uniacid and period_number LIKE '%openmachine%'";
			$data = array(
				':uniacid'=>$_W['uniacid']
			);
			$result = pdo_fetch($sql,$data);
			$result_close = pdo_update('weliam_indiana_machineset',array('status'=>0,'createtime'=>time(),'goodsid'=>0),array('id'=>$result['id']));
			if($result_close == 1){
				message('机器人进程关闭成功!关闭时间【'.date('Y-m-d H:i:s',time()).'】!,关闭后10分钟内不能重启',referer(),'success');
			}else{
				message('机器人进程关闭失败!失败时间【'.date('Y-m-d H:i:s',time()).'】!,关闭后10分钟内不能重启',referer(),'error');
			}
		}
		
	?>