<?php

		global $_W,$_GPC;
		$weid=$_W['uniacid'];
		checklogin();
		$op=empty($_GPC['op'])?"display":$_GPC['op'];
		
		$quan = pdo_fetchall("SELECT id,aname FROM " . tablename('cgc_ad_quan') . " WHERE weid=" . $weid . " AND del=0",array(),"id");
		$cgc_ad_vip_rule = new cgc_ad_vip_rule();
		if ($op == 'display'){
				
			$con = " order by id asc";
	     
			$list = $cgc_ad_vip_rule->getByConAll($con);
			
			foreach ($list as $key => $value){
				if (empty($value['piece_model'])){
					$value['piece_model'] = '1,2,3,4,5,6,7';
				}
				if(!empty($value['piece_model'])){
					$value['piece_model'] = explode(',',$value['piece_model']);
				}
				$list[$key] = $value;
			}
			
		} else if ($op=='post') {
  	     $id=$_GPC['id']; 
  	     if (!empty($id)){
            $data = $cgc_ad_vip_rule->getOne($id);
  	     }
  	     
  	     if (empty($data['piece_model'])){
  	     	$data['piece_model'] = '1,2,3,4,5,6,7';
  	     }
  	     
  	     if(!empty($data['piece_model'])){
  	     	$data['piece_model'] = explode(',',$data['piece_model']);
  	     }
  	     
  	  
  	     
  	     if (checksubmit('submit')) {
  	     	$data = $_GPC['data'];
  	     	$data['weid'] = $weid;
  	     	$data['createtime'] = TIMESTAMP;
  	     	
  	     	if(!empty($_GPC['piece_model'])){
  	     		$data['piece_model'] = implode(',',$_GPC['piece_model']);
  	     	}
  	     	
  	     	if (!empty($id)) {
  	     		$cgc_ad_vip_rule->modify($id,$data);
  	     	}else{
  	     		$cgc_ad_vip_rule->insert($data);
  	     	}
  	     	message('信息更新成功',$this->createWebUrl('cgc_ad_vip_rule', array('op' => 'display')), 'success');
  	     }
		} else if ($op=='delete') {
			$id=$_GPC['id'];
			$cgc_ad_vip_rule->delete($id);
			message('删除成功！',referer(), 'success');
		} else if ($op=='deleteAll') {
			$cgc_ad_vip_rule->deleteAll();
			message('删除成功！',referer(), 'success');
		}


		include $this->template('web/cgc_ad_vip_rule');