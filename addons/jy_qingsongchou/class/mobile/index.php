<?php

    /**
     * 首页方法
     */
     if($this->sys['is_h5']&&$this->modal == 'phone'){
        $project_l = pdo_fetchall("SELECT * FROM ".tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and is_p=1");
        $banner = pdo_fetchall("SELECT * FROM ".tablename(GARCIA_PREFIX."banner")." where weid=".$this->weid."  and type = 0 order by thumb_rank asc");
        $this->config['list'] = $project_l;
        $this->config['banner'] = $banner;
        $this->h5t('index/index');
     }
     else if($this->modal=='pc'){
            $sons = pdo_fetchall("SELECT id,project_name as name FROM ".tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and is_p=1 and pre_id!=0 order by rank asc limit 6");
            $plist = pdo_fetchall('SELECT id,project_name as name FROM '.tablename(GARCIA_PREFIX."project")." where weid=".$_W['uniacid']." and pre_id=0 and is_p=1");
           $tlist = pdo_fetchall('SELECT a.*,b.headimgurl as avatar,b.nickname,c.project_name as pname FROM '.tablename(GARCIA_PREFIX."fabu")." a
           LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
           LEFT JOIN ".tablename(GARCIA_PREFIX."project")." c on c.id=a.pid
           where  a.weid=".$this->weid."  and a.status=1  group by  a.id order by a.rank,a.id desc  limit 0,6");
           foreach ($tlist as $key => $value) {
                foreach ($value as $k => $v) {
                     if($k=='cover_thumb'){
                       $_id = '';
                       $ids = json_decode($v,true);
                       foreach ($ids as $k3 => $v3) {
                         if(!is_numeric($v3))continue;
                         if($k3==0){
                            $_id=$v3;
                         }
                       }

                       if(!empty($_id)){
                         $_th = pdo_fetchcolumn("SELECT pic FROM ".tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id =".$_id);
                         $tlist[$key]['cover_thumb'] = $_th;
                       }
                     }

                     else if($k=='project_texdesc'){
                         $tlist[$key]['description'] = mb_substr(strip_tags(htmlspecialchars_decode($v)),0,60,'utf-8');
                     }
                     else if($k=='avatar'){
                         $tlist[$key]['avatar'] =empty($v)?tomedia($this->sys['user_img']):$v;
                     }
                     else if($k=='nickname'){
                         $tlist[$key]['nickname'] =empty($v)?"用户:".$tlist[$key]['id']:urldecode($v);
                     }

                     $_sum = 0;
                     $_sum = pdo_fetchcolumn('SELECT sum(fee) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$value['id']." and status=1 and (type=0 or type=6)");
                     $_count = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$value['id']." and status=1 and (type=0 or type=6)");
                     $tlist[$key]['is_sup'] = empty($_count)?0:$_count;
                     $tlist[$key]['has_monet'] = empty($_sum)?0:$_sum;
                    //  $tlist[$key]['present'] = round($tlist[$key]['has_monet']/$tlist[$key]['tar_monet'],2)*100;
                     $tlist[$key]['present'] =round($tlist[$key]['has_monet']/$tlist[$key]['tar_monet'],2)*100;
                      $tlist[$key]['_time'] = $this->_format_date($tlist[$key]['upbdate']);
                }
                $_avatar = pdo_fetchall('SELECT b.headimgurl as avatar FROM '.tablename(GARCIA_PREFIX.'paylog')." a
                LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
                where a.weid=".$this->weid." and a.fid=".$value['id']." and a.status=1 and (a.type=0 or a.type=6) order by a.id desc limit 0,10");
                foreach ($_avatar as $k1 => $v2) {
                  $tlist[$key]['_thumb'][$k1] = $v2['avatar'];
                }
           }

           $sql = "SELECT * FROM ".tablename(GARCIA_PREFIX."media")." where weid=".$this->weid;
           $medias = pdo_fetchall($sql);

           $banner  = pdo_fetchall('SELECT * FROM '.tablename(GARCIA_PREFIX."banner")." where weid=".$this->weid." and type=1");
           include $this->template('web/index/index');
     }
     else{
        $project_l = pdo_fetchall("SELECT * FROM ".tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and is_p=1 order by rank asc");
        $banner = pdo_fetchall("SELECT * FROM ".tablename(GARCIA_PREFIX."banner")." where weid=".$this->weid." and type = 0  order by thumb_rank asc");
        $pagesize =15;
        $start = max(1, intval($_GPC['start']));
        $pid = $_GPC['pid'];
        if(!empty($pid)){
            $w = ' and a.pid='.$pid ;
            //判断是否是顶级栏目
            $_top = pdo_fetchall('SELECT id FROM  '.tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and pre_id=".$pid);
            if($_top){
                $w = ' and a.pid in (';
                foreach ($_top as $key => $value) {
                if($key==0){
                $w.= $value['id'];
                }else{
                $w.= ",".$value['id'];
                }
                }
                $w.= ')';
            }
        }else{
            $w = ' and a.is_p=1';
        }
        $_checkfbC = pdo_fetchcolumn('SELECT count(a.id) FROM '.tablename(GARCIA_PREFIX."fabu")." a where  a.weid=".$this->weid."  and a.status=1 ". $w ." group by id");
        $_checkfb = pdo_fetchall('SELECT a.*,b.headimgurl as avatar,b.nickname FROM '.tablename(GARCIA_PREFIX."fabu")." a
        LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
        where  a.weid=".$this->weid."  and a.status=1 ".$w." group by  a.id order by a.rank,a.id desc  limit ".($start - 1)*$pagesize.",".$pagesize."");
        $data['total'] = count($_checkfb);
     foreach ($_checkfb as $k => $v) {
           $_v = 0;
          foreach ($v as $k1 => $v2) {
            $_thb = $ids = $_th = '' ;
              if($k1=='cover_thumb'){

                 $ids = json_decode($v2,true);
                 foreach ($ids as $k3 => $v3) {
                   if(!is_numeric($v3))continue;
                   if($k3==0){
                      $_id.=$v3;
                   }else{
                      $_id.=",".$v3;
                   }
                 }
                 if(!empty($_id)){
                   $_th = pdo_fetchall("SELECT thumb FROM ".tablename(GARCIA_PREFIX."photo")." where weid=".$this->weid." and id in(".$_id.")");
                   foreach ($_th as $k4 => $v4) {
                      $_thb[] = $v4['thumb'];
                   }
                    $_checkfb[$k]['cover_thumb'] = $_thb;
                 }
               unset($_thb);
               unset($ids);
               unset($_th);
               unset($_id);
               $_thb= '';
              }else if($k1=='nickname'){
                $_checkfb[$k][$k1] = urldecode($v2);
              }else if($k1=='rand_day'){
                $_time = $v2;//获取过期时间
                $cur_data = date('Y-m-d',time());
                if($this->diffDate($cur_data,$_time)<=0){
                    //过期判断条件+
                    $_sum = pdo_fetchcolumn('SELECT sum(fee) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$v['id']." and status=1 and (type=0 or type=6)");
                    if($v['tar_monet']>=$_sum){
                       $_v  = 2;
                    }else{
                       $_v  = 1;
                    }

                   $_vk = $k;
                   $_checkfb[$k][$k1] =  $v['id'];
                }else{
                  $_checkfb[$k][$k1] =   $this->diffDate($cur_data,$_time);
                }

              }else if($k1=='project_texdesc'){
                  $_checkfb[$k][$k1] = html_entity_decode($v2);
              }

              $_checkfb[$k]['_time'] = $this->_format_date($_checkfb[$k]['upbdate']);
              $_checkfb[$k]['url'] = $this->createMobileUrl('detail',array('id'=>$_checkfb[$k]['id']));
              $_checkfb[$k]['detail'] = htmlspecialchars_decode($_checkfb[$k]['detail']);
              $_checkfb[$k]['detail'] =  strip_tags($_checkfb[$k]['detail']);
              $_checkfb[$k]['detail'] = mb_substr($_checkfb[$k]['detail'],0,60,'utf-8');

          }
          $_avatar = pdo_fetchall('SELECT b.headimgurl as avatar FROM '.tablename(GARCIA_PREFIX.'paylog')." a
          LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
          where a.weid=".$this->weid." and a.fid=".$v['id']." and a.status=1 and (a.type=0 or a.type=6) order by a.id desc limit 0,15");
          foreach ($_avatar as $k1 => $v2) {
            $_checkfb[$k]['_thumb'][$k1] = $v2['avatar'];
          }
          $_count = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$v['id']." and status=1 and (type=0 or type=6)");
          $_sum = pdo_fetchcolumn('SELECT sum(fee) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$this->weid." and fid=".$v['id']." and status=1 and (type=0 or type=6)");
          $_checkfb[$k]['has_monet'] = empty($_sum)?0:$_sum;
          $_checkfb[$k]['is_sup'] = empty($_count)?0:$_count;
          /**
           * 过期操作处理
           */

          if($_v==0){
              $_thumb[]= $_checkfb[$k];
          }else if($_v==1){
             //过期处理 只对过期做处理
              pdo_update(GARCIA_PREFIX."fabu",array('status'=>3),array('id'=>$v['id']));
          }else if($_v==2){
             //过期但是达成目标
              pdo_update(GARCIA_PREFIX."fabu",array('status'=>2),array('id'=>$v['id']));
          }
          $_v = 0;

     }
      $data['list']= $_thumb;
      $list = $data['list'];
      include $this->template('index');
  }



function _getson($id){
    global $_W;
       return $plist = pdo_fetchall('SELECT id,project_name as name FROM '.tablename(GARCIA_PREFIX."project")." where weid=".$_W['uniacid']." and pre_id=".$id);
}
function _getlist($pid){
  global $_W;
  $tlist = pdo_fetchall('SELECT a.*,b.headimgurl as avatar,b.nickname,c.project_name as pname FROM '.tablename(GARCIA_PREFIX."fabu")." a
  LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
  LEFT JOIN ".tablename(GARCIA_PREFIX."project")." c on c.id=a.pid
  where  a.weid=".$_W['uniacid']."  and a.status=1 and a.pid=".$pid."  group by  a.id order by a.rank,a.id desc  limit 0,3");
  foreach ($tlist as $key => $value) {
       foreach ($value as $k => $v) {
            if($k=='cover_thumb'){
              $_id = '';
              $ids = json_decode($v,true);
              foreach ($ids as $k3 => $v3) {
                if(!is_numeric($v3))continue;
                if($k3==0){
                   $_id=$v3;
                }
              }

              if(!empty($_id)){
                $_th = pdo_fetchcolumn("SELECT pic FROM ".tablename(GARCIA_PREFIX."photo")." where weid=".$_W['uniacid']." and id =".$_id);
                $tlist[$key]['cover_thumb'] = $_th;
              }
            }

            else if($k=='project_texdesc'){
                $tlist[$key]['description'] = mb_substr(strip_tags(htmlspecialchars_decode($v)),0,60,'utf-8');


            }
            else if($k=='nickname'){
              $tlist[$key][$k] = urldecode($v);
            }
            else if($k=='avatar'){
                $tlist[$key][$k] = tomedia($v);
            }
            $_sum = 0;
            $_sum = pdo_fetchcolumn('SELECT sum(fee) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$_W['uniacid']." and fid=".$value['id']." and status=1 and (type=0 or type=6)");
            $_count = pdo_fetchcolumn('SELECT count(id) FROM '.tablename(GARCIA_PREFIX.'paylog')." where weid=".$_W['uniacid']." and fid=".$value['id']." and status=1 and (type=0 or type=6)");
            $tlist[$key]['is_sup'] = empty($_count)?0:$_count;
            $tlist[$key]['has_monet'] = empty($_sum)?0:$_sum;
            $tlist[$key]['present'] = round($tlist[$key]['has_monet']/$tlist[$key]['tar_monet'],2)*100;
              $tlist[$key]['_time'] = _format_date($tlist[$key]['upbdate']);
       }
       $_avatar = pdo_fetchall('SELECT b.headimgurl as avatar FROM '.tablename(GARCIA_PREFIX.'paylog')." a
       LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
       where a.weid=".$_W['uniacid']." and a.fid=".$value['id']." and a.status=1 and (a.type=0 or a.type=6) order by a.id desc limit 0,10");
       foreach ($_avatar as $k1 => $v2) {
         $tlist[$key]['_thumb'][$k1] = $v2['avatar'];
       }
  }
  return $tlist;
}

function _format_date($time){
    $t=time()-$time;
      $f=array(
      '31536000'=>'年',
      '2592000'=>'个月',
      '604800'=>'星期',
      '86400'=>'天',
      '3600'=>'小时',
      '60'=>'分钟',
      '1'=>'秒'
      );
    foreach ($f as $k=>$v)    {
      if (0 !=$c=floor($t/(int)$k)) {
          return $c.$v.'前';
      }
    }
  }
 ?>
