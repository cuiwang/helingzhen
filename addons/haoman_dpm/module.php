<?php
defined('IN_IA') or exit('Access Denied');
require_once '../framework/library/qrcode/phpqrcode.php';
define('ROOT_PATH', str_replace('module.php', '', str_replace('\\', '/', __FILE__)));
require_once ROOT_PATH."custom/custom.inc.php"; //引入定制判断文件

class haoman_dpmModule extends WeModule {
	public $tablenames = 'haoman_dpm_reply';
	public $tablename = 'haoman_dpm_prize';
		public function fieldsFormDisplay($rid = 0) {
		global $_W;
		global $_GPC;
		load()->func('tpl');

			$file_mob = '../addons/haoman_base/site.php';

			if(!file_exists($file_mob)){

				message('还为安装基础模块，请去下载安装！','http://s.we7.cc/module-3674.html','error');

			}


		$creditnames = array();
			$unisettings = uni_setting($uniacid, array('creditnames'));
			foreach ($unisettings['creditnames'] as $key=>$credit) {
				if (!empty($credit['enabled'])) {
					$creditnames[$key] = $credit['title'];
				}
			}
			if (!empty($rid)) {
				$reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
				$yyy = pdo_fetch("select * from " . tablename('haoman_dpm_yyyreply') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
				$xys = pdo_fetch("select * from " . tablename('haoman_dpm_xysreply') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
                $bp = pdo_fetch("select * from " . tablename('haoman_dpm_bpreply') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
                $video = pdo_fetch("select * from " . tablename('haoman_dpm_mp4') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
                $xyh = pdo_fetch("select * from " . tablename('haoman_dpm_xyhreply') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
                $cjx = pdo_fetch("select * from " . tablename('haoman_dpm_cjxreply') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
                $mb = pdo_fetch("select * from " . tablename('haoman_dpm_notifications') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
				$fanshb = pdo_fetch("select * from " . tablename('haoman_dpm_hb_setting') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
				$shouqian = pdo_fetch("select * from " . tablename('haoman_dpm_shouqianBase') . " where rid = :rid order by `id` asc", array(':rid' => $rid));



            }

			if(!$bp){
				$t = time();
				$start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
				$end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
				$bp = array(
					"bd_starttime" => $start,
					"bd_endtime" => $end,
				);
			}

         if(!$yyy){
             $yyy = array(
                 "yyy_pdbg" => "../addons/haoman_dpm/img10/car.png",
             );
         }

			if (!$reply) {
				$now = time();
				$reply = array(
					"title" => "",
					"start_picurl" => "",
					"backpicurl" => "",
					"most_num_times" => 0,
					"award_times" => 1,
					"show_num" => 0,
					"password" => 0,
					"share_acid" => 100,
					"credit1" => 1,
					"awardnum" => 50,
					"xf_condition" =>0,
					"show_type" =>0,
					"share_type" =>0,
					"is_openbbm" =>0,
					"is_sharetype" =>0,
					"is_indexshow_rule" =>0,
					"tx_most" =>500,
					"rank" =>1,
					"iscjnum" =>1,
					"isappkey" =>0,
					"end_hour" =>10,
					"start_hour" =>200,
                    "tp_times" =>1,
                    "isbaoming" =>0,
                    "bm_pnumber" =>0,
                    "most_money" =>1,
                    "total_num" =>1,
                    "is_showjiabin" =>0,
                    "tiemadpic" =>"../addons/haoman_dpm/img4/start.png",
                    "gl_openid" =>"../addons/haoman_dpm/img4/bimu.png",
                    "isbaoming_pay" =>0,
                    "tp_starttime" => time(),
                    "tp_endtime" => strtotime(date("Y-m-d H:i", time() + 7 * 24 * 3600)),
                    "bm_starttime" => time(),
                    "bm_endtime" => strtotime(date("Y-m-d H:i", time() + 7 * 24 * 3600)),

				);
			}

			$imgName = "haomandpm".$_W['uniacid'].$rid;
			$linkUrl = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&m=haoman_dpm&do=index&id=".$rid;
			$imgUrl = "../addons/haoman_dpm/qrcode/".$imgName.".png";
			load()->func('file');
			mkdirs(ROOT_PATH . '/qrcode');
			$dir = $imgUrl;
			$flag = file_exists($dir);
			if($flag == false){
				//生成二维码图片
				$errorCorrectionLevel = "L";
				$matrixPointSize = "4";
				QRcode::png($linkUrl,$imgUrl,$errorCorrectionLevel,$matrixPointSize);
				//生成二维码图片
			}

            $imgName2 = "haomandpm_bm".$_W['uniacid'].$rid;
            $linkUrl2 = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&m=haoman_dpm&do=ve_baoming&id=".$rid;
            $imgUrl2 = "../addons/haoman_dpm/qrcode/".$imgName2.".png";
            load()->func('file');
            mkdirs(ROOT_PATH . '/qrcode');
            $dir2 = $imgUrl2;
            $flag2 = file_exists($dir2);
            if($flag == false){
                //生成二维码图片
                $errorCorrectionLevel = "L";
                $matrixPointSize = "4";
                QRcode::png($linkUrl2,$imgUrl2,$errorCorrectionLevel,$matrixPointSize);
                //生成二维码图片
            }

			$imgName3 = "haomandpm_messages".$_W['uniacid'].$rid;
			$linkUrl3 = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&m=haoman_dpm&do=messagesindex&id=".$rid;
			$imgUrl3 = "../addons/haoman_dpm/qrcode/".$imgName3.".png";
			load()->func('file');
			mkdirs(ROOT_PATH . '/qrcode');
			$dir3 = $imgUrl3;
			$flag3 = file_exists($dir3);
			if($flag == false){
				//生成二维码图片
				$errorCorrectionLevel = "L";
				$matrixPointSize = "4";
				QRcode::png($linkUrl3,$imgUrl3,$errorCorrectionLevel,$matrixPointSize);
				//生成二维码图片
			}

			$allowip1 = explode("|",$reply['allowip']);
			$allowip2 = $allowip1[3];
			if($allowip2!=0){
				$allowip = implode("|",array_slice($allowip1,0,3));
			}



			/******************************* 20161230定制打赏功能  *********************************************/

			if(ISCUSTOM == 1 && CUSTOM_VERSION == 'DS'){//20161230定制打赏功能的
				if (!empty($rid)) {
					$ds = pdo_fetch("select * from " . tablename('haoman_dpm_ds_reply') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
				}

				$dsimgName = "haomandpm_ds".$_W['uniacid'].$rid;
				$dslinkUrl = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&m=haoman_dpm&do=dsindex&id=".$rid;
				$dsimgUrl = "../addons/haoman_dpm/qrcode/".$dsimgName.".png";
				load()->func('file');
				mkdirs(ROOT_PATH . '/qrcode');
				$dsdir = $dsimgUrl;
				$dsflag = file_exists($dsdir);
				if($dsflag == false){
					//生成二维码图片
					$errorCorrectionLevel = "L";
					$matrixPointSize = "4";
					QRcode::png($dslinkUrl,$dsimgUrl,$errorCorrectionLevel,$matrixPointSize);
					//生成二维码图片
				}

				include $this->template('custom_ds_form');

			/******************************* 20161230定制打赏功能  *********************************************/
				
			}else if(ISCUSTOM == 1 && CUSTOM_VERSION == 'ZNL'){
				
				/******************************* 20170103定制攒能量功能  *********************************************/

				if (!empty($rid)) {
					$znl = pdo_fetch("select * from " . tablename('haoman_dpm_znl_reply') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
				}

				$znlimgName = "haomandpm_znl".$_W['uniacid'].$rid;
				$znllinkUrl = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&m=haoman_dpm&do=znlindex&id=".$rid;
				$znlimgUrl = "../addons/haoman_dpm/qrcode/".$znlimgName.".png";
				load()->func('file');
				mkdirs(ROOT_PATH . '/qrcode');
				$znldir = $znlimgUrl;
				$znlflag = file_exists($znldir);
				if($znlflag == false){
					//生成二维码图片
					$errorCorrectionLevel = "L";
					$matrixPointSize = "4";
					QRcode::png($znllinkUrl,$znlimgUrl,$errorCorrectionLevel,$matrixPointSize);
					//生成二维码图片
				}

				include $this->template('custom_znl_form');

				/******************************* 20170103定制攒能量功能  *********************************************/

			}else{

				include $this->template('form');

			}

			
	}

	public function fieldsFormValidate($rid = 0) {
		return '';
	}

	public function fieldsFormSubmit($rid = 0) {
	load()->func('file');
		global $_GPC, $_W;
		$actime = $_GPC['actime'];
		// if(intval($_GPC['prace_times']) < intval($_GPC['sharecount'])){
		// 	message("转发奖励次数不能大于每天最大奖励次数");
		// }
        $uniacid = $_W['uniacid'];
		if($_GPC['isbaoming_paymoney']<0){
			message('报名费用不能小于0元','','error');
		}
		if($_GPC['isbaoming_paymoney']==0){
            $_GPC['isbaoming_pay'] = 0;
        }

        if(empty($_GPC['picture'])||empty($_GPC['p_title'])){
            message('活动标题或者图片不能为空','','error');
        }

//        file_put_contents("../addons/haoman_dpm/sign/$uniacid/$rid/sign.txt","");


        $filename ="../addons/haoman_dpm/sign/$uniacid/$rid/sign.txt";

        if(!file_exists($filename)){

            file_write($filename, '');

        }


		$allowip1 = explode("|",$_GPC['allowip']);
		$allowip = implode("|",array_slice($allowip1,0,3));
		$arr_allowip = str_replace("\r\n", " ",$allowip)."|".intval($_GPC['allowip2']);


		$insert = array(
			'rid' => $rid,
			'uniacid' => $_W['uniacid'],
			'weid' => $_W['uniacid'],
			'picture' => $_GPC['picture'],
			'description' => $_GPC['description'],
			'registimg' => $_GPC['registimg'],
			'start_picurl' => $_GPC['start_picurl'],
			'rules' => htmlspecialchars_decode($_GPC['rules']),
			'title' => $_GPC['title'],
			'p_title' => $_GPC['p_title'],
			'liucheng' => str_replace("\r\n", "",$_GPC['liucheng']),
			'3dlogo' => str_replace("\r\n", "",$_GPC['3dlogo']),
			'3dlogo1' => $_GPC['3dlogo1'],
			'3dlogo2' => $_GPC['3dlogo2'],
			'3dlogo3' => $_GPC['3dlogo3'],
			'bottomcolor' => $_GPC['bottomcolor'],
			'bottomwordcolor' => $_GPC['bottomwordcolor'],
			'mobtitle' => $_GPC['mobtitle'],
			'up_qrcode' =>$_GPC['up_qrcode'],
			'headpic' => $_GPC['headpic'],
			'headurl' => $_GPC['headurl'],
			'zhuanfaimg' => $_GPC['zhuanfaimg'],
			'panzi' => $_GPC['panzi'],
			'xf_condition' => $_GPC['xf_condition'],
			'tx_most' => $_GPC['tx_most']*100,
			'show_type' => $_GPC['show_type'],
			'is_openbbm' => $_GPC['is_openbbm'],
			'adpic' => $_GPC['adpic'],
			'backpicurl' => $_GPC['backpicurl'],
			'mobpicurl' => $_GPC['mobpicurl'],
			'share_url' => $_GPC['share_url'],
			'backcolor' => $_GPC['backcolor'],
			'adpicurl' => $_GPC['adpicurl'],
			'noip_url' => $_GPC['noip_url'],
			'registurl' => $_GPC['registurl'],
			'tiemadpic' => $_GPC['tiemadpic'],
			'timenum' => $_GPC['timenum'],
			'timeadurl' => $_GPC['timeadurl'],
			'award_times' => $_GPC['award_times'],
			'total_num' => $_GPC['total_num'],
			'most_num_times' => $_GPC['most_num_times'],
			"gl_openid" => trim($_GPC['gl_openid']),
			'createtime' => time(),
			'share_acid' => $_GPC['share_acid']*100,
			'copyright' => $_GPC['copyright'],
            'ten_time' => $_GPC['ten_time'],
			'start_hour' => $_GPC['start_hour'],
			'end_hour' => $_GPC['end_hour'],
			'qjbpic' => $_GPC['qjbpic'],
			'ziliao' =>  $_GPC['ziliao'],
			'password' =>  $_GPC['password'],
			'is_show_prize' =>  $_GPC['is_show_prize'],
            "hb_lose_openid" => trim($_GPC['hb_lose_openid']),
			'is_show_prize_num' =>  $_GPC['is_show_prize_num'],
			'share_imgurl' =>  $_GPC['share_imgurl'],
			'share_title' =>  $_GPC['share_title'],
			'share_desc' =>  $_GPC['share_desc'],
			'share_type' =>  $_GPC['share_type'],
			'mybb_url' =>  trim($_GPC['mybb_url']),
			'loginpassword' =>  $_GPC['loginpassword'],
			'qrcodedec' =>  $_GPC['qrcodedec'],
			'kaimubg' =>  $_GPC['kaimubg'],
			'bimubg' =>  $_GPC['bimubg'],
			'mobqhbbg' =>  $_GPC['mobqhbbg'],
			'ad_link' =>  $_GPC['ad_link'],
			'lose_hb' =>  $_GPC['lose_hb'],
			'daojishimusic' =>  $_GPC['daojishimusic'],
			'k_templateid' =>  $_GPC['k_templateid'],
			'jiabintitle' =>  $_GPC['jiabintitle'],
			'is3ddaojishi' => intval($_GPC['is3ddaojishi']),
			'daojishinum' => intval($_GPC['daojishinum']),
			'ismessage' => intval($_GPC['ismessage']),
			'qdshow' => intval($_GPC['qdshow']),
			'isckmessage' => intval($_GPC['isckmessage']),
			'isopenimg' => intval($_GPC['isopenimg']),
			'isqd' => intval($_GPC['isqd']),
			'qdgap' => intval($_GPC['qdgap']),
			'qdthemes' => intval($_GPC['qdthemes']),
			'isqdthemes' => intval($_GPC['isqdthemes']),
			'ischoujiang' => intval($_GPC['ischoujiang']),
			'iscjnum' => intval($_GPC['iscjnum']),
			'isqhb' => intval($_GPC['isqhb']),
			'isjiabin' => intval($_GPC['isjiabin']),
			'is_showjiabin' => intval($_GPC['is_showjiabin']),
			'most_money' => intval($_GPC['most_money']),
            'istoupiao' => intval($_GPC['istoupiao']),
            'tp_times' => intval($_GPC['tp_times']),
            'bm_pnumber' => intval($_GPC['bm_pnumber']),
            'isbaoming_pay' => intval($_GPC['isbaoming_pay']),
            'isbaoming_paymoney' => $_GPC['isbaoming_paymoney']*100,
            'tp_starttime' => strtotime($_GPC['times']['start']),
            'tp_endtime' => strtotime($_GPC['times']['end']),
            'toupiaotitle' => $_GPC['toupiaotitle'],
            'tpbg_url' => $_GPC['tpbg_url'],
            'tptop_url' => $_GPC['tptop_url'],
            'jbtop_url' => $_GPC['jbtop_url'],
            'tpbg_voice' => $_GPC['tpbg_voice'],
            'isbaoming' => intval($_GPC['isbaoming']),
            'bm_endtime' => strtotime($_GPC['bm_times']['end']),
            'bm_starttime' => strtotime($_GPC['bm_times']['start']),
			'isallowip' => intval($_GPC['isallowip']),
			'allowip' => str_replace("\r\n", " ",$arr_allowip),
			'isyyy' => intval($_GPC['is_yyy']),
			'is_realname' => intval($_GPC['is_realname']),
			'is_award' => intval($_GPC['is_award']),
			'is_b_share' => intval($_GPC['is_b_share']),

		);

		$insert_shouqian = array(
			'rid' => $rid,
			'uniacid' => $_W['uniacid'],
			'shouqianBg' => $_GPC['shouqianBg'],
			'shouqianVedio' => $_GPC['shouqianVedio'],
			'shouqianMusic' => $_GPC['shouqianMusic'],
			'qrcode' => $_GPC['qrcode'],
			'isAgain' => $_GPC['isAgain'],
			'status' => $_GPC['sh_status'],
			'pm_status' => $_GPC['pm_status'],
			'pm_Bg' => $_GPC['pm_Bg'],
			'pm_Vedio' => $_GPC['pm_Vedio'],
			'pm_Music' => $_GPC['pm_Music'],
			'pm_title' => $_GPC['pm_title'],
		);


		$insert_yyy = array(
			'rid' => $rid,
			'uniacid' => $_W['uniacid'],
			'isyyy' => intval($_GPC['isyyy']),
			'yyy_status' => intval($_GPC['yyy_status']),
			'yyy_bg' => $_GPC['yyy_bg'],
			'yyy_mbg' => $_GPC['yyy_mbg'],
			'yyy_myyybg' => $_GPC['yyy_myyybg'],
			'yyy_banner' => $_GPC['yyy_banner'],
			'yyy_music' => $_GPC['yyy_music'],
			'yyy_pdbg' => $_GPC['yyy_pdbg'],
			'yyy_maimg' => $_GPC['yyy_maimg'],
			'yyy_maxnum' => intval($_GPC['yyy_maxnum']),
			'yyy_mannum' => intval($_GPC['yyy_mannum']),
		);
        $insert_xys = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'isxys' => intval($_GPC['isxys']),
            'xys_bg' => $_GPC['xys_bg'],
            'xys_mbg' => $_GPC['xys_mbg'],
            'xys_banner' => $_GPC['xys_banner'],
            'xys_music' => $_GPC['xys_music'],
            'xys_backcolor' => $_GPC['xys_backcolor'],
            'is_sex' => $_GPC['is_sex'],
            'is_pair' => $_GPC['is_pair'],
            'pair_music' => $_GPC['pair_music'],
            'pair_backcolor' => $_GPC['pair_backcolor'],
            'pair_bg' => $_GPC['pair_bg'],
            'pair_banner' => $_GPC['pair_banner'],
			'is_turntable' => $_GPC['is_turntable'],
			'is_meg' => $_GPC['is_meg'],
			'turntable_bg' => $_GPC['turntable_bg'],
			'turntable_banner' => $_GPC['turntable_banner'],
			'turntable_music' => $_GPC['turntable_music'],
        );
        $insert_bp = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'isbp' => intval($_GPC['isbp']),
            'isbd' => intval($_GPC['isbd']),
            'ispmd' => intval($_GPC['ispmd']),
            'isds' => intval($_GPC['isds']),
            'ishb' => intval($_GPC['ishb']),
            'is_img' => intval($_GPC['is_img']),
            'ismbp' => intval($_GPC['ismbp']),
            'isvo' => intval($_GPC['isvo']),
            'isbb' => intval($_GPC['isbb']),
            'is_gift' => intval($_GPC['is_gift']),
            'is_mf' => intval($_GPC['is_mf']),
            'status' => $_GPC['bp_status'],
            'bp_bg' => $_GPC['bp_bg'],
            'bp_pay' => $_GPC['bp_pay'],
            'bp_pay2' => $_GPC['bp_pay2'],
            'bp_title' => $_GPC['bp_title'],
            'bp_music' => $_GPC['bp_music'],
            'bp_voice' => $_GPC['bp_voice'],
            'bp_listword' => $_GPC['bp_listword'],
            'bp_keyword' => $_GPC['bp_keyword'],
            'bp_maxnum' => $_GPC['bp_maxnum'],
            'bp_mesages_num' => $_GPC['bp_mesages_num'],
			'bd_starttime' => strtotime($_GPC['bd_times']['start']),
			'bd_endtime' => strtotime($_GPC['bd_times']['end']),
        );
        $insert_video = array(
			'rid' => $rid,
			'uniacid' => $_W['uniacid'],
			'vodio_bg1' => $_GPC['vodio_bg1'],
			'vodio_bg2' => $_GPC['vodio_bg2'],
			'vodio_bg3' => $_GPC['vodio_bg3'],
			'vodio_bg4' => $_GPC['vodio_bg4'],
			'vodio_bg5' => $_GPC['vodio_bg5'],
			'vodio_bg6' => $_GPC['vodio_bg6'],
			'vodio_bg7' => $_GPC['vodio_bg7'],
			'vodio_bg8' => $_GPC['vodio_bg8'],
			'vodio_bg9' => $_GPC['vodio_bg9'],
			'vodio_bg10' => $_GPC['vodio_bg10'],
			'vodio_bg11' => $_GPC['vodio_bg11'],
			'vodio_bg12' => $_GPC['vodio_bg12'],
			'vodio_bg13' => $_GPC['vodio_bg13'],
			'vodio_bg15' => $_GPC['vodio_bg15'],
			'vodio_bg16' => $_GPC['vodio_bg16'],
		);
		$insert_xyh = array(
			'rid' => $rid,
			'uniacid' => $_W['uniacid'],
			'is_xyh' => intval($_GPC['is_xyh']),
			'is_xysjh' => intval($_GPC['is_xysjh']),
			'xyh_bg' => $_GPC['xyh_bg'],
			'xysjh_bg' => $_GPC['xysjh_bg'],
			'xyh_banner' => $_GPC['xyh_banner'],
			'xysjh_banner' => $_GPC['xysjh_banner'],
			'xyh_music' => $_GPC['xyh_music'],
			'xysjh_music' => $_GPC['xysjh_music'],
			'xyh_lottery' => $_GPC['xyh_lottery'],
			'xysjh_lottery' => $_GPC['xysjh_lottery'],
			'xysjh_type' => $_GPC['xysjh_type'],
		);

		$insert_cjx = array(
			'rid' => $rid,
			'uniacid' => $_W['uniacid'],
			'isCjxStart' => intval($_GPC['isCjxStart']),
			'cjxBg' => $_GPC['cjxBg'],
			'cjxVideo' => $_GPC['cjxVideo'],
			'cjxMusic' => $_GPC['cjxMusic'],
			'isCjxcjnum' => $_GPC['isCjxcjnum'],
		);

		$insert_mb = array(
			'rid' => $rid,
			'uniacid' => $_W['uniacid'],
			'status' => intval($_GPC['status']),
			'm_templateid' => trim($_GPC['m_templateid']),
			's_templateid' => trim($_GPC['s_templateid']),
			'n_templateid' => trim($_GPC['n_templateid']),
			'p_templateid' => trim($_GPC['p_templateid']),
			'l_templateid' => trim($_GPC['l_templateid']),
			'g_templateid' => trim($_GPC['g_templateid']),
			'i_templateid' => trim($_GPC['i_templateid']),
			'f_templateid' => trim($_GPC['f_templateid']),
		);

		$insert_fanshb = array(
			'rid' => $rid,
			'uniacid' => $_W['uniacid'],
			'isfanshb' => intval($_GPC['isfanshb']),
			'hb_minmoney' => intval($_GPC['hb_minmoney']),
			'hb_manmoney' => intval($_GPC['hb_manmoney']),
			'counter' => floatval($_GPC['counter']),
			'hbtype' => intval($_GPC['hbtype']),
			'top_bg' => $_GPC['top_bg'],
			'bp_logo' => $_GPC['bp_logo'],
		);

		$id = intval($_GPC['id']);
		if (empty($id)||$id==0) {
			$id = pdo_insert('haoman_dpm_reply', $insert);
		} else {
			pdo_update('haoman_dpm_reply', $insert, array('id' => $id));
		}

		$shouqianid = intval($_GPC['shouqianid']);
		if (empty($shouqianid)||$shouqianid==0) {
			$shouqianid = pdo_insert('haoman_dpm_shouqianBase', $insert_shouqian);
		} else {
			pdo_update('haoman_dpm_shouqianBase', $insert_shouqian, array('id' => $shouqianid));
		}

		$yyyid = intval($_GPC['yyyid']);  //摇一摇的ID，用来判断是否有记录了，如果有记录就更新记录，没有就插入记录
		if (empty($yyyid)||$yyyid==0) {
			pdo_insert('haoman_dpm_yyyreply', $insert_yyy);
		} else {
			pdo_update('haoman_dpm_yyyreply', $insert_yyy, array('id' => $yyyid));
		}
        $xysid = intval($_GPC['xysid']);  //许愿树的ID，用来判断是否有记录了，如果有记录就更新记录，没有就插入记录
        if (empty($xysid)||$xysid==0) {
            pdo_insert('haoman_dpm_xysreply', $insert_xys);
        } else {
            pdo_update('haoman_dpm_xysreply', $insert_xys, array('id' => $xysid));
        }
        $bpid = intval($_GPC['bpid']);  //霸屏的ID，用来判断是否有记录了，如果有记录就更新记录，没有就插入记录
        if (empty($bpid)||$bpid==0) {
            pdo_insert('haoman_dpm_bpreply', $insert_bp);
        } else {
            pdo_update('haoman_dpm_bpreply', $insert_bp, array('id' => $bpid));
        }
        $vedioid = intval($_GPC['vedioid']);  //霸屏的ID，用来判断是否有记录了，如果有记录就更新记录，没有就插入记录
        if (empty($vedioid)||$vedioid==0) {
            pdo_insert('haoman_dpm_mp4', $insert_video);
        } else {
            pdo_update('haoman_dpm_mp4', $insert_video, array('id' => $vedioid));
        }
		$xyhid = intval($_GPC['xyhid']);  //幸运号的ID，用来判断是否有记录了，如果有记录就更新记录，没有就插入记录
		if (empty($xyhid)||$xyhid==0) {
			pdo_insert('haoman_dpm_xyhreply', $insert_xyh);
		} else {
			pdo_update('haoman_dpm_xyhreply', $insert_xyh, array('id' => $xyhid));
		}

		$cjxid = intval($_GPC['cjxid']);  //抽奖箱的ID，用来判断是否有记录了，如果有记录就更新记录，没有就插入记录
		if (empty($cjxid)||$cjxid==0) {
			pdo_insert('haoman_dpm_cjxreply', $insert_cjx);
		} else {
			pdo_update('haoman_dpm_cjxreply', $insert_cjx, array('id' => $cjxid));
		}

		$mbid = intval($_GPC['mbid']);  //模版的ID，用来判断是否有记录了，如果有记录就更新记录，没有就插入记录
		if (empty($mbid)||$mbid==0) {
			pdo_insert('haoman_dpm_notifications', $insert_mb);
		} else {
			pdo_update('haoman_dpm_notifications', $insert_mb, array('id' => $mbid));
		}
		$fanshb = intval($_GPC['fhbid']);  //模版的ID，用来判断是否有记录了，如果有记录就更新记录，没有就插入记录
		if (empty($fanshb)||$fanshb==0) {
			pdo_insert('haoman_dpm_hb_setting', $insert_fanshb);
		} else {
			pdo_update('haoman_dpm_hb_setting', $insert_fanshb, array('id' => $fanshb));
		}


        $insert_mysql = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'type' =>0,
            'ip' => CLIENT_IP,
            'createtime' => time(),
        );
        pdo_insert('haoman_dpm_change_mysql', $insert_mysql);

        /******************************* 20161230定制打赏功能  *********************************************/

        if(ISCUSTOM == 1 && CUSTOM_VERSION == 'DS'){//20161230定制打赏功能的
        	$insert_ds = array(
	            'rid' => $rid,
	            'uniacid' => $_W['uniacid'],
	            'is_openbbm' => intval($_GPC['ds_is_openbbm']),
	            'backpicurl' => $_GPC['ds_backpicurl'],
	            'noip_url' => $_GPC['ds_noip_url'],
	            'backcolor' => $_GPC['ds_backcolor'],
	            'counter_fee' => $_GPC['ds_counter_fee'],
	            'split_money' => $_GPC['ds_split_money'],
	            'split_date' => $_GPC['ds_split_date'],
	            'zhuanfaimg' => $_GPC['ds_zhuanfaimg'],
	            'is_ziliao' => $_GPC['ds_is_ziliao'],
	            'starttime' => strtotime($_GPC['dsdatelimit']['start']),
            	'endtime' => strtotime($_GPC['dsdatelimit']['end']),
	        );
	        $dsid = intval($_GPC['dsid']);
	        if (empty($dsid)||$dsid==0) {
				pdo_insert('haoman_dpm_ds_reply', $insert_ds);
			} else {
				pdo_update('haoman_dpm_ds_reply', $insert_ds, array('id' => $dsid));
			}
        }//20161230定制打赏功能的

        /******************************* 20161230定制打赏功能  *********************************************/

        /******************************* 20170103定制攒能量功能  *********************************************/
        if(ISCUSTOM == 1 && CUSTOM_VERSION == 'ZNL'){
        	$insert_znl = array(
	            'rid' => $rid,
	            'uniacid' => $_W['uniacid'],
	            'isznl' => intval($_GPC['znl_isznl']),
	            'isrank' => intval($_GPC['znl_isrank']),
	            'znl_bg' => $_GPC['znl_znl_bg'],
	            'znl_banner' => $_GPC['znl_znl_banner'],
	            'znl_music' => $_GPC['znl_znl_music'],
	            'znl_maxnum' => $_GPC['znl_znl_maxnum'],
	            'znl_maimg' => $_GPC['znl_znl_maimg'],
	            'znl_mbg' => $_GPC['znl_znl_mbg'],
	            'znl_mbg2' => $_GPC['znl_znl_mbg2'],
	            'znl_qrcode' => $_GPC['znl_qrcode'],
	            'znl_number' => $_GPC['znl_number'],
	            'znl_img' => $_GPC['znl_img'],
	            'znl_color' => $_GPC['znl_color'],
	        );
	        $znlid = intval($_GPC['znlid']);
	        if (empty($znlid)||$znlid==0) {
				pdo_insert('haoman_dpm_znl_reply', $insert_znl);
			} else {
				pdo_update('haoman_dpm_znl_reply', $insert_znl, array('id' => $znlid));
			}
        }
        /******************************* 20170103定制攒能量功能  *********************************************/


	}

	public function ruleDeleted($rid = 0) {
		global $_W;
			load()->func('file');
		$replies = pdo_fetchall("SELECT id, picture FROM ".tablename($this->tablenames)." WHERE rid = '$rid'");
		$deleteid = array();
		
		if (!empty($replies)) {
			foreach ($replies as $index => $row) {
				file_delete($row['picture']);
				$deleteid[] = $row['id'];
			}
		}
		pdo_delete($this->tablenames, "id IN ('".implode("','", $deleteid)."')");
		
		return true;
	}
}
