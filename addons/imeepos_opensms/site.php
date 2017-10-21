<?php
/**
 * 小明短信联盟模块微站定义
 *
 * @author imeepos
 */
defined('IN_IA') or exit('Access Denied');

class Imeepos_opensmsModuleSite extends WeModuleSite {
	public $modulename = 'imeepos_opensms';

	public function template($filename) {
		global $_W;
		$name = strtolower($this->modulename);
		$defineDir = dirname($this->__define);
		if(defined('IN_SYS')) {
			$source = IA_ROOT . "/web/themes/{$_W['template']}/{$name}/{$filename}.html";
			$compile = IA_ROOT . "/data/tpl/web/{$_W['template']}/{$name}/{$filename}.tpl.php";
			if(!is_file($source)) {
				$source = IA_ROOT . "/web/themes/default/{$name}/{$filename}.html";
			}
			if(!is_file($source)) {
				$source = $defineDir . "/template/{$filename}.html";
			}
			if(!is_file($source)) {
				$source = IA_ROOT . "/web/themes/{$_W['template']}/{$filename}.html";
			}
			if(!is_file($source)) {
				$source = IA_ROOT . "/web/themes/default/{$filename}.html";
			}
		} else {
			$source = IA_ROOT . "/app/themes/{$_W['template']}/{$name}/{$filename}.html";
			$compile = IA_ROOT . "/data/tpl/app/{$_W['template']}/{$name}/{$filename}.tpl.php";
			if(!is_file($source)) {
				$source = IA_ROOT . "/app/themes/default/{$name}/{$filename}.html";
			}
			if(!is_file($source)) {
				$source = $defineDir . "/template/mobile/{$filename}.html";
			}
			if(!is_file($source)) {
				$source = IA_ROOT . "/app/themes/{$_W['template']}/{$filename}.html";
			}
			if(!is_file($source)) {
				if (in_array($filename, array('header', 'footer', 'slide', 'toolbar', 'message'))) {
					$source = IA_ROOT . "/app/themes/default/common/{$filename}.html";
				} else {
					$source = IA_ROOT . "/app/themes/default/{$filename}.html";
				}
			}
		}
		if(!is_file($source)) {
			exit("Error: template source '{$source}' is not exist!");
		}
		$paths = pathinfo($compile);
		$compile = str_replace($paths['filename'], $_W['uniacid'] . '_' . $paths['filename'], $compile);
		if (DEVELOPMENT || !is_file($compile) || filemtime($source) > filemtime($compile)) {
			template_compile($source, $compile, true);
		}
		return $compile;
	}
	public function M($name){
		static $imeepos_opensms_model = array();
		$class = "ImeeposOpenSms_".$name;
		if(empty($imeepos_opensms_model[$name])) {
			include IA_ROOT.'/addons/imeepos_opensms/model/'.$name.'.php';
			$imeepos_opensms_model[$name] = new $class();
		}
		return $imeepos_opensms_model[$name];
	}
	public function doWebtest(){
	    global $_W,$_GPC;
	    $_GPC['do'] = 'test';
		$code = '1234';
		$mobile = '13140415408';
		$member = array('nickname'=>'测试');
	    $return = $this->sendSmsCode($code,$mobile,$member);
		if(is_error($return)){
			message($return['message'],referer(),'error');
		}else{
			message('发送成功',$this->createWebUrl('log'),'success');
		}
	}
	public function imeeposRunnerPostTask($data = array()){
		global $_W;
		$media_id = ""; //录音单
		$desc = "";//订单简介
		$total = "";//总价
		$small_money = "";//小费
		$limit_time = "";//送达时间
		$code = "";//送达码
		$qrcode = "";//收货二维码
		$message = "";//留言

		$tasks = array();
		$tasks['openid'] = $_W['openid'];
		$tasks['uniacid'] = $_W['uniacid'];
		$tasks['status'] = 0;
		$tasks['create_time'] = time();
		$tasks['media_id'] = $media_id;
		$tasks['desc'] = $desc;
		$tasks['total'] = $total;
		$tasks['small_money'] = $small_money;
		$tasks['limit_time'] = $limit_time;
		$tasks['type'] = 1;
		$tasks['code'] = $code;
		$tasks['qrcode'] = $qrcode;
		$tasks['message'] = $message;
		$task = $this->M('tasks')->update($tasks);
		
		$data = array();
		$data['goodsname'] = '0';//物品名称
		$data['senddetail'] = '';//发货详细地址
		$data['sendrealname'] = '';//发货人姓名
		$data['sendmobile'] = '';//发货人手机号码
		$data['receivedetail'] = '';//收货详细地址
		$data['receiverealname'] = '';//收货人姓名
		$data['receivemobile'] = '';//收货人电话
		$data['message'] = '';//接单留言
		$data['sendlon'] = '';//发货维度
		$data['sendlat'] = '';//发货经度
		$data['receivelon'] = '';//收货维度
		$data['receivelat'] = '';//收货经度
		$data['images'] = serialize(array());//任务附件
		$data['distance'] = '';//总路程
		$data['limit_time'] = '';//要求任务送达时间
		$data['small_money'] = '';//小费*/
		$data['openid'] = $_W['openid'];


	}
	public function doMobileindex(){
	    global $_W,$_GPC;
	    $_GPC['do'] = 'index';
	    include $this->template('index');
	}

