window.onload = function() {
	var MainManager = {
		dom: {
			btnShare: ".btn-share",
			shareLayer: ".share-layer"
		},
		init: function() {
			window.CMER.DomQuery(this.dom);
			this.dom.btnShare.addEventListener("click", this.checkForm.bind(this), false);
			this.dom.shareLayer.addEventListener("click", function() {
				this.classList.remove("show");
			}, false);
		},
		checkForm: function(e) {
			this.dom.shareLayer.classList.add("show");
		},
	}

	MainManager.init();
}