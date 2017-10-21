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
    a WHERE a.weid=".$weid." AND a.quan_id=".$quan_id." AND a.fabu>0 ORDER BY a.fabu DESC,id ASC LIMIT 50 ");
	$list2=pdo_fetchall("SELECT a.* FROM ".tablename('cgc_ad_member')." as a WHERE a.weid=".$weid." AND a.quan_id=".$quan_id." AND a.rob>0 ORDER BY a.rob DESC,id ASC LIMIT 50 ");
   if ($quan['templet_id']){
      include $this->template('rank_new');
    } else {
     include $this->template('rank');
    }

