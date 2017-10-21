<?php

if($this->modal=='pc'){
  // if($this->conf)
  if(!$is_login){
    $this->_TplHtml('请先登陆',$_W['siteroot']."app/".substr($this->createMobileUrl('index'),2),'error');
    exit;
  }



  $display = empty($_GPC['display'])?'index':$_GPC['display'];

  if($display=='done'){
    //推荐
    $tlist = pdo_fetchall('SELECT a.*,b.headimgurl as avatar,b.nickname,c.project_name as pname FROM '.tablename(GARCIA_PREFIX."fabu")." a
    LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
    LEFT JOIN ".tablename(GARCIA_PREFIX."project")." c on c.id=a.pid
    where  a.weid=".$this->weid."  and a.status=1  group by  a.id ORDER BY RAND()  limit 0,4");
    foreach ($tlist as $key => $value) {
         foreach ($value as $k => $v) {
              if($k=='cover_thumb'){
                $_id = '';
                $ids = json_decode($v,true);
                foreach ($ids as $k3 => $v3) {
                  if(!is_numeric($v3))continue;
                  if($k3==0){
                     $_id=$v3;
                  }
                }

                if(!empty($_id)){
                  $_th = pdo_fetchcolumn("SELECT pic FROM ".tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id =".$_id);
                  $tlist[$key]['cover_thumb'] = $_th;
                }
              }

              else if($k=='project_texdesc'){
                  $tlist[$key]['description'] = mb_substr(strip_tags(htmlspecialchars_decode($v)),0,60,'utf-8');
              }
              else if($k=='avatar'){
                  $tlist[$key]['avatar'] =empty($v)?tomedia($this->sys['user_img']):$v;
              }
              else if($k=='nickname'){
                  $tlist[$key]['nickname'] =empty($v)?"用户:".$tlist[$key]['id']:urldecode($v);
              }

              $_sum = 0;
              $_sum = pdo_fetchcolumn('SELECT sum(fee) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$value['id']." and status=1 and (type=0 or type=6)");
              $_count = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$value['id']." and status=1 and (type=0 or type=6)");
              $tlist[$key]['is_sup'] = empty($_count)?0:$_count;
              $tlist[$key]['has_monet'] = empty($_sum)?0:$_sum;
              $tlist[$key]['present'] = round($tlist[$key]['has_monet']/$tlist[$key]['tar_monet'],2)*100;
               $tlist[$key]['_time'] = $this->_format_date($tlist[$key]['upbdate']);
         }
         $_avatar = pdo_fetchall('SELECT b.headimgurl as avatar FROM '.tablename(GARCIA_PREFIX.'paylog')." a
         LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
         where a.weid=".$this->weid." and a.fid=".$value['id']." and a.status=1 and (a.type=0 or a.type=6) order by a.id desc limit 0,10");
         foreach ($_avatar as $k1 => $v2) {
           $tlist[$key]['_thumb'][$k1] = $v2['avatar'];
         }
    }
      include $this->template('web/pay/'.$display);
     exit;
  }else if($display=='paydone'){
    $tid = $_GPC['tid'];
    $fid = $_GPC['fid'];
     $trade_no = $_GPC['trade_no'];
    if(empty($tid)){
      $data = array(
                    'title'=>'操作错误',
                    'desc'=>'支付失败',
                    'type'=>1,'btn'=>'返回首页',
                    'url'=>$this->createMobileUrl('index')
                  );
      $this->_WebWait($data);
      exit;
    }

    $sql ="SELECT a.*,b.nickname,c.name as t1,d.openid as touser,b.id as mid FROM ".tablename(GARCIA_PREFIX."paylog")." a
    LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
    LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." c on a.fid=c.id
    LEFT JOIN ".tablename(GARCIA_PREFIX."member")." d on c.mid=d.id
    where a.weid=".$this->weid." and a.tid='".$tid."' and fid=".$fid;
     $config = pdo_fetch($sql);

    if($config['status']==0){
      $fee = $config['fee'];
      $fb = pdo_fetchcolumn('SELECT has_money FROM '.tablename(GARCIA_PREFIX.'fabu')." where weid=".$this->weid." and id=".$fid);
       $_fee = $fb+$fee;
      pdo_update(GARCIA_PREFIX."fabu",array('has_money'=>$_fee),array('id'=>$fid));
      pdo_update(GARCIA_PREFIX."paylog",array('upbdate'=>time(),'status'=>1,'isalipay'=>1,'transaction_id'=>$trade_no),array('id'=>$config['id']));
    }
    $data = array(
                  'title'=>'支付成功',
                  'desc'=>'支付成功',
                  'type'=>1,'btn'=>'返回项目',
                  'url'=>$this->createMobileUrl('detail',array('id'=>$fid))
                );
    $this->_WebWait($data);
    exit;
  }
   $type = $_GPC['type'];
   if($type=='reward'){
         $sql = "SELECT a.reward  FROM ".tablename(GARCIA_PREFIX.'fabu')." a where a.weid=".$this->weid." and a.id=".$_GPC['fid'];
         $reward = pdo_fetchcolumn($sql);
         $reward = json_decode($reward,true);
         $reward  = $reward[$_GPC['id']];
         //获取地址
         $sql = "SELECT province,city,address,name,tel,is_def,id FROM ".tablename(GARCIA_PREFIX."address")." where weid=".$this->weid." and mid=".$this->conf['user']['mid'];
         $address = pdo_fetchall($sql);

   }
   $fabu = pdo_fetch("SELECT a.*,b.nickname FROM ".tablename(GARCIA_PREFIX."fabu")."  a
    LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id where a.weid=".$this->weid." and a.id=".$_GPC['fid']);
  include $this->template('web/pay/'.$display);
  exit;

}
if($this->modal=='phone'){
    // include  $this->template('bad');
    // exit;

    if(!$this->_login){
       header('Location:'.$this->createMobileUrl('login'));
    }

}

$dopost = $_GPC['dopost'];

if($dopost=='paypc'){
  require_once GARCIA_PATH."class/wechatDemo/lib/WxPay.Api.php";
  include GARCIA_PATH."class/wechatDemo/WxPay.JsApiPay.php";
  require_once GARCIA_PATH.'class/wechatDemo/log.php';
  $tid = $_GPC['tid'];
  $sql = "SELECT fee,fid,status FROM ".tablename(GARCIA_PREFIX."paylog")." where weid=".$this->weid." and tid='".$tid."'";
  $config = pdo_fetch($sql);

  if($config['status']==1){
    $this->_TplHtml('无需重复支付',$_W['siteroot']."app/".substr($this->createMobileUrl('index'),2),'error');
    exit;
  }
  // var_dump($config);
  //①、获取用户openid
  $tools = new JsApiPay();
  $openid = $this->memberinfo['openid'];

  // $tid=date('YmdHhs',TIMESTAMP).TIMESTAMP.$this->weid;
  $fee = $config['fee'];
  $fid = $config['fid'];

   $total_fee = $fee*100;
   //②、统一下单
    $input = new WxPayUnifiedOrder();
    $input->SetAppid($this->sys['pay_appid']);
    $input->SetMch_id($this->sys['pay_number']);
    $input->SetBody($tid);
    $input->SetKey($this->sys['pay_miyao']);
    $input->SetOut_trade_no($tid);
    $input->SetTotal_fee($total_fee);
    $input->SetGoods_tag('支付');
    $input->SetNotify_url( $_W['siteroot'] . 'payment/wechat/notify.php');
    $input->SetTrade_type("JSAPI");
    $input->SetOpenid($openid);

    $order = WxPayApi::unifiedOrder($input);
    $jsApiParameters = $tools->GetJsApiParameters($order,$this->sys['pay_miyao']);
      // var_dump($jsApiParameters);
      $is_pc = true;
      include $this->template('pay/pay2');
  exit;
}

if($this->sys['is_h5']&&$this->modal == 'phone'){
    $mid = json_decode($this->cookies->get('userDatas'),true);
    $mid =$mid['uid'];
}else{
    $mid = $this->_gmodaluserid();
   $this->_checkMobile();
   if($this->sys['is_mobile']==1){
     if(!$this->_OauthMobile){
         $this->_TplHtml('请先绑定联系手机',$this->createMobileUrl('member',array('display'=>'phone')),'error');
         exit;
     }
   }


}
if($this->sys['bro_wx']==1){
  // 判断借用微信获取 借用微信公众号的openid
  $this->sys['pay_appid'];
  $this->sys['pay_appsecret'];
  $bro = $_SESSION['bro_openid'.$this->weid];
  if(empty($bro)){
       $this->BroOatuh();

  }
}

$dopost = $_GPC['dopost'];
$mid = $this->_gmodaluserid();
$openid = $this->memberinfo['openid'];
$avatar = $this->memberinfo['headimgurl'];
$platrrom = $this->_gplatfromuser($openid);
$tel = $this->_GetMemberTel($mid);

$uid = $platrrom['uid'];
if($dopost=='pay'){
  $tid=date('YmdHhs',TIMESTAMP).TIMESTAMP.$this->weid;
  $fee = $_GPC['fee'];
  $fid = $_GPC['fid'];
  $params = array(

    'tid' => $tid,      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码

    'ordersn' => 1,  //收银台中显示的订单号

    'title' => $tid,          //收银台中显示的标题

    'fee' => $fee,      //收银台中显示需要支付的金额,只能大于 0

    'user' =>'',     //付款用户, 付款的用户名(选填项)



  );

  //调用pay方法
  $msg = empty($_GPC['msg'])?"支持":$_GPC['msg'];
  $params=$this->pay($params);
  $params = base64_encode(json_encode($params));
  $data = array(
    'weid'=>$this->weid,
    'tid'=>$tid,
    'uid'=>$uid,
    'avatar'=>$avatar,
    'upbdate'=>time(),
    'fid'=>$fid,
    'fee'=>$fee,
    'msg'=>$msg,
    'mid'=>$mid,
    'address_id'=>$_GPC['address_id'],
    'reid'=>$_GPC['reid'],
    'dream_id'=>$_GPC['dream_id'],
    'count'=>$_GPC['count'],
    'wantSupportTel'=>$_GPC['wantSupportTel'],
    'wantSupportName'=>$_GPC['wantSupportName']

  );
  pdo_insert(GARCIA_PREFIX."paylog",$data);
  die(json_encode(array('status'=>1,'info'=>$params)));
}




 /**
  * 获取地址
  */
  if($_GPC['address_id']){
    $address  = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX."address")." where weid=".$this->weid." and mid=".$mid." and id=".$_GPC['address_id']);
  }
  else{
    $address  = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX."address")." where weid=".$this->weid." and mid=".$mid."  and is_def=1 order by id desc");
  }

