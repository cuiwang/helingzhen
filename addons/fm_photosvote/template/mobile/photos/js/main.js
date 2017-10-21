//分享
function button2(){
$("#mcover").css("display","block")  // 分享给好友圈按钮触动函数
$('.lapiao em').removeClass('r').addClass('change_color')
}

function weChat(){

$("#mcover").css("display","none");  // 点击弹出层，弹出层消失
$('.lapiao em').removeClass('change_color').addClass('r')
}
//搜索
function button3(){

$(".cover").css("display","block") 
$(".search").css("display","block") 
}

function weChat1(){

$(".cover").css("display","none");  // 点击弹出层，弹出层消失
$(".search").css("display","none") 

}
$('.search').click(function(){
	return false;	
})

/**
$('.butn').live('click',function(){
   if ($(this).find("i").hasClass("hide")) {
                $(this).find("i").removeClass("hide")
  				$('.caidan').slideDown()
            } else {
                $(this).find("i").addClass("hide")
                $('.caidan').slideUp()
			}
});
$('.caidan i').live('click',function(){
	//alert('ok')
  $('.txte').val($(this).html()).css('color','#333')
});


var nav4 =(function(){
  bindClick = function(els, mask){
    if(!els || !els.length){return;}
    var isMobile = "ontouchstart" in window;
    for(var i=0,ci; ci = els[i]; i++){
      ci.addEventListener("click", evtFn, false);
    }

    function evtFn(evt, ci){
      ci =this;
      for(var j=0,cj; cj = els[j]; j++){
        if(cj != ci){
          console.log(cj);
          cj.classList.remove("on");
        }
      }
      if(ci == mask){mask.classList.remove("on");return;}
      switch(evt.type){
        case "click":
          var on = ci.classList.toggle("on");
          mask.classList[on?"add":"remove"]("on");
        break;
      }
    }
    mask.addEventListener(isMobile?"touchstart":"click", evtFn, false);
  }
  return {"bindClick":bindClick};
})();**/