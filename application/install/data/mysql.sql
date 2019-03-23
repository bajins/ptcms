
DROP TABLE IF EXISTS `ptcms_ad`;

CREATE TABLE `ptcms_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `key` varchar(50) NOT NULL DEFAULT '',
  `width` smallint(6) DEFAULT '0',
  `height` smallint(6) DEFAULT '0',
  `code` text,
  `intro` varchar(255) NOT NULL DEFAULT '',
  `create_user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建人',
  `update_user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改人',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `type` tinyint(3) DEFAULT '1' COMMENT '广告类型 1 html 2 js',
  `status` tinyint(3) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `ptcms_ad` WRITE;
/*!40000 ALTER TABLE `ptcms_ad` DISABLE KEYS */;

INSERT INTO `ptcms_ad` (`id`, `name`, `key`, `width`, `height`, `code`, `intro`, `create_user_id`, `update_user_id`, `create_time`, `update_time`, `type`, `status`)
VALUES
	(2,'网站统计','tongji',0,0,'网站统计示例广告代码','',1,1,1412900092,1412929570,1,1);

/*!40000 ALTER TABLE `ptcms_ad` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ptcms_admin_auth


DROP TABLE IF EXISTS `ptcms_admin_auth`;

CREATE TABLE `ptcms_admin_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `node_id` varchar(255) DEFAULT NULL,
  `ordernum` smallint(5) unsigned DEFAULT NULL,
  `create_user_id` int(11) unsigned DEFAULT '0' COMMENT '创建人',
  `update_user_id` int(11) unsigned DEFAULT '0' COMMENT '修改人',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(3) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8;



# Dump of table ptcms_admin_group


DROP TABLE IF EXISTS `ptcms_admin_group`;

CREATE TABLE `ptcms_admin_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL DEFAULT '',
  `intro` varchar(255) NOT NULL DEFAULT '',
  `node` text NOT NULL,
  `create_user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建人',
  `update_user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改人',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 COMMENT='用户组信息表';

LOCK TABLES `ptcms_admin_group` WRITE;
/*!40000 ALTER TABLE `ptcms_admin_group` DISABLE KEYS */;

INSERT INTO `ptcms_admin_group` (`id`, `name`, `intro`, `node`, `create_user_id`, `update_user_id`, `create_time`, `update_time`)
VALUES
	(1,'超级管理员','拥有所有权限','4,5,15,8,9,10,11,12,14,17,19,20,21,18,22,23,24,25,3,1,6,7,13,2,16',1,1,1412777739,1412778653);

/*!40000 ALTER TABLE `ptcms_admin_group` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ptcms_admin_node


DROP TABLE IF EXISTS `ptcms_admin_node`;

CREATE TABLE `ptcms_admin_node` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `module` varchar(20) DEFAULT NULL,
  `controller` varchar(50) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `ordernum` smallint(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_user_id` int(11) unsigned DEFAULT '0' COMMENT '创建人',
  `update_user_id` int(11) unsigned DEFAULT '0' COMMENT '修改人',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

LOCK TABLES `ptcms_admin_node` WRITE;
/*!40000 ALTER TABLE `ptcms_admin_node` DISABLE KEYS */;

INSERT INTO `ptcms_admin_node` (`id`, `name`, `pid`, `module`, `controller`, `action`, `ordernum`, `status`, `create_user_id`, `update_user_id`, `create_time`, `update_time`)
VALUES
	(1,'常用',0,'','','',10,1,1,1,1412696793,1453886746),
	(2,'系统',0,'','','',20,1,1,1,1412696797,1453886746),
	(3,'系统概况',1,'','','',50,1,1,1,1412699544,1453886746),
	(4,'欢迎界面',3,'admin','index','welcome',10,1,1,1,1412699598,1453886746),
	(5,'系统探针',3,'admin','index','system',20,1,1,1,1412699662,1453886746),
	(6,'常用功能',1,'','','',50,1,1,1,1412699681,1453886746),
	(7,'开发功能',1,'','','',50,1,1,1,1412699705,1453886746),
	(8,'权限节点',7,'admin','node','index',50,1,1,1,1412699737,1453886746),
	(9,'添加节点',8,'admin','node','add',50,1,1,1,1412699752,1453886746),
	(10,'修改节点',8,'admin','node','edit',50,1,1,1,1412699787,1453886746),
	(11,'删除节点',8,'admin','node','del',50,1,1,1,1412699817,1453886746),
	(12,'批量操作',8,'admin','node','multi',50,1,1,1,1412700038,1453886746),
	(13,'系统设置',2,'','','',50,1,1,1,1412701712,1453886746),
	(14,'基本设置',13,'admin','config','set',50,1,1,1,1412701727,1453886746),
	(15,'添加菜单',6,'','','',50,1,1,1,1412701869,1453886746),
	(16,'管理员设置',2,'','','',50,1,1,1,1412749506,1453886746),
	(17,'用户管理',16,'admin','user','index',50,1,1,1,1412749542,1453886746),
	(18,'用户组管理',16,'admin','group','index',50,1,1,1,1412749571,1453886746),
	(19,'添加用户',17,'admin','user','add',50,1,1,1,1412749589,1453886746),
	(20,'修改用户',17,'admin','user','edit',50,1,1,1,1412749624,1453886746),
	(21,'删除用户',17,'admin','user','del',50,1,1,1,1412749640,1453886746),
	(22,'添加用户组',18,'admin','group','add',50,1,1,1,1412749659,1453886746),
	(23,'修改用户组',18,'admin','group','edit',50,1,1,1,1412749668,1453886746),
	(24,'删除用户组',18,'admin','group','del',50,1,1,1,1412749694,1453886746),
	(25,'批量操作',18,'admin','group','multi',50,1,1,1,1412749710,1453886746),
	(26,'模版设置',13,'admin','theme','index',50,1,1,1,1412826472,1453886746),
	(27,'设置默认模版',26,'admin','theme','set',50,1,1,1,1412826981,1453886746),
	(28,'模版参数',26,'admin','theme','config',50,1,1,1,1412826993,1453886746),
	(29,'删除模版参数',26,'admin','theme','del',50,1,1,1,1412827010,1453886746),
	(30,'扩展',0,'','','',50,1,1,1,1412833775,1453886746),
	(33,'内容',0,'','','',30,1,1,1,1412833875,1453886746),
	(34,'用户',0,'','','',40,1,1,1,1412833879,1453886746),
	(35,'配置管理',7,'admin','config','index',50,1,1,1,1412844601,1453886746),
	(36,'添加配置',35,'admin','config','add',50,1,1,1,1412844636,1453886746),
	(37,'修改配置',35,'admin','config','edit',50,1,1,1,1412844648,1453886746),
	(38,'删除配置',35,'admin','config','del',50,1,1,1,1412844662,1453886746),
	(39,'批量操作',35,'admin','config','multi',50,1,1,1,1412844696,1453886746),
	(41,'模块管理',30,'','','',50,1,1,1,1412907367,1453886746),
	(42,'模块管理',41,'admin','module','index',50,1,1,1,1412907391,1453886746),
	(44,'友情链接',41,'friendlink','manage','index',50,1,1,1,1412907416,1453886746),
	(50,'添加链接',44,'friendlink','manage','add',50,1,1,1,1412907850,1453886746),
	(51,'修改链接',44,'friendlink','manage','edit',50,1,1,1,1412907873,1453886746),
	(52,'删除链接',44,'friendlink','manage','del',50,1,1,1,1412907887,1453886746),
	(53,'批量操作',44,'friendlink','manage','multi',50,1,1,1,1412907901,1453886746),
	(55,'安装模块',42,'admin','module','install',50,1,1,1,1413198337,1453886746),
	(56,'卸载模块',42,'admin','module','uninstall',50,1,1,1,1413198357,1453886746),
	(57,'批量操作',42,'admin','module','multi',50,1,1,1,1413198373,1453886746),
	(97,'升级模块',42,'admin','module','upgrade',50,1,1,1,1413210994,1453886746),
	(170,'插件管理',30,'','','',50,1,1,1,1413280901,1453886746),
	(171,'插件管理',170,'admin','plugin','index',50,1,1,1,1413280920,1453886746),
	(172,'安装插件',171,'admin','plugin','install',50,1,1,1,1413280941,1453886746),
	(173,'卸载插件',171,'admin','plugin','uninstall',50,1,1,1,1413280953,1453886746),
	(174,'升级插件',171,'admin','plugin','upgrade',50,1,1,1,1413280967,1453886746),
	(181,'广告管理',41,'ad','manage','index',50,1,1,1,1413282868,1453886746),
	(182,'添加广告',181,'ad','manage','add',50,1,1,1,1413282868,1453886746),
	(183,'修改广告',181,'ad','manage','edit',50,1,1,1,1413282868,1453886746),
	(184,'删除广告',181,'ad','manage','del',50,1,1,1,1413282868,1453886746),
	(185,'预览广告',181,'ad','manage','show',50,1,1,1,1413282868,1453886746),
	(186,'批量操作',181,'ad','manage','multi',50,1,1,1,1413282868,1453886746),
	(187,'插件配置',171,'admin','plugin','config',50,1,1,1,1413329920,1453886746),
	(194,'分类管理',13,'admin','category','index',50,1,1,1,1413357272,1453886746),
	(195,'添加分类',194,'admin','category','add',50,1,1,1,1413357307,1453886746),
	(196,'修改分类',194,'admin','category','edit',50,1,1,1,1413357315,1453886746),
	(197,'删除分类',194,'admin','category','del',50,1,1,1,1413357324,1453886746),
	(198,'批量操作',194,'admin','category','multi',50,1,1,1,1413357334,1453886746),
	(199,'单页管理',33,'','','',100,1,1,1,1413358631,1453886746),
	(200,'单页管理',199,'page','manage','index',100,1,1,1,1413358656,1453886746),
	(201,'添加单页',200,'page','manage','add',50,1,1,1,1413359013,1453886746),
	(202,'修改单页',200,'page','manage','edit',50,1,1,1,1413359022,1453886746),
	(203,'删除单页',200,'page','manage','del',50,1,1,1,1413359030,1453886746),
	(204,'批量操作',200,'page','manage','multi',50,1,1,1,1413359043,1453886746),
	(227,'网站图片',13,'admin','set','pic',50,1,1,1,1413450099,1453886746),
	(228,'书籍管理',33,'','','',50,1,1,1,1414049517,1453886746),
	(229,'站点管理',33,'','','',50,1,1,1,1414049524,1453886746),
	(230,'规则管理',33,'','','',50,1,1,1,1414049542,1453886746),
	(231,'小说管理',228,'novelsearch','manage','index',30,1,1,1,1414049578,1453886746),
	(232,'修改小说',231,'novelsearch','manage','edit',50,1,1,1,1414049665,1453886746),
	(233,'批量操作',231,'novelsearch','manage','mulit',50,1,1,1,1414049683,1453886746),
	(234,'删除小说',231,'novelsearch','manage','del',50,1,1,1,1414049715,1453886746),
	(236,'采集规则',230,'rule','collect','index',50,1,1,1,1414049905,1453886746),
	(237,'站点管理',229,'novelsearch','site','index',50,1,1,1,1414049923,1453886746),
	(238,'添加站点',237,'novelsearch','site','add',50,1,1,1,1414050048,1453886746),
	(239,'修改站点',237,'novelsearch','site','edit',50,1,1,1,1414050060,1453886746),
	(240,'删除站点',237,'novelsearch','site','del',50,1,1,1,1414050373,1453886746),
	(241,'批量操作',237,'novelsearch','site','mulit',50,1,1,1,1414050435,1453886746),
	(242,'添加规则',236,'rule','collect','add',50,1,1,1,1414050514,1453886746),
	(243,'修改规则',236,'rule','collect','edit',50,1,1,1,1414050524,1453886746),
	(244,'删除规则',236,'rule','collect','del',50,1,1,1,1414050533,1453886746),
	(245,'批量操作',236,'rule','collect','mulit',50,1,1,1,1414050544,1453886746),
	(257,'附件管理',41,'attachment','manage','index',50,1,1,1,1414051757,1453886746),
	(258,'删除附件',257,'attachment','manage','del',50,1,1,1,1414051757,1453886746),
	(259,'批量操作',257,'attachment','manage','multi',50,1,1,1,1414051757,1453886746),
	(272,'用户管理',34,'','','',50,1,1,1,1414052578,1453886746),
	(273,'用户列表',272,'user','manage','index',50,1,1,1,1414052616,1453886746),
	(274,'添加用户',273,'user','manage','add',50,1,1,1,1414052675,1453886746),
	(275,'修改用户',273,'user','manage','edit',50,1,1,1,1414052683,1453886746),
	(276,'删除用户',273,'user','manage','del',50,1,1,1,1414052692,1453886746),
	(277,'批量操作',273,'user','manage','multi',50,1,1,1,1414052700,1453886746),
	(284,'任务管理',30,'','','',50,1,1,1,1414853606,1453886746),
	(285,'任务管理',284,'cron','manage','index',50,1,1,1,1414853606,1453886746),
	(286,'添加任务',285,'cron','manage','add',50,1,1,1,1414853606,1453886746),
	(287,'修改任务',285,'cron','manage','edit',50,1,1,1,1414853606,1453886746),
	(288,'删除任务',285,'cron','manage','del',50,1,1,1,1414853606,1453886746),
	(289,'批量操作',285,'cron','manage','multi',50,1,1,1,1414853606,1453886746),
	(290,'运行监控',284,'cron','monitor','index',50,1,1,1,1414853606,1453886746),
	(291,'TKD设置',13,'admin','set','tkd',50,1,1,1,1414855028,1453886746),
	(292,'链接设置',13,'admin','set','url',50,1,1,1,1414881709,1453886746),
	(293,'采集设置',13,'admin','set','collect',50,1,1,1,1414881722,1453886746),
	(294,'运行采集',236,'rule','collect','run',50,1,1,1,1414913665,1453886746),
	(295,'测试规则',236,'rule','collect','test',50,1,1,1,1414913677,1453886746),
	(296,'网站地图',41,'sitemap','manage','index',50,1,1,1,1415361253,1453886746),
	(297,'模块设置',42,'admin','module','config',50,1,1,1,1415361437,1453886746),
	(305,'站点日统计',229,'novelsearch','site','daystat',100,1,1,1,1440832828,1453886746),
	(306,'站点月统计',229,'novelsearch','site','monthstat',110,1,1,1,1440832839,1453886746),
	(307,'章节沉降',228,'novelsearch','manage','chapterban',50,1,1,1,1440860092,1453886746),
	(308,'采集管理',33,'','','',50,1,1,1,1446943186,1453886746),
	(309,'列表采集',308,'rule','collect','list',50,1,1,1,1446943523,1453886746),
	(310,'书号采集',308,'rule','collect','id',50,1,1,1,1446943675,1453886746),
	(311,'信息更新',308,'rule','collect','updateinfo',50,1,1,1,1446943712,1453886746),
	(312,'内容重采',308,'rule','collect','re',50,1,1,1,1446943746,1453886746),
	(313,'ajax',236,'rule','collect','ajax',50,1,1,1,1447139897,1453886746),
	(314,'单页采集',308,'rule','collect','run',40,1,1,1,1447228147,1453886746),
	(315,'规则导入',230,'rule','manage','import',50,1,1,1,1447852842,1453886746),
	(316,'规则导出',230,'rule','manage','export',50,1,1,1,1447852869,1453886746),
	(317,'清空站点',229,'novelsearch','site','clear',50,1,1,1,1447857310,1453886746),
	(318,'章节重排',308,'rule','collect','reorder',50,1,1,1,1448521976,1453886746),
	(319,'清空内容',228,'novelsearch','manage','clearall',50,1,1,1,1451360948,1453886746),
	(321,'站点采集数据',229,'novelsearch','site','stat',50,1,1,0,1455525649,0);

/*!40000 ALTER TABLE `ptcms_admin_node` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ptcms_admin_user


DROP TABLE IF EXISTS `ptcms_admin_user`;

CREATE TABLE `ptcms_admin_user` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `group_id` smallint(5) DEFAULT '0' COMMENT '用户组',
  `intro` varchar(255) NOT NULL DEFAULT '',
  `create_user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建人',
  `update_user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改人',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `login_num` int(11) unsigned DEFAULT '0' COMMENT '登录次数',
  `login_ip` varchar(15) DEFAULT NULL,
  `login_time` int(11) unsigned DEFAULT '0' COMMENT '最后登录时间',
  `status` tinyint(3) unsigned DEFAULT '1' COMMENT '用户状态 1正常 0未审核',
  PRIMARY KEY (`id`),
  UNIQUE KEY `passport_id` (`user_id`),
  KEY `group_id` (`group_id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='用户信息表';

LOCK TABLES `ptcms_admin_user` WRITE;
/*!40000 ALTER TABLE `ptcms_admin_user` DISABLE KEYS */;

INSERT INTO `ptcms_admin_user` (`id`, `user_id`, `group_id`, `intro`, `create_user_id`, `update_user_id`, `create_time`, `update_time`, `login_num`, `login_ip`, `login_time`, `status`)
VALUES
	(1,1,1,'默认管理员帐号',1,1,1411978787,1412932876,177,'127.0.0.1',1456644633,1);

/*!40000 ALTER TABLE `ptcms_admin_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ptcms_attachment


DROP TABLE IF EXISTS `ptcms_attachment`;

CREATE TABLE `ptcms_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `size` int(11) NOT NULL,
  `ext` varchar(10) NOT NULL,
  `url` varchar(200) NOT NULL,
  `path` varchar(200) NOT NULL,
  `hash` char(32) NOT NULL,
  `create_user_id` int(11) unsigned DEFAULT '0' COMMENT '创建人',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;




# Dump of table ptcms_author


DROP TABLE IF EXISTS `ptcms_author`;

CREATE TABLE `ptcms_author` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `name` varchar(20) DEFAULT '',
  `pinyin` varchar(50) NOT NULL,
  `truename` varchar(20) DEFAULT '',
  `idcard` varchar(20) DEFAULT '',
  `pay` varchar(50) DEFAULT '',
  `mobile` varchar(20) DEFAULT '',
  `qq` varchar(20) DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pinyin` (`pinyin`),
  UNIQUE KEY `name` (`name`)
) ENGINE=myisam DEFAULT CHARSET=utf8;



# Dump of table ptcms_category


DROP TABLE IF EXISTS `ptcms_category`;

CREATE TABLE `ptcms_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `key` varchar(50) NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `ordernum` smallint(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_user_id` int(11) unsigned DEFAULT '0' COMMENT '创建人',
  `update_user_id` int(11) unsigned DEFAULT '0' COMMENT '修改人',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

LOCK TABLES `ptcms_category` WRITE;
/*!40000 ALTER TABLE `ptcms_category` DISABLE KEYS */;

