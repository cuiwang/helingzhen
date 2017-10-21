<?php defined('IN_IA') or exit('Access Denied');?><ul class="nav nav-tabs">
	<li<?php  if($do == 'installed') { ?> class="active"<?php  } ?>><a href="<?php  echo url('extension/module/installed');?>">已安装的模块</a></li>
	<li<?php  if(($do == 'prepared' || $do == 'install') && $status != 'recycle' ) { ?> class="active"<?php  } ?>><a href="<?php  echo url('extension/module/prepared');?>">安装模块</a></li>
	<li<?php  if($do == 'designer') { ?> class="active"<?php  } ?>><a href="<?php  echo url('extension/module/designer');?>">设计新模块</a></li>
	<li<?php  if($do == 'prepared' && $status == 'recycle') { ?> class="active"<?php  } ?>><a href="<?php  echo url('extension/module/prepared', array('status' => 'recycle'));?>">模块回收站</a></li>
	<li><a href="http://www.012wz.com" target="_blank">查找更多模块</a></li>
	<?php  if($do == 'permission') { ?><li class="active"><a href="<?php  echo url('extension/module/permission', array('id' => $id))?>">当前模块</a></li><?php  } ?>
</ul>
