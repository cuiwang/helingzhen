<?php
defined('IN_IA') or exit('Access Denied');
//require_once '../framework/library/qrcode/phpqrcode.php';
define('ROOT_PATH', str_replace('site.php', '', str_replace('\\', '/', __FILE__)));
require_once "phpqrcode.php";/*引入PHP QR库文件*/
require_once "jssdk.php";
class haoman_dpmModuleSite extends WeModuleSite {





	//非微信打开和限制IP地打开
	public function doMobileother(){
		global $_W,$_GPC;
		$rid = intval($_GPC['id']);
		$type = $_GPC['type'];
		$uniacid = $_W['uniacid'];

		if (empty($rid)) {
			message('抱歉，参数错误！!', '', 'error');//调试代码
		}

		if (empty($from_user)) {
			//	$this->message(array("status" => 2, "msg" => '获取不到您的OpenID,请从新进入活动页面'), "");
		}

		$reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));

		$rule_keyword = pdo_fetch("select * from " . tablename('rule_keyword') . " where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        $key_word = $rule_keyword['content'];
		$send_name = $this->substr_cut($_W['account']['name'],30);
		include $this->template('other');
	}


	public function doMobilegetlbs(){
		global $_GPC, $_W;
		//$id = intval($_GPC['id']);
		$lat1 = $_GPC['lat'];
		$lon1 = $_GPC['lon'];
		$allowlbsip = explode("|",$_GPC['lbsip']);
		$lat2 = $allowlbsip[0];
		$lon2 = $allowlbsip[1];
		$dis = intval($allowlbsip[3]);
		$res = intval($this->getDistance($lat1,$lon1,$lat2,$lon2));
		if ($res <= $dis) {
			$data = array(
				'success' => 1,
				'msg' => '您可以正常参加活动！',
			);
		} else {
			$data = array(
				'success' => 100,
				'msg' => '您不在允许参加的活动范围内！',
			);
		}
		echo json_encode($data);
	}


	//根据经纬度计算距离 其中A($lat1,$lng1)、B($lat2,$lng2)
	public function getDistance($lat1,$lng1,$lat2,$lng2) {
		//地球半径
		$R = 6378137;
		//将角度转为狐度
		$radLat1 = deg2rad($lat1);
		$radLat2 = deg2rad($lat2);
		$radLng1 = deg2rad($lng1);
		$radLng2 = deg2rad($lng2);
		//结果
		$s = acos(cos($radLat1)*cos($radLat2)*cos($radLng1-$radLng2)+sin($radLat1)*sin($radLat2))*$R;
		// $s = 2*asin(sqrt(pow(sin(($radLat1-$radLat2)/2),2)+cos($radLat1)*cos($radLat2)*pow(sin(($radLng1-$radLng2)/2),2)))*$R;
		//精度
		$s = round($s* 10000)/10000;
		return  round($s);

//        $earthRadius = 6367000; //approximate radius of earth in meters
//
//        /*
//          Convert these degrees to radians
//          to work with the formula
//        */
//
//        $lat1 = ($lat1 * pi() ) / 180;
//        $lng1 = ($lng1 * pi() ) / 180;
//
//        $lat2 = ($lat2 * pi() ) / 180;
//        $lng2 = ($lng2 * pi() ) / 180;
//
//        /*
//          Using the
//          Haversine formula
//
//          http://en.wikipedia.org/wiki/Haversine_formula
//
//          calculate the distance
//        */
//
//        $calcLongitude = $lng2 - $lng1;
//        $calcLatitude = $lat2 - $lat1;
//        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);  $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
//        $calculatedDistance = $earthRadius * $stepTwo;
//
//        return round($calculatedDistance);
	}

	public function doMobilelogin(){
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
        $rid = intval($_GPC['id']);
        $reply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
        include $this->template('dpm_login');
	}

	public function doMobilecklogin(){
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $rid = intval($_GPC['rid']);
        $cklogin = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
        if(!empty($_POST['password'])){
            if($_POST['password'] == $cklogin['loginpassword']){
            	$cookieid = '__cookie_haoman_dpmweb_201606186_' . $rid;
				$cookie = array("loginpassword" => $cklogin['loginpassword']);
				setcookie($cookieid, base64_encode(json_encode($cookie)), time() + 3600 * 12);
                $data = array(
					'success' => 1,
					'msg' => '密码正确！',

				);
            }else{
                $data = array(
					'success' => 0,
					'msg' => '密码错误！',

				);
            }
        }else{
            $data = array(
				'success' => 0,
				'msg' => '密码错误2！',

			);
        }

        echo json_encode($data);
    }

    public function doMobiledpm_index(){
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
        $rid = intval($_GPC['rid']);
        $reply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
        //检查登陆状态
        $cookieid = '__cookie_haoman_dpmweb_201606186_' . $rid;
		$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
		if($cookie['loginpassword'] != $reply['loginpassword']){
			message('登陆密码错误或已超时，请重新输入',$this->createMobileUrl("login",array('id'=>$rid)),'error');
		}
        //检查登陆状态
        if(empty($_GPC['themes'])){
        	if($reply['timenum']==1){
        		message('未开启开幕墙，请先后台开启！', '', 'error');
        	}
        	$themes = "start";
        	if(empty($reply['kaimubg'])){
        		$bg = "../addons/haoman_dpm/img4/bg2.jpg";
        	}else{
        		$bg = tomedia($reply['kaimubg']);
        	}
        	$music = tomedia($reply['timeadurl']);
        }else{
        	if($reply['share_type']==1){
        		message('未开启闭幕墙，请先后台开启！', '', 'error');
        	}
        	$themes = "over";
        	if(empty($reply['bimubg'])){
        		$bg = "../addons/haoman_dpm/img4/bg2.jpg";
        	}else{
        		$bg = tomedia($reply['bimubg']);
        	}
        	$music = tomedia($reply['noip_url']);
        }

        load()->model('reply');
        $keywords = reply_single($rid);

        include $this->template('dpm_index5');
	}

	public function doMobiledpm_messages(){
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
        $rid = intval($_GPC['rid']);
        $reply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
        //检查登陆状态
        $cookieid = '__cookie_haoman_dpmweb_201606186_' . $rid;
		$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
		if($cookie['loginpassword'] != $reply['loginpassword']){
			message('登陆密码错误或已超时，请重新输入',$this->createMobileUrl("login",array('id'=>$rid)),'error');
		}
        //检查登陆状态

        load()->model('reply');
        $keywords = reply_single($rid);

        if($reply['isckmessage'] == 0){
        	$isckmessage = 0;
        	$totaldata = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('haoman_dpm_messages') . " WHERE uniacid = :uniacid AND rid = :rid and status = 1", array(':uniacid' => $uniacid,':rid'=>$rid));
        }else{
        	$isckmessage = 1;
        	$totaldata = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('haoman_dpm_messages') . " WHERE uniacid = :uniacid AND rid = :rid", array(':uniacid' => $uniacid,':rid'=>$rid));
        }

        if(empty($reply['registurl'])){
    		$bg = "../addons/haoman_dpm/img4/bg2.jpg";
    	}else{
    		$bg = tomedia($reply['registurl']);
    	}
    	$music = tomedia($reply['headurl']);

        include $this->template('dpm_index6');
	}

	public function doMobiledpm_getmessages(){
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
        $rid = intval($_GPC['rid']);
        $len = intval($_GPC['len']);

        $reply = pdo_fetch("SELECT isckmessage FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));

        

        if($reply['isckmessage'] == 0){
        	$totaldata = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('haoman_dpm_messages') . " WHERE uniacid = :uniacid AND rid = :rid and status = 1", array(':uniacid' => $uniacid,':rid'=>$rid));
        	$limit = $totaldata - $len;
        	$list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_messages') . " WHERE rid = :rid and uniacid = :uniacid and status = 1 ORDER BY id DESC limit {$limit}",array(':rid'=>$rid,':uniacid'=>$uniacid));
        }else{
        	$totaldata = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('haoman_dpm_messages') . " WHERE uniacid = :uniacid AND rid = :rid", array(':uniacid' => $uniacid,':rid'=>$rid));
        	$limit = $totaldata - $len;
        	$list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_messages') . " WHERE rid = :rid and uniacid = :uniacid ORDER BY id DESC limit {$limit}",array(':rid'=>$rid,':uniacid'=>$uniacid));
        }

        $data = array(
	        'ret' => 1,
	        'data' => $list
	    );

	    echo json_encode($data);
    }

    public function doMobiledpm_3dqd(){
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
        $rid = intval($_GPC['rid']);
        $isqdthemes = $_GPC['isqdthemes'];
        $reply = pdo_fetch("SELECT id,rid,uniacid,isqd,loginpassword,isqdthemes,panzi,qjbpic,qdthemes,title,timenum,up_qrcode,3dlogo,3dlogo1,3dlogo2,3dlogo3 FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
        //检查登陆状态
        $cookieid = '__cookie_haoman_dpmweb_201606186_' . $rid;
		$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
		if($cookie['loginpassword'] != $reply['loginpassword']){
			message('登陆密码错误或已超时，请重新输入',$this->createMobileUrl("login",array('id'=>$rid)),'error');
		}
        //检查登陆状态
        if($reply['isqd']==1){
    		message('未开启3d签到墙，请先后台开启！', '', 'error');
    	}

        load()->model('reply');
        $keywords = reply_single($rid);

        $totaldata = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('haoman_dpm_fans') . " WHERE uniacid = :uniacid AND rid = :rid", array(':uniacid' => $uniacid,':rid'=>$rid));

        // if($totaldata<186){
        // 	$limitSize = $totaldata;
        // 	$overLimit = 0;
        // }else{
        // 	$limitSize = $totaldata - 186;
        // 	$overLimit = 1;
        // }

        if($isqdthemes == 'torus'){
        	$reply['isqdthemes']=4;
        }elseif($isqdthemes == 'sphere'){
        	$reply['isqdthemes']=1;
        }elseif($isqdthemes == 'helix'){
        	$reply['isqdthemes']=2;
        }elseif($isqdthemes == 'grid'){
        	$reply['isqdthemes']=3;
        }else{
            $reply['isqdthemes']=0;
        }

        if(empty($reply['panzi'])){
    		$bg = "../addons/haoman_dpm/img4/bg2.jpg";
    	}else{
    		$bg = tomedia($reply['panzi']);
    	}
    	$music = tomedia($reply['qjbpic']);

        if($reply['isqd']==0 || $reply['isqdthemes']==0){

            if(!empty($reply['3dlogo1']) && !empty($reply['3dlogo2']) && !empty($reply['3dlogo3'])){
                $logoList = "#icon ".tomedia($reply['3dlogo1'])."|#icon ".tomedia($reply['3dlogo2'])."|#icon ".tomedia($reply['3dlogo3'])."|";
            }elseif(!empty($reply['3dlogo1']) && !empty($reply['3dlogo2'])){
                $logoList = "#icon ".tomedia($reply['3dlogo1'])."|#icon ".tomedia($reply['3dlogo2'])."|";
            }elseif(!empty($reply['3dlogo1']) && !empty($reply['3dlogo3'])){
                $logoList = "#icon ".tomedia($reply['3dlogo1'])."|#icon ".tomedia($reply['3dlogo3'])."|";
            }elseif(!empty($reply['3dlogo2']) && !empty($reply['3dlogo3'])){
                $logoList = "#icon ".tomedia($reply['3dlogo2'])."|#icon ".tomedia($reply['3dlogo3'])."|";
            }else{
                $logoList = "";
            }
        }

        if($reply['isqd']==0){
            $signwall_show_str = $reply['3dlogo']."|".$logoList."#grid|#helix|#torus|#sphere";
        }else{
            if($reply['isqdthemes']==1){
                $signwall_show_str = "#sphere";
            }elseif($reply['isqdthemes']==2){
                $signwall_show_str = "#helix";
            }elseif($reply['isqdthemes']==3){
                $signwall_show_str = "#grid";
            }elseif($reply['isqdthemes']==4){
                $signwall_show_str = "#torus";
            }else{
                $signwall_show_str = $reply['3dlogo']."|".$logoList;
            }
        }

        


        $list = pdo_fetchall("SELECT id,avatar,nickname FROM " . tablename('haoman_dpm_fans') . " WHERE rid = :rid and uniacid = :uniacid and isbaoming =0 ORDER BY id DESC limit 150 ",array(':rid'=>$rid,':uniacid'=>$uniacid));

        include $this->template('dpm_newindex3');
	}

	public function doMobiledpm_get3dqd(){
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
        $rid = intval($_GPC['rid']);
     //    $lastTotal = intval($_GPC['lastTotal']);
        $max_id = intval($_GPC['max_id']);

     //    $totaldata = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('haoman_dpm_fans') . " WHERE uniacid = :uniacid AND rid = :rid", array(':uniacid' => $uniacid,':rid'=>$rid));

    	// if($totaldata == $lastTotal){
     //    	$data = array(
		   //      'ret' => 0,
		   //      'msg' => "success",
		   //      "model"=> array(
		   //      			"total"=>$totaldata,
		   //      			"record"=>array()
		   //      		)
		   //  );

     //    }else{

     //    	$limit = intval($totaldata - $lastTotal);

    	// 	$list = pdo_fetchall("SELECT id,avatar,nickname FROM " . tablename('haoman_dpm_fans') . " WHERE rid = :rid and uniacid = :uniacid ORDER BY id DESC limit {$limit}",array(':rid'=>$rid,':uniacid'=>$uniacid));

     //    	$data = array(
		   //      'ret' => 0,
		   //      'msg' => "success1".$limit,
		   //      "model"=> array(
		   //      			"total"=>$totaldata,
		   //      			"record"=>$list
		   //      		)
		   //  );

    	// }



        $list = pdo_fetchall("SELECT id,avatar,nickname FROM " . tablename('haoman_dpm_fans') . " WHERE rid = :rid and uniacid = :uniacid and id > {$max_id} ORDER BY id DESC limit 150",array(':rid'=>$rid,':uniacid'=>$uniacid));

            $data = array(
                'ret' => 0,
                'msg' => "success1",
                "max_id"=>$list[0]['id'],
                "record"=>$list
            );

        
        
        
	    echo json_encode($data);
    }


    public function doMobiledpm_choujiang(){
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
        $rid = intval($_GPC['rid']);
        $reply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
        //检查登陆状态
        $cookieid = '__cookie_haoman_dpmweb_201606186_' . $rid;
		$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
		if($cookie['loginpassword'] != $reply['loginpassword']){
			message('登陆密码错误或已超时，请重新输入',$this->createMobileUrl("login",array('id'=>$rid)),'error');
		}
        //检查登陆状态

        if($reply['ischoujiang']==1){
    		message('未开启抽奖功能，请先后台开启！', '', 'error');
    	}

        load()->model('reply');
        $keywords = reply_single($rid);

        $totaldata = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('haoman_dpm_fans') . " WHERE uniacid = :uniacid AND rid = :rid and isbaoming=0", array(':uniacid' => $uniacid,':rid'=>$rid));

        // $fanslist = pdo_fetchall("SELECT avatar,nickname FROM " . tablename('haoman_dpm_fans') . " WHERE rid = :rid and uniacid = :uniacid ORDER BY id DESC limit 8",array(':rid'=>$rid,':uniacid'=>$uniacid));

        $list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_prize') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable ORDER BY sort DESC",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>1));
        $awardslist = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_award') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable and titleid = :titleid ORDER BY id DESC",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>1,':titleid'=>$list[0]['id']));
        $winUserNum = 0;
        foreach ($awardslist as $v) {
        	$winUserNum++;
        }

        if(empty($reply['start_picurl'])){
    		$bg = "../addons/haoman_dpm/img4/bg2.jpg";
    	}else{
    		$bg = tomedia($reply['start_picurl']);
    	}
    	$music = tomedia($reply['backpicurl']);
        
        include $this->template('dpm_index2');
	}

