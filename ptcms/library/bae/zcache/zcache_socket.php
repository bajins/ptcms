<?php

   class ZCacheSocket 
   {
       private $connect_timeout;//记录传入的连接超时
	  private $socket           = NULL; /*socket套接字*/
	  private $socket_domain    = NULL; /*socket地址/协议族*/
	  private $socket_type      = NULL; /*socket连接类型*/
	  private $socket_protocol  = NULL; /*socket连接协议*/
	  private $socket_address   = NULL; /*socket连接地址*/
	  private $socket_port      = NULL; /*socket连接端口*/
	  private $socket_read_len  = NULL; /*socket一次读取长度*/
	  private $socket_read_type = NULL; /*socket读模式*/
	  private $socket_timeout   = NULL; /*socket超时发送接收超时*/
	  public  $socket_error     = NULL; /*socket错误信息*/
	  public  $socket_errno     = NULL; /*socket错误代码*/

	  function __construct()
	  {
		 $this->socket_domain    = 1; //AF_INET;
		 $this->socket_type      = 1; //SOCK_STREAM;
		 $this->socket_protocol  = 6; //SOL_TCP;
		 $this->socket_address   = "127.0.0.1";
		 $this->socket_port      = 9120;
		 $this->socket_read_len  = 8192;
		 $this->socket_read_type = 2; //PHP_BINARY_READ;
		 $this->socket_timeout   = 200;
		 $this->socket_error     = "";
		 $this->socket_errno     = 0;
	  }

	  function __destruct()
	  {
		 if (!empty($this->socket))
		 {
			if(is_resource($this->socket))
			{
                            fclose($this->socket);
                            //socket_close($this->socket);
			}
			$this->socket = NULL;
		 }
		 $this->socket_domain    = NULL;
		 $this->socket_type      = NULL;
		 $this->socket_protocol  = NULL;
		 $this->socket_address   = NULL;
		 $this->socket_port      = NULL;
		 $this->socket_read_len  = NULL;
		 $this->socket_read_type = NULL;
		 $this->socket_timeout   = NULL;
		 $this->socket_error     = NULL;
		 $this->socket_errno     = NULL;
	  }

	  function __set($name, $value)
	  {
		 switch ($name)
		 {
			case "socket_domain":
			if ($value == 2 /*AF_INET*/ || $value == 10 /*AF_INET6*/ || $value == 1 /*AF_UNIX*/)
			{
			   $this->$name = $value;
			}
			break;
			case "socket_type":
			if ($value == 1 /*SOCK_STREAM*/ || $value = 2 /*SOCK_DGRAM*/ || $value == 3 /*SOCK_RAW*/ ||
			$value == 5 /*SOCK_SEQPACKET*/ || $value == 4 /*SOCK_RDM*/)
			{
			   $this->$name = $value;
			}
			break;
			case "socket_protocol":
			if ($value == 6 /*SOL_TCP*/ || $value == 17 /*SOL_UDP*/ || $value == 1 /*SOL_SOCKET*/)
			{
			   $this->$name = $value;
			}
			break;
			case "socket_address":
			//preg_match_all("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", $value, $match);
			//if ($match[0][0] == $value)
			//{
			   $this->$name = $value;
			//}
			break;
			case "socket_port":
			if ($value > 0 && $value <= 65535)
			{
			   $this->$name = $value;
			}
			break;
			case "socket_read_len":
			if ($value > 0 && $value <= 8192)
			{
			   $this->$name = $value;
			}
			break;
			case "socket_read_type":
			if ($value == 2 /*PHP_BINARY_READ*/ || $value == 1 /*PHP_NORMAL_READ*/)
			{
			   $this->$name = $value;
			}
			break;
			case "socket_timeout":
			if ($value >= 0)
			{
			   $this->$name = $value;
			}
			break;
		 }
	  }

	  function __get($name)
	  {
		 switch ($name)
		 {
			case "socket_domain":
			case "socket_type":
			case "socket_protocol":
			case "socket_address":
			case "socket_port":
			case "socket_read_len":
			case "socket_read_type":
			case "socket_timeout":
			case "socket_error":
			case "socket_errno":
			return $this->$name;
			break;
			default:
			return NULL;
			break;
		 }
	  }

	  function set_vars_from_array($vars_arr)
	  {
		 foreach ($vars_arr as $key => $value)
		 {
			$this->__set($key, $value);
		 }
	  }

	  function get_vars_to_array()
	  {
		 $vars_arr = array(
			'socket_domain'    => $this->socket_domain,
			'socket_type'      => $this->socket_type,
			'socket_protocol'  => $this->socket_protocol,
			'socket_address'   => $this->socket_address,
			'socket_port'      => $this->socket_port,
			'socket_read_len'  => $this->socket_read_len,
			'socket_read_type' => $this->socket_read_type,
			'socket_timeout'   => $this->socket_timeout,
			'socket_error'     => $this->socket_error,
			'socket_errno'     => $this->socket_errno,
		 );
		 return $vars_arr;
	  }

	  // NOTE: this function may report PHP WARNING
	  function tcp_connect($address, $port, $ctimeout = NULL)
	  {
		$fp = fsockopen($address, $port, $error, $errstr, $ctimeout);
		if(!$fp) {
			return false;
		}

		/*if(!stream_set_blocking($fp, 0)) {
			fclose($fp);
			return false;
		}*/
		return $fp;
	  }

	  public function connect($timeout)
	  {
              $this->connect_timeout = $timeout;
		 $this->socket=$this->tcp_connect($this->socket_address, $this->socket_port, $timeout);
		 if(is_resource($this->socket))
		 {
			return true;
		 }
		 return false;
	  }

	  function close()
	  {
		 if ($this->socket !== false || $this->socket !== NULL)
		 {
			if(is_resource($this->socket))
			{
			   fclose($this->socket);
			}
			$this->socket = NULL;
		 }
	  }

	  function write($buffer)
	  {
              $orignbuf = $buffer;
              $ret = false;
		 while (true)
		 {
			$ret = @fwrite($this->socket, $buffer, strlen($buffer));
			if ($ret == false)
			{
			   	fclose($this->socket);
				$this->socket = NULL;
                                break;
			   	//return false;
                                
			}

			if ($ret == strlen($buffer))
				break;

			$buffer = substr($buffer, $ret);
		 }
                 if ($ret == false)//发送失败
                     {
                         $ret = $this->connect($this->connect_timeout);
                         if ($ret ===false)//重新连接失败
                             {
                                 return $ret;
                             }
                         $this->socket = $ret;
                     }
                 else//发送成功
                     {
                         return $ret;
                     }

                 $ret = false;
                 $buffer = $orignbuf;
		 while (true)
		 {
			$ret = @fwrite($this->socket, $buffer, strlen($buffer));
			if ($ret == false)
			{
			   	fclose($this->socket);
				$this->socket = NULL;
                                return false;
                                
			}

			if ($ret == strlen($buffer))
				break;

			$buffer = substr($buffer, $ret);
		 }
                 
		 return true;
	  }

	  function read($len)
	  {
		 $buffer = "";
		 while ((@ $temp = fread($this->socket, $len)) !== false)
		 {
                        if( strlen($temp) == 0 )
                        {
                            //print("read error\n");
                            return false;
                        }
			$buffer .= $temp;
			$len -= strlen($temp);
			if ($len === 0)
				break;
		 }
		 if ($temp === false)
		 {
			   fclose($this->socket);
				$this->socket = NULL;
			return false;
		 }
		 return $buffer;
	  }
   }
