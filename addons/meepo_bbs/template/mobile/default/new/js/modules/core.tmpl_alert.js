!function(a, b) {
    "function" == typeof define && define.amd ? define(b) : "undefined" == typeof document ? module.exports = b() : a.TmplInline_alert = b()
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
        var e = c("theme")
          , f = c("title")
          , g = c("tplType")
          , h = c("content")
          , i = c("confirmAtRight")
          , j = c("cancel")
          , k = c("confirm")
          , l = "";
        return l += '<div class="frame ',
        l += d(e),
        l += '"> <h3 class="title">',
        l += d(f),
        l += "</h3> ",
        "basic" === g ? (l += " ",
        l += d(h),
        l += " ") : (l += ' <p class="content">',
        l += d(h),
        l += "</p> "),
        l += ' <div class="btn-group section-1px"> ',
        i ? (l += " ",
        j && (l += ' <button class="btn">',
        l += d(j),
        l += "</button> "),
        l += ' <button class="btn" id="confirm-btn">',
        l += d(k),
        l += "</button> ") : (l += ' <button class="btn" id="confirm-btn">',
        l += d(k),
        l += "</button> ",
        j && (l += ' <button class="btn">',
        l += d(j),
        l += "</button> "),
        l += " "),
        l += " </div> </div> "
    }
    ;
    a.frame = "TmplInline_alert.frame",
    Tmpl.addTmpl(a.frame, b);
    var c = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("title")
          , f = c("placeholder")
          , g = c("content")
          , h = c("confirm")
          , i = c("cancel")
          , j = "";
        return j += '<div class="frame a-forwards" > <h3 class="split-title">',
        j += d(e),
        j += '</h3> <div class="a-edit-wrapper"> <div class="a-edit-border border-1px"> <textarea spellcheck="false" class="edit" placeholder="',
        j += d(f),
        j += '" type=\'text\'></textarea> </div> </div> <div class="thumbnail"> ',
        j += d(g),
        j += ' </div> <div class="btn-group section-1px"> <button class="btn" id="confirm-btn">',
        j += d(h),
        j += "</button> ",
        i && (j += ' <button class="btn btn-import">',
        j += d(i),
        j += "</button> "),
        j += " </div> </div>"
    }
    ;
    return a.textarea = "TmplInline_alert.textarea",
    Tmpl.addTmpl(a.textarea, c),
    a
});