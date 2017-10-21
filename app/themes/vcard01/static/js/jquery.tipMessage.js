//浏览器版本
var browser = {};
var ua = navigator.userAgent.toLowerCase();
var browserStr;
(browserStr = ua.match(/msie ([\d]+)/)) ? browser.ie = browserStr[1] :
    (browserStr = ua.match(/firefox\/([\d]+)/)) ? browser.firefox = browserStr[1] :
    (browserStr = ua.match(/chrome\/([\d]+)/)) ? browser.chrome = browserStr[1] :
    (browserStr = ua.match(/opera.([\d]+)/)) ? browser.opera = browserStr[1] :
    (browserStr = ua.match(/version\/([\d]+).*safari/)) ? browser.safari = browserStr[1] : 0;
var isPad = navigator.userAgent.match(/iPad|iPhone|iPod|Android/i) != null;
//tip消息提示
(function ($) {
    jQuery.tipMessage = function (msg, type, time, zIndex, callback) {
        if (typeof tipMessageTimeoutId !== 'number') {
            tipMessageTimeoutId = 0
        }
        if (typeof time !== 'number') {
            time = 2000
        }
        if (typeof zIndex !== 'number' || zIndex == 0) {
            zIndex = 65500
        }
        var $doc = $(document);
        var $win = $(window);
        var $tipMessage = $('#tipMessage');
        var _typeTag = '';
        var _newTop = 0;
        var _newLeft = 0;
        var _width = 0;
        var _NumCount = 1;
        var _mask = "";

        if ($tipMessage.length <= 0) {
            $("body").append('<div id="tipMessage" class="tip_message" ></div>');
            $tipMessage = $('#tipMessage');
        } else {
            if (browser.ie == 6 || browser.ie == 7) {
                $tipMessage.css({
                    width: '99%'
                });
            } else {
                $tipMessage.css({
                    width: 'auto'
                });
            }
        }
        $tipMessage.css({
            opacity: 0,
            zIndex: zIndex
        });
        clearTimeout(tipMessageTimeoutId); //清除旧的延时事件

        if (type == 1) {
            _typeTag = 'hits';
        } else if (type == 2) {
            _typeTag = 'fail';
        } else {
            _typeTag = 'succ';
        }
        if (browser.ie == 6) {
            _mask = '<iframe frameborder="0" scrolling="no" class="ie6_mask"></iframe>';
        }
        $tipMessage.html(_mask + '<div class="tip_message_content"><span class="tip_ico_' + _typeTag + '"></span><span class="tip_content" id="tip_content">' + msg + '</span><span class="tip_end"></span></div>').show();


        //计算top,left 值

        function _calculate() {
            _width = $('#tip_content').width() + 86; //计算tip宽度
            if ($doc.scrollTop() + $win.height() > $doc.height()) {
                _newTop = $doc.height() - $win.height() / 2 - 40;
            } else {
                _newTop = $doc.scrollTop() + $win.height() / 2 - 40;
            }

            if ($win.width() >= $doc.width()) {
                _newLeft = $doc.width() / 2 - _width / 2;
            } else {
                if ($win.width() <= _width) {
                    if ($doc.scrollLeft() + $win.width() + (_width - $win.width()) / 2 > $doc.width()) {
                        _newLeft = $doc.width() - _width;
                    } else {
                        _newLeft = $doc.scrollLeft() + $win.width() / 2 - _width / 2;
                    }
                } else {
                    //alert(1);
                    _newLeft = $doc.scrollLeft() + $win.width() / 2 - _width / 2;

                }
            }
            if (_newLeft < 0) {
                _newLeft = 0;
            }
        }
        _calculate(); //计算top,left 值
        $tipMessage.css({
            top: _newTop,
            left: _newLeft,
            width: _width,
            opacity: 10
        });

        //重置

        function _reSet() {
            _calculate(); //从新计算top,left 值
            $tipMessage.css({
                top: _newTop,
                left: _newLeft,
                width: _width
            });
        }
        //调整大小

        function _resize() {
            if (_NumCount % 2 == 0) { //解决IE6下scrollLeft值问题
                _reSet();
                _NumCount = 1;
            } else {
                ++_NumCount;
            }
        }
        if (!isPad) { //pad设备不支持浮动
            $win.bind({
                "scroll": _reSet,
                "resize": _resize
            });
        }
        tipMessageTimeoutId = setTimeout(function () {
            $tipMessage.remove();
            if (typeof callback == 'function') {
                callback.call();
            }
        }, time);
		$(document).click(function(event) {
			$tipMessage.remove();
		});
    };
})(jQuery);