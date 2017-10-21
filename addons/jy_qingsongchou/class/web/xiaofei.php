<?php


  $dopost = $_GPC['dopost'];


  if($dopost=='ajax'){
    /**
     * 前端加载用户数据
     */
    $mobile  = $_GPC['mobile'];
    $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX.'member').' where weid='.$this->weid." and mobile='".$mobile."'";
    $config = pdo_fetch($sql);

    if(!$config){

       $json = json_encode(array('status'=>0,'log'=>$config));
    }else{
      $config['nickname'] = urldecode($config['nickname']);
      $json = json_encode(array('status'=>1,'log'=>$config));
    }

    die($json);
  }else if($dopost=='excel'){
    $time= $_GPC['work2'];
    $statr = strtotime($_GPC['work2']['start']);
    $end = strtotime($_GPC['work2']['end']);
    if($_GPC['type']==0){
      message('请选择数据导出类型',referer(),'error');
    }
    if($_GPC['type']==1){
        $name = '提现数据';
        $title = array(array('name'=>'id'),array('name'=>'姓名','width'=>30),array('name'=>'提现金额'));
        $sql = "SELECT a.id,b.name,a.price FROM ".tablename(GARCIA_PREFIX."tixian")." a
        LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.uid=b.id
        where a.weid=".$this->weid." AND a.upbdate>".$statr." and a.upbdate<".$end;
    }else if($_GPC['type']==2){
      $name = '消费数据';
      $title = array(array('name'=>'id'),array('name'=>'姓名','width'=>30),array('name'=>'消费金额'),array('name'=>'消费时间','width'=>50));
      $sql = "SELECT a.id,b.name,a.jine, FROM_UNIXTIME(a.upbdate,'%Y-%m-%d %H:%i:%S') as time FROM ".tablename(GARCIA_PREFIX."fxjlist")." a
      LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.uid=b.id
      where a.weid=".$this->weid." AND a.upbdate>".$statr." and a.upbdate<".$end;
    }else if($_GPC['type']==3){
      $name = '返现数据';
      $title = array(array('name'=>'id'),array('name'=>'消费单号','width'=>30),array('name'=>'姓名','width'=>30),array('name'=>'返现金额'),array('name'=>'返现时间','width'=>50));
      $sql = "SELECT a.id,a.orderno,b.name,a.price, FROM_UNIXTIME(a.upbdate,'%Y-%m-%d %H:%i:%S') as time FROM ".tablename(GARCIA_PREFIX."flist")." a
      LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.uid=b.id
      where a.weid=".$this->weid." AND a.upbdate>".$statr." and a.upbdate<".$end;
    }

    $list = pdo_fetchall($sql);
    foreach ($list as $key => $value) {
        $i = 0;
        foreach ($value as $k => $v) {
          $data[$key][$i]= $v ;
          $i++;
        }
    }
    $this->_pushExcel($title,$data,$name);
    exit;
  }
  else if($dopost=='addxf'){
     /**
      * 添加消费
      */
      $data = array(
          'mobile'=>$_GPC['mobile'],
          'jine'=>$_GPC['jine'],
          'kids'=>$_GPC['kids'],
          'uid'=>$_GPC['uid'],
          'weid'=>$this->weid,
          'upbdate'=>time(),
          'orderno'=>date('Yd',time()).substr(rand(10000000000,time()),5,5)
      );
     pdo_Insert(GARCIA_PREFIX.'fxjlist',$data);
     message('保存成功',referer(),'success');
     exit;

  }else if($dopost=='del'){
     pdo_delete(GARCIA_PREFIX.'fxjlist',array('id'=>$_GPC['id']));
     message('删除成功',referer(),'success');
     exit;
  }else if($dopost=='compalte'){
    // 返现金额[日] = (10000*返现比率)/周期内的日数
    $id = $_GPC['id'];
    $sql = "SELECT a.*,c.kprecent,c.kmou FROM ".tablename(GARCIA_PREFIX."fxjlist")." a
    LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.uid=b.id
    LEFT JOIN ".tablename(GARCIA_PREFIX."kids")." c on a.kids=c.id
    where a.weid=".$this->weid." and a.id=".$id;

    $info = pdo_fetch($sql);
     $cur = date('Y-m-d',time());
     $_last = date("Y-m-d",strtotime("+".$info['kmou']."months",time()));
    // echo $_go = $_M+$info['kmou'];
    $_days = $this->diffDate($cur,$_last);
    $_c = round($info['jine']*$info['kprecent'],2);//总返现金额
    $_f = $_c/$_days;
    $_f = round($_f,2);//日返现金额
    $_t = $_f*($_days-1);//日期-1返现总额
    $_l = $_c-$_t;//最后一天返现金额（补足用）
    for ($i=1; $i < $_days ; $i++) {
       $data[$i] = array(
          'day'=>$i,
          'pice'=>$_f,
       );
    }
    $data[$_days] = array(
       'day'=>$_days,
       'pice'=>$_l,
    );
    $data = json_encode($data);

    $config = array(
       'weid'=>$this->weid,
       'uid'=>$info['uid'],
       'total'=>$_c,
       'upbdate'=>time(),
       'bef_date'=>0,
       'lat_date'=>strtotime(date("Y-m-d",strtotime("+1 day"))),
       'cur_day'=>0,
       'fupbdate'=>1,
       'tdate'=>$_days,
       'pjson'=>$data,
       'fid'=>$id
    );
    pdo_Insert(GARCIA_PREFIX.'fanhuan',$config);
    $inser_id = pdo_InsertId();
    if(!empty($inser_id)){
       pdo_update(GARCIA_PREFIX.'fxjlist',array('status'=>1,'fid'=>$inser_id),array('id'=>$info['id']));
    }
    message('生成数据成功',referer(),'success');
    exit;
  }else if($_GPC['dopost']=='tui'){

    // 推送操作
    $id=  $_GPC['id'];
    $ids = $_GPC['ids'];
    if(!empty($ids)){
       $ids =explode(",",$ids);
       $id = $ids[0];
    }

    $sql = "SELECT a.*,b.orderno,c.openid,c.wallet FROM ".tablename(GARCIA_PREFIX."fanhuan")." a
    LEFT JOIN ".tablename(GARCIA_PREFIX."fxjlist")." b on a.fid=b.id
    LEFT JOIN ".tablename(GARCIA_PREFIX."member")." c on a.uid=c.id
    where a.weid=".$this->weid." AND a.id=".$id;
    $_config = pdo_fetch($sql);
    $_list = json_decode($_config['pjson'],true);
    $_var_1= strtotime(date("Y-m-d",time()));//当前推送时间
    $_var_2 = strtotime(date("Y-m-d",strtotime("+1 day")));//下一期推送时间
    $_var_3 = $_config['fupbdate'];//当前需要返还期数
    $_var_4 = $_var_3;//记录已推送期数
    $_var_5 = $_var_3+1;//下期推送期数
    $_var_6 = $_list[$_var_3]['pice'];//当前需要返还金额
    $_var_9 = $_config['tdate']-$_var_4;//剩余返还期数
    $_var_10 = $_config['wallet']+$_var_6;//用户增加余额
    $_var_7 = array(
      'cur_day'=>$_var_4,
      'bef_date'=>$_var_1,
      'lat_date'=>$_var_2,
      'fupbdate'=>$_var_5,
    );//更新返还数据
    $_var_8 = array(
      'weid'=>$this->weid,
      'uid'=>$_config['uid'],
      'orderno'=>$_config['orderno'],
      'price'=>$_var_6,
      'cur_pre'=>$_var_3,
      'upbdate'=>time(),
      'fid'=>$_config['fid']
    );//返还记录
    // var_dump($_var_8);

    /**
     * 记录返现
     */
    $_log = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."flog")." where weid=".$this->weid." AND uid=".$_config['uid']." and upbdate=".$_var_1);
    if(!$_log){
       pdo_insert(GARCIA_PREFIX."flog",array('weid'=>$this->weid,"uid"=>$_config['uid'],"price"=>$_var_6,"upbdate"=>$_var_1));
    }else{
       $_v= $_log['price']+$_var_6;
       pdo_update(GARCIA_PREFIX."flog",array("price"=>$_v),array('id'=>$_log['id']));
    }

    $token =  $this->_gtoken();
    $data = array(
      'first'=>array('value'=>'返现金客服通知','color'=>'#000000'),
      'keyword1'=>array('value'=>"￥".$_var_6,'color'=>'#F50303'),
      'keyword2'=>array('value'=>'第'.$_var_4.'期现金返还已发送成功,剩余'.$_var_9.'期待返还。','color'=>'#173177'),
      'keyword3'=>array('value'=>date('Y-m-d',time()),'color'=>'#173177'),
      'remark'=>array('value'=>'','color'=>'#173177'),
    );
    $_result = $this->wapi->sendTemplate($_config['openid'],$this->sys['temp_id'],'',$token,$data);
    $_result = json_decode($_result,true);
        // pdo_insert('garcia_log',array('log'=>json_encode($_result)));
    pdo_update(GARCIA_PREFIX."fanhuan",$_var_7,array('id'=>$id));
    pdo_update(GARCIA_PREFIX."member",array('wallet'=>$_var_10),array('id'=>$_config['uid']));
    pdo_insert(GARCIA_PREFIX."flist",$_var_8);
    if($_GPC['type']==1){
        $_c = count($ids)-1;
        foreach ($ids as $k => $v) {
            if($k==0){
              continue;
            }else if($k==1){
               $h.=$v;
            }else{
               $h.=",".$v;
            }

        }
        $n = $_GPC['n']+1;
        if($_c==0){
            die(json_encode(array('status'=>1)));
        }else{
          die(json_encode(array('status'=>0,'result'=>$h,'n'=>$n)));
        }
       die();
    }else{
      message('推送成功',referer(),'success');
    }
    //

    // var_dump($_config);
    exit;
  }