//12.15修改过
	public function doMobilehaishen(){
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $gets = $_GPC['gets'];
        $uniacid = $_W['uniacid'];
        if($gets == 'message'){

		    if($_GPC['isckmessage'] == 0){
		    	$totaldata = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('haoman_dpm_messages') . " WHERE uniacid = :uniacid AND rid = :rid and status = 1", array(':uniacid' => $uniacid,':rid'=>$rid));
		    }else{
		    	$totaldata = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('haoman_dpm_messages') . " WHERE uniacid = :uniacid AND rid = :rid", array(':uniacid' => $uniacid,':rid'=>$rid));
		    }
		    $data = array(
	            'shenyu' => $totaldata,
	            'code' => 99,
	        );

        }elseif($gets == 'fans'){
        	$uniacid = $_W['uniacid'];
	        $fans = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('haoman_dpm_fans') . " WHERE rid = " . $rid . " and uniacid=" . $uniacid . " and isbaoming=0");
	        $fans = ($fans < 0) ? 0 : $fans;
	        $data = array(
	            'shenyu' => $fans,
	            'code' => 99,
	        );
        }else{
        	$data = array(
	            'shenyu' => 0,
	            'code' => 990,
	        );
        }
        
        echo json_encode($data);
    }

    //重置奖品
    public function doMobileresetAwards() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $code = $_GPC['code'];
        $titleid = intval($_GPC['rawardid']);
        
        if($code == "reset"){
            if (pdo_delete('haoman_dpm_award', array('rid' => $rid,'titleid'=>$titleid))) {
                $data = array(
                    'ret' => 1,
                    'msg' => "重置奖品成功"
                );
                echo json_encode($data);
                exit;
            }else{
                $data = array(
                    'ret' => 2,
                    'msg' => "重置奖品失败"
                );
                echo json_encode($data);
                exit;
            }
       
        }else{
            if (pdo_delete('haoman_dpm_award', array('rid' => $rid))) {
                $data = array(
                    'ret' => 1,
                    'msg' => "重置奖品成功"
                );
                echo json_encode($data);
                exit;
            }else{
                $data = array(
                    'ret' => 2,
                    'msg' => "重置奖品失败"
                );
                echo json_encode($data);
                exit;
            }
        }
        

    }




	public function doMobiledpm_getchoujiang(){ 
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
        $rid = intval($_GPC['rid']);
        $winUserNum = intval($_GPC['winUserNum']);
        $lotteryNumSel = intval($_GPC['lotteryNumSel']);
        $iscjnum = intval($_GPC['iscjnum']);
        $awardid = intval($_GPC['awardid']);
        $turntable = 1;

        $result = $this->dpm_getfans($rid,$lotteryNumSel,$iscjnum,$turntable,$awardid);
        // $result = $this->dpm_getfans($rid,1,0,1,10);

		// $list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_fans') . " WHERE rid = :rid and uniacid = :uniacid ORDER BY id DESC limit {$limit}",array(':rid'=>$rid,':uniacid'=>$uniacid));
        $num = intval(count($result,0)+$winUserNum);
    	$data = array(
	        'ret' => 1,
	        'msg' => "success",
	        'num' => $num,
	        "data"=> $result
	    );

        
	    echo json_encode($data);
    }

    public function dpm_getfans($rid,$limit,$iscjnum,$turntable,$awardid){  //所有大屏幕抽奖获取中奖者的方法
    	// $rid是规则ID;
    	// $limit是一次抽出的人数;
    	// $iscjnum控制每个人是否可以同时中多个奖品，0为每个人只能中一个奖品，这个只是指同一个活动；
    	// $turntable活动类型，1为现场抽，2为抢红包
    	// $awardid奖品ID
    	global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
		$fans = pdo_fetchall("SELECT id,avatar,from_user,nickname FROM " . tablename('haoman_dpm_fans') . " WHERE rid = :rid and uniacid = :uniacid and isbaoming=0 ORDER BY id DESC",array(':rid'=>$rid,':uniacid'=>$uniacid));

        $prize = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_prize') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable and id = :id ORDER BY id DESC",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>$turntable,':id'=>$awardid));


		// if($iscjnum == 0){
		// 	$res = pdo_fetchall("SELECT from_user FROM " . tablename('haoman_dpm_award') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable ORDER BY id DESC",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>$turntable));
		// 	if(!empty($res)){
		// 		foreach ($res as $k => $v) {
		// 			$ckres[$k] = $v['from_user'];
		// 		}
		// 		foreach ($fans as $k => $v) {
		// 			if(in_array($v['from_user'],$ckres)){
		// 				unset($fans[$k]);
		// 			}
		// 		}
		// 	}
			
		// }else{


			$res = pdo_fetchall("SELECT from_user FROM " . tablename('haoman_dpm_award') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable and titleid = :titleid ORDER BY id DESC",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>$turntable,':titleid'=>$awardid));
			if(!empty($res)){
				foreach ($res as $k => $v) {
					$ckres[$k] = $v['from_user'];
				}
				foreach ($fans as $k => $v) {
					if(in_array($v['from_user'],$ckres)){
						unset($fans[$k]);

					}
//					else{
//                        $fans_openid[$k] = $v['from_user'];//12.15新增部分
//                    }
				}
			}
		// }


//        //内定部分开始
//        $nd_prize = pdo_fetchall("SELECT prizeid,openid FROM " . tablename('haoman_dpm_draw_default') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable and status=1 and prizeid =:prizeid ORDER BY id DESC",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>$turntable,':prizeid'=>$awardid));
//        if($nd_prize){
//
//            $numx = intval(count($nd_prize,0));
//
//            foreach ($nd_prize as $k => $v) {
//
//                if(in_array($v['openid'],$fans_openid)){
//
//                    $opend[$k]=$v['openid'];
//
//                    }else{
//
//                    $numx-=1;
//                }
//              }
//               if($numx<=$limit){
//                   $limit=$limit-$numx;
//               }else{
//                   $limit=0;
//               }
//            foreach ($fans as $k=>$v){
//
//                if(in_array($v['from_user'],$opend)){
//
//                    $nd_fans[$k]= $v;
//                    unset($fans[$k]);
//                }
//
//            }
//            $new_nd_fans = array_rand($nd_fans,$numx);
//            }
//
//
//
//
//        //内定部分结束

		$num = intval(count($fans,0));
		if($num < $limit){
			$limit = $num;
		}

		$newfans = array_rand($fans,$limit);



		if($limit == 1){

//            if($numx){
//                $result[0] = $nd_fans[$new_nd_fans];
//            }else{
                $result[0] = $fans[$newfans];
         //   }


			$insert = array(
				'rid' => $rid,
				'uniacid' => $_W['uniacid'],
				'turntable' => $turntable,
				'from_user' => $result[0]['from_user'],
				'avatar' => $result[0]['avatar'],
				'nickname' => $result[0]['nickname'],
				'awardname' => $prize['prizename'],
				'awardsimg' => $prize['awardsimg'],
				'prizetype' => $prize['ptype'],
				'credit' => $prize['credit'],
				'prize' => $prize['sort'],
				'titleid' => $awardid,
				'createtime' => time(),
				'status' => 1,
			);
			$temp = pdo_insert('haoman_dpm_award', $insert);
		}else{
			foreach ($newfans as $k => $v) {
				$result[$k] = $fans[$v];
				$insert = array(
					'rid' => $rid,
					'uniacid' => $_W['uniacid'],
					'turntable' => $turntable,
					'from_user' => $result[$k]['from_user'],
					'avatar' => $result[$k]['avatar'],
					'nickname' => $result[$k]['nickname'],
					'awardname' => $prize['prizename'],
					'awardsimg' => $prize['awardsimg'],
					'prizetype' => $prize['ptype'],
					'credit' => $prize['credit'],
					'prize' => $prize['sort'],
					'titleid' => $awardid,
					'createtime' => time(),
					'status' => 1,
				);
				$temp = pdo_insert('haoman_dpm_award', $insert);
			}
		}
		return $result;
    }


    public function doMobiledpm_getCjAwardsList(){
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
        $rid = intval($_GPC['rid']);
        $awardid = intval($_GPC['awardid']);
        $turntable = 1;

        $awardslist = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_award') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable and titleid = :titleid ORDER BY id DESC",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>1,':titleid'=>$awardid));
        $winUserNum = 0;
        foreach ($awardslist as $v) {
        	$winUserNum++;
        }

    	$data = array(
	        'ret' => 1,
	        'msg' => "success",
	        'num' => $winUserNum,
	        "data"=> $awardslist
	    );

        
	    echo json_encode($data);
    }


    public function doMobiledpm_qianghongbao(){
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
        $rid = intval($_GPC['rid']);
        $reply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
        //检查登陆状态
        $cookieid = '__cookie_haoman_dpmweb_201606186_' . $rid;
		$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
		if($cookie['loginpassword'] != $reply['loginpassword']){
			message('登陆密码错误或已超时，请重新输入',$this->createMobileUrl("login",array('id'=>$rid)),'error');
		}
        //检查登陆状态

        if($reply['isqhb']==1){
    		message('未开启抢红包功能，请先后台开启！', '', 'error');
    	}

    	if($reply['hbpici']==0){
    		pdo_update('haoman_dpm_reply', array('hbpici' => 1), array('id' => $reply['id']));
    	}

    	pdo_update('haoman_dpm_reply', array('isqhbshow' => 0), array('id' => $reply['id']));

        load()->model('reply');
        $keywords = reply_single($rid);

        $totaldata = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('haoman_dpm_fans') . " WHERE uniacid = :uniacid AND rid = :rid and isbaoming=0", array(':uniacid' => $uniacid,':rid'=>$rid));


        $list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_prize') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable ORDER BY sort DESC",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>1));
        
        $turntable = 2;
        $hbpici = $reply['hbpici'];
		$awards = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_award') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable AND hbpici = :hbpici ORDER BY id DESC limit 1",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>$turntable,':hbpici'=>$hbpici));
		// print_r($awards);
		// exit;

		if($awards == false){
			$text = '开始抢红包';
			$gameover = 0;
		}else{
			$text = '活动已结束';
			$gameover = 1;
		}

        if(empty($reply['adpic'])){
    		$bg = "../addons/haoman_dpm/img/bg13.jpg";
    	}else{
    		$bg = tomedia($reply['adpic']);
    	}
    	$music = tomedia($reply['adpicurl']);

    	if(empty($reply['end_hour'])){
    		$end_hour = 10;
    	}else{
    		$end_hour = $reply['end_hour'];
    	}

    	if(empty($reply['start_hour'])){
    		$start_hour = 500;
    	}else{
    		$start_hour = $reply['start_hour'];
    	}
        
        include $this->template('dpm_index');
	}



    public function doMobiledpm_vote(){
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $rid = intval($_GPC['rid']);
        $reply = pdo_fetch("SELECT id,rid,uniacid,title,loginpassword,tpbg_url,tpbg_voice,up_qrcode,toupiaotitle FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
        //检查登陆状态
        $cookieid = '__cookie_haoman_dpmweb_201606186_' . $rid;
        $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        if($cookie['loginpassword'] != $reply['loginpassword']){
            message('登陆密码错误或已超时，请重新输入',$this->createMobileUrl("login",array('id'=>$rid)),'error');
        }
        //检查登陆状态

        load()->model('reply');
        $keywords = reply_single($rid);

        $toupiao = pdo_fetchall("select `id`,`pid`,`rid`,`uniacid`,`get_num`,`number`,`name`,`avatar`,`status` from " . tablename('haoman_dpm_toupiao') . " where rid = '" . $rid . "' and uniacid = '" . $uniacid . "' and status=0  order by pid desc");

        foreach ($toupiao as $value) {
            $totalnum += $value['get_num'];
        }

        if(empty($reply['tpbg_url'])){
            $bg = "../addons/haoman_dpm/img4/bg2.jpg";
        }else{
            $bg = tomedia($reply['tpbg_url']);
        }
        $music = tomedia($reply['tpbg_voice']);

        include $this->template('dpm_index9');
    }

    public function doMobiledpm_voteList(){
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $rid = intval($_GPC['rid']);

        $fans = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('haoman_dpm_fans') . " WHERE rid = " . $rid . " and uniacid=" . $uniacid . " and isbaoming = 0");
        $fans = ($fans < 0) ? 0 : $fans;

        $votelist = pdo_fetchall("SELECT `id`,`pid`,`rid`,`uniacid`,`get_num`,`number`,`name`,`avatar`,`status` FROM " . tablename('haoman_dpm_toupiao') . " WHERE rid = :rid and uniacid = :uniacid and status = 0 ORDER BY pid DESC",array(':rid'=>$rid,':uniacid'=>$uniacid));
        $totalnum = 0;
        foreach ($votelist as $value) {
            $totalnum += $value['get_num'];
        }

        $data = array(
            'ret' => 1,
            'shenyu'=>$fans,
            'msg' => "success",
            'num' => $totalnum,
            "datalist"=> $votelist
        );

        
        echo json_encode($data);
    }



	public function doMobilehbstatus(){
		global $_GPC,$_W;
		$rid = intval($_GPC['id']);
		$isqhbshow = intval($_GPC['isqhbshow']);


		$reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_reply')." WHERE rid='".$rid."' " );
		if(empty($reply)){
			$data = array(
				'success' => 100,
				'msg' => "活动信息错误",
			);
			echo json_encode($data);
			exit;
		}


		pdo_update('haoman_dpm_reply', array('isqhbshow' => $isqhbshow), array('id' => $reply['id']));

		$data = array(
			'success' => 1,
			'msg' => "活动状态修改正确",
		);

		echo json_encode($data);

	}


	public function doMobilehbchongzhi(){
		global $_GPC,$_W;
		$rid = intval($_GPC['id']);

		$reply = pdo_fetch( " SELECT id,hbpici FROM ".tablename('haoman_dpm_reply')." WHERE rid='".$rid."' " );
		if(empty($reply)){
			$data = array(
				'success' => 100,
				'msg' => "活动信息错误",
			);
			echo json_encode($data);
			exit;
		}

		pdo_update('haoman_dpm_reply', array('hbpici' => $reply['hbpici']+1), array('id' => $reply['id']));

		$data = array(
			'success' => 1,
			'msg' => "活动重置成功",
		);

		echo json_encode($data);

	}


	public function doMobiledpm_getHbList(){
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
        $rid = intval($_GPC['rid']);
        $len = intval($_GPC['len']);
        $hbpici = intval($_GPC['hbpici']);
        $turntable = 2;
        // $reply = pdo_fetch( " SELECT hbpici FROM ".tablename('haoman_dpm_reply')." WHERE rid='".$rid."' " );

        $totaldata = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('haoman_dpm_award') . " WHERE uniacid = :uniacid AND rid = :rid AND turntable = :turntable AND hbpici = :hbpici", array(':uniacid' => $uniacid,':rid'=>$rid,':turntable'=>$turntable,':hbpici'=>$hbpici));

    	$limit = $totaldata - $len;

        $awardslist = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_award') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable AND hbpici = :hbpici ORDER BY id DESC limit {$limit}",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>$turntable,':hbpici'=>$hbpici));
        $winUserNum = 0;
        foreach ($awardslist as $v) {
        	$winUserNum++;
        }

    	$data = array(
	        'ret' => 1,
	        'msg' => "success",
	        'num' => $winUserNum,
	        "data"=> $awardslist
	    );

        
	    echo json_encode($data);
    }

    public function doMobiledpm_getWinHbList(){
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
        $rid = intval($_GPC['rid']);
        $hbpici = intval($_GPC['hbpici']);
        $turntable = 2;

        $awardslist = pdo_fetchall("SELECT id,avatar,nickname,credit,awardname,prizetype,prize FROM " . tablename('haoman_dpm_award') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable AND hbpici = :hbpici ORDER BY prize DESC,credit DESC limit 30",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>$turntable,':hbpici'=>$hbpici));
        $winUserNum = 0;
        foreach ($awardslist as $v) {
        	$winUserNum++;
        }

    	$data = array(
	        'ret' => 1,
	        'msg' => "success",
	        'num' => $winUserNum,
	        "data"=> $awardslist
	    );

        
	    echo json_encode($data);
    }


    public function doMobiledpm_jiabing(){
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
        $rid = intval($_GPC['rid']);
        $reply = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));
        //检查登陆状态
        $cookieid = '__cookie_haoman_dpmweb_201606186_' . $rid;
		$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
		if($cookie['loginpassword'] != $reply['loginpassword']){
			message('登陆密码错误或已超时，请重新输入',$this->createMobileUrl("login",array('id'=>$rid)),'error');
		}
        //检查登陆状态

        if($reply['isjiabin']==1){
    		message('未开启3d签到墙，请先后台开启！', '', 'error');
    	}

        load()->model('reply');
        $keywords = reply_single($rid);

        $list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_jiabing') . " WHERE rid = :rid and uniacid = :uniacid and status = 0 ORDER BY pid DESC",array(':rid'=>$rid,':uniacid'=>$uniacid));
        
        if(empty($reply['mybb_url'])){
    		$bg = "../addons/haoman_dpm/img4/bg2.jpg";
    	}else{
    		$bg = tomedia($reply['mybb_url']);
    	}
    	$music = tomedia($reply['zhuanfaimg']);
     
        include $this->template('dpm_index4');
	}



	public function doMobileShare() {
		global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$uniacid = $_W['uniacid'];
		$fromuser = authcode(base64_decode($_GPC['from_user']), 'DECODE');

		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') === false) {
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
			exit();
//			message('本页面仅支持微信访问!非微信浏览器禁止浏览!', '', 'error');
		}

		//网页授权借用开始

		// load()->model('account');
		// $_W['account'] = account_fetch($_W['acid']);
		// $cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
		// $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
		// if ($_W['account']['level'] != 4) { //如果是借用的，那么$from_user、$avatar、$nickname不能使用全局的信息，只能使用Cookie的信息，这样才不会出现没有一开始没有关系，后面又关注的人，他之前的数据都没有的情况
		// 	$from_user = $cookie['openid'];
		// 	$avatar = $cookie['avatar'];
		// 	$nickname = $cookie['nickname'];
		// }else{
		// 	$from_user = $_W['fans']['from_user'];
		// 	$avatar = $_W['fans']['tag']['avatar'];
		// 	$nickname = $_W['fans']['nickname'];
		// }

		// $code = $_GPC['code'];
		// $urltype = $_GPC['from_user'];
		// if (empty($from_user) || empty($avatar)) {
		// 	if (!is_array($cookie) || !isset($cookie['avatar']) || !isset($cookie['openid'])) {
		// 		$userinfo = $this->get_UserInfo($rid, $code, $urltype); //如果$from_user或是$avatar其中一个为空，并且cookie里面也没有信息，那么就调用高级权限去获取，这个如果本身是认证服务号的话，不会弹出授权界面，借用的会弹出授权界面
		// 		$nickname = $userinfo['nickname'];
		// 		$avatar = $userinfo['headimgurl'];
		// 		$from_user = $userinfo['openid'];
		// 	} else {
		// 		$avatar = $cookie['avatar'];
		// 		$nickname = $cookie['nickname'];
		// 		$from_user = $cookie['openid'];
		// 	}
		// }

		//网页授权借用结束

		// $reply = pdo_fetch("select sharenumtop,sharenum from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));


		// load()->model('mc');

		// if(empty($from_user)){
		// 	$from_user=$this->get_openid($rid,$_GPC['from_user']);
		// }

		// if ($from_user != $fromuser) {
			//  message($from_user, '', 'error');//调试代码
// 
			// $sharedata = pdo_fetch("select id from " . tablename('haoman_dpm_data') . " where rid = '" . $rid . "' and from_user = '" . $from_user . "' and fromuser = '" . $fromuser . "' limit 1");
			//记录分享
			// $insert = array(
			// 	'rid' => $rid,
			// 	'uniacid' => $_W['uniacid'],
			// 	'from_user' => $from_user,
			// 	'fromuser' => $fromuser,
			// 	'visitorsip' => CLIENT_IP,
			// 	'visitorstime' => TIMESTAMP,
			// 	'viewnum' => 1

			// );

			// $fans = pdo_fetch("SELECT sharenum,last_time FROM " . tablename('haoman_dpm_fans') . " WHERE rid = " . $rid . " and from_user='" . $fromuser . "'");

			// if (empty($sharedata) && ($reply['sharenumtop'] > $fans['sharenum'])){

			// 	//更新当日次数
			// 	$nowtime = mktime(0, 0, 0);

			// 	$share_num = $reply['sharenum'];

			// 	pdo_insert('haoman_dpm_data', $insert);


			// 	if ($fans['last_time'] < $nowtime) {
			// 		//   message($fans['sharenum'], '', 'error');//调试代码
			// 		$fans['sharenum'] = 0;
			// 		pdo_update('haoman_dpm_fans', array('sharenum' => $fans['sharenum'] + $share_num, 'last_time' => $nowtime), array('from_user' => $fromuser,'rid'=>$rid));
			// 	}else{

			// 		pdo_update('haoman_dpm_fans', array('sharenum' => $fans['sharenum'] + $share_num), array('from_user' => $fromuser,'rid'=>$rid));
			// 	}


			// }
			//记录分享
		// }
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: " . $this->createMobileUrl('index', array('id' => $rid)) . "");
		exit();
	}


	

	public function doMobilegetShareImgUrl() {

		global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$id = intval($_GPC['id']);
		$num = intval($_GPC['num']);
		$from_user = $_GPC['from_user'];
		$djtitle = $_GPC['djtitle'];
//		//网页授权借用开始（特殊代码）
//
		load()->model('account');
		$_W['account'] = account_fetch($_W['acid']);

		if ($_W['account']['level'] != 4) {
			$cookieid = '__cookie_haoman_dpm_201610186_' . $rid;
			$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
			$from_user = $cookie['openid'];
			$avatar = $cookie['avatar'];
			$nickname = $cookie['nickname'];

		}else{

			$from_user = $_W['fans']['from_user'];
			$avatar = $_W['fans']['tag']['avatar'];
			$nickname = $_W['fans']['nickname'];
		}
//
//		//网页授权借用结束（特殊代码）

		if (empty($from_user)) {
			$this->message(array("success" => 2, "msg" => '获取不到您的OpenID,请从新进入活动页面'), "");
		}

		$imgName = "haomandpm".$_W['uniacid'].$id;
		$linkUrl = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&m=haoman_dpm&do=hexiao&rid=".$rid."&id=".$id;
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

		$data = array(
			'success' => 1,
			'msg' => $imgUrl,
			'djtitle' => $djtitle,
		);

		echo json_encode($data);
	}


	//实物兑奖
	public function doWebSetstatus() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$rid = intval($_GPC['rid']);
		$status = intval($_GPC['status']);
		$reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		if (empty($id)) {
			message('抱歉，传递的参数错误！', '', 'error');
		}
		$p = array('status' => $status);
		if ($status == 2) {
			$p['consumetime'] = TIMESTAMP;
		}
		if ($status == 3) {
			$p['consumetime'] = '';
			$p['status'] = 1;
		}
		$temp = pdo_update('haoman_dpm_award', $p, array('id' => $id));
		if ($temp == false) {
			message('抱歉，刚才操作数据失败！', '', 'error');
		} else {
			//从奖池减少奖品
			message('状态设置成功！', $this->createWebUrl('awardlist', array('rid' => $_GPC['rid'])), 'success');
		}
	}
	
//	活动管理
	public function doWebManage() {
		global $_GPC, $_W;

		load()->model('reply');
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$sql = "uniacid = :uniacid and `module` = :module";
		$params = array();
		$params[':uniacid'] = $_W['uniacid'];
		$params[':module'] = 'haoman_dpm';

		if (!empty($_GPC['keyword'])) {
			$sql .= ' and `name` LIKE :keyword';
			$params[':keyword'] = "%{$_GPC['keyword']}%";
		}
		$list = reply_search($sql, $params, $pindex, $psize, $total);
		$pager = pagination($total, $pindex, $psize);

		if (!empty($list)) {
			foreach ($list as &$item) {
				$condition = "`rid`={$item['id']}";
				$item['keyword'] = reply_keywords_search($condition);
				$dpm = pdo_fetch("select fansnum, viewnum from " . tablename('haoman_dpm_reply') . " where rid = :rid ", array(':rid' => $item['id']));
				$totaldata = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('haoman_dpm_messages') . " WHERE uniacid = :uniacid AND rid = :rid", array(':uniacid' => $_W['uniacid'],':rid'=>$item['id']));
				$item['fansnum'] = $dpm['fansnum'];
				$item['viewnum'] = $totaldata;
				
			}
		}
		include $this->template('manage');
	}


	//粉丝管理
	public function doWebFanslist() {
		global $_GPC, $_W;
		$rid = $_GPC['rid'];

		$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['nickname'])) {
			$where.=' and nickname=:nickname';
			$params[':nickname'] = $_GPC['nickname'];
		}
		if (!empty($_GPC['mobile'])) {
			$where.=' and mobile=:mobile';
			$params[':mobile'] = $_GPC['mobile'];
		}
        $reply = pdo_fetch("select isbaoming from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));

		$total = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid " . $where . "", $params);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 12;
		$pager = pagination($total, $pindex, $psize);
		$start = ($pindex - 1) * $psize;
		$limit .= " LIMIT {$start},{$psize}";
		$list = pdo_fetchall("select * from " . tablename('haoman_dpm_fans') . " where rid = :rid and uniacid=:uniacid " . $where . " order by id desc " . $limit, $params);
		//中奖情况
		foreach ($list as &$lists) {
			$lists['awardinfo'] = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_award') . "  where rid = :rid and from_user=:from_user", array(':rid' => $rid, ':from_user' => $lists['from_user']));
			$lists['share_num'] = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_data') . "  where rid = :rid and fromuser=:fromuser", array(':rid' => $rid, ':fromuser' => $lists['from_user']));
		}
		//中奖情况
		//一些参数的显示
		$num1 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		$num2 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang>0", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		$num3 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang=0", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		//    $num4 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang=2", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		//一些参数的显示
		include $this->template('fanslist');
	}


   //导出粉丝数据
	public function  doWebDownload()
	{
		global $_GPC,$_W;
		$rid = intval($_GPC['rid']);


		checklogin();
		$list = pdo_fetchall('select * from ' . tablename('haoman_dpm_fans') . ' where uniacid = :uniacid and rid = :rid ORDER BY id ', array(':uniacid' => $_W['uniacid'],':rid'=>$rid));

		$tableheader = array('ID','微信名称','OPENID','姓名','手机号','地址','时间');
		$html = "\xEF\xBB\xBF";


		foreach ($tableheader as $value) {
			$html .= $value . "\t ,";
		}
		$html .= "\n";
		foreach ($list as $value) {
			$html .= $value['id'] . "\t ,";
			$html .= str_replace('"','',$value['nickname']) . "\t ,";
			$html .=  $value['from_user'] . "\t ,";
			$html .=  $value['realname'] . "\t ,";
			$html .=  $value['mobile'] . "\t ,";
			$html .=  $value['address'] . "\t ,";
			$html .=  date('Y-m-d H:i:s', $value['createtime']) . "\n ";



		}


		header("Content-type:text/csv");

		header("Content-Disposition:attachment;filename=粉丝数据.csv");

		$html = mb_convert_encoding($html, 'gb2312', 'UTF-8');

		echo $html;
		exit();
	}

	//导出中奖记录
	public function  doWebDownload2()
	{
		global $_GPC,$_W;
		$rid = intval($_GPC['rid']);

		checklogin();
		$list = pdo_fetchall('select * from ' . tablename('haoman_dpm_award') . ' where uniacid = :uniacid and rid = :rid ORDER BY id ', array(':uniacid' => $_W['uniacid'],':rid'=>$rid));
		$tableheader = array('ID','微信名称','姓名','OPENID','奖品名称','姓名','手机号','地址','中奖时间','状态');
		$html = "\xEF\xBB\xBF";

		foreach ($list as &$row) {

			if($row['status'] == 1){

				$row['status']='未兑奖';

			}else if($row['status'] == 2){

				$row['status']='已兑奖';

			}
			else{
				$row['status']='不知道';
			}

		}
		foreach ($list as &$lists) {
			$lists['realname'] = pdo_fetchcolumn("select realname from " . tablename('haoman_dpm_fans') . " where from_user = :from_user", array(':from_user' => $lists['from_user']));
			$lists['address'] = pdo_fetchcolumn("select address from " . tablename('haoman_dpm_fans') . " where from_user = :from_user", array(':from_user' => $lists['from_user']));
		}
		foreach ($tableheader as $value) {
			$html .= $value . "\t ,";
		}
		$html .= "\n";
		foreach ($list as $value) {
			$html .= $value['id'] . "\t ,";
			$html .= str_replace('"','',$value['nickname']) . "\t ,";
			$html .= $value['realname'] . "\t ,";
			$html .=  $value['from_user'] . "\t ,";
			$html .=  $value['awardname'] . "\t ,";
			$html .=  $value['realname'] . "\t ,";
			$html .=  $value['mobile'] . "\t ,";
			$html .=  $value['address'] . "\t ,";
			$html .=  date('Y-m-d H:i:s', $value['createtime']) . "\t ,";
			$html .=  $value['status'] . "\n ";


		}


		header("Content-type:text/csv");

		header("Content-Disposition:attachment;filename=中奖记录.csv");

		$html = mb_convert_encoding($html, 'gb2312', 'UTF-8');

		echo $html;
		exit();
	}

    //导出提现记录
	public function  doWebDownload3()
	{
		global $_GPC,$_W;
		$rid = intval($_GPC['rid']);

		checklogin();
		$list = pdo_fetchall('select * from ' . tablename('haoman_dpm_cash') . ' where uniacid = :uniacid and rid = :rid ORDER BY id ', array(':uniacid' => $_W['uniacid'],':rid'=>$rid));
		$tableheader = array('ID','微信名称','OPENID','姓名','手机号','提现金额(元)','提现IP','提现时间','状态');
		$html = "\xEF\xBB\xBF";

		foreach ($list as &$row) {

			if($row['status'] == 1){

				$row['status']='同意';

			}else if($row['status'] == 2){

				$row['status']='拒绝';

			}
			else{
				$row['status']='申请中';
			}

		}
		foreach ($list as &$lists) {
			$lists['realname'] = pdo_fetchcolumn("select realname from " . tablename('haoman_dpm_fans') . " where from_user = :from_user", array(':from_user' => $lists['from_user']));
		}
		foreach ($tableheader as $value) {
			$html .= $value . "\t ,";
		}
		$html .= "\n";
		foreach ($list as $value) {
			$html .= $value['id'] . "\t ,";
			$html .= str_replace('"','',$value['nickname']) . "\t ,";
			$html .=  $value['from_user'] . "\t ,";
			$html .=  $value['realname'] . "\t ,";
			$html .=  $value['mobile'] . "\t ,";
			$html .=  $value['awardname']/100 . "\t ,";
			$html .=  $value['awardsimg'] . "\t ,";
			$html .=  date('Y-m-d H:i:s', $value['createtime']) . "\t ,";
			$html .=  $value['status'] . "\n ";


		}


		header("Content-type:text/csv");

		header("Content-Disposition:attachment;filename=提现记录.csv");

		$html = mb_convert_encoding($html, 'gb2312', 'UTF-8');

		echo $html;
		exit();
	}

	public function doWebAxq() {
		global $_GPC, $_W;
		if ($_W['isajax']) {
			$uid = intval($_GPC['uid']);
			$rid = intval($_GPC['rid']);
			//粉丝数据

			$data = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . ' where id = :id and uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':id' => $uid));

            $award = pdo_fetchall("select * from " . tablename('haoman_dpm_award') . " where status = 1 and prizetype = 0 and rid = " . $rid . " and from_user='" . $data['from_user'] . "'");
            $nums =0;
            foreach($award as $k){
                $nums +=$k['credit'];
            }

            $list = pdo_fetchall("select * from " . tablename('haoman_dpm_award') . "  where rid = :rid and uniacid=:uniacid and from_user=:from_user order by id desc ", array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':from_user' => $data['from_user']));

			include $this->template('axq');

            exit();
		}
	}

	public function doWebAxq2() {
		global $_GPC, $_W;
		if ($_W['isajax']) {
			$from_user = $_GPC['uid'];
			$rid = intval($_GPC['rid']);
			//粉丝数据


			$data = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . ' where from_user = :from_user and uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':from_user' => $from_user));

			$list = pdo_fetchall("select * from " . tablename('haoman_dpm_award') . "  where titleid >5 and rid = :rid and uniacid=:uniacid and from_user=:from_user order by id desc ", array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':from_user' => $data['from_user']));
			include $this->template('axq2');
			exit();
		}
	}

	public function doWebhelp() {
		global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$uniacid = $_W['uniacid'];

		include $this->template('help');
	}



	public function doWebAwardlist() {
		global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$uniacid = $_W['uniacid'];

		//所有奖品类别		
		//    $reply = pdo_fetch("select turntable from " . tablename('haoman_dpm_reply') . " where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		$award = pdo_fetchall("select * from " . tablename('haoman_dpm_prize') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
		foreach ($award as $k => $awards) {
			$award[$k]['num'] = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_award') . " where rid = :rid and prizetype='" . $awards['id'] . "'", array(':rid' => $rid));
		}
		//所有奖品类别


		if (empty($rid)) {
			message('抱歉，传递的参数错误！', '', 'error');
		}
		$where = '';
		$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['status'])) {
			$where.=' and a.status=:status';
			$params[':status'] = $_GPC['status'];
		}
		if (!empty($_GPC['nickname'])) {
			$where.=' and a.nickname=:nickname';
			$params[':nickname'] = $_GPC['nickname'];
		}

		$total = pdo_fetchcolumn("select count(a.id) from " . tablename('haoman_dpm_award') . " a where a.rid = :rid and a.uniacid=:uniacid " . $where . "", $params);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 12;
		$pager = pagination($total, $pindex, $psize);
		$start = ($pindex - 1) * $psize;
		$limit .= " LIMIT {$start},{$psize}";
		$list = pdo_fetchall("select a.* from " . tablename('haoman_dpm_award') . " a where a.rid = :rid and a.uniacid=:uniacid  " . $where . " order by a.id desc " . $limit, $params);

		//中奖资料
		foreach ($list as &$lists) {
			$lists['realname'] = pdo_fetchcolumn("select realname from " . tablename('haoman_dpm_fans') . " where from_user = :from_user and rid = :rid ", array(':from_user' => $lists['from_user'],':rid'=>$rid));
			$lists['address'] = pdo_fetchcolumn("select address from " . tablename('haoman_dpm_fans') . " where from_user = :from_user and rid = :rid ", array(':from_user' => $lists['from_user'],':rid'=>$rid));
			$lists['ptype'] = pdo_fetchcolumn("select ptype from " . tablename('haoman_dpm_prize') . " where id = :id", array(':id' => $lists['prizetype']));
		}


		//中奖资料	
		//一些参数的显示
		$num1="";
		$prizedraw = pdo_fetchall("select * from " . tablename('haoman_dpm_prize') . " where rid =:rid and uniacid = :uniacid",array(':rid' => $rid,'uniacid'=>$uniacid));
		foreach($prizedraw as $k){
			$num1+=$k['awardstotal'];
		}
		//     $num0 = pdo_fetchcolumn("select awardpassword from " . tablename('haoman_dpm_reply') . " where rid = :rid", array(':rid' => $rid));
		//     $num1 = pdo_fetchcolumn("select count(id)from " . tablename('haoman_dpm_award') . " where rid = :rid", array(':rid' => $rid));
		$num2 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_award') . " where rid = :rid and status=1", array(':rid' => $rid));
		$num3 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_award') . " where rid = :rid and status=2", array(':rid' => $rid));
		$num4 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_award') . " where rid = :rid and status=0", array(':rid' => $rid));
		//一些参数的显示
		include $this->template('awardlist');
	}
	
	public function doWebCashprize() {
		global $_GPC, $_W;
		$rid = $_GPC['rid'];



		$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['nickname'])) {
			$where.=' and nickname=:nickname';
			$params[':nickname'] = $_GPC['nickname'];
		}
		if (!empty($_GPC['mobile'])) {
			$where.=' and mobile=:mobile';
			$params[':mobile'] = $_GPC['mobile'];
		}

		if ($_GPC['status']!='') {
			$where.=' and status=:status';
			$params[':status'] = $_GPC['status'];
		}



		$total = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_cash') . "  where rid = :rid and uniacid=:uniacid " . $where . "", $params);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 12;
		    $pager = pagination($total, $pindex, $psize);
		$start = ($pindex - 1) * $psize;
		$limit .= " LIMIT {$start},{$psize}";
		$list = pdo_fetchall("select * from " . tablename('haoman_dpm_cash') . " where rid = :rid and uniacid=:uniacid " . $where . " order by id desc " . $limit, $params);

		//中奖情况
		foreach ($list as &$lists) {
			$lists['realname'] = pdo_fetchcolumn("select realname from " . tablename('haoman_dpm_fans') . "  where rid = :rid and from_user=:from_user", array(':rid' => $rid, ':from_user' => $lists['from_user']));
		}



		//中奖情况
		//一些参数的显示
		$num1 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		$num2 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang>0", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		$num3 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang=0", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		


		//    $num4 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang=2", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		//一些参数的显示
		include $this->template('cashprize');
	}

	public function doMobileduijiang() {
		global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$inputval = $_GPC['inputval'];
		$num = $_GPC['num'];

		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') === false) {
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
			exit();
		}

		$from_user = $_W['fans']['from_user'];
		$avatar = $_W['fans']['tag']['avatar'];
		$nickname = $_W['fans']['nickname'];

		load()->model('account');
        $_W['account'] = account_fetch($_W['acid']);
        $cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
		$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        if ($_W['account']['level'] != 4) {
            $from_user = authcode(base64_decode($_GPC['from_user']), 'DECODE');
            $avatar = $cookie['avatar'];
			$nickname = $cookie['nickname'];
        }


		if (empty($from_user)) {
			$this->message(array("success" => 2,'level'=>1, "msg" => '获取不到您的OpenID,请从新进入活动页面'), "");
		}


		//  $fans = pdo_fetch("select id,mobile from " . tablename('haoman_qib_fans') . " where rid = " . $rid . " and from_user=" . $from_user . "");
		$num0 = pdo_fetch("select password from " . tablename('haoman_dpm_reply') . " where rid = :rid", array(':rid' => $rid));
		if($num0['password']==0){
			if($rid){
				$temp = pdo_update('haoman_dpm_award', array('status' => 2,'consumetime' => time()), array('rid' => $rid, 'from_user' => $from_user, 'uniacid' => $_W['uniacid'], 'id'=>$num));
				$data = array(
					'success' => 1,
					'msg' => '兑奖成功！',
				);
			}
		}else{


			if($inputval == $num0['password']){
				$temp = pdo_update('haoman_dpm_award', array('status' => 2,'consumetime' => time()), array('rid' => $rid, 'from_user' => $from_user, 'uniacid' => $_W['uniacid'], 'id'=>$num));
				$data = array(
					'success' => 1,
					'msg' => '兑奖成功！',
				);
			}
			else{
				$data = array(
					'success' => 0,
					'msg' => $num0,
				);
			}

		}

		echo json_encode($data);
	}


    //报名后现场确认
    public function doMobileve_baoming()
    {
        global $_GPC, $_W;


        $uniacid = $_W['uniacid'];
        $display = empty($_GPC['act']) ? 'display' : $_GPC['act'];
        $rid = intval($_GPC['id']);



        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {

            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
            exit();
        }
// 			//网页授权借用开始
//
        load()->model('account');
        $_W['account'] = account_fetch($_W['acid']);
        $cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
        $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        if ($_W['account']['level'] != 4) {
            $from_user = $cookie['openid'];
            $avatar = $cookie['avatar'];
            $nickname = $cookie['nickname'];
        }else{
            $from_user = $_W['fans']['from_user'];
            $avatar = $_W['fans']['tag']['avatar'];
            $nickname = $_W['fans']['nickname'];
        }

        $code = $_GPC['code'];
        $urltype = '';
        if (empty($from_user) || empty($avatar) || empty($nickname)) {
            if (!is_array($cookie) || !isset($cookie['avatar']) || !isset($cookie['openid'])) {
                $userinfo = $this->get_UserInfo($rid, $code, $urltype);
                $nickname = $userinfo['nickname'];
                $avatar = $userinfo['headimgurl'];
                $from_user = $userinfo['openid'];
            } else {
                $avatar = $cookie['avatar'];
                $nickname = $cookie['nickname'];
                $from_user = $cookie['openid'];
            }
        }
//
// 			//网页授权借用结束


        $nows =time();
        $replys = pdo_fetch("select isbaoming,bm_endtime from " . tablename('haoman_dpm_reply') . " where uniacid = :uniacid AND rid = :rid", array(':uniacid' => $uniacid,':rid' => $rid));

        if($replys == false){
            message('抱歉，活动已经结束，下次再来吧！', '', 'error');
        }

        if($display == "display"){


//            if($replys['isbaoming']!=1){
//                message('该活动没有开启报名', '', 'error');
//            }

 			$fans = pdo_fetch("select id,isbaoming from " . tablename('haoman_dpm_fans') . " where uniacid = :uniacid and rid = :rid and from_user = :from_user",array(':uniacid'=>$uniacid,':rid'=>$rid,':from_user'=>$from_user));

 			if($fans == false){
                if($replys['isbaoming']!=1){

                    message('您还没签到！',$this->createMobileUrl('information', array('id' => $rid,'from_user'=>$from_user)),'success');
                }
                else{

                    message('您还没报名！',$this->createMobileUrl('go_baoming',array('id' => $rid,'from_user'=>$from_user)),'error');
                }

 			}elseif ($fans['isbaoming']!=1){
                message('您已经签到过了！',$this->createMobileUrl('index',array('id'=>$rid)),'success');
            }
            if($nows<$replys['bm_endtime']&&$fans['isbaoming']==1){
                message('您好，还没到签到时间，请留意！', '', 'error');
            }

            include $this->template('hexiao');
        }else{

            $fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where uniacid = :uniacid and rid = :rid and from_user = :from_user",array(':uniacid'=>$uniacid,':rid'=>$rid,':from_user'=>$from_user));
            if($fans == false){
                if($replys['isbaoming']!=1){
                    message('您还没签到！',$this->createMobileUrl('information', array('id' => $rid,'from_user'=>$from_user)),'success');
                }
                else{

                    message('您还没报名！',$this->createMobileUrl('go_baoming',array('id' => $rid,'from_user'=>$from_user)),'error');
                }


//                message('您还没报名！',$this->createMobileUrl('go_baoming'),'error');

            }elseif ($fans['isbaoming']!=1){
                message('您已经签到成功！',$this->createMobileUrl('index',array('id'=>$rid)),'error');
            }

            if(intval($_GPC['id']) == intval($fans['rid'])){
                $temp = pdo_update('haoman_dpm_fans', array('isbaoming' => 0), array('id' => $fans['id']));
            }else{
                message('签到失败，请重新签到！',$this->createMobileUrl('hexiao'),'error');
            }

            if ($temp === false) {
                message('签到失败，请联系主办方',$this->createMobileUrl('hexiao',array('id'=>$rid)),'error');
            } else {
                message('恭喜，已经成功签到！',$this->createMobileUrl('index',array('id'=>$rid)),'success');
            }
        }


    }

