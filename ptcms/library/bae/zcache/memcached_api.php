<?php

require_once(dirname(__FILE__) . "/zcache_api.php");
require_once(dirname(__FILE__) . "/memcache_errno.php");

define("ENABLE_DEBUG", 0);

class MemcachedMe 
{

	/**
	 * Libmemcached behavior options.
	 */
	/*
	const OPT_HASH;
	const OPT_HASH_DEFAULT;
	const OPT_HASH_MD5;
	const OPT_HASH_CRC;
	const OPT_HASH_FNV1_64;
	const OPT_HASH_FNV1A_64;
	const OPT_HASH_FNV1_32;
	const OPT_HASH_FNV1A_32;
	const OPT_HASH_HSIEH;
	const OPT_HASH_MURMUR;
	const OPT_DISTRIBUTION;
	const OPT_DISTRIBUTION_MODULA;
	const OPT_DISTRIBUTION_CONSISTENT;
	const OPT_LIBKETAMA_COMPATIBLE;
	const OPT_BUFFER_REQUESTS;
	const OPT_BINARY_PROTOCOL;
	const OPT_NO_BLOCK;
	const OPT_TCP_NODELAY;
	const OPT_SOCKET_SEND_SIZE;
	const OPT_SOCKET_RECV_SIZE;
	const OPT_CONNECT_TIMEOUT;
	const OPT_RETRY_TIMEOUT;
	const OPT_SND_TIMEOUT;
	const OPT_RCV_TIMEOUT;
	const OPT_POLL_TIMEOUT;
	const OPT_SERVER_FAILURE_LIMIT;
	const OPT_CACHE_LOOKUPS;
	
	const OPT_COMPRESSION;
	const OPT_PREFIX_KEY;
	*/
	private $errmsgmap = array (
		"-1001" => "RES_PAYLOAD_FAILURE",
                "0" => "RES_SUCCESS",
                "1" => "RES_FAILURE",
                "2" => "RES_USER_AUTH_FAILURE",
                "7" => "RES_UNKNOWN_READ_FAILURE",
                "8" => "RES_PROTOCOL_ERROR",
                "9" => "RES_CLIENT_ERROR",
                "10" => "RES_SERVER_ERROR",
                "5" => "RES_WRITE_FAILURE",
                "12" => "RES_DATA_EXISTS",
	        "14" => "RES_NOTSTORED",
                "16" => "RES_NOTFOUND",
                "18" => "RES_PARTIAL_READ",
                "19" => "RES_SOME_ERRORS",
                "20" => "RES_NO_SERVERS",
                "21" => "RES_SERVER_BUSY",
                "26" => "RES_ERRNO",
                "32" => "RES_BUFFERED",
                "31" => "RES_TIMEOUT",
                "33" => "RES_BAD_KEY_PROVIDED",
                "11" => "RES_CONNECTION_SOCKET_CREATE_FAILURE",
         
	);

