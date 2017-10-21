<?php

defined('IN_IA') or die('Access Denied');
class junsion_simpledailyModuleProcessor extends WeModuleProcessor
{
    public function respond()
    {
        global $_W;
        $content = $this->message['content'];
        $openid = $this->message['from'];
        $mem_info = pdo_fetch('select id,status from ' . tablename($this->modulename . '_member') . " where openid = '{$openid}' and weid = '{$_W['uniacid']}'");
        if ($mem_info['status'] == 1) {
            $text = '您已经被拉入黑名单中，不能创建和编辑，如有疑问，请联系客服！';
            return $this->respText($text);
        }
        if ($this->message['msgtype'] == 'image' || $this->message['event'] == 'pic_photo_or_album') {
            file_put_contents(IA_ROOT . '/addons/junsion_simpledaily/log', $openid . ' ' . json_encode($this->message) . '
', FILE_APPEND);
            if ($this->message['msgtype'] == 'image') {
                $mid = $this->message['mediaid'];
                $res = $this->downLoadImg($mid);
                if (is_array($res)) {
                    return $this->respText($res['msg']);
                }
                $img = $res;
            }
            if ($content == 'boyhood_works' && $this->message['msgtype'] == 'event') {
                file_put_contents(IA_ROOT . '/addons/junsion_simpledaily/log', $openid . ' create ' . $this->message['sendpicsinfo']['count'] . '
', FILE_APPEND);
                $res = $this->createWorks($this->getFans($openid));
                if (!is_numeric($res)) {
                    return $this->respText($res);
                }
                if (empty($this->message['sendpicsinfo']['count'])) {
                    return '';
                } else {
                    $this->beginContext();
                    $_SESSION['junsion_simpledaily_pic' . $_W['uniacid']] = $this->message['sendpicsinfo']['count'];
                }
            } elseif ($this->inContext && $this->message['msgtype'] == 'image') {
                $_SESSION['junsion_simpledaily_pic' . $_W['uniacid']]--;
                if ($_SESSION['junsion_simpledaily_pic' . $_W['uniacid']] <= 0) {
                    $this->endContext();
                }
                $works = pdo_fetch('select * from ' . tablename($this->modulename . '_works') . " where openid='{$openid}' order by createtime desc limit 1");
                $imgs = unserialize($works['imgs']);
                if ($img) {
                    $imgs[] = array('img' => $img, 'content' => '');
                }
                $count = 0;
                foreach ($imgs as $value) {
                    if (!empty($value['img'])) {
                        $count++;
                    }
                    if (empty($works['cover']) && $count == 1) {
                        $data['cover'] = $img;
                    }
                }
                $data['imgs'] = serialize($imgs);
                pdo_update($this->modulename . '_works', $data, array('id' => $works['id']));
                if ($_SESSION['junsion_simpledaily_pic' . $_W['uniacid']] <= 0) {
                    return $this->respText('<a href=\'' . $this->buildSiteUrl($this->createMobileUrl('myworks', array('wid' => base64_encode($works['id']), 'op' => 'edit'))) . "'>已收到{$count}张图片，点这里开始制作</a>");
                }
                return '';
            } else {
                $works = pdo_fetch('select id,imgs from ' . tablename($this->modulename . '_works') . " where openid='{$openid}' order by createtime desc limit 1");
                if (empty($works)) {
                    $res = $this->createWorks($this->getFans($openid), $img);
                    return $this->respText('<a href=\'' . $this->buildSiteUrl($this->createMobileUrl('myworks', array('wid' => base64_encode($res), 'op' => 'edit'))) . '\'>已收到1张图片，点这里开始制作</a>');
                } else {
                    $imgs = unserialize($works['imgs']);
                    $imgs[] = array('img' => $img, 'content' => '');
                    $count = 0;
                    foreach ($imgs as $value) {
                        if (!empty($value['img'])) {
                            $count++;
                        }
                    }
                    pdo_update($this->modulename . '_works', array('imgs' => serialize($imgs)), array('id' => $works['id']));
                    return $this->respText('<a href=\'' . $this->buildSiteUrl($this->createMobileUrl('myworks', array('wid' => base64_encode($works['id']), 'op' => 'edit'))) . "'>已收到{$count}张图片，点这里开始制作</a>");
                }
            }
        }
    }
    private function uploadQiniu($url, $cfg, $type = '.png')
    {
        include_once 'qiniu.php';
        $cfg['url'] = $cfg['qiniuUrl'];
        $qiniu = new Qiniu();
        $res = $qiniu->save($url, $cfg, $type);
        return $res;
    }
    private function downLoadImg($mediaid)
    {
        global $_W;
        $url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=' . $this->getAccessToken() . "&media_id={$mediaid}";
        $dst = imagecreatefromstring(file_get_contents($url));
        $cfg = $this->module['config'];
        if ($cfg['isqiniu']) {
            return $this->uploadQiniu($url, $cfg, '.jpg');
        } else {
            if (!empty($_W['setting']['remote']['type'])) {
                $pathname = 'images/' . random(10) . time() . 'pic.jpg';
                imagejpeg($dst, ATTACHMENT_ROOT . $pathname);
                imagedestroy($dst);
                $remotestatus = file_remote_upload($pathname);
                $url = tomedia($pathname);
            } else {
                $path = './attachment/jun_dailys/';
                if (!file_exists($path)) {
                    mkdir($path, 511);
                }
                if (!file_exists($path)) {
                    return array('code' => 0, 'msg' => '上传图片失败！请确保图片存放路径' . IA_ROOT . '/attachment/jun_dailys/文件夹可写');
                }
                $url = $path . random(10) . time() . 'pic.jpg';
                imagejpeg($dst, $url);
                imagedestroy($dst);
                $url = toimage('.' . $url);
            }
            return $url;
        }
    }
    private function getAccessToken()
    {
        global $_W;
        load()->model('account');
        $acid = $_W['acid'];
        if (empty($acid)) {
            $acid = $_W['uniacid'];
        }
        $account = WeAccount::create($acid);
        $token = $account->fetch_available_token();
        return $token;
    }
    private function getFans($openid)
    {
        global $_W;
        load()->model('mc');
        $fans = mc_fansinfo($openid);
        if (empty($fans['nickname']) || empty($fans['avatar'])) {
            $ACCESS_TOKEN = $this->getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ACCESS_TOKEN}&openid={$openid}&lang=zh_CN";
            load()->func('communication');
            $json = ihttp_get($url);
            $userInfo = @json_decode($json['content'], true);
            if ($userInfo['nickname']) {
                $fans['nickname'] = $userInfo['nickname'];
            }
            if ($userInfo['headimgurl']) {
                $fans['avatar'] = $userInfo['headimgurl'];
            }
            $fans['openid'] = $openid;
        }
        $fans['uniacid'] = $_W['uniacid'];
        return $fans;
    }
    private function createWorks($fans, $img = '')
    {
        global $_W;
        $mem = pdo_fetch('select id from ' . tablename($this->modulename . '_member') . " where openid='{$fans['openid']}' and weid='{$_W['uniacid']}'");
        if (empty($mem)) {
            pdo_insert($this->modulename . '_member', array('weid' => $_W['uniacid'], 'openid' => $fans['openid'], 'nickname' => $fans['nickname'], 'avatar' => $fans['avatar'], 'createtime' => time()));
        }
        $sid = pdo_fetchcolumn('select id from ' . tablename($this->modulename . '_style') . " where weid='{$_W['uniacid']}' and status=1 and price=0 order by sort desc, id asc limit 1");
        if (empty($sid)) {
            return '请先导入模板！';
        }
        $mid = pdo_fetchcolumn('select id from ' . tablename($this->modulename . '_music') . " where weid='{$_W['uniacid']}' and status=1 order by sort desc, id asc limit 1");
        if (empty($mid)) {
            return '请先导入音乐！';
        }
        $data = array('weid' => $_W['uniacid'], 'openid' => $fans['openid'], 'nickname' => $fans['nickname'], 'avatar' => $fans['avatar'], 'createtime' => time(), 'styleid' => $sid, 'musicid' => $mid, 'cover' => $img);
        if ($img) {
            $data['imgs'] = serialize(array(array('img' => $img, 'content' => '')));
        }
        pdo_insert($this->modulename . '_works', $data);
        return pdo_insertid();
    }
}