<?php


if (!pdo_fieldexists('mon_baton', 'end_dialog_tip')) {
    pdo_query("ALTER TABLE " . tablename('mon_baton') . " ADD  `end_dialog_tip` varchar(500);");

}








