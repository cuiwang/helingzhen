<?php
defined('IN_IA') or exit('Access Denied');
class dayu_zhinvModuleSite extends WeModuleSite
{
    public function doMobileindex()
    {
        global $_GPC, $_W;
        $weid   = $_W['uniacid'];
        $reply  = pdo_fetch("SELECT * FROM " . tablename('dayu_zhinv_set') . " WHERE weid=:weid limit 1", array(
            ':weid' => $weid
        ));
        $follow = pdo_fetchcolumn("select follow from " . tablename('mc_mapping_fans') . " where openid=:openid and uniacid=:uniacid order by `fanid` desc", array(
            ":openid" => $from_user,
            ":uniacid" => $_W['uniacid']
        ));
        if ($follow == 0 && $activity['follow'] == 1) {
            if (!empty($activity['share_url'])) {
                header("HTTP/1.1 301 Moved Permanently");
                header('Location: ' . $activity['share_url'] . "");
                exit();
            }
            $isshare = 1;
            $running = false;
            message('请先关注公共号。');
        }
        $_share_img        = $_W['siteroot'] . './addons/dayu_zhinv/template/mobile/data/pics/icon.jpg';
        $_share['imgUrl']  = $_W['siteroot'] . './addons/dayu_zhinv/template/mobile/data/pics/icon.jpg';
        $_share['title']   = $reply['share_title'];
        $_share_content    = $reply['share_desc'];
        $_share['desc']    = $reply['share_desc'];
        $_share['content'] = $reply['share_desc'];
        include $this->template('index');
    }
    public function doWebSet()
    {
        global $_W, $_GPC;
        load()->func('tpl');
        $id    = intval($_GPC['id']);
        $weid  = $_W['uniacid'];
        $reply = pdo_fetch("SELECT * FROM " . tablename('dayu_zhinv_set') . " WHERE weid=:weid limit 1", array(
            ':weid' => $weid
        ));
        if (checksubmit()) {
            $data = array(
                'weid' => $weid,
                'title' => $_GPC['title'],
                'share_title' => $_GPC['share_title'],
                'share_desc' => $_GPC['share_desc'],
                'share_url' => $_GPC['share_url'],
                'share_txt' => $_GPC['share_txt'],
                'copyright' => $_GPC['copyright'],
                'follow' => intval($_GPC['follow'])
            );
            if (empty($reply)) {
                pdo_insert('dayu_zhinv_set', $data);
            } else {
                pdo_update('dayu_zhinv_set', $data, array(
                    'weid' => $weid
                ));
            }
            message('操作成功', $this->createWebUrl('set'));
        }
        if (empty($reply)) {
            $reply = array(
                'title' => '拯救织女',
                'share_title' => '拯救织女。',
                'share_desc' => '织女被王母娘娘劫走了.......快去拯救织女!!',
                'share_txt' => '拯救织女',
                'share_url' => $_W['siteroot'] . $this->createMobileUrl('list')
            );
        }
        include $this->template('set');
    }
}