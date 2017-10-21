(function (global) {
    var ns = {modules : {}, instances : {}}, waiter = {};

    function getMappingArgs(fn) {
        var args = fn.toString().split("{")[0].replace(/\s|function|\(|\)/g, "").split(","), i = 0;
        if(!args[0]){
            args = []
        }
        while( args[i] ){
            args[i] = require(args[i]);
            i += 1
        }
        return args
    }

    function newInst(key, ifExist) {
        if((ifExist ? ns.instances[key] : !ns.instances[key]) && ns.modules[key]){
            ns.instances[key] = ns.modules[key].apply(window, getMappingArgs(ns.modules[key]))
        }
    }

    function require(key) {
        newInst(key, false);
        return ns.instances[key] || {}
    }

    function loadJs(url) {
        var el = document.createElement("script");
        el.setAttribute("type", "text/javascript");
        el.setAttribute("src", url);
        el.setAttribute("async", true);
        document.getElementsByTagName("head")[0].appendChild(el)
    }

    function core(key, target) {
        ns.modules[key] = target;
        newInst(key, true);
        if(!!waiter[key]){
            var i = 0;
            while( waiter[key][i] ){
                waiter[key][i](require(key));
                i += 1
            }
            delete waiter[key]
        }
    }

    core.use       = function (key, cb) {
        cb = cb || function () {
            };
        if(ns.modules[key]){
            cb(require(key))
        }else{
            var config = require("config");
            if(config[key]){
                if(!waiter[key]){
                    waiter[key] = [];
                    loadJs(config[key])
                }
                waiter[key].push(cb)
            }
        }
    };
    core.get       = function (key) {
        return require(key)
    };
    core.loadJs    = loadJs;
    global.qcVideo = core
})(window);
var CryptoJS = CryptoJS || function (h, r) {
        var k = {}, l = k.lib = {}, n = function () {
        }, f                          = l.Base = {
            extend : function (a) {
                n.prototype = this;
                var b       = new n;
                a && b.mixIn(a);
                b.hasOwnProperty("init") || (b.init = function () {
                    b.$super.init.apply(this, arguments)
                });
                b.init.prototype = b;
                b.$super         = this;
                return b
            }, create : function () {
                var a = this.extend();
                a.init.apply(a, arguments);
                return a
            }, init : function () {
            }, mixIn : function (a) {
                for( var b in a ){
                    a.hasOwnProperty(b) && (this[b] = a[b])
                }
                a.hasOwnProperty("toString") && (this.toString = a.toString)
            }, clone : function () {
                return this.init.prototype.extend(this)
            }
        }, j = l.WordArray = f.extend({
            init : function (a, b) {
                a = this.words = a || [];
                this.sigBytes = b != r ? b : 4 * a.length
            }, toString : function (a) {
                return (a || s).stringify(this)
            }, concat : function (a) {
                var b = this.words, d = a.words, c = this.sigBytes;
                a     = a.sigBytes;
                this.clamp();
                if(c % 4){
                    for( var e = 0; e < a; e++ ){
                        b[c + e >>> 2] |= (d[e >>> 2] >>> 24 - 8 * (e % 4) & 255) << 24 - 8 * ((c + e) % 4)
                    }
                }else{
                    if(65535 < d.length){
                        for( e = 0; e < a; e += 4 ){
                            b[c + e >>> 2] = d[e >>> 2]
                        }
                    }else{
                        b.push.apply(b, d)
                    }
                }
                this.sigBytes += a;
                return this
            }, clamp : function () {
                var a    = this.words, b = this.sigBytes;
                a[b >>> 2] &= 4294967295 << 32 - 8 * (b % 4);
                a.length = h.ceil(b / 4)
            }, clone : function () {
                var a   = f.clone.call(this);
                a.words = this.words.slice(0);
                return a
            }, random : function (a) {
                for( var b = [], d = 0; d < a; d += 4 ){
                    b.push(4294967296 * h.random() | 0)
                }
                return new j.init(b, a)
            }
        }), m = k.enc = {}, s = m.Hex = {
            stringify : function (a) {
                var b = a.words;
                a     = a.sigBytes;
                for( var d = [], c = 0; c < a; c++ ){
                    var e = b[c >>> 2] >>> 24 - 8 * (c % 4) & 255;
                    d.push((e >>> 4).toString(16));
                    d.push((e & 15).toString(16))
                }
                return d.join("")
            }, parse : function (a) {
                for( var b = a.length, d = [], c = 0; c < b; c += 2 ){
                    d[c >>> 3] |= parseInt(a.substr(c, 2), 16) << 24 - 4 * (c % 8)
                }
                return new j.init(d, b / 2)
            }
        }, p = m.Latin1 = {
            stringify : function (a) {
                var b = a.words;
                a     = a.sigBytes;
                for( var d = [], c = 0; c < a; c++ ){
                    d.push(String.fromCharCode(b[c >>> 2] >>> 24 - 8 * (c % 4) & 255))
                }
                return d.join("")
            }, parse : function (a) {
                for( var b = a.length, d = [], c = 0; c < b; c++ ){
                    d[c >>> 2] |= (a.charCodeAt(c) & 255) << 24 - 8 * (c % 4)
                }
                return new j.init(d, b)
            }
        }, t = m.Utf8 = {
            stringify : function (a) {
                try{
                    return decodeURIComponent(escape(p.stringify(a)))
                }catch(b){
                    throw Error("Malformed UTF-8 data")
                }
            }, parse : function (a) {
                return p.parse(unescape(encodeURIComponent(a)))
            }
        }, q = l.BufferedBlockAlgorithm = f.extend({
            reset : function () {
                this._data       = new j.init;
                this._nDataBytes = 0
            }, _append : function (a) {
                "string" == typeof a && (a = t.parse(a));
                this._data.concat(a);
                this._nDataBytes += a.sigBytes
            }, _process : function (a) {
                var b = this._data, d = b.words, c = b.sigBytes, e = this.blockSize, f = c / (4 * e), f = a ? h.ceil(f) : h.max((f | 0) - this._minBufferSize, 0);
                a     = f * e;
                c     = h.min(4 * a, c);
                if(a){
                    for( var g = 0; g < a; g += e ){
                        this._doProcessBlock(d, g)
                    }
                    g = d.splice(0, a);
                    b.sigBytes -= c
                }
                return new j.init(g, c)
            }, clone : function () {
                var a   = f.clone.call(this);
                a._data = this._data.clone();
                return a
            }, _minBufferSize : 0
        });
        l.Hasher = q.extend({
            cfg : f.extend(), init : function (a) {
                this.cfg = this.cfg.extend(a);
                this.reset()
            }, reset : function () {
                q.reset.call(this);
                this._doReset()
            }, update : function (a) {
                this._append(a);
                this._process();
                return this
            }, finalize : function (a) {
                a && this._append(a);
                return this._doFinalize()
            }, blockSize : 16, _createHelper : function (a) {
                return function (b, d) {
                    return (new a.init(d)).finalize(b)
                }
            }, _createHmacHelper : function (a) {
                return function (b, d) {
                    return (new u.HMAC.init(a, d)).finalize(b)
                }
            }
        });
        var u    = k.algo = {};
        return k
    }(Math);
(function () {
    var h        = CryptoJS, j = h.lib.WordArray;
    h.enc.Base64 = {
        stringify : function (b) {
            var e = b.words, f = b.sigBytes, c = this._map;
            b.clamp();
            b = [];
            for( var a = 0; a < f; a += 3 ){
                for( var d = (e[a >>> 2] >>> 24 - 8 * (a % 4) & 255) << 16 | (e[a + 1 >>> 2] >>> 24 - 8 * ((a + 1) % 4) & 255) << 8 | e[a + 2 >>> 2] >>> 24 - 8 * ((a + 2) % 4) & 255, g = 0; 4 > g && a + 0.75 * g < f; g++ ){
                    b.push(c.charAt(d >>> 6 * (3 - g) & 63))
                }
            }
            if(e = c.charAt(64)){
                for( ; b.length % 4; ){
                    b.push(e)
                }
            }
            return b.join("")
        }, parse : function (b) {
            var e = b.length, f = this._map, c = f.charAt(64);
            c && (c = b.indexOf(c), -1 != c && (e = c));
            for( var c = [], a = 0, d = 0; d < e; d++ ){
                if(d % 4){
                    var g = f.indexOf(b.charAt(d - 1)) << 2 * (d % 4), h = f.indexOf(b.charAt(d)) >>> 6 - 2 * (d % 4);
                    c[a >>> 2] |= (g | h) << 24 - 8 * (a % 4);
                    a++
                }
            }
            return j.create(c, a)
        }, _map : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/="
    }
})();
var CryptoJS = CryptoJS || function (g, l) {
        var e = {}, d = e.lib = {}, m = function () {
        }, k                          = d.Base = {
            extend : function (a) {
                m.prototype = this;
                var c       = new m;
                a && c.mixIn(a);
                c.hasOwnProperty("init") || (c.init = function () {
                    c.$super.init.apply(this, arguments)
                });
                c.init.prototype = c;
                c.$super         = this;
                return c
            }, create : function () {
                var a = this.extend();
                a.init.apply(a, arguments);
                return a
            }, init : function () {
            }, mixIn : function (a) {
                for( var c in a ){
                    a.hasOwnProperty(c) && (this[c] = a[c])
                }
                a.hasOwnProperty("toString") && (this.toString = a.toString)
            }, clone : function () {
                return this.init.prototype.extend(this)
            }
        }, p = d.WordArray = k.extend({
            init : function (a, c) {
                a = this.words = a || [];
                this.sigBytes = c != l ? c : 4 * a.length
            }, toString : function (a) {
                return (a || n).stringify(this)
            }, concat : function (a) {
                var c = this.words, q = a.words, f = this.sigBytes;
                a     = a.sigBytes;
                this.clamp();
                if(f % 4){
                    for( var b = 0; b < a; b++ ){
                        c[f + b >>> 2] |= (q[b >>> 2] >>> 24 - 8 * (b % 4) & 255) << 24 - 8 * ((f + b) % 4)
                    }
                }else{
                    if(65535 < q.length){
                        for( b = 0; b < a; b += 4 ){
                            c[f + b >>> 2] = q[b >>> 2]
                        }
                    }else{
                        c.push.apply(c, q)
                    }
                }
                this.sigBytes += a;
                return this
            }, clamp : function () {
                var a    = this.words, c = this.sigBytes;
                a[c >>> 2] &= 4294967295 << 32 - 8 * (c % 4);
                a.length = g.ceil(c / 4)
            }, clone : function () {
                var a   = k.clone.call(this);
                a.words = this.words.slice(0);
                return a
            }, random : function (a) {
                for( var c = [], b = 0; b < a; b += 4 ){
                    c.push(4294967296 * g.random() | 0)
                }
                return new p.init(c, a)
            }
        }), b = e.enc = {}, n = b.Hex = {
            stringify : function (a) {
                var c = a.words;
                a     = a.sigBytes;
                for( var b = [], f = 0; f < a; f++ ){
                    var d = c[f >>> 2] >>> 24 - 8 * (f % 4) & 255;
                    b.push((d >>> 4).toString(16));
                    b.push((d & 15).toString(16))
                }
                return b.join("")
            }, parse : function (a) {
                for( var c = a.length, b = [], f = 0; f < c; f += 2 ){
                    b[f >>> 3] |= parseInt(a.substr(f, 2), 16) << 24 - 4 * (f % 8)
                }
                return new p.init(b, c / 2)
            }
        }, j = b.Latin1 = {
            stringify : function (a) {
                var c = a.words;
                a     = a.sigBytes;
                for( var b = [], f = 0; f < a; f++ ){
                    b.push(String.fromCharCode(c[f >>> 2] >>> 24 - 8 * (f % 4) & 255))
                }
                return b.join("")
            }, parse : function (a) {
                for( var c = a.length, b = [], f = 0; f < c; f++ ){
                    b[f >>> 2] |= (a.charCodeAt(f) & 255) << 24 - 8 * (f % 4)
                }
                return new p.init(b, c)
            }
        }, h = b.Utf8 = {
            stringify : function (a) {
                try{
                    return decodeURIComponent(escape(j.stringify(a)))
                }catch(c){
                    throw Error("Malformed UTF-8 data")
                }
            }, parse : function (a) {
                return j.parse(unescape(encodeURIComponent(a)))
            }
        }, r = d.BufferedBlockAlgorithm = k.extend({
            reset : function () {
                this._data       = new p.init;
                this._nDataBytes = 0
            }, _append : function (a) {
                "string" == typeof a && (a = h.parse(a));
                this._data.concat(a);
                this._nDataBytes += a.sigBytes
            }, _process : function (a) {
                var c = this._data, b = c.words, f = c.sigBytes, d = this.blockSize, e = f / (4 * d), e = a ? g.ceil(e) : g.max((e | 0) - this._minBufferSize, 0);
                a     = e * d;
                f     = g.min(4 * a, f);
                if(a){
                    for( var k = 0; k < a; k += d ){
                        this._doProcessBlock(b, k)
                    }
                    k = b.splice(0, a);
                    c.sigBytes -= f
                }
                return new p.init(k, f)
            }, clone : function () {
                var a   = k.clone.call(this);
                a._data = this._data.clone();
                return a
            }, _minBufferSize : 0
        });
        d.Hasher = r.extend({
            cfg : k.extend(), init : function (a) {
                this.cfg = this.cfg.extend(a);
                this.reset()
            }, reset : function () {
                r.reset.call(this);
                this._doReset()
            }, update : function (a) {
                this._append(a);
                this._process();
                return this
            }, finalize : function (a) {
                a && this._append(a);
                return this._doFinalize()
            }, blockSize : 16, _createHelper : function (a) {
                return function (b, d) {
                    return (new a.init(d)).finalize(b)
                }
            }, _createHmacHelper : function (a) {
                return function (b, d) {
                    return (new s.HMAC.init(a, d)).finalize(b)
                }
            }
        });
        var s    = e.algo = {};
        return e
    }(Math);
(function () {
    var g = CryptoJS, l = g.lib, e = l.WordArray, d = l.Hasher, m = [], l = g.algo.SHA1 = d.extend({
        _doReset : function () {
            this._hash = new e.init([1732584193, 4023233417, 2562383102, 271733878, 3285377520])
        }, _doProcessBlock : function (d, e) {
            for( var b = this._hash.words, n = b[0], j = b[1], h = b[2], g = b[3], l = b[4], a = 0; 80 > a; a++ ){
                if(16 > a){
                    m[a] = d[e + a] | 0
                }else{
                    var c = m[a - 3] ^ m[a - 8] ^ m[a - 14] ^ m[a - 16];
                    m[a]  = c << 1 | c >>> 31
                }
                c = (n << 5 | n >>> 27) + l + m[a];
                c = 20 > a ? c + ((j & h | ~j & g) + 1518500249) : 40 > a ? c + ((j ^ h ^ g) + 1859775393) : 60 > a ? c + ((j & h | j & g | h & g) - 1894007588) : c + ((j ^ h ^ g) - 899497514);
                l = g;
                g = h;
                h = j << 30 | j >>> 2;
                j = n;
                n = c
            }
            b[0] = b[0] + n | 0;
            b[1] = b[1] + j | 0;
            b[2] = b[2] + h | 0;
            b[3] = b[3] + g | 0;
            b[4] = b[4] + l | 0
        }, _doFinalize : function () {
            var d                       = this._data, e = d.words, b = 8 * this._nDataBytes, g = 8 * d.sigBytes;
            e[g >>> 5] |= 128 << 24 - g % 32;
            e[(g + 64 >>> 9 << 4) + 14] = Math.floor(b / 4294967296);
            e[(g + 64 >>> 9 << 4) + 15] = b;
            d.sigBytes                  = 4 * e.length;
            this._process();
            return this._hash
        }, clone : function () {
            var e   = d.clone.call(this);
            e._hash = this._hash.clone();
            return e
        }
    });
    g.SHA1     = d._createHelper(l);
    g.HmacSHA1 = d._createHmacHelper(l)
})();
(function () {
    var g       = CryptoJS, l = g.enc.Utf8;
    g.algo.HMAC = g.lib.Base.extend({
        init : function (e, d) {
            e = this._hasher = new e.init;
            "string" == typeof d && (d = l.parse(d));
            var g = e.blockSize, k = 4 * g;
            d.sigBytes > k && (d = e.finalize(d));
            d.clamp();
            for( var p = this._oKey = d.clone(), b = this._iKey = d.clone(), n = p.words, j = b.words, h = 0; h < g; h++ ){
                n[h] ^= 1549556828, j[h] ^= 909522486
            }
            p.sigBytes = b.sigBytes = k;
            this.reset()
        }, reset : function () {
            var e = this._hasher;
            e.reset();
            e.update(this._iKey)
        }, update : function (e) {
            this._hasher.update(e);
            return this
        }, finalize : function (e) {
            var d = this._hasher;
            e     = d.finalize(e);
            d.reset();
            return d.finalize(this._oKey.clone().concat(e))
        }
    })
})();
qcVideo("$", function () {
    var g_win = window;
    if(!!g_win.jQuery){
        return g_win.jQuery
    }
    (function (e, t) {
        function _(e) {
            var t = M[e] = {};
            return v.each(e.split(y), function (e, n) {
                t[n] = !0
            }), t
        }

        function H(e, n, r) {
            if(r === t && e.nodeType === 1){
                var i = "data-" + n.replace(P, "-$1").toLowerCase();
                r     = e.getAttribute(i);
                if(typeof r == "string"){
                    try{
                        r = r === "true" ? !0 : r === "false" ? !1 : r === "null" ? null : +r + "" === r ? +r : D.test(r) ? v.parseJSON(r) : r
                    }catch(s){
                    }
                    v.data(e, n, r)
                }else{
                    r = t
                }
            }
            return r
        }

        function B(e) {
            var t;
            for( t in e ){
                if(t === "data" && v.isEmptyObject(e[t])){
                    continue
                }
                if(t !== "toJSON"){
                    return !1
                }
            }
            return !0
        }

        function et() {
            return !1
        }

        function tt() {
            return !0
        }

        function ut(e) {
            return !e || (!e.parentNode || e.parentNode.nodeType === 11)
        }

        function at(e, t) {
            do{
                e = e[t]
            }while( e && e.nodeType !== 1 );
            return e
        }

        function ft(e, t, n) {
            t = t || 0;
            if(v.isFunction(t)){
                return v.grep(e, function (e, r) {
                    var i = !!t.call(e, r, e);
                    return i === n
                })
            }
            if(t.nodeType){
                return v.grep(e, function (e, r) {
                    return e === t === n
                })
            }
            if(typeof t == "string"){
                var r = v.grep(e, function (e) {
                    return e.nodeType === 1
                });
                if(it.test(t)){
                    return v.filter(t, r, !n)
                }
                t = v.filter(t, r)
            }
            return v.grep(e, function (e, r) {
                return v.inArray(e, t) >= 0 === n
            })
        }

        function lt(e) {
            var t = ct.split("|"), n = e.createDocumentFragment();
            if(n.createElement){
                while( t.length ){
                    n.createElement(t.pop())
                }
            }
            return n
        }

        function Lt(e, t) {
            return e.getElementsByTagName(t)[0] || e.appendChild(e.ownerDocument.createElement(t))
        }

        function At(e, t) {
            if(t.nodeType !== 1 || !v.hasData(e)){
                return
            }
            var n, r, i, s = v._data(e), o = v._data(t, s), u = s.events;
            if(u){
                delete o.handle, o.events = {};
                for( n in u ){
                    for( r = 0, i = u[n].length; r < i; r++ ){
                        v.event.add(t, n, u[n][r])
                    }
                }
            }
            o.data && (o.data = v.extend({}, o.data))
        }

        function Ot(e, t) {
            var n;
            if(t.nodeType !== 1){
                return
            }
            t.clearAttributes && t.clearAttributes(), t.mergeAttributes && t.mergeAttributes(e), n = t.nodeName.toLowerCase(), n === "object" ? (t.parentNode && (t.outerHTML = e.outerHTML), v.support.html5Clone && (e.innerHTML && (!v.trim(t.innerHTML) && (t.innerHTML = e.innerHTML)))) : n === "input" && Et.test(e.type) ? (t.defaultChecked = t.checked = e.checked, t.value !== e.value && (t.value = e.value)) : n === "option" ? t.selected = e.defaultSelected : n === "input" || n === "textarea" ? t.defaultValue = e.defaultValue : n === "script" && (t.text !== e.text && (t.text = e.text)), t.removeAttribute(v.expando)
        }

        function Mt(e) {
            return typeof e.getElementsByTagName != "undefined" ? e.getElementsByTagName("*") : typeof e.querySelectorAll != "undefined" ? e.querySelectorAll("*") : []
        }

        function _t(e) {
            Et.test(e.type) && (e.defaultChecked = e.checked)
        }

        function Qt(e, t) {
            if(t in e){
                return t
            }
            var n = t.charAt(0).toUpperCase() + t.slice(1), r = t, i = Jt.length;
            while( i-- ){
                t = Jt[i] + n;
                if(t in e){
                    return t
                }
            }
            return r
        }

        function Gt(e, t) {
            return e = t || e, v.css(e, "display") === "none" || !v.contains(e.ownerDocument, e)
        }

        function Yt(e, t) {
            var n, r, i = [], s = 0, o = e.length;
            for( ; s < o; s++ ){
                n = e[s];
                if(!n.style){
                    continue
                }
                i[s] = v._data(n, "olddisplay"), t ? (!i[s] && (n.style.display === "none" && (n.style.display = "")), n.style.display === "" && (Gt(n) && (i[s] = v._data(n, "olddisplay", nn(n.nodeName))))) : (r = Dt(n, "display"), !i[s] && (r !== "none" && v._data(n, "olddisplay", r)))
            }
            for( s = 0; s < o; s++ ){
                n = e[s];
                if(!n.style){
                    continue
                }
                if(!t || (n.style.display === "none" || n.style.display === "")){
                    n.style.display = t ? i[s] || "" : "none"
                }
            }
            return e
        }

        function Zt(e, t, n) {
            var r = Rt.exec(t);
            return r ? Math.max(0, r[1] - (n || 0)) + (r[2] || "px") : t
        }

        function en(e, t, n, r) {
            var i = n === (r ? "border" : "content") ? 4 : t === "width" ? 1 : 0, s = 0;
            for( ; i < 4; i += 2 ){
                n === "margin" && (s += v.css(e, n + $t[i], !0)), r ? (n === "content" && (s -= parseFloat(Dt(e, "padding" + $t[i])) || 0), n !== "margin" && (s -= parseFloat(Dt(e, "border" + $t[i] + "Width")) || 0)) : (s += parseFloat(Dt(e, "padding" + $t[i])) || 0, n !== "padding" && (s += parseFloat(Dt(e, "border" + $t[i] + "Width")) || 0))
            }
            return s
        }

        function tn(e, t, n) {
            var r = t === "width" ? e.offsetWidth : e.offsetHeight, i = !0, s = v.support.boxSizing && v.css(e, "boxSizing") === "border-box";
            if(r <= 0 || r == null){
                r = Dt(e, t);
                if(r < 0 || r == null){
                    r = e.style[t]
                }
                if(Ut.test(r)){
                    return r
                }
                i = s && (v.support.boxSizingReliable || r === e.style[t]), r = parseFloat(r) || 0
            }
            return r + en(e, t, n || (s ? "border" : "content"), i) + "px"
        }

        function nn(e) {
            if(Wt[e]){
                return Wt[e]
            }
            var t = v("<" + e + ">").appendTo(i.body), n = t.css("display");
            t.remove();
            if(n === "none" || n === ""){
                Pt = i.body.appendChild(Pt || v.extend(i.createElement("iframe"), {
                        frameBorder : 0,
                        width : 0,
                        height : 0
                    }));
                if(!Ht || !Pt.createElement){
                    Ht = (Pt.contentWindow || Pt.contentDocument).document, Ht.write("<!doctype html><html><body>"), Ht.close()
                }
                t = Ht.body.appendChild(Ht.createElement(e)), n = Dt(t, "display"), i.body.removeChild(Pt)
            }
            return Wt[e] = n, n
        }

        function fn(e, t, n, r) {
            var i;
            if(v.isArray(t)){
                v.each(t, function (t, i) {
                    n || sn.test(e) ? r(e, i) : fn(e + "[" + (typeof i == "object" ? t : "") + "]", i, n, r)
                })
            }else{
                if(!n && v.type(t) === "object"){
                    for( i in t ){
                        fn(e + "[" + i + "]", t[i], n, r)
                    }
                }else{
                    r(e, t)
                }
            }
        }

        function Cn(e) {
            return function (t, n) {
                typeof t != "string" && (n = t, t = "*");
                var r, i, s, o = t.toLowerCase().split(y), u = 0, a = o.length;
                if(v.isFunction(n)){
                    for( ; u < a; u++ ){
                        r = o[u], s = /^\+/.test(r), s && (r = r.substr(1) || "*"), i = e[r] = e[r] || [], i[s ? "unshift" : "push"](n)
                    }
                }
            }
        }

        function kn(e, n, r, i, s, o) {
            s = s || n.dataTypes[0], o = o || {}, o[s] = !0;
            var u, a = e[s], f = 0, l = a ? a.length : 0, c = e === Sn;
            for( ; f < l && (c || !u); f++ ){
                u = a[f](n, r, i), typeof u == "string" && (!c || o[u] ? u = t : (n.dataTypes.unshift(u), u = kn(e, n, r, i, u, o)))
            }
            return (c || !u) && (!o["*"] && (u = kn(e, n, r, i, "*", o))), u
        }

        function Ln(e, n) {
            var r, i, s = v.ajaxSettings.flatOptions || {};
            for( r in n ){
                n[r] !== t && ((s[r] ? e : i || (i = {}))[r] = n[r])
            }
            i && v.extend(!0, e, i)
        }

        function An(e, n, r) {
            var i, s, o, u, a = e.contents, f = e.dataTypes, l = e.responseFields;
            for( s in l ){
                s in r && (n[l[s]] = r[s])
            }
            while( f[0] === "*" ){
                f.shift(), i === t && (i = e.mimeType || n.getResponseHeader("content-type"))
            }
            if(i){
                for( s in a ){
                    if(a[s] && a[s].test(i)){
                        f.unshift(s);
                        break
                    }
                }
            }
            if(f[0] in r){
                o = f[0]
            }else{
                for( s in r ){
                    if(!f[0] || e.converters[s + " " + f[0]]){
                        o = s;
                        break
                    }
                    u || (u = s)
                }
                o = o || u
            }
            if(o){
                return o !== f[0] && f.unshift(o), r[o]
            }
        }

        function On(e, t) {
            var n, r, i, s, o = e.dataTypes.slice(), u = o[0], a = {}, f = 0;
            e.dataFilter && (t = e.dataFilter(t, e.dataType));
            if(o[1]){
                for( n in e.converters ){
                    a[n.toLowerCase()] = e.converters[n]
                }
            }
            for( ; i = o[++f]; ){
                if(i !== "*"){
                    if(u !== "*" && u !== i){
                        n = a[u + " " + i] || a["* " + i];
                        if(!n){
                            for( r in a ){
                                s = r.split(" ");
                                if(s[1] === i){
                                    n = a[u + " " + s[0]] || a["* " + s[0]];
                                    if(n){
                                        n === !0 ? n = a[r] : a[r] !== !0 && (i = s[0], o.splice(f--, 0, i));
                                        break
                                    }
                                }
                            }
                        }
                        if(n !== !0){
                            if(n && e["throws"]){
                                t = n(t)
                            }else{
                                try{
                                    t = n(t)
                                }catch(l){
                                    return {
                                        state : "parsererror",
                                        error : n ? l : "No conversion from " + u + " to " + i
                                    }
                                }
                            }
                        }
                    }
                    u = i
                }
            }
            return {state : "success", data : t}
        }

        function Fn() {
            try{
                return new e.XMLHttpRequest
            }catch(t){
            }
        }

        function In() {
            try{
                return new e.ActiveXObject("Microsoft.XMLHTTP")
            }catch(t){
            }
        }

        function $n() {
            return setTimeout(function () {
                qn = t
            }, 0), qn = v.now()
        }

        function Jn(e, t) {
            v.each(t, function (t, n) {
                var r = (Vn[t] || []).concat(Vn["*"]), i = 0, s = r.length;
                for( ; i < s; i++ ){
                    if(r[i].call(e, t, n)){
                        return
                    }
                }
            })
        }

        function Kn(e, t, n) {
            var r, i = 0, s = 0, o = Xn.length, u = v.Deferred().always(function () {
                delete a.elem
            }), a    = function () {
                var t = qn || $n(), n = Math.max(0, f.startTime + f.duration - t), r = n / f.duration || 0, i = 1 - r, s = 0, o = f.tweens.length;
                for( ; s < o; s++ ){
                    f.tweens[s].run(i)
                }
                return u.notifyWith(e, [f, i, n]), i < 1 && o ? n : (u.resolveWith(e, [f]), !1)
            }, f     = u.promise({
                elem : e,
                props : v.extend({}, t),
                opts : v.extend(!0, {specialEasing : {}}, n),
                originalProperties : t,
                originalOptions : n,
                startTime : qn || $n(),
                duration : n.duration,
                tweens : [],
                createTween : function (t, n, r) {
                    var i = v.Tween(e, f.opts, t, n, f.opts.specialEasing[t] || f.opts.easing);
                    return f.tweens.push(i), i
                },
                stop : function (t) {
                    var n = 0, r = t ? f.tweens.length : 0;
                    for( ; n < r; n++ ){
                        f.tweens[n].run(1)
                    }
                    return t ? u.resolveWith(e, [f, t]) : u.rejectWith(e, [f, t]), this
                }
            }), l    = f.props;
            Qn(l, f.opts.specialEasing);
            for( ; i < o; i++ ){
                r = Xn[i].call(f, e, l, f.opts);
                if(r){
                    return r
                }
            }
            return Jn(f, l), v.isFunction(f.opts.start) && f.opts.start.call(e, f), v.fx.timer(v.extend(a, {
                anim : f,
                queue : f.opts.queue,
                elem : e
            })), f.progress(f.opts.progress).done(f.opts.done, f.opts.complete).fail(f.opts.fail).always(f.opts.always)
        }

        function Qn(e, t) {
            var n, r, i, s, o;
            for( n in e ){
                r = v.camelCase(n), i = t[r], s = e[n], v.isArray(s) && (i = s[1], s = e[n] = s[0]), n !== r && (e[r] = s, delete e[n]), o = v.cssHooks[r];
                if(o && "expand" in o){
                    s = o.expand(s), delete e[r];
                    for( n in s ){
                        n in e || (e[n] = s[n], t[n] = i)
                    }
                }else{
                    t[r] = i
                }
            }
        }

        function Gn(e, t, n) {
            var r, i, s, o, u, a, f, l, c, h = this, p = e.style, d = {}, m = [], g = e.nodeType && Gt(e);
            n.queue || (l = v._queueHooks(e, "fx"), l.unqueued == null && (l.unqueued = 0, c = l.empty.fire, l.empty.fire = function () {
                l.unqueued || c()
            }), l.unqueued++, h.always(function () {
                h.always(function () {
                    l.unqueued--, v.queue(e, "fx").length || l.empty.fire()
                })
            })), e.nodeType === 1 && (("height" in t || "width" in t) && (n.overflow = [p.overflow, p.overflowX, p.overflowY], v.css(e, "display") === "inline" && (v.css(e, "float") === "none" && (!v.support.inlineBlockNeedsLayout || nn(e.nodeName) === "inline" ? p.display = "inline-block" : p.zoom = 1)))), n.overflow && (p.overflow = "hidden", v.support.shrinkWrapBlocks || h.done(function () {
                p.overflow = n.overflow[0], p.overflowX = n.overflow[1], p.overflowY = n.overflow[2]
            }));
            for( r in t ){
                s = t[r];
                if(Un.exec(s)){
                    delete t[r], a = a || s === "toggle";
                    if(s === (g ? "hide" : "show")){
                        continue
                    }
                    m.push(r)
                }
            }
            o = m.length;
            if(o){
                u = v._data(e, "fxshow") || v._data(e, "fxshow", {}), "hidden" in u && (g = u.hidden), a && (u.hidden = !g), g ? v(e).show() : h.done(function () {
                        v(e).hide()
                    }), h.done(function () {
                    var t;
                    v.removeData(e, "fxshow", !0);
                    for( t in d ){
                        v.style(e, t, d[t])
                    }
                });
                for( r = 0; r < o; r++ ){
                    i = m[r], f = h.createTween(i, g ? u[i] : 0), d[i] = u[i] || v.style(e, i), i in u || (u[i] = f.start, g && (f.end = f.start, f.start = i === "width" || i === "height" ? 1 : 0))
                }
            }
        }

        function Yn(e, t, n, r, i) {
            return new Yn.prototype.init(e, t, n, r, i)
        }

        function Zn(e, t) {
            var n, r = {height : e}, i = 0;
            t        = t ? 1 : 0;
            for( ; i < 4; i += 2 - t ){
                n = $t[i], r["margin" + n] = r["padding" + n] = e
            }
            return t && (r.opacity = r.width = e), r
        }

        function tr(e) {
            return v.isWindow(e) ? e : e.nodeType === 9 ? e.defaultView || e.parentWindow : !1
        }

        var n, r, i = e.document, s = e.location, o = e.navigator, u = e.jQuery, a = e.$, f = Array.prototype.push, l = Array.prototype.slice, c = Array.prototype.indexOf, h = Object.prototype.toString, p = Object.prototype.hasOwnProperty, d = String.prototype.trim, v = function (e, t) {
            return new v.fn.init(e, t, n)
        }, m        = /[\-+]?(?:\d*\.|)\d+(?:[eE][\-+]?\d+|)/.source, g = /\S/, y = /\s+/, b = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, w = /^(?:[^#<]*(<[\w\W]+>)[^>]*$|#([\w\-]*)$)/, E = /^<(\w+)\s*\/?>(?:<\/\1>|)$/, S = /^[\],:{}\s]*$/, x = /(?:^|:|,)(?:\s*\[)+/g, T = /\\(?:["\\\/bfnrt]|u[\da-fA-F]{4})/g, N = /"[^"\\\r\n]*"|true|false|null|-?(?:\d\d*\.|)\d+(?:[eE][\-+]?\d+|)/g, C = /^-ms-/, k = /-([\da-z])/gi, L = function (e, t) {
            return (t + "").toUpperCase()
        }, A        = function () {
            i.addEventListener ? (i.removeEventListener("DOMContentLoaded", A, !1), v.ready()) : i.readyState === "complete" && (i.detachEvent("onreadystatechange", A), v.ready())
        }, O        = {};
        v.fn = v.prototype = {
            constructor : v, init : function (e, n, r) {
                var s, o, u, a;
                if(!e){
                    return this
                }
                if(e.nodeType){
                    return this.context = this[0] = e, this.length = 1, this
                }
                if(typeof e == "string"){
                    e.charAt(0) === "<" && (e.charAt(e.length - 1) === ">" && e.length >= 3) ? s = [null, e, null] : s = w.exec(e);
                    if(s && (s[1] || !n)){
                        if(s[1]){
                            return n = n instanceof v ? n[0] : n, a = n && n.nodeType ? n.ownerDocument || n : i, e = v.parseHTML(s[1], a, !0), E.test(s[1]) && (v.isPlainObject(n) && this.attr.call(e, n, !0)), v.merge(this, e)
                        }
                        o = i.getElementById(s[2]);
                        if(o && o.parentNode){
                            if(o.id !== s[2]){
                                return r.find(e)
                            }
                            this.length = 1, this[0] = o
                        }
                        return this.context = i, this.selector = e, this
                    }
                    return !n || n.jquery ? (n || r).find(e) : this.constructor(n).find(e)
                }
                return v.isFunction(e) ? r.ready(e) : (e.selector !== t && (this.selector = e.selector, this.context = e.context), v.makeArray(e, this))
            }, selector : "", jquery : "1.8.3", length : 0, size : function () {
                return this.length
            }, toArray : function () {
                return l.call(this)
            }, get : function (e) {
                return e == null ? this.toArray() : e < 0 ? this[this.length + e] : this[e]
            }, pushStack : function (e, t, n) {
                var r = v.merge(this.constructor(), e);
                return r.prevObject = this, r.context = this.context, t === "find" ? r.selector = this.selector + (this.selector ? " " : "") + n : t && (r.selector = this.selector + "." + t + "(" + n + ")"), r
            }, each : function (e, t) {
                return v.each(this, e, t)
            }, ready : function (e) {
                return v.ready.promise().done(e), this
            }, eq : function (e) {
                return e = +e, e === -1 ? this.slice(e) : this.slice(e, e + 1)
            }, first : function () {
                return this.eq(0)
            }, last : function () {
                return this.eq(-1)
            }, slice : function () {
                return this.pushStack(l.apply(this, arguments), "slice", l.call(arguments).join(","))
            }, map : function (e) {
                return this.pushStack(v.map(this, function (t, n) {
                    return e.call(t, n, t)
                }))
            }, end : function () {
                return this.prevObject || this.constructor(null)
            }, push : f, sort : [].sort, splice : [].splice
        }, v.fn.init.prototype = v.fn, v.extend = v.fn.extend = function () {
            var e, n, r, i, s, o, u = arguments[0] || {}, a = 1, f = arguments.length, l = !1;
            typeof u == "boolean" && (l = u, u = arguments[1] || {}, a = 2), typeof u != "object" && (!v.isFunction(u) && (u = {})), f === a && (u = this, --a);
            for( ; a < f; a++ ){
                if((e = arguments[a]) != null){
                    for( n in e ){
                        r = u[n], i = e[n];
                        if(u === i){
                            continue
                        }
                        l && (i && (v.isPlainObject(i) || (s = v.isArray(i)))) ? (s ? (s = !1, o = r && v.isArray(r) ? r : []) : o= r && v.isPlainObject(r) ? r : {}, u[n] = v.extend(l, o, i)) : i !== t && (u[n] = i)
                    }
                }
            }
            return u
        }, v.extend({
            noConflict : function (t) {
                return e.$ === v && (e.$ = a), t && (e.jQuery === v && (e.jQuery = u)), v
            }, isReady : !1, readyWait : 1, holdReady : function (e) {
                e ? v.readyWait++ : v.ready(!0)
            }, ready : function (e) {
                if(e === !0 ? --v.readyWait : v.isReady){
                    return
                }
                if(!i.body){
                    return setTimeout(v.ready, 1)
                }
                v.isReady = !0;
                if(e !== !0 && --v.readyWait > 0){
                    return
                }
                r.resolveWith(i, [v]), v.fn.trigger && v(i).trigger("ready").off("ready")
            }, isFunction : function (e) {
                return v.type(e) === "function"
            }, isArray : Array.isArray || function (e) {
                return v.type(e) === "array"
            }, isWindow : function (e) {
                return e != null && e == e.window
            }, isNumeric : function (e) {
                return !isNaN(parseFloat(e)) && isFinite(e)
            }, type : function (e) {
                return e == null ? String(e) : O[h.call(e)] || "object"
            }, isPlainObject : function (e) {
                if(!e || (v.type(e) !== "object" || (e.nodeType || v.isWindow(e)))){
                    return !1
                }
                try{
                    if(e.constructor && (!p.call(e, "constructor") && !p.call(e.constructor.prototype, "isPrototypeOf"))){
                        return !1
                    }
                }catch(n){
                    return !1
                }
                var r;
                for( r in e ){
                }
                return r === t || p.call(e, r)
            }, isEmptyObject : function (e) {
                var t;
                for( t in e ){
                    return !1
                }
                return !0
            }, error : function (e) {
                throw new Error(e)
            }, parseHTML : function (e, t, n) {
                var r;
                return !e || typeof e != "string" ? null : (typeof t == "boolean" && (n = t, t = 0), t = t || i, (r = E.exec(e)) ? [t.createElement(r[1])] : (r = v.buildFragment([e], t, n ? null : []), v.merge([], (r.cacheable ? v.clone(r.fragment) : r.fragment).childNodes)))
            }, parseJSON : function (t) {
                if(!t || typeof t != "string"){
                    return null
                }
                t = v.trim(t);
                if(e.JSON && e.JSON.parse){
                    return e.JSON.parse(t)
                }
                if(S.test(t.replace(T, "@").replace(N, "]").replace(x, ""))){
                    return (new Function("return " + t))()
                }
                v.error("Invalid JSON: " + t)
            }, parseXML : function (n) {
                var r, i;
                if(!n || typeof n != "string"){
                    return null
                }
                try{
                    e.DOMParser ? (i = new DOMParser, r = i.parseFromString(n, "text/xml")) : (r = new ActiveXObject("Microsoft.XMLDOM"), r.async = "false", r.loadXML(n))
                }catch(s){
                    r = t
                }
                return (!r || (!r.documentElement || r.getElementsByTagName("parsererror").length)) && v.error("Invalid XML: " + n), r
            }, noop : function () {
            }, globalEval : function (t) {
                t && (g.test(t) && (e.execScript || function (t) {
                    e.eval.call(e, t)
                })(t))
            }, camelCase : function (e) {
                return e.replace(C, "ms-").replace(k, L)
            }, nodeName : function (e, t) {
                return e.nodeName && e.nodeName.toLowerCase() === t.toLowerCase()
            }, each : function (e, n, r) {
                var i, s = 0, o = e.length, u = o === t || v.isFunction(e);
                if(r){
                    if(u){
                        for( i in e ){
                            if(n.apply(e[i], r) === !1){
                                break
                            }
                        }
                    }else{
                        for( ; s < o; ){
                            if(n.apply(e[s++], r) === !1){
                                break
                            }
                        }
                    }
                }else{
                    if(u){
                        for( i in e ){
                            if(n.call(e[i], i, e[i]) === !1){
                                break
                            }
                        }
                    }else{
                        for( ; s < o; ){
                            if(n.call(e[s], s, e[s++]) === !1){
                                break
                            }
                        }
                    }
                }
                return e
            }, trim : d && !d.call("\ufeff\u00a0") ? function (e) {
                    return e == null ? "" : d.call(e)
                } : function (e) {
                    return e == null ? "" : (e + "").replace(b, "")
                }, makeArray : function (e, t) {
                var n, r = t || [];
                return e != null && (n = v.type(e), e.length == null || (n === "string" || (n === "function" || (n === "regexp" || v.isWindow(e)))) ? f.call(r, e) : v.merge(r, e)), r
            }, inArray : function (e, t, n) {
                var r;
                if(t){
                    if(c){
                        return c.call(t, e, n)
                    }
                    r = t.length, n = n ? n < 0 ? Math.max(0, r + n) : n : 0;
                    for( ; n < r; n++ ){
                        if(n in t && t[n] === e){
                            return n
                        }
                    }
                }
                return -1
            }, merge : function (e, n) {
                var r = n.length, i = e.length, s = 0;
                if(typeof r == "number"){
                    for( ; s < r; s++ ){
                        e[i++] = n[s]
                    }
                }else{
                    while( n[s] !== t ){
                        e[i++] = n[s++]
                    }
                }
                return e.length = i, e
            }, grep : function (e, t, n) {
                var r, i = [], s = 0, o = e.length;
                n        = !!n;
                for( ; s < o; s++ ){
                    r = !!t(e[s], s), n !== r && i.push(e[s])
                }
                return i
            }, map : function (e, n, r) {
                var i, s, o = [], u = 0, a = e.length, f = e instanceof v || a !== t && (typeof a == "number" && (a > 0 && (e[0] && e[a - 1]) || (a === 0 || v.isArray(e))));
                if(f){
                    for( ; u < a; u++ ){
                        i = n(e[u], u, r), i != null && (o[o.length] = i)
                    }
                }else{
                    for( s in e ){
                        i = n(e[s], s, r), i != null && (o[o.length] = i)
                    }
                }
                return o.concat.apply([], o)
            }, guid : 1, proxy : function (e, n) {
                var r, i, s;
                return typeof n == "string" && (r = e[n], n = e, e = r), v.isFunction(e) ? (i = l.call(arguments, 2), s = function () {
                        return e.apply(n, i.concat(l.call(arguments)))
                    }, s.guid = e.guid = e.guid || v.guid++, s) : t
            }, access : function (e, n, r, i, s, o, u) {
                var a, f = r == null, l = 0, c = e.length;
                if(r && typeof r == "object"){
                    for( l in r ){
                        v.access(e, n, l, r[l], 1, o, i)
                    }
                    s = 1
                }else{
                    if(i !== t){
                        a = u === t && v.isFunction(i), f && (a ? (a = n, n = function (e, t, n) {
                                return a.call(v(e), n)
                            }) : (n.call(e, i), n = null));
                        if(n){
                            for( ; l < c; l++ ){
                                n(e[l], r, a ? i.call(e[l], l, n(e[l], r)) : i, u)
                            }
                        }
                        s = 1
                    }
                }
                return s ? e : f ? n.call(e) : c ? n(e[0], r) : o
            }, now : function () {
                return (new Date).getTime()
            }
        }), v.ready.promise = function (t) {
            if(!r){
                r = v.Deferred();
                if(i.readyState === "complete"){
                    setTimeout(v.ready, 1)
                }else{
                    if(i.addEventListener){
                        i.addEventListener("DOMContentLoaded", A, !1), e.addEventListener("load", v.ready, !1)
                    }else{
                        i.attachEvent("onreadystatechange", A), e.attachEvent("onload", v.ready);
                        var n = !1;
                        try{
                            n = e.frameElement == null && i.documentElement
                        }catch(s){
                        }
                        n && (n.doScroll && function o() {
                            if(!v.isReady){
                                try{
                                    n.doScroll("left")
                                }catch(e){
                                    return setTimeout(o, 50)
                                }
                                v.ready()
                            }
                        }())
                    }
                }
            }
            return r.promise(t)
        }, v.each("Boolean Number String Function Array Date RegExp Object".split(" "), function (e, t) {
            O["[object " + t + "]"] = t.toLowerCase()
        }), n = v(i);
        var M = {};
        v.Callbacks = function (e) {
            e                       = typeof e == "string" ? M[e] || _(e) : v.extend({}, e);
            var n, r, i, s, o, u, a = [], f = !e.once && [], l = function (t) {
                n = e.memory && t, r = !0, u = s || 0, s = 0, o = a.length, i = !0;
                for( ; a && u < o; u++ ){
                    if(a[u].apply(t[0], t[1]) === !1 && e.stopOnFalse){
                        n = !1;
                        break
                    }
                }
                i = !1, a && (f ? f.length && l(f.shift()) : n ? a = [] : c.disable())
            }, c                    = {
                add : function () {
                    if(a){
                        var t = a.length;
                        (function r(t) {
                            v.each(t, function (t, n) {
                                var i = v.type(n);
                                i === "function" ? (!e.unique || !c.has(n)) && a.push(n) : n && (n.length && (i !== "string" && r(n)))
                            })
                        })(arguments), i ? o = a.length : n && (s = t, l(n))
                    }
                    return this
                }, remove : function () {
                    return a && v.each(arguments, function (e, t) {
                        var n;
                        while( (n = v.inArray(t, a, n)) > -1 ){
                            a.splice(n, 1), i && (n <= o && o--, n <= u && u--)
                        }
                    }), this
                }, has : function (e) {
                    return v.inArray(e, a) > -1
                }, empty : function () {
                    return a = [], this
                }, disable : function () {
                    return a = f = n = t, this
                }, disabled : function () {
                    return !a
                }, lock : function () {
                    return f = t, n || c.disable(), this
                }, locked : function () {
                    return !f
                }, fireWith : function (e, t) {
                    return t = t || [], t = [e, t.slice ? t.slice() : t], a && ((!r || f) && (i ? f.push(t) : l(t))), this
                }, fire : function () {
                    return c.fireWith(this, arguments), this
                }, fired : function () {
                    return !!r
                }
            };
            return c
        }, v.extend({
            Deferred : function (e) {
                var t = [["resolve", "done", v.Callbacks("once memory"), "resolved"], ["reject", "fail", v.Callbacks("once memory"), "rejected"], ["notify", "progress", v.Callbacks("memory")]], n = "pending", r = {
                    state : function () {
                        return n
                    }, always : function () {
                        return i.done(arguments).fail(arguments), this
                    }, then : function () {
                        var e = arguments;
                        return v.Deferred(function (n) {
                            v.each(t, function (t, r) {
                                var s = r[0], o = e[t];
                                i[r[1]](v.isFunction(o) ? function () {
                                        var e = o.apply(this, arguments);
                                        e && v.isFunction(e.promise) ? e.promise().done(n.resolve).fail(n.reject).progress(n.notify) : n[s + "With"](this === i ? n : this, [e])
                                    } : n[s])
                            }), e = null
                        }).promise()
                    }, promise : function (e) {
                        return e != null ? v.extend(e, r) : r
                    }
                }, i  = {};
                return r.pipe = r.then, v.each(t, function (e, s) {
                    var o = s[2], u = s[3];
                    r[s[1]] = o.add, u && o.add(function () {
                        n = u
                    }, t[e ^ 1][2].disable, t[2][2].lock), i[s[0]] = o.fire, i[s[0] + "With"] = o.fireWith
                }), r.promise(i), e && e.call(i, i), i
            }, when : function (e) {
                var t = 0, n = l.call(arguments), r = n.length, i = r !== 1 || e && v.isFunction(e.promise) ? r : 0, s = i === 1 ? e : v.Deferred(), o = function (e, t, n) {
                    return function (r) {
                        t[e] = this, n[e] = arguments.length > 1 ? l.call(arguments) : r, n === u ? s.notifyWith(t, n) : --i || s.resolveWith(t, n)
                    }
                }, u, a, f;
                if(r > 1){
                    u = new Array(r), a = new Array(r), f = new Array(r);
                    for( ; t < r; t++ ){
                        n[t] && v.isFunction(n[t].promise) ? n[t].promise().done(o(t, f, n)).fail(s.reject).progress(o(t, a, u)) : --i
                    }
                }
                return i || s.resolveWith(f, n), s.promise()
            }
        }), v.support = function () {
            var t, n, r, s, o, u, a, f, l, c, h, p = i.createElement("div");
            p.setAttribute("className", "t"), p.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", n = p.getElementsByTagName("*"), r = p.getElementsByTagName("a")[0];
            if(!n || (!r || !n.length)){
                return {}
            }
            s = i.createElement("select"), o = s.appendChild(i.createElement("option")), u = p.getElementsByTagName("input")[0], r.style.cssText = "top:1px;float:left;opacity:.5", t = {
                leadingWhitespace : p.firstChild.nodeType === 3,
                tbody : !p.getElementsByTagName("tbody").length,
                htmlSerialize : !!p.getElementsByTagName("link").length,
                style : /top/.test(r.getAttribute("style")),
                hrefNormalized : r.getAttribute("href") === "/a",
                opacity : /^0.5/.test(r.style.opacity),
                cssFloat : !!r.style.cssFloat,
                checkOn : u.value === "on",
                optSelected : o.selected,
                getSetAttribute : p.className !== "t",
                enctype : !!i.createElement("form").enctype,
                html5Clone : i.createElement("nav").cloneNode(!0).outerHTML !== "<:nav></:nav>",
                boxModel : i.compatMode === "CSS1Compat",
                submitBubbles : !0,
                changeBubbles : !0,
                focusinBubbles : !1,
                deleteExpando : !0,
                noCloneEvent : !0,
                inlineBlockNeedsLayout : !1,
                shrinkWrapBlocks : !1,
                reliableMarginRight : !0,
                boxSizingReliable : !0,
                pixelPosition : !1
            }, u.checked = !0, t.noCloneChecked = u.cloneNode(!0).checked, s.disabled = !0, t.optDisabled = !o.disabled;
            try{
                delete p.test
            }catch(d){
                t.deleteExpando = !1
            }
            !p.addEventListener && (p.attachEvent && (p.fireEvent && (p.attachEvent("onclick", h = function () {
                t.noCloneEvent = !1
            }), p.cloneNode(!0).fireEvent("onclick"), p.detachEvent("onclick", h)))), u = i.createElement("input"), u.value = "t", u.setAttribute("type", "radio"), t.radioValue = u.value === "t", u.setAttribute("checked", "checked"), u.setAttribute("name", "t"), p.appendChild(u), a = i.createDocumentFragment(), a.appendChild(p.lastChild), t.checkClone = a.cloneNode(!0).cloneNode(!0).lastChild.checked, t.appendChecked = u.checked, a.removeChild(u), a.appendChild(p);
            if(p.attachEvent){
                for( l in {submit : !0, change : !0, focusin : !0} ){
                    f = "on" + l, c = f in p, c || (p.setAttribute(f, "return;"), c = typeof p[f] == "function"), t[l + "Bubbles"] = c
                }
            }
            return v(function () {
                var n, r, s, o, u = "padding:0;margin:0;border:0;display:block;overflow:hidden;", a = i.getElementsByTagName("body")[0];
                if(!a){
                    return
                }
                n = i.createElement("div"), n.style.cssText = "visibility:hidden;border:0;width:0;height:0;position:static;top:0;margin-top:1px", a.insertBefore(n, a.firstChild), r = i.createElement("div"), n.appendChild(r), r.innerHTML = "<table><tr><td></td><td>t</td></tr></table>", s = r.getElementsByTagName("td"), s[0].style.cssText = "padding:0;margin:0;border:0;display:none", c = s[0].offsetHeight === 0, s[0].style.display = "", s[1].style.display = "none", t.reliableHiddenOffsets = c && s[0].offsetHeight === 0, r.innerHTML = "", r.style.cssText = "box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;padding:1px;border:1px;display:block;width:4px;margin-top:1%;position:absolute;top:1%;", t.boxSizing = r.offsetWidth === 4, t.doesNotIncludeMarginInBodyOffset = a.offsetTop !== 1, e.getComputedStyle && (t.pixelPosition = (e.getComputedStyle(r, null) || {}).top !== "1%", t.boxSizingReliable = (e.getComputedStyle(r, null) || {width : "4px"}).width === "4px", o = i.createElement("div"), o.style.cssText = r.style.cssText = u, o.style.marginRight = o.style.width = "0", r.style.width = "1px", r.appendChild(o), t.reliableMarginRight = !parseFloat((e.getComputedStyle(o, null) || {}).marginRight)), typeof r.style.zoom != "undefined" && (r.innerHTML = "", r.style.cssText = u + "width:1px;padding:1px;display:inline;zoom:1", t.inlineBlockNeedsLayout = r.offsetWidth === 3, r.style.display = "block", r.style.overflow = "visible", r.innerHTML = "<div></div>", r.firstChild.style.width = "5px", t.shrinkWrapBlocks = r.offsetWidth !== 3, n.style.zoom = 1), a.removeChild(n), n = r = s = o = null
            }), a.removeChild(p), n = r = s = o = u = a = p = null, t
        }();
        var D = /(?:\{[\s\S]*\}|\[[\s\S]*\])$/, P = /([A-Z])/g;
        v.extend({
            cache : {},
            deletedIds : [],
            uuid : 0,
            expando : "jQuery" + (v.fn.jquery + Math.random()).replace(/\D/g, ""),
            noData : {embed : !0, object : "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000", applet : !0},
            hasData : function (e) {
                return e = e.nodeType ? v.cache[e[v.expando]] : e[v.expando], !!e && !B(e)
            },
            data : function (e, n, r, i) {
                if(!v.acceptData(e)){
                    return
                }
                var s, o, u = v.expando, a = typeof n == "string", f = e.nodeType, l = f ? v.cache : e, c = f ? e[u] : e[u] && u;
                if((!c || (!l[c] || !i && !l[c].data)) && (a && r === t)){
                    return
                }
                c || (f ? e[u] = c = v.deletedIds.pop() || v.guid++ : c = u), l[c] || (l[c] = {}, f || (l[c].toJSON = v.noop));
                if(typeof n == "object" || typeof n == "function"){
                    i ? l[c] = v.extend(l[c], n) : l[c].data = v.extend(l[c].data, n)
                }
                return s = l[c], i || (s.data || (s.data = {}), s = s.data), r !== t && (s[v.camelCase(n)] = r), a ? (o = s[n], o == null && (o = s[v.camelCase(n)])) : o = s, o
            },
            removeData : function (e, t, n) {
                if(!v.acceptData(e)){
                    return
                }
                var r, i, s, o = e.nodeType, u = o ? v.cache : e, a = o ? e[v.expando] : v.expando;
                if(!u[a]){
                    return
                }
                if(t){
                    r = n ? u[a] : u[a].data;
                    if(r){
                        v.isArray(t) || (t in r ? t = [t] : (t = v.camelCase(t), t in r ? t = [t] : t = t.split(" ")));
                        for( i = 0, s = t.length; i < s; i++ ){
                            delete r[t[i]]
                        }
                        if(!(n ? B : v.isEmptyObject)(r)){
                            return
                        }
                    }
                }
                if(!n){
                    delete u[a].data;
                    if(!B(u[a])){
                        return
                    }
                }
                o ? v.cleanData([e], !0) : v.support.deleteExpando || u != u.window ? delete u[a] : u[a] = null
            },
            _data : function (e, t, n) {
                return v.data(e, t, n, !0)
            },
            acceptData : function (e) {
                var t = e.nodeName && v.noData[e.nodeName.toLowerCase()];
                return !t || t !== !0 && e.getAttribute("classid") === t
            }
        }), v.fn.extend({
            data : function (e, n) {
                var r, i, s, o, u, a = this[0], f = 0, l = null;
                if(e === t){
                    if(this.length){
                        l = v.data(a);
                        if(a.nodeType === 1 && !v._data(a, "parsedAttrs")){
                            s = a.attributes;
                            for( u = s.length; f < u; f++ ){
                                o = s[f].name, o.indexOf("data-") || (o = v.camelCase(o.substring(5)), H(a, o, l[o]))
                            }
                            v._data(a, "parsedAttrs", !0)
                        }
                    }
                    return l
                }
                return typeof e == "object" ? this.each(function () {
                        v.data(this, e)
                    }) : (r = e.split(".", 2), r[1] = r[1] ? "." + r[1] : "", i = r[1] + "!", v.access(this, function (n) {
                        if(n === t){
                            return l = this.triggerHandler("getData" + i, [r[0]]), l === t && (a && (l = v.data(a, e), l = H(a, e, l))), l === t && r[1] ? this.data(r[0]) : l
                        }
                        r[1] = n, this.each(function () {
                            var t = v(this);
                            t.triggerHandler("setData" + i, r), v.data(this, e, n), t.triggerHandler("changeData" + i, r)
                        })
                    }, null, n, arguments.length > 1, null, !1))
            }, removeData : function (e) {
                return this.each(function () {
                    v.removeData(this, e)
                })
            }
        }), v.extend({
            queue : function (e, t, n) {
                var r;
                if(e){
                    return t = (t || "fx") + "queue", r = v._data(e, t), n && (!r || v.isArray(n) ? r = v._data(e, t, v.makeArray(n)) : r.push(n)), r || []
                }
            }, dequeue : function (e, t) {
                t     = t || "fx";
                var n = v.queue(e, t), r = n.length, i = n.shift(), s = v._queueHooks(e, t), o = function () {
                    v.dequeue(e, t)
                };
                i === "inprogress" && (i = n.shift(), r--), i && (t === "fx" && n.unshift("inprogress"), delete s.stop, i.call(e, o, s)), !r && (s && s.empty.fire())
            }, _queueHooks : function (e, t) {
                var n = t + "queueHooks";
                return v._data(e, n) || v._data(e, n, {
                        empty : v.Callbacks("once memory").add(function () {
                            v.removeData(e, t + "queue", !0), v.removeData(e, n, !0)
                        })
                    })
            }
        }), v.fn.extend({
            queue : function (e, n) {
                var r = 2;
                return typeof e != "string" && (n = e, e = "fx", r--), arguments.length < r ? v.queue(this[0], e) : n === t ? this : this.each(function () {
                            var t = v.queue(this, e, n);
                            v._queueHooks(this, e), e === "fx" && (t[0] !== "inprogress" && v.dequeue(this, e))
                        })
            }, dequeue : function (e) {
                return this.each(function () {
                    v.dequeue(this, e)
                })
            }, delay : function (e, t) {
                return e = v.fx ? v.fx.speeds[e] || e : e, t = t || "fx", this.queue(t, function (t, n) {
                    var r  = setTimeout(t, e);
                    n.stop = function () {
                        clearTimeout(r)
                    }
                })
            }, clearQueue : function (e) {
                return this.queue(e || "fx", [])
            }, promise : function (e, n) {
                var r, i = 1, s = v.Deferred(), o = this, u = this.length, a = function () {
                    --i || s.resolveWith(o, [o])
                };
                typeof e != "string" && (n = e, e = t), e = e || "fx";
                while( u-- ){
                    r = v._data(o[u], e + "queueHooks"), r && (r.empty && (i++, r.empty.add(a)))
                }
                return a(), s.promise(n)
            }
        });
        var j, F, I, q = /[\t\r\n]/g, R = /\r/g, U = /^(?:button|input)$/i, z = /^(?:button|input|object|select|textarea)$/i, W = /^a(?:rea|)$/i, X = /^(?:autofocus|autoplay|async|checked|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped|selected)$/i, V = v.support.getSetAttribute;
        v.fn.extend({
            attr : function (e, t) {
                return v.access(this, v.attr, e, t, arguments.length > 1)
            }, removeAttr : function (e) {
                return this.each(function () {
                    v.removeAttr(this, e)
                })
            }, prop : function (e, t) {
                return v.access(this, v.prop, e, t, arguments.length > 1)
            }, removeProp : function (e) {
                return e = v.propFix[e] || e, this.each(function () {
                    try{
                        this[e] = t, delete this[e]
                    }catch(n){
                    }
                })
            }, addClass : function (e) {
                var t, n, r, i, s, o, u;
                if(v.isFunction(e)){
                    return this.each(function (t) {
                        v(this).addClass(e.call(this, t, this.className))
                    })
                }
                if(e && typeof e == "string"){
                    t = e.split(y);
                    for( n = 0, r = this.length; n < r; n++ ){
                        i = this[n];
                        if(i.nodeType === 1){
                            if(!i.className && t.length === 1){
                                i.className = e
                            }else{
                                s = " " + i.className + " ";
                                for( o = 0, u = t.length; o < u; o++ ){
                                    s.indexOf(" " + t[o] + " ") < 0 && (s += t[o] + " ")
                                }
                                i.className = v.trim(s)
                            }
                        }
                    }
                }
                return this
            }, removeClass : function (e) {
                var n, r, i, s, o, u, a;
                if(v.isFunction(e)){
                    return this.each(function (t) {
                        v(this).removeClass(e.call(this, t, this.className))
                    })
                }
                if(e && typeof e == "string" || e === t){
                    n = (e || "").split(y);
                    for( u = 0, a = this.length; u < a; u++ ){
                        i = this[u];
                        if(i.nodeType === 1 && i.className){
                            r = (" " + i.className + " ").replace(q, " ");
                            for( s = 0, o = n.length; s < o; s++ ){
                                while( r.indexOf(" " + n[s] + " ") >= 0 ){
                                    r = r.replace(" " + n[s] + " ", " ")
                                }
                            }
                            i.className = e ? v.trim(r) : ""
                        }
                    }
                }
                return this
            }, toggleClass : function (e, t) {
                var n = typeof e, r = typeof t == "boolean";
                return v.isFunction(e) ? this.each(function (n) {
                        v(this).toggleClass(e.call(this, n, this.className, t), t)
                    }) : this.each(function () {
                        if(n === "string"){
                            var i, s = 0, o = v(this), u = t, a = e.split(y);
                            while( i = a[s++] ){
                                u = r ? u : !o.hasClass(i), o[u ? "addClass" : "removeClass"](i)
                            }
                        }else{
                            if(n === "undefined" || n === "boolean"){
                                this.className && v._data(this, "__className__", this.className), this.className = this.className || e === !1 ? "" : v._data(this, "__className__") || ""
                            }
                        }
                    })
            }, hasClass : function (e) {
                var t = " " + e + " ", n = 0, r = this.length;
                for( ; n < r; n++ ){
                    if(this[n].nodeType === 1 && (" " + this[n].className + " ").replace(q, " ").indexOf(t) >= 0){
                        return !0
                    }
                }
                return !1
            }, val : function (e) {
                var n, r, i, s = this[0];
                if(!arguments.length){
                    if(s){
                        return n = v.valHooks[s.type] || v.valHooks[s.nodeName.toLowerCase()], n && ("get" in n && (r = n.get(s, "value")) !== t) ? r : (r = s.value, typeof r == "string" ? r.replace(R, "") : r == null ? "" : r)
                    }
                    return
                }
                return i = v.isFunction(e), this.each(function (r) {
                    var s, o = v(this);
                    if(this.nodeType !== 1){
                        return
                    }
                    i ? s = e.call(this, r, o.val()) : s = e, s == null ? s = "" : typeof s == "number" ? s += "" : v.isArray(s) && (s = v.map(s, function (e) {
                                return e == null ? "" : e + ""
                            })), n = v.valHooks[this.type] || v.valHooks[this.nodeName.toLowerCase()];
                    if(!n || (!("set" in n) || n.set(this, s, "value") === t)){
                        this.value = s
                    }
                })
            }
        }), v.extend({
            valHooks : {
                option : {
                    get : function (e) {
                        var t = e.attributes.value;
                        return !t || t.specified ? e.value : e.text
                    }
                }, select : {
                    get : function (e) {
                        var t, n, r = e.options, i = e.selectedIndex, s = e.type === "select-one" || i < 0, o = s ? null : [], u = s ? i + 1 : r.length, a = i < 0 ? u : s ? i : 0;
                        for( ; a < u; a++ ){
                            n = r[a];
                            if((n.selected || a === i) && ((v.support.optDisabled ? !n.disabled : n.getAttribute("disabled") === null) && (!n.parentNode.disabled || !v.nodeName(n.parentNode, "optgroup")))){
                                t = v(n).val();
                                if(s){
                                    return t
                                }
                                o.push(t)
                            }
                        }
                        return o
                    }, set : function (e, t) {
                        var n = v.makeArray(t);
                        return v(e).find("option").each(function () {
                            this.selected = v.inArray(v(this).val(), n) >= 0
                        }), n.length || (e.selectedIndex = -1), n
                    }
                }
            },
            attrFn : {},
            attr : function (e, n, r, i) {
                var s, o, u, a = e.nodeType;
                if(!e || (a === 3 || (a === 8 || a === 2))){
                    return
                }
                if(i && v.isFunction(v.fn[n])){
                    return v(e)[n](r)
                }
                if(typeof e.getAttribute == "undefined"){
                    return v.prop(e, n, r)
                }
                u = a !== 1 || !v.isXMLDoc(e), u && (n = n.toLowerCase(), o = v.attrHooks[n] || (X.test(n) ? F : j));
                if(r !== t){
                    if(r === null){
                        v.removeAttr(e, n);
                        return
                    }
                    return o && ("set" in o && (u && (s = o.set(e, r, n)) !== t)) ? s : (e.setAttribute(n, r + ""), r)
                }
                return o && ("get" in o && (u && (s = o.get(e, n)) !== null)) ? s : (s = e.getAttribute(n), s === null ? t : s)
            },
            removeAttr : function (e, t) {
                var n, r, i, s, o = 0;
                if(t && e.nodeType === 1){
                    r = t.split(y);
                    for( ; o < r.length; o++ ){
                        i = r[o], i && (n = v.propFix[i] || i, s = X.test(i), s || v.attr(e, i, ""), e.removeAttribute(V ? i : n), s && (n in e && (e[n] = !1)))
                    }
                }
            },
            attrHooks : {
                type : {
                    set : function (e, t) {
                        if(U.test(e.nodeName) && e.parentNode){
                            v.error("type property can't be changed")
                        }else{
                            if(!v.support.radioValue && (t === "radio" && v.nodeName(e, "input"))){
                                var n = e.value;
                                return e.setAttribute("type", t), n && (e.value = n), t
                            }
                        }
                    }
                }, value : {
                    get : function (e, t) {
                        return j && v.nodeName(e, "button") ? j.get(e, t) : t in e ? e.value : null
                    }, set : function (e, t, n) {
                        if(j && v.nodeName(e, "button")){
                            return j.set(e, t, n)
                        }
                        e.value = t
                    }
                }
            },
            propFix : {
                tabindex : "tabIndex",
                readonly : "readOnly",
                "for" : "htmlFor",
                "class" : "className",
                maxlength : "maxLength",
                cellspacing : "cellSpacing",
                cellpadding : "cellPadding",
                rowspan : "rowSpan",
                colspan : "colSpan",
                usemap : "useMap",
                frameborder : "frameBorder",
                contenteditable : "contentEditable"
            },
            prop : function (e, n, r) {
                var i, s, o, u = e.nodeType;
                if(!e || (u === 3 || (u === 8 || u === 2))){
                    return
                }
                return o = u !== 1 || !v.isXMLDoc(e), o && (n = v.propFix[n] || n, s = v.propHooks[n]), r !== t ? s && ("set" in s && (i = s.set(e, r, n)) !== t) ? i : e[n] = r : s && ("get" in s && (i = s.get(e, n)) !== null) ? i : e[n]
            },
            propHooks : {
                tabIndex : {
                    get : function (e) {
                        var n = e.getAttributeNode("tabindex");
                        return n && n.specified ? parseInt(n.value, 10) : z.test(e.nodeName) || W.test(e.nodeName) && e.href ? 0 : t
                    }
                }
            }
        }), F = {
            get : function (e, n) {
                var r, i = v.prop(e, n);
                return i === !0 || typeof i != "boolean" && ((r = e.getAttributeNode(n)) && r.nodeValue !== !1) ? n.toLowerCase() : t
            }, set : function (e, t, n) {
                var r;
                return t === !1 ? v.removeAttr(e, n) : (r = v.propFix[n] || n, r in e && (e[r] = !0), e.setAttribute(n, n.toLowerCase())), n
            }
        }, V || (I = {name : !0, id : !0, coords : !0}, j = v.valHooks.button = {
            get : function (e, n) {
                var r;
                return r = e.getAttributeNode(n), r && (I[n] ? r.value !== "" : r.specified) ? r.value : t
            }, set : function (e, t, n) {
                var r = e.getAttributeNode(n);
                return r || (r = i.createAttribute(n), e.setAttributeNode(r)), r.value = t + ""
            }
        }, v.each(["width", "height"], function (e, t) {
            v.attrHooks[t] = v.extend(v.attrHooks[t], {
                set : function (e, n) {
                    if(n === ""){
                        return e.setAttribute(t, "auto"), n
                    }
                }
            })
        }), v.attrHooks.contenteditable = {
            get : j.get, set : function (e, t, n) {
                t === "" && (t = "false"), j.set(e, t, n)
            }
        }), v.support.hrefNormalized || v.each(["href", "src", "width", "height"], function (e, n) {
            v.attrHooks[n] = v.extend(v.attrHooks[n], {
                get : function (e) {
                    var r = e.getAttribute(n, 2);
                    return r === null ? t : r
                }
            })
        }), v.support.style || (v.attrHooks.style = {
            get : function (e) {
                return e.style.cssText.toLowerCase() || t
            }, set : function (e, t) {
                return e.style.cssText = t + ""
            }
        }), v.support.optSelected || (v.propHooks.selected = v.extend(v.propHooks.selected, {
            get : function (e) {
                var t = e.parentNode;
                return t && (t.selectedIndex, t.parentNode && t.parentNode.selectedIndex), null
            }
        })), v.support.enctype || (v.propFix.enctype = "encoding"), v.support.checkOn || v.each(["radio", "checkbox"], function () {
            v.valHooks[this] = {
                get : function (e) {
                    return e.getAttribute("value") === null ? "on" : e.value
                }
            }
        }), v.each(["radio", "checkbox"], function () {
            v.valHooks[this] = v.extend(v.valHooks[this], {
                set : function (e, t) {
                    if(v.isArray(t)){
                        return e.checked = v.inArray(v(e).val(), t) >= 0
                    }
                }
            })
        });
        var $ = /^(?:textarea|input|select)$/i, J = /^([^\.]*|)(?:\.(.+)|)$/, K = /(?:^|\s)hover(\.\S+|)\b/, Q = /^key/, G = /^(?:mouse|contextmenu)|click/, Y = /^(?:focusinfocus|focusoutblur)$/, Z = function (e) {
            return v.event.special.hover ? e : e.replace(K, "mouseenter$1 mouseleave$1")
        };
        v.event = {
            add : function (e, n, r, i, s) {
                var o, u, a, f, l, c, h, p, d, m, g;
                if(e.nodeType === 3 || (e.nodeType === 8 || (!n || (!r || !(o = v._data(e)))))){
                    return
                }
                r.handler && (d = r, r = d.handler, s = d.selector), r.guid || (r.guid = v.guid++), a = o.events, a || (o.events = a = {}), u = o.handle, u || (o.handle = u = function (e) {
                    return typeof v == "undefined" || !!e && v.event.triggered === e.type ? t : v.event.dispatch.apply(u.elem, arguments)
                }, u.elem = e), n = v.trim(Z(n)).split(" ");
                for( f = 0; f < n.length; f++ ){
                    l = J.exec(n[f]) || [], c = l[1], h = (l[2] || "").split(".").sort(), g = v.event.special[c] || {}, c = (s ? g.delegateType : g.bindType) || c, g = v.event.special[c] || {}, p = v.extend({
                        type : c,
                        origType : l[1],
                        data : i,
                        handler : r,
                        guid : r.guid,
                        selector : s,
                        needsContext : s && v.expr.match.needsContext.test(s),
                        namespace : h.join(".")
                    }, d), m = a[c];
                    if(!m){
                        m = a[c] = [], m.delegateCount = 0;
                        if(!g.setup || g.setup.call(e, i, h, u) === !1){
                            e.addEventListener ? e.addEventListener(c, u, !1) : e.attachEvent && e.attachEvent("on" + c, u)
                        }
                    }
                    g.add && (g.add.call(e, p), p.handler.guid || (p.handler.guid = r.guid)), s ? m.splice(m.delegateCount++, 0, p) : m.push(p), v.event.global[c] = !0
                }
                e = null
            },
            global : {},
            remove : function (e, t, n, r, i) {
                var s, o, u, a, f, l, c, h, p, d, m, g = v.hasData(e) && v._data(e);
                if(!g || !(h = g.events)){
                    return
                }
                t = v.trim(Z(t || "")).split(" ");
                for( s = 0; s < t.length; s++ ){
                    o = J.exec(t[s]) || [], u = a = o[1], f = o[2];
                    if(!u){
                        for( u in h ){
                            v.event.remove(e, u + t[s], n, r, !0)
                        }
                        continue
                    }
                    p = v.event.special[u] || {}, u = (r ? p.delegateType : p.bindType) || u, d = h[u] || [], l = d.length, f = f ? new RegExp("(^|\\.)" + f.split(".").sort().join("\\.(?:.*\\.|)") + "(\\.|$)") : null;
                    for( c = 0; c < d.length; c++ ){
                        m = d[c], (i || a === m.origType) && ((!n || n.guid === m.guid) && ((!f || f.test(m.namespace)) && ((!r || (r === m.selector || r === "**" && m.selector)) && (d.splice(c--, 1), m.selector && d.delegateCount--, p.remove && p.remove.call(e, m)))))
                    }
                    d.length === 0 && (l !== d.length && ((!p.teardown || p.teardown.call(e, f, g.handle) === !1) && v.removeEvent(e, u, g.handle), delete h[u]))
                }
                v.isEmptyObject(h) && (delete g.handle, v.removeData(e, "events", !0))
            },
            customEvent : {getData : !0, setData : !0, changeData : !0},
            trigger : function (n, r, s, o) {
                if(!s || s.nodeType !== 3 && s.nodeType !== 8){
                    var u, a, f, l, c, h, p, d, m, g, y = n.type || n, b = [];
                    if(Y.test(y + v.event.triggered)){
                        return
                    }
                    y.indexOf("!") >= 0 && (y = y.slice(0, -1), a = !0), y.indexOf(".") >= 0 && (b = y.split("."), y = b.shift(), b.sort());
                    if((!s || v.event.customEvent[y]) && !v.event.global[y]){
                        return
                    }
                    n = typeof n == "object" ? n[v.expando] ? n : new v.Event(y, n) : new v.Event(y), n.type = y, n.isTrigger = !0, n.exclusive = a, n.namespace = b.join("."), n.namespace_re = n.namespace ? new RegExp("(^|\\.)" + b.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, h = y.indexOf(":") < 0 ? "on" + y : "";
                    if(!s){
                        u = v.cache;
                        for( f in u ){
                            u[f].events && (u[f].events[y] && v.event.trigger(n, r, u[f].handle.elem, !0))
                        }
                        return
                    }
                    n.result = t, n.target || (n.target = s), r = r != null ? v.makeArray(r) : [], r.unshift(n), p = v.event.special[y] || {};
                    if(p.trigger && p.trigger.apply(s, r) === !1){
                        return
                    }
                    m = [[s, p.bindType || y]];
                    if(!o && (!p.noBubble && !v.isWindow(s))){
                        g = p.delegateType || y, l = Y.test(g + y) ? s : s.parentNode;
                        for( c = s; l; l = l.parentNode ){
                            m.push([l, g]), c = l
                        }
                        c === (s.ownerDocument || i) && m.push([c.defaultView || (c.parentWindow || e), g])
                    }
                    for( f = 0; f < m.length && !n.isPropagationStopped(); f++ ){
                        l = m[f][0], n.type = m[f][1], d = (v._data(l, "events") || {})[n.type] && v._data(l, "handle"), d && d.apply(l, r), d = h && l[h], d && (v.acceptData(l) && (d.apply && (d.apply(l, r) === !1 && n.preventDefault())))
                    }
                    return n.type = y, !o && (!n.isDefaultPrevented() && ((!p._default || p._default.apply(s.ownerDocument, r) === !1) && ((y !== "click" || !v.nodeName(s, "a")) && (v.acceptData(s) && (h && (s[y] && ((y !== "focus" && y !== "blur" || n.target.offsetWidth !== 0) && (!v.isWindow(s) && (c = s[h], c && (s[h] = null), v.event.triggered = y, s[y](), v.event.triggered = t, c && (s[h] = c)))))))))), n.result
                }
                return
            },
            dispatch : function (n) {
                n = v.event.fix(n || e.event);
                var r, i, s, o, u, a, f, c, h, p, d = (v._data(this, "events") || {})[n.type] || [], m = d.delegateCount, g = l.call(arguments), y = !n.exclusive && !n.namespace, b = v.event.special[n.type] || {}, w = [];
                g[0] = n, n.delegateTarget = this;
                if(b.preDispatch && b.preDispatch.call(this, n) === !1){
                    return
                }
                if(m && (!n.button || n.type !== "click")){
                    for( s = n.target; s != this; s = s.parentNode || this ){
                        if(s.disabled !== !0 || n.type !== "click"){
                            u = {}, f = [];
                            for( r = 0; r < m; r++ ){
                                c = d[r], h = c.selector, u[h] === t && (u[h] = c.needsContext ? v(h, this).index(s) >= 0 : v.find(h, this, null, [s]).length), u[h] && f.push(c)
                            }
                            f.length && w.push({elem : s, matches : f})
                        }
                    }
                }
                d.length > m && w.push({elem : this, matches : d.slice(m)});
                for( r = 0; r < w.length && !n.isPropagationStopped(); r++ ){
                    a = w[r], n.currentTarget = a.elem;
                    for( i = 0; i < a.matches.length && !n.isImmediatePropagationStopped(); i++ ){
                        c = a.matches[i];
                        if(y || (!n.namespace && !c.namespace || n.namespace_re && n.namespace_re.test(c.namespace))){
                            n.data = c.data, n.handleObj = c, o = ((v.event.special[c.origType] || {}).handle || c.handler).apply(a.elem, g), o !== t && (n.result = o, o === !1 && (n.preventDefault(), n.stopPropagation()))
                        }
                    }
                }
                return b.postDispatch && b.postDispatch.call(this, n), n.result
            },
            props : "attrChange attrName relatedNode srcElement altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
            fixHooks : {},
            keyHooks : {
                props : "char charCode key keyCode".split(" "), filter : function (e, t) {
                    return e.which == null && (e.which = t.charCode != null ? t.charCode : t.keyCode), e
                }
            },
            mouseHooks : {
                props : "button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),
                filter : function (e, n) {
                    var r, s, o, u = n.button, a = n.fromElement;
                    return e.pageX == null && (n.clientX != null && (r = e.target.ownerDocument || i, s = r.documentElement, o = r.body, e.pageX = n.clientX + (s && s.scrollLeft || (o && o.scrollLeft || 0)) - (s && s.clientLeft || (o && o.clientLeft || 0)), e.pageY = n.clientY + (s && s.scrollTop || (o && o.scrollTop || 0)) - (s && s.clientTop || (o && o.clientTop || 0)))), !e.relatedTarget && (a && (e.relatedTarget = a === e.target ? n.toElement : a)), !e.which && (u !== t && (e.which = u & 1 ? 1 : u & 2 ? 3 : u & 4 ? 2 : 0)), e
                }
            },
            fix : function (e) {
                if(e[v.expando]){
                    return e
                }
                var t, n, r = e, s = v.event.fixHooks[e.type] || {}, o = s.props ? this.props.concat(s.props) : this.props;
                e           = v.Event(r);
                for( t = o.length; t; ){
                    n = o[--t], e[n] = r[n]
                }
                return e.target || (e.target = r.srcElement || i), e.target.nodeType === 3 && (e.target = e.target.parentNode), e.metaKey = !!e.metaKey, s.filter ? s.filter(e, r) : e
            },
            special : {
                load : {noBubble : !0},
                focus : {delegateType : "focusin"},
                blur : {delegateType : "focusout"},
                beforeunload : {
                    setup : function (e, t, n) {
                        v.isWindow(this) && (this.onbeforeunload = n)
                    }, teardown : function (e, t) {
                        this.onbeforeunload === t && (this.onbeforeunload = null)
                    }
                }
            },
            simulate : function (e, t, n, r) {
                var i = v.extend(new v.Event, n, {type : e, isSimulated : !0, originalEvent : {}});
                r ? v.event.trigger(i, null, t) : v.event.dispatch.call(t, i), i.isDefaultPrevented() && n.preventDefault()
            }
        }, v.event.handle = v.event.dispatch, v.removeEvent = i.removeEventListener ? function (e, t, n) {
                e.removeEventListener && e.removeEventListener(t, n, !1)
            } : function (e, t, n) {
                var r = "on" + t;
                e.detachEvent && (typeof e[r] == "undefined" && (e[r] = null), e.detachEvent(r, n))
            }, v.Event = function (e, t) {
            if(!(this instanceof v.Event)){
                return new v.Event(e, t)
            }
            e && e.type ? (this.originalEvent = e, this.type = e.type, this.isDefaultPrevented = e.defaultPrevented || (e.returnValue === !1 || e.getPreventDefault && e.getPreventDefault()) ? tt : et) : this.type = e, t && v.extend(this, t), this.timeStamp = e && e.timeStamp || v.now(), this[v.expando] = !0
        }, v.Event.prototype = {
            preventDefault : function () {
                this.isDefaultPrevented = tt;
                var e                   = this.originalEvent;
                if(!e){
                    return
                }
                e.preventDefault ? e.preventDefault() : e.returnValue = !1
            }, stopPropagation : function () {
                this.isPropagationStopped = tt;
                var e                     = this.originalEvent;
                if(!e){
                    return
                }
                e.stopPropagation && e.stopPropagation(), e.cancelBubble = !0
            }, stopImmediatePropagation : function () {
                this.isImmediatePropagationStopped = tt, this.stopPropagation()
            }, isDefaultPrevented : et, isPropagationStopped : et, isImmediatePropagationStopped : et
        }, v.each({mouseenter : "mouseover", mouseleave : "mouseout"}, function (e, t) {
            v.event.special[e] = {
                delegateType : t, bindType : t, handle : function (e) {
                    var n, r = this, i = e.relatedTarget, s = e.handleObj, o = s.selector;
                    if(!i || i !== r && !v.contains(r, i)){
                        e.type = s.origType, n = s.handler.apply(this, arguments), e.type = t
                    }
                    return n
                }
            }
        }), v.support.submitBubbles || (v.event.special.submit = {
            setup : function () {
                if(v.nodeName(this, "form")){
                    return !1
                }
                v.event.add(this, "click._submit keypress._submit", function (e) {
                    var n = e.target, r = v.nodeName(n, "input") || v.nodeName(n, "button") ? n.form : t;
                    r && (!v._data(r, "_submit_attached") && (v.event.add(r, "submit._submit", function (e) {
                        e._submit_bubble = !0
                    }), v._data(r, "_submit_attached", !0)))
                })
            }, postDispatch : function (e) {
                e._submit_bubble && (delete e._submit_bubble, this.parentNode && (!e.isTrigger && v.event.simulate("submit", this.parentNode, e, !0)))
            }, teardown : function () {
                if(v.nodeName(this, "form")){
                    return !1
                }
                v.event.remove(this, "._submit")
            }
        }), v.support.changeBubbles || (v.event.special.change = {
            setup : function () {
                if($.test(this.nodeName)){
                    if(this.type === "checkbox" || this.type === "radio"){
                        v.event.add(this, "propertychange._change", function (e) {
                            e.originalEvent.propertyName === "checked" && (this._just_changed = !0)
                        }), v.event.add(this, "click._change", function (e) {
                            this._just_changed && (!e.isTrigger && (this._just_changed = !1)), v.event.simulate("change", this, e, !0)
                        })
                    }
                    return !1
                }
                v.event.add(this, "beforeactivate._change", function (e) {
                    var t = e.target;
                    $.test(t.nodeName) && (!v._data(t, "_change_attached") && (v.event.add(t, "change._change", function (e) {
                        this.parentNode && (!e.isSimulated && (!e.isTrigger && v.event.simulate("change", this.parentNode, e, !0)))
                    }), v._data(t, "_change_attached", !0)))
                })
            }, handle : function (e) {
                var t = e.target;
                if(this !== t || (e.isSimulated || (e.isTrigger || t.type !== "radio" && t.type !== "checkbox"))){
                    return e.handleObj.handler.apply(this, arguments)
                }
            }, teardown : function () {
                return v.event.remove(this, "._change"), !$.test(this.nodeName)
            }
        }), v.support.focusinBubbles || v.each({focus : "focusin", blur : "focusout"}, function (e, t) {
            var n              = 0, r = function (e) {
                v.event.simulate(t, e.target, v.event.fix(e), !0)
            };
            v.event.special[t] = {
                setup : function () {
                    n++ === 0 && i.addEventListener(e, r, !0)
                }, teardown : function () {
                    --n === 0 && i.removeEventListener(e, r, !0)
                }
            }
        }), v.fn.extend({
            on : function (e, n, r, i, s) {
                var o, u;
                if(typeof e == "object"){
                    typeof n != "string" && (r = r || n, n = t);
                    for( u in e ){
                        this.on(u, n, r, e[u], s)
                    }
                    return this
                }
                r == null && i == null ? (i = n, r = n = t) : i
                ==
                null && (typeof n == "string" ? (i = r, r = t) : (i = r, r = n, n = t));
                if(i === !1){
                    i = et
                }else{
                    if(!i){
                        return this
                    }
                }
                return s === 1 && (o = i, i = function (e) {
                    return v().off(e), o.apply(this, arguments)
                }, i.guid = o.guid || (o.guid = v.guid++)), this.each(function () {
                    v.event.add(this, e, i, r, n)
                })
            }, one : function (e, t, n, r) {
                return this.on(e, t, n, r, 1)
            }, off : function (e, n, r) {
                var i, s;
                if(e && (e.preventDefault && e.handleObj)){
                    return i = e.handleObj, v(e.delegateTarget).off(i.namespace ? i.origType + "." + i.namespace : i.origType, i.selector, i.handler), this
                }
                if(typeof e == "object"){
                    for( s in e ){
                        this.off(s, n, e[s])
                    }
                    return this
                }
                if(n === !1 || typeof n == "function"){
                    r = n, n = t
                }
                return r === !1 && (r = et), this.each(function () {
                    v.event.remove(this, e, r, n)
                })
            }, bind : function (e, t, n) {
                return this.on(e, null, t, n)
            }, unbind : function (e, t) {
                return this.off(e, null, t)
            }, live : function (e, t, n) {
                return v(this.context).on(e, this.selector, t, n), this
            }, die : function (e, t) {
                return v(this.context).off(e, this.selector || "**", t), this
            }, delegate : function (e, t, n, r) {
                return this.on(t, e, n, r)
            }, undelegate : function (e, t, n) {
                return arguments.length === 1 ? this.off(e, "**") : this.off(t, e || "**", n)
            }, trigger : function (e, t) {
                return this.each(function () {
                    v.event.trigger(e, t, this)
                })
            }, triggerHandler : function (e, t) {
                if(this[0]){
                    return v.event.trigger(e, t, this[0], !0)
                }
            }, toggle : function (e) {
                var t  = arguments, n = e.guid || v.guid++, r = 0, i = function (n) {
                    var i = (v._data(this, "lastToggle" + e.guid) || 0) % r;
                    return v._data(this, "lastToggle" + e.guid, i + 1), n.preventDefault(), t[i].apply(this, arguments) || !1
                };
                i.guid = n;
                while( r < t.length ){
                    t[r++].guid = n
                }
                return this.click(i)
            }, hover : function (e, t) {
                return this.mouseenter(e).mouseleave(t || e)
            }
        }), v.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "), function (e, t) {
            v.fn[t] = function (e, n) {
                return n == null && (n = e, e = null), arguments.length > 0 ? this.on(t, null, e, n) : this.trigger(t)
            }, Q.test(t) && (v.event.fixHooks[t] = v.event.keyHooks), G.test(t) && (v.event.fixHooks[t] = v.event.mouseHooks)
        }), function (e, t) {
            function nt(e, t, n, r) {
                n = n || [], t = t || g;
                var i, s, a, f, l = t.nodeType;
                if(!e || typeof e != "string"){
                    return n
                }
                if(l !== 1 && l !== 9){
                    return []
                }
                a = o(t);
                if(!a && !r){
                    if(i = R.exec(e)){
                        if(f = i[1]){
                            if(l === 9){
                                s = t.getElementById(f);
                                if(!s || !s.parentNode){
                                    return n
                                }
                                if(s.id === f){
                                    return n.push(s), n
                                }
                            }else{
                                if(t.ownerDocument && ((s = t.ownerDocument.getElementById(f)) && (u(t, s) && s.id === f))){
                                    return n.push(s), n
                                }
                            }
                        }else{
                            if(i[2]){
                                return S.apply(n, x.call(t.getElementsByTagName(e), 0)), n
                            }
                            if((f = i[3]) && (Z && t.getElementsByClassName)){
                                return S.apply(n, x.call(t.getElementsByClassName(f), 0)), n
                            }
                        }
                    }
                }
                return vt(e.replace(j, "$1"), t, n, r, a)
            }

            function rt(e) {
                return function (t) {
                    var n = t.nodeName.toLowerCase();
                    return n === "input" && t.type === e
                }
            }

            function it(e) {
                return function (t) {
                    var n = t.nodeName.toLowerCase();
                    return (n === "input" || n === "button") && t.type === e
                }
            }

            function st(e) {
                return N(function (t) {
                    return t = +t, N(function (n, r) {
                        var i, s = e([], n.length, t), o = s.length;
                        while( o-- ){
                            n[i = s[o]] && (n[i] = !(r[i] = n[i]))
                        }
                    })
                })
            }

            function ot(e, t, n) {
                if(e === t){
                    return n
                }
                var r = e.nextSibling;
                while( r ){
                    if(r === t){
                        return -1
                    }
                    r = r.nextSibling
                }
                return 1
            }

            function ut(e, t) {
                var n, r, s, o, u, a, f, l = L[d][e + " "];
                if(l){
                    return t ? 0 : l.slice(0)
                }
                u = e, a = [], f = i.preFilter;
                while( u ){
                    if(!n || (r = F.exec(u))){
                        r && (u = u.slice(r[0].length) || u), a.push(s = [])
                    }
                    n = !1;
                    if(r = I.exec(u)){
                        s.push(n = new m(r.shift())), u = u.slice(n.length), n.type = r[0].replace(j, " ")
                    }
                    for( o in i.filter ){
                        (r = J[o].exec(u)) && ((!f[o] || (r = f[o](r))) && (s.push(n = new m(r.shift())), u = u.slice(n.length), n.type = o, n.matches = r))
                    }
                    if(!n){
                        break
                    }
                }
                return t ? u.length : u ? nt.error(e) : L(e, a).slice(0)
            }

            function at(e, t, r) {
                var i = t.dir, s = r && t.dir === "parentNode", o = w++;
                return t.first ? function (t, n, r) {
                        while( t = t[i] ){
                            if(s || t.nodeType === 1){
                                return e(t, n, r)
                            }
                        }
                    } : function (t, r, u) {
                        if(!u){
                            var a, f = b + " " + o + " ", l = f + n;
                            while( t = t[i] ){
                                if(s || t.nodeType === 1){
                                    if((a = t[d]) === l){
                                        return t.sizset
                                    }
                                    if(typeof a == "string" && a.indexOf(f) === 0){
                                        if(t.sizset){
                                            return t
                                        }
                                    }else{
                                        t[d] = l;
                                        if(e(t, r, u)){
                                            return t.sizset = !0, t
                                        }
                                        t.sizset = !1
                                    }
                                }
                            }
                        }else{
                            while( t = t[i] ){
                                if(s || t.nodeType === 1){
                                    if(e(t, r, u)){
                                        return t
                                    }
                                }
                            }
                        }
                    }
            }

            function ft(e) {
                return e.length > 1 ? function (t, n, r) {
                        var i = e.length;
                        while( i-- ){
                            if(!e[i](t, n, r)){
                                return !1
                            }
                        }
                        return !0
                    } : e[0]
            }

            function lt(e, t, n, r, i) {
                var s, o = [], u = 0, a = e.length, f = t != null;
                for( ; u < a; u++ ){
                    if(s = e[u]){
                        if(!n || n(s, r, i)){
                            o.push(s), f && t.push(u)
                        }
                    }
                }
                return o
            }

            function ct(e, t, n, r, i, s) {
                return r && (!r[d] && (r = ct(r))), i && (!i[d] && (i = ct(i, s))), N(function (s, o, u, a) {
                    var f, l, c, h = [], p = [], d = o.length, v = s || dt(t || "*", u.nodeType ? [u] : u, []), m = e && (s || !t) ? lt(v, h, e, u, a) : v, g = n ? i || (s ? e : d || r) ? [] : o : m;
                    n && n(m, g, u, a);
                    if(r){
                        f = lt(g, p), r(f, [], u, a), l = f.length;
                        while( l-- ){
                            if(c = f[l]){
                                g[p[l]] = !(m[p[l]] = c)
                            }
                        }
                    }
                    if(s){
                        if(i || e){
                            if(i){
                                f = [], l = g.length;
                                while( l-- ){
                                    (c = g[l]) && f.push(m[l] = c)
                                }
                                i(null, g = [], f, a)
                            }
                            l = g.length;
                            while( l-- ){
                                (c = g[l]) && ((f = i ? T.call(s, c) : h[l]) > -1 && (s[f] = !(o[f] = c)))
                            }
                        }
                    }else{
                        g = lt(g === o ? g.splice(d, g.length) : g), i ? i(null, o, g, a) : S.apply(o, g)
                    }
                })
            }

            function ht(e) {
                var t, n, r, s = e.length, o = i.relative[e[0].type], u = o || i.relative[" "], a = o ? 1 : 0, f = at(function (e) {
                    return e === t
                }, u, !0), l   = at(function (e) {
                    return T.call(t, e) > -1
                }, u, !0), h   = [function (e, n, r) {
                    return !o && (r || n !== c) || ((t = n).nodeType ? f(e, n, r) : l(e, n, r))
                }];
                for( ; a < s; a++ ){
                    if(n = i.relative[e[a].type]){
                        h = [at(ft(h), n)]
                    }else{
                        n = i.filter[e[a].type].apply(null, e[a].matches);
                        if(n[d]){
                            r = ++a;
                            for( ; r < s; r++ ){
                                if(i.relative[e[r].type]){
                                    break
                                }
                            }
                            return ct(a > 1 && ft(h), a > 1 && e.slice(0, a - 1).join("").replace(j, "$1"), n, a < r && ht(e.slice(a, r)), r < s && ht(e = e.slice(r)), r < s && e.join(""))
                        }
                        h.push(n)
                    }
                }
                return ft(h)
            }

            function pt(e, t) {
                var r = t.length > 0, s = e.length > 0, o = function (u, a, f, l, h) {
                    var p, d, v, m = [], y = 0, w = "0", x = u && [], T = h != null, N = c, C = u || s && i.find.TAG("*", h && a.parentNode || a), k = b += N == null ? 1 : Math.E;
                    T && (c = a !== g && a, n = o.el);
                    for( ; (p = C[w]) != null; w++ ){
                        if(s && p){
                            for( d = 0; v = e[d]; d++ ){
                                if(v(p, a, f)){
                                    l.push(p);
                                    break
                                }
                            }
                            T && (b = k, n = ++o.el)
                        }
                        r && ((p = !v && p) && y--, u && x.push(p))
                    }
                    y += w;
                    if(r && w !== y){
                        for( d = 0; v = t[d]; d++ ){
                            v(x, m, a, f)
                        }
                        if(u){
                            if(y > 0){
                                while( w-- ){
                                    !x[w] && (!m[w] && (m[w] = E.call(l)))
                                }
                            }
                            m = lt(m)
                        }
                        S.apply(l, m), T && (!u && (m.length > 0 && (y + t.length > 1 && nt.uniqueSort(l))))
                    }
                    return T && (b = k, c = N), x
                };
                return o.el = 0, r ? N(o) : o
            }

            function dt(e, t, n) {
                var r = 0, i = t.length;
                for( ; r < i; r++ ){
                    nt(e, t[r], n)
                }
                return n
            }

            function vt(e, t, n, r, s) {
                var o, u, f, l, c, h = ut(e), p = h.length;
                if(!r && h.length === 1){
                    u = h[0] = h[0].slice(0);
                    if(u.length > 2 && ((f = u[0]).type === "ID" && (t.nodeType === 9 && (!s && i.relative[u[1].type])))){
                        t = i.find.ID(f.matches[0].replace($, ""), t, s)[0];
                        if(!t){
                            return n
                        }
                        e = e.slice(u.shift().length)
                    }
                    for( o = J.POS.test(e) ? -1 : u.length - 1; o >= 0; o-- ){
                        f = u[o];
                        if(i.relative[l = f.type]){
                            break
                        }
                        if(c = i.find[l]){
                            if(r = c(f.matches[0].replace($, ""), z.test(u[0].type) && t.parentNode || t, s)){
                                u.splice(o, 1), e = r.length && u.join("");
                                if(!e){
                                    return S.apply(n, x.call(r, 0)), n
                                }
                                break
                            }
                        }
                    }
                }
                return a(e, h)(r, t, s, n, z.test(e)), n
            }

            function mt() {
            }

            var n, r, i, s, o, u, a, f, l, c, h = !0, p = "undefined", d = ("sizcache" + Math.random()).replace(".", ""), m = String, g = e.document, y = g.documentElement, b = 0, w = 0, E = [].pop, S = [].push, x = [].slice, T = [].indexOf || function (e) {
                    var t = 0, n = this.length;
                    for( ; t < n; t++ ){
                        if(this[t] === e){
                            return t
                        }
                    }
                    return -1
                }, N                            = function (e, t) {
                return e[d] = t == null || t, e
            }, C                                = function () {
                var e = {}, t = [];
                return N(function (n, r) {
                    return t.push(n) > i.cacheLength && delete e[t.shift()], e[n + " "] = r
                }, e)
            }, k                                = C(), L = C(), A = C(), O = "[\\x20\\t\\r\\n\\f]", M = "(?:\\\\.|[-\\w]|[^\\x00-\\xa0])+", _ = M.replace("w", "w#"), D = "([*^$|!~]?=)", P = "\\[" + O + "*(" + M + ")" + O + "*(?:" + D + O + "*(?:(['\"])((?:\\\\.|[^\\\\])*?)\\3|(" + _ + ")|)|)" + O + "*\\]", H = ":(" + M + ")(?:\\((?:(['\"])((?:\\\\.|[^\\\\])*?)\\2|([^()[\\]]*|(?:(?:" + P + ")|[^:]|\\\\.)*|.*))\\)|)", B = ":(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + O + "*((?:-\\d)?\\d*)" + O + "*\\)|)(?=[^-]|$)", j = new RegExp("^" + O + "+|((?:^|[^\\\\])(?:\\\\.)*)" + O + "+$", "g"), F = new RegExp("^" + O + "*," + O + "*"), I = new RegExp("^" + O + "*([\\x20\\t\\r\\n\\f>+~])" + O + "*"), q = new RegExp(H), R = /^(?:#([\w\-]+)|(\w+)|\.([\w\-]+))$/, U = /^:not/, z = /[\x20\t\r\n\f]*[+~]/, W = /:not\($/, X = /h\d/i, V = /input|select|textarea|button/i, $ = /\\(?!\\)/g, J = {
                ID : new RegExp("^#(" + M + ")"),
                CLASS : new RegExp("^\\.(" + M + ")"),
                NAME : new RegExp("^\\[name=['\"]?(" + M + ")['\"]?\\]"),
                TAG : new RegExp("^(" + M.replace("w", "w*") + ")"),
                ATTR : new RegExp("^" + P),
                PSEUDO : new RegExp("^" + H),
                POS : new RegExp(B, "i"),
                CHILD : new RegExp("^:(only|nth|first|last)-child(?:\\(" + O + "*(even|odd|(([+-]|)(\\d*)n|)" + O + "*(?:([+-]|)" + O + "*(\\d+)|))" + O + "*\\)|)", "i"),
                needsContext : new RegExp("^" + O + "*[>+~]|" + B, "i")
            }, K                                = function (e) {
                var t = g.createElement("div");
                try{
                    return e(t)
                }catch(n){
                    return !1
                }finally{
                    t = null
                }
            }, Q                                = K(function (e) {
                return e.appendChild(g.createComment("")), !e.getElementsByTagName("*").length
            }), G                               = K(function (e) {
                return e.innerHTML = "<a href='#'></a>", e.firstChild && (typeof e.firstChild.getAttribute !== p && e.firstChild.getAttribute("href") === "#")
            }), Y                               = K(function (e) {
                e.innerHTML = "<select></select>";
                var t       = typeof e.lastChild.getAttribute("multiple");
                return t !== "boolean" && t !== "string"
            }), Z                               = K(function (e) {
                return e.innerHTML = "<div class='hidden e'></div><div class='hidden'></div>", !e.getElementsByClassName || !e.getElementsByClassName("e").length ? !1 : (e.lastChild.className = "e", e.getElementsByClassName("e").length === 2)
            }), et                              = K(function (e) {
                e.id = d + 0, e.innerHTML = "<a name='" + d + "'></a><div name='" + d + "'></div>", y.insertBefore(e, y.firstChild);
                var t = g.getElementsByName && g.getElementsByName(d).length === 2 + g.getElementsByName(d + 0).length;
                return r = !g.getElementById(d), y.removeChild(e), t
            });
            try{
                x.call(y.childNodes, 0)[0].nodeType
            }catch(tt){
                x = function (e) {
                    var t, n = [];
                    for( ; t = this[e]; e++ ){
                        n.push(t)
                    }
                    return n
                }
            }
            nt.matches = function (e, t) {
                return nt(e, null, null, t)
            }, nt.matchesSelector = function (e, t) {
                return nt(t, null, null, [e]).length > 0
            }, s = nt.getText = function (e) {
                var t, n = "", r = 0, i = e.nodeType;
                if(i){
                    if(i === 1 || (i === 9 || i === 11)){
                        if(typeof e.textContent == "string"){
                            return e.textContent
                        }
                        for( e = e.firstChild; e; e = e.nextSibling ){
                            n += s(e)
                        }
                    }else{
                        if(i === 3 || i === 4){
                            return e.nodeValue
                        }
                    }
                }else{
                    for( ; t = e[r]; r++ ){
                        n += s(t)
                    }
                }
                return n
            }, o = nt.isXML = function (e) {
                var t = e && (e.ownerDocument || e).documentElement;
                return t ? t.nodeName !== "HTML" : !1
            }, u = nt.contains = y.contains ? function (e, t) {
                    var n = e.nodeType === 9 ? e.documentElement : e, r = t && t.parentNode;
                    return e === r || !!(r && (r.nodeType === 1 && (n.contains && n.contains(r))))
                } : y.compareDocumentPosition ? function (e, t) {
                        return t && !!(e.compareDocumentPosition(t) & 16)
                    } : function (e, t) {
                        while( t = t.parentNode ){
                            if(t === e){
                                return !0
                            }
                        }
                        return !1
                    }, nt.attr = function (e, t) {
                var n, r = o(e);
                return r || (t = t.toLowerCase()), (n = i.attrHandle[t]) ? n(e) : r || Y ? e.getAttribute(t) : (n = e.getAttributeNode(t), n ? typeof e[t] == "boolean" ? e[t] ? t : null : n.specified ? n.value : null : null)
            }, i = nt.selectors = {
                cacheLength : 50,
                createPseudo : N,
                match : J,
                attrHandle : G ? {} : {
                        href : function (e) {
                            return e.getAttribute("href", 2)
                        }, type : function (e) {
                            return e.getAttribute("type")
                        }
                    },
                find : {
                    ID : r ? function (e, t, n) {
                            if(typeof t.getElementById !== p && !n){
                                var r = t.getElementById(e);
                                return r && r.parentNode ? [r] : []
                            }
                        } : function (e, n, r) {
                            if(typeof n.getElementById !== p && !r){
                                var i = n.getElementById(e);
                                return i ? i.id === e || typeof i.getAttributeNode !== p && i.getAttributeNode("id").value === e ? [i] : t : []
                            }
                        }, TAG : Q ? function (e, t) {
                            if(typeof t.getElementsByTagName !== p){
                                return t.getElementsByTagName(e)
                            }
                        } : function (e, t) {
                            var n = t.getElementsByTagName(e);
                            if(e === "*"){
                                var r, i = [], s = 0;
                                for( ; r = n[s]; s++ ){
                                    r.nodeType === 1 && i.push(r)
                                }
                                return i
                            }
                            return n
                        }, NAME : et && function (e, t) {
                        if(typeof t.getElementsByName !== p){
                            return t.getElementsByName(name)
                        }
                    }, CLASS : Z && function (e, t, n) {
                        if(typeof t.getElementsByClassName !== p && !n){
                            return t.getElementsByClassName(e)
                        }
                    }
                },
                relative : {
                    ">" : {dir : "parentNode", first : !0},
                    " " : {dir : "parentNode"},
                    "+" : {dir : "previousSibling", first : !0},
                    "~" : {dir : "previousSibling"}
                },
                preFilter : {
                    ATTR : function (e) {
                        return e[1] = e[1].replace($, ""), e[3] = (e[4] || (e[5] || "")).replace($, ""), e[2] === "~=" && (e[3] = " " + e[3] + " "), e.slice(0, 4)
                    }, CHILD : function (e) {
                        return e[1] = e[1].toLowerCase(), e[1] === "nth" ? (e[2] || nt.error(e[0]), e[3] = +(e[3] ? e[4] + (e[5] || 1) : 2 * (e[2] === "even" || e[2] === "odd")), e[4] = +(e[6] + e[7] || e[2] === "odd")) : e[2] && nt.error(e[0]), e
                    }, PSEUDO : function (e) {
                        var t, n;
                        if(J.CHILD.test(e[0])){
                            return null
                        }
                        if(e[3]){
                            e[2] = e[3]
                        }else{
                            if(t = e[4]){
                                q.test(t) && ((n = ut(t, !0)) && ((n = t.indexOf(")", t.length - n) - t.length) && (t = t.slice(0, n), e[0] = e[0].slice(0, n)))), e[2] = t
                            }
                        }
                        return e.slice(0, 3)
                    }
                },
                filter : {
                    ID : r ? function (e) {
                            return e = e.replace($, ""), function (t) {
                                return t.getAttribute("id") === e
                            }
                        } : function (e) {
                            return e = e.replace($, ""), function (t) {
                                var n = typeof t.getAttributeNode !== p && t.getAttributeNode("id");
                                return n && n.value === e
                            }
                        }, TAG : function (e) {
                        return e === "*" ? function () {
                                return !0
                            } : (e = e.replace($, "").toLowerCase(), function (t) {
                                return t.nodeName && t.nodeName.toLowerCase() === e
                            })
                    }, CLASS : function (e) {
                        var t = k[d][e + " "];
                        return t || (t = new RegExp("(^|" + O + ")" + e + "(" + O + "|$)")) && k(e, function (e) {
                                return t.test(e.className || (typeof e.getAttribute !== p && e.getAttribute("class") || ""))
                            })
                    }, ATTR : function (e, t, n) {
                        return function (r, i) {
                            var s = nt.attr(r, e);
                            return s == null ? t === "!=" : t ? (s += "", t === "=" ? s === n : t === "!=" ? s !== n : t === "^=" ? n && s.indexOf(n) === 0 : t === "*=" ? n && s.indexOf(n) > -1 : t === "$=" ? n && s.substr(s.length - n.length) === n : t === "~=" ? (" " + s + " ").indexOf(n) > -1 : t === "|=" ? s === n || s.substr(0, n.length + 1) === n + "-" : !1) : !0
                        }
                    }, CHILD : function (e, t, n, r) {
                        return e === "nth" ? function (e) {
                                var t, i, s = e.parentNode;
                                if(n === 1 && r === 0){
                                    return !0
                                }
                                if(s){
                                    i = 0;
                                    for( t = s.firstChild; t; t = t.nextSibling ){
                                        if(t.nodeType === 1){
                                            i++;
                                            if(e === t){
                                                break
                                            }
                                        }
                                    }
                                }
                                return i -= r, i === n || i % n === 0 && i / n >= 0
                            } : function (t) {
                                var n = t;
                                switch(e){
                                    case"only":
                                    case"first":
                                        while( n = n.previousSibling ){
                                            if(n.nodeType === 1){
                                                return !1
                                            }
                                        }
                                        if(e === "first"){
                                            return !0
                                        }
                                        n = t;
                                    case"last":
                                        while( n = n.nextSibling ){
                                            if(n.nodeType === 1){
                                                return !1
                                            }
                                        }
                                        return !0
                                }
                            }
                    }, PSEUDO : function (e, t) {
                        var n, r = i.pseudos[e] || (i.setFilters[e.toLowerCase()] || nt.error("unsupported pseudo: " + e));
                        return r[d] ? r(t) : r.length > 1 ? (n = [e, e, "", t], i.setFilters.hasOwnProperty(e.toLowerCase()) ? N(function (e, n) {
                                        var i, s = r(e, t), o = s.length;
                                        while( o-- ){
                                            i = T.call(e, s[o]), e[i] = !(n[i] = s[o])
                                        }
                                    }) : function (e) {
                                        return r(e, 0, n)
                                    }) : r
                    }
                },
                pseudos : {
                    not : N(function (e) {
                        var t = [], n = [], r = a(e.replace(j, "$1"));
                        return r[d] ? N(function (e, t, n, i) {
                                var s, o = r(e, null, i, []), u = e.length;
                                while( u-- ){
                                    if(s = o[u]){
                                        e[u] = !(t[u] = s)
                                    }
                                }
                            }) : function (e, i, s) {
                                return t[0] = e, r(t, null, s, n), !n.pop()
                            }
                    }),
                    has : N(function (e) {
                        return function (t) {
                            return nt(e, t).length > 0
                        }
                    }),
                    contains : N(function (e) {
                        return function (t) {
                            return (t.textContent || (t.innerText || s(t))).indexOf(e) > -1
                        }
                    }),
                    enabled : function (e) {
                        return e.disabled === !1
                    },
                    disabled : function (e) {
                        return e.disabled === !0
                    },
                    checked : function (e) {
                        var t = e.nodeName.toLowerCase();
                        return t === "input" && !!e.checked || t === "option" && !!e.selected
                    },
                    selected : function (e) {
                        return e.parentNode && e.parentNode.selectedIndex, e.selected === !0
                    },
                    parent : function (e) {
                        return !i.pseudos.empty(e)
                    },
                    empty : function (e) {
                        var t;
                        e = e.firstChild;
                        while( e ){
                            if(e.nodeName > "@" || ((t = e.nodeType) === 3 || t === 4)){
                                return !1
                            }
                            e = e.nextSibling
                        }
                        return !0
                    },
                    header : function (e) {
                        return X.test(e.nodeName)
                    },
                    text : function (e) {
                        var t, n;
                        return e.nodeName.toLowerCase() === "input" && ((t = e.type) === "text" && ((n = e.getAttribute("type")) == null || n.toLowerCase() === t))
                    },
                    radio : rt("radio"),
                    checkbox : rt("checkbox"),
                    file : rt("file"),
                    password : rt("password"),
                    image : rt("image"),
                    submit : it("submit"),
                    reset : it("reset"),
                    button : function (e) {
                        var t = e.nodeName.toLowerCase();
                        return t === "input" && e.type === "button" || t === "button"
                    },
                    input : function (e) {
                        return V.test(e.nodeName)
                    },
                    focus : function (e) {
                        var t = e.ownerDocument;
                        return e === t.activeElement && ((!t.hasFocus || t.hasFocus()) && !!(e.type || (e.href || ~e.tabIndex)))
                    },
                    active : function (e) {
                        return e === e.ownerDocument.activeElement
                    },
                    first : st(function () {
                        return [0]
                    }),
                    last : st(function (e, t) {
                        return [t - 1]
                    }),
                    eq : st(function (e, t, n) {
                        return [n < 0 ? n + t : n]
                    }),
                    even : st(function (e, t) {
                        for( var n = 0; n < t; n += 2 ){
                            e.push(n)
                        }
                        return e
                    }),
                    odd : st(function (e, t) {
                        for( var n = 1; n < t; n += 2 ){
                            e.push(n)
                        }
                        return e
                    }),
                    lt : st(function (e, t, n) {
                        for( var r = n < 0 ? n + t : n; --r >= 0; ){
                            e.push(r)
                        }
                        return e
                    }),
                    gt : st(function (e, t, n) {
                        for( var r = n < 0 ? n + t : n; ++r < t; ){
                            e.push(r)
                        }
                        return e
                    })
                }
            }, f = y.compareDocumentPosition ? function (e, t) {
                    return e === t ? (l = !0, 0) : (!e.compareDocumentPosition || !t.compareDocumentPosition ? e.compareDocumentPosition : e.compareDocumentPosition(t) & 4) ? -1 : 1
                } : function (e, t) {
                    if(e === t){
                        return l = !0, 0
                    }
                    if(e.sourceIndex && t.sourceIndex){
                        return e.sourceIndex - t.sourceIndex
                    }
                    var n, r, i = [], s = [], o = e.parentNode, u = t.parentNode, a = o;
                    if(o === u){
                        return ot(e, t)
                    }
                    if(!o){
                        return -1
                    }
                    if(!u){
                        return 1
                    }
                    while( a ){
                        i.unshift(a), a = a.parentNode
                    }
                    a = u;
                    while( a ){
                        s.unshift(a), a = a.parentNode
                    }
                    n = i.length, r = s.length;
                    for( var f = 0; f < n && f < r; f++ ){
                        if(i[f] !== s[f]){
                            return ot(i[f], s[f])
                        }
                    }
                    return f === n ? ot(e, s[f], -1) : ot(i[f], t, 1)
                }, [0, 0].sort(f), h = !l, nt.uniqueSort = function (e) {
                var t, n = [], r = 1, i = 0;
                l = h, e.sort(f);
                if(l){
                    for( ; t = e[r]; r++ ){
                        t === e[r - 1] && (i = n.push(r))
                    }
                    while( i-- ){
                        e.splice(n[i], 1)
                    }
                }
                return e
            }, nt.error = function (e) {
                throw new Error("Syntax error, unrecognized expression: " + e)
            }, a = nt.compile = function (e, t) {
                var n, r = [], i = [], s = A[d][e + " "];
                if(!s){
                    t || (t = ut(e)), n = t.length;
                    while( n-- ){
                        s = ht(t[n]), s[d] ? r.push(s) : i.push(s)
                    }
                    s = A(e, pt(i, r))
                }
                return s
            }, g.querySelectorAll && function () {
                var e, t = vt, n = /'|\\/g, r = /\=[\x20\t\r\n\f]*([^'"\]]*)[\x20\t\r\n\f]*\]/g, i = [":focus"], s = [":active"], u = y.matchesSelector || (y.mozMatchesSelector || (y.webkitMatchesSelector || (y.oMatchesSelector || y.msMatchesSelector)));
                K(function (e) {
                    e.innerHTML = "<select><option selected=''></option></select>", e.querySelectorAll("[selected]").length || i.push("\\[" + O + "*(?:checked|disabled|ismap|multiple|readonly|selected|value)"), e.querySelectorAll(":checked").length || i.push(":checked")
                }), K(function (e) {
                    e.innerHTML = "<p test=''></p>", e.querySelectorAll("[test^='']").length && i.push("[*^$]=" + O + "*(?:\"\"|'')"), e.innerHTML = "<input type='hidden'/>", e.querySelectorAll(":enabled").length || i.push(":enabled", ":disabled")
                }), i = new RegExp(i.join("|")), vt = function (e, r, s, o, u) {
                    if(!o && (!u && !i.test(e))){
                        var a, f, l = !0, c = d, h = r, p = r.nodeType === 9 && e;
                        if(r.nodeType === 1 && r.nodeName.toLowerCase() !== "object"){
                            a = ut(e), (l = r.getAttribute("id")) ? c = l.replace(n, "\\$&") : r.setAttribute("id", c), c = "[id='" + c + "'] ", f = a.length;
                            while( f-- ){
                                a[f] = c + a[f].join("")
                            }
                            h = z.test(e) && r.parentNode || r, p = a.join(",")
                        }
                        if(p){
                            try{
                                return S.apply(s, x.call(h.querySelectorAll(p), 0)), s
                            }catch(v){
                            }finally{
                                l || r.removeAttribute("id")
                            }
                        }
                    }
                    return t(e, r, s, o, u)
                }, u && (K(function (t) {
                    e = u.call(t, "div");
                    try{
                        u.call(t, "[test!='']:sizzle"), s.push("!=", H)
                    }catch(n){
                    }
                }), s = new RegExp(s.join("|")), nt.matchesSelector = function (t, n) {
                    n = n.replace(r, "='$1']");
                    if(!o(t) && (!s.test(n) && !i.test(n))){
                        try{
                            var a = u.call(t, n);
                            if(a || (e || t.document && t.document.nodeType !== 11)){
                                return a
                            }
                        }catch(f){
                        }
                    }
                    return nt(n, null, null, [t]).length > 0
                })
            }(), i.pseudos.nth = i.pseudos.eq, i.filters = mt.prototype = i.pseudos, i.setFilters = new mt, nt.attr = v.attr, v.find = nt, v.expr = nt.selectors, v.expr[":"] = v.expr.pseudos, v.unique = nt.uniqueSort, v.text = nt.getText, v.isXMLDoc = nt.isXML, v.contains = nt.contains
        }(e);
        var nt = /Until$/, rt = /^(?:parents|prev(?:Until|All))/, it = /^.[^:#\[\.,]*$/, st = v.expr.match.needsContext, ot = {
            children : !0,
            contents : !0,
            next : !0,
            prev : !0
        };
        v.fn.extend({
            find : function (e) {
                var t, n, r, i, s, o, u = this;
                if(typeof e != "string"){
                    return v(e).filter(function () {
                        for( t = 0, n = u.length; t < n; t++ ){
                            if(v.contains(u[t], this)){
                                return !0
                            }
                        }
                    })
                }
                o = this.pushStack("", "find", e);
                for( t = 0, n = this.length; t < n; t++ ){
                    r = o.length, v.find(e, this[t], o);
                    if(t > 0){
                        for( i = r; i < o.length; i++ ){
                            for( s = 0; s < r; s++ ){
                                if(o[s] === o[i]){
                                    o.splice(i--, 1);
                                    break
                                }
                            }
                        }
                    }
                }
                return o
            }, has : function (e) {
                var t, n = v(e, this), r = n.length;
                return this.filter(function () {
                    for( t = 0; t < r; t++ ){
                        if(v.contains(this, n[t])){
                            return !0
                        }
                    }
                })
            }, not : function (e) {
                return this.pushStack(ft(this, e, !1), "not", e)
            }, filter : function (e) {
                return this.pushStack(ft(this, e, !0), "filter", e)
            }, is : function (e) {
                return !!e && (typeof e == "string" ? st.test(e) ? v(e, this.context).index(this[0]) >= 0 : v.filter(e, this).length > 0 : this.filter(e).length > 0)
            }, closest : function (e, t) {
                var n, r = 0, i = this.length, s = [], o = st.test(e) || typeof e != "string" ? v(e, t || this.context) : 0;
                for( ; r < i; r++ ){
                    n = this[r];
                    while( n && (n.ownerDocument && (n !== t && n.nodeType !== 11)) ){
                        if(o ? o.index(n) > -1 : v.find.matchesSelector(n, e)){
                            s.push(n);
                            break
                        }
                        n = n.parentNode
                    }
                }
                return s = s.length > 1 ? v.unique(s) : s, this.pushStack(s, "closest", e)
            }, index : function (e) {
                return e ? typeof e == "string" ? v.inArray(this[0], v(e)) : v.inArray(e.jquery ? e[0] : e, this) : this[0] && this[0].parentNode ? this.prevAll().length : -1
            }, add : function (e, t) {
                var n = typeof e == "string" ? v(e, t) : v.makeArray(e && e.nodeType ? [e] : e), r = v.merge(this.get(), n);
                return this.pushStack(ut(n[0]) || ut(r[0]) ? r : v.unique(r))
            }, addBack : function (e) {
                return this.add(e == null ? this.prevObject : this.prevObject.filter(e))
            }
        }), v.fn.andSelf = v.fn.addBack, v.each({
            parent : function (e) {
                var t = e.parentNode;
                return t && t.nodeType !== 11 ? t : null
            }, parents : function (e) {
                return v.dir(e, "parentNode")
            }, parentsUntil : function (e, t, n) {
                return v.dir(e, "parentNode", n)
            }, next : function (e) {
                return at(e, "nextSibling")
            }, prev : function (e) {
                return at(e, "previousSibling")
            }, nextAll : function (e) {
                return v.dir(e, "nextSibling")
            }, prevAll : function (e) {
                return v.dir(e, "previousSibling")
            }, nextUntil : function (e, t, n) {
                return v.dir(e, "nextSibling", n)
            }, prevUntil : function (e, t, n) {
                return v.dir(e, "previousSibling", n)
            }, siblings : function (e) {
                return v.sibling((e.parentNode || {}).firstChild, e)
            }, children : function (e) {
                return v.sibling(e.firstChild)
            }, contents : function (e) {
                return v.nodeName(e, "iframe") ? e.contentDocument || e.contentWindow.document : v.merge([], e.childNodes)
            }
        }, function (e, t) {
            v.fn[e] = function (n, r) {
                var i = v.map(this, t, n);
                return nt.test(e) || (r = n), r && (typeof r == "string" && (i = v.filter(r, i))), i = this.length > 1 && !ot[e] ? v.unique(i) : i, this.length > 1 && (rt.test(e) && (i = i.reverse())), this.pushStack(i, e, l.call(arguments).join(","))
            }
        }), v.extend({
            filter : function (e, t, n) {
                return n && (e = ":not(" + e + ")"), t.length === 1 ? v.find.matchesSelector(t[0], e) ? [t[0]] : [] : v.find.matches(e, t)
            }, dir : function (e, n, r) {
                var i = [], s = e[n];
                while( s && (s.nodeType !== 9 && (r === t || (s.nodeType !== 1 || !v(s).is(r)))) ){
                    s.nodeType === 1 && i.push(s), s = s[n]
                }
                return i
            }, sibling : function (e, t) {
                var n = [];
                for( ; e; e = e.nextSibling ){
                    e.nodeType === 1 && (e !== t && n.push(e))
                }
                return n
            }
        });
        var ct = "abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video", ht = / jQuery\d+="(?:null|\d+)"/g, pt = /^\s+/, dt = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi, vt = /<([\w:]+)/, mt = /<tbody/i, gt = /<|&#?\w+;/, yt = /<(?:script|style|link)/i, bt = /<(?:script|object|embed|option|style)/i, wt = new RegExp("<(?:" + ct + ")[\\s/>]", "i"), Et = /^(?:checkbox|radio)$/, St = /checked\s*(?:[^=]|=\s*.checked.)/i, xt = /\/(java|ecma)script/i, Tt = /^\s*<!(?:\[CDATA\[|\-\-)|[\]\-]{2}>\s*$/g, Nt = {
            option : [1, "<select multiple='multiple'>", "</select>"],
            legend : [1, "<fieldset>", "</fieldset>"],
            thead : [1, "<table>", "</table>"],
            tr : [2, "<table><tbody>", "</tbody></table>"],
            td : [3, "<table><tbody><tr>", "</tr></tbody></table>"],
            col : [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"],
            area : [1, "<map>", "</map>"],
            _default : [0, "", ""]
        }, Ct  = lt(i), kt = Ct.appendChild(i.createElement("div"));
        Nt.optgroup = Nt.option, Nt.tbody = Nt.tfoot = Nt.colgroup = Nt.caption = Nt.thead, Nt.th = Nt.td, v.support.htmlSerialize || (Nt._default = [1, "X<div>", "</div>"]), v.fn.extend({
            text : function (e) {
                return v.access(this, function (e) {
                    return e === t ? v.text(this) : this.empty().append((this[0] && this[0].ownerDocument || i).createTextNode(e))
                }, null, e, arguments.length)
            }, wrapAll : function (e) {
                if(v.isFunction(e)){
                    return this.each(function (t) {
                        v(this).wrapAll(e.call(this, t))
                    })
                }
                if(this[0]){
                    var t = v(e, this[0].ownerDocument).eq(0).clone(!0);
                    this[0].parentNode && t.insertBefore(this[0]), t.map(function () {
                        var e = this;
                        while( e.firstChild && e.firstChild.nodeType === 1 ){
                            e = e.firstChild
                        }
                        return e
                    }).append(this)
                }
                return this
            }, wrapInner : function (e) {
                return v.isFunction(e) ? this.each(function (t) {
                        v(this).wrapInner(e.call(this, t))
                    }) : this.each(function () {
                        var t = v(this), n = t.contents();
                        n.length ? n.wrapAll(e) : t.append(e)
                    })
            }, wrap : function (e) {
                var t = v.isFunction(e);
                return this.each(function (n) {
                    v(this).wrapAll(t ? e.call(this, n) : e)
                })
            }, unwrap : function () {
                return this.parent().each(function () {
                    v.nodeName(this, "body") || v(this).replaceWith(this.childNodes)
                }).end()
            }, append : function () {
                return this.domManip(arguments, !0, function (e) {
                    (this.nodeType === 1 || this.nodeType === 11) && this.appendChild(e)
                })
            }, prepend : function () {
                return this.domManip(arguments, !0, function (e) {
                    (this.nodeType === 1 || this.nodeType === 11) && this.insertBefore(e, this.firstChild)
                })
            }, before : function () {
                if(!ut(this[0])){
                    return this.domManip(arguments, !1, function (e) {
                        this.parentNode.insertBefore(e, this)
                    })
                }
                if(arguments.length){
                    var e = v.clean(arguments);
                    return this.pushStack(v.merge(e, this), "before", this.selector)
                }
            }, after : function () {
                if(!ut(this[0])){
                    return this.domManip(arguments, !1, function (e) {
                        this.parentNode.insertBefore(e, this.nextSibling)
                    })
                }
                if(arguments.length){
                    var e = v.clean(arguments);
                    return this.pushStack(v.merge(this, e), "after", this.selector)
                }
            }, remove : function (e, t) {
                var n, r = 0;
                for( ; (n = this[r]) != null; r++ ){
                    if(!e || v.filter(e, [n]).length){
                        !t && (n.nodeType === 1 && (v.cleanData(n.getElementsByTagName("*")), v.cleanData([n]))), n.parentNode && n.parentNode.removeChild(n)
                    }
                }
                return this
            }, empty : function () {
                var e, t = 0;
                for( ; (e = this[t]) != null; t++ ){
                    e.nodeType === 1 && v.cleanData(e.getElementsByTagName("*"));
                    while( e.firstChild ){
                        e.removeChild(e.firstChild)
                    }
                }
                return this
            }, clone : function (e, t) {
                return e = e == null ? !1 : e, t = t == null ? e : t, this.map(function () {
                    return v.clone(this, e, t)
                })
            }, html : function (e) {
                return v.access(this, function (e) {
                    var n = this[0] || {}, r = 0, i = this.length;
                    if(e === t){
                        return n.nodeType === 1 ? n.innerHTML.replace(ht, "") : t
                    }
                    if(typeof e == "string" && (!yt.test(e) && ((v.support.htmlSerialize || !wt.test(e)) && ((v.support.leadingWhitespace || !pt.test(e)) && !Nt[(vt.exec(e) || ["", ""])[1].toLowerCase()])))){
                        e = e.replace(dt, "<$1></$2>");
                        try{
                            for( ; r < i; r++ ){
                                n = this[r] || {}, n.nodeType === 1 && (v.cleanData(n.getElementsByTagName("*")), n.innerHTML = e)
                            }
                            n = 0
                        }catch(s){
                        }
                    }
                    n && this.empty().append(e)
                }, null, e, arguments.length)
            }, replaceWith : function (e) {
                return ut(this[0]) ? this.length ? this.pushStack(v(v.isFunction(e) ? e() : e), "replaceWith", e) : this : v.isFunction(e) ? this.each(function (t) {
                            var n = v(this), r = n.html();
                            n.replaceWith(e.call(this, t, r))
                        }) : (typeof e != "string" && (e = v(e).detach()), this.each(function () {
                            var t = this.nextSibling, n = this.parentNode;
                            v(this).remove(), t ? v(t).before(e) : v(n).append(e)
                        }))
            }, detach : function (e) {
                return this.remove(e, !0)
            }, domManip : function (e, n, r) {
                e                 = [].concat.apply([], e);
                var i, s, o, u, a = 0, f = e[0], l = [], c = this.length;
                if(!v.support.checkClone && (c > 1 && (typeof f == "string" && St.test(f)))){
                    return this.each(function () {
                        v(this).domManip(e, n, r)
                    })
                }
                if(v.isFunction(f)){
                    return this.each(function (i) {
                        var s = v(this);
                        e[0] = f.call(this, i, n ? s.html() : t), s.domManip(e, n, r)
                    })
                }
                if(this[0]){
                    i = v.buildFragment(e, this, l), o = i.fragment, s = o.firstChild, o.childNodes.length === 1 && (o = s);
                    if(s){
                        n = n && v.nodeName(s, "tr");
                        for( u = i.cacheable || c - 1; a < c; a++ ){
                            r.call(n && v.nodeName(this[a], "table") ? Lt(this[a], "tbody") : this[a], a === u ? o : v.clone(o, !0, !0))
                        }
                    }
                    o = s = null, l.length && v.each(l, function (e, t) {
                        t.src ? v.ajax ? v.ajax({
                                    url : t.src,
                                    type : "GET",
                                    dataType : "script",
                                    async : !1,
                                    global : !1,
                                    "throws" : !0
                                }) : v.error("no ajax") : v.globalEval((t.text || (t.textContent || (t.innerHTML || ""))).replace(Tt, "")), t.parentNode && t.parentNode.removeChild(t)
                    })
                }
                return this
            }
        }), v.buildFragment = function (e, n, r) {
            var s, o, u, a = e[0];
            return n = n || i, n = !n.nodeType && n[0] || n, n = n.ownerDocument || n, e.length === 1 && (typeof a == "string" && (a.length < 512 && (n === i && (a.charAt(0) === "<" && (!bt.test(a) && ((v.support.checkClone || !St.test(a)) && ((v.support.html5Clone || !wt.test(a)) && (o = !0, s = v.fragments[a], u = s !== t)))))))), s || (s = n.createDocumentFragment(), v.clean(e, n, s, r), o && (v.fragments[a] = u && s)), {
                fragment : s,
                cacheable : o
            }
        }, v.fragments = {}, v.each({
            appendTo : "append",
            prependTo : "prepend",
            insertBefore : "before",
            insertAfter : "after",
            replaceAll : "replaceWith"
        }, function (e, t) {
            v.fn[e] = function (n) {
                var r, i = 0, s = [], o = v(n), u = o.length, a = this.length === 1 && this[0].parentNode;
                if((a == null || a && (a.nodeType === 11 && a.childNodes.length === 1)) && u === 1){
                    return o[t](this[0]), this
                }
                for( ; i < u; i++ ){
                    r = (i > 0 ? this.clone(!0) : this).get(), v(o[i])[t](r), s = s.concat(r)
                }
                return this.pushStack(s, e, o.selector)
            }
        }), v.extend({
            clone : function (e, t, n) {
                var r, i, s, o;
                v.support.html5Clone || (v.isXMLDoc(e) || !wt.test("<" + e.nodeName + ">")) ? o = e.cloneNode(!0) : (kt.innerHTML = e.outerHTML, kt.removeChild(o = kt.firstChild));
                if((!v.support.noCloneEvent || !v.support.noCloneChecked) && ((e.nodeType === 1 || e.nodeType === 11) && !v.isXMLDoc(e))){
                    Ot(e, o), r = Mt(e), i = Mt(o);
                    for( s = 0; r[s]; ++s ){
                        i[s] && Ot(r[s], i[s])
                    }
                }
                if(t){
                    At(e, o);
                    if(n){
                        r = Mt(e), i = Mt(o);
                        for( s = 0; r[s]; ++s ){
                            At(r[s], i[s])
                        }
                    }
                }
                return r = i = null, o
            }, clean : function (e, t, n, r) {
                var s, o, u, a, f, l, c, h, p, d, m, g, y = t === i && Ct, b = [];
                if(!t || typeof t.createDocumentFragment == "undefined"){
                    t = i
                }
                for( s = 0; (u = e[s]) != null; s++ ){
                    typeof u == "number" && (u += "");
                    if(!u){
                        continue
                    }
                    if(typeof u == "string"){
                        if(!gt.test(u)){
                            u = t.createTextNode(u)
                        }else{
                            y = y || lt(t), c = t.createElement("div"), y.appendChild(c), u = u.replace(dt, "<$1></$2>"), a = (vt.exec(u) || ["", ""])[1].toLowerCase(), f = Nt[a] || Nt._default, l = f[0], c.innerHTML = f[1] + u + f[2];
                            while( l-- ){
                                c = c.lastChild
                            }
                            if(!v.support.tbody){
                                h = mt.test(u), p = a === "table" && !h ? c.firstChild && c.firstChild.childNodes : f[1] === "<table>" && !h ? c.childNodes : [];
                                for( o = p.length - 1; o >= 0; --o ){
                                    v.nodeName(p[o], "tbody") && (!p[o].childNodes.length && p[o].parentNode.removeChild(p[o]))
                                }
                            }
                            !v.support.leadingWhitespace && (pt.test(u) && c.insertBefore(t.createTextNode(pt.exec(u)[0]), c.firstChild)), u = c.childNodes, c.parentNode.removeChild(c)
                        }
                    }
                    u.nodeType ? b.push(u) : v.merge(b, u)
                }
                c && (u = c = y = null);
                if(!v.support.appendChecked){
                    for( s = 0; (u = b[s]) != null; s++ ){
                        v.nodeName(u, "input") ? _t(u) : typeof u.getElementsByTagName != "undefined" && v.grep(u.getElementsByTagName("input"), _t)
                    }
                }
                if(n){
                    m = function (e) {
                        if(!e.type || xt.test(e.type)){
                            return r ? r.push(e.parentNode ? e.parentNode.removeChild(e) : e) : n.appendChild(e)
                        }
                    };
                    for( s = 0; (u = b[s]) != null; s++ ){
                        if(!v.nodeName(u, "script") || !m(u)){
                            n.appendChild(u), typeof u.getElementsByTagName != "undefined" && (g = v.grep(v.merge([], u.getElementsByTagName("script")), m), b.splice.apply(b, [s + 1, 0].concat(g)), s += g.length)
                        }
                    }
                }
                return b
            }, cleanData : function (e, t) {
                var n, r, i, s, o = 0, u = v.expando, a = v.cache, f = v.support.deleteExpando, l = v.event.special;
                for( ; (i = e[o]) != null; o++ ){
                    if(t || v.acceptData(i)){
                        r = i[u], n = r && a[r];
                        if(n){
                            if(n.events){
                                for( s in n.events ){
                                    l[s] ? v.event.remove(i, s) : v.removeEvent(i, s, n.handle)
                                }
                            }
                            a[r] && (delete a[r], f ? delete i[u] : i.removeAttribute ? i.removeAttribute(u) : i[u] = null, v.deletedIds.push(r))
                        }
                    }
                }
            }
        }), function () {
            var e, t;
            v.uaMatch = function (e) {
                e     = e.toLowerCase();
                var t = /(chrome)[ \/]([\w.]+)/.exec(e) || (/(webkit)[ \/]([\w.]+)/.exec(e) || (/(opera)(?:.*version|)[ \/]([\w.]+)/.exec(e) || (/(msie) ([\w.]+)/.exec(e) || (e.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(e) || []))));
                return {browser : t[1] || "", version : t[2] || "0"}
            }, e = v.uaMatch(o.userAgent), t = {}, e.browser && (t[e.browser] = !0, t.version = e.version), t.chrome ? t.webkit = !0 : t.webkit && (t.safari = !0), v.browser = t, v.sub = function () {
                function e(t, n) {
                    return new e.fn.init(t, n)
                }

                v.extend(!0, e, this), e.superclass = this, e.fn = e.prototype = this(), e.fn.constructor = e, e.sub = this.sub, e.fn.init = function (r, i) {
                    return i && (i instanceof v && (!(i instanceof e) && (i = e(i)))), v.fn.init.call(this, r, i, t)
                }, e.fn.init.prototype = e.fn;
                var t = e(i);
                return e
            }
        }();
        var Dt, Pt, Ht, Bt = /alpha\([^)]*\)/i, jt = /opacity=([^)]*)/, Ft = /^(top|right|bottom|left)$/, It = /^(none|table(?!-c[ea]).+)/, qt = /^margin/, Rt = new RegExp("^(" + m + ")(.*)$", "i"), Ut = new RegExp("^(" + m + ")(?!px)[a-z%]+$", "i"), zt = new RegExp("^([-+])=(" + m + ")", "i"), Wt = {BODY : "block"}, Xt = {
            position : "absolute",
            visibility : "hidden",
            display : "block"
        }, Vt              = {
            letterSpacing : 0,
            fontWeight : 400
        }, $t              = ["Top", "Right", "Bottom", "Left"], Jt = ["Webkit", "O", "Moz", "ms"], Kt = v.fn.toggle;
        v.fn.extend({
            css : function (e, n) {
                return v.access(this, function (e, n, r) {
                    return r !== t ? v.style(e, n, r) : v.css(e, n)
                }, e, n, arguments.length > 1)
            }, show : function () {
                return Yt(this, !0)
            }, hide : function () {
                return Yt(this)
            }, toggle : function (e, t) {
                var n = typeof e == "boolean";
                return v.isFunction(e) && v.isFunction(t) ? Kt.apply(this, arguments) : this.each(function () {
                        (n ? e : Gt(this)) ? v(this).show() : v(this).hide()
                    })
            }
        }), v.extend({
            cssHooks : {
                opacity : {
                    get : function (e, t) {
                        if(t){
                            var n = Dt(e, "opacity");
                            return n === "" ? "1" : n
                        }
                    }
                }
            },
            cssNumber : {
                fillOpacity : !0,
                fontWeight : !0,
                lineHeight : !0,
                opacity : !0,
                orphans : !0,
                widows : !0,
                zIndex : !0,
                zoom : !0
            },
            cssProps : {"float" : v.support.cssFloat ? "cssFloat" : "styleFloat"},
            style : function (e, n, r, i) {
                if(!e || (e.nodeType === 3 || (e.nodeType === 8 || !e.style))){
                    return
                }
                var s, o, u, a = v.camelCase(n), f = e.style;
                n = v.cssProps[a] || (v.cssProps[a] = Qt(f, a)), u = v.cssHooks[n] || v.cssHooks[a];
                if(r === t){
                    return u && ("get" in u && (s = u.get(e, !1, i)) !== t) ? s : f[n]
                }
                o = typeof r, o === "string" && ((s = zt.exec(r)) && (r = (s[1] + 1) * s[2] + parseFloat(v.css(e, n)), o = "number"));
                if(r == null || o === "number" && isNaN(r)){
                    return
                }
                o === "number" && (!v.cssNumber[a] && (r += "px"));
                if(!u || (!("set" in u) || (r = u.set(e, r, i)) !== t)){
                    try{
                        f[n] = r
                    }catch(l){
                    }
                }
            },
            css : function (e, n, r, i) {
                var s, o, u, a = v.camelCase(n);
                return n = v.cssProps[a] || (v.cssProps[a] = Qt(e.style, a)), u = v.cssHooks[n] || v.cssHooks[a], u && ("get" in u && (s = u.get(e, !0, i))), s === t && (s = Dt(e, n)), s === "normal" && (n in Vt && (s = Vt[n])), r || i !== t ? (o = parseFloat(s), r || v.isNumeric(o) ? o || 0 : s) : s
            },
            swap : function (e, t, n) {
                var r, i, s = {};
                for( i in t ){
                    s[i] = e.style[i], e.style[i] = t[i]
                }
                r = n.call(e);
                for( i in t ){
                    e.style[i] = s[i]
                }
                return r
            }
        }), e.getComputedStyle ? Dt = function (t, n) {
                var r, i, s, o, u = e.getComputedStyle(t, null), a = t.style;
                return u && (r = u.getPropertyValue(n) || u[n], r === "" && (!v.contains(t.ownerDocument, t) && (r = v.style(t, n))), Ut.test(r) && (qt.test(n) && (i = a.width, s = a.minWidth, o = a.maxWidth, a.minWidth = a.maxWidth = a.width = r, r = u.width, a.width = i, a.minWidth = s, a.maxWidth = o))), r
            } : i.documentElement.currentStyle && (Dt = function (e, t) {
                var n, r, i = e.currentStyle && e.currentStyle[t], s = e.style;
                return i == null && (s && (s[t] && (i = s[t]))), Ut.test(i) && (!Ft.test(t) && (n = s.left, r = e.runtimeStyle && e.runtimeStyle.left, r && (e.runtimeStyle.left = e.currentStyle.left), s.left = t === "fontSize" ? "1em" : i, i = s.pixelLeft + "px", s.left = n, r && (e.runtimeStyle.left = r))), i === "" ? "auto" : i
            }), v.each(["height", "width"], function (e, t) {
            v.cssHooks[t] = {
                get : function (e, n, r) {
                    if(n){
                        return e.offsetWidth === 0 && It.test(Dt(e, "display")) ? v.swap(e, Xt, function () {
                                return tn(e, t, r)
                            }) : tn(e, t, r)
                    }
                }, set : function (e, n, r) {
                    return Zt(e, n, r ? en(e, t, r, v.support.boxSizing && v.css(e, "boxSizing") === "border-box") : 0)
                }
            }
        }), v.support.opacity || (v.cssHooks.opacity = {
            get : function (e, t) {
                return jt.test((t && e.currentStyle ? e.currentStyle.filter : e.style.filter) || "") ? 0.01 * parseFloat(RegExp.$1) + "" : t ? "1" : ""
            }, set : function (e, t) {
                var n  = e.style, r = e.currentStyle, i = v.isNumeric(t) ? "alpha(opacity=" + t * 100 + ")" : "", s = r && r.filter || (n.filter || "");
                n.zoom = 1;
                if(t >= 1 && (v.trim(s.replace(Bt, "")) === "" && n.removeAttribute)){
                    n.removeAttribute("filter");
                    if(r && !r.filter){
                        return
                    }
                }
                n.filter = Bt.test(s) ? s.replace(Bt, i) : s + " " + i
            }
        }), v(function () {
            v.support.reliableMarginRight || (v.cssHooks.marginRight = {
                get : function (e, t) {
                    return v.swap(e, {display : "inline-block"}, function () {
                        if(t){
                            return Dt(e, "marginRight")
                        }
                    })
                }
            }), !v.support.pixelPosition && (v.fn.position && v.each(["top", "left"], function (e, t) {
                v.cssHooks[t] = {
                    get : function (e, n) {
                        if(n){
                            var r = Dt(e, t);
                            return Ut.test(r) ? v(e).position()[t] + "px" : r
                        }
                    }
                }
            }))
        }), v.expr && (v.expr.filters && (v.expr.filters.hidden = function (e) {
            return e.offsetWidth === 0 && e.offsetHeight === 0 || !v.support.reliableHiddenOffsets && (e.style && e.style.display || Dt(e, "display")) === "none"
        }, v.expr.filters.visible = function (e) {
            return !v.expr.filters.hidden(e)
        })), v.each({margin : "", padding : "", border : "Width"}, function (e, t) {
            v.cssHooks[e + t] = {
                expand : function (n) {
                    var r, i = typeof n == "string" ? n.split(" ") : [n], s = {};
                    for( r = 0; r < 4; r++ ){
                        s[e + $t[r] + t] = i[r] || (i[r - 2] || i[0])
                    }
                    return s
                }
            }, qt.test(e) || (v.cssHooks[e + t].set = Zt)
        });
        var rn = /%20/g, sn = /\[\]$/, on = /\r?\n/g, un = /^(?:color|date|datetime|datetime-local|email|hidden|month|number|password|range|search|tel|text|time|url|week)$/i, an = /^(?:select|textarea)/i;
        v.fn.extend({
            serialize : function () {
                return v.param(this.serializeArray())
            }, serializeArray : function () {
                return this.map(function () {
                    return this.elements ? v.makeArray(this.elements) : this
                }).filter(function () {
                    return this.name && (!this.disabled && (this.checked || (an.test(this.nodeName) || un.test(this.type))))
                }).map(function (e, t) {
                    var n = v(this).val();
                    return n == null ? null : v.isArray(n) ? v.map(n, function (e, n) {
                                return {name : t.name, value : e.replace(on, "\r\n")}
                            }) : {name : t.name, value : n.replace(on, "\r\n")}
                }).get()
            }
        }), v.param = function (e, n) {
            var r, i = [], s = function (e, t) {
                t = v.isFunction(t) ? t() : t == null ? "" : t, i[i.length] = encodeURIComponent(e) + "=" + encodeURIComponent(t)
            };
            n === t && (n = v.ajaxSettings && v.ajaxSettings.traditional);
            if(v.isArray(e) || e.jquery && !v.isPlainObject(e)){
                v.each(e, function () {
                    s(this.name, this.value)
                })
            }else{
                for( r in e ){
                    fn(r, e[r], n, s)
                }
            }
            return i.join("&").replace(rn, "+")
        };
        var ln, cn, hn = /#.*$/, pn = /^(.*?):[ \t]*([^\r\n]*)\r?$/mg, dn = /^(?:about|app|app\-storage|.+\-extension|file|res|widget):$/, vn = /^(?:GET|HEAD)$/, mn = /^\/\//, gn = /\?/, yn = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, bn = /([?&])_=[^&]*/, wn = /^([\w\+\.\-]+:)(?:\/\/([^\/?#:]*)(?::(\d+)|)|)/, En = v.fn.load, Sn = {}, xn = {}, Tn = ["*/"] + ["*"];
        try{
            cn = s.href
        }catch(Nn){
            cn = i.createElement("a"), cn.href = "", cn = cn.href
        }
        ln = wn.exec(cn.toLowerCase()) || [], v.fn.load = function (e, n, r) {
            if(typeof e != "string" && En){
                return En.apply(this, arguments)
            }
            if(!this.length){
                return this
            }
            var i, s, o, u = this, a = e.indexOf(" ");
            return a >= 0 && (i = e.slice(a, e.length), e = e.slice(0, a)), v.isFunction(n) ? (r = n, n = t) : n
            &&
            (typeof n == "object" && (s = "POST")), v.ajax({
                url : e,
                type : s,
                dataType : "html",
                data : n,
                complete : function (e, t) {
                    r && u.each(r, o || [e.responseText, t, e])
                }
            }).done(function (e) {
                o = arguments, u.html(i ? v("<div>").append(e.replace(yn, "")).find(i) : e)
            }), this
        }, v.each("ajaxStart ajaxStop ajaxComplete ajaxError ajaxSuccess ajaxSend".split(" "), function (e, t) {
            v.fn[t] = function (e) {
                return this.on(t, e)
            }
        }), v.each(["get", "post"], function (e, n) {
            v[n] = function (e, r, i, s) {
                return v.isFunction(r) && (s = s || i, i = r, r = t), v.ajax({
                    type : n,
                    url : e,
                    data : r,
                    success : i,
                    dataType : s
                })
            }
        }), v.extend({
            getScript : function (e, n) {
                return v.get(e, t, n, "script")
            },
            getJSON : function (e, t, n) {
                return v.get(e, t, n, "json")
            },
            ajaxSetup : function (e, t) {
                return t ? Ln(e, v.ajaxSettings) : (t = e, e = v.ajaxSettings), Ln(e, t), e
            },
            ajaxSettings : {
                url : cn,
                isLocal : dn.test(ln[1]),
                global : !0,
                type : "GET",
                contentType : "application/x-www-form-urlencoded; charset=UTF-8",
                processData : !0,
                async : !0,
                accepts : {
                    xml : "application/xml, text/xml",
                    html : "text/html",
                    text : "text/plain",
                    json : "application/json, text/javascript",
                    "*" : Tn
                },
                contents : {xml : /xml/, html : /html/, json : /json/},
                responseFields : {xml : "responseXML", text : "responseText"},
                converters : {
                    "* text" : e.String,
                    "text html" : !0,
                    "text json" : v.parseJSON,
                    "text xml" : v.parseXML
                },
                flatOptions : {context : !0, url : !0}
            },
            ajaxPrefilter : Cn(Sn),
            ajaxTransport : Cn(xn),
            ajax : function (e, n) {
                function T(e, n, s, a) {
                    var l, y, b, w, S, T = n;
                    if(E === 2){
                        return
                    }
                    E = 2, u && clearTimeout(u), o = t, i = a || "", x.readyState = e > 0 ? 4 : 0, s && (w = An(c, x, s));
                    if(e >= 200 && e < 300 || e === 304){
                        c.ifModified && (S = x.getResponseHeader("Last-Modified"), S && (v.lastModified[r] = S), S = x.getResponseHeader("Etag"), S && (v.etag[r] = S)), e === 304 ? (T = "notmodified", l = !0) : (l = On(c, w), T = l.state, y = l.data, b = l.error, l = !b)
                    }else{
                        b = T;
                        if(!T || e){
                            T = "error", e < 0 && (e = 0)
                        }
                    }
                    x.status = e, x.statusText = (n || T) + "", l ? d.resolveWith(h, [y, T, x]) : d.rejectWith(h, [x, T, b]), x.statusCode(g), g = t, f && p.trigger("ajax" + (l ? "Success" : "Error"), [x, c, l ? y : b]), m.fireWith(h, [x, T]), f && (p.trigger("ajaxComplete", [x, c]), --v.active || v.event.trigger("ajaxStop"))
                }

                typeof e == "object" && (n = e, e = t), n = n || {};
                var r, i, s, o, u, a, f, l, c = v.ajaxSetup({}, n), h = c.context || c, p = h !== c && (h.nodeType || h instanceof v) ? v(h) : v.event, d = v.Deferred(), m = v.Callbacks("once memory"), g = c.statusCode || {}, b = {}, w = {}, E = 0, S = "canceled", x = {
                    readyState : 0,
                    setRequestHeader : function (e, t) {
                        if(!E){
                            var n = e.toLowerCase();
                            e = w[n] = w[n] || e, b[e] = t
                        }
                        return this
                    },
                    getAllResponseHeaders : function () {
                        return E === 2 ? i : null
                    },
                    getResponseHeader : function (e) {
                        var n;
                        if(E === 2){
                            if(!s){
                                s = {};
                                while( n = pn.exec(i) ){
                                    s[n[1].toLowerCase()] = n[2]
                                }
                            }
                            n = s[e.toLowerCase()]
                        }
                        return n === t ? null : n
                    },
                    overrideMimeType : function (e) {
                        return E || (c.mimeType = e), this
                    },
                    abort : function (e) {
                        return e = e || S, o && o.abort(e), T(0, e), this
                    }
                };
                d.promise(x), x.success = x.done, x.error = x.fail, x.complete = m.add, x.statusCode = function (e) {
                    if(e){
                        var t;
                        if(E < 2){
                            for( t in e ){
                                g[t] = [g[t], e[t]]
                            }
                        }else{
                            t = e[x.status], x.always(t)
                        }
                    }
                    return this
                }, c.url = ((e || c.url) + "").replace(hn, "").replace(mn, ln[1] + "//"), c.dataTypes = v.trim(c.dataType || "*").toLowerCase().split(y), c.crossDomain == null && (a = wn.exec(c.url.toLowerCase()), c.crossDomain = !(!a || a[1] === ln[1] && (a[2] === ln[2] && (a[3] || (a[1] === "http:" ? 80 : 443)) == (ln[3] || (ln[1] === "http:" ? 80 : 443))))), c.data && (c.processData && (typeof c.data != "string" && (c.data = v.param(c.data, c.traditional)))), kn(Sn, c, n, x);
                if(E === 2){
                    return x
                }
                f = c.global, c.type = c.type.toUpperCase(), c.hasContent = !vn.test(c.type), f && (v.active++ === 0 && v.event.trigger("ajaxStart"));
                if(!c.hasContent){
                    c.data && (c.url += (gn.test(c.url) ? "&" : "?") + c.data, delete c.data), r = c.url;
                    if(c.cache === !1){
                        var N = v.now(), C = c.url.replace(bn, "$1_=" + N);
                        c.url = C + (C === c.url ? (gn.test(c.url) ? "&" : "?") + "_=" + N : "")
                    }
                }
                (c.data && (c.hasContent && c.contentType !== !1) || n.contentType) && x.setRequestHeader("Content-Type", c.contentType), c.ifModified && (r = r || c.url, v.lastModified[r] && x.setRequestHeader("If-Modified-Since", v.lastModified[r]), v.etag[r] && x.setRequestHeader("If-None-Match", v.etag[r])), x.setRequestHeader("Accept", c.dataTypes[0] && c.accepts[c.dataTypes[0]] ? c.accepts[c.dataTypes[0]] + (c.dataTypes[0] !== "*" ? ", " + Tn + "; q=0.01" : "") : c.accepts["*"]);
                for( l in c.headers ){
                    x.setRequestHeader(l, c.headers[l])
                }
                if(!c.beforeSend || c.beforeSend.call(h, x, c) !== !1 && E !== 2){
                    S = "abort";
                    for( l in {success : 1, error : 1, complete : 1} ){
                        x[l](c[l])
                    }
                    o = kn(xn, c, n, x);
                    if(!o){
                        T(-1, "No Transport")
                    }else{
                        x.readyState = 1, f && p.trigger("ajaxSend", [x, c]), c.async && (c.timeout > 0 && (u = setTimeout(function () {
                            x.abort("timeout")
                        }, c.timeout)));
                        try{
                            E = 1, o.send(b, T)
                        }catch(k){
                            if(!(E < 2)){
                                throw k
                            }
                            T(-1, k)
                        }
                    }
                    return x
                }
                return x.abort()
            },
            active : 0,
            lastModified : {},
            etag : {}
        });
        var Mn = [], _n = /\?/, Dn = /(=)\?(?=&|$)|\?\?/, Pn = v.now();
        v.ajaxSetup({
            jsonp : "callback", jsonpCallback : function () {
                var e = Mn.pop() || v.expando + "_" + Pn++;
                return this[e] = !0, e
            }
        }), v.ajaxPrefilter("json jsonp", function (n, r, i) {
            var s, o, u, a = n.data, f = n.url, l = n.jsonp !== !1, c = l && Dn.test(f), h = l && (!c && (typeof a == "string" && (!(n.contentType || "").indexOf("application/x-www-form-urlencoded") && Dn.test(a))));
            if(n.dataTypes[0] === "jsonp" || (c || h)){
                return s = n.jsonpCallback = v.isFunction(n.jsonpCallback) ? n.jsonpCallback() : n.jsonpCallback, o = e[s], c ? n.url = f.replace(Dn, "$1" + s) : h ? n.data = a.replace(Dn, "$1" + s) : l && (n.url += (_n.test(f) ? "&" : "?") + n.jsonp + "=" + s), n.converters["script json"] = function () {
                    return u || v.error(s + " was not called"), u[0]
                }, n.dataTypes[0] = "json", e[s] = function () {
                    u = arguments
                }, i.always(function () {
                    e[s] = o, n[s] && (n.jsonpCallback = r.jsonpCallback, Mn.push(s)), u && (v.isFunction(o) && o(u[0])), u = o = t
                }), "script"
            }
        }), v.ajaxSetup({
            accepts : {script : "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},
            contents : {script : /javascript|ecmascript/},
            converters : {
                "text script" : function (e) {
                    return v.globalEval(e), e
                }
            }
        }), v.ajaxPrefilter("script", function (e) {
            e.cache === t && (e.cache = !1), e.crossDomain && (e.type = "GET", e.global = !1)
        }), v.ajaxTransport("script", function (e) {
            if(e.crossDomain){
                var n, r = i.head || (i.getElementsByTagName("head")[0] || i.documentElement);
                return {
                    send : function (s, o) {
                        n = i.createElement("script"), n.async = "async", e.scriptCharset && (n.charset = e.scriptCharset), n.src = e.url, n.onload = n.onreadystatechange = function (e, i) {
                            if(i || (!n.readyState || /loaded|complete/.test(n.readyState))){
                                n.onload = n.onreadystatechange = null, r && (n.parentNode && r.removeChild(n)), n = t, i || o(200, "success")
                            }
                        }, r.insertBefore(n, r.firstChild)
                    }, abort : function () {
                        n && n.onload(0, 1)
                    }
                }
            }
        });
        var Hn, Bn     = e.ActiveXObject ? function () {
                for( var e in Hn ){
                    Hn[e](0, 1)
                }
            } : !1, jn = 0;
        v.ajaxSettings.xhr = e.ActiveXObject ? function () {
                return !this.isLocal && Fn() || In()
            } : Fn, function (e) {
            v.extend(v.support, {ajax : !!e, cors : !!e && "withCredentials" in e})
        }(v.ajaxSettings.xhr()), v.support.ajax && v.ajaxTransport(function (n) {
            if(!n.crossDomain || v.support.cors){
                var r;
                return {
                    send : function (i, s) {
                        var o, u, a = n.xhr();
                        n.username ? a.open(n.type, n.url, n.async, n.username, n.password) : a.open(n.type, n.url, n.async);
                        if(n.xhrFields){
                            for( u in n.xhrFields ){
                                a[u] = n.xhrFields[u]
                            }
                        }
                        n.mimeType && (a.overrideMimeType && a.overrideMimeType(n.mimeType)), !n.crossDomain && (!i["X-Requested-With"] && (i["X-Requested-With"] = "XMLHttpRequest"));
                        try{
                            for( u in i ){
                                a.setRequestHeader(u, i[u])
                            }
                        }catch(f){
                        }
                        a.send(n.hasContent && n.data || null), r = function (e, i) {
                            var u, f, l, c, h;
                            try{
                                if(r && (i || a.readyState === 4)){
                                    r = t, o && (a.onreadystatechange = v.noop, Bn && delete Hn[o]);
                                    if(i){
                                        a.readyState !== 4 && a.abort()
                                    }else{
                                        u = a.status, l = a.getAllResponseHeaders(), c = {}, h = a.responseXML, h && (h.documentElement && (c.xml = h));
                                        try{
                                            c.text = a.responseText
                                        }catch(p){
                                        }
                                        try{
                                            f = a.statusText
                                        }catch(p){
                                            f = ""
                                        }
                                        !u && (n.isLocal && !n.crossDomain) ? u = c.text ? 200 : 404 : u === 1223 && (u = 204)
                                    }
                                }
                            }catch(d){
                                i || s(-1, d)
                            }
                            c && s(u, f, c, l)
                        }, n.async ? a.readyState === 4 ? setTimeout(r, 0) : (o = ++jn, Bn && (Hn || (Hn = {}, v(e).unload(Bn)), Hn[o] = r), a.onreadystatechange = r) : r()
                    }, abort : function () {
                        r && r(0, 1)
                    }
                }
            }
        });
        var qn, Rn, Un = /^(?:toggle|show|hide)$/, zn = new RegExp("^(?:([-+])=|)(" + m + ")([a-z%]*)$", "i"), Wn = /queueHooks$/, Xn = [Gn], Vn = {
            "*" : [function (e, t) {
                var n, r, i = this.createTween(e, t), s = zn.exec(t), o = i.cur(), u = +o || 0, a = 1, f = 20;
                if(s){
                    n = +s[2], r = s[3] || (v.cssNumber[e] ? "" : "px");
                    if(r !== "px" && u){
                        u = v.css(i.elem, e, !0) || (n || 1);
                        do{
                            a = a || ".5", u /= a, v.style(i.elem, e, u + r)
                        }while( a !== (a = i.cur() / o) && (a !== 1 && --f) )
                    }
                    i.unit = r, i.start = u, i.end = s[1] ? u + (s[1] + 1) * n : n
                }
                return i
            }]
        };
        v.Animation = v.extend(Kn, {
            tweener : function (e, t) {
                v.isFunction(e) ? (t = e, e = ["*"]) : e= e.split(" ");
                var n, r = 0, i = e.length;
                for( ; r < i; r++ ){
                    n = e[r], Vn[n] = Vn[n] || [], Vn[n].unshift(t)
                }
            }, prefilter : function (e, t) {
                t ? Xn.unshift(e) : Xn.push(e)
            }
        }), v.Tween = Yn, Yn.prototype = {
            constructor : Yn, init : function (e, t, n, r, i, s) {
                this.elem = e, this.prop = n, this.easing = i || "swing", this.options = t, this.start = this.now = this.cur(), this.end = r, this.unit = s || (v.cssNumber[n] ? "" : "px")
            }, cur : function () {
                var e = Yn.propHooks[this.prop];
                return e && e.get ? e.get(this) : Yn.propHooks._default.get(this)
            }, run : function (e) {
                var t, n = Yn.propHooks[this.prop];
                return this.options.duration ? this.pos = t = v.easing[this.easing](e, this.options.duration * e, 0, 1, this.options.duration) : this.pos = t = e, this.now = (this.end - this.start) * t + this.start, this.options.step && this.options.step.call(this.elem, this.now, this), n && n.set ? n.set(this) : Yn.propHooks._default.set(this), this
            }
        }, Yn.prototype.init.prototype = Yn.prototype, Yn.propHooks = {
            _default : {
                get : function (e) {
                    var t;
                    return e.elem[e.prop] == null || !!e.elem.style && e.elem.style[e.prop] != null ? (t = v.css(e.elem, e.prop, !1, ""), !t || t === "auto" ? 0 : t) : e.elem[e.prop]
                }, set : function (e) {
                    v.fx.step[e.prop] ? v.fx.step[e.prop](e) : e.elem.style && (e.elem.style[v.cssProps[e.prop]] != null || v.cssHooks[e.prop]) ? v.style(e.elem, e.prop, e.now + e.unit) : e.elem[e.prop] = e.now
                }
            }
        }, Yn.propHooks.scrollTop = Yn.propHooks.scrollLeft = {
            set : function (e) {
                e.elem.nodeType && (e.elem.parentNode && (e.elem[e.prop] = e.now))
            }
        }, v.each(["toggle", "show", "hide"], function (e, t) {
            var n   = v.fn[t];
            v.fn[t] = function (r, i, s) {
                return r == null || (typeof r == "boolean" || !e && (v.isFunction(r) && v.isFunction(i))) ? n.apply(this, arguments) : this.animate(Zn(t, !0), r, i, s)
            }
        }), v.fn.extend({
            fadeTo : function (e, t, n, r) {
                return this.filter(Gt).css("opacity", 0).show().end().animate({opacity : t}, e, n, r)
            }, animate : function (e, t, n, r) {
                var i = v.isEmptyObject(e), s = v.speed(t, n, r), o = function () {
                    var t = Kn(this, v.extend({}, e), s);
                    i && t.stop(!0)
                };
                return i || s.queue === !1 ? this.each(o) : this.queue(s.queue, o)
            }, stop : function (e, n, r) {
                var i = function (e) {
                    var t = e.stop;
                    delete e.stop, t(r)
                };
                return typeof e != "string" && (r = n, n = e, e = t), n && (e !== !1 && this.queue(e || "fx", [])), this.each(function () {
                    var t = !0, n = e != null && e + "queueHooks", s = v.timers, o = v._data(this);
                    if(n){
                        o[n] && (o[n].stop && i(o[n]))
                    }else{
                        for( n in o ){
                            o[n] && (o[n].stop && (Wn.test(n) && i(o[n])))
                        }
                    }
                    for( n = s.length; n--; ){
                        s[n].elem === this && ((e == null || s[n].queue === e) && (s[n].anim.stop(r), t = !1, s.splice(n, 1)))
                    }
                    (t || !r) && v.dequeue(this, e)
                })
            }
        }), v.each({
            slideDown : Zn("show"),
            slideUp : Zn("hide"),
            slideToggle : Zn("toggle"),
            fadeIn : {opacity : "show"},
            fadeOut : {opacity : "hide"},
            fadeToggle : {opacity : "toggle"}
        }, function (e, t) {
            v.fn[e] = function (e, n, r) {
                return this.animate(t, e, n, r)
            }
        }), v.speed = function (e, t, n) {
            var r      = e && typeof e == "object" ? v.extend({}, e) : {
                    complete : n || (!n && t || v.isFunction(e) && e),
                    duration : e,
                    easing : n && t || t && (!v.isFunction(t) && t)
                };
            r.duration = v.fx.off ? 0 : typeof r.duration == "number" ? r.duration : r.duration in v.fx.speeds ? v.fx.speeds[r.duration] : v.fx.speeds._default;
            if(r.queue == null || r.queue === !0){
                r.queue = "fx"
            }
            return r.old = r.complete, r.complete = function () {
                v.isFunction(r.old) && r.old.call(this), r.queue && v.dequeue(this, r.queue)
            }, r
        }, v.easing = {
            linear : function (e) {
                return e
            }, swing : function (e) {
                return 0.5 - Math.cos(e * Math.PI) / 2
            }
        }, v.timers = [], v.fx = Yn.prototype.init, v.fx.tick = function () {
            var e, n = v.timers, r = 0;
            qn       = v.now();
            for( ; r < n.length; r++ ){
                e = n[r], !e() && (n[r] === e && n.splice(r--, 1))
            }
            n.length || v.fx.stop(), qn = t
        }, v.fx.timer = function (e) {
            e() && (v.timers.push(e) && (!Rn && (Rn = setInterval(v.fx.tick, v.fx.interval))))
        }, v.fx.interval = 13, v.fx.stop = function () {
            clearInterval(Rn), Rn = null
        }, v.fx.speeds = {
            slow : 600,
            fast : 200,
            _default : 400
        }, v.fx.step = {}, v.expr && (v.expr.filters && (v.expr.filters.animated = function (e) {
            return v.grep(v.timers, function (t) {
                return e === t.elem
            }).length
        }));
        var er = /^(?:body|html)$/i;
        v.fn.offset = function (e) {
            if(arguments.length){
                return e === t ? this : this.each(function (t) {
                        v.offset.setOffset(this, e, t)
                    })
            }
            var n, r, i, s, o, u, a, f = {top : 0, left : 0}, l = this[0], c = l && l.ownerDocument;
            if(!c){
                return
            }
            return (r = c.body) === l ? v.offset.bodyOffset(l) : (n = c.documentElement, v.contains(n, l) ? (typeof l.getBoundingClientRect != "undefined" && (f = l.getBoundingClientRect()), i = tr(c), s = n.clientTop || (r.clientTop || 0), o = n.clientLeft || (r.clientLeft || 0), u = i.pageYOffset || n.scrollTop, a = i.pageXOffset || n.scrollLeft, {
                        top : f.top + u - s,
                        left : f.left + a - o
                    }) : f)
        }, v.offset = {
            bodyOffset : function (e) {
                var t = e.offsetTop, n = e.offsetLeft;
                return v.support.doesNotIncludeMarginInBodyOffset && (t += parseFloat(v.css(e, "marginTop")) || 0, n += parseFloat(v.css(e, "marginLeft")) || 0), {
                    top : t,
                    left : n
                }
            }, setOffset : function (e, t, n) {
                var r = v.css(e, "position");
                r === "static" && (e.style.position = "relative");
                var i = v(e), s = i.offset(), o = v.css(e, "top"), u = v.css(e, "left"), a = (r === "absolute" || r === "fixed") && v.inArray("auto", [o, u]) > -1, f = {}, l = {}, c, h;
                a ? (l = i.position(), c = l.top, h = l.left) : (c = parseFloat(o) || 0, h = parseFloat(u) || 0), v.isFunction(t) && (t = t.call(e, n, s)), t.top != null && (f.top = t.top - s.top + c), t.left != null && (f.left = t.left - s.left + h), "using" in t ? t.using.call(e, f) : i.css(f)
            }
        }, v.fn.extend({
            position : function () {
                if(!this[0]){
                    return
                }
                var e = this[0], t = this.offsetParent(), n = this.offset(), r = er.test(t[0].nodeName) ? {
                        top : 0,
                        left : 0
                    } : t.offset();
                return n.top -= parseFloat(v.css(e, "marginTop")) || 0, n.left -= parseFloat(v.css(e, "marginLeft")) || 0, r.top += parseFloat(v.css(t[0], "borderTopWidth")) || 0, r.left += parseFloat(v.css(t[0], "borderLeftWidth")) || 0, {
                    top : n.top - r.top,
                    left : n.left - r.left
                }
            }, offsetParent : function () {
                return this.map(function () {
                    var e = this.offsetParent || i.body;
                    while( e && (!er.test(e.nodeName) && v.css(e, "position") === "static") ){
                        e = e.offsetParent
                    }
                    return e || i.body
                })
            }
        }), v.each({scrollLeft : "pageXOffset", scrollTop : "pageYOffset"}, function (e, n) {
            var r   = /Y/.test(n);
            v.fn[e] = function (i) {
                return v.access(this, function (e, i, s) {
                    var o = tr(e);
                    if(s === t){
                        return o ? n in o ? o[n] : o.document.documentElement[i] : e[i]
                    }
                    o ? o.scrollTo(r ? v(o).scrollLeft() : s, r ? s : v(o).scrollTop()) : e[i] = s
                }, e, i, arguments.length, null)
            }
        }), v.each({Height : "height", Width : "width"}, function (e, n) {
            v.each({padding : "inner" + e, content : n, "" : "outer" + e}, function (r, i) {
                v.fn[i] = function (i, s) {
                    var o = arguments.length && (r || typeof i != "boolean"), u = r || (i === !0 || s === !0 ? "margin" : "border");
                    return v.access(this, function (n, r, i) {
                        var s;
                        return v.isWindow(n) ? n.document.documentElement["client" + e] : n.nodeType === 9 ? (s = n.documentElement, Math.max(n.body["scroll" + e], s["scroll" + e], n.body["offset" + e], s["offset" + e], s["client" + e])) : i === t ? v.css(n, r, i, u) : v.style(n, r, i, u)
                    }, n, o ? i : t, o, null)
                }
            })
        }), e.jQuery = e.$ = v, typeof define == "function" && (define.amd && (define.amd.jQuery && define("jquery", [], function () {
            return v
        })))
    })(window);
    return g_win.jQuery
});
if(typeof JSON !== "object"){
    JSON = {}
}
(function () {
    function f(n) {
        return n < 10 ? "0" + n : n
    }

    if(typeof Date.prototype.toJSON !== "function"){
        Date.prototype.toJSON   = function () {
            return isFinite(this.valueOf()) ? this.getUTCFullYear() + "-" + f(this.getUTCMonth() + 1) + "-" + f(this.getUTCDate()) + "T" + f(this.getUTCHours()) + ":" + f(this.getUTCMinutes()) + ":" + f(this.getUTCSeconds()) + "Z" : null
        };
        String.prototype.toJSON = Number.prototype.toJSON = Boolean.prototype.toJSON = function () {
            return this.valueOf()
        }
    }
    var cx, escapable, gap, indent, meta, rep;

    function quote(string) {
        escapable.lastIndex = 0;
        return escapable.test(string) ? '"' + string.replace(escapable, function (a) {
                var c = meta[a];
                return typeof c === "string" ? c : "\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4)
            }) + '"' : '"' + string + '"'
    }

    function str(key, holder) {
        var i, k, v, length, mind = gap, partial, value = holder[key];
        if(value && (typeof value === "object" && typeof value.toJSON === "function")){
            value = value.toJSON(key)
        }
        if(typeof rep === "function"){
            value = rep.call(holder, key, value)
        }
        switch(typeof value){
            case"string":
                return quote(value);
            case"number":
                return isFinite(value) ? String(value) : "null";
            case"boolean":
            case"null":
                return String(value);
            case"object":
                if(!value){
                    return "null"
                }
                gap += indent;
                partial = [];
                if(Object.prototype.toString.apply(value) === "[object Array]"){
                    length = value.length;
                    for( i = 0; i < length; i += 1 ){
                        partial[i] = str(i, value) || "null"
                    }
                    v   = partial.length === 0 ? "[]" : gap ? "[\n" + gap + partial.join(",\n" + gap) + "\n" + mind + "]" : "[" + partial.join(",") + "]";
                    gap = mind;
                    return v
                }
                if(rep && typeof rep === "object"){
                    length = rep.length;
                    for( i = 0; i < length; i += 1 ){
                        if(typeof rep[i] === "string"){
                            k = rep[i];
                            v = str(k, value);
                            if(v){
                                partial.push(quote(k) + (gap ? ": " : ":") + v)
                            }
                        }
                    }
                }else{
                    for( k in value ){
                        if(Object.prototype.hasOwnProperty.call(value, k)){
                            v = str(k, value);
                            if(v){
                                partial.push(quote(k) + (gap ? ": " : ":") + v)
                            }
                        }
                    }
                }
                v   = partial.length === 0 ? "{}" : gap ? "{\n" + gap + partial.join(",\n" + gap) + "\n" + mind + "}" : "{" + partial.join(",") + "}";
                gap = mind;
                return v
        }
    }

    if(typeof JSON.stringify !== "function"){
        escapable      = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;
        meta           = {
            "\b" : "\\b",
            "\t" : "\\t",
            "\n" : "\\n",
            "\f" : "\\f",
            "\r" : "\\r",
            '"' : '\\"',
            "\\" : "\\\\"
        };
        JSON.stringify = function (value, replacer, space) {
            var i;
            gap    = "";
            indent = "";
            if(typeof space === "number"){
                for( i = 0; i < space; i += 1 ){
                    indent += " "
                }
            }else{
                if(typeof space === "string"){
                    indent = space
                }
            }
            rep = replacer;
            if(replacer && (typeof replacer !== "function" && (typeof replacer !== "object" || typeof replacer.length !== "number"))){
                throw new Error("JSON.stringify")
            }
            return str("", {"" : value})
        }
    }
    if(typeof JSON.parse !== "function"){
        cx         = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;
        JSON.parse = function (text, reviver) {
            var j;

            function walk(holder, key) {
                var k, v, value = holder[key];
                if(value && typeof value === "object"){
                    for( k in value ){
                        if(Object.prototype.hasOwnProperty.call(value, k)){
                            v = walk(value, k);
                            if(v !== undefined){
                                value[k] = v
                            }else{
                                delete value[k]
                            }
                        }
                    }
                }
                return reviver.call(holder, key, value)
            }

            text         = String(text);
            cx.lastIndex = 0;
            if(cx.test(text)){
                text = text.replace(cx, function (a) {
                    return "\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4)
                })
            }
            if(/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, ""))){
                j = eval("(" + text + ")");
                return typeof reviver === "function" ? walk({"" : j}, "") : j
            }
            throw new SyntaxError("JSON.parse")
        }
    }
})();
qcVideo("JSON", function () {
    return JSON
});
function ksort(inputArr, sort_flags) {
    var tmp_arr = {}, keys = [], sorter, i, k, that = this, strictForIn = false, populateArr = {};
    switch(sort_flags){
        case"SORT_STRING":
            sorter = function (a, b) {
                return that.strnatcmp(a, b)
            };
            break;
        case"SORT_LOCALE_STRING":
            var loc = this.i18n_loc_get_default();
            sorter  = this.php_js.i18nLocales[loc].sorting;
            break;
        case"SORT_NUMERIC":
            sorter = function (a, b) {
                return a + 0 - (b + 0)
            };
            break;
        default:
            sorter = function (a, b) {
                var aFloat = parseFloat(a), bFloat = parseFloat(b), aNumeric = aFloat + "" === a, bNumeric = bFloat + "" === b;
                if(aNumeric && bNumeric){
                    return aFloat > bFloat ? 1 : aFloat < bFloat ? -1 : 0
                }else{
                    if(aNumeric && !bNumeric){
                        return 1
                    }else{
                        if(!aNumeric && bNumeric){
                            return -1
                        }
                    }
                }
                return a > b ? 1 : a < b ? -1 : 0
            };
            break
    }
    for( k in inputArr ){
        if(inputArr.hasOwnProperty(k)){
            keys.push(k)
        }
    }
    keys.sort(sorter);
    this.php_js     = this.php_js || {};
    this.php_js.ini = this.php_js.ini || {};
    strictForIn     = this.php_js.ini["phpjs.strictForIn"] && (this.php_js.ini["phpjs.strictForIn"].local_value && this.php_js.ini["phpjs.strictForIn"].local_value !== "off");
    populateArr     = strictForIn ? inputArr : populateArr;
    for( i = 0; i < keys.length; i++ ){
        k          = keys[i];
        tmp_arr[k] = inputArr[k];
        if(strictForIn){
            delete inputArr[k]
        }
    }
    for( i in tmp_arr ){
        if(tmp_arr.hasOwnProperty(i)){
            populateArr[i] = tmp_arr[i]
        }
    }
    return strictForIn || populateArr
}
!function (e, t) {
    function n(e, t) {
        for( var n, i = [], r = 0; r < e.length; ++r ){
            if(n = s[e[r]] || o(e[r]), !n){
                throw"module definition dependecy not found: " + e[r]
            }
            i.push(n)
        }
        t.apply(null, i)
    }

    function i(e, i, r) {
        if("string" != typeof e){
            throw"invalid module definition, module id must be defined and be a string"
        }
        if(i === t){
            throw"invalid module definition, dependencies must be specified"
        }
        if(r === t){
            throw"invalid module definition, definition function must be specified"
        }
        n(i, function () {
            s[e] = r.apply(null, arguments)
        })
    }

    function r(e) {
        return !!s[e]
    }

    function o(t) {
        for( var n = e, i = t.split(/[.\/]/), r = 0; r < i.length; ++r ){
            if(!n[i[r]]){
                return
            }
            n = n[i[r]]
        }
        return n
    }

    function a(n) {
        for( var i = 0; i < n.length; i++ ){
            for( var r = e, o = n[i], a = o.split(/[.\/]/), u = 0; u < a.length - 1; ++u ){
                r[a[u]] === t && (r[a[u]] = {}), r = r[a[u]]
            }
            r[a[a.length - 1]] = s[o]
        }
    }

    var s = {}, u = "moxie/core/utils/Basic", c = "moxie/core/I18n", l = "moxie/core/utils/Mime", d = "moxie/core/utils/Env", f = "moxie/core/utils/Dom", h = "moxie/core/Exceptions", p = "moxie/core/EventTarget", m = "moxie/core/utils/Encode", g = "moxie/runtime/Runtime", v = "moxie/runtime/RuntimeClient", y = "moxie/file/Blob", w = "moxie/file/File", E = "moxie/file/FileInput", _ = "moxie/file/FileDrop", x = "moxie/runtime/RuntimeTarget", b = "moxie/file/FileReader", R = "moxie/core/utils/Url", T = "moxie/file/FileReaderSync", A = "moxie/xhr/FormData", S = "moxie/xhr/XMLHttpRequest", O = "moxie/runtime/Transporter", I = "moxie/image/Image", D = "moxie/runtime/html5/Runtime", N = "moxie/runtime/html5/file/Blob", L = "moxie/core/utils/Events", M = "moxie/runtime/html5/file/FileInput", C = "moxie/runtime/html5/file/FileDrop", F = "moxie/runtime/html5/file/FileReader", H = "moxie/runtime/html5/xhr/XMLHttpRequest", P = "moxie/runtime/html5/utils/BinaryReader", k = "moxie/runtime/html5/image/JPEGHeaders", U = "moxie/runtime/html5/image/ExifParser", B = "moxie/runtime/html5/image/JPEG", z = "moxie/runtime/html5/image/PNG", G = "moxie/runtime/html5/image/ImageInfo", q = "moxie/runtime/html5/image/MegaPixel", X = "moxie/runtime/html5/image/Image", j = "moxie/runtime/flash/Runtime", V = "moxie/runtime/flash/file/Blob", W = "moxie/runtime/flash/file/FileInput", Y = "moxie/runtime/flash/file/FileReader", $ = "moxie/runtime/flash/file/FileReaderSync", J = "moxie/runtime/flash/xhr/XMLHttpRequest", Z = "moxie/runtime/flash/runtime/Transporter", K = "moxie/runtime/flash/image/Image", Q = "moxie/runtime/silverlight/Runtime", et = "moxie/runtime/silverlight/file/Blob", tt = "moxie/runtime/silverlight/file/FileInput", nt = "moxie/runtime/silverlight/file/FileDrop", it = "moxie/runtime/silverlight/file/FileReader", rt = "moxie/runtime/silverlight/file/FileReaderSync", ot = "moxie/runtime/silverlight/xhr/XMLHttpRequest", at = "moxie/runtime/silverlight/runtime/Transporter", st = "moxie/runtime/silverlight/image/Image", ut = "moxie/runtime/html4/Runtime", ct = "moxie/runtime/html4/file/FileInput", lt = "moxie/runtime/html4/file/FileReader", dt = "moxie/runtime/html4/xhr/XMLHttpRequest", ft = "moxie/runtime/html4/image/Image";
    i(u, [], function () {
        var e  = function (e) {
            var t;
            return e === t ? "undefined" : null === e ? "null" : e.nodeType ? "node" : {}.toString.call(e).match(/\s([a-z|A-Z]+)/)[1].toLowerCase()
        }, t   = function (i) {
            var r;
            return n(arguments, function (o, s) {
                s > 0 && n(o, function (n, o) {
                    n !== r && (e(i[o]) === e(n) && ~a(e(n), ["array", "object"]) ? t(i[o], n) : i[o] = n)
                })
            }), i
        }, n   = function (e, t) {
            var n, i, r, o;
            if(e){
                try{
                    n = e.length
                }catch(a){
                    n = o
                }
                if(n === o){
                    for( i in e ){
                        if(e.hasOwnProperty(i) && t(e[i], i) === !1){
                            return
                        }
                    }
                }else{
                    for( r = 0; n > r; r++ ){
                        if(t(e[r], r) === !1){
                            return
                        }
                    }
                }
            }
        }, i   = function (t) {
            var n;
            if(!t || "object" !== e(t)){
                return !0
            }
            for( n in t ){
                return !1
            }
            return !0
        }, r   = function (t, n) {
            function i(r) {
                "function" === e(t[r]) && t[r](function (e) {
                    ++r < o && !e ? i(r) : n(e)
                })
            }

            var r = 0, o = t.length;
            "function" !== e(n) && (n = function () {
            }), t && t.length || n(), i(r)
        }, o   = function (e, t) {
            var i = 0, r = e.length, o = new Array(r);
            n(e, function (e, n) {
                e(function (e) {
                    if(e){
                        return t(e)
                    }
                    var a = [].slice.call(arguments);
                    a.shift(), o[n] = a, i++, i === r && (o.unshift(null), t.apply(this, o))
                })
            })
        }, a   = function (e, t) {
            if(t){
                if(Array.prototype.indexOf){
                    return Array.prototype.indexOf.call(t, e)
                }
                for( var n = 0, i = t.length; i > n; n++ ){
                    if(t[n] === e){
                        return n
                    }
                }
            }
            return -1
        }, s   = function (t, n) {
            var i = [];
            "array" !== e(t) && (t = [t]), "array" !== e(n) && (n = [n]);
            for( var r in t ){
                -1 === a(t[r], n) && i.push(t[r])
            }
            return i.length ? i : !1
        }, u   = function (e, t) {
            var i = [];
            return n(e, function (e) {
                -1 !== a(e, t) && i.push(e)
            }), i.length ? i : null
        }, c   = function (e) {
            var t, n = [];
            for( t = 0; t < e.length; t++ ){
                n[t] = e[t]
            }
            return n
        }, l   = function () {
            var e = 0;
            return function (t) {
                var n = (new Date).getTime().toString(32), i;
                for( i = 0; 5 > i; i++ ){
                    n += Math.floor(65535 * Math.random()).toString(32)
                }
                return (t || "o_") + n + (e++).toString(32)
            }
        }(), d = function (e) {
            return e ? String.prototype.trim ? String.prototype.trim.call(e) : e.toString().replace(/^\s*/, "").replace(/\s*$/, "") : e
        }, f   = function (e) {
            if("string" != typeof e){
                return e
            }
            var t = {t : 1099511627776, g : 1073741824, m : 1048576, k : 1024}, n;
            return e = /^([0-9]+)([mgk]?)$/.exec(e.toLowerCase().replace(/[^0-9mkg]/g, "")), n = e[2], e = +e[1], t.hasOwnProperty(n) && (e *= t[n]), e
        };
        return {
            guid : l,
            typeOf : e,
            extend : t,
            each : n,
            isEmptyObj : i,
            inSeries : r,
            inParallel : o,
            inArray : a,
            arrayDiff : s,
            arrayIntersect : u,
            toArray : c,
            trim : d,
            parseSizeStr : f
        }
    }), i(c, [u], function (e) {
        var t = {};
        return {
            addI18n : function (n) {
                return e.extend(t, n)
            }, translate : function (e) {
                return t[e] || e
            }, _ : function (e) {
                return this.translate(e)
            }, sprintf : function (t) {
                var n = [].slice.call(arguments, 1);
                return t.replace(/%[a-z]/g, function () {
                    var t = n.shift();
                    return "undefined" !== e.typeOf(t) ? t : ""
                })
            }
        }
    }), i(l, [u, c], function (e, t) {
        var n = "application/msword,doc dot,application/pdf,pdf,application/pgp-signature,pgp,application/postscript,ps ai eps,application/rtf,rtf,application/vnd.ms-excel,xls xlb,application/vnd.ms-powerpoint,ppt pps pot,application/zip,zip,application/x-shockwave-flash,swf swfl,application/vnd.openxmlformats-officedocument.wordprocessingml.document,docx,application/vnd.openxmlformats-officedocument.wordprocessingml.template,dotx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,xlsx,application/vnd.openxmlformats-officedocument.presentationml.presentation,pptx,application/vnd.openxmlformats-officedocument.presentationml.template,potx,application/vnd.openxmlformats-officedocument.presentationml.slideshow,ppsx,application/x-javascript,js,application/json,json,audio/mpeg,mp3 mpga mpega mp2,audio/x-wav,wav,audio/x-m4a,m4a,audio/ogg,oga ogg,audio/aiff,aiff aif,audio/flac,flac,audio/aac,aac,audio/ac3,ac3,audio/x-ms-wma,wma,image/bmp,bmp,image/gif,gif,image/jpeg,jpg jpeg jpe,image/photoshop,psd,image/png,png,image/svg+xml,svg svgz,image/tiff,tiff tif,text/plain,asc txt text diff log,text/html,htm html xhtml,text/css,css,text/csv,csv,text/rtf,rtf,video/mpeg,mpeg mpg mpe m2v,video/quicktime,qt mov,video/mp4,mp4,video/x-m4v,m4v,video/x-flv,flv,video/x-ms-wmv,wmv,video/avi,avi,video/webm,webm,video/3gpp,3gpp 3gp,video/3gpp2,3g2,video/vnd.rn-realvideo,rv,video/ogg,ogv,video/x-matroska,mkv,application/vnd.oasis.opendocument.formula-template,otf,application/octet-stream,exe", i = {
            mimes : {},
            extensions : {},
            addMimeType : function (e) {
                var t = e.split(/,/), n, i, r;
                for( n = 0; n < t.length; n += 2 ){
                    for( r = t[n + 1].split(/ /), i = 0; i < r.length; i++ ){
                        this.mimes[r[i]] = t[n]
                    }
                    this.extensions[t[n]] = r
                }
            },
            extList2mimes : function (t, n) {
                var i = this, r, o, a, s, u = [];
                for( o = 0; o < t.length; o++ ){
                    for( r = t[o].extensions.split(/\s*,\s*/), a = 0; a < r.length; a++ ){
                        if("*" === r[a]){
                            return []
                        }
                        if(s = i.mimes[r[a]]){
                            -1 === e.inArray(s, u) && u.push(s)
                        }else{
                            if(!n || !/^\w+$/.test(r[a])){
                                return []
                            }
                            u.push("." + r[a])
                        }
                    }
                }
                return u
            },
            mimes2exts : function (t) {
                var n = this, i = [];
                return e.each(t, function (t) {
                    if("*" === t){
                        return i = [], !1
                    }
                    var r = t.match(/^(\w+)\/(\*|\w+)$/);
                    r && ("*" === r[2] ? e.each(n.extensions, function (e, t) {
                            (new RegExp("^" + r[1] + "/")).test(t) && [].push.apply(i, n.extensions[t])
                        }) : n.extensions[t] && [].push.apply(i, n.extensions[t]))
                }), i
            },
            mimes2extList : function (n) {
                var i = [], r = [];
                return "string" === e.typeOf(n) && (n = e.trim(n).split(/\s*,\s*/)), r = this.mimes2exts(n), i.push({
                    title : t.translate("Files"),
                    extensions : r.length ? r.join(",") : "*"
                }), i.mimes = n, i
            },
            getFileExtension : function (e) {
                var t = e && e.match(/\.([^.]+)$/);
                return t ? t[1].toLowerCase() : ""
            },
            getFileMime : function (e) {
                return this.mimes[this.getFileExtension(e)] || ""
            }
        };
        return i.addMimeType(n), i
    }), i(d, [u], function (e) {
        function t(e, t, n) {
            var i = 0, r = 0, o = 0, a = {
                dev : -6,
                alpha : -5,
                a : -5,
                beta : -4,
                b : -4,
                RC : -3,
                rc : -3,
                "#" : -2,
                p : 1,
                pl : 1
            }, s  = function (e) {
                return e = ("" + e).replace(/[_\-+]/g, "."), e = e.replace(/([^.\d]+)/g, ".$1.").replace(/\.{2,}/g, "."), e.length ? e.split(".") : [-8]
            }, u  = function (e) {
                return e ? isNaN(e) ? a[e] || -7 : parseInt(e, 10) : 0
            };
            for( e = s(e), t = s(t), r = Math.max(e.length, t.length), i = 0; r > i; i++ ){
                if(e[i] != t[i]){
                    if(e[i] = u(e[i]), t[i] = u(t[i]), e[i] < t[i]){
                        o = -1;
                        break
                    }
                    if(e[i] > t[i]){
                        o = 1;
                        break
                    }
                }
            }
            if(!n){
                return o
            }
            switch(n){
                case">":
                case"gt":
                    return o > 0;
                case">=":
                case"ge":
                    return o >= 0;
                case"<=":
                case"le":
                    return 0 >= o;
                case"==":
                case"=":
                case"eq":
                    return 0 === o;
                case"<>":
                case"!=":
                case"ne":
                    return 0 !== o;
                case"":
                case"<":
                case"lt":
                    return 0 > o;
                default:
                    return null
            }
        }

        var n  = function (e) {
            var t = "", n = "?", i = "function", r = "undefined", o = "object", a = "major", s = "model", u = "name", c = "type", l = "vendor", d = "version", f = "architecture", h = "console", p = "mobile", m = "tablet", g = {
                has : function (e, t) {
                    return -1 !== t.toLowerCase().indexOf(e.toLowerCase())
                }, lowerize : function (e) {
                    return e.toLowerCase()
                }
            }, v  = {
                rgx : function () {
                    for( var t, n = 0, a, s, u, c, l, d, f = arguments; n < f.length; n += 2 ){
                        var h = f[n], p = f[n + 1];
                        if(typeof t === r){
                            t = {};
                            for( u in p ){
                                c = p[u], typeof c === o ? t[c[0]] = e : t[c] = e
                            }
                        }
                        for( a = s = 0; a < h.length; a++ ){
                            if(l = h[a].exec(this.getUA())){
                                for( u = 0; u < p.length; u++ ){
                                    d = l[++s], c = p[u], typeof c === o && c.length > 0 ? 2 == c.length ? t[c[0]] = typeof c[1] == i ? c[1].call(this, d) : c[1] : 3 == c.length ? t[c[0]] = typeof c[1] !== i || c[1].exec && c[1].test ? d ? d.replace(c[1], c[2]) : e : d ? c[1].call(this, d, c[2]) : e : 4 == c.length && (t[c[0]] = d ? c[3].call(this, d.replace(c[1], c[2])) : e) : t[c] = d ? d : e
                                }
                                break
                            }
                        }
                        if(l){
                            break
                        }
                    }
                    return t
                }, str : function (t, i) {
                    for( var r in i ){
                        if(typeof i[r] === o && i[r].length > 0){
                            for( var a = 0; a < i[r].length; a++ ){
                                if(g.has(i[r][a], t)){
                                    return r === n ? e : r
                                }
                            }
                        }else{
                            if(g.has(i[r], t)){
                                return r === n ? e : r
                            }
                        }
                    }
                    return t
                }
            }, y  = {
                browser : {
                    oldsafari : {
                        major : {1 : ["/8", "/1", "/3"], 2 : "/4", "?" : "/"},
                        version : {
                            "1.0" : "/8",
                            "1.2" : "/1",
                            "1.3" : "/3",
                            "2.0" : "/412",
                            "2.0.2" : "/416",
                            "2.0.3" : "/417",
                            "2.0.4" : "/419",
                            "?" : "/"
                        }
                    }
                },
                device : {sprint : {model : {"Evo Shift 4G" : "7373KT"}, vendor : {HTC : "APA", Sprint : "Sprint"}}},
                os : {
                    windows : {
                        version : {
                            ME : "4.90",
                            "NT 3.11" : "NT3.51",
                            "NT 4.0" : "NT4.0",
                            2000 : "NT 5.0",
                            XP : ["NT 5.1", "NT 5.2"],
                            Vista : "NT 6.0",
                            7 : "NT 6.1",
                            8 : "NT 6.2",
                            "8.1" : "NT 6.3",
                            RT : "ARM"
                        }
                    }
                }
            }, w  = {
                browser : [[/(opera\smini)\/((\d+)?[\w\.-]+)/i, /(opera\s[mobiletab]+).+version\/((\d+)?[\w\.-]+)/i, /(opera).+version\/((\d+)?[\w\.]+)/i, /(opera)[\/\s]+((\d+)?[\w\.]+)/i], [u, d, a], [/\s(opr)\/((\d+)?[\w\.]+)/i], [[u, "Opera"], d, a], [/(kindle)\/((\d+)?[\w\.]+)/i, /(lunascape|maxthon|netfront|jasmine|blazer)[\/\s]?((\d+)?[\w\.]+)*/i, /(avant\s|iemobile|slim|baidu)(?:browser)?[\/\s]?((\d+)?[\w\.]*)/i, /(?:ms|\()(ie)\s((\d+)?[\w\.]+)/i, /(rekonq)((?:\/)[\w\.]+)*/i, /(chromium|flock|rockmelt|midori|epiphany|silk|skyfire|ovibrowser|bolt|iron)\/((\d+)?[\w\.-]+)/i], [u, d, a], [/(trident).+rv[:\s]((\d+)?[\w\.]+).+like\sgecko/i], [[u, "IE"], d, a], [/(yabrowser)\/((\d+)?[\w\.]+)/i], [[u, "Yandex"], d, a], [/(comodo_dragon)\/((\d+)?[\w\.]+)/i], [[u, /_/g, " "], d, a], [/(chrome|omniweb|arora|[tizenoka]{5}\s?browser)\/v?((\d+)?[\w\.]+)/i], [u, d, a], [/(dolfin)\/((\d+)?[\w\.]+)/i], [[u, "Dolphin"], d, a], [/((?:android.+)crmo|crios)\/((\d+)?[\w\.]+)/i], [[u, "Chrome"], d, a], [/((?:android.+))version\/((\d+)?[\w\.]+)\smobile\ssafari/i], [[u, "Android Browser"], d, a], [/version\/((\d+)?[\w\.]+).+?mobile\/\w+\s(safari)/i], [d, a, [u, "Mobile Safari"]], [/version\/((\d+)?[\w\.]+).+?(mobile\s?safari|safari)/i], [d, a, u], [/webkit.+?(mobile\s?safari|safari)((\/[\w\.]+))/i], [u, [a, v.str, y.browser.oldsafari.major], [d, v.str, y.browser.oldsafari.version]], [/(konqueror)\/((\d+)?[\w\.]+)/i, /(webkit|khtml)\/((\d+)?[\w\.]+)/i], [u, d, a], [/(navigator|netscape)\/((\d+)?[\w\.-]+)/i], [[u, "Netscape"], d, a], [/(swiftfox)/i, /(icedragon|iceweasel|camino|chimera|fennec|maemo\sbrowser|minimo|conkeror)[\/\s]?((\d+)?[\w\.\+]+)/i, /(firefox|seamonkey|k-meleon|icecat|iceape|firebird|phoenix)\/((\d+)?[\w\.-]+)/i, /(mozilla)\/((\d+)?[\w\.]+).+rv\:.+gecko\/\d+/i, /(uc\s?browser|polaris|lynx|dillo|icab|doris|amaya|w3m|netsurf|qqbrowser)[\/\s]?((\d+)?[\w\.]+)/i, /(links)\s\(((\d+)?[\w\.]+)/i, /(gobrowser)\/?((\d+)?[\w\.]+)*/i, /(ice\s?browser)\/v?((\d+)?[\w\._]+)/i, /(mosaic)[\/\s]((\d+)?[\w\.]+)/i], [u, d, a]],
                engine : [[/(presto)\/([\w\.]+)/i, /(webkit|trident|netfront|netsurf|amaya|lynx|w3m)\/([\w\.]+)/i, /(khtml|tasman|links)[\/\s]\(?([\w\.]+)/i, /(icab)[\/\s]([23]\.[\d\.]+)/i], [u, d], [/rv\:([\w\.]+).*(gecko)/i], [d, u]],
                os : [[/(windows)\snt\s6\.2;\s(arm)/i, /(windows\sphone(?:\sos)*|windows\smobile|windows)[\s\/]?([ntce\d\.\s]+\w)/i], [u, [d, v.str, y.os.windows.version]], [/(win(?=3|9|n)|win\s9x\s)([nt\d\.]+)/i], [[u, "Windows"], [d, v.str, y.os.windows.version]], [/\((bb)(10);/i], [[u, "BlackBerry"], d], [/(blackberry)\w*\/?([\w\.]+)*/i, /(tizen)\/([\w\.]+)/i, /(android|webos|palm\os|qnx|bada|rim\stablet\sos|meego)[\/\s-]?([\w\.]+)*/i], [u, d], [/(symbian\s?os|symbos|s60(?=;))[\/\s-]?([\w\.]+)*/i], [[u, "Symbian"], d], [/mozilla.+\(mobile;.+gecko.+firefox/i], [[u, "Firefox OS"], d], [/(nintendo|playstation)\s([wids3portablevu]+)/i, /(mint)[\/\s\(]?(\w+)*/i, /(joli|[kxln]?ubuntu|debian|[open]*suse|gentoo|arch|slackware|fedora|mandriva|centos|pclinuxos|redhat|zenwalk)[\/\s-]?([\w\.-]+)*/i, /(hurd|linux)\s?([\w\.]+)*/i, /(gnu)\s?([\w\.]+)*/i], [u, d], [/(cros)\s[\w]+\s([\w\.]+\w)/i], [[u, "Chromium OS"], d], [/(sunos)\s?([\w\.]+\d)*/i], [[u, "Solaris"], d], [/\s([frentopc-]{0,4}bsd|dragonfly)\s?([\w\.]+)*/i], [u, d], [/(ip[honead]+)(?:.*os\s*([\w]+)*\slike\smac|;\sopera)/i], [[u, "iOS"], [d, /_/g, "."]], [/(mac\sos\sx)\s?([\w\s\.]+\w)*/i], [u, [d, /_/g, "."]], [/(haiku)\s(\w+)/i, /(aix)\s((\d)(?=\.|\)|\s)[\w\.]*)*/i, /(macintosh|mac(?=_powerpc)|plan\s9|minix|beos|os\/2|amigaos|morphos|risc\sos)/i, /(unix)\s?([\w\.]+)*/i], [u, d]]
            }, E  = function (e) {
                var n = e || (window && (window.navigator && window.navigator.userAgent) ? window.navigator.userAgent : t);
                this.getBrowser = function () {
                    return v.rgx.apply(this, w.browser)
                }, this.getEngine = function () {
                    return v.rgx.apply(this, w.engine)
                }, this.getOS = function () {
                    return v.rgx.apply(this, w.os)
                }, this.getResult = function () {
                    return {
                        ua : this.getUA(),
                        browser : this.getBrowser(),
                        engine : this.getEngine(),
                        os : this.getOS()
                    }
                }, this.getUA = function () {
                    return n
                }, this.setUA = function (e) {
                    return n = e, this
                }, this.setUA(n)
            };
            return (new E).getResult()
        }(), i = function () {
            var t = {
                define_property : function () {
                    return !1
                }(), create_canvas : function () {
                    var e = document.createElement("canvas");
                    return !(!e.getContext || !e.getContext("2d"))
                }(), return_response_type : function (t) {
                    try{
                        if(-1 !== e.inArray(t, ["", "text", "document"])){
                            return !0
                        }
                        if(window.XMLHttpRequest){
                            var n = new XMLHttpRequest;
                            if(n.open("get", "/"), "responseType" in n){
                                return n.responseType = t, n.responseType !== t ? !1 : !0
                            }
                        }
                    }catch(i){
                    }
                    return !1
                }, use_data_uri : function () {
                    var e = new Image;
                    return e.onload = function () {
                        t.use_data_uri = 1 === e.width && 1 === e.height
                    }, setTimeout(function () {
                        e.src = "data:image/gif;base64,R0lGODlhAQABAIAAAP8AAAAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw=="
                    }, 1), !1
                }(), use_data_uri_over32kb : function () {
                    return t.use_data_uri && ("IE" !== r.browser || r.version >= 9)
                }, use_data_uri_of : function (e) {
                    return t.use_data_uri && 33000 > e || t.use_data_uri_over32kb()
                }, use_fileinput : function () {
                    var e = document.createElement("input");
                    return e.setAttribute("type", "file"), !e.disabled
                }
            };
            return function (n) {
                var i = [].slice.call(arguments);
                return i.shift(), "function" === e.typeOf(t[n]) ? t[n].apply(this, i) : !!t[n]
            }
        }(), r = {
            can : i,
            browser : n.browser.name,
            version : parseFloat(n.browser.major),
            os : n.os.name,
            osVersion : n.os.version,
            verComp : t,
            swf_url : "../flash/Moxie.swf",
            xap_url : "../silverlight/Moxie.xap",
            global_event_dispatcher : "moxie.core.EventTarget.instance.dispatchEvent"
        };
        return r.OS = r.os, r
    }), i(f, [d], function (e) {
        var t = function (e) {
            return "string" != typeof e ? e : document.getElementById(e)
        }, n  = function (e, t) {
            if(!e.className){
                return !1
            }
            var n = new RegExp("(^|\\s+)" + t + "(\\s+|$)");
            return n.test(e.className)
        }, i  = function (e, t) {
            n(e, t) || (e.className = e.className ? e.className.replace(/\s+$/, "") + " " + t : t)
        }, r  = function (e, t) {
            if(e.className){
                var n       = new RegExp("(^|\\s+)" + t + "(\\s+|$)");
                e.className = e.className.replace(n, function (e, t, n) {
                    return " " === t && " " === n ? " " : ""
                })
            }
        }, o  = function (e, t) {
            return e.currentStyle ? e.currentStyle[t] : window.getComputedStyle ? window.getComputedStyle(e, null)[t] : void 0
        }, a  = function (t, n) {
            function i(e) {
                var t, n, i = 0, r = 0;
                return e && (n = e.getBoundingClientRect(), t = "CSS1Compat" === s.compatMode ? s.documentElement : s.body, i = n.left + t.scrollLeft, r = n.top + t.scrollTop), {
                    x : i,
                    y : r
                }
            }

            var r = 0, o = 0, a, s = document, u, c;
            if(t = t, n = n || s.body, t && (t.getBoundingClientRect && ("IE" === e.browser && (!s.documentMode || s.documentMode < 8)))){
                return u = i(t), c = i(n), {x : u.x - c.x, y : u.y - c.y}
            }
            for( a = t; a && (a != n && a.nodeType); ){
                r += a.offsetLeft || 0, o += a.offsetTop || 0, a = a.offsetParent
            }
            for( a = t.parentNode; a && (a != n && a.nodeType); ){
                r -= a.scrollLeft || 0, o -= a.scrollTop || 0, a = a.parentNode
            }
            return {x : r, y : o}
        }, s  = function (e) {
            return {w : e.offsetWidth || e.clientWidth, h : e.offsetHeight || e.clientHeight}
        };
        return {get : t, hasClass : n, addClass : i, removeClass : r, getStyle : o, getPos : a, getSize : s}
    }), i(h, [u], function (e) {
        function t(e, t) {
            var n;
            for( n in e ){
                if(e[n] === t){
                    return n
                }
            }
            return null
        }

        return {
            RuntimeError : function () {
                function n(e) {
                    this.code = e, this.name = t(i, e), this.message = this.name + ": RuntimeError " + this.code
                }

                var i = {NOT_INIT_ERR : 1, NOT_SUPPORTED_ERR : 9, JS_ERR : 4};
                return e.extend(n, i), n.prototype = Error.prototype, n
            }(), OperationNotAllowedException : function () {
                function t(e) {
                    this.code = e, this.name = "OperationNotAllowedException"
                }

                return e.extend(t, {NOT_ALLOWED_ERR : 1}), t.prototype = Error.prototype, t
            }(), ImageError : function () {
                function n(e) {
                    this.code = e, this.name = t(i, e), this.message = this.name + ": ImageError " + this.code
                }

                var i = {WRONG_FORMAT : 1, MAX_RESOLUTION_ERR : 2};
                return e.extend(n, i), n.prototype = Error.prototype, n
            }(), FileException : function () {
                function n(e) {
                    this.code = e, this.name = t(i, e), this.message = this.name + ": FileException " + this.code
                }

                var i = {
                    NOT_FOUND_ERR : 1,
                    SECURITY_ERR : 2,
                    ABORT_ERR : 3,
                    NOT_READABLE_ERR : 4,
                    ENCODING_ERR : 5,
                    NO_MODIFICATION_ALLOWED_ERR : 6,
                    INVALID_STATE_ERR : 7,
                    SYNTAX_ERR : 8
                };
                return e.extend(n, i), n.prototype = Error.prototype, n
            }(), DOMException : function () {
                function n(e) {
                    this.code = e, this.name = t(i, e), this.message = this.name + ": DOMException " + this.code
                }

                var i = {
                    INDEX_SIZE_ERR : 1,
                    DOMSTRING_SIZE_ERR : 2,
                    HIERARCHY_REQUEST_ERR : 3,
                    WRONG_DOCUMENT_ERR : 4,
                    INVALID_CHARACTER_ERR : 5,
                    NO_DATA_ALLOWED_ERR : 6,
                    NO_MODIFICATION_ALLOWED_ERR : 7,
                    NOT_FOUND_ERR : 8,
                    NOT_SUPPORTED_ERR : 9,
                    INUSE_ATTRIBUTE_ERR : 10,
                    INVALID_STATE_ERR : 11,
                    SYNTAX_ERR : 12,
                    INVALID_MODIFICATION_ERR : 13,
                    NAMESPACE_ERR : 14,
                    INVALID_ACCESS_ERR : 15,
                    VALIDATION_ERR : 16,
                    TYPE_MISMATCH_ERR : 17,
                    SECURITY_ERR : 18,
                    NETWORK_ERR : 19,
                    ABORT_ERR : 20,
                    URL_MISMATCH_ERR : 21,
                    QUOTA_EXCEEDED_ERR : 22,
                    TIMEOUT_ERR : 23,
                    INVALID_NODE_TYPE_ERR : 24,
                    DATA_CLONE_ERR : 25
                };
                return e.extend(n, i), n.prototype = Error.prototype, n
            }(), EventException : function () {
                function t(e) {
                    this.code = e, this.name = "EventException"
                }

                return e.extend(t, {UNSPECIFIED_EVENT_TYPE_ERR : 0}), t.prototype = Error.prototype, t
            }()
        }
    }), i(p, [h, u], function (e, t) {
        function n() {
            var n = {};
            t.extend(this, {
                uid : null, init : function () {
                    this.uid || (this.uid = t.guid("uid_"))
                }, addEventListener : function (e, i, r, o) {
                    var a = this, s;
                    return e = t.trim(e), /\s/.test(e) ? void t.each(e.split(/\s+/), function (e) {
                            a.addEventListener(e, i, r, o)
                        }) : (e = e.toLowerCase(), r = parseInt(r, 10) || 0, s = n[this.uid] && n[this.uid][e] || [], s.push({
                            fn : i,
                            priority : r,
                            scope : o || this
                        }), n[this.uid] || (n[this.uid] = {}), void (n[this.uid][e] = s))
                }, hasEventListener : function (e) {
                    return e ? !(!n[this.uid] || !n[this.uid][e]) : !!n[this.uid]
                }, removeEventListener : function (e, i) {
                    e     = e.toLowerCase();
                    var r = n[this.uid] && n[this.uid][e], o;
                    if(r){
                        if(i){
                            for( o = r.length - 1; o >= 0; o-- ){
                                if(r[o].fn === i){
                                    r.splice(o, 1);
                                    break
                                }
                            }
                        }else{
                            r = []
                        }
                        r.length || (delete n[this.uid][e], t.isEmptyObj(n[this.uid]) && delete n[this.uid])
                    }
                }, removeAllEventListeners : function () {
                    n[this.uid] && delete n[this.uid]
                }, dispatchEvent : function (i) {
                    var r, o, a, s, u = {}, c = !0, l;
                    if("string" !== t.typeOf(i)){
                        if(s = i, "string" !== t.typeOf(s.type)){
                            throw new e.EventException(e.EventException.UNSPECIFIED_EVENT_TYPE_ERR)
                        }
                        i = s.type, s.total !== l && (s.loaded !== l && (u.total = s.total, u.loaded = s.loaded)), u.async = s.async || !1
                    }
                    if(-1 !== i.indexOf("::") ? !function (e) {
                                r = e[0], i = e[1]
                            }(i.split("::")) : r = this.uid, i = i.toLowerCase(), o = n[r] && n[r][i]){
                        o.sort(function (e, t) {
                            return t.priority - e.priority
                        }), a = [].slice.call(arguments), a.shift(), u.type = i, a.unshift(u);
                        var d = [];
                        t.each(o, function (e) {
                            a[0].target = e.scope, d.push(u.async ? function (t) {
                                    setTimeout(function () {
                                        t(e.fn.apply(e.scope, a) === !1)
                                    }, 1)
                                } : function (t) {
                                    t(e.fn.apply(e.scope, a) === !1)
                                })
                        }), d.length && t.inSeries(d, function (e) {
                            c = !e
                        })
                    }
                    return c
                }, bind : function () {
                    this.addEventListener.apply(this, arguments)
                }, unbind : function () {
                    this.removeEventListener.apply(this, arguments)
                }, unbindAll : function () {
                    this.removeAllEventListeners.apply(this, arguments)
                }, trigger : function () {
                    return this.dispatchEvent.apply(this, arguments)
                }, convertEventPropsToHandlers : function (e) {
                    var n;
                    "array" !== t.typeOf(e) && (e = [e]);
                    for( var i = 0; i < e.length; i++ ){
                        n = "on" + e[i], "function" === t.typeOf(this[n]) ? this.addEventListener(e[i], this[n]) : "undefined" === t.typeOf(this[n]) && (this[n] = null)
                    }
                }
            })
        }

        return n.instance = new n, n
    }), i(m, [], function () {
        var e = function (e) {
            return unescape(encodeURIComponent(e))
        }, t  = function (e) {
            return decodeURIComponent(escape(e))
        }, n  = function (e, n) {
            if("function" == typeof window.atob){
                return n ? t(window.atob(e)) : window.atob(e)
            }
            var i = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", r, o, a, s, u, c, l, d, f = 0, h = 0, p = "", m = [];
            if(!e){
                return e
            }
            e += "";
            do{
                s = i.indexOf(e.charAt(f++)), u = i.indexOf(e.charAt(f++)), c = i.indexOf(e.charAt(f++)), l = i.indexOf(e.charAt(f++)), d = s << 18 | u << 12 | c << 6 | l, r = d >> 16 & 255, o = d >> 8 & 255, a = 255 & d, m[h++] = 64 == c ? String.fromCharCode(r) : 64 == l ? String.fromCharCode(r, o) : String.fromCharCode(r, o, a)
            }while( f < e.length );
            return p = m.join(""), n ? t(p) : p
        }, i  = function (t, n) {
            if(n && e(t), "function" == typeof window.btoa){
                return window.btoa(t)
            }
            var i = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", r, o, a, s, u, c, l, d, f = 0, h = 0, p = "", m = [];
            if(!t){
                return t
            }
            do{
                r = t.charCodeAt(f++), o = t.charCodeAt(f++), a = t.charCodeAt(f++), d = r << 16 | o << 8 | a, s = d >> 18 & 63, u = d >> 12 & 63, c = d >> 6 & 63, l = 63 & d, m[h++] = i.charAt(s) + i.charAt(u) + i.charAt(c) + i.charAt(l)
            }while( f < t.length );
            p     = m.join("");
            var g = t.length % 3;
            return (g ? p.slice(0, g - 3) : p) + "===".slice(g || 3)
        };
        return {utf8_encode : e, utf8_decode : t, atob : n, btoa : i}
    }), i(g, [u, f, p], function (e, t, n) {
        function i(n, r, a, s, u) {
            var c = this, l, d = e.guid(r + "_"), f = u || "browser";
            n = n || {}, o[d] = this, a = e.extend({
                access_binary : !1,
                access_image_binary : !1,
                display_media : !1,
                do_cors : !1,
                drag_and_drop : !1,
                filter_by_extension : !0,
                resize_image : !1,
                report_upload_progress : !1,
                return_response_headers : !1,
                return_response_type : !1,
                return_status_code : !0,
                send_custom_headers : !1,
                select_file : !1,
                select_folder : !1,
                select_multiple : !0,
                send_binary_string : !1,
                send_browser_cookies : !0,
                send_multipart : !0,
                slice_blob : !1,
                stream_upload : !1,
                summon_file_dialog : !1,
                upload_filesize : !0,
                use_http_method : !0
            }, a), n.preferred_caps && (f = i.getMode(s, n.preferred_caps, f)), l = function () {
                var t = {};
                return {
                    exec : function (e, n, i, r) {
                        return l[n] && (t[e] || (t[e] = {
                            context : this,
                            instance : new l[n]
                        }), t[e].instance[i]) ? t[e].instance[i].apply(this, r) : void 0
                    }, removeInstance : function (e) {
                        delete t[e]
                    }, removeAllInstances : function () {
                        var n = this;
                        e.each(t, function (t, i) {
                            "function" === e.typeOf(t.instance.destroy) && t.instance.destroy.call(t.context), n.removeInstance(i)
                        })
                    }
                }
            }(), e.extend(this, {
                initialized : !1,
                uid : d,
                type : r,
                mode : i.getMode(s, n.required_caps, f),
                shimid : d + "_container",
                clients : 0,
                options : n,
                can : function (t, n) {
                    var r = arguments[2] || a;
                    if("string" === e.typeOf(t) && ("undefined" === e.typeOf(n) && (t = i.parseCaps(t))), "object" === e.typeOf(t)){
                        for( var o in t ){
                            if(!this.can(o, t[o], r)){
                                return !1
                            }
                        }
                        return !0
                    }
                    return "function" === e.typeOf(r[t]) ? r[t].call(this, n) : n === r[t]
                },
                getShimContainer : function () {
                    var n, i = t.get(this.shimid);
                    return i || (n = this.options.container ? t.get(this.options.container) : document.body, i = document.createElement("div"), i.id = this.shimid, i.className = "moxie-shim moxie-shim-" + this.type, e.extend(i.style, {
                        position : "absolute",
                        top : "0px",
                        left : "0px",
                        width : "1px",
                        height : "1px",
                        overflow : "hidden"
                    }), n.appendChild(i), n = null), i
                },
                getShim : function () {
                    return l
                },
                shimExec : function (e, t) {
                    var n = [].slice.call(arguments, 2);
                    return c.getShim().exec.call(this, this.uid, e, t, n)
                },
                exec : function (e, t) {
                    var n = [].slice.call(arguments, 2);
                    return c[e] && c[e][t] ? c[e][t].apply(this, n) : c.shimExec.apply(this, arguments)
                },
                destroy : function () {
                    if(c){
                        var e = t.get(this.shimid);
                        e && e.parentNode.removeChild(e), l && l.removeAllInstances(), this.unbindAll(), delete o[this.uid], this.uid = null, d = c = l = e = null
                    }
                }
            }), this.mode && (n.required_caps && (!this.can(n.required_caps) && (this.mode = !1)))
        }

        var r = {}, o = {};
        return i.order = "html5,flash,silverlight,html4", i.getRuntime = function (e) {
            return o[e] ? o[e] : !1
        }, i.addConstructor = function (e, t) {
            t.prototype = n.instance, r[e] = t
        }, i.getConstructor = function (e) {
            return r[e] || null
        }, i.getInfo = function (e) {
            var t = i.getRuntime(e);
            return t ? {
                    uid : t.uid, type : t.type, mode : t.mode, can : function () {
                        return t.can.apply(t, arguments)
                    }
                } : null
        }, i.parseCaps = function (t) {
            var n = {};
            return "string" !== e.typeOf(t) ? t || {} : (e.each(t.split(","), function (e) {
                    n[e] = !0
                }), n)
        }, i.can = function (e, t) {
            var n, r = i.getConstructor(e), o;
            return r ? (n = new r({required_caps : t}), o = n.mode, n.destroy(), !!o) : !1
        }, i.thatCan = function (e, t) {
            var n = (t || i.order).split(/\s*,\s*/);
            for( var r in n ){
                if(i.can(n[r], e)){
                    return n[r]
                }
            }
            return null
        }, i.getMode = function (t, n, i) {
            var r = null;
            if("undefined" === e.typeOf(i) && (i = "browser"), n && !e.isEmptyObj(t)){
                if(e.each(n, function (n, i) {
                        if(t.hasOwnProperty(i)){
                            var o = t[i](n);
                            if("string" == typeof o && (o = [o]), r){
                                if(!(r = e.arrayIntersect(r, o))){
                                    return r = !1
                                }
                            }else{
                                r = o
                            }
                        }
                    }), r){
                    return -1 !== e.inArray(i, r) ? i : r[0]
                }
                if(r === !1){
                    return !1
                }
            }
            return i
        }, i.capTrue = function () {
            return !0
        }, i.capFalse = function () {
            return !1
        }, i.capTest = function (e) {
            return function () {
                return !!e
            }
        }, i
    }), i(v, [h, u, g], function (e, t, n) {
        return function i() {
            var i;
            t.extend(this, {
                connectRuntime : function (r) {
                    function o(t) {
                        var s, u;
                        return t.length ? (s = t.shift(), (u = n.getConstructor(s)) ? (i = new u(r), i.bind("Init", function () {
                                    i.initialized = !0, setTimeout(function () {
                                        i.clients++, a.trigger("RuntimeInit", i)
                                    }, 1)
                                }), i.bind("Error", function () {
                                    i.destroy(), o(t)
                                }), i.mode ? void i.init() : void i.trigger("Error")) : void o(t)) : (a.trigger("RuntimeError", new e.RuntimeError(e.RuntimeError.NOT_INIT_ERR)), void (i = null))
                    }

                    var a = this, s;
                    if("string" === t.typeOf(r) ? s = r : "string" === t.typeOf(r.ruid) && (s = r.ruid), s){
                        if(i = n.getRuntime(s)){
                            return i.clients++, i
                        }
                        throw new e.RuntimeError(e.RuntimeError.NOT_INIT_ERR)
                    }
                    o((r.runtime_order || n.order).split(/\s*,\s*/))
                }, getRuntime : function () {
                    return i && i.uid ? i : (i = null, null)
                }, disconnectRuntime : function () {
                    i && (--i.clients <= 0 && (i.destroy(), i = null))
                }
            })
        }
    }), i(y, [u, m, v], function (e, t, n) {
        function i(o, a) {
            function s(t, n, o) {
                var a, s = r[this.uid];
                return "string" === e.typeOf(s) && s.length ? (a = new i(null, {
                        type : o,
                        size : n - t
                    }), a.detach(s.substr(t, a.size)), a) : null
            }

            n.call(this), o && this.connectRuntime(o), a ? "string" === e.typeOf(a) && (a = {data : a}) : a = {}, e.extend(this, {
                uid : a.uid || e.guid("uid_"),
                ruid : o,
                size : a.size || 0,
                type : a.type || "",
                slice : function (e, t, n) {
                    return this.isDetached() ? s.apply(this, arguments) : this.getRuntime().exec.call(this, "Blob", "slice", this.getSource(), e, t, n)
                },
                getSource : function () {
                    return r[this.uid] ? r[this.uid] : null
                },
                detach : function (e) {
                    this.ruid && (this.getRuntime().exec.call(this, "Blob", "destroy"), this.disconnectRuntime(), this.ruid = null), e = e || "";
                    var n = e.match(/^data:([^;]*);base64,/);
                    n && (this.type = n[1], e = t.atob(e.substring(e.indexOf("base64,") + 7))), this.size = e.length, r[this.uid] = e
                },
                isDetached : function () {
                    return !this.ruid && "string" === e.typeOf(r[this.uid])
                },
                destroy : function () {
                    this.detach(), delete r[this.uid]
                }
            }), a.data ? this.detach(a.data) : r[this.uid] = a
        }

        var r = {};
        return i
    }), i(w, [u, l, y], function (e, t, n) {
        function i(i, r) {
            var o, a;
            if(r || (r = {}), a = r.type && "" !== r.type ? r.type : t.getFileMime(r.name), r.name){
                o = r.name.replace(/\\/g, "/"), o = o.substr(o.lastIndexOf("/") + 1)
            }else{
                var s = a.split("/")[0];
                o = e.guid(("" !== s ? s : "file") + "_"), t.extensions[a] && (o += "." + t.extensions[a][0])
            }
            n.apply(this, arguments), e.extend(this, {
                type : a || "",
                name : o || e.guid("file_"),
                lastModifiedDate : r.lastModifiedDate || (new Date).toLocaleString()
            })
        }

        return i.prototype = n.prototype, i
    }), i(E, [u, l, f, h, p, c, w, g, v], function (e, t, n, i, r, o, a, s, u) {
        function c(r) {
            var c = this, d, f, h;
            if(-1 !== e.inArray(e.typeOf(r), ["string", "node"]) && (r = {browse_button : r}), f = n.get(r.browse_button), !f){
                throw new i.DOMException(i.DOMException.NOT_FOUND_ERR)
            }
            h = {
                accept : [{title : o.translate("All Files"), extensions : "*"}],
                name : "file",
                multiple : !1,
                required_caps : !1,
                container : f.parentNode || document.body
            }, r = e.extend({}, h, r), "string" == typeof r.required_caps && (r.required_caps = s.parseCaps(r.required_caps)), "string" == typeof r.accept && (r.accept = t.mimes2extList(r.accept)), d = n.get(r.container), d || (d = document.body), "static" === n.getStyle(d, "position") && (d.style.position = "relative"), d = f = null, u.call(c), e.extend(c, {
                uid : e.guid("uid_"),
                ruid : null,
                shimid : null,
                files : null,
                init : function () {
                    c.convertEventPropsToHandlers(l), c.bind("RuntimeInit", function (t, i) {
                        c.ruid = i.uid, c.shimid = i.shimid, c.bind("Ready", function () {
                            c.trigger("Refresh")
                        }, 999), c.bind("Change", function () {
                            var t = i.exec.call(c, "FileInput", "getFiles");
                            c.files = [], e.each(t, function (e) {
                                return 0 === e.size ? !0 : void c.files.push(new a(c.ruid, e))
                            })
                        }, 999), c.bind("Refresh", function () {
                            var t, o, a, s;
                            a = n.get(r.browse_button), s = n.get(i.shimid), a && (t = n.getPos(a, n.get(r.container)), o = n.getSize(a), s && e.extend(s.style, {
                                top : t.y + "px",
                                left : t.x + "px",
                                width : o.w + "px",
                                height : o.h + "px"
                            })), s = a = null
                        }), i.exec.call(c, "FileInput", "init", r)
                    }), c.connectRuntime(e.extend({}, r, {required_caps : {select_file : !0}}))
                },
                disable : function (t) {
                    var n = this.getRuntime();
                    n && n.exec.call(this, "FileInput", "disable", "undefined" === e.typeOf(t) ? !0 : t)
                },
                refresh : function () {
                    c.trigger("Refresh")
                },
                destroy : function () {
                    var t = this.getRuntime();
                    t && (t.exec.call(this, "FileInput", "destroy"), this.disconnectRuntime()), "array" === e.typeOf(this.files) && e.each(this.files, function (e) {
                        e.destroy()
                    }), this.files = null
                }
            })
        }

        var l = ["ready", "change", "cancel", "mouseenter", "mouseleave", "mousedown", "mouseup"];
        return c.prototype = r.instance, c
    }), i(_, [c, f, h, u, w, v, p, l], function (e, t, n, i, r, o, a, s) {
        function u(n) {
            var a = this, u;
            "string" == typeof n && (n = {drop_zone : n}), u = {
                accept : [{
                    title : e.translate("All Files"),
                    extensions : "*"
                }], required_caps : {drag_and_drop : !0}
            }, n = "object" == typeof n ? i.extend({}, u, n) : u, n.container = t.get(n.drop_zone) || document.body, "static" === t.getStyle(n.container, "position") && (n.container.style.position = "relative"), "string" == typeof n.accept && (n.accept = s.mimes2extList(n.accept)), o.call(a), i.extend(a, {
                uid : i.guid("uid_"),
                ruid : null,
                files : null,
                init : function () {
                    a.convertEventPropsToHandlers(c), a.bind("RuntimeInit", function (e, t) {
                        a.ruid = t.uid, a.bind("Drop", function () {
                            var e = t.exec.call(a, "FileDrop", "getFiles");
                            a.files = [], i.each(e, function (e) {
                                a.files.push(new r(a.ruid, e))
                            })
                        }, 999), t.exec.call(a, "FileDrop", "init", n), a.dispatchEvent("ready")
                    }), a.connectRuntime(n)
                },
                destroy : function () {
                    var e = this.getRuntime();
                    e && (e.exec.call(this, "FileDrop", "destroy"), this.disconnectRuntime()), this.files = null
                }
            })
        }

        var c = ["ready", "dragenter", "dragleave", "drop", "error"];
        return u.prototype = a.instance, u
    }), i(x, [u, v, p], function (e, t, n) {
        function i() {
            this.uid = e.guid("uid_"), t.call(this), this.destroy = function () {
                this.disconnectRuntime(), this.unbindAll()
            }
        }

        return i.prototype = n.instance, i
    }), i(b, [u, m, h, p, y, w, x], function (e, t, n, i, r, o, a) {
        function s() {
            function i(e, i) {
                function l(e) {
                    o.readyState = s.DONE, o.error = e, o.trigger("error"), d()
                }

                function d() {
                    c.destroy(), c = null, o.trigger("loadend")
                }

                function f(t) {
                    c.bind("Error", function (e, t) {
                        l(t)
                    }), c.bind("Progress", function (e) {
                        o.result = t.exec.call(c, "FileReader", "getResult"), o.trigger(e)
                    }), c.bind("Load", function (e) {
                        o.readyState = s.DONE, o.result = t.exec.call(c, "FileReader", "getResult"), o.trigger(e), d()
                    }), t.exec.call(c, "FileReader", "read", e, i)
                }

                if(c = new a, this.convertEventPropsToHandlers(u), this.readyState === s.LOADING){
                    return l(new n.DOMException(n.DOMException.INVALID_STATE_ERR))
                }
                if(this.readyState = s.LOADING, this.trigger("loadstart"), i instanceof r){
                    if(i.isDetached()){
                        var h = i.getSource();
                        switch(e){
                            case"readAsText":
                            case"readAsBinaryString":
                                this.result = h;
                                break;
                            case"readAsDataURL":
                                this.result = "data:" + i.type + ";base64," + t.btoa(h)
                        }
                        this.readyState = s.DONE, this.trigger("load"), d()
                    }else{
                        f(c.connectRuntime(i.ruid))
                    }
                }else{
                    l(new n.DOMException(n.DOMException.NOT_FOUND_ERR))
                }
            }

            var o = this, c;
            e.extend(this, {
                uid : e.guid("uid_"),
                readyState : s.EMPTY,
                result : null,
                error : null,
                readAsBinaryString : function (e) {
                    i.call(this, "readAsBinaryString", e)
                },
                readAsDataURL : function (e) {
                    i.call(this, "readAsDataURL", e)
                },
                readAsText : function (e) {
                    i.call(this, "readAsText", e)
                },
                abort : function () {
                    this.result = null, -1 === e.inArray(this.readyState, [s.EMPTY, s.DONE]) && (this.readyState === s.LOADING && (this.readyState = s.DONE), c && c.getRuntime().exec.call(this, "FileReader", "abort"), this.trigger("abort"), this.trigger("loadend"))
                },
                destroy : function () {
                    this.abort(), c && (c.getRuntime().exec.call(this, "FileReader", "destroy"), c.disconnectRuntime()), o = c = null
                }
            })
        }

        var u = ["loadstart", "progress", "load", "abort", "error", "loadend"];
        return s.EMPTY = 0, s.LOADING = 1, s.DONE = 2, s.prototype = i.instance, s
    }), i(R, [], function () {
        var e = function (t, n) {
            for( var i = ["source", "scheme", "authority", "userInfo", "user", "pass", "host", "port", "relative", "path", "directory", "file", "query", "fragment"], r = i.length, o = {
                http : 80,
                https : 443
            }, a       = {}, s = /^(?:([^:\/?#]+):)?(?:\/\/()(?:(?:()(?:([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?()(?:(()(?:(?:[^?#\/]*\/)*)()(?:[^?#]*))(?:\\?([^#]*))?(?:#(.*))?)/, u = s.exec(t || ""); r--; ){
                u[r] && (a[i[r]] = u[r])
            }
            if(!a.scheme){
                n && "string" != typeof n || (n = e(n || document.location.href)), a.scheme = n.scheme, a.host = n.host, a.port = n.port;
                var c = "";
                /^[^\/]/.test(a.path) && (c = n.path, /(\/|\/[^\.]+)$/.test(c) ? c += "/" : c = c.replace(/\/[^\/]+$/, "/")), a.path = c + (a.path || "")
            }
            return a.port || (a.port = o[a.scheme] || 80), a.port = parseInt(a.port, 10), a.path || (a.path = "/"), delete a.source, a
        }, t  = function (t) {
            var n = {http : 80, https : 443}, i = e(t);
            return i.scheme + "://" + i.host + (i.port !== n[i.scheme] ? ":" + i.port : "") + i.path + (i.query ? i.query : "")
        }, n  = function (t) {
            function n(e) {
                return [e.scheme, e.host, e.port].join("/")
            }

            return "string" == typeof t && (t = e(t)), n(e()) === n(t)
        };
        return {parseUrl : e, resolveUrl : t, hasSameOrigin : n}
    }), i(T, [u, v, m], function (e, t, n) {
        return function () {
            function i(e, t) {
                if(!t.isDetached()){
                    var i = this.connectRuntime(t.ruid).exec.call(this, "FileReaderSync", "read", e, t);
                    return this.disconnectRuntime(), i
                }
                var r = t.getSource();
                switch(e){
                    case"readAsBinaryString":
                        return r;
                    case"readAsDataURL":
                        return "data:" + t.type + ";base64," + n.btoa(r);
                    case"readAsText":
                        for( var o = "", a = 0, s = r.length; s > a; a++ ){
                            o += String.fromCharCode(r[a])
                        }
                        return o
                }
            }

            t.call(this), e.extend(this, {
                uid : e.guid("uid_"), readAsBinaryString : function (e) {
                    return i.call(this, "readAsBinaryString", e)
                }, readAsDataURL : function (e) {
                    return i.call(this, "readAsDataURL", e)
                }, readAsText : function (e) {
                    return i.call(this, "readAsText", e)
                }
            })
        }
    }), i(A, [h, u, y], function (e, t, n) {
        function i() {
            var e, i = [];
            t.extend(this, {
                append : function (r, o) {
                    var a = this, s = t.typeOf(o);
                    o instanceof n ? e = {name : r, value : o} : "array" === s ? (r += "[]", t.each(o, function (e) {
                                a.append(r, e)
                            })) : "object" === s ? t.each(o, function (e, t) {
                                    a.append(r + "[" + t + "]", e)
                                }) : "null" === s || ("undefined" === s || "number" === s && isNaN(o)) ? a.append(r, "false") : i.push({
                                        name : r,
                                        value : o.toString()
                                    })
                }, hasBlob : function () {
                    return !!this.getBlob()
                }, getBlob : function () {
                    return e && e.value || null
                }, getBlobName : function () {
                    return e && e.name || null
                }, each : function (n) {
                    t.each(i, function (e) {
                        n(e.value, e.name)
                    }), e && n(e.value, e.name)
                }, destroy : function () {
                    e = null, i = []
                }
            })
        }

        return i
    }), i(S, [u, h, p, m, R, g, x, y, T, A, d, l], function (e, t, n, i, r, o, a, s, u, c, l, d) {
        function f() {
            this.uid = e.guid("uid_")
        }

        function h() {
            function n(e, t) {
                return y.hasOwnProperty(e) ? 1 === arguments.length ? l.can("define_property") ? y[e] : v[e] : void (l.can("define_property") ? y[e] = t : v[e] = t) : void 0
            }

            function u(t) {
                function i() {
                    k && (k.destroy(), k = null), s.dispatchEvent("loadend"), s = null
                }

                function r(r) {
                    k.bind("LoadStart", function (e) {
                        n("readyState", h.LOADING), s.dispatchEvent("readystatechange"), s.dispatchEvent(e), I && s.upload.dispatchEvent(e)
                    }), k.bind("Progress", function (e) {
                        n("readyState") !== h.LOADING && (n("readyState", h.LOADING), s.dispatchEvent("readystatechange")), s.dispatchEvent(e)
                    }), k.bind("UploadProgress", function (e) {
                        I && s.upload.dispatchEvent({
                            type : "progress",
                            lengthComputable : !1,
                            total : e.total,
                            loaded : e.loaded
                        })
                    }), k.bind("Load", function (t) {
                        n("readyState", h.DONE), n("status", Number(r.exec.call(k, "XMLHttpRequest", "getStatus") || 0)), n("statusText", p[n("status")] || ""), n("response", r.exec.call(k, "XMLHttpRequest", "getResponse", n("responseType"))), ~e.inArray(n("responseType"), ["text", ""]) ? n("responseText", n("response")) : "document" === n("responseType") && n("responseXML", n("response")), U = r.exec.call(k, "XMLHttpRequest", "getAllResponseHeaders"), s.dispatchEvent("readystatechange"), n("status") > 0 ? (I && s.upload.dispatchEvent(t), s.dispatchEvent(t)) : (N = !0, s.dispatchEvent("error")), i()
                    }), k.bind("Abort", function (e) {
                        s.dispatchEvent(e), i()
                    }), k.bind("Error", function (e) {
                        N = !0, n("readyState", h.DONE), s.dispatchEvent("readystatechange"), D = !0, s.dispatchEvent(e), i()
                    }), r.exec.call(k, "XMLHttpRequest", "send", {
                        url : E,
                        method : _,
                        async : w,
                        user : b,
                        password : R,
                        headers : x,
                        mimeType : A,
                        encoding : T,
                        responseType : s.responseType,
                        withCredentials : s.withCredentials,
                        options : P
                    }, t)
                }

                var s = this;
                M = (new Date).getTime(), k = new a, "string" == typeof P.required_caps && (P.required_caps = o.parseCaps(P.required_caps)), P.required_caps = e.extend({}, P.required_caps, {return_response_type : s.responseType}), t instanceof c && (P.required_caps.send_multipart = !0), L || (P.required_caps.do_cors = !0), P.ruid ? r(k.connectRuntime(P)) : (k.bind("RuntimeInit", function (e, t) {
                        r(t)
                    }), k.bind("RuntimeError", function (e, t) {
                        s.dispatchEvent("RuntimeError", t)
                    }), k.connectRuntime(P))
            }

            function g() {
                n("responseText", ""), n("responseXML", null), n("response", null), n("status", 0), n("statusText", ""), M = C = null
            }

            var v = this, y = {
                timeout : 0,
                readyState : h.UNSENT,
                withCredentials : !1,
                status : 0,
                statusText : "",
                responseType : "",
                responseXML : null,
                responseText : null,
                response : null
            }, w  = !0, E, _, x = {}, b, R, T = null, A = null, S = !1, O = !1, I = !1, D = !1, N = !1, L = !1, M, C, F = null, H = null, P = {}, k, U = "", B;
            e.extend(this, y, {
                uid : e.guid("uid_"), upload : new f, open : function (o, a, s, u, c) {
                    var l;
                    if(!o || !a){
                        throw new t.DOMException(t.DOMException.SYNTAX_ERR)
                    }
                    if(/[\u0100-\uffff]/.test(o) || i.utf8_encode(o) !== o){
                        throw new t.DOMException(t.DOMException.SYNTAX_ERR)
                    }
                    if(~e.inArray(o.toUpperCase(), ["CONNECT", "DELETE", "GET", "HEAD", "OPTIONS", "POST", "PUT", "TRACE", "TRACK"]) && (_ = o.toUpperCase()), ~e.inArray(_, ["CONNECT", "TRACE", "TRACK"])){
                        throw new t.DOMException(t.DOMException.SECURITY_ERR)
                    }
                    if(a = i.utf8_encode(a), l = r.parseUrl(a), L = r.hasSameOrigin(l), E = r.resolveUrl(a), (u || c) && !L){
                        throw new t.DOMException(t.DOMException.INVALID_ACCESS_ERR)
                    }
                    if(b = u || l.user, R = c || l.pass, w = s || !0, w === !1 && (n("timeout") || (n("withCredentials") || "" !== n("responseType")))){
                        throw new t.DOMException(t.DOMException.INVALID_ACCESS_ERR)
                    }
                    S = !w, O = !1, x = {}, g.call(this), n("readyState", h.OPENED), this.convertEventPropsToHandlers(["readystatechange"]), this.dispatchEvent("readystatechange")
                }, setRequestHeader : function (r, o) {
                    var a = ["accept-charset", "accept-encoding", "access-control-request-headers", "access-control-request-method", "connection", "content-length", "cookie", "cookie2", "content-transfer-encoding", "date", "expect", "host", "keep-alive", "origin", "referer", "te", "trailer", "transfer-encoding", "upgrade", "user-agent", "via"];
                    if(n("readyState") !== h.OPENED || O){
                        throw new t.DOMException(t.DOMException.INVALID_STATE_ERR)
                    }
                    if(/[\u0100-\uffff]/.test(r) || i.utf8_encode(r) !== r){
                        throw new t.DOMException(t.DOMException.SYNTAX_ERR)
                    }
                    return r = e.trim(r).toLowerCase(), ~e.inArray(r, a) || /^(proxy\-|sec\-)/.test(r) ? !1 : (x[r] ? x[r] += ", " + o : x[r] = o, !0)
                }, getAllResponseHeaders : function () {
                    return U || ""
                }, getResponseHeader : function (t) {
                    return t = t.toLowerCase(), N || ~e.inArray(t, ["set-cookie", "set-cookie2"]) ? null : U && ("" !== U && (B || (B = {}, e.each(U.split(/\r\n/), function (t) {
                            var n = t.split(/:\s+/);
                            2 === n.length && (n[0] = e.trim(n[0]), B[n[0].toLowerCase()] = {
                                header : n[0],
                                value : e.trim(n[1])
                            })
                        })), B.hasOwnProperty(t))) ? B[t].header + ": " + B[t].value : null
                }, overrideMimeType : function (i) {
                    var r, o;
                    if(~e.inArray(n("readyState"), [h.LOADING, h.DONE])){
                        throw new t.DOMException(t.DOMException.INVALID_STATE_ERR)
                    }
                    if(i = e.trim(i.toLowerCase()), /;/.test(i) && ((r = i.match(/^([^;]+)(?:;\scharset\=)?(.*)$/)) && (i = r[1], r[2] && (o = r[2]))), !d.mimes[i]){
                        throw new t.DOMException(t.DOMException.SYNTAX_ERR)
                    }
                    F = i, H = o
                }, send : function (n, r) {
                    if(P = "string" === e.typeOf(r) ? {ruid : r} : r ? r : {}, this.convertEventPropsToHandlers(m), this.upload.convertEventPropsToHandlers(m), this.readyState !== h.OPENED || O){
                        throw new t.DOMException(t.DOMException.INVALID_STATE_ERR)
                    }
                    if(n instanceof s){
                        P.ruid = n.ruid, A = n.type || "application/octet-stream"
                    }else{
                        if(n instanceof c){
                            if(n.hasBlob()){
                                var o = n.getBlob();
                                P.ruid = o.ruid, A = o.type || "application/octet-stream"
                            }
                        }else{
                            "string" == typeof n && (T = "UTF-8", A = "text/plain;charset=UTF-8", n = i.utf8_encode(n))
                        }
                    }
                    this.withCredentials || (this.withCredentials = P.required_caps && (P.required_caps.send_browser_cookies && !L)), I = !S && this.upload.hasEventListener(), N = !1, D = !n, S || (O = !0), u.call(this, n)
                }, abort : function () {
                    if(N = !0, S = !1, ~e.inArray(n("readyState"), [h.UNSENT, h.OPENED, h.DONE])){
                        n("readyState", h.UNSENT)
                    }else{
                        if(n("readyState", h.DONE), O = !1, !k){
                            throw new t.DOMException(t.DOMException.INVALID_STATE_ERR)
                        }
                        k.getRuntime().exec.call(k, "XMLHttpRequest", "abort", D), D = !0
                    }
                }, destroy : function () {
                    k && ("function" === e.typeOf(k.destroy) && k.destroy(), k = null), this.unbindAll(), this.upload && (this.upload.unbindAll(), this.upload = null)
                }
            })
        }

        var p       = {
            100 : "Continue",
            101 : "Switching Protocols",
            102 : "Processing",
            200 : "OK",
            201 : "Created",
            202 : "Accepted",
            203 : "Non-Authoritative Information",
            204 : "No Content",
            205 : "Reset Content",
            206 : "Partial Content",
            207 : "Multi-Status",
            226 : "IM Used",
            300 : "Multiple Choices",
            301 : "Moved Permanently",
            302 : "Found",
            303 : "See Other",
            304 : "Not Modified",
            305 : "Use Proxy",
            306 : "Reserved",
            307 : "Temporary Redirect",
            400 : "Bad Request",
            401 : "Unauthorized",
            402 : "Payment Required",
            403 : "Forbidden",
            404 : "Not Found",
            405 : "Method Not Allowed",
            406 : "Not Acceptable",
            407 : "Proxy Authentication Required",
            408 : "Request Timeout",
            409 : "Conflict",
            410 : "Gone",
            411 : "Length Required",
            412 : "Precondition Failed",
            413 : "Request Entity Too Large",
            414 : "Request-URI Too Long",
            415 : "Unsupported Media Type",
            416 : "Requested Range Not Satisfiable",
            417 : "Expectation Failed",
            422 : "Unprocessable Entity",
            423 : "Locked",
            424 : "Failed Dependency",
            426 : "Upgrade Required",
            500 : "Internal Server Error",
            501 : "Not Implemented",
            502 : "Bad Gateway",
            503 : "Service Unavailable",
            504 : "Gateway Timeout",
            505 : "HTTP Version Not Supported",
            506 : "Variant Also Negotiates",
            507 : "Insufficient Storage",
            510 : "Not Extended"
        };
        f.prototype = n.instance;
        var m       = ["loadstart", "progress", "abort", "error", "load", "timeout", "loadend"], g = 1, v = 2;
        return h.UNSENT = 0, h.OPENED = 1, h.HEADERS_RECEIVED = 2, h.LOADING = 3, h.DONE = 4, h.prototype = n.instance, h
    }), i(O, [u, m, v, p], function (e, t, n, i) {
        function r() {
            function i() {
                l = d = 0, c = this.result = null
            }

            function o(t, n) {
                var i = this;
                u = n, i.bind("TransportingProgress", function (t) {
                    d = t.loaded, l > d && (-1 === e.inArray(i.state, [r.IDLE, r.DONE]) && a.call(i))
                }, 999), i.bind("TransportingComplete", function () {
                    d = l, i.state = r.DONE, c = null, i.result = u.exec.call(i, "Transporter", "getAsBlob", t || "")
                }, 999), i.state = r.BUSY, i.trigger("TransportingStarted"), a.call(i)
            }

            function a() {
                var e = this, n, i = l - d;
                f > i && (f = i), n = t.btoa(c.substr(d, f)), u.exec.call(e, "Transporter", "receive", n, l)
            }

            var s, u, c, l, d, f;
            n.call(this), e.extend(this, {
                uid : e.guid("uid_"),
                state : r.IDLE,
                result : null,
                transport : function (t, n, r) {
                    var a = this;
                    if(r = e.extend({chunk_size : 204798}, r), (s = r.chunk_size % 3) && (r.chunk_size += 3 - s), f = r.chunk_size, i.call(this), c = t, l = t.length, "string" === e.typeOf(r) || r.ruid){
                        o.call(a, n, this.connectRuntime(r))
                    }else{
                        var u = function (e, t) {
                            a.unbind("RuntimeInit", u), o.call(a, n, t)
                        };
                        this.bind("RuntimeInit", u), this.connectRuntime(r)
                    }
                },
                abort : function () {
                    var e = this;
                    e.state = r.IDLE, u && (u.exec.call(e, "Transporter", "clear"), e.trigger("TransportingAborted")), i.call(e)
                },
                destroy : function () {
                    this.unbindAll(), u = null, this.disconnectRuntime(), i.call(this)
                }
            })
        }

        return r.IDLE = 0, r.BUSY = 1, r.DONE = 2, r.prototype = i.instance, r
    }), i(I, [u, f, h, T, S, g, v, O, d, p, y, w, m], function (e, t, n, i, r, o, a, s, u, c, l, d, f) {
        function h() {
            function i(e) {
                e || (e = this.getRuntime().exec.call(this, "Image", "getInfo")), this.size = e.size, this.width = e.width, this.height = e.height, this.type = e.type, this.meta = e.meta, "" === this.name && (this.name = e.name)
            }

            function c(t) {
                var i = e.typeOf(t);
                try{
                    if(t instanceof h){
                        if(!t.size){
                            throw new n.DOMException(n.DOMException.INVALID_STATE_ERR)
                        }
                        m.apply(this, arguments)
                    }else{
                        if(t instanceof l){
                            if(!~e.inArray(t.type, ["image/jpeg", "image/png"])){
                                throw new n.ImageError(n.ImageError.WRONG_FORMAT)
                            }
                            g.apply(this, arguments)
                        }else{
                            if(-1 !== e.inArray(i, ["blob", "file"])){
                                c.call(this, new d(null, t), arguments[1])
                            }else{
                                if("string" === i){
                                    /^data:[^;]*;base64,/.test(t) ? c.call(this, new l(null, {data : t}), arguments[1]) : v.apply(this, arguments)
                                }else{
                                    if("node" !== i || "img" !== t.nodeName.toLowerCase()){
                                        throw new n.DOMException(n.DOMException.TYPE_MISMATCH_ERR)
                                    }
                                    c.call(this, t.src, arguments[1])
                                }
                            }
                        }
                    }
                }catch(r){
                    this.trigger("error", r.code)
                }
            }

            function m(t, n) {
                var i = this.connectRuntime(t.ruid);
                this.ruid = i.uid, i.exec.call(this, "Image", "loadFromImage", t, "undefined" === e.typeOf(n) ? !0 : n)
            }

            function g(t, n) {
                function i(e) {
                    r.ruid = e.uid, e.exec.call(r, "Image", "loadFromBlob", t)
                }

                var r = this;
                r.name = t.name || "", t.isDetached() ? (this.bind("RuntimeInit", function (e, t) {
                        i(t)
                    }), n && ("string" == typeof n.required_caps && (n.required_caps = o.parseCaps(n.required_caps))), this.connectRuntime(e.extend({
                        required_caps : {
                            access_image_binary : !0,
                            resize_image : !0
                        }
                    }, n))) : i(this.connectRuntime(t.ruid))
            }

            function v(e, t) {
                var n = this, i;
                i = new r, i.open("get", e), i.responseType = "blob", i.onprogress = function (e) {
                    n.trigger(e)
                }, i.onload = function () {
                    g.call(n, i.response, !0)
                }, i.onerror = function (e) {
                    n.trigger(e)
                }, i.onloadend = function () {
                    i.destroy()
                }, i.bind("RuntimeError", function (e, t) {
                    n.trigger("RuntimeError", t)
                }), i.send(null, t)
            }

            a.call(this), e.extend(this, {
                uid : e.guid("uid_"),
                ruid : null,
                name : "",
                size : 0,
                width : 0,
                height : 0,
                type : "",
                meta : {},
                clone : function () {
                    this.load.apply(this, arguments)
                },
                load : function () {
                    this.bind("Load Resize", function () {
                        i.call(this)
                    }, 999), this.convertEventPropsToHandlers(p), c.apply(this, arguments)
                },
                downsize : function (t) {
                    var i = {width : this.width, height : this.height, crop : !1, preserveHeaders : !0};
                    t     = "object" == typeof t ? e.extend(i, t) : e.extend(i, {
                            width : arguments[0],
                            height : arguments[1],
                            crop : arguments[2],
                            preserveHeaders : arguments[3]
                        });
                    try{
                        if(!this.size){
                            throw new n.DOMException(n.DOMException.INVALID_STATE_ERR)
                        }
                        if(this.width > h.MAX_RESIZE_WIDTH || this.height > h.MAX_RESIZE_HEIGHT){
                            throw new n.ImageError(n.ImageError.MAX_RESOLUTION_ERR)
                        }
                        this.getRuntime().exec.call(this, "Image", "downsize", t.width, t.height, t.crop, t.preserveHeaders)
                    }catch(r){
                        this.trigger("error", r.code)
                    }
                },
                crop : function (e, t, n) {
                    this.downsize(e, t, !0, n)
                },
                getAsCanvas : function () {
                    if(!u.can("create_canvas")){
                        throw new n.RuntimeError(n.RuntimeError.NOT_SUPPORTED_ERR)
                    }
                    var e = this.connectRuntime(this.ruid);
                    return e.exec.call(this, "Image", "getAsCanvas")
                },
                getAsBlob : function (e, t) {
                    if(!this.size){
                        throw new n.DOMException(n.DOMException.INVALID_STATE_ERR)
                    }
                    return e || (e = "image/jpeg"), "image/jpeg" !== e || (t || (t = 90)), this.getRuntime().exec.call(this, "Image", "getAsBlob", e, t)
                },
                getAsDataURL : function (e, t) {
                    if(!this.size){
                        throw new n.DOMException(n.DOMException.INVALID_STATE_ERR)
                    }
                    return this.getRuntime().exec.call(this, "Image", "getAsDataURL", e, t)
                },
                getAsBinaryString : function (e, t) {
                    var n = this.getAsDataURL(e, t);
                    return f.atob(n.substring(n.indexOf("base64,") + 7))
                },
                embed : function (i) {
                    function r() {
                        if(u.can("create_canvas")){
                            var t = a.getAsCanvas();
                            if(t){
                                return i.appendChild(t), t = null, a.destroy(), void o.trigger("embedded")
                            }
                        }
                        var r = a.getAsDataURL(c, l);
                        if(!r){
                            throw new n.ImageError(n.ImageError.WRONG_FORMAT)
                        }
                        if(u.can("use_data_uri_of", r.length)){
                            i.innerHTML = '<img src="' + r + '" width="' + a.width + '" height="' + a.height + '" />', a.destroy(), o.trigger("embedded")
                        }else{
                            var d = new s;
                            d.bind("TransportingComplete", function () {
                                v = o.connectRuntime(this.result.ruid), o.bind("Embedded", function () {
                                    e.extend(v.getShimContainer().style, {
                                        top : "0px",
                                        left : "0px",
                                        width : a.width + "px",
                                        height : a.height + "px"
                                    }), v = null
                                }, 999), v.exec.call(o, "ImageView", "display", this.result.uid, m, g), a.destroy()
                            }), d.transport(f.atob(r.substring(r.indexOf("base64,") + 7)), c, e.extend({}, p, {
                                required_caps : {display_media : !0},
                                runtime_order : "flash,silverlight",
                                container : i
                            }))
                        }
                    }

                    var o = this, a, c, l, d, p = arguments[1] || {}, m = this.width, g = this.height, v;
                    try{
                        if(!(i = t.get(i))){
                            throw new n.DOMException(n.DOMException.INVALID_NODE_TYPE_ERR)
                        }
                        if(!this.size){
                            throw new n.DOMException(n.DOMException.INVALID_STATE_ERR)
                        }
                        if(this.width > h.MAX_RESIZE_WIDTH || this.height > h.MAX_RESIZE_HEIGHT){
                            throw new n.ImageError(n.ImageError.MAX_RESOLUTION_ERR)
                        }
                        if(c = p.type || (this.type || "image/jpeg"), l = p.quality || 90, d = "undefined" !== e.typeOf(p.crop) ? p.crop : !1, p.width){
                            m = p.width, g = p.height || m
                        }else{
                            var y = t.getSize(i);
                            y.w && (y.h && (m = y.w, g = y.h))
                        }
                        return a = new h, a.bind("Resize", function () {
                            r.call(o)
                        }), a.bind("Load", function () {
                            a.downsize(m, g, d, !1)
                        }), a.clone(this, !1), a
                    }catch(w){
                        this.trigger("error", w.code)
                    }
                },
                destroy : function () {
                    this.ruid && (this.getRuntime().exec.call(this, "Image", "destroy"), this.disconnectRuntime()), this.unbindAll()
                }
            })
        }

        var p = ["progress", "load", "error", "resize", "embedded"];
        return h.MAX_RESIZE_WIDTH = 6500, h.MAX_RESIZE_HEIGHT = 6500, h.prototype = c.instance, h
    }), i(D, [u, h, g, d], function (e, t, n, i) {
        function r(t) {
            var r = this, s = n.capTest, u = n.capTrue, c = e.extend({
                access_binary : s(window.FileReader || window.File && window.File.getAsDataURL),
                access_image_binary : function () {
                    return r.can("access_binary") && !!a.Image
                },
                display_media : s(i.can("create_canvas") || i.can("use_data_uri_over32kb")),
                do_cors : s(window.XMLHttpRequest && "withCredentials" in new XMLHttpRequest),
                drag_and_drop : s(function () {
                    var e = document.createElement("div");
                    return ("draggable" in e || "ondragstart" in e && "ondrop" in e) && ("IE" !== i.browser || i.version > 9)
                }()),
                filter_by_extension : s(function () {
                    return "Chrome" === i.browser && i.version >= 28 || "IE" === i.browser && i.version >= 10
                }()),
                return_response_headers : u,
                return_response_type : function (e) {
                    return "json" === e && window.JSON ? !0 : i.can("return_response_type", e)
                },
                return_status_code : u,
                report_upload_progress : s(window.XMLHttpRequest && (new XMLHttpRequest).upload),
                resize_image : function () {
                    return r.can("access_binary") && i.can("create_canvas")
                },
                select_file : function () {
                    return i.can("use_fileinput") && window.File
                },
                select_folder : function () {
                    return r.can("select_file") && ("Chrome" === i.browser && i.version >= 21)
                },
                select_multiple : function () {
                    return !(!r.can("select_file") || ("Safari" === i.browser && "Windows" === i.os || "iOS" === i.os && i.verComp(i.osVersion, "7.0.4", "<")))
                },
                send_binary_string : s(window.XMLHttpRequest && ((new XMLHttpRequest).sendAsBinary || window.Uint8Array && window.ArrayBuffer)),
                send_custom_headers : s(window.XMLHttpRequest),
                send_multipart : function () {
                    return !!(window.XMLHttpRequest && ((new XMLHttpRequest).upload && window.FormData)) || r.can("send_binary_string")
                },
                slice_blob : s(window.File && (File.prototype.mozSlice || (File.prototype.webkitSlice || File.prototype.slice))),
                stream_upload : function () {
                    return r.can("slice_blob") && r.can("send_multipart")
                },
                summon_file_dialog : s(function () {
                    return "Firefox" === i.browser && i.version >= 4 || ("Opera" === i.browser && i.version >= 12 || ("IE" === i.browser && i.version >= 10 || !!~e.inArray(i.browser, ["Chrome", "Safari"])))
                }()),
                upload_filesize : u
            }, arguments[2]);
            n.call(this, t, arguments[1] || o, c), e.extend(this, {
                init : function () {
                    this.trigger("Init")
                }, destroy : function (e) {
                    return function () {
                        e.call(r), e = r = null
                    }
                }(this.destroy)
            }), e.extend(this.getShim(), a)
        }

        var o = "html5", a = {};
        return n.addConstructor(o, r), a
    }), i(N, [D, y], function (e, t) {
        function n() {
            function e(e, t, n) {
                var i;
                if(!window.File.prototype.slice){
                    return (i = window.File.prototype.webkitSlice || window.File.prototype.mozSlice) ? i.call(e, t, n) : null
                }
                try{
                    return e.slice(), e.slice(t, n)
                }catch(r){
                    return e.slice(t, n - t)
                }
            }

            this.slice = function () {
                return new t(this.getRuntime().uid, e.apply(this, arguments))
            }
        }

        return e.Blob = n
    }), i(L, [u], function (e) {
        function t() {
            this.returnValue = !1
        }

        function n() {
            this.cancelBubble = !0
        }

        var i = {}, r = "moxie_" + e.guid(), o = function (o, a, s, u) {
            var c, l;
            a = a.toLowerCase(), o.addEventListener ? (c = s, o.addEventListener(a, c, !1)) : o.attachEvent && (c = function () {
                    var e = window.event;
                    e.target || (e.target = e.srcElement), e.preventDefault = t, e.stopPropagation = n, s(e)
                }, o.attachEvent("on" + a, c)), o[r] || (o[r] = e.guid()), i.hasOwnProperty(o[r]) || (i[o[r]] = {}), l = i[o[r]], l.hasOwnProperty(a) || (l[a] = []), l[a].push({
                func : c,
                orig : s,
                key : u
            })
        }, a  = function (t, n, o) {
            var a, s;
            if(n = n.toLowerCase(), t[r] && (i[t[r]] && i[t[r]][n])){
                a = i[t[r]][n];
                for( var u = a.length - 1; u >= 0 && (a[u].orig !== o && a[u].key !== o || (t.removeEventListener ? t.removeEventListener(n, a[u].func, !1) : t.detachEvent && t.detachEvent("on" + n, a[u].func), a[u].orig = null, a[u].func = null, a.splice(u, 1), o === s)); u-- ){
                }
                if(a.length || delete i[t[r]][n], e.isEmptyObj(i[t[r]])){
                    delete i[t[r]];
                    try{
                        delete t[r]
                    }catch(c){
                        t[r] = s
                    }
                }
            }
        }, s  = function (t, n) {
            t && (t[r] && e.each(i[t[r]], function (e, i) {
                a(t, i, n)
            }))
        };
        return {addEvent : o, removeEvent : a, removeAllEvents : s}
    }), i(M, [D, u, f, L, l, d], function (e, t, n, i, r, o) {
        function a() {
            var e = [], a;
            t.extend(this, {
                init : function (s) {
                    var u = this, c = u.getRuntime(), l, d, f, h, p, m;
                    a = s, e = [], f = a.accept.mimes || r.extList2mimes(a.accept, c.can("filter_by_extension")), f = [""], d = c.getShimContainer(), d.innerHTML = '<input id="' + c.uid + '" type="file" capture="camcorder"  style="font-size:999px;opacity:0;"' + (a.multiple && c.can("select_multiple") ? "multiple" : "") + (a.directory && c.can("select_folder") ? "webkitdirectory directory" : "") + (f ? ' accept="' + f.join(",") + '"' : "") + "  />", l = n.get(c.uid), t.extend(l.style, {
                        position : "absolute",
                        top : 0,
                        left : 0,
                        width : "100%",
                        height : "100%"
                    }), h = n.get(a.browse_button), c.can("summon_file_dialog") && ("static" === n.getStyle(h, "position") && (h.style.position = "relative"), p = parseInt(n.getStyle(h, "z-index"), 10) || 1, h.style.zIndex = p, d.style.zIndex = p - 1, i.addEvent(h, "click", function (e) {
                        var t = n.get(c.uid);
                        t && (!t.disabled && t.click()), e.preventDefault()
                    }, u.uid)), m = c.can("summon_file_dialog") ? h : d, i.addEvent(m, "mouseover", function () {
                        u.trigger("mouseenter")
                    }, u.uid), i.addEvent(m, "mouseout", function () {
                        u.trigger("mouseleave")
                    }, u.uid), i.addEvent(m, "mousedown", function () {
                        u.trigger("mousedown")
                    }, u.uid), i.addEvent(n.get(a.container), "mouseup", function () {
                        u.trigger("mouseup")
                    }, u.uid), l.onchange = function g() {
                        if(e = [], a.directory ? t.each(this.files, function (t) {
                                    "." !== t.name && e.push(t)
                                }) : e = [].slice.call(this.files), "IE" !== o.browser && "IEMobile" !== o.browser){
                            this.value = ""
                        }else{
                            var n = this.cloneNode(!0);
                            this.parentNode.replaceChild(n, this), n.onchange = g
                        }
                        u.trigger("change")
                    }, u.trigger({type : "ready", async : !0}), d = null
                }, getFiles : function () {
                    return e
                }, disable : function (e) {
                    var t = this.getRuntime(), i;
                    (i = n.get(t.uid)) && (i.disabled = !!e)
                }, destroy : function () {
                    var t = this.getRuntime(), r = t.getShim(), o = t.getShimContainer();
                    i.removeAllEvents(o, this.uid), i.removeAllEvents(a && n.get(a.container), this.uid), i.removeAllEvents(a && n.get(a.browse_button), this.uid), o && (o.innerHTML = ""), r.removeInstance(this.uid), e = a = o = r = null
                }
            })
        }

        return e.FileInput = a
    }), i(C, [D, u, f, L, l], function (e, t, n, i, r) {
        function o() {
            function e(e) {
                if(!e.dataTransfer || !e.dataTransfer.types){
                    return !1
                }
                var n = t.toArray(e.dataTransfer.types || []);
                return -1 !== t.inArray("Files", n) || (-1 !== t.inArray("public.file-url", n) || -1 !== t.inArray("application/x-moz-file", n))
            }

            function o(e) {
                for( var n = [], i = 0; i < e.length; i++ ){
                    [].push.apply(n, e[i].extensions.split(/\s*,\s*/))
                }
                return -1 === t.inArray("*", n) ? n : []
            }

            function a(e) {
                if(!f.length){
                    return !0
                }
                var n = r.getFileExtension(e.name);
                return !n || -1 !== t.inArray(n, f)
            }

            function s(e, n) {
                var i = [];
                t.each(e, function (e) {
                    var t = e.webkitGetAsEntry();
                    if(t){
                        if(t.isFile){
                            var n = e.getAsFile();
                            a(n) && d.push(n)
                        }else{
                            i.push(t)
                        }
                    }
                }), i.length ? u(i, n) : n()
            }

            function u(e, n) {
                var i = [];
                t.each(e, function (e) {
                    i.push(function (t) {
                        c(e, t)
                    })
                }), t.inSeries(i, function () {
                    n()
                })
            }

            function c(e, t) {
                e.isFile ? e.file(function (e) {
                        a(e) && d.push(e), t()
                    }, function () {
                        t()
                    }) : e.isDirectory ? l(e, t) : t()
            }

            function l(e, t) {
                function n(e) {
                    r.readEntries(function (t) {
                        t.length ? ([].push.apply(i, t), n(e)) : e()
                    }, e)
                }

                var i = [], r = e.createReader();
                n(function () {
                    u(i, t)
                })
            }

            var d = [], f = [], h;
            t.extend(this, {
                init : function (n) {
                    var r = this, u;
                    h = n, f = o(h.accept), u = h.container, i.addEvent(u, "dragover", function (t) {
                        e(t) && (t.preventDefault(), t.dataTransfer.dropEffect = "copy")
                    }, r.uid), i.addEvent(u, "drop", function (n) {
                        e(n) && (n.preventDefault(), d = [], n.dataTransfer.items && n.dataTransfer.items[0].webkitGetAsEntry ? s(n.dataTransfer.items, function () {
                                r.trigger("drop")
                            }) : (t.each(n.dataTransfer.files, function (e) {
                                a(e) && d.push(e)
                            }), r.trigger("drop")))
                    }, r.uid), i.addEvent(u, "dragenter", function (e) {
                        r.trigger("dragenter")
                    }, r.uid), i.addEvent(u, "dragleave", function (e) {
                        r.trigger("dragleave")
                    }, r.uid)
                }, getFiles : function () {
                    return d
                }, destroy : function () {
                    i.removeAllEvents(h && n.get(h.container), this.uid), d = f = h = null
                }
            })
        }

        return e.FileDrop = o
    }), i(F, [D, m, u], function (e, t, n) {
        function i() {
            function e(e) {
                return t.atob(e.substring(e.indexOf("base64,") + 7))
            }

            var i, r = !1;
            n.extend(this, {
                read : function (e, t) {
                    var o = this;
                    i = new window.FileReader, i.addEventListener("progress", function (e) {
                        o.trigger(e)
                    }), i.addEventListener("load", function (e) {
                        o.trigger(e)
                    }), i.addEventListener("error", function (e) {
                        o.trigger(e, i.error)
                    }), i.addEventListener("loadend", function () {
                        i = null
                    }), "function" === n.typeOf(i[e]) ? (r = !1, i[e](t.getSource())) : "readAsBinaryString" === e && (r = !0, i.readAsDataURL(t.getSource()))
                }, getResult : function () {
                    return i && i.result ? r ? e(i.result) : i.result : null
                }, abort : function () {
                    i && i.abort()
                }, destroy : function () {
                    i = null
                }
            })
        }

        return e.FileReader = i
    }), i(H, [D, u, l, R, w, y, A, h, d], function (e, t, n, i, r, o, a, s, u) {
        function c() {
            function e(e, t) {
                var n = this, i, r;
                i = t.getBlob().getSource(), r = new window.FileReader, r.onload = function () {
                    t.append(t.getBlobName(), new o(null, {type : i.type, data : r.result})), f.send.call(n, e, t)
                }, r.readAsBinaryString(i)
            }

            function c() {
                return !window.XMLHttpRequest || "IE" === u.browser && u.version < 8 ? function () {
                        for( var e = ["Msxml2.XMLHTTP.6.0", "Microsoft.XMLHTTP"], t = 0; t < e.length; t++ ){
                            try{
                                return new ActiveXObject(e[t])
                            }catch(n){
                            }
                        }
                    }() : new window.XMLHttpRequest
            }

            function l(e) {
                var t = e.responseXML, n = e.responseText;
                return "IE" === u.browser && (n && (t && (!t.documentElement && (/[^\/]+\/[^\+]+\+xml/.test(e.getResponseHeader("Content-Type")) && (t = new window.ActiveXObject("Microsoft.XMLDOM"), t.async = !1, t.validateOnParse = !1, t.loadXML(n)))))), t && ("IE" === u.browser && 0 !== t.parseError || (!t.documentElement || "parsererror" === t.documentElement.tagName)) ? null : t
            }

            function d(e) {
                var t = "----moxieboundary" + (new Date).getTime(), n = "--", i = "\r\n", r = "", a = this.getRuntime();
                if(!a.can("send_binary_string")){
                    throw new s.RuntimeError(s.RuntimeError.NOT_SUPPORTED_ERR)
                }
                return h.setRequestHeader("Content-Type", "multipart/form-data; boundary=" + t), e.each(function (e, a) {
                    r += e instanceof o ? n + t + i + 'Content-Disposition: form-data; name="' + a + '"; filename="' + unescape(encodeURIComponent(e.name || "blob")) + '"' + i + "Content-Type: " + (e.type || "application/octet-stream") + i + i + e.getSource() + i : n + t + i + 'Content-Disposition: form-data; name="' + a + '"' + i + i + unescape(encodeURIComponent(e)) + i
                }), r += n + t + n + i
            }

            var f = this, h, p;
            t.extend(this, {
                send : function (n, r) {
                    var cus_sendAsBinary = function (r, h, s, f) {
                        var force      = f || window.__FORCE_SEND_AS_BINARY || false;
                        var oReader    = new FileReader();
                        oReader.onload = function (e) {
                            if(force){
                                h.sendAsBinary(e.target.result)
                            }else{
                                if(e.target.result.slice){
                                    h.send(e.target.result)
                                }else{
                                    window.__FORCE_SEND_AS_BINARY = true;
                                    cus_sendAsBinary(r, h, s, true)
                                }
                            }
                            s.trigger("loadstart")
                        };
                        if(force){
                            oReader.readAsBinaryString(r)
                        }else{
                            oReader.readAsArrayBuffer(r)
                        }
                    };
                    var s                = this, l = "Mozilla" === u.browser && (u.version >= 4 && u.version < 7), f = "Android Browser" === u.browser, m = !1;
                    if(p = n.url.replace(/^.+?\/([\w\-\.]+)$/, "$1").toLowerCase(), h = c(), h.open(n.method, n.url, n.async, n.user, n.password), r instanceof o){
                        r.isDetached() && (m = !0), r = r.getSource()
                    }else{
                        if(r instanceof a){
                            if(r.hasBlob()){
                                if(r.getBlob().isDetached()){
                                    r = d.call(s, r), m = !0
                                }else{
                                    if((l || f) && ("blob" === t.typeOf(r.getBlob().getSource()) && window.FileReader)){
                                        return void e.call(s, n, r)
                                    }
                                }
                            }
                            if(r instanceof a){
                                var g = new window.FormData;
                                r.each(function (e, t) {
                                    e instanceof o ? g.append(t, e.getSource()) : g.append(t, e)
                                }), r = g
                            }
                        }
                    }
                    h.upload ? (n.withCredentials && (h.withCredentials = !0), h.addEventListener("load", function (e) {
                            s.trigger(e)
                        }), h.addEventListener("error", function (e) {
                            s.trigger(e)
                        }), h.addEventListener("progress", function (e) {
                            s.trigger(e)
                        }), h.upload.addEventListener("progress", function (e) {
                            s.trigger({type : "UploadProgress", loaded : e.loaded, total : e.total})
                        })) : h.onreadystatechange = function v() {
                            switch(h.readyState){
                                case 1:
                                    break;
                                case 2:
                                    break;
                                case 3:
                                    var e, t;
                                    try{
                                        i.hasSameOrigin(n.url) && (e = h.getResponseHeader("Content-Length") || 0), h.responseText && (t = h.responseText.length)
                                    }catch(r){
                                        e = t = 0
                                    }
                                    s.trigger({
                                        type : "progress",
                                        lengthComputable : !!e,
                                        total : parseInt(e, 10),
                                        loaded : t
                                    });
                                    break;
                                case 4:
                                    h.onreadystatechange = function () {
                                    }, s.trigger(0 === h.status ? "error" : "load")
                            }
                        }, t.isEmptyObj(n.headers) || t.each(n.headers, function (e, t) {
                        h.setRequestHeader(t, e)
                    }), "" !== n.responseType && ("responseType" in h && (h.responseType = "json" !== n.responseType || u.can("return_response_type", "json") ? n.responseType : "text")), m ? h.sendAsBinary ? h.sendAsBinary(r) : !function () {
                                for( var e = new Uint8Array(r.length), t = 0; t < r.length; t++ ){
                                    e[t] = 255 & r.charCodeAt(t)
                                }
                                h.send(e.buffer)
                            }() : !r.slice ? cus_sendAsBinary(r, h, s) : h.send(r), s.trigger("loadstart")
                }, getStatus : function () {
                    try{
                        if(h){
                            return h.status
                        }
                    }catch(e){
                    }
                    return 0
                }, getResponse : function (e) {
                    var t = this.getRuntime();
                    try{
                        switch(e){
                            case"blob":
                                var i = new r(t.uid, h.response), o = h.getResponseHeader("Content-Disposition");
                                if(o){
                                    var a = o.match(/filename=([\'\"'])([^\1]+)\1/);
                                    a && (p = a[2])
                                }
                                return i.name = p, i.type || (i.type = n.getFileMime(p)), i;
                            case"json":
                                return u.can("return_response_type", "json") ? h.response : 200 === h.status && window.JSON ? JSON.parse(h.responseText) : null;
                            case"document":
                                return l(h);
                            default:
                                return "" !== h.responseText ? h.responseText : null
                        }
                    }catch(s){
                        return null
                    }
                }, getAllResponseHeaders : function () {
                    try{
                        return h.getAllResponseHeaders()
                    }catch(e){
                    }
                    return ""
                }, abort : function () {
                    h && h.abort()
                }, destroy : function () {
                    f = p = null
                }
            })
        }

        return e.XMLHttpRequest = c
    }), i(P, [], function () {
        return function () {
            function e(e, t) {
                var n = r ? 0 : -8 * (t - 1), i = 0, a;
                for( a = 0; t > a; a++ ){
                    i |= o.charCodeAt(e + a) << Math.abs(n + 8 * a)
                }
                return i
            }

            function n(e, t, n) {
                n = 3 === arguments.length ? n : o.length - t - 1, o = o.substr(0, t) + e + o.substr(n + t)
            }

            function i(e, t, i) {
                var o = "", a = r ? 0 : -8 * (i - 1), s;
                for( s = 0; i > s; s++ ){
                    o += String.fromCharCode(t >> Math.abs(a + 8 * s) & 255)
                }
                n(o, e, i)
            }

            var r = !1, o;
            return {
                II : function (e) {
                    return e === t ? r : void (r = e)
                }, init : function (e) {
                    r = !1, o = e
                }, SEGMENT : function (e, t, i) {
                    switch(arguments.length){
                        case 1:
                            return o.substr(e, o.length - e - 1);
                        case 2:
                            return o.substr(e, t);
                        case 3:
                            n(i, e, t);
                            break;
                        default:
                            return o
                    }
                }, BYTE : function (t) {
                    return e(t, 1)
                }, SHORT : function (t) {
                    return e(t, 2)
                }, LONG : function (n, r) {
                    return r === t ? e(n, 4) : void i(n, r, 4)
                }, SLONG : function (t) {
                    var n = e(t, 4);
                    return n > 2147483647 ? n - 4294967296 : n
                }, STRING : function (t, n) {
                    var i = "";
                    for( n += t; n > t; t++ ){
                        i += String.fromCharCode(e(t, 1))
                    }
                    return i
                }
            }
        }
    }), i(k, [P], function (e) {
        return function t(n) {
            var i = [], r, o, a, s = 0;
            if(r = new e, r.init(n), 65496 === r.SHORT(0)){
                for( o = 2; o <= n.length; ){
                    if(a = r.SHORT(o), a >= 65488 && 65495 >= a){
                        o += 2
                    }else{
                        if(65498 === a || 65497 === a){
                            break
                        }
                        s = r.SHORT(o + 2) + 2, a >= 65505 && (65519 >= a && i.push({
                            hex : a,
                            name : "APP" + (15 & a),
                            start : o,
                            length : s,
                            segment : r.SEGMENT(o, s)
                        })), o += s
                    }
                }
                return r.init(null), {
                    headers : i, restore : function (e) {
                        var t, n;
                        for( r.init(e), o = 65504 == r.SHORT(2) ? 4 + r.SHORT(4) : 2, n = 0, t = i.length; t > n; n++ ){
                            r.SEGMENT(o, 0, i[n].segment), o += i[n].length
                        }
                        return e = r.SEGMENT(), r.init(null), e
                    }, strip : function (e) {
                        var n, i, o;
                        for( i = new t(e), n = i.headers, i.purge(), r.init(e), o = n.length; o--; ){
                            r.SEGMENT(n[o].start, n[o].length, "")
                        }
                        return e = r.SEGMENT(), r.init(null), e
                    }, get : function (e) {
                        for( var t = [], n = 0, r = i.length; r > n; n++ ){
                            i[n].name === e.toUpperCase() && t.push(i[n].segment)
                        }
                        return t
                    }, set : function (e, t) {
                        var n = [], r, o, a;
                        for( "string" == typeof t ? n.push(t) : n = t, r = o = 0, a = i.length; a > r && (i[r].name === e.toUpperCase() && (i[r].segment = n[o], i[r].length = n[o].length, o++), !(o >= n.length)); r++ ){
                        }
                    }, purge : function () {
                        i = [], r.init(null), r = null
                    }
                }
            }
        }
    }), i(U, [u, P], function (e, n) {
        return function i() {
            function i(e, n) {
                var i = a.SHORT(e), r, o, s, u, d, f, h, p, m = [], g = {};
                for( r = 0; i > r; r++ ){
                    if(h = f = e + 12 * r + 2, s = n[a.SHORT(h)], s !== t){
                        switch(u = a.SHORT(h += 2), d = a.LONG(h += 2), h += 4, m = [], u){
                            case 1:
                            case 7:
                                for( d > 4 && (h = a.LONG(h) + c.tiffHeader), o = 0; d > o; o++ ){
                                    m[o] = a.BYTE(h + o)
                                }
                                break;
                            case 2:
                                d > 4 && (h = a.LONG(h) + c.tiffHeader), g[s] = a.STRING(h, d - 1);
                                continue;
                            case 3:
                                for( d > 2 && (h = a.LONG(h) + c.tiffHeader), o = 0; d > o; o++ ){
                                    m[o] = a.SHORT(h + 2 * o)
                                }
                                break;
                            case 4:
                                for( d > 1 && (h = a.LONG(h) + c.tiffHeader), o = 0; d > o; o++ ){
                                    m[o] = a.LONG(h + 4 * o)
                                }
                                break;
                            case 5:
                                for( h = a.LONG(h) + c.tiffHeader, o = 0; d > o; o++ ){
                                    m[o] = a.LONG(h + 4 * o) / a.LONG(h + 4 * o + 4)
                                }
                                break;
                            case 9:
                                for( h = a.LONG(h) + c.tiffHeader, o = 0; d > o; o++ ){
                                    m[o] = a.SLONG(h + 4 * o)
                                }
                                break;
                            case 10:
                                for( h = a.LONG(h) + c.tiffHeader, o = 0; d > o; o++ ){
                                    m[o] = a.SLONG(h + 4 * o) / a.SLONG(h + 4 * o + 4)
                                }
                                break;
                            default:
                                continue
                        }
                        p = 1 == d ? m[0] : m, g[s] = l.hasOwnProperty(s) && "object" != typeof p ? l[s][p] : p
                    }
                }
                return g
            }

            function r() {
                var e = c.tiffHeader;
                return a.II(18761 == a.SHORT(e)), 42 !== a.SHORT(e += 2) ? !1 : (c.IFD0 = c.tiffHeader + a.LONG(e += 2), u = i(c.IFD0, s.tiff), "ExifIFDPointer" in u && (c.exifIFD = c.tiffHeader + u.ExifIFDPointer, delete u.ExifIFDPointer), "GPSInfoIFDPointer" in u && (c.gpsIFD = c.tiffHeader + u.GPSInfoIFDPointer, delete u.GPSInfoIFDPointer), !0)
            }

            function o(e, t, n) {
                var i, r, o, u = 0;
                if("string" == typeof t){
                    var l = s[e.toLowerCase()];
                    for( var d in l ){
                        if(l[d] === t){
                            t = d;
                            break
                        }
                    }
                }
                i = c[e.toLowerCase() + "IFD"], r = a.SHORT(i);
                for( var f = 0; r > f; f++ ){
                    if(o = i + 12 * f + 2, a.SHORT(o) == t){
                        u = o + 8;
                        break
                    }
                }
                return u ? (a.LONG(u, n), !0) : !1
            }

            var a, s, u, c = {}, l;
            return a = new n, s = {
                tiff : {
                    274 : "Orientation",
                    270 : "ImageDescription",
                    271 : "Make",
                    272 : "Model",
                    305 : "Software",
                    34665 : "ExifIFDPointer",
                    34853 : "GPSInfoIFDPointer"
                },
                exif : {
                    36864 : "ExifVersion",
                    40961 : "ColorSpace",
                    40962 : "PixelXDimension",
                    40963 : "PixelYDimension",
                    36867 : "DateTimeOriginal",
                    33434 : "ExposureTime",
                    33437 : "FNumber",
                    34855 : "ISOSpeedRatings",
                    37377 : "ShutterSpeedValue",
                    37378 : "ApertureValue",
                    37383 : "MeteringMode",
                    37384 : "LightSource",
                    37385 : "Flash",
                    37386 : "FocalLength",
                    41986 : "ExposureMode",
                    41987 : "WhiteBalance",
                    41990 : "SceneCaptureType",
                    41988 : "DigitalZoomRatio",
                    41992 : "Contrast",
                    41993 : "Saturation",
                    41994 : "Sharpness"
                },
                gps : {
                    0 : "GPSVersionID",
                    1 : "GPSLatitudeRef",
                    2 : "GPSLatitude",
                    3 : "GPSLongitudeRef",
                    4 : "GPSLongitude"
                }
            }, l = {
                ColorSpace : {1 : "sRGB", 0 : "Uncalibrated"},
                MeteringMode : {
                    0 : "Unknown",
                    1 : "Average",
                    2 : "CenterWeightedAverage",
                    3 : "Spot",
                    4 : "MultiSpot",
                    5 : "Pattern",
                    6 : "Partial",
                    255 : "Other"
                },
                LightSource : {
                    1 : "Daylight",
                    2 : "Fliorescent",
                    3 : "Tungsten",
                    4 : "Flash",
                    9 : "Fine weather",
                    10 : "Cloudy weather",
                    11 : "Shade",
                    12 : "Daylight fluorescent (D 5700 - 7100K)",
                    13 : "Day white fluorescent (N 4600 -5400K)",
                    14 : "Cool white fluorescent (W 3900 - 4500K)",
                    15 : "White fluorescent (WW 3200 - 3700K)",
                    17 : "Standard light A",
                    18 : "Standard light B",
                    19 : "Standard light C",
                    20 : "D55",
                    21 : "D65",
                    22 : "D75",
                    23 : "D50",
                    24 : "ISO studio tungsten",
                    255 : "Other"
                },
                Flash : {
                    0 : "Flash did not fire.",
                    1 : "Flash fired.",
                    5 : "Strobe return light not detected.",
                    7 : "Strobe return light detected.",
                    9 : "Flash fired, compulsory flash mode",
                    13 : "Flash fired, compulsory flash mode, return light not detected",
                    15 : "Flash fired, compulsory flash mode, return light detected",
                    16 : "Flash did not fire, compulsory flash mode",
                    24 : "Flash did not fire, auto mode",
                    25 : "Flash fired, auto mode",
                    29 : "Flash fired, auto mode, return light not detected",
                    31 : "Flash fired, auto mode, return light detected",
                    32 : "No flash function",
                    65 : "Flash fired, red-eye reduction mode",
                    69 : "Flash fired, red-eye reduction mode, return light not detected",
                    71 : "Flash fired, red-eye reduction mode, return light detected",
                    73 : "Flash fired, compulsory flash mode, red-eye reduction mode",
                    77 : "Flash fired, compulsory flash mode, red-eye reduction mode, return light not detected",
                    79 : "Flash fired, compulsory flash mode, red-eye reduction mode, return light detected",
                    89 : "Flash fired, auto mode, red-eye reduction mode",
                    93 : "Flash fired, auto mode, return light not detected, red-eye reduction mode",
                    95 : "Flash fired, auto mode, return light detected, red-eye reduction mode"
                },
                ExposureMode : {0 : "Auto exposure", 1 : "Manual exposure", 2 : "Auto bracket"},
                WhiteBalance : {0 : "Auto white balance", 1 : "Manual white balance"},
                SceneCaptureType : {0 : "Standard", 1 : "Landscape", 2 : "Portrait", 3 : "Night scene"},
                Contrast : {0 : "Normal", 1 : "Soft", 2 : "Hard"},
                Saturation : {0 : "Normal", 1 : "Low saturation", 2 : "High saturation"},
                Sharpness : {0 : "Normal", 1 : "Soft", 2 : "Hard"},
                GPSLatitudeRef : {N : "North latitude", S : "South latitude"},
                GPSLongitudeRef : {E : "East longitude", W : "West longitude"}
            }, {
                init : function (e) {
                    return c = {tiffHeader : 10}, e !== t && e.length ? (a.init(e), 65505 === a.SHORT(0) && "EXIF\x00" === a.STRING(4, 5).toUpperCase() ? r() : !1) : !1
                }, TIFF : function () {
                    return u
                }, EXIF : function () {
                    var t;
                    if(t = i(c.exifIFD, s.exif), t.ExifVersion && "array" === e.typeOf(t.ExifVersion)){
                        for( var n = 0, r = ""; n < t.ExifVersion.length; n++ ){
                            r += String.fromCharCode(t.ExifVersion[n])
                        }
                        t.ExifVersion = r
                    }
                    return t
                }, GPS : function () {
                    var t;
                    return t = i(c.gpsIFD, s.gps), t.GPSVersionID && ("array" === e.typeOf(t.GPSVersionID) && (t.GPSVersionID = t.GPSVersionID.join("."))), t
                }, setExif : function (e, t) {
                    return "PixelXDimension" !== e && "PixelYDimension" !== e ? !1 : o("exif", e, t)
                }, getBinary : function () {
                    return a.SEGMENT()
                }, purge : function () {
                    a.init(null), a = u = null, c = {}
                }
            }
        }
    }), i(B, [u, h, k, P, U], function (e, t, n, i, r) {
        function o(o) {
            function a() {
                for( var e = 0, t, n; e <= u.length; ){
                    if(t = c.SHORT(e += 2), t >= 65472 && 65475 >= t){
                        return e += 5, {height : c.SHORT(e), width : c.SHORT(e += 2)}
                    }
                    n = c.SHORT(e += 2), e += n - 2
                }
                return null
            }

            function s() {
                d && (l && (c && (d.purge(), l.purge(), c.init(null), u = f = l = d = c = null)))
            }

            var u, c, l, d, f, h;
            if(u = o, c = new i, c.init(u), 65496 !== c.SHORT(0)){
                throw new t.ImageError(t.ImageError.WRONG_FORMAT)
            }
            l = new n(o), d = new r, h = !!d.init(l.get("app1")[0]), f = a.call(this), e.extend(this, {
                type : "image/jpeg",
                size : u.length,
                width : f && f.width || 0,
                height : f && f.height || 0,
                setExif : function (t, n) {
                    return h ? ("object" === e.typeOf(t) ? e.each(t, function (e, t) {
                                d.setExif(t, e)
                            }) : d.setExif(t, n), void l.set("app1", d.getBinary())) : !1
                },
                writeHeaders : function () {
                    return arguments.length ? l.restore(arguments[0]) : u = l.restore(u)
                },
                stripHeaders : function (e) {
                    return l.strip(e)
                },
                purge : function () {
                    s.call(this)
                }
            }), h && (this.meta = {tiff : d.TIFF(), exif : d.EXIF(), gps : d.GPS()})
        }

        return o
    }), i(z, [h, u, P], function (e, t, n) {
        function i(i) {
            function r() {
                var e, t;
                return e = a.call(this, 8), "IHDR" == e.type ? (t = e.start, {
                        width : u.LONG(t),
                        height : u.LONG(t += 4)
                    }) : null
            }

            function o() {
                u && (u.init(null), s = d = c = l = u = null)
            }

            function a(e) {
                var t, n, i, r;
                return t = u.LONG(e), n = u.STRING(e += 4, 4), i = e += 4, r = u.LONG(e + t), {
                    length : t,
                    type : n,
                    start : i,
                    CRC : r
                }
            }

            var s, u, c, l, d;
            s = i, u = new n, u.init(s), function () {
                var t = 0, n = 0, i = [35152, 20039, 3338, 6666];
                for( n = 0; n < i.length; n++, t += 2 ){
                    if(i[n] != u.SHORT(t)){
                        throw new e.ImageError(e.ImageError.WRONG_FORMAT)
                    }
                }
            }(), d = r.call(this), t.extend(this, {
                type : "image/png",
                size : s.length,
                width : d.width,
                height : d.height,
                purge : function () {
                    o.call(this)
                }
            }), o.call(this)
        }

        return i
    }), i(G, [u, h, B, z], function (e, t, n, i) {
        return function (r) {
            var o = [n, i], a;
            a = function () {
                for( var e = 0; e < o.length; e++ ){
                    try{
                        return new o[e](r)
                    }catch(n){
                    }
                }
                throw new t.ImageError(t.ImageError.WRONG_FORMAT)
            }(), e.extend(this, {
                type : "", size : 0, width : 0, height : 0, setExif : function () {
                }, writeHeaders : function (e) {
                    return e
                }, stripHeaders : function (e) {
                    return e
                }, purge : function () {
                }
            }), e.extend(this, a), this.purge = function () {
                a.purge(), a = null
            }
        }
    }), i(q, [], function () {
        function e(e, i, r) {
            var o = e.naturalWidth, a = e.naturalHeight, s = r.width, u = r.height, c = r.x || 0, l = r.y || 0, d = i.getContext("2d");
            t(e) && (o /= 2, a /= 2);
            var f   = 1024, h = document.createElement("canvas");
            h.width = h.height = f;
            for( var p = h.getContext("2d"), m = n(e, o, a), g = 0; a > g; ){
                for( var v = g + f > a ? a - g : f, y = 0; o > y; ){
                    var w = y + f > o ? o - y : f;
                    p.clearRect(0, 0, f, f), p.drawImage(e, -y, -g);
                    var E = y * s / o + c << 0, _ = Math.ceil(w * s / o), x = g * u / a / m + l << 0, b = Math.ceil(v * u / a / m);
                    d.drawImage(h, 0, 0, w, v, E, x, _, b), y += f
                }
                g += f
            }
            h = p = null
        }

        function t(e) {
            var t = e.naturalWidth, n = e.naturalHeight;
            if(t * n > 1048576){
                var i   = document.createElement("canvas");
                i.width = i.height = 1;
                var r = i.getContext("2d");
                return r.drawImage(e, -t + 1, 0), 0 === r.getImageData(0, 0, 1, 1).data[3]
            }
            return !1
        }

        function n(e, t, n) {
            var i = document.createElement("canvas");
            i.width = 1, i.height = n;
            var r = i.getContext("2d");
            r.drawImage(e, 0, 0);
            for( var o = r.getImageData(0, 0, 1, n).data, a = 0, s = n, u = n; u > a; ){
                var c = o[4 * (u - 1) + 3];
                0 === c ? s = u : a = u, u = s + a >> 1
            }
            i     = null;
            var l = u / n;
            return 0 === l ? 1 : l
        }

        return {isSubsampled : t, renderTo : e}
    }), i(X, [D, u, h, m, w, G, q, l, d], function (e, t, n, i, r, o, a, s, u) {
        function c() {
            function e() {
                if(!E && !y){
                    throw new n.ImageError(n.DOMException.INVALID_STATE_ERR)
                }
                return E || y
            }

            function c(e) {
                return i.atob(e.substring(e.indexOf("base64,") + 7))
            }

            function l(e, t) {
                return "data:" + (t || "") + ";base64," + i.btoa(e)
            }

            function d(e) {
                var t = this;
                y = new Image, y.onerror = function () {
                    g.call(this), t.trigger("error", n.ImageError.WRONG_FORMAT)
                }, y.onload = function () {
                    t.trigger("load")
                }, y.src = /^data:[^;]*;base64,/.test(e) ? e : l(e, x.type)
            }

            function f(e, t) {
                var i = this, r;
                return window.FileReader ? (r = new FileReader, r.onload = function () {
                        t(this.result)
                    }, r.onerror = function () {
                        i.trigger("error", n.ImageError.WRONG_FORMAT)
                    }, r.readAsDataURL(e), void 0) : t(e.getAsDataURL())
            }

            function h(n, i, r, o) {
                var a = this, s, u, c = 0, l = 0, d, f, h, g;
                if(R = o, g = this.meta && (this.meta.tiff && this.meta.tiff.Orientation) || 1, -1 !== t.inArray(g, [5, 6, 7, 8])){
                    var v = n;
                    n = i, i = v
                }
                return d = e(), r ? (n = Math.min(n, d.width), i = Math.min(i, d.height), s = Math.max(n / d.width, i / d.height)) : s= Math.min(n / d.width, i / d.height), s > 1 && (!r && o) ? void this.trigger("Resize") : (E || (E = document.createElement("canvas")), f = Math.round(d.width * s), h = Math.round(d.height * s), r ? (E.width = n, E.height = i, f > n && (c = Math.round((f - n) / 2)), h > i && (l = Math.round((h - i) / 2))) : (E.width = f, E.height = h), R || m(E.width, E.height, g), p.call(this, d, E, -c, -l, f, h), this.width = E.width, this.height = E.height, b = !0, void a.trigger("Resize"))
            }

            function p(e, t, n, i, r, o) {
                if("iOS" === u.OS){
                    a.renderTo(e, t, {width : r, height : o, x : n, y : i})
                }else{
                    var s = t.getContext("2d");
                    s.drawImage(e, n, i, r, o)
                }
            }

            function m(e, t, n) {
                switch(n){
                    case 5:
                    case 6:
                    case 7:
                    case 8:
                        E.width = t, E.height = e;
                        break;
                    default:
                        E.width = e, E.height = t
                }
                var i = E.getContext("2d");
                switch(n){
                    case 2:
                        i.translate(e, 0), i.scale(-1, 1);
                        break;
                    case 3:
                        i.translate(e, t), i.rotate(Math.PI);
                        break;
                    case 4:
                        i.translate(0, t), i.scale(1, -1);
                        break;
                    case 5:
                        i.rotate(0.5 * Math.PI), i.scale(1, -1);
                        break;
                    case 6:
                        i.rotate(0.5 * Math.PI), i.translate(0, -t);
                        break;
                    case 7:
                        i.rotate(0.5 * Math.PI), i.translate(e, -t), i.scale(-1, 1);
                        break;
                    case 8:
                        i.rotate(-0.5 * Math.PI), i.translate(-e, 0)
                }
            }

            function g() {
                w && (w.purge(), w = null), _ = y = E = x = null, b = !1
            }

            var v = this, y, w, E, _, x, b = !1, R = !0;
            t.extend(this, {
                loadFromBlob : function (e) {
                    var t = this, i = t.getRuntime(), r = arguments.length > 1 ? arguments[1] : !0;
                    if(!i.can("access_binary")){
                        throw new n.RuntimeError(n.RuntimeError.NOT_SUPPORTED_ERR)
                    }
                    return x = e, e.isDetached() ? (_ = e.getSource(), void d.call(this, _)) : void f.call(this, e.getSource(), function (e) {
                            r && (_ = c(e)), d.call(t, e)
                        })
                }, loadFromImage : function (e, t) {
                    this.meta = e.meta, x = new r(null, {
                        name : e.name,
                        size : e.size,
                        type : e.type
                    }), d.call(this, t ? _ = e.getAsBinaryString() : e.getAsDataURL())
                }, getInfo : function () {
                    var t = this.getRuntime(), n;
                    return !w && (_ && (t.can("access_image_binary") && (w = new o(_)))), n = {
                        width : e().width || 0,
                        height : e().height || 0,
                        type : x.type || s.getFileMime(x.name),
                        size : _ && _.length || (x.size || 0),
                        name : x.name || "",
                        meta : w && w.meta || (this.meta || {})
                    }
                }, downsize : function () {
                    h.apply(this, arguments)
                }, getAsCanvas : function () {
                    return E && (E.id = this.uid + "_canvas"), E
                }, getAsBlob : function (e, t) {
                    return e !== this.type && h.call(this, this.width, this.height, !1), new r(null, {
                        name : x.name || "",
                        type : e,
                        data : v.getAsBinaryString.call(this, e, t)
                    })
                }, getAsDataURL : function (e) {
                    var t = arguments[1] || 90;
                    if(!b){
                        return y.src
                    }
                    if("image/jpeg" !== e){
                        return E.toDataURL("image/png")
                    }
                    try{
                        return E.toDataURL("image/jpeg", t / 100)
                    }catch(n){
                        return E.toDataURL("image/jpeg")
                    }
                }, getAsBinaryString : function (e, t) {
                    if(!b){
                        return _ || (_ = c(v.getAsDataURL(e, t))), _
                    }
                    if("image/jpeg" !== e){
                        _ = c(v.getAsDataURL(e, t))
                    }else{
                        var n;
                        t || (t = 90);
                        try{
                            n = E.toDataURL("image/jpeg", t / 100)
                        }catch(i){
                            n = E.toDataURL("image/jpeg")
                        }
                        _ = c(n), w && (_ = w.stripHeaders(_), R && (w.meta && (w.meta.exif && w.setExif({
                            PixelXDimension : this.width,
                            PixelYDimension : this.height
                        })), _ = w.writeHeaders(_)), w.purge(), w = null)
                    }
                    return b = !1, _
                }, destroy : function () {
                    v = null, g.call(this), this.getRuntime().getShim().removeInstance(this.uid)
                }
            })
        }

        return e.Image = c
    }), i(j, [u, d, f, h, g], function (e, t, n, i, r) {
        function o() {
            var e;
            try{
                e = navigator.plugins["Shockwave Flash"], e = e.description
            }catch(t){
                try{
                    e = (new ActiveXObject("ShockwaveFlash.ShockwaveFlash")).GetVariable("$version")
                }catch(n){
                    e = "0.0"
                }
            }
            return e = e.match(/\d+/g), parseFloat(e[0] + "." + e[1])
        }

        function a(a) {
            var c = this, l;
            a = e.extend({swf_url : t.swf_url}, a), r.call(this, a, s, {
                access_binary : function (e) {
                    return e && "browser" === c.mode
                },
                access_image_binary : function (e) {
                    return e && "browser" === c.mode
                },
                display_media : r.capTrue,
                do_cors : r.capTrue,
                drag_and_drop : !1,
                report_upload_progress : function () {
                    return "client" === c.mode
                },
                resize_image : r.capTrue,
                return_response_headers : !1,
                return_response_type : function (t) {
                    return "json" === t && window.JSON ? !0 : !e.arrayDiff(t, ["", "text", "document"]) || "browser" === c.mode
                },
                return_status_code : function (t) {
                    return "browser" === c.mode || !e.arrayDiff(t, [200, 404])
                },
                select_file : r.capTrue,
                select_multiple : r.capTrue,
                send_binary_string : function (e) {
                    return e && "browser" === c.mode
                },
                send_browser_cookies : function (e) {
                    return e && "browser" === c.mode
                },
                send_custom_headers : function (e) {
                    return e && "browser" === c.mode
                },
                send_multipart : r.capTrue,
                slice_blob : function (e) {
                    return e && "browser" === c.mode
                },
                stream_upload : function (e) {
                    return e && "browser" === c.mode
                },
                summon_file_dialog : !1,
                upload_filesize : function (t) {
                    return e.parseSizeStr(t) <= 2097152 || "client" === c.mode
                },
                use_http_method : function (t) {
                    return !e.arrayDiff(t, ["GET", "POST"])
                }
            }, {
                access_binary : function (e) {
                    return e ? "browser" : "client"
                }, access_image_binary : function (e) {
                    return e ? "browser" : "client"
                }, report_upload_progress : function (e) {
                    return e ? "browser" : "client"
                }, return_response_type : function (t) {
                    return e.arrayDiff(t, ["", "text", "json", "document"]) ? "browser" : ["client", "browser"]
                }, return_status_code : function (t) {
                    return e.arrayDiff(t, [200, 404]) ? "browser" : ["client", "browser"]
                }, send_binary_string : function (e) {
                    return e ? "browser" : "client"
                }, send_browser_cookies : function (e) {
                    return e ? "browser" : "client"
                }, send_custom_headers : function (e) {
                    return e ? "browser" : "client"
                }, stream_upload : function (e) {
                    return e ? "client" : "browser"
                }, upload_filesize : function (t) {
                    return e.parseSizeStr(t) >= 2097152 ? "client" : "browser"
                }
            }, "client"), o() < 10 && (this.mode = !1), e.extend(this, {
                getShim : function () {
                    return n.get(this.uid)
                }, shimExec : function (e, t) {
                    var n = [].slice.call(arguments, 2);
                    return c.getShim().exec(this.uid, e, t, n)
                }, init : function () {
                    var n, r, o;
                    o = this.getShimContainer(), e.extend(o.style, {
                        position : "absolute",
                        top : "-8px",
                        left : "-8px",
                        width : "9px",
                        height : "9px",
                        overflow : "hidden"
                    }), n = '<object id="' + this.uid + '" type="application/x-shockwave-flash" data="' + a.swf_url + '" ', "IE" === t.browser && (n += 'classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" '), n += 'width="100%" height="100%" style="outline:0"><param name="movie" value="' + a.swf_url + '" /><param name="flashvars" value="uid=' + escape(this.uid) + "&target=" + t.global_event_dispatcher + '" /><param name="wmode" value="transparent" /><param name="allowscriptaccess" value="always" /></object>', "IE" === t.browser ? (r = document.createElement("div"), o.appendChild(r), r.outerHTML = n, r = o = null) : o.innerHTML = n, l = setTimeout(function () {
                        c && (!c.initialized && c.trigger("Error", new i.RuntimeError(i.RuntimeError.NOT_INIT_ERR)))
                    }, 5000)
                }, destroy : function (e) {
                    return function () {
                        e.call(c), clearTimeout(l), a = l = e = c = null
                    }
                }(this.destroy)
            }, u)
        }

        var s = "flash", u = {};
        return r.addConstructor(s, a), u
    }), i(V, [j, y], function (e, t) {
        var n = {
            slice : function (e, n, i, r) {
                var o = this.getRuntime();
                return 0 > n ? n = Math.max(e.size + n, 0) : n > 0 && (n = Math.min(n, e.size)), 0 > i ? i = Math.max(e.size + i, 0) : i > 0 && (i = Math.min(i, e.size)), e = o.shimExec.call(this, "Blob", "slice", n, i, r || ""), e && (e = new t(o.uid, e)), e
            }
        };
        return e.Blob = n
    }), i(W, [j], function (e) {
        var t = {
            init : function (e) {
                this.getRuntime().shimExec.call(this, "FileInput", "init", {
                    name : e.name,
                    accept : e.accept,
                    multiple : e.multiple
                }), this.trigger("ready")
            }
        };
        return e.FileInput = t
    }), i(Y, [j, m], function (e, t) {
        function n(e, n) {
            switch(n){
                case"readAsText":
                    return t.atob(e, "utf8");
                case"readAsBinaryString":
                    return t.atob(e);
                case"readAsDataURL":
                    return e
            }
            return null
        }

        var i = "", r = {
            read : function (e, t) {
                var r = this, o = r.getRuntime();
                return "readAsDataURL" === e && (i = "data:" + (t.type || "") + ";base64,"), r.bind("Progress", function (t, r) {
                    r && (i += n(r, e))
                }), o.shimExec.call(this, "FileReader", "readAsBase64", t.uid)
            }, getResult : function () {
                return i
            }, destroy : function () {
                i = null
            }
        };
        return e.FileReader = r
    }), i($, [j, m], function (e, t) {
        function n(e, n) {
            switch(n){
                case"readAsText":
                    return t.atob(e, "utf8");
                case"readAsBinaryString":
                    return t.atob(e);
                case"readAsDataURL":
                    return e
            }
            return null
        }

        var i = {
            read : function (e, t) {
                var i, r = this.getRuntime();
                return (i = r.shimExec.call(this, "FileReaderSync", "readAsBase64", t.uid)) ? ("readAsDataURL" === e && (i = "data:" + (t.type || "") + ";base64," + i), n(i, e, t.type)) : null
            }
        };
        return e.FileReaderSync = i
    }), i(J, [j, u, y, w, T, A, O], function (e, t, n, i, r, o, a) {
        var s = {
            send : function (e, i) {
                function r() {
                    e.transport = l.mode, l.shimExec.call(c, "XMLHttpRequest", "send", e, i)
                }

                function s(e, t) {
                    l.shimExec.call(c, "XMLHttpRequest", "appendBlob", e, t.uid), i = null, r()
                }

                function u(e, t) {
                    var n = new a;
                    n.bind("TransportingComplete", function () {
                        t(this.result)
                    }), n.transport(e.getSource(), e.type, {ruid : l.uid})
                }

                var c = this, l = c.getRuntime();
                if(t.isEmptyObj(e.headers) || t.each(e.headers, function (e, t) {
                        l.shimExec.call(c, "XMLHttpRequest", "setRequestHeader", t, e.toString())
                    }), i instanceof o){
                    var d;
                    if(i.each(function (e, t) {
                            e instanceof n ? d = t : l.shimExec.call(c, "XMLHttpRequest", "append", t, e)
                        }), i.hasBlob()){
                        var f = i.getBlob();
                        f.isDetached() ? u(f, function (e) {
                                f.destroy(), s(d, e)
                            }) : s(d, f)
                    }else{
                        i = null, r()
                    }
                }else{
                    i instanceof n ? i.isDetached() ? u(i, function (e) {
                                i.destroy(), i = e.uid, r()
                            }) : (i = i.uid, r()) : r()
                }
            }, getResponse : function (e) {
                var n, o, a = this.getRuntime();
                if(o = a.shimExec.call(this, "XMLHttpRequest", "getResponseAsBlob")){
                    if(o = new i(a.uid, o), "blob" === e){
                        return o
                    }
                    try{
                        if(n = new r, ~t.inArray(e, ["", "text"])){
                            return n.readAsText(o)
                        }
                        if("json" === e && window.JSON){
                            return JSON.parse(n.readAsText(o))
                        }
                    }finally{
                        o.destroy()
                    }
                }
                return null
            }, abort : function (e) {
                var t = this.getRuntime();
                t.shimExec.call(this, "XMLHttpRequest", "abort"), this.dispatchEvent("readystatechange"), this.dispatchEvent("abort")
            }
        };
        return e.XMLHttpRequest = s
    }), i(Z, [j, y], function (e, t) {
        var n = {
            getAsBlob : function (e) {
                var n = this.getRuntime(), i = n.shimExec.call(this, "Transporter", "getAsBlob", e);
                return i ? new t(n.uid, i) : null
            }
        };
        return e.Transporter = n
    }), i(K, [j, u, O, y, T], function (e, t, n, i, r) {
        var o = {
            loadFromBlob : function (e) {
                function t(e) {
                    r.shimExec.call(i, "Image", "loadFromBlob", e.uid), i = r = null
                }

                var i = this, r = i.getRuntime();
                if(e.isDetached()){
                    var o = new n;
                    o.bind("TransportingComplete", function () {
                        t(o.result.getSource())
                    }), o.transport(e.getSource(), e.type, {ruid : r.uid})
                }else{
                    t(e.getSource())
                }
            }, loadFromImage : function (e) {
                var t = this.getRuntime();
                return t.shimExec.call(this, "Image", "loadFromImage", e.uid)
            }, getAsBlob : function (e, t) {
                var n = this.getRuntime(), r = n.shimExec.call(this, "Image", "getAsBlob", e, t);
                return r ? new i(n.uid, r) : null
            }, getAsDataURL : function () {
                var e = this.getRuntime(), t = e.Image.getAsBlob.apply(this, arguments), n;
                return t ? (n = new r, n.readAsDataURL(t)) : null
            }
        };
        return e.Image = o
    }), i(Q, [u, d, f, h, g], function (e, t, n, i, r) {
        function o(e) {
            var t = !1, n = null, i, r, o, a, s, u = 0;
            try{
                try{
                    n = new ActiveXObject("AgControl.AgControl"), n.IsVersionSupported(e) && (t = !0), n = null
                }catch(c){
                    var l = navigator.plugins["Silverlight Plug-In"];
                    if(l){
                        for( i = l.description, "1.0.30226.2" === i && (i = "2.0.30226.2"), r = i.split("."); r.length > 3; ){
                            r.pop()
                        }
                        for( ; r.length < 4; ){
                            r.push(0)
                        }
                        for( o = e.split("."); o.length > 4; ){
                            o.pop()
                        }
                        do{
                            a = parseInt(o[u], 10), s = parseInt(r[u], 10), u++
                        }while( u < o.length && a === s );
                        s >= a && (!isNaN(a) && (t = !0))
                    }
                }
            }catch(d){
                t = !1
            }
            return t
        }

        function a(a) {
            var c = this, l;
            a = e.extend({xap_url : t.xap_url}, a), r.call(this, a, s, {
                access_binary : r.capTrue,
                access_image_binary : r.capTrue,
                display_media : r.capTrue,
                do_cors : r.capTrue,
                drag_and_drop : !1,
                report_upload_progress : r.capTrue,
                resize_image : r.capTrue,
                return_response_headers : function (e) {
                    return e && "client" === c.mode
                },
                return_response_type : function (e) {
                    return "json" !== e ? !0 : !!window.JSON
                },
                return_status_code : function (t) {
                    return "client" === c.mode || !e.arrayDiff(t, [200, 404])
                },
                select_file : r.capTrue,
                select_multiple : r.capTrue,
                send_binary_string : r.capTrue,
                send_browser_cookies : function (e) {
                    return e && "browser" === c.mode
                },
                send_custom_headers : function (e) {
                    return e && "client" === c.mode
                },
                send_multipart : r.capTrue,
                slice_blob : r.capTrue,
                stream_upload : !0,
                summon_file_dialog : !1,
                upload_filesize : r.capTrue,
                use_http_method : function (t) {
                    return "client" === c.mode || !e.arrayDiff(t, ["GET", "POST"])
                }
            }, {
                return_response_headers : function (e) {
                    return e ? "client" : "browser"
                }, return_status_code : function (t) {
                    return e.arrayDiff(t, [200, 404]) ? "client" : ["client", "browser"]
                }, send_browser_cookies : function (e) {
                    return e ? "browser" : "client"
                }, send_custom_headers : function (e) {
                    return e ? "client" : "browser"
                }, use_http_method : function (t) {
                    return e.arrayDiff(t, ["GET", "POST"]) ? "client" : ["client", "browser"]
                }
            }), o("2.0.31005.0") && "Opera" !== t.browser || (this.mode = !1), e.extend(this, {
                getShim : function () {
                    return n.get(this.uid).content.Moxie
                }, shimExec : function (e, t) {
                    var n = [].slice.call(arguments, 2);
                    return c.getShim().exec(this.uid, e, t, n)
                }, init : function () {
                    var e;
                    e = this.getShimContainer(), e.innerHTML = '<object id="' + this.uid + '" data="data:application/x-silverlight," type="application/x-silverlight-2" width="100%" height="100%" style="outline:none;"><param name="source" value="' + a.xap_url + '"/><param name="background" value="Transparent"/><param name="windowless" value="true"/><param name="enablehtmlaccess" value="true"/><param name="initParams" value="uid=' + this.uid + ",target=" + t.global_event_dispatcher + '"/></object>', l = setTimeout(function () {
                        c && (!c.initialized && c.trigger("Error", new i.RuntimeError(i.RuntimeError.NOT_INIT_ERR)))
                    }, "Windows" !== t.OS ? 10000 : 5000)
                }, destroy : function (e) {
                    return function () {
                        e.call(c), clearTimeout(l), a = l = e = c = null
                    }
                }(this.destroy)
            }, u)
        }

        var s = "silverlight", u = {};
        return r.addConstructor(s, a), u
    }), i(et, [Q, u, V], function (e, t, n) {
        return e.Blob = t.extend({}, n)
    }), i(tt, [Q], function (e) {
        var t = {
            init : function (e) {
                function t(e) {
                    for( var t = "", n = 0; n < e.length; n++ ){
                        t += ("" !== t ? "|" : "") + e[n].title + " | *." + e[n].extensions.replace(/,/g, ";*.")
                    }
                    return t
                }

                this.getRuntime().shimExec.call(this, "FileInput", "init", t(e.accept), e.name, e.multiple), this.trigger("ready")
            }
        };
        return e.FileInput = t
    }), i(nt, [Q, f, L], function (e, t, n) {
        var i = {
            init : function () {
                var e = this, i = e.getRuntime(), r;
                return r = i.getShimContainer(), n.addEvent(r, "dragover", function (e) {
                    e.preventDefault(), e.stopPropagation(), e.dataTransfer.dropEffect = "copy"
                }, e.uid), n.addEvent(r, "dragenter", function (e) {
                    e.preventDefault();
                    var n = t.get(i.uid).dragEnter(e);
                    n && e.stopPropagation()
                }, e.uid), n.addEvent(r, "drop", function (e) {
                    e.preventDefault();
                    var n = t.get(i.uid).dragDrop(e);
                    n && e.stopPropagation()
                }, e.uid), i.shimExec.call(this, "FileDrop", "init")
            }
        };
        return e.FileDrop = i
    }), i(it, [Q, u, Y], function (e, t, n) {
        return e.FileReader = t.extend({}, n)
    }), i(rt, [Q, u, $], function (e, t, n) {
        return e.FileReaderSync = t.extend({}, n)
    }), i(ot, [Q, u, J], function (e, t, n) {
        return e.XMLHttpRequest = t.extend({}, n)
    }), i(at, [Q, u, Z], function (e, t, n) {
        return e.Transporter = t.extend({}, n)
    }), i(st, [Q, u, K], function (e, t, n) {
        return e.Image = t.extend({}, n, {
            getInfo : function () {
                var e = this.getRuntime(), n = ["tiff", "exif", "gps"], i = {meta : {}}, r = e.shimExec.call(this, "Image", "getInfo");
                return r.meta && t.each(n, function (e) {
                    var t = r.meta[e], n, o, a, s;
                    if(t && t.keys){
                        for( i.meta[e] = {}, o = 0, a = t.keys.length; a > o; o++ ){
                            n = t.keys[o], s = t[n], s && (/^(\d|[1-9]\d+)$/.test(s) ? s = parseInt(s, 10) : /^\d*\.\d+$/.test(s) && (s = parseFloat(s)), i.meta[e][n] = s)
                        }
                    }
                }), i.width = parseInt(r.width, 10), i.height = parseInt(r.height, 10), i.size = parseInt(r.size, 10), i.type = r.type, i.name = r.name, i
            }
        })
    }), i(ut, [u, h, g, d], function (e, t, n, i) {
        function r(t) {
            var r = this, s = n.capTest, u = n.capTrue;
            n.call(this, t, o, {
                access_binary : s(window.FileReader || window.File && File.getAsDataURL),
                access_image_binary : !1,
                display_media : s(a.Image && (i.can("create_canvas") || i.can("use_data_uri_over32kb"))),
                do_cors : !1,
                drag_and_drop : !1,
                filter_by_extension : s(function () {
                    return "Chrome" === i.browser && i.version >= 28 || "IE" === i.browser && i.version >= 10
                }()),
                resize_image : function () {
                    return a.Image && (r.can("access_binary") && i.can("create_canvas"))
                },
                report_upload_progress : !1,
                return_response_headers : !1,
                return_response_type : function (t) {
                    return "json" === t && window.JSON ? !0 : !!~e.inArray(t, ["text", "document", ""])
                },
                return_status_code : function (t) {
                    return !e.arrayDiff(t, [200, 404])
                },
                select_file : function () {
                    return i.can("use_fileinput")
                },
                select_multiple : !1,
                send_binary_string : !1,
                send_custom_headers : !1,
                send_multipart : !0,
                slice_blob : !1,
                stream_upload : function () {
                    return r.can("select_file")
                },
                summon_file_dialog : s(function () {
                    return "Firefox" === i.browser && i.version >= 4 || ("Opera" === i.browser && i.version >= 12 || !!~e.inArray(i.browser, ["Chrome", "Safari"]))
                }()),
                upload_filesize : u,
                use_http_method : function (t) {
                    return !e.arrayDiff(t, ["GET", "POST"])
                }
            }), e.extend(this, {
                init : function () {
                    this.trigger("Init")
                }, destroy : function (e) {
                    return function () {
                        e.call(r), e = r = null
                    }
                }(this.destroy)
            }), e.extend(this.getShim(), a)
        }

        var o = "html4", a = {};
        return n.addConstructor(o, r), a
    }), i(ct, [ut, u, f, L, l, d], function (e, t, n, i, r, o) {
        function a() {
            function e() {
                var r = this, l = r.getRuntime(), d, f, h, p, m, g;
                g = t.guid("uid_"), d = l.getShimContainer(), a && (h = n.get(a + "_form"), h && t.extend(h.style, {top : "100%"})), p = document.createElement("form"), p.setAttribute("id", g + "_form"), p.setAttribute("method", "post"), p.setAttribute("enctype", "multipart/form-data"), p.setAttribute("encoding", "multipart/form-data"), t.extend(p.style, {
                    overflow : "hidden",
                    position : "absolute",
                    top : 0,
                    left : 0,
                    width : "100%",
                    height : "100%"
                }), m = document.createElement("input"), m.setAttribute("id", g), m.setAttribute("type", "file"), m.setAttribute("name", c.name || "Filedata"), m.setAttribute("capture", "camcorder"), m.setAttribute("accept", u.join(",")), t.extend(m.style, {
                    fontSize : "999px",
                    opacity : 0
                }), p.appendChild(m), d.appendChild(p), t.extend(m.style, {
                    position : "absolute",
                    top : 0,
                    left : 0,
                    width : "100%",
                    height : "100%"
                }), "IE" === o.browser && (o.version < 10 && t.extend(m.style, {filter : "progid:DXImageTransform.Microsoft.Alpha(opacity=0)"})), m.onchange = function () {
                    var t;
                    this.value && (t = this.files ? this.files[0] : {name : this.value}, s = [t], this.onchange = function () {
                    }, e.call(r), r.bind("change", function i() {
                        var e = n.get(g), t = n.get(g + "_form"), o;
                        r.unbind("change", i), r.files.length && (e && (t && (o = r.files[0], e.setAttribute("id", o.uid), t.setAttribute("id", o.uid + "_form"), t.setAttribute("target", o.uid + "_iframe")))), e = t = null
                    }, 998), m = p = null, r.trigger("change"))
                }, l.can("summon_file_dialog") && (f = n.get(c.browse_button), i.removeEvent(f, "click", r.uid), i.addEvent(f, "click", function (e) {
                    m && (!m.disabled && m.click()), e.preventDefault()
                }, r.uid)), a = g, d = h = f = null
            }

            var a, s = [], u = [], c;
            t.extend(this, {
                init : function (t) {
                    var o = this, a = o.getRuntime(), s;
                    c = t, u = t.accept.mimes || r.extList2mimes(t.accept, a.can("filter_by_extension")), s = a.getShimContainer(), function () {
                        var e, r, u;
                        e = n.get(t.browse_button), a.can("summon_file_dialog") && ("static" === n.getStyle(e, "position") && (e.style.position = "relative"), r = parseInt(n.getStyle(e, "z-index"), 10) || 1, e.style.zIndex = r, s.style.zIndex = r - 1), u = a.can("summon_file_dialog") ? e : s, i.addEvent(u, "mouseover", function () {
                            o.trigger("mouseenter")
                        }, o.uid), i.addEvent(u, "mouseout", function () {
                            o.trigger("mouseleave")
                        }, o.uid), i.addEvent(u, "mousedown", function () {
                            o.trigger("mousedown")
                        }, o.uid), i.addEvent(n.get(t.container), "mouseup", function () {
                            o.trigger("mouseup")
                        }, o.uid), e = null
                    }(), e.call(this), s = null, o.trigger({type : "ready", async : !0})
                }, getFiles : function () {
                    return s
                }, disable : function (e) {
                    var t;
                    (t = n.get(a)) && (t.disabled = !!e)
                }, destroy : function () {
                    var e = this.getRuntime(), t = e.getShim(), r = e.getShimContainer();
                    i.removeAllEvents(r, this.uid), i.removeAllEvents(c && n.get(c.container), this.uid), i.removeAllEvents(c && n.get(c.browse_button), this.uid), r && (r.innerHTML = ""), t.removeInstance(this.uid), a = s = u = c = r = t = null
                }
            })
        }

        return e.FileInput = a
    }), i(lt, [ut, F], function (e, t) {
        return e.FileReader = t
    }), i(dt, [ut, u, f, R, h, L, y, A], function (e, t, n, i, r, o, a, s) {
        function u() {
            function e(e) {
                var t = this, i, r, a, s, u = !1;
                if(l){
                    if(i = l.id.replace(/_iframe$/, ""), r = n.get(i + "_form")){
                        for( a = r.getElementsByTagName("input"), s = a.length; s--; ){
                            switch(a[s].getAttribute("type")){
                                case"hidden":
                                    a[s].parentNode.removeChild(a[s]);
                                    break;
                                case"file":
                                    u = !0
                            }
                        }
                        a = [], u || r.parentNode.removeChild(r), r = null
                    }
                    setTimeout(function () {
                        o.removeEvent(l, "load", t.uid), l.parentNode && l.parentNode.removeChild(l);
                        var n = t.getRuntime().getShimContainer();
                        n.children.length || n.parentNode.removeChild(n), n = l = null, e()
                    }, 1)
                }
            }

            var u, c, l;
            t.extend(this, {
                send : function (d, f) {
                    function h() {
                        var n = m.getShimContainer() || document.body, r = document.createElement("div");
                        r.innerHTML = '<iframe id="' + g + '_iframe" name="' + g + '_iframe" src="javascript:&quot;&quot;" style="display:none"></iframe>', l = r.firstChild, n.appendChild(l), o.addEvent(l, "load", function () {
                            var n;
                            try{
                                n = l.contentWindow.document || (l.contentDocument || window.frames[l.id].document), /^4(0[0-9]|1[0-7]|2[2346])\s/.test(n.title) ? u = n.title.replace(/^(\d+).*$/, "$1") : (u = 200, c = t.trim(n.body.innerHTML), p.trigger({
                                        type : "progress",
                                        loaded : c.length,
                                        total : c.length
                                    }), w && p.trigger({
                                        type : "uploadprogress",
                                        loaded : w.size || 1025,
                                        total : w.size || 1025
                                    }))
                            }catch(r){
                                if(!i.hasSameOrigin(d.url)){
                                    return void e.call(p, function () {
                                        p.trigger("error")
                                    })
                                }
                                u = 404
                            }
                            e.call(p, function () {
                                p.trigger("load")
                            })
                        }, p.uid)
                    }

                    var p = this, m = p.getRuntime(), g, v, y, w;
                    if(u = c = null, f instanceof s && f.hasBlob()){
                        if(w = f.getBlob(), g = w.uid, y = n.get(g), v = n.get(g + "_form"), !v){
                            throw new r.DOMException(r.DOMException.NOT_FOUND_ERR)
                        }
                    }else{
                        g = t.guid("uid_"), v = document.createElement("form"), v.setAttribute("id", g + "_form"), v.setAttribute("method", d.method), v.setAttribute("enctype", "multipart/form-data"), v.setAttribute("encoding", "multipart/form-data"), v.setAttribute("target", g + "_iframe"), m.getShimContainer().appendChild(v)
                    }
                    f instanceof s && f.each(function (e, n) {
                        if(e instanceof a){
                            y && y.setAttribute("name", n)
                        }else{
                            var i = document.createElement("input");
                            t.extend(i, {
                                type : "hidden",
                                name : n,
                                value : e
                            }), y ? v.insertBefore(i, y) : v.appendChild(i)
                        }
                    }), v.setAttribute("action", d.url), h(), v.submit(), p.trigger("loadstart")
                }, getStatus : function () {
                    return u
                }, getResponse : function (e) {
                    if("json" === e && ("string" === t.typeOf(c) && window.JSON)){
                        try{
                            return JSON.parse(c.replace(/^\s*<pre[^>]*>/, "").replace(/<\/pre>\s*$/, ""))
                        }catch(n){
                            return null
                        }
                    }
                    return c
                }, abort : function () {
                    var t = this;
                    l && (l.contentWindow && (l.contentWindow.stop ? l.contentWindow.stop() : l.contentWindow.document.execCommand ? l.contentWindow.document.execCommand("Stop") : l.src = "about:blank")), e.call(this, function () {
                        t.dispatchEvent("abort")
                    })
                }
            })
        }

        return e.XMLHttpRequest = u
    }), i(ft, [ut, X], function (e, t) {
        return e.Image = t
    }), a([u, c, l, d, f, h, p, m, g, v, y, w, E, _, x, b, R, T, A, S, O, I, L])
}(this);
(function (e) {
    var t = {}, n = e.moxie.core.utils.Basic.inArray;
    return function r(e) {
        var i, s;
        for( i in e ){
            s = typeof e[i], s === "object" && !~n(i, ["Exceptions", "Env", "Mime"]) ? r(e[i]) : s === "function" && (t[i] = e[i])
        }
    }(e.moxie), t.Env = e.moxie.core.utils.Env, t.Mime = e.moxie.core.utils.Mime, t.Exceptions = e.moxie.core.Exceptions, e.mOxie = t, e.o || (e.o = t), t
})(this);
(function (p, o, q) {
    var r = p.setTimeout, fileFilters = {};

    function normalizeCaps(e) {
        var f = e.required_features, caps = {};

        function resolve(a, b, c) {
            var d = {
                chunks : "slice_blob",
                jpgresize : "send_binary_string",
                pngresize : "send_binary_string",
                progress : "report_upload_progress",
                multi_selection : "select_multiple",
                dragdrop : "drag_and_drop",
                drop_element : "drag_and_drop",
                headers : "send_custom_headers",
                urlstream_upload : "send_binary_string",
                canSendBinary : "send_binary",
                triggerDialog : "summon_file_dialog"
            };
            if(d[a]){
                caps[d[a]] = b
            }else{
                if(!c){
                    caps[a] = b
                }
            }
        }

        if(typeof f === "string"){
            s.each(f.split(/\s*,\s*/), function (a) {
                resolve(a, true)
            })
        }else{
            if(typeof f === "object"){
                s.each(f, function (a, b) {
                    resolve(b, a)
                })
            }else{
                if(f === true){
                    if(e.chunk_size > 0){
                        caps.slice_blob = true
                    }
                    if(e.resize.enabled || !e.multipart){
                        caps.send_binary_string = true
                    }
                    s.each(e, function (a, b) {
                        resolve(b, !!a, true)
                    })
                }
            }
        }
        return caps
    }

    var s = {
        VERSION : "2.1.2",
        STOPPED : 1,
        STARTED : 2,
        QUEUED : 1,
        UPLOADING : 2,
        FAILED : 4,
        DONE : 5,
        HASHING : 10,
        DELETED : 11,
        GENERIC_ERROR : -100,
        HTTP_ERROR : -200,
        IO_ERROR : -300,
        SECURITY_ERROR : -400,
        INIT_ERROR : -500,
        FILE_SIZE_ERROR : -600,
        FILE_EXTENSION_ERROR : -601,
        FILE_DUPLICATE_ERROR : -602,
        IMAGE_FORMAT_ERROR : -700,
        MEMORY_ERROR : -701,
        IMAGE_DIMENSIONS_ERROR : -702,
        mimeTypes : o.mimes,
        ua : o.ua,
        typeOf : o.typeOf,
        extend : o.extend,
        guid : o.guid,
        get : function get(a) {
            var b = [], el;
            if(o.typeOf(a) !== "array"){
                a = [a]
            }
            var i = a.length;
            while( i-- ){
                el = o.get(a[i]);
                if(el){
                    b.push(el)
                }
            }
            return b.length ? b : null
        },
        each : o.each,
        getPos : o.getPos,
        getSize : o.getSize,
        xmlEncode : function (b) {
            var c = {"<" : "lt", ">" : "gt", "&" : "amp", '"' : "quot", "'" : "#39"}, xmlEncodeRegExp = /[<>&\"\']/g;
            return b ? ("" + b).replace(xmlEncodeRegExp, function (a) {
                    return c[a] ? "&" + c[a] + ";" : a
                }) : b
        },
        toArray : o.toArray,
        inArray : o.inArray,
        addI18n : o.addI18n,
        translate : o.translate,
        isEmptyObj : o.isEmptyObj,
        hasClass : o.hasClass,
        addClass : o.addClass,
        removeClass : o.removeClass,
        getStyle : o.getStyle,
        addEvent : o.addEvent,
        removeEvent : o.removeEvent,
        removeAllEvents : o.removeAllEvents,
        cleanName : function (a) {
            var i, lookup;
            lookup = [/[\300-\306]/g, "A", /[\340-\346]/g, "a", /\307/g, "C", /\347/g, "c", /[\310-\313]/g, "E", /[\350-\353]/g, "e", /[\314-\317]/g, "I", /[\354-\357]/g, "i", /\321/g, "N", /\361/g, "n", /[\322-\330]/g, "O", /[\362-\370]/g, "o", /[\331-\334]/g, "U", /[\371-\374]/g, "u"];
            for( i = 0; i < lookup.length; i += 2 ){
                a = a.replace(lookup[i], lookup[i + 1])
            }
            a = a.replace(/\s+/g, "_");
            a = a.replace(/[^a-z0-9_\-\.]+/gi, "");
            return a
        },
        buildUrl : function (c, d) {
            var e = "";
            s.each(d, function (a, b) {
                e += (e ? "&" : "") + encodeURIComponent(b) + "=" + encodeURIComponent(a)
            });
            if(e){
                c += (c.indexOf("?") > 0 ? "&" : "?") + e
            }
            return c
        },
        formatSize : function (c) {
            if(c === q || /\D/.test(c)){
                return s.translate("N/A")
            }
            function round(a, b) {
                return Math.round(a * Math.pow(10, b)) / Math.pow(10, b)
            }

            var d = Math.pow(1024, 4);
            if(c > d){
                return round(c / d, 1) + " " + s.translate("tb")
            }
            if(c > (d /= 1024)){
                return round(c / d, 1) + " " + s.translate("gb")
            }
            if(c > (d /= 1024)){
                return round(c / d, 1) + " " + s.translate("mb")
            }
            if(c > 1024){
                return Math.round(c / 1024) + " " + s.translate("kb")
            }
            return c + " " + s.translate("b")
        },
        parseSize : o.parseSizeStr,
        predictRuntime : function (a, b) {
            var c, runtime;
            c       = new s.Uploader(a);
            runtime = o.Runtime.thatCan(c.getOption().required_features, b || a.runtimes);
            c.destroy();
            return runtime
        },
        addFileFilter : function (a, b) {
            fileFilters[a] = b
        }
    };
    s.addFileFilter("mime_types", function (a, b, c) {
        if(a.length && !a.regexp.test(b.name)){
            this.trigger("Error", {
                code : s.FILE_EXTENSION_ERROR,
                message : s.translate("File extension error."),
                file : b
            });
            c(false)
        }else{
            c(true)
        }
    });
    s.addFileFilter("max_file_size", function (a, b, c) {
        var d;
        a = s.parseSize(a);
        if(b.size !== d && (a && b.size > a)){
            this.trigger("Error", {code : s.FILE_SIZE_ERROR, message : s.translate("File size error."), file : b});
            c(false)
        }else{
            c(true)
        }
    });
    s.addFileFilter("prevent_duplicates", function (a, b, c) {
        if(a){
            var d = this.files.length;
            while( d-- ){
                if(b.name === this.files[d].name && b.size === this.files[d].size){
                    this.trigger("Error", {
                        code : s.FILE_DUPLICATE_ERROR,
                        message : s.translate("Duplicate file error."),
                        file : b
                    });
                    c(false);
                    return
                }
            }
        }
        c(true)
    });
    s.Uploader           = function (m) {
        var n = s.guid(), settings, files = [], preferred_caps = {}, fileInputs = [], fileDrops = [], startTime, total, disabled = false, xhr;

        function uploadNext() {
            var a, count = 0, i;
            if(this.state == s.STARTED){
                for( i = 0; i < files.length; i++ ){
                    if(!a && files[i].status == s.QUEUED){
                        a = files[i];
                        if(this.trigger("BeforeUpload", a)){
                            a.status = s.UPLOADING;
                            this.trigger("UploadFile", a)
                        }
                    }else{
                        count++
                    }
                }
                if(count == files.length){
                    if(this.state !== s.STOPPED){
                        this.state = s.STOPPED;
                        this.trigger("StateChanged")
                    }
                    this.trigger("UploadComplete", files)
                }
            }
        }

        function calcFile(a) {
            a.percent = a.size > 0 ? Math.ceil(a.loaded / a.size * 100) : 100;
            calc()
        }

        function calc() {
            var i, file;
            total.reset();
            for( i = 0; i < files.length; i++ ){
                file = files[i];
                if(file.size !== q){
                    total.size += file.origSize;
                    total.loaded += file.loaded * file.origSize / file.size
                }else{
                    total.size = q
                }
                if(file.status == s.DONE){
                    total.uploaded++
                }else{
                    if(file.status == s.FAILED){
                        total.failed++
                    }else{
                        total.queued++
                    }
                }
            }
            if(total.size === q){
                total.percent = files.length > 0 ? Math.ceil(total.uploaded / files.length * 100) : 0
            }else{
                total.bytesPerSec = Math.ceil(total.loaded / ((+new Date - startTime || 1) / 1000));
                total.percent     = total.size > 0 ? Math.ceil(total.loaded / total.size * 100) : 0
            }
        }

        function getRUID() {
            var a = fileInputs[0] || fileDrops[0];
            if(a){
                return a.getRuntime().uid
            }
            return false
        }

        function runtimeCan(a, b) {
            if(a.ruid){
                var c = o.Runtime.getInfo(a.ruid);
                if(c){
                    return c.can(b)
                }
            }
            return false
        }

        function bindEventListeners() {
            this.bind("FilesAdded FilesRemoved", function (a) {
                a.trigger("QueueChanged");
                a.refresh()
            });
            this.bind("CancelUpload", onCancelUpload);
            this.bind("BeforeUpload", onBeforeUpload);
            this.bind("UploadFile", onUploadFile);
            this.bind("UploadProgress", onUploadProgress);
            this.bind("StateChanged", onStateChanged);
            this.bind("QueueChanged", calc);
            this.bind("Error", onError);
            this.bind("FileUploaded", onFileUploaded);
            this.bind("Destroy", onDestroy)
        }

        function initControls(f, g) {
            var h = this, inited = 0, queue = [];
            var i = {
                runtime_order : f.runtimes,
                required_caps : f.required_features,
                preferred_caps : preferred_caps,
                swf_url : f.flash_swf_url,
                xap_url : f.silverlight_xap_url
            };
            s.each(f.runtimes.split(/\s*,\s*/), function (a) {
                if(f[a]){
                    i[a] = f[a]
                }
            });
            if(f.browse_button){
                s.each(f.browse_button, function (d) {
                    queue.push(function (b) {
                        var c      = new o.FileInput(s.extend({}, i, {
                            accept : f.filters.mime_types,
                            name : f.file_data_name,
                            multiple : f.multi_selection,
                            container : f.container,
                            browse_button : d
                        }));
                        c.onready  = function () {
                            var a = o.Runtime.getInfo(this.ruid);
                            o.extend(h.features, {
                                chunks : a.can("slice_blob"),
                                multipart : a.can("send_multipart"),
                                multi_selection : a.can("select_multiple")
                            });
                            inited++;
                            fileInputs.push(this);
                            b()
                        };
                        c.onchange = function () {
                            h.addFile(this.files)
                        };
                        c.bind("mouseenter mouseleave mousedown mouseup", function (e) {
                            if(!disabled){
                                if(f.browse_button_hover){
                                    if("mouseenter" === e.type){
                                        o.addClass(d, f.browse_button_hover)
                                    }else{
                                        if("mouseleave" === e.type){
                                            o.removeClass(d, f.browse_button_hover)
                                        }
                                    }
                                }
                                if(f.browse_button_active){
                                    if("mousedown" === e.type){
                                        o.addClass(d, f.browse_button_active)
                                    }else{
                                        if("mouseup" === e.type){
                                            o.removeClass(d, f.browse_button_active)
                                        }
                                    }
                                }
                            }
                        });
                        c.bind("mousedown", function () {
                            h.trigger("Browse")
                        });
                        c.bind("error runtimeerror", function () {
                            c = null;
                            b()
                        });
                        c.init()
                    })
                })
            }
            if(f.drop_element){
                s.each(f.drop_element, function (d) {
                    queue.push(function (b) {
                        var c     = new o.FileDrop(s.extend({}, i, {drop_zone : d}));
                        c.onready = function () {
                            var a               = o.Runtime.getInfo(this.ruid);
                            h.features.dragdrop = a.can("drag_and_drop");
                            inited++;
                            fileDrops.push(this);
                            b()
                        };
                        c.ondrop  = function () {
                            h.addFile(this.files)
                        };
                        c.bind("error runtimeerror", function () {
                            c = null;
                            b()
                        });
                        c.init()
                    })
                })
            }
            o.inSeries(queue, function () {
                if(typeof g === "function"){
                    g(inited)
                }
            })
        }

        function resizeImage(a, b, c) {
            var d = new o.Image;
            try{
                d.onload   = function () {
                    if(b.width > this.width && (b.height > this.height && (b.quality === q && (b.preserve_headers && !b.crop)))){
                        this.destroy();
                        return c(a)
                    }
                    d.downsize(b.width, b.height, b.crop, b.preserve_headers)
                };
                d.onresize = function () {
                    c(this.getAsBlob(a.type, b.quality));
                    this.destroy()
                };
                d.onerror  = function () {
                    c(a)
                };
                d.load(a)
            }catch(ex){
                c(a)
            }
        }

        function setOption(i, j, k) {
            var l = this, reinitRequired = false;

            function _setOption(e, f, g) {
                var h = settings[e];
                switch(e){
                    case"max_file_size":
                        if(e === "max_file_size"){
                            settings.max_file_size = settings.filters.max_file_size = f
                        }
                        break;
                    case"chunk_size":
                        if(f = s.parseSize(f)){
                            settings[e]             = f;
                            settings.send_file_name = true
                        }
                        break;
                    case"chunk_size_part1":
                        if(f = s.parseSize(f)){
                            settings[e]             = f;
                            settings.send_file_name = true
                        }
                        break;
                    case"chunk_size_step":
                        if(f = s.parseSize(f)){
                            settings[e]             = f;
                            settings.send_file_name = true
                        }
                        break;
                    case"multipart":
                        settings[e] = f;
                        if(!f){
                            settings.send_file_name = true
                        }
                        break;
                    case"unique_names":
                        settings[e] = f;
                        if(f){
                            settings.send_file_name = true
                        }
                        break;
                    case"filters":
                        if(s.typeOf(f) === "array"){
                            f = {mime_types : f}
                        }
                        if(g){
                            s.extend(settings.filters, f)
                        }else{
                            settings.filters = f
                        }
                        if(f.mime_types){
                            settings.filters.mime_types.regexp = function (c) {
                                var d = [];
                                s.each(c, function (b) {
                                    s.each(b.extensions.split(/,/), function (a) {
                                        if(/^\s*\*\s*$/.test(a)){
                                            d.push("\\.*")
                                        }else{
                                            d.push("\\." + a.replace(new RegExp("[" + "/^$.*+?|()[]{}\\".replace(/./g, "\\$&") + "]", "g"), "\\$&"))
                                        }
                                    })
                                });
                                return new RegExp("(" + d.join("|") + ")$", "i")
                            }(settings.filters.mime_types)
                        }
                        break;
                    case"resize":
                        if(g){
                            s.extend(settings.resize, f, {enabled : true})
                        }else{
                            settings.resize = f
                        }
                        break;
                    case"prevent_duplicates":
                        settings.prevent_duplicates = settings.filters.prevent_duplicates = !!f;
                        break;
                    case"browse_button":
                    case"drop_element":
                        f = s.get(f);
                    case"container":
                    case"runtimes":
                    case"multi_selection":
                    case"flash_swf_url":
                    case"silverlight_xap_url":
                        settings[e] = f;
                        if(!g){
                            reinitRequired = true
                        }
                        break;
                    default:
                        settings[e] = f
                }
                if(!g){
                    l.trigger("OptionChanged", e, f, h)
                }
            }

            if(typeof i === "object"){
                s.each(i, function (a, b) {
                    _setOption(b, a, k)
                })
            }else{
                _setOption(i, j, k)
            }
            if(k){
                settings.required_features = normalizeCaps(s.extend({}, settings));
                preferred_caps             = normalizeCaps(s.extend({}, settings, {required_features : true}))
            }else{
                if(reinitRequired){
                    l.trigger("Destroy");
                    initControls.call(l, settings, function (a) {
                        if(a){
                            l.runtime = o.Runtime.getInfo(getRUID()).type;
                            l.trigger("Init", {runtime : l.runtime});
                            l.trigger("PostInit")
                        }else{
                            l.trigger("Error", {code : s.INIT_ERROR, message : s.translate("Init error.")})
                        }
                    })
                }
            }
        }

        function onBeforeUpload(a, b) {
            if(a.settings.unique_names){
                var c = b.name.match(/\.([^.]+)$/), ext = "part";
                if(c){
                    ext = c[1]
                }
                b.target_name = b.id + "." + ext
            }
        }

        function onUploadFile(d, f) {
            var g                  = d.settings.url, chunkSizeMax = d.settings.chunk_size, chunkSizePart1 = d.settings.chunk_size_part1, chunkSizeStep = d.settings.chunk_size_step, extBusiness = d.settings.extBusiness, retries = d.settings.max_retries, features = d.features, offset = 0, blob;
            f.currChunkSize        = 0;
            f.currChunkStartUpTime = new Date - 0;
            function handleError(errorCode) {
                if(retries-- > 0){
                    r(uploadNextChunk, 1000)
                }else{
                    f.loaded = offset;
                    d.trigger("Error", {
                        code : errorCode || s.HTTP_ERROR,
                        message : s.translate("HTTP Error."),
                        file : f,
                        response : xhr.responseText,
                        status : xhr.status,
                        responseHeaders : xhr.getAllResponseHeaders()
                    })
                }
            }

            function uploadNextChunk() {
                if(f.loaded != offset){
                    offset = f.loaded
                }
                var c, formData, args = s.extend({}, d.settings.multipart_params), curChunkSize;
                if(f.status !== s.UPLOADING || d.state === s.STOPPED){
                    return
                }
                if(d.settings.send_file_name){
                    args.name = f.target_name || f.name
                }
                var useChunkSize = f.currChunkSize;
                if(f.loaded == 0 || !!f["is_from_resume"]){
                    useChunkSize = chunkSizePart1
                }else{
                    f.currChunkSize += chunkSizeStep;
                    if(f.currChunkSize >= chunkSizeMax){
                        f.currChunkSize = chunkSizeMax
                    }
                    useChunkSize = f.currChunkSize
                }
                delete f["is_from_resume"];
                curChunkSize      = Math.min(useChunkSize, blob.size - offset);
                c                 = blob.slice(offset, offset + curChunkSize);
                var waitSignature = false;
                if(useChunkSize && features.chunks){
                    if(d.settings.send_chunk_number){
                        args.offset = offset
                    }else{
                        args.offset = offset;
                        args.total  = blob.size
                    }
                    args.dataSize  = curChunkSize ? curChunkSize : 0;
                    args.Action    = "MultipartUploadVodFile";
                    args.Region    = "gz";
                    args.Timestamp = String(parseInt((new Date - 0) / 1000));
                    args.Nonce     = Math.ceil(Math.random() * 10000);
                    args.utype     = 1;
                    if(extBusiness["transcodeNotifyUrl"]){
                        args.notifyUrl = extBusiness["transcodeNotifyUrl"]
                    }
                    args          = ksort(args);
                    var sigString = "POST" + extBusiness.web_upload_url.replace("http://", "") + "?";
                    s.each(args, function (e, i) {
                        sigString += i + "=" + e + "&"
                    });
                    sigString     = sigString.substr(0, sigString.length - 1);
                    waitSignature = true
                }
                if(!waitSignature){
                    startXhr()
                }else{
                    d.settings.signature(sigString, function (Signature) {
                        args.Signature = Signature;
                        startXhr()
                    })
                }
                function cus_startXhr() {
                    var oReader    = new FileReader();
                    oReader.onload = function (e) {
                        result = e.target.result;
                        startXhr(result)
                    };
                    oReader.readAsBinaryString(c.getSource())
                }

                function startXhr(fdata) {
                    xhr = new o.XMLHttpRequest;
                    if(xhr.upload){
                        xhr.upload.onprogress = function (e) {
                            f.loaded = Math.min(f.size, offset + e.loaded);
                            d.trigger("UploadProgress", f)
                        }
                    }
                    xhr.onload    = function () {
                        if(xhr.status >= 400){
                            handleError();
                            return
                        }
                        if(xhr.responseText){
                            try{
                                var retJson = JSON.parse(xhr.responseText);
                                if(retJson.hasOwnProperty("code")){
                                    if(retJson.code != 0){
                                        offset = retJson.hasOwnProperty("offset") ? retJson.offset - 0 : 0;
                                        handleError(retJson.code);
                                        return
                                    }
                                }
                            }catch(xe){
                            }
                        }
                        retries = d.settings.max_retries;
                        if(curChunkSize < blob.size){
                            c.destroy();
                            offset += curChunkSize;
                            f.loaded = Math.min(offset, blob.size);
                            d.trigger("ChunkUploaded", f, {
                                offset : f.loaded,
                                total : blob.size,
                                response : xhr.responseText,
                                status : xhr.status,
                                responseHeaders : xhr.getAllResponseHeaders()
                            });
                            if(o.Env.browser === "Android Browser"){
                                d.trigger("UploadProgress", f)
                            }
                        }else{
                            f.loaded = f.size
                        }
                        c = formData = null;
                        if(!offset || offset >= blob.size){
                            if(f.size != f.origSize){
                                blob.destroy();
                                blob = null
                            }
                            d.trigger("UploadProgress", f);
                            f.status = s.DONE;
                            d.trigger("FileUploaded", f, {
                                response : xhr.responseText,
                                status : xhr.status,
                                responseHeaders : xhr.getAllResponseHeaders()
                            })
                        }else{
                            f.currChunkStartUpTime = new Date - 0;
                            r(uploadNextChunk, 1)
                        }
                    };
                    xhr.onerror   = function () {
                        handleError()
                    };
                    xhr.onloadend = function () {
                        this.destroy();
                        xhr = null
                    };
                    g             = s.buildUrl(d.settings.url, args);
                    xhr.open("post", g, true);
                    xhr.setRequestHeader("Content-Type", "application/octet-stream");
                    s.each(d.settings.headers, function (a, b) {
                        xhr.setRequestHeader(b, a)
                    });
                    if(typeof(fdata) == "undefined"){
                        xhr.send(c, {
                            runtime_order : d.settings.runtimes,
                            required_caps : d.settings.required_features,
                            preferred_caps : preferred_caps,
                            swf_url : d.settings.flash_swf_url,
                            xap_url : d.settings.silverlight_xap_url
                        })
                    }else{
                        xhr.sendAsBinary(fdata, {
                            runtime_order : d.settings.runtimes,
                            required_caps : d.settings.required_features,
                            preferred_caps : preferred_caps,
                            swf_url : d.settings.flash_swf_url,
                            xap_url : d.settings.silverlight_xap_url
                        })
                    }
                }
            }

            blob = f.getSource();
            if(d.settings.resize.enabled && (runtimeCan(blob, "send_binary_string") && !!~o.inArray(blob.type, ["image/jpeg", "image/png"]))){
                resizeImage.call(this, blob, d.settings.resize, function (a) {
                    blob   = a;
                    f.size = a.size;
                    uploadNextChunk()
                })
            }else{
                uploadNextChunk()
            }
        }

        function onUploadProgress(a, b) {
            calcFile(b)
        }

        function onStateChanged(a) {
            if(a.state == s.STARTED){
                startTime = +new Date
            }else{
                if(a.state == s.STOPPED){
                    for( var i = a.files.length - 1; i >= 0; i-- ){
                        if(a.files[i].status == s.UPLOADING){
                            a.files[i].status = s.QUEUED;
                            calc()
                        }
                    }
                }
            }
        }

        function onCancelUpload() {
            if(xhr){
                xhr.abort()
            }
        }

        function onFileUploaded(a) {
            calc();
            r(function () {
                uploadNext.call(a)
            }, 1)
        }

        function onError(a, b) {
            if(b.code === s.INIT_ERROR){
                a.destroy()
            }else{
                if(b.file){
                    b.file.status = s.FAILED;
                    calcFile(b.file);
                    if(a.state == s.STARTED){
                        a.trigger("CancelUpload");
                        r(function () {
                            uploadNext.call(a)
                        }, 1)
                    }
                }
            }
        }

        function onDestroy(b) {
            b.stop();
            s.each(files, function (a) {
                a.destroy()
            });
            files = [];
            if(fileInputs.length){
                s.each(fileInputs, function (a) {
                    a.destroy()
                });
                fileInputs = []
            }
            if(fileDrops.length){
                s.each(fileDrops, function (a) {
                    a.destroy()
                });
                fileDrops = []
            }
            preferred_caps = {};
            disabled       = false;
            startTime      = xhr = null;
            total.reset()
        }

        settings = {
            runtimes : o.Runtime.order,
            max_retries : 0,
            chunk_size : 0,
            multipart : true,
            multi_selection : true,
            file_data_name : "file",
            flash_swf_url : "js/Moxie.swf",
            silverlight_xap_url : "js/Moxie.xap",
            filters : {mime_types : [], prevent_duplicates : false, max_file_size : 0},
            resize : {enabled : false, preserve_headers : true, crop : false},
            send_file_name : true,
            send_chunk_number : true
        };
        setOption.call(this, m, null, true);
        total = new s.QueueProgress;
        s.extend(this, {
            id : n,
            uid : n,
            state : s.STOPPED,
            features : {},
            runtime : null,
            files : files,
            settings : settings,
            total : total,
            init : function () {
                var d = this;
                if(typeof settings.preinit == "function"){
                    settings.preinit(d)
                }else{
                    s.each(settings.preinit, function (a, b) {
                        d.bind(b, a)
                    })
                }
                bindEventListeners.call(this);
                if(!settings.browse_button || !settings.url){
                    this.trigger("Error", {code : s.INIT_ERROR, message : s.translate("Init error.")});
                    return
                }
                initControls.call(this, settings, function (c) {
                    if(typeof settings.init == "function"){
                        settings.init(d)
                    }else{
                        s.each(settings.init, function (a, b) {
                            d.bind(b, a)
                        })
                    }
                    if(c){
                        d.runtime = o.Runtime.getInfo(getRUID()).type;
                        d.trigger("Init", {runtime : d.runtime});
                        d.trigger("PostInit")
                    }else{
                        d.trigger("Error", {code : s.INIT_ERROR, message : s.translate("Init error.")})
                    }
                })
            },
            setOption : function (a, b) {
                setOption.call(this, a, b, !this.runtime)
            },
            getOption : function (a) {
                if(!a){
                    return settings
                }
                return settings[a]
            },
            refresh : function () {
                if(fileInputs.length){
                    s.each(fileInputs, function (a) {
                        a.trigger("Refresh")
                    })
                }
                this.trigger("Refresh")
            },
            start : function () {
                if(this.state != s.STARTED){
                    this.state = s.STARTED;
                    this.trigger("StateChanged");
                    uploadNext.call(this)
                }
            },
            stop : function () {
                if(this.state != s.STOPPED){
                    this.state = s.STOPPED;
                    this.trigger("StateChanged");
                    this.trigger("CancelUpload")
                }
            },
            disableBrowse : function () {
                disabled = arguments[0] !== q ? arguments[0] : true;
                if(fileInputs.length){
                    s.each(fileInputs, function (a) {
                        a.disable(disabled)
                    })
                }
                this.trigger("DisableBrowse", disabled)
            },
            getFile : function (a) {
                var i;
                for( i = files.length - 1; i >= 0; i-- ){
                    if(files[i].id === a){
                        return files[i]
                    }
                }
            },
            addFile : function (h, i) {
                var j = this, queue = [], filesAdded = [], ruid;

                function filterFile(e, f) {
                    var g = [];
                    o.each(j.settings.filters, function (c, d) {
                        if(fileFilters[d]){
                            g.push(function (b) {
                                fileFilters[d].call(j, c, e, function (a) {
                                    b(!a)
                                })
                            })
                        }
                    });
                    o.inSeries(g, f)
                }

                function resolveFile(c) {
                    var d = o.typeOf(c);
                    if(c instanceof o.File){
                        if(!c.ruid && !c.isDetached()){
                            if(!ruid){
                                return false
                            }
                            c.ruid = ruid;
                            c.connectRuntime(ruid)
                        }
                        resolveFile(new s.File(c))
                    }else{
                        if(c instanceof o.Blob){
                            resolveFile(c.getSource());
                            c.destroy()
                        }else{
                            if(c instanceof s.File){
                                if(i){
                                    c.name = i
                                }
                                queue.push(function (b) {
                                    filterFile(c, function (a) {
                                        if(!a){
                                            files.push(c);
                                            filesAdded.push(c);
                                            j.trigger("FileFiltered", c)
                                        }
                                        r(b, 1)
                                    })
                                })
                            }else{
                                if(o.inArray(d, ["file", "blob"]) !== -1){
                                    resolveFile(new o.File(null, c))
                                }else{
                                    if(d === "node" && o.typeOf(c.files) === "filelist"){
                                        o.each(c.files, resolveFile)
                                    }else{
                                        if(d === "array"){
                                            i = null;
                                            o.each(c, resolveFile)
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                ruid = getRUID();
                resolveFile(h);
                if(queue.length){
                    o.inSeries(queue, function () {
                        if(filesAdded.length){
                            j.trigger("FilesAdded", filesAdded)
                        }
                    })
                }
            },
            removeFile : function (a) {
                var b = typeof a === "string" ? a : a.id;
                for( var i = files.length - 1; i >= 0; i-- ){
                    if(files[i].id === b){
                        return this.splice(i, 1)[0]
                    }
                }
            },
            splice : function (b, c) {
                var d = files.splice(b === q ? 0 : b, c === q ? files.length : c);
                var e = false;
                if(this.state == s.STARTED){
                    s.each(d, function (a) {
                        if(a.status === s.UPLOADING){
                            e = true;
                            return false
                        }
                    });
                    if(e){
                        this.stop()
                    }
                }
                this.trigger("FilesRemoved", d);
                s.each(d, function (a) {
                    a.destroy()
                });
                if(e){
                    this.start()
                }
                return d
            },
            bind : function (b, c, d) {
                var e = this;
                s.Uploader.prototype.bind.call(this, b, function () {
                    var a = [].slice.call(arguments);
                    a.splice(0, 1, e);
                    return c.apply(this, a)
                }, 0, d)
            },
            destroy : function () {
                this.trigger("Destroy");
                settings = total = null;
                this.unbindAll()
            }
        })
    };
    s.Uploader.prototype = o.EventTarget.instance;
    s.File               = function () {
        var c = {};

        function PluploadFile(b) {
            s.extend(this, {
                id : s.guid(),
                name : b.name || b.fileName,
                type : b.type || "",
                size : b.size || b.fileSize,
                origSize : b.size || b.fileSize,
                loaded : 0,
                percent : 0,
                status : s.QUEUED,
                lastModifiedDate : b.lastModifiedDate || (new Date).toLocaleString(),
                getNative : function () {
                    var a = this.getSource().getSource();
                    return o.inArray(o.typeOf(a), ["blob", "file"]) !== -1 ? a : null
                },
                getSource : function () {
                    if(!c[this.id]){
                        return null
                    }
                    return c[this.id]
                },
                destroy : function () {
                    var a = this.getSource();
                    if(a){
                        a.destroy();
                        delete c[this.id]
                    }
                }
            });
            c[this.id] = b
        }

        return PluploadFile
    }();
    s.QueueProgress      = function () {
        var a         = this;
        a.size        = 0;
        a.loaded      = 0;
        a.uploaded    = 0;
        a.failed      = 0;
        a.queued      = 0;
        a.percent     = 0;
        a.bytesPerSec = 0;
        a.reset       = function () {
            a.size = a.loaded = a.uploaded = a.failed = a.queued = a.percent = a.bytesPerSec = 0
        }
    };
    p.plupload           = s
})(window, mOxie);
qcVideo("Code", function () {
    return {
        BATCH_FILE_SET : 1,
        UPLOAD_WAIT : 2,
        UPLOAD_SHA : 3,
        WILL_UPLOAD : 4,
        UPLOAD_PROGRESS : 5,
        UPLOAD_DONE : 6,
        UPLOAD_FAIL : 7,
        UPLOADER_STATUS_CHANGE : 8,
        ILLEGAL_TYPE : -1,
        ILLEGAL_NAME : -2,
        OVER_MAX_SIZE : -3,
        DUPLICATE_PICK : -4,
    }
});
qcVideo("constants", function (Code) {
    return {
        ALLOW_UPLOAD_FILE_TYPE : ["WMV", "WM", "ASF", "ASX", "RM", "RMVB", "RA", "RAM", "MPG", "MPEG", "MPE", "VOB", "DAT", "MOV", "3GP", "MP4", "MP4V", "M4V", "MKV", "AVI", "FLV", "F4V"],
        FILE_STATUS : {HASHING : "HASHING"},
        HIGH_FREQUENCY_STATUS : [Code.UPLOAD_SHA, Code.UPLOAD_PROGRESS]
    }
});
qcVideo("ErrorCode", function () {
    return {UN_SUPPORT_BROWSE : -1, REQUIRE_SIGNATURE : -2}
});
qcVideo("FILE_STATUS", function () {
    return {WAIT_UPLOAD : 1, UPLOADING : 2, DONE : 5, FAIL : 4, SHA_ING : 10}
});
qcVideo("Log", function () {
    var empty      = function () {
    }, realConsole = window.console, console = realConsole || {}, wrap = function (fn) {
        return function () {
            try{
                fn.apply(realConsole, arguments)
            }catch(xe){
            }
        }
    };
    return {
        debug : wrap(console.debug || empty),
        log : wrap(console.log || empty),
        error : wrap(console.error || empty),
        info : wrap(console.info || empty)
    }
});
qcVideo("Reporter", function (util) {
    var reporter = function () {
        var reportInterfaceUrl = "http://dcreport.video.qcloud.com/interface.php";
        var tool               = {
            cookie : {
                get : function (name) {
                    var r = new RegExp("(?:^|;+|\\s+)" + name + "=([^;]*)"), m = document.cookie.match(r);
                    return !m ? "" : m[1]
                }
            }
        };
        var _objectToParams    = function (obj) {
            var paramList = [];
            for( var key in obj ){
                var value = obj[key];
                paramList.push(key + "=" + value)
            }
            return paramList.join("&")
        };
        var http_img_sender    = function () {
            var img = new Image;
            return function (src) {
                img.onload = img.onerror = img.onabort = function () {
                    img.onload = img.onerror = img.onabort = null;
                    img = null
                };
                img.src = src
            }
        };
        var report             = function (url, params) {
            try{
                var _url    = url.split("?")[0];
                _url        = _url.replace(/\/$/, "");
                params["_"] = +new Date;
                _url        = _url + "?" + _objectToParams(params, true);
                var sender  = http_img_sender();
                sender(_url)
            }catch(xe){
            }
        };
        return function (params) {
            params.interfacename = "Vod_Upload_DcReport";
            params.uin           = tool.cookie.get("ownerUin") || "111";
            params.appid         = tool.cookie.get("appid") || "111";
            report(reportInterfaceUrl, params)
        }
    }();
    var Item     = function (obj) {
        var data = this.data = {};
        data.browser   = util.browser();
        data.file_sha  = 0;
        data.file_size = obj.size;
        data.file_name = obj.name;
        this._reset()
    };
    $.extend(Item.prototype, {
        _reset : function () {
            var data         = this.data;
            data.action      = 0;
            data.chunk_flag  = 0;
            data.upload_flag = 0;
            data.data_size   = 0;
            data.offset      = 0;
            data.cost_time   = 0;
            data.error_code  = 0
        }, set : function (key, value) {
            this.data[key] = value;
            return this
        }, bSet : function (obj) {
            for( var key in obj ){
                this.set(key, obj[key])
            }
            return this
        }, submit : function () {
            reporter(this.data);
            return this
        }
    });
    return function (obj) {
        return new Item(obj)
    }
});
qcVideo("SHA", function (Log, $) {
    var SETTING    = {
        WORK_JS : "http://www.daren007.com/wq/public/mobile/js/calculator_worker_sha1.js",
        MAX_DOING_SHA1 : 3,
        MAX_SHA_SIZE : Math.pow(2, 30) * 4,
        REPORT_INTERVAL : 60000
    };
    var cache      = {waitList : [], ingMap : {__length : 0}, shaMap : {}};
    var getReport  = function (file) {
        return file["_report"]
    };
    var SpeedLimit = function () {
        var cache = {};
        return {
            set : function (file, percent) {
                var id = file.id, now = +new Date;
                if(!cache.hasOwnProperty(id)){
                    cache[id] = {_time : now}
                }
                cache[id].percent = percent;
                if(now - cache[id]._time > 1000){
                    cache[id]._time = now;
                    dispatchSHA(file, percent)
                }
            }, del : function (id) {
                delete cache[id]
            }
        }
    }();

    function hash_file(file, fileId, worker, bs) {
        var buffer_size, block, reader, handle_hash_block, handle_load_block;
        var clean         = function () {
            Log.debug("SHA \uff0cclear");
            if(reader){
                reader.onload = null
            }
            file = null;
            try{
                worker.terminate()
            }catch(xe){
            }
            worker = null
        };
        handle_load_block = function (event) {
            if(!cache.ingMap[fileId]){
                Log.debug("SHA \uff0c\u7ee7\u7eed\u8ba1\u7b97\u65f6\uff0c\u53d1\u73b0\u5df2\u7ecf\u88ab\u5220\u9664", fileId);
                clean();
                startFileSha()
            }else{
                worker.postMessage({"message" : event.target.result, "block" : block})
            }
        };
        handle_hash_block = function (event) {
            if(block.end !== file.size){
                block.start += buffer_size;
                block.end += buffer_size;
                if(block.end > file.size){
                    block.end = file.size
                }
                if(reader){
                    reader.onload = null
                }
                reader        = new FileReader;
                reader.onload = handle_load_block;
                reader.readAsArrayBuffer(file.slice(block.start, block.end))
            }else{
                clean()
            }
        };
        buffer_size       = bs;
        block             = {
            "file_size" : file.size,
            "start" : 0,
            "end" : buffer_size > file.size ? file.size : buffer_size
        };
        worker.addEventListener("message", handle_hash_block);
        reader        = new FileReader;
        reader.onload = handle_load_block;
        reader.readAsArrayBuffer(file.slice(block.start, block.end))
    }

    function handle_worker_event(file) {
        return function (event) {
            if(file.status != plupload.HASHING || !cache.ingMap[file.id]){
                return
            }
            if(event.data.result){
                file._sha_end_time    = +new Date;
                var cost              = file._sha_end_time - file._sha_start_time;
                cache.shaMap[file.id] = event.data.result;
                if(file._report_timer){
                    clearInterval(file._report_timer);
                    delete file.report_timer
                }
                file._hashed_size = file.size;
                getReport(file).bSet({
                    "file_sha" : cache.shaMap[file.id],
                    "cost_time" : Math.ceil(cost / 1000),
                    "data_size" : file.size
                }).submit();
                SpeedLimit.del(file.id);
                dispatchSHA(file, 100, {sha : event.data.result, cost : cost});
                if(!!cache.ingMap[file.id]){
                    delete cache.ingMap[file.id];
                    cache.ingMap.__length -= 1
                }
                startFileSha()
            }else{
                var block         = event.data.block;
                file._hashed_size = block.end;
                SpeedLimit.set(file, (block.end * 100 / block.file_size).toFixed(2))
            }
        }
    }

    function isAbleStart(file) {
        var size = 0, k;
        for( k in cache.ingMap ){
            if(k !== "__length"){
                size += cache.ingMap[k].size - (cache.ingMap[k]._hashed_size | 0)
            }
        }
        size += file ? file.size : 0;
        if(cache.ingMap.__length === 0 || SETTING.MAX_DOING_SHA1 > cache.ingMap.__length && SETTING.MAX_SHA_SIZE > size){
            return true
        }
    }

    function startFileSha() {
        var length = cache.waitList.length;
        if(length > 0 && isAbleStart(cache.waitList[0])){
            var file              = cache.waitList.shift(1);
            file._sha_start_time  = +new Date;
            file._hashed_size     = 0;
            file._report_timer    = setInterval(function () {
                if(!!file && !!file._report_timer){
                    getReport(file).bSet({
                        "offset" : file._hashed_size,
                        "cost_time" : Math.ceil((new Date - file._sha_start_time) / 1000),
                        "data_size" : file._hashed_size
                    }).submit()
                }
            }, SETTING.REPORT_INTERVAL);
            cache.ingMap[file.id] = file;
            cache.ingMap.__length += 1;
            var worker            = new Worker(SETTING.WORK_JS);
            worker.addEventListener("message", handle_worker_event(file));
            hash_file(file.getNative(), file.id, worker, getBuffer(file))
        }
    }

    var getBuffer   = function (file) {
        var bs, M = Math.pow(2, 20);
        if(file.size < Math.pow(2, 20) * 100){
            bs = M
        }else{
            if(file.size < Math.pow(2, 20) * 512){
                bs = 2 * M
            }else{
                if(file.size < Math.pow(2, 30)){
                    bs = 3 * M
                }else{
                }
            }
        }
        bs = 0.5 * M;
        return bs
    };
    var handler     = $.noop;
    var dispatchSHA = function (file, precent, result) {
        handler(file, precent ? precent : 0, result)
    };
    return {
        setWorkPath : function (path) {
            SETTING.WORK_JS = path
        }, addListener : function (fn) {
            handler = fn
        }, getSha1 : function (id) {
            return cache.shaMap[id] || 0
        }, add : function (file) {
            dispatchSHA(file, 0);
            if(isAbleStart(file)){
                cache.waitList = [file].concat(cache.waitList);
                startFileSha()
            }else{
                cache.waitList.push(file)
            }
        }, del : function (fid) {
            Log.debug("SHA,\u5c06\u5220\u9664\u6587\u4ef6", fid);
            var index = -1;
            $.each(cache.waitList, function (i, file) {
                if(file.id == fid){
                    if(file._report_timer){
                        clearInterval(file._report_timer);
                        delete file.report_timer
                    }
                    index = i;
                    return false
                }
            });
            if(index !== -1){
                cache.waitList.splice(index, 1)
            }
            if(cache.ingMap[fid]){
                delete cache.ingMap[fid];
                cache.ingMap.__length -= 1;
                Log.debug("SHA,\u5df2\u5220\u9664\u6587\u4ef6", fid)
            }
        }
    }
});
qcVideo("util", function (FILE_STATUS) {
    return {
        browser : function () {
            var br  = navigator.userAgent.toLowerCase();
            var ver = (br.match(/.+(?:rv|it|ra|ie)[\/: ]([\\d.]+)/) || [0, "0"])[1];
            var ret = "";
            if(/msie/i.test(br) && !/opera/.test(br)){
                ret = "IE"
            }else{
                if(/firefox/i.test(br)){
                    ret = "Firefox"
                }else{
                    if(/chrome/i.test(br) && (/webkit/i.test(br) && /mozilla/i.test(br))){
                        ret = "Chrome"
                    }else{
                        if(/opera/i.test(br)){
                            ret = "Opera"
                        }else{
                            if(/iPad/i){
                                ret = "ipad"
                            }else{
                                if(/webkit/i.test(br) && !(/chrome/i.test(br) && (/webkit/i.test(br) && /mozilla/i.test(br)))){
                                    ret = "Safari"
                                }else{
                                    ret = "unKnow"
                                }
                            }
                        }
                    }
                }
            }
            return (ret + " " + ver).replace(/\\s|\\./g, "_")
        }, compute_speed : function (num) {
            var step                                                 = [{"level" : Math.pow(1024, 0), "unit" : "B/s"}, {
                "level" : Math.pow(1024, 1),
                "unit" : "KB/s"
            }, {"level" : Math.pow(1024, 2), "unit" : "MB/s"}], unit = "B", level = 1;
            $.each(step, function (i, e) {
                if(num >= e.level){
                    unit  = e.unit;
                    level = e.level
                }
            });
            return (num / level).toFixed(2) + unit
        }, getHStorage : function (stroage) {
            var KB = 1000;
            var MB = KB * 1000;
            var GB = MB * 1000;
            var TB = GB * 1000;
            if(stroage < MB){
                return (stroage / KB).toFixed(2) + "KB"
            }else{
                if(stroage < GB){
                    return (stroage / MB).toFixed(2) + "MB"
                }else{
                    if(stroage < TB){
                        return (stroage / GB).toFixed(2) + "GB"
                    }else{
                        return (stroage / TB).toFixed(2) + "TB"
                    }
                }
            }
        }, getFileStatusName : function (s) {
            switch(s){
                case FILE_STATUS.WAIT_UPLOAD:
                    return "\u7b49\u5f85\u4e0a\u4f20";
                case FILE_STATUS.UPLOADING:
                    return "\u4e0a\u4f20\u4e2d";
                case FILE_STATUS.DONE:
                    return "\u4e0a\u4f20\u5b8c\u6210";
                case FILE_STATUS.FAIL:
                    return "\u4e0a\u4f20\u5931\u8d25";
                case FILE_STATUS.SHA_ING:
                    return "\u8ba1\u7b97SHA"
            }
        }
    }
});
qcVideo("validate", function () {
    return {
        checkFileName : function (n) {
            if(n == "" || (/[,<>\'\"]/g.test(n) || n.length > 150)){
                return false
            }
            return true
        }
    }
});
qcVideo("uploader", function (Log, SHA, constants, validate, Code, util, Reporter, ErrorCode, FILE_STATUS) {
    var uploader;
    var MAX_RETRY_TIME             = 5;
    var file_retry_time            = {};
    var useLoadedAsOffsetErrorCode = ["-28996"];
    var listeners                  = [];
    var dispatch                   = function (args) {
        for( var i = 0, j = listeners.length; i < j; i++ ){
            listeners[i](args)
        }
    };
    var getReport                  = function (file) {
        return file["_report"]
    };
    var _coverBack                 = function () {
        if(uploader && (uploader.files && uploader.files.length)){
            var files = uploader.files;
            for( var k in files ){
                files[k].is_from_resume = true
            }
        }
    };
    var _PostInit                  = function () {
    };
    var _FilesAdded                = function (up, files) {
        var deleteIds   = [];
        var illegalType = false;
        var illegalName = false;
        plupload.each(files, function (file) {
            var fileInfo  = file.getNative(), name = fileInfo.name.split(".");
            file.filename = name.slice(0, -1).join(".");
            file.type     = name[name.length - 1].toUpperCase();
            if($.inArray(file.type, constants.ALLOW_UPLOAD_FILE_TYPE) == -1){
                deleteIds.push(file.id);
                illegalType = true
            }else{
                if(!validate.checkFileName(fileInfo.name)){
                    deleteIds.push(file.id);
                    illegalName = true
                }else{
                    file.status     = plupload.HASHING;
                    file["_report"] = Reporter({"name" : file.filename, "size" : file.size});
                    SHA.add(file)
                }
            }
        });
        $.each(deleteIds, function (_, id) {
            uploader.removeFile(id)
        });
        dispatch({
            "code" : Code.BATCH_FILE_SET,
            "message" : "\u6279\u91cf\u6dfb\u52a0\u4e0a\u4f20\u6587\u4ef6\u5b8c\u6210"
        });
        if(illegalType){
            dispatch({
                "code" : Code.ILLEGAL_TYPE,
                "message" : "\u53ea\u80fd\u4e0a\u4f20\u89c6\u9891\u6587\u4ef6\uff0c\u5df2\u81ea\u52a8\u8fc7\u6ee4\u6389\u975e\u89c6\u9891\u6587\u4ef6",
                "solution" : ""
            })
        }
        if(illegalName){
            dispatch({
                "code" : Code.ILLEGAL_NAME,
                "message" : "\u89c6\u9891\u540d\u79f0\u4e3a\u7a7a\uff0c\u6216\u8005\u542b\u6709&lt; &gt; , ' \" \u7b49\u975e\u6cd5\u5b57\u7b26",
                "solution" : "\u8bf7\u53bb\u586b\u5199\u6b63\u786e\u7684\u89c6\u9891\u540d\u79f0\uff0c\u6700\u957f\u5141\u8bb840\u4e2a\u5b57\u7b26"
            })
        }
    };
    var _UploadProgress            = function (up, file) {
        var isValidPercent = file.virtualPercent > 0;
        if(isValidPercent){
            dispatch({
                "code" : Code.UPLOAD_PROGRESS,
                "message" : "\u6587\u4ef6\u8fdb\u5ea6\u66f4\u65b0",
                "file" : file,
                "virtualPercent" : file.virtualPercent,
                "speed" : file.speed
            })
        }
    };
    var _BeforeUpload              = function (up, file) {
        file.usedTime = file.usedTime == undefined ? 0 : file.usedTime;
        $.extend(up.settings.multipart_params, {
            fileName : file.filename,
            fileSha : SHA.getSha1(file.id),
            fileSize : file.size,
            fileType : file.type
        });
        dispatch({"code" : Code.WILL_UPLOAD, "message" : "\u6587\u4ef6\u51c6\u5907\u4e0a\u4f20", "file" : file})
    };
    var _FileUploaded              = function (up, file, result) {
        var res = result.response;
        if(res){
            res = $.parseJSON(res);
            if(res.code == 0 && res.flag == 1){
                dispatch({
                    "code" : Code.UPLOAD_DONE,
                    "message" : "\u6587\u4ef6\u4e0a\u4f20\u5b8c\u6210",
                    "file" : file,
                    "serverFileId" : res["fileId"]
                });
                return true
            }
        }
        file.status = plupload.FAILED;
        dispatch({"code" : Code.UPLOAD_FAIL, "message" : "\u6587\u4ef6\u4e0a\u4f20\u5931\u8d25", "file" : file})
    };
    var _StateChanged              = function (up) {
        if(up.state == plupload.QUEUED || up.state == plupload.STARTED){
            dispatch({"code" : Code.UPLOADER_STATUS_CHANGE, "message" : "\u4e0a\u4f20\u72b6\u6001\u5df2\u5207\u6362"})
        }
    };
    var _ChunkUploaded             = function (up, file, result) {
        var res         = result.response;
        var code        = -1;
        var reportParam = {
            data_size : file.currChunkSize,
            chunk_flag : 0,
            upload_flag : 0,
            cost_time : Math.ceil((new Date - file.currChunkStartUpTime) / 1000),
            action : 1
        };
        if(res){
            res = $.parseJSON(res);
            if(res.code == 0){
                try{
                    clearInterval(file.speedTimer)
                }catch(_){
                }
                if(res.flag == 1){
                    if(file.percent == 100){
                        getReport(file).bSet($.extend(reportParam, {
                            "offset" : file.size,
                            "chunk_flag" : 1,
                            "upload_flag" : 1
                        })).submit();
                        return true
                    }
                    file.status        = plupload.DONE;
                    file.loaded        = file.size - 0;
                    reportParam.offset = file.loaded;
                    up.trigger("FileUploaded", file, result)
                }else{
                    if(res.offset){
                        file.loaded             = res.offset - 0;
                        reportParam.chunk_flag  = 1;
                        reportParam.upload_flag = 0;
                        reportParam.offset      = file.loaded
                    }
                }
                if(file.startUploadOffset == undefined){
                    file.startUploadOffset = file.loaded - up.settings.chunk_size_part1
                }
                file.realFlux = file.loaded - file.startUploadOffset;
                file.usedTime += new Date - file.currChunkStartUpTime;
                file.speed    = util.compute_speed(file.realFlux / file.usedTime * 1000);
                if(file.loaded != file.size){
                    file_retry_time[file.id] = 0;
                    file.speedTimer          = setInterval(function () {
                        var virtualPercent, virtualLoaded, maxPercent, maxLoaded = file.loaded + file.currChunkSize;
                        maxPercent                                               = maxLoaded / file.size * 100;
                        virtualLoaded                                            = (new Date - file.currChunkStartUpTime + file.usedTime) * (file.realFlux / file.usedTime) + file.startUploadOffset;
                        virtualPercent                                           = virtualLoaded / file.size * 100;
                        virtualPercent                                           = virtualPercent > maxPercent ? maxPercent : virtualPercent;
                        virtualPercent                                           = virtualPercent > 100 ? 100 : virtualPercent;
                        if(virtualPercent != 100){
                            virtualPercent = virtualPercent.toFixed(2)
                        }
                        file.virtualPercent = virtualPercent;
                        up.trigger("UploadProgress", file)
                    }, 1000 / 3)
                }else{
                    file.virtualPercent = 100
                }
                getReport(file).bSet(reportParam).submit();
                return true
            }else{
                code = res.code
            }
        }
        up.trigger("Error", {code : code, message : "upload error", file : file})
    };
    var _Error                     = function (up, err) {
        var code = (err.code || "") + "";
        if(code == -600){
            dispatch({
                "code" : Code.OVER_MAX_SIZE,
                "message" : "\u53ea\u80fd\u4e0a\u4f20\u5c0f\u4e8e8G\u7684\u6587\u4ef6\uff0c" + err.file.name + "(" + util.getHStorage(err.file.size) + ")\u5df2\u88ab\u81ea\u52a8\u8fc7\u6ee4",
                "file" : err.file
            })
        }else{
            if(code == -601){
                dispatch({"code" : Code.ILLEGAL_TYPE, "message" : "文件类型不对：" + err.file.name, "file" : err.file})
            }else{
                if(code == -602){
                    dispatch({"code" : Code.DUPLICATE_PICK, "message" : "文件重复添加：" + err.file.name, "file" : err.file})
                }else{
                    var file   = err.file;
                    var fileId = file.id;
                    getReport(file).bSet({
                        data_size : file.currChunkSize,
                        offset : file.loaded ? file.loaded : 0,
                        chunk_flag : 0,
                        upload_flag : 0,
                        cost_time : Math.ceil((new Date - file.currChunkStartUpTime) / 1000),
                        action : 1,
                        error_code : code
                    }).submit();
                    if(!file_retry_time.hasOwnProperty(fileId)){
                        file_retry_time[fileId] = 0
                    }
                    if(file_retry_time[fileId] > MAX_RETRY_TIME){
                        dispatch({
                            "code" : Code.UPLOAD_FAIL,
                            "message" : "\u6587\u4ef6\u4e0a\u4f20\u5931\u8d25",
                            "file" : file,
                            "errorCode" : code
                        })
                    }else{
                        _coverBack();
                        file_retry_time[fileId] += 1;
                        $.extend(file, {
                            status : plupload.QUEUED,
                            loaded : $.inArray(code, useLoadedAsOffsetErrorCode) > -1 ? file.loaded || 0 : 0
                        });
                        dispatch({
                            "code" : Code.UPLOAD_WAIT,
                            "message" : "\u6587\u4ef6\u7b49\u5f85\u4e0a\u4f20",
                            "file" : file
                        });
                        Log.debug("retry", fileId, file_retry_time[fileId])
                    }
                }
            }
        }
    };
    var eachFiles                  = function (fn) {
        for( var k in uploader.files ){
            fn(uploader.files[k])
        }
    };
    return {
        addListener : function (l) {
            listeners.push(l)
        }, startUpload : function () {
            uploader.start();
            dispatch({"code" : Code.UPLOADER_STATUS_CHANGE, "message" : "\u6587\u4ef6\u5f00\u59cb\u4e0a\u4f20"})
        }, reUpload : function () {
            eachFiles(function (file) {
                if(file.status == plupload.FAILED){
                    file.status = plupload.QUEUED;
                    file.loaded = 0
                }
                file.is_from_resume = true
            });
            uploader.start();
            dispatch({
                "code" : Code.UPLOADER_STATUS_CHANGE,
                "message" : "\u6587\u4ef6\u5f00\u59cb\u91cd\u65b0\u4e0a\u4f20"
            })
        }, deleteFile : function (fid) {
            SHA.del(fid);
            uploader.removeFile(fid);
            dispatch({"code" : Code.UPLOADER_STATUS_CHANGE, "message" : "\u6587\u4ef6\u88ab\u5220\u9664"})
        }, stopUpload : function () {
            for( var k in uploader.files ){
                var file = uploader.files[k];
                if(file.speedTimer){
                    delete file.speed;
                    try{
                        clearInterval(file.speedTimer)
                    }catch(_){
                    }
                }
            }
            uploader.stop();
            dispatch({"code" : Code.UPLOADER_STATUS_CHANGE, "message" : "\u6682\u505c\u4e0a\u4f20"})
        }, getOriginalFile : function (fid) {
            var ret;
            eachFiles(function (file) {
                if(file.id == fid){
                    ret = file.getNative()
                }
            });
            return ret
        }, init : function (setting, handlers) {
            var self  = this;
            var empty = function () {
            };
            if(typeof File === "undefined"){
                return ErrorCode.UN_SUPPORT_BROWSE
            }else{
                if(!File.prototype.slice){
                    if(File.prototype.webkitSlice){
                        File.prototype.slice = File.prototype.webkitSlice
                    }else{
                        if(File.prototype.mozSlice){
                            File.prototype.slice = File.prototype.mozSlice
                        }
                    }
                }
            }
            if(!setting.isTranscode){
                delete setting["transcodeNotifyUrl"]
            }
            if(!setting.secretKey && !setting.getSignature){
                alert("\u60a8\u8fd8\u6ca1\u914d\u7f6e\u4e0a\u4f20\u7b7e\u540d\u7684\u83b7\u53d6\u83b7\u53d6\u65b9\u5f0f\uff0c\u8bf7\u53c2\u8003api\u6587\u6863\u8bf4\u660e\uff0c\u8865\u5145\u7b7e\u540d\u4e32")
            }
            if(!!setting["sha1js_path"]){
                SHA.setWorkPath(setting["sha1js_path"])
            }
            SHA.addListener(function (file, precent, info) {
                if(precent == 100 && (!!info && !!info.sha)){
                    file.status      = plupload.QUEUED;
                    file.hashed_size = info.sha;
                    dispatch({
                        "code" : Code.UPLOAD_WAIT,
                        "message" : "\u6587\u4ef6\u7b49\u5f85\u4e0a\u4f20",
                        "cost_time" : info.cost,
                        "file" : file
                    });
                    if(setting["after_sha_start_upload"]){
                        self.startUpload()
                    }
                }else{
                    if(precent >= 0 && precent <= 100){
                        dispatch({
                            "code" : Code.UPLOAD_SHA,
                            "message" : "\u6587\u4ef6SHA\u8ba1\u7b97\u4e2d",
                            "file" : file,
                            "percent" : precent
                        })
                    }
                }
            });
            (uploader = new plupload.Uploader({
                multi_selection : !setting["disable_multi_selection"],
                extBusiness : setting,
                max_retries : 0,
                runtimes : "html5",
                browse_button : setting["upBtnId"],
                url : setting["web_upload_url"],
                multipart : false,
                chunk_size : "5mb",
                chunk_size_part1 : "512kb",
                chunk_size_step : "2mb",
                multipart_params : {
                    SecretId : setting["secretId"],
                    isTranscode : !!setting["isTranscode"] && setting["isTranscode"] != "0" ? 1 : 0,
                    isScreenshot : 1,
                    isWatermark : !!setting["isWatermark"] && setting["isWatermark"] != "0" ? 1 : 0
                },
                filters : {
                    max_file_size : "8gb",
                    mime_types : [{title : "media files", extensions : constants.ALLOW_UPLOAD_FILE_TYPE.join(",")}]
                },
                init : {
                    PostInit : _PostInit,
                    FilesAdded : _FilesAdded,
                    UploadProgress : _UploadProgress,
                    BeforeUpload : _BeforeUpload,
                    FileUploaded : _FileUploaded,
                    StateChanged : _StateChanged,
                    ChunkUploaded : _ChunkUploaded,
                    Error : _Error
                },
                signature : function (argStr, cb) {
                    if(!!setting.secretKey){
                        cb(CryptoJS.enc.Base64.stringify(CryptoJS.HmacSHA1(argStr, setting.secretKey)))
                    }else{
                        if(setting.getSignature){
                            setting.getSignature(argStr, function (signature) {
                                cb(signature)
                            })
                        }else{
                            alert("\u65e0\u7b7e\u540d\u4e32\uff0c\u8bf7\u53c2\u8003api\u6587\u6863\u8bf4\u660e\uff0c\u8865\u5145\u7b7e\u540d\u4e32")
                        }
                    }
                }
            })).init();
            var updateFile        = handlers.onFileUpdate || empty;
            var getUpdateFileArgs = function (code, file, percent, speed, errorCode, serverFileId) {
                return {
                    code : code,
                    id : file.id,
                    size : file.size,
                    name : file.name,
                    status : file.status,
                    percent : percent,
                    speed : speed,
                    errorCode : errorCode,
                    serverFileId : serverFileId
                }
            };
            self.addListener(function (args) {
                switch(args.code){
                    case Code.UPLOAD_SHA:
                        updateFile(getUpdateFileArgs(args.code, args.file, args.percent ? args.percent : 0));
                        break;
                    case Code.UPLOAD_WAIT:
                        updateFile(getUpdateFileArgs(args.code, args.file));
                        Log.debug(args.file.id, args.message, args.file.name, (args.cost_time / 1000).toFixed(2));
                        break;
                    case Code.WILL_UPLOAD:
                        Log.debug(args.file.id, args.message);
                        break;
                    case Code.UPLOAD_PROGRESS:
                        updateFile(getUpdateFileArgs(args.code, args.file, args.virtualPercent, args.speed));
                        break;
                    case Code.UPLOAD_DONE:
                        updateFile(getUpdateFileArgs(args.code, args.file, null, null, null, args.serverFileId));
                        break;
                    case Code.UPLOAD_FAIL:
                        updateFile(getUpdateFileArgs(args.code, args.file, null, null, args.errorCode));
                        break
                }
            });
            var updateStatus = handlers.onFileStatus || empty;
            self.addListener(function (args) {
                if($.inArray(args.code, constants.HIGH_FREQUENCY_STATUS) === -1){
                    var count = {done : 0, fail : 0, sha : 0, wait : 0, uploading : 0};
                    eachFiles(function (file) {
                        var s = file.status;
                        if(s == plupload.DONE){
                            count.done += 1
                        }else{
                            if(s == plupload.HASHING){
                                count.sha += 1
                            }else{
                                if(s == plupload.FAILED){
                                    count.fail += 1
                                }else{
                                    if(s == plupload.QUEUED){
                                        count.wait += 1
                                    }else{
                                        if(s == plupload.UPLOADING){
                                            count.uploading += 1
                                        }
                                    }
                                }
                            }
                        }
                    });
                    updateStatus(count)
                }
            });
            var filterError = handlers.onFilterError || empty;
            self.addListener(function (args) {
                if(args.code == Code.ILLEGAL_TYPE || args.code == Code.ILLEGAL_NAME){
                    filterError(args)
                }
            })
        }
    }
});
if(!XMLHttpRequest.prototype.sendAsBinary){
    XMLHttpRequest.prototype.sendAsBinary = function (sData) {
        var nBytes = sData.length, ui8Data = new Uint8Array(nBytes);
        for( var nIdx = 0; nIdx < nBytes; nIdx++ ){
            ui8Data[nIdx] = sData.charCodeAt(nIdx) & 255
        }
        this.send(ui8Data.buffer)
    }
}
qcVideo.uploader = qcVideo.get("uploader");
function echo(stringA, stringB) {
    var hello = "你好";
    alert("hello world")
};