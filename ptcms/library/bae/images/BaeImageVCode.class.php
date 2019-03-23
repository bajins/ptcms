<?php
if(!defined('API_ROOT_PATH')){
	define('API_ROOT_PATH', dirname(__FILE__));
}


require_once(API_ROOT_PATH . '/lib/BaeBase.class.php');
require_once(API_ROOT_PATH . '/images/BaeImageConstant.class.php');

class BaeImageVCode extends BaeBase{
	
	private $input;
	private $strSecret;
	private $paramArr;
	

    
    private $_arrayErrorMap = array(
		'0' => 'php sdk error',
		BaeImageConstant::BAE_IMAGEUI_SDK_SYS => 'php sdk error',
		BaeImageConstant::BAE_IMAGEUI_SDK_INIT_FAIL => 'php sdk init error',
		BaeImageConstant::BAE_IMAGEUI_SDK_PARAM => 'param invalid',
		BaeImageConstant::BAE_IMAGEUI_SDK_HTTP_STATUS_ERROR_AND_RESULT_ERROR
            => 'http status is error, and the body returned is not a json string',
		BaeImageConstant::BAE_IMAGEUI_SDK_HTTP_STATUS_OK_BUT_RESULT_ERROR
            => 'http status is ok, but the body returned is not a json string',
	);
	
	public function __construct()
	{
		$this->_resetErrorStatus();
		$this->paramArr = array('len'=>4,'setno'=>0);
	}
	public function getOperations()
	{
		return $this->paramArr;
	}
	
	private function setParam($key, $value)
	{
		$this->paramArr[$key] = $value;
	}
	/**
	 * The bits of the verification code
	 * @param intLen integer,<p><b>range:</b>4-5</p>
	 */
	public function setLen($intLen)
	{
		try {
			$this->_checkInt($intLen, 'verification code len', 4, 5);
			$this->setParam('len', $intLen);
		}catch (Exception $ex) {
			$this->_exceptionHandler($ex);
			return false;
		}
		return true;
	}
	
	/**
	 * The pattern of the verification code
	 * @param pattern integer,<p><b>range:</b>0-3</p>
	 */
	public function setPattern($pattern)
	{
		try {
			$this->_checkInt($pattern, 'verification code pattern', 0, 3);
			$this->setParam('setno', $pattern);
		}catch (Exception $ex) {
			$this->_exceptionHandler($ex);
			return false;
		}
		return true;
	}
	
	/**
	 * The input to verify
	 * @param strInput string,<p><b>range:</b>4-5</p>
	 */
	public function setInput($strInput)
	{
		try {
			$this->_checkString($strInput, 'vcode.input' ,4, 5);
			$this->setParam('input', $strInput);
			$this->input = $strInput;
		}catch (Exception $ex) {
			$this->_exceptionHandler($ex);
			return false;
		}
		return true;
	}
	
	public function getInput(){
		return $this->input;
	}
	
	/**
	 * The secret text of the verification code
	 * @param strSecret string,<p><b>range:</b>without restriction</p>
	 */
	public function setSecret($strSecret)
	{
		try {
			$this->_checkString($strSecret,'vcode.secret', 368, 368);//without restriction
			$this->setParam('vcode', $strSecret);
			$this->strSecret = $strSecret;
		}catch (Exception $ex) {
			$this->_exceptionHandler($ex);
			return false;
		}
		return true;
	}
	public function getSecret(){
		return $this->strSecret;
	}
	
	public function clearOperations()
	{
		$this->paramArr = array();
		return true;
	}
	
   	private function _checkInt($num, $prompt, $intMin = -1, $intMax = -1)
    {
        if (!is_integer($num)) {
            throw new BaeException(sprintf('[%s] parameter not an integer', $prompt), BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
        }
        if ($intMin !== -1 && $num < $intMin) {
            throw new BaeException(sprintf('[%s] parameter less than minimum', $prompt), BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
        }
        if ($intMax !== -1 && $intMax < $num) {
            throw new BaeException(sprintf('[%s] parameter greater than maximum', $prompt), BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
        }
    }
    
	private function _checkString($str, $prompt, $min=null, $max=null)
	{
		if (!is_string($str)){
			throw new BaeException(sprintf('[%s] parameter not a string', $prompt), BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
		}
		if($min !== null && strlen($str) < $min){
			throw new BaeException(sprintf('[%s] parameter less than minimum', $prompt), BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
		}
		if($max !== null && strlen($str) > $max) {
			throw new BaeException(sprintf('[%s] parameter greater than maximum', $prompt), BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
		}
	}
	
    private function _resetErrorStatus()
	{
		$this->errcode = 0;
		$this->errmsg = $this->_arrayErrorMap[$this->errcode];
		$this->_requestId = 0;
	}
	
	private function _exceptionHandler($ex)
	{
		$tmpCode = $ex->getCode();
		if (0 === $tmpCode) {
			$tmpCode = BaeImageConstant::BAE_IMAGEUI_SDK_SYS;
		}

		$this->errcode = $tmpCode;
		if ($this->errcode >= 30000) {
			$this->errmsg = $ex->getMessage();
		} else {
			$this->errmsg = $this->_arrayErrorMap[$this->errcode]
                    . ', detail info[' . $ex->getMessage()
                    . ', break point: ' . $ex->getFile(). ':' . $ex->getLine() . '].'
                    . "\n"
                    . $ex->getTraceAsString();
		}
	}
}