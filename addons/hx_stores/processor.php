<?php
/**
 * 门店导航模块处理程序
 *
 * @author 华轩科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
define(EARTH_RADIUS, 6371);//地球半径，平均半径为6371km

class Hx_storesModuleProcessor extends WeModuleProcessor {

	public $table_stores = 'hx_stores';

	public function respond() {
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微赞文档来编写你的代码
		global $_W;
		$lat = $this->message['location_x'];
		$lng = $this->message['location_y'];

		$range = isset($this->module['config']['range']) ? $this->module['config']['range'] : 5;
		
		$point = $this->squarePoint($lng, $lat, $range);
		
		$sql = "SELECT id, title, thumb, content, lat, lng FROM ".tablename($this->table_stores)." WHERE uniacid = '{$_W['uniacid']}' AND lat<>0 AND lat >= '{$point['right-bottom']['lat']}' AND
					 lat <= '{$point['left-top']['lat']}' AND lng >= '{$point['left-top']['lng']}' AND lng <= '{$point['right-bottom']['lng']}'";
		$result = pdo_fetchall($sql);
		
		$stores = array();
		
		$news = array();
		if (!empty($result)) {
			$min = -1;
			foreach ($result as &$row) {
				$row['distance'] = $this->getDistance($lat, $lng, $row['lat'], $row['lng']);
				if ($min < 0 || $row['distance'] < $min) {
					$min = $row['distance'];
				}
			}
			unset($row);

			$temp = array();
			for ($i=0; $i<6; $i++) {
				foreach ($result as $j => $row) {
					if (empty($temp['distance']) || $row['distance'] < $temp['distance']) {
						$temp = $row;
						$h = $j;
					}
				}
				if (!empty($temp)) {
					$news[] = array(
						'title' => $temp['title'] . '(距'.$temp['distance'].'米)',
						'description' => cutstr($temp['content'], 300),
						'picurl' => $_W['attachurl'] . $temp['thumb'],
						'url' => $this->createMobileUrl('detail', array('id' => $temp['id'])),
					);
					unset($result[$h]);
					$temp = array();
				}
			}
			$news[] = array(
				'title' => "点击查看门店地图热点>>>",
				'description' => "",
				'picurl' =>"",
				'url' => $this->createMobileUrl('area'),
				);
			$news[] = array(
				'title' => "点击查看门店列表>>>",
				'description' => "",
				'picurl' =>"",
				'url' => $this->createMobileUrl('list'),
				);
			return $this->respNews($news);
		} else {
			return $this->respText('抱歉，系统中的门店不在您附近！');
		}
	}

	/**
	 *计算某个经纬度的周围某段距离的正方形的四个点
	 *
	 *@param lng float 经度
	 *@param lat float 纬度
	 *@param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为0.5千米
	 *@return array 正方形的四个点的经纬度坐标
	*/
	public function squarePoint($lng, $lat, $distance = 0.5){

		$dlng =  2 * asin(sin($distance / (2 * EARTH_RADIUS)) / cos(deg2rad($lat)));
		$dlng = rad2deg($dlng);

		$dlat = $distance/EARTH_RADIUS;
		$dlat = rad2deg($dlat);

		return array(
			'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
			'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
			'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
			'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
		);
	}
	
	function getDistance($lat1, $lng1, $lat2, $lng2,$len_type = 1, $decimal = 2){
		$radLat1 = $lat1 * M_PI / 180;
		$radLat2 = $lat2 * M_PI / 180;
		$a = $lat1 * M_PI / 180 - $lat2 * M_PI / 180;
		$b = $lng1 * M_PI / 180 - $lng2 * M_PI / 180 ;
		
		$s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
		$s = $s * EARTH_RADIUS;
		$s = round($s * 1000);
		if ($len_type > 1){
			$s /= 1000;
		}
		return round($s, $decimal);
	}
}