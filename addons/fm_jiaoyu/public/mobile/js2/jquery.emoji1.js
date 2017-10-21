var leftsize = 0;
var pageFootNum = 0;
var WXChooseImageCount = 9;
var oss = '{OSSURL}/';
var images = {
	    localId: [],
	    serverId: []
};
var PB = new PromptBox();
function showBox(type){
	(type=="emoji" && showEmojiBox())||((type=="image")&& showImageBox()) || (type=="vedio" && showVedioBox());
}
/**
 * 判断显示、创建、隐藏表情包
 */
function showEmojiBox(){
	$("#imageBox,#imageOpen").hide();
	$("#imageClose").show();
	//隐藏视频选择
	$("#vedioBox,#vedioOpen").hide();
	$("#vedioClose").show();
	(document.getElementById("emojisList") && $("#emojiBox,#emojiClose,#emojiOpen").toggle())||createEmojiBox();
}

/**
 * 判断显示、创建、隐藏图片框
 */
function showImageBox(){
	$("#emojiBox,#emojiOpen").hide();
	$("#emojiClose").show();
	//隐藏视频选择
	$("#vedioBox,#vedioOpen").hide();
	$("#vedioClose").show();
	(document.getElementById("imageBoxBody") && $("#imageBox,#imageOpen,#imageClose").toggle())||createImageBox();
}
/**
 * 判断显示、创建、隐藏视频框
 */
function showVedioBox(videoUrl,thumbnailUrl){
	//隐藏表情
	$("#emojiBox,#emojiOpen").hide();
	$("#emojiClose").show();
	//隐藏图片选择
	$("#imageBox,#imageOpen").hide();
	$("#imageClose").show();
	(document.getElementById("vedioBoxBody") && $("#vedioBox,#vedioOpen,#vedioClose").toggle())||createVedioBox(vedioUrl,thumbnailUrl);
}


/**
 * 创建imageBox
 */
function createImageBox(){
	$("#imageBox,#imageOpen,#imageClose").toggle();
	var imageBox= new Array();
	imageBox.push('<div id="imageBoxBody"><div id="DivFixedPosition"></div><div class="imagePage"><div class="imageTotalBox"><img alt="image" id="addImages" onclick="wxChooseImage()" src="'+oss+'public/mobile/img/insertImage.png" class="addImages"><div class="imageTotal">(0/9)</div></div></div>');
	$("#imageBox").html(imageBox.join(''));
	/*showThumbnail();*/
}

/**
 * 创建videoBox
 */
function createVedioBox(vedioUrl,thumbnailUrl){
	$("#vedioBox,#vedioOpen,#vedioClose").toggle();
	var vedioBox= new Array();
	if(thumbnailUrl!=null && thumbnailUrl!='' && thumbnailUrl!='null' && thumbnailUrl!=undefined){
		vedioBox.push('<div id="videoBoxBody"><video style="width:100%;height:200" poster="'+thumbnailUrl+'@50q.jpg" preload="none" controls="controls"><source src="'+vedioUrl+'?avthumb/mp4" type="video/mp4"></video></div>');
	}else{
		vedioBox.push('<div id="videoBoxBody"><video style="width:100%;height:200" preload="none" controls="controls"><source src="'+vedioUrl+'?avthumb/mp4" type="video/mp4"></video></div>');
	}
	$("#vedioBox").html(vedioBox.join(''));
	/*showThumbnail();*/
}

/**
 * 新增图片
 */
function addImages(base64){
	var DivFixedPosition = document.getElementById("DivFixedPosition");
	var imageBoxBody = document.getElementById("imageBoxBody");
	var imagePage = document.createElement("div");
	imagePage.setAttribute("class","imagePage");
	imageBoxBody.insertBefore(imagePage,DivFixedPosition);
	$(imagePage).html('<img alt="image" src="'+base64+'"  class="boxImages baseUploadImg"><span class="deleteImage" style= "background: url({OSSURL}/public/mobile/img/deleteImage.png); background-size: 100%;display: inline;float: right;height: 25%;position: absolute;right: 0px;width: 25%;z-index: 4;" onclick="deleteImage(this)"></span>');
	setImageHeight();
	imagesTotal()
}

/**
 * 新增图片
 */
function loadImages(id,url){
	var DivFixedPosition = document.getElementById("DivFixedPosition");
	var imageBoxBody = document.getElementById("imageBoxBody");
	var imagePage = document.createElement("div");
	imagePage.setAttribute("class","imagePage");
	imageBoxBody.insertBefore(imagePage,DivFixedPosition);
	$(imagePage).html('<img alt="image" src="'+url+'" id='+id+'  class="boxImages wxUploadImg"><span class="deleteImage" style= "background: url({OSSURL}/public/mobile/img/deleteImage.png); background-size: 100%;display: inline;float: right;height: 25%;position: absolute;right: 0px;width: 25%;z-index: 4;" onclick="deleteImage(this)"></span>');
	setImageHeight();
	imagesTotal()
}

/**
 * 计算图片数量
 */
function imagesTotal(){
	$(".imageTotal").html('('+$(".deleteImage").length+'/9)');
	$(".imageTotal").length<9 && $(".imageTotal").show();
	$(".deleteImage").length!= 0 && $(".addImages").show();
	$(".deleteImage").length==9 && $(".addImages,.imageTotal").hide();
}

/**
 * 删除图片
 * @param span
 */
function deleteImage(span){
	//todo删除图片
	var deleteNode = span.parentNode;
	var arrayIndex = $.inArray($(span.parentNode).find('img').attr("src"),images.localId)
	images.localId.splice(arrayIndex,1);
	images.serverId.splice(arrayIndex,1);
	deleteNode.parentNode.removeChild(span.parentNode);
	imagesTotal();
}

/**
 * 找出某个值在数组中的下标位置
 * @param data
 * @param key
 * @returns
 */
function cruelSearch(data,key)
{
  re = new RegExp(key,[""]);
  return (data.replace(re,"┢").replace(/[^|┢]/g,"")).indexOf("┢");
}

/**
 * 微信选择图片
 */
function wxChooseImage(){
		wx.chooseImage({
			count: WXChooseImageCount,
			sizeType: ['compressed'],
			success: function (res) {
				setTimeout(function(){
					images.localId = images.localId.concat(res.localIds);
					imagesUploadWx(res.localIds);
				},1000)
			}
		});
};
		  
function imagesUploadWx(localIds) {
	var i = 0, length = localIds.length;
    PB.prompt("开始上传照片","forever");
	function upload() {
	      wx.uploadImage({
	        localId: localIds[i],
	        isShowProgressTips:0,//// 默认为1，显示进度提示
	        success: function (res) {
	        	setTimeout(function(){
	        		addImages(localIds[i]);
	  	          i++;
//	  	          alert("已完成上传 "+(i)+"/"+length);
	  	          PB.prompt("已完成上传 "+(i)+"/"+length,"forever");
	  	          images.serverId.push(res.serverId);
	  	          if($(".deleteImage").length==9){
	  	        	  PB.prompt("上传图片最多9张！")
	  	        	  return;
	  	          }
	  	          if (i < length) {
	  	            upload();
	  	          }
	  	          if(i==length){
	  	        	  PB.prompt("已完成上传");
	  	          }
	        	},1000)
	        },
	        fail: function (res) {
	          alert(JSON.stringify(res));
	        }
	      });
	    }
	upload();
};

/**
 * 设置图片的高
 */
function setImageHeight(){
	var imageWidth = $(".boxImages")[0].offsetWidth;
	$(".boxImages").height(imageWidth);
}
