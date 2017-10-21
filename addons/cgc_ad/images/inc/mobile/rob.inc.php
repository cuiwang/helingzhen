<?php

	global $_W,$_GPC;
    $weid=$_W['uniacid'];
    $quan_id=intval($_GPC['quan_id']);
    $id=intval($_GPC['id']);    
    $member=$this->get_member();
    $from_user=$member['openid'];
    $quan=$this->get_quan();
    $mid=$member['id'];
    $config = $this ->settings;
    $rob_num=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('cgc_ad_red')." WHERE weid=".$weid." AND mid=".$mid." AND quan_id=".$quan_id." order by create_time desc");
    $rob=pdo_fetchall("SELECT a.create_time,a.money,b.title,b.content,b.id,c.type FROM ".tablename('cgc_ad_red')." as a left join ".tablename('cgc_ad_adv')." as b on a.advid=b.id left join ".tablename('cgc_ad_member')." as c on b.mid=c.id WHERE a.weid=".$weid." AND a.mid=".$mid." AND a.quan_id= ".$quan_id." order by create_time desc");
    include $this->template('rob');


		

