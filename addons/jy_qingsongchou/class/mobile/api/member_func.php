<?php
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
    if($action=='base_info'){
      if(empty($_GPC['uid'])){
        _fail(array('msg'=>'用户ID不能为空'));
      }
      // $sql = ;
      $member = pdo_fetch("SELECT id as uid,nickname,headimgurl as avatar,mobile,openid,type,wallet,r_token FROM ".tablename(GARCIA_PREFIX."member")." WHERE weid=".$this->weid." AND id=".$_GPC['uid']);
      if(!$member){
          _fail(array('msg'=>'没有此用户'));
      }
      if(!empty($member['nickname'])){
         $member['nickname'] = urldecode($member['nickname']);
      }else{
         $member['nickname']  =substr($member['mobile'],0,4)."****".substr($member['mobile'],8);
      }
      if(empty($member['avatar'])){
         $member['avatar'] = tomedia($this->sys['user_img']);
      }
      if(empty($member['r_token'])){
        include GARCIA_PATH.'class/serverAPI.php';
          $ry = new ServerAPI('pgyu6atqypm5u','3BjKOZSjHiBl');
          $avatar = empty($member['avatar'])?$this->sys['user_img']:$member['avatar'];
           $token = $ry->getToken($member['uid']."_".$this->weid, $member['uid']."_".$this->weid, tomedia($avatar));
           $token = json_decode($token,true);
           $member['r_token'] = $token['token'];
         pdo_update(GARCIA_PREFIX."member",array('r_token'=>$member['r_token']),array('id'=>$member['uid']));
      }
      $sql = "SELECT count(id) FROM ".tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and mid='".$_GPC['uid']."' and status>0";
      $_fcount =pdo_fetchcolumn($sql); //发布项目总数
      $member['fcount'] = $_fcount;

      $sql = "SELECT count(id) from ".tablename(GARCIA_PREFIX."paylog")." where weid=".$this->weid." AND mid=".$_GPC['uid']." and status=1 group by fid";
      $_sup = pdo_fetchcolumn($sql);
      $member['sup'] = max(0,$_sup);
      if(!$_sup){
         $_sup =0;
      }
      $member['sup'] = $_sup;

      $_memner = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$_GPC['uid']);
      $is_shouc = $_memner['is_shouc'];
      $is_shouc = explode(',',$is_shouc);
      if(empty($is_shouc[0])){
        $is_shouc = 0;
      }
      else{
        $is_shouc = count($is_shouc);
      }
      $member['collect'] = $is_shouc;
      $member['target_id'] = $member['uid']."_".$this->weid;
      $member['kftarhet_id'] = 'yidajia_'.$this->weid.'_kf';
      $member['kftarget_code'] = md5($member['uid'].'yidajia_'.$this->weid.'_kf'.$this->weid);


      _success(array('result'=>$member));
    }else if($action=='kdlist'){
        if(empty($_GPC['uid'])){
          _fail(array('msg'=>'用户ID不能'));
        }

        if($_GPC['status']!=0&&empty($_GPC['status'])){
            $status = 2;
        }else{
           $status = $_GPC['status'];
        }
        if($status==2){
            $_s = '';
        }else{
           $_s = ' and a.status='.$status;
        }

        // $status =
        $list = pdo_fetchall('SELECT a.id,a.kuaidi,a.reid,b.reward,a.status FROM '.tablename(GARCIA_PREFIX."fahuo")." a
        LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")."  b on a.fid = b.id
        where a.weid=".$this->weid." and a.mid=".$_GPC['uid'].$_s);
        foreach ($list as $key => $value) {
            foreach($value as $k => $v){
                if($k=='reward'){
                  $list[$key][$k] = _reward($list[$key]['reid'],$v);
                }
                $list[$key]['thumb'] = $images[$mappings[$list[$key]['kuaidi']]];

           }
        }
              _success(array('res'=>$list));
    }else if($action=='kdinfo'){
      if(empty($_GPC['id'])){
        _fail(array('msg'=>'id不能空'));
      }
      $id = $_GPC['id'];
      $kuaidi = pdo_fetch('SELECT a.*,b.reward FROM '.tablename(GARCIA_PREFIX."fahuo")." a
      LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")."  b on a.fid = b.id
      where a.weid=".$this->weid." and a.id=".$_GPC['id']);
      $code = $mappings[$kuaidi['kuaidi']];
      $rand = rand();
      $url = "http://wap.kuaidi100.com/wap_result.jsp?rand={$rand}&id={$code}&fromWeb=null&&postid=".$kuaidi['kuai_order'];
      load()->func('communication');
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
         _success(array('res'=>$msg,'type'=>0));
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
             _success(array('res'=>$msg,'type'=>1));
      	}

     }


    }
    else if($action=='save_avatar'){
          if(empty($_GPC['mid'])){
            _fail(array('msg'=>'用户id不能空'));
          }
          if(empty($_GPC['headimgurl'])){
            _fail(array('msg'=>'头像地址不能为空'));
          }
        pdo_update(GARCIA_PREFIX."member",array('headimgurl'=>$_GPC['headimgurl']),array('id'=>$_GPC['mid']));
         _success(array('msg'=>'头像上传成功'));
    }
    else if($action=='save_nickname'){
          if(empty($_GPC['mid'])){
            _fail(array('msg'=>'用户id不能空'));
          }
          if(empty($_GPC['nickname'])){
            _fail(array('msg'=>'用户昵称不能为空'));
          }
        pdo_update(GARCIA_PREFIX."member",array('nickname'=>urlencode($_GPC['nickname'])),array('id'=>$_GPC['mid']));
        _success(array('msg'=>'昵称 上传成功'));
    }
    else{
        _fail(array('msg'=>'not found function'));
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
