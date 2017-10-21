// positionlist_advanced_search.js

var AdvancedSearchModule = function() {

	/* SeachBar initialize with an array of data*/
	/* when suggest is called, it publish sug event with result suggestions */
    var SearchBar = function(selector, options) {
        var $bar = this.$bar = $(selector);
        this.options = options;
        var datum = this.options.datum || [];
        this.init(datum);
    }

    SearchBar.prototype = function() {

        // private
        function initEngine(datum) {
        	// console.log(datum);
            var engine = new Bloodhound({
                name: 'animals',
                local: datum,
                // remote: 'http://example.com/animals?q=%QUERY',
                datumTokenizer: function(d) {
                    return Bloodhound.tokenizers.whitespace(d);
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace
            });
            return engine;
        }

        // public
        var init = function(datum) {
            var engine = this.engine = initEngine(datum);
            this.engine.initialize()
        };

        var search = function() {
            return function(keywords) {
                // console.log(keywords);
                this.engine.get(keywords, function (suggestions) {
                	// console.log(suggestions);
                	$.publish("sug", [suggestions]);
                });
            };
        }();

        return {
            init: init,
            search: search
        };
    }();

    /* SearchForm is the Controller for the popup search form */ 
    var SearchForm = function(wrapper) {
        var $wrapper = this.$form = $(wrapper);
        this.hideSelects = false;
        this.$cancelBtn = $(".js-cancel", $wrapper);
        this.$suggestionsPanel = $(".advanced-search-matches", $wrapper);
        this.$selectsPanel = $(".advanced-search-criterias", $wrapper);

        this.searchBar = new SearchBar($wrapper.find(".js-typeahead"), {
            datum: getData()
        });

        this.bindEvents();

        function getData() {
        	var result = [];
        	var $options = $wrapper.find(".select option");
        	$options.each(function(idx, ele) {
        		result.push($(ele).val());
        	});
        	return result;
    	}
    };

    SearchForm.prototype = function() {

        var show = function() {
            this.$form.show();
            return this;
        };

        var hide = function() {
            this.$form.hide();
            return this;
        };

        var focusOnBar = function () {
        	this.searchBar.$bar.focus();
        	return this;
        };

        var bindEvents = function() {

        	// 取消按钮功能
            this.$cancelBtn.on("click", $.proxy(hide, this));

            // search 功能
            var searchBar = this.searchBar;
            var $bar = searchBar.$bar;
            var self = this;
            var intervalId;

			this.searchBar.$bar.on("focus", function () {
				intervalId = window.setInterval(function() {
	            	searchBar.search($bar.val());
				}, 10);
				if(self.hideSelects) {
					self.$selectsPanel.hide();
				}
			});
			this.searchBar.$bar.on("blur", function() {
				window.clearInterval(intervalId);
			});
            			
            $.subscribe("sug", function(e, suggestions) {
            	if(suggestions.length == 0) {
            		// show selects panel
            		// console.log("show selects panel");
            		self.$suggestionsPanel.hide();
            		if(!self.hideSelects) {
            			self.$selectsPanel.show();
            		}
            	} else {
            		// show suggestions panel
            		// console.log("show suggestions panel");
            		// console.log(suggestions);
            		self.generateSuggestionsDisplay(self.$suggestionsPanel, suggestions);
            		self.$selectsPanel.hide();
            		self.$suggestionsPanel.show();
            	}
            });

            this.$suggestionsPanel.on("click", ".advanced-search-match", function () {
            	var $match = $(this);
            	self.searchBar.$bar.val($match.text());
            	self.$form.submit();
            });

        };

        var generateSuggestionsDisplay = function($panel, sugs) {
        	$panel.children().remove();
        	var $sugs = [];
        	$.each(sugs, function(idx, sug) {
        		var $li = $(['<li class="advanced-search-match">', sug,'</li>'].join(''));
        		$sugs.push($li);
        	});
        	$panel.append($sugs);
        };

        return {
            constructor: SearchForm,
            show: show,
            hide: hide,
            bindEvents: bindEvents,
            focusOnBar: focusOnBar,
            generateSuggestionsDisplay: generateSuggestionsDisplay
        };
    }();

    var init = function() {
        var $wrapper = this.$wrapper = $(".search");
        var $adBtn = this.$adBtn = $(".advanced-search-btn", $wrapper);
        var $outSearchBar = this.$outSearchBar = $(".search-bar-out", $wrapper);
        var searchForm = this.searchForm = new SearchForm(".advanced-search");

        this.bindEvents();

    };

    var bindEvents = function() {
        this.$adBtn.on("click", $.proxy(this.showSearchForm, this, /*hideSelects*/false));
        this.$outSearchBar.on("focus", $.proxy(this.showSearchForm, this, /*hideSelects*/true));
    };

    var showSearchForm = function (hideSelects) {
        this.searchForm.hideSelects = hideSelects;
        this.searchForm.show();
        this.searchForm.focusOnBar();
    };

    return {
        init: init,
        bindEvents: bindEvents,
        showSearchForm: showSearchForm
    };
}();

AdvancedSearchModule.init();