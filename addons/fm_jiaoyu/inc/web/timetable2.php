<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
global $_GPC, $_W;
$weid              = $_W['uniacid'];
$action            = 'timetable';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$operation         = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$schoolid          = intval($_GPC['schoolid']);

$logo = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");


if($operation == 'post'){
    load()->func('tpl');
    $id = intval($_GPC['id']);

    if(empty($id)){// add
        // 7天遍历
        $shangwu_xiawu = range(1, 2);
        $weeks         = range(1, 7);
        $shangwus      = range(1, 4);
        $xiawus        = range(1, 3);
        $num           = range(1, 7);

        $act = $_GPC['act'];//add
        // 基础设置
        $items = pdo_fetch("SELECT * FROM " . tablename($this->table_courseTable) . " WHERE id = :id ", array(':id' => $id));
        // 班级
        $banji = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'semester' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'semester', ':schoolid' => $schoolid));
        // 时段
        $sd = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'timeframe'", array(':weid' => $weid, ':type' => 'subject', ':schoolid' => $schoolid));
        // 科目
        $subject = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'subject'", array(':weid' => $weid, ':type' => 'subject', ':schoolid' => $schoolid));

    }else{// edit

        // 7天遍历
        $shangwu_xiawu = range(1, 2);
        $weeks         = range(1, 7);
        $shangwus      = range(1, 4);
        $xiawus        = range(1, 3);
        $num           = range(1, 7);

        $act = $_GPC['act'];//edit
        // 基础设置&上午&下午
        $items = pdo_fetch("SELECT * FROM " . tablename($this->table_courseTable) . " WHERE id = :id ", array(':id' => $id));
        // 班级
        $banji = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'semester' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'semester', ':schoolid' => $schoolid));
        // 科目
        $subject = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'subject'", array(':weid' => $weid, ':type' => 'subject', ':schoolid' => $schoolid));
        // 时段
        $sd = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'timeframe'", array(':weid' => $weid, ':type' => 'subject', ':schoolid' => $schoolid));

        $infos = iunserializer($items['timetable']);

        if(empty($items)){
            message('抱歉，本条信息不存在在或是已经删除！', '', 'error');
        }

    }

    // 点击提交之后
    if(checksubmit('submit')){

        $timetables = $_GPC['time'];
        //fucki($timetables);
        // 总节数
        $totalLength = count($timetables);
        // 2个单元为1节课
        $subjects = $totalLength / 2;
        // 每天节数，7这个值不确定
        $rowLength = $subjects / 7;
        //dump($rowLength);

        $infos = array_chunk($timetables, $rowLength * 2, true);
        for($i = 0; $i < count($infos); $i++){
            $timetable['monday']    = $infos[$i];
            $timetable['tuesday']   = $infos[$i];
            $timetable['wednesday'] = $infos[$i];
            $timetable['thursday']  = $infos[$i];
            $timetable['friday']    = $infos[$i];
            $timetable['saturday']  = $infos[$i];
            $timetable['sunday']    = $infos[$i];
        }

        $data = array(
            'weid'      => $weid,
            'schoolid'  => $schoolid,
            'title'     => trim($_GPC['title']),//课表名称
            'parentid'  => trim($_GPC['parentid']),//年级名称
            'ishow'     => intval($_GPC['ishow']), //1:显示,2隐藏,默认1
            'timetable' => iserializer($timetable)
        );

        if(empty($id)){
            pdo_insert($this->table_courseTable, $data);
        }else{
            pdo_update($this->table_courseTable, $data, array('id' => $id));
        }
        message('操作成功！', $this->createWebUrl('timetable', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
    }

}elseif($operation == 'display'){// 展示数据

    // 当前页
    $currentPage = max(1, intval($_GPC['page']));// 假如2
    // 每页显示数量
    $pageSize = 6;
    // 总记录数
    $totalRow = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_courseTable));
    // 课表信息展示                                                                                                                  起始值，每页显示数量
    $courseTableInfos = pdo_fetchall("SELECT * FROM " . tablename($this->table_courseTable) . " ORDER BY id DESC LIMIT " . ($currentPage - 1) * $pageSize . ',' . $pageSize);

    // dump($courseTableInfos);
    // dump(count($courseTableInfos));
    // die();
    // fucki($a=(iunserializer($courseTableInfos[0]['timetable'])));

    /*
    foreach ($a as $k=>$v) {
        dump($k . '=>' . $v['week_7_1_time']);
        dump($k . '=>' . $v['week_7_1_subject']);
    }
    */

    $pager = pagination($totalRow, $currentPage, $pageSize);

}elseif($operation == 'delete'){

    $id = intval($_GPC['id']);
    if(empty($id)){
        message('抱歉，本条信息不存在在或是已经被删除！');
    }
    pdo_delete($this->table_courseTable, array('id' => $id));
    message('删除成功！', referer(), 'success');

}elseif($operation == 'deleteall'){

    $rowcount    = 0;
    $notrowcount = 0;
    foreach($_GPC['idArr'] as $k => $id){
        $id = intval($id);
        if(!empty($id)){
            $goods = pdo_fetch("SELECT * FROM " . tablename($this->table_courseTable) . " WHERE id = :id", array(':id' => $id));
            if(empty($goods)){
                $notrowcount++;
                continue;
            }
            pdo_delete($this->table_courseTable, array('id' => $id, 'weid' => $weid));
            $rowcount++;
        }
    }
    $this->message("操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!", '', 0);
}
include $this->template('web/timetable');
?>