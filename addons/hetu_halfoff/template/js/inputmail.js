// JavaScript Document
$(function(){
	$.fn.extend({
		"changeTips":function(value){
			value = $.extend({
				divTip:""
			},value)
			
			var $this = $(this);
			var indexLi = -1;
			

			
			//隐藏下拉层
			function blus(){
				$(value.divTip).hide();
			}
			
			//键盘上下执行的函数
			function keychang(up){
				if(up == "up"){
					if(indexLi == 0){
						indexLi = $(value.divTip).children().length-1;
					}else{
						indexLi--;
					}
				}else{
					if(indexLi ==  $(value.divTip).children().length-1){
						indexLi = 0;
					}else{
						indexLi++;
					}
				}
				$(value.divTip).children().eq(indexLi).addClass("active").siblings().removeClass();	
			}
			
			//值发生改变时
			function valChange(){
				var tex = $this.val();//输入框的值

				//让提示层显示，并对里面的LI遍历
				if($this.val()==""){
					blus();
				}else{
					$(value.divTip).show();
					//根据ajax的值,li循环添加
					$.post(value.url,{name:tex},function(data) {
						var str = "";
							console.log(data);
						var res = eval('('+data+')');
							console.log(res);
						$.each(res,function(index,val){
							str+='<li name='+value.divTip+' id='+val.bus_id+'>'+val.name+'</li>';
						});
						$(value.divTip).html(str);

						//鼠标点击和悬停LI
						$(value.divTip).children().
						hover(function(){
							indexLi = $(this).index();//获取当前鼠标悬停时的LI索引值;
							// if($(this).index()!=0){
								$(this).addClass("active").siblings().removeClass();
							// }	
						});
					});
				}	
			}
			
			
			//输入框值发生改变的时候执行函数，这里的事件用判断处理浏览器兼容性;
			// if($.browser.msie){
			if(/msie/.test(navigator.userAgent.toLowerCase())){
				$(this).bind("propertychange",function(){
					valChange();
				})
			}else{
				$(this).bind("input",function(){
					valChange();
				})
			}
			

			//事件必须放在html内容加载之后才起作用,此处事件处理废弃
			// //鼠标点击和悬停LI
			// $(value.divTip).children().
			// hover(function(){
			// 	console.log("zhangkai");
			// 	indexLi = $(this).index();//获取当前鼠标悬停时的LI索引值;
			// 	// if($(this).index()!=0){
			// 		$(this).addClass("active").siblings().removeClass();
			// 	// }	
			// })

			//点击document隐藏下拉层
			$(document).click(function(event){
				if($(event.target).attr("name") == value.divTip && $(event.target).is("li")){
					var liVal = $(event.target).text();
					$this.val(liVal);

					var user_id = $(event.target).attr("id");
					$(value.inputId).val(user_id);
					blus();
				}else{
					blus();
				}
			})				
		
			//按键盘的上下移动LI的背景色
			$this.keydown(function(event){
				if(event.which == 38){//向上
					keychang("up")
				}else if(event.which == 40){//向下
					keychang()
				}else if(event.which == 13){ //回车
					var liVal = $(value.divTip).children().eq(indexLi).text();
					$this.val(liVal);

					var user_id = $(value.divTip).children().eq(indexLi).attr("id");
					console.log(user_id);
					$(value.inputId).val(user_id);
					blus();
				}
			})				
		}	
	})
})