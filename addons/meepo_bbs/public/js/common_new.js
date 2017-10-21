/**
 * Created by imeepos on 2016/6/13.
 */
var supporttouch = "ontouchend" in document;
!supporttouch && (window.location.href = 'index.php');

var platform = navigator.platform;
var ua = navigator.userAgent;
var ios = /iPhone|iPad|iPod/.test(platform) && ua.indexOf( "AppleWebKit" ) > -1;
var andriod = ua.indexOf( "Android" ) > -1;
var lastaction='';
var historylength=window.history.length;

(function($, window, document, undefined) {
    var dataPropertyName = "virtualMouseBindings",
        touchTargetPropertyName = "virtualTouchID",
        virtualEventNames = "vmouseover vmousedown vmousemove vmouseup vclick vmouseout vmousecancel".split(" "),
        touchEventProps = "clientX clientY pageX pageY screenX screenY".split( " " ),
        mouseHookProps = $.event.mouseHooks ? $.event.mouseHooks.props : [],
        mouseEventProps = $.event.props.concat( mouseHookProps ),
        activeDocHandlers = {},
        resetTimerID = 0,
        startX = 0,
        startY = 0,
        didScroll = false,
        clickBlockList = [],
        blockMouseTriggers = false,
        blockTouchTriggers = false,
        eventCaptureSupported = "addEventListener" in document,
        $document = $(document),
        nextTouchID = 1,
        lastTouchID = 0, threshold;
    $.vmouse = {
        moveDistanceThreshold: 10,
        clickDistanceThreshold: 10,
        resetTimerDuration: 1500
    };
    function getNativeEvent(event) {
        while( event && typeof event.originalEvent !== "undefined" ) {
            event = event.originalEvent;
        }
        return event;
    }
    function createVirtualEvent(event, eventType) {
        var t = event.type, oe, props, ne, prop, ct, touch, i, j, len;
        event = $.Event(event);
        event.type = eventType;
        oe = event.originalEvent;
        props = $.event.props;
        if(t.search(/^(mouse|click)/) > -1 ) {
            props = mouseEventProps;
        }
        if(oe) {
            for(i = props.length, prop; i;) {
                prop = props[ --i ];
                event[ prop ] = oe[ prop ];
            }
        }
        if(t.search(/mouse(down|up)|click/) > -1 && !event.which) {
            event.which = 1;
        }
        if(t.search(/^touch/) !== -1) {
            ne = getNativeEvent(oe);
            t = ne.touches;
            ct = ne.changedTouches;
            touch = (t && t.length) ? t[0] : (( ct && ct.length) ? ct[0] : undefined);
            if(touch) {
                for(j = 0, len = touchEventProps.length; j < len; j++) {
                    prop = touchEventProps[j];
                    event[prop] = touch[prop];
                }
            }
        }
        return event;
    }
    function getVirtualBindingFlags(element) {
        var flags = {},
            b, k;
        while(element) {
            b = $.data(element, dataPropertyName);
            for(k in b) {
                if(b[k]) {
                    flags[k] = flags.hasVirtualBinding = true;
                }
            }
            element = element.parentNode;
        }
        return flags;
    }
    function getClosestElementWithVirtualBinding(element, eventType) {
        var b;
        while(element) {
            b = $.data( element, dataPropertyName );
            if(b && (!eventType || b[eventType])) {
                return element;
            }
            element = element.parentNode;
        }
        return null;
    }
    function enableTouchBindings() {
        blockTouchTriggers = false;
    }
    function disableTouchBindings() {
        blockTouchTriggers = true;
    }
    function enableMouseBindings() {
        lastTouchID = 0;
        clickBlockList.length = 0;
        blockMouseTriggers = false;
        disableTouchBindings();
    }
    function disableMouseBindings() {
        enableTouchBindings();
    }
    function startResetTimer() {
        clearResetTimer();
        resetTimerID = setTimeout(function() {
            resetTimerID = 0;
            enableMouseBindings();
        }, $.vmouse.resetTimerDuration);
    }
    function clearResetTimer() {
        if(resetTimerID ) {
            clearTimeout(resetTimerID);
            resetTimerID = 0;
        }
    }
    function triggerVirtualEvent(eventType, event, flags) {
        var ve;
        if((flags && flags[eventType]) ||
            (!flags && getClosestElementWithVirtualBinding(event.target, eventType))) {
            ve = createVirtualEvent(event, eventType);
            $(event.target).trigger(ve);
        }
        return ve;
    }
    function mouseEventCallback(event) {
        var touchID = $.data(event.target, touchTargetPropertyName);
        if(!blockMouseTriggers && (!lastTouchID || lastTouchID !== touchID)) {
            var ve = triggerVirtualEvent("v" + event.type, event);
            if(ve) {
                if(ve.isDefaultPrevented()) {
                    event.preventDefault();
                }
                if(ve.isPropagationStopped()) {
                    event.stopPropagation();
                }
                if(ve.isImmediatePropagationStopped()) {
                    event.stopImmediatePropagation();
                }
            }
        }
    }
    function handleTouchStart(event) {
        var touches = getNativeEvent(event).touches,
            target, flags;
        if(touches && touches.length === 1) {
            target = event.target;
            flags = getVirtualBindingFlags(target);
            if(flags.hasVirtualBinding) {
                lastTouchID = nextTouchID++;
                $.data(target, touchTargetPropertyName, lastTouchID);
                clearResetTimer();
                disableMouseBindings();
                didScroll = false;
                var t = getNativeEvent(event).touches[0];
                startX = t.pageX;
                startY = t.pageY;
                triggerVirtualEvent("vmouseover", event, flags);
                triggerVirtualEvent("vmousedown", event, flags);
            }
        }
    }
    function handleScroll(event) {
        if(blockTouchTriggers) {
            return;
        }
        if(!didScroll) {
            triggerVirtualEvent("vmousecancel", event, getVirtualBindingFlags(event.target));
        }
        didScroll = true;
        startResetTimer();
    }
    function handleTouchMove(event) {
        if(blockTouchTriggers) {
            return;
        }
        var t = getNativeEvent(event).touches[0],
            didCancel = didScroll,
            moveThreshold = $.vmouse.moveDistanceThreshold,
            flags = getVirtualBindingFlags(event.target);
        didScroll = didScroll ||
            (Math.abs(t.pageX - startX) > moveThreshold ||
            Math.abs(t.pageY - startY) > moveThreshold);
        if(didScroll && !didCancel) {
            triggerVirtualEvent("vmousecancel", event, flags);
        }
        triggerVirtualEvent("vmousemove", event, flags);
        startResetTimer();
    }
    function handleTouchEnd(event) {
        if(blockTouchTriggers) {
            return;
        }
        disableTouchBindings();
        var flags = getVirtualBindingFlags(event.target), t;
        triggerVirtualEvent("vmouseup", event, flags);
        if(!didScroll) {
            var ve = triggerVirtualEvent("vclick", event, flags);
            if(ve && ve.isDefaultPrevented()) {
                t = getNativeEvent(event).changedTouches[0];
                clickBlockList.push({
                    touchID: lastTouchID,
                    x: t.clientX,
                    y: t.clientY
                });
                blockMouseTriggers = true;
            }
        }
        triggerVirtualEvent("vmouseout", event, flags);
        didScroll = false;
        startResetTimer();
    }
    function hasVirtualBindings(ele) {
        var bindings = $.data( ele, dataPropertyName ), k;
        if(bindings) {
            for(k in bindings) {
                if(bindings[k]) {
                    return true;
                }
            }
        }
        return false;
    }
    function dummyMouseHandler() {}

    function getSpecialEventObject(eventType) {
        var realType = eventType.substr(1);
        return {
            setup: function(data, namespace) {
                if(!hasVirtualBindings(this)) {
                    $.data(this, dataPropertyName, {});
                }
                var bindings = $.data(this, dataPropertyName);
                bindings[eventType] = true;
                activeDocHandlers[eventType] = (activeDocHandlers[eventType] || 0) + 1;
                if(activeDocHandlers[eventType] === 1) {
                    $document.bind(realType, mouseEventCallback);
                }
                $(this).bind(realType, dummyMouseHandler);
                if(eventCaptureSupported) {
                    activeDocHandlers["touchstart"] = (activeDocHandlers["touchstart"] || 0) + 1;
                    if(activeDocHandlers["touchstart"] === 1) {
                        $document.bind("touchstart", handleTouchStart)
                            .bind("touchend", handleTouchEnd)
                            .bind("touchmove", handleTouchMove)
                            .bind("scroll", handleScroll);
                    }
                }
            },
            teardown: function(data, namespace) {
                --activeDocHandlers[eventType];
                if(!activeDocHandlers[eventType]) {
                    $document.unbind(realType, mouseEventCallback);
                }
                if(eventCaptureSupported) {
                    --activeDocHandlers["touchstart"];
                    if(!activeDocHandlers["touchstart"]) {
                        $document.unbind("touchstart", handleTouchStart)
                            .unbind("touchmove", handleTouchMove)
                            .unbind("touchend", handleTouchEnd)
                            .unbind("scroll", handleScroll);
                    }
                }
                var $this = $(this),
                    bindings = $.data(this, dataPropertyName);
                if(bindings) {
                    bindings[eventType] = false;
                }
                $this.unbind(realType, dummyMouseHandler);
                if(!hasVirtualBindings(this)) {
                    $this.removeData(dataPropertyName);
                }
            }
        };
    }
    for(var i = 0; i < virtualEventNames.length; i++) {
        $.event.special[virtualEventNames[i]] = getSpecialEventObject(virtualEventNames[i]);
    }
    if(eventCaptureSupported) {
        document.addEventListener("click", function(e) {
            var cnt = clickBlockList.length,
                target = e.target,
                x, y, ele, i, o, touchID;
            if(cnt) {
                x = e.clientX;
                y = e.clientY;
                threshold = $.vmouse.clickDistanceThreshold;
                ele = target;
                while(ele) {
                    for(i = 0; i < cnt; i++) {
                        o = clickBlockList[i];
                        touchID = 0;
                        if((ele === target && Math.abs(o.x - x) < threshold && Math.abs(o.y - y) < threshold) ||
                            $.data(ele, touchTargetPropertyName) === o.touchID) {
                            e.preventDefault();
                            e.stopPropagation();
                            return;
                        }
                    }
                    ele = ele.parentNode;
                }
            }
        }, true);
    }
})(jQuery, window, document);

