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

if ($op=="add") {
	if ($_GPC['id']) {
		$list = pdo_fetch("select * from ".tablename('dy_dy')." where uniacid=:uniacid and id=:id", $params = array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['id']));
		//var_dump($list);
		$list = array_merge($list,unserialize($list['content1']));

	}
	if (checksubmit()) {
		$data=array(
			 'title'=>$_GPC['title'],
			 'title2'=>$_GPC['title2'],
			 'xmoney'=>$_GPC['xmoney'],
			 'ymoney'=>$_GPC['ymoney'],
			 'chanpin'=>$_GPC['tuijian'],
			'banner'=>$_GPC['banner'],
			'uniacid'=>$_W['uniacid']
			);
		$a=array(
			'width'=>$_GPC['width'],
			'color'=>$_GPC['color'],
			'rs'=>$_GPC['rs'],
			'djs'=>$_GPC['djs'],
			'tel'=>$_GPC['tel'],
			'sms'=>$_GPC['sms'],
			'qq'=>$_GPC['qq'],
			'weixin'=>$_GPC['weixin'],
			'copyright'=>$_GPC['copyright'],
			'zhutu'=>$_GPC['zhutu'],
			'aname'=>$_GPC['aname'],
			'acontent'=>$_GPC['acontent'],
			'bname'=>$_GPC['bname'],
			'bcontent'=>$_GPC['bcontent'],
			'cname'=>$_GPC['cname'],
			'ccontent'=>$_GPC['ccontent'],
			'dname'=>$_GPC['dname'],
			'dcontent'=>$_GPC['dcontent'],
			'ename'=>$_GPC['ename'],
			'econtent'=>$_GPC['econtent'],
			'fname'=>$_GPC['fname'],
			'fcontent'=>$_GPC['fcontent'],
			'pingjia'=>$_GPC['pingjia'],
			'taocanms'=>$_GPC['taocanms'],
			'taocan'=>$_GPC['taocan'],
			'yanse'=>$_GPC['yanse'],
			'chicun'=>$_GPC['chicun'],
			'ischeck'=>$_GPC['ischeck'],
			'istuihuan'=>$_GPC['istuihuan'],
			);
		$data['content1']=serialize($a);
		if ($_GPC['id']) {
			//更新
			$b = pdo_update('dy_dy', $data, array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['id']));
			if($b){
				message('更新成功！',$this->createWebUrl('web/goods'));
			}

		}else{
			//插入
			$b = pdo_insert('dy_dy',$data);
			if($b){
				message('添加成功！',$this->createWebUrl('web/goods'));
			}

		}
	}
	include $this->template('web/goods/add');
}elseif ($op=="color") {
	include $this->template('web/goods/color');
}elseif ($op=="index"){
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
        
        $condition .= ' and ( title like :keyword or title2 like :keyword )';
        $params[':keyword'] = "%{$_GPC['keyword']}%";
    }
    $limit = " limit " . ($pindex - 1) * $psize . ',' . $psize;
    $params['uniacid'] = $_W['uniacid'];
    $list = pdo_fetchall('select * from '.tablename('dy_dy').' where uniacid=:uniacid '.$condition." order by id desc ".$limit,$params);
    $total = pdo_fetchcolumn('select count(*) from '.tablename('dy_dy').' where uniacid=:uniacid '.$condition,$params);
    $pager = pagination($total, $pindex, $psize);
	include $this->template('web/goods/index');
}elseif ($op=="del") {
		if ($_GPC['id']) {
		$id=$_GPC['id'];
		$result2 = pdo_query("DELETE FROM ".tablename('dy_dy')." WHERE id = :id and uniacid=:uniacid", array(':id' => $id,':uniacid'=>$_W['uniacid']));
		if (!empty($result2)) {
				message('删除成功',$this->createWebUrl('web/order'));
		}
	}
}

