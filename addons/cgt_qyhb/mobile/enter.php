<?php
/*
 * Created on 2015-6-20
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 //获得用户信息，一个文件 表示一个模块，利于多人开发。
  //	$fromUserJson=json_decode($this->getFromUser(),true);
  

  $pindex=1;
  $psize=20;
  
  
   
  
  $list=pdo_fetchall("select user_id,user_name,user_image,createtime from ".tablename("qyhb_user")." where uniacid=".$_W['uniacid'] .
" order by createtime desc limit ". ($pindex - 1) * $psize . ',' . $psize);
 // exit("select user_id,user_name,user_image,createtime from ".tablename("qyhb_user")." where uniacid=".$_W['uniacid'] ." limit ". ($pindex - 1) * $psize . ',' . $psize);
  include $this->template('index');