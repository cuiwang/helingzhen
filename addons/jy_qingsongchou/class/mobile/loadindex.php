<?php


   $pagesize = $_GPC['pagesize'];
    $start = max(1, intval($_GPC['start']));
   $pid = $_GPC['pid'];
   if(!empty($pid)){
      $w = ' and a.pid='.$pid ;
      //判断是否是顶级栏目
      $_top = pdo_fetchall('SELECT id FROM  '.tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and pre_id=".$pid);
      if($_top){
        $w = ' and a.pid in (';
        foreach ($_top as $key => $value) {
           if($key==0){
             $w.= $value['id'];
           }else{
             $w.= ",".$value['id'];
           }
        }
        $w.= ')';
      }
   }else{
      $w = ' and a.is_p=1';
   }
      $_checkfbC = pdo_fetchcolumn('SELECT count(a.id) FROM '.tablename(GARCIA_PREFIX."fabu")." a where  a.weid=".$this->weid."  and a.status=1 ". $w ." group by id");


   /**
    * refresh
    * 刷新操作
    */
    // if($start<0){
    //
    //   $_checkfb = pdo_fetchall('SELECT a.*,b.headimgurl as avatar,b.nickname FROM '.tablename(GARCIA_PREFIX."fabu")." a
    //   LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.openid=b.openid
    //   where  a.weid=".$this->weid."  and a.status=1 ".$w." and a.id>".$_GPC['maxid']." group by  a.id  order by a.rank,a.id desc  limit 0,".$pagesize."");
    //   $data['max'] = $_GPC['maxid'];
    //   $data['status'] = count($_checkfb);
    // }else{
    // if($_GPC['type']==1){
    //   $_checkfb = pdo_fetchall('SELECT a.*,b.headimgurl as avatar,b.nickname FROM '.tablename(GARCIA_PREFIX."fabu")." a
    //   LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.openid=b.openid
    //   where  a.weid=".$this->weid."  and a.status=1 ".$w." group by  a.id order by a.rank,a.id desc  limit ".($start - 1)*$pagesize.",".$pagesize."");
    // }else{
      $_checkfb = pdo_fetchall('SELECT a.*,b.headimgurl as avatar,b.nickname FROM '.tablename(GARCIA_PREFIX."fabu")." a
      LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.openid=b.openid
      where  a.weid=".$this->weid."  and a.status=1 ".$w." group by  a.id order by a.rank,a.id desc  limit ".($start - 1)*$pagesize.",".$pagesize."");

    // }
      $data['total'] = count($_checkfb);
      $data['type'] = $_GPC['type'];

    // }

   foreach ($_checkfb as $k => $v) {
         $_v = 0;
        foreach ($v as $k1 => $v2) {
          $_thb = $ids = $_th = '' ;
            if($k1=='cover_thumb'){

               $ids = json_decode($v2,true);
               foreach ($ids as $k3 => $v3) {
                 if($k3==0){
                    $_id.=$v3;
                 }else{
                    $_id.=",".$v3;
                 }
               }
               if(!empty($_id)){
                 $_th = pdo_fetchall("SELECT thumb FROM ".tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id in(".$_id.")");
                 foreach ($_th as $k4 => $v4) {
                    $_thb[] = $v4['thumb'];
                 }
                  $_checkfb[$k]['cover_thumb'] = $_thb;
               }

             unset($_thb);
             unset($ids);
             unset($_th);
             unset($_id);
             $_thb= '';
            }else if($k1=='nickname'){
              $_checkfb[$k][$k1] = urldecode($v2);
            }else if($k1=='rand_day'){
              $_time = $v2;
              $cur_data = date('Y-m-d',time());
              if($this->diffDate($cur_data,$_time)<=0){
                  //过期判断
                 $_v  = 1;
                 $_vk = $k;
                 $_checkfb[$k][$k1] =  $v['id'];
              }else{
                $_checkfb[$k][$k1] =   $this->diffDate($cur_data,$_time);
              }

            }else if($k1=='project_texdesc'){
                $_checkfb[$k][$k1] = html_entity_decode($v2);
            }

            $_checkfb[$k]['_time'] = $this->_format_date($_checkfb[$k]['upbdate']);
            $_checkfb[$k]['url'] = $this->createMobileUrl('detail',array('id'=>$_checkfb[$k]['id']));
            $_checkfb[$k]['detail'] = htmlspecialchars_decode($_checkfb[$k]['detail']);
            $_checkfb[$k]['detail'] =  strip_tags($_checkfb[$k]['detail']);

        }
        $_avatar = pdo_fetchall('SELECT b.headimgurl as avatar FROM '.tablename(GARCIA_PREFIX.'paylog')." a
        LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
        where a.weid=".$this->weid." and a.fid=".$v['id']." and a.status=1 order by a.id desc limit 0,15");
        $_pps = '';
        foreach ($_avatar as $k1 => $v2) {
          $_checkfb[$k]['_thumb'][$k1] = $v2['avatar'];
          $_pps[] = $v2['avatar'];
        }
        $_checkfb[$k]['pps'] = $_pps;
        $_count = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$v['id']." and status=1 and (type=0 or type=6)");
        $_sum = pdo_fetchcolumn('SELECT sum(fee) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$v['id']." and status=1 and (type=0 or type=6)");
        $_checkfb[$k]['has_monet'] = empty($_sum)?0:$_sum;
        $_checkfb[$k]['is_sup'] = empty($_count)?0:$_count;
        /**
         * 过期操作处理
         */
        if($_v==0){
            $_thumb[]= $_checkfb[$k];
        }else{
           //过期处理 只对过期作处理
            pdo_update(GARCIA_PREFIX."fabu",array('status'=>3),array('id'=>$v['id']));
        }
        $_v = 0;

   }

   $data['list']= $_thumb;
  //  $data['total'] = $_checkfbC;


  //  var_dump($data);
  die(json_encode($data));
 ?>
