<?php

    if($action=='yanz'){
        if(empty($_GPC['mid'])){
           _fail(array('msg'=>'请输入用户id'));
        }
        $data = array(
          'weid' =>$this->weid,
          'mid' =>$_GPC['mid'],
          'upbdate' => time(),
          'thumb'=>$_GPC['idcar']
        );
        pdo_insert(GARCIA_PREFIX."shiming",$data);
        _success(array('msg'=>'提交审核成功'));
    }
    else{
        _fail(array('msg'=>'not found function'));
    }

 ?>
