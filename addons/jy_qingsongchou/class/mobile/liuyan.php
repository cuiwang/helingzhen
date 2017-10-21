<?php
    $mid = $this->_gmodaluserid();
          $display  = 'liuyan';
          $dopost = $_GPC['dopost'];
          if($dopost=='save'){
              $data = array(
                  'weid'=> $this->weid,
                  'tel'=> $_GPC['tel'],
                  'email'=> $_GPC['email'],
                  'content'=> $_GPC['content'],
                  'upbdate'=> time(),
                  'mid'=>$mid
              );
              if(empty($_GPC['tel'])){
                  message('电话不能为空',referer(),'error');
              }else if(empty($_GPC['email'])){
                  message('邮箱不能',referer(),'error');
              }else if(empty($_GPC['content'])){
                  message('留言内容不能为空',referer(),'error');
              }else{
                pdo_insert(GARCIA_PREFIX."liuyan",$data);
                message('留言成功',$this->createMobileUrl('index'),'success');
              }

              exit;
          }
       include $this->template('liuyan/'.$display);
 ?>
