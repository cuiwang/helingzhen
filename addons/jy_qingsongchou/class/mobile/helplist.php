<?php
      $project_l = pdo_fetchall("SELECT * FROM ".tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and is_p=1 order by rank asc limit 3");
    $display = empty($_GPC['display'])?'index':$_GPC['display'];



      if($display=='index'){

        $_list = pdo_fetchall('SELECT id,title FROM '.tablename(GARCIA_PREFIX.'oques')." where weid=".$this->weid." and level=1 and type=1 order by  rank asc ");

        $_lg = pdo_fetchcolumn("SELECT group_concat(id) FROM ".tablename(GARCIA_PREFIX.'oques')." where weid=".$this->weid." and level=1 and type=1 order by  rank asc ");
        if(!empty($_lg)){
          $list = pdo_fetchall('SELECT id,title,content,pre_id FROM '.tablename(GARCIA_PREFIX."oques")." where weid=".$this->weid." AND pre_id in (".$_lg.") order by rank asc");
          foreach ($list as $key => $value) {
              $qlist[$value['pre_id']][]= $value;
          }
          foreach ($qlist as $key => $value) {
                $tqlist[]= $value;
          }
        }
      }else if($display=='project'){
        $_list = pdo_fetchall('SELECT id,title FROM '.tablename(GARCIA_PREFIX.'oques')." where weid=".$this->weid." and level=1 and type=2 order by  rank asc ");

        $_lg = pdo_fetchcolumn("SELECT group_concat(id) FROM ".tablename(GARCIA_PREFIX.'oques')." where weid=".$this->weid." and level=1 and type=2 order by  rank asc ");
        if(!empty($_lg)){
            $list = pdo_fetchall('SELECT id,title,content,pre_id FROM '.tablename(GARCIA_PREFIX."oques")." where weid=".$this->weid." AND pre_id in (".$_lg.") order by rank asc");
            foreach ($list as $key => $value) {
                $qlist[$value['pre_id']][]= $value;
            }
            foreach ($qlist as $key => $value) {
                  $tqlist[]= $value;
            }
        }
      }
     include $this->template('web/help/'.$display);

?>
