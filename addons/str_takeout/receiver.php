<?php
defined('IN_IA') or exit('Access Denied');

class Tiny_qrcodeModuleReceiver extends WeModuleReceiver {
	public function receive() {
		global $_W;
		if($this->message['type'] == 'subscribe' || $this->message['type'] == 'qr') {
			$sceneid = $this->message['scene'];
			$acid = $this->acid;
			$uniacid = $this->uniacid;
			$row = pdo_fetch("SELECT id, name, acid FROM ".tablename('qrcode')." WHERE uniacid = :aid AND acid = :acid AND qrcid = :qrcid", array(':aid' => $uniacid, ':acid' => $acid, ':qrcid' => $sceneid));
			if(empty($row)) {
				$row = pdo_fetch("SELECT id, name, acid FROM ".tablename('qrcode')." WHERE uniacid = :aid AND acid = :acid AND scene_str = :scene_str", array(':aid' => $uniacid, ':acid' => $acid, ':scene_str' => $sceneid));
			}
			$reply = pdo_fetch('SELECT * FROM ' . tablename('tiny_qrcode') . ' WHERE `qid`=:qid', array(':qid' => $row['id']));
			if(!empty($reply)) {
				$groupid = intval($reply['groupid']);
				$openid = trim($this->message['from']);
				if(!empty($openid)) {
					$acc = WeAccount::create($_W['acid']);
					$data = $acc->updateFansGroupid($openid, $groupid);
					if(!is_error($data)) {
						pdo_update('mc_mapping_fans', array('groupid' => $groupid), array('uniacid' => $_W['uniacid'], 'acid' => $_W['acid'], 'openid' => $openid));
					}
				}
			}
		}
	}
}