	private $errnomap = array (

		ZCACHE_AGENT_ERR_PARAM => RES_CLIENT_ERROR,
		ZCACHE_AGENT_ERR_MCPACK => RES_PROTOCOL_ERROR,
		ZCACHE_AGENT_ERR_MEM => RES_SERVER_ERROR,
		ZCACHE_AGENT_ERR_MCPACK_OP => RES_PROTOCOL_ERROR,

		ZCACHE_OK => RES_SUCCESS,
		ZCACHE_ERR_PARAM => RES_CLIENT_ERROR,
		ZCACHE_ERR_NOT_AUTH => RES_HOST_LOOKUP_FAILURE,
		ZCACHE_ERR_BUF_NOT_ENOUGH => RES_BUFFERED,
		ZCACHE_ERR_EXIST => RES_DATA_EXISTS,
		ZCACHE_ERR_NOT_EXIST => RES_NOTFOUND,
		ZCACHE_ERR_BLOCK_NOT_EXIST => RES_SERVER_ERROR,
		ZCACHE_ERR_PRODUCT_NOT_EXIST => RES_SERVER_ERROR,
		ZCACHE_ERR_BUSY => RES_END,
		ZCACHE_ERR_FROZEN_DELETE => RES_SERVER_ERROR,
		ZCACHE_ERR_BLOCK_UPDATED => RES_SERVER_ERROR,
		ZCACHE_ERR_TIMEOUT => RES_TIMEOUT,
		ZCACHE_ERR_NET => RES_SERVER_ERROR,
		ZCACHE_ERR_MEM => RES_SERVER_ERROR,
		ZCACHE_ERR_DISK => RES_SERVER_ERROR,
		ZCACHE_ERR_METASERVER => RES_SERVER_ERROR,
		ZCACHE_ERR_CACHESERVER => RES_SERVER_ERROR,
		ZCACHE_ERR_LIB => RES_SERVER_ERROR,
		ZCACHE_ERR_PART_SUC => RES_SERVER_ERROR,
		ZCACHE_ERR_BLOCK_WRONG_STATE => RES_SERVER_ERROR,
		ZCACHE_APIPLUS_INIT_FAIL => RES_SERVER_ERROR,
		ZCACHE_ERR_CREATE_PRDT_FILE => RES_SERVER_ERROR,
		ZCACHE_ERR_PRODUCT_ALREADY_EXIST => RES_SERVER_ERROR,

		ZCACHE_CLIENT_ERR_PARAM => RES_CLIENT_ERROR,
		ZCACHE_CLIENT_ERR_CONNECT => RES_CONNECTION_SOCKET_CREATE_FAILURE,
		ZCACHE_CLIENT_ERR_READ => RES_UNKNOWN_READ_FAILURE,
		ZCACHE_CLIENT_ERR_WRITE => RES_WRITE_FAILURE,
		ZCACHE_CLIENT_ERR_NSHEAD => RES_PROTOCOL_ERROR,
		ZCACHE_CLIENT_ERR_MCPACK => RES_PROTOCOL_ERROR,
		
	);

	private $_adapter;
	private $_pname_str;
	private $_token_str;
	private $_logid_int;
	private $_appid;
	public $_last_errno;

	private function _convertErrno($errcode) {
		if (array_key_exists($errcode, $this->errnomap)) {
			return $this->errnomap[$errcode];
		}
		return RES_SOME_ERRORS;
	}

	private function _log($output) {
		if(ENABLE_DEBUG !== 0) {
			echo "--memcached: $output";
		}
	}

        public function _convertErrmsg($errcode) {
                if (array_key_exists(strval($errcode), $this->errmsgmap)) {
                        return $this->errmsgmap[strval($errcode)];
                }
                return "RES_SOME_ERRORS";
        }

	public function __construct($cache_id, $memcache_addr, $user, $password) {
		$cnfobj = new ZCacheConf();
                //$cnfobj->PERSISTENT = 0;
		$cnfobj->CONNTIMEOUT = 1;
		$cnfobj->MCPACK_VERSION = PHP_MC_PACK_V2;

		//retry time
		$cnfobj->RETRYTIME = 3;

		$zcache_addrs = $memcache_addr;
		
		if($zcache_addrs === false) {
			throw new MemcachedMeException("Missing cache server address"); 
		}
		
		$addr_arr = explode(",", $zcache_addrs);
		if($addr_arr === false) {
			throw new MemcachedMeException("invalid cache server address"); 
		}

		foreach($addr_arr as $addr) {
			$ipport = explode(":", $addr);
			if($ipport === false) {
				throw new MemcachedMeException("invalid cache server address"); 
			} 
			$cnfobj->agent_servers[] = array(
					"socket_address" => $ipport[0],
					"socket_port" => intval($ipport[1]),
					"socket_timeout" => 500
				);
		}
                if(!$this->_getInitEnv($cache_id, $user, $password)) {
                        throw new MemcachedMeException("invalid cache evn");
                }	
		$this->_adapter = new ZCache($cnfobj);
		$this->_adapter->set_shareAppid($this->_appid);
		$this->_last_errno = RES_SUCCESS; 
	}
	private function _getInitEnv($cache_id, $user, $password) {
		$pname_str = $user;
		$token_str = $password;
		$logid_int = 1;
		
		$appid = $cache_id;

		if($pname_str === false || $token_str === false || $logid_int === false || $appid === false) {
			$this->_log("Please setup HTTP_BAE_ENV_AK, HTTP_BAE_ENV_SK, HTTP_BAE_ENV_APPID\n");
			return false;
		}
		$logid_int = intval($logid_int);
		$this->_pname_str = $pname_str;
		$this->_token_str = $token_str;
		$this->_logid_int = $logid_int;
		$this->_appid = $appid;

		return true;
	}

