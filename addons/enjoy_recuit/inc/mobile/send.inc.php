<?php


global $_W;
global $_GPC;
$openid = $_W['openid'];
$uniacid = $_W['uniacid'];
$pid = $_GPC['pid'];
$com = pdo_fetch('select * from ' . tablename('enjoy_recuit_culture') . ' where uniacid = \'' . $_W['uniacid'] . '\'');
$ctime = pdo_fetchcolumn('select createtime from ' . tablename('enjoy_recuit_deliver') . ' where uniacid=' . $uniacid . ' and openid=\'' . $openid . '\' and pid=' . $pid . ' order by createtime desc');
$time7 = 7 * 24 * 60 * 60;
$time = Intval($ctime) + $time7;
$com = pdo_fetch('select * from ' . tablename('enjoy_recuit_culture') . ' where uniacid = \'' . $_W['uniacid'] . '\'');
$email = $com['email'];
$mylist = pdo_fetch('select uname,sex,age,ed,mobile,email,avatar,present,birth,height,weight,register,address,marriage,school from ' . tablename('enjoy_recuit_basic') . ' as a left join ' . tablename('enjoy_recuit_info') . ' as b on a.openid=b.openid' . "\r\n\t\t\t\t" . 'where a.openid=\'' . $openid . '\' and a.uniacid=' . $uniacid . '');
$myexpers = pdo_fetchall('select * from ' . tablename('enjoy_recuit_exper') . ' where openid=\'' . $openid . '\' and uniacid=' . $uniacid . '');
$mylist['exper'] = $myexpers;
$mycard = pdo_fetchall('select * from ' . tablename('enjoy_recuit_card') . ' where openid=\'' . $openid . '\' and uniacid=' . $uniacid . '');
$mylist['card'] = $mycard;
$mylist['position'] = pdo_fetchcolumn('select pname from ' . tablename('enjoy_recuit_position') . '  where uniacid=' . $uniacid . ' and id=' . $pid . '');
$mylist[sex] = (($mylist[sex] == 1 ? '男' : '女'));
$mylist[height] = ((empty($mylist[height]) ? '' : $mylist[height] . 'cm'));
$mylist[weight] = ((empty($mylist[weight]) ? '' : $mylist[weight] . 'kg'));
$mylist[marriage] = (($mylist[marriage] == 0 ? '未婚' : '已婚'));

switch ($mylist['ed']) {
case 1:
	$mylist['ed'] = '初中';
	break;

case 2:
	$mylist['ed'] = '高中';
	break;

case 3:
	$mylist['ed'] = '中技';
	break;

case 4:
	$mylist['ed'] = '中专';
	break;

case 5:
	$mylist['ed'] = '大专';
	break;

case 6:
	$mylist['ed'] = '本科';
	break;

case 7:
	$mylist['ed'] = '硕士';
	break;

case 8:
	$mylist['ed'] = '博士';
}

switch ($mylist['present']) {
case 1:
	$mylist['present'] = '待业';
	break;

case 2:
	$mylist['present'] = '准备辞职';
	break;

case 3:
	$mylist['present'] = '在职';
	break;

case 4:
	$mylist['present'] = '个体自营';
}

foreach ($mylist['exper'] as $v ) {
	switch ($v['salary']) {
	case 0:
		$v['salary'] = '1000-3000';
		break;

	case 1:
		$v['salary'] = '3000-5000';
		break;

	case 2:
		$v['salary'] = '5000-8000';
		break;

	case 3:
		$v['salary'] = '8000-12000';
		break;

	case 4:
		$v['salary'] = '12000-20000';
		break;

	case 5:
		$v['salary'] = '20000以上';
	}

	$exper .= '                    <tr>' . "\r\n" . '                      <td valign=\'top\' width=\'1%\' nowrap=\'\'></td>' . "\r\n" . '                      <td style=\'WIDTH: 462px; WORD-WRAP: break-word\' class=\'line150\' align=\'left\'>' . "\r\n" . '                       时间：' . $v['stime'] . '--' . $v['etime'] . '<tr>' . "\r\n" . '                      <td></tr>' . "\r\n\r\n" . '                    <tr>' . "\r\n" . '                      <td></td>' . "\r\n" . '                      <td align=\'left\'> 单位：' . $v['company'] . ' </td></tr>' . "\r\n" . '                    <tr>' . "\r\n" . '                      <td></td>' . "\r\n" . '                      <td class=\'resume_p\' align=\'left\'>职务：' . $v['position'] . '</td></tr>' . "\r\n" . '                    <tr>' . "\r\n" . '                    <tr>' . "\r\n" . '                      <td></td>' . "\r\n" . '                      <td class=\'resume_p\' align=\'left\'>薪资：' . $v['salary'] . '元</td></tr>' . "\r\n" . '                    <tr>' . "\r\n" . '                    <tr>' . "\r\n" . '                      <td></td>' . "\r\n" . '                      <td class=\'resume_p\' align=\'left\'>描述：' . $v['descript'] . '</td></tr>' . "\r\n" . '                    <tr>' . "\r\n" . '                      <td height=\'18\' colspan=\'2\'></td></tr>' . "\r\n" . '                  ';
}

