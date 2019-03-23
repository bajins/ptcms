<?php

require_once (dirname(__FILE__) . "/zcache_conf.php");
require_once (dirname(__FILE__) . "/zcache_nshead.php");
require_once (dirname(__FILE__) . "/zcache_socket.php");
require_once (dirname(__FILE__) . "/zcache_util.php");
require_once (dirname(__FILE__) . "/zcache_errno.php");

define("ZCACHE_MAX_KEY_LEN", 180);
//define("ZCACHE_MAX_VALUE_LEN", 1024);
define("ZCACHE_MAX_VALUE_LEN", 1048576);
define("ZCACHE_MAX_QUERY_NUM", 64);


class ZCache {

	private $_last_errno;
	private $_last_errmsg;
	private $zcfObj;
	private $handlers;
        private $_cur_handler;
	private $_appid;
	public function __construct($pZcfObj) {
		$this->handlers = array ();
		$this->zcfObj = $pZcfObj;
	}

	public function __destruct() {
		foreach($this->handlers as $k => $handler) {
			$handler->close();	
			unset ($this->handlers[$k]);
		}
	}

	private function _log($output) {
		#echo "--zcached: $output";
	}

	protected function _getHandler() {
            if (isset($this->_cur_handler))
                {
                    return $this->_cur_handler;
                }
		$servers = $this->zcfObj->agent_servers;
		$num = count($servers);
		$retry = $this->zcfObj->RETRYTIME;

		while ($retry-- > 0) {
			$idx = rand() % $num;
			$server = $servers[$idx];
			$key = $server['socket_address'] . ":" . $server['socket_port'];
                        $socket = new ZCacheSocket();
                        $socket->set_vars_from_array($server);
                        if ($socket->connect($this->zcfObj->CONNTIMEOUT))
                            {
                                $this->_cur_handler = $socket;
                                return $socket;
                            }
			/* if (!isset ($this->handlers[$key])) { */
			/* 	$socket = new ZCacheSocket(); */
			/* 	$socket->set_vars_from_array($server); */

			/* 	if ($socket->connect($this->zcfObj->CONNTIMEOUT)) { */
			/* 		$this->handlers[$key] = $socket; */
			/* 		return $socket; */
			/* 	} */
			/* } else */
			/* 	return $this->handlers[$key]; */
		}
		return null;
	}

	protected function _putHandler($handler, $force = 0) {
		if (!$this->zcfObj->PERSISTENT || $force != 0) {
			/* foreach ($this->handlers as $key => $value) { */
			/* 	if ($value === $handler) { */
			/* 		$handler->close(); */
			/* 		unset ($this->handlers[$key]); */
			/* 		break; */
			/* 	} */
			/* } */
                    $handler->close();
                    unset($this->_cur_handler);
		}
	}

	protected function _readResponse($handler) {
		//读取nshead

		$headbuf = $handler->read(36);
		if (!$headbuf) {
			$this->_log("read nshead failed\n");
			$this->_last_errno = ZCACHE_CLIENT_ERR_READ;
			$this->_last_errmsg = "net read err";
			return NULL;
		}

		//解析nshead
		$head = zcache_split_nshead($headbuf);
		if (!isset ($head['body_len'])) {
			$this->_log("no body_len in nshead\n");
			$this->_last_errno = ZCACHE_CLIENT_ERR_NSHEAD;
			$this->_last_errmsg = "nshead err";
			return NULL;
		}

		//读取数据包内容
		$retbuffer = $handler->read($head['body_len']);
		if (!$retbuffer) { 
			$this->_log("read nsbody " . $head['body_len'] . " failed\n");
			$this->_last_errno = ZCACHE_CLIENT_ERR_READ;
			$this->_last_errmsg = "net read err";
			return NULL;
		}
		if($head['reserverd']) {
			$decrypt_data = fcrypt_decode_hmac('key', $retbuffer);
			//$this->_log("fcrypt_decode_hmac: " . strlen($retbuffer) . " " . strlen($decrypt_data) );
			return $decrypt_data;
		} else {
			return $retbuffer;
		}
	}

	public function getLastErrCode() {
		return $this->_last_errno;
	}
	public function getLastErrMsg() {
		return $this->_last_errmsg;
	}