	private function _getEnv(&$pname_str, &$token_str, &$logid_int) {
		$pname_str = $this->_pname_str;
		$token_str = $this->_token_str;
		$logid_int = $this->_logid_int;

		return true;
	}

	private function _checkParamValue($value) {
		if(!is_string($value))
			return true;

		$len = strlen($value);
		if($len == 0 || $len > ZCACHE_MAX_VALUE_LEN) {
			return false;
		}
		return true;
	}

	private function _convertKey($key) {
		if(is_numeric($key))
			return strval($key);
		return $key;
	}
	
	private function _convertValue($value) {
		if(is_int($value)) {
			$res = strval($value);
		} else if(is_float($value)) {
			$res = serialize(strval($value));
		} else if(is_string($value)) {
			if(is_numeric($value)) {
				if(strstr($value, ".") !== false) { ### float
					$res = serialize(strval($value));
				} else
					$res = $value;
			}
			$res = serialize($value);
		} else {
			$res = serialize($value);
		}

		if($this->_checkParamValue($res) === false) return false;
		else return $res;
	}

	private function _convertOffset($offset) {
		if(is_numeric($offset))
			return intval($offset);
		return 0;
	}

	private function _unconvertResult($result) {
		if(!is_numeric($result)) {
			return unserialize($result);
		}
		return $result;
	}

	public function addEx($pname_str, $token_str, $logid_int, $key, $value, $expiration = 0) {
		if(!is_int($expiration) || $expiration < 0) {
			$this->_last_errno = RES_CLIENT_ERROR;
			return false;
		}

		$key = $this->_convertKey($key);
		$value_str = $this->_convertValue($value);
		if($value_str === false) {
			$this->_last_errno = RES_CLIENT_ERROR;
			return false;
		}
		$ret = $this->_adapter->addOne($pname_str, $token_str, $logid_int, $key, $value_str, $expiration*1000);
		$this->_last_errno = $this->_convertErrno($this->_adapter->getLastErrCode());
		return $ret;
	}

	public function add($key, $value, $expiration = 0) {
		$pname_str = "";
		$token_str = "";
		$logid_int = 0;
		if(!$this->_getEnv($pname_str, $token_str, $logid_int)) {
			$this->_last_errno = RES_CLIENT_ERROR;
			return false;
		}
		return $this->addEx($pname_str, $token_str, $logid_int, $key, $value, $expiration);
	}

	public function setEx($pname_str, $token_str, $logid_int, $key, $value, $expiration = 0) {
		if(!is_int($expiration) || $expiration < 0) {
			$this->_last_errno = RES_CLIENT_ERROR;
			return false;
		}

		$key = $this->_convertKey($key);
		$value_str = $this->_convertValue($value);
		if($value_str === false) {
			$this->_last_errno = RES_CLIENT_ERROR;
			return false;
		}
		$ret = $this->_adapter->setOne($pname_str, $token_str, $logid_int, $key, $value_str, $expiration*1000);
		$this->_last_errno = $this->_convertErrno($this->_adapter->getLastErrCode());
		return $ret;
	}

	public function set($key, $value, $expiration = 0) {
		$pname_str = "";
		$token_str = "";
		$logid_int = 0;
		if(!$this->_getEnv($pname_str, $token_str, $logid_int)) {
			$this->_last_errno = RES_CLIENT_ERROR;
			return false;
		}
		return $this->setEx($pname_str, $token_str, $logid_int, $key, $value, $expiration);
	}

