<?php 
/**
 * [WeEngine System] Copyright (c) 2015 012WZ.COM
 
 */
defined('IN_IA') or exit('Access Denied');
$_W['page']['title'] = '用户列表 - 用户管理 - 用户授权';
global $_W, $_GPC;
$uid     = $_GPC['uid'];
$gzh     = pdo_fetchall("SELECT * FROM " . tablename('uni_account_users') . "where uid=$uid and role='owner'");
$modules = pdo_fetchall("SELECT * FROM " . tablename('modules')."where issystem=0",array(),'name');
$module=array();
foreach($modules as $row=>$value){
	array_push($module,$row);
}

$ownerg  = pdo_get('users',array('uid' =>$uid),'groupid');
$package = pdo_get('users_group',array('id' =>$ownerg['groupid']),'package');
$package = iunserializer($package['package']);
$taocmd  =array();
if(!empty($package)){
    foreach($package as $value){
	    $values=pdo_get('uni_group',array('id' =>$value),'modules');
	    $values=iunserializer($values['modules']);
	    if(is_array($values)){$taocmd =array_merge($taocmd,$values);}
	
    }
}
if(!empty($taocmd)){$module=array_diff($module,$taocmd);}

$user    = pdo_get('users',array('uid' =>$uid));
$agentid =$user['agentid'];
$agent   = pdo_get('users',array('uid' =>$agentid));
if (checksubmit('submit')) {
            $credit  = $_GPC['credit'];
			$crediti =$user['credit2']+$credit;
			$creditd = $agent['credit2']-$credit;
            $weid    = $_GPC['weid'];
            $module  = $_GPC['module'];
			$year    = $_GPC['time'];
            $price   = $modules[$module]['price'] * $year;
			$creditd = $creditd - $price;
			$agentnum=$creditd - $agent['credit2'];
			if(!$_W['isfounder'] && $creditd<0){message('您的余额不足，请先充值', url('system/welcome'), 'warning');}
			if($_GPC['credit']>0){
				pdo_update('users', array('credit2' => $crediti), array('uid' => $uid));
				pdo_insert('users_credits_record',array('uid'=>$uid,'credittype'=>'credit2','num'=>$credit,'createtime'=>TIMESTAMP,'remark'=>'经代理商充值'));
			}
			if(!$_W['isfounder']){
				pdo_update('users', array('credit2' => $creditd), array('uid' => $agentid));
				pdo_insert('users_credits_record',array('uid'=>$agentid,'credittype'=>'credit2','num'=>$agentnum,'createtime'=>TIMESTAMP,'remark'=>'给下线充值或购买模块'));
			}
			if (!empty($weid) && !empty($module)) {

                $buymodule = pdo_fetch("SELECT * FROM " . tablename('uni_group') . " WHERE uniacid = :uniacid", array(
                    ':uniacid' => $weid
                ));
                if (empty($buymodule)) {
                    $moduleset[] .= $module;
					$moduletime =array();
					$star = TIMESTAMP;
					$moduletime[$module]=$star + $year*31536000;
                } 
				else {
                    $moduleall = unserialize($buymodule['modules']);
                    $i         = 0;
                    foreach ($moduleall as $m) {
                        $moduleset[] .= $m;
                        $i = $i++;
                    }
                    $moduleset[] .= $module;
				    if(empty($buymodule['moduletime'])){
					    $moduletime=array_flip(iunserializer($buymodule['modules']));
		                foreach($moduletime as $key=>$value){
			                $buymod=pdo_get('buymod_mbuy',array('weid'=>$weid,'module'=>$key));
			               if(!empty($buymod)){
				                $moduletime[$key]=$buymod['endtime'];
			                }
			                else{
				                $owner=pdo_get('uni_account_users',array('uniacid'=>$weid,'role'=>'owner'));
	                            $user=pdo_get('users',array('uid'=>$owner['uid']));
				                $moduletime[$key]=$user['endtime'];
				
			                }
		                }
				    }
					else{$moduletime=unserialize($buymodule['moduletime']);}
					if(empty($moduletime[$module])){$star=TIMESTAMP;}else if($moduletime[$modulebs]<TIMESTAMP){$star=TIMESTAMP;}else{$star=$moduletime[$module];}
				    $moduletime[$module]=$star + $year*31536000;
				}
				
                $data   = array(
                    'modules' => iserializer($moduleset),
					'moduletime'=>iserializer($moduletime),
                    'name' => ''
                );
				if (empty($buymodule)) {
                        pdo_insert('uni_group', $data);
                    } else {
                        pdo_update('uni_group', $data, array(
                            'id' => $buymodule['id']
                        ));
                    }
                cache_delete("unisetting:{$weid}");
                cache_delete("unimodules:{$weid}:1");
                cache_delete("unimodules:{$weid}:");
                cache_delete("uniaccount:{$weid}");
                load()->model('module');
                module_build_privileges();
				cache_clean();
            }
            message('设置成功', url('user/display'), 'sucess');
        }
 template('user/sq');