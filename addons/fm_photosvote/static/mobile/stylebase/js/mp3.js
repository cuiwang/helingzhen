createbgmusic=function(state){	
			var playbox = (function(state){
				var _playbox = function(){
					var that = this;
					that.box = null;
					that.player = null;
					that.src = null;
					that.on = false;
					that.autoPlayFix = {
						on: true,
						//evtName: ("ontouchstart" in window)?"touchend":"click"
						evtName: ("ontouchstart" in window)?"touchstart":"mouseover"
					}
				};
				_playbox.prototype = {
					init: function(box_ele,state){ 
						if(state==2) return false; 
						this.box = "string" === typeof(box_ele)?document.getElementById(box_ele):box_ele;
						if(this.box){
							this.player = this.box.querySelectorAll("audio")[0];
							this.src = this.player.src;
							this.init = function(){return this;}
							this.autoPlayEvt(true);
						}
						return this;
					},
					play: function(){
						if(this.autoPlayFix.on){
							this.autoPlayFix.on = false;
							this.autoPlayEvt(false);
						}
						this.on = !this.on;
						if(true == this.on){
							this.player.src = this.src;
							this.player.play();
						}else{
							this.player.pause();
							this.player.src = null;
						}
						if("function" == typeof(this.play_fn)){
							this.play_fn.call(this);
						}
					},
					handleEvent: function(evt){
						this.play();
					},
					autoPlayEvt: function(important){
						if(important || this.autoPlayFix.on){
							document.body.addEventListener(this.autoPlayFix.evtName, this, false);
						}else{
							document.body.removeEventListener(this.autoPlayFix.evtName, this, false);
						}
					}
				}
				//
				return new _playbox();
			})();
			playbox.play_fn = function(){
				this.box.className = this.on?"btn_music musicplay":"btn_music musicstop";
			};
			playbox.init("playbox",state);
			$('#playbox').click(function(e){
				playbox.init(this).play();
			});
	}