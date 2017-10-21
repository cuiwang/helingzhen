var _meepoajax = {
	_ajax:function(ajaxInfo){
		var i = _meepoajax._querystring('i');
		var j = _meepoajax._querystring('j');
		var url = './index.php?i='+i+'&j='+j+'&c=entry&do='+ajaxInfo.do_it+'&m=meepo_xianchang';
		var defaultInfo = {
            type: "GET",                       
            dataType: 'JSON',      
            cache: false,
            urlPata: {},
            formPata: {},
            success: function() {
            } //成功后显示debug信息。也可以增加自己的处理程序
        };
        //补全ajaxInfo
        if (typeof ajaxInfo.dataType == "undefined") {
            ajaxInfo.dataType = defaultInfo.dataType;
        }
        if (typeof ajaxInfo.formPata == "undefined") {
            ajaxInfo.type = "GET";
        } else {
            if (ajaxInfo.dataType == "json") {
                ajaxInfo.type = "POST";
            } else {    
                ajaxInfo.type = "POST";
            }
            ajaxInfo.data = ajaxInfo.formPata;
        }
		$.ajax({
            type: ajaxInfo.type,
            dataType: ajaxInfo.dataType,
            cache: ajaxInfo.cache,
            xhrFields: {
                //允许跨域访问时添加cookie
                withCredentials: true
            },
            url: url,  
            data: ajaxInfo.data,
            success: function (data) {
                  ajaxInfo.success(data);
            }
        });
	},
	_querystring : function(name){ 
		var result = location.search.match(new RegExp("[\?\&]" + name+ "=([^\&]+)","i")); 
		if (result == null || result.length < 1){ 
			return "";
		}
		return result[1]; 
	}
};
var _loading_toast= {
    _center: function() {
		var _left = ($(window).width() - $("#toast").outerWidth()) / 2 + "px";
        $("#toast").css({
            "bottom": "20%",
            "left": _left
        });
    },
    _show: function(text, fun) {
        $("#toast").html(text);
        _loading_toast._center();
        $("#toast").show();
        $("#toast").bind("resize", _loading_toast._center);
        setTimeout(function() {
            _loading_toast._hide(fun);
        }, 3 * 1000);
    },
    _hide: function(fun) {
        $("#toast").hide();
        $("#toast").unbind("resize");
        if (fun) {
            (fun)();
        }
    }
};
var _common= {
    _trim: function(text) {
        return text.replace(/(^\s*)|(\s*$)/g, "");
    }
};
function e(e) {
                var t;
                t = e ? new Date(1e3 * e) : new Date;
                var n = t.getFullYear(),
                s = t.getMonth() + 1,
                o = t.getDate(),
                a = t.getHours(),
                r = t.getMinutes(),
				m = t.getSeconds();
                return 10 > a && (a = "0" + a),10 > r && (r = "0" + r),10 > m && (m = "0" + m),
                {
                    year: n,
                    month: s,
                    day: o,
                    hours: a,
                    minute: r,
					seconds: m
                }
            }
            function t(e) {
                for (var t = 0; t < e.length; t++) {
                    var s = e[t],
                    o = s.ts;
                    if (0 === t) s.time = n(o),
                    s.ifShowTime = !0;
                    else {
                        var a = e[t - 1].ts;
                        o - a > S ? (s.time = n(o), s.ifShowTime = !0) : (s.time = "", s.ifShowTime = !1)
                    }
                }
                return e
            }
            function n(t) {
                var n, s = e(),
                o = e(t);
                return n = s.year != o.year || s.month != o.month || s.day - o.day > 1 ? o.year.toString() + "年" + o.month.toString() + "月" + o.day.toString() + "日 " + o.hours.toString() + ":" + o.minute.toString()+":"+ o.seconds.toString() : s.day - o.day === 1 ? "昨天 " + o.hours.toString() + ":" + o.minute.toString()+":"+ o.seconds.toString() : o.hours.toString() + ":" + o.minute.toString()+":"+ o.seconds.toString()
            }