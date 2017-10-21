<?php
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

class Qiniu {

    private $config = null;
    private $auth   = null;

    public function __construct($config) {
        $this->config = $config;
        require MODULE_ROOT . '/qiniu/autoload.php';
        $this->auth = new Auth($config['ak'], $config['sk']);
    }

    public function putContent($filename, $content,$pipeline) {
        global $_W;
        $policy = array(
            'persistentOps'       => 'avthumb/mp3/ab/128k|saveas/' . Qiniu\base64_urlSafeEncode($this->config['bucket'] . ':' . $filename),
            'persistentNotifyUrl' => '',
            'persistentPipeline'=> $pipeline,
        );
        $token             = $this->auth->uploadToken($this->config['bucket'], null, 3600, $policy);
        $uploadMgr         = new UploadManager();
        list($ret, $error) = $uploadMgr->put($token, $filename, $content);
        if (empty($error)) {
            //$ret['key'];
            return $ret;
            return true;
        } else {
            return false;
        }
    }

    public function delete($filename, $isFull = false) {
        if ($isFull) {
            $filename = str_replace('http://' . $this->config['host'] . "/", '', $filename);
        }
        $mgr   = new BucketManager($this->auth);
        $error = $mgr->delete($this->config['bucket'], $filename);
        if (empty($error)) {
            return true;
        } else {
            return error(-1, $error);
        }
    }
}