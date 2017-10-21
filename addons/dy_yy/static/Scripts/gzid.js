//写cookie
function setCookie(name,value,expires){
var exp=new Date();
exp.setTime(exp.getTime()+expires*24*60*60*1000); //天
document.cookie=name+"="+escape(value)+";expires="+exp.toGMTString()+";path=/";
} 

//读取cookie
function readcookie(name){
var oRegex=new RegExp(name+'=([^;]+)','i');
var oMatch=oRegex.exec(document.cookie);
if(oMatch&&oMatch.length>1)return unescape(oMatch[1]);
else return '';
}

//获取url中"?"符后的字串
function GetRequest() {
   var url = location.search; //获取url中"?"符后的字串
   var theRequest = new Object();
   if (url.indexOf("?") != -1) {
      var str = url.substr(1);
      strs = str.split("&");
      for(var i = 0; i < strs.length; i ++) {
         theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);
      }
   }
   return theRequest;
}

//获取url中"?"符后的字串
function GetRequesta(aaa) {
   var bbb= aaa.indexOf('?');
   var url=aaa.substr(bbb);
   var theRequest = new Object();
   if (url.indexOf("?") != -1) {
      var str = url.substr(1);
      strs = str.split("&");
      for(var i = 0; i < strs.length; i ++) {
         theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);
      }
   }
   return theRequest;
}

var getstr = new Object();
getstr = GetRequest();
var gzid=getstr["gzid"];

  if (gzid != null && gzid != "") {
     setCookie("gzid",gzid,7)
  }
  