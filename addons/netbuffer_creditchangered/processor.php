<?php
defined ( 'IN_IA' ) or exit ( 'Access Denied' );
class netbuffer_creditchangeredModuleProcessor extends WeModuleProcessor {
	public function respond() {
		return $this->respText("你好");
	}
}
?>