//  我的奖品
	public function doMobilemybobing() {
		global $_GPC, $_W;
		$rid = intval($_GPC['id']);
		$uniacid = $_W['uniacid'];
		$credit1 = $_W['member']['credit1'];

		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') === false) {

			header("HTTP/1.1 301 Moved Permanently");
			header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
			exit();
		}

		//网页授权借用开始

		load()->model('account');
		$_W['account'] = account_fetch($_W['acid']);
		$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
		$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
		if ($_W['account']['level'] != 4) {
			$from_user = $cookie['openid'];
			$avatar = $cookie['avatar'];
			$nickname = $cookie['nickname'];
		}else{
			$from_user = $_W['fans']['from_user'];
			$avatar = $_W['fans']['tag']['avatar'];
			$nickname = $_W['fans']['nickname'];
		}

		$code = $_GPC['code'];
		$urltype = '';
		if (empty($from_user) || empty($avatar) || empty($nickname)) {
			if (!is_array($cookie) || !isset($cookie['avatar']) || !isset($cookie['openid']) || !isset($cookie['nickname'])) {
				$userinfo = $this->get_UserInfo($rid, $code, $urltype);
				$nickname = $userinfo['nickname'];
				$avatar = $userinfo['headimgurl'];
				$from_user = $userinfo['openid'];
			} else {
				$avatar = $cookie['avatar'];
				$nickname = $cookie['nickname'];
				$from_user = $cookie['openid'];
			}
		}

		//网页授权借用结束

		


		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));


		if (empty($rid)) {
			message('抱歉，参数错误！', '', 'error');//调试代码
		}

		$reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));

		$num = $reply['share_acid'] < 100 ? 100 : $reply['share_acid'];

		$fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");


            $mybobing = pdo_fetchall("select * from " . tablename('haoman_dpm_award') . " where rid = :rid and from_user =:from_user and uniacid = :uniacid ORDER BY id desc",array(':rid'=>$rid,':from_user'=>$from_user,'uniacid'=>$uniacid));
			
        $award = pdo_fetchall("select * from " . tablename('haoman_dpm_award') . " where status = 1 and prizetype = 0 and rid = " . $rid . " and from_user='" . $from_user . "'");
        $nums =0;
        foreach($award as $k){
            $nums +=$k['credit'];
        }

			$cashs = pdo_fetchall("select * from " . tablename('haoman_dpm_cash') . " where rid = :rid and from_user =:from_user and uniacid = :uniacid and status = 0",array(':rid'=>$rid,':from_user'=>$from_user,'uniacid'=>$uniacid));
            $numx = 0;
		if(empty($cashs)){
			$numx = 0;
		}
		foreach($cashs as $k){
			$numx += $k['awardname'];
		}



		if(empty($mybobing)){
				$mybb = 1;
			}


		//分享信息
		$sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $page_from_user));
		$sharetitle = empty($reply['share_title']) ? '一起来咻一咻抽奖吧!' : $reply['share_title'];
		$sharedesc = empty($reply['share_desc']) ? '亲，一起来咻一咻吧，赢大奖哦！！' : str_replace("\r\n", " ", $reply['share_desc']);
		if (!empty($reply['share_imgurl'])) {
			$shareimg = toimage($reply['share_imgurl']);
		} else {
			$shareimg = toimage($reply['picture']);
		}
		$jssdk = new JSSDK();
		$package = $jssdk->GetSignPackage();

		if(empty($reply['mobpicurl'])){
    		$bg = "../addons/haoman_dpm/mobimg/bg.jpg";
    	}else{
    		$bg = tomedia($reply['mobpicurl']);
    	}

		include $this->template('index3');
	}



