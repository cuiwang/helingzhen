<?php
$this->_wapi();
// var_dump($this->wapi);
$display = empty($_GPC['display'])?'list':$_GPC['display'];
$dopost = $_GPC['dopost'];
$do = empty($_GPC['do'])?'member':$_GPC['do'];

  // echo 1

  if($dopost=='mes_ajax'){
       $openid  = $_GPC['openid'];
       $msg = $_GPC['msg'];
       $token = $this->_gtoken();
       $res = $this->wapi->sendText($openid,urlencode($msg),$token);
       die($res);
  }else if($dopost=='member_del'){
      pdo_update(GARCIA_PREFIX."member",array('status'=>1),array('id'=>$_GPC['id']));
      message('删除成功!',referer(),'success');
  }else if($dopost=='member_black'){
      pdo_update(GARCIA_PREFIX."member",array('status'=>3),array('id'=>$_GPC['id']));
      message('拉黑成功!',referer(),'success');
  }
  else if($dopost=='add_zuzhi'){
    $data = array(
      'nickname'=>urlencode($_GPC['nickname']),
      'headimgurl'=>$_GPC['avatar'],
      'weid'=>$this->weid,
      'type'=>9
    );
    pdo_insert(GARCIA_PREFIX."member",$data);
          message('添加成功!',referer(),'success');
    exit;
  }

  else if($dopost=='del_zuzhi'){
    pdo_delete(GARCIA_PREFIX."member",array('id'=>$_GPC['id']));
    pdo_update(GARCIA_PREFIX."fabu",array('status'=>6),array('mid'=>$_GPC['id']));
    message('操作成功!',referer(),'success');
    exit;
  }
  else if($dopost=='upmember'){
    if($_GET['up']!='go'){
      message('准备更新，请勿关闭浏览器',$this->createWebUrl('member',array('dopost'=>'upmember','up'=>'go')),'success');
    }
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $list = pdo_fetchall("SELECT id,openid FROM ".tablename(GARCIA_PREFIX."member")." a where a.weid=".$this->weid."  and a.openid!='' and a.status=0 order by id asc LIMIT ".($pindex - 1) * $psize.','.$psize);
    $_c = count($list);
    $_cc = $_GPC['count']+$_c;
    if($_c<=0){
      message('更新成功!正在返回',$this->createWebUrl('member'),'success');
    }
    $token = $this->_gtoken();
    foreach($list as $key => $value) {
          $_opends['user_list'][]=array(
            'openid'=>$value['openid'],
            'lang'=>"zh-CN",
          );
    }
    $url = 'https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token='.$token;
    $_info = $this->wapi->https_url($url,json_encode($_opends),true);
    $user_list = json_decode($_info,true);
    $user_list = $user_list['user_info_list'];

     foreach ($user_list as $key => $value) {
        $_info = '';
          if($key==0){
            $_in_id = $list[$key]['id'];
          }else{
            $_in_id.= ",".$list[$key]['id'];
          }
            $_nickname = urlencode($value['nickname']);
            $_headimgurl = $value['headimgurl'];
           $_subscribe = $value['subscribe'];
           $_nick_sql.= sprintf("WHEN %d THEN '%s' ", $list[$key]['id'], $_nickname);
           $_head_sql.= sprintf("WHEN %d THEN '%s' ", $list[$key]['id'], $_headimgurl);
           $_subs_sql.= sprintf("WHEN %d THEN '%s' ", $list[$key]['id'], $_subscribe);
          // echo "<br/>";


        //  pdo_update(GARCIA_PREFIX."member",array('nickname'=>$_nickname,'headimgurl'=>$_headimgurl,'subscribe'=>$_subscribe),array('id'=>$value['id']));
     }
         $sql = "UPDATE ".tablename(GARCIA_PREFIX."member")." SET nickname= CASE id ";
         $sql.=$_nick_sql." END,";
         $sql.='headimgurl = CASE id '.$_head_sql.' END,';
         $sql.='subscribe = CASE id '.$_subs_sql.' END';
         $sql.=' WHERE id IN('.$_in_id.')';
         pdo_query($sql);
         $pindex = $pindex+1;
    message('成功更新'.$_cc."人",$this->createWebUrl('member',array('dopost'=>'upmember','page'=>$pindex,'up'=>'go','count'=>$_cc)),'success');
    exit;
  }else if($dopost=='shiming'){
      pdo_update(GARCIA_PREFIX."shiming",array('status'=>1),array('id'=>$_GPC['id']));
      message('实名认证通过',$this->createWebUrl('member'),'success');
      exit;
  }
  else if($dopost=='deshiming'){
      // pdo_update(GARCIA_PREFIX."shiming",array('status'=>1),array('id'=>$_GPC['id']));
      pdo_delete(GARCIA_PREFIX."shiming",array('id'=>$_GPC['id']));
      message('删除实名认证通过',$this->createWebUrl('member'),'success');
      exit;
  }

  else if($dopost=='add_vies'){
    $sql = "INSERT INTO ".tablename(GARCIA_PREFIX.'member')." (`weid`, `openid`, `nickname`, `headimgurl`, `is_roob`, `is_shouc`, `tel`, `allows`, `allow`, `is_manger`, `status`, `subscribe`) VALUES
    (".$this->weid.", NULL, '".urlencode('张艳')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiajrQRSmaiapuicTWNGSCtzzGv1hbVtwDzoXmr9iayCSLAUcxsQDnasQQANtico0bgib8yshYbeNZ29oLA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('枫林')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLAawA8pOnOdEY6xkKke0BY45gaiad0Zte2JIjHtb1U4Re9r5iayAs5yl4OZFE1wvBCQxIl2UODDwCUA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('淡淡&清香')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLBUTYV4QM3FZibOTsK90Fgu28SNvBVhafLr4fU9ukeOyyR8vojTDtkGsLiarcbnKnSHm6WJs6OMOKNw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('CC')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZXscmBokHVmJoeXNHudmicAWQColQ3ANicv6Ef9FQHADS0JUVBXfPYUH0Ky6DhFgEBkrHRLGMdich8ic/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('Wn')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZUcfk4UKJn5EzrdmicDicgic5zNkWiblv6hrAvIoKHm7D7LeI9BWBWibhm7C2bf9yEXy8zx6o7cLShXg6/132', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('花儿')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLArTg7rn3e87jCxYXOuCN5rVenl0FLFQksD1adTIqGE3jo9ibLNYc6kicsZhAiajpYDMxSrqsDKlUblHZsXDhmZ7TZeQy8JOv7YeI/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('没大王就别出单..')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZQoydfhNR54lw6xnsQh3XbkcoLMRPKA2nWAzSOkxJdkWrnCd6knGnibRXK3OTxE6zSatVFBlW9Lsk/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('一季、又一寂')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa1JwWdp2vBEOSI0Zibsqa5KZrDIz6fnkxofwvd1sJ1WFIFkM7o0uc8tPKQt5GuhLWaiceJQ9mGlMacWiaPIGtatCwQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('羚屋_')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLAaicmC4Q5tF2h6PuYMib3ogEocgzN5SoibA20GeHQNaYjDCquDTOfv3SWUFUquq9diabfIFwe8dSsvJw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('吉宇家居—蒋彬')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLAp97ia3zBoEph21vylTTkZHQrQaubnBz6borA2KeHtynslfXEmgOQXR8hyN4x4PpCmXvibg5TOuKrw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('plww0539')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGGib2LcxwkqL6JcMchqFRH7ZgJAyqYmT3yFGG6KWWXeRIKYz7Hu0RBib12b6z1KxuBL6EreujNRhQMYQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('上帝的宠儿')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZTb5a9DUAwYBCB2aDWt0f91ia1TQAGrPPnxBKOXeobdzcMVNnwjS3gQn8rRf6Seibibr6x23CMBgzeq/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('青儿')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZQGXWg4J92P2Dm0eEsZhWWFHKyqhRNtZ6MiaIUBzHWH0Zvib6YzLmVNCsx30B3IClziaQh8l2HZ1gG9/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('宣贤')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa1ZJNqpjUH5tbDT0c879X9q3olaDXtvTfuBQPy4gR3HUiammjGAib5HAJXsiaPB8x4G3S3UKwJMYYO0Ex2jGibkqicFP/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('微笑，诠释所有')."','	http://wx.qlogo.cn/mmopen/PpzMxmN2ntGmWuBhic36giaibqm4MoHyFqHXohF56L10dKc3tcjDhibLIvshYyPqPvGIFcAld6etkibH7fn8H3pIH4lYfhUFgTCpF/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('一只大猫')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa3pXZ1oNsjrplYwOktwicmI3SMPWmic0G7rSw19fsyHYIS3bT1UUficEbUKwjiajibPtkPOpePCU30wk4dFrx8iaPs2RI/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('民民')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8EL2DvlJIhibo82ltcguwnZr5L31M4iaC8LSiahGa1M8Q6EETJko18rZRoQLGcmkib9AEhxTtGK5uAYBUrViaqCe4K0/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('刘君')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZfERrMx6KOy8NiaChng9hK6icaws5b0lBWIqKmwuhWORaWQzrPMGj3WzN8ib49CD306CfE4mIwV3w4N/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('Zhao＂雨婷')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLCRaV65BabCsxicVyF6cNaBylHqclzKsgpIARvADZMNAwWmxJc88K1SJdHPLdhxwKwDCoH8kmwmu3g/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('许丁元521lsn')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAn912aKgsmv9fibJ4Lj9wqpK8zvzMXVliaO8aGFop1IwibdoQxNGwzkiaJFiaPRN4GI2upBT5KLM8mic7r/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('暗涌')."','	http://wx.qlogo.cn/mmopen/PiajxSqBRaEL0smK2ibZVK0spTzfhAaicECGxVTzbK9a6AUDMF3ed5DjOmicic7DsNuJibO1o1ccJibGZribhdibWaUHw5Q/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('蕊')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZYKGhm0JgFYl5CZP1Qu0IPlduCPFCO1ZgPQtjE7Pq5toNRBl6Dn6CRfZsNoZliaJ7DE1AI2z33GB9/132', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('秋景^o^?????')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiarSWfuPnTOUIdS02Ouwa4TnjiaiaDPfctGFcxic80H6djhuhRXO7FT90CY2bxBbopasGAx0l8RJzCicM/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZTWCX28ib5EHk1sia6afiavw4ZtNCAh4XhG9AH3aZsoYMKmC1lPcKJEdfSt7xg4gOkxxfVunOcDg0Zia/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('晓雪')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLB6Ra7ibArD9WKG0mXUxA89OLPFgMnTKF62KuKicZCBg0KxWibgUfQfGLMgomggLE7sCrdL11KjHGU1Q/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('涂阿西儿')."','	http://wx.qlogo.cn/mmopen/PiajxSqBRaEJVEnOGDPMbqACm3NM5YULiaLB73p4OIF89ia4gxG0KJX6GaTo1RwJMMh8wEKHlKqNib9MmMRvou8srQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLByAIvktaRGYdRRcH2byjHH7icoPicg6dsoY9TY0QbjALt7TVr9rb0enKgfRCJNfGOrmuic0eEzXHbuA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('唯薆 娜')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZU9mKickkFlVcygZedkkmYZH2uv5mib22w7B5FQkqZBSSbZ0rz0uxHAse37eHaIXhvetMzSibIDL8gQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('朵朵')."','	http://wx.qlogo.cn/mmopen/K3zv3LKyAibDIuUoVib79psM16RJ7JrTZgibDp0dfSh3Z6Hz4abWYEraPQTWsrxkc9o66RCF3Xenub5EeG1bfxPiaA5vlwic3iaOu7/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('容容')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGGibGNpvdibmH4Zia3ns43PqBiciagfmM8RLVkXG3UmhicwYxyuibQ70R6y8vpOl8SOCjGCUpOaRPAiaU0aHpQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('刘洋')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiavcYxUzxhRlojY2EhBMyB9IwJZMf2XVPXP8JlKDibEOkfMq8sSo0yvl1LZ3icm72icguE6y3gd7P9Xic/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('美丽人生')."','	http://wx.qlogo.cn/mmopen/PiajxSqBRaEJNDPvF75DCAHaQgLJKV856DTReqUw5eAiaDS0rqGQ9LSMSOWjeiavic8WcCkcF5OSmxqKCBFo2wjwsw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('青青菜雀')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa0ibcicROKusqmlIsnfz6G7ibGaiaJHVMNZG7GMVicupqziaV8WzJJZ2kpo43OgoHN950gAicOjjbH0qUibOYHqJ43HEd9W/132', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('刘成')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZapzpP2oBpUkyS851PnZz3jiaDK6kBAtxhpyl5iagNEqkCJ3ZKtiaxuX5uqzG6Lej2G059HD8jq1Kvo/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('莹莹')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZTtDjLIKiaAMsMKYJiafalTznBUTl9VJTeD9zLBXBknNnHl4sG1ThHnStc1IIpQTlXY2DRS4ajf3mb/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('绿色心情')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwPibTzemjLUr5PZsMiaw1Gdyk1f5XaxiaiaQYyeVEJP71A7zT7Qpo5M5BKAXxzlaN9uRItTvm7cibqtuL0zf1xcmRyxQH/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('susie')."','	http://wx.qlogo.cn/mmopen/AuhdPibjfMyW1vbPjH9cnwspJwFrKfpPxyN90HjrVQ5GbB9sWsJLjYw6B0tRFVc5UxWhHycgvuu7eUo2cPvhIHko1QzfBYjZx/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('红茶')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZS0rmVx17zGd4pPgKrpIkeiaIaLV2YlAko7tBUsS1EGXndJNmicDNW2XgEZ59KjxBsTVZLY889mMvs/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('矢村警长')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An48PKOqOvp64lxNQUuU1Nz3JM2AicQibl9icggt06CPTHXtea6D1ibRWXhLWhr7icXOvRNUxtibXo1hvn9S6nvWwhbzgj/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('?糖心')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTApnjic1iapDUrKpHHEiafpwujNXIC0gFRR6ib8jqyquojgoJAicgYBf5oNy3EibqakSoqBCkUL7uRAUAYA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('晨光')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGGiciaOjfGw8gOJwHusv2iaM7q89ycSy3y03DguBzibfLp1tzJibBkOOdydiczsZVFPkuVACofLic9POmn1wg/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('  monkey')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2ICDAxmhzpKjDC1amLdFia0EGIqSHt7jlcPCYFBticgDyGaHLf5QA7Daqu2Qibic3DEXd8GLU9via3YtiaND5vx4tpL5/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('娜样精彩')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM7Tm0QMkoVujmRLyxRNW0sH9TribngvuNrRWENiadn6Oib3zhJwJFjqdBqzx2KAtFJEMtulqgAEJzDm9y4Q6s5IchJiaP0RztewgxM/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('美好明天')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An7jnZQ7syQWa6pAVyURuvuVkDoGaZ4ag3oIvB9r3H3QoUQibFMOLyaMUssnictKMLkFnK4vuJ4Na9tJMVcowXy0SL/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('空白')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiarXzNyYDcyvqfIwe0TXFEzw6vxfJT8LMdpTNiaGyhicgx8qbOic2rlpEPjTAySNdC6aoF2yRdj4537z/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('临沂怡和国际王影')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiakLNHM7ak3TiaRkoSWeEzdlN0CEqETzwiciaLILeUJrUbBSdEnjFqckBfQEk8eFpw2ZtSTKDsCL0G6g/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('萧郎')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZZq3E2IiaKfkpLvV9ucZEB9iaTa7MiargJLULLqtw8iatFjcK3BmSdGsjDslfRJrS3hXs5TicuRjcDI0t/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('承诺')."','	http://wx.qlogo.cn/mmopen/d4EyNKhicTARDcBoXmupBmFjtvIicUjvNlxa9ibanGabBvejrLEqwycJKVGGjPKNicRKwFP7LrYUbndNVmm3cib78tuEpYDndCmDx/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('姚')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5auQibic4FEsXo6IdJe1W1OvsTv4bCeC3zehhsuXOrtst8CibhicJgoq870t32Bn52YdqHDqw0XwMoNmR81KH7V5n4/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('将革命进行到底！')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM5w3R4ejrtBIXibxfvYwHkOnhJEOAOgrX9Tp3rZBoDjSlhqsESbOibyHrPk1NiaXlhTw5jXCM0MmuiaxoWe6anEKEzODWR80Xgviabo/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('红苹果')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa0TRXPpRuIY1rlmIhQdrcdzT2A2wrBV0hNc0DZiaPDV2n6qBqZwxRH7fHWumdXdia11nlTlMMOoF4fIU9WUAAFCvS/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('风中茉莉')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa36TgtydR2qNsY9h0DOI4Cu9iahdzqp9VALRwdVLEx0Kia6SzTsG6Tjia0uvvVWsQDrtNrh8e3c939aDfZHBTWnzoU/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('zhang')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiahbYQGQHhdeutJrgEiah0ibOG3bibw3D7rgQlh8GbxMPAJbSDzxcXibVicJaPFovsK68liczVlmHycicPvW/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('冰点')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiaozWfA9QQ5Ixx86J72zZMTY87icd0JxBD0LADTGiaTMgs7EnXzVAuoCswAW80rq8LYYib5MpSHHDLsO/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('◆◆、执着一切')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAolwYb1Bfdia5usichiaiaJSD3eQgR1UiaZzc4ZUN08jeibJs5xhHTcQZKBzeUdNknnpvdlWrSLOiboZib8e/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('云云')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM4T2VszdibWyjq17yUwnlkrq4Hc4rJmgMibe005yicYhNzGrPfucfVe7y3FSCFz9yrDWxvn3fDqNBgibA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('李尧')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiaucldDvpPqXuaY3N9YFXiaocO4mhNiaic4NdBNPYnJtYFw94BQibeQdLCH19ic0VH2PXy8fYnXtPTTWnr/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('紫丁香mg')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwPibkPVfBTu5l1oZXg0juR6c9Pao1E9giasAEh2cpCibibI1aJN1cKJiclCVLMCzfV8sNXE4ef91gjwibBbQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('月亮')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM7Tm0QMkoVujmRLyxRNW0sH6ZVwiczfNURiaTgjSNzncYS8YtFgygA5wn5xcmBsX3JeKYfMEfMribnsF1SdHCtYZzCicuAH9KibI2DU/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('爱在心头')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiauzBj8gMwqQBPoKZCyUGoQCUPzSxKEX2gDAMJ4GgTOJxQ4WN1d5urUBAsL6jibyxZdYZCibqmm29Ds/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('丹丹')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP882kzBSPHU3YsRefY40wCdfPKDyHan9TUreKZEPMQZdxNquXqjeg6t5iafwPWe1oF5YOefDx8l6nXP7gu84ibM7u/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('我和我的朋友们')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP84HlKKb0XbZ6BtyYwux8Smubkos7qCQv5ia3pCiagv5eU2jy8ibyVw8agtziaeh7L8NbnwNezJsV3fVBQbxLMfkV3l/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('冰海之恋')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa1kAEdpomw221kz1gQEJ5DHEFbI0m4Y6ziay0lFJTYmuWXsEYJm4mbFAqErDYkHofOkWPLgpY9jayLbcwRRODQdh/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('领悟～～')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZc6wKWDoO2KHDYXo3ANcgiaCgr6sllspchJ9NvreKlKYTC6oAoYpt9kj813zka0icanCleqmX6xUeA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('榴莲小姐')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ5dk5BFyn125XsugG0lmP3Sd3JLD2LkN2p1TumSeprS3adLFtzseOe5oAicpSZ3z3BLGdVn7TH2Rd/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('BlueDiamond蓝钻电子烟青年')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP9h2zk9CxKpHcrXWDNu7TyoEOmuR8h8bg6XGeTUicNjeZ0HW15RoY5I9h9ne9ttN8M4DDUnglEoiaWAKEOVFx39Ar/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('人生只能呵呵')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An75AzCgXzRgZib7ic1CGltEmqa0uweSYpXh91vZoZbFTYtM96ht2FDBujwQQjvHLvXa0BBKt5nviaReGLibiazGHtHt8/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('??')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ4ibueKAQMR4f4ZsE7yO5PAk4s84iaNKsc30pWRJC03ibUgqLUR8JDKW1fdMzyj61HCKAyecfXX8IsC/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('林立生')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiaqqu6iaxUDYB4AgUFUrB4uoR8RnFZYibV69HEibEjbxcAIMWJzU4m0wY37LHBXYbRroqjiacibva2eiaCm/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('周小玲')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM71icncQVoicysHwUzibicNjyKg7NRcE2uVwxc4MYLTS3RVmZtYTdceic0MgaLyy909xhbXBP6cLlhgglhtJOT5NtzdeWfoFzEZZpq8/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('姜姜')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9NaWlGI88ZKawicc3yqIgbSSU8GbpbTUib3rLibsL6vmQdiclOJ3zNCDpzDicQ3ST3RWj1Hia9cVv42h5A/132', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('丽丽张')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiagh28DtibzSQeVjNonRGM5eq6qibw8NqJYp4baEFn6ocL5maf7mtticQIOzzNA5Twmd48tk1pqClJDf/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('secretary')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM7pfF9wQFUZvt80HNSa5diavibwT1bFAYl2yauBANCMiaatV1T8kw0mVVnictXicJic6aulJX1V04gc6IjA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('熊宝宝')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZibc8aGHxp345EeKKSxVhXlu5uQhia2FMll1Mkkict3Wcsj633TBCqQdqDmSD5klUUmhia3ZDDM7FLmA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('sunshine')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAu1KKwwImHianmMiag6IaicaCJaLxEsANXlCuniciawp34HVs0ibmeDDiaDccMClDrloVquAR8YBpGgKuvS/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('拾yi')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZTw5jZPupiawMKc4XiamOsdWqrqicFibcUsUk1J6Et4zsuSzk389WVJX1esFrdUibdmIbyMSuguff8HGP/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('朱文昊')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An6ZwjmSk3GnbWb5anuZmD5nUehiatp9qoRnEicPxjT10Gg90sTiaBiahPiaFeILWQJI0ibyacaAukeN1Tl69HzTV3g4TY/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('花信风')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM4Jds85w6jhZ6lY2zk07RFX9QpNUMbGozWPPCz98icZUlkHq1OD6PeTczkZu9FCJ9aOc4csqX5Rxqw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('潘虹吕')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZRPmTyXia37DlzU4hx8x9uWreaqAa1f7nXwicgmNSM25XwEicl5LlamRxIdIopKSLtjVwpmxOM3mFeR/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('祝福祖国')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwPibQI0wQwkiciabcXcdhlWb9J2ZniaU2gnKSy69r2Jp5qehdYKfe46hZ6DdartXMb55k6db1svnfPJomvpWq2LSfuqX/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('萨')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAoGNK41NxMl6crnPygyckZDxE7x9w8jJfGrJbezvJEb31TZP5b8RdJrpnxiaNVicejuiazdIIvDNzl0/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('玲玲')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZwolWvYlqt5XJRTziazmKGJuxSPiaFXHqAWZF8qUE97961QWTYvQN3jrdDxicbpgKU4ymV5T54On949/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('超哥')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An60psoMMCJZxLs9oLnuOXVlko7YICrnCYL7pygpogWGR18paHWkhMsCFUqJiaTBYghicmV275z29l3Fl0W9c5t9nu/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('诺')."','	http://wx.qlogo.cn/mmopen/PpzMxmN2ntGmWuBhic36gia0KDEawDOaZPKCSVmicQ6sicuR0aEBJbicuZyXOnCibWSrvTXBic933LUzCz3v19awJUhibs056zFmOnP3/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('小乔')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ5JeMn2f4icLyXq5qWqxCEKGDCpNmaXJvYPh6pict78Ee3WugcX3yMo3nvgJibjOiaicDK0q1YsEO3ic4v/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('明天会更好')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZVkic6ehmV6jxwSJvYPWI6mfvoFNnO526wjv6pOrkRyNDJ3n9Wib1DibZPmBXxic6oHRiar1lA8ZQLW0B/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('何恒雨')."','	http://wx.qlogo.cn/mmopen/d4EyNKhicTARDcBoXmupBmACjgor0CcWUXDIYeKwSAIbMJ5DiaPSgbmU2sAwcgvL1mhhGBPvCcAEleibv8TC4Fyic40sTByptoIX/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('开心茶馆')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwPibwJQiaXPlLwf2WmNDb0jvyCu3PUQ0koibpbZJ6qmaBqCSmrfB5yLkvcvP9icCwDG2crRzyPMbJgoMv53LxZas7X11/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('钟小钟')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLDO8I2R3VFib6Z2Tk6UWHdepA0KTkJwEFRjBtjA6dUjEwXjicFy2quT8oLWhucRTicl0o93gXeWr3qzw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('傅子真工作室')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAqLFwHfFT3VicPhZbDqgcAhNUKrTJEYv1GPGopOkcibP4tcFRicHQ7RbLIWBuf7iciboF46dOGVcJ8gTv/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('秋风落叶黄')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiao6cDLyqdXGgvVicr2OpG20zwdQXbCJOn8kbHwy8vjTeB5s0YoJPzicEgl7NKgCQBs4Ffq2AED4HWO/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('临沂佐康专业祛痘')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ0ep7odU37c40k5YbLC3QFAZGiakrEQ0eOic7JicLLj7wlZeQGYIBQPvZSFN2NjqvQdyUv29mKAIJ5y/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('幸福的思念')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLBSNsbIbbEk9ZRFbTndzq6PzN2kc8icbJIaibKDvL1EfoAfu0FqXPppicQOm3wgq6Qq0aiaSUFz4TY40Q/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('.........')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAlAVia56TF8RZVZKb0AZM8AOYgNicEibS6B5pxd5Gg05khuzWE9Nyop95UOnqWqtLEiaMqfrKMlib5pj0/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('朱朱')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2ICDAxmhzpKolmqUOepqt5oRYkssPViczb6gjGvic2IWeOn1e7HSTMPw8epwhfSQTu8wHeQsL0kwoNR4VJtoYiaicZ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('顺其自然啦')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ38NJrDT1JXZV4UfK02AkADVtEEeMwWO05c7GH9w4FDPzJylKA4jwcXrqDJLH6XyrIRPSKwe8FSS/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('到處亂走')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAhMlS326YQFVeDePc7Hvh1bTibBnxJQVibZiaImrIRwspPadAhqNYIf0YycPcRMfHe8HX3Gf11ANbad/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('缨络')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiakibv6rhshI9WNfeQV7gicz20kJUurSCETPY7CYK69bLQNXtkX2TTRkoDGDtbgSn4FTzazHDeNvEnk/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('红酒，')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5XrsKCmRlDzESfW5thdl9AU84Lpc2sUld7n2iaD3OCaePVUEdCPua9tiaGDaIBmdy8ShhoC1WbIibWw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('段段')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLDiajYvzhup3vwKcDC3qCsyUwibZISkc3HgiadtOicUcQqCaksicU4yRT9ZOXicwWAhYryFx6uOAN08SUyA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('聆听')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZyGVaxVVlYrRT5MJYe4k60hh1ibLeAjib4nsd04K3ooBKic8QiarVKr5DWfSIqF1RZRysjzkicBPqYCy2/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('简爱')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An7jnZQ7syQWaxdweOSyia6mAItuWEdq2H3WSicaOGr7dhqRA41vJiaFn9HbHXbEia4WryLZhjBD9SuiaSls89BDvTc0L/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('半夏微凉')."','	http://wx.qlogo.cn/mmopen/PpzMxmN2ntGmWuBhic36giayhzo9Wgw322HURI6GqX9hicGobAa2joGcCOvzB71aUMjU0VVJZUpCUVaIxLp2ej3okItxTGtHfHz/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('好名字')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiaiaTbibTeLibgw48X9eu2Hu2VcMrExhxXM9gW7rxAWw0sNyUgwYyGKiaBpUdlE5wP7V8BtiaYNCibc0nFc/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('缘来凯恩')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiaozP4HpHfaNyUdIaWU9hVTbEu0YcQnDXhs0tVta9VbefSEMiaCCfG0hn4iaWriaGTxquCrYcjfI9hkl/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('你是我一场好梦。')."','	http://wx.qlogo.cn/mmopen/PpzMxmN2ntGmWuBhic36giaibYB0uphhWiasla3lfiaOcfdLbFiavjztvdua577rtn6ejhqXeX4b8iaq1QzNVVwSib7g6asz7yjqdjAs/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('A小瑞')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9g04ZMLZouQl51hJFdedtEQR7feiajIgAcTwgzxBQq47YYuyxFXIhvaBd9BVgQwCANR2W8bwAhKhQ/132', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('一生有你')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8ibBUibcDpUdTA2ibicvDnMfbtM0Ye2epcj5kLsReStn7ibzWIhQdTibmzoSG8BNeywTvAZdjKpOwBQvicocibv56Sicu0l/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('王友胜')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa3aQ58KWMamlOFnaWy6Q9q4qIK4DvtcXDJiaM7EEmxMx9DeyFuHu0fn4A4sqBnRtBficSphrKa8LibD94YTiaf1545B/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('爱如空气')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZTow8FJ7LjlibEQaQK7MeWWYxUcIcIibVHlGzh50Zutn6G72LtzHeViclwh7m2rib9LZJJV5Qq9cKq7T/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('舒:) (:心')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLAIh6Xbf7uGbcLvMOO6NTt8xqiatdw8IaSTN3RWJf3hMict78KI8PwuG3e7zbcj5A2xNLDzoLkEelFA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('明')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAhRmcSlz10ibnILNhCOUnYcD4cLuYEDxLbqhpHkekoUErM9EuyuUiaEXOBoOHYhBIA3xOLRa8vHIgE/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('秋高气爽')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG8XUcscCzosdibRszoBDN4mnatjGfoSRPmM9xDMicibgVgX3icpYd7zBXj3PArtbs1GLMKDoPYhmsBJ4g/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('欣然独笑')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ6gJlItSRlN9ber4afCnnibAC44XNAhpdfMOAVG6Yxmibpz4VDGkdDX81EfyDyjkWOmmegV3xibV9FS/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('hd')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ1YB8JeHnDaDHNSJzktRKORNNjOS7z4Qde2IriattGRcvzO6mibuMnnzwx9T8WNd2ib4gPaa6aaSTQZ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('孟凡利')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLBY0LAGBQl5OgNebffVBfemOSExnIkCGusia5FBUiaXgn58cSXNoswKTEp0aHxcfonaloJVUkzMxibkw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('???')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiap9NFVy759e5ibrS6dRg9bEp2y92iapo08WUcHic3bIbHZribYuyuL31LvSiaOfhmZgpKJegpDqAl1okH/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('?唯??爱??ll?ll?洁')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa0eqIPvUcNbfDrHY1TfWAZ3woDTeDJCYbnjaMNox96gh2y2BFmuRrrzmEUicYNLicFa2V9A1dDxohxQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('彭金安……全景广告')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5auQibic4FEsXhPod2hYPAUupJ6Kleh189mMV1Xyia8z3yYJpMcEDSyX3KWHkDhD6licwAza1FT36RBpz1l2CxO4fm/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('熊猫')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZS61cbeHVuDNFqmhtMW2VV54nMtrZWe7KicO6bE7lCvgGBU80yzJUMWafVCZVUCr3lc7J3xCjJwsic/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('路过¤非过')."','	http://wx.qlogo.cn/mmopen/PiajxSqBRaEISkkqWY4XSo1aH9tlMzIhFbk3fonrdwg6QfK1q1IUIohT2kMGAfQKkvZF3ZKHMEBeUoy596tMv8Q/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('A000味道')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM6K8Oib7cpa4FIYfNZf8MTmcJt5TS8RQeas7f8yN4p3qxYLoIvibMicicOPu9W6fGDufhK2iclibtzXhgPA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('宇宙浪子')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ6tvCfhxBK6KnsmibZRkRq15zaKfSJ8kpz5Uo3qibiaSvzzIg9tJxBga53hMFsWm9WRWGFXk6Rk3F18/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('?')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLBsBvHaWibiaJnJBfhia9NCwvncdftT8rf4LvU9JMFvNCdfFaHNslTmMxTwKJO10LMOk97k5UBwpdppQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('小脸迷人')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM4jl2iaia3TFFkQDYDxuwRyiaqPRQkF2ZTeaZmd351cnuicZJ18t5dxwe438gexASHClib0jnXGMKeTwsTiblsgCchvL40YYDs3Og0YE/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('强哥')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa0ghvdMQJibTeypvicx6bOQnRWXs3AAfsjbrZ7xrCiafHHsw3pSTSwAVicEGWPnJZfP6mKibw8NiazYhg8gUribEWtMMMX/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('刘健')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiagl6EqhibZiadLwsfvqyI959xvlPOvJNaeyibrptYHTNYgAZ9VGFwTvNheEgUMicXvYyl2BRUM5icqXtk/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('风明远')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZfnJG2z3Tic1Lgcd6ibiaVBwwRMsa3FrGeXOQ1ITgGkQGzWQm0IiaLoFHyM5TqibiaeCibLlffBBY6J8SicJ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('尤文成')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAuTtx2tXLBUicHLE3s2DosrMexnoOWNncISkR40FCicdA7Pjpx762vVCZ7qNCWfRvn44qILnfVGQoY/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('真诚')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP9cKJfeQfxBXOZNMKFjfOMz9iap5Jb6Aoxiap25YMA1dtODt7J4QTYZ8R5SIA0ibV1F1r67JJibrDZHGCmzVickMZPK2/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('香水橘子')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiat2zGgibR6AsITNswHpDfuyVKv4OgvdPjKCIMlZYXLPjQAZ5pU54iaWlhAXkiafJD442ia33b6wTqt7b/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('临沂德龙食品@颜世海')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZafsnbsIvZOstWx5PuXk2gcicic2utrsO3LHxXoJSxcZ1PvibOZpSIUxBmemxajY4wJ9QhmMqIL5ic9C/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('王建超')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAp33WaCrvR3uYCg0AYnIA9XiaLYJOSgWN3LynSSqTwDglOzTqI8uuibLljLYlzHLYmoNicv7xRG3IYP/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('  ')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZeujkWte85wprZ67NicUsLtwx5vrUvL1gfVmoqfJDpvQaPS5xeVeVf9e60yGS6SZRgd34sxE5JSHA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('商胜庞新刚13573968696')."','	http://wx.qlogo.cn/mmopen/q3ETzrUxSnKbc0auflJ9HrUqQd2vZqR5BiaDKDBFicic4sUmLw7y9ZB6fqgMDtYA6ibUljQrGKbAUIKRHXXc2ibicu85iaZQqH3AAhB/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('大风')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiapdhzISgQDDHYOWNFUGJjMzgfmMqnDC3JXKVQOQia2vNblDdPVnDKgwlVN356H4LE3swvbXEmBQt0/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('陌上花开')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM7Tm0QMkoVujmRLyxRNW0sHghGdNgg5biauMsW3lSKf1pSNfc1gvU0t8DibibO3v5zkOOzF8PDdxqRTm2WPNE3UQA3ibyUUIZe8vZY/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('彭宏达（楚枫）')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZR6w8l1WHXxCY4etpGH2D267ictNwEreDKCq0bB8Oybib9dTbR8ZMDsGODTeJgFicNv1haq9fzHfQJB/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('豆豆')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM76gyK8SGAHNmTSwL0ianyS9v50NT2oYic71XgA0XpA5bZ2PsDgTR5vrxLI7NjsZnTeBpIrLYyTyosNcolt262rwrXI7DbVyAQdA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('蓉儿')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZdz552AQIF4ZKnqJryAZnOc2jmbC4uNdKG3jMrUf0g3E1TOfVxyMxM74cFouSK3sJAleCkxFRuIr/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('吉祥')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An6bpicr33VcsJC9iaMsNYsia3pYtdT1y1tR4J3Jvk3K1JSmMQvibzJhtb5ibGNMX4bG8c7Cg3GtRotvTzKe4u1byiaTbI/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('A\"曾经沧海难为水')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM7wH46nlgxrmOMpRSEB2MM6Jdlbj2ibIUkXibsdSTUiaxadOPxruBcnPtK2OmhiaQwuNI7mG2XvHSgWBg/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('努力?才幸福  ')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZTkj1hVdmvxHNwjFlIGEQdNUjXMNWfHVGJsibicxzQh0Miccia1CPG656ZXbbs6P9FTlOeVXfciaXdScA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('青青子衿')."','	http://wx.qlogo.cn/mmopen/PpzMxmN2ntGmWuBhic36gia4rbQwAVV47Qq9z2akBK5OWPQRXSkFdgmrFKibXzMgDoMoJLkyKN60KCKJlv7EWmEghYBMN23IaVt/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('王发丽')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiaiaufxhQ4Dwc3YaCGueL1AueJue28cxh9PmQeFHL660JHHGZRcF30zXD9LqameZS52crWrskYwUYA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('中慧')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiakNIXicpPLykt02IXYbqFDYSV8ZbeodQoVGzd3PdaWSypibfUTibVB3oT76O5rorLfYUmw0d40TSlB5/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('雨雁')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiaiaz8sLiaTbQVWbfGGvibiaNSjYm1hxEeMyxMNNTZIdKBXUPzsNx1xjhNujiciaTLy6Z5dhdOa2YGhpbGa/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('最初的梦想')."','	http://wx.qlogo.cn/mmopen/PiajxSqBRaELticgNudgPicyCvSk3qdBPvCc3ujN6WfFalUJTVyDHP9dicRITxYLTfdXxB07vz0R3EqbKM7ciaE5M5w/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('东东')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiapvuK3Fzztaia9leyPxicvvCYmo64Irbs845CmjdwsRxEgf1Gy9iaQbHdogMsy48I7g2vNPfCyrTJJu/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('卡卡')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiajHCcmC27T8DsqlBJHICyMiawGdUcmEvkfZ9VlSkp74iasFVM2duMuUFgsZQjpYRZ3ibjg75RrEDsrn/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('“”一杯咖啡“”')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZSKQlucwYZsCcx7KGSWtN9sDrgdREPcxUhvrr7JTHvsDia3icY6mDffzrMXZiafibtqD5JO5sWO1YU8d/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('冉轲（冉氏磨坊）')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLDcnBcEiaBxedU6E3s6cRlSdGgh2uV9QibHEHVPvEU1pfZvPHZnT2Rw6810C5q5799lHjC5Pv5Fsy7Q/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('ゞ﹎待、續﹏゛')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG975cxfCIJgIeVjmwHib1WnrfeNAzNeO51rLg3cSMg6Mh7ycBE2oOeXjyibBCGm0nZoPuVQfibePbI3Q/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('Wei?')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGGibm05ibQsLnKmsJ5SmXSR4EQuvJhQbBiaZ4z7Shg5fhqrCF2xjLZNnqnZFTl0dhVfXHqLBibEb5hrBhg/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('风萧萧')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLCMKBld8yKEXVGicv0R6Et3ef2NkzMib92siaoIITdgM7Qzg1Mic4Q7vabqx4jF00h3z46kU6sAYcvFvQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('朱传祥')."','	http://wx.qlogo.cn/mmopen/bE6AkhnibULN4bElq48wpYqqQASdFxjEQyAbhCQEB4ibkxR4HsGrQV9JcmumJCz3jCP4WKh8VSQFH4Msia14KZWMiblgTzIQ5HLib/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('卜变D爱 ')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8EL2DvlJIhibvkyQGL9sa06uichBe5YjwyEOlym3mBunTMG6WiceXsz44ib7QoRSgy9E7nu8tfIsSH0a5634aibCMCw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('晓煜')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa3P2mejuA0MnxM0DZCv18ZMZ2l9ic3VaYKXjuzR9oF1bwq1wLrBBOZGiaUdZLKElHCANXP2M1IlGdaATfpvwoIAEf/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('~ 瓢江湖我沉浮')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiakNF4nYeJegSEV0sSdS7GXJziaCdhsOdons30dauDX5lnVaprwKGoM2JUgelQl7dEvYDyIhfVgCqm/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('怎么可以忘记')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ1m4QXvQQ9PfVKQDCPFPX4dOEEh3biby3l0BK06jibZhQialPWTAhyjtY8SHg1JcXxXZ6JiaiaLDicLCXE/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('平凡')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa26iaWYfMk4TiaF2pyPNibV8IleSLevlNEsBr31jnic6WH4O3mPBxf8oV0cXtB3P8YSs6r3vOJDP0p9hvNsy4Hd9ZbV/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('唯美')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLDIOWE948qjzPJZY2t0FGPxHcDAiaQu1HPcvKG18VsVldmBYVPKxcjiaHiaicbAucPrv12zTLs6gQIpibA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode(' ? Shuxian - ?? ')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZib5nKwV3eFU7LklxcrNzGfLtAicGopdC2NmBZea5Jxa7HU325dKAe20Yc8azgka1sz3WRVNKhSl2T/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('小蕾')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa3PG7nqNfqx8IKBS4TLiczMUXKvX6Kw6yzJ2wXy01YHSte0HGXJ1iaibZ2nUYVvGicjAiamqxWpibarKNic5I1vXpLBkDR/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('蹦擦擦')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEian8icBmNu0dF7QfhX6ZQjBF4yW9D3jJ4ofUibUB0Zax7ic2fHH5p7LwGQ5Doz6FAhq89SmN7z9kzzDd/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('周 Z Y')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAvfic4wpPvIorCt94yEUrlE3GJqTHSqdicWAqdecLcXLJeAKYbA97acu2yrDpTH8BwiaKB4XLkDw7xb/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('琅琊')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZVl6G71lM6ZeicNfIGf5mtmpM3jgtSibmeJXXKDd1rpCuWEa2t4L854yzp2Q9eBjScgJw31lAwLcug/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('金诺彼岸')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An4AMoGuXbuM8QoEwyxdQYUtEzGWLOicYlibysGfgIpQSoicFIP5rlbd4CQXc63a38dLXeHoOU4E40Z8A/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('阿步妈')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiah0ee7M8HFY8XGibCUkVGeOqq01sCfsYlsL7c9AF9xvmP8ZHKh1ice0CLrUnzGpiayCcemRGSeD48r1/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('海萍 ')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZZkWSc0ug21lm9lX2XhbDnRGhhk2fxXRI66j4aor5ed6sd4oVUiaUCClo87HsVjw0vcwY3dNDLibRx/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('田甜')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiavcJ3pEgt3Fc58hEsRhRaiaojoTtMhB7CFjic1q82kgy7rozuCaJd9CZ06qSoIPjgWbicH8NYzfnLRf/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('情尽--为你画地为牢')."','	http://wx.qlogo.cn/mmopen/PpzMxmN2ntF87glGvibd0356wzQNowEwfxpARDF8nGlQkibFibicGT1NYFibmKFY8iaMibTeXdTJbQ7evLEuHWCmcyhcxKVG3kwV8NX/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('俺－已嫁－')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLCq86PyDHseluYm0XOIK9fmrDQqvkYGNia5da6aaCocqoHic1mpiboiaGoohoAvlGYbgdq3yztgfmpT8A/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('溶溶月')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGGibOX0kMH69HBFcM1ib5FY3icKYP8fQ9Q7xoG3v3volnY4XPyML9t7s2rOdzSXdhFjj18ONMJvllbEdQ/132', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('幽兰')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLDcSMHgSR6ywOdfc1ykR8PiaJJ1jL8j11eibOYF5icFRS7BwgET3183ymNjKUSZRtssSHAaZ6jyxM9IA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('轻盈悠游')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLBOOe2H9rLL1g7yMSU1qyme4shvfLNHhxicf9K7iayPHW3sZG8AiaEK4H29UicX3zUDfKsvqztq97Y2ibQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('＆夏天的冰＆')."','	http://wx.qlogo.cn/mmopen/K3zv3LKyAibC5jaDibWbJphmK04oop6yZFcH3qP1s3EVWNcgURic71qD3NBm0xQn5k8WqraB7iaYN3yRn5v9qj8QW4ibRHGicuHxRy/132', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('菜菜')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiagnxdbibfDsT1ftGcQLIo5BZZ2vrmQNlKawmiaMGop0Lc3Se0sLia3n06XJtjdRQTWF05D7iaIqNJxwS/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('英子')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwPibaulgPEj7icsB3rWhFm07BvziccmlZERya3EUgm3Z2n1U4fmczDZ3GPnhicnF6qFw8tNXDKJOCCog6SywKGDD5vP6/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('阿音')."','	http://wx.qlogo.cn/mmopen/PiajxSqBRaEKP1AI4T0Nq0dHSY7De0Kbh3tyWlibA4GoLiaEyibIk5wqpYfAhkQibJhz9pOVCEtoR8nyJPZG9KhrOUg/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('丅一詀垨候 ')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa3KfcuibsgPyps2MVibBEq4uKP8IH0r0J8WhenvFicLTjicaXQHO9nsLHJrgjk8IXicun4T6dRiaCNuXMiaRJEwBtH2DVn/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('真情所在')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An4ibzlpSnMHoOt939ROB0DnHHOib4OVzqkHFWxAcqYJsJkh91uxTToxicKqwVQYibhlRmY3EcQg9RCKy4tQM7fH4X20/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('?﹏路遥知马力﹍.')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTApIzc0B0VTQKUz7oYgt1vOm4GWpG8CgCyR5oTqEGEibvNGaDqfHYah1xyPB707hxzRj6MicCjYMe9T/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('哈哈')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLCXM7gKib4KCD8C8RxvkumIWaqAg4MumefZ2MAW70WQPxT5ISkoWRc42fdGQ3WODyLN0bicc3bYIFBw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('张翔....')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZeWxnvkRibY25HEmvRPBvWiaST2aq6FW8Dl6sm1O1aw3Qxp7aBYbYeZmibaf481Rt4RKsUgEicxmEFTj/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('卓洪斌')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa3DCyFHpibQdwUg3oYHgSCnkNtjicLqibUb0p8kwHjBnibLbicduqiadCzd7SH4zgJC0MkCzERG4ltXeGZw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('lx')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLBunI61mx8oWicIoUfe4xdbGaxdJ23tACykMH2iadnVz1Zk42pf48sFaELGqvFnQiaVPO7H2QmODic2Qg/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('刘存山')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiam8mcQxXibGvEnl3M1lTn6XcT53icleYp6K9BsCibgaz6FHn3rMIgGcPKbMHDgzlvc9nplVXHVA8pNI/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('李飞')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLCTGJ1Y1yLn4d4vmG2cwJicicdOPbTtbxVj0tBicU7aGgtosfc2qgpjA9mibPrPXR6WfxibrboaeJq6cWg/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('小到中雨')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG8saUm9Q88wn1Sy1uVCFZUGkYho2UpxflefI0W6DPh83iaTHPBHdrnhJiaya3hFFO6L2fkRFVaUrQLQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('a~平凡')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEialfGBD0AdEIricvHkc0KIUjdUZzUr3FicU23gNopURw9lPKuD6sKEE4hBSZ6OlDZBO68mftCzFcziaM/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('娜娜')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa0fj1bn2vBib79RbwKC0SxAXFnbtKjIlpyhbRE2c5iass6mS67m8htic6dEnTVveSmZnSYmHXQZsCdicaO0l6ibDP9So/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('金蝉子')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLCgDC0Y15Zf6dgsskBA9WaKbB1POjTDRnByfUwecXBPouB2HdqHdwACO5WtQyHksKkZfkpib79xabm7cPkDibheeEXgvEb0ictdY0/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('21慢半拍21')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZYHeD7qrtrYpSJLjJhzT4vl9mWgt2uQyqYYcXQFYfZjTzKZibELb58JklAd6RXSgomY0mpm7UvlPZ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('A')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGGiciceX6gWMxjz1GUjXHZe4uPLJHdaHgfPkYI07uCYaNKXmEtJRpLyFeHX19JxCakXTxLsF0jK0homg/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('嘿嘿………你懂的')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZX5M4ia0wFuf7H0tvQDROvMtxYTYpN5RbvBv3RpTPJPjjs71Bj0cIcHWibtjBSanVQ8seJKs9HM23A/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('田靖')."','	http://wx.qlogo.cn/mmopen/PpzMxmN2ntGmWuBhic36giaibWhcq1Tj1Nv53kWr1adkE37WANkAM0bG3TOkH7lAPMPuSS1vjBTAn33GlZn9wN6REI7NJtGCZvM/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('八爪雷')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa3oetvWZ08iaQQX8Ts2YeEic2ibuSGiac3oAS8cX0wTct8N6VbXhIibBw9sybHF2ynichnVwnfOUukHWCcpgVfuWQaW7t/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('王丽')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAhGO8tkednm0S0SAS1IgcEhIIcz6Bh01R68HiaVebqmmOymROo9dmoyIGdkZ3JRfRTTkNRiaSaS33j/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('liyan')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiaskJW9ZUD1vicHicOA3geRnpVRh6x6pADVMNQNqbSrdWJJN6XlJvn1UOImAV9kD0JnJjStP9jWibzNv/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('陪伴是最长情的告白')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZZnRHXkUq7KPpyZJTzvdpcOGJV8Pg0WBaKjiaD7jA0ME7cltWeNDNuAgYW5KMlZePJVQgYErzHQzg/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('立夏')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAlPV5lcPianW3l7ZFiaLpetzkoPTqaCHvEwzkeK3ww9B2vJKeicKPdV4NebaTtOVEtgrQ8sY3YTUZxI/132', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('静默')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM7fIVJ3C6wCP0MN7v3CgJib041FfmcxeeM8C5YCXCy8ic54mx7gdF0OdibG0k6GuKTzuTkN7UhOmZdtw/132', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('LOVE')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiakVO1NKpo9LbPnWNwUgKPBa5gXv5Ap5oPMDIbSnmRSuj5ydzQibJLdczsoMjQ7gwKdyPGIAiaqvrlE/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('夏天的风')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwPicSv3ZLM0fXETNUueByuvzdb6PSbHtwrylFE9mpdROPoIicwcQPRjLFR2WkTQXvZ1Efz0pMzOQIENc9rWOCqKAXV/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('ζ壿街頭↘看汏蹆♂←')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZTQpHKb6uSDibg9oicIp2HTOwJAthugly9RFgCvvvuu9dFbaptv5lVEms46qMBsT7dNqibYeThaK3oy/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('桃宝')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiakBYxqG9atywONNo6PHQKzicLEXiaxXZCr5vcZrlkLljhW9ibUXYaXbluFUEZDtVXNs1KRibJMA0ricLl/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('胡LL')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZY5ooLoeicfq3WKgU2R8JKQytOVJDFbdKN3pEl8giaxRx9mJc5sJO3LZVJmX0vpd9Lp2GowxwTamDh/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('青春革命')."','	http://wx.qlogo.cn/mmopen/17fsSkXW8FbibClVhRP4Nia3I4wW9aPiafEghNXry7Mcboj1rWpzbIib8Qr0U47IolqPmzMO45HhuibzUc7vWKU1Z3Cwiad0wsFpFe/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('如意')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2xRmia8iaNejLxd1At4LqaQTufwddTRbZHsozVvOfOlmibQFypjiabLzGZDI7Micpxak0DnS1icCSiaC9wdJXMYeFzhC9/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('se7en')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZxVVs07Xicznd0GKzibahxQ5JZoib2OuBH9UUofIQx1QSyEckKMqKkwbm5J1Ev8juSs0ePWjrog7p7e/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('姐的范er你学不来')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAo1HDtJQlOxJ799AvkotTQvBqdQ9icZuGqeIAw8MjLBapicgyVe4ogvtibPQjSOpmW9Nx44cdazTc03/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('網剚洳湮')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLDrXFCI3b8LmD5EKIMGu6xib18m7HeRrt2TB3QfuzqomvzD30hibwoJXfic4xhMqDnIj5fbAAEwlnib6Q/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('带上嘟嘟去流浪')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAnBKMpanbyKXszE6h0rqCFKlF45xDB3SeGHTP8clW3iawJS1PjuoOicicp5N9MVdmssFdgQ1SAYVibzF/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('赵娜代购荷兰奶粉')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZdQzxlyn4ar8MialAnibQCr6ONy83ZouNtdPIcx8yTTIYKu7zicEuZEEzfb6Jown4xSl9H8KdgrMSbd/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('棒棒糖')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZdPHvZxjTbMic6sK1vxDUhyopiahFvV0ia8ictg725R8vobVa80joY0x74jRP6NuL2OW2L2UJqcgNtAO/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('蓝鲨')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM4JiaFacjibYYoxrrMdDNdXqPicdRsbm9nbiaCF5wvFk37ibu8bozgWIkeIhA47fZsSosKVvbGT0oNnDibQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('情随缘飞')."','	http://wx.qlogo.cn/mmopen/PiajxSqBRaEJ64pMIg9iaLMDbgD9GwsZfBuSdVbpVYy7VM6jzBvam8UAhiblxtVBWfaqrccHZGt9kpaSntSLxAYrw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('露露')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiamkzYz065YnlsFN8iadKiazeZUXXJ8YiaibBc4aAT4KLCicHC4VicECGha2Fy9MFMAH2sNrTOpQUxDluhG/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('静如水')."','	http://wx.qlogo.cn/mmopen/HsBibpPZFCB6uTTD3W371Bibu1LpxEjictK1LNp5sFPMvG3I8NgrwSuOcJTUvciamX7wW63icyMLUiaibw70M7o6M6lFsEYYrPMGE0ia/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('never say never')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZXiaZPdDmZ1GMfaiat4JNnydNhSC4vhTuvtibx4N81R4RGAh3FWUd2SzANDfQY1C0bCL3rY3LT92M2C/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('妙妙姥姥')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2Jw2DHAesjPVu5x6L73IamRYlvUy4bvxibJotGjyHJlQJx940ctiakKBEsb3KfPwlygGK3tyjKnNPAA3d913dYTI/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('Run away. 逃离')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ9xC20xBn1oXQQLBtakuMqVTcwicPHd5H6WFA9K6TicQsPjPf2NBWiaGIriaLooVRkrPPicsibHKawkA8A/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('摆渡人')."','	http://wx.qlogo.cn/mmopen/PiajxSqBRaEIDbicwOfmq8cXJFb1nicLxvamjIIFxKYOFxyraRQVq3IUMy2Vle4x3emicqxqZfcFMoib6dAoyWgXHbA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('Libra')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLBAicIBbyP3owdTNINI56MnhoyHvWyII2aZ51kSGhUB12RGvXiaTRAkjiaibE44Y60hsuWL7M8gNrcNlg/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('麦兜兜')."','	http://wx.qlogo.cn/mmopen/PiajxSqBRaEJxgva3rs6lg4xvEwDkhYbBUiaGwMLWw9dkPiafRLNWPkh5cBJQ8Sv2NxAfe6Z7YXfkytYibl9uictf8A/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('时光凉透初时模样。')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZicVjdk9FIGicYm7Fqcdib1euC9rD35E4DSmBmpx0k8xxictkXRCb1tlB9ERNs24XTeep7fWcbCKf60F/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('夜的眼睛')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG8tP6bg7gI1bNZfxyeSYP1JF5wia2iasmbxtkvx9mJBVevlQySzDkEpVd2TFKkvBswCjj0xZZ6eu7M8QSVkqIq3kp/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('jm2032992478')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP9ejiabSe36dywRtc3YaLVys9V2QjaaZGTMSU8fNdbRIibiaN3DPZrEre1oLs0pg2w83d4juOibNraabC0Ij8svddIx/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('冬日暖阳')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZwRVXkhGokcsHNnNZDsokqx8ibzhmYnAHBA0le7zDTDibVqclKx75reiaHGp6Lh8XV2qY8jFzRPVwS5/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('刘国贞')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLAqT4zZicOh1GkXp6NVoYDHc6QAu435icufdmZJEPKibxCptibibhTNfswA9d5vplvEiayuNnd0gNQpicxuaUgRtJS7ib6aaRdrCB0qJ4U/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('赵鑫')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM7DEUUubicIbGbccLPJJLguh5kqRm6ET0uPJn5rh1CU9cLTnQp2VtmkULWqibVsFYELjrqJw6aRmicNicoCstRL4uibte44m90pWUso/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('一个伱触摸卜透')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGGicXH81VGWLbVDqUbACRJNe1FQaTbSnGnewvicQTiaY6x1MicZZrytBXicFTnRek1lURiaIqkawa41ejvHg/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('释怀')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZROOvNvJLxgib2LVbnzIukAj5XR7bSXnMlaA5xZ0UY7gcYIaFv0nib7s9LPxJAicEicGGjViaaKXkWsqu/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('輝')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An7jnZQ7syQWaicGsAg1fD1dQgQibb8fXD3Xk2jnhbLCO0mQa17IibNMEhgauJXKppOzZI8JVpyx1d1oNoelrg6zSVJ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('曦')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZfMuIJLezy6k1ltvHH12Gicm2icpP7icekPhia6vDenv5Uf8Zyv4ZqQgpdSFKLNpkydsWydWpTjqDic7Q/132', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('Smile')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGGib1UWe3NfXeWE9XgE3QiamA4f5MibIg7zMFwDfibjichKtZADYhcvicatEB6PCgapLOaiabfOvqyjc9Ma95CtSgaziaOOx/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('邵珠营')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZTCia2sRZOYo3IWfULbzsc54hyjaudljUzEGLicLTNhhd5ZW87nqMQMyvWk09V4qgO4W153KLr8Lx4/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('嘿嘿')."','	http://wx.qlogo.cn/mmopen/ORCiae9DmIT5FheUiaJsbMk5IfYnzOfyvB9DrISAJbTAqT7SQc6DaKZfvMec5e0mVZbmrXFYwgvJy3jibzn1Xnzib2c1afo3bwOic/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('a1502036')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An7XGlfZfO8ww9vp5YePfnUC0CqHo4eictX2hCKfWJtXWnlia5pPV1OTHEAdTMhxVo3w6coW7JLgNKaauJibozr0XH7/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('宝蓝樱花')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGGicEEkXy0TIZ51OcHicasI8NZBRoyOiaMRawRO00eiahoXnE8DicmxydaONMiasvBGooq5ncEVx9K135kow/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('图图')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLBycpIichb0jXJ4CytdrZ6BsGrWicNiblicq8GqM3fk6ktuDgKZ6M4L4K204UMe95nLMjEVOQe61qcFSw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('卫东')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ8Z14FOxtoaz7HWCcsmicErDPY7LAeytcjnkmjmf7DWFiaj1OkPKJA3ibkpoJpfCfibV5sWNY6PKwOrj/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('咏絮')."','	http://wx.qlogo.cn/mmopen/d4EyNKhicTARDcBoXmupBmLqxVkh7q3R7Wuf8Gx08BL5lpZ2L0gsEl6bLfjPwdTuibmYUWcl8cNwjsLaFmjcJicaUPrQlM8X2aw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('初&遇见')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAmZ2P1IscmE0uibrP2kEXmxKn8Yh6ppJ7XLKEhy9U8zCWh57EzXicVzMcojxxFk78d8iaicBRhEWGDQo/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('艳芳15550495086')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM4jl2iaia3TFFkQDYDxuwRyiaq3VwgJbNl5VrTPXAYh7OkL8RlTwIXs0HyVKfzbkrpdmGZRLEeavPYrv2XgAQqkkglibRgJazDhdZs/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('Lesley')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZchdK3tf9Xa1bzNfSeAlvQyynxhdugphdjHbPcVxV9zLOnYG3jcmg0Pibibic8h725ia1SkJpgIIbavB/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('GJ')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiamuFicicq8IFcYzuoiaX1l7YdpMiaU04P55V8KzgEtvVYzdywmiarwibCLaWIjVsiaeSkyqWkXiapGlwCecK/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('xhmy168')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa0xJvBapUbPw6P4lGFmKEOc0euiatFLjCHQ9gZUiabx3fQS61IakbKcGXQqGoxOrvITauSJgSaOOlXGwLLurcuxhT/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('liyi')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2U8te83ndZX5Z06MO9A1Y3ZicZzc17ExqpAlsAgicZrUHSz9LsianMqeJywNvAsGeGFy0a4tyIH4qNDyVDoUDeicBx/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('鱼忆七秒')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwPibPwpFnwvyntQE9jWNaI0mZgArmtE5RZ5EmuBSuFYnj9nvfoM9Y0Vxiar74D3o7AeWibx0kQDUBy0YHfF9dC1D89z/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('龙凤吉祥')."','	http://wx.qlogo.cn/mmopen/d4EyNKhicTARDcBoXmupBmINVhjcnVVtQKab1lyWMTUyXwAnzEvN55IqddVNiaQHy7Qr2RYE575yw36kMPyr88y4uCnLDdDFUU/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('浑身牙疼')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZY8N1ycibZ5IUm3C1R6WhXThxdBQBwDDGiadP0TfaUicNud6KhEmhfwfMaEBfjEUnDzRo4HU70p6EiaA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('23岁，山东临沂人')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZd0uiawPHCTL0SXh99jsqpX4nFoyNWwCria77zf83fkMr7OSLueLhX7xH8DwKzn7ExSgNufnF4wsyh/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('日照海涛')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ4TNCcAwZIlGhItJDqodnfibibicLssWR2V3vcRr1Ol8YhtJGLreGZ2YInTaDnr1oN40xTFoamswFNv/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('AAAAA 奇迹女王@')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM5TG4UKMvvbIxhE6YTlZ6Eo7Xfy00U8G9omTK7YicdThYTIX9WibJm1fXG3ShOyuAIjtP0PibUlQfM1nOQ4a5vhibGXzonGluXCkDU/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('Smile')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAgMCXWB8a2kwJJzUoDuPiaGfLIaLW5uhuakvmJTWO42uY0oR5TKgMoM91u6cpapnQrD7hVY2C8DyM/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('吴艳')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ72siaalDeWExzBSBZP2icNZbrBAuJpOPe2iaHDiac2peTAwE5ZLCKdfibUDW6xYJkaCdSQ7cicndFE6wN/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('胤珩')."','	http://wx.qlogo.cn/mmopen/PiajxSqBRaEITE3NtwXwuzhlJPCHeLcA6AUdMHshphMkgv5Fa0e9icgbMyssnbpaz3lF4xNFjMtS5k9IjHUib2CyQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('勤奋的小学霸')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAjN9JzqMZckecYG1C81mgA2kyc35ubeb9ZZibL1tsEziaIZBjy8FvI5WIYEgq0EjX2P1ia6qrF95WSq/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('稀里糊涂')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8bLpPuVo1EDiaAYVmDHqibOfNRh3sibXsyjfaWuOpCmyPm6HNDdzich97GEViboMcIhIj33mcZOgmY8Mg4ABAicj9COa/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('封爱POS机')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiat6T842xJ81cJvPuGaV7PrEibg3ibQD6gwVl5ZMPPfw7u7fXBwAlqLNoCkw68AoL78h4fzt933Z7Zh/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('大灰狼')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM56SEE7cmuUQKygYMds8KHIEKbG8LK3UyRaWv783rujFka8MH1micvfhzDibtRlxkibg1lL5Xs7BpwVw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('宋诗范')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2ICDAxmhzpKjUVTaQic12RWFwGoTQeDHnQ1pmQiczAUTKiaej35hhr0mLnzCxalWaMphcDM8E84I3neTaFW1uqRQS/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('杨婷')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGGibRAkmdia4YXxjZQgIq2ZS9vJ282ia41OT65gibwnQAH4HRgEicAaeVs7su58GBGezN32MJial5OGPjpgQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('徐萌')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiajpl4RKoyTPqZM3AyiagqgSCPcibiakpzJM4PL7zaccZEp9FiajLTdhYPxBTwWchcbBarbw0WglZfFwg/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('叶子小乖')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa1Qnggia1hnrtE4p0icBlKOdxYiamfKay1BGdWicViayBZXuPyGvb6tLoiccQNuA5Nu06kb4kicmlsviaXBKvbglS768uht/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('宝贝')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAiabHHicsJYYKWWegWicChuVtMjZib0kGDa37qjOwhzPSWIBP7phr8aVKDzc0ahAhXCOibGWwpe4BPKWO/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('百事通国际旅行社-郭民')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwPich0LX3lsaKwib3vIXVNtUVpE6g2GoGPTnM0r7hicLoaYQcoY0jHKosHque9rrqeInkCKhaWSNulF3SfvYX45290ib/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('赵')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8EL2DvlJIhibg6J6ZEU1uNMBNZjetCmLN2HGHZUZ29Am35ED3uEibH41EM3bU0BmXsfqK0PgDyJ0P0bJFmVMBKZ5/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('#^_^#')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG8Gow5iajgXf5r7Iu0FxIEuibbT5BQ8UDvdeAmiaDBJcAYEdXnTJcvzhCBz6q9mns1BKJnpN6WHOQ37eTz7obzbgz1/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('奔跑的蜗牛')."','	http://wx.qlogo.cn/mmopen/cjNgzGcUZCRHia86wjtgv2mesPhhw6vlureRxsdAX1xox34MbuV7iaKVv3mchKU9ibwnriaCspgniajrn0J6ibcohKrYM6wIJ9aN33/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('邈')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLCgDC0Y15Zf6QTPs5QaRNzj0VjLadsVQ3aCibmopCPaWdYcSH7r8cPg7yU6awL6hbELHticuQCvvZTicLfrEO7dgtmKlWGF4WQq14/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('那年夏天。')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZa30woypAWszTISlGdRWUDPa2q470LliaN1401JrUZYuRct6lpprbXGmkt9KXH97z6lAaunzDLDT2/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('完美幸福')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGGibvSSZoibWibIkXWGngPeicYrNwHNSng8lzshPPen0tiaZXVLJlYUhGzq9cAOGENE7u7G94XhFhzIxRMnaHJU4g0rCM/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('大西瓜')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZbKoeBsrrnP8HjvvhAguFPTuRs4EXnsEBk1fJ1PctreUvESEWMOnT5H67QrSfJ3L6kka2pBFMlIE/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('文静')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa1PBh8X1ib7v9A2Rxd5tncRrRLZ88BicmFnuWnOxV8eno11FmUocJfNG2SRiafjmWwjLOUEbg7uWfVib3ITMiaribSDlI/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('已定，势')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZcZLKdJOCnWAtuf4oL1icL76dtQt3mt12ZhZF0Z3JZe5FCicFZXbXzD2TtNGO5DpXpxJ9P7Oib1Pjj7/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('王林')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGGibmu8SiajkRVNDnMz72rVgEWlvOIs7RsHjwuLuL780dz46LwwiciaxxyL6MahrdiczibHAicytMIhQdtULQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('张元银')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZaXbYiaIMib2HfJufbsiblukoic8eDZCyXPXezuaXiaVyB16sGQ8FpKyjLNxYdLicAYRpzWa7p9l8NP9MA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZxSZ5GkILhIrxNbuhMwtR2PcKELBib2APyDcASaLHmPP6iaSHVsSaA0QwtLkjSyjDDBWMBTwMeSfFib/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('人寿保险')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5Qt5x5zhgP3fWA2NwCXBSqcE4sTcPiabRlGQJ81RzaEvxYEIW4VDDCDb5JTy1aqsmoH5BnzLn33jn6cmThrxhwH/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('Jay-Z')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ83YEsH06FwCmVdRdvLrDMPQhofOfOCQYySPzB3LIfuxCVqicrYIwq0SJbubPZ3u2K4lqXhzuIvur/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('刘苍')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLAGsERWLRPY6exhZSPSFLtrKJnttA2SqQQX3utXuwWCR3ygg8UOjeWSQ8m16fVVubuzknhLOmTzqibH9y8hpl30twA9demD3fjg/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('小猴子')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiatJhxueq3kBYAmjQkwqdZPPsoia5Bf7MAdNCDGPQHMASRVYndEVgxhNI01Sa5LvdEFxl6W8Q787yP/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('feellife')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG8Ya4xVF6Gz85tLgmhMzZ3xchicrJIPNCMLBjIGLWicrwzwz2hgaT1hVrCsmrMJib6ibZDibic3Mcy9A7LmibGkutjoEFia/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('风')."','	http://wx.qlogo.cn/mmopen/RP7VvDVNhRoeedSxGJfKR5icIGAgJVjbibkh5Kic3YUyYldoGfW5esEVyiaIOhpEGOg1NOrjMEDS6F1iabo4lnfWIXgDDj6J8VMVP/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('duan公子')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM4s1qrPqyWG8hvvNIOhARfbcMX3tDHpVJ0HPE1MdTHXm38uNcia5TFJv66Tn3cntQicWiaNRO7GqC0OFPNAYcZVn6FAAW9b7l9BNU/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('责人之心责己 恕己之心恕人')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG8L4Oc0LIErudUGibHlyw639w2HJyErsuOvZLaqnVK53T2TOQkP4hcej5e9CFwxAAX8PibllibxTicyHw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('A蒸汽电子烟总销售')."','	http://wx.qlogo.cn/mmopen/G7rHVFI51IlVpmpmA63rr7WSPDicic8meuJHN639efXptbqeynNuqwMScicQXiaR6gibrjynwhdCe6gPunXSFlh28CeGF6AicEhF51/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('王林')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZzt5jBB97pbEU62zabm5jB8g1jlqwDfsuJ0JWMrFeemLJvjzLS2wzImibPMXIttkBJDv14BXklLLp/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('平安邦')."','	http://wx.qlogo.cn/mmopen/PpzMxmN2ntGmWuBhic36gia44UDWtNaSh4XDWTw6fCZb2Wg6hdDlrkdics37ORNyHDg040vxT6BFddLmQJCUyrZV73Vf5wSe24K/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('刹那间，')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZQ7X80uyYdmvLTsku2nbpWBcy4wUdsRkZb5RwFiaCPzbS9rPtiaWczOwffib0aHEVv2Pnw4hlM0YLJ6/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('凿石得玉')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8TvxB5O8YlP4TaJibE7VsCaEMrgjmtDbibSBcErcCQibBBNoux6XyOBqPmQqmSSUvpq5qoafICe7ibzryNCNXafSjia/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('海海')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZQWwJlzNSGnTZDUUCbqr35BR2Gsz475XxUC9vPnAhUqsbtBPH2vLicQsQUGmicXv8TgX539uy5OGWN/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('木木')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZVTkJvtlontUXA8Lt4dcEUDWib4pqIlD4IvFJFTVd2wursmhiaYNHw7M4wzrwOsc931OsWxA8D1QQX/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('梦幻')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An42tpFGtycHx0exEcIEicyrgRLNrdAbgnGIIVLl6utv3ibOm2eQIWNwvibia2hBqAMiciciaFXHm4VcLpvFpbJqnprEcyo/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('朱凯')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ24UvicpazCvuo8hCad3AnD2YFagicX6cKxiaFqH716EjRiavmRpO6MaeHLUX36Mq9oTrafoRYJqywric/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('梧桐?雨')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5gbf5OxgbQWAv82KpFm3yeiaLC9qOicvJWx2LnhcVZ7LRWbOy2rIPB0ahHm9RY5wlYBohicEDpr0gNpL80pMrvYld/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('芹菜')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP88iadYN3hsWmLxHBQf426qx81oWUqCJ46c8cV1QfWTTXHgDrYDfdPssn6uDv01RK69hDpxDk80x2mDdbKaLx8va/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('学亮')."','	http://wx.qlogo.cn/mmopen/iac1zhub9RGlIhLhfGC0CwjODHiaQUOXZaBXL4fWmd5s5ogzHM4WxCcPQ8JvdxRfXnBDKU0a3KuzUlynZGI3VVW5ibEgzudfNrp/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('')."','	http://wx.qlogo.cn/mmopen/PiajxSqBRaEL6u6wC4ISyRkooCCQWkJKGmOZRah4iaShct9VPicuvWoXJe7wmzyXG2ydGicFUDZibtdYwtCTeZNHqGA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('高伟娟')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLBXAAcWbY1aPdvoGWxBnXQBMNYCLZz0eMMTIl09K4NUl8vJIbpxxqagPSicBsIMdQgRichW1iaMZlPqg/132', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('百步穿杨')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ7PPpYuiaYjNq6XmGKlPcs3MqO7puH5ibOCY5p3s3PA3whU6zPvDe5TUasDI3uguN70Wh3q6ttbP1B/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('叶子飞啦')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZQYrvicOKVWgLMPqfSSmtGXDunaf7Ufyco5Ong4xNMDN3MfsC12v4bIOwXkicGXduuiaWyZ8cHIIQrZ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('The chicken braise a mushroom')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiaht1d2Vdpq4NxTgJ1WjTUibU5pRKy0jGda8McMOiaShszNyictOstkGJobwpH8pEwAlDeblR7Ngh2zG/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('哎呦青年@子雲')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLBKuic1yFNOxdZK6LNOHHGComhWfuicdmGiaLm7WJkOGSTBRyqmaVkGsR5XjS9mOx58XwDtzuDJK9T4w/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('许多年以后 i℡')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An6yricjAAXVxtmHeyTrkA6awWsTc37iarqicO4ybfvmEARBIAmuId9DOuPcmI69koY3OZW9ZiaM3ia6wrw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('Anna')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAlrvm3q18bWmkX4ewACOaXNJ72TL6LdmrljiaNBhOpovlcnvavx0VQxlIHhubBKQnqXMiaia3EHwovY/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('XUXIA')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM4jl2iaia3TFFkQDYDxuwRyiaqhqY5L6zMLqnlqib4p1LO0E3iccbf5mkNTarE5j2o9r24ho6UzicibHOiaEGCIaiblSBFP8Zp2tB6ARz8I/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('孙志国')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZQbWpr58EHm0n2icHX5xh0MX91uSwFjnbDd1dLUoedz1lkwYsYZ7YwticHaPWdyfauuz6xjeIaByGE/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('姜玉涛')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAmTFsI9X0cAF2icn1kPZObVLRTR6epicMeaNibrxKvxlCLM2G6WsBPLibBQox5aIbLQicS0hzbovPdqNa/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('&想太多')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ0iaia8CwBV56BcmOwnd5oSfZA5jxhf4tTONNTZJNM3PJJyB57oicSuica4D6Bs4jA3kQRibibGVy1gIme/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('邱磊')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiattInVj1axPvaticFFIML5zj7Ctlz3VmBibuo2cKMcehgQ6qrLoQwo9o0qdsrY6ic0buuXrMQHlW0zo/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('锦绣会计（注册公司、代理记账）')."','	http://wx.qlogo.cn/mmopen/qUAQsRr8QhWuSvDFurZZ9STLT8Z9JRMO6wdOo7mTL5kGT9ibibwxq8lYkYrlMfqmYRL3AMFpE3XnjnUw5MJFRYUs0YoVNaD4YX/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('霞光')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa1SibwN6OeerFy9cd7JgWtTeLVibd13YM4vKL3bX04HGrkAyxjPdR38VibicB86kunSwhKS8vokfszU0Ee8D7LkwgvE/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('紫★曦')."','	http://wx.qlogo.cn/mmopen/d4EyNKhicTARDcBoXmupBmPBiaVj0lrAhwvDYuN6IYUn727XVWTfDibFZoK1soNBSe763mGUxNTLkAqBhyiaz40dQsjYeRIlVMly/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('璐文姑娘')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM6tm0RdzHjpAP2htCc7iapZ3bJRWRuRWf2qg1s19prrGzVUGfLyVqM4Rs7tx2zzMiaLlpWLPqe1icqCA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('小米粒')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa1UnINSb1iaGjrbEG7K8Ajn3SkyWRjk20FkUQJVmO4Bee0Z7eeQegRVIWzEjUJIjx1wfRTicetrqyk4VP0UUBIiaDD/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('非诚勿扰')."','	http://wx.qlogo.cn/mmopen/d4EyNKhicTARDcBoXmupBmBN2TFlMoXbGYro4lD2Pdcs7XomZxswzcNYHOe1xb4uuPdfwJOB2Deibl0oRpze2NP9RPWaEXl3ic4/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('星禾')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiarcNRMPQFwIjFIEkHWM7n8Pa8y8LuiaHXRvWqmoPnJqAw9EH0lZAGdov8qFibN5agWzjWDk1kauPnv/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('小莉友')."','	http://wx.qlogo.cn/mmopen/C8NurUhHZ0mlWhgsqrIXVSI1ReTdRkfQoAaI0cI23yKZfeynX2NBqQxkE4qqPuibic2Jv9C582ibDM12BIjfqqiaibe6WmdhhpN18/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('赵志方')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZS5NSpQ8PjiaibBgQwiaRYzw6rZhKR3LOD5TpgZ1vfTKVbNEPqgc83z7XWBXZIwhuWrhnrdIycOicG3g/132', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('倔到没人要')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiamwKCLafyKsjFmlxjdEhicnNmxnf9WQFENfrGbibqw75jDlSYEknwvtzteb0xwpvSQMMW7uzSiacjy3/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('　淡然″浅笑')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAsd4VenUfHBOibZjFJLdBLZan0V8q2aeZ5ygLmuAnohiaQwS4E9QqOuxMuvQULhyVbJAMsp8NfJTuU/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('紐紐')."','	http://wx.qlogo.cn/mmopen/PiajxSqBRaEKiciaGcvrBI0VS5LkR8l49AKZQjdKLZhYFkZclSHRbx0efhf0a1N0P6YRmvDhL594TOxIFFNeQLaFw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('姜良峰')."','	http://wx.qlogo.cn/mmopen/PpzMxmN2ntGmWuBhic36gia8kFqIuWGPusGcK9xP8CicBURBqJKEvG6bjoZkHy4EvHf91VHmynW9GnIHY5GSzGXVibiandf03WvoI/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('A 言言韩妆')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP9bFFHia4icXMuMiapLH8euica22zzfDFFPkfy6iceUFJEglvjV7VYY5unOcCQwTUoibv6hMNBhBwvR4GRONK2K3eFyibic/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('hccmgk')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiag6vJkM1WvFo2UHOWagSB42iaAzibrhiaf3GwicdSibGagjicNDLRwSvEQanU6vMibbAibicINiabIEpnenNdY/132', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('A大泽山葡萄●干果特产')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2ICDAxmhzpKiaCE0Y9PX6pMokfibKibfLvb46Vica5zSJ5FsYCCickqMTcevlpIria02Khdd3vut3v88v4veZgfuDfKx/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('连云港石英制品有限公司  13775418232')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8D2icy2Y3wfia99ZMNxZ6UmT8LAFGUn2FShjuAfWHuexGI1NgKEGbPqYaXjypwkgcgnQs5gE724xD0ibibmy09axSZ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('张连飞')."','	http://wx.qlogo.cn/mmopen/nezSZ6aHzPGPHL8H2plcUZ2uPYcia2P0pkXOSAuAW5l1WMLKLUZNem03sWFjf4ASXZSUia9Vj9VcCEGt2jDHhXSW9hIvef3ncM/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('zhu')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ9qospbNG6DzLibmia4hB0FezfzImJvWDRqyribwwz2iayQF7dYhtdk1kEpeuN7BgkibEgedpgJRCSLYt/132', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('叶子')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiajjvW1Fn3blFge7TwGFsGTfh4VsdibGwNIBytXMLpnHdWK1iciaibeoYiczthiaRicUyMPPovg9gJ6K265D/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('卡蒂娜')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM5tbEyoAia4J5TIjicVgmD71tvAibRbNrP9OPGia5wZ3ibpNpSy37VRor5qc99tuGBW3Va7n45PAeU1PjQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('得利斯汪')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8LrjDrNuc82p830iaUCSweIDWxnPckjxCgH1oiaoFXEBKLA6ickrM69rhzmW1u8S6xnQwiazrEODsVKEObFicucQ7fN/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('花海')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP9xKZGhxSicvxLpCic0IroVCEbHUFxyzHfq07rAbs6g6zgXTMQrtvHexrEvXZ3HGUI7Tl5Ln2tNW52VZibe7fe1eVo/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('AA下雨??')."','	http://wx.qlogo.cn/mmopen/PpzMxmN2ntGmWuBhic36gia51Uibic6H44bPa6WP7O3EC7q1RJNr0ZSSAoEuu4xAD5ibg8R9EcGjWOnu6KABpMyOYGGXS4lkodFHF/132', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('?寒枝雀静?')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZZKTClaXVOAo1cfeqeM37Oa4z46iboL347YT0Y2VL3Z4GS8gMmLibaZMOSPLoTU47WQgYLxX1DF4Yib/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('An')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLDOtMkrXAg0oicm7lS2a6ZcZVk6oaIH1B6ARpqj05sp6xG08pOLvAX09aSzQ6S8gobpicSggMzZ3KYg/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('月满西楼')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2ICDAxmhzpKls25Uhic02SbZodJQHZODbBmibj5gicV2Fj7NZcOaNTAySurWMoTTeF5ibg3tjw7FtsQO5fHhv5LYKq/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('明媚')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLA7MpjiaBnTKIlodCxomXfpHcia5lVFTq7CIP50DcdfN8VuVeXUl84hhV7pV71BKwLdbmic00J6GgM3Q/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('')."','	http://wx.qlogo.cn/mmopen/PiajxSqBRaEKs2KcobXeuUOPNY56tOMgNAAYjVatHt0TOfODlnIPfOsyYa0XVnEjSwRuvkbpaHNYXlFQaaLic8SQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An6kgrqWrqzsiauleNVQYQUEicd3icnXPciaFXkvznKHZw4oSLSibnh548HVGib1VPDgnibYVNvAmaL6Pe1OxTZhB8H1IYw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('果果')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP9luCqkdhdacbCSskPkpZxHKYS5iafP0XJIFS5cvxdKa5guZiaf96Znx1dteFkqc6xfmvAvGZb5hbjEbd36axwJxK/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('和你分享……')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZTXoh8jRIYqjPK50sAxqDfObLYxLuzyicfd3JGFn8wTX9QXjwN5w2Gg7pNr00HfmU5DO2icaNB5baV/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('海边的你')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiaoZ0ibx3NWGJbyv73LY4icticeolyliacAzqquPbDdfib2geeuG3Hb1fdyMZQF3Dibxgzm7vh6q87mX5Hx/132', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('徐百海')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ7r9gUXJ6UXTRB8MYctnx7ECF1ev8kjItGO6gIZoJRnTTjgGt7IdibPDX6JRro9SNUjqwp3wxDncD/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('灵犀')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLBb00S9mUP8AgnZIUibicFahFg6j4CI9dibYaxKicyUpm5OGcUrU11I6XmibicPicXB5apfd7utlZVcCgIHQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('涛哥')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ89ns9uJ2icRiaO73FicwNpdy5EySia6iaaTOjLKhz3ZxX1AHTiag3Zwh6WStZuEtQOffnxpSUvwdficvg1/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('陈运会')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiamD5lNDo0CcHxA3k7S8Wiad6283PPNxoEbRb7aic0vpGjZT3icjhNKPOibvoia0Le08IeV11YTw1icNh7z/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('宝贝计划')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZSg0hhcVG0oRWydelmXAIq732TUcq3aAVMMsBbR7EvfPibe7icnfYPqvjfynXpoSmzOfyVG0VGMy5L/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('奥特曼*小卖部')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLCo5KzdT56iaVCpT14O8Nulib3rF3m1uvQbXdXUfj5JtxiaTDbAe4uGa7bMtMkmqPhmiaSW30EAkfSLuA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('好运来')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ9hFOp6gj00Shdrf8lySJ4Oias3paVWjWibjlzkWPlyR8VhP24629cZFZu8oXGuauu9rbRib4N3qNuo/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZYYh8IAtHehRSj0gHkoZFNWCbSqdXbnJDicpA3IVyXATGI5uw1Mibko0Esx7hic8yAejyicje32AXxjl/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('蓝若天')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiaiaN7icic47t82TK6zyiaQEUuUhs9QbuaQC2WiaMiaKZuZmv4o0d92M8Socy8gfQkaQBvibNe9TsfaGxj1c/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('以梦为马')."','	http://wx.qlogo.cn/mmopen/PiajxSqBRaEJx4KibPTtyyDlV7xicQOicYL8wcJ5ZOXUcyjbpKibF24S6qryNianMUxA37elRHwsCbLGq4sMyz8TxQ1A/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('洋洋洒洒')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP9dRI631VrUfLiaHgbiarVn4lWV7XriaMRgpU3fYHHp2mqJQnGDm20Whq3qdceqaEJU7o7SpiamVILVfCwjLlQlia5vv/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('品味人生')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZfAPHtKLDqx8XgCa1ty4ZrbeqTwysaXLcWvuDLZQcFcDyW4gicWkaEZyj7o3F5e2cOP0XibItH6PZO/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('海绵宝宝~加油')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8EL2DvlJIhibrnp9tG60hh6M8lTtjJLHTnSyMBBl6zghlia1CPIX46uExG3UKHdM9iaV7b9HA4M1HC1ibp86MV40JS/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('A:)刘刚')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZYh0N4LDtWbTjkQibpzRDnmib8y6fDHniaRDDn2daA9qwicn6h2P6QhrtA0SJDeehCxs0j5EUvhDVF3p/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('鑫多多')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiasl7eVJdj0keks1aSRNQS62MpmEGNQg2VlW6vSBLlibrevkYVpMSfVjcT4pGicHhpxGtVWP4vn6YSg/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('且行且珍惜')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLAIficpW3Tn3Tal5Rm5XcicyF97zZiclbz2WtSLhu3aFiaTpbgokfJTQYAlpgdm9xhxnsUIAANTngvpUw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('太?阳?妹?')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5auQibic4FEsXhJ20ImvnicomeLRT4nxq0mNCWuS7yhm5wzyt9p9Vcibwibo7ZiaicPImxwSoh7oS10hXdJCuwGATQvibj/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('竹 林')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiajr9hrMo8NDOYCK4fJ7bIglJPs0CLf3MFkeFAHsEfNbV6TKHzbbUx2M1CAr5yZtey8XwBIrPrOOC/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('冬季阳光')."','	http://wx.qlogo.cn/mmopen/PpzMxmN2ntGmWuBhic36giaibrpia3jyzPssqdULLHxD3iaCFv220Z8nWV9l4v3EOeic1CcNzfZu0GAXeump5yMicBBkh15Sib8HkwLO/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEianXG4s50l4SgDcP0hUq44gZ1PTrfM9SYuy57qZzV0IVtK3KiaeaaZhY7gtiacUzceuRjf2ibwgia3ChD/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('玛索')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZboYAsF3trElYA9JvBYeJYDdLmia3JL4erBmbvYib2j9FIkl2eLDdR9JIzheQ1T3WcRnC7zxX8mHicS/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('九天')."','	http://wx.qlogo.cn/mmopen/PiajxSqBRaEKibnXjM3LMf7y49bBZjPPxIL7kB7qmTdX2iaTDuSibZ8sU6cickjDFmSmfVEzyHnjwDvtzduBaEbHudw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('?奮閗嘵圊姩★')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZic27WwQic7afGJWvJYVBibGCGkI98cHAlfuCt8VxST4Ibp1aqu0cFu85TFtZR3jdG9SNxktUSf1Xup/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('伊亲游泳设备13355006027')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGGicRjOtfEEDCibHibEsibdeNbGtPoMjTACsFbgxS3momYoctq31YVta3yS8CIhVDuiba4kZAgTX8okhcNRgYK8rLz3YY/132', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('曹波')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZVpU6MCDUn2eUmibgJkA7Mpd16xXGeSJxXmRZnKq9K919riczhnkOYRyUcOavLk73BbAKUYYb6hnjX/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('不落的太阳')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAg7Uotnn2NwxrdxY2pEfiblDaiczLfhX9via93tu4Zt7e7GUNuOiaoqQ4YmXfla2d2KQb8gBJpSWSC2M/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('卢子')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZdNTpvJiba0n8lsnEtcyJy4fHVPOFcPH7VR4JU5J7VVMqGIbjXJ71WFmAWXCIrvhTGewju8HibbXCZ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('熙赫')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An4wnWxhxk5oNPjRibdkkzxUf2o94287S1Ubv8NZ0Jx8GtftaZO8xibhQNpm7NlUKfNWOVqtYHtwN2pAnfsGFGrhMy/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('自信最美')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2PToXibW0jwsCoOXkoSAibv5dKlykwMxt8PwVOhYqY1xHOFd2K66iaRvCbgxAoYMRgqSYr7KZDk06dkQE2GuEbN7o/132', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('如此简单')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZTdMrIY2Y2kHDAsqDENP5NGrdKs7FqfCSyHZr8L0sxkI8wM9Z7dhjfE3ecfCHo1HyE75C502sxkV/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('Y')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZSESROJT5vau5zicHbiabe176yZaClIfiaojSPIMlfcBIuWcOvRxicagcfdA4f522oCribsEAXkEgAKST/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('若凡')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2ISSMibCiaq1icFGpr7yyHbr6ILhsshxtV7syUiazqWggyANQNolryPpUYrw6wWRtVTia5y31VNLJpGWuiapulvKWTxz/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('')."','	http://wx.qlogo.cn/mmopen/PiajxSqBRaEKvf7gAykdq1dRU9c9cOCoUpWictdyv7vl6UWcRyZciaN4YcyKTuhvU8YBccR9Y2dA5lHbYzdelQdlA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('石头')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM4jl2iaia3TFFkQDYDxuwRyiaqRUIMoeRzdqQ9bINnSufK8TkHlBeQrLTlumibO3adPEPc0s4Zjq972ZbdPZVHEGEr09nfQk9UiaibOA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('22007')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ2WZAouA9etjicJicPZOJHnrWyvNr6a8AMlxhj7JIOHBMF3tsJxCniahcctu3Ph244PYmtR9EljGI0f/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('纳尼')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM4jl2iaia3TFFkQDYDxuwRyiaq4sEiaWwQAKzJPthY7s1cDPDgvBfZiamMhN3IUW9MnIGgkDyq31DUic4DwtByGODsedWSfRaE30PcTo/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('孙家银')."','	http://wx.qlogo.cn/mmopen/q3ETzrUxSnKYqUGwnicYJEBtKg5ddP4mLIWEM7EjFBMEcjaVvbDC0icTlhJfS5BwpmBbnq5QSQaJx1Niaf0eeHwia8LTXujGeI0Y/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('咏梅')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGGibB4mYxIucibRU9U1PQf29v0HvbLheBCYl7SyPEX4ic6mEqtdNvvrctPpjcb27mnMvyiaOEmExRczlNh8yQibwnqFsU/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('幸福好店')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8MhQ3Eyia41rWoc1zWkWmsRdta6NhibkkOxEfoGtWE2QP8rHhmM7dSpftAQ6xNeHqqcU5h5U1SCiajUhMA3bU7I8E/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('家居用品')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZUBW70MdKciciafVHKD927jypia9nu4Uabcw89X9lyBicNhP02rXBqicOiapj1VbXRa2N3aKXoudUDxSuE/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('寓言妈咪')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZTnmsxzecTz4Lsp9IOzzSfSlaGw697wq0uiad8N2dnpNyfmOS5tc1gTUeLkic9uJibOpUQ55hCpKL8J/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('大华')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwPicaeOmfbfytaPrQicKZ9CPeUicia2ibpETQnvtfrQiaPiaBiaGBaNmgKFe8wicgT6WdZyJf15s09NBlzSecTVnnTvngIyv5/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('知足者也18653952266')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa3iaPbKGRpHNcXrywiboOswy329Ta9ZtULX496KMqQOjOJkVpC7E7oGWiaQtnbGBFMKon0tKSjVnOTt2yyVAImNtia6/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('Dear丶小女子')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZbT2ElyOoiaKyBcwDsKnlDKDticcFP1iaaVPSI3ibVNWOcyq07LROTqGCDEdMicNELicQyjVKIXLH1Laxm/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('常士永')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM4EjbYygBvwvo7eccQlQLFllsck7QwJmbnfuykD6GvlwuzuqtVSiaa1UBq6lXm8A3SkNupLx8H8Vlg/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode(' 张媛妞儿')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2ICDAxmhzpKqYkicDJwu9Nbsrc9WIjuyoSuVU7tTdHfvy4vvuHS1XZbZl4ic5tkIy6sw6djzP3rryzsHArNvkP3Z/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('一曲悠扬')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiatocrkCKx8regRhP7jft2j0xcwrG4LP7bt66RyOibgNEh1FhBwick0jq4ib3aia89IF7yuF7A2SlmL0G/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('Alice')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZVK3KRiaM7YTxqXmen5hyT81D5I6HHYxLWhQBD7Dj19xSUgbRDdoUwHZtj8uTlwutt9aG01vJcy13/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('流失')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8PeTiaTgEPqazICiapGEfTibf29SaFn9yOyPHgXBicjQib57eVlcboI6II1x6TlX2KkapHzLAoib1BlMpwiaJeaE0z0TQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('帅的不明显。')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM7icqSO7ibW0UXQx6B0lSZB65HCAnLqAzd35UfqtIpAOJ0TDuNXgS0WxjibVGlr0diaH32XE9k13FHRUafXpLXn5EZhAUtibl8rJ1Xg/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('雪糕')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEian2wLibZ6vF1B7YANPQ57sNGQB8xVlwC1bTGwrYtwOeiblIz4W9rxlyGgWWL50nP1NeYyViaN6KW9fr/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('情至梦边缘')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEialPWWsghuWJrlaPlyQEYoRMe2nJibpyq1rlB7mZz20mBQqTlnbassmKRtfIEicplxia8DBN3DSheIFr/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('笑嘻嘻')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZZXcjaOVopOISVUO3Adeibcy9MeJRqw2P22hZBYhboggbI5zhI1qfl1Mj8bgOfGCIuGym9SbOOzbJ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('S     J')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP83v00zPtULIzPwiclHCibMQxt9X9iafMciaIgbAjhm56Sj0uAS05O6B35207d5UUrIY3pjGNia2Zu4AibukRWnOMdY8u/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('张峰瑞')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZSCfDMpOTdmYkVF1J3S07Tu3pLKdGGRBYj0A1icePotSpSwJXTXsssK88nhxnI6Df9BQpNYNZM8iak/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('G小卿')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAl1ZBvuicxlgSydmAskd4Qd8AfIaNbAGqMm6XibzSDg65W6prlqWm2Sy3rdI7o7pc563WA4SQ0XE7V/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('『Q7')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLDsSooiaU6wc9W5WqyElDc3D2bctbG8ibHDAdaHw6VFVlyEHdPCmIQj4NSH4UEKVaRTBQyLSANYj8Vw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('燕子手绘总代理')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiamtBzRvVY5icKUxNWkyAjZnDtpicFbhs4qN3CiaicZ3ZDAwkmwia04kuLyDcFm1ib48zlB56SiaWQicibFZibk/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('回到那年夏天')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZZ6QibbSNRkDFmxUq3gD4lCrc1B3ribgw6EibicomPyyv7yO6y29a5zXFANbzs4nuqh3k6k6iclm54hMJ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('人生梦')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZbdiaQnPDibZvIiatiaicyaq4PqTz0xXxIppazt4mvCfDqUX706v2xCYoRjk87RrOI0rwNgWedyuibfdUc/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('兎訝 ')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZYAuB5ZebXWuhIRfzKkGsvZO8qQUlKXzRIhYra7T8t17paFJrBIVo7APh9p1qbNibFvPlM7ylzYqk/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('李潇')."','	http://wx.qlogo.cn/mmopen/nEicOibIiaftA288dmBxcTia4Pd72BtYWO0Yn0f6uW2LjHBMoSKwGsQZ8zuecx0thwfwLO16wxFo6SbiceIno8VFORAG4nGtHo4tx/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('老鼠')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8EL2DvlJIhibqxp8jaKibrTVZz8ctWsNAZk0BnYjl6vT7ax8lR71G5f7icIB3AbqUyOMicNQIoh29xxWeRE65cxXeC/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('辉哥')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa3LPW4RdtGaeBROXruktgfAIp9dTBbzPnnEHPnUdgXeXvtubJuSIyqQQC7rbgKBGDeUZgb5QqlqXfA0oPoGVze5/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('杨晓红')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAnibUQqpuwIngX1f58PC8fBUd7mgicbT5Avudtp80rdIII8Z8NSNjBQzraEZ3kGB7dtkccMkuqHdsa/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('暖阳')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiagYvDBWk9eH3cnxH7sfAu8FXvQLyBszhuyIZMR4fbVmCYhM7Hz4icOKb4X7E8ibia2myKm3fawtCrOZ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An4YbychgVHSTjWEAHPibicUrRfzn9kjr51Ca4thHPhocUEaUmVGJMVvCTOaaicGqSLkMHIrJTxuRwwdw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('Alex')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ3UpD8K7JtKAHvKUXRYfa3PSamTWDBmPicfuJBfK0OPhz5hgnoggIEc8LzWyyf1zt4ag6TpJOyCWU/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('宋赫男')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZSJvzlN8HnUqIs5ZWslH23NoqGGiaw6qCL5eibib5T8bX0avibic7tA9nDjicCxPtZE82OWOk6zXBwzw0I/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('高高')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An7jnZQ7syQWayMV6NqyyXGCGo6mbO1GmvX2TxrnuqDNvP6K1o3sg52ic2khHyUhj6y6P27lYBJ8rMPHIfSsSkjDn/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('南岸清风')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiat953nH8kyvfJqFpJs33gbMv5NSPWWxxhOFuCjHwprf4lxYcNrC2IpkWEB8EYniaAWsStVQv3hx0P/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('马牧池有机小米山东临沂总代理')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLD6lFXa8YkcVqAsX5CBjyiabK6vUPyMCBRU39ic1lUC3J1wEPyQ1zFWSd1ToInUr0UWDLZKUMA6jHNg/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('七夜')."','	http://wx.qlogo.cn/mmopen/PpzMxmN2ntGmWuBhic36gia8ODibu9dYf61nbWiab38D1Vk38XP6w37fjAFSqZwRwzTQibyHibHGhzIJscibOL8zRpjLZMiaXjdzKZpw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('..')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiah9Pr919AHwSUXb4lwXMSvRmlHPALfaic6el45QBPSxte4LhOTxev2rYRz3icBF4cJ14TWDatibzGku/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('沂蒙小钓')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZaFD5yrPINiaRBnqkwfYSP3BezQt2Xib2LY3aa4kRsElBynyQianz50pCBZUEVSpXPKTeeOYiaF8Hxicic/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('冬天的雪')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZQDmPPuxfmqcZiaVBQOxjr1RoibrGLIVoFPL2cq4EEUGaJFsEtiae7ia0tHeqzMJibzUexojygRAbicAJR/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('じぴ微笑ヾ')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa02ueDkJJARx2Kb0kYcIBvcd5BrsO15PficSTsBPkkbfibbrHibInBZjg1ftHOsU7pCZcEEpGRM481kET6oA80iaUAO/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('爱拼才会赢')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwPichUADJgRYzH9Nab9ntdhXweFxw9E07k9pg5LlbTZUFicD9xm8ibNlIoOHOO7QCictU3ZlJqBJ6oQh5H89dtWMymO9/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('琅琊~~陶乐公')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiaptEn7iaAkibjYZEbGfKCC866srJECExE6Wk3qbQFXa69yoicgRBst2YbYSC08AbMTsabEtlT1AKHvm/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('鲸')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZzdK6c3ODsuQP9NKJIBibLsnibmZFVgDLWT3iaMwo0ibRPTjyicUCscwoBevRWj3hiau1X3tFUo0JE9VFic/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('阿雪')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZxmIsIgGnWcibCS4fyhTwsTGOIeocYLicLHgEiaSsFmaw3tBYicM8ZrMjEp5FLObBeDL7y40plSliaXhk/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('小鱼儿')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiav3PdzMHCobUOCjrrEksMkjzXjuibZ79WCLxa2RWpZUUTfE43uahicaYRREBYjTr2FVZs3NuWr1seG/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('平凡一生')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ0tkYnr88ib1dU8f6mRkFHRwdwngmVrka0E3ficswDxsnMaKyaTbDPIeia7WhibJukofrV9MN9t7P0Mz/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('街拥')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP9hiawoHq5YINWFG2TZbdP4bGygNK8jf9a3A5Duugks631akFWaWnDWRM5MUrAiaYichByyNy22r0L2wI3oDKicQeuP/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('燕子')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An4icmNMw63H0oTJ1hv5ogianV6BS9SKeporZTNBeZibngBL0WkINDTlfCbPYZz6bnPWSxsicek8fPvX6Dl3HcDgUpk4/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('王')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP9iaKfE2wGn7BMDjMOFSLSjgkH638dJmWpENqkNGF1eDjOcLHkBVUmRw7c3spcEZCgEz6NAo7DgHzJtqe7xhwKjl/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('露珠')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8EL2DvlJIhibspfuy4NauibL4FZlOu0mrThFY6EMcGYBgtibXPBVCkaTiaOn2kdiarSGicZbTvdBpqXLBYw386HW0NuO/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('石榴花开')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAvRvJvJoAO9wmY6IhkzbkwGrfVl0q6bXx2RuXIfSVErYe6rsgcwPzHIY4sZjmgO338l0EypJqrpU/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('九州梦妆刘磊')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZVbMOFIFxhYSZPD6Bz3PYXO81HQFzwfUC6gGRzgjonUI0A5qme2ibesfDCXPLEMtUWwZkmIvnCLj1/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('高娜')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ0RhHX2nEn8VrysQpf30SrHhe3fbpSjeQESDBTzibcDfzaRuFnib0xjmsUwnefNc1wK1XtyNcFzwUh/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('陈晓婷')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAmRK4IFSv6CBWB1TWdSC4XS8Reib6A9ickBaz6PEKgzmUk9MD8ZUFiaojGicUpdiceonHexlKW2SUTgo5/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('临沂祥林手机、电脑、小家电商贸')."','	http://wx.qlogo.cn/mmopen/PpzMxmN2ntGmWuBhic36giax1COUicn0EwIXz15LWKQsc7kVgcDAwXR34Q3mGXsHMX8ceXkYfPVaTibDfrTKdZy8AamWVY2yKo22/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('玲')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5saibotRBiaoYGHkpNWjBsAe5fva47icWuV8wPEIXnUXGZjXQ6n1mmQQKNYmNuYhfZRxe47oTu13al11bGbs53eqQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('玉莲')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM7Tm0QMkoVujmRLyxRNW0sHFWy3LrIW6kFiacBPiaKDTFuVXYKe9hia04N1voCfe3bqX3P0pL0T6sVmNKZWr2M29koLZhWQ37XJgI/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('释演云')."','	http://wx.qlogo.cn/mmopen/PpzMxmN2ntGmWuBhic36giayZoPo6sEpEiaDfflzvADzqrsicd4pCQl8esyeia9DvZG1tqEk5IBaYxmQLlf5z8XekYmM7RFU9A8ao/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('sinir')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2ICDAxmhzpKofwykIBZJkLFgKBX49ToXwkeK9WyJdib6EHqXItGItMviab4yNrfmmO87YUjdWv9ibZeoVIJen05PB/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('紫露冰茶')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiapsomEc3K9UJEZUbgwHwIzh8tBrPEVMQ0zQ2q8AiaNphG0ibfspdGM2pm5OhfBiaovscILnP5JAt9ict/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('王浩数码相机维修')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM4awdDtaDoNGqBII1fdyfgf17usmFGEh7ywwWbiazRqE2fEa6WK0SE9qEtc8ibT8LCrNcUrxzlWo3Gk8GYK242cEd8pB3KoJHXe4/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('涵')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGGibJhibibqPEYyicHRMoqWUkPcnKmqMSFsfc9WPpPqNnYWUt9uFjKic5UjAL0HkpNWsTiaA0OJIB9icLZOpg/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('@～两个小可爱“@”')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM4jl2iaia3TFFkQDYDxuwRyiaqC6ZLXLfCibEJQCaA4LfaxvLj3tgStcib97xicpfaswZGsGfjlJNFLSfngEH4Smibr7hQQ28Fz9jkTXQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('天很蓝')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa1yicmgyjVJaha9hvxy7OsdrEwrPZgsFnZdGujevrJNu5aKIACj9WEeVHb4AkFLoYb0ialzNxicePgaDeBib7HvnyN3/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('光合君')."','	http://wx.qlogo.cn/mmopen/1VsbN3N0FQibiaonUfib7JnhDjvSVlibGxW7uC5oP43FlWyYhn8NegbopW7MFdJLNr1c7AvfeVJicAasmKswJZJ7hU3by2cicTicReQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('嘉禾')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ53FAV6VqEW4t6gzWEodWicVokMZ3Kp9K7tsKndNCdPzHsZYxmT8RRicA2ibecyulzYcdvOQSGfnriaG/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('依然')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa1GSKgJX8joTauEz8K1bVJYAQ9Wk9ribcOlPcMFWudtKFcN0YK3fLpBOpoiaKEKdgmo4WGLynDGnpxRDGvmApo5MG/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('A天使爱美丽')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZQCNxgo9Le9vbPd1PDhRQuU6R3wicsicLfFfsrkjPjQL73SazCZgSHBicDrDOCmlpoQicCibcaJ82VWjK/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('向右走')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZVdealy56P5o002dQ2aM13XrkicEhVLh4LME8DFFu5NReyNfTF5NtpIlLsPs2ueLhH69188eWcNrT/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('胡胡')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiajuT129pCy3o1nv66OT7OyA6l9jrw2UXegUvg5hrEOmwTzTSj7RCJZ5NS0dXsTFhwtMDh6WDhUtr/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('★王者归来★')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An7jnZQ7syQWa8FluRIh6kTsQUal1f16PV3Q1Z6mO39TXsMXRtHgnMW98fzUI3UibYQY4mAiavmL3U0ZZdDy6wQQV9/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('张学文')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZabJgicx5LHPdHCg2wVO2iboPPVo2OIdlN2TUKDOqCdMJwyG7h29U0kaeIiclJq1y9dNgotkIERlujC/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('婧麒')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiaoIBumCDibHXnHAvlcGLEnTrJC0bMXFXsSicicbn3gJMuTI3R3NB4K30xNv3NEkUnZUlPTqMqq8G3DG/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('行者')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTArrOquibtqnyjK06WMvN35UqL4EliavibMXbo38oqPCCwhGlAKicXLygQrxOXx1u21VP8RFibOKjibib4G8/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('牛汉子?')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGGibpTzUoxsMSRMUicTxQCBXNCuf9NmJghcP7F5swCZZzhZiburUERd1drA3UHib4eW6kCZjkF2g1iaLI0g/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('刘蜗牛')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM4xzEkgak6SPuTpatvM2mMjnxaiaXW9Bh5AgdPj8ZZD1uckNBgz3AFdH6ZNBMXGQaoOmuq9zI2zy8w/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('宁静港湾')."','	http://wx.qlogo.cn/mmopen/PiajxSqBRaEJ4PViaic2IF17hegKfia9mISEmokL4ZAbib6FsVu7mwicm5DficuIYVEdhyw3T4X09mOU9zrI1drpqJSew/132', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('晴天娃娃')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa14icbaq6ITnz8cyg6Sx3DjewTjP9naftLd17o5YiaoQll5lmgZ6lDw3OjINAhNJ9LsibVf7Ct2uVJPt9rWeJJe0Qn/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('')."','	http://wx.qlogo.cn/mmopen/PiajxSqBRaEK8AWRIPQmD8w64z4MuEhaNia8ia2mLJR2nakYULTtnvzv8eSLnDTQSHwbpkBoAic4J8icsNapJMibq9cw/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('柏翔翔 绿森林硅藻泥')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLDQvrW42w8r7zZKEE12wmp0Xic2TTeicRQHdzaNR6J6fKCkr56B56fRe7BIeJIG0iaxD1N1cFsHTzHnQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('落尘')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTAg1aGpibNic9xLNWv1LmLAwkl6HKjicEPQwqlt9IeOgX6ibM7yMIjZknomx0GqdPFcj7ecIUWAQtibOwL/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('下个，路口，见')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ8ia2dCvno6Nh00BicsryWB5t7SE2GykWrBQ2Xw2Foe483oNiaczXacto7YrxSKnHMjC4SDbtogUe7g/132', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('磬灵儿')."','	http://wx.qlogo.cn/mmopen/wjVtTPhRGG9uqLvGvddTApGvMRSceenRUXic4xsspNajDRlBGUUMmL4icBOSXCqp4LKXibIZvFjXQSyccWLDN3fxucpM3eqbVKE/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('ID:小浩simida')."','	http://wx.qlogo.cn/mmopen/PiajxSqBRaEJURlIRxVtTdziaaP4B0ia7vmuK7zRCFss4kkOkrHtO4PId15GqIic16rzCicd7oibZqF7TYq4SuWPFvOg/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('颜行一致')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2ICDAxmhzpKonvTuvlriaJQ1JZKInNSUGbibjSDGuzOpClKj7TrGO795icKLq85clKKicPltt1OtboEVgf84CWVWxn/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('王俊玲')."','	http://wx.qlogo.cn/mmopen/nEicOibIiaftA37j7cazuYVSVibNRHWL0IIpcu0SOPscvCY9l1Xib0oWl5S0AIvc9hHLNSiby9hicHiaPQWzPtbLCe6UTk1ib4NEX4Hfv/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('月落花开')."','	http://wx.qlogo.cn/mmopen/PpzMxmN2ntGmWuBhic36giaicrsk46ZgDp6iahonbR1WD8V3dmPhr56r5PaqOsNYwgh8cgLFbWdbt9BJa82bEOh0jyZumCA77laJ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('席席')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLDdnPYg3hFgsfLuEDeCxQIhAsnghoIgmDia9reuC2kjeE5UvdFF4hUibxF7C7se1Hm5xMNR3c4UYGSg/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('爱上微信')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2xRmia8iaNejLibWUNrGY7UaBQSzpSnO6hZpelDS6UZFTJn0kiayDfB0sWARqJuqWJqxvBf6O6XLl0jfwPgchiaeyp9/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('小手拉大手')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZThxbI1tW37o9GCv8ZwoKzy7P92Jaicxjv8m3TFnA9x61p3eyuQ8Em0kAQRSDQChsOFn2roWnkl74/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('慧慧')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa1Rd7Lu8icvjkJGgFMicPRXVhdQbMxJVJQGzWLRHDIxx0NHibJXVbLlDPhyvdfelookuL0e9yWT07J5DgOwsTDL57c/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('仙人掌')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8EL2DvlJIhibsEicQsWzEWme39U2rbsZrcT4c0WFZhaPgj5EibJFP6JZiaSHdJDyBibaRicZm9YzlRlaCNg5aV7ImrGM/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('* 似笑菲笑*')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ0sLMkYO1ZCZUibfX6fLoAqQn5JTcRXicymr5TYhLQddfibWo1zaIHibpplKpJWQrvNHzGoTib3ZFzErK/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('瞌睡姐999')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM7Tm0QMkoVujmRLyxRNW0sHx62atqPBFFlY0N9RtSoKiaUjniaibicxl8raTgTmcxwhVyPaCCfhHbMxtAmNVxDAE6zsp1keibeGCPmQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('A娇娃孕婴用品')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8ycnNYYickB5f3RmZhYmthumoLo2bZhRFuxMOfHPcNhAbHpwvicsDKuAkldZzo1CYnEIbam7qSRVt3mBxxwNDopQ/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('客户太少怎么办？')."','	http://wx.qlogo.cn/mmopen/Q3auHgzwzM6YDxCMiblpGibcsyJGROvU92AAYf8HnuAp0icJetoiaayRwiaHDoL34iaiazgcpa7rNegdkfSZvSYNIhUkggBRzWtcftkgaCmwzuribxA/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('彼岸花')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiamcenyQ6l6dP4ib3rend7GSofazxVb1Y4Y4DNvAmeRnWexQn5yUdfIrrPf3BRcB1iarrxfibuTMzz1G/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('王慧')."','	http://wx.qlogo.cn/mmopen/iap3NuicaIwP8e5hqZOoFxZ0c5P7zgVhTUIxouSlStyMuibOmxxxDK81rYLCD30tWRz53ubhfjbIf57uZzbP1tIw4cicEVG5jjQD/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('号')."','	http://wx.qlogo.cn/mmopen/PiajxSqBRaEI4libn3buIxEzczPhIWU9r2oT6NLn1bfMSQ16Wic4ibzdEfXESUVstX78d2aqEUDvReIBngkH7YIcCw/132', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('晨风')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2sGyX5taPEiagJDxBqzSAhMMRcNCFico0QMGVcQ4zuDeIIjGz0YRkAqlkxK8KPCrfveic3uicecXicQ6OKfvZueOvicF/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('越来越好')."','	http://wx.qlogo.cn/mmopen/eSyA3wG0An5ebpibdwo6LZbZ4brL2A4oQHNCEcgMImpeAluOVe4hwfSLicCicHcgDnhhddaoELjoooJQry5qm5ibj6S1WhHVZIyx/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('陈刘婷')."','	http://wx.qlogo.cn/mmopen/ajNVdqHZLLCmdNPRN1taxmBvJXe8TZq2Zkq5qnGna6ETA2fuDl2bFYuFDD64nS4gAiaJNNZXHjkrVEw48ibK0c2g/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('润竹')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa0SiaHgO1v4dMRlr4I6FqAB0AtWAvoutW8eR3eEIXiaIClvgKW72zeUuH7B4ynodtGv388IwvCYYhlcYB6StHBibYS/0', '1', NULL, NULL, '0', NULL, '0', '0', '0')
    ,(".$this->weid.", NULL, '".urlencode('紫藤')."','	http://wx.qlogo.cn/mmopen/YpN9DkG8iaa2ICDAxmhzpKj2X3sI7uBsHJ3xQhlJWupRicBJhX9orM2oiaRJ8zQ8JtSMgFGd53UoMToqWZpLUkJlPydVQibdtl73/0', '1', NULL, NULL, '0', NULL, '0', '0', '0');
    ";
     $res = pdo_query($sql);
     if($res){
        message('增加成功',referer(),'success');
     }else{
     }
     message('增加失败',referer(),'error');

  //  echo $sql;
}


  if($display=='list'){
    if($dopost=='search'){
           $_continer = " and a.id  =".$_GPC['id'];
   }
      $pindex = max(1, intval($_GPC['page']));
      $psize = 20;
      $list = pdo_fetchall("SELECT a.* FROM ".tablename(GARCIA_PREFIX."member")." a where a.weid=".$this->weid."  and (type=0 or type=2) and is_roob=0 and a.status=0 ".$_continer." order by subscribe desc LIMIT ".($pindex - 1) * $psize.','.$psize);
      $total = pdo_fetchcolumn('SELECT COUNT(a.id) FROM ' . tablename(GARCIA_PREFIX."member") . " a WHERE a.weid = '".$this->weid."' and a.status=0 and a.openid!='' ".$_continer);
      $pager = pagination($total, $pindex, $psize);
  }
  else if($display=='group'){

        if($dopost=='save_zuzhi'){
         $id =$_GPC['id'];

            $data = array(
              'nickname'=>urlencode($_GPC['nickname']),
              'headimgurl'=>$_GPC['avatar'],
            );
            pdo_update(GARCIA_PREFIX."member",$data,array('id'=>$id));
                 message('操作成功',$this->createWebUrl('member',array('display'=>'group')),'success');
            exit;
        }
      if($_GPC['id']){
          $fabu = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$_GPC['id']);
      }
           $list = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and type=9");


  }
  else if($display=='views'){

     $mid = $_GPC['mid'];
     $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX.'member')." where weid=".$this->weid." and id=".$mid;
     $member = pdo_fetch($sql);

     $member_name = urldecode($member['nickname']);
     $wallet = $member['wallet'];
     $pindex = max(1, intval($_GPC['page']));
     $psize = 20;
     $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and mid=".$member['id']." and mid=".$mid." and status>0 order by status asc LIMIT ".($pindex - 1) * $psize.','.$psize;
     $total = pdo_fetchcolumn( "SELECT COUNT(id) FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and mid=".$member['id']." and mid=".$mid." and status>0");
     $pager = pagination($total, $pindex, $psize);
     $list = pdo_fetchall($sql);
      //处理图片
      foreach ($list as $key => $value) {
           $_v = 0;
           foreach ($value as $k => $v) {
               if($k=='thumb'){
                  $_temp = json_decode($v,true);

                   if ($_temp[0]) {
                     $list[$key]['cover_thumb'] =_getphoto($_temp[0]);
                   }else{
                     $list[$key]['cover_thumb'] = './resource/images/nopic.jpg';
                   }
               }else if($k=='rand_day'){

                   if($this->diffDate(date('Y-m-d',time()),$value['rand_day'])<=0){
                      $_v  = 1;
                      $_vk = $key;
                       $list[$key]['rand_day'] = '已过期';
                   }else{
                     $list[$key]['rand_day'] = "剩余".$this->diffDate(date('Y-m-d',time()),$value['rand_day'])."天";
                   }
               }
           }
             $_thumb[]= $list[$key];
           if($_v==0){

           }else{
              //过期
               pdo_update(GARCIA_PREFIX."fabu",array('status'=>3),array('id'=>$value['id']));
           }

      }
      $list = '';
      $list = $_thumb;
  }else if($display=='shiming'){
      $id = $_GPC['id'];
      $status=pdo_fetchcolumn('SELECT status FROM '.tablename(GARCIA_PREFIX."shiming")." where weid=".$this->weid." and id=".$id);
      $img = pdo_fetchcolumn('SELECT b.pic FROM '.tablename(GARCIA_PREFIX."shiming")." a left join ".tablename(GARCIA_PREFIX."photo")." b on a.thumb=b.id where a.weid = ".$this->weid." and a.id=".$id);
  }else if($display=='vies'){
       $pindex = max(1, intval($_GPC['page']));
       $psize = 20;
       if($dopost=='search'){
              $_continer = " and id  =".$_GPC['id'];
      }
       $count = pdo_fetchcolumn('SELECT COUNT(id) FROM '.tablename(GARCIA_PREFIX.'member')." where weid=".$this->weid." and is_roob=1 ".$_continer);
       $list = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX.'member')." where weid=".$this->weid." and is_roob=1 ". $_continer." LIMIT ".($pindex - 1) * $psize.','.$psize);
       $pager = pagination($count, $pindex, $psize);
      //  var_dump($list);
      // echo $sql;
  }



  include $this->template('admin/member/'.$display);




  function _sup($mid,$weid){
     $sql = "SELECT id,fid from ".tablename(GARCIA_PREFIX."paylog")." where weid=".$weid." AND mid=".$mid." and status=1 group by fid";
    $_sup = pdo_fetchall($sql);
    return $_sup = count($_sup);
  }


function _fcount($weid,$mid){
  $sql = "SELECT count(id) FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$weid." and mid='".$mid."' and status>0";
  return $_fcount =pdo_fetchcolumn($sql);
}

  function _shiming($mid){
       global $_W;
         $sql = "SELECT id,status FROM ".tablename(GARCIA_PREFIX."shiming")." where weid=".$_W['uniacid']." and mid='".$mid."'";
        $_c = pdo_fetch($sql);
        if(empty($_c['id'])){
           return "<a href='javascript:void(0)' class='btn btn-xs btn-danger'>未认证</a>";
        }else if($_c['status']==0){
          return "<a href='./index.php?c=site&a=entry&display=shiming&id=".$_c['id']."&do=member&m=jy_qingsongchou' class='btn btn-xs btn-warning'>待审核</a>";
        }else{
          return "<a href='./index.php?c=site&a=entry&display=shiming&id=".$_c['id']."&do=member&m=jy_qingsongchou'  class='btn btn-xs btn-success'>已审核</a>";
        }
  }

  function _getphoto($pid){
    global $_W;
     return pdo_fetchcolumn('SELECT thumb FROM '.tablename(GARCIA_PREFIX."photo")." where weid=".$_W['uniacid']." and id=".$pid);
  }
 ?>
