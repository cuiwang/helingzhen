<?php

require_once 'qiniu/io.php';
require_once 'qiniu/rs.php';
class Qiniu
{
    public function save($url, $config, $ext = '')
    {
        set_time_limit(0);
        if (empty($url)) {
            return '';
        }
        if (empty($ext)) {
            $ext = strrchr($url, '.');
        }
        $filename = random(30) . $ext;
        $contents = @file_get_contents($url);
        $storename = $filename;
        $bu = $config['bucket'] . ':' . $storename;
        $accessKey = $config['access_key'];
        $secretKey = $config['secret_key'];
        Qiniu_SetKeys($accessKey, $secretKey);
        $putPolicy = new Qiniu_RS_PutPolicy($bu);
        if ($ext == '.mp3') {
            $pipeline = $config['pipeline'];
            $fops = 'avthumb/mp3/ab/128k';
            $savekey = $this->base64_urlSafeEncode($bu);
            $fops = $fops . '|saveas/' . $savekey;
            $putPolicy->PersistentOps = $fops;
            $putPolicy->PersistentPipeline = $pipeline;
        }
        $upToken = $putPolicy->Token(null);
        $putExtra = new Qiniu_PutExtra();
        $putExtra->Crc32 = 1;
        list($ret, $err) = Qiniu_Put($upToken, $storename, $contents, $putExtra);
        if (!empty($err)) {
            return array('code' => 0, 'msg' => json_encode($err));
        }
        return trim($config['url']) . '/' . $filename;
    }
    public function delete($config, $filename)
    {
        $accessKey = $config['access_key'];
        $secretKey = $config['secret_key'];
        Qiniu_SetKeys($accessKey, $secretKey);
        Qiniu_RS_Delete(new Qiniu_MacHttpClient(null), $config['bucket'], $filename);
    }
    function base64_urlSafeEncode($data)
    {
        $find = array('+', '/');
        $replace = array('-', '_');
        return str_replace($find, $replace, base64_encode($data));
    }
}