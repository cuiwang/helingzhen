<?php
	global $_W,$_GPC;
    $weid=$_W['uniacid'];
    $quan_id=intval($_GPC['quan_id']);
    $id=intval($_GPC['id']);    
    $member=$this->get_member();
    $from_user=$member['openid'];
    $quan=$this->get_quan();
    $mid=$member['id'];  
    
    $config = $this ->settings;
    
     $list=pdo_fetchall("SELECT a.* FROM ".tablename('cgc_ad_member')." as a WHERE a.weid=".$weid." AND a.quan_id=".$quan_id." AND a.rob>0 ORDER BY a.rob DESC,id ASC LIMIT 20 ");
     include $this->template('rank2');

			

	
