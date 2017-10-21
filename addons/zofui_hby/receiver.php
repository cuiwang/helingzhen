<?php
/**
 * 红包雨模块订阅器
 *
 * @author 众惠科技
 * @url http://bbs.we7.cc/
 */

defined('IN_IA') or exit('Access Denied');
define('HBY_ROOT', IA_ROOT . '/addons/zofui_hby');
class Zofui_hbyModuleReceiver extends WeModuleReceiver {
	public function receive() {
		global $_W;
 		//活动信息
		$asql = "SELECT * FROM " . tablename('zofui_hby_activity') . " WHERE `uniacid` = :uniacid ORDER BY `id` DESC";
		$actinfo = pdo_fetch($asql , array(
			':uniacid' => $_W['uniacid']			
		));	 
		
		$mess = $this -> message;
		
 		if($mess['event'] == 'subscribe'){	
			 			
			
			$sql = "SELECT openid FROM " . tablename('zofui_hby_user') . " WHERE `uniacid`=:uniacid AND `openid`=:openid AND `actid`=:actid ";
			$uinfo = pdo_fetch($sql, array(':uniacid'=>$_W['uniacid'], ':openid'=>$mess['from'], ':actid'=>$actinfo['id']));
			if(intval($mess['scene']) > 0){
				if(!$uinfo){
					$newuser = array();
					$newuser['uniacid'] = $_W['uniacid'];
					$newuser['actid'] = $actinfo['id'];				
					$newuser['openid'] = $mess['from'];
					$newuser['parentid'] = $mess['scene'];
					$newuser['times'] = $this->module['config']['times'];					
					$newuser['subscribe'] = 1;
					$newuser['time'] = time();
					pdo_insert('zofui_hby_user', $newuser);
					$newid = pdo_insertid();
					if($this->module['config']['givetimes']>0){
						$usersql = "SELECT * FROM " . tablename('zofui_hby_user') . " WHERE `uniacid`=:uniacid AND `id`=:id AND `actid`=:actid ";
						$usersinfo = pdo_fetch($usersql,array(':uniacid'=>$_W['uniacid'],':id'=>$mess['scene'], ':actid'=>$actinfo['id']));
						pdo_update("zofui_hby_user", array('times'=>$usersinfo['times']+$this->module['config']['givetimes']), array('id' => $mess['scene']));
					}
				}
			}
			if($uinfo['subscribe'] == 0){
				pdo_update('zofui_hby_user', array('subscribe' => 1), array('openid' => $mess['from'],'uniacid' => $_W['uniacid'],'actid'=>$actinfo['id']));
			}
			
			
		}
		
		if($mess['event'] == 'unsubscribe'){
			$sql = "SELECT openid FROM " . tablename('zofui_hby_user') . " WHERE `uniacid`=:uniacid AND `openid`=:openid AND `actid`=:actid ";
			$uinfo = pdo_fetch($sql, array(':uniacid'=>$_W['uniacid'], ':openid'=>$mess['from'],':actid'=>$actinfo['id']));
			if($uinfo){
				pdo_update('zofui_hby_user', array('subscribe' => 0), array('openid' => $mess['from'],'uniacid' => $_W['uniacid'],'actid'=>$actinfo['id']));
			}
		}

		
	}
}