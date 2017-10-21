<?php
global $_GPC, $_W;
	   $weid = $_W['uniacid'];
	   $rid = intval($_GPC['rid']);
		 $start = intval($_GPC['utime']);//加载前条数
		 $end = intval($_GPC['endtime']);//检测完最后条数
     //$list['list'] = pdo_fetchall("SELECT * FROM ".tablename('weixin_wall')." WHERE   weid = '{$weid}' AND rid='{$rid}' AND isshow=1 AND num > '{$start}' AND  num <= '{$end}' ORDER BY createtime");
		 $psize = $end - $start;
		// nd = intval($_GPC['endtime']);//检测完最后条数
     $list['list'] = pdo_fetchall("SELECT * FROM ".tablename('weixin_wall')." WHERE   weid = '{$weid}' AND rid='{$rid}' AND isshow=1   ORDER BY createtime ASC LIMIT ".$start.','.$psize);
			if(!empty($list['list']) && is_array($list['list'])){
		     foreach($list['list'] as &$row){
		          $row['content'] =  emotion(emo($row['content']));
		     }
			   unset($row);
			   die(json_encode($list));
		  }else{
	           die('');
	    }
