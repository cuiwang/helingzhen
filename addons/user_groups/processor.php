<?php
/*
 * @copyright	Copyright (c) 2015 http://www.jakehu.me All rights reserved.
 * @license	    http://www.apache.org/licenses/LICENSE-2.0
 * @link	    http://www.jakehu.me
 * @author	    jakehu <jakehu1991@gmail.com>
 */

//----------------------------------
// 用户会员组管理模块处理程序
//----------------------------------
//
defined('IN_IA') or exit('Access Denied');

class User_groupsModuleProcessor extends WeModuleProcessor {

	public function respond() {

		global $_W, $_GPC;

		$content = $this->message['content'];

		if(!$this->inContext) {	

			$user_info = mc_fetch($_W['openid']);
			$groups = $this->getGroups($uniacid = $_W['uniacid'] , $groupid = 0);
		
			$reply .= "您好，请您输入下列序号选择服务：\n";
			$reply .= "[1] 查询系统所有会员组\n";
			$reply .= "[2] 查询自己所在会员组\n";
			$reply .= "[3] 查询会员卡剩余使用次数\n";

			if ($groups) {
				$reply .= "\n您好，你可以通过输入以下序列号修改自己所在会员组：\n";
				foreach ($groups as $key => $value) {
					if ($user_info['groupid'] != $value['groupid']) {

						$reply .= '['.($key+5).'] '.$value['title']."\n";

					}
				}
			}

			$reply .= "\n退出请输“0”";
			$this->beginContext(600); //10分钟释放资源
		} else {

			if ($content > 4) {
				$key = $content-5;
				$user_info = mc_fetch($_W['openid']);
				$groups = $this->getGroups($uniacid = $_W['uniacid'] , $groupid = 0);
				$data = $groups[$key];

				if ($user_info['credit1'] < $data['credit']) {

					$reply = "亲，您的积分为[".$user_info['credit1']."]小于".$data['title'].'积分['.$data['credit'].'],不能变更';
				} else {

					load()->model('mc');
					$result = mc_update($user_info['uid'], array('groupid' => $data['groupid']));

					if ($result) {
						$reply = "用户组成功变更为【".$data['title']."】！";
					} else {
						$reply = "用户组变更失败！";
					}	
				}				
			} else {
				switch ($content) {
					case 1:
						$groups = $this->getGroups($uniacid = $_W['uniacid'] , $groupid = 0);					

						$reply .="您好，系统所有会员组如下：\n";
						foreach ($groups as $key => $value) {
							$reply .= ($key+1).'、 '.$value['title']."\n";
						}
						$reply .="\n退出请输“0”";
						break;
					
					case 2:
						$user_info = mc_fetch($_W['openid']);
						$groups = $this->getGroups($uniacid = 0 , $groupid = $user_info['groupid']);

						$reply ="您好，您所在的会员组为：\n【 ".$groups['title']." 】\n\n退出请输“0”";
						break;

					case 3:
						$user_info = mc_fetch($_W['openid']);

						$sql = 'SELECT `nums` FROM ' . tablename('mc_card_members') . ' WHERE `uid` = :uid';
						$params = array(':uid' => $user_info['uid']);
						$members = pdo_fetch($sql, $params);

						if ($members) {
							$reply ="您好，您会员卡剩余次数为【 ".$members['nums']." 】次！\n\n退出请输“0”";
						} else {
							$reply ="您好，您目前还未领取过会员卡！\n\n退出请输“0”";
						}			
						break;

					case 0:
						$reply = "您好，您已成功退出服务！";
						$this->endContext();
						break;
				}	
			}
		}
		return $this->respText($reply);
	}

	/**
	 * 获取当前公众号所有的会员分组 / 获取当前用户所在分组
	 * @param  integer $uniacid 公众号ID
	 * @param  integer $groupid 用户groupid
	 * @return [array]          分组数组
	 */
	public function getGroups($uniacid = 0 , $groupid = 0)
	{	
		if ($uniacid != 0) {

			$sql = 'SELECT `groupid` , `title` , `credit` FROM ' . tablename('mc_groups') . ' WHERE `uniacid` = :uniacid';
			$params = array(':uniacid' => $uniacid);
			$groups = pdo_fetchall($sql, $params);
		} else if($groupid != 0){

			$sql = 'SELECT `groupid` , `title` , `credit` FROM ' . tablename('mc_groups') . ' WHERE `groupid` = :groupid';
			$params = array(':groupid' => $groupid);
			$groups = pdo_fetch($sql, $params);
		}
		return $groups;
	}

}