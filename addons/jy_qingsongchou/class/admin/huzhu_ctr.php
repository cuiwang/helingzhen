<?php


    if($_GPC['dopost']=='save_vir'){
       $data  = array(
         'vir_peo'=>$_GPC['vir_peo'],
         'vir_mon'=>$_GPC['vir_mon'],
         'weid'=>$this->weid
       );
       if($_GPC['id']){
           pdo_update(GARCIA_PREFIX.'huzhu_set',$data,array('id'=>$_GPC['id']));
       }else{
             pdo_insert(GARCIA_PREFIX.'huzhu_set',$data);
       }
             message('保存成功',referer(),'success');
      exit;
    }else if($_GPC['dopost']=='del_member'){
      $id = $_GPC['id'];
      pdo_delete(GARCIA_PREFIX."huzhu",array('id'=>$id));
      message('删除成功',referer(),'success');
      exit;
    }

    // 会员数
    $_members = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX."huzhu")." where weid=".$this->weid." and status=1");
    //互助资金
    $_moneys = pdo_fetchcolumn('SELECT SUM(mmm) FROM '.tablename(GARCIA_PREFIX."huzhu_pay")." where weid=".$this->weid." and status=1");
    $_moneys = number_format($_moneys,2);
    // 均摊资金
    if($_members<1000000){
       $jt = 3;
    }else{
       $jt = 0;
    }
    $list = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."huzhu")." where weid=".$this->weid);


   $display = empty($_GPC['display'])?'index':$_GPC['display'];

   if($display=='set'){
       $item = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."huzhu_set")." where weid=".$this->weid);
   }
   include $this->template('admin/huzhu_ctr/'.$display);
 ?>
