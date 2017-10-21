(function() {
    'use strict';
    //监听事件
    wx.ready(function(){

        wx.onMenuShareAppMessage(sharedata);
        wx.onMenuShareTimeline(sharedata);

        var Timer = {
            start: function(){
                $('.timer').show();
                var t = 60;
                $('.timer').text(t+"'");
                this._timer = setInterval(function(){
                    $('.timer').text(--t+"'");
                }, 1000);
            },
            stop: function(){
                $('.timer').hide();
                clearInterval(this._timer);
            }
        };

        $('#record_button').on('touchstart', function(evt){
            $(this).addClass('record');
            Timer.start();
            evt.preventDefault();
        });

        $(document).on('touchend', function(evt){
            $('#record_button').removeClass('record');
            Timer.stop();
            evt.preventDefault();
        });

        $('.button_bar').velocity('transition.bounceIn', {
            duration: 1500,
            delay: 1000
        });

        Recorder("#record_button", function(err,res){
            if(!err){
                var serverId = res.serverId;
                $('.button_bar').hide();
                $('.status').show();
                $.ajax({
                  type: 'POST',
                  url: upload_url,
                  data:{serverId:serverId},
                  timeout: 15000,
                  success: function(res){
                    location.href = res;
                  },
                  fail: function(err) {
                      alert(JSON.stringify(err));
                  }
                });
            }else{
                //alert(JSON.stringify(err));
                alert('录音失败，请重新录制。');
            }
        });

    });

    //调整页面布局
    var baseWidth = 640;
    var deviceWidth = $(window).width();
    if(deviceWidth/baseWidth <= 0.5) {
        $('#doc').css('zoom', 0.5);
    }else if(deviceWidth/baseWidth >= 1.5) {
        $('#doc').css('zoom', 1.5);
    }else {
        $('#doc').css('zoom', deviceWidth/baseWidth);
    }

    //添加动画效果
    var $body = $('body');
    $body.find('.diaozhui-1').velocity('fadeIn', {
        duration: 2000
    });
    $body.find('.diaozhui-2').velocity('fadeIn', {
        duration: 2000,
        delay: 500
    });
    $body.find('.diaozhui-3').velocity('fadeIn', {
        duration: 2000,
        delay: 1000
    });
    $body.find('.diaozhui-4').velocity('fadeIn', {
        duration: 2000,
        delay: 1500
    });
    // $body.find('.diaozhui-5').velocity('fadeIn', {
    //     duration: 2000,
    //     delay: 2000
    // }).velocity('callout.pulse', {
    //     duration: 2000
    // });
    $body.find('.title').velocity('transition.bounceDownIn', {
        duration: 1000,
        delay: 1000
    });
    $body.find('.desc').velocity('transition.bounceUpIn', {
        duration: 1000,
        delay: 1000
    });
    $body.find('.house').velocity('fadeIn', {
        duration: 3000
    });
})();