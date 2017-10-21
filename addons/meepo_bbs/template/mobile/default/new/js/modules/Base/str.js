!$.str = function() {
    function a(a) {
        var b = {};
        return a >= 0 && (b["&quot;"] = 34,
        b["&amp;"] = 38,
        b["&apos;"] = 39,
        b["&lt;"] = 60,
        b["&gt;"] = 62,
        b["&nbsp;"] = 160),
        a >= 1 && (b["&iexcl;"] = 161,
        b["&cent;"] = 162,
        b["&pound;"] = 163,
        b["&curren;"] = 164,
        b["&yen;"] = 165,
        b["&brvbar;"] = 166,
        b["&sect;"] = 167,
        b["&uml;"] = 168,
        b["&copy;"] = 169,
        b["&ordf;"] = 170,
        b["&laquo;"] = 171,
        b["&not;"] = 172,
        b["&shy;"] = 173,
        b["&reg;"] = 174,
        b["&macr;"] = 175,
        b["&deg;"] = 176,
        b["&plusmn;"] = 177,
        b["&sup2;"] = 178,
        b["&sup3;"] = 179,
        b["&acute;"] = 180,
        b["&micro;"] = 181,
        b["&para;"] = 182,
        b["&middot;"] = 183,
        b["&cedil;"] = 184,
        b["&sup1;"] = 185,
        b["&ordm;"] = 186,
        b["&raquo;"] = 187,
        b["&frac14;"] = 188,
        b["&frac12;"] = 189,
        b["&frac34;"] = 190,
        b["&iquest;"] = 191,
        b["&Agrave;"] = 192,
        b["&Aacute;"] = 193,
        b["&Acirc;"] = 194,
        b["&Atilde;"] = 195,
        b["&Auml;"] = 196,
        b["&Aring;"] = 197,
        b["&AElig;"] = 198,
        b["&Ccedil;"] = 199,
        b["&Egrave;"] = 200,
        b["&Eacute;"] = 201,
        b["&Ecirc;"] = 202,
        b["&Euml;"] = 203,
        b["&Igrave;"] = 204,
        b["&Iacute;"] = 205,
        b["&Icirc;"] = 206,
        b["&Iuml;"] = 207,
        b["&ETH;"] = 208,
        b["&Ntilde;"] = 209,
        b["&Ograve;"] = 210,
        b["&Oacute;"] = 211,
        b["&Ocirc;"] = 212,
        b["&Otilde;"] = 213,
        b["&Ouml;"] = 214,
        b["&times;"] = 215,
        b["&Oslash;"] = 216,
        b["&Ugrave;"] = 217,
        b["&Uacute;"] = 218,
        b["&Ucirc;"] = 219,
        b["&Uuml;"] = 220,
        b["&Yacute;"] = 221,
        b["&THORN;"] = 222,
        b["&szlig;"] = 223,
        b["&agrave;"] = 224,
        b["&aacute;"] = 225,
        b["&acirc;"] = 226,
        b["&atilde;"] = 227,
        b["&auml;"] = 228,
        b["&aring;"] = 229,
        b["&aelig;"] = 230,
        b["&ccedil;"] = 231,
        b["&egrave;"] = 232,
        b["&eacute;"] = 233,
        b["&ecirc;"] = 234,
        b["&euml;"] = 235,
        b["&igrave;"] = 236,
        b["&iacute;"] = 237,
        b["&icirc;"] = 238,
        b["&iuml;"] = 239,
        b["&eth;"] = 240,
        b["&ntilde;"] = 241,
        b["&ograve;"] = 242,
        b["&oacute;"] = 243,
        b["&ocirc;"] = 244,
        b["&otilde;"] = 245,
        b["&ouml;"] = 246,
        b["&divide;"] = 247,
        b["&oslash;"] = 248,
        b["&ugrave;"] = 249,
        b["&uacute;"] = 250,
        b["&ucirc;"] = 251,
        b["&uuml;"] = 252,
        b["&yacute;"] = 253,
        b["&thorn;"] = 254,
        b["&yuml;"] = 255),
        b
    }
    var b = {}
      , c = {}
      , d = {}
      , e = {}
      , f = function(c) {
        return b[c] || (b[c] = a(c)),
        b[c]
    }
      , g = function(a) {
        if (!d[a]) {
            var b = f(a)
              , c = {};
            for (var e in b)
                b.hasOwnProperty(e) && (c[String.fromCharCode(b[e])] = "&#" + b[e] + ";");
            c[" "] = "&#32;",
            d[a] = c
        }
        return d[a]
    }
      , h = function(a) {
        return c[a] || (c[a] = new RegExp("(" + j(f(a)).join("|") + ")","g")),
        c[a]
    }
      , i = function(a) {
        return e[a] || (e[a] = new RegExp("[" + j(g(a)).join("") + "]","g")),
        e[a]
    }
      , j = function(a) {
        var b = [];
        for (var c in a)
            a.hasOwnProperty(c) && b.push(c);
        return b
    }
      , k = {
        '"': "#34",
        "<": "#60",
        ">": "#62",
        "&": "#38",
        " ": "#160"
    };
    return k[String.fromCharCode(160)] = "#160",
    {
        decodeHtml: function(a, b) {
            b = isNaN(b) ? 0 : b,
            a += "";
            var c = h(b)
              , d = f(b);
            return a.replace(c, function(a, b) {
                return "&#" + d[b] + ";"
            }).replace(/&#x([a-f\d]+);/g, function(a, b) {
                return "&#" + parseInt("0x" + b) + ";"
            }).replace(/&#(\d+);/g, function(a, b) {
                return String.fromCharCode(+b)
            })
        },
        encodeHtml: function(a, b) {
            b = isNaN(b) ? 0 : b,
            a += "";
            var c = i(b)
              , d = g(b);
            return a.replace(c, function(a) {
                return d[a]
            })
        }
    }
}();