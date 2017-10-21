<?php


   if($action=='sups'){

     $page = max(1,$_GPC['page']);
     $psize = 5;
     $llist  = pdo_fetchall('SELECT a.fee,a.msg,a.id,b.nickname,b.headimgurl as avatar,a.upbdate,a.wantSupportTel FROM '.tablename(GARCIA_PREFIX."paylog")." a
     LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b  on a.mid=b.id
     where a.weid=".$this->weid." and a.fid=".$_GPC['id']." and a.status=1 and a.type=0 and a.fee!=0 order by id desc  limit ".($page-1)*$psize.",".$psize);
     $scount = pdo_fetchcolumn('SELECT count(a.id) as c FROM '.tablename(GARCIA_PREFIX."paylog")." a
     where a.weid=".$this->weid." and a.fid=".$_GPC['id']." and a.status=1 and a.type=0 and a.fee!=0");
     $scount2 = $scount-(($page-1)*$psize);
      $pager = ceil($scount/$psize);

     for ($i=0; $i <$pager ; $i++) {
        $pagers[$i] = array(
           'page'=>($i+1)
        );
     }
    foreach ($llist as $key => $value) {
        $res[] = array(
           'i'=>$scount2,
           'nickname'=>urldecode($value['nickname']),
           'fee'=>$value['fee'],
           'time1'=>date('Y-m-d',$value['upbdate']),
           'time2'=>date('H:i:s',$value['upbdate']),
        );
        $scount2--;
    }
       _success(array('res'=>$res,'pages'=>$pagers,'cur'=>$page));
   }else if($action=='message'){
     $id =$_GPC['id'] ;
     $list = pdo_fetchall('SELECT a.id,a.upbdate,a.content,b.nickname,b.headimgurl as avatar,b.id as mid FROM '.tablename(GARCIA_PREFIX."message")." a
                           LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id where a.weid=".$this->weid." and a.fid=".$id." order by id desc");
      foreach ($list as $k => $v) {
            foreach ($v as $k1 => $v2) {
                if($k1=='nickname'){
                    $list[$k][$k1] = urldecode($v2);
                }
                 if($k1=='avatar'){
                    $list[$k][$k1] = tomedia($v2);
                }
                if($k1=='upbdate'){
                      $list[$k]['upbdate'] = date('Y-m-d',$v2);
                }
            }

      }
     _success(array('res'=>$list));
   }
   else{
          _fail(array('msg'=>'not found function'));
  }

 ?>
