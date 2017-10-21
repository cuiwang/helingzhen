<?php

defined('IN_IA') or exit('Access Denied');
class Yg_supergrowthModuleReceiver extends WeModuleReceiver
{
    public function receive()
    {
        $type = $this->message['type'];
    }
}