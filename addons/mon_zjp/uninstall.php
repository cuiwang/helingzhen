<?php

pdo_query("DROP TABLE IF EXISTS ".tablename('mon_zjp').";");

pdo_query("DROP TABLE IF EXISTS ".tablename('mon_zjp_prize').";");
pdo_query("DROP TABLE IF EXISTS ".tablename('mon_zjp_user').";");
pdo_query("DROP TABLE IF EXISTS ".tablename('mon_zjp_record').";");