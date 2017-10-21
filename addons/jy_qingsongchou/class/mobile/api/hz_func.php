<?php

    if($action=='home'){
         $_jm = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX."huzhu")." where weid=".$this->weid." and status=1"); //已加入会员
         $_jp = pdo_fetchcolumn('SELECT SUM(mmm) FROM '.tablename(GARCIA_PREFIX."huzhu_pay")." where weid=".$this->weid." and status=1"); //已有资金
         if($_jm<1000000){
            $_jf = 3;
         }else{
           $_jf = 0.3;
         }
         //今日
         $start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
         $end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
         $_upmoney = pdo_fetchcolumn('SELECT SUM(mmm) FROM '.tablename(GARCIA_PREFIX."huzhu_pay")." where weid=".$this->weid." and status=1 and upbdate>".$start." and upbdate<".$end);
         $_upmoney = round($_upmoney,2);
         $_upmember = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX."huzhu")." where weid=".$this->weid." and status=1 and upbdate>".$start." and upbdate<".$end);
         _success(array('jm'=>$_jm,'jp'=>round($_jp,2),'jf'=>$_jf,'um'=>$_upmember,'up'=>$_upmoney));
    }else{
        _fail(array('msg'=>'not found function'));
    }
 ?>
