$(function () {
			var thisa=$('.museum ul li dl dd span:nth-child(2)');
			var thisb=$('.museum ul li dl dt span:nth-child(2)');
			var thisc=$('.regulation span:nth-child(1)');
			var thisd=$('.division ul li h6 p span:nth-child(1)');
			var timer=0;
	$('.inputa').focus(function(){
		timer=setInterval(function(){
			$('.completed').val('￥' + $('.inputb').val().substring(0,$('.inputb').val().length-3)*$('.inputa').val())
		},300)
		
	});
	// $('.inputa').bler(function(){
	// 	clearInterval(timer);
	// });
	
			thisd[0].onclick=function(){
				this.style.background='#00BEAF';
				thisd[1].style.background='';
			}
			thisd[1].onclick=function(){
				this.style.background='#00BEAF';
				thisd[0].style.background='';
			}
			thisc[0].onclick=function(){
				if(this.style.background==''){
					this.style.background='#00BEAF';
				}else{
					this.style.background='';
				}
				
			}
			$('.project h3').click(function(){
				$('.museum').show();
				$('.museum').height($(document).height()).width($(document).width());
				$('.museum ul li dl').width($(document).width()-($('.museum ul').width()));
				$('.museum ul li dl dd span:nth-child(2)').attr('off',true);	
			})
			var arr=[];
			$('.museum div span').click(function(){
				$('.museum').hide();
				$('.project div').html(' ');
				for (var j = 0; j < $('.duihao').length; j++) {  
    			$('.project div').append('<span></span>')
    			$('.project div span').eq(j).html($('.duihao').eq(j).html())    
    		}   
			})
			
			
			for(var i=0;i<$('.museum ul li dl dt span:nth-child(2)').length;i++){
				thisb[i].onclick=function(){
				if(this.innerHTML==''){
					$(this).parents('dl').find(' dd span:nth-child(2)').html('&#xe606;')
					$(this).parents('dl').find(' dd span:nth-child(1)').addClass('duihao')
					$(this).html('&#xe606;')
				}else{
					$(this).parents('dl').find(' dd span:nth-child(2)').html('')
					$(this).parents('dl').find(' dd span:nth-child(1)').removeClass('duihao')
					$(this).html('')
				}				
			}
			}
			
			for (var i=0;i<$('.museum ul li').length;i++) {
				$('.museum ul li').eq(i).click(function(){
					$('.museum ul li').css({
						'background':'',
						'color':'#000'
					});
					$('.museum ul li dl').css({
						'display':'none',
					});
					$(this).css({
						'background':'#00BEAF',
						'color':'#fff'
					});
					$(this).find('dl').css({
						'display':'block',
						'top':-$(this).position().top
					});
			})
			}
			
			for (var i=0;i<thisa.length;i++) {
					thisa[i].onclick = function(){
					if(this.innerHTML==''){
						this.innerHTML='&#xe606;';
						$(this).prev('span').addClass('duihao')
					}else{
						this.innerHTML='';
						$(this).prev('span').removeClass('duihao')
					}
				}
			}	
			for(var i=0;i<$('.order_lst li').length;i++){
		$('.order_lst li').eq(i).click(function(){
			$('.order_lst li span').css('display','none');
			$(this).find('span').css('display','block')
		})}
		var thise=$('.division ul li h6 p span:nth-child(1)');
		var thisf=$('.regulation span:nth-child(1)');
			thise[0].onclick=function(){
				this.style.background='#00BEAF';
				thise[1].style.background='';
			}
			thise[1].onclick=function(){
				this.style.background='#00BEAF';
				thise[0].style.background='';
			}
			thisf[0].onclick=function(){
				if(this.style.background==''){
					this.style.background='#00BEAF';
				}else{
					this.style.background='';
				}
			}
			for (var i=0;i<$('.assessment_a div ul li').length;i++) {
				$('.assessment_a div ul li').eq(i).click(function(){
					$('.assessment_a div ul li').css('backgroundImage','url(images/xxx.png)')
					$('.assessment_a div h6 span').html($("li").index(this)+1);
					$(this).css('backgroundImage','url(images/sx.png)')
					$(this).prevAll().css('backgroundImage','url(images/sx.png)')
				})

	};
	
		})
