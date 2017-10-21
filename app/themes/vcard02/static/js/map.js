//初始化
function _init_map() {
	var posX = $("#data-map").attr("data-map-pos-x");
	var posY = $("#data-map").attr("data-map-pos-y");
	var text = $("#data-map").attr("data-map-text");
	var map = new BMap.Map("allmap"); // 创建Map实例
	var point = new BMap.Point(posX, posY); // 创建点坐标
	map.centerAndZoom(point, 16); // 初始化地图,设置中心点坐标和地图级别。
	map.enableScrollWheelZoom(); //启用滚轮放大缩小
	var marker1 = new BMap.Marker(new BMap.Point(posX, posY)); // 创建标注
	map.addOverlay(marker1);
	if(text) {
		var opts = {
		  position : point,    // 指定文本标注所在的地理位置
		  offset   : new BMap.Size(-40, -50)    //设置文本偏移量
		}
		var label = new BMap.Label(text, opts);  // 创建文本标注对象
		label.setStyle({
			 color : "red", 
			 fontSize : "12px",
			 height : "20px",
			 lineHeight : "20px",
			 fontFamily:"微软雅黑",
             maxWidth:"inherit"
		 });
		map.addOverlay(label);   
	}
	//禁止冒泡事件
	$("#r-result").bind("touchmove",function(e){
		 e.stopPropagation();
	});
	//开始导航
	$("#map-btn").bind("click",function(){
			//$(this).hide();
			$("#map-close-img").show();
			$("#bus-guide-img").show();
			$("#self-guide-img").show();
			$("#itude-parent-div").show();
			$("#itude-div").show();
			$("#itude-parent-div").css("z-index",1002);
			var guide_map = new BMap.Map("itude-div"); // 创建Map实例
			var point = new BMap.Point(posX, posY); // 创建点坐标
			var your_point = null;
			guide_map.centerAndZoom(point, 16); // 初始化地图,设置中心点坐标和地图级别。
			guide_map.enableScrollWheelZoom(); //启用滚轮放大缩小
			guide_map.enableDragging();
			var guide_marker = new BMap.Marker(point); // 创建标注
			guide_map.addOverlay(guide_marker);
			var opts = {
			  position : point,    // 指定文本标注所在的地理位置
			  offset   : new BMap.Size(-40, -50)    //设置文本偏移量
			}
			var label = new BMap.Label(text, opts);  // 创建文本标注对象
			label.setStyle({
				 color : "red",
				 fontSize : "12px",
				 height : "20px",
				 lineHeight : "20px",
				 fontFamily:"微软雅黑",
                 maxWidth:"inherit"
			 });
			guide_map.addOverlay(label);   
			 
			$("#map-close-img").unbind();
			$("#self-guide-img").unbind();
			$("#open-road-btn").unbind();
			$("#bus-guide-img").unbind();
			$("#map-close-img").bind("click",function(){
				$("#itude-div").html("");
				$("#itude-div").hide();
				$("#allmap_").show();
				$("#r-result").html("");
				$("#map-btn").show();
				$("#map-close-img").hide();
				$("#bus-guide-img").hide();
				$("#self-guide-img").hide();
				$("#open-road").hide();
				$("#itude-parent-div").css("z-index",-1);
				$("#itude-parent-div").hide();
				$(".BMap_Marker").show();
			});
			//自驾导航
			$("#self-guide-img").bind("click",function(){ 
			$("#open-road").show()
			$("#open-road-btn").html("正在寻找路线...");
			var geolocation = new BMap.Geolocation();
			geolocation.getCurrentPosition(function(r){
		    	if(this.getStatus() == BMAP_STATUS_SUCCESS){
		       	guide_map.panTo(r.point);
		       	guide_map.enableScrollWheelZoom();                            //启用滚轮放大缩小
		     		 your_point = r.point;
		        	 if(your_point && point){
		        		//alert("正在绘制路线");
		        		$("#r-result").html(''); //清空结果面板
		        		guide_map.clearOverlays();//清空路线
						var driving = new BMap.DrivingRoute(guide_map, {
                            renderOptions: {map: guide_map,panel:"r-result",autoViewport: true},
                            onSearchComplete:function(result){
                                if(result.getNumPlans() == 0) {
                                    alert('非常抱歉,未搜索到可用路线');
                                    closeTheResult();
                                    guide_map.centerAndZoom(point, 16); // 初始化地图,设置中心点坐标和地图级别。
                                    var result_marker = new BMap.Marker(point); // 创建标注
                                    guide_map.addOverlay(result_marker);
                                    var result_opts = {
                                      position : point,    // 指定文本标注所在的地理位置
                                      offset   : new BMap.Size(-40, -50)    //设置文本偏移量
                                    }
                                    var resutl_label = new BMap.Label(text, result_opts);  // 创建文本标注对象
                                    resutl_label.setStyle({
                                         color : "red",
                                         fontSize : "12px",
                                         height : "20px",
                                         lineHeight : "20px",
                                         fontFamily:"微软雅黑",
                                         maxWidth:"inherit"
                                     });
                                    guide_map.addOverlay(resutl_label);
                                }
                            }
						});
						driving.search(your_point,point);
						$("#r-result").height($("#itude-div").height()-55);
						$("#r-result").show();
						$("#open-road-btn").html("关闭路线图");
						$("#open-road").show();
					 }else {
		        		alert("绘制路线失败，请稍后重试");
		        	 } 
		          }else {
		                 warningAlert(this.getStatus());
		   		  }        
			},{enableHighAccuracy: true});
		});
		//打开关闭路线图
		$("#open-road-btn").bind("click",function(){
			if($("#r-result").css("display") == "none"){
				$("#r-result").show();
				$("#open-road-btn").html("关闭路线图");
			} else {
				$("#r-result").hide();
				$(".BMap_Marker","#itude-div").remove();
				var myIcon = new BMap.Icon("./themes/vcard01/static/images/start.png", new BMap.Size(38,33));
				var markerx = new BMap.Marker(your_point,{icon:myIcon}); // 创建标注
				guide_map.addOverlay(markerx);
				myIcon = new BMap.Icon("./themes/vcard01/static/images/end.png", new BMap.Size(33,33));
				markerx = new BMap.Marker(point,{icon:myIcon}); // 创建标注
				guide_map.addOverlay(markerx);
				$("#open-road-btn").html("打开路线图");
			}
		});
		//交通工具导航
		$("#bus-guide-img").bind("click",function(){
			$("#open-road").show()
			$("#open-road-btn").html("正在寻找路线...");
			var geolocation = new BMap.Geolocation();
			geolocation.getCurrentPosition(function(r){
		    	if(this.getStatus() == BMAP_STATUS_SUCCESS){
		      	  var mk = new BMap.Marker(r.point);
		       	  guide_map.addOverlay(mk);
		       	  guide_map.panTo(r.point);
		       	  guide_map.enableScrollWheelZoom();                            //启用滚轮放大缩小
		     	  your_point = r.point;
		        	 if(your_point && point){
		        		//alert("正在绘制路线");
		        		$("#r-result").html(''); //清空结果面板
		        		guide_map.clearOverlays();//清空路线
						var transit = new BMap.TransitRoute(guide_map, {
						renderOptions: {map: guide_map,panel:"r-result"},
						onSearchComplete:function(result){
							if(result.getNumPlans() == 0) {
								alert('非常抱歉,未搜索到可用路线');
								closeTheResult();
								guide_map.centerAndZoom(point, 16); // 初始化地图,设置中心点坐标和地图级别。
								var result_marker = new BMap.Marker(point); // 创建标注
								guide_map.addOverlay(result_marker);
								var result_opts = {
								  position : point,    // 指定文本标注所在的地理位置
								  offset   : new BMap.Size(0, -50)    //设置文本偏移量
								}
								var resutl_label = new BMap.Label(text, result_opts);  // 创建文本标注对象
								resutl_label.setStyle({
									 color : "red",
									 fontSize : "12px",
									 height : "20px",
									 lineHeight : "20px",
									 fontFamily:"微软雅黑",
                                     maxWidth:"inherit"
								 });
								guide_map.addOverlay(resutl_label); 
							}
						},			
			    		policy://BMAP_TRANSIT_POLICY_AVOID_SUBWAYS   //不乘地铁  
						       BMAP_TRANSIT_POLICY_LEAST_TIME	//最少时间。
						   	   //BMAP_TRANSIT_POLICY_LEAST_TRANSFER	最少换乘。
						       //BMAP_TRANSIT_POLICY_LEAST_WALKING	最少步行。
						       //BMAP_TRANSIT_POLICY_AVOID_SUBWAYS	不乘地铁。(自 1.2 新增)
						});
						transit.search(your_point, point);
						$("#r-result").height($("#itude-div").height()-55);
						$("#r-result").show();
						$("#open-road-btn").html("关闭路线图");
						$("#open-road").show();
					 }else {
		        		alert("绘制路线失败，请稍后重试");
		        	 }
		          }else {
		                warningAlert(this.getStatus());
		   		  }        
			},{enableHighAccuracy: true});
		});
	});
}
//错误信息
function warningAlert(flag){
	switch (flag){
		case 1: alert("城市列表");break;
		case 2: alert("位置结果未知");break;
		case 3: alert("导航结果未知");break;
		case 4: alert("非法密钥");break;
		case 5: alert("非法请求");break;
		case 6: alert("没有权限");break;
		case 7: alert("服务不可用");break;
		case 8: alert("超时");break;
		default : alert("未知错误");break;
	}
}

//关闭导航
function closeTheResult(){
	$("#open-road").hide();
	$("#r-result").html("");
	$("#r-result").hide();
}