<?php
/**
 * 多功能产品预约模块微站定义
 *
 * @author Chavin
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Chavin_productModuleSite extends WeModuleSite {


public function doMobileProduct() {
	global $_W,$_GPC;
		$productid=$_GPC['product'];
		if(isMobile()){
			header('Location:'.$this->createMobileUrl('mobile',array('product'=>$productid)));
			}else{
				header('Location:'.$this->createMobileUrl('pc',array('product'=>$productid)));
				}
		
}
public function doMobilePc() {
	global $_W,$_GPC;
	$productid=$_GPC['product'];
	$product = pdo_fetch("SELECT * FROM ".tablename('chavin_product')." WHERE uniacid = :uniacid and id = :productid ", array(':uniacid' => $_W['uniacid'],':productid'=>$productid));
	include $this->template('pc'); 
	}

public function doMobileMobile() {
global $_W,$_GPC;
		$productid=$_GPC['product'];
		$reply = pdo_fetch("SELECT * FROM ".tablename('chavin_product_reply')." WHERE uniacid = :uniacid and productid = :productid", array(':uniacid' => $_W['uniacid'],':productid'=>$productid));
		$detail = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_detail')." WHERE uniacid = :uniacid and productid = :productid order by orderlist desc", array(':uniacid' => $_W['uniacid'],':productid'=>$productid));
		
		
		$product = pdo_fetch("SELECT * FROM ".tablename('chavin_product')." WHERE uniacid = :uniacid and id = :productid ", array(':uniacid' => $_W['uniacid'],':productid'=>$productid));
		
		//表单数据
if($reply['isform']==1){

$list = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_diytype')." WHERE uniacid = :uniacid and productid = :productid order by orderl desc", array(':uniacid' => $_W['uniacid'],':productid'=>$productid));


$types = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_diytype')." WHERE uniacid = :uniacid and productid = :productid and type in (0,1,2)", array(':uniacid' => $_W['uniacid'],':productid'=>$productid));


$sends = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_sendopenid')." WHERE uniacid = :uniacid and productid = :productid ", array(':uniacid' => $_W['uniacid'],':productid'=>$productid));

				 

for($i=0;$i<count($types);$i++){
	$o[$types[$i]['id']] = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_sonoption')." WHERE uniacid = :uniacid and productid = :productid and typeid =".$types[$i]['id'], array(':uniacid' => $_W['uniacid'],'productid'=>$productid));
	}
		//表单END
		
}

		//虚拟购买记录
		if($reply['isbuy']==1){
		$buy = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_buy')." WHERE uniacid = :uniacid and productid = :productid order by orderlist desc limit 0,".$reply['buynum'], array(':uniacid' => $_W['uniacid'],':productid'=>$productid));
		}
		
	
		
		if($reply['isbutton']==1){
		$button = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_button')." WHERE uniacid = :uniacid and productid = :productid order by orderlist desc limit 0,4", array(':uniacid' => $_W['uniacid'],':productid'=>$productid));
		$CountButton=count($button);
		}
		
		if($reply['isviewcount']==1){
		$look = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_look')." WHERE uniacid = :uniacid and productid = :productid order by time desc limit 0,5", array(':uniacid' => $_W['uniacid'],':productid'=>$productid));
		$CountLook = pdo_fetchcolumn("SELECT count(id) FROM ".tablename('chavin_product_look')." WHERE uniacid = :uniacid and productid = :productid ", array(':uniacid' => $_W['uniacid'],':productid'=>$productid));
			
		}
		
		pdo_update('chavin_product', array('lookcount' => $product['lookcount']+=1), array('id' => $productid));
		
		
		
		$fans=mc_fansinfo($_W['openid']) ;
		
		//添加一条用户记录，并判断是否成功
			$user_data = array(
				'productid' => $productid,
				'openid' => $_W['openid'],
				'time' => time(),
				'nickname' => $fans['nickname'],
				'avatar' => $fans['tag']['avatar'],
				'uniacid'=>$_W['uniacid']
			);
		 pdo_insert('chavin_product_look', $user_data);
			
include $this->template('product'); 
	}
	

public function doMobileIndex() {
	global $_W,$_GPC;
$productid=$_GPC['product'];
load()->func('tpl');

$nav = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_nav')." WHERE uniacid = :uniacid and productid = :productid", array(':uniacid' => $_W['uniacid'],':productid'=>$productid));


$reply = pdo_fetch("SELECT * FROM ".tablename('chavin_product_reply')." WHERE uniacid = :uniacid and productid = :productid", array(':uniacid' => $_W['uniacid'],':productid'=>$productid));

$list = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_diytype')." WHERE uniacid = :uniacid and productid = :productid order by orderl desc", array(':uniacid' => $_W['uniacid'],':productid'=>$productid));


$types = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_diytype')." WHERE uniacid = :uniacid and productid = :productid and type in (0,1,2)", array(':uniacid' => $_W['uniacid'],':productid'=>$productid));


$sends = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_sendopenid')." WHERE uniacid = :uniacid and productid = :productid ", array(':uniacid' => $_W['uniacid'],':productid'=>$productid));


		if($reply['isbutton']==1){
		$button = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_button')." WHERE uniacid = :uniacid and productid = :productid order by orderlist desc limit 0,4", array(':uniacid' => $_W['uniacid'],':productid'=>$productid));
		$CountButton=count($button);
		}
		
				 


				 

for($i=0;$i<count($types);$i++){
	$o[$types[$i]['id']] = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_sonoption')." WHERE uniacid = :uniacid and productid = :productid and typeid =".$types[$i]['id'], array(':uniacid' => $_W['uniacid'],'productid'=>$productid));
	}
	$data='';
	$picdata='';

//print_r($list);
//exit();

	
			
			
if(!empty($_GPC['submit'])){
	
	if($reply['isfollow']==1){
		$member = pdo_fetch("SELECT * FROM ".tablename('mc_mapping_fans')." WHERE uniacid = :uniacid and openid = :openid and follow = 1", array(':uniacid' => $_W['uniacid'],':openid'=>$_W['openid']));
		
		
		if(empty($member)){
			message('亲，请先关注我们的公众号',$this-> createMobileUrl('index',array('product'=>$productid)));
			exit();
			}
		}
	
	
	if($reply['postnum']!=0){
		$postnum = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_data')." WHERE uniacid = :uniacid and openid = :openid and productid = :productid", array(':uniacid' => $_W['uniacid'],':openid'=>$_W['openid'],':productid'=>$productid));
		$postnum=count($postnum);
		
	
		
		if($postnum>=$reply['postnum']){
			message('亲，请提交的次数超过上限了',$this-> createMobileUrl('index',array('product'=>$productid)));
			exit();
			}
			
			
		}
		
	
	
	
	$senddata='';
	for($i=0;$i<count($list);$i++)
				{
			
			
			
				if($list[$i]['type']==9){
					
					
					//修改
					if($reply['issend']==1){
						if($list[$i]['issend']==1){
							
									$sheng=$_GPC['type'.$i.'1'];
									$shi=$_GPC['type'.$i.'2'];
									$qu=$_GPC['type'.$i.'3'];
									$address=$sheng.$shi.$qu;
									
							$senddata.=$list[$i]['name'].':'.$address." \n ";
							
							}
					
					 }
				 		
								$sheng=$_GPC['type'.$i.'1'];
								$shi=$_GPC['type'.$i.'2'];
								$qu=$_GPC['type'.$i.'3'];
								$address=$sheng.$shi.$qu;
								
								$data.=$address.'|';
								
								
	             }	
								
								
			
			
			if($list[$i]['type']!=5&&$list[$i]['type']!=1&&$list[$i]['type']!=9){
				//修改
				if($reply['issend']==1){
					if($list[$i]['issend']==1){
						
						$sd=$_GPC['type'.$i];
						$senddata.=$list[$i]['name'].':'.$sd." \n ";
						
						}
				
				 }	
				
						$datatype=$_GPC['type'.$i];
						$data.=$datatype.'|';
				}else if($list[$i]['type']==1){
						
							
							$sum='';	
								
					for($k=0;$k<count($_GPC['type'.$i]);$k++){
						
			
							$sum.=$_GPC['type'.$i][$k].',';
							
							}
						$sum=substr($sum,0,strlen($sum)-1);
						
						$data.= $sum.'|';
				//修改		
				if($reply['issend']==1){
					if($list[$i]['issend']==1){
						
						
						$senddata.=$list[$i]['name'].':'.$sum." \n ";
						
						}
						
					}
							
									
			}else 
				if($list[$i]['type']==5){
				
				$datatype=$_GPC['type'.$i];
				$picdata.=$datatype.'|';
				}
				}
		$picdata=substr($picdata,0,strlen($picdata)-1);
		$data=substr($data,0,strlen($data)-1);
		
		
		/*if($reply['issend']==1){
			$senddata=substr($senddata,0,strlen($senddata)-1);	
		}*/
		
		$product = pdo_fetch("SELECT * FROM ".tablename('chavin_product')." WHERE uniacid = :uniacid and id = :productid ", array(':uniacid' => $_W['uniacid'],':productid'=>$productid));
		
	
	pdo_update('chavin_product', array('datacount' => $product['datacount']+=1), array('id' => $productid));
	
	$fans=mc_fansinfo($_W['openid']);
		
			$arr = array(
						'uniacid'=> $_W['uniacid'],
						'data'=>$data,
						'pic'=>$picdata,
						'productid'=>$productid,
						'openid'=>$_W['openid'],
						'time'=>time()
					);
							$result = pdo_insert('chavin_product_data', $arr);
							
					
							
							if (!empty($result)) {
								
								//通知发送
								if($reply['issend']==1){
										
										if($reply['sendmethod']=='sms'){
										
											$target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";
			//短信
				
					
				
					
											$post_data = "account=".$reply['smsuser']."&password=".$reply['smspass']."&mobile=".$reply['smsnum']."&content=".rawurlencode($senddata);
											//密码可以使用明文密码或使用32位MD5加密
											$gets =  xml_to_array(Post($post_data, $target));
											if($gets['SubmitResult']['code']==2){
												//$_SESSION['mobile'] = $mobile;
												//$_SESSION['mobile_code'] = $mobile_code;
											}
											//echo $gets['SubmitResult']['msg'];

										
										}else 
										
										
										if($reply['sendmethod']=='temp'){
										
										
			//模版消息
										//循环发送
												for($i=0;$i<count($sends);$i++){
													
													sendCustomerFP($sends[$i]['openid'],$fans['nickname'],date("Y-m-d  H:i:s",time()),$senddata,$reply['sendid']);
													}
				
													
				
					
				

										
										}
										
										
									}
									
								
								
								
								
								
								message('提交成功,稍后将会有工作人员联系您<br>请保持电话通畅',$this-> createMobileUrl('index',array('product'=>$productid)),'success');
							}else{
								message('提交失败',$this-> createMobileUrl('index',array('product'=>$productid)),'error');
								}
						
						
			exit();	
		}
		
	
	
	
	

