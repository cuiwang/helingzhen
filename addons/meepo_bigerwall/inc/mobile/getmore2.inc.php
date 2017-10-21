<?php
global $_GPC, $_W;
	   $weid = $_W['uniacid'];
	   $wallnum = intval($_GPC['num']);
     $signnum = intval($_GPC['signnum']);
	   $rid = intval($_GPC['rid']);
			$all = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('weixin_wall')." WHERE   weid = '{$weid}' AND isshow=1 AND rid='{$rid}'");
			if($wallnum < $all){
							 $list['time'] = $all;
               $list['hadsay'] = 1;
			  
			}else{
			         $list['hadsay'] = 0;
			   
			}
      $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_signs') . " WHERE weid = '{$weid}' AND status=1 AND rid='{$rid}'");
			if($signnum == 0){
				
				if($total > 0){
					
					 $list['hadsign'] = 1;
					 $list['time2'] = $total;
					 
				}else{
				   $list['hadsign'] = 0;
				}
			}else{
			    if($signnum < $total){
				     $list['hadsign'] = 1;
					 $list['time2'] = $total;
				}else{
				   $list['hadsign'] = 0;
				}
			}
            
             die(json_encode($list));