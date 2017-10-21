(function() {
var e = [], t = parseFloat(seajs.version);
define([], function(n, r, i) {
var s = i.uri || i.id, o = s.split("?")[0].match(/^(.+\/)([^\/]*?)(?:\.js)?$/i), u = o && o[1], a = o && "./" + o[2], f = 0, l = e.length, c, h, p;
for (; f < l; f++) h = e[f], typeof h[0] == "string" && (a === h[0] && (c = h[2]), h[0] = u + h[0].replace("./", ""), t > 1 && define.apply(this, h));
return e = [], n.get = n, typeof c == "function" ? c.apply(this, arguments) : n;
}), define.pack = function() {
e.push(arguments), t > 1 || define.apply(null, arguments);
};
})(), define.pack("./ajax", [ "./util" ], function(e, t, n) {
var r = e("./util"), i = {};
i.request = function(e, t) {
var n = new XMLHttpRequest;
typeof n.timeout != "undefined" && (n.timeout = 6e3), n.open("get", e, !0), n.send(), n.onreadystatechange = function() {
if (n.readyState == 4 && n.status == 200) {
if (n.responseText == "success") {
t({
status: !0,
msg: "OK"
});
return;
}
var e = JSON.parse(n.responseText);
t({
status: !0,
msg: e
});
} else if (n.readyState == 4 && n.status != 200) {
var e = JSON.parse(n.responseText);
t({
status: !1,
msg: e.error.message
});
}
}, n.ontimeout = function(e) {
t({
status: !1,
msg: "请求超时，请稍后重试"
});
}, n.onerror = function(e) {
t({
status: !1,
msg: "network error"
});
};
}, i.post = function(e, t, n) {
var i = new XMLHttpRequest;
if (typeof i.timeout != "undefined") try {
i.timeout = 6e3;
} catch (s) {}
i.open("post", e, !0), i.setRequestHeader("Content-Type", "application/json"), i.send(JSON.stringify(t)), i.onreadystatechange = function() {
i.readyState == 4 && n(i.responseText);
}, i.ontimeout = function(e) {
r.innerCallback({
status: !1,
msg: "请求超时，请稍后重试"
});
}, i.onerror = function(e) {
r.innerCallback({
status: !1,
msg: "network error"
});
};
}, n.exports = i;
}), define.pack("./ap", [], function(e, t, n) {
return new function() {
(function() {
var e = {}, t = {};
t.PADCHAR = "=", t.ALPHA = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/", t.makeDOMException = function() {
var e, t;
try {
return new DOMException(DOMException.INVALID_CHARACTER_ERR);
} catch (t) {
var n = new Error("DOM Exception 5");
return n.code = n.number = 5, n.name = n.description = "INVALID_CHARACTER_ERR", n.toString = function() {
return "Error: " + n.name + ": " + n.message;
}, n;
}
}, t.getbyte64 = function(e, n) {
var r = t.ALPHA.indexOf(e.charAt(n));
if (r === -1) throw t.makeDOMException();
return r;
}, t.decode = function(e) {
e = "" + e;
var n = t.getbyte64, r, i, s, o = e.length;
if (o === 0) return e;
if (o % 4 !== 0) throw t.makeDOMException();
r = 0, e.charAt(o - 1) === t.PADCHAR && (r = 1, e.charAt(o - 2) === t.PADCHAR && (r = 2), o -= 4);
var u = [];
for (i = 0; i < o; i += 4) s = n(e, i) << 18 | n(e, i + 1) << 12 | n(e, i + 2) << 6 | n(e, i + 3), u.push(String.fromCharCode(s >> 16, s >> 8 & 255, s & 255));
switch (r) {
case 1:
s = n(e, i) << 18 | n(e, i + 1) << 12 | n(e, i + 2) << 6, u.push(String.fromCharCode(s >> 16, s >> 8 & 255));
break;
case 2:
s = n(e, i) << 18 | n(e, i + 1) << 12, u.push(String.fromCharCode(s >> 16));
}
return u.join("");
}, t.getbyte = function(e, n) {
var r = e.charCodeAt(n);
if (r > 255) throw t.makeDOMException();
return r;
}, t.encode = function(e) {
if (arguments.length !== 1) throw new SyntaxError("Not enough arguments");
var n = t.PADCHAR, r = t.ALPHA, i = t.getbyte, s, o, u = [];
e = "" + e;
var f = e.length - e.length % 3;
if (e.length === 0) return e;
for (s = 0; s < f; s += 3) o = i(e, s) << 16 | i(e, s + 1) << 8 | i(e, s + 2), u.push(r.charAt(o >> 18)), u.push(r.charAt(o >> 12 & 63)), u.push(r.charAt(o >> 6 & 63)), u.push(r.charAt(o & 63));
switch (e.length - f) {
case 1:
o = i(e, s) << 16, u.push(r.charAt(o >> 18) + r.charAt(o >> 12 & 63) + n + n);
break;
case 2:
o = i(e, s) << 16 | i(e, s + 1) << 8, u.push(r.charAt(o >> 18) + r.charAt(o >> 12 & 63) + r.charAt(o >> 6 & 63) + n);
}
return u.join("");
}, e.pay = function(e) {
var n = encodeURIComponent(t.encode(e));
var Url = 'http://'+window.location.host+"/addons/weliam_indiana/template/mobile/default/pay.htm?goto=";
location.href = Url + n;
}, e.decode = function(e) {
return t.decode(decodeURIComponent(e));
}, window._AP = e;
})();
};
}), define.pack("./bind", [ "./tmpl", "./ajax", "./cache", "./util", "./success", "./config", "./report" ], function(e, t, n) {
var r = e("./tmpl"), i = e("./ajax"), s = e("./cache"), o = e("./util"), u = e("./success"), a = e("./config"), f = e("./report"), l = {};
l.buttonClickable = !0, l.maskClickable = !0, l.moveFlag = !1, l.init = function() {
var e = this, t = o.Id("p_one_channelList");
t.addEventListener("click", function(t) {
t.preventDefault();
if (!e.buttonClickable) return;
o.showLoading("正在创建订单"), e.buttonClickable = !1;
var t = t || event, n = t.target, r = n.getAttribute("p_one_channel");
r == null && (r = n.parentNode.getAttribute("p_one_channel")), r == null && (r = n.parentNode.parentNode.getAttribute("p_one_channel"));
var u = {};
u.channel = r, u.order_no = s.userData.order_no, u.amount = s.userData.amount, r == "wx_pub" && (u.open_id = s.userData.open_id);
if (s.userData.charge_param) {
var a = s.userData.charge_param;
for (var l in a) u[l] = a[l];
}
i.post(s.userData.charge_url, u, function(t) {
o.hideLoading();
var n;
try {
n = JSON.parse(t);
} catch (i) {
o.innerCallback({
status: !1,
msg: "json decode fail",
debug: s.isDebugMode,
chargeUrlOutput: t
}), o.pop.close();
return;
}
if (typeof n == "undefined") {
o.innerCallback({
status: !1,
msg: "json decode fail",
debug: s.isDebugMode,
chargeUrlOutput: t
}), o.pop.close();
return;
}
if (typeof n.id == "undefined") {
o.innerCallback({
status: !1,
msg: "invalid_charge:no_charge_id",
debug: s.isDebugMode,
chargeUrlOutput: t
}), o.pop.close();
return;
}
if (typeof n.channel == "undefined") {
o.innerCallback({
status: !1,
msg: "invalid_charge:no_channel",
debug: s.isDebugMode,
chargeUrlOutput: t
}), o.pop.close();
return;
}
if (typeof n.credential == "undefined") {
o.innerCallback({
status: !1,
msg: "invalid_charge:no_credential",
debug: s.isDebugMode,
chargeUrlOutput: t
}), o.pop.close();
return;
}
if (s.isDebugMode) {
e.buttonClickable = !0, s.result = n, s.channel = r, o.innerCallback({
status: !0,
msg: "success",
debug: s.isDebugMode,
chargeUrlOutput: t
});
return;
}
o.pop.close(), e.requestCallback({
status: !0,
msg: n
}, r);
}), s.isDebugMode || f.report({
type: "click",
channel: r
});
});
var n;
"ontouchstart" in document ? n = "touchstart" : n = "click", document.addEventListener("dbclick", function() {
return !1;
}), document.ontouchmove = function() {
e.moveFlag = !0;
}, document.ontouchend = function() {
e.moveFlag = !1;
}, o.Id("p_one_mask").addEventListener("touchstart", function(e) {});
var r = "ontouchend" in document ? "touchend" : "click";
o.Id("p_one_mask").addEventListener(r, function(t) {
if (!e.maskClickable) return;
e.buttonClickable = !0, e.moveFlag || (o.pop.close(), e.moveFlag = !0, e.maskClickable = !1);
});
}, l.requestCallback = function(e, t) {
var n = this;
if (e.status) {
var r = e.msg, i = r.credential[t], s = r.app, u = r.order_no, l = r.amount;
o.cookie._set("PING_APPID_ORDER", s + a.seperator + u + a.seperator + l), r["livemode"] == 0 ? n.testModeNotify(r) : (f.report({
type: "charge_success",
channel: t,
ch_id: r.id
}), n.chargeCallback(t, i));
} else n.buttonClickable = !0, f.report({
type: "charge_fail",
channel: t
}), o.innerCallback(e);
}, l.testModeNotify = function(e) {
var t = this;
if (e["channel"] == "wx_pub") confirm("模拟付款") && (o.pop.close(), o.hideLoading(), i.request(a.channelURLMap.pingpp_notify_url + e.id + "?livemode=false", function(e) {
e.status ? (t.buttonClickable = !0, o.innerCallback({
status: !0,
msg: "OK"
})) : o.innerCallback(e);
})); else {
var n = {
ch_id: e.id,
scheme: "http",
channel: e.channel
};
e.order_no && (n.order_no = e.order_no), e.extra && (n.extra = encodeURIComponent(JSON.stringify(e.extra))), window.location.href = a.channelURLMap.pingpp_mock_url + "?" + o.stringify_data(n);
}
}, l.chargeCallback = function(e, t) {
var n = this, t = t, r = a.channelURLMap[e];
if (e == "alipay_wap") {
t._input_charset = "utf-8";
var i = o.stringify_data(t, e, !0);
typeof _AP != "undefined" ? _AP.pay(r + "?" + i) : location.href = r + "?" + i;
} else if (e == "upacp_wap") o.form_submit(r, "post", t); else if (e == "upmp_wap") n.buttonClickable = !0, location.href = r + t.paydata; else if (e == "bfb_wap") {
var i = o.stringify_data(t, e);
location.href = t.url + "?" + i;
} else if (e == "wx_pub") {
var u = [ "appId", "timeStamp", "nonceStr", "package", "signType", "paySign" ];
for (var f in u) if (typeof u[f] != "function" && !t[u[f]]) {
o.innerCallback({
status: !1,
msg: "missing field " + u[f]
});
return;
}
n.callWXPay(t);
} else if (e == "jdpay_wap") o.form_submit(r, "post", t); else if (e == "yeepay_wap") {
var u = [ "merchantaccount", "encryptkey", "data" ];
for (var f in u) if (typeof u[f] != "function" && !t[u[f]]) {
o.innerCallback({
status: !1,
msg: "missing field " + u[f],
debug: s.isDebugMode
});
return;
}
var i = o.stringify_data(t, e, !0);
t.mode && t["mode"] == "test" ? window.location.href = a.channelURLMap.yeepay_wap_test + "?" + i : window.location.href = a.channelURLMap.yeepay_wap + "?" + i;
}
}, l.callWXPay = function(e) {
function n() {
WeixinJSBridge.invoke("getBrandWCPayRequest", e, function(e) {
e.err_msg != "get_brand_wcpay_request:ok" && e.err_msg != "get_brand_wcpay_request:cancel" && o.innerCallback({
status: !1,
msg: e.err_msg,
debug: s.isDebugMode
}), e.err_msg == "get_brand_wcpay_request:ok" && (t.buttonClickable = !0, o.innerCallback({
status: !0,
msg: "OK",
debug: s.isDebugMode,
wxSuccess: !0
}));
});
}
var t = this, e = e;
t.buttonClickable = !0, o.pop.close(), o.hideLoading(), typeof WeixinJSBridge == "undefined" ? document.addEventListener("WeixinJSBridgeReady", n, !1) : n();
}, n.exports = l;
}), define.pack("./cache", [], function(e, t, n) {
var r = {};
n.exports = r;
}), define.pack("./config", [], function(e, t, n) {
var r = {};
r.channelMap = {
alipay_wap: "alipay_wap",
upmp_wap: "upmp_wap",
upacp_wap: "upacp_wap",
bfb_wap: "bfb_wap",
wx_pub: "wx_pub",
jdpay_wap: "jdpay_wap",
yeepay_wap: "yeepay_wap"
}, r.channelURLMap = {
upacp_wap: "https://gateway.95516.com/gateway/api/frontTransReq.do",
alipay_wap: "http://wappaygw.alipay.com/service/rest.htm",
upmp_wap: "uppay://uppayservice/?style=token&paydata=",
jdpay_wap: "https://m.jdpay.com/wepay/web/pay",
yeepay_wap: "https://ok.yeepay.com/paymobile/api/pay/request",
yeepay_wap_test: "http://mobiletest.yeepay.com/paymobile/api/pay/request",
pingpp_notify_url: "https://api.pingxx.com/notify/charges/",
pingpp_mock_url: "http://sissi.pingxx.com/mock.php"
}, r.seperator = "###", n.exports = r;
}), define.pack("./init", [ "./config", "./tmpl", "./cache", "./util", "./ajax", "./bind", "./report", "./ap" ], function(e, t, n) {
var r = e("./config"), i = e("./tmpl"), s = e("./cache"), o = e("./util"), u = e("./ajax"), a = e("./bind"), f = e("./report"), l = {};
l.init = function(t, n) {
o.isInWeixin() && e("./ap"), o.hideLoading();
var u = t.channel || {}, l = t.charge_url;
s.userData = t, s.userCallback = n, s.isDebugMode = t.debug || !1, a.moveFlag = !1;
if (typeof t.app_id == "undefined") {
o.innerCallback({
status: !1,
msg: "缺少参数app_id",
debug: s.isDebugMode
});
return;
}
if (typeof t.amount == "undefined") {
o.innerCallback({
status: !1,
msg: "缺少参数 amount",
debug: s.isDebugMode
});
return;
}
if (typeof t.channel == "undefined") {
o.innerCallback({
status: !1,
msg: "缺少参数 channel",
debug: s.isDebugMode
});
return;
}
if (t.channel.length == 0) {
o.innerCallback({
status: !1,
msg: "请至少配置一个渠道",
debug: s.isDebugMode
});
return;
}
if (typeof t.charge_url == "undefined") {
o.innerCallback({
status: !1,
msg: "缺少参数 charge_url",
debug: s.isDebugMode
});
return;
}
for (var c = 0; c < u.length; c++) if (typeof r.channelMap[u[c]] == "undefined") {
o.innerCallback({
status: !1,
msg: "传入了非法渠道：" + u[c],
debug: s.isDebugMode
});
return;
}
var h = {};
for (var c = 0; c < u.length; c++) switch (u[c]) {
case "alipay_wap":
h.alipay_wap = "alipay_wap";
break;
case "upmp_wap":
h.upmp_wap = "upmp_wap";
break;
case "upacp_wap":
h.upacp_wap = "upacp_wap";
break;
case "bfb_wap":
h.bfb_wap = "bfb_wap";
break;
case "wx_pub":
h.wx_pub = "wx_pub";
break;
case "jdpay_wap":
h.jdpay_wap = "jdpay_wap";
break;
case "yeepay_wap":
h.yeepay_wap = "yeepay_wap";
}
h.wx_pub && !o.isInWeixin() && (u = o.removeFromArray(u, "wx_pub")), h.upmp_wap && o.isInWeixin() && (u = o.removeFromArray(u, "upmp_wap"));
if (h.wx_pub && o.isInWeixin()) if (!t.open_id || t.open_id == "") {
o.innerCallback({
status: !1,
msg: "缺少参数 open_id",
debug: s.isDebugMode
});
return;
}
var p = function() {
if (o.Id("p_one_frame") == null) {
var e = document.createElement("div");
e.id = "p_one_frame", e.innerHTML = v, document.body.appendChild(e), a.buttonClickable = !0;
} else document.getElementById("p_one_frame").style.display = "block", a.buttonClickable = !1;
};
s.version = 2;
var d = i.one_style_2(), v = i.one_2({
style: d,
channels: h,
channelArr: u
});
p(), setTimeout(function() {
o.pop.open(), setTimeout(function() {
a.maskClickable = !0;
}, 400), setTimeout(function() {
a.buttonClickable = !0, a.init();
}, 700);
}, 0), f.report({
type: "pv"
});
}, l.resume = function() {
a.requestCallback({
status: !0,
msg: s.result
}, s.channel);
}, n.exports = l;
}), define.pack("./md5", [], function(e, t, n) {
var r = {};
r.MD5 = function(e) {
function t(e, t) {
return e << t | e >>> 32 - t;
}
function n(e, t) {
var n, r, i, s, o;
return i = e & 2147483648, s = t & 2147483648, n = e & 1073741824, r = t & 1073741824, o = (e & 1073741823) + (t & 1073741823), n & r ? o ^ 2147483648 ^ i ^ s : n | r ? o & 1073741824 ? o ^ 3221225472 ^ i ^ s : o ^ 1073741824 ^ i ^ s : o ^ i ^ s;
}
function r(e, t, n) {
return e & t | ~e & n;
}
function i(e, t, n) {
return e & n | t & ~n;
}
function s(e, t, n) {
return e ^ t ^ n;
}
function o(e, t, n) {
return t ^ (e | ~n);
}
function u(e, i, s, o, u, a, f) {
return e = n(e, n(n(r(i, s, o), u), f)), n(t(e, a), i);
}
function a(e, r, s, o, u, a, f) {
return e = n(e, n(n(i(r, s, o), u), f)), n(t(e, a), r);
}
function f(e, r, i, o, u, a, f) {
return e = n(e, n(n(s(r, i, o), u), f)), n(t(e, a), r);
}
function l(e, r, i, s, u, a, f) {
return e = n(e, n(n(o(r, i, s), u), f)), n(t(e, a), r);
}
function c(e) {
var t, n = e.length, r = n + 8, i = (r - r % 64) / 64, s = (i + 1) * 16, o = Array(s - 1), u = 0, a = 0;
while (a < n) t = (a - a % 4) / 4, u = a % 4 * 8, o[t] = o[t] | e.charCodeAt(a) << u, a++;
return t = (a - a % 4) / 4, u = a % 4 * 8, o[t] = o[t] | 128 << u, o[s - 2] = n << 3, o[s - 1] = n >>> 29, o;
}
function h(e) {
var t = "", n = "", r, i;
for (i = 0; i <= 3; i++) r = e >>> i * 8 & 255, n = "0" + r.toString(16), t += n.substr(n.length - 2, 2);
return t;
}
function p(e) {
e = e.replace(/\r\n/g, "\n");
var t = "";
for (var n = 0; n < e.length; n++) {
var r = e.charCodeAt(n);
r < 128 ? t += String.fromCharCode(r) : r > 127 && r < 2048 ? (t += String.fromCharCode(r >> 6 | 192), t += String.fromCharCode(r & 63 | 128)) : (t += String.fromCharCode(r >> 12 | 224), t += String.fromCharCode(r >> 6 & 63 | 128), t += String.fromCharCode(r & 63 | 128));
}
return t;
}
var d = Array(), v, m, g, y, b, w, E, S, x, T = 7, N = 12, C = 17, k = 22, L = 5, A = 9, O = 14, M = 20, _ = 4, D = 11, P = 16, H = 23, B = 6, j = 10, F = 15, I = 21;
e = p(e), d = c(e), w = 1732584193, E = 4023233417, S = 2562383102, x = 271733878;
for (v = 0; v < d.length; v += 16) m = w, g = E, y = S, b = x, w = u(w, E, S, x, d[v + 0], T, 3614090360), x = u(x, w, E, S, d[v + 1], N, 3905402710), S = u(S, x, w, E, d[v + 2], C, 606105819), E = u(E, S, x, w, d[v + 3], k, 3250441966), w = u(w, E, S, x, d[v + 4], T, 4118548399), x = u(x, w, E, S, d[v + 5], N, 1200080426), S = u(S, x, w, E, d[v + 6], C, 2821735955), E = u(E, S, x, w, d[v + 7], k, 4249261313), w = u(w, E, S, x, d[v + 8], T, 1770035416), x = u(x, w, E, S, d[v + 9], N, 2336552879), S = u(S, x, w, E, d[v + 10], C, 4294925233), E = u(E, S, x, w, d[v + 11], k, 2304563134), w = u(w, E, S, x, d[v + 12], T, 1804603682), x = u(x, w, E, S, d[v + 13], N, 4254626195), S = u(S, x, w, E, d[v + 14], C, 2792965006), E = u(E, S, x, w, d[v + 15], k, 1236535329), w = a(w, E, S, x, d[v + 1], L, 4129170786), x = a(x, w, E, S, d[v + 6], A, 3225465664), S = a(S, x, w, E, d[v + 11], O, 643717713), E = a(E, S, x, w, d[v + 0], M, 3921069994), w = a(w, E, S, x, d[v + 5], L, 3593408605), x = a(x, w, E, S, d[v + 10], A, 38016083), S = a(S, x, w, E, d[v + 15], O, 3634488961), E = a(E, S, x, w, d[v + 4], M, 3889429448), w = a(w, E, S, x, d[v + 9], L, 568446438), x = a(x, w, E, S, d[v + 14], A, 3275163606), S = a(S, x, w, E, d[v + 3], O, 4107603335), E = a(E, S, x, w, d[v + 8], M, 1163531501), w = a(w, E, S, x, d[v + 13], L, 2850285829), x = a(x, w, E, S, d[v + 2], A, 4243563512), S = a(S, x, w, E, d[v + 7], O, 1735328473), E = a(E, S, x, w, d[v + 12], M, 2368359562), w = f(w, E, S, x, d[v + 5], _, 4294588738), x = f(x, w, E, S, d[v + 8], D, 2272392833), S = f(S, x, w, E, d[v + 11], P, 1839030562), E = f(E, S, x, w, d[v + 14], H, 4259657740), w = f(w, E, S, x, d[v + 1], _, 2763975236), x = f(x, w, E, S, d[v + 4], D, 1272893353), S = f(S, x, w, E, d[v + 7], P, 4139469664), E = f(E, S, x, w, d[v + 10], H, 3200236656), w = f(w, E, S, x, d[v + 13], _, 681279174), x = f(x, w, E, S, d[v + 0], D, 3936430074), S = f(S, x, w, E, d[v + 3], P, 3572445317), E = f(E, S, x, w, d[v + 6], H, 76029189), w = f(w, E, S, x, d[v + 9], _, 3654602809), x = f(x, w, E, S, d[v + 12], D, 3873151461), S = f(S, x, w, E, d[v + 15], P, 530742520), E = f(E, S, x, w, d[v + 2], H, 3299628645), w = l(w, E, S, x, d[v + 0], B, 4096336452), x = l(x, w, E, S, d[v + 7], j, 1126891415), S = l(S, x, w, E, d[v + 14], F, 2878612391), E = l(E, S, x, w, d[v + 5], I, 4237533241), w = l(w, E, S, x, d[v + 12], B, 1700485571), x = l(x, w, E, S, d[v + 3], j, 2399980690), S = l(S, x, w, E, d[v + 10], F, 4293915773), E = l(E, S, x, w, d[v + 1], I, 2240044497), w = l(w, E, S, x, d[v + 8], B, 1873313359), x = l(x, w, E, S, d[v + 15], j, 4264355552), S = l(S, x, w, E, d[v + 6], F, 2734768916), E = l(E, S, x, w, d[v + 13], I, 1309151649), w = l(w, E, S, x, d[v + 4], B, 4149444226), x = l(x, w, E, S, d[v + 11], j, 3174756917), S = l(S, x, w, E, d[v + 2], F, 718787259), E = l(E, S, x, w, d[v + 9], I, 3951481745), w = n(w, m), E = n(E, g), S = n(S, y), x = n(x, b);
var q = h(w) + h(E) + h(S) + h(x);
return q.toLowerCase();
}, n.exports = r;
}), define.pack("./report", [ "./cache", "./md5" ], function(e, t, n) {
var r = e("./cache"), i = e("./md5"), s = {
seperator: "###",
limit: 5,
report_url: "https://statistics.pingxx.com/one_stats"
}, o = function(e) {
var e = e || 32, t = "ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678", n = t.length, r = "";
for (var i = 0; i < e; i++) r += t.charAt(Math.floor(Math.random() * n));
return r;
}, u = function(e, t) {
var n = new RegExp("(^|&)" + t + "=([^&]*)(&|$)", "i"), r = e.substr(0).match(n);
return r != null ? unescape(r[2]) : null;
}, a = function() {
return navigator.userAgent;
}, f = function() {
return location.host;
}, l = function() {
return localStorage ? localStorage.PPP_USER_ID == undefined ? (localStorage.PPP_USER_ID = o(32), localStorage.PPP_USER_ID) : localStorage.PPP_USER_ID : null;
}, c = function(e, t, n, r) {
var i = new XMLHttpRequest;
typeof i.timeout != "undefined" && (i.timeout = 6e3), i.open("post", e, !0), i.setRequestHeader("Content-Type", "application/json"), i.setRequestHeader("X-Pingpp-Report-Token", n), i.send(JSON.stringify(t)), i.onreadystatechange = function() {
i.readyState == 4 && r(i.responseText);
}, i.onerror = function() {}, i.ontimeout = function() {};
};
s.report = function(e) {
if (!localStorage) return;
var t = this, n = {};
n.app_id = e.app_id || r.userData.app_id, n.ch_id = e.ch_id || "", n.channel = e.channel || "", n.type = e.type || "", n.user_agent = a(), n.host = f(), n.time = (new Date).getTime();
var s = "app_id=" + n.app_id + "&channel=" + n.channel + "&ch_id=" + n.ch_id + "&host=" + n.host + "&time=" + n.time + "&type=" + n.type + "&user_agent=" + n.user_agent;
if (localStorage.PPP_ONE_STATS == undefined || localStorage.PPP_ONE_STATS.length == 0) try {
localStorage.PPP_ONE_STATS = s;
} catch (o) {} else if (localStorage.PPP_ONE_STATS.split(t.seperator).length < t.limit - 1) try {
localStorage.PPP_ONE_STATS = localStorage.PPP_ONE_STATS + t.seperator + s;
} catch (o) {} else try {
localStorage.PPP_ONE_STATS = localStorage.PPP_ONE_STATS + t.seperator + s;
var l = [], h = localStorage.PPP_ONE_STATS.split(t.seperator), p = i.MD5(h.join("&"));
for (var d in h) l.push({
app_id: u(h[d], "app_id"),
channel: u(h[d], "channel"),
ch_id: u(h[d], "ch_id"),
host: u(h[d], "host"),
time: u(h[d], "time"),
type: u(h[d], "type"),
user_agent: u(h[d], "user_agent")
});
c(t.report_url, l, p, function(e) {
localStorage.removeItem("PPP_ONE_STATS");
});
} catch (o) {}
}, n.exports = s;
}), define.pack("./success", [ "./tmpl", "./cache", "./config", "./util" ], function(e, t, n) {
var r = e("./tmpl"), i = e("./cache"), s = e("./config"), o = e("./util"), u = {};
u.init = function(e, t) {
i.userCallback = e;
if (typeof t != "function") {
o.innerCallback({
status: !1,
msg: "参数类型必须为 function"
});
return;
}
var n = "";
if (o.cookie._get("PING_APPID_ORDER") == "" || o.cookie._get("PING_APPID_ORDER") == null) n = r.success({}); else {
var a = o.cookie._get("PING_APPID_ORDER").split(encodeURIComponent(s.seperator)), f = a[0], l = a[1], c = a[2];
n = r.success({
app_id: f,
order_no: l,
amount: o.formatNumber(c)
});
}
var h = document.createElement("div");
h.id = "p_one_frame", h.innerHTML = n, document.body.appendChild(h), u.bind(t);
}, u.bind = function(e) {
o.Id("p_one_goon").addEventListener("click", function() {
e();
});
}, n.exports = u;
}), define.pack("./util", [ "./cache", "./tmpl", "./config" ], function(e, t, n) {
var r = e("./cache"), i = e("./tmpl"), s = e("./config"), o = {};
o.stringify_data = function(e, t, n) {
typeof n == "undefined" && (n = !1);
var r = [];
for (var i in e) {
if (t == "bfb_wap" && i == "url") continue;
if (t == "yeepay_wap" && i == "mode") continue;
r.push(i + "=" + (n ? encodeURIComponent(e[i]) : e[i]));
}
return r.join("&");
}, o.removeFromArray = function(e, t) {
var n = e.length, r = [];
for (var i = 0; i < n; i++) e[i] != t && r.push(e[i]);
return r;
}, o.form_submit = function(e, t, n) {
var r = document.createElement("form");
r.setAttribute("method", t), r.setAttribute("action", e);
for (var i in n) {
var s = document.createElement("input");
s.setAttribute("type", "hidden"), s.setAttribute("name", i), s.setAttribute("value", n[i]), r.appendChild(s);
}
document.body.appendChild(r), r.submit();
}, o.Id = function(e) {
return document.getElementById(e);
}, o.innerCallback = function(e) {
r.userCallback(e);
}, o.formatNumber = function(e) {
var t = e.toString(), n = t.length, r = "", i = "";
return n == 1 ? (r = "0", i = "0" + t) : n == 2 ? (r = "0", i = t) : (r = t.substring(0, n - 2), i = t.substr(n - 2)), r + "." + i;
}, o.hasClass = function(e, t) {
return e.className.match(new RegExp("(\\s|^)" + t + "(\\s|$)"));
}, o.addClass = function(e, t) {
if (!o.hasClass(e, t)) {
var n = e.className ? " " + t : t;
e.className += n;
}
}, o.removeClass = function(e, t) {
if (o.hasClass(e, t)) {
var n = new RegExp("(\\s|^)" + t + "(\\s|$)");
e.className = e.className.replace(n, "");
}
}, o.pop = {
open: function() {
o.addClass(document.body, "p_one_open"), o.addClass(document.getElementsByClassName("p_one_html")[0], "in");
},
close: function() {
o.removeClass(document.getElementsByClassName("p_one_html")[0], "in"), setTimeout(function() {
document.getElementById("p_one_frame").style.display = "none";
}, 400), o.removeClass(document.body, "p_one_open");
}
}, o.showLoading = function(e) {
var t = i.loading({
text: e
}), n = document.createElement("div");
n.id = "p_one_loading", n.innerHTML = t, document.body.appendChild(n);
}, o.hideLoading = function() {
var e = this;
e.Id("p_one_loading") && document.body.removeChild(e.Id("p_one_loading"));
}, o.storage = {
_set: function(e, t) {
var n = window.localStorage;
n.setItem(e, t);
},
_get: function(e) {
var t = window.localStorage;
return t.getItem(e);
}
}, o.cookie = {
_get: function(e) {
var t = document.cookie.match(new RegExp("(?:^|;\\s)" + e + "=(.*?)(?:;\\s|$)"));
return t ? t[1] : "";
},
_set: function(e, t) {
var n = "/";
document.cookie = encodeURIComponent(e) + "=" + encodeURIComponent(t) + "; path=" + n;
}
}, o.getToken = function(e) {}, o.isInWeixin = function() {
var e = navigator.userAgent.toLowerCase();
return e.match(/MicroMessenger/i) == "micromessenger" ? !0 : !1;
}, n.exports = o;
}), define.pack("./tmpl", [], function(e, t, n) {
var r = {
one_style_2: function(e) {
var t = [], n = function(e) {
t.push(e);
};
return t.push('    <style type="text/css">\r\n        .p_one_window *{-webkit-font-smoothing:antialiased}.p_one_window *,.p_one_window *:before,.p_one_window *:after{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}@font-face{font-family:\'pingpp_one\';src:url(data:application/x-font-ttf;charset=utf-8;base64,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GBYWHwkDCwgEBgIBAQEFBRkGBQYHAwQMCAgJAgEKDAEBAhIDFRMmAQEBAwICBQMDCAUCAgIEAwsDBQIEAQEDBAEBBgUeJQgIChL+wAQIBAQFAgECAwsHCAgFBloEAwIIBgQFBQIBBQQCAgEJCBADBgMGBQQBBQIDEwYGAQIBAQIBBQMCBRMOBwIB9AQEAQcGBQYBAQECBQMCAgEIBxACAwUCAQUEAgMBAQEBAgECAgQDBAcEBAUBAQEBAwIFAQUEEQwGATsBBQQDBwQEBQEBAQEDAgIIBAUBAQIBAQEDBQIEAgEBAg4ICwMDDQoIGhEGBAIDAwUHAgQFAQEFBQECAQECAQEBAQH+eg4YCQoJAwMDCAYFDQcKEAYGBQIDCwkBKQsVCwsDBwMKCAgJAgISCAgBAQIBAgcFAQECAQYFCgsEAQMC/o0HAggGBQUBAgECBQMBAQgIEAIBBgYBAVoPDwYECAQEBA4OBgQtBB8BAQEBRQYHDgEKCQoKAgEDBA8MEAICAgEFBAYGAwMGHh0GBAYCAQMDAgUCAgEVEhoHAwMBAQEBiB0CAREQAQIBAQMCAhwODh0DAwEBBAYCCAsDAwkGBgIBAQECAQEDAgMFAwoEAQEEAwQBAQECDQIDIgQBAQcHDgICEA4EAQEXFy8CBBASAwMgBAEBAQgHDAYICgICBAIDAgEBAgMBAQ4OAwMEAgIDAgMLCQkEAgQEBQ4dIwYCAwEBAbhPAgIQBAgFBQMDBAUJAwMDAQICDgwWAgICAQQDBwgBAQECBB4DAQIBCQgOAgIfBmUKDQEEAwYODAECAwIKCAgJAgMDDQoBAQEBBQULDAIBATACAwEBBwYPCgQEFA8IBs4HBQIEBAkFAwEDAgcEATUGBgQEBAcDAwECAgYE0gsLCwwBAQ0JDwYDAwICAggsAiwCiwUMBQUCAgMDAwYDBAMBAQIIGQIBAgEDAwQGAwMDAQECAhIIFwIDAhgC8wEBAQEGGRMDgHx8gQQWGgQEGBQEf3t7gQYKEggICQGcAQohGRkcAw0NDQ0FJB4RGAgBAQEBAxwYGBsCCAgBBwcIMysEAwEBAQEEAQYFBAwHBxUODiITAQEBAQEIBw4RAgICFBQWGwQzAQIBBQMDBQIEAgMCCAUDBAEKAgEBAQECAwMGAgscEAYHAQEBBA4bDAICFRQJCgEBAQMDAgMKBxUYBAgBBwICAQIBAgEBAQIBAQEFBQscEAcIAQEBBhEZBwMDAQEDAgMGBAMMCAgJAQEBAQEFBQYPCREUBQQBBgIGAgEBAQEBAgECAggdFBQOBwoDAgECAgEEBQYBAgIBAwEEAgMDAxUREC0eCgoBAQEBAQIBCAgCBQIDAwEBARUVAgMGCAkYDwYJBAMFAQEBAw0JChMKCQwCAgULEBAbCgUGAQEDBAICAQEHBwoiFwICAQECAwECAQIBDgECAQEBBQUNFQkFBQEBCAYbFgMDbAEKBwYSAwIFBAITAgICAgIBASAfHyMDBQYCAQEBCgUGAQEBAQEBDw0QAgICBgQCCgcIDgcIBwQEAgICAwIDAQEGATg3AgIEBAoCAwECAgIBAQICAgECCQgKDAQFAgEDAQEBAQEKBQUBAQEGBQ8CBAgEBAEBAgEGBQIIBAUBCggCBwcIBAUBCgwMAQEBAgICAgEBLy8BAQEEBwkFBgIBAgoKBQQGAwMBAQICLQQJBRABAQgIAQEICQsNAgICCgQFAQEBAQcGDQgCBAIDAQIHBxImAgQEBAwCHRARAQEBBQoFAgIBBQMEBAEBCQkDA94JCwECAQEBAQwLDAsBGCQDCQwNDAgEAgwKCwsBAgQFCQgPBgYEAgIMCQoMAnwBAQEFBAIDAwEBBQUIHAgIEgwBAQUIAwECAQEBBgYaBgMEAQICAQIBAQEGBgUGAT0XAgoLAAEAUgA9A4oDTAAyAAABNhYzMj4CNzYuAiMiDgIVFB4CFRQGFRQ2MzIeATY3PgEuASMiDgIjIi4CNzEBIQdsDgeRqIwBAjVghE5OnX5PMzwzEHcXBVWCoVJRLQsnAwehv6AFBSEiGQQCLAxYPUtCAwQyOi81YIlUVGk+HQgRWxEQVBoELEVGmoBTYHJgPk5EBwAAAAACAJgABwN/A3cAHAB2AAABDgEVFBYfAR4BMzI2PwE+ATU0Ji8BLgEjIgYPATciBhUUFhceAxUUDgIjIi4CNTQ2Nz4BNz4BNz4BNzI2NS4BIyIGBw4BIyImJy4BJzAmIyIGFR4BFzIWFRQGBw4DFRQeAjMyPgI1NC4CIyIGBwGeAQICAWUCBAMCBAJlAgICAmUCBAIDBAJlhwICAwIsTDcgJEBVMDFVQCQsIC99QwULAy9jNAQGATYmCRAJSIFIMF8tEyQRAgEDAw1WPAICAQEnQC0YOmWITU2HZTooR142FywUAYQCBAMCBAJlAgICAmUCBAIDBAJlAgICAmXsBAICAwEFKD5QLjBWPyUlP1YwN2EiNFEdAgUBExkGBwQmNAMDGRkMDAUQCwEEAz9eEwMCAgMBGERQXDJNh2Y6OmaHTTZfRikHCAAAAQBFAAUDuwN7AAwAAAkBBwkBJwkBNwkBFwECKwGQK/5w/nArAZD+cCsBkAGQK/5wAcD+cCsBkP5wKwGQAZAr/nABkCv+cAAAAgBAADwDwANEABEAHQAAASMuAScuASMiBgcOAQcjAyEDJTIeAhchPgMzA2EuBRgWH3pnZ3oeFhcGMF4DgF/+nz5XOiEI/hEIITpWPgJpETYdKU5OKR02Ef3TAi2gIzE3FRU3MSMAAAACAAX/uQP7A7AAFAAbAAABIg4CFRQeAjMyPgI1NC4CIwMnNxcBFwECAGm5ik9PirlpabmJUFCJuWlJ2UiRAShD/pUDr0+KuWlpuYlQUIm5aWm5ik/9CNlIkAFkQ/5OAAAQABUAegPaAwYADAAaACgARwBYAGkAhQCTAJgArgC3AMEAygDUAN0BAAAAAQcnIxUzNRc3FTM1IwUiJjU0NjMyFhUUBiMxNSIGFRQWMzI2NTQmIzEHDgEjIiY1NDYzMhYfATcnLgEjIgYVFBYzMjY/AScHBQ4BKwE1MzIWFx4BFRQGBzE3LgErARUzMjY3PgE1NCYnMQcUBgcOAQcOAQcOASsBFTMwMjEyNjc+AT0BIxUhIgYVFBYzMjY1NCYjMRMhNSEVFzUhFTMVFAYHDgEjFTI2Nz4BPQEzMScjFSMVITUjNQMzPgExIw4BBzElMBYXMy4BJyMXMz4BMSMOAQcxJTAWFzMuAScjJzcjByMVMwczFRQGBw4BIxUyNjc+AT0BMzUjNSMVIzchNSEDsz49IB8+Ph8f/u4aJycaGyYmGyg5OSgoOTkohgkWDBsmJhsMFgkEFgQNIhIoOTkoEiIOAxYE/tsKHhMSEhMeCgoKCgoUDikaKCgaKQ4ODg4OvgEBAgQDBAkFBQ0HAQEBFR4KCgocAREKDg4KCg8PCj/+3wEhO/5qrAUFBQ8KGicMDAytrT27AbS8+CI4JkMGJBMBNCY3IxMkBkPAIzcmQwYkEwE0JjcjEyQGQ5sMPQxcTD2xBQUFDwoaJgwMDaioPWMtASr+5QEzc3OxcHBwcLGaJhsbJiYbGyaiOCgoOTkoKDiRCAgmGxomCAgDFwMMDTgoKDkNDAQWAwUJCZMKCQgdEhIcCX8MDMIMDQwlFxglDF4KDgQEBwIDBQEBAhcICQgaEX11DgoKDg4KCg4BoykpIm1taA4QBAQFIAwKCyAWXL8aIiIa/o8wazBQG5trMBtQMJswazBQG5trMBtQMLwaGiKDaA8PBAQFIAwKCyAWXCJISGEiAAAAABf//wCqA/cC1wA0AFoAYgCmALMAwgDPAOoA+QEUASMBQgFOAVsBfQGMAaoByAHXAf4CDQIcAnEAAAE3IwczByM3NiYrATc0JisBByMHMwcjBzMOAQ8BMjY3MwcGFjMyNj8BDgEHBiY1NzM3IzczJTMHMBY7ATczMhYVAyM3IyImPwEwNjc+ATsBBw4BKwEiBgcOARUFIzczMhYPAQEyNjUUBiciJjc+AxceAQcOASMiJicuATEjNzM0NjUjNzM+ATEzMhYVMAYHMwcjDgEHMwcjFR4BFxY2Jy4BBwYWMxMiJjU0NjMyFhUUBiMHNyMiNjc+ATEjBwYWOwEHIzAGFx4BMzcuAT8BBQ4BJyImNz4BNx4BBw4BByImNzQ2Mz4BNzkBIzI2NzYmJyIGBxQWMzkBMw4BJyImNz4BNx4BFQ4BByImNzQ2Mz4BNzkBIzI2NzYmJyIGBxQWMzkBFzc8ATc0NjsBMhYVDgEHMR4BFQ4BKwEiJjU+ATU5ATcHBhY3MjY3NCYnBzcHPgE3NCYnIgYXOQEXIyImNTQ2MTc0JicuATU0NjczPgEzNhYPATAGFRQGIzkBNzIWFRQGIyImNyY2MzkBFyMiJjU0NjE3NCYnLgE1PgE3MhYPATAGFRYGIzkBKwEiJjU0NjE3NCYnLgE1PgE3MhYPATAGFRYGIzkBFyImNT4BNx4BFQ4BIzkBNz4BNx4BFw4BIyImJy4BIw4BBxQWFzI2Nz4BMTIUFQ4BBy4BNzkBNw4BBy4BNT4BNx4BFTkBIwYWMz4BNzYmJw4BBzkBFyMiJjU8AT8BNCYnDgEPARwBBxQGKwEiJjU0NjU3NCYnDgEPARQGFRQGKwEiJjU0NjU3NCYnLgExPgE3NhYHMT4BMzIWFz4BMx4BHQEUBhUUBiM5AQKnA7cDKAJYCAEVD1AEBAM9BFUDVQhVA1ENOhQCGHEW0gkCFBEgQQsBCyENCAkHSgNKA0z9lgIEAwcaDjYDBB0+Cz8OEAIEBAQFNl2XAQEGCHxAJgQDBAE9MQgeCAoBBgHpSUtoSTJZDQc7TVIfEhkHCTclKT0YIAgnBCUCJQMoBAc6BAMHBXsDgAEBAYEEfwIzLzE4FhNZLksrSEgPFRUPDhUVDv8CYQoBAgINRRgFBA6n80QDBApCFwIIIAIE/r0GCgQNDQECERAQEAEEHBgFBAEDAw8SBBAICQEBBgcHCAIFBlsGCgQNDQECERAQDwQdGAQEAQMDDxIEEAgJAQEHBgcIAgYFLwUBAwMjDQwBCwsMDAERECMDAwEBEwIBBQYNDQEICBMEAg8RAQkJCAYBRgYDAgEDAgIBAQIBAQUHAgICAQMBAQMEBAQFBQUEAQEGBTwHAgIBBgICAQECCQcCAgEGAgECAiAGAwIBBgICAQECCQcCAgEGAgECAj8FBAEFBAQFAQUEGAMQDwoKAQEDAwIDAQIDAwYJAQcIBAcEAQMCAw0JEA4BfAMRDw4PAhEQDg8xAQcICAgCAQgGCAkCjgYDAgEDBAUGBwECAQMBBwICAQIEBQYGAQIBAwIGAwIBAwICAQEBBwgCAgEDCgcFCAIECgcJCgECAwISISEdWAwRKgMEMSFUISc/CxJMN2cQDRgFEwIHAQEHCU8hHQ4jCKUEBP6/gxIPLCsaHVQGBAVPGxYkBCtUCQZF/ttbGztiAVFLMEgoAxUNLBQYHR8gJlwhBQwFIRUaBAQWEiEFDAUhFDhRBQYkHRsDKkaJAakVDg8VFQ8OFTAgCAQEITQKE4IjECkaEgIbHyjeAwQBDQ0ODgIBEhIYGwEBAQECAREQCgoLCgELCwoKAwQBDQ0ODgIBEhIYGwEBAQECAREQCgoLCgELCwoKGz4CBgECAgoJCAwCAQoKDQwBAgEFAxoWBQUBCAkHCQEDHxcCDAgFBQEFBUUBAgEIHgICAQEBAQECAQICAQUFJQgBAgFPBQQEBQUEBAVPAQIBCD4CAgEBAgEDAwEEBEYIAQIBAQIBCD4CAgEBAgEDAwEEBEYIAQIBAgUDAwUBAQQEAwUcDg8CAQYFAwMCBAMDAQsMCQoBAwMBAgIBBwcBAQ4NAQ4OAQEPDQ4OAgEPDgsLAQoLCwsBAQsLGwECAQUDGgUFAQEFBBoDBQECAQECAQUDGgUFAQEFBBoDBQECAQECAQUDHAMDAQECAwMBAQUGBQUFBQUFAQkJHAMFAQIBAAAAAwBHACsDtQNdABsANwB5AAATND4CNyoBIyIOAhUUHgIzOgEzLgM1MQEqASMeAxUUDgIHOgEzMj4CNTQuAiMxAzAGFxY2MzcwNicwLgIPATAOAhcwFhcwHgIXMBY3MD4CNzA0NTAmBzAGBzAGJzAmJzAmNzA2FzAWFzAWDwGjQW+UVQgFCGOvgkxMgq9jCAUIVZRvQQGtCA8IQW1PKytPbUEIDwhNgmA2NmCCTR8PDwgaB3sPDyI/WzmFLSoPHxwXJzEvB3g2MDs0BBQfRRc1JjZFIRdoJyEIDw8+AcRQkW9FBEFvlVRVlW9ABERvkVEBUQQ5XHdBQnZcOgQ2XHtFRXpcNv7NGRAPBWYSFy0pCiIzGjBEKhoPISgkBB49MT88DA8QFB9PFykfIUUpH0ceDQgXCDMAAAAAAQAAAAEAACeaAPNfDzz1AAsEAAAAAADR22x1AAAAANHbbHX///+5A/sDsAAAAAgAAgAAAAAAAAABAAADwP/AAAAEAP//AAAD+wABAAAAAAAAAAAAAAAAAAAADgQAAAAAAAAAAAAAAAIAAAAEAAAUA/8AJAQAAFIEAACYBAAARQQAAEAEAAAFBAAAFQQA//8EAABHAAAAAAAKABQAHgDEBZQF3gaCBqYG2gcKCF4LmAw0AAAAAQAAAA4DmAAXAAAAAAACAAAAAAAAAAAAAAAAAAAAAAAAAA4ArgABAAAAAAABAAoAAAABAAAAAAACAAcAewABAAAAAAADAAoAPwABAAAAAAAEAAoAkAABAAAAAAAFAAsAHgABAAAAAAAGAAoAXQABAAAAAAAKABoArgADAAEECQABABQACgADAAEECQACAA4AggADAAEECQADABQASQADAAEECQAEABQAmgADAAEECQAFABYAKQADAAEECQAGABQAZwADAAEECQAKADQAyHBpbmdwcF9vbmUAcABpAG4AZwBwAHAAXwBvAG4AZVZlcnNpb24gMS4wAFYAZQByAHMAaQBvAG4AIAAxAC4AMHBpbmdwcF9vbmUAcABpAG4AZwBwAHAAXwBvAG4AZXBpbmdwcF9vbmUAcABpAG4AZwBwAHAAXwBvAG4AZVJlZ3VsYXIAUgBlAGcAdQBsAGEAcnBpbmdwcF9vbmUAcABpAG4AZwBwAHAAXwBvAG4AZUZvbnQgZ2VuZXJhdGVkIGJ5IEljb01vb24uAEYAbwBuAHQAIABnAGUAbgBlAHIAYQB0AGUAZAAgAGIAeQAgAEkAYwBvAE0AbwBvAG4ALgAAAAMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=) format("truetype");font-weight:normal;font-style:normal}.p_one_icon_jdpay:before{content:"\\e607"}.p_one_icon_alipay:before{content:"\\e600"}.p_one_icon_unionpay:before{content:"\\e601"}.p_one_icon_wechat:before{content:"\\e602"}.p_one_icon_baidu:before{content:"\\e603"}.p_one_icon_yeepay:before{content:"\\e616"}.p_one_icon_kuaiqian:before{content:"\\e608"}.p_one_icon_close:before{content:"\\e604"}.p_one_icon_bag:before{content:"\\e605"}.p_one_icon_done:before{content:"\\e606"}.p_one_mask{overflow:hidden;position:fixed;top:0;right:0;bottom:0;left:0;z-index:2147483646;background-color:transparent;transition:background-color 0.4s;-webkit-transition:background-color 0.4s;-moz-transition:background-color 0.4s;-o-transition:background-color 0.4s;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.p_one_window{overflow:hidden;position:fixed;min-height:52px;width:100%;right:0;bottom:0;left:0;z-index:2147483647;color:#0e2026;font:16px/2 "Helvetica Neue","Helvetica","Hiragino Sans GB","Heiti SC",STHeiti;font-weight:normal;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;-webkit-transform:translate3d(0, 0, 0);-moz-transform:translate3d(0, 0, 0);-o-transform:translate3d(0, 0, 0);transform:translate3d(0, 0, 0)}.p_one_window .p_one_html{-webkit-transform:translate3d(0, 100%, 0);-moz-transform:translate3d(0, 100%, 0);-o-transform:translate3d(0, 100%, 0);transform:translate3d(0, 100%, 0);-webkit-transition:-webkit-transform 0.4s;-moz-transition:-moz-transform 0.4s;-o-transition:-o-transform 0.4s;transition:transform 0.4s;-webkit-transition:transform .4s}.p_one_window .p_one_html.in{-webkit-transform:translate3d(0, 0, 0);-moz-transform:translate3d(0, 0, 0);-o-transform:translate3d(0, 0, 0);transform:translate3d(0, 0, 0)}.p_one_window .p_one_body{position:relative;padding:10px}.p_one_window .p_one_channel{text-align:center;-webkit-border-radius:15px;-moz-border-radius:15px;border-radius:15px;overflow:hidden;-webkit-tap-highlight-color:transparent}.p_one_window .p_one_btn{display:inline-block;width:100%;cursor:pointer;padding:7px 13px;border-bottom:1px solid rgba(225,225,225,0.7);line-height:2.4;background-color:rgba(247,247,247,0.98);-webkit-tap-highlight-color:transparent;transition:background-color 0.15s;-webkit-transition:background-color 0.15s;-moz-transition:background-color 0.15s;-o-transition:background-color 0.15s}.p_one_window .p_one_btn:active{background-color:rgba(235,235,235,0.98)}.p_one_window .p_one_btn:last-child{border-bottom:none}.p_one_window [class^="p_one_icon_"]{position:relative;display:inline-block;width:100px;padding-left:33px;white-space:nowrap;text-align:left}.p_one_window [class^="p_one_icon_"]:before{position:absolute;left:0;top:8px;font-size:22px;line-height:1;font-family:\'pingpp_one\'}.p_one_window.p_one_default .p_one_body{padding:0}.p_one_window.p_one_default .p_one_channel{-webkit-border-radius:0;-moz-border-radius:0;border-radius:0}.p_one_window.p_one_default .p_one_btn{border-bottom:none}body.p_one_open{overflow:hidden;position:relative}body.p_one_open .p_one_window{display:block}body.p_one_open .p_one_mask{background-color:rgba(0,0,0,0.4)}\r\n    </style>'), t.join("");
},
one_2: function(e) {
var t = [], n = function(e) {
t.push(e);
};
t.push("    "), n(e.style), t.push('    <div id="p_one_mask" class="p_one_mask"></div>\r\n    <div class="p_one_window'), n(navigator.userAgent.toLowerCase().match("iphone") ? "" : " p_one_default"), t.push('">\r\n        <div class="p_one_html">\r\n            <div class="p_one_body">\r\n                <div id="p_one_channelList" class="p_one_channel">');
for (var r = 0; r < e.channelArr.length; r++) t.push("                    "), e.channelArr[r] == "alipay_wap" && t.push('                    <div p_one_channel="alipay_wap" class="p_one_btn"><div class="p_one_icon_alipay">支付宝</div></div>'), t.push("                    "), e.channelArr[r] == "wx_pub" && t.push('                    <div p_one_channel="wx_pub" class="p_one_btn"><div class="p_one_icon_wechat">微信支付</div></div>'), t.push("                    "), e.channelArr[r] == "upacp_wap" && t.push('                    <div p_one_channel="upacp_wap" class="p_one_btn"><div class="p_one_icon_unionpay">银联支付</div></div>'), t.push("                    "), e.channelArr[r] == "upmp_wap" && t.push('                    <div p_one_channel="upmp_wap" class="p_one_btn"><div class="p_one_icon_unionpay">银联支付</div></div>'), t.push("                    "), e.channelArr[r] == "bfb_wap" && t.push('                    <div p_one_channel="bfb_wap" class="p_one_btn"><div class="p_one_icon_baidu">百度钱包</div></div>'), t.push("                    "), e.channelArr[r] == "jdpay_wap" && t.push('                    <div p_one_channel="jdpay_wap" class="p_one_btn"><div class="p_one_icon_jdpay">京东支付</div></div>'), t.push("                    "), e.channelArr[r] == "yeepay_wap" && t.push('                    <div p_one_channel="yeepay_wap" class="p_one_btn"><div class="p_one_icon_yeepay">易宝支付</div></div>'), t.push("                    ");
return t.push("                </div>\r\n            </div>\r\n        </div>\r\n    </div>"), t.join("");
},
success: function(e) {
var t = [], n = function(e) {
t.push(e);
};
return t.push('    <div style="position: fixed; top: 0;left: 0;bottom: 0;right: 0; margin: auto;font-size: 18px; line-height: 2; text-align: center; background-color: #fff;">\r\n        <div style="position: absolute;width: 100px;height: 200px; margin: auto; left: 0; top: 0; right: 0;bottom: 0;">\r\n            <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+Cjxzdmcgd2lkdGg9IjY0cHgiIGhlaWdodD0iNjRweCIgdmlld0JveD0iMCAwIDY0IDY0IiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPgogICAgPHRpdGxlPjwvdGl0bGU+CiAgICA8ZyBzdHJva2U9Im5vbmUiIHN0cm9rZS13aWR0aD0iMSIgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj4KICAgICAgICA8cGF0aCBkPSJNMzIsNjQgQzQ5LjY3MzExMiw2NCA2NCw0OS42NzMxMTIgNjQsMzIgQzY0LDE0LjMyNjg4OCA0OS42NzMxMTIsMCAzMiwwIEMxNC4zMjY4ODgsMCAwLDE0LjMyNjg4OCAwLDMyIEMwLDQ5LjY3MzExMiAxNC4zMjY4ODgsNjQgMzIsNjQgWiBNMjcuMjUsMzguNjAyNSBMMjAuNjQ3NSwzMiBMMTguNDA3MDgzMywzNC4yNDA0MTY3IEwyNy4yNSw0My4wODMzMzMzIEw0Ni4yNSwyNC4wODMzMzMzIEw0NC4wMDk1ODMzLDIxLjg0MjkxNjcgTDI3LjI1LDM4LjYwMjUgWiIgaWQ9Ik92YWwtMzYiIGZpbGw9IiM0N0NDQkIiPjwvcGF0aD4KICAgIDwvZz4KPC9zdmc+" alt="">\r\n            <div style="color: #47ccba;">支付成功</div>\r\n        </div>\r\n        <div style="position: absolute;width: 100%;bottom: 75px;">\r\n            <a href="#" style="display: block; margin: 0 15px;padding: 7px;border-radius:3px; background-color: #47ccba; color: #fff;text-decoration: none;" id="p_one_goon">完成</a>\r\n        </div>\r\n        <div style="position: absolute;width: 100%;bottom: 15px; color: #76858c; font-size: 14px;">支付体验由 Ping++ 提供</div>\r\n    </div>'), t.join("");
},
loading: function(e) {
var t = [], n = function(e) {
t.push(e);
};
return t.push('    <div style="position: fixed;top: 0;right: 0;bottom: 0;left: 0;background-color: rgba(0,0,0,.4);z-index: 2147483647;">\r\n        <div style="position: absolute;width: 150px;height: 105px;top: 0;right: 0;bottom: 0;left: 0;margin: auto;color: #fff;text-align: center;">\r\n            <img src="data:image/svg+xml;base64,PCEtLSBCeSBTYW0gSGVyYmVydCAoQHNoZXJiKSwgZm9yIGV2ZXJ5b25lLiBNb3JlIEAgaHR0cDovL2dvby5nbC83QUp6YkwgLS0+Cjxzdmcgd2lkdGg9IjQ0IiBoZWlnaHQ9IjQ0IiB2aWV3Qm94PSIwIDAgNDQgNDQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgc3Ryb2tlPSIjZmZmIj4KICAgIDxnIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCIgc3Ryb2tlLXdpZHRoPSIyIj4KICAgICAgICA8Y2lyY2xlIGN4PSIyMiIgY3k9IjIyIiByPSIxIj4KICAgICAgICAgICAgPGFuaW1hdGUgYXR0cmlidXRlTmFtZT0iciIKICAgICAgICAgICAgICAgIGJlZ2luPSIwcyIgZHVyPSIxLjhzIgogICAgICAgICAgICAgICAgdmFsdWVzPSIxOyAyMCIKICAgICAgICAgICAgICAgIGNhbGNNb2RlPSJzcGxpbmUiCiAgICAgICAgICAgICAgICBrZXlUaW1lcz0iMDsgMSIKICAgICAgICAgICAgICAgIGtleVNwbGluZXM9IjAuMTY1LCAwLjg0LCAwLjQ0LCAxIgogICAgICAgICAgICAgICAgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiIC8+CiAgICAgICAgICAgIDxhbmltYXRlIGF0dHJpYnV0ZU5hbWU9InN0cm9rZS1vcGFjaXR5IgogICAgICAgICAgICAgICAgYmVnaW49IjBzIiBkdXI9IjEuOHMiCiAgICAgICAgICAgICAgICB2YWx1ZXM9IjE7IDAiCiAgICAgICAgICAgICAgICBjYWxjTW9kZT0ic3BsaW5lIgogICAgICAgICAgICAgICAga2V5VGltZXM9IjA7IDEiCiAgICAgICAgICAgICAgICBrZXlTcGxpbmVzPSIwLjMsIDAuNjEsIDAuMzU1LCAxIgogICAgICAgICAgICAgICAgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiIC8+CiAgICAgICAgPC9jaXJjbGU+CiAgICAgICAgPGNpcmNsZSBjeD0iMjIiIGN5PSIyMiIgcj0iMSI+CiAgICAgICAgICAgIDxhbmltYXRlIGF0dHJpYnV0ZU5hbWU9InIiCiAgICAgICAgICAgICAgICBiZWdpbj0iLTAuOXMiIGR1cj0iMS44cyIKICAgICAgICAgICAgICAgIHZhbHVlcz0iMTsgMjAiCiAgICAgICAgICAgICAgICBjYWxjTW9kZT0ic3BsaW5lIgogICAgICAgICAgICAgICAga2V5VGltZXM9IjA7IDEiCiAgICAgICAgICAgICAgICBrZXlTcGxpbmVzPSIwLjE2NSwgMC44NCwgMC40NCwgMSIKICAgICAgICAgICAgICAgIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiAvPgogICAgICAgICAgICA8YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPSJzdHJva2Utb3BhY2l0eSIKICAgICAgICAgICAgICAgIGJlZ2luPSItMC45cyIgZHVyPSIxLjhzIgogICAgICAgICAgICAgICAgdmFsdWVzPSIxOyAwIgogICAgICAgICAgICAgICAgY2FsY01vZGU9InNwbGluZSIKICAgICAgICAgICAgICAgIGtleVRpbWVzPSIwOyAxIgogICAgICAgICAgICAgICAga2V5U3BsaW5lcz0iMC4zLCAwLjYxLCAwLjM1NSwgMSIKICAgICAgICAgICAgICAgIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiAvPgogICAgICAgIDwvY2lyY2xlPgogICAgPC9nPgo8L3N2Zz4=" width="50" height="50">\r\n            <!--<div>'), n(e.text), t.push("</div>-->\r\n        </div>\r\n    </div>"), t.join("");
}
};
return r;
});