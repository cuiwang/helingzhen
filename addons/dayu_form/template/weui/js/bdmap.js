
var map = new BMap.Map("allmap");
var point = new BMap.Point({$fansloc['loc_y']},{$fansloc['loc_x']});
var marker = '';
map.centerAndZoom(point, 15);
map.addControl(new BMap.NavigationControl()); 
map.addControl(new BMap.ScaleControl());
map.addControl(new BMap.OverviewMapControl());
	var myIcon = new BMap.Icon("{TEMPLATE_WEUI}images/center.gif", new BMap.Size(24,24));
	  
  // 添加定位控件
  var geolocationControl = new BMap.GeolocationControl({
  anchor: BMAP_ANCHOR_TOP_RIGHT,
  showAddressBar: false,
  offset: new BMap.Size(5, 5),
  });
  geolocationControl.addEventListener("locationSuccess", function(e){
    // 定位成功事件
    var address = '';
    address += e.addressComponent.province;
    address += e.addressComponent.city;
    address += e.addressComponent.district;
    address += e.addressComponent.street;
    address += e.addressComponent.streetNumber;
    alert("当前定位地址为：" + address);
  });
  geolocationControl.addEventListener("locationError",function(e){
    // 定位失败事件
    alert(e.message);
  });
  map.addControl(geolocationControl);
	var marker = new BMap.Marker(point,{icon:myIcon});
	map.addOverlay(marker);
map.addEventListener("dragend", function(){
	marker.setPosition(map.getCenter());
	var center = map.getCenter();    
	$("#lng").val(center.lng);
	$("#lat").val(center.lat);   
});
if(marker){
   map.addOverlay(marker);
   marker.enableDragging();
   marker.addEventListener("dragend",function(e){
     $("#lng").val(e.point.lng);
     $("#lat").val(e.point.lat);
   });
}else{
      map.addEventListener("click", function(e){
      if(marker){
          return false;
      }else{
        marker = new BMap.Marker(new BMap.Point(e.point.lng,e.point.lat));
        marker.enableDragging();
        map.addOverlay(marker);
     $("#lng").val(e.point.lng);
     $("#lat").val(e.point.lat);
        marker.addEventListener("dragend",function(e){
     $("#lng").val(e.point.lng);
     $("#lat").val(e.point.lat);
        });         
      }
    });
}
$(function(){ 
	$("#butSumbit").click(function(){ 
		var address=$("#address").val(); 
			alert("跳转至："+address); 		// 创建地址解析器实例
		var myGeo = new BMap.Geocoder();	// 将地址解析结果显示在地图上,并调整地图视野
		myGeo.getPoint($("#address").val(), function(point){
		if (point) {
			map.centerAndZoom(point, 12); 
		}},
		$("#address").val());
	});
});