(function(global){
'use strict';


function Recorder(container, callback){
    wx.ready(function(){

      $(container).on('touchstart', function(){
        wx.startRecord();
      });

      function uploadVoice(localId){
        wx.uploadVoice({
            localId: localId, // 需要上传的音频的本地ID，由stopRecord接口获得
            isShowProgressTips: 1, // 默认为1，显示进度提示
            success: function (res) {
              callback(null, res);
              // var serverId = res.serverId; // 返回音频的服务器端ID
              // $.ajax({
              //     type: 'GET',
              //     url: 'http://'+domain+'/index/voice?id='+serverId,
              //     dataType: 'jsonp',
              //     timeout: 15000,
              //     success: function(res){
              //         if(res.err === 'ok'){
              //           res.localId = localId;
              //           callback(null, res);
              //         }
              //     },
              //     fail: function(err) {
              //         alert(JSON.stringify(err));
              //     }
              // });
            },
            fail: function(err){
              callback(err);
            }
        });
      }

      $(container).on('touchend', function(){
        setTimeout(function(){
          wx.stopRecord({
            success: function (res) {
              var localId = res.localId;
              uploadVoice(localId);
            },
            fail: function(err){
              callback(err);
            }
          });
        }, 500);
      });

      wx.onVoiceRecordEnd({
          // 录音时间超过一分钟没有停止的时候会执行 complete 回调
          complete: function (res) {
              var localId = res.localId;
              uploadVoice(localId);
          }
      });
    });
}

global.Recorder = Recorder;

})(this);