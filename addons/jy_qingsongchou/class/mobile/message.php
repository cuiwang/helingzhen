<?php

   $display  = empty($_GPC['display'])?'message':$_GPC['display'];
   if($this->sys['is_h5']&&$this->modal == 'phone'){
     $mid = json_decode($this->cookies->get('userDatas'),true);
     $mid =$mid['uid'];
   }else{
     $mid = $this->_gmodaluserid();
   }


  if($display=='list'){
     $list = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."sysmsg")." where weid=".$this->weid." AND status=1");

  }else if($display=='detail'){
       $id = $_GPC['id'];
       $detail = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX."sysmsg")." where weid=".$this->weid." AND id=".$id);
  }else if($display=='pl'){
       $sql = "SELECT GROUP_CONCAT(id) FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." AND mid=".$mid." and status>0";
       $_id = pdo_fetchcolumn($sql);
       if(empty($_id)){
         $__s = "a.mid=".$mid;
       }else{
        $__s =  "( a.fid in (".$_id.") or a.mid=".$mid.")";
       }
       $_sql = "SELECT a.*,b.nickname,b.headimgurl as avatar,c.avatar as avatar2,c.fee,c.msg,d.thumb,d.name as title,e.nickname as n2 FROM ".tablename(GARCIA_PREFIX.'message')." a
       LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
       LEFT JOIN ".tablename(GARCIA_PREFIX."paylog")." c on a.payid=c.id
       LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." d on a.fid=d.id
       LEFT JOIN ".tablename(GARCIA_PREFIX."member")." e on e.id=a.mid
       where a.weid=".$this->weid." AND ".$__s." order by a.upbdate desc";
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
      //  var_dump($_list);
  }

   include $this->template('message/'.$display);
 ?>
