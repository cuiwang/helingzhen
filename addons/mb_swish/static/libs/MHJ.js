define([('__proto__' in {} ? 'zepto' : 'jquery')], function($) {

	var MHJ = window.MHJ || {};

	MHJ.mlog = MHJ.mlog || function() {
		console && console.log(arguments);
	};

	MHJ.post = MHJ.post || function(url, data, callback) {
		$.post(url, data, function(json) {
			try {
				//alert('vv-----'+JSON.stringify(json));
				callback && callback(json);
			} catch (e) {
				//TODO错误处理
				alert('AJAX请求处理错误', 'error');
			}
		}, "json");
	};

	MHJ.getUrlParam = MHJ.getUrlParam || function(url) {
		var str = url;
		var jinghao = 0; //存放'#'的位置
		var jinghaoyu = null; //存放'#'后面第一个'&'的位置
		if (!str) str = window.location.href;
		while(str.indexOf('#') > -1){ //以防url中出现多个'#'
			jinghao = str.indexOf('#',jinghao + 1);
			var jinghaostr = str.substr(jinghao);
			if(jinghaostr.indexOf('&') > -1){
				jinghaoyu = jinghaostr.indexOf('&');
			}else{
				jinghaoyu = jinghaostr.length;
			}
			str = str.replace(str.substr(jinghao,jinghaoyu),'');
		}
//		str = str.replace("#rd", ""); //微信阅读原文的url中会出现#rd
//		str = str.split("#")[0];
		var obj = new Object();
		if (str.indexOf('?') > -1) {
			var string = str.substr(str.indexOf('?') + 1);
			var strs = string.split('&');
			for (var i = 0; i < strs.length; i++) {
				var tempArr = strs[i].split('=');
				obj[tempArr[0]] = tempArr[1];
			}
		}
		return obj;
	};

	MHJ.setCookie = MHJ.setCookie || function(name, value, expire_days) {
		var exdate = new Date();
		exdate.setDate(exdate.getDate() + expire_days);
		document.cookie = name + '=' + escape(value) + ((expire_days == null) ? '' : ';expires=' + exdate.toGMTString());
	};

	MHJ.getCookie = MHJ.getCookie || function(name) {
		var arr, reg = new RegExp('(^| )' + name + '=([^;]*)(;|$)');
		if (arr = document.cookie.match(reg))
			return (arr[2]);
		else
			return null;
	};

	MHJ.delCookie = MHJ.delCookie || function(name) {
		var exp = new Date();
		exp.setTime(exp.getTime() - 1);
		var cval = this.getCookie(name);
		if (cval != null)
			document.cookie = name + '=' + cval + ';expires=' + exp.toGMTString();
	};
	MHJ.tmpl = MHJ.tmpl || (function(cache) {
		var r = /(?:^|%>)([\s|\S]*?)(<%(?!\=)|$)/g,
			z = /(\"|\\)/g,
			m = /<%=([\s\S]*?)%>/g;
		return function(s, data) {
			if (!(s in cache)) {
				cache[s] = s.replace(r, function(a, b) {
					return ';s.push("' + b.replace(z, "\\$1").replace(m, function(e, f) {
						return '",' + f.replace(/\\"/g, '"') + ',"';
					}) + '");';
				}).replace(/\r|\n/g, "");
			}
			var $fn = Function('data', "var s=[];" + cache[s] + " return s.join('');");
			return data ? $fn(data) : $fn;
		};
	})({});
	return MHJ;

});