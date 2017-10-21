<?php 
	global $_W,$_GPC;
	$openid = m('user') -> getOpenid();
	$id = $_GPC['id'];
	//判断第一次进入界面
	if(empty($id)){
		$result = pdo_fetchall("select * from".tablename('weliam_indiana_showprize')."where uniacid = '{$_W['uniacid']}' and openid='{$openid}' order by id desc limit 0,20");
		foreach($result as $key => $value){
		$result[$key]['thumbs'] = unserialize($value['thumbs']);
			foreach($result[$key]['thumbs'] as $kk=>$v){
				$result[$key]['thumbs'][$kk] = tomedia('..'.$v);
			}
		$list = pdo_fetch("select * from".tablename('weliam_indiana_period')."where period_number='{$value['period_number']}' ");
		$result[$key]['nickname'] = $list['nickname'];
		}
		if(!empty($result[4]['id'])){
			$id = $result[4]['id'];
		}else{
			$id = '-1';
		}
		include $this->template('myshare');
	}else{
		//刷新加载我的晒单
	}
	
	?>