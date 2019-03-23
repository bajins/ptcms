$.admin = $.admin || {};
$.extend($.admin, {
'init': function () {
$.admin.iframe.init();
$.admin.sidebarmenu.init()
}, 'iframe': {
'init': function () {
$('#pt-mainframe').bind('load', $.admin.iframe.reheight());
$(window).bind('resize', $.admin.iframe.reheight())
},
'reheight': function () {
var height = $(window).height();
if (height > 94) {
setTimeout(function () {
$('#pt-mainframe').height(height - 110);
$('.pt-sidebar').height(height - 110);
$('.pt-main').height(height - 110)
}, 0)
}
}
}, 'sidebarmenu': {
'init': function () {
var n = $.admin.sidebarmenu.getposition();
$.admin.sidebarmenu.select(n[0], n[1]);
$.admin.sidebarmenu.titleclick();
$.admin.sidebarmenu.menuclick();
$.admin.sidebarmenu.linkclick()
}, 'getposition': function () {
var n = [], hash = window.location.hash;
if (hash.length > 1) {
n = hash.substr(1).split(',', 2);
return [parseInt(n[0]), parseInt(n[1])]
} else {
return [0, 0]
}
}, 'select': function (n, m) {
$('#pt-header .menu a').removeClass('current').eq(n).addClass('current');
$('.pt-sidebar li').removeClass('open');
$('.pt-sidebar li a').removeClass('current');
$('.pt-sidebar .pt-sidebar-menu-info').hide();
$('.pt-sidebar .pt-sidebar-menu').hide().eq(n).show().find('li a').eq(m).addClass('current').parents('li').addClass('open').find('.pt-sidebar-menu-info').show();
window.location.hash = '#' + n + ',' + m;
$('#pt-mainframe').attr('src', $('.pt-sidebar-menu').eq(n).find('li a').eq(m).attr('href'))
}, 'titleclick': function () {
$('#pt-header .menu a').bind('click', function () {
m = $('#pt-header .menu a').index(this);
$.admin.sidebarmenu.select(m, 0);
$(this).addClass('current').siblings().removeClass('current');
return false
})
}, 'menuclick': function () {
$('.pt-sidebar h3').bind('click', function () {
$(this).siblings('div').show(200).parent().addClass('open').siblings().removeClass('open').find('.pt-sidebar-menu-info').hide(200)
})
}, 'linkclick': function () {
$('.pt-sidebar a').bind('click', function () {
n = $.admin.sidebarmenu.getposition();
m = $(this).parents('.pt-sidebar-menu').find('a').index(this);
$.admin.sidebarmenu.select(n[0], m);
return false
})
}
}, 'tab': {
'init': function () {
$('.pt-tab').each(function () {
var tab = this, id = $(tab).find('.pt-tab-nav a').index($(tab).find('.pt-tab-nav a.current'));
$(tab).find('.pt-tab-item').eq(id).show().siblings('.pt-tab-item').hide();
$(tab).find('.pt-tab-nav a').bind('click', function () {
var aid = $(tab).find('.pt-tab-nav a').index($(this));
$(this).addClass('current').siblings().removeClass('current');
$(tab).find('.pt-tab-item').eq(aid).show().siblings('.pt-tab-item').hide();
return false
})
})
}
}, 'tool': {
'checkAll': function () {
$('.checkall').each(function () {
$(this).bind('click', function () {
var name = $(this).attr('data');
if ($(this).is(':checked')) {
$('input[name="' + name + '"]').attr('checked', true)
} else {
$('input[name="' + name + '"]').attr('checked', false)
}
event.preventDefault();
})
})
}, 'selectJump': function () {
$('select').each(function () {
var field = $(this).attr('data');
if (field != undefined) {
$(this).change(function () {
var arr = SELF.split('/'), param = [], url = '/' + arr[1] + '/' + arr[2] + '/' + arr[3];
for (i = 4; i < arr.length; i += 2) {
if (arr[i] != '') param[arr[i]] = arr[i + 1]
}
param[field] = $(this).find('option:selected').val();
for (var key in param) {
if (param[key] != '') {
url += '/' + key + '/' + param[key]
}
}
window.location.href = url
})
}
})
}, 'tooltips': function () {
}, 'successtip': function (html, obj, url, t) {
t = t || 1;
layer.tips(html, obj, {style: ['background-color:#3c763d;color:#fff;font-size:14px;line-height:30px;font-weight:bold', '#3c763d'], maxWidth: 185, guide: 1, time: t, closeBtn: false}, function () {
if (url != undefined) {
window.location.href = url
}
})
}, 'errortip': function (html, obj, t) {
t = t || 3;
layer.tips(html, obj, {style: ['background-color:darkred;color:#fff;font-size:14px;line-height:30px;font-weight:bold', 'darkred'], maxWidth: 185, guide: 1, time: t, closeBtn: false})
}, 'tipbox': function (id, boxtitle, callback) {
layer.open({
type: 1,
title: boxtitle,
closeBtn: [0, true],
border: [5, 0.3, '#666', true],
offset: ['50px', ''],
shadeClose: true,
area: ['500px', 'auto'],
content:$(id),
success: function () {
if ($.isFunction(callback)) callback();
}
})
}
}
});
$.fn.extend({
'alert': function (type, text, url) {
var h = '';
$('body,html').animate({scrollTop: 0}, 500);
$('.alert').remove();
h += '<div class="alert alert-' + type + ' alert-dismissable">';
h += ' <button type="button" class="close">&times;</button>';
h += ' ' + text;
h += '</div>';
$(this).before(h);
$('.alert').fadeIn(300).delay(2000).fadeOut(500, function () {
if (url != undefined) {
window.location.href = url
}
});
if (url != undefined) {
setTimeout(function () {
window.location.href = url
}, 5000)
}
}
});
$(function () {
if ($('.pt-tab').length > 0) {
$.admin.tab.init()
}
$.admin.tool.checkAll();
$.admin.tool.selectJump();
// 下拉
$('select').each(function () {
$(this).width($(this).width() + 8).removeClass('w450 w640');
});
//
$('.alert .close').on('click', function () {
$(this).parent().fadeOut(500)
});
// 时间选择插件
$('input.date').on('click', function () {
WdatePicker({skin: 'ext', dateFmt: 'yyyy-MM-dd', el: this})
});
$('input.datetime').on('click', function () {
WdatePicker({skin: 'ext', dateFmt: 'yyyy-MM-dd HH:mm:ss', el: this})
});
// 表单验证
if ($('.vform').length > 0) {
$('.vform').Validform({tiptype: 3});
}
});
function checkselt(name, form) {
si = 0;
var ss = $("input[name='" + name + "']");
for (var i = 0; i < ss.length; i++) {
if (ss[i].checked) {
si++
}
}
if (si == 0) {
layer.alert('请选择您要操作的记录!', 8);
return false;
}
if ($("select[name='dooperate']").val() == 0) {
layer.alert('请选择您要进行的操作！', 8);
return false;
}
layer.confirm('请注意提交后操作就不能再恢复,您确定提交吗？', function () {
$(form).submit();
});
}
/*before after的支持*/
var patterns = {text: /^['"]?(.+?)["']?$/, url: /^url\(["']?(.+?)['"]?\)$/};
function clean(content) {
if (content && content.length) {
var text = content.match(patterns.text)[1], url = text.match(patterns.url);
return url ? '<img src="' + url[1] + '" />' : text
}
}
function inject(prop, elem, content) {
if (prop != 'after') prop = 'before';
if (content = clean(elem.currentStyle[prop])) {
$(elem)[prop == 'before' ? 'prepend' : 'append']($(document.createElement('span')).addClass(prop).html(content))
}
}
$.pseudo = function (elem) {
inject('before', elem);
inject('after', elem);
elem.runtimeStyle.behavior = null
};
if (document.createStyleSheet) {
var o = document.createStyleSheet(null, 0);
o.addRule('.dummy', 'display:static;');
o.cssText = 'html, head, head *, body, *.before, *.after, *.before *, *.after *{behavior:none}*{behavior:expression($.pseudo(this))}'
}
