var map = new BMap.Map("allmap");
var gc = new BMap.Geocoder();
function getaddress(lat, lng) {
    var point = new BMap.Point(lng,lat);
    var convertor = new BMap.Convertor();
    var pointArr = [];
    pointArr.push(point);
    convertor.translate(pointArr, 1, 5, function(data) {
        if (data.status === 0) {
            $("#hidlat").val(data.points[0].lat);
            $("#hidlng").val(data.points[0].lng);
            point = data.points[0]
        }
        gc.getLocation(point, function(rs) {
            var showAddress = rs.addressComponents.city + "," + rs.addressComponents.district + "," + rs.addressComponents.street;
            $(".location-text").text(showAddress).removeClass("location-no").addClass("location-ok");
            $("#hidaddress").val(JSON.stringify(rs.addressComponents));
            addStorage()
        })
    })
}
function getLocation() {
    $(".location-text").text("定位中...");
    wx.getLocation({
        type: "wgs84",
        success: function(res) {
            $("#hidlat").val(res.latitude);
            $("#hidlng").val(res.longitude);
            $("#hidspeed").val(res.speed);
            $("#hidacy").val(res.accuracy);
            getaddress(res.latitude, res.longitude)
        },
        cancel: function(res) {
            var IsNeedPosition = $("#IsNeedPosition").val();
            if (IsNeedPosition == 1) {
                layer.open({
                    content: "^.^大家一起共享位置,看看身边有没有好玩的话题",
                    btn: ["好的", "取消"],
                    shadeClose: false,
                    yes: function() {
                        layer.closeAll();
                        getLocation()
                    },
                    no: function() {
                        layer.closeAll()
                    }
                })
            }
        },
        fail: function(res) {
            $(".location-text").text("点击发送位置")
        }
    })
}
function selectLocal(type) {
    $("#VideoOrMusic").val(type);
    layer.closeAll();
    $("#fileUp").click()
}
function processerbar(time) {
    document.getElementById("probar").style.display = "block";
    $("#line").each(function(i, item) {
        var a = parseInt($(item).attr("w"));
        $(item).animate({
            width: a + "%"
        }, time)
    })
}
function uploadFile(type) {
    var fileObj = document.getElementById("fileUp").files;
    var fileType = getExtend(fileObj[0].name);
    if (fileObj.length == 0) {
        alert("请选择文件");
        return
    }
    if (type == "voice") {
        var str = ".cda,.wav,.mp3,.wma,.ra,.midi,.ogg,.ape,.flac,.aac";
        if (str.indexOf(fileType.toLowerCase(), 0) < 0) {
            alert("音频文件格式不对,暂时支持格式有：\r\n.cda,.wav,.mp3,.wma,.ra,.midi,.ogg,.ape,.flac,.aac");
            return
        }
    } else {
        if (fileType.length > 10 || "" == fileType) {
            fileType = ".mp4"
        }
        var str = ".mpeg4,.mp4,.mov,.avi,.wmv,.asf,.odd,.3gp";
        if (str.indexOf(fileType.toLowerCase(), 0) < 0) {
            alert("抱歉,目前只支持：\r\n.mpeg4,.mp4,.mov,.avi,.wmv,.asf,.odd,.3gp");
            return
        }
    }
    if (fileObj[0].size > 100 * 1024 * 1024) {
        alert("文件不能大于100M");
        return
    }
    layer.open({
        type: 1,
        shadeClose: false,
        content: '<div style="margin:10px;padding:10px;width:450px;height:120px;background:#EEE;">' + '<div class="barline" id="probar"><div id="percent"></div>' + '<div id="line" w="100" style="width:0px;"></div>' + '<div id="msg" style=""></div>' + "</div></div>"
    });
    var si = window.setInterval(function() {
        a = $("#line").width();
        b = (a / 200 * 100).toFixed(0);
        document.getElementById("percent").innerHTML = b + "%";
        document.getElementById("percent").style.left = a - 12 + "px";
        document.getElementById("msg").innerHTML = "上传中...";
        if (document.getElementById("percent").innerHTML == "100%") {
            clearInterval(si)
        }
    }, 70);
    processerbar(90000);
    var _FId = $("#hidminisnsId").val();
    $.ajaxFileUpload({
        url: "/t/UploadVoiceOrVideo-" + _FId,
        secureuri: false,
        fileElementId: "fileUp",
        dataType: "json",
        data: {
            fileType: fileType,
            UId: $("#hiduserId").val(),
            VideoOrMusic: $("#VideoOrMusic").val(),
            FId: _FId
        },
        success: function(data, status) {
            if (data.result == 1) {
                innerVoice(data)
            }
            if (data.result == 2) {
                window.clearInterval(si);
                layer.closeAll();
                var vidurl = data.youkuurl;
                var iframeDivHtml = "";
                $("#hvid").val(data.Vid);
                iframeDivHtml = $("#VideoLocalPostOne_Tmp").html();
                $("#hvideo").val(vidurl);
                $(".post-video-all").html(iframeDivHtml);
                $(".post-video-thread").hide();
                addStorage()
            } else {
                alert(data.msg);
                window.clearInterval(si);
                layer.closeAll()
            }
        },
        error: function(data, status, e) {
            layer.closeAll();
            window.clearInterval(si);
            alert("上传失败,网络是否畅通或者文件过大")
        }
    })
}
function innerVoice(data) {
    $("#hidrecordSerId").val("");
    $("#hidrecordId").val(data.id);
    var t = "";
    if (data.time && data.time > 0) {
        t = data.time + '"'
    }
    var rechtml = $("#VoicePostOne_Tmp").html().replace("{MP3PATH}", data.url).replace("{TIME}", t).replace("{LOCALID}", "0").replace("{TYPE}", "MP3");
    $("#reclist").html(rechtml);
    document.getElementById("myvoice").play();
    $("#myvoice").attr("class", "on");
    addStorage();
    layer.closeAll()
}
function playMp3() {
    if ($("#myvoice").hasClass("on")) {
        $("#myvoice").attr("class", "off");
        document.getElementById("myvoice").pause()
    } else {
        $("#myvoice").attr("class", "on");
        document.getElementById("myvoice").play()
    }
}
function getExtend(file_name) {
    var result = file_name.match(/\.[^\.]+/);
    return ( (result + "").toLowerCase()) 
}
var imgcounts = 0;
var images = {
    localId: [],
    serverId: []
};
function uploadimg() {
    wx.chooseImage({
        success: function(res) {
            images.localId = res.localIds;
            var i = 0
              , length = images.localId.length;
            if ((imgcounts + length) > 9) {
                Popup(0, "亲，最多只能上传9张哦");
                return false
            }
            if ((imgcounts + length) == 9) {
                $("#btn-addimg").hide()
            } else {
                $("#btn-addimg").show()
            }
            function upload() {
                wx.uploadImage({
                    localId: images.localId[i],
                    isShowProgressTips: 1,
                    success: function(res) {
                        i++;
                        core.post('upload',{serverId:res.serverId,localId:images.localId[i]},function(data){
                        	if (data.id != -1) {
                                var rethtml = $("#post_img_html_one").html().replace("FILEPATH", data.path).replace("IMGID", data.id);
                                imgcounts++;
                                $("#imgList").find("li").eq(0).before(rethtml);
                                $("#imgList").show();
                                addStorage()
                            } else {
                                alert("该图片已损坏或无法显示，请重新选择图片上传！")
                            }
                        });
                        if (i < length) {
                            upload()
                        }
                    },
                    fail: function(res) {}
                })
            }
            upload()
        }
    })
}
function vzanpostarticle() {
    var _choosedType = $("#choosedType").val();
    if ($("#PostRule").val() == 1 && (IsNullOrEmpty(_choosedType) || _choosedType == 0)) {
        $(".plate-container,.tc_bg").show();
        (function() {
            var scroll = new fz.Scroll("#bk_li",{
                scrollY: true
            })
        })();
        return false
    }
    if ($("#is_placetop_open").prop("checked")) {
        var _top_hours = $("#top_hours").val();
        if (IsNullOrEmpty(_top_hours) || _top_hours == "0") {
            alert("请选择“置顶”的范围，方式 以及时长");
            return false
        }
    } else {
        if ($("#ty-" + _choosedType).attr("ischarge") == 1) {
            var _all_hours = $("#all_hours").val();
            if (IsNullOrEmpty(_all_hours) || _all_hours == "0") {
                alert("请选择“帖子显示”方式 以及时长");
                return false
            }
        }
    }
    if ($("#IsNeedPosition").val() == 1 && ($("#hidlat").val() == 0 || IsNullOrEmpty($("#hidaddress").val()))) {
        layer.open({
            content: "发帖需要获取您当前的位置",
            btn: ["好的", "取消"],
            shadeClose: false,
            yes: function() {
                layer.closeAll();
                getLocation()
            },
            no: function() {
                layer.closeAll()
            }
        });
        return
    }
    var imgids = [];
    $(".temp_img_one").each(function() {
        imgids.push($(this).attr("id"))
    });
    if (imgids.length > 0) {
        $("#hImgIds").val(imgids)
    }
    if (($("#txtContentAdd").val().trim() == "" || $("#txtContentAdd").val().trim().length < 2) && (($("#hidrecordSerId").val().trim() == "") && (imgids == "") && ($("#hvideo").val() == "") && $("#hidrecordId").val() == "")) {
        Popup(0, "内容太少啦");
        return
    }
    showLoadingUI("提交中");
    if ($("#farticle #value-add-services").length > 0) {
        $("#farticle #value-add-services").remove()
    }
    $("#farticle").append($("#value-add-services"));
    
    core.post('vcc_add',$("#farticle").serialize(),function(data){
        layerClose();
        if (data.code == 1) {
            clearStorage();
            var aid = data.aid;
            var money = $("#hdguerdonmoney").val();
            if (data.isguerdon > 0) {
                if (data.isguerdon == 7) {
                    alert("您发布的打赏阅读贴，需要管理员审核才能看到，请耐心等待");
                    //window.location.href = data.msg
                } else {
                    window.location.href = "/pay/ag-" + aid + "?fee=" + data.money
                }
            } else {
                if (data.state == -2) {
                    alert("您的帖子存在违规词，需要人工审核才能看到，请耐心等待")
                }
                //window.location.href = data.msg
            }
        } else {
            if (data.code == -1) {
                alert(data.msg)
            } else {
                if (data.code == 2) {
                    window.location.href = data.url
                } else {
                    alert(data.msg)
                }
            }
        }
    });
}
function guerdonOk_Click(lower) {
    var money = $("#gmoney").val();
    if (!CheckMoney(lower, money)) {
        return false
    }
    $("#hdguerdonmoney").val(money);
    $("#guerdonmoney").text(money);
    $(".post-reward-all").show();
    $(".temp-reward-remove").one("click", function() {
        deleteGuerdon()
    });
    $("#selectCharge").hide();
    $(".charge-tips").hide();
    if (!IsNullOrEmpty($("#choosedType").val())) {
        if ($("#ty-" + $("#choosedType").val()).attr("ischarge") == "1") {
            $("#choosedType").val("");
            $("#ty-" + $("#choosedType").val()).removeClass("on")
        }
    }
}
function deleteGuerdon() {
    $("#guerdonmoney").html("");
    $("#hdguerdonmoney").val("");
    $(".post-reward-all").hide()
}
function CheckMoney(lower, value) {
    if (value == "") {
        Popup(0, "请输入金额");
        return false
    }
    if (!isPositiveNum(value)) {
        Popup(0, "请输入正整数");
        return false
    }
    var money = parseInt(value);
    if (!money || money > 999 || money < lower) {
        Popup(0, "金额范围" + lower + "-999");
        return false
    }
    return true
}
function isPositiveNum(s) {
    var re = /^[0-9]*[1-9][0-9]*$/;
    return re.test(s)
}
function saveVideo() {
    var st = $("#txtvideourl").val().replace("https:", "http:");
    var regex = /http:\/\/[^]+\.[^]+/;
    var r = st.match(regex);
    if (r == null ) {
        alert("请输入正确视频的路劲\r\n视频路劲为浏览器地址栏地址，包含http://");
        $("#txtvideourl").focus();
        return
    }
    var iframeDivHtml = $("#VideoPostOne_Tmp").html();
    if (st.indexOf("iqiyi.com") > -1) {
        $.ajax({
            url: "http://123.56.75.84:8089/api/getvideoinfo",
            type: "Get",
            dataType: "json",
            data: {
                url: st
            },
            success: function(data) {
                layer.closeAll();
                if (data == null  || data == "") {
                    alert("该地址暂时不支持获取视频分享内容，请更换地址")
                }
                iframeDivHtml = iframeDivHtml.replace("{VIDEOURL}", data);
                $("#hvideo").val(data);
                $(".post-video-all").html(iframeDivHtml);
                $(".post-video-thread").hide();
                addStorage()
            }
        })
    } else {
        var vidurl = getUrl(st);
        if (IsNullOrEmpty(vidurl)) {
            var submitData = {
                url: encodeURIComponent(st)
            };
            core.post('vcc_getvideourl',submitData,function(data){
                    if (1 == data.status) {
                        if (data.vidurl != "") {
                            vidurl = decodeURIComponent(data.vidurl);
                            iframeDivHtml = iframeDivHtml.replace("VIDEOURL", vidurl);
                            $("#hvideo").val(vidurl);
                            $(".post-video-all").html(iframeDivHtml);
                            $(".post-video-thread").hide();
                            addStorage()
                        } else {
                            alert("该地址暂时不支持获取视频分享内容，请更换地址")
                        }
                    } else {
                        alert("该地址暂时不支持获取视频分享内容，请更换地址")
                    }
            });
        } else {
            iframeDivHtml = iframeDivHtml.replace("VIDEOURL", vidurl);
            $("#hvideo").val(vidurl);
            $(".post-video-all").html(iframeDivHtml);
            $(".post-video-thread").hide();
            addStorage()
        }
    }
}
var expendlevel = 1;
function thissize() {
    var w = $(".post-wrap").width();
    $("#bk_li").animate({
        "width": w / expendlevel - 1
    });
    $("#bk_li2").animate({
        "width": w / expendlevel - 1,
        "left": w / expendlevel
    });
    $("#bk_li3").animate({
        "width": w / expendlevel - 1,
        "left": w / expendlevel + w / expendlevel,
    });
    if (expendlevel != 1) {
        var current_expend = $(".ui-scroller").eq(expendlevel - 1);
        current_expend.show();
        current_expend.prevAll(".ui-scroller").show();
        current_expend.next(".ui-scroller").hide()
    } else {
        $(".ui-scroller").eq(expendlevel - 1).next(".ui-scroller").hide()
    }
    (function() {
        var scroll = new fz.Scroll("#bk_li",{
            scrollY: true
        })
    })()
}
$(function() {
    thissize();
    $(window).resize(thissize);
    $(".tc_bg").click(function(e) {
        $(".plate-container,.tc_bg").hide();
        e.stopPropagation()
    });
    $(document).on("click", "#bk_li dt", function() {
        expendlevel = 2;
        $(this).addClass("qh").siblings().removeClass("qh");
        var typeId = $(this).data("typeid");
        var vName1 = $(this).data("name1");
        var sub_types = $("#temp_level2 dl[pid='" + typeId + "']");
        var sub_types_clone = sub_types.clone(true);
        if (sub_types.length == 0) {
            chooseTypeEnd(typeId, vName1);
            return
        } else {
            $(".level2").html(sub_types_clone.show())
        }
        thissize();
        (function() {
            var scroll = new fz.Scroll("#bk_li2",{
                scrollY: true
            })
        })()
    });
    $(document).on("click", "#bk_li2 dt", function() {
        expendlevel = 3;
        $(this).addClass("qh").siblings().removeClass("qh");
        var typeId = $(this).data("typeid");
        var vName1 = $(this).data("name1");
        var vName2 = $(this).data("name2");
        var sub_types = $("#temp_level3 dl[pid='" + typeId + "']");
        var sub_types_clone = sub_types.clone(true);
        if (sub_types.length == 0) {
            chooseTypeEnd(typeId, vName1, vName2);
            return
        } else {
            $(".level3").html(sub_types_clone.show())
        }
        thissize();
        (function() {
            var scroll = new fz.Scroll("#bk_li3",{
                scrollY: true
            })
        })()
    });
    $(document).on("click", "#bk_li3 dt", function() {
        var typeId = $(this).data("typeid");
        var vName1 = $(this).data("name1");
        var vName2 = $(this).data("name2");
        var vName3 = $(this).data("name3");
        chooseTypeEnd(typeId, vName1, vName2, vName3);
        return
    })
});
function ArtypeChoose(typeobj) {
    var stypecount = parseInt(typeobj.data("stypecount"));
    var typeId = typeobj.data("typeid");
    var vName1 = typeobj.text();
    if (stypecount > 0) {
        if (!$(".plate-container").is(":hidden")) {
            $(".plate-container,.tc_bg").hide()
        } else {
            $(".plate-container,.tc_bg").show();
            $("#ty-" + typeId).click()
        }
        var typeObj = $("#ty-" + typeId);
        changeprice(typeObj);
        thissize();
        event.stopPropagation()
    } else {
        chooseTypeEnd(typeId, vName1)
    }
}
function chooseTypeEnd(vTypeId, vName1, vName2, vName3) {
    $("#choosedType").val(vTypeId);
    if (!IsNullOrEmpty(vName1)) {
        $(".success-select-column .success_bk1").text(vName1).show()
    } else {
        $(".success-select-column .success_bk1").hide()
    }
    if (!IsNullOrEmpty(vName2)) {
        $(".success-select-column .success_bk2").text(vName2).show()
    } else {
        $(".success-select-column .success_bk2").hide()
    }
    if (!IsNullOrEmpty(vName3)) {
        $(".success-select-column .success_bk3").text(vName3).show()
    } else {
        $(".success-select-column .success_bk3").hide()
    }
    $(".plate-container,.tc_bg,.post-section-first-level-column").hide();
    $(".success-select-column").show();
    var typeObj = $("#ty-" + vTypeId);
    changeprice(typeObj)
}
$(document).ready(function() {
    $("#txtContentAdd").focus();
    var viewTypeId = $("#choosedType").val();
    if (viewTypeId > 0) {
        var typeObj = $("#ty-" + viewTypeId);
        if (typeObj.length > 0) {
            var tlevevl = typeObj.data("levevl");
            if (tlevevl == 3) {
                chooseTypeEnd(viewTypeId, typeObj.data("name1"), typeObj.data("name2"), typeObj.data("name3"))
            } else {
                if (tlevevl == 2) {
                    var sub_types = $("#temp_level3 dl[pid='" + viewTypeId + "']");
                    if (sub_types.length > 0) {
                        var cPId = typeObj.parent().attr("pid");
                        $("#first_" + cPId).click()
                    } else {
                        chooseTypeEnd(viewTypeId, typeObj.data("name1"), typeObj.data("name2"), typeObj.data("name3"))
                    }
                } else {
                    ArtypeChoose($("#first_" + viewTypeId))
                }
            }
        }
    } else {
        $(".post-section-first-level-column").show();
        $(".success-select-column").hide()
    }
    $(document).on("click", "#btn-add-article", function() {
        vzanpostarticle()
    });
    $(document).on("click", ".post-section-first-level-column li", function() {
        ArtypeChoose($(this))
    });
    $(document).on("click", ".success-select-column-close", function() {
        $("#choosedType").val("0");
        $(".post-section-first-level-column").show();
        $(".success-select-column").hide()
    });
    $(document).on("click", ".icon-post-pic,#btn-addimg", function() {
        uploadimg()
    });
    $(document).on("click", ".temp-voice-close", function(e) {
        var voiceType = $(this).data("voicetype");
        $("#hidrecordId").val("");
        $("#hidrecordSerId").val("");
        $("#hidrecordTime").val("0");
        $("#hidrecordText").val("");
        if (voiceType == "MP3") {
            document.getElementById("myvoice").pause()
        }
        $(this).parent().parent().remove();
        e.stopPropagation()
    });
    $(document).on("click", ".temp_clear_img", function() {
        imgcounts--;
        $(this).parent().remove();
        $("#btn-addimg").show();
        if (imgcounts <= 0) {
            $("#btn-addimg").hide()
        }
    });
    $(document).on("click", ".post-voice-box-rp", function() {
        var localId = $(this).data("localid");
        if (localId == "0") {
            playMp3()
        } else {
            var stateBox = $($(this).find("div")[0]);
            wx.playVoice({
                localId: localId,
                success: function(res) {
                    stateBox.attr("class", "voice-box-playing");
                    wx.onVoicePlayEnd({
                        complete: function(res) {
                            stateBox.attr("class", "post-voice-box-pause")
                        }
                    })
                },
                fail: function(res) {
                    Popup(0, "无法播放")
                }
            })
        }
    });
    $(document).on("click", ".temp-location-btn", function() {
        getLocation()
    });
    $(document).on("click", ".icon-post-video", function() {
        $(".post-video-thread").toggle();
        if (!$(".post-video-thread").is(":hidden")) {
            document.getElementById("txtvideourl").scrollIntoView()
        }
    });
    $(document).on("click", ".temp-video-local", function() {
        selectLocal("video")
    });
    $(document).on("click", ".temp-video-online", function() {
        saveVideo()
    });
    $(document).on("click", ".tem-video-remove", function(e) {
        $("#hvid").val("");
        $("#hvideo").val("");
        $("#txtvideourl").val("");
        $(this).parent().remove();
        e.stopPropagation()
    });
    $(document).on("click", ".icon-post-reward", function() {
        var $dialog = $("#gurdon_dialog");
        $dialog.show();
        $("#gmoney").focus();
        $dialog.find(".temp_dialog_cancle").one("click", function() {
            $dialog.hide()
        });
        $dialog.find(".temp_dialog_ok").one("click", function() {
            var leastMoney = $(this).data("minmoney");
            guerdonOk_Click(leastMoney);
            $dialog.hide()
        })
    });
    $(document).on("click", ".icon-post-expression", function() {
        $(".post-qq-face-all").toggle()
    });
    $(document).on("click", "#txtContentAdd", function() {
        $(".post-qq-face-all").hide()
    });
    $(document).on("click", ".post-qq-face a", function() {
        var facedata = $(this).attr("code");
        if ($("#txtContentAdd").length > 0) {
            $("#txtContentAdd").val($("#txtContentAdd").val() + facedata)
        }
    })
});
wx.ready(function() {
    if ($("#IsNeedPosition").val() == 1) {
        setTimeout("getLocation()", 1000)
    }
});