	private function _makeNshead($logid_int, $query_pack) {
		$nshead = new ZCacheNsHead();
		$nshead_arr['provider'] = "zcacheadapter";
		$nshead_arr['log_id'] = $logid_int;

		$nshead_arr['reserved'] = $this->zcfObj->crypt_flag; 
		if($this->zcfObj->crypt_flag) {
			$crypt_data = fcrypt_encode_hmac('key', $query_pack);
			//$this->_log("fcrypt_encode_hmac: " . strlen($query_pack) . " " . strlen($crypt_data) );
			$nshead_arr['body_len'] = strlen($crypt_data);
			$buffer = $nshead->build_nshead($nshead_arr) . $crypt_data;
		} else {
			$nshead_arr['body_len'] = strlen($query_pack);
			$buffer = $nshead->build_nshead($nshead_arr) . $query_pack;
		}
		return $buffer;
	}

	private function _makePack($pname_str, $token_str, $logid_int, $cmd_str, $expire_int, $item_arr) {
		//fill query pack
		$query_arr['cmd']   = $cmd_str;
		$query_arr['pname'] = $pname_str;
		$query_arr['token'] = $token_str;
		$query_arr['logid'] = $logid_int;
		$query_arr['appid'] = $this->_appid;

		$query_num = count($item_arr);
		$query_arr['content']['query_num'] = $query_num;

		$i = 0;
		foreach($item_arr as $value) {
			$query_arr['content']['query' . $i]['key'] = $value['key'];
			
			if (isset($value['value']))
				$query_arr['content']['query' . $i]['value'] = $value['value'];

			if($expire_int >= 0) {
				$query_arr['content']['query' . $i]['delay_time'] = $expire_int;
			}
			$i++;	
		}

		$query_pack = mc_pack_array2pack($query_arr, $this->zcfObj->MCPACK_VERSION);
		return $this->_makeNshead($logid_int, $query_pack);
	}

	public function parseResult($ret_arr) {
		if(!isset($ret_arr['content']) || !is_array($ret_arr['content']) ||
				!isset($ret_arr['content']['result0']) || !is_array($ret_arr['content']['result0']) ) {
			$this->_last_errno = ZCACHE_CLIENT_ERR_MCPACK;
			return false;
		}

		$val = $ret_arr['content']['result0'];
		if (isset($val['err_no']) && is_int($val['err_no']) ) {
			$this->_last_errno = $val['err_no'];
			if($this->_last_errno == ZCACHE_OK)
				return true;
			return false;
		} else {
			$this->_last_errno = ZCACHE_CLIENT_ERR_MCPACK;
			return false;
		}
	}

	public function parseResult2($ret_arr) {
		if(!isset($ret_arr['content']) || !is_array($ret_arr['content']) ||
				!isset($ret_arr['content']['result0']) || !is_array($ret_arr['content']['result0']) ) {
			$this->_last_errno = ZCACHE_CLIENT_ERR_MCPACK;
			return false;
		}

		$val = $ret_arr['content']['result0'];
		if (isset($val['err_no']) && is_int($val['err_no']) &&
				isset($val['value']) && is_string($val['value']) ) {
			$this->_last_errno = $val['err_no'];
			return $val['value'];
		}
                else if(isset($val['err_no']) && is_int($val['err_no'])) {
                        $this->_last_errno = $val['err_no'];
                        return false;
                }   
                else {
			$this->_last_errno = ZCACHE_CLIENT_ERR_MCPACK;
			return false;
		}
	}

	private function _talkWithServer($handler, $buffer) {

		if (!$handler->write($buffer)) {
			$this->_last_errno = ZCACHE_CLIENT_ERR_WRITE;
			$this->_last_errmsg = "net write err";
			$this->_putHandler($handler, 1);
			return false;
		}

		$retbuffer = $this->_readResponse($handler);
		if (!$retbuffer) {
			$this->_putHandler($handler, 1);

                        //print("retry connect\n");
		        $handler = $this->_getHandler(); // read error: retry connect , write , read once
                        if (!$handler) {
                        	$this->_last_errno = ZCACHE_CLIENT_ERR_CONNECT;
                        	$this->_last_errmsg = "retry net connect err";
                        	return false;
                        }
     
                        //print("retry write\n");
                        if (!$handler->write($buffer)) {
                        	$this->_last_errno = ZCACHE_CLIENT_ERR_WRITE;
                        	$this->_last_errmsg = "retry net write err";
                        	$this->_putHandler($handler, 1);
                        	return false;
                        }

                        //print("retry read\n");
                        $retbuffer = $this->_readResponse($handler);
                        if (!$retbuffer) {
                                $this->_last_errno = ZCACHE_CLIENT_ERR_READ;
                                $this->_last_errmsg = "retry net read err";
                                $this->_putHandler($handler, 1);
                                return false;
                        }

		}
  
                //print("query ok\n");
		$this->_putHandler($handler);
		$ret_arr = mc_pack_pack2array($retbuffer);

	

		if (isset ($ret_arr['err_no']) && ZCACHE_OK == $ret_arr['err_no'] ) { 
			$this->_last_errno = ZCACHE_OK;
			if(isset($ret_arr['error'])) {
				$this->_last_errmsg = $ret_arr['error'];
			}
			return $ret_arr;
		} else {
			if (isset ($ret_arr['err_no'])) {
				$this->_last_errno = $ret_arr['err_no'];
				if(isset($ret_arr['error'])) {
					$this->_last_errmsg = $ret_arr['error'];
				}
			} else {
				$this->_last_errno = ZCACHE_CLIENT_ERR_MCPACK;
				$this->_last_errmsg = "mcpack err";
			}
			return false;
		}
	}

