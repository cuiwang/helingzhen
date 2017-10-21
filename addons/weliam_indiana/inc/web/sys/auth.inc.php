<?php
global $_W,$_GPC;
require WELIAM_INDIANA.'version.php'; 
set_time_limit(0);
if (!$_W['isfounder']) {
	message('无权访问!');
}
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
load()->func('communication');
load()->func('file');

if ($op == 'display') {
	$domain = $_SERVER['SERVER_NAME']; 
	$siteid = $_W['setting']['site']['key'];
	$auth = m('syssetting')->syssetting_read('auth');
	
	$resp = ihttp_request('http://weixin.012wz.com/addons/weliam_manage/api.php', array('type' => 'user','module' => 'weliam_indiana','website' => $siteid,'domain'=> $domain),null,1);
	$resp = @json_decode($resp['content'], true);
	$ip = $resp['ip'];
	
	$result = ihttp_request('http://weixin.012wz.com/addons/weliam_manage/api.php', array('type' => 'checkauth','module' => 'weliam_indiana'),null,5);
	$result = @json_decode($result['content'], true);
	
	if (checksubmit()) {
		$data = array('ip' => $_GPC['ip'],'domain' => $_GPC['domain'],'siteid' => $_GPC['siteid'],'code' => $_GPC['code']);
		m('syssetting')->syssetting_save($data,'auth');
		$resp = ihttp_request('http://weixin.012wz.com/addons/weliam_manage/api.php', array('type' => 'grant','module' => 'weliam_indiana','code' => $data['code']),null,1);
		$resp = @json_decode($resp['content'], true);
		if($resp['errno'] == 1){
			message($resp['message']);
		}else{
			message($resp['message']);
		}
	}
	
	include $this->template('auth');
}

if ($op == 'process') {
	include $this->template('process');
}

if ($op == 'upgrade') {
	$result = ihttp_request('http://weixin.012wz.com/addons/weliam_manage/api.php', array('type' => 'checkauth','module' => 'weliam_indiana'),null,5);
	$result = @json_decode($result['content'], true);
	if($result['status'] != 1){
		message('您还未授权，请授权后再试！',web_url('auth/display'),'warning');
	}
    $version = WELIAM_INDIANNA_VERSION;
	$versionfile = IA_ROOT . '/addons/weliam_indiana/version.php';
	$release = date('YmdHis', filemtime($versionfile));
    $resp = ihttp_post('http://weixin.012wz.com/addons/weliam_manage/api.php', array(
        'type' => 'check',
        'module' => 'weliam_indiana',
        'version' => $version
    ));
    $upgrade = @json_decode($resp['content'], true);
    if (is_array($upgrade)) {
        if ($upgrade['result'] == 1) {
            $files = array();
            if (!empty($upgrade['files'])) {
                foreach ($upgrade['files'] as $file) {
                    $entry = IA_ROOT . '/addons/weliam_indiana/' . $file['path'];
                    if (!is_file($entry) || md5_file($entry) != $file['md5']) {
                        $files[] = array(
                            'path' => $file['path'],
                            'download' => 0,
                            'entry'=>$entry
                        );
                    }
                }
            }
			if(!empty($files)){
				$upgrade['files'] = $files;
	            $tmpdir = IA_ROOT . '/addons/weliam_indiana/temp';
	            if (!is_dir($tmpdir)) {
	                mkdirs($tmpdir);
	            }
	            file_put_contents($tmpdir . '/file.txt', json_encode($upgrade));
			}else{
				unset($upgrade);
			}
        }else{
        	message($upgrade['message']);
        }
    }else{
    	message('服务器错误:' . $resp['content'] . '. ');
    }
	include $this->template('upgrade');
}

if ($op == 'download') {
    $tmpdir = IA_ROOT . '/addons/weliam_indiana/temp';
    $f = file_get_contents($tmpdir . '/file.txt');
    $upgrade = json_decode($f, true);
    $files = $upgrade['files'];
	
	//判断是否存在需要更新的文件
    $path = "";
    foreach ($files as $f) {
        if (empty($f['download'])) {
            $path = $f['path'];
            break;
        }
    }
	
    if (!empty($path)) {
        $resp = ihttp_post('http://weixin.012wz.com/addons/weliam_manage/api.php', array(
            'type' => 'download',
            'module' => 'weliam_indiana',
            'path' => $path
        ));
        $ret = @json_decode($resp['content'], true);
        if (is_array($ret)) {
        	//检查路径
            $path = $ret['path'];
            $dirpath = dirname($path);
            if (!is_dir(IA_ROOT . '/addons/weliam_indiana/' . $dirpath)) {
                mkdirs(IA_ROOT . '/addons/weliam_indiana/' . $dirpath, '0777');
            }
			//获取更新文件
            $content = base64_decode($ret['content']);
            file_put_contents(IA_ROOT . '/addons/weliam_indiana/' . $path, $content);
            $success = 1;
            foreach ($files as & $f) {
                if ($f['path'] == $path) {
                    $f['download'] = 1;
                    break;
                }
                if ($f['download']) {
                    $success++;
                }
            }
            unset($f);
            $upgrade['files'] = $files;
            $tmpdir = IA_ROOT . '/addons/weliam_indiana/temp';
            if (!is_dir($tmpdir)) {
                mkdirs($tmpdir);
            }
            file_put_contents($tmpdir . '/file.txt', json_encode($upgrade));
            die(json_encode(array(
                'result' => 1,
                'total' => count($files) ,
                'success' => $success,
                'path' => $path
            )));
        }
    } else {
        $updatefile = IA_ROOT . '/addons/weliam_indiana/upgrade.php';
        require $updatefile;
        $tmpdir = IA_ROOT . '/addons/weliam_indiana/temp';
        @rmdirs($tmpdir);
        message('恭喜您，系统更新成功！',web_url('auth/upgrade'),'success');
    }
}