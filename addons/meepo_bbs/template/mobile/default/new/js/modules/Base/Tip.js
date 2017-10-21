!function(a, b) {
    a.Tip = b(a.Simple)
}(this, function() {
    var a, b, c, d, e, f = !1, g = function() {
        a = $('<div class="tip"></div>'),
        a.html("<i></i><span></span>"),
        $(document.body).append(a),
        c = a.children()[0],
        b = a.children()[1]
    }
    ;
    return {
        show: function(h, i) {
            if (i = $.extend({interval: 2e3,top: 0,type: "ok"}, i),mqq && mqq.compare("5.7") > -1){
            	return void mqq.ui.showTips({
                    text: h,
                    iconMode: "ok" === i.type ? 1 : 2
                });
            }
            f || (g(),f = !0);
            var j = i.interval;
            if (c.className = i.type,b.innerHTML = h,
	            setTimeout(function() {
	                a.addClass("show")
	            }, 100),d = !0,j
	        ) {
                var k = this;
                clearTimeout(e),
                e = setTimeout(function() {
                    k.hide()
                }, j)
            }
        },
        hide: function() {
            a && (a.removeClass("show"),
            d = !1)
        }
    }
});