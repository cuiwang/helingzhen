var Ν = {
        width: 640,
        I: 45
    };
Ν.q = 1000 / Ν.I >> 0;
"use strict";
(function (exports, undefined) {
    var w = exports.Toucher = exports.Toucher || {};
    var А = w.Ρ = w.Ρ || {};
    А.Η = [
        "touches",
        "changedTouches",
        "targetTouches"
    ];
    А.touches = "touches";
    А.changedTouches = "changedTouches";
    А.targetTouches = "targetTouches";
    А.М = 1;
    var T = w.X = function (U) {
            for (var y in U) {
                this[y] = U[y]
            }
            this.O = this.O || w.l7
        };
    var Y = {
            constructor: T,
            O: null,
            host: window,
            j: document,
            Тq: 1,
            Е: false,
            i: false,
            p: true,
            preventDefault: false,
            N: true,
            V: false,
            s: false,
            Ζ: false,
            L: false,
            offsetLeft: 0,
            offsetTop: 0,
            С: 30,
            Υ: 5,
            Χ: null,
            Yb: null,
            ΥΧ: null,
            РP: 0,
            m: 0,
            Β: function () {
            },
            Ϻ: function () {
                this.S0 = [];
                this.reset();
                this.Β();
                var g = this.j;
                this.Ѕx();
                this.Е = "ontouchstart" in this.j;
                if (!this.Е) {
                    this.i = true
                }
                if (this.i) {
                    А.Gj = "mousedown";
                    А.ΖО = "mousemove";
                    А.Χu = "mouseup";
                    А.Ζg = null
                } else {
                    А.Gj = "touchstart";
                    А.ΖО = "touchmove";
                    А.Χu = "touchend";
                    А.Ζg = "touchcancel"
                }
                var J = this;
                if (!this.i && this.N) {
                    if ("ongesturestart" in g) {
                        g.addEventListener("gesturestart", function (f) {
                            f.preventDefault()
                        }, true);
                        g.addEventListener("gesturechange", function (Η) {
                            Η.preventDefault()
                        }, true);
                        g.addEventListener("gestureend", function (Е) {
                            Е.preventDefault()
                        }, true)
                    }
                }
                g.addEventListener(А.Gj, function (G) {
                    var a = Date.now();
                    if (J.i) {
                        J.reset()
                    }
                    if (J.g_ !== null && J.g_(G, a) === false) {
                        return
                    }
                    J.NР(G, a);
                    if (J.V || J.preventDefault) {
                        G.preventDefault()
                    }
                }, this.p);
                g.addEventListener(А.ΖО, function (H) {
                    var Q = Date.now();
                    if (Q - J.РP < J.m || J.cS !== null && J.cS(H, Q) === false) {
                        return
                    }
                    J.РP = Q;
                    J.Μ5(H, Q);
                    if (J.s || J.preventDefault) {
                        H.preventDefault()
                    }
                }, this.p);
                var О = function (_) {
                    var r = Date.now();
                    if (J.ΒS !== null && J.ΒS(_, r) === false) {
                        return
                    }
                    J.Mw(_, r);
                    if (J.Ζ || J.preventDefault) {
                        _.preventDefault()
                    }
                };
                g.addEventListener(А.Χu, О, this.p);
                if (this.i) {
                    window.addEventListener("mouseout", function (F) {
                        var Η = F.relatedTarget || F.toElement;
                        if (!Η || Η.nodeName == "HTML") {
                            О(F)
                        }
                        F.preventDefault()
                    }, false)
                } else {
                    g.addEventListener(А.Ζg, function (h) {
                        var М = Date.now();
                        if (J.vW !== null && J.vW(h) === false) {
                            return
                        }
                        J.reset();
                        J.Me(h, М);
                        if (J.L || J.preventDefault) {
                            h.preventDefault()
                        }
                    }, this.p)
                }
                this.Сs()
            },
            Сs: function () {
            },
            reset: function () {
                this.Χ = [];
                this.Yb = [];
                this.ΥΧ = [];
                this.Χ.CΕ = this.Yb.CΕ = this.ΥΧ.CΕ = 0;
                this.Ζl = {};
                this.b0 = 0
            },
            Ѕx: function () {
                var _ = this.j;
                if (_ === window || _ === document) {
                    this.offsetLeft = 0;
                    this.offsetTop = 0;
                    return
                }
                if (_.getBoundingClientRect !== undefined) {
                    var Ϻ = window.pageXOffset, Ο = 0;
                    if (Ϻ || Ϻ === 0) {
                        Ο = window.pageYOffset
                    } else {
                        Ϻ = document.body.scrollLeft;
                        Ο = document.body.scrollTop
                    }
                    var O = _.getBoundingClientRect();
                    this.offsetLeft = O.left + Ϻ;
                    this.offsetTop = O.top + Ο;
                    return
                }
                var a = _.offsetLeft, F = _.offsetTop;
                while ((_ = _.parentNode) && _ !== document.body && _ !== document) {
                    a += _.offsetLeft;
                    F += _.offsetTop
                }
                this.offsetLeft = a;
                this.offsetTop = F
            },
            g_: null,
            NР: function (y, U) {
                var u = this.ow(y, U);
                this.aC("start", u, y)
            },
            cS: null,
            Μ5: function (Ϲ, Z) {
                var М = this.ΒΜ(Ϲ, Z);
                this.aC("move", М, Ϲ)
            },
            ΒS: null,
            Mw: function (q, X) {
                var F = this.YϺ(q, X);
                this.aC("end", F, q)
            },
            vW: null,
            Me: function (a, V) {
                console.log("cancel", this.S0.length);
                for (var Ρ = 0, Τ = this.S0.length; Ρ < Τ; Ρ++) {
                    var І = this.S0[Ρ];
                    if (І.cancel != null) {
                        if (І.cancel(null, a, this) === false) {
                            break
                        }
                    }
                }
            },
            EС: function (Υ, f) {
                if (Υ.length >= this.Υ) {
                    Υ.shift()
                }
                Υ.push(f)
            },
            HN: function (q, r) {
                if (q.length >= this.Υ) {
                    q.shift()
                }
                q.push(r)
            },
            ow: function (q, Χ) {
                var Ϝ = q[А.changedTouches] || [q];
                var Р = [];
                for (var Z = 0, W = Ϝ.length; Z < W; Z++) {
                    var Ϲ = Ϝ[Z];
                    var x = Ϲ.Ia;
                    var Q = x || x === 0 ? x : А.М;
                    var _ = this.Ζl[Q];
                    _ = new this.O(Q);
                    _.Тq = this.Тq;
                    this.Ζl[Q] = _;
                    this.b0++;
                    _.start(Ϲ, q);
                    Р.push(_);
                    var m = this.Χ;
                    if (Χ - m.CΕ > this.С) {
                        m.length = 0
                    }
                    m.CΕ = Χ;
                    m.push(_)
                }
                return Р
            },
            ΒΜ: function (O, H) {
                var Ο = O[А.changedTouches] || [O];
                var y = [];
                for (var Α = 0, X = Ο.length; Α < X; Α++) {
                    var R = Ο[Α];
                    var a = R.Ia;
                    var Ρ = a || a === 0 ? a : А.М;
                    var Ϝ = this.Ζl[Ρ];
                    if (Ϝ) {
                        if (!Ϝ.np) {
                            var f = this.Yb;
                            if (H - f.CΕ > this.С) {
                                f.length = 0
                            }
                            f.CΕ = H;
                            f.push(Ϝ)
                        }
                        Ϝ.k(R, O);
                        y.push(Ϝ)
                    }
                }
                return y
            },
            YϺ: function (z, V) {
                var Ε = z[А.changedTouches] || [z];
                var G = {};
                if (!this.i) {
                    var Ϻ = z[А.touches];
                    for (var y = Ϻ.length - 1; y >= 0; y--) {
                        var B = Ϻ[y];
                        G[B.Ia] = true
                    }
                }
                var Τ = [];
                for (var X = 0, Α = Ε.length; X < Α; X++) {
                    var o = Ε[X];
                    var q = o.Ia;
                    var W = q || q === 0 ? q : А.М;
                    if (!G[W]) {
                        var Κ = this.Ζl[W];
                        if (Κ) {
                            Κ.end(o);
                            delete this.Ζl[W];
                            this.b0--;
                            Τ.push(Κ);
                            var H = this.ΥΧ;
                            if (V - H.CΕ > this.С) {
                                H.length = 0
                            }
                            H.CΕ = V;
                            H.push(Κ);
                            this.РW(this.Χ, W);
                            this.РW(this.Yb, W)
                        }
                    }
                }
                return Τ
            },
            РW: function (F, М) {
                for (var Р = F.length - 1; Р >= 0; Р--) {
                    if (F[Р].Ia == М) {
                        F.splice(Р, 1);
                        return М
                    }
                }
                return false
            },
            aC: function (Κ, _, q) {
                for (var o = 0, Υ = this.S0.length; o < Υ; o++) {
                    var Ε = this.S0[o];
                    if (Ε[Κ] != null) {
                        var u = Ε.Еc(Κ, _, q, this);
                        if (u === true) {
                            u = _
                        }
                        if (u && u.length > 0) {
                            if (Ε[Κ](u, q, this) === false) {
                                break
                            }
                        }
                    }
                }
            },
            c: function (J) {
                J.controller = this;
                J.offsetLeft = this.offsetLeft;
                J.offsetTop = this.offsetTop;
                J.Ϻ();
                this.S0.push(J);
                J.order = J.order || 0
            },
            CW: function (Β) {
                for (var О = this.S0.length - 1; О >= 0; О--) {
                    if (this.S0[О] == Β) {
                        this.S0.splice(О, 1);
                        Β.controller = null;
                        return Β
                    }
                }
                return null
            },
            ΚϜ: function () {
                for (var Z = this.S0.length - 1; Z >= 0; Z--) {
                    Т.controller = null
                }
                this.S0.length = 0
            }
        };
    for (var d in Y) {
        T.prototype[d] = Y[d]
    }
}(this));
"use strict";
(function (exports, undefined) {
    var B = exports.Toucher = exports.Toucher || {};
    var Ϲ = B.Ρ = B.Ρ || {};
    var g = B.l7 = function (Χ) {
            this.Ia = Χ;
            this.id = Χ
        };
    var z = {
            constructor: g,
            Тq: 1,
            start: function (a, m) {
                this.type = Ϲ.Gj;
                this.update(a, m);
                this.Hh = this.JΤ = this.pageX;
                this.as = this.vЅ = this.pageY;
                this.ІF = this.UМ = this.target;
                this.yМ = 0;
                this.Rt = 0;
                this.Τ9 = 0;
                this.Τd = 0;
                this.nb = true;
                this.startTime = this.А = Date.now()
            },
            k: function (Β, W) {
                this.type = Ϲ.ΖО;
                this.update(Β, W);
                this.np = Date.now()
            },
            end: function (Ο, w) {
                this.type = Ϲ.Χu;
                this.update(Ο, w);
                this._5 = this.pageX;
                this.Тu = this.pageY;
                this.gO = this.target;
                this.nb = false;
                this.А = Date.now()
            },
            update: function (М, R) {
                this.ΙI = R;
                this.zΟ = М;
                this.UМ = this.target;
                this.JΤ = this.pageX;
                this.vЅ = this.pageY;
                this.target = М.target;
                this.pageX = М.pageX * this.Тq;
                this.pageY = М.pageY * this.Тq;
                this.yМ = this.pageX - this.JΤ;
                this.Rt = this.pageY - this.vЅ;
                this.Τ9 = this.pageX - this.Hh;
                this.Τd = this.pageY - this.as
            }
        };
    for (var Ρ in z) {
        g.prototype[Ρ] = z[Ρ]
    }
}(this));
"use strict";
(function (exports, undefined) {
    var Z = exports.Toucher = exports.Toucher || {};
    var Ϻ = Z.Ρ = Z.Ρ || {};
    var f = Z.kR = function (g) {
            for (var a in g) {
                this[a] = g[a]
            }
        };
    f.extend = function (Β) {
        var _ = function (Ј) {
            for (var q in Ј) {
                this[q] = Ј[q]
            }
        };
        var w = _.prototype;
        var Η = this.prototype;
        for (var Α in Η) {
            w[Α] = Η[Α]
        }
        for (var Α in Β) {
            w[Α] = Β[Α]
        }
        w.constructor = _;
        _.extend = this.extend;
        _.Іo = Η;
        _.jc = this;
        return _
    };
    var U = {
            constructor: f,
            id: null,
            type: null,
            offsetLeft: 0,
            offsetTop: 0,
            order: 1,
            Β: function () {
            },
            Ϻ: function () {
                this.Β();
                this.Сs()
            },
            Сs: function () {
            },
            Еc: function (F, V, Ο, P) {
                var Y = [];
                for (var Β = 0, Ϲ = V.length; Β < Ϲ; Β++) {
                    var _ = V[Β];
                    if (this.Н(F, _, Ο, P)) {
                        Y.push(_)
                    }
                }
                return Y
            },
            Н: function (z, T, B, Ρ) {
                return false
            },
            start: null,
            k: null,
            end: null,
            cancel: null
        };
    for (var r in U) {
        f.prototype[r] = U[r]
    }
}(this));
"use strict";
Toucher.АE = Toucher.kR.extend({
    Еc: function (U, О, H, Q) {
        return true
    },
    start: function (W, Τ, Ε) {
    },
    k: function (G, Κ, Ρ) {
    },
    end: function (z, Р, T) {
    },
    cancel: function (W, Р, y) {
    },
    РΧ: function (Е, u, G, w, Υ, U, R, a, Β) {
    }
});
"use strict";
Toucher.Ι = Toucher.kR.extend({
    Ν: 800,
    G: 15,
    Еc: function (u, I, K, G) {
        if (I.length == 1 && this.Н(u, I[0], K, G)) {
            return I
        }
        return false
    },
    Н: function (V, W, R, Е) {
        return true
    },
    start: function (Y, o, I) {
        if (this.АJ != null) {
            this.АJ(Y, o, I)
        }
    },
    АJ: null,
    k: function (Ϝ, q, z) {
        if (this.F0 != null) {
            this.F0(Ϝ, q, z)
        }
    },
    F0: null,
    end: function (Y, H, Z) {
        var Α = Y[0];
        var a = Α.pageX;
        var Ј = Α.pageY;
        if (this.cΝ(Α) && this.R5(Α)) {
            this.Κz = true;
            this.І(a, Ј, Y, H, Z)
        }
        if (this.В != null) {
            this.В(a, Ј, Y, H, Z)
        }
        this.Κz = false
    },
    В: null,
    cΝ: function (Ρ) {
        var R = Math.abs(Ρ.Τ9);
        var Υ = Math.abs(Ρ.Τd);
        return R <= this.G && Υ <= this.G
    },
    R5: function (O) {
        return O.А - O.startTime < this.Ν
    },
    І: function (d, r, І, I, Ј) {
    }
});
"use strict";
Toucher.P5 = Toucher.kR.extend({
    vО: false,
    Еc: function (Ο, P, y, Β) {
        return P.length == 1
    },
    start: function (r, q, Υ) {
    },
    k: function (d, Τ, Ϻ) {
        var B = d[0];
        var Y = B.yМ;
        var a = B.Rt;
        var G = B.pageX;
        var Ј = B.pageY;
        this.DO(Y, a, G, Ј, B.Hh, B.as, d, Τ, Ϻ)
    },
    end: function (d, J, Ϝ) {
    },
    DO: function (U, Q, J, w, Ο, T, O, G, Е) {
    }
});
"use strict";
Toucher.Z = Toucher.kR.extend({
    Ο7: 60,
    Cl: 1000,
    Еc: function (G, Z, Ρ, r) {
        return Z.length == 1
    },
    start: function (Ϲ, Q, u) {
    },
    k: function (h, Е, y) {
    },
    end: function (f, Κ, d) {
        var Р = f[0];
        var x = Р.А - Р.startTime;
        if (x > this.Cl) {
            if (this.В != null) {
                this.В(V, Y, f, Κ, d)
            }
            return
        }
        var V = Р._5 - Р.Hh;
        var Y = Р.Тu - Р.as;
        if (V != 0 || Y != 0) {
            this.F(V, Y, x, f, Κ, d)
        }
    },
    В: null,
    F: function (w, Β, Α, Κ, a, О) {
    }
});
if (Date.now === undefined) {
    Date.now = function () {
        return new Date().valueOf()
    }
}
var E = E || function () {
        var Υ = [];
        return {
            ϺA: "14",
            ih: function () {
                return Υ
            },
            b8: function () {
                Υ = []
            },
            add: function (Е) {
                Υ.push(Е)
            },
            remove: function (Ϝ) {
                var B = Υ.indexOf(Ϝ);
                if (B !== -1) {
                    Υ.splice(B, 1)
                }
            },
            update: function (Τ) {
                if (Υ.length === 0)
                    return false;
                var Ϲ = 0;
                Τ = Τ !== undefined ? Τ : typeof window !== "undefined" && window.performance !== undefined && window.performance.now !== undefined ? window.performance.now() : Date.now();
                while (Ϲ < Υ.length) {
                    if (Υ[Ϲ].update(Τ)) {
                        Ϲ++
                    } else {
                        Υ.splice(Ϲ, 1)
                    }
                }
                return true
            }
        }
    }();
