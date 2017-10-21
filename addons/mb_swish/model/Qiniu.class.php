<?php
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
class Qiniu{
    private $config = null;
    private $auth = null;
    public function __construct($config){
        $this -> config = $config;
        require MB_ROOT . '/lib/qiniu/autoload.php';
        $this -> auth = new Auth($config['key'], $config['secret']);
    }
    public function put($filename, $file){
        $policy = array('persistentOps' => 'avthumb/mp3/ab/128k', 'persistentNotifyUrl' => MODULE_URL,);
        $token = $this -> auth -> uploadToken($this -> config['bucket'], null, 3600, $policy);
        $uploadMgr = new UploadManager();
        list($ret, $error) = $uploadMgr -> putFile($token, $filename, $file);
        if(empty($error)){
            $url = "http://{$this->config['host']}/{$ret['key']}";
            return $url;
        }else{
            return error(-1, $error);
        }
    }
    public function putContent($filename, $content){
        global $_W;
        $url = url('entry', array('do' => 'callback', 'm' => 'mb_swish', 'i' => $_W['uniacid']));
        $url = $_W['siteroot'] . 'app/' . substr($url, 2);
        $policy = array('persistentOps' => 'avthumb/mp4/ab/64k/ar/44100/acodec/libfaac|saveas/' . Qiniu\base64_urlSafeEncode($this -> config['bucket'] . ':' . $filename), 'persistentNotifyUrl' => $url,);
        $token = $this -> auth -> uploadToken($this -> config['bucket'], null, 3600, $policy);
        $uploadMgr = new UploadManager();
        list($ret, $error) = $uploadMgr -> put($token, $filename, $content);
        if(empty($error)){
            $url = "http://{$this->config['host']}/{$ret['key']}";
            return $url;
        }else{
            return error(-1, '上传7牛发生错误, 详情: ' . $error -> message());
        }
    }
    public function delete($filename, $isFull = false){
        if($isFull){
            $filename = str_replace('http://' . $this -> config['host'] . "/", '', $filename);
        }
        $mgr = new BucketManager($this -> auth);
        $error = $mgr -> delete($this -> config['bucket'], $filename);
        if(empty($error)){
            return true;
        }else{
            return error(-1, $error);
        }
    }
}
