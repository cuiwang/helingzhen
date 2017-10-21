;(function($) {
	/**
	 * 自动消失de提示
	 */
	$.flytip = function(options, callback) {

		var setting = {
			"msg" : null,
			"delay" : 2000,
			"arrow" : null,
			"position" : {
				"left" : null,
				"right" : null,
				"top" : null,
				"bottom" : null,
				"center" : true
			},
			"callback" : null
		};

		var settings = $.extend(setting, options);

		if (typeof options == "string") {
			settings.msg = options;
		}
		if (typeof callback == "number") {
			settings.delay = callback;
		} else if (typeof callback == "function") {
			settings.callback = callback;
		}

		var $temp = $("<div class='flytip'>" + "<div class='flytip-inner'>"
				+ settings.msg + "</div>" + "</div>");

		var tipInner = $temp.find(".flytip-inner");

		if (settings.arrow != null) {
			tipInner.append("<span class='flytip-arrow flytip-arrow-"
					+ settings.arrow + "'>◆</span>");
		}

		$("body").append($temp);

		if (settings.position != null) {
			if (settings.position.left != null) {
				tipInner.css("left", settings.position.left);
			}
			if (settings.position.right != null) {
				tipInner.css("right", settings.position.right);
			}
			if (settings.position.top != null) {
				tipInner.css("top", settings.position.top);
			}
			if (settings.position.bottom != null) {
				tipInner.css("bottom", settings.position.bottom);
			}
			if (settings.position.center === true) {
				tipInner.css({
					"margin-left" : -tipInner.outerWidth() / 2,
					"left" : "50%"
				});
			}
		}

		tipInner.css("opacity", "1");

		setTimeout(function() {
			tipInner.css("opacity", "0");
			setTimeout(function() {
				$temp.remove();
				settings.callback && settings.callback();
			}, 1000);
		}, settings.delay);
	};
})(jQuery);