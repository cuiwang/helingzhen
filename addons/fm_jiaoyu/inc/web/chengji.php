<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
global $_GPC, $_W;

$weid              = $_W['uniacid'];
$action            = 'chengji';
$this1             = 'no2';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");

$it    = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $sid));
$xueqi = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'semester' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'semester', ':schoolid' => $schoolid));
$km    = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'subject' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'subject', ':schoolid' => $schoolid));
$bj    = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'theclass' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'theclass', ':schoolid' => $schoolid));
$xq    = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'week' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'week', ':schoolid' => $schoolid));
$sd    = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'timeframe' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'timeframe', ':schoolid' => $schoolid));
$qh    = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'score' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'score', ':schoolid' => $schoolid));

$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid =  '{$weid}' AND schoolid ={$schoolid} ORDER BY sid ASC, ssort DESC", array(':weid' => $weid, ':schoolid' => $schoolid), 'sid');

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($operation == 'post'){
    load()->func('tpl');
    $id = intval($_GPC['id']);
    if(!empty($id)){
        $item    = pdo_fetch("SELECT * FROM " . tablename($this->table_score) . " WHERE id = :id", array(':id' => $id));
        $student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where id = :id ", array(':id' => $item['sid']));
        $bj      = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where sid = :sid ", array(':sid' => $item['bj_id']));
        if(empty($item)){
            $this->imessage('抱歉，本条信息不存在在或是已经删除！', '', 'error');
        }
    }
    if(checksubmit('submit')){
        $data = array(
            'weid'     => $weid,
            'schoolid' => $schoolid,
            'sid'      => intval($_GPC['sid']),
            'km_id'    => trim($_GPC['km']),
            'bj_id'    => trim($_GPC['bj']),
            'qh_id'    => trim($_GPC['qh']),
            'xq_id'    => trim($_GPC['xueqi']),
            'my_score' => trim($_GPC['score']),
            'info'     => trim($_GPC['info']),
        );

        if(empty($id)){
            $this->imessage('抱歉，本条信息不存在在或是已经删除！', '', 'error');
        }else{
            pdo_update($this->table_score, $data, array('id' => $id));
        }
        $this->imessage('修改学生成绩成功！', $this->createWebUrl('chengji', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
    }
}elseif($operation == 'display'){

    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 10;
    $condition = '';

    if(!empty($_GPC['xuehao'])){
        $condition .= " AND sid LIKE '%{$_GPC['xuehao']}%' ";
    }

    if(!empty($_GPC['xueqi_id'])){
        $cid       = intval($_GPC['xueqi_id']);
        $condition .= " AND xq_id = '{$cid}'";
    }

    if(!empty($_GPC['bj_id'])){
        $cid       = intval($_GPC['bj_id']);
        $condition .= " AND bj_id = '{$cid}'";
    }

    if(!empty($_GPC['qh_id'])){
        $cid       = intval($_GPC['qh_id']);
        $condition .= " AND qh_id = '{$cid}'";
    }

    if(!empty($_GPC['km_id'])){
        $cid       = intval($_GPC['km_id']);
        $condition .= " AND km_id = '{$cid}'";
    }


    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_score) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
    foreach($list as $key => $row){
        $student                 = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where id = :id ", array(':id' => $row['sid']));
        $list[$key]['s_name']    = $student['s_name'];
        $list[$key]['numberid']  = $student['numberid'];
        $list[$key]['birthdate'] = $student['birthdate'];
        $list[$key]['mobile']    = $student['mobile'];
        $list[$key]['sex']       = $student['sex'];
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_score) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} $condition");

    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'delete'){
    $id = intval($_GPC['id']);
    //     $row = pdo_fetch("SELECT id, thumb FROM " . tablename($this->table_score) . " WHERE id = :id", array(':id' => $id));
    if(empty($id)){
        $this->imessage('抱歉，本条信息不存在在或是已经被删除！');
    }
    pdo_delete($this->table_score, array('id' => $id));
    $this->imessage('删除成功！', referer(), 'success');
}elseif($operation == 'deleteall'){
    $rowcount    = 0;
    $notrowcount = 0;
    foreach($_GPC['idArr'] as $k => $id){
        $id = intval($id);
        if(!empty($id)){
            $goods = pdo_fetch("SELECT * FROM " . tablename($this->table_score) . " WHERE id = :id", array(':id' => $id));
            if(empty($goods)){
                $notrowcount++;
                continue;
            }
            pdo_delete($this->table_score, array('id' => $id, 'weid' => $weid));
            $rowcount++;
        }
    }
    $$this->imessage = "操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!";

    $data ['result'] = true;

    $data ['msg'] = $$this->imessage;

    die (json_encode($data));
}
include $this->template('web/chengji');
?>