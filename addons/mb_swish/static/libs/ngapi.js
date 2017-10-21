define(["MHJ",('__proto__' in {} ? 'zepto':'jquery')],function(MHJ, $){
    
var API_BASE = mbCore.jssdkUrl;

var ngapi = window.ngapi = function(action,params,appid,callback,apiopenid,apitoken,debug) {
    if(!action) {
        alert("action不能为空");
        return false;
    }

    if(!appid) {
        alert("appid不能为空");
        return false;
    }

    this.httpid = this.httpid || 0;
    this.httpid ++;

    var postdata = {};
    postdata.action = action;
    postdata.params = (typeof params == "string" ? params:(JSON.stringify(params) || ""));
    postdata.gameid = appid;
    postdata.apiopenid = apiopenid || "testopenid";
    postdata.apitoken = apitoken || "testopenid";
    if(debug) {
        postdata.debug = debug;
    }

    MHJ.post(API_BASE,postdata,callback);
}

    return ngapi;

});