<?php
if(!defined('API_ROOT_PATH')){
	define('API_ROOT_PATH', dirname(__FILE__));
}


require_once(API_ROOT_PATH . '/lib/BaeBase.class.php');

/**
 * BaeImageTransform provides simple process to a single image,</br>
 * such as cropping,rotation,transcode and adjusting lightness an image.</br>
 * You can consider each operation or simultaneously combine some operations </br>
 * at one request.
 *
 */
class BaeImageTransform extends BaeBase{
	
    
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
	
	private $paramArr;
	
	private $_requestId;
	
	public function __construct(){
		$this->paramArr = array();
		$this->_resetErrorStatus();
	}
	
	public function getRequestId()
	{
		return $this->_requestId;
	}
	
	private function setParam($key, $value)
	{
		$this->paramArr[$key] = $value;
	}
	
	public function getOperations()
	{
		
		return $this->paramArr;
	}
	
	/**
	 * 
	 * @param intZoomingType int,<p>the zooming type,including height,width and pixel</p>
	 * @param intValue int, <p>the zooming value,<b>width:</b>0-1000 <b>height:</b>0-1000
	 * <b>pixel:</b>0-1000000 </p>
	 * @param heightIntValue[optional],<p></p>
	 * @throws BaeException
	 */
	public function setZooming($intZoomingType, $intValue, $heightIntValue = 0)
    {
        //$this->_resetErrorStatus();
        try {
        	switch ($intZoomingType) {
        		case BaeImageConstant::TRANSFORM_ZOOMING_TYPE_HEIGHT:
        			$this->_checkInt($intValue, 'zooming height' , 0, 10000);
        			$this->setParam('size', 'b0_' . $intValue);
        			break;
        		case BaeImageConstant::TRANSFORM_ZOOMING_TYPE_WIDTH:
        			$this->_checkInt($intValue, 'zooming width' ,0, 10000);
        			$this->setParam('size', 'b' . $intValue . '_0');
        			break;
        		case BaeImageConstant::TRANSFORM_ZOOMING_TYPE_PIXELS:
        			$this->_checkInt($intValue, 'zooming pixels' ,0, 100000000);
        			$this->setParam('size','p' . $intValue);
        			break;
        		case BaeImageConstant::TRANSFORM_ZOOMING_TYPE_UNRATIO:
        			$this->_checkInt($intValue, 'zooming unratio' , 0, 10000);
        			$this->_checkInt($heightIntValue, 'zooming height(unratio)' , 0, 10000);
        			$this->setParam('size','u' . $intValue . '_' .$heightIntValue);
        			break;
            default:
                throw new BaeException('invalid zooming type parameters', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
            }
        } catch (Exception $ex) {
            $this->_exceptionHandler($ex);
			return false;
        }
        return true;
    }
    /**
     * Cropping an image
     * @param intX int,<p>start with x coordinates,<b>range:</b>0-10000</p>
     * @param intY int,<p>start with y coordinates,<b>range:</b>0-10000</p>
     * @param intWidth,<p>end with x+width,<b>range:</b>0-10000</p>
     * @param intHeight, <p>end with y+height,<b>range:</b>0-10000</p>
     */
    public function setCropping($intX, $intY, $intWidth, $intHeight)
    {
        //$this->_resetErrorStatus();
        try {
            $this->_checkInt($intX, 'cut_x' , 0, 10000);
            $this->_checkInt($intY, 'cut_y' , 0, 10000);
            $this->_checkInt($intHeight, 'cut_h' , 0, 10000);
            $this->_checkInt($intWidth, 'cut_w' , 0, 10000);

            $this->setParam('cut_x', $intX);
            $this->setParam('cut_y', $intY);
            $this->setParam('cut_h', $intHeight);
            $this->setParam('cut_w', $intWidth);
        } catch (Exception $ex) {
            $this->_exceptionHandler($ex);
			return false;
        }
        return true;
    }
    /**
     * Rotating an image with any degree
     * @param intDegree int, <p>the rotating value,<b>range:</b>0-360</p>
     */
    public function setRotation($intDegree)
    {
        //$this->_resetErrorStatus();
        try {
            $this->_checkInt($intDegree, 'rotate' , 0, 360);
            $this->setParam('rotate', $intDegree);
        } catch (Exception $ex) {
            $this->_exceptionHandler($ex);
			return false;
        }
        return true;
    }
    /**
     * Setting the hue of an image
     * @param intHue int, <p>the hue value,<b>range:</b>1-100</p>
     */
    public function setHue($intHue)
    {
        //$this->_resetErrorStatus();
        try {
            $this->_checkInt($intHue, 'hue' ,1, 100);
            $this->setParam('hue', $intHue);
        } catch (Exception $ex) {
            $this->_exceptionHandler($ex);
			return false;
        }
        return true;
    }
    /**
     * Setting the lightness of an image
     * @param intLightness int,<p>the lightness value <b>range:</b> bigger than 1</p>
     */
    public function setLightness($intLightness)
    {
        //$this->_resetErrorStatus();
        try {
            $this->_checkInt($intLightness, 'lightness' , 1);
            $this->setParam('lightness', $intLightness);
        } catch (Exception $ex) {
            $this->_exceptionHandler($ex);
			return false;
        }
        return true;
    }
    /**
     * Setting the contrast of an image
     * @param intContrast int,<p>0 for degenerate,1 for enhance.</p>
     */
    public function setContrast($intContrast)
    {
        //$this->_resetErrorStatus();
        try {
            $this->_checkInt($intContrast, 'contrast' , 0, 1);
            $this->setParam('contrast', $intContrast);
        } catch (Exception $ex) {
            $this->_exceptionHandler($ex);
			return false;
        }
        return true;
    }
    /**
     * Setting the sharpness of an image
     * @param intSharpness int,<p>the sharpness value,1-100 for sharpen, 101-200 for vague.</p>
     */
    public function setSharpness($intSharpness)
    {
        //$this->_resetErrorStatus();
        try {
            $this->_checkInt($intSharpness, 'sharpness' , 1, 200);
            $this->setParam('sharpen', $intSharpness);
        } catch (Exception $ex) {
            $this->_exceptionHandler($ex);
			return false;
        }
        return true;
    }
    /**
     * Setting the saturation of an image
     * @param intSaturation int, <p>the saturation value,<b>range:</b>1-100</p>
     */
    public function setSaturation($intSaturation)
    {
        //$this->_resetErrorStatus();
        try {
            $this->_checkInt($intSaturation, 'saturation', 1, 100);
            $this->setParam('saturation',$intSaturation);
        } catch (Exception $ex) {
            $this->_exceptionHandler($ex);
			return false;
        }
        return true;
    }
    /**
     * Transcoding the output image with a given type
     * @param imageType BaeImageConstant,<p>support by GIF,JPG,WEBP,BMP,PNG</P>intQuality
     * @param intQuality[optional] int, <p>the quality of an image.<b>range:</b>0-100</p>
     * @throws BaeException
     */
    public function setTranscoding($imageType, $intQuality = 60)
    {
        //$this->_resetErrorStatus();
    	try {
    		if(!is_integer($imageType)){
    			throw new BaeException(sprintf('invalid image type[%s]', $imageType),
    			BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
    		}
            switch ($imageType) {
            case BaeImageConstant::GIF:
                $this->_checkInt($intQuality, 'quality' , 0, 100);
                $this->setParam('quality', $intQuality);
                $this->setParam('imgtype', 2);
                break;
            case BaeImageConstant::JPG:
                $this->_checkInt($intQuality, 'quality' , 0, 100);
                $this->setParam('quality', $intQuality);
                $this->setParam('imgtype', 1);
                break;
            case BaeImageConstant::PNG:
                $this->setParam('imgtype', 3);
                break;
            case BaeImageConstant::WEBP:
                $this->setParam('imgtype', 4);
                break;
            default:
                throw new BaeException(sprintf('invalid image type[%s]', $imageType),
                        BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
            }
        } catch (Exception $ex) {
            $this->_exceptionHandler($ex);
			return false;
        }
        return true;
    }
    /**
     * Setting the quality of an image
     * @param intQuality int,<p>the quality value, <b>range:</b>0-100</p>
     */
    public function setQuality($intQuality = 60)
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
    
    public function setGetGifFirstFrame()
    {
        $this->setParam('tieba', 1);
        return true;
    }
    
    public function setAutorotate()
    {
        $this->setParam('autorotate', 1);
        return true;
    }
    /**
     * Flipping the image horizontally
     */
    public function horizontalFlip()
    {
        $this->setParam('flop', 1);
        return true;
    }
	/**
	 * Flipping the image vertically
	 */
    public function verticalFlip()
    {
        $this->setParam('flip', 1);
        return true;
    }
    /**
     * Clear operations
     */
    public function clearOperations(){
    	$this->paramArr = array();
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
}