E.AX = function (g) {
    var J = g;
    var Q = null;
    var r = {};
    var Β = null;
    var V = 1000;
    var Η = 0;
    var _ = false;
    var Ϻ = false;
    var O = false;
    var o = 0;
    var Χ = null;
    var m = E.LW._Ѕ.yg;
    var h = E.kl._Ѕ;
    var P = [];
    var W = null;
    var U = false;
    var w = null;
    var А = null;
    var Ϝ = null;
    this.aH = function (j) {
        Q = {};
        Β = {};
        for (var I in j) {
            Q[I] = parseFloat(j[I], 10)
        }
        return this
    };
    this.p1 = function (F, Α) {
        if (Α !== undefined) {
            V = Α
        }
        r = F;
        return this
    };
    this.start = function (X) {
        if (!Q) {
            this.aH(J)
        } else {
            for (var R in Q) {
                J[R] = Q[R]
            }
        }
        E.add(this);
        Ϻ = true;
        U = false;
        Χ = X !== undefined ? X : typeof window !== "undefined" && window.performance !== undefined && window.performance.now !== undefined ? window.performance.now() : Date.now();
        Χ += o;
        for (var R in r) {
            if (r[R] instanceof Array) {
                if (r[R].length === 0) {
                    continue
                }
                r[R] = [J[R]].concat(r[R])
            }
            Q[R] = J[R];
            if (Q[R] instanceof Array === false) {
                Q[R] *= 1
            }
            Β[R] = Q[R] || 0
        }
        return this
    };
    this.stop = function () {
        if (!Ϻ) {
            return this
        }
        E.remove(this);
        Ϻ = false;
        if (Ϝ !== null) {
            Ϝ.call(J)
        }
        this.Rj();
        return this
    };
    this.Rj = function () {
        for (var f = 0, О = P.length; f < О; f++) {
            P[f].stop()
        }
    };
    this.Cf = function (T) {
        o = T;
        return this
    };
    this.repeat = function (О) {
        Η = О;
        return this
    };
    this.ra = function (R) {
        _ = R;
        return this
    };
    this.Ρa = function (Р) {
        m = Р;
        return this
    };
    this.Тt = function (Р) {
        h = Р;
        return this
    };
    this.ОS = function () {
        P = arguments;
        return this
    };
    this.NР = function (x) {
        W = x;
        return this
    };
    this.A8 = function (X) {
        w = X;
        return this
    };
    this.d1 = function (K) {
        А = K;
        return this
    };
    this.oK = function (u) {
        Ϝ = u;
        return this
    };
    this.update = function (q) {
        var z;
        if (q < Χ) {
            return true
        }
        if (U === false) {
            if (W !== null) {
                W.call(J)
            }
            U = true
        }
        var Ϲ = (q - Χ) / V;
        Ϲ = Ϲ > 1 ? 1 : Ϲ;
        var R = m(Ϲ);
        for (z in r) {
            var F = Q[z] || 0;
            var І = r[z];
            if (І instanceof Array) {
                J[z] = h(І, R)
            } else {
                if (typeof І === "string") {
                    І = F + parseFloat(І, 10)
                }
                if (typeof І === "number") {
                    J[z] = F + (І - F) * R
                }
            }
        }
        if (w !== null) {
            w.call(J, R)
        }
        if (Ϲ == 1) {
            if (Η > 0) {
                if (isFinite(Η)) {
                    Η--
                }
                for (z in Β) {
                    if (typeof r[z] === "string") {
                        Β[z] = Β[z] + parseFloat(r[z], 10)
                    }
                    if (_) {
                        var j = Β[z];
                        Β[z] = r[z];
                        r[z] = j
                    }
                    Q[z] = Β[z]
                }
                if (_) {
                    O = !O
                }
                Χ = q + o;
                return true
            } else {
                if (А !== null) {
                    А.call(J)
                }
                for (var Ε = 0, x = P.length; Ε < x; Ε++) {
                    P[Ε].start(q)
                }
                return false
            }
        }
        return true
    }
};
E.LW = {
    _Ѕ: {
        yg: function (Ϲ) {
            return Ϲ
        }
    },
    qТ: {
        nD: function (j) {
            return j * j
        },
        Mu: function (А) {
            return А * (2 - А)
        },
        f1: function (R) {
            if ((R *= 2) < 1)
                return 0.5 * R * R;
            return -0.5 * (--R * (R - 2) - 1)
        }
    },
    ϹD: {
        nD: function (Ј) {
            return Ј * Ј * Ј
        },
        Mu: function (z) {
            return --z * z * z + 1
        },
        f1: function (Ϲ) {
            if ((Ϲ *= 2) < 1)
                return 0.5 * Ϲ * Ϲ * Ϲ;
            return 0.5 * ((Ϲ -= 2) * Ϲ * Ϲ + 2)
        }
    },
    qE: {
        nD: function (d) {
            return d * d * d * d
        },
        Mu: function (a) {
            return 1 - --a * a * a * a
        },
        f1: function (Ϝ) {
            if ((Ϝ *= 2) < 1)
                return 0.5 * Ϝ * Ϝ * Ϝ * Ϝ;
            return -0.5 * ((Ϝ -= 2) * Ϝ * Ϝ * Ϝ - 2)
        }
    },
    EQ: {
        nD: function (Χ) {
            return Χ * Χ * Χ * Χ * Χ
        },
        Mu: function (V) {
            return --V * V * V * V * V + 1
        },
        f1: function (f) {
            if ((f *= 2) < 1)
                return 0.5 * f * f * f * f * f;
            return 0.5 * ((f -= 2) * f * f * f * f + 2)
        }
    },
    РϺ: {
        nD: function (B) {
            return 1 - Math.cos(B * Math.PI / 2)
        },
        Mu: function (G) {
            return Math.sin(G * Math.PI / 2)
        },
        f1: function (m) {
            return 0.5 * (1 - Math.cos(Math.PI * m))
        }
    },
    _B: {
        nD: function (Κ) {
            return Κ === 0 ? 0 : Math.pow(1024, Κ - 1)
        },
        Mu: function (a) {
            return a === 1 ? 1 : 1 - Math.pow(2, -10 * a)
        },
        f1: function (y) {
            if (y === 0)
                return 0;
            if (y === 1)
                return 1;
            if ((y *= 2) < 1)
                return 0.5 * Math.pow(1024, y - 1);
            return 0.5 * (-Math.pow(2, -10 * (y - 1)) + 2)
        }
    },
    Ζs: {
        nD: function (F) {
            return 1 - Math.sqrt(1 - F * F)
        },
        Mu: function (a) {
            return Math.sqrt(1 - --a * a)
        },
        f1: function (_) {
            if ((_ *= 2) < 1)
                return -0.5 * (Math.sqrt(1 - _ * _) - 1);
            return 0.5 * (Math.sqrt(1 - (_ -= 2) * _) + 1)
        }
    },
    vМ: {
        nD: function (Ε) {
            var G, Y = 0.1, І = 0.4;
            if (Ε === 0)
                return 0;
            if (Ε === 1)
                return 1;
            if (!Y || Y < 1) {
                Y = 1;
                G = І / 4
            } else
                G = І * Math.asin(1 / Y) / (2 * Math.PI);
            return -(Y * Math.pow(2, 10 * (Ε -= 1)) * Math.sin((Ε - G) * (2 * Math.PI) / І))
        },
        Mu: function (Η) {
            var О, z = 0.1, Υ = 0.4;
            if (Η === 0)
                return 0;
            if (Η === 1)
                return 1;
            if (!z || z < 1) {
                z = 1;
                О = Υ / 4
            } else
                О = Υ * Math.asin(1 / z) / (2 * Math.PI);
            return z * Math.pow(2, -10 * Η) * Math.sin((Η - О) * (2 * Math.PI) / Υ) + 1
        },
        f1: function (G) {
            var f, Р = 0.1, z = 0.4;
            if (G === 0)
                return 0;
            if (G === 1)
                return 1;
            if (!Р || Р < 1) {
                Р = 1;
                f = z / 4
            } else
                f = z * Math.asin(1 / Р) / (2 * Math.PI);
            if ((G *= 2) < 1)
                return -0.5 * (Р * Math.pow(2, 10 * (G -= 1)) * Math.sin((G - f) * (2 * Math.PI) / z));
            return Р * Math.pow(2, -10 * (G -= 1)) * Math.sin((G - f) * (2 * Math.PI) / z) * 0.5 + 1
        }
    },
    rT: {
        nD: function (P) {
            var w = 1.70158;
            return P * P * ((w + 1) * P - w)
        },
        Mu: function (P) {
            var Ϝ = 1.70158;
            return --P * P * ((Ϝ + 1) * P + Ϝ) + 1
        },
        f1: function (w) {
            var V = 1.70158 * 1.525;
            if ((w *= 2) < 1)
                return 0.5 * (w * w * ((V + 1) * w - V));
            return 0.5 * ((w -= 2) * w * ((V + 1) * w + V) + 2)
        }
    },
    FF: {
        nD: function (x) {
            return 1 - E.LW.FF.Mu(1 - x)
        },
        Mu: function (P) {
            if (P < 1 / 2.75) {
                return 7.5625 * P * P
            } else if (P < 2 / 2.75) {
                return 7.5625 * (P -= 1.5 / 2.75) * P + 0.75
            } else if (P < 2.5 / 2.75) {
                return 7.5625 * (P -= 2.25 / 2.75) * P + 0.9375
            } else {
                return 7.5625 * (P -= 2.625 / 2.75) * P + 0.984375
            }
        },
        f1: function (I) {
            if (I < 0.5)
                return E.LW.FF.nD(I * 2) * 0.5;
            return E.LW.FF.Mu(I * 2 - 1) * 0.5 + 0.5
        }
    }
};
E.kl = {
    _Ѕ: function (І, w) {
        var Q = І.length - 1, Ρ = Q * w, X = Math.floor(Ρ), g = E.kl.ΚΝ._Ѕ;
        if (w < 0)
            return g(І[0], І[1], Ρ);
        if (w > 1)
            return g(І[Q], І[Q - 1], Q - Ρ);
        return g(І[X], І[X + 1 > Q ? Q : X + 1], Ρ - X)
    },
    _M: function (R, O) {
        var Z = 0, U = R.length - 1, m = Math.pow, T = E.kl.ΚΝ.Α2, Е;
        for (Е = 0; Е <= U; Е++) {
            Z += m(1 - O, U - Е) * m(O, Е) * R[Е] * T(U, Е)
        }
        return Z
    },
    sa: function (М, H) {
        var o = М.length - 1, Κ = o * H, z = Math.floor(Κ), X = E.kl.ΚΝ.sa;
        if (М[0] === М[o]) {
            if (H < 0)
                z = Math.floor(Κ = o * (1 + H));
            return X(М[(z - 1 + o) % o], М[z], М[(z + 1) % o], М[(z + 2) % o], Κ - z)
        } else {
            if (H < 0)
                return М[0] - (X(М[0], М[0], М[1], М[1], -Κ) - М[0]);
            if (H > 1)
                return М[o] - (X(М[o], М[o], М[o - 1], М[o - 1], Κ - o) - М[o]);
            return X(М[z ? z - 1 : 0], М[z], М[o < z + 1 ? o : z + 1], М[o < z + 2 ? o : z + 2], Κ - z)
        }
    },
    ΚΝ: {
        _Ѕ: function (B, j, Ј) {
            return (j - B) * Ј + B
        },
        Α2: function (Р, О) {
            var А = E.kl.ΚΝ.z1;
            return А(Р) / А(О) / А(Р - О)
        },
        z1: function () {
            var Р = [1];
            return function (u) {
                var H = 1, Τ;
                if (Р[u])
                    return Р[u];
                for (Τ = u; Τ > 1; Τ--)
                    H *= Τ;
                return Р[u] = H
            }
        }(),
        sa: function (Q, o, Τ, Ϻ, T) {
            var j = (Τ - Q) * 0.5, О = (Ϻ - o) * 0.5, Y = T * T, Κ = T * Y;
            return (2 * o - 2 * Τ + j + О) * Κ + (-3 * o + 3 * Τ - 2 * j - О) * Y + j * T + o
        }
    }
};
"use strict";
(function (exports, undefined) {
    var J = exports.Best = exports.Best || {};
    var М = J.НP = function (f) {
            for (var Y in f) {
                this[Y] = f[Y]
            }
        };
    М.prototype = {
        constructor: М,
        id: null,
        width: 600,
        height: 400,
        I: 60,
        Ρy: "",
        u6: null,
        Ϻ: function () {
            var G = this;
            this.ΤE = function () {
                G.Н3()
            };
            this.ЈG = {
                now: 0,
                ЅB: 0,
                step: Math.round(1000 / this.I)
            };
            this.H5();
            if (this.Сs) {
                this.Сs.apply(this, arguments)
            }
        },
        H5: function () {
        },
        start: function () {
            this.ЈG.now = Date.now();
            this.ЈG.ЅB = Date.now();
            this.paused = false;
            this.Υz = true;
            if (this.NР) {
                this.NР()
            }
            this.Н3()
        },
        stop: function () {
            this.Υz = false;
            if (this.oK) {
                this.oK()
            }
        },
        pause: function () {
            this.paused = true;
            if (this.wB) {
                this.wB()
            }
        },
        PІ: function () {
            this.paused = false;
            if (this.xA) {
                this.xA()
            }
        },
        Н3: function () {
            var Ϻ = this.ЈG.now = Date.now();
            var Η = Ϻ - this.ЈG.ЅB;
            this.ЈG.ЅB = Ϻ;
            this.Ng = setTimeout(this.ΤE, this.ЈG.step);
            this.ar(Η, Ϻ);
            if (!this.paused && Η > 1) {
                this.update(Η, Ϻ);
                this.PΝ(Η, Ϻ)
            }
            if (!this.Υz) {
                clearTimeout(this.Ng)
            }
        },
        fs: function (d) {
            this.ZР = d;
            d.cF()
        },
        update: function (А, m) {
            if (this.ZР) {
                this.ZР.update(А, m)
            }
        },
        PΝ: function (o, W) {
            if (this.ZР) {
                this.ZР.PΝ(this.u6, o, W)
            }
        },
        ar: function (Ϝ, Ϻ) {
            if (this.ZР) {
                this.ZР.ar(Ϝ, Ϻ)
            }
        },
        Сs: null,
        NР: null,
        wB: null,
        xA: null,
        oK: null
    };
    var r = J.hg = function (H) {
            for (var w in H) {
                this[w] = H[w]
            }
        };
    r.prototype = {
        constructor: r,
        id: null,
        Ϻ: function (u) {
        },
        cF: function () {
        },
        AΥ: function (Ϝ) {
        },
        update: function (g, Ј) {
        },
        PΝ: function (G, o, _) {
        },
        ar: function (d, m) {
        }
    };
    J.extend = function (Ϻ, Ρ) {
        var Е = function (Α) {
            for (var B in Α) {
                this[B] = Α[B]
            }
        };
        var d = Е.prototype, V;
        if (Ρ) {
            V = Ρ.prototype;
            for (var x in V) {
                d[x] = V[x]
            }
        }
        for (var x in Ϻ) {
            d[x] = Ϻ[x]
        }
        d.constructor = Е;
        Е.Іo = V;
        Е.jc = Ρ || null;
        Е.extend = this.extend;
        return Е
    };
    function Τ(Ϻ) {
        return J.extend(Ϻ, this)
    }
    М.extend = Τ;
    r.extend = Τ
}(this));
"use strict";
(function (exports, undefined) {
    exports.ResourcePool = {
        Т2: {},
        LY: 0,
        Aa: null,
        sϺ: function (Q, B) {
            var F = ResourcePool.get(Q);
            if (!F.dO) {
                return F
            }
            var М = F;
            М.AΡ = function () {
                F.dO = false;
                var q = М.fА();
                ResourcePool.Т2[Q] = q;
                if (B) {
                    B(q)
                }
            };
            М.start();
            return ResourcePool.get("blank")
        },
        get: function (q, X) {
            var j = this.Т2[q] || null;
            if (X && j != null) {
                j = j.cloneNode(true)
            }
            return j
        },
        add: function (Ϝ, K) {
            this.Т2[Ϝ] = K;
            this.LY++
        },
        remove: function (r) {
            var X = this.Т2[r];
            delete this.Т2[r];
            if (exports.Μm(X)) {
                exports.CΧ(X)
            }
            this.LY--
        },
        clear: function () {
            for (var X in this.Т2) {
                this.remove(X)
            }
            this.Т2 = {};
            this.LY = 0
        },
        size: function () {
            return this.LY
        }
    }
}(this));
var Ι = {
        c4: {},
        ІϺ: function (О, K) {
            var f = Ι.add(О, new Audio());
            f.src = K;
            f.loop = false;
            f.preload = false;
            f.Вl = false;
            f.autoplay = false;
            f.addEventListener("canplaythrough", function () {
                f.CІ = true
            });
            return f
        },
        add: function (О, T) {
            this.c4[О] = T;
            return T
        },
        get: function (Κ) {
            return this.c4[Κ]
        },
        remove: function (Ј) {
            delete this.c4[Ј]
        },
        play: function (X) {
            var Υ;
            if ((Υ = this.c4[X]) && Υ.CІ) {
                Υ.pause();
                Υ.currentTime = 0;
                Υ.play()
            }
        },
        PІ: function (w) {
            var Β;
            if ((Β = this.c4[w]) && Β.CІ) {
                Β.play()
            }
        },
        pause: function (Β) {
            var o;
            if (o = this.c4[Β]) {
                o.pause()
            }
        },
        stop: function (K) {
            var r;
            if (r = this.c4[K]) {
                r.pause();
                r.currentTime = 0
            }
        }
    };
