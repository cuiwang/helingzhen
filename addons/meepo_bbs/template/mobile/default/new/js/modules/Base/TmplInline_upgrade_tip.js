!function(a, b) {
    "function" == typeof define && define.amd ? define(b) : "undefined" == typeof document ? module.exports = b() : a.TmplInline_upgrade_tip = b()
}(this, function() {
    var a = {}
      , b = '<div class="upgrade-tip-bg">\r\n    <div class="upgrade-tip-pulse">\r\n        <div class="upgrade-tip-icon-wrap">\r\n            <div class="upgrade-tip-icon"></div>\r\n            <div class="upgrade-tip-rotate"></div>\r\n        </div>\r\n\r\n        <div class="upgrade-tip-text">\r\n            <p>恭喜您升级为<span class="level">LV.{{level}}</span></p>\r\n            <p soda-if="level_title" class="level-title">获得新头衔<span class="level">{{level_title}}</span></p>\r\n        </div>\r\n    </div>\r\n\r\n    <i class="close-icon" id="upgradeTipCloseBtn"></i>\r\n</>\r\n';
    return a.index = "TmplInline_upgrade_tip.index",
    Tmpl.addTmpl(a.index, b),
    a
});