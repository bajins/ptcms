<?php

class BaeImageConstant{
	/*output encoding*/
	const JPG = 0;
	const BMP = 1;
	const PNG = 2;
	const GIF = 3;
	const WEBP = 4;
	
	/*Font constant*/
	const SUN = 0;
	const KAI = 1;
	const HEI = 2;
	const MICROHEI = 3;
	const ARIAL = 4;
	
	/*Positon constant*/
	const TOP_LEFT = 0;
	const TOP_CENTER = 1;
	const TOP_RIGHT = 2;
	const CENTER_LEFT = 3;
	const CENTER_CENTER = 4;
	const CENTER_RIGHT = 5;
	const BOTTOM_LEFT = 6;
	const BOTTOM_CENTER = 7;
	const BOTTOM_RIGHT = 8;
	
	/*SDK error constant*/
	const BAE_IMAGEUI_SDK_SYS = 1;
    const BAE_IMAGEUI_SDK_INIT_FAIL = 2;
    const BAE_IMAGEUI_SDK_PARAM = 3;
    const BAE_IMAGEUI_SDK_HTTP_STATUS_ERROR_AND_RESULT_ERROR = 4;
    const BAE_IMAGEUI_SDK_HTTP_STATUS_OK_BUT_RESULT_ERROR = 5;
    
    /*Transform params key name*/
    const TRANSFORM_ZOOMING = 'size';
    const TRANSFORM_CROPPING = 'crop';
    const TRANSFORM_ROTATE = 'rotate';
    const TRANSFORM_HUE = 'hue';
    const TRANSFORM_LIGHTNESS = 'lightness';
    const TRANSFORM_CONTRAST = 'contrast';
    const TRANSFORM_SHARPNESS = 'sharpness';
    const TRANSFORM_SATURATION = 'saturation';
    const TRANSFORM_TRANSCODE = 'transcode';
    const TRANSFORM_QUALITY = 'quality';
    const TRANSFORM_GETGIFFIRSTFRAME = 'getgiffirstframe';
    const TRANSFORM_AUTOROTATE = 'autorotate';
    const TRANSFORM_HORIZONTALFLIP = 'horizontalflip';
    const TRANSFORM_VERTICALFLIP = 'verticalflip';
    const TRANSFORM_CLEAROPERATIONS = 'clearoperations';
    const TRANSFORM_ZOOMING_TYPE_HEIGHT = 1; 
    const TRANSFORM_ZOOMING_TYPE_WIDTH = 2;
    const TRANSFORM_ZOOMING_TYPE_PIXELS = 3;
    const TRANSFORM_ZOOMING_TYPE_UNRATIO = 4;
    
    /*QRCODE params key name*/
    const QRCODE_TEXT = 'text';
    const QRCODE_VERSION = 'version';
    const QRCODE_SIZE = 'size';
    const QRCODE_LEVEL = 'level';
    const QRCODE_MARGIN = 'margin';
    const QRCODE_FOREGROUND = 'foreground';
    const QRCODE_BACKGROUND = 'background';
    const QRCODE_CLEAROPERATIONS = 'clearoperations';
    
    /*ANNOTATE params key name*/
    const ANNOTATE_OPACITY = 'opacity';
    const ANNOTATE_FONT = 'font';
    const ANNOTATE_POS = 'pos';
    const ANNOTATE_OUTPUTCODE = 'outputcode';
    const ANNOTATE_QUALITY = 'quality';
    const ANNOTATE_CLEAROPERATIONS = 'clearoperations';
    
    /*COMPOSITE params key name*/
    const COMPOSITE_BAEIMAGESOURCE = 'baeimagesource';
    const COMPOSITE_POS = 'pos';
    const COMPOSITE_OPACITY = 'opacity';
    const COMPOSITE_ANCHOR = 'anchor';
    const COMPOSITE_CLEAROPERATIONS = 'clearoperations';
    
    /*VCode params key name*/
    const VCODE_LEN = 'len';
    const VCODE_PATTERN = 'pattern';
    const VCODE_INPUT = 'input';
    const VCODE_SECRET = 'secret';
    const VCODE_CLEAROPERATIONS = 'clearoperations';
    

    
    
    
	
	
	
}


?>