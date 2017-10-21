<?php
//		echo "create_tables";
		if ($tablestr="msyou_meituzone_paraset"||$tablestr=''){
			//系统参数
			if (!pdo_tableexists('msyou_meituzone_paraset')){
//				echo 'msyou_meituzone_paraset 0'; 
				$sqlstr="CREATE TABLE ".tablename('msyou_meituzone_paraset')." (
					 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					 `uniacid` int(11) unsigned NOT NULL,
					 `test` text NOT NULL DEFAULT '',
					 `filterkeyword` text NOT NULL DEFAULT '',
					 `resroot` text NOT NULL DEFAULT '',

					 `creater` int(11) unsigned NOT NULL,
					 `createtime` int(11) unsigned NOT NULL,
					 `editer` int(11) unsigned NOT NULL,
					 `edittime` int(11) unsigned NOT NULL,
					 PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
				pdo_run($sqlstr);
			}else{
				if (!pdo_fieldexists('msyou_meituzone_paraset','resroot')){
//					echo 'msyou_meituzone_paraset createid'; 
					$sqlstr="alter table ".tablename('msyou_meituzone_paraset')." add `resroot` text NOT NULL DEFAULT '';";
	  				pdo_run($sqlstr);
	  			}
			}
		}

		if ($tablestr="msyou_meituzone_reply"||$tablestr=''){
			//规则回复
			if (!pdo_tableexists('msyou_meituzone_reply')){
//				echo 'msyou_meituzone_reply'; 
				$sqlstr="CREATE TABLE ".tablename('msyou_meituzone_reply')." (
					 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					 `uniacid` int(11) unsigned NOT NULL,
					 `rid` int(11) unsigned NOT NULL,
					
					 `title` varchar(100) NOT NULL DEFAULT '',
					 `detail` text NOT NULL DEFAULT '',

					 `thumburl` text NOT NULL DEFAULT '',
					 `loadimgurl` text NOT NULL DEFAULT '',
					 `bgurl` text NOT NULL DEFAULT '',
					 `topurl` text NOT NULL DEFAULT '',

					 `content` text NOT NULL DEFAULT '',
					 `starttime` int(11) unsigned NOT NULL,
					 `endtime` int(11) unsigned NOT NULL,
				     `zanx` float NOT NULL DEFAULT 0,
					 `sharex` float NOT NULL DEFAULT 0,
					 `viewx` float NOT NULL DEFAULT 0,

					 `crinfo` text NOT NULL DEFAULT '',
					`gzjoin` int(1) NOT NULL DEFAULT '0' ,
					`gzzan` int(1) NOT NULL DEFAULT '0' ,
					`followurl` text NOT NULL DEFAULT '',

 					`status` int(1) NOT NULL DEFAULT '0' ,
  					`joincount` int(11) NOT NULL DEFAULT 0,
  					`zancount` int(11) NOT NULL DEFAULT 0,
  					`sharecount` int(11) NOT NULL DEFAULT 0,
  					`viewcount` int(11) NOT NULL DEFAULT 0,
					
					
					`musicurl` text NOT NULL DEFAULT '',
					`indexshownick` int(1) NOT NULL DEFAULT 0,
					`indexshowcontent` int(1) NOT NULL DEFAULT 0,
					`coverurl` text NOT NULL DEFAULT '',
					`justpai` int(1) NOT NULL DEFAULT 0,
					`justpinglun` int(1) NOT NULL DEFAULT 0,
					`maxaddimg` int(3) NOT NULL DEFAULT 0,
					`indexshowzan` int(1) NOT NULL DEFAULT 0,
					`pagesize` int(2) NOT NULL DEFAULT 0,
					
					
					 `creater` int(11) unsigned NOT NULL,
					 `createtime` int(11) unsigned NOT NULL,
					 `editer` int(11) unsigned NOT NULL,
					 `edittime` int(11) unsigned NOT NULL,
					 PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
				pdo_run($sqlstr);
			}else{
				if (!pdo_fieldexists('msyou_meituzone_reply','sharecount')){
					$sqlstr="alter table ".tablename('msyou_meituzone_reply')." add `sharecount` int(11) NOT NULL DEFAULT 0;";
	  				pdo_run($sqlstr);
	  			}
				if (!pdo_fieldexists('msyou_meituzone_reply','zancount')){
					$sqlstr="alter table ".tablename('msyou_meituzone_reply')." add `zancount` int(11) NOT NULL DEFAULT 0;";
	  				pdo_run($sqlstr);
	  			}
				if (!pdo_fieldexists('msyou_meituzone_reply','topurl')){
					$sqlstr="alter table ".tablename('msyou_meituzone_reply')." add `topurl` text NOT NULL DEFAULT '';";
	  				pdo_run($sqlstr);
	  			}
				if (!pdo_fieldexists('msyou_meituzone_reply','musicurl')){
					$sqlstr="alter table ".tablename('msyou_meituzone_reply')." add `musicurl` text NOT NULL DEFAULT '';";
	  				pdo_run($sqlstr);
	  			}
				if (!pdo_fieldexists('msyou_meituzone_reply','indexshownick')){
					$sqlstr="alter table ".tablename('msyou_meituzone_reply')." add `indexshownick` int(1) NOT NULL DEFAULT 0;";
	  				pdo_run($sqlstr);
	  			}
				if (!pdo_fieldexists('msyou_meituzone_reply','indexshowcontent')){
					$sqlstr="alter table ".tablename('msyou_meituzone_reply')." add `indexshowcontent` int(1) NOT NULL DEFAULT 0;";
	  				pdo_run($sqlstr);
	  			}
				if (!pdo_fieldexists('msyou_meituzone_reply','coverurl')){
					$sqlstr="alter table ".tablename('msyou_meituzone_reply')." add `coverurl` text NOT NULL DEFAULT '';";
	  				pdo_run($sqlstr);
	  			}
				if (!pdo_fieldexists('msyou_meituzone_reply','justpai')){
					$sqlstr="alter table ".tablename('msyou_meituzone_reply')." add `justpai` int(1) NOT NULL DEFAULT 0;";
	  				pdo_run($sqlstr);
	  			}
				if (!pdo_fieldexists('msyou_meituzone_reply','justpinglun')){
					$sqlstr="alter table ".tablename('msyou_meituzone_reply')." add `justpinglun` int(1) NOT NULL DEFAULT 0;";
	  				pdo_run($sqlstr);
	  			}
				if (!pdo_fieldexists('msyou_meituzone_reply','followurl')){
					$sqlstr="alter table ".tablename('msyou_meituzone_reply')." add `followurl` text NOT NULL DEFAULT '';";
	  				pdo_run($sqlstr);
	  			}
				if (!pdo_fieldexists('msyou_meituzone_reply','maxaddimg')){
					$sqlstr="alter table ".tablename('msyou_meituzone_reply')." add `maxaddimg` int(3) NOT NULL DEFAULT 0;";
	  				pdo_run($sqlstr);
	  			}
				if (!pdo_fieldexists('msyou_meituzone_reply','indexshowzan')){
					$sqlstr="alter table ".tablename('msyou_meituzone_reply')." add `indexshowzan` int(1) NOT NULL DEFAULT 0;";
	  				pdo_run($sqlstr);
	  			}
				if (!pdo_fieldexists('msyou_meituzone_reply','pagesize')){
					$sqlstr="alter table ".tablename('msyou_meituzone_reply')." add `pagesize` int(2) NOT NULL DEFAULT 0;";
	  				pdo_run($sqlstr);
	  			}
	  			
			}
		}

		if ($tablestr="msyou_meituzone_lists"||$stablestr=''){
			//模式
			if (!pdo_tableexists('msyou_meituzone_lists')){
//				echo 'msyou_meituzone_lists'; 
				$sqlstr="CREATE TABLE ".tablename('msyou_meituzone_lists')." (
					 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					 `uniacid` int(11) unsigned NOT NULL,
					 `rid` int(11) unsigned NOT NULL,
					 `fanid` int(11) unsigned NOT NULL,
					 `bh` int(11) NOT NULL default 0,
					 `imgurl` text NOT NULL DEFAULT '',
					 `content` text NOT NULL DEFAULT '',

  					`zancount` int(11) NOT NULL DEFAULT 0,
  					`sharecount` int(11) NOT NULL DEFAULT 0,
  					`viewcount` int(11) NOT NULL DEFAULT 0,
					
					`jiang` int(1) NOT NULL DEFAULT 0,
					
					 `creater` int(11) unsigned NOT NULL,
					 `createtime` int(11) unsigned NOT NULL,
					 `editer` int(11) unsigned NOT NULL,
					 `edittime` int(11) unsigned NOT NULL,
					 PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
				pdo_run($sqlstr);
			}else{
				if (!pdo_fieldexists('msyou_meituzone_lists','bh')){
//					echo 'msyou_meituzone_lists createid'; 
					$sqlstr="alter table ".tablename('msyou_meituzone_lists')." add `bh` int(11) NOT NULL DEFAULT 0;";
	  				pdo_run($sqlstr);
	  			}
			}
		}

		if ($tablestr="msyou_meituzone_lists_log"||$stablestr=''){
			//模式
			if (!pdo_tableexists('msyou_meituzone_lists_log')){
//				echo 'msyou_meituzone_lists'; 
				$sqlstr="CREATE TABLE ".tablename('msyou_meituzone_lists_log')." (
					 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					 `uniacid` int(11) unsigned NOT NULL,
					 `rid` int(11) unsigned NOT NULL,
					 `listsid` int(11) unsigned NOT NULL,
					 `uid` int(11) unsigned NOT NULL,
  					`zancount` int(11) NOT NULL DEFAULT 0,
					 `createtime` int(11) unsigned NOT NULL,
					 PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
				pdo_run($sqlstr);
			}else{
/*
				if (!pdo_fieldexists('msyou_meituzone_lists','xh')){
//					echo 'msyou_meituzone_lists createid'; 
					$sqlstr="alter table ".tablename('msyou_meituzone_lists')." add `xh` int(11) NOT NULL DEFAULT 0;";
	  				pdo_run($sqlstr);
	  			}
*/
			}
		}


