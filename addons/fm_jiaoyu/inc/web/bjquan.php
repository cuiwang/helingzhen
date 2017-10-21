<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
global $_GPC, $_W;

$weid              = $_W['uniacid'];
$action            = 'bjquan';
$this1             = 'no3';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
$school            = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where id = :id ORDER BY ssort DESC", array(':id' => $schoolid));

$bj = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'theclass' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'theclass', ':schoolid' => $schoolid));

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($operation == 'post'){
    $id = intval($_GPC['id']);
    if(!empty($id)){
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_bjq) . " WHERE id = :id ", array(':id' => $id));
        if(empty($item)){
            $this->imessage('抱歉，本条信息不存在在或是已经删除！', '', 'error');
        }
    }

    $bj1   = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = :sid ", array(':sid' => $item['bj_id1']));
    $bj2   = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = :sid ", array(':sid' => $item['bj_id2']));
    $bj3   = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = :sid ", array(':sid' => $item['bj_id3']));
    $list1 = pdo_fetchall("SELECT * FROM " . tablename($this->table_media) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And sherid = '{$id}' ORDER BY id DESC");
    $list2 = pdo_fetchall("SELECT * FROM " . tablename($this->table_bjq) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And type = 1 And sherid = '{$id}' ORDER BY createtime DESC");
}elseif($operation == 'display'){

    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 10;
    $condition = '';

    if(!empty($_GPC['bj_id'])){
        $bj_id     = intval($_GPC['bj_id']);
        $condition .= " And (bj_id1 = '{$bj_id}' or bj_id2 = '{$bj_id}' or bj_id3 = '{$bj_id}')";
    }

    if(!empty($_GPC['isopen'])){

        if($_GPC['isopen'] == -1){
            $isopen = 0;
        }else{
            $isopen = 1;
        }
        $condition .= " And isopen = " . $isopen;
    }


    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_bjq) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And type = 0 $condition ORDER BY createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

    foreach($list as $index => $row){
        if(!empty($row['sherid'])){
            $list[$index]['picurl'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_media) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND sherid =:sherid  ORDER BY id ASC", array(':sherid' => $row['sherid']));
        }
        $member                 = pdo_fetch("SELECT * FROM " . tablename('mc_members') . " where uniacid = :uniacid And uid = :uid ORDER BY uid ASC", array(':uniacid' => $_W ['uniacid'], ':uid' => $row['uid']));
        $list[$index]['avatar'] = $member['avatar'];
    }

    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_bjq) . " WHERE weid = '{$weid}' AND schoolid = {$schoolid} And type = 0 $condition");

    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'delete'){
    $id = intval($_GPC['id']);
    if(empty($id)){
        $this->imessage('抱歉，本条信息不存在在或是已经被删除！');
    }
    pdo_delete($this->table_bjq, array('id' => $id));
    pdo_delete($this->table_media, array('sherid' => $id));
    pdo_delete($this->table_dianzan, array('sherid' => $id));
    $this->imessage('删除成功！', referer(), 'success');
}elseif($operation == 'shenhe'){
    $id  = intval($_GPC['id']);
    $bjq = pdo_fetch("SELECT * FROM " . tablename($this->table_bjq) . " WHERE id = :id ", array(':id' => $id));
    if(empty($id)){
        $this->imessage('抱歉，本条信息不存在在或是已经被删除！');
    }
    $temp = array(
        'isopen' => 0
    );
    pdo_update($this->table_bjq, $temp, array('id' => $id));
    $this->sendMobileBjqshjg($schoolid, $weid, $bjq['shername'], $bjq['openid']);
    $this->imessage('审核成功！', referer(), 'success');
}
include $this->template('web/bjquan');
?>