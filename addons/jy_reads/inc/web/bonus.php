<?php
$oparr = array('display','edit','close');
$op =  in_array( $_GPC ['op'], $oparr) ? $_GPC ['op'] : 'display';

// 列表展示
if ($op == 'display') {
	$pindex = max ( 1, intval ( $_GPC ['page'] ) );
	$psize = 10;
	$list = pdo_fetchall ( 'SELECT * FROM ' . tablename ( $this->table_bonus ) . ' WHERE uniacid=:uniacid ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, array (
			':uniacid' => $_W ['uniacid'] 
	) );
	$total = pdo_fetchcolumn ( 'SELECT COUNT(1) FROM ' . tablename ( $this->table_bonus ) . ' WHERE uniacid=:uniacid', array (
			':uniacid' => $_W ['uniacid'] 
	) );
	$activities = pdo_fetchAll('SELECT * FROM '.tablename($this->table_reply).' WHERE status<>0 AND uniacid=:uniacid',array(':uniacid'=>$_W['uniacid']));
	foreach($list as $key => $value){
		if($value['limit']==null||empty($value['limit'])){
			$list[$key]['lista'] = '没有活动';
		}else{
			$temp= json_decode('['.$value['limit'].']',true);
			foreach($temp as $v){
				$tempa = pdo_fetch('SELECT * FROM '.tablename($this->table_reply).' WHERE status<>0 AND uniacid=:uniacid AND id=:replyid',array(':uniacid'=>$_W['uniacid'],':replyid'=>$v));
				if($tempa){
					$list[$key]['lista'] .= $tempa['name'] . ',';
				}
			}
		}
	}
	$page = pagination ( $total, $pindex, $psize );
	include $this->template ( 'web/bonus' );
}

if ($op == 'edit') {
	load()->func('tpl');
	$bonusid = $_GPC ['bonusid'];
	$bonus = pdo_fetch('SELECT * FROM '.tablename($this->table_bonus).' WHERE id=:id AND uniacid=:uniacid',array(':id'=>$bonusid,':uniacid'=>$_W['uniacid']));
	
	if(checksubmit()){
		$data = array();
		$data['bonusname'] = $_GPC['bonusname'];
		$data['bonusthumb'] = $_GPC['bonusthumb'];
		$data['bonuscount'] = $_GPC['bonuscount'];
		$data['bonusneed'] = $_GPC['bonusneed'];
		$data['displayorder'] = $_GPC['displayorder'];
		$data['bonusvalue'] = $_GPC['bonusvalue'];

		$data['isrange'] = $_GPC['isrange'];
		$data['bonusvaluerange'] = $_GPC['bonusvaluerange'];

		$data['sendname'] = $_GPC['sendname'];
		$data['actname'] = $_GPC['actname'];

		$data['status'] = $_GPC['status'];
		$data['location'] = $_GPC['location'];
		$data['area'] = $_GPC['area'];
		$data['uniacid'] = $_W['uniacid'];
		$data['islimit'] = $_GPC['islimit'];
		$data['wishing'] = $_GPC['wishing'];
		$data['remark'] = $_GPC['remark'];
		$data['bonusmsg'] = $_GPC['bonusmsg'];
		$data['share'] = $_GPC['share'];

		$data['info'] = empty($_GPC ['property'])?'':json_encode($_GPC ['property']);
		// 添加活动可用红包，不为全部通用
		if(!empty($data['islimit'])){
			$data['limit'] = json_encode($_GPC['limit']);
			$data['limit'] = str_replace('[','',$data['limit']);
			$data['limit'] = str_replace(']','',$data['limit']);
		}

		if(!$bonus){
			$data['bonusrest'] = $_GPC['bonuscount'];
			pdo_insert($this->table_bonus,$data);
			$bonusid = pdo_insertid();
			message('添加成功',$this->createWebUrl('bonus',array('bonusid'=>$bonusid,'op'=>'edit')));
		}else{
			if($data['bonuscount'] < $bonus['bonuscount']){
				// 减少的时候
				if(($bonus['bonuscount'] - $data['bonuscount']) < $bonus['bonusrest']){
					$data['bonusrest'] = $bonus['bonusrest'] + $data['bonuscount'] - $bonus['bonuscount'];
				}else{
					$data['bonusrest'] = 0;
				}
			}else{
				// 增加的时候
				$data['bonusrest'] = $_GPC['bonuscount'] - $bonus['bonuscount'] + $bonus['bonusrest'];
			}
			// 修正
			if($data['bonuscount'] < $data['bonusrest']){
				$data['bonusrest'] = $data['bonuscount'];
			}
			pdo_update($this->table_bonus,$data,array('id'=>$bonusid));
			message('修改成功',$this->createWebUrl('bonus',array('bonusid'=>$bonusid,'op'=>'edit')));
		}
	}

	if(!$bonus){
		$bonus = array();
		$bonus['bonusname'] = '红包';
		$bonus['bonusthumb'] = '';
		$bonus['bonuscount'] = 1;
		$bonus['bonusneed'] = 1;
		$bonus['bonusrest'] = 1;
		$bonus['displayorder'] = 0;
		$bonus['bonusvalue'] = 1.0;
		$bonus['status'] = 1;
		$bonus['location'] = 0;
		$bonus['area'] = '';
		$bonus['info'] = '';
		$bonus['islimit'] = 0;
		$bonus['limit'] = '';

		$bonus['isrange'] = 0;
		$bonus['bonusvaluerange'] = '';

		$bonus['sendname'] = '集阅读';
		$bonus['actname'] = '集阅读';

		$bonus['wishing'] = '恭喜';
		$bonus['remark'] = '集阅读';
		$bonus['bonusmsg'] = '感谢参加此活动！';
		$bonus['limitarr'] = array();
	}

	// 获取activityid
	if(empty($bonus['limit'])){
		$bonus['limitarr'] = array();
	}else{
		$bonus['limitarr'] = json_decode('['.$bonus['limit'].']',true);
	}
	$bonus['info'] = json_decode($bonus['info'],true);
	$activities = pdo_fetchAll('SELECT * FROM '.tablename($this->table_reply).' WHERE status<>0 AND uniacid=:uniacid',array(':uniacid'=>$_W['uniacid']));
	$properties = pdo_fetchAll('SELECT * FROM '.tablename($this->table_property));
	include $this->template ( 'web/bonus' );
}

if($op == 'close'){
	$bonusid = $_GPC ['bonusid'];
	$re = pdo_update($this->table_bonus,array('status'=>0),array('id'=>$bonusid));
	if($re){
		message('修改成功',$this->createWebUrl('bonus',array('bonusid'=>$bonusid)));
	}else{
		message('修改失败',$this->createWebUrl('bonus',array('bonusid'=>$bonusid,'op'=>'edit')));
	}
}
?>