<?php
class WxpayAPI
{
    private static $debug = true;
    private static $pay_url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
    private static $query_url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo';
    private static $sendredpack_url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
    public static function pay($params, $extra = array())
    {
        $data                              = array(
            'mch_appid' => $params['mch_appid'],
            'mchid' => $params['mchid'],
            'nonce_str' => $params['nonce_str'],
            'partner_trade_no' => $params['partner_trade_no'],
            'openid' => $params['openid'],
            'check_name' => $params['check_name'],
            're_user_name' => $params['re_user_name'],
            'amount' => $params['amount'] * 100,
            'desc' => $params['desc'],
            'spbill_create_ip' => $params['spbill_create_ip']
        );
        $sign                              = self::sign($data, $extra['sign_key']);
        $xml_data                          = "<xml><mch_appid>{$data['mch_appid']}</mch_appid><mchid>{$data['mchid']}</mchid><nonce_str>{$data['nonce_str']}</nonce_str><partner_trade_no>{$data['partner_trade_no']}</partner_trade_no><openid>{$data['openid']}</openid><check_name>{$data['check_name']}</check_name><re_user_name>{$data['re_user_name']}</re_user_name><amount>{$data['amount']}</amount><desc>{$data['desc']}</desc><spbill_create_ip>{$data['spbill_create_ip']}</spbill_create_ip><sign>{$sign}</sign></xml>";
        $headers                           = array();
        $headers['Content-Type']           = 'application/x-www-form-urlencoded';
        $headers['CURLOPT_SSL_VERIFYPEER'] = false;
        $headers['CURLOPT_SSL_VERIFYHOST'] = false;
        $headers['CURLOPT_SSLCERTTYPE']    = 'PEM';
        $headers['CURLOPT_SSLCERT']        = $extra['apiclient_cert'];
        $headers['CURLOPT_SSLKEYTYPE']     = 'PEM';
        $headers['CURLOPT_SSLKEY']         = $extra['apiclient_key'];
        if (!empty($extra['rootca'])) {
            $headers['CURLOPT_CAINFO'] = $extra['rootca'];
        }
        if (self::$debug) {
            WeUtility::logging('trace', 'xml_data=' . $xml_data);
            WeUtility::logging('trace', 'headers=' . var_export($headers, true));
        }
        load()->func('communication');
        $response = ihttp_request(self::$pay_url, $xml_data, $headers);
        if ($response == '') {
            return '[wxpay-api:pay] response NULL';
        }
        $response = $response['content'];
        if (self::$debug) {
            WeUtility::logging('trace', 'response=' . $response);
        }
        $xml = @simplexml_load_string($response);
        if (empty($xml)) {
            return '[wxpay-api:pay] parse xml NULL';
        }
        if (self::$debug) {
            WeUtility::logging('trace', 'xml=' . var_export($xml, true));
        }
        $return_code  = $xml->return_code ? (string) $xml->return_code : '';
        $return_msg   = $xml->return_msg ? (string) $xml->return_msg : '';
        $result_code  = $xml->result_code ? (string) $xml->result_code : '';
        $err_code     = $xml->err_code ? (string) $xml->err_code : '';
        $err_code_des = $xml->err_code_des ? (string) $xml->err_code_des : '';
        if ($return_code == 'SUCCESS' && $result_code == 'SUCCESS') {
            $ret = array(
                'success' => true,
                'partner_trade_no' => $xml->partner_trade_no,
                'payment_no' => $xml->payment_no,
                'payment_time' => $xml->payment_time
            );
            return $ret;
        } else {
            return $return_code . ':' . $return_msg . ',' . $err_code . ':' . $err_code_des;
        }
    }
    public static function query($params)
    {
    }
    public static function sendredpack($params, $extra = array(), $query_result = false)
    {
        $data     = array(
            'nonce_str' => $params['nonce_str'],
            'mch_billno' => $params['mch_billno'],
            'mch_id' => $params['mch_id'],
            'wxappid' => $params['wxappid'],
            'send_name' => mb_substr($params['send_name'], 0, 8, 'utf-8'),
            're_openid' => $params['re_openid'],
            'total_amount' => $params['total_amount'] * 100,
            'total_num' => $params['total_num'],
            'wishing' => $params['wishing'],
            'client_ip' => $params['client_ip'],
            'act_name' => $params['act_name'],
            'remark' => $params['remark']
        );
        $xml_data = '<xml>';
        foreach ($data as $k => $v) {
            $xml_data .= "<{$k}>{$v}</{$k}>";
        }
        $sign = self::sign($data, $extra['sign_key']);
        $xml_data .= "<sign>{$sign}</sign>";
        $xml_data .= '</xml>';
        $headers                           = array();
        $headers['Content-Type']           = 'application/x-www-form-urlencoded';
        $headers['CURLOPT_SSL_VERIFYPEER'] = false;
        $headers['CURLOPT_SSL_VERIFYHOST'] = false;
        $headers['CURLOPT_SSLCERTTYPE']    = 'PEM';
        $headers['CURLOPT_SSLCERT']        = $extra['apiclient_cert'];
        $headers['CURLOPT_SSLKEYTYPE']     = 'PEM';
        $headers['CURLOPT_SSLKEY']         = $extra['apiclient_key'];
        if (!empty($extra['rootca'])) {
            $headers['CURLOPT_CAINFO'] = $extra['rootca'];
        }
        if (self::$debug) {
            WeUtility::logging('trace', '[wxpay-api:sendredpack] xml_data=' . $xml_data);
            WeUtility::logging('trace', '[wxpay-api:sendredpack] headers=' . var_export($headers, true));
        }
        load()->func('communication');
        $response = ihttp_request(self::$sendredpack_url, $xml_data, $headers);
        if ($response == '') {
            return '[wxpay-api:sendredpack] response NULL';
        }
        $response = $response['content'];
        if (self::$debug) {
            WeUtility::logging('trace', '[wxpay-api:sendredpack] response=' . $response);
        }
        $xml = @simplexml_load_string($response);
        if (empty($xml)) {
            return '[wxpay-api:sendredpack] parse xml NULL';
        }
        if (self::$debug) {
            WeUtility::logging('trace', '[wxpay-api:sendredpack] xml=' . var_export($xml, true));
        }
        $return_code  = $xml->return_code ? (string) $xml->return_code : '';
        $return_msg   = $xml->return_msg ? (string) $xml->return_msg : '';
        $result_code  = $xml->result_code ? (string) $xml->result_code : '';
        $err_code     = $xml->err_code ? (string) $xml->err_code : '';
        $err_code_des = $xml->err_code_des ? (string) $xml->err_code_des : '';
        if ($query_result) {
            if ($return_code == 'SUCCESS' && $result_code == 'SUCCESS') {
                $ret = array(
                    'success' => true,
                    'send_listid' => (string) $xml->send_listid,
                    'mch_billno' => (string) $xml->mch_billno,
                    'send_time' => (string) $xml->send_time,
                    'return_code' => $return_code,
                    'return_msg' => $return_msg,
                    'err_code' => $err_code,
                    'err_code_des' => $err_code_des
                );
                return $ret;
            } else {
                return $return_code . ':' . $return_msg . ',' . $err_code . ':' . $err_code_des;
            }
        }
        if ($return_code == 'SUCCESS') {
            if ($result_code == 'SUCCESS') {
                $ret = array(
                    'success' => true,
                    'send_listid' => (string) $xml->send_listid,
                    'mch_billno' => (string) $xml->mch_billno,
                    'send_time' => (string) $xml->send_time,
                    'return_code' => $return_code,
                    'return_msg' => $return_msg,
                    'err_code' => $err_code,
                    'err_code_des' => $err_code_des
                );
                return $ret;
            } else if ($err_code == 'SYSTEMERROR') {
                $ret = array(
                    'success' => true,
                    'send_listid' => (string) $xml->send_listid,
                    'mch_billno' => (string) $xml->mch_billno,
                    'send_time' => (string) $xml->send_time,
                    'return_code' => $return_code,
                    'return_msg' => $return_msg,
                    'err_code' => $err_code,
                    'err_code_des' => $err_code_des
                );
                return $ret;
            }
        } else {
            return $return_code . ':' . $return_msg . ',' . $err_code . ':' . $err_code_des;
        }
    }
    public static function sign($data, $sign_key)
    {
        ksort($data);
        $data_str = '';
        foreach ($data as $k => $v) {
            if ($v == '' || $k == 'sign') {
                continue;
            }
            $data_str .= "$k=$v&";
        }
        $data_str .= "key=" . $sign_key;
        $sign = strtoupper(md5($data_str));
        return $sign;
    }
}
?>