<?php

pdo_query("DROP TABLE IF EXISTS ".tablename('tim_city_cate').";");
pdo_query("DROP TABLE IF EXISTS ".tablename('tim_city_member').";");
pdo_query("DROP TABLE IF EXISTS ".tablename('tim_city_event').";");
pdo_query("DROP TABLE IF EXISTS ".tablename('tim_city_set').";");
pdo_query("DROP TABLE IF EXISTS ".tablename('tim_city_slide').";");