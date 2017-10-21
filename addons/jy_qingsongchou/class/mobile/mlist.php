<?php

  $plist = pdo_fetchall("SELECT id,project_name as name FROM ".tablename(GARCIA_PREFIX."project")." where weid=".$this->weid."  and pre_id=0 order by rank asc ");

  if($_GPC['id']){
     $son = pdo_fetchall("SELECT id,project_name as name FROM ".tablename(GARCIA_PREFIX."project")." where weid=".$this->weid."  and  pre_id= ".$_GPC['id']." order by rank asc");

  }
  $where = '';
  if(!empty($_GPC['id'])){
      if($_GPC['preid']){
          $where.=  ' and a.pid='.$_GPC['preid'];
      }else{
          $where.=  ' and a.pid='.$_GPC['id'];
      }
  }


  if($_GPC['status']==3){
    $where.=  ' and (a.status=1 or a.status=2)';
  }
  else if(!empty($_GPC['status'])&&$_GPC['status']!=3){
     $where.=  ' and a.status='.$_GPC['status'];
  }
  else{
     $where.=  ' and (a.status=1 or a.status=2)';
  }

  if($_GPC['rank']==9){
          $order ='order by a.rank,a.id desc';
  }
  else if(!empty($_GPC['rank'])&&$_GPC['status']!=9){
       if($_GPC['rank']==1){
          $order =' order by a.upbdate desc';
       }else if($_GPC['rank']==2){
         $order =' order by a.tar_monet desc';
       }else if($_GPC['rank']==3){
         $order =' order by a.rank,a.id desc';
       }
       else if($_GPC['rank']==4){
         $order =' order by a.rank,a.id desc';
       }
  }else{
      $order =' order by a.rank,a.id desc';
  }
  $pindex = max(1, intval($_GPC['page']));
  $psize = 12;
  $tlist = pdo_fetchall('SELECT a.*,b.headimgurl as avatar,b.nickname,c.project_name as pname FROM '.tablename(GARCIA_PREFIX."fabu")." a
  LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
  LEFT JOIN ".tablename(GARCIA_PREFIX."project")." c on c.id=a.pid
  where  a.weid=".$this->weid."   ".$where." group by a.id   ".$order."   LIMIT ".($pindex - 1) * $psize.','.$psize);
 $total = pdo_fetchcolumn("SELECT count(id) FROM ".tablename(GARCIA_PREFIX."fabu")." a   where  a.weid=".$this->weid."   ".$where." ".$order);
            $pager = pagination($total, $pindex, $psize);
  foreach ($tlist as $key => $value) {
       foreach ($value as $k => $v) {
            if($k=='cover_thumb'){
              $_id = '';
              $ids = json_decode($v,true);
              foreach ($ids as $k3 => $v3) {
                if(!is_numeric($v3))continue;
                if($k3==0){
                   $_id=$v3;
                }
              }

              if(!empty($_id)){
                $_th = pdo_fetchcolumn("SELECT pic FROM ".tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id =".$_id);
                $tlist[$key]['cover_thumb'] = $_th;
              }
            }

            else if($k=='project_texdesc'){
                $tlist[$key]['description'] = mb_substr(strip_tags(htmlspecialchars_decode($v)),0,60,'utf-8');
            }
            else if($k=='nickname'){
                $tlist[$key][$k] = urldecode($v);
            }
            $_sum = 0;
            $_sum = pdo_fetchcolumn('SELECT sum(fee) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$value['id']." and status=1 and type=0 ");
            $_count = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$value['id']." and status=1 and type=0");
            $tlist[$key]['is_sup'] = empty($_count)?0:$_count;
            $tlist[$key]['has_monet'] = empty($_sum)?0:$_sum;
            $tlist[$key]['present'] = round($tlist[$key]['has_monet']/$tlist[$key]['tar_monet'],2)*100;
                      $tlist[$key]['_time'] = $this->_format_date($tlist[$key]['upbdate']);
       }
       $_avatar = pdo_fetchall('SELECT b.headimgurl as avatar FROM '.tablename(GARCIA_PREFIX.'paylog')." a
       LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
       where a.weid=".$this->weid." and a.fid=".$value['id']." and a.status=1 and (a.type=0 or a.type=6) order by a.id desc limit 0,10");
       foreach ($_avatar as $k1 => $v2) {
         $tlist[$key]['_thumb'][$k1] = $v2['avatar'];
       }
  }
   include $this->template('web/list/index');
 ?>
