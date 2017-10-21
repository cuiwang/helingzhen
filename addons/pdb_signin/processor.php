<?php
/**
 * 我要签到模块处理程序
 *
 * 请遵循开源协议，本模块源码允许二次修改和开发，但必须注明作者和出处，如不遵守，我们将保留追求的权利。
 * @author PHPDB
 * @url http://www.phpdb.net/
 */
defined('IN_IA') or exit('Access Denied');

class Pdb_signinModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W,$_GPC;
		
		// 接收到关键词
		$content = $this->message['content'];
		
		//根据关键词找出是哪个活动：
		$sql = "SELECT a.* FROM " . tablename('pdb_signin') . " as a ".
				" left join ". tablename('rule_keyword') . " as rl " .
				" on a.rid = rl.rid ".
				" WHERE a.uniacid = '{$_W['uniacid']}' and a.status = 1
				and rl.content like '{$content}' and module like 'pdb_signin' ";
		$signin = pdo_fetch($sql);
		// print_r($signin);exit;
		
		if (!$signin) return ;
		
		//读取粉丝的身份：
		$fans = mc_fansinfo($this->message['from']);
		$uid = $fans['uid'];
		// print_r($fans);exit;
		$now = date("Y-m-d");
		
		//广告内容：
		$ad_content = '';
		if ($signin['ad_content']){
			$ad_content = "\r\n".$signin['ad_content'];
		}
		// echo $ad_content;exit;

		// 检查当天是否已经签到了
		$sql = "select count(id) from ".tablename('pdb_signin_log').
				" where uniacid = '{$_W['uniacid']}' and signin_id = '{$signin['id']}'
				and (fans_id = '{$this->message['from']}' or uid = '{$uid}' )
				and date(log_time) = '{$now}'
				limit 1";
				// echo $sql;exit;
		$today_count = pdo_fetchcolumn($sql);
		// echo $signin['times_perday'];exit;
		if ($today_count>=$signin['times_perday'] && $signin['times_perday'] > 0){
			$msg = $signin['repeat_message'].$ad_content;
			return $this->respText($msg);
		}
		// echo $signin['times_perday'];exit;
		// 检查签到是否在活动范围内=》活动结束或者活动未开始
		if (!$signin['is_longterm']){
			
			//未开始：
			if ($now < $signin['start_time']){
				$msg = $signin['nostart_message'].$ad_content;
				return $this->respText($msg);
			}
			//已经结束；
			if ($now > $signin['end_time']){
				$msg = $signin['finished_message'].$ad_content;
				return $this->respText($msg);
			}
		}
		

		// 如果没有签到，增加签到的积分
		$is_ok = 0;
		//查询该用户签到次数：
		$sql = "select count(id) from ".tablename('pdb_signin_log').
				" where uniacid = '{$_W['uniacid']}' 
				and (fans_id = '{$this->message['from']}' or uid = '{$uid}' ) limit 1";
		$sign_count = pdo_fetchcolumn($sql);
		// echo $sign_count;exit;
		if ($sign_count == 0){
			//首次签到：
			$credit = $signin['credit_first'];
			$log = '通过“我要签到”首次签到赚取的积分。';
		}else{
			$credit = $signin['credit_pertime'];
			$log = '通过“我要签到”每天签到赚取的积分。';
		}
		
		//更新会员的积分：
		load()->model('mc');
		// echo $uid. '=>' . $credit;exit;
		
		$new_credit = 0;
		
		
		$mc_credit = mc_credit_update($uid,'credit1',$credit,array(1,$log));
		// print_r($mc_credit);exit;
		if ($mc_credit){
			$new_credit += $credit;
			
			//签到成功，记录日志：
			$data = array();
			$data['uniacid'] = $_W['uniacid'];
			$data['signin_id'] = $signin['id'];
			$data['fans_id'] = $this->message['from'];
			$data['uid'] = $uid;
			$data['credit'] = $credit;
			$data['note'] = $log;
			$data['log_time'] = date("Y-m-d H:i:s");
			// print_r($data);exit;
			pdo_insert('pdb_signin_log',$data);
			$sign_count ++;//签到次数加1；
			
			$is_ok = 1;
		}

		// echo $sign_count;exit;
		// 检查是否达到连续签到多少天的条件，如果是奖励积分
		// if ($sign_count >= $signin['times_total'] && $signin['times_total'] > 0 && $signin['credit_total']>0){
		if ($sign_count == $signin['times_total'] && $signin['times_total'] > 0 && $signin['credit_total']>0){
			$log = "通过“我要签到”累计签到{$signin['times_total']}次获取的积分。";
			$mc_credit = mc_credit_update($uid,'credit1',$signin['credit_total'],array(1,$log));
			if ($mc_credit){
				$new_credit += $signin['credit_total'];
			}
		}

		if ($is_ok){
			// 返回文字内容；（签到成功的提示+广告内容）
			$credit1 = mc_credit_fetch($uid,array('credit1'));
			$credit1 = $credit1['credit1'];
			// print_r($credit1['credit1']);exit;
			
			$search = array();
			$replace = array();
			$search[] = '#新增积分#';
			$search[] = '#总积分#';
			
			$replace[]=doubleval($new_credit);
			$replace[]=doubleval($credit1);
			
			$signin['notify_message'] = str_replace($search,$replace,$signin['notify_message']);//替换积分变量
			$msg = $signin['notify_message'].$ad_content;
			
			return $this->respText($msg);
		}else{
			$msg = '签到失败，失败原因：程序故障，请联系客服！';
			return $this->respText($msg);
		}
		
		
	}
}