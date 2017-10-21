<?php

	global $_W,$_GPC;
    $weid=$_W['uniacid'];
    $quan_id=intval($_GPC['quan_id']);
    $id=intval($_GPC['id']); 
    $quan=$this->get_quan();   
    $member=$this->get_member();
    $from_user=$member['openid'];
   
    $mid=$member['id'];
    
    $config = $this ->settings;
    	
   $list=pdo_fetchall("SELECT a.*,a.nickname,a.headimgurl FROM ".tablename('cgc_ad_member')." 
   a WHERE a.weid=".$weid." AND a.quan_id=".$quan_id." AND a.fabu>0 ORDER BY a.fabu DESC,id ASC LIMIT 20 ");
   include $this->template('rank');

		

