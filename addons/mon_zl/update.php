<?php
if (!pdo_fieldexists('mon_zl_friend', 'point')) {
    pdo_query("ALTER TABLE " . tablename('mon_zl_friend') . " ADD  `point` int(3) default 0 ;");
}

if (!pdo_fieldexists('mon_zl_friend', 'nickname')) {
    pdo_query("ALTER TABLE " . tablename('mon_zl_friend') . " ADD  `nickname` varchar(300) ;");
}

if (!pdo_fieldexists('mon_zl_friend', 'headimgurl')) {
    pdo_query("ALTER TABLE " . tablename('mon_zl_friend') . " ADD `headimgurl` varchar(300) ;");
}

if (!pdo_fieldexists('mon_zl_user', 'ptime')) {
    pdo_query("ALTER TABLE " . tablename('mon_zl_user') . " ADD  `ptime` int(3) default 0 ;");
}



if (!pdo_fieldexists('mon_zl', 'top_tag')) {
    pdo_query("ALTER TABLE " . tablename('mon_zl') . " ADD  `top_tag` int(3) ;");

}


if (!pdo_fieldexists('mon_zl', 'view_count')) {
    pdo_query("ALTER TABLE " . tablename('mon_zl') . " ADD  `view_count` int(3) ;");
}

if (!pdo_fieldexists('mon_zl', 'share_count')) {
    pdo_query("ALTER TABLE " . tablename('mon_zl') . " ADD  `share_count` int(3) ;");
}


if (!pdo_fieldexists('mon_zl', 'zlunit')) {
    pdo_query("ALTER TABLE " . tablename('mon_zl') . " ADD  `zlunit` varchar(10) ;");
}

if (!pdo_fieldexists('mon_zl', 'share_bg')) {
    pdo_query("ALTER TABLE " . tablename('mon_zl') . " ADD  `share_bg` varchar(300) ;");
}
if (!pdo_fieldexists('mon_zl', 'syncredit')) {
    pdo_query("ALTER TABLE " . tablename('mon_zl') . " ADD   `syncredit` int(1) DEFAULT NULL ;");
}
if (!pdo_fieldexists('mon_zl_user', 'moid')) {
    pdo_query("ALTER TABLE " . tablename('mon_zl_user') . " ADD    `moid` varchar(500) DEFAULT NULL;");
}

if (!pdo_fieldexists('mon_zl', 'f_zl_limit_tip')) {
    pdo_query("ALTER TABLE " . tablename('mon_zl') . " ADD   `f_zl_limit_tip` varchar(2000) DEFAULT NULL;");
}
if (!pdo_fieldexists('mon_zl', 'f_day_limit')) {
    pdo_query("ALTER TABLE " . tablename('mon_zl') . " ADD     `f_day_limit` int(10) DEFAULT NULL;");
}
if (!pdo_fieldexists('mon_zl', 'f_day_limit_tip')) {
    pdo_query("ALTER TABLE " . tablename('mon_zl') . " ADD   `f_day_limit_tip` varchar(2000) DEFAULT NULL;");
}
if (!pdo_fieldexists('mon_zl', 'f_diff_limt')) {
    pdo_query("ALTER TABLE " . tablename('mon_zl') . " ADD    `f_diff_limt` int(10) DEFAULT NULL;");
}
if (!pdo_fieldexists('mon_zl', 'f_diff_tip')) {
    pdo_query("ALTER TABLE " . tablename('mon_zl') . " ADD  `f_diff_tip` varchar(2000) DEFAULT NULL;");
}
if (!pdo_fieldexists('mon_zl', 'ip_limit')) {
    pdo_query("ALTER TABLE " . tablename('mon_zl') . " ADD     `ip_limit` int(10) DEFAULT NULL;");
}
if (!pdo_fieldexists('mon_zl', 'ip_limit_tip')) {
    pdo_query("ALTER TABLE " . tablename('mon_zl') . " ADD     `ip_limit_tip` varchar(2000) DEFAULT NULL;");
}
if (!pdo_fieldexists('mon_zl', 'tmp_enable')) {
    pdo_query("ALTER TABLE " . tablename('mon_zl') . " ADD     `tmp_enable` int(1) DEFAULT NULL;");
}
if (!pdo_fieldexists('mon_zl', 'tmpId')) {
    pdo_query("ALTER TABLE " . tablename('mon_zl') . " ADD      `tmpId` varchar(1000) DEFAULT NULL;");
}
if (!pdo_fieldexists('mon_zl_user', 'isblack')) {
    pdo_query("ALTER TABLE " . tablename('mon_zl_user') . " ADD     `isblack` int(1) DEFAULT NULL;");
}