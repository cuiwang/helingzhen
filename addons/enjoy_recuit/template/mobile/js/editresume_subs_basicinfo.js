// editresume_subs_basicinfo.js

var GroupModule = function() {
    var init = function() {
        var $ifgroup = $(".ifgroup");
        var $ifgroupSelect = $("select", $ifgroup);
        var ifgroup = null;
        // updateIfGroup();

        function updateIfGroup() {
            ifgroup = $ifgroupSelect.find(":selected").val();
        }

        var $ifcaptain = $(".ifcaptain");
        var $ifcaptainSelect = $("select", $ifcaptain);
        var ifcaptainSelect = null;

        function updateIfCaptain() {
            ifcaptainSelect = $ifcaptainSelect.find(":selected").val();
        }
        // updateIfCaptain();
        function updateData() {
	        updateIfGroup();
        	updateIfCaptain();
        }

        function update() {
	        updateData();
        	trigger();
        }

        var $captainName = $(".captain-name");

        var $teamName = $(".team-name");

        var pubsub = $({});
        $ifgroupSelect.on("change", update);
        $ifcaptainSelect.on("change", update);

        function trigger() {
            if (ifgroup === "是") {
                if (ifcaptainSelect === "是") {
                    pubsub.trigger("isCaptain");
                } else if (ifcaptainSelect === "否") {
                    pubsub.trigger("notCaptain");
                }
            } else if (ifgroup === "否") {
                pubsub.trigger("noGroup");
            }
        }

        pubsub.on("isCaptain", function() {
        	$ifgroup.show();
        	$ifcaptain.show();
        	$captainName.hide();
        	$teamName.show();
        });
        pubsub.on("notCaptain", function() {
        	$ifgroup.show();
        	$ifcaptain.show();
        	$captainName.show();
        	$teamName.show();
        });
        pubsub.on("noGroup", function() {
        	$ifgroup.show();
        	$ifcaptain.hide();
        	$captainName.hide();
        	$teamName.hide();
        });

        update();
    };
    return {
        init: init
    };
}();

$(window).on("load", function(){
    GroupModule.init();
});