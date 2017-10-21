$(document).ready(function(){

	/*tab*/
	showTabs();
	/**/

	$('#tab_my').addClass("current");
	
	/**/
	$('#tab ul li').click(function(){
		if (this.id=="tab_my"){
			$('#currentPage').attr("value",1);
			window.sessionStorage.setItem("currentPage",$('#currentPage').attr("value")); 

			window.sessionStorage.setItem("scrollTop",0); 
		}
	});
	
	$(window).scroll(function () {
        var scrollTop = $(window).scrollTop();
        window.sessionStorage.setItem("scrollTop",scrollTop); 
    });
	
	initSession();
	
	var currentPage = $('#currentPage').attr("value");
	var countPerPage = $('#countPerPage').attr("value");
	loadActivity(1,countPerPage*currentPage);

	$('#loadMore').click(function(){
		
		if ($('#loadMore').hasClass('noData')==false){
			
			$('#loadMore').removeClass();
			$('#loadMore').addClass("isLoading");
			$('#loadMore p').html("正在加载...");

			var currentPage = $('#currentPage').attr("value");
			var newPage = parseInt(currentPage)+1;
			var countPerPage = $('#countPerPage').attr("value");
			loadActivity(newPage,countPerPage);
		}
		
	});
	/**/
	
});

function showTabs(){
	var actType = $('#actType').attr("value");
	$('.tabs').find("li").removeClass("current");
	$('#tab-'+actType).addClass("current");
}

function initSession(){
	var currentPage = window.sessionStorage.getItem("currentPage");
	if (currentPage){
		$('#currentPage').attr("value",currentPage)
	}
	else{
		window.sessionStorage.setItem("currentPage",$('#currentPage').attr("value")); 
	}
}

function loadActivity(currentPage,countPerPage){

	var actType = $('#actType').attr("value");

	var scrollTop = window.sessionStorage.getItem("scrollTop"); 

	if (currentPage>1){
		$('#currentPage').attr("value",currentPage);
		window.sessionStorage.setItem("currentPage",currentPage); 
	}
	else{
		var countPerPage2 = $('#countPerPage').attr("value");
		var currentPage2 =  Math.ceil(countPerPage / countPerPage2);
		$('#currentPage').attr("value",currentPage2);
		window.sessionStorage.setItem("currentPage",currentPage2);
	}
	
	$.ajax({
		url: url,
		type:'GET',
		data:{currentPage:currentPage,countPerPage:countPerPage,actType:actType},
		dataType:'json',
		success:function(data)
		{
			if (data.success == "true"){
				
				var list='';
				if (data.activities.length>0){
					
					for (i = 0; i < data.activities.length; i++)
					{
						list=list+'<a href="'+data.activities[i]._url+'" >';
						list=list+'<div class="holder-act-my" id="act-'+data.activities[i].id+'">';
						list=list+'<div class="left-act-my">';
						list=list+'<img src="'+data.activities[i].index_logo+'" />';
						list=list+'</div>';
						
						list=list+'<div class="right-act-my">';
						list=list+'<h2>'+data.activities[i].title+'</h2>';
						list=list+'<p>抽 奖 码：'+data.activities[i].cj_code+'</p>';
						list=list+'<p>活动时间：'+data.activities[i].start_time+' ~ '+data.activities[i].end_time+'</p>';
						list=list+'<p>开奖时间：'+data.activities[i].kj_time+'</p>';
						list=list+'<p>';
						list=list+'中奖状态：';
						if (data.activities[i].zj_status==0){
							list=list+'<span class="color-red">未中奖</span>';
						}
						if (data.activities[i].zj_status==1){
							list=list+'<span class="color-green">已中奖</span>';
						}
						list=list+'</p>';
						if (data.activities[i].pay_money>0){
							list=list+'<p>';
							list=list+'支付状态：';
							if (data.activities[i].is_pay==0){
								list=list+'<span class="color-red">未支付</span>';
							}
							if (data.activities[i].is_pay==1){
								list=list+'<span class="color-green">已支付</span>';
							}
							list=list+'(<span class="color-gray">￥'+data.activities[i].pay_money+'</span>)';
							list=list+'</p>';
						}
						
						if(data.activities[i].activity_type==0){
							list=list+'<span class="type t1">抽</span>';
						}else if(data.activities[i].activity_type==1){
							list=list+'<span class="type t1">优</span>';
						}else if(data.activities[i].activity_type==2){
							list=list+'<span class="type t2">支</span>';
						}
						
						
				
						list=list+'</div>';
						list=list+'</div>';
						list=list+'</a>';
			
			
					}
					if (currentPage==1){
						$('#showActivity').html(list);
					}
					else{
						$('#showActivity').append(list);
					}

					//$('.holder-act-my').click(function(){
						//var activity_id = this.id.split("-")[1];
						//window.location.href = "index.php?activity_id="+activity_id;
					//});
					
				}
				else{
					list=list+'<div class="noresult">';
					list=list+'<h3>抱歉!</h3>';
					list=list+'<p>暂无报名记录！</p>';
					list=list+'</div>';
					$('#showActivity').html(list);
				}

				$('#countRecords').attr("value",data.countRecords);
				//$('#currentPage').attr("value",data.data['currentPage']);
				loadPager();

				if(scrollTop!=null){
					$(window).scrollTop(scrollTop);
				}
				
			}
			else{
				alert(data.message);
			}

		}

	});
}

function loadPager(){
	
	var countRecords = parseInt($('#countRecords').attr("value"));
	if (countRecords>0){
		$('#loadMore').show();
		var currentPage = parseInt($('#currentPage').attr("value"));
		var countPerPage = parseInt($('#countPerPage').attr("value"));
		var countPage =  Math.ceil(countRecords / countPerPage);
		if (currentPage<countPage){
			$('#loadMore').removeClass();
			$('#loadMore p').html("加载更多");
		}
		else{
			$('#loadMore').removeClass();
			$('#loadMore').addClass("noData");
			$('#loadMore p').html("没有更多数据了");
		}
	}
	else{
		$('#loadMore').hide();
	}
}
