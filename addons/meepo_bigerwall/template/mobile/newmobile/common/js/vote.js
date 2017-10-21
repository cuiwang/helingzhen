
function progressBar(){
	$("#content").text(VOTECONTENT);
	$("#vote").empty();
	$("#vote1").empty();
	    $.ajax({
			url:GETVOTE,
	    	data:{},
	    	type:"post",
			dataType:'json',  
	    	async:false,
	    	success:function(data){
				if(parseInt(data.total)>0){
	    			$('#sum').text(data.total);
	    		}else{
					$('#sum').text('0');
				}
			if(data.statList.length > 0){
				var idx=1;
	    		var color;
	    		$.each(data.statList,function(i,val){
	    			if(idx==1){
	    				color="pinkBar";
	    			}else if(idx==2){
	    				color="greenBar";
	    			}else if(idx==3){
	    				color="blueBar";
	    			}else{
	    				color="yellowBar";
	    			}
	    			var len = parseInt(data.statList.length);
	    			var chu,yu,left;
	    			chu = len/2;
	    			yu = len%2;
	    			left = yu+Math.floor(chu);
	    			if(len<=8){
		    			if(i<4){
		    			$("#vote").append('<li class="clearfix" style="height:80px"><h6 style="height:43px">'+idx+'、'+val.content+'<img src="'+val.vote_img+'" width=43px height=43px  style="border-radius:50%" /></h6><div class="progressBar"><span class="'+color+'"></span>'+
								              '</div><p><span class="percent">'+val.per+'%</span><span class="poll">'+val.num+'票</span></p></li>');
		    			}else{
		    			$("#vote1").append('<li class="clearfix" style="height:80px"><h6 style="height:43px">'+idx+'、'+val.content+'<img src="'+val.vote_img+'" width=43px height=43px  style="border-radius:50%" /></h6><div class="progressBar"><span class="'+color+'"></span>'+
						              '</div><p><span class="percent">'+val.per+'%</span><span class="poll">'+val.num+'票</span></p></li>');
		    			}
	    			}else{
		    			if(i<left){
			    			$("#vote").append('<li class="clearfix" style="height:80px"><h6 style="height:43px">'+idx+'、'+val.content+'<img src="'+val.vote_img+'" width=43px height=43px  style="border-radius:50%" /></h6><div class="progressBar"><span class="'+color+'"></span>'+
									              '</div><p><span class="percent">'+val.per+'%</span><span class="poll">'+val.num+'票</span></p></li>');
			    			}else{
			    			$("#vote1").append('<li class="clearfix" style="height:80px"><h6 style="height:43px">'+idx+'、'+val.content+'<img src="'+val.vote_img+'" width=43px height=43px  style="border-radius:50%" /></h6><div class="progressBar"><span class="'+color+'"></span>'+
							              '</div><p><span class="percent">'+val.per+'%</span><span class="poll">'+val.num+'票</span></p></li>');
			    			}
	    			}
	    			idx++;
	    		})
					//$("#content").html('<font color=red>本次投票活动还未添加投票项！</font>');
			  }else{
			     	$("#content").html('<font color=red>本次投票活动还未添加投票项！</font>');
			  }
	    	}
	    });
	    
	    
	   
	    
	   
		$(".voteResult li").each(function(i){
			var num=($(this).find(".percent").text())
			$(this).find(".progressBar span").animate({"width":num},1000)
		})
		
		
	
}