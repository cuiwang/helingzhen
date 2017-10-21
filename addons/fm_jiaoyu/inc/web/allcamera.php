<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
global $_GPC, $_W;

$weid              = $_W['uniacid'];
$action            = 'allcamera';
$this1             = 'no1';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT logo,title,videoname FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
$operation         = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($operation == 'post'){
    load()->func('tpl');
    $id = intval($_GPC['id']);
    if(!empty($id)){
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_allcamera) . " WHERE id = :id", array(':id' => $id));
        if(empty($item)){
            $this->imessage('抱歉，不存在或是已经删除！', referer(), 'error');
        }
    }
    $banji  = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' And schoolid = '{$schoolid}' And type = 'theclass'  ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'theclass', ':schoolid' => $schoolid));
    $uniarr = explode(',', $item['bj_id']);
    if(checksubmit('submit')){
        if($_GPC['arr']){
            $bj_id = implode(',', $_GPC['arr']);
        }else{
            $bj_id = '';
        }
        $data = array(
            'weid'       => $weid,
            'schoolid'   => $schoolid,
            'name'       => trim($_GPC['name']),
            'videopic'   => trim($_GPC['videopic']),
            'videourl'   => trim($_GPC['videourl']),
            'starttime'  => trim($_GPC['starttime']),
            'endtime'    => trim($_GPC['endtime']),
            'click'      => trim($_GPC['click']),
            'allowpy'    => trim($_GPC['allowpy']),
            'bj_id'      => $bj_id,
            'videotype'  => trim($_GPC['videotype']),
            'conet'      => trim($_GPC['conet']),
            'type'       => 1,
            'createtime' => time(),
            'ssort'      => intval($_GPC['ssort'])
        );
        if(empty($data['name'])){
            $this->imessage('请输入名称!！', referer(), 'error');
        }
        if(empty($data['starttime'])){
            $this->imessage('请设置每日观看开始时间', referer(), 'error');
        }
        if(empty($data['endtime'])){
            $this->imessage('请设置每日观看结束时间', referer(), 'error');
        }
        if(empty($id)){
            pdo_insert($this->table_allcamera, $data);
        }else{
            unset($data['dateline']);
            pdo_update($this->table_allcamera, $data, array('id' => $id));
        }

        $this->imessage('操作成功！', $this->createWebUrl('allcamera', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
    }
}elseif($operation == 'display'){

    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 15;
    $condition = '';
    if(!empty($_GPC['keyword'])){
        $condition .= " AND name LIKE '%{$_GPC['keyword']}%'";
    }

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_allcamera) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} $condition ORDER BY id DESC, ssort DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
    foreach($list as $key => $value){
        $plsl                  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_camerapl) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND carmeraid = '{$value['id']}' And type = 2");
        $dzsl                  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_camerapl) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND carmeraid = '{$value['id']}' And type = 1");
        $list[$key]['plsl']    = $plsl;
        $list[$key]['dianzan'] = $dzsl;
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_allcamera) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} $condition");

    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'delete'){
    $id  = intval($_GPC['id']);
    $row = pdo_fetch("SELECT * FROM " . tablename($this->table_allcamera) . " WHERE id = :id", array(':id' => $id));
    if(empty($row)){
        $this->imessage('抱歉，不存在或是已经被删除！', referer(), 'error');
    }
    pdo_delete($this->table_allcamera, array('id' => $id));
    $this->imessage('删除成功！', referer(), 'success');
}elseif($operation == 'pllist'){
    load()->func('tpl');
    $pindex  = max(1, intval($_GPC['page']));
    $psize   = 20;
    $videoid = intval($_GPC['id']);
    $allpl   = pdo_fetchall("SELECT * FROM " . tablename($this->table_camerapl) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND carmeraid = '{$videoid}' AND type = 2 ORDER BY createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
    foreach($allpl as $key => $row){
        $user = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where id = :id ", array(':id' => $row['userid']));
        if($user['pard'] == 0){
            $teacher             = pdo_fetch("SELECT tname,thumb FROM " . tablename($this->table_teachers) . " where id = :id ", array(':id' => $user['tid']));
            $allpl[$key]['name'] = $teacher['tname'] . "老师";
        }else{
            $studen = pdo_fetch("SELECT s_name,icon FROM " . tablename($this->table_students) . " where id = :id ", array(':id' => $user['sid']));
            if($user['pard'] == 4){
                $allpl[$key]['name'] = $studen['s_name'];
            }else{
                $item                = pdo_fetch("SELECT avatar FROM " . tablename('mc_members') . " where uniacid = :uniacid AND uid=:uid ", array(':uid' => $user['uid'], ':uniacid' => $weid));
                $allpl[$key]['icon'] = $item['avatar'];
                if($user['pard'] == 2){
                    $allpl[$key]['name'] = $studen['s_name'] . "妈妈";
                }
                if($user['pard'] == 3){
                    $allpl[$key]['name'] = $studen['s_name'] . "爸爸";
                }
                if($user['pard'] == 4){
                    $allpl[$key]['name'] = $studen['s_name'] . "家长";
                }
            }
        }
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_camerapl) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND carmeraid = '{$videoid}' AND type = 2 ");
}elseif($operation == 'deleteall'){
    $rowcount    = 0;
    $notrowcount = 0;
    foreach($_GPC['idArr'] as $k => $id){
        $id = intval($id);
        if(!empty($id)){
            $item = pdo_fetch("SELECT * FROM " . tablename($this->table_camerapl) . " WHERE id = :id", array(':id' => $id));
            if(empty($item)){
                $notrowcount++;
                continue;
            }
            pdo_delete($this->table_camerapl, array('id' => $id));
            $rowcount++;
        }
    }
    $message = "操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!";

    $data ['result'] = true;

    $data ['msg'] = $message;

    die (json_encode($data));
}
include $this->template('web/allcamera');
?>