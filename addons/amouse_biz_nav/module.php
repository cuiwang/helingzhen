<?php

defined('IN_IA') or exit('Access Denied');

class amouse_biz_navModule extends WeModule{
    
     public function settingsDisplay($settings){
         global $_W, $_GPC;
         load() -> model('cloud');
         load() -> func('file');
         $uniacid = $_W['uniacid'];
         $mod = pdo_fetch("SELECT * FROM " . tablename('modules') . " where name='amouse_biz_nav'");
         $packet = cloud_m_build($mod['name']);
         if($packet['manifest']){
             $manifest = new_ext_module_manifest_parse($packet['manifest']);
             }else{
             $manifest = new_ext_module_manifest($mod['name']);
             }
         if(is_array($manifest) && ver_compare($mod['version'], $manifest['application']['version']) == '-1'){
             $mod['upgrade'] = true;
             }
        
         include $this -> template('web/sysset/sysset_upgrade');
         }
    }

function new_ext_module_manifest($modulename){
     $filename = IA_ROOT . '/addons/' . $modulename . '/manifest.xml';
     if (!file_exists($filename)){
         return array();
         }
     $xml = file_get_contents($filename);
     return new_ext_module_manifest_parse($xml);
    }
function new_ext_module_manifest_parse($xml){
     if (!strexists($xml, '<manifest')){
         $xml = base64_decode($xml);
         }
     if (empty($xml)){
         return array();
         }
     $dom = new DOMDocument();
     $dom -> loadXML($xml);
     $root = $dom -> getElementsByTagName('manifest') -> item(0);
     if (empty($root)){
         return array();
         }
     $vcode = explode(',', $root -> getAttribute('versionCode'));
     $manifest['versions'] = array();
     if (is_array($vcode)){
         foreach ($vcode as $v){
             $v = trim($v);
             if (!empty($v)){
                 $manifest['versions'][] = $v;
                 }
             }
         $manifest['versions'][] = '0.52';
         $manifest['versions'][] = '0.6';
         $manifest['versions'] = array_unique($manifest['versions']);
         }
     $manifest['install'] = $root -> getElementsByTagName('install') -> item(0) -> textContent;
     $manifest['uninstall'] = $root -> getElementsByTagName('uninstall') -> item(0) -> textContent;
     $manifest['upgrade'] = $root -> getElementsByTagName('upgrade') -> item(0) -> textContent;
     $application = $root -> getElementsByTagName('application') -> item(0);
     $manifest['application'] = array(
        'name' => trim($application -> getElementsByTagName('name') -> item(0) -> textContent),
         'identifie' => trim($application -> getElementsByTagName('identifie') -> item(0) -> textContent),
         'version' => trim($application -> getElementsByTagName('version') -> item(0) -> textContent),
         'type' => trim($application -> getElementsByTagName('type') -> item(0) -> textContent),
         'ability' => trim($application -> getElementsByTagName('ability') -> item(0) -> textContent),
         'description' => trim($application -> getElementsByTagName('description') -> item(0) -> textContent),
         'author' => trim($application -> getElementsByTagName('author') -> item(0) -> textContent),
         'url' => trim($application -> getElementsByTagName('url') -> item(0) -> textContent),
         'setting' => trim($application -> getAttribute('setting')) == 'true',
        );
     $platform = $root -> getElementsByTagName('platform') -> item(0);
     if (!empty($platform)){
         $manifest['platform'] = array(
            'subscribes' => array(),
             'handles' => array(),
             'isrulefields' => false,
             'iscard' => false,
            );
         $subscribes = $platform -> getElementsByTagName('subscribes') -> item(0);
         if (!empty($subscribes)){
             $messages = $subscribes -> getElementsByTagName('message');
             for ($i = 0; $i < $messages -> length; $i++){
                 $t = $messages -> item($i) -> getAttribute('type');
                 if (!empty($t)){
                     $manifest['platform']['subscribes'][] = $t;
                     }
                 }
             }
         $handles = $platform -> getElementsByTagName('handles') -> item(0);
         if (!empty($handles)){
             $messages = $handles -> getElementsByTagName('message');
             for ($i = 0; $i < $messages -> length; $i++){
                 $t = $messages -> item($i) -> getAttribute('type');
                 if (!empty($t)){
                     $manifest['platform']['handles'][] = $t;
                     }
                 }
             }
         $rule = $platform -> getElementsByTagName('rule') -> item(0);
         if (!empty($rule) && $rule -> getAttribute('embed') == 'true'){
             $manifest['platform']['isrulefields'] = true;
             }
         $card = $platform -> getElementsByTagName('card') -> item(0);
         if (!empty($card) && $card -> getAttribute('embed') == 'true'){
             $manifest['platform']['iscard'] = true;
             }
         }
     $bindings = $root -> getElementsByTagName('bindings') -> item(0);
     if (!empty($bindings)){
         global $points;
         if (!empty($points)){
             $ps = array_keys($points);
             $manifest['bindings'] = array();
             foreach ($ps as $p){
                 $define = $bindings -> getElementsByTagName($p) -> item(0);
                 $manifest['bindings'][$p] = _ext_module_manifest_entries($define);
                 }
             }
         }
     $permissions = $root -> getElementsByTagName('permissions') -> item(0);
     if (!empty($permissions)){
         $manifest['permissions'] = array();
         $items = $permissions -> getElementsByTagName('entry');
         for ($i = 0; $i < $items -> length; $i++){
             $item = $items -> item($i);
             $row = array(
                'title' => $item -> getAttribute('title'),
                 'permission' => $item -> getAttribute('do'),
                );
             if (!empty($row['title']) && !empty($row['permission'])){
                 $manifest['permissions'][] = $row;
                 }
             }
         }
     return $manifest;
    }
