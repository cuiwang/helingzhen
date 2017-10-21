$(function() {
    var $body = $("body"),
    $root = $(".root"),
    $maskTr = $(".mask-transparent"),
    $maskBl = $(".mask-black"),
    $btnMenu = $("#J_BtnMenu"),
    $menu = $("#J_Menu");
    function getBrowser() {
        if (isFirefox = navigator.userAgent.indexOf("Firefox") > 0) {
            $body.addClass("moz")
        }
        if (isSafari = navigator.userAgent.indexOf("Safari") > 0) {
            $body.addClass("webkit")
        }
    }
    getBrowser();
    function stopRolling(event) {
        event.preventDefault()
    }
    function showMaskB() {
        $maskBl.removeClass("none");
        $root.addClass("blur")
    }
    function hideMaskB($this) {
        $root.removeClass("blur");
        $tabConMod.addClass("none")
    }
    $(".logo-single").on("click",
    function() {
        history.go( - 1)
    });
    var timer_mask = null;
    function showMenu() {
        $menu.addClass("show");
        $maskTr.removeClass("none");
        clearTimeout(timer_mask)
    }
    function hideMenu() {
        $menu.removeClass("show");
        timer_mask = setTimeout(function() {
            $maskTr.addClass("none")
        },
        150)
    }
    $btnMenu.on("click",
    function() {
        showMenu()
    });
    $maskTr.on({
        click: function() {
            hideMenu()
        },
        touchstart: function() {
            hideMenu()
        }
    });
    var $tabTitItem = $("#J_TabTit").find(".item"),
    $tabCon = $("#J_TabCon"),
    $tabConMod = $tabCon.find(".mod"),
    $tabConItem = $tabConMod.find(".item");
    $tabTitItem.on("click",
    function() {
        var index = $(this).index();
        $tabConMod.eq(index).removeClass("none").siblings().addClass("none");
        showMaskB()
    });
    $tabConItem.on("click",
    function() {
        $(this).addClass("cur").siblings().removeClass("cur")
    });
    $maskBl.on("click",
    function() {
        $(this).addClass("none");
        hideMaskB()
    });
    var $opt = $(".opt"),
    $zan = $(".zan"),
    $disabledZan = $opt.find(".disabled"),
    $btnZan = $("#J_BtnZan");
    $zan.on("click",
    function() {
        var cmtid = $(this).attr("cmtid");
        var the = this;
        $.get("index.php", {
            c: "huodong",
            a: "zan",
            cmtid: cmtid
        },
        function(r) {
            if (r == "OK") {
                $(the).addClass("disabled").find(".ad").addClass("on");
                var num = parseInt($(the).find("em").html()) + 1;
                $(the).find("em").html(num);
                $(the).off("click")
            } else {
                alert(r);
                return false
            }
        })
    });
    $disabledZan.off("click");
    $btnZan.on("click",
    function() {
        var hid = $(this).attr("hid");
        $.get("index.php", {
            c: "huodong",
            a: "zan",
            hid: hid
        },
        function(r) {
            if (r == "OK") {
                $btnZan.addClass("disabled").find(".ad").addClass("on");
                var num = parseInt($btnZan.find("em").html()) + 1;
                $btnZan.find("em").html(num);
                $btnZan.off("click")
            } else {
                alert(r);
                return false
            }
        })
    });
	/**/
    var $joinBar = $("#J_JoinBar"),
    $btnJoin = $joinBar.find(".btn-join"),
    $btnJoinText = $btnJoin.find("span");
    $commentForm = $("#J_commentForm");
    /*
	$(window).on("scroll",
    function() {
        var d = $joinBar.offset().top,
        h = $commentForm.offset().top - 100;
        if (d > h) {
            $btnJoin.animate({
                width: "44px"
            },
            200);
            $btnJoinText.animate({
                opacity: 0
            },
            150)
        } else {
            $btnJoin.animate({
                width: "86px"
            },
            200);
            $btnJoinText.animate({
                opacity: 1
            },
            150)
        }
    });
	
    var clearAd = setTimeout(function() {
        $btnJoin.removeClass("rubberBand")
    },
    3500);
    var setAd = setTimeout(function() {
        clearAd = null;
        $btnJoin.addClass("rubberBand")
    },
    7000);*/
    var $baoming = $(".baoming"),
    $btnShare = $(".btn-share");
    $btnShare.on("click",
    function() {
        $joinBar.addClass("none");
        showMaskB()
    });
    $maskBl.click(function() {
        $joinBar.removeClass("none")
    });
    var $listRadio = $("#J_ListRadio"),
    $listRadioUl = $listRadio.find("ul"),
    $publishItem = $listRadio.find(".item"),
    $listRadioShow = $listRadio.find(".btn-show");
    $listRadioShow.on("click",
    function() {
        if (!$(this).hasClass("btn-hide")) {
            $listRadioUl.css("display", "block");
            $listRadio.css("height", "auto");
            $(this).addClass("btn-hide").removeClass("btn-show")
        } else {
            $listRadioUl.css("display", "none");
            $listRadio.css("height", "42px");
            $(this).addClass("btn-show").removeClass("btn-hide")
        }
    });
    $publishItem.on("click",
    function() {
        if (!$(this).hasClass("cur")) {
            var t = $(this).attr("t");
            if (t == "qq" || t == "email") {
                $(this).parent().append('<input type="hidden" name="fperm[]" value="' + t + '" />')
            } else {
                $(this).parent().append('<input type="hidden" name="adddiy[]" value="' + t + '" />')
            }
            $(this).addClass("cur")
        } else {
            $(this).parent().find("input").remove();
            $(this).removeClass("cur")
        }
    });
    $("#J_FormSelect").on("change",
    function() {
        var optText = $("#J_FormSelect option").not(function() {
            return ! this.selected
        }).text();
        $("#J_FormSelectLabel").text(optText).addClass("selected");
        $(this).blur()
    })
});