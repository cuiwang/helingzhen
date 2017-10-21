<?php
defined('IN_IA') or exit('Access Denied');
require IA_ROOT. '/addons/weliam_indiana/defines.php';
require WELIAM_INDIANA_INC.'function.php'; 
class weliam_indianaModuleSite extends WeModuleSite {
	public function __call($name, $arguments) {
		$isWeb = stripos($name, 'doWeb') === 0;
		$isMobile = stripos($name, 'doMobile') === 0;
		if($isWeb || $isMobile) {
			$dir = IA_ROOT . '/addons/' . $this->modulename . '/inc/';
			if($isWeb) {
				$dirr = 'index.web.php';
			}
			if($isMobile) {
				$dirr = 'index.app.php';
			}
			$file = $dir.$dirr;
			if(file_exists($file)) {
				require $file;
				exit;
			}
		}
		trigger_error("访问的方法 {$name} 不存在.", E_USER_WARNING);
		return null;
	}
	
	
	public function __construct(){
		global $_W;
		//异步请求失败后，处理中奖结果
		$status2 = pdo_fetchall("select status,endtime,id,openid,goodsid,period_number from".tablename('weliam_indiana_period')."where uniacid={$_W['uniacid']} and status=2 ");
		if($status2){
			foreach($status2 as$k=>$v){
				$t = $v['endtime'] - time();
				if($t<=0){
					pdo_update("weliam_indiana_period",array('status'=>3),array('id'=>$v['id']));
					$goods = pdo_fetch("select title,automatic,is_alert from".tablename("weliam_indiana_goodslist")."where id='{$v['goodsid']}' and uniacid='{$_W['uniacid']}'");
					$datam = array(
						"first"=>array( "value"=> "恭喜你！你参与的一元夺宝已中奖！","color"=>"#173177"),
						"keyword1"=>array('value' => "一元夺宝", "color" => "#4a5077"),
						"keyword2"=>array('value' => $goods['title'], "color" => "#4a5077"),
						"remark"=>array("value"=>'点击查看详情', "color" => "#4a5077"),
					);
					$url2 = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=order_get&m=weliam_indiana";
					$sql = 'SELECT `settings` FROM ' . tablename('uni_account_modules') . ' WHERE `uniacid` = :uniacid AND `module` = :module';
					$settings = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid'], ':module' => 'weliam_indiana'));
					$settings = iunserializer($settings);
					$template_id = $settings['m_suc'];
					$account= WeAccount :: create($_W['acid']);
					$account -> sendTplNotice($v['openid'], $template_id, $datam, $url2);
					
					if($goods['is_alert'] == 2){
						//该中奖商品开启了未中奖用户提醒
						$url_file_b = $_SERVER["REQUEST_URI"];
						$url_o = explode($app_web, $url_file_b);
						$url = 'http://'.$_SERVER['SERVER_NAME'].$url_o[0].'/addons'.'/weliam_indiana/core/api/is_alert.api.php';			//路径组合
						$http = ihttp_request($url, array('uniacid' => $_W['uniacid'],'period_number'=>$v['period_number']),array('Content-Type' => 'application/x-www-form-urlencoded'),1);
						m('log')->WL_log('automatic',$url.'参数回传情况'.$goods['title'].'】自动发货失败',$http,$uniacid);
					}
					
					$automatic = unserialize($goods['automatic']);
					if($automatic['select'] == 2 || $automatic['select'] == 3){//自动开奖
						$url_file_b = $_SERVER["REQUEST_URI"];
						$url_o = explode($app_web, $url_file_b);
						$url = 'http://'.$_SERVER['SERVER_NAME'].$url_o[0].'/addons'.'/weliam_indiana/core/api/automatic.api.php';			//路径组合
						$http = ihttp_request($url, array('uniacid' => $_W['uniacid'],'period_number'=>$v['period_number']),array('Content-Type' => 'application/x-www-form-urlencoded'),1);
						m('log')->WL_log('automatic',$url.'参数回传情况'.$goods['title'].'】自动发货失败',$http,$uniacid);
					}
				}
			}
		}
	}

