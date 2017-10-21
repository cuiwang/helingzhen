<?php
/**
 * 钻石投票模块处理程序
 *
 */
defined('IN_IA') or exit('Access Denied');
require IA_ROOT. '/addons/tyzm_diamondvote/defines.php'; 
require TYZM_MODEL_FUNC.'/function.php';
class tyzm_diamondvoteModuleProcessor extends WeModuleProcessor {
	public $tablevoteuser = 'tyzm_diamondvote_voteuser';
	public function respond() {
		global $_W,$_GPC;
		//这里定义此模块进行消息处理时的具体过程, 请查看文档来编写你的代码
		$message = $this->message;
		$rid = $this->rule;
		$voteid=$this->number($message['content']);
		if($voteid!=0){
			$openid= $message['from'];
            load()->model('mc');
			$fans = mc_fansinfo($openid);

			$voteuser = pdo_fetch("SELECT id,noid FROM " . tablename($this->tablevoteuser) . " WHERE noid = :noid AND rid = :rid  ", array(':noid' => $voteid,':rid' => $rid));
			
			if(empty($voteuser)){
				return $this->respText("没有该编号用户，请检查后再输入！");
			}
			$userinfo=array();
			$userinfo['nickname']=$fans['tag']['nickname'];
			$userinfo['openid']=$fans['openid'];
			$userinfo['avatar']=$fans['tag']['avatar'];
			$userinfo['oauth_openid']=$fans['openid'];
			$userinfo['unionid']=$fans['unionid'];
			$userinfo['follow']=$fans['follow'];

			$votere=m('vote')->setvote($userinfo,$rid,$voteuser['id'],$_W['account']['token']);
			return $this->respText($votere['msg']);
		}
		


		$sql = "SELECT title,description,thumb,status,starttime FROM " . tablename('tyzm_diamondvote_reply') . " WHERE `rid`=:rid LIMIT 1";
		$row = pdo_fetch($sql, array(':rid' => $rid));

		if ($row == false) {
			return $this->respText("活动已取消...");
		}

		if ($row['isshow'] == 1){
			return $this->respText("活动暂停，请稍后...");
		}

		if ($row['starttime'] > time()) {
			return $this->respText("活动未开始，请等待...");
		}
		return $this->respNews(array(
			'Title' => $row['title'],
			'Description' => $row['description'],
			'PicUrl' => toimage($row['thumb']),
			'Url' => $this->createMobileUrl('index', array('rid' => $rid)),
		));
	}
	public function number($str)
	{
	    return preg_replace('/\D/s', '', $str);
	}
}

