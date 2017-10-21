<?php
/**
 * Created by  Administrator
 * Date: 15-7-28
 * Time: 上午11:14 
 */

require_once 'Cryptogram.class.php';

class cryto {

    protected   $key ;

    function  __construct($key=""){
        global $_W;
        $this->key = empty($key)?$_W['config']['epay']['deskey']:$key;
    }

    public function generateAuthenticator($code,$key=""){
        $key = empty($key)?$this->key:$key;
        $sha = sha1($code,1);
        $des = Cryptogram::encryptByKey($sha,$key);
        $ret = base64_encode($des);
        return $ret;
    }
    public function  encryptBase643DES($code,$key=""){
        $key = empty($key)?$this->key:$key;
        $des = Cryptogram::encryptByKey($code,$key);
        $ret = base64_encode($des);
        return $ret;
    }

    public function  decryptBase643DES($code,$key=""){
        $key = empty($key)?$this->key:$key;
        $des = Cryptogram::decryptByKey($code,$key);
        return $des;
    }

}