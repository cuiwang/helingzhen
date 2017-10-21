(function(window,JSON){
	function JsonUtil() {
		var config = {};
        this.get = function(n) {
            return config[n];
        }

        this.set = function(n, v) {
            config[n] = v;
        }
        this.init();
	}
	JsonUtil.prototype = {
			init: function() {
				
			},
			parse: function(str) {
				str = str.replace(/\r\n/g,'<br>'); //回车换行替换
		        str = str.replace(/\n/g,'<br>');  //换行替换
				str = str.replace(/\t/g,''); // 去掉制表符
		        str= str.replace( /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,''); 

				return JSON.parse(str);
	        },
	        eval: function(str) {
	        	str = str.replace(/\r\n/g,'<br>'); //回车换行替换
		        str = str.replace(/\n/g,'<br>');  //换行替换
	        	str = str.replace(/\s/g,''); // 去掉所有空白符
		        str= str.replace( /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,''); 

				return eval('(' + str + ')');
	        },
	        evalNoSpace: function(str) {
	        	str = str.replace(/\r\n/g,'<br>'); //回车换行替换
		        str = str.replace(/\n/g,'<br>');  //换行替换
		        str= str.replace( /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,''); 
				return eval('(' + str + ')');
	        }
	}
	
	window.JsonUtil = window.JsonUtil || JsonUtil;
	
})(window,JSON)