<?php

defined('IN_IA') or exit('Access Denied');
class lwx_nicedumplingsModuleProcessor extends WeModuleProcessor
{
    public function respond()
    {
        $content = $this->message['content'];
    }
}
