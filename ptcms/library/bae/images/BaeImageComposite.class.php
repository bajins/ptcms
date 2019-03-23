<?php
if(!defined('API_ROOT_PATH')){
	define('API_ROOT_PATH', dirname(__FILE__));
}


require_once(API_ROOT_PATH . '/lib/BaeBase.class.php');
require_once(API_ROOT_PATH . '/images/BaeImageConstant.class.php');
//require_once(API_ROOT_PATH . '/images/BaeImageSource.class.php');

class BaeImageComposite extends BaeBase{
	
	private $baeImageSource;
	private $isURL;
	private $paramArr = array();

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
	
	public function __construct($baeImageSource = NULL)
	{	
		$isURL = true;
		$this->_resetErrorStatus();
		try{
			if(!is_null($baeImageSource)){
				if($isURL === true){
					$this->_examURL($baeImageSource);
					$this->baeImageSource = $baeImageSource;
				}
				else{
					if(!$this->_checkString($baeImageSource, 0, 2*1024*1024)){
						throw new BaeException('image must be less than 2M', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
					}
				}
				$this->isURL = true;
			}
		}catch (Exception $ex) {
			$this->_exceptionHandler($ex);
			return false;
		}
	}
	/**
	 * Setting the image source
	 * @param baeImageSource string, <p>image source url or byte string</p>
	 * @param isURL bool,<p>true for $baeImageSource is url pattern, false for byte string</p>
	 */
	public function setBaeImageSource($baeImageSource)
	{
		$isURL=true;
		try{
			if(empty($baeImageSource)){
				throw new BaeException('no bae image source', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			if($isURL === true){
				$this->_examURL($baeImageSource);
				$this->baeImageSource = $baeImageSource;
			}
			else{
				if(!$this->_checkString($baeImageSource, 0, 2*1024*1024)){
					throw new BaeException('image must be less than 2M', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
				}
			}
			$this->isURL = true;
		}catch (Exception $ex) {
			$this->_exceptionHandler($ex);
			return false;
		}
		return true;
	}
	
	public function getBaeImageSource()
	{
		return array('data'=>$this->baeImageSource, 'isURL'=>$this->isURL);
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
	 * Position of the image apart from anchor point
	 * @param x_offset int
	 * @param y_offset int
	 */
	public function setPos($x_offset, $y_offset)
	{
		//$this->_resetErrorStatus();
        try {
            $this->_checkInt($x_offset, 'x_offset');//without restriction
            $this->_checkInt($y_offset, 'y_offset');//without restriction
            $this->setParam('x_offset', $x_offset);
            $this->setParam('y_offset', $y_offset);
        } catch (Exception $ex) {
            $this->_exceptionHandler($ex);
			return false;
        }
        return true;

	}
	/**
	 * Opacity of the image 
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
	 * Anchor point of the image
	 * @param anchor BaeImageConstant,<p><b>range:</b>0-8,default 0</p>
	 */
	public function setAnchor($anchor)
	{
		//$this->_resetErrorStatus();
        try {
            $this->_checkInt($anchor, 'anchor', 0, 8);
            $this->setParam('anchor_point', $anchor);
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
    
    private function _checkString($str, $min, $max)
	{
		if (is_string($str) && strlen($str) >= $min && strlen($str) <= $max) {
			return true;
		}
		return false;
	}
	private function _checkString2($str, $prompt, $min=null, $max=null)
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
	
    /*
     * Examming url pattern
     * */
    private function _examURL($url)
    {
    	$this->_checkString2($url, 'url', 0, 2048);
    	$pattern = "/^(http[s]?:\/\/){1}[.]*/";
    	// regular match
    	if(!preg_match($pattern, $url)){
    		throw new BaeException(sprintf('invalid image source url[%s]', $url),
    		BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);//
    	}
    	return true;
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
