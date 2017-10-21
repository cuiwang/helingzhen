<?php
/**
 * 自动分组图文回复处理类
 *
 * @author FantasyMoons Team
 * @url http://www.fmoons.com
 */
defined('IN_IA') or exit('Access Denied');

class Fm_autogroupModuleProcessor extends WeModuleProcessor {
	
	
	public function respond() {
		global $_W;
		load()->func('communication');
		$rid = $this->rule;
		$uniacid = $_W['uniacid'];
		//$uniacid = $_W['uniacid'];
				
		
		$openid = $this->message['from'];
		$group = $this->message['content'];
		$member = pdo_fetch("SELECT * FROM ".tablename('fm_autogroup_members')." WHERE from_user = '{$openid}' AND uniacid = '{$uniacid}' LIMIT 1");
		$message = '';
			//$atype = 'weixin';
			//$account_token = "account_{$atype}_token";
			//$account_code = "account_weixin_code";
			//print_r($_W);
			load()->classs('weixin.account');
			$accObj= WeixinAccount::create($uniacid);
			$token = $accObj->fetch_token();
			$urls = sprintf("https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN", $token,$openid);
			$contents = ihttp_get($urls);
			
			//print_r($contents);
			$dats = $contents['content'];
			$re = @json_decode($dats, true);
		
		if (!empty($group)) {
			//$token = $_W['account']['access_token']['token'];
			
			//if (empty($member['nickname'])) {				
				$nickname = $re['nickname'];
			//}elseif ($member['nickname'] != $re['nickname']) {
			//	$nickname = $re['nickname'];
			//} else {
			//	$nickname = $member['nickname'];
			//}
			//if (empty($member['avatar'])) {				
				$avatar = $re['headimgurl'];
			//}elseif ($member['avatar'] != $re['headimgurl']) {
			//	$avatar = $re['headimgurl'];
			//} else {
			//	$avatar = $member['avatar'];
			//}
			
			
			$url = "https://api.weixin.qq.com/cgi-bin/groups/get?access_token={$token}";
			$content = ihttp_get($url);			
			
			if ($content['code'] == '200') {
				$jsoninfo = json_decode($content['content'], true);
				
				//print_r($jsoninfo['groups']);
				$groups = $jsoninfo['groups'];
				//print_r($groups['0']['name']);
				for ($i = 0; $i <= 100 ; $i++) {
					if ($groups[$i]['name'] == $group) {
						$gid = $groups[$i]['id'];
						$group = $groups[$i]['name'];
						$count = $groups[$i]['count'];
						$dat = '{"openid":"'.$openid.'","to_groupid":'.$gid.'}';
						$yurl = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token={$token}";
						$ycontent = ihttp_post($yurl,$dat);
						//print_r($ycontent);		
						if ($ycontent['code'] == '200') {
							$yd = json_decode($ycontent['content'], true);
							
							if ($yd['errmsg'] == 'ok') {
								if (!empty($member)) {
									pdo_update('fm_autogroup_members', array(
										'lastupdate' => TIMESTAMP,
										'gid' => $gid,
										'gname' => $group,
										'nickname' => $nickname,
										'avatar' => $avatar,
										'lastupdate' => TIMESTAMP,
										'follow' => '1',
										'fscount' => $member['fscount'] + 1,
									), array(
										'from_user' => $openid,
										'uniacid' => $uniacid,
									));
								} else {
									$data = array(
										'from_user' => $openid,
										'rid' => $this->rule,
										'uniacid' => $uniacid,
										'gid' => $gid,
										'gname' => $group,
										'fscount' => '1',
										'follow' => '1',
										'nickname' => $nickname,
										'avatar' => $avatar,
										'lastupdate' => TIMESTAMP,
										'isblacklist' => 0,
									);
									pdo_insert('fm_autogroup_members', $data);
								}
								$ggroup = $this->getGroup($gid);
								$ydcount = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('fm_autogroup_members')." WHERE gid = :gid and uniacid = :uniacid ", array(':gid' => $gid,':uniacid' => $uniacid));
								if (!empty($ggroup)) {
									
									pdo_update('fm_autogroup_group', array(
										'gname' => $group,
										'count' => $count,
										'ydcount' => $ydcount,
										'fscount' => $ggroup['fscount'] + 1,
									), array(
										'gid' => $gid,
										'uniacid' => $uniacid,
									));
								} else {
									$gdata = array(
										'rid' => $this->rule,
										'uniacid' => $uniacid,
										'gid' => $gid,
										'gname' => $group,
										'count' => $count,
										'ydcount' => $ydcount,
										'fscount' => '1',
										'createtime' => TIMESTAMP,
									);
									pdo_insert('fm_autogroup_group', $gdata);
								}
								
								$glog = array(
										'from_user' => $openid,
										'rid' => $this->rule,
										'uniacid' => $uniacid,
										'gid' => $gid,
										'gname' => $group,
										'content' => $group,
										'fscount' => '1',
										'nickname' => $nickname,
										'createtime' => TIMESTAMP,
									);
									pdo_insert('fm_autogroup_log', $glog);
								
								
								if (!empty($member['nickname'])) {
									$nickname = '亲爱的用户：'.$member['nickname'].' 您好！';
								} else {
									$nickname = '亲爱的用户：匿名 您好！';
								}
$message .= $nickname.'
您当前要进入的分组是：'.$group.' 组
该组共有用户：'.$count.'人。
我们已经成功的将您移动到'.$group.'组,谢谢您的合作！
'.$_W['account']['name'];
								
							} else {
								$message .= '您当前要进入的分组是：'.$group.' 组,由于一些问题，将您移动到'.$group.' 组时出错,请联系您的负责人查看问题！';
								
							}
							break;
						}						
					}			
				}//for
				
				if ($group != $groups[$i]['name']) {
							$ggroup = $this->getGroupn($group);
							$gid = $ggroup['id'];
							$ydcount = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('fm_autogroup_members')." WHERE gid = :gid and uniacid = :uniacid ", array(':gid' => $gid,':uniacid' => $uniacid));
							if (!empty($ggroup)) {
								
								pdo_update('fm_autogroup_group', array(
									'count' => '无分组',
									'wcount' => $ydcount,
									'ydcount' => $ydcount,
									'fscount' => $ggroup['fscount'] + 1,
									'gid' => $gid,
								), array(
									'id' => $gid,
									'gname' => $group,
									'uniacid' => $uniacid,
								));
							} else {
								$gdata = array(
									'rid' => $this->rule,
									'uniacid' => $uniacid,
									'gid' => $gid,
									'gname' => $group,
									'count' => '无分组',
									'wcount' => $ydcount,
									'fscount' => '1',
									'ydcount' => $ydcount,
									'createtime' => TIMESTAMP,
								);
								pdo_insert('fm_autogroup_group', $gdata);
								$gid = pdo_insertid();
							}
							
						
							if (!empty($member)) {
								pdo_update('fm_autogroup_members', array(
									'lastupdate' => TIMESTAMP,
									'gid' => $gid,
									'gname' => $group,
									'nickname' => $nickname,
									'avatar' => $avatar,
									'follow' => '1',
									'lastupdate' => TIMESTAMP,
									//'fscount' => $member['fscount'] + 1,
								), array(
									'from_user' => $openid,
									'uniacid' => $uniacid,
								));
							} else {
								$data = array(
									'from_user' => $openid,
									'rid' => $this->rule,
									'uniacid' => $uniacid,
									'gid' => $gid,
									'gname' => $group,
									'fscount' => '1',
									'follow' => '1',
									'nickname' => $nickname,
									'avatar' => $avatar,
									'lastupdate' => TIMESTAMP,
									'isblacklist' => 0,
								);
								pdo_insert('fm_autogroup_members', $data);
							}
							//记录
							$glog = array(
								'from_user' => $openid,
								'rid' => $this->rule,
								'uniacid' => $uniacid,
								'gid' => $gid,
								'gname' => $group,
								'content' => $group,
								'fscount' => '1',
								'nickname' => $nickname,
								'createtime' => TIMESTAMP,
							);
							pdo_insert('fm_autogroup_log', $glog);
							
							
							if (!empty($member['nickname'])) {
									$nickname = '亲爱的用户：'.$member['nickname'].' 您好！';
								} else {
									$nickname = '亲爱的用户：匿名 您好！';
								}
$message .= $nickname.'
您当前要进入的分组是：'.$group.' 组
该组共有用户：'.$ydcount.'人。
我们已经成功的将您移动到'.$group.'组,谢谢您的合作！
'.$_W['account']['name'];
							
							
					}
			}
		}
			//
			
			
			//var_dump($content);
			//$c = print_r($content);
			
			
		
		//$this->beginContext();
		return $this->respText($message);
	}
	
	
	
	private function getGroup($gid) {
		global $_W;
		$rid = $this->rule;
		$uniacid = $_W['uniacid'];
		$group = pdo_fetch("SELECT * FROM ".tablename('fm_autogroup_group')." WHERE gid = '{$gid}' AND uniacid = '$uniacid' LIMIT 1");
		
		return $group;
	}
	private function getGroupn($gname) {
		global $_W;
		$rid = $this->rule;
		$uniacid = $_W['uniacid'];
		$group = pdo_fetch("SELECT * FROM ".tablename('fm_autogroup_group')." WHERE gname = '{$gname}' AND uniacid = '$uniacid' LIMIT 1");
		
		return $group;
	}
	
	public function hookBefore() {
		global $_W, $engine;
	}
}
