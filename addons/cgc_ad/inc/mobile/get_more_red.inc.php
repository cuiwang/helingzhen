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
    



  if($_GPC['op']=='get_morered'){
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
      $is_luck="";
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

  
  

	