	protected function pay($params = array(), $mine = array()) {
		global $_W;
		//购买的商品
		$openid = m('user') -> getOpenid();
		$record = pdo_fetch("select openid,uniacid,type from".tablename('weliam_indiana_rechargerecord')."where ordersn='{$params['tid']}'");
		$openid = m('user') -> getOpenid();
		isetcookie('uniacid',$_W['uniacid'],600);
		if($record['type']==1){
			//充值
			$money = $params['fee'];
		}else{
			//删除支付数量为0的购物车记录
			pdo_delete("weliam_indiana_cart",array('uniacid'=>$_W['uniacid'],'num'=>0));
			//支付
			$thisCart = pdo_fetchall("select * from".tablename('weliam_indiana_cart')."where openid='{$record['openid']}' and uniacid={$record['uniacid']}");				
			$money=0;
			$num = 0;
			foreach($thisCart as $key=>$value){
				$goodslist = m('goods')->getListByPeriod_number($value['period_number']);
				$money +=$value['num']*$goodslist['init_money'];
				$thisCart[$key]['num']=$value['num'];
				$num++;
			}
			//账户余额夺宝币
			$thismember = m('member') -> getInfoByOpenid($record['openid']);
		}
		if(!$this->inMobile) {
			message('支付功能只能在手机上使用');
		}
		$share_data = $this -> module['config'];
		$_W['page']['footer'] = $share_data['copyright'];
		$title = '支付方式';
		if($share_data['paytype'] == 2){
			include $this->template('pay/paycenter');
		}else{
			$params['module'] = $this->module['name'];
			$pars = array();
			$pars[':uniacid'] = $_W['uniacid'];
			$pars[':module'] = $params['module'];
			$pars[':tid'] = $params['tid'];
			if($params['fee'] <= 0) {
				$pars['from'] = 'return';
				$pars['result'] = 'success';
				$pars['type'] = 'alipay';
				$pars['tid'] = $params['tid'];
				$site = WeUtility::createModuleSite($pars[':module']);
				$method = 'payResult';
				if (method_exists($site, $method)) {
					exit($site->$method($pars));
				}
			}
	
			$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
			$log = pdo_fetch($sql, $pars);
			if (empty($log)) {
				$log = array(
					'uniacid' => $_W['uniacid'],
					'acid' => $_W['acid'],
					'openid' => $_W['member']['uid'],
					'module' => $this->module['name'],
					'tid' => $params['tid'],
					'fee' => $params['fee'],
					'card_fee' => $params['fee'],
					'status' => '0',
					'is_usecard' => '0',
				);
				pdo_insert('core_paylog', $log);
			}
			if(!empty($log) && $log['status'] == '1') {
				message('这个订单已经支付成功, 不需要重复支付.');
			}
			$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
			if(!is_array($setting['payment'])) {
				message('没有有效的支付方式, 请联系网站管理员.');
			}
			$pay = $setting['payment'];
			/*print_r($pay);exit;*/
			$pay['credit']['switch'] = false;
			$pay['delivery']['switch'] = false;
			if (!empty($pay['credit']['switch'])) {
				$credtis = mc_credit_fetch($_W['member']['uid']);
			}
			$iscard = pdo_fetchcolumn('SELECT iscard FROM ' . tablename('modules') . ' WHERE name = :name', array(':name' => $params['module']));
			$you = 0;
			
	

			include $this->template('pay/wpaycenter');
		}
	}
	//单独购买支付
	protected function pay2($params = array(), $mine = array()) {
		global $_W;
		load()->model('activity');
		activity_coupon_type_init();
		if(!$this->inMobile) {
			message('支付功能只能在手机上使用');
		}
		$params['module'] = $this->module['name'];
		if($params['fee'] <= 0) {
			$pars = array();
			$pars['from'] = 'return';
			$pars['result'] = 'success';
			$pars['type'] = '';
			$pars['tid'] = $params['tid'];
			$site = WeUtility::createModuleSite($pars[':module']);
			$method = 'payResult';
			if (method_exists($site, $method)) {
				exit($site->$method($pars));
			}
		}
		$log = pdo_get('core_paylog', array('uniacid' => $_W['uniacid'], 'module' => $params['module'], 'tid' => $params['tid']));
		if (empty($log)) {
			$log = array(
				'uniacid' => $_W['uniacid'],
				'acid' => $_W['acid'],
				'openid' => $_W['member']['uid'],
				'module' => $this->module['name'],
				'tid' => $params['tid'],
				'fee' => $params['fee'],
				'card_fee' => $params['fee'],
				'status' => '0',
				'is_usecard' => '0',
			);
			pdo_insert('core_paylog', $log);
		}
		if($log['status'] == '1') {
			message('这个订单已经支付成功, 不需要重复支付.');
		}
		$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
		if(!is_array($setting['payment'])) {
			message('没有有效的支付方式, 请联系网站管理员.');
		}
		$pay = $setting['payment'];
		$cards = activity_paycenter_coupon_available();
		if (!empty($cards)) {
			foreach ($cards as $key => &$val) {
				if ($val['type'] == '1') {
					$val['discount_cn'] = sprintf("%.2f", $params['fee'] * (1 - $val['extra']['discount'] * 0.01));
					$coupon[$key] = $val;
				} else {
					$val['discount_cn'] = sprintf("%.2f", $val['extra']['reduce_cost'] * 0.01);
					$token[$key] = $val;
					if ($log['fee'] < $val['extra']['least_cost'] * 0.01) {
						unset($token[$key]);
					}
				}
				unset($val['icon']);
			}
		}
		$cards_str = json_encode($cards);
		if (empty($_W['member']['uid'])) {
			$pay['credit']['switch'] = false;
		}
		if ($params['module'] == 'paycenter') {
			$pay['delivery']['switch'] = false;
			$pay['line']['switch'] = false;
		}
		if (!empty($pay['credit']['switch'])) {
			$credtis = mc_credit_fetch($_W['member']['uid']);
		}
		$you = 0;
		include $this->template('common/paycenter');
	}
	//付款结果返回
	public function payResult($params){
		global $_W, $_GPC;
		$uniacid=$_W['uniacid'];
		$fee = $params['fee'];
		$buy_codes = $fee;
		$openid = $params['user'];
		$flag = substr($params['tid'],0,3);
		if($flag == 'ykj'){
			if($params['type'] == 'wechat'){
				$paytype = 1;
			}elseif ($params['type'] == 'credit') {
				$paytype = 2;
			}
			$data = array(
				'paytime'  => TIMESTAMP,
				'paytype'  => $paytype,
				'status'   => 1
			);
			pdo_update('weliam_indiana_aloneorder',$data,array('orderno' => $params['tid']));
		}
		$data = array('status' => $params['result'] == 'success' ? 1 : 0);
		$paytype = array('credit' => '1', 'wechat' => '3', 'alipay' => '2');
		$data['paytype'] = $paytype[$params['type']];
		if ($params['type'] == 'wechat' || $params['type'] == 'wechat_cash') {
			$data['transid'] = $params['tag']['transaction_id'];
		}
		if ($params['type'] == 'yunpay') {
			$data['transid'] = $params['tag']['transaction_id'];//二次开发
		}
		$record = pdo_fetch("SELECT * FROM " . tablename('weliam_indiana_rechargerecord') . " WHERE ordersn ='{$params['tid']}'");//获取商品ID
		if(empty($openid) || !empty($openid) ){		//判定传值是否正确，判定是否是传递uid
			$openid = $record['openid'];
		}
		if ($params['result'] == 'success' && $params['from'] == 'notify') {
			//微信支付
			if (empty($record['status'])) {
				$data['status'] = 1;
				$pays = m('credit')->checkpay($params['tid']);
				$data['num'] = $pays['fee'];
				pdo_update('weliam_indiana_rechargerecord', $data, array('ordersn' => $params['tid']));
				m('credit')->updateCredit2($record['openid'],$_W['uniacid'],$pays['fee'],'支付充值余额');
				if($record['type']==1){
					$result_mess = '支付成功！';
				}elseif($record['num'] != $data['num']){
					m('log')->WL_log('pay','支付异常，支付金额返回余额',$params);
					exit;
				}else{
					/****************检索购买夺宝码开始*****************/
					$numsql = "select * from".tablename('weliam_indiana_cart')."where uniacid = ".$_W['uniacid']." and openid = '".$openid."'";
					$num_money = pdo_fetchall($numsql);
					$money = 0;
					foreach($num_money as $key =>$value){
						$goodsid = pdo_fetchcolumn("select goodsid from".tablename('weliam_indiana_period')."where period_number = '{$value['period_number']}'");
						$init_money = pdo_fetchcolumn("select init_money from".tablename('weliam_indiana_goodslist')."where id = '{$goodsid}'");
						$money = $money+$init_money*$value['num'];
					}
					if($record['num'] < 1 || $record['num'] != $money || $record['num'] == '' || $record['type'] == 1){
						m('log')->WL_log('pay','非法操作，计算数量不相同',$params);
						exit;
					}
					/****************检索购买夺宝码结束****************/
					if(m('codes')->code($record['openid'],$record['ordersn'],$record['uniacid'],'')){
						/****************自己购买返回积分开始*****************/
						$credit_num = $this->module['config']['buy_followed'];
						if($credit_num > 0){
							$sql = "select * from".tablename('weliam_indiana_invite')."where uniacid=:uniacid and invite_openid=:invite_openid and type=:type";
							$data = array(
								':uniacid'=>$_W['uniacid'],
								':invite_openid'=>$record['openid'],
								':type'=>2
							);
							$result = pdo_fetch($sql,$data);
							if(empty($result)){
								$numi = $credit_num*$buy_codes;
								$datam = array(
									'uniacid'=>$_W['uniacid'],
									'beinvited_openid'=>'yourself',
									'invite_openid'=>$record['openid'],
									'createtime'=>time(),
									'credit1'=>$numi,
									'type'=>2
								);
								$ins = pdo_insert("weliam_indiana_invite", $datam);
							}else{
								$numu = $result['credit1']+$credit_num*$buy_codes;
								$upd = pdo_update("weliam_indiana_invite",array('credit1'=>$numu),array('uniacid'=>$_W['uniacid'],'type'=>2,'invite_openid'=>$record['openid']));
							}
							m('credit')->updateCredit1($record['openid'],$_W['uniacid'],$credit_num*$buy_codes,'被邀请人积分增加');
						}
						/****************自己购买返回积分结束*****************/
						$level=$this->module['config']['level'];
						if($level==1){
							$level1=$this->module['config']['level1'];
							$invites=m('invite')->getInvitesByOpenid($openid,$_W['uniacid']);
							foreach($invites as$key=>$value){
								m('credit')->updateCredit1($value['invite_openid'],$_W['uniacid'],$level1*$buy_codes,'自己购买积分返回');
								m('invite')->updateBy2Openid($openid,$value['invite_openid'],$_W['uniacid'],$level1*$buy_codes,'邀请关注返夺宝币');
							}
						}
					}
				}
			}
		}
		m('log')->WL_log('pay','支付参数',$params);
		if ($params['from'] == 'return' && $params['result'] == 'success') {
			//微信云支付通知
			$siterooturl = $_W['siteroot']."app/";
			if(strpos($siterooturl,'addons')!==false||strpos($siterooturl,'yunpay')!==false)$siterooturl = $_W['siteroot']."../../../app/";//二次开发
			$url2 = $siterooturl.$this -> createMobileUrl('order');
			$tpl_id_short = $this->module['config']['m_pay'];
			$data  = array(
				"name"=>array( "value"=> "支付成功！预祝中大奖！","color"=>"#173177"),
				"remark"=>array('value' => "\r点击查看详情！", "color" => "#4a5077"),
			);
			m('common')->sendTplNotice($record['openid'],$tpl_id_short,$data,$url2,'');
			
			if($record['type']==1){
				$siterooturl = $_W['siteroot']."app/";
				if(strpos($siterooturl,'addons')!==false||strpos($siterooturl,'yunpay')!==false)$siterooturl = $_W['siteroot']."../../../app/";//二次开发
				header("location:".$siterooturl.str_replace('./','',$this->createMobileUrl('person')));
			}else{
				$siterooturl = $_W['siteroot']."app/";
				if(strpos($siterooturl,'addons')!==false||strpos($siterooturl,'yunpay')!==false)$siterooturl = $_W['siteroot']."../../../app/";//二次开发
				header("location:".$siterooturl.str_replace('./','',$this->createMobileUrl('endbuy',array('flag'=>$flag))));
			}
		}
	}
	
