	$(function(){
		var token = $("#token").val();
		switch(token){
			/*case 'dfm':
				$("body div").hide();
				var winHeight = 0;
				if (window.innerHeight){
                   winHeight = window.innerHeight;
	             }else if ((document.body) && (document.body.clientHeight)){
	                   winHeight = document.body.clientHeight;
	            }
		    	$("body").prepend("<div><img class='dfmstartimg' src='http://t2.qpic.cn/mblogpic/fd56280402d37a6b313a/460' style='position:fixed;width:100%;height:"+winHeight+"px;z-index:40;'/></div> ");
		    	$(".dfmstartimg").show();
		    	$(".dfmstartimg").click(function(){
		    		imgup();
		    	});
		    	setTimeout('imgup()',4000);
		    	break;*/
		    case 'lanbo':
		    	$(".copyright").html(" ");
		    	break;
		    case 'chihewanle':
		    	$(".copyright").html("<div style='font-size:14px;'>富阳吃喝玩乐</div><div style='font-size:10px;'><a href='tel:0571-61733602' style='text-decoration:none;'>客服专线：0571-61733602</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;客服QQ：811423685</div>");
		    	break;
		}
		/*if (token == 'dfm') {
			//$("body div").hide();
	    	//var winWidth = 0;
     	   
	    	 //获取窗口宽度
            if (window.innerWidth){
                   winWidth = window.innerWidth;

             }else if ((document.body) && (document.body.clientWidth)){
                   winWidth = document.body.clientWidth;
             }
             //获取窗口高度
             if (window.innerHeight){
                   winHeight = window.innerHeight;
             }else if ((document.body) && (document.body.clientHeight)){
                   winHeight = document.body.clientHeight;
             }
            // alert(winHeight+":"+winWidth);
            
	    	//$("body").prepend("<div><img class='dfmstartimg' src='http://t2.qpic.cn/mblogpic/fd56280402d37a6b313a/460' style='position:fixed;width:100%;height:"+winHeight+"px;z-index:40;'/></div> ");
	    	//$(".dfmstartimg").show();
	    	$(".dfmstartimg").click(function(){
	    		imgup();
	    	});
	    	setTimeout('imgup()',4000);
	    }
		if (token == 'lanbo') {
	    	$(".copyright").html(" ");
	    }
	    if(token == 'chihewanle'){
	    	$(".copyright").html("<div style='font-size:14px;'>富阳吃喝玩乐</div><div style='font-size:10px;'><a href='tel:0571-61733602' style='text-decoration:none;'>客服专线：0571-61733602</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;客服QQ：811423685</div>");
	    };*/
	    
		
	});
	   
	    function imgup(){
	    		$("body div").show();
	    		$(".dfmstartimg").slideUp(800);

	  	}