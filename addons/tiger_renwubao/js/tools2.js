var wx2 = wx2 || {};

//#region 弹窗（使用Bootstrap）
wx2.alert = wx2.alert || {};

wx2.alert.load = function () {
    if ($('#alert_load_box').length == 0) {
        $('body').append('<div class="modal fade" id="alert_load_box" role="dialog" ><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-body text-center" style="padding:40px 0;"><img src="/images/big-ajax-loader.gif" /></div></div></div></div>');
        $('#alert_load_box').modal({ backdrop: false, keyboard: false, backdrop: 'static' });
    } else {
        $('#alert_load_box').modal('show');
    }
}

wx2.alert.text_autoclose = function (msg) {
    var waitsec = arguments[1] ? arguments[1] : 1000;
    if ($('#alert_autoclose_box').length == 0) {
        $('body').append('<div class="modal fade" id="alert_autoclose_box" role="dialog" ><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-body text-center"></div></div></div></div>');
        $('#alert_autoclose_box .modal-body').html(msg);
        $('#alert_autoclose_box').modal({ backdrop: false, keyboard: false, backdrop: 'static' });
    } else {
        $('#alert_autoclose_box .modal-body').html(msg);
        $('#alert_autoclose_box').modal('show');
    }
    window.setTimeout('$("#alert_autoclose_box").modal("hide");', waitsec);
}

wx2.alert.text_noaction = function (msg) {
    if ($('#alert_noaction_box').length == 0) {
        $('body').append('<div class="modal fade" id="alert_noaction_box" role="dialog" ><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-body"></div></div></div></div>');
        $('#alert_noaction_box .modal-body').html(msg);
        $('#alert_noaction_box').modal({ backdrop: false, keyboard: false, backdrop: 'static' });
    } else {
        $('#alert_noaction_box .modal-body').html(msg);
        $('#alert_noaction_box').modal('show');
    }
}

wx2.alert.text_confrim = function (msg, ok_handel, cancel_handel) {
    if ($('#alert_confrim_box').length == 0) {
        $('body').append('<div class="modal fade" id="alert_confrim_box" role="dialog" ><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-default btn_cancel" >取消</button> <button type="button" class="btn btn-default btn_ok">确定</button></div></div></div></div>');
        $('#alert_confrim_box .modal-body').html(msg);
        $('#alert_confrim_box').modal({ backdrop: false, keyboard: false, backdrop: 'static' });
    } else {
        $('#alert_confrim_box .modal-body').html(msg);
        $('#alert_confrim_box').modal('show');
    }
    $('#alert_confrim_box').on('click', '.btn_ok', ok_handel).on('click', '.btn_cancel', cancel_handel);
}

wx2.alert.text = function (msg) {
    if ($('#alert_box').length == 0) {
        $('body').append('<div class="modal fade" id="alert_box" role="dialog" ><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-default btn_ok" data-dismiss="modal">确定</button></div></div></div></div>');
        $('#alert_box .modal-body').html(msg);
        $('#alert_box').modal({ backdrop: false, keyboard: false, backdrop: 'static' });
    } else {
        $('#alert_box .modal-body').html(msg);
        $('#alert_box').modal('show');
    }
}

wx2.alert.text_go_url = function (msg, gourl) {
    var waitsec = arguments[2] ? arguments[1] : 1500;
    if ($('#alert_gourl_box').length == 0) {
        $('body').append('<div class="modal fade" id="alert_gourl_box" role="dialog" ><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-body text-center" style="padding:30px 0;"></div></div></div></div>');
        $('#alert_gourl_box .modal-body').html(msg);
        $('#alert_gourl_box').modal({ backdrop: false, keyboard: false, backdrop: 'static' });
    } else {
        $('#alert_gourl_box .modal-body').html(msg);
        $('#alert_gourl_box').modal('show');
    }
    window.setTimeout('window.location.href = "' + gourl + '";', waitsec);
}

wx2.alert.close = function () {
    $('.modal').modal('hide');
}
//#endregion


//#region 随机字符串处理
wx2.random = wx2.random || {};

//生成随机整数
wx2.random.randomInt = function (min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
};

//生成随机字符串
wx2.random.randomStr = function (length) {
    var chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678'.split('');
    if (!length) length = 6;
    var str = '';
    for (var i = 0; i < length; i++) {
        str += chars[Math.floor(Math.random() * chars.length)];
    }
    return str;
};
//#endregion

//#region url处理
wx2.url = wx2.url || {};

//获取querystring参数集合,获取时使用var xxx=getQueryString();xxx.id或xxx[id];无值返回undefined
wx2.url.getQueryString = function () {
    var args = new Object();
    var query = location.search.substring(1);      // Get query string
    var pairs = query.split("&");                  // Break at ampersand
    for (var i = 0; i < pairs.length; i++) {
        var pos = pairs[i].indexOf('=');           // Look for "name=value"
        if (pos == -1) continue;                   // If not found, skip
        var argname = pairs[i].substring(0, pos);  // Extract the name
        var value = pairs[i].substring(pos + 1);   // Extract the value
        try {
            value = decodeURIComponent(value);     // Decode it, if needed
        }
        catch (e) {
            value = unescape(value);
        }
        args[argname] = value;                     // Store as a property
    }
    return args;                                   // Return the object
}
//修改url参数，并返回新的url,参数为{id:1,name:'xxx'}
wx2.url.updateQueryParam = function (params) {
    var newurl = location.pathname + "?";
    var query = location.search.substring(1);
    var pairs = query.split("&");
    for (var i = 0; i < pairs.length; i++) {
        var pos = pairs[i].indexOf('=');
        if (pos == -1) continue;
        var argname = pairs[i].substring(0, pos);
        var value = pairs[i].substring(pos + 1);
        var tempvalue = value;
        try {
            value = params[argname];//这里在IE下如果键不存在，是会报错的“键 is not defind”，火狐不报错直接赋undefind。
            if (value === undefined || value == null)//===是恒等，用==undefind等价于null，不清楚浏览器兼容，所以做或判断。
                value = tempvalue;
        }
        catch (e) {
            value = tempvalue;
        }
        try {
            value = decodeURIComponent(value);
        }
        catch (e) {
            value = unescape(value);
        }
        newurl += argname + "=" + value;
        if (i != pairs.length - 1)
            newurl += "&";
    }
    return newurl + location.hash;//加上#部分
}
//#endregion

//#region 异步交互
wx2.action = wx2.action || {};

//异步Post处理
wx2.action.post = function (post_url, post_date, success_callback, error_callback) {
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
wx2.action.get = function (get_url, success_callback, error_callback) {
    $.ajax({
        type: "get",
        dataType: "json",
        url: get_url + "&" + wx2.random.randomStr(6),
        success: success_callback,
        error: error_callback
    });
}
//#endregion
