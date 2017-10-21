/* 设置要保存的数据  */

// upyun.set('bucket','chuchuang');
// upyun.set('form_api_secret', 'ktozXGPu9C1ymlD0jIV+a/Efn5M=');

/* musicView **********************************/
/* musicView **********************************/
var isMusic=false;
var musicView = {
  audioPlayer : undefined,
  musicViewElem: undefined,

  init : function () {
    this.musicViewElem = $('#MusicView');
    
    // 初始化音乐
    var musicItem = $('#MusicItem'+mvCfg.music);
    var musicBtn = $('#AudioBtn');
    if(musicItem.length > 0) {
      // 音乐加载
      this.audioPlayer = new Player(musicBtn,musicItem.eq(0).find('.musicStatus').data('info'),function(){});
      this.audioPlayer.isPlay = false;
      this.audioPlayer._play();
      this.chooseIdx = mvCfg.music;
      
      musicItem.addClass('select');


      
    }
  }
}
musicView.init();
musicView.initEdit = function () {
    // 取消选择音乐
    var ulItem = $('#MusicList');
    $('#MusicView .musicType span').on('click', function () {
        var className = $(this).attr('class');
        $(this).parent().parent().attr('class','musicCates '+className);
        
        $('.bottomLine').removeClass('bottomLine');
        $(this).parent().attr('class','bottomLine');
        $('.musicBlock').removeClass('musicBlock');
        ulItem.find('.'+className).addClass('musicBlock');
    });

    // 取消选择
    $('#MusicCancel').on('click', function () {
        var musicItem = $('#MusicItem'+mvCfg.music);
        if(musicView.chooseIdx != undefined){
        	// 音乐选择保存
            if(musicView.chooseIdx != mvCfg.music) {
                var selectMusicItem = $('#MusicItem'+musicView.chooseIdx);

                if(selectMusicItem.length > 0) {
                    selectMusicItem.eq(0).removeClass('select');
                }
                var musicUrl = "";
                if(musicItem.length > 0) {
                    musicItem.eq(0).addClass('select');
                    musicUrl = musicItem.eq(0).find('.musicStatus').data('info');
                }
                if(musicUrl!=""){
                    musicView.audioPlayer._src(musicUrl);
                    musicView.audioPlayer._load();
                    musicView.chooseIdx = mvCfg.music;
                }
            }
        }
        

        musicView.hideMusicView();
        $('#AudioBtn').addClass('on');
    });

    // 确定选择音乐
    $('#MusicSelect').on('click', function () {
        mvCfg.music = musicView.chooseIdx;
        //mvChange.music = musicView.chooseIdx;
        musicView.hideMusicView();
        // 重置音乐
        //musicView.audioPlayer._audio.currentTime = 0;
        //musicView.audioPlayer.isPlay = false;
        //musicView.audioPlayer._play();
        if($('.select').find('.musicItemTitle').html()=="无音乐"){
            $('.top_muise_set').text("点击添加音乐");
        }else{
            $('.top_muise_set').text($('.select').find('.musicItemTitle').html());
        }
        $('.jj_musicid').text(musicView.chooseIdx);
        $('#AudioBtn').addClass('on');

    });

    // 绑定选择音乐事件
    $(".musicItem").on('click', function () {
    	if(musicView.chooseIdx != mvCfg.music){
    		if($('#MusicItem'+mvCfg.music).length > 0) {
    			$('#MusicItem'+mvCfg.music).eq(0).removeClass('select');
    		}
    	}
    	
        var selectMusicItem = $(this);
        var musicItem = $('#MusicItem'+musicView.chooseIdx);
        if(musicItem.length > 0) {
            musicItem.eq(0).removeClass('select');
        }
        selectMusicItem.addClass('select');
       
        musicView.chooseIdx = parseInt(selectMusicItem.eq(0)[0].id.match(/\d+/)[0]);
        var musicUrl = selectMusicItem.eq(0).find('.musicStatus').data('info');
        if(musicView.audioPlayer == undefined) {
            musicView.audioPlayer = new Player($('#AudioBtn'),musicUrl,function(){});
            musicView.audioPlayer.isPlay = false;
            musicView.audioPlayer._play();
        } else {
            musicView.audioPlayer._src(musicUrl);
            musicView.audioPlayer._load();
        }
    });
}

musicView.isInit = false;

musicView.showMusicView = function () {
    function show() {
        var v = 'translate(0,-100%)';
        musicView.musicViewElem.css({
            '-moz-transform':v,
            '-webkit-transform':v,
            '-o-transform':v,
            '-ms-transform':v,
            'transform':v,
        });
    }
    if(false == musicView.isInit) {
    	musicView.initEdit();
       show();
       musicView.isInit = true;         
    } else {
        show();
    };
    $('#MusicView .musicType').show();
}

musicView.hideMusicView = function () {
    var v = 'translate(0,0%)';
    this.musicViewElem.css({
        '-moz-transform':v,
        '-webkit-transform':v,
        '-o-transform':v,
        '-ms-transform':v,
        'transform':v,
    });
}