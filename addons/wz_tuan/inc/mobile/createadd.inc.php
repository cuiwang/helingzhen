<?php
		global $_GPC, $_W;
		$this->getuserinfo();
        session_start();
		$goodsid = $_SESSION['goodsid'];
		$groupnum = $_SESSION['groupnum'];
        $tuan_id = $_SESSION['tuan_id'];
		
        $share_data = $this->module['config'];
    	$operation = $_GPC['op'];
        $id=$_GPC['id'];
        $weid = $_W['uniacid'];
        $openid = $_W['openid'];
    	if ($operation == 'display') {
            if($id){
                $addres = pdo_fetch("SELECT * FROM " . tablename('wz_tuan_address')."where id={$id}");
                if(!empty($goodsid)){
                    $addresschange = 1;
                } 
            }  		
        }elseif($operation == 'addaddress'){
            if(!empty($goodsid)){
                $con = 1;
            } 
        }elseif ($operation == 'post') {
	        if(!empty($id)){
	            $status = pdo_fetch("SELECT * FROM " . tablename('wz_tuan_address')."where id='{$id}'");
	            $data=array(
	                'openid' => $openid,
	                'uniacid'=>$weid,
	                'cname'=>$_GPC['lxr_val'],
	                'tel'=>$_GPC['mobile_val'],
	                'province'=>$_GPC['province_val'],
	                'city'=>$_GPC['city_val'],
	                'county'=>$_GPC['area_val'],
	                'detailed_address'=>$_GPC['address_val'],
	                'status'=>$status['status'],
	                'type'=>$_GPC['addresstype'],
	                'addtime'=>time()
	            );
	            if(pdo_update('wz_tuan_address',$data,array('id' => $id))){ 
	            	echo 1;
	            	exit;
	            }else{   
	                echo 0;
	                exit;
	            }
	        }else{
	            $data1=array(
		            'openid' => $openid,
		            'uniacid'=>$weid,
		            'cname'=>$_GPC['lxr_val'],
		            'tel'=>$_GPC['mobile_val'],
		            'province'=>$_GPC['province_val'],
		            'city'=>$_GPC['city_val'],
		            'county'=>$_GPC['area_val'],
		            'detailed_address'=>$_GPC['address_val'],
		            'status'=>'1',
		            'type'=>$_GPC['addresstype'],
		            'addtime'=>time()
	        	);
	        	$moren =  pdo_fetch("SELECT * FROM".tablename('wz_tuan_address')."where status=1 and openid='$openid'");
	        	pdo_update('wz_tuan_address',array('status' => 0),array('id' => $moren['id']));
	            if(pdo_insert('wz_tuan_address',$data1)){
	            	echo 1;
	            	exit;
	            }else{                      
	                echo 0;
	                exit;
	            }                 
	        }   
        }elseif($operation == 'deletes'){
        	if($id){
                if(pdo_delete('wz_tuan_address',array('id' => $id ))){
                    echo 1;
                    exit;
                }else{
                    echo 0;
                    exit;
                }        
            }else{
                echo 2;
                exit;
            }
        }elseif($operation == 'moren'){    
            if(!empty($id)){
                $moren =  pdo_fetch("SELECT * FROM".tablename('wz_tuan_address')."where status=1 and openid='$openid'");
                pdo_update('wz_tuan_address',array('status' => 0),array('id' => $moren['id']));
                if(pdo_update('wz_tuan_address',array('status' =>1),array('id' => $id))){
                    echo 1;
                    exit;
                }else{
                    echo 0;
                    exit;
                }
            }else{
                echo 2;
                exit; 
            }
        }
		/*限制省*/
		$limit = pdo_fetch("select enabled from".tablename("wz_tuan_arealimit")."where uniacid = '{$_W['uniacid']}'");
		if($limit['enabled']==1){
			include $this->template('createadd');
		}else{
			include $this->template('createaddtow');
		}
        
?>