foreach ($mylist['card'] as $v ) {
	$card .= '<tr>' . "\r\n" . '                      <td valign=\'top\' width=\'1%\' nowrap=\'\'>证书名称:</td>' . "\r\n" . '                      <td style=\'WORD-WRAP: break-word; WORD-BREAK: break-all\' align=\'left\'>' . $v['cname'] . '</td></tr>';
}

$subject = '(' . $com['cname'] . ') 应聘 ' . $mylist['position'] . '-' . $mylist['uname'] . '';
$body = '<!DOCTYPE html><html lang=\'zh-cmn-Hans\'><head><meta http-equiv=\'Content-Type\' content=\'text/html; charset=UTF-8\'>' . "\r\n\t\t" . '<title>简历</title>' . "\r\n\t\t" . '<meta charset=\'utf-8\'>' . "\r\n\t\t" . '<meta name=\'viewport\' content=\'width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no\'>' . "\r\n\t\t" . '<meta name=\'format-detection\' content=\'telephone=no\'>' . "\r\n\t\t" . '<link rel=\'stylesheet\' href=\'../addons/recruit/template/mobile/css/global.css?v=3125e0f738b1f44a65ad321699cef79ff47017a1\'>' . "\r\n\t\t\r\n\t\t" . '<link rel=\'stylesheet\' href=\'../addons/recruit/template/mobile/css/editresume_main.min.css?v=6a8316efbe2876d5c7436ada47b9b5e4de6d17a9\'>' . "\r\n\t\t" . '<link href=\'../addons/recruit/template/mobile/css/navmenu.min.css\' type=\'text/css\' rel=\'stylesheet\'>' . "\r\n\t\t" . '</head>' . "\r\n\t\t" . '<body>' . "\r\n\t\t" . '<table border=\'0\' cellspacing=\'0\' cellpadding=\'0\' width=\'600\'>' . "\r\n\t\t" . '  <tbody>' . "\r\n\t\t" . '  <tr>' . "\r\n\t\t" . '    <td style=\'BORDER-BOTTOM: #d6d3ce 1px solid; BORDER-LEFT: #d6d3ce 1px solid; BORDER-TOP: #d6d3ce 1px solid; BORDER-RIGHT: #d6d3ce 1px solid\'>' . "\r\n\t\t" . '      <table border=\'0\' cellspacing=\'0\' cellpadding=\'0\' width=\'600\' bgcolor=\'#ffffff\'>' . "\r\n\t\t" . '        <tbody>' . "\r\n\t\t" . '        <tr>' . "\r\n\t\t" . '          <td>' . "\r\n\t\t" . '            <table border=\'0\' cellspacing=\'0\' cellpadding=\'0\' width=\'580\' align=\'center\'><tbody>' . "\r\n\t\t" . '              <tr>' . "\r\n\t\t" . '                <td height=\'10\' colspan=\'3\'></td></tr>' . "\r\n\t\t" . '              <tr>' . "\r\n\t\t" . '                <td valign=\'top\' width=\'1%\' nowrap=\'\'><span style=\'COLOR: #000000; FONT-SIZE: 40px; FONT-WEIGHT: bold\'>' . $mylist[uname] . '</span></td>' . "\r\n\t\t" . '                <td valign=\'top\' align=\'right\'>' . $mylist[sex] . '| ' . $mylist[marriage] . ' | ' . $mylist[birth] . '生 | 籍贯：' . $mylist[register] . ' | ' . "\r\n\t\t" . '                  现住' . $mylist[address] . ' <br> ' . $mylist[school] . '|' . $mylist[ed] . '<br>' . $mylist[present] . '<br>mobile:' . $mylist[mobile] . '<br>身高：' . $mylist[height] . '|体重：' . $mylist[weight] . '<br>E-mail: <a href=\'mailto:' . $mylist[email] . '\'>' . $mylist[email] . '</a> </td>' . "\r\n\t\t" . '                <td width=\'1%\' nowrap=\'\'>' . "\r\n\t\t" . '                  <div style=\'PADDING-BOTTOM: 5px; PADDING-LEFT: 5px; PADDING-RIGHT: 5px; PADDING-TOP: 0px\' class=\'photo\'><a href=\'#\' target=\'_blank\'><img style=\'VERTICAL-ALIGN: middle\' border=\'0\' src=\'' . $mylist[avatar] . '\' width=\'70\' height=\'90\'></a></div></td></tr>' . "\r\n\t\t" . '              <tr>' . "\r\n\t\t" . '                <td height=\'10\' colspan=\'3\'></td></tr></tbody></table></td></tr>' . "\r\n\t\t" . '              <tr>' . "\r\n\t\t" . '                <td><br>' . "\r\n\t\t" . '                  <table border=\'0\' cellspacing=\'0\' cellpadding=\'2\' width=\'580\' bgcolor=\'#f6f7f8\'>' . "\r\n\t\t" . '                    <tbody>' . "\r\n\t\t" . '                    <tr>' . "\r\n\t\t" . '                      <td style=\'BORDER-BOTTOM: #e7e7e7 1px solid; BORDER-LEFT: #e7e7e7 1px solid; BORDER-TOP: #e7e7e7 1px solid; BORDER-RIGHT: #e7e7e7 1px solid\'>&nbsp;&nbsp;<span style=\'COLOR: #8866ff; FONT-SIZE: 14px\'>工作经历</span></td></tr></tbody></table><br>' . "\r\n\t\t" . '                  <table border=\'0\' cellspacing=\'0\' cellpadding=\'0\'>' . "\r\n\t\t" . '                    <tbody>' . "\r\n\t\t\t\t\t\t\t\t" . $exper . "\r\n\t\t" . '</tbody></table></td></tr>' . "\r\n\r\n\t\t" . '              <tr>' . "\r\n\t\t" . '                <td><br>' . "\r\n\t\t" . '                  <table border=\'0\' cellspacing=\'0\' cellpadding=\'2\' width=\'580\' bgcolor=\'#f6f7f8\'>' . "\r\n\t\t" . '                    <tbody>' . "\r\n\t\t" . '                    <tr>' . "\r\n\t\t" . '                      <td style=\'BORDER-BOTTOM: #e7e7e7 1px solid; BORDER-LEFT: #e7e7e7 1px solid; BORDER-TOP: #e7e7e7 1px solid; BORDER-RIGHT: #e7e7e7 1px solid\'>&nbsp;&nbsp;<span style=\'COLOR: #8866ff; FONT-SIZE: 14px\'>证书</span></td></tr></tbody></table><br>' . "\r\n\t\t" . '                  <table border=\'0\' cellspacing=\'0\' cellpadding=\'0\' width=\'580\'>' . "\r\n\t\t" . '                    <tbody>' . "\r\n\t\t\t\t\t\t\t" . $card . "\r\n\t\t\t\t\t\t\t" . ' <tr>' . "\r\n\t\t" . '                <td height=\'10\' colspan=\'3\'></td></tr>' . "\r\n\t\t" . '   </tr></tbody></table></td></tr>' . "\r\n\r\n\t\t" . '</tbody></table></td></tr></tbody></table></td></tr></tbody></table>' . "\r\n\r\n\r\n\r\n\t\t" . '</body></html>';

if ($time <= TIMESTAMP) {
	$data = array('uniacid' => $uniacid, 'openid' => $openid, 'pid' => $pid, 'createtime' => TIMESTAMP);
	$res = pdo_insert('enjoy_recuit_deliver', $data);

	if ($res == 1) {
		pdo_query('update ' . tablename('enjoy_recuit_position') . ' set deliveries=deliveries+1 where uniacid = \'' . $_W['uniacid'] . '\' and id=' . $pid);
		load()->func('communication');
		$result = ihttp_email($email, $subject, $body);
		$results['flag'] = 1;
	}
	 else {
		$results['flag'] = 0;
	}
}
 else {
	$results['flag'] = -1;
}

echo json_encode($results);
