<?php
defined('IN_IA') or exit('Access Denied');
class Tb_serviceModuleSite extends WeModuleSite
{
    public function doMobileIndex()
    {
        global $_GPC, $_W;
        if (checksubmit('submit')) {
            $data = $_GPC['data'];
            $res  = pdo_fetch("SELECT * FROM " . tablename('tb_service_user') . " WHERE `username`=:username AND `password`=:password AND `uniacid`=:uniacid AND `uditing`=1 ", array(
                ':username' => $data['username'],
                ':password' => $data['password'],
                ':uniacid' => $_W['uniacid']
            ));
            if ($res) {
                $_SESSION['check'] = $data['username'];
                message('登录成功，请稍等...', $this->createMobileUrl('show'), 'success');
            } else {
                message('登录失败,用户名或者密码错误！', $this->createMobileUrl('index'), 'error');
            }
        }
        $share = pdo_fetch("SELECT * FROM " . tablename('tb_service_share') . " WHERE `uniacid`=:uniacid", array(
            ':uniacid' => $_W['uniacid']
        ));
        include $this->template('index');
    }
    public function doMobileRegister()
    {
        global $_GPC, $_W;
        if (checksubmit()) {
            $data            = $_GPC['data'];
            $data['uniacid'] = $_W['uniacid'];
            empty($data['username']) && message("没有填写名字");
            empty($data['password']) && message("没有填写密码");
            empty($data['phoneNumber']) && message("没有填写手机号码");
            $res = pdo_fetch("SELECT username FROM  " . tablename('tb_service_user') . " WHERE `username` = :username AND `uniacid` = :uniacid", array(
                ':username' => $data['username'],
                'uniacid' => $_W['uniacid']
            ));
            if (!empty($res['username'])) {
                message('用户名已存在，请重新填写', $this->createMobileUrl('register'), 'info');
            }
            $res = pdo_fetch("SELECT phoneNumber FROM " . tablename('tb_service_user') . "WHERE `phoneNumber`=:phoneNumber AND `uniacid`=:uniacid", array(
                ':phoneNumber' => $data['phoneNumber'],
                ':uniacid' => $_W['uniacid']
            ));
            if (!empty($res['phoneNumber'])) {
                message('手机号码已存在,请重新填写', $this->createMobileUrl('register'), 'info');
            }
            $res = pdo_insert('tb_service_user', $data);
            if ($res) {
                message('注册成功,请等待管理员审核！！', $this->createMobileUrl('index'), 'success');
            } else {
                message('注册失败，请稍后再试！', $this->createMobileUrl('register'), 'error');
            }
        }
        $share = pdo_fetch("SELECT * FROM " . tablename('tb_service_share') . " WHERE `uniacid`=:uniacid", array(
            ':uniacid' => $_W['uniacid']
        ));
        include $this->template('Register');
    }
    public function doMobileShow()
    {
        global $_GPC, $_W;
        $root   = MODULE_URL;
        $slider = pdo_fetch("SELECT * FROM " . tablename('tb_service_slider') . "WHERE `uniacid`=:uniacid", array(
            ':uniacid' => $_W['uniacid']
        ));
        $fast   = pdo_fetch("SELECT * FROM " . tablename('tb_service_fast') . "WHERE `uniacid`=:uniacid", array(
            ':uniacid' => $_W['uniacid']
        ));
        $report = pdo_fetchall("SELECT * FROM " . tablename('tb_service_report') . "WHERE `uniacid`=:uniacid AND `status`=0 ORDER BY `id` DESC LIMIT 0,8 ", array(
            ':uniacid' => $_W['uniacid']
        ));
        $share  = pdo_fetch("SELECT * FROM " . tablename('tb_service_share') . " WHERE `uniacid`=:uniacid", array(
            ':uniacid' => $_W['uniacid']
        ));
        include $this->template('show');
    }
    public function doMobileImage()
    {
        global $_GPC, $_W;
        $root = MODULE_ROOT;
        load()->func('file');
        $uptypes = array(
            'image/jpg',
            'image/jpeg',
            'image/png',
            'image/bmp',
            'image/gif'
        );
        if (!is_uploaded_file($_FILES["file"]["tmp_name"])) {
            $tmp_name = false;
        } else {
            $tmp_name = true;
        }
        if ($_FILES["file"]["size"] > 3145728) {
            $siz = false;
        } else {
            $siz = true;
        }
        if (!in_array($_FILES["file"]["type"], $uptypes)) {
            $type = false;
        } else {
            $type = true;
        }
        if (!file_exists(MODULE_ROOT . '/template/attachment/')) {
            mkdirs(MODULE_ROOT . '/template/attachment/');
        }
        if ($tmp_name && $siz && $type) {
            $name       = $_FILES['file']['tmp_name'];
            $tname      = $_FILES['file']['name'];
            $ext        = extend($tname);
            $path       = MODULE_ROOT . '/template/attachment/';
            $image_name = time() . rand(100, 999) . "." . $ext;
            if (move_uploaded_file($name, $path . $image_name)) {
                $answer = "y";
            } else {
                $answer = "n";
            }
            $jason = json_encode(array(
                'error1' => 'yes',
                'type1' => MODULE_URL . 'template/attachment/' . $image_name
            ));
        } else {
            $jason = json_encode(array(
                'error1' => 'not'
            ));
        }
        return $jason;
    }
    public function doMobileReport()
    {
        global $_GPC, $_W;
        load()->func('tpl');
        if (checksubmit()) {
            $data            = $_GPC['data'];
            $data['uniacid'] = $_W['uniacid'];
            $image           = $_GPC['check'];
            $data['image1']  = $image[0];
            $data['image2']  = $image[1];
            $data['image3']  = $image[2];
            empty($data['username']) && message("没有填写名字");
            empty($data['phoneNumber']) && message("没有填写手机号码");
            empty($data['address']) && message("没有填写手机号码");
            empty($data['title']) && message("没有填写标题");
            empty($data['summery']) && message("没有填写简述概要");
            empty($data['content']) && message("没有填写故障详细内容");
            $res = pdo_insert('tb_service_report', $data);
            if ($res) {
                message("发表成功", $this->createMobileUrl('report'), 'success');
            } else {
                message('发表失败,请重试!', '', 'error');
            }
        }
        $res   = pdo_fetch("SELECT * FROM " . tablename('tb_service_user') . " WHERE `username`=:username AND `uniacid`=:uniacid AND `uditing`=1 ", array(
            ':username' => $_SESSION['check'],
            ':uniacid' => $_W['uniacid']
        ));
        $fast  = pdo_fetch("SELECT * FROM " . tablename('tb_service_fast') . "WHERE `uniacid`=:uniacid", array(
            ':uniacid' => $_W['uniacid']
        ));
        $share = pdo_fetch("SELECT * FROM " . tablename('tb_service_share') . " WHERE `uniacid`=:uniacid", array(
            ':uniacid' => $_W['uniacid']
        ));
        include $this->template('report');
    }
    public function doMobileGettap()
    {
        global $_GPC, $_W;
        $id       = $_GPC['id'];
        $username = $_GPC['take_name'];
        $res      = pdo_fetch("SELECT identify FROM " . tablename('tb_service_user') . "WHERE `username`=:username AND `uniacid`=:uniacid", array(
            ':username' => $_SESSION['check'],
            ':uniacid' => $_W['uniacid']
        ));
        if ($res['identify'] == 0) {
            return 'kehu';
        } else {
            $res = pdo_update('tb_service_report', array(
                'take_name' => $username,
                'status' => 1
            ), array(
                'id' => $id,
                'uniacid' => $_W['uniacid']
            ));
            if ($res) {
                return 'success';
            } else if ($res == 0) {
                return 'success';
            } else {
                return 'error';
            }
        }
    }
    public function doMobileConfirm()
    {
        global $_GPC, $_W;
        $id  = $_GPC['id'];
        $res = pdo_update('tb_service_report', array(
            'confirm' => 1
        ), array(
            'id' => $id,
            'uniacid' => $_W['uniacid']
        ));
        if ($res) {
            return 'success';
        } else if ($res == 0) {
            return 'success';
        } else {
            return 'error';
        }
    }
    public function doMobileList()
    {
        global $_W, $_GPC;
        $fast    = pdo_fetch("SELECT * FROM " . tablename('tb_service_fast') . "WHERE `uniacid`=:uniacid", array(
            ':uniacid' => $_W['uniacid']
        ));
        $report1 = pdo_fetchall("SELECT * FROM " . tablename('tb_service_report') . "WHERE `uniacid`=:uniacid AND `status`=0 ORDER BY `id` DESC", array(
            ':uniacid' => $_W['uniacid']
        ));
        $report2 = pdo_fetchall("SELECT * FROM " . tablename('tb_service_report') . "WHERE `uniacid`=:uniacid AND `status`=1 ORDER BY `id` DESC", array(
            ':uniacid' => $_W['uniacid']
        ));
        $report3 = pdo_fetchall("SELECT * FROM " . tablename('tb_service_report') . "WHERE `uniacid`=:uniacid AND `status`=2 ORDER BY `id` DESC", array(
            ':uniacid' => $_W['uniacid']
        ));
        $share   = pdo_fetch("SELECT * FROM " . tablename('tb_service_share') . " WHERE `uniacid`=:uniacid", array(
            ':uniacid' => $_W['uniacid']
        ));
        include $this->template('list');
    }
    public function doMobileMine()
    {
        global $_W, $_GPC;
        $res = pdo_fetch("SELECT identify FROM " . tablename('tb_service_user') . "WHERE `username`=:username AND `uniacid`=:uniacid", array(
            ':username' => $_SESSION['check'],
            ':uniacid' => $_W['uniacid']
        ));
        if ($res['identify'] == 0) {
            $report1 = pdo_fetchall("SELECT * FROM " . tablename('tb_service_report') . "WHERE `uniacid`=:uniacid AND `username`=:username AND `status`=0 ORDER BY `id` DESC", array(
                ':uniacid' => $_W['uniacid'],
                ':username' => $_SESSION['check']
            ));
            $report2 = pdo_fetchall("SELECT * FROM " . tablename('tb_service_report') . "WHERE `uniacid`=:uniacid AND `username`=:username AND `status`=1 ORDER BY `id` DESC", array(
                ':uniacid' => $_W['uniacid'],
                ':username' => $_SESSION['check']
            ));
            $report3 = pdo_fetchall("SELECT * FROM " . tablename('tb_service_report') . "WHERE `uniacid`=:uniacid AND `username`=:username AND `status`=2 ORDER BY `id` DESC", array(
                ':uniacid' => $_W['uniacid'],
                ':username' => $_SESSION['check']
            ));
        } else {
            $report1 = pdo_fetchall("SELECT * FROM " . tablename('tb_service_report') . "WHERE `uniacid`=:uniacid AND `username`=:username AND `status`=0 ORDER BY `id` DESC", array(
                ':uniacid' => $_W['uniacid'],
                ':username' => $_SESSION['check']
            ));
            $report2 = pdo_fetchall("SELECT * FROM " . tablename('tb_service_report') . "WHERE `uniacid`=:uniacid AND `take_name`=:username AND `status`=1 ORDER BY `id` DESC", array(
                ':uniacid' => $_W['uniacid'],
                ':username' => $_SESSION['check']
            ));
            $report3 = pdo_fetchall("SELECT * FROM " . tablename('tb_service_report') . "WHERE `uniacid`=:uniacid AND `take_name`=:username AND `status`=2 ORDER BY `id` DESC", array(
                ':uniacid' => $_W['uniacid'],
                ':username' => $_SESSION['check']
            ));
        }
        $fast  = pdo_fetch("SELECT * FROM " . tablename('tb_service_fast') . "WHERE `uniacid`=:uniacid", array(
            ':uniacid' => $_W['uniacid']
        ));
        $share = pdo_fetch("SELECT * FROM " . tablename('tb_service_share') . " WHERE `uniacid`=:uniacid", array(
            ':uniacid' => $_W['uniacid']
        ));
        include $this->template('mine');
    }
    public function doMobileDetail()
    {
        global $_W, $_GPC;
        $res    = pdo_fetch("SELECT identify FROM " . tablename('tb_service_user') . "WHERE `username`=:username AND `uniacid`=:uniacid", array(
            ':username' => $_SESSION['check'],
            ':uniacid' => $_W['uniacid']
        ));
        $report = pdo_fetch("SELECT * FROM " . tablename('tb_service_report') . "WHERE `uniacid`=:uniacid AND `id`=:id", array(
            ':uniacid' => $_W['uniacid'],
            'id' => $_GPC['id']
        ));
        $fast   = pdo_fetch("SELECT * FROM " . tablename('tb_service_fast') . "WHERE `uniacid`=:uniacid", array(
            ':uniacid' => $_W['uniacid']
        ));
        $share  = pdo_fetch("SELECT * FROM " . tablename('tb_service_share') . " WHERE `uniacid`=:uniacid", array(
            ':uniacid' => $_W['uniacid']
        ));
        include $this->template('detail');
    }
    public function doMobileUser()
    {
        global $_W, $_GPC;
        $root  = MODULE_URL;
        $share = pdo_fetch("SELECT * FROM " . tablename('tb_service_share') . " WHERE `uniacid`=:uniacid", array(
            ':uniacid' => $_W['uniacid']
        ));
        include $this->template('user');
    }
    public function doWebEngineer()
    {
        global $_W, $_GPC;
        load()->func('tpl');
        $data['uniacid'] = $_W['uniacid'];
        $ops             = array(
            'manage',
            'change',
            'check'
        );
        $op              = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'manage';
        $index           = isset($_GPC['page']) ? $_GPC['page'] : 1;
        $pageIndex       = $index;
        $pageSize        = 10;
        $contion         = 'LIMIT ' . ($pageIndex - 1) * $pageSize . ',' . $pageSize;
        if ($op == 'manage') {
            $total    = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tb_service_user') . " WHERE `uniacid`=:uniacid AND `identify`<>0", array(
                ':uniacid' => $_W['uniacid']
            ));
            $page     = pagination($total, $pageIndex, $pageSize);
            $engineer = pdo_fetchall("SELECT * FROM " . tablename('tb_service_user') . " WHERE `uniacid`=:uniacid AND `identify`<>0 ORDER BY `id` DESC " . $contion, array(
                ':uniacid' => $_W['uniacid']
            ));
            include $this->template('engineer');
        }
        if ($op == 'check') {
            $total    = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tb_service_user') . " WHERE `uditing`=0 AND `uniacid`=:uniacid AND `identify`<>0", array(
                ':uniacid' => $_W['uniacid']
            ));
            $page     = pagination($total, $pageIndex, $pageSize);
            $engineer = pdo_fetchall("SELECT * FROM " . tablename('tb_service_user') . " WHERE `uditing`=0 AND `uniacid`=:uniacid AND `identify`<>0 ORDER BY `id` DESC " . $contion, array(
                ':uniacid' => $_W['uniacid']
            ));
            include $this->template('engineer');
        }
        if ($op == 'change') {
            if (checksubmit()) {
                $data = $_GPC['data'];
                empty($data['username']) && message("没有填写名字");
                empty($data['password']) && message("没有填写密码");
                empty($data['phoneNumber']) && message("没有填写手机号码");
                $res = pdo_update('tb_service_user', $data, array(
                    'id' => $data['id']
                ));
                if ($res) {
                    message('更新成功', $this->createWebUrl('engineer', array()), 'success');
                } elseif ($res == 0) {
                    message('你未做任何改变', $this->createWebUrl('engineer', array()), 'error');
                } else {
                    message('更新失败', $this->createWebUrl('engineer', array()), 'error');
                }
            }
            $engineer = pdo_fetch("SELECT * FROM " . tablename('tb_service_user') . " WHERE `id`=:id AND `uniacid`=:uniacid AND `identify`<>0", array(
                ':id' => $_GPC['id'],
                ':uniacid' => $_W['uniacid']
            ));
            $report   = pdo_fetchall("SELECT * FROM " . tablename('tb_service_report') . " WHERE `username`=:username OR `take_name`=:username AND `uniacid`=:uniacid ", array(
                ':username' => $engineer['username'],
                ':uniacid' => $_W['uniacid']
            ));
            include $this->template('engineer');
        }
    }
    public function doWebCheckajax()
    {
        global $_W, $_GPC;
        $getku = $_GPC['ku'];
        $res   = pdo_update($getku, array(
            'uditing' => 1
        ), array(
            'id' => $_GPC['id'],
            'uniacid' => $_W['uniacid']
        ));
        if ($res) {
            return 'success';
        } else {
            return 'error';
        }
    }
    public function doWebDeleteajax()
    {
        global $_W, $_GPC;
        $getku = $_GPC['ku'];
        $res   = pdo_delete($getku, array(
            'id' => $_GPC['id'],
            'uniacid' => $_W['uniacid']
        ));
        if ($res) {
            return 'success';
        } else {
            return 'error';
        }
    }
    public function doWebCustomer()
    {
        global $_W, $_GPC;
        $data['uniacid'] = $_W['uniacid'];
        $ops             = array(
            'manage',
            'change',
            'check'
        );
        $op              = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'manage';
        $index           = isset($_GPC['page']) ? $_GPC['page'] : 1;
        $pageIndex       = $index;
        $pageSize        = 10;
        $contion         = 'LIMIT ' . ($pageIndex - 1) * $pageSize . ',' . $pageSize;
        if ($op == 'manage') {
            $total    = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tb_service_user') . " WHERE `uniacid`=:uniacid AND `identify`=0", array(
                ':uniacid' => $_W['uniacid']
            ));
            $page     = pagination($total, $pageIndex, $pageSize);
            $customer = pdo_fetchall("SELECT * FROM " . tablename('tb_service_user') . " WHERE `uniacid`=:uniacid AND `identify`=0 ORDER BY `id` DESC " . $contion, array(
                ':uniacid' => $_W['uniacid']
            ));
            include $this->template('customer');
        }
        if ($op == 'check') {
            $total    = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tb_service_user') . " WHERE `uditing`=0 AND `uniacid`=:uniacid AND `identify`=0", array(
                ':uniacid' => $_W['uniacid']
            ));
            $page     = pagination($total, $pageIndex, $pageSize);
            $customer = pdo_fetchall("SELECT * FROM " . tablename('tb_service_user') . " WHERE `uditing`=0 AND `uniacid`=:uniacid AND `identify`=0 ORDER BY `id` DESC " . $contion, array(
                ':uniacid' => $_W['uniacid']
            ));
            include $this->template('customer');
        }
        if ($op == 'change') {
            if (checksubmit()) {
                $data = $_GPC['data'];
                empty($data['name']) && message("没有填写名字");
                empty($data['password']) && message("没有填写密码");
                empty($data['phoneNumber']) && message("没有填写手机号码");
                $res = pdo_update('tb_service_customer', $data, array(
                    'id' => $data['id'],
                    'uniacid' => $_W['uniacid']
                ));
                if ($res) {
                    message('更新成功', $this->createWebUrl('engineer', array()), 'success');
                } elseif ($res == 0) {
                    message('你未做任何改变', $this->createWebUrl('engineer', array()), 'error');
                } else {
                    message('更新失败', $this->createWebUrl('engineer', array()), 'error');
                }
            }
            $customer = pdo_fetch("SELECT * FROM " . tablename('tb_service_user') . " WHERE `id`=:id AND `uniacid`=:uniacid AND `identify`=0 ", array(
                ':id' => $_GPC['id'],
                ':uniacid' => $_W['uniacid']
            ));
            $report   = pdo_fetchall("SELECT * FROM " . tablename('tb_service_report') . " WHERE `username`=:username AND `uniacid`=:uniacid ", array(
                ':username' => $customer['username'],
                ':uniacid' => $_W['uniacid']
            ));
            include $this->template('customer');
        }
    }
    public function doWebShare()
    {
        global $_GPC, $_W;
        load()->func('tpl');
        if (checksubmit()) {
            if (empty($_GPC['getid'])) {
                $data            = $_GPC['data'];
                $data['uniacid'] = $_W['uniacid'];
                empty($data['shareTitle']) && message('分享标题不能为空');
                empty($data['shareImage']) && message('分享图片不能为空');
                empty($data['shareContent']) && message('分享内容不能为空');
                $res = pdo_insert('tb_service_share', $data);
                if ($res) {
                    message('操作成功', $this->createWebUrl('share'), 'success');
                } else {
                    message('操作失败', '', 'error');
                }
            } else {
                $data            = $_GPC['data'];
                $data['uniacid'] = $_W['uniacid'];
                empty($data['shareTitle']) && message('分享标题不能为空');
                empty($data['shareImage']) && message('分享图片不能为空');
                empty($data['shareContent']) && message('分享内容不能为空');
                $res = pdo_update('tb_service_share', $data, array(
                    'id' => $_GPC['getid'],
                    'uniacid' => $_W['uniacid']
                ));
                if ($res) {
                    message('更新成功', $this->createWebUrl('share', array()), 'success');
                } elseif ($res == 0) {
                    message('你未做任何改变', $this->createWebUrl('share', array()), 'error');
                } else {
                    message('更新失败', '', 'error');
                }
            }
        }
        $share = pdo_fetch("SELECT * FROM " . tablename('tb_service_share') . " WHERE uniacid=:uniacid ", array(
            ':uniacid' => $_W['uniacid']
        ));
        include $this->template('share');
    }
    public function doWebFast()
    {
        global $_GPC, $_W;
        load()->func('tpl');
        if (checksubmit()) {
            if (empty($_GPC['getid'])) {
                $data            = $_GPC['data'];
                $data['uniacid'] = $_W['uniacid'];
                empty($data['qq']) && message('QQ不能为空');
                empty($data['weixin']) && message('关注公众号链接不能为空');
                empty($data['number']) && message('分享内容不能为空');
                empty($data['shop']) && message('商城链接不能为空');
                $res = pdo_insert('tb_service_fast', $data);
                if ($res) {
                    message('操作成功', $this->createWebUrl('fast'), 'success');
                } else {
                    message('操作失败', '', 'error');
                }
            } else {
                $data            = $_GPC['data'];
                $data['uniacid'] = $_W['uniacid'];
                empty($data['qq']) && message('QQ不能为空');
                empty($data['weixin']) && message('关注公众号链接不能为空');
                empty($data['number']) && message('分享内容不能为空');
                empty($data['shop']) && message('商城链接不能为空');
                empty($data['shop']) && message('公司名字不能为空');
                $res = pdo_update('tb_service_fast', $data, array(
                    'id' => $_GPC['getid'],
                    'uniacid' => $_W['uniacid']
                ));
                if ($res) {
                    message('更新成功', $this->createWebUrl('fast', array()), 'success');
                } elseif ($res == 0) {
                    message('你未做任何改变', $this->createWebUrl('fast', array()), 'error');
                } else {
                    message('更新失败', '', 'error');
                }
            }
        }
        $fast = pdo_fetch("SELECT * FROM " . tablename('tb_service_fast') . " WHERE uniacid=:uniacid ", array(
            ':uniacid' => $_W['uniacid']
        ));
        include $this->template('fast');
    }
    public function doWebSlider()
    {
        global $_GPC, $_W;
        load()->func('tpl');
        if (checksubmit()) {
            $data            = $_GPC['data'];
            $data['uniacid'] = $_W['uniacid'];
            empty($data['slider1']) && message('第一张幻灯片不能为空');
            empty($data['slider2']) && message('第二张幻灯片不能为空');
            empty($data['slider3']) && message('第三张幻灯片不能为空');
            empty($data['slider4']) && message('第四张幻灯片不能为空');
            if (empty($_GPC['diff'])) {
                $res = pdo_insert('tb_service_slider', $data);
                if ($res) {
                    message('操作成功', $this->createWebUrl('slider'), 'success');
                } else {
                    message('操作失败', '', 'error');
                }
            } else {
                $res = pdo_update('tb_service_slider', $data, array(
                    'id' => $_GPC['diff'],
                    'uniacid' => $_W['uniacid']
                ));
                if ($res) {
                    message('更新成功', $this->createWebUrl('slider', array()), 'success');
                } elseif ($res == 0) {
                    message('你未做任何改变', $this->createWebUrl('slider', array()), 'error');
                } else {
                    message('更新失败', '', 'error');
                }
            }
        }
        $data = pdo_fetch("SELECT * FROM " . tablename('tb_service_slider') . " WHERE uniacid=:uniacid ", array(
            ':uniacid' => $_W['uniacid']
        ));
        include $this->template('slider');
    }
    public function doWebFault()
    {
        global $_W, $_GPC;
        $data['uniacid'] = $_W['uniacid'];
        $ops             = array(
            'manage',
            'change',
            'check'
        );
        $op              = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'manage';
        $index           = isset($_GPC['page']) ? $_GPC['page'] : 1;
        $pageIndex       = $index;
        $pageSize        = 10;
        $contion         = 'LIMIT ' . ($pageIndex - 1) * $pageSize . ',' . $pageSize;
        if ($op == 'manage') {
            $total  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tb_service_report') . " WHERE `uniacid`=:uniacid ", array(
                ':uniacid' => $_W['uniacid']
            ));
            $page   = pagination($total, $pageIndex, $pageSize);
            $report = pdo_fetchall("SELECT * FROM " . tablename('tb_service_report') . " WHERE `uniacid`=:uniacid  ORDER BY `id` DESC " . $contion, array(
                ':uniacid' => $_W['uniacid']
            ));
            include $this->template('fault');
        }
        if ($op == 'check') {
            $total  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tb_service_report') . " WHERE `confirm`=1 AND `uniacid`=:uniacid ", array(
                ':uniacid' => $_W['uniacid']
            ));
            $page   = pagination($total, $pageIndex, $pageSize);
            $report = pdo_fetchall("SELECT * FROM " . tablename('tb_service_report') . "  WHERE `confirm`=1 AND `uniacid`=:uniacid  ORDER BY `id` DESC " . $contion, array(
                ':uniacid' => $_W['uniacid']
            ));
            include $this->template('fault');
        }
        if ($op == 'change') {
            $report = pdo_fetch("SELECT * FROM " . tablename('tb_service_report') . " WHERE `id`=:id AND `uniacid`=:uniacid ", array(
                ':id' => $_GPC['id'],
                ':uniacid' => $_W['uniacid']
            ));
            include $this->template('fault');
        }
    }
    public function doWebDeletetask()
    {
        global $_GPC, $_W;
        $id  = $_GPC['id'];
        $res = pdo_delete('tb_service_report', array(
            'id' => $id,
            'uniacid' => $_W['uniacid']
        ));
        if ($res) {
            return 'success';
        } else {
            return 'error';
        }
    }
    public function doWebTask()
    {
        global $_GPC, $_W;
        $id  = $_GPC['id'];
        $res = pdo_update('tb_service_report', array(
            'confirm' => 0,
            'status' => 2
        ), array(
            'id' => $id,
            'uniacid' => $_W['uniacid']
        ));
        if ($res) {
            return 'success';
        } else {
            return 'error';
        }
    }
}
function extend($file_name)
{
    $extend = pathinfo($file_name);
    $extend = strtolower($extend["extension"]);
    return $extend;
}