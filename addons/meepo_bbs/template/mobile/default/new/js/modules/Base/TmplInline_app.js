!function(a, b) {
    "function" == typeof define && define.amd ? define(b) : "undefined" == typeof document ? module.exports = b() : a.TmplInline_app = b()
}(this, function() {
    var a = {}
      , b = '<div class="invoke-app-wrap">\r\n    <div class="left">\r\n        <img class="app-avatar" src="http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/app_logo.png" alt="兴趣部落"/>\r\n        <div class="app-info-wrap">\r\n            <h3 class="app-title">兴趣部落</h3>\r\n            \r\n        </div>\r\n    </div>\r\n    <div class="right">\r\n        <a class="invoke-btn">立即打开</a>\r\n        <i class="close-invoke-btn"></i>\r\n    </div>\r\n</div>\r\n';
    return a.invoke_app = "TmplInline_app.invoke_app",
    Tmpl.addTmpl(a.invoke_app, b),
    a
});