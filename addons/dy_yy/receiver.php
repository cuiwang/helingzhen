<?php
/**
 * 啦啦啦模块订阅器
 *
 * @author 
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Cc_listModuleReceiver extends WeModuleReceiver {
	public function receive() {
		global $_W,$_GPC;
		//openid
		$fromuser = $this->message['from'];

		if ($this->message['msgtype'] == 'event') {
			if ($this->message['event'] == 'subscribe' && !empty($this->message['scene'])) {
				//验证操作
				$member = pdo_fetch("SELECT * FROM ".tablename('cc_ewm')." WHERE uniacid=:uniacid AND uid=:uid",array(':uniacid'=>$_W['uniacid'],':uid'=>$this->message['scene']));
				if ($member['yid']==$fromuser) {
					//自己邀请自己停止
					return '';
				}
				//检查用户之前是否关注过
				$fromuser2 = pdo_fetch("SELECT * FROM ".tablename('mc_mapping_fans')." WHERE uniacid=:uniacid AND openid=:openid",array(':uniacid'=>$_W['uniacid'],':openid'=>$fromuser));
				if($fromuser2){
					//之前关注过则不增加佣金。
					return '';
				}

				//通过验证后操作
				if ($member) {
					//系统配置
					$set = pdo_fetch("SELECT * FROM ".tablename('cc_set')." WHERE uniacid = :uniacid LIMIT 1", array(':uniacid' => $_W['uniacid']));

					//load()->model('mc');
					//mc_credit_update($this->message['scene'], 'credit2',$set['money'], array(0, '邀请新用户'));
					
					$insert = array(
							'uniacid' => $_W['uniacid'],
							'bid' => $fromuser,
							'yid' => $this->message['scene'],
							'money' =>$set['money'],
							'createtime' => time(),
							'state' => 0
							);
					pdo_insert('cc_jl', $insert);
				}

			}
		}
		return '';
	}
}