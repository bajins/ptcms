ALTER TABLE `ptcms_novelsearch_info` ADD `orderid` INT NOT NULL DEFAULT '0' ;
ALTER TABLE `ptcms_rule` ADD `discardendnum` INT NOT NULL DEFAULT '0' COMMENT '略过最后几张' AFTER `name`;
ALTER TABLE `ptcms_rule` ADD `discardstartnum` INT NOT NULL DEFAULT '0' COMMENT '略过最开始几张' AFTER `name`;
ALTER TABLE `ptcms_novelsearch_log` ADD `oid` INT NOT NULL DEFAULT '0' ;
INSERT INTO `ptcms_config` (`id`, `title`, `key`, `intro`, `type`, `group`, `extra`, `value`, `level`, `ordernum`, `create_user_id`, `update_user_id`, `create_time`, `update_time`, `status`)
VALUES
	(null, '旧章节重排', 'collect_reorder', '重排解决部分老书对比错误，不重排只对新入库的进行优化', 'radio', -3, '1:重排老章节\r\n0:不重排', '0', 1, 50, 1, 0, 1414908929, 0, 1);


update ptcms_novelsearch_info set orderid=0;
update ptcms_novelsearch_log set oid=0;