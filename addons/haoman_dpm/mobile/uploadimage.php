<?php
global $_W,$_GPC;
$rid = intval($_GPC['rid']);
load()->func('file');
$reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));

if (empty($_FILES['file']['name'])) {
    $result['message'] = '请选择要上传的文件！';
    exit(json_encode($result));
}

$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

$ext = strtolower($ext);
//$result['message'] = '请选择要上传的文件！'.$ext;
//exit(json_encode($result));
if (in_array($ext, array('mp4', 'mp3', 'avi', 'wmv', '3gp','rmvb','mov'))) {

//    if ($file = $this->fileUpload($_FILES['file'], 'video')) {
    if ($file = $this->fileUpload($_FILES['file'], 'audio')) {
        if ($file['error']) {
            exit('0');
            //exit(json_encode($file));
        }
        $result['url'] = $_W['config']['upload']['attachdir'] . $file['filename'];
        $result['error'] = $file['error'];
        $result['filename'] = $file['filename'];

        $pathname = $result['filename'];
        if (!empty($_W['setting']['remote']['type'])) { // 判断系统是否开启了远程附件
            $remotestatus = file_remote_upload($pathname); //上传图片到远程
            if (is_error($remotestatus)) {
                $result['msg'] = $remotestatus['message'];
            }
        }

        exit(json_encode($result));
    }

}else{
    if ($file = $this->fileUpload($_FILES['file'], 'image')) {
        if ($file['error']) {
            exit('0');
            //exit(json_encode($file));
        }
        $result['url'] = $_W['config']['upload']['attachdir'] . $file['filename'];
        $result['error'] = $file['error'];
        $result['filename'] = $file['filename'];

        $pathname = $result['filename'];
        if (!empty($_W['setting']['remote']['type'])) { // 判断系统是否开启了远程附件
            $remotestatus = file_remote_upload($pathname); //上传图片到远程
            if (is_error($remotestatus)) {
                $result['msg'] = $remotestatus['message'];
            }
        }

        exit(json_encode($result));
    }
}