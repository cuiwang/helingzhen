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
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT logo,title,is_kb FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");

// ims_wx_school_classify
// SELECT * FROM `ims_wx_school_classify` WHERE sid = 31
$it = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $sid));

/** 学期? */
$xueqi = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'semester' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'semester', ':schoolid' => $schoolid));

/** 科目? */
$km = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'subject' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'subject', ':schoolid' => $schoolid));

/** 班级? */
$bj = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'theclass' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'theclass', ':schoolid' => $schoolid));

/** 星期? */
$xq = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'week' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'week', ':schoolid' => $schoolid));

/** 时段? */
$sd = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'timeframe' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'timeframe', ':schoolid' => $schoolid));

/** xx? */
$qh = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'score' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'score', ':schoolid' => $schoolid));

$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid =  '{$weid}' AND schoolid ={$schoolid} ORDER BY sid ASC, ssort DESC", array(':weid' => $weid, ':schoolid' => $schoolid), 'sid');
if(!empty($category)){
    $children = '';
    foreach($category as $cid => $cate){
        if(!empty($cate['parentid'])){
            $children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
        }
    }
}

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($operation == 'post'){
    load()->func('tpl');
    $id = intval($_GPC['id']);
    if(!empty($id)){
        $item     = pdo_fetch("SELECT * FROM " . tablename($this->table_kcbiao) . " WHERE id = :id ", array(':id' => $id));
        $kc       = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE id = :id ", array(':id' => $item['kcid']));
        $teachers = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " WHERE id = :id ", array(':id' => $item['tid']));
        if(empty($item)){
            $this->imessage('抱歉，本条信息不存在在或是已经删除！', '', 'error');
        }
    }
    if(checksubmit('submit')){
        $data = array(
            'weid'        => $weid,
            'schoolid'    => $schoolid,
            'tid'         => intval($_GPC['tid']),
            'kcid'        => trim($_GPC['kcid']),
            'bj_id'       => trim($_GPC['bj_id']),
            'km_id'       => trim($_GPC['km_id']),
            'sd_id'       => trim($_GPC['sd']),
            'xq_id'       => trim($_GPC['xq']),
            'nub'         => trim($_GPC['nub']),
            'isxiangqing' => trim($_GPC['isxiangqing']),
            'content'     => trim($_GPC['content']),
            'date'        => strtotime($_GPC['date']),
        );

        if(empty($id)){
            $this->imessage('抱歉，本条信息不存在在或是已经删除！', '', 'error');
        }else{
            pdo_update($this->table_kcbiao, $data, array('id' => $id));
        }
        $this->imessage('修改成功！', $this->createWebUrl('kcbiao', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
    }
}elseif($operation == 'display'){

    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 10;
    $condition = '';

    if(!empty($_GPC['name'])){
        $condition .= " AND id LIKE '%{$_GPC['name']}%' ";
    }

    if(!empty($_GPC['bj_id'])){
        $cid       = intval($_GPC['bj_id']);
        $condition .= " AND bj_id = '{$cid}'";
    }

    if(!empty($_GPC['km_id'])){
        $cid       = intval($_GPC['km_id']);
        $condition .= " AND km_id = '{$cid}'";
    }

    if(!empty($_GPC['xq_id'])){
        $cid       = intval($_GPC['xq_id']);
        $condition .= " AND xq_id = '{$cid}'";
    }

    if(!empty($_GPC['sd_id'])){
        $cid       = intval($_GPC['sd_id']);
        $condition .= " AND sd_id = '{$cid}'";
    }
    /** 课程表? */
    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_kcbiao) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
    foreach($list as $key => $row){
        $teacher              = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where id = :id ", array(':id' => $row['tid']));
        $kc                   = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " where id = :id ", array(':id' => $row['kcid']));
        $list[$key]['tname']  = $teacher['tname'];
        $list[$key]['kcname'] = $kc['name'];
        $list[$key]['adrr']   = $kc['adrr'];
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_kcbiao) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} $condition");

    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'delete'){
    $id = intval($_GPC['id']);
    if(empty($id)){
        $this->imessage('抱歉，本条信息不存在在或是已经被删除！');
    }
    pdo_delete($this->table_kcbiao, array('id' => $id));
    $this->imessage('删除成功！', referer(), 'success');
}elseif($operation == 'deleteall'){
    $rowcount    = 0;
    $notrowcount = 0;
    foreach($_GPC['idArr'] as $k => $id){
        $id = intval($id);
        if(!empty($id)){
            $goods = pdo_fetch("SELECT * FROM " . tablename($this->table_kcbiao) . " WHERE id = :id", array(':id' => $id));
            if(empty($goods)){
                $notrowcount++;
                continue;
            }
            pdo_delete($this->table_kcbiao, array('id' => $id, 'weid' => $weid));
            $rowcount++;
        }
    }
    $this->imessage("操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!", '', 0);
}
include $this->template('web/kcbiao');
?>