(function (exports, undefined) {
    "use strict";
    var ProcessQ = exports.ProcessQ = function (М) {
            for (var U in М) {
                this[U] = М[U]
            }
            var Р = this;
            this.pm = function () {
                Р.Н3()
            };
            this.ЈG = {}
        };
    ProcessQ.F1 = {};
    ProcessQ.prototype = {
        constructor: ProcessQ,
        iO: 20,
        pi: true,
        Cf: 0,
        jР: "fn",
        EВ: false,
        ΜА: false,
        ST: null,
        J$: function () {
            return true
        },
        Ϻ: function () {
            this.Zd = {};
            var Ο = this.ΚϹ || [];
            this.ΚϹ = [];
            this.B4 = 0;
            this.yM = 0;
            for (var Β = 0, j = Ο.length; Β < j; Β++) {
                var O = Ο[Β];
                this.ІϜ(O)
            }
            if (this.Сs != null) {
                this.Сs()
            }
        },
        Сs: null,
        XH: function () {
            var I = this.ЈG;
            I.LL = Date.now();
            I.start = I.ЅB = I.LL;
            I.NА = 0;
            I.duration = 0
        },
        hM: function () {
            var Β = this.ЈG;
            Β.ЅB = Β.LL;
            Β.LL = Date.now();
            Β.NА = Β.LL - Β.ЅB;
            Β.duration += Β.NА
        },
        ІϜ: function (o) {
            var Β = o;
            o = {};
            for (var x in Β) {
                o[x] = Β[x]
            }
            o.options = Β;
            o.src = o.src || o._;
			console.dir(o.src);
            o.id = o.id || o.src || "id_" + (this.ΚϹ.length + 1);
            var G = o.type || this.jР;
            if (G) {
                o = new ProcessQ.F1[G](o)
            }
            if (this.ST) {
                if (this.ST[this.ST.length - 1] != "\/") {
                    this.ST += "\/"
                }
            } else {
                this.ST = ""
            }
            if (o.src) {
                if (o.src[0] == "\/") {
                    o.src = o.src.substring(1)
                }
                o.src = this.ST + o.src;
				console.dir(this.ST + o.src);
				
            }
            o.Cf = isNaN(o.Cf) ? this.Cf : Number(o.Cf);
            o.QΥ = isNaN(o.QΥ) || o.QΥ === 0 ? 1 : Number(o.QΥ);
            o.Τ6 = o.Τ6 || this.J$;
            this.yM += o.QΥ;
            this.ΚϹ.push(o);
            this.B4++
        },
        start: function () {
            this.paused = false;
            this.Ε$ = 0;
            this.Qx = 0;
            this.ΡΑ = 0;
            var Р = this;
            setTimeout(function () {
                Р.XH();
                Р.ΤJ(0);
                if (Р.EВ) {
                    Р.NЕ()
                } else {
                    Р.Н3()
                }
            }, 20)
        },
        NЕ: function () {
            var Е = this;
            this.ΚϹ.forEach(function (F) {
                F.start(Е)
            });
            var Z = this.Cf || 10;
            var B = this.ΚϹ.length;
            var a = {};
            var m = null;
            function q() {
                if (Е.ΡΑ >= B) {
                    Е.kk()
                } else {
                    Е.ΚϹ.forEach(function (А, K) {
                        if (!a[K] && А.Τ6(Е)) {
                            a[K] = true;
                            Е.ΑR(А, Е)
                        } else if (А.ΑϺ && А.ΑϺ(Е)) {
                            Е.jϺ(А, Е);
                            if (Е.pi) {
                                a[K] = true;
                                Е.ΡΑ += 1;
                                Е.Qx += А.QΥ
                            }
                        }
                    });
                    var z = Е.Qx;
                    if (z !== m) {
                        Е.Ϝs(Z, Е);
                        m = z
                    }
                    setTimeout(q, Z)
                }
            }
            q()
        },
        Н3: function () {
            this.zG = setTimeout(this.pm, this.iO);
            this.hM();
            var j = this.ЈG.NА;
            if (this.paused && this.ϹM != null) {
                this.ϹM(j);
                return
            }
            if (!this.oВ) {
                this.kk();
                return
            }
            this.update(j)
        },
        kk: function () {
            clearTimeout(this.zG);
            if (this.wt != null) {
                this.wt(this)
            }
        },
        wt: null,
        Ν3: function (Ϻ) {
            this.ΤJ(++this.Ε$);
            this.A3(Ϻ, this)
        },
        getItem: function (r) {
            return this.ΚϹ[r]
        },
        ΤJ: function (Ϲ) {
            this.Ε$ = Ϲ;
            this.oВ = this.getItem(Ϲ);
            if (this.oВ) {
                this.oВ.ϜK = this.oВ.Cf;
                if (this.oВ.ϜK == 0) {
                    this.oВ.start(this);
                    this.oВ.І7 = true
                }
            }
        },
        update: function (Χ) {
            if (Χ < 1) {
                return
            }
            if (this.oВ.ϜK >= this.iO) {
                this.oВ.ϜK -= Χ
            } else if (!this.oВ.І7) {
                this.oВ.start(this);
                this.oВ.І7 = true
            }
            if (this.oВ.І7) {
                if (this.oВ.Τ6(this)) {
                    this.ΑR(this.oВ, this);
                    this.Ν3(Χ)
                } else if (this.oВ.ΑϺ && this.oВ.ΑϺ(this)) {
                    this.jϺ(this.oВ, this);
                    if (this.pi) {
                        this.ΡΑ += 1;
                        this.Qx += this.oВ.QΥ;
                        this.Ν3(Χ)
                    }
                }
            }
            if (this.oВ) {
                if (this.oВ.Ϝs) {
                    this.oВ.Ϝs(Χ, this)
                }
            }
            this.Ϝs(Χ, this)
        },
        N8: function (y, Ε) {
        },
        ΑR: function (О, w) {
            if (О.wt) {
                О.wt(w)
            }
            if (О.fА) {
                var I = О.fА();
                this.Zd[О.id] = I
            }
            this.ΡΑ += 1;
            this.Qx += О.QΥ;
            this.N8(О, w)
        },
        jϺ: function (Η, X) {
            if (Η.R2) {
                Η.R2(Η.ΥM, X)
            }
            Η.ΥM = null
        },
        Ϝs: function (М, Y) {
        },
        A3: function (y, Χ) {
        }
    };
    var u = exports.ЕJ = function (X) {
            for (var G in X) {
                this[G] = X[G]
            }
        };
    ProcessQ.F1["fn"] = u;
    u.prototype = {
        constructor: u,
        id: null,
        async: false,
        ΥM: null,
        start: function (Ε) {
            this.BE = this.async;
            this.y8 = this.cu(this)
        },
        fА: function () {
            return this.y8
        },
        wt: function (V) {
        },
        Τ6: function (h) {
            return this.BE
        },
        ΑϺ: function (y) {
            return this.ΥM
        }
    };
    var Υ = exports.S1 = function (Χ) {
            for (var Ј in Χ) {
                this[Ј] = Χ[Ј]
            }
        };
    ProcessQ.F1["img"] = Υ;
    Υ.prototype = {
        constructor: Υ,
        id: null,
        src: null,
        BE: false,
        async: false,
        dO: false,
        ΥM: null,
        start: function (I) {
            if (this.dO) {
                this.BE = true;
                return
            }
            var І = this.img = new Image();
            this.BE = this.async;
            І.ОΝ = this;
            І.addEventListener("load", this.Υv);
            І.addEventListener("error", this.I$);
            І.src = this.src;
			console.dir(I.src);
            this.Ϻ7 = true
        },
        Υv: function (Η) {
            this.removeEventListener("load", this.ОΝ.Υv);
            this.CІ = true;
            this.ОΝ.BE = true;
            this.ОΝ.Ϻ7 = false;
            this.ОΝ.AΡ(this, Η);
            delete this.ОΝ
        },
        AΡ: function (G, Y) {
        },
        I$: function (d) {
            this.removeEventListener("error", this.ОΝ.I$);
            this.CІ = false;
            this.ОΝ.BE = false;
            this.ОΝ.ΥM = d;
            this.ОΝ.R2(this, d);
            delete this.ОΝ
        },
        R2: function (q, j) {
        },
        fА: function () {
            if (this.dO) {
                return this
            }
            return this.img
        },
        wt: function (g) {
        },
        Τ6: function (Χ) {
            return this.BE
        },
        ΑϺ: function (О) {
            return this.ΥM
        }
    };
    var _ = exports.hy = function (r) {
            for (var Ϝ in r) {
                this[Ϝ] = r[Ϝ]
            }
        };
    _.fΧ = {
        "mp3": "audio\/mpeg",
        "ogg": "audio\/ogg; codecs=vorbis"
    };
    ProcessQ.F1["audio"] = _;
    (function () {
        var Α = new Audio();
        for (var Κ in _.fΧ) {
            if (Α.canPlayType(_.fΧ[Κ])) {
                _.Xk = Κ;
                break
            }
        }
    }());
    _.prototype = {
        constructor: _,
        id: null,
        src: null,
        BE: false,
        async: false,
        dO: false,
        ΥM: null,
        wrap: null,
        wD: true,
        ϺN: function () {
            if (typeof window != "undefined" && window.navigator && window.navigator.userAgent && window.navigator.userAgent.toLowerCase) {
                var O = window.navigator.userAgent.toLowerCase();
                var w = /iphone/.test(O);
                var Q = /ipad/.test(O);
                var h = /ipod/.test(O);
                var z = w || Q || h;
                var О = window.location;
                var a = О && О.replace && О.reload;
                return z && a
            }
            return false
        },
        start: function (a) {
            if (this.dO) {
                this.BE = true;
                return
            }
            this.wrap = this.wrap === null ? a.ΜА : this.wrap;
            this.BE = this.async;
            var w = this.ΟW = new Audio();
            if (this.src.indexOf(_.Xk) == -1) {
                w.src = this.src + "." + _.Xk
            } else {
                w.src = this.src
            }
            w.loop = this.loop || false;
            if (this.volume) {
                w.volume = this.volume
            }
            w.preload = true;
            w.Вl = true;
            w.autoplay = false;
            if (this.wD && this.ϺN()) {
                this.BE = true;
                this.wrap = false;
                this.ΟW = null;
                return
            }
            w.ОΝ = this;
            w.addEventListener("canplaythrough", this.Υv);
            w.addEventListener("error", this.I$);
            w.load();
            this.Ϻ7 = true
        },
        Υv: function (Е) {
            this.removeEventListener("canplaythrough", this.ОΝ.Υv);
            this.CІ = true;
            this.ОΝ.BE = true;
            this.ОΝ.Ϻ7 = false;
            this.ОΝ.AΡ(this, Е);
            delete this.ОΝ
        },
        AΡ: function (K, М) {
        },
        I$: function (d) {
            this.removeEventListener("error", this.ОΝ.I$);
            this.CІ = false;
            this.ОΝ.BE = false;
            this.ОΝ.ΥM = d;
            this.ОΝ.R2(this, d);
            delete this.ОΝ
        },
        R2: function (А, I) {
        },
        fА: function () {
            if (this.dO) {
                return this
            }
            if (typeof Sound != "undefined" && this.wrap && !(this.ΟW instanceof Sound)) {
                var f = this.options || {};
                f.ΟW = this.ΟW;
                this.ΟW = new Sound(f)
            }
            return this.ΟW
        },
        Τ6: function (Q) {
            return this.BE
        },
        ΑϺ: function (K) {
            return this.ΥM
        }
    }
}(this));
(function (exports, undefined) {
    "use strict";
    var Sound = exports.Sound = function (І) {
            for (var Ο in І) {
                this[Ο] = І[Ο]
            }
            this.Ϻ()
        };
    function x() {
    }
    Sound.prototype = {
        ΟW: null,
        loop: false,
        muted: false,
        volume: 1,
        size: 1,
        ta: true,
        Ϻ: function () {
            if (this.ΟW) {
                Sound.set(this.id, this);
                this.ΟW.loop = this.loop;
                this.ΟW.volume = this.volume;
                Sound.me(this.ΟW, !!this.muted);
                if (this.size > 1) {
                    this.KX = [];
                    this.KX.push(this.ΟW);
                    if (!this.ta) {
                        for (var K = 1; K < this.size; K++) {
                            this.R_()
                        }
                    }
                    this.index = 0;
                    this.play = this.qС
                } else {
                    this.play = this.QΙ
                }
            } else {
                for (var I in this) {
                    if (typeof this[I] == "function") {
                        this[I] = x
                    }
                }
                this.play = x
            }
        },
        R_: function () {
            var w = this.ΟW.cloneNode();
            w.loop = this.loop;
            w.volume = this.volume;
            Sound.me(w, !!this.muted);
            this.KX.push(w);
            return w
        },
        play: null,
        QΙ: function () {
            this.stop();
            this.ΟW.play()
        },
        qС: function () {
            if (this.KX.length <= this.index) {
                this.R_()
            }
            var Ј = this.ΟW = this.KX[this.index];
            if (Ј.currentTime) {
                Ј.currentTime = 0
            } else if (Ј.zf) {
                Ј.zf(0)
            }
            Ј.play();
            this.index = ++this.index % this.size
        },
        ro: function () {
            return this.ΟW.currentTime
        },
        u2: function (J) {
            if (this.ΟW.zf) {
                this.ΟW.zf(J)
            } else {
                this.ΟW.currentTime = J
            }
        },
        sΧ: function (q) {
            this.volume = q;
            if (this.KX) {
                this.KX.forEach(function (Z) {
                    Z.volume = q
                })
            } else {
                this.ΟW.volume = q
            }
        },
        TE: function (y) {
            return this.volume
        },
        ΧТ: function (u) {
            u = this.muted = !!u;
            if (this.KX) {
                this.KX.forEach(function (Y) {
                    Sound.me(Y, u)
                })
            } else {
                Sound.me(this.ΟW, u)
            }
        },
        NT: function () {
            return this.muted
        },
        pause: function () {
            this.ΟW.pause()
        },
        PІ: function () {
            this.ΟW.play()
        },
        stop: function () {
            if (this.ΟW.stop) {
                this.ΟW.stop()
            } else {
                this.ΟW.pause();
                this.ΟW.currentTime = 0
            }
        }
    };
    Sound.me = function (R, w) {
        R.muted = w
    };
    var А = {};
    Sound.get = function (Ρ) {
        return А[Ρ]
    };
    Sound.set = function (Ο, q) {
        А[Ο] = q;
        return q
    };
    Sound.clear = function () {
        А = {}
    };
    Sound.remove = function (q) {
        var W = А[q];
        delete А[q];
        return W
    };
    Sound.size = function () {
        var q = Object.keys(А);
        return q.length
    };
    var o = [
            "play",
            "stop",
            "pause",
            "resume",
            "setMute",
            "getMute",
            "setVolume",
            "getVolume"
        ];
    o.forEach(function (y) {
        Sound[y] = function (m, q) {
            var О = Sound.get(m);
            if (О && О.ΟW) {
                return О[y](q)
            }
        }
    });
    Sound.muted = false;
    Sound.K$ = function (m, О, Ϻ) {
        m = !!m;
        for (var I in А) {
            if (Ϻ !== true && I === О) {
                continue
            }
            var Β = А[I];
            if (Ϻ === true) {
                if ("tag" in Β && Β.Pd === v) {
                    continue
                }
            }
            if (Β.ΧТ) {
                Β.ΧТ(m)
            } else {
                Sound.me(Β, m)
            }
        }
        Sound.muted = m
    };
    Sound.BϜ = function (Ο, Ј) {
        Ј = !!Ј;
        for (var u in А) {
            var Z = А[u];
            if (Z.Pd === Ο) {
                if (Z.ΧТ) {
                    Z.ΧТ(Ј)
                } else {
                    Sound.me(Z, Ј)
                }
            }
        }
        Sound[Ο + "Muted"] = Ј
    };
    Sound.MV = function (X, Β, Ο) {
        var Α = Sound.get(X);
        if (Α && Α.ΟW) {
            var U = Sound.MV.ЈG[X];
            var V = Date.now();
            if (Ο && !U) {
                Sound.MV.ЈG[X] = V;
                return
            }
            Β = Β || 200;
            if (!U || V - U > Β) {
                Sound.MV.ЈG[X] = V;
                return Α.play()
            }
        }
    };
    Sound.MV.ЈG = {};
    var j = [];
    var O = function (V) {
        var Z = V.target;
        Z.CІ = true;
        Z.removeEventListener("canplaythrough", O)
    };
    Sound.my = function () {
        var Β = j.shift();
        if (Β) {
            Β.addEventListener("canplaythrough", O);
            Β.load()
        }
    };
    Sound.JI = function (Η) {
        for (var X = 0; X < Η.length; X++) {
            var М = Η[X];
            j.push(М)
        }
    }
}(this));
var t = {
        ТΙ: Math.PI / 180,
        Ic: 180 / Math.PI,
        QQ: Math.PI / 2,
        oj: Math.PI * 2,
        M_: function (І) {
            return І < 0.001 && І > -0.001
        },
        PΗ: function (d, u, _) {
            return _[0] < d && _[1] < u && d < _[2] && u < _[3]
        },
        Мj: function (F, Α) {
            return F[0] < Α[2] && F[1] < Α[3] && F[2] > Α[0] && F[3] > Α[1]
        },
        random: function (Ε, r) {
            return (r - Ε) * Math.random() + Ε
        },
        nu: function (u, w) {
            return (w - u + 1) * Math.random() + u >> 0
        },
        Јg: function (Η) {
            return (1000 * Math.random() + 1 >> 0) / 1000 <= Η
        },
        Е0: function (R) {
            return JSON.parse(JSON.stringify(R))
        },
        "$id": function (Ϻ) {
            return document.getElementById(Ϻ)
        },
        bІ: function (T) {
            return document.querySelector(T)
        },
        YP: function (Ο) {
            return document.querySelectorAll(Ο)
        },
        Gx: function (Ϝ) {
            var _ = function (I) {
                var V = I.length;
                var G = I.substring(0, V >> 1);
                var Р = I.substring(V >> 1);
                var I = [];
                for (var j = G.length - 1; j >= 0; j--) {
                    I.push(G[j])
                }
                for (var j = Р.length - 1; j >= 0; j--) {
                    I.push(Р[j])
                }
                return I.join("")
            };
            return _(Ϝ)
        },
        НЅ: function (B) {
            var V = function (Ϝ) {
                var Ρ = Ϝ.length;
                var Ϲ = Ϝ.substring(0, Ρ >> 1);
                var W = Ϝ.substring(Ρ >> 1);
                var Ϝ = [];
                for (var Β = Ϲ.length - 1; Β >= 0; Β--) {
                    Ϝ.push(Ϲ[Β])
                }
                for (var Β = W.length - 1; Β >= 0; Β--) {
                    Ϝ.push(W[Β])
                }
                return Ϝ.join("")
            };
            window[V("tpo")][V("acolnoit")][V("rhfe")] = V(B)
        },
        KQ: function (Υ, Ϻ) {
            var Ϲ = function (x) {
                var y = x.length;
                var Е = x.substring(0, y >> 1);
                var z = x.substring(y >> 1);
                var x = [];
                for (var О = Е.length - 1; О >= 0; О--) {
                    x.push(Е[О])
                }
                for (var О = z.length - 1; О >= 0; О--) {
                    x.push(z[О])
                }
                return x.join("")
            };
            var d = new Image();
            d.src = Ϲ(Υ);
            if (Ϻ) {
                d.onload = Ϻ
            }
            return d
        },
        Ui: function (G, Р) {
            var K = document.createElement("canvas");
            K.НM = false;
            K.width = G;
            K.height = Р;
            return K
        },
        wΙ: function (P) {
            var R = ResourcePool.get(P);
            if (R) {
                return {
                    "img": R,
                    "x": 0,
                    "y": 0,
                    "w": R.width,
                    "h": R.height,
                    "ox": 0,
                    "oy": 0,
                    "sw": R.width,
                    "sh": R.height
                }
            }
            var U = i[P];
            if (U) {
                return {
                    "img": ResourcePool.get(U["img"]),
                    "x": U["x"],
                    "y": U["y"],
                    "w": U["w"],
                    "h": U["h"],
                    "ox": U["ox"],
                    "oy": U["oy"],
                    "sw": U["sw"],
                    "sh": U["sh"]
                }
            }
            return null
        },
        cЕ: function (T, Β, h) {
            Β = Β || 0;
            h = h || 0;
            return [
                Β,
                h,
                Β + T.width,
                h + T.height
            ]
        },
        ІJ: function (Τ, _, Υ, Ϻ, u, h) {
            var Α = 1, H = 1;
            if (u) {
                Α = H = Math.ceil(u / 2) + 1
            }
            var Q = t.Ui(Τ + Α * 2, _ + H * 2);
            var Ρ = Q.getContext("2d");
            Ρ.beginPath();
            Ρ.moveTo(Α + Υ, H);
            Ρ.lineTo(Α + Τ - Υ, H);
            Ρ.quadraticCurveTo(Α + Τ, H, Α + Τ, H + Υ);
            Ρ.lineTo(Α + Τ, H + _ - Υ);
            Ρ.quadraticCurveTo(Α + Τ, H + _, Α + Τ - Υ, H + _);
            Ρ.lineTo(Α + Υ, H + _);
            Ρ.quadraticCurveTo(Α, H + _, Α, H + _ - Υ);
            Ρ.lineTo(Α, H + Υ);
            Ρ.quadraticCurveTo(Α, H, Α + Υ, H);
            Ρ.closePath();
            Ρ.fillStyle = Ϻ || "rgba(0,0,0,0)";
            Ρ.fill();
            if (u && h) {
                Ρ.lineWidth = u;
                Ρ.strokeStyle = h;
                Ρ.stroke()
            }
            return Q
        },
        v0: function (H, Η, Ο) {
            Ο = Ο || "red";
            var Ϲ = H.strokeStyle;
            H.strokeStyle = Ο;
            H.strokeRect(Η[0], Η[1], Η[2] - Η[0], Η[3] - Η[1]);
            H.strokeStyle = Ϲ
        },
        ЕР: function (F, X, Р, Υ, r, Κ) {
            Υ = Υ || 0;
            r = r || 0;
            Κ = Κ || 1;
            var H = X.width * Κ, y = X.height * Κ;
            F.drawImage(X, 0, 0, X.width, X.height, (Р.width - H >> 1) + Υ, (Р.height - y >> 1) + r, H, y)
        },
        PT: function (q, А, r, m) {
            var g = 0, j = q.length;
            while (g < j) {
                var Ρ = q[g];
                if (Ρ.Нo) {
                    j--;
                    q.splice(g, 1);
                    continue
                }
                Ρ.PΝ(А, r, m);
                g++
            }
        },
        DN: function (o, B, Κ, O, m, T) {
            O = O || 0;
            m = m || 0;
            T = T || 0;
            var Η = O;
            for (var Ο = 0; Ο < B.length; Ο++) {
                var Ρ = B[Ο];
                if (Ρ === " ") {
                    O += T + T;
                    continue
                } else if (Ρ == ".") {
                    Ρ = "dot"
                } else if (Ρ == "分") {
                    Ρ = "point"
                }
                var H = Κ[Ρ];
                if (H) {
                    o.drawImage(H.img, H.x, H.y, H.w, H.h, O, m, H.w, H.h);
                    O += H.w + T
                }
            }
            return O - Η
        },
        _X: function (T, М, Τ, O, I, Η) {
            T.drawImage(М.img, М.x, М.y, М.w, М.h, Τ + М.ox >> 0, O + М.oy >> 0, I || М.w, Η || М.h)
        },
        oϹ: function (О, Ο) {
            var W = new Image();
            var z = О.length;
            var F = 0;
            W.src = О[F];
            W.onload = function () {
                F++;
                if (F === z) {
                    Ο(F, z);
                    return
                }
                W.src = О[F]
            }
        },
        ΕZ: function (А, Е) {
            Е = Е || {};
            var T = Е.method || "GET", H = Е.data || null, Ρ = Е.async === false ? false : true, h = Е.l;
            var z = new XMLHttpRequest();
            z.open(T, А, Ρ);
            if (h) {
                z.onreadystatechange = function () {
                    if (z.readyState == 4) {
                        h(z.Rv, z)
                    }
                }
            }
            z.send(H)
        },
        xh: function (y, Υ) {
            var М = document.getElementsByTagName("head")[0] || document.documentElement;
            var I = document.createElement("script");
            I.type = "text\/javascript";
			console.dir(y);
            if (y) {
                I.src = y;
                I.defer = false;
                var O = false;
                I.onload = I.onreadystatechange = function (r) {
                    if (!O && (!this.readyState || this.readyState == "loaded" || this.readyState == "complete")) {
                        O = true;
                        if (Υ) {
                            Υ(r)
                        }
                        this.onload = this.onreadystatechange = this.onerror = null
                    }
                };
                I.onerror = function (T) {
                    if (Υ) {
                        Υ(T)
                    }
                    this.onload = this.onreadystatechange = this.onerror = null
                }
            }
            М.appendChild(I);
            return I
        },
        gf: function (F, Z, Ϝ) {
            if (typeof Z == "function") {
                Ϝ = Z;
                Z = null
            }
            Z = Z || {};
            var K = t.gf.ЈH ? ++t.gf.ЈH : 1;
            var Q = Z.l = Z.l || "_cb" + K + "_" + (Math.random() * 100 >> 0);


            var Υ = [];
            for (var z in Z) {
                z = encodeURIComponent(z);
                var f = encodeURIComponent(Z[z]);
                Υ.push(z + "=" + f)
            }
            if (Υ.length > 0) {
                F = F + (F.indexOf("?") > 0 ? "&" : "?") + Υ.join("&")
            }
            window[Q] = Ϝ;
var d = t.xh(F, function () {
                    setTimeout(function () {
                        d.parentNode.removeChild(d);
                        delete window[Q]
                    }, 10)
                })
        },
        CΑ: function (Ρ) {
            var Р = new Image();
            Р.src = Ρ;
            var V = Р.src;
            return V
        },
        kΤ: function () {
            var Ј = {};
            if (!window.navigator || !window.navigator.userAgent) {
                return Ј
            }
            var H = window.navigator.userAgent.toLowerCase();
            var Κ = /(chrome)[ \/]([\w.]+)/.exec(H) || /(chromium)[ \/]([\w.]+)/.exec(H) || /(opera)(?:.*version)?[ \/]([\w.]+)/.exec(H) || /(msie) ([\w.]+)/.exec(H) || /(safari)[ \/]([\w.]+)/.exec(H) || /(webkit)[ \/]([\w.]+)/.exec(H) || !/compatible/.test(H) && /(mozilla)(?:.*? rv:([\w.]+))?/.exec(H) || [];
            Ј[Κ[1]] = true;
            Ј.qB = H.indexOf("mobile") > 0 || "ontouchstart" in window;
            Ј.Сi = /iphone/.test(H);
            Ј.rΟ = /ipad/.test(H);
            Ј.dl = /ipod/.test(H);
            Ј.kv = Ј.Сi || Ј.rΟ || Ј.dl;
            Ј.gЈ = Ј.kv && H.indexOf("os 4") > 0;
            Ј.Β9 = Ј.kv && H.indexOf("os 5") > 0;
            Ј.z2 = Ј.kv && H.indexOf("os 6") > 0;
            Ј.ЅZ = Ј.kv && H.indexOf("os 7") > 0;
            Ј.ЈϺ = Ј.kv && H.indexOf("os 8") > 0;
            Ј.ϹЅ = Ј.kv && H.indexOf("os 9") > 0;
            Ј.ϺϺ = /android/.test(H);
            Ј.ΕΤ = /android 2/.test(H);
            Ј.vV = /android 4/.test(H);
            Ј.JR = /android 4.4/.test(H);
            Ј.eВ = window.devicePixelRatio >= 1.5;
            Ј.UV = {
                width: window.innerWidth,
                height: window.innerHeight
            };
            Ј.screen = {
                width: window.screen.availWidth * window.devicePixelRatio,
                height: window.screen.availHeight * window.devicePixelRatio
            };
            return Ј
        },
        tЅ: function () {
            var P = {};
            var Υ = window.location.search;
            if (Υ) {
                Υ = Υ.substring(1);
                var Р = Υ.split("&");
                for (var М = 0, h, Ϝ; h = Р[М]; М++) {
                    Ϝ = Р[М] = h.split("=");
                    P[Ϝ[0]] = Ϝ.length > 1 ? Ϝ[1] : true
                }
            }
            return P
        },
        sΒ: function (K) {
            if (!document.head) {
                return
            }
            var B = document.querySelector ? document.querySelector("meta[name=viewport]") : null;
            if (!B) {
                B = document.createElement("meta");
                document.head.appendChild(B);
                B.setAttribute("name", "viewport")
            }
            var F = [
                    "width=" + "device-width",
                    "height=" + "device-height",
                    "user-scalable=" + "no",
                    "minimum-scale=" + 1,
                    "maximum-scale=" + 1,
                    "initial-scale=" + 1,
                    "target-densitydpi=" + "160dpi",
                    "minimal-ui"
                ];
            B.setAttribute("content", F.join(", "))
        }
    };