INSERT INTO `ptcms_category` (`id`, `name`, `key`, `pid`, `ordernum`, `status`, `create_user_id`, `update_user_id`, `create_time`, `update_time`)
VALUES
	(1,'玄幻','xuanhuan',0,50,1,1,0,1414492021,0),
	(2,'奇幻','qihuan',0,50,1,1,0,1414492034,0),
	(3,'武侠','wuxia',0,50,1,1,0,1414492039,0),
	(4,'仙侠','xianxia',0,50,1,1,0,1414492045,0),
	(5,'都市','dushi',0,50,1,1,0,1414492050,0),
	(6,'历史','lishi',0,50,1,1,0,1414492056,0),
	(7,'军事','junshi',0,50,1,1,0,1414492062,0),
	(8,'游戏','youxi',0,50,1,1,0,1414492068,0),
	(9,'竞技','jingji',0,50,1,1,0,1414492076,0),
	(10,'科幻','kehuan',0,50,1,1,0,1414492082,0),
	(11,'灵异','lingyi',0,50,1,1,0,1414492087,0),
	(12,'同人','tongren',0,50,1,1,0,1414492093,0),
	(13,'女生','nvsheng',0,50,1,1,0,1414492098,0),
	(14,'其他','qita',0,50,1,1,0,1414492103,0);

/*!40000 ALTER TABLE `ptcms_category` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ptcms_config


DROP TABLE IF EXISTS `ptcms_config`;

CREATE TABLE `ptcms_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `key` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `intro` varchar(100) NOT NULL DEFAULT '' COMMENT '配置说明',
  `type` set('num','text','radio','checkbox','textarea','array','select') NOT NULL DEFAULT '' COMMENT '配置类型',
  `group` tinyint(3) NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` text NOT NULL COMMENT '配置值',
  `value` text COMMENT '真是配置设置值',
  `level` tinyint(3) DEFAULT '1' COMMENT '配置级别 0辅助配置不导出 1主配置',
  `ordernum` smallint(5) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `create_user_id` int(11) unsigned DEFAULT '0' COMMENT '创建人',
  `update_user_id` int(11) unsigned DEFAULT '0' COMMENT '修改人',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`key`),
  KEY `group` (`group`),
  KEY `type` (`type`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

LOCK TABLES `ptcms_config` WRITE;
/*!40000 ALTER TABLE `ptcms_config` DISABLE KEYS */;

