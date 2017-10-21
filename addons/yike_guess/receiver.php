<?php

defined('IN_IA') or exit('Access Denied');
class Yike_guessModuleReceiver extends WeModuleReceiver
{
    public function receive()
    {
        $type = $this->message['type'];
    }
}