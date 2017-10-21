var Util = {};
/**
 * 解析url
 * */
var C = function(a){
	var b = window.location.search.match(new RegExp("(?:\\?|&)" + a + "=([^&]*)(&|$)")), c = b ? decodeURIComponent(b[1]) : "";
	return c
}

var E = function(a){
	var b = window.location.hash.match(new RegExp("(?:#|&)" + a + "=([^&]*)(&|$)")), c = b ? decodeURIComponent(b[1]) : "";
  return c || C(a)
}

var F = function(a, b){
	var c = E(a);
	c ? window.location.hash = window.location.hash.replace(new RegExp("(?:#|&)" + a + "=([^&]*)(&|$)"), function(a, c) {
        return a.replace("=" + c, "=" + b)
    }) : window.location.hash += "&" + [a, b].join("=")
}

function b(a, b, c, d, e) {
    
}


Util.queryString = C;
Util.getHash = E;
Util.setHash = F;
Util.openUrl = b;