INSERT INTO `ptcms_config` (`id`, `title`, `key`, `intro`, `type`, `group`, `extra`, `value`, `level`, `ordernum`, `create_user_id`, `update_user_id`, `create_time`, `update_time`, `status`)
VALUES
	(1, '网站名称', 'sitename', '', 'text', 1, '', '{installsitename}', 1, 10, 1, 0, 1412994941, 0, 1),
	(2, '网站地址', 'siteurl', '', 'text', 1, '', '{installsiteurl}', 1, 20, 1, 0, 1412994963, 0, 1),
	(3, '备案编号', 'beian', '', 'text', 1, '', '京ICP备111111号', 1, 30, 1, 0, 1412994983, 0, 1),
	(4, '站长邮箱', 'email', '', 'text', 1, '', 'admin#ptcms.com', 1, 30, 1, 0, 1412995008, 0, 1),
	(5, '站长QQ', 'qq', '', 'text', 1, '', '10000', 1, 50, 1, 0, 1412995027, 0, 1),
	(6, '后台列表数量', 'admin_pagesie', '', 'num', 3, '', '20', 1, 50, 1, 0, 1412995077, 0, 1),
	(7, '可用模块列表', 'allow_module', '', 'text', 0, '', 'admin,index,friendlink,ad,page,attachment,rule,novelsearch,cron,user,sitemap,build,install,author', 1, 50, 1, 0, 1412995122, 0, 1),
	(8, '配置分组列表', 'config_group', '', 'array', 0, '', '{"0":"不分组","1":"基本","2":"数据库","3":"显示","4":"功能","5":"开放登录","-1":"TKD","-2":"URL","-3":"采集"}', 1, 50, 1, 1, 1412995206, 1414882030, 1),
	(9, '前台模版', 'tpl_theme', '', 'text', 0, '', 'default', 1, 50, 1, 0, 1412995241, 0, 1),
	(10, '广告目录', 'addir', '', 'text', 4, '', 'ptcms', 1, 50, 1, 0, 1412995428, 0, 1),
	(11, '程序运行信息', 'runinfo', '', 'textarea', 3, '', 'Processed in {time}(s), Memory: {mem}MB, Sqls: {sql}, cacheread: {cacheread}, cachewrite: {cachewrite}, net:{net}.', 1, 50, 1, 0, 1412995459, 0, 1),
	(12, '数据库驱动引擎', 'mysql_driver', '', 'text', 2, '', '{mysql_driver}', 1, 50, 1, 0, 1413002828, 0, 1),
	(13, '数据库表前缀', 'mysql_prefix', '', 'text', 2, '', '{mysql_prefix}', 1, 50, 1, 0, 1413002845, 0, 1),
	(14, '数据库主机', 'mysql_master_host', '', 'text', 2, '', '{mysql_master_host}', 1, 50, 1, 0, 1413002865, 0, 1),
	(15, '数据库端口', 'mysql_master_port', '', 'text', 2, '', '{mysql_master_port}', 1, 50, 1, 0, 1413002882, 0, 1),
	(16, '数据库名称', 'mysql_master_name', '', 'text', 2, '', '{mysql_master_name}', 1, 50, 1, 0, 1413002900, 0, 1),
	(17, '数据库帐号', 'mysql_master_user', '', 'text', 2, '', '{mysql_master_user}', 1, 50, 1, 0, 1413002916, 0, 1),
	(18, '数据库密码', 'mysql_master_pwd', '', 'text', 2, '', '{mysql_master_pwd}', 1, 50, 1, 0, 1413002927, 0, 1),
	(19,'网站开关','app_status','','radio',1,'0:关闭站点\r\n1:正常运行','1',1,100,1,0,1413030317,0,1),
	(20,'网站关闭提示语','app_closemsg','网站管理提示语','textarea',1,'','网站升级中，请稍后访问！',1,110,1,1,1413030364,1413030381,1),
	(21,'默认模块','default_module','访问网站时显示的模块','select',1,'{\"admin\":\"\\u540e\\u53f0\\u7ba1\\u7406\",\"index\":\"\\u7f51\\u7ad9\\u524d\\u53f0\",\"friendlink\":\"\\u53cb\\u60c5\\u94fe\\u63a5\",\"ad\":\"\\u5e7f\\u544a\\u6a21\\u5757\",\"page\":\"\\u5355\\u9875\\u6a21\\u5757\",\"novelsearch\":\"\\u5c0f\\u8bf4\\u641c\\u7d22\",\"rule\":\"\\u89c4\\u5219\\u7ba1\\u7406\",\"attachment\":\"\\u9644\\u4ef6\\u6a21\\u5757\",\"user\":\"\\u7528\\u6237\\u6a21\\u5757\",\"cron\":\"\\u8ba1\\u5212\\u4efb\\u52a1\",\"sitemap\":\"sitemap\",\"install\":\"\\u5b89\\u88c5\\u7a0b\\u5e8f\",\"author\":\"\\u4f5c\\u8005\\u6a21\\u5757\",\"api\":\"api\\u63a5\\u53e3\",\"update\":\"\\u5347\\u7ea7\\u7a0b\\u5e8f\"}','novelsearch',1,50,1,1,1413123711,1413123764,1),
	(22,' 插件','plugin','','array',0,'','{\"template_compile_end\":[\"cleartitle\"]}',1,50,1,0,1413296220,0,1),
	(23,'上传目录','upload_path','上传文件存放地址，相对于根目录','text',4,'','uploads',1,50,1,1,1413419307,1413419327,1),
	(24,'水印类型','water_type','','radio',4,'0:关闭水印\r\n1:图片水印\r\n2:文字水印','0',1,100,1,0,1413446923,0,1),
	(25,'水印图片地址','water_image','','text',4,'0:关闭水印\r\n1:图片水印\r\n2:文字水印','/uploads/201410/16/81c7c4c938a730efd011a68794ad2e51.png',1,105,1,0,1413447136,0,1),
	(26,'文字水印内容','water_text','','textarea',4,'0:关闭水印\r\n1:图片水印\r\n2:文字水印','图片上传于www.ptcms.com',1,130,1,0,1413447169,0,1),
	(27,'文字水印字体','water_font','请添加public/font目录中的字体','textarea',4,'0:关闭水印\r\n1:图片水印\r\n2:文字水印','ptcms.otf',1,140,1,0,1413447207,0,1),
	(28,'水印位置','water_position','','select',4,'[\"\\u5176\\u4ed6\\u4f4d\\u7f6e\",\"\\u5de6\\u4e0a\\u89d2\",\"\\u4e0a\\u5c45\\u4e2d\",\"\\u53f3\\u4e0a\\u89d2\",\"\\u5de6\\u5c45\\u4e2d\",\"\\u5c45\\u4e2d\",\"\\u53f3\\u5c45\\u4e2d\",\"\\u5de6\\u4e0b\\u89d2\",\"\\u4e0b\\u5c45\\u4e2d\",\"\\u53f3\\u4e0b\\u89d2\"]','9',1,110,1,1,1413447377,1413447649,1),
	(29,'水印图片透明度','water_alpha','0-100，100不透明','num',4,'','60',1,120,1,0,1413448316,0,1),
	(30,'水印文字尺寸','water_fontsize','','num',4,'','20',1,150,1,0,1413448363,0,1),
	(31,'水印文字颜色','water_color','','textarea',4,'','#666666',1,160,1,0,1413448390,0,1),
	(32,'网站logo','logo','','text',0,'','/uploads/201410/16/f7786c480bebd49c9248c75c474919b8.png',1,40,1,1,1413449869,1415168548,1),
	(33,'章节列表条数','pagesize_chapterlist','','num',3,'','50',1,50,1,0,1414593864,0,1),
	(34,'分类页条数','pagesize_categorylist','','num',3,'','15',1,50,1,0,1414711182,0,1),
	(35,'排行页条数','pagesize_toplist','','num',3,'','15',1,50,1,0,1414711198,0,1),
	(36,'搜索结果条数','pagesize_search','','num',3,'','20',1,50,1,0,1414715260,0,1),
	(37,'缓存类型','cache_driver','','select',4,'{\"file\":\"文本\",\"memcache\":\"内存\"}','memcache',1,50,1,1,1414773717,1414773853,1),
	(38,'点击记录日期','visit_day','','num',0,'','20160226',1,50,1,0,1414842678,0,1),
	(39,'点击单次次数','visit_num','访问一次增加点击数','num',4,'','1',1,50,1,1,1414842728,1414842898,1),
	(40,'点击累加缓冲','visit_update','当有多少人点击这本书后对数据增加记录','num',4,'','7',1,50,1,0,1414842885,0,1),
	(41,'首页TKD','tkd_index_caption','首页','array',-1,'','{\"title\":\"{sitename} - \\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66\",\"keywords\":\"{sitename} \\u641c\\u7d22\\u9605\\u8bfb\\u7f51 \\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66\",\"description\":\"{sitename}\\u4e13\\u6ce8\\u4e8e\\u7384\\u5e7b\\u5c0f\\u8bf4\\u641c\\u7d22,\\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66,\\u63d0\\u4f9b\\u6700\\u5168\\u7684\\u5c0f\\u8bf4\\u4fdd\\u6301\\u6700\\u5feb\\u7684\\u66f4\\u65b0,\\u65b9\\u4fbf\\u5927\\u5bb6\\u6109\\u5feb\\u5730\\u9605\\u8bfb\\u7384\\u5e7b\\u5c0f\\u8bf4\"}',0,50,1,0,1414882364,0,1),
	(42,'分类列表TKD','tkd_category_caption','分类列表页','array',-1,'','{\"title\":\"{categoryname} - {sitename}\",\"keywords\":\"{categoryname},{sitename}\",\"description\":\"{sitename}\\u4e13\\u6ce8\\u4e8e\\u7384\\u5e7b\\u5c0f\\u8bf4\\u641c\\u7d22,\\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66,\\u63d0\\u4f9b\\u6700\\u5168\\u7684\\u5c0f\\u8bf4\\u4fdd\\u6301\\u6700\\u5feb\\u7684\\u66f4\\u65b0,\\u65b9\\u4fbf\\u5927\\u5bb6\\u6109\\u5feb\\u5730\\u9605\\u8bfb\\u7384\\u5e7b\\u5c0f\\u8bf4\"}',0,50,1,0,1414882388,0,1),
	(43,'排行列表TKD','tkd_top_caption','排行列表页','array',-1,'','{\"title\":\"{topname} - {sitename}\",\"keywords\":\"{topname},{sitename}\",\"description\":\"{sitename}\\u4e13\\u6ce8\\u4e8e\\u7384\\u5e7b\\u5c0f\\u8bf4\\u641c\\u7d22,\\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66,\\u63d0\\u4f9b\\u6700\\u5168\\u7684\\u5c0f\\u8bf4\\u4fdd\\u6301\\u6700\\u5feb\\u7684\\u66f4\\u65b0,\\u65b9\\u4fbf\\u5927\\u5bb6\\u6109\\u5feb\\u5730\\u9605\\u8bfb\\u7384\\u5e7b\\u5c0f\\u8bf4\"}',0,50,1,0,1414882421,0,1),
	(44,'信息TKD','tkd_info_caption','信息页','array',-1,'','{\"title\":\"{novelname}\\u7ae0\\u8282\\u76ee\\u5f55 - {novelname}{sitename}\",\"keywords\":\"{novelname}\\u6700\\u65b0\\u7ae0\\u8282\\u5217\\u8868,{novelname}{sitename}\",\"description\":\"{sitename}\\u63d0\\u4f9b{categoryname}\\u5c0f\\u8bf4\\u300a{novelname}\\u300b\\u6700\\u65b0\\u7ae0\\u8282\\u7684\\u641c\\u7d22\\uff0c\\u66f4\\u65b0\\u8d85\\u7ea7\\u5feb\\uff0c\\u65e0\\u75c5\\u6bd2\\u65e0\\u6728\\u9a6c\\uff0c\\u9875\\u9762\\u5e72\\u51c0\\u6e05\\u723d\\uff0c\\u5e0c\\u671b\\u5927\\u5bb6\\u6536\\u85cf!\"}',0,50,1,0,1414882463,0,1),
	(45,'目录TKD','tkd_dir_caption','目录页','array',-1,'','{\"title\":\"{novelname}\\u6700\\u65b0\\u7ae0\\u8282\\u76ee\\u5f55 - {novelname} {fromname} - {sitename}\",\"keywords\":\"{novelname}\\u6700\\u65b0\\u7ae0\\u8282,{novelname} {fromname}\",\"description\":\"{sitename}\\u63d0\\u4f9b\\u300a{novelname}\\u300b\\u5728\\u201c{fromname}\\u201d\\u7684\\u6700\\u65b0\\u7ae0\\u8282\\u76ee\\u5f55\\u7684\\u7d22\\u5f15\\uff0c\\u66f4\\u65b0\\u8d85\\u7ea7\\u5feb\\uff0c\\u65e0\\u75c5\\u6bd2\\u65e0\\u6728\\u9a6c\\uff0c\\u9875\\u9762\\u5e72\\u51c0\\u6e05\\u723d\\uff0c\\u5e0c\\u671b\\u5927\\u5bb6\\u6536\\u85cf!\"}',0,50,1,0,1414882479,0,1),
	(46,'作者TKD','tkd_author_caption','作者页','array',-1,'','{\"title\":\"{author}\\u4f5c\\u54c1\\u5927\\u5168\",\"keywords\":\"{author},{sitename}\",\"description\":\"{sitename}\\u4e13\\u6ce8\\u4e8e\\u7384\\u5e7b\\u5c0f\\u8bf4\\u641c\\u7d22,\\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66,\\u63d0\\u4f9b\\u6700\\u5168\\u7684\\u5c0f\\u8bf4\\u4fdd\\u6301\\u6700\\u5feb\\u7684\\u66f4\\u65b0,\\u65b9\\u4fbf\\u5927\\u5bb6\\u6109\\u5feb\\u5730\\u9605\\u8bfb\\u7384\\u5e7b\\u5c0f\\u8bf4\"}',0,50,1,0,1414882514,0,1),
	(47,'章节列表TKD','tkd_chapterlist_caption','章节更新列表','array',-1,'','{\"title\":\"{novelname}\\u6700\\u65b0\\u7ae0\\u8282 - {novelname} {fromname} - {novelname}\\u5feb\\u773c\\u770b\\u4e66\",\"keywords\":\"{novelname}\\u6700\\u65b0\\u7ae0\\u8282,{novelname} {fromname} ,{novelname}\\u5feb\\u773c\\u770b\\u4e66\",\"description\":\"{sitename}\\u63d0\\u4f9b\\u300a{novelname}\\u300b\\u6700\\u65b0\\u7ae0\\u8282\\u7684\\u641c\\u7d22\\uff0c\\u66f4\\u65b0\\u8d85\\u7ea7\\u5feb\\uff0c\\u65e0\\u75c5\\u6bd2\\u65e0\\u6728\\u9a6c\\uff0c\\u9875\\u9762\\u5e72\\u51c0\\u6e05\\u723d\\uff0c\\u5e0c\\u671b\\u5927\\u5bb6\\u6536\\u85cf!\"}',0,50,1,0,1414882542,0,1),
	(48,'框架阅读TKD','tkd_frame_caption','框架阅读页','array',-1,'','{\"title\":\"{novelname} {fromname} - {chaptername} - {sitename}\",\"keywords\":\"{novelname} {fromname},{chaptername},{sitename}\",\"description\":\"{sitename}\\u63d0\\u4f9b\\u300a{novelname}\\u300b\\u6700\\u65b0\\u7ae0\\u8282\\u7684\\u641c\\u7d22\\uff0c\\u66f4\\u65b0\\u8d85\\u7ea7\\u5feb\\uff0c\\u65e0\\u75c5\\u6bd2\\u65e0\\u6728\\u9a6c\\uff0c\\u9875\\u9762\\u5e72\\u51c0\\u6e05\\u723d\\uff0c\\u5e0c\\u671b\\u5927\\u5bb6\\u6536\\u85cf!\"}',0,50,1,0,1414883472,0,1),
	(49,'单页TKD','tkd_page_caption','单页','array',-1,'','{\"title\":\"{pagetitle} - {sitename}\",\"keywords\":\"{pagetitle},{sitename}\",\"description\":\"{sitename}\\u4e13\\u6ce8\\u4e8e\\u7384\\u5e7b\\u5c0f\\u8bf4\\u641c\\u7d22,\\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66,\\u63d0\\u4f9b\\u6700\\u5168\\u7684\\u5c0f\\u8bf4\\u4fdd\\u6301\\u6700\\u5feb\\u7684\\u66f4\\u65b0,\\u65b9\\u4fbf\\u5927\\u5bb6\\u6109\\u5feb\\u5730\\u9605\\u8bfb\\u7384\\u5e7b\\u5c0f\\u8bf4\"}',0,50,1,0,1414883512,0,1),
	(50,'搜索TKD','tkd_search_caption','搜索结果页','array',-1,'','{\"title\":\"\\u641c\\u7d22\\u201c{searchkey}\\u201d\\u7684\\u7ed3\\u679c\",\"keywords\":\"{searchkey},{sitename}\",\"description\":\"{sitename}\\u4e13\\u6ce8\\u4e8e\\u7384\\u5e7b\\u5c0f\\u8bf4\\u641c\\u7d22,\\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66,\\u63d0\\u4f9b\\u6700\\u5168\\u7684\\u5c0f\\u8bf4\\u4fdd\\u6301\\u6700\\u5feb\\u7684\\u66f4\\u65b0,\\u65b9\\u4fbf\\u5927\\u5bb6\\u6109\\u5feb\\u5730\\u9605\\u8bfb\\u7384\\u5e7b\\u5c0f\\u8bf4\"}',0,50,1,0,1414883547,0,1),
	(51,'跳转TKD','tkd_go_caption','跳转','array',-1,'','{\"title\":\"{chaptername}\",\"keywords\":\"\",\"description\":\"\"}',0,50,1,0,1414883564,0,1),
	(53,'分类列表URL','url_category','可用{key}分类标识,{page}页码','text',-2,'','/sort[/{key}][/{page}].html',0,11,1,0,1414882388,0,1),
	(54,'排行列表URL','url_top','可用{type}排行标识,{page}页码','text',-2,'','/top[/{type}][/{page}].html',0,50,1,0,1414882421,0,1),
	(55,'信息URL','url_info','可用{novelkey}小说拼音{novelid}书号','text',-2,'','/novel/{novelkey}/',0,2,1,0,1414882463,0,1),
	(56,'目录URL','url_dir','可用{novelkey}小说拼音{novelid}书号','text',-2,'','/dir/{novelkey}/[{sitekey}/]',0,5,1,0,1414882479,0,1),
	(57,'作者URL','url_author','可用{author}作者名{authorid} 作者id{authorpinyin} 作者拼音','text',-2,'','/author/{authorpinyin}',0,6,1,0,1414882514,0,1),
	(58,'章节列表URL','url_chapterlist','可用{novelkey}拼音{novelid}书号{siteid}站点id{page}页码','text',-2,'','/novel/chapterlist/{novelkey}[_{sitekey}][_{page}].html',0,4,1,0,1414882542,0,1),
	(59,'框架阅读URL','url_frame','可用{novelkey}小说拼音{novelid}书号{chapterid}章节id','text',-2,'','/novel/{novelkey}/kj_{chapterid}.html',0,8,1,0,1414883472,0,1),
	(60,'单页URL','url_page','可用{key}单页标识','text',-2,'','/about/{key}.html',0,50,1,0,1414883512,0,1),
	(61,'搜索结果URL','url_search','搜索','text',-2,'','/search.html',0,50,1,0,1414883547,0,1),
	(62,'跳转URL','url_go','可用{novelkey}小说拼音{novelid}书号{chapterid}章节id','text',-2,'','/novel/{novelkey}/go_{chapterid}.html',0,9,1,1,1414883564,1414891953,1),
	(63,'页面标识','pagesign','页面标识和控制器之间映射','array',0,'','{\"novelsearch.index.index\":\"index\",\"novelsearch.list.top\":\"top\",\"novelsearch.list.category\":\"category\",\"novelsearch.novel.index\":\"info\",\"novelsearch.novel.author\":\"author\",\"novelsearch.novel.dir\":\"dir\",\"novelsearch.chapter.list\":\"chapterlist\",\"novelsearch.chapter.frame\":\"frame\",\"novelsearch.chapter.go\":\"go\",\"novelsearch.chapter.green\":\"green\",\"novelsearch.chapter.read\":\"read\",\"novelsearch.search.result\":\"search\",\"page.index.detail\":\"page\",\"sitemap.index.index\":\"sitemapindex\",\"sitemap.index.info\":\"sitemapinfo\",\"novelsearch.list.over\":\"over\",\"novelsearch.novel.readend\":\"readend\",\"novelsearch.index.top\":\"topindex\",\"novelsearch.index.category\":\"categoryindex\",\"novelsearch.search.index\":\"searchindex\"}',1,50,1,1,1414891596,1454477411,1),
	(64,'链接生成规则','url_rules','','array',0,'','{\"novelsearch.index.index\":\"\\/\",\"novelsearch.novel.index\":\"\\/novel\\/{novelkey}\\/\",\"novelsearch.chapter.list\":\"\\/novel\\/chapterlist\\/{novelkey}[_{sitekey}][_{page}].html\",\"novelsearch.novel.dir\":\"\\/dir\\/{novelkey}\\/[{sitekey}\\/]\",\"novelsearch.novel.author\":\"\\/author\\/{author}\",\"novelsearch.chapter.read\":\"\\/novel\\/{novelkey}\\/read_{chapterid}.html\",\"novelsearch.chapter.frame\":\"\\/novel\\/{novelkey}\\/kj_{chapterid}.html\",\"novelsearch.chapter.go\":\"\\/novel\\/{novelkey}\\/go_{chapterid}.html\",\"novelsearch.novel.readend\":\"\\/novel\\/{novelkey}\\/readend.html\",\"novelsearch.list.over\":\"\\/over\\/[{page}.html]\",\"novelsearch.index.category\":\"\\/sort\\/\",\"novelsearch.index.top\":\"\\/top\\/\",\"novelsearch.search.index\":\"\\/search\\/\",\"novelsearch.chapter.green\":\"\\/novel\\/{novelkey}\\/tc_{chapterid}.html\",\"novelsearch.list.category\":\"\\/sort[\\/{key}][\\/{page}].html\",\"novelsearch.list.top\":\"\\/top[\\/{type}][\\/{page}].html\",\"page.index.detail\":\"\\/about\\/{key}.html\",\"novelsearch.search.result\":\"\\/search.html\",\"sitemap.index.index\":\"\\/sitemap\\/index.xml\",\"sitemap.index.info\":\"\\/sitemap\\/{page}.xml\"}',1,50,1,0,1414892933,0,1),
	(65,'路由映射规则','url_router','','array',0,'','{\"^novel\\/([a-zA-Z0-9_\\\\-]+)(\\\\?(.*))*$\":\"novelsearch\\/novel\\/index?novelkey\",\"^novel\\/chapterlist\\/([a-zA-Z0-9_\\\\-]+)_([a-zA-Z0-9_\\\\-]+)_(\\\\d+).html(\\\\?(.*))*$\":\"novelsearch\\/chapter\\/list?novelkey&sitekey&page\",\"^novel\\/chapterlist\\/([a-zA-Z0-9_\\\\-]+)_(\\\\d+).html(\\\\?(.*))*$\":\"novelsearch\\/chapter\\/list?novelkey&page\",\"^novel\\/chapterlist\\/([a-zA-Z0-9_\\\\-]+)_([a-zA-Z0-9_\\\\-]+).html(\\\\?(.*))*$\":\"novelsearch\\/chapter\\/list?novelkey&sitekey\",\"^novel\\/chapterlist\\/([a-zA-Z0-9_\\\\-]+).html(\\\\?(.*))*$\":\"novelsearch\\/chapter\\/list?novelkey\",\"^dir\\/([a-zA-Z0-9_\\\\-]+)\\/([a-zA-Z0-9_\\\\-]+)(\\\\?(.*))*$\":\"novelsearch\\/novel\\/dir?novelkey&sitekey\",\"^dir\\/([a-zA-Z0-9_\\\\-]+)(\\\\?(.*))*$\":\"novelsearch\\/novel\\/dir?novelkey\",\"^author\\/([^\\\\..]+?)(\\\\?(.*))*$\":\"novelsearch\\/novel\\/author?author\",\"^novel\\/([a-zA-Z0-9_\\\\-]+)\\/read_(\\\\d+).html(\\\\?(.*))*$\":\"novelsearch\\/chapter\\/read?novelkey&chapterid\",\"^novel\\/([a-zA-Z0-9_\\\\-]+)\\/kj_(\\\\d+).html(\\\\?(.*))*$\":\"novelsearch\\/chapter\\/frame?novelkey&chapterid\",\"^novel\\/([a-zA-Z0-9_\\\\-]+)\\/go_(\\\\d+).html(\\\\?(.*))*$\":\"novelsearch\\/chapter\\/go?novelkey&chapterid\",\"^novel\\/([a-zA-Z0-9_\\\\-]+)\\/readend.html(\\\\?(.*))*$\":\"novelsearch\\/novel\\/readend?novelkey\",\"^over\\/(\\\\d+).html(\\\\?(.*))*$\":\"novelsearch\\/list\\/over?page\",\"^over(\\\\?(.*))*$\":\"novelsearch\\/list\\/over?\",\"^sort(\\\\?(.*))*$\":\"novelsearch\\/index\\/category?\",\"^top(\\\\?(.*))*$\":\"novelsearch\\/index\\/top?\",\"^search(\\\\?(.*))*$\":\"novelsearch\\/search\\/index?\",\"^novel\\/([a-zA-Z0-9_\\\\-]+)\\/tc_(\\\\d+).html(\\\\?(.*))*$\":\"novelsearch\\/chapter\\/green?novelkey&chapterid\",\"^sort\\/([a-zA-Z0-9]+)\\/(\\\\d+).html(\\\\?(.*))*$\":\"novelsearch\\/list\\/category?key&page\",\"^sort\\/(\\\\d+).html(\\\\?(.*))*$\":\"novelsearch\\/list\\/category?page\",\"^sort\\/([a-zA-Z0-9]+).html(\\\\?(.*))*$\":\"novelsearch\\/list\\/category?key\",\"^sort.html(\\\\?(.*))*$\":\"novelsearch\\/list\\/category?\",\"^top\\/([a-zA-Z0-9]+)\\/(\\\\d+).html(\\\\?(.*))*$\":\"novelsearch\\/list\\/top?type&page\",\"^top\\/(\\\\d+).html(\\\\?(.*))*$\":\"novelsearch\\/list\\/top?page\",\"^top\\/([a-zA-Z0-9]+).html(\\\\?(.*))*$\":\"novelsearch\\/list\\/top?type\",\"^top.html(\\\\?(.*))*$\":\"novelsearch\\/list\\/top?\",\"^about\\/([a-zA-Z0-9]+).html(\\\\?(.*))*$\":\"page\\/index\\/detail?key\",\"^search.html(\\\\?(.*))*$\":\"novelsearch\\/search\\/result?\",\"^sitemap\\/index.xml(\\\\?(.*))*$\":\"sitemap\\/index\\/index?\",\"^sitemap\\/(\\\\d+).xml(\\\\?(.*))*$\":\"sitemap\\/index\\/info?page\"}',1,50,1,0,1414892966,0,1),
	(66,'封面校验','collect_cover_check','','radio',-3,'1:校验\r\n0:不校验','1',1,35,1,0,1414904643,0,1),
	(67,'默认分类','collect_category_default','','text',-3,'','14',1,50,1,0,1414904668,0,1),
	(68,'分类匹配规则','collect_category_rule','','textarea',-3,'','1|玄幻=玄幻|东方|异界|远古|神话|异世|上古|王朝|争霸|变身情缘|异世争霸|异术超能\r\n2|奇幻=奇幻|西方|领主贵族|亡灵骷髅|异类兽族|魔法校园|吸血传奇\r\n3|武侠=武侠|异侠|国术|武技|国术古武|江湖情仇\r\n4|仙侠=仙侠|古典仙侠|奇幻修真|现代修真|洪荒封神\r\n5|都市=都市|屌丝|生活|商|职场|官场|明星|现实|乡土|宦海|重生|异能|青春|乡村|风云|大亨\r\n6|历史=历史|架空|秦|汉|三国|两晋|隋|唐|五代|十国|宋|元|明|清|民国|传记|穿越|传奇\r\n7|军事=军事|战争|抗战|烽火|军|间谍|暗战|谍战\r\n8|游戏=游戏|网游|电子竞技\r\n9|竞技=竞技|体育|篮球|足球|弈林|棋牌|桌游\r\n10|科幻=科幻|未来|星际|古武机甲|数字生命|科技|时空|进化|变异|末世\r\n11|灵异=灵异|恐怖|惊悚|推理|侦探|悬疑|探险|悬念|神怪|探险|异闻|神秘\r\n12|同人=同人\r\n13|女生=女生|女|言情|言情|豪门|王爷|合租|情缘|恩怨|情仇|校园|情感|总裁\r\n14|其他=其他',1,50,1,0,1414904759,0,1),
	(69,'采集函数','httpmethod','','radio',-3,'curl:curl推荐\r\nfilegc:file_get_contents\r\nfsock:FSockOpen','curl',1,50,1,0,1414908929,0,1),
	(70,'采集超时','timeout','','num',-3,'','10',1,50,1,0,1414908972,0,1),
	(71,'采集器标识','user_agent','','textarea',-3,'','KuaiYanKanShuSpider/4.1 (http://www.kuaiyankanshu.net/about/spider.html)',1,50,1,0,1414909002,0,1),
	(72,'开放登录开关','oauth_power','','radio',5,'0:关闭\r\n1:启用','0',1,10,1,1,1414971321,1414971333,1),
	(73,'QQAPPID','oauth_qq_appid','','text',5,'','',1,20,1,0,1414971412,0,1),
	(74,'QQ密钥','oauth_qq_appsecret','','text',5,'','',1,30,1,1,1414971453,1414971592,1),
	(75,'QQ网站验证','oauth_qq_check','','text',5,'','',1,40,1,1,1414971494,1414971604,1),
	(76,'微博网站验证','oauth_weibo_check','','text',5,'','',1,70,1,0,1414971527,0,1),
	(77,'微博APPID','oauth_weibo_appid','','text',5,'','',1,50,1,0,1414971552,0,1),
	(78,'微博密钥','oauth_weibo_appsecret','','text',5,'','',1,60,1,0,1414971576,0,1),
	(79,'章节阅读方式','read_type','','select',4,'{\"go\":\"直接跳转\",\"frame\":\"框架嵌入\",\"green\":\"快照阅读\"}','green',1,50,1,0,1414971931,0,1),
	(80,'分类默认名称','caption_allcateogry','','text',3,'','全部',1,50,1,0,1414971998,0,1),
	(81,'排行名称','caption_top','','array',0,'','{\"postdate\":\"入库时间\",\"lastupdate\":\"最新更新\",\"dayvisit\":\"日点击\",\"weekvisit\":\"周点击\",\"monthvisit\":\"月点击\",\"allvisit\":\"总点击\",\"marknum\":\"收藏数\",\"votenum\":\"推荐数\",\"downnum\":\"下载数\"}',1,50,1,0,1414972100,0,1),
	(82,'计划任务状态','cron_power','','num',0,'','0',1,50,1,0,1415242000,0,1),
	(84,'首页TKD','tkd_index','首页','array',0,'','{\"title\":\"{$pt.config.sitename} - \\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66\",\"keywords\":\"{$pt.config.sitename} \\u641c\\u7d22\\u9605\\u8bfb\\u7f51 \\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66\",\"description\":\"{$pt.config.sitename}\\u4e13\\u6ce8\\u4e8e\\u7384\\u5e7b\\u5c0f\\u8bf4\\u641c\\u7d22,\\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66,\\u63d0\\u4f9b\\u6700\\u5168\\u7684\\u5c0f\\u8bf4\\u4fdd\\u6301\\u6700\\u5feb\\u7684\\u66f4\\u65b0,\\u65b9\\u4fbf\\u5927\\u5bb6\\u6109\\u5feb\\u5730\\u9605\\u8bfb\\u7384\\u5e7b\\u5c0f\\u8bf4\"}',1,50,1,0,1414882364,0,1),
	(85,'分类TKD','tkd_category','分类','array',0,'','{\"title\":\"{$category.name} - {$pt.config.sitename}\",\"keywords\":\"{$category.name},{$pt.config.sitename}\",\"description\":\"{$pt.config.sitename}\\u4e13\\u6ce8\\u4e8e\\u7384\\u5e7b\\u5c0f\\u8bf4\\u641c\\u7d22,\\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66,\\u63d0\\u4f9b\\u6700\\u5168\\u7684\\u5c0f\\u8bf4\\u4fdd\\u6301\\u6700\\u5feb\\u7684\\u66f4\\u65b0,\\u65b9\\u4fbf\\u5927\\u5bb6\\u6109\\u5feb\\u5730\\u9605\\u8bfb\\u7384\\u5e7b\\u5c0f\\u8bf4\"}',1,50,1,0,1414882388,0,1),
	(86,'排行TKD','tkd_top','排行','array',0,'','{\"title\":\"{$top.name} - {$pt.config.sitename}\",\"keywords\":\"{$top.name},{$pt.config.sitename}\",\"description\":\"{$pt.config.sitename}\\u4e13\\u6ce8\\u4e8e\\u7384\\u5e7b\\u5c0f\\u8bf4\\u641c\\u7d22,\\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66,\\u63d0\\u4f9b\\u6700\\u5168\\u7684\\u5c0f\\u8bf4\\u4fdd\\u6301\\u6700\\u5feb\\u7684\\u66f4\\u65b0,\\u65b9\\u4fbf\\u5927\\u5bb6\\u6109\\u5feb\\u5730\\u9605\\u8bfb\\u7384\\u5e7b\\u5c0f\\u8bf4\"}',1,50,1,0,1414882421,0,1),
	(87,'信息TKD','tkd_info','信息','array',0,'','{\"title\":\"{$novel.name}\\u7ae0\\u8282\\u76ee\\u5f55 - {$novel.name}{$pt.config.sitename}\",\"keywords\":\"{$novel.name}\\u6700\\u65b0\\u7ae0\\u8282\\u5217\\u8868,{$novel.name}{$pt.config.sitename}\",\"description\":\"{$pt.config.sitename}\\u63d0\\u4f9b{$category.name}\\u5c0f\\u8bf4\\u300a{$novel.name}\\u300b\\u6700\\u65b0\\u7ae0\\u8282\\u7684\\u641c\\u7d22\\uff0c\\u66f4\\u65b0\\u8d85\\u7ea7\\u5feb\\uff0c\\u65e0\\u75c5\\u6bd2\\u65e0\\u6728\\u9a6c\\uff0c\\u9875\\u9762\\u5e72\\u51c0\\u6e05\\u723d\\uff0c\\u5e0c\\u671b\\u5927\\u5bb6\\u6536\\u85cf!\"}',1,50,1,0,1414882463,0,1),
	(88,'目录TKD','tkd_dir','目录','array',0,'','{\"title\":\"{$novel.name}\\u6700\\u65b0\\u7ae0\\u8282\\u76ee\\u5f55 - {$novel.name} {$sitename} - {$pt.config.sitename}\",\"keywords\":\"{$novel.name}\\u6700\\u65b0\\u7ae0\\u8282,{$novel.name} {$sitename}\",\"description\":\"{$pt.config.sitename}\\u63d0\\u4f9b\\u300a{$novel.name}\\u300b\\u5728\\u201c{$sitename}\\u201d\\u7684\\u6700\\u65b0\\u7ae0\\u8282\\u76ee\\u5f55\\u7684\\u7d22\\u5f15\\uff0c\\u66f4\\u65b0\\u8d85\\u7ea7\\u5feb\\uff0c\\u65e0\\u75c5\\u6bd2\\u65e0\\u6728\\u9a6c\\uff0c\\u9875\\u9762\\u5e72\\u51c0\\u6e05\\u723d\\uff0c\\u5e0c\\u671b\\u5927\\u5bb6\\u6536\\u85cf!\"}',1,50,1,0,1414882479,0,1),
	(89,'作者TKD','tkd_author','作者','array',0,'','{\"title\":\"{$author.name}\\u4f5c\\u54c1\\u5927\\u5168\",\"keywords\":\"{$author.name},{$pt.config.sitename}\",\"description\":\"{$pt.config.sitename}\\u4e13\\u6ce8\\u4e8e\\u7384\\u5e7b\\u5c0f\\u8bf4\\u641c\\u7d22,\\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66,\\u63d0\\u4f9b\\u6700\\u5168\\u7684\\u5c0f\\u8bf4\\u4fdd\\u6301\\u6700\\u5feb\\u7684\\u66f4\\u65b0,\\u65b9\\u4fbf\\u5927\\u5bb6\\u6109\\u5feb\\u5730\\u9605\\u8bfb\\u7384\\u5e7b\\u5c0f\\u8bf4\"}',1,50,1,0,1414882514,0,1),
	(90,'章节列表TKD','tkd_chapterlist','章节列表','array',0,'','{\"title\":\"{$novel.name}\\u6700\\u65b0\\u7ae0\\u8282 - {$novel.name} {$sitename} - {$novel.name}\\u5feb\\u773c\\u770b\\u4e66\",\"keywords\":\"{$novel.name}\\u6700\\u65b0\\u7ae0\\u8282,{$novel.name} {$sitename} ,{$novel.name}\\u5feb\\u773c\\u770b\\u4e66\",\"description\":\"{$pt.config.sitename}\\u63d0\\u4f9b\\u300a{$novel.name}\\u300b\\u6700\\u65b0\\u7ae0\\u8282\\u7684\\u641c\\u7d22\\uff0c\\u66f4\\u65b0\\u8d85\\u7ea7\\u5feb\\uff0c\\u65e0\\u75c5\\u6bd2\\u65e0\\u6728\\u9a6c\\uff0c\\u9875\\u9762\\u5e72\\u51c0\\u6e05\\u723d\\uff0c\\u5e0c\\u671b\\u5927\\u5bb6\\u6536\\u85cf!\"}',1,50,1,0,1414882542,0,1),
	(91,'框架阅读TKD','tkd_frame','框架','array',0,'','{\"title\":\"{$novel.name} {$sitename} - {$chapter.name} - {$pt.config.sitename}\",\"keywords\":\"{$novel.name} {$sitename},{$chapter.name},{$pt.config.sitename}\",\"description\":\"{$pt.config.sitename}\\u63d0\\u4f9b\\u300a{$novel.name}\\u300b\\u6700\\u65b0\\u7ae0\\u8282\\u7684\\u641c\\u7d22\\uff0c\\u66f4\\u65b0\\u8d85\\u7ea7\\u5feb\\uff0c\\u65e0\\u75c5\\u6bd2\\u65e0\\u6728\\u9a6c\\uff0c\\u9875\\u9762\\u5e72\\u51c0\\u6e05\\u723d\\uff0c\\u5e0c\\u671b\\u5927\\u5bb6\\u6536\\u85cf!\"}',1,50,1,0,1414883472,0,1),
	(92,'单页TKD','tkd_page','单页','array',0,'','{\"title\":\"{$name} - {$pt.config.sitename}\",\"keywords\":\"{$name},{$pt.config.sitename}\",\"description\":\"{$pt.config.sitename}\\u4e13\\u6ce8\\u4e8e\\u7384\\u5e7b\\u5c0f\\u8bf4\\u641c\\u7d22,\\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66,\\u63d0\\u4f9b\\u6700\\u5168\\u7684\\u5c0f\\u8bf4\\u4fdd\\u6301\\u6700\\u5feb\\u7684\\u66f4\\u65b0,\\u65b9\\u4fbf\\u5927\\u5bb6\\u6109\\u5feb\\u5730\\u9605\\u8bfb\\u7384\\u5e7b\\u5c0f\\u8bf4\"}',1,50,1,0,1414883512,0,1),
	(93,'搜索TKD','tkd_search','搜索','array',0,'','{\"title\":\"\\u641c\\u7d22\\u201c{$searchkey}\\u201d\\u7684\\u7ed3\\u679c\",\"keywords\":\"{$searchkey},{$pt.config.sitename}\",\"description\":\"{$pt.config.sitename}\\u4e13\\u6ce8\\u4e8e\\u7384\\u5e7b\\u5c0f\\u8bf4\\u641c\\u7d22,\\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66,\\u63d0\\u4f9b\\u6700\\u5168\\u7684\\u5c0f\\u8bf4\\u4fdd\\u6301\\u6700\\u5feb\\u7684\\u66f4\\u65b0,\\u65b9\\u4fbf\\u5927\\u5bb6\\u6109\\u5feb\\u5730\\u9605\\u8bfb\\u7384\\u5e7b\\u5c0f\\u8bf4\"}',1,50,1,0,1414883547,0,1),
	(94,'跳转TKD','tkd_go','跳转','array',0,'','{\"title\":\"{$chapter.name}\",\"keywords\":\"\",\"description\":\"\"}',1,50,1,0,1414883564,0,1),
	(95,'sitemap索引','url_sitemapindex','','text',-2,'','/sitemap/index.xml',0,50,1,0,1415362686,0,1),
	(96,'sitemap内容','url_sitemapinfo','','text',-2,'','/sitemap/{page}.xml',0,50,1,0,1415362710,0,1),
	(97,'rewrite开关','rewritepower','','num',0,'','1',1,50,1,0,1415242000,0,1),
	(98,'数据库类型','db_type','','text',0,'','mysql',1,50,1,0,1428932179,0,1),
	(99,'章节阅读方式备用','read_type2','快照阅读没有规则的时候使用','select',4,'{\"go\":\"直接跳转\",\"frame\":\"框架嵌入\",\"green\":\"快照阅读\"}','frame',1,50,1,0,1414971931,0,1),
	(100,'绿色阅读URL','url_green','可用{novelkey}小说拼音{novelid}书号{chapterid}章节id','text',-2,'','/novel/{novelkey}/tc_{chapterid}.html',0,10,1,0,1414883472,0,1),
	(101,'绿色阅读TKD','tkd_green','转码','array',0,'','{\"title\":\"{$novel.name} {$sitename} - {$chapter.name} - {$pt.config.sitename}\",\"keywords\":\"{$novel.name} {$sitename},{$chapter.name},{$pt.config.sitename}\",\"description\":\"{$pt.config.sitename}\\u63d0\\u4f9b\\u300a{$novel.name}\\u300b\\u6700\\u65b0\\u7ae0\\u8282\\u7684\\u641c\\u7d22\\uff0c\\u66f4\\u65b0\\u8d85\\u7ea7\\u5feb\\uff0c\\u65e0\\u75c5\\u6bd2\\u65e0\\u6728\\u9a6c\\uff0c\\u9875\\u9762\\u5e72\\u51c0\\u6e05\\u723d\\uff0c\\u5e0c\\u671b\\u5927\\u5bb6\\u6536\\u85cf!\"}',1,50,1,0,1414883472,0,1),
	(102,'绿色阅读TKD','tkd_green_caption','转码阅读页','array',-1,'','{\"title\":\"{novelname} {fromname} - {chaptername} - {sitename}\",\"keywords\":\"{novelname} {fromname},{chaptername},{sitename}\",\"description\":\"{sitename}\\u63d0\\u4f9b\\u300a{novelname}\\u300b\\u6700\\u65b0\\u7ae0\\u8282\\u7684\\u641c\\u7d22\\uff0c\\u66f4\\u65b0\\u8d85\\u7ea7\\u5feb\\uff0c\\u65e0\\u75c5\\u6bd2\\u65e0\\u6728\\u9a6c\\uff0c\\u9875\\u9762\\u5e72\\u51c0\\u6e05\\u723d\\uff0c\\u5e0c\\u671b\\u5927\\u5bb6\\u6536\\u85cf!\"}',0,50,1,0,1414883472,0,1),
	(103,'wap模板','wap_theme','','text',0,'','wap',1,30,1,0,1412994983,0,1),
	(104,'wap域名','wap_domain','如 http://m.zhuishukan.com','text',1,'','http://m.ptso.com',1,95,1,0,1412994983,0,1),
	(105,'wap模式','wap_type','','radio',1,'0:关闭wap\r\n1:直接显示手机模版\r\n2:跳转到手机域名','2',1,90,1,0,1413030317,0,1),
	(106,'模板保护目录','tpl_protect','','text',3,'','ptcms',1,50,1,0,1412995077,0,1),
	(107,'综合阅读URL','url_read','可用{novelkey}小说拼音{novelid}书号{chapterid}章节id','text',-2,'','/novel/{novelkey}/read_{chapterid}.html',0,7,1,0,1414883472,0,1),
	(108,'综合阅读TKD','tkd_read','综合阅读','array',0,'','{\"title\":\" {$chapter.name} \\u8f6c\\u7801\\u9605\\u8bfb - {$novel.name} {$sitename} -{$pt.config.sitename}\",\"keywords\":\"{$novel.name} {$sitename},{$chapter.name},{$pt.config.sitename}\",\"description\":\"{$pt.config.sitename}\\u63d0\\u4f9b\\u300a{$novel.name}\\u300b\\u6700\\u65b0\\u7ae0\\u8282\\u7684\\u641c\\u7d22\\uff0c\\u66f4\\u65b0\\u8d85\\u7ea7\\u5feb\\uff0c\\u65e0\\u75c5\\u6bd2\\u65e0\\u6728\\u9a6c\\uff0c\\u9875\\u9762\\u5e72\\u51c0\\u6e05\\u723d\\uff0c\\u5e0c\\u671b\\u5927\\u5bb6\\u6536\\u85cf!\"}',1,50,1,0,1414883472,0,1),
	(109,'综合阅读TKD','tkd_read_caption','综合阅读','array',-1,'','{\"title\":\" {chaptername} \\u8f6c\\u7801\\u9605\\u8bfb - {novelname} {fromname} -{sitename}\",\"keywords\":\"{novelname} {fromname},{chaptername},{sitename}\",\"description\":\"{sitename}\\u63d0\\u4f9b\\u300a{novelname}\\u300b\\u6700\\u65b0\\u7ae0\\u8282\\u7684\\u641c\\u7d22\\uff0c\\u66f4\\u65b0\\u8d85\\u7ea7\\u5feb\\uff0c\\u65e0\\u75c5\\u6bd2\\u65e0\\u6728\\u9a6c\\uff0c\\u9875\\u9762\\u5e72\\u51c0\\u6e05\\u723d\\uff0c\\u5e0c\\u671b\\u5927\\u5bb6\\u6536\\u85cf!\"}',0,50,1,0,1414883472,0,1),
	(110,'网站资源地址','resurl','图片js静态资源地址,末尾不要带/','text',1,'','',1,20,1,0,1412994963,0,1),
	(111,'网站计划任务地址','cronurl','执行计划任务的地址前缀，不写为使用网站地址','text',1,'','',1,20,1,0,1412994963,0,1),
	(112,'直接跳转等待时间','go_waittime','','num',4,'','1',1,50,0,0,0,0,0),
	(113,'封面本地化','collect_cover_save','必须开启封面校验才可以本地化','radio',-3,'1:保存\r\n0:不保存','0',1,40,1,0,1414904643,0,1),
	(114,'转码阅读展示方式','greenread_showtype','','radio',4,'0:自动(手机及蜘蛛直接显示，用户js加载)\r\n1:直接展示\r\n2:JS加载','0',1,50,1,0,1414904643,0,1),
	(115,'章节缓存保存目录','chapter_path','章节内容保存目录,@表示程序根目录','text',4,'','@/data/',1,52,1,0,1412994963,0,1),
	(116,'章节缓存开关','chapter_cache_power','','radio',4,'0:关闭\r\n1:开启','0',1,52,1,0,1414904643,0,1),
	(117,'章节显示方式','chapter_show_type','','radio',4,'0:权重随机\r\n1:权重排序\r\n2:最新更新\r\n3:最早更新','0',1,52,1,0,1414904643,0,1),
	(118,'转码阅读失败提示','greenread_errormsg','','textarea',4,'','转码失败！请您使用右上换源切换源站阅读或者直接前往源网站进行阅读！',1,51,1,0,1414904643,0,1),
	(119,'采集跳过书籍','collect_skip_novel','采集时跳过的书籍，一行一个','textarea',-3,'','',1,50,1,0,1414904759,0,1),
	(120,'章节内容替换','content_replace','法律风险，慎用！一行一个，用\"替换前|||替换后\"进行替换操作，如果没有“|||替换后”则为清除','textarea',-3,'','',1,50,1,0,1414904759,0,1),
	(121,'连载完结标识','collect_over_caption','','textarea',-3,'','完结|结束|完本|完成|1',1,50,1,0,1414909002,0,1),
	(122,'阅读尾页','tkd_readend_caption','阅读尾页','array',-1,'','{\"title\":\"{novelname}\\u7ae0\\u8282\\u76ee\\u5f55 - {novelname}{sitename}\",\"keywords\":\"{novelname}\\u6700\\u65b0\\u7ae0\\u8282\\u5217\\u8868,{novelname}{sitename}\",\"description\":\"{sitename}\\u63d0\\u4f9b{categoryname}\\u5c0f\\u8bf4\\u300a{novelname}\\u300b\\u6700\\u65b0\\u7ae0\\u8282\\u7684\\u641c\\u7d22\\uff0c\\u66f4\\u65b0\\u8d85\\u7ea7\\u5feb\\uff0c\\u65e0\\u75c5\\u6bd2\\u65e0\\u6728\\u9a6c\\uff0c\\u9875\\u9762\\u5e72\\u51c0\\u6e05\\u723d\\uff0c\\u5e0c\\u671b\\u5927\\u5bb6\\u6536\\u85cf!\"}',0,50,1,0,1414883564,0,1),
	(123,'全本列表','tkd_over_caption','全本列表','array',-1,'','{\"title\":\"\\u5168\\u672c\\u5c0f\\u8bf4 - {sitename}\",\"keywords\":\"\\u5168\\u672c\\u5c0f\\u8bf4,{sitename}\",\"description\":\"{sitename}\\u4e13\\u6ce8\\u4e8e\\u7384\\u5e7b\\u5c0f\\u8bf4\\u641c\\u7d22,\\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66,\\u63d0\\u4f9b\\u6700\\u5168\\u7684\\u5c0f\\u8bf4\\u4fdd\\u6301\\u6700\\u5feb\\u7684\\u66f4\\u65b0,\\u65b9\\u4fbf\\u5927\\u5bb6\\u6109\\u5feb\\u5730\\u9605\\u8bfb\\u7384\\u5e7b\\u5c0f\\u8bf4\"}',0,50,1,0,1414883564,0,1),
	(125,'阅读尾页URL','url_readend','可用{novelkey}小说拼音{novelid}书号','text',-2,'','/novel/{novelkey}/readend.html',0,9,1,1,1414883564,1414891953,1),
	(126,'全本列表URL','url_over','可用{page}页码','text',-2,'','/over/[{page}.html]',0,9,1,1,1414883564,1414891953,1),
	(127,'全本页条数','pagesize_over','','num',3,'','15',1,50,1,0,1414711198,0,1),
	(128,'章节尾页跳转','readend_type','','radio',3,'0:阅读尾页\r\n1:信息页\r\n2:更新列表','0',1,90,1,0,1413030317,0,1),
	(129,'接口地址前缀','apiurl','','text',1,'','http://app.api.ptcms.com',1,20,1,0,1412994963,0,1),
	(130,'全本TKD','tkd_over','全本','array',0,'','{\"title\":\"\\u5168\\u672c\\u5c0f\\u8bf4 - {$pt.config.sitename}\",\"keywords\":\"\\u5168\\u672c\\u5c0f\\u8bf4,{$pt.config.sitename}\",\"description\":\"{$pt.config.sitename}\\u4e13\\u6ce8\\u4e8e\\u7384\\u5e7b\\u5c0f\\u8bf4\\u641c\\u7d22,\\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66,\\u63d0\\u4f9b\\u6700\\u5168\\u7684\\u5c0f\\u8bf4\\u4fdd\\u6301\\u6700\\u5feb\\u7684\\u66f4\\u65b0,\\u65b9\\u4fbf\\u5927\\u5bb6\\u6109\\u5feb\\u5730\\u9605\\u8bfb\\u7384\\u5e7b\\u5c0f\\u8bf4\"}',1,50,1,0,1414883564,0,1),
	(131,'阅读尾页TKD','tkd_readend','阅读尾页','array',0,'','{\"title\":\"{$novel.name}\\u7ae0\\u8282\\u76ee\\u5f55 - {$novel.name}{$pt.config.sitename}\",\"keywords\":\"{$novel.name}\\u6700\\u65b0\\u7ae0\\u8282\\u5217\\u8868,{$novel.name}{$pt.config.sitename}\",\"description\":\"{$pt.config.sitename}\\u63d0\\u4f9b{$category.name}\\u5c0f\\u8bf4\\u300a{$novel.name}\\u300b\\u6700\\u65b0\\u7ae0\\u8282\\u7684\\u641c\\u7d22\\uff0c\\u66f4\\u65b0\\u8d85\\u7ea7\\u5feb\\uff0c\\u65e0\\u75c5\\u6bd2\\u65e0\\u6728\\u9a6c\\uff0c\\u9875\\u9762\\u5e72\\u51c0\\u6e05\\u723d\\uff0c\\u5e0c\\u671b\\u5927\\u5bb6\\u6536\\u85cf!\"}',1,50,1,0,1414883564,0,1),
	(132,'手机目录单页条数','pagesize_dir_wap','','num',3,'','50',1,50,1,0,1414711198,0,1),
	(133,'拼音首字母大写','pinyin_uc_first','书名转拼音的时候拼音首字母是否大写','radio',4,'1:首字母大写\r\n0:全小写','0',1,50,1,0,1414711198,0,1),
	(134,'排行首页TKD','tkd_topindex_caption','排行首页','array',-1,'','{\"title\":\"\\u5c0f\\u8bf4\\u6392\\u884c - {sitename}\",\"keywords\":\"\\u6392\\u884c,{sitename}\",\"description\":\"{sitename}\\u4e13\\u6ce8\\u4e8e\\u7384\\u5e7b\\u5c0f\\u8bf4\\u641c\\u7d22,\\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66,\\u63d0\\u4f9b\\u6700\\u5168\\u7684\\u5c0f\\u8bf4\\u4fdd\\u6301\\u6700\\u5feb\\u7684\\u66f4\\u65b0,\\u65b9\\u4fbf\\u5927\\u5bb6\\u6109\\u5feb\\u5730\\u9605\\u8bfb\\u7384\\u5e7b\\u5c0f\\u8bf4\"}',0,50,1,0,1414882364,0,1),
	(135,'分类首页TKD','tkd_categoryindex_caption','分类首页','array',-1,'','{\"title\":\"\\u5c0f\\u8bf4\\u5206\\u7c7b - {sitename}\",\"keywords\":\"\\u5206\\u7c7b,{sitename}\",\"description\":\"{sitename}\\u4e13\\u6ce8\\u4e8e\\u7384\\u5e7b\\u5c0f\\u8bf4\\u641c\\u7d22,\\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66,\\u63d0\\u4f9b\\u6700\\u5168\\u7684\\u5c0f\\u8bf4\\u4fdd\\u6301\\u6700\\u5feb\\u7684\\u66f4\\u65b0,\\u65b9\\u4fbf\\u5927\\u5bb6\\u6109\\u5feb\\u5730\\u9605\\u8bfb\\u7384\\u5e7b\\u5c0f\\u8bf4\"}',0,50,1,0,1414882364,0,1),
	(136,'搜索首页TKD','tkd_searchindex_caption','搜索首页','array',-1,'','{\"title\":\"\\u641c\\u7d22 - {sitename}\",\"keywords\":\"\\u641c\\u7d22,{sitename}\",\"description\":\"{sitename}\\u4e13\\u6ce8\\u4e8e\\u7384\\u5e7b\\u5c0f\\u8bf4\\u641c\\u7d22,\\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66,\\u63d0\\u4f9b\\u6700\\u5168\\u7684\\u5c0f\\u8bf4\\u4fdd\\u6301\\u6700\\u5feb\\u7684\\u66f4\\u65b0,\\u65b9\\u4fbf\\u5927\\u5bb6\\u6109\\u5feb\\u5730\\u9605\\u8bfb\\u7384\\u5e7b\\u5c0f\\u8bf4\"}',0,50,1,0,1414882364,0,1),
	(137,'分类首页TKD','tkd_categoryindex','分类首页','array',0,'','{\"title\":\"\\u5c0f\\u8bf4\\u5206\\u7c7b - {$pt.config.sitename}\",\"keywords\":\"\\u5206\\u7c7b,{$pt.config.sitename}\",\"description\":\"{$pt.config.sitename}\\u4e13\\u6ce8\\u4e8e\\u7384\\u5e7b\\u5c0f\\u8bf4\\u641c\\u7d22,\\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66,\\u63d0\\u4f9b\\u6700\\u5168\\u7684\\u5c0f\\u8bf4\\u4fdd\\u6301\\u6700\\u5feb\\u7684\\u66f4\\u65b0,\\u65b9\\u4fbf\\u5927\\u5bb6\\u6109\\u5feb\\u5730\\u9605\\u8bfb\\u7384\\u5e7b\\u5c0f\\u8bf4\"}',1,50,1,0,1414883564,0,1),
	(139,'排行首页TKD','tkd_topindex','排行首页','array',0,'','{\"title\":\"\\u5c0f\\u8bf4\\u6392\\u884c - {$pt.config.sitename}\",\"keywords\":\"\\u6392\\u884c,{$pt.config.sitename}\",\"description\":\"{$pt.config.sitename}\\u4e13\\u6ce8\\u4e8e\\u7384\\u5e7b\\u5c0f\\u8bf4\\u641c\\u7d22,\\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66,\\u63d0\\u4f9b\\u6700\\u5168\\u7684\\u5c0f\\u8bf4\\u4fdd\\u6301\\u6700\\u5feb\\u7684\\u66f4\\u65b0,\\u65b9\\u4fbf\\u5927\\u5bb6\\u6109\\u5feb\\u5730\\u9605\\u8bfb\\u7384\\u5e7b\\u5c0f\\u8bf4\"}',1,50,1,0,1414883564,0,1),
	(140,'搜索首页TKD','tkd_searchindex','搜索首页','array',0,'','{\"title\":\"\\u641c\\u7d22 - {$pt.config.sitename}\",\"keywords\":\"\\u641c\\u7d22,{$pt.config.sitename}\",\"description\":\"{$pt.config.sitename}\\u4e13\\u6ce8\\u4e8e\\u7384\\u5e7b\\u5c0f\\u8bf4\\u641c\\u7d22,\\u65b0\\u7248\\u5feb\\u773c\\u770b\\u4e66,\\u63d0\\u4f9b\\u6700\\u5168\\u7684\\u5c0f\\u8bf4\\u4fdd\\u6301\\u6700\\u5feb\\u7684\\u66f4\\u65b0,\\u65b9\\u4fbf\\u5927\\u5bb6\\u6109\\u5feb\\u5730\\u9605\\u8bfb\\u7384\\u5e7b\\u5c0f\\u8bf4\"}',1,50,1,0,1414883564,0,1),
	(141,'分类首页URL','url_categoryindex','','text',-2,'','/sort/',0,9,1,1,1414883564,1414891953,1),
	(142,'排行首页URL','url_topindex','','text',-2,'','/top/',0,9,1,1,1414883564,1414891953,1),
	(143,'搜索首页URL','url_searchindex','','text',-2,'','/search/',0,9,1,1,1414883564,1414891953,1),
	(145,'wap快照合并','wap_tc2read','wap下快照阅读转为综合阅读','radio',1,'0:不使用\r\n1:使用','1',1,99,1,0,1413030317,0,1);

