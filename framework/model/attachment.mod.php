<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

function attachment_alioss_datacenters() {
	$bucket_datacenter = array(
		'oss-cn-hangzhou' => '杭州数据中心',
		'oss-cn-qingdao' => '青岛数据中心',
		'oss-cn-beijing' => '北京数据中心',
		'oss-cn-hongkong' => '香港数据中心',
		'oss-cn-shenzhen' => '深圳数据中心',
		'oss-cn-shanghai' => '上海数据中心',
		'oss-us-west-1' => '美国硅谷数据中心',
	);
	return $bucket_datacenter;
}

function attachment_newalioss_auth($key, $secret, $bucket){
	require_once(IA_ROOT.'/framework/library/alioss/autoload.php');
	$buckets = attachment_alioss_buctkets($key, $secret);
	$url = 'http://'.$buckets[$bucket]['location'].'.aliyuncs.com';
	$filename = 'MicroEngine.ico';
	try {
		$ossClient = new \OSS\OssClient($key, $secret, $url);
		$ossClient->uploadFile($bucket, $filename, ATTACHMENT_ROOT.'images/global/'.$filename);
	} catch (\OSS\Core\OssException $e) {
		return error(1, $e->getMessage());
	}
	return 1;
}

function attachment_alioss_buctkets($key, $secret) {
	require_once(IA_ROOT.'/framework/library/alioss/autoload.php');
	$url = 'http://oss-cn-beijing.aliyuncs.com';
	try {
		$ossClient = new \OSS\OssClient($key, $secret, $url);
	} catch(\OSS\Core\OssException $e) {
		return error(1, $e->getMessage());
	}
	try{
		$bucketlistinfo = $ossClient->listBuckets();
	} catch(OSS\OSS_Exception $e) {
		return error(1, $e->getMessage());
	}
	$bucketlistinfo = $bucketlistinfo->getBucketList();
	$bucketlist = array();
	foreach ($bucketlistinfo as &$bucket) {
		$bucketlist[$bucket->getName()] = array('name' => $bucket->getName(), 'location' => $bucket->getLocation());
	}
	return $bucketlist;
}

