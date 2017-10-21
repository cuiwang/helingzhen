<?php
global $_GPC, $_W;
			 $weid = $_W['uniacid'];
			 $colors = array('white','red','green','blue','yellow');
			 $sizes = array('0','5');
			 if($_W['isajax']){
			    $rid = intval($_GPC['rid']);
				$checktime = intval($_GPC['checktime']);
				$nowtime = intval($_GPC['time']) + 20;
				$ridwall = pdo_fetch("SELECT `danmufontcolor` FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid", array(':weid'=>$weid,':rid'=>$rid));
				if($checktime == 0){
				 $all = pdo_fetchall("SELECT * FROM ".tablename('weixin_wall')." WHERE weid=:weid AND rid=:rid AND isshow=:isshow ORDER BY createtime DESC",array(':weid'=>$weid,':rid'=>$rid,':isshow'=>1));
				if(!empty($all)){
				   $danmu['maxtime'] = $all['0']['createtime'];
				   $time = 5;
				   foreach($all as $row){
						 if(empty($ridwall['danmufontcolor'])){
								$color=  array_rand($colors);
						 }else{
						    $color=  $ridwall['danmufontcolor'];
						 }
						
								$size=  array_rand($sizes);
						
						 if(empty($row['image']) && !preg_match("/(.*)\/(.*)/",$row['content'])){
								$data[] = array('text'=>$row['nickname'].": ".$row['content'],'color'=>$colors[$color],'size'=>$sizes[$size],'position'=>'0','time'=>$time); 
								$time = $time + 20;
						 }  
				   }
				   $danmu['data'] = $data;
				 }
				}else{
				       $all = pdo_fetchall("SELECT * FROM ".tablename('weixin_wall')." WHERE weid=:weid AND rid=:rid AND isshow=:isshow AND createtime > :createtime ORDER BY createtime DESC",array(':weid'=>$weid,':rid'=>$rid,':isshow'=>1,':createtime'=>$checktime));
							 if(!empty($all)){
								
								 $danmu['maxtime'] = $all[0]['createtime'];
								 
								 foreach($all as $row){
									   if(empty($ridwall['danmufontcolor'])){
												$color = randrgb();
										 }else{
												$color=  $ridwall['danmufontcolor'];
										 }
										 
												$size=  array_rand($sizes);
										 
									
									
									 if(empty($row['image']) && !preg_match("/(.*)\/(.*)/",$row['content'])){
											$data[] = array('text'=>$row['nickname'].": ".$row['content'],'color'=>$color,'size'=>$sizes[$size],'position'=>'0','time'=>$nowtime); 
											$nowtime = $nowtime + 20;
									 }
									 
								 }
								 $danmu['data'] = $data;
							 }
				}
				die(json_encode($danmu));
    }
function randrgb(){  
    $str='0123456789ABCDEF';  
    $estr='#';  
    $len=strlen($str);  
    for($i=1;$i<=6;$i++)  
    {  
        $num=rand(0,$len-1);    
        $estr=$estr.$str[$num];   
    }  
    return $estr;  
} 