<?php
/**
 * @url 
 */
defined ( 'IN_IA' ) or exit ( 'Access Denied' );

class MON_BrandModuleProcessor extends WeModuleProcessor {
	
	public $name = 'EbookModuleProcessor';
	
	
	public $table_brand  = 'brand';
	public $table_reply  = 'brand_reply';
	
	
	
	
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$fromuser = $this->message ['from'];
		if ($rid) {
			$reply = pdo_fetch ( "SELECT * FROM " . tablename ( $this->table_reply ) . " WHERE rid = :rid", array (':rid' => $rid ) );
			
			
			if ($reply) {
				$sql = 'SELECT * FROM ' . tablename ( $this->table_brand ) . ' WHERE `weid`=:weid AND `id`=:bid';
				$brand = pdo_fetch ( $sql, array (':weid' => $_W ['weid'], ':bid' => $reply ['bid'] ) );
				
				$news = array ();
				$news [] = array ('title' => $brand ['bname'], 'description' => $reply['news_content'], 'picurl' => $this->getpicurl ( $reply['new_pic'] ), 'url' => $this->createMobileUrl ( 'brand', array ('bid' => $brand ['id'] ) ) );
				return $this->respNews ( $news );
			}
		}
		return null;
	}
	
	private function getpicurl($url) {
		global $_W;
		if ($url) {
		   
		   // return $url;
			return $_W ['attachurl'] . $url;
		} else {
			return $_W ['siteroot'] . 'source/modules/activity/images/1.jpg';
		}
	}
}

