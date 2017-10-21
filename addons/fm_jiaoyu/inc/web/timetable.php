<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
global $_GPC, $_W;
$weid              = $_W['uniacid'];
$action            = 'kcbiao';
$this1             = 'no2';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$operation         = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$schoolid          = intval($_GPC['schoolid']);

$logo = pdo_fetch("SELECT logo,title,is_kb FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");

if($operation == 'post'){

    load()->func('tpl');
    $id     = intval($_GPC['id']);//编辑操作
    $bjlist = pdo_fetchall("SELECT sid,sname,parentid FROM " . tablename($this->table_classify) . " WHERE weid = :weid And schoolid = :schoolid And type = :type", array(':weid' => $weid, ':schoolid' => $schoolid, ':type' => 'theclass'));
    foreach($bjlist as $key => $v){
        if($v['parentid']){
            $njname                 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $v['parentid']));
            $bjlist[$key]['njname'] = $njname['sname'];
        }
    }
    $allsd = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " WHERE weid = :weid And schoolid = :schoolid And type = :type", array(':weid' => $weid, ':schoolid' => $schoolid, ':type' => 'timeframe'));
    $allkm = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " WHERE weid = :weid And schoolid = :schoolid And type = :type", array(':weid' => $weid, ':schoolid' => $schoolid, ':type' => 'subject'));
    if(!empty($id)){
        $item   = pdo_fetch("SELECT * FROM " . tablename($this->table_timetable) . " WHERE id = :id ", array(':id' => $id));
        $monarr = iunserializer($item['monday']);
        $tusarr = iunserializer($item['tuesday']);
        $wedarr = iunserializer($item['wednesday']);
        $thuarr = iunserializer($item['thursday']);
        $friarr = iunserializer($item['friday']);
        $satarr = iunserializer($item['saturday']);
        $sunarr = iunserializer($item['sunday']);
        if(empty($item)){
            $this->imessage('抱歉，本条信息不存在在或是已经删除！', '', 'error');
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
            'bj_id'     => trim($_GPC['bj_id']), //排序
            'ishow'     => intval($_GPC['ishow']), //1:显示,2隐藏,默认1
            'begintime' => $start,
            'endtime'   => $end
        );
        $monday         = array(
            'mon_1_sd'  => trim($_GPC['mon_1_sd']),
            'mon_2_sd'  => trim($_GPC['mon_2_sd']),
            'mon_3_sd'  => trim($_GPC['mon_3_sd']),
            'mon_4_sd'  => trim($_GPC['mon_4_sd']),
            'mon_5_sd'  => trim($_GPC['mon_5_sd']),
            'mon_6_sd'  => trim($_GPC['mon_6_sd']),
            'mon_7_sd'  => trim($_GPC['mon_7_sd']),
            'mon_8_sd'  => trim($_GPC['mon_8_sd']),
            'mon_9_sd'  => trim($_GPC['mon_9_sd']),
            'mon_10_sd' => trim($_GPC['mon_10_sd']),
            'mon_11_sd' => trim($_GPC['mon_11_sd']),
            'mon_12_sd' => trim($_GPC['mon_12_sd']),
            'mon_1_km'  => trim($_GPC['mon_1_km']),
            'mon_2_km'  => trim($_GPC['mon_2_km']),
            'mon_3_km'  => trim($_GPC['mon_3_km']),
            'mon_4_km'  => trim($_GPC['mon_4_km']),
            'mon_5_km'  => trim($_GPC['mon_5_km']),
            'mon_6_km'  => trim($_GPC['mon_6_km']),
            'mon_7_km'  => trim($_GPC['mon_7_km']),
            'mon_8_km'  => trim($_GPC['mon_8_km']),
            'mon_9_km'  => trim($_GPC['mon_9_km']),
            'mon_10_km' => trim($_GPC['mon_10_km']),
            'mon_11_km' => trim($_GPC['mon_11_km']),
            'mon_12_km' => trim($_GPC['mon_12_km'])
        );
        $data['monday'] = iserializer($monday);

        $tuesday         = array(
            'tus_1_sd'  => trim($_GPC['tus_1_sd']),
            'tus_2_sd'  => trim($_GPC['tus_2_sd']),
            'tus_3_sd'  => trim($_GPC['tus_3_sd']),
            'tus_4_sd'  => trim($_GPC['tus_4_sd']),
            'tus_5_sd'  => trim($_GPC['tus_5_sd']),
            'tus_6_sd'  => trim($_GPC['tus_6_sd']),
            'tus_7_sd'  => trim($_GPC['tus_7_sd']),
            'tus_8_sd'  => trim($_GPC['tus_8_sd']),
            'tus_9_sd'  => trim($_GPC['tus_9_sd']),
            'tus_10_sd' => trim($_GPC['tus_10_sd']),
            'tus_11_sd' => trim($_GPC['tus_11_sd']),
            'tus_12_sd' => trim($_GPC['tus_12_sd']),
            'tus_1_km'  => trim($_GPC['tus_1_km']),
            'tus_2_km'  => trim($_GPC['tus_2_km']),
            'tus_3_km'  => trim($_GPC['tus_3_km']),
            'tus_4_km'  => trim($_GPC['tus_4_km']),
            'tus_5_km'  => trim($_GPC['tus_5_km']),
            'tus_6_km'  => trim($_GPC['tus_6_km']),
            'tus_7_km'  => trim($_GPC['tus_7_km']),
            'tus_8_km'  => trim($_GPC['tus_8_km']),
            'tus_9_km'  => trim($_GPC['tus_9_km']),
            'tus_10_km' => trim($_GPC['tus_10_km']),
            'tus_11_km' => trim($_GPC['tus_11_km']),
            'tus_12_km' => trim($_GPC['tus_12_km'])
        );
        $data['tuesday'] = iserializer($tuesday);

        $wednesday         = array(
            'wed_1_sd'  => trim($_GPC['wed_1_sd']),
            'wed_2_sd'  => trim($_GPC['wed_2_sd']),
            'wed_3_sd'  => trim($_GPC['wed_3_sd']),
            'wed_4_sd'  => trim($_GPC['wed_4_sd']),
            'wed_5_sd'  => trim($_GPC['wed_5_sd']),
            'wed_6_sd'  => trim($_GPC['wed_6_sd']),
            'wed_7_sd'  => trim($_GPC['wed_7_sd']),
            'wed_8_sd'  => trim($_GPC['wed_8_sd']),
            'wed_9_sd'  => trim($_GPC['wed_9_sd']),
            'wed_10_sd' => trim($_GPC['wed_10_sd']),
            'wed_11_sd' => trim($_GPC['wed_11_sd']),
            'wed_12_sd' => trim($_GPC['wed_12_sd']),
            'wed_1_km'  => trim($_GPC['wed_1_km']),
            'wed_2_km'  => trim($_GPC['wed_2_km']),
            'wed_3_km'  => trim($_GPC['wed_3_km']),
            'wed_4_km'  => trim($_GPC['wed_4_km']),
            'wed_5_km'  => trim($_GPC['wed_5_km']),
            'wed_6_km'  => trim($_GPC['wed_6_km']),
            'wed_7_km'  => trim($_GPC['wed_7_km']),
            'wed_8_km'  => trim($_GPC['wed_8_km']),
            'wed_9_km'  => trim($_GPC['wed_9_km']),
            'wed_10_km' => trim($_GPC['wed_10_km']),
            'wed_11_km' => trim($_GPC['wed_11_km']),
            'wed_12_km' => trim($_GPC['wed_12_km'])
        );
        $data['wednesday'] = iserializer($wednesday);

        $thursday         = array(
            'thu_1_sd'  => trim($_GPC['thu_1_sd']),
            'thu_2_sd'  => trim($_GPC['thu_2_sd']),
            'thu_3_sd'  => trim($_GPC['thu_3_sd']),
            'thu_4_sd'  => trim($_GPC['thu_4_sd']),
            'thu_5_sd'  => trim($_GPC['thu_5_sd']),
            'thu_6_sd'  => trim($_GPC['thu_6_sd']),
            'thu_7_sd'  => trim($_GPC['thu_7_sd']),
            'thu_8_sd'  => trim($_GPC['thu_8_sd']),
            'thu_9_sd'  => trim($_GPC['thu_9_sd']),
            'thu_10_sd' => trim($_GPC['thu_10_sd']),
            'thu_11_sd' => trim($_GPC['thu_11_sd']),
            'thu_12_sd' => trim($_GPC['thu_12_sd']),
            'thu_1_km'  => trim($_GPC['thu_1_km']),
            'thu_2_km'  => trim($_GPC['thu_2_km']),
            'thu_3_km'  => trim($_GPC['thu_3_km']),
            'thu_4_km'  => trim($_GPC['thu_4_km']),
            'thu_5_km'  => trim($_GPC['thu_5_km']),
            'thu_6_km'  => trim($_GPC['thu_6_km']),
            'thu_7_km'  => trim($_GPC['thu_7_km']),
            'thu_8_km'  => trim($_GPC['thu_8_km']),
            'thu_9_km'  => trim($_GPC['thu_9_km']),
            'thu_10_km' => trim($_GPC['thu_10_km']),
            'thu_11_km' => trim($_GPC['thu_11_km']),
            'thu_12_km' => trim($_GPC['thu_12_km']),
        );
        $data['thursday'] = iserializer($thursday);

        $friday         = array(
            'fri_1_sd'  => trim($_GPC['fri_1_sd']),
            'fri_2_sd'  => trim($_GPC['fri_2_sd']),
            'fri_3_sd'  => trim($_GPC['fri_3_sd']),
            'fri_4_sd'  => trim($_GPC['fri_4_sd']),
            'fri_5_sd'  => trim($_GPC['fri_5_sd']),
            'fri_6_sd'  => trim($_GPC['fri_6_sd']),
            'fri_7_sd'  => trim($_GPC['fri_7_sd']),
            'fri_8_sd'  => trim($_GPC['fri_8_sd']),
            'fri_9_sd'  => trim($_GPC['fri_9_sd']),
            'fri_10_sd' => trim($_GPC['fri_10_sd']),
            'fri_11_sd' => trim($_GPC['fri_11_sd']),
            'fri_12_sd' => trim($_GPC['fri_12_sd']),
            'fri_1_km'  => trim($_GPC['fri_1_km']),
            'fri_2_km'  => trim($_GPC['fri_2_km']),
            'fri_3_km'  => trim($_GPC['fri_3_km']),
            'fri_4_km'  => trim($_GPC['fri_4_km']),
            'fri_5_km'  => trim($_GPC['fri_5_km']),
            'fri_6_km'  => trim($_GPC['fri_6_km']),
            'fri_7_km'  => trim($_GPC['fri_7_km']),
            'fri_8_km'  => trim($_GPC['fri_8_km']),
            'fri_9_km'  => trim($_GPC['fri_9_km']),
            'fri_10_km' => trim($_GPC['fri_10_km']),
            'fri_11_km' => trim($_GPC['fri_11_km']),
            'fri_12_km' => trim($_GPC['fri_12_km'])
        );
        $data['friday'] = iserializer($friday);

        $saturday         = array(
            'sat_1_sd'  => trim($_GPC['sat_1_sd']),
            'sat_2_sd'  => trim($_GPC['sat_2_sd']),
            'sat_3_sd'  => trim($_GPC['sat_3_sd']),
            'sat_4_sd'  => trim($_GPC['sat_4_sd']),
            'sat_5_sd'  => trim($_GPC['sat_5_sd']),
            'sat_6_sd'  => trim($_GPC['sat_6_sd']),
            'sat_7_sd'  => trim($_GPC['sat_7_sd']),
            'sat_8_sd'  => trim($_GPC['sat_8_sd']),
            'sat_9_sd'  => trim($_GPC['sat_9_sd']),
            'sat_10_sd' => trim($_GPC['sat_10_sd']),
            'sat_11_sd' => trim($_GPC['sat_11_sd']),
            'sat_12_sd' => trim($_GPC['sat_12_sd']),
            'sat_1_km'  => trim($_GPC['sat_1_km']),
            'sat_2_km'  => trim($_GPC['sat_2_km']),
            'sat_3_km'  => trim($_GPC['sat_3_km']),
            'sat_4_km'  => trim($_GPC['sat_4_km']),
            'sat_5_km'  => trim($_GPC['sat_5_km']),
            'sat_6_km'  => trim($_GPC['sat_6_km']),
            'sat_7_km'  => trim($_GPC['sat_7_km']),
            'sat_8_km'  => trim($_GPC['sat_8_km']),
            'sat_9_km'  => trim($_GPC['sat_9_km']),
            'sat_10_km' => trim($_GPC['sat_10_km']),
            'sat_11_km' => trim($_GPC['sat_11_km']),
            'sat_12_km' => trim($_GPC['sat_12_km']),
        );
        $data['saturday'] = iserializer($saturday);


        $sunday = array(
            'sun_1_sd'  => trim($_GPC['sun_1_sd']),
            'sun_2_sd'  => trim($_GPC['sun_2_sd']),
            'sun_3_sd'  => trim($_GPC['sun_3_sd']),
            'sun_4_sd'  => trim($_GPC['sun_4_sd']),
            'sun_5_sd'  => trim($_GPC['sun_5_sd']),
            'sun_6_sd'  => trim($_GPC['sun_6_sd']),
            'sun_7_sd'  => trim($_GPC['sun_7_sd']),
            'sun_8_sd'  => trim($_GPC['sun_8_sd']),
            'sun_9_sd'  => trim($_GPC['sun_9_sd']),
            'sun_10_sd' => trim($_GPC['sun_10_sd']),
            'sun_11_sd' => trim($_GPC['sun_11_sd']),
            'sun_12_sd' => trim($_GPC['sun_12_sd']),
            'sun_1_km'  => trim($_GPC['sun_1_km']),
            'sun_2_km'  => trim($_GPC['sun_2_km']),
            'sun_3_km'  => trim($_GPC['sun_3_km']),
            'sun_4_km'  => trim($_GPC['sun_4_km']),
            'sun_5_km'  => trim($_GPC['sun_5_km']),
            'sun_6_km'  => trim($_GPC['sun_6_km']),
            'sun_7_km'  => trim($_GPC['sun_7_km']),
            'sun_8_km'  => trim($_GPC['sun_8_km']),
            'sun_9_km'  => trim($_GPC['sun_9_km']),
            'sun_10_km' => trim($_GPC['sun_10_km']),
            'sun_11_km' => trim($_GPC['sun_11_km']),
            'sun_12_km' => trim($_GPC['sun_12_km']),
        );


        $data['sunday'] = iserializer($sunday);
        if(!$_GPC['bj_id']){
            $this->imessage('抱歉！请选择班级');
        }
        if(!$start || !$end){
            $this->imessage('抱歉！请设置开始时间和结束时间');
        }
        if($start >= $end){
            $this->imessage('抱歉！结束时间必须大于开始时间');
        }
        $condition = " AND begintime <= '{$start}' AND endtime >= '{$end}'";
        $check     = pdo_fetch("SELECT * FROM " . tablename($this->table_timetable) . " WHERE schoolid = :schoolid And bj_id = :bj_id $condition ", array(':schoolid' => $schoolid, ':bj_id' => $_GPC['bj_id']));

        if($check && !$id){
            $this->imessage('抱歉！本班在本时间范围内已经添加了课表，请勿重复');
        }
        if(empty($id)){
            pdo_insert($this->table_timetable, $data);

        }else{
            pdo_update($this->table_timetable, $data, array('id' => $id));

        }

        $this->imessage('操作成功！', $this->createWebUrl('timetable', array('op' => 'display', 'schoolid' => $schoolid)), 'success');

    }

}elseif($operation == 'display'){//

    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 10;
    $condition = '';
    if(!empty($_GPC['title'])){
        $condition .= " AND id LIKE '%{$_GPC['title']}%' ";
    }

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_timetable) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} $condition ORDER BY sort DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
    foreach($list as $key => $r){
        $bjname               = pdo_fetch("SELECT sname,parentid FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $r['bj_id']));
        $njname               = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $bjname['parentid']));
        $list[$key]['bjname'] = $bjname['sname'];
        $list[$key]['njname'] = $njname['sname'];
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_timetable) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} $condition");

    $pager = pagination($total, $pindex, $psize);


}elseif($operation == 'delete'){

    $id = intval($_GPC['id']);
    if(empty($id)){

        $this->imessage('抱歉，本条信息不存在在或是已经被删除！');
    }

    pdo_delete($this->table_timetable, array('id' => $id));

    $this->imessage('删除成功！', $this->createWebUrl('timetable', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
    #$this->imessage('删除成功！', 'referer', 'success');

}elseif($operation == 'deleteall'){

    $rowcount    = 0;
    $notrowcount = 0;
    foreach($_GPC['idArr'] as $k => $id){

        $id = intval($id);
        if(!empty($id)){

            $goods = pdo_fetch("SELECT * FROM " . tablename($this->table_timetable) . " WHERE id = :id", array(':id' => $id));

            if(empty($goods)){
                $notrowcount++;
                continue;
            }
            pdo_delete($this->table_timetable, array('id' => $id, 'weid' => $weid));
            $rowcount++;

        }
    }

    $message = "操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!";

    $data ['result'] = true;

    $data ['msg'] = $message;

    die (json_encode($data));
}elseif($operation == 'change'){
    $id    = intval($_GPC['id']);
    $ishow = intval($_GPC['ishow']);

    $data = array('ishow' => $ishow);

    pdo_update($this->table_timetable, $data, array('id' => $id));
}


include $this->template('web/timetable');
?>