	//ping++支付结果
	public function othrtpayResult($params){
		global $_W, $_GPC;
		$uniacid=$_W['uniacid'];
		$fee = $params['fee']/100;
		$buy_codes = $fee;
		$paytype = array('credit' => '1', 'wx_pub' => '3', 'alipay_wap' => '2','jdpay_wap' => '4' , 'bfb_wap' => '5');
		$data['paytype'] = $paytype[$params['type']];

		$record = pdo_fetch("SELECT * FROM " . tablename('weliam_indiana_rechargerecord') . " WHERE ordersn ='{$params['tid']}'");//获取商品ID
		$openid = $record['openid'];
		if (empty($record['status'])) {
			$data['status'] = 1;
			pdo_update('weliam_indiana_rechargerecord', $data, array('ordersn' => $params['tid']));
			m('credit')->updateCredit2($record['openid'],$_W['uniacid'],$fee);
			if($record['type']==1){
				$result_mess = '支付成功！';
			}else{
				if(m('codes')->code($record['openid'],$params['tid'],$record['uniacid'],'')){
					$level=$this->module['config']['level'];
					if($level==1){
						$level1=$this->module['config']['level1'];
						$invites=m('invite')->getInvitesByOpenid($openid,$_W['uniacid']);
						foreach($invites as$key=>$value){
							m('credit')->updateCredit1($value['invite_openid'],$_W['uniacid'],$level1*$buy_codes);
							m('invite')->updateBy2Openid($openid,$value['invite_openid'],$_W['uniacid'],$level1*$buy_codes);
						}
					}
				}
			}
			
		}
	}

/*＝＝＝＝＝＝＝＝＝＝＝＝＝＝以下为后台管理＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/
//商品管理
	private function getGoodsStatus($status){
		$status = intval($status);
		if ($status == 1) {
			return '下架';
		} elseif ($status == 2) {
			return '上架';
		} else {
			return '未知';
		}
	}
/*＝＝＝＝＝＝＝＝＝＝＝＝＝＝设置商品上下架函数＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/	
	public function doWebSetGoodsProperty() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$type = $_GPC['type'];
		$data = intval($_GPC['data']);
		if (in_array($type, array('new', 'hot', 'recommand', 'discount'))) {
			$data = ($data==1?'0':'1');
			pdo_update("weliam_indiana_goodslist", array("is" . $type => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		if (in_array($type, array('status'))) {
			$data = ($data==2?'1':'2');
			if($data==1){				
				pdo_update("weliam_indiana_period",array('status'=> 0),array('goodsid'=>$id,'uniacid'=>$_W['uniacid'],'status'=>1));
				pdo_update("weliam_indiana_goodslist", array($type => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
			}else{
				//判定是否是重新上架
				$max_periods = pdo_fetchcolumn("select max(periods) from".tablename('weliam_indiana_period')."where uniacid = '{$_W['uniacid']}' and goodsid = '{$id}'");//检测当前商品最大期数
				$periods_result = pdo_fetch("select shengyu_codes,status,goodsid from".tablename('weliam_indiana_period')."where uniacid = '{$_W['uniacid']}' and goodsid = '{$id}' and periods = '{$max_periods}'");
				if($periods_result['status'] == 0 || $periods_result['status'] == 1){//提出判断
					pdo_update("weliam_indiana_period",array('status'=> 1),array('goodsid'=>$id,'uniacid'=>$_W['uniacid'],'status'=>0));
					pdo_update("weliam_indiana_goodslist", array($type => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
				}else{
					m('codes')->create_newgoods($periods_result['goodsid']);
				}
				
			}
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		if (in_array($type, array('type'))) {
			$data = ($data==1?'2':'1');
			pdo_update("weliam_indiana_goodslist", array($type => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		die(json_encode(array("result" => 0)));
	}
	
/*＝＝＝＝＝＝＝＝＝＝＝＝＝＝以下为其他函数＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/
  	//微信图片下载两个方法downloadWeiXinFile(),saveWeiXinFile()
  	public function downloadWeiXinFile($url){
  		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_NOBODY, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$package = curl_exec($ch);
		$httpinfo = curl_getinfo($ch);
		curl_close($ch);
		return "$package";
		
  	}
	
	public function saveWeiXinFile($filename,$filecontent){
  		$local_file = fopen($filename, 'w');
		if(false !== $local_file){
			if(FALSE !== fwrite($local_file, $filecontent)){
				fclose($local_file);
				return "ture";
			}
		}
  	}
	
	public function template($filename, $type = TEMPLATE_INCLUDEPATH) {
		global $_W;
		$name = strtolower($this -> modulename);
		if (defined('IN_SYS')) {
			$source = IA_ROOT . "/web/themes/{$_W['template']}/{$name}/{$filename}.html";
			$compile = IA_ROOT . "/data/tpl/web/{$_W['template']}/{$name}/{$filename}.tpl.php";
			if (!is_file($source)) {
				$source = IA_ROOT . "/web/themes/default/{$name}/{$filename}.html";
			} 
			if (!is_file($source)) {
				$source = IA_ROOT . "/addons/{$name}/template/web/{$filename}.html";
			} 
			if (!is_file($source)) {
				$source = IA_ROOT . "/web/themes/{$_W['template']}/{$filename}.html";
			} 
			if (!is_file($source)) {
				$source = IA_ROOT . "/web/themes/default/{$filename}.html";
			} 
		} else {
			$template = $this->module['config']['style'];
			$file = IA_ROOT . "/addons/{$name}/data/template/shop_" . $_W['uniacid'];
			if (is_file($file)) {
				$template = file_get_contents($file);
				if (!is_dir(IA_ROOT . '/addons/{$name}/template/mobile/' . $template)) {
					$template = "default";
				} 
			} 
			$compile = IA_ROOT . "/data/tpl/app/{$name}/{$template}/mobile/{$filename}.tpl.php";
			$source = IA_ROOT . "/addons/{$name}/template/mobile/{$template}/{$filename}.html";
			if (!is_file($source)) {
				$source = IA_ROOT . "/addons/{$name}/template/mobile/default/{$filename}.html";
			}
			if (!is_file($source)) {
				$source = IA_ROOT . "/app/themes/{$_W['template']}/{$filename}.html";
			} 
			if (!is_file($source)) {
				$source = IA_ROOT . "/app/themes/default/{$filename}.html";
			} 
		} 
		if (!is_file($source)) {
			exit("Error: template source '{$filename}' is not exist!");
		} 
		if (DEVELOPMENT || !is_file($compile) || filemtime($source) > filemtime($compile)) {
			template_compile($source, $compile, true);
		} 
		return $compile;
	} 
/*＝＝＝＝＝＝＝＝＝＝＝＝＝＝以下为打印记录函数＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/
	public function WL_log($filename,$param,$filedata){
		$url_log = WELIAM_INDIANA."log/".date('Y-m-d',time())."/".$filename.".log";
		$url_dir = WELIAM_INDIANA."log/".date('Y-m-d',time());
		$this->WL_mkdirs($url_dir);			//检测目录是否存在
		file_put_contents($url_log, var_export('/========================================='.date('Y-m-d H:i:s',time()).'============================================/', true).PHP_EOL, FILE_APPEND);
		file_put_contents($url_log, var_export('******记录'.$param.'*****', true).PHP_EOL, FILE_APPEND);
		file_put_contents($url_log, var_export($filedata, true).PHP_EOL, FILE_APPEND);
	} 
/*＝＝＝＝＝＝＝＝＝＝＝＝＝＝以下判断文件夹是否存在不存在则创建函数＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/
	public function WL_mkdirs($dir){
		if (file_exists($dir)) {   
		 	return 'true';
		} else {
			mkdir($dir);
			return 'false';
		}
	}
/*＝＝＝＝＝＝＝＝＝＝＝＝＝＝以下用于获取机器人统计数＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/
	public function getMachine_nicknamenum(){
		//获取机器人名称数量
		global $_W;
		
		$sql_in_nickname = "select count(id) from".tablename('weliam_indiana_in')."where uniacid=:uniacid and type=:type";
		$data_in_nickname = array(
			':uniacid'=>$_W['uniacid'],
			':type'=>1
		);
		$result_in_nickname = pdo_fetchcolumn($sql_in_nickname,$data_in_nickname);		//名称个数
		return $result_in_nickname;
	}
	
	public function getMachine_IPnum(){
		//获取机器人ip段数数量
		global $_W;
		
		$sql_in_IP = "select count(id) from".tablename('weliam_indiana_in')."where uniacid=:uniacid and type=:type";
		$data_in_IP = array(
			':uniacid'=>$_W['uniacid'],
			':type'=>2
		);
		$result_in_IP = pdo_fetchcolumn($sql_in_IP,$data_in_IP);		//ip段个数
		return $result_in_IP;
	}
	
	public function getMachine_machinenum(){
		//获取已经创建机器人个数
		global $_W;
		
		$sql_machine = "select count(mid) from".tablename('weliam_indiana_member')."where uniacid=:uniacid and openid LIKE '%"."machine"."%' ";
		$data_machine = array(
			':uniacid'=>$_W['uniacid']
		);
		$result_machine = pdo_fetchcolumn($sql_machine,$data_machine);
		return $result_machine;
	}
	
/*＝＝＝＝＝＝＝＝＝＝＝＝＝＝以下用于判断机器人进程是否开启＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/
	public function checkMachineStatus(){
		//判断机器人进程(false表示返回未开启。ture表示返回开启)
		global $_W;
		
		$sql_machine_machine = "select id,uniacid,period_number,status from".tablename('weliam_indiana_machineset')."where uniacid=:uniacid and period_number LIKE '%openmachine%'";
		$data_machine_machine = array(
			':uniacid'=>$_W['uniacid']
		);
		$result_machine_machine = pdo_fetch($sql_machine_machine,$data_machine_machine);
		
		$sql_tenlent_machine = "select id,uniacid,period_number,status,goodsid from".tablename('weliam_indiana_machineset')."where uniacid=:uniacid and period_number LIKE '%tenlent%'";
		$data_tenlent_machine = array(
			':uniacid'=>$_W['uniacid']
		);
		$result_tenlent_machine = pdo_fetch($sql_tenlent_machine,$data_tenlent_machine);
		if($result_machine_machine['status'] == 1){
			//本地机器人进程开启
			$ret = 'local_open';
		}elseif($result_tenlent_machine['status'] == 1 and $result_tenlent_machine['goodsid'] == -2){
			//远程开启，通过运行中
			$ret = 'tenlent_open';
		}elseif($result_tenlent_machine['status'] == '0' and $result_tenlent_machine['goodsid'] == -2){
			//远程开启，等待审核
			$ret = 'tenlent_wait';
		}else{
			//都未开启
			$ret = 'no';
		}
		return $ret;
	}
	
	/*＝＝＝＝＝＝＝＝＝＝＝＝＝＝分享图片判断＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/
	function tomedia_s($src, $local_path = false){
		global $_W;
		if (empty($src)) {
			return '';
		}
		if (strpos($src, './addons') === 0) {
			return $_W['siteroot'] . str_replace('./', '', $src);
		}
		if (strexists($src, $_W['siteroot']) && !strexists($src, '/addons/')) {
			$urls = parse_url($src);
			$src = $t = substr($urls['path'], strpos($urls['path'], 'images'));
		}
		if(strpos($src, '/attachment') === 0){
			return tomedia($_W['siteroot'].str_replace('/attachment/', '', $src));
		}
		$t = strtolower($src);
		if (strexists($t, 'http://') || strexists($t, 'https://')) {
			return $src;
		}
		if ($local_path || empty($_W['setting']['remote']['type']) || file_exists(IA_ROOT . '/' . $_W['config']['upload']['attachdir'] . '/' . $src)) {
			$src = $_W['siteroot'] . $_W['config']['upload']['attachdir'] . '/' . $src;
		} else {
			$src = $_W['attachurl_remote'] . $src;
		}
		return $src;
	}

}

