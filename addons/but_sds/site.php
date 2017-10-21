<?php
/**
 * 浇灌圣诞树模块微站定义
 *
 */
defined('IN_IA') or exit('Access Denied');

class But_sdsModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		//这个操作被定义用来呈现 功能封面
		global $_W,$_GPC;
	
			$sharelink = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$sql = 'select * from '.tablename('sds_member').'where `openid` = :openid and `uniacid`=:uniacid';
			$params = array(
			
					':openid' => $_W['openid'],
					':uniacid' => $_W['uniacid']
			
						);
				if($member = pdo_fetch($sql,$params)){
					
					
						$url = $this->createMobileUrl('share',array('id'=>$_W['openid']));
						Header("Location:".$url);
						exit();
					
					}
			
		$sql = 'select * from '.tablename('sds_info').'where `uniacid`=:uniacid';
			$params = array(
					
					':uniacid' => $_W['uniacid']
			
						);
		
		$info = pdo_fetch($sql,$params);
		
		$seed_sql = 'select * from '.tablename('sds_seeds');
		
		$seed_data = pdo_fetchall($seed_sql);
		
		for($i=0;$i<count($seed_data);$i++){
			
			
			switch($seed_data[$i]['id'])
			{
			case '1':
				
				$red['name'] = $seed_data[$i]['fruit'];
				$red['water'] = $seed_data[$i]['water'];
				$red['dabian'] = $seed_data[$i]['yy'];
				break;
			case '2':
				
				$green['name'] = $seed_data[$i]['fruit'];
				$green['water'] = $seed_data[$i]['water'];
				$green['dabian'] = $seed_data[$i]['yy'];
				break;
			case '3':
				$orange['name'] = $seed_data[$i]['fruit'];
				$orange['water'] = $seed_data[$i]['water'];
				$orange['dabian'] = $seed_data[$i]['yy'];
				break;
			case '4':
				$lemon['name'] = $seed_data[$i]['fruit'];
				$lemon['water'] = $seed_data[$i]['water'];
				$lemon['dabian'] = $seed_data[$i]['yy'];
				break;
				
				}
			
			}
		
		
		$adimg = tomedia($info['adimg']);
		$introduce = htmlspecialchars_decode($info['introduce']);
		
		include $this->template('index');
		
	}
	
	public function doWebFoods(){
		global $_W,$_GPC;
		
		if(checksubmit()){
			
			$red = array(
			
			'fruit' => $_GPC['redname'],
			'water' => $_GPC['redwater'],
			'yy' => $_GPC['redyy']
				
			);
			
			$green = array(
			
			'fruit' => $_GPC['greenname'],
			'water' => $_GPC['greenwater'],
			'yy' => $_GPC['greenyy']
				
			);
			
			$orange = array(
			
			'fruit' => $_GPC['orangename'],
			'water' => $_GPC['orangewater'],
			'yy' => $_GPC['orangeyy']
				
			);
			
			$lemon = array(
			
			'fruit' => $_GPC['lemonname'],
			'water' => $_GPC['lemonwater'],
			'yy' => $_GPC['lemonyy']
				
			);
			
			$upred = pdo_update('sds_seeds',$red ,array('id' => 1));
			$upgreen = pdo_update('sds_seeds',$green ,array('id' => 2));
			$uporange = pdo_update('sds_seeds',$orange ,array('id' => 3));
			$uplemon = pdo_update('sds_seeds',$lemon ,array('id' => 4));
			
			
				
				 message('修改成功！', $this->createWebUrl('foods'));
				
			
			
			
			}
	
	$seed_sql = 'select * from '.tablename('sds_seeds');
		
		$seed_data = pdo_fetchall($seed_sql);
		
		for($i=0;$i<count($seed_data);$i++){
			
			
			switch($seed_data[$i]['id'])
			{
			case '1':
			$red['id'] = $seed_data[$i]['id'];
				$red['name'] = $seed_data[$i]['fruit'];
				$red['water'] = $seed_data[$i]['water'];
				$red['yy'] = $seed_data[$i]['yy'];
				break;
			case '2':
			$green['id'] = $seed_data[$i]['id'];
				$green['name'] = $seed_data[$i]['fruit'];
				$green['water'] = $seed_data[$i]['water'];
				$green['yy'] = $seed_data[$i]['yy'];
				break;
			case '3':
			$orange['id'] = $seed_data[$i]['id'];
				$orange['name'] = $seed_data[$i]['fruit'];
				$orange['water'] = $seed_data[$i]['water'];
				$orange['yy'] = $seed_data[$i]['yy'];
				break;
			case '4':
			$lemon['id'] = $seed_data[$i]['id'];
				$lemon['name'] = $seed_data[$i]['fruit'];
				$lemon['water'] = $seed_data[$i]['water'];
				$lemon['yy'] = $seed_data[$i]['yy'];
				break;
				
				}
			
			}
		
	
	
	include $this->template('foods');
		
		
		}

	public function doMobileLogin(){
		global $_W,$_GPC;
		
	//checksubmit()
		if($_GPC['user'] && $_GPC['mobile']){
			
			//将post过来的信息存入数据库
			
			$sql = 'select * from '.tablename('sds_info').' where uniacid = :uniacid';
			$params = array(
			
				':uniacid' => $_W['uniacid']
			);
			
			$info = pdo_fetch($sql,$params);
			$adimg = $info['adimg'];
			$sql = 'select * from '.tablename('sds_member').' where uniacid = :uniacid';
			$params = array(
			
				':uniacid' => $_W['uniacid']
			);
			
			$count = count(pdo_fetchall($sql,$params));
			
			if($info['count'] < $count+1){
				 $url = $this->createMobileUrl('index');
				 $alert = '不好意思，种子已被领取完毕，请期待下一期<br />
确定';
				 include $this->template('alert');
				
				}
			
			
			$data = array(
			
				'openid' => $_W['openid'],
				'name' => $_GPC['user'],
				'mobile' => $_GPC['mobile'],
				'kinds' => $_GPC['kinds'],
				'address' => $_GPC['address'],
				'uniacid' => $_W['uniacid']
			
					);
					
					
					
			$add = pdo_insert('sds_member',$data);
			
			 if(add){
				 $url = $this->createMobileUrl('share',array('id'=>$_W['openid']));
				 $alert = '恭喜您，成功领取圣诞果种子一枚！<br />
确定';
				 include $this->template('alert');
				 }
			
			}
		
		$a_sql = 'select * from '.tablename('sds_address').' where uniacid = :uniacid';
			$a_params = array(
			
				':uniacid' => $_W['uniacid']
			);
		
		$address = pdo_fetchall($a_sql,$a_params);
		
		include $this->template('login');
		
		}

	public function doMobileShare(){
		global $_W,$_GPC;
		$sql = 'select * from '.tablename('sds_info').'where `uniacid`=:uniacid';
			$params = array(
					
					':uniacid' => $_W['uniacid']
			
						);
		
		$info = pdo_fetch($sql,$params);
		$adimg = tomedia($info['adimg']);
	
		$sharelink = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'&id='.$_GPC['id'];
		$urllink = 'http://'.$_SERVER['HTTP_HOST'];
		
		$sql = 'select * from '.tablename('sds_member').'where uniacid = :uniacid and openid = :id';
		$params = array(
				':id' => $_GPC['id'],
				':uniacid' => $_W['uniacid']
				);
		$data = pdo_fetch($sql,$params);
		
		
		$sqlsg = 'select * from '.tablename('sds_seeds').' where id = :kinds';
		$paramsg = array(
		
				':kinds' => $data['kinds']
		
				);
		$sg = pdo_fetch($sqlsg,$paramsg);
		
	
		
		switch ($data['status']){
			
			case '0': //未完成
		if($_W['openid'] != $_GPC['id']){
			
			//如果是自己的订单
			
					$btn = '<a href="'.$this->createMobileUrl('water',array('id' => $_GPC['id'])).'"><div class="content">
							帮助浇水
							</div></a>
							
							<a href="'.$this->createMobileUrl('dabian',array('id' => $_GPC['id'])).'"><div onclick="sf()" class="content" style=" left:31%;">
							浇灌营养液
							</div></a>
							  <a href="'.$this->createMobileUrl('index').'"> <div class="content" style="width:61%; bottom:10px; height:22px; line-height:22px; border-radius:4px;">
       我也要领取种子
        </div></a>';
		
		}else{
			
				$btn = '<div class="content" onclick="mcover()" style=" width:61%;">
							展示给好友看看
							</div>';
			
			}
		break;
		
			case '1'://已完成,未兑换
			if($_W['openid'] != $_GPC['id']){
			
			//如果不是自己的订单
					$btn = '<div class="content"style=" width:61%;">
							'.$data['name'].'的圣诞果已成熟
							</div>
							  <a href="'.$this->createMobileUrl('index').'"> <div class="content" style="width:61%; bottom:10px; height:22px; line-height:22px; border-radius:4px;">
       我也要领取种子
        </div></a>
							
							';
		
		}else{
			
				$btn = '<div class="content" style="width:90%; margin:0 5%; font-size:0.8em; border:none;">
							恭喜您，圣诞果已经成熟，去比优特超市兑换吧！
							</div>
							<div class="content" style=" width:60%; bottom:10px; border:none;">
							<form action="'.$this->createMobileUrl('duih').'" method="post">
							<input type="hidden" name="id" value="'.$_W['openid'].'">
							<input type="password" name="pwd"><br />
							
                            <input type="submit" name="submit" value="提交兑换" style="width:30%; height:20px;">
							<input type="hidden" name="token" value="'.$_W['token'].'">
							</form>
                            </div>
							';
			
			}
			
			break;
			
			case '2': //已兑换
			if($_W['openid'] != $_GPC['id']){
			
			//如果是自己的订单
					$btn = '<div class="content"style=" width:61%;">
							'.$data['name'].'的圣诞果已成熟
							</div>
							  <a href="'.$this->createMobileUrl('index').'"> <div class="content" style="width:61%; bottom:10px; height:22px; line-height:22px; border-radius:4px;">
       我也要领取种子
        </div></a>
							
							';
		
		}else{
			
				$btn = '<div class="content" style="width:90%; margin:0 5%; font-size:0.8em; border:none;">
							恭喜您，圣诞果已经成功兑换！
							</div>
							';
			
			}
			
			
			break;
		
		
		}
		
			
		switch($data['kinds']){
			
			case '1':
			$img = 'red1.gif';
			
			break;
			
			case '2':
			$img = 'green1.gif';
			break;
			
			case '3':
			$img = 'orange1.gif';
			break;
			
			case '4':
			$img = 'lemon1.gif';
			break;
			
			
			
			}
		
		
		
		
		
		$cz =  ($data['water']+ $data['yingy'])*0.5+10;
		
		include $this->template('share');
		}
	public function doWebGuize() {
		//这个操作被定义用来呈现 规则列表
	}

	public function doWebAddress() {
		//这个操作被定义用来呈现 规则列表
		global $_W,$_GPC;
		if(checksubmit()){
			
			$t = array(
			
					'shop' => $_GPC['shop'],
					'address' => $_GPC['address'],
					'uniacid' => $_W['uniacid']
			
			);
			$data = pdo_insert('sds_address',$t);
			
			if($data){
				
				
				 message('增加成功！', $this->createWebUrl('address'));
				 exit();
				
				}else{
					
					 message('增加失败！', $this->createWebUrl('address'));
					exit();
					}
			
			
			
		}
		
		
		
			$sql = 'select * from '.tablename('sds_address').' where `uniacid` = :uniacid';
	$params = array(
	
			':uniacid' => $_W['uniacid']
	
			);
		$data = pdo_fetchall($sql,$params);	
		
		
		
				for($i=0; $i<count($data);$i++)//挨个提出地址
				
				
				
				{
					$member_sql = 'select * from '.tablename('sds_member').' where `uniacid` = :uniacid and `address` = :address';
					$m_params = array(
					
							':uniacid' => $_W['uniacid'],
							':address' => $data[$i]['shop']
							
								);
					$list[$i]['id'] = $data[$i]['shop'];
					$list[$i]['shop'] = $data[$i]['address'];
				$list[$i]['red'] = 0;
				$list[$i]['green'] = 0;
				$list[$i]['orange'] = 0;
				$list[$i]['lemon'] = 0;
					
					$m_data = pdo_fetchall($member_sql,$m_params);	
					
					  		for($j=0; $j<count($m_data); $j++){		//按地址取出所有该地址的登陆信息
								
								if($m_data[$j]['status'] == 1){		//将该地址的已成功的挑选出来
								switch($m_data[$j]['kinds']){		//将已成功的水果进行分类储存到tt里
									
									case '1':
										$list[$i]['red'] += 1;
									break;
									
									case '2':
										$list[$i]['green'] += 1;
									break;
									
									case '3':
										$list[$i]['orange'] += 1;
									break;
									
									case '4':
										$list[$i]['lemon'] += 1;
									break;
									
									
									
									}
								}
								
								}
										//保存到当前地址下的水果数量里
					
					
					}
		
		
		
		include $this->template('address');
		
		
	}


