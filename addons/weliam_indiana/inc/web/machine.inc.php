<?php
		global $_W,$_GPC;
		$id = intval($_GPC['goodsid']);
		$periods = intval($_GPC['periods']);
		$title = $_GPC['title'];
		$url='../addons/weliam_indiana/static/head_imgs'; //放图片的文件夹路径名称
		$filename = "../addons/weliam_indiana/static/nickname.text";$filename2 = "../addons/weliam_indiana/static/ip.text";
		$goods = pdo_fetch("select id,goodsid,period_number,periods,shengyu_codes,createtime from".tablename('weliam_indiana_period')."where uniacid={$_W['uniacid']}  and goodsid={$id} and periods={$periods}");
		$goodslist = m('goods')->getGoods($goods['goodsid']);
		$period = pdo_fetchall("select createtime from".tablename('weliam_indiana_consumerecord')."where uniacid={$_W['uniacid']} and period_number='{$goods['period_number']}' order by id desc");
		$machinesnum = pdo_fetchcolumn("select count(mid) from".tablename('weliam_indiana_member')."where uniacid = '{$_W['uniacid']}' and type = '-1' and ip != ''");
		$machines = pdo_fetch("select * from".tablename('weliam_indiana_machineset')."where period_number = '{$goods['period_number']}'");
		if (checksubmit('sure')) {
			//添加机器人
			$limit_num = $_GPC['limit_num'];
			$init = $period[0]['createtime'];
			$now = time();
			$head_imgs_array = m('order')->get_head_img($url, $limit_num);
			$nickname_arr = m('order')->get_nickname($filename,$limit_num);
			$randtime_arr = m('order')->get_randtime($init,$now,$limit_num);
			$ip_arr = m('order')->get_ip($filename2,$limit_num);
			for($i=0;$i<$limit_num;$i++){
				$api = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip={$ip_arr[$i]}"; 
				$json = @file_get_contents($api);//调用新浪IP地址库 
				$arr = json_decode($json,true);
				pdo_insert('weliam_indiana_member',array('nickname'=>$nickname_arr[$i]['nickname'],'avatar'=>$head_imgs_array[$i],'type'=>-1,'uniacid'=>$_W['uniacid'],'ip'=>$ip_arr[$i]));
				$openid = pdo_insertid();
				pdo_update('weliam_indiana_member',array('openid'=>'machine'.$openid),array('mid'=>$openid));
			}
					
			message('机器人添加成功！', referer(), 'success');
		}

		$op = $_GPC['op'];
		if($op == 'used'){
			//使用现有机器人
			$limit_num = $_GPC['limit_num'];
			$code_num = $_GPC['code_num'];
			if($limit_num > $machinesnum || $code_num > $goods['shengyu_codes'] || $limit_num > $code_num){
				message('机器人数量或者分码数量大于现有数量', referer(), 'error');
			}else{
				$machines = m('order')->get_Machines($limit_num);
				$num_arr = m('order')->randnum($code_num,$limit_num);
				$max_time = pdo_fetchcolumn("select max(createtime) from".tablename('weliam_indiana_consumerecord')."where uniacid = '{$_W['uniacid']}' and period_number = '{$goods['period_number']}'");
				if(empty($max_time)){
					$max_time = $goods['createtime'];
				}
				$machines_time = m('order')->get_randtime($max_time,time(),$limit_num);
				$i = 0;
				foreach($machines as $key=>$value){
					$api = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip={$value['ip']}";
					$json = @file_get_contents($api);//调用新浪IP地址库 
					$arr = json_decode($json,true);
					pdo_insert('weliam_indiana_cart',array('num'=>$num_arr[$i],'uniacid'=>$_W['uniacid'],'period_number'=>$goods['period_number'],'openid'=>$value['openid'],'title'=>$title,'ip'=>$value['ip'],'ipaddress'=>$arr['province'].$arr['city']));
					m('codes')->code($value['openid'],'machine',$_W['uniacid'],$machines_time[$i]);
					$i++;
				}
				message('使用机器人成功！', referer(), 'success');
			}
			
		}
		
		if($op == 'timeused'){
			//限定时间使用机器人
			$data['uniacid'] = $_W['uniacid'];
			$data['period_number'] = $goods['period_number'];
			$data['max_num'] = !empty($_GPC['max_num'])?$_GPC['max_num']:-1; // -1表示默认不限制购买数
			$data['createtime'] = time();
			$data['start_time'] = strtotime($_GPC['time']['start']);
			$data['end_time'] = strtotime($_GPC['time']['end']);
			$data['status'] = 1;        										//	1表示正常执行
			$data['timebucket'] = $_GPC['timebucket'];
			$data['machine_num'] = $_GPC['machine_num'];						//单次购买夺宝码个数
			$data['is_followed'] = $_GPC['is_followed'];
			if((strtotime($_GPC['time']['end'])+28800)%86400 < (strtotime($_GPC['time']['start'])+28800)%86400 || $_GPC['machine_num'] < 1 || $_GPC['timebucket'] < 1){
				message('机器人参数设置不正确！', referer(), 'error');
				exit;
			}else{
				$machine = pdo_fetch("select id from".tablename('weliam_indiana_machineset')."where uniacid = '{$_W['uniacid']}' and period_number = '{$goods['period_number']}'");
				if(empty($machine)){
					pdo_insert("weliam_indiana_machineset",$data);
					m('machine')->marchine_cir($goods['period_number'] , $_GPC['timebucket'] , $_GPC['machine_num']);
				}else{
					pdo_update("weliam_indiana_machineset",$data,array('period_number'=>$goods['period_number']));
					m('machine')->marchine_cir($goods['period_number'] , $_GPC['timebucket'] , $_GPC['machine_num']);
				}
			}
		}
		
		if($op == 'dele_machine'){
			//删除定时机器人
			$period_number = $_GPC['period_number'];
			pdo_update("weliam_indiana_machineset",array('status'=>'0','is_followed'=>0),array('period_number'=>$period_number));
			message('机器人暂停成功！', referer(), 'success');
		}
		include $this->template('goods_machine');
	?>