include $this->template('index');
	}
	
	
	
	public function doWebType() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		 if(!empty($_GPC['add'])){
			
			 $topid=$_GPC['topid'];
			 $name=$_GPC['name'];
			 
			
			 
			 
			 $user_data = array(
						'topid' => $topid,
						'uniacid' => $_W['uniacid'],
						'name'=>$name,
						
					);
				$result = pdo_insert('chavin_product_type', $user_data);
				if (!empty($result)) {
					
					message('添加分类成功',$this-> createWebUrl('type'),'success');
				}


			 exit();
			 }
			
			
			if(!empty($_GPC['delete'])){
			$id=$_GPC['id'];
			 
			 
			$result = pdo_delete('chavin_product_type', array('id' => $id));
					if (!empty($result)) {
										message('删除成功',$this-> createWebUrl('type'),'success');
									}else{
										message('删除失败',$this-> createWebUrl('type'),'error');
										}
										exit();
			}
			
			
			if(!empty($_GPC['submit'])){
			$id=$_GPC['id'];
			$topid=$_GPC['topid'];
			$name=$_GPC['name'];
			
			 $user_data = array(
						'topid' => $topid,
						'uniacid' => $_W['uniacid'],
						'name'=>$name,
						
					);
			
			$result = pdo_update('chavin_product_type', $user_data, array('id' => $id));
					if (!empty($result)) {
										message('更新成功',$this-> createWebUrl('type'),'success');
									}else{
										message('更新失败',$this-> createWebUrl('type'),'error');
										}
										exit();
			}
		
		
		
			$res = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_type').
				 "where `uniacid`=:uniacid and topid = 0", array(':uniacid'=>$_W['uniacid']));  
				  
				  for($i=0;$i<count($res);$i++){
					  	$son[$res[$i]['id']] = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_type').
							"where `uniacid`=:uniacid and topid = :topid", array(':uniacid'=>$_W['uniacid'],':topid'=>$res[$i]['id']));  
				 
					  }
					  
					  
		include $this->template('type');
	}
	
	public function doWebProduct() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		 if(!empty($_GPC['add'])){
			
			 $typeid=$_GPC['typeid'];
			 
			
			 if(empty($typeid)){
				 message('添加产品失败，请选择分类',$this-> createWebUrl('product',array('id'=>$_GPC['typeid'])),'info');
				 }
			 $name=$_GPC['name'];
			 
			
			 
			 
			 $user_data = array(
						'name' => $name,
						'uniacid' => $_W['uniacid'],
						'typeid'=>$typeid,
						'time'=>time(),
						
					);
				$result = pdo_insert('chavin_product', $user_data);
				if (!empty($result)) {
					
					message('添加产品成功',$this-> createWebUrl('product',array('id'=>$_GPC['typeid'])),'success');
				}


			 exit();
			 }
			
			
			if(!empty($_GPC['delete'])){
			$id=$_GPC['id'];
			 
			 
			$result = pdo_delete('chavin_product', array('id' => $id));
					if (!empty($result)) {
										message('删除成功',$this-> createWebUrl('product',array('id'=>$_GPC['typeid'])),'success');
									}else{
										message('删除失败',$this-> createWebUrl('product',array('id'=>$_GPC['typeid'])),'error');
										}
										exit();
			}
			
			
			if(!empty($_GPC['submit'])){
			$id=$_GPC['id'];
			$typeid=$_GPC['typeid'];
			$name=$_GPC['name'];
			
			 $user_data = array(
						'typeid' => $typeid,
						'uniacid' => $_W['uniacid'],
						'name'=>$name,
						
					);
			
			$result = pdo_update('chavin_product', $user_data, array('id' => $id));
					if (!empty($result)) {
										message('更新成功',$this-> createWebUrl('product',array('id'=>$_GPC['typeid'])),'success');
									}else{
										message('更新失败',$this-> createWebUrl('product',array('id'=>$_GPC['typeid'])),'error');
										}
										exit();
			}
		
		
			
		
						
		 $pindex = $_GPC['page'];
				$strat=$pindex*7-7;
	
				if($strat<0){
					$strat=0;
					}
	
				//$start=$pindex*5;
		

	if($_GPC['id']){
				
				$count = pdo_fetchcolumn("SELECT count(id) FROM ".tablename('chavin_product').
				 "where `uniacid`=:uniacid and typeid=:typeid order by id desc ", array(':uniacid'=>$_W['uniacid'],':typeid'=>$_GPC['id']));  
				}else{
					$count = pdo_fetchcolumn("SELECT count(id) FROM ".tablename('chavin_product').
				 "where `uniacid`=:uniacid order by id desc ", array(':uniacid'=>$_W['uniacid']));  
					} 
					
		
			
			if($_GPC['search']){
			
		
				if($_GPC['typeid']>-1){
					$res = pdo_fetchall("SELECT * FROM ".tablename('chavin_product').
					 " where `uniacid`=:uniacid and typeid=:typeid and name like '%".$_GPC['values']."%'", array(':uniacid'=>$_W['uniacid'],':typeid'=>$_GPC['typeid']));  
					}else{
						$res = pdo_fetchall("SELECT * FROM ".tablename('chavin_product').
					 " where `uniacid`=:uniacid  and name like '%".$_GPC['values']."%'", array(':uniacid'=>$_W['uniacid']));  
						} 

				
				}else{
					
						if($_GPC['id']){
							$res = pdo_fetchall("SELECT * FROM ".tablename('chavin_product').
							 " where `uniacid`=:uniacid and typeid=:typeid order by id desc limit ".$strat.",7", array(':uniacid'=>$_W['uniacid'],':typeid'=>$_GPC['id']));  
							}else{
								$res = pdo_fetchall("SELECT * FROM ".tablename('chavin_product').
							 " where `uniacid`=:uniacid order by id desc limit ".$strat.",7", array(':uniacid'=>$_W['uniacid']));  
								} 

					}
				
			
			


