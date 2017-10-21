$.config = {
	router: false,
    routerFilter: function($link) {
        var href = $($link).attr('href');
        if ($link.hasClass('redirect') && href != '') {
            $.toast('跳转中...');
            return false;
        }
        if ($link.hasClass('external')) {
            $.showIndicator();
        }
        if ($link.hasClass('disable-router')) {
            return false;
        }
        return true;
    }
};
