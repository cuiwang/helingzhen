//初始化地图
function InitMap() {
    var mapObj = $("#branchMap");
    var title = mapObj.data("title");
    var longitude = mapObj.data("longitude");
    var latitude = mapObj.data("latitude");
    var address = mapObj.data("address");
    var phone = mapObj.data("phone");

     /*var tArray = phone.split('-');
    var pArray;
   if (tArray.length > 1) {
        pArray = tArray[1].split('/');
        for (var j = 0; j < pArray.length; j++) {
            pArray[j] = tArray[0] + "-" + pArray[j];
        }
    }
    else {*/
        pArray = [phone];
   /* }*/

    var phoneStr = "";
    for (var i = 0; i < pArray.length; i++) {
        phoneStr += "<br/>电话：" + pArray[i] + "<br/>"
    }

    // 百度地图API功能
    var map = new BMap.Map("branchMap");
    var myIcon = new BMap.Icon("/addons/siyuan_cms/img/shop/baidu.png", new BMap.Size(16, 32));
    var point = new BMap.Point(longitude, latitude);
    map.centerAndZoom(point, 15);
    map.addControl(new BMap.ZoomControl());

    var marker = new BMap.Marker(new BMap.Point(longitude, latitude), { icon: myIcon });  //创建标注
    map.addOverlay(marker);    // 将标注添加到地图中
    var opts = {
        width: 250,    // 信息窗口宽度
        height: 120,     // 信息窗口高度
        title: title, // 信息窗口标题
        enableAutoPan: true //自动平移
    }
    var infoWindow = new BMap.InfoWindow("<br>地址：" + address + "" + phoneStr, opts);  // 创建信息窗口对象
    map.openInfoWindow(infoWindow, point);
    marker.addEventListener("click", function () {
        map.openInfoWindow(infoWindow, point); //开启信息窗口
    });
}

$(function () {
    InitMap();
})