/**
 *
 **/
var pcodeUtil = pcodeUtil || {};

pcodeUtil.fixHeight = function(){
    var wheight = $('.wrapper').height();
    var windowh = $(window).height();
    if(wheight < windowh){
        $('.wrapper').css('height',windowh+'px');
    }
}

pcodeUtil.getQueryString = function(url){
    var queryString = url.split('?')[1];
    if(!queryString) return;
    queryString = queryString.split('&');
    var queries = {};
    for(var i= 0,len=queryString.length;i<len;i++){
        var tmp = queryString[i].split('=');
        queries[''+tmp[0]] = tmp[1];
    }
    return queries;
}

$(function(){
    pcodeUtil.fixHeight();
});