//  规则
	public function doMobilerules() {
		global $_GPC, $_W;
		$rid = intval($_GPC['id']);
		$uniacid = $_W['uniacid'];

		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') === false) {

			header("HTTP/1.1 301 Moved Permanently");
			header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
			exit();
		}

		//网页授权借用开始

		load()->model('account');
		$_W['account'] = account_fetch($_W['acid']);
		$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
		$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
		if ($_W['account']['level'] != 4) {
			$from_user = $cookie['openid'];
			$avatar = $cookie['avatar'];
			$nickname = $cookie['nickname'];
		}else{
			$from_user = $_W['fans']['from_user'];
			$avatar = $_W['fans']['tag']['avatar'];
			$nickname = $_W['fans']['nickname'];
		}

		$code = $_GPC['code'];
		$urltype = '';
		if (empty($from_user) || empty($avatar) || empty($nickname)) {
			if (!is_array($cookie) || !isset($cookie['avatar']) || !isset($cookie['openid']) || !isset($cookie['nickname'])) {
				$userinfo = $this->get_UserInfo($rid, $code, $urltype);
				$nickname = $userinfo['nickname'];
				$avatar = $userinfo['headimgurl'];
				$from_user = $userinfo['openid'];
			} else {
				$avatar = $cookie['avatar'];
				$nickname = $cookie['nickname'];
				$from_user = $cookie['openid'];
			}
		}

		//网页授权借用结束

		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));


		if (empty($rid)) {
			message('抱歉，参数错误！', '', 'error');//调试代码
		}

		

		$reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));

		if ($reply == false) {
			message('抱歉，活动已经结束，下次再来吧！', '', 'error');
		}

		$prize = pdo_fetchall("select * from " . tablename('haoman_dpm_prize') . " where rid = '" . $rid . "' and uniacid = '" . $uniacid . "' and awardsimg!=''  order by id");

		//分享信息
		$sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $page_from_user));
		$sharetitle = empty($reply['share_title']) ? '一起来咻一咻抽奖吧!' : $reply['share_title'];
		$sharedesc = empty($reply['share_desc']) ? '亲，一起来咻一咻吧，赢大奖哦！！' : str_replace("\r\n", " ", $reply['share_desc']);
		if (!empty($reply['share_imgurl'])) {
			$shareimg = toimage($reply['share_imgurl']);
		} else {
			$shareimg = toimage($reply['picture']);
		}
		$jssdk = new JSSDK();
		$package = $jssdk->GetSignPackage();

		if(empty($reply['mobpicurl'])){
    		$bg = "../addons/haoman_dpm/mobimg/bg2.jpg";
    	}else{
    		$bg = tomedia($reply['mobpicurl']);
    	}

		include $this->template('index5');
	}


	//修改资料
	public function doMobileinformation(){
		global $_GPC,$_W;
		// $this->checkFollow;
		// $this->checkBowser;
		$rid = intval($_GPC['id']);

		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') === false) {

			header("HTTP/1.1 301 Moved Permanently");
			header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
			exit();
		}


        //网页授权借用开始

        load()->model('account');
        $_W['account'] = account_fetch($_W['acid']);
        $cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
        $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        if ($_W['account']['level'] != 4) {
            $from_user = $cookie['openid'];
            $avatar = $cookie['avatar'];
            $nickname = $cookie['nickname'];
        }else{
            $from_user = $_W['fans']['from_user'];
            $avatar = $_W['fans']['tag']['avatar'];
            $nickname = $_W['fans']['nickname'];
        }

        $code = $_GPC['code'];
        $urltype = '';
        if (empty($from_user) || empty($avatar) || empty($nickname)) {
            if (!is_array($cookie) || !isset($cookie['avatar']) || !isset($cookie['openid']) || !isset($cookie['nickname'])) {
                $userinfo = $this->get_UserInfo($rid, $code, $urltype);
                $nickname = $userinfo['nickname'];
                $avatar = $userinfo['headimgurl'];
                $from_user = $userinfo['openid'];
            } else {
                $avatar = $cookie['avatar'];
                $nickname = $cookie['nickname'];
                $from_user = $cookie['openid'];
            }
        }

        //网页授权借用结束

		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));


		$reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_reply')." WHERE rid='".$rid."' " );

		// $fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");
		//分享信息
		$sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $page_from_user));
		$sharetitle = empty($reply['share_title']) ? '一起来参与抽奖吧!' : $reply['share_title'];
		$sharedesc = empty($reply['share_desc']) ? '亲，一起来参与，赢大奖哦！！' : str_replace("\r\n", " ", $reply['share_desc']);
		if (!empty($reply['share_imgurl'])) {
			$shareimg = toimage($reply['share_imgurl']);
		} else {
			$shareimg = toimage($reply['picture']);
		}

		$jssdk = new JSSDK();
		$package = $jssdk->GetSignPackage();

		if(empty($reply['mobpicurl'])){
    		$bg = "../addons/haoman_dpm/mobimg/bg.jpg";
    	}else{
    		$bg = tomedia($reply['mobpicurl']);
    	}

		include $this->template('mob_qd');
	}

    //报名
    public function doMobilego_baoming(){
        global $_GPC,$_W;
        // $this->checkFollow;
        // $this->checkBowser;
        $rid = intval($_GPC['id']);

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {

            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
            exit();
        }


        //网页授权借用开始

        load()->model('account');
        $_W['account'] = account_fetch($_W['acid']);
        $cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
        $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        if ($_W['account']['level'] != 4) {
            $from_user = $cookie['openid'];
            $avatar = $cookie['avatar'];
            $nickname = $cookie['nickname'];
        }else{
            $from_user = $_W['fans']['from_user'];
            $avatar = $_W['fans']['tag']['avatar'];
            $nickname = $_W['fans']['nickname'];
        }

        $code = $_GPC['code'];
        $urltype = '';
        if (empty($from_user) || empty($avatar) || empty($nickname)) {
            if (!is_array($cookie) || !isset($cookie['avatar']) || !isset($cookie['openid']) || !isset($cookie['nickname'])) {
                $userinfo = $this->get_UserInfo($rid, $code, $urltype);
                $nickname = $userinfo['nickname'];
                $avatar = $userinfo['headimgurl'];
                $from_user = $userinfo['openid'];
            } else {
                $avatar = $cookie['avatar'];
                $nickname = $cookie['nickname'];
                $from_user = $cookie['openid'];
            }
        }

        //网页授权借用结束

        $page_from_user = base64_encode(authcode($from_user, 'ENCODE'));


        $reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_reply')." WHERE rid='".$rid."' " );

        $num1 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));

        if($num1>=$reply['bm_pnumber']&&$reply['bm_pnumber']!=0&&$reply['isbaoming']==1){
            message('您好，报名人数已经满了，下次再来吧！!', '', 'error');
            exit();
        }
        // $fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");
        //分享信息
        $sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $page_from_user));
        $sharetitle = empty($reply['share_title']) ? '一起来报名参加活动吧，还能有大奖!' : $reply['share_title'];
        $sharedesc = empty($reply['share_desc']) ? '亲，一起来报名参加活动吧，还能有大奖！！' : str_replace("\r\n", " ", $reply['share_desc']);
        if (!empty($reply['share_imgurl'])) {
            $shareimg = toimage($reply['share_imgurl']);
        } else {
            $shareimg = toimage($reply['picture']);
        }

        $jssdk = new JSSDK();
        $package = $jssdk->GetSignPackage();

        if(empty($reply['mobpicurl'])){
            $bg = "../addons/haoman_dpm/mobimg/bg.jpg";
        }else{
            $bg = tomedia($reply['mobpicurl']);
        }

        include $this->template('mob_bm');
    }

    //签到或者报名提交
    public function doMobileckinfo(){
        global $_GPC,$_W;
        // $this->checkFollow;
        // $this->checkBowser;
        $rid = intval($_GPC['id']);

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {

            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
            exit();
        }

        $from_user = $_W['fans']['from_user'];
        $avatar = $_W['fans']['tag']['avatar'];
        $nickname = $_W['fans']['nickname'];

        load()->model('account');
        $_W['account'] = account_fetch($_W['acid']);
        $cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
        $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        if ($_W['account']['level'] != 4) {
            $from_user = authcode(base64_decode($_GPC['from_user']), 'DECODE');
            $avatar = $cookie['avatar'];
            $nickname = $cookie['nickname'];
        }

        $reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_reply')." WHERE rid='".$rid."' " );

        $fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");
        $realname = trim($_GPC['realname']);
        $mobile = trim($_GPC['mobile']);
        $address = trim($_GPC['address']);
        if(empty($nickname)){
            $nickname = trim($_GPC['nickname']);
        }
        if(empty($avatar)){
            $avatar = $_GPC['avatar'];
        }

        if($reply['ziliao'] ==2 || $reply['ziliao'] ==3){
            if(empty($mobile)){
                $data = array(
                    'success' => 100,
                    'msg' => "请填写手机号码",
                );

                echo json_encode($data);
                exit;
            }
            $chars = "/^((\(\d{2,3}\))|(\d{3}\-))?1(3|5|8|9|7)\d{9}$/";
            $flag = preg_match($chars, $mobile);
            if($flag == false){
                $data = array(
                    'success' => 100,
                    'msg' => "手机号码格式错误",
                );

                echo json_encode($data);
                exit;
            }
        }

        if($reply['isbaoming']==1){
            if($reply['bm_starttime']>time()){
                $data = array(
                    'success' => 100,
                    'msg' => "报名时间还没开始",
                );
                echo json_encode($data);
                exit;
            }
            if($reply['bm_endtime']<time()){
                $data = array(
                    'success' => 100,
                    'msg' => "报名时间已经结束了",
                );
                echo json_encode($data);
                exit;
            }
            $num1 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_fans') . "  where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
            if($num1>=$reply['bm_pnumber']&&$reply['bm_pnumber']!=0){
                message('抱歉，报名人数已经满了，下次再来吧！', '', 'error');
                exit();
            }
            if($reply['isbaoming_pay']!=0&&$reply['isbaoming_paymoney']>0){
                $data = array(
                    'success' => 200,
                    'msg' => "支付报名",
                );
                echo json_encode($data);
                exit;
            }
            $isbaoming = 1;
        }else{
            $isbaoming = 0;
        }

        if(empty($fans)){

            $insert = array(
                'uniacid' => $_W['uniacid'],
                'from_user' => $from_user,
                'avatar' => $avatar,
                'nickname' => $nickname,
                'realname' => $realname,
                'mobile' => $mobile,
                'address' => $address,
                'rid' => $rid,
                'isbaoming' => $isbaoming,
                'createtime' => time(),
            );


            pdo_insert('haoman_dpm_fans',$insert);
            pdo_update('haoman_dpm_reply', array('fansnum' => $reply['fansnum'] + 1, 'viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));

        }else{
            $fans['avatar'] = $avatar;
            $fans['nickname'] = $nickname;
            $fans['realname'] = $realname;
            $fans['mobile'] = $mobile;
            $fans['address'] = $address;
            pdo_update('haoman_dpm_fans',$fans,array('id'=>$fans['id']));
        }



        $data = array(
            'success' => 1,
            'msg' => "签到成功",
        );

        echo json_encode($data);

    }

    //报名支付
    public function doMobileConfirm() {
        global $_GPC, $_W;
        $rid = intval($_GPC['id']);

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {

            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
            exit();
        }

        $from_user = $_W['fans']['from_user'];
        $avatar = $_W['fans']['tag']['avatar'];
        $nickname = $_W['fans']['nickname'];

        load()->model('account');
        $_W['account'] = account_fetch($_W['acid']);
        $cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
        $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        if ($_W['account']['level'] != 4) {
            $from_user = authcode(base64_decode($_GPC['from_user']), 'DECODE');
            $avatar = $cookie['avatar'];
            $nickname = $cookie['nickname'];
        }

        $reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_reply')." WHERE rid='".$rid."' " );

//        $fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");
        $realname = trim($_GPC['realname']);
        $mobile = trim($_GPC['mobile']);
        $address = trim($_GPC['address']);
        if(empty($nickname)){
            $nickname = trim($_GPC['nickname']);
        }
        if(empty($avatar)){
            $avatar = $_GPC['avatar'];
        }

        if($reply['ziliao'] ==2 || $reply['ziliao'] ==3){
            if(empty($mobile)){
                $data = array(
                    'success' => 100,
                    'msg' => "请填写手机号码",
                );

                echo json_encode($data);
                exit;
            }
            $chars = "/^((\(\d{2,3}\))|(\d{3}\-))?1(3|5|8|9|7)\d{9}$/";
            $flag = preg_match($chars, $mobile);
            if($flag == false){
                $data = array(
                    'success' => 100,
                    'msg' => "手机号码格式错误",
                );

                echo json_encode($data);
                exit;
            }
        }

        if($reply['isbaoming_pay']==0||$reply['isbaoming_paymoney']<=0){
            $data = array(
                'success' => 100,
                'msg' => "未开启支付报名!",
            );

            echo json_encode($data);
            exit;
        }

        $payorder = pdo_fetch("select * from " . tablename('haoman_dpm_pay_order') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'and status =2");

            if(empty($payorder)) {
                $result = pdo_insert('haoman_dpm_pay_order', array(
                                    'uniacid' => $_W['uniacid'],
                                    'transid'=>date('YmdHi').random(8, 1),
                                    'from_user' => $from_user,
                                    'avatar' => $avatar,
                                    'nickname' => $nickname,
                                    'from_realname' => $realname,
                                    'mobile' => $mobile,
                                    'pay_addr' => $address,
                                    'pay_total' => $reply['isbaoming_paymoney'],
                                    'pay_ip' => $_W['clientip'],
                                    'rid' => $rid,
                                    'status' => 1,
                                    'createtime' => time(),
                ));
            }else{
                $data = array(
                    'success' => 100,
                    'msg' => "您已经支付报名过了!",
                );

                echo json_encode($data);
                exit;
            }

            if (empty($result)) {
                $data = array(
                    'success' => 100,
                    'msg' => "支付报名失败",
                );
                echo json_encode($data);
                exit;

            }else{
                $orderid = pdo_insertid();



            $data = array(
                'success' => 1,
                'orderid' => $orderid,
                'msg' => "提交报名支付成功",
            );

            echo json_encode($data);
            exit;
            }
    }
    //报名支付确认
    public function doMobilePay(){
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $from_user = $_W['fans']['from_user'];
        $orderid = intval($_GPC['orderid']);
        if(empty($orderid)){
            message("订单号不能为空",'','error');
        }

        $order = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_pay_order') . " WHERE uniacid = :uniacid AND id = :id AND status = :status ", array(':uniacid' => $uniacid,':id' => $orderid,':status' => 1));

        if($order == false){

            message("不存在该笔报名订单！",'','error');

        }else {

            $params['tid'] = $order['transid'];
            $params['user'] = $_W['fans']['from_user'];
            $params['fee'] = $order['pay_total']/100;
            $params['title'] = $order['from_realname'] . "报名费用";
            $params['ordersn'] = $order['transid'];

            include $this->template('bm_pay');
        }
    }
   //支付返回结果
    public function payResult($params) {

        global $_GPC, $_W;
        //一些业务代码
        //根据参数params中的result来判断支付是否成功
        // if ($params['result'] == 'success' && $params['from'] == 'notify') {
        //     //此处会处理一些支付成功的业务代码
        //     //此处再次判断用户支付的金额是否与其生成订单的金额相符，二次验证支付安全
        //     if ($params['fee'] != $order['fee']) {
        //         exit('用户支付的金额与订单金额不符合');
        //     }
        // }

        if ($params['result'] == 'success'&& $params['from'] == 'notify') {
            if($params['type']=="credit"){
                $paytype =1;
            }elseif ($params['type']=="wechat"){
                $paytype =2;
            }
            elseif ($params['type']=="alipay"){
                $paytype =3;
            }
            elseif ($params['type']=="delivery"){
                $paytype =4;
            }
            $update = array();
            $update['status'] = 2;
            $update['paytime'] = TIMESTAMP;
            $transid = $params['tid'];
            $update['orderid'] = $paytype;
            $ress =  $this->modify($transid,$update);

        }


        if (empty($params['result']) || $params['result'] != 'success'&& $params['from'] == 'notify') {
            message('支付失败！', '', 'error');
        }
        //因为支付完成通知有两种方式 notify，return,notify为后台通知,return为前台通知，需要给用户展示提示信息
        //return做为通知是不稳定的，用户很可能直接关闭页面，所以状态变更以notify为准
        //如果消息是用户直接返回（非通知），则提示一个付款成功
        if ($params['from'] == 'return') {
            if ($params['result'] == 'success') {
                $order = pdo_fetch("select * from " . tablename('haoman_dpm_pay_order') . " where transid = :transid",array(':transid'=>$params['tid']));
                $reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where uniacid = :uniacid and rid =:rid order by `id` desc", array(':uniacid' => $_W['uniacid'],':rid'=>$order['rid']));

                include $this->template('result');

            } else {
                message('支付失败！', '', 'error');
            }
        }
    }

    public function modify($transid,$entity){
        global $_GPC,$_W;
//        $transid = intval($transid);

        $parms= array();
        $sql = "SELECT * FROM ".tablename('haoman_dpm_pay_order')." WHERE transid = :transid ";
        // $exits = pdo_fetch("SELECT * FROM " . tablename('haoman_ds_data') . " WHERE transid = :transid", array(':transid' => $transid));

        $parms[':transid'] = $transid;
        $exits = pdo_fetch($sql,$parms);
        if($exits){
            $reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where uniacid = :uniacid and rid =:rid order by `id` desc", array(':uniacid' => $_W['uniacid'],':rid'=>$exits['rid']));

            $update = $entity;
            $ret = pdo_update('haoman_dpm_pay_order', $update, array('transid'=>$transid));
            if($ret){

                $insert = array(
                    'uniacid' => $_W['uniacid'],
                    'from_user' => $exits['from_user'],
                    'avatar' => $exits['avatar'],
                    'nickname' => $exits['nickname'],
                    'realname' => $exits['from_realname'],
                    'mobile' => $exits['mobile'],
                    'address' => $exits['pay_addr'],
                    'rid' => $exits['rid'],
                    'isbaoming' => 1,
                    'createtime' => time(),
                );
                pdo_insert('haoman_dpm_fans',$insert);
                pdo_update('haoman_dpm_reply', array('fansnum' => $reply['fansnum'] + 1, 'viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));


                return true;
            }else{
                return false;
            }
        }
        return false;
    }



	//微信端首页
	public function doMobileIndex() {
		global $_GPC, $_W;
		$rid = intval($_GPC['id']);
		$uniacid = $_W['uniacid'];

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') === false) {

			header("HTTP/1.1 301 Moved Permanently");
			header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
			exit();
		}


		//网页授权借用开始

		load()->model('account');
		$_W['account'] = account_fetch($_W['acid']);
		$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
		$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
		if ($_W['account']['level'] != 4) {
			$from_user = $cookie['openid'];
			$avatar = $cookie['avatar'];
			$nickname = $cookie['nickname'];
		}else{
			$from_user = $_W['fans']['from_user'];
			$avatar = $_W['fans']['tag']['avatar'];
			$nickname = $_W['fans']['nickname'];
		}

		$code = $_GPC['code'];
		$urltype = '';
		if (empty($from_user) || empty($avatar) || empty($nickname)) {
			if (!is_array($cookie) || !isset($cookie['avatar']) || !isset($cookie['openid']) || !isset($cookie['nickname'])) {
				$userinfo = $this->get_UserInfo($rid, $code, $urltype);
				$nickname = $userinfo['nickname'];
				$avatar = $userinfo['headimgurl'];
				$from_user = $userinfo['openid'];
			} else {
				$avatar = $cookie['avatar'];
				$nickname = $cookie['nickname'];
				$from_user = $cookie['openid'];
			}
		}

		//网页授权借用结束

		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));

		

		$reply = pdo_fetch("select id,isallowip,allowip,liucheng,share_url,viewnum,share_title,share_desc,share_imgurl,picture,mobpicurl,isbaoming,is_showjiabin from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));

		if (empty($reply)) {
			message('非法访问，请重新发送消息进入活动页面！');
		}

		$liucheng = explode("|",$reply['liucheng']);
		foreach ($liucheng as $k => $v) {
			$lc[$k]['value'] = $v;
		}

		//检测是否关注
		if (!empty($reply['share_url'])) {
			//查询是否为关注用户
			$fansID = $_W['member']['uid'];
			$follow = pdo_fetchcolumn("select follow from " . tablename('mc_mapping_fans') . " where uid=:uid and uniacid=:uniacid order by `fanid` desc", array(":uid" => $fansID, ":uniacid" => $uniacid));

			if ($follow == 0) {
				header("HTTP/1.1 301 Moved Permanently");
				header("Location: " . $reply['share_url'] . "");
				exit();
			}

		}

		
		//检测是否为空
		$fans = pdo_fetch("select id,zhongjiang,isbaoming from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");
		if ($fans == false) {

            if($reply['isbaoming']==1){
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: " . $this->createMobileUrl('go_baoming', array('id' => $rid,'from_user'=>$page_from_user)) . "");
                exit();
            }else{
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: " . $this->createMobileUrl('information', array('id' => $rid,'from_user'=>$page_from_user)) . "");
                exit();
            }
		} else {

            if($fans['isbaoming']==1){
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: " . $this->createMobileUrl('ve_baoming', array('id' => $rid,'from_user'=>$page_from_user)) . "");
                exit();
            }
			//增加浏览次数
			pdo_update('haoman_dpm_reply', array('viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
		}

        $award = pdo_fetch("select id from " . tablename('haoman_dpm_award') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");
        if (!empty($award) && $fans['zhongjiang'] == 0) {
            pdo_update('haoman_dpm_fans', array('zhongjiang' => 1), array('id' => $fans['id']));
        }


		//分享信息
		$sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $page_from_user));
		$sharetitle = empty($reply['share_title']) ? '一起来咻一咻抽奖吧!' : $reply['share_title'];
		$sharedesc = empty($reply['share_desc']) ? '亲，一起来咻一咻吧，赢大奖哦！！' : str_replace("\r\n", " ", $reply['share_desc']);
		if (!empty($reply['share_imgurl'])) {
			$shareimg = toimage($reply['share_imgurl']);
		} else {
			$shareimg = toimage($reply['picture']);
		}

		if(empty($reply['mobpicurl'])){
    		$bg = "../addons/haoman_dpm/mobimg/bg.jpg";
    	}else{
    		$bg = tomedia($reply['mobpicurl']);
    	}

		$jssdk = new JSSDK();
		$package = $jssdk->GetSignPackage();
		include $this->template('mob_licheng');
	}



		//微信端上墙页面
	public function doMobilemessagesindex() {
		global $_GPC, $_W;
		$rid = intval($_GPC['id']);
		$uniacid = $_W['uniacid'];

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') === false) {

			header("HTTP/1.1 301 Moved Permanently");
			header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
			exit();
		}


		//网页授权借用开始

		load()->model('account');
		$_W['account'] = account_fetch($_W['acid']);
		$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
		$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
		if ($_W['account']['level'] != 4) {
			$from_user = $cookie['openid'];
			$avatar = $cookie['avatar'];
			$nickname = $cookie['nickname'];
		}else{
			$from_user = $_W['fans']['from_user'];
			$avatar = $_W['fans']['tag']['avatar'];
			$nickname = $_W['fans']['nickname'];
		}

		$code = $_GPC['code'];
		$urltype = '';
		if (empty($from_user) || empty($avatar) || empty($nickname)) {
			if (!is_array($cookie) || !isset($cookie['avatar']) || !isset($cookie['openid']) || !isset($cookie['nickname'])) {
				$userinfo = $this->get_UserInfo($rid, $code, $urltype);
				$nickname = $userinfo['nickname'];
				$avatar = $userinfo['headimgurl'];
				$from_user = $userinfo['openid'];
			} else {
				$avatar = $cookie['avatar'];
				$nickname = $cookie['nickname'];
				$from_user = $cookie['openid'];
			}
		}

		//网页授权借用结束

		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));

		

		$reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		if (empty($reply)) {
			message('非法访问，请重新发送消息进入活动页面！');
		}



		//检测是否关注
		if (!empty($reply['share_url'])) {
			//查询是否为关注用户
			$fansID = $_W['member']['uid'];
			$follow = pdo_fetchcolumn("select follow from " . tablename('mc_mapping_fans') . " where uid=:uid and uniacid=:uniacid order by `fanid` desc", array(":uid" => $fansID, ":uniacid" => $uniacid));

			if ($follow == 0) {
				header("HTTP/1.1 301 Moved Permanently");
				header("Location: " . $reply['share_url'] . "");
				exit();
			}

		}

		
		//检测是否为空
		$fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");
		if ($fans == false) {
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: " . $this->createMobileUrl('information', array('id' => $rid,'from_user'=>$page_from_user)) . "");
			exit();
		} else {
			//增加浏览次数
			pdo_update('haoman_dpm_reply', array('viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
		}

		if(!empty($fans['realname'])){
			$nickname = $fans['realname'];
		}


		//分享信息
		$sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $page_from_user));
		$sharetitle = empty($reply['share_title']) ? '一起来咻一咻抽奖吧!' : $reply['share_title'];
		$sharedesc = empty($reply['share_desc']) ? '亲，一起来咻一咻吧，赢大奖哦！！' : str_replace("\r\n", " ", $reply['share_desc']);
		if (!empty($reply['share_imgurl'])) {
			$shareimg = toimage($reply['share_imgurl']);
		} else {
			$shareimg = toimage($reply['picture']);
		}

		if(empty($reply['mobpicurl'])){
    		$bg = "../addons/haoman_dpm/mobimg/bg.jpg";
    	}else{
    		$bg = tomedia($reply['mobpicurl']);
    	}

		$jssdk = new JSSDK();
		$package = $jssdk->GetSignPackage();
		include $this->template('mob_index');
	}


	public function doMobileUploadImage() {
        global $_W;
        load()->func('file');
        if (empty($_FILES['file']['name'])) {
            $result['message'] = '请选择要上传的文件！';
            exit(json_encode($result));
        }

        if ($file = $this->fileUpload($_FILES['file'], 'image')) {
            if ($file['error']) {
                exit('0');
                //exit(json_encode($file));
            }
            $result['url'] = $_W['config']['upload']['attachdir'] . $file['filename'];
            $result['error'] = $file['error'];
            $result['filename'] = $file['filename'];

            $pathname = $result['filename'];
            if (!empty($_W['setting']['remote']['type'])) { // 判断系统是否开启了远程附件
                $remotestatus = file_remote_upload($pathname); //上传图片到远程
                if (is_error($remotestatus)) {
                    $result['msg'] = $remotestatus['message'];
                } 
            }

            exit(json_encode($result));
        }
    }

    private function fileUpload($file, $type) {
        global $_W;
        set_time_limit(0);
        $_W['uploadsetting'] = array();
        $_W['uploadsetting']['images']['folder'] = 'image';
        $_W['uploadsetting']['images']['extentions'] = array('jpg', 'png', 'gif');
        $_W['uploadsetting']['images']['limit'] = 50000;
        $result = array();
        $upload = file_upload($file, 'image');
        if (is_error($upload)) {
            message($upload['message'], '', 'ajax');
        }
        $result['url'] = $upload['url'];
        $result['error'] = 0;
        $result['filename'] = $upload['path'];

        return $result;
    }


    public function doMobilesavemessages(){
		global $_GPC,$_W;
		$rid = intval($_GPC['id']);

		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') === false) {

			header("HTTP/1.1 301 Moved Permanently");
			header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
			exit();
		}

		$from_user = $_W['fans']['from_user'];
		$avatar = $_W['fans']['tag']['avatar'];
		$nickname = $_W['fans']['nickname'];

		load()->model('account');
        $_W['account'] = account_fetch($_W['acid']);
        $cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
		$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        if ($_W['account']['level'] != 4) {
            $from_user = authcode(base64_decode($_GPC['from_user']), 'DECODE');
            $avatar = $cookie['avatar'];
			$nickname = $cookie['nickname'];
        }

        $fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");

		$reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_reply')." WHERE rid='".$rid."' " );

		$message = $_GPC['message'];
		$picture = $_GPC['picture'];




		if(empty($message)){
			$data = array(
				'success' => 100,
				'msg' => "留言不能为空",
			);

			echo json_encode($data);
			exit;
		}

		if($reply['isckmessage']==0){
			$status = 0;
		}else{
			$status = 1;
		}

        if(empty($nickname) || empty($avatar)){
            $nickname = $fans['nickname'];
            $avatar = tomedia($fans['avatar']);
        }

		if(!empty($fans['realname'])){
			$nickname = $fans['realname'];
		}



		$insert = array(
			'uniacid' => $_W['uniacid'],
			'avatar' => $avatar,
			'nickname' => $nickname,
			'from_user' => $from_user,
			'word' => $message,
			'wordimg' => $picture,
			'rid' => $rid,
			'status' => $status,
			'createtime' => time(),
		);
		$temp = pdo_insert('haoman_dpm_messages',$insert);

		if($temp == false){
			$data = array(
				'success' => 100,
				'msg' => "上墙失败，请从新发送",
			);
		}else{
			$data = array(
				'success' => 1,
				'msg' => "发言成功",
			);
		}

		echo json_encode($data);

	}





	//抽奖页面
	public function doMobileqhbIndex() {
		global $_GPC, $_W;
		$rid = intval($_GPC['id']);
		$uniacid = $_W['uniacid'];

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') === false) {

			header("HTTP/1.1 301 Moved Permanently");
			header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
			exit();
		}


		//网页授权借用开始

		load()->model('account');
		$_W['account'] = account_fetch($_W['acid']);
		$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
		$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
		if ($_W['account']['level'] != 4) {
			$from_user = $cookie['openid'];
			$avatar = $cookie['avatar'];
			$nickname = $cookie['nickname'];
		}else{
			$from_user = $_W['fans']['from_user'];
			$avatar = $_W['fans']['tag']['avatar'];
			$nickname = $_W['fans']['nickname'];
		}

		$code = $_GPC['code'];
		$urltype = '';
		if (empty($from_user) || empty($avatar) || empty($nickname)) {
			if (!is_array($cookie) || !isset($cookie['avatar']) || !isset($cookie['openid']) || !isset($cookie['nickname'])) {
				$userinfo = $this->get_UserInfo($rid, $code, $urltype);
				$nickname = $userinfo['nickname'];
				$avatar = $userinfo['headimgurl'];
				$from_user = $userinfo['openid'];
			} else {
				$avatar = $cookie['avatar'];
				$nickname = $cookie['nickname'];
				$from_user = $cookie['openid'];
			}
		}

		//网页授权借用结束

		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));

		

		$reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		if (empty($reply)) {
			message('非法访问，请重新发送消息进入活动页面！');
		}


		//检测是否关注
		if (!empty($reply['share_url'])) {
			//查询是否为关注用户
			$fansID = $_W['member']['uid'];
			$follow = pdo_fetchcolumn("select follow from " . tablename('mc_mapping_fans') . " where uid=:uid and uniacid=:uniacid order by `fanid` desc", array(":uid" => $fansID, ":uniacid" => $uniacid));

			if ($follow == 0) {
				header("HTTP/1.1 301 Moved Permanently");
				header("Location: " . $reply['share_url'] . "");
				exit();
			}

		}

		
		//检测是否为空
		$fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");
		if ($fans == false) {
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: " . $this->createMobileUrl('information', array('id' => $rid,'from_user'=>$page_from_user)) . "");
			exit();
		} else {
			//增加浏览次数
			pdo_update('haoman_dpm_reply', array('viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
		}


		if($reply['most_num_times'] > 0) {
			$Lcount = $reply['most_num_times'] - $fans['todaynum'];
		}  else {
			$Lcount = 99999;
		}



		$Lcount = $Lcount < 0 ? 0 : $Lcount;

		if (empty($fans['todaynum'])) {
			$fans['todaynum'] = 0;
		}


		$addad = pdo_fetchall("select * from " . tablename('haoman_dpm_addad') . " where rid = :rid ", array(':rid' => $rid));
		$num1 = array_rand($addad);
		$addad_img = $addad[$num1][adlogo];
		$addad_url = $addad[$num1][adlink];
		

		//卡券
    	$cardArry = $this->getCardTicket($rid,$from_user);

		$awardlist = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_award') . " WHERE rid = :rid and uniacid = :uniacid ORDER BY createtime DESC limit 15 ",array(':rid'=>$rid,':uniacid'=>$uniacid));
		foreach ($awardlist as &$lists) {
			$lists['nickname'] = pdo_fetchcolumn("select nickname from " . tablename('haoman_dpm_fans') . " where from_user = :from_user and rid = :rid ", array(':from_user' => $lists['from_user'],':rid'=>$rid));
			$lists['avatar'] = pdo_fetchcolumn("select avatar from " . tablename('haoman_dpm_fans') . " where from_user = :from_user and rid = :rid ", array(':from_user' => $lists['from_user'],':rid'=>$rid));
		}


		//分享信息
		$sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $page_from_user));
		$sharetitle = empty($reply['share_title']) ? '一起来咻一咻抽奖吧!' : $reply['share_title'];
		$sharedesc = empty($reply['share_desc']) ? '亲，一起来咻一咻吧，赢大奖哦！！' : str_replace("\r\n", " ", $reply['share_desc']);
		if (!empty($reply['share_imgurl'])) {
			$shareimg = toimage($reply['share_imgurl']);
		} else {
			$shareimg = toimage($reply['picture']);
		}

		$jssdk = new JSSDK();
		$package = $jssdk->GetSignPackage();

		if(empty($reply['mobqhbbg'])){
			if($reply['isqhb']==0){
				$bg = "../addons/haoman_dpm/images/bg.jpg";
			}else{
				$bg = "../addons/haoman_dpm/images/yaoyiyaobg.jpg";
			}
    		
    	}else{
    		$bg = tomedia($reply['mobqhbbg']);
    	}

		include $this->template('index');
	}

	private function sendText($openid,$txt){
		global $_W;
		$acid=pdo_fetchcolumn("SELECT acid FROM ".tablename('account')." WHERE uniacid=:uniacid ",array(':uniacid'=>$_W['uniacid']));
		$acc = WeAccount::create($acid);
		$data = $acc->sendCustomNotice(array('touser'=>$openid,'msgtype'=>'text','text'=>array('content'=>urlencode($txt))));
		return $data;


	}

    //开始咻红包了
	function Get_rand($proArr) {
		$result = '';
		//概率数组的总概率精度
		$proSum = array_sum($proArr);
		//概率数组循环
		foreach ($proArr as $key => $proCur) {
			$randNum = mt_rand(1, $proSum);
			if ($randNum <= $proCur) {
				$result = $key;
				break;
			} else {
				$proSum -= $proCur;
			}
		}
		unset($proArr);
		return $result;
	}

	public function doMobileget_award() {
		global $_GPC, $_W;
		$rid = intval($_GPC['id']);
		$uniacid = $_W['uniacid'];

		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') === false) {

			header("HTTP/1.1 301 Moved Permanently");
			header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
			exit();
		}

		$fansID = $_W['member']['uid'];
		$credit1 = $_W['member']['credit1'];


		$from_user = $_W['fans']['from_user'];
		$avatar = $_W['fans']['tag']['avatar'];
		$nickname = $_W['fans']['nickname'];

		load()->model('account');
        $_W['account'] = account_fetch($_W['acid']);
        $cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
		$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        if ($_W['account']['level'] != 4) {
            $from_user = authcode(base64_decode($_GPC['from_user']), 'DECODE');
            $avatar = $cookie['avatar'];
			$nickname = $cookie['nickname'];
        }


		if (empty($from_user)) {
			$this->message(array("success" => 2,'level'=>1, "msg" => '获取不到您的OpenID,请从新进入活动页面'), "");
		}



		//开始抽奖咯
		$reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		if ($reply == false) {
			$this->message(array("success" => 2,'level'=>1, "msg" => '规则出错！...'), "");
		}

		if ($reply['isqhbshow'] == 0) {
			//活动已经暂停,请稍后...
			$this->message(array("success" => 2,'level'=>1, "msg" => '活动还没开始或是已经结束，请关注大屏幕的提示。'), "");
		}

		// if ($reply['isqhbshow'] == 2) {
		// 	//活动已经暂停,请稍后...
		// 	$this->message(array("success" => 2,'level'=>1, "msg" => '活动已结束，请关注大屏幕的提示。'), "");
		// }


		if (!empty($reply['share_url'])) {
			//判断是否为关注用户
			$fansID = $_W['member']['uid'];
			$follow = pdo_fetchcolumn("select follow from " . tablename('mc_mapping_fans') . " where uid=:uid and uniacid=:uniacid order by `fanid` desc", array(":uid" => $fansID, ":uniacid" => $uniacid));
			if ($follow == 0) {
				$this->message(array("success" => 3,'level'=>1, "msg" => '您还未关注公共账号！'), "");
			}

		}
		//判断是否为关注用户
		$fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = " . $rid . " and from_user='" . $from_user . "'and isbaoming=0");
		if ($fans == false) {
			$this->message(array("success" => 5,'level'=>1, "msg" => '获取不到您的会员信息，请刷新页面重试!'), "");
		}

        if(empty($nickname)){
            $nickname = $fans['nickname'];
        }

        if(empty($avatar)){
            $avatar = tomedia($fans['avatar']);
        }

	
		if ($fans['todaynum'] >= $reply['most_num_times'] && $reply['most_num_times'] > 0) {
			//$this->message('', '超过当日限制次数');
			$this->message(array("success" => 4,'level'=>1, "msg" => '您超过抽奖次数了!'), "");
		}

		//所有奖品
		$gift = pdo_fetchall("select * from " . tablename('haoman_dpm_prize') . " where rid = :rid and uniacid=:uniacid and turntable=2 order by Rand()", array(':rid' => $rid, ':uniacid' => $uniacid));
		
		$rate = 1;
		foreach ($gift as $giftxiao) {
			if ($giftxiao['probalilty'] < 1 && $giftxiao['probalilty'] > 0 && $giftxiao['awardstotal'] - $giftxiao['prizedraw'] >= 1) {
				$temp = explode('.', $giftxiao['probalilty']);
				$temp = pow(10, strlen($temp[1]));
				$rate = $temp < $rate ? $rate : $temp;
			}
		}
		$prize_arr = array();
		$isgift = false;
		foreach ($gift as $row) {
			if ($row['awardstotal'] - $row['prizedraw'] >= 1 and floatval($row['awardspro']) > 0) {
				$item = array(
					'id' => $row['id'],
					'prize' => $row['prizetype'],
					'v' => $row['awardspro'] * $rate,
				);
				$prize_arr[] = $item;
				$isgift = true;
			}
			$zprizepro += $row['awardspro'];
		}

		if ((100 - $zprizepro) > 0) {
			$item = array(
				'id' => 0,
				'prize' => '好可惜！没有中22',
				'v' => (100 - $zprizepro) * $rate,
			);
			$prize_arr[] = $item;
		}

        //点数概率
		$level=array();

		//所有奖品
		if ($isgift) {
			$last_time = strtotime(date("Y-m-d", mktime(0, 0, 0)));

			//开始抽奖咯
			foreach ($prize_arr as $key => $val) {
				$arr[$val['id']] = $val['v'];
			}
			$prizetype = $this->get_rand($arr); //根据概率获取奖项id

            if( $reply['most_money'] > 0){
                $money = pdo_fetchall("select credit from " . tablename('haoman_dpm_award') . " where rid = " . $rid . " and turntable=2 and prizetype =0");
                $most_money ='';
                foreach ($money as $v){
                    $most_money +=$v['credit']/100;
                }
                if($most_money >= $reply['most_money']&&$reply['most_money']!=0){
                    $prizetype = -1;
                    pdo_update('haoman_dpm_fans', array('todaynum' => $fans['todaynum'] + 1, 'last_time' => $last_time), array('id' => $fans['id']));
                    $data = array(
                        'msg' => '好可惜!！没有抽中！!',
                        'level'=>1,
                        'success' => 11,
                    );
                    $this->message($data);
                }
            }

			if ($fans['awardnum'] >= $reply['award_times'] && $reply['award_times'] != 0) {
				$prizetype = -1;
				 pdo_update('haoman_dpm_fans', array('todaynum' => $fans['todaynum'] + 1, 'last_time' => $last_time), array('id' => $fans['id']));
				$data = array(
					'msg' => '好可惜!！没有抽中！!',
					'level'=>1,
					'success' => 11,
				);
				$this->message($data);
			} else {

			

					if ($prizetype > 0) {
						$status = 1;
						$consumetime = '';
						$awardinfo = pdo_fetch("select * from " . tablename('haoman_dpm_prize') . " where  id='" . $prizetype . "'");
						if($awardinfo['ptype'] == 1){
							$prizetype = $_GPC['cardrowid'];
							$awardinfo = pdo_fetch("select * from " . tablename('haoman_dpm_prize') . " where  id='" . $prizetype . "'");
						}

						switch ($awardinfo['ptype']) {
							case 0:
								$credit = (mt_rand($awardinfo['credit'], $awardinfo['credit2']));
								if ($credit < 100) {
									//中奖记录保存
									$insert = array(
										'uniacid' => $uniacid,
										'rid' => $rid,
										'turntable' => 2,
										'from_user' => $from_user,
										'avatar' => $avatar,
										'nickname' => $nickname,
										'mobile' => $fans['mobile'],
										'awardname' => $awardinfo['prizename'],
										'awardsimg' => $awardinfo['awardsimg'],
										'prizetype' => 0,
										'credit' => $credit,
										'hbpici' => $reply['hbpici'],
										'prize' => $prizetype,
										'createtime' => time(),
										'consumetime' => $consumetime,
										'status' => 1,
									);

                                  	$nu = $credit/100;
									$actions = "恭喜您抽中：".$awardinfo['prizename']."，获得红包".$nu."元";
									$temp = pdo_update('haoman_dpm_prize', array('prizedraw' => $awardinfo['prizedraw'] + 1), array('id' => $prizetype));
									if($temp == false){
										$data = array(
											'msg' => '好可惜!！没有抽中!!！',
											'level'=>1,
											'success' => 11,
										);
										$this->message($data);

									}else{
										pdo_insert('haoman_dpm_award', $insert);
										pdo_update('haoman_dpm_fans', array('today_most_times' => $fans['today_most_times'] + 1,'todaynum' => $fans['todaynum'] + 1,'awardnum' => $fans['awardnum'] + 1,'totalnum' => $fans['totalnum'] + $credit, 'zhongjiang' => 1), array('id' => $fans['id']));
										$this->sendText($from_user,$actions);
									}

								} else {
									//中奖记录保存
									$insert = array(
										'uniacid' => $uniacid,
										'rid' => $rid,
										'turntable' => 2,
										'from_user' => $from_user,
										'avatar' => $avatar,
										'nickname' => $nickname,
										'mobile' => $fans['mobile'],
										'awardname' => $awardinfo['prizename'],
										'awardsimg' => $awardinfo['awardsimg'],
										'prizetype' => 0,
										'credit' => $credit,
										'hbpici' => $reply['hbpici'],
										'prize' => $prizetype,
										'createtime' => time(),
										'consumetime' => $consumetime,
										'status' => 2,
									);

									$record['fee'] = $credit / 100; //红包金额；
									$record['openid'] = $from_user;
									$user['nickname'] = $nickname;


									$actions = "恭喜您抽中：".$awardinfo['prizename']."，获得红包".$record['fee']."元";
									//更新提现状态
									$temp = pdo_update('haoman_dpm_prize', array('prizedraw' => $awardinfo['prizedraw'] + 1), array('id' => $prizetype));

									if($temp == false){
										$data = array(
											'msg' => '好可惜!!！没有抽中!！',
											'level'=>1,
											'success' => 11,
										);
										$this->message($data);
									}else{
						        		
						        		$temps = pdo_insert('haoman_dpm_award', $insert);
                                        $awardid = pdo_insertid();
										$tempss = pdo_update('haoman_dpm_fans', array('today_most_times' => $fans['today_most_times'] + 1,'todaynum' => $fans['todaynum'] + 1,'awardnum' => $fans['awardnum'] + 1,'zhongjiang' => 1), array('id' => $fans['id']));
										$sendhongbao = $this->sendhb($record, $user);
										if (is_error($sendhongbao['isok'])) {
											$awardinfo['prizename'] = $awardinfo['prizename'] . "虽然您中了红包，但是我们不真发哦！";
										} else {
											if ($sendhongbao['isok']) {
												$this->sendText($from_user,$actions);
											} else {
												if(!empty($reply['gl_openid'])){
													$actions = "亲爱的管理员，有粉丝红包领取失败！\n原因：".$sendhongbao['error_msg'];
													$this->sendText($reply['gl_openid'],$actions);
												}
                                                pdo_update('haoman_dpm_award', array('status' => 1), array('id' => $awardid));
												$awardinfo['prizename'] = $awardinfo['prizename'] . "红包发送失败,你可以在我的奖品中心申请提现！";
												$data = array(
													'success' => 6,
													'level'=>1,
													  // 'msg'=>$sendhongbao['error_msg'],
													'msg' => '红包发送完毕，掌柜的充值中...',
												);
												$this->message($data);
											}
										}
										
									}
									
								}
								break;

							case 1:

								//中奖记录保存
								$insert = array(
									'uniacid' => $uniacid,
									'rid' => $rid,
									'turntable' => 2,
									'avatar' => $avatar,
									'nickname' => $nickname,
									'mobile' => $fans['mobile'],
									'from_user' => $from_user,
									'awardname' => $awardinfo['prizename'],
									'awardsimg' => $awardinfo['awardsimg'],
									'card_id' => $awardinfo['couponid'],
									'prizetype' => 1,
									'prize' => $prizetype,
									'hbpici' => $reply['hbpici'],
									'createtime' => time(),
									'consumetime' => $consumetime,
									'status' => 2,
								);
							
								$actions = "恭喜您抽中：".$awardinfo['prizename']."，获得卡券一张";
								$temp = pdo_update('haoman_dpm_prize', array('prizedraw' => $awardinfo['prizedraw'] + 1), array('id' => $prizetype));

								if($temp == false){

									$data = array(
										'msg' => '好可惜!!！没有抽中！',
										'level'=>1,
										'success' => 11,
									);
									$this->message($data);

								}else{
									pdo_insert('haoman_dpm_award', $insert);
									pdo_update('haoman_dpm_fans', array('today_most_times' => $fans['today_most_times'] + 1,'todaynum' => $fans['todaynum'] + 1,'awardnum' => $fans['awardnum'] + 1, 'zhongjiang' => 1), array('id' => $fans['id']));
									$this->sendText($from_user,$actions);
									
									
								}
								
								break;

							case 2:
								$djtitle = $_W['uniacid'].sprintf('%d', time());
								//中奖记录保存
								$insert = array(
									'uniacid' => $uniacid,
									'rid' => $rid,
									'turntable' => 2,
									'avatar' => $avatar,
									'nickname' => $nickname,
									'mobile' => $fans['mobile'],
									'from_user' => $from_user,
									'title' => $djtitle,
									'awardname' => $awardinfo['prizename'],
									'awardsimg' => $awardinfo['awardsimg'],
									'jifen' => $awardinfo['jifen'],
									'prizetype' => 2,
									'prize' => $prizetype,
									'hbpici' => $reply['hbpici'],
									'createtime' => time(),
									'consumetime' => $consumetime,
									'status' => 1,
								);

								$actions = "恭喜您抽中：".$awardinfo['prizename'].",您的兑奖码是:".$djtitle;
								$temp = pdo_update('haoman_dpm_prize', array('prizedraw' => $awardinfo['prizedraw'] + 1), array('id' => $prizetype));

								if($temp == false){

									$data = array(
										'msg' => '好可惜!！没有抽中！',
										'level'=>1,
										'success' => 11,
									);
									$this->message($data);

								}else{
									pdo_insert('haoman_dpm_award', $insert);
									pdo_update('haoman_dpm_fans', array('today_most_times' => $fans['today_most_times'] + 1,'todaynum' => $fans['todaynum'] + 1,'awardnum' => $fans['awardnum'] + 1,'zhongjiang' => 1), array('id' => $fans['id']));
									$this->sendText($from_user,$actions);
									
									
								}
								
								break;
							case 3:
								$jifen = (mt_rand($awardinfo['jifen'], $awardinfo['jifen2']));
								//中奖记录保存
								$insert = array(
									'uniacid' => $uniacid,
									'rid' => $rid,
									'turntable' => 2,
									'avatar' => $avatar,
									'nickname' => $nickname,
									'mobile' => $fans['mobile'],
									'from_user' => $from_user,
									'awardname' => $awardinfo['prizename'],
									'awardsimg' => $awardinfo['awardsimg'],
									'jifen' => $jifen,
									'prizetype' => 1,
									'prize' => $prizetype,
									'hbpici' => $reply['hbpici'],
									'createtime' => time(),
									'consumetime' => $consumetime,
									'status' => 2,
								);

								$actions = "恭喜您抽中：".$jifen."积分";

								$temp = pdo_insert('haoman_dpm_award', $insert);

								if($temp == false){

									$data = array(
										'msg' => '好可惜!!！没有抽中！',
										'level'=>1,
										'success' => 11,
									);
									$this->message($data);

								}else{


									$this->sendText($from_user,$actions);
									pdo_update('haoman_dpm_fans', array('today_most_times' => $fans['today_most_times'] + 1,'todaynum' => $fans['todaynum'] + 1,'awardnum' => $fans['awardnum'] + 1, 'zhongjiang' => 1), array('id' => $fans['id']));
									pdo_update('haoman_dpm_prize', array('prizedraw' => $awardinfo['prizedraw'] + 1), array('id' => $prizetype));

									mc_credit_update($fansID, 'credit1', $jifen, array($fansID, '咻一咻活动抽中' . $jifen . '积分'));


								}

								break;

							default :
								pdo_update('haoman_dpm_fans', array('todaynum' => $fans['todaynum'] + 1, 'last_time' => $last_time), array('id' => $fans['id']));

								$data = array(
									'msg' => '好可惜！没有中奖!！',
									'level'=>1,
									'success' => 11,
									'height' => 240,
								);
								$this->message($data);

						}


						if (!empty($awardinfo['awardsimg'])) {
							$awardinfo['awardsimg'] = toimage($awardinfo['awardsimg']);
						}

						if (empty($awardinfo['prizename'])) {
							$awardinfo['prizename'] = "卡券ID设置错误";
						}

						if (!empty($awardinfo['credit'])) {
							$awardinfo['credit'] = $credit;
						}
						if (!empty($awardinfo['jifen'])) {
							$awardinfo['jifen'] = $jifen;
						}

						$data = array(
							'award' => $awardinfo,
							'msg' => '中奖了！',
							'level' => $level,
							'success' => 1,
							'prizetype' => $prizetype,
							'ptype' => $awardinfo['ptype'],
						);


						$this->message($data);
					} else {
						pdo_update('haoman_dpm_fans', array('todaynum' => $fans['todaynum'] + 1, 'last_time' => $last_time), array('id' => $fans['id']));
						$data = array(
							'msg' => '好可惜!！没有抽中！!',
							'level'=>1,
							'success' => 11,
						);
						$this->message($data);

					}
			}
		} else {
			$last_time = strtotime(date("Y-m-d", mktime(0, 0, 0)));
			pdo_update('haoman_dpm_fans', array('today_most_times' => $fans['today_most_times'] + 1,'todaynum' => $fans['todaynum'] + 1, 'last_time' => $last_time), array('id' => $fans['id']));
			$data = array(
				'msg' => '奖品被抽完了，下次赶早哦！',
				'level'=>1,
				'success' => 11,
			);
		}


		$this->message($data);
	}

	
	
	//json
	public function message($_data = '', $_msg = '') {
		if (!empty($_data['succes']) && $_data['success'] != 2) {
			//$this->setfans();
		}
		if (empty($_data)) {
			$_data = array(
				'name' => "谢谢参与",
				'success' => 1,
			);
		}
		if (!empty($_msg)) {
			//$_data['error']='invalid';
			$_data['msg'] = $_msg;
		}
		die(json_encode($_data));
	}


	    //消息管理
	public function doWebMessagelist() {
		global $_GPC, $_W;
		// checklogin();
		$uniacid = $_W['uniacid'];
		load()->model('reply');
		load()->func('tpl');
		$_GPC['do'] = 'messageslist';
		$rid = $_GPC['rid'];


		$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);

		$total = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_messages') . "  where rid = :rid and uniacid=:uniacid " . $where . "", $params);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 30;
		$pager = pagination($total, $pindex, $psize);
		$start = ($pindex - 1) * $psize;
		$limit .= " LIMIT {$start},{$psize}";
		$list = pdo_fetchall("select * from " . tablename('haoman_dpm_messages') . " where rid = :rid and uniacid=:uniacid " . $where . " order by id desc " . $limit, $params);

		include $this->template('messagelist');
	}

	//审核消息
	public function doWebcklistmessage() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$rule = pdo_fetch("select id from " . tablename('haoman_dpm_messages') . " where id = :id ", array(':id' => $id));
		if (empty($rule)) {
			message('抱歉，参数错误！');
		}

		if (pdo_update('haoman_dpm_messages', array('status' => 1), array('id' => $id))) {
			message('审核成功！', referer(), 'success');
		}

	}

    //删除消息
	public function doWebDeletemessage() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$rule = pdo_fetch("select id from " . tablename('haoman_dpm_messages') . " where id = :id ", array(':id' => $id));
		if (empty($rule)) {
			message('抱歉，参数错误！');
		}
		if (pdo_delete('haoman_dpm_messages', array('id' => $id))) {
			message('删除成功！', referer(), 'success');
		}

	}

	//批量审核消息
	public function doWebAllmessages() {
		global $_GPC, $_W;
		foreach ($_GPC['idArr'] as $k=>$rid) {
			$rid = intval($rid);
			if ($rid == 0 ||$rid ==1)
				continue;
			$rule = pdo_fetch("select id from " . tablename('haoman_dpm_messages') . " where id = :id ", array(':id' => $rid));
			if (empty($rule)) {
				message('抱歉，要审核的消息不存在或是已经被删除！', '', 'error');
			}
			pdo_update('haoman_dpm_messages', array('status' => 1), array('id' => $rid));
		}

		$data = array(
				'flag' => 1,
				'msg' => "批量审核成功",
			);

		echo json_encode($data);
		// message('批量审核成功！', referer(), 'success');
	}

   
    //广告管理
	public function doWebAdmanage() {
		global $_GPC, $_W;

		checklogin();
		$uniacid = $_W['uniacid'];
		load()->model('reply');
		load()->func('tpl');
		$sql = "uniacid = :uniacid and `module` = :module";
		$params = array();
		$params[':uniacid'] = $_W['uniacid'];
		$params[':module'] = 'haoman_dpm';

		$list = reply_search($sql, $params);
		foreach($list as $lists){
			$rid= $lists['id'];
		}





		$addcard = pdo_fetchall("select * from " . tablename('haoman_dpm_addad') . " order by `id` desc");


//
		$now = time();
		$addcard1 = array(
			"getstarttime" => $now,
			"getendtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
		);



		$addad = pdo_fetchall("select * from " . tablename('haoman_dpm_addad') . "where uniacid= :uniacid order by `id` desc",array(':uniacid'=>$uniacid));

		include $this->template('admanage');
	}
    //添加广告
	public function doWebNewad() {
		global $_GPC, $_W;
		// $rid = intval($_GPC['rid']);
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

		load()->model('reply');
		load()->func('tpl');
		$sql = "uniacid = :uniacid and `module` = :module";
		$params = array();
		$params[':uniacid'] = $_W['uniacid'];
		$params[':module'] = 'haoman_dpm';

		$rowlist = reply_search($sql, $params);

		// message($rid);

		if($operation == 'updataad'){

			$id = $_GPC['listid'];

			// message($_GPC['cardnum']);
			$keywords = reply_single($_GPC['rulename']);

			$updata = array(
				'rid' => $_GPC['rulename'],
				'uniacid' => $_W['uniacid'],
				'rulename' => $keywords['name'],
				'adlogo' => $_GPC['adlogo'],
				'adtitle' => $_GPC['adtitle'],
				'addetails' => $_GPC['addetails'],
				'adlink' => $_GPC['adlink'],
				'createtime' =>time(),
			);


			$temp =  pdo_update('haoman_dpm_addad',$updata,array('id'=>$id));

			message("修改广告成功",$this->createWebUrl('admanage'),"success");


		}elseif($operation == 'addad'){

			// message($_GPC['cardname']);

			$keywords = reply_single($_GPC['rulename']);

			$updata = array(
				'rid' => $_GPC['rulename'],
				'uniacid' => $_W['uniacid'],
				'rulename' => $keywords['name'],
				'adlogo' => $_GPC['adlogo'],
				'adtitle' => $_GPC['adtitle'],
				'addetails' => $_GPC['addetails'],
				'adlink' => $_GPC['adlink'],
				'createtime' =>time(),
			);


			// message($keywords['name']);

			$temp = pdo_insert('haoman_dpm_addad', $updata);

			message("添加广告成功",$this->createWebUrl('admanage'),"success");

		}elseif($operation == 'up'){

			$uid = intval($_GPC['uid']);
			$list = pdo_fetch("select * from " . tablename('haoman_dpm_addad') . "  where id=:uid ", array(':uid' => $uid));

			include $this->template('updataad');

		}else{

			$now = time();
			$addcard1 = array(
				"getstarttime" => $now,
				"getendtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
			);

			include $this->template('newad');

		}

	}
    //删除广告
	public function doWebDelete9() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$rule = pdo_fetch("select id from " . tablename('haoman_dpm_addad') . " where id = :id ", array(':id' => $id));
		if (empty($rule)) {
			message('抱歉，参数错误！');
		}
		if (pdo_delete('haoman_dpm_addad', array('id' => $id))) {
			message('删除成功！', referer(), 'success');
		}

	}
