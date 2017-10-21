var leftsize = 0;
var pageFootNum = 0;
var WXChooseImageCount = 9;
var oss = '../addons/fm_jiaoyu/';
var images = {
	    localId: [],
	    serverId: []
};
var PB = new PromptBox();
var emoji_keys = '\\0|1|2|3|4|5|6|7|8|9|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31|32|33|34|35|36|37|38|39|40|41|42|43|44|45|46|47|48|49|50|51|52|53|54|55|56|57|58|59|60|61|62|63';
var emoji_title= '\\跷跷板|晕|睡觉|Thanks|病了|Sorry|OK!|踢腿|GO|飞翔|送花|晕倒|爱|牵手|Hello|哈哈|抓|蹦跶|好可怕呀|扭一扭|困|火把|凌乱|笑到打滚|爱意满满|好委屈|揉眼睛|街舞|跑|流汗|呵呵|大笑|揉脸|生气|忍|放屁|音乐|扔东西|狂奔|发抖|感冒|吃东西|顶|思索|泡澡|开心|睡了|敲打|大脑|浮云|鞠躬|委屈|奔溃|暧昧|我来啦|坏笑|招呼|无语|可爱|哈哈大笑|问候|酷|无奈|鬼脸';
var emojiKeys = emoji_keys.substr(emoji_keys.indexOf('\\')+1).split('|');
var emojiTitles = emoji_title.substr(emoji_title.indexOf('\\')+1).split('|');
var emojiUrl = '../addons/fm_jiaoyu/public/mobile/img/face/';
var extension = '.gif';
(function($){
  $.fn.emoji = function(){
    return this.each(function(){
      var regex = new RegExp('\\[(' + emoji_title + ')\\]', 'g');
      $(this).html($(this).html().replace(regex, $.fn.emoji.replace));
    });
  };

  $.fn.emoji.replace = function(){
    var key = arguments[1];
    var arraySubscript = cruelSearch(emoji_title,key);
    var src = emojiUrl + arraySubscript + extension;
    return '<img class="emoji" width="20" height="20" align="absmiddle" src="' + src + '" alt="' + key + '" title="' + key + '" />';
  };
})(jQuery);
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

function insertEmoji(img){
	var content = document.getElementById("content");
	content.value= content.value + "["+img.title+"]";
}

function createEmojiBox(){
	var emojiBox = document.getElementById("emojiBox");
	var parent = document.createElement("div");
	parent.setAttribute("class","emojisList");
	parent.setAttribute("id","emojisList");
	var emojisFoot = document.createElement("div");
	emojisFoot.setAttribute("class","emojisFoot");
	emojisFoot.setAttribute("id","emojisFoot");
	emojiBox.appendChild(parent);
	emojiBox.appendChild(emojisFoot);
	var emojiImgs = new Array();
	var emojifoots = new Array();
	emojiImgs.push('<div class="emojiPage">');
	for(var i=0;i<emojiKeys.length;i++){
		emojiImgs.push('<img class="emojis" width="24" height="24" align="absmiddle" src="' + emojiUrl + i + extension + '" alt="' + i + '" title="' + emojiTitles[i] + '"  onclick="insertEmoji(this);"/>');
		((i+1)==emojiKeys.length && emojiImgs.push('<img class="emojis" width="24" height="18" align="absmiddle" src="' + emojiUrl + "del.gif" +'" alt="删除" onclick="deleteEmoji();" />') && emojifoots.push('<img class="pageFoots" src="../addons/fm_jiaoyu/public/mobile/img/check.png" />') && emojiImgs.push('</div>'))||((i+1)%20==0 && emojiImgs.push('<img class="emojis" width="24" height="18" align="absmiddle" src="' + emojiUrl + "del.gif" +'" alt="删除" onclick="deleteEmoji();" />')&& emojifoots.push('<img class="pageFoots" src="../addons/fm_jiaoyu/public/mobile/img/check.png" />') && emojiImgs.push('</div><div class="emojiPage">'));
	}
	$("#emojisList").html(emojiImgs.join(''));
	$("#emojisFoot").html(emojifoots.join(''));
	if($(".pageFoots").length !=0){
		$(".pageFoots")[0].src="../addons/fm_jiaoyu/public/mobile/img/checked.png";
	}
	$(".emojiPage").each(function(){
		this.addEventListener('touchstart', touchSatrtFunc, false);
	});
	$(".emojiPage")[0].addEventListener('touchmove', touchMoveFunc, false);
	$("#emojiBox,#emojiClose,#emojiOpen").toggle();
}
/**
 * 获取初始触点
 * @param evt
 */
