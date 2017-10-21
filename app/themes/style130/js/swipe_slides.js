// pure JS
var elem = document.getElementById('mySwipe');
var bullets = document.getElementById('position').getElementsByTagName('li');
window.mySwipe = Swipe(elem, {
    startSlide: 0,
    auto: 30000000,
    continuous: true,
    // disableScroll: true,
    // stopPropagation: true,
    callback: function(pos) {
        var i = bullets.length;
        while (i--) {
            bullets[i].className = ' ';
        }
        bullets[pos].className = 'on';
    }
    // transitionEnd: function(index, element) {}
});

// with jQuery
// window.mySwipe = $('#mySwipe').Swipe().data('Swipe');