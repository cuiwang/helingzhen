define('mmd.community.topic',
function(require, exports, module) {
    var $ = require('zepto'),
    URL = require('url'),
    Xss = require('xss'),
    mCommonlist = require('commonlistv2'),
    cookie = require('cookie'),
    mError = require('module.error');
    require('zepto.mpopup.v2');
    require('zepto.lazyload');
    var $mask = $("#mask"),
    $shareLayer = $("#shareLayer"),
    $postBar = $(".post_bar"),
    nUin = $postBar.attr("data-id");
    var signPop = $.mpopup({
        type: 'info',
        autoClose: 2000,
        mask: false,
        icoType: ''
    });
    $.ajax({
        type: 'GET',
        url: '/mlogin/is_login',
        data: {},
        dataType: 'json',
        success: function(data) {
            if (data.errcode == 0) {
                window.isLogin = true;
            } else {
                window.isLogin = false;
                window.retUrl = data.data.retUrl;
            }
        }
    });
    function asynTopicData() {
        var topicid = URL.getUrlParam('topicid');
        var oCommonList = mCommonlist.init({
            domId: 'asynItemListTopic',
            reqUrl: 'http://w.midea.com/community/index/get_section_list_2',
            params: {
                'topicid': topicid
            },
            pageno: 1,
            startPageno: 1,
            itemTag: 'div',
            pagesize: 10,
            emptyMsg: '没有更多的帖子！',
            usePullRefresh: true,
            success: function(obj, callback) {
                callback(obj.data, obj.total);
                littleImg();
                lazyLoad();
                commentShow($('#asynItemListTopic'));
            }
        });
        if (/(iPhone|iPad|iPod)/i.test(navigator.userAgent)) {
            window.onpageshow = function(event) {
                if (event.persisted) {
                    $("#asynItemListTopic").empty();
                    oCommonList.reset();
                }
            }
        }
    }
    function clickInit() {
        $(".post_bar").on("click", ".like_btn",
        function() {
            var operationtype;
            var $this = $(this),
            $postItem = $(this).parents(".post_item");
            if ($this.attr("data-click") == 1) {
                return;
            } else {
                $this.attr("data-click", 1);
            }
            if ($(this).hasClass("like_active")) {
                operationtype = 2;
            } else {
                operationtype = 1;
            }
            var sectionId = $postItem.attr("data-sectionId");
            var topicId = $postItem.attr("data-topicId");
            var $likeNum = $this.find(".num");
            var $likeBar = $postItem.find(".like_bar");
            var $commentBar = $postItem.find(".comment_bar");
            $.ajax({
                type: 'GET',
                url: 'http://w.midea.com/community/index/operate_user_like',
                data: {
                    "sectionid": sectionId,
                    "topicid": topicId,
                    "operatetype": operationtype
                },
                dataType: 'json',
                success: function(data) {
                    $this.attr("data-click", 0);
                    if (data.errcode == 0) {
                        if (operationtype == 2) {
                            if ($likeNum.html() == 1) {
                                $likeNum.html("赞");
                                $commentBar.attr("data-like", 0);
                            } else {
                                $commentBar.attr("data-like", parseInt($commentBar.attr("data-like") - 1));
                                $likeNum.html(parseInt($likeNum.html()) - 1);
                            }
                            $this.removeClass("like_active");
                        } else {
                            if (isNaN($likeNum.html())) {
                                $likeNum.html(1);
                                $commentBar.attr("data-like", 1);
                            } else {
                                $likeNum.html(parseInt($likeNum.html()) + 1, 10);
                                $commentBar.attr("data-like", parseInt($commentBar.attr("data-like") + 1));
                            }
                            $this.addClass("like_active");
                        }
                        commentShow($postItem);
                        $.ajax({
                            type: 'GET',
                            url: 'http://w.midea.com/community/index/get_like_for_list',
                            data: {
                                "sectionid": sectionId
                            },
                            dataType: 'json',
                            success: function(data) {
                                if (data.errcode == 0) {
                                    $likeBar.html(data.likelist);
                                } else {
                                    mError.checkCode(data);
                                }
                            },
                            error: function() {}
                        });
                    } else {
                        mError.checkCode(data);
                    }
                },
                error: function() {
                    $this.attr("data-click", 0);
                    signPop.setContent('网络错误').show();
                }
            });
        });
        $('#title').prop("onclick", '');
        $('#title').click(function() {
            $("body").scrollTop(0);
        });
    }
    function littleImg() {
        var $imgBar = $(".img_resizing");
        $imgBar.each(function(index) {
            var $img = $(this).find(".img_single"),
            $this = $(this);
            if ($img[0]) {
                $img[0].onload = function() {
                    if ($img[0].width > $img[0].height) {
                        $img.css({
                            "width": $this.width(),
                            "height": "auto"
                        });
                    }
                }
            } else {
                var $imgMulti = $this.find(".img_multi");
                $imgMulti.each(function(index) {
                    var _this = $(this);
                    _this[0].onload = function() {
                        if (_this[0].width < _this[0].height) {
                            _this.css({
                                "width": "100%",
                                "height": "auto"
                            });
                        } else {
                            _this.css({
                                "width": "auto",
                                "height": "100%"
                            });
                        }
                    }
                });
            }
            $this.removeClass("img_resizing");
        });
    }
    function lazyLoad() {
        $('.img_loading').lazyload({
            effect: "fadeIn",
            data_attribute: 'img',
            threshold: 240,
            appear: function(obj) {
                $(this).removeClass("img_loading");
            }
        });
    }
    function commentCommit() {
        $postBar.on("click", ".comment_btn",
        function() {
            if (isLogin) {
                var $postItem = $(this).parents(".post_item");
                $postItem.find(".comment_bar").show(500);
                $postItem.find(".comment_edit").show(500).find(".edit_text").attr("placeholder", "我来说一句").attr("data-to", 0)[0].focus();
            } else {
                window.location.href = retUrl + encodeURIComponent(window.location.href);
            }
        });
        $postBar.on("click", ".comment_item",
        function(e) {
            if (e.target.nodeName != 'A') {
                var $this = $(this);
                if (isLogin) {
                    if ($this.hasClass("more_comment")) {
                        return;
                    }
                    var idForm = $this.attr("data-from"),
                    nameFrom = $this.attr("data-user");
                    if (idForm != nUin) {
                        $this.parents(".post_item").find(".comment_edit").show(500).find(".edit_text").attr("placeholder", "回复" + nameFrom).attr("data-to", idForm).attr("data-to-name", nameFrom)[0].focus();
                    }
                } else {
                    window.location.href = retUrl + encodeURIComponent(window.location.href);
                }
            }
        });
        $postBar.on("click", ".cancel",
        function() {
            $(this).parents(".comment_edit").hide().find(".edit_text").val('').attr("placeholder", "").parent().find(".left_num").html(200).removeClass("left_warn");
            commentShow($(this).parents(".post_item"));
        });
        $postBar.on("input", ".edit_text",
        function() {
            var textLength = $(this).val().length,
            $num = $(this).parent().find(".left_num");
            if (textLength > 200) {
                $num.addClass("left_warn");
            } else {
                $num.removeClass("left_warn");
            }
            $num.html(200 - textLength);
        });
        $postBar.on("focus", ".edit_text",
        function() {
            $("body").css("padding-bottom", "230px");
            $("#headWrap").hide(0);
            $(".container").css("marginTop", 0);
            var top = $(this).offset().top;
            $("body").scrollTop(top - 100);
            $(".edit_bar").hide();
        });
        $postBar.on("blur", ".edit_text",
        function() {
            $(".edit_bar").show();
            $("#headWrap").show();
            $(".container").css("marginTop", "44px");
            $("body").css("padding-bottom", 0);
        });
        $postBar.on("click", ".commit",
        function() {
            var $text = $(this).parents(".comment_edit").find(".edit_text"),
            text = $.trim($text.val()),
            $postItem = $(this).parents(".post_item"),
            sectionId = $postItem.attr("data-sectionId"),
            topicId = $postItem.attr("data-topicid"),
            $commentBox = $postItem.find(".comment_box"),
            commentNum = parseInt($commentBox.attr("data-count")),
            toId = $text.attr("data-to"),
            username = $postBar.attr("data-name"),
            $commentBar = $postItem.find(".comment_bar"),
            $moreBtn = $commentBox.find(".more_comment");
            if (text.length > 200) {
                signPop.setContent("评论字数不能超过200").show();
                return false;
            }
            if ($.trim(text) == "") {
                signPop.setContent("评论不能为空").show();
                return false;
            }
            $.ajax({
                type: 'GET',
                url: 'http://w.midea.com/community/index/comment_section',
                data: {
                    "sectionid": sectionId,
                    "topicid": topicId,
                    "content": text,
                    "toid": toId
                },
                dataType: 'json',
                success: function(data) {
                    if (data.errcode == 0) {
                        signPop.setContent("评论成功").show();
                        $text.parent().hide();
                        $text.val("");
                        $commentBar.attr("data-comment", commentNum + 1) $commentBox.attr("data-count", commentNum + 1).show();
                        if (commentNum < 5) {
                            var $model = $("#commentModel").find(".comment_item");
                            text = text.replace(/\r\n/g, "<br>");
                            text = text.replace(/\r/g, "<br>");
                            text = text.replace(/\n/g, "<br>");
                            if (toId == 0) {
                                var $comment = $model.eq(0).clone();
                                $comment.find(".name").html(Xss.parse(username, 'html') + '：').attr("href", "http://w.midea.com/community/index/homepage?queryuin=" + nUin);
                                $comment.find(".comment_content").html(Xss.parse(text, 'html'));
                                $comment.attr("data-to", 0).attr("data-from", nUin);
                            } else {
                                var nameTo = $text.attr("data-to-name");
                                var $comment = $model.eq(1).clone();
                                $comment.find(".name").eq(0).html(Xss.parse(username, 'html')).attr("href", "http://w.midea.com/community/index/homepage?queryuin=" + nUin);
                                $comment.find(".name").eq(1).html(Xss.parse(nameTo, 'html') + '：').attr("href", "http://w.midea.com/community/index/homepage?queryuin=" + toId);
                                $comment.find(".comment_content").html(Xss.parse(text, 'html'));
                                $comment.attr("data-to", toId).attr("data-from", nUin);
                            }
                            $moreBtn.before($comment);
                        } else if (commentNum == 5) {
                            $moreBtn.show();
                        }
                    } else {
                        mError.checkCode(data);
                    }
                },
                error: function() {
                    signPop.setContent('网络错误').show();
                }
            });
        });
    }
    function commentShow(selector) {
        var $commentBar = selector.find(".comment_bar");
        $commentBar.each(function() {
            var $likeBar = $(this).find(".like_bar"),
            $comment = $(this).find(".comment_box");
            if ($(this).attr("data-like") > 0) {
                $likeBar.css("display", "block");
            } else {
                $likeBar.css("display", "none");
            }
            if ($(this).attr("data-comment") > 0) {
                $comment.css("display", "block");
            } else {
                $comment.css("display", "none");
            }
            if ($(this).attr("data-like") == 0 && $(this).attr("data-comment") == 0) {
                $(this).css("display", "none");
            } else {
                $(this).css("display", "block");
            }
        })
    }
    exports.init = function() {
        clickInit();
        asynTopicData();
        littleImg();
        lazyLoad();
        commentShow($('.post_bar'));
        commentCommit();
        window._md_share_config = {
            link: window.location.href,
            imgUrl: 'http://st.midea.com/h5/img/community/st-168.png',
            desc: $("#topicDesc").html(),
            title: document.title
        };
        window._md_share_friend_config = {
            link: window.location.href,
            imgUrl: 'http://st.midea.com/h5/img/community/st-168.png',
            desc: $("#topicDesc").html(),
            title: document.title
        };
    }
});