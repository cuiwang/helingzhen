<?php
global $_GPC, $_W;
$judge=$_GPC['judge'];
$rid = intval($_GPC['rid']);
$weid = $_W['uniacid']; 
if(!empty($_SERVER['HTTP_APPNAME'])){
	@$mem=memcache_init();
}else if(class_exists("Memcache")){
	@$mem=new Memcache;
  $conn = @$mem->connect('localhost','11211');  
}
if($conn){
				$memsql = realpath("..").'SELECT * FROM  '.tablename('weixin_shake_toshake').' ';
        $key = substr(md5($memsql), 10, 8); 
        //从memcache服务器获取数据
        $data = $mem->get($key);
			  //判断memcache是否有数据
				if(empty($data)){				
					 $sql1="SELECT * FROM  ".tablename('weixin_shake_toshake')." WHERE weid=:weid AND rid=:rid ORDER BY point DESC";
					 $q  = pdo_fetchall($sql1,array(':weid'=>$weid,':rid'=>$rid));
					 if(is_array($q) && !empty($q)){
							foreach($q as $key=>$row){
										 $data[$key] = $row;
										 $mem->set(realpath("..").'shakeu'.$row['openid'],$key, MEMCACHE_COMPRESSED, 3600);
								 }
							 }	
							 //向memcache服务器存储数据,还要设置失效时间（单位为秒）
							 $mem->set($key, $data, MEMCACHE_COMPRESSED, 3600);
							 $data = $mem->get($key);
				}          
					 if(!empty($data)){
								 usort($data,"compare"); 
					 }                         
					  $start=realpath("..")."UPDATE  ".tablename('weixin_wall_reply')." ";
						$key2 = substr(md5($start), 10, 8);
					if($judge == 1){
						 echo json_encode($data);	
					}else if($judge == 2){//参与的总人数
							 $sql = "SELECT count(*) FROM  ".tablename('weixin_shake_toshake')." WHERE weid=:weid AND rid=:rid";
               $num  = pdo_fetchcolumn($sql,array(':weid'=>$weid,':rid'=>$rid));
			         echo $num;	
					}else if($judge == 3){
							 $startvalue = 2;
							 $mem->set($key2, $startvalue, MEMCACHE_COMPRESSED, 3600);
					    pdo_update('weixin_wall_reply',array('isopen'=>2),array('weid'=>$weid,'rid'=>$rid));
					}else if($judge == 4){
							$startvalue = 1;
							$mem->set($key2, $startvalue, MEMCACHE_COMPRESSED, 3600);
							pdo_update('weixin_wall_reply',array('isopen'=>1),array('weid'=>$weid,'rid'=>$rid));
					}elseif($judge == 5){
					    pdo_update('weixin_shake_toshake',array('point'=>0),array('weid'=>$weid,'rid'=>$rid));
					    pdo_update('weixin_wall_reply',array('isopen'=>1),array('weid'=>$weid,'rid'=>$rid));
				  }        
					$mem->close(); //关闭memcache连接
}else{
     $sql = "SELECT * FROM  ".tablename('weixin_shake_toshake')." WHERE weid=:weid AND rid=:rid ORDER BY point DESC";
     $arr  = pdo_fetchall($sql,array(':weid'=>$weid,':rid'=>$rid));
     if($judge == 1){   
        echo json_encode($arr);
		 }elseif($judge == 2){
			  $sql2 = "SELECT count(id) FROM  ".tablename('weixin_shake_toshake')." WHERE weid=:weid AND rid=:rid";
        $num  = pdo_fetchcolumn($sql2,array(':weid'=>$weid,':rid'=>$rid));
			  echo $num;
     }elseif($judge == 3){
         pdo_update('weixin_wall_reply',array('isopen'=>2),array('weid'=>$weid,'rid'=>$rid));
		}elseif($judge == 4){
				pdo_update('weixin_wall_reply',array('isopen'=>1),array('weid'=>$weid,'rid'=>$rid));					
		}elseif($judge == 5){
			pdo_update('weixin_shake_toshake',array('point'=>0),array('weid'=>$weid,'rid'=>$rid));
			pdo_update('weixin_wall_reply',array('isopen'=>1),array('weid'=>$weid,'rid'=>$rid));
		}
}  
function compare($x,$y){ 
        	if($x['point'] == $y['point']) 
        		return 0; 
        	elseif($x['point'] > $y['point']) 
        		return -1; 
        	else 
        		return 1; 
 } 