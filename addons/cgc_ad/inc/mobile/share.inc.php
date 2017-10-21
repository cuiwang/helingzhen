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
    
    $form=empty($_GPC['form'])?"detail":$_GPC['form'];
    
    switch($_GPC['type']){
		case 'share_time_line':
		  $type='share_time_line_count';
		  break;
		case 'share_app_message':
		  $type='share_app_message_count';
		  break;
		default:
		  $type='share_time_line_count';
	}
	
	if($op=='display'){	
      if ($form!="task_detail"){	
	    $my=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_red')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=".$id." AND mid=".$mid); 	  
	    $red=new cgc_ad_red();	  
	    $ret=$red->modify($my['id'],array($type=>$my[$type]+1));
      } else {
        $my=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_task')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=".$id." AND mid=".$mid); 	  
	    $task=new cgc_ad_task();	  
	    $ret=$red->modify($my['id'],array($type=>$my[$type]+1));
      }
	}
