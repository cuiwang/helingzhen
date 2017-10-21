!function(a, b) {
    a.$ = b(a.$)
}(this, function(a) {
    return a.storage = function() {
        function a(a, b) {
            if (window.localStorage) {
                var d, e = localStorage.getItem(c);
                if (e)
                    try {
                        d = JSON.parse(e)
                    } catch (f) {
                        d = {}
                    }
                else
                    d = {};
                d[a] = b;
                try {
                    localStorage.setItem(c, JSON.stringify(d))
                } catch (f) {}
            }
        }
        function b(a) {
            if (window.localStorage) {
                var b, d = localStorage.getItem(c);
                if (d)
                    try {
                        return b = JSON.parse(d),
                        b[a]
                    } catch (e) {}
            }
            return ""
        }
        var c = Login.getUin() + "buluoInfo";
        return {
            set: a,
            get: b
        }
    }(),
    window.localStorage.clear = function() {
        for (var a in window.localStorage)
            if (window.localStorage.hasOwnProperty(a)) {
                if (/^im_/.test(a))
                    continue;window.localStorage.removeItem(a)
            }
    }
    ,
    a
});