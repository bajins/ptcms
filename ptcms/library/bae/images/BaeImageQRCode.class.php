<?php
if(!defined('API_ROOT_PATH')){
	define('API_ROOT_PATH', dirname(__FILE__));
}


require_once(API_ROOT_PATH . '/lib/BaeBase.class.php');
require_once(API_ROOT_PATH . '/images/BaeImageConstant.class.php');

class BaeImageQRCode extends BaeBase{
	
	private $text;
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
	
	public function __construct($text = NULL)
	{
		$this->_resetErrorStatus();
		$this->paramArr = array();
		if(!is_null($text)){
			$this->setText($text);
		}

	}
	/**
	 * Setting the text script
	 * @param text string,<p>the text script to generate QR code<b>range:</b>1-500 characters</p>
	 */
	public function setText($text)
	{
		try {
			$this->_checkString($text);//check if it is not a string
			$text = mb_convert_encoding($text, 'GBK', 'UTF-8');
			//echo "---------:". $text." len:". strlen($text);
			$this->_checkString($text, 1, 500);
			$this->text = $text;
			$this->paramArr['text'] = $text;
		}catch (Exception $ex) {
            	$this->_exceptionHandler($ex);
            	return false;
		 	}
		return true;
	}
	
	public function getText()
	{
		return $this->text;
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
	 * The version of the QR code
	 * @param intVersion int,<p><b>range:</b>0-30</p>
	 */
	public function setVersion($intVersion)
	{
		//$this->_resetErrorStatus();
        try {
            $this->_checkInt($intVersion, 'version', 0, 30);
            $this->setParam('version', $intVersion);
        } catch (Exception $ex) {
            $this->_exceptionHandler($ex);
			return false;
        }
        return true;
	}
	/**
	 * The size of the QR code
	 * @param intSize int, <p><b>range:</b>1-100</p>
	 */
	public function setSize($intSize)
	{
		//$this->_resetErrorStatus();
        try {
            $this->_checkInt($intSize, 'size', 1, 100);
            $this->setParam('size', $intSize);
        } catch (Exception $ex) {
            $this->_exceptionHandler($ex);
			return false;
        }
        return true;
	}
	/**
	 * The error correction level of an QR code
	 * @param intLevel int, <p><b>range:</b>1-4</p>
	 */
	public function setLevel($intLevel)
	{
		//$this->_resetErrorStatus();
        try {
            $this->_checkInt($intLevel, 'level', 1, 4);
            $this->setParam('level', $intLevel);
        } catch (Exception $ex) {
            $this->_exceptionHandler($ex);
			return false;
        }
        return true;
	}
	/**
	 * The margin of a QR code
	 * @param intMargin int,<p><b>range:</b>1-100</p>
	 */
	public function setMargin($intMargin)
	{
		//$this->_resetErrorStatus();
        try {
            $this->_checkInt($intMargin, 'margin', 1, 100);
            $this->setParam('margin', $intMargin);
        } catch (Exception $ex) {
            $this->_exceptionHandler($ex);
			return false;
        }
        return true;
	}
	/**
	 * Foreground color of QR code
	 * @param strForeground string,<p><b>range:</b>6 bits RGB,default '000000'</p>
	 */
	public function setForeground($strForeground)
	{
		//$this->_resetErrorStatus();
        try {
            $this->_checkRGB($strForeground);
            $this->setParam('foreground', $strForeground);
        } catch (Exception $ex) {
            $this->_exceptionHandler($ex);
			return false;
        }
        return true;
	}
	/**
	 * Background color of QR code
	 * @param strBackground string,<p><b>range:</b>6 bits RGB,default 'FFFFFF'</p>
	 */
	public function setBackground($strBackground)
	{
		//$this->_resetErrorStatus();
        try {
            $this->_checkRGB($strBackground);
            $this->setParam('background', $strBackground);
        } catch (Exception $ex) {
            $this->_exceptionHandler($ex);
			return false;
        }
        return true;
	}
	
	public function clearOperations(){
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
	
    private function _checkString($str, $min=null, $max=null)
	{
		if (!is_string($str)){
			throw new BaeException(sprintf('[%s] parameter not a string', $str), BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
		}
		if($min !== null && strlen($str) < $min){
			throw new BaeException(sprintf('[%s] parameter less than minimum', $str), BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
		}
		if($max !== null && strlen($str) > $max) {
			throw new BaeException(sprintf('[%s] parameter greater than maximum', $str), BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
		}
	}
	
	private function _checkRGB($strRGB){
		$this->_checkString($strRGB, 6, 6);
	    $pattern = "/[AaBbCcDdEeFf0123456789]{6}/";
    	// regular match
    	if(!preg_match($pattern, $strRGB)){
    		throw new BaeException(sprintf('invalid RGB color[%s]', $strRGB),
    		BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);//
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
?>