<?php
function majax($filename){
	ob_start();
	include $this->template($filename);
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}