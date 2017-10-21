window.onload = function() {
	var FormManager = {
		dom: {
			panel: ".panel-box",
			btnClose: ".panel-close"
		},
		init: function() {
			window.CMER.DomQuery(this.dom);

			this.dom.btnClose.addEventListener("click", this.hidePanel.bind(this), false);
		},
		showPanel: function() {
			this.dom.panel.classList.add("show");
		},
		hidePanel: function() {
			this.dom.panel.classList.remove("show");
		}
	}

	

	MainManager.init();
	FormManager.init();
}