$sql2 =  "SELECT count(a.id) FROM ".tablename(GARCIA_PREFIX."fxjlist")." a where a.weid=".$this->weid." order by a.id desc ";
$_result = $this->_pager($_GPC['page'],20,$sql2);
$_list = pdo_fetchall("SELECT a.*,b.headimgurl,b.name,c.kname FROM ".tablename(GARCIA_PREFIX."fxjlist")." a
left join ".tablename(GARCIA_PREFIX.'member')." b on a.uid=b.id
left join ".tablename(GARCIA_PREFIX.'kids')." c on a.kids=c.id
where a.weid=".$this->weid." order by id desc ".$_result['limit']);
$_klist  = pdo_fetchall("SELECT * FROM ".tablename(GARCIA_PREFIX."kids")." where weid=".$this->weid." ");


 if($_GPC['op']=='display'){

    $fanhuan = pdo_fetch("SELECT a.*,b.name,c.orderno FROM ".tablename(GARCIA_PREFIX."fanhuan")." a
    LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.uid=b.id
    LEFT JOIN ".tablename(GARCIA_PREFIX."fxjlist")." c on c.id=a.fid
     where a.weid=".$this->weid." and a.fid=".$_GPC['id']);
    $__flist = json_decode($fanhuan['pjson'],true);
     $_yfh = pdo_fetchcolumn('SELECT SUM(price) FROM '.tablename(GARCIA_PREFIX.'flist')." where weid=".$this->weid." AND fid=".$_GPC['id']." AND uid=".$fanhuan['uid']);
     $_yfh  = empty($_yfh)?0:$_yfh ;

 }
 /**
  * 推送返还列表
  */
  $_cur = strtotime(date('Y-m-d'));

  $sql2 =  "SELECT count(a.id) FROM ".tablename(GARCIA_PREFIX."fanhuan")." a
  LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on b.id=a.uid
  where a.weid=".$this->weid." AND a.lat_date<='".$_cur."' and a.cur_day<a.tdate";
  $_result2 = $this->_pager($_GPC['page'],20,$sql2);

  $sql = "SELECT a.*,b.headimgurl as avatar,b.mobile,b.name FROM ".tablename(GARCIA_PREFIX."fanhuan")." a
  LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on b.id=a.uid
  where a.weid=".$this->weid." AND a.lat_date<='".$_cur."' and a.cur_day<a.tdate ".$_result2['limit'];

  // $sql = "SELECT a.*,b.headimgurl as avatar,b.mobile,b.name FROM ".tablename(GARCIA_PREFIX."fanhuan")." a
  // LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on b.id=a.uid
  // where a.weid=".$this->weid."  and a.cur_day<a.tdate";
  $_flist = pdo_fetchall($sql);

 $_twallet  = pdo_fetchcolumn("SELECT sum(wallet) FROM ".tablename(GARCIA_PREFIX.'member')." where weid=".$this->weid);//用户余额
 $_txf = pdo_fetchcolumn("SELECT sum(jine) FROM ".tablename(GARCIA_PREFIX.'fxjlist')." where weid=".$this->weid);//用户余额
 $_tfx = pdo_fetchcolumn("SELECT sum(price) FROM ".tablename(GARCIA_PREFIX.'flist')." where weid=".$this->weid);//用户余额
  include $this->template('web/xiaofei');
 ?>
