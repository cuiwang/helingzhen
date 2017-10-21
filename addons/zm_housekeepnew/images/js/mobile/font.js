function font(w) {

            var w = w || 720,

                docEl = document.documentElement,

                resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',

                recalc = function() {

                    var clientWidth = docEl.clientWidth;

                    if (!clientWidth) return;

                    docEl.style.fontSize = 72 * (clientWidth / w) + 'px';

                };

            if (!document.addEventListener) return;

            window.addEventListener(resizeEvt, recalc, false);

            document.addEventListener('DOMContentLoaded', recalc, false);

        }

        font();