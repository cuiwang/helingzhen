<?php

  $pindex = max(1, intval($_GPC['page']));
  $psize = 50;


  $list = pdo_fetchall("SELECT a.openid,a.id FROM ".tablename(GARCIA_PREFIX."member")." a where a.weid=".$this->weid."  and a.is_roob=0 and (type=0 or type=2) and a.status=0   LIMIT ".($pindex - 1) * $psize.','.$psize);
  $lc = count($list);
  if($lc<=0){
         pdo_update(GARCIA_PREFIX."setting",array('is_trans'=>1),array('id'=>$this->sys['id']));
     message('操作完毕,正在返回',$this->createWebUrl('member'),'success');

  }
  $c = ($pindex - 1)+$lc;

  foreach ($list as $key => $value) {
      // $this->_wallet()
      $walet = 0;
      if(!empty($value['openid'])){
        $walet =  $this->_wallet($value['openid']);
      }else{
        $walet = 0;
      }
      pdo_update(GARCIA_PREFIX."member",array('wallet'=>$walet),array('id'=>$value['id']));
  }

  message("正在下一轮操作",$this->createWebUrl('trans',array('page'=>$pindex+1)),'success');
 ?>
