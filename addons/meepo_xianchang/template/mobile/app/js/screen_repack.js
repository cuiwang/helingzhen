(function(k, e) {
    var A = k.WBActivity.resize = function() {
			e(".Panel.Top").css({
                top: 0
            });
            e(".Panel.Bottom").css({
                bottom: 0
            });
    };
	function n(B) {
                B.call()
    }
    var c = k.WBActivity.start = function() {
        
        n(function() {
            k.WBActivity.hideLoading();
            e(".Panel.Top").css({
                top: 0
            });
            e(".Panel.Bottom").css({
                bottom: 0
            });
            senddh()
            
           
        })
    }
})(window, jQuery);