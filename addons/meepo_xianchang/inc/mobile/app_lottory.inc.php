<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
include MODULE_ROOT.'/inc/mobile/__init.php';
if(!in_array('lottory',$xianchang['controls'])){
	message('本次活动未开启抽奖活动！');
}
if(empty($user)){
	message('错误你的信息不存在或是已经被删除！');
}
if($user['status']==2 || $user['isblacklist']==2 || $user['can_lottory']==2){
	message('对不起你不具备参与抽奖资格');
}
include $this->template('app_lottory');