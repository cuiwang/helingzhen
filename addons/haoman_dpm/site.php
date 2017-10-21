<?php
defined('IN_IA') or exit('Access Denied');
define('ROOT_PATH', str_replace('site.php', '', str_replace('\\', '/', __FILE__)));
require_once "phpqrcode.php";/*引入PHP QR库文件*/
//require_once '../framework/library/qrcode/phpqrcode.php';
require_once "jssdk.php";
require_once ROOT_PATH."custom/custom.inc.php"; //引入定制判断文件
class haoman_dpmModuleSite extends WeModuleSite {


    
    /******************************* 定制开始区域  *********************************************/

    //定义包含文件方法

    // public function __mobile($f_name){
    //     if(ISCUSTOM == 1){
    //         global $_W, $_GPC;
    //         $file_mob = ROOT_PATH.'custom/'.strtolower(substr($f_name, 8)).'.php';
    //         if(file_exists($file_mob)){
    //             include_once 'custom/'.strtolower(substr($f_name, 8)).'.php';
    //         }
    //     }else{
    //         $file_mob2 = ROOT_PATH.'mobile/'.strtolower(substr($f_name, 8)).'.php';
    //         if(file_exists($file_mob2)){
    //             include_once 'mobile/'.strtolower(substr($f_name, 8)).'.php';
    //         }
    //     }
    // }

    public function __mobile($f_name){
        
        global $_W, $_GPC;
        $file_mob = ROOT_PATH.'custom/'.strtolower(substr($f_name, 8)).'.php';
        if(file_exists($file_mob) && ISCUSTOM == 1){
            include_once 'custom/'.strtolower(substr($f_name, 8)).'.php';
        }else{
            $file_mob2 = ROOT_PATH.'mobile/'.strtolower(substr($f_name, 8)).'.php';
            if(file_exists($file_mob2)){
                include_once 'mobile/'.strtolower(substr($f_name, 8)).'.php';
            }
        }
    }

    public function __web($f_name){
        global $_W, $_GPC;
        $file_web = ROOT_PATH.'custom/'.strtolower(substr($f_name, 5)).'.php';
        if(file_exists($file_web) && ISCUSTOM == 1){
            include_once 'custom/'.strtolower(substr($f_name, 5)).'.php';
        }else{
            $file_web2 = ROOT_PATH.'web/'.strtolower(substr($f_name, 5)).'.php';
            if(file_exists($file_web2)){
                include_once 'web/'.strtolower(substr($f_name, 5)).'.php';
            }
        }
    }

    //定义包含文件方法

    public function doWebinti_sql(){ //所有定制的数据库安装文件  
        if(ISCUSTOM == 1){  //判断是否是定制版本
            $this->__web(__FUNCTION__);
            exit;
        }
    }

    /******************************* 20161230定制打赏功能的  *********************************************/
    public function doMobiledpm_dsindex(){ 
        if(ISCUSTOM == 1 && CUSTOM_VERSION == 'DS'){  //判断是否是定制版本
            $this->__mobile(__FUNCTION__);
            exit;
        }
    }

    public function doMobiledpm_dsList(){ //20161230定制打赏功能的
        if(ISCUSTOM == 1 && CUSTOM_VERSION == 'DS'){  //判断是否是定制版本
            $this->__mobile(__FUNCTION__);
            exit;
        }
    }

    public function doMobiledpm_dsgetdata(){ //20161230定制打赏功能的
        if(ISCUSTOM == 1 && CUSTOM_VERSION == 'DS'){  //判断是否是定制版本
            $this->__mobile(__FUNCTION__);
            exit;
        }
    }

    public function doMobiledsindex() {
        if(ISCUSTOM == 1 && CUSTOM_VERSION == 'DS'){  //判断是否是定制版本
            $this->__mobile(__FUNCTION__);
            exit;
        }
    }

    public function doMobiledsindex2() {
        if(ISCUSTOM == 1 && CUSTOM_VERSION == 'DS'){  //判断是否是定制版本
            $this->__mobile(__FUNCTION__);
            exit;
        }
    }

    public function doMobileConfirm_ds() {
        if(ISCUSTOM == 1 && CUSTOM_VERSION == 'DS'){  //判断是否是定制版本
            $this->__mobile(__FUNCTION__);
            exit;
        }
    }

    public function doWebdsManage() {
        if(ISCUSTOM == 1){  //判断是否是定制版本
            $this->__web(__FUNCTION__);
            exit;
        }
    }

    public function doWebNewdspeople() {
        if(ISCUSTOM == 1){  //判断是否是定制版本
            $this->__web(__FUNCTION__);
            exit;
        }
    }

    public function doWebdspeopleshow(){
        if(ISCUSTOM == 1){  //判断是否是定制版本
            $this->__web(__FUNCTION__);
            exit;
        }
    }

    public function doWebds_order() {
        if(ISCUSTOM == 1){  //判断是否是定制版本
            $this->__web(__FUNCTION__);
            exit;
        }
    }


    /******************************* 20161230定制打赏功能的  *********************************************/


    /******************************* 20170103定制攒能量功能的  *********************************************/

    public function doMobiledpm_znlindex(){
        if(ISCUSTOM == 1 && CUSTOM_VERSION == 'ZNL'){  //判断是否是定制版本
            $this->__mobile(__FUNCTION__);
            exit;
        }
    }

    public function doMobileznlruning2(){
        if(ISCUSTOM == 1 && CUSTOM_VERSION == 'ZNL'){ 
         //判断是否是定制版本
            $this->__mobile(__FUNCTION__);
            exit;
        }
    }

    public function doMobileznlhaishen(){
        if(ISCUSTOM == 1 && CUSTOM_VERSION == 'ZNL'){  //判断是否是定制版本
            $this->__mobile(__FUNCTION__);
            exit;
        }
    }

    public function doMobileznlindex() {
        if(ISCUSTOM == 1 && CUSTOM_VERSION == 'ZNL'){  //判断是否是定制版本
            $this->__mobile(__FUNCTION__);
            exit;
        }
    }


    public function doMobileznlSaveUser(){
        if(ISCUSTOM == 1 && CUSTOM_VERSION == 'ZNL'){  //判断是否是定制版本
            $this->__mobile(__FUNCTION__);
            exit;
        }
    }

    public function doMobiledpm_ZnlUserList(){
        if(ISCUSTOM == 1 && CUSTOM_VERSION == 'ZNL'){  //判断是否是定制版本
            $this->__mobile(__FUNCTION__);
            exit;
        }
    }




    /******************************* 20170103定制攒能量功能的  *********************************************/

    /******************************* 定制结束区域  *********************************************/




	//非微信打开和限制IP地打开
	public function doMobileother(){
		$this->__mobile(__FUNCTION__);
	}


	public function doMobilegetlbs(){
		$this->__mobile(__FUNCTION__);
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
	}

	public function doMobilelogin(){
		$this->__mobile(__FUNCTION__);
	}

    public function doMobileframe(){
        $this->__mobile(__FUNCTION__);
    }

