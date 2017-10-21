function GetCurrentDateTime(AddDayCount) {
    var d = new Date();
	d.setDate(d.getDate()+AddDayCount);
    var year = d.getFullYear();
    var month = d.getMonth() + 1;
    var date = d.getDate();
    var week = d.getDay();

    var curDateTime = year;
    if (month > 9)
        curDateTime = curDateTime + "年" + month;
    else
        curDateTime = curDateTime + "年0" + month;
    if (date > 9)
        curDateTime = curDateTime + "月" + date + "日";
    else
        curDateTime = curDateTime + "月0" + date + "日";

    var weekday = "";
    if (week == 0)
        weekday = "日";
    else if (week == 1)
        weekday = "一";
    else if (week == 2)
        weekday = "二";
    else if (week == 3)
        weekday = "三";
    else if (week == 4)
        weekday = "四";
    else if (week == 5)
        weekday = "五";
    else if (week == 6)
        weekday = "六";
    curDateTime ='<h2>'+ curDateTime + " 周" + weekday +'</h2><h1>'+date+'</h1>';

    return curDateTime;
}
   