/*!40000 ALTER TABLE `ptcms_config` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ptcms_cron


DROP TABLE IF EXISTS `ptcms_cron`;

CREATE TABLE `ptcms_cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `param` text NOT NULL,
  `interval` varchar(10) NOT NULL DEFAULT '0',
  `lastruntime` int(11) unsigned DEFAULT '0',
  `create_user_id` int(11) unsigned DEFAULT '0' COMMENT '创建人',
  `update_user_id` int(11) unsigned DEFAULT '0' COMMENT '修改人',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8;

LOCK TABLES `ptcms_cron` WRITE;
/*!40000 ALTER TABLE `ptcms_cron` DISABLE KEYS */;

INSERT INTO `ptcms_cron` (`id`, `name`, `action`, `param`, `interval`, `lastruntime`, `create_user_id`, `update_user_id`, `create_time`, `update_time`, `status`)
VALUES
	(1,'主动提交','cron.task.ping','','60',1451287249,1,1,1414849120,1414852670,1),
	(2,'更新日周月点击数','cron.task.setvisit','','00:00:00',1451287249,1,1,1414849120,1414852670,1),
	(3,'23us规则(到目录)','cron.task.collect','34','10',1451370461,1,1,1447231231,1447319944,1);

/*!40000 ALTER TABLE `ptcms_cron` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ptcms_friendlink


DROP TABLE IF EXISTS `ptcms_friendlink`;

CREATE TABLE `ptcms_friendlink` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `logo` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL COMMENT '描述信息',
  `ordernum` smallint(5) unsigned NOT NULL DEFAULT '50',
  `color` varchar(20) NOT NULL COMMENT '颜色代码',
  `isbold` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否加粗',
  `create_user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建人',
  `update_user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改人',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1可用 0禁用',
  PRIMARY KEY (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

LOCK TABLES `ptcms_friendlink` WRITE;
/*!40000 ALTER TABLE `ptcms_friendlink` DISABLE KEYS */;