(function($, window, undefined) {
    function triggercustomevent(obj, eventtype, event) {
        var origtype = event.type;
        event.type = eventtype;
        $.event.handle.call(obj, event);
        event.type = origtype;
    }

    $.event.special.tap = {
        setup : function() {
            var thisobj = this;
            var obj = $(thisobj);
            obj.on('vmousedown', function(e) {
                if(e.which && e.which !== 1) {
                    return false;
                }
                var origtarget = e.target;
                var origevent = e.originalEvent;
                var timer;

                function cleartaptimer() {
                    clearTimeout(timer);
                }
                function cleartaphandlers() {
                    cleartaptimer();
                    obj.off('vclick', clickhandler)
                        .off('vmouseup', cleartaptimer);
                    $(document).off('vmousecancel', cleartaphandlers);
                }

                function clickhandler(e) {
                    cleartaphandlers();
                    if(origtarget === e.target) {
                        triggercustomevent(thisobj, 'tap', e);
                    }
                    return false;
                }

                obj.on('vmouseup', cleartaptimer)
                    .on('vclick', clickhandler)
                $(document).on('touchcancel', cleartaphandlers);

                timer = setTimeout(function() {
                    triggercustomevent(thisobj, 'taphold', $.Event('taphold', {target:origtarget}));
                }, 750);
                return false;
            });
        }
    };
    $.each(('tap').split(' '), function(index, name) {
        $.fn[name] = function(fn) {
            return this.on(name, fn);
        };
    });

})(jQuery, this);

var page = {
    converthtml : function() {
        var prevpage = $('div.pg .prev').prop('href');
        var nextpage = $('div.pg .nxt').prop('href');
        var lastpage = $('div.pg label span').text().replace(/[^\d]/g, '') || 0;
        var curpage = $('div.pg input').val() || 1;

        if(!lastpage) {
            prevpage = $('div.pg .pgb a').prop('href');
        }

        var prevpagehref = nextpagehref = '';
        if(prevpage == undefined) {
            prevpagehref = 'javascript:;" class="grey';
        } else {
            prevpagehref = prevpage;
        }
        if(nextpage == undefined) {
            nextpagehref = 'javascript:;" class="grey';
        } else {
            nextpagehref = nextpage;
        }

        var selector = '';
        if(lastpage) {
            selector += '<a id="select_a" style="margin:0 2px;padding:1px 0 0 0;border:0;display:inline-block;position:relative;width:100px;height:31px;line-height:27px;background:url('+STATICURL+'/image/mobile/images/pic_select.png) no-repeat;text-align:left;text-indent:20px;">';
            selector += '<select id="dumppage" style="position:absolute;left:0;top:0;height:27px;opacity:0;width:100px;">';
            for(var i=1; i<=lastpage; i++) {
                selector += '<option value="'+i+'" '+ (i == curpage ? 'selected' : '') +'>第'+i+'页</option>';
            }
            selector += '</select>';
            selector += '<span>第'+curpage+'页</span>';
        }

        $('div.pg').removeClass('pg').addClass('page').html('<a href="'+ prevpagehref +'">上一页</a>'+ selector +'<a href="'+ nextpagehref +'">下一页</a>');
        $('#dumppage').on('change', function() {
            var href = (prevpage || nextpage);
            window.location.href = href.replace(/page=\d+/, 'page=' + $(this).val());
        });
    },
};

