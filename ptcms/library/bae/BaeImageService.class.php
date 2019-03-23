<?php
if(!defined('API_ROOT_PATH')){
	define('API_ROOT_PATH', dirname(__FILE__));
}


require_once(API_ROOT_PATH . '/lib/BaeBase.class.php');
require_once(API_ROOT_PATH . '/images/BaeImageConstant.class.php');
require_once(API_ROOT_PATH . '/images/BaeImageAnnotate.class.php');
require_once(API_ROOT_PATH . '/images/BaeImageComposite.class.php');
require_once(API_ROOT_PATH . '/images/BaeImageQRCode.class.php');
require_once(API_ROOT_PATH . '/images/BaeImageTransform.class.php');
require_once(API_ROOT_PATH . '/images/BaeImageVCode.class.php');
require_once(API_ROOT_PATH . '/lib/RequestCore.class.php');

class BaeImageService extends BaeBase{
	
    const METHOD = 'method';
	const HOST = 'host';
	const PRODUCT = 'imageui';
	const SIGN = 'sign';
	const ACCESS_TOKEN = 'access_token';
	const SECRET_KEY = 'client_secret';
	const ACCESS_KEY = 'client_id';
	const DEFAULT_HOST = 'image.duapp.com';
    const TIMESTAMP = 'timestamp';
    const EXPIRES = 'expires';
    const VERSION = 'v';
    const RESOURCE = 'resource';
    
    private $_requestId;
    private $_clientId;
    private $_clientSecret;
    private $_host;
	
	private $paramArr;
	private $ProcType_Annotate = 1;
	private $ProcType_Composite = 2;
	private $ProcType_QRCode = 0;
	
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
	
	
    public function getRequestId()
	{
		return $this->_requestId;
	}
	
	public function __construct($accessKey, $secretKey, $host)
	{
        $this->paramArr = array();
        
		if (is_null($accessKey) || $this->_checkString($accessKey, 1, 64)) {
			$this->_clientId = $accessKey;
		} else {
			throw new BaeException("invalid param - access key[${accessKey}], which must be a 1 - 64 length string",
                    BaeImageConstant::BAE_IMAGEUI_SDK_INIT_FAIL);
		}

		if (is_null($secretKey) || $this->_checkString($secretKey, 1, 64)) {
			$this->_clientSecret = $secretKey;
		} else {
			throw new BaeException("invalid param - secret key[${secretKey}], which must be a 1 - 64 length string",
                    BaeImageConstant::BAE_IMAGEUI_SDK_INIT_FAIL);
		}

		if (is_null($host) || $this->_checkString($host, 1, 1024)) {
			if (!is_null($host)) {
				$this->_host = $host;
			}
		} else {
			throw new BaeException("invalid param - host[${host}], which must be a 1 - 1024 length string",
                    BaeImageConstant::BAE_IMAGEUI_SDK_INIT_FAIL);
		}
		$this->_resetErrorStatus();
		
	}
	
