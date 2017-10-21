   $(function() {
       //tab(".item", ".cont_list > li", "ed")
 
   })
   $(function() {
       tab(".imgs .li", ".ctx_img li", "ed")
 
   })
   function tab(a, b, c) { //a 是点击的目标,,b 是所要切换的目标,c 是点击目标的当前样式
       var len = $(a);
       len.bind("click", function() {
           var index = 0;
           $(this).addClass(c).siblings().removeClass(c);
           index = len.index(this); //获取当前的索引
           $(b).eq(index).show().siblings().hide();
           return false;
       }).eq(0).trigger("click"); //浏览器模拟第一个点击
   }

 
   $(function() {
         var hd=$(window).height();
	     var wd=$(window).width();
		 $(".payment .banner").css("height",wd*0.54);
         $(".xieyi").css("height",hd-40);
		 $(".banner_content").css("left",(wd-270)/2)
	   $(".bb_tab").click(function(){
		    $(".proj .desc ").slideToggle();
		})
       $(".donations_area,#btn_submit1 .btn").click(function() {
           $("#payTips,.pay").slideDown("slow");

       })
	   $(".prot span.p").click(function(){

		    $("#tips,.xieyi").fadeIn("slow");
			
		   		   })
		  $(".xieyi span.xieyi_ca, #tips").click(function(){
			
		
		    $("#tips,.xieyi").fadeOut("slow");
		   		   })		   
       $(".close").click(function() {
           $("#payTips,.pay").slideUp("slow");

       })
       $(".sel").click(function() {
           $(this).addClass("ed").parents().siblings().find(".ed").removeClass("ed");

       })
       $("#check").click(function() {
    	   var imgobj = $(this).parents('.prot').find('img');
    	   var src = imgobj.attr("src"), data_src = imgobj.attr("data-src");
    	   if ($(this).attr('checked')) {
    		   $(this).attr('checked', true);
    	   } else {
    		   $(this).attr('checked', false);
    	   }
    	   imgobj.attr("src", data_src);
    	   imgobj.attr("data-src", src);
       })
	   $(".ctx .li").click(function(){
		   $(this).addClass("ed").siblings().removeClass("ed");
		   })
		   
		   var nt=$(".none_txt").text();
		   $(".more").click(function(){
			 $(this).toggleClass("moreu");
			 $(".sou").toggle();
			 //$(".txt").val(nt) ;
		 
			   })
   })