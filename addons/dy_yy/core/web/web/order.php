<?php
 
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
/***********侧栏*************/
$frames = array();
$frames['store']['title'] = '<i class=""></i>&nbsp;&nbsp; 产品管理';
$frames['store']['items'] = array();
$frames['store']['items']['add']['url'] = $this->createWebUrl('web/goods',array('op'=>"add"));
$frames['store']['items']['add']['title'] = '增加商品';
$frames['store']['items']['add']['actions'] = array('op','add');
$frames['store']['items']['add']['active'] = '';
$frames['store']['items']['index']['url'] = $this->createWebUrl('web/goods',array('op'=>"index"));
$frames['store']['items']['index']['title'] = '商品列表';
$frames['store']['items']['index']['actions'] = array('op','index');
$frames['store']['items']['index']['active'] = '';
$orders = array('等待确认','确认假单','用户取消','催取发货','重复订单','等待发货','已经发货','已经签收','已经拒签','退货处理');
$pay = array('货到付款');
$toudi = array('未安排','顺丰快递','宅急送','EMS','申通快递','圆通快递','申通快递');
if ($op=="index") {
	$pindex    = max(1, intval($_GPC["page"]));
    $psize     = 20;
    $condition='';
    /***********时间**********/
    if (empty($starttime) || empty($endtime)) {
        $starttime = strtotime('-1 month');
        $endtime   = time();
    }
    if (!empty($_GPC["time"])) {
        $starttime = strtotime($_GPC["time"]["start"]);
        $endtime   = strtotime($_GPC["time"]["end"]);
		
        if ($_GPC["searchtime"] == "1") {
            $condition .= " AND createtime >= :starttime AND createtime <= :endtime ";
            $params[":starttime"] = $starttime;
            $params[":endtime"]   = $endtime;
        }
    }
    /***********关键词******************/
    if (!empty($_GPC['keyword'])) {
        $_GPC['keyword'] = trim($_GPC['keyword']);
        
        $condition .= ' and ( tid like :keyword or chanpin like :keyword or tid like :keyword)';
        $params[':keyword'] = "%{$_GPC['keyword']}%";
    }
    $limit = " limit " . ($pindex - 1) * $psize . ',' . $psize;
    $params['uniacid'] = $_W['uniacid'];
    $list = pdo_fetchall('select * from '.tablename('dy_order').' where uniacid=:uniacid '.$condition." order by id desc ".$limit,$params);
    $total = pdo_fetchcolumn('select count(*) from '.tablename('dy_order').' where uniacid=:uniacid '.$condition,$params);
    $pager = pagination($total, $pindex, $psize);
    //var_dump($list);
	include $this->template('web/order/index');
}elseif ($op=="del") {
	if ($_GPC['id']) {
		$id=$_GPC['id'];
		$result2 = pdo_query("DELETE FROM ".tablename('dy_order')." WHERE id = :id and uniacid=:uniacid", array(':id' => $id,':uniacid'=>$_W['uniacid']));
		if (!empty($result2)) {
				message('删除成功',$this->createWebUrl('web/order'));
		}
	}
}elseif ($op=="detail") {

    if ($_GPC['id']) {
        $list = pdo_fetch('select * from '.tablename('dy_order').' where uniacid=:uniacid and id=:id',array(':uniacid'=>$_W['uniacid'],':id'=>$_GPC['id']));
        if ($_POST) {

            $data=array(
                    'chanpin'=>$_GPC['chanpin'],
                    'num'=>$_GPC['num'],
                    'money'=>$_GPC['money'],
                    'tel'=>$_GPC['tel'],
                    'name'=>$_GPC['name'],
                    'danhao'=>$_GPC['danhao'],
                    'sheng'=>$_GPC['sheng'],
                    'shi'=>$_GPC['shi'],
                    'qu'=>$_GPC['qu'],
                    'address'=>$_GPC['address'],
                    'kuaidi'=>$_GPC['kuaidi'],
                    'pay'=>$_GPC['pay'],
                    'stat'=>$_GPC['stat'],
                    'kehubeizhu'=>$_GPC['kehubeizhu'],
                    'adminbeizhu'=>$_GPC['adminbeizhu']
                );
            $b = pdo_update('dy_order', $data, array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['id']));
            if($b){
                message('更新成功！',$this->createWebUrl('web/order'));
            }else{
                message('更新失败！');
            }
        }
    }

    include $this->template('web/order/detail');
}