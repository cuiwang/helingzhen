<?php
global $_W, $_GPC;

$url = $_W['siteroot'] . 'app/' . substr($this->createMobileUrl('build'), 2);
$biz = $this->module['config']['biz'];
if(empty($biz['title'])) {
    $biz['title'] = '猴赛雷语音祝福';
}
if($biz['subscribe'] == 'true' && empty($_W['fans']['follow'])) {
    $subscribeUrl = $biz['guide'];
}
$storage = $this->module['config']['storage'];
if($storage['resource'] == 'true') {
    define('RESOURCE_URL', 'http://' . $storage['host']);
} else {
    define('RESOURCE_URL', '../addons/mb_swish');
}
include $this->template('play');
?>
