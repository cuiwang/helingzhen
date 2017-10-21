<?php

if(!pdo_fieldexists('str_store', 'email_notice')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `email_notice` INT( 3 ) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_store', 'email')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `email` varchar(30) NOT NULL;");
}
if (pdo_fieldexists('str_dish', 'price')) {
	pdo_query('ALTER TABLE ' . tablename('str_dish') . " CHANGE `price` `price` VARCHAR(200) NOT NULL;");
}
