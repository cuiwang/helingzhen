<?php



      if($_GPC['dopost']=='ajax'){
          $action = $_GPC['action'];
          $token = $_GPC['token'];
          $sql = "SELECT id FROM ".tablename(GARCIA_PREFIX."member")." where weid=".$_GPC['weid']." and openid='".$_GPC['openid']."' ";
          $_member = pdo_fetchcolumn($sql);
          $data = array(
              'token'=>$token,
              'upbdate'=>date('Y-m-d H:i:s',time()),
              'action'=>$action,
          );
          if($action=='cannel'){
               pdo_update(GARCIA_PREFIX."pc_login",array('status'=>2),array('weid'=>$this->weid,'token'=>$token));
               $data['status']=2;
          }else if($action=='success'){
              pdo_update(GARCIA_PREFIX."pc_login",array('status'=>1),array('weid'=>$this->weid,'token'=>$token));
              $data['status']=1;
          }

          die(json_encode($data));
      }
       $token = $_GPC['token'];
       pdo_update(GARCIA_PREFIX."pc_login",array('status'=>3),array('weid'=>$this->weid,'token'=>$token));
    // $token =  $this->_gtoken();
    // echo 1;
        include $this->template('web/login/oauth');
 ?>
