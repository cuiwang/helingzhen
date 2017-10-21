<?php

  require_once  GARCIA_QNIU."autoload.php";
  use Qiniu\Auth;
  use Qiniu\Storage\UploadManager;

  class _qniu{


     function __construct($accessKey,$secretKey,$bucket,$url){
        // 用于签名的公钥和私钥
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
        $this->bucket  = $bucket;
        $this->url = $url;
           $this->_Init();
     }
     private function _Init(){
          try {
               $this->auth = new Auth($this->accessKey, $this->secretKey);

          } catch (OssException $e) {
              return '七牛链接失败';
          }
      }
      /**
       * $filePath 本地文件
       * $key 上传文件名字
       */
      public  function upload($filePath,$key,$token){
          if(empty($token)){
            $token = $this->auth->uploadToken($this->bucket);
          }
          // 初始化 UploadManager 对象并进行文件的上传
          $uploadMgr = new UploadManager();

          list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);

          if ($err !== null) {
               return array('status'=>1,'msg'=>$err);
          } else {
               $this->url = str_replace(array('/','http:','https:'),array('','',''),$this->url);
              return array('img'=>"http://".$this->url."/".$ret['key'],'token'=>$token,'msg'=>$err);
          }
      }
  }

 ?>