	public function doMobilecklogin(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobiledpm_index(){
		$this->__mobile(__FUNCTION__);
	}

	public function doMobiledpm_messages(){
        $this->__mobile(__FUNCTION__);
	}

	public function doMobiledpm_getmessages(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobiledpm_tanmu(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobiledpm_dm_getmessages(){
        $this->__mobile(__FUNCTION__);
    }

    //许愿树
    public function doMobiledpm_wedding(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobiledpm_wd_getmessages(){
        $this->__mobile(__FUNCTION__);
    }


    public function doMobiledpm_3dqd(){
		$this->__mobile(__FUNCTION__);
	}

	public function doMobiledpm_get3dqd(){
		$this->__mobile(__FUNCTION__);
    }


    public function doMobiledpm_choujiang(){
        $this->__mobile(__FUNCTION__);
	}

    //20170407新增手签功能开始

    public function doMobiledpm_shouqian(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobiledpm_get_shouqian(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobilemob_shouqian(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobilemob_shouqian_send(){
        $this->__mobile(__FUNCTION__);
    }
    public function doWebshouqian(){
        $this->__web(__FUNCTION__);
    }
    public function doWebshouqianshow(){
        $this->__web(__FUNCTION__);
    }
    public function doWebdel_shouqian(){
        $this->__web(__FUNCTION__);
    }
    //20170407新增手签功能结束


    //2017-04-15  新增霸屏涂鸦
    public function doMobilemob_bp_sq(){
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilemob_bp_sq_send(){
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileconfirm_bp_sq(){
        $this->__mobile(__FUNCTION__);
    }

    //2017-04-08 新增平面签到  开始
    public function doMobiledpm_new_index2(){
        $this->__mobile(__FUNCTION__);
    }
    public function doMobiledpm_getnew_qd(){
        $this->__mobile(__FUNCTION__);
    }

    //2017-04-08 新增平面签到  结束


    //2017-04-28 新增酒吧商城    开始

    public function doMobilemob_bjlist(){
        $this->__mobile(__FUNCTION__);
    }

    public function doWebshopping(){
        $this->__web(__FUNCTION__);
    }
    public function doWebCategory(){
        $this->__web(__FUNCTION__);
    }
    public function doWebgoods(){
        $this->__web(__FUNCTION__);
    }
    public function doWebshop_wditer(){
        $this->__web(__FUNCTION__);
    }
    public function doWebshop_order(){
        $this->__web(__FUNCTION__);
    }
    public function doWebshop_setting(){
        $this->__web(__FUNCTION__);
    }
    public function doWebdownload_shop(){
        $this->__web(__FUNCTION__);
    }
    public function doWebSetGoodsProperty() {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $type = $_GPC['type'];
        $data = intval($_GPC['data']);
        if (in_array($type, array('new', 'hot', 'recommand', 'discount'))) {
            $data = ($data==1?'0':'1');
            pdo_update("haoman_dpm_shop_goods", array("is" . $type => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
            die(json_encode(array("result" => 1, "data" => $data)));
        }
        if (in_array($type, array('status'))) {
            $data = ($data==1?'0':'1');
            pdo_update("haoman_dpm_shop_goods", array($type => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
            die(json_encode(array("result" => 1, "data" => $data)));
        }
        if (in_array($type, array('type'))) {
            $data = ($data==1?'2':'1');
            pdo_update("haoman_dpm_shop_goods", array($type => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
            die(json_encode(array("result" => 1, "data" => $data)));
        }
        die(json_encode(array("result" => 0)));
    }

    public function doMobilemorecategory(){
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileconfirm_shop_pay(){
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileaddshoppingcar(){
        $this->__mobile(__FUNCTION__);
    }


    //2017-04-28 新增酒吧商城    结束

//2017-05-01新增统计报表  开始

    public function doWebstatistics(){
        $this->__web(__FUNCTION__);
    }
//2017-05-01新增统计报表  结束

//2017-05-03 投票修改 开始

    public function doMobiledpm_vote_result(){
        $this->__mobile(__FUNCTION__);
    }
    public function doWebchange_votenum(){
        $this->__web(__FUNCTION__);
    }
//2017-05-03 投票修改   结束


	//2017-01-29 新增对对碰

    public function doMobiledpm_ddp(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobileGetPairs(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobileGetPairFans(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobileSubmitPairs(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobileDeleteALL_Pair(){
        $this->__mobile(__FUNCTION__);
    }

    public function doWebddp(){
        $this->__web(__FUNCTION__);
    }

    public function doWebddpshow(){
        $this->__web(__FUNCTION__);
    }

    public function doWebdele_webddp(){
        $this->__web(__FUNCTION__);
    }

    public function doMobiledpm_dzp(){
        $this->__mobile(__FUNCTION__);
    }

    //2017.4.27

    public function doMobiledpm_photo_wall(){
        $this->__mobile(__FUNCTION__);
    }


    //新增2017-02-21
    public function doMobiledpm_xyh(){
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileSubmit_win(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobiledpm_xysjh(){
        $this->__mobile(__FUNCTION__);
    }
    public function doWebxyh_list(){
        $this->__web(__FUNCTION__);
    }
    public function doWebcodeshow2(){
        $this->__web(__FUNCTION__);
    }
    public function doWebdelete_xyh(){
        $this->__web(__FUNCTION__);
    }
    //新增2017-02-21
    public function doMobileGetTurntableAward(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobileGetTurntableFans(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobileGetTurntableInternalFans(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobileSubmitTurntableFans(){
        $this->__mobile(__FUNCTION__);
    }


	//2017-01-04
    //新增霸屏部分

    public function doMobiledpm_bp(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobileaddweionwallNewsMsg(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobilegetWeiOnWallMsg(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobilenoshowpascreens(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobilegetmarquee(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobilesetshowpascreen(){
        $this->__mobile(__FUNCTION__);
    }

    public function doWebbapin(){
        $this->__web(__FUNCTION__);
    }

    public function doWebbapinshow(){
        $this->__web(__FUNCTION__);
    }


    //添加和编辑霸屏
    public function doWebNewbapin() {
        $this->__web(__FUNCTION__);
    }


    //2017-04-27 新增照片  开始
    public function doWebphoto(){
        $this->__web(__FUNCTION__);
    }

    public function doWebphotoshow(){
        $this->__web(__FUNCTION__);
    }


    //添加和编辑照片
    public function doWebNewphoto() {
        $this->__web(__FUNCTION__);
    }
    public function doWebsetting_photo() {
        $this->__web(__FUNCTION__);
    }
    public function doMobilemob_photo(){
        $this->__mobile(__FUNCTION__);
    }

    //2017-04-27 新增照片  结束



    //2017-02-27
    //新增后台打赏管理
    public function doWebdashang(){
        $this->__web(__FUNCTION__);
    }
    public function doWebNewds(){
        $this->__web(__FUNCTION__);
    }
    public function doWebdsshow(){
        $this->__web(__FUNCTION__);
    }
    public function doWebUserinfo2() {
        $this->__web(__FUNCTION__);
    }

    public function doWebDeleteds_jilu() {
        $this->__web(__FUNCTION__);
    }

    //新增霸屏数据统计开始

    public function doWebbpreportes(){
        $this->__web(__FUNCTION__);
    }

    public function doWebpaytxsetlog(){
        $this->__web(__FUNCTION__);
    }

    //新增霸屏数据统计结束


    //2017-03-28  新增送礼物和表白送礼  开始

    public function doWebbb_gift(){
        $this->__web(__FUNCTION__);
    }
    public function doWebNewbb_gift(){
        $this->__web(__FUNCTION__);
    }
    public function doWebbb_giftshow(){
        $this->__web(__FUNCTION__);
    }
    public function doWebUserinfo5() {
        $this->__web(__FUNCTION__);
    }

    public function doWebDeletebb_gift_jilu() {
        $this->__web(__FUNCTION__);
    }
    //2017-03-28  新增送礼物和表白送礼  结束

//12.15修改过
	public function doMobilehaishen(){
    $this->__mobile(__FUNCTION__);
}

    //重置奖品
    public function doMobileresetAwards(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobiledpm_notice_awarduser(){
        $this->__mobile(__FUNCTION__);
    }

    

	public function doMobiledpm_getchoujiang(){ 
		$this->__mobile(__FUNCTION__);
    }

     //02.24新增抽奖箱开始
    public function doMobiledpm_cjx(){ 
        $this->__mobile(__FUNCTION__);
    }

    public function doMobiledpm_cjxgetchoujiang(){ 
        $this->__mobile(__FUNCTION__);
    }

    public function doMobiledpm_cjxlist(){ 
        $this->__mobile(__FUNCTION__);
    }

    public function doWebcjxcode(){
        $this->__web(__FUNCTION__);
    }

    //02.24新增抽奖箱结束

       //2017-05-22
    //新个人中心  开始
    public function doMobilesetting_fans(){
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilebalance(){
        $this->__mobile(__FUNCTION__);
    }
    public function doMobiletixian_more(){
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilemy_order(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobilemy_prizes(){
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilemy_bpds(){
        $this->__mobile(__FUNCTION__);
    }
    public function doMobiledeposit(){
    $this->__mobile(__FUNCTION__);
    }
    public function doMobileadd_deposit(){
    $this->__mobile(__FUNCTION__);
    }
    public function doMobilesave_deposit(){
        $this->__mobile(__FUNCTION__);
    }
    public function doWebweb_deposit(){
        $this->__web(__FUNCTION__);
    }
    public function doWebdeposit_detail(){
    $this->__web(__FUNCTION__);
    }
    public function doWebmobile_code(){
    $this->__web(__FUNCTION__);
    }
    public function doWebmobile_code_setting(){
    $this->__web(__FUNCTION__);
    }
    public function addDetail($rows){
        if(empty($rows)){
            return false;
        }
        //数据量较大,采取批量插入
        $data = implode(',', $rows);
        $sql = "INSERT IGNORE INTO `ims_haoman_dpm_pw`(uniacid,rid,pici,pwid,title,num,status,iscqr,createtime)  VALUES {$data}";
        $result = pdo_query($sql);
        return true;
    }
//新个人中心结束

    //参数设置
    public function doWebparameter_setting(){
        $this->__web(__FUNCTION__);
    }
    public function doWebpersonal_setting(){
        $this->__web(__FUNCTION__);
    }
    public function doWebcustom(){
        $this->__web(__FUNCTION__);
    }
    public function doWebcustom_setting(){
        $this->__web(__FUNCTION__);
    }

    public function dpm_getfans($rid,$limit,$iscjnum,$turntable,$awardid){  //所有大屏幕抽奖获取中奖者的方法
    	// $rid是规则ID;
    	// $limit是一次抽出的人数;
    	// $iscjnum控制每个人是否可以同时中多个奖品，0为每个人只能中一个奖品，这个只是指同一个活动；
    	// $turntable活动类型，1为现场抽，2为抢红包
    	// $awardid奖品ID
    	global $_GPC, $_W;
		$uniacid = $_W['uniacid'];

        $fans = pdo_fetchall("SELECT id,avatar,from_user,nickname,realname,address FROM " . tablename('haoman_dpm_fans') . " WHERE rid = :rid and uniacid = :uniacid and isbaoming=0 and is_back !=1 ORDER BY id DESC",array(':rid'=>$rid,':uniacid'=>$uniacid));




        $reply = pdo_fetch("SELECT iscjnum,is_realname,is_award FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));

        if($reply['is_realname']==1){
            foreach ($fans as &$v){
                $v['nickname']= $v['realname'];
                if(empty($v['nickname'])){
                    $v['nickname'] = "匿名用户!";
                }
            }
            unset($v);
        }


        $prize = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_prize') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable and id = :id  ORDER BY id DESC",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>$turntable,':id'=>$awardid));

        if($reply['iscjnum'] == 0){
            $res = pdo_fetchall("SELECT from_user FROM " . tablename('haoman_dpm_award') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable ORDER BY id DESC",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>$turntable));
            if(!empty($res)){
                foreach ($res as $k => $v) {
                    $ckres[$k] = $v['from_user'];
                }
                foreach ($fans as $k => $v) {
                    if(in_array($v['from_user'],$ckres)){
                        unset($fans[$k]);
                    }
                }
            }
            $num12 = intval(count($fans,0));
            if($num12<$limit){
                return 0;
            }
        }else {
            $res = pdo_fetchall("SELECT from_user FROM " . tablename('haoman_dpm_award') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable and titleid = :titleid ORDER BY id DESC",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>$turntable,':titleid'=>$awardid));
            if(!empty($res)) {
                foreach ($res as $k => $v) {
                    $ckres[$k] = $v['from_user'];
                }
                foreach ($fans as $k => $v) {
                    if (in_array($v['from_user'], $ckres)) {
                        unset($fans[$k]);

                    }

                }
            }
        }


        //内定部分开始
        $nd_prize = pdo_fetchall("SELECT prizeid,openid FROM " . tablename('haoman_dpm_draw_default') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable and status=1 and prizeid =:prizeid ORDER BY id DESC",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>$turntable,':prizeid'=>$awardid));

        if($nd_prize){

            foreach ($nd_prize as $k => $v) {

                if(in_array($v['openid'],$ckres)&&!empty($res)){
                    unset($nd_prize[$k]);
                    //       $opend[$k]=$v['openid'];
                }
                else{
                    $openid[$k]=$v['openid'];
                }
            }
            foreach ($fans as $k=>$v){
                if($openid){
                    if(in_array($v['from_user'],$openid)){
                        $nd_fans[$k] = $v;
                        unset($fans[$k]);
                    }
                }

            }

            $numx = intval(count($nd_prize,0));

            if($numx<=$limit&&$numx!=0){
                if($nd_fans){
                    $new_nd_fans = array_rand($nd_fans,$numx);
                    if($limit==1){
                        $results[0] = $nd_fans[$new_nd_fans];
                        $insert = array(
                            'rid' => $rid,
                            'uniacid' => $_W['uniacid'],
                            'turntable' => $turntable,
                            'from_user' => $results[0]['from_user'],
                            'avatar' => $results[0]['avatar'],
                            'nickname' => $results[0]['nickname'],
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
//                        $actions = "恭喜您，参加大屏幕抽奖，抽中：".$prize['prizename']."，请留意领奖时间！";
//                      if($reply['is_award']==0){
//                          $this->sendText($results[0]['from_user'],$actions);
//                      }
                    }
                    else{

                        if($numx==1){
                            $results[0] = $nd_fans[$new_nd_fans];
                            $insert = array(
                                'rid' => $rid,
                                'uniacid' => $_W['uniacid'],
                                'turntable' => $turntable,
                                'from_user' => $results[0]['from_user'],
                                'avatar' => $results[0]['avatar'],
                                'nickname' => $results[0]['nickname'],
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
       //                     $actions = "恭喜您，参加大屏幕抽奖，抽中：".$prize['prizename']."，请留意领奖时间！";
//                            if($reply['is_award']==0) {
//                                $this->sendText($results[0]['from_user'], $actions);
//                            }
                        }

                        else{
                            foreach ($new_nd_fans as $k => $v) {
                                $results[$k] = $nd_fans[$v];
                                $insert = array(
                                    'rid' => $rid,
                                    'uniacid' => $_W['uniacid'],
                                    'turntable' => $turntable,
                                    'from_user' => $results[$k]['from_user'],
                                    'avatar' => $results[$k]['avatar'],
                                    'nickname' => $results[$k]['nickname'],
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
          //                      $actions = "恭喜您，参加大屏幕抽奖，抽中：".$prize['prizename']."，请留意领奖时间！";
//                                if($reply['is_award']==0) {
//                                    $this->sendText($results[$k]['from_user'], $actions);
//                                }
                            }
                        }

                    }

                    $limit=$limit-$numx;

                    if($limit==0){
                        return $results;
                    }
                }


            }elseif ($numx==0){

                $limit=$limit;
            }
            else{
                if($nd_fans){
                    $new_nd_fans = array_rand($nd_fans,$limit);
                    if($limit==1){
                        $result[0] = $nd_fans[$new_nd_fans];
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
                       // $actions = "恭喜您，参加大屏幕抽奖，抽中：".$prize['prizename']."，请留意领奖时间！";
//                        if($reply['is_award']==0) {
//                            $this->sendText($result[0]['from_user'], $actions);
//                        }
                    }
                    else{

                        foreach ($new_nd_fans as $k => $v) {
                            $result[$k] = $nd_fans[$v];
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
                         //   $actions = "恭喜您，参加大屏幕抽奖，抽中：".$prize['prizename']."，请留意领奖时间！";
//                            if($reply['is_award']==0) {
//                                $this->sendText($result[$k]['from_user'], $actions);
//                            }
                        }
                    }

                    return $result;
                }

            }



        } else{

            $nd_prizes = pdo_fetchall("SELECT prizeid,openid FROM " . tablename('haoman_dpm_draw_default') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable and status=1  ORDER BY id DESC",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>$turntable));
            
                if($nd_prizes){
                    foreach ($nd_prizes as $k => $v) {

                        if(in_array($v['openid'],$ckres)&&!empty($res)){
                            unset($nd_prizes[$k]);
                            //       $opend[$k]=$v['openid'];
                        }
                        else{

                            $openid[$k]=$v['openid'];
                        }
                    }
                    foreach ($fans as $k=>$v){
                        if($openid){
                            if(in_array($v['from_user'],$openid)){
                                unset($fans[$k]);
                            }
                        }

                    }
                }
        }


//        //内定部分结束


		$num = intval(count($fans,0));
		if($num < $limit){
			$limit = $num;
		}

		$newfans = array_rand($fans,$limit);



		if($limit == 1){

                $result[0] = $fans[$newfans];

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
          //  $actions = "恭喜您，参加大屏幕抽奖，抽中：".$prize['prizename']."，请留意领奖时间！";
//            if($reply['is_award']==0) {
//                $this->sendText($result[0]['from_user'], $actions);
//            }
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
         //       $actions = "恭喜您，参加大屏幕抽奖，抽中：".$prize['prizename']."，请留意领奖时间！";
//                if($reply['is_award']==0) {
//                    $this->sendText($result[$k]['from_user'], $actions);
//                }
				}
		}
        if($results){
            return array_merge($result,$results);
        }
        return $result;
    }


    public function doMobiledpm_getCjAwardsList(){
		$this->__mobile(__FUNCTION__);
    }


   // ***********************************12.19摇一摇新增开始

    public function doMobiledpm_yyy(){
        $this->__mobile(__FUNCTION__);
    }


    public function doMobileyyyStatus(){
        $this->__mobile(__FUNCTION__);
    }


    public function doMobileyyyRuning(){
        $this->__mobile(__FUNCTION__);
    }


    public function doMobileyyydpmreset(){
        $this->__mobile(__FUNCTION__);
    }


    public function doMobileyyyResult(){
        $this->__mobile(__FUNCTION__);
    }


    public function doMobileyyyhaishen(){
        $this->__mobile(__FUNCTION__);
    }

        //抽奖页面
    public function doMobileyyyIndex() {
        $this->__mobile(__FUNCTION__);
    }


    public function doMobileyyySaveUser(){
        $this->__mobile(__FUNCTION__);
    }


    public function doMobileyyyGetStatus(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobileyyyreset(){
        $this->__mobile(__FUNCTION__);
    }


    public function doMobileyyyMobResult(){
        $this->__mobile(__FUNCTION__);
    }

    //摇一摇列表
    public function doWebyaoyiyao(){
        $this->__web(__FUNCTION__);
    }


    //查看摇一摇
    public function doWebyaoyiyaoshow(){
        $this->__web(__FUNCTION__);
    }
    //查看摇一摇详情
    public function doWebyyydetails(){
        $this->__web(__FUNCTION__);
    }

    public function doWebdel_yyy(){
        $this->__web(__FUNCTION__);
    }

    // ***********************************12.19摇一摇新增结束



    public function doMobiledpm_qianghongbao(){
		$this->__mobile(__FUNCTION__);
	}

    public function doMobiledpm_vote(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobiledpm_voteList(){
        $this->__mobile(__FUNCTION__);
    }

	public function doMobilehbstatus(){
		$this->__mobile(__FUNCTION__);
	}


	public function doMobilehbchongzhi(){
		$this->__mobile(__FUNCTION__);
	}


	public function doMobiledpm_getHbList(){
		$this->__mobile(__FUNCTION__);
    }

    public function doMobiledpm_getWinHbList(){
		$this->__mobile(__FUNCTION__);
    }


    public function doMobiledpm_jiabing(){
		$this->__mobile(__FUNCTION__);
	}

    public function doMobilefile_get_contents(){
        $this->__mobile(__FUNCTION__);
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

	
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: " . $this->createMobileUrl('index', array('id' => $rid)) . "");
		exit();
	}


	

	public function doMobilegetShareImgUrl() {
        $this->__mobile(__FUNCTION__);
	}


	//实物兑奖
	public function doWebSetstatus() {
		$this->__web(__FUNCTION__);
	}
	
//	活动管理
	public function doWebManage() {
		$this->__web(__FUNCTION__);
	}


	//粉丝管理
	public function doWebFanslist() {
		$this->__web(__FUNCTION__);
	}

    //2.04新增修改性别功能

    public function doWebSetfansSex() {
        $this->__web(__FUNCTION__);
    }

	//12.20新增拉黑功能

    public function doWebSetfansStatus() {
        $this->__web(__FUNCTION__);
    }

   //导出粉丝数据
	public function doWebDownload(){
		$this->__web(__FUNCTION__);
	}

//导出粉丝数据
    public function doWebDownload_error(){
        $this->__web(__FUNCTION__);
    }


    public function  doWebDownload2(){
        $this->__web(__FUNCTION__);
    }

	//导出中奖记录
	public function doWebDownload21(){
		$this->__web(__FUNCTION__);
	}

    //导出霸屏记录
    public function doWebbp_download(){
        $this->__web(__FUNCTION__);
    }

    //导出提现记录
	public function doWebDownload3(){
		$this->__web(__FUNCTION__);
	}

	public function doWebAxq() {
		$this->__web(__FUNCTION__);
	}

	public function doWebAxq2() {
		$this->__web(__FUNCTION__);
	}

	public function doWebhelp() {
		global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$uniacid = $_W['uniacid'];

		include $this->template('help');
	}



	public function doWebAwardlist() {
		$this->__web(__FUNCTION__);
	}
	
	public function doWebCashprize() {
		$this->__web(__FUNCTION__);
	}

	public function doMobileduijiang() {
		$this->__mobile(__FUNCTION__);
	}


    //清除桌面软件数据
    public function doWebdel_txt(){
        $this->__web(__FUNCTION__);
    }

    //报名后现场确认
    public function doMobileve_baoming(){
        $this->__mobile(__FUNCTION__);
    }

//  我的奖品
	public function doMobilemybobing() {
		$this->__mobile(__FUNCTION__);
	}



//  规则
	public function doMobilerules() {
		$this->__mobile(__FUNCTION__);
	}

//惩罚大转盘
    public function doMobilemob_turnplate() {
        $this->__mobile(__FUNCTION__);
    }
    public function doWebturnplate_set(){
        $this->__web(__FUNCTION__);
    }
	//签到
	public function doMobileinformation(){
		$this->__mobile(__FUNCTION__);
	}

    //报名
    public function doMobilego_baoming(){
        $this->__mobile(__FUNCTION__);
    }

    //签到或者报名提交
    public function doMobileckinfo(){
        $this->__mobile(__FUNCTION__);

    }

    function strFilter($str){
        $str = str_replace('`', '', $str);
        $str = str_replace('·', '', $str);
        $str = str_replace('~', '', $str);
        $str = str_replace('!', '', $str);
        $str = str_replace('！', '', $str);
        $str = str_replace('@', '', $str);
        $str = str_replace('#', '', $str);
        $str = str_replace('$', '', $str);
        $str = str_replace('￥', '', $str);
        $str = str_replace('%', '', $str);
        $str = str_replace('^', '', $str);
        $str = str_replace('……', '', $str);
        $str = str_replace('&', '', $str);
        $str = str_replace('*', '', $str);
        $str = str_replace('(', '', $str);
        $str = str_replace(')', '', $str);
        $str = str_replace('（', '', $str);
        $str = str_replace('）', '', $str);
        $str = str_replace('-', '', $str);
        $str = str_replace('_', '', $str);
        $str = str_replace('——', '', $str);
        $str = str_replace('+', '', $str);
        $str = str_replace('=', '', $str);
        $str = str_replace('|', '', $str);
        $str = str_replace('\\', '', $str);
        $str = str_replace('[', '', $str);
        $str = str_replace(']', '', $str);
        $str = str_replace('【', '', $str);
        $str = str_replace('】', '', $str);
        $str = str_replace('{', '', $str);
        $str = str_replace('}', '', $str);
        $str = str_replace(';', '', $str);
        $str = str_replace('；', '', $str);
        $str = str_replace(':', '', $str);
        $str = str_replace('：', '', $str);
        $str = str_replace('\'', '', $str);
        $str = str_replace('"', '', $str);
        $str = str_replace('“', '', $str);
        $str = str_replace('”', '', $str);
        $str = str_replace(',', '', $str);
        $str = str_replace('，', '', $str);
        $str = str_replace('<', '', $str);
        $str = str_replace('>', '', $str);
        $str = str_replace('《', '', $str);
        $str = str_replace('》', '', $str);
        $str = str_replace('.', '', $str);
        $str = str_replace('。', '', $str);
        $str = str_replace('/', '', $str);
        $str = str_replace('、', '', $str);
        $str = str_replace('?', '', $str);
        $str = str_replace('？', '', $str);
        return trim($str);
    }

    //报名支付
    public function doMobileConfirm() {
        $this->__mobile(__FUNCTION__);
    }

    public function doMobileConfirm_bp() {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileConfirm_dpmds() {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileConfirm_dpmhb() {
        $this->__mobile(__FUNCTION__);
    }
    //报名、打赏、霸屏支付确认
    public function doMobilePay(){
        $this->__mobile(__FUNCTION__);
    }
    private function changeWechatSend($id, $status, $msg = '') {
        global $_W;
        $paylog = pdo_fetch("SELECT plid, openid, tag FROM " . tablename('core_paylog') . " WHERE tid = '{$id}' AND status = 1 AND type = 'wechat'");
        if (!empty($paylog['openid'])) {
            $paylog['tag'] = iunserializer($paylog['tag']);
            $acid = $paylog['tag']['acid'];
            $account = account_fetch($acid);
            $payment = uni_setting($account['uniacid'], 'payment');
            if ($payment['payment']['wechat']['version'] == '2') {
                return true;
            }
            $send = array(
                'appid' => $account['key'],
                'openid' => $paylog['openid'],
                'transid' => $paylog['tag']['transaction_id'],
                'out_trade_no' => $paylog['plid'],
                'deliver_timestamp' => TIMESTAMP,
                'deliver_status' => $status,
                'deliver_msg' => $msg,
            );
            $sign = $send;
            $sign['appkey'] = $payment['payment']['wechat']['signkey'];
            ksort($sign);
            $string = '';
            foreach ($sign as $key => $v) {
                $key = strtolower($key);
                $string .= "{$key}={$v}&";
            }
            $send['app_signature'] = sha1(rtrim($string, '&'));
            $send['sign_method'] = 'sha1';
            $account = WeAccount::create($acid);
            $response = $account->changeOrderStatus($send);
            if (is_error($response)) {
                message($response['message']);
            }
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
            }else{
                $paytype =2;
            }
            $update = array();
            $update['status'] = 2;
            $update['paytime'] = TIMESTAMP;
            $transid = $params['tid'];
            $fee = $params['fee'];
            $update['orderid'] = $paytype;
            $update['pay_total'] = $params['fee'];
            $update['pay_status'] = 0;

            $update['transaction_id'] = $params['tag']['transaction_id'];



            $order = pdo_fetch("select * from " . tablename('haoman_dpm_pay_order') . " where transid = :transid",array(':transid'=>$params['tid']));

            if(ISCUSTOM == 1 && CUSTOM_VERSION == 'DS'){
                $order2 = pdo_fetch("select * from " . tablename('haoman_dpm_ds_pay_order') . " where transid = :transid",array(':transid'=>$params['tid']));
                if($order2['status']!=2){
                    $ress =  $this->modify($transid,$update);
                }
            }else{

                if($order['status']!=2){

                    $ress =  $this->modify($transid,$update);
                }
            }



        }


        if (empty($params['result']) || $params['result'] != 'success'&& $params['from'] == 'notify') {
            message('支付失败！', '', 'error');
        }
        //因为支付完成通知有两种方式 notify，return,notify为后台通知,return为前台通知，需要给用户展示提示信息
        //return做为通知是不稳定的，用户很可能直接关闭页面，所以状态变更以notify为准
        //如果消息是用户直接返回（非通知），则提示一个付款成功
        if ($params['from'] == 'return') {
            if ($params['result'] == 'success') {
                if(ISCUSTOM == 1 && CUSTOM_VERSION == 'DS'){
                    $order2 = pdo_fetch("select * from " . tablename('haoman_dpm_ds_pay_order') . " where transid = :transid",array(':transid'=>$params['tid']));
                    $dsreply = pdo_fetch("select * from " . tablename('haoman_dpm_ds_reply') . " where uniacid = :uniacid and rid =:rid order by `id` desc", array(':uniacid' => $_W['uniacid'],':rid'=>$order2['rid']));
                }

                $order = pdo_fetch("select * from " . tablename('haoman_dpm_pay_order') . " where transid = :transid",array(':transid'=>$params['tid']));
                $reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where uniacid = :uniacid and rid =:rid order by `id` desc", array(':uniacid' => $_W['uniacid'],':rid'=>$order['rid']));
                $bpreply = pdo_fetch("select * from " . tablename('haoman_dpm_bpreply') . " where uniacid = :uniacid and rid =:rid order by `id` desc", array(':uniacid' => $_W['uniacid'],':rid'=>$order['rid']));
                $bphb = pdo_fetch("select * from " . tablename('haoman_dpm_hb_setting') . " where uniacid = :uniacid and rid =:rid order by `id` desc", array(':uniacid' => $_W['uniacid'],':rid'=>$order['rid']));

                if($order['pay_type']==2||$order['pay_type']==3||$order['pay_type']==4){
                    $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=messagesindex&id='.$order['rid'];
                    header("HTTP/1.1 301 Moved Permanently");
                    header("Location: " .$linkUrl . "");
                    exit();
                }


                $jssdk = new JSSDK();
                $package = $jssdk->GetSignPackage();
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
        $sql2 = "SELECT * FROM ".tablename('haoman_dpm_ds_pay_order')." WHERE transid = :transid ";
        // $exits = pdo_fetch("SELECT * FROM " . tablename('haoman_ds_data') . " WHERE transid = :transid", array(':transid' => $transid));

        $parms[':transid'] = $transid;
        $exits = pdo_fetch($sql,$parms);
        $exitss = pdo_fetch($sql2,$parms);



        if($exits&&$exits['pay_type']==0){
            //报名
            $reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where uniacid = :uniacid and rid =:rid order by `id` desc", array(':uniacid' => $exits['uniacid'],':rid'=>$exits['rid']));

            if($exits['pay_total']!=$entity['pay_total']){
                $entity['pay_status']=1;
            }

            $update = $entity;
            $ret = pdo_update('haoman_dpm_pay_order', $update, array('transid'=>$transid));
            if($ret){

                $insert = array(
                    'uniacid' => $exits['uniacid'],
                    'from_user' => $exits['from_user'],
                    'avatar' => $exits['avatar'],
                    'nickname' => $exits['nickname'],
                    'realname' => $exits['from_realname'],
                    'mobile' => $exits['mobile'],
                    'address' => $exits['pay_addr'],
                    'rid' => $exits['rid'],
                    'sex' => $exits['sex'],
                    'isbaoming' => 1,
                    'is_back' => 0,
                    'createtime' => time(),
                );
                pdo_insert('haoman_dpm_fans',$insert);
                pdo_update('haoman_dpm_reply', array('fansnum' => $reply['fansnum'] + 1, 'viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));


                return true;
            }else{
                return false;
            }
        }

        if ($exitss&&$exitss['pay_type']==1){
            //打赏
            $reply = pdo_fetch("select * from " . tablename('haoman_dpm_ds_reply') . " where uniacid = :uniacid and rid =:rid order by `id` desc", array(':uniacid' => $exitss['uniacid'],':rid'=>$exitss['rid']));
            $fans = pdo_fetch("select * from " . tablename('haoman_dpm_ds_fans') . " where uniacid = :uniacid and rid =:rid and from_user =:from_user", array(':uniacid' => $exitss['uniacid'],':rid'=>$exitss['rid'],':from_user'=>$exitss['ds_openid']));
            $dspeople = pdo_fetch("select * from " . tablename('haoman_dpm_ds_dspeople') . " where uniacid = :uniacid and rid =:rid and id =:id", array(':uniacid' => $exitss['uniacid'],':rid'=>$exitss['rid'],':id'=>$exitss['fansid']));

            if($exitss['pay_total']!=$entity['pay_total']){
                $entity['pay_status']=1;
            }


            $update = $entity;
            $ret = pdo_update('haoman_dpm_ds_pay_order', $update, array('transid'=>$transid));
            if($ret){
                if(empty($fans)){
                    $insert = array(
                        'uniacid' => $exitss['uniacid'],
                        'from_user' => $exitss['from_user'],
                        'avatar' => $exitss['avatar'],
                        'nickname' => $exitss['nickname'],
                        'realname' => $exitss['from_realname'],
                        'mobile' => $exitss['mobile'],
                        'address' => 0,
                        'rid' => $exitss['rid'],
                        'zhongjiang' => 1,
                        'awardinfo' => 1,
                        'awardnum' => $exitss['awardnum'],
                        'createtime' => time(),
                    );
                    pdo_insert('haoman_dpm_ds_fans',$insert);
                    pdo_update('haoman_dpm_ds_reply', array('fansnum' => $reply['fansnum'] + 1, 'viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
                }else{
                    pdo_update('haoman_dpm_ds_fans', array('mobile' => $exitss['mobile'] ,'awardnum'=>$fans['awardnum']+$exitss['pay_total'], 'zhongjiang' => 1,'awardinfo'=>$fans['awardinfo']+1), array('id' => $fans['id']));
                }
                pdo_update('haoman_dpm_ds_dspeople', array('ds_total' => $dspeople['ds_total'] + $exitss['pay_total']), array('id' => $dspeople['id']));
                return true;
            }else{
                return false;
            }
        }
        if ($exits&&$exits['pay_type']==2){
            //霸屏
            $fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $exits['rid'] . "' and from_user='" . $exits['from_user'] . "'");

            $reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_bpreply')." WHERE rid='".$exits['rid']."' " );

            if($reply['status']==1){
                $status =0;
            }else{
                $status =1;
            }
               if($exits['psy_type']==4){
                   $typ=4;
               }elseif($exits['psy_type']==5){
                   $typ=5;
               }else{
                   $typ=1;
               }

            if($exits['pay_total']!=$entity['pay_total']){
                $entity['pay_status']=1;
            }

            $update = $entity;
            $ret = pdo_update('haoman_dpm_pay_order', $update, array('transid'=>$transid));
            if($ret) {
             $avatar = empty($exits['avatar'])?tomedia($fans['avatar']):tomedia($exits['avatar']);
                $insert = array(
                    'uniacid' => $exits['uniacid'],
                    'avatar' => $avatar,
                    'nickname' => $exits['nickname'],
                    'from_user' =>  $exits['from_user'],
                    'for_nickname' =>  $exits['from_realname'],
                    'word' => $exits['message'],
                    'wordimg' => $exits['wordimg'],
                    'rid' => $exits['rid'],
                    'status' => $status,
                    'is_back' => $fans['is_back'],
                    'is_xy' =>0,
                    'is_bp' =>1,
                    'type' =>$typ,//1表示图片霸屏4表示视频霸屏5表示未他人霸屏
                    'gift' =>0,
                    'is_bpshow' =>1,
                    'bptime' =>$exits['bptime'],
                    'createtime' => time(),
                );
                $temp = pdo_insert('haoman_dpm_messages',$insert);
                $admin = pdo_fetchall("select id,free_times,uses_times,bpadmin,admin_openid from " . tablename('haoman_dpm_bpadmin') . "  where status=0 and rid=:rid", array(':rid'=>$exits['rid']));
                $isadmin_msg = pdo_fetch("select isadmin from " . tablename('haoman_dpm_pay_order') . "  where transid = :transid ", array(':transid'=>$transid));

                foreach($admin as $v){
                   if($isadmin_msg['isadmin']&&$v['admin_openid']==$exits['from_user']){
                       $retss = pdo_update('haoman_dpm_bpadmin', array('uses_times'=>$v['uses_times']+1), array('id'=>$v['id'],'admin_openid'=>$exits['from_user']));
                   }
                   if(($v['bpadmin']==0||$v['bpadmin']==1)&&$isadmin_msg['isadmin']!=1){
                       $mbid = pdo_fetch("select s_templateid from " . tablename('haoman_dpm_notifications') . "  where rid=:rid", array(':rid'=>$exits['rid']));

                       if($mbid['s_templateid']){

                           $this->send_template($v['admin_openid'],$mbid['s_templateid'],'霸屏/小视频',$exits['nickname'],$exits['pay_total'],$exits['transid']);
                       }
                   }
               }




            };
        }
        if ($exits&&$exits['pay_type']==7){
            //送礼
            $fans = pdo_fetch("select is_back,avatar from " . tablename('haoman_dpm_fans') . " where rid = '" . $exits['rid'] . "' and from_user='" . $exits['from_user'] . "'");
            $for_fans = pdo_fetch("select nickname,avatar from " . tablename('haoman_dpm_fans') . " where rid = '" . $exits['rid'] . "' and from_user='" . $exits['from_realname'] . "'");

//            $reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_bpreply')." WHERE rid='".$exits['rid']."' " );
            $item_list = pdo_fetch("SELECT bb_price,bb_time,bb_name FROM " . tablename('haoman_dpm_bbgift') . " WHERE rid = :rid and uniacid = :uniacid and type =1 and id =:id ORDER BY id desc",array(':rid'=>$exits['rid'],':uniacid'=>$exits['uniacid'],':id'=>$exits['wordimg']));


               if($exits['psy_type']==4){
                   $typ=4;
               }elseif($exits['psy_type']==5){
                   $typ=5;
               }elseif($exits['psy_type']==7){
                   $typ=7;
               }else{
                   $typ=1;
               }

            if($exits['pay_total']!=$entity['pay_total']){
                $entity['pay_status']=1;
            }

            $update = $entity;
            $ret = pdo_update('haoman_dpm_pay_order', $update, array('transid'=>$transid));
            if($ret) {
             $avatar = empty($exits['avatar'])?tomedia($fans['avatar']):tomedia($exits['avatar']);
                $insert = array(
                    'uniacid' => $exits['uniacid'],
                    'avatar' => $avatar,
                    'nickname' => $exits['nickname'],
                    'from_user' =>  $exits['from_user'],
                    'for_nickname' =>  $for_fans['nickname'],
                    'word' => $item_list['bb_name'],
                    'wordimg' => $for_fans['avatar'],
                    'rid' => $exits['rid'],
                    'status' => 1,
                    'is_back' => $fans['is_back'],
                    'is_xy' =>0,
                    'is_bp' =>1,
                    'type' =>$typ,//1表示图片霸屏4表示视频霸屏5表示未他人霸屏
                    'gift' =>0,
                    'is_bpshow' =>1,
                    'says' =>$exits['message'],
                    'bptime' =>$exits['bptime'],
                    'createtime' => time(),
                );
                $temp = pdo_insert('haoman_dpm_messages',$insert);
                $admin = pdo_fetchall("select id,free_times,uses_times,bpadmin,admin_openid from " . tablename('haoman_dpm_bpadmin') . "  where status=0 and rid=:rid", array(':rid'=>$exits['rid']));
                $isadmin_msg = pdo_fetch("select isadmin from " . tablename('haoman_dpm_pay_order') . "  where transid = :transid ", array(':transid'=>$transid));

                foreach($admin as $v){
                   if($isadmin_msg['isadmin']&&$v['admin_openid']==$exits['from_user']){
                       $retss = pdo_update('haoman_dpm_bpadmin', array('uses_times'=>$v['uses_times']+1), array('id'=>$v['id'],'admin_openid'=>$exits['from_user']));
                   }
                   if(($v['bpadmin']==0||$v['bpadmin']==1)&&$isadmin_msg['isadmin']!=1){
                       $mbid = pdo_fetch("select s_templateid from " . tablename('haoman_dpm_notifications') . "  where rid=:rid", array(':rid'=>$exits['rid']));

                       if($mbid['s_templateid']){

                           $this->send_template($v['admin_openid'],$mbid['s_templateid'],'霸屏/小视频',$exits['nickname'],$exits['pay_total'],$exits['transid']);
                       }
                   }
               }




            };
        }
        if ($exits&&$exits['pay_type']==6){
            //表白
            $fans = pdo_fetch("select is_back,avatar from " . tablename('haoman_dpm_fans') . " where rid = '" . $exits['rid'] . "' and from_user='" . $exits['from_user'] . "'");
            $for_fans = pdo_fetch("select nickname,avatar from " . tablename('haoman_dpm_fans') . " where rid = '" . $exits['rid'] . "' and from_user='" . $exits['from_realname'] . "'");

//            $reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_bpreply')." WHERE rid='".$exits['rid']."' " );
            $item_list = pdo_fetch("SELECT bb_says,bb_pic,bb_name FROM " . tablename('haoman_dpm_bbgift') . " WHERE rid = :rid and uniacid = :uniacid and type =2 and id =:id ORDER BY id desc",array(':rid'=>$exits['rid'],':uniacid'=>$exits['uniacid'],':id'=>$exits['pay_addr']));


               if($exits['psy_type']==4){
                   $typ=4;
               }elseif($exits['psy_type']==5){
                   $typ=5;
               }elseif($exits['psy_type']==7){
                   $typ=7;
               }elseif($exits['psy_type']==6){
                   $typ=6;
               }else{
                   $typ=1;
               }

            if($exits['pay_total']!=$entity['pay_total']){
                $entity['pay_status']=1;
            }

            $update = $entity;
            $ret = pdo_update('haoman_dpm_pay_order', $update, array('transid'=>$transid));
            if($ret) {
             $avatar = empty($exits['avatar'])?tomedia($fans['avatar']):tomedia($exits['avatar']);
                $insert = array(
                    'uniacid' => $exits['uniacid'],
                    'avatar' => $avatar,
                    'nickname' => $exits['nickname'],
                    'from_user' =>  $exits['from_user'],
                    'for_nickname' =>  $for_fans['nickname'],
                    'word' => $exits['message'],
                    'wordimg' => $exits['wordimg'],
                    'rid' => $exits['rid'],
                    'status' => 1,
                    'is_back' => $fans['is_back'],
                    'is_xy' =>0,
                    'is_bp' =>1,
                    'type' =>$typ,//1表示图片霸屏4表示视频霸屏5表示未他人霸屏
                    'gift' =>0,
                    'is_bpshow' =>1,
                    'says' =>$item_list['bb_says'],
                    'bptime' =>$exits['bptime'],
                    'bb_theme' =>$item_list['bb_name'],
                    'createtime' => time(),
                );
                $temp = pdo_insert('haoman_dpm_messages',$insert);
                $admin = pdo_fetchall("select id,free_times,uses_times,bpadmin,admin_openid from " . tablename('haoman_dpm_bpadmin') . "  where status=0 and rid=:rid", array(':rid'=>$exits['rid']));
                $isadmin_msg = pdo_fetch("select isadmin from " . tablename('haoman_dpm_pay_order') . "  where transid = :transid ", array(':transid'=>$transid));

                foreach($admin as $v){
                   if($isadmin_msg['isadmin']&&$v['admin_openid']==$exits['from_user']){
                       $retss = pdo_update('haoman_dpm_bpadmin', array('uses_times'=>$v['uses_times']+1), array('id'=>$v['id'],'admin_openid'=>$exits['from_user']));
                   }
                   if(($v['bpadmin']==0||$v['bpadmin']==1)&&$isadmin_msg['isadmin']!=1){
                       $mbid = pdo_fetch("select s_templateid from " . tablename('haoman_dpm_notifications') . "  where rid=:rid", array(':rid'=>$exits['rid']));

                       if($mbid['s_templateid']){

                           $this->send_template($v['admin_openid'],$mbid['s_templateid'],'霸屏/小视频',$exits['nickname'],$exits['pay_total'],$exits['transid']);
                       }
                   }
               }




            };
        }
        if ($exits&&$exits['pay_type']==3){
            //ds
            $fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $exits['rid'] . "' and from_user='" . $exits['from_user'] . "'");

            $reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_bpreply')." WHERE rid='".$exits['rid']."' " );

            $item_list = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_guest') . " WHERE turntable =2 and id =:id ",array(':id'=>$exits['pay_addr']));

            $guest_list = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_guest') . " WHERE turntable =1 and id =:id ",array(':id'=>$exits['message']));

            if($reply['status']==1){
                $status =0;
            }else{
                $status =1;
            }


            if($exits['pay_total']!=$entity['pay_total']){
                $entity['pay_status']=1;
            }

            $update = $entity;
            $ret = pdo_update('haoman_dpm_pay_order', $update, array('transid'=>$transid));
            if($ret) {
                $avatar = empty($exits['avatar'])?tomedia($fans['avatar']):tomedia($exits['avatar']);
                $insert = array(
                    'uniacid' => $exits['uniacid'],
                    'avatar' => $avatar,
                    'nickname' => $exits['nickname'],
                    'from_user' =>  $exits['from_user'],
                    'word' => $guest_list['name'],
                    'wordimg' => $item_list['pic'],
                    'rid' => $exits['rid'],
                    'status' => $status,
                    'is_back' => $fans['is_back'],
                    'is_xy' =>0,
                    'is_bp' =>1,
                    'is_bpshow' =>1,
                    'type' =>2,
                    'gift_id' =>$exits['pay_addr'],
                    'gift' =>1,
                    'bptime' =>1,
                    'says' =>$exits['wordimg'],
                    'createtime' => time(),
                );
                $temp = pdo_insert('haoman_dpm_messages',$insert);
               $rets = pdo_update('haoman_dpm_guest', array('type'=>$guest_list['type']+1), array('id'=>$guest_list['id']));

                $admin = pdo_fetchall("select id,free_times,uses_times,bpadmin,admin_openid from " . tablename('haoman_dpm_bpadmin') . "  where status=0 and rid=:rid", array(':rid'=>$exits['rid']));
                $isadmin_msg = pdo_fetch("select isadmin from " . tablename('haoman_dpm_pay_order') . "  where transid = :transid ", array(':transid'=>$transid));

                foreach($admin as $v){
                    if($isadmin_msg['isadmin']&&$v['admin_openid']==$exits['from_user']){
                        $retss = pdo_update('haoman_dpm_bpadmin', array('uses_times'=>$v['uses_times']+1), array('id'=>$v['id'],'admin_openid'=>$exits['from_user']));
                    }
                    if(($v['bpadmin']==0||$v['bpadmin']==1)&&$isadmin_msg['isadmin']!=1){
                        $mbid = pdo_fetch("select s_templateid from " . tablename('haoman_dpm_notifications') . "  where rid=:rid", array(':rid'=>$exits['rid']));

                        if($mbid['s_templateid']){

                            $this->send_template($v['admin_openid'],$mbid['s_templateid'],'打赏',$exits['nickname'],$exits['pay_total'],$exits['transid']);
                        }
                    }
                }



            };
        }
        if ($exits&&$exits['pay_type']==4){
            //hb
            $fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $exits['rid'] . "' and from_user='" . $exits['from_user'] . "'");

//            $reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_bpreply')." WHERE rid='".$exits['rid']."' " );
//
//            if($reply['status']==1){
//                $status =0;
//            }else{
//                $status =1;
//            }
            $avatar = empty($exits['avatar'])?tomedia($fans['avatar']):tomedia($exits['avatar']);
            $fashb = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_hb_setting')." WHERE rid='".$exits['rid']."' " );

            $hb_money = $entity['pay_total']/((100+$fashb['counter'])/100);//支付费用加手续费

            $hb_money=sprintf("%.2f",$hb_money);

            if($exits['pay_addr']!=$entity['pay_total']){
                $entity['pay_addr']=$entity['pay_total'];
                $entity['pay_total']=$hb_money;
                $entity['pay_status']=1;
            }else{
                $entity['pay_total']=$hb_money;
            }

            $update = $entity;
            $ret = pdo_update('haoman_dpm_pay_order', $update, array('transid'=>$transid));

            $insert_hb = array(
                'rid' => $exits['rid'],
                'uniacid' => $exits['uniacid'],
                'avatar' => $avatar,
                'nickname' => $exits['nickname'],
                'from_user' =>  $exits['from_user'],
                'actual_money' => $hb_money,
                'counter' => $exits['from_realname'],
                'hbnum' => $exits['bptime'],
                'desknum' => $exits['wordimg'],
                'money' =>$exits['pay_addr'],
                'says' =>$exits['message'],
                'createtime' => time(),
            );
            $temp_hb = pdo_insert('haoman_dpm_hb_log',$insert_hb);
            $hbid = pdo_insertid();
            if($ret&&$temp_hb) {

                $insert = array(
                    'uniacid' => $exits['uniacid'],
                    'avatar' => $avatar,
                    'nickname' => $exits['nickname'],
                    'from_user' =>  $exits['from_user'],
                    'word' => $exits['message'],
                    'rid' => $exits['rid'],
                    'status' => 1,
                    'is_back' => $fans['is_back'],
                    'for_nickname' =>$hb_money,
                    'is_xy' =>0,
                    'is_bp' =>0,
                    'is_bpshow' =>0,
                    'type' =>3,
                    'gift_id' =>$hbid,
                    'gift' =>1,
                    'bptime' =>1,
                    'says' =>0,
                    'createtime' => time(),
                );
                $temp = pdo_insert('haoman_dpm_messages',$insert);
                $admin = pdo_fetchall("select id,free_times,uses_times,bpadmin,admin_openid from " . tablename('haoman_dpm_bpadmin') . "  where status=0 and rid=:rid", array(':rid'=>$exits['rid']));
                $isadmin_msg = pdo_fetch("select isadmin from " . tablename('haoman_dpm_pay_order') . "  where transid = :transid ", array(':transid'=>$transid));

                foreach($admin as $v){
                    if($isadmin_msg['isadmin']&&$v['admin_openid']==$exits['from_user']){
                        $retss = pdo_update('haoman_dpm_bpadmin', array('uses_times'=>$v['uses_times']+1), array('id'=>$v['id'],'admin_openid'=>$exits['from_user']));
                    }
                    if(($v['bpadmin']==0||$v['bpadmin']==1)&&$isadmin_msg['isadmin']!=1){
                        $mbid = pdo_fetch("select s_templateid from " . tablename('haoman_dpm_notifications') . "  where rid=:rid", array(':rid'=>$exits['rid']));

                        if($mbid['s_templateid']){

                            $this->send_template($v['admin_openid'],$mbid['s_templateid'],'红包',$exits['nickname'],$exits['pay_total'],$exits['transid']);
                        }
                    }
                }
            };
        }
        if ($exits&&$exits['pay_type']==10){
            //购物
            $fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $exits['rid'] . "' and from_user='" . $exits['from_user'] . "'");

            $reply = pdo_fetch( " SELECT * FROM ".tablename('haoman_dpm_bpreply')." WHERE rid='".$exits['rid']."' " );

            if($exits['pay_total']!=$entity['pay_total']){
                $entity['pay_status']=1;
            }

            $update = $entity;
            $ret = pdo_update('haoman_dpm_pay_order', $update, array('transid'=>$transid));
            if($ret) {
                pdo_update('haoman_dpm_pay_order_goods', array('status'=>1), array('orderid'=> $exits['transid']));


                $goods = pdo_fetchall("select goodsid,`number`,price from " . tablename('haoman_dpm_pay_order_goods') . "  where rid=:rid and orderid =:orderid and status=1", array(':rid'=>$exits['rid'],':orderid'=>$exits['transid']));
                $content ='';
                foreach ($goods as $k){
                    $shop_goods = pdo_fetch("select sale_number,title,give_type,give_note,full_money,seng_money,company from " . tablename('haoman_dpm_shop_goods') . "  where id=:id and rid=:rid", array(':id'=>$k['goodsid'],':rid'=>$exits['rid']));
                    pdo_update('haoman_dpm_shop_goods', array('sale_number'=>$shop_goods['sale_number']+1), array('id'=> $k['goodsid']));
                   if($shop_goods['give_type']==1){
                       if($shop_goods['give_note']){
                           $content .= $shop_goods['title'].'x'.$k['number'].'('.$shop_goods['company'].')，'.'赠：'.$shop_goods['give_note'].'。';
                       }else{
                           $content .= $shop_goods['title'].'x'.$k['number'].'('.$shop_goods['company'].')。';
                       }

                   }else{
                       $content .= $shop_goods['title'].'x'.$k['number'].'('.$shop_goods['company'].')。';
                      $price = sprintf("%1.2f",$k['price']*$k['number']);
                     if($price>=$shop_goods['full_money']){
                         $fans = pdo_fetch("select id,fanshbnum from " . tablename('haoman_dpm_fans') . "  where  rid=:rid and from_user =:from_user", array(':rid'=>$exits['rid'],':from_user'=>$exits['from_user']));
                         pdo_update('haoman_dpm_fans', array('fanshbnum'=>$fans['fanshbnum']+$shop_goods['seng_money']), array('id'=> $fans['id']));
                     }

                   }
                }

//                $goods = pdo_fetchall("SELECT a.id,b.title,b.company FROM " . tablename('haoman_dpm_pay_order_goods') . "as a left join" . tablename('haoman_dpm_shop_goods') . "as b on a.goodsid=b.id WHERE a.rid =b.rid And a.rid = :rid and a.uniacid=:uniacid and a.orderid=:orderid  ORDER BY a.createtime DESC",array(':rid'=>$exits['rid'],':uniacid'=>$exits['uniacid'],':orderid'=>$exits['transid']));



                $mbid = pdo_fetch("select n_templateid from " . tablename('haoman_dpm_notifications') . "  where rid=:rid", array(':rid'=>$exits['rid']));

                $address ='位置：'.$exits['wordimg'];
                if($mbid['n_templateid']){
                    $wditer = pdo_fetchall("select id,wditer_openid from " . tablename('haoman_dpm_shop_wditer') . "  where rid=:rid and status=0", array(':rid'=>$exits['rid']));
                    foreach ($wditer as $v){
                        $this->shop_template($v['wditer_openid'],trim($mbid['n_templateid']),$content,$address);

                    }
                }
            };
        }
        return false;
    }



    //报名订单管理
    public function doWebBm_order() {
        $this->__web(__FUNCTION__);
    }

    //霸屏订单管理
    public function doWebbp_order() {
        $this->__web(__FUNCTION__);
    }

    public function doWebDelete_cash() {
        $this->__web(__FUNCTION__);
    }

    public function doWebDeletepay_order() {
        $this->__web(__FUNCTION__);
    }


    //2017-03-05新增管理员

    public function doWebadmin(){
        $this->__web(__FUNCTION__);
    }
    public function doWebadminshow(){
        $this->__web(__FUNCTION__);
    }
    public function doWebnewadmin(){
        $this->__web(__FUNCTION__);
    }
    public function doWebDeleteadmin_jilu() {
        $this->__web(__FUNCTION__);
    }

//删除消息
    public function doMobiledeletemsg() {
        $this->__mobile(__FUNCTION__);
    }
//拉黑
    public function doMobilepullblack() {
        $this->__mobile(__FUNCTION__);
    }

    public function doWebUserinfo3() {
        $this->__web(__FUNCTION__);
    }

	//微信端首页
	public function doMobileIndex() {
		$this->__mobile(__FUNCTION__);
	}



		//微信端上墙页面
	public function doMobilemessagesindex() {
		$this->__mobile(__FUNCTION__);
	}

    //2017-02-13新增手机聊天界面
    public function doMobilehaobang(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobileGetmsg(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobilesendmsg(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobileGetHistoryMsg(){
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileplay_reward_url(){
        $this->__mobile(__FUNCTION__);
    }

	public function doMobileUploadImage() {
        $this->__mobile(__FUNCTION__);
    }

    //2017-03-22  新增私聊 开始
    public function doMobilegettalkmsg(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobilesendtalkmsg(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobileunread(){
        $this->__mobile(__FUNCTION__);
    }

    public function doMobileis_online(){
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileemptychat(){
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilemoretalkuser(){
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilemy_talk(){
        $this->__mobile(__FUNCTION__);
    }
//2017-03-22  新增私聊 结束
    //2017-03-13
    public function doMobileRedPackStatus(){
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileRedPackLuck(){
        $this->__mobile(__FUNCTION__);
    }
    public function doWebfanshb() {
        $this->__web(__FUNCTION__);
    }
    public function doWebfanshbshow() {
        $this->__web(__FUNCTION__);
    }

    public function doWebUserinfo4() {
        $this->__web(__FUNCTION__);
    }
    public function doWebdele_fanshb() {
        $this->__web(__FUNCTION__);
    }
    public function doWebdownload_hb_log() {
        $this->__web(__FUNCTION__);
    }
    protected function fileUpload2($file, $type) {
        global $_W;
        set_time_limit(0);
        $_W['uploadsetting'] = array();
        $_W['uploadsetting']['images']['folder'] = 'image';
        $_W['uploadsetting']['images']['extentions'] = array('jpg', 'png', 'gif','mp4');
        $_W['uploadsetting']['images']['limit'] = 50000;
        $result = array();
        $upload = file_upload($file, $type);
        if (is_error($upload)) {
            message($upload['message'], '', 'ajax');
        }
        $result['url'] = $upload['url'];
        $result['error'] = 0;
        $result['filename'] = $upload['path'];

        return $result;
    }


    public function doMobilesavemessages(){
		$this->__mobile(__FUNCTION__);
	}

//2017-03-15

    public function doMobiletalk() {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileexpress_love() {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilesend_gift() {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilemoreUsers() {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileconfirm_dpm_gift() {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileconfirm_dpm_saylove() {
        $this->__mobile(__FUNCTION__);
    }
    public function doWebis_start() {
        $this->__web(__FUNCTION__);
    }
    public function doWebset_start() {
        $this->__web(__FUNCTION__);
    }
    public function doWebpay_orders() {
        $this->__web(__FUNCTION__);
    }

    public function doMobilem_friends() {
        $this->__mobile(__FUNCTION__);
    }

	//抽奖页面
	public function doMobileqhbIndex() {
		$this->__mobile(__FUNCTION__);
	}

	private function sendText($openid,$txt){
		global $_W;

        load()->model('account');
        $_W['account'] = account_fetch($_W['acid']);
        if ($_W['account']['level'] != 4) {
            return false;
        }
        $acid=pdo_fetchcolumn("SELECT acid FROM ".tablename('account')." WHERE uniacid=:uniacid ",array(':uniacid'=>$_W['uniacid']));
		$acc = WeAccount::create($acid);
		$data = $acc->sendCustomNotice(array('touser'=>$openid,'msgtype'=>'text','text'=>array('content'=>urlencode($txt))));
		return $data;


	}
    public function getAccount()
    {
        global $_W;
        load()->model('account');
        if (!empty($_W['acid'])) {
            return WeAccount::create($_W['acid']);
        } else {
            $acid = pdo_fetchcolumn('SELECT acid FROM ' . tablename('account_wechats') . ' WHERE `uniacid`=:uniacid LIMIT 1', array(':uniacid' => $_W['uniacid']));
            return WeAccount::create($acid);
        }
        return false;
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
		$this->__mobile(__FUNCTION__);
	}

     //2017-04-14  新增图片上传
    public function doMobilesave_image() {
        $this->__mobile(__FUNCTION__);
    }
    function sn(){
        return date('YmdHis').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
    //下载多媒体
    function downimg($meid){
        global $_GPC, $_W;
       // if ($_W['account']['level'] != 4) {
            load()->func('cache');

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
                   return;
                }
            }
            $data = cache_load("haoman.dpm.access_token.json::".$_W['acid'], true);

            if (empty($data['expire_time']) || $data['expire_time'] < time()) {
                $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
                $res = @json_decode($this->httpGet($url));
                $token = $res->access_token;
                if ($token) {
                    $data['expire_time'] = time() + 7000;
                    $data['access_token'] = $token;
                    cache_write("haoman.dpm.access_token.json::".$_W['acid'], iserializer($data));
                    cache_write("haoman.dpm.api_share.json::".$_W['acid'], iserializer(array("appid"=>$appid,"appsecret"=>$appsecret)));
                }
            } else {
                $token = $data['access_token'];
            }


//        }else{
//            load()->classs('weixin.account');
//            load()->func('communication');
//            $token = WeAccount::token();
//        }

//        $jssdk = new JSSDK();
//        $token = $jssdk->getAccessToken();

        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$token."&media_id=".$meid;
        $data =$this->get($url);
        return $data;
    }

    //上传图片
    public function doMobileuploadimg()
    {
        global $_W, $_GPC;
        if ($_W['isajax']) {

            $dateF = date('Y-m-d', time());
            $datearr = explode('-', $dateF);
            $rPathName = 'images/' . $_W['uniacid'] . '/' . $datearr[0] . '/' . $datearr[1] . '/';
            $pathName = IA_ROOT . '/attachment/' . $rPathName;
            $fileName = uniqid() . '.jpg';
            $oImgUrl = $pathName . $fileName;
            $rImgUrl = $rPathName . $fileName;
            if (!is_dir($pathName)) {
                mkdir($pathName);
            }
            $data = base64_decode($_GPC['strImg']);
            if (file_put_contents($oImgUrl, $data)) {
                $img = array('sImgUrl' => tomedia($rImgUrl), 'bImgUrl' => tomedia($rImgUrl));
                $this->ajaxReturn($status = 1, $img);
            } else {
                $this->ajaxReturn($status = 0, '图片上传失败');
            }
        }
    }

    function ajaxReturn($status = 0, $msg = array())
    {
        header('content-type:application/json;charset=utf8');
        $arr = array('isResultTrue' => $status, 'resultMsg' => $msg);
        echo json_encode($arr);
        die;
    }

    function get($url){
        $ch = curl_init();
        preg_match('/https:\/\//',$url)?$ssl=TRUE:$ssl=FALSE;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if($ssl){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $data  =  curl_exec($ch);
        curl_close($ch);
        return $data;
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
		$this->__web(__FUNCTION__);
	}

	//审核消息
	public function doWebcklistmessage() {
		$this->__web(__FUNCTION__);
	}

    //删除消息
	public function doWebDeletemessage() {
		$this->__web(__FUNCTION__);
	}

    //批量拉黑粉丝
    public function doWebBack_fans() {
        $this->__web(__FUNCTION__);
    }

    //批量删除粉丝
    public function doWebDeleall_fans() {
        $this->__web(__FUNCTION__);
    }

	//批量审核消息
	public function doWebAllmessages() {
		$this->__web(__FUNCTION__);
	}

    //批量删除消息
    public function doWebDel_allmessages() {
        $this->__web(__FUNCTION__);
    }

//批量删除霸屏订单
    public function doWebDel_bporders() {
        $this->__web(__FUNCTION__);
    }

    public function doWebDelall_bporders() {
        $this->__web(__FUNCTION__);
    }

    public function doWeball_link() {
        $this->__web(__FUNCTION__);
    }

    //广告管理
	public function doWebAdmanage() {
		$this->__web(__FUNCTION__);
	}

    //添加广告
	public function doWebNewad() {
		$this->__web(__FUNCTION__);
	}

    //删除广告
	public function doWebDelete9() {
		$this->__web(__FUNCTION__);
	}

    //批量删除广告
	public function doWebDeleteAllad() {
		$this->__web(__FUNCTION__);
	}



	//删除奖品
	public function doWebDeleteAwards() {
		$this->__web(__FUNCTION__);
	}


   //	提现申请
	public function doMobileapplication() {
		$this->__mobile(__FUNCTION__);
	}

   //后台提现审核
	public function doWebSetstatuss() {
		$this->__web(__FUNCTION__);
	}





    //嘉宾列表
	public function doWebjiabin(){
		$this->__web(__FUNCTION__);
	}


	//查看详细嘉宾
	public function doWebjiabinshow(){
		$this->__web(__FUNCTION__);
	}


	//添加和编辑嘉宾
	public function doWebNewjiabin() {
		$this->__web(__FUNCTION__);
	}

    public function doMobileshowjiabin() {
        $this->__mobile(__FUNCTION__);
    }

    //嘉宾搜索
    public function doMobilejiabin_save() {
        $this->__mobile(__FUNCTION__);
    }

    //修改部分开始
    //投票首页
    public function doMobilego_toupiao() {
        $this->__mobile(__FUNCTION__);
    }

    //投票排行页
    public function doMobilevote_show() {
        $this->__mobile(__FUNCTION__);
    }

    //开始投票
    public function doMobilevote_save() {
        $this->__mobile(__FUNCTION__);
    }

    //投票搜索
    public function doMobilevote_list() {
        $this->__mobile(__FUNCTION__);
    }

    //投票排行搜索
    public function doMobilevote_show_list() {
        $this->__mobile(__FUNCTION__);
    }

    //投票列表
    public function doWebtoupiao(){
        $this->__web(__FUNCTION__);
    }


    //查看投票详情
    public function doWebtoupiaoshow(){
        $this->__web(__FUNCTION__);
    }


    //添加和编辑投票
    public function doWebNewtoupiao() {
        $this->__web(__FUNCTION__);
    }


    //2017-04-13  开始

    //新高级投票列表
    public function doWebnew_vote(){
        $this->__web(__FUNCTION__);
    }
    public function doWebnewvote(){
        $this->__web(__FUNCTION__);
    }
    public function doWebvoteshow(){
        $this->__web(__FUNCTION__);
    }
    public function doWebsetting_vote(){
        $this->__web(__FUNCTION__);
    }
    //大屏幕端
    public function doMobiledpm_newvote() {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilechangge_vote() {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobiledpm_newvotelist() {
        $this->__mobile(__FUNCTION__);
    }
    //大屏幕端
    //手机端
    public function doMobilevote_option_list() {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilenew_vote_index() {
        $this->__mobile(__FUNCTION__);
    }
    //手机端
    public function doWebchange_votestatus(){
        $this->__web(__FUNCTION__);
    }
//2017-04-13  结束

    public function doWebUserinfo() {
        $this->__web(__FUNCTION__);
    }

    public function doWebDeletehexiao_jilu() {
        $this->__web(__FUNCTION__);
    }

    public function doWebDownload_tp_log(){
        $this->__web(__FUNCTION__);
    }

    //修改结束



    //现场抽奖奖品列表
	public function doWebcode(){
		$this->__web(__FUNCTION__);
	}

    //大转盘抽奖
	public function doWebdzp(){
		$this->__web(__FUNCTION__);
	}


	//抢红包奖品列表
	public function doWebqhbjp(){
		$this->__web(__FUNCTION__);
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
		$this->__web(__FUNCTION__);
	}


	//添加和编辑奖品
	public function doWebNewcode() {
		$this->__web(__FUNCTION__);
	}

    //内定开始部分
    public function doWebDraw_default() { //内定人员管理
        $this->__web(__FUNCTION__);
    }


    public function doWebnewneiding() { //添加、修改内定人员
        $this->__web(__FUNCTION__);
    }
    //内定结束
    public function doWebsetting_bp() { //添加、修改霸屏
        $this->__web(__FUNCTION__);
    }
	//口令导入
	public function doWebImport(){
		$this->__web(__FUNCTION__);
	}


	//失效口令删除
	public function doWebMiss() {
		$this->__web(__FUNCTION__);
	}

	//每批次卡密删除
	public function doWebCodedie() {
		$this->__web(__FUNCTION__);
	}

    //单独口令删除
	public function doWebDeletepw() {
		$this->__web(__FUNCTION__);
	}

//    抽奖码下载
	public function doWebUDownload2(){
		$this->__web(__FUNCTION__);
	}




	//检测用户浏览器
	public function checkBowser(){
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){

		}
	}



//删除测试数据
	public function doWebDelete_openid() {
		$this->__web(__FUNCTION__);
	}

//删除活动
	public function doWebDelete() {
		global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$rule = pdo_fetch("select id,`module` from " . tablename('rule') . " where id = :id and uniacid=:uniacid", array(':id' => $rid, ':uniacid' => $_W['uniacid']));
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

    //批量删除活动
    public function doWebDeleteAll() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        foreach ($_GPC['idArr'] as $k=>$rid) {
            $rid = intval($rid);
            if ($rid == 0 ||$rid ==1)
                continue;
            $rule = pdo_fetch("select id,`module` from " . tablename('rule') . " where id = :id and uniacid=:uniacid", array(':id' => $rid, ':uniacid' => $_W['uniacid']));
            if (empty($rule)) {
                message('抱歉，要修改的规则不存在或是已经被删除！', '', 'error');
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
        }
        $data = array(
            'errno' => 0,
            'msg' => "批量删除成功",
        );

        echo json_encode($data);
      //  message('删除成功！', referer(), 'success');

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
//		$appid = $_W['account']['key'];
//		$appSecret = $_W['account']['secret'];
//		load()->func('communication');
       load()->classs('weixin.account');
       load()->func('communication');
       $tokens = WeAccount::token();
      $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$tokens}";
		//检测ticket是否过期
		if ($data['createtime'] < time()) {
			//$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appSecret."";
			$res = json_decode($this->httpGet($url));
		//	$tokens = $res->access_token;
			if(empty($tokens))
			{
				return $res['errmsg'];
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
		$cookie = array("nickname" => $userInfo['nickname'],'avatar'=>$userInfo['headimgurl'],'openid'=>$userInfo['openid'],'sex'=>$userInfo['sex']);
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

    /*红包新商户订单号生成方式*/
    public function get_rand_number($pre = ''){
        return $pre.date('ym').substr(time(),4).substr(microtime(),2,6).rand(18,99);
    }
    /*红包新商户订单号生成方式*/

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


        /*红包新商户订单号生成方式*/
        $new_mch_billno = intval($user['fansid']%100);
        $mch_billno = $this->get_rand_number($new_mch_billno);
        /*红包新商户订单号生成方式*/

		$actname = empty($api['actname']) ? '参与疯狂抢红包活动' : $api['actname'];

		if(empty($api['wishing'])){
			$wishing = '恭喜您,抽中了一个' . $record['fee'] . '元红包!';
		}else{
			$wishing = $api['wishing'] . $record['fee'] . '元红包!';
		}

        //$user = $this->strFilter($user['nickname']);

		$fee                   = floatval($record['fee'])*100;//红包金额，单位为分;
		$url                   = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
		$pars                  = array();
		$pars['nonce_str']     = random(32);
		$pars['mch_billno']    = $mch_billno; //红包新商户订单号生成方式
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
		$pars['remark']        = '恭喜您的' . $record['fee'] . '元红包已经发放，请注意查收';
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
		$packpage['code']=$code;
		// $packpage['error_msg']=$error;
		if (is_error($procResult)) {
			$packpage['isok']=false;
			return $packpage;
		} else {
			$packpage['isok']=true;
			return $packpage;
		}
	}


    //粉丝留言模版
    public function private_chat_template($for_from_user,$mbid,$from_user,$rid,$nickname,$content){
        global $_W;
        $uniacid = $_W['uniacid'];
        $template =array(

            "touser"=>$for_from_user,//$from_user,

            "template_id"=>$mbid,

            "url"=>$_W['siteroot'] .'app/'.$this->createMobileUrl('talk', array('rid' => $rid,'uid'=>$from_user)),

            "topcolor"=>"#FF0000",

            "data"=>array('first'=>array('value'=>"您有新的留言了，赶快看看吧。",

                'color'=>"#743A3A",

            ),

                'keyword1'=>array('value'=>"[".$nickname."]",

                    'color'=>'#000000',

                ),

                'keyword2'=>array('value'=>".$content.",

                    'color'=>'#000000',

                ),

                'keyword3'=>array('value'=>date("Y-m-d H:i:s",time()),

                    'color'=>"#000000",

                ),


                'remark'=>array('value'=>"对方正在等待您的回复..！！"),

            )

        );
        $rest = $this->send_template_message(json_encode($template));
    }

    //有商城下单模版
    public function shop_template($from_user,$mbid,$content,$address){
        global $_W;
        $uniacid = $_W['uniacid'];
        $template =array(

            "touser"=>$from_user,//$from_user,

            "template_id"=>$mbid,

            "url"=>'',

            "topcolor"=>"#FF0000",

            "data"=>array('first'=>array('value'=>"亲，有人下订单了",

                'color'=>"#743A3A",

            ),

                'keyword1'=>array('value'=>"$content",

                    'color'=>'#000000',

                ),

                'keyword2'=>array('value'=>date("Y-m-d H:i:s",time()),

                    'color'=>'#000000',

                ),

                'remark'=>array('value'=>"$address"),

            )

        );
        $rest = $this->send_template_message(json_encode($template));
    }

    //有支付模版
    public function send_template($from_user,$mbid,$type,$nickname,$money,$tid){
        global $_W;
        $uniacid = $_W['uniacid'];
        $template =array(

            "touser"=>$from_user,//$from_user,

            "template_id"=>$mbid,

            "url"=>'',

            "topcolor"=>"#FF0000",

            "data"=>array('first'=>array('value'=>"亲爱的管理员有人[".$type."]，请查收。",

                'color'=>"#743A3A",

            ),

                'keyword1'=>array('value'=>"[".$type."]",

                    'color'=>'#000000',

                ),

                'keyword2'=>array('value'=>"[".$nickname."]",

                    'color'=>'#000000',

                ),

                'keyword3'=>array('value'=>"[".$money."]",

                    'color'=>'#00ff00',

                ),

                'keyword4'=>array('value'=>date("Y-m-d H:i:s",time()),

                    'color'=>"#000000",

                ),
                'keyword5'=>array('value'=>"[".$tid."]",

                    'color'=>"#000000",

                ),

                'remark'=>array('value'=>"[".$type."]支付成功，请查收！！"),

            )

        );
        $rest = $this->send_template_message(json_encode($template));
    }

    //删除订单模版
    public function del_send_template($from_user,$mbid,$type,$nickname,$money,$tid){
        global $_W;
        $uniacid = $_W['uniacid'];
        $template =array(

            "touser"=>$from_user,//$from_user,

            "template_id"=>$mbid,

            "url"=>'',

            "topcolor"=>"#FF0000",

            "data"=>array('first'=>array('value'=>"亲爱的管理员有订单被删除了。",

                'color'=>"#743A3A",

            ),

                'keyword1'=>array('value'=>"[".$type."]",

                    'color'=>'#000000',

                ),

                'keyword2'=>array('value'=>"[".$nickname."]",

                    'color'=>'#000000',

                ),



                'keyword3'=>array('value'=>date("Y-m-d H:i:s",time()),

                    'color'=>"#000000",

                ),
                'keyword4'=>array('value'=>"[".$money."]",

                    'color'=>'#00ff00',

                ),
                'keyword5'=>array('value'=>"[".$tid."]",

                    'color'=>"#000000",

                ),

                'remark'=>array('value'=>"[".$type."]已经被删除，请留意！！"),

            )

        );
        $rest = $this->send_template_message(json_encode($template));
    }

    public function send_template_message($data)

    {
//模版消息
        global $_W, $_GPC;

        $atype = 'weixin';

        $account_code = "account_weixin_code";

        load()->classs('weixin.account');

        $access_token = WeAccount::token();

        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;

        $response = ihttp_request($url, $data);

        if (is_error($response)) {

            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");

        }

        $result = @json_decode($response['content'], true);

        if (empty($result)) {

            return error(-1, "接口调用失败, 元数据: {$response['meta']}");

        } elseif (!empty($result['errcode'])) {

            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：");

        }

        return true;

    }

    function unique_arr($array2D,$stkeep=false,$ndformat=true)
    {
        //二维数组去重复
        // 判断是否保留一级数组键 (一级数组键可以为非数字)
        if($stkeep) $stArr = array_keys($array2D);

        // 判断是否保留二级数组键 (所有二级数组键必须相同)
        if($ndformat) $ndArr = array_keys(end($array2D));

        //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
        foreach ($array2D as $v){
            $v = join(",",$v);
            $temp[] = $v;
        }

        //去掉重复的字符串,也就是重复的一维数组
        $temp = array_unique($temp);

        //再将拆开的数组重新组装
        foreach ($temp as $k => $v)
        {
            if($stkeep) $k = $stArr[$k];
            if($ndformat)
            {
                $tempArr = explode(",",$v);
                foreach($tempArr as $ndkey => $ndval) $output[$k][$ndArr[$ndkey]] = $ndval;
            }
            else $output[$k] = explode(",",$v);
        }

        return $output;
    }

    function time_tran($the_time) {


        //echo $now_time;
        $now_time = time();
        $show_time = $the_time;

        $dur = $now_time - $show_time;
        if ($dur < 0) {
            return $the_time;
        } else {
            if ($dur < 60) {
                return $dur . '秒前';
            } else {
                if ($dur < 3600) {
                    return floor($dur / 60) . '分钟前';
                } else {
                    if ($dur < 86400) {
                        return floor($dur / 3600) . '小时前';
                    } else {
                        if ($dur < 259200) {//3天内
                            return floor($dur / 86400) . '天前';
                        } else {
                            return date("m-d H:i", $the_time);
                        }
                    }
                }
            }
        }
    }

    //获取图片的宽高
    function getjpegsize($img_loc) {
        $handle = fopen($img_loc, "rb") or die("Invalid file stream.");
        $new_block = NULL;
        if(!feof($handle)) {
            $new_block = fread($handle, 32);
            $i = 0;
            if($new_block[$i]=="xFF" && $new_block[$i+1]=="xD8" && $new_block[$i+2]=="xFF" && $new_block[$i+3]=="xE0") {
                $i += 4;
                if($new_block[$i+2]=="x4A" && $new_block[$i+3]=="x46" && $new_block[$i+4]=="x49" && $new_block[$i+5]=="x46" && $new_block[$i+6]=="x00") {

// Read block size and skip ahead to begin cycling through blocks in search of SOF marker

                    $block_size = unpack("H*", $new_block[$i] . $new_block[$i+1]);
                    $block_size = hexdec($block_size[1]);
                    while(!feof($handle)) {
                        $i += $block_size;
                        $new_block .= fread($handle, $block_size);
                        if($new_block[$i]=="xFF") {

// New block detected, check for SOF marker

                            $sof_marker = array("xC0", "xC1", "xC2", "xC3", "xC5", "xC6", "xC7", "xC8", "xC9", "xCA", "xCB", "xCD", "xCE", "xCF");
                            if(in_array($new_block[$i+1], $sof_marker)) {

// SOF marker detected. Width and height information is contained in bytes 4-7 after this byte.

                                $size_data = $new_block[$i+2] . $new_block[$i+3] . $new_block[$i+4] . $new_block[$i+5] . $new_block[$i+6] . $new_block[$i+7] . $new_block[$i+8];
                                $unpacked = unpack("H*", $size_data);
                                $unpacked = $unpacked[1];
                                $height = hexdec($unpacked[6] . $unpacked[7] . $unpacked[8] . $unpacked[9]);
                                $width = hexdec($unpacked[10] . $unpacked[11] . $unpacked[12] . $unpacked[13]);
                                return array($width, $height);
                            } else {

// Skip block marker and read block size

                                $i += 2;
                                $block_size = unpack("H*", $new_block[$i] . $new_block[$i+1]);
                                $block_size = hexdec($block_size[1]);
                            }
                        } else {
                            return FALSE;
                        }
                    }
                }
            }
        }
        return FALSE;
    }

    function ShengYu_Tian_Shi_Fen($unixEndTime=0)
    {
        if ($unixEndTime <= time()) { // 如果过了活动终止日期
            return '0天0时0分';
        }

        // 使用当前日期时间到活动截至日期时间的毫秒数来计算剩余天时分
        $time = $unixEndTime - time();

        $days = 0;
        if ($time >= 86400) { // 如果大于1天
            $days = (int)($time / 86400);

            $time = $time % 86400; // 计算天后剩余的毫秒数
        }

        $xiaoshi = 0;
        if ($time >= 3600) { // 如果大于1小时
            $xiaoshi = (int)($time / 3600);


            $time = $time % 3600; // 计算小时后剩余的毫秒数
        }

        $fen = (int)($time / 60); // 剩下的毫秒数都算作分
        $miao = (int)($time% 60); // 剩下的毫秒数都算作分

        if($days<10){
            $days=sprintf ( "%02d",$days);
        }
        if($xiaoshi<10){
            $xiaoshi=sprintf ( "%02d",$xiaoshi);
        }
        if($fen<10){
            $fen=sprintf ( "%02d",$fen);
        }
        if($miao<10){
            $miao=sprintf ( "%02d",$miao);
        }

        return $days.':'.$xiaoshi.':'.$fen.':'.$miao;
    }

    function get_device_type()
    {
        //全部变成小写字母
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $type = 'other';
        //分别进行判断
        if(strpos($agent, 'iphone') || strpos($agent, 'ipad'))
        {
            $type = 'ios';
        }

        if(strpos($agent, 'android'))
        {
            $type = 'android';
        }
        return $type;
    }
}