var img = {
    init : function(is_err_t) {
        var errhandle = this.errorhandle;
        $('img').on('load', function() {
            var obj = $(this);
            if(obj.width() < 5 && obj.height() < 10 && obj.css('display') != 'none') {
                return errhandle(obj, is_err_t);
            }
            if(obj.width() > window.innerWidth) {
                obj.css('width', window.innerWidth);
            }
            obj.parent().find('.loading').remove();
            obj.parent().find('.error_text').remove();
        }).on('error', function() {
            var obj = $(this);
            obj.attr('zsrc', obj.attr('src'));
            errhandle(obj, is_err_t);
        });
    },
    errorhandle : function(obj, is_err_t) {
        if(obj.attr('noerror') == 'true') {
            return;
        }
        obj.css('visibility', 'hidden');
        obj.css('display', 'none');
        var parentnode = obj.parent();
        parentnode.find('.loading').remove();
        parentnode.append('<div class="loading" style="background:url(../addons/meepo_bbs/public/images/imageloading.gif) no-repeat center center;width:'+parentnode.width()+'px;height:'+parentnode.height()+'px"></div>');
        var loadnums = parseInt(obj.attr('load')) || 0;
        if(loadnums < 3) {
            obj.attr('src', obj.attr('zsrc'));
            obj.attr('load', ++loadnums);
            return false;
        }
        if(is_err_t) {
            var parentnode = obj.parent();
            parentnode.find('.loading').remove();
            parentnode.append('<div class="error_text"></div>');
            parentnode.find('.error_text').one('click', function() {
                obj.attr('load', 0).find('.error_text').remove();
                parentnode.append('<div class="loading" style="background:url(../addons/meepo_bbs/public/images/imageloading.gif) no-repeat center center;width:'+parentnode.width()+'px;height:'+parentnode.height()+'px"></div>');
                obj.attr('src', obj.attr('zsrc'));
            });
        }
        return false;
    }
};

var atap = {
    init : function() {
        $('.atap').on('tap', function() {
            var obj = $(this);
            obj.css({'background':'#6FACD5', 'color':'#FFFFFF', 'font-weight':'bold', 'text-decoration':'none', 'text-shadow':'0 1px 1px #3373A5'});
            return false;
        });
        $('.atap a').off('click');
    }
};


var POPMENU = new Object;
var popup = {
    init : function() {
        var $this = this;
        $('.popup').each(function(index, obj) {
            obj = $(obj);
            var pop = $(obj.attr('href'));
            if(pop && pop.attr('popup')) {
                pop.css({'display':'none'});
                obj.on('click', function(e) {
                    $this.open(pop,'html');
                });
            }
        });
        this.maskinit();
    },
    maskinit : function() {
        var $this = this;
        $('#dark').off().on('tap', function() {$this.close();});
        $('#light').off().on('tap', function() {$this.close();});
    },

    open : function(pop, type, url) {
        this.close();
        this.maskinit();
        if(typeof pop == 'string') {
            $('#ntcmsg').remove();
            if(type == 'alert') {
                pop = '<div class="tip"><dt class="tc">'+ pop +'</dt><dd class="flex_box bo_t"><a href="javascript:;" class="cc flex" onclick="popup.close();">确定</a></dd></div>';
                var popwidth=window.innerWidth*0.8;
            } else if(type == 'confirm') {
                pop = '<div class="tip"><dt class="tc">'+ pop +'</dt><dd class="flex_box bo_t"><a class="cc flex bo_r" href="'+ url +'">确定</a><a href="javascript:;" class="flex" onclick="popup.close();">取消</a></dd></div>'
                var popwidth=window.innerWidth*0.8;
            }
            $('body').append('<div id="ntcmsg" style="display:none;">'+ pop +'</div>');
            pop = $('#ntcmsg');
        }
        if(type=='html'){
            var popwidth=window.innerWidth*0.8;
        }
        if(!popwidth){
            var popwidth=pop.width();
        }
        /*
         if(POPMENU[pop.attr('id')]) {
         $('#' + pop.attr('id') + '_popmenu').html(pop.html()).css({'height':pop.height()+'px', 'width':popwidth+'px'});
         } else {
         pop.parent().append('<div class="dialogbox" id="'+ pop.attr('id') +'_popmenu" style="height:'+ pop.height() +'px;width:'+ popwidth +'px;">'+ pop.html() +'</div>');
         }
         */
        pop.parent().append('<div class="dialogbox" id="'+ pop.attr('id') +'_popmenu" style="height:'+ pop.height() +'px;width:'+ popwidth +'px;">'+ pop.html() +'</div>');
        var popupobj = $('#' + pop.attr('id') + '_popmenu');
        var left = (window.innerWidth - popwidth) / 2;
        var top = (document.documentElement.clientHeight - popupobj.height()) / 2;
        popupobj.css({'display':'block','position':'fixed','left':left,'top':top,'z-index':1010,'opacity':1});
        $('#dark').css('display', 'block');
        $('#light').css({'display':'block','height':'50px'});
        POPMENU[pop.attr('id')] = pop;
    },
    close : function() {
        $('#dark').css('display', 'none');
        $('#light').css({'display':'none','height':''});
        $.each(POPMENU, function(index, obj) {
            $('#' + index + '_popmenu').remove();
            $('#ntcmsg').remove();
        });
    }
};

var dialog = {
    init : function() {
        $(document).on('click', '.dialog', function() {
            var obj = $(this);
            popup.open('<img src="../addons/meepo_bbs/public/images/imageloading.gif">');
            $.ajax({
                type : 'GET',
                url : obj.attr('href') + '&inajax=1',
                dataType : 'xml'
            }).success(function(s) {
                popup.open(s.lastChild.firstChild.nodeValue,'html');
                evalscript(s.lastChild.firstChild.nodeValue);
            }).error(function() {
                window.location.href = obj.attr('href');
                popup.close();
            });
            return false;
        });
    },
};

