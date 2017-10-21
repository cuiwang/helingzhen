<?php

  $this->_wapi();
  $display  = empty($_GPC['display'])?'index':$_GPC['display'];
  $dopost = $_GPC['dopost'];
  $id =  $_GPC['id'];
  if($dopost=='save'){
    $id = $_GPC['id'];
    $ms_title  = $_GPC['ms_title'];
    $ms_content  = $_GPC['ms_content'];
    $thumb = $_GPC['thumb'];

    $data = array(
        'ms_title'=>$ms_title,
        'ms_content'=>$ms_content,
        'thumb'=>$thumb
    );
    if(empty($id)){
      $data['upbdate'] = time();
      $data['weid'] = $this->weid;
      pdo_insert(GARCIA_PREFIX."sysmsg",$data);
    }else{
      pdo_update(GARCIA_PREFIX."sysmsg",$data,array('id'=>$id));
    }

    message('保存成功',$this->createWebUrl('sysmessage'),'success');

    exit;
  }else if($dopost=='del'){

    pdo_delete(GARCIA_PREFIX."sysmsg",array('id'=>$id));
    message('删除成功',referer(),'success');
    exit;
  }else if($dopost=='edit'){
     $msg = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."sysmsg")." where weid=".$this->weid." and id=".$id);
  }else if($dopost=='push'){
    if($_GPC['action']!='go'){
      message('正在准备推送',$this->createWebUrl('sysmessage',array('dopost'=>'push','action'=>'go','id'=>$id)),'success');
    }

    $acc_json = $this->jsonfile;
    $token = $this->wapi->getAccessToken($acc_json);
    $msg = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."sysmsg")." where weid=".$this->weid." and id=".$id);
    $ms_content = cutstr_html(htmlspecialchars_decode($msg['ms_content']), 30);

    // var_dump();
    /**
     * 获取所有用户
     */
     $pindex = max(1, intval($_GPC['page']));
       $psize =50; //每次50分次推送
       $_next = $pindex+1;
     $member = pdo_fetchall('SELECT distinct openid FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and openid!='' order by id desc limit ".($pindex - 1) * $psize.','.$psize);
     $_cur  =($pindex - 1) * $psize;
     $_cur =  $_cur+count($member);
     if(count($member)<=0){
       pdo_update(GARCIA_PREFIX."sysmsg",array('status'=>1),array('id'=>$id));
       message('推送成功',$this->createWebUrl('sysmessage'),'success');
     }else{
       $_url = $_W['siteroot']."app/".substr($this->createMobileUrl('message',array('id'=>$id,'display'=>'detail')),2);
        $data = array(
           'title'=>urlencode($msg['ms_title']),
           'description'=>urlencode(mb_substr(strip_tags(htmlspecialchars_decode($msg['ms_content'])),0,30,'utf8')),
           'url'=>$_url,
           'picurl'=>tomedia($msg['thumb'])
        );

       foreach ($member as $key => $value) {
          $a = $this->wapi->sendNews($value['openid'],$data,$token);

       }

      //  if($this->sys['kf_news_type']==0){
       //
      //      $message = str_replace($_d,$_m,$this->sys['kf_news']);
      //      foreach ($member as $key => $value) {
      //           $a = $this->wapi->sendText($value['openid'],urlencode($message),$token);
      //      }
       //
      //  }else{
      //      $temp = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX."temp")." where weid=".$this->weid." and id=".$this->sys['kf_news_tmp']);
      //       $temp_id = $temp['tempid'];
      //       $_url = $_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('id'=>$fid)),2);
      //       $url =  str_replace("{url}",$_url,$temp['url']);
      //      $parama = json_decode($temp['parama'],true);
       //
      //      foreach ($parama as $key => $value) {
      //         foreach ($value as $k => $v) {
      //              if($k=='value'){
      //                   $parama[$key][$k] = str_replace($_d,$_m,$v);
      //              }else if($k=='color'){
      //                  if(empty($v)){
      //                     $parama[$key][$k] = '#333333';
      //                  }
      //              }
      //         }
      //      }
      //      foreach ($member as $key => $value) {
      //            $a = $this->wapi->sendTemplate($value['openid'],$temp_id,$url,$token,$parama);
      //      }
      //        //
      //  }
      //  echo 1;
      // echo $_next;
      message('已推送'.$_cur.'人，请不要关闭浏览器，正在下次推送',$this->createWebUrl('sysmessage',array('dopost'=>'push','action'=>'go','page'=>$_next,'id'=>$id)),'success');
     }

        exit;


  }


  if($display=='index'){
    $list = pdo_fetchall("SELECT * FROM ".tablename(GARCIA_PREFIX."sysmsg")." where weid=".$this->weid);
    foreach ( $list as $key => $value) {
         foreach ($value as $k => $v) {

           if($k=='ms_content'){
             $list[$key]['ms_content'] = preg_replace('/<[^>]+>/', '', htmlspecialchars_decode($v));
         }
    }
  }
  }
  include $this->template('admin/message/'.$display);

  function cutstr_html($string, $sublen){

  $string = strip_tags($string);

  $string = preg_replace ('/\n/is', '', $string);

  $string = preg_replace ('/ |　/is', '', $string);

  $string = preg_replace ('/&nbsp;/is', '', $string);

  preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $t_string);

  if(count($t_string[0]) - 0 > $sublen) $string = join('', array_slice($t_string[0], 0, $sublen))."…";

  else $string = join('', array_slice($t_string[0], 0, $sublen));

  return $string;

 }
 ?>
