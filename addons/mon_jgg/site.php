<?php
/**
 */
defined('IN_IA') or exit('Access Denied');

define("MON_JGG", "mon_jgg");
define("MON_JGG_RES", "../addons/" . MON_JGG . "/");
require_once IA_ROOT . "/addons/" . MON_JGG . "/CRUD.class.php";
require IA_ROOT . "/addons/" . MON_JGG . "/oauth2.class.php";

/**
 * Class Mon_JggModuleSite
 */
class Mon_JggModuleSite extends WeModuleSite
{
    public $weid;
    public $acid;
    public $oauth;


    public static $STATUS_UNDO=1;//未处理
    public static  $STATUS_APPLY=2;//已申请领奖
    public static $STATUS_COMPLETED_DH=3;//已发奖
    public static $STATUS_OVER=4;//已完成



    function __construct()
    {
        global $_W;
        $this->weid = $_W['uniacid'];

        $this->oauth = new Oauth2("", "");

    }


    /**
     * 活动管理
     */
        public function  doWebJggManage(){

            global $_W,$_GPC;

            $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
            if ($operation == 'display') {

                $pindex = max(1, intval($_GPC['page']));
                $psize = 20;
                $list = pdo_fetchall("SELECT * FROM " . tablename(CRUD::$table_jgg) . " WHERE weid =:weid  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':weid' => $this->weid));
                $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(CRUD::$table_jgg) . " WHERE weid =:weid ", array(':weid' => $this->weid));
                $pager = pagination($total, $pindex, $psize);

            } else if ($operation == 'delete') {
                $id = $_GPC['id'];
                pdo_delete(CRUD::$table_jgg, array("id" => $id));

                message('删除成功！', referer(), 'success');
            }

            include $this->template("jgg_manage");


        }


    public function  doMobileIndex(){

        global $_W,$_GPC;

        $this->checkmobile();


        $jid = $_GPC['jid'];
        $jgg=CRUD::findById(CRUD::$table_jgg,$jid);
        if(empty($jgg)){
            message("九宫格活动删除或不存在!");
        }

        $openid = $_W['fans']['from_user'];


        if (!empty($openid)) {
           $this->setClientUserInfo($openid);
        }

		if (empty($_W['fans']['follow'])){
				$follow_url=$jgg['follow_url'];
						header ( "location: $follow_url" );
			
		}
        $user=$this->findPlayUser($jid,$openid);

        if(!empty($user)){

            $playCount=$this->findUserRecordCount($jgg['id'],$user['id']);
            $limitCount=$jgg['day_play_count'];
            $leftPlayCount=$limitCount-$playCount;


            $uawards = pdo_fetchall("SELECT * FROM " . tablename(CRUD::$table_jgg_user_award) . "  WHERE jid =:jid and uid=:uid  ORDER BY  createtime desc" , array(':jid' => $jid,":uid"=>$user['id']));


        }else{

            $leftPlayCount=$jgg['day_play_count'];

        }




        $pindex = max(1, intval($_GPC['page']));
        $psize = 15;

        $awards = pdo_fetchall("SELECT a.*,b.tel as tel FROM " . tablename(CRUD::$table_jgg_user_award) . " a left join ".tablename(CRUD::$table_jgg_user)." b on a.uid=b.id WHERE a.jid =:jid  ORDER BY createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':jid' => $jid));
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(CRUD::$table_jgg_user_award) . " WHERE jid =:jid  ", array(':jid' => $jid));
        $pagecount= ceil($total / $psize);


        include $this->template('index');


    }

