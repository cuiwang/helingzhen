<?php
global $_GPC, $_W;
$weid = $_W['uniacid'];
$modules_table = 'weixin_modules';
		$id = intval($_GPC['id']);
		if(empty($id)){
		   message('错误、规则不存在！');
		}
		$pindex = max(1, intval($_GPC['page']));
	      $psize = 20;
		   $op = empty($_GPC['op']) ? 'list' : $_GPC['op'];
			 $status = empty($_GPC['status']) ? '1' : $_GPC['status'];
			if($op == 'list'){
			  $params = array();
        $where = " weid = :weid AND rid = :rid AND status = :status";
				$params[':weid'] = $_W['uniacid'];
				$params[':rid'] = $id;
				$params[':status'] = $status;
				$sql = "SELECT * FROM ".tablename($modules_table)." WHERE {$where} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
				$lists = pdo_fetchall($sql,$params);
				
		   }elseif($op == 'post'){
			    load()->func('tpl');
					$modules_id = intval($_GPC['modules_id']);
					if(!empty($modules_id)){
					  $sql = "SELECT * FROM ".tablename($modules_table)." WHERE rid=:rid AND weid=:weid AND id=:id";
				    $list = pdo_fetch($sql,array(':rid'=>$id,':weid'=>$weid,':id'=>$modules_id));
					}
					if(checksubmit('submit')){
					//批量删除
						$modules_id = intval($_GPC['modules_id']);
						$data = array();
						$data['name']=$_GPC['name'];
						$data['status']=intval($_GPC['status']);
						$data['modules_rid']=intval($_GPC['modules_rid']);
						$data['rid']=$id;
						$data['bg']=$_GPC['bg'];
						$data['weid']=$weid;
						if(empty($modules_id)){
								$data['createtime'] = time();
							pdo_insert($modules_table,$data);
							message('新增成功',$this->createWebUrl("modules_manage",array('id'=>$id)),"success");
						}else{
						  pdo_insert($modules_table,$data);
							message('编辑成功',$this->createWebUrl("modules_manage",array('id'=>$id)),"success");
						}
					
					}
					
			 }elseif($op == 'delete'){
			   
					$modules_id = intval($_GPC['modules_id']);
					if(empty($modules_id)){
		           message('删除项目不存在',$this->createWebUrl('modules_manage',array('id'=>$id)),"error");
					}
					
					pdo_delete($modules_table,array('id'=>$signid,'rid'=>$id,'weid'=>$_W['uniacid']));
					message('删除成功',$this->createWebUrl("signmanage",array('id'=>$id)),"success");
			 }else{
			   message('访问错误');
			 }

				if(checksubmit('delete')){
					//批量删除
					$select = $_GPC['select'];
					if(empty($select)){
						message('请选择删除项',$this->createWebUrl("modules_manage",array('id'=>$id)),"error");
					}
					foreach ($select as $se) {
						pdo_delete($modules_table,array('id'=>$se,'rid'=>$id,'weid'=>$_W['uniacid']));
					}
					message('批量删除成功',$this->createWebUrl("modules_manage",array('id'=>$id)),"success");
				}
				
				
include $this->template('modulesmanage');  
