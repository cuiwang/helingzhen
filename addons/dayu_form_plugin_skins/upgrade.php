<?php
if(!pdo_fieldexists('dayu_form_skins', 'thumb')) {
	pdo_query("ALTER TABLE ".tablename('dayu_form_skins')." ADD `thumb` varchar(250) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('dayu_form_skins', 'ids')) {
	pdo_query("ALTER TABLE ".tablename('dayu_form_skins')." ADD `ids` text NOT NULL;");
}