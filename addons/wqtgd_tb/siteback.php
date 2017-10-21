<?php
/**
 * 淘宝红包返现模块微站定义
 *
 * @author 特工队
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Wqtgd_tbModuleSite extends WeModuleSite {
	public $_weid = '';
	public $_from_user = '';
	public $_upload_prefix = '';
	private $_debug_flag = 0;
	private function hdmkqxflag()
	{
		return;
		load()->classs('cloudapi');
		$api = new CloudApi();
		$mkqx = $api->get('getuserbuydata', 'getUserBuyData', array('mk' => $this->modulename));
		if ($mkqx['error'] != 2) {
		message($mkqx['message'], 'http://s.we7.cc/index.php?c=home&a=author&do=index&uid=91001', 'error');
		}
	}



	public function __construct()
   	 {
	        global $_W, $_GPC;
	        if ($_GPC['fseller'] == 'view') {
	            $this->_debug_flag = 1;
	        }
	        if ($this->_debug_flag) {
	            $this->_from_user = 'test_from_user';
	            $this->_weid = 2;
	        } else {
	            $this->_from_user = $_W['openid'];
	            $this->_weid = $_W['uniacid'];
	        }
	        $string = $_SERVER['REQUEST_URI'];
	        if ($this->_debug_flag == 0 && strpos($string, 'app') == true && !strstr($string, 'httpsendhongbao')) {
	            $useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
	            if (strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false) {
	                message('非微信浏览器，请通过微信打开！');
	                die;
	            }
	            $cfg_arr = $this->getConfigArr();
	            if ($cfg_arr['openguanzhu'] == 'Y' && $cfg_arr['openguanzhu_url']) {
	                $sql = "select * from " . tablename("mc_mapping_fans") . " where openid='" . $this->_from_user . "' and follow>0 and uniacid='" . $this->_weid . "'";
	                $reg_arr = pdo_fetch($sql);
	                if (!$reg_arr) {
	                    header('location:' . $cfg_arr['openguanzhu_url']);
	                    exit;
	                }
	            }
	            if ($cfg_arr['open_gps'] == 'Y' && !$_SESSION['gps_chk_' . date('ymdh')]) {
	                $_save_from_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
	                $weixin_get_fans_location = $_SESSION['weixin_get_fans_location'];
	                if (!$weixin_get_fans_location || $_SESSION['weixin_get_fans_location'] == '未知') {
	                    if ($_POST['fans_location_json']) {
	                        $fans_location_json = $_POST['fans_location_json'];
	                        if ('no_open' == $fans_location_json) {
	                            $_SESSION['weixin_get_fans_location'] = '未知';
	                            $_SESSION['gps_chk_' . date('ymdh')] = 1;
	                            echo json_encode(array('success' => true));
	                            exit;
	                        }
	                        $fans_location_arr = json_decode($fans_location_json, 1);
	                        if ($fans_location_arr['district']) {
	                            $_SESSION['weixin_get_fans_location'] = $fans_location_arr['province'] . $fans_location_arr['city'] . $fans_location_arr['district'] . $fans_location_arr['addr'];
	                        } else {
	                        }
	                        echo json_encode(array('success' => true));
	                        exit;
	                    }
	                    require_once 'geolocation/get_user_detail_address.php';
	                    exit;
	                }
	            }
	        }

/*$appid = $this->module['config']['fhb_appid'];

$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.'wxafca714812a5db91'.'&redirect_uri=http%3a%2f%2fwe7.zssyb.com%2faddons%2fwqtgd_tb%2foauth.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';  
   
header("Location:".$url);  	*/        
	    }
	// 首页/
	public function doMobileIndex() {
		global $_W,$_GPC;
		$start_time = $this->module['config']['start_time'];
		$end_time = $this->module['config']['end_time'];
		$today = date("Y-m-d",time());
		$index_title = $this->module['config']['index_title'];
		$images_sl = $this->module['config']['images_sl'];
		$share_title = $this->module['config']['share_title'];
		$share_photo = $this->module['config']['share_photo'];
		$share_desc = $this->module['config']['share_desc'];
		
		$Lookup = $this->createMobileUrl('Lookup');//奖励插入路径
		$Detail = $this->createMobileUrl('Detail',array('openid'=>$_W['fans']['from_user']));//用户奖品记录路径
		if($today >= $start_time && $today <= $end_time){
			include $this->template('index');		
		}else{
			message("活动已结束!");
		}
	}
	public function doMobileLookup() {
		global $_W, $_GPC;
		$openid = $_W['fans']['openid'];
		//昵称获取
		$sql = "SELECT  mem.nickname " . "FROM " . tablename('mc_mapping_fans') . " fans ". "LEFT JOIN " . tablename('mc_members') . " mem ON fans.uid=mem.uid " ."where fans.openid=:openid";
		$data = pdo_fetchall($sql,array(':openid'=>$openid));

		$nickname = $data['0']['nickname'];
		$_W['nickname'] = $nickname;

		$sql =  "select `order_name` from " . tablename('wqtgd_order');
		$data = pdo_fetchall($sql);
		$num = count($data);
		$order_list = [];
		for ($i = 0; $i < $num; $i++) {
			$order_list[] = $data[$i]['order_name'];
		}
		//检测订单号是否存在
		if (in_array($_GPC['order_name'],$order_list)) {
			//检测订单号是否重复
			$sql =  "select order_name from " . tablename('wqtgd_mation');//执行的SQL语句
			$mation = pdo_fetchall($sql);
			$shu = count($mation);
			$mation_order = [];
			for ($i = 0; $i < $shu; $i++) {
				$mation_order[] = $mation[$i]['order_name'];
			}
			if (!in_array($_GPC['order_name'],$mation_order)) {
				$sql =  "select * from " . tablename('wqtgd_fanxi1');//执行的SQL语句
				$fanxi = pdo_fetch($sql);//返回的信息|$res以数组形式包含所以字段
				$mode 	  = $fanxi['mode'];//奖励方式 【0:积分,1:余额,2:红包】
				switch ($mode) {
					case 0://积分
						$credit = 'credit1';
						$integral = $fanxi['integral'];//积分
						$mode_name = '积分';
						break;
					case 1://余额
						$credit = 'credit2';
						$integral = $fanxi['balance'];//余额
						$mode_name = '余额';
						break;
					case 2://红包
						$credit = 'credit3';
						// $integral = $fanxi['Red'];//红包
						$mode_name = '元红包';


				    		 $order_name = $_GPC['order_name'];
				    		 $openid = $_W['fans']['from_user'];
				    		 $receive_flag = 'N' ;
				    		 $send_type = "现金红包";
				    		 $send_time = date('Y-m-d H:i:s');
				    		 $send_time = date('Y-m-d H:i:s');
				    		 $send_res = "SENDING";
				    		 $weid = $this->_weid;
				    		 $billno = ' ';


						$fhb_send_type = $this->module['config']['fhb_send_type'];
						if ($fhb_send_type == 'f') {
						    $money = round($this->module['config']['fhb_send_money'] / 1, 2);
						} else {
						    $dat['fhb_send_money_from'] = $this->module['config']['fhb_send_money_from'];
						    $dat['fhb_send_money_to'] = $this->module['config']['fhb_send_money_to'];
						    $money = $this->randomFloat($dat['fhb_send_money_from'], $dat['fhb_send_money_to']);
						}


				    		 // $money = round($this->module['config']['fhb_send_money'] / 1, 2);
				    		 $data = array('weid' => $weid,'openid'=>$openid ,'billno'=>$billno, 'nickname'=>$nickname , 'order_name' =>$order_name , 'receive_flag'=>$receive_flag , 'money' =>$money , 'send_type' => $send_type , 'send_time' => $send_time , 'send_res'=>$send_res , 'status' =>0,'remark'=>'');

				    		 // var_dump($data);


						pdo_insert('wqtgd_sendrec' , $data);

						$in_id = pdo_insertid();

						$billno = $this->module['config']['fhb_mchid'] . date('Ymd') . str_pad($in_id, 10, "0", STR_PAD_LEFT);
						pdo_update('wqtgd_sendrec', array('billno' => $billno), array('id' => $in_id));

						$id = $in_id;

						 //请求现金红包url
					 	// https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack

						$data = pdo_fetch("SELECT * FROM " . tablename('wqtgd_sendrec') . " WHERE id = :id", array(':id' => $id));

						$send_res = $this->send_hongbao($data['billno'], $data['openid'], $data['money']);
						// var_dump($send_res);

						$res = $send_res['return_code'] . '->' . $send_res['return_msg'];

						$update_data = array('send_res' => $res, 'send_time' => date('Y-m-d H:i:s'));
						pdo_update('wqtgd_sendrec', $update_data, array('id' => $id));


						$integral = $data['money'] / 100;

						// $obj_url = $this->createMobileUrl('httpsendhongbao',array('id'=>$id,'data'=>$data));
						
						// $res = file_get_contents($obj_url);

						// var_dump($res);



						break;
				}
				//------------------------------

				//订单正确获得积分
				$uid = $_W['member']['uid'];
				load()->model('mc');
				$res =  mc_credit_update($uid ,  $credit , $integral , array(0, '返现赠送'. $integral .$mode_name));
				//--------奖品信息加入----
				$array = array(
					'openid'   	 => $_W['fans']['from_user'],//openid
					'nickname'   => $nickname,//昵称
					'order_name' => $_GPC['order_name'],//订单号
					'mation'	 => '获得' . $integral . $mode_name,
					'tel'		 => '',
					'addtime' 	 => time(),
					);
				if ($res) {
					$res = pdo_insert('wqtgd_mation',$array);
					echo json_encode ($integral . $mode_name);
				}else{
					echo '0';//数据库插入错误
					exit;
				}
			}else{
				echo 'false';//订单重复
				exit;
			}
		}else{
			echo 'null';//订单号不存在
			exit;
		}

	}
		//用户奖品记录
	public function doMobileDetail() {
		global $_W, $_GPC;
		// $openid = $_GPC['openid'];
		$openid = $_W['fans']['openid'];
		$sql_Detali =  "select * from " . tablename('wqtgd_mation') . "where openid = '{$openid}'";//执行的SQL语句
		$openid_coun = pdo_fetchall($sql_Detali);//返回的信息|$res以数组
		
		// $sql = "SELECT  mem.nickname " . "FROM " . tablename('mc_mapping_fans') . " fans ". "LEFT JOIN " . tablename('mc_members') . " mem ON fans.uid=mem.uid " ."where fans.openid=:openid";
		// $data = pdo_fetchall($sql,array(':openid'=>$openid));

		// var_dump($_W['fans']); 
		// var_dump($data);
		// echo "<pre>";
		// print_r($data['0']['nickname']);
		include $this->template('Detail');
	}
	public function doWebJilu() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		$openid = $_W['openid'];
		$nickname = $_W['nickname'];
		$pindex = max(1, intval($_GPC['page']));;
    	$psize = 12;//每页显示条数
		$sql =  "select * from " . tablename('wqtgd_mation') ." limit " . ($pindex - 1) * $psize . ',' . $psize;;
		$mation = pdo_fetchall($sql);//执行的SQL语句
		$total = pdo_fetchcolumn("select count(*) from ".tablename("wqtgd_mation").$condition);
		$pager = pagination($total, $pindex, $psize);//模板输出{$pager}
		include $this->template('Jilu');
	}
	//
	public function doMobileJilu() {
		//这个操作被定义用来呈现 ec
	}
	public function doMobileFafang() {
		//这个操作被定义用来呈现 微站快捷功能导航
	}

	// public function doWebFafang() {
	// 	//这个操作被定义用来呈现 管理中心导航菜单
	// 	global $_W,$_GPC;

	// 	$openid = $_W['openid'];
	// 	include $this->template('sendrec');
	// }

	//发放红包模板
	public function doWebSendrec(){
		global $_W,$_GPC;



		$openid = $_W['openid'];

		$this->hdmkqxflag();
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$condition = " rec.weid=" . $this->_weid;
		if ($_GPC['openid']) {
		$condition .= " and rec.openid like '%" . $_GPC['openid'] . "%'";
		}
		$sql = "SELECT rec.*, mem.nickname " . "FROM " . tablename('wqtgd_sendrec') . " rec " . "LEFT JOIN " . tablename('mc_mapping_fans') . " fans ON rec.openid=fans.openid " . "LEFT JOIN " . tablename('mc_members') . " mem ON fans.uid=mem.uid " . "WHERE {$condition} and rec.status = 0 ORDER BY rec.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
		$list = pdo_fetchall($sql);
		$total = pdo_fetchcolumn('SELECT COUNT(*) ' . "FROM " . tablename('wqtgd_sendrec') . " rec " . "LEFT JOIN " . tablename('mc_mapping_fans') . " fans ON rec.openid=fans.openid " . "LEFT JOIN " . tablename('mc_members') . " mem ON fans.uid=mem.uid " . "WHERE {$condition}");
		$pager = pagination($total, $pindex, $psize);

		

		 include $this->template('sendrec');
	}


	//红包发放添加
	public function doWebSendrec_add()
	{

		 global $_W, $_GPC;
	        if ($_GPC['op'] == 'delete') {
	            $id = intval($_GPC['id']);
	            $row = pdo_fetch("SELECT id FROM " . tablename('wqtgd_sendrec') . " WHERE id = :id", array(':id' => $id));
	            if (empty($row)) {
	                message('抱歉，信息不存在或是已经被删除！');
	            }
	            pdo_update('wqtgd_sendrec', array( 'status'=> 1) , array('id' => $id));
	            message('删除成功！', referer(), 'success');
	        }
	        if (!empty($_FILES['file_cumtomer']['name'])) {
	            $tmp_file = $_FILES['file_cumtomer']['tmp_name'];
	            $file_types = explode(".", $_FILES['file_cumtomer']['name']);
	            $file_type = $file_types[count($file_types) - 1];
	            $savePath = IA_ROOT . '/addons/wqtgd_tb/template/upFile/';
	            $str = date('Ymdhis');
	            $file_name = $str . "." . $file_type;
	            if (!copy($tmp_file, $savePath . $file_name)) {
	                message('上传失败');
	            }
	            $res = $this->read($savePath . $file_name);
	            $insert['receive_flag'] = 'N';
	            $insert['weid'] = $this->_weid;
	            $insert['send_time'] = date('Y-m-d H:i:s');
	            foreach ($res as $k => $v) {
	                if ($k != 0) {
	                    $insert['openid'] = trim($v[0]);
	                    $insert['money'] = round($v[1], 2);
	                    $insert['remark'] = $v[2];
	                    pdo_insert('wqtgd_sendrec', $insert);
	                    $in_id = pdo_insertid();
		       $billno = $this->module['config']['fhb_mchid'] . date('Ymd') . str_pad($in_id, 10, "0", STR_PAD_LEFT);
		       pdo_update('wqtgd_sendrec', array('billno' => $billno), array('id' => $in_id));
		        $id = $in_id;

	                    $data = pdo_fetch("SELECT * FROM " . tablename('wqtgd_sendrec') . " WHERE id = :id", array(':id' => $id));
	                    $send_res = $this->send_hongbao($data['billno'], $data['openid'], $data['money'] *100);
	                    $res = $send_res['return_code'] . '->' . $send_res['return_msg'];
	                    $update_data = array('send_res' => $res, 'send_time' => date('Y-m-d H:i:s'));
	                    pdo_update('wqtgd_sendrec', $update_data, array('id' => $id));
	                }
	            }
	            $curr_index_url = $this->createWebUrl('sendrec');
	            message('导入成功！', $curr_index_url, 'success');
	        } else {
	            if (checksubmit()) {
	                $id = intval($_GPC['id']);
	                $data = array('weid' => $this->_weid, 'openid' => $_GPC['openid'], 'money' => $_GPC['money'] * 1,'remark' => $_GPC['remark']);
	               
	                if (!empty($id)) {
	                    pdo_update('wqtgd_sendrec', $data, array('id' => $id));
	                } else {
	                    $data['receive_flag'] = 'N';
	                    // $data['createtime'] = date('Y-m-d H:i:s');
	                    pdo_insert('wqtgd_sendrec', $data);
	                    $id = pdo_insertid();
	                    $data = pdo_fetch("SELECT * FROM " . tablename('wqtgd_sendrec') . " WHERE id = :id", array(':id' => $id));
	                    pdo_update('wqtgd_sendrec', array('order_name' => $data['order_name']), array('id' => $id));
	                    $id = $in_id;
	                }
	                $curr_index_url = $this->createWebUrl('sendrec');
	                $data = pdo_fetch("SELECT * FROM " . tablename('wqtgd_sendrec') . " WHERE id = :id", array(':id' => $id));
	                $send_res = $this->send_hongbao($data['order_name'],$data['openid'], $data['money']);
	                $res = $send_res['return_code'] . '->' . $send_res['return_msg'];
	                $update_data = array('send_res' => $res, 'send_time' => date('Y-m-d H:i:s'));
	                pdo_update('wqtgd_sendrec', $update_data, array('id' => $id));
	                message('更新成功！' . $res, $curr_index_url, 'success');
	            }
	        }
	        $data = array();
	        $fhb_send_type = $this->module['config']['fhb_send_type'];
	        if ($fhb_send_type == 'f') {
	            $data['money'] = round($this->module['config']['fhb_send_money'] / 1, 2);
	        } else {
                $money = $this->randomFloat($cfgArr['fhb_send_money_from'], $cfgArr['fhb_send_money_to']);
	}
		 include $this->template('sendrec_add');
	}


	//全选删除
	 public function doWebDeleteBatch()
	{
	    global $_W, $_GPC;
	    $table_name = $_GPC['table'];
	    foreach ($_GPC['idArr'] as $k => $id) {
	        $id = intval($id);
	        if ($id == 0) {
	            continue;
	        }
	        $fans = pdo_fetch("select * from " . tablename($table_name) . " where id = :id", array(':id' => $id));
	        if (empty($fans)) {
	            $this->webmessage('抱歉，选中的记录数据不存在！');
	        }
	        pdo_update($table_name, array( 'status'=> 1) ,array('id' => $id));
	        // pdo_update('wqtgd_sendrec', array( 'status'=> 1) , array('id' => $id));
	        // message('删除成功！', referer(), 'success');
	    }
	    $this->webmessage('记录删除成功！', '', 0);
	}



	//发送红包
	  // public function doMobileHttpsendhongbao($id,$data)
   //  	{
   //  		 global $_W, $_GPC;

		
    		
   //  	}




    	//更新红包的状态
    	 public function doWebGetstatus()
	    {
	        global $_W, $_GPC;
	        $send_res = array('SENDING' => '发放中', 'SENT' => '已发放待领取', 'FAILED' => '发放失败', 'RECEIVED' => '已领取', 'REFUND' => '已退款');
	        $condition = " weid= " . $this->_weid;
	        $sql = "SELECT id, billno FROM " . tablename('wqtgd_sendrec') . " WHERE  {$condition} and receive_flag !='Y' ORDER BY id DESC";
	        $list = pdo_fetchall($sql);
	        foreach ($list as $row) {
	            $get_status_arr = $this->get_hongbao_status($row['billno']);
	            if ($get_status_arr['return_code'] == 'SUCCESS' && $get_status_arr['result_code'] == 'SUCCESS') {
	                $receive_flag = $get_status_arr['status'] == 'RECEIVED' ? 'Y' : 'N';
	                pdo_update('wqtgd_sendrec', array('receive_flag' => $receive_flag, 'remark' => $row['remark'] .' 最新状态：' . $send_res[$get_status_arr['status']]), array('id' => $row['id']));
	            }
	        }
	        message('更新完成！', $this->createWebUrl('sendrec'), 'success');
	    }
       
		
	   //通过网页请求获取状态码
	    private function get_hongbao_status($billno)
	    {
	        global $_W, $_GPC;
	        $para_data = pdo_fetch("SELECT * FROM " . tablename('uni_account_modules') . " WHERE module = :module AND uniacid = :uniacid", array(':module' => 'wqtgd_tb', ':uniacid' => $_W['uniacid']));
	        $para_data = unserialize($para_data['settings']);
	        $company_info = array('mchid' => $para_data['fhb_mchid'], 'appid' => $para_data['fhb_appid'], 'key' => $para_data['fhb_send_key']);
	        $para_str = '';
	        $hongbao_url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gethbinfo';
	        $param_arr = array('nonce_str' => md5(date('YmdHis') . rand(0, 1000)), 'mch_billno' => $billno, 'mch_id' => $company_info['mchid'], 'appid' => $company_info['appid'], 'bill_type' => 'MCHT');
	        ksort($param_arr);
	        $para_str = $this->formatQueryParaMap($param_arr);
	        $para_str .= '&key=' . $company_info['key'];
	        $param_arr['sign'] = strtoupper(md5($para_str));
	        $xml = $this->arr2xml($param_arr);
	        $result = $this->vpost($hongbao_url, $xml);
	        $array_data = json_decode(json_encode(simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
	        return $array_data;
	    }




	    // 现金红包请求
    	private function send_hongbao($billno, $openid, $money)
	    {
	        $company_info = array('mchid' => $this->module['config']['fhb_mchid'], 'billno' => $billno, 'appid' => $this->module['config']['fhb_appid'], 'send_name' => $this->module['config']['fhb_send_name'], 'nick_name' => $this->module['config']['fhb_nick_name'], 'openid' => $openid, 'min_value' => $money, 'max_value' => $money, 'total_amount' => $money, 'total_num' => 1, 'wishing' => $this->module['config']['fhb_wishing'], 'remark' => $this->module['config']['fhb_remark'], 'act_name' => $this->module['config']['fhb_act_name'], 'key' => $this->module['config']['fhb_send_key']);
	        $para_str = '';
	        $hongbao_url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
	        $param_arr = array('nonce_str' => md5(date('YmdHis') . rand(0, 1000)), 'mch_billno' => $company_info['billno'], 'mch_id' => $company_info['mchid'], 'wxappid' => $company_info['appid'], 'send_name' => $company_info['send_name'], 'nick_name' => $company_info['nick_name'], 're_openid' => $company_info['openid'], 'min_value' => $company_info['min_value'], 'max_value' => $company_info['max_value'], 'total_amount' => $company_info['total_amount'], 'total_num' => $company_info['total_num'], 'wishing' => $company_info['wishing'], 'client_ip' => $_SERVER['SERVER_ADDR'] ? $_SERVER['SERVER_ADDR'] : '192.168.1.1', 'remark' => $company_info['remark'], 'act_name' => $company_info['act_name']);
	        ksort($param_arr);
	        $para_str = $this->formatQueryParaMap($param_arr);
	        $para_str .= '&key=' . $company_info['key'];
	        $param_arr['sign'] = strtoupper(md5($para_str));
	        $xml = $this->arr2xml($param_arr);
	        $result = $this->vpost($hongbao_url, $xml);
	        $array_data = json_decode(json_encode(simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
	        return $array_data;
	    }




	    private function arr2xml($data, $root = true)
	    {
	        $str = "";
	        if ($root) {
	            $str .= "<xml>";
	        }
	        foreach ($data as $key => $val) {
	            if (is_array($val)) {
	                $child = $this->arr2xml($val, false);
	                $str .= "<{$key}>{$child}<{$key}>";
	            } else {
	                $str .= "<{$key}><![CDATA[{$val}]]></{$key}>";
	            }
	        }
	        if ($root) {
	            $str .= "</xml>";
	        }
	        return $str;
	    }



	    private function formatQueryParaMap($paraMap, $urlencode = 0)
	    {
	        $buff = "";
	        ksort($paraMap);
	        foreach ($paraMap as $k => $v) {
	            if (null != $v && "null" != $v && "sign" != $k) {
	                if ($urlencode) {
	                    $v = urlencode($v);
	                }
	                $buff .= $k . "=" . $v . "&";
	            }
	        }
	        $reqPar = '';
	        if (strlen($buff) > 0) {
	            $reqPar = substr($buff, 0, strlen($buff) - 1);
	        }
	        return $reqPar;
	    }



	    private function vpost($url, $data)
	    {
	        global $_W, $_GPC;
	        $id = $_W['uniacid'];
	        $curl = curl_init();
	        curl_setopt($curl, CURLOPT_URL, $url);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
	        $key_url = str_replace('app', 'web', getcwd()) . '/' . $id;
	        curl_setopt($curl, CURLOPT_SSLCERT, $key_url . '/apiclient_cert.pem');
	        curl_setopt($curl, CURLOPT_SSLKEY, $key_url . '/apiclient_key.pem');
	        curl_setopt($curl, CURLOPT_CAINFO, $key_url . '/rootca.pem');
	        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	        @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
	        curl_setopt($curl, CURLOPT_POST, 1);
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	        curl_setopt($curl, CURLOPT_HEADER, 0);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	        $tmpInfo = curl_exec($curl);
	        if (curl_errno($curl)) {
	            echo 'Errno' . curl_error($curl);
	            exit;
	        }
	        curl_close($curl);
	        return $tmpInfo;
	    }




	    private function get_award_type($index = 3)
	    {
	        $arr = array( '商城积分',  '现金红包',  '商城余额');
	        if ($index) {
	            return $arr[$index];
	        }
	        return $arr;
	    }



	    //编辑红包
	     public function doWebSendrec_edit()
	    {
	        global $_W, $_GPC;
	        $id = intval($_GPC['id']);
	        $sql = "SELECT rec.* " . "FROM " . tablename('wqtgd_sendrec') . " rec " . "LEFT JOIN " . tablename('mc_mapping_fans') . " fans ON rec.openid=fans.openid " . "LEFT JOIN " . tablename('mc_members') . " mem ON fans.uid=mem.uid " . "WHERE id = :id  ";
	        $data = pdo_fetch($sql, array(':id' => $id));
	        $fhb_send_type = $this->module['config']['fhb_send_type'];
	        if ($fhb_send_type == 'f') {
	            $data['fhb_send_money'] = round($this->module['config']['fhb_send_money'] / 100, 2);
	        } else {
	            $data['fhb_send_money_from'] = $this->module['config']['fhb_send_money_from'];
	            $data['fhb_send_money_to'] = $this->module['config']['fhb_send_money_to'];
	            $random_money = rand(intval($data['fhb_send_money_from']), intval($data['fhb_send_money_to']));
	            $data['fhb_send_money'] = $random_money;
	        }
	        
	        include $this->template('sendrec_add');
	    }



        // 幻灯片 ——陈勇——
		public function doWebAdv(){  
		global $_W,$_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$list = pdo_fetchall("SELECT * FROM " . tablename('wqtgd_adv') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);
	if (checksubmit('submit')) {
		$data = array(
			'weid' => $_W['uniacid'],
			'advname' => $_GPC['advname'],
			'link' => $_GPC['link'],
			'enabled' => intval($_GPC['enabled']),
			'displayorder' => intval($_GPC['displayorder']),
			'thumb'=>$_GPC['thumb']
		);
		if (!empty($id)) {
			pdo_update('wqtgd_adv', $data, array('id' => $id));
		} else {
			pdo_insert('wqtgd_adv', $data);
			$id = pdo_insertid();
		}
		message('更新幻灯片成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
	}
	$adv = pdo_fetch("select * from " . tablename('wqtgd_adv') . " where id=:id and weid=:weid limit 1", array(":id" => $id, ":weid" => $_W['uniacid']));
} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$adv = pdo_fetch("SELECT id FROM " . tablename('wqtgd_adv') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
	if (empty($adv)) {
		message('抱歉，幻灯片不存在或是已经被删除！', $this->createWebUrl('adv', array('op' => 'display')), 'error');
	}
	pdo_delete('wqtgd_adv', array('id' => $id));
	message('幻灯片删除成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
} else {
	message('请求方式不存在');
}
	include $this->template("adv");
		}

    // 幻灯片结束 ——陈勇——

	public function doWebShenhe() {
		//这个操作被定义用来呈现 微站快捷功能导航
		global $_W,$_GPC;
		$deleteUrl = $this->createWebUrl("delete");
		$pindex = max(1, intval($_GPC['page']));;
        $psize = 12;
        
        if ($_GPC['order_name']) {
            $condition .= " and order_name like '%" . $_GPC['order_name'] . "%'";
        }
		$sql = "select * from " . tablename("wqtgd_order")." where status = 1 ".$condition." limit " . ($pindex - 1) * $psize . ',' . $psize;
		$res = pdo_fetchAll($sql);
		//判断订单号是否已经存在领奖记录表，如果已存在则设置订单审核表的领取状态为已领取
		foreach($res as $row){
			$sql = "select order_name from ".tablename("wqtgd_mation")." where order_name = :order_name";
			$arr = array(":order_name"=>$row["order_name"]);
			$result = pdo_fetch($sql,$arr);
			if($result){
				pdo_update("wqtgd_order",array("receive"=>1),array("id"=>$row["id"]));
			}
		}
		$total = pdo_fetchcolumn("select count(*) from ".tablename("wqtgd_order")." where status = 1".$condition);
		$pager = pagination($total, $pindex, $psize);
		include $this->template("shenhe");
	}
	public function doWebDaoru() {
		//这个操作被定义用来呈现 微站快捷功能导航
		global $_GPC;
		if(isset($_GPC["submit"])){
			if(!empty($_FILES["file_cumputer"]["name"])){
				$tmp_file = $_FILES["file_cumputer"]["tmp_name"];
	            $file_types = explode(".", $_FILES["file_cumputer"]["name"]);
	            $file_type = $file_types[count($file_types) - 1];
	            if($file_type == "xls" || $file_type == "xlsx"){
		            $savePath = IA_ROOT . "/addons/wqtgd_tb/template/upFile/";
		            $str = date("Ymdhis");
		            $file_name = $str . "." . $file_type;
		            if (!copy($tmp_file, $savePath . $file_name)) {
		                message("上传失败!");
		            }
		            $orderData['create_time'] = time();
		            $res = $this->read($savePath . $file_name);
		            foreach ($res as $k => $v) {
		            	if($k != 0 && $v[0] != ""){
		            		$orderData["order_name"] = $v[0];
		            		pdo_insert("wqtgd_order", $orderData);
		            	}
		            }
		            $shenheUrl = $this->createWebUrl('shenhe');
		            message("导入成功!",$shenheUrl,"success");	            	
	            }else{
	            	message("请上传Excel类型的文件!");
	            }
			}else{
				$daoruUrl = $this->createWebUrl('daoru');
				message("请上传Excel文件!",$daoruUrl,"error");
			}
		}
		include $this->template("daoru");
	}
	public function doWebDelete() {
		global $_GPC;
		$shenheUrl = $this->createWebUrl("shenhe");
		$id = $_GPC["id"];
		$sql = "update ".tablename("wqtgd_order")." set status = 0 where id = :id";
		$array = array("id"=>$id);
		$res = pdo_query($sql,$array);
		if($res){
			message("删除用户订单数据成功!",$shenheUrl,"success");
		}else{
			message("删除用户订单数据失败!",$shenheUrl,"error");
		}
	}


	public function read($filename, $encode = 'utf-8')
	{
		require_once IA_ROOT . "/framework/library/phpexcel/PHPExcel.php";
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load($filename);
		$indata = $objPHPExcel->getSheet(0)->toArray();
		return $indata;
	}
}
