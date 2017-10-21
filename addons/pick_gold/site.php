<?php
/**
 * 欢乐接金币模块微站定义
 *
 * @author 茶盒互动
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Pick_goldModuleSite extends WeModuleSite {

	public function doMobileGstart() {
        global $_W;

        $uid=$_W['member']['uid'];

        $follow = pdo_fetch('SELECT * FROM ' . tablename('mc_mapping_fans') . ' WHERE `uid` = :uid', array(':uid' => $uid));

        $setting = pdo_fetch('SELECT * FROM ' . tablename('pick_gold_setting') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid']));
        $setting['prop_value']=$setting['prop_value']?$setting['prop_value']:'50,100,150,-50';
        $setting['game_time']=$setting['game_time']?$setting['game_time']:30;
        $setting['share_title']=$setting['share_title']?$setting['share_title']:'欢乐接金币';
        $setting['share_desc']=$setting['share_desc']?$setting['share_desc']:'我接了{1}吨金币，谁比我厉害？';
        $prop_value=explode(',',$setting['prop_value']);

        $wxConfig=$this->getJssdkConfig();

        if($follow['follow']){
            
            $this->setFansInfo();
            include $this->template('index');
        }else{
            $rule = pdo_fetch('SELECT b.content FROM ' . tablename('cover_reply') . ' a LEFT JOIN ' . tablename('rule_keyword') . ' b ON a.rid=b.rid WHERE a.uniacid = :uniacid AND a.module = :module AND b.module = :bmodule', array(':uniacid' => $_W['uniacid'],':module' => 'pick_gold',':bmodule' => 'cover'));

            include $this->template('follow');
        }
	}

    public function doMobileMobile() {
        global $_W,$_GPC;

        $user = pdo_fetch('SELECT * FROM ' . tablename('mc_members') . ' WHERE `uid` = :uid', array(':uid' => $_W['member']['uid']));


        if(isset($_GPC['mobile']) && isset($_GPC['realname'])){
            $data=array(
                'mobile'=>$_GPC['mobile'],
                'realname'=>$_GPC['realname'],
            );
            pdo_update('mc_members', $data, array('uid' => $_W['member']['uid']));
            header("location: ./index.php?i=".$_W['uniacid']."&c=entry&do=gstart&m=pick_gold");
            exit;
        }
        include $this->template('mobile');
    }

    public function doMobileShare() {
        global $_W,$_GPC;
        $uid=$_GPC['uid_'.$_W['uniacid']];

        $user = pdo_fetch( 'SELECT a.score,b.nickname FROM ' . tablename('pick_gold_rank') . ' AS a LEFT JOIN ' . tablename('mc_members') . ' AS b ON a.uid=b.uid WHERE a.uid = :uid AND a.uniacid=:uniacid', array(':uid' => $uid,':uniacid' => $_W['uniacid']) );

        $setting = pdo_fetch('SELECT * FROM ' . tablename('pick_gold_setting') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid']));
        $setting['share_title']=$setting['share_title']?$setting['share_title']:'欢乐接金币';
        $setting['share_desc']=$setting['share_desc']?$setting['share_desc']:'我接了{1}吨金币，谁比我厉害？';


        $wxConfig=$this->getJssdkConfig();

        include $this->template('share');
    }
        
	public function doWebSetting() {
		global $_W,$_GPC;

        $setting = pdo_fetch('SELECT * FROM ' . tablename('pick_gold_setting') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid']));
        
        if(isset($_GPC['item']) && $_GPC['item'] == 'ajax' && $_GPC['key'] == 'setting'){
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'rank_show'=>$_GPC['rank_show'],
                'thumb'=>$_GPC['thumb'],
                'share_img'=>$_GPC['share_img'],
                'share_title'=>$_GPC['share_title'],
                'share_desc'=>$_GPC['share_desc'],
                'help'=>$_GPC['help'],
                'game_time'=>$_GPC['game_time'],
                'starttime'=>strtotime($_GPC['starttime']),
                'endtime'=>strtotime($_GPC['endtime']),
                'prop_value'=>$_GPC['prop_value'],
                'award'=>$_GPC['award'],
                'game_title'=>$_GPC['game_title'],
            );
            if($setting){
                pdo_update('pick_gold_setting', $data, array('id' => $setting['id']));
                $id=$setting['id'];
            }else{
                pdo_insert('pick_gold_setting', $data);
                $id=pdo_insertid();
            }
            echo $id;
			exit;
		}
        if(!$setting['starttime']){
            $setting['starttime']=TIMESTAMP;
            $setting['endtime']=TIMESTAMP+3600*24;
        }
        $setting['prop_value']=$setting['prop_value']?$setting['prop_value']:'50,100,150,-50';

        
        load()->func('tpl');
		include $this->template('setting');
        
	}
    public function doWebClearank() {
        global $_W;
        pdo_delete('pick_gold_rank', array('uniacid' => $_W['uniacid']));
        echo 1;
    }

        
	public function doWebRank() {
        global $_GPC,$_W;
        
		
        $setting = pdo_fetch('SELECT * FROM ' . tablename('pick_gold_setting') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid']));

        $setting['rank_show']=$setting['rank_show']?$setting['rank_show']:100;

        $sql = 'SELECT b.realname,b.mobile,b.nickname,b.avatar,a.score,a.dateline,a.uid FROM ' . tablename('pick_gold_rank') . ' AS a LEFT JOIN ' . tablename('mc_members') . ' AS b ON a.uid=b.uid WHERE a.uniacid = :uniacid ORDER BY a.score DESC LIMIT '.$setting['rank_show'];
        $params = array(':uniacid' => $_W['uniacid']);
        $list = pdo_fetchall($sql, $params);
        

        include $this->template('rank');
	}

    public function doMobileRank() {
        global $_W;
        $setting = pdo_fetch('SELECT * FROM ' . tablename('pick_gold_setting') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid']));

        $setting['rank_show']=$setting['rank_show']?$setting['rank_show']:100;

		$sql = 'SELECT b.nickname,b.avatar,a.score,a.dateline FROM ' . tablename('pick_gold_rank') . ' AS a LEFT JOIN ' . tablename('mc_members') . ' AS b ON a.uid=b.uid WHERE a.uniacid = :uniacid ORDER BY a.score DESC LIMIT '.$setting['rank_show'];
        $params = array(':uniacid' => $_W['uniacid']);
        $list = pdo_fetchall($sql, $params);

        include $this->template('rank');
	}
    
    public function doMobileSecret(){
        $json=array();
        $json['type']='secret';

        $secret = $this->tbRandom(6);
        $_SESSION['secret']=$secret;
        $json['secret']=$secret;
        echo json_encode($json);
    }

    
    public function doMobileSubmitscore(){
        global $_W;
        $json=array();
        $json['type']='score';
        $json['msg']='';
        $uid=$_W['member']['uid'];
        $follow = pdo_fetch('SELECT * FROM ' . tablename('mc_mapping_fans') . ' WHERE `uid` = :uid', array(':uid' => $uid));
        if(!$follow['follow']){
            $json['msg']='请先关注公众号';
            exit(json_encode($json));
        }
        $data=$this->fdecode($_GET['data']);
        $json['uid']=$uid;
        $json['data']=$data;
        $skey=$_SESSION['secret'];
        $arr=explode(':',$data);
        $score=intval($arr[0]);
        if(md5($skey)!=$arr[1]){
            $json['msg']='分数提交错误';
            exit(json_encode($json));
        }

        $setting = pdo_fetch('SELECT * FROM ' . tablename('pick_gold_setting') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid']));
        if(TIMESTAMP<$setting['starttime']||TIMESTAMP>$setting['endtime']){
            $json['msg']=date('Y-m-d',$setting['starttime']).'到'.date('Y-m-d',$setting['endtime']).'分数有效';
            exit(json_encode($json));
        }

        $sql = 'SELECT * FROM ' . tablename('pick_gold_rank') . ' WHERE `uid` = :uid AND `uniacid`=:uniacid';
        $params = array(':uid' => $uid,':uniacid' => $_W['uniacid']);
        $user = pdo_fetch($sql, $params);
        
        if($user){
            if($score>$user['score']){
                $data=array(
                    'score'=>$score,
                    'dateline'=>TIMESTAMP,
                );
                pdo_update('pick_gold_rank', $data, array('id' => $user['id']));
            }
        }else{
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'uid'=>$uid,
                'score'=>$score,
                'dateline'=>TIMESTAMP,
            );
            pdo_insert('pick_gold_rank', $data); 
        }
        exit(json_encode($json));
        
    }
    
    private function tbRandom($length, $chars = '123456789') {
        $hash = '';
        $max = strlen($chars) - 1;
        for($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
        return $hash;
    }
    
    private function fdecode($s){
        $a = str_split($s,2);
        $s = '%' . implode('%',$a);
        return urldecode($s);
    }
    
    private function friendlyDate($sTime) {
        //sTime=源时间，cTime=当前时间，dTime=时间差
        $cTime        =    time();
        $dTime        =    $cTime - $sTime;
        $dDay        =    intval(date("z",$cTime)) - intval(date("z",$sTime));
        //$dDay        =    intval($dTime/3600/24);
        $dYear        =    intval(date("Y",$cTime)) - intval(date("Y",$sTime));
        //normal：n秒前，n分钟前，n小时前，日期
        if( $dTime < 60 ){
            return $dTime."秒前";
        }elseif( $dTime < 3600 ){
            return intval($dTime/60)."分钟前";
        //今天的数据.年份相同.日期相同.
        }elseif( $dYear==0 && $dDay == 0  ){
            //return intval($dTime/3600)."小时前";
            return '今天'.date('H:i',$sTime);
        }elseif($dYear==0){
            return date("m月d日 H:i",$sTime);
        }else{
            return date("Y-m-d H:i",$sTime);
        }
    }
    
    private function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
    
    private function getJssdkConfig(){
        global $_W;
        $jsapiTicket = $_W['account']['jsapi_ticket']['ticket'];
        $nonceStr = $this->createNonceStr();
		$timestamp = TIMESTAMP;
		$url = $_W['siteurl'];
		$string1 = "jsapi_ticket={$jsapiTicket}&noncestr={$nonceStr}&timestamp={$timestamp}&url={$url}";
		$signature = sha1($string1);
		$config = array(
			"appId"		=> $_W['account']['key'],
			"nonceStr"	=> $nonceStr,
			"timestamp" => "$timestamp",
			"signature" => $signature,
		);
        return $config;
    }
    
    private function setFansInfo(){
        global $_W;

        $user = pdo_fetch('SELECT * FROM ' . tablename('mc_members') . ' WHERE `uid` = :uid', array(':uid' => $_W['member']['uid']));

        if(empty($user['nickname'])&&empty($user['avatar'])){
            $param=array();
            $param ['access_token']=$_W['account']['access_token']['token'];
            $param ['openid'] = $_W['openid'];
            $param ['lang'] = 'zh_CN';
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?' . http_build_query ( $param );
            $content = file_get_contents ( $url );
            $content = json_decode ( $content, true );
            if($content['nickname']){
                $data=array(
                    'nickname'=>stripslashes($content['nickname']),
                    'gender'=>$content['sex'],
                    'residecity'=>$content['city'].'市',
                    'resideprovince'=>$content['province'].'省',
                    'nationality'=>$content['country'],
                    'avatar'=>rtrim($content['headimgurl'], '0') . 132
                );
                pdo_update('mc_members', $data, array('uid' => $user['uid']));
            }
        }
    }

}