var pageConfigWidth = 750;
var pageMaxWidth = 800;
var _w, _zoom, _hd, _orientationChange, _doc = document,
    __style = _doc.getElementById("_zoom");
__style || (_hd = _doc.getElementsByTagName("head")[0], __style = _doc.createElement("style"), _hd.appendCHild(_style)), _orientationChange = function() { _w = _doc.documentElement.clientWidth || _doc.body.clientWidth, _w = _w > pageMaxWidth ? pageMaxWidth : _w, _zoom = _w / pageConfigWidth, __style.innerHTML = ".zoom {zoom:" + _zoom + ";-webkit-text-size-adjust:auto!important;}" }, _orientationChange(), window.addEventListener("resize", _orientationChange, !1);
// loadedPercent(20);

function loadedPercent(a) {
    if (parseInt(document.getElementById("J_precent").innerHTML) < a) { document.getElementById("J_precent").innerHTML = a + "%" }
    if (a == 100) { document.getElementById("J_loading").style.display = "none" } }

function finishLoading() { 
	// $("#t_loading").hide();
    document.body.scrollTop = 0;
    setTimeout(function() { $("#container [isusing=yes]").css("opacity", "0").show().animate({ opacity: 1 }, 30, "ease-in-out") }, 0);
    // loadedPercent(100) 
}
$(function() { finishLoading() });

function showPage() { 
	// $("#loading").hide();
    // $(".cover").hide() 
};