$pagination = pagination($count, $pindex, 5);    //  生成HTML分页导航条

				 
				 
		 	$type = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_type').
				 "where `uniacid`=:uniacid and topid = 0", array(':uniacid'=>$_W['uniacid']));  
				
				
				  
				  for($i=0;$i<count($type);$i++){
					  	$son[$type[$i]['id']] = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_type').
							"where `uniacid`=:uniacid and topid = :topid", array(':uniacid'=>$_W['uniacid'],':topid'=>$type[$i]['id']));  
				 
					  }
					   
				  
			
					  
					  
		include $this->template('product');
	}
	
	
		public function doWebDetail() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		 if(!empty($_GPC['add'])){
			
			 
			
			 
			 
			 $user_data = array(
						'img' => $_GPC['img'],
						'uniacid' => $_W['uniacid'],
						'orderlist'=>$_GPC['orderlist'],
						'productid'=>$_GPC['productid'],
						
					);
				$result = pdo_insert('chavin_product_detail', $user_data);
				if (!empty($result)) {
					
					message('添加产品详细图成功',$this-> createWebUrl('detail',array('id'=>$_GPC['productid'])),'success');
				}


			 exit();
			 }
			
			
			if(!empty($_GPC['delete'])){
			$id=$_GPC['id'];
			 
			 
			$result = pdo_delete('chavin_product_detail', array('id' => $id));
					if (!empty($result)) {
										message('删除成功',$this-> createWebUrl('detail',array('id'=>$_GPC['productid'])),'success');
									}else{
										message('删除失败',$this-> createWebUrl('detail',array('id'=>$_GPC['productid'])),'error');
										}
										exit();
			}
			
			
			if(!empty($_GPC['submit'])){
			$id=$_GPC['id'];
			
			
			
			 $user_data = array(
						'img' => $_GPC['img'],
						'uniacid' => $_W['uniacid'],
						'orderlist'=>$_GPC['orderlist'],
						
						
					);
			
			$result = pdo_update('chavin_product_detail', $user_data, array('id' => $id));
					if (!empty($result)) {
										message('更新成功',$this-> createWebUrl('detail',array('id'=>$_GPC['productid'])),'success');
									}else{
										message('更新失败',$this-> createWebUrl('detail',array('id'=>$_GPC['productid'])),'error');
										}
										exit();
			}
		
		
			
				
				
			 
			$res = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_detail').
				 "where `uniacid`=:uniacid and productid=:productid order by orderlist desc", array(':uniacid'=>$_W['uniacid'],':productid'=>$_GPC['id']));  
				 
				 
				 
				  
			
					  
					  
		include $this->template('detail');
	}
	
		public function doWebButton() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		 if(!empty($_GPC['add'])){
			
			 
			
			 
			 
			 $user_data = array(
						'name' => $_GPC['name'],
						'uniacid' => $_W['uniacid'],
						'orderlist'=>$_GPC['orderlist'],
						'url'=>$_GPC['url'],
						'productid'=>$_GPC['id'],
						'color'=>$_GPC['color'],
						'font'=>$_GPC['font']
					);
				$result = pdo_insert('chavin_product_button', $user_data);
				if (!empty($result)) {
					
					message('添加成功',$this-> createWebUrl('button',array('id'=>$_GPC['productid'])),'success');
				}


			 exit();
			 }
			
			
			if(!empty($_GPC['delete'])){
			$id=$_GPC['id'];
			 
			 
			$result = pdo_delete('chavin_product_button', array('id' => $id));
					if (!empty($result)) {
										message('删除成功',$this-> createWebUrl('button',array('id'=>$_GPC['productid'])),'success');
									}else{
										message('删除失败',$this-> createWebUrl('button',array('id'=>$_GPC['productid'])),'error');
										}
										exit();
			}
			
			
			if(!empty($_GPC['submit'])){
			$id=$_GPC['id'];
			
			
			
			 $user_data = array(
						
						'name' => $_GPC['name'],
						'uniacid' => $_W['uniacid'],
						'orderlist'=>$_GPC['orderlist'],
						'url'=>$_GPC['url'],
						'productid'=>$_GPC['productid'],
						'color'=>$_GPC['color'],
						'font'=>$_GPC['font']
					);
			
			$result = pdo_update('chavin_product_button', $user_data, array('id' => $id));
					if (!empty($result)) {
										message('更新成功',$this-> createWebUrl('button',array('id'=>$_GPC['productid'])),'success');
									}else{
										message('更新失败',$this-> createWebUrl('button',array('id'=>$_GPC['productid'])),'error');
										}
										exit();
			}
		
		
			
				
				
			 
			$res = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_button').
				 "where `uniacid`=:uniacid and productid=:productid order by orderlist", array(':uniacid'=>$_W['uniacid'],':productid'=>$_GPC['id']));  
				 
				 
				 
				  
			
					  
					  
		include $this->template('button');
	}
	
	
	public function doWebManagement() {
		//这个操作被定义用来呈现 管理中心导航菜单
			global $_W,$_GPC;

			//$_SESSION['productid']= $_GPC['productid'];
		
			include $this->template('management'); 
	}
	
	
	public function doWebSet() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;

		 if(!empty($_GPC['submit'])){
			
			 $type=$_GPC['type'];
			 $name=$_GPC['name'];
			 
			
			 
			 
			 $user_data = array(
						'type' => $type,
						'uniacid' => $_W['uniacid'],
						'name'=>$name,
						'productid'=>$_GPC['productid']
					);
				$result = pdo_insert('chavin_product_diytype', $user_data);
				if (!empty($result)) {
					
					message('添加分类成功',$this-> createWebUrl('set',array('productid'=>$_GPC['productid'])),'success');
				}


			 exit();
			 }
			 		 include $this->template('set');
	}
	
	
	
		public function doWebSetlist() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		
			if(!empty($_GPC['delete'])){
			$id=$_GPC['id'];
			
			$result = pdo_delete('chavin_product_diytype', array('id' => $id));
					if (!empty($result)) {
										message('删除成功',$this-> createWebUrl('setlist',array('productid'=>$_GPC['productid'])),'success');
									}else{
										message('删除失败',$this-> createWebUrl('setlist',array('productid'=>$_GPC['productid'])),'error');
										}
										exit();
			}
			
			
			if(!empty($_GPC['submit'])){
				$id=$_GPC['id'];
				$productid=$_GPC['productid'];
				
				$user_data = array(
					'name' => $_GPC['name'],
					'orderl' => $_GPC['order'],
					'ischeck' => $_GPC['ischeck'],
					'issend' => $_GPC['issend'],
				);
			
			$result = pdo_update('chavin_product_diytype', $user_data, array('id' => $id));
					if (!empty($result)) {
										message('更新成功',$this-> createWebUrl('setlist',array('productid'=>$_GPC['productid'])),'success');
									}else{
										message('更新失败',$this-> createWebUrl('setlist',array('productid'=>$_GPC['productid'])),'error');
										}
										exit();
			}
		
		
		$res = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_diytype').
				 "where `uniacid`=:uniacid and productid = ".$_GPC['productid'], array(':uniacid'=>$_W['uniacid']));  
			 
		include $this->template('setlist');
	}
	
	
	
	
	
	public function doWebOptionlist() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
	
			$productid=$_GPC['productid'];
			
			if(!empty($_GPC['delete'])){
			$id=$_GPC['id'];
			
			$result = pdo_delete('chavin_product_sonoption', array('id' => $id));
					if (!empty($result)) {
										message('删除成功',$this-> createWebUrl('optionlist',array('productid'=>$_GPC['productid'],'typeid'=>$_GPC['typeid'])),'success');
									}else{
										message('删除失败',$this-> createWebUrl('optionlist',array('productid'=>$_GPC['productid'],'typeid'=>$_GPC['typeid'])),'error');
										}
										exit();
			}
			
			
			if(!empty($_GPC['submit'])){
			$id=$_GPC['id'];
			$productid=$_GPC['productid'];
			
			$user_data = array(
				'name' => $_GPC['name'],
				'image'=>$_GPC['image'],
			);
			
			$result = pdo_update('chavin_product_sonoption', $user_data, array('id' => $id));
					if (!empty($result)) {
										message('更新成功',$this-> createWebUrl('optionlist',array('productid'=>$_GPC['productid'],'typeid'=>$_GPC['typeid'])),'success');
									}else{
										message('更新成功',$this-> createWebUrl('optionlist',array('productid'=>$_GPC['productid'],'typeid'=>$_GPC['typeid'])),'error');
										}
										exit();
			}
		
		
		$res = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_sonoption').
				 "where `uniacid`=:uniacid and productid = :productid and typeid =".$_GPC['typeid'], array(':uniacid'=>$_W['uniacid'],':productid'=>$productid));  
			 
		include $this->template('optionlist');
	}
	
	
			public function doWebSonoption() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		
		$productid=$_GPC['productid'];
			
			
			if(!empty($_GPC['submit'])){
			
			$productid=$_GPC['productid'];
			
			$user_data = array(
				'name' => $_GPC['name'],
				'typeid' => $_GPC['typeid'],
				'productid' => $productid,
				'uniacid'=>$_W['uniacid'],
				'image'=>$_GPC['image'],
			);
		
			
		$result = pdo_insert('chavin_product_sonoption', $user_data);
		
					if (!empty($result)) {
										message('添加成功',$this-> createWebUrl('sonoption',array('productid'=>$_GPC['productid'])),'success');
									}else{
										message('添加成功',$this-> createWebUrl('sonoption',array('productid'=>$_GPC['productid'])),'error');
										}
										exit();
			}
		
		
		$res = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_diytype').
				 "where `uniacid`=:uniacid and productid = :productid and type in (0,1,2)", array(':uniacid'=>$_W['uniacid'],':productid'=>$_GPC['productid']));  
			 
		include $this->template('sonoption');
	}
	
	
	public function doWebDatalist() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		$productid=$_GPC['productid'];
		$type = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_diytype').
				 "where `uniacid`=:uniacid and productid = :productid and type != 5 order by orderl desc", array(':uniacid'=>$_W['uniacid'],':productid'=>$_GPC['productid']));  
			
			
	

	$dataall = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_data').
				 "where `uniacid`=:uniacid and productid = :productid ", array(':uniacid'=>$_W['uniacid'],':productid'=>$_GPC['productid'])); 
				 

			
		if(!empty($_GPC['search'])){
			
		$starttime= strtotime($_GPC['starttime']);
		$endtime= strtotime($_GPC['endtime']);
		
			$data = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_data').
				 "where `uniacid`=:uniacid and productid = :productid and data like '%".$_GPC['values']."%' and time > ".$starttime." and time < ".$endtime." order by time desc", array(':uniacid'=>$_W['uniacid'],':productid'=>$_GPC['productid'])); 
			
			}else{	 
		
		
				
	        $pindex = $_GPC['page'];
            $strat=$pindex*5-5;

			if($strat<0){
				$strat=0;
				}

            //$start=$pindex*5;
        $data = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_data').' where `uniacid`=:uniacid and productid = :productid  order by time desc  limit '.$strat.',5', array(':uniacid'=>$_W['uniacid'],':productid'=>$_GPC['productid']));   
        $count = pdo_fetchcolumn("SELECT COUNT(id) FROM ".tablename('chavin_product_data').' where `uniacid`=:uniacid and productid = :productid ', array(':uniacid'=>$_W['uniacid'],':productid'=>$_GPC['productid']));
        $pagination = pagination($count, $pindex, 5);    //  生成HTML分页导航条
		
		
			
	
			}
		
				 
		$pic=pdo_fetchall("SELECT * FROM ".tablename('chavin_product_diytype').
				 "where `uniacid`=:uniacid and productid = :productid and type = 5 order by orderl desc", array(':uniacid'=>$_W['uniacid'],':productid'=>$_GPC['productid']));
		
		
		
		
	  for($i=0;$i<count($data);$i++){
		 	
		 
		 		
		  		 $text = $data[$i]['data'];   

				 $datalist[$i] = explode("|",  $text);   
			}	 
			
			//全部数据
			for($i=0;$i<count($dataall);$i++){
		 	
		 
		 		
		  		 $text = $dataall[$i]['data'];   

				 $dataalllist[$i] = explode("|",  $text); 
				 
				  $z[$i][1]=date("Y-m-d  H:i:s",$dataall[$i]['time']);
			
				 
				 $dataalllist[$i]=array_merge_recursive($z[$i], $dataalllist[$i]);
				 
				   
			}	
			
			
			
			 for($i=0;$i<count($data);$i++){
				 $text = $data[$i]['pic'];   

				 $piclist[$i] = explode("|",  $text);   
			}	
			
			
		
		
			if(!empty($_GPC['delete'])){
			$id=$_GPC['id'];
			
			$result = pdo_delete('chavin_product_data', array('id' => $id));
					if (!empty($result)) {
										message('删除成功',$this-> createWebUrl('datalist',array('productid'=>$_GPC['productid'],'typeid'=>$_GPC['typeid'])),'success');
									}else{
										message('删除失败',$this-> createWebUrl('datalist',array('productid'=>$_GPC['productid'],'typeid'=>$_GPC['typeid'])),'error');
										}
										exit();
			}
			
			
			
			
			
			if(!empty($_GPC['down'])){
				
				
				//引入PHPExcel库文件（路径根据自己情况）
include '../addons/chavin_product/phpexcel/Classes/PHPExcel.php';

//创建对象
$excel = new PHPExcel();
//Excel表格式,这里简略写了8列
$letter = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ');
//表头数组
//$tableheader = array('学号','姓名','性别','年龄','班级');

for($i=0;$i<count($type);$i++){
	$tableheader[$i+1]=$type[$i]['name'];
	
	}


$tableheader[0]='提交时间';


//填充表头信息
for($i = 0;$i < count($tableheader);$i++) {
$excel->getActiveSheet()->setCellValue("$letter[$i]1","$tableheader[$i]");
}




$data = $dataalllist;


//填充表格信息
for ($i = 2;$i <= count($data) + 1;$i++) {
$j = 0;
foreach ($data[$i - 2] as $key=>$value) {
$excel->getActiveSheet()->setCellValue("$letter[$j]$i","$value");
$j++;
}
}


//创建Excel输入对象
$write = new PHPExcel_Writer_Excel5($excel);
header("Pragma: public");
header("Expires: 0");
header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
header("Content-Type:application/force-download");
header("Content-Type:application/vnd.ms-execl");
header("Content-Type:application/octet-stream");
header("Content-Type:application/download");;
header('Content-Disposition:attachment;filename="testdata.xls"');
header("Content-Transfer-Encoding:binary");
$write->save('php://output');

				}
	

		
				 include $this->template('datalist');
		}
		
		
		public function doWebReply() {
				global $_W,$_GPC;
		
		$row = pdo_fetch("SELECT * FROM ".tablename('chavin_product_reply')." WHERE uniacid = :uniacid and productid = :productid", array(':uniacid' => $_W['uniacid'],':productid' => $_GPC['productid']));
		
		
		
	if($_GPC['op']=='add'){
		
	
		if(!empty($row)){
		$user_data = array(
			'productid' => $_GPC['productid'],
			'uniacid' => $_W['uniacid'],
			'lat' => $_GPC['location']['lat'],
			'lng' => $_GPC['location']['lng'],
			'title' => $_GPC['title'],
			'address' => $_GPC['address'],
			'phone' => $_GPC['phone'],
			'issend' => $_GPC['issend'],
			'sendmethod' => $_GPC['sendmethod'],
			'smsnum' => $_GPC['smsnum'],
			'smsuser' => $_GPC['smsuser'],
			'smspass' => $_GPC['smspass'],
			'sendopenid' => $_GPC['sendopenid'],
			'sendid' => $_GPC['sendid'],
			'isfollow' => $_GPC['isfollow'],
			'postnum' => $_GPC['postnum'],
			'shareimg' => $_GPC['shareimg'],
			'sharetitle' => $_GPC['sharetitle'],
			'sharedescription' => $_GPC['sharedescription'],
			'isviewcount'=>$_GPC['isviewcount'],
			'isbutton'=>$_GPC['isbutton'],
			'isbuy'=>$_GPC['isbuy'],
			'buyspeed'=>$_GPC['buyspeed'],
			'buynum'=>$_GPC['buynum'],
			'isform'=>$_GPC['isform']
			);
			$result = pdo_update('chavin_product_reply', $user_data, array('id' => $row['id']));
				if (!empty($result)) {
					message('更新设置成功','','success');
				}
			
			}else{
		
		$user_data = array(
			'productid' => $_GPC['productid'],
			'uniacid' => $_W['uniacid'],
			'lat' => $_GPC['location']['lat'],
			'lng' => $_GPC['location']['lng'],
			'title' => $_GPC['title'],
			'address' => $_GPC['address'],
			'phone' => $_GPC['phone'],
			'issend' => $_GPC['issend'],
			'sendmethod' => $_GPC['sendmethod'],
			'smsnum' => $_GPC['smsnum'],
			'smsuser' => $_GPC['smsuser'],
			'smspass' => $_GPC['smspass'],
			'sendopenid' => $_GPC['sendopenid'],
			'sendid' => $_GPC['sendid'],
			'isfollow' => $_GPC['isfollow'],
			'postnum' => $_GPC['postnum'],
			'shareimg' => $_GPC['shareimg'],
			'sharetitle' => $_GPC['sharetitle'],
			'sharedescription' => $_GPC['sharedescription'],
			'isviewcount'=>$_GPC['isviewcount'],
			'isbutton'=>$_GPC['isbutton'],
			'isbuy'=>$_GPC['isbuy'],
			'buyspeed'=>$_GPC['buyspeed'],
			'buynum'=>$_GPC['buynum'],
			'isform'=>$_GPC['isform']
			);
			$result = pdo_insert('chavin_product_reply', $user_data);
			if (!empty($result)) {
				$uid = pdo_insertid();
				message('更新设置成功','','success');
			}
			
			}
		}
		

		

		
		
		include $this->template('reply');
		}
		
		
		
		public function doWebInto() {
				global $_W,$_GPC;
		
		$row = pdo_fetch("SELECT * FROM ".tablename('chavin_product_reply')." WHERE uniacid = :uniacid and productid = :productid", array(':uniacid' => $_W['uniacid'],':productid' => $_GPC['productid']));
		
		
	
		
		
		include $this->template('into');
		}
		
		
		
		public function doWebSendopenid() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		 if(!empty($_GPC['add'])){
			
			 
			
			 
			 
			 $user_data = array(
						'time'=>time(),
						'uniacid' => $_W['uniacid'],
						'openid'=>$_GPC['openid'],
						
						'productid'=>$_GPC['productid'],
						'name'=>$_GPC['name'],
						'mobile'=>$_GPC['mobile']
						
					);
				$result = pdo_insert('chavin_product_sendopenid', $user_data);
				if (!empty($result)) {
					
					message('添加通知管理员成功',$this-> createWebUrl('sendopenid',array('productid'=>$_GPC['productid'])),'success');
				}


			 exit();
			 }
			
			
			if(!empty($_GPC['delete'])){
			$id=$_GPC['id'];
			 
			 
			$result = pdo_delete('chavin_product_sendopenid', array('id' => $id));
					if (!empty($result)) {
										message('删除成功',$this-> createWebUrl('sendopenid',array('productid'=>$_GPC['productid'])),'success');
									}else{
										message('删除失败',$this-> createWebUrl('sendopenid',array('productid'=>$_GPC['productid'])),'error');
										}
										exit();
			}
			
			
			if(!empty($_GPC['submit'])){
			$id=$_GPC['id'];
			
			
			
			 $user_data = array(
						
						'time'=>time(),
						'uniacid' => $_W['uniacid'],
						'openid'=>$_GPC['openid'],
						'name'=>$_GPC['name'],
						'mobile'=>$_GPC['mobile'],
						'productid'=>$_GPC['productid']
						
					);
			
			$result = pdo_update('chavin_product_sendopenid', $user_data, array('id' => $id));
					if (!empty($result)) {
										message('更新成功',$this-> createWebUrl('sendopenid',array('productid'=>$_GPC['productid'])),'success');
									}else{
										message('更新失败',$this-> createWebUrl('sendopenid',array('productid'=>$_GPC['productid'])),'error');
										}
										exit();
			}
		
		
			
				
				
			 
			$res = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_sendopenid').
				 "where `uniacid`=:uniacid and productid=:productid order by id", array(':uniacid'=>$_W['uniacid'],':productid'=>$_GPC['productid']));  
				 
				 
				 
				  
			
					  
					  
		include $this->template('sendopenid');
	}
	
	
	
		public function doWebLook() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		
			
			if(!empty($_GPC['delete'])){
			$id=$_GPC['id'];
			 
			 
			$result = pdo_delete('chavin_product_look', array('id' => $id));
					if (!empty($result)) {
										message('删除成功',$this-> createWebUrl('look',array('productid'=>$_GPC['productid'])),'success');
									}else{
										message('删除失败',$this-> createWebUrl('look',array('productid'=>$_GPC['productid'])),'error');
										}
										exit();
			}
			
		 $pindex = $_GPC['page'];
            $strat=$pindex*10-10;

            if($strat<0){
                $strat=0;
                }


     