//批量删除广告
	public function doWebDeleteAllad() {
		global $_GPC, $_W;
		foreach ($_GPC['idArr'] as $k=>$rid) {
			$rid = intval($rid);
			if ($rid == 0 ||$rid ==1)
				continue;
			$rule = pdo_fetch("select id from " . tablename('haoman_dpm_addad') . " where id = :id ", array(':id' => $rid));
			if (empty($rule)) {
				message('抱歉，要修改的规则不存在或是已经被删除！', '', 'error');
			}
			if (pdo_delete('haoman_dpm_addad', array('id' => $rid))) {

			}
		}
		message('删除成功！', referer(), 'success');
	}



	//删除奖品
	public function doWebDeleteAwards() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$rule = pdo_fetch("select id from " . tablename('haoman_dpm_award') . " where id = :id ", array(':id' => $id));
		if (empty($rule)) {
			message('抱歉，参数错误！');
		}
		if (pdo_delete('haoman_dpm_award', array('id' => $id))) {
			message('删除成功！', referer(), 'success');
		}

	}


   //	提现申请
	public function doMobileapplication() {
		global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$uniacid = $_W['uniacid'];
		$content = intval($_GPC['content']*100);

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {

            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
            exit();
        }

		//网页授权借用开始（特殊代码）

		$from_user = $_W['fans']['from_user'];
        $avatar = $_W['fans']['tag']['avatar'];
        $nickname = $_W['fans']['nickname'];

        load()->model('account');
        $_W['account'] = account_fetch($_W['acid']);
        $cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
        $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        if ($_W['account']['level'] != 4) {
            $from_user = authcode(base64_decode($_GPC['from_user']), 'DECODE');
            $avatar = $cookie['avatar'];
            $nickname = $cookie['nickname'];
        }

		//网页授权借用结束（特殊代码）
		if (empty($from_user)) {
			$this->message(array("success" => 0, "msg" => '获取不到您的OpenID,请从新进入活动页面'), "");
		}

		

		$reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));

		$num = $reply['share_acid'];

			$num = $num < 100 ? 100 : $num;

		$num2 = $reply['tx_most'];

		$num2 = $num2 < 500 ? 500 : $num2;

		$fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = " . $rid . " and from_user='" . $from_user . "'");

		$award = pdo_fetchall("select * from " . tablename('haoman_dpm_award') . " where status = 1 and prizetype = 0 and rid = " . $rid . " and from_user='" . $from_user . "'");
		$nums =0;
		foreach($award as $k){
			$nums +=$k['credit'];
		}
		if($nums<$num){
			$data = array(
				'success' => 0,
				'msg' => '账户金额未达到提现标准！',
			);
			exit();
		}
		if ($fans == false) {
			$data = array(
				'success' => 0,
				'msg' => '保存数据错误！',
			);
		}
		else {
			if (intval($nums) >= intval($num2)) {
				// if (intval($fans['totalnum']) > $num && intval($fans['totalnum']) == $content && intval($fans['totalnum']) == intval($nums)) {

					$insert = array(
						'uniacid' => $uniacid,
						'rid' => $rid,
						'from_user' => $from_user,
						'avatar' => $avatar,
						'nickname' => $nickname,
						'mobile' => $fans['mobile'],
						'fansID' => 0,
						'awardname' => intval($nums),
						'prizetype' => 0,
						'awardsimg' => CLIENT_IP,
						'credit' => 0,
						'prize' => 0,
						'createtime' => time(),
						'consumetime' => 0,
						'status' => 0,
					);
					$temps = pdo_update('haoman_dpm_fans', array('totalnum' => $fans['totalnum'] - $content, 'sharetime' => $fans['sharetime'] + 1), array('rid' => $rid, 'from_user' => $from_user, 'uniacid' => $_W['uniacid']));

					$tempss = pdo_update('haoman_dpm_award', array('status' => 2), array('rid' => $rid, 'from_user' => $from_user, 'uniacid' => $_W['uniacid'], 'prizetype' => 0));

					$temp = pdo_insert('haoman_dpm_cash', $insert);
					$data = array(
						'success' => 1,
						'msg' => '提现申请成功！',
					);
				// } else {
				// 	$data = array(
				// 		'success' => 0,
				// 		'msg' => '提现申请失败！！',
				// 	);
				// }
			} else {

			if ($reply['xf_condition'] == 0) {

				// if (intval($fans['totalnum']) > 0 && intval($fans['totalnum']) == $content && intval($fans['totalnum']) == intval($nums)) {

					$insert = array(
						'uniacid' => $uniacid,
						'rid' => $rid,
						'from_user' => $from_user,
						'avatar' => $avatar,
						'nickname' => $nickname,
						'mobile' => $fans['mobile'],
						'fansID' => 0,
						'awardname' => intval($nums),
						'prizetype' => 0,
						'awardsimg' => CLIENT_IP,
						'credit' => 0,
						'prize' => 0,
						'createtime' => time(),
						'consumetime' => 0,
						'status' => 0,
					);
					$temps = pdo_update('haoman_dpm_fans', array('totalnum' => $fans['totalnum'] - $content, 'sharetime' => $fans['sharetime'] + 1), array('rid' => $rid, 'from_user' => $from_user, 'uniacid' => $_W['uniacid']));

					$tempss = pdo_update('haoman_dpm_award', array('status' => 2), array('rid' => $rid, 'from_user' => $from_user, 'uniacid' => $_W['uniacid'], 'prizetype' => 0));

					$temp = pdo_insert('haoman_dpm_cash', $insert);
					$data = array(
						'success' => 1,
						'msg' => '提现申请成功！',
					);
				// } else {
				// 	$data = array(
				// 		'success' => 0,
				// 		'msg' => '提现申请失败！',
				// 	);
				// }
			} elseif ($reply['xf_condition'] == 1) {

				// if (intval($fans['totalnum']) > $num && intval($fans['totalnum']) == $content && intval($fans['totalnum']) == intval($nums)) {

					$insert = array(
						'uniacid' => $uniacid,
						'rid' => $rid,
						'from_user' => $from_user,
						'avatar' => $avatar,
						'nickname' => $nickname,
						'mobile' => $fans['mobile'],
						'fansID' => 0,
						'awardname' => intval($nums),
						'prizetype' => 0,
						'credit' => 0,
						'awardsimg' => CLIENT_IP,
						'prize' => 0,
						'createtime' => time(),
						'consumetime' => 0,
						'status' => 1,
					);
					$credit = intval($nums);
					$record['fee'] = $credit / 100; //红包金额；
					$record['openid'] = $from_user;
					$user['nickname'] = $nickname;
					$sendhongbao = $this->sendhb($record, $user);
					if ($sendhongbao['isok']) {
						//更新提现状态
						$temp = pdo_insert('haoman_dpm_cash', $insert);
						$temps = pdo_update('haoman_dpm_fans', array('totalnum' => $fans['totalnum'] - $content, 'sharetime' => $fans['sharetime'] + 1), array('rid' => $rid, 'from_user' => $from_user, 'uniacid' => $_W['uniacid']));
						$tempss = pdo_update('haoman_dpm_award', array('status' => 2), array('rid' => $rid, 'from_user' => $from_user, 'uniacid' => $_W['uniacid'], 'prizetype' => 0));

						if ($temp == false) {
							$data = array(
								'success' => 0,
								'msg' => '提现申请失败！3',
							);
						} else {
							$data = array(
								'success' => 1,
								'msg' => '提现申请成功！',
							);
						}

						$hbstatus = 2;

					} else {

						if(!empty($reply['gl_openid'])){
							$actions = "亲爱的管理员，有粉丝提现失败！\n原因：".$sendhongbao['error_msg'];
							$this->sendText($reply['gl_openid'],$actions);
						}

						$data = array(
							'success' => 0,
//                            'msg' => $sendhongbao['error_msg'],
							'msg' => '红包已发完，掌柜正在充值...',
						);

						$hbstatus = 21;
					}


				// } else {
				// 	$data = array(
				// 		'success' => 0,
				// 		'msg' => '提现申请失败！',
				// 	);
				// }
			}
		}
		}

		echo json_encode($data);
	}
   //后台提现审核
	public function doWebSetstatuss() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$rid = intval($_GPC['rid']);
		$status = intval($_GPC['status']);
		$credit = $_GPC['awardname'];

		$from_user =$_GPC['from_user'];
		//$nickname =$_GPC['nickname'];

		$reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		$cash = pdo_fetch("select * from " . tablename('haoman_dpm_cash') . " where rid = :rid and id = :id and from_user = :from_user ", array(':rid' => $rid,':id'=>$id,':from_user'=>$from_user));



		if (empty($id)) {
			message('抱歉，传递的参数错误！', '', 'error');
		}
		$p = array('status' => $status);
		if ($status == 1&&$cash['awardname']==$credit) {
			$record['fee'] = $cash['awardname']/100; //红包金额；
			$record['openid'] = $from_user;
			$user['nickname'] = $cash['nickname'];
			$sendhongbao = $this->sendhb($record, $user);
			if ($sendhongbao['isok']) {
				//更新提现状态

				$temp = pdo_update('haoman_dpm_cash', $p, array('id' => $id));

				if ($temp == false) {
					message('抱歉，刚才操作数据失败！', '', 'error');
				}else{
					message('操作成功！', $this->createWebUrl('cashprize', array('rid' => $_GPC['rid'])), 'success');
				}

				$hbstatus = 2;

			} else {

				message($sendhongbao['error_msg'], '', 'error');

				$hbstatus = 21;
			}
		}
		elseif($status == 2){
			$temp = pdo_update('haoman_dpm_cash', $p, array('id' => $id));
			message('成功拒绝！', $this->createWebUrl('cashprize', array('rid' => $_GPC['rid'])), 'success');

		}
		else{
			message('非法操作', '', 'error');
		}

	}

