window.onload = function() {
	var MainManager = {
		dom: {
			btnHelpOther: ".btn-share",
			inputanquan: "[name='shouquan']",
			shareLayer: ".share-layer"
		},
		init: function() {
			window.CMER.DomQuery(this.dom);		
			this.dom.btnHelpOther && this.dom.btnHelpOther.addEventListener("click", this.checkForm.bind(this), false);
			this.dom.shareLayer.addEventListener("click", function() {
				this.classList.remove("show");
			}, false);
		},
		checkForm:function(){
		    var tel = this.dom.inputanquan.value;
		
			this.dom.shareLayer.classList.add("show");
		},
		bindOpen: function() {

		}
		// showNum: function() {
		// 	var numData = window.config_custom.OPENPRIZE;
		// 	if (numData.h === 0) {
		// 		this.dom.textNum.classList.add("first");
		// 	} else if (numData.r === 1) {
		// 		this.dom.textNum.classList.add("last");
		// 	} else {
		// 		this.dom.textNumRest.textContent = numData.r;
		// 	}
		// },
		// showNumAfterShare: function() {
		// 	var numData = window.config_custom.OPENPRIZE;
		// 	numData.r--;
		// 	numData.h++;
		// 	this.dom.textNum.classList.remove("first");
		// 	this.dom.textNum.classList.remove("last");
		// 	if (numData.r === 0) {
		// 		this.dom.textNum.classList.add("sharelast");
		// 	} else {
		// 		this.dom.textNum.classList.add("share");
		// 		this.dom.textNumRest.textContent = numData.r;
		// 		this.dom.textNumRest.textNumHave = numData.h;
		// 	}
		// }
	};
	MainManager.init();
}
