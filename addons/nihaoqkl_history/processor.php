<?php 

defined('IN_IA') or exit('Access Denied'); class Nihaoqkl_historyModuleProcessor extends WeModuleProcessor { public function respond() { global $_W; $content = $this->message['content']; $info = pdo_fetch("select * from ".tablename('keyword'). " where keyword = :keyword", array(':uniacid'=>$_W['uniacid'], ':keyword'=>$content)); if($info) { $url = $_W['siteroot'] . 'app' . ltrim(murl('entry',array('do'=>'index','m'=>'nihaoqkl_history','cid'=>$info['id'])),'.');; } else { $url = $_W['siteroot'] . 'app' . ltrim(murl('entry',array('do'=>'index','m'=>'nihaoqkl_history')),'.');; } return $this->respText($url); } }

?>