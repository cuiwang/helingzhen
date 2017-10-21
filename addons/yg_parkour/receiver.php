<?php
defined('IN_IA') or exit('Access Denied');
class Yg_parkourModuleReceiver extends WeModuleReceiver
{
    public function receive()
    {
        $type = $this->message['type'];
    }
}