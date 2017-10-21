var PATH_ACTIVITY = "";
if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function(b) {
        var a = this.length;
        var c = Number(arguments[1]) || 0;
        c = (c < 0) ? Math.ceil(c) : Math.floor(c);
        if (c < 0) {
            c += a
        }
        for (; c < a; c++) {
            if (c in this && this[c] === b) {
                return c
            }
        }
        return - 1
    }
}
document.addEventListener("WeixinJSBridgeReady", 
function onBridgeReady() {
    WeixinJSBridge.call("hideOptionMenu");
    WeixinJSBridge.call("hideToolbar")
}); (function(d, e) {
    var b,
    a = {};
    d.WBPage = {};
    d.WBPage.Loader = {
        append: function() {
            if (!b) {
                b = e('<div class="weiba-loader"></div>').appendTo("body")
            }
            b.show();
            return c()
        },
        remove: function(f) {
            if (a.hasOwnProperty(f)) {
                delete a[f]
            }
            if (WBPage.Loader.getAllIds().length == 0) {
                b.css("display", "none")
            }
        },
        removeAll: function() {
            a = {};
            b.hide()
        },
        getAllIds: function() {
            var f = [];
            e.each(a, 
            function(g, h) {
                f.push(g)
            });
            return f
        }
    };
    function c() {
        var f = "weiba_loaders_" + Math.round(Math.random() * 8000000 + 1000000);
        if (!a.hasOwnProperty(f)) {
            a[f] = true;
            return f
        } else {
            return c()
        }
    }
    e.getUrlParam = function(f) {
        var g = new RegExp("(^|&)" + f + "=([^&]*)(&|$)");
        var h = d.location.search.substr(1).match(g);
        if (h != null) {
            return unescape(h[2])
        }
        return null
    }
})(window, jQuery);
var scene_id = $.getUrlParam("rid");
