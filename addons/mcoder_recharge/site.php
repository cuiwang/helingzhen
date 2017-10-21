<?php
/**
 * 充值卡模块微站定义
 *
 * @author mcoder
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Mcoder_rechargeModuleSite extends WeModuleSite {

	//充值卡列表
	public function doWeblist(){
		global $_W, $_GPC;	
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$status = $_GPC['status'];

			$condition = " o.weid = :weid AND o.isdel = 0";
			$paras = array(':weid' => $_W['uniacid']);

			if (empty($starttime) || empty($endtime)) {
				$starttime = strtotime('-1 month');
				$endtime = TIMESTAMP;
			}
			if (!empty($_GPC['time'])) {
				$starttime = strtotime($_GPC['time']['start']);
				$endtime = strtotime($_GPC['time']['end']) + 86399;
				$condition .= " AND o.addtime >= :starttime AND o.addtime <= :endtime ";
				$paras[':starttime'] = $starttime;
				$paras[':endtime'] = $endtime;
			}

			if (!empty($_GPC['cardno'])) {
				$condition .= " AND (o.cardno  LIKE  '{$_GPC['cardno']}') ";
			}

			if (!empty($_GPC['cardse'])) {
				$condition .= " AND (o.cardse  LIKE  '{$_GPC['cardse']}') ";
			}
			if (!empty($_GPC['cardamount'])) {
				$condition .= " AND (o.cardamount = '{$_GPC['cardamount']}') ";
			}
			if (!empty($_GPC['cardintegration'])) {
				$condition .= " AND (o.cardintegration = '{$_GPC['cardintegration']}') ";
			}
			if (!empty($_GPC['issecretcard'])) {
				$condition .= " AND (o.issecretcard = '{$_GPC['issecretcard']}') ";
			}
			if ($status != '') {
				$condition .= " AND o.status = '" . intval($status) . "'";
			}


			$sql = 'SELECT COUNT(*) FROM ' . tablename('mcoder_recharge_card') . ' AS `o`  WHERE ' . $condition;
			$total = pdo_fetchcolumn($sql, $paras);



			if ($total > 0) {

				if ($_GPC['export'] != 'export') {
					$limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				} else {
					$limit = '';
				}

				$sql = 'SELECT  `o`.* FROM ' . tablename('mcoder_recharge_card') . ' AS `o`  WHERE ' .
						$condition . ' ORDER BY `o`.`tid` DESC ' . $limit;

				$list = pdo_fetchall($sql,$paras);

				$pager = pagination($total, $pindex, $psize);

				$orderstatus = array (
					'1' => array('css' => 'success', 'name' => '已使用'),
					'0' => array('css' => 'warning', 'name' => '未使用'),
				);
				$type = array (
					'1' => array('css' => 'success', 'name' => '卡密卡'),
					'0' => array('css' => 'success', 'name' => '普通卡'),
				);
				//var_dump($list);
				load()->model('mc');
				
				foreach ($list as &$value) {
					$s = $value['status'];
					//var_dump($value);
					$value['statuscss'] = $orderstatus[$value['status']]['css'];
					$value['status'] = $orderstatus[$value['status']]['name'];
					$value['typecss'] = $type[$value['issecretcard']]['css'];
					$value['type'] = $type[$value['issecretcard']]['name'];
					$value['uid'] = mc_openid2uid($value['openid']);
					if(!empty($value['cardamount']) && !empty($value['cardintegration'])){
						$value['jump'] = 1;
					}elseif(!empty($value['cardamount'])){
						$value['jump'] = 2;
					}else{
						$value['jump'] = 3;
					}					
				}

				//var_dump($list);
				if ($_GPC['export'] != '') {
					/* 输入到CSV文件 */
					$html = "\xEF\xBB\xBF";

					/* 输出表头 */
					$filter = array(
						'tid' => 'ID',						
						'cardno' => '卡号',
						'cardse' => '卡密',
						'cardamount' => '卡金额',
						'cardintegration' => '卡积分',
						'addtime' => '生成时间',
						'type' => '卡类型',						
						'status' => '状态',
						'openid' => '使用者',
						'usedtime' => '使用时间',
						'qrcode_info' => '二维码信息',
					);

					foreach ($filter as $key => $title) {
						$html .= $title . "\t,";
					}
					$html .= "\n";

					foreach ($list as $k => $v) {
						foreach ($filter as $key => $title) {
							if ($key == 'addtime' || ($key == 'usedtime' && $v[$key] != '')) {
								$html .= date('Y-m-d H:i:s', $v[$key]) . "\t, ";
							}else{
								$html .= $v[$key] . "\t, ";
							}
						}
						$html .= "\n";
					}
					/* 输出CSV文件 */
					header("Content-type:text/csv");
					header("Content-Disposition:attachment; filename=充值卡数据.csv");
					echo $html;
					exit();

				}
			}

		}elseif($operation == 'delete'){

			$tid = intval($_GPC['tid']);
			pdo_update('mcoder_recharge_card', array('isdel' => '1'), array('tid' => $tid));
			message('删除成功！', referer(), 'success');

			}elseif($operation == 'delall'){
				$ids= implode(",", $_GPC['delete']);
				$sqls= "update ".tablename('mcoder_recharge_card')." set isdel = 1 where tid in(".$ids.")";
				pdo_query($sqls);
				message('批量删除成功！', referer(), 'success');
	        
		}elseif($operation == 'getqrcode'){
			$tid = intval($_GPC['tid']);
			$card_item = pdo_fetch("SELECT cardno,cardse FROM ".tablename('mcoder_recharge_card')." where tid = ".$tid);
			//var_dump($card_item);
        	$imgname = "$card_item[cardno].png";
			$imgurl = "../addons/mcoder_recharge/qrcode/$imgname";	
			$value = $_W['siteroot'].'app/'.$this->createMobileUrl('isqrrecharge',array('cardno'=>$card_item['cardno'],'cardse'=>$card_item['cardse']));			
			if(!file_exists($imgurl)){
				include_once IA_ROOT . '/framework/library/qrcode/phpqrcode.php';									
				$errorCorrectionLevel = "L";
				$matrixPointSize = "4";
				QRcode::png($value, $imgurl, $errorCorrectionLevel, $matrixPointSize);
			}
			pdo_update('mcoder_recharge_card', array('isqrcard' => '1','qrcode_info'=>$value), array('tid' => $tid));
			message('&#20108;&#32500;&#30721;&#29983;&#25104;&#25104;&#21151;&#65281;', referer(), 'success');
		}
		include $this->template('list');
	}

	//一键生成所有的二维码
	public function doWebgetallqrcode(){
		global $_GPC, $_W;

		$pindex = max(1, intval($_GPC['page']));
		$psize = 50;

		$condition = " o.weid = :weid AND o.isdel = 0";
		$paras = array(':weid' => $_W['uniacid']);

		$limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;

		$sql = 'SELECT  cardno,tid,cardse FROM ' . tablename('mcoder_recharge_card') . ' AS `o`  WHERE ' .
				$condition . $limit;

		$list = pdo_fetchall($sql,$paras);

		if($list){

			foreach ($list as $key => $value) {
				$cardno = $value['cardno'];
				$cardse = $value['cardse'];
				$imgname = "$cardno.png";
				$imgurl = "../addons/mcoder_recharge/qrcode/$imgname";		
				$values = $_W['siteroot'].'app/'.$this->createMobileUrl('isqrrecharge',array('cardno'=>$cardno,'cardse'=>$cardse));			
				if(!file_exists($imgurl)){
					include_once IA_ROOT . '/framework/library/qrcode/phpqrcode.php';									
					$errorCorrectionLevel = "L";
					$matrixPointSize = "4";
					QRcode::png($values, $imgurl, $errorCorrectionLevel, $matrixPointSize);
				}
				pdo_update('mcoder_recharge_card', array('isqrcard' => '1','qrcode_info'=>$values), array('tid' => $value['tid']));
			}

			$pindex_ = $pindex +1; 

			message('正在生成二维码ing！', $this->createWebUrl('getallqrcode',array('page'=>$pindex_)), 'success');		
		}else{

			message('&#29983;&#25104;&#23436;&#27605;&#65281;', $this->createWebUrl('list',array(), 'success'));		
		}
	}

	//生成充值卡
	public function doWebadd(){
		global $_W, $_GPC;
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

		if ($operation == 'doadd') {


			if($_GPC['cardnums']<=0){
				message('生成的充值卡数量不能少于1个！', referer(), 'error');
			}

			if(empty($_GPC['cardintegration']) && empty($_GPC['cardamount'])){
				message('卡积分或者卡金额至少填写一项！', referer(), 'error');
			}

			$cardpre = (string)$_GPC['cardpre'];
			$cardnums = intval($_GPC['cardnums']);
			$cardamount = $_GPC['cardamount'];
			$cardintegration = $_GPC['cardintegration'];
			$remark = $_GPC['remark'];
			//2.0版本弃用
			//$rechargetype = intval($_GPC['rechargetype']);
			$options = $_GPC['options'];
			$weid = $_W['uniacid'];
			
			if($options){
				$issecretcard = 1;
			}else{
				$issecretcard = 0;
			}

			$sql = "insert into ".tablename('mcoder_recharge_card')." (cardno,cardse,cardamount,cardintegration,addtime,weid,issecretcard,remark) values ";
			for($i=0;$i<$cardnums;$i++){

				$timestring = explode(' ',microtime());
				$cardno = $cardpre.substr($timestring[1], 5).($timestring[0]*10000000).$i; 
				$cardse = $this->getRandChar();
				$addtime = TIMESTAMP;
				$sql .= "('$cardno','$cardse','$cardamount','$cardintegration','$addtime',$weid,$issecretcard,'$remark'),";

			}

			$sql = substr( $sql ,0, strlen($sql)-1);
			pdo_run($sql);
			message('成功生成'.$cardnums.'张充值卡！', referer(), 'success');

		}elseif($operation == 'display'){
			include $this->template('add');
		}		
	}
	
	public function doWebEditRemark(){
	    global $_W, $_GPC;
		if($_W['isajax']){
    		$tid = trim($_GPC['tid']);
    		$remark = trim($_GPC['remark']);
    		$operation = trim($_GPC['operation']);
    		
    		if($operation == 'remarkone'){    
    			$tid = intval($_GPC['tid']);
    			pdo_update('mcoder_recharge_card', array('remark' => $remark), array('tid' => $tid));
    			message(error(0,'备注成功'), '', 'ajax');
    		}elseif($operation == 'remarkall'){
    	        $tids= implode(",", $_GPC['tids']);
    	        $sqls= "update ".tablename('mcoder_recharge_card')." set remark = {$remark} where tid in(".$tids.")";
    	        pdo_query($sqls);
    	        message(error(0,'批量备注成功'), '', 'ajax');    	        
    		}
		}
	}
	public function doWebRemark_action(){
		global $_W, $_GPC;
		if($_W['isajax']){
    		$tid = trim($_GPC['tid']);
    		$remark = trim($_GPC['remark']);
    		$operation = trim($_GPC['op']);
    		
    		if($operation == 'remarkone'){    
    			$tid = intval($_GPC['tid']);
    			pdo_update('mcoder_recharge_card', array('remark' => $remark), array('tid' => $tid));
    			message(error(0,'备注成功'), '', 'ajax');
    		}elseif($operation == 'remarkall'){
    	        $tids= implode(",", $_GPC['tids']);
    	        $sqls= "update ".tablename('mcoder_recharge_card')." set remark = {$remark} where tid in(".$tids.")";
    	        pdo_query($sqls);
    	        message(error(0,'批量备注成功'), '', 'ajax');    	        
    		}elseif ($operation == 'getRemark'){
    			$tid = intval($_GPC['tid']);
    			$card_item = pdo_fetch("SELECT * FROM ".tablename('mcoder_recharge_card')." where tid = ".$tid);
    			return $this->return_json(200,'success',$card_item);
    		}
		}
	}

	public function doWebGetMemberInfo(){
		global $_W, $_GPC;
		if($_W['isajax']){
    		$uid = trim($_GPC['uid']);
    		load()->model('mc');
    		$user = mc_fetch($uid, array('nickname', 'realname', 'mobile', 'email'));

    		$data_html = '<div class="form-group" style="margin-bottom:40px;height:130px;">
							<label class="col-xs-12 col-sm-6 col-md-12 control-label" style="font-size:14px;margin-left:5%;font-weight:normal;">
								用户昵称：'.$user['nickname'].'
							</label>
							<label class="col-xs-12 col-sm-6 col-md-12 control-label" style="font-size:14px;margin-left:5%;font-weight:normal;">
								真实姓名：'.$user['realname'].'
							</label>
							<label class="col-xs-12 col-sm-6 col-md-12 control-label" style="font-size:14px;margin-left:5%;font-weight:normal;">
								手机号码：'.$user['mobile'].'
							</label>
							<label class="col-xs-12 col-sm-6 col-md-12 control-label" style="font-size:14px;margin-left:5%;font-weight:normal;">
								邮箱地址：'.$user['email'].'
							</label>
						</div>';
			return $this->return_json(200,'success',$data_html);
    	}
	}
	
	private function return_json($status = 200,$message = 'success',$data = null){	
		exit(json_encode(	
				array(	
						'status' => $status,	
						'message' => $message,	
						'data' => $data	
				)	
			)	
		);	
	}

	//扫码充值
	public function doMobileisqrrecharge(){
		global $_GPC, $_W;
		$operation = $_GPC['op'];

		if($this->module['config']['is_need_follow'] && $_W['fans']['follow'] != 1){
			//如果没有关注  提示关注
            die("<!DOCTYPE html>
                <html>
                    <head>
                        <meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'>
                        <title>需要关注公众号</title><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'><link rel='stylesheet' type='text/css' href='https://res.wx.qq.com/connect/zh_CN/htmledition/style/wap_err1a9853.css'>
                    </head>
                    <body>
                    <div class='page_msg'><div class='inner'><span class='msg_icon_wrp'><img style='width:300px;height:300px;' src='../attachment/qrcode_".$_W['uniacid'].".jpg'></span><div class='msg_content'><h4>长按上图二维码，关注公众号</h4></div></div></div>
                    </body>
                </html>");
			
		}
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
        if (empty($_SERVER['HTTP_USER_AGENT']) || strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false && strpos($_SERVER['HTTP_USER_AGENT'], 'Windows Phone') === false) {
            die("<!DOCTYPE html>
                <html>
                    <head>
                        <meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'>
                        <title>抱歉，出错了!</title><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'><link rel='stylesheet' type='text/css' href='https://res.wx.qq.com/connect/zh_CN/htmledition/style/wap_err1a9853.css'>
                    </head>
                    <body>
                    <div class='page_msg'><div class='inner'><span class='msg_icon_wrp'><i class='icon80_smile'></i></span><div class='msg_content'><h4>请在微信客户端打开！</h4></div></div></div>
                    </body>
                </html>");
        }

		if(empty($_GPC['cardno']) || empty($_GPC['cardse'])){
			die("<!DOCTYPE html>
                <html>
                    <head>
                        <meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'>
                        <title>抱歉，出错了!</title><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'><link rel='stylesheet' type='text/css' href='https://res.wx.qq.com/connect/zh_CN/htmledition/style/wap_err1a9853.css'>
                    </head>
                    <body>
                    <div class='page_msg'><div class='inner'><span class='msg_icon_wrp'><i class='icon80_smile'></i></span><div class='msg_content'><h4>非法的参数请求！</h4></div></div></div>
                    </body>
                </html>");
		}else{
			$cardno = (string)$_GPC['cardno'];
			$cardse = (string)$_GPC['cardse'];
		}

		$row = pdo_fetch("SELECT * FROM " . tablename('mcoder_recharge_card') . " WHERE weid= :weid AND cardno= :cardno AND isqrcard= 1", array(':weid' => $_W['uniacid'],":cardno" => $cardno));
		if(!$row){
			die("<!DOCTYPE html>
                <html>
                    <head>
                        <meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'>
                        <title>抱歉，出错了!</title><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'><link rel='stylesheet' type='text/css' href='https://res.wx.qq.com/connect/zh_CN/htmledition/style/wap_err1a9853.css'>
                    </head>
                    <body>
                    <div class='page_msg'><div class='inner'><span class='msg_icon_wrp'><i class='icon80_smile'></i></span><div class='msg_content'><h4>此充值卡不存在！</h4></div></div></div>
                    </body>
                </html>");
		}elseif($row['cardse'] != $cardse){
						die("<!DOCTYPE html>
                <html>
                    <head>
                        <meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'>
                        <title>抱歉，出错了!</title><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'><link rel='stylesheet' type='text/css' href='https://res.wx.qq.com/connect/zh_CN/htmledition/style/wap_err1a9853.css'>
                    </head>
                    <body>
                    <div class='page_msg'><div class='inner'><span class='msg_icon_wrp'><i class='icon80_smile'></i></span><div class='msg_content'><h4>充值卡密码不对！</h4></div></div></div>
                    </body>
                </html>");
		}elseif($row['status'] == 1){
						die("<!DOCTYPE html>
                <html>
                    <head>
                        <meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'>
                        <title>抱歉，出错了!</title><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'><link rel='stylesheet' type='text/css' href='https://res.wx.qq.com/connect/zh_CN/htmledition/style/wap_err1a9853.css'>
                    </head>
                    <body>
                    <div class='page_msg'><div class='inner'><span class='msg_icon_wrp'><i class='icon80_smile'></i></span><div class='msg_content'><h4>此充值卡已经被充值过了哦！</h4></div></div></div>
                    </body>
                </html>");
		}elseif($row['cardse'] == $cardse){
			$data = array(
				'status' => 1,
				'usedtime' => TIMESTAMP,
				'openid' => $_W['openid'],
			);

			$amount = $row['cardamount'];
			$integration = $row['cardintegration'];

			if($row['cardamount']){
				//充值余额
				$credit = 'credit2';
				$fuid = mc_openid2uid($_W['openid']);
				$fuser = mc_fetch($fuid,array('nickname'));
				$record[] = $fuid;
				$record[] = '通过充值卡充值'.$amount.'元！';
				$recharge_info = ' <font style="font-weight:bold;">'.$amount.'</font>元余额';
				mc_credit_update($fuid, $credit, $amount, $record);
			}

			if($row['cardintegration']){
				//充值积分
				$credit = 'credit1';
				$fuid = mc_openid2uid($_W['openid']);
				$fuser = mc_fetch($fuid,array('nickname'));
				$record[] = $params['user'];
				$record[] = '通过充值卡充值' . $integration.'积分';
				$recharge_info .= ' <font style="font-weight:bold;">'.$integration.'</font>积分';
				mc_credit_update($fuid, $credit, $integration, $record);
			}
			pdo_update('mcoder_recharge_card', $data, array('tid' => $row['tid']));
			$url = !empty($this->module['config']['qr_skip_url'])?$this->module['config']['qr_skip_url']:murl('entry', array('c' => 'mc', 'a' => 'home'), true, true);
			$text = !empty($this->module['config']['qr_skip_text'])?$this->module['config']['qr_skip_text']:'点击进入会员中心查看！';
			$recharge_info = 
			die("<!DOCTYPE html>
                <html>
                    <head>
                        <meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'>
                        <title>恭喜!</title><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'><link rel='stylesheet' type='text/css' href='https://res.wx.qq.com/connect/zh_CN/htmledition/style/wap_err1a9853.css'>
                    </head>
                    <body>
                    <div class='page_msg'><div class='inner'><span class='msg_icon_wrp'><i class='icon80_smile'></i></span><div class='msg_content'>
					<h4>恭喜，您已成功充值".$recharge_info."！<br/><br/><br/><a style='color:#333333;border-bottom: dotted 1px;text-decoration:none;' href=".$url.">".$text."</a></h4></div></div></div>
                    </body>
                </html>");
		}else{
			//充值失败
				die("<!DOCTYPE html>
                <html>
                    <head>
                        <meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'>
                        <title>对不起！</title><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'><link rel='stylesheet' type='text/css' href='https://res.wx.qq.com/connect/zh_CN/htmledition/style/wap_err1a9853.css'>
                    </head>
                    <body>
                    <div class='page_msg'><div class='inner'><span class='msg_icon_wrp'><i class='icon80_smile'></i></span><div class='msg_content'><h4>对不起，充值失败！</h4></div></div></div>
                    </body>
                </html>");
		}
		
	}

	public function mobile_message($info,$url,$status){
		switch ($status) {
			case 'success':
				$title = '恭喜！';
				$class = 'icon80_smile';
				break;
			case 'error':
				$title = '抱歉！';
				$class = 'icon80_smile';
				break;
			default:
				$title = '抱歉！';
				$class = 'icon80_smile';
				break;
		}
		die("<!DOCTYPE html>
                <html>
                    <head>
                        <meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'>
                        <title>".$title."</title><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'><link rel='stylesheet' type='text/css' href='https://res.wx.qq.com/connect/zh_CN/htmledition/style/wap_err1a9853.css'>
                    </head>
                    <style>.btn-lg{width:90%; height:2.5em; margin:2.5em auto; line-height:2.5em; font-size:1em; font-family:'微软雅黑'; text-align:center; color:#fff; border:none; border-radius:5px; background:#44BB44; display:block;}</style>
                    <body style='color:#fff;'>
                    <div class='page_msg'><div class='inner'><span class='msg_icon_wrp'><i class='".$class."'></i></span><div class='msg_content'>
					<h4>".$info."<br/><br/><br/><a class='btn btn-lg' href=".$url." style='text-decoration:none;'>".确定."</a></h4></div></div></div>
                    </body>
                </html>");
		return false;
	}

	public function doMobileissecretrecharge(){
		global $_GPC, $_W;
		$operation = $_GPC['op'];

		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
        if (empty($_SERVER['HTTP_USER_AGENT']) || strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false && strpos($_SERVER['HTTP_USER_AGENT'], 'Windows Phone') === false) {
            $this->mobile_message('&#35831;&#22312;&#24494;&#20449;&#23458;&#25143;&#31471;&#25171;&#24320;&#65281;', $this->createMobileUrl('issecretrecharge'), 'error');
        }

		if($operation == 'dorecharge'){
			if(empty($_GPC['cardse'])){
				$this->mobile_message('请将充值卡卡密填写完整！', $this->createMobileUrl('issecretrecharge'), 'error');
			}else{
				$cardse = (string)$_GPC['cardse'];
			}
			$row = pdo_fetch("SELECT * FROM " . tablename('mcoder_recharge_card') . " WHERE weid= :weid AND cardse= :cardse AND issecretcard=1", array(':weid' => $_W['uniacid'],":cardse" => $cardse));
			if(!$row){
				$this->mobile_message('此充值卡不存在！', $this->createMobileUrl('issecretrecharge'), 'error');
			}elseif($row['cardse'] != $cardse){
				$this->mobile_message('充值卡密码不对！', $this->createMobileUrl('issecretrecharge'), 'error');
			}elseif($row['status'] == 1){
				$this->mobile_message('该充值卡已被充值过！', $this->createMobileUrl('issecretrecharge'), 'error');
			}elseif($row['cardse'] == $cardse){
				$data = array(
					'status' => 1,
					'usedtime' => TIMESTAMP,
					'openid' => $_W['openid'],
				);

				$amount = $row['cardamount'];
				$integration = $row['cardintegration'];



				if($row['cardamount']){
					//充值余额
					$credit = 'credit2';
					$fuid = mc_openid2uid($_W['openid']);
					$fuser = mc_fetch($fuid,array('nickname'));
					$record[] = $fuid;
					$record[] = '通过充值卡充值'.$amount.'元！';

					$recharge_info = ' <font style="font-weight:bold;">'.$amount.'</font>元余额';
					mc_credit_update($fuid, $credit, $amount, $record);
				}

				if($row['cardintegration']){
					//充值积分
					$credit = 'credit1';
					$fuid = mc_openid2uid($_W['openid']);
					$fuser = mc_fetch($fuid,array('nickname'));
					$record[] = $params['user'];
					$record[] = '通过充值卡充值' . $integration.'积分';

					$recharge_info .= ' <font style="font-weight:bold;">'.$integration.'</font>积分';
					
					mc_credit_update($fuid, $credit, $integration, $record);
				}
							
				pdo_update('mcoder_recharge_card', $data, array('tid' => $row['tid']));

				$this->mobile_message(!empty($this->module['config']['web_issecret_skip_text'])?$this->module['config']['web_issecret_skip_text']:'恭喜，您已成功充值'.$recharge_info.'！',  !empty($this->module['config']['web_issecret_skip_url'])?$this->module['config']['web_issecret_skip_url']:$this->createMobileUrl('issecretrecharge'), 'success');
			}else{
				$this->mobile_message('充值失败！', $this->createMobileUrl('issecretrecharge'), 'error');
			}
		}else{
			include $this->template('recharge_secret');
		}
	} 

	public function doWebXls() {
		global $_W,$_GPC;
		$op = $_GPC['op'];
		if($op=='import'){
			include_once("lib/reader.php");
			$tmp = $_FILES['file']['tmp_name'];
			$ext = @end(explode('.', $_FILES['file']['name']));
			$ext = strtolower($ext);
			if($ext !='xls' || empty($tmp)){
				$str = '只支持xls文件！';
			}else{
				$sheets = array();
				$path = $_W['config']['upload']['attachdir'];
				if(!is_dir('../'.$path.'/xls')){
					mkdir('../'.$path.'/xls', 0777);
				}
				$save_path = $_W['config']['upload']['attachdir']."/xls/";
				//$save_path = '/'.$_W['config']['upload']['attachdir'];
				$file_name = '../'.$save_path.date('Ymdhis') . ".xls";
				//echo $file_name;
				if (copy($tmp, $file_name)) {
					$xls = new Spreadsheet_Excel_Reader();
					$xls->setOutputEncoding('utf-8');
					$xls->read($file_name);
					$createtime=TIMESTAMP;
					$numRows=$xls->sheets[0]['numRows'];
					$suscess_row=0;
					$error_row=0;
					$data=array(
						'addtime'=>$createtime,
						'weid'=>$_W['uniacid'],
					);
					for ($i=2; $i<=$numRows; $i++) {

						$timestring = explode(' ',microtime());
						$cardno = $cardpre.substr($timestring[1], 5).($timestring[0]*10000000).$i; 
						$cardse = $this->getRandChar();

						$sheets1 = $xls->sheets[0]['cells'][$i][1];
						$sheets2 = $xls->sheets[0]['cells'][$i][2];
						$sheets3 = $xls->sheets[0]['cells'][$i][3];
						$sheets4 = $xls->sheets[0]['cells'][$i][4];
						$sheets5 = $xls->sheets[0]['cells'][$i][5];	
						$sheets6 = $xls->sheets[0]['cells'][$i][6];
						$data['cardse']=trim($sheets2);
						$data['cardamount']=(int)$sheets3;
						$data['cardintegration']=(int)$sheets4;
						$data['issecretcard']=(int)$sheets5;
						$data['remark']=trim($sheets6);
						if($sheets5 == 1){
							$data['cardno'] = $cardno;
						}else{
							$data['cardno'] = trim($sheets1);
						}						
						if($sheets2 == ''){
							$data['cardse'] = $cardse;
						}
						
/*						if($i<12){
							$data_values .= " <tr><td>$i</td> <td> $sheets1</td> <td> $sheets2</td> <td>$sheets3</td> <td> $sheets4</td> <td> $sheets5</td></tr> ";
						}*/
						
						//开始插入
						if(pdo_insert('mcoder_recharge_card',$data)){ 
							++$suscess_row; 
						}else{ 
							++$error_row; 
						}
					}
					
				  $str = $suscess_row." 条记录导入成功</br>";
				  $str .= $error_row." 条记录导入失败</br>";
				  //$str .= "您的EXCEL数据：</br></br><table width=600 border=1 cellpadding=0 cellspacing=0 bordercolor=\"#CCCCCC\">";
				  //$str .= $data_values;
				  //$str .= "</table>";
				  
				}else{
					$str='请正确上传XLS文件';
				}
			}
		}
		include $this->template('xls');
	}
	//生成密码
	private function getRandChar($length=8){
		$str = null;
		$strPol = "0123456789abcdefghijklmnopqrstuvwxyz";

		//$strPol = "0123456789";
		$max = strlen($strPol)-1;

		for($i=0;$i<$length;$i++){
		$str.=$strPol[rand(0,$max)];
		}

		return $str;
	}
}