var showpage = {
    init : function() {
        $(document).on('click', '.showpage', function() {
            $('html').removeClass();
            var obj = $(this);
            var type = obj.attr('type');
            var id = obj.attr('id');

            if(type=='getpage'){
                var url=obj.attr('href') + '&getpage=1';
                var dataType='html';
            }else{
                var url=obj.attr('href') + '&inajax=1';
                var dataType='xml';
            }
            //if(url.indexOf('mobile=2')<0){
           //     var url= url + '&mobile=2';
            //}
            popup.open('<img src="../addons/meepo_bbs/public/images/imageloading.gif">');
            $.ajax({
                type : 'GET',
                url : url,
                dataType : dataType
            }).success(function(s) {
                if(type=='getpage'){
                    var html=s;
                }else{
                    var html=s.lastChild.firstChild.nodeValue;
                }
                if(html.indexOf('<div class="tip">')!=-1){
                    lastaction="popup.close();";
                    popup.open(html,'html');
                    evalscript(html);
                }else{
                    lastaction="closepage('#"+id+"_area');";
                    var main = $(html).find('.wrap').html();
                    $('body').append('<div id="'+id+'_area" class="showpagearea showpage_in"><div class="bodyarea"><div class="wrap">'+ main +'</div></div></div>');
                    popup.close();
                    evalscript(html);
                    $(document).on('click', '.showpagearea a.go', function() {
                        closepage('#'+id+'_area');
                    });
                }
            }).error(function() {
                //popup.close();
                window.location.href = obj.attr('href');
            });
            return false;
        });
    },
};


var gettab = {
    init : function() {
        $(document).on('click', '.gettab', function() {
            popup.open('<img src="../addons/meepo_bbs/public/images/imageloading.gif">');
            var obj = $(this);
            var tabid= obj.attr('tab');
            var geturl= obj.attr('href');

            if($('#'+tabid).length){
                var gettype= 'update';
            }else{
                var gettype= 'create';
            }
            var value = {
                url: geturl,
                type: gettype,
                tabid: tabid,
                html: ''
            };
            loadtab('ajax',value);

            return false;
        });
    },
};

var getpage = {
    init : function() {
        $(document).on('click', '.getpage', function() {

            var obj = $(this);
            var gettype= obj.attr('gettype');
            var getid= obj.attr('getid');
            var geturl= obj.attr('href');
            popup.open('<img src="../addons/meepo_bbs/public/images/imageloading.gif">');

            var value = {
                url: geturl,
                type: gettype,
                id: getid,
                html: ''
            };
            loadpage('ajax',value);
            popup.close();
            return false;
        });
    },
};
var formdialog = {
    init : function() {
        $(document).on('click', '.formdialog', function() {
            popup.open('<img src="../addons/meepo_bbs/public/images/imageloading.gif">');
            var obj = $(this);
            var formobj = $(this.form);

            $.ajax({
                type:'POST',
                url:formobj.attr('action') + '&handlekey='+ formobj.attr('id') +'&inajax=1',
                data:formobj.serialize(),
                dataType:'xml'
            }).success(function(s) {
                popup.open(s.lastChild.firstChild.nodeValue,'html');
                evalscript(s.lastChild.firstChild.nodeValue);
            }).error(function() {
                window.location.href = obj.attr('href');
                popup.close();
            });
            return false;
        });
    }
};

var redirect = {
    init : function() {
        $(document).on('click', '.redirect', function() {
            var obj = $(this);
            popup.close();
            window.location.href = obj.attr('href');
        });
    }
};

var DISMENU = new Object;
var display = {
    init : function() {
        var $this = this;
        $('.display').each(function(index, obj) {
            obj = $(obj);
            var dis = $(obj.attr('href'));
            if(dis && dis.attr('display')) {
                dis.css({'display':'none'});
                dis.css({'z-index':'102'});
                DISMENU[dis.attr('id')] = dis;
                obj.on('click', function(e) {
                    if(in_array(e.target.tagName, ['A', 'IMG', 'INPUT'])) return;
                    $this.maskinit();
                    if(dis.attr('display') == 'true') {
                        dis.css('display', 'block');
                        dis.attr('display', 'false');
                        $('#dark').css('display', 'block');
                    }
                    return false;
                });
            }
        });
    },
    maskinit : function() {
        var $this = this;
        $('#dark').off().on('touchstart', function() {
            $this.hide();
        });
    },
    hide : function() {
        $('#dark').css('display', 'none');
        $.each(DISMENU, function(index, obj) {
            obj.css('display', 'none');
            obj.attr('display', 'true');
        });
    }
};

var geo = {
    latitude : null,
    longitude : null,
    loc : null,
    errmsg : null,
    timeout : 5000,
    getcurrentposition : function() {
        if(!!navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(this.locationsuccess, this.locationerror, {
                enableHighAcuracy : true,
                timeout : this.timeout,
                maximumAge : 3000
            });
        }
    },
    locationerror : function(error) {
        geo.errmsg = 'error';
        switch(error.code) {
            case error.TIMEOUT:
                geo.errmsg = "获取位置超时，请重试";
                break;
            case error.POSITION_UNAVAILABLE:
                geo.errmsg = '无法检测到您的当前位置';
                break;
            case error.PERMISSION_DENIED:
                geo.errmsg = '请允许能够正常访问您的当前位置';
                break;
            case error.UNKNOWN_ERROR:
                geo.errmsg = '发生未知错误';
                break;
        }
    },
    locationsuccess : function(position) {
        geo.latitude = position.coords.latitude;
        geo.longitude = position.coords.longitude;
        geo.errmsg = '';
        $.ajax({
            type:'POST',
            url:'http://maps.google.com/maps/api/geocode/json?latlng=' + geo.latitude + ',' + geo.longitude + '&language=zh-CN&sensor=true',
            dataType:'json'
        }).success(function(s) {
            if(s.status == 'OK') {
                geo.loc = s.results[0].formatted_address;
            }
        }).error(function() {
            geo.loc = null;
        });
    }
};

