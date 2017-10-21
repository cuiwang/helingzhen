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
  include $this->template('my'); 
}	


if ($op=='load') { 		
  $pindex = intval($_GPC['currentPage']);	
  $psize= intval($_GPC['countPerPage']);
  $con=" u.uniacid=$uniacid ";	     			  
  $con.=" and u.openid ='$from_user' and share_status=1";
  
  
  if(isset($_GPC['actType'])&&$_GPC['actType']!=''){
  	 $con.=" and a.activity_type=".$_GPC['actType'];
  }
  	      
  $total=0; 
  
  $sql = "SELECT COUNT(*) FROM ".tablename("cgc_baoming_user")." u ,".tablename("cgc_baoming_activity")." a WHERE u.activity_id = a.id and {$con}";
  $total = pdo_fetchcolumn($sql);
  $start = ($pindex - 1) * $psize;
  $sql = "SELECT *,u.cj_code as user_cj_code,u.id as user_id FROM ".tablename("cgc_baoming_user")." u ,".tablename("cgc_baoming_activity")." a WHERE u.activity_id = a.id  and {$con} ORDER BY u.`id` DESC LIMIT {$start},{$psize}";
  $list = pdo_fetchall($sql);
  
  if(!empty($list)){
		foreach ($list as $key => $value) {
		  $list[$key]['logo'] = tomedia($list[$key]['logo']);
		  $list[$key]['end_time'] = date('Y-m-d', $list[$key]['end_time']);
		  $list[$key]['start_time'] = date('Y-m-d', $list[$key]['start_time']);
		  if($list[$key]['kj_time']>time()&&empty($list[$key]['zj_status'])){
		  	$list[$key]['zj_status']=2;
		  }
		  $list[$key]['kj_time'] = date('Y-m-d', $list[$key]['kj_time']);
		  $list[$key]['_url'] = murl('entry', array('m' => $this->module['name'], 'do' => 'enter', 'id' => $list[$key]['id'],'user_id'=>$list[$key]['user_id'],'form'=>'login'),true,true);
		}
	}
	
/*	 $con=" u.uniacid=$uniacid ";	     			  
  $con.=" and u.openid ='$from_user'";
	  $sql = "SELECT a.*,u.* FROM ".tablename("cgc_baoming_user")." u   WHERE  {$con} ORDER BY u.`id` DESC LIMIT {$start},{$psize}";
  $user_list = pdo_fetchall($sql);
  */
  echo json_encode(array('success'=>'true','activities'=>$list,'user_list'=>$user_list,'countRecords'=> count($list)));
  exit;
}	


        
     