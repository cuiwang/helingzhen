!function(a, b) {
    "function" == typeof define && define.amd ? define(b) : "undefined" == typeof document ? module.exports = b() : a.TmplInline_ad = b()
}(this, function() {
    var a = {}
      , b = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("type")
          , f = c("uiType")
          , g = c("title")
          , h = c("desc")
          , i = c("imgHandle")
          , j = c("img")
          , k = c("document")
          , l = c("reply")
          , m = c("imgHeight")
          , n = c("style")
          , o = c("starHtml")
          , p = c("wording")
          , q = "";
        if (q += "",
        "barindex" === e) {
            if (q += " ",
            1 === f)
                q += ' <div class="detail-text-content haspic"> <div class="text-container"> <h3 class="text">',
                q += d(g),
                q += '<span class="list-ad-mark-top">广告</span></h3> <div class="list-content">',
                q += d(h),
                q += '</div> </div> <div class="act-img img-gallary img-ph"> <img class="ad-img" src="',
                q += d(i.getThumbUrl(j)),
                q += '" style="width:',
                q += d(j.width),
                q += "px; height:",
                q += d(j.height),
                q += "px; margin-left:",
                q += d(j.marginLeft),
                q += "px; margin-top:",
                q += d(j.marginTop),
                q += 'px;" /> </div> </div> ';
            else if (2 === f) {
                (k.documentElement.clientWidth || k.body.clientWidth) - 30;
                q += ' <div class="detail-text-content haspic"> <div class="report-content"> <h3 class="grouptitle feed-two-line"><span class="ad-mark-new">广告</span>',
                q += d(g),
                q += '</h3> <div class="groupbrief feed-two-line">',
                q += d(h),
                q += "</div> </div> ";
                var r = window.feedRenderTool.getAdFeeds(j);
                q += ' <div class="img-wrap clearfix"> ';
                for (var s = 0; s < r.length; s++)
                    q += ' <div class="img-ph feed-img ',
                    q += d(l ? "report" : ""),
                    q += '" style="',
                    q += d(r[s].styleStr),
                    q += '"> <img hidebg="true" noSize="',
                    q += d(r[s].noSize),
                    q += '" src="',
                    q += d(r[s].picList[s].url),
                    q += '" style="width:100%;height:100%;"/> </div> ';
                q += " </div> </div> "
            } else
                q += ' <div class="gdt-ad-wrapper"> <div class="detail-text-content haspic"> <div class="text-container"> <h3 class="text">',
                q += d(g),
                q += '</h3> <div class="list-content">',
                q += d(h),
                q += '</div> </div> <div class="act-img img-gallary img-ph"> <img class="ad-img" src="',
                q += d(i.getThumbUrl(j)),
                q += '" style="width:',
                q += d(j.width),
                q += "px; height:",
                q += d(j.height),
                q += "px; margin-left:",
                q += d(j.marginLeft),
                q += "px; margin-top:",
                q += d(j.marginTop),
                q += 'px;" /> </div> <span class="ad-ribbon">广告</span> </div> </div> ';
            q += " "
        } else
            ("post" === e || "recommend" === e) && (q += " ",
            j = i.formatThumb(j, !0, 80, 80),
            j = j[0],
            q += ' <div class="ad-banner-img-wrapper img-ph" style="height: ',
            q += d(m),
            q += 'px"> <img src="',
            q += d(i.getThumbUrl(j)),
            q += '" class="ad-banner-img"/> <div class="ad-tag"></div> </div> <div class="ad-banner-wording-wrapper"> ',
            n && o ? (q += ' <p class="app-stars">',
            q += d(o),
            q += '</p> <div class="app-title">今日热门推荐</div> ') : (q += ' <div class="wording">',
            q += d(p[0]),
            q += "</div> "),
            q += ' <div class="ad-btn">',
            q += d(p[1]),
            q += "</div> </div> ");
        return q += " "
    }
    ;
    return a.ad = "TmplInline_ad.ad",
    Tmpl.addTmpl(a.ad, b),
    a
});