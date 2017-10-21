<?php

   global $_W, $_GPC;  
   $op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
   $uniacid=$_W["uniacid"];
   $advid=$_GPC['advid'];
   
   $cgc_ad_red = new cgc_ad_red();
   
   if ($op=='display') { 		
   		$nickname = $_GPC['nickname'];
        $list = $cgc_ad_red->getRedByAdv($uniacid, $advid, $nickname);
  	}/* else if ($op=='post') {
  	     $id=$_GPC['id']; 
  	     if (!empty($id)){
            $data = $cgc_ad_group->getOne($id);  
  	     }
  	     
  	     if (checksubmit('submit')) {
  	     	$data = $_GPC['data'];
  	     	$data['uniacid'] = $_W['uniacid'];
  	     	$data['createtime'] = TIMESTAMP;
  	     
  	     	if (!empty($id)) {
  	     		$cgc_ad_group->modify($id,$data);
  	     	}else{
  	     		$cgc_ad_group->insert($data);
  	     	}
  	     	message('信息更新成功',$this->createWebUrl('cgc_sleep_record', array('op' => 'display')), 'success');
  	     }
		}else if ($op=='delete') {
			$id=$_GPC['id'];
			$cgc_ad_group->delete($id);
			message('删除成功！',referer(), 'success');
		}*/else if ($op=='deleteAll') {
			$cgc_ad_red->deleteAll(" and advid=$advid");
			message('删除成功！',referer(), 'success');
		} 
		
		 include $this->template('web/red');
	 
