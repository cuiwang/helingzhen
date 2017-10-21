<?php
		global $_W,$_GPC;
		$user_table = 'weixin_flag';
		$vote_table = 'weixin_vote';
		$id = intval($_GPC['id']);
		if(empty($id)){
		   message('错误、规则不存在！');
		}
		
    
		$pindex = max(1, intval($_GPC['page']));
	  $psize = 20;
		$op = empty($_GPC['op']) ? 'list' : $_GPC['op'];
		    $where = " weid = :weid AND rid = :rid";
				$params[':weid'] = $_W['uniacid'];
				$params[':rid'] = $id;
			
			if($op == 'list'){
			  $params = array();
        $where = " weid = :weid AND rid = :rid AND vote != ''";
				if(!empty($_GPC['vote_this'])){
						$vote_this = intval($_GPC['vote_this']);
						$where .= " AND vote = '{$vote_this}'";
				}
				$params[':weid'] = $_W['uniacid'];
				$params[':rid'] = $id;
				$sql = "SELECT * FROM ".tablename($user_table)." WHERE {$where} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
				$lists = pdo_fetchall($sql,$params);
				
				if(is_array($lists)){
						foreach($lists as &$row){
								$row['name'] = pdo_fetchcolumn("SELECT `name` FROM ".tablename($vote_table)." WHERE weid = :weid AND rid=:rid AND id = :id",array(':weid'=>$_W['uniacid'],':rid'=>$id,':id'=>$row['vote']));
						}		
						unset($row);
				}

				$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($user_table) . " WHERE {$where} ", $params);
			  $pager = pagination($total, $pindex, $psize);
				$vote_ids = pdo_fetchall("SELECT `name`,`id` FROM ".tablename($vote_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$_W['uniacid'],':rid'=>$id));
		   }elseif($op == 'delete'){
					$vote_id = intval($_GPC['vote_id']);
					if(empty($vote_id)){
		           message('删除项目不存在',$this->createWebUrl("vote_person",array('id'=>$id,'page'=>$pindex)),"error");
					}else{
							$vote_one = pdo_fetchcolumn("SELECT `vote` FROM ".tablename($user_table)." WHERE weid = :weid AND rid=:rid AND id = :id",array(':weid'=>$_W['uniacid'],':rid'=>$id,':id'=>$vote_id));
							if($vote_one){
								pdo_query("UPDATE ".tablename($vote_table)." SET res = res - 1 WHERE id = '{$vote_one}' AND weid = '{$_W['uniacid']}' AND rid = '{$id}'");
							}
					}
					pdo_update($user_table,array('vote'=>''),array('id'=>$vote_id,'rid'=>$id,'weid'=>$_W['uniacid']));
					message('删除成功',$this->createWebUrl("vote_person",array('id'=>$id,'page'=>$pindex)),"success");
			 }else{
			   message('访问错误');
			 }

		if(checksubmit('delete')){
			//批量删除
			$select = $_GPC['select'];
			if(empty($select)){
				message('请选择删除项',$this->createWebUrl("vote_person",array('id'=>$id,'page'=>$pindex)),"error");
			}
			foreach ($select as $se) {
				$vote_one = pdo_fetchcolumn("SELECT `vote` FROM ".tablename($user_table)." WHERE weid = :weid AND rid=:rid AND id = :id",array(':weid'=>$_W['uniacid'],':rid'=>$id,':id'=>$se));
				if($vote_one){
					pdo_query("UPDATE ".tablename($vote_table)." SET res = res - 1 WHERE id = '{$vote_one}' AND weid = '{$_W['uniacid']}' AND rid = '{$id}'");
				}
				pdo_update($user_table,array('vote'=>''),array('id'=>$se,'rid'=>$id,'weid'=>$_W['uniacid']));
			}
			message('批量删除成功',$this->createWebUrl("vote_person",array('id'=>$id,'page'=>$pindex)),"success");
		}
    include $this->template('vote_person');
		
	 