	public function __construct()
	{
		global $_W;
		$this->__define = IA_ROOT.'/addons/imeepos_opensms/site.php';
		$this->system = $this->M('setting')->getSetting('system');
		$this->tpl = $this->M('setting')->getSetting('tpl');
		$this->appkey = $this->system['appkey'];
		$this->code_tpl = $this->system['code_tpl'];
		$this->signname = $this->system['signname'];
		$this->appsecret = $this->system['appsecret'];
		$this->content_tpl = $this->system['content_tpl'];
	}
	protected function sendSmsSystem($code,$mobile,$member){
		load()->model('cloud');
		$content = $this->content_tpl;
		$content = str_replace('#nickname#',$member['nickname'],$content);
		$content = str_replace('#code#',$code,$content);
		$return = cloud_sms_send($mobile,$content);
		if(is_error($return)){
			return $return;
		}
		return true;
	}
	public function sendSmsAlidayuVoice($code,$mobile,$member){
		include IA_ROOT."/addons/imeepos_opensms/libs/TopSdk.php";
		$c = new TopClient();
		$c->appkey = $this->appkey;
		$c->secretKey = $this->appsecret;
		$req = new AlibabaAliqinFcTtsNumSinglecallRequest();
		$req ->setExtend("123456");
		$data = array();
		$setting = $this->M('setting')->getSetting('system');
		if(!empty($setting['nickname_title'])){
			$data[$setting['nickname_title']] = $member['nickname'];
		}
		if(!empty($setting['code_title'])){
			$data[$setting['code_title']] = $code;
		}
		if(!empty($setting['product_title'])){
			$data[$setting['product_title']] = '小明跑腿';
		}
		$json = json_encode($data);
		$req ->setTtsParam($json);
		$req ->setCalledNum($mobile);
		$req ->setCalledShowNum($this->calledShowNum);
		$req ->setTtsCode($this->code_tpl);
		$return = $c ->execute( $req );
		return $return;
	}
	protected function sendSmsAlidayu($code,$mobile,$member){
		include IA_ROOT."/addons/imeepos_opensms/libs/TopSdk.php";
		$c = new TopClient();
		$c->appkey = $this->appkey;
		$c->secretKey = $this->appsecret;
		$req = new AlibabaAliqinFcSmsNumSendRequest();
		$req->setExtend("123456");
		$req->setSmsType("normal");
		$req->setSmsFreeSignName($this->signname);
		$data = array();
		$setting = $this->M('setting')->getSetting('system');
		if(!empty($setting['nickname_title'])){
			$data[$setting['nickname_title']] = $member['nickname'];
		}
		if(!empty($setting['code_title'])){
			$data[$setting['code_title']] = $code;
		}
		if(!empty($setting['product_title'])){
			$data[$setting['product_title']] = $setting['product_value'];
		}
		$json = json_encode($data);
		$req->setSmsParam($json);
		$req->setRecNum($mobile);
		$req->setSmsTemplateCode($this->code_tpl);
		$return = $c->execute($req);
		return $return;
	}
	protected function sendSmsJuhe($code,$mobile,$member){
		$sendUrl = 'http://v.juhe.cn/sms/send';
		$smsConf = array(
			'key'   => $this->appkey,
			'mobile'    => $mobile,
			'tpl_id'    => $this->code_tpl,
			'tpl_value' =>'#nickname#='.$member['nickname'].'&#code#='.$code
		);
		$content = $this->juhecurl($sendUrl,$smsConf,1);
		if($content){
			$result = json_decode($content,true);
			$error_code = $result['error_code'];
			if($error_code == 0){
				return true;
			}else{
				$msg = $result['reason'];
				return error(-1,$msg);
			}
		}else{
			return false;
		}
		return true;
	}
	function mc_notice_init() {
		global $_W;
		if(empty($_W['account'])) {
			$_W['account'] = uni_fetch($_W['uniacid']);
		}
		if(empty($_W['account'])) {
			return error(-1, '创建公众号操作类失败');
		}
		if($_W['account']['level'] < 3) {
			return error(-1, '公众号没有经过认证，不能使用模板消息和客服消息');
		}
		$acc = WeAccount::create();
		if(is_null($acc)) {
			return error(-1, '创建公众号操作对象失败');
		}
		return $acc;
	}
	public function doWebtest2(){
	    global $_W,$_GPC;
	    $_GPC['do'] = 'test';
		$openid = 'o3_-vuIrct7sNoucfzKHJhdH160o';
		$tpl = trim($_GPC['tpl']);
		$remark = "测试";
		$title = "测试";
		$url = "http://www.baidu.com";
		$task_type = "测试";
		$credit = '1.0';
		$time = date("Y-m-d",time());
		$member = array();
		$member['openid'] = $openid;
		$return = $this->sendTplTaskNew($member,$title,$task_type,$credit,$time,$remark,$url);
		if(is_error($return)){
			message($return['message'],referer(),'error');
		}
		message("消息发送成功",referer(),'success');
	}
	public function juhecurl($url,$params=false,$ispost=0){
		$httpInfo = array();
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
		curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
		curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
		if( $ispost )
		{
			curl_setopt( $ch , CURLOPT_POST , true );
			curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
			curl_setopt( $ch , CURLOPT_URL , $url );
		}
		else
		{
			if($params){
				curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
			}else{
				curl_setopt( $ch , CURLOPT_URL , $url);
			}
		}
		$response = curl_exec( $ch );
		if ($response === FALSE) {
			//echo "cURL Error: " . curl_error($ch);
			return false;
		}
		$httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
		$httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
		curl_close( $ch );
		return $response;
	}
	public function sendSmsCode($code,$mobile,$member){
		if($this->system['type'] == 0){
			//微赞短信
			$return = $this->sendSmsSystem($code,$mobile,$member);
			if(is_error($return)){
				$this->insertlog($member,"微赞短信：".$return['message'],0,0);
			}else{
				$this->insertlog($member,"微赞短信验证码",1,0);
			}
		}
		if($this->system['type'] == 1){
			//阿里大鱼
			$return = $this->sendSmsAlidayu($code,$mobile,$member);
			$code = $return->code;
			$msg = $return->msg;
			if($code == 0){
				$this->insertlog($member,"阿里大鱼验证码",1,1);
				return $code;
			}else{
				$this->insertlog($member,"阿里大鱼验证码失败",0,1);
				return error(-1,$msg);
			}
		}
		if($this->system['type'] == 3){
			$return = $this->sendSmsAlidayuVoice($code,$mobile,$member);
			$code = $return->code;
			$msg = $return->msg;
			if($code == 0){
				$this->insertlog($member,"阿里大鱼语音验证码",1,1);
				return $code;
			}else{
				$this->insertlog($member,"阿里大鱼语音验证码失败",0,1);
				return error(-1,$msg);
			}
		}
		if($this->system['type'] == 2){
			//聚合短信
			$return = $this->sendSmsJuhe($code,$mobile,$member);
			if(is_error($return)){
				$this->insertlog($member,"聚合短信：".$return['message'],0,2);
			}else{
				$this->insertlog($member,"聚合短信验证码",1,2);
			}
		}
		return false;
	}
	public function doWebdrop(){
	    global $_W,$_GPC;
	    $_GPC['do'] = 'drop';
	    $this->M('log')->drop();
		message('操作成功',referer(),'success');
	}
	public function insertlog($member,$content,$status = 1,$type=0){
		global $_W;
		$log = array();
		$log['openid'] = $member['openid'];
		$log['uniacid'] = $_W['uniacid'];
		$log['avatar'] = tomedia($member['avatar']);
		$log['nickname'] = $member['nickname'];
		$log['content'] = $content;
		$log['status'] = $status;
		$log['create_time'] = time();
		$log['type'] = $type;
		$this->M('log')->update($log);
	}
	function mc_notice_custom_news($openid, $title, $content,$url,$thumb) {
		global $_W;
		$acc = $this->mc_notice_init();
		if(is_error($acc)) {
			return error(-1, $acc['message']);
		}
		$fans = pdo_fetch('SELECT salt,acid,openid FROM ' . tablename('mc_mapping_fans') . ' WHERE uniacid = :uniacid AND openid = :openid', array(':uniacid' => $_W['uniacid'], ':openid' => $openid));

		$row = array();
		$row['title'] = urlencode($title);
		$row['description'] = urlencode($content);
		!empty($thumb) && ($row['picurl'] = tomedia($thumb));

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
	//任务进行状态提醒
	public function sendTplTaskRealtime($member,$title,$task_type,$task_order,$time,$finish_time,$remark,$url){
		$openid = $member['openid'];
		load()->model('mc');
		$acc = $this->mc_notice_init();
		if(is_error($acc)) {
			return error(-1, $acc['message']);
		}
		$data = array(
			'first' => array(
				'value' => $title,
				'color' => '#ff510'
			),
			'keyword1' => array(
				'value' => $task_type,
				'color' => '#ff510'
			),
			'keyword2' => array(
				'value' => $task_order,
				'color' => '#ff510'
			),
			'keyword3' => array(
				'value' => $time,
				'color' => '#ff510'
			),
			'keyword3' => array(
				'value' => $finish_time,
				'color' => '#ff510'
			),
			'remark' => array(
				'value' => $remark,
				'color' => '#ff510'
			),
		);
		$status = $acc->sendTplNotice($openid, $this->tpl['tmplmsg_task_realtime'], $data, $url);
		if(!is_error($status)){
			$this->insertlog($member,"任务实时提醒",1,3);
		}else{
			$content = "";
			if(!empty($task_type)){
				$content = "任务类型：".$task_type;
			}
			if(!empty($time)){
				$content = "时间：".$time;
			}
			if(!empty($finish_time)){
				$content = "完成时间：".$finish_time;
			}
			$this->mc_notice_custom_news($openid, $title, $content,$url,'');
			$this->insertlog($member,"任务实时提醒失败",0,3);
		}
		return $status;

	}
	//最新任务发布 群发
	public function sendTplTaskNew($member,$title,$task_type,$credit,$time,$remark,$url){
		$openid = $member['openid'];
		load()->model('mc');
		$acc = $this->mc_notice_init();
		if(is_error($acc)) {
			return error(-1, $acc['message']);
		}
		$data = array(
			'first' => array(
				'value' => $title,
				'color' => '#ff510'
			),
			'keyword1' => array(
				'value' => $task_type,
				'color' => '#ff510'
			),
			'keyword2' => array(
				'value' => $credit,
				'color' => '#ff510'
			),
			'keyword3' => array(
				'value' => $time,
				'color' => '#ff510'
			),
			'remark' => array(
				'value' => $remark,
				'color' => '#ff510'
			),
		);
		$status = $acc->sendTplNotice($openid, $this->tpl['tmplmsg_task_new'], $data, $url);
		if(!is_error($status)){
			$this->insertlog($member,"最新任务提醒",1,4);
		}else{
			$this->insertlog($member,"最新任务提醒：".$status['message'],0,4);
		}
		return $status;
	}
	public function sendTplCreditToCash($member,$title,$credit,$time,$remark,$url){
		$openid = $member['openid'];
		load()->model('mc');
		$acc = $this->mc_notice_init();
		if(is_error($acc)) {
			return error(-1, $acc['message']);
		}
		$data = array(
			'first' => array(
				'value' => $title,
				'color' => '#ff510'
			),
			'keyword1' => array(
				'value' => $member['nickname'],
				'color' => '#ff510'
			),
			'keyword2' => array(
				'value' => $credit,
				'color' => '#ff510'
			),
			'keyword2' => array(
				'value' => $time,
				'color' => '#ff510'
			),
			'remark' => array(
				'value' => $remark,
				'color' => '#ff510'
			),
		);
		$status = $acc->sendTplNotice($openid, $this->tpl['tmplmsg_credit_to_cash'], $data, $url);
		if(!is_error($status)){
			$this->insertlog($member,"打款成功提醒",1,5);
		}else{
			$this->insertlog($member,"打款失败：".$status['message'],0,5);
		}
		return $status;
	}
	//任务完成通知
	public function sendTplTaskFinish($member,$title,$task,$time,$remark,$url){
		$openid = $member['openid'];
		load()->model('mc');
		$acc = $this->mc_notice_init();
		if(is_error($acc)) {
			return error(-1, $acc['message']);
		}
		$data = array(
			'first' => array(
				'value' => $title,
				'color' => '#ff510'
			),
			'keyword1' => array(
				'value' => $task,
				'color' => '#ff510'
			),
			'keyword2' => array(
				'value' => $time,
				'color' => '#ff510'
			),
			'remark' => array(
				'value' => $remark,
				'color' => '#ff510'
			),
		);
		$status = $acc->sendTplNotice($openid, $this->tpl['tmplmsg_task_finish'], $data, $url);
		if(!is_error($status)){
			$this->insertlog($member,"完成任务提醒",1,6);
		}else{
			$this->insertlog($member,"完成任务提醒：".$status['message'],0,6);
		}
		return $status;
	}

	public function doWebsetting(){
	    global $_W,$_GPC;
	    $_GPC['do'] = 'setting';
	    $code = $_GPC['code'];
	    if(empty($code)){
	        $code = 'system';
	    }
	    if(empty($code)){
	        message('参数错误',referer(),'error');
	    }
	    if($_W['ispost']){
	        $data = array();
	        $data['codename'] = $code;
	        $data['value'] = serialize($_POST);
	        $this->M('setting')->update($data);
	        message('保存成功',referer(),'success');
	    }
	    $item = $this->M('setting')->getSetting($code);

		if(empty($item)){
			$item = array();
			$item['appkey'] = '';
			$item['appid'] = '';
		}
	    include $this->template('setting_'.$code);
	}
	public function doWeblog(){
	    global $_W,$_GPC;
	    $_GPC['do'] = 'log';
	    if ($_GPC['act'] == 'edit') {
	        $id = intval($_GPC['id']);
	        if($_W['ispost']){
	            $data = array();
	            $data['uniacid'] = $_W['uniacid'];
	            $data['create_time'] = time();
	            if(!empty($id)){
	                $data['id'] = $id;
	                unset($data['create_time']);
	            }
				$this->M('log')->update($data);
	            message('保存成功',$this->createWebUrl('log'),'success');
	        }
	        $item = M('log')->getInfo($id);
	        include $this->template('log_edit');
	        exit();
	    }
	    if ($_GPC['act'] == 'delete') {
	        $id = intval($_GPC['id']);
	        if(empty($id)){
	            if($_W['ispost']){
	                $data = array();
	                $data['status'] = 1;
	                $data['message'] = '参数错误';
	                die(json_encode($data));
	            }else{
	                message('参数错误',referer(),'error');
	            }
	        }
			$this->M('log')->delete($id);
	        if($_W['ispost']){
	            $data = array();
	            $data['status'] = 1;
	            $data['message'] = '操作成功';
	            die(json_encode($data));
	        }else{
	            message('删除成功',referer(),'success');
	        }
	    }
	    $page = !empty($_GPC['page'])?intval($_GPC['page']):1;
	    $where = "";
		$list = $this->M('log')->getList($page,$where);
	    include $this->template('log');
	}
	public function doWebhelp(){
	    global $_W,$_GPC;
	    $_GPC['do'] = 'help';
	    if ($_GPC['act'] == 'edit') {
	        $id = intval($_GPC['id']);
	        if($_W['ispost']){
	            $data = array();
	            $data['uniacid'] = $_W['uniacid'];
	            $data['create_time'] = time();
	            if(!empty($id)){
	                $data['id'] = $id;
	                unset($data['create_time']);
	            }
				$this->M('help')->update($data);
	            message('保存成功',$this->createWebUrl('help'),'success');
	        }
	        $item = M('help')->getInfo($id);
	        include $this->template('help_edit');
	        exit();
	    }
	    if ($_GPC['act'] == 'delete') {
	        $id = intval($_GPC['id']);
	        if(empty($id)){
	            if($_W['ispost']){
	                $data = array();
	                $data['status'] = 1;
	                $data['message'] = '参数错误';
	                die(json_encode($data));
	            }else{
	                message('参数错误',referer(),'error');
	            }
	        }
			$this->M('help')->delete($id);
	        if($_W['ispost']){
	            $data = array();
	            $data['status'] = 1;
	            $data['message'] = '操作成功';
	            die(json_encode($data));
	        }else{
	            message('删除成功',referer(),'success');
	        }
	    }
	    $page = !empty($_GPC['page'])?intval($_GPC['page']):1;
	    $where = "";
		$list = $this->M('help')->getList($page,$where);
	    include $this->template('help');
	}
}