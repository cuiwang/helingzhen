<?php
defined('IN_IA') or exit('Access Denied');
class Kobe_waterModuleProcessor extends WeModuleProcessor {
    public function respond() {
        $content = $this->message['content'];
    }
}
?>