function touchSatrtFunc(evt) {
    try
    {
        //evt.preventDefault(); //阻止触摸时浏览器的缩放、滚动条滚动等
        var touch = evt.touches[0]; //获取第一个触点
        var x = Number(touch.pageX); //页面触点X坐标
        var y = Number(touch.pageY); //页面触点Y坐标
        //记录触点初始位置
        startX = x;
        startY = y;
    }
    catch (e) {
        console.log('touchSatrtFunc：' + e.message);
    }
}

/**
 * 获取滑动方向、切换emojiPage
 */
function touchMoveFunc(evt) {
    try
    {
        //evt.preventDefault(); //阻止触摸时浏览器的缩放、滚动条滚动等
        var touch = evt.touches[0]; //获取第一个触点
        var x = Number(touch.pageX); //页面触点X坐标
        var y = Number(touch.pageY); //页面触点Y坐标
        //判断滑动方向
        var pageWidth = document.getElementById("emojiBox").offsetWidth || 300;
        if (x > startX) {
        	 
        	if(leftsize != 0){
        		$(".emojiPage")[pageFootNum].removeEventListener('touchmove', touchMoveFunc, false);
        		(leftsize = leftsize + pageWidth);
        		$("#emojisList").animate({"left":leftsize},"fast");
        		(pageFootNum = pageFootNum - 1 );
        		$(".pageFoots").attr("src","../addons/fm_jiaoyu/public/mobile/img/check.png");
        		$(".pageFoots")[pageFootNum].src="../addons/fm_jiaoyu/public/mobile/img/checked.png";
        		$(".emojiPage")[pageFootNum].addEventListener('touchmove', touchMoveFunc, false);
        	}
        //向左滑动
        }else if(x < startX ){
        	if(leftsize != -pageWidth*4){
        		$(".emojiPage")[pageFootNum].removeEventListener('touchmove', touchMoveFunc, false);
        		(leftsize = leftsize - pageWidth);
        		$("#emojisList").animate({"left":leftsize},"fast");
        		(pageFootNum = pageFootNum + 1 );
        		$(".pageFoots").attr("src","../addons/fm_jiaoyu/public/mobile/img/check.png");
        		$(".pageFoots")[pageFootNum].src="../addons/fm_jiaoyu/public/mobile/img/checked.png";
        		$(".emojiPage")[pageFootNum].addEventListener('touchmove', touchMoveFunc, false);
        	}
        }
    }
    catch (e) {
    	console.log('touchMoveFunc：' + e.message);
    }
}
/**
 * 删除emoji
 */
function deleteEmoji(){
	var content = document.getElementById("content") ? document.getElementById("content").value:'';
	if(content.length>0){
		content.substr(content.length-1)==']' && content.indexOf('[') != -1 ? document.getElementById("content").value=content.substring(0,content.lastIndexOf('[')):document.getElementById("content").value=content.substr(0,content.length-1);
	}
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
	imageBox.push('<div id="imageBoxBody"><div id="DivFixedPosition"></div><div class="imagePage"><div class="imageTotalBox"><img alt="image" id="addImages" onclick="wxChooseImage()" src="../addons/fm_jiaoyu/public/mobile/img/insertImage.png" class="addImages"><div class="imageTotal">(0/9)</div></div></div>');
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
	$(imagePage).html('<img alt="image" src="'+base64+'"  class="boxImages baseUploadImg"><span class="deleteImage" style= "background: url(../addons/fm_jiaoyu/public/mobile/img/deleteImage.png); background-size: 100%;display: inline;float: right;height: 25%;position: absolute;right: 0px;width: 25%;z-index: 4;" onclick="deleteImage(this)"></span>');
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
	$(imagePage).html('<img alt="image" src="'+url+'" id='+id+'  class="boxImages wxUploadImg"><span class="deleteImage" style= "background: url(../addons/fm_jiaoyu/public/mobile/img/deleteImage.png); background-size: 100%;display: inline;float: right;height: 25%;position: absolute;right: 0px;width: 25%;z-index: 4;" onclick="deleteImage(this)"></span>');
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
