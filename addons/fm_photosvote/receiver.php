<?php
/**
 * 模块订阅器
 * 易福 源 码 网 
 */
defined('IN_IA') or exit('Access Denied');

class Fm_photosvoteModuleReceiver extends WeModuleReceiver {
	public function receive() {
		global $_W, $_GPC;
		//load()->func('communication');
		$type = $this->message['type'];
		$uniacid = $_W['uniacid'];
		$acid = $_W['acid'];
		$openid = $this->message['from'];
		$event = $this->message['event'];
		$cfg = $this->module['config'];
		$sceneid = $this->message['scene'];
		$ticket = $this->message['ticket'];
		file_put_contents(IA_ROOT.'/addons/fm_photosvote/test/fm_test.txt', iserializer($sceneid));
		//print_r($event);
		if ($event == 'unsubscribe') {
			/**$record = array(
				'updatetime'=> TIMESTAMP,
				'follow' 	=> '0',
				'unfollowtime'=> TIMESTAMP
			);
			pdo_update('mc_mapping_fans', $record, array('openid' => $openid, 'uniacid' => $uniacid));**/

			if ($cfg['isopenjsps']) {
				$rids = pdo_fetchall("SELECT rid FROM ".tablename('fm_photosvote_votelog')." WHERE from_user = :from_user and uniacid = :uniacid AND is_del = 0 GROUP BY rid", array(':from_user' => $openid,':uniacid' => $uniacid));
				if (!empty($rids)) {
					foreach ($rids as $row) {
						$rbasic = pdo_fetch("SELECT start_time,end_time FROM ".tablename('fm_photosvote_reply')." WHERE  rid = :rid LIMIT 1", array(':rid' => $row['rid']));

						if(TIMESTAMP >= $rbasic['start_time'] && TIMESTAMP <= $rbasic['end_time']){
							$vote_times = pdo_fetchall("SELECT rid, tfrom_user, from_user, SUM(vote_times) AS total FROM ".tablename('fm_photosvote_votelog')." WHERE from_user = :from_user and rid = :rid AND is_del = 0 GROUP BY tfrom_user", array(':from_user' => $openid,':rid' => $row['rid']));
							//print_r($vote_times);
							if (!empty($vote_times)) {
								foreach ($vote_times as $key => $value) {
									$fmprovevote = pdo_fetch("SELECT photosnum, unphotosnum, hits, unhits FROM ".tablename('fm_photosvote_provevote')." WHERE from_user = :from_user and rid = :rid LIMIT 1", array(':from_user' => $value['tfrom_user'],':rid' => $row['rid']));
									pdo_update('fm_photosvote_provevote', array(
										'lasttime' => TIMESTAMP,
										'photosnum' => $fmprovevote['photosnum'] - $value['total'],
										'hits' => $fmprovevote['hits'] - $value['total'],
										'unphotosnum' => $fmprovevote['unphotosnum'] + $value['total'],
										'unhits' => $fmprovevote['unhits'] + $value['total'],
									), array(
										'from_user' => $value['tfrom_user'],
										'rid' => $row['rid'],
									));
									pdo_update('fm_photosvote_votelog', array('is_del' => 1), array('from_user' => $openid, 'tfrom_user' => $value['tfrom_user'],'rid'=> $row['rid']));
									$rdisplay = pdo_fetch("SELECT ljtp_total, cyrs_total, unphotosnum FROM ".tablename('fm_photosvote_reply_display')." WHERE  rid = :rid LIMIT 1", array(':rid' => $row['rid']));
									pdo_update('fm_photosvote_reply_display', array('ljtp_total' => $rdisplay['ljtp_total'] - $value['total'], 'cyrs_total' => $rdisplay['cyrs_total'] - $value['total'], 'unphotosnum' => $rdisplay['unphotosnum'] + $value['total']), array('rid' => $row['rid']));
								}
							}
							pdo_update('fm_photosvote_bbsreply',array('is_del' => 1), array('from_user' => $openid, 'rid'=> $row['rid']));
						}
					}
				}

			}


		}elseif($event == 'subscribe'){
			if ($cfg['oauthtype'] == 2) {

				$arrlog = pdo_fetch("SELECT * FROM ".tablename('mc_mapping_fans') . " WHERE uniacid = :uniacid AND openid = :openid", array(':uniacid' => $_W['uniacid'],':openid' => $openid));


				$access_token = WeAccount::token();
				$url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
				$html=file_get_contents($url);
				$re = @json_decode($html, true);


				if(!empty($arrlog)){
					$data = array(
					'nickname' => $re['nickname'],
					'unionid' => $re['unionid'],
					);
					pdo_update('mc_mapping_fans',$data, array('openid'=>$openid));
				}else{
					$default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' .tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $_W['uniacid']));
					$nickname=$re['nickname'];
					$data = array(
						'uniacid' => $_W['uniacid'],
						'nickname' => $re['nickname'],
						'avatar' => $re['headimgurl'],
						'groupid' => $default_groupid,
						'createtime' => TIMESTAMP,
					);
					pdo_insert('mc_members', $data);
					$id = pdo_insertid();
					$data = array(
						'nickname' => $re['nickname'],
						'unionid' => $re['unionid'],
						'uid' => $id,
					);
					pdo_update('mc_mapping_fans',$data, array('openid'=>$openid));
				}
			}
		}elseif($event == 'SCAN'){

		}

	}


}