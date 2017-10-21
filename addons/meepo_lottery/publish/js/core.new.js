var core = {};
	/**
	 * Dialog
	 * */
	(function($, window, undefined) {
	    
	    var win = $(window)
	      , 
	    doc = $(document)
	      , 
	    count = 1
	      , 
	    isLock = false;
	    
	    var Dialog = function(options) {
	        
	        this.settings = $.extend({}, Dialog.defaults, options);
	        
	        this.init();
	    
	    }
	    
	    Dialog.prototype = {
	        
	        /**
	         * 初始化
	         */
	        init: function() {
	            
	            this.create();
	            
	            if (this.settings.lock) {
	                this.lock();
	            }
	            
	            if (!isNaN(this.settings.time) && this.settings.time != null ) {
	                this.time();
	            }
	        
	        },
	        
	        /**
	         * 创建
	         */
	        create: function() {
	            var templates = '<div class="weui_dialog_hd"><strong class="weui_dialog_title">' + this.settings.title + '</strong></div>';
	            templates += '<div class="weui_dialog_bd">' + this.settings.content + '</div>';
	            templates += '<div class="weui_dialog_ft">';
	            templates += '</div>';
	            
	            // 追回到body
	            this.dialog = $('<div>').addClass('weui_dialog').html(templates).prependTo('body');
	            // 设置cancel按钮
	            if ($.isFunction(this.settings.cancel)) {
	                this.cancel();
	            }
	            // 设置ok按钮
	            if ($.isFunction(this.settings.ok)) {
	                this.ok();
	            }
	        
	        },
	        /*
	         * ok
	         */
	        ok: function() {
	            var _this = this
	              , 
	            footer = this.dialog.find('.weui_dialog_ft');
	            
	            $('<a>', {
	                href: 'javascript:;',
	                text: this.settings.okText
	            }).on("click", function() {
	                var okCallback = _this.settings.ok();
	                if (okCallback == undefined || okCallback) {
	                    _this.close();
	                }
	            
	            }).addClass('weui_btn_dialog primary').appendTo(footer);
	        
	        },
	        
	        /**
	         * cancel
	         */
	        cancel: function() {
	            var _this = this
	              , 
	            footer = this.dialog.find('.weui_dialog_ft');
	            $('<a>', {
	                href: 'javascript:;',
	                text: this.settings.cancelText
	            }).on("click", function() {
	                var cancelCallback = _this.settings.cancel();
	                if (cancelCallback == undefined || cancelCallback) {
	                    _this.close();
	                }
	            }).addClass('weui_btn_dialog default').appendTo(footer);
	        },
	        
	        /**
	         * 设置锁屏
	         */
	        lock: function() {
	            
	            if (isLock)
	                return;
	            this.lock = $("<div class='weui_mask'></div>");
	            this.lock.appendTo('body');
	            
	            isLock = true;
	        
	        },
	        
	        /**
	         * 关闭锁屏
	         */
	        unLock: function() {
	            if (this.settings.lock) {
	                if (isLock) {
	                    this.lock.remove();
	                    isLock = false;
	                }
	            }
	        },
	        
	        /**
	         * 关闭方法
	         */
	        close: function() {
	            this.dialog.remove();
	            this.unLock();
	        },
	        
	        /**
	         * 定时关闭
	         */
	        time: function() {
	            
	            var _this = this;
	            
	            this.closeTimer = setTimeout(function() {
	                _this.close();
	            }, this.settings.time);
	        
	        }
	    
	    }
	    
	    /**
	     * 默认配置
	     */
	    Dialog.defaults = {
	        
	        // 内容
	        content: '加载中...',
	        
	        // 标题
	        title: 'load',
	        
	        // 宽度
	        width: 'auto',
	        
	        // 高度
	        height: 'auto',
	        
	        // 确定按钮回调函数
	        ok: null ,
	        
	        // 取消按钮回调函数
	        cancel: null ,
	        
	        // 确定按钮文字
	        okText: '确定',
	        
	        // 取消按钮文字
	        cancelText: '取消',
	        
	        // 自动关闭时间(毫秒)
	        time: null ,
	        
	        // 是否锁屏
	        lock: true,
	        
	        // z-index值
	        zIndex: 9999
	    
	    }
	    var rDialog = function(options) {
	        return new Dialog(options);
	    }
	    $.dialog = rDialog;

	})($);
	
	var core = core || {
	    version: '1.0.0'
	};
	
	var $actionSheetWrapper = null;

    /**
     * show actionSheet
     * @param {Array} menus
     * @param {Array} actions
     */
   core.actionSheet = function () {
        var menus = arguments.length <= 0 || arguments[0] === undefined ? [] : arguments[0];
        var actions = arguments.length <= 1 || arguments[1] === undefined ? [{ label: '取消' }] : arguments[1];

        var cells = menus.map(function (item, idx) {
            return '<div class="weui_actionsheet_cell">' + item.label + '</div>';
        }).join('');
        var action = actions.map(function (item, idx) {
            return '<div class="weui_actionsheet_cell">' + item.label + '</div>';
        }).join('');
        var html = '<div>\n            <div class="weui_mask_transition"></div>\n            <div class="weui_actionsheet">\n                <div class="weui_actionsheet_menu">\n                    ' + cells + '\n                </div>\n                <div class="weui_actionsheet_action">\n                    ' + action + '\n                </div>\n            </div>\n        </div>';

        $actionSheetWrapper = $(html);
        $('body').append($actionSheetWrapper);

        // add class
        $actionSheetWrapper.find('.weui_mask_transition').show().addClass('weui_fade_toggle');
        $actionSheetWrapper.find('.weui_actionsheet').addClass('weui_actionsheet_toggle');

        // bind event
        $actionSheetWrapper.on('click', '.weui_actionsheet_menu .weui_actionsheet_cell', function () {
            var item = menus[$(this).index()];
            var cb = item.onClick || $.noop;
            cb.call();
            core.hideActionSheet();
        }).on('click', '.weui_mask_transition', function () {
            core.hideActionSheet();
        }).on('click', '.weui_actionsheet_action .weui_actionsheet_cell', function () {
            var item = actions[$(this).index()];
            var cb = item.onClick || $.noop;
            cb.call();
            core.hideActionSheet();
        });
    };

    core.hideActionSheet = function (call) {
        if (!$actionSheetWrapper) {
            return;
        }

        var $mask = $actionSheetWrapper.find('.weui_mask_transition');
        var $actionsheet = $actionSheetWrapper.find('.weui_actionsheet');

        $mask.removeClass('weui_fade_toggle');
        $actionsheet.removeClass('weui_actionsheet_toggle');

        $actionsheet.on('transitionend', function () {
            $actionSheetWrapper.remove();
            $actionSheetWrapper = null;
        }).on('webkitTransitionEnd', function () {
            $actionSheetWrapper.remove();
            $actionSheetWrapper = null;
        });
    };
    
    var $loading = null;

    /**
     * show loading
     * @param {String} content
     */
    core.loading = function () {
        var content = arguments.length <= 0 || arguments[0] === undefined ? 'loading...' : arguments[0];

        var html = '<div class="weui_loading_toast">\n        <div class="weui_mask_transparent"></div>\n        <div class="weui_toast">\n            <div class="weui_loading">\n                <div class="weui_loading_leaf weui_loading_leaf_0"></div>\n                <div class="weui_loading_leaf weui_loading_leaf_1"></div>\n                <div class="weui_loading_leaf weui_loading_leaf_2"></div>\n                <div class="weui_loading_leaf weui_loading_leaf_3"></div>\n                <div class="weui_loading_leaf weui_loading_leaf_4"></div>\n                <div class="weui_loading_leaf weui_loading_leaf_5"></div>\n                <div class="weui_loading_leaf weui_loading_leaf_6"></div>\n                <div class="weui_loading_leaf weui_loading_leaf_7"></div>\n                <div class="weui_loading_leaf weui_loading_leaf_8"></div>\n                <div class="weui_loading_leaf weui_loading_leaf_9"></div>\n                <div class="weui_loading_leaf weui_loading_leaf_10"></div>\n                <div class="weui_loading_leaf weui_loading_leaf_11"></div>\n            </div>\n            <p class="weui_toast_content">' + content + '</p>\n        </div>\n    </div>';
        $loading = $(html);
        $('body').append($loading);
    };

    /**
     * hide loading
     */
    core.loaded = function () {
        $loading && $loading.remove();
        $loading = null;
    };
    
    core.json_encode = function(obj){
    	return JSON.stringify(obj)
    }
	
	core.wxshare = function(params){
		var _share = params;
		wx.ready(function () {
			wx.onMenuShareAppMessage(_share);
			wx.onMenuShareTimeline(_share);
			wx.onMenuShareQQ(_share);
			wx.onMenuShareWeibo(_share);
		});
	}
	
    /**
	 * 函数延迟执行
	 * */
    core.debounce = function(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this;
            var args = arguments;
            var later = function() {
                timeout = null ;
                if (!immediate) {
                    func.apply(context, args);
                }
            };
            var callNow = immediate && !timeout;
            
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            
            if (callNow) {
                func.apply(context, args);
            }
        };
    };
    /**
	 * 是否在显示区域
	 * */
    core.isInView = function(element, options) {
        var $element = $(element);
        var $win = $(document);
        var visible = !!($element.width() || $element.height()) && 
        $element.css('display') !== 'none';
        
        if (!visible) {
            return false;
        }
        
        var windowLeft = $win.scrollLeft();
        var windowTop = $win.scrollTop();
        var offset = $element.offset();
        var left = offset.left;
        var top = offset.top;
        
        options = $.extend({
            topOffset: 0,
            leftOffset: 0
        }, options);
        
        return ( top + $element.height() >= windowTop && 
        top - options.topOffset <= windowTop + $win.height() && 
        left + $element.width() >= windowLeft && 
        left - options.leftOffset <= windowLeft + $win.width()) ;
    };
    
    core.ohSnap = function(text, options) {
        var defaultOptions = {
            'color': null ,
            // color is  CSS class `alert-color`
            'icon': null ,
            // class of the icon to show before the alert text
            'duration': '5000',
            // duration of the notification in ms
            'container-id': 'pjax-container',
            // id of the alert container
            'fade-duration': 'fast',
            // duration of the fade in/out of the alerts. fast, slow or integer in ms
        }
        
        options = (typeof options == 'object') ? $.extend(defaultOptions, options) : defaultOptions;
        
        var $container = $('#' + options['container-id'])
          , 
        icon_markup = ""
          , 
        color_markup = "";
        
        if (options.icon) {
            icon_markup = "<span class='" + options.icon + "'></span> ";
        }
        
        if (options.color) {
            color_markup = 'alert-' + options.color;
        }
        
        // Generate the HTML
        var html = $('<div class="alert ' + color_markup + '">' + icon_markup + text + '</div>').fadeIn(options['fade-duration']);
        
        // Append the label to the container
        $container.append(html);
        
        // Remove the notification on click
        html.on('click', function() {
            core.ohSnapX($(this));
        });
        
        // After 'duration' seconds, the animation fades out
        setTimeout(function() {
            core.ohSnapX(html);
        }, options.duration);
    }
    
    core.ohSnapX = function(element, options) {
        defaultOptions = {
            'duration': 'fast'
        }
        
        options = (typeof options == 'object') ? $.extend(defaultOptions, options) : defaultOptions;
        
        if (typeof element !== "undefined") {
            element.fadeOut(options.duration, function() {
                $(this).remove();
            });
        } else {
            $('.alert').fadeOut(options.duration, function() {
                $(this).remove();
            });
        }
    }
    
    /**
	 * 当前时间戳
	 * */
    core.time = function() {
        var timestamp = new Date().getTime() / 1000;
        return timestamp;
    }
    
    core.nowStr = function() {
        var d = new Date();
        return core.dateToStr(d);
    }
    
    core.isjson = function(obj) {
        var isjson = typeof (obj) == "object" && Object.prototype.toString.call(obj).toLowerCase() == "[object object]" && !obj.length;
        return isjson;
    }
    /**
	 * core.getNow
	 * */
    core.dateToStr = function(date) {
        var seperator1 = "-";
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        var strDate = date.getDate();
        if (month >= 1 && month <= 9) {
            month = "0" + month;
        }
        if (strDate >= 0 && strDate <= 9) {
            strDate = "0" + strDate;
        }
        var currentdate = year + seperator1 + month + seperator1 + strDate;
        return currentdate;
    }
    /**
	 * 字符串时间转换整形
	 * */
    core.timeToInt = function(date) {
        date = date.substring(0, 19);
        date = date.replace(/-/g, '/');
        return new Date(date).getTime();
    }
    
    core.intToTime = function(timestamp) {
        var d = new Date(timestamp * 1000);
        return core.dateToStr(d);
    }
    
    core.nextWeekStr = function() {
        var now = new Date();
        var d = new Date(now.getTime() + 7 * 24 * 3600 * 1000);
        return core.dateToStr(d);
    }
    
    core.go = function(d, params, container) {
        var i = core.querystring('i');
        var j = core.querystring('j');
        var _str = "";
        for (var o in params) {
            _str += '&' + o + '=' + params[o];
        }
        
        var url = './index.php?i=' + i + '&j=' + j + '&c=entry&do=' + d + _str + '&m=meepo_voteplatform';
        if (core.empty(container)) {
            container = '#pjax-container';
        }
        $.pjax({
            url: url,
            container: container
        });
    }
    
    core.isset = function(a) {
        return typeof (a) == "undefined";
    }
    
    core.empty = function(v) {
        switch (typeof v) {
        case 'undefined':
            return true;
        case 'string':
            if (core.trim(v).length == 0) {
                return true;
                break;
            }
        case 'boolean':
            if (!v) {
                return true;
                break;
            }
        case 'number':
            if (0 === v)
                return true;
            break;
        case 'object':
            if (null  === v)
                return true;
            if (undefined !== v.length && v.length == 0) {
                return true;
            }
        }
        return false;
    }
    
    core.trim = function(str) {
        return str.replace(/(^\s*)|(\s*$)/g, "");
    }
    
    
    /**
	 * str
	 * */
    core.json_decode = function(str) {
        var obj = eval('(' + str + ')');
        return obj;
    }
    
    /**
	 * get请求 
	 */
    
    core.get = function(d, call,t) {
        var i = core.querystring('i');
        var j = core.querystring('j');
        var type = t || 'json';
        var url = './index.php?i=' + i + '&j=' + j + '&c=entry&do=' + d + '&m=meepo_voteplatform';
        $.get(url, call,type);
    }
    /**
	 * post请求 
	 */
    
    core.post = function(d, data, call,t) {
        var i = core.querystring('i');
        var j = core.querystring('j');
        
        var type = t || 'json';
        
        var url = './index.php?i=' + i + '&j=' + j + '&c=entry&do=' + d + '&m=meepo_voteplatform';
        $.post(url, data, call,type);
    }
    
    core.getScript = function(file, call) {
        var i = core.querystring('i');
        var j = core.querystring('j');
        var url = './index.php?i=' + i + '&j=' + j + '&c=entry&do=loadjs&m=meepo_voteplatform';
        $.ajax({
            url: url,
            data: {
                file: file
            },
            dataType: "script",
            success: call
        });
    }
    /**
	 * url解析
	 * */
    
    core.querystring = function(name) {
        var result = location.search.match(new RegExp("[\?\&]" + name + "=([^\&]+)","i"));
        if (result == null  || result.length < 1) {
            return "";
        }
        return result[1];
    }
    
    core.queryurl = function(name,url){
    	var result = url.match(new RegExp("[\?\&]" + name + "=([^\&]+)","i"));
        if (result == null  || result.length < 1) {
            return "";
        }
        return result[1];
    }
    
    /**
	 * agent 判断
	 */
    
    core.agent = function() {
        var agent = navigator.userAgent;
        var isAndroid = agent.indexOf('Android') > -1 || agent.indexOf('Linux') > -1;
        var isIOS = !!agent.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
        if (isAndroid) {
            return 'android';
        } else if (isIOS) {
            return 'ios';
        } else {
            return 'unknown'
        }
    }
    ;
    /**
	 * 模板解析
	 * */
    
    core.tpl = tpl;
    
    function tpl(html, data) {
        var fn = compiler(html);
        return data ? fn(data) : fn;
    }
    tpl.begin = '<#';
    tpl.end = '#>';
    
    function compiler(html) {
        html = html || '';
        if (html.charAt(0) === '#')
            html = document.getElementById(html.substring(1)).innerHTML;
        var trim = function(str) {
            return str.trim ? str.trim() : str.replace(/^\s*|\s*$/g, '');
        }
        , 
        ecp = function(str) {
            return str.replace(/('|\\|\r?\n)/g, '\\$1');
        }
        , 
        begin = tpl.begin, 
        end = tpl.end, 
        v = tpl.variable, 
        arg1 = v || "$", 
        str = "var " + arg1 + "=" + arg1 + "||this,__='',___,\
                echo=function(s){__+=s},\
                include=function(t,d){__+=tpl(t).call(d||" + arg1 + ")};" + (v ? "" : "with($||{}){"), 
        blen = begin.length, elen = end.length, 
        b = html.indexOf(begin), e, 
        skip, 
        tmp;
        
        while (b != -1) {
            e = skip ? b + blen : html.indexOf(end);
            if (e < b)
                break;
            //出错后不再编译
            str += "__+='" + ecp(html.substring(0, b)) + "';";
            
            if (skip) {
                html = html.substring(blen + elen + 1);
                skip--;
            } else {
                tmp = trim(html.substring(b + blen, e));
                if ('#' === tmp) {
                    skip = 1;
                } else if (tmp.indexOf('=') === 0) {
                    //模板变量
                    tmp = tmp.substring(1);
                    str += "___=" + tmp + ";typeof ___!=='undefined'&&(__+=___);";
                } else {
                    //js代码
                    str += "\n" + tmp + "\n";
                }
            }
            
            html = html.substring(e + elen);
            b = html.indexOf(begin + (skip ? '#' + end : ''));
        }
        str += "__+='" + ecp(html) + "'" + (v ? ";" : "}") + "return __";
        return new Function(arg1,str);
    }
    /**
	 * ui组件
	 * */
    var ui = {
        _init: function(config) {
            if (config.template.charAt(0) === '#') {
                var el = $('' + config.selector);
                var template = $(config.template).innerHTML;
                var html = core.tpl(template, config.data);
                var $html = $(html).addClass('slideIn').addClass(config.name);
                if (config.after) {
                    el.after($html);
                } else {
                    el.append($html);
                }
            } else {
                var el = $('' + config.selector);
                core.post('tpl', {
                    tpl: config.template
                }, function(data) {
                    if (config.after) {
                        el.after(core.tpl(data, config.data));
                    } else {
                        el.append(core.tpl(data, config.data));
                    }
                    ui._bind(config);
                    if (config.call) {
                        config.call()
                    }
                });
            }
        },
        _bind: function(config) {
            var el = $('' + config.selector);
            var events = config.events || {};
            for (var t in events) {
                for (var type in events[t]) {
                    el.on(type, t, events[t][type]);
                }
            }
        }
    }
    
    core.ui = ui._init;
    
    core.pjaxLoadding = function(){
    	var meepo_loading_data = $(".mask_pajx");
    	if (meepo_loading_data.length <= 0) {
    		var loadding = '<div class="mask_pajx">'+
								'<div style="position: relative;z-index: 10;top: 10%;margin:0 auto;">'+
									'<div id="waterfall-loading">'+
										'<div class="kb-wall-loading"></div>'+
									'</div>'+
								'</div>'+
							'</div>';
    		$(loadding).appendTo(document.body);
    		meepo_loading_data = $(".mask_pajx");
    		meepo_loading_data.show();
    	}else{
    		meepo_loading_data.show();
    	}
	}
    
    core.pjaxloaded = function(){
    	var meepo_loading_data = $(".mask_pajx");
    	meepo_loading_data.hide();
    }
    
    //ok
    core.cancel = function(content,cancel) {
        $.dialog({
            title: '温馨提示',
            content: content,
            okText: '我知道了',
            cancel: cancel || function() {}
        });
    }
    
    core.ok = function(content, ok,cancel) {
        $.dialog({
            title: '温馨提示',
            content: content,
            okText: '确定',
            cancelText: '取消',
            cancel: cancel || function(){},
            ok: ok
        });
    };
    
    window.core = core;

