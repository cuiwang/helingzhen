<?php

    if($this->modal!='pc'){ include  $this->template('bad'); exit;}

     $display = empty($_GPC['display'])?'index':$_GPC['display'];
     $dopost = $_GPC['dopost'];

     if($dopost=="save_up"){
       $wxId = $_GPC['wxid'];
       if(is_array($wxId)){
           $thumb= json_encode($wxId);
       }
       $data = array(
         'weid'=>$this->weid,
         'mid'=>$this->conf['user']['mid'],
         'fid'=>$_GPC['fid'],
         'content'=>$_GPC['content'],
         'thumb'=>$thumb,
         'upbdate'=>time()
       );

         pdo_insert(GARCIA_PREFIX."update",$data);
         $data = array(
                       'title'=>'操作成功',
                       'desc'=>'项目更新成功',
                       'type'=>1,
                       'btn'=>'返回项目管理',
                       'url'=>$this->createMobileUrl('detail',array('id'=>$_GPC['fid']))
                     );
         $this->_WebWait($data);
       exit;
     }else if($dopost=='save_early'){

       $data = array(
         'weid'=>$this->weid,
         'mid'=>$this->conf['user']['mid'],
         'fid'=>$_GPC['fid'],
         'content'=>$_GPC['content'],
         'upbdate'=>time(),
         'type'=>1,
         'status'=>2,
       );
      //  var_dump($data);
       pdo_insert(GARCIA_PREFIX."update",$data);
       pdo_update(GARCIA_PREFIX."fabu",array('early'=>1),array('id'=>$_GPC['fid']));
      $data = array(
                    'title'=>'操作成功',
                    'desc'=>'提前结束申请成',
                    'type'=>1,
                    'btn'=>'返回项目管理',
                    'url'=>$this->createMobileUrl('fmanger',array('id'=>$_GPC['fid']))
                  );
      $this->_WebWait($data);
       exit;
     }else if($dopost=='del'){

              pdo_update(GARCIA_PREFIX."fabu",array('status'=>6),array('id'=>$_GPC['id']));
              $data = array(
                            'title'=>'操作成功',
                            'desc'=>'删除成功',
                            'type'=>1,
                            'btn'=>'返回项目管理',
                            'url'=>$this->createMobileUrl('myorder',array('display'=>'faqi'))
                          );
              $this->_WebWait($data);
               exit;
     }else if($dopost=='save_fahuo'){

       $data = array(
         'weid'=>$this->weid,
         'kuaidi'=>$_GPC['kuaidi'],
         'kuai_order'=>$_GPC['kuai_order'],
         'fahuo_time'=>$_GPC['fahuo_time'],
         'address_id'=>$_GPC['address_id'],
         'status'=>0,
         'upbdate'=>time(),
         'reid'=>$_GPC['reid'],
         'fid' =>$_GPC['fid'],
         'pid'=>$_GPC['pid'],
         'mid'=>$_GPC['mid'],
       );
      //  var_dump($data);
      pdo_insert(GARCIA_PREFIX."fahuo",$data);
      pdo_update(GARCIA_PREFIX."paylog",array('fahuo'=>1),array('id'=>$_GPC['pid']));
      $data = array(
                    'title'=>'操作成功',
                    'desc'=>'发货成功',
                    'type'=>1,
                    'btn'=>'返回项目管理',
                    'url'=>$this->createMobileUrl('fmanger',array('display'=>'index','id'=>$_GPC['fid']))
                  );
      $this->_WebWait($data);
       exit;
     }


    if($display=='index'){
        $_title = pdo_fetchcolumn('SELECT name FROM '.tablename(GARCIA_PREFIX."fabu")." where weid=".$this->weid." and id=".$_GPC['id']);

        $sql = "SELECT a.*,c.nickname,c.headimgurl as avatar ,
        b.project_plus4 as is_reward ,b.project_plus3 as is_list,b.is_hospital,b.is_goods,
        c.id as mid FROM ".tablename(GARCIA_PREFIX.'fabu')." a
        LEFT JOIN ".tablename(GARCIA_PREFIX."project")." b on a.pid=b.id
        LEFT JOIN ".tablename(GARCIA_PREFIX."member")." c on a.mid=c.id
        where a.weid=".$this->weid." and a.id=".$_GPC['id'];
        $config =  pdo_fetch($sql);
        $config['superNumber'] = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$_GPC['id']." and status=1 and type=0  and fee!=0");
        $config['hasMoney'] = pdo_fetchcolumn('SELECT sum(fee) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$_GPC['id']." and status=1 and type=0 ");
        $config['hasMoney'] = max(0,$config['hasMoney']);
        $config['present'] = round($config['hasMoney']/$config['tar_monet'],2)*100;
        $config['hasMoney'] = number_format($config['hasMoney'],2);

        $paylog = pdo_fetchall("SELECT a.*,b.headimgurl as avatar,b.nickname FROM ".tablename(GARCIA_PREFIX."paylog")." a
         LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid = b.id
         where a.weid=".$this->weid." and a.fid=".$_GPC['id']." and a.status=1 and a.type=0 order by upbdate desc");

    }else if($display=='update'){

    }
    else if($display=='excel'){
      $paylog = pdo_fetchall("SELECT a.*,b.headimgurl as avatar,b.nickname FROM ".tablename(GARCIA_PREFIX."paylog")." a
       LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid = b.id
       where a.weid=".$this->weid." and a.fid=".$_GPC['id']." and a.status=1 and a.type=0");
       $title = array(
              array(
                'name'=>'昵称',
                'width'=>10,
              ),
              array(
                'name'=>'金额',
                'width'=>10,
              ),
              array(
                'name'=>'订单号',
                'width'=>10,
              ),
              array(
                'name'=>'创建时间',
                'width'=>10,
              ),
       );
       foreach ($paylog as $key => $value) {
           $data[]  = array(
              'nickname'=>urldecode($value['nickname']),
              'fee'=>$value['fee'],
              'tid'=>$value['tid'],
              'upbdate'=>date('Y-m-d H:i:s',$value['upbdate'])
           );
       }
       $name = '流水_'.$_GPC['id'];
       $this->_pushExcel($title,$data,$name);
      exit;
    }
    else if($display=='yz'){
        $list = pdo_fetch("SELECT * FROM ".tablename(GARCIA_PREFIX."shouchishenfenz")." where  weid=".$this->weid." and fid=".$_GPC['id']);
        if($list){
            $list['less'] =  $this->_format_date($list['upbdate']);
        }

    }
    else if($display=='fahuo'){
       $sql = "SELECT a.id,a.fee,a.fid,a.reid,a.address_id,a.upbdate,a.type,a.tid,a.count,b.nickname,b.headimgurl as avatar, a.fahuo,
       d.reward,c.tel,c.name,c.province,c.city,c.area,c.address ,b.id as mid FROM ".tablename(GARCIA_PREFIX."paylog")." a
       LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b  on a.mid=b.id
       LEFT JOIN ".tablename(GARCIA_PREFIX."address")." c on c.id = a.address_id
       LEFT JOIN ".tablename(GARCIA_PREFIX."fabu")." d on d.id = a.fid
       where a.weid=".$this->weid." and a.id=".$_GPC['pid'];
       $conf = pdo_fetch($sql);
       if(empty($conf['address_id'])){
          $temp  = pdo_fetch('SELECT id,name,tel,province,city,area,address FROM '.tablename(GARCIA_PREFIX."address")." where weid=".$this->weid." and mid=".$conf['mid']." and is_def=1");
          // var_dump($temp);
          $conf['address_id'] = $temp['id'];
          $conf['name'] = $temp['name'];
          $conf['tel'] = $temp['tel'];
          $conf['province'] = $temp['province'];
          $conf['city'] = $temp['city'];
          $conf['area'] = $temp['area'];
          $conf['address'] = $temp['address'];
       }
       $conf['count']= max(1,$conf['count']);
       $conf['reward'] = json_decode($conf['reward'],true);
      //  var_dump($conf['reward']);
       if(empty($conf['reid'])){
          $conf['rname'] = '无私奉献';
       }else{
          $conf['rname'] = $conf['reward'][$conf['reid']]['supportContent'];

       }


    }
    else if($display=='early'){
         $list = pdo_fetch("SELECT id FROM ".tablename(GARCIA_PREFIX."shouchishenfenz")." where  weid=".$this->weid." and fid=".$_GPC['id']);
    }
    else if($display=='ikuaidi'){
          $_kuaidi = array('申通' ,'圆通' ,'中通' ,'汇通' ,'韵达' ,'顺丰' ,'ems' ,'天天','宅急送','邮政' ,'德邦','全峰');
    }
    include $this->template('web/fmanger/'.$display);
 ?>
