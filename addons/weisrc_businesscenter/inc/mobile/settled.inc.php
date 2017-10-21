<?php
global $_GPC, $_W;
load()->func('tpl');
load()->func('file');
$weid = $this->_weid;
$fromuser = $this->_fromuser;

$title = "微商圈";
$modulename = $this->modulename;

if (isset($_COOKIE[$this->_auth2_openid])) {
    $from_user = $_COOKIE[$this->_auth2_openid];
    $nickname = $_COOKIE[$this->_auth2_nickname];
    $headimgurl = $_COOKIE[$this->_auth2_headimgurl];
} else {
    $userinfo = $this->setUserInfo();
    if (!empty($userinfo)) {
        $from_user = $userinfo["openid"];
        $nickname = $userinfo["nickname"];
        $headimgurl = $userinfo["headimgurl"];
    }
}

//基本信息
$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid = :weid ", array(':weid' => $weid));
if (!empty($setting) && $setting['settled'] == 0) {
    message("商家没有开启入驻功能！");
}

//商家类别
$children = array();
$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE weid = '{$_W['uniacid']}' ORDER BY parentid ASC, displayorder DESC", array(), 'id');
if (!empty($category)) {
    $children = array();
    foreach ($category as $cid => $cate) {
        if (!empty($cate['parentid'])) {
            $children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
        }
    }
}

$item = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " WHERE weid = :weid AND from_user=:from_user LIMIT 1", array(':weid' => $weid, ':from_user' => $fromuser));

if (!empty($item)) {
    $now_date = date('y-m-d', TIMESTAMP);
    //开始时间
    $start_time = strtotime($now_date . ' ' . $item['starttime']);
    $start_hour = date('H', $start_time);
    $start_second = date('i', $start_time);
    //结束时间
    $end_time = strtotime($now_date . ' ' . $item['endtime']);
    $end_hour = date('H', $end_time);
    $end_second = date('i', $end_time);
}

//商家提交信息
if (checksubmit('btnsubmit')) {
    $data = array(
        'weid' => intval($_W['uniacid']),
        'title' => trim($_GPC['title']),
        'from_user' => $fromuser,
        'pcate' => intval($_GPC['category']),
        'ccate' => intval($_GPC['category_child']),
        'services' => trim($_GPC['services']),
        'username' => trim($_GPC['username']),
        'tel' => trim($_GPC['tel']),
        'address' => trim($_GPC['address']),
        'starttime' => trim($_GPC['start_hour'] . ':' . $_GPC['start_second']),
        'endtime' => trim($_GPC['end_hour'] . ':' . $_GPC['end_second']),
        'status' => 0,
        'top' => 0,
        'mode' => 1,
        'checked' => 0,
        'displayorder' => 0,
        'dateline' => TIMESTAMP,
    );

    if (empty($data['title'])) {
        message('请输入商家名称!');
    }
    if (empty($data['username'])) {
        message('请输入您的名称!');
    }
    if (empty($data['tel'])) {
        message('请输入您的联系电话!');
    }
    if (empty($data['address'])) {
        message('请输入您的联系地址!');
    }
    if (empty($data['pcate'])) {
        message('请选择商家类别!');
    }
    if (empty($data['starttime'])) {
        message('请选择营业开始时间');
    }
    if (empty($data['endtime'])) {
        message('请选择营业结束时间');
    }
    if ($data['endtime'] < $data['starttime']) {
        message('请选择正确的营业时间');
    }

    if (!empty($_FILES['fileToUpload']['tmp_name'])) {
        $upload = file_upload($_FILES['fileToUpload']);
        if (is_error($upload)) {
            message($upload['message']);
        }
        $data['businesslicense'] = $upload['path'];
    }

    if (!empty($_FILES['fileToUpload2']['tmp_name'])) {
        $upload2 = file_upload($_FILES['fileToUpload2']);
        if (is_error($upload2)) {
            message($upload2['message']);
        }
        $data['logo'] = $upload2['path'];
    }

    if (empty($item)) { //新增
        pdo_insert($this->table_stores, $data);
        message('您的申请已经成功提交，我们会尽快联系您！', $this->createMobileurl('index'), 'success');
    } else { //更新
        //pdo_update($this->table_stores, $data, array('weid' => $weid, 'from_user' => $fromuser));
        message('您已经提交过申请！', $this->createMobileurl('index'));
    }
}

//#share
$share_image = tomedia($setting['share_image']);
$share_title = empty($setting['share_title']) ? $setting['title'] : $setting['share_title'];
$share_desc = empty($setting['share_desc']) ? $setting['title'] : $setting['share_desc'];
$share_url = empty($setting['share_url']) ? $_W['siteroot'] . 'app/' . $this->createMobileUrl('settled') : $setting['share_url'];
include $this->template($this->cur_tpl . '/settled');