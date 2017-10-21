// positiondetails_main.js

// star
// $(document).on("ready", function () {
// 	// fast click	
// 	FastClick.attach(document.body);
// });

$(window).on("load", function() {
	StarModule.init({
		clickEffects: "flash",
		successEffects: "tada",
		failEffects: "wobble"
	});
});

var StarModule = {
	/*usage: 
	StarModule.init({
		clickEffects: "flash",
		successEffects: "tada",
		failEffects: "whobble"
	});*/
	init: function (options) {
		var $starTouchArea = this.$starTouchArea = $(".star-touch-area");
		var $star = this.$star = $(".js-star", $starTouchArea);
		this.options = options;
		this.bindEvents(options);
		this.starred = $star.hasClass("starred");
		if(options) {
			this.$star.addClass("animated");
		}
	},
	star: function () {
		if(!this.starred) {
			this.$star.addClass("starred");
			this.starred = true;
		}
		return this;
	},
	unstar: function () {
		if(this.starred) {
			this.$star.removeClass("starred");
			this.starred = false;
		}
		return this;
	},
	request: function (/*boolean star unstar*/) {
		return $.ajax({
			method: "POST",
			url: "/mobile/position",
            data: {
                ajax: true,
                m: "favorite",
                wechat_signature: $("#wechat_signature").val(),
                pid: $("#pid").val(),
                star: !this.starred,
                _xsrf: Utility.getCookie("_xsrf"),
            }
		});
	},
	addAnimation: function (effectClass) {
		this.$star.addClass(effectClass);
	},
	removeAnimation: function (effectClass) {
		var $star = this.$star;
		if(!effectClass) {
			// remove all
			$star.removeClass(Utility.obj2arr(this.options).join(" "));
		} else {
			$star.addClass(effectClass);
		}
		return this;
	},
	setStarred: function (/*boolean*/star) {
		if(star) {
			this.star();
		} else {
			this.unstar();
		}
		this.starred = star;
	},
	bindEvents: function (options) {
		var self = this;
		this.$starTouchArea.on("click", function () {
            var result = {
                msg: (self.starred ? "取消" : "") + "收藏",
                successflag: true
            };
			self
				.removeAnimation()
				.addAnimation(self.options["clickEffects"]);
			self
                .request()
				.done($.proxy(function (status) {
                    this.ajaxhandler.success.call(this, status, result);
                }, self))
				.fail($.proxy(function (status) {
                    this.ajaxhandler.fail.call(this, status, result);
                }, self))
				.always(function () {
					window.setTimeout(function(){
						self.removeAnimation();
						Utility.showMsg(result.msg, result.successflag);
					}, 1.5*1000);
				});
			return false;
		});
	},
    ajaxhandler: {
    	success: function(status, result){
    		var statusCode = status.statecode;
    		if(statusCode === -1) { // fail
    			this.ajaxhandler.fail.call(this, status, result);
    		} else if (statusCode === 1) { // needs login
                if(window.confirm("登陆后才能收藏，去登陆？")) {
                    window.location.href = status.url;
                }
                this.ajaxhandler.fail.call(this, status, result);
                result.msg = "请登陆后再收藏职位"; // padding
    		} else if (statusCode === 0) { // real success
				this.addAnimation(this.options["successEffects"]);
				this.setStarred(!this.starred);
				result.msg = result.msg + "成功！";
				result.successflag = true;
    		}
		},
        fail: function (status, result) {
			this.addAnimation(this.options["failEffects"]);
			result.msg = result.msg + "失败！请稍后再试";
			result.successflag = false;
		}
    }
};