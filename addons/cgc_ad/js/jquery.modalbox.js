/*
 * Jquery plugins collection: modalBox plugin
 * Author: Vladislav Rubanovich
 * Licensed under the MIT license
 */
;(function ( $ ) {

    $.modalBox = function ( el, action, options ) {
        var app = this;
        app.$el = $(el);
        app.$body = $('body');
        app.el = el;

        app.init = function () {
            app.options = $.extend({}, $.modalBox.defaultOptions, options);

            /* BIND CLOSE BUTTON */
            app.$el.find(".close").click(function(){
                app.close();
            });

            if ( app.options.closeOnEscape ) {
                $(app.$el).bind('keydown', function(e){
                    if (e.keyCode == 27) app.close();
                });
            }

            app.$el.click(function(e){
                if ( $(e.target).closest(".inner").length ) return;
                app.close();
            });

            // SCROLLBAR
            app.originalBodyPad = '';

            if ( ! $('.modal-box.active') )
                app.originalBodyPad = app.$body.css('padding-right') || '';

            app.scrollbarWidth = app.measureScrollBar();
        };

        app.open = function() {
            app.$el.trigger( "modalBox:beforeOpen", app );

            if ( $('>.inner', app.$el).outerHeight(true) > $(window).height() ) {
                app.$body.addClass("modal-box-open");
                app.setScrollBar();
            }

            // SET TAB INDEX
            app.$el.attr( 'tabindex', app.getTabIndex() );

            // SET Z-INDEX
            app.$el.css( 'z-index', app.getZIndex() );

            app.$el.addClass("active");

            app.transitionDuration(app.$el, app.options.openAnimationDuration);
            app.animationDuration($('.inner', app.$el), app.options.openAnimationDuration);

            app.animate(app.options.openAnimationEffect, function(){
                app.$el.trigger( "modalBox:afterOpen", app );
                $(app.$el).focus();
            });
        };

        app.close = function() {
            app.$el.trigger( "modalBox:beforeClose", app );

            app.$el.removeClass("active");

            app.transitionDuration(app.$el, app.options.closeAnimationDuration);
            app.animationDuration($('.inner', app.$el), app.options.closeAnimationDuration);

            app.animate(app.options.closeAnimationEffect, function(){
                app.resetScrollBar();
                app.$body.removeClass("modal-box-open");

                app.$el.trigger( "modalBox:afterClose", app );
            });
        };

        app.animate = function(effect, callback) {
            $('.inner', app.$el)
                .addClass(effect + ' animated')
                .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                    $(this).removeClass(effect);
                    if ( typeof callback != 'undefined' ) callback.call(this);
                });
        };

        app.animationDuration = function(el, duration) {
            el.css({ 'animation-duration': duration + 'ms' });
        };

        app.transitionDuration = function(el, duration) {
            el.css({ 'transition-duration': duration + 'ms' });
        };

        app.setScrollBar = function () {
            var bodyPad = parseInt(app.originalBodyPad, 10);
            app.$body.css('padding-right', bodyPad + app.scrollbarWidth);
        };

        app.resetScrollBar = function () {
            app.$body.css('padding-right', app.originalBodyPad);
        };

        app.measureScrollBar = function () {
            var scroll = $('<div class="modal-box-scroll-bar">');
            app.$body.append(scroll);
            var width = scroll[0].offsetWidth - scroll[0].clientWidth;
            scroll.remove();
            return width;
        };

        app.getTabIndex = function() {
            var tabIndex = 1;

            $('div.modal-box[tabindex]').each(function(){
                if ( $(this).attr( 'tabindex' ) > tabIndex ) {
                    tabIndex = Number($(this).attr( 'tabindex' ));
                }
            });

            return tabIndex+1;
        };

        app.getZIndex = function() {
            var zIndex = 999;

            $('div.modal-box').each(function(){
                if ( $(this).css( 'z-index' ) > zIndex ) {
                    zIndex = Number($(this).css( 'z-index' ));
                }
            });

            return zIndex+1;
        };

        var target = app.$el.data().modalBox;
        if ( typeof target == 'undefined' ) {
            app.init();
            app.$el.data( "modalBox" , app );
        } else {
            app = target;
        }

        if (action == "open") {
            app.open();

            if ( app.options.autoClose )
                setTimeout( app.close, app.options.autoCloseDelay );
        }
        else if (action == "close") {
            app.close();
        }
    };

    $.modalBox.defaultOptions = {
        openAnimationDuration: 500,
        closeAnimationDuration: 500,
        openAnimationEffect: 'default-in',
        closeAnimationEffect: 'default-out',
        closeOnEscape: true,
        autoClose: false,
        autoCloseDelay: 3000
    };

    $.fn.modalBox = function( action, options ) {
        return this.each(function() {
            (new $.modalBox(this, action, options))
        });
    };

})( jQuery );