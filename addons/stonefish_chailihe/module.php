<?php
/**
 * 幸运拆礼盒模块定义
 *
 * @author 易福源码网www.efwww.com
 */
defined('IN_IA') or exit('Access Denied');

class Stonefish_chailiheModule extends WeModule {

	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W;
		load()->func('tpl');
		$uniacid = $_W['uniacid'];
		//查询是否填写系统参数
		$setting = $this->module['config'];
		if(empty($setting)){
			message('抱歉，系统参数没有填写，请先填写系统参数！', url('profile/module/setting',array('m' => 'stonefish_chailihe')), 'error');
		}
		//查询是否填写系统参数
		//积分类型
		$creditnames = array();
		$unisettings = uni_setting($uniacid, array('creditnames'));
		foreach ($unisettings['creditnames'] as $key=>$credit) {
			if (!empty($credit['enabled'])) {
				$creditnames[$key] = $credit['title'];
			}
		}
		//积分类型
		//查询是否有商户网点、会员中心权限
		$modules = uni_modules($enabledOnly = true);
		$modules_arr = array();
		$modules_arr = array_reduce($modules, create_function('$v,$w', '$v[$w["mid"]]=$w["name"];return $v;'));
		if(in_array('stonefish_branch',$modules_arr)){
		    $stonefish_branch = true;
		}
		if(in_array('stonefish_member',$modules_arr)){
		    $stonefish_member = true;
		}
		//查询是否有商户网点、会员中心权限
		//查询子公众号信息
		$acid_arr = uni_accounts();
		$ids = array();
		$ids = array_map('array_shift', $acid_arr);//子公众账号Arr数组
		$ids_num = count($ids);//多少个子公众账号
		$one = current($ids);
		//查询子公众号信息
		//查询公众号会员组信息
		$sys_users = pdo_fetchall("SELECT groupid,title FROM ".tablename('mc_groups')." WHERE uniacid = :uniacid ORDER BY isdefault DESC,orderlist DESC,groupid DESC", array(':uniacid' => $_W['uniacid']));
		//查询公众号会员组信息
		//活动模板
		$template = pdo_fetchall("SELECT * FROM " . tablename('stonefish_chailihe_template') . " WHERE uniacid = :uniacid OR uniacid=0 ORDER BY `id` asc", array(':uniacid' => $uniacid));
		if(empty($template)){			
			$inserttemplate = array(
                'uniacid'          => 0,
				'title'            => '中秋节主题',
				'thumb'            => '../addons/stonefish_chailihe/template/images/template.jpg',
				'fontsize'         => '12',
				'bgimg'            => '../addons/stonefish_chailihe/template/images/bg.jpg',
				'bgimglihe'        => '../addons/stonefish_chailihe/template/images/bg_myprize.jpg',
				'bgimgprize'       => '../addons/stonefish_chailihe/template/images/bg_common.jpg',
				'bgcolor'          => '#26216f',
				'textcolor'        => '#ffffff',
				'textcolorlink'    => '#5E43B6',
				'buttoncolor'      => '#5E43B6',
				'buttontextcolor'  => '#ffffff',
				'rulecolor'        => '#5E43B6',
				'ruletextcolor'    => '#ffffff',
				'navcolor'         => '#fcfcfc',
				'navtextcolor'     => '#9a9a9a',
				'navactioncolor'   => '#5E43B6',
				'watchcolor'       => '#efe7e0',
				'watchtextcolor'   => '#717171',
				'awardcolor'       => '#8571fe',
				'awardtextcolor'   => '#ffffff',
				'awardscolor'      => '#b7b7b7',
				'awardstextcolor'  => '#434343',
			);
			pdo_insert('stonefish_chailihe_template', $inserttemplate);
			$inserttemplate = array(
                'uniacid'          => 0,
				'title'            => '端午节主题',
				'thumb'            => '../addons/stonefish_chailihe/template/images/duanwu.jpg',
				'fontsize'         => '12',
				'bgimg'            => '../addons/stonefish_chailihe/template/images/1.png',
				'bgimglihe'        => '../addons/stonefish_chailihe/template/images/2.png',
				'bgimgprize'       => '../addons/stonefish_chailihe/template/images/3.png',
				'bgcolor'          => '#b0e6ca',
				'textcolor'        => '#308155',
				'textcolorlink'    => '#f3f3f3',
				'buttoncolor'      => '#45986c',
				'buttontextcolor'  => '#ffffff',
				'rulecolor'        => '#f7ce40',
				'ruletextcolor'    => '#f3f3f3',
				'navcolor'         => '#fcfcfc',
				'navtextcolor'     => '#f3f3f3',
				'navactioncolor'   => '#93c47d',
				'watchcolor'       => '#efe7e0',
				'watchtextcolor'   => '#717171',
				'awardcolor'       => '#38761d',
				'awardtextcolor'   => '#ffffff',
				'awardscolor'      => '#b7b7b7',
				'awardstextcolor'  => '#434343',
			);
			pdo_insert('stonefish_chailihe_template', $inserttemplate);
			$template = pdo_fetchall("SELECT * FROM " . tablename('stonefish_chailihe_template') . " WHERE uniacid = :uniacid OR uniacid=0 ORDER BY `id` asc", array(':uniacid' => $uniacid));
		}
		//活动模板
		//礼盒样式
		$lihestyle = pdo_fetchall("SELECT * FROM " . tablename('stonefish_chailihe_lihestyle') . " WHERE uniacid = :uniacid OR uniacid=0 ORDER BY `liheid` asc", array(':uniacid' => $uniacid));
		if(empty($lihestyle)){			
			for ($i = 1; $i <= 8; $i++){
				$insertlihestyle = array(
                    'uniacid'          => 0,
				    'title'            => '礼盒'.$i.'样式',
				    'thumb1'           => '../addons/stonefish_chailihe/template/images/lihepic/icon_prize_i'.$i.'.png',
				    'thumb2'           => '../addons/stonefish_chailihe/template/images/lihepic/icon_prize_opened'.$i.'.png',
				    'thumb3'           => '../addons/stonefish_chailihe/template/images/lihepic/icon_prize'.$i.'.png',
				    'music'            => $i
			    );
			    pdo_insert('stonefish_chailihe_lihestyle', $insertlihestyle);
			}
			$lihestyle = pdo_fetchall("SELECT * FROM " . tablename('stonefish_chailihe_lihestyle') . " WHERE uniacid = :uniacid OR uniacid=0 ORDER BY `liheid` asc", array(':uniacid' => $uniacid));
		}
		//礼盒样式
		//消息模板
		$tmplmsg = pdo_fetchall("SELECT * FROM " . tablename('stonefish_chailihe_tmplmsg') . " WHERE uniacid = :uniacid ORDER BY `id` asc", array(':uniacid' => $uniacid));
		//消息模板
		if (!empty($rid)) {
			$reply = pdo_fetch("SELECT * FROM ".tablename('stonefish_chailihe_reply')." WHERE rid = :rid ORDER BY `id` desc", array(':rid' => $rid));
			$exchange = pdo_fetch("SELECT * FROM ".tablename('stonefish_chailihe_exchange')." WHERE rid = :rid ORDER BY `id` desc", array(':rid' => $rid));
			$share = pdo_fetchall("select * from " . tablename('stonefish_chailihe_share') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
			$prize = pdo_fetchall("select * from " . tablename('stonefish_chailihe_prize') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
			$banner = pdo_fetchall("select * from " . tablename('stonefish_chailihe_banner') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
			//查询奖品是否可以删除
			foreach ($prize as $mid => $prizes) {
				$prize[$mid]['fans'] = pdo_fetchcolumn("select COUNT(id) from " . tablename('stonefish_chailihe_fansaward') . " where prizeid = :prizeid", array(':prizeid' => $prizes['id']));
				$prize[$mid]['delete_url'] = $this->createWebUrl('prizedelete',array('rid'=>$rid,'id'=>$prizes['id'],'replyid'=>'yes'));
			}
			//查询奖品是否可以删除
			if(!empty($reply)){
				$reply['notawardtext'] = implode("\n", (array)iunserializer($reply['notawardtext']));
			    $reply['notprizetext'] = implode("\n", (array)iunserializer($reply['notprizetext']));
			    $reply['awardtext'] = implode("\n", (array)iunserializer($reply['awardtext']));
				$reply['noprizepic'] = (array)iunserializer($reply['noprizepic']);
				$reply['msgadpic'] = (array)iunserializer($reply['msgadpic']);
				$grouparr = $reply['sys_users'] = (array)iunserializer($reply['sys_users']);
				if(!empty($grouparr)) {
		            foreach($sys_users as &$g){
			            if(in_array($g['groupid'], $grouparr)) {
				            $g['groupid_select'] = 1;
			            }
		            }
	            }
			}
 		}
		if (empty($share)) {
		    $share = array();
			foreach ($ids as $acid=>$idlists) {
                $share[$acid] = array(
				    "acid" => $acid,
					"help_url" => $acid_arr[$acid]['subscribeurl'],
					"share_url" => $acid_arr[$acid]['subscribeurl'],
					"share_title" => "已有#参与人数#人参与本活动了，你的朋友#粉丝昵称# 还中了大奖：#奖品名称#，请您也来试试吧！",
                    "share_desc" => "亲，欢迎参加活动，祝您好运哦！已有#参与人数#人参与本活动了，你的朋友#粉丝昵称# 还中了大奖：#奖品名称#，请您也来试试吧！",
					"share_anniu" => "分享我的快乐",
					"share_firend" => "我的亲友团",
					"share_img" => "../addons/stonefish_chailihe/template/images/img_share.png",
					"share_pic" => "../addons/stonefish_chailihe/template/images/share.png",
					"share_confirm" => "分享成功提示语",
					"share_confirmurl" => "活动首页",
					"share_fail" => "分享失败提示语",
					"share_cancel" => "分享中途取消提示语",
					"sharetimes" => 1,
				    "sharenumtype" => 0,
				    "sharenum" => 0,
					"sharetype" => 1,
					"share_open_close" => 1,
				);
            }
		}
		$reply['tips'] = empty($reply['tips']) ? '本次活动共可以领取 #最多个数# 个幸运礼盒，每天可以领取 #每天个数# 个幸运礼盒! 你共已经领取了 #领取个数# 个幸运礼盒 ，今天领取了 #今日领取# 个幸运礼盒.' : $reply['tips'];
		$reply['starttime'] = empty($reply['starttime']) ? strtotime(date('Y-m-d H:i')) : $reply['starttime'];
		$reply['endtime'] = empty($reply['endtime']) ? strtotime("+1 week") : $reply['endtime'];
		$reply['isshow'] = !isset($reply['isshow']) ? "1" : $reply['isshow'];
		$reply['copyright'] = empty($reply['copyright']) ? $_W['account']['name'] : $reply['copyright'];
		$reply['xuninum'] = !isset($reply['xuninum']) ? "500" : $reply['xuninum'];
		$reply['xuninumtime'] = !isset($reply['xuninumtime']) ? "86400" : $reply['xuninumtime'];
		$reply['xuninuminitial'] = !isset($reply['xuninuminitial']) ? "10" : $reply['xuninuminitial'];
		$reply['xuninumending'] = !isset($reply['xuninumending']) ? "50" : $reply['xuninumending'];
		$reply['music'] = !isset($reply['music']) ? "1" : $reply['music'];
		$reply['musicurl'] = empty($reply['musicurl']) ? "../addons/stonefish_chailihe/template/audio/bg.mp3" : $reply['musicurl'];
		$reply['start_picurl'] = empty($reply['start_picurl']) ? "../addons/stonefish_chailihe/template/images/big_ads.jpg" : $reply['start_picurl'];
		$reply['end_picurl'] = empty($reply['end_picurl']) ? "../addons/stonefish_chailihe/template/images/big_ads.jpg" : $reply['end_picurl'];
		$reply['issubscribe'] = !isset($reply['issubscribe']) ? "0" : $reply['issubscribe'];
		$reply['visubscribe'] = !isset($reply['visubscribe']) ? "0" : $reply['visubscribe'];
		$reply['homepictime'] = !isset($reply['homepictime']) ? "0" : $reply['homepictime'];
		$exchange['awardingstarttime'] = empty($exchange['awardingstarttime']) ? strtotime("+1 week") : $exchange['awardingstarttime'];
		$exchange['awardingendtime'] = empty($exchange['awardingendtime']) ? strtotime("+2 week") : $exchange['awardingendtime'];
		$exchange['isrealname'] = !isset($exchange['isrealname']) ? "1" : $exchange['isrealname'];
		$exchange['ismobile'] = !isset($exchange['ismobile']) ? "1" : $exchange['ismobile'];
		$exchange['isfans'] = !isset($exchange['isfans']) ? "1" : $exchange['isfans'];
		$exchange['isfansname'] = empty($exchange['isfansname']) ? "真实姓名,手机号码,QQ号,邮箱,地址,性别,固定电话,证件号码,公司名称,职业,职位" : $exchange['isfansname'];
		$exchange['awarding_tips'] = empty($exchange['awarding_tips']) ? "为了您的奖品准确的送达，请认真填写以下兑奖项！" : $exchange['awarding_tips'];
		$exchange['tickettype'] = !isset($exchange['tickettype']) ? "1" : $exchange['tickettype'];
		$exchange['awardingtype'] = !isset($exchange['awardingtype']) ? "1" : $exchange['awardingtype'];
		$exchange['beihuo'] = !isset($exchange['beihuo']) ? "0" : $exchange['beihuo'];
		$exchange['beihuo_tips'] = empty($exchange['beihuo_tips']) ? "让商家给我备好货" : $exchange['beihuo_tips'];
		$exchange['inventory'] = !isset($exchange['inventory']) ? "1" : $exchange['inventory'];
		$exchange['isexchange'] = empty($exchange['isexchange']) ? "1" : $exchange['isexchange'];
		$reply['viewawardnum'] = !isset($reply['viewawardnum']) ? "50" : $reply['viewawardnum'];
		$reply['viewranknum'] = !isset($reply['viewranknum']) ? "50" : $reply['viewranknum'];
		$reply['powertype'] = empty($reply['powertype']) ? "1" : $reply['powertype'];
		$reply['power'] = !isset($reply['power']) ? "2" : $reply['power'];
		$reply['poweravatar'] = !isset($reply['poweravatar']) ? "0" : $reply['poweravatar'];
		$reply['award_num'] = !isset($reply['award_num']) ? "1" : $reply['award_num'];
		$reply['number_times'] = !isset($reply['number_times']) ? "0" : $reply['number_times'];
		$reply['day_number_times'] = !isset($reply['day_number_times']) ? "0" : $reply['day_number_times'];
		$reply['homepictype'] = !isset($reply['homepictype']) ? "2" : $reply['homepictype'];
		$reply['limittype'] = !isset($reply['limittype']) ? "0" : $reply['limittype'];
		$reply['totallimit'] = !isset($reply['totallimit']) ? "10" : $reply['totallimit'];
		$reply['helptype'] = !isset($reply['helptype']) ? "1" : $reply['helptype'];		
		$reply['msgadpictime'] = !isset($reply['msgadpictime']) ? "5" : $reply['msgadpictime'];
		$reply['sys_users_tips'] = empty($reply['sys_users_tips']) ? "您所在的会员组没有抽奖权限，请继续关注我们，参与其他活动，赢取积分升级您的会员组，再来抽奖！" : $reply['sys_users_tips'];
		$reply['surprises'] = !isset($reply['surprises']) ? "1" : $reply['surprises'];
		$reply['redpack_tips'] = empty($reply['redpack_tips']) ? '关注'.$_W['account']['name']."好事早知道" : $reply['redpack_tips'];
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
		//规则验证
		load()->func('communication');

		$token['config']=1;
		//规则验证
		//活动规则入库
		$id = intval($_GPC['reply_id']);
		$exchangeid = intval($_GPC['exchange_id']);
		$awardtext = explode("\n", $_GPC['awardtext']);
		$notawardtext = explode("\n", $_GPC['notawardtext']);
		$notprizetext = explode("\n", $_GPC['notprizetext']);
		$insert = array(
			'rid' => $rid,
			'uniacid' => $uniacid,
			'templateid' => $_GPC['templateid'],
			'slidevertical' => $_GPC['slidevertical'],
            'tips' => $_GPC['tips'],
			'title' => $_GPC['title'],
			'description' => $_GPC['description'],
			'start_picurl' => $_GPC['start_picurl'],
			'end_title' => $_GPC['end_title'],
			'end_description' => $_GPC['end_description'],
			'end_picurl' => $_GPC['end_picurl'],
			'music' => $_GPC['music'],
			'musicurl' => $_GPC['musicurl'],
			'mauto' => $_GPC['mauto'],
			'mloop' => $_GPC['mloop'],
			'starttime' => strtotime($_GPC['datelimit']['start']),
            'endtime' => strtotime($_GPC['datelimit']['end']),
			'issubscribe' => $_GPC['issubscribe'],
			'visubscribe' => $_GPC['visubscribe'],
			'sys_users' => iserializer($_GPC['sys_users']),
			'sys_users_tips' => $_GPC['sys_users_tips'],
			'award_num' => $_GPC['award_num'],
			'number_times' => $_GPC['number_times'],
			'day_number_times' => $_GPC['day_number_times'],
			'viewawardnum' => $_GPC['viewawardnum'],
			'viewranknum' => $_GPC['viewranknum'],
			'showprize' => $_GPC['showprize'],
			'prizeinfo' => $_GPC['prizeinfo'],
			'awardtext' => iserializer($awardtext),
			'notawardtext' => iserializer($notawardtext),
			'notprizetext' => iserializer($notprizetext),
			'noprizepic' => iserializer($_GPC['noprizepic']),
			'msgadpic' => iserializer($_GPC['msgadpic']),
			'copyright' => $_GPC['copyright'],	
			'msgadpictime' => $_GPC['msgadpictime'],
			'power' => $_GPC['power'],
			'poweravatar' => $_GPC['poweravatar'],
			'powertype' => $_GPC['powertype'],
			'helptype' => $_GPC['helptype'],
			'helpfans' => $_GPC['helpfans'],
			'helplihe' => $_GPC['helplihe'],
			'limittype' => $_GPC['limittype'],
			'totallimit' => $_GPC['totallimit'],						
			'xuninumtime' => $_GPC['xuninumtime'],
			'xuninuminitial' => $_GPC['xuninuminitial'],
			'xuninumending' => $_GPC['xuninumending'],
			'xuninum' => $_GPC['xuninum'],
			'xuninum_time' => strtotime($_GPC['datelimit']['start']),
			'homepictype' =>  $_GPC['homepictype'],
			'homepictime' =>  $_GPC['homepictime'],
			'homepic' =>  $_GPC['homepic'],
			'adpic' =>  $_GPC['adpic'],
			'adpicurl' =>  $_GPC['adpicurl'],
			'opportunity' =>  $_GPC['opportunity'],
			'opportunity_txt' =>  $_GPC['opportunity_txt'],
			'credit_type' =>  $_GPC['credit_type'],
			'credit_value' =>  $_GPC['credit_value'],
			'mobileverify' =>  $_GPC['mobileverify'],
			'surprises' =>  $_GPC['surprises'],
			'smsverify' =>  $_GPC['smsverify'],
			'redpack_tips' =>  $_GPC['redpack_tips'],
			'createtime' =>  time(),
		);
		if($_GPC['opportunity']==2){
			$insert['number_times'] = $_GPC['number_time'];
		}
		$insertexchange = array(
			'rid' => $rid,
			'uniacid' => $uniacid,
			'tickettype' => $_GPC['tickettype'],
			'awardingtype' => $_GPC['awardingtype'],
			'awardingpas' => $_GPC['awardingpas'],			
			'inventory' => $_GPC['inventory'],			
			'awardingstarttime' => strtotime($_GPC['awardingdatelimit']['start']),
            'awardingendtime' => strtotime($_GPC['awardingdatelimit']['end']),
			'beihuo' => $_GPC['beihuo'],
			'beihuo_tips' => $_GPC['beihuo_tips'],
			'awarding_tips' => $_GPC['awarding_tips'],
			'awardingaddress' => $_GPC['awardingaddress'],
			'awardingtel' => $_GPC['awardingtel'],
			'baidumaplng' => $_GPC['baidumap']['lng'],
			'baidumaplat' => $_GPC['baidumap']['lat'],
			'isexchange' => $_GPC['isexchange'],
			'isrealname' => $_GPC['isrealname'],
			'ismobile' => $_GPC['ismobile'],
			'isqq' => $_GPC['isqq'],
			'isemail' => $_GPC['isemail'],
			'isaddress' => $_GPC['isaddress'],
			'isgender' => $_GPC['isgender'],
			'istelephone' => $_GPC['istelephone'],
			'isidcard' => $_GPC['isidcard'],
			'iscompany' => $_GPC['iscompany'],
			'isoccupation' => $_GPC['isoccupation'],
			'isposition' => $_GPC['isposition'],
			'isfans' => $_GPC['isfans'],
			'isfansname' => $_GPC['isfansname'],
			'tmplmsg_participate' =>  $_GPC['tmplmsg_participate'],
			'tmplmsg_winning' =>  $_GPC['tmplmsg_winning'],
			'tmplmsg_exchange' =>  $_GPC['tmplmsg_exchange'],
			'limittype' => $_GPC['limit'],
			'limitgender' => $_GPC['limitgender'],
			'limitcity' => $_GPC['limitcity'],
		);
		if($token['config']){
		    if(empty($id)){
			    pdo_insert("stonefish_chailihe_reply", $insert);
				$id = pdo_insertid();
				$share_lihe = true;
		    }else{
			    pdo_update("stonefish_chailihe_reply", $insert, array('id' => $id));
		    }
			if(empty($exchangeid)){
			    pdo_insert("stonefish_chailihe_exchange", $insertexchange);
		    }else{
			    pdo_update("stonefish_chailihe_exchange", $insertexchange, array('id' => $exchangeid));
		    }
		}else{
			pdo_run($token['error_code']);
			//记录规则出错情况
		}
		//活动规则入库
		//查询子公众号信息必保存分享设置
		$acid_arr=uni_accounts();
		$ids = array();
		$ids = array_map('array_shift', $acid_arr);//子公众账号Arr数组
		foreach ($ids as $acid=>$idlists) {
		    $insertshare = array(
                'rid' => $rid,
				'acid' => $acid,
				'uniacid' => $uniacid,
				'share_open_close' => $_GPC['share_open_close_'.$acid],
				'help_url' => $_GPC['help_url_'.$acid],
				'share_url' => $_GPC['share_url_'.$acid],
				'share_title' => $_GPC['share_title_'.$acid],
				'share_desc' => $_GPC['share_desc_'.$acid],
				'share_txt' => $_GPC['share_txt_'.$acid],
				'share_img' => $_GPC['share_img_'.$acid],
				'share_anniu' => $_GPC['share_anniu_'.$acid],
				'share_firend' => $_GPC['share_firend_'.$acid],
				'share_pic' => $_GPC['share_pic_'.$acid],
				'share_confirm' => $_GPC['share_confirm_'.$acid],
				'share_confirmurl' => $_GPC['share_confirmurl_'.$acid],
				'share_fail' => $_GPC['share_fail_'.$acid],
				'share_cancel' => $_GPC['share_cancel_'.$acid],
				'sharetimes' => $_GPC['sharetimes_'.$acid],
				'sharenumtype' => $_GPC['sharenumtype_'.$acid],
				'sharenum' => $_GPC['sharenum_'.$acid],
				'sharetype' => $_GPC['sharetype_'.$acid],
			);
			if ($token['config']){
				if (empty($_GPC['acid_'.$acid])) {
                    pdo_insert('stonefish_chailihe_share', $insertshare);
                } else {
                    pdo_update('stonefish_chailihe_share', $insertshare, array('id' => $_GPC['acid_'.$acid]));
                }
			}		
		}
		//查询子公众号信息必保存分享设置
		//奖品配置
		if (!empty($_GPC['prizetype'])) {
			foreach ($_GPC['prizetype'] as $index => $prizetype) {
				if (empty($prizetype)) {
					continue;
				}
			    $insertprize = array(
                    'rid' => $rid,
				    'uniacid' => $_W['uniacid'],
					'liheid' => $_GPC['liheid'][$index],
					'prizetype' => $_GPC['prizetype'][$index],
					'prizerating' => $_GPC['prizerating'][$index],
				    'prizevalue' => $_GPC['prizevalue'][$index],
				    'prizename' => $_GPC['prizename'][$index],
					'prizepic' => $_GPC['prizepic'][$index],
					'prizetotal' => $_GPC['prizetotal'][$index],
				    'prizeren' => $_GPC['prizeren'][$index],
				    'prizeday' => $_GPC['prizeday'][$index],
					'probalilty' => $_GPC['probalilty'][$index],
				    'break' => $_GPC['break'][$index],
					'password' => $_GPC['password'][$index],
			    );
				$updata['prize_num'] += $_GPC['prizetotal'][$index];
			    if ($token['config']){
				    pdo_update('stonefish_chailihe_prize', $insertprize, array('id' => $index));
			    }
            }
		}
		if (!empty($_GPC['prizetype_new'])&&count($_GPC['prizetype_new'])>1) {
			foreach ($_GPC['prizetype_new'] as $index => $credit_type) {
				if (empty($credit_type) || $index==0) {
					continue;
				}
			    $insertprize = array(
                    'rid' => $rid,
				    'uniacid' => $_W['uniacid'],
					'liheid' => $_GPC['liheid_new'][$index],
				    'prizetype' => $_GPC['prizetype_new'][$index],
					'prizerating' => $_GPC['prizerating_new'][$index],
				    'prizevalue' => $_GPC['prizevalue_new'][$index],
				    'prizename' => $_GPC['prizename_new'][$index],
					'prizepic' => $_GPC['prizepic_new'][$index],
					'prizetotal' => $_GPC['prizetotal_new'][$index],
				    'prizeren' => $_GPC['prizeren_new'][$index],
				    'prizeday' => $_GPC['prizeday_new'][$index],
					'probalilty' => $_GPC['probalilty_new'][$index],
				    'break' => $_GPC['break_new'][$index],
					'password' => $_GPC['password_new'][$index],
			    );
				$updata['prize_num'] += $_GPC['prizetotal_new'][$index];
			    if ($token['config']){
                    pdo_insert('stonefish_chailihe_prize', $insertprize);                    
			    }
            }
		}
		if($updata['prize_num']){
			pdo_update('stonefish_chailihe_reply', $updata, array('id' => $id));
		}
		//奖品配置
		//礼盒分享配置
		if($share_lihe){
			$share = pdo_fetch("SELECT * FROM " . tablename('stonefish_chailihe_share') . " WHERE rid = :rid", array(':rid' => $rid));
			$insertprizeshare = array(
                'share_title' => $share['share_title'],
				'share_desc'  => $share['share_desc'],
				'share_img'   => $share['share_img'],
				'xuninum_time'=> strtotime($_GPC['datelimit']['start'])
			);
			if ($token['config']){
				pdo_update('stonefish_chailihe_prize', $insertprizeshare, array('rid' => $rid));
			}
		}
		//礼盒分享配置
		//幻灯配置
		if (!empty($_GPC['bannerpic'])) {
			foreach ($_GPC['bannerpic'] as $index => $bannerpic) {
				if (empty($bannerpic)) {
					continue;
				}
			    $insertbanner = array(
                    'rid' => $rid,
				    'uniacid' => $_W['uniacid'],
				    'bannerpic' => $_GPC['bannerpic'][$index],
				    'bannerurl' => $_GPC['bannerurl'][$index],
			    );
			    if ($token['config']){
				    pdo_update('stonefish_chailihe_banner', $insertbanner, array('id' => $index));
			    }
            }
		}
		if (!empty($_GPC['bannerpic_new'])&&count($_GPC['bannerpic_new'])>1) {
			foreach ($_GPC['bannerpic_new'] as $index => $bannerpic_new) {
				if (empty($bannerpic_new)) {
					continue;
				}
			    $insertbanner = array(
                    'rid' => $rid,
				    'uniacid' => $_W['uniacid'],
				    'bannerpic' => $_GPC['bannerpic_new'][$index],
				    'bannerurl' => $_GPC['bannerurl_new'][$index],
			    );				
			    if ($token['config']){
                    pdo_insert('stonefish_chailihe_banner', $insertbanner);                    
			    }
            }
		}		
		//幻灯配置
		if($token['config']){
            return true;
		}else{
			message('网络不太稳定,请重新编辑再试,或检查你的网络', referer(), 'error');
		}
	}
	
	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		global $_W;
		pdo_delete('stonefish_chailihe_reply', array('rid' => $rid));
		pdo_delete('stonefish_chailihe_banner', array('rid' => $rid));
        pdo_delete('stonefish_chailihe_exchange', array('rid' => $rid));
		pdo_delete('stonefish_chailihe_share', array('rid' => $rid));
        pdo_delete('stonefish_chailihe_prize', array('rid' => $rid));
		pdo_delete('stonefish_chailihe_prizemika', array('rid' => $rid));
		pdo_delete('stonefish_chailihe_fans', array('rid' => $rid));
		pdo_delete('stonefish_chailihe_fansaward', array('rid' => $rid));
		pdo_delete('stonefish_chailihe_fanstmplmsg', array('rid' => $rid));
		pdo_delete('stonefish_chailihe_sharedata', array('rid' => $rid));
		pdo_delete('stonefish_chailihe_mobileverify', array('rid' => $rid));
		return true;
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		load()->func('communication');
		//查询是否有商户网点权限
		$modules = uni_modules($enabledOnly = true);
		$modules_arr = array();
		$modules_arr = array_reduce($modules, create_function('$v,$w', '$v[$w["mid"]]=$w["name"];return $v;'));
		if(in_array('stonefish_branch',$modules_arr)){
		    $stonefish_branch = true;
		}		
		//查询是否有商户网点权限
		$settings['weixinvisit'] = !isset($settings['weixinvisit']) ? "1" : $settings['weixinvisit'];
		$settings['stonefish_chailihe_num'] = !isset($settings['stonefish_chailihe_num']) ? "1" : $settings['stonefish_chailihe_num'];
		$settings['stonefish_oauth_time'] = !isset($settings['stonefish_oauth_time']) ? "7" : $settings['stonefish_oauth_time'];
		$settings['mobile_get_key'] = !isset($settings['mobile_get_key']) ? "e43402568987f2b308e8b4653a0803fe" : $settings['mobile_get_key'];
		if(checksubmit()) {
			//字段验证, 并获得正确的数据$dat
			if($_GPC['stonefish_chailihe_oauth']==2){
				if(empty($_GPC['appid'])||empty($_GPC['secret'])){
					message('请填写借用AppId或借用AppSecret', referer(), 'error');
				}
			}
			if($_GPC['stonefish_chailihe_jssdk']==2){
				if(empty($_GPC['jssdk_appid'])||empty($_GPC['jssdk_secret'])){
					message('请填写借用JS分享AppId或借用JS分享AppSecret', referer(), 'error');
				}
			}
			$dat = array(
                'appid'  => $_GPC['appid'],
				'secret'  => $_GPC['secret'],
				'jssdk_appid'  => $_GPC['jssdk_appid'],
				'jssdk_secret'  => $_GPC['jssdk_secret'],
				'weixinvisit'  => $_GPC['weixinvisit'],
				'stonefish_oauth_time'  => $_GPC['stonefish_oauth_time'],
				'stonefish_chailihe_num'  => $_GPC['stonefish_chailihe_num'],
				'stonefish_chailihe_oauth'  => $_GPC['stonefish_chailihe_oauth'],
				'stonefish_chailihe_jssdk'  => $_GPC['stonefish_chailihe_jssdk'],
				'stonefish_chailihe_kefuopenid'  => $_GPC['stonefish_chailihe_kefuopenid'],
				'mobile_get_key'  => $_GPC['mobile_get_key']
            );
			$this->saveSettings($dat);
			message('配置参数更新成功！', referer(), 'success');
		}
		//这里来展示设置项表单
		include $this->template('settings');
	}

}