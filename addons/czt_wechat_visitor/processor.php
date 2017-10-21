<?php
defined('IN_IA') or exit('Access Denied');
class Czt_wechat_visitorModuleProcessor extends WeModuleProcessor
{
    public function respond()
    {
        $content = $this->message['content'];
    }
}