$count = pdo_fetchcolumn("SELECT count(id) FROM ".tablename('chavin_product_look').
				 "where `uniacid`=:uniacid and productid=:productid order by id desc", array(':uniacid'=>$_W['uniacid'],':productid'=>$_GPC['productid']));  
				 
	
			 
			$res = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_look').
				 "where `uniacid`=:uniacid and productid=:productid order by id desc limit ".$strat.",10", array(':uniacid'=>$_W['uniacid'],':productid'=>$_GPC['productid']));  
				
				
			
			   $pagination = pagination($count, $pindex, 10);    //  生成HTML分页导航条	 
				 
				  
			
					  
					  
		include $this->template('look');
	}
	
	
	
	
	
	
		public function doWebLookcount() {
		//这个操作被定义用来呈现 管理中心导航菜单
			global $_W,$_GPC;

		
			$res = pdo_fetchall("SELECT * FROM ".tablename('chavin_product').
				 "where `uniacid`=:uniacid order by lookcount desc", array(':uniacid'=>$_W['uniacid']));  
			
			$count = pdo_fetchcolumn("SELECT count(id) FROM ".tablename('chavin_product_look').
				 "where `uniacid`=:uniacid", array(':uniacid'=>$_W['uniacid']));  
		
				 	 
		
			include $this->template('lookcount'); 
	}


	public function doWebbookcount() {
		//这个操作被定义用来呈现 管理中心导航菜单
			global $_W,$_GPC;

		
			$res = pdo_fetchall("SELECT * FROM ".tablename('chavin_product').
				 "where `uniacid`=:uniacid order by datacount desc", array(':uniacid'=>$_W['uniacid']));  
			
			$count = pdo_fetchcolumn("SELECT count(id) FROM ".tablename('chavin_product_data').
				 "where `uniacid`=:uniacid", array(':uniacid'=>$_W['uniacid']));  
				 	 
		
			include $this->template('bookcount'); 
	}
		
	
		public function doWebCount() {
		//这个操作被定义用来呈现 管理中心导航菜单
			global $_W,$_GPC;
			include $this->template('count'); 
	}
	
	
	public function doWebNavphoto(){
						
						 global $_W,$_GPC;
						
						 
						 
				if(!empty($_GPC['submit'])){
				 $image=$_GPC['image'];
			
				 $src=$_GPC['src'];
			
				 
				 	$arr = array(
						'uniacid'=> $_W['uniacid'],
						'image'=>$image,
						'src'=>$src,
						'productid'=>$_GPC['productid'],
					);
							$result = pdo_insert('chavin_product_nav', $arr);
							if (!empty($result)) {
								message('轮播图片上传成功',$this-> createWebUrl('navphoto',array('productid'=>$_GPC['productid'])),'success');
							}else{
								message('轮播图片上传失败，请返回重试',$this-> createWebUrl('navphoto',array('productid'=>$_GPC['productid'])),'error');
								}
				 
				 
				 
				 
				
				 
				 exit();
				 }
						 include $this->template('navphoto'); 
						}
						
	



