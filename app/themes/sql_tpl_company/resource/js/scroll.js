/*显示隐藏*/
function display(id){ 
var traget=document.getElementById(id); 
if(traget.style.display=="none"){ 
traget.style.display=""; 
}else{ 
traget.style.display="none"; 
} 
}
//滚动
 	if (document.getElementById('slider')) {
    console=window.console || {dir:new Function(),log:new Function()};
    var active=0,as=document.getElementById('pagenavi').getElementsByTagName('a');
    for(var i=0;i<as.length;i++){
      (function(){
        var j=i;
        as[i].onclick=function(){
          t4.slide(j);
          return false;
        }
      })();
    }
    var t4=new TouchSlider({id:'slider', speed:600, timeout:6000, before:function(index){
      as[active].className='';
      active=index;
      as[active].className='active';
    }});
  };
//导航
$(function(){function a(){var b=$(".all");if(parseInt(b.css("right"))=="0"){b.animate({right:"12em"},400)}else{b.animate({right:"0"},400)}}$("#btn-nav").click(function(){a()});$("#btn-cla").click(function(){$(".pro-cla_list").slideToggle()})});
//分类
$(function(){
	//菜单隐藏展开
	var tabs_i=0
	$('.vtitle').click(function(){
		var _self = $(this);
		var j = $('.vtitle').index(_self);
		if( tabs_i == j ) return false; tabs_i = j;
		$('.vtitle em').each(function(e){
			if(e==tabs_i){
				$('em',_self).removeClass('v01').addClass('v02');
			}else{
				$(this).removeClass('v02').addClass('v01');
			}
		});
		$('.vcon').slideUp().eq(tabs_i).slideDown();
	});
})