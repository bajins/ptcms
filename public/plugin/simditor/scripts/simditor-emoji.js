(function (root, factory) {
if (typeof define === 'function' && define.amd) {
// AMD. Register as an anonymous module.
define(["jquery",
"simditor"], function ($, Simditor) {
return (root.returnExportsGlobal = factory($, Simditor));
});
} else if (typeof exports === 'object') {
// Node. Does not work with strict CommonJS, but
// only CommonJS-like enviroments that support module.exports,
// like Node.
module.exports = factory(require("jquery"),
require("Simditor"));
} else {
factory(jQuery,
Simditor);
}
}(this, function ($, Simditor) {

var EmojiButton,
__hasProp = {}.hasOwnProperty,
__extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
__slice = [].slice;

EmojiButton = (function(_super) {
__extends(EmojiButton, _super);

EmojiButton.i18n = {
'zh-CN': {
title: '表情'
},
'en': {
title: 'emoji'
}
};

EmojiButton.images = ['angry','anguished','astonished','blush','cold_sweat','confounded','confused','cry','disappointed','dizzy_face','expressionless','fearful','flushed','frowning','grimacing','grin','grinning','heart_eyes','hushed','innocent','joy','kissing_smiling_eyes','laughing','mask','neutral_face','open_mouth','pensive','persevere','relaxed','relieved','satisfied','scream','sleeping','sleepy','smile','smiley','smirk','sob','stuck_out_tongue','stuck_out_tongue_closed_eyes','stuck_out_tongue_winking_eye','sunglasses','sweat','sweat_smile','tired_face','triumph','unamused','wink','worried','yum'];

EmojiButton.prototype.name = 'emoji';

EmojiButton.prototype.icon = 'smile-o';

EmojiButton.prototype.title = EmojiButton.prototype._t('title');

EmojiButton.prototype.htmlTag = 'img';

EmojiButton.prototype.menu = true;

function EmojiButton() {
var args;
args = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
EmojiButton.__super__.constructor.apply(this, args);
$.merge(this.editor.formatter._allowedAttributes['img'], ['data-emoji', 'alt']);
}

EmojiButton.prototype.renderMenu = function() {
var $list, dir, html, name, opts, tpl, _i, _len, _ref;
tpl = '<ul class="emoji-list">\n</ul>';
opts = $.extend({
imagePath: 'images/emoji/',
images: EmojiButton.images
}, this.editor.opts.emoji || {});
html = "";
dir = opts.imagePath.replace(/\/$/, '') + '/';
_ref = opts.images;
for (_i = 0, _len = _ref.length; _i < _len; _i++) {
name = _ref[_i];
html += "<li data-name='" + name + "'><img src='" + dir + name + ".png' width='20' height='20' alt='" + name + "' /></li>";
}
$list = $(tpl);
$list.html(html).appendTo(this.menuWrapper);
return $list.on('mousedown', 'li', (function(_this) {
return function(e) {
var $img;
_this.wrapper.removeClass('menu-on');
if (!_this.editor.inputManager.focused) {
return;
}
$img = $(e.currentTarget).find('img').clone().attr({
'data-emoji': true,
'data-non-image': true
});
_this.editor.selection.insertNode($img);
_this.editor.trigger('valuechanged');
_this.editor.trigger('selectionchanged');
return false;
};
})(this));
};

return EmojiButton;

})(Simditor.Button);
Simditor.Toolbar.addButton(EmojiButton);

}));