var pullrefresh = {
    init : function() {
        var pos = {};
        var status = false;
        var divobj = null;
        var contentobj = null;
        var reloadflag = false;
        $('body').on('touchstart', function(e) {
            e = mygetnativeevent(e);
            pos.startx = e.touches[0].pageX;
            pos.starty = e.touches[0].pageY;
        }).on('touchmove', function(e) {
            e = mygetnativeevent(e);
            pos.curposx = e.touches[0].pageX;
            pos.curposy = e.touches[0].pageY;
            if(pos.curposy - pos.starty < 0 && !status) {
                return;
            }
            if(!status && $('.body_main').scrollTop() <= 0) {
                status = true;
                divobj = document.createElement('div');
                divobj = $(divobj);
                divobj.css({'position':'relative', 'margin-left':'-85px'});
                $('body').prepend(divobj);
                contentobj = document.createElement('div');
                contentobj = $(contentobj);
                contentobj.css({'position':'absolute', 'height':'30px', 'top': '-30px', 'left':'50%'});
                contentobj.html('<img src="source/plugin/cis_weixin/core/ui/arrow.png" style="vertical-align:middle; width:16px; margin-right:5px;-moz-transform:rotate(180deg);-webkit-transform:rotate(180deg);-o-transform:rotate(180deg);transform:rotate(180deg);"><span id="refreshtxt">下拉可以刷新</span>');
                contentobj.find('img').css({'-webkit-transition':'all 0.5s ease-in-out'});
                divobj.prepend(contentobj);
                pos.topx = pos.curposx;
                pos.topy = pos.curposy;
            }
            if(!status) {
                return;
            }
            if(status == true) {
                var pullheight = pos.curposy - pos.topy;
                if(pullheight >= 0 && pullheight < 150) {
                    divobj.css({'height': pullheight/2 + 'px'});
                    contentobj.css({'top': (-30 + pullheight/2) + 'px'});
                    if(reloadflag) {
                        contentobj.find('img').css({'-webkit-transform':'rotate(180deg)', '-moz-transform':'rotate(180deg)', '-o-transform':'rotate(180deg)', 'transform':'rotate(180deg)'});
                        contentobj.find('#refreshtxt').html('下拉可以刷新');
                    }
                    reloadflag = false;
                } else if(pullheight >= 150) {
                    divobj.css({'height':pullheight/2 + 'px'});
                    contentobj.css({'top': (-10 + pullheight/2) + 'px'});
                    if(!reloadflag) {
                        contentobj.find('img').css({'-webkit-transform':'rotate(360deg)', '-moz-transform':'rotate(360deg)', '-o-transform':'rotate(360deg)', 'transform':'rotate(360deg)'});
                        contentobj.find('#refreshtxt').html('松开可以刷新');
                    }
                    reloadflag = true;
                }
            }
            e.preventDefault();
        }).on('touchend', function(e) {
            if(status == true) {
                if(reloadflag) {
                    contentobj.html('<img src="source/plugin/cis_weixin/core/ui/icon_load.gif" style="vertical-align:middle;margin-right:5px;">正在加载...');
                    contentobj.animate({'top': (-30 + 75) + 'px'}, 618, 'linear');
                    divobj.animate({'height': '75px'}, 618, 'linear', function() {
                        window.location.reload();
                    });
                    return;
                }
            }
            divobj.remove();
            divobj = null;
            status = false;
            pos = {};
        });
    }
};

function mygetnativeevent(event) {
    while(event && typeof event.originalEvent !== "undefined") {
        event = event.originalEvent;
    }
    return event;
}

function evalscript(s,type) {
    if(s.indexOf('<script') == -1) return s;
    var p = /<script[^\>]*?>([^\x00]*?)<\/script>/ig;
    var arr = [];
    while(arr = p.exec(s)) {
        var p1 = /<script[^\>]*?src=\"([^\>]*?)\"[^\>]*?(reload=\"1\")?(?:id=\"([\w\-]+?)\")?><\/script>/i;
        var arr1 = [];
        arr1 = p1.exec(arr[0]);
        if(arr1) {
            reload = arr1[2] ? true : false;
            appendscript(arr1[1], '', reload, type, arr1[3]);
        } else {
            p1 = /<script[^\>]*?id=\"([^\>]*?)\"[^\>]*?(reload=\"1\")?>([^\x00]+?)<\/script>/i;
            arr1 = p1.exec(arr[0]);
            if(arr1){
                var reload = arr1[2] ? true : false;
                appendscript('', arr1[3], reload,type,arr1[1]);
            }else{
                p1 = /<script(.*?)>([^\x00]+?)<\/script>/i;
                arr1 = p1.exec(arr[0]);
                appendscript('', arr1[2], arr1[1].indexOf('reload=') != -1);
            }
        }
    }
    return s;
}

var safescripts = {}, evalscripts = [];
function appendscript(src, text, reload, type, id) {
    var JSLOADED = [];
    if(!id){
        var id = hash(src + text);
    }
    if(!reload && in_array(id, evalscripts)) return;
    if(reload && $('#' + id)[0]) {
        $('#' + id)[0].parentNode.removeChild($('#' + id)[0]);
    }else{
        evalscripts.push(id);
    }

    var scriptNode = document.createElement("script");
    scriptNode.type = "text/javascript";
    scriptNode.id = id;
    scriptNode.charset = !document.charset ? document.characterSet : document.charset;

    try {
        if(src) {
            scriptNode.src = src;
            scriptNode.onloadDone = false;
            scriptNode.onload = function () {
                scriptNode.onloadDone = true;
                JSLOADED[src] = 1;
            };
            scriptNode.onreadystatechange = function () {
                if((scriptNode.readyState == 'loaded' || scriptNode.readyState == 'complete') && !scriptNode.onloadDone) {
                    scriptNode.onloadDone = true;
                    JSLOADED[src] = 1;
                }
            };
        } else if(text){
            scriptNode.text = text;
        }

        document.getElementById('modscript').appendChild(scriptNode);

    } catch(e) {}
}

function hash(string, length) {
    var length = length ? length : 32;
    var start = 0;
    var i = 0;
    var result = '';
    filllen = length - string.length % length;
    for(i = 0; i < filllen; i++){
        string += "0";
    }
    while(start < string.length) {
        result = stringxor(result, string.substr(start, length));
        start += length;
    }
    return result;
}

function stringxor(s1, s2) {
    var s = '';
    var hash = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var max = Math.max(s1.length, s2.length);
    for(var i=0; i<max; i++) {
        var k = s1.charCodeAt(i) ^ s2.charCodeAt(i);
        s += hash.charAt(k % 52);
    }
    return s;
}

function in_array(needle, haystack) {
    if(typeof needle == 'string' || typeof needle == 'number') {
        for(var i in haystack) {
            if(haystack[i] == needle) {
                return true;
            }
        }
    }
    return false;
}

function isUndefined(variable) {
    return typeof variable == 'undefined' ? true : false;
}

function setcookie(cookieName, cookieValue, seconds, path, domain, secure) {
    if(cookieValue == '' || seconds < 0) {
        cookieValue = '';
        seconds = -2592000;
    }
    if(seconds) {
        var expires = new Date();
        expires.setTime(expires.getTime() + seconds * 1000);
    }
    domain = !domain ? cookiedomain : domain;
    path = !path ? cookiepath : path;
    document.cookie = escape(cookiepre + cookieName) + '=' + escape(cookieValue)
        + (expires ? '; expires=' + expires.toGMTString() : '')
        + (path ? '; path=' + path : '/')
        + (domain ? '; domain=' + domain : '')
        + (secure ? '; secure' : '');
}

function getcookie(name, nounescape) {
    name = cookiepre + name;
    var cookie_start = document.cookie.indexOf(name);
    var cookie_end = document.cookie.indexOf(";", cookie_start);
    if(cookie_start == -1) {
        return '';
    } else {
        var v = document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length));
        return !nounescape ? unescape(v) : v;
    }
}

$(document).ready(function() {

    if($('div.pg').length > 0) {
        page.converthtml();
    }
    if($('img').length > 0) {
        img.init(1);
    }
    if($('.popup').length > 0) {
        popup.init();
    }
    if($('.display').length > 0) {
        display.init();
    }
    if($('.atap').length > 0) {
        atap.init();
    }
    if($('.pullrefresh').length > 0) {
        pullrefresh.init();
    }
    showpage.init();
    getpage.init();
    gettab.init();
    dialog.init();
    formdialog.init();
    redirect.init();
});



