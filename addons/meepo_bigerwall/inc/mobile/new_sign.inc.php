<?php
global $_W,$_GPC;
$weid  = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$max_id = intval($_GPC['max_id']);
if($_W['isajax']){
$data = array();
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_signs') . " WHERE weid = '{$weid}' AND status=1 AND rid='{$rid}'");
			if($max_id == $total){
					$data['max_id'] = '';
					$data['data'] = array();
					$data['edit_message_list'] = array();
					$return =  error(0,$data);
			}else{
					$psize = $total - $max_id ;
					$all = pdo_fetchall("SELECT * FROM ".tablename('weixin_signs')." WHERE   weid = '{$weid}' AND rid='{$rid}' AND status=1 ORDER BY createtime ASC LIMIT ".$max_id.','.$psize);
					if(is_array($all) && !empty($all)){
						 foreach($all as &$row){
							$row['image'] = $row['avatar'];
							$row['name'] = $row['nickname'];
						  $row['text'] = $row['content'];
							$row['company'] = '';
							$row['position'] = '';
					 }
					 unset($row);
					}
			    $data['max_id'] = $total;
					$data['data'] = $all;
					$data['edit_message_list'] = $all;
					$return =  error(0,$data);
			}
			die(json_encode($return));
}