	private function _get_ak_sk_host(&$opt, $opt_key, $member, $g_key, $env_key, $min, $max)
	{
		$dis = array(
            'client_id' => 'access_key',
            'client_secret' => 'secret_key',
            'host' => 'host'
        );
        
		global $$g_key;
        
		if (isset($opt[$opt_key])) {
			if (!$this->_checkString($opt[$opt_key], $min, $max)) {
				throw new BaeException('invalid ' . $dis[$opt_key] . ' in $optinal('
                        . $opt[$opt_key] . '), which must be a ' . $min . ' - ' . $max . ' length string',
                        BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			return;
		}
		if ($this->_checkString($member, $min, $max)) {
			$opt[$opt_key]= $member;
			return;
		}
		if (isset($$g_key)) {
			if (!$this->_checkString($$g_key, $min, $max)) {
				throw new BaeException('invalid ' . $g_key . ' in global area('
                        . $$g_key . '), which must be a ' . $min . ' - ' . $max . ' length string',
                        BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			$opt[$opt_key]= $$g_key;
			return;
		}
		if (false !== getenv($env_key)) {
			if (! $this->_checkString(getenv($env_key), $min, $max)) {
				throw new BaeException('invalid ' . $env_key . ' in environment variable('
                        . getenv($env_key). '), which must be a ' . $min . ' - ' . $max . ' length string',
                        BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			$opt[$opt_key]= getenv($env_key);
			return;
		}
        if ($opt_key === self::HOST) {
            $opt[$opt_key]= self::DEFAULT_HOST;
            return;
        }

		throw new BaeException('no param(' . $dis[$opt_key] . ')was found', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
	}
    
    private function _adjustOpt(&$opt)
    {
		if (! isset($opt) || empty($opt) || !is_array($opt)) {
			throw new BaeException('no params are set', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
		}
		if (!isset($opt[self::TIMESTAMP])) {
			$opt[self::TIMESTAMP]= time();
		}

		$this->_get_ak_sk_host($opt, self::ACCESS_KEY, $this->_clientId,
                'g_accessKey', 'HTTP_BAE_ENV_AK', 1, 64);
		$this->_get_ak_sk_host($opt, self::SECRET_KEY, $this->_clientSecret,
                'g_secretKey', 'HTTP_BAE_ENV_SK', 1, 64);
		$this->_get_ak_sk_host($opt, self::HOST, $this->_host,
                'g_host', 'HTTP_BAE_ENV_ADDR_BUS', 1, 1024);
	}
    
    private function _getSign(&$opt, &$arrContent, $arrNeed = array())
    {
		$arrData = array();
		$arrContent = array();
		$arrNeed[] = self::TIMESTAMP;
		$arrNeed[] = self::METHOD;
		$arrNeed[] = self::ACCESS_KEY;
		if (isset($opt[self::EXPIRES])) {
			$arrNeed[] = self::EXPIRES;
		}
		if (isset($opt[self::VERSION])) {
			$arrNeed[] = self::VERSION;
		}
		
		$arrExclude = array(self::HOST, self::SECRET_KEY);
		foreach ($arrNeed as $key) {
			if (!isset($opt[$key]) || (!is_integer($opt[$key]) && empty($opt[$key]))) {
				throw new BaeException("lack param(${key})", BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			if (in_array($key, $arrExclude)) {
				continue;
			}
			$arrData[$key] = $opt[$key];
			$arrContent[$key] = $opt[$key];
		}
		foreach ($opt as $key => $value) {
			if (!in_array($key, $arrNeed) && !in_array($key, $arrExclude)) {
				$arrData[$key]= $value;
				$arrContent[$key]= $value;
			}
		}
		ksort($arrData);
		$url = 'http://' . $opt[self::HOST] . '/rest/2.0/' . self::PRODUCT . '/';
		if (isset($opt[self::RESOURCE]) && !is_null($opt[self::RESOURCE])){
            $url .= $opt[self::RESOURCE];
            $arrContent[self::RESOURCE]= $opt[self::RESOURCE];
		}else {
            $url .= self::RESOURCE;
		}
		$basicString = 'POST' . $url;
		foreach ($arrData as $key => $value) {
			$basicString .= $key . '=' . $value;
		}
		$basicString .= $opt[self::SECRET_KEY];
		
		$sign = md5(urlencode($basicString));
		$arrContent[self::SIGN] = $sign;
		$arrContent[self::HOST] = $opt[self::HOST];
	}
	
	private function _baseControl($opt)
	{
		$content = '';
		$resource = self::RESOURCE;
        if (isset($opt[self::RESOURCE]) && !is_null($opt[self::RESOURCE])) {
            $resource = $opt[self::RESOURCE];
        }
		
		$host = $opt[self::HOST];
		unset($opt[self::HOST]);

		foreach ($opt as $k => $v) {
			if (is_string($v)) {
				$v = urlencode($v);
			}
			$content .= $k . '=' . $v . '&';
		}
		$content = substr($content, 0, strlen($content)- 1);
		$url = 'http://' . $host . '/rest/2.0/' . self::PRODUCT . '/';
		$url .= $resource;

		$request = new RequestCore($url);
		$headers['Content-Type'] = 'application/x-www-form-urlencoded';
		$headers['User-Agent'] = 'Baidu ImageUi Phpsdk Client';
		foreach ($headers as $headerKey => $headerValue) {
			$headerValue = str_replace(array("\r", "\n"), '', $headerValue);
			if ($headerValue !== '') {
				$request->add_header($headerKey, $headerValue);
			}
		}
		$request->set_method('POST');
		$request->set_body($content);
		$request->send_request();
		return new ResponseCore($request->get_response_header(),
                $request->get_response_body(),
                $request->get_response_code());
	}
	
	
	private function _commonProcess($paramOpt = NULL, $arrNeed = array())
	{
		$this->_adjustOpt($paramOpt);
		$arrContent = array();
		$this->_getSign($paramOpt, $arrContent, $arrNeed);
		/****************************debug**********************/
		//var_dump($arrContent);
		$ret = $this->_baseControl($arrContent);
		if (empty($ret)) {
			throw new BaeException('base control returned empty object', BaeImageConstant::BAE_IMAGEUI_SDK_SYS);
		}
		if ($ret->isOK()) {
			$result = json_decode($ret->body, true);
			if (is_null($result)) {
				throw new BaeException($ret->body,
                        BaeImageConstant::BAE_IMAGEUI_SDK_HTTP_STATUS_OK_BUT_RESULT_ERROR);
			}
			$this->_requestId = $result['request_id'];
			
			return $result;
		}
		$result = json_decode($ret->body, true);
		if (is_null($result)) {
			throw new BaeException('ret body: ' . $ret->body,
                    BaeImageConstant::BAE_IMAGEUI_SDK_HTTP_STATUS_ERROR_AND_RESULT_ERROR);
		}
		$this->_requestId = $result['request_id'];
		
		trigger_error("request_id : " . $this->_requestId . ", fail . error_msg: "
                 . $result['error_msg'].", error_code: " . $result['error_code'],
                E_USER_WARNING);
		throw new BaeException($result['error_msg'], $result['error_code']);
	}
	
	/**
	 *  Apply transforms into an image 
	 * @param baeImageSource array <p>image source,
	 * contains image source or image url</p>
	 * @param baeImageTransform BaeImageTransform, <p>contains variety transforms</p> 
	 * @param isURL bool<p>true for image source is URL, false for image 
	 * 		  byte string</p>
	 * @return Image-relevant data,if success, otherwise return false
	 */
	public function applyTransformByObject($baeImageSource, $baeImageTransform)
	{
		$isURL=true;
		$URL = '';
		$this->_resetErrorStatus();
		try {
			if(empty($baeImageTransform) || !($baeImageTransform instanceof BaeImageTransform)){
				throw new BaeException('input source is not an instance of BaeImageTransform', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			if($baeImageTransform->errcode !== 0){
				$this->errcode = $baeImageTransform->errcode;
				$this->errmsg = $baeImageTransform->errmsg;
				return false;

			}
			if(empty($baeImageSource)){
				throw new BaeException('no bae image source', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
				
			/********************examming url pattern*******************************/
			if($isURL === true){
				$this->_examURL($baeImageSource);
				$URL = $baeImageSource;
			}else{
				//restrict image into 2M
				if(!$this->_checkString($baeImageSource, 0, 2*1024*1024)){
					throw new BaeException('image must be less than 2M', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
				}
			}

			$paramArr = $baeImageTransform->getOperations();// obtain the operations setting on the image
			$paramArr['src'] = $URL;
			$arrArgs = array(
			self::METHOD => 'process',/*'appid'=>"appid7avderfd05",'dev_uid'=>'12345','secret_key'=>'pNceSAlS4HB8fToDilmXQvwSc6nHInHW',*/
			);
			$arrArgs = array_merge($arrArgs, $paramArr);
			return $this->_commonProcess($arrArgs,array('src',));

		} catch (Exception $ex) {
			$this->_exceptionHandler($ex);
			return false;
		}

	}
	
	/**
	 *  Apply transforms into an image
	 * @param image string <p>image source,
	 * contains image source or image url</p>
	 * @param params array <p>options operating into an image</p>
	 * @param isURL bool<p>true for image source is URL, false for image 
	 * 		  byte string</p>
	 * @return Image-relevant data,if success, otherwise return false
	 * 
	 */
	public function applyTransform($baeImageSource, $params=array())
	{
		$isURL=true;
		$URL = '';
		$baeImageTransform = new BaeImageTransform();
		$this->_resetErrorStatus();
		try{
			if(!is_array($params)){
				throw new BaeException(sprintf('invalid parameters, [%s] should be array', $params),
				BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			
			if (!empty($params)){
				foreach ($params as $key => $value){
					if(!is_string($key)){
						throw new BaeException(sprintf('invalid key [%s]', $key),
						BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
					}
					switch($key){
						case BaeImageConstant::TRANSFORM_ZOOMING:
							if(!is_array($value) || count($value) < 2 || count($value) > 3){
								throw new BaeException('invalid zooming param', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
							}
							if(count($value) == 3){
								$baeImageTransform->setZooming($value[0], $value[1],$value[2]);
							}else{
								$baeImageTransform->setZooming($value[0], $value[1]);
							}
							break;
						case BaeImageConstant::TRANSFORM_CROPPING:
							if(!is_array($value) || count($value) != 4){
								throw new BaeException('invalid cropping param', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
							}
							$baeImageTransform->setCropping($value[0], $value[1], $value[2], $value[3]);
							break;
						case BaeImageConstant::TRANSFORM_ROTATE:
							$baeImageTransform->setRotation($value);
							break;
						case BaeImageConstant::TRANSFORM_HUE:
							$baeImageTransform->setHue($value);
							break;
						case BaeImageConstant::TRANSFORM_LIGHTNESS:
							$baeImageTransform->setLightness($value);
							break;
						case BaeImageConstant::TRANSFORM_CONTRAST:
							$baeImageTransform->setContrast($value);
							break;
						case BaeImageConstant::TRANSFORM_SHARPNESS:
							$baeImageTransform->setSharpness($value);
							break;
						case BaeImageConstant::TRANSFORM_SATURATION:
							$baeImageTransform->setSaturation($value);
							break;
						case BaeImageConstant::TRANSFORM_TRANSCODE:
							if(!is_array($value) || count($value)  > 2 || count($value) == 0){
								throw new BaeException('invalid transcode param', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
							}
							if(count($value) == 2){
								$baeImageTransform->setTranscoding($value[0], $value[1]);
							}else{
								$baeImageTransform->setTranscoding($value[0]);
							}
							break;
						case BaeImageConstant::TRANSFORM_QUALITY:
							$baeImageTransform->setQuality($value);
							break;
						case BaeImageConstant::TRANSFORM_GETGIFFIRSTFRAME:
							$baeImageTransform->setGetGifFirstFrame();
							break;
						case BaeImageConstant::TRANSFORM_HORIZONTALFLIP:
							$baeImageTransform->horizontalFlip();
							break;
						case BaeImageConstant::TRANSFORM_VERTICALFLIP:
							$baeImageTransform->verticalFlip();
							break;
						case BaeImageConstant::TRANSFORM_AUTOROTATE:
							$baeImageTransform->setAutorotate();
							break;
						case BaeImageConstant::TRANSFORM_CLEAROPERATIONS:
							$baeImageTransform->clearOperations();
							break;
						default:
							throw new BaeException(sprintf('invalid key [%s]', $key),
							BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
					}
				}
			}
			if($baeImageTransform->errcode !== 0){
				$this->errcode = $baeImageTransform->errcode;
				$this->errmsg = $baeImageTransform->errmsg;
				return false;
			}
			if(empty($baeImageSource)){
				throw new BaeException('no bae image source', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			// handle the baeImageSource
			if($isURL === true){
				$this->_examURL($baeImageSource);
				$URL = $baeImageSource;
			}else{
				//restrict image into 2M
				if(!$this->_checkString($baeImageSource, 0, 2*1024*1024)){
					throw new BaeException('image must be less than 2M', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
				}
			}
			
			unset($params);
			$params = $baeImageTransform->getOperations();
			$params['src'] = $URL;
			$arrArgs = array(
			self::METHOD => 'process',);
			$arrArgs = array_merge($arrArgs, $params);
			return $this->_commonProcess($arrArgs,array('src',));

		} catch (Exception $ex) {
			$this->_exceptionHandler($ex);
			return false;
		}
	}
	
	
	/** 
	 * Apply an annotation into an image 
	 * @param baeImageSource string, <p>image source or image url</p>
	 * @param isURL bool, <p> true for $image field
	 *        is URL, false for image byte string</p>
	 * @param baeImageAnnotate BaeImageAnnotate, contains variety of 
	 * 		  operations about annotation.
	 * @return Image-relevant data,if success, otherwise return false
	 */
	public function applyAnnotateByObject($baeImageSource, $baeImageAnnotate)
	{
		$isURL=true;
		$URL = '';
		$this->_resetErrorStatus();
		try {
			if(empty($baeImageAnnotate) || !($baeImageAnnotate instanceof BaeImageAnnotate)){
				throw new BaeException('input source is not an instance of BaeImageAnnotate', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			if($baeImageAnnotate->errcode !== 0){
				$this->errcode = $baeImageAnnotate->errcode;
				$this->errmsg = $baeImageAnnotate->errmsg;
				return false;
			}
			if(empty($baeImageSource)){
				throw new BaeException('no bae image source', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			/********************examming url pattern*******************************/
			if($isURL === true){
				$this->_examURL($baeImageSource);
				$URL = $baeImageSource;
			}else{
				if(!$this->_checkString($baeImageSource, 0, 2*1024*1024)){
					throw new BaeException('image must be less than 2M', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
				}
			}

			$operations = $baeImageAnnotate->getOperations();

			// $operations must contain text
			if (empty($operations['text'])) {
				throw new BaeException('no text script', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			$jsonParams = $this->_formJsonParams($URL, $operations, $this->ProcType_Annotate, true);
			$paramArr = array('strudata'=>$jsonParams);
			$arrArgs = array(
			self::METHOD => 'processExt',/*'appid'=>"appid7avderfd05",'dev_uid'=>'12345','secret_key'=>'pNceSAlS4HB8fToDilmXQvwSc6nHInHW',*/
			);
			$arrArgs = array_merge($arrArgs, $paramArr);
			return $this->_commonProcess($arrArgs);
				
		}catch (Exception $ex) {
				//echo "exception:". $ex->getMessage();
				$this->_exceptionHandler($ex);
				return false;
			}
	}
	
	 /** 
	 * Apply an annotation into an image 
	 * @param image string, <p>image source or image url</p>
	 * @param isURL bool, <p> true for $image field
	 *        is URL, false for image data source</p>
	 * @param params array, <p>contains variety of 
	 * 		  operations about annotation</p>
	 * @return Image-relevant data,if success, otherwise return false
	 */
	public function applyAnnotate($baeImageSource, $text, $params=array())
	{
		$isURL=true;
		$URL = '';
		$baeImageAnnotate = new BaeImageAnnotate();
		$this->_resetErrorStatus();
		try{
			if(!is_array($params)){
				throw new BaeException(sprintf('invalid parameters, [%s] should be array', $params),
				BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}	
			if (!empty($params)) {
				foreach ($params as $key => $value){
					if(!is_string($key)){
						throw new BaeException(sprintf('invalid key [%s]', $key),
						BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
					}
					switch($key){
						case BaeImageConstant::ANNOTATE_OPACITY:
							$baeImageAnnotate->setOpacity($value);
							break;
						case BaeImageConstant::ANNOTATE_OUTPUTCODE:
							$baeImageAnnotate->setOutputCode($value);
							break;
						case BaeImageConstant::ANNOTATE_QUALITY:
							$baeImageAnnotate->setQuality($value);
							break;
						case BaeImageConstant::ANNOTATE_FONT:
							if(!is_array($value) || count($value) != 3){
								throw new BaeException('invalid font param', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
							}
							$baeImageAnnotate->setFont($value[0],$value[1],$value[2]);
							break;
						case BaeImageConstant::ANNOTATE_POS:
							if(!is_array($value) || count($value) != 2){
								throw new BaeException('invalid pos param', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
							}
							$baeImageAnnotate->setPos($value[0],$value[1]);
							break;
						case BaeImageConstant::ANNOTATE_CLEAROPERATIONS:
							$baeImageAnnotate->clearOperations();
							break;
						default:
							throw new BaeException(sprintf('invalid key [%s]', $key),
							BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
					}
				}
			}
			if(empty($baeImageSource)){
				throw new BaeException('no bae image source', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			if(empty($text)){
				throw new BaeException('no text script', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			$baeImageAnnotate->setText($text); // check $text
			if($baeImageAnnotate->errcode !== 0){
				$this->errcode = $baeImageAnnotate->errcode;
				$this->errmsg = $baeImageAnnotate->errmsg;
				return false;
			}
			/********************examming url pattern*******************************/
			if($isURL == true){
				$this->_examURL($baeImageSource);
				$URL = $baeImageSource;
			}else{
				//restrict image into 2M
				if(!$this->_checkString($baeImageSource, 0, 2*1024*1024)){
					throw new BaeException('image must be less than 2M', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
				}
			}
			unset($params);

			$operations = $baeImageAnnotate->getOperations();
			$jsonParams = $this->_formJsonParams($URL, $operations, $this->ProcType_Annotate, true);
			$paramArr = array('strudata'=>$jsonParams);
			$arrArgs = array(
			self::METHOD => 'processExt',);
			$arrArgs = array_merge($arrArgs, $paramArr);
			return $this->_commonProcess($arrArgs);

		} catch (Exception $ex) {
			//echo "exception:". $ex->getMessage();
			$this->_exceptionHandler($ex);
			return false;
		}
	}
	
	/**
	 * Generate a QR Code image about a text script. 
	 * @param baeImageQRCode BaeImageQRCode,<p>contains variety of 
	 * 		  operations about QR Code<p>
	 * @return Image-relevant data,if success, otherwise return false
	 */
	public function applyQRCodeByObject($baeImageQRCode)
	{
		$this->_resetErrorStatus();
		try {
			if(empty($baeImageQRCode) || !($baeImageQRCode instanceof BaeImageQRCode)){
				throw new BaeException('input source is not an instance of BaeImageQRCode', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			if($baeImageQRCode->errcode !== 0){
				$this->errcode = $baeImageQRCode->errcode;
				$this->errmsg = $baeImageQRCode->errmsg;
				return false;
			}
			$operations = $baeImageQRCode->getOperations();
			// $operations must contain text
			if (empty($operations['text'])) {
				throw new BaeException('no text script', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			$jsonParams = $this->_formJsonParams(null, $operations, $this->ProcType_QRCode, false);
			$paramArr = array('strudata'=>$jsonParams);
			$arrArgs = array(
			self::METHOD => 'processExt',
			);
			$arrArgs = array_merge($arrArgs, $paramArr);
			return $this->_commonProcess($arrArgs);

		}catch (Exception $ex) {
				$this->_exceptionHandler($ex);
				return false;
			}
	}
	
	 /**
	 * Generate a QR Code image about a text script. 
	 * @param text string, <p>text script for processing</p>
	 * @param params array, <p>contains variety of 
	 * 		  operations about QR Code</p>
	 * @return Image-relevant data,if success, otherwise return false
	 */
	public function applyQRCode($text, $params=array())
	{
		$baeImageQRCode = new BaeImageQRCode();
		$this->_resetErrorStatus();
		try{
			if(empty($text)){
				throw new BaeException('no text script', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			if(!is_array($params)){
				throw new BaeException(sprintf('invalid parameters, [%s] should be array', $params),
				BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			if (!empty($params)){
				foreach ($params as $key => $value){
					if(!is_string($key)){
						throw new BaeException(sprintf('invalid key [%s]', $key),
						BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
					}
					switch($key){
						case BaeImageConstant::QRCODE_SIZE:
							$baeImageQRCode->setSize($value);
							break;
						case BaeImageConstant::QRCODE_LEVEL:
							$baeImageQRCode->setLevel($value);
							break;
						case BaeImageConstant::QRCODE_VERSION:
							$baeImageQRCode->setVersion($value);;
							break;
						case BaeImageConstant::QRCODE_MARGIN:
							$baeImageQRCode->setMargin($value);
							break;
						case BaeImageConstant::QRCODE_FOREGROUND:
							$baeImageQRCode->setForeground($value);
							break;
						case BaeImageConstant::QRCODE_BACKGROUND:
							$baeImageQRCode->setBackground($value);
							break;
						case BaeImageConstant::QRCODE_CLEAROPERATIONS:
							$baeImageQRCode->clearOperations();
							break;
						default:
							throw new BaeException(sprintf('invalid key [%s]', $key),
							BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
					}
				}
			}
			unset($params);
			if($baeImageQRCode->errcode !== 0){
				$this->errcode = $baeImageQRCode->errcode;
				$this->errmsg = $baeImageQRCode->errmsg;
				return false;
			}
			$operations = $baeImageQRCode->getOperations();
			// GBK ENCODE
			$this->_checkString2($text,'text', 1,500);
			$text = mb_convert_encoding($text, 'GBK', 'UTF-8');
			$operations['text'] = $text;
			$jsonParams = $this->_formJsonParams(null, $operations, $this->ProcType_QRCode, false);
			$paramArr = array('strudata'=>$jsonParams);
			$arrArgs = array(
			self::METHOD => 'processExt',);
			$arrArgs = array_merge($arrArgs, $paramArr);
			return $this->_commonProcess($arrArgs);

		} catch (Exception $ex) {
			$this->_exceptionHandler($ex);
			return false;
		}
	}
	
	/**
	 *  Apply composition 
	 * @param composites array, <p>a set of BaeImageComposite object</p>
	 * @param canvas_width[optional] int,<p>canvas width</p>
	 * @param canvas_height[optional] int,<p>canvas height</p>
	 * @param outputcode[optional] BaeImageConstant, <p>output image type</p>
	 * @param quality[optional] int,<p>output image quality</p>
	 * @return Image-relevant data,if success, otherwise return false
	 */
	public function applyCompositeByObject($composites, $canvas_width = 1000, $canvas_height = 1000,
											$outputcode = BaeImageConstant::JPG, $quality = 80)
	{
					
		$this->_resetErrorStatus();
		try {
			if(empty($composites) || ! is_array($composites)){
				throw new BaeException('input source is not a BaeImageComposite array', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			//common params
			$this->_checkInt($canvas_width, 'canvas width', 0, 10000);
			$this->_checkInt($canvas_height, 'canvas height', 0, 10000);
			$this->_checkInt($outputcode, 'outputcode', 0, 3);
			$this->_checkInt($quality, 'quality', 0, 100);
			$commParams = array('desttype'=> $outputcode, 'canvas_width'=> $canvas_width,
								'canvas_height'=>$canvas_height, 'quality'=>$quality);
			$len = count($composites);
			if($len < 2){
				throw new BaeException('short of images, at least two elements', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			if($len > 2){
				throw new BaeException('too many images, at most two elements', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			/*construct parameter array of _formJsonParmas()*/
			$operations = array();
			$isURL = array();
			$imageSource = array();
			if(!($composites[0] instanceof BaeImageComposite)){
				throw new BaeException('input source is not an instance of BaeImageComposite', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			if($composites[0]->errcode !== 0){
				$this->errcode = $composites[0]->errcode;
				$this->errmsg = $composites[0]->errmsg;
				return false;
			}
			$operations[0] = $composites[0]->getOperations();
			// merge common params
			$operations = array_merge($operations, $commParams);
			$baeImageSource = $composites[0]->getBaeImageSource();
			if (empty($baeImageSource['data'])) {
				throw new BaeException('no commosite image parameters', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			$imageSource[0] = $baeImageSource['data'];
			$isURL[0] = $baeImageSource['isURL'];

			for($i=1;$i<$len;$i++){
				if(!($composites[$i] instanceof BaeImageComposite)){
					throw new BaeException('input source is not an instance of BaeImageComposite', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
				}
				if($composites[$i]->errcode !== 0){
					$this->errcode = $composites[$i]->errcode;
					$this->errmsg = $composites[$i]->errmsg;
					return false;
				}
				$operations[1] = $composites[$i]->getOperations();
				$baeImageSource = $composites[$i]->getBaeImageSource();
				if (empty($baeImageSource['data'])) {
					throw new BaeException('no commosite image parameters', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
				}
				$imageSource[1] = $baeImageSource['data'];
				$isURL[1] = $baeImageSource['isURL'];
				$jsonParams = $this->_formJsonParams($imageSource, $operations,$this->ProcType_Composite, $isURL);
				$paramArr = array('strudata'=>$jsonParams);
				$arrArgs = array(
				self::METHOD => 'processExt',);
				$arrArgs = array_merge($arrArgs, $paramArr);
				$retImage = $this->_commonProcess($arrArgs);
			}
			return $retImage;

		}catch (Exception $ex) {
			$this->_exceptionHandler($ex);
			return false;
		}
	}
	
	 /**
	 *  Apply composition 
	 * @param params array, <p>contains variety of operaitons about composite</p>
	 * @param canvas_width[optional] int,<p>canvas width</p>
	 * @param canvas_height[optional] int,<p>canvas height</p>
	 * @param outputcode[optional] BaeImageConstant, <p>output image type</p>
	 * @param quality[optional] int,<p>output image quality</p>
	 * @return Image-relevant data,if success, otherwise return false
	 */
	public function applyComposite($params,$canvas_width = 1000, $canvas_height = 1000,
									$outputcode = BaeImageConstant::JPG, $quality = 80)
	{
		$baeImageComposite = new BaeImageComposite();
		$this->_resetErrorStatus();
		try{/*check common paramters*/
			$this->_checkInt($canvas_width, 'canvas width', 0, 10000);
			$this->_checkInt($canvas_height, 'canvas height', 0, 10000);
			$this->_checkInt($outputcode, 'outputcode', 0, 3);
			$this->_checkInt($quality, 'quality', 0, 100);
			$commParams = array('desttype'=> $outputcode, 'canvas_width'=> $canvas_width,
								'canvas_height'=>$canvas_height, 'quality'=>$quality);

			if (empty($params) || !is_array($params)) {////
				throw new BaeException(sprintf('invalid parameters, [%s] should be array', $params),
				BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			if(count($params) < 2){
				throw new BaeException('short of images, at least two elements', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			if(count($params) > 2){
				throw new BaeException('too many images, at most two elements', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}

			$operations = array();
			$isURL = array();
			$imageSource = array();
			$paramElement = $params[0];
			foreach ($paramElement as $key => $value){
				if(!is_string($key)){
					throw new BaeException(sprintf('invalid key [%s]', $key),
					BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
				}
				switch($key){
					case BaeImageConstant::COMPOSITE_BAEIMAGESOURCE:
						//if(!is_array($value) || count($value) != 2){
						if(!is_array($value)){
							throw new BaeException('invalid bae image source parameter', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
						}
						if (empty($value[0])) {
							throw new BaeException('no image source parameters', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
						}
						if(count($value) === 2){
							$baeImageComposite->setBaeImageSource($value[0], $value[1]);
						}else{
							$baeImageComposite->setBaeImageSource($value[0]);
						}
						break;
					case BaeImageConstant::COMPOSITE_OPACITY:
						$baeImageComposite->setOpacity($value);
						break;
					case BaeImageConstant::COMPOSITE_ANCHOR:
						$baeImageComposite->setAnchor($value);
						break;
					case BaeImageConstant::COMPOSITE_POS:
						if(!is_array($value) || count($value) != 2){
							throw new BaeException('invalid position parameter', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
						}
						$baeImageComposite->setPos($value[0],$value[1]);
						break;
					case BaeImageConstant::COMPOSITE_CLEAROPERATIONS:
						$baeImageComposite->clearOperations();
						break;
					default:
						throw new BaeException(sprintf('invalid key [%s]', $key),
						BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
				}
			}
			if($baeImageComposite->errcode !== 0){
				$this->errcode = $baeImageComposite->errcode;
				$this->errmsg = $baeImageComposite->errmsg;
				return false;
			}
			$operations[0] = $baeImageComposite->getOperations();
			$operations = array_merge($operations, $commParams);
			$baeImageSource = $baeImageComposite->getBaeImageSource();
			$imageSource[0] = $baeImageSource['data'];
			$isURL[0] = $baeImageSource['isURL'];

			for($i=1,$len=count($params);$i<$len;$i++){
				$baeImageComposite->clearOperations();
				$paramElement = $params[$i];
				foreach ($paramElement as $key => $value){
					if(!is_string($key)){
						throw new BaeException(sprintf('invalid key [%s]', $key),
						BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
					}
					switch($key){
						case BaeImageConstant::COMPOSITE_BAEIMAGESOURCE:
							if(!is_array($value)){
								throw new BaeException('invalid bae image source parameter', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
							}
							if (empty($value[0])) {
								throw new BaeException('no image source parameters', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
							}
							if(count($value) === 2){
								$baeImageComposite->setBaeImageSource($value[0], $value[1]);
							}else{
								$baeImageComposite->setBaeImageSource($value[0]);
							}
							break;
						case BaeImageConstant::COMPOSITE_OPACITY:
							$baeImageComposite->setOpacity($value);
							break;
						case BaeImageConstant::COMPOSITE_ANCHOR:
							$baeImageComposite->setAnchor($value);
							break;
						case BaeImageConstant::COMPOSITE_POS:
							if(!is_array($value) || count($value) != 2){
								throw new BaeException('invalid position parameter', BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
							}
							$baeImageComposite->setPos($value[0],$value[1]);
							break;
						case BaeImageConstant::COMPOSITE_CLEAROPERATIONS:
							$baeImageComposite->clearOperations();
							break;
						default:
							throw new BaeException(sprintf('invalid key [%s]', $key),
							BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
					}
				}
				if($baeImageComposite->errcode !== 0){
					$this->errcode = $baeImageComposite->errcode;
					$this->errmsg = $baeImageComposite->errmsg;
					return false;
						
				}
				$operations[1] = $baeImageComposite->getOperations();
				$baeImageSource = $baeImageComposite->getBaeImageSource();
				$imageSource[1] = $baeImageSource['data'];
				$isURL[1] = $baeImageSource['isURL'];

				$jsonParams = $this->_formJsonParams($imageSource, $operations,
				$this->ProcType_Composite, $isURL);
				$paramArr = array('strudata'=>$jsonParams);
				$arrArgs = array(
				self::METHOD => 'processExt',);
				$arrArgs = array_merge($arrArgs, $paramArr);
				$retImage = $this->_commonProcess($arrArgs);
			}
			return $retImage;
		} catch (Exception $ex) {
			//echo "exception:" . $ex->getMessage();
			$this->_exceptionHandler($ex);
			return false;
		}
	}
	
	/**
	 *  Generate verificaion code 
	 * @param baeImageVCode BaeImageVCode, <p>contains variety of setting about vcdoe</p> 
	 * @return image url of vcode,if success, otherwise return false
	 */
	public function generateVCode($params=array())
	{
		$this->_resetErrorStatus();
		$baeImageVCode = new BaeImageVCode();
		try {
			if(!is_array($params)){
				throw new BaeException(sprintf('invalid parameters, [%s] should be array', $params),
				BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			
			if (!empty($params)) {
				foreach ($params as $key => $value){
					if(!is_string($key)){
						throw new BaeException(sprintf('invalid key [%s]', $key),
						BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
					}
					switch ($key){
						case BaeImageConstant::VCODE_LEN: 
							$baeImageVCode->setLen($value);
							break;
						case BaeImageConstant::VCODE_PATTERN:
							$baeImageVCode->setPattern($value);
							break;
						case BaeImageConstant::VCODE_CLEAROPERATIONS:
							$baeImageVCode->clearOperations();
							break;
						default:
							throw new BaeException(sprintf('invalid key [%s]', $key),
                        		BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
					}
				}
			}
			unset($params);
			if($baeImageVCode->errcode !== 0){
				$this->errcode = $baeImageVCode->errcode;
				$this->errmsg = $baeImageVCode->errmsg;
				return false;
			}

			$paramArr = $baeImageVCode->getOperations();// obtain the operations setting on the vcode
			$paramArr['vcservice'] = 0;//generate vcode
			$arrArgs = array(
			self::METHOD => 'process',
			);
			$arrArgs = array_merge($arrArgs, $paramArr);
			$retArr = $this->_commonProcess($arrArgs);
			$retStatus = $retArr['response_params']['status'];
			if($retStatus !== 0){
				throw new BaeException('failed to get verification code', BaeImageConstant::BAE_IMAGEUI_SDK_HTTP_STATUS_OK_BUT_RESULT_ERROR);
			}
			return $retArr;
		} catch (Exception $ex) {
				$this->_exceptionHandler($ex);
				return false;
        		}
		
	}
	
	/**
	 *  Verify  vcode 
	 * @param baeImageVCode BaeImageVCode, <p>contains variety of setting about vcdoe</p> 
	 * @return result of vcode,if success, otherwise return false
	 */
	public function verifyVCode($params)
	{
		$this->_resetErrorStatus();
		$baeImageVCode = new BaeImageVCode();
		try {
			if(empty($params)){
				throw new BaeException(sprintf('invalid parameters, [%s] is empty', $params),
				BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			if(!is_array($params)){
				throw new BaeException(sprintf('invalid parameters, [%s] should be array', $params),
				BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			if(empty($params['input'])){
				throw new BaeException(sprintf('invalid parameters, [%s] input field is empty ', $params),
				BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			if(empty($params['secret'])){
				throw new BaeException(sprintf('invalid parameters, [%s] secret field is empty ', $params),
				BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
			}
			foreach ($params as $key => $value){
				if(!is_string($key)){
					throw new BaeException(sprintf('invalid key [%s]', $key),
					BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
				}
				switch ($key){
					case BaeImageConstant::VCODE_INPUT:
						$baeImageVCode->setInput($value);
						break;
					case BaeImageConstant::VCODE_SECRET:
						$baeImageVCode->setSecret($value);
						break;
					case BaeImageConstant::VCODE_CLEAROPERATIONS:
						$baeImageVCode->clearOperations();
						break;
					default:
						throw new BaeException(sprintf('invalid key [%s]', $key),
						BaeImageConstant::BAE_IMAGEUI_SDK_PARAM);
				}
			}

			unset($params);
			if($baeImageVCode->errcode !== 0){
				$this->errcode = $baeImageVCode->errcode;
				$this->errmsg = $baeImageVCode->errmsg;
				return false;
			}
			$paramArr = $baeImageVCode->getOperations();// obtain the operations setting on the vcode
			$paramArr['vcservice'] = 1;//verify vcode
			$arrArgs = array(
			self::METHOD => 'process',
			);
			$arrArgs = array_merge($arrArgs, $paramArr);
			return $this->_commonProcess($arrArgs);

		} catch (Exception $ex) {
			$this->_exceptionHandler($ex);
			return false;
		}
	}
	
	/*
	 * 
	 * formed json type params using the given input
	 * @param type mixed $imageSource, image source data or url
	 * @param type array $operations
	 * @param int $procType
	 * @param bool $isURL
	 * 
	 * @return the json-formed params 
	 */
	private function _formJsonParams($imageSource, $operations, $procType, $isURL)
	{
		$jsonParamArr = array();
		switch ($procType) {
			/****************************QR Code*******************************/
			case 0:
				$jsonParamArr['process_type'] = $procType . '';// value = '0'
				$jsonParamArr['req_data_num'] = '1'; // fixed value;
				$jsonParamArr['req_data_source'] = array();
				$jsonParamArr['source_data'] = array();
				$text = $this->_getOperation($operations, 'text');  //get text in qr code
				$jsonParamArr['source_data']['data1'] = base64_encode($text);
				$jsonParamArr['req_data_source'][0] = array('sourcemethod' => 'BODY');
				$jsonParamArr['req_data_source'][0]['source_data_type'] = 0; //0-text script, 1-image
				$jsonParamArr['req_data_source'][0]['operations'] = array();
				$jsonParamArr['req_data_source'][0]['http_reqpack'] = array(); //reserve

				if(!is_null($this->_getOperation($operations, 'size'))){
					$jsonParamArr['req_data_source'][0]['operations']['size'] = $this->_getOperation($operations, 'size');
				}else{
					$jsonParamArr['req_data_source'][0]['operations']['size'] = 3;
				}
					
				if(!is_null($this->_getOperation($operations, 'version'))){
					$jsonParamArr['req_data_source'][0]['operations']['version'] = $this->_getOperation($operations, 'version');
				}else{
					$jsonParamArr['req_data_source'][0]['operations']['version'] = 0;
				}

				if(!is_null($this->_getOperation($operations,'margin'))){
					$jsonParamArr['req_data_source'][0]['operations']['margin'] = $this->_getOperation($operations,'margin');
				}else{
					$jsonParamArr['req_data_source'][0]['operations']['margin'] = 4;
				}
				if(!is_null($this->_getOperation($operations,'level'))){
					$jsonParamArr['req_data_source'][0]['operations']['level'] = $this->_getOperation($operations,'level');
				}else{
					$jsonParamArr['req_data_source'][0]['operations']['level'] = 2;
				}
				
				if(!is_null($this->_getOperation($operations,'foreground'))){
					$jsonParamArr['req_data_source'][0]['operations']['foreground'] = $this->_getOperation($operations,'foreground');
				}else{
					$jsonParamArr['req_data_source'][0]['operations']['foreground'] = '000000';
				}
				if(!is_null($this->_getOperation($operations,'background'))){
					$jsonParamArr['req_data_source'][0]['operations']['background'] = $this->_getOperation($operations,'background');
				}else{
					$jsonParamArr['req_data_source'][0]['operations']['background'] = 'FFFFFF';
				}
			break;
			/****************Annotate*********************/
			case 1:
				$jsonParamArr['process_type'] = $procType . '';// value = '1'
				$jsonParamArr['req_data_num'] = '2'; // fixed value;
				$jsonParamArr['req_data_source'] = array();
				$jsonParamArr['source_data'] = array();
				$text = $this->_getOperation($operations, 'text');  //get text in annotate
				if($isURL === true){
					$jsonParamArr['req_data_source'][0] = array('sourcemethod' => 'GET'); 
					$jsonParamArr['req_data_source'][0]['source_url'] = $imageSource;
					$jsonParamArr['source_data']['data1'] = base64_encode($text);
				}else{
					$jsonParamArr['req_data_source'][0] = array('sourcemethod' => 'BODY');
					// image source must be filled in 'data1',if exist. followed by text in data2
					$jsonParamArr['source_data']['data1'] = base64_encode($imageSource);
					$jsonParamArr['source_data']['data2'] = base64_encode($text);
					
				}
				$jsonParamArr['req_data_source'][0]['source_data_type'] = 1;
				$jsonParamArr['req_data_source'][0]['operations'] = array();
				$jsonParamArr['req_data_source'][0]['http_reqpack'] = array();
				
				$jsonParamArr['req_data_source'][1] = array('sourcemethod' => 'BODY');
				$jsonParamArr['req_data_source'][1]['source_data_type'] = 0;
				$jsonParamArr['req_data_source'][1]['operations'] = array();
				$jsonParamArr['req_data_source'][1]['http_reqpack'] = array();
				// operations cant be empty in req_data_source[0][operations]
				if(!is_null($this->_getOperation($operations, 'x_offset'))){
					$jsonParamArr['req_data_source'][0]['operations']['x_offset'] = $this->_getOperation($operations, 'x_offset');
				}else{
					$jsonParamArr['req_data_source'][0]['operations']['x_offset'] = 0;
				}
					
				if(!is_null($this->_getOperation($operations, 'y_offset'))){
					$jsonParamArr['req_data_source'][0]['operations']['y_offset'] = $this->_getOperation($operations, 'y_offset');
				}else{
					$jsonParamArr['req_data_source'][0]['operations']['y_offset'] = 0;
				}
				
				if(!is_null($this->_getOperation($operations, 'opacity'))){
					$opacity = $this->_getOperation($operations, 'opacity');
					$strOpacity = strtoupper(dechex(ceil(255-$opacity*255)). '');
					if(strlen($strOpacity) === 1){
						$strOpacity = '0'. $strOpacity;
					}
				}else{
					$strOpacity = "FF";
				}
				
				if(!is_null($this->_getOperation($operations,'font_name'))){
					$jsonParamArr['req_data_source'][0]['operations']['font_name'] = $this->_getOperation($operations,'font_name');
				}else{
					$jsonParamArr['req_data_source'][0]['operations']['font_name'] = BaeImageConstant::SUN;
				}
				if(!is_null($this->_getOperation($operations,'font_color'))){
					$jsonParamArr['req_data_source'][0]['operations']['font_color'] = '#'. $this->_getOperation($operations,'font_color'). $strOpacity;
					//echo "color" . $jsonParamArr['req_data_source'][0]['operations']['font_color'];
				}else{
					$jsonParamArr['req_data_source'][0]['operations']['font_color'] = '#000000'. $strOpacity;
				}
				
				if(!is_null($this->_getOperation($operations,'font_size'))){
					$jsonParamArr['req_data_source'][0]['operations']['font_size'] = $this->_getOperation($operations,'font_size');
				}else{
					$jsonParamArr['req_data_source'][0]['operations']['font_size'] = 25;
				}
				if(!is_null($this->_getOperation($operations,'desttype'))){
					$jsonParamArr['req_data_source'][0]['operations']['desttype'] = $this->_getOperation($operations,'desttype');
				}else{
					$jsonParamArr['req_data_source'][0]['operations']['desttype'] = 0;
				}
				if(!is_null($this->_getOperation($operations,'quality'))){
					$jsonParamArr['req_data_source'][0]['operations']['quality'] = $this->_getOperation($operations,'quality');
				}else{
					$jsonParamArr['req_data_source'][0]['operations']['quality'] = 80;
				}

			break;
			/********************Composite**********************/
			case 2:
				$jsonParamArr['process_type'] = $procType . '';// value = '2'
				$jsonParamArr['req_data_source'] = array();
				$jsonParamArr['source_data'] = array();
				
				$num = count($imageSource);
				$jsonParamArr['req_data_num'] = 2 .''; // fixed value;
				$count = 0;
				$isEmpty = true;
				while($count < $num){
					if($isURL[$count] === true){
						$jsonParamArr['req_data_source'][$count] = array('sourcemethod' => 'GET');
						$jsonParamArr['req_data_source'][$count]['source_url'] = $imageSource[$count];
					}else{
						$jsonParamArr['req_data_source'][$count] = array('sourcemethod' => 'BODY');
						if($isEmpty === true){
							$jsonParamArr['source_data']['data1'] = base64_encode($imageSource[$count]);
							$isEmpty = false;
						}else{
							$jsonParamArr['source_data']['data2'] = base64_encode($imageSource[$count]);
						}
						
					}
					$jsonParamArr['req_data_source'][$count]['source_data_type'] = 1;
					$jsonParamArr['req_data_source'][$count]['operations'] = array();
					$jsonParamArr['req_data_source'][$count]['http_reqpack'] = array();
					if(!is_null($this->_getOperation($operations[$count], 'x_offset'))){
						$jsonParamArr['req_data_source'][$count]['operations']['x_offset'] = $this->_getOperation($operations[$count], 'x_offset');
					}else{
						$jsonParamArr['req_data_source'][$count]['operations']['x_offset'] = 0;
					}
						
					if(!is_null($this->_getOperation($operations[$count], 'y_offset'))){
						$jsonParamArr['req_data_source'][$count]['operations']['y_offset'] = $this->_getOperation($operations[$count], 'y_offset');
					}else{
						$jsonParamArr['req_data_source'][$count]['operations']['y_offset'] = 0;
					}

					if(!is_null($this->_getOperation($operations[$count], 'opacity'))){
						$jsonParamArr['req_data_source'][$count]['operations']['opacity'] = $this->_getOperation($operations[$count], 'opacity');
					}else{
						$jsonParamArr['req_data_source'][$count]['operations']['opacity'] = 0.0;
					}

					if(!is_null($this->_getOperation($operations[$count],'anchor_point'))){
						$jsonParamArr['req_data_source'][$count]['operations']['anchor_point'] = $this->_getOperation($operations[$count],'anchor_point');
					}else{
						$jsonParamArr['req_data_source'][$count]['operations']['anchor_point'] = BaeImageConstant::TOP_LEFT;
					}
					/*********************Common params********************************/
					if(!is_null($this->_getOperation($operations,'canvas_width'))){
						$jsonParamArr['req_data_source'][$count]['operations']['canvas_width'] = $this->_getOperation($operations,'canvas_width');
					}else{
						$jsonParamArr['req_data_source'][$count]['operations']['canvas_width'] = 0;
					}
					if(!is_null($this->_getOperation($operations,'canvas_height'))){
						$jsonParamArr['req_data_source'][$count]['operations']['canvas_height'] = $this->_getOperation($operations,'canvas_height');
					}else{
						$jsonParamArr['req_data_source'][$count]['operations']['canvas_height'] = 0;
					}
					if(!is_null($this->_getOperation($operations,'desttype'))){
						$jsonParamArr['req_data_source'][$count]['operations']['desttype'] = $this->_getOperation($operations,'desttype');
					}else{
						$jsonParamArr['req_data_source'][$count]['operations']['desttype'] = 0;
					}
					if(!is_null($this->_getOperation($operations,'quality'))){
						$jsonParamArr['req_data_source'][$count]['operations']['quality'] = $this->_getOperation($operations,'quality');
					}else{
						$jsonParamArr['req_data_source'][$count]['operations']['quality'] = 80;
					}
					$count++;
					
				}
			break;

		}
		return json_encode($jsonParamArr);
	}
	
    private function _getOperation($arrParams, $strOperation){
    	if(!empty($arrParams[$strOperation])){
    		return $arrParams[$strOperation];
    	}
    	return null;
    	
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
}
