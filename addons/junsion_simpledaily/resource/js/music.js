function Player(el,src,callback,callbackThis){
  this.el = el;
  this.isPlay = false;
  if(src == undefined) {
    src = '';
  }
  this.init(src,callback,callbackThis); 
}
Player.prototype = {
  init: function(src,callback,callbackThis){
    var _this = this,
      attr = {
        loop: false, 
        preload: "auto",
        autoplay: true, 
        src: src
      };

    this._audio = new Audio;
    for (var i in attr){
      attr.hasOwnProperty(i) && i in this._audio && (this._audio[i] = attr[i]);
    }

    this.inited = false;
    $(this._audio).on('durationchange',function(){
      // 播放加载
      if(_this._audio.duration > 1 && callback) {
        _this.inited = true;
        callback.call(callbackThis);
      }
    });

    $(this._audio).on('ended',function(){
      // 播放结束
      _this._audio.currentTime = 0;
      if(callback){
        callback.call(callbackThis);
      }
      _this.isPlay = false;
      _this._play();
    });

    if(src != ''){
      this._audio.load();
    }

    if(typeof this.el !== 'string'){
      this.el.on('click', function(){
        _this._play();
      });      
    }
  },

  _load: function(){
    this._audio.load();
  },

  _src: function(src){
    this._audio['src'] = src;
  },

  _isplay: function(){
    return this.isPlay;
  },
  
  _play: function(){
    if(!this.isPlay){
      this._audio.play();
      if(typeof this.el !== 'string'){
        this.el.addClass('on');
      }
    }else{
      this._audio.pause();
      if(typeof this.el !== 'string'){
        this.el.removeClass('on');
      }
    }
    this.isPlay = !this.isPlay;
  },
  
  _playOn: function(){
    this._audio.play();
    if(typeof this.el !== 'string'){
      this.el.addClass('on');
    }
    this.isPlay = true;
  },
  
  _playOff: function(){
    this._audio.pause();
    if(typeof this.el !== 'string'){
      this.el.removeClass('on');
    }
    this.isPlay = false;
  },
}