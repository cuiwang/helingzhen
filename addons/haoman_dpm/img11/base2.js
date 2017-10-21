var PATH_ACTIVITY = "";
if (!Array.prototype.indexOf) { Array.prototype.indexOf = function(b) {
        var a = this.length;
        var c = Number(arguments[1]) || 0;
        c = (c < 0) ? Math.ceil(c) : Math.floor(c);
        if (c < 0) { c += a }
        for (; c < a; c++) {
            if (c in this && this[c] === b) {
                return c } }
        return -1 } }(function(a, b) { b.fn.scroll_subtitle = function() {
        return this.each(function() {
            var c = b(this);
            if (c.children().length > 1) { a.setInterval(function() {
                    var e = c.children(),
                        d = b(e[0]);
                    d.slideUp(2000, function() { d.remove().appendTo(c).show() }) }, 5000) } }) };
    b.preloadImages = function(c, j) {
        if (b.isArray(c)) {
            var e = c.length;
            if (e > 0) {
                var h = 0,
                    d = function() { h++;
                        if (h >= e) {
                            if (typeof j == "function") { a.setTimeout(j, 100) } } };
                for (var g = 0; g < e; g++) {
                    var f = new Image();
                    f.onload = d;
                    f.onerror = d;
                    f.src = c[g] } } } };
    b.getUrlParam = function(c) {
        var d = new RegExp("(^|&)" + c + "=([^&]*)(&|$)");
        var e = a.location.search.substr(1).match(d);
        if (e != null) {
            return unescape(e[2]) }
        return null };
    b.fn.toFillText = function() {
        return this.each(function() {
            var c = b(this),
                e = c.html(),
                f = c.height();
            c.html("");
            var g = b("<div>" + e + "</div>").appendTo(c);
            g.css("font-size", "12px");
            for (var d = 12; d < 200; d++) {
                if (g.height() > f) { c.css("font-size", (d - 2) + "px").html(e);
                    break } else { g.css("font-size", d + "px") } } }) };
    b.fillText = function(c) {
        var e = c.html(),
            f = c.height();
        c.html("");
        var g = b("<div>" + e + "</div>").appendTo(c);
        g.css("font-size", "12px");
        for (var d = 12; d < 200; d++) {
            if (g.height() > f) { c.css("font-size", (d - 2) + "px").html(e);
                break } else { g.css("font-size", d + "px") } } };
    b.showPage = function(c) {
        var d = b('<div class="frame-dialog"><iframe frameborder="0" src="' + c + '"></iframe><div class="closebutton"></div></div>');
        d.appendTo("body").show().on("click", ".closebutton", function() { d.hide(function() { d.remove();
                d = null }) }) };
    a.WBActivity = { showLoginForm: function() { b(".loginform").fadeIn() }, hideLoginForm: function() { b(".loginform").fadeOut() }, showLoading: function() { b(".loader").fadeIn() }, hideLoading: function() { b(".loader").fadeOut() } };
    b(function() { 
    	a.WBActivity.start();
    	a.sessionStorage.setItem("loginkey", 1);
    	b(".top_title").scroll_subtitle();
        b(".button-login").on("click", function() { a.WBActivity.showLoading();
            b.getJSON(PATH_ACTIVITY + "?ac=wxc_password&callback=", { scene_id: scene_id, password: b("#password").val() }, function(e) {
                if (e && e.ret == 0 && e.data == 1) { a.sessionStorage.setItem("loginkey", 1);
                    a.WBActivity.hideLoginForm();
                    a.WBActivity.start() } else { alert("密码错误") } }).complete(function() { a.WBActivity.hideLoading() }) });
        b(".mp_account_codeimage").on("click", function() { b(".bigmpcodebar").slideDown() });
        b(".bigmpcodebar .closebutton").on("click", function() { b(".bigmpcodebar").slideUp() });
        b(".navbaritem.fullscreen").on("click", function() { b.toggleFullScreen() });
        var c = "_wb_islogin" + scene_id,
            d = a.sessionStorage.getItem("loginkey");
        if (!d) { a.WBActivity.hideLoading();
            a.WBActivity.showLoginForm() } else { a.WBActivity.start() } });
    b(a).on("resize", function() { a.WBActivity.resize() }) })(window, jQuery);
(function(d, e) {
    var f = { supportsFullScreen: false, isFullScreen: function() {
                return false }, requestFullScreen: function() {}, cancelFullScreen: function() {}, fullScreenEventName: "", prefix: "" },
        c = "webkit moz o ms khtml".split(" ");
    if (typeof document.cancelFullScreen != "undefined") { f.supportsFullScreen = true } else {
        for (var b = 0, a = c.length; b < a; b++) { f.prefix = c[b];
            if (typeof document[f.prefix + "CancelFullScreen"] != "undefined") { f.supportsFullScreen = true;
                break } } }
    if (f.supportsFullScreen) { f.fullScreenEventName = f.prefix + "fullscreenchange";
        f.isFullScreen = function() {
            switch (this.prefix) {
                case "":
                    return document.fullScreen;
                case "webkit":
                    return document.webkitIsFullScreen;
                default:
                    return document[this.prefix + "FullScreen"] } };
        f.requestFullScreen = function(g) {
            return (this.prefix === "") ? g.requestFullScreen() : g[this.prefix + "RequestFullScreen"]() };
        f.cancelFullScreen = function(g) {
            return (this.prefix === "") ? document.cancelFullScreen() : document[this.prefix + "CancelFullScreen"]() } }
    if (typeof jQuery != "undefined") { jQuery.fn.requestFullScreen = function() {
            return this.each(function() {
                var g = jQuery(this);
                if (f.supportsFullScreen) { f.requestFullScreen(g) } }) } }
    d.fullScreenApi = f;
    e.toggleFullScreen = function() {
        if (f.isFullScreen()) { f.cancelFullScreen(document.documentElement) } else { f.requestFullScreen(document.documentElement) } } })(window, jQuery);
var scene_id = $.getUrlParam("id");
