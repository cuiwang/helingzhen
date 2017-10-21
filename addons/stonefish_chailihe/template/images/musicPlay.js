(function($) {
   
    $.fn.musicPlay = function(options) {
        $.fn.musicPlay.defaults={
            code: 'list',
            baseDir: 'dist/'        //是必填的
        };

        $.fn.musicPlay.config = {
            _time:$('<span class="time"></span>'),
            _classPause:"icon-pause",
            _classPlay:"icon-play",
            _palyDiv:"div.player",
            _starBtn:"span.ctrl",
            _repeatBtn:"span.repeat",
            _selectBtn:"select.input-medium"
        }

        var opt = $.extend(true,$.fn.musicPlay.defaults,options||{}),
            _config = $.extend({}, $.fn.musicPlay.config),
            numObj = "";

        return this.each(function() {

            var player = window.player = new _mu.Player(opt),
            that = $(this),
            id = $(this).attr("id"),
            $i = $("#"+id).find("span.ctrl").find("i"),
            $siblings = $("#"+id).find(_config._palyDiv),
            reset = function() {
                $siblings.find("span.ctrl").removeClass('playing pause');
                _config._time.remove();
            },
            findCurrItem = function() {
                return $siblings.find('span.ctrl');
            };

            reset();

            $("#"+id).on("change",_config._selectBtn,function(){
                var _this = $(this).find("option:selected"),
                    _thisHtml = _this.html();
                    _thisIink = $(this).find("option:selected").data("link");
                    numObj = $(this).closest("div.playlist-box");
                    numObj.find("span.text").html(_thisHtml);
                    numObj.find("span.ctrl").data("link",_thisIink);
                    
                    if(!_thisIink){
                        reset();
                    }
                    if(!_this){                                 
                        $i.removeClass(_config._classPlay).addClass(_config._classPause);
                        player.pause();
                    }else{
                        $i.removeClass(_config._classPause).addClass(_config._classPlay);
                        player.reset().add(_thisIink).play(); 
                    }
            });

            $("#"+id).on("click",_config._starBtn,function(){
                var _this = $(this);
                numObj = _this.closest("div.playlist-box");
                if(!_this.data("link")){
                    reset();
                    player.stop();
                    return;
                }

                if(_this.hasClass("playing pause")){
                    _this.removeClass("playing");
                }

                if(_this.hasClass("playing")){                          
                    $i.removeClass(_config.classPlay).addClass(_config._classPause);  //再次点击暂停             
                    player.pause();
                    _this.removeClass("playing")
                }else{
                    $i.removeClass(_config._classPause).addClass(_config._classPlay); 
                    if(_this.hasClass("pause")){
                        player.play();
                        _this.removeClass("pause")
                    }else{
                        player.reset().add(_this.data('link')).play();              //播放列表和内核资源重置  再次添加当前播放的歌曲地址播发
                    }
                }
            });

            $("#"+id).on("click",_config._repeatBtn,function(){
                var link = $(this).siblings("span.ctrl").data("link");
                numObj = $(this).closest("div.playlist-box");
                if(link){
                    $i.removeClass(_config._classPause).addClass(_config._classPlay);
                    player.reset().add(link).play();
                }
            })

            player.on('playing pause', function() {
                if(numObj!="" && numObj!="undefind"){
                    numObj.find("span.ctrl").addClass(player.getState());
                    numObj.find("div.opts").append(_config._time);
                }else{
                    console.log(_config._time.length)
                    _config._time.remove();
                    reset()
                }

            }).on('ended', reset).on('timeupdate', function() {
                _config._time.text(player.curPos(true) + ' / ' + player.duration(true));
            });

        
            
        })
    }
})(jQuery);