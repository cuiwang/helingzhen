<?php

if(!$is_login){
  $this->_TplHtml('请先登陆',$_W['siteroot']."app/".substr($this->createMobileUrl('index'),2),'error');
  exit;
}
    if($this->modal!='pc'){ include  $this->template('bad'); exit;}
   $display = empty($_GPC['display'])?'index':$_GPC['display'];

   if($display=='faqi'){
        if($_GPC['status']=='0'){
          $status = $_GPC['status'];
        }
        else{
          $status = empty($_GPC['status'])?9:$_GPC['status'];
        }

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;

        if($status==9){
           $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and mid=".$this->conf['user']['mid']." and (status=1 or status=2 or status=3)  order by upbdate desc LIMIT ".($pindex - 1) * $psize.','.$psize;
           $sql2 = "SELECT count(id) FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and mid=".$this->conf['user']['mid']." and (status=1 or status=2 or status=3)";
        }else{
            $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and mid=".$this->conf['user']['mid']." and status=".$status ." order by upbdate desc LIMIT ".($pindex - 1) * $psize.','.$psize;
            $sql2 = "SELECT count(id) FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and mid=".$this->conf['user']['mid']." and status=".$status ;
        }

        $total = pdo_fetchcolumn($sql2);
        $pager = pagination($total, $pindex, $psize);

      $list = pdo_fetchall($sql);
      foreach ($list as $key => $value) {
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
                    $list[$key]['cover_thumb'] = $_th;
                  }
                }
                $_sum = 0;
                $_sum = pdo_fetchcolumn('SELECT sum(fee) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$value['id']." and status=1 and (type=0 or type=6)");
                $_count = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$value['id']." and status=1 and (type=0 or type=6)");
                $list[$key]['is_sup'] = empty($_count)?0:$_count;
                $list[$key]['has_monet'] = empty($_sum)?0:$_sum;
                $list[$key]['present'] = round($list[$key]['has_monet']/$list[$key]['tar_monet'],2)*100;
                $list[$key]['_time'] = $this->_format_date($list[$key]['upbdate']);
                $cur_data = date('Y-m-d',time());
                $out_data = date('Y-m-d',$list[$key]['upbdate']);
                $list[$key]['less'] = $this->diffDate($cur_data,$list[$key]['rand_day']);
           }
           $_avatar = pdo_fetchall('SELECT b.headimgurl as avatar FROM '.tablename(GARCIA_PREFIX.'paylog')." a
           LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
           where a.weid=".$this->weid." and a.fid=".$value['id']." and a.status=1 and (a.type=0 or a.type=6) order by a.id desc limit 0,10");
           foreach ($_avatar as $k1 => $v2) {
             $list[$key]['_thumb'][$k1] = $v2['avatar'];
           }
      }
   }

