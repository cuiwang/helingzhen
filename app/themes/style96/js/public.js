/** 我的css3动画类 */
var css3Animate = function(node, opat, time, easing, callback, onbegin){
    return this instanceof css3Animate ? this.init.apply(this, arguments) : new css3Animate(node, opat, time, easing, callback, onbegin);
}
css3Animate.prototype = {
    init: function(node, opat, time, easing, callback, onbegin){
        this.fire(node, opat, time, easing, callback, onbegin);
    },
    fire: function(node, opat, time, easing, callback, onbegin){
        var self = this, nodeTemp, i, resureVar;
        resureVar = self.resureVar;
        //代码防御node,opat必须存在
        //设置默认
        time = resureVar(time, 500);
        easing = resureVar(easing, 'easeInOutExpo');
        easing = self.easingObj[easing] || 'ease';
        callback = resureVar(callback, function(){});
        onbegin = resureVar(onbegin, function(){});
        nodeTemp = $(node);
        onbegin.call(node);
        //为什么要用定时器,是因为渲染需要时间吧
        setTimeout(function(){
            //设置动画属性
            opat['transition'] = 'all '+ time +'ms '+ easing;
            //设置样式
            nodeTemp.css(opat);
            //这里用了定时器，比事件要稳定
            setTimeout(function(){
                //去除动画属性, 用removeProperty在chrom无法正确去除,不晓得为啥
                node.style['-webkit-transition'] = '';
                node.style.removeProperty('-webkit-transition');
                callback.call(nodeTemp);
            }, time);
        }, 16);
        return self;
    },
    resureVar: function(tar, obj){
        return tar === undefined ? obj : tar;
    },
    easingObj: {
        'easeInOutExpo': 'cubic-bezier(.9, 0, .1, 1)',
        'easeOutExpo': 'cubic-bezier(0, 0, .1, 1)',
        'easeInExpo': 'cubic-bezier(.9, 0, 1.0, 1.0)',
        'linear': 'linear'
    }
}
/**
 * 基于手机的切换，可以用于 table banner 由于使用的的计算是百分比，所以这里请注意!!!
 * obj = {
 *          box: '',        //默认为空  字符串 比如 '#banner'
            mov: '',        //默认为空
            lis: '',        //默认为空  
            auto: 0,        //默认为0, 不自动切换 需要数字 比如 500
            event: false,   //默认为false, 不绑定事件
            guid: 0,        //默认重0开始
            oneMax: 70      //默认为70, 一次滑动的阀值 0 ~ 100  100为没有控制
 *      }
 * 提供的事件 onbegin change
 * HTML结构
    <div class="banner" id="bannerBox">
        <ul style="width:300%;left:0%;">
            <li style="width:33.33%;></li>
            <li style="width:33.33%;></li>
            <li style="width:33.33%;></li>
        </ul>
    </div>
 */
