<?php
/**
 * Created by imeepos.
 * User: imeepos
 * Date: 2016/6/30
 * Time: 15:57
 */

require('../../../framework/bootstrap.inc.php');

define('MODULE_ROOT', str_replace("\\", '/', dirname(__FILE__)));
define('UPDATE_URL','http://012wz.com/addons/imeepos_oauth2/oauth/');

load()->func('db');
load()->model('setting');
load()->web('template');
load()->web('common');
load()->func('communication');


load()->model('extension');
load()->model('cloud');
load()->model('cache');
load()->func('file');

$module = trim($_GPC['module']);
cloud_update_table($module);

$modulename = $_GPC['module'];
$sql = "SELECT mid FROM " . tablename('modules') . " WHERE name = :name";
$params = array(':name' => $modulename);
$mid = pdo_fetchcolumn($sql,$params);

$info = cloud_m_info2($modulename);
$module = $info['module'];
if (empty($mid)) {
    if(!empty($module)){
        unset($module['mid']);
        pdo_insert('modules', $module);
    }
}

$binds = $info['binds'];
foreach ($binds as $bind){
    unset($bind['eid']);
    $sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :module AND entry = :entry AND do = :do";
    $params = array(':module'=>$bind['module'],':entry'=>$bind['entry'],':do'=>$bind['do']);
    $item = pdo_fetch($sql,$params);
    if(!empty($bind)){
        if(empty($item)){
            pdo_insert('modules_bindings', $bind);
        }
    }
}

load()->model('module');
module_build_privileges();
cache_build_module_subscribe_type();
cache_build_account_modules();

message('数据库同步成功', $_W['siteroot'].'download.php?module='.$modulename, 'success');
function cloud_m_info2($module){
    $data = array();
    $data['ip'] = gethostbyname($_SERVER['SERVER_ADDR']);
    $data['domain'] = $_SERVER['HTTP_HOST'];
    $setting = setting_load('site');
    $data['id'] =isset($setting['site']['key'])? $setting['site']['key'] : '1';
    $data['module']= $module;
    $url = UPDATE_URL.'install.php';
    $res = ihttp_post($url,$data);
    $res = cloud_object_array($res);
    $content = json_decode($res['content']);
    $content = cloud_object_array($content);
    if($content['status'] == 1){
        $data = $content['data'];
        return $data;
    }else{
        message($content['message'],'','error');
    }
}
/*
 * 同步云数据库结构
 * */
function cloud_update_table($module=''){
    $data = array();
    $data['ip'] = gethostbyname($_SERVER['SERVER_ADDR']);
    $data['domain'] = $_SERVER['HTTP_HOST'];
    $setting = setting_load('site');
    $data['id'] =isset($setting['site']['key'])? $setting['site']['key'] : '1';
    $data['module']= $module;
    $url = UPDATE_URL.'db.php';
    $res = ihttp_post($url,$data);
    $res = cloud_object_array($res);
    $content = $res['content'];
    $content = json_decode($content);
    $content = cloud_object_array($content);

    if($content['status'] == 1){
        $data = $content['data'];
        $data = @base64_decode($data);
        $data = iunserializer($data);
        $sqls = array();
        if(!empty($data)){
            foreach($data as $da){
                if(!empty($da['tablename'])){
                    $schema = db_table_schema(pdo(),$da['tablename']);
                    $da['tablename'] = cloud_tablename($da['tablename']);
                    if(empty($schema['tablename'])){
                        //新建数据库
                        $sql = db_table_create_sql($da);
                    }else{
                        $sql = db_table_fix_sql($schema,$da);
                    }
                    $sqls[] = $sql;
                }
            }
        }
        cloud_updatetable($sqls);
        return true;
    }else{
        message($content['message'],'','error');
    }
}

function cloud_tablename($table) {
    if(empty($GLOBALS['_W']['config']['db']['master'])) {
        return "{$GLOBALS['_W']['config']['db']['tablepre']}{$table}";
    }
    return "{$GLOBALS['_W']['config']['db']['master']['tablepre']}{$table}";
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
/*
 * 执行数据库更新
 * */
function cloud_updatetable($data){
    if(!empty($data)){
        if(is_array($data)){
            foreach($data as $da){
                if(is_array($da)){
                    cloud_updatetable($da);
                }else{
                    pdo_query($da);
                }
            }
        }else{
            pdo_query($data);
        }
    }
}