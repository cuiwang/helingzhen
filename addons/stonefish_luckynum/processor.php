<?php
/**
 * 幸运数字活动模块处理程序
 *
 * @author 微赞
 * @url http://www.00393.com/
 */
defined('IN_IA') or exit('Access Denied');

include 'common.inc.php';

class stonefish_luckynumModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
        $rid = $this->rule;
        $from_user = $this->message['from'];
		$reply = pdo_fetch("SELECT * FROM ".tablename('stonefish_luckynum')." WHERE rid = :rid", array(':rid' => $rid));
		//查询是否中奖但没有提供信息
		$zhongjiang = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('stonefish_luckynum_fans')." WHERE rid = :rid and uniacid=:uniacid and from_user=:from_user and zhongjiang=1", array(':rid' => $rid,':uniacid' => $_W['uniacid'],':from_user' => $from_user));
		//查询是否中奖但没有提供信息
		//赞助商
		if(!empty($reply['sponsors1'])){
			if(!empty($reply['sponsors1link'])){
               $ad_info = "<a target=\"_blank\" href=\"".$reply['sponsors1link']."\">".$reply['sponsors1']."</a>";
			}else{
			   $ad_info = $reply['sponsors1'];
			}
		}
		if(!empty($reply['sponsors2'])){
			if(!empty($reply['sponsors2link'])){
               $ad_info .= "\n<a target=\"_blank\" href=\"".$reply['sponsors2link']."\">".$reply['sponsors2']."</a>";
			}else{
			   $ad_info .= "\n".$reply['sponsors2'];
			}
		}
		if(!empty($reply['sponsors3'])){
			if(!empty($reply['sponsors3link'])){
               $ad_info .= "\n<a target=\"_blank\" href=\"".$reply['sponsors3link']."\">".$reply['sponsors3']."</a>";
			}else{
			   $ad_info .= "\n".$reply['sponsors3'];
			}
		}
		if(!empty($reply['sponsors4'])){
			if(!empty($reply['sponsors4link'])){
               $ad_info .= "\n<a target=\"_blank\" href=\"".$reply['sponsors4link']."\">".$reply['sponsors4']."</a>";
			}else{
			   $ad_info .= "\n".$reply['sponsors4'];
			}
		}
		if(!empty($reply['sponsors5'])){
			if(!empty($reply['sponsors5link'])){
               $ad_info .= "\n<a target=\"_blank\" href=\"".$reply['sponsors5link']."\">".$reply['sponsors5']."</a>";
			}else{
			   $ad_info .= "\n".$reply['sponsors5'];
			}
		}
		if(!empty($ad_info)){
			$ad_info = "\n———————————\n".$ad_info;
		}
		//赞助商
		//查询活动状态
		if ($reply == false) {
            return $this->respText("活动已取消...".$ad_info);
        }
        if ($reply['isshow'] == 0) {
            return $this->respText($reply['show_instruction'].$ad_info);
        }
        if ($reply['starttime'] > time()) {
            return $this->respText($reply['time_instruction'].$ad_info);
        }
		if ($reply['endtime'] < time()) {
            $result = $reply['end_instruction'];
			if($zhongjiang){
				$url = $_W['siteroot'].'app/'.$this->createMobileUrl('infosubmit', array('_x' => superman_authcode("$rid|$from_user", 'ENCODE')));
			    $result .= $reply['award_instruction']."<a href='$url'>".$reply['ticketinfo']."</a>";
			}
			return $this->respText($result.$ad_info);
        }
		//查询活动状态		
		$limittype = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('stonefish_luckynum_fans')." WHERE rid=:rid and uniacid=:uniacid and from_user=:from_user", array(':rid' => $rid,':uniacid' => $_W['uniacid'],':from_user' => $from_user));
		//第一次参与显示分享给好友链接
		if($limittype==0){
			$share = "\n———————————\n<a target=\"_blank\" href=\"".$_W['siteroot'].'app/'.$this->createMobileUrl('share', array('rid' => $rid))."\">快拉小伙伴一起幸运数字吧</a>";
		}
		//第一次参与显示分享给好友链接
		//查询参与次数限制
		if($reply['limittype']){
			if($limittype){
				$result = $reply['limit_instruction'];
				if($zhongjiang){
				    $url = $_W['siteroot'].'app/'.$this->createMobileUrl('infosubmit', array('_x' => superman_authcode("$rid|$from_user", 'ENCODE')));
			        $result .= $reply['award_instruction']."<a href='$url'>".$reply['ticketinfo']."</a>";
			    }
				return $this->respText($result.$share.$ad_info);
			}
		}
		//查询参与次数限制		
		//查询中奖次数
		$awardnum = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('stonefish_luckynum_fans')." WHERE rid = :rid and uniacid=:uniacid and from_user=:from_user and zhongjiang>=1", array(':rid' => $rid,':uniacid' => $_W['uniacid'],':from_user' => $from_user));		
		if($reply['limittype']==0 && $awardnum>=$reply['awardnum']){
			$result = $reply['awardnum_instruction'];
			if($zhongjiang){
				$url = $_W['siteroot'].'app/'.$this->createMobileUrl('infosubmit', array('_x' => superman_authcode("$rid|$from_user", 'ENCODE'),));
			    $result .= $reply['award_instruction']."<a href='$url'>".$reply['ticketinfo']."</a>";
			}
			return $this->respText($result.$share.$ad_info);
		}
		//查询中奖次数		
		//查询最后一个数字记录
		$lastnumber = pdo_fetchcolumn("SELECT number FROM ".tablename('stonefish_luckynum_fans')." WHERE rid = :rid and uniacid=:uniacid ORDER BY `number` desc", array(':rid' => $rid,':uniacid' => $_W['uniacid']));
		$lastnumber = $lastnumber+1;
		if($lastnumber==1){
			$lastnumber = $reply['luckynumstart'];
		}
		//查询最后一个数字记录		
		//判断是否包含过滤数字
		$luckynumfilter = explode(',', $reply['luckynumfilter']);
		foreach ($luckynumfilter as $luckynumfilteritem) {
			$lastnumber = str_replace($luckynumfilteritem,$luckynumfilteritem+1,$lastnumber);
	    }
		//判断是否包含过滤数字		
		$new_data = array(
			'rid' => $rid,
			'uniacid' => $_W['uniacid'],
			'number' => $lastnumber,
			'dateline' => $_W['timestamp'],
			'from_user' => $from_user,
			'ip' => $_W['clientip'],
		);
		pdo_insert("stonefish_luckynum_fans", $new_data, false);
		$new_id = pdo_insertid();
		if ($new_id <= 0) {
			return $this->respText('系统异常，请稍后重试！');
		}
        $awards = pdo_fetchall("SELECT * FROM ".tablename('stonefish_luckynum_award')." WHERE rid=$rid ORDER BY `id` ASC");
        if ($awards) {
			foreach ($awards as $item) {
                $numbers = explode(',', $item['numbers']);
				if (in_array($lastnumber, $numbers)) {
					$up_data = array(                        
                        'award_id' => $item['id'],
						'zhongjiang' => 1
                    );
					pdo_update('stonefish_luckynum_fans', $up_data, array('id' => $new_id));
                    $result = str_replace('{LUCKYNUM}', $lastnumber, $reply['awardprompt']);
					$result = str_replace('{AWARD}', $item['title'], $result);
					$result = str_replace('{DESCRIPTION}', $item['description'], $result);
					$url = $_W['siteroot'].'app/'.$this->createMobileUrl('infosubmit', array('_x' => superman_authcode("$rid|$from_user", 'ENCODE')));					
					$result .= "<a href='$url'>".$reply['ticketinfo']."</a>";
					return $this->respText($result.$share.$ad_info);
					if($reply['limittype'] || ($reply['limittype']==0 && $awardnum>=$reply['awardnum'])){
					    break;
				    }
				}
			}
		}
		$result = str_replace('{LUCKYNUM}', $lastnumber, $reply['currentprompt']);
		if($zhongjiang){
			$url = $_W['siteroot'].'app/'.$this->createMobileUrl('infosubmit', array('_x' => superman_authcode("$rid|$from_user", 'ENCODE')));
			$result .= $reply['award_instruction']."<a href='$url'>".$reply['ticketinfo']."</a>";
		}        
		return $this->respText($result.$share.$ad_info);
	}
}
