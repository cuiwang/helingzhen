<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
defined('IN_IA') or exit('Access Denied');

class Meepo_xianchangModuleSite extends WeModuleSite {
	public $basic_config_table = 'meepo_xianchang_basic_config';
	public $user_table = 'meepo_xianchang_user';
	public $xc_table = 'meepo_xianchang_rid';
	public $wall_table = 'meepo_xianchang_wall';
	public $wall_config_table = 'meepo_xianchang_wall_config';
	public $cookie_table = 'meepo_xianchang_cookie';
	public $qd_table = 'meepo_xianchang_qd';
	public $qd_config_table = 'meepo_xianchang_qd_config';
	public $lottory_award_table = 'meepo_xianchang_lottory_award';
	public $lottory_user_table = 'meepo_xianchang_lottory_user';
	public $lottory_config_table = 'meepo_xianchang_lottory_config';
	public $jb_table = 'meepo_xianchang_jb';
	public $vote_table = 'meepo_xianchang_vote';
	public $vote_xms_table = 'meepo_xianchang_vote_xms';
	public $vote_record = 'meepo_xianchang_vote_record';
	public $shake_rotate_table = 'meepo_xianchang_shake_rotate';
	public $shake_user_table = 'meepo_xianchang_shake_user';
	public $shake_config_table = 'meepo_xianchang_shake_config';
	public $xc2_table = 'meepo_xianchang_xc';
	public $bd_manage_table = 'meepo_xianchang_bd';
	public $bd_data_table = 'meepo_xianchang_bd_data';
	public $sd_config_table = 'meepo_xianchang_3d_config';
	public $redpack_config_table = 'meepo_xianchang_redpack_config';
	public $redpack_user_table = 'meepo_xianchang_redpack_user';
	public $redpack_rotate_table = 'meepo_xianchang_redpack_rotate';
	public $ddp_config_table = 'meepo_xianchang_ddp_config';
	public $cjx_config_table = 'meepo_xianchang_cjx_config';
	public $zjd_config_table = 'meepo_xianchang_zjd_config';
	public $ddp_record_table = 'meepo_xianchang_ddp_record';
	public $xysjh_record_table = 'meepo_xianchang_xysjh_record';
	public function docheckurl(){
				global $_GPC, $_W;
				/*load()->func('communication');
				$content = ihttp_get('http://meepo.com.cn/addons/meepo_xianchang/check.php?url='.$_W['siteroot']);
				if($content['content']=='1'){
					return true;
				}*/
				return true;
				
	}
	
	public function get_follow_fansinfo($openid){
					if(!$this->docheckurl()){
						die('');
					}
					global $_W,$_GPC;
					$access_token = $this->getAccessToken();
					$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
					load()->func('communication');
					$content = ihttp_request($url);		
					$info = @json_decode($content['content'], true);
					return $info;
	}
	
