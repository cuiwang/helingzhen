<?php
defined('IN_IA') or exit('Access Denied');
class lwx_loveModuleProcessor extends WeModuleProcessor
{
    public function respond()
    {
        $content = $this->message['content'];
    }
}