	public function replaceEx($pname_str, $token_str, $logid_int, $key, $value, $expiration = 0) {
		if(!is_int($expiration) || $expiration < 0) {
			$this->_last_errno = RES_CLIENT_ERROR;
			return false;
		}

		$key = $this->_convertKey($key);
		$value_str = $this->_convertValue($value);
		if($value_str === false) {
			$this->_last_errno = RES_CLIENT_ERROR;
			return false;
		}

		$ret = $this->_adapter->replaceOne($pname_str, $token_str, $logid_int, $key, $value_str, $expiration*1000);
		$this->_last_errno = $this->_convertErrno($this->_adapter->getLastErrCode());
		return $ret;
	}

	public function replace($key, $value, $expiration = 0) {
		$pname_str = "";
		$token_str = "";
		$logid_int = 0;
		if(!$this->_getEnv($pname_str, $token_str, $logid_int)) {
			$this->_last_errno = RES_CLIENT_ERROR;
			return false;
		}
		return $this->replaceEx($pname_str, $token_str, $logid_int, $key, $value, $expiration);
	}

	public function deleteEx($pname_str, $token_str, $logid_int, $key, $time=0) {
		$key = $this->_convertKey($key);
		$ret = $this->_adapter->deleteOne($pname_str, $token_str, $logid_int, $key, $time*1000);
		$this->_last_errno = $this->_convertErrno($this->_adapter->getLastErrCode());
		
		return $ret;
	}

	public function delete($key, $time = 0) {
		$pname_str = "";
		$token_str = "";
		$logid_int = 0;
		if(!$this->_getEnv($pname_str, $token_str, $logid_int)) {
			$this->_last_errno = RES_CLIENT_ERROR;
			return false;
		}
		return $this->deleteEx($pname_str, $token_str, $logid_int, $key, $time);
	}

	public function incrementEx($pname_str, $token_str, $logid_int, $key, $offset = 1) {
		$key = $this->_convertKey($key);
		$offset = $this->_convertOffset($offset);
		$ret = $this->_adapter->increment($pname_str, $token_str, $logid_int, $key, strval($offset), 0);
		$this->_last_errno = $this->_convertErrno($this->_adapter->getLastErrCode());
		if($ret === false) return false;
		return intval($ret);
	}

	public function increment($key, $offset = 1) {
		$pname_str = "";
		$token_str = "";
		$logid_int = 0;
		if(!$this->_getEnv($pname_str, $token_str, $logid_int)) {
			$this->_last_errno = RES_CLIENT_ERROR;
			return false;
		}
		return $this->incrementEx($pname_str, $token_str, $logid_int, $key, $offset);
	}

	public function decrementEx($pname_str, $token_str, $logid_int, $key, $offset = 1) {
		$key = $this->_convertKey($key);
		$offset = $this->_convertOffset($offset);
		$ret = $this->_adapter->decrement($pname_str, $token_str, $logid_int, $key, strval($offset), 0);
		$this->_last_errno = $this->_convertErrno($this->_adapter->getLastErrCode());
		if($ret === false) return false;
		return intval($ret);
	}

	public function decrement($key, $offset = 1) {
		$pname_str = "";
		$token_str = "";
		$logid_int = 0;
		if(!$this->_getEnv($pname_str, $token_str, $logid_int)) {
			$this->_last_errno = RES_CLIENT_ERROR;
			return false;
		}
		return $this->decrementEx($pname_str, $token_str, $logid_int, $key, $offset);
	}

	public function getEx($pname_str, $token_str, $logid_int, $key) {
		$key = $this->_convertKey($key);
		$ret = $this->_adapter->getOne($pname_str, $token_str, $logid_int, $key);
		$this->_last_errno = $this->_convertErrno($this->_adapter->getLastErrCode());
		$ret = $this->_unconvertResult($ret);
		return $ret;
	}

