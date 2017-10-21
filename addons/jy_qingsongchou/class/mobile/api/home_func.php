<?php

      // 首页数据
      $pagesize = empty($_GPC['pagesize'])?'5':$_GPC['pagesize'];
      $start = max(1, intval($_GPC['page']));
      $pid = $_GPC['pid'];
      $container = '';

      if(!empty($pid)){
         $container.=  " AND a.pid = ".$pid;
         //判断是否是顶级栏目
          $_top = pdo_fetchall('SELECT id FROM  '.tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and pre_id=".$pid);

         if($_top){
           $container = ' and a.pid in (';
           foreach ($_top as $key => $value) {
              if($key==0){
                $container.= $value['id'];
              }else{
                $container.= ",".$value['id'];
              }
           }
            $container.= ')';
         }
      }else{
         $container = ' and a.is_p=1';
      }

      $_count = pdo_fetchcolumn('SELECT count(a.id) FROM '.tablename(GARCIA_PREFIX."fabu")." a where  a.weid=".$this->weid."  and a.status=1 ". $container ."");

      $_list = pdo_fetchall('SELECT a.id,a.name,a.project_texdesc,a.tar_monet,a.mid,a.cover_thumb,b.headimgurl as avatar,b.nickname,b.mobile,c.project_name,a.upbdate FROM '.tablename(GARCIA_PREFIX."fabu")." a
      LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
      LEFT JOIN ".tablename(GARCIA_PREFIX."project")." c on c.id=a.pid
      where  a.weid=".$this->weid."  and a.status=1 ".$container." group by  a.id order by a.rank,a.id desc  limit ".($start - 1)*$pagesize.",".$pagesize."");

      foreach ($_list as $k => $v) {
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
                     $_list[$k]['cover_thumb'] = array_slice($_thb,0,3);
                  }
                unset($_thb);unset($ids);unset($_th);unset($_id);
                $_thb= '';
               }else if($k1=='nickname'){
                 if(empty($v2)){
                      $_list[$k][$k1] = substr($_list[$k]['mobile'],0,4)."****".substr($_list[$k]['mobile'],8);
                 }
                 else{
                   $_list[$k][$k1] = urldecode($v2);
                 }
               }
               else if($k1=='avatar'){
                   if(empty($v2)){
                       $_list[$k][$k1] = tomedia($this->sys['user_img']);
                   }else{
                        $_list[$k][$k1] = tomedia(urldecode($v2));
                   }
               }
               else if($k1=='rand_day'){
                 $_time = $v2;
                 $cur_data = date('Y-m-d',time());
                 if($this->diffDate($cur_data,$_time)<=0){
                     //过期判断
                    $_v  = 1;
                    $_vk = $k;
                    $_list[$k][$k1] =  $v['id'];
                 }else{
                   $_list[$k][$k1] =   $this->diffDate($cur_data,$_time);
                 }
               }else if($k1=='project_texdesc'){
                   $_list[$k][$k1] = mb_substr(strip_tags(htmlspecialchars_decode($v2)),0,90,'utf8');
                  //  unset($_list[$k][$k1]);
               }
               $_list[$k]['_time'] = $this->_format_date($_list[$k]['upbdate']);
              //  $_list[$k]['url'] = $this->createMobileUrl('detail',array('id'=>$_list[$k]['id']));
              //  $_list[$k]['detail'] = htmlspecialchars_decode($_list[$k]['detail']);
              //  $_list[$k]['detail'] =  strip_tags($_list[$k]['detail']);
               $_list[$k]['target_id'] =  $_list[$k]['mid']."_".$this->weid;


           }
           $_avatar = pdo_fetchall('SELECT b.headimgurl as avatar FROM '.tablename(GARCIA_PREFIX.'paylog')." a
           LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
           where a.weid=".$this->weid." and a.fid=".$v['id']." and a.status=1 order by a.id desc limit 0,15");

           foreach ($_avatar as $k1 => $v2) {
             $_list[$k]['_thumb'][$k1] = $v2['avatar'];

           }

           $_count = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$v['id']." and status=1 and type=0");
           $_sum = pdo_fetchcolumn('SELECT sum(fee) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$v['id']." and status=1");
           $_list[$k]['has_monet'] = empty($_sum)?0:$_sum;
           $_list[$k]['is_sup'] = empty($_count)?0:$_count;
            $_list[$k]['rate'] =  round(($_list[$k]['has_monet']/$_list[$k]['tar_monet'])*100,2)."%";
           /**
            * 过期操作处理
            */
           if($_v==0){
               $_thumb[]= $_list[$k];
           }else{
              //过期处理 只对过期作处理
               pdo_update(GARCIA_PREFIX."fabu",array('status'=>3),array('id'=>$v['id']));
           }
           $_v = 0;

      }
      $data['status_code'] = 1;
      $data['total'] = count($_thumb);
      $data['list']= $_thumb;

      die(json_encode($data));

 ?>
