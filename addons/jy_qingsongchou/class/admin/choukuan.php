<?php


load()->model('mc');
$display = empty($_GPC['display'])?'list':$_GPC['display'];
$dopost = $_GPC['dopost'];
$do = empty($_GPC['do'])?'choukuan':$_GPC['do'];
$this->_wapi();

   if($dopost=='save_fabu'){

     $__id = $this->httpcopy($_GPC['thumb']);
    //  echo $_GPC['id'];
    if(!empty($_GPC['id'])){
      // $mid = pdo_fetchcolumn('SELECT a.id FROM '.tablename(GARCIA_PREFIX."member")."  a  LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." b on a.openid=b.openid where a.weid=".$this->weid." and b.id =".$_GPC['id']);
    }

      $ps = $_GPC['ps'];
      if(!empty($ps)){
          // $openids = $_GPC['openids'];
          if(empty($_GPC['mid'])){
                message('请输入发布人id',referer(),'error');
          }
      }
      if(empty($_GPC['name'])){
            message('请输入筹款名称',referer(),'error');
      }

     if(empty($_GPC['pid'])){
            message('请选择项目',referer(),'error');
     }

    $modal = $_GPC['modal'];

    $reward_id = $_GPC['reward_id'];

    $reward_money = $_GPC['reward_money'];
    $reward_content = $_GPC['reward_content'];
    $reward_limit = $_GPC['reward_limit'];
    $reward_thumb = $_GPC['reward_thumb'];
    $reward_title = $_GPC['reward_title'];
    if(is_array($reward_id)){
       foreach ($reward_id as $key => $value) {
            $reward[$value]['supportNumber'] =$reward_money[$key];
            $reward[$value]['supportContent']= $reward_content[$key];
            $reward[$value]['thumb']= $reward_thumb[$key];
            $reward[$value]['places'] = $reward_limit[$key];
            $reward[$value]['title'] = $reward_title[$key];
            $reward[$value]['tid'] = $value;
       }
    }

          if (version_compare(PHP_VERSION,'5.4.0','<')) {
            $reward = json_encode($reward);
            $reward = preg_replace_callback("#\\\u([0-9a-f]{4})#i",function($matchs){
                                      return iconv('UCS-2BE', 'UTF-8', pack('H4', $matchs[1]));
                                 },
                                  $reward
                               );
          }else{
              $reward = json_encode($reward, JSON_UNESCAPED_UNICODE);
          }


     if(is_array($__id)){


       foreach ($__id as $key => $value) {
          if($key<4){
              $cover_thumb[$key] = $value;
          }
          $thumb[$key] = $value;
       }
       $thumb = json_encode($thumb);
       $cover_thumb = json_encode($cover_thumb);
     }
     $tar_monet = $_GPC['tar_monet'];

     $dream_money = $_GPC['dream_money'];
     $dream_content = $_GPC['dream_content'];
     $dream_id = $_GPC['dream_id'];

     if(is_array($dream_money)){
           $tar_monet = 0;
           foreach ($dream_money as $key => $value) {
               $tar_monet =$tar_monet+$value;
               $_dream[$key]['money']= $value;
               $_dream[$key]['content']= $dream_content[$key];
               $_dream[$key]['dream_id']= $dream_id[$key];
           }
           $_dream = json_encode($_dream);
          //  $_dream = str_replace(array('[',']'),'',$_dream);
     }
    //  var_dump($_dream);
    //  var_dump($reward);
    //  exit;
     $_GPC['project_texdesc'] = preg_replace("/style=.+?['|\"]/i",'',htmlspecialchars_decode($_GPC['project_texdesc']));
     $_GPC['project_texdesc'] = htmlentities( $_GPC['project_texdesc'],ENT_COMPAT,'UTF-8');


     $data = array(
       'tar_monet'=>$tar_monet,
       'project_texdesc'=>$_GPC['project_texdesc'],
       'name'=>$_GPC['name'],
       'use'=>$_GPC['use'],
       'cur_day'=>$_GPC['cur_day'],
        'cover_thumb'=>$cover_thumb,
        'thumb'=>$thumb,
        'rand_day'=>$_GPC['rand_day'],
        'detail'=>empty($_GPC['detail'])?mb_substr(strip_tags(htmlspecialchars_decode($_GPC['project_texdesc'])),0,90,'utf8'):$_GPC['detail'],
        'pid'=>$_GPC['pid'],
        'is_p'=>$_GPC['is_p'],
        'is_secret'=>$_GPC['is_secret'],
        'has_sh'=>$_GPC['has_sh'],
        'dream'=>$_dream,
        'reward'=>$reward,
        'm_bili'=>$_GPC['m_bili'],
        'videourl'=>$_GPC['videourl'],
        'fxts'=>$_GPC['fxts'],
        'mupbdate'=>time()
     );

     $data['mid'] = $_GPC['mid'];

     if(empty($_GPC['id'])){
         $data['status']=-1;
         $data['upbdate']=time();
         $data['weid']=$this->weid;
         $data['is_admin']=1;
         pdo_insert(GARCIA_PREFIX."fabu",$data);
          message('发布成功',$this->createWebUrl('choukuan',array('display'=>'background')),'success');
     }else{
       if($ps==='back'){
          $data['status']=1;
       }
          pdo_update(GARCIA_PREFIX.'fabu',$data,array('id'=>$_GPC['id']));
         message('编辑成功',referer(),'success');
     }

     exit;
   }
   else if($dopost=='hexie'){
       $id= $_GPC['id'];

       pdo_update(GARCIA_PREFIX."paylog",array('msg'=>'支持'),array('id'=>$id));
       message('操作成功',referer(),'success');
       exit;
   }else if($dopost=='delmesg'){
      $id= $_GPC['id'];
      pdo_delete(GARCIA_PREFIX."message",array('id'=>$id));
      message('删除成功',referer(),'success');
     exit;
   }
   else if($dopost=='toexcel'){
     $id =$_GPC['id'];
     $_list = pdo_fetchall('SELECT a.*,b.nickname,b.headimgurl as avatar2,b.tel,b.mobile,c.name as ad_name,c.province,c.city,c.area,b.is_roob FROM '.tablename(GARCIA_PREFIX.'paylog')." a
     LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
     LEFT JOIN ".tablename(GARCIA_PREFIX."address")." c on a.address_id=c.id
     LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." d on d.id=a.fid
     WHERE a.weid=".$this->weid." and a.fid=".$id." and a.status=1 and a.type=0 order by upbdate desc ");
     $title  = array(
         array(
            'name'=>'昵称',
            'width'=>'10',
         ),
         array(
            'name'=>'支持金额',
            'width'=>'10',
         ),
         array(
            'name'=>'时间',
            'width'=>'10',
         ),
         array(
            'name'=>'留言',
            'width'=>'10',
         ),
         array(
            'name'=>'支持电话',
            'width'=>'20',
         ),
         array(
            'name'=>'联系姓名',
            'width'=>'10',
         ),
     );
     $sql = "SELECT name FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." AND id=".$id;
     $pname = pdo_fetchcolumn($sql);
     $name=  '【'.$pname.'】支持列表';
     foreach ($_list as $key => $row) {
         $data[]= array(
            'nickname'=>urldecode($row['nickname']),
            'fee' =>$row['fee'],
            'upbdate'=>date('Y-m-d',$row['upbdate']),
            'msg'=>$row['msg'],
            'tel'=>$row['wantSupportTel']==0?'无预留':$row['wantSupportTel'],
            'name'=>!empty($row['wantSupportName'])?$row['wantSupportName']:'无名氏',
         );
     }
    $this-> _pushExcel($title,$data,$name);
     exit;
   }
   else if($dopost=='search_member'){
     if($_GPC['mid']){
         $sql = "SELECT id,openid,nickname,headimgurl FROM ".tablename(GARCIA_PREFIX."member")." where weid = ".$this->weid." and is_roob=0 and id= ".$_GPC['mid'];
     }
     else{
          $sql = "SELECT id,openid,nickname,headimgurl FROM ".tablename(GARCIA_PREFIX."member")." where weid = ".$this->weid." and is_roob=0 ";
     }
     $list = pdo_fetchall($sql);
     foreach ($list as $key => $value) {
           foreach ($value as $k => $v){
               if($k=='nickname'){
                    $list[$key][$k] = urldecode($v);
               }
               if($k=='headimgurl'){
                 $list[$key][$k] = tomedia($v);
               }
           }
     }
     $_list = $list;
     $data = array(
       'total'=>count($list),
       'list'=>$_list,
     );
     die(json_encode($data));
   }
   else if($dopost=='done_renz'){
       $id = $_GPC['id'];
        $status = $_GPC['status'];

       pdo_update(GARCIA_PREFIX."shouchishenfenz",array('status'=>$status),array('id'=>$id));
      message('操作成功',$this->createWebUrl('choukuan'),'success');
      exit;
   }

   else if($dopost=='done_hospital'){
       $id = $_GPC['id'];
        $status = $_GPC['status'];

       pdo_update(GARCIA_PREFIX."hospital",array('status'=>$status),array('id'=>$id));
      message('操作成功',$this->createWebUrl('choukuan'),'success');
      exit;
   }
   else if($dopost=='del'){
      $id=$_GPC['id'];
      pdo_update(GARCIA_PREFIX.'fabu',array('status'=>6),array('id'=>$_GPC['id']));
      message('删除成功,如有支持者请到退款管理进行退款操作',referer(),'success');
   }else if($dopost=='huanyuan'){
      $id=$_GPC['id'];
      pdo_update(GARCIA_PREFIX.'fabu',array('status'=>1),array('id'=>$_GPC['id']));
      message('已还原',referer(),'success');
   }else if($dopost=='shenhe'){
     $id=$_GPC['id'];
       pdo_update(GARCIA_PREFIX.'fabu',array('status'=>1),array('id'=>$_GPC['id']));
      message('审核成功',referer(),'success');
   }else if($dopost  == 'fengt'){
     $id=$_GPC['id'];
      pdo_update(GARCIA_PREFIX.'fabu',array('status'=>5),array('id'=>$_GPC['id']));
      message('封停成功',referer(),'success');
   }else if($dopost=='uprank'){
      pdo_update(GARCIA_PREFIX.'fabu',array('rank'=>$_GPC['rank']),array('id'=>$_GPC['id']));
      die(json_encode(array('status'=>1)));
   }else if($dopost=='uptuijian'){
      pdo_update(GARCIA_PREFIX.'fabu',array('is_p'=>$_GPC['p']),array('id'=>$_GPC['id']));
      die(json_encode(array('status'=>1)));
   }else if($dopost=='jujue'){
     pdo_update(GARCIA_PREFIX.'fabu',array('status'=>8),array('id'=>$_GPC['id']));
     message('项目已被拒绝',referer(),'success');
   }
   else if($dopost=='save_shenhe'){
     $shenhe = $_GPC['shenhe'];
     if(empty($shenhe)){
       message('请选择要审核的项目',referer(),'error');
     }
     foreach ($shenhe as $key => $value) {
          pdo_update(GARCIA_PREFIX."fabu",array('status'=>1),array('id'=>$value));
          pdo_update(GARCIA_PREFIX."shouchishenfenz",array('status'=>1),array('fid'=>$value));
     }
     message('审核完成',referer(),'success');
     die();
   }else if($dopost=='excel_do'){
     $id = $_GPC['id'];
     $_list = pdo_fetchall('SELECT a.id,b.nickname,a.fee,b.tel,c.name as ad_name,c.province,c.city,c.area,c.address FROM '.tablename(GARCIA_PREFIX.'paylog')." a
     LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
     LEFT JOIN ".tablename(GARCIA_PREFIX."address")." c on a.address_id=c.id
     WHERE a.weid=".$this->weid." and a.fid=".$id." and a.status=1 and a.type=0");
    $title = array(
      array(
        'name'=>'ID',
        'width'=>'10'
      ),
      array(
        'name'=>'昵称',
        'width'=>'20'
      ),
      array(
        'name'=>'支持金额',
        'width'=>'10'
      ),
      array(
        'name'=>'联系电话',
        'width'=>'10'
      ),
      array(
        'name'=>'姓名',
        'width'=>'10'
      ),
      array(
        'name'=>'省份',
        'width'=>'20'
      ),
      array(
        'name'=>'城市',
        'width'=>'20'
      ),
      array(
        'name'=>'区域',
        'width'=>'20'
      ),
      array(
        'name'=>'详细地址',
        'width'=>'30'
      ),
    );
    //数据处理
    foreach ($_list as $key => $value) {
       foreach ($value as $k => $v) {
           if($k=='nickname'){
              $_list[$key][$k] = urldecode($v);
           }
       }
    }
// var_dump($_list);
     $this->_pushExcel($title,$_list,'筹款支持');
     exit;
   }else if($dopost=='ajax_tomedia'){
      die(__tomedia($_GPC['url']));
   }else if($dopost =='choukuan_access'){
         $acc_json = $this->jsonfile;
         $token = $this->wapi->getAccessToken($acc_json);

         $id = $_GPC['id'];
         $sql = "SELECT  a.id,a.name,b.fee,b.shouxufei,b.mid,a.pid,b.id as payid,d.openid FROM ".tablename(GARCIA_PREFIX."fabu")." a
         LEFT JOIN ".tablename(GARCIA_PREFIX."paylog")." b on a.id=b.fid
         LEFT JOIN ".tablename(GARCIA_PREFIX."member")." d on a.mid=d.id
         where a.weid=".$this->weid." and a.id=".$id." and b.type=7";
         $_con = pdo_fetch($sql);

         $_yjarr = array(
           'weid'=>$this->weid,
           'mid'=>$_con['mid'],
           'fid'=>$id,
           'pid'=>$_con['payid'],
           'fee'=>$_con['shouxufei'],
           'user_fee'=>$_con['fee'],
           'upbdate'=>time()
         );

         //发送消息
         if($this->sys['kf_cksuccess_type']==1){
           $acc_json = $this->jsonfile;
           $token = $this->wapi->getAccessToken($acc_json);
             $temp = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX."temp")." where weid=".$this->weid." and id=".$this->sys['kf_cksuccess_tmp']);
              $temp_id = $temp['tempid'];
              // $_url = $_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('id'=>$fid)),2);
              // $url =  str_replace("{url}",$_url,$temp['url']);
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
               $a = $this->wapi->sendTemplate($_con['openid'],$temp_id,$url,$token,$parama);
         }else{
             $message = $this->sys['kf_cksuccess_content'];
              $_res = $this->wapi->sendText($_con['openid'],urlencode($message),$token);
         }

         pdo_update(GARCIA_PREFIX."paylog",array('type'=>6,'msg'=>'资金发放成功'),array('id'=>$_con['payid']));
         pdo_insert(GARCIA_PREFIX."yongjin",$_yjarr);

        //  $result = mc_credit_update($_con['uid'], 'credit2',$_con['fee'],array(0=>'筹款成功到账'));
          $wallet = pdo_fetchcolumn('SELECT wallet FROM  '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$_con['mid']);
          $wallet = $wallet + $_con['fee'];
          pdo_update(GARCIA_PREFIX."member",array('wallet'=>$wallet),array('id'=>$_con['mid']));
         pdo_update(GARCIA_PREFIX."fabu",array('status'=>2,'is_get'=>1),array('id'=>$id));//申请审核
         message('筹款发送成功',referer(),'success');
   }else if($dopost =='choukuan_fail'){
           $acc_json = $this->jsonfile;
           $token = $this->wapi->getAccessToken($acc_json);
          $id = $_GPC['id'];
          $sql = "SELECT  b.id as payid,d.openid FROM ".tablename(GARCIA_PREFIX."fabu")." a
          LEFT JOIN ".tablename(GARCIA_PREFIX."paylog")." b on a.id=b.fid
          LEFT JOIN ".tablename(GARCIA_PREFIX."member")." d on a.mid=d.id
          where a.weid=".$this->weid." and a.id=".$id." and b.type=7";
          $_con = pdo_fetch($sql);
          //发送消息
          if($this->sys['kf_ckfail_type']==1){
              $temp = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX."temp")." where weid=".$this->weid." and id=".$this->sys['kf_ckfail_tmp']);
               $temp_id = $temp['tempid'];
               // $_url = $_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('id'=>$fid)),2);
               // $url =  str_replace("{url}",$_url,$temp['url']);
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
                $a = $this->wapi->sendTemplate($_con['openid'],$temp_id,$url,$token,$parama);
          }else{
              $message = $this->sys['kf_ckfail_content'];
               $_res = $this->wapi->sendText($_con['openid'],urlencode($message),$token);
          }

          pdo_update(GARCIA_PREFIX."paylog",array('type'=>9,'msg'=>'筹款资金审核失败'),array('id'=>$_con['payid']));
          pdo_update(GARCIA_PREFIX."fabu",array('status'=>7),array('id'=>$id));//申请审核
          message('筹款不通过',referer(),'success');
   }else if($dopost=='tuikuan'){
        $id = $_GPC['id'];
        $fid = $_GPC['fid'];
        $acc_json = $this->jsonfile;
        $token = $this->wapi->getAccessToken($acc_json);
        $xiangmu = pdo_fetch('SELECT name FROM '.tablename(GARCIA_PREFIX.'fabu')." where weid=".$this->weid." and id=".$fid);
        $action = $_GPC['action'];
        $_d = array(
             '{name}',
             '{time}',
             '{fee}'
        );
        $_m =  array(
             $xiangmu['name'],
             date('Y-m-d H:i:s',time()),
        );
        if($action=='arr'){

          if($_GPC['todo']!='go'){
               message('准备退款操作，请勿关闭浏览器',$this->createWebUrl('choukuan',array('dopost'=>'tuikuan','fid'=>$fid,'action'=>'arr','todo'=>'go')),'success');
          }
          //批量退款操作
          $pindex = max(1, intval($_GPC['page']));
          $psize =50; //每次50分次推送
          $_next = $pindex+1;
          //获取需要退换租金的用户
          $sql = "SELECT  a.transaction_id,a.fee,a.uid,b.openid FROM ".tablename(GARCIA_PREFIX."paylog")." a
          LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
          where a.weid=".$this->weid." and a.fid=".$fid." and a.status=1 limit ".($pindex - 1) * $psize.','.$psize;
          $_cc = pdo_fetchall($sql);
          $_cur  =($pindex - 1) * $psize;
          $_cur =  $_cur+count($_cc);
          if(count($_cc)<=0){

            pdo_update(GARCIA_PREFIX."fabu",array('tuikuan'=>1),array('id'=>$fid));
            message('资金退换成功',$this->createWebUrl('tuikuan_ctr'),'success');
          }else{
            foreach ($_cc as $key => $value) {
                if($value['transaction_id']==0){
                    $_m[2] = $value['fee'];
                    $result = mc_credit_update($value['uid'], 'credit2',$value['fee'],array(0=>'项目失败退款'));
                    pdo_update(GARCIA_PREFIX."paylog",array('status'=>2),array('id'=>$id));
                }else{
                    $_m[2] = $value['fee'];
                    $data = array(
                      'appid'=>$this->sys['pay_appid'],
                      'mch_id'=>$this->sys['pay_number'],
                      'op_user_id'=>$this->sys['pay_number'],
                      'transaction_id'=>$value['transaction_id'],
                      'total_fee'=>$value['fee']*100,
                      'refund_fee'=>$value['fee']*100,
                    );

                    $_res = $this->wapi->_tuikuan($data,$this->sys['pay_miyao']);
                    if($_res['return_code']=='FAIL'){
                      message($_res['return_msg'],referer(),'error');
                    }else if($_res['return_code']=='SUCCESS'){
                       pdo_update(GARCIA_PREFIX."paylog",array('status'=>2),array('id'=>$id));
                    }
                }

                //推送信息
              $a =  __sendMsg($_d,$_m,$this->sys['kf_tuikuan_type'],$this->sys['kf_tuikuan_content'],$this->sys['kf_tuikuan_tmp'],$fid,$this->wapi,$token,$value['openid']);

            }
          }

        message('已推送'.$_cur.'人，请不要关闭浏览器',$this->createWebUrl('choukuan',array('dopost'=>'tuikuan','page'=>$_next,'fid'=>$fid,'action'=>'arr','todo'=>'go')),'success');
        //  echo $fid;
         exit;
        }else{
          $sql = "SELECT a.fee,a.transaction_id,b.openid,a.tid,a.fid FROM ".tablename(GARCIA_PREFIX."paylog")." a
          LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on b.id=a.mid
          where a.weid=".$this->weid." and a.id=".$id;
          $_conf = pdo_fetch($sql);
          $_fee = $_conf['fee'];
          $_oppenid = $_conf['openid'];
          if($_conf['transaction_id']==0){
              // 钱包退款
              $result = mc_credit_update($value['uid'], 'credit2',$_fee,array(0=>'项目失败退款'));
              pdo_update(GARCIA_PREFIX."paylog",array('status'=>2),array('id'=>$id));
          }else{
                 $data = array(
                   'appid'=>$this->sys['pay_appid'],
                   'mch_id'=>$this->sys['pay_number'],
                   'op_user_id'=>$this->sys['pay_number'],
                   'transaction_id'=>$_conf['transaction_id'],
                   'total_fee'=>$_fee*100,
                   'refund_fee'=>$_fee*100,
                 );

                 $_res = $this->wapi->_tuikuan($data,$this->sys['pay_miyao']);
                 if($_res['return_code']=='FAIL'){
                   message($_res['return_msg'],referer(),'error');
                 }else if($_res['return_code']=='SUCCESS'){
                        pdo_update(GARCIA_PREFIX."paylog",array('status'=>2),array('id'=>$id));
                 }

          }

          $_pda = array(
            'weid'=>$this->weid,
            'uid'=>$value['uid'],
            'mid'=>$value['mid'],
            'upbdate'=>time(),
            'status'=>1,
            'type'=>8,
            'fee'=>$_fee,
            'msg'=>'退款资金'
          );
          if($this->sys['kf_tuikuan_type']==0){
              $message = str_replace($_d,$_m,$this->sys['kf_tuikuan_content']);
              $a = $this->wapi->sendText($_oppenid,urlencode($message),$token);
          }else{
              $temp = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX."temp")." where weid=".$this->weid." and id=".$this->sys['kf_tuikuan_tmp']);
               $temp_id = $temp['tempid'];
               $_url = $_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('id'=>$fid)),2);
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
                $a = $this->wapi->sendTemplate($_oppenid,$temp_id,$url,$token,$parama);
                //
          }
                message('退款成功',referer(),'success');
                _checkFabu($fid);
        }


        exit;

   }else if($dopost=='tiqian'){
        $type = $_GPC['type'];
        $fid = $_GPC['fid'];
        $status = $_GPC['status'];
        $uid = $_GPC['uuid'];
        $openid = pdo_fetchcolumn('SELECT b.openid FROM '.tablename(GARCIA_PREFIX."fabu")." a LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid = b.id where a.weid=".$this->weid." and a.id=".$fid);
        $ftitle = pdo_fetchcolumn('SELECT name FROM '.tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id=".$fid);
      if($type==1){
          pdo_update(GARCIA_PREFIX."fabu",array('status'=>2,'early'=>0),array('id'=>$fid));//更新项目状态
          pdo_update(GARCIA_PREFIX."update",array('status'=>888),array('id'=>$uid));//改变状态
          $message = urlencode('恭喜您,您的项目['.$ftitle.']申请的提前结束已被管理员审核通过').'!<a href="'.$_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('id'=>$fid)),2).'">'.urlencode('点击进入').'</a>';
      }else if($type==2){
          pdo_update(GARCIA_PREFIX."fabu",array('early'=>0),array('id'=>$fid));//更新项目状态
          pdo_update(GARCIA_PREFIX."update",array('status'=>999),array('id'=>$uid));//改变状态
          $message = urlencode('抱歉,您的项目['.$ftitle.']申请的提前结束已被管理员审核拒绝').'!<a href="'.$_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('id'=>$fid)),2).'">'.urlencode('点击进入').'</a>';
      }
      if(!empty($openid)){
        $c= $this->_SendTxt($openid,$message);
      }

      message('操作成功',$this->createWebUrl('choukuan'),'success');
     exit;
   }

   if($_GPC['action']=='e_fabu'){
       $fabu = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id=".$_GPC['id']);
       $_id = json_decode($fabu['thumb'],true);

       foreach ($_id as $k => $v) {
           if(empty($v)){
              continue;
           }else{
              $__id[]= $v;
           }
       }
       $__id = implode(',',$__id);

       if(!empty($__id)){
         $_conf = pdo_fetchall("SELECT * FROM ".tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id in(".$__id.")");
       }
       foreach ($_conf as $key => $value) {

         $thumb[] = str_replace($_W['siteroot']."attachment/",'',$value['pic']);
       }

   }else if($dopost=='add_vie'){
        $num = $_GPC['number'];
        $total = $_GPC['total'];
        $fid = $_GPC['id'];
        $min=0.01;//每个人最少能收到0.01元
        if(empty($num)){
           message('请输入人数',referer(),'error');
        }
        if(empty($total)){
           message('请输入总数',referer(),'error');
        }
        if($total<=10){
          message('金额必须大于10元',referer(),'error');
        }
        for ($i=1;$i<$num;$i++)
        {
            $safe_total=($total-($num-$i)*$min)/($num-$i);//随机安全上限
            $money=ceil(mt_rand($min*100,$safe_total*100)/100);
            $total=$total-$money;
             $hb[$i-1] = $money;
        }
        $hb[$num-1] = $total;

        $list  = pdo_fetchall('SELECT id FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and is_roob=1 order by rand() LIMIT ".$num);
        // var_dump($hb);

        foreach ($list as $k => $v) {
            $start_time = strtotime(date('Y-m-d',time()));
            $end_time = time();
            $rand_time = mt_rand($start_time,$end_time);
            //调用函数
            // rand_time($start_time,$end_time);
            $list[$k]['price'] = $hb[$k];
            $data = array(
               'weid'=>$this->weid,
               'fid'=>$fid,
               'upbdate'=>$rand_time,
               'status'=>1,
               'fee'=>$hb[$k],
               'mid'=>$v['id'],
               'type'=>0,
               'is_roob'=>1
            );
            pdo_insert(GARCIA_PREFIX."paylog",$data);
        }
          message('增加成功！',referer(),'success');
        exit;
   }



  if($display=='jubao'){
     $fid = $_GPC['id'];
     $sql = "SELECT b.name,a.* FROM ".tablename(GARCIA_PREFIX."jubao")." a
     LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." b on a.fid = b.id
      where a.fid=".$fid." and a.weid=".$this->weid;
      $list  = pdo_fetchall($sql);
      $name = $list[0]['name'];
      // var_dump($list);
  }

  else if($display=='list'){
    $action = empty($_GPC['action'])?'1':$_GPC['action'];
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;

     $_list = pdo_fetchall("SELECT a.*,b.project_name,b.shouchishenfenz,c.nickname,b.is_hospital,c.nickname,c.tel,c.mobile FROM ".tablename(GARCIA_PREFIX."fabu")." a
     LEFT JOIN ".tablename(GARCIA_PREFIX."project")." b on a.pid=b.id
     LEFT JOIN ".tablename(GARCIA_PREFIX."member")." c on c.id=a.mid
     where a.weid=".$this->weid." and a.status=".$action." and a.early=0 order by a.status asc,a.id desc limit ".($pindex - 1) * $psize.','.$psize);
     $total = pdo_fetchcolumn('SELECT COUNT(a.id) FROM '.tablename(GARCIA_PREFIX."fabu").' a
     LEFT JOIN '.tablename(GARCIA_PREFIX."project").' b on a.pid=b.id
     where a.weid='.$this->weid.' and a.status='.$action.' and a.early=0 order by a.status asc,a.id desc ');
     $pager = pagination($total, $pindex, $psize);

  }else if($display=='views'){
        $_config = pdo_fetch("SELECT a.*,b.nickname,b.headimgurl as avatar FROM ".tablename(GARCIA_PREFIX."fabu")." a
        LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
         where a.weid=".$this->weid." and a.id=".$_GPC['id']);
         $ids = json_decode($_config['thumb'],true);
         foreach ($ids as $k3 => $v3) {
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
        $less = $this->diffDate($cur_data,$_config['rand_day']);
        $_count = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$_GPC['id']." and status=1");
        $_sum = pdo_fetchcolumn('SELECT sum(fee) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$_GPC['id']." and status=1");
        $_uplist = pdo_fetchall('SELECT a.*,b.nickname,b.headimgurl as avatar FROM '.tablename(GARCIA_PREFIX.'update')." a
        LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
        where a.weid=".$this->weid."  and a.fid=".$_GPC['id']." order by a.id desc");
       include $this->template('admin/choukuan/'.$display);
       exit;
  }else if($display=='suport'){
      $id = $_GPC['id'];
      $pindex = max(1, intval($_GPC['page']));
      $psize = 20;
      $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." AND id=".$id;
      $_conf = pdo_fetch($sql);
      $_list = pdo_fetchall('SELECT a.*,b.nickname,b.headimgurl as avatar2,b.tel,b.mobile,c.name as ad_name,c.province,c.city,c.area,b.is_roob FROM '.tablename(GARCIA_PREFIX.'paylog')." a
      LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
      LEFT JOIN ".tablename(GARCIA_PREFIX."address")." c on a.address_id=c.id
      LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." d on d.id=a.fid
      WHERE a.weid=".$this->weid." and a.fid=".$id." and a.status=1 and a.type=0 order by upbdate desc limit ".($pindex - 1) * $psize.','.$psize);

      $total = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX.'paylog')." WHERE weid=".$this->weid." and fid=".$id." and status=1 and type=0");
      $pager = pagination($total, $pindex, $psize);
      include $this->template('admin/choukuan/'.$display);
     exit;
  }else if($display=='pinglun'){
        $pazie = 20;
        $page = max(1,$_GPC['page']);
    $_slist = pdo_fetchall('SELECT a.fee,a.msg,a.id,b.nickname,b.headimgurl as avatar,a.upbdate,a.wantSupportTel FROM '.tablename(GARCIA_PREFIX."paylog")." a
    LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b  on a.mid=b.id
    where a.weid=".$this->weid." and a.fid=".$_GPC['id']." and a.status=1 and a.type=0 order by id desc limit ".($page-1).",".$pazie);


     $total = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX.'paylog')." WHERE weid=".$this->weid." and fid=".$_GPC['id']." and status=1 and type=0 ");
     $pager = pagination($total, $page, $pazie);

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
    //  var_dump()
    include $this->template('admin/choukuan/'.$display);
   exit;
  }

  else if($display=='add'){


        if(!empty($fabu)){
              $project  = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and id=".$fabu['pid']);

              if($project['project_plus3']==1){
                  //梦想清单
                  $dream = $fabu['dream'];
                  $dream = json_decode($dream,true);
                  // var_dump($dream);
              }
              if($project['project_plus4']==1||$project['project_plus5']){
                $huibao = $fabu['reward'];
                        // var_dump($huibao);
                $huibao = json_decode($huibao,true);
              }
        }
  }else if($display=='chouchi'){

        $sql = "SELECT a.*,c.headimgurl as avatar,c.nickname FROM ".tablename(GARCIA_PREFIX."shouchishenfenz")." a
        LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." b on a.fid=b.id
        LEFT JOIN ".tablename(GARCIA_PREFIX."member")." c on b.mid=c.id
         where a.weid=".$this->weid." and a.fid=".$_GPC['fid'];
        $_item = pdo_fetch($sql);
        if(!empty($_item)){
            // if( $_item['type']==1){
            //     $zhengm = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id in(".$_item['zhengm'].")");
            // }else{
            //     $zhengm = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id in(".$_item['zhengm'].")");
            // }
        }
        unset($sql);
        $sql  ="SELECT * FROM ".tablename(GARCIA_PREFIX."hospital")." where weid=".$this->weid.' and fid ='.$_GPC['fid'];
        $_item2 = pdo_fetch($sql);

        if(!empty($_item2)){
           $_item2pic = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id in(".$_item2['idcar'].")");
        }

        if(empty($_item)&&empty($_item2)){
           message('没有相关记录或并没上传',$this->createWebUrl('choukuan'),'error');
        }

       include $this->template('admin/choukuan/'.$display);
    exit;
  }else if($display=='background'){
      $action = empty($_GPC['action'])?'list':$_GPC['action'];
      if($action=='list'){
         $_list = pdo_fetchall('SELECT a.*,b.project_name FROM '.tablename(GARCIA_PREFIX."fabu")." a
         LEFT JOIN ".tablename(GARCIA_PREFIX."project")." b on a.pid=b.id
          where a.weid=".$this->weid." and a.status=-1");
      }
  }else if($display=='early'){
    $list = pdo_fetchall('SELECT a.*,b.status,b.id as uid,b.content FROM '.tablename(GARCIA_PREFIX."fabu")." a
    LEFT JOIN ".tablename(GARCIA_PREFIX."update")." b on a.id = b.fid
    where a.weid=".$this->weid."  and a.early=1 and b.type=1 and b.status<999 order by id desc");
  }


  $_plist = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."project")." where weid=".$this->weid);


  // echo $this->template('admin/choukuan/index');
   include $this->template('admin/choukuan/index');



   function _report($fid,$weid,$act){

       $sql = "SELECT count(id) FROM ".tablename(GARCIA_PREFIX."jubao")." where weid=".$weid." and fid=".$fid;
       $_c = pdo_fetchcolumn($sql);

       if(!$_c){
         return "<button class='btn btn-xs btn-success'>无</button>";
       }else{
         return "<a class='btn btn-xs btn-danger' href='".$act."'>".pdo_fetchcolumn($sql)."</a>";
       }

   }


   function _getpic($id){
       global $_W;
       if(empty($id)){
         return '无';
       }
      return pdo_fetchcolumn('SELECT pic FROM '.tablename(GARCIA_PREFIX."photo")." where weid=".$_W['uniacid']." and id=".$id);
   }
   function _src($id){
     global $_W;
    //  $_W['uniacid'];
     $sql = "SELECT project_name FROM ".tablename(GARCIA_PREFIX."project")." where weid=".$_W['uniacid']." and id=".$id;
     return pdo_fetchcolumn($sql);
   }

   function __tomedia($url){
      return tomedia($url);
   }
   function _checkFabu($fid){
        global $_W;
        $sql = "SELECT COUNT(id) FROM ".tablename(GARCIA_PREFIX."paylog")." a
        where a.weid=".$_W['uniacid']." and a.fid=".$fid." and a.status=1 ";
        $_c  = pdo_fetchcolumn($sql);
        if($_c<=0){
              pdo_update(GARCIA_PREFIX."fabu",array('tuikuan'=>1),array('id'=>$fid));
        }
   }

   function __sendMsg($_d,$_m,$kf_tuikuan_type,$kf_tuikuan_content,$kf_tuikuan_tmp,$fid,$wapi,$token,$openid){
     global $_W;

     if($kf_tuikuan_type==0){
         $message = str_replace($_d,$_m,$kf_tuikuan_content);
        return  $a = $wapi->sendText($openid,urlencode($message),$token);
     }else{
          $temp = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX."temp")." where weid=".$_W['uniacid']." and id=".$kf_tuikuan_tmp);
          $temp_id = $temp['tempid'];
          $_url = $_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('id'=>$fid)),2);
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
         return $a = $wapi->sendTemplate($openid,$temp_id,$url,$token,$parama);
     }
   }

   function __people($id){
       global $_W;
        return pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX."paylog")." where weid=".$_W['uniacid']." and status=1 and type=0 and fid=".$id);
   }
   function __yanz($id){
       global $_W;
        return pdo_fetchcolumn('SELECT status FROM '.tablename(GARCIA_PREFIX."shouchishenfenz")." where weid=".$_W['uniacid']." and fid=".$id);
   }
 ?>
