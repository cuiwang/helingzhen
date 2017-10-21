$(function() {

    $("#send").click(function() {

        $("#cover").show();

        $("#guide").show();

    });

    $("#cover").click(function() {

        $("#cover").hide();

        $("#guide").hide();

        $("#guide2").hide();

    });

    $("#fengmian").click(function() {

        $("#fengmian").slideToggle("slow");

    });

    $("#sendother").click(function() {

        if (navigator.userAgent.indexOf("iPhone") == -1) {

            location.href = 'weixin://contacts/profile/' + follow_account

        } else {

            location.href = follow_url;

        }

    });

});



function codefans() {

    var box = document.getElementById("priview3");

    box.style.display = "block";

}

setTimeout("codefans()", 3000);



/*function codefans2() {

    var box = document.getElementById("fengmian");

    box.style.display = "none";

}

setTimeout("codefans2()", 4000);
*/
