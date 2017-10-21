(function($) {
	$.fn.EightycloudsFliptimer = function(params) {
		var glob = {
			element: this,
			params: params,
			interval: null,
			time: {
				now: null,
				start: null,
				end: null,
				days: null,
				hours: null,
				minutes: null,
				seconds: null
			}
		};
		this.init = function() {
			timer.build();
			timer.set.time()
		};
		var timer = {
			callback: glob.params.callback,
			addBlock: function(klass) {
				var html;
				html = '<div class="block ' + klass + '">';
				html += '<div class="block_left">';
				html += '<div class="block_left_top">';
				html += '<div class="block_left_top_count">0</div>';
				html += '</div>';
				html += '<div class="block_middle_separator"></div>';
				html += '<div class="block_left_bottom">';
				html += '<div class="block_left_bottom_count">0</div>';
				html += '</div>';
				html += '<div class="block_effect1 left"></div>';
				html += '<div class="block_effect2"></div>';
				html += '<div class="block_effect3"></div>';
				html += '<div class="block_effect4"></div>';
				html += '<div class="block_effect5 left"></div>';
				html += '<div class="block_effect6 left"></div>';
				html += '</div>';
				html += '<div class="block_right">';
				html += '<div class="block_right_top">';
				html += '<div class="block_right_top_count">0</div>';
				html += '</div>';
				html += '<div class="block_middle_separator"></div>';
				html += '<div class="block_right_bottom">';
				html += '<div class="block_right_bottom_count">0</div>';
				html += '</div>';
				html += '<div class="block_effect1"></div>';
				html += '<div class="block_effect2"></div>';
				html += '<div class="block_effect3"></div>';
				html += '<div class="block_effect4"></div>';
				html += '<div class="block_effect5 right"></div>';
				html += '<div class="block_effect6 right"></div>';
				html += '</div>';
				html += '<div class="block_text">';
				html += klass;
				html += '</div>';
				html += '</div>';
				return html
			},
			build: function() {
				var html;
				html = '<div class="EightycloudsFlipTimer">';
				//html += this.addBlock("days");
				html += this.addBlock("hours");
				html += this.addBlock("minutes");
				html += this.addBlock("seconds");
				html += '</div>';
				$(html).appendTo(glob.element)
			},
			set: {
				easing1: "easeInExpo",
				easing2: "easeOutExpo",
				delay: function() {
					return 200
				},
				days: function() {
					var days = ("0" + glob.time.days).slice( - 2);
					var delay = timer.set.delay();
					if ($(glob.element).find(".block.days .block_right_top_count").text() !== days[1]) {
						$(glob.element).find(".block.days .block_effect5.right").animate({
							top: 64,
							height: 0,
							left: 10
						},
						{
							easing: timer.set.easing1,
							duration: delay / 2,
							start: function() {
								$(glob.element).find(".block.days .block_effect5.right").show();
								$(glob.element).find(".block.days .block_right_top_count").text(days[1])
							},
							complete: function() {
								$(glob.element).find(".block.days .block_effect5.right").css({
									top: 3,
									height: 60,
									left: 3
								}).hide();
								$(glob.element).find(".block.days .block_effect6.right").animate({
									height: 55,
									left: 3
								},
								{
									easing: timer.set.easing2,
									duration: delay / 2,
									start: function() {
										$(glob.element).find(".block.days .block_effect6.right").show()
									},
									complete: function() {
										$(glob.element).find(".block.days .block_effect6.right").css({
											height: 0,
											left: 10
										}).hide();
										$(glob.element).find(".block.days .block_right_bottom_count").text(days[1])
									}
								})
							}
						})
					}
					if ($(glob.element).find(".block.days .block_left_top_count").text() !== days[0]) {
						$(glob.element).find(".block.days .block_effect5.left").animate({
							top: 64,
							height: 0,
							left: 10
						},
						{
							easing: timer.set.easing1,
							duration: delay / 2,
							start: function() {
								$(glob.element).find(".block.days .block_effect5.left").show();
								$(glob.element).find(".block.days .block_left_top_count").text(days[0])
							},
							complete: function() {
								$(glob.element).find(".block.days .block_effect5.left").css({
									top: 3,
									height: 60,
									left: 3
								}).hide();
								$(glob.element).find(".block.days .block_effect6.left").animate({
									height: 55,
									left: 3
								},
								{
									easing: timer.set.easing2,
									duration: delay / 2,
									start: function() {
										$(glob.element).find(".block.days .block_effect6.left").show()
									},
									complete: function() {
										$(glob.element).find(".block.days .block_effect6.left").css({
											height: 0,
											left: 10
										}).hide();
										$(glob.element).find(".block.days .block_left_bottom_count").text(days[0])
									}
								})
							}
						})
					}
				},
				hours: function() {
					var hours = ("0" + glob.time.hours).slice( - 2);
					var delay = timer.set.delay();
					if ($(glob.element).find(".block.hours .block_right_top_count").text() !== hours[1]) {
						$(glob.element).find(".block.hours .block_effect5.right").animate({
							top: 64,
							height: 0,
							left: 10
						},
						{
							easing: timer.set.easing1,
							duration: delay / 2,
							start: function() {
								$(glob.element).find(".block.hours .block_effect5.right").show();
								$(glob.element).find(".block.hours .block_right_top_count").text(hours[1])
							},
							complete: function() {
								$(glob.element).find(".block.hours .block_effect5.right").css({
									top: 3,
									height: 60,
									left: 3
								}).hide();
								$(glob.element).find(".block.hours .block_effect6.right").animate({
									height: 55,
									left: 3
								},
								{
									easing: timer.set.easing2,
									duration: delay / 2,
									start: function() {
										$(glob.element).find(".block.hours .block_effect6.right").show()
									},
									complete: function() {
										$(glob.element).find(".block.hours .block_effect6.right").css({
											height: 0,
											left: 10
										}).hide();
										$(glob.element).find(".block.hours .block_right_bottom_count").text(hours[1])
									}
								})
							}
						})
					}
					if ($(glob.element).find(".block.hours .block_left_top_count").text() !== hours[0]) {
						$(glob.element).find(".block.hours .block_effect5.left").animate({
							top: 64,
							height: 0,
							left: 10
						},
						{
							easing: timer.set.easing1,
							duration: delay / 2,
							start: function() {
								$(glob.element).find(".block.hours .block_effect5.left").show();
								$(glob.element).find(".block.hours .block_left_top_count").text(hours[0])
							},
							complete: function() {
								$(glob.element).find(".block.hours .block_effect5.left").css({
									top: 3,
									height: 60,
									left: 3
								}).hide();
								$(glob.element).find(".block.hours .block_effect6.left").animate({
									height: 55,
									left: 3
								},
								{
									easing: timer.set.easing2,
									duration: delay / 2,
									start: function() {
										$(glob.element).find(".block.hours .block_effect6.left").show()
									},
									complete: function() {
										$(glob.element).find(".block.hours .block_effect6.left").css({
											height: 0,
											left: 10
										}).hide();
										$(glob.element).find(".block.hours .block_left_bottom_count").text(hours[0])
									}
								})
							}
						})
					}
				},
				minutes: function() {
					var minutes = ("0" + glob.time.minutes).slice( - 2);
					var delay = timer.set.delay();
					if ($(glob.element).find(".block.minutes .block_right_top_count").text() !== minutes[1]) {
						$(glob.element).find(".block.minutes .block_effect5.right").animate({
							top: 64,
							height: 0,
							left: 10
						},
						{
							easing: timer.set.easing1,
							duration: delay / 2,
							start: function() {
								$(glob.element).find(".block.minutes .block_effect5.right").show();
								$(glob.element).find(".block.minutes .block_right_top_count").text(minutes[1])
							},
							complete: function() {
								$(glob.element).find(".block.minutes .block_effect5.right").css({
									top: 3,
									height: 60,
									left: 3
								}).hide();
								$(glob.element).find(".block.minutes .block_effect6.right").animate({
									height: 55,
									left: 3
								},
								{
									easing: timer.set.easing2,
									duration: delay / 2,
									start: function() {
										$(glob.element).find(".block.minutes .block_effect6.right").show()
									},
									complete: function() {
										$(glob.element).find(".block.minutes .block_effect6.right").css({
											height: 0,
											left: 10
										}).hide();
										$(glob.element).find(".block.minutes .block_right_bottom_count").text(minutes[1])
									}
								})
							}
						})
					}
					if ($(glob.element).find(".block.minutes .block_left_top_count").text() !== minutes[0]) {
						$(glob.element).find(".block.minutes .block_effect5.left").animate({
							top: 64,
							height: 0,
							left: 10
						},
						{
							easing: timer.set.easing1,
							duration: delay / 2,
							start: function() {
								$(glob.element).find(".block.minutes .block_effect5.left").show();
								$(glob.element).find(".block.minutes .block_left_top_count").text(minutes[0])
							},
							complete: function() {
								$(glob.element).find(".block.minutes .block_effect5.left").css({
									top: 3,
									height: 60,
									left: 3
								}).hide();
								$(glob.element).find(".block.minutes .block_effect6.left").animate({
									height: 55,
									left: 3
								},
								{
									easing: timer.set.easing2,
									duration: delay / 2,
									start: function() {
										$(glob.element).find(".block.minutes .block_effect6.left").show()
									},
									complete: function() {
										$(glob.element).find(".block.minutes .block_effect6.left").css({
											height: 0,
											left: 10
										}).hide();
										$(glob.element).find(".block.minutes .block_left_bottom_count").text(minutes[0])
									}
								})
							}
						})
					}
				},
				seconds: function() {
					var seconds = ("0" + glob.time.seconds).slice( - 2);
					var delay = timer.set.delay();
					if ($(glob.element).find(".block.seconds .block_right_top_count").text() !== seconds[1]) {
						$(glob.element).find(".block.seconds .block_effect5.right").animate({
							top: 64,
							height: 0,
							left: 10
						},
						{
							easing: timer.set.easing1,
							duration: delay / 2,
							start: function() {
								$(glob.element).find(".block.seconds .block_effect5.right").show();
								$(glob.element).find(".block.seconds .block_right_top_count").text(seconds[1])
							},
							complete: function() {
								$(glob.element).find(".block.seconds .block_effect5.right").css({
									top: 3,
									height: 60,
									left: 3
								}).hide();
								$(glob.element).find(".block.seconds .block_effect6.right").animate({
									height: 55,
									left: 3
								},
								{
									easing: timer.set.easing2,
									duration: delay / 2,
									start: function() {
										$(glob.element).find(".block.seconds .block_effect6.right").show()
									},
									complete: function() {
										$(glob.element).find(".block.seconds .block_effect6.right").css({
											height: 0,
											left: 10
										}).hide();
										$(glob.element).find(".block.seconds .block_right_bottom_count").text(seconds[1])
									}
								})
							}
						})
					}
					if ($(glob.element).find(".block.seconds .block_left_top_count").text() !== seconds[0]) {
						$(glob.element).find(".block.seconds .block_effect5.left").animate({
							top: 64,
							height: 0,
							left: 10
						},
						{
							easing: timer.set.easing1,
							duration: delay / 2,
							start: function() {
								$(glob.element).find(".block.seconds .block_effect5.left").show();
								$(glob.element).find(".block.seconds .block_left_top_count").text(seconds[0])
							},
							complete: function() {
								$(glob.element).find(".block.seconds .block_effect5.left").css({
									top: 3,
									height: 60,
									left: 3
								}).hide();
								$(glob.element).find(".block.seconds .block_effect6.left").animate({
									height: 55,
									left: 3
								},
								{
									easing: timer.set.easing2,
									duration: delay / 2,
									start: function() {
										$(glob.element).find(".block.seconds .block_effect6.left").show()
									},
									complete: function() {
										$(glob.element).find(".block.seconds .block_effect6.left").css({
											height: 0,
											left: 10
										}).hide();
										$(glob.element).find(".block.seconds .block_left_bottom_count").text(seconds[0])
									}
								})
							}
						})
					}
				},
				time: function() {
					glob.time.now = Math.floor(new Date()) / 1000;
					glob.time.end = Math.floor(new Date(glob.params.enddate)) / 1000;
					if (glob.time.now >= glob.time.end) {
						clearInterval(glob.interval);
						if (typeof timer.callback === "function") {
							timer.callback()
						}
						return
					}
					glob.time.days = Math.floor((glob.time.end - glob.time.now) / 86400);
					glob.time.hours = Math.floor((glob.time.end - glob.time.now) % 86400 / 3600);
					glob.time.minutes = Math.floor((glob.time.end - glob.time.now) % 86400 % 3600 / 60);
					glob.time.seconds = Math.floor((glob.time.end - glob.time.now) % 86400 % 3600 % 60);
					timer.set.days();
					timer.set.hours();
					timer.set.minutes();
					timer.set.seconds();
					glob.interval = setInterval(function() {
						glob.time.now = Math.floor(new Date()) / 1000;
						glob.time.end = Math.floor(new Date(glob.params.enddate)) / 1000;
						if (glob.time.now >= glob.time.end) {
							clearInterval(glob.interval);
							if (typeof timer.callback === "function") {
								timer.callback()
							}
							return
						}
						glob.time.days = Math.floor((glob.time.end - glob.time.now) / 86400);
						glob.time.hours = Math.floor((glob.time.end - glob.time.now) % 86400 / 3600);
						glob.time.minutes = Math.floor((glob.time.end - glob.time.now) % 86400 % 3600 / 60);
						glob.time.seconds = Math.floor((glob.time.end - glob.time.now) % 86400 % 3600 % 60);
						timer.set.days();
						timer.set.hours();
						timer.set.minutes();
						timer.set.seconds()
					},
					1000)
				}
			}
		};
		this.init()
	}
})(jQuery)

