<?php
pdo_query("DROP TABLE IF EXISTS ".tablename('cgc_baoming_reply').";");
pdo_query("DROP TABLE IF EXISTS ".tablename('cgc_baoming_code').";");
pdo_query("DROP TABLE IF EXISTS ".tablename('cgc_baoming_user').";");
pdo_query("DROP TABLE IF EXISTS ".tablename('cgc_baoming_activity').";");

