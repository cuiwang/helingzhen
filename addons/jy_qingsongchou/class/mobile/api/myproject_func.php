<?php

if(empty($_GPC['mid'])){
   _fail(array('msg'=>'请输入用户id'));
}

    if($action=='fq'){
      $status = empty($_GPC['status'])?1:$_GPC['status'];
      if($status==2){
         $sql = "SELECT id,thumb,name,upbdate,rand_day FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and mid='".$_GPC['mid']."' and (status=2  or status=9)";
      }else if($status==3){
        $sql = "SELECT id,thumb,name,upbdate,rand_day FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and mid='".$_GPC['mid']."' and (status=3 or status=6 or status=8)";
      }
      else if($status==1){
         $sql = "SELECT id,thumb,name,upbdate,rand_day FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and mid='".$_GPC['mid']."' and (status=".$status." or status=4) order by status desc";
      }

       $list = pdo_fetchall($sql);

       //处理图片
       foreach ($list as $key => $value) {
            foreach ($value as $k => $v) {
                if($k=='thumb'){
                   $list[$key][$k] = implode(',',json_decode($v,true));
                   if(!empty($list[$key][$k][0])){
                     $list[$key][$k] = pdo_fetchcolumn('SELECT pic FROM '.tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id =".$list[$key][$k][0]);
                     if(empty($list[$key][$k])){
                        $list[$key][$k] = tomedia($this->sys['web_logo']);
                     }
                   }else{
                     $list[$key][$k] =  tomedia($this->sys['web_logo']);
                   }

                 }else if($k=='rand_day'){
                  $list[$key]['less'] =  $this->diffDate( date('Y-m-d',time()),$v);
                  unset($list[$key][$k]);
                }else if($k=='upbdate'){
                    $list[$key][$k] = $this->_format_date($v);
                }
            }
       }
       _success(array('res'=>$list,'mid'=>$_GPC['mid']));
    }else if($action=='suport'){
      $status = empty($_GPC['status'])?1:$_GPC['status'];
      if($status==2){
        $sql = "SELECT b.id,b.thumb,b.name,b.upbdate,b.rand_day from ".tablename(GARCIA_PREFIX."paylog")." a
        LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." b on a.fid=b.id
        where a.weid=".$this->weid." AND a.mid=".$_GPC['mid']." and b.status=".$status." and a.status=1 group by fid";
      }else if($status==3){
        $sql = "SELECT b.id,b.thumb,b.name,b.upbdate,b.rand_day from ".tablename(GARCIA_PREFIX."paylog")." a
        LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." b on a.fid=b.id
        where a.weid=".$this->weid." AND a.mid=".$_GPC['mid']."  and (b.status=3 or b.status=6 or b.status=8) group by fid";
      }
      else if($status==1){
        $sql = "SELECT b.id,b.thumb,b.name,b.upbdate,b.rand_day from ".tablename(GARCIA_PREFIX."paylog")." a
        LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." b on a.fid=b.id
        where a.weid=".$this->weid." AND a.mid=".$_GPC['mid']." and b.status=".$status." and a.status=1 group by fid";
      }


      $list = pdo_fetchall($sql);
      foreach ($list as $key => $value) {
           foreach ($value as $k => $v) {
               if($k=='thumb'){
                  $list[$key][$k] = implode(',',json_decode($v,true));
                  if(empty($list[$key][$k][0])){
                     $list[$key][$k] = $this->sys['web_logo'];
                  }else{
                    $list[$key][$k] = pdo_fetchcolumn('SELECT pic FROM '.tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id =".$list[$key][$k][0]);
                  }


                }else if($k=='rand_day'){
                 $list[$key]['less'] =  $this->diffDate( date('Y-m-d',time()),$v);
                 unset($list[$key][$k]);
               }else if($k=='upbdate'){
                   $list[$key][$k] = $this->_format_date($v);
               }
           }
      }
             _success(array('res'=>$list));
    }else if($action=='guanz'){
      $status = empty($_GPC['status'])?1:$_GPC['status'];
      $_a = pdo_fetchcolumn('SELECT is_shouc FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$_GPC['mid']);
      $_a = explode(',',$_a);
      $is_up = false;
      foreach ($_a as $key => $value) {
          if(empty($value)){
             $is_up = true;
          }else{
             $_fids[]=$value;
          }
      }

      if($is_up){
          pdo_update(GARCIA_PREFIX."member",array('is_shouc'=>implode(',',$_fids)),array('id'=>$_GPC['mid']));
      }

      $_a = implode(',',$_fids);

       if(!empty($_a)){
         if($status==2){
            $sql = "SELECT id,thumb,name,upbdate,rand_day FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid."   and status=2 and id in(".$_a.")";
         }else if($status==3){
           $sql = "SELECT id,thumb,name,upbdate,rand_day FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid."  and id in(".$_a.") and (status=3 or status=6 or status=8) ";
         }
         else if($status==1){
            $sql = "SELECT id,thumb,name,upbdate,rand_day FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid."  and id in(".$_a.") and (status=".$status." or status=4)   order by status desc";
         }
       }
     $list = pdo_fetchall($sql);
     foreach ($list as $key => $value) {
          foreach ($value as $k => $v) {
              if($k=='thumb'){
                 $list[$key][$k] = implode(',',json_decode($v,true));
                 if(empty($list[$key][$k][0])){
                    $list[$key][$k] = $this->sys['web_logo'];
                 }else{
                   $list[$key][$k] = pdo_fetchcolumn('SELECT pic FROM '.tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id =".$list[$key][$k][0]);
                 }


               }else if($k=='rand_day'){
                $list[$key]['less'] =  $this->diffDate( date('Y-m-d',time()),$v);
                unset($list[$key][$k]);
              }else if($k=='upbdate'){
                  $list[$key][$k] = $this->_format_date($v);
              }
          }
     }
            _success(array('res'=>$list));
  }

 ?>
