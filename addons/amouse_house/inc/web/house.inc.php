<?php
//易福源码网 http://www.efwww.com

global $_GPC, $_W;
load()->func('tpl');
$op= $_GPC['op'] ? $_GPC['op'] : 'display';
$weid= $_W['uniacid'];

if($op == 'display') {
    $pindex= max(1, intval($_GPC['page']));
    $psize= 20; //每页显示
    $starttime= empty($_GPC['date']['start']) ? strtotime('-1 month') : strtotime($_GPC['date']['start']);
    $endtime= empty($_GPC['date']['end']) ? TIMESTAMP : strtotime($_GPC['date']['end']) + 86399*2;
    $pindex= max(1, intval($_GPC['page']));
    $psize= 20;
    $condition=" WHERE weid=:weid AND createtime>=:starttime AND createtime<=:endtime";
    $paras= array(':weid'=>$weid,':starttime' => $starttime, ':endtime' => $endtime);
    $status=$_GPC['status'];
    $type = $_GPC['type'];  //类型 出售新房 ---
    if ($status!='') {
        $condition .= " AND status='".$status. "'";
    }
    if (!empty($type)) {
        $condition .= " AND type = '" . $type. "'";
    }
    if(!empty($_GPC['title'])) {
        $condition .= " AND title LIKE '%".$_GPC['title']."%'";
    }
    $list= pdo_fetchall('SELECT * FROM '.tablename('amouse_house')." $condition ORDER BY createtime desc  LIMIT ".($pindex -1) * $psize.','.$psize, $paras);
    $total= pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('amouse_house').$condition, $paras);
    $pager= pagination($total, $pindex, $psize);

}elseif($op == 'verify') {  //审核
    $id= intval($_GPC['id']);
    if($id > 0) {
        if(pdo_update('amouse_house',array('status' => 1), array('id' => $id)) === false) {
            message('审核失败, 请稍后重试.', 'error');
        }
        message('审核成功！', $this->createWebUrl('house', array('op' => 'display', 'name' => 'amouse_house')), 'success');
    }
}elseif($op == 'recommed'){//认证
    $id= intval($_GPC['id']);
    $recommed= intval($_GPC['recommed']);
    if($recommed==1){
        $msg='认证';
    }elseif($recommed==0){
        $msg='取消认证';
    }
    if($id > 0) {
        pdo_update('amouse_house',array('recommed' =>$recommed), array('id' => $id)) ;
        message($msg.'成功！', $this->createWebUrl('house', array('op' => 'display', 'name' => 'amouse_house')), 'success');
    }
}elseif($op == 'del') { //删除
    if(isset($_GPC['delete'])) {
        $ids= implode(",", $_GPC['delete']);
        $sqls= "delete from  ".tablename('amouse_house')."  where id in(".$ids.")";
        pdo_query($sqls);
        message('删除成功！', referer(), 'success');
    }
    $id= intval($_GPC['id']);
    $temp= pdo_delete("amouse_house", array('id' => $id));
    message('删除数据成功！', $this->createWebUrl('house', array('op' => 'display', 'name' => 'amouse_house')), 'success');
}elseif($op == 'post') {
    $id= intval($_GPC['id']);
    load()->func('tpl');
    if($id > 0) {
        $item= pdo_fetch('SELECT * FROM '.tablename('amouse_house')." WHERE id=:id", array(':id' => $id));
    }

    if(checksubmit('submit')) {
        $data = array(
            'title'=> trim($_GPC['title']),
            'price'=> trim($_GPC['price']),
            'square_price'=> trim($_GPC['square_price']),
            'area'=>trim($_GPC['area']),
            'house_type'=> $_GPC['house_type'],
            'status'=> 1,
            'recommed'=>0,
            'floor'=> trim($_GPC['floor']),
            'orientation'=> $_GPC['orientation'],
            'contacts'=> trim($_GPC['contacts']),
            'location_p' => $_GPC['district']['province'],
            'location_c' => $_GPC['district']['city'],
            'location_a' => $_GPC['district']['district'],
            'phone'=> trim($_GPC['phone']),
            'lng' => $_GPC['baidumap']['lng'],
            'lat' => $_GPC['baidumap']['lat'],
            'thumb1'=>trim($_GPC['thumb1']),
            'weid'=>$weid,
            'brokerage'=>$_GPC['brokerage'],
            'thumb3'=>trim($_GPC['thumb3']),
            'thumb2'=>trim($_GPC['thumb2']),
            'createtime'=> TIMESTAMP,
            'introduction'=> trim($_GPC['introduction'])  );

        if(empty($id)) {
            pdo_insert('amouse_house', $data);
        } else {
            pdo_update('amouse_house', $data, array('id' => $id));
        }
        message('更新新楼盘数据成功！', $this->createWebUrl('house', array('op' => 'display', 'name' => 'amouse_house')), 'success');
    }
}
include $this->template('web/house');

?>
