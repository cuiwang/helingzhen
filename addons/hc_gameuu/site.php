<?php
defined('IN_IA') or exit('Access Denied');
function get_timelinegame($pubtime)
{
    $time = time();
    if (idate('Y', $time) != idate('Y', $pubtime)) {
        return date('Y年m月d日', $pubtime);
    }
    $seconds = $time - $pubtime;
    $days    = idate('z', $time) - idate('z', $pubtime);
    if ($days == 0) {
        if ($seconds < 3600) {
            if ($seconds < 60) {
                if (3 > $seconds) {
                    return '刚刚';
                } else {
                    return $seconds . '秒前';
                }
            }
            return intval($seconds / 60) . '分钟前';
        }
        return idate('H', $time) - idate('H', $pubtime) . '小时前';
    }
    if ($days == 1) {
        return '昨天 ' . date('H:i', $pubtime);
    }
    if ($days == 2) {
        return '前天 ' . date('H:i', $pubtime);
    }
    if ($days < 7) {
        return $days . '天前';
    }
    return date('n月j日 H:i', $pubtime);
}
class Hc_gameuuModuleSite extends WeModuleSite
{
    public function doMobileFm()
    {
        global $_W, $_GPC;
        $src    = $_W['siteroot'] . 'addons/hc_gameuu/images/';
        $weixin = "搜索[" . $_W['account']['name'] . "]关注我";
        $listt  = pdo_fetchall("SELECT * FROM " . tablename('hc_gameuu_game') . " WHERE weid = '{$_W['weid']}' and ist=1 and isok=1 ORDER BY id DESC");
        $gamen  = intval($this->module['config']['gamen']);
        $gamew  = $this->module['config']['gamew'];
        include $this->template('game');
    }
    public function doWebGamet()
    {
        global $_W, $_GPC;
        load()->func('tpl');
        $op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ('post' == $op) {
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename('hc_gameuu_game_category') . " WHERE id = :id", array(
                    ':id' => $id
                ));
                if (empty($item)) {
                    message('亲,数据不存在！', '', 'error');
                }
            }
            if (checksubmit('submit')) {
                if (empty($_GPC['title'])) {
                    message('亲,分类名称不能为空!');
                }
                $weid  = $_W['weid'];
                $title = $_GPC['title'];
                $data  = array(
                    'weid' => $weid,
                    'title' => $title
                );
                if (empty($id)) {
                    pdo_insert('hc_gameuu_game_category', $data);
                    message('游戏分类添加成功！', $this->createWebUrl('gamet', array(
                        'op' => 'display'
                    )), 'success');
                } else {
                    pdo_update('hc_gameuu_game_category', $data, array(
                        'id' => $id
                    ));
                    message('游戏分类更新成功！', $this->createWebUrl('gamet', array(
                        'op' => 'display'
                    )), 'success');
                }
            } else {
                include $this->template('t');
            }
        } else if ('del' == $op) {
            $id  = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id FROM " . tablename('hc_gameuu_game_category') . " WHERE id = :id", array(
                ':id' => $id
            ));
            if (empty($row)) {
                message('亲,分类' . $id . '不存在,不要乱动哦！');
            }
            pdo_delete('hc_gameuu_game_category', array(
                'id' => $id
            ));
            message('删除成功！', referer(), 'success');
        } else if ('display' == $op) {
            $pindex    = max(1, intval($_GPC['page']));
            $psize     = 20;
            $condition = '';
            $list      = pdo_fetchall("SELECT * FROM " . tablename('hc_gameuu_game_category') . " WHERE weid = '{$_W['weid']}' $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
            $total     = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('hc_gameuu_game_category') . " WHERE weid = '{$_W['weid']}'");
            $pager     = pagination($total, $pindex, $psize);
            include $this->template('t');
        }
    }
    public function doWebGamem()
    {
        global $_W, $_GPC;
        load()->func('tpl');
        $op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ('post' == $op) {
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename('hc_gameuu_game') . " WHERE id = :id", array(
                    ':id' => $id
                ));
                if (empty($item)) {
                    message('亲,数据不存在！', '', 'error');
                }
            }
            if (checksubmit('submit')) {
                if (empty($_GPC['title'])) {
                    message('亲,游戏名称不能为空!');
                }
                if (empty($_GPC['url'])) {
                    message('亲,游戏地址不能为空!');
                }
                if (empty($_GPC['img'])) {
                    message('亲,游戏图标不能为空!');
                }
                $weid       = $_W['weid'];
                $title      = $_GPC['title'];
                $url        = $_GPC["url"];
                $img        = $_GPC['img'];
                $createtime = time();
                $isok       = $_GPC['isok'];
                $desc       = $_GPC['desc'];
                $category   = $_GPC['category'];
                $ist        = $_GPC['ist'];
                $data       = array(
                    'weid' => $weid,
                    'title' => $title,
                    'url' => $url,
                    'img' => $img,
                    'createtime' => $createtime,
                    'isok' => $isok,
                    'desc' => $desc,
                    'category' => $category,
                    'ist' => $ist
                );
                if (empty($id)) {
                    pdo_insert('hc_gameuu_game', $data);
                    message('游戏添加成功！', $this->createWebUrl('gamem', array(
                        'op' => 'display'
                    )), 'success');
                } else {
                    unset($data['createtime']);
                    pdo_update('hc_gameuu_game', $data, array(
                        'id' => $id
                    ));
                    message('游戏更新成功！', $this->createWebUrl('gamem', array(
                        'op' => 'display'
                    )), 'success');
                }
            } else {
                include $this->template('m');
            }
        } else if ('del' == $op) {
            $id  = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id FROM " . tablename('hc_gameuu_game') . " WHERE id = :id", array(
                ':id' => $id
            ));
            if (empty($row)) {
                message('亲,幻灯片' . $id . '不存在,不要乱动哦！');
            }
            pdo_delete('hc_gameuu_game', array(
                'id' => $id
            ));
            message('删除成功！', referer(), 'success');
        } else if ('display' == $op) {
            $pindex    = max(1, intval($_GPC['page']));
            $psize     = 20;
            $condition = '';
            $list      = pdo_fetchall("SELECT * FROM " . tablename('hc_gameuu_game') . " WHERE weid = '{$_W['weid']}' $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
            $total     = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('hc_gameuu_game') . " WHERE weid = '{$_W['weid']}'");
            $pager     = pagination($total, $pindex, $psize);
            include $this->template('m');
        }
    }
    public function doWebGameh()
    {
        global $_W, $_GPC;
        load()->func('tpl');
        $op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ('post' == $op) {
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename('hc_gameuu_game_img') . " WHERE id = :id", array(
                    ':id' => $id
                ));
                if (empty($item)) {
                    message('亲,数据不存在！', '', 'error');
                }
            }
            if (checksubmit('submit')) {
                if (empty($_GPC['img'])) {
                    message('亲,幻灯片不能为空!');
                }
                $weid  = $_W['weid'];
                $title = $_GPC['title'];
                $url   = $_GPC["url"];
                $img   = $_GPC['img'];
                $data  = array(
                    'weid' => $weid,
                    'title' => $title,
                    'url' => $url,
                    'img' => $img
                );
                if (empty($id)) {
                    pdo_insert('hc_gameuu_game_img', $data);
                    message('幻灯片添加成功！', $this->createWebUrl('gameh', array(
                        'op' => 'display'
                    )), 'success');
                } else {
                    pdo_update('hc_gameuu_game_img', $data, array(
                        'id' => $id
                    ));
                    message('幻灯片更新成功！', $this->createWebUrl('gameh', array(
                        'op' => 'display'
                    )), 'success');
                }
            } else {
                include $this->template('h');
            }
        } else if ('del' == $op) {
            $id  = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id FROM " . tablename('hc_gameuu_game_img') . " WHERE id = :id", array(
                ':id' => $id
            ));
            if (empty($row)) {
                message('亲,幻灯片' . $id . '不存在,不要乱动哦！');
            }
            pdo_delete('hc_gameuu_game_img', array(
                'id' => $id
            ));
            message('删除成功！', referer(), 'success');
        } else if ('display' == $op) {
            $pindex    = max(1, intval($_GPC['page']));
            $psize     = 20;
            $condition = '';
            $list      = pdo_fetchall("SELECT * FROM " . tablename('hc_gameuu_game_img') . " WHERE weid = '{$_W['weid']}' $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
            $total     = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('hc_gameuu_game_img') . " WHERE weid = '{$_W['weid']}'");
            $pager     = pagination($total, $pindex, $psize);
            include $this->template('h');
        }
    }
    public function doMobileAjaxn()
    {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        pdo_query('update ' . tablename('hc_gameuu_game') . ' set num=num+1 where id=:id', array(
            ':id' => $id
        ));
    }
    public function str_murl($url)
    {
        global $_W;
        return $_W['siteroot'] . 'app' . str_replace('./', '/', $url);
    }
}