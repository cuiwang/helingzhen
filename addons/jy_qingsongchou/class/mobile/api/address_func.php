 <?php



    if($action=='addresslist'){
      if(empty($_GPC['mid'])){
         _fail(array('msg'=>'请输入用户id'));
      }

            $_list = pdo_fetchall('SELECT id,province,city,area,address,name,tel,is_def FROM '.tablename(GARCIA_PREFIX."address")." where weid=".$this->weid." AND mid=".$_GPC['mid']);
            foreach ($_list as $key => $value) {
                  if($_list[$key]['tel']){
                     $_list[$key]['tel'] = substr($_list[$key]['tel'],0,4)."****".substr($_list[$key]['tel'],8);
                  }
            }
            _success(array('res'=>$_list));
    }
    else if($action=='add_address'){
      if(empty($_GPC['mid'])){
         _fail(array('msg'=>'请输入用户id'));
      }
      $data = array(
            'province'=>$_GPC['province'],
            'city'=>$_GPC['city'],
            'area'=>$_GPC['area'],
            'address'=>$_GPC['address'],
            'name'=>$_GPC['name'],
            'tel'=>$_GPC['tel'],
            'is_def'=>$_GPC['is_def'],
            'mid'=>$_GPC['mid'],
            'youbian'=>$_GPC['youbian'],
            'weid'=>$this->weid,
            'upbdate'=>time()
      );
      if($_GPC['is_def']==1){
         pdo_update(GARCIA_PREFIX."address",array('is_def'=>0),array('mid'=>$_GPC['mid']));
      }
      if($_GPC['id']){
         pdo_update(GARCIA_PREFIX."address",$data,array('id'=>$_GPC['id']));
         _success(array('msg'=>'保存成功','id'=>$_GPC['id'],'res'=>$data));
      }
      else{
        pdo_insert(GARCIA_PREFIX."address",$data);
           _success(array('msg'=>'保存成功','id'=>pdo_insertid(),'res'=>$data));
      }

    }
    else if($action=='is_def'){
      if(empty($_GPC['mid'])){
         _fail(array('msg'=>'请输入用户id'));
      }
      pdo_update(GARCIA_PREFIX."address",array('is_def'=>0),array('mid'=>$_GPC['mid']));
      $id = $_GPC['id'];

      pdo_update(GARCIA_PREFIX."address",array('is_def'=>1),array('mid'=>$_GPC['mid'],'id'=>$id));
         _success(array('msg'=>'设置成功','res'=>1));
    }else if($action=='del_address'){
      if(empty($_GPC['id'])){
         _fail(array('msg'=>'请输入id'));
      }

      pdo_delete(GARCIA_PREFIX."address",array('id'=>$_GPC['id']));
      _success(array('msg'=>'删除成功','res'=>1));
    }
    else{
        _fail(array('msg'=>'not found function'));
    }
 ?>