//嘉宾列表
	public function doWebjiabin(){
		global $_W  ,$_GPC;
		checklogin();
		$uniacid = $_W['uniacid'];
		$rid = $_GPC['rid'];
		$_GPC['do']='code';
		load()->model('reply');
		load()->func('tpl');
		$sql = "uniacid = :uniacid and `module` = :module";
		$params = array();
		$params[':uniacid'] = $_W['uniacid'];
		$params[':module'] = 'haoman_dpm';

		$rowlist = reply_search($sql, $params);
		foreach ($rowlist as $k => $v) {
			$rowlist[$k]['awardstotal'] = pdo_fetchcolumn('select count(*) from ' . tablename('haoman_dpm_jiabing') . 'where uniacid = :uniacid and rid=:rid', array(':uniacid' => $_W['uniacid'],':rid' => $v['id']));
			if(empty($rowlist[$k]['awardstotal'])){
				$rowlist[$k]['awardstotal'] = 0;
			}
		}
		include $this->template('jiabinlist');
	}


	//查看详细嘉宾
	public function doWebjiabinshow(){
		global $_W  ,$_GPC;
		// checklogin();
		$rid = intval($_GPC['rid']);
		load()->model('reply');


		$t = time();
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$sql = 'select * from ' . tablename('haoman_dpm_jiabing') . 'where uniacid = :uniacid and rid = :rid order by `pid` desc LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
		$prarm = array(':uniacid' => $_W['uniacid'] ,':rid' => $rid);
		$list = pdo_fetchall($sql, $prarm);
		$count = pdo_fetchcolumn('select count(*) from ' . tablename('haoman_dpm_jiabing') . 'where uniacid = :uniacid and rid = :rid', $prarm);
		$pager = pagination($count, $pindex, $psize);

		foreach ($list as $k => $v) {
			$keywords = reply_single($v['rid']);
			$list[$k]['rulename'] = $keywords['name'];
		}

		load()->func('tpl');
		include $this->template('jiabinshow');
	}


	//添加和编辑嘉宾
	public function doWebNewjiabin() {
		global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		load()->model('reply');
		load()->func('tpl');
		$sql = "uniacid = :uniacid and `module` = :module";
		$params = array();
		$params[':uniacid'] = $_W['uniacid'];
		$params[':module'] = 'haoman_dpm';

		$rowlist = reply_search($sql, $params);

		// message($rid);

		if($operation == 'updataad'){

			$id = $_GPC['listid'];

			// message($_GPC['cardnum']);
			$keywords = reply_single($_GPC['rulename']);

			$updata = array(
				'rid' => $_GPC['rulename'],
				'uniacid' => $_W['uniacid'],
				'pid' => $_GPC['pid'],
				'name' => $_GPC['name'],
				'description' => $_GPC['description'],
				'avatar' => $_GPC['avatar'],
				'img' => $_GPC['img'],
				'status' => $_GPC['status'],
			);


			$temp =  pdo_update('haoman_dpm_jiabing',$updata,array('id'=>$id));

			message("修改嘉宾成功",$this->createWebUrl('jiabinshow',array('rid'=>$rid)),"success");


		}elseif($operation == 'addad'){

			// message($_GPC['cardname']);

			$keywords = reply_single($_GPC['rulename']);

			$updata = array(
				'rid' => $_GPC['rulename'],
				'uniacid' => $_W['uniacid'],
				'pid' => $_GPC['pid'],
				'name' => $_GPC['name'],
				'description' => $_GPC['description'],
				'avatar' => $_GPC['avatar'],
				'img' => $_GPC['img'],
				'status' => $_GPC['status'],
			);


			// message($keywords['name']);

			$temp = pdo_insert('haoman_dpm_jiabing', $updata);

			message("添加嘉宾成功",$this->createWebUrl('jiabinshow',array('rid'=>$rid)),"success");

		}elseif($operation == 'up'){
			$uid = intval($_GPC['uid']);
			if(empty($uid)){
				message('获取嘉宾ID出错，请刷新后重试', '', 'error');
			}
			$item = pdo_fetch("select * from " . tablename('haoman_dpm_jiabing') . "  where id=:uid ", array(':uid' => $uid));
			$keywords = reply_single($item['rid']);
			include $this->template('updatajiabin');

		}elseif($operation == 'del'){
			$uid = intval($_GPC['uid']);
			if(empty($uid)){
				message('获取奖品ID出错，请刷新后重试', '', 'error');
			}
			pdo_delete('haoman_dpm_jiabing', array('id' => $uid));
			message("删除嘉宾成功",$this->createWebUrl('jiabinshow',array('rid'=>$rid)),"success");

		}else{


			include $this->template('newjiabin');

		}

	}

    public function doMobileshowjiabin() {
        global $_GPC, $_W;
        $rid = intval($_GPC['id']);
        $uniacid = $_W['uniacid'];

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {

            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
            exit();
        }

        //网页授权借用开始

        load()->model('account');
        $_W['account'] = account_fetch($_W['acid']);
        $cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
        $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        if ($_W['account']['level'] != 4) {
            $from_user = $cookie['openid'];
            $avatar = $cookie['avatar'];
            $nickname = $cookie['nickname'];
        }else{
            $from_user = $_W['fans']['from_user'];
            $avatar = $_W['fans']['tag']['avatar'];
            $nickname = $_W['fans']['nickname'];
        }

        $code = $_GPC['code'];
        $urltype = '';
        if (empty($from_user) || empty($avatar) || empty($nickname)) {
            if (!is_array($cookie) || !isset($cookie['avatar']) || !isset($cookie['openid']) || !isset($cookie['nickname'])) {
                $userinfo = $this->get_UserInfo($rid, $code, $urltype);
                $nickname = $userinfo['nickname'];
                $avatar = $userinfo['headimgurl'];
                $from_user = $userinfo['openid'];
            } else {
                $avatar = $cookie['avatar'];
                $nickname = $cookie['nickname'];
                $from_user = $cookie['openid'];
            }
        }

        //网页授权借用结束

        $page_from_user = base64_encode(authcode($from_user, 'ENCODE'));


        if (empty($rid)) {
            message('抱歉，参数错误！', '', 'error');//调试代码
        }


        $reply = pdo_fetch("select id,jiabintitle,jbtop_url from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));

        if ($reply == false) {
            message('抱歉，活动已经结束，下次再来吧！', '', 'error');
        }

        $jiabin = pdo_fetchall("select * from " . tablename('haoman_dpm_jiabing') . " where rid = '" . $rid . "' and uniacid = '" . $uniacid . "' and status=0  order by pid desc");

        if($jiabin==false){
            message('抱歉，目前没有嘉宾，下次再来吧！', '', 'error');
        }

        $fans = pdo_fetch("select id from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");

        if($fans==false){
            message('抱歉，你走错位置了', '', 'error');
        }


        //分享信息
        $sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $page_from_user));
        $sharetitle = empty($reply['share_title']) ? '一起来看嘉宾吧!' : $reply['share_title'];
        $sharedesc = empty($reply['share_desc']) ? '亲，一起来看嘉宾吧，还能赢大奖哦！！' : str_replace("\r\n", " ", $reply['share_desc']);
        if (!empty($reply['share_imgurl'])) {
            $shareimg = toimage($reply['share_imgurl']);
        } else {
            $shareimg = toimage($reply['picture']);
        }
        $jssdk = new JSSDK();
        $package = $jssdk->GetSignPackage();

        if(empty($reply['mobpicurl'])){
            $bg = "../addons/haoman_dpm/mobimg/bg2.jpg";
        }else{
            $bg = tomedia($reply['mobpicurl']);
        }

        include $this->template('show_jiabin');
    }

    //嘉宾搜索
    public function doMobilejiabin_save() {
        global $_GPC, $_W;

        $rid = intval($_GPC['rid']);
        $uniacid = $_W['uniacid'];
        $openid = $_GPC['openid'];
        $key = $_GPC['key'];


        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {

            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
            exit();
        }

        //网页授权借用开始（特殊代码）

        $from_user = $_W['fans']['from_user'];
        $avatar = $_W['fans']['tag']['avatar'];
        $nickname = $_W['fans']['nickname'];

        load()->model('account');
        $_W['account'] = account_fetch($_W['acid']);
        $cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
        $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        if ($_W['account']['level'] != 4) {
            $from_user = authcode(base64_decode($_GPC['from_user']), 'DECODE');
            $avatar = $cookie['avatar'];
            $nickname = $cookie['nickname'];
        }

        //网页授权借用结束（特殊代码）
        if (empty($from_user)) {
            $this->message(array("success" => 0, "msg" => '获取不到您的OpenID,请从新进入活动页面'), "");
        }
        if($from_user!=$openid){
            $data = array(
                'success' => 5,
                'msg' => '获取资料出错，请重新进入！',
            );
            echo json_encode($data);
            exit();
        }

        $now = time();

        $toupiao = pdo_fetchall("select * from " . tablename('haoman_dpm_jiabing') . " where rid = :rid and `name` LIKE :keyword and status =0 order by `id` desc", array(':rid' => $rid,':keyword' => "%{$key}%"));

        if($toupiao==false){
            $data = array(
                's' => 1,
                'msg' => '没搜索到',
                'list' => '没搜索到,您想要的结果',
            );
            echo json_encode($data);
            exit();
        }else{
            $aa='';
            foreach($toupiao as $v){
                if(empty($v['img'])){
                    $v['img']="../addons/haoman_dpm/img9/582c1db1c84c3.jpg";
                    $v['avatar']="../addons/haoman_dpm/img9/ava_default.jpg";
                }else{
                    $v['img'] = tomedia($v['img']);
                    $v['avatar'] = tomedia($v['avatar']);
                }
                $aa .='<div class="ml">';
                $aa .='<div class="sh">';
                $aa .='<li class="p" style="margin-top: 5px;"><img src="'.$v['img'].'"></li>';
                $aa .='<li class="na" style="height:40px;margin: 4px;"><span style="display: inline-block;height: auto">'.$v['description'].'</span></li>';
                $aa .='<li class="no" style="height:60px;text-align:left;margin-left: 5px;line-height: 60px;"><img src="'.$v['avatar'].'" style="height: 44px;width: 44px;border-radius: 100%;"><span style="display: inline-block;margin-left: 10px;">'.$v['name'].'</span></li>';
                $aa .='</div>';
                $aa .='</div>';
            }

            $data = array(
                's' => 0,
                'list' => $aa,
            );
            echo json_encode($data);
            exit();
        }

    }

    //修改部分开始
    //投票首页
    public function doMobilego_toupiao() {
        global $_GPC, $_W;
        $rid = intval($_GPC['id']);
        $uniacid = $_W['uniacid'];

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {

            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
            exit();
        }

        //网页授权借用开始

        load()->model('account');
        $_W['account'] = account_fetch($_W['acid']);
        $cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
        $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        if ($_W['account']['level'] != 4) {
            $from_user = $cookie['openid'];
            $avatar = $cookie['avatar'];
            $nickname = $cookie['nickname'];
        }else{
            $from_user = $_W['fans']['from_user'];
            $avatar = $_W['fans']['tag']['avatar'];
            $nickname = $_W['fans']['nickname'];
        }

        $code = $_GPC['code'];
        $urltype = '';
        if (empty($from_user) || empty($avatar) || empty($nickname)) {
            if (!is_array($cookie) || !isset($cookie['avatar']) || !isset($cookie['openid']) || !isset($cookie['nickname'])) {
                $userinfo = $this->get_UserInfo($rid, $code, $urltype);
                $nickname = $userinfo['nickname'];
                $avatar = $userinfo['headimgurl'];
                $from_user = $userinfo['openid'];
            } else {
                $avatar = $cookie['avatar'];
                $nickname = $cookie['nickname'];
                $from_user = $cookie['openid'];
            }
        }

        //网页授权借用结束

        $page_from_user = base64_encode(authcode($from_user, 'ENCODE'));


        if (empty($rid)) {
            message('抱歉，参数错误！', '', 'error');//调试代码
        }


        $reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));

        if ($reply == false) {
            message('抱歉，活动已经结束，下次再来吧！', '', 'error');
        }

        $toupiao = pdo_fetchall("select * from " . tablename('haoman_dpm_toupiao') . " where rid = '" . $rid . "' and uniacid = '" . $uniacid . "' and status=0  order by get_num desc");

        if($toupiao==false){
            message('抱歉，目前没有投票，下次再来吧！', '', 'error');
        }

        $fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");
        if($fans==false){
            message('抱歉，你走错位置了', '', 'error');
        }
        $nowtime = mktime(0, 0, 0);

        if ($fans['last_time'] < $nowtime) {

            $fans['tp_times'] = 0;

            $temp = pdo_update('haoman_dpm_fans', array('tp_times' =>$fans['tp_times'],'last_time' => $nowtime), array('id' => $fans['id']));

        }

        //分享信息
        $sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $page_from_user));
        $sharetitle = empty($reply['share_title']) ? '一起来帮TA投票吧!' : $reply['share_title'];
        $sharedesc = empty($reply['share_desc']) ? '亲，一起来帮TA投票吧，还能赢大奖哦！！' : str_replace("\r\n", " ", $reply['share_desc']);
        if (!empty($reply['share_imgurl'])) {
            $shareimg = toimage($reply['share_imgurl']);
        } else {
            $shareimg = toimage($reply['picture']);
        }
        $jssdk = new JSSDK();
        $package = $jssdk->GetSignPackage();

        if(empty($reply['mobpicurl'])){
            $bg = "../addons/haoman_dpm/mobimg/bg2.jpg";
        }else{
            $bg = tomedia($reply['mobpicurl']);
        }

        include $this->template('index9');
    }
//投票排行页
    public function doMobilevote_show() {
        global $_GPC, $_W;
        $rid = intval($_GPC['id']);
        $uniacid = $_W['uniacid'];

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {

            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
            exit();
        }

        //网页授权借用开始

        load()->model('account');
        $_W['account'] = account_fetch($_W['acid']);
        $cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
        $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        if ($_W['account']['level'] != 4) {
            $from_user = $cookie['openid'];
            $avatar = $cookie['avatar'];
            $nickname = $cookie['nickname'];
        }else{
            $from_user = $_W['fans']['from_user'];
            $avatar = $_W['fans']['tag']['avatar'];
            $nickname = $_W['fans']['nickname'];
        }

        $code = $_GPC['code'];
        $urltype = '';
        if (empty($from_user) || empty($avatar) || empty($nickname)) {
            if (!is_array($cookie) || !isset($cookie['avatar']) || !isset($cookie['openid']) || !isset($cookie['nickname'])) {
                $userinfo = $this->get_UserInfo($rid, $code, $urltype);
                $nickname = $userinfo['nickname'];
                $avatar = $userinfo['headimgurl'];
                $from_user = $userinfo['openid'];
            } else {
                $avatar = $cookie['avatar'];
                $nickname = $cookie['nickname'];
                $from_user = $cookie['openid'];
            }
        }

        //网页授权借用结束

        $page_from_user = base64_encode(authcode($from_user, 'ENCODE'));


        if (empty($rid)) {
            message('抱歉，参数错误！', '', 'error');//调试代码
        }



        $reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));

        if ($reply == false) {
            message('抱歉，活动已经结束，下次再来吧！', '', 'error');
        }

        $toupiao = pdo_fetchall("select * from " . tablename('haoman_dpm_toupiao') . " where rid = '" . $rid . "' and uniacid = '" . $uniacid . "' and status=0  order by get_num desc");

        if($toupiao==false){
            message('抱歉，目前没有投票，下次再来吧！', '', 'error');
        }

        $fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");
        if($fans==false){
            message('抱歉，你走错位置了', '', 'error');
        }
        $nowtime = mktime(0, 0, 0);

        if ($fans['last_time'] < $nowtime) {

            $fans['tp_times'] = 0;

            $temp = pdo_update('haoman_dpm_fans', array('tp_times' =>$fans['tp_times'],'last_time' => $nowtime), array('id' => $fans['id']));

        }
        $total = pdo_fetchcolumn("select sum(get_num) from " . tablename('haoman_dpm_toupiao') . "  where rid = :rid and uniacid=:uniacid and status=0",array(':rid'=>$rid,':uniacid'=>$uniacid));

        foreach ($toupiao as &$v){
            if($total>0){
                $v['xyz'] = round(($v['get_num']/$total)*100,2);
                if($v['xyz']>100){
                    $v['xyz']=100;
                }
            }else{
                $v['xyz']=0;
            }

        }
        unset($v);



        //分享信息
        $sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $page_from_user));
        $sharetitle = empty($reply['share_title']) ? '一起来帮TA投票吧!!' : $reply['share_title'];
        $sharedesc = empty($reply['share_desc']) ? '亲，一起来帮TA投票吧!，赢大奖哦！！' : str_replace("\r\n", " ", $reply['share_desc']);
        if (!empty($reply['share_imgurl'])) {
            $shareimg = toimage($reply['share_imgurl']);
        } else {
            $shareimg = toimage($reply['picture']);
        }
        $jssdk = new JSSDK();
        $package = $jssdk->GetSignPackage();

        if(empty($reply['mobpicurl'])){
            $bg = "../addons/haoman_dpm/mobimg/bg2.jpg";
        }else{
            $bg = tomedia($reply['mobpicurl']);
        }

        include $this->template('vote_show');
    }
//开始投票
    public function doMobilevote_save() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $uniacid = $_W['uniacid'];
        $openid = $_GPC['openid'];
        $oid = $_GPC['oid'];
        $type = $_GPC['type'];


        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {

            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
            exit();
        }

        //网页授权借用开始（特殊代码）

        $from_user = $_W['fans']['from_user'];
        $avatar = $_W['fans']['tag']['avatar'];
        $nickname = $_W['fans']['nickname'];

        load()->model('account');
        $_W['account'] = account_fetch($_W['acid']);
        $cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
        $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        if ($_W['account']['level'] != 4) {
            $from_user = authcode(base64_decode($_GPC['from_user']), 'DECODE');
            $avatar = $cookie['avatar'];
            $nickname = $cookie['nickname'];
        }

        //网页授权借用结束（特殊代码）
        if (empty($from_user)) {
            $this->message(array("success" => 0, "msg" => '获取不到您的OpenID,请从新进入活动页面'), "");
        }
        if($from_user!=$openid){
            $data = array(
                'success' => 5,
                'msg' => '获取资料出错，请重新进入！',
            );
            echo json_encode($data);
            exit();
        }

        $now = time();
        $reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
        if($reply==false){
            $data = array(
                'success' => 5,
                'msg' => '活动还没开启',
            );
            echo json_encode($data);
            exit();
        }
        if($reply['tp_starttime']>$now){
            $data = array(
                'success' => 5,
                'msg' => '投票还没开启',
            );
            echo json_encode($data);
            exit();
        }
        if($reply['tp_endtime']<$now){
            $data = array(
                'success' => 5,
                'msg' => '投票已经结束了',
            );
            echo json_encode($data);
            exit();
        }

        $fans = pdo_fetch("select id,tp_times from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $from_user . "'");

        if($fans==false){
            $data = array(
                'success' => 5,
                'msg' => '获取不到您的信息，请重新进入',
            );
            echo json_encode($data);
            exit();
        }
        if($fans['tp_times']>=$reply['tp_times']&&$reply['tp_times']!=0){
            $data = array(
                'success' => 3,
                'msg' => '没有次数了',
            );
            echo json_encode($data);
            exit();
        }

        $toupiao = pdo_fetch("select id,get_num from " . tablename('haoman_dpm_toupiao') . " where rid = :rid and id=:id and status =0 order by `id` desc", array(':rid' => $rid,':id'=>$oid));
        if($toupiao==false){
            $data = array(
                'success' => 5,
                'msg' => '这个投票已取消',
            );
            echo json_encode($data);
            exit();
        }
        $tp_data = pdo_fetch("select id from " . tablename('haoman_dpm_tp_log') . " where rid = '" . $rid . "' and from_user='" . $from_user . "' and toupiaoip='" . $oid . "' and tp_number='" . $toupiao['number'] . "'");
        if($tp_data){
            $data = array(
                'success' => 1,
                'msg' => '已经投票过了',
            );
            echo json_encode($data);
            exit();
        }
        else{
            $insert = array(
                'rid' => $rid,
                'uniacid' => $_W['uniacid'],
                'from_user' => $from_user,
                'avatar' => $fans['avatar'],
                'nickname' => $nickname,
                'toupiaoip' => $oid,
                'tp_number' => $toupiao['tp_number'],
                'visitorsip' => CLIENT_IP,
                'visitorstime' => TIMESTAMP,
                'viewnum' => 1,
            );

            $temp = pdo_insert('haoman_dpm_tp_log', $insert);
            if($temp){
                pdo_update('haoman_dpm_toupiao',array('get_num'=>$toupiao['get_num']+1),array('id'=>$toupiao['id']));
                pdo_update('haoman_dpm_fans',array('tp_times'=>$fans['tp_times']+1),array('id'=>$fans['id']));

                $data = array(
                    'success' => 0,
                    'msg' => $toupiao['get_num']+1,
                );
                echo json_encode($data);
                exit();
            }else{
                $data = array(
                    'success' => 5,
                    'msg' => '投票失败',
                );
                echo json_encode($data);
                exit();
            }
        }
    }
