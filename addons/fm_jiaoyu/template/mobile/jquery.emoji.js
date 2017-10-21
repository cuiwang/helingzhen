var leftsize = 0;
var pageFootNum = 0;
var WXChooseImageCount = 9;
var images = {
	    localId: [],
	    serverId: []
};

/**
 * 创建imageBox
 */
function createImageBox(){
	$("#imageBox,#imageOpen,#imageClose").toggle();
	var imageBox= new Array();
	imageBox.push('<div id="imageBoxBody"><div id="DivFixedPosition"></div><div class="imagePage"><div class="imageTotalBox"><img alt="image" id="addImages" onclick="wxChooseImage()" src="{OSSURL}/public/mobile/img/insertImage.png" class="addImages"><div class="imageTotal">(0/9)</div></div></div>');
	$("#imageBox").html(imageBox.join(''));
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
    jTips("开始上传照片");
	function upload() {
	      wx.uploadImage({
	        localId: localIds[i],
	        isShowProgressTips:0,//// 默认为1，显示进度提示
	        success: function (res) {
	        	setTimeout(function(){
	        		addImages(localIds[i]);
	  	          i++;
//	  	          alert("已完成上传 "+(i)+"/"+length);
	  	          jTips("已完成上传 "+(i)+"/"+length);
	  	          images.serverId.push(res.serverId);
	  	          if($(".deleteImage").length==9){
	  	        	  jTips("上传图片最多9张！")
	  	        	  return;
	  	          }
	  	          if (i < length) {
	  	            upload();
	  	          }
	  	          if(i==length){
	  	        	  jTips("已完成上传");
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
					
function setImageHeight(){
	var imageWidth = $(".boxImages")[0].offsetWidth;
	$(".boxImages").height(imageWidth);
}