INSERT INTO `ptcms_friendlink` (`id`, `name`, `url`, `logo`, `description`, `ordernum`, `color`, `isbold`, `create_user_id`, `update_user_id`, `create_time`, `update_time`, `status`)
VALUES
	(1,'PTCMS工作室','http://www.ptcms.com/','','PTCMS官方网站',10,'red',1,1,1,1412859114,1413340746,1);

/*!40000 ALTER TABLE `ptcms_friendlink` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ptcms_module


DROP TABLE IF EXISTS `ptcms_module`;

CREATE TABLE `ptcms_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `key` varchar(50) DEFAULT NULL,
  `create_user_id` int(11) unsigned DEFAULT '0' COMMENT '创建人',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `system` tinyint(3) DEFAULT '0' COMMENT '1为系统内置模块',
  `status` tinyint(3) DEFAULT '1' COMMENT '模块状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`key`)
) ENGINE=myisam DEFAULT CHARSET=utf8;

LOCK TABLES `ptcms_module` WRITE;
/*!40000 ALTER TABLE `ptcms_module` DISABLE KEYS */;

INSERT INTO `ptcms_module` (`id`, `name`, `key`, `create_user_id`, `create_time`, `system`, `status`)
VALUES
	(1,'后台管理','admin',1,1411978787,1,1),
	(2,'网站前台','index',1,1411978787,1,1),
	(4,'友情链接','friendlink',1,1411978787,0,1),
	(19,'广告模块','ad',1,1413282868,0,1),
	(20,'单页模块','page',1,1411978787,1,1),
	(34,'小说搜索','novelsearch',1,1411978787,1,1),
	(35,'规则管理','rule',1,1411978787,1,1),
	(38,'附件模块','attachment',1,1414051757,0,1),
	(41,'用户模块','user',1,1413282868,1,1),
	(43,'计划任务','cron',1,1414853606,0,1),
	(44,'sitemap','sitemap',1,1415361253,0,1),
	(46,'安装程序','install',1,1415453699,0,1),
	(47,'作者模块','author',1,1435577412,0,1),
	(48,'api接口','api',1,1452176003,0,1),
	(50,'升级程序','update',1,1456627130,0,1);

/*!40000 ALTER TABLE `ptcms_module` ENABLE KEYS */;
UNLOCK TABLES;





DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_0`;

CREATE TABLE `ptcms_novelsearch_chapter_0` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL COMMENT '站点id',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '章节名',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '章节地址规则',
  `time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_1`;

CREATE TABLE `ptcms_novelsearch_chapter_1` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_10`;

CREATE TABLE `ptcms_novelsearch_chapter_10` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_11`;

CREATE TABLE `ptcms_novelsearch_chapter_11` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_12`;

CREATE TABLE `ptcms_novelsearch_chapter_12` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_13`;

CREATE TABLE `ptcms_novelsearch_chapter_13` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_14`;

CREATE TABLE `ptcms_novelsearch_chapter_14` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_15`;

CREATE TABLE `ptcms_novelsearch_chapter_15` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_16`;

CREATE TABLE `ptcms_novelsearch_chapter_16` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_17`;

CREATE TABLE `ptcms_novelsearch_chapter_17` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_18`;

CREATE TABLE `ptcms_novelsearch_chapter_18` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_19`;

CREATE TABLE `ptcms_novelsearch_chapter_19` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_2`;

CREATE TABLE `ptcms_novelsearch_chapter_2` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_20`;

CREATE TABLE `ptcms_novelsearch_chapter_20` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_21`;

CREATE TABLE `ptcms_novelsearch_chapter_21` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_22`;

CREATE TABLE `ptcms_novelsearch_chapter_22` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_23`;

CREATE TABLE `ptcms_novelsearch_chapter_23` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_24`;

CREATE TABLE `ptcms_novelsearch_chapter_24` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_25`;

CREATE TABLE `ptcms_novelsearch_chapter_25` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_26`;

CREATE TABLE `ptcms_novelsearch_chapter_26` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_27`;

CREATE TABLE `ptcms_novelsearch_chapter_27` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_28`;

CREATE TABLE `ptcms_novelsearch_chapter_28` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_29`;

CREATE TABLE `ptcms_novelsearch_chapter_29` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_3`;

CREATE TABLE `ptcms_novelsearch_chapter_3` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `novelid` int(10) unsigned NOT NULL,
  `oid` int(11) DEFAULT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_30`;

CREATE TABLE `ptcms_novelsearch_chapter_30` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_31`;

CREATE TABLE `ptcms_novelsearch_chapter_31` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_32`;

CREATE TABLE `ptcms_novelsearch_chapter_32` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_33`;

CREATE TABLE `ptcms_novelsearch_chapter_33` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_34`;

CREATE TABLE `ptcms_novelsearch_chapter_34` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_35`;

CREATE TABLE `ptcms_novelsearch_chapter_35` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_36`;

CREATE TABLE `ptcms_novelsearch_chapter_36` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_37`;

CREATE TABLE `ptcms_novelsearch_chapter_37` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_38`;

CREATE TABLE `ptcms_novelsearch_chapter_38` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_39`;

CREATE TABLE `ptcms_novelsearch_chapter_39` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_4`;

CREATE TABLE `ptcms_novelsearch_chapter_4` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_40`;

CREATE TABLE `ptcms_novelsearch_chapter_40` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_41`;

CREATE TABLE `ptcms_novelsearch_chapter_41` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_42`;

CREATE TABLE `ptcms_novelsearch_chapter_42` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_43`;

CREATE TABLE `ptcms_novelsearch_chapter_43` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_44`;

CREATE TABLE `ptcms_novelsearch_chapter_44` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_45`;

CREATE TABLE `ptcms_novelsearch_chapter_45` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_46`;

CREATE TABLE `ptcms_novelsearch_chapter_46` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_47`;

CREATE TABLE `ptcms_novelsearch_chapter_47` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_48`;

CREATE TABLE `ptcms_novelsearch_chapter_48` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_49`;

CREATE TABLE `ptcms_novelsearch_chapter_49` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_5`;

CREATE TABLE `ptcms_novelsearch_chapter_5` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_50`;

CREATE TABLE `ptcms_novelsearch_chapter_50` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_51`;

CREATE TABLE `ptcms_novelsearch_chapter_51` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_52`;

CREATE TABLE `ptcms_novelsearch_chapter_52` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_53`;

CREATE TABLE `ptcms_novelsearch_chapter_53` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_54`;

CREATE TABLE `ptcms_novelsearch_chapter_54` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_55`;

CREATE TABLE `ptcms_novelsearch_chapter_55` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_56`;

CREATE TABLE `ptcms_novelsearch_chapter_56` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_57`;

CREATE TABLE `ptcms_novelsearch_chapter_57` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_58`;

CREATE TABLE `ptcms_novelsearch_chapter_58` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_59`;

CREATE TABLE `ptcms_novelsearch_chapter_59` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_6`;

CREATE TABLE `ptcms_novelsearch_chapter_6` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_60`;

CREATE TABLE `ptcms_novelsearch_chapter_60` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_61`;

CREATE TABLE `ptcms_novelsearch_chapter_61` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_62`;

CREATE TABLE `ptcms_novelsearch_chapter_62` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_63`;

CREATE TABLE `ptcms_novelsearch_chapter_63` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_64`;

CREATE TABLE `ptcms_novelsearch_chapter_64` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_65`;

CREATE TABLE `ptcms_novelsearch_chapter_65` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_66`;

CREATE TABLE `ptcms_novelsearch_chapter_66` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_67`;

CREATE TABLE `ptcms_novelsearch_chapter_67` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_68`;

CREATE TABLE `ptcms_novelsearch_chapter_68` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_69`;

CREATE TABLE `ptcms_novelsearch_chapter_69` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_7`;

CREATE TABLE `ptcms_novelsearch_chapter_7` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_70`;

CREATE TABLE `ptcms_novelsearch_chapter_70` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_71`;

CREATE TABLE `ptcms_novelsearch_chapter_71` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_72`;

CREATE TABLE `ptcms_novelsearch_chapter_72` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_73`;

CREATE TABLE `ptcms_novelsearch_chapter_73` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_74`;

CREATE TABLE `ptcms_novelsearch_chapter_74` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_75`;

CREATE TABLE `ptcms_novelsearch_chapter_75` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_76`;

CREATE TABLE `ptcms_novelsearch_chapter_76` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_77`;

CREATE TABLE `ptcms_novelsearch_chapter_77` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_78`;

CREATE TABLE `ptcms_novelsearch_chapter_78` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_79`;

CREATE TABLE `ptcms_novelsearch_chapter_79` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_8`;

CREATE TABLE `ptcms_novelsearch_chapter_8` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;



DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_80`;

CREATE TABLE `ptcms_novelsearch_chapter_80` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;



DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_81`;

CREATE TABLE `ptcms_novelsearch_chapter_81` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_82`;

CREATE TABLE `ptcms_novelsearch_chapter_82` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;



DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_83`;

CREATE TABLE `ptcms_novelsearch_chapter_83` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;



DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_84`;

CREATE TABLE `ptcms_novelsearch_chapter_84` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;



DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_85`;

CREATE TABLE `ptcms_novelsearch_chapter_85` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;




DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_86`;

CREATE TABLE `ptcms_novelsearch_chapter_86` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;




DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_87`;

CREATE TABLE `ptcms_novelsearch_chapter_87` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;



DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_88`;

CREATE TABLE `ptcms_novelsearch_chapter_88` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;



DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_89`;

CREATE TABLE `ptcms_novelsearch_chapter_89` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;



DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_9`;

CREATE TABLE `ptcms_novelsearch_chapter_9` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;



DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_90`;

CREATE TABLE `ptcms_novelsearch_chapter_90` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;




DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_91`;

CREATE TABLE `ptcms_novelsearch_chapter_91` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;




DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_92`;

CREATE TABLE `ptcms_novelsearch_chapter_92` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;



DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_93`;

CREATE TABLE `ptcms_novelsearch_chapter_93` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;



DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_94`;

CREATE TABLE `ptcms_novelsearch_chapter_94` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;



DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_95`;

CREATE TABLE `ptcms_novelsearch_chapter_95` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;



DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_96`;

CREATE TABLE `ptcms_novelsearch_chapter_96` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;




DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_97`;

CREATE TABLE `ptcms_novelsearch_chapter_97` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;



DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_98`;

CREATE TABLE `ptcms_novelsearch_chapter_98` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;




DROP TABLE IF EXISTS `ptcms_novelsearch_chapter_99`;

