<?php
global $_W,$_GPC;
$sql = "
CREATE TABLE IF NOT EXISTS ".tablename('weixin_cookie')." (
`id` int(11) NOT NULL AUTO_INCREMENT,
`cookie` text NOT NULL,  
`cookies` text NOT NULL, 
`token` int(11) NOT NULL,
 `weid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
pdo_query($sql);
$data = array('cookie'=>'meepo','cookies'=>'meepo','token'=>1,'weid'=>100);
pdo_insert('weixin_cookie',$data);
die('DO SUCCESS');