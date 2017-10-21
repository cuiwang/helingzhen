<?php
/**
 * 女神来了导出
 *
 */
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$rid= intval($_GPC['rid']);
$uniacid = $_W['uniacid'];
$indexpx = intval($_GPC['indexpx']);
		$indexpxf = intval($_GPC['indexpxf']);
if(empty($rid)){
    message('抱歉，传递的参数错误！','', 'error');              
}

	$reply = pdo_fetch("SELECT * FROM ".tablename($this->table_reply)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
	
	    $where = '';
		$order = '';
	//0 按最新排序 1 按人气排序 3 按投票数排序
		if ($indexpx == '-1') {
			$order .= " `createtime` DESC";
		}elseif ($indexpx == '1') {
			$order .= " `hits` + `xnhits` DESC";
		}elseif ($indexpx == '2') {
			$order .= " `photosnum` + `xnphotosnum` DESC";
		}
		
		//0 按最新排序 1 按人气排序 3 按投票数排序  倒叙
		if ($indexpxf == '-1') {
			$order .= " `createtime` ASC";
		}elseif ($indexpxf == '1') {
			$order .= " `hits` + `xnhits` ASC";
		}elseif ($indexpxf == '2') {
			$order .= " `photosnum` + `xnphotosnum` ASC";
		}
		
		if (empty($indexpx) && empty($indexpxf)) {
			$order .= " `createtime` DESC";
		}

	if ($rid>0){
	    $list = pdo_fetchall('SELECT * FROM '.tablename($this->table_users).' WHERE rid =:rid  and uniacid= :uniacid '.$where.' ORDER BY '.$order.' ', array(':rid' => $rid,':uniacid'=>$uniacid));	
	}else{
	    $list = pdo_fetchall('SELECT * FROM '.tablename($this->table_users).' WHERE uniacid= :uniacid '.$where.' ORDER BY '.$order.' ', array(':uniacid'=>$uniacid));	
	}
 

$tableheader = array('ID', '排名',  '姓名', '手机号','微信号' ,'QQ号', '邮箱','地址' , '宣言','参赛照片' , '真实票数', '虚拟票数', '真实人气', '虚拟人气', '分享数', 'IP', '报名时间', '简介');
$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $mid => $value) {
	$sharenum = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename($this->table_data)." WHERE uniacid = :uniacid and tfrom_user = :tfrom_user and rid = :rid", array(':uniacid' => $uniacid,':tfrom_user' => $value['from_user'],':rid' => $rid));
	if(empty($value['realname'])){
		$value['realname']=$value['nickname'];
	}else {
		$value['realname']=$value['realname'];
	}
	if(empty($value['weixin'])){
		$value['weixin']=$value['from_user'];
	}else {
		$value['weixin']=$value['weixin'];
	}
	$p = $mid + 1;
	$html .= $value['uid'] . "\t ,";	
	$html .= $p . "\t ,";	
	$html .= $value['realname'] . "\t ,";	
	$html .= $value['mobile'] . "\t ,";	
	$html .= $value['weixin'] . "\t ,";	
	$html .= $value['qqhao'] . "\t ,";	
	$html .= $value['email'] . "\t ,";	
	$html .= $value['address'] . "\t ,";	
	$html .= $value['photoname'] . "\t ,";
	$html .= $value['photo'] . "\t ,";	
	$html .= $value['photosnum'] . "\t ,";	
	$html .= $value['xnphotosnum'] . "\t ,";	
	$html .= $value['hits'] . "\t ,";
	$html .= $value['xnhits'] . "\t ,";	
	$html .= $sharenum . "\t ,";	
	$html .= $value['createip'] . "\t ,";	
	$html .= date('Y年m月d日 H:i:s',$value['createtime']) . "\t ,";	
	$html .= $value['description'] . "\t ,";	
	$html .= "\n";
}
$filename = $reply['title'].'_'.$rid.'_'.$now;

header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=".$filename.".csv");

echo $html;
exit();
