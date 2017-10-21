<?php

class Yike_Qrcode {
    public function __construct()
    {
        $this->module_name = 'yike_guess';
    }
    public function getQrcode($member) {
        global $_W;
        $acid = $_W['acid'];
        $uniacccount = m('common')->getAccount();
        $qr = pdo_fetch('select * from ' . tablename($this->module_name.'_qr') . ' where openid=:openid and acid=:acid and type=4 limit 1', array(
            ':openid' => $member['openid'],
            ':acid' => $acid
        ));
        if (empty($qr)) {
            $sceneid = pdo_fetchcolumn("SELECT qrcid FROM " . tablename('qrcode') . " WHERE acid = :acid and model=2 ORDER BY qrcid DESC LIMIT 1", array(
                ':acid' => $acid
            ));
            $barcode['action_info']['scene']['scene_id'] = intval($sceneid) + 1;
            if ($barcode['action_info']['scene']['scene_id'] > 100000) {
                return error(-1, '抱歉，永久二维码已经生成最大数量，请先删除一些。');
            }
            $barcode['action_name'] = 'QR_LIMIT_SCENE';
            $result = $uniacccount->barCodeCreateFixed($barcode);
            if (is_error($result)) {
                return error(-1, "公众平台返回接口错误. <br />错误代码为: {$result['errorcode']} <br />错误信息为: {$result['message']}");
            }
            $qrimg = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" . $result['ticket'];
            $ims_qrcode = array(
                'uniacid' => $_W['uniacid'],
                'acid' => $_W['acid'],
                'qrcid' => $barcode['action_info']['scene']['scene_id'],
                "model" => 2,
                "name" => strtoupper($this->module_name)."_QRCODE",
                "keyword" => strtoupper($this->module_name),
                "expire" => 0,
                "createtime" => time(),
                "status" => 1,
                'url' => $result['url'],
                "ticket" => $result['ticket']
            );
            pdo_insert('qrcode', $ims_qrcode);
            $qr = array(
                'acid' => $acid,
                'openid' => $member['openid'],
                'type' => 4,
                'sceneid' => $barcode['action_info']['scene']['scene_id'],
                'ticket' => $result['ticket'],
                'qrimg' => $qrimg,
                'url' => $result['url']
            );
            pdo_insert($this->module_name.'_qr', $qr);
            $qr['id'] = pdo_insertid();
            $qr['current_qrimg'] = $qrimg;
        } else {
            $qr['current_qrimg'] = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" . $qr['ticket'];
        }
        return $qr;
    }

    public function getRealData($data) {
        $data['left'] = intval(str_replace('px', '', $data['left'])) * 2;
        $data['top'] = intval(str_replace('px', '', $data['top'])) * 2;
        $data['width'] = intval(str_replace('px', '', $data['width'])) * 2;
        $data['height'] = intval(str_replace('px', '', $data['height'])) * 2;
        $data['size'] = intval(str_replace('px', '', $data['size'])) * 2;
        $data['src'] = tomedia($data['src']);
        return $data;
    }

    public function createImage($imgurl) {
        load()->func('communication');
        $resp = ihttp_request($imgurl);
        return imagecreatefromstring($resp['content']);
    }

    public function mergeImage($target, $data, $imgurl) {
        $img = $this->createImage($imgurl);
        $w = imagesx($img);
        $h = imagesy($img);
        imagecopyresized($target, $img, $data['left'], $data['top'], 0, 0, $data['width'], $data['height'], $w, $h);
        imagedestroy($img);
        return $target;
    }

    public function mergeText($target, $data, $text) {
        $font = IA_ROOT . "/addons/yike_guess/static/fonts/msyh.ttf";
        $colors = $this->hex2rgb($data['color']);
        $color = imagecolorallocate($target, $colors['red'], $colors['green'], $colors['blue']);
        imagettftext($target, $data['size'], 0, $data['left'], $data['top'] + $data['size'], $color, $font, $text);
        return $target;
    }

