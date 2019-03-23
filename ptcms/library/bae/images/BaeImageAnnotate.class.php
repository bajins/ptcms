<?php
if(!defined('API_ROOT_PATH')){
	define('API_ROOT_PATH', dirname(__FILE__));
}


require_once(API_ROOT_PATH . '/lib/BaeBase.class.php');
require_once(API_ROOT_PATH . '/images/BaeImageConstant.class.php');

class BaeImageAnnotate extends BaeBase{
	
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
	 * The text script of annotation
	 * @param text string,<p><b>range:</b>1-500 characters</p>
	 */
	public function setText($text)
	{
		try {
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
	 * The opacity of an image
	 * @param floatOpacity float,<p><b>range:</b>0-1</p>
	 */
	public function setOpacity($floatOpacity)
	{
		//$this->_resetErrorStatus();
        try {
            $this->_checkFloat($floatOpacity, 'opacity', 0.0, 1.0);
            $this->setParam('opacity', $floatOpacity);
        } catch (Exception $ex) {
            $this->_exceptionHandler($ex);
			return false;
        }
        return true;

	}
	/**
	 * Font options
	 * @param name BaeImageConstant
	 * @param size int, <p><b>range:</b>0-1000,default 5</p>
	 * @param color string,<p><b>range:</b>6 bits RGB,default '000000'</p>
	 */
	public function setFont($name, $size, $color)
	{
		//$this->_resetErrorStatus();
        try {
            $this->_checkInt($name,'font name', 0, 4);
            $this->_checkInt($size, 'font size' ,0, 1000);
            $this->_checkRGB($color);
            $this->setParam('font_name', $name);
            $this->setParam('font_color', $color);
            $this->setParam('font_size', $size);
        } catch (Exception $ex) {
            $this->_exceptionHandler($ex);
			return false;
        }
        return true;

	}
	/**
	 * Position of the text script
	 * @param x_offset int,<p><b>range:</b>0-width of the image</p>
	 * @param y_offset int,<p><b>range:</b>0-height of the image</p>
	 */
	public function setPos($x_offset, $y_offset)
	{
		//$this->_resetErrorStatus();
        try {
            $this->_checkInt($x_offset, 'x_offset', 0);//without restriction
            $this->_checkInt($y_offset, 'y_offset', 0);//without restriction
            $this->setParam('x_offset', $x_offset);
            $this->setParam('y_offset', $y_offset);
        } catch (Exception $ex) {
            $this->_exceptionHandler($ex);
			return false;
        }
        return true;

	}
	/**
	 * The output image type
	 * @param outputcode BaeImageConstant,<p>support by JPG,GIF,BMP,PNG,WEBP
	 */
	public function setOutputCode($outputcode)
	{
		//$this->_resetErrorStatus();
        try {
            $this->_checkInt($outputcode, 'outputcode', 0, 4);
            $this->setParam('desttype', $outputcode);
        } catch (Exception $ex) {
            $this->_exceptionHandler($ex);
			return false;
        }
        return true;

	}
	/**
	 * The quality of output image
	 * @param intQuality int, <p><b>range:</b>0-100,default 80</p>
	 */
	public function setQuality($intQuality)
	{
		//$this->_resetErrorStatus();
        try {
            $this->_checkInt($intQuality, 'quality', 0, 100);
            $this->setParam('quality', $intQuality);
        } catch (Exception $ex) {
            $this->_exceptionHandler($ex);
			return false;
        }
        return true;

	}
	
	public function clearOperations()
	{
		$this->paramArr = array();
		return true;
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
	
	private function _checkFloat($num, $prompt, $floatMin = null, $floatMax = null)
    {
    	if(is_integer($num)){
    		$num = floatval($num);
    	}
    	if(!is_float($num)){
    		throw new BaeException(sprintf('[%s] parameter not a float', $prompt), BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
    	}
    	if($floatMin !== null && $num < $floatMin){
    		throw new BaeException(sprintf('[%s] parameter less than minimum', $prompt), BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
    	}
    	if($floatMax !== null && $num > $floatMax){
    		throw new BaeException(sprintf('[%s] parameter greater than maximum', $prompt), BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
    	}
    }
    
    private function _checkEnum($value, $arrEnum = array(0,1,2),$name)
    {
        if(!in_array($value, $arrEnum)) {
            throw new Exception(sprintf('Param % is incorrect',$name));
        }
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