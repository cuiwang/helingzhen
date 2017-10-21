<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
defined ( 'IN_IA' ) or exit ( 'Access Denied' );
define('OSSURL', '../addons/fm_jiaoyu/');
class Core extends WeModuleSite {

	// ===============================================
	public $m = 'wx_school';
	public $table_classify = 'wx_school_classify';
	public $table_score = 'wx_school_score';
	public $table_news = 'wx_school_news';
	public $table_index = 'wx_school_index';
	public $table_students = 'wx_school_students';
	public $table_tcourse = 'wx_school_tcourse';
	public $table_teachers = 'wx_school_teachers';
	public $table_area = 'wx_school_area';
    public $table_type = 'wx_school_type';
    public $table_kcbiao = 'wx_school_kcbiao';
	public $table_cook = 'wx_school_cookbook';
	public $table_reply = 'wx_school_reply';
	public $table_banners = 'wx_school_banners';
	public $table_bbsreply = 'wx_school_bbsreply';
	public $table_user = 'wx_school_user';
	public $table_set = 'wx_school_set';
	public $table_leave = 'wx_school_leave';
	public $table_notice = 'wx_school_notice';
	public $table_bjq = 'wx_school_bjq';
	public $table_media = 'wx_school_media';
	public $table_dianzan = 'wx_school_dianzan';
	public $table_order = 'wx_school_order';
    public $table_wxpay = 'wx_school_wxpay';
    public $table_group = 'wx_school_fans_group';
	public $table_qrinfo = 'wx_school_qrcode_info';
	public $table_qrset = 'wx_school_qrcode_set';
	public $table_qrstat = 'wx_school_qrcode_statinfo';
	public $table_cost = 'wx_school_cost';
	public $table_object = 'wx_school_object';
	public $table_signup = 'wx_school_signup';
	public $table_record = 'wx_school_record';
	public $table_checkmac = 'wx_school_checkmac';
	public $table_checklog = 'wx_school_checklog';
	public $table_idcard = 'wx_school_idcard';
	
    public function getNaveMenu($schoolid,$action) {
        global $_W, $_GPC;
        $do = $_GPC['do'];
        $navemenu = array();
        $navemenu[0] = array(
            'title' => '<icon style="color:#d9534f;" class="fa fa-cog"></icon>  基础设置',
            'items' => array(
                0 => array(
				'title' => '基本设置 ',
				'url' => $do != 'schoolset' ? $this->createWebUrl('schoolset', array('op' => 'post', 'schoolid' => $schoolid)) : '',
				'active' => $action == 'schoolset' ? ' active' : '',
				'append' => array(
				'title' => '<i style="color:#d9534f;" class="fa fa-cog"></i>',
								),
								),			
                1 => array(
				'title' => '分类管理 ',
				'url' => $do != 'semester' ? $this->createWebUrl('semester', array('op' => 'display', 'schoolid' => $schoolid)) : '',
				'active' => $action == 'semester' ? ' active' : '',
				'append' => array(
				'title' => '<i style="color:#d9534f;" class="fa fa-bars"></i>',
								),
								),
				2 => array('title' => '食谱管理', 'url' => $do != 'cook' ? $this->createWebUrl('cook', array('op' => 'display', 'schoolid' => $schoolid)) : '',	
				'active' => $action == 'cook' ? ' active' : '',				
				'append' => array(
				'title' => '<i style="color:#d9534f;" class="fa fa-cutlery"></i>',
								),
								),
				3 => array('title' => '幻灯片管理', 'url' => $do != 'banner' ? $this->createWebUrl('banner', array('op' => 'display', 'schoolid' => $schoolid)) : '',
				'active' => $action == 'banner' ? ' active' : '',		
				'append' => array(
				'title' => '<i style="color:#d9534f;" class="fa fa-image"></i>',
								),
								),
            ),
			'icon'=>'fa fa-user-md'
        );
        $navemenu[1] = array(
            'title' => '<icon style="color:#7228b5;" class="fa fa-database"></icon>  数据管理',
            'items' => array(
                0 => array(
				'title' => '教师管理',
				'url' => $do != 'assess' ? $this->createWebUrl('assess', array('op' => 'display', 'schoolid' => $schoolid)) : '',
				'active' => $action == 'assess' ? ' active' : '',
				'append' => array(
				'title' => '<i style="color:#7228b5;" class="fa fa-user"></i>',
								),
								),															
                1 => array('title' => '学生管理', 'url' => $do != 'students' ? $this->createWebUrl('students', array('op' => 'display', 'schoolid' => $schoolid)) : '',
				'active' => $action == 'students' ? ' active' : '',
				'append' => array(
				'title' => '<i style="color:#7228b5;" class="fa fa-users"></i>',
								),
								),
                2 => array('title' => '成绩管理', 'url' => $do != 'chengji' ? $this->createWebUrl('chengji', array('op' => 'display', 'schoolid' => $schoolid)) : '',
				'active' => $action == 'chengji' ? ' active' : '',
				'append' => array(
				'title' => '<i style="color:#7228b5;" class="fa fa-book"></i>',
								),
								),
				3 => array('title' => '课程管理', 'url' => $do != 'kecheng' ? $this->createWebUrl('kecheng', array('op' => 'display', 'schoolid' => $schoolid)) : '',
				'active' => $action == 'kecheng' ? ' active' : '',
				'append' => array(
				'title' => '<i style="color:#7228b5;" class="fa fa-graduation-cap"></i>',
								),
								),
				4 => array('title' => '课表管理', 'url' => $do != 'kcbiao' ? $this->createWebUrl('kcbiao', array('op' => 'display', 'schoolid' => $schoolid)) : '',
				'active' => $action == 'kcbiao' ? ' active' : '',
				'append' => array(
				'title' => '<i style="color:#7228b5;" class="fa fa-calendar"></i>',
								),
								),
            )
        );
        $navemenu[2] = array(
            'title' => '<icon style="color:#258a25;" class="fa fa-wechat"></icon> 互动管理',
            'items' => array(
				0 => array('title' => '作业通知请假', 'url' => $do != 'notice' ? $this->createWebUrl('notice', array('op' => 'display', 'schoolid' => $schoolid)) : '',
				'active' => $action == 'notice' ? ' active' : '',
				'append' => array(
				'title' => '<i style="color:#258a25;" class="fa fa-bullhorn"></i>',
								),
								),
				1 => array('title' => '报名管理', 'url' => $do != 'signup' ? $this->createWebUrl('signup', array('op' => 'display', 'schoolid' => $schoolid)) : '',
				'active' => $action == 'signup' ? ' active' : '',
				'append' => array(
				'title' => '<i style="color:#258a25;" class="fa fa-comments"></i>',
								),
								),
				2 => array('title' => '文章系统', 'url' => $do != 'article' ? $this->createWebUrl('article', array('op' => 'display', 'schoolid' => $schoolid)) : '',
				'active' => $action == 'article' ? ' active' : '',
				'append' => array(
				'title' => '<i style="color:#258a25;" class="fa fa-desktop"></i>',
								),
								),
				3 => array('title' => '班级圈管理', 'url' => $do != 'bjquan' ? $this->createWebUrl('bjquan', array('op' => 'display', 'schoolid' => $schoolid)) : '',
				'active' => $action == 'bjquan' ? ' active' : '',
				'append' => array(
				'title' => '<i style="color:#258a25;" class="fa fa-wechat"></i>',
								),
								),
				4 => array('title' => '相册管理', 'url' => $do != 'photos' ? $this->createWebUrl('photos', array('op' => 'display', 'schoolid' => $schoolid)) : '',
				'active' => $action == 'photos' ? ' active' : '',
				'append' => array(
				'title' => '<i style="color:#258a25;" class="fa fa-camera"></i>',
								),
								),
            )
        );
		$school = pdo_fetch("SELECT * FROM ".tablename($this->table_index)." WHERE :id = id", array(':id' => $schoolid));
		if ($school['is_cost'] == 2){
			if ($_W['isfounder']){
				$navemenu[3] = array(
					'title' => '<icon style="color:#cc6b08;" class="fa fa-money"></icon>  财务管理',
					'items' => array(
						0 => array('title' => '缴费管理', 'url' => $do != 'cost' ? $this->createWebUrl('cost', array('op' => 'display', 'schoolid' => $schoolid)) : '',
						'active' => $action == 'cost' ? ' active' : '',
						'append' => array(
						'title' => '<i style="color:#cc6b08;" class="fa fa-money"></i>',
								),
								),
						1 => array('title' => '订单管理', 'url' => $do != 'payall' ? $this->createWebUrl('payall', array('op' => 'display', 'schoolid' => $schoolid)) : '',
						'active' => $action == 'payall' ? ' active' : '',
						'append' => array(
						'title' => '<i style="color:#cc6b08;" class="fa fa-bar-chart-o"></i>',
										),
										),
					)
				);
			}
		}else{
			$navemenu[3] = array(
				'title' => '<icon style="color:#cc6b08;" class="fa fa-money"></icon>  财务管理',
				'items' => array(
					0 => array('title' => '缴费管理', 'url' => $do != 'cost' ? $this->createWebUrl('cost', array('op' => 'display', 'schoolid' => $schoolid)) : '',
					'active' => $action == 'cost' ? ' active' : '',
					'append' => array(
					'title' => '<i style="color:#cc6b08;" class="fa fa-money"></i>',
								),
								),
					1 => array('title' => '订单管理', 'url' => $do != 'payall' ? $this->createWebUrl('payall', array('op' => 'display', 'schoolid' => $schoolid)) : '',
					'active' => $action == 'payall' ? ' active' : '',
					'append' => array(
					'title' => '<i style="color:#cc6b08;" class="fa fa-bar-chart-o"></i>',
									),
									),								
				)
			);			
		}
		if ($school['is_recordmac'] == 2){
			if ($_W['isfounder']){
				$navemenu[4] = array(
					'title' => '<icon style="color:#077ccc;" class="fa fa-credit-card"></icon>  考勤管理',
					'items' => array(
						0 => array('title' => '考勤记录', 'url' => $do != 'checklog' ? $this->createWebUrl('checklog', array('op' => 'display', 'schoolid' => $schoolid)) : '',
						'active' => $action == 'checklog' ? ' active' : '',
						'append' => array(
						'title' => '<i style="color:#077ccc;" class="fa fa-table"></i>',
								),
								),
						1 => array('title' => '设备管理', 'url' => $do != 'check' ? $this->createWebUrl('check', array('op' => 'display', 'schoolid' => $schoolid)) : '',
						'active' => $action == 'check' ? ' active' : '',
						'append' => array(
						'title' => '<i style="color:#077ccc;" class="fa fa-gears"></i>',
										),
										),
					)
				);
			}
		}else{
			$navemenu[4] = array(
				'title' => '<icon style="color:#077ccc;" class="fa fa-credit-card"></icon>  考勤管理',
				'items' => array(
					0 => array('title' => '考勤记录', 'url' => $do != 'checklog' ? $this->createWebUrl('checklog', array('op' => 'display', 'schoolid' => $schoolid)) : '',
					'active' => $action == 'checklog' ? ' active' : '',
					'append' => array(
					'title' => '<i style="color:#077ccc;" class="fa fa-table"></i>',
							),
							),
					1 => array('title' => '设备管理', 'url' => $do != 'check' ? $this->createWebUrl('check', array('op' => 'display', 'schoolid' => $schoolid)) : '',
					'active' => $action == 'check' ? ' active' : '',
					'append' => array(
					'title' => '<i style="color:#077ccc;" class="fa fa-gears"></i>',
									),
									),
				)
			);
		}		
        return $navemenu;
    }
	
