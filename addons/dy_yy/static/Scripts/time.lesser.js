// JavaScript Document
/**
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package js
 * @name time.lesser.js
 * @date 2011-12-09 13:10:08
 */
 
var $__G_Time = {};
var $__ms_Count = {};
var $__G_Timer_Count = 0;
var $__ms_use = true;

if (typeof(__Timer_lesser_auto_accuracy) == 'undefined')
{
	__Timer_lesser_auto_accuracy = false;
}

$(document).ready(function(){
	if (__Timer_lesser_auto_accuracy && $__G_Timer_Count >= __Timer_lesser_worker_max)
	{
		$__ms_use = false;
	}
	for (id in $__G_Time)
	{
		// first time minus 1 secs
		showtime(id, $__G_Time[id]-1);
	}
});

function addTimeLesser(id, time)
{
	$__G_Time['remainTime_'+id] = time;
	$__G_Timer_Count ++;
}

function showtime(id, time, msid)
{
	var msC = $__ms_Count[id];
	if (msC == undefined) msC = 0;
	if ($__ms_use && msC > 0 && msid != '')
	{
		$('#'+msid).text('.'+msC);
		msC --;
		$__ms_Count[id] = msC;
		setTimeout(function(){showtime(id, time, msid)}, 100);
		return;
	}
	$__ms_Count[id] = 9;
	if (time <= 0)
	{
		//$('#' + id).html('<span>活动已经结束</span>');
		return;
	}
	var timeUnits = {
		'day': { 'name': '天', 'count': 86400 },
		'hour': { 'name': '小时', 'count': 3600 },
		'minute': { 'name': '分', 'count': 60 },
		'second': { 'name': '秒', 'count': 1 }
	};
	var string = '';
	var iLess = time;
	for (ix in timeUnits)
	{
		var unit = timeUnits[ix];
		/* if (iLess >= unit.count || iLess == 0)
		{
			*/
			var cc = Math.floor(iLess / unit.count);
			var ccString = cc < 10 ? '0'+cc.toString() : cc.toString();
			string += '<span >' + ccString + '</span>' + unit.name;
			iLess -= cc * unit.count;
		/* }
		*/
	}
/*	if ($__ms_use)
	{
		var msid = 'msid_'+__rand_key();
		string += '<font id="'+msid+'">.0</font>';
	}
*/	$('#' + id).html(string);
	setTimeout(function(){showtime(id, time - 1, msid)}, $__ms_use ? 100 : 1000);
}

function __rand_key()
{
	var salt = '0123456789qwertyuioplkjhgfdsazxcvbnm';
	var str = 'id_';
	for(var i=0; i<6; i++)
	{
		str += salt.charAt(Math.ceil(Math.random()*100000000)%salt.length);
	}
	return str;
}