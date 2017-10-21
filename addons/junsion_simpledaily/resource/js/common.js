

// 提供 app 音乐暂停接口
function appPauseMusic() {
  musicView.audioPlayer.isPlay = true;
  musicView.audioPlayer._play();
}
// 提供 app 音乐播放接口
function appPlayMusic() {
  musicView.audioPlayer.isPlay = false;
  musicView.audioPlayer._play();
}

function androidMusicInit() {
  musicView.audioPlayer.isPlay = false;
  musicView.audioPlayer._play();
}

function escapeHtml(text) {
  if (text.length == 0){ return "" };

  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;',
    " ": '&nbsp;'
  };

  return text.replace(/[&<>"' ]/g, function(m) { return map[m]; });
}

function decodeEscapeHtml(text) {
  if (text.length == 0){ return "" };

  var map = {
    '&amp;': '&',
    '&lt;': '<',
    '&gt;': '>',
    '&quot;': '"',
    '&#039;': "'",
    '&nbsp;': ' '
  };

  return text.replace(/(&nbsp;|&amp;|&lt;|&gt;|&quot;|&#039;)/g, function(m) { return map[m]; });
}

function trimImgUrl(url){
    return  url.replace(/\!\/fw\/\d+/gi,'')
        .replace(/\/format\/(jpg|webp)/gi,'')
        .replace(/(\!){0,1}\/rotate\/\d+/gi,'')
        .replace(/\!640(jpg|webp)/gi,'')
        .replace(/\/640/gi,'')
        .replace(/\/+$/gi,'');
}

function getBaseUrl(url) {
    var parser = document.createElement('a');
    parser.href = url;
    return trimImgUrl(parser.pathname);
}

var jsTunnel = {
    loadedImg : function() {
        var pics = $('#picsViewGroup .picsItem .picDiv');
        for(var i=0;i<pics.length;++i){
            pics.eq(i).data('key',i+1);
        }

        var imgs = $('#picsViewGroup .picsItem .picImg img');
        var coverImg = $('.coverSetting img:first-child');
        imgs.push(coverImg[0]);

        jsTunnel.unloadImgCount = imgs.length;
        function picsLoaded() {
            $('.loadingWrap').remove();
            var egretImgs = [];
            //添加图片
            for(var idx=imgs.length-2;idx>=0;--idx) {
                if(imgs[idx] != null) {
                    egretImgs.push(imgs[idx]);
                }
            }
            //添加标题和图标
            egretImgs.push(imgs[imgs.length-1]);
            $("#OptionBar").show();
            jsTunnel.egretCallBack('picsLoaded',[egretImgs]);
        }
        for(var idx=0,len=imgs.length;idx<len;++idx){
            if(imgs[idx].complete == true || imgs[idx].readyState == 'complete') {
                --jsTunnel.unloadImgCount;
                jsTunnel.callExtend("changeLoaderPro",imgs.length - jsTunnel.unloadImgCount,imgs.length);
            } else {
                imgs[idx].onload = function() {
                    --jsTunnel.unloadImgCount;
                    jsTunnel.callExtend("changeLoaderPro",imgs.length - jsTunnel.unloadImgCount,imgs.length);
                    if(jsTunnel.unloadImgCount == 0){
                        picsLoaded();
                    }
                }
                imgs[idx].onerror = (function(idx) {
                    return function() {
                        --jsTunnel.unloadImgCount;
                        imgs[idx].style.width = "100%";
                        imgs[idx].style.height = "100%";
                        imgs[idx].style.borderRadius = "7px";
                        imgs[idx] = null;
                        jsTunnel.callExtend("changeLoaderPro",imgs.length - jsTunnel.unloadImgCount,imgs.length);
                        if(jsTunnel.unloadImgCount == 0){
                            picsLoaded();
                        }
                    }
                })(idx);
            }
        }
        if(jsTunnel.unloadImgCount == 0){
            console.log('finish load imgs');
            picsLoaded();
        }
    },
    analysisPhone:function(text) {
        var phoneArray = [];
        var textReg = new RegExp("^\\d{3}[ -]{0,1}\\d{4}[ -]{0,1}\\d{4}$"+"|"
            +"^\\d{3}[ -]{0,1}\\d{4}[ -]{0,1}\\d{4}\\D"+"|"
            +"\\D\\d{3}[ -]{0,1}\\d{4}[ -]{0,1}\\d{4}$"+"|"
            +"\\D\\d{3}[ -]{0,1}\\d{4}[ -]{0,1}\\d{4}\\D"+"|"
            +"^\\d{4}[ -]\\d{7}$"+"|"
            +"^\\d{4}[ -]\\d{7}\\D"+"|"
            +"\\D\\d{4}[ -]\\d{7}$"+"|"
            +"\\D\\d{4}[ -]\\d{7}\\D","g");
        var phoneReg = new RegExp("\\d{3}[ -]{0,1}\\d{4}[ -]{0,1}\\d{4}"+"|"
            +"\\d{4}[ -]\\d{7}");
        text = text.replace(textReg, function(phone) {
            phone = phone.replace(phoneReg,function(v) {
                phoneArray.push(v);
                return "__replace__";
            });
            return phone;
        });
        var textArray = text.split("__replace__");
        var res = [];
        do {
            var val = textArray.pop();
            var phone = phoneArray.pop();
            if(val) {
                res.push({'type':'text', 'value': val });
            }
            if(phone) {
                res.push({'type':'phone', 'value': phone });
            }
        } while ( phoneArray.length || textArray.length );
        return res.reverse();
    },
    callExtend: function(){
        switch(arguments[0]) {
            case 'getPics':
                return mvCfg.pics;
            case 'initFinish':
                initFunc();
                musicView.init();
                if(typeof android != "undefined") {
                    if(typeof android.musicInit != "undefined") {
                        android.musicInit();
                    } else {
                        alert('no defined android.musicInit');
                    }
                }
                if(typeof musicPrepared != "undefined") {
                    musicPrepared({"load":"ok"});
                }
                jsTunnel.loadedImg();
                return;
            case 'changeLoaderPro':
                var text = $('#loadingPro');
                text.html(Math.floor(arguments[1]*100/arguments[2])+"%");
                return;
            case 'changeLoaderText':
                var text = $('#loadingText');
                text.html(arguments[1]);
                return;
            case 'analysisPhone': {
                return jsTunnel.analysisPhone(arguments[1]);
            }
        }
    },
    egretCallBack : function(){}
}