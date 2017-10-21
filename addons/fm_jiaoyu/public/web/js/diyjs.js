	function showImageDialogss(elm, opts, options) {
		require(["util"], function(util){
			var btn = $(elm);
			var ipt = btn.parent().prev();
			var val = ipt.val();
			var img = ipt.parent().next().children();
			var p = elm.getAttribute("data_id");
			//alert(pp);
			options = {'global':false,'class_extra':'','direct':true,'multiple':false};
			util.image(val, function(url){
				if(url.url){
					if(img.length > 0){
						img.get(0).src = url.url;
					}
					ipt.val(url.attachment);
					ipt.attr("filename",url.filename);
					ipt.attr("url",url.url);
					$("#"+p).attr("src",url.url);
				}
				if(url.media_id){
					if(img.length > 0){
						img.get(0).src = "";
					}
					ipt.val(url.media_id);
					$("#"+p).attr("src",url.media_id);
				}
			}, null, options);
		});
	}
	function showImageDialogmfs(elm, opts, options) {
		require(["util"], function(util){
			var btn = $(elm);
			var ipt = btn.parent().prev();
			var val = ipt.val();
			var img = ipt.parent().next().children();
			var pp = elm.getAttribute("data_id");
			options = {'global':false,'class_extra':'','direct':true,'multiple':false};
			util.image(val, function(url){
				if(url.url){
					if(img.length > 0){
						img.get(0).src = url.url;
					}
					ipt.val(url.attachment);
					ipt.attr("filename",url.filename);
					ipt.attr("url",url.url);
					$("#"+pp).css("background",'url(' + url.url+ ') no-repeat');
					$("#"+pp).css("background-size",'38px 40px');
					$("#"+pp).css("background-position",'90% center');
				}
				if(url.media_id){
					if(img.length > 0){
						img.get(0).src = "";
					}
					ipt.val(url.media_id);
					$("#"+pp).css("background",'url(' + url.media_id+ ') no-repeat');
					$("#"+pp).css("background-size",'38px 40px');
					$("#"+pp).css("background-position",'90% center');					
				}
			}, null, options);
		});
	}
	function showImageDialoglb(elm, opts, options) {
		require(["util"], function(util){
			var btn = $(elm);
			var ipt = btn.parent().prev();
			var val = ipt.val();
			var img = ipt.parent().next().children();
			var pp = elm.getAttribute("data_id");
			options = {'global':false,'class_extra':'','direct':true,'multiple':false};
			util.image(val, function(url){
				if(url.url){
					if(img.length > 0){
						img.get(0).src = url.url;
					}
					ipt.val(url.attachment);
					ipt.attr("filename",url.filename);
					ipt.attr("url",url.url);
					$("#"+pp).css("background",'url(' + url.url+ ') no-repeat');
					$("#"+pp).css("background-size",'17px 15px');
					$("#"+pp).css("background-position",'15px center');
				}    
				if(url.media_id){
					if(img.length > 0){
						img.get(0).src = "";
					}
					ipt.val(url.media_id);
					$("#"+pp).css("background",'url(' + url.media_id+ ') no-repeat');
					$("#"+pp).css("background-size",'17px 15px');
					$("#"+pp).css("background-position",'15px center');				
				}
			}, null, options);
		});
	}	
	function deleteImage(elm){
		require(["jquery"], function($){
			$(elm).prev().attr("src", "./resource/images/nopic.jpg");
			$(elm).parent().prev().find("input").val("");
		});
	}