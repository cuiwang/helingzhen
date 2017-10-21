<?php
defined('IN_IA') or exit('Access Denied');
class lwx_nicedumplingsModuleReceiver extends WeModuleReceiver
{
    public function receive()
    {
        $type = $this->message['type'];
    }
}
