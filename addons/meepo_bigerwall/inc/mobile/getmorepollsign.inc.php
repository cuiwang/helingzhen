<?php
global $_GPC, $_W;
	   $weid = $_W['uniacid'];
	   $endtime=intval($_GPC['endtime']);
	   $starttime=intval($_GPC['utime']);
       $rid = intval($_GPC['rid']);
	   $it = '签到';
	    if($endtime > $starttime){
          if($starttime == 0){
		      $all = pdo_fetchall("SELECT * FROM ".tablename('weixin_signs')." WHERE   weid = '{$weid}' AND status=1 AND rid='{$rid}' ORDER BY createtime ASC");
			  if(is_array($all) && !empty($all)){
			     foreach($all as &$row){
					  $row['content'] = emotion(emo($row['content']));
				 }
				 unset($row);
			  }
			  $list['list'] = $all;
		  }else{
			  $psize = $endtime - $starttime;
		     $all = pdo_fetchall("SELECT * FROM ".tablename('weixin_signs')." WHERE   weid = '{$weid}' AND rid='{$rid}' AND status=1 ORDER BY createtime ASC LIMIT ".$starttime.','.$psize);
			  if(is_array($all) && !empty($all)){
			     foreach($all as &$row){
					  $row['content'] = emotion(emo($row['content']));
				 }
				 unset($row);
			  }
			  $list['list'] = $all;
		  }   
        
	    }else{
	     $list['list'] = array();
	    }
	   
	   if(!empty($list['list'])){
         die(json_encode($list));
	   }else{
	     die('');
	   }
	