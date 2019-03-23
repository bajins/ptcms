<?php

/**
 * BAE memory cache服务SDK
 * @copyright Copyright (c) 2012-2020 百度在线网络技术(北京)有限公司
 * @version   1.0.0
 * @package   BaeOpenAPI
 **/

require_once(dirname(__FILE__) . '/lib/BaeBase.class.php');
require_once(dirname(__FILE__) . '/zcache/memcached_api.php');

/**
 * Wrapper class for Memcache.
 **/

class BaeMemcache extends BaeBase
{

	private $appid;

	/**
	 * Create Memcache instance
	 * 
	 */
	public function __construct($cache_id, $memcache_addr, $user, $password)
	{
		$memcache= new MemcachedMe($cache_id, $memcache_addr, $user, $password); 
		if(false === $memcache)
		{
			Throw new BaeException('Failed to connect to memcache cluster!');
		}
		else
		{
			$this->_handle= $memcache;
		}
		return;
	}

	/**
	 * Retrieve previously stored data if an item with such key exists on the server  
	 * at this moment. You can pass array of keys to get array of values. The result 
	 * array will contain only found key-value pairs.
	 * 
	 * @param string|array $mixedKey The key or array of keys to fetch
	 * @param int|array $flag		 If present, flags fetched along with 
	 * the values will be written to this parameter
	 * @return string|array
	 * @see http://cn.php.net/manual/zh/memcache.get.php
	 */
	public function get($key, $flag= null)
	{
		if(empty($key))
		{
			return null;
		}
		$res= $this->_handle->getExt($key);
		if(false === $res)
		{
            $this->errcode = $this->_handle->_last_errno;
            $this->errmsg= $this->_handle->_convertErrmsg($this->errcode);
		}
        else
        {
            $this->errcode = 0;
            $this->errmsg= "RES_SUCCESS";
        }
		return $res;
	}

	/**
	 * Store data at the memcache cluster 
	 * 
	 * @param string $key	The key that will be associated with the item
	 * @param mixed $var	The variable to store
	 * @param int $flag		Use MEMCACHE_COMPRESSED to store the item compressed (uses zlib)
	 * @param int $expire	Expiration time of the item. If it's equal to zero, the item will never expire
	 * @return bool
	 * @see http://cn.php.net/manual/zh/memcache.set.php
	 */
	public function set($key, $var, $flag=0, $expire=0)
	{
		$res= $this->_handle->set($key, $var, $expire);
		if(!$res)
		{
            $this->errcode = $this->_handle->_last_errno;
			$this->errmsg= $this->_handle->_convertErrmsg($this->errcode);
		}
        else
        {
            $this->errcode = 0;
            $this->errmsg= "RES_SUCCESS";
        }
		return $res;
	}

	/**
	 * Add an item to the  memcache cluster 
	 * 
	 * @param string $key	The key that will be associated with the item
	 * @param mix $var		The variable to store
	 * @param int $flag		Use MEMCACHE_COMPRESSED to store the item compressed (uses zlib)
	 * @param int $expire	Expiration time of the item. If it's equal to zero, the item will never expire
	 * @return bool
	 * @see	http://cn.php.net/manual/zh/memcache.add.php
	 */
	public function add($key, $var, $flag= 0, $expire= 0)
	{
		$res= $this->_handle->add($key, $var, $expire);
		if(!$res)
		{
            $this->errcode = $this->_handle->_last_errno;                 
            $this->errmsg= $this->_handle->_convertErrmsg($this->errcode);
		}
        else
        {
            $this->errcode = 0;
            $this->errmsg= "RES_SUCCESS";
        }
		return $res;
	}

	/**
	 * Replace value of the <b>existing</b> item in memcache cluster
	 * 
	 * @param string $key	The key that will be associated with the item
	 * @param mixed $var	The variable to store
	 * @param int $flag		Use MEMCACHE_COMPRESSED to store the item compressed (uses zlib)
	 * @param int $expire	Expiration time of the item. If it's equal to zero, the item will never expire
	 * @return bool
	 * @see http://cn.php.net/manual/zh/memcache.replace.php
	 */
	public function replace($key, $var, $flag= 0, $expire= 0)
	{
		$res= $this->_handle->replace($key, $var, $expire);
		if(!$res)
		{
            $this->errcode = $this->_handle->_last_errno;                 
            $this->errmsg= $this->_handle->_convertErrmsg($this->errcode);
		}
        else
        {
            $this->errcode = 0;
            $this->errmsg= "RES_SUCCESS";
        }
		return $res;
	}

	/**
	 * Increment cache item's value at the memcache cluster
	 * 
	 * @param string $key	Key of the item to increment
	 * @param int $value	Increment the item by value
	 * @return int
	 * @see http://cn.php.net/manual/zh/memcache.increment.php
	 */
	public function increment($key, $value= 1)
	{
		$res= $this->_handle->increment($key, $value);
		if(!$res)
		{
            $this->errcode = $this->_handle->_last_errno;                 
            $this->errmsg= $this->_handle->_convertErrmsg($this->errcode);
		}
        else
        {
            $this->errcode = 0;
            $this->errmsg= "RES_SUCCESS";
        }
		return $res;
	}

	/**
	 * Decrement cache item's value at the memcache cluster
	 * 
	 * @param string $key	Key of the item to decrement
	 * @param int $value	Dncrement the item by value
	 * @return int
	 * @see http://cn.php.net/manual/zh/memcache.decrement.php
	 */
	public function decrement($key, $value= 1)
	{
		$res= $this->_handle->decrement($key, $value);
		if(!$res)
		{
            $this->errcode = $this->_handle->_last_errno;                 
            $this->errmsg= $this->_handle->_convertErrmsg($this->errcode);
		}
        else
        {
            $this->errcode = 0;
            $this->errmsg= "RES_SUCCESS";
        }
		return $res;
	}

	/**
	 * Delete item from the memcache cluster
	 * 
	 * @param string $key	The key associated with the item to delete
	 * @param int $time	    The value will be deleted whithin $time seconds.
	 * @return bool
	 * @see http://cn.php.net/manual/zh/function.memcache-delete.php
	 */
	public function delete($key, $time = 0)
	{
		$res= $this->_handle->delete($key, $time);
		if(!$res)
		{
            $this->errcode = $this->_handle->_last_errno;                 
            $this->errmsg= $this->_handle->_convertErrmsg($this->errcode);
		}
        else
        {
            $this->errcode = 0;
            $this->errmsg= "RES_SUCCESS";
        }
		return $res;
	}
	
	/**
	 * Close memcached connection
	 * 
	 */
	public function close()
	{
		if($this->_handle)
		{
			//return $this->_handle->close();
			return true;
		}
		else
		{
			return false;
		}
	}

	public function set_shareAppid($appid)
	{
		if(!is_string($appid)) return false;

		$ret = $this->_handle->set_shareAppid($appid);
		if($ret === false) return false;
		
		$this->appid = $appid;
		return true;
	}

	public function __destruct()
	{
		$this->close();
	}

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
