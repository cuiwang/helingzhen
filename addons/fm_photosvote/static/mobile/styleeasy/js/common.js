//font-size
resetfont();
//loadBox
$('body').bind("touchmove",function(event){event.preventDefault();});

//dom 加载完毕
$(window).load(function(){
    $('.loadBox').remove();
    $('body').unbind('touchmove');
})
//font-size resize
function resetfont(){
    function fontSize(){
        size=$(document.body).width()/320*10;
        $('html').css("font-size",size+'px');
    }
    fontSize();
    $(window).resize(function() {
        fontSize();
    });
}

//back to top
function gotoTop(acceleration,stime){
    acceleration=acceleration||2;stime=stime||2;var x1=0;var y1=0;var x2=0;var y2=0;var x3=0;var y3=0;if(document.documentElement){x1=document.documentElement.scrollLeft||0;y1=document.documentElement.scrollTop||0;}
    if(document.body){x2=document.body.scrollLeft||0;y2=document.body.scrollTop||0;}
    var x3=window.scrollX||0;var y3=window.scrollY||0;var x=Math.max(x1,Math.max(x2,x3));var y=Math.max(y1,Math.max(y2,y3));var speeding=1+ acceleration;window.scrollTo(Math.floor(x/speeding),Math.floor(y/speeding));if(x>0||y>0){var run="gotoTop("+ acceleration+", "+ stime+")";window.setTimeout(run,stime);}
}
$(window).scroll(function(){
    var A=$(".backtop");
    if($(window).scrollTop()>200){A.show()}
    else{A.hide()}
});

 function getQueryString(name) {
     var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
     var r = window.location.search.substr(1).match(reg);
     if (r != null) return decodeURI(r[2]);//unescape(r[2]);
     return null;
 };