
// 绑定事件
function addEvent( elm, evType, fn, useCapture ) {
    if ( elm.addEventListener ) {
        elm.addEventListener( evType, fn, useCapture );//DOM2.0
    }
    else if ( elm.attachEvent ) {
        elm.attachEvent( 'on' + evType, fn );//IE5+
    }
    else {
        elm['on' + evType] = fn;//DOM 0
    }
}

addEvent(window, 'load', pageInit, false);

function isWeixin(){
	var ua = navigator.userAgent.toLowerCase();
	if(ua.match(/MicroMessenger/i)=="micromessenger") {
		return true;
	} else {
		return false;
	}
}

function isIos() {
	return navigator.userAgent.match(/iphone|ipod|ios|ipad/i);
}

function pageInit() {
	checkMobile();
}

function checkMobile() {
    if(isMobile()) {
        displayType = "none";
        var mysheet=document.styleSheets[0];
        var myrules= mysheet.cssRules ? mysheet.cssRules: mysheet.rules;
        for (i=0; i<myrules.length; i++){
            if(myrules[i].selectorText ==".hideMobile"){
                myrules[i].style["display"] = displayType;
                break;
            }
        }
        document.getElementById('jiathisDiv').style.cssText = "font: 20px; width: 212px; margin: 10px auto;";
    }
}

function isMobile(){
	return navigator.userAgent.match(/android|iphone|ipod|blackberry|meego|symbianos|windowsphone|ucbrowser/i);
}

function setShareInfo(data) {
    var _t = Number(data.showShareTime);
    if ( _t != 'NaN' && _t == 0 ) {
        show_share_page();
    }
    else if ( _t != 'NaN' && _t > 0 ) {
        setTimeout(function(){ show_share_page(data.title, data.desc); }, _t);
    }
}

// 接受sdk回调
function onmessage(e) {
    var _fns = {
        'setShareInfo' : function(args) { setShareInfo(args); }
    };
    switch( e.data.op_type ) {
    case 'fn':
        (_fns[e.data.value.fn]).apply(window, e.data.value.args);
        break;
    default:
        console.log(e);
    }
}

addEvent( window, 'message', onmessage, false);
/*
function show_share_page(title, desc ) {
    SHARE_DESC=desc;
	addWXQR(title,desc);
}
*/