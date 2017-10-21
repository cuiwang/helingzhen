<?php
/**
 * 钻石投票-数据统计
 *
 * 易/福/源/码/网 www.efwww.com
 * 易/福/源/码/网 www.efwww.com
 */




defined('IN_IA') or exit('Access Denied');
is_weixin();
global $_W,$_GPC;
$id = intval($_GPC['id']);
$rid = intval($_GPC['rid']);
$uniacid = intval($_W['uniacid']);
if(empty($id)){
		$id=0;
}
$tys = array('share', 'pv');
$ty=trim($_GPC['ty']);
$ty = in_array($ty, $tys) ? $ty : 'pv';



if($ty=='share'){
	
	 $setshare = 'update ' . tablename($this->tablecount) . ' set share_total=share_total+1 where tid = '.$id.' AND rid='.$rid.' AND uniacid='.$uniacid;
	 
	 if(!pdo_query($setshare)){
		$count=pdo_fetch("SELECT * FROM " . tablename($this->tablecount) . " WHERE tid = :tid AND rid = :rid ", array(':tid' => $id,':rid' => $rid));
		if(empty($count)){
			$indata=array(
				'tid'=>$id,
				'rid'=>$rid,
				'uniacid'=>$_W['uniacid'],
				'share_total'=>1,
		    );
		    pdo_insert($this->tablecount, $indata); 
		}
	}
	exit();
}



if($ty=='pv'){


	$setpv = 'update ' . tablename($this->tablecount) . ' set pv_total=pv_total+1 where tid = '.$id.' AND rid='.$rid.' AND uniacid='.$uniacid;
	if(!pdo_query($setpv)){
		$count=pdo_fetch("SELECT * FROM " . tablename($this->tablecount) . " WHERE tid = :tid AND rid = :rid ", array(':tid' => $id,':rid' => $rid));
		if(empty($count)){
			$indata=array(
				'tid'=>$id,
				'rid'=>$rid,
				'uniacid'=>$_W['uniacid'],
				'pv_total'=>1,
		    );
		    pdo_insert($this->tablecount, $indata); 
		}
	}
	exit();

}

	

	

	

   