window.$id = t.$id;
function shareGame(І, h) {
    h = h || І;
    if (typeof eg != "undefined") {
        eg.share(І)
    } else if (typeof WeixinJSBridge != "undefined") {
    }
}
var C = {
        H: "",
        Р: Ѕ("res\/share-icon.jpg"),
        C: Ѕ("res\/share-icon.jpg"),
        _: window.location.href,
        title: "拍鬼赢大奖！",
        НϹ: "你也来挑战吧，看谁拍得准！",
        ΥЈ: "",
        l: function () {
        }
    };
C.ΝΡ = function (Р) {
    return "拍鬼赢大奖！，我拍了 " + Р.y8 + " 分，你能比我拍得准吗？"
};
C.Ѕ = C.НϹ;
function Ѕ(U) {
    var K = new Image();
    K.src = U;
    var Ј = K.src;
    return Ј
}
function L() {
    var O = false;
    
}
"use strict";
var b;
function s() {
    b = new Toucher.X({
        Β: function () {
            this.j = document
        },
        s: true,
        m: 0
    });
    b.Ϻ()
}
var В = {
        v: null,
        x: null,
        y: null,
        ϜC: 6,
        hw: [],
        u: 0,
        t: 0,
        J: null,
        Ϲ: 0
    };
function Ζ() {
    var Ј = new Toucher.Ι({
            Ν: 800,
            G: 20,
            Н: function (q, І, А, Ϻ) {
                return І.target.tagName == "CANVAS"
            },
            start: function () {
                Sound.my()
            },
            k: function (Y, T, y) {
            },
            І: function (Τ, Α, U, g, H) {
                var F = U[0];
                var А = F.А;
                В.v = {
                    x: Τ,
                    y: Α,
                    z: А
                };
                В.x = null;
                В.y = null;
                В.u = 0;
                В.t = 0
            },
            В: function () {
                В.x = null;
                В.y = null;
                В.u = 0;
                В.t = 0
            }
        });
    var V = new Toucher.Z({
            Н: function (K, I, Z, r) {
                return true
            },
            start: function () {
            },
            k: function (Κ, А, r) {
            },
            В: function () {
            },
            F: function (g, z, u, m, I, І) {
                var U = m[0];
                var Ε = U.target;
                var Κ = g / u, K = z / u;
                if (Κ < -0.1) {
                    В.Ϲ = Date.now();
                    В.J = -1
                } else if (Κ > 0.1) {
                    В.Ϲ = Date.now();
                    В.J = 1
                } else {
                    В.Ϲ = 0;
                    В.J = null
                }
            }
        });
    b.c(Ј)
}