public function doMobileDuih(){
		global $_W,$_GPC;
		
		if(checksubmit()){
			
			$sql = 'select * from '.tablename('sds_info').' where `uniacid` = :uniacid';
	$params = array(
	
			':uniacid' => $_W['uniacid']
	
			);
			
	$data = pdo_fetch($sql,$params);
			if($_GPC['pwd'] ==$data ['password']){
			
			
			
			$updata = pdo_update('sds_member',array('status' => 2),array('uniacid' => $_W['uniacid'],'openid' => $_W['openid']));
		
			$url = $this->createMobileUrl('share',array('id'=>$_W['openid']));
			
				if($updata){
					
					
					$alert = '恭喜您，兑换成功！<br />
确定';
					
					
					
					}else{
						
				
					$alert = '兑换失败！<br />
确定';
				
						
						}}else{
							
						$alert = '密码错误！<br />确定';	
						
							}
			include $this->template('alert');
			}
		
	}
	
public function doMobileWater(){
	global $_W,$_GPC;
	
	$sql = 'select * from '.tablename('sds_member').' where `openid` = :openid and `uniacid` = :uniacid';
	$params = array(
	
			':openid' => $_GPC['id'],
			':uniacid' => $_W['uniacid']
	
			);
	$data = pdo_fetch($sql,$params);
	
	$helper = json_decode($data['helper']);
	if(count($helper)>0){
	for($i=0; $i<count($helper); $i++){
		
		if($_W['openid'] == $helper[$i]){
			
			$url = $this->createMobileUrl('share',array('id'=>$_GPC['id']));
			$alert = '对不起，您已经帮助过了！<br />
确定';
			include $this->template('alert');
			exit();
			}
		
		
		}
	}
	$helper[] = $_W['openid'];
	$helper = json_encode($helper);
	$water = $data['water']+1; 
	$up = array(
				'water' => $water,
				'helper' => $helper
				);
	$sgsql = 'select * from '.tablename('sds_seeds').' where `id` = :kinds';
	$sgparams = array(
			':kinds' => $data['kinds']
			);
	$sgdata = pdo_fetch($sgsql,$sgparams);
	
	$alert = '谢谢您，辛勤的园丁！<br />
确定';
	if($water > $sgdata['water']){
		
		$up['water'] = $sgdata['water'];
		
		$alert = '不能再浇水啦，再浇就淹死啦！<br />
确定';
	$url = $this->createMobileUrl('share',array('id'=>$_GPC['id']));
			
			include $this->template('alert');
			exit();
		}
	if($water == $sgdata['water'] && $data['yingy'] == $sgdata['yy']){
		
		
		$up['status'] = 1;
		
		}
	
	$updata = pdo_update('sds_member', $up,array('uniacid' => $_W['uniacid'],'openid' => $_GPC['id']));
	
	
	if($updata){
		
		$url = $this->createMobileUrl('share',array('id'=>$_GPC['id']));
			
			include $this->template('alert');
		
		}
	
	}
	
