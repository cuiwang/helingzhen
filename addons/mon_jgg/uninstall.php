<?php

pdo_query("DROP TABLE IF EXISTS ".tablename('mon_jgg').";");

pdo_query("DROP TABLE IF EXISTS ".tablename('mon_jgg_user').";");
pdo_query("DROP TABLE IF EXISTS ".tablename('mon_jgg_user_record').";");
pdo_query("DROP TABLE IF EXISTS ".tablename('mon_jgg_user_award').";");