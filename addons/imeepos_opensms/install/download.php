<?php
/**
 * Created by imeepos.
 * User: imeepos
 * Date: 2016/6/30
 * Time: 15:52
 */

require('../../../framework/bootstrap.inc.php');

define('MODULE_ROOT', str_replace("\\", '/', dirname(__FILE__)));
define('UPDATE_URL','http://012wz.com/addons/imeepos_oauth2/oauth/');

$op =empty($_GPC['op'])? 'display' : $_GPC['op'];

load()->web('template');
load()->web('common');

load()->func('communication');
load()->func('file');

load()->func('db');
load()->model('setting');
load()->func('communication');

$oauth = array();
$oauth['ip'] = gethostbyname($_SERVER['SERVER_ADDR']);
$oauth['domain'] = $_SERVER['HTTP_HOST'];
$setting = setting_load('site');
$oauth['id'] =isset($setting['site']['key'])? $setting['site']['key'] : '1';
$oauth['module']= trim($_GPC['module']);

if(!empty($setting)){
    $tmpdir =IA_ROOT."/addons/".$oauth['module']."/log/".date('ymd');
    $versionfile = IA_ROOT."/addons/".$oauth['module']."/version.php";

    if(file_exists($versionfile)){
        require_once $versionfile;
        $version = VERSION;
        if($version == '0.0.0'){
            $version = '开发同步版';
        }
    }else{
        $version = '1.0.0';
    }
    if(!is_dir($tmpdir)){
        mkdirs($tmpdir);
    }
    if ($op == 'display'){
        $versionfile =IA_ROOT . '/addons/'.$oauth['module'].'/version.php';
        if (is_file($versionfile)){
            $updatedate =date('Y-m-d H:i', filemtime($versionfile));
        }else{
            $updatedate =date('Y-m-d H:i', time());
        }
        set_time_limit(0);
        global $my_scenfiles;
        my_scandir(IA_ROOT.'/addons/'.$oauth['module'].'/');
        $files =array();
        if(!empty($my_scenfiles)){
            foreach($my_scenfiles as $sf){
                $files[] =array('path' => str_replace(IA_ROOT."/addons/".$oauth['module']."/","",$sf), 'md5'=> md5_file($sf));
            }
        }
        $files =base64_encode(json_encode($files));
        $resp =ihttp_post(UPDATE_URL.'check.php',array('ip'=>$oauth['ip'], 'id'=>$oauth['id'], 'code'=>$code, 'domain'=>$oauth['domain'], 'version'=>$version, 'files'=>$files ,'module'=>$oauth['module']));
        $content = cloud_object_array(@json_decode($resp['content']));
        $has_site = 0;

        if($content['status'] ==1){
            $files =array();
            if (!empty($content['files'])){
                foreach ($content['files'] as $file){
                    $entry =IA_ROOT . "/addons/".$oauth['module']."/".$file['path'];
                    if (!is_file($entry)|| md5_file($entry)!= $file['md5']){
                        if($file['path'] == '/site.php' || $file['path'] == '/version.php'){
                            if($file['path'] == '/site.php'){
                                $has_site = 1;
                            }
                        }else{
                            $files[] =array('path'=>$file['path'],'download'=>0);
                        }
                    }
                }
            }
            if($has_site == 1){
                $files[] =array('path'=>'/site.php','download'=>0);
            }
            $content['files'] = $files;
            $message = '重要: 本次更新涉及到程序变动, 请做好备份.';
            file_put_contents($tmpdir."/file.txt",json_encode($content));
        }else if($content['status'] == -1){
            $files = array();
            $message = $content['message'];
        }else{
            $files = array();
            $message = $content['message'];
        }
        load()->web('template');
        template2('download');
    }else if ($op == 'download'){
        $f =file_get_contents($tmpdir."/file.txt");
        $upgrade =json_decode($f,true);
        $files =$upgrade['files'];
        $path ="";
        if(!empty($files)){
            foreach($files as $f){
                if(empty($f['download'])){
                    $path =$f['path'];
                    break;
                }
            }
        }
        if(!empty($path)){
            $resp =ihttp_post(UPDATE_URL.'download.php',array('ip'=>$oauth['ip'], 'id'=>$oauth['id'], 'code'=>$code, 'domain'=>$oauth['domain'], 'path'=>$path ,'module'=>$oauth['module']));
            $ret =cloud_object_array(@json_decode($resp['content'], true));
            if($ret['status'] == 0){
                die(json_encode(array('result'=>1, 'total'=>1,'success'=>$ret['message'])));
            }
            if ($ret['status'] == 1){
                $path =$ret['path'];
                if($path == 'version.php'){

                }else{
                    if(!file_exists(IA_ROOT.'/addons/'.$oauth['module'].'/'.$path)){
                        mkdirs(dirname(IA_ROOT.'/addons/'.$oauth['module'].'/'.$path),"0777");
                    }
                }
                $content =base64_decode($ret['content']);
                file_put_contents(IA_ROOT.'/addons/'.$oauth['module'].''.$path, $content);
                $success =0;
                foreach($files as &$f){
                    if($f['path']==$path){
                        $f['download'] =1;
                        break;
                    }
                    if($f['download']){
                        $success++;
                    }
                }
                unset($f);
                $upgrade['files'] =$files;
                file_put_contents($tmpdir."/file.txt",json_encode($upgrade));
                die(json_encode(array('result'=>1, 'total'=>count($files),'success'=>$success."(".$path.")")));
            }
        }else{
            if(!empty($upgrade['version'])){
                file_put_contents(IA_ROOT.'/addons/'.$oauth['module'].'/version.php',"<?php if(!defined('VERSION')) {define('VERSION','".$upgrade['version']."');}");
            }
            @rmdirs($tmpdir);
            die(json_encode(array('result'=>2)));
        }
    }
}else{
    message('验证授权失败，请联系客服处理','','error');
}

//便利文件夹
function my_scandir($dir) {
    global $my_scenfiles;
    if ($handle = opendir($dir)) {
        while (($file = readdir($handle)) !== false) {
            if ($file != ".." && $file != ".") {
                if (is_dir($dir . "/" . $file)) {
                    my_scandir($dir . "/" . $file);
                } else {
                    $my_scenfiles[] = $dir . "/" . $file;
                }
            }
        }
        closedir($handle);
    }
}

/*
 * 结构转数组
 * */
function cloud_object_array($array) {
    if(is_object($array)) {
        $array = (array)$array;
    } if(is_array($array)) {
        foreach($array as $key=>$value) {
            $array[$key] = cloud_object_array($value);
        }
    }
    return $array;
}

function template2($filename, $flag = TEMPLATE_DISPLAY) {
    global $_W;
    $source = MODULE_ROOT . "/template/{$filename}.html";
    $compile = MODULE_ROOT . "/tpl/{$filename}.tpl.php";
    if(!is_file($source)) {
        exit("Error: template source '{$source}' is not exist!");
    }
    if(DEVELOPMENT || !is_file($compile) || filemtime($source) > filemtime($compile)) {
        template_compile($source, $compile);
    }
    switch ($flag) {
        case TEMPLATE_DISPLAY:
        default:
            extract($GLOBALS, EXTR_SKIP);
            include $compile;
            break;
        case TEMPLATE_FETCH:
            extract($GLOBALS, EXTR_SKIP);
            ob_flush();
            ob_clean();
            ob_start();
            include $compile;
            $contents = ob_get_contents();
            ob_clean();
            return $contents;
            break;
        case TEMPLATE_INCLUDEPATH:
            return $compile;
            break;
    }
}