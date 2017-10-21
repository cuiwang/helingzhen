$(function() {
    page.init();
    /*日历*/
    calendar();
    /*加载房型列表*/
    
   
})

/*日历*/
function calendar() {
    var week = ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'],
        fromTime,
        liveTime,
        htmlliveDate,
        htmllfromDate,
        Today = new Date(),
        fromDate = $("#sInDate .choose-time").attr("date-time"), //入住时间
        liveDate = $("#sOutDate .choose-back-time").attr("date-time"); //离店时间

    $("#sInDate").on("click", function() {
        _tcTraObj._tcTrackEvent("click", "mHotelFinal01","入住时间", "3");
        var that = this,
            TodayDate = Today.getFullYear() + "-" + (Today.getMonth() + 1) + "-" + Today.getDate(),
            EndDate = TodayDate.split("-");

        EndDate = new Date(EndDate[0], parseInt(EndDate[1]) - 1, parseInt(EndDate[2]) + 58);
        EndDate = EndDate.getFullYear() + "-" + (EndDate.getMonth() + 1) + "-" + EndDate.getDate();

        $.calendar({
            model: "rangeFrom",
            currentDate: [fromDate, liveDate],
            startDate: TodayDate,
            endDate: EndDate,
            fn: function(dates) {
                $(".choose-time").attr("date-time", dates.join('-'));
                fromTime = new Date(dates.join('/'));
                $(".choose-time").html(dates.join('-').split("-")[1] + "-" + dates.join('-').split("-")[2]);
                fromDate = $("#sInDate .choose-time").attr("date-time"); //重置入住时间
                liveDate = $("#sOutDate .choose-back-time").attr("date-time"); //获取离店日期

                var LineNight,
                    start = fromDate.split("-"),
                    end = liveDate.split('-');

                start = new Date(start[0], parseInt(start[1]) - 1, parseInt(start[2]));
                end = new Date(end[0], parseInt(end[1]) - 1, parseInt(end[2]));
                LineNight = (end - start) / 1000 / 60 / 60 / 24;
                if (LineNight <= 0 || LineNight > 20) {
                    liveDate = new Date(dates[0], parseInt(dates[1]) - 1, parseInt(dates[2]) + 1);
                    htmlliveDate = (liveDate.getMonth() + 1) + "-" + liveDate.getDate();
                    liveDate = liveDate.getFullYear() + "-" + (liveDate.getMonth() + 1) + "-" + liveDate.getDate();
                    $(".choose-back-time").html(liveDate.split("-")[1] + "-" + liveDate.split("-")[2]);
                    $("#sOutDate .choose-back-time").attr("date-time", liveDate);
                }
                $.cookie("comedate", fromDate, {
                    path: "/"
                });
                $.cookie("leavedate", liveDate, {
                    path: "/"
                });
                page.close();
               
            }
        });
        page.open('calendar');
    });
    $("#sOutDate").on("click", function() {
        _tcTraObj._tcTrackEvent("click", "mHotelFinal02","离店时间", "3");
        var that = this,
            fromDate = $("#sInDate .choose-time").attr("date-time"); //重置入住时间
        liveDate = $("#sOutDate .choose-back-time").attr("date-time"); //获取离店日期
        TodayDate = Today.getFullYear() + "-" + (Today.getMonth() + 1) + "-" + Today.getDate(), //今天
            EndDateBox = TodayDate.split("-"), //60天内的日期

            EndDate = fromDate.split("-"), //20天内的日期
            LfromDate = fromDate.split("-"); //开始日期

        EndDateBox = new Date(EndDateBox[0], parseInt(EndDateBox[1]) - 1, parseInt(EndDateBox[2]) + 58);
        EndDate = new Date(EndDate[0], parseInt(EndDate[1]) - 1, parseInt(EndDate[2]) + 19);
        if (EndDate > EndDateBox) {
            EndDate = EndDateBox
        }
        EndDate = EndDate.getFullYear() + "-" + (EndDate.getMonth() + 1) + "-" + EndDate.getDate();

        LfromDate = new Date(LfromDate[0], parseInt(LfromDate[1]) - 1, parseInt(LfromDate[2]) + 1);
        LfromDate = LfromDate.getFullYear() + "-" + (LfromDate.getMonth() + 1) + "-" + LfromDate.getDate();

        $.calendar({
            model: "rangeTo",
            startDate: LfromDate,
            endDate: EndDate,
            currentDate: [fromDate, liveDate],
            fn: function(dates) {
                $(".choose-back-time").attr("date-time", dates.join('-'));
                liveDate = $("#sOutDate .choose-back-time").attr("date-time");
                $(".choose-back-time").html(dates.join('-').split("-")[1] + "-" + dates.join('-').split("-")[2]);
                var LineNight,
                    start = fromDate.split("-"),
                    end = dates.join('-');

                start = new Date(start[0], parseInt(start[1]) - 1, parseInt(start[2]));
                end = new Date(end.split("-")[0], parseInt(end.split("-")[1]) - 1, parseInt(end.split("-")[2]));
                LineNight = (end - start) / 1000 / 60 / 60 / 24;
                $.cookie("comedate", fromDate, {
                    path: "/"
                });
                $.cookie("leavedate", liveDate, {
                    path: "/"
                });
                page.close();
                
            }
        });
        page.open('calendar');
    });
}




