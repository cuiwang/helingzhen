$(function() {
    function distanceByLnglat(lng1, lat1, lng2, lat2) {
        var radLat1 = Rad(lat1);
        var radLat2 = Rad(lat2);
        var a = radLat1 - radLat2;
        var b = Rad(lng1) - Rad(lng2);
        var s = 2 * Math.asin(Math.sqrt(Math.pow(Math.sin(a / 2), 2) + Math.cos(radLat1) * Math.cos(radLat2) * Math.pow(Math.sin(b / 2), 2)));
        s = s * 6378137.0;
        s = Math.round(s * 10000) / 10000000;
        s = s.toFixed(2);
        return s;
    }
    function Rad(d) {
        return d * Math.PI / 180.0
    };
//    var point = new BMap.Point(116.331398, 39.897445);
    var geolocation = new BMap.Geolocation();
    if (document.location.href.indexOf("/index/") == 0) {
        geolocation = null
    };
	
    geolocation.getCurrentPosition(function(r) {
        var _this = this;
		var mloc = $(this).find("#mloc").val();
   //     if (this.getStatus() == BMAP_STATUS_SUCCESS) {
  //          var mk = new BMap.Marker(r.point);
            locLng = $(this).find("#mlng").val();
            locLat = $(this).find("#mlat").val();
            $(".dist").each(function() {
                var ShopLngLat = $(this).find("#showdist").val();
                var InputOF = ShopLngLat.indexOf(",");
                var InputOFLast = ShopLngLat.length;
                var ShopLng = ShopLngLat.slice(0, InputOF);
                var ShopLat = ShopLngLat.slice(InputOF + 1, InputOFLast);
                var dis111 = distanceByLnglat(locLng, locLat, ShopLng, ShopLat);
				if (distanceByLnglat(locLng, locLat, ShopLng, ShopLat)< 1) {
                $(this).find("#dist").html("<i class='iconfont'>&#xe60c;</i>" + dis111*1000 + "m");
				}else{
                $(this).find("#dist").html("<i class='iconfont'>&#xe60c;</i>" + dis111 + "Km");
				}
			alert(ShopLng);
            });
            $("#curlat").val(locLat);
            $("#curlng").val(locLng);
     //   } else {
     //       $(".dist").each(function() {
     //          $(this).find("#dist").html("无法获取距离" + _this.getStatus() + "");
     //       });
     //   }
    }, {
       enableHighAccuracy: true
   });
	
});

