jQuery.fn.extend({
	delayLoading: function(a) {
		function g(d) {
			console.log(1);
			var b, c;
			if (a.container === undefined || a.container === window) {
				b = $(window).scrollTop();
				c = $(window).height() + $(window).scrollTop()
			} else {
				b = $(a.container).offset().top;
				c = $(a.container).offset().top + $(a.container).height()
			}
			return d.offset().top + d.height() + a.beforehand >= b && c >= d.offset().top - a.beforehand
		}
		function h(d) {
			var b, c;
			if (a.container === undefined || a.container === window) {
				b = $(window).scrollLeft();
				c = $(window).width() + $(window).scrollLeft()
			} else {
				b = $(a.container).offset().left;
				c = $(a.container).offset().left + $(a.container).width()
			}
			return d.offset().left + d.width() + a.beforehand >= b && c >= d.offset().left - a.beforehand
		}
		function f() {
			e.filter("img[" + a.imgSrcAttr + "]").each(function(d, b) {
				if ($(b).attr(a.imgSrcAttr) !== undefined && $(b).attr(a.imgSrcAttr) !== null && $(b).attr(a.imgSrcAttr) !== "" && g($(b)) && h($(b))) {
					var c = new Image;
					c.onload = function() {
						$(b).attr("src", c.src);
						a.duration !== 0 && $(b).hide().fadeIn(a.duration);
						$(b).removeAttr(a.imgSrcAttr);
						a.success($(b))
					};
					c.onerror = function() {
						$(b).attr("src", a.errorImg);
						$(b).removeAttr(a.imgSrcAttr);
						a.error($(b))
					};
					c.src = $(b).attr(a.imgSrcAttr)
				}
			})
		}
		a = jQuery.extend({
			defaultImg: "",
			errorImg: "",
			imgSrcAttr: "originalSrc",
			beforehand: 0,
			event: "scroll",
			duration: "normal",
			container: window,
			success: function() {},
			error: function() {}
		}, a || {});
		if (a.errorImg === undefined || a.errorImg === null || a.errorImg === "") a.errorImg = a.defaultImg;
		var e = $(this);
		if (e.attr("src") === undefined || e.attr("src") === null || e.attr("src") === "") e.attr("src", a.defaultImg);
		f();
		$(a.container).bind(a.event, function() {
			f()
		})
	}
});