<?php
defined('IN_IA') or exit('Access Denied');
include_once IA_ROOT . '/addons/han_sheka/model.php';
class Han_shekaModuleSite extends WeModuleSite
{
    private $turlar = array('1' => array('id' => 1, 'name' => "生日卡", 'ename' => "shengri"), '2' => array('id' => 2, 'name' => "爱情卡", 'ename' => "zhuhe"), '3' => array('id' => 3, 'name' => "元旦", 'ename' => "aiqing"), '4' => array('id' => 4, 'name' => "春节", 'ename' => "qinyou"), '5' => array('id' => 5, 'name' => "情人节", 'ename' => "xinqing"), '6' => array('id' => 6, 'name' => "元宵节", 'ename' => "ganxie"), '7' => array('id' => 7, 'name' => "端午节", 'ename' => "weiwen"), '8' => array('id' => 8, 'name' => "中秋节", 'ename' => "baifang"), '9' => array('id' => 9, 'name' => "母亲节", 'ename' => "jieri"), '10' => array('id' => 10, 'name' => "父亲节", 'ename' => "dingzhi"), '11' => array('id' => 11, 'name' => "圣诞节", 'ename' => "shengdan"), '12' => array('id' => 12, 'name' => "其他节日", 'ename' => "qita"));
    private $slide = array('0' => array('id' => 1, 'name' => "生日卡"), '1' => array('id' => 2, 'name' => "祝贺卡"), '2' => array('id' => 3, 'name' => "爱情卡"));
    public function __construct()
    {
        global $_W;
        load()->model('account');
        $modules        = uni_modules();
        $_W['settings'] = $modules['han_sheka']['config'];
    }
    public function doWebList()
    {
        global $_W, $_GPC;
        $op = $_GPC['op'];
        if (empty($op))
            $op = "display";
        if ($op == 'display') {
            $classid   = intval($_GPC['classid']);
            $pindex    = max(1, intval($_GPC['page']));
            $psize     = 24;
            $condition = '';
            $params    = array();
            if (!empty($classid)) {
                $condition .= " and classid = '{$classid}'";
            }
            $list  = pdo_fetchall("SELECT * FROM " . tablename('han_sheka_list') . " WHERE (weid = '{$_W['uniacid']}' or weid = 0 )$condition ORDER BY  id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('han_sheka_list') . " WHERE  (weid = '{$_W['uniacid']}' or weid = 0 )$condition");
            $pager = pagination($total, $pindex, $psize);
        } elseif ($op == 'del') {
            $id = intval($_GPC['id']);
            pdo_delete('han_sheka_list', array(
                'id' => $id
            ));
            message('删除成功！', $this->createWebUrl('list', array(
                'op' => 'display'
            )), 'success');
        } elseif ($op == 'post') {
            $id  = intval($_GPC['id']);
            $zid = intval($_GPC['zid']);
            if ($id) {
                if (empty($_W['isfounder'])) {
                    $item = pdo_fetch("SELECT * FROM " . tablename("han_sheka_list") . " WHERE  weid = 0 or weid=:weid and id = :id  ", array(
                        ':weid' => $_W['uniacid'],
                        ':id' => $id
                    ));
                } else {
                    $item = pdo_fetch("SELECT * FROM " . tablename("han_sheka_list") . " WHERE  id = :id  ", array(
                        ':id' => $id
                    ));
                }
                if (empty($item)) {
                    message('贺卡不存在或是已经被删除！');
                }
                if ($item['weid'] == 0) {
                    if (empty($_W['isfounder'])) {
                        message('抱歉，你没有权限！', '', 'error');
                    }
                }
                $zhufu = pdo_fetch("SELECT * FROM " . tablename("han_sheka_zhufu") . " WHERE  cid = :cid  ", array(
                    ':cid' => $id
                ));
            } else {
                $item['tempid'] = 0;
            }
            if (checksubmit('submit')) {
                if (empty($_GPC['title'])) {
                    message('标题不能为空，请输入标题！');
                }
                $insert  = array(
                    'weid' => $_W['uniacid'],
                    'title' => $_GPC['title'],
                    'classid' => $_GPC['classid'],
                    'tempid' => $_GPC['tempid'],
                    'thumb' => $_GPC['thumb'],
                    'cardbg' => $_GPC['cardbg'],
                    'music' => $_GPC['music'],
                    'lang' => $_GPC['lang']
                );
                $zinsert = array(
                    'weid' => $_W['uniacid'],
                    'lang' => $_GPC['lang'],
                    'cardfrom' => $_GPC['cardfrom'],
                    'cardto' => $_GPC['cardto'],
                    'cardbody' => $_GPC['cardbody'],
                    'cardto_left' => $_GPC['cardto_left'],
                    'cardto_top' => $_GPC['cardto_top'],
                    'cardbody_width' => $_GPC['cardbody_width'],
                    'cardbody_left' => $_GPC['cardbody_left'],
                    'cardbody_top' => $_GPC['cardbody_top'],
                    'cardfrom_left' => $_GPC['cardfrom_left'],
                    'cardfrom_top' => $_GPC['cardfrom_top'],
                    'panel_top' => $_GPC['panel_top'],
                    'panel_left' => $_GPC['panel_left'],
                    'panel_width' => $_GPC['panel_width'],
                    'panel_height' => $_GPC['panel_height'],
                    'panel_color' => $_GPC['panel_color'],
                    'panel_bg' => $_GPC['panel_bg'],
                    'panel_alpha' => $_GPC['panel_alpha'],
                    'font_size' => $_GPC['font_size']
                );
                if (empty($id)) {
                    pdo_insert("han_sheka_list", $insert);
                    $insertid       = pdo_insertid();
                    $zinsert['cid'] = $insertid;
                    pdo_insert('han_sheka_zhufu', $zinsert);
                } else {
                    pdo_update('han_sheka_list', $insert, array(
                        'id' => $id
                    ));
                    if ($zid) {
                        pdo_update("han_sheka_zhufu", $zinsert, array(
                            'id' => $zid
                        ));
                    } else {
                        $zinsert['cid'] = $id;
                        pdo_insert('han_sheka_zhufu', $zinsert);
                    }
                }
                message('修改成功！', $this->createWebUrl('list', array(
                    'op' => 'post',
                    'id' => $id
                )), 'success');
            }
        }
        load()->func('tpl');
        include $this->template('list');
    }
    public function doMobileIndex()
    {
        global $_GPC, $_W;
        include $this->template('index');
    }
    public function doMobileList()
    {
        global $_GPC, $_W;
        $classid = intval($_GPC['classid']);
        $list    = pdo_fetchall("SELECT * FROM " . tablename('han_sheka_list') . "  where classid= '{$classid}'  and (weid = '{$_W['weid']}'  or weid =0)  ORDER BY id deSC");
        include $this->template('list');
    }
    public function doMobilePreview()
    {
        global $_GPC, $_W;
        $id     = intval($_GPC['id']);
        $sql    = "SELECT * FROM " . tablename('han_sheka_list') . " WHERE `id`=:id";
        $detail = pdo_fetch($sql, array(
            ':id' => $id
        ));
        if (empty($detail['id'])) {
            exit;
        }
        include $this->template('preview');
    }
    public function doWebQuery()
    {
        global $_W, $_GPC;
        $ds = $this->turlar;
        foreach ($ds as &$row) {
            $r                = array();
            $r['name']        = $row['name'];
            $r['description'] = $row['name'];
            $r['id']          = $row['id'];
            $row['entry']     = $r;
        }
        include $this->template('query');
    }
    public function doWebzdy()
    {
        global $_W, $_GPC;
        $item = pdo_fetch("SELECT * FROM " . tablename('han_sheka_reply') . " WHERE  weid = :weid  ", array(
            ':weid' => $_W['uniacid']
        ));
        if (checksubmit('submit')) {
            if ($_GPC['follow_switch'] == 0) {
                if (empty($_GPC['zdyurl'])) {
                    message('开启状态下引导关注链接不能为空！');
                }
            }
            $id     = intval($_GPC['id']);
            $insert = array(
                'weid' => $_W['uniacid'],
                'follow_switch' => $_GPC['follow_switch'],
                'zdyurl' => $_GPC['zdyurl'],
                'f_logo' => $_GPC['f_logo']
            );
            if (empty($id)) {
                pdo_insert("han_sheka_reply", $insert);
            } else {
                pdo_update('han_sheka_reply', $insert, array(
                    'id' => $id
                ));
            }
            message('修改成功！', $this->createWebUrl('zdy'), 'success');
        }
        include $this->template('zdy');
    }
    public function doMobileTemp()
    {
        global $_GPC, $_W;
        $id   = intval($_GPC['id']);
        $data = pdo_fetch("SELECT * FROM " . tablename('han_sheka_list') . " WHERE id = '{$id}' ");
        if ($data['tempid'] == 1) {
            if ($id >= 271) {
                $zhufu = pdo_fetch("SELECT * FROM " . tablename("han_sheka_zhufu") . " WHERE  cid = :cid  ", array(
                    ':cid' => $id
                ));
                include $this->template('temp_' . $data['tempid'] . '');
            } else {
                include $this->template('temp/' . $data['id'] . '');
            }
        } else {
            $zhufu = pdo_fetch("SELECT * FROM " . tablename("han_sheka_zhufu") . " WHERE  cid = :cid  ", array(
                ':cid' => $id
            ));
            include $this->template('temp_' . $data['tempid'] . '');
        }
    }
    public function doMobileSendform()
    {
        global $_GPC, $_W;
        $id      = intval($_GPC['id']);
        $guanzhu = pdo_fetch("SELECT * FROM " . tablename("han_sheka_reply") . " WHERE  weid = :weid  ", array(
            ':weid' => $_W['uniacid']
        ));
        if ($guanzhu['follow_switch'] == 0 && empty($_W['fans']['follow'])) {
            echo "<script>alert('请关注后再定制！');location.href='" . $guanzhu['zdyurl'] . "';</script>";
            die();
        }
        $sql  = "SELECT * FROM " . tablename('han_sheka_list') . " WHERE `id`=:id";
        $data = pdo_fetch($sql, array(
            ':id' => $id
        ));
        if (empty($data['id'])) {
            exit;
        }
        $zhufu     = pdo_fetch("SELECT * FROM " . tablename('han_sheka_zhufu') . " WHERE cid = '{$id}' ");
        $zhufulist = pdo_fetchall("SELECT * FROM " . tablename('han_sheka_zhufu') . " as z left join  " . tablename('han_sheka_list') . " as l   on  z.cid=l.id WHERE l.classid = '{$data['classid']}'   and  l.lang = '{$data['lang']}'  limit 0,10");
        include $this->template('sendform');
    }
    public function doMobileCardshow()
    {
        global $_GPC, $_W;
        $id       = intval($_GPC['cid']);
        $cardFrom = htmlspecialchars_decode($_GPC['cardFrom']);
        $cardTo   = htmlspecialchars_decode($_GPC['cardTo']);
        $cardBody = htmlspecialchars_decode($_GPC['cardBody']);
        $sql      = "SELECT * FROM " . tablename('han_sheka_list') . " WHERE `id`=:id";
        $data     = pdo_fetch($sql, array(
            ':id' => $id
        ));
        $guanzhu  = pdo_fetch("SELECT * FROM " . tablename("han_sheka_reply") . " WHERE  weid = :weid  ", array(
            ':weid' => $_W['uniacid']
        ));
        $newname  = $guanzhu['f_logo'];
        if (empty($data['id'])) {
            exit;
        }
        include $this->template('cardshow');
    }
}