//投票搜索
    public function doMobilevote_list() {
        global $_GPC, $_W;

        $rid = intval($_GPC['rid']);
        $uniacid = $_W['uniacid'];
        $openid = $_GPC['openid'];
        $cid = $_GPC['cid'];
        $key = $_GPC['key'];


        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {

            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
            exit();
        }

        //网页授权借用开始（特殊代码）

        $from_user = $_W['fans']['from_user'];
        $avatar = $_W['fans']['tag']['avatar'];
        $nickname = $_W['fans']['nickname'];

        load()->model('account');
        $_W['account'] = account_fetch($_W['acid']);
        $cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
        $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        if ($_W['account']['level'] != 4) {
            $from_user = authcode(base64_decode($_GPC['from_user']), 'DECODE');
            $avatar = $cookie['avatar'];
            $nickname = $cookie['nickname'];
        }

        //网页授权借用结束（特殊代码）
        if (empty($from_user)) {
            $this->message(array("success" => 0, "msg" => '获取不到您的OpenID,请从新进入活动页面'), "");
        }
        if($from_user!=$openid){
            $data = array(
                'success' => 5,
                'msg' => '获取资料出错，请重新进入！',
            );
            echo json_encode($data);
            exit();
        }

        $now = time();

        $toupiao = pdo_fetchall("select * from " . tablename('haoman_dpm_toupiao') . " where rid = :rid and `name` LIKE :keyword or `number`  LIKE :keyword  and status =0 order by `id` desc", array(':rid' => $rid,':keyword' => "%{$key}%"));

        if($toupiao==false){
            $data = array(
                's' => 1,
                'msg' => '没搜索到',
                'list' => '没搜索到,您想要的结果',
            );
            echo json_encode($data);
            exit();
        }else{
            $aa='';
            foreach($toupiao as $v){
                if(empty($v['img'])){
                    $v['img']="../addons/haoman_dpm/img9/582c1db1c84c3.jpg";
                }else{
                    $v['img'] = tomedia($v['img']);
                }
                $aa .='<div class="ml">';
                $aa .='<div class="sh">';
                $aa .='<li class="n">编号: '.$v['number'].'</li>';
                $aa .='<li class="p"><img src="'.$v['img'].'"></li>';
                $aa .='<li class="na">'.$v['description'].'</li>';
                $aa .='<li class="no">'.$v['name'].'</li>';
                $aa .='<li class="v" id="get_num_'.$v['id'].'">票数：'.$v['get_num'].'</li>';
                $aa .='<li class="a" id="'.$v['id'].'">投一票</li>';
                $aa .='</div>';
                $aa .='</div>';
            }

            $data = array(
                's' => 0,
                'list' => $aa,
            );
            echo json_encode($data);
            exit();
        }

    }
//投票排行搜索
    public function doMobilevote_show_list() {
        global $_GPC, $_W;

        $rid = intval($_GPC['rid']);
        $uniacid = $_W['uniacid'];
        $openid = $_GPC['openid'];
        $cid = $_GPC['cid'];
        $key = $_GPC['key'];


        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {

            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
            exit();
        }

        //网页授权借用开始（特殊代码）

        $from_user = $_W['fans']['from_user'];
        $avatar = $_W['fans']['tag']['avatar'];
        $nickname = $_W['fans']['nickname'];

        load()->model('account');
        $_W['account'] = account_fetch($_W['acid']);
        $cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
        $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        if ($_W['account']['level'] != 4) {
            $from_user = authcode(base64_decode($_GPC['from_user']), 'DECODE');
            $avatar = $cookie['avatar'];
            $nickname = $cookie['nickname'];
        }

        //网页授权借用结束（特殊代码）
        if (empty($from_user)) {
            $this->message(array("success" => 0, "msg" => '获取不到您的OpenID,请从新进入活动页面'), "");
        }
        if($from_user!=$openid){
            $data = array(
                'success' => 5,
                'msg' => '获取资料出错，请重新进入！',
            );
            echo json_encode($data);
            exit();
        }

        $now = time();

        $toupiao = pdo_fetchall("select * from " . tablename('haoman_dpm_toupiao') . " where rid = :rid and `name` LIKE :keyword or `number`  LIKE :keyword  and status =0 order by `id` desc", array(':rid' => $rid,':keyword' => "%{$key}%"));

        if($toupiao==false){
            $data = array(
                's' => 1,
                'msg' => '没搜索到',
                'list' => '没搜索到,您想要的结果',
            );
            echo json_encode($data);
            exit();
        }else{
            $aa='';
            foreach($toupiao as $v){
                if(empty($v['img'])){
                    $v['img']="../addons/haoman_dpm/img9/582c1db1c84c3.jpg";
                }else{
                    $v['img'] = tomedia($v['img']);
                }
                $aa .='<div class="show">';
                $aa .='<div class="ll">';
                $aa .='<div class="p" data-original=""><img src="'.$v['img'].'"></div>';
                $aa .='</div>';
                $aa .='<div class="lr">';
                $aa .=' <li class="na">'.$v['description'].'</li>';
                $aa .='<li class="no">'.$v['name'].'<span>编号：'.$v['number'].'</span></li>';
                $aa .='<li class="vs"><span class="vsb"></span><span class="vsp" id="vs206"></span></li>';
                $aa .='<li class="vo"><span class="vol">票数：'.$v['get_num'].'</span><span class="vor" rel="206">'.$v['xyz'].'%</span></li>';
                $aa .='</div>';
                $aa .='</div>';

            }

            $data = array(
                's' => 0,
                'list' => $aa,
            );
            echo json_encode($data);
            exit();
        }

    }

//投票列表
    public function doWebtoupiao(){
        global $_W  ,$_GPC;
        checklogin();
        $uniacid = $_W['uniacid'];
        $rid = $_GPC['rid'];
        $_GPC['do']='toupiao';
        load()->model('reply');
        load()->func('tpl');
        $sql = "uniacid = :uniacid and `module` = :module";
        $params = array();
        $params[':uniacid'] = $_W['uniacid'];
        $params[':module'] = 'haoman_dpm';

        $rowlist = reply_search($sql, $params);
        foreach ($rowlist as $k => $v) {
            $rowlist[$k]['awardstotal'] = pdo_fetchcolumn('select count(*) from ' . tablename('haoman_dpm_toupiao') . 'where uniacid = :uniacid and rid=:rid', array(':uniacid' => $_W['uniacid'],':rid' => $v['id']));
            if(empty($rowlist[$k]['awardstotal'])){
                $rowlist[$k]['awardstotal'] = 0;
            }
        }
        include $this->template('toupiaolist');
    }


    //查看投票详情
    public function doWebtoupiaoshow(){
        global $_W  ,$_GPC;
        // checklogin();
        $rid = intval($_GPC['rid']);
        load()->model('reply');


        $t = time();
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $sql = 'select * from ' . tablename('haoman_dpm_toupiao') . 'where uniacid = :uniacid and rid = :rid order by `pid` desc ,`get_num`  desc LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
        $prarm = array(':uniacid' => $_W['uniacid'] ,':rid' => $rid);
        $list = pdo_fetchall($sql, $prarm);
        // $count = pdo_fetchcolumn('select count(*) from ' . tablename('haoman_dpm_pw') . 'where uniacid = :uniacid and pici = :pici', $prarm);
        $pager = pagination($count, $pindex, $psize);

        foreach ($list as $k => $v) {
            $keywords = reply_single($v['rid']);
            $list[$k]['rulename'] = $keywords['name'];
        }

        load()->func('tpl');
        include $this->template('toupiaoshow');
    }


    //添加和编辑投票
    public function doWebNewtoupiao() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        load()->model('reply');
        load()->func('tpl');
        $sql = "uniacid = :uniacid and `module` = :module";
        $params = array();
        $params[':uniacid'] = $_W['uniacid'];
        $params[':module'] = 'haoman_dpm';

        $rowlist = reply_search($sql, $params);
        $starttime = time();
        $endtime = time()+86400;
        // message($rid);

        if($operation == 'updataad'){

            $id = $_GPC['listid'];

            // message($_GPC['cardnum']);
            $keywords = reply_single($_GPC['rulename']);

            $updata = array(

                'rid' => $_GPC['rulename'],
                'uniacid' => $_W['uniacid'],
                'starttime' => strtotime($_GPC['times']['start']),
                'endtime' => strtotime($_GPC['times']['end']),
                'back_time' => intval($_GPC['back_time']),
                'pid' => $_GPC['pid'],
                'name' => $_GPC['name'],
                'description' => $_GPC['description'],
                'avatar' => $_GPC['avatar'],
                'img' => $_GPC['img'],
                'status' => $_GPC['status'],
            );


            $temp =  pdo_update('haoman_dpm_toupiao',$updata,array('id'=>$id));

            message("修改投票成功",$this->createWebUrl('toupiaoshow',array('rid'=>$rid)),"success");


        }elseif($operation == 'addad'){

            // message($_GPC['cardname']);

            $keywords = reply_single($_GPC['rulename']);

            $randcode = $this->genkeyword(4);

            $updata = array(
                'number' => $randcode,
                'rid' => $_GPC['rulename'],
                'uniacid' => $_W['uniacid'],
                'starttime' => strtotime($_GPC['times']['start']),
                'endtime' => strtotime($_GPC['times']['end']),
                'back_time' => intval($_GPC['back_time']),
                'pid' => $_GPC['pid'],
                'name' => $_GPC['name'],
                'description' => $_GPC['description'],
                'avatar' => $_GPC['avatar'],
                'img' => $_GPC['img'],
                'status' => $_GPC['status'],
            );


            // message($keywords['name']);

            $temp = pdo_insert('haoman_dpm_toupiao', $updata);

            message("添加投票成功",$this->createWebUrl('toupiaoshow',array('rid'=>$rid)),"success");

        }elseif($operation == 'up'){
            $uid = intval($_GPC['uid']);
            if(empty($uid)){
                message('获取投票ID出错，请刷新后重试', '', 'error');
            }
            $item = pdo_fetch("select * from " . tablename('haoman_dpm_toupiao') . "  where id=:uid ", array(':uid' => $uid));
            $keywords = reply_single($item['rid']);
            include $this->template('updatatoupiao');

        }elseif($operation == 'del'){
            $uid = intval($_GPC['uid']);
            if(empty($uid)){
                message('获取投票ID出错，请刷新后重试', '', 'error');
            }
            pdo_delete('haoman_dpm_toupiao', array('id' => $uid));
            message("删除投票成功",$this->createWebUrl('toupiaoshow',array('rid'=>$rid)),"success");

        }else{


            include $this->template('newtoupiao');

        }

    }

    public function doWebUserinfo() {
        global $_GPC, $_W;

        $op = empty($_GPC['op']) ? 'display' : $_GPC['op'];
        $starttime = time()-604800;
        $endtime = time()+604800;

        if ($_W['isajax']) {

            $fansid = intval($_GPC['fansid']);
            $uniacid = $_W['uniacid'];

            $params = array();

            $condition = 'uniacid=:uniacid';
            $params[':uniacid'] = $_W['uniacid'];

            if (!empty($_GPC['datestart'])) {
                $starttime = strtotime($_GPC['datestart']);
                $endtime   = strtotime($_GPC['dateend']);
                $condition .= " AND visitorstime >= :starttime AND visitorstime <= :endtime";
                $params[':starttime'] = $starttime;
                $params[':endtime']   = $endtime;
            }


            $fans = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_toupiao') . " WHERE uniacid = :uniacid AND id = :id", array(':uniacid' => $uniacid, ':id' => $fansid));

            $dsdata = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_tp_log') . " WHERE ".$condition." and toupiaoip='".$fans['id']."' ORDER BY `id` LIMIT 50",$params);

//            foreach($dsdata as &$k){
////                $k['category'] = pdo_fetchcolumn('SELECT `name` FROM ' . tablename('haoman_virtuamall_category') . ' WHERE `weid` = :weid and id = :id',array(":weid" => $uniacid,':id'=>$k['category']));
//
//                $k['name']	= pdo_fetchcolumn('SELECT `realname` FROM ' . tablename('haoman_virtuamall_order') . ' WHERE `weid` = :weid and tandid = :tandid',array(":weid" => $uniacid,':tandid'=>$k['orderid']));
//                $k['description']	= pdo_fetchcolumn('SELECT `mobile` FROM ' . tablename('haoman_virtuamall_order') . ' WHERE `weid` = :weid and tandid = :tandid',array(":weid" => $uniacid,':tandid'=>$k['orderid']));
//
//            }
//            unset($k);

            include $this->template('userinfo');
            exit();
        }
    }

    public function doWebDeletehexiao_jilu() {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $rule = pdo_fetch("select id from " . tablename('haoman_dpm_tp_log') . " where id = :id ", array(':id' => $id));
        if (empty($rule)) {
            message('抱歉，参数错误！');
        }else{
            pdo_delete('haoman_dpm_tp_log', array('id' => $id));

            message('投票记录删除成功！', referer(), 'success');
        }

    }

    public function  doWebDownload_tp_log()
    {
        global $_GPC,$_W;
        $rid= intval($_GPC['rid']);

        if(empty($rid)){

            message('抱歉，传递的参数错误！','', 'error');

        }

        checklogin();
        $list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_tp_log') . " WHERE rid = {$rid}  ORDER BY id DESC ");
        foreach ($list as &$row) {
            $row['name'] = pdo_fetchcolumn("select name from " . tablename('haoman_dpm_toupiao') . " where id = :id and rid=:rid", array(':id' => $row['toupiaoip'],':rid'=>$rid));
            $row['number'] = pdo_fetchcolumn("select number from " . tablename('haoman_dpm_toupiao') . " where id = :id and rid=:rid", array(':id' => $row['toupiaoip'],':rid'=>$rid));

            $row['description'] = pdo_fetchcolumn("select description from " . tablename('haoman_dpm_toupiao') . " where id = :id and rid = :rid", array(':id' => $row['toupiaoip'],':rid'=>$rid));

//            if($row['status'] == 1){
//
//                $row['status']='未兑奖';
//
//            }else{
//
//                $row['status']='已兑换';
//
//            }

        }

        $tableheader = array('序号','投票人','投票编码','标题', '简介','数量','参与时间' );
        $html = "\xEF\xBB\xBF";
        foreach ($tableheader as $value) {
            $html .= $value . "\t ,";
        }
        $html .= "\n";

        foreach ($list as $value) {

            $html .= $value['id'] . "\t ,";

            $html .= $value['nickname'] . "\t ,";
            $html .= $value['number'] . "\t ,";

            $html .= $value['name'] . "\t ,";

            $html .= $value['description'] . "\t ,";

            $html .= $value['viewnum'] . "\t ,";


            $html .= date('Y-m-d H:i:s', $value['visitorstime']) . "\n ";


        }


        header("Content-type:text/csv");

        header("Content-Disposition:attachment;filename=投票记录.csv");

        $html = mb_convert_encoding($html, 'gb2312', 'UTF-8');

        echo $html;
        exit();
    }

    //修改结束



//现场抽奖奖品列表
	public function doWebcode(){
		global $_W  ,$_GPC;
		checklogin();
		$uniacid = $_W['uniacid'];
		$rid = $_GPC['rid'];
		$_GPC['do']='code';
		load()->model('reply');
		load()->func('tpl');
		$sql = "uniacid = :uniacid and `module` = :module";
		$params = array();
		$params[':uniacid'] = $_W['uniacid'];
		$params[':module'] = 'haoman_dpm';
		$awardtitle = '现场抽奖';
		$turntable = 1;

		$rowlist = reply_search($sql, $params);

		foreach ($rowlist as $k => $v) {
			$rowlist[$k]['awardstotal'] = pdo_fetchcolumn('select SUM(awardstotal) from ' . tablename('haoman_dpm_prize') . 'where uniacid = :uniacid and rid=:rid and turntable=:turntable', array(':turntable' => $turntable,':uniacid' => $_W['uniacid'],':rid' => $v['id']));
			if(empty($rowlist[$k]['awardstotal'])){
				$rowlist[$k]['awardstotal'] = 0;
			}
			$rowlist[$k]['prizedraw'] = pdo_fetchcolumn('select SUM(prizedraw) from ' . tablename('haoman_dpm_prize') . 'where uniacid = :uniacid and rid=:rid and turntable=:turntable', array(':turntable' => $turntable,':uniacid' => $_W['uniacid'],':rid' => $v['id']));
			$rowlist[$k]['total'] = pdo_fetchcolumn('select count(id) from ' . tablename('haoman_dpm_prize') . 'where uniacid = :uniacid and rid=:rid and turntable=:turntable', array(':turntable' => $turntable,':uniacid' => $_W['uniacid'],':rid' => $v['id']));
		}
		include $this->template('code');
	}


	//抢红包奖品列表
	public function doWebqhbjp(){
		global $_W  ,$_GPC;
		checklogin();
		$uniacid = $_W['uniacid'];
		$rid = $_GPC['rid'];
		$_GPC['do']='code';
		load()->model('reply');
		load()->func('tpl');
		$sql = "uniacid = :uniacid and `module` = :module";
		$params = array();
		$params[':uniacid'] = $_W['uniacid'];
		$params[':module'] = 'haoman_dpm';
		$awardtitle = '抢红包';
		$turntable = 2;

		$rowlist = reply_search($sql, $params);
		foreach ($rowlist as $k => $v) {
			$rowlist[$k]['awardstotal'] = pdo_fetchcolumn('select SUM(awardstotal) from ' . tablename('haoman_dpm_prize') . 'where uniacid = :uniacid and rid=:rid and turntable=:turntable', array(':turntable' => $turntable,':uniacid' => $_W['uniacid'],':rid' => $v['id']));
			if(empty($rowlist[$k]['awardstotal'])){
				$rowlist[$k]['awardstotal'] = 0;
			}
			$rowlist[$k]['prizedraw'] = pdo_fetchcolumn('select SUM(prizedraw) from ' . tablename('haoman_dpm_prize') . 'where uniacid = :uniacid and rid=:rid and turntable=:turntable', array(':turntable' => $turntable,':uniacid' => $_W['uniacid'],':rid' => $v['id']));
		}
		include $this->template('code');
	}



	function isExist($randcode){
		global $_W;
		$sql = 'select * from ' . tablename('haoman_dpm_code') . 'where uniacid = :uniacid and code = :code';
		$prarm = array(':uniacid' => $_W['uniacid'], ':code' => $randcode);
		if(pdo_fetch($sql,$prarm)){
			return 1;
		}else{
			return 0;
		}

	}

	function genkeyword($length)
	{
		$chars = array('0','1', '2', '3', '4', '5', '6', '7', '8', '9');
		$password = rand(1, 9);
		for ($i = 0; $i < $length - 1; $i++) {
			$keys = array_rand($chars, 1);
			$password .= $chars[$keys];
		}
		return $password;
	}

	//查看详细奖品
	public function doWebcodeshow(){
		global $_W  ,$_GPC;
		// checklogin();
		$rid = intval($_GPC['rid']);
		$turntable = $_GPC['turntable'];
		load()->model('reply');

		if(empty($turntable)){
			message('请从左侧菜单选择具体的活动类型', '', 'error');
		}

		if($turntable == 1){
			$awardtitle = '现场抽奖';
		}
		if($turntable == 2){
			$awardtitle = '抢红包';
		}

		$t = time();
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$sql = 'select * from ' . tablename('haoman_dpm_prize') . 'where uniacid = :uniacid and rid = :rid and turntable = :turntable order by sort desc LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
		$prarm = array(':uniacid' => $_W['uniacid'] ,':rid' => $rid,':turntable' => $turntable);
		$list = pdo_fetchall($sql, $prarm);
		// $count = pdo_fetchcolumn('select count(*) from ' . tablename('haoman_dpm_pw') . 'where uniacid = :uniacid and pici = :pici', $prarm);
		$pager = pagination($count, $pindex, $psize);

		foreach ($list as $k => $v) {
			$keywords = reply_single($v['rid']);
			$list[$k]['rulename'] = $keywords['name'];
		}

		load()->func('tpl');
		include $this->template('codeshow');
	}


	//添加和编辑奖品
	public function doWebNewcode() {
		global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$turntable = $_GPC['turntable'];
		if(empty($turntable)){
			message('请从左侧菜单选择具体的活动类型，再添加奖品信息', '', 'error');
		}
		if($turntable == 1){
			$awardtitle = '现场抽奖';
		}
		if($turntable == 2){
			$awardtitle = '抢红包';
		}
		load()->model('reply');
		load()->func('tpl');
		$sql = "uniacid = :uniacid and `module` = :module";
		$params = array();
		$params[':uniacid'] = $_W['uniacid'];
		$params[':module'] = 'haoman_dpm';

		$rowlist = reply_search($sql, $params);

		// message($rid);

		if($operation == 'updataad'){

			$id = $_GPC['listid'];

			// message($_GPC['cardnum']);
			$keywords = reply_single($_GPC['rulename']);

			$updata = array(
				'rid' => $_GPC['rulename'],
				'uniacid' => $_W['uniacid'],
				'turntable' => $_GPC['turntable'],
				'sort' => $_GPC['sort'],
				'prizename' => $_GPC['prizename'],
				'couponid' => $_GPC['couponid'],
				'awardspro' => $_GPC['awardspro'],
				'awardstotal' => $_GPC['awardstotal'],
				'awardsimg' => $_GPC['awardsimg'],
				'ptype' => intval($_GPC['ptype']),
				'credit' => $_GPC['credit']*100,
				'credit2' => $_GPC['credit2']*100,
			);


			$temp =  pdo_update('haoman_dpm_prize',$updata,array('id'=>$id));

			message("修改奖品成功",$this->createWebUrl('codeshow',array('rid'=>$rid,'turntable'=>$turntable)),"success");


		}elseif($operation == 'addad'){

			// message($_GPC['cardname']);

			$keywords = reply_single($_GPC['rulename']);

			$updata = array(
				'rid' => $_GPC['rulename'],
				'uniacid' => $_W['uniacid'],
				'turntable' => $_GPC['turntable'],
				'sort' => $_GPC['sort'],
				'prizename' => $_GPC['prizename'],
				'couponid' => $_GPC['couponid'],
				'awardspro' => $_GPC['awardspro'],
				'awardstotal' => $_GPC['awardstotal'],
				'awardsimg' => $_GPC['awardsimg'],
				'ptype' => intval($_GPC['ptype']),
				'credit' => $_GPC['credit']*100,
				'credit2' => $_GPC['credit2']*100,
			);


			// message($keywords['name']);

			$temp = pdo_insert('haoman_dpm_prize', $updata);

			message("添加奖品成功",$this->createWebUrl('codeshow',array('rid'=>$rid,'turntable'=>$turntable)),"success");

		}elseif($operation == 'up'){
			$uid = intval($_GPC['uid']);
			if(empty($uid)){
				message('获取奖品ID出错，请刷新后重试', '', 'error');
			}
			$item = pdo_fetch("select * from " . tablename('haoman_dpm_prize') . "  where id=:uid ", array(':uid' => $uid));
			$keywords = reply_single($item['rid']);
			include $this->template('updatacode');

		}elseif($operation == 'del'){
			$uid = intval($_GPC['uid']);
			if(empty($uid)){
				message('获取奖品ID出错，请刷新后重试', '', 'error');
			}
			pdo_delete('haoman_dpm_prize', array('id' => $uid));
			message("删除奖品成功",$this->createWebUrl('codeshow',array('rid'=>$rid,'turntable'=>$turntable)),"success");

		}else{

			// $now = time();
			// $addcard1 = array(
			// 	"getstarttime" => $now,
			// 	"getendtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
			// );

			include $this->template('newcode');

		}

	}

