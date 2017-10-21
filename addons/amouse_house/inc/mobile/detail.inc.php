<?php
/**
 *易福源码网 http://www.efwww.com
 * User: user
 * Date: 15-3-14
 * Time: 上午11:10
 * To change this template use File | Settings | File Templates.
 */

global $_W,$_GPC;
load()->func('tpl');
$weid= $_W['uniacid'];
$setting= $this->get_sysset($weid);
$id= intval($_GPC['id']);
$a= !empty($_GPC['a']) ? $_GPC['a'] : 'rent';
$type =!empty($_GPC['type']) ? $_GPC['type'] : '0';
if($a == 'rent') {
    $item= pdo_fetch("SELECT * FROM ".tablename('amouse_house')." WHERE weid=:weid AND id = :id", array(':weid'=>$weid,':id' => $id));
    if(empty($item)) {
        message("抱歉，信息不存在!", referer(), "error");
    }
    if($type==0||$type==1){
        $recommeds=pdo_fetchall("SELECT * FROM ".tablename('amouse_house')." WHERE weid=:weid AND (type='0' OR type='1') AND recommed=1 ORDER BY createtime ", array(':weid'=>$weid));
        $url=$_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('a'=>'rent','type'=>$type,'id'=>$item[id]),true),2);
    }elseif($type==2||$type==3){
        $recommeds=pdo_fetchall("SELECT * FROM ".tablename('amouse_house')." WHERE weid=:weid AND (type='2' OR type='3') AND recommed=1 ORDER BY createtime ", array(':weid'=>$weid));
        $url=$_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('a'=>'rent','type'=>$type,'id'=>$item[id]),true),2);
    }

    include $this->template('house/rent_detail');
}elseif($a='house'){
    $item= pdo_fetch("SELECT * FROM ".tablename('amouse_newflats')." WHERE weid=:weid AND id = :id", array(':weid'=>$weid,':id' => $id));
    if(empty($item)) {
        message("抱歉，信息不存在!", referer(), "error");
    }
    $url=$_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('a'=>'house','id'=>$item[id]),true),2);
    include $this->template('house/new_house_detail');
}