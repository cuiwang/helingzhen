!function(a, b) {
    var c = a.Detail;
    c.UI = b(c)
}(this, function(a) {
    function b(b, c) {
        try {
            if (Number(a.postData.user_info.uin) !== Number(a.myuin))
                return
        } catch (d) {}
        b && ($("#lz_level")[0].className = "prevent_default l-level lv" + b,
        $("#lz_level").html("LV." + b + " " + c))
    }
    return {
        updateLevel: b
    }
});