$id = $_GPC['id'];
 $sql = "SELECT a.*,b.nickname,b.headimgurl as avatar,a.has_sh,b.is_shouc FROM ".tablename(GARCIA_PREFIX.'fabu')." a
 LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.openid=b.openid
 where a.weid=".$this->weid." and a.id=".$_GPC['id'];
 $config = pdo_fetch($sql);
    $project = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX.'project')." where weid=".$this->weid." and id=".$config['pid']);
    if($project['project_plus4']==1||$project['project_plus5']==1){
       $_plist = json_decode($config['reward'],true);
    }
include $this->template('pay/pay');



function _less($places,$fid,$tid,$weid){
  if(!$places){
    return '无限制';
  }else{
   $sql = "SELECT COUNT(id) FROM ".tablename(GARCIA_PREFIX."paylog")." where weid=".$weid." and fid=".$fid." AND reid='".$tid."' and status=1";
   $a = pdo_fetchcolumn($sql);
   if($places-$a<=0){
     return '售罄';
   }else{
      return "剩余 ".($places-$a)." 份";
   }

 }
}

function _less2($places,$fid,$tid,$weid){
    if(!$places){
      return 'data-go="true"';
    }else{
     $sql = "SELECT COUNT(id) FROM ".tablename(GARCIA_PREFIX."paylog")." where weid=".$weid." and fid=".$fid." AND reid='".$tid."' and status=1";
     $a = pdo_fetchcolumn($sql);
     if($places-$a<=0){
      return 'data-go="false"';
     }else{
        return 'data-go="true"';
     }
   }
}
function _less3($places,$fid,$tid,$weid){
    if(!$places){
      return true;
    }else{
     $sql = "SELECT COUNT(id) FROM ".tablename(GARCIA_PREFIX."paylog")." where weid=".$weid." and fid=".$fid." AND reid='".$tid."' and status=1";
     $a = pdo_fetchcolumn($sql);
     if($places-$a<=0){
      return false;
     }else{
        return true;
     }
   }
}
 ?>
