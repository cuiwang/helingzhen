<?php


if($this->sys['is_h5']&&$this->modal == 'phone'){
  $mid = json_decode($this->cookies->get('userDatas'),true);
  $mid = empty($mid)?$_GPC['mid']:$mid;
}else{
  $mid = $this->_gmodaluserid();
  $mid = empty($mid)?$_GPC['mid']:$mid;
}
$mappings = array(
  '申通' => 'shentong',
  '圆通' => 'yuantong',
  '中通' => 'zhongtong',
  '汇通' => 'huitongkuaidi',
  '韵达' => 'yunda',
  '顺丰' => 'shunfeng',
  'ems' => 'ems',
  '天天' => 'tiantian',
  '宅急送' => 'zhaijisong',
  '邮政' => 'youzhengguonei',
  '德邦' => 'debangwuliu',
  '全峰' => 'quanfengkuaidi'
);
$images = array(
  'shentong' => 'http://cdn.kuaidi100.com/images/all/st_logo.gif',
  'yuantong' => 'http://cdn.kuaidi100.com/images/all/yt_logo.gif',
  'zhongtong' => 'http://cdn.kuaidi100.com/images/all/zt_logo.gif',
  'huitongkuaidi' => 'http://cdn.kuaidi100.com/images/all/htky_logo.gif',
  'yunda' => 'http://cdn.kuaidi100.com/images/all/yd_logo.gif',
  'shunfeng' => 'http://cdn.kuaidi100.com/images/all/sf_logo.gif',
  'ems' => 'http://cdn.kuaidi100.com/images/all/ems_logo.gif',
  'tiantian' => 'http://cdn.kuaidi100.com/images/all/tt_logo.gif',
  'zhaijisong' => 'http://cdn.kuaidi100.com/images/all/zjs_logo.gif',
  'youzhengguonei' => 'http://cdn.kuaidi100.com/images/all/yzgn_logo.gif',
  'debangwuliu' => 'http://cdn.kuaidi100.com/images/all/dbwl_logo.gif',
  'quanfengkuaidi' => 'http://cdn.kuaidi100.com/images/all/qfkd_logo.gif'
) ;


  load()->model('mc');
  include GARCIA_TAOBAO."TopSdk.php";

    $display=empty($_GPC['display'])?'member':$_GPC['display'];
   $dopost = $_GPC['dopost'];


   if($dopost=='upavatar'){
     $imgurl = $_GPC['imgurl'];
     pdo_update(GARCIA_PREFIX."member",array('headimgurl'=>$imgurl),array('id'=>$_GPC['mid']));
     die(json_encode(array('msg'=>$imgurl)));

     exit;
   }
   else if($dopost=='shouhuo'){
      $id = $_GPC['id'];
      pdo_update(GARCIA_PREFIX."fahuo",array('status'=>1),array('id'=>$id));
     $this->_TplHtml('收货成功',$this->createMobileUrl('member',array('display'=>'detail')),'success');
     exit;
   }
   else if($dopost=='xnickname'){
      $nickname  = $_GPC['nickname'];
      pdo_update(GARCIA_PREFIX."member",array('nickname'=>urlencode($nickname)),array('id'=>$mid));
     $data = array('nickname'=>$nickname);
     die(json_encode($data));
   }else if($dopost=='shimingc'){

     $data = array(
       'weid' =>$this->weid,
       'mid' =>$mid,
       'real_name' =>$_GPC['real_name'],
       'cert_no' =>$_GPC['cert_no'],
       'upbdate' => time(),
       'thumb'=>$_GPC['idcar']
     );
     pdo_insert(GARCIA_PREFIX."shiming",$data);
     //通知管理员
     $openids = $this->_gManers();
     $nickname = $this->_GetMemberName($mid);
     $message =$this->sys['sitename']."系统消息:\nID[".$mid."],昵称[".$nickname."]的用户在".date('Y-m-d H:i:s')." 提交了实名审核，请到尽快到后台进行审核!";
     $this->_SendTxts($message,$openids);
     echo "<script language='javascript' type='text/javascript'>";
     echo "window.location.href='".$this->createMobileUrl('member',array('display'=>'smsucceses'))."'";
     echo "</script>";
     eixt;
   }else if($dopost=='rshouji'){

      //微信用绑定手机
       $mid = $this->_gmodaluserid();
       $sms = $_GPC['sms'];
       $_md5 = $_GPC['token'];
       $phone = $_GPC['phone'];
       $token = $_GPC['token'];
       $code  = pdo_fetchcolumn('SELECT code FROM '.tablename(GARCIA_PREFIX."msgcode")." where weid=".$this->weid." and mid=".$mid." and token='".$token."' order by id desc");
       $codeid  = pdo_fetchcolumn('SELECT id FROM '.tablename(GARCIA_PREFIX."msgcode")." where weid=".$this->weid." and mid=".$mid." and token='".$token."' order by id desc");
       if($code!=$sms){
            message('验证码不正确',referer(),'error');
       }else{
         pdo_update(GARCIA_PREFIX."member",array('tel'=>$phone,'mobile'=>$phone),array('id'=>$mid));
         pdo_update(GARCIA_PREFIX."msgcode",array('status'=>1),array('id'=>$codeid));
         $this->_TplHtml('绑定成功',$this->createMobileUrl('project'),'success');
       }
     exit;
   }else if($dopost=='withdraw'){
     $type = $_GPC['payType']==0?0:5;
     $money = $_GPC['money'];
     $token = $_GPC['token'];


     //防止刷新
     $_C = pdo_fetchcolumn('SELECT id FROM '.tablename(GARCIA_PREFIX."withdraw")." where weid=".$this->weid." and mid=".$mid." and token='".$token."'");
     if($_C){
           $this->_TplHtml('你已申请成功,请勿重复申请!',$this->createMobileUrl('member'),'error');
           exit;
     }
     $_paylog  = array(
       'weid'=>$this->weid,
       'uid'=>$uid,
       'upbdate'=>time(),
       'status'=>0,
       'fee'=>$money,
       'mid'=>$mid,
       'type'=>1,
       'msg'=>'提现资金冻结'
     );
     if($type==5){
       $_paylog['bank_id']=$_GPC['payType'];
     }

     pdo_insert(GARCIA_PREFIX."paylog",$_paylog);
     $_insertid = pdo_insertid();
     $_withdraw = array(
       'weid'=>$this->weid,
       'uid'=>$uid,
       'mid'=>$mid,
       'money'=>$money,
       'upbdate'=>time(),
       'payid'=>$_insertid,
       'type'=>$type,
       'token'=>$token
     );
     pdo_insert(GARCIA_PREFIX."withdraw",$_withdraw);
     $wallet = pdo_fetchcolumn('SELECT wallet FROM  '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$mid);
     $wallet = $wallet - $money;
     pdo_update(GARCIA_PREFIX."member",array('wallet'=>$wallet),array('id'=>$mid));

    //  $result = mc_credit_update($uid, 'credit2', "-".$money,array(0=>'提现资金冻结'));

     //客服消息
     $openids = $this->_gManers();
     $nickname = $this->_GetMemberName($mid);
     $message =$this->sys['sitename']."系统消息:\nid:[".$mid."],昵称:[".$nickname."]的用户在".date('Y-m-d H:i:s')." 进行了余额提现，请到尽快到后台进行审核!";
     $this->_SendTxts($message,$openids);
     //客服消息

       $this->_TplHtml('提现申请提交，等待管理员审核',$this->createMobileUrl('member'),'success');
     exit;
   }else if($dopost=='ajax_paylog'){
     $mid = $this->_gmodaluserid();
     $sql = "SELECT a.* FROM ".tablename(GARCIA_PREFIX."paylog")." a
       where a.weid=".$this->weid." AND a.mid=".$mid." order by a.id desc";
       $list  = pdo_fetchall($sql);
       $_conf = array();
       foreach ($list as $key => $value) {
         foreach ($value as $k => $v) {
           if($k=='id'){
              $_conf[$key]['id'] =  $v;
           }else if($k=='type'){
               $_conf[$key]['type'] =  $v;
           }
         }

         $_conf[$key]['fee'] = _fee($value['type'],$value['fee']);
         $_conf[$key]['thumb'] =  _thumb($value['fid'],$value['type'],$this->weid);
         if($_conf[$key]['type']==0){
             $_conf[$key]['desc'] = _pick($value['fid'],$this->weid,$value['type']);
         }else if($_conf[$key]['type']==1){
             $_conf[$key]['desc'] = _pick($value['id'],$this->weid,$value['type']);
         }
         else if($_conf[$key]['type']==4){
             $_conf[$key]['desc'] = _pick($value['id'],$this->weid,$value['type']);
         }
         else if($_conf[$key]['type']==6){
             $_conf[$key]['desc'] = _pick($value['id'],$this->weid,$value['type']);
         }
         else if($_conf[$key]['type']==7){
             $_conf[$key]['desc'] = _pick($value['id'],$this->weid,$value['type']);
         }
         else if($_conf[$key]['type']==8){
             $_conf[$key]['desc'] = _pick($value['id'],$this->weid,$value['type']);
         }
         else if($_conf[$key]['type']==9){
             $_conf[$key]['desc'] = _pick($value['id'],$this->weid,$value['type']);
         }
       }

       die(json_encode(array('list'=>$_conf)));
   }else if($dopost=='add_presonal'){
     $bank = $_GPC['bank'];
     $holder= $_GPC['holder'];
     $cardNo= $_GPC['cardNo'];
     // $mid
     $_conf = array(
       'bank'=>$bank,
       'holder'=>$holder,
       'cardNo'=>$cardNo,
       'weid'=>$this->weid,
       'mid'=>$mid,
       'type'=>0,
       'upbdate'=>time(),
     );
     if(!empty($_GPC['id'])){
       pdo_update(GARCIA_PREFIX.'bank',$_conf,array('id'=>$_GPC['id']));
     }else{
       pdo_insert(GARCIA_PREFIX.'bank',$_conf);
     }

     message('保存成功',$this->createMobileUrl('member',array('display'=>'bank')),'success');
     exit;
   }else if($dopost=='add_business'){
     $bank = $_GPC['bank'];
     $holder= $_GPC['holder'];
     $cardNo= $_GPC['cardNo'];
     // $mid
     $_conf = array(
       'bank'=>$bank,
       'holder'=>$holder,
       'cardNo'=>$cardNo,
       'weid'=>$this->weid,
       'mid'=>$mid,
       'type'=>1,
       'upbdate'=>time(),
     );
     if(!empty($_GPC['id'])){
       pdo_update(GARCIA_PREFIX.'bank',$_conf,array('id'=>$_GPC['id']));
     }else{
       pdo_insert(GARCIA_PREFIX.'bank',$_conf);
     }

     message('保存成功',$this->createMobileUrl('member',array('display'=>'bank')),'success');
     exit;
   }
    if($display){
         $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$mid;
         $_member = pdo_fetch($sql);
    }

    if($display=='fq'){

      $status = empty($_GPC['status'])?1:$_GPC['status'];
      if($status==2){
         $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and mid='".$mid."' and (status=2  or status=9)";
      }else if($status==3){
        $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and mid='".$mid."' and (status=3 or status=6 or status=8)";
      }
      else if($status==1){
         $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and mid='".$mid."' and (status=".$status." or status=4) order by status desc";
      }

       $list = pdo_fetchall($sql);

       //处理图片
       foreach ($list as $key => $value) {
            $_v = 0;
            foreach ($value as $k => $v) {
                if($k=='thumb'){

                   $_id = json_decode($v,true);
                   foreach ($_id as $k => $v) {
                       if($k==0){
                          $__id = $v;
                       }else{
                          $__id.=",".$v;
                       }
                   }

                   if(!empty($__id)){
                     $thumb = pdo_fetchall("SELECT * FROM ".tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id in(".$__id.")");
                     $list[$key]['cover_thumb'] =$thumb[0]['thumb'];
                   }


                }else if($k=='rand_day'){
                  if($status==1){
                    if($this->diffDate(date('Y-m-d',time()),$value['rand_day'])<=0){
                       $_v  = 1;
                       $_vk = $key;
                       if($value['status']==1){
                         $list[$key]['rand_day'] = "剩余".$this->diffDate(date('Y-m-d',time()),$value['rand_day'])."天";
                       }else if($value['status']==4){
                         $list[$key]['rand_day'] = '<font color="red">审核中</font>';
                       }

                    }else{
                      if($value['status']==1){
                        $list[$key]['rand_day'] = "剩余".$this->diffDate(date('Y-m-d',time()),$value['rand_day'])."天";
                      }else if($value['status']==4){
                        $list[$key]['rand_day'] = '<font color="red">审核中</font>';
                      }
                    }
                  }else{
                      $list[$key]['rand_day'] ='已结束';
                  }

                }
            }

            if($_v==0){
              $_thumb[]= $list[$key];
            }else{
               //过期
                pdo_update(GARCIA_PREFIX."fabu",array('status'=>3),array('id'=>$value['id']));
            }
       }
       $list = '';
       $list = $_thumb;
      //  var_dump($list);
      include $this->template('member/fq');
      exit;
    }else if($display=='guanz'){

      $status = empty($_GPC['status'])?1:$_GPC['status'];
         $_a = pdo_fetchcolumn('SELECT is_shouc FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$mid);

       if(!empty($_a)){
         if($status==2){
            $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid."  and mid=".$mid." and status=2 and id in(".$_a.")";
         }else if($status==3){
           $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid."  and mid=".$mid." and id in(".$_a.") and (status=3 or status=6 or status=8) ";
         }
         else if($status==1){
            $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid."  and mid=".$mid." and id in(".$_a.") and (status=".$status." or status=4)   order by status desc";
         }

             $list = pdo_fetchall($sql);
             foreach ($list as $key => $value) {
                  foreach ($value as $k => $v) {
                    if($k=='thumb'){
                      $_id = json_decode($v,true);
                      foreach ($_id as $k => $v) {
                           if(empty($v))continue;
                          if($k==0){
                             $__id = $v;
                          }else{
                             $__id.=",".$v;
                          }
                      }

                      if(!empty($__id)){
                        $thumb = pdo_fetchall("SELECT * FROM ".tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id in(".$__id.")");
                        $list[$key]['cover_thumb'] =$thumb[0]['thumb'];
                      }

                    }else if($k=='rand_day'){
                      if($status==1){
                        if($this->diffDate(date('Y-m-d',time()),$value['rand_day'])<=0){
                           $_v  = 1;
                           $_vk = $key;
                           if($value['status']==1){
                             $list[$key]['rand_day'] = "剩余".$this->diffDate(date('Y-m-d',time()),$value['rand_day'])."天";
                           }else if($value['status']==4){
                             $list[$key]['rand_day'] = '<font color="red">审核中</font>';
                           }

                        }else{
                          if($value['status']==1){
                            $list[$key]['rand_day'] = "剩余".$this->diffDate(date('Y-m-d',time()),$value['rand_day'])."天";
                          }else if($value['status']==4){
                            $list[$key]['rand_day'] = '<font color="red">审核中</font>';
                          }
                        }
                      }else{
                          $list[$key]['rand_day'] ='已结束';
                      }

                    }
                  }
             }
       }

    }else if($display=='help'){
        $_flist = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX.'project')." where weid=".$this->weid."  ");
        $_flist2 = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX.'oques')." where weid=".$this->weid." and level=1 and type=1 order by  rank asc ");

    }else if($display=='suport'){
          $status = empty($_GPC['status'])?1:$_GPC['status'];
          if($status==2){
            $sql = "SELECT b.* from ".tablename(GARCIA_PREFIX."paylog")." a
            LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." b on a.fid=b.id
            where a.weid=".$this->weid." AND a.mid=".$mid." and b.status=".$status." and a.status=1 group by fid";
          }else if($status==3){
            $sql = "SELECT b.* from ".tablename(GARCIA_PREFIX."paylog")." a
            LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." b on a.fid=b.id
            where a.weid=".$this->weid." AND a.mid=".$mid."  and (b.status=3 or b.status=6 or b.status=8) group by fid";
          }
          else if($status==1){
            $sql = "SELECT b.* from ".tablename(GARCIA_PREFIX."paylog")." a
            LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." b on a.fid=b.id
            where a.weid=".$this->weid." AND a.mid=".$mid." and b.status=".$status." and a.status=1 group by fid";
          }


          $list = pdo_fetchall($sql);
          foreach ($list as $key => $value) {
               foreach ($value as $k => $v) {
                 if($k=='thumb'){
                   $_id = json_decode($v,true);
                   foreach ($_id as $k => $v) {
                       if($k==0){
                          $__id = $v;
                       }else{
                          $__id.=",".$v;
                       }
                   }

                   if(!empty($__id)){
                     $thumb = pdo_fetchall("SELECT * FROM ".tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id in(".$__id.")");
                     $list[$key]['cover_thumb'] =$thumb[0]['thumb'];
                   }

                 }else if($k=='rand_day'){
                   if($status==1){
                     if($this->diffDate(date('Y-m-d',time()),$value['rand_day'])<=0){
                        $_v  = 1;
                        $_vk = $key;
                     }else{
                       $list[$key]['rand_day'] = $this->diffDate(date('Y-m-d',time()),$value['rand_day']);
                     }
                   }else{
                       $list[$key]['rand_day'] = 0;
                   }

                 }
               }
          }
    }else if($display=='wallet'){
      // $mid = $this->_gmodaluserid();
      // $openid = $this->memberinfo['openid'];
      // $platrrom = $this->_gplatfromuser($openid);
      // $uid = $platrrom['uid'];
      // $wallket = mc_credit_fetch($uid);
      // $wallket=$wallket['credit2'];
        $wallket = pdo_fetchcolumn('SELECT wallet FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$mid);
        $sql = "SELECT a.* FROM ".tablename(GARCIA_PREFIX."paylog")." a
        where a.weid=".$this->weid." AND a.mid=".$mid." and a.status=1 order by status asc ,upbdate desc";
        $list  = pdo_fetchall($sql);


    }else if($display=='withdraw'){

       $_member= pdo_fetch("SELECT * FROm ".tablename(GARCIA_PREFIX."shiming")." where weid=".$this->weid." AND mid=".$mid.' and status=1');

      if(empty($_member)){

          $this->_TplHtml('请实名认证后提现',$this->createMobileUrl('member',array('display'=>'shiming')),'success');
      }

        $wallket=pdo_fetchcolumn('SELECT wallet FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$mid);
        $_banklist = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX.'bank')." where weid=".$this->weid." and mid=".$mid);
    }
    else if($display=='detail'){
      // echo $mid;
      $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$mid;
      $_member = pdo_fetch($sql);
    }else if($display=='member'){
       $sql = "SELECT count(id) FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and mid='".$mid."' and status>0";
       $_fcount =pdo_fetchcolumn($sql);


       /**
        * 获取当前用户的支持项目
        */
       $sql = "SELECT id,fid from ".tablename(GARCIA_PREFIX."paylog")." where weid=".$this->weid." AND mid=".$mid." and status=1 group by fid";
       $_sup = pdo_fetchall($sql);
       $_sup = count($_sup);
       $_member = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$mid);
       if(empty($_member['headimgurl'])){
           $_member['headimgurl'] = tomedia($this->sys['user_img']);
           pdo_update(GARCIA_PREFIX."member",array('headimgurl'=>$_member['headimgurl']),array('id'=>$mid));
       }
       if(empty($_member['nickname'])){
           $_member['nickname'] = empty($_member['mobile'])?$_member['tel']:$_member['mobile'];
           $_member['nickname'] = substr($_member['nickname'],0,3)."****".substr($_member['nickname'],7);
           $_member['nickname'] = urlencode($_member['nickname']);
           pdo_update(GARCIA_PREFIX."member",array('nickname'=>$_member['nickname']),array('id'=>$mid));
       }
       $_member['tel'] =empty($_member['mobile'])?$_member['tel']:$_member['mobile'];
       $_member['tel'] = substr($_member['tel'],0,3)."****".substr($_member['tel'],7);
       $is_shouc = $_memner['is_shouc'];
       $is_shouc = explode(',',$is_shouc);
       if(empty($is_shouc[0])){
         $is_shouc = 0;
       }
       else{
         $is_shouc = count($is_shouc);
       }
    $wallket = $_member['wallet'];

    }else if($display=='smsucceses'){
       $_member = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX."shiming")." where weid=".$this->weid." AND mid=".$mid);
       if($_member['status']==0){
           $_info='您的资料已提交，正在等待管理员审核中';
       }else if($_member['status']==1){
          $_info='恭喜您，审核成功';
       }
       if(!empty($_member['thumb'])){
            $idcar = pdo_fetchcolumn('SELECT pic FROM '.tablename('jy_qsc_photo')." where weid=".$this->weid." and id=".$_member['thumb']);
       }

      //  $idcar = $_member['thumb'];

    }else if($display=='shiming'){
      $_member = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX."shiming")." where weid=".$this->weid." AND mid=".$mid);
      if($_member){
        echo "<script language='javascript' type='text/javascript'>";
        echo "window.location.href='".$this->createMobileUrl('member',array('display'=>'smsucceses'))."'";
        echo "</script>";
      }
    }else if($display=='phone'){
       $_member = pdo_fetch("SELECT * FROm ".tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." AND id=".$mid);
       $token = md5(time().$this->weid.$mid);
    }else if($display=='nexphone'){
       $_md5 = $_GPC['token'];

        $code = (string)rand(1000,9999);
        $phone =$_GPC['mobile'];
        $_phone  =  pdo_fetchcolumn('SELECT tel    FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and tel='".$_phone."'");
        $_mobile  = pdo_fetchcolumn('SELECT mobile FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid."  and mobile='".$_phone."'");

        if($_phone){
                $this->_TplHtml('该号码已被别人使用',referer(),'error');
                exit;
        }

        $data = array(
            'weid'=>$this->weid,
            'mid'=>$mid,
            'token'=>$_md5,
            'code'=>$code,
            'upbdate'=>time(),
            'tel'=>$phone
        );

        $_c = pdo_fetchcolumn('SELECT  upbdate FROM '.tablename(GARCIA_PREFIX."msgcode")." where weid=".$this->weid." and mid=".$mid." order by id desc");
        $_nickname = pdo_fetchcolumn('SELECT nickname FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$mid);
        $_nickname = urldecode($_nickname);
         $_c = (int)$_c+60;

        if(time()>$_c){
           pdo_insert(GARCIA_PREFIX."msgcode",$data);

           $files = json_decode($this->sys['dayu_files'],true);
           foreach ($files as $key => $value) {
               if($value['value']==1){
                  //验证码
                  $_config[$value['file']] = $code;
               }else if($value['value']==2){
                  //用户昵称
                  $_config[$value['file']] = $_nickname;
               }else if($value['value']==3){
                  //时间
                  $_config[$value['file']] = date('Y-m-d H:i:s',time());
               }else if($value['value']==4){
                  //站点名称
                  $_config[$value['file']] = $this->sys['sitename'];
               }
           }

          //  $_config = array('code'=>$code,'product'=>$this->sys['sitename']);
           $_config = json_encode($_config);
           $c = new TopClient;
           $c->appkey = $this->sys['dayu_appkey'];
           $c->secretKey = $this->sys['dayu_secretkey'];
           $req = new AlibabaAliqinFcSmsNumSendRequest;
           $req->setExtend(123456);
           $req->setSmsType("normal");
           $req->setSmsFreeSignName($this->sys['dayu_sign']);
           $req->setSmsParam($_config);
           $req->setRecNum($phone);
           $req->setSmsTemplateCode($this->sys['dayu_temp']);
           $resp = $c->execute($req);
           if($resp->code!=0){
              message($resp->sub_msg,referer(),'error');
           }

        }

    }else if($display=='l1'){
        $pid  =  $_GPC['pid'];
        $id  =  $_GPC['id'];
        $type = $_GPC['type'];
        if(empty($type)){
            $details = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."ques")." where weid=".$this->weid." AND pid=".$pid." and id=".$id);
        }else{
            $details = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."oques")." where weid=".$this->weid." and id=".$_GPC['pxid']);
        }

    }else if($display=='llist'){
        $pid  =  $_GPC['pid'];
        $type = $_GPC['type'];
        if(empty($type)){
            $list = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."ques")." where weid=".$this->weid." AND pid=".$pid." order by rank asc");
        }else{
            $list = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."oques")." where weid=".$this->weid." AND pre_id=".$pid." order by rank asc");
        }

    }else if($display=='bank'){

        $_list = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX.'bank')." where weid=".$this->weid." and mid=".$mid);

    }else if($display=='add_personal_bank'){

        if(!empty($_GPC['id'])){

           $_item = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."bank")." where weid=".$this->weid." and id=".$_GPC['id']);
        }
    }else if($display=='add_business_bank'){

        if(!empty($_GPC['id'])){

           $_item = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."bank")." where weid=".$this->weid." and id=".$_GPC['id']);
        }
    }
    else if($display=='kuaidi'){




          $kuaidi = pdo_fetchall('SELECT a.*,b.reward FROM '.tablename(GARCIA_PREFIX."fahuo")." a
          LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")."  b on a.fid = b.id
          where a.weid=".$this->weid." and a.mid=".$mid);

          // var_dump($kuaidi);

          // echo $mid;
    }else if($display=='kuaidi_info'){
      $kuaidi = pdo_fetch('SELECT a.*,b.reward FROM '.tablename(GARCIA_PREFIX."fahuo")." a
      LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")."  b on a.fid = b.id

      where a.weid=".$this->weid." and a.id=".$_GPC['id']);

      $code = $mappings[$kuaidi['kuaidi']];
      $rand = rand();
      $url = "http://wap.kuaidi100.com/wap_result.jsp?rand={$rand}&id={$code}&fromWeb=null&&postid=".$kuaidi['kuai_order'];
      $dat = ihttp_get($url);
      $msg = '';
     if(!empty($dat) && !empty($dat['content'])) {
     	$reply = $dat['content'];
     	preg_match ('/查询结果如下所示.+/', $reply, $matchs);
     	$reply = $matchs[0];
     	if (empty($reply)) {
     		 preg_match('/errordiv.*?<p.*?>(.+?)<\/p>{1}/', $dat['content'], $matchs);
     		 $msg = ', 错误信息为: ' . $matchs[1];
         $msg = '没有查找到相关的数据' . $msg . '. 请重新发送或检查您的输入格式, 正确格式为: 快递公司+空格+单号, 例如: 申通 2309381801';
         $is_has = false;
     	} else {
       		preg_match_all('/&middot;(.*?)<br \/>(.*?)<\/p>/', $reply, $matchs);
       		$traces = '';
       		for ($i = 0; $i < count($matchs[0]); $i++ ) {
       			$traces[]=array(
              'time'=>$matchs[1][$i],
              'info'=>$matchs[2][$i]
            );
       		}
       	// 	 krsort($traces);
          $msg = $traces;
             $is_has = true;
      	}
     }

    }


   //display end



    include $this->template('member/'.$display);





    function _pick($id,$weid,$type){
        if($type==0){
          $sql = "SELECT name FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$weid." and id=".$id;
          $_tmp = pdo_fetchcolumn($sql);
          if(empty($_tmp)){
            return $_tmp = '已被删除';
          }
          return "（微信支付）".$_tmp;
        }else if($type==1){
          $status = pdo_fetchcolumn("SELECT status FROM ".tablename(GARCIA_PREFIX."paylog")." where weid=".$weid." and id=".$id);
          if($status==0){
             return "（提现）".pdo_fetchcolumn("SELECT msg FROM ".tablename(GARCIA_PREFIX."paylog")." where weid=".$weid." and id=".$id);
          }
          else{
            return "（已发送）".pdo_fetchcolumn("SELECT msg FROM ".tablename(GARCIA_PREFIX."paylog")." where weid=".$weid." and id=".$id);
          }
        }
        else if($type==4){
          return pdo_fetchcolumn("SELECT msg FROM ".tablename(GARCIA_PREFIX."paylog")." where weid=".$weid." and id=".$id);
        }
        else if($type==6){

          return pdo_fetchcolumn("SELECT msg FROM ".tablename(GARCIA_PREFIX."paylog")." where weid=".$weid." and id=".$id);
        }
        else if($type==7){

          return pdo_fetchcolumn("SELECT msg FROM ".tablename(GARCIA_PREFIX."paylog")." where weid=".$weid." and id=".$id); //申请领取筹款资金
        }
        else if($type==8){

          return pdo_fetchcolumn("SELECT msg FROM ".tablename(GARCIA_PREFIX."paylog")." where weid=".$weid." and id=".$id); //申请领取筹款资金
        }
        else if($type==9){
          return pdo_fetchcolumn("SELECT msg FROM ".tablename(GARCIA_PREFIX."paylog")." where weid=".$weid." and id=".$id); //申请领取筹款资金
        }
    }
    function _fee($type,$fee){
        switch ($type) {
          case '0':
            return "-".$fee;
            break;
            case '1':
            return  "-".$fee;
            case '4':
            return  "+".$fee;
            case '6':
            return  "+".$fee;
            case '7':
            return $fee;
            case '8':
            return "+".$fee;
            case '9':
            return $fee;
            break;

        }
    }
    function _thumb($fid,$type,$weid){
      global $_W;
      if($type==0){

        return $_W['siteroot']."addons/".GARCIA_DIR."/resource/images/WeChat_96px_1194711_easyicon.net.png";;
      }else if($type==1){
        return $_W['siteroot']."addons/".GARCIA_DIR."/resource/images/snow_63.png";
      }
      else if($type==4){
        return $_W['siteroot']."addons/".GARCIA_DIR."/resource/images/wallet.png";
      }
      else if($type==6){
        return $_W['siteroot']."addons/".GARCIA_DIR."/resource/images/cash_receiving_64px_1125250_easyicon.net.png";
      }
      else if($type==7){
        return $_W['siteroot']."addons/".GARCIA_DIR."/resource/images/Time_Machine_96px_1137386_easyicon.net.png";
      }
      else if($type==8){
        return $_W['siteroot']."addons/".GARCIA_DIR."/resource/images/success_512px_1129030_easyicon.net.png";
      }
      else if($type==9){
        return $_W['siteroot']."addons/".GARCIA_DIR."/resource/images/sign_error_91.339805825243px_1185688_easyicon.net.png";
      }
    }

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

    function _reward($id,$reward,$is){

      $reward = json_decode($reward,true);
       $reward = $reward[$id]['supportContent'];
      if(empty($reward)){
          return '未知';
      }else{
        return $reward;
      }
    }
 ?>
