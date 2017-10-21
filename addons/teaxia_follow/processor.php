<?php
/**
 * 关注积分赠送模块处理程序
 *
 * @author 茶树虾
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Teaxia_followModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$uid = $_W['member']['uid'];
		$udo = pdo_fetch("SELECT * FROM ".tablename('teaxia_follow_log') . " WHERE `uid` = ".$uid." AND `uniacid` = ".$_W['uniacid']."");
		if(!empty($udo)){
			return $this->respText('您已经参加过该活动，请勿重复参加！');
		}else{
			$do = pdo_fetch("SELECT * FROM ".tablename('teaxia_follow') . " WHERE `uniacid` = ".$_W['uniacid']."");
			mc_credit_update($uid,$do['credits'],$do['num'],$log = array('1'=>'关注赠送'));
			$log_data = array(
				'uid' => $uid,
				'uniacid' => $_W['uniacid'],
			);
			pdo_insert('teaxia_follow_log', $log_data);//添加日志操作
			$credit = $list[$do['credits']]['title'];
			$str = $do['focus'];
			$str = str_replace('{name}',$_W['account']['name'],$str);
			$str = str_replace('{credits}',$credit,$str);
			$str = str_replace('{num}',$do['num'],$str);
			return $this->respText($str);
		}
	}
}