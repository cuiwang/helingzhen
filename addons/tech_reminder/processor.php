<?php
/**
 * 梦昂--关注提示模块处理程序
 *
 * @author 梦昂科技
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');



class Tech_reminderModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		$openid = $this->message['from'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
		/*return $this->respText('您触发了we7_demo模块'.$openid);*/
		/*$fans_info = $this->getInfo($openid);*/
		/*$text = $fans_info['nickname'];
		$this->sendText($openid, $text);*/

		$rid = $this->rule;
		if (!empty($rid)) {
            $item = pdo_fetch("SELECT * FROM " . tablename('tech_reminder_form') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
            $item['number'] = $item['number'] + 1;
            $result = pdo_update('tech_reminder_form', array('number' => $item['number']), array('rid' => $rid));
        }
    	$fans_info = $this->getInfo($openid);
    	if ($fans_info['sex'] == 1) {
    		$fans_info['sex'] = '男';
    	} else {
    		$fans_info['sex'] = '女';
    	}
        if ($item){
        	$wenabled = $item['wenabled'];
        	$senabled = $item['senabled'];
        	if ($wenabled) {
        		$wtitle = $item['wtitle'];
	        	$wtitle = str_replace('#昵称#', $fans_info['nickname'], $wtitle);
	        	$wtitle = str_replace('#性别#', $fans_info['sex'], $wtitle);
	        	$wtitle = str_replace('#人数#', $item['number'], $wtitle);
	        	$wtitle = str_replace('#国家#', $fans_info['country'], $wtitle);
	        	$wtitle = str_replace('#省份#', $fans_info['province'], $wtitle);
	        	$wtitle = str_replace('#城市#', $fans_info['city'], $wtitle);
	        	$wtitle = str_replace('#时间#', date('Y-m-d H:i:s', $fans_info['subscribe_time']), $wtitle);
	        	$this->sendText($openid, $wtitle);
        	}
        	if ($senabled) {
				$titles = unserialize($item['stitle']);
				$thumbs = unserialize($item['sthumb']);
				$sdesc = unserialize($item['sdesc']);
				$surl = unserialize($item['surl']);
				foreach ($titles as $key => $value) {
					if (empty($value)) continue;
					$value = str_replace('#昵称#', $fans_info['nickname'], $value);
					$value = str_replace('#性别#', $fans_info['sex'], $value);
					$value = str_replace('#人数#', $item['number'], $value);
					$value = str_replace('#国家#', $fans_info['country'], $value);
					$value = str_replace('#省份#', $fans_info['province'], $value);
					$value = str_replace('#城市#', $fans_info['city'], $value);
					$value = str_replace('#时间#', date('Y-m-d H:i:s', $fans_info['subscribe_time']), $value);
					$sdesc[$key] = str_replace('#昵称#', $fans_info['nickname'], $sdesc[$key]);
					$sdesc[$key] = str_replace('#性别#', $fans_info['sex'], $sdesc[$key]);
					$sdesc[$key] = str_replace('#人数#', $item['number'], $sdesc[$key]);
					$sdesc[$key] = str_replace('#国家#', $fans_info['country'], $sdesc[$key]);
					$sdesc[$key] = str_replace('#省份#', $fans_info['province'], $sdesc[$key]);
					$sdesc[$key] = str_replace('#城市#', $fans_info['city'], $sdesc[$key]);
					$sdesc[$key] = str_replace('#时间#', date('Y-m-d H:i:s', $fans_info['subscribe_time']), $sdesc[$key]);
					$slist[] = array('title'=> urlencode($value),'description'=>urlencode($sdesc[$key]),'url'=>$surl[$key],'picurl'=>tomedia($thumbs[$key]));
				}
				$slist = json_encode($slist);
				$slist = urldecode($slist);
				$data = '{"touser":"' . $openid . '","msgtype":"news","news":{"articles":' . $slist . '}}';
				$this->sendRes($data);
        	}
		}
	}



    private function getInfo($openid) {
        global $_W;
		$account_api = WeAccount::create();
		$token = $account_api->getAccessToken();
		if(is_error($token)){
		    $account_api->clearAccessToken();
		    $token = $account_api->getAccessToken();
		}
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$token}&openid={$openid}&lang=zh_CN";
		load()->func('communication'); 
		$res = ihttp_get($url);
		$res = json_decode($res['content'],true);
		return $res;
    }
	private function sendText($openid, $text) {
		$post = '{"touser":"' . $openid . '","msgtype":"text","text":{"content":"' . $text . '"}}';
		$ret = $this->sendRes($post);
		return $ret;
	}

	private function sendNews($openid, $news) {
		$post = '{"touser":"' . $openid . '","msgtype":"news","news":{"articles":[{"title":"' . $news['title'] . '","description":"' . $news['description'] . '","url":"' . $news['url'] . '","picurl":"' . $news['picurl'] . '"}]}}';
		$ret = $this->sendRes($post);
		return $ret;
	}

	private function sendRes($data) {
		$access_token = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
		load()->func('communication');
		$ret = ihttp_request($url, $data);
		$content = @json_decode($ret['content'], true);
		return $content['errcode'];
	}

	private function batch_info($data) {
		$access_token = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token={$access_token}";
		load()->func('communication');
		$ret = ihttp_request($url, $data);
		$content = @json_decode($ret['content'], true);
		return $content;
	}

	private function getAccessToken() {
		global $_W;
		load()->model('account');
		$acid = $_W['acid'];
		if (empty($acid)) {
			$acid = $_W['uniacid'];
		}
		$account = WeAccount::create($acid);
		$token = $account->getAccessToken();
		return $token;
	}

	private function unicode_decode($name) {
    //转换编码，将Unicode编码转换成可以浏览的utf-8编码
	    $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
	    preg_match_all($pattern, $name, $matches);
	    if (!empty($matches))
	    {
	        $name = '';
	        for ($j = 0; $j < count($matches[0]); $j++)
	        {
	            $str = $matches[0][$j];
	            if (strpos($str, '\\u') === 0)
	            {
	                $code = base_convert(substr($str, 2, 2), 16, 10);
	                $code2 = base_convert(substr($str, 4), 16, 10);
	                $c = chr($code).chr($code2);
	                $c = iconv('UCS-2', 'UTF-8', $c);
	                $name .= $c;
	            }
	            else
	            {
	                $name .= $str;
	            }
	        }
	    }
	    return $name;
	}
}