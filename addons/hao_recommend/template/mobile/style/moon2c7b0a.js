!function(){
"object"!=typeof JSON&&(window.JSON={
stringify:function(){
return"";
},
parse:function(){
return{};
}
});
var e=function(){
!function(){
var e={},o={},t={};
e.COMBO_UNLOAD=0,e.COMBO_LOADING=1,e.COMBO_LOADED=2;
var n=function(e,t,n){
if(!o[e]){
o[e]=n;
for(var r=3;r--;)try{
moon.setItem(moon.prefix+e,n.toString()),moon.setItem(moon.prefix+e+"_ver",moon_map[e]);
break;
}catch(i){
moon.clear();
}
}
},r=window.alert;
window.__alertList=[],window.alert=function(e){
r(e),window.__alertList.push(e);
};
var i=function(e){
if(!e||!o[e])return null;
var n=o[e];
return"function"!=typeof n||t[e]||(n=o[e]=n(i,{},{},r),t[e]=!0),n;
};
e.combo_status=e.COMBO_UNLOAD,e.run=function(){
var o=e.run.info,t=o&&o[0],n=o&&o[1];
if(t&&e.combo_status==e.COMBO_LOADED){
var r=i(t);
n&&n(r);
}
},e.use=function(o,t){
e.run.info=[o,t],e.run();
},window.define=n,window.seajs=e;
}(),function(){
window.addEventListener&&window.__DEBUGINFO&&Math.random()<.01&&window.addEventListener("load",function(){
var e=document.createElement("script");
e.src=__DEBUGINFO.safe_js,e.type="text/javascript",e.async=!0;
var o=document.head||document.getElementsByTagName("head")[0];
o.appendChild(e);
});
}(),function(){
function e(e){
var o="; "+document.cookie,t=o.split("; "+e+"=");
return 2==t.length?t.pop().split(";").shift():void 0;
}
window.__consoleList=[];
for(var o=window.console,t=function(e){
return function(){
var t=arguments;
window.__consoleList.push({
type:e,
msg:t,
time:+new Date
});
try{
o&&o[e]&&(o[e].apply?o[e].apply(o,t):o[e](t[0]||""));
}catch(n){}
};
},n=["log","info","error","warn","debug"],r={},i=0,a=n.length;a>i;++i){
var s=n[i];
r[s]=t(s);
}
if(window.console=r,window._console=o,window.localStorage&&window.__DEBUGINFO){
var c=e("DEBUG_SWITCH"),l=window.__DEBUGINFO;
if(("1"==c||-1!=location.href.indexOf("moon_debug1=1"))&&l.debug_js){
window.__moondebug=!0;
var u=document.createElement("script");
u.src=l.debug_js,u.type="text/javascript",u.async=!0;
var d=document.head||document.getElementsByTagName("head")[0];
d.appendChild(u);
}
}
-1!=location.href.indexOf("moon_debug2=1")&&(window.onerror=function(e,o,t,n){
var r=window.console;
"undefined"!=typeof e&&r.error("error : "+e),"undefined"!=typeof o&&r.error("file : "+o);
var i=[];
"undefined"!=typeof t&&i.push("line : "+t),"undefined"!=typeof n&&i.push("col : "+n),
i.length>0&&r.error(i.join(", "));
});
}(),function(){
function e(e){
return"[object Array]"===Object.prototype.toString.call(e);
}
function t(e){
return"[object Object]"===Object.prototype.toString.call(e);
}
function n(e){
var t=e.stack||e.toString()||"";
try{
t=t.replace(/http(s)?:\/\/res\.wx\.qq\.com/g,"");
for(var n=/\/([^.]+)\/js\/(\S+?)\.js(\,|:)?/g;n.test(t);)t=t.replace(n,"$2$3");
}catch(e){
t=e.stack?e.stack:"";
}
var r=[];
for(o in f)f.hasOwnProperty(o)&&r.push(o+":"+f[o]);
return r.push("STK:"+t.replace(/\n/g,"")),r.join("|");
}
function r(e){
if(!e){
var o=window.onerror;
window.onerror=function(){},e=setTimeout(function(){
window.onerror=o,e=null;
},50);
}
}
function i(e){
var o;
if(window.ActiveXObject)try{
o=new ActiveXObject("Msxml2.XMLHTTP");
}catch(t){
try{
o=new ActiveXObject("Microsoft.XMLHTTP");
}catch(n){
o=!1;
}
}else window.XMLHttpRequest&&(o=new XMLHttpRequest);
o&&(o.open("POST",location.protocol+"//mp.weixin.qq.com/mp/jsmonitor?",!0),o.setRequestHeader("cache-control","no-cache"),
o.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8"),
o.setRequestHeader("X-Requested-With","XMLHttpRequest"),o.send(e));
}
function a(e){
return function(o,t){
if("string"==typeof o)try{
o=new Function(o);
}catch(n){
throw n;
}
var i=[].slice.call(arguments,2),a=o;
return o=function(){
try{
return a.apply(this,i.length&&i||arguments);
}catch(e){
throw e.stack&&console&&console.error&&console.error("[TryCatch]"+e.stack),l&&window.__moon_report&&(window.__moon_report([{
offset:y,
log:"timeout_error;host:"+top.location.host,
e:e
}]),r(m)),e;
}
},e(o,t);
};
}
function s(e){
return function(o,t,n){
if("undefined"==typeof n)var n=!1;
var i=this,a=t;
return t=function(){
try{
return a.apply(i,arguments);
}catch(e){
throw e.stack&&console&&console.error&&console.error("[TryCatch]"+e.stack),l&&window.__moon_report&&(window.__moon_report([{
offset:v,
log:"listener_error;type:"+o+";host:"+top.location.host,
e:e
}]),r(m)),e;
}
},a.moon_lid=D,E[D]=t,D++,e.call(i,o,t,n);
};
}
function c(e){
return function(o,t,n){
if("undefined"==typeof n)var n=!1;
var r=this;
return t=E[t.moon_lid],e.call(r,o,t,n);
};
}
var l,u,d,f,w,m,p=/MicroMessenger/i.test(navigator.userAgent),_=window.define,h=0,v=2,g=4,y=9,O=10;
if(window.__initCatch=function(e){
l=e.idkey,u=e.startKey||0,d=e.limit,f=e.reportOpt||"",w=e.extInfo||"";
},window.__moon_report=function(o){
if(/mp\.weixin\.qq\.com/.test(location.href)&&!(Math.random()>.5)&&p&&top==window&&(t(o)&&(o=[o]),
e(o)&&""!=l)){
var r="",a=[],s=[],c=[],f=[];
"number"!=typeof d&&(d=1/0);
for(var m=0;m<o.length;m++){
var _=o[m]||{};
if(!(_.offset>d||"number"!=typeof _.offset||_.offset==g&&w&&w.network_rate&&Math.random()>=w.network_rate)){
var h=1/0==d?u:u+_.offset;
a[m]="[moon]"+l+"_"+h+";"+_.log+";"+n(_.e||{})||"",s[m]=h,c[m]=1;
}
}
for(var v=0;v<s.length;v++)f[v]=l+"_"+s[v]+"_"+c[v],r=r+"&log"+v+"="+a[v];
f.length>0&&i("idkey="+f.join(";")+"&lc="+a.length+r);
}
},window.setTimeout=a(window.setTimeout),window.setInterval=a(window.setInterval),
Math.random()<.01&&window.Document&&window.HTMLElement){
var E={},D=0;
Document.prototype.addEventListener=s(Document.prototype.addEventListener),Document.prototype.removeEventListener=c(Document.prototype.removeEventListener),
HTMLElement.prototype.addEventListener=s(HTMLElement.prototype.addEventListener),
HTMLElement.prototype.removeEventListener=c(HTMLElement.prototype.removeEventListener);
}
var j=window.navigator.userAgent;
if((/ip(hone|ad|od)/i.test(j)||/android/i.test(j))&&!/windows phone/i.test(j)&&window.localStorage&&window.localStorage.setItem){
var I=window.localStorage.setItem,L=0;
window.localStorage.setItem=function(e,o){
if(!(L>=10))try{
I.call(window.localStorage,e,o);
}catch(t){
t.stack&&console&&console.error&&console.error("[TryCatch]"+t.stack),window.__moon_report([{
offset:O,
log:"localstorage_error;"+t.toString(),
e:t
}]),L++,L>=3&&window.moon&&window.moon.clear&&moon.clear();
}
};
}
window.seajs&&_&&(window.define=function(){
for(var e,o=[],t=arguments&&arguments[0],n=0,i=arguments.length;i>n;n++){
var a=e=arguments[n];
"function"==typeof e&&(e=function(){
try{
return a.apply(this,arguments);
}catch(e){
throw"string"==typeof t&&console.error("[TryCatch][DefineeErr]id:"+t),e.stack&&console&&console.error&&console.error("[TryCatch]"+e.stack),
l&&window.__moon_report&&(window.__moon_report([{
offset:h,
log:"define_error;id:"+t+";",
e:e
}]),r(m)),e;
}
},e.toString=function(e){
return function(){
return e.toString();
};
}(arguments[n])),o.push(e);
}
return _.apply(this,o);
});
}(),function(e){
function o(e,o,t){
return window.__DEBUGINFO?(window.__DEBUGINFO.res_list||(window.__DEBUGINFO.res_list=[]),
window.__DEBUGINFO.res_list[e]?(window.__DEBUGINFO.res_list[e][o]=t,!0):!1):!1;
}
function t(e,o,t){
if("object"==typeof e){
var r=Object.prototype.toString.call(e).replace(/^\[object (.+)\]$/,"$1");
if(t=t||e,"Array"==r){
for(var i=0,a=e.length;a>i;++i)if(o.call(t,e[i],i,e)===!1)return;
}else{
if("Object"!==r&&n!=e)throw"unsupport type";
if(n==e){
for(var i=e.length-1;i>=0;i--){
var s=n.key(i),c=n.getItem(s);
if(o.call(t,c,s,e)===!1)return;
}
return;
}
for(var i in e)if(e.hasOwnProperty(i)&&o.call(t,e[i],i,e)===!1)return;
}
}
}
var n=e.localStorage,r=document.head||document.getElementsByTagName("head")[0],i=1,a=1,s={
prefix:"__MOON__",
loaded:[],
unload:[],
hit_num:0,
mod_num:0,
version:1003,
init:function(){
s.loaded=[],s.unload=[];
var o,r,i;
if(n){
var a="_moon_ver_key_",c=n.getItem(a);
c!=s.version&&(s.clear(),n.setItem(a,s.version));
}
if(-1!=location.search.indexOf("no_moon1=1")&&s.clear(),n){
var l=1*n.getItem(s.prefix+"clean_time"),u=+new Date;
if(u-l>=1296e6){
s.clear();
try{
!!n&&n.setItem(s.prefix+"clean_time",+new Date);
}catch(d){}
}
}
t(moon_map,function(t,a){
if(r=s.prefix+a,i=!!t&&t.replace(/^http(s)?:\/\/res.wx.qq.com/,""),o=!!n&&n.getItem(r),
version=!!n&&(n.getItem(r+"_ver")||"").replace(/^http(s)?:\/\/res.wx.qq.com/,""),
s.mod_num++,o&&i==version)try{
var c="//# sourceURL="+a+"\n//@ sourceURL="+a;
e.eval.call(e,'define("'+a+'",[],'+o+")"+c),s.hit_num++;
}catch(l){
s.unload.push(i.replace(/^http(s)?:\/\/res.wx.qq.com/,""));
}else s.unload.push(i.replace(/^http(s)?:\/\/res.wx.qq.com/,""));
}),s.load(s.genUrl());
},
genUrl:function(){
var e=s.unload;
if(!e||e.length<=0)return[];
var o,t,n="",r=[],i={},a=-1!=location.search.indexOf("no_moon2=1"),c="//res.wx.qq.com";
-1!=location.href.indexOf("moon_debug2=1")&&(c="//mp.weixin.qq.com");
for(var l=0,u=e.length;u>l;++l)/^\/(.*?)\//.test(e[l]),RegExp.$1&&(t=RegExp.$1,n=i[t],
n?(o=n+","+e[l],o.length>1e3||a?(r.push(n+"?v="+s.version),n=location.protocol+c+e[l],
i[t]=n):(n=o,i[t]=n)):(n=location.protocol+c+e[l],i[t]=n));
for(var d in i)i.hasOwnProperty(d)&&r.push(i[d]);
return r;
},
load:function(e){
if(!e||e.length<=0)return seajs.combo_status=seajs.COMBO_LOADED,seajs.run(),void console.debug("[moon] load js complete, all in cache, cost time : 0ms, total count : "+s.mod_num+", hit num: "+s.hit_num);
seajs.combo_status=seajs.COMBO_LOADING;
var o=0,n=+new Date;
t(e,function(t){
s.request(t,a,function(){
if(o++,o==e.length){
var t=+new Date-n;
seajs.combo_status=seajs.COMBO_LOADED,seajs.run(),console.debug("[moon] load js complete, url num : "+e.length+", total mod count : "+s.mod_num+", hit num: "+s.hit_num+", use time : "+t+"ms");
}
});
});
},
request:function(e,t,n){
if(e){
t=t||0;
var a=-1;
window.__DEBUGINFO&&(__DEBUGINFO.res_list||(__DEBUGINFO.res_list=[]),__DEBUGINFO.res_list.push({
type:"js",
status:"pendding",
start:+new Date,
end:0,
url:e
}),a=__DEBUGINFO.res_list.length-1);
var c=document.createElement("script");
c.src=e,c.type="text/javascript",c.async=!0,c.onerror=function(n){
if(o(a,"status","error"),o(a,"end",+new Date),t>=0)s.request(e,t);else if(window.__moon_report){
var r=new Error(n);
window.__moon_report([{
offset:i,
log:"load_script_error: "+e,
e:r
}]);
}
},"undefined"!=typeof moon_crossorigin&&moon_crossorigin&&c.setAttribute("crossorigin",!0),
c.onload=c.onreadystatechange=function(){
o(a,"status","loaded"),o(a,"end",+new Date),!c||c.readyState&&!/loaded|complete/.test(c.readyState)||(o(a,"status","200"),
c.onload=c.onreadystatechange=null,"function"==typeof n&&n());
},t--,r.appendChild(c);
}
},
setItem:function(e,o){
!!n&&n.setItem(e,o);
},
clear:function(){
n&&(t(n,function(e,o){
~o.indexOf(s.prefix)&&n.removeItem(o);
}),console.debug("[moon] clear"));
}
};
window.moon=s;
}(window),window.moon.init();
};
e();
}();