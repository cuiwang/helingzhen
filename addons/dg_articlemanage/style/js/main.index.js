$(function () {
    $(document).wipetouch(
    {
        // settings here
        preventDefault: false,
        wipeLeft: function (result) { if (result.x > 80) menuListShow(); },
        wipeRight: function (result) { if (result.x > 80) menuListHide(); }
    });

    $("#side_menu_button").bind("click", function () {
        if ($(".side-right-menu").css("right") == "-120px") {
            menuListShow();
        } else {
            menuListHide();
        }
    });

    function menuListShow() {
        $("body").stop().animate({ marginLeft: "-120px" }, "fast");
        $(".footer-toolbar").stop().animate({ left: "-120px" }, "fast");
        $("#nav-menu").stop().animate({ left: "-120px" }, "fast");
        $(".side-right-menu").stop().animate({ right: "0px" }, "fast");
    }

    function menuListHide() {
        $("body").stop().animate({ marginLeft: "0px" }, "fast");
        $(".footer-toolbar").stop().animate({ left: "0px" }, "fast");
        $("#nav-menu").stop().animate({ left: "0px" }, "fast");
        $(".side-right-menu").stop().animate({ right: "-120px" }, "fast");
    }


});
