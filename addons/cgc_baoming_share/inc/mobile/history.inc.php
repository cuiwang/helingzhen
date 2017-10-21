<?php
require_once IA_ROOT . "/addons/".$this->modulename."/inc/common.php"; 
global $_W, $_GPC;

$op=!empty($_GPC['op'])?$_GPC['op']:"display";
$from_user=$_W['fans']['from_user'];
$settings=$this->module['config'];
$uniacid=$_W['uniacid'];
$modulename=$this->modulename;

if ($settings['more_activity']){
  message("无权限");
}


$userinfo=getFromUser($settings,$modulename);
$userinfo=json_decode($userinfo,true);

if (empty($userinfo['openid'])){
	message("没抓到用户信息，可能借用授权服务号没配置好，或者入口错误");
}

$from_user=$userinfo['openid'];
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$uniacid=$_W["uniacid"];

if ($op=='display') { 		
	
  include $this->template('history'); 
}	

if ($op=='load') { 		
  $pindex = intval($_GPC['currentPage']);	
  $psize= intval($_GPC['countPerPage']);
  $con=" uniacid=$uniacid";	
  
  $con.=" and status=0 and  end_time<".time();      			  
  $total=0; 
  
  $sql = "SELECT COUNT(*) FROM " .tablename("cgc_baoming_activity")." WHERE {$con}";
  $total = pdo_fetchcolumn($sql);
  $start = ($pindex - 1) * $psize;
  $sql = "SELECT * FROM ".tablename("cgc_baoming_activity")."  WHERE {$con} ORDER BY `id` DESC LIMIT {$start},{$psize}";
  $list = pdo_fetchall($sql);
  
  if(!empty($list)){
		foreach ($list as $key => $value) {
			if (empty($list[$key]['index_logo']) ){
			$list[$key]['index_logo'] = tomedia($list[$key]['logo']);
			}
		  
		  $list[$key]['end_time'] = date('Y-m-d H:i:s', $list[$key]['end_time']);
		  $list[$key]['start_time'] = date('Y-m-d H:i:s', $list[$key]['start_time']);
		  $list[$key]['kj_time'] = date('Y-m-d H:i:s', $list[$key]['kj_time']);
		  $list[$key]['_url'] = murl('entry', array('m' => $this->module['name'], 'do' => 'enter', 'id' => $list[$key]['id'],'form'=>'login'),true,true);
		}
	}
 
  echo json_encode(array('success'=>'true','activities'=>$list,'countRecords'=> count($list)));
  exit;
}	
