<?php

    $display = empty($_GPC['display'])?'index':$_GPC['display'];


   if($display=='index'){
     $pindex = max(1, intval($_GPC['page']));
     $psize = 20;
     $_index = ($pindex - 1) * $psize;
     $sql = "SELECT a.id,a.name,a.has_money,count(b.id) as c FROM ".tablename(GARCIA_PREFIX."fabu")." a
     LEFT JOIN ".tablename(GARCIA_PREFIX."paylog")." b on a.id =  b.fid
     where a.weid=".$this->weid." and (a.status=3 or a.status=6)  and a.has_money!=0 and b.status=1 and a.tuikuan=0  GROUP BY a.id order by a.id desc LIMIT ".($pindex - 1) * $psize.','.$psize;

     $total = pdo_fetchcolumn("SELECT count(a.id) as c FROM ".tablename(GARCIA_PREFIX."fabu")." a
     LEFT JOIN ".tablename(GARCIA_PREFIX."paylog")." b on a.id =  b.fid
     where a.weid=".$this->weid." and (a.status=3 or a.status=6)  and a.has_money!=0  and a.tuikuan=0   GROUP BY a.id");
     $pager = pagination($total, $pindex, $psize);

     $list = pdo_fetchall($sql);
   }else if($display=='tuikuan_list'){
     $sql = "SELECT a.*,b.nickname FROM ".tablename(GARCIA_PREFIX."paylog")." a
     LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b  on b.id = a.mid
     LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." c  on c.id = a.fid
     where a.weid=".$this->weid." and a.fid=".$_GPC['id']." and a.status=1  order by a.id desc";
     $_item = pdo_fetchall($sql);
   }




    include  $this->template('admin/tuikuan/'.$display);
 ?>
