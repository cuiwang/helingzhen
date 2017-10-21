/*
Powered by Xrh 2014-02-25
*/

var statistics_obj={
	chart:function(){
		$('.chart').height(500).highcharts({
            chart:{
				type:'line',
				backgroundColor:'#F3F3F3'
            },
            title:{text:''},
			xAxis:{categories:chart_data.date},
			yAxis:[{
				title:{text:''},
				min:0
			}],
			plotOptions:{
				line:{
					dataLabels:{enabled:true},
					enableMouseTracking:false
				}
			},
			series:chart_data.count,
			exporting:{enabled:false}
        });
	},
	
	user_area:function(){
		$('.chart').height(500).highcharts({
			title:{text:''},
            credits:{enabled:false},
			tooltip:{
				pointFormat:'{series.name}: <b>{point.percentage:.2f}%</b>'
			},
			plotOptions:{
				pie:{
					allowPointSelect:true,
					cursor:'pointer',
					dataLabels:{
						enabled:true,
						color:'#000000',
						connectorColor:'#000000',
						format:'<b>{point.name}</b>: {point.percentage:.2f} %'
					}
				}
			},
			series:[{
				type:'pie',
				name:'百分比',
				data:user_area_data
			}]
		});
	},
	
	spread_init:function(){
		var date_str=new Date();
		$('#search_form input[name=Time]').daterangepicker({format:'YYYY/MM/DD'});
	}
}