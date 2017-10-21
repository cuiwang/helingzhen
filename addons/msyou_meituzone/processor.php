<?php
defined('IN_IA') or exit('Access Denied');
class Msyou_meituzoneModuleProcessor extends WeModuleProcessor
{
    public function respond()
    {
        global $_W;
        $content = $this->message['content'];
        if ($this->message['type'] == 'image') {
            $sql = "SELECT * FROM " . tablename('msyou_meituzone_reply') . " WHERE `status`=:status and `starttime`<=:stime and `endtime`>=:etime LIMIT 1";
            $row = pdo_fetch($sql, array(
                ':status' => 1,
                ':stime' => time(),
                ':etime' => time()
            ));
            if ($row == false) {
                return $this->respText("活动不存在...");
            } else {
                load()->func('communication');
                $image     = ihttp_get($this->message['picurl']);
                $dirstr    = ATTACHMENT_ROOT . '/msyou_meituzone/upfiles/' . $row['rid'] . '/' . $_W['member']['uid'];
                $listsdata = array();
                load()->func('file');
                if (mkdirs($dirstr)) {
                    $filename     = '/' . random(30) . '.jpg';
                    $fullfilename = $dirstr . $filename;
                    if (file_put_contents($fullfilename, $image['content'])) {
                        $listsdata['fanid']  = $_W['member']['uid'];
                        $listsdata['rid']    = $row['rid'];
                        $listsdata['imgurl'] = $_W['attachurl'] . '/msyou_meituzone/upfiles/' . $row['rid'] . '/' . $_W['member']['uid'] . $filename;
                        pdo_insert('msyou_meituzone_lists', $listsdata);
                        $listsdata['id'] = pdo_insertid();
                    }
                }
                if (!empty($listsdata['id'])) {
                    return $this->respNews(array(
                        'Title' => $row['title'] . "，点我完成参与!",
                        'Description' => strip_tags($row['contact']),
                        'PicUrl' => $listsdata['imgurl'],
                        'Url' => $this->createMobileUrl('index', array(
                            'id' => $row['id'],
                            'rid' => $row['rid'],
                            'lid' => $listsdata['id'],
                            'opt' => 'submit'
                        ), true)
                    ));
                } else {
                    return $this->respText('图片接收失败！请联系管理员...');
                    exit;
                }
            }
        } else {
            $rid = $this->rule;
            $sql = "SELECT * FROM " . tablename('msyou_meituzone_reply') . " WHERE `rid`=:rid LIMIT 1";
            $row = pdo_fetch($sql, array(
                ':rid' => $rid
            ));
            if ($row == false) {
                return $this->respText("活动不存在...");
            } else {
                $url     = $this->createMobileUrl('index', array(
                    'id' => $row['id'],
                    'rid' => $row['rid']
                ), true);
                $respval = array(
                    'Title' => $row['title'],
                    'Description' => strip_tags($row['detail']),
                    'PicUrl' => tomedia($row['thumburl']),
                    'Url' => $url
                );
                if ($row['status']) {
                    $bhnum = explode(':', $content);
                    if (!empty($bhnum[1])) {
                        $list = pdo_fetch("SELECT * FROM " . tablename('msyou_meituzone_lists') . " WHERE uniacid=:uniacid and rid=:rid and bh=:bh", array(
                            ":uniacid" => $_W['uniacid'],
                            ":rid" => $rid,
                            ":bh" => $bhnum[1]
                        ));
                        if (!empty($list)) {
                            $respval['Description'] = "快来帮 " . $list['bh'] . "号选手点赞吧！";
                            $respval['PicUrl']      = tomedia($list['imgurl']);
                            $respval['Url']         = $this->createMobileUrl('index', array(
                                'id' => $row['id'],
                                'rid' => $row['rid'],
                                'pageid' => $list['id']
                            ), true);
                        }
                    }
                    return $this->respNews($respval);
                } else {
                    return $this->respText("活动未开始...");
                }
            }
        }
    }
}