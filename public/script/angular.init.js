var ptng=angular.module('ptcms', []).config(function($httpProvider){
//对php的post处理
$httpProvider.defaults.transformRequest = function(request){
if(typeof(request)!='object'){
return request;
}
var str = [];
for(var k in request){
if(k.charAt(0)=='$'){
delete request[k];
continue;
}
var v='object'==typeof(request[k])?JSON.stringify(request[k]):request[k];
str.push(encodeURIComponent(k) + "=" + encodeURIComponent(v));
}
return str.join("&");
};
$httpProvider.defaults.timeout=10000;
$httpProvider.defaults.headers.post = {
'Content-Type': 'application/x-www-form-urlencoded',
'X-Requested-With': 'XMLHttpRequest'
};
}).filter('to_trusted', ['$sce', function($sce){
return function(text) {
return $sce.trustAsHtml(text);
};
}]).filter('default',function(){
return function(v,d){
if (v==undefined) return d;
return v;
}
})

