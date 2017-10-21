<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $schoolid = intval($_GPC['schoolid']);
        
        $school = pdo_fetch("SELECT title,style2,is_kb,logo FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ORDER BY ssort DESC", array(':weid' => $weid, ':id' => $schoolid));
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid AND id=:id ORDER BY id DESC", array(':weid' => $weid, ':id' => $_SESSION['user']));
		if (!empty($_W['setting']['remote']['type'])) {
			$urls = $_W['attachurl']; 
		} else {
			$urls = $_W['siteroot'].'attachment/';
		}
		if($it){
			$student = pdo_fetch("SELECT bj_id FROM " . tablename($this->table_students) . " where weid = :weid AND id=:id ", array(':weid' => $weid, ':id' => $it['sid']));
			$starttime = mktime(0,0,0,date("m"),date("d"),date("Y"));
			$endtime = $starttime + 86399;
			$condition = " AND begintime < '{$starttime}' AND endtime > '{$endtime}'";
			$cook = pdo_fetch("SELECT * FROM " . tablename($this->table_timetable) . " WHERE schoolid = :schoolid And bj_id = :bj_id And ishow = 1 $condition", array(':schoolid' => $schoolid,':bj_id' => $student['bj_id']));
			$week = date("w",$endtime);
			if($week ==1){
				if($cook['monday']){
					$thecook = iunserializer($cook['monday']);
					$sd_1 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_1_sd']}'");
					$sd_2 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_2_sd']}'");
					$sd_3 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_3_sd']}'");
					$sd_4 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_4_sd']}'");
					$sd_5 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_5_sd']}'");
					$sd_6 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_6_sd']}'");
					$sd_7 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_7_sd']}'");
					$sd_8 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_8_sd']}'");
					$sd_9 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_9_sd']}'");
					$sd_10 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_10_sd']}'");
					$sd_11 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_11_sd']}'");
					$sd_12 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_12_sd']}'");
					$km_1 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_1_km']}'");
					$km_2 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_2_km']}'");
					$km_3 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_3_km']}'");
					$km_4 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_4_km']}'");
					$km_5 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_5_km']}'");
					$km_6 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_6_km']}'");
					$km_7 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_7_km']}'");
					$km_8 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_8_km']}'");
					$km_9 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_9_km']}'");
					$km_10 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_10_km']}'");
					$km_11 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_11_km']}'");
					$km_12 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['mon_12_km']}'");
				}
			}
			if($week ==2){
				if($cook['tuesday']){
					$thecook = iunserializer($cook['tuesday']);
					$sd_1 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_1_sd']}'");
					$sd_2 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_2_sd']}'");
					$sd_3 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_3_sd']}'");
					$sd_4 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_4_sd']}'");
					$sd_5 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_5_sd']}'");
					$sd_6 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_6_sd']}'");
					$sd_7 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_7_sd']}'");
					$sd_8 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_8_sd']}'");
					$sd_9 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_9_sd']}'");
					$sd_10 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_10_sd']}'");
					$sd_11 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_11_sd']}'");
					$sd_12 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_12_sd']}'");
					$km_1 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_1_km']}'");
					$km_2 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_2_km']}'");
					$km_3 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_3_km']}'");
					$km_4 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_4_km']}'");
					$km_5 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_5_km']}'");
					$km_6 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_6_km']}'");
					$km_7 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_7_km']}'");
					$km_8 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_8_km']}'");
					$km_9 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_9_km']}'");
					$km_10 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_10_km']}'");
					$km_11 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_11_km']}'");
					$km_12 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['tus_12_km']}'");
				}		
			}
			if($week ==3){
				if($cook['wednesday']){
					$thecook = iunserializer($cook['wednesday']);	
					$sd_1 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_1_sd']}'");
					$sd_2 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_2_sd']}'");
					$sd_3 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_3_sd']}'");
					$sd_4 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_4_sd']}'");
					$sd_5 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_5_sd']}'");
					$sd_6 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_6_sd']}'");
					$sd_7 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_7_sd']}'");
					$sd_8 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_8_sd']}'");
					$sd_9 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_9_sd']}'");
					$sd_10 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_10_sd']}'");
					$sd_11 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_11_sd']}'");
					$sd_12 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_12_sd']}'");
					$km_1 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_1_km']}'");
					$km_2 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_2_km']}'");
					$km_3 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_3_km']}'");
					$km_4 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_4_km']}'");
					$km_5 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_5_km']}'");
					$km_6 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_6_km']}'");
					$km_7 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_7_km']}'");
					$km_8 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_8_km']}'");
					$km_9 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_9_km']}'");
					$km_10 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_10_km']}'");
					$km_11 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_11_km']}'");
					$km_12 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['wed_12_km']}'");
				}		
			}
			if($week ==4){
				if($cook['thursday']){
					$thecook = iunserializer($cook['thursday']);
					$sd_1 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_1_sd']}'");
					$sd_2 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_2_sd']}'");
					$sd_3 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_3_sd']}'");
					$sd_4 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_4_sd']}'");
					$sd_5 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_5_sd']}'");
					$sd_6 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_6_sd']}'");
					$sd_7 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_7_sd']}'");
					$sd_8 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_8_sd']}'");
					$sd_9 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_9_sd']}'");
					$sd_10 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_10_sd']}'");
					$sd_11 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_11_sd']}'");
					$sd_12 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_12_sd']}'");
					$km_1 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_1_km']}'");
					$km_2 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_2_km']}'");
					$km_3 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_3_km']}'");
					$km_4 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_4_km']}'");
					$km_5 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_5_km']}'");
					$km_6 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_6_km']}'");
					$km_7 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_7_km']}'");
					$km_8 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_8_km']}'");
					$km_9 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_9_km']}'");
					$km_10 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_10_km']}'");
					$km_11 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_11_km']}'");
					$km_12 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['thu_12_km']}'");
				}		
			}
			if($week ==5){
				if($cook['friday']){
					$thecook = iunserializer($cook['friday']);	
					$sd_1 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_1_sd']}'");
					$sd_2 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_2_sd']}'");
					$sd_3 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_3_sd']}'");
					$sd_4 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_4_sd']}'");
					$sd_5 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_5_sd']}'");
					$sd_6 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_6_sd']}'");
					$sd_7 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_7_sd']}'");
					$sd_8 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_8_sd']}'");
					$sd_9 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_9_sd']}'");
					$sd_10 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_10_sd']}'");
					$sd_11 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_11_sd']}'");
					$sd_12 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_12_sd']}'");
					$km_1 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_1_km']}'");
					$km_2 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_2_km']}'");
					$km_3 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_3_km']}'");
					$km_4 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_4_km']}'");
					$km_5 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_5_km']}'");
					$km_6 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_6_km']}'");
					$km_7 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_7_km']}'");
					$km_8 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_8_km']}'");
					$km_9 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_9_km']}'");
					$km_10 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_10_km']}'");
					$km_11 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_11_km']}'");
					$km_12 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['fri_12_km']}'");
				}		
			}
			if($week ==6){
				if($cook['saturday']){
					$thecook = iunserializer($cook['saturday']);
					$sd_1 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_1_sd']}'");
					$sd_2 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_2_sd']}'");
					$sd_3 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_3_sd']}'");
					$sd_4 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_4_sd']}'");
					$sd_5 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_5_sd']}'");
					$sd_6 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_6_sd']}'");
					$sd_7 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_7_sd']}'");
					$sd_8 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_8_sd']}'");
					$sd_9 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_9_sd']}'");
					$sd_10 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_10_sd']}'");
					$sd_11 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_11_sd']}'");
					$sd_12 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_12_sd']}'");
					$km_1 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_1_km']}'");
					$km_2 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_2_km']}'");
					$km_3 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_3_km']}'");
					$km_4 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_4_km']}'");
					$km_5 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_5_km']}'");
					$km_6 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_6_km']}'");
					$km_7 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_7_km']}'");
					$km_8 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_8_km']}'");
					$km_9 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_9_km']}'");
					$km_10 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_10_km']}'");
					$km_11 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_11_km']}'");
					$km_12 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sat_12_km']}'");
				}		
			}
			if($week ==7){
				if($cook['sunday']){
					$thecook = iunserializer($cook['sunday']);	
					$sd_1 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_1_sd']}'");
					$sd_2 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_2_sd']}'");
					$sd_3 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_3_sd']}'");
					$sd_4 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_4_sd']}'");
					$sd_5 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_5_sd']}'");
					$sd_6 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_6_sd']}'");
					$sd_7 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_7_sd']}'");
					$sd_8 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_8_sd']}'");
					$sd_9 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_9_sd']}'");
					$sd_10 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_10_sd']}'");
					$sd_11 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_11_sd']}'");
					$sd_12 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_12_sd']}'");
					$km_1 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_1_km']}'");
					$km_2 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_2_km']}'");
					$km_3 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_3_km']}'");
					$km_4 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_4_km']}'");
					$km_5 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_5_km']}'");
					$km_6 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_6_km']}'");
					$km_7 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_7_km']}'");
					$km_8 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_8_km']}'");
					$km_9 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_9_km']}'");
					$km_10 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_10_km']}'");
					$km_11 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_11_km']}'");
					$km_12 = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE sid = '{$thecook['sun_12_km']}'");
				}		
			}
			include $this->template(''.$school['style2'].'/timetable');
		}else{
		   session_destroy();
		   include $this->template('bangding');
        } 
?>
