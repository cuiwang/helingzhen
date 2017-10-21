
var llnum = [4.35, 4.75, 4.75, 4.90];   //商贷利率
var gjjllnum = [2.75, 2.75, 2.75, 3.25];   //公积金利率

    var jsMethod = {
        /** 本息还款的月还款额* param lilv 年利率* param total 贷款总额* param month 贷款总月份*/
        bxgetMonthMoney1: function (lilv, total, month) {
            var lilv_month = lilv / 1200; //月利率
            var money = total * lilv_month * Math.pow((1 + lilv_month), month) / (Math.pow((1 + lilv_month), month) - 1);
            return money.toFixed(2);
        },
        //总利息 = 还款月数×每月月供额-贷款本金
        bxzlx: function (month, lilv, bj) {
            var lilv_month = lilv / 1200; //月利率
            var yge = bj * lilv_month * Math.pow((1 + lilv_month), month) / (Math.pow((1 + lilv_month), month) - 1);
            var money = month * yge - bj;
            return money;
        },
        //每月应还利息  每月应还利息=贷款本金×月利率×〔(1+月利率)^还款月数-(1+月利率)^(还款月序号-1)〕÷〔(1+月利率)^还款月数-1〕
        bxmonthlx: function (bj, lv, month, imonth) {
            var lilv_month = lv / 1200; //月利率
            var money = bj * lilv_month * (Math.pow((1 + lilv_month), month) - Math.pow((1 + lilv_month), (imonth - 1))) / (Math.pow((1 + lilv_month), month) - 1);
            return money.toFixed(2);
        },
        //每月应还本金 贷款本金×月利率×(1+月利率)^(还款月序号-1)÷〔(1+月利率)^还款月数-1〕
        bxmonthbj: function (bj, lv, month, imonth) {
            var lilv_month = lv / 1200; //月利率
            var money = (bj * lilv_month * Math.pow((1 + lilv_month), (imonth - 1)) / (Math.pow((1 + lilv_month), month) - 1));
            return money.toFixed(2);
        },
        /** 本金还款的月还款额* param  lilv年利率* param  total贷款总额* param  month贷款总月份* param  cur_month 贷款当前月0～length-1*/
        bjgetMonthMoney2: function (lilv, total, month, cur_month) {
            var lilv_month = lilv / 1200; //月利率
            var benjin_money = total / month; //本金 (贷款本金÷还款月数)+(贷款本金-已归还本金累计额)×月利率
            var money = (total - benjin_money * cur_month) * lilv_month + benjin_money;
            return money.toFixed(2);
        },
        //本金每月应还利息 剩余本金×月利率=(贷款本金-已归还本金累计额)×月利率
        bjmonthlx: function (bj,lilv,month,imonth) {
            var lilv_month = lilv / 1200; //月利率
            var ghbj = bj/month * (imonth-1)  //归还本金
            var money = (bj - ghbj) * lilv_month;
            return money.toFixed(2);
        },
        //每月应还本金=贷款本金÷还款月数 n.o无用参数
        bjmonthbj: function (bj,n, hkys,o) {
            var money = bj / hkys;
            return money.toFixed(2);
        },
        //每月月供递减额=每月应还本金×月利率=贷款本金÷还款月数×月利率
        bjmonthdje: function (bj, hkys, lilv) {
            var lilv_month = lilv / 1200; //月利率
            var money = bj / hkys * lilv_month;
            return money;
        },
        //等额本金总利息=〔(总贷款额÷还款月数+总贷款额×月利率)+总贷款额÷还款月数×(1+月利率)〕÷2×还款月数-总贷款额
        bjzlx: function (bj, month, lilv) {
            var lilv_month = lilv / 1200; //月利率
            var money = ((bj / month + bj * lilv_month) + bj / month * (1 + lilv_month)) / 2 * month - bj;
            return money;
        }
    }

    var sfMethod = {
        mj:0,
        dj:0,
        yhs: function () //印花税
        {
            var money = this.mj * this.dj * 0.05 / 100;
            return money;
        },
        qs: function (sl)  //契税
        {

            var money = this.mj * this.dj * sl;
            return money;
        },
        yys: function (sl) //营业税
        {
            var money = this.mj * this.dj * sl;
            return money;
        },
        gzf: function ()   //公证费 & 委托办产权证费
        {
            var money = this.mj * this.dj * 0.3 / 100;
            return money;
        },
        jysxf: function ()   //交易手续费
        {
            var money = this.mj * 3;
            return money;
        },
        zhdjk: function () //综合地价款
        {
            var money = this.mj * this.dj * 10 / 100;
            return money;
        },
        grsds: function (sl) //个人所得税
        {
            var money = this.mj * this.dj * sl;
            return money;
         }



    }


    function regpd(obj) {
        var reg = new RegExp("^(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*))$");
        if (obj != "") {
            if (!reg.test(obj)) {
                alert("请输入数字!");
            }
        } else {
            alert("请输入数字!");
        }
    }



    //文本框只能输入数字，并屏蔽输入法和粘贴  
    $.fn.numeral = function () {
        $(this).css("ime-mode", "disabled");
        this.bind("keypress", function (e) {
            var code = (e.keyCode ? e.keyCode : e.which);  //兼容火狐 IE      
            if (!$.browser.msie && (e.keyCode == 0x8))  //火狐下不能使用退格键     
            {
                return;
            }
            return code >= 48 && code <= 57;
        });
        this.bind("blur", function () {
            if (this.value.lastIndexOf(".") == (this.value.length - 1)) {
                this.value = this.value.substr(0, this.value.length - 1);
            } else if (isNaN(this.value)) {
                this.value = "";
            }
        });
        this.bind("paste", function () {
            var s = clipboardData.getData('text');
            if (!/\D/.test(s));
            value = s.replace(/^0*/, '');
            return false;
        });
        this.bind("dragenter", function () {
            return false;
        });
        this.bind("keyup", function () {
            if (/(^0+)/.test(this.value)) {
                this.value = this.value.replace(/^0*/, '');
            }
        });
    };


    $(window).load(function () {
        if ($(".listtab").length > 0) {
            var inittop = $(".listtab").offset().top;
            var $backToTopFun = function () {
                if ($("#detailResult").length != 0) {
                    var st = $(document).scrollTop(), winh = $(window).height(); //, sh = $(document).scrollHeight;
                    if (st >= inittop) {
                        //$(".sec").addClass("tabsx");
                        $(".counter_back_top").show();
                    }
                    else {
                        //$(".sec").removeClass("tabsx");
                        $(".counter_back_top").hide();
                    }
                }
            };

            $(document).bind("scroll", $backToTopFun);
            $(document).resize("scroll", $backToTopFun);
            $(function () { $backToTopFun(); });
        }

    })

    function jsScorll() {

        
        $("html,body").animate({ scrollTop: $(".jsresults").offset().top}, "slow"); //1000是ms,也可以用slow代替
     }

    function tableFun(id, data) {
        var chart = new iChart.Donut2D({
            render: id,
            data: data,
            border: false,
            donutwidth: 0.4,
            animation: true,
            shadow_offsety: 10,
            mutex:true,
            sub_option: {
                label: false,
                color_factor: 0.3,
                listeners: {
                    click: function (r) {
                        
                        $(".info").removeClass("lh").html(r.get("name")+"<br/><em>" + r.get("value").toFixed(2) + "</em>");
                    }
                }
            },
            width: 180,
            height: 180,
            padding: -3
        });

        chart.draw();
    }

    $(document).ready(function () {
        $(".daohang").click(function () {
            if ($(".dhshow").is(":hidden")) {
                $(".dhshow").show();

            } else {
                $(".dhshow").hide();

            }
        });

        $(".dhshow").click(function () {
            $(".dhshow").hide();
        });

        if ($(".popShadow").length > 0) {
            $(".popShadow").get(0).addEventListener("touchmove", function (e) {
                e.preventDefault();
                e.stopPropagation();
            }, false);
        }
    });
    