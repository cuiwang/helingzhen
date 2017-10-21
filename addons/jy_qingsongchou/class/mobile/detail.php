<?php


$dopost = $_GPC['dopost'];
if($this->sys['is_h5']&&$this->modal == 'phone'){
  if($dopost=='ajax'){
        if(!$this->_login){
          die(json_encode(array('status_code'=>0,'msg'=>'请先登陆')));
        }

  }
      $sql = "SELECT a.shouc,a.is_share,a.project_texdesc FROM ".tablename(GARCIA_PREFIX.'fabu')." a
      where a.weid=".$this->weid." and a.id=".$_GPC['id'];
      $this->config = pdo_fetch($sql);
    //是否关注
    $wb_url = $_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('id'=>$_GPC['id'],'is_share'=>1)),2);
    $wb_pic = $this->sys['share_img'];
    $wb_title = mb_substr(strip_tags(htmlspecialchars_decode($this->config['project_texdesc'])),0,150,'utf-8');
    $wb_title = str_replace('&nbsp;','',$wb_title);
    $wb_title = str_replace(array("\r\n", "\r", "\n"), "",$wb_title).$this->sys['sitename'];
    $this->config['share']['weibo'] = "http://v.t.sina.com.cn/share/share.php?title=".$wb_title."&url=".urlencode($wb_url).'&content=utf-8&sourceUrl='.$wb_url.'&pic='.urlencode(tomedia($wb_pic));
    $this->config['share']['qqzon'] = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?title='.$wb_title.'&summary='.urlencode($wb_title).'&url='.urlencode($wb_url).'&pics='.tomedia($wb_pic);
    $this->config['share']['qqweibo']= 'http://v.t.qq.com/share/share.php?title='.$wb_title.'&url='.urlencode($wb_url).'&pic='.tomedia($wb_pic);
    $this->config['share']['qq']='http://connect.qq.com/widget/shareqq/index.html?title='.$wb_title.'&url='.urlencode($wb_url)."&pics=".tomedia($wb_pic);
    if(in_array($_GPC['id'],$is_shouc)){ $_gz = true; }else{$_gz = false; }
    $this->h5t('detail/index');
}else if($this->modal=='pc'){

      $project_l = pdo_fetchall("SELECT * FROM ".tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and is_p=1 order by rank asc limit 3");
      $sql = "SELECT a.*,c.nickname,c.headimgurl as avatar ,
      b.project_plus4 as is_reward ,b.project_plus3 as is_list,b.is_hospital,
      c.id as mid FROM ".tablename(GARCIA_PREFIX.'fabu')." a
      LEFT JOIN ".tablename(GARCIA_PREFIX."project")." b on a.pid=b.id
      LEFT JOIN ".tablename(GARCIA_PREFIX."member")." c on a.mid=c.id
      where a.weid=".$this->weid." and a.id=".$_GPC['id'];

      // 分享处理
      $is_share = $_GPC['is_share'];
      if($is_share==1){
         //判断是否已经记录过
        if($this->conf['user']['mid']){
           $is_mark = pdo_fetchcolumn('SELECT id FROM '.tablename(GARCIA_PREFIX.'sharelist')." where weid=".$this->weid." and mid=".$this->conf['user']['mid']." and fid=".$_GPC['id']);
         }
         if(!$is_mark){
             $share = pdo_fetchcolumn('SELECT is_share FROM '.tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id=".$_GPC['id']);
             $share  = (int)$share+1;
             pdo_update(GARCIA_PREFIX."fabu",array('is_share'=>$share),array('id'=>$_GPC['id']));
             if($this->conf['user']['mid']){
                pdo_insert(GARCIA_PREFIX."sharelist",array('weid'=>$this->weid,'fid'=>$_GPC['id'],'mid'=>$this->conf['user']['mid'],'upbdate'=>time()));
             }

         }

      }
      $this->config = pdo_fetch($sql);
      //是否关注
      $wb_url = $_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('id'=>$_GPC['id'],'is_share'=>1)),2);
      $wb_pic = $this->sys['share_img'];
      $wb_title = mb_substr(strip_tags(htmlspecialchars_decode($this->config['project_texdesc'])),0,150,'utf-8');
      $wb_title = str_replace('&nbsp;','',$wb_title);
      $wb_title = $wb_title = str_replace(array("\r\n", "\r", "\n"), "",$wb_title).$this->sys['sitename'];
      $this->config['share']['weibo'] = "http://v.t.sina.com.cn/share/share.php?title=".$wb_title."&url=".urlencode($wb_url).'&content=utf-8&sourceUrl='.$wb_url.'&pic='.urlencode(tomedia($wb_pic));
      $this->config['share']['qqzon'] = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?title='.$wb_title.'&summary='.urlencode($wb_title).'&url='.urlencode($wb_url).'&pics='.tomedia($wb_pic);
      $this->config['share']['qqweibo']= 'http://v.t.qq.com/share/share.php?title='.$wb_title.'&url='.urlencode($wb_url).'&pic='.tomedia($wb_pic);
      $this->config['share']['qq']='http://connect.qq.com/widget/shareqq/index.html?title='.$wb_title.'&url='.urlencode($wb_url)."&pics=".tomedia($wb_pic);


      $this->config['superNumber'] = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$_GPC['id']." and status=1 and (type=0 or type=6)  and fee!=0");
       $this->config['hasMoney'] = pdo_fetchcolumn('SELECT sum(fee) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$_GPC['id']." and status=1 and (type=0 or type=6)");
      $this->config['hasMoney'] = max(0,$this->config['hasMoney']);
      $this->config['present'] = round($this->config['hasMoney']/$this->config['tar_monet'],2)*100;
      $this->config['hasMoney'] = number_format($this->config['hasMoney'],2);
          $cur_data = date('Y-m-d',time());
      $this->config['less'] = $this->diffDate($cur_data,$this->config['rand_day']);
      $this->config['_time'] = $this->_format_date($this->config['upbdate']);
      $this->config['nickname'] = empty($this->config['nickname'])?'用户:'.$this->config['mid']:urldecode($this->config['nickname']);
      $this->config['_plist']  = json_decode($this->config['reward'],true);
      $this->config['dream']  = json_decode($this->config['dream'],true);

      $ids = json_decode($this->config['thumb'],true);

      foreach ($ids as $k3 => $v3) {
        if(empty($v3))continue;
        if($k3==0){
           $_id.=$v3;
        }else{
           $_id.=",".$v3;
        }
      }
      if(!empty($_id)){
        $_th = pdo_fetchall("SELECT thumb,pic FROM ".tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id in(".$_id.")");
       foreach ($_th as $k4 => $v4) {
          $_thb[$k4]['pic'] = $v4['pic'];
          $_thb[$k4]['thumb'] = $v4['thumb'];
       }
      $thumb = $_thb;
      }


      //更新动态
      $_uplist = pdo_fetchall('SELECT a.*,b.nickname,b.headimgurl as avatar FROM '.tablename(GARCIA_PREFIX.'update')." a
      LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
      where a.weid=".$this->weid."  and a.fid=".$_GPC['id']." order by id desc");
      foreach ($_uplist as $key => $value) {
          foreach ($value as $k => $v) {

              if($k=='nickname'){
                 $_uplist[$key][$k] = urldecode($v);
              }
              if($k=='upbdate'){
                    $_uplist[$key][$k] = $this->_format_date($v);
                    // echo
              }
              if($k=='thumb'){
                $v = json_decode($v,true);
                $v = $v[0];
                if(!empty($v)){
                   $_uplist[$key][$k] = array(
                     'pic'=> $this->_getPic($v),
                     'thumb'=> $this->_getthumb($v),
                   );
                }

              }
          }

      }
      //评论总数
      $pcount = pdo_fetchcolumn('SELECT count(a.id) FROM '.tablename(GARCIA_PREFIX."message")." a where a.weid=".$this->weid." and a.fid=".$_GPC['id']." order by id desc");

      //是否收藏
      if(!empty($this->conf['user']['mid'])){
             $is_shouc = pdo_fetchcolumn('SELECT id FROM '.tablename(GARCIA_PREFIX.'member')." where weid=".$this->weid." AND id='".$this->conf['user']['mid']."' and FIND_IN_SET('".$_GPC['id']."',is_shouc)");
      }else{
        $is_shouc = false;
      }

      //推荐
      $tlist = pdo_fetchall('SELECT a.*,b.headimgurl as avatar,b.nickname FROM '.tablename(GARCIA_PREFIX."fabu")." a
      LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
      where  a.weid=".$this->weid."  and a.status=1  group by  a.id ORDER BY RAND()  limit 0,4");
      foreach ($tlist as $key => $value) {
                   $_id = '';
           foreach ($value as $k => $v) {

               if($k=='cover_thumb'){
               $ids = json_decode($v,true);
                 foreach ($ids as $k3 => $v3) {
                   if(!is_numeric($v3))continue;
                   if(empty($_id)){
                      $_id=$v3;
                   }
                 }

                 if(!empty($_id)){
                    $_th = pdo_fetchcolumn("SELECT pic FROM ".tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id =".$_id);
                   $tlist[$key]['cover_thumb'] = $_th;
                 }
               }
             if($k=='project_texdesc'){
                 $tlist[$key]['description'] = mb_substr(strip_tags(htmlspecialchars_decode($v)),0,60,'utf-8');
             }
              $tlist[$key]['present'] = round($tlist[$key]['has_monet']/$tlist[$key]['tar_monet'],2)*100;
                            $tlist[$key]['_time'] = $this->_format_date($tlist[$key]['upbdate']);
           }
      }

      //支持者
      $_slist= pdo_fetchall('SELECT a.upbdate,a.fee,a.msg,a.id,b.nickname,b.headimgurl as avatar,a.upbdate,a.wantSupportTel FROM '.tablename(GARCIA_PREFIX."paylog")." a
      LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b  on a.mid=b.id
      where a.weid=".$this->weid." and a.fid=".$_GPC['id']." and a.status=1 and (a.type=0 or a.type=6) order by id desc");
      // foreach ($_slist as $key => $value) {
      //    $temp = '';
      //      $sql = 'SELECT  a.*,b.nickname FROM '.tablename(GARCIA_PREFIX.'message')." a
      //     LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on b.id = a.mid
      //      where a.weid=".$this->weid." AND a.payid=".$value['id']." and a.fid=".$_GPC['id'];
      //      $temp =pdo_fetchall($sql);
      //      // echo $value['id'];
      //
      //
      // }
       foreach ($_slist as $key => $value) {
         if($value['nickname']){
             $_slist[$key]['nickname']=urldecode($value['nickname']);
         }
         if($value['upbdate']){
             $_slist[$key]['upbdate']=$this->_format_date($value['upbdate']);
         }
         if($value['avatar']){
             $_slist[$key]['avatar']=  tomedia($_slist[$key]['avatar']);
         }
       }

       $pllist = pdo_fetchall('SELECT a.id,a.payid,b.nickname,b.headimgurl as avatar,a.upbdate FROM '.tablename(GARCIA_PREFIX."message")." a
        LEFT JOIN ".tablename(GARCIA_PREFIX."member")."  b  on a.mid = b.id where a.weid=".$this->weid." and a.fid=".$_GPC['id']."  group by a.payid order by a.upbdate asc");
        krsort($pllist);

        foreach ($pllist as $key => $value) {
             foreach ($value as $k => $v) {
                   if($k=='nickname'){
                     $pllist[$key][$k] = urldecode($v);
                   }
                   if($k=='avatar'){
                      $pllist[$key][$k] = tomedia($v);
                   }
             }
             $pllist[$key]['mess'] =  pdo_fetchall('SELECT a.id,a.content,a.upbdate,a.wxid,b.nickname,b.headimgurl as avatar,a.mesid,a.tmid FROM '.tablename(GARCIA_PREFIX."message")." a
              LEFT JOIN ".tablename(GARCIA_PREFIX."member")."  b  on a.mid = b.id where a.weid=".$this->weid." and a.fid=".$_GPC['id']." and a.payid=".$value['payid']);
        }

      include $this->template('web/detail/index');
}
else{

  if($this->sys['is_h5']&&$this->modal == 'phone'){
    $mid = json_decode($this->cookies->get('userDatas'),true);
    $mid = empty($mid)?$_GPC['mid']:$mid;
  }else{
    $mid = $this->_gmodaluserid();
    $mid = empty($mid)?$_GPC['mid']:$mid;
  }

      $display = empty($_GPC['display'])?'detail':$_GPC['display'];
      $pazie = 15;
    if($dopost=='ajax'){

       $_shouc = pdo_fetchcolumn('SELECT shouc FROM '.tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id=".$_GPC['fid']);
       $_shoum = $_shoum2 = pdo_fetchcolumn('SELECT is_shouc FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$mid);
       $_shoum = explode(',',$_shoum);

       if($_GPC['modal']=='jia'){
          $_shouc = $_shouc+1;
          if(empty($_shoum[0])){
             $_shoum =$_GPC['fid'];
          }else{
             $_shoum =$_shoum2.",".$_GPC['fid'];
          }
       }else{
         foreach ($_shoum as $k => $v) {
             if($v==$_GPC['fid']){
               array_splice($_shoum, $k, 1);
             }
         }
         foreach ($_shoum as $key => $value) {
             if($key<=0){
               $temp = $value;
             }else{
               $temp.=",".$value;
             }
         }
         $_shoum = '';
         $_shoum = $temp;
         $_shouc = ($_shouc-1)==0?0:$_shouc-1;
       }

       pdo_update(GARCIA_PREFIX."member",array('is_shouc'=>$_shoum),array('id'=>$mid));
       pdo_update(GARCIA_PREFIX."fabu",array('shouc'=>$_shouc),array('id'=>$_GPC['fid']));
       die(json_encode(array('status'=>1)));
      exit;
    }else if($dopost=='save_hospital'){

      if(is_array($_GPC['idcar'])){
         $idcar = implode(',',$_GPC['idcar']);
      }
      $data = array(
          'weid'=>$this->weid,
          'mid'=>$mid,
          'uid'=>$uid,
          'fid'=>$_GPC['fid'],
          'upbdate'=>time(),
          'dis_name'=>$_GPC['dis_name'],
          'dis_idcar'=>$_GPC['dis_idcar'],
          'hospital'=>$_GPC['hospital'],
          'disease'=>$_GPC['disease'],
          'creator_name'=>$_GPC['creator_name'],
          'creator_id'=>$_GPC['creator_id'],
          'creator_phone'=>$_GPC['creator_phone'],
          'desction'=>$_GPC['desction'],
          'idcar'=>$idcar,
      );
      $id = $_GPC['id'];
      if(empty($id)){
          pdo_insert(GARCIA_PREFIX."hospital",$data);
      }else{
         pdo_update(GARCIA_PREFIX."hospital",$data,array('id'=>$id));
      }
      $openids = $this->_gManers();
      $nickname = $this->_GetMemberName($mid);
      $message =$this->sys['sitename']."系统消息:\nid:[".$mid."],昵称:[".$nickname."]的用户在".date('Y-m-d H:i:s')." 提交了医院资料，请到尽快到后台进行审核!";
      $this->_SendTxts($message,$openids);
      $this->_TplHtml('提交成功',referer(),'success');
      exit;
    }
    else if($dopost=='message'){
       $fid = $_GPC['fid'];
       $mesid = $_GPC['mesid'];
       $tmid = $_GPC['tmid'];
       $wxid = $_GPC['wxid'];
       //防止重复操作
        $tokens  = $_GPC['tokens'];

       $_reid = pdo_fetchcolumn("SELECT id FROM ".tablename(GARCIA_PREFIX.'message')." where weid=".$this->weid." and fid=".$fid." and token='".$tokens."'");

        if(!empty($_reid)){
           $this->_TplHtml('请不要重复评论',referer(),'error');
           exit;
        }

        $content = htmlspecialchars_decode($_GPC['content']);

       $acc_json = $this->jsonfile;
       $token = $this->wapi->getAccessToken($acc_json);
       if(empty($content)){
             $this->_TplHtml('评论不能为空',referer(),'error');
             exit;
       }
       //获取发布人信息

       $sql = "SELECT b.openid,a.name FROM ".tablename(GARCIA_PREFIX."fabu")." a
       LEFT JOIN ".tablename(GARCIA_PREFIX."member") ." b on a.mid = b.id
       where a.weid=".$this->weid." and a.id=".$fid;
       $touser = pdo_fetch($sql);
       $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."member").' where weid='.$this->weid." and openid='".$openid."'";
       $_ms  =pdo_fetch($sql);
       $_d = array(
           '{nickname}',
           '{time}',
           '{content}',
           '{name}',

       );

       $_m = array(
             urldecode($_ms['nickname']),
             date('Y-m-d H:i:s',time()),
             $content,
             $touser['name'],

       );
       if(!empty($mesid)){
         $touser['openid'] = pdo_fetchcolumn('SELECT openid FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$tmid);
       }

// $this->sys['kf_pl_success'];
       if($this->sys['kf_pl_type']==0){
           $_url = $_W['siteroot']."/".$this->createMobileUrl('detail',array('id'=>$fid));
           $message = str_replace($_d,$_m,$this->sys['kf_pl_success']);
           $message = urlencode($message);
           $message = $message.'<a href="'.$_url.'">'.urlencode('点击查看').'</a>';
         $a = $this->wapi->sendText($touser['openid'],$message,$token);
       }else{
         $temp = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX."temp")." where weid=".$this->weid." and id=".$this->sys['kf_pl_tmp']);
          $temp_id = $temp['tempid'];
          $_url = $_W['siteroot'].'app/'."app/".substr($this->createMobileUrl('detail',array('id'=>$fid)),2);
          $url =  str_replace("{url}",$_url,$temp['url']);
         $parama = json_decode($temp['parama'],true);
         foreach ($parama as $key => $value) {
            foreach ($value as $k => $v) {
                 if($k=='value'){
                      $parama[$key][$k] = str_replace($_d,$_m,$v);
                 }else if($k=='color'){
                     if(empty($v)){
                        $parama[$key][$k] = '#333333';
                     }
                 }
            }
         }
           $a = $this->wapi->sendTemplate($touser['openid'],$temp_id,$url,$token,$parama);
       }

       $payid = $_GPC['payid'];
       $content = $_GPC['content'];

       $data = array(
         'weid'=>$this->weid,
         'payid'=>$payid,
         'mid'=>$mid,
         'fid'=>$fid,
         'content'=>$content,
         'upbdate'=>time(),
         'mesid'=>$mesid,
         'tmid'=>$tmid,
         'token'=>$tokens,
         'wxid'=>$wxid,
       );
       pdo_insert(GARCIA_PREFIX."message",$data);

      $this->_TplHtml('评论成功',referer(),'success');

      exit;
    }else if($dopost=='htu'){
         $list = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."oques")." where weid=".$this->weid." and level=1 and type=2 order by rank asc");
        include $this->template('howtouse');
      exit;
    }else if($dopost=='howtoused'){
      $details = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."oques")." where weid=".$this->weid." and level=2 and type=2 and id=".$_GPC['id']);

     include $this->template('howtousede');
   exit;
 }else if($dopost=='howtouselist'){
   $list = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."oques")." where weid=".$this->weid." and level=2 and type=2 and pre_id=".$_GPC['pre_id']);
   include $this->template('howtouselist');
   exit;
 }
 else if($dopost=='save_bang'){
    $prove_relation = $_GPC['prove_relation'];
   $prove_name = $_GPC['prove_name'];
   $prove_desc = $_GPC['prove_desc'];
   $__mid = pdo_fetchcolumn('SELECT id FROM '.tablename(GARCIA_PREFIX."bang")." where weid=".$this->weid." AND mid=".$mid." and fid=".$_GPC['fid']);

   if(!empty($__mid)){
        message('你已经证实过，无需重复证实',referer(),'error');
   }
   $data = array(
     'weid'=>$this->weid,
     'fid'=>$_GPC['fid'],
     'prove_relation'=>$prove_relation,
     'prove_name'=>$prove_name,
     'prove_desc'=>$prove_desc,
     'upbdate'=>time(),
     'mid'=>$mid
   );

   pdo_insert(GARCIA_PREFIX."bang",$data);
   message('操作成功',referer(),'success');
   exit;
 }
 else if($dopost=='finish'){
    /**
     * 筹款模板
     */
     $id = $_GPC['id'];
     $sql = "SELECT a.id,a.status,a.pid,a.name,a.tar_monet,b.project_name,b.project_logo,b.yongjin,c.nickname,c.openid,c.headimgurl as avatar,b.project_plus5 as is_diy,a.m_bili,c.openid,c.id as mid FROM ".tablename(GARCIA_PREFIX."fabu")." a
     LEFT JOIN ".tablename(GARCIA_PREFIX."project")." b on a.pid=b.id
     LEFT JOIN ".tablename(GARCIA_PREFIX."member")." c on a.mid=c.id
     where a.weid=".$this->weid." and a.id=".$id;
     $_conf = pdo_fetch($sql);
     $token = sha1(time().GARCIA_MD5);
     $is_diy = $_conf['is_diy'];

     $platrrom = $this->_gplatfromuser($_conf['openid']);
      $puid = $platrrom['uid'];
      $mid = $_conf['mid'];
      // $puid = $_confg['uid'];
    //  exit;
     /**
      * 获取实际筹款金额
      */
      //防止错误
      if(empty($puid)){
        message('抱歉，你并没有注册该平台会员或获取uid失败。请联系管理员','','error');
      }
      $_smoney = pdo_fetchcolumn('SELECT SUM(fee) FROM '.tablename(GARCIA_PREFIX."paylog")." where weid=".$this->weid." and fid=".$id." and status=1 and type=0");
      if($is_diy==1){
          $_conf['yongjin'] = $_conf['m_bili'];
          $sj = $_smoney*($_conf['yongjin']/100);
          $yongjin  = 0;
      }else{
        $sj = $_smoney-($_smoney*($_conf['yongjin']/100));
            $yongjin  = $_smoney*($_conf['yongjin']/100);
      }
      $money = $sj ;
      $sj = number_format($sj,2);
     if($_GPC['action']=='complate'){

         $_paylog  = array(
           'weid'=>$this->weid,
           'uid'=>$puid,
           'upbdate'=>time(),
           'status'=>1,
           'fid'=>$_conf['id'],
           'fee'=>$money,
           'shouxufei'=>$yongjin,
           'mid'=>$mid,
           'type'=>7,
           'msg'=>'筹款资金申请中'
         );

        if($_conf['status']==9){
                  message('请不要重复操作',$this->createMobileUrl('index'),'error');
        }
        $openids = $this->_gManers();
        $nickname = $this->_GetMemberName($_conf['mid']);
        $message =$this->sys['sitename']."系统消息:\nid:[".$_conf['mid']."],昵称:[".$nickname."]的用户在".date('Y-m-d H:i:s')." 申请领取筹款资金，请到尽快到后台进行审核!";
        $this->_SendTxts($message,$openids);
        pdo_insert(GARCIA_PREFIX."paylog",$_paylog);
        pdo_update(GARCIA_PREFIX."fabu",array('status'=>9),array('id'=>$id));//申请审核
        message('申请审核提交成功',$this->createMobileUrl('member'),'success');
       exit;
     }

     include $this->template('detail/finish');
     exit;
 }else if($dopost=='save_geren'){
        $fid = $_GPC['fid'];
        $idcar = $_GPC['idcar'];
        $zhengm = $_GPC['zhengm'];
        $creator_name = $_GPC['creator_name'];
        $creator_id = $_GPC['creator_id'];
        $sz_creator_id = $_GPC['sz_creator_id'];
        $hospital = $_GPC['hospital'];
        $creator_phone = $_GPC['creator_phone'];
        $sz_name = $_GPC['sz_name'];
        $data = array(
           'weid'=>$this->weid,
           'fid'=>$fid,
           'idcar'=>implode(',',$idcar),
           'zhengm'=>implode(',',$zhengm),
           'creator_name'=>$creator_name,
           'creator_id'=>$creator_id,
           'creator_phone'=>$creator_phone,
           'upbdate'=>time(),
           'sz_creator_id'=>$sz_creator_id,
           'hospital'=>$hospital,
           'sz_name'=>$sz_name,
           'status'=>0,
           'creator_relation' => $_GPC['creator_relation'],
           'disease' => $_GPC['disease']
        );
        if(!empty($_GPC['id'])){
           pdo_update(GARCIA_PREFIX."shouchishenfenz",$data,array('id'=>$_GPC['id']));
        }else{
           pdo_insert(GARCIA_PREFIX."shouchishenfenz",$data);
        }
        echo "<script language='javascript'   type='text/javascript'>";
        echo "window.location.href='".$this->createMobileUrl('detail',array('display'=>'shenheing','fid'=>$fid))."'";
        echo "</script>";
   exit;
 }
 else if($dopost=='save_zuzhi'){
        $fid = $_GPC['fid'];
        $zuzhi = $_GPC['zuzhi'];
        $zhengm = $_GPC['zhengm'];
        $zuzhir_name = $_GPC['zuzhir_name'];
        $zuzhi_info = $_GPC['zuzhi_info'];
        $data = array(
           'weid'=>$this->weid,
           'fid'=>$fid,
           'zuzhi'=>implode(',',$zuzhi),
           'zhengm'=>implode(',',$zhengm),
           'zuzhir_name'=>$zuzhir_name,
           'zuzhi_info'=>$zuzhi_info,
           'upbdate'=>time(),
           'status'=>0,
           'type'=>1
        );
        if(!empty($_GPC['id'])){
           pdo_update(GARCIA_PREFIX."shouchishenfenz",$data,array('id'=>$_GPC['id']));
        }else{
           pdo_insert(GARCIA_PREFIX."shouchishenfenz",$data);
        }

        echo "<script language='javascript'   type='text/javascript'>";
        echo "window.location.href='".$this->createMobileUrl('detail',array('display'=>'shenheing','fid'=>$fid))."'";
        echo "</script>";
   exit;
 }else if($dopost=='load_list_to_support'){

  $page = max(1,$_GPC['page']);
  if($this->sys['supr_rank']==0){
     $_order = 'order by a.upbdate desc,a.id desc';
  }else{
     $_order = 'order by a.fee desc,a.id desc';
  }

   $_slist= pdo_fetchall('SELECT a.fee,a.msg,a.id,b.nickname,b.headimgurl as avatar,a.upbdate,a.wantSupportTel FROM '.tablename(GARCIA_PREFIX."paylog")." a
   LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b  on a.mid=b.id
   where a.weid=".$this->weid." and a.fid=".$_GPC['id']." and a.status=1 and a.type=0   ".$_order." limit ".($page-1)*$pazie.",".$pazie);

    foreach ($_slist as $key => $value) {
      if($value['nickname']){
          $_slist[$key]['nickname']=urldecode($value['nickname']);
      }
      if($value['upbdate']){
          $_slist[$key]['upbdate']=$this->_format_date($value['upbdate']);
      }
      if($value['avatar']){
          $_slist[$key]['avatar']=  tomedia($_slist[$key]['avatar']);
      }
       $temp = '';
        $sql = 'SELECT  a.*,b.nickname FROM '.tablename(GARCIA_PREFIX.'message')." a
        LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on b.id = a.mid
         where a.weid=".$this->weid." AND a.payid=".$value['id']." and a.fid=".$_GPC['id'];
         $temp =pdo_fetchall($sql);
         // echo $value['id'];

           # code...

         if(!empty($temp)){
            foreach ($temp as $k => $v) {
               if($v['mesid']!=''){
                   $_a.=('
                   <a class="publish_comment publish-comment_shadow-task" href="javascript:void(0)" >
                     <strong data-pid="'.$value['id'].'" data-nickname="'.$v['nickname'].'" data-tmid ="'.$v['mid'].'" data-mesid="'.$v['id'].'" onclick="_replay($(this))">
                     '.urldecode($v['nickname']).'</strong>回复<strong>'.$this->_GetMemberName($v['tmid']).'</strong> : '.$v['content'].'</a>');
             }else{
              $_a.=('
                  <a class="publish_comment publish-comment_shadow-task" href="javascript:void(0)" >
               <strong data-pid="'.$value['id'].'" data-nickname="'.$v['nickname'].'" data-tmid ="'.$v['mid'].'" data-mesid="'.$v['id'].'" onclick="_replay($(this))">'.urldecode($v['nickname']).': </strong>'.$v['content'].'</a>
               ');
             }
           }

            $_slist[$key]['mess']= $_a;
         }else{
             $_slist[$key]['mess']= '';
         }

    }
    $data['list']= $_slist;
    $data['total'] = count($_slist);
    die(json_encode($data));
 }


   if($display=='yan_list'){
        $id = $_GPC['id'];
        $_c = pdo_fetch('SELECT id,status,type FROM '.tablename(GARCIA_PREFIX."shouchishenfenz")." where weid=".$this->weid." AND fid=".$id);
        if($_c){

          if($_c['status']==0){
            //  message('项目认证审核中',$this->createMobileUrl('detail',array('id'=>$id)),'success');
            echo "<script language='javascript'   type='text/javascript'>";
            echo "window.location.href='".$this->createMobileUrl('detail',array('display'=>'shenheing','fid'=>$id))."'";
            echo "</script>";
          }else if($_c['status']==1){
            echo "<script language='javascript'   type='text/javascript'>";
            echo "window.location.href='".$this->createMobileUrl('detail',array('display'=>'shenheingsuccess','fid'=>$id))."'";
            echo "</script>";
          }else if($_c['status']==3){
            if($_c['type']==1){
              echo "<script language='javascript'   type='text/javascript'>";
              echo "window.location.href='".$this->createMobileUrl('detail',array('display'=>'zuzhi','fid'=>$id,'id'=>$_c['id']))."'";
              echo "</script>";
            }else{
              echo "<script language='javascript'   type='text/javascript'>";
              echo "window.location.href='".$this->createMobileUrl('detail',array('display'=>'geren','fid'=>$id,'id'=>$_c['id']))."'";
              echo "</script>";
            }

          }

        }
      //  exit;
   }else if($display=='hospital'){
       $item = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."hospital")." where weid=".$this->weid." AND fid=".$_GPC['fid']);
       if(!empty($item['idcar'])){
         $pic = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id in(".$item['idcar'].")");
       }

   }
   else if($display=='shenheingsuccess'){
        $_item = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."shouchishenfenz")." where weid=".$this->weid." AND fid=".$_GPC['fid']);
   }
   else if($display=='zuzhi'){
         $id = $_GPC['id'];
         if(!empty($id)){
           $item = pdo_fetch('SELECT  * FROM '.tablename(GARCIA_PREFIX."shouchishenfenz")." where weid=".$this->weid." and id=".$id);

           $item1 = explode(',',$item['zuzhi']);
           $item1 =array_filter($item1);
           $item1 = implode(',',$item1);

           $item2 = explode(',',$item['zhengm']);
           $item2 =array_filter($item2);
           $item2 = implode(',',$item2);

           $pic1 = pdo_fetchall('SELECT id,thumb FROM '.tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id in(".$item1.")");
           $pic2 = pdo_fetchall('SELECT id,thumb FROM '.tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id in(".$item2.")");
         }


   }
   else if($display=='geren'){
         $id = $_GPC['id'];
         if(!empty($id)){
         $item = pdo_fetch('SELECT  * FROM '.tablename(GARCIA_PREFIX."shouchishenfenz")." where weid=".$this->weid." and id=".$id);

         $item1 = explode(',',$item['idcar']);
         $item1 =array_filter($item1);
         $item1 = implode(',',$item1);

         $item2 = explode(',',$item['zhengm']);
         $item2 =array_filter($item2);
         $item2 = implode(',',$item2);

         $pic1 = pdo_fetchall('SELECT id,thumb FROM '.tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id in(".$item1.")");
         $pic2 = pdo_fetchall('SELECT id,thumb FROM '.tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id in(".$item2.")");
       }
   }
  else if($display=='zhiqingzhe'){
     $fid = $_GPC['fid'];
     $sql = "SELECT a.*,b.nickname,b.headimgurl as avatar FROM ".tablename(GARCIA_PREFIX."bang")." a
     LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
     where a.weid=".$this->weid." and a.fid=".$fid;
     $_list = pdo_fetchall($sql);
  }
  else if($display=='detail'){

       $sql = "SELECT a.*,b.nickname,b.headimgurl as avatar,b.is_shouc,a.status,a.mid,a.deliveryTime,a.yunfei FROM ".tablename(GARCIA_PREFIX.'fabu')." a
       LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
       where a.weid=".$this->weid." and a.id=".$_GPC['id'];

       $is_share = $_GPC['is_share'];
       if($is_share==1){
          //判断是否已经记录过
          $is_mark = pdo_fetchcolumn('SELECT id FROM '.tablename(GARCIA_PREFIX.'sharelist')." where weid=".$this->weid." and mid=".$mid." and fid=".$_GPC['id']);
          if(!$is_mark){
              $share = pdo_fetchcolumn('SELECT is_share FROM '.tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id=".$_GPC['id']);
              $share  = (int)$share+1;
              pdo_update(GARCIA_PREFIX."fabu",array('is_share'=>$share),array('id'=>$_GPC['id']));
              pdo_insert(GARCIA_PREFIX."sharelist",array('weid'=>$this->weid,'fid'=>$_GPC['id'],'mid'=>$mid,'upbdate'=>time()));
          }

       }

       $config = pdo_fetch($sql);
       if(!$config||$config['status']==6){
         echo "<script language='javascript'   type='text/javascript'>";
         echo "window.location.href='".$this->createMobileUrl('fsuccess',array('dopost'=>'del','fid'=>$_GPC['id']))."'";
         echo "</script>";
       }else if($config['status']==4&&$mid!=$config['mid']){
         echo "<script language='javascript'   type='text/javascript'>";
         echo "window.location.href='".$this->createMobileUrl('fsuccess',array('dopost'=>'shenheing','fid'=>$_GPC['id']))."'";
         echo "</script>";
       }
       $project = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX.'project')." where weid=".$this->weid." and id=".$config['pid']);

       //Is project is true
       if($project['is_hospital']==1){
         $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."hospital")." where weid=".$this->weid." and fid=".$_GPC['id'];
          $_shouchi = pdo_fetch($sql);

          $sql = "SELECT a.*,b.nickname,b.headimgurl as avatar FROM ".tablename(GARCIA_PREFIX."bang")." a
          LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
          where a.weid=".$this->weid." and a.fid=".$_GPC['id']." order by a.id desc";
          $zhengshi = pdo_fetchall($sql);

           $_iszhengshi = pdo_fetchcolumn('SELECT id FROM '.tablename(GARCIA_PREFIX."bang")." where weid=".$this->weid." and mid=".$mid);
          // echo $mid;
       }
       $ids = json_decode($config['thumb'],true);
       foreach ($ids as $k3 => $v3) {
         if(empty($v3))continue;
         if($k3==0){
            $_id.=$v3;
         }else{
            $_id.=",".$v3;
         }
       }
       if(!empty($_id)){
         $_th = pdo_fetchall("SELECT thumb,pic FROM ".tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id in(".$_id.")");
        foreach ($_th as $k4 => $v4) {
           $_thb[$k4]['pic'] = $v4['pic'];
           $_thb[$k4]['thumb'] = $v4['thumb'];
        }
       $thumb = $_thb;
       }

      $cur_data = date('Y-m-d',time());
      $less = $this->diffDate($cur_data,$config['rand_day']);

      $is_shouc = pdo_fetchcolumn('SELECT is_shouc FROM '.tablename(GARCIA_PREFIX.'member')." where weid=".$this->weid." AND id='".$mid."'");
      $is_shouc = explode(',',$is_shouc);
       if($project['project_plus4']==1||$project['project_plus5']==1){
          $_plist = json_decode($config['reward'],true);
       }

      if(in_array($_GPC['id'],$is_shouc)){
        $_gz = true;
      }else{
        $_gz = false;
      }

      if($config['is_get']==2){
        $is_get = 1;
      }else{
         $is_get = pdo_fetchcolumn('SELECT type FROM '.tablename(GARCIA_PREFIX."paylog")." where weid=".$this->weid." and fid=".$config['id']." and status =1 order by id desc") ;
         if($is_get==6){
             $is_get = 1;
         }
      }


      /**
       * 获取支持次数
       */
      $_count = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$_GPC['id']." and status=1 and type=0 ");
      $_sum = pdo_fetchcolumn('SELECT sum(fee) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$_GPC['id']." and status=1 and type=0 ");
      $wb_title = mb_substr(strip_tags(htmlspecialchars_decode($config['project_texdesc'])),0,150,'utf-8');
      $wb_title = $wb_title = str_replace(array("\r\n", "\r", "\n"), "",$wb_title).$this->sys['sitename'];
      $wb_url = $_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('id'=>$_GPC['id'],'is_share'=>1)),2);
        $wb_pic = !empty($thumb[0]['pic'])?$thumb[0]['pic']:$this->sys['share_img'];


       /**
        * 更新状态
        */
      $_uplist = pdo_fetchall('SELECT a.*,b.nickname,b.headimgurl as avatar FROM '.tablename(GARCIA_PREFIX.'update')." a
      LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
      where a.weid=".$this->weid."  and a.fid=".$_GPC['id']." order by id desc");



      $qqzon_title = empty($this->sys['qqzon_content'])?$config['name']:$this->sys['qqzon_content'];
      $qqzon_url = empty($this->sys['qqzon_url'])?$_W['siteroot'].'app/'.substr($this->createMobileUrl('detail',array('id'=>$_GPC['id'])),2):$this->sys['qqzon_url'];
      $qqzon_pic = empty($this->sys['qqzon_pic'])?$thumb[0]:$this->sys['qqzon_pic'];

      $qqweibo_title = empty($this->sys['qqweibo_content'])?$config['name']:$this->sys['qqweibo_content'];
      $qqweibo_url = empty($this->sys['qqweibo_url'])?$_W['siteroot'].'app/'.substr($this->createMobileUrl('detail',array('id'=>$_GPC['id'])),2):$this->sys['qqweibo_url'];
      $qqweibo_pic = empty($this->sys['qqweibo_pic'])?$thumb[0]:$this->sys['qqweibo_pic'];

      $qq_title = empty($this->sys['qq_content'])?$config['name']:$this->sys['qq_content'];
      $qq_url = empty($this->sys['qq_url'])?$_W['siteroot'].'app/'.substr($this->createMobileUrl('detail',array('id'=>$_GPC['id'])),2):$this->sys['qq_url'];
      $qq_pic = empty($this->sys['qq_pic'])?$thumb[0]:$this->sys['qq_pic'];




      if($this->sys['supr_rank']==0){
         $_order = 'order by a.upbdate desc,a.id desc';
      }else{
         $_order = 'order by a.fee desc,a.id desc';
      }

        $_slist = pdo_fetchall('SELECT a.fee,a.msg,a.id,b.nickname,b.headimgurl as avatar,a.upbdate,a.wantSupportTel FROM '.tablename(GARCIA_PREFIX."paylog")." a
        LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b  on a.mid=b.id
        where a.weid=".$this->weid." and a.fid=".$_GPC['id']." and a.status=1 and a.type=0 ".$_order." limit 0,".$pazie);
         foreach ($_slist as $key => $value) {
            $temp = '';
             $sql = 'SELECT  a.*,b.nickname FROM '.tablename(GARCIA_PREFIX.'message')." a
             LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on b.id = a.mid
              where a.weid=".$this->weid." AND a.payid=".$value['id']." and a.fid=".$_GPC['id'];
              $temp =pdo_fetchall($sql);
              // echo $value['id'];
              if(!empty($temp)){
                $_mess[$value['id']] = $temp;
              }

         }

        $_share['weibo'] = "http://v.t.sina.com.cn/share/share.php?title=".$wb_title."&url=".urlencode($wb_url).'&content=utf-8&sourceUrl='.$wb_url.'&pic='.urlencode(tomedia($wb_pic));
        $_share['qqzon'] = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?title='.$wb_title.'&summary='.urlencode($wb_title).'&url='.urlencode($wb_url).'&pics='.tomedia($wb_pic);
        $_share['qqweibo']= 'http://v.t.qq.com/share/share.php?title='.$wb_title.'&url='.urlencode($wb_url).'&pic='.tomedia($wb_pic);
        $_share['qq']='http://connect.qq.com/widget/shareqq/index.html?title='.$wb_title.'&url='.urlencode($wb_url)."&pics=".tomedia($wb_pic);


        /**
         * 判断是否有支持
         */
    if($this->modal!='pc'&&$this->modal!='phone'){
         $is_f = pdo_fetchcolumn('SELECT a.id FROM '.tablename(GARCIA_PREFIX."paylog")." a
         LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b  on a.uid=b.id
         where a.weid=".$this->weid." and a.fid=".$_GPC['id']." and a.status=1 and mid=".$mid." order by a.id desc");
       }
        pdo_update(GARCIA_PREFIX.'fabu',array('views'=>($config['views']+1)),array('id'=>$config['id']));
        $manger = $this->_isManger($mid);
        $is_manger = $manger['is_manger'];
  }
        // var_dump($manger);

    include $this->template('detail/'.$display);
}

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

  function _gdream($id){
     global $_W;
     $sql = "SELECT COUNT(id) FROM ".tablename(GARCIA_PREFIX."paylog")." where weid=".$_W['uniacid']." and status=1 and dream_id='".$id."'";

     return pdo_fetchcolumn($sql);
  }
  function _getpic($id){
      global $_W;
      if(empty($id)){
        return '无';
      }
     return pdo_fetchcolumn('SELECT pic FROM '.tablename(GARCIA_PREFIX."photo")." where weid=".$_W['uniacid']." and id=".$id);
  }
  function _getthumb($id){
    global $_W;
    if(empty($id)){
      return '无';
    }
   return pdo_fetchcolumn('SELECT thumb FROM '.tablename(GARCIA_PREFIX."photo")." where weid=".$_W['uniacid']." and id=".$id);
  }
 ?>
