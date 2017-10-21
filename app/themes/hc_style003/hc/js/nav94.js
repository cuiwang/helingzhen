var nav94 =(function(){
	bindClick = function(els){
		if(!els || !els.length){return;}
		var evt = "ontouchstart" in window;
		for(var i=0,ci; ci = els[i]; i++){
			if(evt){
				(function(){
					ci.addEventListener("touchstart", evtFn, false);
					ci.addEventListener("touchend", evtFn, false);
				})(ci);
			}else{
				(function(){
					ci.addEventListener("mousedown", evtFn, false);
					//ci.addEventListener("mouseleave", evtFn, false);
					ci.addEventListener("mouseup", evtFn, false);
				})();
			}
		}

		function evtFn(evt, ci){
			ci =this;
			console.log(evt.type);
			switch(evt.type){
				case "touchstart":
				case "mousedown":
					ci.classList.add("on");
				break;
				case "touchend":
				case "mouseup":
				case "mouseleave":
					ci.classList.remove("on");
				break;

			}
		}
	}
	return {"bindClick":bindClick};
})();