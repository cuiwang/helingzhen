var shouhui = { stopAnimate: "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", websocketUrl: "", sessionID: 0, companyID: 0, hID: 0, userID: 0, photoUrl: "", isTest: 2, rand: 0, recheck: 0 },
    flag = !0,
    canvasWidth = $("#canvas").width(),
    canvasHeight = $("#canvas").height(),
    strokeColor = "rgb(277,0,82)",
    lineWidth = 10,
    isMouseDown = !1,
    lastLoc = { x: 0, y: 0 },
    curLoc = { x: 0, y: 0 },
    imgArr = [],
    isErase = !1,
    canvas = document.getElementById("canvas"),
    context = canvas.getContext("2d");
	canvas.width = canvasWidth, canvas.height = canvasHeight, 
	shouhui.initEvent = function() {
    function h(a) {
        var b = document.createElement("canvas");
        return b.width = a.width, b.height = a.height, a.toDataURL() == b.toDataURL() }

    function i(a) { isMouseDown = !0, lastLoc = m(a.x, a.y), context.save(), context.beginPath(), context.moveTo(lastLoc.x, lastLoc.y) }

    function j() { isMouseDown = !1;
        var a = canvas.toDataURL();
        imgArr.push(a) }

    function k(a) { curLoc = m(a.x, a.y), isErase ? (context.globalCompositeOperation = "destination-out", context.lineTo(curLoc.x, curLoc.y), context.strokeStyle = "rgba(255,255,255,1)", context.lineWidth = 100, context.stroke()) : (context.globalCompositeOperation = "source-over", context.lineTo(curLoc.x, curLoc.y), context.strokeStyle = "rgba(" + f[g] + ",.9)", context.lineWidth = lineWidth, "20" == context.lineWidth ? (context.strokeStyle = "rgba(" + f[g] + ",.1)", context.miterLimit = 5) : "2" == context.lineWidth && (context.miterLimit = 10, context.lineCap = "round"), context.stroke()), lastLoc = curLoc }

    function m(a, b) {
        var c = canvas.getBoundingClientRect();
        return { x: Math.round(a - c.left), y: Math.round(b - c.top) } }
    var a, b, c, d, e, f, g;
    $(".cancel").on("click", function() {
        location.href=shouhui.indexUrl;
    }), 
    $("#clear_btn").click(function() { 
    	context.clearRect(0, 0, canvasWidth, canvasHeight), imgArr = [] 
    }), 
    a = 1, b = 1, c = 1, f = ["227, 0, 82", "50, 177, 108", "241, 151, 21", "0, 184, 236", "95, 84, 158", "182, 169, 0", "0, 87, 82", "3, 0, 74", "130, 78, 0", "54, 46, 43"], g = 0, 
    $(".pLi .pn").on("click", function() { 
    	a = $(".pLi .pn").index(this), 
    	$(".pLi .pn").removeClass("panChecked"), 
    	$(".pLi .pn").removeClass("panDelChecked"), 
    	a == c || (
    		b = c, c = a, 
    		$(".pLi .pn:eq(" + b + ")").addClass("panDelChecked"), 
    		$(".pLi .pn:eq(" + b + ")").removeClass("active"), 
    		$(this).addClass("panChecked"), 
    		$(this).addClass("active")
		), 
		lineWidth = $(this).attr("data-width"), 
		isErase = "3" == a ? !0 : !1 
    }), 
    $("#js_colorBar").on("click", function() { 
    	isErase = !1, 3 == a && (
    		$(".pLi .pn:eq(" + a + ")").removeClass("active"), 
    		$(".pLi .pn:eq(" + b + ")").addClass("active"), 
    		a = b, c = b
		), 
		d = $(".pLi .pn:eq(" + a + ")").offset().left, 
		e = $(".pLi .pn:eq(" + a + ")").offset().top - ($(window).height() - 210), 
		$(".cLi").removeClass("hidden"), 
		$(".cLi .pn").css({ left: d, top: e }), 
		$(".cLi .pn").animate({ left: 65, top: 30 }, 300, "linear"), 
		$(".pLi").animate({ top: 210 }, 300, "linear"), 
		$(".pLi .pn:eq(" + a + ")").css("opacity", 0), 
		$(".cLi .cLs li").removeClass("colorOut"), 
		$(".cLi .cLs li").addClass("colorIn"), 
		$(".cLi .pn").css("background", "url(" + shouhui.photoUrl + "pan" + a + ".png)"), 
		lineWidth = $(".pLi .pn:eq(" + a + ")").attr("data-width") 
	}), 
	$(".cLi .pn").on("click", function() { 
		$(".cLi .cLs li").removeClass("colorIn"), 
		$(".cLi .cLs li").addClass("colorOut"), 
		$(".cLi .pn").animate({ left: d, top: e }, 300, "linear"), 
		$(".pLi").animate({ top: 0 }, 300, "linear"), 
		setTimeout(function() { 
			$(".cLi ").addClass("hidden"), 
			$(".pLi .pn:eq(" + a + ")").css("opacity", 1) 
		}, 300) 
	}), 
	$(".cLi li").on("click", function() { 
		$(".cLi li").removeClass("active"), 
		$(this).addClass("active"), 
		g = $(".cLi li").index(this) 
	}), 
	$("#img_btn").on("click", function() {
        if (h(canvas)) return $("#btn-success").html("您还没有留下大名呢~"), 
        $("#pro-img").attr("src", shouhui.photoUrl + "icon-warn.png"), 
        $(".pop-box").fadeIn("slow"), 
        setTimeout(function() { 
        	$(".pop-box").fadeOut("slow") 
        }, 1e3), void 0;
        var a = canvas.toDataURL("image/png"),
            b = new Image;
        b.src = a, 
        context.clearRect(0, 0, canvasWidth, canvasHeight), 
        imgArr = [], 
        shouhui.fire("", { url: a}) 
    }), 
    context.lineCap = "round", 
    context.lineJoin = "round", 
    canvas.onmousedown = function(a) { 
    	a.preventDefault(), 
    	i({ x: a.clientX, y: a.clientY }) 
    }, 
    canvas.onmouseup = function(a) { 
    	a.preventDefault(), j() 
    }, 
    canvas.onmouseout = function(a) { 
    	a.preventDefault(), j() 
    }, 
    canvas.onmousemove = function(a) { 
    	a.preventDefault(), isMouseDown && k({ x: a.clientX, y: a.clientY }) 
    }, 
    canvas.addEventListener("touchstart", function(a) { 
    	a.preventDefault(), touch = a.touches[0], i({ x: touch.pageX, y: touch.pageY }) 
    }), 
    canvas.addEventListener("touchmove", function(a) { 
    	a.preventDefault(), isMouseDown && (touch = a.touches[0], k({ x: touch.pageX, y: touch.pageY })) 
    }), 
    canvas.addEventListener("touchend", function(a) { 
    	a.preventDefault(), j() 
    }) }, 
    shouhui.fire = function(a, b) {
    // console.log(b.url)
    $.post(shouhui.ajaxUrl,{ url:1, params: b.url }, function(idata) {
        $("#pro-img").attr("src", shouhui.photoUrl + "icon-warn.png"),$(".pop-box").fadeIn("slow"),setTimeout(function() { 
            location.href=shouhui.indexUrl;
        }, 1e3);
    	// console.log(idata);
    },"json");

	};
