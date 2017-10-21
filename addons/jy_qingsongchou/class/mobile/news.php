<?php

     $display = empty($_GPC['display'])?'index':$_GPC['display'];


    if($display=='index'){
        $list = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."sysmsg")." where weid=".$this->weid." AND status=1");
        foreach ($list as $key => $value) {
             foreach ($value as $k=> $v) {
                 if($k=='ms_content'){
                    $list[$key][$k] = htmlspecialchars_decode($v);
                    $list[$key][$k]  = strip_tags($list[$key][$k]);
                 }
             }
        }
    }else if($display=='pl'){
      $sql = "SELECT GROUP_CONCAT(id) FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." AND mid=".$this->conf['user']['mid']." and status>0";
      $_id = pdo_fetchcolumn($sql);
      if(empty($_id)){
        $__s = "a.mid=".$this->conf['user']['mid'];
      }else{
       $__s =  "( a.fid in (".$_id.") or a.mid=".$this->conf['user']['mid'].")";
      }
      $_sql = "SELECT a.*,b.nickname as n1,b.headimgurl as avatar,c.avatar as avatar2,c.fee,c.msg,d.thumb,d.name as title,e.nickname as n2,d.name FROM ".tablename(GARCIA_PREFIX.'message')." a
      LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
      LEFT JOIN ".tablename(GARCIA_PREFIX."paylog")." c on a.payid=c.id
      LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." d on a.fid=d.id
      LEFT JOIN ".tablename(GARCIA_PREFIX."member")." e on e.id=a.mid
      where a.weid=".$this->weid." AND ".$__s." order by a.upbdate";
      $_list = pdo_fetchall($_sql);
      foreach ($_list as $key => $value) {
           foreach ($value as $k => $v) {
             if($k=='thumb'){
               $_tmp = json_decode($v,true);
                $_list[$key][$k] =$_tmp[0];
             }
           }
         //
      }
    }else if($display=='sdetail'){
        $sql = "SELECT *  FROM ".tablename(GARCIA_PREFIX."sysmsg")." where weid=".$this->weid." and id=".$_GPC['id'];
        $conf = pdo_fetch($sql);
    }
   include $this->template('web/news/'.$display);
 ?>
