window.onload = function() {
	var MainManager = {
		dom: {
			btnHelpOther: ".btn-help-other",
			btnAlertOther: ".btn-alert-other",
			shareLayer: ".share-layer"
		},
		init: function() {
			window.CMER.DomQuery(this.dom);
			this.dom.btnHelpOther && this.dom.btnHelpOther.addEventListener("click", this.checkForm.bind(this), false);
			this.dom.btnAlertOther && this.dom.btnAlertOther.addEventListener("click", this.checkForm.bind(this), false);
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