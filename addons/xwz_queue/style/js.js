$.showOpenBox = {
    htmls: function(a) {
        var b = '<div class="showOpenBox-bg"></div><div class="showOpenBox-loading"><span></span></div>';
        $("body").append(b)
    },
    show: function(a) {
        var b = this;
        b.htmls();
        $(".showOpenBox-bg").css("display", "block");
        $(".showOpenBox-loading").css("display", "block")
    },
    close: function() {
        $(".showOpenBox-bg").remove();
        $(".showOpenBox-loading").remove()
    }
};