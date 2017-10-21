<?
       global $_GPC, $_W;
        $groupnum=$_GPC['groupnum'];//团购人数  
        $g_id = intval($_GPC['g_id']);
        $tuan_id = intval($_GPC['tuan_id']);
       
        $all = array(
            'g_id' =>$g_id,
            'groupnum' =>$groupnum
            );
    	$operation = $_GPC['op'];
        $id=$_GPC['id'];
        $weid = $_W['uniacid'];
        $openid = $_W['openid'];
        message('con='.$g_id);exit;
    	if ($operation == 'display') {
            if($id){
                $addres = pdo_fetch("SELECT * FROM " . tablename('tg_address')."where id={$id}");
                if(!empty($all)){
                    $addresschange = 1;
                } 
            }  		
        }elseif($operation == 'conf'){
            if(!empty($all)){
                   $con = 1;
                } 
        }elseif ($operation == 'post') { 
                if(!empty($id)){
                    $status = pdo_fetch("SELECT * FROM " . tablename('tg_address')."where id={$id}");
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
                        'addtime'=>time()
                    );
                    if(pdo_update('tg_address',$data,array('id' => $id))){ 
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
                    'addtime'=>time()
                );
                $moren =  pdo_fetch("SELECT * FROM".tablename('tg_address')."where status=1 and openid='$openid'");
                pdo_update('tg_address',array('status' => 0),array('id' => $moren['id']));
                     if(pdo_insert('tg_address',$data1)){
                   
                    echo 1;
                    exit;
                    }else{                      
                        echo 0;
                        exit;
                    }                 
                }
               
        }elseif($operation == 'deletes'){

        if($id){
                    if(pdo_delete('tg_address',array('id' => $id )))
                    {
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
            if(!empty($id))
            {
                $moren =  pdo_fetch("SELECT * FROM".tablename('tg_address')."where status=1 and openid='$openid'");
                pdo_update('tg_address',array('status' => 0),array('id' => $moren['id']));
                    if(pdo_update('tg_address',array('status' =>1),array('id' => $id))){
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
        
        include $this->template('createadd');
    
 ?>