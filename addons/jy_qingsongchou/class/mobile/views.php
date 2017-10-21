<?php

$project = pdo_fetch('SELECT views_top,views_bottom FROM '.tablename(GARCIA_PREFIX."project")." where weid=".$this->weid." and id=".$_GPC['id']);
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
          $tlist[$key]['present'] = round($tlist[$key]['has_monet']/$tlist[$key]['tar_monet'],2)*100;
           $tlist[$key]['_time'] = $this->_format_date($tlist[$key]['upbdate']);
     }
     $_avatar = pdo_fetchall('SELECT b.headimgurl as avatar FROM '.tablename(GARCIA_PREFIX.'paylog')." a
     LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.mid=b.id
     where a.weid=".$this->weid." and a.fid=".$value['id']." and a.status=1 and (a.type=0 or a.type=6) order by a.id desc limit 0,10");
     foreach ($_avatar as $k1 => $v2) {
       $tlist[$key]['_thumb'][$k1] = $v2['avatar'];
     }
}

  include $this->template('web/views/index');
 ?>
