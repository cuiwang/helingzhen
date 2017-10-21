<?php
/**
 * 微房产
 * QQ：792454007
 *
 * @author 大路货
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class Lxy_buildproModuleProcessor extends WeModuleProcessor {
	public $table_reply  = 'lxy_buildpro_reply';
	public $table_list  = 'lxy_buildpro_head';
	
	
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$fromuser = $this->message['from'];
		
		if($rid) {
			$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid", array(':rid' => $rid));
			if($reply) {
				$sql = 'SELECT * FROM ' . tablename($this->table_list) . ' WHERE `weid`=:weid AND `hid`=:hid';
				$activity = pdo_fetch($sql, array(':weid' => $_W['uniacid'], ':hid' => $reply['hid']));
				$news = array();			
				$news[] = array(
						'title' => $activity['title'],
						'description' =>strip_tags($activity['jianjie']),
						'picurl' =>$this->getpicurl($activity['pic']),
						'url' => $_W['siteroot'].'app/'.$this->createMobileUrl('buildindex', array('hid' => $activity['hid'],'from_user' => base64_encode(authcode($fromuser, 'ENCODE'))))
				);
				return $this->respNews($news);
			}
		}
		return null;
	}
 

	private  function getpicurl($url)	
	{
		global $_W;
		if($url)
		{
			return $_W['attachurl'].$url;
		}
		else 
		{
			return $_W['siteroot'].'addons/lxy_buildpro/template/img/build_home.png';
		}
	}
}

