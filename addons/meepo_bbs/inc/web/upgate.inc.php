<?php
global $_W,$_GPC;
if(empty($_W['isfounder'])) {
	message('您没有相应操作权限', '', 'error');
}
