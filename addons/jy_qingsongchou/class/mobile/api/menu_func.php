<?php

   if($is_memache){
       $_mkeys = md5('menu_'.$this->weid);
      //  $list = $memcache_obj->get($_mkeys);
      //  $list = json_decode($list,true);
      //  array_push($list,array('memache'=>$_mkeys));
   }else{
       $_mkeys = false;
   }
   if(empty($list)||!$_mkeys){
       $pre_ids = explode(',',$pre_ids);
       $pre_ids = array_unique($pre_ids);
       $pre_ids = implode(',',$pre_ids);
       if(!empty($pre_ids)){
           $notIn = " AND id not in(".$pre_ids.")";
       }else{
          $notIn = '';
       }

       $_menulist = pdo_fetchall('SELECT a.id,a.project_name FROM '.tablename(GARCIA_PREFIX."project")." a
       where a.weid=".$this->weid." and a.is_p=1 and is_show=1 ".$notIn." order by a.rank asc");

       $list['total'] = count($_menulist);

               array_unshift($_menulist,array('id'=>'0','project_name'=>$this->sys['head_title1']));
                // sort($_menulist);
       $list['list']= $_menulist;
        if($is_memache){
          // $a = $memcache_obj->set($_mkeys,json_encode($list));
        }
   }

    _success($list);
 ?>
