<?php

pdo_query("DROP TABLE IF EXISTS ".tablename('mon_baton').";");

pdo_query("DROP TABLE IF EXISTS ".tablename('mon_baton_user').";");
pdo_query("DROP TABLE IF EXISTS ".tablename('mon_baton_setting').";");