public function doMobileDabian(){
	global $_W,$_GPC;
	
	$sql = 'select * from '.tablename('sds_member').' where `openid` = :openid and `uniacid` = :uniacid';
	$params = array(
	
			':openid' => $_GPC['id'],
			':uniacid' => $_W['uniacid']
	
			);
	$data = pdo_fetch($sql,$params);
	
	$helper = json_decode($data['helper']);
	if(count($helper)>0){
	for($i=0; $i<count($helper); $i++){
		
		if($_W['openid'] == $helper[$i]){
			
			$url = $this->createMobileUrl('share',array('id'=>$_GPC['id']));
			$alert = '对不起，您已经帮助过了！<br />
确定';
			include $this->template('alert');
			exit();
			
			}
		
		
		}
	}
	$helper[] = $_W['openid'];
	$helper = json_encode($helper);
	$dabian = $data['yingy']+1; 
	$up = array(
				'yingy' => $dabian,
				'helper' => $helper
				);
	//===============================
	$sgsql = 'select * from '.tablename('sds_seeds').' where `id` = :kinds';
	$sgparams = array(
			':kinds' => $data['kinds']
			);
	$sgdata = pdo_fetch($sgsql,$sgparams);
	
	$alert = '谢谢您，辛勤的园丁！<br />
确定';
	if($dabian > $sgdata['yy']){
		
		$up['yingy'] = $sgdata['yy'];
		$alert = '不能再施肥啦，再浇就烧死啦！<br />
确定';
$url = $this->createMobileUrl('share',array('id'=>$_GPC['id']));
			
			include $this->template('alert');
			exit();
		}
	
	
	
	
	//================================
	if($data['water'] == $sgdata['water'] && $dabian == $sgdata['yy']){
		
		
		$up['status'] = 1;
		
		}
	$updata = pdo_update('sds_member', $up,array('uniacid' => $_W['uniacid'],'openid' => $_GPC['id']));
	
	
	if($updata){
		
		$url = $this->createMobileUrl('share',array('id'=>$_GPC['id']));
			
			include $this->template('alert');
		
		}
	
	}
	
	public function doWebSetting() {
		//这个操作被定义用来呈现 规则列表
		global $_W,$_GPC;
		load()->func('tpl');
		$options = array(
					'width'  => 300, // 上传后图片最大宽度
    				'global'=>false 
   					);
					
		$thumb = array(
					'width'  => 100, // 上传后图片最大宽度
					'height' => 100,
    				'global'=>false
   					);			
		
		if(checksubmit()){
			
			$sql = 'select * from '.tablename('sds_info').'where `uniacid`=:uniacid';
			$params = array(
					
					':uniacid' => $_W['uniacid']
			
						);
			if(pdo_fetch($sql,$params)){
				
				
				$data = pdo_update('sds_info',  array(
				
													'title' => $_GPC['title'],
													'count' => $_GPC['count'],
													'password' => $_GPC['password'],
													'copyright' => $_GPC['copyright'],
													'adimg' =>$_GPC['adimg'],
													'sharetitle' => $_GPC['sharetitle'],
													'shareicon' => $_GPC['shareicon'],
													'sharecontent' => $_GPC['sharecontent'],
													'introduce' => $_GPC['introduce']
													
													
														),array('uniacid' => $_W['uniacid']));
				
					message('修改成功！', $this->createWebUrl('setting'));
				
				}else{
					
					$data = pdo_insert('sds_info', array(
								
								'title' => $_GPC['title'],
								'count' => $_GPC['count'],
								'password' => $_GPC['password'],
								'copyright' => $_GPC['copyright'],
								'adimg' =>$_GPC['adimg'],
								'sharetitle' => $_GPC['sharetitle'],
								'shareicon' => $_GPC['shareicon'],
								'sharecontent' => $_GPC['sharecontent'],
								'introduce' => $_GPC['introduce'],
								'uniacid' => $_W['uniacid']
								
								));
					message('修改成功！', $this->createWebUrl('setting'));
					
					}
			
			
			}
			
			
			$sql = 'select * from '.tablename('sds_info').'where `uniacid`=:uniacid';
			$params = array(
					
					':uniacid' => $_W['uniacid']
			
						);
			$data = pdo_fetch($sql,$params);
			
		
		include $this->template('Setting');
	}
	
	public function doWebMember() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		
			
			
			$sql = 'select * from '.tablename('sds_member').' where `uniacid`=:uniacid';
		
			$params = array(
					
					':uniacid' => $_W['uniacid']
			
						);
				
			$data = pdo_fetchall($sql,$params);
			
		
		include $this->template('member');
	}

public function doWebDelmember(){
	global $_W,$_GPC;
	
	 $data = pdo_delete('sds_member', array('uniacid' => $_W['uniacid'], 'openid' => $_GPC['id']));
	 
	 if($data){
		 
		 message('删除成功！', $this->createWebUrl('member'));
		 }else{
			 
			 message('删除失败！', $this->createWebUrl('member'));
			 
			 }
	
	
	}
	
	
	public function doWebDelshop(){
	global $_W,$_GPC;
	
	
	 $data = pdo_delete('sds_address', array('uniacid' => $_W['uniacid'], 'shop' => $_GPC['shop']));
	 
	 if($data){
		 
		 message('删除成功！', $this->createWebUrl('address'));
		 }else{
			 
			 message('删除失败！', $this->createWebUrl('address'));
			 
			 }
	
	
	}
	
	
	
}



