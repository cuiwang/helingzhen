define(function (require, exports, module) {
    var moduleCommon = require('common');
    var isShowWarning = false;
    var isLock = false;

    //获取消息数量和签到人数
    var getCount = function () {
        $.extendGetJSON(moduleCommon.httpURL + $('#GetCount').val(), {}, function (data) {
            //获取大屏幕活动配置
            var screenConfig = $('body').data('screenConfig');
            $('#main>#joinCount #messagesCount').html(data.ChatCount + '<br/>条消息');
            $('#main>#joinCount #userCount').html(data.FansCount + '<br/>人签到');
            if (data.FansCount >= data.MaxFansCount * 0.8 && data.FansCount < data.MaxFansCount && !isShowWarning) {
                $('#locked').remove();
                $('body').append('<div id="warning">上墙消息数量已达80%，如有需要请联系工作人员!！</div>');
                setTimeout(function () {
                    $('#warning').remove();
                }, 1800000);
                $('#warning').hover(function () {
                    $(this).remove();
                });
                isShowWarning = true;
            } else if (data.FansCount < data.MaxFansCount * 0.8) {
                isShowWarning = false;
            }
            if (data.FansCount >= data.MaxFansCount && !isLock) {
                $('#warning').remove();
                $('body').append('<div id="locked"><span>上墙数量已满，请联系工作人员!</span></div>');
                isLock = true;
            } else if (data.FansCount < data.MaxFansCount) {
                $('#locked').remove();
                isLock = false;
            }
            setTimeout(function () { getCount(); }, 5000);
        }, function () {
            setTimeout(function () { getCount(); }, 10000);
        });
    };

    //消息数量和签到人数切换
    var countSwitch = function () {
        setInterval(function () {
            $('#main>#joinCount>.num').each(function (i, e) {
                var $this = $(e);
                if ($this.css("opacity") == 1) {
                    $this.animate({ "opacity": 0 }, function () {
                        $this.siblings('.num').show().animate({ "opacity": 1 });
                    });
                }
            });
        }, 5000);
    };

    //初始化
    exports.init = function () {
        $('body').on('active', function () {
            //显示消息数量和签到人数
            $('#main>#joinCount').show();
            //开始获取消息数量和签到人数
            getCount();
            //开始切换消息数量和签到人数
            countSwitch();
        });
    };
});