	public function sendtempmsg($template_id, $url, $data, $topcolor, $tousers = '') {
		load()->func('communication');
		load()->classs('weixin.account');
		$access_token = WeAccount::token();
		if(empty($access_token)) {
			return;
		}
		$postarr = '{"touser":"'.$tousers.'","template_id":"'.$template_id.'","url":"'.$url.'","topcolor":"'.$topcolor.'","data":'.$data.'}';
		$res = ihttp_post('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token,$postarr);
		return true;
	}

	public function sendMobileBmshtz($signup_id, $schoolid, $weid, $toopenid, $s_name) {
		global $_GPC,$_W;
		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));
        $template_id = $msgtemplate['bjqshtz'];//消息模板id 微信的模板id
		$signtype = pdo_fetch("SELECT * FROM " . tablename($this->table_signup) . " where :id = id", array(':id' => $signup_id));
		$class = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where sid = :sid ", array(':sid' => $signtype['bj_id']));
		$order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " where id = :id ", array(':id' => $signtype['orderid']));

	    $leibie = "学生报名申请";
		if(!empty($class['cost'])){
			if($order['status'] == 1){
				$zhuangtai = "未付费";
			}else{
				$zhuangtai = "已付费";
			}
		}else{
			$zhuangtai = "未通过";
		}
		$ttime = date('Y-m-d H:i:s', TIMESTAMP);
		$body = "点击本条消息快速审核 ";
	    $datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
		'first'=>array('value'=>'老师您好,您收到了一条报名审核提醒','color'=>'#FF9E05'),
		'keyword1'=>array('value'=>$leibie,'color'=>'#1587CD'),
		'keyword2'=>array('value'=>$s_name,'color'=>'#FF9E05'),
		'keyword3'=>array('value'=>$zhuangtai,'color'=>'#1587CD'),
		'keyword4'=>array('value'=>$ttime,'color'=>'#1587CD'),
		'remark'=> array('value'=>$body,'color'=>'#FF9E05')
	     );
	    $data=json_encode($datas); //发送的消息模板数据

		$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('bm', array('schoolid' => $schoolid, 'id' => $signup_id));


		if (!empty($template_id)) {
			$this->sendtempmsg($template_id, $url, $data, '#FF0000', $toopenid);
		}
	}

	public function sendMobileBmshjg($signupid, $schoolid, $weid, $toopenid, $s_name) {
		global $_GPC,$_W;
		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));
        $template_id = $msgtemplate['bjqshjg'];//消息模板id 微信的模板id
		$signtype = pdo_fetch("SELECT * FROM " . tablename($this->table_signup) . " where :id = id", array(':id' => $signupid));
		$class = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where sid = :sid ", array(':sid' => $signtype['bj_id']));
		$order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " where id = :id ", array(':id' => $signtype['orderid']));

	    $leibie = "报名申请";
		if(!empty($class['cost'])){
			if($order['status'] == 1){
				$zhuangtai = "未付费";
				$body = "点击本条消息快速支付报名费";
			}else{
				$zhuangtai = "已付费";
				$body = "点击本条消息快速查看 ";
			}
		}else{
				$zhuangtai = "审核中";
				$body = "点击本条消息快速查看 ";
		}

		$ttime = date('Y-m-d H:i:s', TIMESTAMP);

	    $datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
		'first'=>array('value'=>'您好,【'.$s_name.'】的报名资料已经开始审核','color'=>'#FF9E05'),
		'keyword1'=>array('value'=>$leibie,'color'=>'#1587CD'),
		'keyword2'=>array('value'=>$zhuangtai,'color'=>'#FF9E05'),
		'keyword3'=>array('value'=>$ttime,'color'=>'#1587CD'),
		'remark'=> array('value'=>$body,'color'=>'#FF9E05')
	     );
	    $data=json_encode($datas); //发送的消息模板数据

		$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('signupjc', array('schoolid' => $schoolid, 'id' =>$signupid));


		if (!empty($template_id)) {
			$this->sendtempmsg($template_id, $url, $data, '#FF0000', $toopenid);
		}
	}

	public function sendMobileBmshjgtz($signupid, $schoolid, $weid, $toopenid, $s_name) {
		global $_GPC,$_W;
		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));
        $template_id = $msgtemplate['bjqshjg'];//消息模板id 微信的模板id
		$signtype = pdo_fetch("SELECT * FROM " . tablename($this->table_signup) . " where :id = id", array(':id' => $signupid));
		$class = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where sid = :sid ", array(':sid' => $signtype['bj_id']));
		$order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " where id = :id ", array(':id' => $signtype['orderid']));

	    $leibie = "报名申请";
		if ($signtype['status'] == 2){
			$zhuangtai = "审核通过";
			$body = "您可以通过以下信息绑定学生:\n学生姓名:{$s_name}\n手机号码:{$signtype['mobile']}\n千万不要将本信息告诉给陌生人 ";
		}else if($signtype['status'] == 3){
			$zhuangtai = "未通过";
			$body = "点击本条消息查看详情 ";
		}

		$ttime = date('Y-m-d H:i:s', TIMESTAMP);

	    $datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
		'first'=>array('value'=>'您好,【'.$s_name.'】的报名资料审核完毕','color'=>'#FF9E05'),
		'keyword1'=>array('value'=>$leibie,'color'=>'#1587CD'),
		'keyword2'=>array('value'=>$zhuangtai,'color'=>'#FF9E05'),
		'keyword3'=>array('value'=>$ttime,'color'=>'#1587CD'),
		'remark'=> array('value'=>$body,'color'=>'#FF9E05')
	     );
	    $data=json_encode($datas); //发送的消息模板数据

		$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('signupjc', array('schoolid' => $schoolid, 'id' =>$signupid));


		if (!empty($template_id)) {
			$this->sendtempmsg($template_id, $url, $data, '#FF0000', $toopenid);
		}
	}

	public function sendMobileBjqshtz($schoolid, $weid, $shername, $bj_id) {
		global $_GPC,$_W;
		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));
        $template_id = $msgtemplate['bjqshtz'];//消息模板id 微信的模板id
		$bzj = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where weid = :weid And schoolid = :schoolid And sid = :sid", array(':weid' => $weid, ':schoolid' => $schoolid, ':sid' => $bj_id));
		$teachers = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $bzj['tid']));

	    $leibie = "班级圈内容审核";
		$zhuangtai = "未通过";
		$ttime = date('Y-m-d H:i:s', TIMESTAMP);
		$body = "点击本条消息快速审核 ";
	    $datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
		'first'=>array('value'=>'老师您好,您收到了一条班级圈内容审核提醒','color'=>'#FF9E05'),
		'keyword1'=>array('value'=>$leibie,'color'=>'#1587CD'),
		'keyword2'=>array('value'=>$shername,'color'=>'#FF9E05'),
		'keyword3'=>array('value'=>$zhuangtai,'color'=>'#1587CD'),
		'keyword4'=>array('value'=>$ttime,'color'=>'#1587CD'),
		'remark'=> array('value'=>$body,'color'=>'#FF9E05')
	     );
	    $data=json_encode($datas); //发送的消息模板数据

		$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('bjq', array('schoolid' => $schoolid, 'bj_id' => $bj_id));


		if (!empty($template_id)) {
			$this->sendtempmsg($template_id, $url, $data, '#FF0000', $teachers['openid']);
		}
	}

	public function sendMobileBjqshjg($schoolid, $weid, $shername, $toopenid) {
		global $_GPC,$_W;
		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));
        $template_id = $msgtemplate['bjqshjg'];//消息模板id 微信的模板id

	    $leibie = "班级圈内容审核";
		$zhuangtai = "审核通过";
		$ttime = date('Y-m-d H:i:s', TIMESTAMP);
		$body = "点击本条消息快速查看 ";
	    $datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
		'first'=>array('value'=>'您好'.$shername.',您收到一条班级圈审核结果通知','color'=>'#FF9E05'),
		'keyword1'=>array('value'=>$leibie,'color'=>'#1587CD'),
		'keyword2'=>array('value'=>$zhuangtai,'color'=>'#FF9E05'),
		'keyword3'=>array('value'=>$ttime,'color'=>'#1587CD'),
		'remark'=> array('value'=>$body,'color'=>'#FF9E05')
	     );
	    $data=json_encode($datas); //发送的消息模板数据

		$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('sbjq', array('schoolid' => $schoolid));


		if (!empty($template_id)) {
			$this->sendtempmsg($template_id, $url, $data, '#FF0000', $toopenid);
		}
	}
	
	public function doWebZuoyeMsg(){
		global $_GPC,$_W;
		$notice_id = $_GPC['notice_id'];
		$schoolid = $_GPC['schoolid'];
		$weid = $_GPC['weid'];
		$tname = $_GPC['tname'];
		$bj_id = $_GPC['bj_id'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 2;

		$userinfo = pdo_fetchall("SELECT * FROM ".tablename($this->table_students)." where weid = :weid And schoolid = :schoolid And bj_id = :bj_id ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,array(':weid'=>$weid, ':schoolid'=>$schoolid, ':bj_id'=>$bj_id));
		$total = pdo_fetchcolumn("SELECT COUNT(1) FROM ".tablename($this->table_students)." where weid = :weid And schoolid = :schoolid And bj_id = :bj_id",array(':weid'=>$weid, ':schoolid'=>$schoolid, ':bj_id'=>$bj_id));
 
		$tp = ceil($total/$psize);
		for ($i=1; $i < $tp; $i++) {
			$this->sendMobileZuoye($notice_id, $schoolid, $weid, $tname, $bj_id, $pindex, $psize);
			if ($pindex == $i) {
				$mq = round(($pindex / $tp) * 100);
				$msg = '正在发送，目前：<strong style="color:#5cb85c">' . $mq . ' %</strong>,请勿执行任何操作';

				$page = $pindex + 1;
				$to = $this -> createWebUrl('ZuoyeMsg', array('notice_id' => $notice_id, 'schoolid' => $schoolid, 'weid' => $weid, 'tname' => $tname, 'bj_id' => $bj_id, 'page' => $page));
				message($msg, $to);
			}
		}
		message('发送成功！', $this -> createWebUrl('notice', array('op' => 'display5','schoolid' => $schoolid,'notice_id' => $notice_id)));
	}
	
	public function sendMobileZuoye($notice_id, $schoolid, $weid, $tname, $bj_id) {
		global $_GPC,$_W;
		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));
		$notice = pdo_fetch("SELECT * FROM ".tablename($this->table_notice)." WHERE :weid = weid AND :id = id AND :schoolid = schoolid", array(':weid' => $weid, ':id' => $notice_id, ':schoolid' => $schoolid));

        $template_id = $msgtemplate['zuoye'];//消息模板id 微信的模板id
		$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE :weid = weid AND :schoolid =schoolid", array(':weid' => $weid, ':schoolid' => $schoolid), 'sid');

		$userinfo = pdo_fetchall("SELECT * FROM ".tablename($this->table_students)." where weid = :weid And schoolid = :schoolid And bj_id = :bj_id",array(':weid'=>$weid, ':schoolid'=>$schoolid, ':bj_id'=>$notice['bj_id']));

		foreach ($userinfo as $key => $value) {

			$openidall = pdo_fetchall("select * from ".tablename($this->table_user)." where sid = '{$value['id']}'");
			$name  = "{$tname}老师";
			$title ="{$category[$notice['km_id']]['sname']}老师{$tname}发来一条作业消息!";
			$bjname  = "{$category[$notice['bj_id']]['sname']}";
			$body  = "点击本条消息查看详情 ";
			$datas=array(
				'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
				'first'=>array('value'=>$title,'color'=>'#1587CD'),
				'keyword1'=>array('value'=>$bjname,'color'=>'#1587CD'),
				'keyword2'=>array('value'=>$notice['title'],'color'=>'#2D6A90'),
				'remark'=> array('value'=>$body,'color'=>'#FF9E05')
						);
			$data = json_encode($datas); //发送的消息模板数据

			$num = count($openidall);
			if ($num > 1){
				foreach ($openidall as $key => $values) {
					$openid = $values['openid'];
					$record = pdo_fetch("SELECT * FROM ".tablename($this->table_record)." where weid = :weid And schoolid = :schoolid And noticeid = :noticeid And openid = :openid And sid = :sid And userid = :userid And createtime = :createtime",array(
										':weid'=>$_W['uniacid'],
										':schoolid'=>$schoolid,
										':noticeid'=>$notice_id,
										':openid'=>$openid,
										':sid'=>$values['sid'],
										':userid'=>$values['id'],
										':createtime'=>$notice['createtime'],
					));
					
					if(empty($record)){		
						$date = array(
							'weid' =>  $_W['uniacid'],
							'schoolid' => $schoolid,
							'noticeid' => $notice_id,
							'sid' => $values['sid'],
							'userid' => $values['id'],
							'openid' => $openid,
							'createtime' => $notice['createtime']
						);
						pdo_insert($this->table_record, $date);
						$record_id = pdo_insertid();
						if(!empty($notice['outurl'])){
							$url = $notice['outurl'];
						}else{
							$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('szuoye', array('schoolid' => $schoolid,'record_id' => $record_id,'id' => $notice_id,'userid' => $values['id']));
						}
						$this->sendtempmsg($template_id, $url, $data, '#FF0000', $openid);
					}
				}
			}else{
					$openid = $openidall['0']['openid'];
					$record = pdo_fetch("SELECT * FROM ".tablename($this->table_record)." where weid = :weid And schoolid = :schoolid And noticeid = :noticeid And openid = :openid And sid = :sid And userid = :userid And createtime = :createtime",array(
										':weid'=>$_W['uniacid'],
										':schoolid'=>$schoolid,
										':noticeid'=>$notice_id,
										':openid'=>$openid,
										':sid'=>$openidall['0']['sid'],
										':userid'=>$openidall['0']['id'],
										':createtime'=>$notice['createtime'],
					));					
					if(empty($record)){	
						$date = array(
							'weid' =>  $_W['uniacid'],
							'schoolid' => $schoolid,
							'noticeid' => $notice_id,
							'sid' => $openidall['0']['sid'],
							'userid' => $openidall['0']['id'],
							'openid' => $openid,
							'createtime' => $notice['createtime']
						);
						pdo_insert($this->table_record, $date);
						$record_id = pdo_insertid();
						if(!empty($notice['outurl'])){
							$url = $notice['outurl'];
						}else{
							$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('szuoye', array('schoolid' => $schoolid,'record_id' => $record_id,'id' => $notice_id,'userid' => $openidall['0']['id']));
						}
						$this->sendtempmsg($template_id, $url, $data, '#FF0000', $openid);
					}					
			}

		}
	}
	public function doWebBjtzMsg(){
		global $_GPC,$_W;
		$notice_id = $_GPC['notice_id'];
		$schoolid = $_GPC['schoolid'];
		$weid = $_GPC['weid'];
		$tname = $_GPC['tname'];
		$bj_id = $_GPC['bj_id'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 2;

		$userinfo = pdo_fetchall("SELECT * FROM ".tablename($this->table_students)." where weid = :weid And schoolid = :schoolid And bj_id = :bj_id ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,array(':weid'=>$weid, ':schoolid'=>$schoolid, ':bj_id'=>$bj_id));
		$total = pdo_fetchcolumn("SELECT COUNT(1) FROM ".tablename($this->table_students)." where weid = :weid And schoolid = :schoolid And bj_id = :bj_id",array(':weid'=>$weid, ':schoolid'=>$schoolid, ':bj_id'=>$bj_id));
 
		$tp = ceil($total/$psize);
		for ($i=1; $i < $tp; $i++) {
			$this->sendMobileBjtz($notice_id, $schoolid, $weid, $tname, $bj_id, $pindex, $psize);
			if ($pindex == $i) {
				$mq = round(($pindex / $tp) * 100);
				$msg = '正在发送，目前：<strong style="color:#5cb85c">' . $mq . ' %</strong>,请勿执行任何操作';

				$page = $pindex + 1;
				$to = $this -> createWebUrl('BjtzMsg', array('notice_id' => $notice_id, 'schoolid' => $schoolid, 'weid' => $weid, 'tname' => $tname, 'bj_id' => $bj_id, 'page' => $page));
				message($msg, $to);
			}
		}
		message('发送成功！', $this -> createWebUrl('notice', array('op' => 'display5','schoolid' => $schoolid,'notice_id' => $notice_id)));
	}
	
	private function sendMobileBjtz($notice_id, $schoolid, $weid, $tname, $bj_id) {
		global $_GPC,$_W;
		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));	
		$notice = pdo_fetch("SELECT * FROM ".tablename($this->table_notice)." WHERE :weid = weid AND :id = id AND :schoolid = schoolid", array(':weid' => $weid, ':id' => $notice_id, ':schoolid' => $schoolid));
        $template_id = $msgtemplate['bjtz'];//消息模板id 微信的模板id
		$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE :weid = weid AND :schoolid =schoolid", array(':weid' => $weid, ':schoolid' => $schoolid), 'sid');
				
		$userinfo=pdo_fetchall("SELECT * FROM ".tablename($this->table_students)." where weid = :weid And schoolid = :schoolid And bj_id = :bj_id",array(':weid'=>$weid, ':schoolid'=>$schoolid, ':bj_id'=>$bj_id));	
		
		foreach ($userinfo as $key => $value) {
			
			$openidall = pdo_fetchall("select * from ".tablename($this->table_user)." where sid = '{$value['id']}' ");
			
			$name  = "{$tname}老师";
			$bjname  = "{$category[$notice['bj_id']]['sname']}";
			$ttime = date('Y-m-d H:i:s', $notice['createtime']);
			$body  = "点击本条消息查看详情 ";

			$num = count($openidall);
			if ($num > 1){ 
				foreach ($openidall as $key => $values) {
					$openid = $values['openid'];					
					$record = pdo_fetch("SELECT * FROM ".tablename($this->table_record)." where weid = :weid And schoolid = :schoolid And noticeid = :noticeid And openid = :openid And sid = :sid And userid = :userid And createtime = :createtime",array(
										':weid'=>$_W['uniacid'],
										':schoolid'=>$schoolid,
										':noticeid'=>$notice_id,
										':openid'=>$openid,
										':sid'=>$values['sid'],
										':userid'=>$values['id'],
										':createtime'=>$notice['createtime'],
					));
					$student = pdo_fetch("SELECT s_name FROM ".tablename($this->table_students)." where id = :id",array(':id'=>$values['sid']));
					if($values['pard'] == 2){
						$guanxi = "妈妈";
					}else if($values['pard'] == 3){
						$guanxi = "爸爸";
					}else if($values['pard'] == 4){
						$guanxi = "";
					}
					$title = "【{$student['s_name']}】{$guanxi}，您收到一条班级通知";
					$datas=array(
						'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
						'first'=>array('value'=>$title,'color'=>'#1587CD'),
						'keyword1'=>array('value'=>$bjname,'color'=>'#1587CD'),
						'keyword2'=>array('value'=>$name,'color'=>'#2D6A90'),
						'keyword3'=>array('value'=>$ttime,'color'=>'#1587CD'),
						'keyword4'=>array('value'=>$notice['title'],'color'=>'#1587CD'),
						'remark'=> array('value'=>$body,'color'=>'#FF9E05')
								);
					$data = json_encode($datas); //发送的消息模板数据
					
					if(empty($record)){
						
						$date = array(
							'weid' =>  $_W['uniacid'],
							'schoolid' => $schoolid,
							'noticeid' => $notice_id,
							'sid' => $values['sid'],
							'userid' => $values['id'],
							'openid' => $openid,
							'createtime' => $notice['createtime']
						);
						pdo_insert($this->table_record, $date);
						$record_id = pdo_insertid();
						if(!empty($notice['outurl'])){
							$url = $notice['outurl'];
						}else{
							$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('snotice', array('schoolid' => $schoolid,'record_id' => $record_id,'id' => $notice_id,'userid' => $values['id']));
						}
						$this->sendtempmsg($template_id, $url, $data, '#FF0000', $openid);
					}					
				}
			}else{
					$openid = $openidall['0']['openid'];
					
					$record = pdo_fetch("SELECT * FROM ".tablename($this->table_record)." where weid = :weid And schoolid = :schoolid And noticeid = :noticeid And openid = :openid And sid = :sid And userid = :userid And createtime = :createtime",array(
										':weid'=>$_W['uniacid'],
										':schoolid'=>$schoolid,
										':noticeid'=>$notice_id,
										':openid'=>$openid,
										':sid'=>$openidall['0']['sid'],
										':userid'=>$openidall['0']['id'],
										':createtime'=>$notice['createtime'],
					));
					$student = pdo_fetch("SELECT s_name FROM ".tablename($this->table_students)." where id = :id",array(':id'=>$openidall['0']['sid']));
					if($openidall['0']['pard'] == 2){
						$guanxi = "妈妈";
					}else if($openidall['0']['pard'] == 3){
						$guanxi = "爸爸";
					}else if($openidall['0']['pard'] == 4){
						$guanxi = "";
					}
					$title = "【{$student['s_name']}】{$guanxi}，您收到一条班级通知";
					$datas=array(
						'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
						'first'=>array('value'=>$title,'color'=>'#1587CD'),
						'keyword1'=>array('value'=>$bjname,'color'=>'#1587CD'),
						'keyword2'=>array('value'=>$name,'color'=>'#2D6A90'),
						'keyword3'=>array('value'=>$ttime,'color'=>'#1587CD'),
						'keyword4'=>array('value'=>$notice['title'],'color'=>'#1587CD'),
						'remark'=> array('value'=>$body,'color'=>'#FF9E05')
								);
					$data = json_encode($datas); //发送的消息模板数据					
					if(empty($record)){	
						$date = array(
							'weid' =>  $_W['uniacid'],
							'schoolid' => $schoolid,
							'noticeid' => $notice_id,
							'sid' => $openidall['0']['sid'],
							'userid' => $openidall['0']['id'],
							'openid' => $openid,
							'createtime' => $notice['createtime']
						);
						pdo_insert($this->table_record, $date);
						$record_id = pdo_insertid();
						if(!empty($notice['outurl'])){
						$url = $notice['outurl'];
						}else{
							$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('snotice', array('schoolid' => $schoolid,'record_id' => $record_id,'id' => $notice_id,'userid' => $openidall['0']['id']));
						}
						$this->sendtempmsg($template_id, $url, $data, '#FF0000', $openid);
					}
			}
		}
	}	
	public function doWebXytzMsg(){
		global $_GPC,$_W;
		$notice_id = $_GPC['notice_id'];
		$schoolid = $_GPC['schoolid'];
		$weid = $_GPC['weid'];
		$tname = $_GPC['tname'];
		$groupid = $_GPC['groupid'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 2;
		if ($groupid == 1) {

		$userinfo = pdo_fetchall("SELECT * FROM ".tablename($this->table_user)." where weid = :weid And schoolid = :schoolid ORDER BY id ASC ",array(':weid'=>$weid, ':schoolid'=>$schoolid));
		$total = pdo_fetchcolumn("SELECT COUNT(1) FROM ".tablename($this->table_user)." where weid = :weid And schoolid = :schoolid",array(':weid'=>$weid, ':schoolid'=>$schoolid));

		}
		if ($groupid == 2) {

		$userinfo = pdo_fetchall("SELECT * FROM ".tablename($this->table_teachers)." where weid = :weid And schoolid = :schoolid ORDER BY id ASC ",array(':weid'=>$weid, ':schoolid'=>$schoolid));
		$total = pdo_fetchcolumn("SELECT COUNT(1) FROM ".tablename($this->table_teachers)." where weid = :weid And schoolid = :schoolid",array(':weid'=>$weid, ':schoolid'=>$schoolid));

		}
		if ($groupid == 3) {

		$userinfo = pdo_fetchall("SELECT * FROM ".tablename($this->table_students)." where weid = :weid And schoolid = :schoolid ORDER BY id ASC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,array(':weid'=>$weid, ':schoolid'=>$schoolid));
		$total = pdo_fetchcolumn("SELECT COUNT(1) FROM ".tablename($this->table_students)." where weid = :weid And schoolid = :schoolid",array(':weid'=>$weid, ':schoolid'=>$schoolid));

        }
		$tp = ceil($total/$psize);
			//echo '第' . $pindex . '次,总共'.$tp.'次';

		for ($i=1; $i < $tp; $i++) {
			$this->sendMobileXytz($notice_id, $schoolid, $weid, $tname, $groupid, $pindex, $psize);
			if ($pindex == $i) {
				$mq = round(($pindex / $tp) * 100);
				$msg = '正在发送，目前：<strong style="color:#5cb85c">' . $mq . ' %</strong>,请勿执行任何操作';

				$page = $pindex + 1;
				$to = $this -> createWebUrl('XytzMsg', array('notice_id' => $notice_id, 'schoolid' => $schoolid, 'weid' => $weid, 'tname' => $tname, 'groupid' => $groupid, 'page' => $page));
				message($msg, $to);
			}
		}
		message('发送成功！', $this -> createWebUrl('notice', array('op' => 'display5','schoolid' => $schoolid,'notice_id' => $notice_id)));
	}
	
	public function sendMobileXytz($notice_id, $schoolid, $weid, $tname, $groupid,$pindex='1', $psize='20') {
		global $_GPC,$_W;
		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));
		$notice = pdo_fetch("SELECT * FROM ".tablename($this->table_notice)." WHERE :weid = weid AND :id = id AND :schoolid = schoolid", array(':weid' => $weid, ':id' => $notice_id, ':schoolid' => $schoolid));
		$school = pdo_fetch("SELECT * FROM ".tablename($this->table_index)." WHERE :weid = weid AND :id = id", array(':weid' => $weid, ':id' => $schoolid));
        $template_id = $msgtemplate['xxtongzhi'];//消息模板id 微信的模板id

		if ($groupid == 1) {

		$userinfo = pdo_fetchall("SELECT * FROM ".tablename($this->table_user)." where weid = :weid And schoolid = :schoolid LIMIT " . ($pindex - 1) * $psize . ',' . $psize,array(':weid'=>$weid, ':schoolid'=>$schoolid));

		}
		if ($groupid == 2) {

		$userinfo = pdo_fetchall("SELECT * FROM ".tablename($this->table_teachers)." where weid = :weid And schoolid = :schoolid LIMIT " . ($pindex - 1) * $psize . ',' . $psize,array(':weid'=>$weid, ':schoolid'=>$schoolid));

		}
		if ($groupid == 3) {
			$userinfo = pdo_fetchall("SELECT * FROM ".tablename($this->table_students)." where weid = :weid And schoolid = :schoolid ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,array(':weid'=>$weid, ':schoolid'=>$schoolid));

        }

		foreach ($userinfo as $key => $value) {

			$openid = "";

				if ($groupid == 2) {
				    $openid = pdo_fetch("select * from ".tablename($this->table_user)." where tid = '{$value['id']}' ");
					$title = "【{$value['tname']}】老师，您收到一条学校通知";
					$schoolname ="{$school['title']}";
					$name  = "{$tname}老师";
					$ttime = date('Y-m-d H:i:s', $notice['createtime']);
					$body  = "点击本条消息查看详情 ";
					$datas=array(
						'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
						'first'=>array('value'=>$title,'color'=>'#1587CD'),
						'keyword1'=>array('value'=>$schoolname,'color'=>'#1587CD'),
						'keyword2'=>array('value'=>$name,'color'=>'#2D6A90'),
						'keyword3'=>array('value'=>$ttime,'color'=>'#1587CD'),
						'keyword4'=>array('value'=>$notice['title'],'color'=>'#1587CD'),
						'remark'=> array('value'=>$body,'color'=>'#FF9E05')
								);
					$data = json_encode($datas); //发送的消息模板数据
					//message($datas);
					
					$record = pdo_fetch("SELECT * FROM ".tablename($this->table_record)." where weid = :weid And schoolid = :schoolid And noticeid = :noticeid And openid = :openid And tid = :tid And userid = :userid And createtime = :createtime",array(
										':weid'=>$_W['uniacid'],
										':schoolid'=>$schoolid,
										':noticeid'=>$notice_id,
										':openid'=>$openid['openid'],
										':tid'=>$openid['tid'],
										':userid'=>$openid['id'],
										':createtime'=>$notice['createtime'],
					));
					if(empty($record)){		
						$date = array(
							'weid' =>  $_W['uniacid'],
							'schoolid' => $schoolid,
							'noticeid' => $notice_id,
							'tid' => $openid['tid'],
							'userid' => $openid['id'],
							'openid' => $openid['openid'],
							'createtime' => $notice['createtime']
						);
						pdo_insert($this->table_record, $date);
						$record_id = pdo_insertid();
						
						if(!empty($notice['outurl'])){
							$url = $notice['outurl'];
						}else{
							$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('mnotice', array('schoolid' => $schoolid,'id' => $notice_id,'record_id' => $record_id));
						}
						$this->sendtempmsg($template_id, $url, $data, '#FF0000', $openid['openid']);						
					}	
				}else{


					if ($groupid == 1) {
					    $openid = pdo_fetch("select * from ".tablename($this->table_user)." where id = '{$value['id']}' ");
					    if(!empty($value['pard'])){
							$student = pdo_fetch("SELECT s_name FROM ".tablename($this->table_students)." where id = :id",array(':id'=>$value['sid']));
							if($value['pard'] == 2){
								$guanxi = "妈妈";
							}else if($value['pard'] == 3){
								$guanxi = "爸爸";
							}else if($value['pard'] == 4){
								$guanxi = "";
							}
							$title = "【{$student['s_name']}】{$guanxi}，您收到一条学校通知";
						}else{
							$teacher = pdo_fetch("SELECT tname FROM ".tablename($this->table_teachers)." where id = :id",array(':id'=>$value['tid']));
							$title = "【{$teacher['tname']}】老师，您收到一条学校通知";
						}
						$schoolname ="{$school['title']}";
						$name  = "{$tname}老师";
						$ttime = date('Y-m-d H:i:s', $notice['createtime']);
						$body  = "点击本条消息查看详情 ";
						$datas=array(
							'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
							'first'=>array('value'=>$title,'color'=>'#1587CD'),
							'keyword1'=>array('value'=>$schoolname,'color'=>'#1587CD'),
							'keyword2'=>array('value'=>$name,'color'=>'#2D6A90'),
							'keyword3'=>array('value'=>$ttime,'color'=>'#1587CD'),
							'keyword4'=>array('value'=>$notice['title'],'color'=>'#1587CD'),
							'remark'=> array('value'=>$body,'color'=>'#FF9E05')
									);
						$data = json_encode($datas); //发送的消息模板数据
						if(!empty($value['pard'])){ //判断身份 然后检测是否发送本消息
							$record = pdo_fetch("SELECT * FROM ".tablename($this->table_record)." where weid = :weid And schoolid = :schoolid And noticeid = :noticeid And openid = :openid And sid = :sid And userid = :userid And createtime = :createtime",array(
												':weid'=>$_W['uniacid'],
												':schoolid'=>$schoolid,
												':noticeid'=>$notice_id,
												':openid'=>$openid['openid'],
												':sid'=>$openid['sid'],
												':userid'=>$openid['id'],
												':createtime'=>$notice['createtime'],
							));
							if(empty($record)){
								$date = array(
									'weid' =>  $_W['uniacid'],
									'schoolid' => $schoolid,
									'noticeid' => $notice_id,
									'sid' => $openid['sid'],
									'userid' => $openid['id'],
									'openid' => $openid['openid'],
									'createtime' => $notice['createtime']
								);
								pdo_insert($this->table_record, $date);
								$record_id = pdo_insertid();
								if(!empty($notice['outurl'])){
									$url = $notice['outurl'];
								}else{
									$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('snotice', array('schoolid' => $schoolid,'record_id' => $record_id,'id' => $notice_id,'userid' => $value['id']));
								}
								$this->sendtempmsg($template_id, $url, $data, '#FF0000', $openid['openid']);
							}							
						}else{
							$record = pdo_fetch("SELECT * FROM ".tablename($this->table_record)." where weid = :weid And schoolid = :schoolid And noticeid = :noticeid And openid = :openid And tid = :tid And userid = :userid And createtime = :createtime",array(
												':weid'=>$_W['uniacid'],
												':schoolid'=>$schoolid,
												':noticeid'=>$notice_id,
												':openid'=>$openid['openid'],
												':tid'=>$openid['tid'],
												':userid'=>$openid['id'],
												':createtime'=>$notice['createtime'],
							));
							if(empty($record)){
								$date = array(
									'weid' =>  $_W['uniacid'],
									'schoolid' => $schoolid,
									'noticeid' => $notice_id,
									'tid' => $openid['tid'],
									'userid' => $openid['id'],
									'openid' => $openid['openid'],
									'createtime' => $notice['createtime']
								);
								pdo_insert($this->table_record, $date);
								$record_id = pdo_insertid();
								if(!empty($notice['outurl'])){
									$url = $notice['outurl'];
								}else{
									$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('snotice', array('schoolid' => $schoolid,'record_id' => $record_id,'id' => $notice_id,'userid' => $value['id']));
								}
								$this->sendtempmsg($template_id, $url, $data, '#FF0000', $openid['openid']);
							}							
						}						
			        }
					if ($groupid == 3) {
					   	$openid = pdo_fetchall("select * from ".tablename($this->table_user)." where sid = '{$value['id']}' ");

						foreach($openid as $o) {
							if($o['pard'] == 2){
								$guanxi = "妈妈";
							}else if($o['pard'] == 3){
								$guanxi = "爸爸";
							}else if($o['pard'] == 4){
								$guanxi = "";
							}
							$title = "【{$value['s_name']}】{$guanxi}，您收到一条学校通知";
							$schoolname ="{$school['title']}";
							$name  = "{$tname}老师";
							$ttime = date('Y-m-d H:i:s', $notice['createtime']);
							$body  = "点击本条消息查看详情 ";
							$datas=array(
								'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
								'first'=>array('value'=>$title,'color'=>'#1587CD'),
								'keyword1'=>array('value'=>$schoolname,'color'=>'#1587CD'),
								'keyword2'=>array('value'=>$name,'color'=>'#2D6A90'),
								'keyword3'=>array('value'=>$ttime,'color'=>'#1587CD'),
								'keyword4'=>array('value'=>$notice['title'],'color'=>'#1587CD'),
								'remark'=> array('value'=>$body,'color'=>'#FF9E05')
										);
							$data = json_encode($datas); //发送的消息模板数据
							$record = pdo_fetch("SELECT * FROM ".tablename($this->table_record)." where weid = :weid And schoolid = :schoolid And noticeid = :noticeid And openid = :openid And sid = :sid And userid = :userid And createtime = :createtime",array(
												':weid'=>$_W['uniacid'],
												':schoolid'=>$schoolid,
												':noticeid'=>$notice_id,
												':openid'=>$o['openid'],
												':sid'=>$o['sid'],
												':userid'=>$o['id'],
												':createtime'=>$notice['createtime'],
							));
							if(empty($record)){
								
								$date = array(
									'weid' =>  $_W['uniacid'],
									'schoolid' => $schoolid,
									'noticeid' => $notice_id,
									'sid' => $o['sid'],
									'userid' => $o['id'],
									'openid' => $o['openid'],
									'createtime' => $notice['createtime']
								);
								pdo_insert($this->table_record, $date);
								$record_id = pdo_insertid();
								if(!empty($notice['outurl'])){
									$url = $notice['outurl'];
								}else{
									$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('snotice', array('schoolid' => $schoolid,'record_id' => $record_id,'id' => $notice_id,'userid' => $o['id']));
								}
								$this->sendtempmsg($template_id, $url, $data, '#FF0000', $o['openid']);
							}
						}
					}
				}
		}
	}

	public function sendMobileFxtz($schoolid, $weid, $tname, $bj_id) {
		global $_GPC,$_W;
		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));
        $template_id = $msgtemplate['bjtz'];//消息模板id 微信的模板id
		$bname = pdo_fetch("SELECT * FROM ".tablename($this->table_classify)." WHERE :weid = weid AND :schoolid =schoolid And :sid = sid", array(':weid' => $weid, ':schoolid' => $schoolid, ':sid' => $bj_id));
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;

		$userinfo = pdo_fetchall("SELECT * FROM ".tablename($this->table_students)." where weid = :weid And schoolid = :schoolid And bj_id = :bj_id",array(':weid'=>$weid, ':schoolid'=>$schoolid, ':bj_id'=>$bj_id));

		foreach ($userinfo as $key => $value) {

			$openidall = pdo_fetchall("select openid from ".tablename($this->table_user)." where sid = '{$value['id']}' ");
			$s_name = $value['s_name'];
			$name  = "班主任-{$tname}";
			$title = "{$s_name}家长，您收到一条学生放学通知";
			$bjname  = "{$bname['sname']}";
			$ttime = date('Y-m-d H:i:s', TIMESTAMP);
			$notice  = "本班已经放学，请家长留意学生放学后动态，确认是否安全回家";
			$body  = "";
			$datas=array(
				'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
				'first'=>array('value'=>$title,'color'=>'#FF9E05'),
				'keyword1'=>array('value'=>$bjname,'color'=>'#1587CD'),
				'keyword2'=>array('value'=>$name,'color'=>'#2D6A90'),
				'keyword3'=>array('value'=>$ttime,'color'=>'#1587CD'),
				'keyword4'=>array('value'=>$notice,'color'=>'#1587CD'),
				'remark'=> array('value'=>$body,'color'=>'#FF9E05')
						);
			$data = json_encode($datas); //发送的消息模板数据
			$url = "";
			$num = count($openidall);
			if ($num > 1){
				foreach ($openidall as $key => $values) {
					$openid = $values['openid'];
					$this->sendtempmsg($template_id, $url, $data, '#FF0000', $openid);
				}
			}else{
					$openid = $openidall['0']['openid'];
					$this->sendtempmsg($template_id, $url, $data, '#FF0000', $openid);
			}
		}
	}

	public function sendMobileXsqj($leave_id, $schoolid, $weid, $tid) {
		global $_GPC,$_W;
		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));
		$leave = pdo_fetch("SELECT * FROM ".tablename($this->table_leave)." WHERE :weid = weid AND :id = id AND :schoolid = schoolid", array(':weid' => $weid, ':id' => $leave_id, ':schoolid' => $schoolid));
        $template_id = $msgtemplate['xsqingjia'];//消息模板id 微信的模板id
        $student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $leave['sid']));
        $teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $tid));

		$guanxi = "本人";

		if($student['muid'] == $leave['uid']){
			$guanxi = "妈妈";
		}else if($student['duid'] == $leave['uid']) {
			$guanxi = "爸爸";
		}

        if (!empty($template_id)) {

		$shenfen = "{$student['s_name']}{$guanxi}";
	    $stime = $leave['startime'];
	    $etime = $leave['endtime'];
		$ttime = date('Y-m-d H:i:s', $leave['createtime']);
		$time  = "{$stime}至{$etime}";
		$body .= "消息时间：{$ttime} \n";
		$body .= "点击本条消息快速处理 ";
	    $datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
		'first'=>array('value'=>'您收到了一条'.$shenfen.'的请假申请','color'=>'#1587CD'),
		'childName'=>array('value'=>$student['s_name'],'color'=>'#1587CD'),
		'time'=>array('value'=>$time,'color'=>'#2D6A90'),
		'score'=>array('value'=>$leave['conet'],'color'=>'#1587CD'),
		'remark'=> array('value'=>$body,'color'=>'#FF9E05')
	     );
	    $data=json_encode($datas); //发送的消息模板数据
        }
			$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('smcomet', array('schoolid' => $schoolid,'id' => $leave_id));
		//}

		if (!empty($template_id)) {
			$this->sendtempmsg($template_id, $url, $data, '#FF0000', $teacher['openid']);
		}
	}

	public function sendMobileXsqjsh($leaveid, $schoolid, $weid, $tname) {
		global $_GPC,$_W;
		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));
		$leave = pdo_fetch("SELECT * FROM ".tablename($this->table_leave)." WHERE :weid = weid AND :id = id AND :schoolid = schoolid", array(':weid' => $weid, ':id' => $leaveid, ':schoolid' => $schoolid));
		$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $leave['sid']));
        $template_id = $msgtemplate['xsqjsh'];//消息模板id 微信的模板id
        $jieguo = "";
		if($leave['status'] ==1){
			$jieguo = "同意";
		}else{
			$jieguo = "不同意";
		}

        if (!empty($template_id)) {
		$stime = $leave['startime'];
	    $etime = $leave['endtime'];
		$time = "{$stime}至{$etime}";
		$ctime = date('Y-m-d H:i:s', $leave['cltime']);
		$body .= "处理时间：{$ctime} \n";
		$body .= "";
	    $datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
		'first'=>array('value'=>'您好，'.$tname.'老师已经回复了您的请假申请','color'=>'#1587CD'),
		'keyword1'=>array('value'=>$student['s_name'],'color'=>'#1587CD'),
		'keyword2'=>array('value'=>$time,'color'=>'#2D6A90'),
		'keyword3'=>array('value'=>$jieguo,'color'=>'#1587CD'),
		'keyword4'=>array('value'=>$tname,'color'=>'#1587CD'),
		'remark'=> array('value'=>$body,'color'=>'#FF9E05')
	     );
	    $data=json_encode($datas); //发送的消息模板数据
        }
			//$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('smcomet', array('schoolid' => $schoolid,'id' => $leaveid));
		//}

		if (!empty($template_id)) {
			$this->sendtempmsg($template_id, $url, $data, '#FF0000', $leave['openid']);
		}
	}

	public function sendMobileJzly($leave_id, $schoolid, $weid, $uid, $bj_id, $sid, $tid) {
		global $_GPC,$_W;

		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));
        $leave = pdo_fetch("SELECT * FROM ".tablename($this->table_leave)." WHERE :weid = weid AND :id = id AND :schoolid = schoolid", array(':weid' => $weid, ':id' => $leave_id, ':schoolid' => $schoolid));
		$students = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $sid));
		$msgs = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND schoolid=:schoolid AND status=:status", array(':weid' => $weid, ':schoolid' => $schoolid, ':status' => 2));
		$template_id = $msgtemplate['liuyan'];//消息模板id 微信的模板id
		$teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $tid));//查询master

		$guanxi = "本人";

		if($students['muid'] == $uid){
			$guanxi = "妈妈";
		}else if($students['duid'] == $uid) {
			$guanxi = "爸爸";
		}

        if (!empty($template_id)) {
		$time = date('Y-m-d H:i:s', $leave['createtime']);
		$data1 = "{$students['s_name']}{$guanxi}";
		$body .= "留言摘要：{$leave['conet']} \n";
		$body .= "点击本条消息快速回复 ";
	    $datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
		'first'=>array('value'=>'您收到了一条留言信息！','color'=>'#1587CD'),
		'keyword1'=>array('value'=>$data1,'color'=>'#1587CD'),
		'keyword2'=>array('value'=>$time,'color'=>'#2D6A90'),
		'remark'=> array('value'=>$body,'color'=>'#FF9E05')
	     );
	    $data=json_encode($datas); //发送的消息模板数据
        }

		$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('tjiaoliu', array('schoolid' => $schoolid,'id' => $leave_id,'leaveid' => $leave['leaveid']));

		if (!empty($template_id)) {
			$this->sendtempmsg($template_id, $url, $data, '#FF0000', $teacher['openid']);
		}
	}

	public function sendMobileJzlyhf($leave_id, $schoolid, $weid, $topenid, $sid, $tname) {
		global $_GPC,$_W;

		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));
        $leave = pdo_fetch("SELECT * FROM ".tablename($this->table_leave)." WHERE :weid = weid AND :id = id AND :schoolid = schoolid", array(':weid' => $weid, ':id' => $leave_id, ':schoolid' => $schoolid));
		$students = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $sid));
		$msgs = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND schoolid=:schoolid AND status=:status", array(':weid' => $weid, ':schoolid' => $schoolid, ':status' => 2));
		$template_id = $msgtemplate['liuyanhf'];//消息模板id 微信的模板id
		$teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $tid));//查询master

		$guanxi = "";

		if($students['muid'] == $uid){
			$guanxi = "妈妈";
		}else if($students['duid'] == $uid) {
			$guanxi = "爸爸";
		}

        if (!empty($template_id)) {
		$time = date('Y-m-d H:i:s', $leave['createtime']);
		$data1 = "{$students['s_name']}{$guanxi},您收到了一条老师的留言回复信息！";
		$body = "点击本条消息快速回复 ";
	    $datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
		'first'=>array('value'=>$data1,'color'=>'#1587CD'),
		'keyword1'=>array('value'=>$tname,'color'=>'#1587CD'),
		'keyword2'=>array('value'=>$time,'color'=>'#2D6A90'),
		'keyword3'=>array('value'=>$leave['conet'],'color'=>'#2D6A90'),
		'remark'=> array('value'=>$body,'color'=>'#FF9E05')
	     );
	    $data=json_encode($datas); //发送的消息模板数据
        }

		$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('jiaoliu', array('schoolid' => $schoolid));

		if (!empty($template_id)) {
			$this->sendtempmsg($template_id, $url, $data, '#FF0000', $topenid);
		}
	}

	public function sendMobileJsqj($leave_id, $schoolid, $weid) {
		global $_GPC,$_W;
		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));
		$leave = pdo_fetch("SELECT * FROM ".tablename($this->table_leave)." WHERE :weid = weid AND :id = id AND :schoolid = schoolid", array(':weid' => $weid, ':id' => $leave_id, ':schoolid' => $schoolid));
		$msgs = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND schoolid=:schoolid AND status=:status", array(':weid' => $weid, ':schoolid' => $schoolid, ':status' => 2));
        $template_id = $msgtemplate['jsqingjia'];//消息模板id 微信的模板id
        $teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $leave['tid']));


        if (!empty($template_id)) {

	    $stime = $leave['startime'];
	    $etime = $leave['endtime'];
		$time = "{$stime}至{$etime}";
		$body = "点击本条消息快速处理 ";
	    $datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
		'first'=>array('value'=>'您收到了一条教师请假申请','color'=>'#1587CD'),
		'keyword1'=>array('value'=>$teacher['tname'],'color'=>'#1587CD'),
		'keyword2'=>array('value'=>$leave['type'],'color'=>'#2D6A90'),
		'keyword3'=>array('value'=>$time,'color'=>'#1587CD'),
		'keyword4'=>array('value'=>$leave['conet'],'color'=>'#173177'),
		'remark'=> array('value'=>$body,'color'=>'#FF9E05')
	     );
	    $data=json_encode($datas); //发送的消息模板数据
        }
			$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('tmcomet', array('schoolid' => $schoolid,'id' => $leave_id));
		//}


		if (!empty($template_id)) {
			$this->sendtempmsg($template_id, $url, $data, '#FF0000', $msgs['openid']);
		}
	}

	public function sendMobileJsqjsh($leaveid, $schoolid, $weid) {
		global $_GPC,$_W;
		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));
		$leave = pdo_fetch("SELECT * FROM ".tablename($this->table_leave)." WHERE :weid = weid AND :id = id AND :schoolid = schoolid", array(':weid' => $weid, ':id' => $leaveid, ':schoolid' => $schoolid));
		$msgs = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND schoolid=:schoolid AND status=:status", array(':weid' => $weid, ':schoolid' => $schoolid, ':status' => 2));
        $template_id = $msgtemplate['jsqjsh'];//消息模板id 微信的模板id
        $teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $leave['tid']));
        $jieguo = "";
		if($leave['status'] ==1){
			$jieguo = "同意";
		}else{
			$jieguo = "不同意";
		}

        if (!empty($template_id)) {

		$time = date('Y-m-d H:i:s', $leave['cltime']);
		$body = "点击本条消息查看详情 ";
	    $datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
		'first'=>array('value'=>'请假审批结果通知','color'=>'#1587CD'),
		'keyword1'=>array('value'=>$jieguo,'color'=>'#1587CD'),
		'keyword2'=>array('value'=>$msgs['tname'],'color'=>'#2D6A90'),
		'keyword3'=>array('value'=>$time,'color'=>'#1587CD'),
		'remark'=> array('value'=>$body,'color'=>'#FF9E05')
	     );
	    $data=json_encode($datas); //发送的消息模板数据
        }
			$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('tmcomet', array('schoolid' => $schoolid,'id' => $leaveid));
		//}

		if (!empty($template_id)) {
			$this->sendtempmsg($template_id, $url, $data, '#FF0000', $leave['openid']);
		}
	}

	public function sendMobileJxlxtz($schoolid, $weid, $bj_id, $sid, $type, $leixing, $id, $pard) {
		global $_GPC,$_W;
		$msgtemplate = pdo_fetch("SELECT * FROM ".tablename($this->table_set)." WHERE :weid = weid", array(':weid' => $weid));
        $template_id = $msgtemplate['jxlxtx'];//消息模板id 微信的模板id
		$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where id = :id ", array(':id' => $sid));
		$userinfo = pdo_fetchall("SELECT * FROM ".tablename($this->table_user)." where weid = :weid And schoolid = :schoolid And sid = :sid",array(':weid'=>$weid, ':schoolid'=>$schoolid, ':sid'=>$sid));

		foreach ($userinfo as $key => $value) {

			$openid = pdo_fetch("select id,openid from ".tablename($this->table_user)." where id = '{$value['id']}' ");
			$s_name = $student['s_name'];
			include 'pard.php';
			$guanxi = "";
			if($leixing == 1){
				$lx = "进校";
				if($pard >1){
					$body  = "您的孩子已由【{$jsr}】安全送到,点击详情查看更多";
				}else{
					$body  = "打卡成功,点击详情查看";
				}
			}else{
				$lx = "离校";
				if($pard >1){
					$body  = "您的孩子已由【{$jsr}】安全接到,点击详情查看更多";
				}else{
					$body  = "打卡成功,点击详情查看";
				}
			}
			if($value['pard'] == 2){
				$guanxi = "妈妈";
			}else if($value['pard'] == 3) {
				$guanxi = "爸爸";
			}			
			if($openid['pard'] < 4){
				$title = "【{$s_name}】{$guanxi},您收到一条学生{$lx}通知";
			}else{
				$title = "【{$s_name}】,您收到一条学生{$lx}通知";
			}
			$ttime = date('Y-m-d H:i:s', TIMESTAMP);
			$datas=array(
				'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
				'first'=>array('value'=>$title,'color'=>'#FF9E05'),
				'childName'=>array('value'=>$s_name,'color'=>'#1587CD'),
				'time'=>array('value'=>$ttime,'color'=>'#2D6A90'),
				'status'=>array('value'=>$type,'color'=>'#1587CD'),
				'remark'=> array('value'=>$body,'color'=>'#FF9E05')
						);
			$data = json_encode($datas); //发送的消息模板数据
			$url =  $_W['siteroot'] .'app/'.$this->createMobileUrl('checklog', array('schoolid' => $schoolid,'userid' => $openid['id']));
			$this->sendtempmsg($template_id, $url, $data, '#FF0000', $openid['openid']);

		}
	}	
}