	public function get($key, $cache_cb = null, & $cas_token = null) {
		$pname_str = "";
		$token_str = "";
		$logid_int = 0;
		if(!$this->_getEnv($pname_str, $token_str, $logid_int)) {
			$this->_last_errno = RES_CLIENT_ERROR;
			return false;
		}
		return $this->getEx($pname_str, $token_str, $logid_int, $key);
	}

	public function setMultiEx($pname_str, $token_str, $logid_int, array $items, $expiration = 0) {
		if(!is_int($expiration) || $expiration < 0) {
			$this->_last_errno = RES_CLIENT_ERROR;
			return false;
		}
		$new_items = array();
		foreach($items as $key => $val) {
			$key = $this->_convertKey($key);
			$value_str = $this->_convertValue($val);
			if($value_str === false) {
				$this->_last_errno = RES_CLIENT_ERROR;
				return false;
			}
			$new_items[$key] = $value_str;
		}

		$ret_arr = $this->_adapter->setMulti($pname_str, $token_str, $logid_int, $new_items, $expiration*1000);
		if(!$ret_arr) {
			$this->_last_errno = RES_PAYLOAD_FAILURE;
			return false;
		}
		
		if(!isset($ret_arr['content']) || !is_array($ret_arr['content']) ) {
			$this->_last_errno = RES_PAYLOAD_FAILURE;
			return false;
		}

		$count = count($ret_arr['content']);
		if(count($items) != $count) {
			$this->_last_errno = RES_PAYLOAD_FAILURE;
			return false;
		}

		for($i = 0; $i < $count; $i++) {
			$result = $ret_arr['content']['result' . $i];
			if(isset($result['err_no']) && is_int($result['err_no']) && $result['err_no'] == ZCACHE_OK) {
				continue;
			} else {
				$this->_last_errno = RES_PAYLOAD_FAILURE;
				return false;
			}
		}
		$this->_last_errno = RES_SUCCESS;
		return true;
	}

	public function setMulti(array $items, $expiration = 0) {
		$pname_str = "";
		$token_str = "";
		$logid_int = 0;

		if(!$this->_getEnv($pname_str, $token_str, $logid_int)) {
			$this->_last_errno = RES_CLIENT_ERROR;
			return false;
		}
		return $this->setMultiEx($pname_str, $token_str, $logid_int, $items, $expiration);
	}

	public function getMultiEx($pname_str, $token_str, $logid_int, array $keys) {
		if(!is_array($keys)) {
			$this->_last_errno = RES_CLIENT_ERROR;
			return false;
		}

		$strkey_arr = array();
		foreach($keys as $k => $v) {
			$strkey_arr[] = $this->_convertKey($v);
		}

		$ret_arr = $this->_adapter->getMulti($pname_str, $token_str, $logid_int, $strkey_arr);
		if(!$ret_arr) {

			$this->_last_errno = RES_PAYLOAD_FAILURE;
			return false;
		}

		if(!isset($ret_arr['content']) || !is_array($ret_arr['content']) ) {

			$this->_last_errno = RES_PAYLOAD_FAILURE;
			return false;
		}

		$count = count($ret_arr['content']);
		if(count($keys) != $count) {

			$this->_last_errno = RES_PAYLOAD_FAILURE;
			return false;
		}


		$ret_values = array();
		for($i = 0; $i < $count; $i++) {
			$result = $ret_arr['content']['result' . $i];
			if(isset($result['err_no']) && is_int($result['err_no']) && $result['err_no'] == ZCACHE_OK &&
					isset($result['value']) && is_string($result['value']) ) {

				$ret_values[$keys[$i]] = $this->_unconvertResult($result['value']);
			}
		}
		$this->_last_errno = RES_SUCCESS;
		return $ret_values;
	}

