<?php

      if($action=='bank_list'){
        if(empty($_GPC['mid'])){
           _fail(array('msg'=>'请输入用户id'));
        }
        $_list = pdo_fetchall('SELECT id,bank,cardNo FROM '.tablename(GARCIA_PREFIX.'bank')." where weid=".$this->weid." and mid=".$_GPC['mid']);
        foreach ($_list as $k => $v) {
            if($v['cardNo']){
                $_list[$k]['cardNo']= substr($v['cardNo'],strlen($v['cardNo'])-4);
            }
        }
        _success(array('res'=>$_list));
      }
      else if($action=='add_bank'){
        if(empty($_GPC['mid'])){
           _fail(array('msg'=>'请输入用户id'));
        }
        $bank = $_GPC['bank'];
        $holder= $_GPC['holder'];
        $cardNo= $_GPC['cardNo'];
        // $mid
        $_conf = array(
          'bank'=>$bank,
          'holder'=>$holder,
          'cardNo'=>$cardNo,
          'weid'=>$this->weid,
          'mid'=>$_GPC['mid'],
          'type'=>1,
          'upbdate'=>time(),
        );
        if(!empty($_GPC['id'])){
          pdo_update(GARCIA_PREFIX.'bank',$_conf,array('id'=>$_GPC['id']));
        }else{
          pdo_insert(GARCIA_PREFIX.'bank',$_conf);
        }
          _success(array('msg'=>'保存成功'));
      }
      else{
          _fail(array('msg'=>'not found function'));
      }
 ?>