//内定开始部分
    public function doWebDraw_default() { //内定人员管理
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $rid = $_GPC['rid'];
        $turntable = $_GPC['turntable'];
        load()->model('reply');
        $_GPC['do']='draw_default';

        $params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
        $total = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_draw_default') . "  where rid = :rid and uniacid=:uniacid and turntable=1", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 12;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select * from " . tablename('haoman_dpm_draw_default') . " where rid = :rid and uniacid=:uniacid and turntable=1 order by id desc " . $limit, $params);
        foreach ($list as &$k){
            $k['prizename'] = pdo_fetchcolumn("select prizename from " . tablename('haoman_dpm_prize') . "  where rid = :rid and uniacid=:uniacid and turntable=1 and id=:id", array(':id'=>$k['prizeid'],':rid' => $rid, ':uniacid' => $_W['uniacid']));

        }
        unset($k);
        include $this->template('draw_default');
    }


    public function doWebnewneiding() { //添加、修改内定人员
        global $_GPC, $_W;
         $rid= $_GPC['rid'];
        $_GPC['do']='newneiding';
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $uniacid = $_W['uniacid'];
        load()->model('reply');
        load()->func('tpl');
        $sql = "uniacid = :uniacid and `module` = :module";
        $params = array();
        $params[':uniacid'] = $_W['uniacid'];
        $params[':module'] = 'haoman_dpm';

        $rowlist = reply_search($sql, $params);

        $item = pdo_fetchall("select * from " . tablename('haoman_dpm_prize') . "  where rid=:rid and uniacid=:uniacid and turntable=1", array(':rid' => $rid,':uniacid'=>$uniacid));

        if($operation == 'updataneiding'){

            $id = $_GPC['listid'];

            $keywords = reply_single($_GPC['rulename']);
            $updata = array(
                'rid'=>$_GPC['rulename'],
                'rulename' => $keywords['name'],
                'uniacid' => $_W['uniacid'],
                'turntable' => 1,
                'openid' =>  $_GPC['openid'],
                'realname' => $_GPC['realname'],
                'mobile' => $_GPC['mobile'],
                'prizeid' => $_GPC['prizeid'],
                'status' => $_GPC['status'],
            );
            $temp =  pdo_update('haoman_dpm_draw_default',$updata,array('id'=>$id));

            message("更新内定人员成功",$this->createWebUrl('draw_default',array('rid' => $rid)),"success");


        }elseif($operation == 'newneiding'){
            $keywords = reply_single($_GPC['rulename']);
            $updata = array(
                'rid'=>$_GPC['rulename'],
                'rulename' => $keywords['name'],
                'uniacid' => $_W['uniacid'],
                'turntable' => 1,
                'openid' =>  $_GPC['openid'],
                'realname' => $_GPC['realname'],
                'mobile' => $_GPC['mobile'],
                'prizeid' => $_GPC['prizeid'],
                'status' => $_GPC['status'],
            );

            // message($keywords['name']);

            $temp = pdo_insert('haoman_dpm_draw_default', $updata);

            message("添加内定人员成功",$this->createWebUrl('draw_default',array('rid'=>$_GPC['rulename'])),"success");

        }elseif($operation == 'up'){

            $uid = intval($_GPC['uid']);

            $list = pdo_fetch("select * from " . tablename('haoman_dpm_draw_default') . "  where id=:uid ", array(':uid' => $uid));

            $prizename = pdo_fetchcolumn("select prizename from " . tablename('haoman_dpm_prize') . "  where rid = :rid and uniacid=:uniacid and turntable=1 and id=:id", array(':id'=>$list['prizeid'],':rid' => $rid, ':uniacid' => $_W['uniacid']));

            include $this->template('updataneiding');

        }elseif($operation == 'del'){

            $id = intval($_GPC['id']);
            if(empty($id)){
                message('获取内定人员出错，请刷新后重试', '', 'error');
            }
            pdo_delete('haoman_dpm_draw_default', array('id' => $id));
            message("删除内定人员成功",$this->createWebUrl('draw_default',array('rid'=>$rid)),"success");

        }else{

            include $this->template('newneiding');

        }


    }
//内定结束

	//口令导入
	public function doWebImport()
	{
		global $_W, $_GPC;
		load()->func('logging');
		$pici = $_GPC['pici'];

		if (!empty($_GPC['foo'])) {
			try {
				include_once "reader.php";
				$tmp = $_FILES['file']['tmp_name'];
				if (empty($tmp)) {
					echo '请选择要导入的Excel文件！';
					die;
				}
				$file_name = IA_ROOT . "/addons/haoman_dpm/xls/code.xls";
				$uniacid = $_W['uniacid'];

				if (copy($tmp, $file_name)) {
					$xls = new Spreadsheet_Excel_Reader();
					$xls->setOutputEncoding('utf-8');
					$xls->read($file_name);
					$data_values = "";
					$count = $xls->sheets[0]['numRows'];
					for ($i = 1; $i <= $count; $i++) {
						$code = $xls->sheets[0]['cells'][$i][1];

						$data = array(
							'uniacid' => $_W['uniacid'],
							'title' => $code,
							'pici' => $pici,
							'time' => TIMESTAMP,
						);
						$res = pdo_insert('haoman_dpm_pw',$data);
					}
					if ($res) {
						pdo_query("update " . tablename("haoman_dpm_pici") . " set codenum = codenum + {$count} where pici = :pici and uniacid =:uniacid", array(":pici" => $pici, ":uniacid" => $uniacid));
						$url = $this->createWebUrl('code');
						echo '<script>alert(\'导入成功！\')</script>';
						echo "<script>window.location.href= '{$url}'</script>";
					} else {
						$url = $this->createWebUrl('Import', array());
						echo '<script>alert(\'导入失败！\')</script>';
						echo "<script>window.location.href= '{$url}'</script>";
					}
				} else {
					echo '复制失败！';
					die;
				}
			} catch (Exception $e) {
				logging_run($e, '', 'upload_tiku');
			}
		} else {
			include $this->template('import');
		}
	}


	//失效口令删除
	public function doWebMiss() {
		global $_GPC, $_W;
		checklogin();
		$res = pdo_fetch('select * from ' . tablename('haoman_dpm_pw') . ' where uniacid = :uniacid and status = 2', array(':uniacid' => $_W['uniacid']));
		if($res){
			pdo_delete('haoman_dpm_pw',array('uniacid' => $_W['uniacid'] ,'status' =>'2'));
			message('删除成功',$this->createWebUrl("code"),'success');
		}else{
			message('暂无已失效口令',$this->createWebUrl("code"),'error');
		}
	}

	//每批次卡密删除
	public function doWebCodedie() {
		global $_GPC, $_W;
		checklogin();
		$pici = $_GPC['pici'];
		$res = pdo_fetch('select * from ' . tablename('haoman_dpm_pici') . ' where uniacid = :uniacid and pici = :pici', array(':uniacid' => $_W['uniacid'] ,':pici' => $pici));
		if($res){
			pdo_delete('haoman_dpm_pici', array('uniacid' => $_W['uniacid'],'pici' => $pici));
			pdo_delete('haoman_dpm_pw', array('uniacid' => $_W['uniacid'],'pici' => $pici));

			message('删除成功',$this->createWebUrl("code"),'success');
		}else{
			message('暂无口令',$this->createWebUrl("code"),'error');
		}
	}
//单独口令删除
	public function doWebDeletepw() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$pici = intval($_GPC['pici']);
		$rule = pdo_fetch("select * from " . tablename('haoman_dpm_pw') . " where id = :id ", array(':id' => $id));
		$codenum = pdo_fetch("select * from " . tablename('haoman_dpm_pici') . " where pici = :pici ", array(':pici' => $pici));
		if (empty($rule)) {
			message('抱歉，参数错误！');
		}
		pdo_delete('haoman_dpm_pw', array('id' => $id));
		if($rule['pici']!=0){
			pdo_update('haoman_dpm_pici', array('codenum' => $codenum['codenum'] - 1), array('pici' => $codenum['pici']));

		}
		message('口令删除成功！', referer(), 'success');
	}

//    抽奖码下载
	public function  doWebUDownload2()
	{
		global $_GPC,$_W;
		checklogin();
		$list = pdo_fetchall('select * from ' . tablename('haoman_dpm_pw') . ' where uniacid = :uniacid and status = 1 ORDER BY id ', array(':uniacid' => $_W['uniacid']));
		$tableheader = array('ID','批次','博饼码','适用规则','开始时间','结束时间','剩余数量','创建时间');
		$html = "\xEF\xBB\xBF";
		foreach ($tableheader as $value) {
			$html .= $value . "\t ,";
		}
		$html .= "\n";
		foreach ($list as $value) {
			$html .= $value['id'] . "\t ,";
			$html .= $value['pici'] . "\t ,";
			$html .=  $value['title'] . "\t ,";
			$html .=  $value['rulename'] . "\t ,";
			$html .=  date('Y-m-d H:i:s', $value['starttime']) . "\t ,";
			$html .=  date('Y-m-d H:i:s', $value['endtime']) . "\t ,";
			$html .=  $value['num'] . "\t ,";
			$html .= date('Y-m-d H:i:s', $value['createtime']) . "\n";

		}


		header("Content-type:text/csv");

		header("Content-Disposition:attachment;filename=全部博饼码.csv");

		$html = mb_convert_encoding($html, 'gb2312', 'UTF-8');

		echo $html;
		exit();
	}



	//检测用户浏览器
	public function checkBowser(){
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){

		}
	}



//删除测试数据
	public function doWebDelete_openid() {
		global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$del_openid = $_GPC['del_openid'];

		$reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		$fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where from_user=:from_user and rid=:rid", array(':from_user' => $del_openid,':rid'=>$rid));
		$datas = pdo_fetchall("select * from " . tablename('haoman_dpm_data') . " where fromuser=:fromuser and rid=:rid", array(':fromuser' => $del_openid,':rid'=>$rid));
		$award = pdo_fetchall("select * from " . tablename('haoman_dpm_award') . " where from_user=:from_user and rid=:rid", array(':from_user' => $del_openid,':rid'=>$rid));
		$message = pdo_fetchall("select * from " . tablename('haoman_dpm_messages') . " where from_user=:from_user and rid=:rid", array(':from_user' => $del_openid,':rid'=>$rid));
		// $password = pdo_fetchall("select * from " . tablename('haoman_dpm_password') . " where from_user=:from_user and rid=:rid", array(':from_user' => $del_openid,':rid'=>$rid));

		if (empty($fans)) {
			$data = array(
				'success' => 0,
				'msg' => '抱歉，要删除的帐号不存在或是已经被删除！',
			);
		}
		else{
			pdo_delete('haoman_dpm_fans', array('from_user' => $del_openid,'rid'=>$rid));
			// pdo_update('haoman_dpm_reply', array('fansnum' => $reply['fansnum'] - 1), array('id' => $reply['id']));

			if(!empty($datas)){
				pdo_delete('haoman_dpm_data', array('fromuser' => $del_openid,'rid'=>$rid));
			}
			if(!empty($award)){
				pdo_delete('haoman_dpm_award', array('from_user' => $del_openid,'rid'=>$rid));
			}
			if(!empty($message)){
				pdo_delete('haoman_dpm_messages', array('from_user' => $del_openid,'rid'=>$rid));
			}
			$data = array(
				'success' => 1,
				'msg' => '删除成功',
			);

		}
		echo json_encode($data);
	}

//删除活动
	public function doWebDelete() {
		global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$rule = pdo_fetch("select id, module from " . tablename('rule') . " where id = :id and uniacid=:uniacid", array(':id' => $rid, ':uniacid' => $_W['uniacid']));
		if (empty($rule)) {
			message('抱歉，要修改的规则不存在或是已经被删除！');
		}
		if (pdo_delete('rule', array('id' => $rid))) {
			pdo_delete('rule_keyword', array('rid' => $rid));
			//删除统计相关数据
			pdo_delete('stat_rule', array('rid' => $rid));
			pdo_delete('stat_keyword', array('rid' => $rid));
			//调用模块中的删除
			$module = WeUtility::createModule($rule['module']);
			if (method_exists($module, 'ruleDeleted')) {
				$module->ruleDeleted($rid);
			}
		}
		message('活动删除成功！', referer(), 'success');
	}
	//更改活动状态
	public function doWebSetshow() {
		global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$isshow = intval($_GPC['isshow']);

		if (empty($rid)) {
			message('抱歉，传递的参数错误！', '', 'error');
		}
		$temp = pdo_update('haoman_dpm_reply', array('isshow' => $isshow), array('rid' => $rid));
		message('状态设置成功！', referer(), 'success');
	}

	private function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
  }
  //随机字符串
  private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }


	//获取api_ticket
   public function getCardTicket($rid,$openid){
		global $_W,$_GPC;
	    $uniacid = $_W['uniacid'];

	   $card_idarr = pdo_fetchall("select id,couponid,awardspro from " . tablename('haoman_dpm_prize') . " where  rid = " . $rid ." and awardspro > 0 and awardstotal-prizedraw>0 and couponid <> '' ORDER BY Rand() ASC"  );

	   $card_rowid=-1;
	   if($card_idarr) {
		   $card_temparr = array();
		   foreach ($card_idarr as $index => $row) {
			   $item = array(
				   'id' => $row['id'],
				   'couponid' => $row['couponid'],
				   'v' => $row['awardspro'],
			   );
			   $card_temparr[] = $item;

		   }

		   foreach ($card_temparr as $key => $val) {
			   $randarr[$val['id']] = $val['v'];
		   }

		   $card_rowid = $this->Get_rand($randarr); //根据概率获取奖项id
		   $card_new = pdo_fetch("select * from " . tablename('haoman_dpm_prize') . " where  id=" . $card_rowid . " and rid = " . $rid);
		   $card_id = $card_new['couponid'];

	   }else{
		   return false;
	   }

		//获取access_token
		$data = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_cardticket')." WHERE weid='".$_W['uniacid']."' " );
		$appid = $_W['account']['key'];
		$appSecret = $_W['account']['secret'];
		load()->func('communication');
		//检测ticket是否过期
		if ($data['createtime'] < time()) {
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appSecret."";
			$res = json_decode($this->httpGet($url));
			$tokens = $res->access_token;
			if(empty($tokens))
			{
				return;
			}

			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$tokens."&type=wx_card";
			$res = json_decode($this->httpGet($url));
			$now = TIMESTAMP;
			$now = intval($now) + 7200;
			$ticket = $res->ticket;
			$insert = array(
				'weid' => $_W['uniacid'],
				'createtime' => $now,
				'ticket' => $ticket,
			);
			if(empty($data)){
				pdo_insert('haoman_dpm_cardticket',$insert);
			}else{
				pdo_update('haoman_dpm_cardticket',$insert,array('id'=>$data['id']));
			}

		}else{
			$ticket = $data['ticket'];
		}

		// 注意 URL 一定要动态获取，不能 hardcode.
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		//获得ticket后将参数拼成字符串进行sha1加密
		$now = time();
		$timestamp = $now;



		//随机字符串
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < 16; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		//随机字符串



		$nonceStr = $str;
		$card_id = $card_id;
		$openid = $openid;
		$string = "card_id=$card_id&jsapi_ticket=$ticket&noncestr=$nonceStr$openid=$openid&timestamp=$timestamp";

		$arr = array($card_id,$ticket,$nonceStr,$openid,$timestamp);//组装参数
		asort($arr, SORT_STRING);
		$sortString = "";
		foreach($arr as $temp){
			$sortString = $sortString.$temp;
		}
		$signature = sha1($sortString);
		$cardArry = array(
			'code' =>"",
			'openid' => $openid,
			'timestamp' => $now,
			'signature' => $signature,
			'cardId' => $card_id,
			'ticket' => $ticket,
			'nonceStr' => $nonceStr,
			'card_rowid' => $card_rowid,
		);
		return $cardArry;


	}


	public function get_jieyong() {
		global $_W, $_GPC;
		$path = "/addons/haoman_dpm";
		$filename = IA_ROOT . $path . "/data/sysset_" . $_W['uniacid'] . ".txt";
		if (is_file($filename)) {
			$content = file_get_contents($filename);
			if (empty($content)) {
				return false;
			}
			return json_decode(base64_decode($content), true);
		}
		return pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_jiequan') . " WHERE uniacid = :uniacid limit 1", array(':uniacid' => $_W['uniacid']));
	}


	public function doWebjieyong() {
		global $_W, $_GPC;
		$set = $this->get_jieyong();
		if (checksubmit('submit')) {
			$appid = trim($_GPC['appid']);
			$appsecret = trim($_GPC['appsecret']);

			$data = array(
				'uniacid' => $_W['uniacid'],
				'appid' => $appid,
				'appsecret' => $appsecret,
				'appid_share' => $appid,
				'appsecret_share' => $appsecret,
			);
			if (!empty($set)) {
				pdo_update('haoman_dpm_jiequan', $data, array('id' => $set['id']));
			} else {
				pdo_insert('haoman_dpm_jiequan', $data);
			}
			$this->write_cache("sysset_" . $_W['uniacid'], $data);
			message('更新借用设置成功！', 'refresh');
		}

		include $this->template('jiequan');
	}


	public function get_sysset() {   //读取借用数据appid和appsecret
		global $_W;
		return pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_jiequan') . " WHERE uniacid = :uniacid limit 1", array(':uniacid' =>$_W['uniacid']));
	}

	private function get_code($rid,$appid,$urltype) {  //第一步先获取Code
		global $_W;
		if(empty($urltype)){  //这边是回调地址，获取Code成功后跳转的页面，默认是到首页，但是在助力页面也需要用到，所以需要传入$_GPC['from_user']，这样才不会出现回调后，分享人信息丢失

			$url = $_W['siteroot'] . "app/index.php?i=" . $_W['uniacid'] . "&c=entry&m=haoman_dpm&do=index&id={$rid}";

		}else{

			$url =  $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $urltype));
		}
		$oauth2_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appid . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect";
		header("location: $oauth2_url");
		exit();
	}

	public function get_openid($rid, $code, $urltype) { //第二步或是OpenID和AccessToken，注意借用获取到的OpenID是认证服务号的OpenID
		global $_GPC, $_W;
		load()->func('communication');
		load()->model('account');
		$_W['account'] = account_fetch($_W['acid']);
		$appid = $_W['account']['key'];
		$appsecret = $_W['account']['secret'];

		if ($_W['account']['level'] != 4) {
			//不是认证服务号
			$set = $this->get_sysset();
			if (!empty($set['appid']) && !empty($set['appsecret'])) {
				$appid = $set['appid'];
				$appsecret = $set['appsecret'];
			}  else{
				//如果没有借用，判断是否认证服务号
				message('请使用认证服务号进行活动，或借用其他认证服务号权限!');
			}
		}
		if (empty($appid) || empty($appsecret)) {
			message('请到管理后台设置完整的 AppID 和AppSecret !');
		}

		if (!isset($code)) {
			$this->get_code($rid, $appid,$urltype);
		}
		$oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $appsecret . "&code=" . $code . "&grant_type=authorization_code";
		$content = ihttp_get($oauth2_code);
		$token = @json_decode($content['content'], true);
		if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
			message('未获取到 openid , 请刷新重试!','error');
		}
		return $token;
	}


	public function get_UserInfo($rid, $code, $urltype){ //第三步获取用户的昵称、头像、性别等信息，可以通过print_r($userInfo)来查看里面所有的字段
		global $_GPC, $_W;
		load()->func('communication');
		$token = $this->get_openid($rid, $code, $urltype);
		$accessToken = $token['access_token'];
		$openid = $token['openid'];
		$tokenUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $accessToken . "&openid=" . $openid . "&lang=zh_CN";
		$content = ihttp_get($tokenUrl);
		$userInfo = @json_decode($content['content'], true);
		$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
		$cookie = array("nickname" => $userInfo['nickname'],'avatar'=>$userInfo['headimgurl'],'openid'=>$userInfo['openid']);
		setcookie($cookieid, base64_encode(json_encode($cookie)), time() + 3600 * 24 * 365);
		return $userInfo;
	}


	public function doWebHb(){
		global $_W,$_GPC;
		load()->func('tpl');
		load()->model('account');
		$sql = "SELECT * FROM ".tablename('haoman_dpm_hb')." WHERE uniacid = :uniacid";
		$params = array(':uniacid'=>$_W['uniacid']);
		$settings = pdo_fetch($sql,$params);

		// $settings = unserialize($settings['set']);
		if($_W['ispost']) {
			//字段验证, 并获得正确的数据$dat
			load()->func('file');
			mkdirs(ROOT_PATH . '/cert');
			$r = true;
			if (!empty($_GPC['cert'])) {
				$ret = file_put_contents(ROOT_PATH . '/cert/apiclient_cert.pem.' . $_W['uniacid'], trim($_GPC['cert']));
				$r = $r && $ret;
			}
			if (!empty($_GPC['key'])) {
				$ret = file_put_contents(ROOT_PATH . '/cert/apiclient_key.pem.' . $_W['uniacid'], trim($_GPC['key']));
				$r = $r && $ret;
			}
			if (!empty($_GPC['ca'])) {
				$ret = file_put_contents(ROOT_PATH . '/cert/rootca.pem.' . $_W['uniacid'], trim($_GPC['ca']));
				$r = $r && $ret;
			}
			if (!$r) {
				message('证书保存失败, 请保证 /addons/haoman_dpm/cert/ 目录可写');
			}

			$data = array();
			// $data['set'] = trim($_GPC['password']);;
			$data['password'] = trim($_GPC['password']);;
			$data['uniacid'] = $_W['uniacid'];
			$data['appid'] = trim($_GPC['appid']);
			$data['secret'] = trim($_GPC['secret']);
			$data['mchid'] = intval($_GPC['mchid']);
			$data['ip'] = trim($_GPC['ip']);
			$data['sname'] = trim($_GPC['sname']);
			$data['wishing'] = trim($_GPC['wishing']);
			$data['actname'] = trim($_GPC['actname']);
			$data['logo'] = trim($_GPC['logo']);
			$data['createtime'] = time();

			if(empty($settings)){
				pdo_insert('haoman_dpm_hb',$data);
			}else{
				pdo_update('haoman_dpm_hb',$data,array('uniacid'=>$_W['uniacid']));
			}

			message('提交成功',referer(),success);
		}

		if (empty($settings['ip'])) {
			$settings['ip'] = $_SERVER['SERVER_ADDR'];
		}
		include $this->template('hsetting');
	}

	public function substr_cut($str_cut,$length)
	{
		if (strlen($str_cut) > $length)
		{
			for($i=0; $i < $length; $i++)
				if (ord($str_cut[$i]) > 128)    $i++;
			$str_cut = substr($str_cut,0,$i)."..";
		}
		return $str_cut;
	}

	protected function sendhb($record, $user){  //红包发送代码
		global $_W;
		$uniacid = $_W['uniacid'];
		$sql = "SELECT * FROM ".tablename('haoman_dpm_hb')." WHERE uniacid = :uniacid";
		$params = array(':uniacid'=>$_W['uniacid']);
		$api = pdo_fetch($sql,$params);
		// $api = unserialize($api['set']);

		if (empty($api)) {
			return error(-2, '红包信息没有填！');
		}

		if(empty($api['sname'])){
			$send_name = $this->substr_cut($_W['account']['name'],30);
		}else{
			$send_name = $api['sname'];
		}

		$actname = empty($api['actname']) ? '参与疯狂抢红包活动' : $api['actname'];

		if(empty($api['wishing'])){
			$wishing = '恭喜您,抽中了一个' . $record['fee'] . '元红包!';
		}else{
			$wishing = $api['wishing'] . $record['fee'] . '元红包!';
		}


		$fee                   = floatval($record['fee'])*100;//红包金额，单位为分;
		$url                   = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
		$pars                  = array();
		$pars['nonce_str']     = random(32);
		$pars['mch_billno']    = $api['mchid'] . date('Ymd') . sprintf('%d', time());
		$pars['mch_id']        = $api['mchid'];
		$pars['wxappid']       = $api['appid'];
		$pars['nick_name']     = $_W['account']['name'];
		$pars['send_name']     = $send_name;
		$pars['re_openid']     = $record['openid'];
		$pars['total_amount']  = $fee;
		$pars['min_value']     = $pars['total_amount'];
		$pars['max_value']     = $pars['total_amount'];
		$pars['total_num']     = 1;
		$pars['wishing']       = $wishing;
		$pars['client_ip']     = $api['ip'];
		$pars['act_name']      = $actname;
		$pars['remark']        = '恭喜' . $user['nickname'] . '您的' . $record['fee'] . '元红包已经发放，请注意查收';
		$pars['logo_imgurl']   = tomedia($api['logo']);
		ksort($pars, SORT_STRING);
		$string1 = '';
		foreach ($pars as $k => $v) {
			$string1 .= "{$k}={$v}&";
		}
		$string1 .= "key={$api['password']}";
		$pars['sign']              = strtoupper(md5($string1));
		$xml                       = array2xml($pars);
		$extras                    = array();
		$extras['CURLOPT_CAINFO']  = ROOT_PATH . '/cert/rootca.pem.' . $uniacid;
		$extras['CURLOPT_SSLCERT'] = ROOT_PATH . '/cert/apiclient_cert.pem.' . $uniacid;
		$extras['CURLOPT_SSLKEY']  = ROOT_PATH . '/cert/apiclient_key.pem.' . $uniacid;
		load()->func('communication');

		// $this->message(array("success" => 2, "msg" => $api['ip']), "");

		$procResult = null;
		$resp       = ihttp_request($url, $xml, $extras);
		if (is_error($resp)) {
			$procResult = $resp;

		} else {

			$xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
			$dom = new DOMDocument();
			if ($dom->loadXML($xml)) {
				$xpath = new DOMXPath($dom);
				$code  = $xpath->evaluate('string(//xml/return_code)');
				$return_msg  = $xpath->evaluate('string(//xml/return_msg)');
				$ret   = $xpath->evaluate('string(//xml/result_code)');

				if (strtolower($code) == 'success' && strtolower($ret) == 'success') {
					$procResult = true;

				} else {
					$error      = $xpath->evaluate('string(//xml/err_code_des)');
					$procResult = error(-2, $error);
				}
			} else {
				$procResult = error(-1, 'error response');
			}
		}


		$packpage['error_msg']=$return_msg;
		// $packpage['error_msg']=$error;
		if (is_error($procResult)) {
			$packpage['isok']=false;
			return $packpage;
		} else {
			$packpage['isok']=true;
			return $packpage;
		}
	}
}
