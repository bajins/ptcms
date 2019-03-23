<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
    <meta content = "text/html; charset=utf-8" http-equiv = "Content-Type">
    <title>系统发生错误 - Power By PTcms Framework</title>
    <style type = "text/css">
        *{padding:0;margin:0;}html{overflow-y:scroll;}body{background:#fff;font-family:'微软雅黑';color:#333;font-size:16px;}img{border:0;}.copyright{padding:12px 48px;color:#999;}.copyright a{color:#000;text-decoration:none;}html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,font,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,b,u,i,center,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,caption{border:0;outline:0;font-size:100%;vertical-align:baseline;background:transparent;margin:0;padding:0;}body{line-height:1;}ol,ul{list-style:none;}blockquote,q{quotes:none;}blockquote:before,blockquote:after,q:before,q:after{content:none;}:focus{outline:0;}ins{text-decoration:none;}del{text-decoration:line-through;}table{border-collapse:collapse;border-spacing:0;}body{font:normal 9pt "Verdana";color:#000;background:#fff;}h1{font:normal 18pt "Verdana";color:#f00;margin-bottom:.5em;}h2{font:normal 14pt "Verdana";color:#800000;margin-bottom:.5em;}h3{font:bold 11pt "Verdana";}pre{font:normal 11pt Menlo,Consolas,"Lucida Console",Monospace;}pre span.error{display:block;background:#fce3e3;}pre span.ln{color:#999;padding-right:0.5em;border-right:1px solid #ccc;}pre span.error-ln{font-weight:bold;}.container{margin:1em 4em;}.version{color:gray;font-size:8pt;border-top:1px solid #aaa;padding-top:1em;margin-bottom:1em;}.message{color:#000;padding:1em;font-size:11pt;background:#f3f3f3;-webkit-border-radius:10px;-moz-border-radius:10px;border-radius:10px;margin-bottom:1em;line-height:160%;}.source{margin-bottom:1em;}.code pre{background-color:#ffe;margin:0.5em 0;padding:0.5em;line-height:125%;border:1px solid #eee;}.source .file{margin-bottom:1em;font-weight:bold;}.traces{margin:2em 0;}.trace{margin:0.5em 0;padding:0.5em;}.trace.app{border:1px dashed #c00;}.trace .number{text-align:right;width:2em;padding:0.5em;}.trace .content{padding:0.5em;}.trace .plus,.trace .minus{display:inline;vertical-align:middle;text-align:center;border:1px solid #000;color:#000;font-size:10px;line-height:10px;margin:0;padding:0 1px;width:10px;height:10px;}.trace.collapsed .minus,.trace.expanded .plus,.trace.collapsed pre{display:none;}.trace-file{cursor:pointer;padding:0.2em;}.errorBox{padding:12px 48px;}.trace-file:hover{background:#f0ffff;}.big{font:50px/1.8 "microsoft yahei"}.info{margin-bottom:20px;}.title{font:bold 20px/1.8 "microsoft yahei"}.text{line-height:2}
     </style>
</head>
<body>
<?php
//echo $data['type']

function renderSourceCode($file,$errorLine,$maxLines)
{
	$errorLine--;	// adjust line number to 0-based from 1-based
	if($errorLine<0 || ($lines=@file($file))===false || ($lineCount=count($lines))<=$errorLine)
		return '';

	$halfLines=(int)($maxLines/2);
	$beginLine=$errorLine-$halfLines>0 ? $errorLine-$halfLines:0;
$endLine=$errorLine+$halfLines<$lineCount?$errorLine+$halfLines:$lineCount-1;
$lineNumberWidth=strlen($endLine+1);
$output='';
for($i=$beginLine;$i<=$endLine;++$i)
{
$isErrorLine = $i===$errorLine;
$code=sprintf("<span class = \"ln".($isErrorLine?' error-ln':'')."\">%0{$lineNumberWidth}d</span> %s",$i+1,htmlspecialchars(str_replace("\t",' ',$lines[$i])));
if(!$isErrorLine)
$output.=$code;
else
$output.='<span class = "error">'.$code.'</span>';
}
return '
<div class = "code">
    <pre>'.$output.'</pre>
</div>
';
}
?>
<div class = "errorBox">
    <b class = "big">Hi,出错了！</b>
    <h1><?php echo strip_tags($e['message']);?></h1>
    <div class = "content">
        <?php if(!empty($e['file'])) :?>
        <div class = "info">
            <div class = "title">
                错误位置
            </div>
            <div class = "text">
                <p><?php echo $e['file'] ;?> &#12288;LINE: <?php echo $e['line'];?></p>
            </div>
        </div>
        <?php
			$maxLines = 15;
			$file = $e["file"];
			$errorLine = $e["line"];
			echo renderSourceCode($file,$errorLine,$maxLines);
			endif;?>
        <div class = "info">
            <div class = "title">TRACE</div>
            <div class = "text">
                <pre><?php debug_print_backtrace();?></pre>
            </div>
        </div>
        <div class = "info">
            <div class = "title">文件加载</div>
            <div class = "text">
                <pre><?php echo implode("\n",get_included_files());?></pre>
            </div>
        </div>
    </div>
</div>
<div class = "copyright">
    <p><a title = "PTCMS" href = "http://www.ptcms.com">PTcms FrameWork</a><sup><?php echo PTCMS_VERSION?></sup> { Fast & Simple & Light MVC PHP Framework }</p>
</div>
</body>
</html>
