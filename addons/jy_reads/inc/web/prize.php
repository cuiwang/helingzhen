<?php
$oparr = array('display','add','delete','remove','verifier','qrcode');
$op =  in_array( $_GPC ['op'], $oparr) ? $_GPC ['op'] : 'display';

// 列表展示
if ($op == 'display') {
	$replyid = intval ( $_GPC ['replyid'] );
	$reply = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_reply ) . ' WHERE id=:replyid', array (
			':replyid' => $replyid 
	) );
	if(!$reply){
		message ( '规则不存在', referer(), 'info' );
	}
	$prizes = pdo_fetchall ( 'SELECT p.*,r.share_title as title FROM ' . tablename ( $this->table_prize ) . ' as p left join ' . tablename ( $this->table_reply ) . ' as r on r.rid=p.rid where r.id=:replyid and p.status=:status', array (
			':replyid' => $replyid,
			':status' => 1 
	) );
	include $this->template ( 'web/prize' );
}

// 添加奖项
if ($op == 'add') {
	load()->func('tpl');
	$key = $_GPC['key'];

	$properties = pdo_fetchAll('SELECT * FROM '.tablename($this->table_property));
	if($properties) {
		$tempproperties = "<div class=\"form-group\"><span class=\"col-xs-3 control-label\">搜集信息：</span> <div class=\"col-xs-9\">";
		foreach ($properties as  $property) {
			$tempproperties .= "<label class=\"checkbox-inline\"><input type=\"checkbox\" name=\"nproperty[".$key."][]\" value=\"".$property['propertykey']."\">".$property['propertyvalue']."</label>";
		}
		$tempproperties .= "</div></div>";
	}else{
		$tempproperties = "";
	}
	$temp = tpl_form_field_image("nprizethumb[$key]",'');
	$str = <<<EOF
<div class="jumbotron" id="new_{$key}">
	<button type="button" class="close">
		<span onclick="deleteNew({$key})">&times;</span>
		<span class="sr-only">Close</span>
	</button>
	<div class="row">
		<div class="col-xs-6">
			<div class="row">
				<div class="input-group">
					<span class="input-group-addon">奖品名称：</span>
					<input type="text" name="nprizename[$key]" class="form-control" value="" />
				</div><br />
				<div class="input-group">
					<span class="input-group-addon">显示顺序：</span>
					<input type="text" name="ndisplayorder[$key]" class="form-control" value="" />
				</div><br />
				<div class="input-group">
					<span class="input-group-addon">奖品总数量：</span>
					<input type="text" name="nprizecount[$key]" class="form-control" value="" />
				</div><br />
				<div class="input-group">
					<span class="input-group-addon">兑奖需要数量：</span>
					<input type="text" name="nprizeneed[$key]" class="form-control" value="" />
				</div><br />
				<div class="input-group">
					<span class="input-group-addon">剩余奖品数：</span>
					<input type="text" name="nprizerest[$key]" class="form-control" value="" />
				</div>
				$tempproperties
			</div>
		</div>
		<div class="col-xs-6">
			<div class="row">
				<div class="form-group">
					<label class="col-xs-3 control-label">链接地址：</label>
					<div class="col-xs-9">
						<input type="text" name="nprizeurl[$key]" class="form-control" value="" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-3 control-label">显示图片：</label>
					<div class="col-xs-9">
						$temp
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-3 control-label">是否加入分享显示：</label>
					<div class="col-xs-9">
						<label class="radio-inline">
  							<input type="radio" name="nshare" value="0" checked> 否
						</label>
						<label class="radio-inline">
  							<input type="radio" name="nshare" value="1"> 是
						</label>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
EOF;
	if(empty($key))
		echo json_encode(array('result'=>false,'msg'=>'网络错误'));
	else
		echo json_encode(array('result'=>true,'msg'=>$str));
	exit();
}
// 删除奖项
if ($op == 'delete') {
	$prizeid = $_GPC ['prizeid'];
	$prize = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_prize ) . ' WHERE id=:id AND status=:status', array (
			':id' => $prizeid,
			':status' => 1 
	) );
	if ($prizeid && $prize) {
		$re = pdo_update ( $this->table_prize, array (
				'status' => 0 
		), array (
				'id' => $prizeid 
		) );
		if ($re) {
			message ( '删除成功', referer(), 'success' );
		} else {
			message ( '删除失败', referer(), 'error' );
		}
	} else {
		message ( '兑奖项不存在', referer(), 'info' );
	}
}
// 删除奖项
if ($op == 'remove') {
	$prizeid = intval ( $_GPC ['prizeid'] );
	$prize = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_prize ) . ' WHERE id=:id AND status=:status', array (
			':id' => $prizeid,
			':status'=>1 
	) );
	if ($prizeid && $prize) {
		$re = pdo_update ( $this->table_prize, array (
				'status' => 0 
		), array (
				'id' => $prizeid 
		) );
		if ($re) {
			echo json_encode ( array (
					'result' => true,
					'msg' => '' 
			) );
			exit ();
		}else{
			echo json_encode ( array (
					'result' => false,
					'msg' => '操作错误'
			) );
			exit ();
		}
	}else{
		echo json_encode ( array (
				'result' => false,
				'msg' => '不存在'
		) );
		exit ();
	}
}
// 获核销者
if ($op == 'verifier') {
	$prizeid = $_GPC ['prizeid'];
	if (empty ( $prizeid )) {
		die ( json_encode ( array (
				'result' => false,
				'msg' => "信息缺失" 
		) ) );
	}
	$verifiers = pdo_fetchall ( 'select m.nickname as nickname,m.avatar as avatar from ' . tablename ( $this->table_verifier ) . ' as v left join ' . tablename ( 'mc_members' ) . ' as m on v.uid=m.uid  where prizeid=:prizeid', array (
			':prizeid' => $prizeid 
	) );
	if (! $verifiers) {
		die ( json_encode ( array (
				'result' => true,
				'msg' => '暂无' 
		) ) );
	}
	
	$str = '<div class="row">';
	foreach ( $verifiers as $verifier ) {
		$str .= '<div class="col-xs-3">';
		$str .= '<a href="#" class="thumbnail">';
		$str .= '<img src="' . $verifier ['avatar'] . '" alt="' . $verifier ['nickname'] . '">';
		$str .= '<div class="caption">';
		$str .= '<h4>' . $verifier ['nickname'] . '</h4>';
		$str .= '</div>';
		$str .= '</a>';
		$str .= '</div>';
	}
	$str .= '</div>';
	die ( json_encode ( array (
			'result' => true,
			'msg' => $str 
	) ) );
}

// 获取二维码
if ($op == 'qrcode') {
	$prizeid = $_GPC ['prizeid'];
	if (empty ( $prizeid )) {
		die ( json_encode ( array (
				'result' => false,
				'msg' => "信息缺失" 
		) ) );
	}
	$prize = pdo_fetch ( 'select * from ' . tablename ( $this->table_prize ) . ' where id=:id', array (
			':id' => $prizeid 
	) );
	if ($prize) {
		die ( json_encode ( array (
				'result' => true,
				'msg' => $_W ['siteroot'] .'app/'. substr ( $this->createMobileUrl ( 'Verifier', array (
						'prizeid' => $prizeid,
						'mask' => time () 
				) ), 2 ) 
		) ) );
	} else {
		die ( json_encode ( array (
				'result' => false,
				'msg' => "信息缺失" 
		) ) );
	}
}
?>