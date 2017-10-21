<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Receiver extends WeModuleReceiver
{
	public function receive()
	{
		global $_W;
		$type = $this->message['type'];
		$event = $this->message['event'];
		if (($event == 'subscribe') && ($type == 'subscribe')) {
			$this->saleVirtual();
		}
	}

	public function saleVirtual($obj = NULL)
	{
		global $_W;

		if (empty($obj)) {
			$obj = $this;
		}

		$sale = m('common')->getSysset('sale');
		$data = $sale['virtual'];

		if (empty($data['status'])) {
			return false;
		}

		load()->model('account');
		$account = account_fetch($_W['uniacid']);
		$totalagent = pdo_fetchcolumn('select count(*) from' . tablename('ewei_shop_member') . ' where uniacid =' . (int) $account['uniacid'] . ' and isagent =1');
		$totalmember = pdo_fetchcolumn('select count(*) from' . tablename('ewei_shop_member') . ' where uniacid =' . (int) $account['uniacid']);
		$acc = WeAccount::create();
		$member = abs((int) $data['virtual_people']) + (int) $totalmember;
		$commission = abs((int) $data['virtual_commission']) + (int) $totalagent;
		$user = m('member')->checkMemberFromPlatform($obj->message['from'], $acc);

		if ($user['isnew']) {
			$message = str_replace('[会员数]', $member, $data['virtual_text']);
			$message = str_replace('[排名]', $member + 1, $message);
		}
		else {
			$message = str_replace('[会员数]', $member, $data['virtual_text2']);
		}

		$message = str_replace('[分销商数]', $commission, $message);
		$message = str_replace('[昵称]', $user['nickname'], $message);
		$message = htmlspecialchars_decode($message, ENT_QUOTES);
		$message = str_replace('"', '\\"', $message);
		return $this->sendText($acc, $obj->message['from'], $message);
	}

	public function sendText($acc, $openid, $content)
	{
		$send['touser'] = trim($openid);
		$send['msgtype'] = 'text';
		$send['text'] = array('content' => urlencode($content));
		$data = $acc->sendCustomNotice($send);
		return $data;
	}
}

?>
