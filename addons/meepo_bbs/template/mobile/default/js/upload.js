(function( $ ){
    // 当domReady的时候开始初始化
    $(function() {
    	
    	$upload = $( '#pub-pics' ),
    	
    	// 优化retina, 在retina下这个值是2
        ratio = window.devicePixelRatio || 1,
        // 缩略图大小
        thumbnailWidth = 110 * ratio,
        thumbnailHeight = 110 * ratio,
        // 可能有pedding, ready, uploading, confirm, done.
        state = 'pedding',
    	percentages = {},
    	
    	// 判断浏览器是否支持图片的base64
        isSupportBase64 = ( function() {
            var data = new Image();
            var support = true;
            data.onload = data.onerror = function() {
                if( this.width != 1 || this.height != 1 ) {
                    support = false;
                }
            }
            data.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
            return support;
        } )(),
        
        // 检测是否已经安装flash，检测flash的版本
        flashVersion = ( function() {
            var version;

            try {
                version = navigator.plugins[ 'Shockwave Flash' ];
                version = version.description;
            } catch ( ex ) {
                try {
                    version = new ActiveXObject('ShockwaveFlash.ShockwaveFlash')
                            .GetVariable('$version');
                } catch ( ex2 ) {
                    version = '0.0';
                }
            }
            version = version.match( /\d+/g );
            return parseFloat( version[ 0 ] + '.' + version[ 1 ], 10 );
        } )(),
        
        supportTransition = (function(){
            var s = document.createElement('p').style,
                r = 'transition' in s ||
                        'WebkitTransition' in s ||
                        'MozTransition' in s ||
                        'msTransition' in s ||
                        'OTransition' in s;
            s = null;
            return r;
        })(),
        
        // WebUploader实例
        uploader;

	    if ( !WebUploader.Uploader.support('flash') && WebUploader.browser.ie ) {
	
	        // flash 安装了但是版本过低。
	        if (flashVersion) {
	            (function(container) {
	                window['expressinstallcallback'] = function( state ) {
	                    switch(state) {
	                        case 'Download.Cancelled':
	                            alert('您取消了更新！')
	                            break;
	
	                        case 'Download.Failed':
	                            alert('安装失败')
	                            break;
	
	                        default:
	                            alert('安装已成功，请刷新！');
	                            break;
	                    }
	                    delete window['expressinstallcallback'];
	                };
	
	                var swf = './expressInstall.swf';
	                // insert flash object
	                var html = '<object type="application/' +
	                        'x-shockwave-flash" data="' +  swf + '" ';
	
	                if (WebUploader.browser.ie) {
	                    html += 'classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ';
	                }
	
	                html += 'width="100%" height="100%" style="outline:0">'  +
	                    '<param name="movie" value="' + swf + '" />' +
	                    '<param name="wmode" value="transparent" />' +
	                    '<param name="allowscriptaccess" value="always" />' +
	                '</object>';
	
	                container.html(html);
	
	            })($wrap);
	
	        // 压根就没有安转。
	        } else {
	            $wrap.html('<a href="http://www.adobe.com/go/getflashplayer" target="_blank" border="0"><img alt="get flash player" src="http://www.adobe.com/macromedia/style_guide/images/160x41_Get_Flash_Player.jpg" /></a>');
	        }
	
	        return;
	    } else if (!WebUploader.Uploader.support()) {
	        alert( 'Web Uploader 不支持您的浏览器！');
	        return;
	    }
    	
        i = querystring('i'),
        j = querystring('j'),
        id = querystring('id'),
        
        defaultOptions = {
			pick: {
				id: '#filePicker',
				label: '点击选择图片',
				multiple : false
			},
			auto: true,
			swf: '../addons/meepo_bbs/template/mobile/default/js/webupload/Uploader.swf',
			server: './index.php?i='+i+'&j='+j+'&c=entry&do=upload&type=image&m=meepo_bbs',
			chunked: false,
			chunkSize: 512 * 1024,
			resize:false,
			
			disableGlobalDnd: true,
            fileNumLimit: 300,
            fileSizeLimit: 200 * 1024 * 1024,    // 200 M
            fileSingleSizeLimit: 50 * 1024 * 1024    // 50 M
		},
        
        options = {
    		paste:true,
    		accept:{
			    title: 'Images',
			    extensions: 'gif,jpg,jpeg,bmp,png',
			    mimeTypes: 'image/*'
			}
        }
		
		options = $.extend({}, defaultOptions, options);
		
		var uploader = WebUploader.create(options);
		
		uploader.on('ready', function() {
            window.uploader = uploader;
        });
		
		uploader.on( 'fileQueued', function( file ) {
			console.log('onFileQueued');
        	$li = $('<li class="up-pic" id="'+file.id+'""> <div class="up-mask"></div> <div class="up-progress">  </div> <a class="btn-del" href="javascript:void(0)" title="关闭">&nbsp;</a> </li>'),
        	
        	$btns = $li.find( 'a.btn-del' ),
        	
        	$btns.on('click',function(){
        		uploader.removeFile( file );
        	});
        	
        	$upload.append($li);
        	
        	uploader.makeThumb( file, function( error, ret ) {
                if ( error ) {
                	$item = $('#'+file.id);
                	$mask = $item.find('up-mask');
                	$item.append('<span>不能预览</span>'); 
                	return;
                } else {
                	$item = $('#'+file.id);
                	$clip = '<div class="clip "><img src="'+ret+'" style="width: 65px; height: 65px; display: block; margin-left: 0px;"></div>';
                	$item.append($clip);
                }
            },65, 65 );
		});
		uploader.on('uploadSuccess', function(file, result) {
			console.log('uploadSuccess');
        	if(result.status != 'success'){
        		var $li = $('#'+file.id),
            	$progress = $li.find('.up-progress');
                $percent = $li.find('.up-progress .pos');
                $percent.css( 'width', percentage * 6 + '%' );
                $( '#'+file.id ).addClass('up-error');
                alert(result.message);
        	}else{
        		$input = $('<input type="hidden" name="thumb[]" value="'+result.result+'">');
            	$li = $('#' + file.id);
            	$img = $li.find('.clip img');
            	$img.attr('src', tomedia(result.result));
            	$li.append($input);
            	$( '#'+file.id ).addClass('up-over');
        	}
		});
		uploader.onError = function( code ) {
			uploader.reset();
			if(code == 'Q_EXCEED_SIZE_LIMIT'){
				alert('错误信息: 图片大于 1M 无法上传.');
				return
			}
		};
		
        function querystring(name){
    		var result = location.search.match(new RegExp("[\?\&]" + name+ "=([^\&]+)","i")); 
    		if (result == null || result.length < 1){ 
    			return "";
    		}
    		return result[1]; 
    	}
        
        uploader.on('uploadProgress',function( file, percentage  ) {
        	
        	var $li = $('#'+file.id),
        	$progress = $li.find('.up-progress');
            $percent = $li.find('.up-progress .pos');
        	if ( !$percent.length ) {
                $percent = $('<div class="pos" role="progressbar" style="width: 6%"></div>');
                $progress.append($percent);
            }
        	$percent.css( 'width', percentage * 100 + '%' );
        	 $( '#'+file.id ).addClass('up-error');
        });
        
        uploader.on( 'uploadComplete', function( file ) {
        	console.log('uploadComplete');
        	$( '#'+file.id ).addClass('up-over');
        });
        
        uploader.on('uploadError', function(file, reason){
        	console.log('uploadError');
        	$( '#'+file.id ).addClass('up-error');
            alert(reason)
        });
        
        function removeFile( file ) {
            var $li = $('#'+file.id);
            $li.remove();
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
        
        uploader.onFileDequeued = function( file ) {
        	console.log('onFileDequeued');
            removeFile( file );
        };
    });

})( jQuery );