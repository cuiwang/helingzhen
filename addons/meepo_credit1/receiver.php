<?php
defined('IN_IA') or exit('Access Denied');
class Meepo_credit1ModuleReceiver extends WeModuleReceiver
{
    public function receive()
    {
        $type = $this->message['type'];
    }
}