	private function talkWithServer($pname_str, $token_str, $logid_int, $cmd_str, $expire_int, $item_arr) {
		$handler = $this->_getHandler();
		if (!$handler) {
			$this->_last_errno = ZCACHE_CLIENT_ERR_CONNECT;
			$this->_last_errmsg = "net connect err";
			return false;
		}

		$buffer = $this->_makePack($pname_str, $token_str, $logid_int, $cmd_str, $expire_int, $item_arr);
		return $this->_talkWithServer($handler, $buffer);	
	}

	private function talkWithServer2($logid_int, $query_arr) {
		$handler = $this->_getHandler();
		if (!$handler) {
			$this->_last_errno = ZCACHE_CLIENT_ERR_CONNECT;
			$this->_last_errmsg = "net connect err";
			return false;
		}
		
		$query_pack = mc_pack_array2pack($query_arr, $this->zcfObj->MCPACK_VERSION);
		$buffer = $this->_makeNshead($logid_int, $query_pack);
		return $this->_talkWithServer($handler, $buffer);
	}

	private function _addSetReplace($pname_str, $token_str, $logid_int, $cmd_str, $key_str, $value_str, $expire_int) {
		//param judge 
		if (!is_string($pname_str) || !is_string($token_str) || !is_int($logid_int) ||
				!is_string($key_str) || strlen($key_str) > ZCACHE_MAX_KEY_LEN || strlen($key_str) == 0 ||
				!is_string($value_str) || 
				 !is_int($expire_int) ) {
			$this->_last_errno = ZCACHE_CLIENT_ERR_PARAM;
			$this->_last_errmsg = "param err";
			return false;
		}

		$query_arr = array(
			array(
				'key' => $key_str,
				'value' => $value_str
				)
			);
		$ret_arr = $this->talkWithServer($pname_str, $token_str, $logid_int, $cmd_str, $expire_int, $query_arr);
		if($ret_arr === false) return false;
		return $this->parseResult($ret_arr);
	}

	public function addOne($pname_str, $token_str, $logid_int, $key_str, $value_str, $expire_int = 0) {
		return $this->_addSetReplace($pname_str, $token_str, $logid_int, "add", $key_str, $value_str, $expire_int);
	}
	
	public function setOne($pname_str, $token_str, $logid_int, $key_str, $value_str, $expire_int = 0) {
		return $this->_addSetReplace($pname_str, $token_str, $logid_int, "set", $key_str, $value_str, $expire_int);
	}

	public function replaceOne($pname_str, $token_str, $logid_int, $key_str, $value_str, $expire_int = 0) {
		return $this->_addSetReplace($pname_str, $token_str, $logid_int, "replace", $key_str, $value_str, $expire_int);
	}

	public function deleteOne($pname_str, $token_str, $logid_int, $key_str, $delay_int = 0) {
		//param judge
		if (!is_string($pname_str) || !is_string($token_str) || !is_int($logid_int) ||
				!is_string($key_str) || strlen($key_str) > ZCACHE_MAX_KEY_LEN || strlen($key_str) == 0 ||
				!is_int($delay_int) ) {
			$this->_last_errno = ZCACHE_CLIENT_ERR_PARAM;
			$this->_last_errmsg = "param err";
			return false;
		}

		$query_arr = array(
			array(
				'key' => $key_str,
				)
			);
		$ret_arr = $this->talkWithServer($pname_str, $token_str, $logid_int, "delete", $delay_int, $query_arr);
		if($ret_arr === false) return false;
		return $this->parseResult($ret_arr);
	}

