!function(e, i) {
                "function" == typeof define && define.amd ? define(i) : "undefined" == typeof document ? module.exports = i() : e.TmplInline_detail_header = i()
            } (this,
            function() {
                var e = {},
                i = function(e, i) {
                    function n(i) {
                        return a("undefined" == typeof e[i] ? this[i] : e[i])
                    }
                    function a(e) {
                        return "undefined" == typeof e ? "": e
                    }
                    e = e || {};
                    var t = n("RegExp"),
                    d = n("keyword"),
                    l = (n("match"), n("pos"), n("str"), n("img"), n("a"), n("span"), n("user_info")),
                    s = n("isOfficialAccountTribe"),
                    r = n("uin"),
                    o = n("bid"),
                    c = n("title"),
                    p = (n("g"), n("time_str")),
                    u = n("bar_name"),
                    v = "";
                    v += "";
                    var f = /Android.*HM/.test(window.navigator.userAgent) ? "": "allow-copy",
                    m = new t(d, "gmi"),
                    _ = function(e, i, n) {
                        return /(<img)|(<a)/.test(n) ? e: ['<span class="keyword-match">', e, "</span>"].join("")
                    };
                    return v += ' <div class="user-info section-1px js-user-info" data-profile-uin="',
                    v += a(l.uin),
                    v += '"> <div class="user-avatar" data-profile-uin="',
                    v += a(l.uin),
                    v += '"> <img src="',
                    v += a(l && l.pic ? l.pic: "http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/default-avatar.png"),
                    v += '"/> </div> <div class="name-wrap ',
                    v += a(l.longnick ? "": "center"),
                    v += "\"> <div class='name-section1'> <span class=\"author user-nick ",
                    v += a(l.vipno && !s ? " user-nick-vipno": ""),
                    v += '" data-profile-uin="',
                    v += a(l.uin),
                    v += '"> ',
                    (r + "").indexOf("*") > -1 ? (v += " ", v += a(l.nick_name), v += "(", v += a(r), v += ") ") : (v += " ", v += a(l.nick_name), v += " "),
                    v += " </span> ",
                    !s && l.level ? (v += "<span id='lz_level' class='prevent_default l-level lv", v += a(l.level), v += "'>LV.", v += a(l.level), v += " ", v += a(l.level_title), v += "</span>&nbsp;") : v += "<span id='lz_level' class='prevent_default hide' ></span> ",
                    !s && i && i.renderHonours && (v += a(i.renderHonours(l, o)), v += " "),
                    v += ' </div><div class="user-sign">',
                    v += a(l.longnick || ""),
                    v += '</div> </div> <div class="arrow-right"></div> </div> <div class="post-title js-detail-title ',
                    v += a(f),
                    v += '"> ',
                    v += a((d ? c.replace(m, _) : c).replace(/<br>/g, "&lt;br&gt;")),
                    v += ' </div> <div class="post-title-info"> <div class="time">',
                    v += a(p || ""),
                    v += '</div> <div class="detail-from"> <div>',
                    v += a(u),
                    s || (v += "部落"),
                    v += "</div> </div> </div> "
                };
                return e.top_detail_header = "TmplInline_detail_header.top_detail_header",
                Tmpl.addTmpl(e.top_detail_header, i),
                e
            });