/*--------------------add--------------------*/

$(document).on('click', '.scrolltop', function() {
    $('.body_main').scrollTop('0', '1');
});

$(document).on('input propertychange focus', '.autoheight', function() {
    this.style.height=this.scrollHeight + 'px';
})
$(document).on('click', '#autopbn', function() {
    var btn=$(this);
    var obj={};
    obj.id=btn.attr('tab');
    autopage(obj,'click');
    return false;
});

$(document).on('focus', '.imui_search_input', function() {
    $('.imui_search_text').hide();
});
$(document).on('blur', '.imui_search_input', function() {
    $('.imui_search_text').show();
});

function selectquestion(value){
    if(value){
        $('#answer').css('display', 'block');
    }else{
        $('#answer').css('display', 'none');
    }
}

function goto(url,tabid){
    popup.open('<img src="../addons/meepo_bbs/public/images/imageloading.gif">');
    //if(url.indexOf('mobile=2')<0){
    //    url= url + '&mobile=2';
   // }
    if($('#'+tabid).length){
        var gettype= 'update';
    }else{
        var gettype= 'create';
    }
    var value = {
        url: url,
        type: gettype,
        tabid: tabid,
        html: ''
    };
    loadtab('ajax',value);
    return false;
}
//getnextpage
var nextstar=2;
function getnextpage(url,id){
    $.ajax({
        type : 'GET',
        url : url+'&page='+nextstar,
        dataType : 'xml'
    }).success(function(s){
        if(s.lastChild.firstChild.nodeValue){
            $('#'+id).append(s.lastChild.firstChild.nodeValue);
            nextstar++;
        }else{
            $('#ajaxnext').css('display','none');
        }
    }).error(function() {
        return false;
    });
}
/*formsubmit*/
function formsubmit(id,url,tab){
    var formobj = $('#'+id+'_form');
    popup.open('<img src="../addons/meepo_bbs/public/images/imageloading.gif">');
    $.ajax({
        type:'POST',
        url:formobj.attr('action') + '&inajax=1&check=1',
        data:formobj.serialize(),
        dataType:'xml'
    }).success(function(s) {
        if(s.lastChild.firstChild.nodeValue!='ok'){
            popup.open(s.lastChild.firstChild.nodeValue,'html');
        }else{
            if(url && tab){
                goto(url,tab);
            }else{
                popup.open('操作成功', 'alert');
            }

        }
    }).error(function() {
        popup.open('Ajax请求失败','alert');
    });
    closepage('#'+id+'_area');
    return false;

}

function settime(value,id){
    if(value){
        var val=value.replace("T"," ");
        $('#'+id).val(val);
    }
}

//showbox
function showbox(id,type){
    if($('#'+id).css('display')=='none'){
        $('#'+id).fadeIn(300);
        if(type=='wrap'){
            $('#light').css({'display':'block','height':'100%'});
            $('#light').off().on('tap', function() {showbox(id);});
        }else{
            $('#light').css({'display':'block','height':'50px'});
            $('#dark').css('display', 'block');

            $('#light').off().on('tap', function() {showbox(id);});
            $('#dark').off().on('tap', function() {showbox(id);});
        }
    }else{
        $('#'+id).fadeOut(300);
        if(type=='wrap'){
            $('#light').css({'display':'none','height':''});
        }else{
            $('#light').css({'display':'none','height':''});
            $('#dark').css('display', 'none');
        }
    }
}

/*side*/
function openside(){
    if($('html').hasClass("openside")){
        $('html').removeClass('openside').addClass('closeside');
    }else{
        $('html').removeClass('closeside').addClass('openside');
    }
}
/*sheet*/
function opensheet(id){
    if($(id).hasClass("showsheet")){
        $(id).removeClass('showsheet').addClass('hidesheet');
        $('#light').css({'display':'none','height':''});
        $('#dark').css('display', 'none');
    }else{
        $(id).removeClass('hidesheet').addClass('showsheet');
        $(id).css('display', 'block');
        $('#light').css({'display':'block','height':'50px'});
        $('#dark').css('display', 'block');

        $('#light').off().on('tap', function() {opensheet(id);});
        $('#dark').off().on('tap', function() {opensheet(id);});

        $(document).on('click', '.imui_sheet a.bo_bl', function() {
            $(id).removeClass('showsheet').addClass('hidesheet');
            $('#light').css({'display':'none','height':''});
            $('#dark').css('display', 'none');
        });
    }
}


/*closepage*/
function closepage(id){
    lastaction="";
    $(id).remove();
}

/*creattab*/
function creattab(s,tabid,type){
    $('html').removeClass('closeside');
    var upid = $('.body_main').attr('id');
    var title = $(s).find('.title_name').text();

    var header = $(s).find('.headerarea').html();
    var mainarea = $(s).find('.mainarea').html();
    var main = $(s).find('.body_main').html();
    var footer = $(s).find('.footerarea').html();
    //var css = $(s).find('#modcssarea').html();

    sessionStorage.setItem(tabid, JSON.stringify({hd:header,ft:footer}));
    $('.headerarea').html(header);
    $('.headerarea').html(header);
    $('.footerarea').html(footer);
    $('title').text(title);

    if(type=='update'){
        var thisscroll = $(s).find('#'+tabid).attr('onscroll')?$(s).find('#'+tabid).attr('onscroll'):'';

        $('#'+tabid).attr('onscroll',thisscroll);
        $('#'+tabid).html(main);
        if(upid!=tabid){
            $('#'+tabid).attr('form',upid);
        }
        $('.body_main').addClass('main_hide').removeClass('body_main');
        $('#'+tabid).addClass('body_main').removeClass('main_hide');

    }else if(type=='create'){
        $('.body_main').addClass('main_hide').removeClass('body_main');
        $('.mainarea').append(mainarea);
        $('#'+tabid).attr('form',upid);
    }else if(type=='destroy'){
        if($('#'+tabid).length){
            if(upid!=tabid){
                $('#'+upid).remove();
                $('#'+tabid).addClass('body_main').removeClass('main_hide');
            }else{
                return false;
            }
        }else{
            $('.body_main').addClass('main_hide').removeClass('body_main');
            $('.mainarea').append(mainarea);
        }
    }
    if($(s).find('#'+tabid).attr('class')){
        evalscript(s);
    }
    popup.close();
}

