<?php
defined('IN_IA') or exit('Access Denied');
load()->model("account");
load()->model("mc");
load()->func('cache');
load()->func('communication');
function kim_get_uni_group($id) 
{
	return pdo_fetch("SELECT * FROM ".tablename('users_group')." WHERE id=:id",array(":id"=>$id));
}
function getUserGroupAccount() 
{
	global $_W;
	$user = pdo_fetch('SELECT groupid, endtime FROM '.tablename('users').' WHERE `uid` = :uid LIMIT 1',array(':uid' => $_W['uid']));
	$groupid = $user['groupid'];
	$group = kim_get_uni_group($groupid);
	$_W['user']['level'] = $group["name"];
	return uni_groups(array($_W['account']['groupid']));
}
function getAllRecords($table, $params=array(),$uid=0)
{
	global $_W,$_GPC;
	unset($sql,$sql_params);
	if($uid <= 0) $uid = $_W["user"]["uid"];
	$page = max(1, intval($_GPC['page']));
	$page_size = 20;
	$now_page = ($page - 1) * $page_size;
	$sql = "SELECT %s FROM ".tablename($table)." WHERE uniacid=:uniacid AND uid=:uid";
	$sql_params= array(":uniacid"=>$_W["uniacid"],":uid"=>$uid);
	foreach($params as $k => $v) 
	{
		if(empty($k) || empty($v)) continue;
		$sql.=(" AND `".$k."`".$v["op"].":".$k);
		$sql_params[":".$k] = $v["val"];
	}
	$list = pdo_fetchall(sprintf($sql,"*")." LIMIT $now_page,$page_size", $sql_params);
	$total = pdo_fetchcolumn(sprintf($sql,"COUNT(*)"), $sql_params);
	$pager = pagination($total, $page, $page_size);
	return array($list, $pager);
}
function user_credits_update($uid, $credittype, $creditval = 0, $log = array()) 
{
	global $_W;
	$credittype = trim($credittype);
	$credittypes = array('credit1','credit2','credit3','credit4','credit5');
	if(!in_array($credittype, $credittypes))
	{
		return error('-1',"指定的用户积分类型 “{$credittype}
	”不存在.");
}
$creditval = floatval($creditval);
if(empty($creditval)) 
{
	return false;
}
$value = pdo_fetchcolumn("SELECT $credittype FROM ".tablename('users')." WHERE `uid` = :uid", array(':uid' => $uid));
$groupid = pdo_fetchcolumn("SELECT groupid FROM ".tablename('users')." WHERE `uid` = :uid", array(':uid' => $uid));
$discount = pdo_fetchcolumn("SELECT discount FROM ".tablename('users_group')." WHERE `id` = :uid", array(':uid' => $groupid));
if($creditval > 0 || ($value + $creditval >= 0)) 
{
	if($discount<1){
	pdo_update('users',array($credittype => $value + $creditval), array('uid' => $uid));
	}else{
		pdo_update('users',array($credittype => $value + ($creditval*$discount)), array('uid' => $uid));
		}
}
else 
{
	return error('-1',"积分类型为“{$credittype}
”的积分不够，无法操作。");
}
if(empty($log) || !is_array($log)) 
{
$log = array($uid, '未记录');
}
$data = array( 'uid' => $uid, 'credittype' => $credittype, 'uniacid' => $_W['uniacid'], 'num' => $creditval, 'createtime' => TIMESTAMP, 'operator' => intval($log[0]), 'remark' => $log[1], );
pdo_insert('users_credits_record', $data);
return pdo_insertid();
}

function user_credits_update1($uid,$pid, $credittype, $creditval = 0, $log = array()) 
{
	global $_W;
	$credittype = trim($credittype);
	$credittypes = array('credit1','credit2','credit3','credit4','credit5');
$creditval = floatval($creditval);
if(empty($creditval)) 
{
	return 0;//金额有误
}
$value = pdo_fetchcolumn("SELECT ".$credittype." FROM ".tablename('users')." WHERE `uid` = :uid", array(':uid' => $uid));
$endtime = pdo_fetchcolumn("SELECT endtime FROM ".tablename('users')." WHERE `uid` = :uid", array(':uid' => $uid));
$b_time = strtotime('+1 Year',$endtime);
if( ($value >= $creditval)) 
{
	pdo_update('users',array($credittype => $value - $creditval,'groupid' => $pid,'endtime' => $b_time), array('uid' => $uid));
}else {
	return 0;//余额不足
}
if(empty($log) || !is_array($log)) 
{
$log = array($uid, '未记录');
}
$data = array( 'uid' => $uid, 'credittype' => $credittype, 'uniacid' => $_W['uniacid'], 'num' => -$creditval, 'createtime' => TIMESTAMP, 'operator' => intval($log[0]), 'remark' => $log[1], );
pdo_insert('users_credits_record', $data);
return pdo_insertid();
}


function get_settings()
{
$value = pdo_fetchcolumn("SELECT value FROM ".tablename("core_settings")." WHERE `key`=:key",array(":key"=>"member"));
if(empty($value)) 
{
return array();
}
return @iunserializer($value);
}



function get_AllPackage()
{
return pdo_fetchall("SELECT * FROM ".tablename("uni_group"));
}
function common_group_check() 
{
global $_W;
$uniacid = $_W['uniacid'];
$settings = uni_setting($uniacid, array('groupdata'));
$groupdata = $settings['groupdata'] ? $settings['groupdata'] : array();
if($groupdata['isexpire'] == 1) 
{
$settings = get_settings();
$account = uni_fetch();
$group = kim_get_uni_group($account['groupid']);
$user = pdo_fetch("SELECT U.* FROM ".tablename("uni_account_users")." AS A LEFT JOIN ".tablename("users")." AS U ON A.uid=U.uid WHERE A.uniacid=:uniacid AND A.role='manager'",array(":uniacid"=>$uniacid));
if($groupdata['endtime'] < TIMESTAMP) 
{
	if($user["credit2"] >= $group["price"] && $settings["is_auto"] == 1) 
	{
		$result = buy_package($user, $group["id"]);
		if(is_error($result)) 
		{
			return $result;
		}
		return error(0,"自动续费成功.");
	}
	else
	{
		$over_group = !empty($settings["over_group"]) ? intval($settings["over_group"]) : 0;
		pdo_update('uni_account', array('groupid' => $over_group), array('uniacid' =>$uniacid));
		pdo_update('uni_settings', array('groupdata' => iserializer(array('isexpire' => 0, 'endtime' => 0, 'oldgroupid' => $group['id'],'is_auto' => 1))), array('uniacid' => $uniacid));
		$_W['account']['groupid'] = $over_group;
		load()->model('module');
		module_build_privileges();
		return error(0,"自动续费失败，变成配置过期套餐.");
	}
}
$overTime = intval($groupdata['endtime'])-TIMESTAMP;
if(!empty($settings["tx_date"]) && intval($settings["tx_date"]) > 0 && is_email_tx($overTime, intval($settings["tx_date"]))) 
{
	$email = pdo_fetchcolumn("SELECT P.email FROM ".tablename("uni_account_users")." AS A LEFT JOIN ".tablename("users_profile")." AS P ON A.uid=P.uid WHERE A.uniacid=:uniacid AND A.role='manager'",array(":uniacid"=>$uniacid));
	if(!empty($email)) 
	{
		$email_body = str_replace('&lt;',"<",$settings["tx_email"]);
		$email_body = str_replace('&gt;',">",$email_body);
		$email_body = str_replace('#package#',$group['name'],$email_body);
		$email_body = str_replace('#day#',$groupdata['endtime'],$email_body);
		load()->func('communication');
		$send = ihttp_email($email,"套餐到期提醒-系统邮件,请勿回复.", $email_body , true);
		if(is_error($send)) 
		{
			return $send;
		}
		cache_write('members_email_tx'.$_W["uniacid"], array("time" => date("Y-m-d", TIMESTAMP)));
		return error(0,"套餐到期提醒,成功");
	}
	return error(0,"提醒邮箱未设定");
}
return error(0,"检查成功");
}
return error(-1,"操作失败");
}
function buy_package($user, $package_id, $total= 1) 
{
if(empty($user) || empty($user["uid"])) return error(-1,"用户不存在");
if($total <= 0) return error(-1,"购买数必须大于1");
if(empty($user["credit2"]) || doubleval($user["credit2"]) < 0) return error(-1,"用户余额为0无法购买套餐.");
$group = kim_get_uni_group($package_id);
if(empty($group)) return error(-1,"模块不存在.");
$price = doubleval($group["price"]);
if(intval($user['groupid']) > 0) list($price, $discount) = check_price($price, intval($user['groupid']));
if(doubleval($user["credit2"]) < $price*$total) return error(-1,"用户余额不足.");
$st = get_settings();
$day = 30;
if(intval($st[package_day]) > 0) $day = intval($st[package_day]);
$package_price = $price*$total;
$package_time = $total*$day*24*60*60;
load()->model("account");
$account = uni_fetch();
if(empty($account)) return error(-1,"公众号不存在.");
$settings = uni_setting($account["uniacid"], array('groupdata'));
$groupData = $settings['groupdata'] ? $settings['groupdata'] : array("endtime"=>TIMESTAMP);
$package_endTime = $package_time;
if($groupData["endtime"]-TIMESTAMP > 0) $package_endTime = ($groupData["endtime"]-TIMESTAMP)+$package_time;
$old_package = kim_get_uni_group($account["groupid"]);
try
{
pdo_begin();
$endtime = date("Y-m-d", TIMESTAMP+$package_endTime);
load()->model('user');
		$record = array();
		$record['uid'] = $user["uid"];
		$record['endtime'] = $endtime;
user_update($record);

$order_record = array( 
	"uniacid"=> $account["uniacid"],
	"uid" => $user["uid"],
	"package"=>$package_id,
	"buy_time"=>TIMESTAMP,
	"expiration_time"=>TIMESTAMP+$package_endTime
);
pdo_insert("users_packages",$order_record);
$record_id = pdo_insertid();
if($record_id <= 0) throw new Exception("保存记录失败");
//VIP时间同步


$groupData["endtime"] = $groupData["endtime"] < TIMESTAMP ? TIMESTAMP : $groupData["endtime"];
$old_over_time = date("Y-m-d",$groupData["endtime"]);
$new_over_time = date("Y-m-d",TIMESTAMP+$package_endTime);
$log = array(0,sprintf("自动续费： %s 套餐续费,续费前：%s 到期; 续费后：%s 到期",$group["name"], $old_over_time, $new_over_time));
if(intval($account["groupid"]) <> intval($package_id)) 
{
	$surplus_price = $old_package["price"]*round(($groupData["endtime"]-TIMESTAMP)/86400);
	$surplus_price = $surplus_price/$day;
	$surplus_time = round($surplus_price/($group["price"]))*$day;
	$package_endTime = ($surplus_time*24*60*60)+$package_time;
	$new_over_time = date("Y-m-d",TIMESTAMP+$package_endTime);
	$log_text = sprintf("套餐变更: &lt;p&gt;A、原套餐: %s , %s 到期&lt;/p&gt;&lt;p&gt;B、变更后: %s , %s 到期.&lt;/p&gt;",$old_package["name"],$old_over_time,$group["name"],$new_over_time);
	$log = array(0,$log_text);
	if(pdo_update('uni_account', array('groupid' => $package_id), array('uniacid' =>$account["uniacid"])) <=0) 
	{
		throw new Exception("更新套餐失败.");
	}
}
$new_groupdata = array('groupdata' => iserializer(array('isexpire' => 1, 'endtime' => TIMESTAMP+$package_endTime, 'oldgroupid' => $old_package['id'],'is_auto' => 1)));
if(pdo_update('uni_settings', $new_groupdata, array('uniacid' => $account["uniacid"])) <=0) 
{
	throw new Exception("更新套餐失败!");
}
$result = user_credits_update($user["uid"],"credit2",-$package_price, $log);
if(is_error($result)) 
{
	throw new Exception($result["message"]);
}
$_W['account']['groupid'] = $account["uniacid"];
load()->model('module');
module_build_privileges();
pdo_update("users_packages",array("record_id"=>$record_id,"status"=>1),array("id"=>$record_id));
pdo_commit();
return true;
}
catch (Exception $e) 
{
pdo_rollback();
return error(-1, $e->getMessage());
}
return error(-1,"错误操作.");
}
function get_surplus_price($price) 
{
global $_W;
$st = get_settings();
$day = 30;
if(intval($st[package_day]) > 0) $day = intval($st[package_day]);
$settings = uni_setting($_W["uniacid"], array('groupdata'));
$_price = $price*round(($settings["groupdata"]["endtime"]-TIMESTAMP)/86400);
return $_price/$day;
}
function get_surplus_price_time($surplus_price,$price) 
{
return round($surplus_price/($price))*30;
}
function is_email_tx($time,$day) 
{
global $_W;
if($time < 0) return false;
$temp = cache_read("members_email_tx".$_W["uniacid"]);
if($temp["time"] == date("Y-m-d", TIMESTAMP)) return false;
if($time <= $day*24*60*60) 
{
return true;
}
return false;
}
function check_price($price,$groupId=0) 
{
global $_W;
if($groupId<=0) $groupId = $_W["user"]["groupid"];
$group = pdo_fetch("SELECT id, name, discount FROM ".tablename('users_group')." WHERE id=:id",array(":id"=>$groupId));
if(doubleval($group["discount"]) <= 0) return array($price,0);
return array($price*($group["discount"]/10), $group["discount"]);
}
function ChangePackage() {
        global $_W,$_GPC;
        $_W["user"]["packages"] = getUserGroupAccount();
        if (empty($_W['isfounder'])) {
            $group = pdo_fetch("SELECT * FROM ".tablename('users_group')." WHERE id = '{$_W['user']['groupid']}'");
            $group_packages = (array)@iunserializer($group['package']);
            $user_packages = (array)@iunserializer($_W['user']['package']);
            $group_account = uni_groups(array_merge($user_packages,$group_packages));
        } else {
            $group_account = uni_groups();
        }

        $allow_group = array_keys($group_account);
        $allow_group[] = 0;
        if(!empty($_W['isfounder'])) {
            $allow_group[] = -1;
        }

        if($_W['ispost']) {
            $uniacid = intval($_W['uniacid']);
            $groupid = intval($_GPC['groupid']);

            $state = uni_permission($_W['uid'], $uniacid);
            if($state != 'founder' && $state != 'manager') {
                exit('illegal-uniacid');
            }

            if(!in_array($groupid, $allow_group)) {
                exit('illegal-group');
            } else {
                pdo_update('uni_account', array('groupid' => $groupid), array('uniacid' => $uniacid));
                if($groupid == 0) {
                    exit('基础服务');
                } elseif($groupid == -1) {
                    exit('所有服务');
                } else {
                    exit($group_account[$groupid]['name']);
                }
            }
            exit();
        }
}
function GetPayResult() {
        global $_W,$_GPC;
        $order_no = $_GPC["order_no"];
        $order = pdo_fetch("SELECT * FROM ".tablename("uni_payorder")." WHERE orderid=:orderid", array(":orderid"=>$order_no));
        if(empty($order)){
            message("订单不存在!",url("member/member"));
        }
        if($order["status"] <> 1) {
            message("订单待支付状态，如果支付成功请与客服联系!",url("member/member"));
        }
        if($order["status"] == 1) {
            message("订单支付成功!",url("member/member"));
            exit;
        }
}
$user = pdo_fetch("SELECT U.* FROM ".tablename("uni_account_users")." AS A LEFT JOIN ".tablename("users")." AS U ON A.uid=U.uid WHERE A.uniacid=:uniacid AND A.role='manager'",array(":uniacid"=>$_W["uniacid"]));
$_W["user"] = $user;
$setting = uni_setting($_W['uniacid'], array('notify','groupdata'));
$_W["user"]["packages"] = getUserGroupAccount();
$_W["user"]["account"] = uni_fetch($_W['uniacid']);
$user2 = pdo_fetch('SELECT * FROM '.tablename('users').' WHERE `uid` = :uid LIMIT 1',array(':uid' => $_W['uid']));
$_W["user"]["packages"]['endtime'] = $user2['endtime'];
$_W["user"]["credit2"] = $user2['credit2'];
$_W[user][username] = $_W['username'];
define ('BAIFUBAO_ROOT', "payment" . DIRECTORY_SEPARATOR . "baifubao");
?>