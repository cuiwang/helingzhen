<?php
/**
 * [Weizan System] Copyright (c) 2014 WEIZANCMS.COM
 
 */
 defined('IN_IA') or exit('Access Denied');
        global $_W, $_GPC;
        $weid     = $_W['uniacid'];
        $modulebs = $_GPC['module'];
       $dos = array('list', 'post', 'details');
		$do = in_array($do, $dos) ? $do : 'post';
		$items     = pdo_fetch("SELECT * FROM " . tablename('modules') . "where name=:module", array(
                ':module' => $modulebs
            ));
        if ($do == 'post') {
			load()->model('module');
			$modtypes = module_types();
            
			
			$items['imgsrc'] = '../addons/' . $items['name'] . '/icon-custom.jpg';
				if(!file_exists($items['imgsrc'])) {
					$items['imgsrc'] = '../addons/' . $items['name'] . '/icon.jpg';
				}
			
            $member    = pdo_fetch("SELECT * FROM " . tablename('users') . " where uid=:uid", array(
                ':uid' => $_W['uid']
            ));
			

            if (empty($items)) {
                message('您所购买的模块或套餐不存在，请联系管理员', referer, 'warning');
                exit;
            }
        }
        if (checksubmit('submit')) {
            $year  = $_GPC['time'];
            $price = $items['price'] * $year;
			$owner = pdo_get('uni_account_users',array('uniacid'=>$weid,'role'=>'owner'));
			if($_W['uid'] != $owner['uid']){
                message('抱歉，您不是当前公众号的主管理员，不能使用此功能', referer, 'warning');
                exit;
			}
			if($member['credit2'] < $price){
                message('抱歉，您的余额不足，请先充值', referer, 'warning');
                exit;
			}
			//模块购买
			$buymodule = pdo_fetch("SELECT * FROM " . tablename('uni_group') . " WHERE uniacid = :uniacid", array(':uniacid' => $weid));
			if (empty($buymodule)) {
                    $moduleset[] .= $modulebs;
					
					$star = TIMESTAMP;
					$time = $star + $year*31536000;
					$moduletime = array($modulebs=>$time);
                } 
				else {
                    $moduleall = unserialize($buymodule['modules']);
                    $i         = 0;
                    foreach ($moduleall as $m) {
                        $moduleset[] .= $m;
                        $i = $i++;
                    }
                    $moduleset[] .= $modulebs;
				    if(empty($buymodule['moduletime'])){
					    $moduletime=array_flip($moduleall);
		                foreach($moduletime as $key=>$value){
			                $buymod=pdo_get('buymod_mbuy',array('weid'=>$weid,'module'=>$key));
			               if(!empty($buymod)){
				                $moduletime[$key]=$buymod['endtime'];
			                }
			                else{
	                            $user=pdo_get('users',array('uid'=>$owner['uid']));
				                $moduletime[$key]=$user['endtime'];
				
			                }
		                }
				    }
					else{$moduletime=unserialize($buymodule['moduletime']);}
					if(empty($moduletime[$modulebs])){$star=TIMESTAMP;}else if($moduletime[$modulebs]<TIMESTAMP){$star=TIMESTAMP;}else{$star=$moduletime[$modulebs];}
				    $moduletime[$modulebs]=$star + $year*31536000;
				}
				
                $data   = array(
                    'modules' => iserializer($moduleset),
					'moduletime'=>iserializer($moduletime),
					'uniacid' => $weid,
                    'name' => ''
                );
				if (empty($buymodule)) {
                        pdo_insert('uni_group', $data);
                } 
			    else {
                    pdo_update('uni_group', $data, array('id' => $buymodule['id']));
                }
			//会员扣费
			$credit= $member['credit2'] - $price;
			pdo_update('users', array('credit2'=>$credit), array('uid' => $_W['uid']));
			//消费记录
			pdo_insert('users_credits_record',array('uid'=>$_W['uid'],'uniacid'=>$weid,'credittype'=>'credit2','num'=>-$price,'createtime'=>TIMESTAMP,'remark'=>'购买模块'.$items['title']));

            cache_delete("unisetting:{$weid}");
            cache_delete("unimodules:{$weid}:1");
            cache_delete("unimodules:{$weid}:");
            cache_delete("uniaccount:{$weid}");
            load()->model('module');
            module_build_privileges();

            message('购买成功！', url('members/module/list'), 'sucess');
        }
        template('members/order');