/**
 * Created by zhoufeng on 16/8/31.
 */
$(function () {
    WebUtils.hideFallBackButton();
});

var WebUtils = {

    fallbackPage: function (suffixParamsStr) {
        if("index"==suffixParamsStr){
            window.location.href = path+"/index.do";
        }
        else if(window.history.length>1){
            window.history.go(-1);
        }else
            window.location.href = path+"/index.do";
    },

    isAccessByApp: function () {
        if (typeof(user_agent) == "undefined" || user_agent == null){
            return (false);
        }
        if (user_agent.indexOf("BLKIOSCOMMUNITYAPP") > -1 || user_agent.indexOf("BLKANDROIDCOMMUNITYAPP") > -1) {
            return (true);
        }
        return (false);
    },
    hideFallBackButton: function () {
        if(WebUtils.isAccessByApp()){
            if($(".forwxonly")){
                $(".forwxonly").hide();
            }
            if ($(".header-return")) {
                $(".header-return").hide();
            }
            if ($(".index-return")) {
                $(".index-return").hide();
            }
            if ($(".return-index")) {
                $(".return-index").hide();
            }
            if ($(".pro-footer-share")) {
                $(".pro-footer-share").hide();
            }
            if($(".returnico")){
                $(".returnico").hide();
            }
        }
    },

    bindingScrollLoading:function(callbackFunc,_callbackFunc){
        $(window).scroll(function() {
            if ($(document).scrollTop() <= 0) {
                if(_callbackFunc!=undefined&&typeof _callbackFunc=="function")
                    _callbackFunc();
            }
            if ($(document).scrollTop() >= $(document).height() - $(window).height()) {
                if(callbackFunc!=undefined&&typeof callbackFunc=="function")
                    callbackFunc();
            }
        });
    },

    configWeChatCustomShare:function(imgUrl,link,title,desc,shareCallBackFunc){
        var url = window.location.href;
        var _url = path + "/myWeChat/signature.do?url=" + encodeURIComponent(url);
        $.post(_url, function (res) {

            wx.config({
                "debug": false,
                "appId": res.appId,     //appid
                "timestamp":res.timestamp, //时间戳
                "nonceStr":res.nonceStr,  //生成签名的随机串
                "signature":res.signature, //签名
                "jsApiList":['onMenuShareTimeline','onMenuShareAppMessage']//分享到朋友圈，分享给朋友
            });

            wx.ready(function () {

                //分享朋友圈
                wx.onMenuShareTimeline({
                    title: title,  // 分享标题
                    link: link, //分享链接
                    imgUrl: imgUrl,	//分享图标
                    success: function () {
                        if(shareCallBackFunc!=undefined&&typeof shareCallBackFunc=="function")
                            shareCallBackFunc();
                    },
                    cancel: function () {
                    }
                });
                wx.onMenuShareAppMessage({
                    title: title, //分享标题
                    desc: desc,	 //分享描述
                    link: link, //分享链接
                    imgUrl: imgUrl,	//分享图标
                    trigger: function (res) {
                    },
                    success: function (res) {
                        if(shareCallBackFunc!=undefined&&typeof shareCallBackFunc=="function")
                            shareCallBackFunc();
                    },
                    cancel: function (res) {
                    },
                    fail: function (res) {
                    }
                });

            });

        },"json");//post end
    },
    getImgUploaderDom:function(targetUlId){
        var targetDom = null;
        if(targetUlId!=undefined&&targetUlId!=null&&targetUlId.length>0)
            targetDom = $("#"+targetUlId);
        else
            targetDom = $(".nav-ul:visible");
        return targetDom;
    },

    constructImgsUploaderJsonObj:function(targetUlId){

        var storedFiles = "";
        var inputIds = new Array();

        var uploader = WebUtils.getImgsUploader(targetUlId);
        uploader.el.find(".fileInput").each(function(){
            var storedFile = $(this).attr("stored_file");
            if(storedFile==undefined||storedFile==null||storedFile.length==0){
                if(this.files.length>0)
                    inputIds.push(this.id);
            }else{
                if(storedFiles.length>0)
                    storedFiles+=",";
                storedFiles += storedFile;
            }
        });
        return {"storeCate":uploader.storeCate,"uploadedFiles":storedFiles,"manualUploadInputs":inputIds};

    },

    getImgsUploader:function(targetUlId){
        return eval(WebUtils.getImgsUploaderId(targetUlId));
    },

    getImgsUploaderId:function(targetUlId){
        targetUlId = (targetUlId==undefined||targetUlId==null)?"":targetUlId;
        return "imguploader_"+(targetUlId.length==0?"default":targetUlId);
    },

    initImgsUploader:function(maximumImgElements,domIdentificationPrefix,imageClickEventStr,targetUlId,autoUploadTempFile,storedFilesObj){


        if(storedFilesObj==undefined||storedFilesObj==null)
            storedFilesObj = {"cate":"",files:[]};

        targetUlId = (targetUlId==undefined||targetUlId==null)?"":targetUlId;
        eval(WebUtils.getImgsUploaderId(targetUlId)+"=" +
            "{\"id\":\""+targetUlId+"\",\"maximumImgElements\":"+maximumImgElements+",\"autoUploadTempFile\":"+(autoUploadTempFile=="Y")+"," +
            "\"el\":WebUtils.getImgUploaderDom(this.id)," +
            "\"constructJsonObj\":function(){return WebUtils.constructImgsUploaderJsonObj(this.id);},\"uploading\":false,\"storeCate\":\""+storedFilesObj.cate+"\"}");

        if(autoUploadTempFile==undefined||autoUploadTempFile==null)
            autoUploadTempFile = "N";
        else
            autoUploadTempFile = "Y";

        if(domIdentificationPrefix==undefined||domIdentificationPrefix==null)
            domIdentificationPrefix = "test";
        if(maximumImgElements==undefined||maximumImgElements==null||maximumImgElements<1)
            maximumImgElements = 4;
        if(imageClickEventStr==undefined)
            imageClickEventStr = "";
        debugger;
        var targetDom = WebUtils.getImgUploaderDom(targetUlId);
        if(targetDom.length>0){
            $(targetDom[0]).empty();
            var ind = 0;
            if(typeof storedFilesObj =="object"&&storedFilesObj.files.length>0){
                for(var i in storedFilesObj.files){
                    var tmp_ind = parseInt(i,10)+1;
                    if(tmp_ind>maximumImgElements)
                        break;
                    ind = tmp_ind;
                    var file = storedFilesObj.files[i];
                    $(targetDom[0]).append("<li><div placeholder=\""+domIdentificationPrefix+"\" class=\"fileInputContainer\"><img name='"+domIdentificationPrefix+"_image' src='"+pathName+"/file/read.do?cate="+storedFilesObj.cate+"&file="+file+"' onclick=\""+imageClickEventStr+"\"/><input type=\"file\" id=\"file_"+new Date().getTime()+"\" name=\""+domIdentificationPrefix+"_fileInput\" stored_file=\""+file+"\" class=\"fileInput\" accept=\"image/*\" onchange=\"WebUtils.putIntoUploadImgsContainer(this,"+maximumImgElements+",'"+domIdentificationPrefix+"','"+imageClickEventStr+"','"+autoUploadTempFile+"');\"><i onclick=\"WebUtils.removeFromUploadImgsContainer(this,"+maximumImgElements+",'"+domIdentificationPrefix+"','"+imageClickEventStr+"','"+autoUploadTempFile+"')\"></i></div></li>");
                }

            }
            if(ind<maximumImgElements)
                $(targetDom[0]).append("<li><div placeholder=\""+domIdentificationPrefix+"\" class=\"fileInputContainer\"><img style=\"display:none;\" name='"+domIdentificationPrefix+"_image' src='' onclick=\""+imageClickEventStr+"\"/><input type=\"file\" id=\"file_"+new Date().getTime()+"\" name=\""+domIdentificationPrefix+"_fileInput\" class=\"fileInput\" accept=\"image/*\" onchange=\"WebUtils.putIntoUploadImgsContainer(this,"+maximumImgElements+",'"+domIdentificationPrefix+"','"+imageClickEventStr+"','"+autoUploadTempFile+"');\"><i style=\"display:none;\" onclick=\"WebUtils.removeFromUploadImgsContainer(this,"+maximumImgElements+",'"+domIdentificationPrefix+"','"+imageClickEventStr+"','"+autoUploadTempFile+"')\"></i></div></li>");
        }

    },

    putIntoUploadImgsContainer:function(dom,maximumImgElements,domIdentificationPrefix,imageClickEventStr,autoUploadTempFile){
        // Get a reference to the fileList
        var files = !!dom.files ? dom.files : [];
        // If no files were selected, or no FileReader support, return
        if (!files.length || !window.FileReader) return;
        // Only proceed if the selected file is an image
        if (/^image/.test(files[0].type)){

            var $liDom = $(dom).closest("li");
            var $ulDom = $(dom).closest("ul");
            var reader = new FileReader();
            // Read the local file as a DataURL
            reader.readAsDataURL(files[0]);
            // When loaded, set image data as background of div
            /*ulDom.append("<li><div placeholder=\""+domIdentificationPrefix+"\" class='fileInputContainer'><img style=\"display:none;\" name='"+domIdentificationPrefix+"_image' src='' onclick=\""+imageClickEventStr+"\"/><input type=\"file\" id=\"file_"+new Date().getTime()+"\" name=\""+domIdentificationPrefix+"_fileInput\" class=\"fileInput\" accept=\"image/*\" placeholder=\""+$("[name='"+domIdentificationPrefix+"_fileInput']").length+"\" onchange=\"WebUtils.putIntoUploadImgsContainer(this,"+maximumImgElements+",'"+domIdentificationPrefix+"','"+imageClickEventStr+"');\" /><i style=\"display:none;\" onclick=\"WebUtils.removeFromUploadImgsContainer(this,"+maximumImgElements+",'"+domIdentificationPrefix+"','"+imageClickEventStr+"')\"></i></div></li>");*/
            reader.onloadend = function(){
                var imgRes = this.result;

                if(autoUploadTempFile=="Y"){

                    /*eval(WebUtils.getImgsUploaderId($ulDom.attr("id"))+".uploading=true;");*/
                    $liDom.prepend("<p class=\"loading\"><i></i><span>img uploading...</span></p>");
                    WebUtils.doAjaxUploadForImgsUploader($ulDom,$liDom,dom.id,imgRes);

                }else{
                    $liDom.find(".fileInput").css("display","none");
                    $liDom.find("img").attr({"src":imgRes});
                    $liDom.find("i").attr({"class":"img-close-i" });
                    $liDom.find("img").show();
                    $liDom.find("i").show();
                }

                if($ulDom.find("li").length<maximumImgElements)
                    $ulDom.append("<li><div placeholder=\""+domIdentificationPrefix+"\" class='fileInputContainer'><img style=\"display:none;\" name='"+domIdentificationPrefix+"_image' src='' onclick=\""+imageClickEventStr+"\"/><input type=\"file\" id=\"file_"+new Date().getTime()+"\" name=\""+domIdentificationPrefix+"_fileInput\" class=\"fileInput\" accept=\"image/*\" placeholder=\""+$("[name='"+domIdentificationPrefix+"_fileInput']").length+"\" onchange=\"WebUtils.putIntoUploadImgsContainer(this,"+maximumImgElements+",'"+domIdentificationPrefix+"','"+imageClickEventStr+"','"+autoUploadTempFile+"');\" /><i style=\"display:none;\" onclick=\"WebUtils.removeFromUploadImgsContainer(this,"+maximumImgElements+",'"+domIdentificationPrefix+"','"+imageClickEventStr+"','"+autoUploadTempFile+"')\"></i></div></li>");

            }



        }else{
            $(dom).val("");
            alert("非法的图片文件格式！");
        }
    },
    doAjaxUploadForImgsUploader:function($ulDom,$liDom,fileInputId,imgRes){
        var uploader = eval(WebUtils.getImgsUploaderId($ulDom.attr("id")));
        if(!uploader.uploading){
            uploader.uploading=true;
            var url = pathName+"/uploader/upload.do";
            $.ajaxFileUpload({
                url:url,
                secureuri:false,
                type: 'post',
                fileElementId: [fileInputId],
                dataType: 'json',
                data:{},
                success: function (res){
                    $liDom.find(".loading").remove();
                    if(res.length>0){
                        $liDom.find(".fileInput").attr("stored_file",res[0]);
                        $liDom.find(".fileInput").css("display","none");
                        $liDom.find("img").attr({"src":imgRes});
                        $liDom.find("i").attr({"class":"img-close-i" });
                        $liDom.find("img").show();
                        $liDom.find("i").show();
                    }
                    eval(WebUtils.getImgsUploaderId($ulDom.attr("id"))+".uploading=false;");
                },
                error: function(data){
                    $liDom.find(".loading").remove();
                    eval(WebUtils.getImgsUploaderId($ulDom.attr("id"))+".uploading=false;");
                    alert("error");
                }
            });
        }else
            setTimeout(function(){WebUtils.doAjaxUploadForImgsUploader($ulDom,$liDom,fileInputId,imgRes);},1000);


    },
    removeFromUploadImgsContainer:function(dom,maximumImgElements,domIdentificationPrefix,imageClickEventStr,autoUploadTempFile){
        debugger;
        var count = 0;
        if(autoUploadTempFile=="Y")
            count = $(dom).closest("ul").find("li img:visible").length+$(dom).closest("ul").find("li p").length;
        else
            count = $(dom).closest("ul").find("li img:visible").length;

        if(count==maximumImgElements)
            $(".nav-ul:visible").append("<li><div placeholder=\""+domIdentificationPrefix+"\" class='fileInputContainer'><img style=\"display:none;\" name='"+domIdentificationPrefix+"_image' src='' onclick=\""+imageClickEventStr+"\"/><input type=\"file\" id=\"file_"+new Date().getTime()+"\" name=\""+domIdentificationPrefix+"_fileInput\" class=\"fileInput\" accept=\"image/*\" placeholder=\""+$("[name='"+domIdentificationPrefix+"_fileInput']").length+"\" onchange=\"WebUtils.putIntoUploadImgsContainer(this,"+maximumImgElements+",'"+domIdentificationPrefix+"','"+imageClickEventStr+"','"+autoUploadTempFile+"');\" /><i style=\"display:none;\" onclick=\"WebUtils.removeFromUploadImgsContainer(this,"+maximumImgElements+",'"+domIdentificationPrefix+"','"+imageClickEventStr+"','"+autoUploadTempFile+"')\"></i></div></li>");
        $(dom).closest("li").remove();

    },
    deleteFromUploadImgsContainer:function(dom){

        $(dom).closest("li").find("img-close-i").remove();

    },

    //校验字符串是否为空
    checkStr:function(temp){
        if(temp!=null && temp!="" && temp!=undefined ){
            return true;
        }else{
            return false;
        }
    },

    //空值处理
    dealValue:function(tempStr){
        return WebUtils.checkStr(tempStr)?tempStr:"";
    },

    //检查手机号码
    checkMobile:function(phone){
        var temp = /^1\d{10}$/;
        if(temp.test(phone)){
            return true;
        }
        return false;
    },

    //获取请求url后面的参数
    GetRequestParam:function(){
        var url = location.search; //获取url中"?"符后的字串
        var theRequest = new Object();
        if (url.indexOf("?") != -1) {
            var str = url.substr(1);
            strs = str.split("&");
            for(var i = 0; i < strs.length; i ++) {
                theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);
            }
        }
        return theRequest;
    }
};

