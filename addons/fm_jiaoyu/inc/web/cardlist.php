<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
global $_GPC, $_W;

$weid              = $_W['uniacid'];
$action            = 'cardlist';
$this1             = 'no5';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT logo,title,spic FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
$bj                = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'theclass' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'theclass', ':schoolid' => $schoolid));

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($operation == 'post'){
    load()->func('tpl');
    $id = intval($_GPC['id']);
    if(!empty($id)){
        $item    = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE id = :id", array(':id' => $id));
        $student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where id = :id ", array(':id' => $item['sid']));
        $teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where id = :id ", array(':id' => $item['tid']));
        $bj      = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where sid = :sid ", array(':sid' => $item['bj_id']));
        if(empty($item)){
            $this->imessage('抱歉，本条信息不存在在或是已经删除！', '', 'error');
        }
    }
    if(checksubmit('submit')){
        $data = array(
            'severend' => strtotime($_GPC['severend'])
        );
        if(empty($id)){
            $this->imessage('抱歉，本条信息不存在在或是已经删除！', '', 'error');
        }else{
            pdo_update($this->table_idcard, $data, array('id' => $id));
        }
        $this->imessage('修改成功！', $this->createWebUrl('cardlist', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
    }
}elseif($operation == 'display'){

    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 30;
    $condition = '';

    if(!empty($_GPC['idcard'])){
        $cid       = $_GPC['idcard'];
        $condition .= " AND idcard LIKE '%{$cid}%'";
    }

    if(!empty($_GPC['bj_id'])){
        $bj_id     = intval($_GPC['bj_id']);
        $condition .= " AND bj_id = '{$bj_id}'";
    }

    if($_GPC['type'] == 1){
        $condition .= " AND sid >= 1";
    }
    if($_GPC['type'] == 2){
        $condition .= " AND tid >= 1";
    }
    if($_GPC['type'] == 3){
        $condition .= " AND sid < 1 AND tid < 1";
    }
    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_idcard) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} $condition ORDER BY sid DESC, tid DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
    foreach($list as $key => $row){
        $student              = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where id = :id ", array(':id' => $row['sid']));
        $teacher              = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where id = :id ", array(':id' => $row['tid']));
        $bjlist               = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where sid = :sid ", array(':sid' => $row['bj_id']));
        $jxlog                = pdo_fetchall("SELECT * FROM " . tablename($this->table_checklog) . " where cardid = :cardid", array(':cardid' => $row['idcard']));
        $list[$key]['s_name'] = $student['s_name'];
        $list[$key]['tname']  = $teacher['tname'];
        $list[$key]['bjname'] = $bjlist['sname'];
        $list[$key]['num']    = count($jxlog);
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_idcard) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} $condition");

    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'jiebang'){
    $id  = intval($_GPC['id']);
    $row = pdo_fetch("SELECT sid FROM " . tablename($this->table_idcard) . " WHERE id = :id", array(':id' => $id));
    if(empty($row)){
        $this->imessage('抱歉，本卡不存在或是已经被删除！');
    }
    $temp = array(
        'sid'        => 0,
        'tid'        => 0,
        'pard'       => 0,
        'bj_id'      => 0,
        'usertype'   => 3,
        'createtime' => '',
        'pname'      => '',
        'severend'   => '',
        'spic'       => '',
        'tpic'       => '',
    );
    pdo_delete($this->table_checklog, array('sid' => $row['sid']));
    pdo_update($this->table_idcard, $temp, array('id' => $id));
    $this->imessage('解绑成功！', referer(), 'success');
}elseif($operation == 'delete'){
    $id = intval($_GPC['id']);
    //     $row = pdo_fetch("SELECT id, thumb FROM " . tablename($this->table_score) . " WHERE id = :id", array(':id' => $id));
    if(empty($id)){
        $this->imessage('抱歉，本条信息不存在在或是已经被删除！');
    }
    pdo_delete($this->table_idcard, array('id' => $id));
    $this->imessage('删除成功！', referer(), 'success');
}elseif($operation == 'deleteall'){
    $rowcount    = 0;
    $notrowcount = 0;
    foreach($_GPC['idArr'] as $k => $id){
        $id = intval($id);
        if(!empty($id)){
            $goods = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE id = :id", array(':id' => $id));
            if(empty($goods)){
                $notrowcount++;
                continue;
            }
            pdo_delete($this->table_idcard, array('id' => $id, 'weid' => $weid));
            $rowcount++;
        }
    }
    $this->imessage("操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!", '', 0);
}
include $this->template('web/cardlist');
?>