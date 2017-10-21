(typeof Crypto == "undefined" || !Crypto.util) && function ()
{
    var e = self.Crypto = {}, g = e.util = 
    {
        rotl : function (a, b)
        {
            return a << b | a >>> 32 - b;
        },
        rotr : function (a, b)
        {
            return a << 32 - b | a >>> b;
        },
        endian : function (a)
        {
            if (a.constructor == Number) {
                return g.rotl(a, 8) & 16711935 | g.rotl(a, 24) & 4278255360;
            }
            for (var b = 0; b < a.length; b++) {
                a[b] = g.endian(a[b]);
            }
            return a;
        },
        randomBytes : function (a)
        {
            for (var b = []; a > 0; a--) {
                b.push(Math.floor(Math.random() * 256));
            }
            return b;
        },
        bytesToWords : function (a)
        {
            for (var b = [], c = 0, d = 0; c < a.length; c++, d += 8) {
                b[d >>> 5] |= a[c] << 24 - d % 32;
            }
            return b;
        },
        wordsToBytes : function (a)
        {
            for (var b = [], c = 0; c < a.length * 32; c += 8) {
                b.push(a[c >>> 5] >>> 24 - c % 32 & 255);
            }
            return b;
        },
        bytesToHex : function (a)
        {
            for (var b = [], c = 0; c < a.length; c++) {
                b.push((a[c] >>> 4).toString(16)), b.push((a[c] & 15).toString(16));
            }
            return b.join("");
        },
        hexToBytes : function (a)
        {
            for (var b = [], c = 0; c < a.length; c += 2) {
                b.push(parseInt(a.substr(c, 2), 16));
            }
            return b;
        },
        bytesToBase64 : function (a)
        {
            if (typeof btoa == "function")
            {
                return btoa(f.bytesToString(a));
            }
            for (var b = [], c = 0; c < a.length; c += 3)
            {
                for (var d = a[c] << 16 | a[c + 1] << 8 | a[c + 2], e = 0; e < 4; e++)
                {
                    c * 8 + e * 6 <= a.length * 8 ? b.push("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/".charAt(d >>> 6 * (3 - e) & 63)) : b.push("=");
                }
            }
            return b.join("");
        },
        base64ToBytes : function (a)
        {
            if (typeof atob == "function")
            {
                return f.stringToBytes(atob(a));
            }
            for (var a = a.replace(/[^A-Z0-9+\/]/ig, ""), b = [], c = 0, d = 0; c < a.length; d =++c % 4)
            {
                d != 0 && b.push(("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/".indexOf(a.charAt(c - 1)) & Math.pow(2, 
                 - 2 * d + 8) - 1) << d * 2 | "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/".indexOf(a.charAt(c)) >>> 6 - d * 2);
            }
            return b;
        }
    },
    e = e.charenc = {};
    e.UTF8 = 
    {
        stringToBytes : function (a)
        {
            return f.stringToBytes(unescape(encodeURIComponent(a)));
        },
        bytesToString : function (a)
        {
            return decodeURIComponent(escape(f.bytesToString(a)));
        }
    };
    var f = e.Binary = 
    {
        stringToBytes : function (a)
        {
            for (var b = [], c = 0; c < a.length; c++) {
                b.push(a.charCodeAt(c) & 255);
            }
            return b;
        },
        bytesToString : function (a)
        {
            for (var b = [], c = 0; c < a.length; c++) {
                b.push(String.fromCharCode(a[c]));
            }
            return b.join("");
        }
    }
}();
function sha1(m, hash)
{
    var w = [];
    var H0 = hash[0], H1 = hash[1], H2 = hash[2], H3 = hash[3], H4 = hash[4];
    for (var i = 0; i < m.length; i += 16)
    {
        var a = H0, b = H1, c = H2, d = H3, e = H4;
        for (var j = 0; j < 80; j++)
        {
            if (j < 16) {
                w[j] = m[i + j] | 0;
            }
            else {
                var n = w[j - 3]^w[j - 8]^w[j - 14]^w[j - 16];
                w[j] = n << 1 | n >>> 31
            }
            var t = (H0 << 5 | H0 >>> 27) + H4 + (w[j] >>> 0) + (j < 20 ? (H1 & H2 | ~H1 & H3) + 1518500249 : j < 40 ? (H1^H2^H3) + 1859775393 : j < 60 ? (H1 & H2 | H1 & H3 | H2 & H3) - 1894007588 : (H1^H2^H3) - 899497514);
            H4 = H3;
            H3 = H2;
            H2 = H1 << 30 | H1 >>> 2;
            H1 = H0;
            H0 = t
        }
        H0 = H0 + a | 0;
        H1 = H1 + b | 0;
        H2 = H2 + c | 0;
        H3 = H3 + d | 0;
        H4 = H4 + e | 0
    }
    return [H0, H1, H2, H3, H4]
}
self.hash = [1732584193, - 271733879, - 1732584194, 271733878, - 1009589776];
self.addEventListener("message", function (event)
{
    var uint8_array, message, block, nBitsTotal, output, nBitsLeft, nBitsTotalH, nBitsTotalL;
    uint8_array = new Uint8Array(event.data.message);
    message = Crypto.util.bytesToWords(uint8_array);
    block = event.data.block;
    event = null;
    uint8_array = null;
    output = {
        "block" : block
    };
    if (block.end === block.file_size)
    {
        nBitsTotal = block.file_size * 8;
        nBitsLeft = (block.end - block.start) * 8;
        nBitsTotalH = Math.floor(nBitsTotal / 4294967296);
        nBitsTotalL = nBitsTotal & 4294967295;
        message[nBitsLeft >>> 5] |= 128 << 24 - nBitsLeft % 32;
        message[(nBitsLeft + 64 >>> 9 << 4) + 14] = nBitsTotalH;
        message[(nBitsLeft + 64 >>> 9 << 4) + 15] = nBitsTotalL;
        self.hash = sha1(message, self.hash);
        output.result = Crypto.util.bytesToHex(Crypto.util.wordsToBytes(self.hash))
    }
    else {
        self.hash = sha1(message, self.hash);
    }
    message = null;
    self.postMessage(output)
}, false);