	public function getMulti(array $keys, & $cas_tokens = null, $flags = 0) {
		$pname_str = "";
		$token_str = "";
		$logid_int = 0;
		if(!$this->_getEnv($pname_str, $token_str, $logid_int)) {
			$this->_last_errno = RES_CLIENT_ERROR;
			return false;
		}
		return $this->getMultiEx($pname_str, $token_str, $logid_int, $keys);
	}
/*
	public function getByKey($server_key, $key, $cache_cb = null, & $cas_token = null) {
		throw new Exception("NotImplemented");
	}

	public function getMultiByKey($server_key, array $keys, & $cas_tokens = null, $flags = 0) {
		throw new Exception("NotImplemented");
	}

	public function getDelayed(array $keys, $with_cas = null, $value_cb = null) {
		throw new Exception("NotImplemented");
	}

	public function getDelayedByKey($server_key, array $keys, $with_cas = null, $value_cb = null) {
		throw new Exception("NotImplemented");
	}

	public function fetch() {
		throw new Exception("NotImplemented");
	}

	public function fetchAll() {
		throw new Exception("NotImplemented");
	}

	public function setByKey($server_key, $key, $value, $expiration = 0) {
		throw new Exception("NotImplemented");
	}

	public function setMultiByKey($server_key, array $items, $expiration = 0) {
		throw new Exception("NotImplemented");
	}

	public function cas($token, $key, $value, $expiration = 0) {
		throw new Exception("NotImplemented");
	}

	public function casByKey($token, $server_key, $key, $value, $expiration = 0) {
		throw new Exception("NotImplemented");
	}

	public function addByKey($server_key, $key, $value, $expiration = 0) {
		throw new Exception("NotImplemented");
	}

	public function append($key, $value, $expiration = 0) {
		throw new Exception("NotImplemented");
	}

	public function appendByKey($server_ke, $key, $value, $expiration = 0) {
		throw new Exception("NotImplemented");
	}

	public function prepend($key, $value, $expiration = 0) {
		throw new Exception("NotImplemented");
	}

	public function prependByKey($server_key, $key, $value, $expiration = 0) {
		throw new Exception("NotImplemented");
	}

	public function replaceByKey($serve_key, $key, $value, $expiration = 0) {
		throw new Exception("NotImplemented");
	}

	public function deleteByKey($key, $time = 0) {
		throw new Exception("NotImplemented");
	}

	public function getOption($option) {
		throw new Exception("NotImplemented");
	}

	public function setOption($option, $value) {
		throw new Exception("NotImplemented");
	}

	public function addServer($host, $port, $weight = 0) {
		throw new Exception("NotImplemented");
	}

	public function addServers(array $servers) {
		throw new Exception("NotImplemented");
	}

	public function getServerList() {
		throw new Exception("NotImplemented");
	}

	public function getServerByKey($server_key) {
		throw new Exception("NotImplemented");
	}

	public function flush($delay = 0) {
		throw new Exception("NotImplemented");
	}

	public function getStats() {
		throw new Exception("NotImplemented");
	}

	public function getResultMessage() {
		throw new Exception("NotImplemented");
	}
*/

	public function getResultCode() {
		return $this->_last_errno;
	}

	public function getExtEx($pname_str, $token_str, $logid_int, $key) {

		$key = $this->_convertKey($key);

		if(is_string($key)) {
			return $this->getEx($pname_str, $token_str, $logid_int, $key);
		} elseif(is_array($key)) {
			return $this->getMultiEx($pname_str, $token_str, $logid_int, $key);
		} else {
			$this->_last_errno = RES_CLIENT_ERROR;
			return false;	
		}
	}

	public function getExt($key, $cache_cb = null, & $cas_token = null) {
		$pname_str = "";
		$token_str = "";
		$logid_int = 0;

		if(!$this->_getEnv($pname_str, $token_str, $logid_int)) {
			$this->_last_errno = RES_CLIENT_ERROR;
			return false;
		}
		return $this->getExtEx($pname_str, $token_str, $logid_int, $key);	
	}

	public function set_shareAppid($appid)
	{
		if(!is_string($appid)) return false;

		$ret = $this->_adapter->set_shareAppid($appid);
		if($ret === false) return false;
		
		$this->_appid = $appid;
		return true;
	}
}

class MemcachedMeException extends Exception {
	function __construct($errmsg = "", $errcode = 0) {
	}

}
