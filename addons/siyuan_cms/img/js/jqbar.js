(function ($) {
    $.fn.extend({
        jqbar: function (options) {
            var settings = $.extend({
                animationSpeed: 2000,
                barLength: 260,
                orientation: 'h',
                barWidth: 30,
                barColor: 'red',
                label: '&nbsp;',
                value: 10000
            }, options);

            return this.each(function () {

                var valueLabelHeight = 0;
                var progressContainer = $(this);

                if (settings.orientation == 'h') {

                    progressContainer.addClass('jqbar horizontal').append('<span class="bar-label"></span><span class="bar-level"></span><span class="bar-renshu"></span>');

                    var progressLabel = progressContainer.find('.bar-label').html(settings.label);
                    var progressBar = progressContainer.find('.bar-level').attr('data-value', settings.value);
					var progressRenshu = progressContainer.find('.bar-renshu').html(settings.renshu+ '人');
                    /*var progressBarWrapper = progressContainer.find('.bar-level-wrapper');*/

                    progressBar.css({ height: settings.barWidth, width: 0, backgroundColor: settings.barColor });

                    /*var valueLabel = progressContainer.find('.bar-percent');
                    valueLabel.html('0');*/
                }
 

                animateProgressBar(progressBar);

                function animateProgressBar(progressBar) {

                    var level = parseInt(progressBar.attr('data-value'));
                    if (level > 100) {
                        level = 100;
                        alert('max value cannot exceed 100 percent');
                    }
                    var w = settings.barLength * level / 100;

                    if (settings.orientation == 'h') {
                        progressBar.animate({ width: w }, {
                            duration: 2000,
                            step: function (currentWidth) {
                                var percent = parseInt(currentWidth / settings.barLength * 100);
                                if (isNaN(percent))
                                    percent = 0;
                                progressContainer.find('.bar-percent').html(percent + '人');
                            }
                        });
                    }
                    /*else {

                        var h = settings.barLength - settings.barLength * level / 100;
                        progressBar.animate({ top: h }, {
                            duration: 2000,
                            step: function (currentValue) {
                                var percent = parseInt((settings.barLength - parseInt(currentValue)) / settings.barLength * 100);
                                if (isNaN(percent))
                                    percent = 0;
                                progressContainer.find('.bar-percent').html(Math.abs(percent) + '%');
                            }
                        });

                        progressContainer.find('.bar-percent').animate({ top: (h - valueLabelHeight) }, 2000);

                    }*/
                }

            });
        }
    });

})(jQuery);