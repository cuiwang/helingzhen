<?php 
	global $_W,$_GPC;
	$uniacid=$_W['uniacid'];
       $op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if($op == 'display'){
			$list      = pdo_fetchall("select * from " . tablename($this->adv) . " where enabled=1 and uniacid= '{$_W['uniacid']}' order by displayorder asc");
		foreach ($list as &$adv) {
			if (substr($adv['link'], 0, 5) != 'http:') {
				$adv['link'] = $adv['link'];
			}
		}
		unset($adv);
		}elseif($op == 'post'){
			$id     = intval($_GPC['id']);
			if(checksubmit('submit')){
				
				if ($id) {
					 $data = array(
						'advname'  => $_GPC['advname'],
						'link'     => $_GPC['link'],
						'thumb'      =>$_GPC['thumb'],
						'displayorder' => $_GPC['displayorder'],
						'enabled'     =>$_GPC['enabled'],
						'type'    => intval($_GPC['type']),
		                );
                     pdo_update($this->adv, $data, array('id' => $id,'uniacid' => $uniacid));
				}else{
					 $data = array(
					 	'uniacid'    => $_W['uniacid'],
						'advname'  => $_GPC['advname'],
						'link'     => $_GPC['link'],
						'thumb'      =>$_GPC['thumb'],
						'displayorder' => $_GPC['displayorder'],
						'enabled'     =>$_GPC['enabled'],
						'type'    => intval($_GPC['type']),
						'add_time'  => TIMESTAMP,
		                );
					pdo_insert($this->adv, $data);
				}
				message('更新幻灯片成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
			}else{
				if($id){                
		       $adv = pdo_fetch("select * from " . tablename($this->adv) . " where id='{$id}' and uniacid= '{$uniacid}' ");
            }
			}
		} elseif ($op == 'delete') {
			$id  = intval($_GPC['id']);
            if($id){
				pdo_delete($this->adv,  array('id' => $id,'uniacid' => $uniacid));
            }
			message('幻灯片删除成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
		} 

		include $this->template('adv');
		exit;