	public function increment($pname_str, $token_str, $logid_int, $key_str, $value_str, $expire_int = 0) {
		//param judge 
		if (!is_string($pname_str) || !is_string($token_str) || !is_int($logid_int) ||
				!is_string($key_str) || strlen($key_str) > ZCACHE_MAX_KEY_LEN || strlen($key_str) == 0 ||
				!is_string($value_str) || 
				!is_int($expire_int) ) {
			$this->_last_errno = ZCACHE_CLIENT_ERR_PARAM;
			$this->_last_errmsg = "param err";
			return false;
		}
		$query_arr = array(
			array(
				'key' => $key_str,
				'value' => $value_str
				)
			);
		$ret_arr = $this->talkWithServer($pname_str, $token_str, $logid_int, "increment", $expire_int, $query_arr);
		if($ret_arr === false) return false;
		return $this->parseResult2($ret_arr);
	}

	public function decrement($pname_str, $token_str, $logid_int, $key_str, $value_str, $expire_int = 0) {
		//param judge 
		if (!is_string($pname_str) || !is_string($token_str) || !is_int($logid_int) ||
				!is_string($key_str) || strlen($key_str) > ZCACHE_MAX_KEY_LEN || strlen($key_str) == 0 ||
				!is_string($value_str) || 
				!is_int($expire_int) ) {
			$this->_last_errno = ZCACHE_CLIENT_ERR_PARAM;
			$this->_last_errmsg = "param err";
			return false;
		}

		$query_arr = array(
			array(
				'key' => $key_str,
				'value' => $value_str
				)
			);
		$ret_arr = $this->talkWithServer($pname_str, $token_str, $logid_int, "decrement", $expire_int, $query_arr);
		if($ret_arr === false) return false;
		return $this->parseResult2($ret_arr);
	}	

	/*
	** return: value(string) or FALSE
	*/
	public function getOne($pname_str, $token_str, $logid_int, $key_str) {
		//param judge
		if (!is_string($pname_str) || !is_string($token_str) || !is_int($logid_int) ||
				!is_string($key_str) || strlen($key_str) > ZCACHE_MAX_KEY_LEN || strlen($key_str) == 0) {
			$this->_last_errno = ZCACHE_CLIENT_ERR_PARAM;
			$this->_last_errmsg = "param err";
			return false;
		}

		$query_arr = array(
			array(
				'key' => $key_str,
				)
			);
		$ret_arr = $this->talkWithServer($pname_str, $token_str, $logid_int, "get", -1, $query_arr);
		if($ret_arr === false) return false;
		return $this->parseResult2($ret_arr);
	}

	public function getMulti($pname_str, $token_str, $logid_int, $key_arr) {
		//param judge
		if (!is_string($pname_str) || !is_string($token_str) || !is_int($logid_int) ||
				!is_array($key_arr) || count($key_arr) > ZCACHE_MAX_QUERY_NUM) {
			$this->_last_errno = ZCACHE_CLIENT_ERR_PARAM;
			$this->_last_errmsg = "param err";
			return false;
		}

		$query_arr = array();
		foreach ($key_arr as $key) {
			if (!is_string($key) || strlen($key) > ZCACHE_MAX_KEY_LEN || strlen($key) == 0) {
				$this->_last_errno = ZCACHE_CLIENT_ERR_PARAM;
				$this->_last_errmsg = "param err";
				return false;
			}
			$query_arr[] = array(
				'key' => $key,
				);
		}
		return $this->talkWithServer($pname_str, $token_str, $logid_int, "get", -1, $query_arr);
	}

	public function setMulti($pname_str, $token_str, $logid_int, $item_arr, $expire_int = 0) {
		//param judge
		if (!is_string($pname_str) || !is_string($token_str) || !is_int($logid_int) ||
				!is_array($item_arr) || count($item_arr) > ZCACHE_MAX_QUERY_NUM ||
				!is_int($expire_int) ) {
			$this->_last_errno = ZCACHE_CLIENT_ERR_PARAM;
			$this->_last_errmsg = "param err";
			return false;
		}

		$query_arr = array();
		foreach($item_arr as $key => $val) {
			if (!is_string($key) || strlen($key) > ZCACHE_MAX_KEY_LEN || strlen($key) == 0 ||
					!is_string($val) ) {
				$this->_last_errno = ZCACHE_CLIENT_ERR_PARAM;
				$this->_last_errmsg = "param err";
				return false;
			}

			$query_arr[] = array(
				'key' => $key,
				'value' => $val
				);
		}
		return $this->talkWithServer($pname_str, $token_str, $logid_int, "set", $expire_int, $query_arr);
	}

	public function set_shareAppid($appid)
	{
		if(!is_string($appid)) return false;

		$this->_appid = $appid;
		return true;
	}
}
