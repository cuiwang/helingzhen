<?php


  $display = empty($_GPC['display'])?'list':$_GPC['display'];
$dopost = $_GPC['dopost'];
  $do = empty($_GPC['do'])?'project':$_GPC['do'];

  if(!empty($dopost)){
      if($dopost=='save_project'){





           //保存项目操作
           $id= $_GPC['id'];
           if(empty($_GPC['project_name'])){
              message('项目名称不能为空',referer(),'error');
           }
           if(empty($_GPC['project_desc'])){
              message('项目说明不能为空',referer(),'error');
           }
           if($_GPC['project_plus_com']==1){
               $project_plus3 = 1;
           }else if($_GPC['project_plus_com']==2){
             $project_plus4 = 1;
           }
           else if($_GPC['project_plus_com']==3){
             $project_plus5 = 1;
           }
           else{
               $project_plus5 = 0;
               $project_plus3 = 0;
               $project_plus4 = 0;
           }
           if($_GPC['banner']){
              $banner = json_encode($_GPC['banner']);
           }else{
              $banner='';
           }
           if($_GPC['group1']){
               foreach ($_GPC['group1'] as $k => $v) {
                   $group1[] = array(
                      'thum'=>$v,
                      'link'=>$_GPC['group1link'][$k]
                   );
               }
           }
           if($_GPC['group2']){
               foreach ($_GPC['group2'] as $k => $v) {
                   $group2[] = array(
                      'thum'=>$v,
                      'link'=>$_GPC['group2link'][$k]
                   );
               }
           }
           $group1 = json_encode($group1);
           $group2 = json_encode($group2);


          $data = array(
              'project_name'=>$_GPC['project_name'],
              'project_logo'=>$_GPC['project_logo'],
              'project_plus1'=>$_GPC['project_plus1'],
              'project_plus2'=>$_GPC['project_plus2'],
              'project_plus3'=>$project_plus3,
              'project_plus4'=>$project_plus4,
              'project_plus5'=>$project_plus5,
              'project_desc'=>$_GPC['project_desc'],
              'project_shuoming'=>$_GPC['project_shuoming'],
              'project_max'=>$_GPC['project_max'],
              'project_min'=>$_GPC['project_min'],
              'project_moren'=>$_GPC['project_moren'],
              'project_texdesc'=>$_GPC['project_texdesc'],
              'project_mstips'=>$_GPC['project_mstips'],
              'weid'=>$this->weid,
              'title_placeholder'=>$_GPC['title_placeholder'],
              'desc_placeholder'=>$_GPC['desc_placeholder'],
              'project_gg'=>$_GPC['project_gg'],
              'is_p'=>$_GPC['is_p'],
              'is_shenhe'=>$_GPC['is_shenhe'],
              'pre_id'=>$_GPC['pre_id'],
              'yongjin' =>$_GPC['yongjin'],
              'shouchishenfenz' =>$_GPC['shouchishenfenz'],
              'end_type'=>$_GPC['end_type'],
              'is_goods'=>$_GPC['is_goods'],
              'is_suptel'=>$_GPC['is_suptel'],
              'is_hospital'=>$_GPC['is_hospital'],
              'is_show'=>$_GPC['is_show'],
              'is_pc'=>$_GPC['is_pc'],
              'is_gongyi'=>$_GPC['is_gongyi'],
              'banner'=>$banner,
              'views_bottom'=>$_GPC['views_bottom'],
              'views_top'=>$_GPC['views_top'],
              'group2'=>$group2,
              'group1'=>$group1,
              'is_use'=>$_GPC['is_use']
          );
          if(empty($id)){
             $data['upbdate']=time();
             pdo_insert(GARCIA_PREFIX."project",$data);
          }else{
             pdo_update(GARCIA_PREFIX."project",$data,array('id'=>$id));
          }
          message('操作成功',referer(),'success');
          //  var_dump($data);
      }
      else if($dopost=='save_ques'){
        $pid = $_GPC['pid'];
        $id = $_GPC['id'];
        $data = array(
          'weid'=>$this->weid,
          'pid'=>$pid,
          'title'=>trim($_GPC['title']),
          'content'=>$_GPC['content'],
          'rank'=>$_GPC['rank'],
        );
        if(!empty($id)){
          pdo_update(GARCIA_PREFIX."ques",$data,array('id'=>$id));
        }else{
          $data['upbdate']= time();
          pdo_insert(GARCIA_PREFIX."ques",$data);
        }
        message('操作成功',$this->createWebUrl('project',array('display'=>'ques','pid'=>$pid)),'success');
        exit;
      }
      else if($dopost=='save_oques'){
           $id = $_GPC['id'];
           $pre_id= $_GPC['pre_id'];
           $type = empty($_GPC['type'])?$_GPC['type2']:$_GPC['type'];
            $data  = array(
              'title'=>$_GPC['title'],
              'rank'=>$_GPC['rank'],
              'level'=>$_GPC['level'],
              'type'=>$_GPC['type'],
              'content'=>$_GPC['content'],
              'weid'=>$this->weid,
              'pre_id'=>$_GPC['pre_id'],
            );

            if(empty($id)){
               $data['upbdate']= time();
               pdo_insert(GARCIA_PREFIX."oques",$data);
            }else{
               pdo_update(GARCIA_PREFIX."oques",$data,array('id'=>$id));
            }
              if($_GPC['level']==1){
                message('操作成功',$this->createWebUrl('project',array('display'=>'oques')),'success');
             }else{
               message('操作成功',$this->createWebUrl('project',array('display'=>'oques','pre_id'=>$_GPC['pre_id'],'type'=>$type,'level'=>$_GPC['level'])),'success');
             }
      }else if($dopost=='oques_del'){
        pdo_delete(GARCIA_PREFIX."oques",array('id'=>$_GPC['id']));
        message('操作成功',referer(),'success');
      }else if($dopost=='uprank'){
         pdo_update(GARCIA_PREFIX.'project',array('rank'=>$_GPC['rank']),array('id'=>$_GPC['id']));
         die(json_encode(array('status'=>1)));
      }else if($dopost=='del_pro'){
        pdo_delete(GARCIA_PREFIX."project",array('id'=>$_GPC['pid']));
        message("删除成功",referer(),'success');
      }
  }

  if($display=='list'){
       $list = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and pre_id=0");
  }
  else if($display=='ques'){
     if($_GPC['action']!='editor_qeus'){
       $_list = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."ques")." where weid=".$this->weid." and pid=".$_GPC['pid']." order by rank asc");
     }else{
        $ques =pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."ques")." where weid=".$this->weid." AND  id=".$_GPC['id']);
     }
  }else if($display=='oques'){
     $level = empty($_GPC['level'])?1:$_GPC['level'];
     $pre_id= $_GPC['pre_id'];
     if(!empty($pre_id)){
       $pre = " and pre_id=".$pre_id;
     }else{
        $pre = '';
     }
     if($_GPC['action']!='editor_qeus'){
       $_list = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."oques")." where weid=".$this->weid." and level =".$level.$pre." order by rank asc");
     }else{
        $ques =pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."oques")." where weid=".$this->weid." and level =".$level." and id=".$_GPC['id']);

     }
  }
  if($_GPC['action']=='e_project'){
     $project = pdo_fetch('SELECT * FROM '.tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." AND id=".$_GPC['id']);
    //  var_dump($project);
  }

  include $this->template('admin/project/'.$display);


  function _projectname($id){
     global $_W;
    //  uniacid
    return pdo_fetchcolumn('SELECT project_name FROM '.tablename(GARCIA_PREFIX."project")." where weid=".$_W['uniacid']." AND id=".$id);
  }
  function _getson($preid){
     global $_W;


    $list  = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."project")." where weid=".$_W['uniacid']." and pre_id=".$preid);
    if($list){
        return $list;
    }else{
        return '';
    }

  }
 ?>
