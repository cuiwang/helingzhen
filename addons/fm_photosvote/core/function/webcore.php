<?php
/**
 * 女神来了模块定义
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
//require IA_ROOT. '/addons/fm_photosvote/core/defines.php';
class Webcore extends WeModule {
	public $title 			 = '女神来了！';
	public $table_reply  	 = 'fm_photosvote_reply';//规则 相关设置
	public $table_reply_share  = 'fm_photosvote_reply_share';//规则 相关设置
	public $table_reply_huihua  = 'fm_photosvote_reply_huihua';//规则 相关设置
	public $table_reply_display  = 'fm_photosvote_reply_display';//规则 相关设置
	public $table_reply_vote  = 'fm_photosvote_reply_vote';//规则 相关设置
	public $table_reply_body  = 'fm_photosvote_reply_body';//规则 相关设置
	public $table_users  	 = 'fm_photosvote_provevote';	//报名参加活动的人
	public $table_tags  	 = 'fm_photosvote_tags';	//
	public $table_users_picarr  = 'fm_photosvote_provevote_picarr';	//
	public $table_users_voice  	= 'fm_photosvote_provevote_voice';	//
	public $table_users_name  	= 'fm_photosvote_provevote_name';	//
	public $table_log        = 'fm_photosvote_votelog';//投票记录
	public $table_bbsreply   = 'fm_photosvote_bbsreply';//投票记录
	public $table_banners    = 'fm_photosvote_banners';//幻灯片
	public $table_advs  	 = 'fm_photosvote_advs';//广告
	public $table_gift  	 = 'fm_photosvote_gift';
	public $table_data  	 = 'fm_photosvote_data';
	public $table_iplist 	 = 'fm_photosvote_iplist';//禁止ip段
	public $table_iplistlog  = 'fm_photosvote_iplistlog';//禁止ip段
	public $table_announce   = 'fm_photosvote_announce';//公告
	public $table_templates   = 'fm_photosvote_templates';//模板
	public $table_designer   = 'fm_photosvote_templates_designer';//模板页面
	public $table_order   = 'fm_photosvote_order';//付费投票

	public function __construct() {
		global $_W;
	}


}
