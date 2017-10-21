<?php

if (!defined('IN_IA')) {
	exit('Access Denied');
} 
class Tyzm_redpack{
	public function __construct() {
		global $_W;
	} 
	public $tablereply = 'tyzm_diamondvote_reply';
	public $tablevoteuser = 'tyzm_diamondvote_voteuser';
	public $tablevotedata = 'tyzm_diamondvote_votedata';
	public $tablegift = 'tyzm_diamondvote_gift';
	public $tablecount = 'tyzm_diamondvote_count';
	public $table_fans = 'tyzm_diamondvote_fansdata';
	public $tableredpack = 'tyzm_diamondvote_redpack';
	public $tablefriendship = 'tyzm_diamondvote_friendship';
	public $tablelooklist = 'tyzm_diamondvote_looklist';
	public $tableviporder = 'tyzm_diamondvote_viporder';



	 public function sendredpack($redpackid,$rid)
    {
        global $_W;
        $rid          = intval($rid);
        $reply = pdo_fetch("SELECT config FROM ".tablename($this->tablereply)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        $configdata=@unserialize($reply['config']);
		
		$table=$this->tableredpack;
		
        $lickylog     = pdo_fetch("SELECT * FROM " . tablename($table) . " WHERE rid=:rid  AND id=:id  ", array(
            ':rid' => $rid,
            ':id' => $redpackid
        ));

		
        
        if (!empty($lickylog)){
            if ($lickylog['result_code'] != 'SUCCESS') {
                //执行发红包操作 start
				$modulelist = uni_modules(false);
	            $config = $modulelist['tyzm_diamondvote']['config'];
                if (empty($config['mchid'])||empty($config['apikey'])) {
                    pdo_update($table, array(
                        'return_msg' => "红包参数没有设定，至“参数设置”修改！"
                    ), array(
                        'openid' => $lickylog['openid']
                    ));
                    return "没有设定支付参数";
                }
                $data['wxappid']      = $config['key'];
                $data['mch_id']       = $config['mchid'];
                $data['mch_billno']   = $lickylog['mch_billno'];
                $data['client_ip']    = $_W['clientip']; //获得服务器IP
                $data['re_openid']    = $lickylog['openid'];
                $data['total_amount'] = $lickylog['total_amount'];
                $data['send_name']    = $this->str_cut($configdata['send_name'], 32, '');
                $data['act_name']     = $this->str_cut($configdata['act_name'], 32, '');
                $data['remark']       = $this->str_cut($configdata['remark'], 256, '');
                $data['wishing']      = $this->str_cut($configdata['wishing'], 128, '');
                $data['total_num']    = 1;
                $data['nonce_str']    = $this->createNoncestr();
                $data['sign']         = $this->getSign($data, $config['apikey']);
                $xml                  = $this->arrayToXml($data);
                $url                  = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
                $re                   = $this->wxHttpsRequestPem($xml, $url);
                if ($re == 58) {
                    pdo_update($table, array(
                        'return_msg' => "证书有问题"
                    ), array(
                        'id' => $lickylog['id']
                    ));
                    return "证书错误";
                }
                $data['createtime']   = TIMESTAMP;
                $rearr                = $this->xmlToArray($re);
                $rearr['return_data'] = json_encode($rearr);
                unset($rearr['wxappid'], $rearr['mch_id'], $rearr['mch_billno'], $rearr['re_openid']); //删除多余数据
                //file_put_contents(MODULE_ROOT."/".time()."test.txt",json_encode($rearr));
                if (!empty($rearr['return_code']) && $rearr['err_code'] != 'SEND_FAILED') {
                    $rearr['client_ip'] = $_W['clientip'];
                    $rearr['send_time'] = time();
                    pdo_update($table, $rearr, array(
                        'id' => $lickylog['id']
                    ));
                    if ($rearr['result_code'] == "SUCCESS") {
						return 88 ;//成功			
                    } else {
                        return $rearr['return_msg'];
                    }
                } else {
                    return $rearr['return_msg'];
                }
                //执行发红包操作 start 
            } else {
                return 1;//红包已发送成功！
            }
        } else {
            return 0;//没有红包列表
        }
        
    }
        
    public function getSign($Obj, $key) //生成签名
    {
        foreach ($Obj as $k => $v) {
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        
        $String = $this->formatBizQueryParaMap($Parameters, false);
        
        //echo '【string1】'.$String.'</br>';
        
        //签名步骤二：在string后加入KEY
        
        $String = $String . "&key=" . $key; // 商户后台设置的key
        
        //echo "【string2】".$String."</br>";
        
        //签名步骤三：MD5加密
        
        $String = md5($String);
        
        //echo "【string3】 ".$String."</br>";
        
        //签名步骤四：所有字符转为大写
        
        $result_ = strtoupper($String);
        
        //echo "【result】 ".$result_."</br>";
        
        return $result_;
        
    }
    
    /**
    
    *  作用：格式化参数，签名过程需要使用
    
    */
    
    public function formatBizQueryParaMap($paraMap, $urlencode)
    {
        
        $buff = "";
        
        ksort($paraMap);
        
        foreach ($paraMap as $k => $v) {
            
            if ($urlencode) {
                
                $v = urlencode($v);
                
            }
            
            $buff .= $k . "=" . $v . "&";
            
        }
        
        $reqPar;
        
        if (strlen($buff) > 0) {
            
            $reqPar = substr($buff, 0, strlen($buff) - 1);
            
        }
        
        return $reqPar;
        
    }
    
    /**
    
    *  作用：产生随机字符串，不长于32位
    
    */
    
    public function createNoncestr($length = 32)
    {
        
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        
        $str = "";
        
        for ($i = 0; $i < $length; $i++) {
            
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            
        }
        
        return $str;
        
    }
    
    /**
    
    *  作用：array转xml
    
    */
    
    public function arrayToXml($arr)
    {
        
        $xml = "<xml>";
        
        foreach ($arr as $key => $val) {
            
            // if (is_numeric($val))
            
            if (0) {
                
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
                
            } else {
                
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
                
            }
            
        }
        
        $xml .= "</xml>";
        
        return $xml;
        
    }
    
    /**
    
    *  作用：将xml转为array
    
    */
    
    public function xmlToArray($xml)
    {
        
        //将XML转为array        
        
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        
        return $array_data;
        
    }
    
    
    public function wxHttpsRequestPem($vars, $url, $second = 30, $aHeader = array())
    {
        global $_W;
		
		$modulelist = uni_modules(false);
	    $config = $modulelist['tyzm_diamondvote']['config'];
        $ch = curl_init();
        
        //超时时间
        
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        //这里设置代理，如果有的话
        
        //curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
        
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        
        curl_setopt($ch, CURLOPT_URL, $url);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        //以下两种方式需选择一种
        
        //第一种方法，cert 与 key 分别属于两个.pem文件
        
        //默认格式为PEM，可以注释
        
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
        
        curl_setopt($ch, CURLOPT_SSLCERT, TYZM_MODEL . '/template/certdata/' . $_W['uniacid'] . '/' .  $config['certkey'] . '/apiclient_cert.pem');
        
        //默认格式为PEM，可以注释
        
        curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLKEY, TYZM_MODEL . '/template/certdata/' . $_W['uniacid'] . '/' .  $config['certkey'] . '/apiclient_key.pem');
        if (count($aHeader) >= 1) {
            
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
            
        }
        
        curl_setopt($ch, CURLOPT_POST, 1);
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        
        $data = curl_exec($ch);
        
        if ($data) {
            
            curl_close($ch);
            
            return $data;
            
        } else {
            $error = curl_errno($ch);
            return $error; //提交错误参数
            curl_close($ch);
            return false;
        }
        
    }
    
    public function makeRequest($url, $param, $httpMethod = 'GET')
    {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        
        if ($httpMethod == 'GET') {
            curl_setopt($oCurl, CURLOPT_URL, $url . "?" . http_build_query($param));
            curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        } else {
            curl_setopt($oCurl, CURLOPT_URL, $url);
            curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($oCurl, CURLOPT_POST, 1);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($param));
        }
        
        $sContent = curl_exec($oCurl);
        $aStatus  = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return FALSE;
        }
    }
    public function str_cut($string, $length, $dot = '', $charset = "utf-8")
    {
        $strlen = strlen($string);
        if ($strlen <= $length)
            return $string;
        $string = str_replace(array(
            ' ',
            '&nbsp;',
            '&amp;',
            '&quot;',
            '&#039;',
            '&ldquo;',
            '&rdquo;',
            '&mdash;',
            '&lt;',
            '&gt;',
            '&middot;',
            '&hellip;'
        ), array(
            '∵',
            ' ',
            '&',
            '"',
            "'",
            '“',
            '”',
            '—',
            '<',
            '>',
            '·',
            '…'
        ), $string);
        $strcut = '';
        if (strtolower($charset) == 'utf-8') {
            $length = intval($length - strlen($dot) - $length / 3);
            $n      = $tn = $noc = 0;
            while ($n < strlen($string)) {
                $t = ord($string[$n]);
                if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1;
                    $n++;
                    $noc++;
                } elseif (194 <= $t && $t <= 223) {
                    $tn = 2;
                    $n += 2;
                    $noc += 2;
                } elseif (224 <= $t && $t <= 239) {
                    $tn = 3;
                    $n += 3;
                    $noc += 2;
                } elseif (240 <= $t && $t <= 247) {
                    $tn = 4;
                    $n += 4;
                    $noc += 2;
                } elseif (248 <= $t && $t <= 251) {
                    $tn = 5;
                    $n += 5;
                    $noc += 2;
                } elseif ($t == 252 || $t == 253) {
                    $tn = 6;
                    $n += 6;
                    $noc += 2;
                } else {
                    $n++;
                }
                if ($noc >= $length) {
                    break;
                }
            }
            if ($noc > $length) {
                $n -= $tn;
            }
            $strcut = substr($string, 0, $n);
            $strcut = str_replace(array(
                '∵',
                '&',
                '"',
                "'",
                '“',
                '”',
                '—',
                '<',
                '>',
                '·',
                '…'
            ), array(
                ' ',
                '&amp;',
                '&quot;',
                '&#039;',
                '&ldquo;',
                '&rdquo;',
                '&mdash;',
                '&lt;',
                '&gt;',
                '&middot;',
                '&hellip;'
            ), $strcut);
        } else {
            $dotlen      = strlen($dot);
            $maxi        = $length - $dotlen - 1;
            $current_str = '';
            $search_arr  = array(
                '&',
                ' ',
                '"',
                "'",
                '“',
                '”',
                '—',
                '<',
                '>',
                '·',
                '…',
                '∵'
            );
            $replace_arr = array(
                '&amp;',
                '&nbsp;',
                '&quot;',
                '&#039;',
                '&ldquo;',
                '&rdquo;',
                '&mdash;',
                '&lt;',
                '&gt;',
                '&middot;',
                '&hellip;',
                ' '
            );
            $search_flip = array_flip($search_arr);
            for ($i = 0; $i < $maxi; $i++) {
                $current_str = ord($string[$i]) > 127 ? $string[$i] . $string[++$i] : $string[$i];
                if (in_array($current_str, $search_arr)) {
                    $key         = $search_flip[$current_str];
                    $current_str = str_replace($search_arr[$key], $replace_arr[$key], $current_str);
                }
                $strcut .= $current_str;
            }
        }
        return $strcut . $dot;
    }
  

}  