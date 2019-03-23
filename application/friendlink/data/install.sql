CREATE TABLE IF NOT EXISTS `!@#_friendlink` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

INSERT INTO `!@#_friendlink` (`id`, `name`, `url`, `logo`, `description`, `ordernum`, `color`, `isbold`, `create_user_id`, `update_user_id`, `create_time`, `update_time`, `status`) VALUES
(1, '冷梦博客', 'http://blog.lmz8.cn', '', '冷梦博客', 10, 'red', 1, 1, 1, 1412859114, 1412932698, 1);