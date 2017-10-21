<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */

global $_GPC, $_W;
$weid              = $_W['uniacid'];
$action            = 'shoucelist';
$this1             = 'no2';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT logo,title,shoucename FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
$operation         = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
// $xueqi = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid = {$schoolid} And type = 'semester' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'semester', ':schoolid' => $schoolid));
if($operation == 'display'){
    $allxq     = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid = {$schoolid} And type = 'score' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'semester', ':schoolid' => $schoolid));
    $allbj     = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid = {$schoolid} And type = 'theclass' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'semester', ':schoolid' => $schoolid));
    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 10;
    $condition = '';
    if(!empty($_GPC['keyword'])){
        $condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
    }
    if(!empty($_GPC['bj_id'])){
        $bj_id     = $_GPC['bj_id'];
        $condition .= " AND bj_id = '{$bj_id}' ";
    }
    if(!empty($_GPC['xq_id'])){
        $xq_id     = $_GPC['xq_id'];
        $condition .= " AND xq_id = '{$xq_id}' ";
    }
    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_sc) . " WHERE weid = '{$weid}' And schoolid = {$schoolid} ORDER BY createtime LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
    foreach($list as $key => $row){
        $teacher              = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " WHERE id = :id", array(':id' => $row['tid']));
        $xueqi                = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $row['xq_id']));
        $banji                = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $row['bj_id']));
        $guize                = pdo_fetch("SELECT title,icon FROM " . tablename($this->table_scset) . " WHERE id = :id", array(':id' => $row['setid']));
        $list[$key]['tname']  = $teacher['tname'];
        $list[$key]['xueqi']  = $xueqi['sname'];
        $list[$key]['banji']  = $banji['sname'];
        $list[$key]['gzname'] = $guize['title'];
        $list[$key]['gzicon'] = $guize['icon'];
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_sc) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} $condition");
    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'delete'){
    $id   = intval($_GPC['id']);
    $list = pdo_fetch("SELECT id FROM " . tablename($this->table_sc) . " WHERE id = '$id'");
    if(empty($list)){
        $this->imessage('抱歉，信息不存在或是已经被删除！', $this->createWebUrl('shoucelist', array('op' => 'display', 'schoolid' => $schoolid)), 'error');
    }
    pdo_delete($this->table_sc, array('id' => $id));
    pdo_delete($this->table_scforxs, array('id' => $id));
    $this->imessage('删除成功！', $this->createWebUrl('shoucelist', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
}
include $this->template('web/shoucelist');
?>