<?php

pdo_query('CREATE TABLE IF NOT EXISTS `ims_hetu_seckill_goods` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) unsigned NOT NULL,
`cardtype_id` int(11) unsigned NOT NULL,
`name` varchar(50) NOT NULL,
`unit` varchar(10) NOT NULL,
`thumb` varchar(100) NOT NULL,
`sn` int(11) unsigned NOT NULL,
`barcode` varchar(20) NOT NULL,
`marketprice` float(10,2) NOT NULL,
`seksillprice` float(10,2) NOT NULL,
`total` int(11) unsigned NOT NULL,
`totalcnf` int(1) unsigned NOT NULL,
`maxbuy` int(11) unsigned NOT NULL,
`usermaxbuy` int(11) unsigned NOT NULL,
`sales` int(11) unsigned NOT NULL,
`credit` int(11) unsigned,
`content` text NOT NULL,
`specifications` text,
`status` int(1) unsigned NOT NULL,
`miaosha_type` varchar(10),
`displayorder` int(11) unsigned NOT NULL,
`supplier` varchar(100),
`supplier_pass` varchar(100),
`since_address` varchar(150),
`since_phone` int(15) unsigned,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_hetu_seckill_order` (
`order_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) unsigned NOT NULL,
`order_no` varchar(150) NOT NULL,
`member` int(11) unsigned NOT NULL,
`goods_id` int(11) unsigned NOT NULL,
`stage_id` int(11) unsigned NOT NULL,
`goods_nature` varchar(150) NOT NULL,
`goods_number` int(11) unsigned NOT NULL,
`goods_seksillprice` float(10,2) unsigned NOT NULL,
`order_yunfei` float(10,2) unsigned DEFAULT \'0.00\',
`order_totalprice` float(10,2) unsigned NOT NULL,
`address` varchar(255),
`peis` varchar(255),
`order_time` int(20) unsigned NOT NULL,
`kd_no` varchar(255),
`delivery_time` int(20) unsigned,
`sign_ttime` int(20) unsigned,
`qx_ttime` int(20) unsigned,
`status` int(1) unsigned NOT NULL DEFAULT \'1\',
PRIMARY KEY (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_hetu_seckill_peis` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) unsigned NOT NULL,
`name` varchar(150) NOT NULL,
`status` int(1) unsigned NOT NULL DEFAULT \'1\',
`delivery_fee` float(10,2) NOT NULL DEFAULT \'0.00\',
`compositor` int(11) unsigned NOT NULL DEFAULT \'0\',
`peis_type` int(1) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_hetu_seckill_remind` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) unsigned NOT NULL,
`userid` int(11) unsigned NOT NULL,
`stageid` int(11) unsigned NOT NULL,
`goodsid` int(11) unsigned NOT NULL,
`createtime` int(20) unsigned NOT NULL,
`status` int(1) unsigned NOT NULL DEFAULT \'0\',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_hetu_seckill_stage` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) unsigned NOT NULL,
`datetime` varchar(20) NOT NULL,
`timestart` varchar(20) NOT NULL,
`timeend` varchar(20) NOT NULL,
`goods` varchar(100) NOT NULL,
`status` int(1) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_hetu_seckill_user` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) unsigned NOT NULL,
`openid` varchar(100) NOT NULL,
`avatar` varchar(150) NOT NULL,
`phone` varchar(11) NOT NULL,
`nickname` varchar(50) NOT NULL,
`realname` varchar(50),
`sex` int(1) unsigned,
`address` text,
`status` int(1) unsigned NOT NULL DEFAULT \'1\',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

');
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `uniacid` int(11) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'cardtype_id')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `cardtype_id` int(11) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'name')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `name` varchar(50) NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'unit')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `unit` varchar(10) NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'thumb')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `thumb` varchar(100) NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'sn')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `sn` int(11) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'barcode')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `barcode` varchar(20) NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'marketprice')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `marketprice` float(10,2) NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'seksillprice')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `seksillprice` float(10,2) NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'total')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `total` int(11) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'totalcnf')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `totalcnf` int(1) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'maxbuy')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `maxbuy` int(11) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'usermaxbuy')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `usermaxbuy` int(11) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'sales')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `sales` int(11) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'credit')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `credit` int(11) unsigned;');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'content')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `content` text NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'specifications')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `specifications` text;');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'status')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `status` int(1) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'miaosha_type')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `miaosha_type` varchar(10);');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'displayorder')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `displayorder` int(11) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'supplier')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `supplier` varchar(100);');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'supplier_pass')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `supplier_pass` varchar(100);');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'since_address')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `since_address` varchar(150);');
    }
}
if (pdo_tableexists('hetu_seckill_goods')) {
    if (!pdo_fieldexists('hetu_seckill_goods', 'since_phone')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_goods') . ' ADD `since_phone` int(15) unsigned;');
    }
}
if (pdo_tableexists('hetu_seckill_order')) {
    if (!pdo_fieldexists('hetu_seckill_order', 'order_id')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_order') . ' ADD `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT;');
    }
}
if (pdo_tableexists('hetu_seckill_order')) {
    if (!pdo_fieldexists('hetu_seckill_order', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_order') . ' ADD `uniacid` int(11) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_order')) {
    if (!pdo_fieldexists('hetu_seckill_order', 'order_no')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_order') . ' ADD `order_no` varchar(150) NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_order')) {
    if (!pdo_fieldexists('hetu_seckill_order', 'member')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_order') . ' ADD `member` int(11) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_order')) {
    if (!pdo_fieldexists('hetu_seckill_order', 'goods_id')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_order') . ' ADD `goods_id` int(11) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_order')) {
    if (!pdo_fieldexists('hetu_seckill_order', 'stage_id')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_order') . ' ADD `stage_id` int(11) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_order')) {
    if (!pdo_fieldexists('hetu_seckill_order', 'goods_nature')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_order') . ' ADD `goods_nature` varchar(150) NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_order')) {
    if (!pdo_fieldexists('hetu_seckill_order', 'goods_number')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_order') . ' ADD `goods_number` int(11) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_order')) {
    if (!pdo_fieldexists('hetu_seckill_order', 'goods_seksillprice')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_order') . ' ADD `goods_seksillprice` float(10,2) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_order')) {
    if (!pdo_fieldexists('hetu_seckill_order', 'order_yunfei')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_order') . ' ADD `order_yunfei` float(10,2) unsigned DEFAULT \'0.00\';');
    }
}
if (pdo_tableexists('hetu_seckill_order')) {
    if (!pdo_fieldexists('hetu_seckill_order', 'order_totalprice')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_order') . ' ADD `order_totalprice` float(10,2) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_order')) {
    if (!pdo_fieldexists('hetu_seckill_order', 'address')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_order') . ' ADD `address` varchar(255);');
    }
}
if (pdo_tableexists('hetu_seckill_order')) {
    if (!pdo_fieldexists('hetu_seckill_order', 'peis')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_order') . ' ADD `peis` varchar(255);');
    }
}
if (pdo_tableexists('hetu_seckill_order')) {
    if (!pdo_fieldexists('hetu_seckill_order', 'order_time')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_order') . ' ADD `order_time` int(20) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_order')) {
    if (!pdo_fieldexists('hetu_seckill_order', 'kd_no')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_order') . ' ADD `kd_no` varchar(255);');
    }
}
if (pdo_tableexists('hetu_seckill_order')) {
    if (!pdo_fieldexists('hetu_seckill_order', 'delivery_time')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_order') . ' ADD `delivery_time` int(20) unsigned;');
    }
}
if (pdo_tableexists('hetu_seckill_order')) {
    if (!pdo_fieldexists('hetu_seckill_order', 'sign_ttime')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_order') . ' ADD `sign_ttime` int(20) unsigned;');
    }
}
if (pdo_tableexists('hetu_seckill_order')) {
    if (!pdo_fieldexists('hetu_seckill_order', 'qx_ttime')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_order') . ' ADD `qx_ttime` int(20) unsigned;');
    }
}
if (pdo_tableexists('hetu_seckill_order')) {
    if (!pdo_fieldexists('hetu_seckill_order', 'status')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_order') . ' ADD `status` int(1) unsigned NOT NULL DEFAULT \'1\';');
    }
}
if (pdo_tableexists('hetu_seckill_peis')) {
    if (!pdo_fieldexists('hetu_seckill_peis', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_peis') . ' ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;');
    }
}
if (pdo_tableexists('hetu_seckill_peis')) {
    if (!pdo_fieldexists('hetu_seckill_peis', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_peis') . ' ADD `uniacid` int(11) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_peis')) {
    if (!pdo_fieldexists('hetu_seckill_peis', 'name')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_peis') . ' ADD `name` varchar(150) NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_peis')) {
    if (!pdo_fieldexists('hetu_seckill_peis', 'status')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_peis') . ' ADD `status` int(1) unsigned NOT NULL DEFAULT \'1\';');
    }
}
if (pdo_tableexists('hetu_seckill_peis')) {
    if (!pdo_fieldexists('hetu_seckill_peis', 'delivery_fee')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_peis') . ' ADD `delivery_fee` float(10,2) NOT NULL DEFAULT \'0.00\';');
    }
}
if (pdo_tableexists('hetu_seckill_peis')) {
    if (!pdo_fieldexists('hetu_seckill_peis', 'compositor')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_peis') . ' ADD `compositor` int(11) unsigned NOT NULL DEFAULT \'0\';');
    }
}
if (pdo_tableexists('hetu_seckill_peis')) {
    if (!pdo_fieldexists('hetu_seckill_peis', 'peis_type')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_peis') . ' ADD `peis_type` int(1) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_remind')) {
    if (!pdo_fieldexists('hetu_seckill_remind', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_remind') . ' ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;');
    }
}
if (pdo_tableexists('hetu_seckill_remind')) {
    if (!pdo_fieldexists('hetu_seckill_remind', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_remind') . ' ADD `uniacid` int(11) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_remind')) {
    if (!pdo_fieldexists('hetu_seckill_remind', 'userid')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_remind') . ' ADD `userid` int(11) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_remind')) {
    if (!pdo_fieldexists('hetu_seckill_remind', 'stageid')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_remind') . ' ADD `stageid` int(11) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_remind')) {
    if (!pdo_fieldexists('hetu_seckill_remind', 'goodsid')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_remind') . ' ADD `goodsid` int(11) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_remind')) {
    if (!pdo_fieldexists('hetu_seckill_remind', 'createtime')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_remind') . ' ADD `createtime` int(20) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_remind')) {
    if (!pdo_fieldexists('hetu_seckill_remind', 'status')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_remind') . ' ADD `status` int(1) unsigned NOT NULL DEFAULT \'0\';');
    }
}
if (pdo_tableexists('hetu_seckill_stage')) {
    if (!pdo_fieldexists('hetu_seckill_stage', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_stage') . ' ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;');
    }
}
if (pdo_tableexists('hetu_seckill_stage')) {
    if (!pdo_fieldexists('hetu_seckill_stage', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_stage') . ' ADD `uniacid` int(11) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_stage')) {
    if (!pdo_fieldexists('hetu_seckill_stage', 'datetime')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_stage') . ' ADD `datetime` varchar(20) NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_stage')) {
    if (!pdo_fieldexists('hetu_seckill_stage', 'timestart')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_stage') . ' ADD `timestart` varchar(20) NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_stage')) {
    if (!pdo_fieldexists('hetu_seckill_stage', 'timeend')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_stage') . ' ADD `timeend` varchar(20) NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_stage')) {
    if (!pdo_fieldexists('hetu_seckill_stage', 'goods')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_stage') . ' ADD `goods` varchar(100) NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_stage')) {
    if (!pdo_fieldexists('hetu_seckill_stage', 'status')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_stage') . ' ADD `status` int(1) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_user')) {
    if (!pdo_fieldexists('hetu_seckill_user', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_user') . ' ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;');
    }
}
if (pdo_tableexists('hetu_seckill_user')) {
    if (!pdo_fieldexists('hetu_seckill_user', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_user') . ' ADD `uniacid` int(11) unsigned NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_user')) {
    if (!pdo_fieldexists('hetu_seckill_user', 'openid')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_user') . ' ADD `openid` varchar(100) NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_user')) {
    if (!pdo_fieldexists('hetu_seckill_user', 'avatar')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_user') . ' ADD `avatar` varchar(150) NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_user')) {
    if (!pdo_fieldexists('hetu_seckill_user', 'phone')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_user') . ' ADD `phone` varchar(11) NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_user')) {
    if (!pdo_fieldexists('hetu_seckill_user', 'nickname')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_user') . ' ADD `nickname` varchar(50) NOT NULL;');
    }
}
if (pdo_tableexists('hetu_seckill_user')) {
    if (!pdo_fieldexists('hetu_seckill_user', 'realname')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_user') . ' ADD `realname` varchar(50);');
    }
}
if (pdo_tableexists('hetu_seckill_user')) {
    if (!pdo_fieldexists('hetu_seckill_user', 'sex')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_user') . ' ADD `sex` int(1) unsigned;');
    }
}
if (pdo_tableexists('hetu_seckill_user')) {
    if (!pdo_fieldexists('hetu_seckill_user', 'address')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_user') . ' ADD `address` text;');
    }
}
if (pdo_tableexists('hetu_seckill_user')) {
    if (!pdo_fieldexists('hetu_seckill_user', 'status')) {
        pdo_query('ALTER TABLE ' . tablename('hetu_seckill_user') . ' ADD `status` int(1) unsigned NOT NULL DEFAULT \'1\';');
    }
}