/*
 * 注意：
 * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
 * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
 * 3. 完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
 *
 * 如有问题请通过以下渠道反馈：
 * 邮箱地址：weixin-open@qq.com
 * 邮件主题：【微信JS-SDK反馈】具体问题
 * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
 */
wx.ready(function () {
  // 5 图片接口
  // 5.1 拍照、本地选图
	i = querystring('i');
    j = querystring('j');
    id = querystring('id');
    function querystring(name){
		var result = location.search.match(new RegExp("[\?\&]" + name+ "=([^\&]+)","i")); 
		if (result == null || result.length < 1){ 
			return "";
		}
		return result[1]; 
	}
    
    function tomedia(src){
		if(src.indexOf('http://') == 0 || src.indexOf('https://') == 0) {
			return src;
		} else if(src.indexOf('../addons') == 0 || src.indexOf('../attachment') == 0) {
			src=src.substr(3);
			return window.sysinfo.siteroot + src;
		} else if(src.indexOf('./resource') == 0) {
			src=src.substr(2);
			return window.sysinfo.siteroot + 'app/' + src;
		} else if(src.indexOf('images/') == 0) {
			return window.sysinfo.attachurl+ src;
		}
	}
  var images = {
    localId: [],
    serverId: []
  };
  document.querySelector('#chooseImage').onclick = function () {
    wx.chooseImage({
      success: function (res) {
        images.localId = res.localIds;
        
        if (images.localId.length == 0) {
        	return;
        }
        var m = 0, length = images.localId.length;
        
        function preview(data,localId){
        	if(data.error == 1){
        		$upload = $( '#pub-pics' );
            	$li = $('<li class="up-pic up-error" id="'+localId+'"><div class="clip "><img name="thumb[]" value="" src="'+ localId +'" style="width: 65px; height: 65px; display: block; margin-left: 0px;"></div> <div class="up-mask"></div> <div class="up-progress">  </div> <a class="btn-del" href="javascript:void(0)" title="关闭">&nbsp;</a> </li>')
            	$upload.append($li);
        	}else{
        		$upload = $( '#pub-pics' );
            	$li = $('<li class="up-pic up-over" id="'+data.serverId+'"><div class="clip "><img name="thumb[]" value="'+ data.path +'" src="'+ data.path +'" style="width: 65px; height: 65px; display: block; margin-left: 0px;"></div> <div class="up-mask"></div> <div class="up-progress">  </div> <a class="btn-del" href="javascript:void(0)" title="关闭">&nbsp;</a> </li>')
            	$upload.append($li);
        	}
        	
        }
        
        images.serverId = [];
        function upload() {
        	wx.uploadImage({
            localId: images.localId[m],
            success: function (res) {
              m++;
              images.serverId.push(res.serverId);
              url = './index.php?i='+i+'&j='+j+'&c=entry&do=upload&type=image&m=meepo_bbs&serverId='+res.serverId;
              alert(res.serverId);
              $.get(url,function(data){
            	  preview(data,images.localId[m]);
              });
              
              if (m < length) {
                upload();
              }
            },
            fail: function (res) {
              //上传失败
            }
          });
        }
        upload();
        
      }
    });
  };
  // 8.7 关闭当前窗口
  document.querySelector('#closeWindow').onclick = function () {
    wx.closeWindow();
  };
  function decryptCode(code, callback) {
    $.getJSON('/jssdk/decrypt_code.php?code=' + encodeURI(code), function (res) {
      if (res.errcode == 0) {
        codes.push(res.code);
      }
    });
  }
});

wx.error(function (res) {
  alert(res.errMsg);
});

