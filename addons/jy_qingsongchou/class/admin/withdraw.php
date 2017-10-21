<?php


    load()->model('mc');
    $this->_wapi();
   $display = empty($_GPC['display'])?'index':$_GPC['display'];
   $dopost = $_GPC['dopost'];
   $id= $_GPC['id'];
   if(!empty($_GPC['type'])||$_GPC['type']=='0'){
        $type = " and a.type=".$_GPC['type'];
   }else{
      $type = '';
   }
   $pindex = max(1, intval($_GPC['page']));
   $psize = 20;
  if($display=='index'){

    $total = pdo_fetchcolumn("SELECT count(a.id) as c FROM ".tablename(GARCIA_PREFIX."withdraw")." a where a.weid=".$this->weid." and a.status=0 ".$type." order by id desc");
    $pager = pagination($total, $pindex, $psize);
    $_list = pdo_fetchall("SELECT a.*,b.nickname FROM ".tablename(GARCIA_PREFIX."withdraw")." a
    LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
    where a.weid=".$this->weid." and a.status=0 ".$type." order by id desc  LIMIT ".($pindex - 1) * $psize.','.$psize);

  }else if($display=='success'){
    $total = pdo_fetchcolumn("SELECT count(a.id) as c FROM ".tablename(GARCIA_PREFIX."withdraw")." a where a.weid=".$this->weid." and a.status=1 ".$type." order by id desc");
    $pager = pagination($total, $pindex, $psize);
    $_list = pdo_fetchall("SELECT a.*,b.nickname,b.id as uid FROM ".tablename(GARCIA_PREFIX."withdraw")." a
    LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
    where a.weid=".$this->weid." and a.status=1 ".$type." order by id desc LIMIT ".($pindex - 1) * $psize.','.$psize);
  }else if($display=='error'){

    $total = pdo_fetchcolumn("SELECT count(a.id) as c FROM ".tablename(GARCIA_PREFIX."withdraw")." a where a.weid=".$this->weid." and a.status=2 ".$type." order by id desc");
    $pager = pagination($total, $pindex, $psize);
    $_list = pdo_fetchall("SELECT a.*,b.nickname,b.id as uid FROM ".tablename(GARCIA_PREFIX."withdraw")." a
    LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
    where a.weid=".$this->weid." and a.status=2 ".$type." order by id desc LIMIT ".($pindex - 1) * $psize.','.$psize);
  }


  if($dopost=='success'){

      $sql= "SELECT a.*,b.openid FROM  ".tablename(GARCIA_PREFIX."withdraw")." a
      LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
      where a.weid=".$this->weid." and a.id=".$id;
      $conf = pdo_fetch($sql);
      $fee = $conf['money'];
      $openid = $conf['openid'];
      $type= $conf['type'];
      $payid= $conf['payid'];

      if($type==0){

            $data = $this->wapi->_setComArray($this->sys['pay_number'],$openid,$fee,$this->sys['pay_com_title'],$this->sys['pay_appid'],$this->sys['ip_address']);
            $re = $this->wapi->sendComPay($data,$this->sys['pay_miyao']);
            $msg = "商户付款成功";
            if($re['result_code']=='SUCCESS'){
                pdo_update(GARCIA_PREFIX."withdraw",array('status'=>1,'upbdate'=>time()),array('id'=>$id));
                pdo_update(GARCIA_PREFIX."paylog",array('msg'=>'提现发放成功'),array('id'=>$payid));
                message($msg,referer(),'success');
            }else{
              message($re['return_msg'],referer(),'error');
            }
      }else if($type==5){
        pdo_update(GARCIA_PREFIX."withdraw",array('status'=>1,'upbdate'=>time()),array('id'=>$id));
        pdo_update(GARCIA_PREFIX."paylog",array('msg'=>'提现发放成功'),array('id'=>$payid));
        message('发放成功',referer(),'success');
      }

      exit;
  }else if($dopost=='bad'){

      $mid =  pdo_fetchcolumn('SELECT mid FROM '.tablename(GARCIA_PREFIX."withdraw")." where weid=".$this->weid." and id=".$id);
      $payid = pdo_fetchcolumn('SELECT payid FROM '.tablename(GARCIA_PREFIX."withdraw")." where weid=".$this->weid." and id=".$id);
      $money = pdo_fetchcolumn('SELECT `money` FROM '.tablename(GARCIA_PREFIX."withdraw")." where weid=".$this->weid." and id=".$id);
      pdo_update(GARCIA_PREFIX."withdraw",array('status'=>2,'upbdate'=>time()),array('id'=>$id));
      // //提现不通过原路退钱
      pdo_update(GARCIA_PREFIX."paylog",array('msg'=>'提现失败资金返还','type'=>'6'),array('id'=>$payid));
      $wallet = pdo_fetchcolumn('SELECT wallet FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$mid);
      $wallet = $wallet + $money;
      pdo_update(GARCIA_PREFIX."member",array('wallet'=>$wallet),array('id'=>$mid));
      $result = mc_credit_update($uid, 'credit2', "+".$money,array(0=>'提现失败资金返还'));
      message('操作成功',referer(),'success');
      exit;
  }else if($dopost=='Toexcel'){
            $action  = empty($_GPC['action'])?'index':$_GPC['action'];
      $type = $_GPC['type'];
      if(!empty($_GPC['type'])||$_GPC['type']=='0'){
           $type = " and a.type=".$_GPC['type'];
      }else{
         $type = '';
      }
     if($action=='index'){
       $_list = pdo_fetchall("SELECT a.*,b.nickname,b.id as uid,d.bank,d.cardNo,d.holder FROM ".tablename(GARCIA_PREFIX."withdraw")." a
       LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
       LEFT JOIN ".tablename(GARCIA_PREFIX."paylog")." c on a.payid=c.id
       LEFT JOIN ".tablename(GARCIA_PREFIX."bank")." d on c.bank_id=d.id
       where a.weid=".$this->weid." and a.status=0 ".$type." order by id desc");

        $title = array(
            array(
              'name'=>'id',
              'width'=>10,
            ),
            array(
              'name'=>'用户名',
              'width'=>10,
            ),
            array(
              'name'=>'提现金额',
              'width'=>10,
            ),
            array(
              'name'=>'提现方式',
              'width'=>10,
            ),
            array(
              'name'=>'提现时间',
              'width'=>30,
            ),
            array(
              'name'=>'状态',
              'width'=>10,
            ),
            array(
              'name'=>'银行',
              'width'=>20,
            ),
            array(
              'name'=>'银行户名',
              'width'=>20,
            ),
            array(
              'name'=>'银行卡号',
              'width'=>20,
            ),
        );
        foreach ($_list as $key => $value) {
            $data[]= array(
              'id'=>$value['id'],
              'nickname'=>urldecode($value['nickname']),
              'money'=>$value['money'],
              'type'=>$value['type']==0?'微信':'银行卡',
              'time'=>date('Y-m-d H:i:s',$value['upbdate']),
              'status'=>$value['status']==0?'未通过':'通过',
              'bank'=>_bankname($value['bank']),
              'name'=>$value['holder'],
              'carno'=>$value['cardNo'],
            );
        }
        $name=  '提现数据';
     }else if($action=='success'){
       $_list = pdo_fetchall("SELECT a.*,b.nickname,b.id as uid,d.bank,d.cardNo,d.holder FROM ".tablename(GARCIA_PREFIX."withdraw")." a
       LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
       LEFT JOIN ".tablename(GARCIA_PREFIX."paylog")." c on a.payid=c.id
       LEFT JOIN ".tablename(GARCIA_PREFIX."bank")." d on c.bank_id=d.id
       where a.weid=".$this->weid." and a.status=1 ".$type." order by id desc");
        $name=  '提现数据';
        $title = array(
            array(
              'name'=>'id',
              'width'=>10,
            ),
            array(
              'name'=>'用户名',
              'width'=>10,
            ),
            array(
              'name'=>'提现金额',
              'width'=>10,
            ),
            array(
              'name'=>'提现方式',
              'width'=>10,
            ),
            array(
              'name'=>'提现时间',
              'width'=>30,
            ),
            array(
              'name'=>'状态',
              'width'=>10,
            ),
            array(
              'name'=>'银行',
              'width'=>20,
            ),
            array(
              'name'=>'银行户名',
              'width'=>20,
            ),
            array(
              'name'=>'银行卡号',
              'width'=>20,
            ),
        );
        foreach ($_list as $key => $value) {
            $data[]= array(
              'id'=>$value['id'],
              'nickname'=>urldecode($value['nickname']),
              'money'=>$value['money'],
              'type'=>$value['type']==0?'微信':'银行卡',
              'time'=>date('Y-m-d H:i:s',$value['upbdate']),
              'status'=>'通过',
              'bank'=>_bankname($value['bank']),
              'name'=>$value['holder'],
              'carno'=>$value['cardNo'],
            );
          }
        $name=  '成功提现数据';
     }else if($action=='error'){
       $_list = pdo_fetchall("SELECT a.*,b.nickname,b.id as uid,d.bank,d.cardNo,d.holder FROM ".tablename(GARCIA_PREFIX."withdraw")." a
       LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
       LEFT JOIN ".tablename(GARCIA_PREFIX."paylog")." c on a.payid=c.id
       LEFT JOIN ".tablename(GARCIA_PREFIX."bank")." d on c.bank_id=d.id
       where a.weid=".$this->weid." and a.status=2 ".$type." order by id desc");
        $name=  '提现数据';
        $title = array(
            array(
              'name'=>'id',
              'width'=>10,
            ),
            array(
              'name'=>'用户名',
              'width'=>10,
            ),
            array(
              'name'=>'提现金额',
              'width'=>10,
            ),
            array(
              'name'=>'提现方式',
              'width'=>10,
            ),
            array(
              'name'=>'提现时间',
              'width'=>30,
            ),
            array(
              'name'=>'状态',
              'width'=>10,
            ),
            array(
              'name'=>'银行',
              'width'=>20,
            ),
            array(
              'name'=>'银行户名',
              'width'=>20,
            ),
            array(
              'name'=>'银行卡号',
              'width'=>20,
            ),
        );
        foreach ($_list as $key => $value) {
            $data[]= array(
              'id'=>$value['id'],
              'nickname'=>urldecode($value['nickname']),
              'money'=>$value['money'],
              'type'=>$value['type']==0?'微信':'银行卡',
              'time'=>date('Y-m-d H:i:s',$value['upbdate']),
              'status'=>'未通过',
              'bank'=>_bankname($value['bank']),
              'name'=>$value['holder'],
              'carno'=>$value['cardNo'],
            );
       $name=  '失败提现数据';
     }
   }
      $this->_pushExcel($title,$data,$name);
     exit;
  }

  include $this->template('admin/withdraw/'.$display);

  function _bankname($code){
      $_banks = array(
          'ccb'=>'建设银行',
          'icbc'=>'工商银行',
          'abchina'=>'农业银行',
          'bankcomm'=>'交通银行',
          'boc'=>'中国银行',
          'psbc'=>'邮政银行',
          'cebbank'=>'光大银行',
          'cmbchina'=>'招商银行',
          'ecitic'=>'中信银行',
          'cmbc'=>'民生银行',
          'cib'=>'兴业银行',
          'cgb'=>'广发银行',
          'spdb'=>'浦发银行',
          'spabank'=>'平安银行',
      );
      return $_banks[$code];
  }
 ?>
