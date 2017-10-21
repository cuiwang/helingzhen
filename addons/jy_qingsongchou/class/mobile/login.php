<?php



        if($_GPC['dopost']=='ajax'){
           $token = $_GPC['token'];

           $_id = pdo_fetchcolumn('SELECT id FROM '.tablename(GARCIA_PREFIX."pc_login")." where weid=".$this->weid." and token='".$token."'");
           $_status = pdo_fetchcolumn('SELECT status FROM '.tablename(GARCIA_PREFIX."pc_login")." where weid=".$this->weid." and token='".$token."'");
           $data = array(
             'code'=>2,
             'token'=>$token,
             'id'=>$_id,
             'status'=>$_status
           );
           die(json_encode($data));
          exit;
        }
          $token = $this->cookies->get('token');
          if(empty($token)){
             $token = sha1(GARCIA_MD5.time().(15*60));
              $this->cookies->set('token',$token,10);
              pdo_insert(GARCIA_PREFIX."pc_login",array('upbdate'=>time(),'token'=>$token,'weid'=>$this->weid));
          }

        // include $this->template('web/login/index');
          $this->h5t('login/index');
        // include  $this->template('bad');

 ?>
