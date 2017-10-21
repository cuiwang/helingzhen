(function(k, e) {
    var w;
    var s = 64;
    var a = 0;
    var h;
    var q = 0;
    var f = 0;
    var g, x;
    var r = [],
    v = [];
    var m = WALL_INFO.re_time * 1000,
    b = WALL_INFO.show_style,
    z = WALL_INFO.show_type;
    var j = (WALL_INFO.chistory) ? (WALL_INFO.chistory * 1) : 0;
    var c = k.WBActivity.start = function() {
        g = e(".Panel.MsgList"),
        x = g.children().length - 1,
        h = g.children();
      
            k.WBActivity.hideLoading();
            e(".Panel.Top").css({
                top: 0
            });
            e(".Panel.Bottom").css({
                bottom: 0
            });
            e(".Panel.MsgList").css({
                display: "block",
                opacity: 1
            });
           
         
    }
})(window, jQuery);