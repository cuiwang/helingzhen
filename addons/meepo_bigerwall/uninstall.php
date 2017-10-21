<?php
if(pdo_tableexists('weixin_cookie')) {
		pdo_query("DROP TABLE ".tablename("weixin_cookie")." ");
}
if(pdo_tableexists('weixin_flag')) {
		pdo_query("DROP TABLE ".tablename("weixin_flag")." ");
}
if(pdo_tableexists('weixin_vote')) {
		pdo_query("DROP TABLE ".tablename("weixin_vote")." ");
}
if(pdo_tableexists('weixin_wall')) {
		pdo_query("DROP TABLE ".tablename("weixin_wall")." ");
}
if(pdo_tableexists('weixin_shake_toshake')) {
		pdo_query("DROP TABLE ".tablename("weixin_shake_toshake")." ");
}
if(pdo_tableexists('weixin_wall_num')) {
		pdo_query("DROP TABLE ".tablename("weixin_wall_num")." ");
}
if(pdo_tableexists('weixin_wall_reply')) {
		pdo_query("DROP TABLE ".tablename("weixin_wall_reply")." ");
}
if(pdo_tableexists('weixin_luckuser')) {
		pdo_query("DROP TABLE ".tablename("weixin_luckuser")." ");
}
if(pdo_tableexists('weixin_awardlist')) {
		pdo_query("DROP TABLE ".tablename("weixin_awardlist")." ");
}
if(pdo_tableexists('weixin_shake_data')) {
		pdo_query("DROP TABLE ".tablename("weixin_shake_data")." ");
}
if(pdo_tableexists('weixin_signs')) {
		pdo_query("DROP TABLE ".tablename("weixin_signs")." ");
}
if(pdo_tableexists('weixin_modules')) {
		pdo_query("DROP TABLE ".tablename("weixin_modules")." ");
}