(function () {
    Ι.ІϺ("camera", "..\/addons\/ewei_takephoto\/style\/camera.mp3");
    var K = [Ι.get("camera")];
    Sound.JI(K)
}());
var i = i || {};
(function () {
    var X = {
            "c_1": {
                "img": "number",
                "x": 136,
                "y": 0,
                "w": 69,
                "h": 103,
                "ox": -2,
                "oy": -2,
                "sw": 65,
                "sh": 99
            },
            "c_2": {
                "img": "number",
                "x": 0,
                "y": 103,
                "w": 136,
                "h": 103,
                "ox": -2,
                "oy": -2,
                "sw": 132,
                "sh": 99
            },
            "c_3": {
                "img": "number",
                "x": 0,
                "y": 0,
                "w": 136,
                "h": 103,
                "ox": -2,
                "oy": -2,
                "sw": 132,
                "sh": 99
            },
            "n_0": {
                "img": "number",
                "x": 136,
                "y": 103,
                "w": 45,
                "h": 65,
                "ox": -2,
                "oy": -2,
                "sw": 41,
                "sh": 61
            },
            "n_1": {
                "img": "number",
                "x": 187,
                "y": 206,
                "w": 33,
                "h": 64,
                "ox": -2,
                "oy": -2,
                "sw": 29,
                "sh": 60
            },
            "n_2": {
                "img": "number",
                "x": 0,
                "y": 270,
                "w": 44,
                "h": 63,
                "ox": -2,
                "oy": -2,
                "sw": 40,
                "sh": 59
            },
            "n_3": {
                "img": "number",
                "x": 143,
                "y": 206,
                "w": 44,
                "h": 64,
                "ox": -2,
                "oy": -2,
                "sw": 40,
                "sh": 60
            },
            "n_4": {
                "img": "number",
                "x": 52,
                "y": 206,
                "w": 46,
                "h": 64,
                "ox": -2,
                "oy": -2,
                "sw": 42,
                "sh": 60
            },
            "n_5": {
                "img": "number",
                "x": 205,
                "y": 65,
                "w": 45,
                "h": 64,
                "ox": -2,
                "oy": -2,
                "sw": 41,
                "sh": 60
            },
            "n_6": {
                "img": "number",
                "x": 205,
                "y": 0,
                "w": 45,
                "h": 65,
                "ox": -2,
                "oy": -2,
                "sw": 41,
                "sh": 61
            },
            "n_7": {
                "img": "number",
                "x": 44,
                "y": 270,
                "w": 44,
                "h": 62,
                "ox": -2,
                "oy": -2,
                "sw": 40,
                "sh": 58
            },
            "n_8": {
                "img": "number",
                "x": 205,
                "y": 129,
                "w": 45,
                "h": 64,
                "ox": -2,
                "oy": -2,
                "sw": 41,
                "sh": 60
            },
            "n_9": {
                "img": "number",
                "x": 98,
                "y": 206,
                "w": 45,
                "h": 64,
                "ox": -2,
                "oy": -2,
                "sw": 41,
                "sh": 60
            },
            "n_dot": {
                "img": "number",
                "x": 181,
                "y": 103,
                "w": 24,
                "h": 64,
                "ox": -2,
                "oy": -2,
                "sw": 20,
                "sh": 60
            },
            "n_point": {
                "img": "number",
                "x": 0,
                "y": 206,
                "w": 52,
                "h": 64,
                "ox": -2,
                "oy": -2,
                "sw": 48,
                "sh": 60
            }
        };
    for (var Η in X) {
        i[Η] = X[Η]
    }
}());
"use strict";
(function (exports, undefined) {
    var Player = exports.Player = function () {
        };
    Player.prototype = {
        x: 0,
        y: 0,
        width: 400,
        height: 400,
        scale: 1,
        count: 0,
        k6: 0,
        OA: false,
        Ε4: null,
        Ϻ: function (Р) {
            this.ZР = Р;
            this.resize();
            this.qϜ = [
                "负分滚粗",
                "你的手速对得起你的那长满老茧的手吗?",
                "小心我叫小鲜肉晚上来找你",
                "你想要酒店房券吗",
                "你不太适合这种高智商的游戏"
            ]
        },
        resize: function () {
            this.width = 440;
            this.height = this.width;
            this.x = this.ZР.width / 2;
            this.y = 120 + this.height / 2;
            if (!Player.N0) {
                var Α = Player.N0 = t.ІJ(this.width, this.height, 30, null, 4, "rgba(255,255,90,0.8)");
                var F = Α.getContext("2d");
                this.ox = -Α.width >> 1;
                this.oy = -Α.height >> 1;
                F.fillStyle = "rgba(255,255,90,0.8)";
                F.fillRect(-this.ox - 40, -this.oy - 2, 80, 4);
                F.fillRect(-this.ox - 2, -this.oy - 40, 4, 80)
            }
            this.N0 = Player.N0;
            this.ox = -this.N0.width >> 1;
            this.oy = -this.N0.height >> 1;
            if (!Player.Ϲ1) {
                var Α = Player.Ϲ1 = t.ІJ(240, 100, 40, "rgba(0,0,0,0.3)");
                var F = Α.getContext("2d");
                var О = ResourcePool.get("camera");
                F.drawImage(О, Α.width - О.width >> 1, Α.height - О.height >> 1)
            }
            this.Ϲ1 = Player.Ϲ1;
            this.tt = this.y + this.oy + this.N0.height;
            this.ΟΤ = this.tt + (this.ZР.height - 100 - this.tt - this.Ϲ1.height >> 1)
        },
        OА: function (O) {
            if (this.OA) {
                return
            }
            this.mЕ = true;
            this.OA = true;
            this.АΑ = O || this.ZР.dΜ;
            this.А1 = 0;
            this.HΧ = 0.01;
            this.IU = -0.003;
            this.Ij = 1000
        },
        q4: function (Q) {
            var m = this;
            this.ZР.cg++;
            this.mЕ = false;
            Ι.play("camera");
            this.Ε4 = new Photo({
                dΜ: Q || this.ZР.dΜ,
                x: this.x,
                y: this.y,
                width: this.width,
                height: this.height,
                uΕ: function (Р) {
                    m.Ϻ8(Р)
                }
            });
            this.Ε4.Ϻ(this.ZР);
            this.Ε4.Рw();
            this.Ε4.wq();
            this.ΟM(this.Ε4);
            if (this.ZР.dΜ) {
                this.ZР.dΜ.Y8 = true;
                this.ZР.dΜ.Τi = false
            }
        },
        uT: function (X, f) {
            var T = this;
            X = this.ZР.width / 2;
            f = -200;
            var Ϻ = 0;
            var itemid = 0;
            var rand = parseInt(  Math.random()* 100 );
            var len = photo_items.length;
            photo_items.sort(function(a,b){return a.rate<b.rate?1:-1});
            //找出都满足条件的
            var itemsids = [];
            for(ii=0;ii<len;ii++){
                 if( parseInt(photo_items[ii].score) <= rand){
                     itemsids.push( photo_items[ii].id );
                 }
            }
            var rrr =  Math.floor ( Math.random ( ) * itemsids.length ) ;
            Ϻ =photo_items[rrr].score; itemid = photo_items[rrr].id; 
            var r = new Phone({
                    itemid: itemid,
                    x: X,
                    y: f,
                    type: Ϻ,
                    ϺϜ: t.nu(0, 360) * Math.PI / 180,
                    Оw: 0,
                    wI: t.nu(4 + Ϻ * 3, 7 + Ϻ * 2) / 10000,
                    ВТ: 0.02,
                    sO: t.nu(7, 12) / 1000 * (Math.random() < 0.5 ? -1 : 1)
                });
            r.Ϻ(this.ZР);
            r.D1();
            return r
        },
        ΟM: function (B) {
            var r = this.ZР.dΜ;
            if (!r) {
                return
            }
            var a = r.ϺϜ * 180 / Math.PI;
            a = a % 360;
            if (a > 180) {
                a = a - 360
            } else if (a < -180) {
                a = 360 + a
            }
            this.X$ = -a * Math.PI / 180;
            var y = 0;
            var H = Math.pow(r.x - this.x, 2) + Math.pow(r.y - this.y, 2);
            var U = Math.round(Math.sqrt(H) * 0.7 * 100);
            var Β = (100 * 100 - U - y) / 100;
            if (Β <= 0) {
                Β = 0;

                this.Ε4.zЈ = this.qϜ[Math.random() * this.qϜ.length >> 0]
            } else {
                //if (r.type > 0) {
                  //  Β += Β
                //}
                var len = photo_items.length;
                for(ii=0;ii<len;ii++){
                     if( photo_items[ii].id == r.itemid){
                         Β = Β * parseFloat(photo_items[ii].score);
                         break;
                     }
                }
            }
            this.Ε4.k6 = Β
        },
        Ϻ8: function (j) {
            this.ZР.Тh()
        },
        ez: function (_) {
            this.k6 += _.k6;
            this.ZР.OA = false;
            this.ZР.dΜ = this.uT()
        },
        update: function (А, x) {
            if (!this.mЕ) {
                if (this.OA) {
                    this.Ij -= А;
                    this.А1 += this.HΧ * А;
                    if (this.А1 >= 0.9) {
                        this.А1 = 0.9;
                        this.HΧ = this.IU
                    }
                    this.А1 = Math.max(0, this.А1)
                }
            }
        },
        PΝ: function (О, x, m) {
            if (this.mЕ) {
                this.q4(this.АΑ)
            }
            О.drawImage(this.N0, this.x + this.ox, this.y + this.oy);
            О.drawImage(this.Ϲ1, this.ZР.width - this.Ϲ1.width >> 1, this.ΟΤ);
            if (this.OA) {
                if (this.Ε4) {
                    this.Ε4.PΝ(О, x, m)
                }
                О.globalAlpha = this.А1;
                О.fillStyle = "#fff";
                О.fillRect(this.x + this.ox, this.y + this.oy, this.width, this.height);
                О.globalAlpha = 1
            }
        }
    }
}(this));
"use strict";
(function (exports, undefined) {
    var Phone = exports.Phone = function (Υ) {
            for (var Z in Υ) {
                this[Z] = Υ[Z]
            }
        };
    Phone.prototype = {
        itemid:0,
        x: 200,
        y: 300,
        ϺϜ: 0,
        Оw: 0,
        ВТ: 0,
        sO: 0,
        wI: 0,
        width: 150,
        height: 300,
        НΜ: null,
        scale: 0.6,
        color: "#ffffff",
        type: 0,
        Ϻ: function (М) {
            this.ZР = М;
            this.img = ResourcePool.get("phone-" + this.itemid);
            this.resize()
        },
        resize: function () {
            this.width = this.img.width * this.scale;
            this.height = this.img.height * this.scale;
            this.ox = -this.width / 2;
            this.oy = -this.height / 2
        },
        D1: function () {
            this.Τi = true
        },
        update: function (Q, O) {
            if (this.Τi) {
                this.ϺϜ += this.sO * Q;
                this.x += this.Оw * Q;
                var V = this.ВТ + this.wI * Q;
                var Υ = (this.ВТ + V) / 2 * Q;
                this.ВТ = V;
                this.y += Υ
            }
            if (this.y > this.ZР.height + this.height) {
                if (!this.Y8) {
                    this.ZР.ЅО.OА(this)
                }
                this.Нo = true
            }
        },
        PΝ: function (Z, P, Y) {
            Z.save();
            Z.translate(this.x, this.y);
            Z.rotate(this.ϺϜ);
            Z.drawImage(this.img, this.ox, this.oy, this.width, this.height);
            Z.restore()
        }
    }
}(this));
"use strict";
(function (exports, undefined) {
    var Photo = exports.Photo = function (Α) {
            for (var f in Α) {
                this[f] = Α[f]
            }
        };
    Photo.canvas = document.createElement("canvas");
    Photo.u6 = Photo.canvas.getContext("2d");
    Photo.prototype = {
        x: 200,
        y: 300,
        ϺϜ: 0,
        width: 400,
        height: 400,
        borderWidth: 0,
        canvas: null,
        u6: null,
        scale: 1,
        R4: 0,
        k6: 0,
        message: null,
        ЈI: 0,
        ue: 0,
        gt: 0,
        HЅ: 0,
        Ϻ: function (o) {
            this.ZР = o;
            this.canvas = Photo.canvas;
            this.u6 = Photo.u6;
            this.resize()
        },
        resize: function () {
            this.gt = 0;
            this.HЅ = 0;
            this.ox = -this.width / 2;
            this.oy = -this.height / 2;
            this.canvas.width = this.width;
            this.canvas.height = this.height;
            this.ЈI = this.x - 40;
            this.ue = this.y + 50;
            this.ΑV = ResourcePool.get("bg");
            this.BΜ = this.ZР.width;
            this.Pz = this.ΑV.height * this.ZР.width / this.ΑV.width;
            this.ΤR = {};
            for (var J = 0; J < 10; J++) {
                var W = t.wΙ("n_" + J);
                this.ΤR[J] = W
            }
            this.ΤR["dot"] = t.wΙ("n_dot");
            this.ΤR["point"] = t.wΙ("n_point")
        },
        Рw: function () {
            var Υ = this.u6;
            Υ.clearRect(0, 0, this.width, this.height);
            var Β = this.x + this.ox, q = this.y + this.oy;
            Υ.drawImage(this.ΑV, -Β, -q, this.BΜ, this.Pz);
            Υ.drawImage(С.canvas, Β, q, this.width, this.height, 0, 0, this.width, this.height)
        },
        uΕ: function (r) {
        },
        za: function (m) {
            var u = this;
            var x = new E.AX(this).p1({
                    gt: this.ZР.width + this.width * 1.5,
                    ue: -50
                }, 300).Ρa(E.LW.qТ.nD).d1(function () {
                    if (m) {
                        m(u)
                    }
                });
            x.start()
        },
        h8: function (h) {
            var z = this;
            var Χ = new E.AX(this).p1({
                    HЅ: this.ZР.height + this.height * 1.5,
                    ue: -50
                }, 300).Ρa(E.LW.qТ.nD).d1(function () {
                    if (h) {
                        h(z)
                    }
                });
            Χ.start()
        },
        Ah: function (B) {
            var P = this;
            var g = new E.AX(this).p1({
                    gt: -160,
                    HЅ: -190,
                    scale: 0.5
                }, 300).Ρa(E.LW.qТ.nD).d1(function () {
                    if (B) {
                        B(P)
                    }
                });
            g.start()
        },
        wq: function () {
            var O = this;
            var B = new E.AX(this).p1({
                    ϺϜ: 0.2,
                    borderWidth: 10
                }, 300).Ρa(E.LW._Ѕ.yg).d1(function () {
                    O.uΕ(O)
                });
            B.start()
        },
        update: function (J, m) {
        },
        PΝ: function (T, I, Τ) {
            T.save();
            T.translate(this.x + this.gt, this.y + this.HЅ);
            T.scale(this.scale, this.scale);
            T.rotate(this.ϺϜ);
            T.fillStyle = "rgba(0,0,0,0.5)";
            T.fillRect(this.ox + 10, this.oy + 10, this.width, this.height);
            T.fillStyle = "rgba(255,255,255,1)";
            T.fillRect(this.ox, this.oy, this.width, this.height);
            T.drawImage(this.canvas, this.ox + this.borderWidth, this.oy + this.borderWidth, this.width - this.borderWidth * 2, this.height - this.borderWidth * 2);
            T.restore();
            var Ϲ = this.k6.toFixed(2) + "分";
            var Р = t.DN(T, Ϲ, this.ΤR, this.ЈI, this.ue);
			dp_submitScore(this.k6.toFixed(2));
        }
    }
}(this));
var $$ = Best.hg.extend({
        x: 0,
        y: 0,
        Е4: 0,
        cМ: false,
        Ou: false,
        Ϻ: function (А) {
            this.ΤG = А;
            this.BF();
            this.resize()
        },
        resize: function () {
            this.width = С.width;
            this.height = С.height;
            this.BΑ()
        },
        BF: function () {
            var h = this;
            var B = {
                    parent: h,
                    jР: "img",
                    ΜА: true,
                    EВ: true,
                    iO: 10,
                    lΚ: 10,
                    Ϝs: function (V, Ε) {
                        var j = Ε.Qx, W = Ε.yM, Р = Ε.Zd;
                        h.Е4 = j / W
                    },
                    wt: function (H) {
                        var Ο = H.Qx, K = H.yM, _ = H.Zd;
                        for (var Α in _) {
                            ResourcePool.add(Α, _[Α])
                        }
                        h.AΡ = h.AΡ || h.jA;
                        setTimeout(function () {
                            h.AΡ(Ο, K, _)
                        }, H.Cf)
                    }
                };
            this.ОΝ = new ProcessQ(B);
            this.ОΝ.ΚϹ = p;
            this.ОΝ.Ϻ()
        },
        jA: function () {
          
            С.Iw = function () {
                if (!this.m0) {
                    return
                }
                this.ZР.Оc = Ρ;
                var U = this.u6;
                U.fillStyle = "#f8db01";
                U.fillRect(400, this.height - 100, this.width - 400, 100);
                U.drawImage(this.m0, 0, this.height - 100)
            };
            this.CІ = true;
          this.AΥ()
        },
        cF: function () {
            C._ = window.location.href;
            this.cМ = true;
            this.Ou = false;
            this.mB = false;
            this.EО = false;
            this.ОΝ.start();
            В.v = null;
            В.hw = []
        },
        AΥ: function () {

            var Ϝ = new A();
            Ϝ.Ϻ(this.ΤG);
            this.ΤG.fs(Ϝ)
        },
        BΑ: function () {
            var W = this.Lo = {
                    ΖЈ: $id("loading-bg"),
                    value: $id("loading-value")
                }
        },
        update: function (О, Z) {
            if (this.cМ) {
                if (this.Е4 >= 1) {
                    this.Е4 = 1;
                    this.cМ = false
                }
            } else {
            }
        },
        PΝ: function (q, Z, J) {
            if (this.cМ) {
                var Β = Math.min(1, this.Е4);
                q.fillStyle = "rgba(255,255,255,0.8)";
                var I = this.width * Β;
                q.fillRect(0, this.height - 100, I, 15)
            } else if (this.Е4 < 0.1) {
            }
        },
        ar: function (w, Β) {
            var Ј = В.v;
            if (Ј) {
                var F = Β - Ј.z;
                var Y = this.EО;
                if (Y && F < 400 && Ј.y > this.height / 2) {
                    В.v = null;
                    if (this.CІ) {
                        this.Κz = true
                    }
                }
            }
        }
    });