else if($display=='del_order'){
  $id = $_GPC['id'];
   $id;
  pdo_update(GARCIA_PREFIX."paylog",array('is_del'=>1),array('id'=>$id));
  $this->_TplHtml('删除成功',$_W['siteroot']."app/".substr($this->createMobileUrl('myorder',array('display'=>'order')),2),'success');
  exit;
}
else if($display=='order'){

  $type = empty($_GPC['type'])?3:$_GPC['type'];

  $pindex = max(1, intval($_GPC['page']));
  $psize = 20;

  if($type==3){
     $w = 'and (a.status=1 or a.status=0)';
  }else if($type==1){
      $w = 'and a.status=1';
  }else if($type==2){
     $w = 'and a.status=0';
  }
   $sql = "SELECT a.*,b.name as title,b.tar_monet,b.status as sstatus,c.nickname,b.id as fid,
   d.project_plus4 as is_reward,e.id as eid FROM ".tablename(GARCIA_PREFIX."paylog")." a
  LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." b on a.fid = b.id
  LEFT JOIN ".tablename(GARCIA_PREFIX."member")." c on b.mid = c.id
  LEFT JOIN ".tablename(GARCIA_PREFIX."project")." d on d.id=b.pid
  LEFT JOIN ".tablename(GARCIA_PREFIX."fahuo")." e on e.pid = a.id
  where a.weid=".$this->weid." and a.mid= ".$this->conf['user']['mid']." and (a.type = 0 or a.type = 6)  and b.status!=6 ".$w." and a.is_del = 0 order by a.upbdate desc  LIMIT ".($pindex - 1) * $psize.','.$psize;


  $sql2 = "SELECT  COUNT(a.id) as c FROM ".tablename(GARCIA_PREFIX."paylog")." a
 LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." b on a.fid = b.id
 LEFT JOIN ".tablename(GARCIA_PREFIX."member")." c on b.mid = c.id
 LEFT JOIN ".tablename(GARCIA_PREFIX."project")." d on d.id=b.pid
 where a.weid=".$this->weid." and a.mid= ".$this->conf['user']['mid']." and (a.type = 0 or a.type = 6)   and b.status!=6 ".$w." order by a.upbdate desc";

  $list = pdo_fetchall($sql);


  $total = pdo_fetchcolumn($sql2);
  $pager = pagination($total, $pindex, $psize);

  foreach ($list as $key => $value) {
      $_sum = 0;
        $_sum = pdo_fetchcolumn('SELECT sum(fee) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$value['fid']." and status=1 and (type=0 or type=6)");

      $list[$key]['has_monet'] = empty($_sum)?0:$_sum;
        $list[$key]['present'] = round($list[$key]['has_monet']/$list[$key]['tar_monet'],2)*100;

  }

}
   else if($display=='guanz'){
     $status = empty($_GPC['status'])?9:$_GPC['status'];
     $pindex = max(1, intval($_GPC['page']));
     $psize = 15;
        $sid = pdo_fetchcolumn('SELECT is_shouc FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$this->conf['user']['mid']);
        $status = empty($_GPC['status'])?9:$_GPC['status'];
        if(!empty($sid)){
          if($status==9){
              $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id in(".$sid.")  order by upbdate desc LIMIT ".($pindex - 1) * $psize.','.$psize;
              $sql2 = "SELECT count(id) FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id in(".$sid.") ";
          }else{
                $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id in(".$sid.") and status=".$status." order by upbdate desc LIMIT ".($pindex - 1) * $psize.','.$psize;
                $sql2 = "SELECT count(id) FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id in(".$sid.") and status=".$status;
          }

          $list = pdo_fetchall($sql);
          $total = pdo_fetchcolumn($sql2);
          $pager = pagination($total, $pindex, $psize);
          foreach ($list as $key => $value) {
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
                        $list[$key]['cover_thumb'] = $_th;
                      }
                    }
                    $_sum = 0;
                    $_sum = pdo_fetchcolumn('SELECT sum(fee) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$value['id']." and status=1 and (type=0 or type=6)");
                    $_count = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$value['id']." and status=1 and (type=0 or type=6)");
                    $list[$key]['is_sup'] = empty($_count)?0:$_count;
                    $list[$key]['has_monet'] = empty($_sum)?0:$_sum;
                    $list[$key]['present'] = round($list[$key]['has_monet']/$list[$key]['tar_monet'],2)*100;
                    $list[$key]['_time'] = $this->_format_date($list[$key]['upbdate']);
                    $cur_data = date('Y-m-d',time());
                    $out_data = date('Y-m-d',$list[$key]['upbdate']);
                    $list[$key]['less'] = $this->diffDate($cur_data,$list[$key]['rand_day']);
               }
               $_avatar = pdo_fetchall('SELECT b.headimgurl as avatar FROM '.tablename(GARCIA_PREFIX.'paylog')." a
               LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
               where a.weid=".$this->weid." and a.fid=".$value['id']." and a.status=1 and (a.type=0 or a.type=6) order by a.id desc limit 0,10");
               foreach ($_avatar as $k1 => $v2) {
                 $list[$key]['_thumb'][$k1] = $v2['avatar'];
               }
          }
        }
   }

   else if($display=='sup'){
          $status = empty($_GPC['status'])?9:$_GPC['status'];
          $status = empty($_GPC['status'])?9:$_GPC['status'];
          $pindex = max(1, intval($_GPC['page']));
          $psize = 15;
          if($status==9){
            $sql = "SELECT b.*,a.fid as id FROM ".tablename(GARCIA_PREFIX."paylog")." a
            LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." b  on a.fid = b.id
            where a.weid=".$this->weid." and a.mid = ".$this->conf['user']['mid']." and a.fid!='' group by a.fid LIMIT ".($pindex - 1) * $psize.','.$psize;
            $sql2 = "SELECT count(b.id) FROM ".tablename(GARCIA_PREFIX."paylog")." a
            LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." b  on a.fid = b.id
            where a.weid=".$this->weid." and a.mid = ".$this->conf['user']['mid']." and a.fid!='' group by a.fid ";
          }else{
            $sql = "SELECT b.*,a.fid as id FROM ".tablename(GARCIA_PREFIX."paylog")." a
            LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." b  on a.fid = b.id
            where a.weid=".$this->weid." and a.mid = ".$this->conf['user']['mid']." and a.fid!='' and b.status=".$status." group by a.fid LIMIT ".($pindex - 1) * $psize.','.$psize;
            $sql2 = "SELECT count(b.id) FROM ".tablename(GARCIA_PREFIX."paylog")." a
            LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." b  on a.fid = b.id
            where a.weid=".$this->weid." and a.mid = ".$this->conf['user']['mid']." and a.fid!='' and b.status=".$status." group by a.fid ";
          }

          $list = pdo_fetchall($sql);
          $total = pdo_fetchcolumn($sql2);
          $pager = pagination($total, $pindex, $psize);
          foreach ($list as $key => $value) {
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
                        $list[$key]['cover_thumb'] = $_th;
                      }
                    }
                    $_sum = 0;
                    $_sum = pdo_fetchcolumn('SELECT sum(fee) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$value['id']." and status=1 and (type=0 or type=6)");
                    $_count = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$value['id']." and status=1 and (type=0 or type=6)");
                    $list[$key]['is_sup'] = empty($_count)?0:$_count;
                    $list[$key]['has_monet'] = empty($_sum)?0:$_sum;
                    $list[$key]['present'] = round($list[$key]['has_monet']/$list[$key]['tar_monet'],2)*100;
                    $list[$key]['_time'] = $this->_format_date($list[$key]['upbdate']);
                    $cur_data = date('Y-m-d',time());
                    $out_data = date('Y-m-d',$list[$key]['upbdate']);
                     $list[$key]['less'] = $this->diffDate($cur_data,$list[$key]['rand_day']);
               }
               $_avatar = pdo_fetchall('SELECT b.headimgurl as avatar FROM '.tablename(GARCIA_PREFIX.'paylog')." a
               LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
               where a.weid=".$this->weid." and a.fid=".$value['id']." and a.status=1 and (a.type=0 or a.type=6) order by a.id desc limit 0,10");
               foreach ($_avatar as $k1 => $v2) {
                 $list[$key]['_thumb'][$k1] = $v2['avatar'];
               }
          }
   }
   else if($display=='kuaidi'){
     load()->func('communication');
     $id = $_GPC['id'];
     $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."fahuo")." where weid=".$this->weid." and id=".$id;
     $conf = pdo_fetch($sql);
    //  var_dump($conf);

     $code =  $this->_kuaidi($conf['kuaidi']);

     $rand = rand();
      $url = "http://www.kuaidi100.com/query?type=".$code."&postid=".$conf['kuai_order'];
    $dat =$this->wapi->https_url($url);
    $dat = json_decode($dat,true);
    var_dump($dat);
    exit;
   }
   include $this->template('web/order/'.$display);
 ?>
