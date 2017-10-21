<?php
global $_W,$_GPC;
$weid=$_W['uniacid'];
$quan_id=intval($_GPC['quan_id']);
$id=intval($_GPC['id']);
  $quan=$this->get_quan();
$adv=$this->get_adv(); 
$member=$this->get_member(); 
$mid=$member['id'];
$op=$_GPC['op'];
$from_user=$member['openid'];

$config = $this ->settings;

if($op=='display'){
  if($quan['is_message']==0){
    $this->returnError('评论功能已暂时关闭');
  }	
  $mid=$member['id'];
  $submit = $_GPC['submit'];  
  //$reply=pdo_fetch("select * from " . tablename('cgc_ad_message') . " where weid=:uniacid  and advid=:advid and mid=:mid ", array(':uniacid' => $_W['uniacid'],':advid' => $id,':mid' => $mid));
  if($submit=='save'){	
    $content = trim($_GPC['content']);  
    if(empty($content)){
      $this->returnError('请说点儿什么吧~');
      exit;
    }
    
    if(text_len($content)>5000){
      $this->returnError('内容不能超过5000字哦~');
    }
   
   $status=empty($quan['is_pl'])?1:0;
 
	  $data = array(
        'advid'=>$id,
        'mid'=>$mid,
        'weid'=>$weid,
        'upbdate'=>time(),
        'content'=>$_GPC['content'],
        'status'=>$status,
      );
      pdo_insert('cgc_ad_message',$data);
	  $this->returnSuccess('评论成功！',$quan['is_pl']);								

  } else {
    include $this->template('reply');
  }
}

if ($op=='del'){
  $_kf = explode(',', $this->settings['kf_openid']);
  if(!in_array($from_user,$_kf)){
     $this->returnError('没有权限！',"referer");
  }
  $id = $_GPC['id'];
 
  pdo_delete('jy_adv_message',array('id'=>$id));
  
  $this->returnSuccess('删除成功！',"referer");

}

 if($op=='plpage'){
 	 $id = $_GPC['advid'];
      $_kf = explode(',', $this->settings['kf_openid']);
      if(in_array($from_user,$_kf)){
        $is_kf = 1;
      }else{
        $is_kf = 0;
      }
 	  $__pages = intval($_GPC['page'])*5;
      $_msglist = pdo_fetchall("SELECT a.*,b.headimgurl,b.nickname FROM ".tablename('cgc_ad_message')." a
      left join ".tablename('cgc_ad_member')." b on a.mid=b.id
	  WHERE a.weid=".$weid." AND a.advid=".$id." and a.status=1 order by upbdate desc limit ".$__pages.",5");
      $ht = '';
      foreach ($_msglist as $key => $row) {
        $ht.='<li><span class="sp1"><img src="'.tomedia($row['headimgurl']).'" >&nbsp;&nbsp;'.$row['nickname'];
	    if($is_kf==1){
		  $ht.='<a href="'.$this->createMobileUrl('reply',array('op'=>'del','id'=>$row['id'])).'" style="font-size:12px;color:red;" onclick="return confirm(\'确认删除吗?\')">删除</a>';
		}
	    $ht.='</span><span class=" sp2">'.$row['content'].'</span></li>';
      }
      
     if(!empty($ht)){
         exit(json_encode(array('status'=>1,'log'=>$ht)));
     }else{
        exit(json_encode(array('status'=>0)));
     }
   }
    


