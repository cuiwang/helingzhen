<?php


$mid = $this->_gmodaluserid();

   $display = $_GPC['display'];
   $dopost =$_GPC['dopost'];


   if($dopost=='save'){
      $fid =$_GPC['fid'];

      $id = $_GPC['id'];
      $data = array(
          'province'=>$_GPC['province'],
          'city'=>$_GPC['city'],
          'area'=>$_GPC['area'],
          'name'=>$_GPC['name'],
          'tel'=>$_GPC['tel'],
          'address'=>$_GPC['address'],
          'youbian'=>$_GPC['youbian'],
          'is_def'=>$_GPC['defaultLocation'],
          'mid'=>$mid,
          'weid'=>$this->weid,
          'upbdate'=>time()
      );
      if($_GPC['defaultLocation']==1){
         pdo_update(GARCIA_PREFIX."address",array('is_def'=>0),array('mid'=>$mid));
      }
      if($id){
         pdo_update(GARCIA_PREFIX."address",$data,array('id'=>$id));
      }
      else{
        pdo_insert(GARCIA_PREFIX."address",$data);
      }
      if(empty($fid)){
       message('操作成功',$this->createMobileUrl('address',array('display'=>'list')),'success');
      }else{
        echo "<script language='javascript'
        type='text/javascript'>";
        echo "window.location.href='".$this->createMobileUrl('pay',array('id'=>$fid))."'";
        echo "</script>";

      }

   }


   if($_GPC['edit_id']){

      $address  = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX."address")." where weid=".$this->weid." and mid=".$mid." and id=".$_GPC['edit_id']);
      // var_dump($address);
   }

  if($display=='add_list'){

      $_list = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."address")." where weid=".$this->weid." AND mid=".$mid);
  }else if($display=='list'){
  $_list = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."address")." where weid=".$this->weid." AND mid=".$mid." order by is_def desc");
  }
   include $this->template('address/'.$display);




 ?>
