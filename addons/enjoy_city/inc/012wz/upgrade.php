<?php
if (!pdo_fieldexists("enjoy_city_kind", "wurl")) {
    pdo_query("ALTER TABLE " . tablename("enjoy_city_kind") . " ADD `wurl` varchar(1000) NOT NULL DEFAULT '';");
}
if (!pdo_fieldexists("enjoy_city_reply", "weixin")) {
    pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `weixin` varchar(200) NOT NULL DEFAULT '';");
}
if (!pdo_fieldexists("enjoy_city_reply", "fee")) {
    pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `fee` varchar(200) NOT NULL DEFAULT '200';");
}
if (!pdo_fieldexists("enjoy_city_reply", "mshare_title")) {
    pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `mshare_title` varchar(500) NOT NULL DEFAULT '';");
}
if (!pdo_fieldexists("enjoy_city_reply", "mshare_icon")) {
    pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `mshare_icon` varchar(500) NOT NULL DEFAULT '';");
}
if (!pdo_fieldexists("enjoy_city_reply", "mshare_content")) {
    pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `mshare_content` varchar(1000) NOT NULL DEFAULT '';");
}
if (!pdo_fieldexists("enjoy_city_reply", "jointel")) {
    pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `jointel` varchar(500) NOT NULL DEFAULT '';");
}
if (!pdo_fieldexists("enjoy_city_reply", "slogo")) {
    pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD  `slogo` longtext;");
}
if (!pdo_fieldexists("enjoy_city_reply", "banner")) {
    pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD  `banner` longtext;");
}
if (!pdo_fieldexists("enjoy_city_reply", "issq")) {
    pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD  `issq` int(2) NOT NULL DEFAULT '0';");
}
pdo_update("enjoy_city_reply", array(
    "issq" => 1
), array());
if (!pdo_fieldexists("enjoy_city_firm", "ispay")) {
    pdo_query("ALTER TABLE " . tablename("enjoy_city_firm") . " ADD `ispay` int(2) NOT NULL DEFAULT '0';");
}
if (!pdo_fieldexists("enjoy_city_firm", "paymoney")) {
    pdo_query("ALTER TABLE " . tablename("enjoy_city_firm") . " ADD `paymoney` float(50,2);");
}
if (!pdo_fieldexists("enjoy_city_firm", "sid")) {
    pdo_query("ALTER TABLE " . tablename("enjoy_city_firm") . " ADD `sid` int(50);");
}
if (!pdo_fieldexists("enjoy_city_firm", "breaks")) {
    pdo_query("ALTER TABLE " . tablename("enjoy_city_firm") . " ADD `breaks` longtext;");
}
if (!pdo_fieldexists("enjoy_city_firm", "firmurl")) {
    pdo_query("ALTER TABLE " . tablename("enjoy_city_firm") . " ADD `firmurl` varchar(5000) DEFAULT NULL;");
}
pdo_query("	CREATE TABLE IF NOT EXISTS " . tablename("enjoy_city_forward") . " (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `fid` int(200) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `createtime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;");
pdo_query("	CREATE TABLE IF NOT EXISTS " . tablename("enjoy_city_seller") . " (
  `id` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `realname` varchar(500) DEFAULT NULL,
  `openid` varchar(1000) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `createtime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;");
pdo_query("alter table " . tablename("enjoy_city_firm") . " modify column `intro` longtext;");
if ($http_host1!='wqwx.diaodingshop.com' && $http_host1!='ccc.0470mi.com' && $http_host1!='rrd.wxqyb.com' && $http_host1!='ws.dhipr.cn' && $http_host1!='v.hckee.com') {
    pdo_query("	CREATE TABLE IF NOT EXISTS " . tablename('enjoy_city_firmfans') . " (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `fid` int(255) DEFAULT NULL,
  `rid` int(255) DEFAULT NULL,
  `openid` varchar(500) DEFAULT NULL,
  `createtime` varchar(50) DEFAULT NULL,
  `favatar` longtext,
  `fnickname` varchar(500) DEFAULT NULL,
  `flag` int(2) DEFAULT '0',
  PRIMARY KEY (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    pdo_query("	CREATE TABLE IF NOT EXISTS " . tablename("enjoy_city_fansxx") . " (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `fid` int(255) DEFAULT NULL,
  `title` varchar(1000) DEFAULT NULL,
  `intro` longtext,
  `createtime` varchar(50) DEFAULT NULL,
  `overtime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    pdo_query("	CREATE TABLE IF NOT EXISTS " . tablename("enjoy_city_job") . " (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `fid` int(255) DEFAULT NULL,
  `ptitle` varchar(500) DEFAULT NULL,
  `wages` varchar(500) DEFAULT NULL,
  `pnum` int(50) DEFAULT NULL,
  `pmobile` varchar(50) DEFAULT NULL,
  `isend` int(2) NOT NULL DEFAULT '0',
  `isfull` int(2) NOT NULL DEFAULT '0',
  `paddress` varchar(5000) DEFAULT NULL,
  `pdetail` longtext,
  `ischeck` int(11) NOT NULL DEFAULT '0',
  `updatetime` varchar(50) DEFAULT NULL,
  `createtime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    pdo_query("	CREATE TABLE IF NOT EXISTS " . tablename("enjoy_city_firmlabel") . " (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `openid` varchar(500) DEFAULT NULL,
  `label` varchar(100) DEFAULT NULL,
  `checked` int(2) NOT NULL DEFAULT '0',
  `times` int(50) unsigned DEFAULT NULL,
  `checktime` varchar(30) DEFAULT NULL,
  `fid` int(50) DEFAULT NULL,
  `createtime` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    pdo_query("	CREATE TABLE IF NOT EXISTS " . tablename("enjoy_city_taglap") . " (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `tagid` int(255) DEFAULT NULL,
  `openid` varchar(200) DEFAULT NULL,
  `fid` int(255) DEFAULT NULL,
  `flag` int(2) NOT NULL DEFAULT '1',
  `createtime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    pdo_query("	CREATE TABLE IF NOT EXISTS " . tablename("enjoy_city_custom") . " (
    `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
    `uniacid` int(50) DEFAULT NULL,
    `hot` int(50) DEFAULT NULL,
    `name` varchar(500) DEFAULT NULL,
    `thumb` varchar(1000) DEFAULT NULL,
    `wurl` varchar(1000) DEFAULT NULL,
    `enabled` int(2) NOT NULL DEFAULT '0' COMMENT '0显示1不显示',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    pdo_query("	CREATE TABLE IF NOT EXISTS " . tablename("modules_newmean") . " (
    `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
    `uniacid` int(50) DEFAULT NULL,
    `hot` int(50) DEFAULT NULL,
    `name` varchar(500) DEFAULT NULL,
    `thumb` varchar(1000) DEFAULT NULL,
    `wurl` varchar(1000) DEFAULT NULL,
    `enabled` int(2) NOT NULL DEFAULT '0' COMMENT '0显示1不显示',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    if (!pdo_fieldexists("enjoy_city_firm", "rid")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_firm") . " ADD `rid` int(255) DEFAULT NULL;");
    }
    if (!pdo_fieldexists("enjoy_city_firm", "starnums")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_firm") . " ADD `starnums` int(50) NOT NULL DEFAULT '0';");
    }
    if (!pdo_fieldexists("enjoy_city_firm", "starscores")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_firm") . " ADD `starscores` int(50) NOT NULL DEFAULT '0';");
    }
    if (!pdo_fieldexists("enjoy_city_firm", "custom")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_firm") . " ADD `custom` varchar(200) NOT NULL;");
    }
    if (!pdo_fieldexists("enjoy_city_firm", "fax")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_firm") . " ADD `fax` text NOT NULL;");
    }
    pdo_query("alter table " . tablename("enjoy_city_firm") . " modify COLUMN fax text not null;");
    if (!pdo_fieldexists("enjoy_city_firm", "video1")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_firm") . " ADD `video1` text;");
    }
    if (!pdo_fieldexists("enjoy_city_firm", "video2")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_firm") . " ADD `video2` text;");
    }
    if (!pdo_fieldexists("enjoy_city_firm", "cflag")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_firm") . " ADD `cflag` int(2) DEFAULT NULL");
    }
    pdo_query("ALTER TABLE " . tablename("enjoy_city_firm") . " modify column `uid` int(255) NOT NULL DEFAULT '0';");
    if (!pdo_fieldexists("enjoy_city_reply", "onlinepay")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `onlinepay` int(2) NOT NULL DEFAULT '0';");
    }
    if (!pdo_fieldexists("enjoy_city_reply", "bonus")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `bonus` longtext;");
    }
    if (!pdo_fieldexists("enjoy_city_reply", "isright")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `isright` int(2) DEFAULT '0';");
    }
    if (!pdo_fieldexists("enjoy_city_reply", "kfewm")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `kfewm` longtext;");
    }
    if (!pdo_fieldexists("enjoy_city_reply", "issmple")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `issmple` int(2) NOT NULL DEFAULT '0';");
    }
    if (!pdo_fieldexists("enjoy_city_reply", "wtt")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `wtt` longtext;");
    }
    if (!pdo_fieldexists("enjoy_city_reply", "custurl")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `custurl` varchar(5000) DEFAULT NULL;");
    }
    if (!pdo_fieldexists("enjoy_city_reply", "custimg")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `custimg` longtext;");
    }
    if (!pdo_fieldexists("enjoy_city_reply", "custurl1")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `custurl1` varchar(2000) DEFAULT NULL;");
    }
    if (!pdo_fieldexists("enjoy_city_reply", "custimg1")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `custimg1` longtext;");
    }
    if (!pdo_fieldexists("enjoy_city_reply", "custurl2")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `custurl2` varchar(2000) DEFAULT NULL;");
    }
    if (!pdo_fieldexists("enjoy_city_reply", "custimg2")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `custimg2` longtext;");
    }
    if (!pdo_fieldexists("enjoy_city_reply", "isjob")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `isjob` int(2) NOT NULL DEFAULT '0';");
    }
    if (!pdo_fieldexists("enjoy_city_reply", "wstyle")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `wstyle` int(2) NOT NULL DEFAULT '0';");
    }
    if (!pdo_fieldexists("enjoy_city_reply", "weurl")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `weurl` text;");
    }
    if (!pdo_fieldexists("enjoy_city_reply", "ispayfirst")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_reply") . " ADD `ispayfirst` int(2) NOT NULL DEFAULT '0';");
    }
    if (!pdo_fieldexists("enjoy_city_kind", "headimg")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_kind") . " ADD `headimg` varchar(5000) DEFAULT NULL;");
    }
    if (!pdo_fieldexists("enjoy_city_kind", "footimg")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_kind") . " ADD `footimg` varchar(2000) DEFAULT NULL;");
    }
    if (!pdo_fieldexists("enjoy_city_kind", "headurl")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_kind") . " ADD `headurl` varchar(2000) DEFAULT NULL;");
    }
    if (!pdo_fieldexists("enjoy_city_kind", "footurl")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_kind") . " ADD `footurl` varchar(2000) DEFAULT NULL;");
    }
    if (!pdo_fieldexists("enjoy_city_firmfans", "favatar")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_firmfans") . " ADD `favatar` longtext;");
    }
    if (!pdo_fieldexists("enjoy_city_firmfans", "fnickname")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_firmfans") . " ADD `fnickname` varchar(500) DEFAULT NULL;");
    }
    if (!pdo_fieldexists("enjoy_city_firmfans", "flag")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_firmfans") . " ADD `flag` int(2) DEFAULT '0';");
    }
    if (!pdo_fieldexists("enjoy_city_firmfans", "starscore")) {
        pdo_query("ALTER TABLE " . tablename("enjoy_city_firmfans") . " ADD `starscore` int(2) DEFAULT NULL;");
    }
}
$isext = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_city_reply') . " where uniacid=0");
if ($isext < 1) {
    pdo_query("INSERT INTO " . tablename('enjoy_city_reply') . "(`uniacid`,`title`,`icon`,`ewm`,`slogo`,`sucai`,`share_title`,`share_icon`,`share_content`,`province`,`city`,
        `district`,`tel`,`copyright`,`agreement`,`weixin`,`createtime`,`fee`,`issq`,`mshare_title`,`mshare_icon`,
        `mshare_content`,`jointel`,`banner`,`onlinepay`,`bonus`,`kfewm`,`isright`)
        VALUES ('0', '微城市', 'images/2/2016/07/sW0BmyhK20LlDUKHLYd5kdhwWkEWhU.png', 'images/2/2016/05/Oy5v38vXDrr5453Ez4vR2x1r38Vr6v.jpg',
            './addons/enjoy_city/public/images/slogo.png',
            'http://mp.weixin.qq.com/s?__biz=MzIzMTA4Njg3OQ==&mid=400385515&idx=1&sn=3ae90c15d40e99e934fa796c230f2abf#rd',
            '#firm#强势入驻微城市', '#firmlogo#', '我向你推荐#firm#，快来看看吧', '江苏省', '南京市', '鼓楼区', '15250236258',
            'test', '&lt;p&gt;请仔细阅读本&ldquo;帐号使用协议&rdquo; （以下亦称&ldquo;本协议&rdquo;）条款，如你（亦称&ldquo;用户&rdquo;）阅读后通过点选本协议下方的&ldquo;同意&rdquo;按钮而自愿接受本协议的约束，本协议就构成你与微城市直接有约束力的法律文件。如果你是代表你的雇主或其他企业签订本协议，你在此陈述并保证；你已获得充分的授权签署本协议。&lt;/p&gt;\r\n&lt;p&gt;1.微城市服务&lt;/p&gt;\r\n&lt;p&gt;1.1 描述微城市服务的&ldquo;使用协议&rdquo;构成本&ldquo;帐号使用协议 &rdquo;的一部分，微城市服务包括在其网站上不时说明的服务，微城市保留权利随时对该等服务进行更改、更新或提高；&lt;/p&gt;\r\n&lt;p&gt;1.2 作为微城市服务的一部分，微城市针对不同的传播渠道为用户直接上载、管理、传播其内容提供服务，该等传播渠道包括但不限于：&lt;br /&gt; （i）用户自己拥有权利的网站；&lt;br /&gt; （ii）通过微城市的服务；或&lt;br /&gt; （iii）微城市提供的可以使用的其他传播渠道。&lt;/p&gt;\r\n&lt;p&gt;2.上传人义务&lt;/p&gt;\r\n&lt;p&gt;为使用微城市服务上载、传播内容，你必须已经仔细阅读并接受&ldquo;帐号使用协议&rdquo;和&ldquo;使用协议&rdquo;，并且已注册一个有效的、经合法授权的帐号。你对所有上载到微城市上的内容，无论是否由你本人或代表你所创作，负完全的法律责任。 你保证不在微城市上载、传播任何包含淫秽、色情、侵权、反动或其他非法内容的书籍及资料。你仅被允许通过微城市服务上载、传播你有充分的权利或授权进行传播的书籍及资料。 在使用微城市服务的过程中，你必须始终遵守&ldquo;帐号使用协议 &rdquo;和&ldquo;使用协议&rdquo;及其后续不时修订之版本。&lt;/p&gt;\r\n&lt;p&gt;3.用户注册&lt;/p&gt;\r\n&lt;p&gt;如果你使用微城市提供的网络存储空间进行视听节目的上载、传播服务，你需要注册一个帐号、密码，并确保用户身份的真实性、正确性及完整性，如果资料发生变化，你应及时更改。
在安全完成本服务的登记程序并收到微城市提供的一个帐号及初始密码后，你应自行维持密码及帐号的安全。你应对任何人利用你的帐号及密码所进行的活动负完全的责任，微城市无法对非法或未经你授权使用你帐号及密码的行为作出甄别，因此微城市对此不承担任何责任。在此，你同意并承诺做到：&lt;/p&gt;\r\n&lt;p&gt;3.1 当你的帐号或密码遭到未获授权的使用，或者发生其他任何安全问题时，你会立即有效通知到微城市；&lt;/p&gt;\r\n&lt;p&gt;3.2 当你每次上网或使用其他服务完毕后，会将有关帐号等安全退出；&lt;/p&gt;\r\n&lt;p&gt;3.3 用户同意接受微城市通过电子邮件、客户端、网页或其他合法方式向用户发送商品促销或其他相关商业信息。在使用电信增值服务的情况下，用户同意接受本公司及合作公司通过增值服务系统或其他方式向用户发送的相关服务信息或其他信息，其他信息包括但不限于通知信息、宣传信息、广告信息等；&lt;/p&gt;\r\n&lt;p&gt;3.4 你承诺不在注册、使用微城市提供的帐号从事下列行为： &lt;br /&gt; （i） 故意冒用他人信息为你注册微城市帐号； &lt;br /&gt; （ii） 未经他人合法授权以他人名义注册微城市帐号；或&lt;br /&gt; （iii） 使用侮辱、诽谤、色情等违反公序良俗的词语注册微城市帐号。&lt;/p&gt;\r\n&lt;p&gt;你在此同意，微城市有权根据自己的判定，对违反上述条款的用户拒绝提供帐号注册或取消该帐号的使用。&lt;/p&gt;\r\n&lt;p&gt;3.5 微城市保证，你提供给微城市的所有注册信息将根据隐私保护政策予以保密，但根据国家法律强制性要求予以披露的信息除外。&lt;/p&gt;\r\n&lt;p&gt;4.微城市上的内容&lt;/p&gt;\r\n&lt;p&gt;4.1 用户上传的作品和内容是指用户在微城市上载、传播的包括文字、图片、视频、音频或其它任何形式的内容和连接；&lt;/p&gt;\r\n&lt;p&gt;4.2 用户在微城市上传或发布原创作品及转载作品的，用户保证其对该等作品享有合法著作权/版权或者相应授权，并且用户同意授予微城市对所有上述作品和内容的在全球范围内的免费、不可撤销的、无限期的、并且可转让的非独家使用权许可，微城市有权展示、散布及推广上述作品；&lt;/p&gt;\r\n&lt;p&gt;4.3微城市在此郑重提请你注意，任何经由微城市的服务以上载、张贴、发送电子邮件或任何其它方式传送的资讯、资料、文字、软件、音乐、音讯、照片、图形、视讯、信息或其它资料（以下简称&ldquo;内容&rdquo;），无论系公开还是私下传送，均由内容提供者、上传者承担责任；&lt;/p&gt;\r\n&lt;p&gt;4.4 微城市无法预先知晓并合理控制经由微城市的服务传送之内容，亦无法知晓内容提供者、上传者的真实身份。有鉴于此，你已预知使用微城市的服务时，可能会接触到令人不快、不适当或令人厌恶之内容，并同意放弃由此而产生的针对微城市的任何追索权。微城市有权依法停止传输任何上述内容并采取相应行动，包括但不限于暂停你使用微城市的服务的全部或部分，保存有关记录，并向有关机关报告；&lt;/p&gt;\r\n&lt;p&gt;4.5 你需独立对自己在微城市上实施的行为承担法律责任。若你的行为不符合本协议，微城市有权在无需事先通知的情况下做出独立判断而立即取消你对帐号使用。你若在微城市上散布和传播反动、色情或其他违反国家法律的信息，微城市的系统记录有可能作为你违反法律的证据；及&lt;/p&gt;\r\n&lt;p&gt;4.6 因你进行上述作品和内容在微城市的上载、传播而导致任何第三方提出索赔要求或衍生的任何损害或损失，概与微城市无关，而由你承担全部责任。&lt;/p&gt;\r\n&lt;p&gt;5.第三方链接&lt;/p&gt;\r\n&lt;p&gt;为方便你使用，微城市服务可能会提供与第三方国际互联网网站或资源进行链接。除非另有声明，微城市无法对第三方网站服务进行控制，你因使用或依赖上述网站或资源所生的损失或损害，微城市不负担任何责任。&lt;/p&gt;\r\n&lt;p&gt;6.用户行为&lt;/p&gt;\r\n&lt;p&gt;6.1 你不得使用微城市提供的服务进行任何非法、淫秽、色情及其他违反公序良俗之活动，包括但不限于非法传销、诈骗、侵权及反动活动等，微城市有权依据自己的独立判断在不事先通知的情况下立即删除此类活动的相关内容、停止从事此类活动的帐号使用；&lt;/p&gt;\r\n&lt;p&gt;6.2 除你与微城市另有约定外，你同意微城市的服务仅供你个人非商业性质的使用，你不可对微城市服务的任何部分或对其使用及获得进行复制、拷贝、出售或利用微城市服务进行调查、广告或将微城市服务用于其他任何商业目的，但微城市对特定服务另有使用指引或规则的除外。&lt;/p&gt;\r\n&lt;p&gt;7.知识产权及其他权利&lt;/p&gt;\r\n&lt;p&gt;7.1 除你自行上载、传播的内容外，微城市提供的网络服务中，包括但不限于任何网页、文本、图片、图形、音频和/或视频资料、商标、服务标记、公司名称及版权等（&ldquo;知识产权&rdquo;）均归属微城市所有，或已由微城市经合法授权取得，上述知识产权受《中华人民共和国版权法》、《中华人民共和国版权商标法》和/或其它财产所有权法律的保护。未经微城市同意，上述资料均不得在任何媒体直接或间接发布、播放、出于播放或发布目的而改写或再发行，或者被你用于其他任何商业目的。上述知识产权的全部或任何部分仅可由你为私人用途而保存在某台计算机内。微城市不对因上述知识产权的产生、传送或递交其全部或部分的过程中产生的延误、不准确、错误和遗漏或从中产生或由此产生的任何损害赔偿向你或任何第三方负法律责任。&lt;/p&gt;\r\n&lt;p&gt;7.2 微城市为提供网络服务而使用的任何软件（包括但不限于软件中所含的任何图像、照片、动画、录像、录音、音乐、文字和附加程序及随附的帮助材料）的一切权利均属于该软件的著作权人，未经该软件的著作权人许可，你不得对该软件进行反向工程（reverse engineer）、反向编译（decompile）或反汇编（disassemble），或以其他方式发现原始编码，否则你应为此向该软件的著作权人承担相应的法律责任。&lt;/p&gt;\r\n&lt;p&gt;8.版权政策&lt;/p&gt;\r\n&lt;p&gt;微城市是根据用户指令提供作品上载、下载的信息网络存储空间，历来十分重视网络版权及其他知识产权的保护。为更好地保护网络版权及相关知识产权，微城市根据《中华人民共和国版权法》、《信息网络传播权保护条例》、《互联网著作权行政保护办法》及《互联网视听节目服务管理规定》等相关法律、法规的规定，制定本版权政策：&lt;/p&gt;\r\n&lt;p&gt;（1）微城市对网络版权保护尽合理、审慎的义务，在有理由确信有任何明显侵犯任何第三人版权的作品存在时，有权不事先通知随时删除该侵权作品；&lt;/p&gt;\r\n&lt;p&gt;（2）在接到符合法定要求的版权侵权通知时，迅速删除涉嫌侵权的作品；&lt;/p&gt;\r\n&lt;p&gt;（3）采取必要的技术措施，尽量防止相同侵权作品的再次上传；及&lt;/p&gt;\r\n&lt;p&gt;（4）对有证据证明反复上传侵权作品的用户随时停止提供网络存储空间的技术服务；&lt;/p&gt;\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n&lt;p&gt;如果您认为微城市用户通过微城市提供的信息存储空间上载、传播的书籍内容侵犯了您的信息网络传播权或删除、改变了您享有版权作品的权利管理电子信息，您可以在线或通过线下向微城市提交书面通知，要求删除该侵权作品，我们将会及时做出相应处理。&lt;/p&gt;\r\n&lt;p&gt;9.隐私权政策&lt;/p&gt;\r\n&lt;p&gt;9.1 当用户注册微城市的服务时，用户须向微城市提供个人信息。微城市收集你个人信息的目的是为用户提供尽可能多的个人化网上服务以及为广告商提供一个方便的途径适当地与用户接触，并且可以向用户发送具有相关性的内容和广告。在此过程中，广告商绝对不会接触到用户的个人信息。微城市不会在未经合法注册并使用微城市的用户授权时，公开、编辑或透露其个人信息及用户保存在微城市中的非公开内容，除非有下列情况：&lt;br /&gt; （i）有关法律规定或微城市合法服务程序规定； &lt;br /&gt; （ii）在紧急情况下，为维护用户及公众的权益； &lt;br /&gt; （iii）为维护微城市的商标权、专利权及其他任何合法权益；或 &lt;br /&gt; （iv）其他需要公开、编辑或透露用户个人信息的情况。&lt;/p&gt;\r\n&lt;p&gt;9.2 在包括但不限于以下几种情况下，微城市有权使用用户的个人信息：&lt;br /&gt; （i）在进行促销或抽奖时，微城市可能会与赞助商共享用户的个人信息，在这些情况下微城市会在发送用户信息之前进行提示，并且用户可以通过不参与而终止传送过程； &lt;br /&gt; （ii）微城市可以将用户信息与第三方数据匹配； &lt;br /&gt; （iii）微城市会通过透露合计用户统计数据，向未来的合作伙伴、广告商及其他第三方以及为了其他合法目的而描述微城市的服务；或 &lt;br /&gt; （iv）用户购买在微城市列出的商品或服务时，微城市获得的信息及用户提供的信息会提供给商家，这些商家会进行数据收集操作，但微城市对商家的此种操作不负任何责任。&lt;/p&gt;\r\n&lt;p&gt;10.免责声明&lt;/p&gt;\r\n&lt;p&gt;10.1 微城市对于任何自微城市而获得的信息、内容或者广告宣传等任何资讯（以下统称&ldquo;信息&rdquo;），不保证真实、准确和完整性。如果任何单位或者个人通过上述信息而进行任何行为，须自行甄别真伪和谨慎预防风险，否则，无论何种原因，微城市不对任何非与微城市直接发生的交易和/或行为承担任何直接、间接、附带或衍生的损失和责任。&lt;/p&gt;\r\n&lt;p&gt;10.2 微城市不对包括但不限于以下情形负责：&lt;br /&gt; （i）微城市完全适合用户的使用要求； &lt;br /&gt; （ii）微城市不受任何干扰、及时、安全、可靠且不出现任何错误；及&lt;br /&gt; （iii）用户经由微城市取得的任何产品、服务或其他材料符合用户的期望。&lt;/p&gt;\r\n&lt;p&gt;10.3 用户使用经由微城市下载的或取得的任何资料，其风险自行负担；因该等使用导致用户电脑系统损坏或资料流失，用户应负完全责任；&lt;/p&gt;\r\n&lt;p&gt;10.4 基于以下原因而造成的利润、商业信誉、资料损失或其他有形或无形损失，微城市不承担任何直接、间接的赔偿：&lt;br /&gt; （i）微城市使用或无法使用； &lt;br /&gt; （ii）经由微城市购买或取得的任何产品、资料或服务； &lt;br /&gt; （iii）用户资料遭到未经授权的使用或修改；或&lt;br /&gt; （iv）其他与微城市相关的事宜。&lt;/p&gt;\r\n&lt;p&gt;11.出口与国际使用&lt;/p&gt;\r\n&lt;p&gt;微城市通过中华人民共和国境内的设施提供和控制微城市的服务，微城市不担保其所提供或控制之服务在其他国家或地区是适当的、可行的，任何在其他司法管辖区使用微城市服务的用户应自行确保其遵守当地的法律、法规，微城市对此不负任何责任。&lt;/p&gt;\r\n&lt;p&gt;12.服务终止&lt;/p&gt;\r\n&lt;p&gt;12.1 用户同意微城市有权基于其自行之考虑，因任何理由，包含但不限于缺乏使用或微城市认为用户已经违反本协议的文字及精神，而终止用户的帐号或服务之全部或任何部分，并将用户在微城市的任何内容加以移除并删除；&lt;/p&gt;\r\n&lt;p&gt;12.2 用户同意依本协议提供之微城市的服务，无需事先向用户通知即可中断或终止，用户承认并同意，微城市可立即关闭或删除用户的帐号及用户帐号中所有相关信息及文件，及/或禁止继续使用前述文件或微城市的服务；&lt;/p&gt;\r\n&lt;p&gt;12.3 用户同意若微城市的服务使用被中断、终止或用户的帐号及相关信息和文件被关闭、删除，微城市对用户或任何第三人均不承担任何责任。&lt;/p&gt;\r\n&lt;p&gt;13.法律适用和管辖&lt;/p&gt;\r\n&lt;p&gt;13.1 本协议的生效、履行、解释及争议的解决均适用中华人民共和国法律。&lt;/p&gt;\r\n&lt;p&gt;13.2 如就本协议内容或其执行发生任何争议，应尽量友好协商解决；协商不成时，则争议各方均一致同意将争议提交上海仲裁委员会依据其现行仲裁规则进行仲裁解决。仲裁地点为上海，仲裁语言为中文。仲裁裁决为终局的，对各方均有法律约束力。&lt;/p&gt;\r\n&lt;p&gt;特别提示：你在进行注册之前，请确保你本人已经完全理解并接受本协议所有条款（尤其是免责条款），否则请不要注册。一旦你正式注册，则表明你已经完全理解并接受本协议所有条款，尤其是免责和责任限制条款。&lt;/p&gt;',
            'weixinhao', '1470135917', '200', '1', 'test无所不查，邀您入驻', 'images/2/2016/07/gaaA7aB93dF8Z6BbA9AZaSAbNu5ZuZ.png',
            '一个神奇的网站，快来入驻吧', '每日查询5000次，加盟联系：#tel#,微城市是手机上的114查号台，无所不查；无论您的店铺多偏僻，在微城市都是黄金旺铺；加入微城市，50万粉丝等着你；无论你想找什么，微城市都会给你答案；大到一座城，小到一根针，在微城市都能找到；微城市，为老百姓和商家之间架起一座桥梁。', './addons/enjoy_city/public/images/banner.jpg', '1', 'http://bbs.we7.cc/thread-17510-1-1.html',
            'images/2/2016/07/QtMIxD55M10uEMIzq3RI2u0IvQ1350.jpg', '1')");
}