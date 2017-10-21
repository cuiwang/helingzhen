<?php
defined('IN_IA') or exit('Access Denied');
class Meepo_credit1ModuleProcessor extends WeModuleProcessor
{
    public function respond()
    {
        $content = $this->message['content'];
    }
}