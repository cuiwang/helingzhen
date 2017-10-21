<?php 
	global $_W,$_GPC;
	$share_data = $this->module['config'];
	$openid = m('user') -> getOpenid();
	$id = $_GPC['id'];
	
	$myCart_num = pdo_fetchcolumn("select count(*) from".tablename('weliam_indiana_cart')."where openid = '{$openid}' and uniacid={$_W['uniacid']}");	
	//进入晒单页面
	if(empty($id)){
		$result = pdo_fetchall("select * from".tablename('weliam_indiana_showprize')."where uniacid = '{$_W['uniacid']}' and status = 2 order by id desc limit 0,10");
		foreach($result as $key => $value){
			$result[$key]['thumbs'] = unserialize($value['thumbs']);
			$list = pdo_fetch("select * from".tablename('weliam_indiana_period')."where period_number='{$value['period_number']}' and uniacid='{$_W['uniacid']}'");
			$result[$key]['nickname'] = $list['nickname'];
		}
		if(!empty($result[9]['id'])){
			$id = $result[9]['id'];
		}else{
			$id = '-1';
		}
		
		include $this->template('allshare');		
	}else{
		$result = pdo_fetchall("select * from".tablename('weliam_indiana_showprize')."where uniacid = '{$_W['uniacid']}' and status = 2 and id<'{$id}' order by id desc limit 0,10");
		if(!empty($result)){
			foreach($result as $key => $value){
				$result[$key]['thumbs'] = unserialize($value['thumbs']);
				$list = pdo_fetch("select * from".tablename('weliam_indiana_period')."where period_number='{$value['period_number']}' and uniacid='{$_W['uniacid']}'");
				$result[$key]['nickname'] = $list['nickname'];
			}	
			foreach ($result as $item){
				$row=array(
					'id'=>$item['id'],
					'title'=>$item['title'],
					'nickname'=>$item['nickname'],
					'createtime'=>date("m-d H:i",$item['createtime']),
					'detail'=>$item['detail'],
					'thumbs'=>tomedia('..'.$item['thumbs'][0]),
				);
				$info[]=$row;			
			}				
		}
		
		for( $i=10;empty($result[$i]['id'])&&$i>-1;$i--){
			$id = $result[$i-1]['id'];
		}
		if(empty($id)){
			$id = -1;
		}
		$data = array(
			'success'=>$id,
			'list'=>$info,	
		);
		echo json_encode($data);
	}
	?>