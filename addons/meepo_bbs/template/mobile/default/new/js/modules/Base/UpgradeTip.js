!function() {
    function a() {
        var a = $("#upgradeTipWrapper");
        a.length || (a = $('<div id="upgradeTipWrapper" class="upgrade-tip-wrapper"></div>'),
        $("body").append(a)),
        c = new d({
            renderContainer: "#upgradeTipWrapper",
            renderTmpl: window.TmplInline_upgrade_tip.index,
            events: function() {
                a.on("tap", function() {
                    a.html("")
                }).on("touchend", function(a) {
                    a.preventDefault()
                })
            }
        })
    }
    function b(a) {
        a && a.level > 0 && c.update({
            level: a.level,
            level_title: a.level_title
        })
    }
    var c, d = window.renderModel;
    a(),
    window.UpgradeTip = {
        show: b
    }
}();