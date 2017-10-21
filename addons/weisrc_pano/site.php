<?php
/**
 * 360全景
 *
 * www.weisrc.com
 *
 * 作者:迷失卍国度
 *
 * qq : 15595755
 */
defined('IN_IA') or exit('Access Denied');
class weisrc_panoModuleSite extends WeModuleSite
{
    public function doMobileIndex()
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $rid = intval($_GPC['rid']);
        $list = pdo_fetchall('select * from ' . tablename('weisrc_pano_reply') . ' where weid=:weid', array(':weid' => $weid));

        $config = $this->module['config']['weisrc_pano'];
        $title = $config['title'];
        $bg = tomedia($config['bg']);
        $share_image = tomedia($config['share_image']);
        $share_title = empty($config['share_title']) ? $config['title'] : $config['share_title'];
        $share_desc = empty($config['share_desc']) ? $config['title'] : $config['share_desc'];
        $share_url = empty($setting['share_url']) ? $_W['siteroot'] . 'app/' . $this->createMobileUrl('index', array(), true) : $setting['share_url'];
        include $this->template('index');
    }

    public function doWebManage() {
        global $_GPC, $_W;
        load()->model('reply');
        $pindex = max(1, intval($_GPC['page']));
        $psize = 10;
        $sql = "uniacid = :uniacid AND `module` = :module";
        $params = array();
        $params[':uniacid'] = $_W['uniacid'];
        $params[':module'] = 'weisrc_pano';

        if (isset($_GPC['keywords'])) {
            $sql .= ' AND `name` LIKE :keywords';
            $params[':keywords'] = "%{$_GPC['keywords']}%";
        }

        $list = reply_search($sql, $params, $pindex, $psize, $total);
        $pager = pagination($total, $pindex, $psize);

        if (!empty($list)) {
            foreach ($list as &$item) {
                $condition = "`rid`={$item['id']}";
                $item['keywords'] = reply_keywords_search($condition);
                $reply = pdo_fetch("SELECT * FROM " . tablename('weisrc_pano_reply') . " WHERE rid = :rid ", array(':rid' => $item['id']));
                $item['dateline'] = date('Y-m-d H:i', $reply['dateline']);
            }
        }
        include $this->template('manage');
    }

    public function doWebdelete() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $rule = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and uniacid=:uniacid", array(':id' => $rid, ':uniacid' => $_W['uniacid']));
        if (empty($rule)) {
            message('抱歉，要修改的规则不存在或是已经被删除！');
        }
        if (pdo_delete('rule', array('id' => $rid))) {
            pdo_delete('rule_keyword', array('rid' => $rid));
            //删除统计相关数据
            pdo_delete('stat_rule', array('rid' => $rid));
            pdo_delete('stat_keyword', array('rid' => $rid));
        }
        message('规则操作成功！', $this->createWebUrl('manage', array('op' => 'display')), 'success');
    }

    public function doMobileView()
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $rid = intval($_GPC['rid']);
        $reply = pdo_fetch('select * from ' . tablename('weisrc_pano_reply') . ' where rid=:rid', array(':rid' => $rid));
        $config = $this->module['config']['weisrc_pano'];
        $share_title = empty($reply['title']) ? $config['title'] : $reply['title'];
        $share_desc = empty($config['share_desc']) ? $config['title'] : $config['share_desc'];
        $share_image = tomedia($config['share_image']);
        $share_url = $_W['siteroot'] . "app/index.php?i={$weid}&c=entry&rid={$rid}&do=view&m=weisrc_pano";
        include $this->template('view');
    }

    public function doMobilegetimagexml()
    {
        global $_GPC, $_W;
        header('Content-Type: text/xml;');
        $rid = intval($_GPC['rid']);
        $type = 2;
        $attachurl = $_W['attachurl'];
        $reply = pdo_fetch('select * from ' . tablename('weisrc_pano_reply') . ' where rid=:rid', array(':rid' => $rid));
        $remote_url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('GetRemoteImg', array(), true) . '&url=';

        if ($reply['type'] == -1) {
            $outputstr = '
            <panorama id="">
            <view fovmode="0" pannorth="0">
                <start pan="0" fov="70" tilt="0"/>
                    <min pan="0" fov="5" tilt="-90"/>
                    <max pan="360" fov="120" tilt="90"/>
            </view>
            <userdata title="360view" datetime="'.TIMESTAMP.'" description="description" copyright="copyright" tags="tags" author="author" source="source" comment="comment" info="info" longitude="0" latitude=""/>
            <media/><input ';
            $outputstr .= '
                tile0url="' . tomedia($reply['picture1']) . '"
                tile1url="' . tomedia($reply['picture2']) . '"
                tile2url="' . tomedia($reply['picture3']) . '"
                tile3url="' . tomedia($reply['picture4']) . '"
                tile4url="' . tomedia($reply['picture5']) . '"
                tile5url="' . tomedia($reply['picture6']) . '"
                tilesize="685"
                tilescale="1.0"/>
            <autorotate speed="0.200" nodedelay="0.00" startloaded="1" returntohorizon="0.000" delay="5.00"/>
            <control simulatemass="1" lockedmouse="0" lockedkeyboard="0" dblclickfullscreen="0" invertwheel="0" lockedwheel="0" invertcontrol="1" speedwheel="1" sensitivity="8"/>
</panorama>
        ';
        } else {
            if (!empty($reply))
            {
                $type = $reply['type'];
            }

            $outputstr = '
                <panorama id="">
                <view fovmode="0" pannorth="0">
                    <start pan="0" fov="70" tilt="0"/>
                    <min pan="0" fov="5" tilt="-90"/>
                    <max pan="360" fov="120" tilt="90"/>
                </view>
                <userdata title="pano" datetime="'.TIMESTAMP.'" description="description" copyright="copyright" tags="tags" author="author" source="source" comment="comment" info="info" longitude="0" latitude=""/>
<media/>';
            $outputstr .= '<input tile0url="../addons/weisrc_pano/template/images/' . $type . '/1.jpeg"
                    tile1url="../addons/weisrc_pano/template/images/' . $type . '/2.jpeg"
                    tile2url="../addons/weisrc_pano/template/images/' . $type . '/3.jpeg"
                    tile3url="../addons/weisrc_pano/template/images/' . $type . '/4.jpeg"
                    tile4url="../addons/weisrc_pano/template/images/' . $type . '/5.jpeg"
                    tile5url="../addons/weisrc_pano/template/images/' . $type . '/6.jpeg"
                    tilesize="685"
                    tilescale="1.014598540145985"/>
                    <autorotate speed="0.200" nodedelay="0.00" startloaded="1" returntohorizon="0.000" delay="5.00"/>
                    <control simulatemass="1" lockedmouse="0" lockedkeyboard="0" dblclickfullscreen="0" invertwheel="0" lockedwheel="0" invertcontrol="1" speedwheel="1" sensitivity="8"/>
                </panorama>';
        }
        echo $outputstr;
    }

    public function doMobileGetRemoteImg()
    {
        global $_W, $_GPC;
        $url = $_GPC['url'];
        header("content-type:image/png");
        $result = file_get_contents($url);
        echo $result;
    }
}