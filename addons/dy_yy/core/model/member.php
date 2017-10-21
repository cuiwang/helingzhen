<?php


if (!defined('IN_IA')) {
    exit('Access Denied');
}
class Dy_yy_Member
{	public function getuid($openid){
		global $_W;

		$a = pdo_fetch('select uid from'.tablename('mc_mapping_fans').'where uniacid=:uniacid and openid=:openid',array(
		':uniacid' => $_W['uniacid'],
		':openid' => $openid
		));
		return $a['uid'];
	}
	public function checkMember(){
		global $_W, $_GPC;
		if (strexists($_SERVER['REQUEST_URI'], '/web/')) {
            return;
        }
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
			}
		if (empty($_W['fans']['nickname'])) {
			 die("<!DOCTYPE html>
						<html>
							<head>
								<meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'>
								<title>抱歉，出错了</title><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'><link rel='stylesheet' type='text/css' href='https://res.wx.qq.com/connect/zh_CN/htmledition/style/wap_err1a9853.css'>
							</head>
							<body>
							<div class='page_msg'><div class='inner'><span class='msg_icon_wrp'><i class='icon80_smile'></i></span><div class='msg_content'><h4>请使用微信访问！</h4></div></div></div>
							</body>
						</html>");
			}
	}
}