/*
 *
 2 * MAP对象，实现MAP功能
 3 *
 4 * 接口：
 5 * size()     获取MAP元素个数
 6 * isEmpty()    判断MAP是否为空
 7 * clear()     删除MAP所有元素
 8 * put(key, value)   向MAP中增加元素（key, value)
 9 * remove(key)    删除指定KEY的元素，成功返回True，失败返回False
 10 * get(key)    获取指定KEY的元素值VALUE，失败返回NULL
 11 * element(index)   获取指定索引的元素（使用element.key，element.value获取KEY和VALUE），失败返回NULL
 12 * containsKey(key)  判断MAP中是否含有指定KEY的元素
 13 * containsValue(value) 判断MAP中是否含有指定VALUE的元素
 14 * values()    获取MAP中所有VALUE的数组（ARRAY）
 15 * keys()     获取MAP中所有KEY的数组（ARRAY）
 16 *
 17 * 例子：
 18 * var map = new Map();
 19 *
 * map.put("key", "value");
 * var val = map.get("key")
 */
(function(win) {
    var Map = function() {
        this.count = 0;
        this.entrySet = {};
    };
    var proto = Map.prototype;
    proto.size = function() {
        return this.count;
    };

    proto.isEmpty = function() {
        return this.count === 0;
    };

    proto.containsKey = function(key) {
        if (this.isEmpty()) {
            return false;
        }

        for ( var prop in this.entrySet) {
            if (prop === key) {
                return true;
            }
        }

        return false;
    };

    proto.containsValue = function(value) {
        if (this.isEmpty()) {
            return false;
        }

        for ( var key in this.entrySet) {
            if (this.entrySet[key] === value) {
                return true;
            }
        }

        return false;
    };

    proto.get = function(key) {
        if (this.isEmpty()) {
            return null;
        }

        if (this.containsKey(key)) {
            return this.entrySet[key];
        }

        return null;
    };

    proto.put = function(key, value) {
        this.entrySet[key] = value;
        this.count++;
    };

    proto.remove = function(key) {
        if (this.containsKey(key)) {
            delete this.entrySet[key];
            this.count--;
        }
    };

    proto.putAll = function(map) {
//　　　　　if(!map instanceof Map){
//　　　　　　  return ;
//　　　　　}

        for ( var key in map.entrySet) {
            this.put(key, map.entrySet[key]);
        }
    };

    proto.clear = function() {
        for ( var key in this.entrySet) {
            this.remove(key);
        }
    };

    proto.values = function() {
        var result = [];

        for ( var key in this.entrySet) {
            result.push(this.entrySet[key]);
        }

        return result;
    };

    proto.keySet = function() {
        var result = [];

        for ( var key in this.entrySet) {
            result.push(key);
        }

        return result;
    };

    proto.toString = function() {
        var result = [];
        for ( var key in this.entrySet) {
            result.push(key + ":" + this.entrySet[key]);
        }

        return "{" + result.join() + "}";
    };

    proto.valueOf = function() {
        return this.toString();
    };

    win.Map = Map;
})(window);
