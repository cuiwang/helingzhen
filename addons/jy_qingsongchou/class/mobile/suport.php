<?php


  $id = $_GPC['id'];
  $display = empty($_GPC['display'])?'index':$_GPC['display'];

  $_kuaidi = array(
    '申通' ,
  	'圆通' ,
  	'中通' ,
  	'汇通' ,
  	'韵达' ,
  	'顺丰' ,
  	'ems' ,
  	'天天',
  	'宅急送',
  	'邮政' ,
  	'德邦',
  	'全峰' ,
  );
  // '申通' => 'shentong',
  // '圆通' => 'yuantong',
  // '中通' => 'zhongtong',
  // '汇通' => 'huitongkuaidi',
  // '韵达' => 'yunda',
  // '顺丰' => 'shunfeng',
  // 'ems' => 'ems',
  // '天天' => 'tiantian',
  // '宅急送' => 'zhaijisong',
  // '邮政' => 'youzhengguonei',
  // '德邦' => 'debangwuliu',
  // '全峰' => 'quanfengkuaidi'


  if($_GPC['dopost']=='save_fahuo'){

    $kuaidi  = $_GPC['kuaidi'];
    $kuai_order = $_GPC['kuai_order'];
    $fahuo_time = strtotime(str_replace('T',' ',$_GPC['fahuo_time']));
    $address_id = $_GPC['address_id'];
    $data = array(
      'weid'=>$this->weid,
      'kuaidi'=>$kuaidi,
      'kuai_order'=>$kuai_order,
      'fahuo_time'=>$fahuo_time,
      'address_id'=>$address_id,
      'status'=>0,
      'upbdate'=>time(),
      'reid'=>$_GPC['reid'],
      'fid' =>$_GPC['fid'],
      'pid'=>$_GPC['pid'],
      'mid'=>$_GPC['mid'],
    );
    // var_dump($data);
    pdo_insert(GARCIA_PREFIX."fahuo",$data);
    pdo_update(GARCIA_PREFIX."paylog",array('fahuo'=>1),array('id'=>$_GPC['pid']));
    message('发货成功',$this->createMobileUrl('suport',array('id'=>$_GPC['fid'])),'success');
    exit;
  }else if($_GPC['dopost']=='done_kuaidi'){
      $id = $_GPC['id'];
      $pid = $_GPC['pid'];

      pdo_update(GARCIA_PREFIX."fahuo",array('status'=>1),array('id'=>$id));
      pdo_update(GARCIA_PREFIX."paylog",array('fahuo'=>2),array('id'=>$pid));
      message('确认成功',$this->createMobileUrl('member',array('display'=>'kuaidi')),'success');
    exit;
  }


  if($display=='index'){

    $mid = $this->_gmodaluserid();

    $_count = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$_GPC['id']." and status=1");
    $_sum = pdo_fetchcolumn('SELECT sum(fee) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$_GPC['id']." and status=1");
    $_views = pdo_fetchcolumn('SELECT views FROM '.tablename(GARCIA_PREFIX.'fabu')." where weid=".$this->weid." and id=".$_GPC['id']);
    $pid = pdo_fetchcolumn('SELECT pid FROM '.tablename(GARCIA_PREFIX.'fabu')." where weid=".$this->weid." and id=".$_GPC['id']);
    $is_goods = pdo_fetchcolumn("SELECT is_goods FROM ".tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and id=".$pid);

     if($is_goods==1){
         $status = $_GPC['status'];
         if(empty($status)&&$status!='0'){
            $status = '';
         }else{
             $status = ' and a.fahuo='.$status;
         }
        //  echo $status;
       $_goos_list = pdo_fetchall('SELECT a.*,b.nickname,b.headimgurl as avatar2  FROM '.tablename(GARCIA_PREFIX."paylog")." a
       LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
       where a.weid=".$this->weid."  and a.status=1 and a.fid=".$id .$status." order by fahuo desc , id desc ");
       $reward = pdo_fetchcolumn('SELECT reward FROM '.tablename(GARCIA_PREFIX."fabu").' where weid='.$this->weid." and id=".$id."  ");
       $reward = json_decode($reward,true);

     }else{
         $_list = pdo_fetchall('SELECT a.*,b.nickname,b.headimgurl as avatar2 FROM '.tablename(GARCIA_PREFIX."paylog")." a
         LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
         where a.weid=".$this->weid."  and a.status=1 and a.fid=".$id." order by id desc");
    }
  }else if($display=='fahuo'){
      $item = pdo_fetch('SELECT a.* FROM '.tablename(GARCIA_PREFIX."paylog")." a where a.weid=".$this->weid." and a.id=".$id);
      if(empty($item['address_id'])){
         //获取用户默认收获地址
        //  echo $item['mid'];
         $de_address = pdo_fetchcolumn('SELECT id FROM '.tablename(GARCIA_PREFIX."address").' where weid='.$this->weid." and mid=".$item['mid']." and is_def=1");
         if(empty($de_address)){
            $this->_TplHtml('用户没有填写地址或没有默认地址，请联系用户填写后发货！',referer(),'error');
         }
         $addrss_id = $de_address;
      }else{
          $addrss_id  = $item['address_id'];
      }
      $reward = pdo_fetchcolumn('SELECT reward FROM '.tablename(GARCIA_PREFIX."fabu").' where weid='.$this->weid." and id=".$item['fid']);
      $reward = json_decode($reward,true);
      $supportContent= $reward[$item['reid']]['supportContent'];
      $supportNumber = $reward[$item['reid']]['supportNumber'];
      $address  = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."address")." where weid=".$this->weid." and id=".$addrss_id);
      // var_dump();
  }



  include $this->template('suport/'.$display);
 ?>
