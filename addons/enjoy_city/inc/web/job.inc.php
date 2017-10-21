<?php
global $_W, $_GPC;
$id = $_GPC['id'];
$uniacid = $_W['uniacid'];
load()->func("tpl");
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$fid = intval($_GPC['fid']);
if ($fid > 0) {
    $where2 = "and a.fid=" . $fid;
}
if ($op=='delete') {
    $id = $_GPC['id'];
    $res = pdo_delete('enjoy_city_job', array(
        'id' => $id,
        'uniacid' => $uniacid
    ));
    if ($res > 0) {
        message('职位删除成功！', $this->createWebUrl('job', array()), 'success');
    }
} else if ($op=='ischeck') {
    $ischeck = $_GPC['ischeck'];
    $uid = $_GPC['uid'];
    if ($ischeck==0) {
        $ischeck = 1;
    } else {
        $ischeck = 0;
    }
    pdo_update("enjoy_city_job", array(
        "ischeck" => $ischeck
    ), array(
        'uniacid' => $uniacid,
        'id' => $id
    ));
    message("操作成功", $this->createWebUrl('job'), 'success');
} else if ($op=='display') {
    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;
    if ($_GPC['ptitle']) {
        $where = "and a.ptitle LIKE '%" . $_GPC['ptitle'] . "%'";
    } else {
        $where = "";
    }
    if ($_GPC['unusual']) {
        $where1 = "and a.ischeck=0";
    } else {
        $where1 = "";
    }
    if (checksubmit("submit")) {
        if (!empty($_GPC['hot'])) {
            foreach ($_GPC['hot'] as $id => $hot) {
                pdo_update('enjoy_city_job', array(
                    'hot' => $hot
                ), array(
                    'id' => $id
                ));
            }
            message("排序更新成功！", $this->createWebUrl('job', array(
                'op' => 'display'
            )), 'success');
        }
    }
    $job = pdo_fetchall("select a.*,b.title from " . tablename('enjoy_city_job') . " as a left join
    " . tablename('enjoy_city_firm') . " as b on a.fid=b.id where a.uniacid=" . $uniacid . " " . $where . $where1 . $where2 . " order by a.createtime desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
    $countadd = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_city_job') . " as a left join
    " . tablename('enjoy_city_firm') . " as b on a.fid=b.id where a.uniacid=" . $uniacid . " " . $where . $where1 . $where2 . "");
    $pager = pagination($countadd, $pindex, $psize);
    $countjob = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_city_job') . " where uniacid=" . $uniacid . "");
    $countcheck = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_city_job') . " where uniacid=" . $uniacid . " and ischeck=1");
} elseif ($op=='post') {
    $id = intval($_GPC['id']);
    if (checksubmit("submit")) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'fid' => intval($_GPC['fid']),
            'ptitle' => trim($_GPC['ptitle']),
            'pnum' => intval($_GPC['pnum']),
            'wages' => trim($_GPC['wages']),
            'pmobile' => trim($_GPC['pmobile']),
            'isend' => trim($_GPC['isend']),
            'isfull' => trim($_GPC['isfull']),
            'paddress' => trim($_GPC['paddress']),
            'pdetail' => trim($_GPC['pdetail']),
            'ischeck' => trim($_GPC['ischeck'])
        );
        if (!empty($id)) {
            pdo_update('enjoy_city_job', $data, array(
                'id' => $id
            ));
            $message = "更新职位成功！";
        } else {
            $data['createtime'] = TIMESTAMP;
            $data['updatetime'] = TIMESTAMP;
            pdo_insert("enjoy_city_job", $data);
            $id = pdo_insertid();
            $message = "新增职位成功！";
        }
        message($message, $this->createWebUrl('job', array(
            'op' => 'display'
        )), 'success');
    }
    $job = pdo_fetch("select a.*,b.title from " . tablename('enjoy_city_job') . " as a left join
    " . tablename('enjoy_city_firm') . " as b on a.fid=b.id where a.uniacid=" . $uniacid . " and a.id = '$id'");
    if ($fid > 0) {
        $job = pdo_fetch("select title,tel from " . tablename('enjoy_city_firm') . " where uniacid=" . $uniacid . "
        and id=" . $fid . "");
        $job['createtime'] = TIMESTAMP;
        $job['updatetime'] = TIMESTAMP;
        $job['fid'] = $fid;
        $job['pmobile'] = $job['tel'];
    }
} else {
    message("请求方式不存在");
}
$basic = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=0");
include $this->template('job');