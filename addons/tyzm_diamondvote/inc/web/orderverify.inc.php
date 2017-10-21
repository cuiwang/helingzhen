<?php


/**
 * 钻石投票模块-订单校验
 *
 * 易/福/源/码/网 www.efwww.com
 * 易/福/源/码/网 www.efwww.com
 */

defined('IN_IA') or exit('Access Denied');
global $_GPC, $_W;
$this->authorization();
$uniacid = intval($_W['uniacid']);
$rid=intval($_GPC['rid']);
$reply=pdo_fetch("SELECT config FROM " . tablename($this->tablereply) . " WHERE rid = :rid ", array(':rid' => $rid));
$reply=array_merge ($reply,unserialize($reply['config']));unset($reply['config']);
$list = pdo_fetchall("SELECT tid,openid,ptid,fee,giftvote,ispay FROM ".tablename($this->tablegift)."  WHERE uniacid=:uniacid AND rid = :rid AND ptid!='' AND ispay=0 ", array(':uniacid' => $uniacid,'rid'=>$rid));


$verifynum=0;

foreach ($list as $key => $value){
	
	$paylog = pdo_get('core_paylog', array('tid' => $value['ptid'],'status'=>1), array('fee'));
	
	if(!empty($paylog) && $value['fee']==$paylog['fee']){
		$reupvote=pdo_update($this->tablegift, array('ispay' =>'1','isdeal' =>'1'), array('ptid' => $value['ptid']));
		//处理信息
		//格式化小数点
        $value['fee']=sprintf("%.2f", $value['fee']);
		$setvotesql = 'update ' . tablename($this->tablevoteuser) . ' set votenum=votenum+'.$value['giftvote'].',giftcount=giftcount+'.$value['fee'].'  where id = '.$value['tid'];
		$resetvote=pdo_query($setvotesql);
		if($resetvote){
			$verifynum++;
			
			//奖励用户
			if(!empty($reply['giftgive_num'])){
				m('present')->upcredit($value['openid'],$reply['giftgive_type'],$reply['giftgive_num']*$paylog['fee'],'tyzm_diamondvote');
			}
			//奖励end
			//奖励选手
			if(!empty($reply['awardgive_num'])){
				$votedata=pdo_fetch("SELECT openid FROM " . tablename($this->tablevoteuser) . " WHERE id = :id ", array(':id' => $value['tid']));
				m('present')->upcredit($votedata['openid'],$reply['awardgive_type'],$reply['awardgive_num']*$paylog['fee'],'tyzm_diamondvote');
			}
			//奖励end
			
			
		}
		
		///
	}	
}
message('完成'.$verifynum."个校验");
