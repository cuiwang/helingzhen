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
    
     $send=pdo_fetchall("SELECT * FROM ".tablename('cgc_ad_adv')." WHERE weid=".$weid." AND mid=".$mid." AND quan_id=".$quan_id." and status=1 order by create_time desc");
     $send_sum=0;
     if(empty($send_sum)){
       foreach ($send as $key => $value) {
         $send_sum+=$value['views'];
        }
      }
     include $this->template('send');

     

		

