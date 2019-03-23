
function showReadTool() {
document.writeln("<div id=\"tools\">");
document.writeln("    <ul>");
document.writeln("        <li>背景：</li>");
document.writeln("        <li>");
document.writeln("            <span class=\"setbglist\">");
document.writeln("                <input id=\"bg1\" onclick=\"setBG(\'#dcecf5\')\" type=\"button\" class=\"setBG\" />");
document.writeln("                <input id=\"bg2\" onclick=\"setBG(\'#e1ffe6\')\" type=\"button\" class=\"setBG\" />");
document.writeln("                <input id=\"bg3\" onclick=\"setBG(\'#edf6d0\')\" type=\"button\" class=\"setBG\" />");
document.writeln("                <input id=\"bg4\" onclick=\"setBG(\'#eae8f7\')\" type=\"button\" class=\"setBG\" />");
document.writeln("                <input id=\"bg5\" onclick=\"setBG(\'#f5f1e7\')\" type=\"button\" class=\"setBG\" />");
document.writeln("                <input id=\"bg6\" onclick=\"setBG(\'#ebf4ef\')\" type=\"button\" class=\"setBG\" />");
document.writeln("                <input id=\"bg7\" onclick=\"setBG(\'#FFFFFF\')\" type=\"button\" class=\"setBG\" />");
document.writeln("            </span>");
document.writeln("        </li>");
document.writeln("        <li>");
document.writeln("        <span class=\"l\">字体：</span>");
document.writeln("            <select onchange=\"setFontType(this.value)\" id=\"txttype\" name=\"txttype\" >");
document.writeln("                <option value=\"Microsoft YaHei\">雅黑</option>");
document.writeln("                <option value=\"SimHei\">黑体</option>");
document.writeln("                <option value=\"SimSun\">宋体</option>");
document.writeln("                <option value=\"LiSu\">隶书</option>");
document.writeln("                <option value=\"YouYuan\">幼圆</option>");
document.writeln("                <option value=\"KaiTi_GB2312\">楷体</option>");
document.writeln("                <option value=\"FangSong_GB2312\">仿宋</option>");
document.writeln("            </select>");
document.writeln("        </li>");
document.writeln("        <li>");
document.writeln("        <span class=\"l\">字号：</span>");
document.writeln("            <select onchange=\"setFontSize(this.value)\" id=\"txtsize\" name=\"txtsize\">");
document.writeln("                <option value=\"16px\">小</option>");
document.writeln("                <option value=\"20px\">中</option>");
document.writeln("                <option value=\"24px\">大</option>");
document.writeln("                <option value=\"28px\">超大</option>");
document.writeln("            </select>");
document.writeln("        </li>");
document.writeln("        <li> 字色：");
document.writeln("            <select onchange=\"setFontColor(this.value)\" id=\"txtcolor\" name=\"txtcolor\">");
document.writeln("                <option value=\"#000000\">黑色</option>");
document.writeln("                <option value=\"#ff0000\">红色</option>");
document.writeln("                <option value=\"#006600\">绿色</option>");
document.writeln("                <option value=\"#0000ff\">蓝色</option>");
document.writeln("                <option value=\"#660000\">棕色</option>");
document.writeln("            </select>");
document.writeln("        </li>");
document.writeln("        <li id=\"sudu\">滚速：");
document.writeln("            <select onchange=\"setSpeed(this.value)\" id=\"speed\" name=\"speed\">");
document.writeln("                <option value=\"1\">超慢</option>");
document.writeln("                <option value=\"3\">慢</option>");
document.writeln("                <option value=\"5\">中速</option>");
document.writeln("                <option value=\"8\">快</option>");
document.writeln("                <option value=\"12\">超快</option>");
document.writeln("            </select>");
document.writeln("        </li>");
document.writeln("    </ul>");
document.writeln("</div>");
}


function setBG(o) {
$("#content").css('backgroundColor', o);
$.cookie("kyks_rtool_background", o,{expires: 7, path: '/'});
}

function setFontColor(o) {
$("#txt").css("color", o);
$.cookie("kyks_rtool_fontColor", o,{expires: 7, path: '/'});
}
function setFontSize(o) {
$("#txt").css("font-size", o);
$.cookie("kyks_rtool_fontSize", o,{expires: 7, path: '/'});
}
function setFontType(o) {
$("#txt").css("font-family", o);
$.cookie("kyks_rtool_fontType", o,{expires: 7, path: '/'});
}

function setSpeed(o) {
$.cookie("kyks_rtool_speed", o,{expires: 7, path: '/'});
}

$(function() {
if ($.cookie("kyks_rtool_background")) {
setBG($.cookie("kyks_rtool_background"));
}
if ($.cookie("kyks_rtool_fontColor")) {
setFontColor($.cookie("kyks_rtool_fontColor"));
$("#txtcolor").val($.cookie("kyks_rtool_fontColor"));
}
if ($.cookie("kyks_rtool_fontType")) {
setFontType($.cookie("kyks_rtool_fontType"));
$("#txttype").val($.cookie("kyks_rtool_fontType"));
}
if ($.cookie("kyks_rtool_fontSize")) {
setFontSize($.cookie("kyks_rtool_fontSize"));
$("#txtsize").val($.cookie("kyks_rtool_fontSize"));
}else{
$.cookie("kyks_rtool_fontSize", '20px',{expires: 7, path: '/'});
$("#txtsize").val($.cookie("kyks_rtool_fontSize"));
}
if ($.cookie("kyks_rtool_speed")) {
setSpeed($.cookie("kyks_rtool_speed"));
$("#speed").val($.cookie("kyks_rtool_speed"));
}else{
$.cookie("kyks_rtool_speed", '5',{expires: 7, path: '/'});
$("#speed").val($.cookie("kyks_rtool_speed"));
}
})

var currentpos,timer;
function initialize()
{
timer=setInterval("scrollwindow()",50/($.cookie("kyks_rtool_speed")));
}
function stop(){
clearInterval(timer);
}
function scrollwindow()
{
window.scrollBy(0,1);
}
document.onmousedown=stop
document.ondblclick=initialize