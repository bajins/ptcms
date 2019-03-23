<?php

class ZCacheConf 
{
	//持续连接
	public  $PERSISTENT = 1;		
	//连接超时，秒级
	public $CONNTIMEOUT = 1;			
	//MCPACK版本
	public $MCPACK_VERSION = PHP_MC_PACK_V2;
	//retry time
	public $RETRYTIME = 3;

	public $crypt_flag = 0;

	//当前配置
	public $agent_servers = array();
}


