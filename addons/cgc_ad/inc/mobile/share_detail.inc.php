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
      
    $rob_next_time=$member['rob_next_time'];
    
    $mid=$member['id'];
    $op=empty($_GPC['op'])?"display":$_GPC['op'];
	   
	$quan['city']=str_replace("|", "或", $quan['city']);
    $my=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_red')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=".$id." AND mid=".$mid);
	if($op=='display'){	
		
      // 抢钱令牌，避免重复提交
      $_SESSION['rob_token'] = md5(microtime(true));

	  $adv['views']=$this->get_view($member,$adv);
      $pagesize=50;
      $red=pdo_fetchall("SELECT a.*,b.type,b.headimgurl,b.nickname FROM ".tablename('cgc_ad_red')." as a ".
      "  left join ".tablename('cgc_ad_member')." as b on a.mid=b.id ".
      " WHERE a.weid=".$weid." AND a.quan_id=".$adv['quan_id']." AND a.advid=".$id." ORDER BY a.create_time DESC limit 0,$pagesize",array(),"mid");

      $_red = pdo_fetchcolumn('SELECT count(id) FROM '.tablename('cgc_ad_red')." WHERE weid=".$weid." AND quan_id=".$adv['quan_id']." AND advid=".$id);
   

     $_msglist = pdo_fetchall("SELECT a.*,b.headimgurl,b.nickname FROM ".tablename('cgc_ad_message')." a
      left join ".tablename('cgc_ad_member')." b on a.mid=b.id
	  WHERE a.weid=".$weid." AND a.advid=".$id." and a.status=1 order by upbdate desc limit 0,5");
  
      $_msgtotal = pdo_fetchcolumn('SELECT count(id) FROM '.tablename('cgc_ad_message')." WHERE weid=".$weid." AND status=1  and advid=".$_GPC['id']);
      
      $images=iunserializer($adv['images']);
      
      $share_image=tomedia($images[0]);
      
     
        $share_link=$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('share_foo',array('form'=>'share_detail','op'=>'help','quan_id'=>$quan_id,'pid'=>$mid,'id'=>$adv['id'])), 2);
     
      
      include $this->template('share_detail');
      exit();
   }  else if($op=='auth_rob'){   	 	
      if(!empty($my)){
        $this->returnError('本次您已经抢过了，不能重复抢');
      }
	  
	  $rob_next_time=$member['rob_next_time'];
	 
	  if($adv['rob_users']>=$adv['total_num']){
        $this->returnError('手慢了，钱被抢光啦！');
      }
           															
      $ret=auth_red($member,$quan,$adv,$config);
      if ($ret['code']=="0"){
        $this->returnError($ret['msg']);
      } else {
        $this->returnSuccess("成功");
      }	
  }
   
   else if($op=='rob'){   	 	
      if(!empty($my)){
        $this->returnError('本次您已经抢过了，不能重复抢');
      }
      $rob_next_time=$member['rob_next_time'];
	 
	  if($adv['rob_users']>=$adv['total_num']){
        $this->returnError('手慢了，钱被抢光啦！');
      }
      
      															
      $ret=cal_red($member,$quan,$adv,$config);
      if ($ret['code']=="0"){
         $this->returnError($ret['msg'],$ret['data']);
      } else {
        $this->returnSuccess($ret['msg'],$ret['data']);
      }	
  }else if($_GPC['op']=='get_morered'){
    $pagesize=50;
    $__pages = intval($_GPC['page'])*$pagesize;
    $red=pdo_fetchall("SELECT a.*,b.headimgurl,b.nickname FROM ".tablename('cgc_ad_red')." as a 
          left join ".tablename('cgc_ad_member')." as b on a.mid=b.id
		  WHERE a.weid=".$weid." AND a.quan_id=".$adv['quan_id']." AND a.advid=".$id." ORDER BY a.create_time DESC limit ".$__pages.",$pagesize");
    $ht='';
    
    foreach ($red as $key => $r) {   
      if ($r['is_luck']){
	    $is_luck='<font style="color:#337AB7;">(最佳)</font>';	
      }	       								   							  
      
      $ht.= '<div class="weui_cell" style="width:87%">
            <div class="weui_cell_hd"><img src="'.$r['headimgurl'].'" style="width:20px;margin-right:5px;display:block"></div>
            <div class="weui_cell_bd weui_cell_primary">
            <p>'.$r['nickname'].'</p>
           </div>
          <div class="weui_cell_ft">'.$r['money'].$config['unit_text'].$is_luck.'</div>';							
          $ht.="</div>";
        }
	 if(!empty($ht)){
       exit(json_encode(array('status'=>1,'log'=>$ht)));
     }else{
       exit(json_encode(array('status'=>0)));
     }
   }

  
  

	