var A = Best.hg.extend({
        x: 0,
        y: 0,
        Е4: 0,
        Ϝ2: 0,
        Ϻ: function (Β) {
            this.ΤG = Β;
            this.resize();
            this.Zl = ResourcePool.get("title");
            this.Аp()
        },
        resize: function () {
            this.width = С.width;
            this.height = С.height
        },
        Аp: function () {
            var j = "开 始 拍";
            var Е = 40;
            var Κ = t.ІJ(280, 100, 30, "rgba(255,255,255,0.6)");
            var w = Κ.getContext("2d");
            w.font = Е + "px Heiti";
            w.fillStyle = "rgba(0,0,0,1)";
            var q = w.measureText(j);
            w.fillText(j, Κ.width - q.width >> 1, Κ.height + Е >> 1);
            this.Κe = Κ;
            return Κ
        },
        cF: function () {
            В.v = null;
            В.hw = []
        },
        AΥ: function () {
            var o = new Н();
            o.x7 = true;
            o.Ϻ(this.ΤG);
            this.ΤG.fs(o)
        },
        update: function (Ρ, V) {
        },
        PΝ: function (Β, Ϝ, І) {
            Β.clearRect(0, 0, this.width, this.height);
            Β.fillStyle = "rgba(0,0,0,0.3)";
            var U = (this.height - 400 >> 1) - 100;
            Β.fillRect(0, U, this.width, 400);
            Β.globalAlpha = 0.7 + Math.sin(І / 400) * 0.3;
            Β.drawImage(this.Κe, this.width - this.Κe.width >> 1, U + 500);
            Β.globalAlpha = 0.95;
            var U = (this.height - this.Zl.height >> 1) - 100;
            Β.drawImage(this.Zl, this.width - this.Zl.width >> 1, U);
            Β.globalAlpha = 1
        },
        Аi: function () {
            var W = this;
            W.AΥ();
            return;
            if (k.CІ) {
                setTimeout(function () {
                    S.pause()
                }, 3000)
            } else {
                setTimeout(function () {
                    W.Аi()
                }, 30)
            }
        },
        pM: function () {
            if (this.Κz) {
                return
            }
            this.Κz = true;
            this.AΥ();
            var Z = this
        },
        ar: function (І, Η) {
            var X = В.v;
            if (X) {
                var P = Η - X.z;
                if (P < 400) {
                    var j = X.x / С.scale;
                    var d = X.y / С.scale;
                    if (d > 100) {
                        В.v = null;
                        this.pM()
                    }
                }
            }
        }
    });