CREATE TABLE `ptcms_novelsearch_chapter_99` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL,
  `siteid` smallint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `novelid_siteid` (`novelid`,`siteid`),
  KEY `novelid_oid` (`novelid`,`oid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;



DROP TABLE IF EXISTS `ptcms_novelsearch_info`;

CREATE TABLE `ptcms_novelsearch_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `pinyin` varchar(100) NOT NULL,
  `initial` char(1) NOT NULL,
  `author` varchar(30) NOT NULL DEFAULT '',
  `authorid` int(11) NOT NULL DEFAULT '0',
  `postdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cover` varchar(200) NOT NULL DEFAULT '',
  `caption` varchar(200) NOT NULL DEFAULT '',
  `intro` text NOT NULL,
  `categoryid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `lastchapterid` int(10) unsigned NOT NULL DEFAULT '0',
  `lastchaptername` varchar(100) NOT NULL DEFAULT '',
  `lastsiteid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `lastupdate` int(10) unsigned NOT NULL DEFAULT '0',
  `chaptersign` int(11) unsigned NOT NULL DEFAULT '0',
  `allvisit` int(10) unsigned NOT NULL DEFAULT '0',
  `monthvisit` int(10) unsigned NOT NULL DEFAULT '0',
  `weekvisit` int(10) unsigned NOT NULL DEFAULT '0',
  `dayvisit` int(10) unsigned NOT NULL DEFAULT '0',
  `votenum` int(10) unsigned NOT NULL DEFAULT '0',
  `downnum` int(10) unsigned NOT NULL DEFAULT '0',
  `marknum` int(10) unsigned NOT NULL DEFAULT '0',
  `isgood` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isover` tinyint(4) NOT NULL DEFAULT '1',
  `siteid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '0为原创 其他为转载',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pinyin` (`pinyin`),
  UNIQUE KEY `name` (`name`,`author`),
  KEY `lastupdate` (`lastupdate`),
  KEY `categoryid` (`categoryid`),
  KEY `siteid` (`siteid`),
  KEY `author` (`author`(3)),
  KEY `allvisit` (`allvisit`),
  KEY `monthvisit` (`monthvisit`),
  KEY `weekvisit` (`weekvisit`),
  KEY `marknum` (`marknum`),
  KEY `votenum` (`votenum`),
  KEY `downnum` (`downnum`)
) ENGINE=myisam DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `ptcms_novelsearch_ipcount`;

CREATE TABLE `ptcms_novelsearch_ipcount` (
  `siteid` smallint(6) unsigned NOT NULL,
  `date` char(10) NOT NULL,
  `ip` int(10) unsigned NOT NULL DEFAULT '0',
  `pv` int(10) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `date` (`date`,`siteid`)
) ENGINE=myisam DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `ptcms_novelsearch_log`;

CREATE TABLE `ptcms_novelsearch_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` smallint(11) unsigned NOT NULL COMMENT '站点id',
  `novelid` int(11) unsigned NOT NULL COMMENT '本站的id',
  `fromid` varchar(100) NOT NULL DEFAULT '' COMMENT '来源占id',
  `lastid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `siteid` (`siteid`,`fromid`),
  KEY `novelid` (`novelid`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `ptcms_novelsearch_site`;

CREATE TABLE `ptcms_novelsearch_site` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '站点id',
  `name` varchar(30) NOT NULL COMMENT '站点名',
  `key` varchar(20) NOT NULL COMMENT '站点关键词',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '站点地址',
  `isoriginal` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否原创 原创1 ',
  `style` varchar(255) DEFAULT '',
  `desc` varchar(255) DEFAULT '',
  `weight` tinyint(3) unsigned DEFAULT '100' COMMENT '站点评分，满分是100',
  `status` tinyint(3) unsigned DEFAULT '1',
  `ip` int(11) DEFAULT '0' COMMENT 'ip限制',
  `pv` int(11) DEFAULT '0' COMMENT 'pv限制',
  PRIMARY KEY (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO `ptcms_novelsearch_site` (`id`, `name`, `key`, `url`, `isoriginal`, `style`, `desc`, `weight`, `status`, `ip`, `pv`)
VALUES
	(9,'起点中文','qidian','http://www.qidian.com',1,'color:red','',30,1,0,0),
	(11,'纵横中文','zongheng','http://www.zonghong.com',1,'','',30,1,0,0),
	(12,'创世中文','chuangshi','http://chuangshi.qq.com/',1,'','',30,1,0,0),
	(14,'小说阅读网','readnovel','http://www.readnovel.com',1,'','',30,1,0,0),
	(16,'17K小说网','17k','http://www.17k.com',1,'','',30,1,0,0),
	(17,'红袖添香','hongxiu','http://www.hongxiu.com',1,'','',30,1,0,0),
	(19,'笔下文学','bxwx','http://www.bxwx.org',0,'','',70,1,0,0),
	(20,'大家读','dajiadu','http://www.dajiadu.net',0,'','',70,1,0,0),
	(21,'番茄小说网','fqxsw','http://www.fqxsw.com',0,'','',70,1,0,0),
	(22,'书书网','shushuw','http://www.shushuw.com',0,'','',70,1,0,0),
	(25,'顶点中文','23wx','http://www.23wx.com',0,'','',70,1,0,0),
	(26,'寻书网','xunshu','http://www.xunshu.org',0,'','',20,1,0,0),
	(28,'79文学','79wx','http://www.79wx.com/',0,'','',60,1,0,0),
	(29,'78小说','78xs','http://www.78xs.com/',0,'','',60,1,0,0),
	(30,'随梦小说','suimeng','http://www.suimeng.com/',0,'','',60,1,0,0),
	(31,'猪猪岛小说','zhuzhudao','http://www.zhuzhudao.com/',0,'','',60,1,0,0),
	(32,'要看书','1kanshu','http://www.1kanshu.com/',0,'','',60,1,0,0),
	(33,'雅文言情','yawen8','http://www.yawen8.com/',0,'','',60,1,0,0),
	(34,'E小说','exiaoshuo','http://www.exiaoshuo.com/',0,'','',60,1,0,0),
	(35,'乐文小说','365xs','http://www.365xs.org/',0,'','',60,1,0,0),
	(36,'新笔趣阁','xbiquge','http://www.xbiquge.com/',0,'','',70,1,0,0),
	(37,'飘天文学','piaotian','http://www.piaotian.net/',0,'','',70,1,0,0),
	(38,'小说下载网','xshuotxt','http://www.xshuotxt.com/',0,'','',10,1,0,0),
	(39,'百书楼','baishulou','http://www.baishulou.net/',0,'','',10,1,0,0),
	(40,'风华居','fenghuaju','http://www.fenghuaju.com',0,'','',10,1,0,0),
	(41,'少年文学','snwx','http://www.snwx.com/',0,'','',10,1,0,0);



DROP TABLE IF EXISTS `ptcms_page`;

CREATE TABLE `ptcms_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL DEFAULT '',
  `key` varchar(50) NOT NULL DEFAULT '',
  `litpic` varchar(150) NOT NULL DEFAULT '',
  `ordernum` smallint(6) NOT NULL DEFAULT '50',
  `type` tinyint(3) DEFAULT '1',
  `content` text NOT NULL,
  `create_user_id` int(11) unsigned DEFAULT '0' COMMENT '创建人',
  `update_user_id` int(11) unsigned DEFAULT '0' COMMENT '修改人',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(3) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `key` (`key`),
  KEY `ordernum` (`ordernum`)
) ENGINE=myisam DEFAULT CHARSET=utf8;



INSERT INTO `ptcms_page` (`id`, `name`, `key`, `litpic`, `ordernum`, `type`, `content`, `create_user_id`, `update_user_id`, `create_time`, `update_time`, `status`)
VALUES
	(1,'关于我们','about','',10,2,'<p>{$pt.config.sitename}定位于为网民提供优质的小说、电子书资源服务，{$pt.config.sitename}spider自动抓取互联网小说网站数据进行资源整合。为网民提供更舒适的阅读体验。</p>',1,1,1414588472,1446708055,1),
	(3,'免责声明','disdaimer','',30,2,'<p>鉴于本服务以非人工检索方式提供无线搜索、根据您输入的关键字自动生成到第三方网页的链接，本服务会提供与其他任何互联网网站或资源的链接。由于{$pt.config.sitename}无法控制这些网站或资源的内容，您了解并同意：无论此类网站或资源是否可供利用，{$pt.config.sitename}不予负责；{$pt.config.sitename}亦对存在或源于此类网站或资源之任何内容、广告、产品或其他资料不予保证或负责。因您使用或依赖任何此类网站或资源发布的或经由此类网站或资源获得的任何内容、商品或服务所产生的任何损害或损失，{$pt.config.sitename}不负任何直接或间接责任。</p>\r\n<p>因本服务搜索结果根据您键入的关键字自动搜索获得并生成，不代表{$pt.config.sitename}赞成被搜索链接到的第三方网页上的内容或立场。</p>\r\n<p>任何通过使用本服务而搜索链接到的第三方网页均系第三方提供或制作，您可能从该第三方网页上获得资讯及享用服务，{$pt.config.sitename}无法对其合法性负责，亦不承担任何法律责任。</p>\r\n<p>您应对使用无线搜索引擎的结果自行承担风险。{$pt.config.sitename}不做任何形式的保证：不保证搜索结果满足您的要求，不保证搜索服务不中断，不保证搜索结果的安全性、准确性、及时性、合法性。因网络状况、通讯故障、第三方网站等任何原因而导致您不能正常使用本服务的，{$pt.config.sitename}不承担任何法律责任。</p>\r\n<p>您应该了解并知晓，{$pt.config.sitename}作为移动互联网的先行者，拥有先进的无线数据应用技术和智能搜索系统，为手机等无线端用户提供了移动互联网的最佳搜索体验。{$pt.config.sitename}使用行业内成熟的搜索引擎技术，同时充分考虑用户手机端上网特征，由于电脑端网页的复杂、多样与标准的不同，用户无法通过手机正常浏览电脑端网页，为了提供更好的用户体验，用户在搜索点击后，我们网页会提供转码，这就是网页实时转换技术，将页面转换为适于手机用户访问的页面，从而为用户提供可用、高效的搜索服务。由于搜索引擎对数据即时性和客观性的要求，和复杂的数据变更以及本身的技术问题，在转码的过程中可能会出现原网站的部门数据异常而导致部分数据错误，若您想获取完整的原网站完整有效的内容，您应选择去原网站浏览，介于此类技术问题，{$pt.config.sitename}一直在不断的完善搜索技术，以提高数据的准确性。</P>\r\n<p>您使用本服务即视为您已阅读并同意受本声明内容的相关约束。{$pt.config.sitename}有权在根据具体情况进行修改本声明条款。对此，我们不会有专门通知，但，您可以在相关页面中查阅最新的条款。条款变更后，如果您继续使用本服务，即视为您已接受修改后的条款。如果您不接受，应当停止使用本服务。</p>\r\n<p>本声明内容同时包括《{$pt.config.sitename}软件服务协议》，《版权保护投诉指引》及{$pt.config.sitename}可能不断发布本服务的相关声明、协议、业务规则等内容。上述内容一经正式发布，即为本声明不可分割的组成部分，您同样应当遵守。上述内容与本声明内容存在冲突的，以本声明为准。您对前述任何业务规则、声明内容的接受，即视为您对本声明内容全部的接受。</p>\r\n<p>本声明的成立、生效、履行、解释及纠纷解决，适用中华人民共和国大陆地区法律（不包括冲突法）。</p>\r\n<p>若您和{$pt.config.sitename}之间发生任何纠纷或争议，首先应友好协商解决；协商不成的，您同意将纠纷或争议提交{$pt.config.sitename}所在地的人民法院处理。</p>',1,1,1414588610,1415355126,1),
	(4,'蜘蛛协议','spider','',50,2,'<p>\r\n	由于服务器及带宽资源有限，我们会尽力的多收录专业的小说站点，如果您的站点尚未收录，欢迎参考“申请收录”页面来提交您的申请。\r\n</p>\r\n<p>\r\n	任何网站如果不想被本服务收录（即不被搜索到），应及时向{$pt.config.sitename}反映，或者在其网站页面中根据拒绝蜘蛛协议（Robots Exclusion Protocol）加注拒绝收录的标记，否则，本服务将依照惯例视其为可收录网站。\r\n</p>',1,1,1414588640,1416018885,1),
	(5,'联系我们','contact','',70,2,'<p>\r\n	如果您有问题，可以通过以下联系方式联系我们。\r\n</p>\r\n<p>\r\n	QQ:{$pt.config.qq}\r\n</p>\r\n<p>\r\n	Email:{$pt.config.email}\r\n</p>\r\n',1,1,1414588732,1415354579,1),
	(6,'隐私条款','privacy','',40,2,'<p>尊重用户个人信息的私有性是{$pt.config.sitename}的一贯制度，除法律或有法律赋予权限的政府部门要求或用户同意等原因外，{$pt.config.sitename}未经用户同意不向除合作单位以外的第三方公开、 透露用户个人信息。 但是用户在注册时选择或同意，或用户与{$pt.config.sitename}及合作单位之间就用户个人信息公开或使用另有约定的除外，同时用户应自行承担因此可能产生的任何风险，{$pt.config.sitename}对此不予负责。同时为了运营和改善{$pt.config.sitename}的技术和服务，我们将可能会使用或向第三方提供用户的非个人身份信息，这将有助于向用户提供更好的用户体验和提高我们的服务质量。</p>\r\n<p>cookies等技术的使用，本服可能会使用cookies，以便您能登录并使用我们的服务，并允许设定您特定的服务选项。运用cookies技术，我们能够为您提供更加周到的个性化服务。通过使用cookies所收集的任何内容均以集合的、匿名的方式进行。您可以选择接受或拒绝 cookies。您可以通过修改浏览器设置的方式拒绝cookies。如果您选择拒绝cookies，则您可能无法登录或使用依赖于cookies 的服务或功能。</p>\r\n<p>本服务需要用户共同享用、维护其所提供的利益，用户在此确认同意本服务在必要时使用您计算机的处理器和带宽等资源，用作容许其它本\"软件\"使用者与您通讯联络、分享本服务的有限目的。此项同意可能会影响用户的使用感受和带来不可预知的风险。您应认真考虑并做出选择，承担风险。用户同意本\"软件\"将会尽其合理努力以保护您的计算机资源及计算机通讯的隐私性和完整性，但是，您承认和同意{$pt.config.sitename}不能就此事提供任何保证。</p>\r\n<p>保有您的使用记录，当您使用搜索的服务时，服务器会自动记录一些信息，包括URL、IP地址、浏览器的类型和使用的语言以及访问日期和时间等。</p>\r\n<p>{$pt.config.sitename}尊重并保护所有使用您的个人身份信息，您注册的用户名、电子邮件地址等个人资料，但{$pt.config.sitename}提醒您：您在使用搜索引擎时输入的关键字将不被认为是您的个人信息资料。</p>\r\n<p>在如下情况下，会遵照您的意愿或法律的规定披露您的个人信息，由此引发的问题将由您个人承担：</p>\r\n<p>（1）事先获得您的授权；</p>\r\n<p>（2）只有透露你的个人资料，才能提供你所要求的产品和服务；</p>\r\n<p>（3）根据有关的法律法规和按照相关政府主管部门的要求；</p>\r\n<p>（4）我们发现您违反了{$pt.config.sitename}公司服务条款或任何其他产品服务的使用规定。</p>\r\n<p>（5）其他可合法披露您个人信息的情形。</p>',1,1,1414588896,1415354333,1),
	(7,'申请收录','employ','',60,2,'<p>我们的收录是完全免费的，所有小说网站站长都可以联系我们提交免费收录申请（无弹窗广告优先收录），只要您的网站符合以下收录原则，都可以联系我们：</p>\r\n<p>1.不收录有反动、色情、赌博等不良内容或提供不良内容链接的网站，以及网站名称或内容违反国家有关法规的网站</p>\r\n<p>2.不收录无实用内容的网站，如您的网站正在建设中，尚无完整内容，请不必现在登录，欢迎您在网站建设完成后再来登录</p>\r\n<p>3.不收录含有病毒、木马，弹出插件或恶意更改他人电脑设置的网站及有多个弹窗广告的网站</p>\r\n<p>4.对挂靠别人的网站下的网站 ( 即没有自己单独域名的网站 ) ，本站将不予收录</p>\r\n<p>5.不收录在正常情况下无法正常连接或浏览的网站</p>\r\n<p>6.本站保留收录决定权以及在本站网址数据库中相关内容的编辑决定权</p>\r\n<p>\r\n如果您认为您符合上面的要求，请按照如下格式给我们邮箱发邮件申请{$pt.config.email}<br />\r\n</p>\r\n<div style=\"border:1px dashed #ccc;margin:0 30px;padding:10px 20px;\">	\r\n网站名称：<br />\r\n网站地址：<br />\r\n更新列表更新间隔：<br />\r\n更新列表页地址：(如果您站点开启了缓存，请提供一个私密的没有缓存的更新列表页)<br />\r\n小说信息页地址：(任意一本书)<br />\r\n小说目录页地址：(任意一本书)<br />\r\n小说章节页地址：(任意一本书)<br />\r\n</div>\r\n<p>请注意，如果您网站启用了防采集措施，请给我们提供一个私密的没有防采集及缓存的的地址，我们确保不会对外进行泄漏。<br />\r\n</p>',1,1,1414588987,1416018695,1);


DROP TABLE IF EXISTS `ptcms_rule`;

CREATE TABLE `ptcms_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `addnew` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `newreplace` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `collectallchapter` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dirsort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `siteid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `url_list` text NOT NULL,
  `url_info` text NOT NULL,
  `url_dir` text NOT NULL,
  `list_novelid` text NOT NULL,
  `list_novelname` text NOT NULL,
  `list_lastid` text NOT NULL,
  `info_novelname` text NOT NULL,
  `info_author` text NOT NULL,
  `info_category` text NOT NULL,
  `info_intro` text NOT NULL,
  `info_cover` text NOT NULL,
  `info_isover` text NOT NULL,
  `dir_chapterid` text NOT NULL,
  `dir_chaptername` text NOT NULL,
  `dir_chapterurl` text NOT NULL,
  `chapter_api` text NOT NULL,
  `chapter_content` text NOT NULL,
  `create_user_id` int(11) unsigned DEFAULT '0' COMMENT '创建人',
  `update_user_id` int(11) unsigned DEFAULT '0' COMMENT '修改人',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8;



INSERT INTO `ptcms_rule` (`id`, `name`, `addnew`, `newreplace`, `collectallchapter`, `dirsort`, `siteid`, `url_list`, `url_info`, `url_dir`, `list_novelid`, `list_novelname`, `list_lastid`, `info_novelname`, `info_author`, `info_category`, `info_intro`, `info_cover`, `info_isover`, `dir_chapterid`, `dir_chaptername`, `dir_chapterurl`, `chapter_api`, `chapter_content`, `create_user_id`, `update_user_id`, `create_time`, `update_time`)
VALUES
	(34,'23us规则',1,0,1,0,25,'{\"charset\":\"auto\",\"delay\":0,\"error\":\"\",\"rule\":\"http://www.23wx.com/\",\"replace\":[]}','{\"charset\":\"auto\",\"delay\":0,\"error\":\"\",\"rule\":\"http://www.23wx.com/book/[novelid]\",\"replace\":[]}','{\"charset\":\"auto\",\"delay\":0,\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/rule/getdir.xml?site=23wx&novelid=[novelid]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a class=\\\"poptext\\\" href=\\\"http://www.23wx.com/book/[内容]\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a class=\\\"poptext\\\"[参数]>[内容]</a>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<p class=\\\"ul2\\\"><a href=\\\"http://www.23wx.com/html/[数字]/[数字]/[内容].html\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<meta name=\\\"keywords\\\" content=\\\"[内容]\\\" />\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<th>文章作者</th><td>&nbsp;[内容]</td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<th>文章类别</th><td>&nbsp;<a href=\\\"[属性]\\\">[内容]</a></td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"</table>[空白]<p>[内容]</p>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<img style=[参数]src=\\\"[内容]\\\"/>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<th>文章状态</th><td>&nbsp;(.+?)</td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chapterid>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterid>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chaptername>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chaptername>\",\"replace\":[]}','{\"charset\":\"auto\",\"delay\":0,\"error\":\"\",\"rule\":\"<chapterurl>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterurl>\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/chapter/get.xml?url=[url]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<content>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</content>\",\"replace\":[]}',1,1,1415431292,1448958495),
	(35,'起点中文主站vip',1,0,1,0,9,'{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/rule/getlist.xml?site=qidian\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/rule/getinfo.xml?site=qidian&novelid=[novelid]\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/rule/getdir.xml?site=qidian&novelid=[novelid]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<novelid>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</novelid>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<novelname>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</novelname>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chapterid>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterid>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<novelname>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</novelname>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<author>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</author>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<category>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</category>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<intro>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</intro>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<cover>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</cover>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<isover>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</isover>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chapterid>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterid>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chaptername>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chaptername>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<chapterurl>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterurl>\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/chapter/get.xml?url=[url]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<content>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</content>\",\"replace\":[]}',1,1,1415431945,1448958911),
	(36,'起点女生vip监控',1,0,1,0,9,'{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/rule/getlist.xml?site=qdmm\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/rule/getinfo.xml?site=qdmm&novelid=[novelid]\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/rule/getdir.xml?site=qidian&novelid=[novelid]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<novelid>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</novelid>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<novelname>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</novelname>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chapterid>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterid>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<novelname>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</novelname>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<author>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</author>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<category>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</category>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<intro>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</intro>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<cover>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</cover>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<isover>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</isover>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chapterid>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterid>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chaptername>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chaptername>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<chapterurl>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterurl>\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/chapter/get.xml?url=[url]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<content>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</content>\",\"replace\":[]}',1,1,1415431945,1448959123),
	(37,'纵横中文监控',1,0,1,0,11,'{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/rule/getlist.xml?site=zongheng\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/rule/getinfo.xml?site=zongheng&novelid=[novelid]\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/rule/getdir.xml?site=zongheng&novelid=[novelid]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<novelid>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</novelid>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<novelname>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</novelname>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chapterid>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterid>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<novelname>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</novelname>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<author>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</author>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<category>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</category>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<intro>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</intro>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<cover>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</cover>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<isover>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</isover>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chapterid>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterid>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chaptername>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chaptername>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<chapterurl>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterurl>\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/chapter/get.xml?url=[url]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<content>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</content>\",\"replace\":[]}',1,1,1415431945,1448959185),
	(38,'创世中文监控',1,0,1,0,12,'{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/rule/getlist.xml?site=chuangshi\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/rule/getinfo.xml?site=chuangshi&novelid=[novelid]\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/rule/getdir.xml?site=chuangshi&novelid=[novelid]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<novelid>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</novelid>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<novelname>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</novelname>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chapterid>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterid>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<novelname>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</novelname>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<author>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</author>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<category>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</category>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<intro>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</intro>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<cover>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</cover>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<isover>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</isover>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chapterid>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterid>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chaptername>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chaptername>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<chapterurl>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterurl>\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/chapter/get.xml?url=[url]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<content>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</content>\",\"replace\":[]}',1,1,1415431945,1448959802),
	(40,'17K小说网监控',1,0,1,0,16,'{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/rule/getlist.xml?site=17k\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/rule/getinfo.xml?site=17k&novelid=[novelid]\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/rule/getdir.xml?site=17k&novelid=[novelid]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<novelid>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</novelid>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<novelname>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</novelname>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chapterid>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterid>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<novelname>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</novelname>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<author>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</author>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<category>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</category>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<intro>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</intro>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<cover>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</cover>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<isover>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</isover>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chapterid>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterid>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chaptername>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chaptername>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<chapterurl>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterurl>\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/chapter/get.xml?url=[url]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<content>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</content>\",\"replace\":[]}',1,1,1415431945,1448960604),
	(41,'红袖添香采集规则',1,0,1,0,17,'{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/rule/getlist.xml?site=hongxiu\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/rule/getinfo.xml?site=hongxiu&novelid=[novelid]\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/rule/getdir.xml?site=hongxiu&novelid=[novelid]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<novelid>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</novelid>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<novelname>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</novelname>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chapterid>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterid>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<novelname>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</novelname>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<author>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</author>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<category>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</category>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<intro>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</intro>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<cover>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</cover>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<isover>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</isover>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chapterid>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterid>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chaptername>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chaptername>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<chapterurl>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterurl>\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/chapter/get.xml?url=[url]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<content>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</content>\",\"replace\":[]}',1,1,1415431945,1448960675),
	(43,'笔下文学-采集规则',1,0,1,1,19,'{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"http://www.bxwx.org/modules/article/toplist.php?sort=lastupdate\",\"replace\":[]}','{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"http://www.bxwx.org/modules/article/articleinfo.php?id=[novelid]\",\"replace\":[]}','{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"http://www.bxwx.org/b/[subnovelid]/[novelid]/index.html\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td class=\\\"odd\\\"><a href=\\\"/binfo/[数字]/[内容].htm\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td class=\\\"odd\\\"><a href=\\\"/binfo/[数字]/[数字].htm\\\">[内容] 下载\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a href=\\\"http://www.bxwx.org/b/[数字]/[数字]/[内容].html\\\" target=\\\"_blank\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"keywords\\\" content=\\\"(.+?)下载\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"searchtype=author&searchkey=[内容]\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a href=\\\"/index.php\\\\?class=\\\\d+\\\">[内容]</\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div align=\\\"left\\\">[内容]</div>\\\\s*<br></td>\",\"replace\":[{\"search\":\"bxwx.Org\",\"$$hashKey\":\"object:3\"},{\"search\":\"wWw.BxWx.Org\",\"$$hashKey\":\"object:5\"}]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"class=\\\"picborder\\\" src=\\\"[内容]\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td>文章状态 ：</td>\\\\s*<td>(.+?)</td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a href=\\\"(\\\\d+).html\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a href=\\\"[数字].html\\\">[内容]</a>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<a href=\\\"(\\\\d+.html)\\\">\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/chapter/get.xml?url=[url]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<content>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</content>\",\"replace\":[]}',1,1,1415431945,1448961420),
	(44,'大家读-采集规则',1,0,1,0,20,'{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"http://www.dajiadu.net/modules/article/toplist.php?sort=lastupdate\",\"replace\":[]}','{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"http://www.dajiadu.net/files/article/info/[subnovelid]/[novelid].htm\",\"replace\":[]}','{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"http://www.dajiadu.net/files/article/html/[subnovelid]/[novelid]/index.html\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"http://www.dajiadu.net/files/article/info/\\\\d+/(\\\\d+).htm\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"http://www.dajiadu.net/files/article/info/\\\\d+/\\\\d+.htm\\\">[内容]</a>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"http://www.dajiadu.net/files/article/html/\\\\d+/\\\\d+/(\\\\d+).html\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<title>小说[内容]最新章节\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"作&nbsp;&nbsp;&nbsp; 者：[内容]</td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"类&nbsp;&nbsp;&nbsp; 别：[内容]</td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<span class=\\\"hottext\\\">内容简介：</span>[内容]</td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"valign=\\\"top\\\">\\\\s*<img src=\\\"[内容]\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td bgcolor=\\\"#FFFFFF\\\">文章状态：(.+?)</td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a href=\\\"(\\\\d+).html\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a href=\\\"\\\\d+.html\\\" alt=\\\"(.+?)\\\"\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<a href=\\\"(\\\\d+.html)\\\"\",\"replace\":[],\"ignorecase\":false}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/chapter/get.xml?url=[url]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<content>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</content>\",\"replace\":[]}',1,1,1415431945,1454464604),
	(45,'番茄小说网-采集规则',1,0,1,0,21,'{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"http://www.fqxsw.com/modules/article/toplist.php?sort=lastupdate\",\"replace\":[]}','{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"http://www.fqxsw.com/book/[novelid]/\",\"replace\":[]}','{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"http://www.fqxsw.com/book/[novelid]/\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div class=\\\"c1\\\"><a href=\\\"/book/(.+?)/\\\" target=\\\"_blank\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div class=\\\"c1\\\"><a href=\\\"/book/.+?/\\\" target=\\\"_blank\\\">(.+?)</a></div>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div class=\\\"c3\\\"><a href=\\\"/html/\\\\d+/(\\\\d+).html\\\" target=\\\"_blank\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<title>[内容]最新章节\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"作者[内容]写的\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"分类：[内容]</span>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"class=\\\"sayimg\\\" />[内容]<div class=\\\"clear\\\"></div>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"简介</div>\\\\s*<img src=\\\"[内容]\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"分类：[内容]</span>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<span><a href=\\\"/html/\\\\d+/(\\\\d+).html\\\">.+?</a></span>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<span><a href=\\\"/html/\\\\d+/\\\\d+.html\\\">(.+?)</a></span>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<span><a href=\\\"(/html/\\\\d+/\\\\d+.html)\\\">.+?</a></span>\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/chapter/get.xml?url=[url]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<content>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</content>\",\"replace\":[]}',1,1,1415431945,1448962090),
	(47,'寻书网-采集规则',1,0,1,0,26,'{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"http://www.xunshu.org/\",\"replace\":[]}','{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"http://www.xunshu.org/shu/[novelid].htm\",\"replace\":[]}','{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"http://www.xunshu.org/xunshu/[subnovelid]/[novelid]/index.html\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"</a>] 《<a href=\\\"http://www.xunshu.org/shu/(\\\\d+).htm\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"《<a href=\\\"http://www.xunshu.org/shu/\\\\d+.htm\\\" target=\\\"_blank\\\">(.+?)</a>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a href=\\\"http://www.xunshu.org/xunshu/\\\\d+/\\\\d+/(\\\\d+).html\\\" target=\\\"_blank\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<title>[内容]5200\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"提供[内容]写的\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"类&nbsp;&nbsp;&nbsp; 别：[内容]</td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<!--11-->[内容]<!--22-->\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"valign=\\\"top\\\">\\\\s*<img src=\\\"[内容]\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"类&nbsp;&nbsp;&nbsp; 别：[内容]</td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a href=\\\"(\\\\d+).html\\\" class=\\\"STYLE7\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a href=\\\"\\\\d+.html\\\" class=\\\"STYLE7\\\">(.+?)</a\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<a href=\\\"(\\\\d+.html)\\\" class=\\\"STYLE7\\\">\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/chapter/get.xml?url=[url]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<content>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</content>\",\"replace\":[]}',1,1,1415431945,1448962329),
	(51,'79文学',1,0,1,0,28,'{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.79xs.com/book/ShowBookList.aspx\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.79xs.com/Book/[novelid]/Index.shtml\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.79xs.com/Book/Xml.aspx?id=[novelid]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"TXT</a>] <a title=\\\"[参数][空白]\\\" href=\\\"/Book/[内容]/Index.shtml\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"TXT</a>] <a title=\\\"[内容][空白]\\\" href=\\\"/Book/[数字]/Index.shtml\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"- <a href=\\\"/Html/Book/[数字]/[数字]/[内容].html\\\" target=\\\"_blank\\\">[属性]</a><span class=\\\"yl_red\\\"></span>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<h1> 《[内容]》<span>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"content=\\\"欢迎光临本站阅读[内容]的小说\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a href=\\\"/Book/LC/[数字].shtml\\\" title=\\\"[内容]\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<h3><p>[内容]</p></h3>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div class=\\\"img\\\"><img src=\\\"[内容]\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td>写作进程：<span>(.+?)</span>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<ChapterUrl>/Html/Book/\\\\d+/\\\\d+/(\\\\d+).html</ChapterUrl>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<ChapterTitle>(.+?)</ChapterTitle>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<ChapterUrl>(/Html/Book/\\\\d+/\\\\d+/\\\\d+.html)</ChapterUrl>\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/chapter/get.xml?url=[url]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<content>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</content>\",\"replace\":[]}',1,1,1416705687,1455508735),
	(52,'78小说',1,0,1,0,29,'{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.78xs.com/Book/ShowBookList.aspx\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<a class=\\\"f14\\\" href=\\\"[内容]\\\">[参数]</a>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.78xs.com/Book/Xml.aspx?id=[novelid]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a class=\\\"f14\\\" href=\\\"/Book/[内容].aspx\\\">[参数]</a>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a class=\\\"f14\\\" href=\\\"/Book/[数字].aspx\\\">[内容]</a>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"</a> <a href=\\\"/article/[数字]/[数字]/[内容].shtml\\\">[参数]</a></li>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<h1>[内容][空白]<a\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<strong>作者：</strong><a href=\\\"[属性]\\\">[内容]</a>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a href=\\\"/Book/LN/[数字].aspx\\\">[内容]</a>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"</a></div>[空白]<p>[内容]</p>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div class=\\\"bortable wleft\\\">[空白]<img src=\\\"[内容]\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td><strong>写作进程：</strong>(.+?)</td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<ChapterUrl>/article/\\\\d+/\\\\d+/(\\\\d+).shtml</ChapterUrl>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<ChapterTitle>(.+?)</ChapterTitle>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<ChapterUrl>(/article/\\\\d+/\\\\d+/\\\\d+.shtml)</ChapterUrl>\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/chapter/get.xml?url=[url]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<content>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</content>\",\"replace\":[]}',1,1,1416706404,1448962664),
	(53,'猪猪岛',1,0,1,0,31,'{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.zhuzhudao.com/top/lastupdate_1/\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.zhuzhudao.com/info/[novelid]/\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/rule/getdir.xml?site=zhuzhudao&novelid=[novelid]\",\"replace\":[{\"search\":\"<dd>\\\\s*<a href=\\\"(http://www.zhuzhudao.com/txt/\\\\d+/\\\\d+/)\\\">[^\\\\<]+?重要公告\",\"type\":\"1\"}]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<h2><a href=\\\"http://www.zhuzhudao.com/info/(\\\\d+)/\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<h2><a href=\\\"http://www.zhuzhudao.com/info/[数字]/\\\" title=\\\"[内容]\\\"\",\"replace\":[]}','{\"singleline\":\"1\",\"ignorecase\":\"\",\"rule\":\"<a href=\\\"http://www.zhuzhudao.com/txt/[数字]/[内容]/\\\" target=\\\"_blank\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<h1>[内容]</h1>\",\"replace\":[]}','{\"singleline\":\"1\",\"ignorecase\":\"\",\"rule\":\"作者：[内容]</div>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"类别：<a href=\\\"http://www.zhuzhudao.com/txt/[属性]/\\\">[内容]</a>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div class=\\\"intro\\\">[内容]</div>\",\"replace\":[]}','{\"singleline\":false,\"ignorecase\":\"\",\"rule\":\"<div class=\\\"book_img\\\">[任意]<img src=\\\"[内容]\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"类别：<a href=\\\"http://www.zhuzhudao.com/txt/[属性]/\\\">[内容]</a>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chapterid>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterid>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<chaptername>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chaptername>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<chapterurl>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</chapterurl>\",\"replace\":[],\"singleline\":\"1\"}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/chapter/get.xml?url=[url]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<content>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</content>\",\"replace\":[]}',1,1,1416707896,1449050629),
	(54,'随梦小说',1,0,1,0,30,'{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.suimeng.la/lastupdate-1.html\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.suimeng.la/files/article/html/[subnovelid]/[novelid]/index.html\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.suimeng.la/files/article/html/[subnovelid]/[novelid]/index.html\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a class=\\\"aname\\\" href=\\\"http://www.suimeng.la/files/article/html/[数字]/[内容]/\\\" target=\\\"_blank\\\">[参数]</a>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a class=\\\"aname\\\" href=\\\"http://www.suimeng.la/files/article/html/[数字]/[数字]/\\\" target=\\\"_blank\\\">[内容]</a>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<p><a href=\\\"http://www.suimeng.la/files/article/html/[数字]/[数字]/[内容].html\\\" target=\\\"_blank\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<h2 class=\\\"ratitle\\\">[内容]<\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"作者：(.+?)　　类别\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"类别：(.+?)　　状态\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div class=\\\"gray\\\">[内容]</div>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<img alt=\\\"[属性]\\\" src=\\\"[内容]\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"类别：(.+?)　　状态\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a href=\\\"(\\\\d+).html\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a href=\\\"\\\\d+.html\\\">(.+?)<\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<a href=\\\"(\\\\d+.html)\\\">\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/chapter/get.xml?url=[url]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<content>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</content>\",\"replace\":[]}',1,1,1416709835,1449050933),
	(55,'要看书1kanshucom',1,0,1,1,32,'{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.1kanshu.cc/\",\"replace\":[]}','{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"http://www.1kanshu.cc/modules/article/articleinfo.php?id=[novelid]\",\"replace\":[]}','{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"http://www.1kanshu.cc/files/article/html/[subnovelid]/[novelid]/\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a class=\\\"poptext\\\" href=\\\"http://www.1kanshu.cc/files/article/html/[数字]/[内容]/\\\" target=\\\"_blank\\\" title=\\\"[属性]\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a class=\\\"poptext\\\" href=\\\"http://www.1kanshu.cc/files/article/html/[数字]/[数字]/\\\" target=\\\"_blank\\\" title=\\\"[内容]\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"</a> <a href=\\\"http://www.1kanshu.cc/files/article/html/[数字]/[数字]/[内容].html\\\" target=\\\"_blank\\\">[参数]</a></li><li class=\\\"fr tr\\\">\",\"replace\":[]}','{\"singleline\":\"1\",\"ignorecase\":\"\",\"rule\":\"<h1 class=\\\"f20h\\\">[内容]<em>\",\"replace\":[]}','{\"singleline\":\"1\",\"ignorecase\":\"\",\"rule\":\"<em>作者：[内容]</em></h1>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<b>小说分类：</b>[内容]</td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div class=\\\"intro\\\" style=\\\"text-indent:2em;\\\">[内容]</div>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div class=\\\"pic\\\">[空白]<img src=\\\"[内容]\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<b>小说状态：</b>(.+?)</td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a href=\\\"http://www.1kanshu.(?:cc|com)/files/article/html/\\\\d+/\\\\d+/(\\\\d+).html\\\">.+?</a>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a href=\\\"http://www.1kanshu.(?:cc|com)/files/article/html/\\\\d+/\\\\d+/\\\\d+.html\\\">(.+?)</a>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<a href=\\\"(http://www.1kanshu.(?:cc|com)/files/article/html/\\\\d+/\\\\d+/\\\\d+.html)\\\">.+?</a>\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/chapter/get.xml?url=[url]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<content>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</content>\",\"replace\":[]}',1,1,1416711071,1449051000),
	(56,'雅文言情',1,0,1,0,33,'{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.yawen8.com/top/lastupdate.html\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.yawen8.com/[novelid]/\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.yawen8.com/[novelid]/\",\"replace\":[{\"search\":\"雅文言情小说(www.yawen8.com)\",\"$$hashKey\":\"object:4\"},{\"search\":\"(www.yawen8.com)\"}]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td><a href=\\\"http://www.yawen8.com/(\\\\w+/\\\\d+)/\\\">[参数]</a></td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td><a href=\\\"http://www.yawen8.com/[a-z]+/\\\\d+/\\\">[内容]</a>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td><a href=\\\"http://www.yawen8.com/[参数]/[数字]/(\\\\d+).html\\\" target=\\\"_blank\\\">[参数]</a></td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<h2 class=\\\"bookName\\\">《[内容]》<em>作者：\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"作者：<a href=\\\"http://www.yawen8.com/writer/[内容]\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<p>分类：<strong>[内容]</strong>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"class=\\\"r\\\">[属性]</span>[内容]</p>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div class=\\\"bookPic l\\\"><a href=\\\"[属性]\\\"><img src=\\\"[内容]\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"状态：<strong>(.+?)/strong>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<li>\\\\s*<a\\\\s*href=\\\"(\\\\d+).html\\\"\\\\s*>.+?</a>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<li>\\\\s*<a\\\\s*href=\\\"\\\\d+.html\\\"\\\\s*>(.+?)</a>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<li>\\\\s*<a\\\\s*href=\\\"(\\\\d+.html)\\\"\\\\s*>.+?</a>\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/chapter/get.xml?url=[url]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<content>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</content>\",\"replace\":[]}',1,1,1416712822,1449051233),
	(57,'E小说（exiaoshuo）',1,0,1,0,34,'{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"http://www.exiaoshuo.com/\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.exiaoshuo.com/[novelid]/\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.exiaoshuo.com/[novelid]/\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<span class=\\\"s2\\\"><a href=\\\"http://www.exiaoshuo.com/[内容]/\\\">[参数]</a></span><span class=\\\"s3\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<span class=\\\"s2\\\"><a href=\\\"http://www.exiaoshuo.com/[属性]/\\\">[内容]</a></span><span class=\\\"s3\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<span class=\\\"s3\\\"><a href=\\\"http://www.exiaoshuo.com/[参数]/[内容]/\\\" target=\\\"_blank\\\">[参数]</a></span><span class=\\\"s4\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div id=\\\"fmimg\\\"><img alt=\\\"[内容]\\\" src=\\\"[属性]\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"作者：[内容][空白]</h1>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<p>类&nbsp;&nbsp;&nbsp;&nbsp;别：[内容]</p>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"</font></p>[空白]<p>[内容]</p>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div id=\\\"fmimg\\\"><img alt=\\\"[参数]\\\" src=\\\"[内容]\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<p>状&nbsp;&nbsp;&nbsp;&nbsp;态：(.+?)</p>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<dd><a href=\\\"http://www.exiaoshuo.com/.+?/(\\\\d+)/\\\" title=\\\".+?\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<dd><a href=\\\"http://www.exiaoshuo.com/.+?/\\\\d+/\\\" title=\\\"(.+?)\\\">\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<dd><a href=\\\"(http://www.exiaoshuo.com/.+?/\\\\d+/)\\\" title=\\\".+?\\\">\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/chapter/get.xml?url=[url]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<content>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</content>\",\"replace\":[]}',1,1,1416713411,1449051295),
	(58,'乐文小说网 365xs.org规则',1,0,1,0,35,'{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.365xs.org/?r=[timestamp]\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.365xs.org/book/[novelid]/index.html\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.365xs.org/books/[subnovelid]/[novelid]/\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"</span><span class=\\\"s2\\\"><a href=\\\"http://www.365xs.org/books/[数字]/[内容]/\\\" target=\\\"_blank\\\">[参数]</a></span>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"</span><span class=\\\"s2\\\"><a href=\\\"http://www.365xs.org/books/[数字]/[数字]/\\\" target=\\\"_blank\\\">[内容]</a></span>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"/span><span class=\\\"s3\\\"><a href=\\\"http://www.365xs.org/books/[数字]/[数字]/[内容].html\\\" target=\\\"_blank\\\">[参数]</a></a></span><span class=\\\"s4\\\">\",\"replace\":[{\"search\":\"最强\",\"replace\":\"最d\"}]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<h1 class=\\\"f20h\\\">[内容]<em>\",\"replace\":[{\"search\":\"深海\",\"replace\":\"深海a\"}]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<em>作者：[内容]</em></h1>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td width=\\\"24%\\\"><b>小说分类：</b>[内容]</td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div class=\\\"intro\\\" style=\\\"text-indent:2em;\\\">[内容]</div>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div class=\\\"pic\\\">[空白]<img src=\\\"[内容]\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<img style=[参数]src=[内容]/>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<li><a href=\\\"(\\\\d+).html\\\" target=\\\"_blank\\\">.+?</a></li>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<li><a href=\\\"\\\\d+.html\\\" target=\\\"_blank\\\">(.+?)</a></li>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<li><a href=\\\"(\\\\d+.html)\\\" target=\\\"_blank\\\">.+?</a></li>\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/chapter/get.xml?url=[url]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<content>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</content>\",\"replace\":[]}',1,1,1416721094,1446710795),
	(59,'新笔趣阁',1,0,1,0,36,'{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.xbiquge.com/\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.xbiquge.com/[subnovelid]_[novelid]/\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.xbiquge.com/[subnovelid]_[novelid]/\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<span\\\\s+class=\\\"s2\\\"><a href=\\\"/\\\\d+_(\\\\d+)/\\\"\\\\s+target=\\\"_blank\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<span\\\\s+class=\\\"s2\\\"><a href=\\\"/\\\\d+_\\\\d+/\\\"\\\\s+target=\\\"_blank\\\">(.+?)</a>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<span\\\\s+class=\\\"s3\\\"><a href=\\\"/\\\\d+_\\\\d+/(\\\\d+).html\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<h1>(.+?)<\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<p>\\\\s+作&nbsp;&nbsp;者：(.+?)</p>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<meta property=\\\"og:novel:category\\\" content=\\\"(.+?)\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div id=\\\"intro\\\">(.+?)</div>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div id=\\\"fmimg\\\">\\\\s+<img alt=\\\".+?\\\" src=\\\"(.+?)\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"status\\\" content=\\\"(.+?)\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<dd> <a style=\\\"\\\" href=\\\"(\\\\d+).html\\\">.+?</a></dd>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<dd> <a style=\\\"\\\" href=\\\"\\\\d+.html\\\">(.+?)</a></dd>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<dd> <a style=\\\"\\\" href=\\\"(\\\\d+.html)\\\">.+?</a></dd>\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/chapter/get.xml?url=[url]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<content>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</content>\",\"replace\":[]}',1,1,1446911028,1449051453),
	(60,'飘天文学',1,0,1,0,37,'{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.piaotian.net/\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.piaotian.net/bookinfo/[subnovelid]/[novelid].html\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.piaotian.net/html/[subnovelid]/[novelid]/\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"《<a class=\\\"poptext\\\" href=\\\"http://www.piaotian.net/bookinfo/\\\\d+/(\\\\d+).html\\\" target=\\\"_blank\\\">.+?</a>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"《<a class=\\\"poptext\\\" href=\\\"http://www.piaotian.net/bookinfo/\\\\d+/\\\\d+.html\\\" target=\\\"_blank\\\">(.+?)</a>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<a href=\\\"http://www.piaotian.net/html/\\\\d+/\\\\d+/(\\\\d+).html\\\" target=\\\"_blank\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<h1>(.+?)</h1>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td width=\\\"25%\\\">作&nbsp;&nbsp;&nbsp; 者：(.+?)</td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td width=\\\"25%\\\">类&nbsp;&nbsp;&nbsp; 别：(.+?)</td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<span class=\\\"hottext\\\">内容简介：</span><br />(.+?) </td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td width=\\\"80%\\\" valign=\\\"top\\\">\\\\s+<a href=\\\"(.+?)\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td>文章状态：(.+?)</td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<li><a href=\\\"(\\\\d+).html\\\">.+?</a></li>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<li><a href=\\\"\\\\d+.html\\\">(.+?)</a></li>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<li><a href=\\\"(\\\\d+.html)\\\">.+?</a></li>\",\"replace\":[]}','{\"charset\":\"utf-8\",\"error\":\"\",\"rule\":\"http://app.api.ptcms.com/api/chapter/get.xml?url=[url]\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<content>(?:\\\\<\\\\!\\\\[CDATA\\\\[)?[内容](?:\\\\]\\\\]\\\\>)?</content>\",\"replace\":[]}',1,1,1446911908,1449051414),
	(61,'TXT下载',1,0,1,0,38,'{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"http://www.xshuotxt.com/xstoplastupdate/0/1.html\",\"replace\":[]}','{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"http://www.xshuotxt.com/xsinfo/[subnovelid]/[novelid].html\",\"replace\":[]}','{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"http://www.xshuotxt.com/8/[subnovelid]/[novelid]/index.html\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td class=\\\"odd\\\"><a href=\\\"http://www.xshuotxt.com/xsinfo/\\\\d*/(\\\\d*).html\\\">.+?</a></td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td class=\\\"odd\\\"><a href=\\\".+?\\\">(.+?)</a></td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td class=\\\"even\\\"><a href=\\\".+?l\\\" target=\\\"_blank\\\">(.+?)</a></td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<h1>(.+?)</h1>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"作者</td>\\\\s*<td width=\\\"16%\\\" height=\\\"25\\\" bgcolor=\\\"#FFFFFF\\\"><a href=\\\".+?\\\" target=\\\"_blank\\\">(.+?)</font></a></td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"类&nbsp;&nbsp;&nbsp;别</td>\\\\s*<td width=\\\"12%\\\" height=\\\"25\\\" bgcolor=\\\"#FFFFFF\\\">(.+?)</td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"版权归原作者.+?所有[内容]td width=\\\"19%\\\" valign=\\\"top\\\" align=\\\"center\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td width=\\\"19%\\\" valign=\\\"top\\\" align=\\\"center\\\"><img src=\\\"(.+?)\\\" width=\\\"100\\\" height=\\\"125\\\" />\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"进程</td>\\\\s*<td width=\\\"12%\\\" height=\\\"25\\\" bgcolor=\\\"#FFFFFF\\\">(.+?)</td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td width=\\\"25%\\\" class=\'chaptertd\'>\\\\s*<a href=\\\"(\\\\d*).html\\\" target=\\\"_blank\\\">.+?</a>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td width=\\\"25%\\\" class=\'chaptertd\'>\\\\s*<a href=\\\"\\\\d*.html\\\" target=\\\"_blank\\\">(.+?)</a>\",\"replace\":[]}','{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"<td width=\\\"25%\\\" class=\'chaptertd\'>\\\\s*<a href=\\\"(\\\\d*.html)\\\" target=\\\"_blank\\\">.+?</a>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div id=\\\"content\\\">[内容]<div id=\\\"footlink\\\">\",\"replace\":[{\"search\":\"www/xshuotxt/com\",\"replace\":\"\"},{\"search\":\"XshuOTXt\",\"replace\":\"\"}]}',1,1,1451617070,1451617089),
	(62,'百书楼',0,0,0,0,39,'{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"http://www.baishulou.net/page_lastupdate/1.html\",\"replace\":[]}','{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"http://www.baishulou.net/books_[novelid].html\",\"replace\":[]}','{\"charset\":\"gbk\",\"error\":\"\",\"rule\":\"http://www.baishulou.net/read/[subnovelid]/[novelid]/\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<tr>\\\\s*<td class=\\\"odd\\\"><a href=\\\"http://www.baishulou.net/books_(\\\\d*).html\\\">.+?</a></td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<tr>\\\\s*<td class=\\\"odd\\\"><a href=\\\"http://www.baishulou.net/books_\\\\d*.html\\\">(.+?)</a></td>\\\\s* <td\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td class=\\\"even\\\"><a href=\\\"http://www.baishulou.net/read/\\\\d*/\\\\d*/\\\" target=\\\"_blank\\\">(.+?)</a></td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<title>(.+?)TXT全文免费\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<em><strong>作者：</strong><a rel=\\\"nofollow\\\" href=\\\".+?\\\" target=\\\"_blank\\\">(.+?)</a></em>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"作品大类：<a href=\\\".+?\\\">(.+?)</a></span>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div class=\\\"bintro clear\\\">[内容]<p class=\\\"readlast\\\">\",\"replace\":[{\"search\":\"nbsp&amp;\",\"ignorecase\":\"1\"},{\"search\":\"&nbsp;\",\"singleline\":false,\"ignorecase\":\"1\"},{\"search\":\"&nbsp\"}]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div class=\\\"bimg\\\"><a href=\\\".+?\\\" target=\\\"_blank\\\"><img src=\\\"(.+?)\\\"></a></div>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"写作进程：(.+?)</span><span>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td class=\\\"dccss\\\">\\\\s* <a href=\\\"(\\\\d*).html\\\">.+?</a>\\\\s*</td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td class=\\\"dccss\\\">\\\\s* <a href=\\\"\\\\d*.html\\\">(.+?)</a>\\\\s*</td>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<td class=\\\"dccss\\\">\\\\s* <a href=\\\"(\\\\d*.html)\\\">.+?</a>\\\\s*</td>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<DIV id=\\\"content\\\" name=\\\"content\\\" style=\\\"line-height:[内容]<DIV id=\\\"link_24\\\" align=\\\"center\\\">\",\"replace\":[{\"singleline\":\"1\",\"search\":\"190%; color: rgb(0, 0, 0); \\\">\"}]}',1,1,1451617772,1451730868),
	(63,'风华居-规则',0,0,0,0,40,'{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.fenghuaju.com/modules/article/toplist.php?sort=lastupdate\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.fenghuaju.com/[subnovelid]_[novelid]/\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.fenghuaju.com/[subnovelid]_[novelid]/\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td class=\\\"odd\\\"><a href=\\\"http://www.fenghuaju.com/\\\\d*_(\\\\d*)/\\\">.+?</a></td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td class=\\\"odd\\\"><a href=\\\".+?\\\">(.+?)</a></td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<td class=\\\"odd\\\" align=\\\"center\\\">(.+?)</td>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<h1>(.+?)</h1>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<p>作&nbsp;&nbsp;&nbsp;&nbsp;者：(.+?)</p>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"&gt; <a href=\\\".+?\\\">(.+?)</a> &gt;\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div id=\\\"intro\\\">[内容]<p>各位书友要是觉得\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div id=\\\"fmimg\\\"><img alt=\\\".+?\\\" src=\\\"(.+?)\\\" width=\\\"120\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<span class=\\\"(.+?)\\\"></span>\",\"replace\":[{\"search\":\"a\",\"replace\":\"完本\"},{\"search\":\"b\",\"replace\":\"连载中\"}]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<dd><a href=\\\"/\\\\d+_\\\\d+/(\\\\d+).html\\\">.+?</a></dd>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<dd><a href=\\\".+?\\\">(.+?)</a></dd>\",\"replace\":[{\"search\":\"登录书架即可实时查看。）</dt>.+？</dd>\\\\s*<dt>《.+？》正文</dt\",\"replace\":\"\"}]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<dd><a href=\\\"(.+?)\\\">.+?</a></dd>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div id=\\\"content\\\">[内容]<div class=\\\"bottem2\\\">\",\"replace\":[{\"search\":\"read3();\",\"singleline\":false},{\"search\":\"bdshare();\",\"singleline\":false}]}',1,0,1451731202,0),
	(64,'少年文学采集',1,0,1,0,41,'{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.snwx.com/toplastupdate/1.html\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.snwx.com/book/[subnovelid]/[novelid]/\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"http://www.snwx.com/book/[subnovelid]/[novelid]/\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<span class=\\\"s2\\\"><a href=\\\"http://www.snwx.com/book/\\\\d*/(\\\\d*)/\\\">.+?</a></span><span class=\\\"s3\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<span class=\\\"s2\\\"><a href=\\\"http://www.snwx.com/book/\\\\d*/\\\\d*/\\\">(.+?)</a></span><span class=\\\"s3\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<span class=\\\"s3\\\"><a href=\\\"http://www.snwx.com/book/\\\\d*/\\\\d*/(\\\\d*).html\\\" target=\\\"_blank\\\">.+?</a></span><span class=\\\"s4\\\">\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<h1>(.+?)</h1>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<i>作者：(.+?)</i>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<i>类别：(.+?)</i>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"简介：</b><br />[内容]</div>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div id=\\\"fmimg\\\"><img alt=\\\".+?\\\" src=\\\"(.+?)\\\" onerror=\\\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<i>类别：(.+?)</i>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<dd><a href=\\\"(\\\\d*).html\\\" title=\\\".+?\\\">.+?</a></dd>\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<dd><a href=\\\"\\\\d*.html\\\" title=\\\".+?\\\">(.+?)</a></dd>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"<dd><a href=\\\"(\\\\d*.html)\\\" title=\\\".+?\\\">.+?</a></dd>\",\"replace\":[]}','{\"charset\":\"auto\",\"error\":\"\",\"rule\":\"\",\"replace\":[]}','{\"singleline\":0,\"ignorecase\":\"\",\"rule\":\"<div id=\\\"BookText\\\">[内容]</div>\",\"replace\":[]}',1,0,1455500167,0);

DROP TABLE IF EXISTS `ptcms_user`;

CREATE TABLE `ptcms_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `password` char(32) DEFAULT NULL,
  `salt` char(6) DEFAULT NULL,
  `reg_ip` varchar(15) DEFAULT NULL,
  `reg_time` int(11) unsigned DEFAULT '0',
  `login_ip` varchar(15) DEFAULT NULL,
  `login_time` int(11) unsigned DEFAULT '0',
  `qqid` char(32) NOT NULL DEFAULT '',
  `weiboid` char(32) NOT NULL DEFAULT '',
  `status` tinyint(3) DEFAULT '1',
  `login_day` int(11) unsigned DEFAULT '0',
  `login_num` smallint(5) DEFAULT '0',
  `sign_day` int(11) unsigned DEFAULT '0',
  `sign_num` smallint(6) unsigned DEFAULT '0',
  `point` int(6) unsigned DEFAULT '0',
  `exp` int(6) unsigned DEFAULT '0',
  `author` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`(5)),
  KEY `weiboid` (`weiboid`(3)),
  KEY `qqid` (`qqid`(3))
) ENGINE=myisam DEFAULT CHARSET=utf8 COMMENT='用户信息主表';


INSERT INTO `ptcms_user` (`id`, `name`, `password`, `salt`, `reg_ip`, `reg_time`, `login_ip`, `login_time`, `qqid`, `weiboid`, `status`, `login_day`, `login_num`, `sign_day`, `sign_num`, `point`, `exp`, `author`)
VALUES
	(1,'{adminuser}','{adminpwd}','{salt}','127.0.0.1',1411978787,'127.0.0.1',1456644633,'','',1,20160226,1,NULL,NULL,0,0,1);


DROP TABLE IF EXISTS `ptcms_user_habbit`;

CREATE TABLE `ptcms_user_habbit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `pc` text NOT NULL,
  `wap` text NOT NULL,
  `app` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=myisam DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `ptcms_user_mark`;

CREATE TABLE `ptcms_user_mark` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `novelid` int(10) unsigned NOT NULL DEFAULT '0',
  `chaptersign` int(10) unsigned NOT NULL DEFAULT '0',
  `datetime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`,`novelid`)
) ENGINE=myisam DEFAULT CHARSET=utf8;




ALTER TABLE `ptcms_novelsearch_info` ADD `orderid` INT NOT NULL DEFAULT '0' ;
ALTER TABLE `ptcms_rule` ADD `discardendnum` INT NOT NULL DEFAULT '0' COMMENT '略过最后几张' AFTER `name`;
ALTER TABLE `ptcms_rule` ADD `discardstartnum` INT NOT NULL DEFAULT '0' COMMENT '略过最开始几张' AFTER `name`;
ALTER TABLE `ptcms_novelsearch_log` ADD `oid` INT NOT NULL DEFAULT '0' ;
INSERT INTO `ptcms_config` (`id`, `title`, `key`, `intro`, `type`, `group`, `extra`, `value`, `level`, `ordernum`, `create_user_id`, `update_user_id`, `create_time`, `update_time`, `status`)
VALUES
	(null, '旧章节重排', 'collect_reorder', '重排解决部分老书对比错误，不重排只对新入库的进行优化', 'radio', -3, '1:重排老章节\r\n0:不重排', '0', 1, 50, 1, 0, 1414908929, 0, 1);