public function doWebCnavphoto(){
						
						 global $_W,$_GPC;
						 $productid=$_GPC['productid'];
						 	if(!empty($_GPC['id'])and!empty($_GPC['delete']) ){
					
								$id=$_GPC['id'];
									$result = pdo_delete('chavin_product_nav', array('id' => $id));
								if (!empty($result)) {
									message('删除轮播图片成功',$this-> createWebUrl('cnavphoto',array('productid'=>$_GPC['productid'],'uniacid'=>$_W['uniacid'])),'sucess');
								}else{
												message('删除轮播图片失败',$this-> createWebUrl('cnavphoto',array('productid'=>$_GPC['productid'],'uniacid'=>$_W['uniacid'])),'error');
												}
								
								exit();
								}
						 
						 
						 
						 
						 
					if(!empty($_GPC['id'])and!empty($_GPC['change']) ){
					$src=$_GPC['src'];
					$id=$_GPC['id'];
					$image=$_GPC['image'];
				
					
								$user_data = array(
									'src' => $src,
									'image' => $image,
								);
								$result = pdo_update('chavin_product_nav', $user_data, array('id' =>$id));
								if (!empty($result)) {
									message('修改轮播图片成功',$this-> createWebUrl('cnavphoto',array('productid'=>$_GPC['productid'])),'sucess');
								}else{
									
									message('修改轮播图片失败',$this-> createWebUrl('cnavphoto',array('productid'=>$_GPC['productid'])),'error');
									}
					exit();
					}
						 
						 $listnav = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_nav').
				 		 "where `uniacid`=:uniacid and productid = ".$productid, array(':uniacid'=>$_W['uniacid']));
						 include $this->template('cnavphoto'); 
						 	}
				
				
	public function doWebBuy() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		 if(!empty($_GPC['add'])){
			
			 
			
			 
			 
			 $user_data = array(
						'data' => $_GPC['data'],
						'uniacid' => $_W['uniacid'],
						'orderlist'=>$_GPC['orderlist'],
						
						'productid'=>$_GPC['id'],
				
					);
				$result = pdo_insert('chavin_product_buy', $user_data);
				if (!empty($result)) {
					
					message('添加成功',$this-> createWebUrl('buy',array('id'=>$_GPC['productid'])),'success');
				}


			 exit();
			 }
			
			
			if(!empty($_GPC['delete'])){
			$id=$_GPC['id'];
			 
			 
			$result = pdo_delete('chavin_product_buy', array('id' => $id));
					if (!empty($result)) {
										message('删除成功',$this-> createWebUrl('buy',array('id'=>$_GPC['productid'])),'success');
									}else{
										message('删除失败',$this-> createWebUrl('buy',array('id'=>$_GPC['productid'])),'error');
										}
										exit();
			}
			
			
			if(!empty($_GPC['submit'])){
			$id=$_GPC['id'];
			
			
			
			 $user_data = array(
						
						'data' => $_GPC['data'],
						'uniacid' => $_W['uniacid'],
						'orderlist'=>$_GPC['orderlist'],
						
						'productid'=>$_GPC['productid'],
					);
			
			$result = pdo_update('chavin_product_buy', $user_data, array('id' => $id));
					if (!empty($result)) {
										message('更新成功',$this-> createWebUrl('buy',array('id'=>$_GPC['productid'])),'success');
									}else{
										message('更新失败',$this-> createWebUrl('buy',array('id'=>$_GPC['productid'])),'error');
										}
										exit();
			}
		
		
			
				
				
			 
			$res = pdo_fetchall("SELECT * FROM ".tablename('chavin_product_buy').
				 "where `uniacid`=:uniacid and productid=:productid order by orderlist", array(':uniacid'=>$_W['uniacid'],':productid'=>$_GPC['id']));  
				 
				 
				 
				  
			
					  
					  
		include $this->template('buy');
	}			
	
}