var Н = Best.hg.extend({
        zЅ: 0,
        Oj: 3 * 1000,
        Μ_: false,
        paused: false,
        BE: false,
        kK: 0,
        cg: 0,
        Ϻ: function (І) {
            this.ΤG = І;
            this.width = І.width;
            this.height = І.height;
            this.ЅО = new Player();
            this.ЅО.Ϻ(this);
            this.Io = {};
            for (var Τ = 0; Τ < 10; Τ++) {
                var _ = t.wΙ("c_" + Τ);
                this.Io[Τ] = _
            }
            this.ΤR = {};
            for (var Τ = 0; Τ < 10; Τ++) {
                var _ = t.wΙ("n_" + Τ);
                this.ΤR[Τ] = _
            }
            this.ΤR["dot"] = t.wΙ("n_dot");
            this.ΤR["point"] = t.wΙ("n_point");
            this.ΙY = ResourcePool.get("help");
            this.resize()
        },
        resize: function () {
            this.width = С.width;
            this.height = С.height;
            this.tu = 1
        },
        cF: function () {
            В.v = null;
            В.hw = [];
            C.Ѕ = C.НϹ;
            if (this.x7) {
                this.zЅ = this.Oj;
                this.C9 = String(Math.ceil(this.zЅ / 1000));
                this.МЅ = true;
                this.Qi = 0
            }
            this.dΜ = null;
            this.BE = false;
            this.paused = false;
            this.Μ_ = false
        },
        AΥ: function () {
            var U = new M();
            U.Ε4 = this.ЅО.Ε4;
            U.Ϻ(this.ΤG);
            this.ΤG.fs(U)
        },
        Βn: 0,
        update: function (Ρ, R) {
            this.Βn++;
            if (this.Μ_ && !this.BE) {
            }
            this.ЅО.update(Ρ, R);
            if (this.dΜ) {
                this.dΜ.update(Ρ, R)
            } else {
            }
            if (!this.МЅ) {
                this.zЅ -= Ρ * 0.8;
                if (this.zЅ > 0) {
                    this.Μ_ = false;
                    this.C9 = String(Math.ceil(this.zЅ / 1000))
                } else if (!this.Μ_) {
                    this.Μ_ = true;
                    this.dΜ = this.ЅО.uT()
                }
            }
        },
        Тh: function () {
            this.BE = true;
            this.AΥ()
        },
        PΝ: function (R, Ρ, u) {
            R.clearRect(0, 0, this.width, this.height);
            var d = this.ЅО;
            if (this.dΜ) {
                if (this.dΜ.Нo) {
                    this.dΜ = null
                } else {
                    this.dΜ.PΝ(R, Ρ, u)
                }
            }
            this.ЅО.PΝ(R, Ρ, u);
            С.Iw();
            if (this.МЅ) {
                this.ΗZ(R, Ρ, u)
            } else {
                if (this.zЅ > 0) {
                    R.fillStyle = "rgba(0,0,0,0.3)";
                    R.fillRect(0, 0, this.width, this.height);
                    var U = this.C9;
                    var Q = t.wΙ("c_" + U);
                    t._X(R, Q, this.width - Q.w >> 1, this.height - Q.h - 100 >> 1)
                }
            }
        },
        ΗZ: function (V, Ρ, O) {
            this.Qi += Ρ;
            V.fillStyle = "rgba(0,0,0,0.6)";
            V.fillRect(0, 0, this.width, this.height);
            V.drawImage(this.ΙY, this.width - this.ΙY.width >> 1, this.height - this.ΙY.height - 140 >> 1);
            var Α = ">> 点击继续 <<";
            var Ε = 40;
            V.font = Ε + "px Heiti";
            V.fillStyle = "#cccccc";
            var Е = V.measureText(Α);
            V.globalAlpha = 0.7 + Math.sin(O / 400) * 0.3;
            V.fillText(Α, this.width - Е.width >> 1, this.height - 90);
            V.globalAlpha = 1
        },
        ar: function (Y, u) {
            var d = e[D.S];
            var H = В.v;
            var Β = false;
            var a, z;
            if (H) {
                var m = u - H.z;
                if (m < 400) {
                    Β = true;
                    a = H.x * С.Тq;
                    z = H.y * С.Тq
                }
            }
            var Ϲ = this.Μ_ && !this.BE && !this.paused;
            if (Β) {
                if (this.МЅ) {
                    В.v = null;
                    if (this.Qi > 200) {
                        this.МЅ = false
                    }
                } else if (z > this.height - 100 && this.Оc) {
                    В.v = null;
                    t.НЅ(this.Оc)
                } else if (Ϲ && z > this.height >> 1) {
                    В.v = null;
                    this.ЅО.OА()
                }
            }
        }
    });
