!function() {
    window.honourHelper = {
        renderHonours: function(a, b) {
            var c, d, e = a.admin_ext, f = a["continue"], g = '<span class="honour vip-icon" data-url="http://buluo.qq.com/xylm/business/personal_center/center.html?from=exp_icon"></span>', h = "", i = "";
            return a.vipno && (i = "&nbsp;",
            h = g),
            8 === (8 & e) ? (c = a.title || "大明星",
            h += i + '<span class="honour vip" data-report-action="star" data-url="http://buluo.qq.com/mobile/hof.html?bid=' + b + '&type=admin&from=icon">' + c + "</span>") : 2 === (2 & e) ? (d = a.owner ? "首席酋长" : "大酋长",
            h += i + '<span class="honour admin" data-report-action="big" data-url="http://buluo.qq.com/mobile/hof.html?bid=' + b + '&type=admin&from=icon">' + d + "</span>") : 4 === (4 & e) ? h += i + '<span class="honour border-1px admin" data-report-action="small" data-url="http://buluo.qq.com/mobile/hof.html?bid=' + b + '&type=admin&from=icon">小酋长</span>' : 256 === (256 & e) ? h += i + '<span class="honour admin xiaobian" data-report-action="xiaobian" data-url="http://buluo.qq.com/mobile/hof.html?bid=' + b + '&type=admin&from=icon">小编</span>' : 2048 === (2048 & e) ? h += i + '<span class="honour rich" data-report-action="tuhao" data-url="http://buluo.qq.com/mobile/hof.html?bid=' + b + '&type=rich&from=icon">土豪</span>' : 1024 === (1024 & e) ? h += i + '<span class="honour expert" data-report-action="daren" data-url="http://buluo.qq.com/mobile/hof.html?bid=' + b + '&type=expert&from=icon">达人</span>' : f >= 7 && (h += i + '<span class="honour fans" data-report-action="iron" data-url="http://buluo.qq.com/mobile/hof.html?bid=' + b + '&type=fans&from=icon">铁杆粉</span>'),
            h
        },
        renderPoster: function(a, b) {
            var c, d = arguments.length;
            return 1 === d ? c = !!a : 2 === d && (c = a && b ? a === b : !1),
            c ? '<span class="honour poster">楼主</span>' : ""
        },
        isAdmin: function(a) {
            return 1 === (1 & a) || 2 === (2 & a) || 4 === (4 & a) || 32 === (32 & a)
        },
        renderVip: function(a) {
            var b = '<span class="vip-icon" data-url="http://buluo.qq.com/xylm/business/personal_center/center.html?from=exp_icon"></span>';
            return 1 === a ? b : ""
        }
    }
}();