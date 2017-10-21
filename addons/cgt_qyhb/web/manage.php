<?php
/*
 * Created on 2015-6-20
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */



		$table='qyhb_user';
		$op=empty($_GPC['op'])?'display':$_GPC['op'];
		if($_GPC['op']=='post'){
  			$field=array('user_id','user_name','user_image','createtime','ipaddr','num');  
            $id=intval($_GPC['id']);  
            if($_W['ispost']){  
                //保存数据  
                foreach($field as $v){  
                    $insert[$v]=$_GPC[$v];  
                }  
                if($id>0){  
                    $temp=pdo_update($table, $insert, array('id' => $id));  
                }else{  
					$insert['createtime']=time();  
                    $temp=pdo_insert($table,$insert);  
                }  
                if($temp===false){                
                    message('抱歉，数据操作失败！','', 'error');                
                }else{  
                    message('更新数据成功！', $this->createWeburl('manage'), 'success');  
                }  
            }  
            if($id>0){  
                $item=pdo_fetch('select * from '.tablename($table).' where  id=:id',array(':id'=>$id));  
            }     
            if($item==false){  
                //初始数值  
                $item=array(  
                 );    
            }  
		}elseif($op=='delete'){
			$id=intval($_GPC['id']);
			if(empty($id)){
				message('参数错误，请确认操作');
			}
			$temp = pdo_delete($table,array('id'=>$id));
			if($temp==false){
				message('抱歉，刚才修改的数据失败！','', 'error');              
			}else{
				message('删除数据成功！',$this->createWeburl('manage'), 'success');      
			}	
		}elseif($op=='display'){
			$where=" where ";
			$where.=" uniacid=".$_W['uniacid'];
			if (!empty($_GPC['keyword'])){
			$where.=" and user_name like '%{$_GPC['keyword']}%'";
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($table) . $where);
			$start = ($pindex - 1) * $psize;
			$where .= "  order by `id` desc   LIMIT {$start},{$psize}";
	 	    $list = pdo_fetchall("SELECT * FROM ".tablename($table)." ".$where);
			$pager = pagination($total, $pindex, $psize);
		}	

  include $this->template('manage');