	public  function send_message($rid,$openid,$content){
			global $_GPC, $_W;
			$weid = $_W['uniacid'];
				if($_W['account']['level']==4){
					
					$access_token = $this->getAccessToken();  
					$data = '{
						"touser":"'.$openid.'",
						"msgtype":"text",
						"text":
						{
							 "content":"'.$content.'"
						}
					}';
					load()->func('communication');
					$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$access_token;
					$cont = ihttp_post($url, $data);
					//var_dump($cont);
				}
			
	
	}
	public  function  getAccessToken () {
		global $_W;
		load()->classs('weixin.account');
		$accObj = WeixinAccount::create($_W['acid']);
		$access_token = $accObj->fetch_token();
		return $access_token;
	}
	public function doMobilemakeqrcode(){
			global $_W,$_GPC;
			$url = $_GPC['url'];
			$url = str_replace('./','',$_W['siteroot'].'app/'.$url);
			if(!empty($url)) {
				include IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
				QRcode::png($url, false, QR_ECLEVEL_Q, 4);
				exit();
			}
	}
	public function emo($content,$type='app'){
		$emo = array("[笑脸]", "[感冒]", "[流泪]", "[发怒]", "[爱慕]", "[吐舌]", "[发呆]", "[可爱]", "[调皮]", "[寒冷]", "[呲牙]", "[闭嘴]", "[害羞]", "[苦闷]", "[难过]", "[流汗]", "[犯困]", "[惊恐]", "[咖啡]", "[炸弹]", "[西瓜]", "[爱心]", "[心碎]");
		foreach($emo as $key=>$val){
				if(strexists($content, $val)){
						$imgurl = "../addons/meepo_xianchang/template/mobile/app/emo/".($key+1).".png";
						if($type=='app'){
							$replace = '<img src="'.$imgurl.'" border="0" class="dt_emo" width=20px height=20px />';
						}else{
							$replace = '<img src="'.$imgurl.'" border="0" class="dt_emo" width=50px height=50px />';
						}
						$content = str_replace($val,$replace,$content);
				}
		}
		return $content;
	}
	public function get_allfiles($path,&$files) {  
    if(is_dir($path)){  
        $dp = dir($path);  
        while ($file = $dp ->read()){  
            if($file !="." && $file !=".."){  
                $this->get_allfiles($path."/".$file, $files);  
            }  
        }  
        $dp ->close();  
    }  
    if(is_file($path)){  
        $files[] =  $path;  
    }  
	}  
		  
	public function get_filenamesbydir($dir){  
		$files =  array();  
		$this->get_allfiles($dir,$files);  
		return $files;  
	}
	public function file_upload2($weid,$file,$attname='', $name = '') {
		global $_W,$_GPC;
		load()->func('file'); 
		if(empty($file)) {
			return error(-1, '没有上传内容');
		}
		$deftype = array('xls','xlsx','cvs');
		$extention = pathinfo($file['name'], PATHINFO_EXTENSION);
		if(!in_array(strtolower($extention), $deftype)) {
			return error(-1, '不允许上传此类文件');
		}
		$result = array();
		
		if(empty($name) || $name == 'auto') {
			$result['path'] = "uploadfile/".$_W['uniacid']."/". date('Y/m/');
			mkdirs(MODULE_ROOT . '/' . $result['path']);
			do {
				if(empty($attname)){
						$nowname = random(20);
					$filename =  $nowname. ".{$extention}";
			  }else{
						$file['name'] = str_replace(".".$extention,'',$file['name']);
						$filename = $file['name'] .random(2). ".{$extention}";
				}
			} while(file_exists(MODULE_ROOT . '/' . $result['path'] . $filename));
			$result['path'] .= $filename;
		} else {
			$result['path'] = $name . '.' .$extention;
		}
		
		if(!file_move($file['tmp_name'], MODULE_ROOT . '/' . $result['path'])) {
			return error(-1, '保存上传文件失败');
		}
		$result['nowname'] = $nowname;
		$result['success'] = true;
		return $result; 
	}
	public function readexl($filename){
			date_default_timezone_set('Asia/ShangHai');
			/** PHPExcel_IOFactory */
			include_once ('../framework/library/phpexcel/PHPExcel/IOFactory.php');
			if (!file_exists($filename)) {
				die($filename.".\n");
			}
			$reader = PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
			$PHPExcel = $reader->load($filename); // 载入excel文件
			$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
			$highestRow = $sheet->getHighestRow(); // 取得总行数
			$highestColumm = $sheet->getHighestColumn(); // 取得总列数
			$highestColumm= PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27
			/** 循环读取每个单元格的数据 */
			for ($row = 2; $row <= $highestRow; $row++){//行数是以第2行开始 去掉代表各列名称的行
					for ($column = 0; $column < $highestColumm; $column++) {//列数是
								$data[$row][] = $sheet->getCellByColumnAndRow($column, $row)->getValue();
					}
			}
			return $data;
	}
	public function color($key){
		$colors = array('#34c2e3','#FF4141','#08B40F','#FF4141','#D3C203','#0066FF','#6E08DB','#FF8F00','#8CB609','#CE4FFF','#949494','#04BE02');
		if($key<11){
			return $colors[$key];
		}else{
			$str='0123456789ABCDEF';  
			$estr='#';  
			$len=strlen($str);  
			for($i=1;$i<=6;$i++)  
			{  
				$num=rand(0,$len-1);    
				$estr=$estr.$str[$num];   
			}  
			return $estr;  
		}

	}
	public function _sendpack($_openid,$fee,$rid){
		global $_W;
		$weid = $_W['uniacid'];
		load()->func('communication');
		$reply = pdo_fetch('SELECT `appid`,`mchid`,`ip`,`signkey`,`_desc` FROM '.tablename($this->redpack_config_table).' WHERE weid=:weid AND rid=:rid',array(':weid'=>$weid,':rid'=>$rid));
 		$url='https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $pars = array();				
		$pars['mch_appid'] =$reply['appid'];
		$pars['mchid']=$reply['mchid'];			
		$pars['nonce_str'] =random(32);	
		$pars['partner_trade_no'] =time().random(3,1);	
		$pars['openid'] =$_openid;
		$pars['check_name'] ='NO_CHECK' ;
		$pars['amount'] =$fee*100;		
		$pars['desc'] =(empty($reply['_desc'])?'没什么，就是想送你一个红包':$reply['_desc']);
		$pars['spbill_create_ip'] =$reply['ip'];
        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach ($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key=".$reply['signkey'];
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();
		$extras['CURLOPT_CAINFO'] =  ATTACHMENT_ROOT ."/images/cert/".$weid."/rootca.pem";
        $extras['CURLOPT_SSLCERT'] =ATTACHMENT_ROOT . "/images/cert/".$weid."/apiclient_cert.pem";
        $extras['CURLOPT_SSLKEY'] =ATTACHMENT_ROOT . "/images/cert/".$weid."/apiclient_key.pem";
        $resp = ihttp_request($url, $xml, $extras);
        if (is_error($resp)) {
            $procResult = array(//http error
			  'errno'=>-2,
			  'error'=>$resp['message'],
			);
        } else {
			$arr=json_decode(json_encode((array) simplexml_load_string($resp['content'])), true);
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new \DOMDocument();
            if ($dom->loadXML($xml)) {
                $xpath = new \DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $ret = $xpath->evaluate('string(//xml/result_code)');
                if (strtolower($code) == 'success' && strtolower($ret) == 'success') {
                    $procResult =  array('errno'=>0,'error'=>'success');//success
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $procResult = array('errno'=>-3,'error'=>$error);//错误信息
                }
            } else {
				$procResult = array('errno'=>-4,'error'=>'未知错误');//未知错误		
            }
        }
		return $procResult;
	}
	public function removeEmoji($nickname) {
			$clean_text = "";
			// Match Emoticons
			$regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
			$clean_text = preg_replace($regexEmoticons, '', $nickname);

			// Match Miscellaneous Symbols and Pictographs
			$regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
			$clean_text = preg_replace($regexSymbols, '', $clean_text);

			// Match Transport And Map Symbols
			$regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
			$clean_text = preg_replace($regexTransport, '', $clean_text);

			// Match Miscellaneous Symbols
			$regexMisc = '/[\x{2600}-\x{26FF}]/u';
			$clean_text = preg_replace($regexMisc, '', $clean_text);

			// Match Dingbats
			$regexDingbats = '/[\x{2700}-\x{27BF}]/u';
			$clean_text = preg_replace($regexDingbats, '', $clean_text);
			$clean_text = str_replace("'",'',$clean_text);
			$clean_text = str_replace('"','',$clean_text);
			$clean_text = str_replace('“','',$clean_text);
			$clean_text = str_replace('゛','',$clean_text);
			$search = array(" ","　","\n","\r","\t");
			$replace = array("","","","","");
			return str_replace($search, $replace, $clean_text);
		}
	public function get_rand($arr){
        $pro_sum=array_sum($arr);
        $rand_num=mt_rand(1,$pro_sum);
        $tmp_num=0;
        foreach($arr as $k=>$val) {    
            if($rand_num<=$val+$tmp_num){
                $n=$k;
                break;
            }else{
                $tmp_num+=$val;
            }
        }
        return $n;
	}
	public function var_tool($var,$show=1){
		echo "<pre>";
		if($show==1){
			var_dump($var);
			die;
		}else{
			var_dump($var);
		}
		echo "</pre>";
	}
}