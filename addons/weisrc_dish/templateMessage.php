<?php
class templateMessage
{
    function __construct()
    {

    }

    public function send_template_message($touser, $template_id, $postdata, $access_token, $url = '', $topcolor = '#FF683F')
    {
        $data = array();
        $data['touser'] = $touser;
        $data['template_id'] = trim($template_id);
        $data['url'] = trim($url);
        $data['topcolor'] = trim($topcolor);
        $data['data'] = $postdata;
        $data = json_encode($data);
        $posturl = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
        $res = $this->https_request($posturl, $data);
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