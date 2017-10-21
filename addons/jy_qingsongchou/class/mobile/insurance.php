<?php
    $display = empty($_GPC['display'])?'index':$_GPC['display'];
    $dopost = $_GPC['dopost'];
    $openid = $this->memberinfo['openid'];
    $platrrom = $this->_gplatfromuser($openid);
    $uid = $platrrom['uid'];
    $mid = $this->_gmodaluserid();
    if($display=='index'){
        $question = pdo_fetchcolumn('SELECT hz_question FROM '.tablename(GARCIA_PREFIX."setting")." where weid=".$this->weid);
        $question = json_decode($question,true);
        $vir =  pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."huzhu_set")." where weid=".$this->weid);

        foreach ($question as $key => $value) {
             $_temp[$value['rank']] = $value;
        }
            ksort($_temp);
        $question = '';
        $i = 0;
        foreach ($_temp as $k => $v) {
            $question[$i]= $v;
            $i++;
        }
        // 会员数
        $_members = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX."huzhu")." where weid=".$this->weid." and status=1");
        if($vir['vir_peo']){
             $_members = $_members + $vir['vir_peo'];
        }
        //互助资金
        $_moneys = pdo_fetchcolumn('SELECT SUM(mmm) FROM '.tablename(GARCIA_PREFIX."huzhu_pay")." where weid=".$this->weid." and status=1");
        $_moneys = number_format($_moneys,2);
        if($vir['vir_mon']){
             $_moneys = $_members + $vir['vir_mon'];
        }
        // 均摊资金
        if($_members<1000000){
           $jt = 3;
        }else{
          $jt = 0.3;
        }
         $is_join  = pdo_fetchcolumn('SELECT id FROM '.tablename(GARCIA_PREFIX."huzhu")." where weid=".$this->weid." and uid=".$uid);
         $t = time();
         $start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
         $end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
          $_upmoney = pdo_fetchcolumn('SELECT SUM(mmm) FROM '.tablename(GARCIA_PREFIX."huzhu_pay")." where weid=".$this->weid." and status=1 and upbdate>".$start." and upbdate<".$end);
          $_upmoney = number_format($_upmoney,2);
          $_upmember = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX."huzhu")." where weid=".$this->weid." and status=1 and upbdate>".$start." and upbdate<".$end);
    }else if($display=='join'){
        $_slist = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."huzhu")." where weid=".$this->weid." and status=0  and openid='".$openid."' group by idcar order by id desc");
                $vir =  pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."huzhu_set")." where weid=".$this->weid);
        if(!empty($_slist)){
           $_ispay = true;
        }else{
          $_ispay = false;
        }
          $_slist2 = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."huzhu")." where weid=".$this->weid." and status=1 and openid='".$openid."' group by idcar order by id desc");
          if(!empty($_slist2)){
             $_ispay2 = true;
          }else{
            $_ispay2 = false;
          }
          // 会员数
          $_members = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX."huzhu")." where weid=".$this->weid." and status=1 ");
          if($vir['vir_peo']){
               $_members = $_members + $vir['vir_peo'];
          }
          //互助资金
                  $_moneys = pdo_fetchcolumn('SELECT SUM(mmm) FROM '.tablename(GARCIA_PREFIX."huzhu_pay")." where weid=".$this->weid." and status=1");
          $_moneys = number_format($_moneys,2);
          if($vir['vir_mon']){
               $_moneys = $_members + $vir['vir_mon'];
          }
          // 均摊资金
          if($_members<1000000){
             $jt = 3;
          }else{
            $jt = 0.3;
          }
        // var_dump($_slist);
        // echo ;
    }else if($display=='info'){
       $item  = pdo_fetch('SELECT * from '.tablename(GARCIA_PREFIX."huzhu")." where weid=".$this->weid." and id=".$_GPC['id']." and openid='".$openid."'");
       $maxd = floor(($item['maxday']-time())/86400);
       $maxd = $maxd+1;
       //二次判断
       $cttt  = floor(($item['maxday']-$item['upbdate'])/86400);
       if($item['maxday']<=$item['upbdate']){
         if($item['age']>=18&&$item['age']<=39){
             $maxd = date('Y-m-d',strtotime('+180 day',$item['upbdate']));
         }else if($item['age']>=40&&$item['age']<=60){
               $maxd = date('Y-m-d',strtotime('+360 day',$item['upbdate']));
         }else{
              $maxd = date('Y-m-d',strtotime('+360 day',$item['upbdate']));
         }
            $maxd = strtotime($maxd);
             pdo_update(GARCIA_PREFIX."huzhu",array('maxday'=>$maxd),array('id'=>$_GPC['id']));
              $maxd = floor(($maxd-time())/86400);
              $maxd = $maxd+1;
       }

       if($maxd<=0){
          $itemp['hid'] = 1;
          pdo_update(GARCIA_PREFIX."huzhu",array('hid'=>1),array('id'=>$_GPC['id']));
       }
       if($item['moneys']<=0){
           pdo_update(GARCIA_PREFIX."huzhu",array('hid'=>2),array('id'=>$_GPC['id']));
       }
    }else if($display=='bill'){
        $list = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."huzhu_pay")." where weid=".$this->weid." and status = 1 and  ids=".$_GPC['id']);
        $weekarray=array("周日","周一","周二","周三","周四","周五","周六");
        // var_dump($list);
    }

  if($dopost=='save_peo'){
    $max = pdo_fetchcolumn('SELECT max(id) FROM '.tablename(GARCIA_PREFIX."huzhu")." where weid=".$this->weid."");
    $max = $max+1;
    $number = sprintf("%010d", $max);

    $key = 'fb09596fa207c7a799f97b9665e284b6';
    $url = 'http://apis.baidu.com/chazhao/idcard/idcard?idcard='.$_GPC['idcard'];
    $header = array('apikey: '.$key,);
    $id_info = $this->wapi->https_url($url,false,false,true,$header);
    $id_info = json_decode($id_info,true);

    //重复身份证
    $id = pdo_fetchcolumn('SELECT id FROM '.tablename(GARCIA_PREFIX."huzhu")." where weid=".$this->weid." and idcar='".$_GPC['idcard']."'");
    if($id){
       die(json_encode(array('status'=>0,'msg'=>'身份证已注册')));
    }
    if($id_info['error']==-1){
         die(json_encode(array('status'=>0,'msg'=>$id_info['msg'])));
    }else{
        // die(json_encode($id_info));
        $birthday = $id_info['data']['birthday'];
        $age = date('Y', time()) - date('Y', strtotime($birthday)) - 1;
        if (date('m', time()) == date('m', strtotime($birthday))){
          if (date('d', time()) > date('d', strtotime($birthday))){
              $age++;
          }
        }elseif (date('m', time()) > date('m', strtotime($birthday))){
            $age++;
        }
        if($age<18||$age>60){
              die(json_encode(array('status'=>0,'msg'=>'已超龄或年龄没够')));
        }
    }

    //验证身份证信息

    $data = array(
       'weid'=>$this->weid,
       'mid'=>$mid,
       'uid'=>$uid,
       'name'=>$_GPC['name'],
       'idcar'=>$_GPC['idcard'],
       'openid'=>$openid,
       'upbdate'=>time(),
       'status'=>0,
       'moneys'=>$this->sys['hz_money'],
       'number'=>$number,
       'age'=>$age
    );
    pdo_insert(GARCIA_PREFIX."huzhu",$data);
    $data['status']=1;

    $vLength = strlen($_GPC['idcard']);
    if ($vLength == 18)
    {
        $__idcard = substr($_GPC['idcard'], 0, 5) .'*********'. substr($_GPC['idcard'], 14, 4);
    } else {
          $__idcard = substr($_GPC['idcard'], 0, 5) .'******'. substr($_GPC['idcard'], 11, 4);
    }
    $data['idcar']=$__idcard;
    $data['status']=1;
    $data['insertid'] = pdo_insertID();
    die(json_encode($data));
    exit;
  }else if($dopost=='ajax_pay'){
    $tid=date('Ymd',TIMESTAMP).substr(time(),3)."_huzhu";
    $fee = $_GPC['fee'];
    $ids = $_GPC['ids'];
    $ids = explode(',',$ids);
    $params = array(
      'tid' => $tid,      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
      'ordersn' => 1,  //收银台中显示的订单号
      'title' => $tid,          //收银台中显示的标题
      'fee' => $fee,      //收银台中显示需要支付的金额,只能大于 0
      'user' =>'',     //付款用户, 付款的用户名(选填项)
    );
    $params=$this->pay($params);
    $_fee = $fee/count($ids);
    $params = base64_encode(json_encode($params));
    foreach ($ids as $key => $value) {
        $data = array(
            'weid'=>$this->weid,
            'ids'=>$value,
            'upbdate'=>time(),
            'tid'=>$tid,
            'status'=>0,
            'hid'=>0,
            'mmm'=>$_fee
        );
        pdo_insert(GARCIA_PREFIX."huzhu_pay",$data);
    }

    die(json_encode(array('status'=>1,'info'=>$params)));
  }else if($dopost=='save_charge'){
    $tid=date('Ymd',TIMESTAMP).substr(time(),3)."_chargehuzhu";
    $fee = $_GPC['fee'];
    $id= $_GPC['id'];
    $params = array(
      'tid' => $tid,      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
      'ordersn' => 1,  //收银台中显示的订单号
      'title' => $tid,          //收银台中显示的标题
      'fee' => $fee,      //收银台中显示需要支付的金额,只能大于 0
      'user' =>'',     //付款用户, 付款的用户名(选填项)
    );
    $params=$this->pay($params);

    $params = base64_encode(json_encode($params));

        $data = array(
            'weid'=>$this->weid,
            'ids'=>$id,
            'upbdate'=>time(),
            'tid'=>$tid,
            'status'=>0,
            'hid'=>1,
            'mmm'=>$fee
        );
        pdo_insert(GARCIA_PREFIX."huzhu_pay",$data);


    die(json_encode(array('status'=>1,'info'=>$params)));
  }

    include  $this->template('insurance/'.$display);
 ?>