    function hex2rgb($colour) {
        if ($colour[0] == '#') {
            $colour = substr($colour, 1);
        }
        if (strlen($colour) == 6) {
            list($r, $g, $b) = array(
                $colour[0] . $colour[1],
                $colour[2] . $colour[3],
                $colour[4] . $colour[5]
            );
        } elseif (strlen($colour) == 3) {
            list($r, $g, $b) = array(
                $colour[0] . $colour[0],
                $colour[1] . $colour[1],
                $colour[2] . $colour[2]
            );
        } else {
            return false;
        }
        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);
        return array(
            'red' => $r,
            'green' => $g,
            'blue' => $b
        );
    }

    public function getPoster($poster, $member, $upload = true) {
        global $_W;
        $path = IA_ROOT . "/addons/".$this->module_name."/data/poster/" . $_W['uniacid'] . "/";
        if (!is_dir($path)) {
            load()->func('file');
            mkdirs($path);
        }
        $md5 = md5(json_encode(array(
            'openid' => $member['openid'],
            'data' => $poster['data'],
            'version' => 1
        )));
        $file = $md5 . '.jpg';
        if (!is_file($path . $file)) {
            $qr = $this->getQrcode($member);
            set_time_limit(0);
            @ini_set('memory_limit', '256M');
            $target = imagecreatetruecolor(640, 1008);
            $bg = $this->createImage(tomedia($poster['bg']));
            imagecopy($target, $bg, 0, 0, 0, 0, 640, 1008);
            imagedestroy($bg);
            $data = json_decode(str_replace('&quot;', "'", $poster['data']), true);
            foreach ($data as $d) {
                $d = $this->getRealData($d);
                if ($d['type'] == 'head') {
                    $avatar = preg_replace('/\/0$/i', '/96', $member['avatar']);
                    $target = $this->mergeImage($target, $d, $avatar);
                } else if ($d['type'] == 'img') {
                    $target = $this->mergeImage($target, $d, $d['src']);
                } else if ($d['type'] == 'qr') {
                    $target = $this->mergeImage($target, $d, $qr['qrimg']);
                } else if ($d['type'] == 'nickname') {
                    $target = $this->mergeText($target, $d, $member['nickname']);
                } else if ($d['type'] == 'word') {
                    $target = $this->mergeText($target, $d, '我为世界代言');
                }
            }
            imagejpeg($target, $path . $file);
            imagedestroy($target);
        }
        $img = $_W['siteroot'] . "addons/".$this->module_name."/data/poster/" . $_W['uniacid'] . "/" . $file;
        if ($qr['qrimg'] != $qr['current_qrimg'] || empty($qr['mediaid']) || empty($qr['createtime']) || $qr['createtime'] + 3600 * 24 * 3 - 7200 < time()) {
            $mediaid       = $this->uploadImage($path . $file);
            $qr['mediaid'] = $mediaid;
            pdo_update($this->module_name.'_qr', array(
                'mediaid' => $mediaid,
                'createtime' => time()
            ), array(
                'id' => $qr['id']
            ));
        }
        return $img;
    }

    public function createRule() {
        global $_W;
        $rule = pdo_fetch("select * from " . tablename('rule') . ' where uniacid=:uniacid and module=:module and name=:name  limit 1', array(
            ':uniacid' => $_W['uniacid'],
            ':module' => $this->module_name,
            ':name' => $this->module_name.":poster"
        ));
        if (empty($rule)) {
            $rule_data = array(
                'uniacid' => $_W['uniacid'],
                'module' => $this->module_name,
                'displayorder' => 0,
                'status' => 1
            );
            pdo_insert('rule', $rule_data);
            $rid          = pdo_insertid();
            $keyword_data = array(
                'uniacid' => $_W['uniacid'],
                'rid' => $rid,
                'module' => $this->module_name,
                'content' => '竞猜二维码',
                'type' => 1,
                'displayorder' => 0,
                'status' => 1
            );
            pdo_insert('rule_keyword', $keyword_data);
        } else {
            $content = '竞猜二维码';
            pdo_update('rule_keyword', array(
                'content' => $content
            ), array(
                'rid' => $rule['id']
            ));
        }
        $ruleauto = pdo_fetch("select * from " . tablename('rule') . ' where uniacid=:uniacid and module=:module and name=:name  limit 1', array(
            ':uniacid' => $_W['uniacid'],
            ':module' => $this->module_name,
            ':name' => $this->module_name.":poster:auto",
        ));
        if (empty($ruleauto)) {
            $rule_data = array(
                'uniacid' => $_W['uniacid'],
                'module' => $this->module_name,
                'name' => $this->module_name.":poster:auto",
                'displayorder' => 0,
                'status' => 1
            );
            pdo_insert('rule', $rule_data);
            $rid          = pdo_insertid();
            $keyword_data = array(
                'uniacid' => $_W['uniacid'],
                'rid' => $rid,
                'module' => $this->module_name,
                'content' => strtoupper($this->module_name),
                'type' => 1,
                'displayorder' => 0,
                'status' => 1
            );
            pdo_insert('rule_keyword', $keyword_data);
        }
    }

    public function uploadImage($img)
    {
        load()->func('communication');
        $account      = m('common')->getAccount();
        $access_token = $account->fetch_token();
        $resp         = ihttp_request("http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token={$access_token}&type=image", array(
            'media' => '@' . $img
        ));
        $content      = @json_decode($resp['content'], true);
        return $content['media_id'];
    }

    public function getQRBySceneid($sceneid = 0)
    {
        global $_W;
        if (empty($sceneid)) {
            return false;
        }
        return pdo_fetch('select * from ' . tablename($this->module_name.'_qr') . ' where sceneid=:sceneid and acid=:acid and type=4 limit 1', array(
            ':sceneid' => $sceneid,
            ':acid' => $_W['acid']
        ));
    }
}