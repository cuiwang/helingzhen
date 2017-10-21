<?php
class class_templateMessage
{
    var $appid = '';
    var $appsecret = '';

    function __construct($appid = NULL, $appsecret = NULL)
    {
        if ($appid && $appsecret) {
            $this->appid = $appid;
            $this->appsecret = $appsecret;
        }
    }

    public function send_template_message($data)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $this->appid . "&secret=" . $this->appsecret . "";
        $res = $this->https_request($url);
        $result = json_decode($res, true);
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $result['access_token'];
        $res = $this->https_request($url, urldecode(json_encode($data)));
        return json_decode($res, true);
    }

    function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}