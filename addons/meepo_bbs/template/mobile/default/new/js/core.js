(function($){
var core = {};
	
	/**公用*/
	core.tool = {
	    trim: function(text) {
	        return text.replace(/(^\s*)|(\s*$)/g, "");
	    },
	    len: function(text) {
	        return text.replace(/[^\x00-\xff]/g, "aa").length;
	    },
	    encode: function(text) {
	        return escape(encodeURIComponent(text));
	    },
	    htmlencode: function(text) {
	        return text.replace(/\'/g, "&#39;")
	            .replace(/\"/g, "&quot;")
	            .replace(/</g, "&lt;")
	            .replace(/>/g, "&gt;")
	            .replace(/ /g, "&nbsp;")
	            .replace(/\n\r/g, "<br>")
	            .replace(/\r\n/g, "<br>")
	            .replace(/\n/g, "<br>");
	    },
	    htmlencodeReturn: function(text) {
	        return text.replace(/&#39;/g, "\'")
	        .replace(/&quot;/g, "\"")
	        .replace(/&lt;/g, "<")
	        .replace(/&gt;/g, ">")
	        .replace(/&nbsp;/g, " ")
	        .replace(/&amp;/g, "&");
	    },
	    zero: function(n) {
	        return n < 0 ? 0 : n;
	    },
	    scroll: function() {
	        return {
	            x: $(document).scrollLeft() + $(window).scrollLeft(),
	            y: $(document).scrollTop() + $(window).scrollTop()
	        };
	    },
	    client: function() {
	        return {
	            w: document.documentElement.scrollWidth,
	            h: document.documentElement.scrollHeight,
	            bw: $(window).width(),
	            bh: $(window).height()
	        };
	    },
	    center: function(id) {
	        var _top = _._zero(_._client().bh - $("#" + id).outerHeight()) / 2;
	        var _left = _._zero(_._client().bw - $("#" + id).outerWidth()) / 2;

	        $("#" + id).css({
	            "top": _top + "px",
	            "left": _left + "px"
	        });
	    },
	    isHide: function(id) {
	        $("#" + id).css("display") == "none";
	    }
	};
	/*
	Date.prototype.Format = function(formatStr){
		var str = formatStr; 
		var Week = ['日','一','二','三','四','五','六']; 
		str=str.replace(/yyyy|YYYY/,this.getFullYear());
		str=str.replace(/yy|YY/,(this.getYear() % 100)>9?(this.getYear() % 100).toString():'0' + (this.getYear() % 100));
		str=str.replace(/MM/,this.getMonth()>9?this.getMonth().toString():'0' + this.getMonth());
		str=str.replace(/M/g,this.getMonth());
		str=str.replace(/w|W/g,Week[this.getDay()]);
		str=str.replace(/dd|DD/,this.getDate()>9?this.getDate().toString():'0' + this.getDate());
		str=str.replace(/d|D/g,this.getDate());
		str=str.replace(/hh|HH/,this.getHours()>9?this.getHours().toString():'0' + this.getHours());
		str=str.replace(/h|H/g,this.getHours());
		
	}*/
	
	
	core.isWeiXin = function(){
		var ua = window.navigator.userAgent.toLowerCase();
	    if(ua.match(/MicroMessenger/i) == 'micromessenger'){
	        return true;
	    }else{
	        return false;
	    }
	}
	
	core.go = function(d){
		var i = core.querystring('i');
        var j = core.querystring('j');
        
        var url = './index.php?i=' + i + '&j=' + j + '&c=entry&do=' + d + '&m=meepo_bar';
        window.location.href = url;
	};
	
	core.post = function(d, data, call,t) {
        var i = core.querystring('i');
        var j = core.querystring('j');
        
        var type = t || 'json';
        
        var url = './index.php?i=' + i + '&j=' + j + '&c=entry&do=' + d + '&m=meepo_bar';
        $.post(url, data, call,type);
    }
	
	/**
	 * 调试用 弹出json结构体
	 * */
	
	core.alert = function(obj){
		alert(JSON.stringify(obj));
	}
	/**
	 * url解析
	 * */
	core.querystring = function(name) {
        var result = location.search.match(new RegExp("[\?\&]" + name + "=([^\&]+)","i"));
        if (result == null  || result.length < 1) {
            return "";
        }
        return result[1];
    }
	/**
	 * 动态加载js
	 * */
	core.load_script = function(xyUrl, callback){
	    var head = document.getElementsByTagName('head')[0];
	    var script = document.createElement('script');
	    script.type = 'text/javascript';
	    script.src = xyUrl;
	    //借鉴了jQuery的script跨域方法
	    script.onload = script.onreadystatechange = function(){
	        if((!this.readyState || this.readyState === "loaded" || this.readyState === "complete")){
	            callback && callback();
	            // Handle memory leak in IE
	            script.onload = script.onreadystatechange = null;
	            if ( head && script.parentNode ) {
	                head.removeChild( script );
	            }
	        }
	    };
	    head.insertBefore( script, head.firstChild );
	}
	
	/**
	 * 加载百度地图转换插件
	 * */
	
	core.translate = function(point,type,callback){
	    var callbackName = 'cbk_' + Math.round(Math.random() * 10000);    //随机函数名
	    var xyUrl = "http://api.map.baidu.com/ag/coord/convert?from="+ type + "&to=4&x=" + point.lng + "&y=" + point.lat + "&callback=BMap.Convertor." + callbackName;
	    //动态创建script标签
	    core.load_script(xyUrl);
	    BMap.Convertor[callbackName] = function(xyResult){
	        delete BMap.Convertor[callbackName];    //调用完需要删除改函数
	        var point = new BMap.Point(xyResult.x, xyResult.y);
	        callback && callback(point);
	    }
	}
	
	core.empty = function(v) {
	    switch (typeof v) {
	    case 'undefined':
	        return true;
	    case 'string':
	        if (core.trim(v).length == 0) {
	            return true;
	            break;
	        }
	    case 'boolean':
	        if (!v) {
	            return true;
	            break;
	        }
	    case 'number':
	        if (0 === v)
	            return true;
	        break;
	    case 'object':
	        if (null  === v)
	            return true;
	        if (undefined !== v.length && v.length == 0) {
	            return true;
	        }
	    }
	    return false;
	}

	core.trim = function(str) {
	    return str.replace(/(^\s*)|(\s*$)/g, "");
	}
	
	window.core = core;
})(window.JQuery);