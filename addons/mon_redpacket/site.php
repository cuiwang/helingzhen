<?php
/**
 * @author codeMonkey
 * qq:631872807
 */
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT."/addons/mon_redpacket/jssdk.class.php";
class Mon_redpacketModuleSite extends WeModuleSite
{

    public $table_redpacket = "redpacket";

    public $table_redpacket_reply = "redpacket_reply";

    public $table_redpacket_user = "redpacket_user";

    public $table_redpacket_firend = "redpacket_firend";

    public $table_redpacket_setting = "redpacket_setting";

    public $table_redpacket_token = "redpacket_token";

    public $table_repacket_award = "redpacket_award";

    public $nomal = true;


    public $weid;
    public function __construct() {
        global $_W;
        $this->weid = IMS_VERSION<0.6?$_W['weid']:$_W['uniacid'];
    }

    /**
     * 红包活动定义
     */
    public function doWebPacket()
    {
        global $_W, $_GPC;
        $operation = ! empty($_GPC['op']) ? $_GPC['op'] : 'display';



        if ($operation == 'post') { // 添加
            $id = intval($_GPC['id']);
            
            if (! empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_redpacket) . " WHERE id = :id", array(
                    ':id' => $id
                ));
                if (empty($item)) {
                    message('抱歉，分享删除或或不存在！', '', 'error');
                }
                
                $awards = pdo_fetchall("SELECT * FROM " . tablename($this->table_repacket_award) . " WHERE pid = :pid ORDER BY `id` DESC", array(
                    ':pid' => $id
                ));
                $item['endtime'] = date("Y-m-d  H:i", $item['endtime']);
                $item['begintime'] = date("Y-m-d  H:i", $item['begintime']);
				//输出时显示html源代码   同时mysql数据结构awardtip ，fanpaitip，sharetip，packetsummary 数据类型由varchar(100)改为vchar(1000)以上，因为html代码的长度比较长
            }
            if (checksubmit('submit')) {
                
                if (empty($_GPC['rname'])) {
                    message('请输入活动名称!');
                }
                
                if (empty($_GPC['packetsummary'])) {
                    message("请输入活动摘要");
                }
                
                if (empty($_GPC['sharetip'])) {
                    message("请输入分享提示");
                }
                
                if (empty($_GPC['fanpaitip'])) {
                    message("请输入翻牌提示文字");
                }
                
                if (empty($_GPC['awardtip'])) {
                    message("请输入获取奖提示文字");
                }
                
                if (empty($_GPC['carebtn'])) {
                    message("请输入关注按钮文字");
                }
                
                if (empty($_GPC['countlimit'])) {
                    message('请输入活动限制人数!');
                }
                
                if(empty($_GPC['sortcount'])){
                	
                	  message('请输入排名人数');
                	
                }
                
                if (empty($_GPC['begintime'])) {
                    message('请输入活动开始时间!');
                }
                
                if (empty($_GPC['endtime'])) {
                    message('请输入活动结束时间!');
                }
                
                if ($_GPC['limitType'] == 0) {
                    if (empty($_GPC['totallimit'])) {
                        
                        message('请输入好友总翻牌次数');
                    }
                }
                
                if ($_GPC['limitType'] == 1) {
                    if (empty($_GPC['daylimit'])) {
                        
                        message('请输入好友每天翻牌次数');
                    }
                }
                
                if (empty($_GPC['banner_pic'])) {
                    message('请上传活动背景Bannle!');
                }
                
               
                
                if (empty($_GPC['rule'])) {
                    message('请输入活动规则！');
                }
                
                if(empty($_GPC['careurl'])){
                	
                	  message('请输入关注所处引导页面！');
                }
                
                
                
                if(empty($_GPC['sharebtn'])){
                	 
                	message('请输入邀请好友攒钱按钮文字！');
                }
                
                
                if(empty($_GPC['fsharebtn'])){
                
                	message('请输入好友帮助邀请攒钱按钮文字！');
                }
                
                
                
                if (empty($_GPC['incomelimit'])) {
                    message('请输入金额限制!');
                }
                
                if (empty($_GPC['start'])) {
                    message("请输入初始金额");
                }
                
                if ($_GPC['steptype'] == 0) {
                    
                    if (empty($_GPC['step'])) {
                        message("请输入翻牌固定金额");
                    }
                }
                
                if ($_GPC['steptype'] == 1) {
                    
                    if (empty($_GPC['steprandom'])) {
                        message("请输入翻牌随机金额");
                    }
                }
                
                if (empty($_GPC['addp'])) {
                    
                    message("请输入增加金额的概率");
                }
                
                if (empty($_GPC['shareTitle'])) {
                    message("请输入分享标题");
                }
                
                if (empty($_GPC['shareImg'])) {
                    message("请上传分享图标");
                }
                
                if (empty($_GPC['shareContent'])) {
                    message("请输入分享说明");
                }
                
                $data = array(
                    'weid' => $this->weid,
                    'packetsummary' =>htmlspecialchars_decode($_GPC["packetsummary"]),
                    'sharetip' => htmlspecialchars_decode($_GPC['sharetip']),
                    'fanpaitip' => htmlspecialchars_decode($_GPC['fanpaitip']),
                    'awardtip' => htmlspecialchars_decode($_GPC['awardtip']),
                    'carebtn' => $_GPC['carebtn'],
                    'name' => $_GPC['rname'],
                    'countlimit' => $_GPC['countlimit'],
                    'incomelimit' => $_GPC['incomelimit'],
                    'begintime' => strtotime($_GPC['begintime']),
                    'endtime' => strtotime($_GPC['endtime']),
                    'banner_pic' => $_GPC['banner_pic'],
                    'careurl' => $_GPC['careurl'],
                    'rule' => htmlspecialchars_decode($_GPC['rule']),
                    'shareTitle' => $_GPC['shareTitle'],
                    'shareImg' => $_GPC['shareImg'],
                    'shareContent' => $_GPC['shareContent'],
                    'createtime' => TIMESTAMP,
                    'limitType' => $_GPC['limitType'],
                    'daylimit' => $_GPC['daylimit'],
                    'totallimit' => $_GPC['totallimit'],
                    'start' => $_GPC['start'],
                    'step' => $_GPC['step'],
                    'steprandom' => $_GPC['steprandom'],
                    'steptype' => $_GPC['steptype'],
                    'addp' => $_GPC['addp'],
                	'cardbg'=>$_GPC['cardbg'],
                	'sortcount'=>$_GPC['sortcount']	,
                	'sharebtn'=>$_GPC['sharebtn'],
                	'fsharebtn'=>$_GPC['fsharebtn'],
					'fanpaimustfollow'=>$_GPC['fanpaimustfollow'],
					'fanpaiurl'=>$_GPC['fanpaiurl']
                );
                if (! empty($id)) {
                    pdo_update($this->table_redpacket, $data, array(
                        'id' => $id
                    ));
                } else {
                    pdo_insert($this->table_redpacket, $data);
                    $id = pdo_insertid();
                }
                
                // 奖品
                $awardids = array();
                $award_ids = $_GPC['award_id'];
                $award_names = $_GPC['award_name'];
                $award_points = $_GPC['award_point'];
                $award_nums = $_GPC['award_num'];
                $award_caches = array();
                if (is_array($award_ids)) {
                    foreach ($award_ids as $key => $value) {
                        $value = intval($value);
                        $d = array(
                            "pid" => $id,
                            "point" => $award_points[$key],
                            "name" => $award_names[$key],
                            "num" => $award_nums[$key]
                        );
                        
                        if (empty($value)) {
                            pdo_insert($this->table_repacket_award, $d);
                            $awardids[] = pdo_insertid();
                        } else {
                            pdo_update($this->table_repacket_award, $d, array(
                                "id" => $value
                            ));
                            $awardids[] = $value;
                        }
                        $d['id'] = $awardids[count($awardids) - 1];
                        $award_caches[] = $d;
                    }
                    if (count($awardids) > 0) {
                        pdo_query("delete from " . tablename($this->table_repacket_award) . " where pid='{$id}' and id not in (" . implode(",", $awardids) . ")");
                    } else {
                        pdo_query("delete from " . tablename($this->table_repacket_award) . " where pid='{$id}'");
                    }
                }
                
                message('更新红包有礼活动成功！', $this->createWebUrl('Packet', array(

                    'op' => 'display'
                )), 'success');
            }
        } elseif ($operation == 'display') {
            $pindex = max(1, intval($_GPC['page']));
            
            $psize = 20;
            
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_redpacket) . " WHERE weid = '{$_W['uniacid']}'  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_redpacket) . " WHERE weid = '{$_W['uniacid']}'");
            $pager = pagination($total, $pindex, $psize);
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            
            // 删除活动
            
            pdo_delete($this->table_redpacket_firend, array(
                'pid' => $id
            )); // 删除用户表
            
            
            pdo_delete($this->table_redpacket_user, array(
            'pid' => $id
            )); // 删除用户表
            
            pdo_delete($this->table_repacket_award, array(
            'pid' => $id
            )); // 删除用户表
            
            pdo_delete($this->table_redpacket, array(
            'id' => $id
            )); // 删除用户表
            
      
            message('删除成功！', referer(), 'success');
        }

        load()->func('tpl');
        include $this->template('redpacket');
    }
    
    /**
     * 好友帮助翻牌记录
     */
    public function doMobilefirendSort(){
    	global $_GPC, $_W;
    	$pid=$_GPC['pid'];
    	$uid = $_GPC['uid'];
    	$packet=$this->findPacket($pid);
    	
    	if(empty($packet)){
    		message("活动删除不不存在!");
    		 
    	}
    	
    	$user = $this->findUserId($uid);
    	
    	if(empty($user)){
    		message("用户不存在!");
    	}
    	
    	
    	$list = pdo_fetchall("SELECT sum(income) as tincome,uid,pid,nickname,headimgurl  FROM `ims_redpacket_firend` where pid=:pid and uid=:uid group by openid order by tincome desc limit 0,10", array(
    			":pid" => $pid,
    			":uid"=>$uid
    	));
    	
    	
    	
    	
    	 include $this->template('firendSort');
    	
    
    	
    }

    /**
     * 虚拟人数
     */
    public function  doWebvirtualUser(){
        global $_GPC, $_W;
         $pid=$_GPC['pid'];
         
         $packet=$this->findPacket($pid);
         
         if(empty($packet)){
             message("活动删除不不存在");
             
         }
         
         
         $operation = ! empty($_GPC['op']) ? $_GPC['op'] : 'display';
         
         
         if ($operation == 'post') { // 添加
             $id = intval($_GPC['id']);
         
             if (! empty($id)) {
                 $item = pdo_fetch("SELECT * FROM " . tablename($this->table_redpacket_user) . " WHERE id = :id", array(
                     ':id' => $id
                 ));
                 if (empty($item)) {
                     message('抱歉，分享删除或或不存在！', '', 'error');
                 }
         
                
             }
             if (checksubmit('submit')) {
         
                 if (empty($_GPC['nickname'])) {
                     message('请输入虚拟用户昵称!');
                 }
         
                 if (empty($_GPC['tel'])) {
                     message("请输入虚拟用户电话!");
                 }
                 
                 if (empty($_GPC['headimgurl'])) {
                     message("请上传用户头头像!");
                 }
         
                 if (empty($_GPC['income'])) {
                     message("请输入虚拟用户金额!");
                 }
         
              
                 $awards = pdo_fetchall("SELECT * FROM " . tablename($this->table_repacket_award) . " WHERE pid = :pid ORDER BY point ASC ", array(
                     ':pid' => $pid
                 ));
                 $lowpoint = $awards[0]["point"]; // 最低分数
                 $status=0;
                 if($_GPC['income']>=$lowpoint){//已中奖
                 
                     $status=1;
                 
                 }else{//未中奖
                     $status=0;
                 
                 
                 }
                 

                 $data = array(
                     'pid' => $pid,
                     'nickname'=>$_GPC['nickname'],
                     'headimgurl'=>$_GPC['headimgurl'],
                     'tel'=>$_GPC['tel'],
                     'income'=>$_GPC['income'],
                     'createtime' => TIMESTAMP,
                     'virtual'=>1,
                     'status'=>$status
                 );
                 if (! empty($id)) {
                     pdo_update($this->table_redpacket_user, $data, array(
                     'id' => $id
                     ));
                 } else {
                     pdo_insert($this->table_redpacket_user, $data);
                   
                 }
         
              
                 message('添加虚拟用户成功！', $this->createMobileUrl('fanslist', array(

                 'pid'=>$pid,
               
                     )), 'success');
             }
         } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            
          
            pdo_delete($this->table_redpacket_user, array(
            'id' => $id
            )); // 删除用户表
          
            
      
            message('删除成功！', referer(), 'success');
        }
        
         

        load()->func('tpl');
         include $this->template('virtualuser');
         
        
        
        
    }



    /**
     * author: codeMonkey QQ:631872807
     * @param $url
     */
    public function  doMobileJsSign(){
        global $_W, $_GPC;
		
        $url=urldecode($_GPC['link']);

        $wechat = pdo_fetch("select * from ".tablename('account_wechats')." where uniacid=:uniacid limit 1",array(":uniacid"=>$this->weid));
        if($wechat){
            $appid = $wechat['key'];
            $appsecret = $wechat['secret'];

        }


        $weixin = new jssdk(0, $url,$appid,$appsecret);

        $signPackage= $weixin->get_sign();

        echo json_encode($signPackage);



    }
    
    
    public function  checkmobile(){
    	
    	$user_agent = $_SERVER['HTTP_USER_AGENT'];
    	if (strpos($user_agent, 'MicroMessenger') === false) {
    		echo "本页面仅支持微信访问!非微信浏览器禁止浏览!";
    		exit();
    	}
    	
    }
    /**
     * 参加活动用户列表
     */
    public function doWebfanslist()
    {
        global $_GPC, $_W;
        $pid = intval($_GPC['pid']);
        if (empty($pid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $where = '';
        $params = array(
            ':pid' => $pid
        );
        
        if ($_GPC['status'] != '') {
            $where .= ' and status=:status';
            $params[':status'] = intval($_GPC['status']);
        }
        
        if($_GPC['virtual']!=''){
            
            $where .= ' and virtual=:virtual';
            
            $params[':virtual'] = intval($_GPC['virtual']);
            
        }
        
        
        if (! empty($_GPC['keywords'])) {
            $where .= ' and tel<>\'\' and tel like :tel';
            $params[':tel'] = "%{$_GPC['keywords']}%";
        } else {
            $where .= " and tel<>:tel";
            $params[':tel'] = "";
        }
        
        $total = pdo_fetchcolumn("SELECT count(*) FROM " . tablename($this->table_redpacket_user) . " WHERE pid = :pid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 12;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        
        $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_redpacket_user) . " WHERE pid = :pid " . $where . " ORDER BY income DESC " . $limit, $params);
        
        $awards = pdo_fetchall("select * from " . tablename($this->table_repacket_award) . " where pid=:pid ", array(
            ":pid" => $pid
        ));
        
        foreach ($list as &$row) {
            $awardnames = array();
            foreach ($awards as $award) {
                if ($row['income'] >= $award['point']) {
                    $awardnames[] = $award;
                }
            }
            $row['awardnames'] = $awardnames;
            
            if (! empty($row['awardid'])) {
                $row['awardname'] = pdo_fetchcolumn("select name from " . tablename($this->table_repacket_award) . " where id=:id limit 1 ", array(
                    ":id" => $row['awardid']
                ));
            }
        }
        
        unset($row);
        
        include $this->template('userlist');
    }

    public function doWebgetaward()
    {
        global $_W, $_GPC;
        
        $pid = $_GPC['id'];
        
        $packet = $this->findPacket($pid);
        if (empty($packet)) {
            message('未找到活动!', '', 'error');
        }
        
        $fansid = intval($_GPC['fansid']);
        $fans = pdo_fetch("select * from " . tablename($this->table_redpacket_user) . " where pid=:pid and id=:id limit 1", array(
            ":pid" => $pid,
            ":id" => $fansid
        ));
        if (empty($fans)) {
            message('未找到用户!', '', 'error');
        }
        
        $awardid = intval($_GPC['awardid']);
        $award = pdo_fetch("select * from " . tablename($this->table_repacket_award) . " where pid=:pid and id=:id limit 1 ", array(
            ":pid" => $pid,
            ":id" => $awardid
        ));
        if (empty($award)) {
            message('未找到礼品!', '', 'error');
        }
        
        if ($award['num'] <= 0) {
            message('礼品数已经不足，无法领取了!', '', 'error');
        }
        pdo_update($this->table_repacket_award, array(
            'num' => $award['num'] - 1
        ), array(
            "id" => $awardid,
            "pid" => $pid
        ));
        
        pdo_update($this->table_redpacket_user, array(
            "status" => 2,
            "awardid" => $awardid,
            "awardtime" => time()
        ), array(
            "pid" => $pid,
            "id" => $fansid
        ));
        message('领奖成功!', referer(), "success");
    }

    /**
     * 好友翻牌
     */
    public function doMobileFirend()
    {
        global $_W, $_GPC;
        $this->checkmobile();
        $uid = $_GPC['uid'];
        $pid = $_GPC['pid'];
        
        $packet = $this->findPacket($pid);
        if (empty($packet)) {
            message("活动不存在或已删除");
        }
        
        
        if(TIMESTAMP<$packet['begintime']){
            message("活动未开始");
        }
        
      
        
        
        
        
        
        
        $user = $this->findUserId($uid);
        if(empty($user)){
            message("用户不存在");
        }
        
        $openid = $_GPC['openid'];
        $accessToken = $_GPC['access_token'];
        $firend = $this->getUserInfo($openid, $accessToken); // 好友信息
        
        $firendExists = false;
        
        if($openid==$user['openid']){//用户是哥们自己
            
            include $this->template("mypacket");
            exit();
        }
		
		//判断翻牌人是否关注公众号
		$isfollow = pdo_fetchall("SELECT * FROM " . tablename('mc_mapping_fans') . " WHERE acid = '" . $this->weid . "' and openid = '" . $openid . "' and follow = 1;");
		if(!$isfollow&&$packet['fanpaimustfollow'] == 1){
		   header("location: $packet[fanpaiurl]");//活动结束跳转中奖页面
           exit;
		}
        
		
        $firendDbUser = $this->findUserByOpenid($openid, $pid);
        if (! empty($firendDbUser)) {
            $firendExists = true; // 该用户已注册过
        }
        
      
        
        
        
        
        if (TIMESTAMP > $packet['endtime']) {
        
            $awradsUrl = $_W['siteroot'] . $this->createMobileUrl('Awards', array(
        
                'pid' => $pid
            ));
        
            header("location: $awradsUrl");//活动结束跳转中奖页面
            exit();
        }
        
        
        
        
        
        
        $firendHelpCount = 0;
        $limitType = $packet['limitType'];
        
        $daylimit = $packet['daylimit'];
        $totallimit = $packet['totallimit'];
        
        if ($limitType == 1) { // 每天限制
            
            $firendHelpCount = pdo_fetchcolumn("SELECT count(*) FROM " . tablename($this->table_redpacket_firend) . " WHERE uid = :uid and openid=:openid and TO_DAYS( DATE_FORMAT( FROM_UNIXTIME(  `createtime` ) ,  '%Y-%m-%d' ) ) = TO_DAYS( NOW( ) ) ", array(
                ':uid' => $uid,
                ":openid" => $openid
            ));
        } else 
            if ($limitType == 0) { // 总助力限制
                
                $firendHelpCount = pdo_fetchcolumn("SELECT count(*) FROM " . tablename($this->table_redpacket_firend) . " WHERE uid = :uid and openid=:openid", array(
                    ':uid' => $uid,
                    ":openid" => $openid
                ));
            }
        
        $leftDayCount = $daylimit - $firendHelpCount;
        
        $leftTotalCount = $totallimit - $firendHelpCount; // 总限制次数
        $allow = true;
        
        if ($limitType == 0 && $leftTotalCount <= 0) { // 没个好友稚嫩改之一次
            $allow = false;
            $msg = "亲你的翻牌次数已用完!";
        }
        if ($leftDayCount <= 0 && $limitType == 1) {
            
            $allow = false;
            $msg = "今天翻牌翻牌次数已用完，明天再来吧!";
        }
        
        include $this->template("firend");
    }

    /**
     * 好友翻牌
     */
    public function doMobileFirendFanpai()
    {
        global $_W, $_GPC;
        $this->checkmobile();
        $uid = $_GPC['uid'];
        $pid = $_GPC['pid'];
        
        $fid = $_GPC['fopenid'];
        $fnickname = $_GPC['fnickname'];
        $fheadUrl = $_GPC['fheadUrl'];
        
        $packet = $this->findPacket($pid);
        $user = $this->findUserId($uid);
        $res = array();
        
        if (empty($packet)) {
            
            $res["msg"] = "红包不存在";
            $res["code"] = 501;
        } else {
            
            $firendHelpCount = 0;
            $limitType = $packet['limitType'];
            
            $daylimit = $packet['daylimit'];
            $totallimit = $packet['totallimit'];
            
            if ($limitType == 1) { // 每天限制
                
                $firendHelpCount = pdo_fetchcolumn("SELECT count(*) FROM " . tablename($this->table_redpacket_firend) . " WHERE uid = :uid and openid=:openid and TO_DAYS( DATE_FORMAT( FROM_UNIXTIME(  `createtime` ) ,  '%Y-%m-%d' ) ) = TO_DAYS( NOW( ) ) ", array(
                    ':uid' => $uid,
                    ":openid" => $fid
                ));
            } else 
                if ($limitType == 0) { // 总助力限制
                    
                    $firendHelpCount = pdo_fetchcolumn("SELECT count(*) FROM " . tablename($this->table_redpacket_firend) . " WHERE uid = :uid and openid=:openid", array(
                        ':uid' => $uid,
                        ":openid" => $fid
                    ));
                }
            
            $leftDayCount = $daylimit - $firendHelpCount;
            
            $leftTotalCount = $totallimit - $firendHelpCount; // 总限制次数
            
            if ($limitType == 0 && $leftTotalCount <= 0) { // 没个好友稚嫩改之一次
                
                $res["msg"] = "亲你的翻牌次数已用完!";
                $res["code"] = 502;
            }
            if ($leftDayCount <= 0 && $limitType == 1) {
                $res["msg"] = "今天翻牌翻牌次数已用完，明天再来吧!";
                $res["code"] = 503;
            } else 
                if (($limitType == 0 && $leftTotalCount > 0) || ($limitType == 1 && $leftDayCount > 0)) {
                    
                    $left = 0;
                    if ($limitType == 0) {
                        $left = $leftTotalCount;
                    } else 
                        if ($limitType == 1) {
                            $left = $leftDayCount;
                        }
                    
                    $incomelimit = $packet['incomelimit'];
                    $income = $user['income'];
                    $score = 0;
                    $addP = $packet['addp'];
                    
                    $op = $this->get_rand(array(
                        "+" => $addP,
                        "-" => (100 - $addP)
                    ));
                    
                    if ($packet['steptype'] == 0) {
                        $score = $packet['step'];
                    } else 
                        if ($packet['steptype'] == 1) {
                            
                            $score = rand(0, $packet['steprandom'] * 100) / 100;
                        }
                    
                    if ($op == "+") {
                        $income = $income + $score;
                    } else 
                        if ($op == "-") {
                            $income = $income - $score;
                        }
                    
                    if ($income > $incomelimit) {
                        $income = $incomelimit;
                    }
                    
                    
                    $awards = pdo_fetchall("SELECT * FROM " . tablename($this->table_repacket_award) . " WHERE pid = :pid ORDER BY point ASC ", array(
                        ':pid' => $pid
                    ));
                    $lowpoint = $awards[0]["point"]; // 最低分数
                    $status=0;
                    if($income>=$lowpoint){//已中奖
                        
                        $status=1;
                        
                    }else{//未中奖
                        $status=0;
                        
                        
                    }
                    
                    $fincome=0;
                   
                    if ($op == "+") {
                    	$fincome=$score;
                    }
                    
                    if ($op == "-") {
                    	$fincome=-$score;
                    }
                    
                    
                    $data = array(
                        'uid' => $uid,
                        'openid' => $fid,
                        'nickname' => $fnickname,
                        'headimgurl' => $fheadUrl,
                        'pid' => $pid,
                        'income'=>$fincome,
                        'createtime' => TIMESTAMP
                    );
                    pdo_insert($this->table_redpacket_firend, $data); // 记录助力人
                    
                    $updatedata = array(
                        'helpcount' => $user['helpcount'] + 1,
                        'income' => $income,
                        'status'=>$status
                    );
                    
                    // 更新user 表数据
                    pdo_update($this->table_redpacket_user, $updatedata, array(
                        'id' => $uid
                    ));
                    
                    $res["code"] = 200;
                    if ($op == "+") {
                        $res["result"] = "+" . $score;
                    }
                    
                    if ($op == "-") {
                        $res["result"] = "-" . $score;
                    }
                    
                    $res['left'] = $left - 1;
                }
        }
        
        echo json_encode($res);
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

    /**
     * 详细信息
     */
    public function doMobilePacketDetail()
    {
        global $_W, $_GPC;
        $this->checkmobile();
        $pid = $_GPC['pid'];
        
        $packet = $this->findPacket($pid);
        if (empty($packet)) {
            message("活动不存在或已删除");
        }
        
        
        
        
       $awards= pdo_fetchall("SELECT * FROM " . tablename($this->table_repacket_award) . " WHERE pid = :pid order by point asc", array(':pid' => $pid));
        
        
        
        
        
        
        
        include $this->template("packetdetail2");
    }

    /**
     * 我的账户
     */
    public function doMobileMyPacket()
    {
        global $_W, $_GPC;
        $this->checkmobile();
        $uid = $_GPC['uid'];
        $pid = $_GPC['pid'];
        
        $packet = $this->findPacket($pid);
        $user = $this->findUserId($uid);
        
        
       
        $firendlist = pdo_fetchall("SELECT sum(income) as tincome,uid,pid,nickname,headimgurl  FROM `ims_redpacket_firend` where pid=:pid and uid=:uid group by openid order by createtime  desc limit 0,10", array(
        			":pid" => $pid,
        			":uid"=>$uid
        	));
        	 
        
        include $this->template("mypacket");
    }

    /**
     * 查看好友的钱包
     */
    public function doMobileUserPacket()
    {
        global $_W, $_GPC;
        $this->checkmobile();
        $uid = $_GPC['uid'];
        $pid = $_GPC['pid'];
		$openid = $_GPC['openid'];
        $packet = $this->findPacket($pid);
        $user = $this->findUserId($uid);
		
		$firendDbUser = $this->findUserByOpenid($openid, $pid);
        if (! empty($firendDbUser)) {
            $firendExists = true; // 该用户已注册过
        }
        
        
        include $this->template("userpacket");
    }
    
    /**
     * 卡片背景
     * @return string
     */
    public function  cardbg(){
    	
    	
    	$img_url = '../addons/mon_redpacket/images/card_back.png';
    	
    	return $img_url;
    	
    	
    }

    /**
     * 中奖信息
     */
    public function doMobileAwards()
    {
        global $_W, $_GPC;
        $this->checkmobile();
        
        $openid = $_GPC['openid'];
        $pid = $_GPC['pid'];
        
        $packet = $this->findPacket($pid);
        if (empty($packet)) {
            message("红包活动删除或不存在!");
        }
        
        $awards = pdo_fetchall("SELECT * FROM " . tablename($this->table_repacket_award) . " WHERE pid = :pid ORDER BY point ASC ", array(
            ':pid' => $pid
        ));
        $lowpoint = $awards[0]["point"]; // 最低分数
        $list = pdo_fetchall("SELECT @rownum:=@rownum+1 AS rowno ,u.*  FROM (SELECT @rownum:=0 ) r , " . tablename($this->table_redpacket_user) . " u WHERE pid = :pid and income>=:lowincome ORDER BY income DESC ", array(
            ":pid" => $pid,
            ":lowincome" => $lowpoint
        ));
        
        $user = $this->findUserByOpenid($openid, $pid);
        
      
        
        if (! empty($user)) { // 用户已存在
            
            $uawardnames = array();
            $index = 0;
            foreach ($awards as $award) {
                if ($user['income'] >= $award['point']) {
                    $award['i'] = $index;
                    $uawardnames[] = $award;
                    $index ++;
                }
            }
            
            $user['uawardnames'] = $uawardnames;
            
            if (! empty($user['awardid'])) {
                $user['awardname'] = pdo_fetchcolumn("select name from " . tablename($this->table_repacket_award) . " where id=:id limit 1 ", array(
                    ":id" => $user['awardid']
                ));
            }
            
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_redpacket_user) . " WHERE income>:income and pid=:pid", array(
                ":income" => $user['income'],
				":pid"=>$pid
            ));
            
            $userSort = $total + 1;
        }
        
        foreach ($list as &$row) {
            $awardnames = array();
            $index = 0;
            foreach ($awards as $award) {
                if ($row['income'] >= $award['point']) {
                    $award['i'] = $index;
                    $awardnames[] = $award;
                    $index ++;
                }
            }
            $row['awardnames'] = $awardnames;
            
            if (! empty($row['awardid'])) {
                $row['awardname'] = pdo_fetchcolumn("select name from " . tablename($this->table_repacket_award) . " where id=:id limit 1 ", array(
                    ":id" => $row['awardid']
                ));
            }
        }
        
        unset($row);
        include $this->template("userawards");
    }

    /**
     * 首页
     */
    public function doMobileindex()
    {
        global $_W, $_GPC;
        $this->checkmobile();
        $pid = $_GPC['pid'];
        
        $openid = $_GPC['openid'];
        $accessToken = $_GPC['access_token'];
        
        // echo "openid". $openid."<br/>";
        
        // echo "token".$accessToken;
        // exit();
        $packet = $this->findPacket($pid);
        if (empty($packet)) {
            message("活动删除或不存在");
        }
        
        
        if(TIMESTAMP<$packet['begintime']){
            message("活动未开始");
        }
        
        if (TIMESTAMP > $packet['endtime']) {
            
            $awradsUrl = $_W['siteroot'] .'app/'. substr($this->createMobileUrl('Awards', array(
                
                'pid' => $pid,
                'openid' => $openid
            )),2);
            
            header("location: $awradsUrl");
            exit();
        }
        
        $user = $this->findUserByOpenid($openid, $pid);
        
        if (empty($user)) { // 哥们没有报名
            
            $userInfo = $this->getUserInfo($openid, $accessToken); //
            
            include $this->template("regist");
        } else {
            
        	

        	$firendlist = pdo_fetchall("SELECT sum(income) as tincome,uid,pid,nickname,headimgurl  FROM `ims_redpacket_firend` where pid=:pid and uid=:uid group by openid order by createtime  desc limit 0,10", array(
        			":pid" => $pid,
        			":uid"=>$user['id']
        	));
        	 
        
        	
        	
            include $this->template("mypacket");
        }
    }

    public function doMobiletest()
    {
        global $_W, $_GPC;
        
        $openid = $_W['fans']['from_user'];
        
        $accessToken = $this->getAccessToken();
        $userInfo = $this->getUserInfo($openid, $accessToken);
        
        var_dump($userInfo);
    }

    /**
     * 注册
     */
    public function doMobileRegist()
    {
        global $_W, $_GPC;
        $this->checkmobile();
        $pid = $_GPC['pid'];
        $openid = $_GPC['openid'];
        $sex = $_GPC['sex'];
        $nickname = $_GPC['nickname'];
        $headimgurl = $_GPC['headimgurl'];
        $uname = $_GPC['uname'];
        $tel = $_GPC['tel'];
        $packet = $this->findPacket($pid);
        $data = array(
            'pid' => $pid,
            'name' => $uname,
            'tel' => $tel,
            'openid' => $openid,
            'sex' => $sex,
            'nickname' => $nickname,
            'headimgurl' => $headimgurl,
            'income' => $packet['start'],
            'createtime' => TIMESTAMP
        );
        
        $user=$this->findUserByOpenid($openid, $pid);
        if(!empty($user)){
        	
        	$res = array(
        			"code" => 500,
        			'msg' => '用户已存在！'
        	);
        	
        }else{
        	pdo_insert($this->table_redpacket_user, $data);
        	$res = array(
        			"code" => 200,
        			'msg' => '报名成功'
        	);
        	
        	
        }
        
        
        
      
        
      
        echo json_encode($res);
    }

    /**
     * 排行榜
     */
    public function doMobileSort()
    {
        global $_W, $_GPC;
        
        $this->checkmobile();
       
    	
        
        $pid = $_GPC['pid'];
        $uid = $_GPC['uid'];
        
        $packet = $this->findPacket($pid);
        $user = $this->findUserId($uid);
      
        if (empty($packet)) {
            message("活动删除或不存在");
        }
        
        $sortcount=$packet['sortcount'];
        $list = pdo_fetchall("SELECT @rownum:=@rownum+1 AS rowno ,u.*  FROM (SELECT @rownum:=0 ) r, " . tablename($this->table_redpacket_user) . " u WHERE u.pid =:pid  ORDER BY income DESC limit 0,".$sortcount, array(
            ":pid" => $pid
        ));
        
        $total = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename($this->table_redpacket_user) . " WHERE income>:inco and pid=:pid", array(
            ":inco" => $user['income'],":pid"=>$pid
        ));
        
     	 $userSort=$total+1;
        
        include $this->template("sort");
    }

    public function doWebdownload() {
        require_once 'download.php';
    }
    
    /**
     * 授权设置
     */
    public function doWebSetting()
    {
        global $_W, $_GPC;
        
        $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_redpacket_setting) . " where weid=:weid", array(
            ":weid" => $_W['uniacid']
        ));
        
        if (checksubmit('submit')) {
            
            $data = array(
                'appid' => $_GPC['appid'],
                'secret' => $_GPC['secret'],
                'weid' => $_W['uniacid']
            );
            if (! empty($setting)) {
                pdo_update($this->table_redpacket_setting, $data, array(
                    'id' => $setting['id']
                ));
            } else {
                pdo_insert($this->table_redpacket_setting, $data);
            }
            message('更新授权接口成功！', $this->createWebUrl('setting', array(

                'op' => 'display'
            )), 'success');
        }
        
        include $this->template('autho_setting');
    }

    public function doWebQuery()
    {
        global $_W, $_GPC;
        $kwd = $_GPC['keyword'];
        $sql = 'SELECT * FROM ' . tablename($this->table_redpacket) . ' WHERE `weid`=:weid AND `name` LIKE :name';
        $params = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':name'] = "%{$kwd}%";
        $ds = pdo_fetchall($sql, $params);
        foreach ($ds as &$row) {
            $r = array();
            $r['name'] = $row['name'];
            
            $r['id'] = $row['id'];
            $row['entry'] = $r;
        }
        include $this->template('query');
    }

    /**
     * 认证第二部获取 openid和accessToken
     */
    public function doMobileauth2()
    {
        global $_W, $_GPC;
        $au = $_GPC['au'];
        $code = $_GPC['code'];
        
        $tokenInfo = $this->getAuthTokenInfo($code, $_W['uniacid']);
        
        $pid = $_GPC['pid'];
        
        $openid = $tokenInfo['openid'];
        $accessToken = $tokenInfo['access_token'];
        
		
		
        if ($au == "msg") { // 图文点击进去的
		
		
		$appUrl= $this->createMobileUrl('index', array(
                'pid' => $pid,
                "openid" => $openid,
                "access_token" => $accessToken
            ),true);
			
			
			
		$appUrl=substr($appUrl,2);
		
            $url = $_W['siteroot'] . "app/".$appUrl;
			
			
			
			
        } else 
            if ($au == "firend") { // 好友进入认证
                
				
			$appUrl=$this->createMobileUrl('firend', array(
                    'pid' => $pid,
                    "openid" => $openid,
                    "uid" => $_GPC['uid'],
                    "access_token" => $accessToken
                ),true);
			
				$appUrl=substr($appUrl,2);
				
                $url = $_W['siteroot'] ."app/".$appUrl;
            }
        
        header("location: $url");
    }

    /**
     * 认证
     */
    public function doMobileauth()
    {
        global $_W, $_GPC;
        
        $au = $_GPC['au']; // 标示类型
        $autype = $_GPC['at'];
        $pid = $_GPC['pid'];
        $uid = $_GPC['uid'];
		
		$appUrl= $this->createMobileUrl('auth2', array(
            'au' => $au,
            'pid' => $pid,
            'uid' => $uid
        ),true);
		$appUrl=substr($appUrl,2);
		
		
		
        $redirect_uri = $_W['siteroot'] ."app/".$appUrl ;
		
	
        $appid = $_W['account']['key'];
        $secret = $_W['account']['secret'];
        
        $setting = $this->findAuthSetting($_W['uniacid']);
		
		
        
        if (! empty($setting) && ! empty($setting['appid']) && ! empty($setting['secret'])) { // 判断是否是借用设置
            $appid = $setting['appid'];
            $secret = $setting['secret'];
        }
        
        $oauth2_code = "";
        
        if ($autype == 0) { // base
            $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appid . "&redirect_uri=" . urlencode($redirect_uri) . "&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
        } else 
            if ($autype == 1) { // info
                $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appid . "&redirect_uri=" . urlencode($redirect_uri) . "&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
            }
        
	
		
        header("location: $oauth2_code");
    }

    /**
     * 判断是否借用了
     *
     * @return boolean
     */
    public function isJieyong()
    {
        global $_W;
        $setting = $this->findAuthSetting($_W['uniacid']);
        
        if (! empty($setting) && ! empty($setting['appid']) && ! empty($setting['secret'])) { // 判断是否是借用设置
            
            return true;
        }
        
        return false;
    }

    public function getAccessToken()
    {
        global $_W;
        $appid = $_W['account']['key'];
        $secret = $_W['account']['secret'];
        $tokenUrl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret;
        $accessToken = $this->findDBAcctessToken();
        
        if (! empty($accessToken)) {
            
            $expires_in = $accessToken['expires_in'];
            
            if (TIMESTAMP - $accessToken['createtime'] >= $expires_in) { // 过期
                
                $content = ihttp_get($tokenUrl);
                $token = @json_decode($content['content'], true);
                $data = array(
                    'weid' => $_W['uniacid'],
                    'access_token' => $token['access_token'],
                    'expires_in' => $token['expires_in'],
                    'createtime' => TIMESTAMP
                );
                
                pdo_update($this->table_redpacket_token, $data, array(
                    'id' => $accessToken['id']
                )); // 更新token
                
                return $token['access_token'];
            } else {
                
                return $accessToken['access_token'];
            }
        } else {
			load()->func('communication');
            
            $content = ihttp_get($tokenUrl);
            $token = @json_decode($content['content'], true);
            $data = array(
                'weid' => $_W['uniacid'],
                'access_token' => $token['access_token'],
                'expires_in' => $token['expires_in'],
                'createtime' => TIMESTAMP
            );
            
            pdo_insert($this->table_redpacket_token, $data);
            
            return $token['access_token'];
        }
    }

    public function findDBAcctessToken()
    {
        global $_W;
        $accessToken = pdo_fetch("SELECT * FROM " . tablename($this->table_redpacket_token) . " where weid=:weid", array(
            ":weid" => $_W['uniacid']
        ));
        
        return $accessToken;
    }

    /**
     * 获取用户信息
     *
     * @param unknown $openid            
     * @param unknown $accessToken            
     * @return unknown
     */
    public function getUserInfo($openid, $accessToken)
    {
        // $tokenUrl = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $accessToken . "&openid=" . $openid . "&lang=zh_CN";
		load()->func('communication');
        $tokenUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $accessToken . "&openid=" . $openid . "&lang=zh_CN";
        $content = ihttp_get($tokenUrl);
        
        $userInfo = @json_decode($content['content'], true);
        
        return $userInfo;
    }

    /**
     * 查找setting
     *
     * @param unknown $weid            
     * @return Ambigous <mixed, boolean>
     */
    private function findAuthSetting($weid)
    {
        $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_redpacket_setting) . " where weid=:weid", array(
            ":weid" => $weid
        ));
        
        return $setting;
    }

    /**
     * 获取token信息
     *
     * @param unknown $code            
     * @return unknown
     */
    public function getAuthTokenInfo($code, $weid)
    {
        global $_GPC, $_W;
        $appid = $_W['account']['key'];
        $secret = $_W['account']['secret'];
        $setting = $this->findAuthSetting($weid);
        
        if (! empty($setting) && ! empty($setting['appid']) && ! empty($setting['secret'])) { // 判断是否是借用设置
            $appid = $setting['appid'];
            $secret = $setting['secret'];
        }
        load()->func('communication');
        $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $secret . "&code=" . $code . "&grant_type=authorization_code";
        $content = ihttp_get($oauth2_code);
        $token = @json_decode($content['content'], true);
        
        if (empty($token) || ! is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
            
            echo '<h1>获取微信公众号授权' . $code . '失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
            exit();
        }
        
        return $token;
    }

    /**
     * 根据 openid pid查找用户
     *
     * @param unknown $openid            
     * @param unknown $pid            
     */
    public function findUserByOpenid($openid, $pid)
    {
        $user = pdo_fetch("SELECT * FROM " . tablename($this->table_redpacket_user) . " WHERE pid = :pid and openid=:openid", array(
            ':pid' => $pid,
            ':openid' => $openid
        ));
        
        return $user;
    }

    public function findUserId($uid)
    {
        $user = pdo_fetch("SELECT * FROM " . tablename($this->table_redpacket_user) . " WHERE id = :uid", array(
            ':uid' => $uid
        ));
        
        return $user;
    }

    /**
     * 查询红包活动
     *
     * @param unknown $pid            
     * @return Ambigous <mixed, boolean>
     */
    public function findPacket($pid)
    {
        $packet = pdo_fetch("SELECT * FROM " . tablename($this->table_redpacket) . " WHERE id = :pid", array(
            ':pid' => $pid
        ));
        
        return $packet;
    }
}