/*loadtab*/
function loadtab(readtype,value){
    lastaction='';
    if(readtype=='ajax'){
        //if(value.url.indexOf('mobile=2')<0){
        //    value.url= value.url + '&mobile=2';
        //}
        if(value.url.indexOf('?')>0){
            var geturl= value.url + '&getpage=1';
        }else{
            var geturl= value.url + '?getpage=1';
        }
        var tabid=value.tabid;
        var type=value.type;
        $.ajax({
            type : 'GET',
            url : geturl,
            dataType : 'html'
        }).success(function(s) {
            if(s.indexOf('<div class="tip">')!=-1 && s.indexOf('<div class="bodyarea">')==-1){
                lastaction="popup.close();";
                popup.open(s,'html');
                return false;
            }
            if(!$(s).find('#'+tabid).attr('class')){
                window.location.href = value.url;
                popup.close();
                return false;
            }
            var hist=$(s).find('#'+tabid).attr('hist');
            creattab(s,tabid,type);
            var state = {
                url: value.url,
                tabid: tabid,
                html: s
            };
            if(!hist){
                lastaction='';
                if(type=='update'){
                    history.replaceState(state,null,value.url);
                }else{
                    history.pushState(state,null,value.url);
                    historylength++;
                }
            }else{
                lastaction="closetab('"+tabid+"');";
            }
        }).error(function() {
            window.location.href = value.url;
            popup.close();
        });
    }else{
        var tabid=value.tabid;
        var s=value.html;
        creattab(s,tabid,'destroy');
    }
}
/*creatpage*/
function creatpage(s,gettype,getid){
    $('html').removeClass('closeside');
    if(gettype=='one'){
        var content = $(s).find('.'+getid).html();
        $('.'+getid).html(content);
    }else if(gettype=='dispersed'){

        var header = $(s).find('.headerarea').html();
        var main = $(s).find('.mainarea').html();
        var footer = $(s).find('.footerarea').html();

        $('.headerarea').html(header);
        $('.mainarea').html(main);

        $('.footerarea').html(footer);
    }else{
        $('div.wrap').html($(s).find('.wrap').html());
    }
}
/*loadpage*/
function loadpage(readtype,value){

    if(value.url.indexOf('?')>0){
        var geturl= value.url + '&getpage=1';
    }else{
        var geturl= value.url + '?getpage=1';
    }
    var gettype=value.type;
    var getid=value.id;
    if(readtype=='ajax'){

        $.ajax({
            type : 'GET',
            url : geturl,
            dataType : 'html'
        }).success(function(s) {
            creatpage(s,gettype,getid);
            var state = {
                geturl:value.url,
                gettype: gettype,
                getid: getid,
                html: s
            };
            history.pushState(state,null,value.url);
            historylength++;
        }).error(function() {
            window.location.href = geturl;
        });
    }else if(readtype=='history'){
        var s=value.html;
        creatpage(s,gettype,getid);
    }
}

function closetab(id){
    var tabid=$('#'+id).attr('form');
    var upvar=sessionStorage.getItem(tabid);
    if(upvar){
        var upvar = JSON.parse(upvar);
    }
    $('.headerarea').html(upvar.hd);
    $('.footerarea').html(upvar.ft);
    $('#'+id).remove();
    $('#'+tabid).addClass('body_main').removeClass('main_hide');
}
/*preview*/
function preview(id){
    var file=$('#'+id).val();
    var arr=file.split('\\');
    var name=arr[arr.length-1];
    $('#'+id+'_name').html(name);
}

//addsmile
function addsmile(smile){
    var old=$("#postmessage")[0].value;
    $("#postmessage").val(old+smile);
}

//aituser
function aituser(){
    var ait=$("#aitarea")[0].value;
    var old=$("#postmessage")[0].value;
    if(ait){
        $("#postmessage").val(old+' @'+ait+' ');
        $("#aitarea").val('');
    }
}

// showhiden
function showhiden(id){
    if(document.getElementById(id+'_area').style.display=='none'){
        var hidenarea=document.getElementById('hidenarea');
        var hidens=hidenarea.childNodes;
        for(i=0; i<hidens.length; i++) {
            if(hidens[i].className=='ha'){
                hidens[i].style.display='none';
            }
        }
        $('#'+id+'_area').fadeIn(300);
    }else{
        $('#'+id+'_area').fadeOut(300);
    }
}

/*linkpage*/
function linkage(value,id,level,content){
    if(level=='second'){
        var nextlevel='third';
        var uplevel='first';
    }else if(level=='third'){
        var nextlevel='null';
        var uplevel='second';
    }
    if(level!='null'){
        var v=value.replace('.', '_');
        var lis = $('#dom_' + id +'_'+ level + '_' + v +' li');
        if(lis.length>0){
            var s='<div class="imui_blocks b_f imui_blocks_radio size_16">';
            for(i = 0;i < lis.length;i++) {
                var optionid = lis[i].getAttribute('optionid');
                s += '<label class="imui_block imui_check_label" for="laber_'+id+'_'+optionid+'_'+level+'"><div class="imui_block_bd flex"><p>'+ lis[i].innerHTML +'</p></div><div class="imui_block_ft"><input type="radio" class="imui_check" name="false_'+id+'_'+level+'" id="laber_'+id+'_'+optionid+'_'+level+'" value="'+optionid+'" onclick="linkage(this.value,\''+id+'\',\''+nextlevel+'\',\''+lis[i].innerHTML+'\')"/><span class="imui_icon_checked"></span></div></label>';
            }
            s += '</div>';
            $('#select_'+id+'_'+level+'_area').html(s);
            $('#select_'+id+'_'+level+'_area').css('display', 'block');
            $('#select_'+id+'_'+uplevel+'_area').css('display', 'none');
        }else{
            calllinkpage(id);
        }
    }else{
        calllinkpage(id);
    }
    if($('#'+id+'_back').css('display')=='none'){
        $('#'+id+'_back').css('display', 'block');
    }
    if(level=='second'){
        $('#'+id+'_var_1').html(content);
        $('#'+id+'_var_2').html('');
        $('#'+id+'_var_3').html('');
    }else if(level=='third'){
        $('#'+id+'_var_2').html(' - '+content);
        $('#'+id+'_var_3').html('');
    }else{
        $('#'+id+'_var_3').html(' - '+content);
    }

    $('#'+id+'_var_title').html('');
    $('#typeoption_'+id).val(value);
    $('#alert_'+id).removeClass('cc');
}

function calllinkpage(id){

    if($('#select_'+id+'_area').length){
        lastaction="";
        var main = $('#select_'+id+'_area').html();
        $('#select_'+id+'_area').remove();
        $('#select_'+id).html(main);
    }else{
        lastaction="calllinkpage('"+id+"');";
        var main = $('#select_'+id).html();
        $('#select_'+id).empty();
        $('#windowarea').append('<div id="select_'+id+'_area" class="showpagearea showpage_in">'+ main +'</div>');
    }
}

