(function(){

	function c(a, b, c) {
	    var d;
	    for (d in b)
	        (b.hasOwnProperty(d) && !(d in a) || c) && (a[d] = b[d]);
	    return a
	}
	
	function d(a, b) {
        var c, d, e, f;
        for (a = String(a).split("."),
        b = String(b).split("."),
        c = 0,
        f = Math.max(a.length, b.length); f > c; c++) {
            if (d = isFinite(a[c]) && Number(a[c]) || 0,
            e = isFinite(b[c]) && Number(b[c]) || 0,
            e > d)
                return -1;
            if (d > e)
                return 1
        }
        return 0
    }
	
	/**
	 * tools.debuging, //是否调试
	 * tools.isObject, //是否结构
	 * tools.isFunction, //是否函数
	 * tools.isString, //是否字符串
	 * tools.isNumber, //是否数字
	 * tools.isBoolean, //是否布尔
	 * tools.isUndefined, //是否定义
	 * tools.isNull, //是否为空
	 * tools.isDate //是否日期
	 * */

	var tools = {};
	tools.debuging = !1;
	tools.version = '20160410';
	tools.clientVersion = "1.0.0",
	
	tools.ERROR_NO_SUCH_METHOD = 'no such method';
	tools.ERROR_PERMISSION_DENIED = 'permission denied';
	
	
	var C = navigator.userAgent;
	var F = Object.prototype.toString;
	var G = /\b(iPad|iPhone|iPod)\b.*? OS ([\d_]+)/;
	var H = /\bAndroid([^;]+)/;
	var Q = 1;

	c(tools,function(){
		var a = {}, b = "Object,Function,String,Number,Boolean,Date,Undefined,Null";
		return b.split(",").forEach(function(b, c) {
	        a["is" + b] = function(a) {
	            return F.call(a) === "[object " + b + "]"
	        }
	    }),a
	}());

	/**
	 * 是否邮箱
	 * */
	tools.isEmail = function(a){
		var re = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;
		if(re.test(a)){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 是否电话号码
	 */
	tools.isPhone = function(a){
		var re = /^0\d{2,3}-?\d{7,8}$/;
		if(re.test(a)){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 是否手机号
	 */
	tools.isMobile = function(){
		var re = /^1\d{10}$/;
		if(re.test(a)){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 是否为空
	 * */
	tools.isEmpty = function(v){
		switch (typeof v) {
	    	case 'undefined':return true;
	    	case 'string':
	    		if (tools.trim(v).length == 0) {return true;break;}
	    	case 'boolean':
	    		if (!v) {return true;break;}
	    	case 'number':
	    		if (0 === v)return true;
	    		break;
	    	case 'object':
	    		if (null  === v)return true;
	    		if (undefined !== v.length && v.length == 0) {return true;}
	    }
	    return false;
	}
	/**
	 * 去掉空格
	 * */
	tools.trim = function(str) {
	    return str.replace(/(^\s*)|(\s*$)/g, "");
	}
	/**
	 * url解析
	 * */
	tools.querystring = function(name) {
        var result = location.search.match(new RegExp("[\?\&]" + name + "=([^\&]+)","i"));
        if (result == null  || result.length < 1) {
            return "";
        }
        return result[1];
    }
	/**
	 * 跳转到
	 * */
	tools.go = function(d,m){
		var i = tools.querystring('i');
        var j = tools.querystring('j');
        var url = './index.php?i=' + i + '&j=' + j + '&c=entry&do=' + d + '&m='+m;
        window.location.href = url;
	};
	
	/**
	 * 是否ios
	 * */
	tools.iOS = G.test(C);
	tools.android = H.test(C);
	
	/**
	 * 版本号比较
	 * 如果版本号小于当前版本号返回1
	 * 如果大于当前版本号返回-1
	 * 如果等于当前版本号 返回0
	 * */
	tools.compare = function(b){
		return d(tools.clientVersion, b)
	}
	
	tools.browser = function(){
		return 'browser';
	}
	
	window.tools = tools;
})();