var phoneTable = function(obj){
    return this instanceof phoneTable ? this.init.apply(this, arguments) : new phoneTable(obj);
}
phoneTable.prototype = {
    init: function(obj){
        this.option = $.extend({
            box: '',
            mov: '',
            lis: '',
            auto: 0,
            guid: 0,
            event: false,
            oneMax: 90
        }, obj);
        this.event = {
            begin: function(){},
            change: function(){}
        }
        return this;
    },
    //开始绑定事件
    fire: function(){
        var self = this,
            $ = jQuery,
            option = self.option,
            box = self.box = $(option.box),
            mov = self.mov = $(option.mov),
            lis = self.lis = $(option.lis),
            eve = self.event,
            change = eve.change,
            guid = -option.guid,
            auto = option.auto,
            DOC = $(document),
            whereFlag,
            timeoutID,
            max = 1 - lis.length;
        //代码防御 ， 这3个 box, mov, lis 是必须的
        if(!box.length || !mov.length || !lis.length)
            return self;
        //设置重第几个开始
        //控制guid 的最大最小值
        if(guid > 0){
            guid = 0;
        }else if(guid < max){
            guid = max;
        }
        mov.css('left', guid*100+'%');
        //onbegin事件
        eve.begin(guid, box, mov, lis);
        //动画函数
        function todoLeft(num, flag){
            //控制最大最小两个区间 num 只会在  -4 ~ 0 之间
            if(num > 0){
                num = guid = 0;
            }else if(num < max){
                if(flag){
                    num = guid = 0;
                }else{
                    num = guid = max;
                }
            }
            //自定义事件
            change(-num, box, mov, lis);
            //动画
            css3Animate(mov[0], {
                left: num * 100 + '%'
            }, 500, 'easeOutExpo');
            //定时器
            auto && (timeoutID = self.timeoutID = setTimeout(function(){
                todoLeft(--guid, true);
            }, auto));
        }
        //如果是要自动的
        todoLeft(guid, true);
        //如果不需要绑定事件
        if(!option.event)
            return self;
        //事件开始绑定
        //手指按下
        box.on('vmousedown.phoneTable', function(e){
            var nowLeft = e.screenX,
                newperleft = parseFloat(mov[0].style['left']),
                bannerBoxWidth = box.width();
            //清楚定时器
            clearTimeout(timeoutID);
            DOC.on('vmousemove.phoneTable', function(e){
                var thisLeft = e.screenX,
                    //得到当前的滑动值
                    toLeft = thisLeft - nowLeft,
                    //获取百分比
                    perLeft = toLeft/bannerBoxWidth*100 + newperleft;
                //设置 手指是往哪边滑的
                if(toLeft > 0){
                    whereFlag = false;
                }else{
                    whereFlag = true;
                }
                mov.css({
                    left: perLeft+'%'
                });
                e.preventDefault();
            });
            //手指松开
            DOC.on('vmouseup.phoneTable', function(e){
                //清楚定时器
                clearTimeout(timeoutID);
                //获取当前的left的百分比
                var newperleft = parseFloat(mov[0].style['left']);
                DOC.off('vmouseup.phoneTable');
                DOC.off('vmousemove.phoneTable');
                console.log(whereFlag);
                //如果手指左滑动
                if(whereFlag){
                    --guid;
                    if(newperleft > guid*100 + option.oneMax){
                        ++guid;
                    }
                }
                //如果手指右滑动
                else{
                    ++guid;
                    if(newperleft < guid*100 - option.oneMax){
                        --guid;
                    }
                }
                todoLeft(guid, false);
            });

        });
        
        return self;
    },
    //解除事件
    dead: function(){
        var self = this,
            box = self.box;
        clearTimeout(self.timeoutID);
        if(box){
            box.off('vmousedown.phoneTable');
        }
        return self;
    },
    on: function(ename, fn){
        var self = this,
            eve = self.event;
        //注册事件
        if(eve.hasOwnProperty(ename)){
            eve[ename] = fn;
        }
        return self;
    }
}
;(function (window,$) {
    var onBridgeReady = function () {

        $(document).trigger('bridgeready');

        var $body = $('body'), appId = '',
            title = $body.attr('wsd-title'),
            imgUrl = $body.attr('wsd-icon'),
            link = $body.attr('wsd-link'),
            desc = $body.attr('wsd-desc') || link;
        if (!setForward()) {
            $(document).bind('weishidaichanged', function () {
                setForward();
            });
        }
    };
    if (document.addEventListener) {
        document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
    } else if (document.attachEvent) {
        document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
        document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
    }

    function setForward() {
        var $body = $('body'), //appId = '',
            title = $body.attr('wsd-title'),
            imgUrl = $body.attr('wsd-icon'),
            link = $body.attr('wsd-link'),
            desc;
        //防止分享的时候带入微信ID
        link = link.replace(/&wxid=[^&]*/, '');
        desc = $body.attr('wsd-desc') || link;
        if (title && link) {
            WeixinJSBridge.on('menu:share:appmessage', function (argv) {
                WeixinJSBridge.invoke('sendAppMessage', {
                    //'appid': 'wx8e4c7364a259befb',
                    'img_url': imgUrl?imgUrl:undefined,
                    'link': link,
                    'desc': desc?desc:undefined,
                    'title': title
                }, function (res) {
                    if (res && res['err_msg'] && res['err_msg'].indexOf('confirm') > -1) {
                        $(document).trigger('wx_sendmessage_confirm');
                    }
                });
            });
            WeixinJSBridge.on('menu:share:timeline', function (argv) {
                $(document).trigger('wx_timeline_before');
				
                WeixinJSBridge.invoke('shareTimeline', {
                    'img_url': imgUrl?imgUrl:undefined,
                    'link': link,
                    'desc': desc?desc:undefined,
                    'title': title
                }, function (res) {
                    //貌似目前没有简报
                });
            });
            /*
            WeixinJSBridge.on('menu:share:weibo', function (argv) {
                WeixinJSBridge.invoke('shareWeibo', {
                    'content': title + desc,
                    'url': link
                }, function (res) {

                });
            });
            */
            return true;
        }
        else {
            return false;
        }
    }
})(window,jQuery);

$(function() {
	//首页模板背景自适应高度
	var HTML = document.documentElement,
        wsdBanner = $('#bannerBox'),
		wsdContent = $('#wsdContent'),
        wsdFooter = $('#wsdFooter'),
        footHieght = wsdFooter.height(),
        bannerHeight = wsdBanner.height();
	//代码防御
	if(!wsdContent.length)
		return;
	function main(){
		wsdContent.css('height', 'auto');
		var winHieght = HTML.clientHeight,
			contentHeight = wsdContent.height(),
			num = winHieght - footHieght - bannerHeight;
		if(contentHeight < num)
			wsdContent.css('height', num+'px');
	}
	main();
	$(window).on('resize', main);
});

function back(){
	if(window.history.length<=1){
		window.location.href = location.protocol+'//'+location.host+'/?wxsd=mp.weixin.qq.com';
	}else{
		window.history.back();
	}
}
//微信ID获取，然后存到COOKIE中
;(function(){
    var wxid = localStorage.getItem('wxid');
    function setCookie(name,value,day){
        var date = new Date();
        date.setDate(date.getDate()+day);
        document.cookie = name+"="+escape(value)+((day==null)?"":";expires="+date.toGMTString());
    }
    wxid && setCookie('wxid', wxid, 365);
})();