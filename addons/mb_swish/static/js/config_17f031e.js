function gotoUrl(i) {
    setTimeout(function() {
        window.location.href = i
    },
    200)
}
function __uri(i) {
    return config.baseCDNUrl + "blessingvoice-build/" + i
}
var config = function() {
    return {
        gameid: "sayhello",
        touch: "touchend",
        click: "click",
        isDebug: false,
        htmlUrl: mbCore.shareObject.url,
        baseUrl: "",
        baseCDNUrl: "",
        configUrl: mbCore.resourceUrl + "/static/js/require.config.js",
        scope: "snsapi_userinfo",
        apiopenid: 'test',
        apitoken: 'test',
        shareInfo: {
            title: mbCore.shareObject.title,
            desc: mbCore.shareObject.content,
            link: mbCore.shareObject.url,
            imgUrl: mbCore.shareObject.image
        }
    }
} ();
window.onload = function() {
    setTimeout(function() {
        return document.getElementById("divShow") ? void window.location.reload() : void 0
    },
    300)
};