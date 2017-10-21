<?php

   global $_W, $_GPC;  
   $settings=$this->module['config'];  
   load()->func('tpl');
   $op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
   $uniacid=$_W["uniacid"];
   $id=$_GPC['id'];
  	
 
 
   echo "<br>报名链接:".murl('entry', array('m' => $this->module['name'], 'do' => 'enter', 'id' => $id,'form'=>'login'),true,true);
   echo "<br>候选名单链接:".murl('entry', array('m' => $this->module['name'], 'do' => 'enter', 'id' => $id,'form'=>'user'),true,true);
      echo "<br>抽奖码链接:".murl('entry', array('m' => $this->module['name'], 'do' => 'enter', 'id' => $id,'form'=>'result'),true,true);
       echo "<br>核销链接:".murl('entry', array('m' => $this->module['name'], 'do' => 'is_hx', 'id' => $id),true,true);
      echo "<br>排行榜链接:".murl('entry', array('m' => $this->module['name'], 'do' => 'ranking', 'id' => $id),true,true);   
      