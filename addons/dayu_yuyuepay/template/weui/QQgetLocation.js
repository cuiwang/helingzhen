
function getLocation(_this,id){
	var name = $('html').attr('data-name');
	var qqkey = $('html').attr('data-qqkey');
	$.confirm("由"+name+"自动获取您的地址吗?", "确定?", function() {
		$.showLoading();
	wx.getLocation({
		type: 'gcj02', //wgs84 或 gcj02
		success: function (res) {
			var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
			var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
			var MJKD_LATLNG = latitude+','+longitude;		
			var data={
				location:MJKD_LATLNG,
				key:qqkey,
				get_poi:0
			}
			var url="//apis.map.qq.com/ws/geocoder/v1/?";
			data.output="jsonp";
			$.ajax({
				type:"get",
				dataType:'jsonp',
				data:data,
				jsonp:"callback",
				jsonpCallback:"QQmap",
				url:url,
				success:function(json){
					$.toast(json.result.address, "success");
					$.hideLoading();
					if(id){						
						$('textarea[name="'+id+'"]').val(json.result.address_component.province+json.result.address_component.city+json.result.address_component.district+json.result.address_component.street+json.result.address_component.street_number);
					}else{
						$('input[name=gpsaddress]').val(json.result.address_component.street+json.result.address_component.street_number);
						cascdeInit(json.result.address_component.province, json.result.address_component.city, json.result.address_component.district);
					}
				},
				error : function(err){alert("服务端错误，请刷新浏览器后重试")}
			});	
		},
		fail: function(res) {
			$.alert('获取位置失败');
		}
	});
	}, function() {
		$.toast("取消了自动获取地址!", "cancel");
	});
}