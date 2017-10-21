<?php




if($_GPC['dopost']=='save_fabu'){
  $kuaidi  = $_GPC['kuaidi'];
  $kuai_order = $_GPC['kuai_order'];
  $fahuo_time = strtotime(str_replace('T',' ',$_GPC['fahuo_time']));
  if($kuaidi==1||empty($kuaidi)){
    message('请选择快递公司',referer(),'error');
  }
  if(empty($kuai_order)){
    message('请输入订单号',referer(),'error');
  }
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
  message('发货成功',$this->createWebUrl('fahuo_ctr'),'success');
  exit;
}
 // $type =
 $display = empty($_GPC['display'])?'index':$_GPC['display'];
 $type  = empty($_GPC['type'])?'0':$_GPC['type'];
 if($display=='index'){
   $pindex = max(1, intval($_GPC['page']));
   $psize = 20;
    $total = pdo_fetchcolumn('SELECT count(a.id)  FROM '.tablename(GARCIA_PREFIX."paylog")." a
            LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
            LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." c on a.fid=c.id
            LEFT JOIN ".tablename(GARCIA_PREFIX."project")." d on c.pid = d.id
             LEFT JOIN ".tablename(GARCIA_PREFIX."address")." e on e.id = a.address_id
            where a.weid=".$this->weid."  and a.status=1 and a.fahuo='".$type."' and d.is_goods=1  ");
if($type==2){
  $_list = pdo_fetchall('SELECT a.id,a.fid,a.reid,f.status,b.nickname,b.headimgurl as avatar2,e.name as trname,e.province,e.city,e.address,e.tel,c.reward  FROM '.tablename(GARCIA_PREFIX."paylog")." a
            LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
            LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." c on a.fid=c.id
            LEFT JOIN ".tablename(GARCIA_PREFIX."project")." d on c.pid = d.id
            LEFT JOIN ".tablename(GARCIA_PREFIX."address")." e on e.id = a.address_id
            LEFT JOIN ".tablename(GARCIA_PREFIX."fahuo")." f on a.id = f.pid
            where a.weid=".$this->weid."  and a.status=1 and d.is_goods=1 and f.status='1'  order by a.fahuo desc  LIMIT ".($pindex - 1) * $psize.','.$psize);
}else if($type==1){
  $_list = pdo_fetchall('SELECT a.*,b.nickname,b.headimgurl as avatar2,e.name as trname,e.province,e.city,e.address,e.tel,c.reward  FROM '.tablename(GARCIA_PREFIX."paylog")." a
           LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
           LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." c on a.fid=c.id
           LEFT JOIN ".tablename(GARCIA_PREFIX."project")." d on c.pid = d.id
            LEFT JOIN ".tablename(GARCIA_PREFIX."address")." e on e.id = a.address_id
            LEFT JOIN ".tablename(GARCIA_PREFIX."fahuo")." f on a.id = f.pid
           where a.weid=".$this->weid."  and a.status=1 and d.is_goods=1 and a.fahuo='".$type."' and (f.status=0 or f.status='') order by a.fahuo desc  LIMIT ".($pindex - 1) * $psize.','.$psize);
}
else{
  $_list = pdo_fetchall('SELECT a.*,b.nickname,b.headimgurl as avatar2,e.name as trname,e.province,e.city,e.address,e.tel,c.reward  FROM '.tablename(GARCIA_PREFIX."paylog")." a
           LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
           LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." c on a.fid=c.id
           LEFT JOIN ".tablename(GARCIA_PREFIX."project")." d on c.pid = d.id
            LEFT JOIN ".tablename(GARCIA_PREFIX."address")." e on e.id = a.address_id
           where a.weid=".$this->weid."  and a.status=1 and d.is_goods=1 and a.fahuo='".$type."'  order by a.fahuo desc  LIMIT ".($pindex - 1) * $psize.','.$psize);
}

   $pager = pagination($total, $pindex, $psize);
 }else if($display=='fahuo'){
   $_kuaidi = array('申通' ,'圆通' ,'中通' ,'汇通' ,'韵达' ,'顺丰' ,'ems' ,'天天','宅急送','邮政' ,'德邦','全峰');
   $item = pdo_fetch('SELECT a.*,b.name FROM '.tablename(GARCIA_PREFIX."paylog")." a
   LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." b on b.id=a.fid
   where a.weid=".$this->weid." and a.id=".$_GPC['id']);

   if($_GPC['type']==1){
     $fitem = pdo_fetch('SELECT kuaidi,kuai_order,fahuo_time FROM '.tablename(GARCIA_PREFIX."fahuo")." where weid=".$this->weid." and pid=".$item['id']);
   }
   if(empty($item['address_id'])){
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
 }


 if($_GPC['dopost']=='Toexcel'){
   if($type==2){
     $_list = pdo_fetchall('SELECT a.id,a.fid,a.reid,f.status,b.nickname,b.headimgurl as avatar2,e.name as trname,e.province,e.city,e.address,e.tel,c.reward  FROM '.tablename(GARCIA_PREFIX."paylog")." a
               LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
               LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." c on a.fid=c.id
               LEFT JOIN ".tablename(GARCIA_PREFIX."project")." d on c.pid = d.id
               LEFT JOIN ".tablename(GARCIA_PREFIX."address")." e on e.id = a.address_id
               LEFT JOIN ".tablename(GARCIA_PREFIX."fahuo")." f on a.id = f.pid
               where a.weid=".$this->weid."  and a.status=1 and d.is_goods=1 and f.status='1'  order by a.fahuo desc  LIMIT ".($pindex - 1) * $psize.','.$psize);
   }else{
     $_list = pdo_fetchall('SELECT a.*,e.name as trname,e.province,e.city,e.address,e.tel,c.reward,f.kuaidi,f.kuai_order  FROM '.tablename(GARCIA_PREFIX."paylog")." a
              LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
              LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." c on a.fid=c.id
              LEFT JOIN ".tablename(GARCIA_PREFIX."project")." d on c.pid = d.id
               LEFT JOIN ".tablename(GARCIA_PREFIX."address")." e on e.id = a.address_id
               LEFT JOIN ".tablename(GARCIA_PREFIX."fahuo")." f on a.id = f.pid
              where a.weid=".$this->weid."  and a.status=1 and d.is_goods=1 and a.fahuo='".$type."'  order by a.fahuo desc   ");
   }

      $title = array(
          array(
            'name'=>'id',
            'width'=>10,
          ),
          array(
            'name'=>'产品',
            'width'=>20,
          ),
          array(
            'name'=>'数量',
            'width'=>10,
          ),
          array(
            'name'=>'姓名',
            'width'=>10,
          ),
          array(
            'name'=>'电话',
            'width'=>10,
          ),
          array(
            'name'=>'地址',
            'width'=>30,
          ),
          array(
            'name'=>'状态',
            'width'=>30,
          ),
      );

    if($type!=0){
       $title[7] = array(
         'name'=>'快递单号',
         'width'=>30,
       );
       $title[8] = array(
         'name'=>'快递',
         'width'=>30,
       );
    }
   foreach ($_list as $key => $value) {
       $_conf[$key]['id']= $value['id'];
       $_conf[$key]['reward']= _reward($value['reid'],$value['reward'],1);
       $_conf[$key]['count'] = max(1,$value['count']);
       $_conf[$key]['trname'] = $value['trname'];
       $_conf[$key]['tel'] = $value['tel'];
       $_conf[$key]['address'] = $value['province']." ".$value['city']." ".$value['address'];
       if($type==2){
         $_conf[$key]['status'] =  '已完成';
       }else{
         $_conf[$key]['status'] =  $value['fahuo']==0?'待发货':($value['fahuo']==1?'已发货':'已完成');
       }

         if($type!=0){
            $_conf[$key]['kuai_order'] = $value['kuai_order'];
            $_conf[$key]['kuaidi'] = $value['kuaidi'];
         }
   }
   $name = '发货管理';
      $this->_pushExcel($title,$_conf,$name);
 }else if($_GPC['dopost']=='del_fahuo'){
      pdo_update(GARCIA_PREFIX."paylog",array('status'=>9),array('id'=>$_GPC['id']));
      message('删除成功',referer(),'success');
   exit;
 }else if($_GPC['dopost']=='del_arr'){
   $ids = implode(',',$_GPC['del']);
     pdo_query("UPDATE ".tablename(GARCIA_PREFIX.'paylog')." SET status =9 WHERE id in (".$ids.")");
     message('删除成功',referer(),'success');
   exit;
 }


  include $this->template('admin/fahuo/'.$display);

  function _reward($id,$reward,$is){

    $reward = json_decode($reward,true);
     $reward = $reward[$id]['supportContent'];
    if(empty($reward)){
      if($is==1){
        return '未知';
      }else{
        return '<font color="red">未知回报/或已被删除</font>';
      }

    }else{
      return $reward;
    }
  }
 ?>
