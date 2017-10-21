<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
include_once 'common/common.inc.php';
$dos = array('member', 'record', 'buypackage', 'chongzhi');
$do = in_array($do, $dos) ? $do : 'member';
global $_W,$_GPC;
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
		template('shop/center');
}
if ($do == 'record') {
		$_W['page']['title'] = "消费记录";
		checklogin();
		$uid=$_W['uid'];
		if ($_GPC['uid']){
			$uid = intval($_GPC['uid']);
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize  = 20;
			if($_W['isfounder']){
				$list = pdo_fetchall("SELECT * FROM ".tablename("users_credits_record")." ORDER BY createtime LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
				$total   = pdo_fetchcolumn("SELECT * FROM ".tablename("users_credits_record"));
			}else{
				$list = pdo_fetchall("SELECT * FROM ".tablename("users_credits_record")." WHERE uid=:uid ORDER BY createtime LIMIT " . ($pindex - 1) * $psize . ',' . $psize,array(":uid"=>$uid));
				$total   = pdo_fetchcolumn("SELECT * FROM ".tablename("users_credits_record")." WHERE uid=:uid " ,array(":uid"=>$uid));
			}
			$uni = pdo_fetchall("SELECT name,uniacid FROM ".tablename("account_wechats") ,array(),'uniacid');
			$user = pdo_fetchall("SELECT username,uid FROM ".tablename("users"),array(),'uid');
			$pager = pagination($total, $pindex, $psize);
			template('shop/record');	
}
if ($do == 'chongzhi') {
			$_W['page']['title'] = "充值记录";
			checklogin();
			$uid=$_W['uid'];
			if ($_GPC['uid']){
				$uid = intval($_GPC['uid']);
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize  = 20;
			if($_W['isfounder']){
				$list = pdo_fetchall("SELECT * FROM ".tablename("uni_payorder")." WHERE uid <> :uid ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize ,array(":uid"=>1));
				$total = pdo_fetchcolumn("SELECT * FROM ".tablename("uni_payorder")." WHERE uid <> :uid ",array(":uid"=>1));
			}else{
				$list  = pdo_fetchall("SELECT * FROM ".tablename("uni_payorder")." WHERE uid=:uid ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize ,array(":uid"=>$uid));
				$total = pdo_fetchcolumn("SELECT * FROM ".tablename("uni_payorder")." WHERE uid=:uid ",array(":uid"=>$uid));
			}
			$user = pdo_fetchall("SELECT username,uid FROM ".tablename("users"),array(),'uid');
			$pager = pagination($total, $pindex, $psize);
			template('shop/record');	
}

	