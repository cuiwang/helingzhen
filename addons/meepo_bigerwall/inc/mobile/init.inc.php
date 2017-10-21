<?php
global $_GPC, $_W;
			$weid = $_W['uniacid'];
			$wallnum = intval($_GPC['num']);
			$rid = intval($_GPC['rid']); 
			$list['list'] = pdo_fetchall("SELECT * FROM ".tablename('weixin_wall')." WHERE   weid = '{$weid}' AND isshow=1 AND rid='{$rid}' ORDER BY createtime ASC");
			if(!empty($list['list']) && is_array($list['list'])){
				foreach($list['list'] as &$row){
					$row['content'] =  emotion(emo($row['content']));
				}
				unset($row);
			}
		  $list['time'] = $COUNT = count($list['list']);
		  
		  if($COUNT){
          die(json_encode($list));
			}else{
					$data['list'] = array();
					$data['time'] = 0;
					die(json_encode($data));
			}
			