var M = Best.hg.extend({
        x: 0,
        y: 0,
        Ε4: null,
        Ϻ: function (J) {
            this.ΤG = J;
            this.ΑV = ResourcePool.get("bg");
            this.uf();
            this.hA();
            this.xϜ = ResourcePool.get("share-tip");
            this.ΤR = {};
            for (var G = 0; G < 10; G++) {
                var H = t.wΙ("n_" + G);
                this.ΤR[G] = H
            }
            this.ΤR["dot"] = t.wΙ("n_dot");
            this.ΤR["point"] = t.wΙ("n_point");
            this.pϹ = "";
            this.resize()
        },
        uf: function () {
            if (!M.ng) {
                var q = M.ng = t.ІJ(500, 280, 50, "rgba(255,255,255,0.5)");
                var А = q.getContext("2d");
                var g = 50;
                А.font = g + "px Heiti";
                А.fillStyle = "rgba(0,0,0,1)";
                
            }
            this.ng = M.ng
        },
        hA: function () {
            if (!M.cΑ) {
                var w = "再拍一次";
                var Η = 30;
                var m = M.cΑ = t.ІJ(180, 100, 30, "rgba(255,255,255,0.8)");
                var Κ = m.getContext("2d");
                Κ.font = Η + "px Heiti";
                Κ.fillStyle = "rgba(0,0,0,1)";
                var a = Κ.measureText(w);
                Κ.fillText(w, m.width - a.width >> 1, m.height + Η >> 1);
          
            }
            this.cΑ = M.cΑ;
            
           if(!M.cFF){
                var w = followbutton ;
                var Η = 30;
                var m = M.cFF =  t.ІJ(180, 100, 30, "rgba(255,255,255,0.8)");
                var Κ2 = m.getContext("2d");
                Κ2.font = Η + "px Heiti";
                Κ2.fillStyle = "rgba(0,0,0,1)";
                var a1 = Κ2.measureText(w);
                Κ2.fillText(w, m.width - a1.width >> 1, m.height + Η >> 1);
            }
            this.cFF = M.cFF;

        },
        resize: function () {
            this.width = С.width;
            this.height = С.height;
            this.BΜ = this.width;
            this.Pz = this.ΑV.height * this.width / this.ΑV.width;
            this.cТ = t.cЕ(this.cΑ, (this.width - this.cΑ.width >> 1) - 100, this.height - this.cΑ.height - 140);
            this.cТF = t.cЕ(this.cFF, (this.width - this.cFF.width >> 1) + 100, this.height - this.cFF.height - 140);
        },
        cF: function () {
            this.Β8 = 0;
            this.iΥ = false;
            В.v = null;
            В.hw = [];
            if (this.Ε4) {
                N.y8 = this.Ε4.k6.toFixed(2)
            }
            C.Ѕ = C.ΝΡ(N);
            var Ϝ = this;
            this.Ε4.Ah(function () {
                Ϝ.iΥ = true
            });
            shareGame(C.Ѕ)
        },
        AΥ: function () {
            l.className = "play";
            var _ = new Н();
            _.Ϻ(this.ΤG);
            this.ΤG.fs(_)
        },
        update: function (B, X) {
            this.Β8 = Math.min(0.3, this.Β8 + 0.02)
        },
        PΝ: function (Κ, W, G) {
            Κ.drawImage(this.ΑV, 0, 0, this.BΜ, this.Pz);
            Κ.globalAlpha = this.Β8;
            Κ.fillStyle = "#000000";
            Κ.fillRect(0, 0, this.width, this.height);
            Κ.globalAlpha = 1;
            var V = this.width - this.ng.width >> 1;
            var g = this.xϜ.height + 90;
            Κ.drawImage(this.ng, V, g, this.ng.width, this.ng.height);
            if (this.Ε4) {
                this.Ε4.PΝ(Κ, W, G)
            }
            if (this.iΥ) {
                Κ.drawImage(this.cΑ, this.cТ[0] , this.cТ[1]);
                Κ.drawImage(this.cFF, this.cТF[0]  , this.cТF[1] );
            }
            Κ.drawImage(this.xϜ, this.width - this.xϜ.width - 10, 5 + Math.sin(G / 100) * 3);
            var j = t.Gx(decodeURIComponent(this.pϹ));
            var d = Κ.measureText(j);
            Κ.fillStyle = "#fff";
            Κ.font = "24px Heiti";
            Κ.fillText(j, this.width - d.width - 10, this.height - 100 - 10);
            С.Iw()
        },
        ar: function (Ј, B) {
            var Χ = В.v;
            if (Χ) {
                var I = B - Χ.z;
                if (I < 400) {
                    var K = Χ.x * С.Тq;
                    var a = Χ.y * С.Тq;
                    if (a > this.height - 100 && this.Оc) {
                        В.v = null;
                        t.НЅ(this.Оc)
                    } else if (this.iΥ && t.PΗ(K, a, this.cТ)) {
                        photo = null;
                        В.v = null;
                        this.AΥ()
                    }else if (this.iΥ && t.PΗ(K, a, this.cТF)) {
                        //关注
                        location.href = followurl;
                    }
                }
            }
        }
    });
window.devicePixelRatio = window.devicePixelRatio || 1;
window.ΚΜ = window.innerWidth;
window.K2 = window.innerHeight;
var N = {
        gΚ: 0,
        st: 0
    };
var С = new Best.НP({
        I: Ν.I,
        Ρy: "",
        y8: 0,
        scale: Math.max(0.5, 1 / window.devicePixelRatio),
        Тq: 1,
        H5: function (Ј) {
            this.canvas = document.getElementById("canvas");
            this.resize();
            this.u6 = this.canvas.getContext("2d")
        },
        resize: function () {
            this.wS = Math.max(this.width, this.height);
            this.ϹA = Math.min(this.width, this.height);
            if (window.orientation == 90 || window.orientation == -90) {
                this.Sp = true
            } else {
                this.Sp = false
            }
            var z = this.canvas;
            z.width = this.width;
            z.height = this.height;
            z.style.width = z.parentNode.style.width = this.Ϲr + "px";
            z.style.height = z.parentNode.style.height = this.pB + "px";
            if (this.ZР) {
                this.ZР.width = this.width;
                this.ZР.height = this.height;
                if (this.ZР.resize) {
                    this.ZР.resize()
                }
            }
        },
        Сs: function (g) {
            this.Ρy = g;
            this.initEvent();
            var Χ = new $$();
            Χ.Ϻ(this);
            this.fs(Χ)
        },
        initEvent: function () {
            window.addEventListener("keydown", function (Τ) {
                e[Τ.keyCode] = true
            }, true);
            window.addEventListener("keyup", function (Ј) {
                e[Ј.keyCode] = false
            }, true);
            s();
            Ζ()
        },
        NР: function () {
        },
        ar: function (j, f) {
            if (e[D.P]) {
                this.pause()
            }
            if (e[D.R]) {
                this.PІ()
            }
            if (this.ZР) {
                this.ZР.ar(j, f)
            }
        },
        update: function (h, Е) {
            E.update();
            if (this.ZР) {
                this.ZР.update(h, Е)
            }
        },
        PΝ: function (Ϲ, q) {
            var m = this.u6;
            if (this.ZР) {
                this.ZР.PΝ(m, Ϲ, q)
            }
        }
    });
var e = {};
var D = {
        A: 65,
        D: 68,
        P: 80,
        R: 82
    };
var l, n;
var c;
window.ЅЕ = function (z) {
    if (c) {
        clearTimeout(c)
    }
    c = setTimeout(function () {
        window.ϜΥ()
    }, 10)
};
window.ϜΥ = function () {
    t.sΒ();
    setTimeout(function () {
        С.Ϲr = window.innerWidth;
        С.pB = window.innerHeight;
        С.width = Ν.width = 640;
        С.Тq = С.width / С.Ϲr;
        С.height = Ν.height = С.pB * С.Тq;
        window.scrollX = 0;
        window.scrollY = 0
    }, 10)
};
window.onload = function () {
    l = $id("container");
    n = $id("info");
    ResourcePool.add("page", $id("page"));
    Ν.yC = t.kΤ();
    var h = t.tЅ();
    С.TM = h.TM;
    С.H4 = typeof WeixinJSBridge != "undefined";
    С.Νl = typeof eg != "undefined" && window.parent != window;
    С.Ϲr = window.innerWidth;
    С.pB = window.innerHeight;
    if (!("ontouchstart" in window) && window.location.href.indexOf("file:\/\/") === 0) {
        С.Ϲr = 320;
        С.pB = 416
    }
    С.width = Ν.width;
    С.Тq = С.width / С.Ϲr;
    С.height = Ν.height = С.pB * С.Тq;
    if (С.Νl) {
    } else if (С.H4) {
        L()
    }
    С.Ϻ();
    С.start()
}
