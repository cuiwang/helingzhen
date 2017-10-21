<?php
/**
 * 粉丝分组模块订阅器
 *
 * @author FantasyMoons Team
 * @url http://www.fmoons.com
 */


defined('IN_IA') or exit('Access Denied');

class Fm_autogroupModuleReceiver extends WeModuleReceiver {
	public function receive() {
		global $_W, $_GPC;
		load()->func('communication');
		$type = $this->message['type'];
		//$uniacid = $GLOBALS['_W']['uniacid'];
		$uniacid = $_W['uniacid'];
		
		//$rid = intval($this->params['rule']);		
		$rid = $this->rule;
		//print_r(iserializer($this->message));
		$openid = $this->message['fromusername'];
		
		
		load()->classs('weixin.account');
		$accObj= WeixinAccount::create($uniacid);
		$token = $accObj->fetch_token();

		$urls = sprintf("https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN", $token,$openid);
		$contents = ihttp_get($urls);
		$dats = $contents['content'];
		$re = @json_decode($dats, true);
		//print_r($this->message['type']);
		//
		file_put_contents(IA_ROOT.'/receive.txt', iserializer($type));
		//if ($message['event'] == 'unsubscribe') {
		if ($this->message['event'] == 'unsubscribe') {
			
			$member = pdo_fetch("SELECT * FROM ".tablename('fm_autogroup_members')." WHERE from_user = :from_user and uniacid = :uniacid LIMIT 1", array(':from_user' => $openid,':uniacid' => $uniacid));
			pdo_update('fm_autogroup_members', array(
				'lastupdate' => TIMESTAMP,
				'follow' => 0,
				'followtrue' => 1,
				'fscount' => $member['fscount'] + 1,
			), array(
				'from_user' => $openid,
				'uniacid' => $uniacid,
			));
		} elseif($this->message['event'] == 'subscribe') {
			
			//$token = $_W['account']['access_token']['token'];
			$group = $re['province'];
			$group = empty($group) ? '默认组' : $re['province'];
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
						$count = $groups[$i]['count'];
						
						/**pdo_update('fans', array(
							'follow' => 1,
							'nickname' => $re['nickname'],
							'gender' => $re['sex'],
							'groupid' => $gid,
							'residecity'=> $re['city'],
							'resideprovince' => $re['province'],
							'nationality' => $re['country'],
							'avatar' => $re['headimgurl']
							//'createtime' => TIMESTAMP,
						), array(
							'from_user' => $openid,
							'uniacid' => $uniacid
						));**/
						
						$dat = '{"openid":"'.$openid.'","to_groupid":'.$gid.'}';
						$yurl = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token={$token}";
						$ycontent = ihttp_post($yurl,$dat);
						//print_r($ycontent);		
						if ($ycontent['code'] == '200') {
							$yd = json_decode($ycontent['content'], true);
							
							if ($yd['errmsg'] == 'ok') {
								$member = pdo_fetch("SELECT * FROM ".tablename('fm_autogroup_members')." WHERE from_user = :from_user and uniacid = :uniacid LIMIT 1", array(':from_user' => $this->message['fromusername'],':uniacid' => $uniacid));
								
								if (!empty($member)) {
									pdo_update('fm_autogroup_members', array(
										'lastupdate' => TIMESTAMP,
										'gid' => $gid,
										'gname' =>  $group,
										'follow' => 1,
										'followtrue' => 0,
										'nickname' => $re['nickname'],
										'avatar' => $re['headimgurl'],
										'fscount' => $member['fscount'] + 1,
									), array(
										'from_user' => $openid,
										'uniacid' => $uniacid,
									));
								} else {
									$data = array(
										'from_user' => $openid,
										'uniacid' => $uniacid,
										'gid' => $gid,
										'gname' =>  $group,
										'rid' => $rid,
										'fscount' => '1',
										'follow' => 1,
										'followtrue' => 0,
										'nickname' => $re['nickname'],
										'avatar' => $re['headimgurl'],
										'lastupdate' => TIMESTAMP,
										'createtime' => TIMESTAMP,
									);
									pdo_insert('fm_autogroup_members', $data);
								}								
								$ydcount = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('fm_autogroup_members')." WHERE gid = :gid and uniacid = :uniacid ", array(':gid' => $gid,':uniacid' => $uniacid));
								//$gids = empty($gid) ? '0' : $gid;
								$ggroup = pdo_fetch("SELECT * FROM ".tablename('fm_autogroup_group')." WHERE gid = :gid and uniacid = :uniacid LIMIT 1", array(':gid' => $gid,':uniacid' => $uniacid));
								
									if (!empty($ggroup)) {
										
										pdo_update('fm_autogroup_group', array(
											'gname' => $group,
											'count' => $count,
											'rid' => $rid,
											'ydcount' => $ydcount,
											'fscount' => $ggroup['fscount'] + 1,
										), array(
											'gid' => $gid,
											'uniacid' => $uniacid,
										));
									} else {
										$gdata = array(
											'uniacid' => $uniacid,
											'gid' => $gid,
											'gname' =>  $group,
											'rid' => $rid,
											'fscount' => '1',
											'count' => $count,
											'ydcount' => $ydcount,
											'createtime' => TIMESTAMP,
										);
										pdo_insert('fm_autogroup_group', $gdata);
									}
								//记录
							$glog = array(
								'from_user' => $openid,
								'rid' => $this->rule,
								'uniacid' => $uniacid,
								'gid' => $gid,
								'gname' => $group,
								'content' => $group,
								'fscount' => 1,
								'nickname' => $re['nickname'],
								'createtime' => TIMESTAMP,
							);
							pdo_insert('fm_autogroup_log', $glog);

								break;
							}
							
						}
						
						
					}
					
				} //for
				if ($group != $groups[$i]['name']) {
					/**	pdo_update('fans', array(
							'follow' => 1,
							'nickname' => $re['nickname'],
							'gender' => $re['sex'],
							'residecity'=> $re['city'],
							'resideprovince' => $re['province'],
							'nationality' => $re['country'],
							'avatar' => $re['headimgurl']
							//'createtime' => TIMESTAMP,
						), array(
							'from_user' => $openid,
							'uniacid' => $uniacid
						));
							**/
						$member = pdo_fetch("SELECT * FROM ".tablename('fm_autogroup_members')." WHERE from_user = :from_user and uniacid = :uniacid LIMIT 1", array(':from_user' => $this->message['fromusername'],':uniacid' => $uniacid));
												
						if (!empty($member)) {
								pdo_update('fm_autogroup_members', array(
									'lastupdate' => TIMESTAMP,
									'follow' => 1,
									'followtrue' => 0,
									'nickname' => $re['nickname'],
									'avatar' => $re['headimgurl'],
									'fscount' => $member['fscount'] + 1,
								), array(
									'from_user' => $openid,
									'uniacid' => $uniacid,
								));
							} else {
								$data = array(
									'from_user' => $openid,
									'uniacid' => $uniacid,
									'rid' => $rid,
									'fscount' => '1',
									'follow' => 1,
									'followtrue' => 0,
									'nickname' => $re['nickname'],
									'avatar' => $re['headimgurl'],
									'lastupdate' => TIMESTAMP,
									'createtime' => TIMESTAMP,
								);
								pdo_insert('fm_autogroup_members', $data);
							}								
							//记录
							$glog = array(
								'from_user' => $openid,
								'rid' => $this->rule,
								'uniacid' => $uniacid,
								'gname' => $group,
								'content' => '关注',
								'fscount' => 1,
								'nickname' => $re['nickname'],
								'createtime' => TIMESTAMP,
							);
							pdo_insert('fm_autogroup_log', $glog);
							
					}
				
				
			}
			//
			
			
		}
	}
	
		
}