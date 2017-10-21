<?php
/**
 * 微现场
 * QQ 284099857
 */
defined('IN_IA') or exit('Access Denied');
define('MEEPO','../addons/meepo_bigerwall/template/mobile/newmobile/');
class Meepo_bigerwallModule extends WeModule {
	public $tablename = 'weixin_wall_reply';
	public function fieldsFormDisplay($rid = 0) {
		global $_W;
		if (!empty($rid)) {
						$reply = pdo_fetch("SELECT * FROM ".tablename($this->tablename)." WHERE rid = :rid", array(':rid' => $rid));
						if(empty($reply['bahe_web_bg_image'])){
							$reply['bahe_web_bg_image'] = MEEPO.'common/bahe/images/top_img.jpg';
						}
						if(empty($reply['bahe_web_adv4_image'])){
							$reply['bahe_web_adv4_image'] = '../addons/meepo_bigerwall/cdhn80.jpg';
						}
						if(empty($reply['bahe_web_adv3_image'])){
							$reply['bahe_web_adv3_image'] = '../addons/meepo_bigerwall/cdhn80.jpg';
						}
						if(empty($reply['bahe_web_adv2_image'])){
							$reply['bahe_web_adv2_image'] = '../addons/meepo_bigerwall/cdhn80.jpg';
						}
						if(empty($reply['bahe_web_adv1_image'])){
							$reply['bahe_web_adv1_image'] = '../addons/meepo_bigerwall/cdhn80.jpg';
						}
						if(empty($reply['bahe_web_person4_image'])){
							$reply['bahe_web_person4_image'] = MEEPO.'common/bahe/images/cheer_play4.png';
						}
						if(empty($reply['bahe_web_person3_image'])){
							$reply['bahe_web_person3_image'] = MEEPO.'common/bahe/images/cheer_play3.png';
						}
						if(empty($reply['bahe_web_person2_image'])){
							$reply['bahe_web_person2_image'] = MEEPO.'common/bahe/images/cheer_play2.png';
						}
						if(empty($reply['bahe_web_person1_image'])){
							$reply['bahe_web_person1_image'] = MEEPO.'common/bahe/images/cheer_play1.png';
						}
						if(empty($reply['bahe_web_zhuti2_img'])){
							$reply['bahe_web_zhuti2_img'] = MEEPO.'common/bahe/images/pillar_right.png';
						}
						if(empty($reply['bahe_web_zhuti1_img'])){
							$reply['bahe_web_zhuti1_img'] = MEEPO.'common/bahe/images/pillar_left.png';
						}
						if(empty($reply['bahe_team2_image'])){
							$reply['bahe_team2_image'] = MEEPO.'common/bahe/images/team2_image.png';
						}
						if(empty($reply['bahe_team1_image'])){
							$reply['bahe_team1_image'] = MEEPO.'common/bahe/images/team1_image.png';
						}
						if(empty($reply['bahe_bgmusic'])){
							$reply['bahe_bgmusic'] = MEEPO.'common/bahe/images/tug-bg.mp3';
						}
						if(empty($reply['bahe_team1_name'])){
							$reply['bahe_team1_name'] = '红队';
						}
						if(empty($reply['bahe_team2_name'])){
							$reply['bahe_team2_name'] = '蓝队';
						}
						if(empty($reply['bahe_title'])){
							$reply['bahe_title'] = '拔河、赢苹果6';
						}
						
						if(empty($reply['bahe_joinwords'])){
							$reply['bahe_joinwords'] = "<p>1.关注xxxxxx公众号</p>
                <p>2.发送xxx，获得图文回复后，点击进入图文活动页</p>
                <p>3.进入图文页后，点击允许按钮，获取参赛信息</p>
                <p>4.进入比赛页后，请在大屏幕倒计时结束后，摇动手机参与比赛</p>";
						}
						
				
		} else {
				$reply['renzhen']=1;//
				$reply['lurumobile']=0;
				$reply['new_mess']=1;
				$reply['gz_must']=0;
				$reply['lurucheck']=0;
				$reply['webopen']=0;
				$reply['isshow'] = 0;
				$reply['lurumobile'] =0;
				$reply['cj_config'] =1;
				$reply['timeout'] =10000;
				$reply['subit_tips']= '扫码关注、点击菜单即可参与';
				$reply['enter_tips'] = '您已经录入基本信息！直接回复任意内容即可上墙！';
				$reply['quit_tips'] = '您已经退出了微信墙，再次回复即可进入微信墙！';
				$reply['send_tips'] = '上墙成功，再次回复任意内容或图片即可再次上墙哦！';
				$reply['chaoshi_tips'] = '由于你长时间未参与本次活动，已被系统踢出，请重新进入！';
				$reply['quit_command'] = '退出';//
				$reply['toplogo']='../addons/meepo_bigerwall/logo.png';
				
				$reply['fontcolor']=  '#ffffff';
				$reply['loginpass']='admin';		
				$reply['defaultshow']=2;//
				$reply['followagain']= 1;
				$reply['refreshtime']= 6;
				$reply['saytasktime']= 5;
				$reply['saywords']=  '扫码关注、点击微现场菜单即可参与';//
				$reply['qdqshow']=1;
				$reply['signcheck']=  2;
				$reply['signwords'] = '扫码关注、点击微现场菜单即可参与！';//
				$reply['cjshow']=1;
				$reply['cjname'] = 'xx抽奖';   
				$reply['cjnum_tag']=  1;
				$reply['cjnum_exclude']=  1;
				$reply['cjwords']= '主持人点击抽奖、请多多关注大屏幕！';//
				$reply['tpshow']=1;
				$reply['votepower'] = 'xxx版本所有';
				$reply['votetitle']=  '欢迎进入微现场投票活动';
				$reply['qdtitle']=  '欢迎进入微现场签到活动';
				$reply['votemam']=  '100';
				$reply['voterefreshtime']=10;
				$reply['votewords']= '扫码关注后、点击菜单即可参与投票!';//
				$reply['ddpshow']=1;     
				$reply['ddpwords']='主持人点击对对碰、请多多关注大屏幕！'; //    
				$reply['yyyshow']=1;
				$reply['yyyzhuti']=  '摇一摇中大奖';
				$reply['yyyendtime']= 300;
				$reply['yyyshowperson']=10;
				$reply['yyyrealman']=0;//
				$reply['danmushow']=1;
				$reply['baheshow']=1;
				$reply['danmutime']= 20;
				$reply['danmufontsize']= 0;
				$reply['image_open']= 0;
				$reply['danmufontsmall']= 20;
				$reply['danmufontbig']= 40;
				$reply['danmuwords']= '主持人点击弹幕、请多多关注大屏幕！';
				$reply['mg_words']= '你妹的#我艹你妹';
				$reply['luru_words']= '请输入您的真实姓名以及联系方式，验证您的真实身份！\n';
				$reply['had_sign_content']= '你已经签到过了！';
				$reply['sign_success']= '签到成功！';
				$reply['can_send']= 1;
				$reply['send_luck_words'] = '亲爱的#、恭喜恭喜！你已经中:$奖品为: % 请按照主持人的提示，到指定地点领取您的奖品！您的获奖验证码是: &';
				$reply['3dsign']= 0;
				$reply['3d_noavatar']='../addons/meepo_bigerwall/template/mobile/newmobile/qd/3d/images/no_avatar.png';
				$reply['3dsign_bg']='../addons/meepo_bigerwall/template/mobile/newmobile/qd/3d/images/bg.jpg';
				$reply['3dsign_join_words']='扫码关注、发送签到即可完成签到！';
				$reply['3dsign_title']='欢迎来到3D签到！';
				$reply['3dsign_logo']='../addons/meepo_bigerwall/template/mobile/newmobile/qd/3d/images/no_avatar.png';
				$reply['table_time']= 10;
				$reply['sphere_time']= 20;
				$reply['helix_time']= 20;
				$reply['grid_time']= 20;
				$reply['bg_music_on'] = 0;
				$reply['3dsign_gap'] = 10;
				$reply['3dsign_logo_width'] = 400;
				$reply['3dsign_logo_height'] = 400;
				$reply['bahe_time']=20;
				$reply['bahe_web_bg_image'] = MEEPO.'common/bahe/images/top_img.jpg';
				$reply['bahe_web_adv4_image'] = '../addons/meepo_bigerwall/cdhn80.jpg';
				$reply['bahe_web_adv3_image'] = '../addons/meepo_bigerwall/cdhn80.jpg';
				$reply['bahe_web_adv2_image'] = '../addons/meepo_bigerwall/cdhn80.jpg';
				$reply['bahe_web_adv1_image'] = '../addons/meepo_bigerwall/cdhn80.jpg';
				$reply['bahe_web_person4_image'] = MEEPO.'common/bahe/images/cheer_play4.png';
				$reply['bahe_web_person3_image'] = MEEPO.'common/bahe/images/cheer_play3.png';
				$reply['bahe_web_person2_image'] = MEEPO.'common/bahe/images/cheer_play2.png';
				$reply['bahe_web_person1_image'] = MEEPO.'common/bahe/images/cheer_play1.png';
				$reply['bahe_web_zhuti2_img'] = MEEPO.'common/bahe/images/pillar_right.png';
				$reply['bahe_web_zhuti1_img'] = MEEPO.'common/bahe/images/pillar_left.png';
				$reply['bahe_team2_image'] = MEEPO.'common/bahe/images/team2_image.png';
				$reply['bahe_team1_image'] = MEEPO.'common/bahe/images/team1_image.png';
				$reply['bahe_bgmusic'] = MEEPO.'common/bahe/images/tug-bg.mp3';
				$reply['bahe_team1_name'] = '红队';
				$reply['bahe_team2_name'] = '蓝队';
				$reply['bahe_title'] = '拔河、赢苹果6';
				$reply['bahe_joinwords'] = '<p>1.关注xxxxxx公众号</p>
                <p>2.发送xxx，获得图文回复后，点击进入图文活动页</p>
                <p>3.进入图文页后，点击允许按钮，获取参赛信息</p>
                <p>4.进入比赛页后，请在大屏幕倒计时结束后，摇动手机参与比赛</p>';
				$reply['activity_title'] = '微现场';
				$reply['yyy_pc_word'] = '发送摇一摇、即可参与';
				$reply['qd_zhufus'] = '祝福你们#好漂亮啊';
						//}
				
		}
		  $sty_name=array();//name数组，
			$sty_name['defaultV1.0.css']="默认风格";
			$sty_name['LanternFestival_1.css']="元宵节";
			$sty_name['SpringFestival_1.css']="春节1";
			$sty_name['SpringFestival_2.css']="春节2";
			$sty_name['SpringFestival_3.css']="春节3";
			$sty_name['Valentine_1.css']="情人节1";
			$sty_name['Valentine_2.css']="情人节2";
			$sty_name['Valentine_3.css']="情人节3";
			$sty_name['colorRed_1.css']="红色";
			$sty_name['colorBluishViolet_1.css']="蓝色";
			$sty_name['colorClaret_1.css']="紫红色";
			$sty_name['Christmas_1.css']="圣诞节";
			$sty_name['christmasblue.css']="圣诞节2";
			$sty_name['christmasred.css']="圣诞节3";
			$sty_name['loveheart1.css']="罗曼蒂克1";
			$sty_name['loveheart2.css']="罗曼蒂克2";
		  load()->func('tpl');
		  include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		return true;
	}
	public function fieldsFormSubmit($rid = 0) {
		global $_GPC, $_W;
		$id = intval($_GPC['reply_id']);
		$insert = array(
			'rid' => $rid,
      'weid'=>$_W['uniacid'],
			'enter_tips' => $_GPC['enter_tips'],
			'subit_tips'=> $_GPC['subit_tips'],
			'quit_tips' => $_GPC['quit_tips'],
			'send_tips' => $_GPC['send_tips'],
      'chaoshi_tips' => $_GPC['chaoshi_tips'],
			'timeout' => $_GPC['timeout'],
			'isshow' => intval($_GPC['isshow']),
			'quit_command' => $_GPC['quit_command'],
			'lurumobile' => intval($_GPC['lurumobile']),
				'lurucheck' => intval($_GPC['lurucheck']),
				'webopen' => intval($_GPC['webopen']),
			'signcheck' => intval($_GPC['signcheck']),
		);
			$insert['votetitle'] = $_GPC['votetitle'];
			$insert['qdtitle']=  $_GPC['qdtitle'];
			$insert['votepower'] = $_GPC['votepower'];
			$insert['refreshtime'] = intval($_GPC['refreshtime']);
			$insert['saytasktime'] = intval($_GPC['saytasktime']);
			
      $insert['danmutime'] = intval($_GPC['danmutime']);
			$insert['danmushow'] = intval($_GPC['danmushow']);
			$insert['yyyendtime'] = intval($_GPC['yyyendtime']);
			$insert['yyyshowperson'] = intval($_GPC['yyyshowperson']);
			$insert['yyyzhuti'] = $_GPC['yyyzhuti'];
			$insert['voterefreshtime'] = intval($_GPC['voterefreshtime']);
			$insert['qdqshow'] = intval($_GPC['qdqshow']);
			$insert['yyyshow'] = intval($_GPC['yyyshow']);
			$insert['ddpshow'] = intval($_GPC['ddpshow']);
			$insert['tpshow'] = intval($_GPC['tpshow']);
			$insert['cjshow'] = intval($_GPC['cjshow']);
			$insert['loginpass'] = $_GPC['loginpass'];
			$insert['indexstyle'] = $_GPC['indexstyle'];
			$insert['cjnum_tag'] = intval($_GPC['cjnum_tag']);
			$insert['cjnum_exclude'] = intval($_GPC['cjnum_exclude']);
			$insert['cjtag_exclude'] = intval($_GPC['cjnum_exclude']);
			$insert['cjname'] = $_GPC['cjname'];   
			$insert['cjimgurl'] = $_GPC['cjimgurl'];
			$insert['defaultshow'] = intval($_GPC['defaultshow']);
      $insert['realman'] = intval($_GPC['realman']);
			$insert['bgimg'] = $_GPC['bgimg'];
			$insert['fontcolor'] = $_GPC['fontcolor'];
			$insert['starttime'] = strtotime($_GPC['times']['start']);
			$insert['endtime'] = strtotime($_GPC['times']['end']);
      $insert['votemam'] = intval($_GPC['votemam']);
			$insert['yyyrealman'] = intval($_GPC['yyyrealman']);
			$insert['yyybgimg'] = $_GPC['yyybgimg'];
      $insert['danmubgimg'] = $_GPC['danmubgimg'];
			$insert['saywords'] = $_GPC['saywords'];
			$insert['signwords'] = $_GPC['signwords'];
			$insert['cjwords'] = $_GPC['cjwords'];
			$insert['ddpwords'] = $_GPC['ddpwords'];
			$insert['votewords'] = $_GPC['votewords'];
			$insert['danmuwords'] = $_GPC['danmuwords'];
			$insert['toplogo'] = $_GPC['toplogo'];
			$insert['followagain'] = intval($_GPC['followagain']);
			$insert['renzhen'] = intval($_GPC['renzhen']);
			$insert['erweima'] = $_GPC['erweima'];
			$insert['yyy_keyword'] = $_GPC['yyy_keyword'];
			$insert['tp_keyword'] = $_GPC['tp_keyword'];
			$insert['qd_keyword'] = $_GPC['qd_keyword'];
			$insert['login_bg'] = $_GPC['login_bg'];
			$insert['mg_words'] = $_GPC['mg_words'];
			$insert['luru_words'] = $_GPC['luru_words'];
			$insert['sign_success'] = $_GPC['sign_success'];
			$insert['had_sign_content'] = $_GPC['had_sign_content'];
			$insert['danmufontcolor'] = $_GPC['danmufontcolor'];
			$insert['danmufontsmall'] = intval($_GPC['danmufontsmall']);
			$insert['danmufontbig'] = intval($_GPC['danmufontbig']);
			$insert['had_sign_content'] = $_GPC['had_sign_content'];
			$insert['can_send']= intval($_GPC['can_send']);
			$insert['send_luck_words'] = $_GPC['send_luck_words'];
			$insert['3dsign'] = intval($_GPC['3dsign']);
			$insert['3d_noavatar'] = $_GPC['3d_noavatar'];
			$insert['3dsign_bg'] = $_GPC['3dsign_bg'];
			$insert['3dsign_join_words'] = $_GPC['3dsign_join_words'];
			$insert['3dsign_title'] = $_GPC['3dsign_title'];
			$insert['3dsign_words'] = $_GPC['3dsign_words'];
			$insert['3dsign_logo'] = $_GPC['3dsign_logo'];
			$insert['3dsign_show_info'] = $_GPC['3dsign_show_info'];
			$insert['3dsign_gap'] = intval($_GPC['3dsign_gap']);
			$insert['3dsign_persons'] = intval($_GPC['3dsign_persons']);
			$insert['table_time'] = intval($_GPC['table_time']);
			$insert['sphere_time'] = intval($_GPC['sphere_time']);
			$insert['helix_time'] = intval($_GPC['helix_time']);
			$insert['grid_time'] = intval($_GPC['grid_time']);
			$insert['gz_url'] = $_GPC['gz_url'];
			$insert['bg_music_on'] = intval($_GPC['bg_music_on']);
			$insert['bg_music'] = $_GPC['bg_music'];
			$insert['gz_must'] = intval($_GPC['gz_must']);
			$insert['3dsign_logo_width'] = intval($_GPC['3dsign_logo_width']);
			$insert['3dsign_logo_height'] = intval($_GPC['3dsign_logo_height']);
			$insert['baheshow'] = intval($_GPC['baheshow']);
			$insert['image_open'] = intval($_GPC['image_open']);
			$insert['new_mess']=intval($_GPC['new_mess']);
			$insert['bahe_time']=intval($_GPC['bahe_time']);
			$insert['cj_config']=intval($_GPC['cj_config']);
			
			$insert['bahe_web_bg_image'] = $_GPC['bahe_web_bg_image'];
			$insert['bahe_web_big_bg'] = $_GPC['bahe_web_big_bg'];
			$insert['bahe_web_adv4_image'] = $_GPC['bahe_web_adv4_image'];
			$insert['bahe_web_adv3_image'] = $_GPC['bahe_web_adv3_image'];
			$insert['bahe_web_adv2_image'] = $_GPC['bahe_web_adv2_image'];
			$insert['bahe_web_adv1_image'] = $_GPC['bahe_web_adv1_image'];
			$insert['bahe_web_person4_image'] = $_GPC['bahe_web_person4_image'];
			$insert['bahe_web_person3_image'] = $_GPC['bahe_web_person3_image'];
			$insert['bahe_web_person2_image'] = $_GPC['bahe_web_person2_image'];
			$insert['bahe_web_person1_image'] = $_GPC['bahe_web_person1_image'];
			$insert['bahe_web_zhuti2_img'] = $_GPC['bahe_web_zhuti2_img'];
			$insert['bahe_web_zhuti1_img'] = $_GPC['bahe_web_zhuti1_img'];
			$insert['bahe_team2_image'] = $_GPC['bahe_team2_image'];
			$insert['bahe_team1_image'] = $_GPC['bahe_team1_image'];
			$insert['bahe_team2_name'] = $_GPC['bahe_team2_name'];
			$insert['bahe_team1_name'] = $_GPC['bahe_team1_name'];
			$insert['bahe_title'] = $_GPC['bahe_title'];
			$insert['bahe_joinwords'] = $_GPC['bahe_joinwords'];
			$insert['activity_starttime'] = strtotime($_GPC['ac_times']['start']);
			$insert['activity_endtime'] = strtotime($_GPC['ac_times']['end']);
			$insert['activity_title'] = $_GPC['activity_title'];
			$insert['bahe_bgmusic'] = $_GPC['bahe_bgmusic'];
			$insert['qd_zhufus'] = $_GPC['qd_zhufus'];
			$insert['qd_weixin_bg'] = $_GPC['qd_weixin_bg'];
			$insert['yyy_pc_word'] = $_GPC['yyy_pc_word'];
		if (empty($id)) {
			pdo_insert($this->tablename, $insert);
		} else {
			pdo_update($this->tablename, $insert, array('id' => $id));
		}

	}
	public function ruleDeleted($rid = 0) {
    global $_W;
		pdo_delete('weixin_flag',array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete('weixin_vote',array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete('weixin_wall',array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete('weixin_shake_toshake',array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete('weixin_wall_num',array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete('weixin_wall_reply',array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete('weixin_awardlist',array('luckid'=>$rid,'weid'=>$_W['uniacid']));
    pdo_delete('weixin_luckuser',array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete('weixin_shake_data',array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete('weixin_signs',array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete('weixin_modules',array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete('weixin_bahe_team',array('rid'=>$rid,'weid'=>$_W['uniacid']));
		pdo_delete('weixin_bahe_prize',array('rid'=>$rid,'weid'=>$_W['uniacid']));
		return true;
	}
}

