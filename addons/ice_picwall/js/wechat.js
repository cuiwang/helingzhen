/*
Powered by xiangruihong
*/

var wechat_obj = {
    material_init: function () {
        $.ajax({
            type: "post",
            url: '../Json/Material/NewsDataList.aspx', // 提交的URL 
            data: { type: "news" },
            async: false,
            success: function (data) {
                $('#material>.list #newslist').html(data);
                $('#material>.list').masonry({ itemSelector: '.item', columnWidth: 367 });
            },
            error: function (request) {
                alert("Connection error");
            }
        });
        $('#material>.list .mod_del .del a ').click(function () {
            if (!confirm('删除后不可恢复，继续吗？')) { return false };
            var newsid = $(this).attr("newsid");
            $.ajax({
                type: "post",
                url: '../Json/Material/NewsDataList.aspx', // 提交的URL 
                data: { type: "del", newsid: newsid },
                async: false,
                success: function (data) {
                    $('#material>.list #newslist').html(data);
                    //alert(data);
                    //$('#material>.list').masonry({ itemSelector: '.item', columnWidth: 367 });                
                    location.reload();
                },
                error: function (request) {
                    alert("删除失败");
                }
            });
        });
        $('#material>.list .mod_del .mod a').click(function ()
        {
            var newsid = $(this).attr("newsid");
            var newstype = $(this).attr("newstype");
            if (newstype == "1") $(this).attr("href", "../Material/EditSingelPicText.aspx?ID=" + newsid+"&newstype="+newstype);
            else $(this).attr("href", "../Material/EditPicText.aspx?ID=" + newsid+"&newstype="+newstype);
        });
    },

    material_one_init: function () {
        global_obj.file_upload($('#MsgFileUpload'), $('#material_form input[name=ImgPath]'), $('#MsgImgDetail'));
        $('#MsgImgDetail').html(global_obj.img_link($('#material_form input[name=ImgPath]').val()));

        $('#material_form input[name=Title]').on('keyup paste blur', function () {
            $('#material_form .title').html($(this).val());
        });
        $('#material_form textarea').on('keyup paste blur', function () {
            $('#material_form .txt').html($(this).val().replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1<br>$2'));
        });
        $("select[name=Url]").find("option[value='" + $('input[name=oUrl]').val() + "']").attr("selected", true);
    },

    material_multi_init: function () {

        var i = 2;
        var cur_id = "";
        var editor;
        function a() {
            if (cur_id != "") {
//                setTimeout(a, 3000);
                $(cur_id + ' input[name=NewsContent\\[\\]]').val(editor.html());
                
            } else {
//                setTimeout(a, 3000);
                $('#multi_msg_0 input[name=NewsContent\\[\\]]').val(editor.html());
            }
        }

          $("#img_upload").uploadify({
            'buttonText': '上传图片',
            'method': 'post',
            'swf': '/Js/uploadify/uploadify.swf',
            'uploader': '/UploadHandler.ashx',
            'cancelImg': '/Js/uploadify/uploadify-cancel.png',
            'queueID': 'imgQueue',
            'progressData': 'speed',
            'removeTimeout': 0,
            'fileSizeLimit': '5MB',
            'fileTypeExts': '*.gif; *.jpg; *.png; *.bmp',
            'auto': true,
            'multi': false,
            //开始上传成功之前执行，每上传一个附件就会执行一次
            'onUploadStart': function (file) {
                var uploadPath = 'UploadImg/Material';
                $('#img_upload').uploadify('settings', 'formData', {
                    'uploadPath': uploadPath,
                });
            },
            'onUploadSuccess': function (file, data, response) {
                if (response == true) {
                    alert("上传成功！");
                    $(cur_id + " .img").html('<img src="' + data + '" />');
                    $(cur_id + " .IPath").val(data);
//                    $("#TopImgUrl").val(data);
                }
                else
                {
                    alert('上传失败');
                }
            }
        });
            KindEditor.ready(function (K) {
            editor = K.create("#NewsContent", {
                cssPath : '../Js/kindeditor-4.1.10/plugins/code/prettify.css',
                uploadJson : '../Js/kindeditor-4.1.10/asp.net/upload_json.ashx',
                fileManagerJson : '../Js/kindeditor-4.1.10/asp.net/file_manager_json.ashx',
                height: '290px',
                width: '600px',
                resizeType: '2',
                newlineTag: 'p',
                allowFileManager: true,
                allowUpload: true,
                fontSizeTable: ['12px', '14px', '16px', '18px', '24px', '32px'],
                pasteType: 1,
                allowImageRemote: false,
                afterBlur:function() {
//                    b();
                    a();
                },
                items: ['preview', '|', 'justifyleft', 'justifycenter', 'justifyright',
                    'justifyfull', 'insertorderedlist', 'insertunorderedlist', '|', 'fullscreen',
                    'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                    'italic', 'underline', 'strikethrough', 'removeformat', '|', 'emoticons', 'image', 'table'
                ]
            });
               
        });
 
        $("#sumit").click(function() {
            var strId=$('.multi .list:last').attr("id");
            var num = parseInt(strId.substring(strId.length-1,strId.length));
            var jsonStr = "";
            var newsContent = new Array("","","","","","","","","","");
            if ($('#multi_msg_0 input[name=selectMethod\\[\\]]').val() == "2") {
                 jsonStr = "[{\"Title\": \""+$('#multi_msg_0 input[name=Title\\[\\]]').val()+"\", \"ImgUrl\":\" "+$('#multi_msg_0 input[name=ImgPath\\[\\]]').val()+"\", \"NewsUrl\": \""+$('#multi_msg_0 input[name=linkUrl\\[\\]]').val()+"\",\"NewsContent\":"+"\"\""+"}";
            }
            if ($('#multi_msg_0 input[name=selectMethod\\[\\]]').val() == "1") {
                 jsonStr = "[{\"Title\": \""+$('#multi_msg_0 input[name=Title\\[\\]]').val()+"\", \"ImgUrl\":\" "+$('#multi_msg_0 input[name=ImgPath\\[\\]]').val()+"\", \"NewsUrl\":"+"\"\""+",\"NewsContent\":"+"\"\"}";
            }     
            for (var i = 1; i <= num; i++) {
                 if ( $('#multi_msg_' + i + ' input[name=selectMethod\\[\\]]').val()=="2") {
                    jsonStr += ",{\"Title\": \"" + $('#multi_msg_' + i + ' input[name=Title\\[\\]]').val() + "\", \"ImgUrl\":\" " + $('#multi_msg_' + i + ' input[name=ImgPath\\[\\]]').val() + "\", \"NewsUrl\": \"" + $('#multi_msg_' + i + ' input[name=linkUrl\\[\\]]').val() +"\",\"NewsContent\":"+ "\"\"}";
                }
                if ( $('#multi_msg_' + i + ' input[name=selectMethod\\[\\]]').val()=="1") {
                    jsonStr += ",{\"Title\": \"" + $('#multi_msg_' + i + ' input[name=Title\\[\\]]').val() + "\", \"ImgUrl\":\" " + $('#multi_msg_' + i + ' input[name=ImgPath\\[\\]]').val() + "\", \"NewsUrl\":" +"\"\""+",\"NewsContent\":"+"\"\""+"}";
                }           
                
            }
            jsonStr += "]";

            for (var i = 0; i <= num; i++) {
                if ($('#multi_msg_' + i + ' input[name=selectMethod\\[\\]]').val() == "1") {
                    newsContent[i] =$('#multi_msg_' + i + ' input[name=NewsContent\\[\\]]').val();
                    alert(newsContent[i]);
                }
            }
            $.ajax({
                type: "post",
                url: '../Json/Material/SavePicText.ashx',              
                data: { jsonStr: jsonStr,num:num,newsContent0:newsContent[0],newsContent1:newsContent[1],newsContent2:newsContent[2],newsContent3:newsContent[3],newsContent4:newsContent[4],newsContent5:newsContent[5],newsContent6:newsContent[6],newsContent7:newsContent[7],newsContent8:newsContent[8],newsContent9:newsContent[9]},
                async: false,
                success: function (data) {
                    alert(data);
                },
                error: function (request) {
                    alert("失败");
                }
            });
        });
      $("#selectMethod").change(function() {
          if ($("#selectMethod").val() == '2') {
              $("#divLinkUrl").css("display", "block");
              $("#txtLinkUrl").focus();
              $("#divNewsContent").css("display", "none");
          } if($("#selectMethod").val() == '1') {
              $("#divLinkUrl").css("display", "none");
              $("#divNewsContent").css("display", "block");
        }if ($("#selectMethod").val() == '0') {
              $("#divLinkUrl").css("display", "none");
             $("#divNewsContent").css("display", "none");
         }
             if (cur_id == "") {
                 $('#multi_msg_0 input[name=selectMethod\\[\\]]').val($("#selectMethod").val());
             } else {
                 $(cur_id + ' input[name=selectMethod\\[\\]]').val($("#selectMethod").val());
             }
             
            });
        var material_multi_list_even = function () {            
            $('.multi .first, .multi .list').each(function () {
                var children = $(this).children('.control');
                $(this).mouseover(function () { children.css({ display: 'block' }); });
                $(this).mouseout(function () { children.css({ display: 'none' }); });

                children.children('a[href*=#del]').click(function () {
                    if ($('.multi .list').size() <= 1) {
                        alert('无法删除，多条图文至少需要2条消息！');
                        return false;
                    }
                    if (confirm('删除后不可恢复，继续吗？')) {
                        $(this).parent().parent().remove();
                        $('.multi .first a[href*=#mod]').click();
                        $('.mod_form').css({ top: 37 });
                    }
                });


                children.children('a[href*=#mod]').click(function () {
                    var position = $(this).parent().offset();
                    var material_form_position = $('#material_form').offset();
                    cur_id = '#' + $(this).parent().parent().attr('id');
                    $('.mod_form').css({ top: position.top - material_form_position.top });
                    $('.mod_form input[name=inputTitle]').val($(cur_id + ' input[name=Title\\[\\]]').val());
                    $('.mod_form input[name=linkUrl]').val($(cur_id + ' input[name=linkUrl\\[\\]]').val());
                    editor.html($(cur_id + ' input[name=NewsContent\\[\\]]').val());
                    $("#selectMethod").val($(cur_id + ' input[name=selectMethod\\[\\]]').val());
                    if ($(cur_id + ' input[name=selectMethod\\[\\]]').val() == "2") {
                        $("#divNewsContent").css("display", "none");
                        $("#divLinkUrl").css("display", "block");
                        $("#txtLinkUrl").focus();
                    }
                    if ($(cur_id + ' input[name=selectMethod\\[\\]]').val() == "1") {
                        $("#divNewsContent").css("display", "block");
                        $("#divLinkUrl").css("display", "none");
                    }
                    if ($(cur_id + ' input[name=selectMethod\\[\\]]').val() == "0") {
                        $("#divNewsContent").css("display", "none");
                        $("#divLinkUrl").css("display", "none");
                    }
                    /*$('.mod_form input[name=inputUrl]').val($(cur_id+' input[name=Url\\[\\]]').val());*/
                    $('.mod_form select[name=inputUrl]').find("option[value='" + $(cur_id + ' input[name=Url\\[\\]]').val() + "']").attr("selected", true);
                    $('.big_img_size_tips').html(cur_id == '#multi_msg_0' ? '640*360px' : '300*300px');
                    $('.multi').data('cur_id', cur_id);
                    global_obj.file_upload($('#MsgFileUpload'), $(cur_id + ' input[name=ImgPath\\[\\]]'), $(cur_id + ' .img'));
                });
                $('.mod_form select[name=inputUrl]').find("option[value='" + $('input[name=Url\\[\\]]').val() + "']").attr("selected", true);
            });
        }

        global_obj.file_upload($('#MsgFileUpload'), $('.multi .first input[name=ImgPath\\[\\]]'), $('.first .img'));
        $('.multi').data('cur_id', '#' + $('.multi .first').attr('id'));
        $('.mod_form input').filter('[name=inputTitle]').on('keyup paste blur', function() {
            cur_id = $('.multi').data('cur_id');
            $(cur_id + ' input[name=Title\\[\\]]').val($(this).val());
            $(cur_id + ' .title').html($(this).val());
        });
           $('.mod_form input').filter('[name=linkUrl]').on('keyup paste blur', function() {
            cur_id = $('.multi').data('cur_id');
            $(cur_id + ' input[name=linkUrl\\[\\]]').val($(this).val());
           
        });
        $('.mod_form select').filter('[name=inputUrl]').change(function () {
            cur_id = $('.multi').data('cur_id');
            $(cur_id + ' input[name=Url\\[\\]]').val($(this).val());
        });
 
        material_multi_list_even();
        $('a[href=#add]').click(function () {
            $(this).blur();
            if ($('.multi .list').size() >= 7) {
                alert('你最多只可以加入8条图文消息！');
                return false;
            }
            $('.multi .list, a[href*=#mod], a[href*=#del]').off();
            $('<div class="list" id="multi_msg_' + i + '">' + $('.multi .list:last').html() + '</div>').insertAfter($('.multi .list:last'));
            $('.multi .list:last').children('.info').children('.title').html('标题').siblings('.img').html('缩略图');
            $('.multi .list:last input').filter('[name=Title\\[\\]]').val('').end().filter('[name=Url\\[\\]]').val('').end().filter('[name=ImgPath\\[\\]]').val('').end().filter('[name=linkUrl\\[\\]]').val('').end().filter('[name=selectMethod\\[\\]]').val('0').end().filter('[name=NewsContent\\[\\]]').val('');
            i++;
            material_multi_list_even();
            $('#material').css("height",$('#material').height() + 300);
        });
    },

    url_init: function () {
        $('#add_form').submit(function () {
            if (global_obj.check_form($('*[notnull]'))) { return false };
            $('#add_form input:submit').attr('disabled', true);
            return true;
        });
    },

    attention_init: function () {
        //绑定图文消息
        var bind_news = function() {
            $.ajax({
                type: "post",
                url: "../Json/MyWeiXin/MenuDataList.aspx?type=bindnews",
                contentType: "application/json; charset=UTF-8",
                async: false,
                success: function(m) {
                    if (m != "") {
                        $("#MaterialId").html($("#MaterialId").html() + m);
                    }
                },
            }); 
        };
        bind_news();
        var bindattention = function() {
            $.ajax({
                type: "post",
                url: "../MyWeiXin/Set_attention.aspx?type=bindattention",
                contentType: "application/json; charset=UTF-8",
                async: false,
                success: function(m) {
                    if (m != "") {
                        var arr=new Array();
                        arr= m.split('~');//注split可以用字符或字符串分割
                        $("#ReplyMsgType").val(arr[1]);
                        if (arr[1] == "1") {
                            $("#TextContents").val(arr[2]);
                        } else {
                            $("#MaterialId").val(arr[2]);
                        }
                    }
                },
            });
        };
        bindattention();
        var display_row = function() {
            if ($('select[name=ReplyMsgType]').val() == 1) {
                $('#text_msg_row').show();
                $('#img_msg_row').hide();
            } else {
                $('#text_msg_row').hide();
                $('#img_msg_row').show();
            }
        };

        display_row();
        
        $("#TextContents").manhuaInputLetter({
                len: 600, //限制输入的字符个数				       
                showId: "sid"//显示剩余字符文本标签的ID
        });
        $("#TextContents").focus().blur();
        
        $('select[name=ReplyMsgType]').on('change blur', display_row);
        $('#attention_reply_form input:submit').click(function() {
            if ($('#ReplyMsgType').val() == '1') {
                if (global_obj.check_form($('#TextContents')) || global_obj.check_length($('#TextContents').val(),600)) {
                 alert('文字消息必须为1-600个字');
                 return false; 
                } 
            }
            if ($('#ReplyMsgType').val()== '2') {
                 if($("#MaterialId").val() == '0')
                 {
                    alert('图文消息必须选择一个素材');
                    return false; 
                 }
            } 
            
            $('#attention_reply_form input:submit').attr('disabled', true);

            $.ajax({
                cache: true,
                type: "POST",
                url: '../MyWeiXin/Set_attention.aspx?type=attention', // 提交的URL 
                data: $('#attention_reply_form').serialize(),  //  要提交的表单,必须使用name属性 
                async: false,
                success: function (data) {
                    $('#attention_reply_form input:submit').attr('disabled', false);
                    if (data > 0) {
                        alert("首页关注设置成功！");
                    }
                },
                error: function (request) {
                    alert("Connection error");
                }
            });
        });
        
    },

    reply_keyword_init: function () {
        var display_row = function() {
            if ($('select[name=ReplyMsgType]').val() == 0) {
                $('#text_msg_row').show();
                $('#img_msg_row').hide();
            } else {
                $('#text_msg_row').hide();
                $('#img_msg_row').show();
            }
        };

        display_row();
        $('select[name=ReplyMsgType]').on('change blur', display_row);
        $('#keyword_reply_form').submit(function () { return false; });
        $('#keyword_reply_form input:submit').click(function () {
            if ($('select[name=ReplyMsgType]').val() == 0) {
                if (global_obj.check_form($('*[notnull], textarea[name=TextContents]'))) { return false };
            } else {
                if (global_obj.check_form($('*[notnull]'))) { return false };
            }

            $(this).attr('disabled', true);
            $.post('?', $('form').serialize(), function (data) {
                if (data.status == 1) {
                    window.location = '?m=wechat&a=reply_keyword';
                } else {
                    alert(data.msg);
                    $('#keyword_reply_form input:submit').attr('disabled', false);
                }
            }, 'json');
        });
        
    },

    set_token_init: function () {
        $('#set_token_form').submit(function () { return false; });
        $('#set_token_form input:submit').click(function () {
            if (global_obj.check_form($('input[name="Url"]'))) {return false;} 
            else {
                    if(global_obj.check_url($('input[name="Url"]').val()))
                    {alert('你输入的URL无效'); return false; }
            }
            if (global_obj.check_form($('input[name="Token"]'))) {return false;} 

            var btn_value = $('#set_token_form input:submit').val();
            $('.set_token_msg').css({ display: 'none' }).html('');
            $(this).val('对接中，请耐心等待...').attr('disabled', true);

            $.post('?', $('form').serialize(), function (data) {
                if (data.status == 1) {
                    window.location = '?m=wechat&a=set_token';
                } else {
                    $('.set_token_msg').css({ display: 'block' }).html(data.msg);
                    $('#set_token_form input:submit').val(btn_value).attr('disabled', false);
                }
            }, 'json');
        });
    },

    menu_init: function () {
        //绑定拖动排序事件
        var menu_dragsort = function() {
            $('#wechat_menu .m_lefter dl').dragsort({
                dragSelector: 'dd',
                dragEnd: function ()
                {
                    var sort = $(this).parent().children('dd').map(function ()
                    {
                        return $(this).attr('mid');
                    }).get();
                    $.ajax({
                        cache: false,
                        type: "POST",
                        url: '../Json/MyWeiXin/MenuDataList.aspx?type=sort&order=' + sort, // 提交的URL 
                        async: false,
                        success: function(data) {
                            $("#m_list").html(data); // 输出提交的表表单 
                            $(".iframe_content .r_nav .cur a")[0].click();
                        },
                        error: function(request) {
                            alert("Connection error");
                        }
                    });
                },
                dragSelectorExclude: 'ul, a',
                placeHolderTemplate: '<dd class="placeHolder"></dd>',
                scrollSpeed: 5
            });

            $('#wechat_menu .m_lefter ul').dragsort({
                dragSelector: 'li',
                dragEnd: function ()
                {
                    var sort = $(this).parent().children('li').map(function ()
                    {
                        return $(this).attr('mid');
                    }).get();
                    $.ajax({
                        cache: false,
                        type: "POST",
                        url: '../Json/MyWeiXin/MenuDataList.aspx?type=sort&order=' + sort, // 提交的URL 
                        async: false,
                        success: function(data) {
                            $("#m_list").html(data); // 输出提交的表单 
                            $(".iframe_content .r_nav .cur a")[0].click();
                        },
                        error: function(request) {
                            alert("Connection error");
                        }
                    });
                },
                dragSelectorExclude: 'a',
                placeHolderTemplate: '<li class="placeHolder"></li>',
                scrollSpeed: 5
            });
        };
        
        //获取菜单
        $.ajax({
                cache: true,
                type: "POST",
                url: '../Json/MyWeiXin/MenuDataList.aspx', // 提交的URL 
                async: false,
                success: function (data) {
                    $("#m_list").html(data); // 输出提交的表表单 
                    menu_dragsort();
                }
        });

        $('#wechat_menu .m_lefter ul li').hover(function () {
            $(this).children('.opt').show();
        }, function () {
            $(this).children('.opt').hide();
        });

        var display_row = function () {
            var v = $('#wechat_menu_form select[name=MsgType]').val();
            if (v == 0) {
                $('#img_msg_row, #url_msg_row').hide();
                $('#text_msg_row').show();
            } else if (v == 1) {
                $('#text_msg_row, #url_msg_row').hide();
                $('#img_msg_row').show();
            } else {
                $('#text_msg_row, #img_msg_row').hide();
                $('#url_msg_row').show();
            }
        };

        var clear_form = function() {
            $("input[name='Name']").val("");
            $("#SupMId").val("0");
            $("#dropmenu_type").val("0");
            $("textarea[name='TextContents']").val("");
            $("#MaterialId").val("0");
            $("#Url").val("");
            $("#MId").val("0");
        };
        display_row();

        var InputLetter = function() {
            $("#TextContents").manhuaInputLetter({
                len: 600, //限制输入的字符个数				       
                showId: "sid"//显示剩余字符文本标签的ID
            });
            $("#TextContents").focus().blur();
        };
        InputLetter();
        
        $('#wechat_menu_form select[name=MsgType]').on('change blur', display_row);
        $('#wechat_menu_form').submit(function () { return false; });
        $('#wechat_menu_form input:submit').click(function () {
            if (global_obj.check_form($('*[notnull]'))) {
                return false;
            } else {
                if($('#wechat_menu_form #SupMId').val() == "0")
                {
                    if (global_obj.check_length($('*[notnull]').val(),8)) 
                    { alert('一级菜单名称名字不多于4个汉字或8个字母');return false; }
                }
                else
                {
                    if (global_obj.check_length($('*[notnull]').val(),14)) 
                    { alert('二级菜单名称名字不多于7个汉字或14个字母');return false; }
                }
                
            }
            if ($('#dropmenu_type').val() == "2") {
                if (global_obj.check_form($('input[name="Url"]'))) {
                    return false;
                } else {
                    if(global_obj.check_url($('input[name="Url"]').val()))
                    {alert('你输入的URL无效'); return false; }
                }
            }
            if ($('#dropmenu_type').val() == "0") {
                if (global_obj.check_form($('#TextContents')) || global_obj.check_length($('#TextContents').val(),600)) {
                 alert('文字消息必须为1-600个字');
                 return false; 
                }
            }
            if ($('#dropmenu_type').val() == "1") {
                if ($('#MaterialId').val()== '0') {
                 alert('图文消息必须选择一个素材');
                 return false; 
                }
            }


            $(this).attr('disabled', true);
            $.ajax({
                cache: true,
                type: "POST",
                url: '../Json/MyWeiXin/MenuDataList.aspx?type=ajax', // 提交的URL 
                data: $('#wechat_menu_form').serialize(),  //  要提交的表单,必须使用name属性 
                async: false,
                success: function (data) {
                    if (data == "1no")
                    {
                        alert("一级菜单不能超过3个！");
                    }
                    else if (data == "2no")
                    {
                        alert("二级菜单不能超过5个！");
                    } else
                    {
                        $("#m_list").html(data); // 输出提交的表单 
                        if ($("#MId").val() == "0")
                        {
                            alert("添加菜单成功！");
                        } else
                        {
                            alert("修改菜单成功！");
                        }
                        bind_menu();    //重新绑定菜单项
                        clear_form();  //清空表单
                        display_row();
                        menu_dragsort();  //拖动排序事件
                        InputLetter();
                    }
                    $('#wechat_menu_form input:submit').attr('disabled', false);
                },
                error: function (request) {
                    alert("Connection error");
                }
            });
        });

        $('#wechat_menu .publish .btn_green').click(function () {
            if(!confirm('本次发布将在24小时内对所有用户生效。确认发布？')){return false};
            var btn_value = $(this).val();
            $(this).val('发布中，请耐心等待...').attr('disabled', true);
            /*
            $.get('?do_action=wechat.menu_publish', '', function(data){
            $('#wechat_menu .publish .btn_green').val(btn_value).attr('disabled', false);
            if(data.status==1){
            alert('菜单发布成功，24小时后可看到效果，或取消关注再重新关注可即时看到效果！');
            }else{
            alert(data.msg);
            }
            }, 'json');
            */
            $.ajax({
                cache: true,
                type: "POST",
                url: '../Json/MyWeiXin/MenuDataList.aspx', // 提交的URL 
                data: { type: "token" },
                async: false,
                success: function (data) {
                    $('#wechat_menu .publish .btn_green').val(btn_value).attr('disabled', false);
                    alert('菜单发布成功，24小时后可看到效果，或取消关注再重新关注可即时看到效果！');
                },
                error: function (request) {
                    alert("Connection error");
                }
            });
        });

        //删除菜单代码
        $('#wechat_menu .publish .btn_gray').click(function ()
        {
            if(!confirm('删除后微信公众号的菜单将被删除，继续吗？')){return false};
            var btn_value = $(this).val();
            $(this).val('删除中...').attr('disabled', true);
            var menuid = $("#MId").val();
            $.ajax({
                cache: true,
                type: "POST",
                url: '../Json/MyWeiXin/MenuDataList.aspx?type=deleteMenu&menuid' + menuid, // 提交的URL 
                async: false,
                success: function (data)
                {
                    $('#wechat_menu .publish .btn_gray').val('删除菜单').attr('disabled', false);
                    alert('菜单删除成功，24小时后可看到效果，或取消关注再重新关注可即时看到效果！');
                },
                error: function (request)
                {
                    alert("Connection error");
                }
            });
        });

        $('#wechat_menu .no_ext a[title=删除]').live("click", function () {
            if(!confirm('删除后不可恢复，继续吗？')){return false};
            var id = $(this).parent().parent().attr("mid");
            $.ajax({
                cache: true,
                type: "POST",
                url: '../Json/MyWeiXin/MenuDataList.aspx?type=delete&menuid=' + id, // 提交的URL 
                async: false,
                success: function (data) {
                    $("#m_list").html(data); // 输出提交的表表单 
                    bind_menu();    //重新绑定菜单项
                    menu_dragsort();  //拖动排序事件
                }
            });
        });
        
        $('#wechat_menu .no_ext a[title=修改]').live("click", function () {
            var id = $(this).parent().parent().attr("mid");
            $.ajax({
                cache: true,
                type: "POST",
                url: '../Json/MyWeiXin/MenuDataList.aspx?type=edit&menuid=' + id, // 提交的URL 
                async: false,
                dataType: 'json', 
                success: function (data) {
                    $("#MId").val(data.ID);
                    $("input[name='Name']").val(data.menu_name);
                    $("#SupMId").val(data.menu_lever);
                    $("#dropmenu_type").val(data.menu_type);
                    $("#Url").val(data.menu_view);
                    $("#MPlace").val(data.menu_place);
                    if (data.menu_type == 0) {
                        $("textarea[name='TextContents']").val(data.menu_view);
                    }
                    if (data.menu_type == 1) {
                        $("#MaterialId").val(data.menu_value);
                    }
                    display_row();
                    InputLetter();
                }
            });
        });

        //绑定菜单项
        var bind_menu = function() {
            $.ajax({
                type: "post",
                url: "../Json/MyWeiXin/MenuDataList.aspx?type=bindmenu",
                contentType: "application/json; charset=UTF-8",
                async: false,
                success: function (m) {
                    if (m != "") {
                        $("#SupMId").html(m);
                    }
                },
                error: function (request) {
                    alert(request);
                }
            });
        };
        bind_menu();
        
        
        //绑定图文消息
        var bind_news = function() {
            $.ajax({
                type: "post",
                url: "../Json/MyWeiXin/MenuDataList.aspx?type=bindnews",
                contentType: "application/json; charset=UTF-8",
                async: false,
                success: function(m) {
                    if (m != "") {
                        $("#MaterialId").html($("#MaterialId").html() + m);
                    }
                },
            });
        };
        bind_news();
        
    },

    auth_init: function () {
        $('#wechat_auth_form').submit(function () { return false; });
        $('#wechat_auth_form input:submit').click(function () {
            if (global_obj.check_form($('*[notnull]'))) { return false };

            $(this).attr('disabled', true);
            $.post('?', $('#wechat_auth_form').serialize(), function (data) {
                if (data.status == 1) {
                    window.location = '?m=wechat&a=auth';
                } else {
                    alert('设置失败，出现未知错误！');
                }
            }, 'json');
        });
    },

    spread_init: function () {
        var spread_type = function () {
            if ($('#spread_form input[name=SpreadType]:checked').val() == 0) {
                $('#spread_form .pcas').show();
                $('#spread_form .url').hide();
            } else {
                $('#spread_form .pcas').hide();
                $('#spread_form .url').show();
            }
        }
        $('#spread_form input[name=SpreadType]').click(function () {
            spread_type();
        });
        spread_type();

        $('#spread_form').submit(function () { return false; });
        $('#spread_form input:submit').click(function () {
            if (global_obj.check_form($('*[notnull]'))) { return false };

            $(this).attr('disabled', true);
            $.post('?', $('#spread_form').serialize(), function (data) {
                if (data.ret == 1) {
                    window.location = '?m=wechat&a=spread';
                } else {
                    alert('设置失败，出现未知错误！');
                }
            }, 'json');
        });
    }
}
