function show(src){
    $('#qrcode .qrcode-img').attr('src', src);
    $('#qrcode').show();
}

function getAjaxReturn(url,data,medthod){
    var t = new bybonTools();
    var m = t.showPreloader({ effectIndex: 1 });
    var bol =AjaxReturn(url,data,medthod);
    t.hidePreloader(m);
    return bol;
}

function AjaxReturn(url,medthod){
    var bol;
    $.ajax({
        type:medthod,
        async:false,
        url:url,
        dataType: "json",
        success:function(data){
            bol=data;
        }
    });
    return bol;
}

function hide(){
    $('#qrcode').hide();
}
