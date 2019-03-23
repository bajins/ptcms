4.6以上对导航图标的样式做了修改,因此皮肤文件不一样了

如果你使用的是4.6beta3以及以上版本,请不要改动

如果你的控件是4.5以及以下的版本

请将如下样式做修改
.WdateDiv .navImg a{*}
.WdateDiv .NavImgll a{*}
.WdateDiv .NavImgl a{*}
.WdateDiv .NavImgr a{*}
.WdateDiv .NavImgrr a{*}

改成:
.WdateDiv .NavImg {*}
.WdateDiv .NavImgll {*}
.WdateDiv .NavImgl  {*}
.WdateDiv .NavImgr  {*}
.WdateDiv .NavImgrr {*}