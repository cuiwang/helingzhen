<?php
	global $_W,$_GPC;
    $weid=$_W['uniacid'];
    $quan_id=intval($_GPC['quan_id']);
    $id=intval($_GPC['id']);        
    $member=$this->get_member();
    $from_user=$member['openid'];
    $subscribe=$member['follow'];
    $quan=$this->get_quan();    
    $adv=$this->get_adv();
    $config = $this ->settings;
    $mid=$member['id'];
    $op=empty($_GPC['op'])?"display":$_GPC['op'];	
	if($op=='display'){	
		
		if ($_GPC['hx_pass']!=$adv['hx_pass']){
		  $this->returnError('核销密码错误');
		}
		
	  $my=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_red')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=".$id." AND mid=".$mid); 
	  $red=new cgc_ad_red();	  
	  $ret=$red->modify($my['id'],array("hx_status"=>1));
 	 	
      if(!empty($ret)){
        $this->returnSuccess('核销成功');
      } else {
        $this->returnError('核销失败,或者已经核销过了');
      }
	 
	}
	if($op=='task_detail'){	
		
		if ($_GPC['hx_pass']!=$adv['hx_pass']){
		  $this->returnError('核销密码错误');
		}
		
	  $my=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_task')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=".$id." AND mid=".$mid); 
	  $red=new cgc_ad_task();	  
	  $ret=$red->modify($my['id'],array("hx_status"=>1));
 	 	
      if(!empty($ret)){
        $this->returnSuccess('核销成功');
      } else {
        $this->returnError('核销失败,或者已经核销过了');
      }
	 
	}
  
  

	