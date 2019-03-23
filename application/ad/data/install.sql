CREATE TABLE IF NOT EXISTS `!@#_ad` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `!@#_ad` (`id`, `name`, `key`, `width`, `height`, `code`, `intro`, `create_user_id`, `update_user_id`, `create_time`, `update_time`, `type`, `status`) VALUES
(2, '网站统计', 'tongji', 0, 0, '网站统计示例广告代码', '', 1, 1, 1412900092, 1412929570, 1, 1);
