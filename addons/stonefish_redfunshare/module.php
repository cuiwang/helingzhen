<?php
/**
 * 模块定义：规则保存
 */
defined('IN_IA') or exit('Access Denied');

class stonefish_redfunshareModule extends WeModule {

	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W;
		load()->func('tpl');
		$uniacid = $_W['uniacid'];
		//查询是否填写系统参数
		$setting = $this->module['config'];
		if(empty($setting)){
			message('抱歉，系统参数没有填写，请先填写系统参数！', url('profile/module/setting',array('m' => 'stonefish_redfunshare')), 'error');
		}
		//查询是否填写系统参数
		//查询是否有商户网点
		$modules = uni_modules($enabledOnly = true);
		$modules_arr = array();
		$modules_arr = array_reduce($modules, create_function('$v,$w', '$v[$w["mid"]]=$w["name"];return $v;'));
		if(in_array('stonefish_branch',$modules_arr)){
		    $stonefish_branch = true;
		}		
		//查询是否有商户网点
		//积分类型
		$creditnames = array();
		$unisettings = uni_setting($uniacid, array('creditnames'));
		foreach ($unisettings['creditnames'] as $key=>$credit) {
			if (!empty($credit['enabled'])) {
				$creditnames[$key] = $credit['title'];
			}
		}
		//积分类型
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
		$template = pdo_fetchall("SELECT * FROM " . tablename('stonefish_redfunshare_template') . " WHERE uniacid = :uniacid or uniacid=0 ORDER BY `id` asc", array(':uniacid' => $uniacid));
		if(empty($template)){			
			$inserttemplate = array(
                'uniacid'          => 0,
				'title'            => '默认',
				'thumb'            => '../addons/stonefish_redfunshare/template/images/template.jpg',
				'fontsize'         => '12',
				'bgimg'            => '../addons/stonefish_redfunshare/template/images/bg.png',
				'bgcolor'          => '#f5cd47',
				'textcolor'        => '#ffffff',
				'textcolorlink'    => '#f3f3f3',
				'buttoncolor'      => '#e70012',
				'buttontextcolor'  => '#ffffff',
				'rulecolor'        => '#ffeca9',
				'ruletextcolor'    => '#434343',
				'navcolor'         => '#e70012',
				'navtextcolor'     => '#ffffff',
				'navactioncolor'   => '#ff0000',
				'watchcolor'       => '#f5f0eb',
				'watchtextcolor'   => '#717171',
				'awardcolor'       => '#ffc000',
				'awardtextcolor'   => '#ffffff',
				'awardscolor'      => '#b7b7b7',
				'awardstextcolor'  => '#434343',
			);
			pdo_insert('stonefish_redfunshare_template', $inserttemplate);
			$template = pdo_fetchall("SELECT * FROM " . tablename('stonefish_redfunshare_template') . " WHERE uniacid = :uniacid or uniacid=0 ORDER BY `id` asc", array(':uniacid' => $uniacid));
		}
		//活动模板
		//消息模板
		$tmplmsg = pdo_fetchall("SELECT * FROM " . tablename('stonefish_redfunshare_tmplmsg') . " WHERE uniacid = :uniacid ORDER BY `id` asc", array(':uniacid' => $uniacid));
		//消息模板
		if (!empty($rid)) {
			$reply = pdo_fetch("SELECT * FROM ".tablename('stonefish_redfunshare_reply')." WHERE rid = :rid ORDER BY `id` desc", array(':rid' => $rid));
			$exchange = pdo_fetch("SELECT * FROM ".tablename('stonefish_redfunshare_exchange')." WHERE rid = :rid ORDER BY `id` desc", array(':rid' => $rid));
			$share = pdo_fetchall("select * from " . tablename('stonefish_redfunshare_share') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
			//是否有人申请兑换
		    $fansaward = pdo_fetch("SELECT id FROM " . tablename('stonefish_redfunshare_fansaward') . " WHERE rid = :rid ORDER BY `id` asc", array('rid'=>$rid));
		    //是否有人申请兑换
			if(!empty($reply)){
				$reply['msgadpic'] = (array)iunserializer($reply['msgadpic']);
				$reply['homepic'] = (array)iunserializer($reply['homepic']);
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
					"share_title" => "我正在参与分享得红包活动，攒足5元即可申请兑换了，请您也来试试吧！",
                    "share_desc" => "亲，欢迎参加活动，祝您好运哦！已有#参与人数#人参与本活动了，你的朋友#粉丝昵称# 等你一起参加！",
					"share_anniu" => "分享得红包",
					"share_anniufirend" => "帮好友分享",
					"share_firend" => "../addons/stonefish_redfunshare/template/images/sharef.png",
					"share_img" => "../addons/stonefish_redfunshare/template/images/img_share.png",
					"share_pic" => "../addons/stonefish_redfunshare/template/images/share.png",
					"share_confirm" => "分享成功提示语",
					"share_confirmurl" => "活动首页",
					"share_fail" => "分享失败提示语",
					"share_cancel" => "分享中途取消提示语",
					"share_open_close" => 1,
				);
            }
		}
		if (!$reply) {
            $reply = array(
                "title" => "攒人气送红包、流量、话费",
                "start_picurl" => "../addons/stonefish_redfunshare/template/images/start.jpg",
                "description" => "欢迎参加红包乐分享活动，攒人气就送红包、流量、话费",
                "repeat_lottery_reply" => "亲，继续努力哦~~",
                "end_title" => "攒人气送红包、流量、话费活动已经结束了",
                "end_description" => "亲，活动已经结束，请继续关注我们的后续活动哦~",
                "end_picurl" => "../addons/stonefish_redfunshare/template/images/end.jpg",
				"powertype" => 1,
				"redpack" => '红包',
				"adpic" => "../addons/stonefish_redfunshare/template/images/adpic.png",
				"toppic" => "../addons/stonefish_redfunshare/template/images/toppic.png",
				"helppic" => "../addons/stonefish_redfunshare/template/images/helppic.png",
				"duihuanpic" => "../addons/stonefish_redfunshare/template/images/duihuanpic.png",
				"myfanspic" => "../addons/stonefish_redfunshare/template/images/myfanspic.png",
				"msgadpictime" => 5,
            );
        }
		$reply['intips'] = empty($reply['intips']) ? '金额需 ≥ 50元才可以兑换，话费直接兑换为领取的手机号' : $reply['intips'];
		$reply['redpack_tips'] = empty($reply['redpack_tips']) ? '关注 '.$_W['account']['name'].' 好事早知道！' : $reply['redpack_tips'];
		$reply['starttime'] = empty($reply['starttime']) ? strtotime(date('Y-m-d H:i')) : $reply['starttime'];
		$reply['endtime'] = empty($reply['endtime']) ? strtotime("+1 week") : $reply['endtime'];
		$reply['isshow'] = !isset($reply['isshow']) ? "1" : $reply['isshow'];
		$reply['copyright'] = empty($reply['copyright']) ? $_W['account']['name'] : $reply['copyright'];
		$reply['xuninum'] = !isset($reply['xuninum']) ? "500" : $reply['xuninum'];
		$reply['xuninumtime'] = !isset($reply['xuninumtime']) ? "86400" : $reply['xuninumtime'];
		$reply['xuninuminitial'] = !isset($reply['xuninuminitial']) ? "10" : $reply['xuninuminitial'];
		$reply['xuninumending'] = !isset($reply['xuninumending']) ? "50" : $reply['xuninumending'];
		$reply['music'] = !isset($reply['music']) ? "1" : $reply['music'];
		$reply['musicurl'] = empty($reply['musicurl']) ? "../addons/stonefish_redfunshare/template/audio/bg.mp3" : $reply['musicurl'];
		$reply['issubscribe'] = !isset($reply['issubscribe']) ? "1" : $reply['issubscribe'];
		$reply['visubscribe'] = !isset($reply['visubscribe']) ? "0" : $reply['visubscribe'];
		$reply['visubscribetime'] = !isset($reply['visubscribetime']) ? "5" : $reply['visubscribetime'];
		$reply['homepictime'] = !isset($reply['homepictime']) ? "0" : $reply['homepictime'];
		$reply['viewawardnum'] = !isset($reply['viewawardnum']) ? "50" : $reply['viewawardnum'];
		$reply['viewranknum'] = !isset($reply['viewranknum']) ? "50" : $reply['viewranknum'];
		$reply['power'] = !isset($reply['power']) ? "2" : $reply['power'];
		$reply['poweravatar'] = !isset($reply['poweravatar']) ? "0" : $reply['poweravatar'];
		$reply['homepictype'] = !isset($reply['homepictype']) ? "2" : $reply['homepictype'];
		$reply['limittype'] = !isset($reply['limittype']) ? "0" : $reply['limittype'];
		$reply['totallimit'] = !isset($reply['totallimit']) ? "10" : $reply['totallimit'];
		$reply['helptype'] = !isset($reply['helptype']) ? "1" : $reply['helptype'];
		$reply['sys_users_tips'] = empty($reply['sys_users_tips']) ? "您所在的会员组没有参与权限，请继续关注我们，参与其他活动，赢取积分升级您的会员组，再来参与！" : $reply['sys_users_tips'];
		$reply['inpointstart'] = !isset($reply['inpointstart']) ? "10" : $reply['inpointstart'];
		$reply['inpointend'] = !isset($reply['inpointend']) ? "20" : $reply['inpointend'];
		$reply['randompointstart'] = !isset($reply['randompointstart']) ? "10" : $reply['randompointstart'];
		$reply['randompointend'] = !isset($reply['randompointend']) ? "15" : $reply['randompointend'];
		$reply['addp'] = !isset($reply['addp']) ? "100" : $reply['addp'];
		$reply['prize_num'] = !isset($reply['prize_num']) ? "10000" : $reply['prize_num'];
		$reply['award_num'] = !isset($reply['award_num']) ? "1" : $reply['award_num'];
		$reply['homeanniu'] = !isset($reply['homeanniu']) ? "点击参与抢红包" : $reply['homeanniu'];
		$reply['lingquanniu'] = !isset($reply['lingquanniu']) ? "领取红包" : $reply['lingquanniu'];
		$reply['lingquanniutips'] = !isset($reply['lingquanniutips']) ? "抢到".$_W['account']['name']."送出的#价值#红包" : $reply['lingquanniutips'];
		$reply['helptips'] = !isset($reply['helptips']) ? "我已经抢到了#价值#元红包，帮我凑足#最小提现#元，我就能兑换了！" : $reply['helptips'];
		$reply['helpanniu'] = !isset($reply['helpanniu']) ? "帮TA凑一笔" : $reply['helpanniu'];
		$reply['danwei'] = !isset($reply['danwei']) ? "元" : $reply['danwei'];
		$reply['sharepoint'] = !isset($reply['sharepoint']) ? "50" : $reply['sharepoint'];
		$reply['maxsharepoint'] = !isset($reply['maxsharepoint']) ? "500" : $reply['maxsharepoint'];
		$reply['redpackv'] = !isset($reply['redpackv']) ? "2" : $reply['redpackv'];
		$reply['acthelp'] = empty($reply['acthelp']) ? "凑" : $reply['acthelp'];
		$exchange['awardingstarttime'] = empty($exchange['awardingstarttime']) ? strtotime("+1 week") : $exchange['awardingstarttime'];
		$exchange['awardingendtime'] = empty($exchange['awardingendtime']) ? strtotime("+2 week") : $exchange['awardingendtime'];
		$exchange['isrealname'] = !isset($exchange['isrealname']) ? "1" : $exchange['isrealname'];
		$exchange['ismobile'] = !isset($exchange['ismobile']) ? "1" : $exchange['ismobile'];
		$exchange['isfans'] = !isset($exchange['isfans']) ? "2" : $exchange['isfans'];
		$exchange['isfansname'] = empty($exchange['isfansname']) ? "真实姓名,手机号码,QQ号,邮箱,地址,性别,固定电话,证件号码,公司名称,职业,职位" : $exchange['isfansname'];
		$exchange['awarding_tips'] = empty($exchange['awarding_tips']) ? "输入手机号，赶快领取吧" : $exchange['awarding_tips'];
		$exchange['warning_tips'] = empty($exchange['warning_tips']) ? "上述信息仅供您申请兑换后运营商核对身份使用，XX与您的运营商将严格履行保密义务，请务必确保您提供的信息真实，以免充值失败。" : $exchange['warning_tips'];
		$exchange['limitwelfare'] = !isset($exchange['limitwelfare']) ? "100" : $exchange['limitwelfare'];

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
		$token['config'] = 1;
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
			'prize_num' => $_GPC['prize_num'],
			'redpack_meun' => $_GPC['redpack_meun'],			
			'award_num' => $_GPC['award_num'],
			'viewawardnum' => $_GPC['viewawardnum'],
			'viewranknum' => $_GPC['viewranknum'],
			'intips' => $_GPC['intips'],
			'msgadpic' => iserializer($_GPC['msgadpic']),
			'copyright' => $_GPC['copyright'],
			'helptel' => $_GPC['helptel'],	
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
			'homepic' =>  iserializer($_GPC['homepic']),
			'adpic' =>  $_GPC['adpic'],
			'toppic' =>  $_GPC['toppic'],
			'helppic' =>  $_GPC['helppic'],
			'duihuanpic' =>  $_GPC['duihuanpic'],
			'myfanspic' =>  $_GPC['myfanspic'],
			'mobileverify' =>  $_GPC['mobileverify'],
			'smsverify' =>  $_GPC['smsverify'],
			'sharepoint' => $_GPC['sharepoint'],
			'maxsharepoint' => $_GPC['maxsharepoint'],
			'inpointstart' =>  $_GPC['inpointstart'],
			'inpointend' =>  $_GPC['inpointend'],
			'randompointstart' =>  $_GPC['randompointstart'],
			'randompointend' =>  $_GPC['randompointend'],
			'addp' =>  $_GPC['addp'],
			'homeanniu' => $_GPC['homeanniu'],
			'lingquanniu' => $_GPC['lingquanniu'],
			'lingquanniutips' => $_GPC['lingquanniutips'],
			'helptips' => $_GPC['helptips'],
			'helpanniu' => $_GPC['helpanniu'],
			'danwei' => $_GPC['danwei'],
			'redpack' => $_GPC['redpack'],
			'acthelp' => $_GPC['acthelp'],			
			'redpackv' => $_GPC['redpackv'],
			'redpacktype' => $_GPC['redpacktype'],
			'seedredpack' => $_GPC['seedredpack'],
			'redpack_tips' => $_GPC['redpack_tips'],
			'createtime' =>  time(),
		);		
		$insertexchange = array(
			'rid' => $rid,
			'uniacid' => $uniacid,
			'awarding_tips' => $_GPC['awarding_tips'],
			'warning_tips' => $_GPC['warning_tips'],
			'yidong_tips' => $_GPC['yidong_tips'],
			'liantong_tips' => $_GPC['liantong_tips'],
			'dianxin_tips' => $_GPC['dianxin_tips'],
			'awardingstarttime' => strtotime($_GPC['awardingdatelimit']['start']),
            'awardingendtime' => strtotime($_GPC['awardingdatelimit']['end']),
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
			'tmplmsg_help' =>  $_GPC['tmplmsg_help'],
			'tmplmsg_exchange' =>  $_GPC['tmplmsg_exchange'],
			'limittype' => $_GPC['limit'],
			'limitgender' => $_GPC['limitgender'],
			'limitcity' => $_GPC['limitcity'],
			'limitwelfare' => $_GPC['limitwelfare'],			
		);
		if($token['config']){
		    if(empty($id)){
			    pdo_insert("stonefish_redfunshare_reply", $insert);
				$id = pdo_insertid();
		    }else{
			    pdo_update("stonefish_redfunshare_reply", $insert, array('id' => $id));
		    }
			if(empty($exchangeid)){
			    pdo_insert("stonefish_redfunshare_exchange", $insertexchange);
		    }else{
			    pdo_update("stonefish_redfunshare_exchange", $insertexchange, array('id' => $exchangeid));
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
				'share_anniufirend' => $_GPC['share_anniufirend_'.$acid],
				'share_firend' => $_GPC['share_firend_'.$acid],
				'share_pic' => $_GPC['share_pic_'.$acid],
				'share_confirm' => $_GPC['share_confirm_'.$acid],
				'share_confirmurl' => $_GPC['share_confirmurl_'.$acid],
				'share_fail' => $_GPC['share_fail_'.$acid],
				'share_cancel' => $_GPC['share_cancel_'.$acid],
			);
			if ($token['config']){
				if (empty($_GPC['acid_'.$acid])) {
                    pdo_insert('stonefish_redfunshare_share', $insertshare);
                } else {
                    pdo_update('stonefish_redfunshare_share', $insertshare, array('id' => $_GPC['acid_'.$acid]));
                }
			}		
		}
		//查询子公众号信息必保存分享设置
		if($token['config']){
            return true;
		}else{
			message('网络不太稳定,请重新编辑再试,或检查你的网络', referer(), 'error');
		}
	}
	
	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		global $_W;
		pdo_delete('stonefish_redfunshare_reply', array('rid' => $rid));
        pdo_delete('stonefish_redfunshare_exchange', array('rid' => $rid));
		pdo_delete('stonefish_redfunshare_share', array('rid' => $rid));
		pdo_delete('stonefish_redfunshare_fans', array('rid' => $rid));
		pdo_delete('stonefish_redfunshare_fansaward', array('rid' => $rid));
		pdo_delete('stonefish_redfunshare_fanstmplmsg', array('rid' => $rid));
		pdo_delete('stonefish_redfunshare_sharedata', array('rid' => $rid));
		pdo_delete('stonefish_redfunshare_mobileverify', array('rid' => $rid));
		return true;
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		$_W['page']['title'] = '红包乐分享参数设置';
		load()->func('communication');
		$settings['weixinvisit'] = !isset($settings['weixinvisit']) ? "1" : $settings['weixinvisit'];
		$settings['stonefish_oauth_time'] = !isset($settings['stonefish_oauth_time']) ? "1" : $settings['stonefish_oauth_time'];
		$settings['mobile_get_key'] = !isset($settings['mobile_get_key']) ? "e43402568987f2b308e8b4653a0803fe" : $settings['mobile_get_key'];
		if(checksubmit()) {
			//字段验证, 并获得正确的数据$dat
			if($_GPC['stonefish_redfunshare_oauth']==2){
				if(empty($_GPC['appid'])||empty($_GPC['secret'])){
					message('请填写借用AppId或借用AppSecret', referer(), 'error');
				}
			}
			if($_GPC['stonefish_redfunshare_jssdk']==2){
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
				'mobile_get_key'  => $_GPC['mobile_get_key'],
				'stonefish_oauth_time'  => $_GPC['stonefish_oauth_time'],
				'stonefish_redfunshare_oauth'  => $_GPC['stonefish_redfunshare_oauth'],
				'stonefish_redfunshare_jssdk'  => $_GPC['stonefish_redfunshare_jssdk'],
				'stonefish_redfunshare_kefuopenid'  => $_GPC['stonefish_redfunshare_kefuopenid']
            );
			$this->saveSettings($dat);
			message('配置参数更新成功！', referer(), 'success');
		}
		//这里来展示设置项表单
		include $this->template('settings');
	}

}