function attachment_qiniu_auth($key, $secret,$bucket) {
	require_once(IA_ROOT . '/framework/library/qiniu/autoload.php');
	$auth = new Qiniu\Auth($key, $secret);
	$token = $auth->uploadToken($bucket);
	$config = new Qiniu\Config();
	$uploadmgr = new Qiniu\Storage\UploadManager($config);
	list($ret, $err) = $uploadmgr->putFile($token, 'MicroEngine.ico', ATTACHMENT_ROOT.'images/global/MicroEngine.ico');
	if ($err !== null) {
		$err = (array)$err;
		$err = (array)array_pop($err);
		$err = json_decode($err['body'], true);
		return error(-1, $err);
	} else {
		return true;
	}
}
function attachment_cos_auth($bucket,$appid, $key, $secret, $bucket_local = '') {
	if (!is_numeric($appid)) {
		return error(-1, '传入appid值不合法, 请重新输入');
	}
	if (!preg_match('/^[a-zA-Z0-9]{36}$/', $key)) {
		return error(-1, '传入secretid值不合法，请重新传入');
	}
	if (!preg_match('/^[a-zA-Z0-9]{32}$/', $secret)) {
		return error(-1, '传入secretkey值不合法，请重新传入');
	}
	if (!empty($bucket_local)) {
		$con = $original = @file_get_contents(IA_ROOT.'/framework/library/cosv4.2/qcloudcos/conf.php');
		if (empty($con)) {
			$conf_content = base64_decode("PD9waHANCg0KbmFtZXNwYWNlIHFjbG91ZGNvczsNCg0KY2xhc3MgQ29uZiB7DQogICAgLy8gQ29zIHBocCBzZGsgdmVyc2lvbiBudW1iZXIuDQogICAgY29uc3QgVkVSU0lPTiA9ICd2NC4yLjInOw0KICAgIGNvbnN0IEFQSV9DT1NBUElfRU5EX1BPSU5UID0gJ2h0dHA6Ly9yZWdpb24uZmlsZS5teXFjbG91ZC5jb20vZmlsZXMvdjIvJzsNCg0KICAgIC8vIFBsZWFzZSByZWZlciB0byBodHRwOi8vY29uc29sZS5xY2xvdWQuY29tL2NvcyB0byBmZXRjaCB5b3VyIGFwcF9pZCwgc2VjcmV0X2lkIGFuZCBzZWNyZXRfa2V5Lg0KICAgIGNvbnN0IEFQUF9JRCA9ICcnOw0KICAgIGNvbnN0IFNFQ1JFVF9JRCA9ICcnOw0KICAgIGNvbnN0IFNFQ1JFVF9LRVkgPSAnJzsNCg0KICAgIC8qKg0KICAgICAqIEdldCB0aGUgVXNlci1BZ2VudCBzdHJpbmcgdG8gc2VuZCB0byBDT1Mgc2VydmVyLg0KICAgICAqLw0KICAgIHB1YmxpYyBzdGF0aWMgZnVuY3Rpb24gZ2V0VXNlckFnZW50KCkgew0KICAgICAgICByZXR1cm4gJ2Nvcy1waHAtc2RrLScgLiBzZWxmOjpWRVJTSU9OOw0KICAgIH0NCn0NCg==");
			file_put_contents(IA_ROOT.'/framework/library/cosv4.2/qcloudcos/conf.php', $conf_content);
			$con = $original = $conf_content;
		}
		$con = preg_replace('/const[\s]APP_ID[\s]=[\s]\'.*\';/', 'const APP_ID = \''.$appid.'\';', $con);
		$con = preg_replace('/const[\s]SECRET_ID[\s]=[\s]\'.*\';/', 'const SECRET_ID = \''.$key.'\';', $con);
		$con = preg_replace('/const[\s]SECRET_KEY[\s]=[\s]\'.*\';/', 'const SECRET_KEY = \''.$secret.'\';', $con);
		file_put_contents(IA_ROOT.'/framework/library/cosv4.2/qcloudcos/conf.php', $con);
		require_once(IA_ROOT.'/framework/library/cosv4.2/include.php');
		qcloudcos\Cosapi :: setRegion($bucket_local);
		qcloudcos\Cosapi :: setTimeout(180);
		$uploadRet = qcloudcos\Cosapi::upload($bucket, ATTACHMENT_ROOT.'images/global/MicroEngine.ico', '/MicroEngine.ico','',3 * 1024 * 1024, 0);
	} else {
		require_once(IA_ROOT.'/framework/library/cos/include.php');
		$con = $original = @file_get_contents(IA_ROOT.'/framework/library/cos/Qcloud_cos/Conf.php');
		if (empty($con)) {
			$conf_content = base64_decode("PD9waHANCm5hbWVzcGFjZSBRY2xvdWRfY29zOw0KDQpjbGFzcyBDb25mDQp7DQogICAgY29uc3QgUEtHX1ZFUlNJT04gPSAndjMuMyc7DQoNCiAgICBjb25zdCBBUElfSU1BR0VfRU5EX1BPSU5UID0gJ2h0dHA6Ly93ZWIuaW1hZ2UubXlxY2xvdWQuY29tL3Bob3Rvcy92MS8nOw0KICAgIGNvbnN0IEFQSV9WSURFT19FTkRfUE9JTlQgPSAnaHR0cDovL3dlYi52aWRlby5teXFjbG91ZC5jb20vdmlkZW9zL3YxLyc7DQogICAgY29uc3QgQVBJX0NPU0FQSV9FTkRfUE9JTlQgPSAnaHR0cDovL3dlYi5maWxlLm15cWNsb3VkLmNvbS9maWxlcy92MS8nOw0KICAgIC8v6K+35YiwaHR0cDovL2NvbnNvbGUucWNsb3VkLmNvbS9jb3Pljrvojrflj5bkvaDnmoRhcHBpZOOAgXNpZOOAgXNrZXkNCiAgICBjb25zdCBBUFBJRCA9ICcnOw0KICAgIGNvbnN0IFNFQ1JFVF9JRCA9ICcnOw0KICAgIGNvbnN0IFNFQ1JFVF9LRVkgPSAnJzsNCg0KDQogICAgcHVibGljIHN0YXRpYyBmdW5jdGlvbiBnZXRVQSgpIHsNCiAgICAgICAgcmV0dXJuICdjb3MtcGhwLXNkay0nLnNlbGY6OlBLR19WRVJTSU9OOw0KICAgIH0NCn0NCg0KLy9lbmQgb2Ygc2NyaXB0DQo=");
			file_put_contents(IA_ROOT.'/framework/library/cos/Qcloud_cos/Conf.php', $conf_content);
			$con = $original = $conf_content;
		}
		$con = preg_replace('/const[\s]APPID[\s]=[\s]\'.*\';/', 'const APPID = \''.$appid.'\';', $con);
		$con = preg_replace('/const[\s]SECRET_ID[\s]=[\s]\'.*\';/', 'const SECRET_ID = \''.$key.'\';', $con);
		$con = preg_replace('/const[\s]SECRET_KEY[\s]=[\s]\'.*\';/', 'const SECRET_KEY = \''.$secret.'\';', $con);
		file_put_contents(IA_ROOT.'/framework/library/cos/Qcloud_cos/Conf.php', $con);
		$uploadRet = Qcloud_cos\Cosapi::upload($bucket, ATTACHMENT_ROOT.'images/global/MicroEngine.ico', '/MicroEngine.ico','',3 * 1024 * 1024, 0);
	}
	if ($uploadRet['code'] != 0) {
		switch ($uploadRet['code']) {
			case -62:
				$message = '输入的appid有误';
				break;
			case -79:
				$message = '输入的SecretID有误';
				break;
			case -97:
				$message = '输入的SecretKEY有误';
				break;
			case -166:
				$message = '输入的bucket有误';
				break;
			case -133:
				$message = '请确认你的bucket是否存在';
				break;
		}
		if (empty($bucket_local)) {
			file_put_contents(IA_ROOT.'/framework/library/cos/Qcloud_cos/Conf.php', $original);
		} else {
			file_put_contents(IA_ROOT.'/framework/library/cosv4.2/qcloudcos/Conf.php', $original);
		}
		return error(-1, $message);
	}
	return true;
}
