<?php


    function __construct(){

    }

     if($action=='zc'){
       if(empty($_GPC['id'])){
          _fail(array('msg'=>'请输入id'));

       }

       $is_content = $_GPC['is_content'];

       $ud = pdo_fetchcolumn('SELECT mupbdate FROM '.tablename(GARCIA_PREFIX."fabu")." where weid = ".$this->weid." and id=".$_GPC['id']);

       $mkeys = md5($this->weid."_".$_GPC['id'].$ud.$is_content);

      //  $fabu = $memcache_obj->get($mkeys);
      // $fabu = null; 

       if(empty($fabu)){

       $sql = "SELECT
       a.yunfei,a.deliveryTime,a.id,a.name,a.mid,a.tar_monet,a.upbdate,a.rand_day,a.is_live,a.dream,a.reward,a.is_share as share,a.project_texdesc as content,a.thumb
       ,c.nickname,c.headimgurl as avatar,c.mobile,c.r_token
       ,b.project_plus4 as is_reward ,b.project_plus3 as is_list,b.is_hospital
       FROM ".tablename(GARCIA_PREFIX."fabu")." a
       LEFT JOIN ".tablename(GARCIA_PREFIX."project")." b on a.pid=b.id
       LEFT JOIN ".tablename(GARCIA_PREFIX."member")." c on a.mid=c.id
       where a.weid=".$this->weid." and a.id=".$_GPC['id'];
       $fabu = pdo_fetch($sql);

       if(empty($fabu['r_token'])){
          include GARCIA_PATH.'class/serverAPI.php';
          $ry = new ServerAPI('pgyu6atqypm5u','3BjKOZSjHiBl');
          $token = $ry->getToken($fabu['mid']."_".$this->weid, $fabu['mid']."_".$this->weid, tomedia($this->sys['user_img']));
          $token = json_decode($token,true);
          $fabu['r_token'] = $token['token'];
          pdo_update(GARCIA_PREFIX."member",array('r_token'=>$member['r_token']),array('id'=>$member['uid']));
       }
        unset($fabu['r_token']);
        $_count = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$_GPC['id']." and status=1");
        $_sum = pdo_fetchcolumn('SELECT sum(fee) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$_GPC['id']." and status=1 and type=0");
        $fabu['count_member'] = (int)$_count; //
        $fabu['count_pay'] = max(0,$_sum);
        $fabu['precent'] = sprintf("%.2f",($fabu['count_pay']/$fabu['tar_monet']))*100;

         if(empty($fabu['nickname'])){
             $fabu['nickname']  = substr($fabu['mobile'],0,4)."****".substr($fabu['mobile'],8);
         }
          else{
            $fabu['nickname'] = urldecode($fabu['nickname']);
          }


          if(empty($fabu['avatar'])){
              $fabu['avatar']  =tomedia($this->sys['user_img']);
          }else{
             $fabu['avatar']  =tomedia($fabu['avatar']);
          }

       if($fabu['thumb']){
           $fabu['thumb'] = json_decode($fabu['thumb'],true);
           $fabu['thumb'] = implode(',',$fabu['thumb']);
           $psql = "SELECT pic as url,thumb FROM ".tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id in(".$fabu['thumb'].")";
           $fabu['thumb'] = pdo_fetchall($psql);
       }


      //  显示模式
      //帮他实现
      if($fabu['is_reward']!=1&&$fabu['is_list']==1){
          $fabu['hlist'] = json_decode($fabu['dream'],true);

          foreach ($fabu['hlist'] as $key => $value) {
            if($value['dream_id']){
               $fabu['hlist'][$key]['_count'] = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX."paylog")." where weid=".$this->weid." and dream_id='".$value['dream_id']."' and fid=".$fabu['id']." and status=1");
            }
          }
      }else if($fabu['is_reward']==1&&$fabu['is_list']!=1){
          $fabu['rewards'] = json_decode($fabu['reward'],true);
            $i = 0;
          foreach ($fabu['rewards'] as $key => $value) {
                 if(empty($fabu['rewards'][$key]['places'])){
                     $fabu['rewards'][$key]['places'] = '无限制';
                 }
                 $fabu['rewards'][$i] = $fabu['rewards'][$key];
                 unset($fabu['rewards'][$key]);
          }
          $fabu['yunfei'] = empty($fabu['yunfei'])?'包邮':$fabu['yunfei'];
          $fabu['deliveryTime'] = empty($fabu['deliveryTime'])?'众筹成功后7天内全部发货':$fabu['deliveryTime'];
      }

      if($fabu['is_hospital']==1){
         $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."hospital")." where weid=".$this->weid." and fid=".$fabu['id'];
         $_shouchi = pdo_fetch($sql);
         $_shouchi['zhengshishuoming'] = htmlspecialchars_decode($this->sys['zhengshishuoming']);
         $fabu['hosdata'] = $_shouchi;
        //  $sql = "SELECT a.*,b.nickname,b.headimgurl as avatar FROM ".tablename(GARCIA_PREFIX."bang")." a
        //  LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
        //  where a.weid=".$this->weid." and a.fid=".$_GPC['id']." order by a.id desc";
        //  $zhengshi = pdo_fetchall($sql);
        //   $_iszhengshi = pdo_fetchcolumn('SELECT id FROM '.tablename(GARCIA_PREFIX."bang")." where weid=".$this->weid." and mid=".$mid);
         // echo $mid;
      }
      $fabu['is_good'] = $fabu['is_reward'];

      $fabu['share_title']  = $fabu['name'];
      $fabu['share_desc']  =  mb_substr(strip_tags(htmlspecialchars_decode($fabu['content'])),0,90,'utf8');
      $fabu['share_link'] = $_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('id'=>$fabu['id'])),2);
      if(!empty($_GPC['mid'])){
        $fabu['target_code'] = md5(($_GPC['mid']+$fabu['mid']).$this->weid);
      }else{
         $fabu['target_code'] = '';
      }
      $fabu['revice_nickname'] = $fabu['nickname'];
      $fabu['revice_avatar'] = $fabu['avatar'];

        $fabu['revice_id'] = $fabu['mid']."_".$this->weid;
        $fabu['content'] = htmlspecialchars_decode($fabu['content']);
         $fabu['content'] = preg_replace("/style=.+?['|\"]/i",'',$fabu['content']);
      // unset($fabu['content']);
      unset($fabu['reward']);

        // $memcache_obj->set($mkeys,$fabu,0,3600);

     }
     if(!empty($_GPC['mid'])){
         $is_shouc = pdo_fetchcolumn('SELECT is_shouc FROM '.tablename(GARCIA_PREFIX.'member')." where weid=".$this->weid." AND id='".$_GPC['mid']."'");
         $is_shouc = explode(',',$is_shouc);
         if(in_array($_GPC['id'],$is_shouc)){
           $fabu['is_collect'] = 1;
         }else{
           $fabu['is_collect'] = 0;
         }
      }
      $fabu['sc'] = pdo_fetchcolumn('SELECT shouc as sc FROM '.tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id=".$_GPC['id']);
     //获取动态更新
     $_up = pdo_fetchall('SELECT a.id,a.upbdate,a.thumb,a.content,b.nickname,b.headimgurl as avatar FROM '.tablename(GARCIA_PREFIX.'update')." a
     LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
     where a.weid=".$this->weid."  and a.fid=".$_GPC['id']." order by id desc");
     foreach ($_up as $key => $value) {
        if($value['nickname']){
          $_up[$key]['nickname']  = urldecode($value['nickname']);
        }
        if($value['upbdate']){
           $_up[$key]['upbdate'] = $this->_format_date($value['upbdate']);
        }
        if($value['thumb']){
           $_up[$key]['thumb'] =json_decode($value['thumb'],true);
        }
     }
     if($this->sys['supr_rank']==0){
        $_order = 'order by a.upbdate desc,a.id desc';
     }else{
        $_order = 'order by a.fee desc,a.id desc';
     }
     //获取支持评论
     $pazie = 15;
     $_slist = pdo_fetchall('SELECT a.fee,a.msg,a.id,b.nickname,b.headimgurl as avatar,a.upbdate FROM '.tablename(GARCIA_PREFIX."paylog")." a
     LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b  on a.mid=b.id
     where a.weid=".$this->weid." and a.fid=".$_GPC['id']." and a.status=1 and a.type=0 ".$_order." limit 0,".$pazie);
     foreach ($_slist as $key => $value) {


         if($value['nickname']){
           $nickname  = trim(urldecode($value['nickname']));

           if(empty($nickname)){
              $_slist[$key]['nickname'] = '无名氏'.$key;

           }else{
             $_slist[$key]['nickname'] = $nickname;
           }
         }
         if($value['upbdate']){
            $_slist[$key]['upbdate'] = $this->_format_date($value['upbdate']);
         }

           if(empty($_slist[$key]['avatar'])){
                $_slist[$key]['avatar'] = $this->sys['user_img'];
           }
          $temp = '';
          $sql = 'SELECT  a.id,a.mid,a.content,a.upbdate,a.mesid,a.tmid,b.nickname FROM '.tablename(GARCIA_PREFIX.'message')." a
          LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on b.id = a.mid
           where a.weid=".$this->weid." AND a.payid=".$value['id']." and a.fid=".$_GPC['id'];
           $temp =pdo_fetchall($sql);
           if(!empty($temp)){
             foreach ($temp as $k => $v) {
                 if($v['nickname']){
                   $temp[$k]['nickname']  = '无名氏';

                 }
                 if($v['upbdate']){
                    $temp[$k]['upbdate'] = $this->_format_date($v['upbdate']);
                 }
             }
             $_slist[$key]['message'] = $temp;
           }
     }

     //build
     $fabu['up'] = empty($_up)?'null':$_up;
     $fabu['sup'] = empty($_slist)?'null':$_slist;
     $fabu['supnumber'] = count($_slist);
     if($fabu['upbdate']){
        $fabu['upbdate'] = $this->_format_date($ud);
        $fabu['less'] =  $this->diffDate( date('Y-m-d',time()),$fabu['rand_day']);
     }
    _success(array('res'=>$fabu,'memcache'=>$mkeys));
     }else if($action=='zc_conetnt'){
         if(empty($_GPC['id'])){
            _fail(array('msg'=>'请输入id'));
         }
         $content = pdo_fetchcolumn('SELECT project_texdesc FROM '.tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id=".$_GPC['id']);
                //  _success(array('res'=>'SELECT content FROM '.tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id=".$_GPC['id']));
         if(empty($content)){
            _fail(array('msg'=>'空'));
         }else{
                  _success(array('res'=>preg_replace("/style=.+?['|\"]/i",'',htmlspecialchars_decode($content))));
         }
     }
     else if($action=='share'){
         $fid = $_GPC['id'];
         $is_share = pdo_fetchcolumn('SELECT is_share FROM '.tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." AND id=".$fid);
         $_is_share = $is_share+1;
         pdo_update(GARCIA_PREFIX."fabu",array('is_share'=>$_is_share),array('id'=>$fid));
          _success(array('msg'=>'分享成功'));
     }else if($action=='collect'){

          if(empty($_GPC['mid'])){
                      _success(array('msg'=>'请先登录','type'=>0));
          }
           $_shouc = pdo_fetchcolumn('SELECT shouc FROM '.tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id=".$_GPC['id']);
           $_shoum = $_shoum2 = pdo_fetchcolumn('SELECT is_shouc FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$_GPC['mid']);
           $_shoum = explode(',',$_shoum);
           if(!in_array($_GPC['id'],$_shoum)){
             if(empty($_shoum[0])){
                $_shoum =$_GPC['id'];
             }else{
                $_shoum =$_shoum2.",".$_GPC['id'];
             }
                $_shouc = $_shouc+1;
                $text = '收藏成功';
                $type = 1;
           }else{
             foreach ($_shoum as $k => $v) {
                 if($v==$_GPC['id']){
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
              $text = '取消成功';
              $type = 0;
           }
           pdo_update(GARCIA_PREFIX."member",array('is_shouc'=>$_shoum),array('id'=>$_GPC['mid']));
           pdo_update(GARCIA_PREFIX."fabu",array('shouc'=>$_shouc),array('id'=>$_GPC['id']));
          _success(array('msg'=>$text,'type'=>$type,'count'=>$_shouc));
     }else if($action=='records'){
        //记录
        if(empty($_GPC['id'])){
           _fail(array('msg'=>'请输入id'));
        }
        $id = $_GPC['id'];
        $status = empty($_GPC['status'])?3:$_GPC['status'];
        $_count = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$_GPC['id']." and status=1");
        $_sum = pdo_fetchcolumn('SELECT sum(fee) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$_GPC['id']." and status=1");
        $_views = pdo_fetchcolumn('SELECT views FROM '.tablename(GARCIA_PREFIX.'fabu')." where weid=".$this->weid." and id=".$_GPC['id']);
        $pid = pdo_fetchcolumn('SELECT pid FROM '.tablename(GARCIA_PREFIX.'fabu')." where weid=".$this->weid." and id=".$_GPC['id']);
        $is_goods = pdo_fetchcolumn("SELECT is_goods FROM ".tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and id=".$pid);
        if($is_goods==1){
            $status = $_GPC['status'];
            if($status=='3'){
               $status = '';
            }else{
                $status = ' and a.fahuo='.$status;
            }
           //  echo $status;
          $list = pdo_fetchall('SELECT a.id,a.fee,a.fahuo,a.upbdate,a.count as _count,a.reid,b.nickname,b.headimgurl as avatar  FROM '.tablename(GARCIA_PREFIX."paylog")." a
          LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
          where a.weid=".$this->weid."  and a.status=1 and a.fid=".$id .$status." order by fahuo desc , id desc ");
          $reward = pdo_fetchcolumn('SELECT reward FROM '.tablename(GARCIA_PREFIX."fabu").' where weid='.$this->weid." and id=".$id."  ");
          $reward = json_decode($reward,true);
          foreach ($list as $k => $v) {
               foreach ($v as $k1 => $v1) {
                   if($k1=='reid'){
                       $list[$k]['rewards'][]= array(
                           'desc'=>$reward[$v1]['supportContent'],
                       );
                       unset($list[$k][$k1]);
                   }
                   else if($k1=='nickname'){
                      $list[$k][$k1] = urldecode($v1);
                   }else if($k1=='_count'){
                     $list[$k][$k1] = max(1,$v1);
                   }else if($k1=='fahuo'){
                      if($v1==0){
                         $list[$k][$k1] = '[待发货]';
                      }else if($v1==1){
                        $list[$k][$k1] = '[已发货]';
                      } else if($v1==2){
                        $list[$k][$k1] = '[已收货]';
                      }
                   }else if($k1=='upbdate'){
                     $list[$k][$k1] = $this->_format_date($v1);
                   }
               }
          }
          _success(array('is_good'=>$is_goods,'count'=>count($list),'res'=>$list));
        }else{
            $list = pdo_fetchall('SELECT a.id,a.upbdate,b.nickname,a.fee,b.headimgurl as avatar FROM '.tablename(GARCIA_PREFIX."paylog")." a
            LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
            where a.weid=".$this->weid."  and a.status=1 and a.fid=".$id." order by id desc");
            foreach ($list as $k => $v) {
                 foreach ($v as $k1 => $v1) {
                     if($k1=='nickname'){
                           $list[$k][$k1] = urldecode($v1);
                     }else if($k1=='upbdate'){
                       $list[$k][$k1] = $this->_format_date($v1);
                     }else if($k1=='avatar'){
                       $list[$k][$k1] = trim($v1);
                     }
                 }
               }
              _success(array('is_good'=>$is_goods,'count'=>count($list),'res'=>$list));
       }

     }else if($action=='fahuo'){
        // 发货操作
        if(empty($_GPC['id'])){
           _fail(array('msg'=>'请输入id'));
        }
        $id = $_GPC['id'];
        $item = pdo_fetch('SELECT a.*,c.id as pid FROM '.tablename(GARCIA_PREFIX."paylog")." a
        LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." b on a.fid = b.id
        LEFT JOIN ".tablename(GARCIA_PREFIX."project")." c on c.id=b.pid
        where a.weid=".$this->weid." and a.id=".$id);
        if(empty($item['address_id'])){
           //获取用户默认收获地址
          //  echo $item['mid'];
           $de_address = pdo_fetchcolumn('SELECT id FROM '.tablename(GARCIA_PREFIX."address").' where weid='.$this->weid." and mid=".$item['mid']." and is_def=1");
           if(empty($de_address)){
              _fail(array('msg'=>'用户没有默认地址'));
           }
           $addrss_id = $de_address;
        }else{
            $addrss_id  = $item['address_id'];
        }
        $reward = pdo_fetchcolumn('SELECT reward FROM '.tablename(GARCIA_PREFIX."fabu").' where weid='.$this->weid." and id=".$item['fid']);
        $reward = json_decode($reward,true);
        $reward_name= $reward[$item['reid']]['supportContent'];
        $_count = $reward[$item['reid']]['supportNumber'];
        $address  = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."address")." where weid=".$this->weid." and id=".$addrss_id);
        $list['id']=$item['id'];
        $list['mid'] = $item['mid'];
        $list['pid'] = $item['pid'];
        $list['fid'] = $item['fid'];
        $list['reid'] = $item['reid'];
        $list['name'] = $address['name'];
        $list['tel'] = $address['tel'];
        $list['address'] = $address['province']." ".$address['city']." ".$address['area']." ".$address['address'];
        $list['address_id'] = $addrss_id;
        $list['reward_desc'] = $reward_name;
        $list['_count'] = max(1,$item['count']);
        $list['kdlist'] =  array('申通' ,'圆通' ,'中通' ,'汇通' ,'韵达' ,'顺丰' ,'ems' ,'天天','宅急送','邮政' ,'德邦','全峰' );
          _success(array('res'=>$list));
     }else if($action=='save_fahuo'){
       if(empty($_GPC['id'])){
          _fail(array('msg'=>'请输入id'));
       }
       $kuaidi  = $_GPC['kuaidi']; //快递名称
       $kuai_order = $_GPC['kuai_order']; //快递单号
       $fahuo_time = $_GPC['fahuo_time']; //发货时间时间戳
       $address_id = $_GPC['address_id'];  //发货地址id
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
       pdo_update(GARCIA_PREFIX."paylog",array('fahuo'=>1),array('id'=>$_GPC['id']));
       _success(array('msg'=>'发货成功'));
     }else if($action=='detail_del'){
       if(empty($_GPC['id'])){
          _fail(array('msg'=>'请输入id'));
       }
      pdo_update(GARCIA_PREFIX."fabu",array('status'=>'6'),array('id'=>$_GPC['id']));
      _success(array('msg'=>'删除成功'));
    }else if($action=='detail_end'){
      if(empty($_GPC['id'])){
         _fail(array('msg'=>'请输入id'));
      }
      $fid = $_GPC['id'];
      $content = $_GPC['content'];
      $mid = $_GPC['mid'];
      $data = array(
        'weid'=>$this->weid,
        'mid'=>$mid,
        'fid'=>$fid,
        'content'=>$content,
        'upbdate'=>time(),
        'type'=>1,
        'status'=>2,
      );

      pdo_insert(GARCIA_PREFIX."update",$data);
      pdo_update(GARCIA_PREFIX."fabu",array('early'=>1),array('id'=>$fid));
      _success(array('msg'=>'审核提交成功～'));
    }
    else if($action=='paytemp'){
      if(empty($_GPC['id'])){
         _fail(array('msg'=>'请输入id'));
      }
      if(empty($_GPC['mid'])){
         _fail(array('msg'=>'请输入mid'));
      }
      $sql = "SELECT a.id,b.project_plus4 as is_reward ,a.mid,b.project_plus3 as is_list,b.project_plus5 as is_diy,a.reward,a.dream FROM ".tablename(GARCIA_PREFIX."fabu")." as a
              LEFT JOIN ".tablename(GARCIA_PREFIX."project")." as b on a.pid=b.id
              where a.weid=".$this->weid." and a.id=".$_GPC['id'];
      $res = pdo_fetch($sql);

      if($res['is_reward']==1){
        $res['reward'] = json_decode($res['reward'],true);

          $i = 0;
        foreach ($res['reward'] as $key => $value) {
               if(empty($res['reward'][$key]['places'])){
                   $res['reward'][$key]['places'] = '无限制';
               }
               $res['reward'][$i] = $res['reward'][$key];
               unset($res['reward'][$key]);
               $i++;
        }
        //获取用户地址
        $res['add_list'] = pdo_fetch('SELECT id,province,city,area,address,name,tel FROM '.tablename(GARCIA_PREFIX."address")." where weid=".$this->weid." and mid=".$_GPC['mid']." and is_def = 1");
        if($res['add_list']){
          $res['add_list']['tel'] =  substr($res['add_list']['tel'],0,4).'****'.substr($res['add_list']['tel'],8);
           $res['is_address']  = 1;
        }else{
           $res['is_address']  = 0;
        }
        unset($res['dream']);
      }else  if($res['is_list']==1){
        // $res['dream'] = str_replace(array('[',']'),'',$res['dream']);
          $res['hlist'] = json_decode($res['dream'],true);

          foreach ($res['hlist'] as $key => $value) {
            if($value['dream_id']){
               $res['hlist'][$key]['_count'] = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX."paylog")." where weid=".$this->weid." and dream_id='".$value['dream_id']."' and fid=".$res['id']." and status=1");
            }
          }
          unset($res['reward']);
                unset($res['dream']);
      }
      _success(array('res'=>$res));
    }
     else{
         _fail(array('msg'=>'not found function'));
     }

 ?>
