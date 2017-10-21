<?php
if(pdo_tableexists('meepo_xianchang_cookie')) {
   pdo_insert("meepo_xianchang_cookie",array('weid'=>1,'rid'=>1,'cookie'=>'meepo','createtime'=>time()));
}
die('success');