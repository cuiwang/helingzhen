!function(a, b) {
    "function" == typeof define && define.amd ? define([], b) : a.numHelper = b()
}(this, function() {
    return function(a) {
        if ("number" != typeof a)
            return a;
        a = +a;
        var b, c = "", d = 1e4, e = 1e8;
        return d > a ? c = "" + a : a >= d && e > a ? (b = ("" + (a / d).toFixed(1)).split("."),
        c = ("0" !== b[1] ? b.join(".") : b[0]) + "万") : a >= e && (b = ("" + (a / e).toFixed(2)).split("."),
        c = ("0" !== b[1] ? b.join(".") : b[0]) + "亿"),
        c
    }
});