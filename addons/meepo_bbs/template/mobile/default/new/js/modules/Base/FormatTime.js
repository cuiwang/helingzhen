!function(a, b) {
    "function" == typeof define && define.amd ? define([], b) : a.FormatTime = b()
}(this, function() {
    function a(a, b) {
        return 2 === b ? a % 4 === 0 && a % 100 !== 0 ? 29 : 28 : 7 >= b && b % 2 === 1 || b > 7 && b % 2 === 0 ? 31 : 30
    }
    function b(a, b) {
        return new Date(a.getFullYear() + b,a.getMonth(),a.getDate(),a.getHours(),a.getMinutes(),a.getSeconds())
    }
    function c(b) {
        var c, d, e = b.getMonth();
        return 11 === e ? new Date(b.getFullYear() + 1,1,b.getDate(),b.getHours(),b.getMinutes(),b.getSeconds()) : (c = a(b.getFullYear(), b.getMonth() + 1),
        d = b.getDate(),
        d > c && (d = c),
        new Date(b.getFullYear(),b.getMonth() + 1,d,b.getHours(),b.getMinutes(),b.getSeconds()))
    }
    return function(a, d) {
        if (a = Number(a),
        !a || 0 > a)
            return "";
        d = d || new Date / 1e3;
        var e = d - a
          , f = new Date(1e3 * a)
          , g = new Date(1e3 * d)
          , h = 0
          , i = 0;
        return 59 >= e ? "刚刚" : 61 > e ? "1分钟前" : 3600 > e ? Math.floor(e / 60) + "分钟前" : 86400 > e ? Math.floor(e / 3600) + "小时前" : c(f) > g ? Math.floor(e / 86400) + "天前" : b(f, 1) > g ? (h = g.getMonth() + 12 * (g.getFullYear() - f.getFullYear()) - f.getMonth() - 1,
        i = g.getDate() - f.getDate(),
        i >= -1 && (h += 1),
        Math.floor(h) + "个月前") : "更早"
    }
});