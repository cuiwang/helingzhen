<?php

            //  _success(array('res'=>$action));
         if($action=='getp'){

              $sql = " SELECT a.id,a.project_name as name,a.project_logo as logo,count(b.id) as c FROM ".tablename(GARCIA_PREFIX."project")." a
              LEFT JOIN ".tablename(GARCIA_PREFIX."project")." b on a.id= b.`pre_id`
              where a.weid=".$this->weid." and a.`pre_id` =0 and a.is_show=1  GROUP BY id";

             $list = pdo_fetchall($sql);

             foreach ($list as $key => $value) {
                    foreach ($value as $k => $v) {
                         if($k=='logo'){
                           if(empty($v)){
                              $list[$key][$k] = tomedia($this->sys['logo']);
                           }else{
                             $list[$key][$k] = tomedia($v);
                           }
                         }else if($k=='c'){
                             if($v>0){
                               $list[$key]['type'] = 1;
                             }else{
                               $list[$key]['type'] = 0;
                             }
                            unset($list[$key][$k]);
                         }
                    }
             }

             _success(array('res'=>$list,'project_header'=>$this->sys['project_header']));
         }
         else if($action=='son'){
           //下级子栏目
           if(empty($_GPC['id'])){
              _fail(array('msg'=>'请输入id'));
           }
            $sql = "SELECT id,project_name as name,project_logo as logo FROM ".tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and pre_id=".$_GPC['id'];
            $list = pdo_fetchall($sql);

            foreach ($list as $key => $value) {
                   foreach ($value as $k => $v) {
                        if($k=='logo'){
                            $list[$key][$k] = tomedia($v);
                        }else if($k=='c'){
                            if($v>0){
                              $list[$key]['type'] = 1;
                            }else{
                              $list[$key]['type'] = 0;
                            }
                           unset($list[$key][$k]);
                        }
                   }
            }

            _success(array('res'=>$list));
         }
         else if($action=='pdetail'){
             //项目细节内容
             if(empty($_GPC['id'])){
                _fail(array('msg'=>'请输入id'));
             }
            $sql = "SELECT id,project_min as minday,project_max as maxday,title_placeholder as placeholder ,project_plus1 as is_address,
                   project_plus2 as is_secret,project_plus3 as is_list,project_plus4 as is_reward ,project_shuoming as detail,
                   project_mstips as tips,project_texdesc as textdesc FROM ".tablename(GARCIA_PREFIX."project")."
                   where weid=".$this->weid." and id=".$_GPC['id'];
            $res = pdo_fetch($sql);
            $res['timestamp'] = time();
            $res['time'] = date('Y-m-d H:i:s',time());
            $res['tips'] = html_entity_decode($res['tips']);
            _success(array('res'=>$res));

         }
         else if($action=='savep'){
                 //保存项目
                 $data = array(
                    'rand_day' => date('Y-m-d',$_GPC['rand_day']),
                    'rand_time'=>date('H:i:s',$_GPC['rand_day']),
                    'cur_day'=>$_GPC['cur_day'],
                    'name' =>$_GPC['title'],
                    'project_texdesc'=>$_GPC['textdesc'],
                    'detail'=>mb_substr(strip_tags($_GPC['textdesc']),0,20,'utf8'),
                    'is_secret'=>$_GPC['is_secret'],
                    'has_sh'=>$_GPC['has_sh'] ,
                    'yunfei'=>$_GPC['yunfei'],
                    'deliveryTime'=>$_GPC['deliveryTime'],
                 );


                 //图片处理
                $_GPC['pic'] = str_replace(array("&quot;","[","]"),'',$_GPC['pic']);
//

                $wxId = explode(',',$_GPC['pic']);

                if(is_array($wxId)){

                  foreach ($wxId as $key => $value) {
                     if($key<4){
                       $cover_thumb[$key] = $value;
                     }
                  }

                  if(!empty($wxId)){
                    $wxId = json_encode($wxId);
                  }else{
                    $wxId = '';
                  }

                  if(!empty($cover_thumb)){
                      $cover_thumb = json_encode($cover_thumb);
                  }else{
                      $cover_thumb = '';
                  }

                }
                $data['thumb'] = $wxId;
                $data['cover_thumb'] = $cover_thumb;

                $tar_monet = (float)$_GPC['tar_money'];
                //回报
                  $reward_price = clearTag($_GPC['reward_price']);
                $reward_price = array_filter($reward_price);

                $reward_content = clearTag($_GPC['reward_content']);
                $reward_id = clearTag($_GPC['reward_id']);
                $reward_pic = clearTag($_GPC['reward_pic']);
                $reward_limit = clearTag($_GPC['reward_limit']);
                if($reward_price[0]){
      // _success(array('res'=>$reward_id));
                    foreach ($reward_price as $key => $value) {
                          $rtemp[$reward_id[$key]] = array(
                            'supportNumber'=>empty($reward_limit[$key])?0:$reward_limit[$key],
                            'supportContent'=>$reward_content[$key],
                            'thumb'=>getImg($reward_pic[$key]),
                            'places'=>$value,
                            'tid'=>$reward_id[$key]
                          );
                    }
                     $data['reward'] = json_encode($rtemp);

                }

                //清单
                $dream_price =str_replace(array("&quot;","[","]"),'',$_GPC['dream_price']);
                $dream_price = explode(',',$dream_price);
                $dream_price = array_filter($dream_price);
                $dream_content = str_replace(array("&quot;","[","]"),'',$_GPC['dream_content']);
                $dream_content = explode(',',$dream_content);
                $dream_id = str_replace(array("&quot;","[","]"),'',$_GPC['dream_id']);
                $dream_id = explode(',',$dream_id);
                if(!empty($dream_price[0])){
                  $tar_monet = 0;
                      foreach ($dream_price as $key => $value) {
                          $tar_monet =$tar_monet +  $value;
                          $_dream[$key]['money']= $value;
                          $_dream[$key]['content']= $dream_content[$key];
                          $_dream[$key]['dream_id']= $dream_id[$key];
                      }
                      $_dream = json_encode($_dream);
                      $data['dream'] = 1;
                }
                 $is_shenhe = pdo_fetchcolumn("SELECT is_shenhe FROM ".tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and id=".$_GPC['pid']);
                if($is_shenhe==1){  $status=4;  }else{ $status=1; }
                 $data['upbdate'] = time();
                 $data['tar_monet'] = $tar_monet;
                 $data['status'] = $status;
                 $data['weid']= $this->weid;
                 $data['pid']= $_GPC['pid'];
                 $data['mid'] = $_GPC['mid'];
                $a = pdo_insert(GARCIA_PREFIX."fabu",$data);
                if($a==1){
                  _success(array('msg'=>'保存成功2'));
                }else{
                  _fail(array('msg'=>'保存失败'));
                }
           }else if($action=='editor'){
               $id = $_GPC['id'];
               if(empty($id)){
                  _fail(array('msg'=>'没有项目id'));
               }

               $sql = "SELECT a.id,b.project_name as pname,
               b.project_desc as tips,project_min as minday,project_max as maxday,a.upbdate as `time`,a.rand_day FROM ".tablename(GARCIA_PREFIX."fabu")." a
               LEFT JOIN ".tablename(GARCIA_PREFIX."project")." b on a.pid =b.id
               WHERE a.weid=".$this->weid." and a.id=".$id;
               $res = pdo_fetch($sql);


                $res['setdata'] =  $this->diffDate(date('Y-m-d',$res['time']),$res['rand_day']);
                $res['times'] = $res['time'];
                $res['time'] =  $res['rand_day']; 
                $res['tips'] = html_entity_decode($res['tips']);
               _success(array('res'=>$res));
           }


    function clearTag($key){
      $t=str_replace(array("&quot;","[","]"),'',$key);
      $t = explode(',',$t);
      return $t;
    }

    function getImg($pic){
       global $_W,$_GPC;
       return pdo_fetchcolumn('SELECT thumb FROM '.tablename(GARCIA_PREFIX."photo")." where weid=".$_W['uniacid']."  and id=".$pic);
    }

 ?>
