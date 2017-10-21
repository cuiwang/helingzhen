function show_msg (msg, time) {
    $("#cui-msg").html(msg);
    $("#cui-29").show();
    $("#cui-30").show();

    $("#cui-29").delay(time).hide(1);
    $("#cui-30").delay(time).hide(1);
}

function show_loading(){
    $("#cui-28").show();
    $("#cui-29").show();
}
function hide_loading(){
    $("#cui-28").hide();
    $("#cui-29").hide();
}

function show_msgs(msg){
    alert(msg);
}

