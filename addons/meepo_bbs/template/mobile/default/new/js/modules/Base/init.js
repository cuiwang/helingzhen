function() {
    function a(a, b) {
        if (!document.getElementById(b)) {
            var c = document.createElement("style");
            c.id = b,
            (document.getElementsByTagName("head")[0] || document.body).appendChild(c),
            c.styleSheet ? c.styleSheet.cssText = a : c.appendChild(document.createTextNode(a))
        }
    }
    var b = ".l-level{ line-height:12px; } .honour{line-height:12px;}";
    mqq.android && parseInt($.os.version, "10") >= 6 && a(b, "fixedstyle")
}(window);