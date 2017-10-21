<?php
// $list = $memcache_obj->get('message'.$this->weid);
// $memcache_obj->set('message'.$this->weid,$list,0,3600*24*30);

    //  $mkey = 'message_'.$action"_".$this->weid;
   if($action=='getup'){

      $sql = "SELECT a.id,a.type,a.content,a.upbdate,b.headimgurl as avatar,a.status,b.nickname,c.id as fid,c.mid,a.thumb,c.name,c.project_texdesc as content FROM ".tablename(GARCIA_PREFIX."update") . " a
      LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid= b.id
      LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." c on a.fid= c.id
      WHERE a.weid=".$this->weid." and a.type=0 and c.id !='' order by id desc limit 0,10";
       $list = pdo_fetchall($sql);
       foreach ($list as $key => $value) {
          foreach ($value as $k => $v) {
               if($k=='nickname'){
                 $list[$key][$k]  = urldecode($v);
               }else if($k=='upbdate'){
                 $list[$key][$k]  = $this->_format_date($v);
               }else if($k=='avatar'){
                 if(empty($v)){
                    $list[$key][$k] = tomedia($this->sys['web_logo']);
                 }
               }
               else if($k=='thumb'){
                    $list[$key][$k] = json_decode($v,true)[0];
               }
          }
          $list[$key]['content'] = mb_substr(strip_tags(htmlspecialchars_decode($list[$key]['content'])),0,60,'utf8');
          $list[$key]['content'] = empty($list[$key]['content'])?$list[$key]['nam']:$list[$key]['content'];
          $list[$key]['fabu'] = array(
             'avatar'=>$list[$key]['avatar'],
             'name'=>$list[$key]['name'],
             'desc'=>  $list[$key]['content'],
             'fid'=>$list[$key]['fid'],
             'mid'=>$list[$key]['mid'],
          );
          unset($list[$key]['content']);
          unset($list[$key]['name']);
          unset($list[$key]['fid']);
          unset($list[$key]['status']);
          unset($list[$key]['mid']);
       }
       if(!empty($this->sys['dongtai_notice'])){
          $data['is_notic'] = 1;
          $data['notices'] = array(
             'avatar'=>tomedia($this->sys['logo']),
             'nickname'=>$this->sys['sitename'],
             'upbdate'=>'刚刚',
             'content'=>$this->sys['dongtai_notice']
          );
       }
           _success(array('res'=>$list,'not'=>$data));
   }else if($action=='addchat'){
       //记录聊天记录
       if(empty($_GPC['send_id'])){
           _fail(array('msg'=>'发送方ID 不能为空'));
       }
       if(empty($_GPC['revice_id'])){
           _fail(array('msg'=>'接收方ID 不能为空'));
       }
       $data = array(
         'weid'=>$this->weid,
         'type'=>$_GPC['type'],
         'content'=>$_GPC['content'],
         'upbdate'=>time(),
         'status'=>$_GPC['status'],
         'send_id'=>$_GPC['send_id'],
         'revice_id'=>$_GPC['revice_id'],
         'is_read'=>0,
         'target_code'=>$_GPC['target_code']
       );
       $r = pdo_insert(GARCIA_PREFIX."chat",$data);
       if($r){
            _success(array('msg'=>'记录成功'));
       }else{
          _fail(array('msg'=>'记录失败'));
       }
   }
   else if($action=='isread'){
     if(empty($_GPC['revice_id'])){
         _fail(array('msg'=>'接收方ID 不能为空'));
     }
     $r = pdo_update(GARCIA_PREFIX."chat",array('is_read'=>1),array('revice_id'=>$_GPC['revice_id']));
     if($r){
        _success(array('msg'=>'已读'));
     }else{
        _fail(array('msg'=>'操作失败'));
     }
   }
   else if($action=='chatrcode'){
       //聊天记录
       if(empty($_GPC['target_code'])){
           _fail(array('msg'=>'target_code不能为空'));
       }
       if(empty($_GPC['mid'])){
           _fail(array('msg'=>'mid不能为空'));
       }
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $sql = "SELECT id,type,content,send_id,revice_id FROM ".tablename(GARCIA_PREFIX."chat")." where weid=".$this->weid." and target_code='".$_GPC['target_code']."' order by id desc  LIMIT ".($pindex - 1) * $psize.','.$psize;
        $list = pdo_fetchall($sql);
        foreach ($list as $key => $value) {
        $list[$key]['is_send'] = 0;
        $list[$key]['is_revice'] = 0;
        $avatar = '';
           foreach ($value as $k => $v) {

              if($k=='type'){
                 if($v==2){
                    $list[$key]['content'] = pdo_fetchcolumn('SELECT pic FROM '.tablename(GARCIA_PREFIX.'photo')." where weid=".$this->weid." and id=".$list[$key]['content']);
                 }
              }else if($k=='send_id'){
                     $list[$key][$k] = str_replace('_'.$this->weid,'',$v);
                     if($list[$key][$k]==$_GPC['mid']){
                       $list[$key]['is_send'] = 1;
                       $avatar = pdo_fetchcolumn('SELECT headimgurl FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$_GPC['mid']);
                       $nickname = pdo_fetchcolumn('SELECT nickname FROM '.tablename(GARCIA_PREFIX.'member')." where weid=".$this->weid." and id=".$_GPC['mid']);
                       if(!$nickname){
                           $nickname = pdo_fetchcolumn("SELECT mobile FROM ".tablename(GARCIA_PREFIX.'member')." where weid=".$this->weid." and id=".$_GPC['mid']);
                           $nickname = substr($nickname,0,3)."****".substr($nickname,7);
                       }
                    }else{

                         $list[$key]['is_revice'] = 1;
                         $avatar = pdo_fetchcolumn('SELECT headimgurl FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".str_replace('_'.$this->weid,'',$v));
                          $nickname = pdo_fetchcolumn('SELECT nickname FROM '.tablename(GARCIA_PREFIX.'member')." where weid=".$this->weid." and id=".str_replace('_'.$this->weid,'',$v));

                         if(!$nickname){
                             $nickname = pdo_fetchcolumn("SELECT mobile FROM ".tablename(GARCIA_PREFIX.'member')." where weid=".$this->weid." and id=".str_replace('_'.$this->weid,'',$v));
                             $nickname = substr($nickname,0,3)."****".substr($nickname,7);
                         }

                    }
                }
              }

           if(empty($avatar)){
             $avatar = tomedia($this->sys['user_img']);
           }

           $list[$key]['avatar'] = $avatar;
           $list[$key]['nickname'] = urldecode($nickname);
           unset($list[$key]['send_id']);
           unset($list[$key]['revice_id']);
        }
        pdo_update(GARCIA_PREFIX."chat",array('is_read'=>1),array('target_code'=>$_GPC['target_code']));
        sort($list);
       _success(array('res'=>$list));
   }

   else if($action=='noread'){
     if(empty($_GPC['mid'])){
         _fail(array('msg'=>'mid不能为空'));
     }
      $sql = "SELECT count(id) FROM ".tablename(GARCIA_PREFIX."chat")." where weid=".$this->weid." and revice_id='".$_GPC['mid']."_".$this->weid."' and is_read=0";
      $c = pdo_fetchcolumn($sql);
      _success(array('res'=>$c));
   }
   else if($action=='messagelist'){
     if(empty($_GPC['mid'])){
         _fail(array('msg'=>'mid不能为空'));
     }
     $id =$_GPC['mid']."_".$this->weid;
     $sql = "SELECT `target_code`,`revice_id` ,`send_id` FROM ".tablename(GARCIA_PREFIX."chat")." where weid=".$this->weid." and (send_id='".$id."' or revice_id='".$id."') group by target_code";
     $list = pdo_fetchall($sql);
     foreach ($list as $key => $value) {
       $mid = '';
         foreach ($value as $k => $v) {
               if($k=='send_id'){
                   if($v!=$id){
                      $mid = $v;
                   }
               }else if($k=='revice_id'){
                 if($v!=$id){
                    $mid = $v;
                 }
               }
         }
         $mid = str_replace('_'.$this->weid,'',$mid);
         $list[$key]['mid']=$mid;
         $list[$key]['target_id']=$mid."_".$this->weid;
         $list[$key]['avatar'] = pdo_fetchcolumn('SELECT headimgurl FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$mid);
         $list[$key]['avatar'] = empty($list[$key]['avatar'])?tomedia($this->sys['user_img']):$list[$key]['avatar'];
         $list[$key]['nickname'] = pdo_fetchcolumn('SELECT nickname FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$mid);
         if(!empty($list[$key]['nickname'])){
            $list[$key]['nickname'] = urldecode($list[$key]['nickname']);
         }else{
            $mobile =  pdo_fetchcolumn('SELECT mobile FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$mid);
            $list[$key]['nickname']  =substr($mobile,0,4)."****".substr($mobile,8);
         }
         $list[$key]['type'] =  pdo_fetchcolumn('SELECT type FROM '.tablename(GARCIA_PREFIX."chat")." where weid=".$this->weid." and target_code='".$list[$key]['target_code']."' order by id desc limit 1");
         $list[$key]['content'] =  pdo_fetchcolumn('SELECT content FROM '.tablename(GARCIA_PREFIX."chat")." where weid=".$this->weid." and target_code='".$list[$key]['target_code']."' order by id desc limit 1");
         $list[$key]['upbdate'] =  pdo_fetchcolumn('SELECT upbdate FROM '.tablename(GARCIA_PREFIX."chat")." where weid=".$this->weid." and target_code='".$list[$key]['target_code']."' order by id desc limit 1");
         $list[$key]['noread'] =  pdo_fetchcolumn('SELECT count(is_read) FROM '.tablename(GARCIA_PREFIX."chat")." where weid=".$this->weid." and target_code='".$list[$key]['target_code']."' ");
         unset($list[$key]['revice_id']);
         unset($list[$key]['send_id']);
     }
        _success(array('res'=>$list));
   }else if($action=='myfriends'){
     if(empty($_GPC['mid'])){
         _fail(array('msg'=>'mid不能为空'));
     }
     $id =$_GPC['mid']."_".$this->weid;
     $sql = "SELECT id FROM ".tablename(GARCIA_PREFIX."chat")." where weid=".$this->weid." and (send_id='".$id."' or revice_id='".$id."') group by target_code";
     $_c  = pdo_fetchall($sql);
     _success(array('res'=>count($_c)));
   }else if($action=='friendlist'){
       if(empty($_GPC['mid'])){
           _fail(array('msg'=>'mid不能为空'));
       }
       $id =$_GPC['mid']."_".$this->weid;
       $sql = "SELECT `target_code`,`revice_id` ,`send_id` FROM ".tablename(GARCIA_PREFIX."chat")." where weid=".$this->weid." and (send_id='".$id."' or revice_id='".$id."') group by target_code";
       $list = pdo_fetchall($sql);
       foreach ($list as $key => $value) {
         $mid = '';
           foreach ($value as $k => $v) {
                 if($k=='send_id'){
                     if($v!=$id){
                        $mid = $v;
                     }
                 }else if($k=='revice_id'){
                   if($v!=$id){
                      $mid = $v;
                   }
                 }
           }
           $mid = str_replace('_'.$this->weid,'',$mid);
           $friends[$key]['target_id']=$mid."_".$this->weid;
           $friends[$key]['target_code'] =   md5(($_GPC['mid']+$mid).$this->weid);
           $friends[$key]['avatar'] = pdo_fetchcolumn('SELECT headimgurl FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$mid);
           $friends[$key]['avatar'] = empty($list[$key]['avatar'])?tomedia($this->sys['user_img']):$list[$key]['avatar'];
           $friends[$key]['nickname'] = pdo_fetchcolumn('SELECT nickname FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$mid);
           if(!empty($friends[$key]['nickname'])){
              $friends[$key]['nickname'] = urldecode($list[$key]['nickname']);
           }else{
              $mobile =  pdo_fetchcolumn('SELECT mobile FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$mid);
              $friends[$key]['nickname']  =substr($mobile,0,4)."****".substr($mobile,8);
           }
        }
             _success(array('res'=>$friends));
   }
   else if($action=='help'){
       $_flist2 = pdo_fetchall('SELECT id,title FROM '.tablename(GARCIA_PREFIX.'oques')." where weid=".$this->weid." and level=1 and type=1 order by  rank asc ");
       _success(array('res'=>$_flist2));
   }
   else if($action=='helpson'){
         $pid  =  $_GPC['id'];
         $list = pdo_fetchall('SELECT id,title FROM '.tablename(GARCIA_PREFIX."oques")." where weid=".$this->weid." AND pre_id=".$pid." order by rank asc");
        _success(array('res'=>$list));
   }
   else if($action=='helpcontent'){
     $id = $_GPC['id'];
     $details = pdo_fetch('SELECT title,content FROM '.tablename(GARCIA_PREFIX."oques")." where weid=".$this->weid." and id=".$id);
     $details['content'] = htmlspecialchars_decode($details['content']);
     _success(array('res'=>$details));
   }

   else if($action=='save_liuyan'){
     $data = array(
         'weid'=> $this->weid,
         'tel'=> $_GPC['tel'],
         'email'=> $_GPC['email'],
         'content'=> $_GPC['content'],
         'upbdate'=> time(),
         'mid'=>$_GPC['mid']
     );
     if(empty($_GPC['tel'])){
            _fail(array('msg'=>'电话不能为空'));
     }else if(empty($_GPC['email'])){
         _fail(array('msg'=>'邮箱不能'));
     }else if(empty($_GPC['content'])){
         _fail(array('msg'=>'留言内容不能为空'));
     }else{
       pdo_insert(GARCIA_PREFIX."liuyan",$data);
       _success(array('msg'=>'留言成功'));
     }

   }
   else if($action=='save_liuyan2'){
     if(empty($_GPC['mid'])){
        _fail(array('msg'=>'请先登陆'));
     }
     $data = array(
         'weid'=> $this->weid,
         'tel'=> $_GPC['tel'],
         'content'=> $_GPC['content'],
         'upbdate'=> time(),
         'mid'=>$_GPC['mid']
     );
    if(empty($_GPC['content'])){
         _fail(array('msg'=>'留言内容不能为空'));
     }else{
       pdo_insert(GARCIA_PREFIX."liuyan",$data);
       _success(array('msg'=>'留言成功'));
     }

   }else if($action=='add_message'){

     /**
      * PC端评论
      */
      $data = array(
        'wxid'=>empty($_GPC['img'])?'':$_GPC['img'],
        'content'=>$_GPC['content'],
        'fid'=>$_GPC['fid'],
        'weid'=>$this->weid,
        'mid'=>$_GPC['mid'],
        'upbdate'=>time(),

      );

      pdo_insert(GARCIA_PREFIX."message",$data);
      $iid = pdo_insertid();
      if($_GPC['img']!=0){
        $p = pdo_fetch('SELECT pic,thumb FROM '.tablename(GARCIA_PREFIX."photo")." where weid= ".$this->weid." and id=".$_GPC['img']);
        $data['pic'] =$p['pic'];
        $data['thumb'] =$p['thumb'];
      }
      $data['avatar'] = pdo_fetchcolumn('SELECT headimgurl FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id =".$_GPC['mid']);
      $data['avatar'] = tomedia($data['avatar']);
      $data['nickname'] = pdo_fetchcolumn('SELECT nickname FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id =".$_GPC['mid']);
      $data['nickname'] = urldecode($data['nickname']);
      pdo_update(GARCIA_PREFIX."message",array('payid'=>'999'.$iid),array('id'=>$iid));
       _success(array('res'=>$data));

   }
   else{
       _fail(array('msg'=>'not found function'));
   }


 ?>
