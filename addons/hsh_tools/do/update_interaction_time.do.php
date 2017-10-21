<?php

try {
	require_once './framework/bootstrap.inc.php';
	$specialTableName = 'hsh_tools_interaction_time';
	$specialPostStr = file_get_contents('php://input');
	if (!empty($specialPostStr)) {
		$specialNow = time();
		$specialObj = simplexml_load_string($specialPostStr, 'SimpleXMLElement', 16384);
		$specialSaveData = array();
		$specialSaveData['openid'] = $specialObj->FromUserName;
		$specialFansInfo = pdo_fetch("SELECT * FROM " . tablename($specialTableName) . " WHERE `openid` = :openid", array('openid' => $specialSaveData['openid']));
		if ($specialObj->MsgType == 'event') {
			switch ($specialObj->Event) {
				case 'LOCATION' :
					$specialSaveData['latitude'] = $specialObj->Latitude;
					$specialSaveData['longitude'] = $specialObj->Longitude;
					$specialSaveData['precision'] = $specialObj->Precision;
					break;
				case 'subscribe' :
					if ($specialObj->EventKey != '' && !(strpos($specialObj->EventKey, 'qrscene_') === FALSE) && empty($specialFansInfo)) {
						$specialSaveData['scene_id'] = str_replace('qrscene_', '', $specialObj->EventKey);
					}
					break;
			}
		}
		$specialSaveData['update_times'] = time();
		$specialSaveData['weid'] = $_GPC['id'];
		if (!empty($specialFansInfo)) {
			pdo_update($specialTableName, $specialSaveData, $specialFansInfo);
		} else {
			$specialSaveData['add_time'] = time();
			pdo_insert($specialTableName, $specialSaveData);
		}
	}
} catch (Exception $ex) {
	
}
