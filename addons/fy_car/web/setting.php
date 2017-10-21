<?php
   global $_GPC, $_W;
   $weid = $_W['uniacid'];
   
   if($op=='display'){
	   $setting = pdo_fetch("SELECT * FROM " .tablename($this->table_car_setting). " WHERE weid='$weid'");
	   if(checksubmit('submit')){
		   $data = array(
			   'weid'         => $weid,
			   'store_number' => trim($_GPC['store_number']),
			   'commission'   => trim($_GPC['commission']),
			   'multiple'     => trim($_GPC['multiple']),
			   'conver'       => trim($_GPC['conver']),
			   'add_time'     => time(),
		   );

		   if($data['multiple']==0){
			   $storelist = pdo_fetchall("SELECT id FROM " .tablename($this->table_store). " WHERE weid={$weid} AND accountid>0");
			   if(!empty($storelist)){
				   foreach($storelist as $store){
					   pdo_update($this->table_goods, array('integral'=>0), array('storeid'=>$store['id']));
				   }
			   }
		   }

		   if (empty($setting)) {
               pdo_insert($this->table_car_setting, $data);
			   message("操作成功！", $this->createWebUrl('setting'), "success");
           } else {
               pdo_update($this->table_car_setting, $data, array('weid' => $weid));
			   message("更新成功！", $this->createWebUrl('setting'), "success");
           }
	   }
   }

   include $this->template('web/setting');