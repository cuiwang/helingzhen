module =
{
	maxUpload: 3,
	uploadInfo:
	{
	},
	uploadQueue: [],
	previewQueue: [],
	xhr:
	{
	},
	isBusy: false,
	getgps: 1,
	countUpload: function()
	{
		//alert('countUpload');
		var num = 0;
		jq.each(module.uploadInfo,
			function(i, n)
			{
				if (n)
				{
					++num;
				}
			});
		return num;
	},
	uploadPreview: function(id)
	{
		//alert('uploadPreview');
		var reader = new FileReader();
		var uploadBase64;
		var conf =
		{
		},
		file = module.uploadInfo[id].file;
		reader.onload = function(e)
		{
			var result = this.result;
			if (file.type == 'image/jpeg')
			{
				try
				{
					var jpg = new JpegMeta.JpegFile(result, file.name);
				} catch(e)
				{
					jq.DIC.dialog(
						{
							content: '图片不是正确的图片数据',
							autoClose: true
						});
					jq('#li' + id).remove();
					return false;
				}
				if (jpg.tiff && jpg.tiff.Orientation)
				{
					conf = jq.extend(conf,
						{
							orien: jpg.tiff.Orientation.value
						});
				}
			}
			if (ImageCompresser.support())
			{
				var img = new Image();
				img.onload = function()
				{
					console.log(conf);
					try
					{
						uploadBase64 = ImageCompresser.getImageBase64(this, conf);
					} catch(e)
					{
						jq.DIC.dialog(
							{
								content: '压缩图片失败',
								autoClose: true
							});
						jq('#li' + id).remove();
						return false;
					}
					if (uploadBase64.indexOf('data:image') < 0)
					{
						jq.DIC.dialog(
							{
								content: '上传照片格式不支持',
								autoClose: true
							});
						jq('#li' + id).remove();
						return false;
					}
					module.uploadInfo[id].file = uploadBase64;
					jq('#li' + id).find('img').attr('src', uploadBase64);
					module.uploadQueue.push(id);
				}
				img.onerror = function()
				{
					jq.DIC.dialog(
						{
							content: '解析图片数据失败',
							autoClose: true
						});
					jq('#li' + id).remove();
					return false;
				}
				img.src = ImageCompresser.getFileObjectURL(file);
			} else
			{
				uploadBase64 = result;
				if (uploadBase64.indexOf('data:image') < 0)
				{
					jq.DIC.dialog(
						{
							content: '上传照片格式不支持',
							autoClose: true
						});
					jq('#li' + id).remove();
					return false;
				}
				module.uploadInfo[id].file = uploadBase64;
				jq('#li' + id).find('img').attr('src', uploadBase64);
				module.uploadQueue.push(id);
			}
		}
		reader.readAsBinaryString(module.uploadInfo[id].file);
	},
	createUpload: function(id)
	{
		//alert('createUpload');
		if (!module.uploadInfo[id])
		{
			return false;
		}
		var uploadUrl = myurl;
		var progressHtml = '<div class="progress" id="progress' + id + '"><div class="proBar" style="width:0%;"></div></div>';
		jq('#li' + id).find('.maskLay').after(progressHtml);
		var formData = new FormData();
		formData.append('pic', module.uploadInfo[id].file);
		var progress = function(e)
		{
			if (e.target.response)
			{
				var result = jq.parseJSON(e.target.response);
				if (result.errCode != 0)
				{
					jq.DIC.dialog(
						{
							content: '网络不稳定，请稍后重新操作',
							autoClose: true
						});
					removePic(id);
				}
			}
			var progress = jq('#progress' + id).find('.proBar');
			if (e.total == e.loaded)
			{
				var percent = 100;
			} else
			{
				var percent = 100 * (e.loaded / e.total);
			}
			if (percent > 100)
			{
				percent = 100;
			}
			progress.css('width', percent + '%');
			if (percent == 100)
			{
				jq('#li' + id).find('.maskLay').remove();
				jq('#li' + id).find('.progress').remove();
			}
		}
		var removePic = function(id)
		{
			donePic(id);
			jq('#li' + id).remove();
		}
		var donePic = function(id)
		{
			module.isBusy = false;
			if (typeof module.uploadInfo[id] != 'undefined')
			{
				module.uploadInfo[id].isDone = true;
			}
			if (typeof module.xhr[id] != 'undefined')
			{
				module.xhr[id] = null;
			}
		}
		var complete = function(e)
		{
			var progress = jq('#progress' + id).find('.proBar');
			progress.css('width', '100%');
			jq('#li' + id).find('.maskLay').remove();
			jq('#li' + id).find('.progress').remove();
			donePic(id);
			//var result = e.target.response;
			var result = jq.parseJSON(e.target.response);
			// if (result!='')
			// {
			// 	var inputss = '<input type="hidden" name="img[]" value="' + result.data.picId + '">';
			// 	jq('#newthread').append(inputss);
			// } else
			// {
			// 	jq.DIC.dialog(
			// 		{
			// 			content: '网络不稳定，请稍后重新操作',
			// 			autoClose: true
			// 		});
			// 	removePic(id);
			// }
			 if (result.errCode == 0) {
                    var input = '<input type="hidden" id="input' + result.data.id + '" name="picIds[]" value="' + result.data.picId + '">';
                    jq('#newthread').append(input);
                } else {
                    // jq('#content').val(result.errCode);
                    jq.DIC.dialog({content:'网络不稳定，请稍后重新操作',autoClose:true});
                    removePic(id);
                }
		}
		var failed = function()
		{
			jq.DIC.dialog(
				{
					content: '网络断开，请稍后重新操作',
					autoClose: true
				});
			removePic(id)
		}
		var abort = function()
		{
			jq.DIC.dialog(
				{
					content: '上传已取消',
					autoClose: true
				});
			removePic(id)
		}
		
		//jQuery.post(uploadUrl,{pic:module.uploadInfo[id].file},function(result){});
		module.xhr[id] = new XMLHttpRequest();
		module.xhr[id].addEventListener("progress", progress, false);
		module.xhr[id].addEventListener("load", complete, false);
		module.xhr[id].addEventListener("abort", abort, false);
		module.xhr[id].addEventListener("error", failed, false);
		module.xhr[id].open("POST", uploadUrl);
		module.xhr[id].send(formData);
		
	},
	checkUploadBySysVer: function()
	{
		//alert('checkUploadBySysVer');
		if (jQuery.os.android && (jQuery.os.version.toString().indexOf('4.4') === 0 || jQuery.os.version.toString() <= '2.1'))
		{
			jq.DIC.dialog(
				{
					'content': '您的手机系统暂不支持传图',
					'autoClose': true
				});
			return false;
		} else if (jQuery.os.ios && jQuery.os.version.toString() < '6.0')
		{
			jq.DIC.dialog(
				{
					'content': '手机系统不支持传图，请升级到ios6.0以上',
					'autoClose': true
				});
			return false;
		}
		if (jQuery.os.wx && jQuery.os.wxVersion.toString() < '5.2')
		{
			jq.DIC.dialog(
				{
					'content': '当前微信版本不支持传图，请升级到最新版',
					'autoClose': true
				});
			return false;
		}
		return true;
	},
	initUpload: function()
	{
		jq(document).ready(function()
			{
				//alert('initUpload');
				jq('#addPic').on('click',
					function()
					{
						//alert('click_addPic');
						module.checkUploadBySysVer();
					});
				jq('#uploadFile').on('click',
					function()
					{
						//alert('click_uploadFile');
						if (module.isBusy)
						{
							jq.DIC.dialog(
								{
									content: '上传中，请稍后添加',
									autoClose: true
								});
							return false;
						}
					});
				jq('body').on('change', '#uploadFile',
					function(e)
					{
						//alert('change_uploadFile');
						e = e || window.event;
						var fileList = e.target.files;
						if (!fileList.length)
						{
							return false;
						}
						for (var i = 0; i < fileList.length; i++)
						{
							if (module.countUpload() >= module.maxUpload)
							{
								jq.DIC.dialog(
									{
										content: '你最多只能上传8张照片',
										autoClose: true
									});
								break;
							}
							var file = fileList[i];
							if (!module.checkPicSize(file))
							{
								jq.DIC.dialog(
									{
										content: '图片体积过大',
										autoClose: true
									});
								continue;
							}
							if (!module.checkPicType(file))
							{
								jq.DIC.dialog(
									{
										content: '上传照片格式不支持',
										autoClose: true
									});
								continue;
							}
							var id = Date.now() + i;
							module.uploadInfo[id] =
							{
								file: file,
								isDone: false,

							};
							var html = '<li id="li' + id + '"><div class="photoCut"><img src="http://dzqun.gtimg.cn/quan/images/defaultImg.png" class="attchImg" alt="photo"></div>' + '<div class="maskLay"></div>' + '<a href="javascript:;" class="cBtn spr db " title="" _id="' + id + '">关闭</a></li>';
							jq('#addPic').before(html);
							module.previewQueue.push(id);
						}
						if (module.countUpload() >= module.maxUpload)
						{
							jq('#addPic').hide();
						}
						jq(this).val('');
					});
				jq('.photoList').on('click', '.cBtn',
					function()
					{
						var id = jq(this).attr('_id');
						if (module.xhr[id])
						{
							module.xhr[id].abort();
						}
						jq('#li' + id).remove();
						jq('#input' + id).remove();
						module.uploadInfo[id] = null;
						if (module.countUpload() < module.maxUpload)
						{
							jq('#addPic').show();
						}
					});
				setInterval(function()
					{
						setTimeout(function()
							{
								if (module.previewQueue.length)
								{
									var jobId = module.previewQueue.shift();
									module.uploadPreview(jobId);
								}
							},
							1);
						setTimeout(function()
							{
								if (!module.isBusy && module.uploadQueue.length)
								{
									var jobId = module.uploadQueue.shift();
									module.isBusy = true;
									module.createUpload(jobId);
								}
							},
							10);
					},
					300);
			});
	},
	init: function()
	{
		//alert('init');
		var sId=1;
		var storageKey = sId + "thread_content";
		jq('#content').val(localStorage.getItem(storageKey));
		timer = setInterval(function()
			{
				// jq.DIC.strLenCalc(jq('textarea[name="content"]')[0], 'pText', 1000);
				localStorage.removeItem(storageKey);
				localStorage.setItem(storageKey, jq('#content').val());
			},
			500);
		var isSubmitButtonClicked = false;
		jq('#submitButton').bind('click',
			function()
			{
				if (isSubmitButtonClicked || !module.checkForm())
				{
					return false;
				}
				var opt =
				{
					success: function(re)
					{
						var status = parseInt(re.errCode);
						if (status == 0)
						{
							clearInterval(timer);
							localStorage.removeItem(storageKey);
						} else
						{
							isSubmitButtonClicked = false;
						}
					},
					error: function(re)
					{
						isSubmitButtonClicked = false;
					}
				};
				isSubmitButtonClicked = true;
				jq.DIC.ajaxForm('newthread', opt, true);
				return false;
			});
		jq('.cancelBtn').bind('click',
			function()
			{
				if (jq('.photoList .attchImg').length > 0)
				{
					jq.DIC.dialog(
						{
							content: '是否放弃当前内容?',
							okValue: '确定',
							cancelValue: '取消',
							isMask: true,
							ok: function()
							{
								history.go( - 1);
							}
						});
				} else
				{
					history.go( - 1);
				}
			});
		jq('#content').on('focus',
			function()
			{
				jq('.bNav').hide();
			}).on('blur',
			function()
			{
				jq('.bNav').show();
			});
		module.initUpload();
		module.initModal();
		jq(".tagBox a").on("click",
			function()
			{
				jq(".tagBox").find('a').attr('class', '');
				var labelId = jq(this).attr('labelId');
				if (jq('input[name="fId"]').val() != labelId)
				{
					jq(this).attr('class', 'on');
					jq('input[name="fId"]').val(labelId);
				} else
				{
					jq('input[name="fId"]').val(0);
				}
			});
		var selTagId = jq.DIC.getQuery('filterType');
		if (selTagId)
		{
			var tagArr = jq('.tagBox').find('a');
			jq.each(tagArr,
				function(key, value)
				{
					jq(value).removeClass('on');
					if (jq(value).attr('labelid') == selTagId)
					{
						jq(value).addClass('on');
						jq('input[name="fId"]').val(selTagId);
					}
				})
		}
		/*
		module.checkLBS();
		jq(".locationCon").on('click',
		function() {
		if (module.getgps == 1 || module.getgps == 2) {
		module.getgps = 0;
		jq('.locationCon').removeClass('curOn').html('<i class="locInco commF">' + '所有城市');
		jq('#LBSInfoLatitude').val('');
		jq('#LBSInfoLongitude').val('');
		jq('#LBSInfoProvince').val('');
		jq('#LBSInfoCity').val('');
		jq('#LBSInfoStreet').val('');
		jq('#cityCode').val('');
		} else if (module.getgps == 0) {
		module.getgps = 1;
		jq('.locationCon').html('<i class="locInco commF">' + '正在定位...');
		module.checkLBS();
		}
		});
		*/
	},
	checkLBS: function()
	{
		//alert('checkLBS');
		gps.getLocation(function(latitude, longitude)
			{
				jq.DIC.ajax('http://mq.wsq.qq.com/checkLBS',
					{
						'CSRFToken': CSRFToken,
						'latitude': latitude,
						'longitude': longitude
					},
					{
						'noShowLoading': true,
						'noMsg': true,
						'success': function(result)
						{
							var status = parseInt(result.errCode);
							var LBSInfo = result.data.LBSInfo;
							var cityCode = result.data.cityCode;
							if (status == 0 && module.getgps == 1)
							{
								module.getgps = 2;
								jq('.locationCon').addClass('curOn').html('<i class="locInco commF">' + LBSInfo.city + (LBSInfo.street ? (' ' + LBSInfo.street) : ''));
								if (cityCode) jq('#cityCode').val(cityCode);
								if (LBSInfo)
								{
									jq('#LBSInfoLatitude').val(LBSInfo.latitude);
									jq('#LBSInfoLongitude').val(LBSInfo.longitude);
									jq('#LBSInfoProvince').val(LBSInfo.province);
									jq('#LBSInfoCity').val(LBSInfo.city);
									jq('#LBSInfoStreet').val(LBSInfo.street);
								}
							} else if (module.getgps == 1)
							{
								module.getgps = 0;
								jq('#LBSInfoLatitude').val('');
								jq('#LBSInfoLongitude').val('');
								jq('#LBSInfoProvince').val('');
								jq('#LBSInfoCity').val('');
								jq('#LBSInfoStreet').val('');
								jq('#cityCode').val('');
								jq('.locationCon').html('<i class="locInco commF">' + '获取位置失败');
							}
						}
					});
			});
	},
	initModal: function()
	{
		//alert('initModal');
		jq('#submitButton').bind('touchstart',
			function()
			{
				jq(this).addClass('sendOn');
			}).bind('touchend',
			function()
			{
				jq(this).removeClass('sendOn');
			});
		jq('#cBtn').bind('touchstart',
			function()
			{
				jq(this).addClass('cancelOn');
			}).bind('touchend',
			function()
			{
				jq(this).removeClass('cancelOn');
			});
	},
	checkForm: function()
	{
		//alert('checkForm');
		jq.each(module.uploadInfo,
			function(i, n)
			{
				if (n && !n.isDone)
				{
					jq.DIC.dialog(
						{
							content: '图片上传中，请等待',
							autoClose: true
						});
					return false;
				}
			});
		var content = jq('#content').val();
		var contentLen = jq.DIC.mb_strlen(jq.DIC.trim(content));
		if (contentLen < 15)
		{
			jq.DIC.dialog(
				{
					content: '内容过短',
					autoClose: true
				});
			return false;
		}
		return true;
	},
	checkPicSize: function(file)
	{
		//alert('checkPicSize');
		if (file.size > 10000000)
		{
			return false;
		}
		return true;
	},
	checkPicType: function(file)
	{
		//alert('checkPicType');
		return true;
	}
};
module.init();