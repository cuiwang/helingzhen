<?php
include MODULE_ROOT.'/inc/mobile/__init.php';
if(empty($member)){
	message('错误你的信息不存在或是已经被删除！');
}
include $this->template('msg');