<?php
defined('IN_IA') or exit('Access Denied');
class Siyuan_CmsModuleSite extends WeModuleSite
{
    protected $modules_bindings;
    public function __construct()
    {
        global $_GPC;
        $this->modulename = 'siyuan_cms';
        $this->__define   = IA_ROOT . "/addons/siyuan_cms/module.php";
        load()->model('module');
        $this->module           = module_fetch($this->modulename);
        $dos                    = array(
            'index',
            'huodong',
            'partner',
            'my'
        );
        $sql                    = "SELECT eid,do FROM " . tablename('modules_bindings') . "WHERE `do` IN ('" . implode("','", $dos) . "') AND `entry`='cover' AND module='siyuan_cms'";
        $this->modules_bindings = pdo_fetchall($sql, array(), 'do');
        load()->func('tpl');
        load()->func('file');
    }
    public function Checkeduseragent()
    {
        global $_W, $_GPC;
        $title     = '无法访问';
        $set       = pdo_fetch("SELECT name,ad,logo,qr,color FROM " . tablename('siyuan_cms_setting') . " WHERE weid = :weid ", array(
            ':weid' => $_W['uniacid']
        ));
        $menu      = pdo_fetchall("SELECT displayorder,thumb,url,title FROM " . tablename('siyuan_cms_menu') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder ASC LIMIT 30");
        $useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
        if (strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false) {
            include $this->template('cms/pc');
            die;
        }
    }
    public function doWebPayment()
    {
        global $_W, $_GPC;
        $qcl = pdo_fetch("SELECT * FROM " . tablename('siyuan_cms_setting') . " WHERE weid = :weid ", array(
            ':weid' => $_W['uniacid']
        ));
        if ($_W['ispost']) {
            $payment        = $_FILES['payment'];
            $save_dir       = __DIR__ . '/payment/';
            $cert_save_name = random(32) . '.pem';
            $key_save_name  = random(32) . '.pem';
            move_uploaded_file($payment['tmp_name']['apiclient_cert'], $save_dir . $cert_save_name);
            move_uploaded_file($payment['tmp_name']['apiclient_key'], $save_dir . $key_save_name);
            $data = array(
                'apiclient_cert' => $save_dir . $cert_save_name,
                'apiclient_key' => $save_dir . $key_save_name
            );
            pdo_update('siyuan_cms_setting', $data, array(
                'id' => $qcl['id']
            ));
            message('保存成功！', $this->createWebUrl('payment'));
        }
        include $this->template('web/cms/payment');
    }
    static function downloadFromWxServer($media_ids, $settings)
    {
        global $_W, $_GPC;
        $media_ids = explode(',', $media_ids);
        if (!$media_ids) {
            echoJson(array(
                'res' => '101',
                'message' => 'media_ids error'
            ));
        }
        load()->classs('weixin.account');
        $accObj       = WeixinAccount::create($_W['account']['acid']);
        $access_token = $accObj->fetch_token();
        load()->func('communication');
        load()->func('file');
        $contentType["image/gif"]  = ".gif";
        $contentType["image/jpeg"] = ".jpeg";
        $contentType["image/png"]  = ".png";
        foreach ($media_ids as $id) {
            $url      = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=" . $access_token . "&media_id=" . $id;
            $data     = ihttp_get($url);
            $filetype = $data['headers']['Content-Type'];
            $filename = date('YmdHis') . '_' . rand(1000000000, 9999999999.0) . '_' . rand(1000, 9999) . $contentType[$filetype];
            $wr       = file_write('/images/siyuan_cms/' . $filename, $data['content']);
            if ($wr) {
                $file_succ[] = array(
                    'name' => $filename,
                    'path' => '/images/siyuan_cms/' . $filename,
                    'type' => 'local'
                );
            }
        }
        foreach ($file_succ as $key => $value) {
            $r = file_remote_upload('images/siyuan_cms/' . $value['name']);
            if (is_error($r)) {
                unset($file_succ[$key]);
                continue;
            }
            $file_succ[$key]['name'] = tomedia('images/siyuan_cms/' . $value['name']);
            $file_succ[$key]['type'] = 'other';
        }
        return $file_succ;
    }
    public function doMobilePay()
    {
        global $_W, $_GPC;
        if (empty($_GPC['id'])) {
            message('抱歉，参数错误！', '', 'error');
        }
        $orderid           = intval($_GPC['id']);
        $uniacid           = $_W['uniacid'];
        $order             = pdo_fetch("SELECT * FROM " . tablename('siyuan_cms_order') . " WHERE id ='{$orderid}'");
        $params['tid']     = $order['ordersn'];
        $params['user']    = $_W['fans']['from_user'];
        $params['fee']     = $order['price'];
        $params['title']   = $order['title'];
        $params['ordersn'] = $order['ordersn'];
        include $this->template('cms/pay');
    }
    public function payResult($params)
    {
        global $_W, $_GPC;
        $uniacid         = $_W['uniacid'];
        $fee             = intval($params['fee']);
        $data            = array(
            'status' => $params['result'] == 'success' ? 1 : 0
        );
        $paytype         = array(
            'credit' => '1',
            'wechat' => '2',
            'alipay' => '3'
        );
        $data['paytype'] = $paytype[$params['type']];
        if ($params['type'] == 'wechat') {
            $data['transid'] = $params['tag']['transaction_id'];
        }
        if ($params['from'] == 'return') {
            $order = pdo_fetch("SELECT * FROM " . tablename('siyuan_cms_order') . " WHERE ordersn ='{$params['tid']}'");
            if ($order['status'] != 1) {
                if ($params['result'] == 'success') {
                    $data['status'] = 1;
                }
                if ($order['type'] == "tel") {
                    pdo_update('siyuan_cms_tel', array(
                        'status' => 1
                    ), array(
                        'ordersn' => $order['ordersn']
                    ));
                }
                if ($order['type'] == "shop") {
                    pdo_update('siyuan_cms_shop', array(
                        'status' => 1
                    ), array(
                        'ordersn' => $order['ordersn']
                    ));
                }
                if ($order['type'] == "huodong") {
                    pdo_update('siyuan_cms_huodong_users', array(
                        'status' => 1
                    ), array(
                        'ordersn' => $order['ordersn']
                    ));
                }
                pdo_update('siyuan_cms_order', $data, array(
                    'ordersn' => $params['tid']
                ));
                $upcc['status'] = 1;
                if ($params['type'] == $credit) {
                    message('', $this->createMobileUrl('my'), 'success');
                } else {
                    message('', '../../app/' . $this->createMobileUrl('my'), 'success');
                }
            }
        }
    }
    public function doMobileQiniuVod($file, $type)
    {
        global $_W, $_GPC;
        $weid       = $_W['uniacid'];
        $systeminfo = pdo_fetch("SELECT qnscode,qnym,qnak,qnsk FROM " . tablename('siyuan_cms_setting') . " WHERE weid = :weid ", array(
            ':weid' => $_W['uniacid']
        ));
        if ($_FILES['file']['size'] > 1024 * 1024 * 20 || $_FILES['file']['size'] <= 0) {
            $data          = array();
            $data['error'] = 1;
            exit(json_encode($data));
        } else {
            $prefix   = substr($_FILES['file']['name'], strpos($_FILES['file']['name'], '.'));
            $filename = date('YmdHis') . '_' . rand(1000000000, 9999999999) . $prefix;
            $filename = 'siyuan_cms/' . $filename;
            require_once(IA_ROOT . '/framework/library/qiniu/autoload.php');
            $accessKey   = $systeminfo['qnak'];
            $secretKey   = $systeminfo['qnsk'];
            $bucket      = $systeminfo['qnscode'];
            $auth        = new Qiniu\Auth($accessKey, $secretKey);
            $uploadmgr   = new Qiniu\Storage\UploadManager();
            $putpolicy   = Qiniu\base64_urlSafeEncode(json_encode(array(
                'scope' => $bucket . ':' . $filename
            )));
            $uploadtoken = $auth->uploadToken($bucket, $filename, 36000, $putpolicy);
            list($ret, $err) = $uploadmgr->putFile($uploadtoken, $filename, $_FILES['file']['tmp_name']);
            if ($err !== null) {
                return error(1, '远程附件上传失败，请检查配置并重新上传');
            } else {
                $data             = array();
                $data['filename'] = "http://" . $systeminfo['qnym'] . "/" . $filename;
                $data['tishi']    = "上传成功";
                exit(json_encode($data));
            }
        }
    }
    public function getToken()
    {
        global $_W;
        load()->classs('weixin.account');
        $accObj       = WeixinAccount::create($_W['uniacid']);
        $access_token = $accObj->fetch_token();
        return $access_token;
    }
    public function sendMBXX($access_token, $data)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
        ihttp_post($url, json_encode($data));
    }
}