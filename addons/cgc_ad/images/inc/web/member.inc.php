<?php

		global $_W,$_GPC;
		$weid=$_W['uniacid'];
		checklogin();
		$op=empty($_GPC['op'])?"display":$_GPC['op'];


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

			message("删除成功！",$this->createWebUrl('member'),'success');

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

			if(checksubmit('submit')) {
				$member=$_GPC['member'];			
				pdo_update("cgc_ad_member",$member,array('id'=>$id));
				message("更新数据成功！",$this->createWebUrl('member',array('op'=>'display')),'success');
			} else {
			   $member=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_member')." WHERE weid=".$weid.' and id='.$id);
			}
          }

		else if ($op=='display')

		{

			load()->func('tpl');

			$now_day=strtotime(date('Y-m-d', time()));

			$time = $_GPC['time'];

		
			if (!empty($_GPC['time'])) {
				$starttime = strtotime($_GPC['time']['start']);
				$endtime = strtotime($_GPC['time']['end']) + 86399;
			}
			
			
			if (empty($starttime) || empty($endtime)) {
				$starttime = strtotime('-1 month');
				$endtime = TIMESTAMP;
			}
			
			if (!empty($_GPC['time'])) {

				$condition.=" AND a.createtime>=$starttime AND a.createtime<=$endtime";

			}
			
			
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

			}

			else

			{

				$condition .=" ORDER BY a.createtime DESC";

			}



			if(!empty($_GPC['id']))

			{

				$condition.=" AND a.id=".$_GPC['id'];

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
			
				
						
		     $settings['rootca']= IA_ROOT . '/cert/rootca.pem.' . $weid;
             $settings['apiclient_cert'] = IA_ROOT . '/cert/apiclient_cert.pem.' . $weid;
             $settings['apiclient_key'] = IA_ROOT . '/cert/apiclient_key.pem.' . $weid;
			 $settings['pay_desc'] ="红包来了";
      
			$ret=send_qyfk($settings,$data['openid'],$data['credit']);
			
				if ($ret['code']==0){
					pdo_update("cgc_ad_member",array('credit'=>0),array('id'=>$id));
					message('提现成功！',referer(), 'success');
				}else{
					message('提现失败.'.$ret['message']);
				}
			}
		}



		include $this->template('web/member');