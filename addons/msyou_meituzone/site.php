<?php
defined('IN_IA') or exit('Access Denied');
class Msyou_meituzoneModuleSite extends WeModuleSite
{
function tables_check($tablestr = '')
    {
        global $_W;
        require 'create_tables.php';
    }
    public function doWebreplydel()
    {
        global $_GPC, $_W;
        if ($_W['isajax']) {
            $error = "";
            foreach ($_GPC['idArr'] as $k => $id) {
                $rid = intval($id);
                if ($rid == 0)
                    continue;
                $rule = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and uniacid=:uniacid", array(
                    ':id' => $id,
                    ':uniacid' => $_W['uniacid']
                ));
                if (empty($rule)) {
                    $error = '抱歉，要修改的规则不存在或是已经被删除！';
                    break;
                }
                if (pdo_delete('rule', array(
                    'id' => $rid
                ))) {
                    pdo_delete('rule_keyword', array(
                        'rid' => $rid
                    ));
                    pdo_delete('stat_rule', array(
                        'rid' => $rid
                    ));
                    pdo_delete('stat_keyword', array(
                        'rid' => $rid
                    ));
                    $module = WeUtility::createModule($rule['module']);
                    if (method_exists($module, 'ruleDeleted')) {
                        $module->ruleDeleted($rid);
                    }
                }
            }
            $msg['error'] = $error;
            echo json_encode($msg);
            exit;
        }
        $rid  = intval($_GPC['rid']);
        $rule = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and uniacid=:uniacid", array(
            ':id' => $rid,
            ':uniacid' => $_W['uniacid']
        ));
        if (empty($rule)) {
            message('抱歉，要修改的规则不存在或是已经被删除！');
        }
        if (pdo_delete('rule', array(
            'id' => $rid
        ))) {
            pdo_delete('rule_keyword', array(
                'rid' => $rid
            ));
            pdo_delete('stat_rule', array(
                'rid' => $rid
            ));
            pdo_delete('stat_keyword', array(
                'rid' => $rid
            ));
            $module = WeUtility::createModule($rule['module']);
            if (method_exists($module, 'ruleDeleted')) {
                $module->ruleDeleted($rid);
            }
        }
        message('规则操作成功！', $this->createWebUrl('index'), 'success');
    }
    public function doWebIndex()
    {
        $this->tables_check();
        global $_GPC, $_W;
        $where              = "uniacid = :uniacid ";
        $params[':uniacid'] = $_W['uniacid'];
        if (isset($_GPC['keyword'])) {
            $where .= ' AND `title` LIKE :keyword';
            $params[':keyword'] = "%{$_GPC['keyword']}%";
        }
        $total  = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('msyou_meituzone_reply') . " WHERE " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize  = 12;
        $pager  = pagination($total, $pindex, $psize);
        $start  = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $reply = pdo_fetchall("SELECT * FROM " . tablename('msyou_meituzone_reply') . " WHERE " . $where . " ORDER BY id " . $limit, $params);
        load()->func('tpl');
        include $this->template('index');
    }
    public function doWebjoinlists()
    {
        global $_GPC, $_W;
        $where              = "uniacid = :uniacid and rid=:rid ";
        $params[':uniacid'] = $_W['uniacid'];
        $params[':rid']     = intval($_GPC['rid']);
        if (isset($_GPC['keyword'])) {
            $where2 = ' b.nickname LIKE "%{$_GPC["keyword"]}%" or b.mobile LIKE "%{$_GPC["keyword"]}%"';
        }
        $reply  = pdo_fetch("SELECT * FROM " . tablename('msyou_meituzone_reply') . " WHERE " . $where, $params);
        $bh     = "1";
        $pm     = "(select ifnull(count(1),0)+1 from " . tablename('msyou_meituzone_lists') . " WHERE zancount*" . $reply['zanx'] . "+sharecount*" . $reply['sharex'] . "+viewcount*" . $reply['viewx'] . ">a.zancount*" . $reply['zanx'] . "+a.sharecount*" . $reply['sharex'] . "+a.viewcount*" . $reply['viewx'] . " and rid=" . $_GPC['rid'] . ") pm ";
        $df     = "(zancount*" . $reply['zanx'] . "+sharecount*" . $reply['sharex'] . "+viewcount*" . $reply['viewx'] . ") sumcount ";
        $total  = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('msyou_meituzone_lists') . " WHERE " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize  = 12;
        $pager  = pagination($total, $pindex, $psize);
        $start  = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $lists = pdo_fetchall("select b.nickname, b.mobile, aa.* from (SELECT " . $bh . "," . $pm . "," . $df . ",a.* FROM " . tablename('msyou_meituzone_lists') . " a WHERE " . $where . ") aa left join " . tablename('mc_members') . " b on b.uid=aa.fanid ORDER BY aa.pm" . $limit, $params);
        load()->func('tpl');
        include $this->template('lists');
    }
    public function doWebdownload()
    {
        require_once 'download.php';
    }
    public function doWeblists_del()
    {
        global $_GPC, $_W;
        $lid = intval($_GPC['lid']);
        if (pdo_delete('msyou_meituzone_lists', array(
            'id' => $lid
        ))) {
            message('参与图片 删除成功！', $this->createWebUrl('joinlists', array(
                'rid' => $_GPC['rid']
            )), 'success');
        } else {
            message('参与图片 删除 失败！', $this->createWebUrl('joinlists', array(
                'rid' => $_GPC['rid']
            )), 'error');
        }
    }
    public function doWebjiang()
    {
        global $_GPC, $_W;
        $rid  = intval($_GPC['rid']);
        $lid  = intval($_GPC['lid']);
        $val  = intval($_GPC['val']);
        $temp = pdo_update('msyou_meituzone_lists', array(
            'jiang' => $val
        ), array(
            'id' => $lid,
            'rid' => $rid
        ));
        if ($temp) {
            $res["msg"]   = ($val ? "发奖" : "撤销") . "成功！";
            $res["error"] = '';
        } else {
            $res["msg"]   = ($val ? "发奖" : "撤销") . "失败！UPD:" . $temp . '|val:' . $val . '|lid:' . $lid . '|rid:' . $rid;
            $res["error"] = '1';
        }
        echo json_encode($res);
        exit;
    }
    public function doWebsetshow()
    {
        global $_GPC, $_W;
        $rid    = intval($_GPC['rid']);
        $isshow = intval($_GPC['isshow']);
        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $temp = pdo_update('msyou_meituzone_reply', array(
            'status' => $isshow
        ), array(
            'rid' => $rid
        ));
        message('状态设置成功！', $this->createWebUrl('index'), 'success');
    }
    public function doWebParaset()
    {
        $this->tables_check();
        global $_GPC, $_W;
        if ($_W['isajax']) {
            return '';
            exit;
        }
        $paraset = $this->get_sysset();
        if ($_W['ispost']) {
            if (checksubmit('submit')) {
                $data['filterkeyword'] = $_GPC['filterkeyword'];
                $data['resroot']       = $_GPC['resroot'];
                if (empty($paraset)) {
                    $data['uniacid']    = $_W['uniacid'];
                    $data['creater']    = $_W['username'];
                    $data['createtime'] = time();
                    pdo_insert('msyou_meituzone_paraset', $data);
                } else {
                    $data['editer']   = $_W['username'];
                    $data['edittime'] = time();
                    pdo_update('msyou_meituzone_paraset', $data, array(
                        'uniacid' => $_W['uniacid']
                    ));
                }
                message('状态设置成功！', '', 'success');
            }
        }
        load()->func('tpl');
        include $this->template('paraset');
    }
    public function doWebHelp()
    {
        $this->tables_check();
        global $_GPC, $_W;
        load()->func('tpl');
        include $this->template('help');
    }
    private function get_resroot()
    {
        global $_W;
        $set     = $this->get_sysset();
        $resroot = $set['resroot'];
        if (empty($resroot)) {
            $resroot = "../addons/msyou_meituzone/style/";
        } else {
            if (substr($resroot, strlen($resroot) - 1) !== '/') {
                $resroot .= "/";
            }
        }
        return $resroot;
    }
    private function get_sysset()
    {
        global $_W;
        return pdo_fetch("SELECT * FROM " . tablename('msyou_meituzone_paraset') . " WHERE uniacid = :uniacid", array(
            ':uniacid' => $_W['uniacid']
        ));
    }
    public function doMobileUploadimage()
    {
        global $_GPC, $_W;
        $setting = $_W['setting']['upload'][$type];
        $result  = array(
            'jsonrpc' => '2.0',
            'id' => 'id',
            'error' => array(
                'code' => 1,
                'message' => ''
            )
        );
        load()->func('file');
        if (empty($_FILES['file']['tmp_name'])) {
            $binaryfile = file_get_contents('php://input', 'r');
            if (!empty($binaryfile)) {
                mkdirs(ATTACHMENT_ROOT . '/temp');
                $tempfilename = random(5);
                $tempfile     = ATTACHMENT_ROOT . '/temp/' . $tempfilename;
                if (file_put_contents($tempfile, $binaryfile)) {
                    $imagesize      = @getimagesize($tempfile);
                    $imagesize      = explode('/', $imagesize['mime']);
                    $_FILES['file'] = array(
                        'name' => $tempfilename . '.' . $imagesize[1],
                        'tmp_name' => $tempfile,
                        'error' => 0
                    );
                }
            }
        }
        if (!empty($_FILES['file']['name'])) {
            if ($_FILES['file']['error'] != 0) {
                $result['error']['message'] = '上传失败，请重试！';
                die(json_encode($result));
            }
            $ext  = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $ext  = strtolower($ext);
            $file = file_upload($_FILES['file']);
            if (is_error($file)) {
                $result['error']['message'] = $file['message'];
                die(json_encode($result));
            }
            $pathname = $file['path'];
            $fullname = ATTACHMENT_ROOT . '/' . $pathname;
            $thumb    = empty($setting['thumb']) ? 0 : 1;
            $width    = intval($setting['width']);
            if ($thumb == 1 && $width > 0) {
                $thumbnail = file_image_thumb($fullname, '', $width);
                @unlink($fullname);
                if (is_error($thumbnail)) {
                    $result['message'] = $thumbnail['message'];
                    die(json_encode($result));
                } else {
                    $filename = pathinfo($thumbnail, PATHINFO_BASENAME);
                    $pathname = $thumbnail;
                    $fullname = ATTACHMENT_ROOT . '/' . $pathname;
                }
            }
            $info           = array(
                'name' => $_FILES['file']['name'],
                'ext' => $ext,
                'filename' => $pathname,
                'attachment' => $pathname,
                'url' => tomedia($pathname),
                'is_image' => 1,
                'filesize' => filesize($fullname)
            );
            $size           = getimagesize($fullname);
            $info['width']  = $size[0];
            $info['height'] = $size[1];
            setting_load('remote');
            if (!empty($_W['setting']['remote']['type'])) {
                $remotestatus = file_remote_upload($pathname);
                if (is_error($remotestatus)) {
                    $result['message'] = '远程附件上传失败，请检查配置并重新上传';
                    file_delete($pathname);
                    die(json_encode($result));
                } else {
                    file_delete($pathname);
                    $info['url'] = tomedia($pathname);
                }
            }
            $info['cc'] = $fullname;
            die(json_encode($info));
        } else {
            $result['error']['message'] = '请选择要上传的图片！';
            die(json_encode($result));
        }
    }
    public function doMobileIndex()
    {
        global $_GPC, $_W;
        load()->model('mc');
        $faninfo    = $_W['fans'];
        $memberinfo = mc_fetch($_W['member']['uid'], array(
            'nickname',
            'avatar',
            'mobile',
            'gender',
            'nationality',
            'resideprovince',
            'residecity',
            'nationality'
        ));
        if (!empty($faninfo)) {
            $memberinfo['avatar'] = $faninfo['tag']['avatar'];
            if (empty($memberinfo['nickname'])) {
                $memberinfo['nickname']       = $faninfo['tag']['nickname'];
                $memberinfo['gender']         = $faninfo['tag']['sex'];
                $memberinfo['nationality']    = $faninfo['tag']['country'];
                $memberinfo['resideprovince'] = $faninfo['tag']['province'];
                $memberinfo['residecity']     = $faninfo['tag']['city'];
            }
            mc_update($_W['member']['uid'], $memberinfo);
        }
        $fans               = array(
            5,
            24,
            30
        );
        $members            = mc_fetch($fans, array(
            'nickname',
            'avatar',
            'mobile',
            'gender',
            'nationality',
            'resideprovince',
            'residecity',
            'nationality'
        ));
        $where              = "uniacid = :uniacid and rid=:rid";
        $params[':uniacid'] = $_W['uniacid'];
        $params[':rid']     = $_GPC['rid'];
        $resroot            = $this->get_resroot();
        $reply              = pdo_fetch("SELECT * FROM " . tablename('msyou_meituzone_reply') . " WHERE " . $where, $params);
        $reply['startend']  = date('Y-m-d H:i', $reply['starttime']) . '至' . date('Y-m-d H:i', $reply['endtime']);
        $_share["title"]    = $reply["title"];
        $_share['desc']     = preg_replace('/\s/i', '', str_replace('	', '', cutstr(str_replace('&nbsp;', '', ihtmlspecialchars(strip_tags($reply["contact"]))), 60)));
        $_share["link"]     = $_W['siteroot'] . 'app/' . $this->createMobileUrl($_GPC['do'], array(
            'id' => $_GPC['id'],
            'rid' => $reply['rid']
        ), true);
        $_share['imgUrl']   = tomedia($reply["thumburl"]);
        $bh                 = "1";
        $pm                 = "(select ifnull(count(1),0)+1 from " . tablename('msyou_meituzone_lists') . " WHERE zancount*" . $reply['zanx'] . "+sharecount*" . $reply['sharex'] . "+viewcount*" . $reply['viewx'] . ">a.zancount*" . $reply['zanx'] . "+a.sharecount*" . $reply['sharex'] . "+a.viewcount*" . $reply['viewx'] . " and rid=" . $_GPC['rid'] . ") pm ";
        $df                 = "(zancount*" . $reply['zanx'] . "+sharecount*" . $reply['sharex'] . "+viewcount*" . $reply['viewx'] . ") sumcount ";
        if ($_W['isajax']) {
            $errstr = "";
            if ($reply) {
                if ($_GPC['share']) {
                    if ($_GPC['pageid']) {
                        pdo_query("update " . tablename('msyou_meituzone_lists') . " set sharecount=sharecount+1 WHERE " . $where . " and id=" . $_GPC['pageid'], $params);
                        $listcount = pdo_fetch("SELECT zancount,sharecount,viewcount FROM " . tablename('msyou_meituzone_lists') . " WHERE " . $where . " and id=" . $_GPC['pageid'], $params);
                        $listid    = $_GPC['pageid'];
                    }
                    pdo_query("update " . tablename('msyou_meituzone_reply') . " set sharecount=sharecount+1 WHERE " . $where, $params);
                }
                if ($_GPC['dianzan']) {
                    $zan = pdo_fetch("select * FROM " . tablename('msyou_meituzone_lists_log') . " where uniacid=" . $_W['uniacid'] . " and listsid=" . $_GPC['lid'] . " and uid=0" . $_W['member']['uid']);
                    if (!empty($zan)) {
                        $errstr = "曾经已点赞！";
                    } else {
                        $zandata['uniacid']    = $_W['uniacid'];
                        $zandata['uid']        = '0' . $_W['member']['uid'];
                        $zandata['listsid']    = $_GPC['lid'];
                        $zandata['createtime'] = time();
                        if (pdo_insert('msyou_meituzone_lists_log', $zandata)) {
                            pdo_query("update " . tablename('msyou_meituzone_lists') . " set zancount=zancount+1 WHERE " . $where . " and id=" . $_GPC['lid'], $params);
                            $listcount = pdo_fetch("SELECT zancount,sharecount,viewcount FROM " . tablename('msyou_meituzone_lists') . " WHERE " . $where . " and id=" . $_GPC['lid'], $params);
                            $listid    = $_GPC['lid'];
                            pdo_query("update " . tablename('msyou_meituzone_reply') . " set zancount=zancount+1 WHERE " . $where, $params);
                        } else {
                            $errstr = "点赞 错误！";
                        }
                    }
                }
                if ($_GPC['usersubmit']) {
                    $memberinfo['nickname'] = $_GPC['nickname'];
                    $memberinfo['mobile']   = $_GPC['mobile'];
                    if (!mc_update($_W['member']['uid'], $memberinfo)) {
                        $errstr = "个人信息更新失败！";
                    }
                }
                if ($_GPC['submit']) {
                    $content['imglists'] = explode(',', $_GPC['imglists']);
                    $content['content']  = $_GPC['content'];
                    $data['uniacid']     = $_W['uniacid'];
                    $data['rid']         = $_GPC['rid'];
                    $data['fanid']       = $_W['member']['uid'];
                    $data['imgurl']      = $_GPC['imglist1'];
                    $data['content']     = json_encode($content);
                    $data['createtime']  = time();
                    $data['bh']          = intval("0" . pdo_fetchcolumn("select max(bh) from " . tablename('msyou_meituzone_lists') . " WHERE " . $where, $params)) + 1;
                    if (pdo_insert('msyou_meituzone_lists', $data)) {
                        $list['id'] = pdo_insertid();
                        pdo_query("update " . tablename('msyou_meituzone_reply') . " set joincount=(select count(1) from " . tablename('msyou_meituzone_lists') . " where rid=" . $_GPC['rid'] . " ) WHERE " . $where, $params);
                    } else {
                        $errstr = "发送内容，存储失败！";
                    }
                }
                if ($_GPC['showmore']) {
                    if ($_GPC['searchstr']) {
                        $sstr = $_GPC['searchstr'];
                        $list = pdo_fetchall("select b.nickname,aa.* from (SELECT " . $bh . "," . $pm . "," . $df . ",a.* FROM " . tablename('msyou_meituzone_lists') . " a  WHERE " . $where . ") aa left join " . tablename('mc_members') . " b on b.uid=aa.fanid where b.nickname like '%" . $sstr . "%' or aa.bh='" . $sstr . "' ORDER BY aa.id", $params);
                        foreach ($list as $k => $v) {
                            $list[$k]['createtime'] = date('Y-m-d H:i', $v['createtime']);
                        }
                    } else {
                        $pindex   = max(1, intval($_GPC['pageIndex']));
                        $psize    = intval($_GPC['pageSize']);
                        $orderstr = $_GPC['orderstr'];
                        $start    = ($pindex - 1) * $psize;
                        $limit    = " LIMIT {$start},{$psize}";
                        $list     = pdo_fetchall("SELECT " . $bh . "," . $pm . "," . $df . ",a.* FROM " . tablename('msyou_meituzone_lists') . " a WHERE " . $where . " ORDER BY " . $orderstr . $limit, $params);
                        $fans     = array();
                        foreach ($list as $v) {
                            array_push($fans, $v['fanid']);
                        }
                        $members = mc_fetch($fans, array(
                            'nickname',
                            'avatar',
                            'mobile',
                            'gender',
                            'nationality',
                            'resideprovince',
                            'residecity',
                            'nationality'
                        ));
                        foreach ($list as $k => $v) {
                            $list[$k]['nickname']   = $members[$list[$k]['fanid']]['nickname'];
                            $list[$k]['avatar']     = $members[$list[$k]['fanid']]['avatar'];
                            $list[$k]['createtime'] = date('Y-m-d H:i', $v['createtime']);
                        }
                    }
                }
                if ($_GPC['showpage']) {
                    pdo_query("update " . tablename('msyou_meituzone_lists') . " set viewcount=viewcount+1 WHERE " . $where . " and id=" . $_GPC['lid'], $params);
                    pdo_query("update " . tablename('msyou_meituzone_reply') . " set viewcount=viewcount+1 WHERE " . $where, $params);
                    $list               = pdo_fetch("SELECT " . $bh . "," . $pm . "," . $df . ",a.* FROM " . tablename('msyou_meituzone_lists') . " a WHERE " . $where . " and id=" . $_GPC['lid'], $params);
                    $members            = mc_fetch($list['fanid'], array(
                        'nickname',
                        'avatar',
                        'mobile',
                        'gender',
                        'nationality',
                        'resideprovince',
                        'residecity',
                        'nationality'
                    ));
                    $list['nickname']   = $members['nickname'];
                    $list['avatar']     = $members['avatar'];
                    $list['createtime'] = date('Y-m-d H:i', $list['createtime']);
                    $_share["title"]    = ($list['nickname'] ? $list['nickname'] : '我') . ' 现在排名第' . $list['pm'] . '，快来帮我点赞吧！';
                    $_share['desc']     = preg_replace('/\s/i', '', str_replace('	', '', cutstr(str_replace('&nbsp;', '', ihtmlspecialchars(strip_tags($reply["contact"]))), 60)));
                    $_share["link"]     = $_W['siteroot'] . 'app/' . $this->createMobileUrl($_GPC['do'], array(
                        'id' => $_GPC['id'],
                        'rid' => $reply['rid'],
                        'pageid' => $list['id']
                    ), true);
                    $_share['imgUrl']   = tomedia($list["imgurl"]);
                }
            } else {
                $errstr = "活动不存在！";
            }
            $replycount = pdo_fetch("SELECT * FROM " . tablename('msyou_meituzone_reply') . " WHERE " . $where, $params);
            die(json_encode(array(
                "error" => $errstr,
                "list" => $list,
                "p_share" => $_share,
                "replycount" => $replycount,
                "listcount" => $listcount,
                "listid" => $listid
            )));
        }
        if (empty($_W['openid']) && empty($_GPC['fanid'])) {
            if ($_W['container'] != 'wechat') {
                echo '请在微信中打开！';
                exit;
            }
            $error = mc_oauth_userinfo();
        }
        load()->model('reply');
        $keyword = reply_keywords_search("rid=:rid and uniacid = :uniacid and type=:type", array(
            ":rid" => $_GPC['rid'],
            ":uniacid" => $_W['uniacid'],
            ":type" => 1
        ));
        load()->model('account');
        $acc          = WeAccount::create($_W['acid']);
        $access_token = $acc->getAccessToken();
        load()->func('tpl');
        include $this->template('index');
    }
    public function doMobilewxdemo()
    {
        global $_GPC, $_W;
        load()->func('tpl');
        load()->model('account');
        $acc          = WeAccount::create($_W['acid']);
        $access_token = $acc->getAccessToken();
        include $this->template('wxdemo');
    }
}