    public  function getpicurl($url)
    {
        global $_W;
        return $_W ['attachurl'] . $url;

    }
    /**
     * author: 012wz
     * 参与用户
     */
    public function  doWebPlay_user(){
        global $_W,$_GPC;
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

        if ($operation == 'display') {

            $jid=$_GPC['jid'];

            $jgg=CRUD::findById(CRUD::$table_jgg,$jid);

            if(empty($jgg)){
                message("九宫格活动删除或不存在");

            }


            $itemid=$_GPC['itemid'];
            $keyword=$_GPC['keyword'];

            $where = '';
            $params = array(
                ':jid' => $jid
            );


            if(!empty($keyword)){
                $where .= ' and nickname like :nickname';
                $params[':nickname']="%$keyword%";
            }


            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $list = pdo_fetchall("SELECT * FROM " . tablename(CRUD::$table_jgg_user) ." WHERE jid =:jid ".$where."  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(CRUD::$table_jgg_user) . " WHERE jid =:jid  ".$where, $params);
            $pager = pagination($total, $pindex, $psize);

        } else if ($operation == 'delete') {
            $id = $_GPC['id'];
            pdo_delete(CRUD::$table_jgg_user_record, array("uid" => $id));
            pdo_delete(CRUD::$table_jgg_user_award, array("uid" => $id));

            pdo_delete(CRUD::$table_jgg_user, array("id" => $id));
            message('删除成功！', referer(), 'success');
        }


        include $this->template("user_list");


    }


    /**
     * author:
     *  抽奖记录
     */
    public function  doWebRecordList(){
        global $_W,$_GPC;
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $jid=$_GPC['jid'];
        $uid=$_GPC['uid'];
        $jgg=CRUD::findById(CRUD::$table_jgg,$jid);


        $where = '';
        $params = array(
            ':jid' => $jid
        );


        if($_GPC['uid']!=''){
            $where.=" and r.uid=:uid";
            $params[':uid']=$uid;
        }






        $keyword = $_GPC['keywords'];




        if (!empty($keyword)) {
            $where .= ' and (u.nickname like :nickname) or (u.tel like :tel)';
            $params[':nickname'] = "%$keyword%";
            $params[':tel'] = "%$keyword%";
        }




        if ($operation == 'display') {
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $list = pdo_fetchall("SELECT r.*,u.nickname,u.tel,u.uname FROM " . tablename(CRUD::$table_jgg_user_record) . "  r left join ".tablename(CRUD::$table_jgg_user)." u on r.uid=u.id WHERE r.jid =:jid  ".$where." ORDER BY  id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,$params);

            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(CRUD::$table_jgg_user_record) . " r left join ".tablename(CRUD::$table_jgg_user)."  u  on r.uid=u.id WHERE r.jid =:jid  " .$where, $params);
            $pager = pagination($total, $pindex, $psize);

        } elseif ($operation == 'delete') {

            $id = $_GPC['id'];
            pdo_delete(CRUD::$table_jgg_user_record, array('id' => $id));
            message('删除成功！', referer(), 'success');

        }


        load()->func('tpl');
        include $this->template('record_list');

    }

    /**
     * author: 
     * 中奖记录
     */
    public  function  doWebAwardList(){
        global $_W,$_GPC;
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

        $jid=$_GPC['jid'];
        $uid=$_GPC['uid'];
        $jgg=CRUD::findById(CRUD::$table_jgg,$jid);


        $params = array(
            ':jid' => $jid
        );

        $stauts=0;
        if(!empty($_GPC['status'])){
            $stauts=$_GPC['status'];
        }

        if($stauts!=0){
            $where=" and a.status=$stauts";
        }


        if($_GPC['uid']!=''){
            $where.=" and a.uid=:uid";
            $params[':uid']=$uid;
        }


        $keyword = $_GPC['keywords'];
        if (!empty($keyword)) {
            //$where .= ' and (u.nickname like :nickname) or (u.tel like :tel)';
			 $where .= ' and u.tel like :tel';
           // $params[':nickname'] = "%$keyword%";
            $params[':tel'] = "%$keyword%";
        }


        if ($operation == 'display') {
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $list = pdo_fetchall("SELECT a.*,u.nickname,u.tel,u.uname FROM " . tablename(CRUD::$table_jgg_user_award) . " a left join ".tablename(CRUD::$table_jgg_user)." u on a.uid=u.id  WHERE a.jid =:jid ".$where." ORDER BY  a.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(CRUD::$table_jgg_user_award) . " a left join ".tablename(CRUD::$table_jgg_user)." u on a.uid=u.id  WHERE a.jid =:jid  ".$where, $params);
            $pager = pagination($total, $pindex, $psize);

        } elseif ($operation == 'delete') {
            $id = $_GPC['id'];
            pdo_delete(CRUD::$table_jgg_user_award, array('id' => $id));
            message('删除成功！', referer(), 'success');

        }elseif($operation=='fj'){

            $id = $_GPC['id'];
            CRUD::updateById(CRUD::$table_jgg_user_award,array('status'=>Mon_JggModuleSite::$STATUS_COMPLETED_DH),$id);

            message('发奖处理成功！', referer(), 'success');
        }


        load()->func('tpl');
        include $this->template('award_list');


    }


    /**
     * author:
     * 记录导出
     */
    public function  doWebRdownload(){

        require_once 'rdownload.php';
    }

    /**
     * author: 012wz
     * 用户数据导出
     */
    public function  doWebUdownload(){

        require_once 'udownload.php';

    }

    /**
     * author: 012wz
     * 中奖数据导出
     */
    public function  doWebAdownload(){
        require_once 'adownload.php';

    }







    /**
     * author: 012wz
     * 修改领奖状态
     */
    public  function  doMobileChangeStatus(){
        global $_GPC,$_W;
        $aid=$_GPC['aid'];
        $op_type=$_GPC['op_type'];


        if($op_type==0){//申请领奖
            $status=Mon_JggModuleSite::$STATUS_APPLY;

        }else if($op_type=1){//领奖确认
            $status=Mon_JggModuleSite::$STATUS_OVER;

        }


        CRUD::updateById(CRUD::$table_jgg_user_award,array('status'=>$status),$aid);


        $res=array('code'=>200);

        echo json_encode($res);


    }

    /**
     * author: codeMonkey QQ:63187280
     * 玩游戏
     */
    public function  doMobilePlay(){
        global $_GPC,$_W;

        $jid=$_GPC['jid'];
        $uid=$_GPC['uid'];
        $jgg = CRUD::findById(CRUD::$table_jgg, $jid);
        $user=CRUD::findById(CRUD::$table_jgg_user,$uid);
        $limitCount=$jgg['day_play_count'];
        $res=array();
        if(empty($jgg)){

            $res['code']=500;
            $res['msg']="九宫格活动删除或不存在!";
            echo json_encode($res);
            exit;
        }

        if(TIMESTAMP<$jgg['starttime']){
            $res['code']=500;
            $res['msg']="活动还未开始呢，歇会再来吧!";
            echo json_encode($res);
            exit;

        }

        if(TIMESTAMP>$jgg['endtime']){
            $res['code']=500;
            $res['msg']="活动已结束，下次再来吧!";
            echo json_encode($res);
            exit;
        }


        if(empty($user)){

            $res['code']=500;
            $res['msg']="用户删除或不存在!";
            echo json_encode($res);
            exit;

        }

        $clientUser = $this->getClientUserInfo();
        if (empty($clientUser)) {
            $res['code'] = 501;
            $res['msg'] = "请授权登录后再进行抽奖哦！";
            echo json_encode($res);
            exit;
        }




        $playCount=$this->findUserRecordCount($jgg['id'],$uid);
        $limitCount=$jgg['day_play_count'];

        $leftPlayCount=$limitCount-$playCount;

        if($leftPlayCount<=0){
            $res['code'] = 503;
            $res['msg'] = "今天没有机会了，明天再来抽奖吧!";
            echo json_encode($res);
            exit;
        }

        $prize=$this->createPlayRecored($jgg,$uid,$clientUser['openid']);

        $res['code'] = 200;
        $res['prize']=$prize;
        $res['status']=1;
        echo json_encode($res);
        exit;

    }





    /**
     * author: 012wz
     * @param $dzp
     * @param $uid
     * @param $openid
     * 插入记录
     */

    public function  createPlayRecored($jgg,$uid,$openid){



        $prize = $this->get_rand(array(
            "0" => $jgg['prize_p_0'],
            "1" => $jgg['prize_p_1'],
            "2" => $jgg['prize_p_2'],
            "3" => $jgg['prize_p_3'],
            "4" => $jgg['prize_p_4'],
            "5" => $jgg['prize_p_5'],
            "6" => $jgg['prize_p_6'],
            "7" => $jgg['prize_p_7']
        ));




        $user_ward_count = $this->findUserAwardCount($uid);
        $user_day_ward_count = $this->findUserDayAwardCount($uid);

        if($user_ward_count >= $jgg['award_count']) {
            $prize = 0;
        }



        if ($user_day_ward_count >= $jgg['day_award_count']) {
            $prize = 0;

        }


        if($prize != 0){

            $already_award_count=$this->findAwardLevelCount($jgg['id'], $prize);


            switch($prize){

                case 1:
                    $prize_count=$jgg['prize_num_1'];
                    break;
                case 2:
                    $prize_count=$jgg['prize_num_2'];
                    break;
                case 3:
                    $prize_count=$jgg['prize_num_3'];
                    break;
                case 4:
                    $prize_count=$jgg['prize_num_4'];
                    break;
                case 5:
                    $prize_count=$jgg['prize_num_5'];
                    break;
                case 6:
                    $prize_count=$jgg['prize_num_6'];
                    break;
                case 7:
                    $prize_count=$jgg['prize_num_7'];
                    break;

            }




            if($already_award_count >= $prize_count){//中奖数量控制
                $prize=0;
            }




        }



        switch($prize){
            case 0:
                $award_name=$jgg['prize_name_0'];
                $award_level='没中奖';
                break;
            case 1:
                $award_name=$jgg['prize_name_1'];
                $award_level=$jgg['prize_level_1'];
                break;
            case 2:
                $award_name=$jgg['prize_name_2'];
                $award_level=$jgg['prize_level_2'];
                break;
            case 3:
                $award_name=$jgg['prize_name_3'];
                $award_level=$jgg['prize_level_3'];
                break;
            case 4:
                $award_name=$jgg['prize_name_4'];
                $award_level=$jgg['prize_level_4'];
                break;
            case 5:
                $award_name=$jgg['prize_name_5'];
                $award_level=$jgg['prize_level_5'];
                break;
            case 6:
                $award_name=$jgg['prize_name_6'];
                $award_level=$jgg['prize_level_6'];
                break;
            case 7:
                $award_name=$jgg['prize_name_7'];
                $award_level=$jgg['prize_level_7'];
                break;

        }


         $recordData=array(
             'jid'=>$jgg['id'],
             'openid'=>$openid,
             'uid'=>$uid,
             'award_level'=>$award_level,
             'award_name'=>$award_name,
             'level'=>$prize,
             'createtime'=>TIMESTAMP
         );


        CRUD::create(CRUD::$table_jgg_user_record,$recordData);

        if($prize!=0){// 插入中奖记录

                   $award_record=array(
                       'jid'=>$jgg['id'],
                       'openid'=>$openid,
                       'uid'=>$uid,
                       'award_level'=>$award_level,
                       'award_name'=>$award_name,
                       'status'=>Mon_JggModuleSite::$STATUS_UNDO,
                       'level'=>$prize,
                       'createtime'=>TIMESTAMP
                   );

            CRUD::create(CRUD::$table_jgg_user_award,$award_record);

        }



        return $prize;

    }




    /**
     * 概率计算
     *
     * @param unknown $proArr
     * @return Ambigous <string, unknown>
     */
    function get_rand($proArr)
    {
        $result = '';
        // 概率数组的总概率精度
        $proSum = array_sum($proArr);
        // 概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum); // 抽取随机数
            if ($randNum <= $proCur) {
                $result = $key; // 得出结果
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset($proArr);
        return $result;
    }








    //=================================处理函数====================================//
    /**
     * author: 012wz
     * @param $type
     * @return string
     * 图片处理
     */
    public function p_img($index){

      $imgName="p".$index.".png";

        return MON_JGG_RES . "images/" . $imgName;


    }








    /**
     * author: codeMonkey QQ:63187280
     * 注册
     */
    public function  doMobileRegist(){
        global $_GPC,$_W;

        $jid=$_GPC['jid'];

        $jgg = CRUD::findById(CRUD::$table_jgg, $jid);

        $tel=$_GPC['tel'];
        $uname=$_GPC['uname'];

        $res=array();
        if(empty($jgg)){

            $res['code']=500;
            $res['msg']="九宫格活动删除或不存在";
            echo json_encode($res);
            exit;
        }
        $clientUser = $this->getClientUserInfo();
        if (empty($clientUser)) {
            $res['code'] = 501;
            $res['msg'] = "请授权登录后再进行抽奖哦！";
            echo json_encode($res);
            exit;
        }

        $dbUser=CRUD::findUnique(CRUD::$table_jgg_user,array(':jid'=>$jid,':tel'=>$tel));
        if(!empty($dbUser)){
            $res['code'] = 502;
            $res['msg'] = "该手机号码已存在，请重新注册！";
            echo json_encode($res);
            exit;
        }

        $user_data = array(
            "jid" => $jid,
            "openid" => $clientUser['openid'],
            "nickname" => $clientUser['nickname'],
            "tel"=>$tel,
            "uname"=>$uname,
            "headimgurl" => $clientUser['headimgurl'],
            "createtime"=>TIMESTAMP
        );

        CRUD::create(CRUD::$table_jgg_user,$user_data);

        $res['code'] = 200;

        echo json_encode($res);
        exit;



    }



    public function findPlayUser($jid,$openid){

       $user= CRUD::findUnique(CRUD::$table_jgg_user,array(':jid'=>$jid,':openid'=>$openid));

        return $user;
    }

    public function str_murl($url){
        global $_W;
        return $_W['siteroot'].'app'.str_replace('./','/',$url);

    }




    public function  checkmobile(){

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {
           // echo "本页面仅支持微信访问!非微信浏览器禁止浏览!";
           // exit();
        }

    }


    /**
     * author: 012wz
     * @param $openid
     */
    public function setClientUserInfo($openid)
    {

        if (!empty($openid)) {

            load()->model('account');	 
           $token =WeAccount::token();
          // $token = WeAccount::token(WeAccount::TYPE_WEIXIN);
            if (empty($token)) {
                message("获取accessToken失败");
            }
            $userInfo = $this->oauth->getUserInfo($token, $openid);
            $cookie = array();
            $cookie['openid'] = $openid;
            if (!empty($userInfo)) {
                $cookie['nickname'] = $userInfo['nickname'];
                $cookie['headimgurl'] = $userInfo['headimgurl'];
            }
            $session = base64_encode(json_encode($cookie));
            isetcookie('__monjgguser', $session, 24 * 3600 * 365);
            return $userInfo;
        }


    }


    /**
     * author: 012wz
     * 兑换处理
     */
    public function  statusText($stauts){

        $statusText="未知状态";
        switch($stauts){
            case Mon_JggModuleSite::$STATUS_UNDO:
                $statusText="已中奖（待用户申请领奖）";
                break;
            case Mon_JggModuleSite::$STATUS_APPLY:
                $statusText="用户申请领奖";
                break;
            case Mon_JggModuleSite::$STATUS_COMPLETED_DH:
                $statusText="已发奖（待用户确认收奖）";
                break;
            case Mon_JggModuleSite::$STATUS_OVER:
                $statusText="领奖完成（用户已确认）";
                break;
        }

        return $statusText;

    }


    /**
     * 玩的次数
     * author: 012wz
     * @param $did
     * @param $uid
     * @return bool
     */
    public function  findUserRecordCount($jid,$uid)
    {

        $today_beginTime = strtotime(date('Y-m-d' . '00:00:00', TIMESTAMP));
        $today_endTime = strtotime(date('Y-m-d' . '23:59:59', TIMESTAMP));

        $count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(CRUD::$table_jgg_user_record) . " WHERE  uid=:uid and jid=:jid and createtime<=:endtime and  createtime>=:starttime ", array(':uid' => $uid, ":jid" =>$jid, ":endtime" => $today_endTime, ":starttime" => $today_beginTime));
        return $count;
    }


    /**
     * author: 012wz
     * @param $uid
     * @return bool
     * 中奖次数限制
     */
    public function  findUserAwardCount($uid){

        $count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(CRUD::$table_jgg_user_award) . " WHERE  uid=:uid  and level <> 0 ", array(':uid' => $uid));
        return $count;
    }


    /**
     * @param $uid
     * @return bool
     * 中奖次数限制
     */
    public function  findUserDayAwardCount($uid){
        $today_beginTime = strtotime(date('Y-m-d' . '00:00:00', TIMESTAMP));
        $today_endTime = strtotime(date('Y-m-d' . '23:59:59', TIMESTAMP));
        $count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(CRUD::$table_jgg_user_award) . " WHERE  uid=:uid and  createtime<=:endtime and  createtime>=:starttime and level <> 0 ", array(':uid' => $uid, ":endtime" => $today_endTime, ":starttime" => $today_beginTime));
        return $count;
    }


    /**
     * author: 012wz
     * @param $level
     * @return bool
     * 查找某个奖品中奖的数量
     */
    public function  findAwardLevelCount($jid,$level){

        $count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(CRUD::$table_jgg_user_award) . " WHERE  level=:level and jid=:jid", array(':level' => $level,':jid'=>$jid));
        return $count;

    }




    /**
     * author:codeMonkey QQ 631872807
     * 获取哟规划信息
     * @return array|mixed|stdClass
     */
    public function  getClientUserInfo()
    {
        global $_GPC;
        $session = json_decode(base64_decode($_GPC['__monjgguser']), true);
        return $session;

    }




}