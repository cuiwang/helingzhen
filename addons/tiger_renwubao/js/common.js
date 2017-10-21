var usite = usite || {};
//#region 弹窗提示
usite.alert = usite.alert || {};
//自动调整弹窗大小
$(window).resize(function () {
    $('.blockMsg').css('left', (($(window).width() - $('.blockMsg').width() - 20) / 2) + "px");
})
usite.alert.text = function (msg) {
    $.blockUI({
        message: msg,
        fadeOut: 500,
        timeout: 2000,
        css: {
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            width: '300px',
            padding: '15px 0px',
            left: (($(window).width() - 300 - 0) / 2) + "px"
        }
    });
}
usite.alert.modeldef = function (msg) {
    $.blockUI({
        message: msg,
        showOverlay: true,
        overlayCSS: {
            opacity: 0.6,
            cursor: 'default'
        },
        css: {
            border: 'none',
            padding: '10px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: 1,
            color: '#fff',
            cursor: 'default',
            width: '300px',
            top: '20%',
            left: ((($(window).width() - 300 - 20) / 2) * 100 / $(window).width()) + "%"
        }
    });
}
usite.alert.model = function (msg) {
    $.blockUI({
        message: msg,
        showOverlay: true,
        centerY: true,
        overlayCSS: {
            opacity: 0,
            cursor: 'default'
        },
        css: {
            border: 'none',
            padding: '10px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: 1,
            color: '#fff',
            cursor: 'default',
            width: '300px',
            left: ((($(window).width() - 300 - 20) / 2) * 100 / $(window).width()) + "%"
        }
    });
}
usite.alert.text_go_url = function (msg, url) {
    $.blockUI({
        message: msg,
        fadeOut: 500,
        timeout: 2000,
        css: {
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            width: '300px',
            padding: '15px 0px',
            left: (($(window).width() - 300 - 0) / 2) + "px"
        },
        onUnblock: function () {
            window.location.href = url;
        }
    });
}
usite.alert.text_noaction = function (msg) {
    if ($('#alert_noaction_box').length == 0) {
        $('body').append('<div class="modal fade" id="alert_noaction_box" role="dialog" ><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-body"></div></div></div></div>');
        $('#alert_noaction_box .modal-body').html(msg);
        $('#alert_noaction_box').modal({ backdrop: false, keyboard: false, backdrop: 'static' });
    } else {
        $('#alert_noaction_box .modal-body').html(msg);
        $('#alert_noaction_box').modal('show');
    }
}
//#endregion

//#region
$.extend({
    closeBox: function (o, x) { $('#' + o).fadeOut(); if (x != undefined) { $("#WindowBg").remove(); }; },
    openBox: function (o, x) { $.IninBox(o, x); $('#' + o).fadeIn(); },
    InitCss: function (options, o) {
        var defaults = { "z-index": "6666", top: "50%", left: "50%", position: "fixed", "margin-left": "-100px", "margin-top": "-50px" }
        var settings = $.extend(defaults, options);
        $("#" + o).css(settings);
        //if ($.browser.msie && ($.browser.version == "6.0") && !$.support.style) {
        //    $("#" + o).css("position", "absolute");
        //    var href = location.href; if (href.indexOf('#') > 0) { href = href.substring(0, href.indexOf('#')); }
        //    location.href = href + "#";
        //}
    },
    IninBox: function (o, x) {
        var _box = $('#' + o); var $w = -_box.width() / 2 + "px"; var $h = -_box.height() / 2 + "px";
        $.InitCss({ "margin-left": $w, "margin-top": $h }, o)
        if (x != undefined && $("body").find("#WindowBg").html() == null) {
            var boxBgDom = "<div id=\"WindowBg\" style=\"position:absolute;background:#000;filter:alpha(opacity=10);opacity:0.2;width:100%;left:0;top:0;z-index:222;border:none\"><iframe src=\"about:blank\" style=\"width:100%;height:" + $(document).height() + "px;filter:alpha(opacity=10);opacity:0.2;scrolling=no;z-index:333;border:none\"></iframe></div>"
            $("body").append(boxBgDom);
        }
    }
})
//#endregion
function closeOpen() {
    $(".warmRemind").attr("style","display:none");
    $("#OpenNotice").attr("style", "display:none");
 
}

function indexChangeHeadImgboxShow() {
    $(".warmRemind").show();
}
function indexChangeHeadImgboxClose() { 
    $(".warmRemind").hide();
}

//#region 异步交互
usite.action = usite.action || {};

//异步Post处理
usite.action.post = function (post_url, post_date, success_callback, error_callback) {
    $.ajax({
        type: "post",
        contentType: "text/json",
        dataType: "json",
        url: post_url,
        data: post_date,
        success: success_callback,
        error: error_callback
    });
}

//异步Get处理
usite.action.get = function (get_url, success_callback, error_callback) {
    $.ajax({
        type: "get",
        dataType: "json",
        url: get_url + "&" + usite.random.randomStr(6),
        success: success_callback,
        error: error_callback
    });
}
//#endregion
//#region 随机字符串处理
usite.random = usite.random || {};

//生成随机整数
usite.random.randomInt = function (min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
};

//生成随机字符串
usite.random.randomStr = function (length) {
    var chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678'.split('');
    if (!length) length = 6;
    var str = '';
    for (var i = 0; i < length; i++) {
        str += chars[Math.floor(Math.random() * chars.length)];
    }
    return str;
};
//#endregion