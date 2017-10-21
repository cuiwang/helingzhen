<?php
global $_GPC, $_W;
$op = $_GPC['op'] ? $_GPC['op'] : 'display';
$weid = $_W['uniacid'];
if ($op == 'display') {
    $pindex= max(1, intval($_GPC['page']));
    $psize= 20; //每页显示
    //$condition= "WHERE 1=1";
    if(!empty($_GPC['keyword'])) {
        $condition .= " WHERE name LIKE '%".$_GPC['keyword']."%'";
    }
    $list= pdo_fetchall('SELECT * FROM '.tablename('amouse_newflats')." $condition  ORDER BY createtime ASC LIMIT ".($pindex -1) * $psize.','.$psize);
    $total= pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('amouse_newflats').$condition);
    $pager= pagination($total, $pindex, $psize);

}elseif($op == 'post') {
    $id= intval($_GPC['id']);
    load()->func('tpl');
    if($id > 0) {
        $item= pdo_fetch('SELECT * FROM '.tablename('amouse_newflats')." WHERE id=:id", array(':id' => $id));
    }

    if(checksubmit('submit')) {
        $title= trim($_GPC['name']) ? trim($_GPC['name']) : message('请填写楼盘名称！');
        $logo= $_GPC['thumb'] ?  $_GPC['thumb']  : message('请上传楼盘图片！');
        $prefix= trim($_GPC['type']);
        $insert= array('name' => $title,
            'thumb' => $logo,
            'price' => $_GPC['price'],
            'type' => $prefix,
            'years'=>$_GPC['years'],
            'wytype'=>$_GPC['wytype'],
            'cqtype'=>$_GPC['cqtype'],
            'jzarea'=>$_GPC['jzarea'],
            'ratio'=>$_GPC['ratio'],
            'floor_area'=>$_GPC['floor_area'],
            'afforestation'=>$_GPC['afforestation'],
            'total'=>$_GPC['total'],
            'door_area'=>$_GPC['door_area'],
            'road_transport'=>$_GPC['road_transport'],
            'investors'=>$_GPC['investors'],
            'developers'=>$_GPC['developers'],
            'property_compay'=>$_GPC['property_compay'],
            'propertypay'=>$_GPC['propertypay'],
            'features'=>$_GPC['features'],
            'sales_addres'=>$_GPC['sales_addres'],
            'checkin_time'=>$_GPC['checkin_time'],
            'sales_status'=>$_GPC['sales_status'],
            'average_price'=>$_GPC['average_price'],
            'discounted_costs'=>$_GPC['discounted_costs'],'payment'=>$_GPC['payment'],
            'business'=>$_GPC['business'],'banks'=>$_GPC['banks'],'trading_area'=>$_GPC['trading_area'],
            'park'=>$_GPC['park'],'hotel'=>$_GPC['hotel'],'supermarket'=>$_GPC['supermarket'],
            'humanities'=>$_GPC['humanities'],'supporting'=>$_GPC['supporting'],'internal'=>$_GPC['internal'],
            'parking_number'=>$_GPC['parking_number'],'base'=>$_GPC['base'],'equally'=>$_GPC['equally'],
            'surrounding'=>$_GPC['surrounding'],'landscape'=>$_GPC['landscape'],'hospitals'=>$_GPC['hospitals'],
            'school'=>$_GPC['school'],'traffic'=>$_GPC['traffic'],
            'construction'=>$_GPC['construction'],
            'design'=>$_GPC['design'],'salecom'=>$_GPC['salecom'],
            'address'=>$_GPC['address'],
            'like'=>0,
            'introduction'=>htmlspecialchars_decode($_GPC['introduction']),
            'weid' => $_W['uniacid'], 'createtime' => TIMESTAMP);

        if(empty($id)) {
            pdo_insert('amouse_newflats', $insert);
        } else {
            pdo_update('amouse_newflats', $insert, array('id' => $id));
        }
        message('更新新楼盘数据成功！', $this->createWebUrl('premises', array('op' => 'display', 'name' => 'amouse_house')), 'success');
    }
}elseif($op == 'del') { //删除
    if(isset($_GPC['delete'])) {
        $ids= implode(",", $_GPC['delete']);
        $sqls= "delete from  ".tablename('amouse_newflats')."  where id in(".$ids.")";
        pdo_query($sqls);
        message('删除成功！', referer(), 'success');
    }
    $id= intval($_GPC['id']);
    $temp= pdo_delete("amouse_newflats", array('id' => $id));
    message('删除数据成功！', $this->createWebUrl('premises', array('op' => 'display', 'name' => 'amouse_house')), 'success');
}


include $this->template('web/flats');

?>