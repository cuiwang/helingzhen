
function keyUp(e) {
    var e = e || event;
    var key = e.keyCode;
    var keyName = String.fromCharCode(key);
    if (key === 13) { //表示按的是enter键
        $("#btnSearch").click();
    }
}
function del(PersonalPicWallId, obj,url) {

    $.ajax({
        cache: true,
        type: "POST",
        url: url, // 提交的URL 
        data: { type: "like", personalPicWallId: PersonalPicWallId, OpenId: $("#hidOpenId").val() },
        async: false,
        success: function (result) {

            if (result != "liked") {
                $(obj).parent(".hot").find("#likeNum").html(result);
            }
            else {
                alert("您已经点过赞啦！");
            }
        },
        error: function (request) {
            alert("Connection error");
        }
    });
}

function loadpage(pageIndex,url) {
    var pageSize = $.trim($("#hiddpage").val()) == "" ? 6 : $.trim($("#hiddpage").val());
    $("#hiddpageIndex").val(pageIndex);
    var txtKeywords = $.trim($("#txtKeywords").val());
    $.ajax({
        type: "post",
        async: false,
        url: url,
        data: { type: "query", pageIndex: pageIndex, pageSize: pageSize, txtKeywords: txtKeywords},
        dataType: "html",
        success: function (data) {
            if (data != "") {
                var list = new Array();
                list = data.split("~");
                $("#leftLi").append(list[0]);
                $("#rightLi").append(list[1]);
            }
        }
    });
    $("#popFail").hide("slow");
}

function popUp(obj) {
    photoDiv.style.display = "";
    photoMask.style.display = "";
    photoClick.style.zIndex = "9999";
    $("#scroller").html($(obj).html());
    var img = $("#scroller").children().get(0);

    //            alert(document.documentElement.offsetHeight);
    var imgMax = new Image();

    imgMax.src = img.src;
    img.onload = function () { //谷歌下必须用onload
        var width = imgMax.width; ;
        var height = imgMax.height;
        if (height > width) {
            AutoResizeImage(0, document.documentElement.offsetHeight - 30, width, height, img);
            document.getElementById('scroller').style.display = '';
            document.getElementById('scroller').style.verticalAlign = '';
            document.getElementById('scroller').style.textAlign = '';
        } else {
            AutoResizeImage(document.documentElement.clientWidth, 0, width, height, img);
            document.getElementById('scroller').style.height = document.documentElement.offsetHeight - 30;
            document.getElementById('scroller').style.display = 'table-cell';
            document.getElementById('scroller').style.verticalAlign = 'middle';
            document.getElementById('scroller').style.textAlign = 'center';
        }
    };


}

function closePOP() {
    photoDiv.style.display = "none";
    photoMask.style.display = "none";
}

function AutoResizeImage(maxWidth, maxHeight, width, height, objImg) {
    var hRatio;
    var wRatio;
    var Ratio = 1;
    var w = width;
    var h = height;
    wRatio = maxWidth / w;
    hRatio = maxHeight / h;

    if (maxWidth == 0 && maxHeight == 0) {
        Ratio = 1;
    } else if (maxWidth == 0) {//
        Ratio = hRatio;
    } else if (maxHeight == 0) {
        if (wRatio < 1) Ratio = wRatio;
    } else if (wRatio < 1 || hRatio < 1) {
        Ratio = (wRatio <= hRatio ? wRatio : hRatio);
    }
    if (Ratio < 1) {
        w = w * Ratio;
        //                    alert(Ratio);
        h = h * Ratio;
        objImg.style.height = h;
        objImg.style.width = w;
    }
    if (Ratio > 1) {
        w = w * Ratio;
        objImg.style.height = maxHeight;
        if (w > document.body.offsetWidth) {
            w = document.body.offsetWidth;
        }
        objImg.style.width = w;
    } if (Ratio == 1) {
        objImg.style.height = h;
        objImg.style.width = w;
    }


}
 