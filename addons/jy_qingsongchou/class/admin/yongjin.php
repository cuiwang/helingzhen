<?php


   $display = empty($_GPC['display'])?"index":$_GPC['display'];
   $pindex = max(1, intval($_GPC['page']));
   $psize = 20;

   if($display=='index'){
        $list = pdo_fetchall('SELECT a.*,b.project_name,c.tar_monet,b.yongjin FROM '.tablename(GARCIA_PREFIX."yongjin")." a
        LEFT JOIN ".tablename(GARCIA_PREFIX."project")." b on a.pid=b.id
        LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." c on c.id=a.fid
        where a.weid=".$this->weid ." LIMIT ".($pindex - 1) * $psize.','.$psize);
      $_sum  = pdo_fetchcolumn("SELECT SUM(fee) FROM ".tablename(GARCIA_PREFIX."yongjin")." where weid=".$this->weid);
      $total = pdo_fetchcolumn("SELECT count(id) FROM ".tablename(GARCIA_PREFIX."yongjin")." where weid=".$this->weid);
   }else if($display=='tixian'){
       $list = pdo_fetchall("SELECT c.nickname,c.headimgurl as avatar,a.shouxufei,a.fee,a.id,a.upbdate,a.msg,b.status FROM ".tablename(GARCIA_PREFIX."paylog")." a
       LEFT JOIN ".tablename(GARCIA_PREFIX."withdraw")." b on a.id=b.payid
       LEFT JOIN ".tablename(GARCIA_PREFIX."member")." c on c.id=a.mid
       where a.weid=".$this->weid." AND a.type=1" ." LIMIT ".($pindex - 1) * $psize.','.$psize);
      $_sum  = pdo_fetchcolumn("SELECT SUM(money) FROM ".tablename(GARCIA_PREFIX."withdraw")." where weid=".$this->weid." and status=1");
      $total = pdo_fetchcolumn("SELECT count(id) FROM ".tablename(GARCIA_PREFIX."withdraw")." where weid=".$this->weid." and status=1");
   }else if($display=='suport'){
       $list = pdo_fetchall("SELECT a.id,a.fee,c.nickname,c.headimgurl as avatar,b.tar_monet,a.upbdate,b.name,c.id as mid FROM ".tablename(GARCIA_PREFIX."paylog")." a
       LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." b on a.fid=b.id
       LEFT JOIN ".tablename(GARCIA_PREFIX."member")." c on c.id=a.mid
       where a.weid=".$this->weid." AND a.type=0 and a.status=1  order by id desc" ." LIMIT ".($pindex - 1) * $psize.','.$psize);
      $_sum  = pdo_fetchcolumn("SELECT SUM(fee) FROM ".tablename(GARCIA_PREFIX."paylog")." where weid=".$this->weid." and status= 1 and type=0");
               $total = pdo_fetchcolumn("SELECT COUNT(id) FROM ".tablename(GARCIA_PREFIX."paylog")." where weid=".$this->weid." and status= 1 and type=0");
   }else if($display=='finish'){
     $list = pdo_fetchall("SELECT a.id,a.fee,c.nickname,c.headimgurl as avatar,b.tar_monet,a.upbdate,b.name,d.yongjin FROM ".tablename(GARCIA_PREFIX."paylog")." a
     LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." b on a.fid=b.id
     LEFT JOIN ".tablename(GARCIA_PREFIX."member")." c on c.id=a.mid
     LEFT JOIN ".tablename(GARCIA_PREFIX."project")." d on d.id=b.pid
     where a.weid=".$this->weid." AND a.type=4 and a.status=1" ." LIMIT ".($pindex - 1) * $psize.','.$psize);
    $_sum  = pdo_fetchcolumn("SELECT SUM(fee) FROM ".tablename(GARCIA_PREFIX."paylog")." where weid=".$this->weid." and status= 1 and type=4");
   $total = pdo_fetchcolumn("SELECT COUNT(id) FROM ".tablename(GARCIA_PREFIX."paylog")." where weid=".$this->weid." and status= 1 and type=4");
   }


   $pager = pagination($total, $pindex, $psize);
   include $this->template('admin/yongjin/'.$display);
 ?>
