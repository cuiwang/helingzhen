<?php

    if($action=='saerch'){
        $id = $_GPC['id'];
        if(!$id){
           _fail(array('msg'=>'没有ID'));
        }
        $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."fahuo")." where weid=".$this->weid." and id=".$id;
        $conf = pdo_fetch($sql);
        $code =  $this->_kuaidi($conf['kuaidi']);
        $rand = rand();
         $url = "http://www.kuaidi100.com/query?type=".$code."&postid=".$conf['kuai_order'];
       $dat =$this->wapi->https_url($url);
       $dat = json_decode($dat,true);
        _success(array('res'=>$dat));

    }

    else{
        _fail(array('msg'=>'not found function'));
    }

 ?>
