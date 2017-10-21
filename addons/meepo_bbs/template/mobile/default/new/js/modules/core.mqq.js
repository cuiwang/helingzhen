var mqq = function(){};
var a = {};
var g = function(){};

function c(a, b, c) {
    var d;
    for (d in b)
        (b.hasOwnProperty(d) && !(d in a) || c) && (a[d] = b[d]);
    return a
}


function n(b, c) {
    var d = null 
      , e = a.platform
      , f = b.split(".")
      , h = b.lastIndexOf(".")
      , i = f[f.length - 2]
      , j = f[f.length - 1]
      , k = g(b.substring(0, h));
    (!k[j] || a.debuging) && ((d = c[a.platform]) || "browser" === e || ((d = a.iOS && c.iOS) ? e = "iOS" : (d = a.android && c.android) && (e = "android")),
    d && c.supportInvoke && (P[i + "." + j] = d),
    k[j] = d ? d : m,
    c.support && c.support[e] && (O[i + "." + j] = c.support[e]))
}