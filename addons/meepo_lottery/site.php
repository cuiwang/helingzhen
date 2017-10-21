<?php
/**
 * 积分抽奖模块微站定义
 *
 * @author WDD
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Meepo_lotteryModuleSite extends WeModuleSite {

	// public function doMobileIndex() {
	// 	echo 'nihaoma';
	// }
	public function doWebRule() {
		//这个操作被定义用来呈现 规则列表
		echo 'nihaoma';
	}
	public function doWebNav() {
		//这个操作被定义用来呈现 管理中心导航菜单
		echo 'wozhidaol';
	}
	public function mc_notice_consume2($openid, $title, $content, $url = '',$thumb='') {
			global $_W;
			$cfg = $this->module['config'];
			$tpl_id = $cfg['tpl_id'];
			load()->model('mc');
			$acc = mc_notice_init();
			if(is_error($acc)) {
				return error(-1, $acc['message']);
			}
			if($_W['account']['level'] == 4) {
				$tpl_data = array();
				$tpl_data['first'] = array('value'=>$title, 'color'=>'#173177');
				$tpl_data['keyword1'] = array('value'=>$content, 'color'=>'#173177');
				$tpl_data['keyword2'] = array('value'=>date('Y-m-d H:i:s',time()), 'color'=>'#173177');
				$tpl_data['keyword3'] = array('value'=>$content, 'color'=>'#173177');
				$tpl_data['remark'] = array('value'=>$_W['account']['name'], 'color'=>'#173177');
				$real_url = $_W['siteroot']. 'app/'.$url;
				$status = $acc->sendTplNotice($openid, $tpl_id,$tpl_data, $real_url);
				if(is_error($status)){
					$status = $this->mc_notice_custom_news3($openid, $title, $content,$url,$thumb);
				}
			}
			if($_W['account']['level'] == 3) {
				$status = $this->mc_notice_custom_news3($openid, $title, $content,$url,$thumb);
			}
			return $status;
		}
	public function mc_notice_custom_news3($openid, $title, $content,$url,$thumb) {
		global $_W;
		load()->model('mc');
		$cfg = $this->module['config'];
		$thumb = $cfg['kefuimg'];
		$acc = mc_notice_init();
		if(is_error($acc)) {
			return error(-1, $acc['message']);
		}
		$fans = pdo_fetch('SELECT salt,acid,openid FROM ' . tablename('mc_mapping_fans') . ' WHERE uniacid = :uniacid AND openid = :openid', array(':uniacid' => $_W['uniacid'], ':openid' => $openid));

		$row = array();
		$row['title'] = urlencode($title);
		$row['description'] = urlencode($content);
		$row['picurl'] = tomedia($thumb);

		if(strexists($url, 'http://') || strexists($url, 'https://')) {
			$row['url'] = $url;
		} else {

			$pass['time'] = TIMESTAMP;
			$pass['acid'] = $fans['acid'];
			$pass['openid'] = $fans['openid'];
			$pass['hash'] = md5("{$fans['openid']}{$pass['time']}{$fans['salt']}{$_W['config']['setting']['authkey']}");
			$auth = base64_encode(json_encode($pass));
			$vars = array();
			$vars['__auth'] = $auth;
			if(empty($url)){
				$vars['forward'] = base64_encode($this->createMobileUrl('fans_home'));
			}else{
				$vars['forward'] = base64_encode($url);
			}


			$row['url'] =  $_W['siteroot'] . 'app/' . murl('auth/forward', $vars);
		}
		$news[] = $row;
		$send['touser'] = trim($openid);
		$send['msgtype'] = 'news';
		$send['news']['articles'] = $news;
		$status = $acc->sendCustomNotice($send);
		return $status;
	}


}