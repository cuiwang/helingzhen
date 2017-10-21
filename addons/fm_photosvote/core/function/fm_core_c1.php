
<?php
defined('IN_IA') or exit('Access Denied');
class FmCoreC1 extends Core
{
    public function wxdwz($longurl)
    {
        $token = $this->gettoken();
        if (is_error($token)) {
            return $token;
        }
        $url              = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token={$token}";
        $send             = array();
        $send['action']   = 'long2short';
        $send['long_url'] = $longurl;
        $response         = ihttp_request($url, json_encode($send));
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty($result)) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty($result['errcode'])) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']}");
        }
        return $result;
    }
    public function qrcodecreate($barcode)
    {
        $barcode                   = iunserializer(base64_decode($barcode));
        $barcode['expire_seconds'] = empty($barcode['expire_seconds']) ? 2592000 : $barcode['expire_seconds'];
        if (empty($barcode['action_info']['scene']['scene_id']) || empty($barcode['action_name'])) {
            return error('1', 'Invalid params');
        }
        $token    = $this->gettoken();
        $response = ihttp_request("https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=" . $token, json_encode($barcode));
        if (is_error($response)) {
            return $response;
        }
        $content = @json_decode($response['content'], true);
        if (empty($content)) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        }
        if (!empty($content['errcode'])) {
            return error(-1, "访问微信接口错误, 错误代码: {$content['errcode']}, 错误信息: {$content['errmsg']}");
        }
        return $content;
    }
    public function getData($page)
    {
        global $_W;
        if (!empty($page['datas'])) {
            $data     = htmlspecialchars_decode($page['datas']);
            $d        = json_decode($data, true);
            $usersids = array();
            foreach ($d as $k1 => &$dd) {
                if ($dd['temp'] == 'photosvote') {
                    foreach ($dd['data'] as $k2 => $ddd) {
                        $usersids[] = array(
                            'id' => $ddd['usersid'],
                            'k1' => $k1,
                            'k2' => $k2
                        );
                    }
                } elseif ($dd['temp'] == 'tongji') {
                    $tj                         = pdo_fetch("SELECT csrs_total,ljtp_total,xunips,cyrs_total,xuninum FROM " . tablename($this->table_reply_display) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                        ':rid' => $dd['params']['rid']
                    ));
                    $dd['params']['tongjicszp'] = $tj['csrs_total'];
                    $dd['params']['tongjiljtp'] = $tj['ljtp_total'] + $tj['xunips'];
                    $dd['params']['tongjicyrs'] = $tj['cyrs_total'] + $tj['xuninum'];
                } elseif ($dd['temp'] == 'richtext') {
                    $dd['content'] = $this->unescape($dd['content']);
                }
            }
            unset($dd);
            $arr = array();
            foreach ($usersids as $a) {
                $arr[] = $a['id'];
            }
            if (count($arr) > 0) {
                $usersinfo = pdo_fetchall("SELECT id,rid,from_user,nickname,realname,uid,avatar,photosnum,hits,xnphotosnum,xnhits,sharenum FROM " . tablename($this->table_users) . " WHERE id in ( " . implode(',', $arr) . ") AND uniacid= :uniacid AND status=:status AND rid =:rid ORDER BY uid ASC", array(
                    ':uniacid' => $_W['uniacid'],
                    ':status' => '1',
                    ':rid' => $rid
                ), 'id');
                $usersinfo = $this->set_medias($usersinfo, 'avatar');
                foreach ($d as $k1 => &$dd) {
                    if ($dd['temp'] == 'pusers') {
                        foreach ($dd['data'] as $k2 => &$ddd) {
                            $cdata            = $usersinfo[$ddd['usersid']];
                            $fmimage          = $this->getpicarr($_W['uniacid'], $rid, $cdata['from_user'], 1);
                            $fengmian         = $this->getphotos($cdata['avatar'], $fmimage['photos'], $rbasic['picture']);
                            $ddd['name']      = !empty($cdata['nickname']) ? $cdata['nickname'] : $cdata['realname'];
                            $ddd['uid']       = $cdata['uid'];
                            $ddd['from_user'] = $cdata['from_user'];
                            $ddd['piaoshu']   = $cdata['photosnum'] + $cdata['xnphotosnum'];
                            $ddd['img']       = $fengmian;
                            $ddd['renqi']     = $cdata['hits'] + $cdata['xnhits'];
                            $ddd['sharenum']  = $cdata['sharenum'];
                        }
                        unset($ddd);
                    }
                }
                unset($dd);
            }
            $data = json_encode($d);
            $data = rtrim($data, "]");
            $data = ltrim($data, "[");
        }
        $pageinfo       = htmlspecialchars_decode($page['pageinfo']);
        $p              = json_decode($pageinfo, true);
        $page_title     = empty($p[0]['params']['title']) ? "未设置页面标题" : $p[0]['params']['title'];
        $page_desc      = empty($p[0]['params']['desc']) ? "未设置页面简介" : $p[0]['params']['desc'];
        $page_img       = empty($p[0]['params']['img']) ? "" : tomedia($p[0]['params']['img']);
        $page_keyword   = empty($p[0]['params']['kw']) ? "" : $p[0]['params']['kw'];
        $vote_anname    = empty($p[0]['params']['voteanname']) ? "投Ta一票" : $p[0]['params']['voteanname'];
        $vote_title     = empty($p[0]['params']['votetitle']) ? "未设置投票区标题" : $p[0]['params']['votetitle'];
        $vote_cansaizhe = empty($p[0]['params']['cansaizhe']) ? "" : $p[0]['params']['cansaizhe'];
        $shopset        = array(
            'name' => '女神来了',
            'logo' => '11'
        );
        $users          = $this->getMember($from_user);
        $system         = array(
            'photosvote' => array(
                'sharephoto' => $rshare['sharephoto'],
                'shareurl' => $rshare['shareurl'],
                'sharetitle' => $rshare['sharetitle']
            )
        );
        $system         = json_encode($system);
        $vote           = array(
            'voteanname' => $vote_anname,
            'votetitle' => $vote_title,
            'votecansaizhe' => $vote_cansaizhe
        );
        $vote           = json_encode($vote);
        $pageinfo       = rtrim($pageinfo, "]");
        $pageinfo       = ltrim($pageinfo, "[");
        $ret            = array(
            'page' => $page,
            'pageinfo' => $pageinfo,
            'data' => $data,
            'share' => array(
                'title' => $page_title,
                'desc' => $page_desc,
                'imgUrl' => $page_img
            ),
            'vote_set' => array(
                'voteanname' => $vote_anname,
                'votetitle' => $vote_title,
                'votecansaizhe' => $vote_cansaizhe
            ),
            'footertype' => intval($p[0]['params']['footer']),
            'footermenu' => intval($p[0]['params']['footermenu']),
            'system' => $system,
            'vote' => $vote
        );
        if ($p[0]['params']['footer'] == 2) {
            $menuid = intval($p[0]['params']['footermenu']);
            $menu   = pdo_fetch('select * from ' . tablename('fm_photosvote_templates_designer_menu') . ' where id=:id and uniacid=:uniacid limit 1', array(
                ':id' => $menuid,
                ':uniacid' => $_W['uniacid']
            ));
            if (!empty($menu)) {
                $ret['menus']  = json_decode($menu['menus'], true);
                $ret['params'] = json_decode($menu['params'], true);
            }
        }
        return $ret;
    }
    public function escape($str)
    {
        preg_match_all("/[\xc2-\xdf][\x80-\xbf]+|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}|[\x01-\x7f]+/e", $str, $r);
        $str = $r[0];
        $l   = count($str);
        for ($i = 0; $i < $l; $i++) {
            $value = ord($str[$i][0]);
            if ($value < 223) {
                $str[$i] = rawurlencode(utf8_decode($str[$i]));
            } else {
                $str[$i] = "%u" . strtoupper(bin2hex(iconv("UTF-8", "UCS-2", $str[$i])));
            }
        }
        return join("", $str);
    }
    public function unescape($str)
    {
        $ret = '';
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            if ($str[$i] == '%' && $str[$i + 1] == 'u') {
                $val = hexdec(substr($str, $i + 2, 4));
                if ($val < 0x7f)
                    $ret .= chr($val);
                else if ($val < 0x800)
                    $ret .= chr(0xc0 | ($val >> 6)) . chr(0x80 | ($val & 0x3f));
                else
                    $ret .= chr(0xe0 | ($val >> 12)) . chr(0x80 | (($val >> 6) & 0x3f)) . chr(0x80 | ($val & 0x3f));
                $i += 5;
            } else if ($str[$i] == '%') {
                $ret .= urldecode(substr($str, $i, 3));
                $i += 2;
            } else
                $ret .= $str[$i];
        }
        return $ret;
    }
    public function is_array2($array)
    {
        if (is_array($array)) {
            foreach ($array as $k => $v) {
                return is_array($v);
            }
            return false;
        }
        return false;
    }
    public function set_medias($list = array(), $fields = null)
    {
        if (empty($fields)) {
            foreach ($list as &$row) {
                $row = tomedia($row);
            }
            return $list;
        }
        if (!is_array($fields)) {
            $fields = explode(',', $fields);
        }
        if ($this->is_array2($list)) {
            foreach ($list as $key => &$value) {
                foreach ($fields as $field) {
                    if (is_array($value) && isset($value[$field])) {
                        $value[$field] = tomedia($value[$field]);
                    }
                }
            }
            return $list;
        } else {
            foreach ($fields as $field) {
                if (isset($list[$field])) {
                    $list[$field] = tomedia($list[$field]);
                }
            }
            return $list;
        }
    }
    public function save_media($url)
    {
        return $url;
    }
    public function getGuide($system, $pageinfo)
    {
        global $_W, $_GPC;
        if (!empty($_GPC['preview'])) {
            $guide['followed'] = '0';
        } else {
            $guide['openid2']  = $from_user;
            $guide['followed'] = $follow;
        }
        if ($guide['followed'] != '1') {
            $system               = json_decode($system, true);
            $system['photosvote'] = $this->set_medias($system['photosvote'], 'sharephoto');
            $pageinfo             = json_decode($pageinfo, true);
            if (!empty($_GPC['mid'])) {
                $guide['member1'] = pdo_fetch("SELECT uid,nickname,from_user,avatar FROM " . tablename($this->table_users) . " WHERE from_user=:from_user and rid= :rid limit 1 ", array(
                    ':rid' => $rid,
                    ':from_user' => $fromuser
                ));
            }
            $guide['shareurl'] = $system['photosvote']['shareurl'];
            if (empty($guide['member1'])) {
                $guide['title1'] = $pageinfo['params']['guidetitle1'];
                $guide['title2'] = $pageinfo['params']['guidetitle2'];
                $guide['logo']   = $system['photosvote']['sharephoto'];
            } else {
                $pageinfo['params']['guidetitle1s'] = str_replace("[邀请人]", $guide['member1']['nickname'], $pageinfo['params']['guidetitle1s']);
                $pageinfo['params']['guidetitle2s'] = str_replace("[邀请人]", $guide['member1']['nickname'], $pageinfo['params']['guidetitle2s']);
                $pageinfo['params']['guidetitle1s'] = str_replace("[访问者]", $nickname, $pageinfo['params']['guidetitle1s']);
                $pageinfo['params']['guidetitle2s'] = str_replace("[访问者]", $nickname, $pageinfo['params']['guidetitle2s']);
                $guide['title1']                    = $pageinfo['params']['guidetitle1s'];
                $guide['title2']                    = $pageinfo['params']['guidetitle2s'];
                $guide['logo']                      = $guide['member1']['avatar'];
            }
        }
        return $guide;
    }
    public function GetFooter($id)
    {
        global $_W;
        $footer           = array();
        $menu             = pdo_fetch('select * from ' . tablename($this->table_designer_menu) . ' where id =:id LIMIT 1', array(
            ':id' => $id
        ));
        $footer['menus']  = $menu['menus'];
        $footer['params'] = $menu['params'];
        return $footer;
    }
    public function GetMenuname($id)
    {
        $menu = pdo_fetch('select menuname from ' . tablename($this->table_designer_menu) . ' where id=:id', array(
            ':id' => $id
        ));
        return $menu['menuname'];
    }
    function get_share($uniacid, $rid, $from_user, $title)
    {
        if (!empty($rid)) {
            $reply     = pdo_fetch("SELECT xuninum,hits FROM " . tablename($this->table_reply_display) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
            $csrs      = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_users) . " WHERE rid= " . $rid . "");
            $listtotal = $csrs + $reply['hits'] + pdo_fetchcolumn("SELECT sum(hits) FROM " . tablename($this->table_users) . " WHERE rid= " . $rid . "") + pdo_fetchcolumn("SELECT sum(xnhits) FROM " . tablename($this->table_users) . " WHERE rid= " . $rid . "") + $reply['xuninum'];
            $ljtp      = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_log) . " WHERE rid= " . $rid . "") + pdo_fetchcolumn("SELECT sum(xnphotosnum) FROM " . tablename($this->table_users) . " WHERE rid= " . $rid . "");
        }
        if (!empty($from_user)) {
            $userinfo = pdo_fetch("SELECT uid, nickname,realname FROM " . tablename($this->table_users) . " WHERE rid= :rid AND from_user= :from_user", array(
                ':rid' => $rid,
                ':from_user' => $from_user
            ));
            $nickname = empty($userinfo['realname']) ? $userinfo['nickname'] : $userinfo['realname'];
            $userid   = $userinfo['uid'];
        }
        $str    = array(
            '#编号#' => $userid,
            '#参赛人数#' => $csrs,
            '#参与人数#' => $listtotal,
            '#参与人名#' => $nickname,
            '#累计票数#' => $ljtp
        );
        $result = strtr($title, $str);
        return $result;
    }
    public function gettagname($tagid, $tagpid, $tagtid, $rid)
    {
        if (empty($tagid) && empty($tagpid) && empty($tagtid)) {
            return '全部分组';
        }
        if ($tagid && $tagpid && $tagtid) {
            $tagt = pdo_fetch("SELECT title,parentid FROM " . tablename($this->table_tags) . " WHERE id = :id AND rid = :rid ORDER BY id DESC", array(
                ':id' => $tagtid,
                ':rid' => $rid
            ));
            $tag  = pdo_fetch("SELECT title,parentid FROM " . tablename($this->table_tags) . " WHERE id = :id AND rid = :rid ORDER BY id DESC", array(
                ':id' => $tagid,
                ':rid' => $rid
            ));
            $tagf = pdo_fetch("SELECT title FROM " . tablename($this->table_tags) . " WHERE id = :id AND rid = :rid ORDER BY id DESC", array(
                ':id' => $tag['parentid'],
                ':rid' => $rid
            ));
            return $tagf['title'] . ' --- ' . $tag['title'] . ' --- ' . $tagt['title'];
        }
        if ($tagid && $tagpid && !$tagtid) {
            $tag  = pdo_fetch("SELECT title,parentid FROM " . tablename($this->table_tags) . " WHERE id = :id AND rid = :rid ORDER BY id DESC", array(
                ':id' => $tagid,
                ':rid' => $rid
            ));
            $tagf = pdo_fetch("SELECT title FROM " . tablename($this->table_tags) . " WHERE id = :id AND rid = :rid ORDER BY id DESC", array(
                ':id' => $tag['parentid'],
                ':rid' => $rid
            ));
            return $tagf['title'] . ' --- ' . $tag['title'];
        }
        if ($tagpid && !$tagid && !$tagtid) {
            $tagf = pdo_fetch("SELECT title FROM " . tablename($this->table_tags) . " WHERE id = :id AND rid = :rid ORDER BY id DESC", array(
                ':id' => $tagpid,
                ':rid' => $rid
            ));
            return $tagf['title'];
        }
        return '默认分组';
    }
    public function GetPaihangcha($rid, $tfrom_user, $indexpx = '')
    {
        global $_W;
        $date = array();
        if ($indexpx == '2') {
            $order = " ORDER BY `hits` + `xnhits` DESC, `photosnum` + `xnphotosnum` DESC";
        } else {
            $order = " ORDER BY `photosnum` + `xnphotosnum` DESC , `hits` + `xnhits` DESC ";
        }
        $ranks = pdo_fetchall('SELECT photosnum, xnphotosnum,from_user,hits,xnhits  FROM ' . tablename($this->table_users) . ' WHERE status =1 AND rid = ' . $rid . $order . '');
        foreach ($ranks as $key => $value) {
            if ($value['from_user'] == $tfrom_user) {
                $rank = $key + 1;
                if ($indexpx == '2') {
                    $piaoshu = $value['hits'] + $value['xnhits'];
                } else {
                    $piaoshu = $value['photosnum'] + $value['xnphotosnum'];
                }
                $qkey = $key - 1;
                $akey = $key + 1;
                break;
            }
        }
        $date['rank'] = $rank;
        if ($indexpx == '2') {
            if ($rank == 1) {
                $apiaoshu = $ranks[$akey]['hits'] + $ranks[$akey]['xnhits'];
            } else {
                $qpiaoshu    = $ranks[$qkey]['hits'] + $ranks[$qkey]['xnhits'];
                $apiaoshu    = $ranks[$akey]['hits'] + $ranks[$akey]['xnhits'];
                $date['qps'] = $qpiaoshu - $piaoshu;
            }
            $date['aps'] = $piaoshu - $apiaoshu;
        } else {
            if ($rank == 1) {
                $apiaoshu = $ranks[$akey]['photosnum'] + $ranks[$akey]['xnphotosnum'];
            } else {
                $qpiaoshu    = $ranks[$qkey]['photosnum'] + $ranks[$qkey]['xnphotosnum'];
                $apiaoshu    = $ranks[$akey]['photosnum'] + $ranks[$akey]['xnphotosnum'];
                $date['qps'] = $qpiaoshu - $piaoshu;
            }
            $date['aps'] = $piaoshu - $apiaoshu;
        }
        return $date;
    }
    public function _getip($rid, $ip, $uniacid = '')
    {
        global $_GPC, $_W;
        $iparrs = pdo_fetch("SELECT iparr FROM " . tablename($this->table_log) . " WHERE rid = :rid and ip = :ip ", array(
            ':rid' => $rid,
            ':ip' => $ip
        ));
        $iparr  = iunserializer($iparrs['iparr']);
        return $iparr;
    }
    public function isvoteok($ordersn, $rid)
    {
        $loguser = pdo_fetch("SELECT from_user FROM " . tablename($this->table_log) . " WHERE rid = :rid and ordersn = :ordersn ORDER BY id DESC LIMIT 1", array(
            ':rid' => $rid,
            ':ordersn' => $ordersn
        ));
        return $loguser;
    }
    public function _getloguser($rid, $from_user, $tfrom_user = '', $type = '')
    {
        if ($type == 'all') {
            $loguser = pdo_fetchall("SELECT tfrom_user, createtime,vote_times,is_del FROM " . tablename($this->table_log) . " WHERE rid = :rid and from_user = :from_user and tfrom_user = :tfrom_user ORDER BY createtime DESC", array(
                ':rid' => $rid,
                ':from_user' => $from_user,
                ':tfrom_user' => $tfrom_user
            ));
            foreach ($loguser as $key => $value) {
                $loguser[$key]['createtime'] = date('Y-m-d h:i:s', $value['createtime']);
            }
        } else {
            $loguser = pdo_fetch("SELECT nickname, avatar FROM " . tablename($this->table_log) . " WHERE rid = :rid and from_user = :from_user ORDER BY id DESC LIMIT 1", array(
                ':rid' => $rid,
                ':from_user' => $from_user
            ));
        }
        return $loguser;
    }
    public function gettvotes($rid, $from_user, $indexpx)
    {
        $r     = array();
        $votes = pdo_fetchall("SELECT tfrom_user FROM " . tablename($this->table_log) . " WHERE from_user = :from_user AND rid = :rid GROUP BY tfrom_user ORDER BY createtime DESC", array(
            ':from_user' => $from_user,
            ':rid' => $rid
        ));
        foreach ($votes as $key => $value) {
            $r[$key]['tfrom_user'] = $value['tfrom_user'];
            $r[$key]['tuser']      = $this->_getuser($rid, $value['tfrom_user']);
            $r[$key]['username']   = $this->getusernames($r[$key]['tuser']['realname'], $r[$key]['tuser']['nickname'], '10');
            $r[$key]['tfmimage']   = $this->getpicarr($uniacid, $rid, $value['tfrom_user'], 1);
            $r[$key]['avatar']     = $this->getphotos($r[$key]['tfmimage']['photos'], $r[$key]['tuser']['avatar'], 'addons/fm_photosvote/static/mobile/public/images/no-avatar.png');
            $r[$key]['votenum']    = $this->getvotes($rid, $from_user, $value['tfrom_user'], 'from_user_tfrom_user');
            $r[$key]['paihang']    = $this->GetPaihangcha($rid, $value['tfrom_user'], $rvote['indexpx']);
        }
        return $r;
    }
    public function _getuser($rid, $tfrom_user, $uniacid = '')
    {
        global $_GPC, $_W;
        return pdo_fetch("SELECT uid, avatar, nickname, realname, sex, mobile FROM " . tablename($this->table_users) . " WHERE rid = :rid and from_user = :tfrom_user ", array(
            ':rid' => $rid,
            ':tfrom_user' => $tfrom_user
        ));
    }
    public function getMember($from_user)
    {
        global $_GPC, $_W;
        return pdo_fetch("SELECT * FROM " . tablename($this->table_users) . " WHERE from_user = :from_user ORDER BY id DESC LIMIT 1", array(
            ':from_user' => $from_user
        ));
    }
    public function _auser($rid, $afrom_user, $uniacid = '')
    {
        global $_GPC, $_W;
        load()->model('mc');
        $auser = pdo_fetch("SELECT avatar, nickname FROM " . tablename($this->table_users) . " WHERE rid = :rid and from_user = :afrom_user ", array(
            ':rid' => $rid,
            ':afrom_user' => $afrom_user
        ));
        if (empty($auser)) {
            $auser = pdo_fetch("SELECT avatar, nickname FROM " . tablename($this->table_data) . " WHERE rid = :rid and from_user = :afrom_user ", array(
                ':rid' => $rid,
                ':afrom_user' => $afrom_user
            ));
            if (empty($auser)) {
                $auser = mc_fansinfo($row['afrom_user']);
            }
        }
        return $auser;
    }
    public function getsharenum($uniacid, $tfrom_user, $rid, $sharenum)
    {
        global $_W, $_GPC;
        return pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_data) . " WHERE tfrom_user = :tfrom_user and rid = :rid", array(
            ':tfrom_user' => $tfrom_user,
            ':rid' => $rid
        )) + pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_data) . " WHERE fromuser = :fromuser and rid = :rid", array(
            ':fromuser' => $tfrom_user,
            ':rid' => $rid
        )) + $sharenum;
    }
    public function gettagtitle($tagid, $rid)
    {
        $tags = pdo_fetch("SELECT title FROM " . tablename($this->table_tags) . " WHERE rid = :rid AND id = :id ORDER BY id DESC", array(
            ':rid' => $rid,
            ':id' => $tagid
        ));
        return $tags['title'];
    }
    public function getpicarr($uniacid, $rid, $from_user, $isfm = 0)
    {
        if ($isfm == 1) {
            $photo = pdo_fetch("SELECT photos,photoname,imgpath FROM " . tablename($this->table_users_picarr) . " WHERE from_user = :from_user AND rid = :rid AND isfm = :isfm LIMIT 1", array(
                ':from_user' => $from_user,
                ':rid' => $rid,
                ':isfm' => $isfm
            ));
        } else {
            $photo = pdo_fetch("SELECT photos,photoname,imgpath FROM " . tablename($this->table_users_picarr) . " WHERE from_user = :from_user AND rid = :rid ORDER BY createtime DESC LIMIT 1", array(
                ':from_user' => $from_user,
                ':rid' => $rid
            ));
        }
        return $photo;
    }
    public function getphotos($photo, $avatar, $picture, $is = '')
    {
        if ($is) {
            if (!empty($avatar)) {
                $photos = tomedia($avatar);
            } elseif (!empty($photo)) {
                $photos = tomedia($photo);
            } else {
                $photos = tomedia($picture);
            }
        } else {
            if (!empty($photo)) {
                $photos = tomedia($photo);
            } elseif (!empty($avatar)) {
                $photos = tomedia($avatar);
            } else {
                $photos = tomedia($picture);
            }
        }
        return $photos;
    }
    public function getusernames($realname, $nickname, $limit = '6', $from_user = '')
    {
        if (!empty($realname)) {
            $name = cutstr($realname, $limit);
        } elseif (!empty($nickname)) {
            $name = cutstr($nickname, $limit);
        } elseif (!empty($from_user)) {
            $name = cutstr($from_user, $limit);
        } else {
            $name = '网友';
        }
        return $name;
    }
    public function getname($rid, $from_user, $limit = '20', $type = 'name')
    {
        load()->model('mc');
        if ($type == 'avatar') {
            $username = $this->_getuser($rid, $from_user);
            $avatar   = tomedia($username['avatar']);
            if (empty($avatar)) {
                $username = $this->gettpinfo($rid, $from_user);
                $avatar   = tomedia($username['avatar']);
                if (empty($avatar)) {
                    $username = mc_fansinfo($from_user);
                    $avatar   = tomedia($username['avatar']);
                    if (empty($avatar)) {
                        $avatar = tomedia('./addons/fm_photosvote/icon.jpg');
                    }
                }
            }
            return $avatar;
        } else {
            $username = $this->_getuser($rid, $from_user);
            if (!empty($username['realname'])) {
                $name = cutstr($username['realname'], $limit);
            } else {
                $name = cutstr($username['nickname'], $limit);
            }
            if (empty($name)) {
                $username = $this->gettpinfo($rid, $from_user);
                if (!empty($username['realname'])) {
                    $name = cutstr($username['realname'], $limit);
                } else {
                    $name = cutstr($username['nickname'], $limit);
                }
                if (empty($name)) {
                    $username = mc_fansinfo($from_user);
                    $name     = cutstr($username['nickname'], $limit);
                    if (empty($name)) {
                        $name = cutstr($from_user, $limit);
                        if (empty($name)) {
                            $name = '网友';
                        }
                    }
                }
            }
            return $name;
        }
    }
    public function getmobile($rid, $from_user)
    {
        $userinfo = $this->_getuser($rid, $from_user);
        $mobile   = $userinfo['mobile'];
        if (empty($mobile)) {
            $userinfo = $this->gettpinfo($rid, $from_user);
            $mobile   = $userinfo['mobile'];
            if (empty($mobile)) {
                load()->model('mc');
                $userinfo = mc_fansinfo($from_user);
                $mobile   = $userinfo['mobile'];
                if (empty($mobile)) {
                    $mobile = '';
                }
            }
        }
        return $mobile;
    }
    public function getcommentnum($rid, $uniacid, $from_user, $type = '0')
    {
        if ($type == 1) {
            $num = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_bbsreply) . " WHERE rid= " . $rid . "  AND ( from_user= '" . $from_user . "' OR tfrom_user= '" . $from_user . "') AND status = 1 ");
        } else {
            $num = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_bbsreply) . " WHERE rid= " . $rid . " AND tfrom_user= '" . $from_user . "' AND status = 1 ");
        }
        if (empty($num)) {
            $num = 0;
        }
        return $num;
    }
    public function getphotosnum($rid, $uniacid, $tfrom_user)
    {
        $num = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_users_picarr) . " WHERE rid= " . $rid . " AND from_user= '" . $tfrom_user . "' ");
        return $num;
    }
    public function gettpinfo($rid, $from_user)
    {
        $tpinfo = pdo_fetch('SELECT realname, mobile,nickname,avatar FROM ' . tablename($this->table_voteer) . ' WHERE rid= :rid AND from_user = :from_user ', array(
            ':rid' => $rid,
            ':from_user' => $from_user
        ));
        return $tpinfo;
    }
    public function getuidusers($rid, $uid)
    {
        $tpinfo = pdo_fetch('SELECT from_user,realname, mobile,avatar FROM ' . tablename($this->table_users) . ' WHERE rid= :rid AND uid = :uid ', array(
            ':rid' => $rid,
            ':uid' => $uid
        ));
        return $tpinfo;
    }
    public function getvotes($rid, $from_user, $tfrom_user = '', $type = '')
    {
        if ($type == 'from_user_tfrom_user') {
            $num = pdo_fetchcolumn("SELECT SUM(vote_times) FROM " . tablename($this->table_log) . " WHERE rid= " . $rid . " AND from_user= '" . $from_user . "' AND tfrom_user= '" . $tfrom_user . "' ");
        } else {
            $num = pdo_fetchcolumn("SELECT SUM(vote_times) FROM " . tablename($this->table_log) . " WHERE rid= " . $rid . " AND from_user= '" . $from_user . "' ");
        }
        if (empty($num)) {
            $num = 0;
        }
        return $num;
    }
    public function getaccount($uniacid)
    {
        $acid    = pdo_fetchcolumn("SELECT default_acid FROM " . tablename('uni_account') . " WHERE uniacid = :uniacid", array(
            ':uniacid' => $uniacid
        ));
        $account = account_fetch($acid);
        return $account;
    }
    public function getmoneys($rid, $from_user)
    {
        $num = pdo_fetchcolumn("SELECT SUM(price) FROM " . tablename($this->table_order) . " WHERE rid= " . $rid . " AND status = 1 AND from_user= '" . $from_user . "' ");
        if (empty($num)) {
            $num = '0.00';
        } else {
            $num = sprintf('%.2f', $num);
            ;
        }
        return $num;
    }
    public function addorderlog($rid, $ordersn, $from_user, $jifen, $title, $type = '0', $remark)
    {
        global $_W;
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE ordersn='{$ordersn}' limit 1");
        pdo_update($this->table_order, array(
            'ispayvote' => $type
        ), array(
            'id' => $item['id']
        ));
        $orderlog = pdo_fetch('SELECT * FROM ' . tablename($this->table_orderlog) . ' WHERE rid= :rid AND ordersn = :ordersn', array(
            ':rid' => $rid,
            ':ordersn' => $ordersn
        ));
        if ($type == 6) {
            $status = 1;
        }
        if (empty($orderlog)) {
            $data = array(
                'uniacid' => $_W['uniacid'],
                'rid' => $rid,
                'ordersn' => $ordersn,
                'from_user' => $from_user,
                'num' => $jifen,
                'title' => $title,
                'type' => $type,
                'status' => $status,
                'createtime' => TIMESTAMP,
                'remark' => $remark
            );
            pdo_insert($this->table_orderlog, $data);
        } else {
            pdo_update($this->table_orderlog, array(
                'status' => $status,
                'type' => $type,
                'lasttime' => TIMESTAMP
            ), array(
                'id' => $orderlog['id']
            ));
        }
    }
    public function addmsg($rid, $from_user, $tfrom_user, $title, $content, $type = '1')
    {
        global $_W;
        $date = array(
            'uniacid' => $_W['uniacid'],
            'rid' => $rid,
            'status' => '0',
            'type' => $type,
            'from_user' => $from_user,
            'tfrom_user' => $tfrom_user,
            'title' => $title,
            'content' => $content,
            'createtime' => time()
        );
        pdo_insert($this->table_msg, $date);
    }
    public function getmsg($rid, $from_user)
    {
        $rmsg          = array();
        $rmsg['msg']   = pdo_fetchall('SELECT * FROM ' . tablename($this->table_msg) . ' WHERE rid= :rid AND from_user = :from_user ORDER BY createtime DESC LIMIT 200', array(
            ':rid' => $rid,
            ':from_user' => $from_user
        ));
        $rmsg['total'] = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_msg) . " WHERE rid= " . $rid . " AND from_user= '" . $from_user . "' ");
        return $rmsg;
    }
    public function getgiftlist($rid, $from_user, $pindex, $psize = '6', $jishu = '6', &$list = '')
    {
        $gift  = pdo_fetchall('SELECT * FROM ' . tablename($this->table_jifen_gift) . ' WHERE rid= :rid ORDER BY id DESC LIMIT ' . ($pindex - 1) * $jishu . ',' . $psize, array(
            ':rid' => $rid
        ));
        $total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_jifen_gift) . ' WHERE rid= :rid ORDER BY id DESC LIMIT ' . ($pindex - 1) * $jishu . ',' . $psize, array(
            ':rid' => $rid
        ));
        $list .= '<div class="mui-slider-item"><ul class="mui-table-view mui-grid-view mui-grid-9">';
        foreach ($gift as $key => $value) {
            $mygift = pdo_fetch("SELECT giftnum FROM " . tablename($this->table_user_gift) . " WHERE rid = " . $rid . " AND from_user = :from_user AND status = 1 AND giftid = " . $value['id'] . "  ORDER BY giftid ASC", array(
                ':from_user' => $from_user
            ));
            if ($value['piaoshu'] > 0) {
                $fuhao = '+';
            }
            $list .= '<li id="rall" class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4" onclick="choose(' . $value['id'] . ')">
					<span id="addps_' . $value['id'] . '" class="piaoshu" >' . $fuhao . $value['piaoshu'] . ' 票数</span>
						<a href="#" id="gift_' . $value['id'] . '">
							<span class="mui-icon"><img src="' . tomedia($value['images']) . '" width="100%" height="64"/>';
            if (!empty($mygift) && $mygift['giftnum'] > 0) {
                $list .= '<div class="maskBar text-c">拥有' . $mygift['giftnum'] . '个</div>';
            }
            $list .= '</span>
							<div class="" style="line-height: 15px;">
								<p class="list-group-item-text" style="color:#f0ad4e;">' . $value['gifttitle'] . '</p>
								<p class="list-group-item-text" style="color:#f0ad4e;font-size: 12px;"><span class="mui-icon Hui-iconfont Hui-iconfont-jifen"></span>' . $value['jifen'] . '</p>
							</div>
						</a>
					</li>';
        }
        $list .= '</ul></div>';
        if (!empty($gift) && count($gift) >= $jishu) {
            $pindex = $pindex + 1;
            $psize  = $pindex * $jishu;
            $a      = $this->getgiftlist($rid, $from_user, $pindex, $psize, $jishu, $list);
        }
        return $list;
    }
    public function getgift($rid)
    {
        $gift          = array();
        $gift['gifts'] = pdo_fetchall('SELECT * FROM ' . tablename($this->table_jifen_gift) . ' WHERE rid= :rid ORDER BY createtime DESC', array(
            ':rid' => $rid
        ));
        $gift['total'] = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_jifen_gift) . " WHERE rid= " . $rid . "  ");
        if (empty($gift['total'])) {
            $gift['total'] = '0';
        }
        return $gift;
    }
    public function getmygift($rid, $from_user, $type = '1')
    {
        $gift = array();
        $con  = '';
        if ($type == 2) {
            $con .= ' AND status = 2 ';
        } else {
            $con .= ' AND status = 1 ';
        }
        $gift['gifts'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_user_gift) . " WHERE rid = " . $rid . $con . "  AND from_user = :from_user  ORDER BY giftid ASC", array(
            ':from_user' => $from_user
        ));
        $gift['total'] = pdo_fetchcolumn("SELECT SUM(giftnum) FROM " . tablename($this->table_user_gift) . " WHERE rid= " . $rid . $con . "  AND from_user = :from_user ", array(
            ':from_user' => $from_user
        ));
        if (empty($gift['total'])) {
            $gift['total'] = '0';
        }
        $gift['zstotal'] = pdo_fetchcolumn("SELECT count(1) FROM " . tablename($this->table_user_zsgift) . " WHERE rid= " . $rid . "  AND from_user = :from_user ", array(
            ':from_user' => $from_user
        ));
        if (empty($gift['zstotal'])) {
            $gift['zstotal'] = '0';
        }
        return $gift;
    }
    public function addjifen($rid, $from_user, $tfrom_user, $info = array(), $vote = array(), $type = 'vote')
    {
        if ($type != 'reg') {
            pdo_update($this->table_users, array(
                'photosnum' => $vote['3'] + $vote['1'],
                'hits' => $vote['4'] + $vote['1']
            ), array(
                'rid' => $rid,
                'from_user' => $tfrom_user
            ));
            pdo_update($this->table_reply_display, array(
                'ljtp_total' => $vote['2'] + $vote['1'],
                'cyrs_total' => $vote['5'] + $vote['1']
            ), array(
                'rid' => $rid
            ));
        }
        $rjifen = pdo_fetch("SELECT is_open_jifen,is_open_jifen_sync,jifen_vote,jifen_vote_reg,jifen_reg FROM " . tablename($this->table_jifen) . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        if ($rjifen['is_open_jifen']) {
            if ($type == 'reg') {
                $jifen  = $rjifen['jifen_reg'];
                $msg    = '报名参赛 <span class="label label-warning">增加</span> ' . $jifen . '积分';
                $voteer = pdo_fetch("SELECT jifen FROM " . tablename($this->table_voteer) . " WHERE from_user = :from_user and rid = :rid limit 1", array(
                    ':from_user' => $from_user,
                    ':rid' => $rid
                ));
            } else {
                $user = pdo_fetch("SELECT id FROM " . tablename($this->table_users) . " WHERE from_user = :from_user and rid = :rid limit 1", array(
                    ':from_user' => $tfrom_user,
                    ':rid' => $rid
                ));
                if (!empty($user)) {
                    $tjifen = $rjifen['jifen_vote_reg'] * $vote['1'];
                    $tmsg   = '被投票 <span class="label label-warning">增加</span> ' . $tjifen . '积分';
                }
                $jifen   = $rjifen['jifen_vote'] * $vote['1'];
                $msg     = '投票 <span class="label label-warning">增加</span> ' . $jifen . ' 积分';
                $voteer  = pdo_fetch("SELECT jifen FROM " . tablename($this->table_voteer) . " WHERE from_user = :from_user and rid = :rid limit 1", array(
                    ':from_user' => $from_user,
                    ':rid' => $rid
                ));
                $tvoteer = pdo_fetch("SELECT jifen FROM " . tablename($this->table_voteer) . " WHERE from_user = :from_user and rid = :rid limit 1", array(
                    ':from_user' => $tfrom_user,
                    ':rid' => $rid
                ));
            }
            if ($rjifen['is_open_jifen_sync']) {
                load()->model('mc');
                $uid = mc_openid2uid($from_user);
                if (empty($uid)) {
                    $uid = $_W['fans']['uid'];
                }
                if (!empty($uid)) {
                    mc_credit_update($uid, 'credit1', $jifen, array(
                        0,
                        $msg,
                        'fm_photosvote'
                    ));
                    $result    = mc_fetch($uid, array(
                        'credit1'
                    ));
                    $lastjifen = $result['credit1'];
                } else {
                    $lastjifen = $voteer['jifen'] + $jifen;
                }
                $tuid = mc_openid2uid($tfrom_user);
                if (!empty($tuid)) {
                    mc_credit_update($tuid, 'credit1', $tjifen, array(
                        0,
                        $tmsg,
                        'fm_photosvote'
                    ));
                    $tresult    = mc_fetch($tuid, array(
                        'credit1'
                    ));
                    $tlastjifen = $tresult['credit1'];
                } else {
                    $lastjifen  = $voteer['jifen'] + $jifen;
                    $tlastjifen = $tvoteer['jifen'] + $tjifen;
                }
            } else {
                $lastjifen  = $voteer['jifen'] + $jifen;
                $tlastjifen = $tvoteer['jifen'] + $tjifen;
            }
            pdo_update($this->table_voteer, array(
                'jifen' => $lastjifen
            ), array(
                'rid' => $rid,
                'from_user' => $from_user
            ));
            if ($type != 'reg') {
                pdo_update($this->table_voteer, array(
                    'jifen' => $tlastjifen
                ), array(
                    'rid' => $rid,
                    'from_user' => $tfrom_user
                ));
            }
        }
        if ($type == 'reg') {
            $this->addmsg($rid, $from_user, '', '报名消息', $msg, '3');
        } else {
            $nickname = $this->getname($rid, $from_user);
            $tcontent = '恭喜您，' . $nickname . '为您投了' . $vote['1'] . '票<br />' . $tmsg;
            $this->addmsg($rid, $from_user, $tfrom_user, '投票消息', $info['3'], '1');
            $this->addmsg($rid, $tfrom_user, '', '被投票消息', $tcontent, '2');
        }
        return true;
    }
    public function jsjifen($rid, $from_user, $jifen, $gifttitle, $type = 'gift')
    {
        global $_W;
        $userjf    = $this->cxjifen($rid, $from_user);
        $lastjifen = $userjf - $jifen;
        $voteer    = pdo_fetch("SELECT jifen FROM " . tablename($this->table_voteer) . " WHERE from_user = :from_user and rid = :rid limit 1", array(
            ':from_user' => $from_user,
            ':rid' => $rid
        ));
        $rjifen    = pdo_fetchcolumn("SELECT is_open_jifen_sync FROM " . tablename($this->table_jifen) . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        if ($rjifen) {
            load()->model('mc');
            $uid = mc_openid2uid($from_user);
            if (empty($uid)) {
                $uid = $_W['fans']['uid'];
            }
            if (!empty($uid)) {
                if ($type == 'cj') {
                    $msg = '抽奖，<span class="label label-warning">减少</span>' . $jifen . '积分';
                } elseif ($type == 'zs') {
                    $msg = '赠送礼物: ' . $gifttitle . '，<span class="label label-warning">减少</span>' . $jifen . '积分';
                } else {
                    $msg = '兑换礼物: ' . $gifttitle . '，<span class="label label-warning">减少</span>' . $jifen . '积分';
                }
                fm_mc_credit_update($uid, 'credit1', $jifen, 'js', array(
                    0,
                    $msg,
                    'fm_photosvote'
                ));
            }
        }
        pdo_update($this->table_voteer, array(
            'jifen' => $lastjifen
        ), array(
            'rid' => $rid,
            'from_user' => $from_user
        ));
        if ($type == 'cj') {
            $msg = '抽奖 ，<span class="label label-warning">消费</span>' . $jifen . '积分';
        } elseif ($type == 'zs') {
            $msg = '赠送 ' . $gifttitle . ' 礼物，<span class="label label-warning">消费</span>' . $jifen . '积分';
        } else {
            $msg = '兑换 ' . $gifttitle . ' 礼物，<span class="label label-warning">消费</span>' . $jifen . '积分';
        }
        $this->addmsg($rid, $from_user, '', '积分消费', $msg, '6');
        return true;
    }
    public function addjifencharge($rid, $from_user, $jifen, $ordersn)
    {
        global $_W;
        $remark    = '微信充值，<span class="label label-warning">增加</span>' . $_GPC['jifen'] . '积分';
        $userjf    = $this->cxjifen($rid, $from_user);
        $lastjifen = $userjf + $jifen;
        $voteer    = pdo_fetch("SELECT jifen FROM " . tablename($this->table_voteer) . " WHERE from_user = :from_user and rid = :rid limit 1", array(
            ':from_user' => $from_user,
            ':rid' => $rid
        ));
        $this->addorderlog($rid, $ordersn, $from_user, $_GPC['jifen'], '积分充值', $type = '4', $remark);
        $rjifen = pdo_fetchcolumn("SELECT is_open_jifen_sync FROM " . tablename($this->table_jifen) . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        if ($rjifen) {
            load()->model('mc');
            $uid = mc_openid2uid($from_user);
            if (empty($uid)) {
                $uid = $_W['fans']['uid'];
            }
            $this->addorderlog($rid, $ordersn, $from_user, $_GPC['jifen'], '积分充值', $type = '5', $remark);
            if (!empty($uid)) {
                $msg = '微信充值，<span class="label label-warning">增加</span>' . $jifen . '积分';
                fm_mc_credit_update($uid, 'credit1', $jifen, 'add', array(
                    0,
                    $msg,
                    'fm_photosvote'
                ));
                $this->addorderlog($rid, $ordersn, $from_user, $_GPC['jifen'], '积分充值', $type = '6', $remark);
            }
        } else {
            $this->addorderlog($rid, $ordersn, $from_user, $_GPC['jifen'], '积分充值', $type = '6', $remark);
        }
        pdo_update($this->table_voteer, array(
            'jifen' => $lastjifen
        ), array(
            'rid' => $rid,
            'from_user' => $from_user
        ));
        $msg = '微信充值，<span class="label label-warning">增加</span>' . $jifen . '积分';
        $this->addmsg($rid, $from_user, '', '积分充值', $msg, '4');
        return true;
    }
    public function cxjifen($rid, $from_user)
    {
        $rjifen = pdo_fetch("SELECT is_open_jifen_sync FROM " . tablename($this->table_jifen) . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        $orders = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . " WHERE rid = :rid AND from_user = :from_user AND status = 1 AND paytype = 6 AND ispayvote > 3 AND ispayvote < 6 AND (transid != '' OR transid != '0')", array(
            ':rid' => $rid,
            ':from_user' => $from_user
        ));
        if (!empty($orders)) {
            foreach ($orders as $key => $value) {
                $voteer    = pdo_fetch("SELECT jifen FROM " . tablename($this->table_voteer) . " WHERE from_user = :from_user and rid = :rid limit 1", array(
                    ':from_user' => $from_user,
                    ':rid' => $rid
                ));
                $lastjifen = $voteer['jifen'] + $value['jifen'];
                pdo_update($this->table_voteer, array(
                    'jifen' => $lastjifen
                ), array(
                    'rid' => $rid,
                    'from_user' => $from_user
                ));
                $remark = '微信充值，<span class="label label-warning">增加</span>' . $_GPC['jifen'] . '积分';
                $this->addorderlog($rid, $value['ordersn'], $from_user, $value['jifen'], '积分充值', $type = '6', $remark);
            }
        }
        $voteer = pdo_fetch("SELECT jifen FROM " . tablename($this->table_voteer) . " WHERE from_user = :from_user and rid = :rid limit 1", array(
            ':from_user' => $from_user,
            ':rid' => $rid
        ));
        if ($rjifen['is_open_jifen_sync']) {
            load()->model('mc');
            $uid = mc_openid2uid($from_user);
            if (empty($uid)) {
                $uid = $_W['fans']['uid'];
            }
            if (!empty($uid)) {
                $result    = mc_fetch($uid, array(
                    'credit1'
                ));
                $lastjifen = $result['credit1'];
                if (empty($voteer['jifen'])) {
                    if (!empty($lastjifen)) {
                        return $lastjifen;
                    } else {
                        return '0';
                    }
                } else {
                    if ($lastjifen > 0) {
                        if ($lastjifen != $voteer['jifen'] && $voteer['jifen'] <= 0) {
                            $lastjifen = $lastjifen + $voteer['jifen'];
                            pdo_update($this->table_voteer, array(
                                'jifen' => $lastjifen
                            ), array(
                                'rid' => $rid,
                                'from_user' => $from_user
                            ));
                            $msg = '同步积分，<span class="label label-warning">同步</span>' . $lastjifen . '积分';
                            fm_mc_credit_update($uid, 'credit1', $lastjifen, false, array(
                                0,
                                $msg,
                                'fm_photosvote'
                            ));
                        }
                        $voteer    = pdo_fetch("SELECT jifen FROM " . tablename($this->table_voteer) . " WHERE from_user = :from_user and rid = :rid limit 1", array(
                            ':from_user' => $from_user,
                            ':rid' => $rid
                        ));
                        $lastjifen = $voteer['jifen'];
                        return $lastjifen;
                    } else {
                        if (!empty($uid)) {
                            $msg = '同步积分中，<span class="label label-warning">同步</span>' . $jifen . '积分';
                            fm_mc_credit_update($uid, 'credit1', $voteer['jifen'], false, array(
                                0,
                                $msg,
                                'fm_photosvote'
                            ));
                            $lastjifen = $voteer['jifen'];
                        } else {
                            $lastjifen = $voteer['jifen'];
                        }
                    }
                    return $lastjifen;
                }
            } else {
                if (!empty($voteer['jifen'])) {
                    return $voteer['jifen'];
                } else {
                    return '0';
                }
            }
        } else {
            if (!empty($voteer['jifen'])) {
                return $voteer['jifen'];
            } else {
                return '0';
            }
        }
    }
    public function editjifen($rid, $from_user, $jifen, $nickname, $avatar, $realname, $mobile, $sex)
    {
        global $_W;
        $rjifen = pdo_fetchcolumn("SELECT is_open_jifen_sync FROM " . tablename($this->table_jifen) . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        $voteer = pdo_fetch("SELECT * FROM " . tablename($this->table_voteer) . " WHERE from_user = :from_user and rid = :rid limit 1", array(
            ':from_user' => $from_user,
            ':rid' => $rid
        ));
        if ($rjifen) {
            load()->model('mc');
            $uid = mc_openid2uid($from_user);
            if (empty($uid)) {
                $uid = $_W['fans']['uid'];
            }
            if (!empty($uid)) {
                $msg = '后台手动修改，<span class="label label-warning">变更为</span>' . $jifen . '积分';
                fm_mc_credit_update($uid, 'credit1', $jifen, false, array(
                    0,
                    $msg,
                    'fm_photosvote'
                ));
            }
        }
        $voteer_data = array(
            'uniacid' => $_W['uniacid'],
            'weid' => $_W['uniacid'],
            'rid' => $rid,
            'from_user' => $from_user,
            'nickname' => $nickname,
            'avatar' => $avatar,
            'sex' => $sex,
            'realname' => $realname,
            'mobile' => $mobile,
            'status' => '1',
            'jifen' => $jifen,
            'createtime' => time()
        );
        if (empty($voteer)) {
            pdo_insert($this->table_voteer, $voteer_data);
        } else {
            pdo_update($this->table_voteer, array(
                'jifen' => $jifen
            ), array(
                'rid' => $rid,
                'from_user' => $from_user
            ));
        }
        $msg = '管理员后台手动修改积分，<span class="label label-warning">变更为</span>' . $jifen . '积分';
        $this->addmsg($rid, $from_user, '', '积分变动', $msg, '6');
        return true;
    }
    public function createvoteer($rid, $uniacid, $from_user, $nickname, $avatar, $sex)
    {
        if (!empty($from_user)) {
            $voteer = pdo_fetch("SELECT * FROM " . tablename($this->table_voteer) . " WHERE from_user = :from_user and rid = :rid", array(
                ':from_user' => $from_user,
                ':rid' => $rid
            ));
            $rvote  = pdo_fetch("SELECT open_smart,answer_times,isanswer FROM " . tablename($this->table_reply_vote) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
            $time   = time();
            if (empty($voteer)) {
                if ($rvote['open_smart']) {
                    $voteernickname = pdo_fetch("SELECT id FROM " . tablename($this->table_voteer) . " WHERE nickname = :nickname and rid = :rid", array(
                        ':nickname' => $nickname,
                        ':rid' => $rid
                    ));
                    $voteeravatar   = pdo_fetch("SELECT id FROM " . tablename($this->table_voteer) . " WHERE avatar = :avatar and rid = :rid", array(
                        ':avatar' => $avatar,
                        ':rid' => $rid
                    ));
                    if (!empty($voteernickname) && !empty($voteeravatar)) {
                        $splog          = array(
                            'uniacid' => $uniacid,
                            'weid' => $uniacid,
                            'rid' => $rid,
                            'from_user' => $from_user,
                            'nickname' => $nickname,
                            'avatar' => $avatar,
                            'sex' => $sex,
                            'status' => '1',
                            'ip' => getip(),
                            'createtime' => $time
                        );
                        $splog['iparr'] = getiparr($splog['ip']);
                        pdo_insert($this->table_shuapiaolog, $splog);
                        $stopllq = $_W['siteroot'] . 'app/' . $this->createMobileUrl('stopllq', array(
                            'rid' => $rid,
                            'info' => '系统检测到您存在异常，请勿刷票，否则将拉入黑名单'
                        ));
                        header("location:$stopllq");
                        exit;
                    }
                }
                $voteer_data = array(
                    'uniacid' => $uniacid,
                    'weid' => $uniacid,
                    'rid' => $rid,
                    'from_user' => $from_user,
                    'nickname' => $nickname,
                    'avatar' => $avatar,
                    'sex' => $sex,
                    'realname' => '',
                    'mobile' => '',
                    'status' => '1',
                    'ip' => getip(),
                    'lasttime' => $time,
                    'createtime' => $time
                );
                if ($rvote['isanswer']) {
                    $voteer_data['is_user_chance'] = $rvote['answer_times'];
                }
                $voteer_data['iparr'] = getiparr($voteer_data['ip']);
                pdo_insert($this->table_voteer, $voteer_data);
                $msg = '恭喜您成功开通个人中心';
                $this->addmsg($rid, $from_user, '', '开通信息', $msg, '7');
            } else {
                if ($rvote['isanswer']) {
                    if ($voteer['lasttime'] < mktime(0, 0, 0)) {
                        pdo_update($this->table_voteer, array(
                            'is_user_chance' => $rvote['answer_times'],
                            'lasttime' => time()
                        ), array(
                            'rid' => $rid,
                            'from_user' => $from_user
                        ));
                    }
                }
                pdo_update($this->table_voteer, array(
                    'lasttime' => $time,
                    'ip' => getip(),
                    'iparr' => getiparr(getip())
                ), array(
                    'from_user' => $from_user,
                    'rid' => $rid
                ));
            }
            return true;
        } else {
            return false;
        }
    }
    public function updatevoteer($rid, $from_user, $realname, $mobile)
    {
        $voteer = pdo_fetch("SELECT realname,mobile FROM " . tablename($this->table_voteer) . " WHERE from_user = :from_user and rid = :rid", array(
            ':from_user' => $from_user,
            ':rid' => $rid
        ));
        if (empty($realname)) {
            $msg    = '您的真实姓名没有填写，请填写！';
            $status = -1;
            return $msg;
        }
        if (!preg_match(REGULAR_MOBILE, $mobile)) {
            $msg    = '必须输入手机号，格式为 11 位数字。';
            $status = -1;
            return $msg;
        }
        if ($voteer['realname']) {
            if ($voteer['realname'] == $realname) {
            } else {
                $realname = pdo_fetch("SELECT * FROM " . tablename($this->table_voteer) . " WHERE realname = :realname and rid = :rid", array(
                    ':realname' => $realname,
                    ':rid' => $rid
                ));
                if (!empty($realname)) {
                    $msg    = '已经存在该姓名';
                    $status = -1;
                    return $msg;
                }
            }
        }
        if ($voteer['mobile']) {
            if ($voteer['mobile'] == $mobile) {
            } else {
                $ymobile = pdo_fetch("SELECT * FROM " . tablename($this->table_voteer) . " WHERE mobile = :mobile and rid = :rid", array(
                    ':mobile' => $mobile,
                    ':rid' => $rid
                ));
                if (!empty($ymobile)) {
                    $r   = array(
                        'msg' => '非常抱歉，此手机号码已经被注册，你需要更换注册手机号！',
                        'status' => -1
                    );
                    $msg = '非常抱歉，此手机号码已经被注册，你需要更换注册手机号！';
                    return $msg;
                }
            }
        }
        pdo_update($this->table_voteer, array(
            'realname' => $realname,
            'mobile' => $mobile
        ), array(
            'from_user' => $from_user,
            'rid' => $rid
        ));
    }
    public function updatelp($rid)
    {
        $where = "";
        $where .= " AND (transid != '' OR transid <> '0') AND (paytime != '' OR paytime != '0') ";
        $where .= " AND ispayvote > 1";
        $where .= " AND paytype < 6";
        $votelogs = pdo_fetchall('SELECT * FROM ' . tablename($this->table_order) . ' WHERE `rid` = ' . $rid . ' ' . $where . ' ');
        foreach ($votelogs as $key => $value) {
            $tfrom_user = $value['tfrom_user'];
            $vote_times = $value['vote_times'];
            $user       = $this->_getloguser($rid, $value['from_user']);
            $votedate   = array(
                'uniacid' => $uniacid,
                'rid' => $rid,
                'tptype' => '3',
                'vote_times' => $vote_times,
                'avatar' => $user['avatar'],
                'nickname' => $user['nickname'],
                'from_user' => $value['from_user'],
                'afrom_user' => $value['fromuser'],
                'tfrom_user' => $tfrom_user,
                'ordersn' => $value['ordersn'],
                'islp' => '1',
                'ip' => $value['ip'],
                'iparr' => $value['iparr'],
                'createtime' => $value['paytime']
            );
            pdo_insert($this->table_log, $votedate);
            pdo_update($this->table_order, array(
                'ispayvote' => '1'
            ), array(
                'ordersn' => $value['ordersn']
            ));
            $user = pdo_fetch("SELECT hits,photosnum FROM " . tablename($this->table_users) . " WHERE from_user = :from_user and rid = :rid", array(
                ':from_user' => $tfrom_user,
                ':rid' => $rid
            ));
            pdo_update($this->table_users, array(
                'photosnum' => $user['photosnum'] + $vote_times,
                'hits' => $user['hits'] + $vote_times
            ), array(
                'rid' => $rid,
                'from_user' => $tfrom_user
            ));
            $rdisplay = pdo_fetch("SELECT ljtp_total,cyrs_total  FROM " . tablename($this->table_reply_display) . " WHERE rid = :rid", array(
                ':rid' => $rid
            ));
            pdo_update($this->table_reply_display, array(
                'ljtp_total' => $rdisplay['ljtp_total'] + $vote_times,
                'cyrs_total' => $rdisplay['cyrs_total'] + $vote_times
            ), array(
                'rid' => $rid
            ));
        }
        setcookie("user_lptb_time", time(), time() + 1800);
    }
    public function counter($rid, $from_user, $tfrom_user, $types, $unimoshi = '')
    {
        global $_W;
        $where     = "";
        $starttime = mktime(0, 0, 0);
        $endtime   = $starttime + 86399;
        $where .= ' AND createtime >=' . $starttime;
        $where .= ' AND createtime <=' . $endtime;
        if ($types == 'tp') {
            if ($unimoshi == 1) {
                $num = 8;
            } else {
                $num = 4;
            }
            for ($type = 1; $type <= $num; $type++) {
                $date = array(
                    'uniacid' => $_W['uniacid'],
                    'rid' => $rid,
                    'from_user' => $from_user
                );
                switch ($type) {
                    case '1':
                        $counter          = pdo_fetch("SELECT * FROM " . tablename($this->table_counter) . " WHERE rid = :rid AND from_user = :from_user AND type = :type", array(
                            ':rid' => $rid,
                            ':from_user' => $from_user,
                            ':type' => $type
                        ));
                        $date['tp_times'] = $counter['tp_times'] + 1;
                        $date['type']     = $type;
                        break;
                    case '2':
                        $counter          = pdo_fetch("SELECT * FROM " . tablename($this->table_counter) . " WHERE rid = :rid AND from_user = :from_user AND type = :type $where", array(
                            ':rid' => $rid,
                            ':from_user' => $from_user,
                            ':type' => $type
                        ));
                        $date['tp_times'] = $counter['tp_times'] + 1;
                        $date['type']     = $type;
                        break;
                    case '3':
                        $counter            = pdo_fetch("SELECT * FROM " . tablename($this->table_counter) . " WHERE rid = :rid AND from_user = :from_user AND tfrom_user = :tfrom_user AND type = :type", array(
                            ':rid' => $rid,
                            ':from_user' => $from_user,
                            ':tfrom_user' => $tfrom_user,
                            ':type' => $type
                        ));
                        $date['tfrom_user'] = $tfrom_user;
                        $date['tp_times']   = $counter['tp_times'] + 1;
                        $date['type']       = $type;
                        break;
                    case '4':
                        $counter            = pdo_fetch("SELECT * FROM " . tablename($this->table_counter) . " WHERE rid = :rid AND from_user = :from_user  AND tfrom_user = :tfrom_user AND type = :type $where", array(
                            ':rid' => $rid,
                            ':from_user' => $from_user,
                            ':tfrom_user' => $tfrom_user,
                            ':type' => $type
                        ));
                        $date['tfrom_user'] = $tfrom_user;
                        $date['tp_times']   = $counter['tp_times'] + 1;
                        $date['type']       = $type;
                        break;
                    case '5':
                        $counter          = pdo_fetch("SELECT * FROM " . tablename($this->table_counter) . " WHERE uniacid = :uniacid AND rid = :rid AND from_user = :from_user AND type = :type", array(
                            ':uniacid' => $_W['uniacid'],
                            ':rid' => $rid,
                            ':from_user' => $from_user,
                            ':type' => $type
                        ));
                        $date['tp_times'] = $counter['tp_times'] + 1;
                        $date['type']     = $type;
                        break;
                    case '6':
                        $counter          = pdo_fetch("SELECT * FROM " . tablename($this->table_counter) . " WHERE uniacid = :uniacid AND rid = :rid AND from_user = :from_user AND type = :type $where", array(
                            ':uniacid' => $_W['uniacid'],
                            ':rid' => $rid,
                            ':from_user' => $from_user,
                            ':type' => $type
                        ));
                        $date['tp_times'] = $counter['tp_times'] + 1;
                        $date['type']     = $type;
                        break;
                    case '7':
                        $counter            = pdo_fetch("SELECT * FROM " . tablename($this->table_counter) . " WHERE uniacid = :uniacid AND rid = :rid AND from_user = :from_user AND tfrom_user = :tfrom_user AND type = :type", array(
                            ':uniacid' => $_W['uniacid'],
                            ':rid' => $rid,
                            ':from_user' => $from_user,
                            ':tfrom_user' => $tfrom_user,
                            ':type' => $type
                        ));
                        $date['tfrom_user'] = $tfrom_user;
                        $date['tp_times']   = $counter['tp_times'] + 1;
                        $date['type']       = $type;
                        break;
                    case '8':
                        $starttime = mktime(0, 0, 0);
                        $endtime   = $starttime + 86399;
                        $where .= ' AND createtime >=' . $starttime;
                        $where .= ' AND createtime <=' . $endtime;
                        $counter            = pdo_fetch("SELECT * FROM " . tablename($this->table_counter) . " WHERE uniacid = :uniacid AND rid = :rid AND from_user = :from_user  AND tfrom_user = :tfrom_user AND type = :type $where", array(
                            ':uniacid' => $_W['uniacid'],
                            ':rid' => $rid,
                            ':from_user' => $from_user,
                            ':tfrom_user' => $tfrom_user,
                            ':type' => $type
                        ));
                        $date['tfrom_user'] = $tfrom_user;
                        $date['tp_times']   = $counter['tp_times'] + 1;
                        $date['type']       = $type;
                        break;
                    default:
                        break;
                }
                $id = $counter['id'];
                if (empty($id)) {
                    $date['createtime'] = TIMESTAMP;
                    pdo_insert($this->table_counter, $date);
                } else {
                    pdo_update($this->table_counter, $date, array(
                        'id' => $id
                    ));
                }
            }
        } elseif ($types == 'gift') {
            $date               = array(
                'uniacid' => $_W['uniacid'],
                'rid' => $rid,
                'from_user' => $from_user,
                'type' => 9
            );
            $counter            = pdo_fetch("SELECT * FROM " . tablename($this->table_counter) . " WHERE rid = :rid AND from_user = :from_user AND type = :type", array(
                ':rid' => $rid,
                ':from_user' => $from_user,
                ':type' => 9
            ));
            $date['gift_times'] = $counter['gift_times'] + 1;
            $id                 = $counter['id'];
            if (empty($id)) {
                $date['createtime'] = TIMESTAMP;
                pdo_insert($this->table_counter, $date);
            } else {
                pdo_update($this->table_counter, $date, array(
                    'id' => $id
                ));
            }
        }
    }
    public function gettpnum($rid, $from_user, $tfrom_user = '', $type = '')
    {
        global $_W;
        $where     = "";
        $starttime = mktime(0, 0, 0);
        $endtime   = $starttime + 86399;
        $where .= ' AND createtime >=' . $starttime;
        $where .= ' AND createtime <=' . $endtime;
        switch ($type) {
            case '1':
                $counter = pdo_fetchcolumn("SELECT tp_times FROM " . tablename($this->table_counter) . " WHERE rid = :rid AND from_user = :from_user AND type = :type", array(
                    ':rid' => $rid,
                    ':from_user' => $from_user,
                    ':type' => $type
                ));
                break;
            case '2':
                $counter = pdo_fetchcolumn("SELECT tp_times FROM " . tablename($this->table_counter) . " WHERE rid = :rid AND from_user = :from_user AND type = :type $where", array(
                    ':rid' => $rid,
                    ':from_user' => $from_user,
                    ':type' => $type
                ));
                break;
            case '3':
                $counter = pdo_fetchcolumn("SELECT tp_times FROM " . tablename($this->table_counter) . " WHERE rid = :rid AND from_user = :from_user AND tfrom_user = :tfrom_user AND type = :type", array(
                    ':rid' => $rid,
                    ':from_user' => $from_user,
                    ':tfrom_user' => $tfrom_user,
                    ':type' => $type
                ));
                break;
            case '4':
                $counter = pdo_fetchcolumn("SELECT tp_times FROM " . tablename($this->table_counter) . " WHERE rid = :rid AND from_user = :from_user  AND tfrom_user = :tfrom_user AND type = :type $where", array(
                    ':rid' => $rid,
                    ':from_user' => $from_user,
                    ':tfrom_user' => $tfrom_user,
                    ':type' => $type
                ));
                break;
            case '5':
                $counter = pdo_fetchcolumn("SELECT tp_times FROM " . tablename($this->table_counter) . " WHERE uniacid = :uniacid AND rid = :rid AND from_user = :from_user AND type = :type", array(
                    ':uniacid' => $_W['uniacid'],
                    ':rid' => $rid,
                    ':from_user' => $from_user,
                    ':type' => $type
                ));
                break;
            case '6':
                $counter = pdo_fetchcolumn("SELECT tp_times FROM " . tablename($this->table_counter) . " WHERE uniacid = :uniacid AND rid = :rid AND from_user = :from_user AND type = :type $where", array(
                    ':uniacid' => $_W['uniacid'],
                    ':rid' => $rid,
                    ':from_user' => $from_user,
                    ':type' => $type
                ));
                break;
            case '7':
                $counter = pdo_fetchcolumn("SELECT tp_times FROM " . tablename($this->table_counter) . " WHERE uniacid = :uniacid AND rid = :rid AND from_user = :from_user AND tfrom_user = :tfrom_user AND type = :type", array(
                    ':uniacid' => $_W['uniacid'],
                    ':rid' => $rid,
                    ':from_user' => $from_user,
                    ':tfrom_user' => $tfrom_user,
                    ':type' => $type
                ));
                break;
            case '8':
                $counter = pdo_fetchcolumn("SELECT tp_times FROM " . tablename($this->table_counter) . " WHERE uniacid = :uniacid AND rid = :rid AND from_user = :from_user  AND tfrom_user = :tfrom_user AND type = :type $where", array(
                    ':uniacid' => $_W['uniacid'],
                    ':rid' => $rid,
                    ':from_user' => $from_user,
                    ':tfrom_user' => $tfrom_user,
                    ':type' => $type
                ));
                break;
            case '9':
                $counter = pdo_fetchcolumn("SELECT gift_times FROM " . tablename($this->table_counter) . " WHERE rid = :rid AND from_user = :from_user AND type = :type $where", array(
                    ':rid' => $rid,
                    ':from_user' => $from_user,
                    ':type' => $type
                ));
                break;
            default:
                $counter = pdo_fetchcolumn("SELECT tp_times FROM " . tablename($this->table_counter) . " WHERE rid = :rid AND from_user = :from_user AND type = :type", array(
                    ':rid' => $rid,
                    ':from_user' => $from_user,
                    ':type' => $type
                ));
                break;
        }
        if (empty($counter)) {
            $counter = 0;
        }
        return $counter;
    }
    public function gettpxz_status($rid, $from_user, $tfrom_user = '', $type = '1', $tpxz)
    {
        $counter = $this->gettpnum($rid, $from_user, $tfrom_user, $type);
        if ($counter >= $tpxz) {
            return false;
        } else {
            return true;
        }
    }
    public function votecode($rid, $from_user, $tfrom_user)
    {
        global $_W;
        $setting   = setting_load('site');
        $id        = isset($setting['site']['key']) ? $setting['site']['key'] : '0';
        $onlyoauth = pdo_fetch("SELECT fmauthtoken FROM " . tablename('fm_photosvote_onlyoauth') . " WHERE 1 ORDER BY id DESC LIMIT 1");
        if (!empty($onlyoauth['fmauthtoken'])) {
            $text     = $_W['config']['setting']['authkey'] . $onlyoauth['fmauthtoken'] . $from_user . $tfrom_user . $rid;
            $votecode = base64_encode($text);
            return $votecode;
        } else {
            return false;
        }
    }
    public function fmvipleavel($rid, $uniacid, $tfrom_user)
    {
        $user  = pdo_fetch("SELECT photosnum,xnphotosnum,hits,xnhits,yaoqingnum,zans FROM " . tablename($this->table_users) . " WHERE rid= " . $rid . " AND from_user= '" . $tfrom_user . "' ");
        $jifen = $this->cxjifen($rid, $from_user);
        if (!empty($user)) {
            $userps = $user['photosnum'] + $user['xnphotosnum'] + $user['hits'] + $user['xnhits'] + $user['yaoqingnum'] + $user['zans'] + $jifen;
        } else {
            $userps = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_log) . " WHERE rid= " . $rid . " AND from_user= '" . $tfrom_user . "' ") + $jifen;
        }
        $userps = intval($userps);
        if ($userps > 0 && $userps <= 1) {
            $level = 1;
        } elseif ($userps > 1 && $userps <= 5) {
            $level = 2;
        } elseif ($userps > 5 && $userps <= 15) {
            $level = 3;
        } elseif ($userps > 15 && $userps <= 30) {
            $level = 4;
        } elseif ($userps > 30 && $userps <= 50) {
            $level = 5;
        } elseif ($userps > 50 && $userps <= 100) {
            $level = 6;
        } elseif ($userps > 100 && $userps <= 200) {
            $level = 7;
        } elseif ($userps > 200 && $userps <= 400) {
            $level = 8;
        } elseif ($userps > 400 && $userps <= 800) {
            $level = 9;
        } elseif ($userps > 800 && $userps <= 2000) {
            $level = 10;
        } elseif ($userps > 2000 && $userps <= 3000) {
            $level = 11;
        } elseif ($userps > 3000 && $userps <= 5000) {
            $level = 12;
        } elseif ($userps > 5000 && $userps <= 8000) {
            $level = 13;
        } elseif ($userps > 8000 && $userps <= 15000) {
            $level = 14;
        } elseif ($userps > 15000 && $userps <= 30000) {
            $level = 15;
        } elseif ($userps > 30000 && $userps <= 60000) {
            $level = 16;
        } elseif ($userps > 60000 && $userps <= 100000) {
            $level = 17;
        } elseif ($userps > 100000 && $userps <= 500000) {
            $level = 18;
        } elseif ($userps > 500000 && $userps <= 1500000) {
            $level = 19;
        } elseif ($userps > 1500000 && $userps <= 3500000) {
            $level = 20;
        }
        return $level;
    }
    public function emotion($text)
    {
        $smile_popo  = '<span class="smile_popo" style="background-position-y: ';
        $smile_popoe = 'px;display: inline-block;  width: 30px"></span>';
        $str         = array(
            '(#呵呵)' => $smile_popo . '-0' . $smile_popoe,
            '(#哈哈)' => $smile_popo . '-30' . $smile_popoe,
            '(#吐舌)' => $smile_popo . '-60' . $smile_popoe,
            '(#啊)' => $smile_popo . '-90' . $smile_popoe,
            '(#酷)' => $smile_popo . '-120' . $smile_popoe,
            '(#怒)' => $smile_popo . '-150' . $smile_popoe,
            '(#开心)' => $smile_popo . '-180' . $smile_popoe,
            '(#汗)' => $smile_popo . '-210' . $smile_popoe,
            '(#泪)' => $smile_popo . '-240' . $smile_popoe,
            '(#黑线)' => $smile_popo . '-270' . $smile_popoe,
            '(#鄙视)' => $smile_popo . '-300' . $smile_popoe,
            '(#不高兴)' => $smile_popo . '-330' . $smile_popoe,
            '(#真棒)' => $smile_popo . '-360' . $smile_popoe,
            '(#钱)' => $smile_popo . '-390' . $smile_popoe,
            '(#疑问)' => $smile_popo . '-420' . $smile_popoe,
            '(#阴险)' => $smile_popo . '-450' . $smile_popoe,
            '(#吐)' => $smile_popo . '-480' . $smile_popoe,
            '(#咦)' => $smile_popo . '-510' . $smile_popoe,
            '(#委屈)' => $smile_popo . '-540' . $smile_popoe,
            '(#花心)' => $smile_popo . '-570' . $smile_popoe,
            '(#呼~)' => $smile_popo . '-600' . $smile_popoe,
            '(#笑眼)' => $smile_popo . '-630' . $smile_popoe,
            '(#冷)' => $smile_popo . '-660' . $smile_popoe,
            '(#太开心)' => $smile_popo . '-690' . $smile_popoe,
            '(#滑稽)' => $smile_popo . '-720' . $smile_popoe,
            '(#勉强)' => $smile_popo . '-750' . $smile_popoe,
            '(#狂汗)' => $smile_popo . '-780' . $smile_popoe,
            '(#乖)' => $smile_popo . '-810' . $smile_popoe,
            '(#睡觉)' => $smile_popo . '-840' . $smile_popoe,
            '(#惊哭)' => $smile_popo . '-870' . $smile_popoe,
            '(#升起)' => $smile_popo . '-900' . $smile_popoe,
            '(#惊讶)' => $smile_popo . '-930' . $smile_popoe,
            '(#喷)' => $smile_popo . '-960' . $smile_popoe,
            '(#爱心)' => $smile_popo . '-990' . $smile_popoe,
            '(#心碎)' => $smile_popo . '-1020' . $smile_popoe,
            '(#玫瑰)' => $smile_popo . '-1050' . $smile_popoe,
            '(#礼物)' => $smile_popo . '-1080' . $smile_popoe,
            '(#彩虹)' => $smile_popo . '-1110' . $smile_popoe,
            '(#星星月亮)' => $smile_popo . '-1140' . $smile_popoe,
            '(#太阳)' => $smile_popo . '-1170' . $smile_popoe,
            '(#钱币)' => $smile_popo . '-1200' . $smile_popoe,
            '(#灯泡)' => $smile_popo . '-1230' . $smile_popoe,
            '(#茶杯)' => $smile_popo . '-1260' . $smile_popoe,
            '(#蛋糕)' => $smile_popo . '-1290' . $smile_popoe,
            '(#音乐)' => $smile_popo . '-1320' . $smile_popoe,
            '(#haha)' => $smile_popo . '-1350' . $smile_popoe,
            '(#胜利)' => $smile_popo . '-1380' . $smile_popoe,
            '(#大拇指)' => $smile_popo . '-1410' . $smile_popoe,
            '(#弱)' => $smile_popo . '-1440' . $smile_popoe,
            '(#OK)' => $smile_popo . '-1470' . $smile_popoe
        );
        $content     = strtr($text, $str);
        return $content;
    }
    public function uniarr($uniarr, $uniacid)
    {
        foreach ($uniarr as $key => $value) {
            if ($value == $uniacid) {
                return true;
            }
        }
        return false;
    }
    public function limitSpeed($rid, $limitsd, $from_user, $type = '')
    {
        $zf       = date('H', time()) * 60 + date('i', time());
        $timeduan = intval($zf / $limitsd);
        $cstime   = $timeduan * $limitsd * 60 + mktime(0, 0, 0);
        $jstime   = ($timeduan + 1) * $limitsd * 60 + mktime(0, 0, 0);
        $where    = '';
        if ($type == 'voter') {
            $where .= ' AND from_user = "' . $from_user . '"';
        } else {
            $where .= ' AND tfrom_user = "' . $from_user . '"';
        }
        $where .= ' AND createtime >=' . $cstime;
        $where .= ' AND createtime <=' . $jstime;
        $limitsdvote = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_log) . ' WHERE rid = :rid ' . $where . ' ORDER BY createtime DESC', array(
            ':rid' => $rid
        ));
        $r           = array(
            'cstime' => $cstime,
            'limitsdvote' => $limitsdvote
        );
        return $r;
    }
    public function get_advs($rid)
    {
        $advs = pdo_fetchall("SELECT advname,link,thumb FROM " . tablename($this->table_advs) . " WHERE enabled=1 AND ismiaoxian = 0 AND rid= '{$rid}' AND issuiji = 1");
        if (!empty($advs)) {
            $adv    = array_rand($advs);
            $advarr = array();
            $advarr['thumb'] .= tomedia($advs[$adv]['thumb']);
            $advarr['advname'] .= cutstr($advs[$adv]['advname'], '10');
            $advarr['link'] .= $advs[$adv]['link'];
            return $advarr;
        }
    }
    public function get_answer($rid)
    {
        $answers = pdo_fetchall("SELECT * FROM " . tablename($this->table_answer) . " WHERE rid = '{$rid}' ORDER BY displayorder DESC, id ASC");
        if (!empty($answers)) {
            $ans       = array_rand($answers);
            $answerarr = array();
            $answerarr['thumb'] .= tomedia($answers[$ans]['thumb']);
            $answerarr['title'] .= cutstr($answers[$ans]['title'], '220');
            $answerarr['answer'] .= $answers[$ans]['answer'];
            $answerarr['key'] .= $answers[$ans]['key'];
            $answerarr['id'] .= $answers[$ans]['id'];
            return $answerarr;
        }
    }
    public function input_answer($rid, $from_user, $chose_answer, $answer_id)
    {
        $voteer = pdo_fetch("SELECT chance,is_user_chance FROM " . tablename($this->table_voteer) . " WHERE from_user = :from_user and rid = :rid LIMIT 1", array(
            ':from_user' => $from_user,
            ':rid' => $rid
        ));
        if (empty($voteer['is_user_chance'])) {
            $answer = pdo_fetch("SELECT * FROM " . tablename($this->table_answer) . " WHERE id = :id and rid = :rid limit 1", array(
                ':id' => $answer_id,
                ':rid' => $rid
            ));
            if ($answer['key'] == $chose_answer) {
                pdo_update($this->table_voteer, array(
                    'chance' => 1,
                    'is_user_chance' => 'yes'
                ), array(
                    'rid' => $rid,
                    'from_user' => $from_user
                ));
            } else {
                pdo_update($this->table_voteer, array(
                    'is_user_chance' => 'yes'
                ), array(
                    'rid' => $rid,
                    'from_user' => $from_user
                ));
            }
        }
    }
    public function skipurl($rid, $cfg)
    {
        global $_GPC;
        if ($_GPC['do'] != 'shareuserview' && $_GPC['do'] != 'shareuserdata' && $_GPC['do'] != 'treg' && $_GPC['do'] != 'tregs' && $_GPC['do'] != 'tvotestart' && $_GPC['do'] != 'Tvotestart' && $_GPC['do'] != 'tbbs' && $_GPC['do'] != 'tbbsreply' && $_GPC['do'] != 'saverecord' && $_GPC['do'] != 'subscribeshare' && $_GPC['do'] != 'pagedata' && $_GPC['do'] != 'pagedatab' && $_GPC['do'] != 'listentry' && $_GPC['do'] != 'code' && $_GPC['do'] != 'reguser' && $_GPC['do'] != 'phdata' && $_GPC['do'] != 'stopllq') {
            if (empty($_COOKIE["fm_skipurl"]) || time() > $_COOKIE["fm_skipurl"]) {
                $skipurlarr = explode('|', $cfg['skipurl']);
                $skipcount  = count($skipurlarr) - 1;
                $skipto     = mt_rand(0, $skipcount);
                if (!empty($_SERVER['QUERY_STRING'])) {
                    $skipurl = 'http://' . $skipurlarr[$skipto] . '/app/index.php?' . $_SERVER['QUERY_STRING'];
                } else {
                    $skipurl = 'http://' . $skipurlarr[$skipto] . '/app/' . $this->createMobileUrl('photosvote', array(
                        'rid' => $rid
                    ));
                }
                setcookie("fm_skipurl", time() + 1, time() + 1);
                header("location:$skipurl");
            }
        }
    }
    public function getgiftnum($rid, $tfrom_user, $uni = '')
    {
        $total_gift = pdo_fetch("SELECT SUM(giftnum) as num FROM " . tablename($this->table_user_zsgift) . ' WHERE rid = :rid AND tfrom_user = :tfrom_user ' . $uni . ' ', array(
            ':rid' => $rid,
            ':tfrom_user' => $tfrom_user
        ));
        if (empty($total_gift['num'])) {
            return '0';
        } else {
            return $total_gift['num'];
        }
    }
    public function getregusermoney($rid, $tfrom_user)
    {
        $total_gift = round(pdo_fetchcolumn("SELECT SUM(price) FROM " . tablename($this->table_order) . " WHERE rid = :rid AND tfrom_user =:tfrom_user AND status = 1", array(
            ':rid' => $rid,
            ':tfrom_user' => $tfrom_user
        )), 2);
        if (empty($total_gift)) {
            return '0';
        } else {
            return $total_gift;
        }
    }
}
?>