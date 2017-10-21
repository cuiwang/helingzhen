!function(a, b) {
    "function" == typeof define && define.amd ? define(b) : "undefined" == typeof document ? module.exports = b() : a.TmplInline_peopleList = b()
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
        var e = c("list")
          , f = c("admin_ext")
          , g = "";
        g += "";
        for (var h = 0; h < e.length; h++) {
            var i = e[h]
              , j = "";
            j = 1 == i.gender ? " male" : " female",
            g += ' <li class="focusList link_person_profile" data-profile-uin="',
            g += d(i.uin),
            g += '"> <a> <img src="',
            g += d(i.pic),
            g += '" class="list-img"> <div class="list-content"> <strong class="name">',
            g += d(i.nick_name),
            g += '</strong> <i class="sex-icon',
            g += d(j),
            g += '"></i> <p class="msg"> 话题:<span style="margin-right: 5px;">',
            g += d(i.threads),
            g += "</span> 部落:<span>",
            g += d(i.follow_bar),
            g += "</span> </p> </div> ",
            f && (g += ' <a class="delete-second-comment ',
            g += d(f ? "isAdmin" : ""),
            g += '" href="javascript:void(0)" title="删除"></a> '),
            g += " </a> </li> "
        }
        return g += " "
    }
    ;
    return a.normal = "TmplInline_peopleList.normal",
    Tmpl.addTmpl(a.normal, b),
    a
});