function gettoptype($id){
		global $_W,$_GPC;
		$res = pdo_fetch("SELECT * FROM ".tablename('chavin_product_type').
				 "where `uniacid`=:uniacid and id = :id", array(':uniacid'=>$_W['uniacid'],':id'=>$id));  
				 return $res;
	}
function getproduct($id){
		global $_W,$_GPC;
		$res = pdo_fetch("SELECT * FROM ".tablename('chavin_product').
				 "where `uniacid`=:uniacid and id = :id", array(':uniacid'=>$_W['uniacid'],':id'=>$id));  
				 return $res;
	}
function getlookcount($productid){
		global $_W,$_GPC;
		$res = pdo_fetchcolumn("SELECT count(id) FROM ".tablename('chavin_product_look').
				 "where `uniacid`=:uniacid and productid = :productid", array(':uniacid'=>$_W['uniacid'],':productid'=>$productid));  
				 return $res;
	}
function getbookcount($productid){
		global $_W,$_GPC;
		$res = pdo_fetchcolumn("SELECT count(id) FROM ".tablename('chavin_product_data').
				 "where `uniacid`=:uniacid and productid = :productid", array(':uniacid'=>$_W['uniacid'],':productid'=>$productid));  
				 return $res;
	}
	
/**
* 检查是否是以手机浏览器进入(IN_MOBILE)
*/
function isMobile() {
    $mobile = array();
    static $mobilebrowser_list ='Mobile|iPhone|Android|WAP|NetFront|JAVA|OperasMini|UCWEB|WindowssCE|Symbian|Series|webOS|SonyEricsson|Sony|BlackBerry|Cellphone|dopod|Nokia|samsung|PalmSource|Xphone|Xda|Smartphone|PIEPlus|MEIZU|MIDP|CLDC';
    //note 获取手机浏览器
    if(preg_match("/$mobilebrowser_list/i", $_SERVER['HTTP_USER_AGENT'], $mobile)) {
        return true;
    }else{
        if(preg_match('/(mozilla|chrome|safari|opera|m3gate|winwap|openwave)/i', $_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }else{
            if($_GET['mobile'] === 'yes') {
                return true;
            }else{
                return false;
            }
        }
    }
}




function Post($curlPost,$url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
}
function xml_to_array($xml){
    $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
    if(preg_match_all($reg, $xml, $matches)){
        $count = count($matches[0]);
        for($i = 0; $i < $count; $i++){
        $subxml= $matches[2][$i];
        $key = $matches[1][$i];
            if(preg_match( $reg, $subxml )){
                $arr[$key] = xml_to_array( $subxml );
            }else{
                $arr[$key] = $subxml;
            }
        }
    }
    return $arr;
}








function sendCustomerFP($openid,$keyword1,$keyword2,$senddata,$template_id) {
    global $_W;
    /*$template_id = pdo_fetchcolumn("select CustomerFP from ".tablename('hc_deluxejjr_templatenews')." where uniacid = ".$_W['uniacid']);*/
    

    if (!empty($template_id)) {
         $datas = array(
            'first' => array('value' => '消息提醒', 'color' => '#73a68d'),
            'keyword1' => array('value' => $keyword1, 'color' => '#73a68d'),
            'keyword2' => array('value' => $keyword2, 'color' => '#73a68d'),
             'remark' => array('value' => $senddata, 'color' => '#73a68d')
             );
        $data = json_encode($datas); //发送的消息模板数据
    }

    if (!empty($template_id)){
        $accountid = pdo_fetch("select * from ".tablename('account_wechats')." where uniacid = ".$_W['uniacid']);
        $appid = $accountid['key'];
        $appSecret = $accountid['secret'];



        if(empty($url)){
            $url = '';
        } else {
            $url = $url;
        }
        $sendopenid = $openid;
        $topcolor = "#FF0000";


        tempmsg($template_id, $url, $data, $topcolor, $sendopenid, $appid, $appSecret);

    }
}



function tempmsg($template_id, $url, $data, $topcolor, $sendopenid, $appid, $appSecret){
    load()->func('communication');

   
   
		
		// 如何获取指定公众号的 access_token
$access_token = WeAccount::token();


        $tokens = $access_token;

        if(empty($tokens)){
            return;
        }
        $postarr = '{"touser":"'.$sendopenid.'","template_id":"'.$template_id.'","url":"'.$url.'","topcolor":"'.$topcolor.'","data":'.$data.'}';
        $res = ihttp_post('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$tokens,$postarr);
    
}


function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);
    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
}



