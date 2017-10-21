#多公众号接入系统

    wxauth	menu		接入平台	MpList
    wxauth	menu		使用统计	static
    wxauth	menu		使用帮助	help
    wxauth	rule		添加公众号	MpAdd		0
                        编辑公众号   MpEdit
                        接入公众号   MpJoin
                        添加平台    AppAdd
                        管理平台     AppList
                        编辑平台    AppEdit
    wxauth	function	中转	D		1
###Sql
####安装脚本
    CREATE TABLE IF NOT EXISTS `ims_mp_app` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(20) NOT NULL,
      `m_id` int(11) unsigned NOT NULL DEFAULT '0',
      `status` tinyint(1) NOT NULL DEFAULT '1',
      `is_delete` tinyint(1) unsigned NOT NULL,
      `create_time` int(10) unsigned NOT NULL,
      `token` char(32) NOT NULL,
      `encodingaeskey` char(43) NOT NULL,
      `url` varchar(200) NOT NULL,
      `sort` int(11) unsigned NOT NULL DEFAULT '50',
      `desc` varchar(100) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
    
     CREATE TABLE IF NOT EXISTS `ims_mp_list` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `w_id` int(10) unsigned NOT NULL,
      `name` varchar(20) NOT NULL,
      `appid` char(18) NOT NULL,
      `appsecret` char(32) NOT NULL,
      `token` char(32) NOT NULL,
      `encodingaeskey` char(43) NOT NULL,
      `is_yz` tinyint(1) unsigned NOT NULL DEFAULT '0',
      `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1',
      `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0',
      `create_time` int(10) unsigned NOT NULL,
      `desc` varchar(100) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
    
    CREATE TABLE IF NOT EXISTS `ims_mp_log` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `m_id` int(10) unsigned NOT NULL,
      `a_id` int(10) unsigned NOT NULL,
      `from_data` varchar(10000) NOT NULL,
      `send_data` varchar(10000) NOT NULL,
      `time` int(10) unsigned NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
    
####卸载脚本

    DROP TABLE IF EXISTS `ims_mp_list`;
    DROP TABLE IF EXISTS `ims_mp_app`;
    DROP TABLE IF EXISTS `ims_mp_log`;
    
####升级脚本
    
###公众号列表表(mp_list)
字段名	|	类型		|	长度		|	Null	|	索引		|	默认值	|	说明		|
--------| --------- | ----------| ---------	| ---------	| --------- | --------- |
id		|	int		|	11		|	否		|	主键索引	|			|	主键(unsigned)		|
w_id    |   int     |   11      |   否      |           |           |    公众号id   |
name    |   varchar |   20      |   否      |           |           |    公众号名称 |
appid   |   char    |   18      |   否      |           |           |    公众号Appid|
appsecret|   char   |   32      |   否      |           |           |    公众号AppSecret    |
token   |   char    |   32      |   否      |           |           |    公众号token|
encodingaeskey|   char   |   43      |   否      |           |           |    公众号EncodingAESKey    |
is_yz   |   tinyint |   1       |   否      |           |    0       |     是否验证   |
status  |   tinyint |   1       |   否      |           |    1       |     是否状态   |
is_delete|   tinyint |   1       |   否      |           |    0       |     是否删除   |
create_time| int     |   11      |   否     |            |            |     创建时间  |
desc    |   varchar  |  100     |    否     |           |            |      备注   |


    CREATE TABLE `w_mp_list` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `w_id` int(10) unsigned NOT NULL,
      `name` varchar(20) NOT NULL,
      `appid` char(18) NOT NULL,
      `appsecret` char(32) NOT NULL,
      `token` char(32) NOT NULL,
      `encodingaeskey` char(43) NOT NULL,
      `is_yz` tinyint(1) unsigned NOT NULL DEFAULT '0',
      `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1',
      `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0',
      `create_time` int(10) unsigned NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=myisam AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;




###接入系统表(mp_app)
字段名	|	类型		|	长度		|	Null	|	索引		|	默认值	|	说明		|
--------| --------- | ----------| ---------	| ---------	| --------- | --------- |
id		|	int		|	11		|	否		|	主键索引	|			|	主键(unsigned)		|
name    |   varchar |   20      |   否      |           |           |    应用名称 |
m_id    |   int     |   11      |   否      |           |           |    公众号id |
url     |   varchar |   200     |   否      |           |           |    应用Url  |
token   |   char    |   32      |   否      |           |           |    公众号token|
encodingaeskey|   char   |   43      |   否      |           |           |    公众号EncodingAESKey    |
status  |   tinyint    |   1      |   否      |           |     1      |    状态     |
is_delete|   tinyint |   1       |   否      |           |    0       |     是否删除   |
create_time| int     |   11      |   否     |            |            |     创建时间  |
sort    |    int    |    11     |    否      |           |   50        |       排序   |
desc    |   varchar  |  100     |    否     |           |            |      备注   |

    CREATE TABLE `w_mp_app` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(20) NOT NULL,
      `m_id` int(11) NOT NULL,
      `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
      `is_delete` tinyint(1) unsigned NOT NULL,
      `create_time` int(10) unsigned NOT NULL,
      `token` char(32) NOT NULL,
      `encodingaeskey` char(43) NOT NULL,
      `url` varchar(200) NOT NULL,
      `sort` int(11) unsigned NOT NULL DEFAULT '50',
      PRIMARY KEY (`id`)
    ) ENGINE=n=myisam AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



###接入日志表(mp_log)
字段名	|	类型		|	长度		|	Null	|	索引		|	默认值	|	说明		|
--------| --------- | ----------| ---------	| ---------	| --------- | --------- |
id		|	int		|	11		|	否		|	主键索引	|			|	主键(unsigned)		|
m_id    |   int     |   11      |   否      |           |           |    公众号id |
a_id    |   int     |   11      |   否      |           |           |    应用id |
from_data|  varchar |   10000   |   否      |           |           |    接收数据|
send_data|  varchar |   10000   |   否      |           |           |    发送数据|
time    | int     |   11      |   否     |            |             |     时间  |

    CREATE TABLE `w_mp_log` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `m_id` int(10) unsigned NOT NULL,
      `a_id` int(10) unsigned NOT NULL,
      `from_data` varchar(10000) NOT NULL,
      `send_data` varchar(10000) NOT NULL,
      `time` int(10) unsigned NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=myisam AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
    

###统计记录表