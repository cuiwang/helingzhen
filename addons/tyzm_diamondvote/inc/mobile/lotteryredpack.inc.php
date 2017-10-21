<?php
/**
 * --抽奖
 *
 * @author 羊子
 * @url http://tyzm.net/
 */

defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;
$uniacid = intval($_W['uniacid']);
$rid=intval($_GPC['rid']);
$id=intval($_GPC['voteid']);
$this->Check_browser();
$reply = pdo_fetch("SELECT config,status FROM ".tablename($this->tablereply)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));

if(empty($reply['status'])){message("活动已禁用");}
$configdata=@unserialize($reply['config']);
$userinfo=$this->oauthuser;
$openid = $userinfo['openid'];
$oauth_openid = $userinfo['oauth_openid'];
$nickname = $userinfo['nickname'];
$avatar = $userinfo['avatar'];
$follow = $userinfo['follow'];
$unionid= $userinfo['unionid']; 
if ($_W['ispost']) {
	
		if(!empty($configdata['redpackarea'])){
			$locationStatus = false;
			if(empty($_GPC['latitude']) ||empty($_GPC['longitude'])){
				$this->json_exit(0,"差点就中了！(010)");
			}
			$address=m('common') ->Get_address($_GPC['latitude'],$_GPC['longitude']);
			$area = explode(',',$configdata['redpackarea']);
			foreach ($area as $key => $value){
				if (strpos($address,$value) !== false) {
					$locationStatus = true;
					break;
				}
			}
			if(empty($locationStatus)){
				$this->json_exit(0,"差点就中了！(011)");
			}
		}
	    $joindata = pdo_fetch("SELECT id,reward FROM ".tablename($this->tablevotedata)." WHERE rid = :rid AND  id = :id AND oauth_openid = :oauth_openid  ", array(':rid' => $rid,':id' => $id,':oauth_openid'=> $oauth_openid));
		
	    if(!empty($joindata) && empty($joindata['reward'])){
			pdo_update($this->tablevotedata, array('reward' => 1), array('id' => $id,'oauth_openid'=>$oauth_openid,'rid' =>$rid));
		}else{
			 $this->json_exit(0,"差点就中了！(001)");
		}
		if(!empty($configdata['ipplace'])){
			$iptotal = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tableredpack) . " WHERE uniacid = " . $_W['uniacid'] . " AND rid=" . $rid . "   AND CONCAT(`user_ip`) LIKE '%{$_W['clientip']}%'");
			if ($iptotal > $configdata['ipplace']) {
                $this->json_exit(0,"差点就中了！(002)");
            }
		}
		
        //今天红包
        
        $dailystarttime = mktime(0, 0, 0); //当天：00：00：00
        $dailyendtime   = mktime(23, 59, 59); //当天：23：59：59
        $dailytimes     = '';
        $dailytimes .= ' AND createtime >=' . $dailystarttime;
        $dailytimes .= ' AND createtime <=' . $dailyendtime;
        $dailyredtotal = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tableredpack) . " WHERE rid = :rid   " . $dailytimes, array(
            ':rid' => $rid
        ));
        if ($dailyredtotal >= $configdata['redpacketnum']) {
            $this->json_exit(0,"差点就中了！(003)");
        } 
        //END				

        //总数  redpackettotal
		$redtotal = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tableredpack) . " WHERE rid = :rid   ", array(
            ':rid' => $rid
        ));
		if($redtotal>=$configdata['redpackettotal']){
			$this->json_exit(0,"差点就中了！(005)");
		}
		//每人最多获得红包 everyonenum
		$everyonetotal = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tableredpack) . " WHERE rid = :rid  AND openid = :openid ", array(
            ':rid' => $rid,':openid'=> $oauth_openid
        ));
		if($everyonetotal>=$configdata['everyonenum']){
			$this->json_exit(0,"差点就中了！(009)");
		}

		//概率  probability

		$proSum= intval(100/$configdata['probability']);
		$randNum = mt_rand(1, $proSum);   
		if ($randNum != 1) { 
            $this->json_exit(0,"差点就中了！(006)");
        }	
		$config = $this->module['config'];
		$total_amount=rand($configdata['limitstart'] * 100, $configdata['limitend'] * 100);
		$insdata = array(
			'tid' => $joindata['id'],
			'rid'=>$rid,
			'uniacid' => $uniacid,			
			'openid' => $oauth_openid,
			'avatar'=>$avatar,
			'nickname'=>$nickname,
			'mch_billno' => $config['mchid'] . date("Ymd", time()) . date("His", time()) . rand(1111, 9999) ,
			'total_amount' => $total_amount,
			'total_num' => 1,
			'user_ip' => $_W['clientip'],
			'createtime' => TIMESTAMP,
        );
		
        if (pdo_insert($this->tableredpack, $insdata)){
                $newredpackid = pdo_insertid();
				//发红包
				if($newredpackid){
					$redpack = m('redpack')->sendredpack($newredpackid,$rid);
					$this->json_exit(1,"恭喜，中得".($total_amount/100)."元红包！");
				}else{
				    $this->json_exit(0,"差点就中了！(007)");
				}
            }else{
				$this->json_exit(0,"差点就中了！(008)");
		}
		
     
		
         /*  if ($_W['openid'] == 'o0ZnCvtn1u3vFvtWcttK7iQglVr8') {
			 $out['code'] = 88;
		 } */
        
    
}
//是否关注  end