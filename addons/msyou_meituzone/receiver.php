<?php
defined('IN_IA') or exit('Access Denied');
class Msyou_meituzoneModuleReceiver extends WeModuleReceiver
{
    public function receive()
    {
        $type = $this->message['type'];
    }
}