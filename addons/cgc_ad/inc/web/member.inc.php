<?php

		global $_W,$_GPC;
		$weid=$_W['uniacid'];
		checklogin();
		$op=empty($_GPC['op'])?"display":$_GPC['op'];
		$sys_settings = $this->sys_settings;

		if($op=='forbidden')
		{
		  $id=$_GPC['id'];
		  pdo_update('cgc_ad_member',array('status'=>0),array('weid'=>$weid,'id'=>$id));
		  message("封号成功！",$this->createWebUrl('member'),'success');
		}
		
		 if($op=='del_help')
		{
            $id=$_GPC['id'];
            $zz=pdo_delete('cgc_ad_help',array('weid'=>$weid,'mid'=>$id));
            if (!empty($zz)){
			  message("删除成功！",referer(),'success');
            } else {
              message("删除失败！",referer(),'success');
            }
          }
		
	   if($op=='del')

		{

			$id=$_GPC['id'];

			pdo_delete('cgc_ad_member',array('weid'=>$weid,'id'=>$id));

			message("删除成功！",referer(),'success');

		}

		else if($op=='huifu')
		{
		  $id=$_GPC['id'];
		  pdo_update('cgc_ad_member',array('status'=>1),array('weid'=>$weid,'id'=>$id));
          message("解封成功！",$this->createWebUrl('member'),'success');
        }

		else if($op=='post')
		{
			$id=$_GPC['id'];

			load()->func('tpl');
			
			$cgc_ad_vip_rule = new cgc_ad_vip_rule();
		    if(!empty($_GPC['quan_id']))
	        {
	          $con.=" AND quan_id= ".intval($_GPC['quan_id']);
	        }
	      
			$rule_list = $cgc_ad_vip_rule->getByConAll($con);

			if(checksubmit('submit')) {
				$member=$_GPC['member'];	
				if ($_W['role']!="founder" && $_W['role']!="manager"){
				  unset($member['credit']);
				}
				
				if (!empty($member['vip_id'])){
					$vip_rules = $cgc_ad_vip_rule->getOne($member['vip_id']);
					$member['vip_name'] = $vip_rules['vip_name'];
					$member['vip_recharge'] = $vip_rules['vip_recharge'];
					$member['vip_rob'] = $vip_rules['vip_rob'];
				}
               
            
				pdo_update("cgc_ad_member",$member,array('id'=>$id));
				
				message("更新数据成功！",$this->createWebUrl('member',array('op'=>'display')),'success');
			} else {
			   $member=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_member')." WHERE weid=".$weid.' and id='.$id);
			  
			}
          }

		else if ($op=='display')

		{

			load()->func('tpl');
			
			if (!empty($_GPC['nickname'])) {

				$condition.=" AND a.nickname like '%{$_GPC['nickname']}%'";

			}



			if(!empty($_GPC['type']))

			{

				if($_GPC['type']=='1')

				{

					$condition .=" AND a.type=1 ";

				}

				if($_GPC['type']=='2')

				{

					$condition .=" AND a.type=2 ";

				}

				if($_GPC['type']=='3')

				{

					$condition .=" AND a.type=3 ";

				}

				if($_GPC['type']=='4')

				{

					$condition .=" AND a.type=4 ";

				}

			}

			if(!empty($_GPC['quan_id']))

	        {

	        	$condition.=" AND quan_id= ".intval($_GPC['quan_id']);

	        }
	        
	        
	        	if(!empty($_GPC['nickname']))

	        {

	        	$condition.=" AND nickname like '%".$_GPC['nickname']."%'";

	        }
	        
	        
	        	if(!empty($_GPC['id']))

			{

				$condition.=" AND a.id=".$_GPC['id'];

			}

		    if(!empty($_GPC['inviter_id']))
            {

			  $condition.=" AND a.inviter_id=".$_GPC['inviter_id'];

			}
	        
	        
	        

			if(!empty($_GPC['sort']))

			{

				if($_GPC['sort']=='time')

				{

					$condition .=" ORDER BY a.createtime DESC";

				}

				if($_GPC['sort']=='fabu')

				{

					$condition .=" ORDER BY a.fabu DESC";

				}

				if($_GPC['sort']=='rob')

				{

					$condition .=" ORDER BY a.rob DESC";

				}

				if($_GPC['sort']=='credit')

				{

					$condition .=" ORDER BY a.credit DESC";

				}
				
				
				if($_GPC['sort']=='no_account_amount')

				{

					$condition .=" ORDER BY a.no_account_amount DESC";

				}

			}

			else

			{

				$condition .=" ORDER BY a.createtime DESC";

			}



		





			$pindex = max(1, intval($_GPC['page']));

			$psize = 20;

			$quan=pdo_fetchall("SELECT id,aname FROM ".tablename('cgc_ad_quan')." WHERE weid=".$weid." AND del=0");



           $list=pdo_fetchall("SELECT a.* FROM ".tablename('cgc_ad_member')." a WHERE a.weid=$weid $condition LIMIT ".($pindex - 1) * $psize.",{$psize}");
			
			$total=pdo_fetchcolumn("SELECT count(a.id) FROM ".tablename('cgc_ad_member')." as a WHERE a.weid=$weid $condition ");

			$pager = pagination($total, $pindex, $psize);

		}else if($op=='tx'){
			$id=$_GPC['id'];
			$quan_id=$_GPC['quan_id'];
			if (empty($id)){
			  message('提现用户不能为空.');
			}
			$data = pdo_fetch("SELECT a.* FROM ".tablename('cgc_ad_member')." a WHERE a.weid=$weid and id=$id and quan_id=$quan_id");
			$settings = $this->settings;
			
		   if($data['credit']<1){
		     message('剩余金额不足以提现.');
		   }else{
		   	$cgc_ad_quan=new cgc_ad_quan();
            $quan =$cgc_ad_quan->getOne($quan_id); 
            $quan['tx_percent']=empty($quan['tx_percent'])?0:$quan['tx_percent'];
	        $data['credit']=$data['credit']*((100-$quan['tx_percent'])/100);  
            if ($sys_settings['tx_type']) {
              $md5=md5("{$_W['uniacid']}{$_W['config']['setting']['authkey']}");
	          $end=$weid;
	          if (file_exists(IA_ROOT . '/cert/rootca.pem.' . $md5)){
	            $end=$md5;
	          }		           	      			
		      $settings['rootca']= IA_ROOT . '/cert/rootca.pem.' . $end;
              $settings['apiclient_cert'] = IA_ROOT . '/cert/apiclient_cert.pem.' . $end;
              $settings['apiclient_key'] = IA_ROOT . '/cert/apiclient_key.pem.' . $end;
			  $settings['pay_desc'] ="红包来了";
              $ret=send_qyfk($settings,$data['openid'],$data['credit']);
              $msg=$data['credit'].'元。红包已经存到用户的微信钱包里面！';
           } else {
             $ret = $this->transferByRedpack(array(
               'id' => time(),
               'nick_name' => $quan['aname'],
               'send_name' => $quan['aname'],
               'money' => intval($data['credit'] * 100) ,
               'openid' => $data['openid'],
               'wishing' => '祝您天天开心！',
               'act_name' => '提现红包',
               'remark' => '用户微信红包提现'
             ));
             $msg=$data['credit'].'元。红包发送，点击领取！';
          }
             if (!is_error($ret)){
			    pdo_update("cgc_ad_member",array('credit'=>0),array('id'=>$id));
			    message('提现成功'.$msg,referer(), 'success');
			  }else{
			    message('提现失败.'.$ret['message']);
			  }
		    }
		} else if ($op=="send_msg"){
		  $id = $_GPC['id'];
		  $config = $this->settings;
		  $_user = pdo_fetch('SELECT openid FROM ' . tablename('cgc_ad_member') . " where weid=" . $weid . "  AND quan_id=" . $_GPC['quan_id']. " and id=$id");

        $_url = $_W['siteroot'] . 'app/' . substr($this->createMobileUrl('foo',array('form'=>'index','quan_id'=>$_GPC['quan_id'])), 2);
		$htt = str_replace('"', "'", htmlspecialchars_decode($config['tuisong']));
		$_tdata = array (
			'first' => array (
				'value' => '系统通知',
				'color' => '#173177'
			),
			'keyword1' => array (
				'value' => $config['tuisong'],
				'color' => '#173177'
			),
			'keyword2' => array (
				'value' => date('Y-m-d H:i:s', time()),
				'color' => '#173177'
			),
			'keyword3' => array (
				'value' => '抢钱通知',
				'color' => '#173177'
			),
			'remark' => array (
				'value' => '点击详情进入',
				'color' => '#173177'
			),


		);

		if ($config['is_type'] == 1) {
		  $a = sendTemplate_common($_user['openid'], $config['template_id'], $_url, $_tdata);
		} else {
          $a = post_send_text($_user['openid'], $htt);
		}

		if (is_error($a)){
		  message($a['message'], referer(), 'success');
		}

		message("发送通知成功!", referer(), 'success');

		}



		include $this->template('web/member');