function backlinkpage(id){

    if($('#select_'+id+'_third_area').html()!='' && $('#select_'+id+'_third_area').css('display')!='none'){
        $('#select_'+id+'_third_area').css('display', 'none');
        $('#select_'+id+'_second_area').css('display', 'block');
    }else{
        if($('#select_'+id+'_second_area').html()!='' && $('#select_'+id+'_second_area').css('display')!='none'){
            $('#select_'+id+'_second_area').css('display', 'none');
            $('#select_'+id+'_first_area').css('display', 'block');
        }
    }
    if($('#select_'+id+'_second_area').css('display')=='none' && $('#select_'+id+'_third_area').css('display')=='none'){
        $('#'+id+'_back').css('display', 'none');
    }
}

//shar
function shar(config){
    if($('#shar_box').length){
        lastaction="";
        $('#light').css({'display':'none','height':''});
        $('#dark').css('display', 'none');
        $('#shar_box').remove();
    }else{
        lastaction="shar();";
        $('#light').css({'display':'block','height':'50px'});
        $('#dark').css('display', 'block');
        $('#light').off().on('tap', function() {shar();});
        $('#dark').off().on('tap', function() {shar();});
        if(BRO=='weixin'){
            var ins='<img src="../addons/meepo_bbs/public/images/shar_wx.png"/>';
            var classname='wx';
        }else if(BRO=='qq' || BRO=='uc'){
            var classname='llq';
            var ins ='<div id="immwashar"></div>';
        }else{
            var classname='llq';
            var ins ='<img src="../addons/meepo_bbs/public/images/shar_llq.png"/>';
        }
        $('body').append('<div class="shar_'+classname+'" id="shar_box">'+ins+'</div>');
        if(BRO=='qq' || BRO=='uc'){
            $(document).on('click', '.immwashar_list li', function() {
                $('#light').css({'display':'none','height':''});
                $('#dark').css('display', 'none');
                $('#shar_box').remove();
            });
            var share_obj = new immwashar('immwashar',config);

        }
    }
}

/*callpage*/
function callpage(id){
    if($('#'+id+'_area').length){
        lastaction="";
        var main = $('#'+id+'_area').html();
        $('#'+id+'_area').remove();
        $('#'+id).html(main);
    }else{
        lastaction="callpage('"+id+"');";
        var main = $('#'+id).html();
        $('#'+id).empty();
        $('#windowarea').append('<div id="'+id+'_area" class="showpagearea showpage_in">'+ main +'</div>');
    }
}
/*opentop*/
function opentop(id){
    if($('#'+id+'_area').length){
        lastaction="";
        var main = $('#'+id+'_area').html();
        $('#light').css({'display':'none','height':'','opacity':''});
        $('#dark').css('display', 'none');
        $('#'+id+'_area').remove();
        $('.body_main #'+id).html(main);
    }else{
        lastaction="opentop('"+id+"');";
        var main = $('.body_main #'+id).html();
        $('.body_main #'+id).empty();
        $('#windowarea').append('<div id="'+id+'_area" class="imui_toparea open">'+ main +'</div>');
        $('#light').css({'display':'block','height':'50px','opacity':'0'});
        $('#dark').css('display', 'block');
        $('#light').off().on('tap', function() {opentop(id);});
        $('#dark').off().on('tap', function() {opentop(id);});
        $(document).on('click', '.imui_toparea a', function() {
            lastaction="";
            var main = $('#'+id+'_area').html();
            $('#light').css({'display':'none','height':'','opacity':''});
            $('#dark').css('display', 'none');
            $('#'+id+'_area').remove();
            $('.body_main #'+id).html(main);
        });
    }
}

//openflickr
function openflickr(){
    if($('#imui_flickr').length){
        lastaction="";
        var main = $('#imui_flickr').html();
        $('#imui_flickr').remove();
        $('.body_main .imui_flickr').html(main);
        $('#light').css({'display':'none','height':''});
    }else{
        lastaction="openflickr();";
        var main = $('.body_main .imui_flickr').html();
        $('.body_main .imui_flickr').empty();
        $('#windowarea').append('<div id="imui_flickr" class="imui_flickr b_f size_16">'+ main +'</div>');
        $('#light').css({'display':'block','height':'100%'});
        $('#light').off().on('click', function() {openflickr();});

        $(document).on('click', '.imui_flickr a', function() {
            var main = $('#imui_flickr').html();
            $('#imui_flickr').remove();
            $('.body_main .imui_flickr').html(main);
            $('#light').css({'display':'none','height':''});
        });
    }
}

function loginout(formhash){
    popup.open('<img src="../addons/meepo_bbs/public/images/imageloading.gif">');
    $.ajax({
        type:'GET',
        url:'member.php?mod=logging&action=logout&formhash='+formhash+'&inajax=1&&check=1',
        dataType:'xml',
    }).success(function(s) {
        if(s.lastChild.firstChild.nodeValue!='ok'){
            popup.open(s.lastChild.firstChild.nodeValue);
        }else{
            $('.side_user').html('<img src="'+UC_API+'/avatar.php?uid=0&size=middle"><h3 class="size_16">游客</h3><p><a href="member.php?mod=logging&action=login" class="showpage" id="member" type="getpage">登录</a></p>');
            cis_setcookie('logout','1','2592000');
            popup.open('您已成功退出登录', 'alert');
        }
    }).error(function() {
        window.location.href = obj.attr('href');
        popup.close();
    });
}
/*cis_setcookie*/
function cis_setcookie(cookieName, cookieValue, seconds, path, domain, secure) {
    var cp = 'immwa';
    if(cookieValue == '' || seconds < 0) {
        cookieValue = '';
        seconds = -2592000;
    }
    if(seconds) {
        var expires = new Date();
        expires.setTime(expires.getTime() + seconds * 1000);
    }
    domain = !domain ? cookiedomain : domain;
    path = !path ? cookiepath : path;
    document.cookie = escape(cp + '_'+cookieName) + '=' + escape(cookieValue)
        + (expires ? '; expires=' + expires.toGMTString() : '')
        + (path ? '; path=' + path : '/')
        + (domain ? '; domain=' + domain : '')
        + (secure ? '; secure' : '');
}

$(window).on("load", function(event) {
    var tabid=$('.body_main').attr('id');
    var state = {
        url: window.location.href,
        tabid: tabid,
        html: $('body').html(),
    };
    history.replaceState(state,null,window.location.href);
    historylength++
});

$(window).on("popstate", function(event) {
    historylength--

    var state = event.originalEvent.state;
    if(!state){
    }else{
        if(lastaction){
            eval(lastaction);
        }
        if(state.html){
            if(state.tabid){
                loadtab('history',state);
            }else{
                getpage('history',state);
            }
        }else{
            goto(state.url,state.tabid);
        }
    }
    if(historylength<=0){
        $('.imui_hl').html('<a href="forum.php?mod=guide" class="imui_logo"><img src="source/plugin/cis_weixin/core/ui/logo.png"></a>');
    }
});