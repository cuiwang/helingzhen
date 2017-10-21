<?php



require_once  GARCIA_OSS."autoload.php";
use OSS\OssClient;
use OSS\Core\OssException;


  class _ali{

     function __construct($accessKey,$secretKey,$endpoint,$bucket,$url){
         $this->accessKey = $accessKey;
         $this->secretKey = $secretKey;
        $this->endpoint = $endpoint;
          $this->bucket = $bucket;
          $this->url = $url;
         $this->_Init();
         $this->_Host();
     }


    private function _Init(){
         try {
             $this->ossClient = new OssClient($this->accessKey, $this->secretKey, $this->endpoint);

         } catch (OssException $e) {
             return 'OSS链接失败';
         }
     }

     private function _Host(){
       if(empty($this->url)){
         if (!strexists($this->endpoint, 'http://') && !strexists($this->endpoint,'https://')) {
           $this->_url = 'http://'. trim($this->bucket.".".$this->endpoint);
         } else {
           $this->_url = trim($this->bucket.".".$this->endpoint);
         }
       }else{
         if (!strexists($this->url, 'http://') && !strexists($this->url,'https://')) {
           $this->_url = 'http://'. trim($this->url);
         } else {
           $this->_url = trim($this->url);
         }
       }

     }


     public function multiuploadFile($object,$file){
        $options = array();
        try{
            $this->ossClient->multiuploadFile($this->bucket, $object, $file, $options);
        } catch(OssException $e) {
            return array('status'=>0,'msg'=>$e->getMessage());
        }

        return array('status'=>1,'imgurl'=>$this->_url."/".$object);
     }





  }


 ?>
