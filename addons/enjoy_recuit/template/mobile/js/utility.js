var Utility = {
	obj2arr: function(obj) {
		var arr = [];
		for(var key in obj) {
			if(obj.hasOwnProperty(key)) {
				arr.push(obj[key]);
			}
		}
		return arr;
	},
	extend: function(/*target, src0, src1...*/) {
		var args = Array.prototype.slice.call(arguments);
		var target = args[0],
			srcs = args.slice(1);
		for(var i=0; i<srcs.length; i++) {
			var src = srcs[i];
			for(var prop in src) {
				if(src.hasOwnProperty(prop)) {
					target[prop] = src[prop];
				}
			}
		}
		return target;
	},
	showMsg: (function () {
		var setTimeoutId = null;
		return function (msg, successFlag, time) {
			var $globalMessage = $(".globalMessage");
			window.clearTimeout(setTimeoutId);
			$globalMessage.hide().text(msg);
			successFlag ? $globalMessage.removeClass("error") : $globalMessage.addClass("error");
			$globalMessage.fadeIn();
			setTimeoutId = window.setTimeout(function() {
				$globalMessage.fadeOut();
			}, (time||3)*1000);
		}
	}()),
	falsy: function () {
		return false;
	},
	getCookie: function(name) {
		var r = document.cookie.match("\\b" + name + "=([^;]*)\\b");
    	return r ? r[1] : undefined;
	},
	checkGlobalMessage: function(time/*sec*/) {
		var $globalMessage = $(".globalMessage");
		var text = $globalMessage.text().trim();
		if (text.length > 0) {
			Utility.showMsg(text, false, time*1000||5); // defaults to 5 seconds
		}
	}
};