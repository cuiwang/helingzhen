<?php
/**
 * [Weizan System] Copyright (c) 2014 WEIZANCMS.COM
 
 */
defined('IN_IA') or exit('Access Denied');
include_once 'common/common.inc.php';
$dos = array('member', 'record', 'buypackage', 'chongzhi');
$do = in_array($do, $dos) ? $do : 'member';
global $_W,$_GPC;
        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;
if ($do == 'member') {
		//这个操作被定义用来呈现 管理中心导航菜单
        if($_W['ispost'] && $_W['isajax']) {
            $setting = uni_setting($_W['uniacid'], array('groupdata'));
            $setting["groupdata"]["is_auto"] = $_GPC["is_auto"];
            pdo_update("uni_settings",array("groupdata"=>iserializer($setting["groupdata"])),array("uniacid"=>$_W['uniacid']));
            die(json_encode(array("code"=>1, "message"=>"操作成功.")));
        }
        $settings = get_settings();
        $service = explode("|",$settings["service_qqs"]);
        $qqs = array();
        foreach($service as $ser) {
            list($name,$qq) = explode("-",$ser);
            $qqs[] = array(
                "name"=>$name,
                "qq"=>$qq
            );
        }
		template('member/center');
}

if ($do == 'record') {
		checklogin();
		$uid=$_W['uid'];
		$_W['page']['title'] = "消费记录";
			if($_W['isfounder']){
				$list = pdo_fetchall("SELECT * FROM ".tablename("users_credits_record")." ORDER BY createtime LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
				$total   = pdo_fetchcolumn("SELECT * FROM ".tablename("users_credits_record"));
				}
			else{
				$list = pdo_fetchall("SELECT * FROM ".tablename("users_credits_record")." WHERE uid=:uid ORDER BY createtime LIMIT " . ($pindex - 1) * $psize . ',' . $psize,array(":uid"=>$_W["uid"]));
				$total   = pdo_fetchcolumn("SELECT * FROM ".tablename("users_credits_record")." WHERE uid=:uid " ,array(":uid"=>$_W["uid"]));
				}
			$uni = pdo_fetchall("SELECT name,uniacid FROM ".tablename("account_wechats") ,array(),'uniacid');
			$user =pdo_fetchall("SELECT username,uid FROM ".tablename("users"),array(),'uid');
			$pager = pagination($total, $pindex, $psize);
			template('member/record');	
}
if ($do == 'chongzhi') {
			checklogin();
			$uid=$_W['uid'];
			if($_W['isfounder']){
				$list = pdo_fetchall("SELECT * FROM ".tablename("uni_payorder")." ORDER BY order_time LIMIT ". ($pindex - 1) * $psize . ',' . $psize);
				$total   = pdo_fetchcolumn("SELECT * FROM ".tablename("uni_payorder"));

				}
			else{
				$list = pdo_fetchall("SELECT * FROM ".tablename("uni_payorder")." WHERE uid=:uid ORDER BY order_time LIMIT ". ($pindex - 1) * $psize . ',' . $psize,array(":uid"=>$_W["uid"]));
				$total   = pdo_fetchcolumn("SELECT * FROM ".tablename("uni_payorder")." WHERE uid=:uid ",array(":uid"=>$_W["uid"]));
				}
			$pager = pagination($total, $pindex, $psize);
			$user =pdo_fetchall("SELECT username,uid FROM ".tablename("users"),array(),'uid');
			$pager = pagination($total, $pindex, $psize);
			template('member/record');	
}

	