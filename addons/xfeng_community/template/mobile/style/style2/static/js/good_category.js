var g_platformCategoryJson;

var platformCategory = {
	chooseCate:function(dom){
		var catePkno = $(dom).attr("placeholder");
		if(typeof goodsList=="object"&&goodsList.hasOwnProperty("loadGoodsData")){
			goodsList._loadGoodsData(catePkno);
			$(".catego_main").hide();
		}else{
			window.location.href = path+"/goods/list.do?catePkno="+catePkno;
		}
		
	},
	openCates:function(){
		platformCategory.getPlatformCategory();
		$(".catego_main").show();
	},
	closeCates:function(){
		$(".catego_main").hide();
	},
	getPlatformCategory:function(){
		// if($("#rootCategories li").length==0){
			var url = path;
			$.post(url, function(data){
				// g_platformCategoryJson = json.data;
				// var childNodes = g_platformCategoryJson[parentCatePk];			
				
				

				// if(g_platformCategoryJson.hasOwnProperty("-1")){
				// 	var rootNodes = g_platformCategoryJson["-1"];
				// 	for(var i=0; i<rootNodes.length; i++){
				// 		var rootNode = rootNodes[i];
				// 		$("#rootCategories").append("<li placeholder=\""+rootNode.catePkno+"\">"+rootNode.cateName+"</li>")
				// 	}
					// platformCategory.bindingLiClickEvent();
					// $("#rootCategories").find("li:first").click();
				// }
				html = (function(data) { //数据渲染
                            var liArr = [];
                            $.each(data.allhtml, function() {
                                liArr.push(this.html);
                            });
                            return liArr.join('');
                        })(data);

                    $('.clearfix').html(html)
			}, "json");
		// }	
	},
	bindingLiClickEvent:function(){

		$("#rootCategories").find("li").click(function(){
			if(!$(this).hasClass('on')){
				$(this).addClass('on').siblings().removeClass('on');
				$("#childCategories").empty();
				
				var parentCatePk = $(this).attr("placeholder");
				var childNodes = g_platformCategoryJson[parentCatePk];			
				
				if(childNodes!=undefined){
					
					var ind = 0;
					for(var i=0; i<childNodes.length; i++){					
						var tmpChildNode = childNodes[i];
						var childCatePk = tmpChildNode.catePkno;
						var grandsonNodes = g_platformCategoryJson[childCatePk];
						
						if(grandsonNodes!=undefined&&grandsonNodes.length > 0){
							$("#childCategories").append("<h3>"+tmpChildNode.cateName+"</h3>");
							var _ind = 0;
							for(var i=0; i<grandsonNodes.length; i++){
								var tmpGrandsonNode = grandsonNodes[i];
								if(_ind%4 == 0)
									$("#childCategories").append("<ul class=\"clearfix\"></ul>");
								$("#childCategories").find("ul:last").append("<li><div><a href=\"javascript:void(0)\" placeholder=\""+tmpGrandsonNode.catePkno+"\" onclick=\"platformCategory.chooseCate(this);\" ><img class=\"cate_icon_img\" src=\""+$("#hdnCateImagePath").val()+tmpGrandsonNode.cateImg+"\"><span>"+tmpGrandsonNode.cateName+"</span></a></div></li>");
								_ind++;
							}
						}else{
							if(ind%4 == 0)
								$("#childCategories").append("<ul class=\"clearfix\"></ul>");
							$("#childCategories").find("ul:last").append("<li><div><a href=\"javascript:void(0)\" placeholder=\""+tmpChildNode.catePkno+"\" onclick=\"platformCategory.chooseCate(this);\" ><img class=\"cate_icon_img\" src=\""+$("#hdnCateImagePath").val()+tmpChildNode.cateImg+"\"><span>"+tmpChildNode.cateName+"</span></a></div></li>");
						}
						ind++;
					}
				}
				
				if(childNodes==undefined||childNodes.length==0)
					$("#childCategories").append("<div class=\"unhappy\"><i></i><p>无下级分类</p></div>");
			}
		});
		$("#rootCategories").append("<li></li>");
	},
};