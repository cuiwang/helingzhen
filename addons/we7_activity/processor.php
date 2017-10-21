<?php
/**
 * @url 
 */
defined ( 'IN_IA' ) or exit ( 'Access Denied' );

class We7_ActivityModuleProcessor extends WeModuleProcessor {
	
	public $name = 'EbookModuleProcessor';
	
	
	public $table_activity  = 'activity';
	public $table_reply  = 'activity_reply';
	
	
	
	
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$fromuser = $this->message ['from'];
		if ($rid) {
			$reply = pdo_fetch ( "SELECT * FROM " . tablename ( $this->table_reply ) . " WHERE rid = :rid", array (':rid' => $rid ) );
			
			
			
			if ($reply) {
				$sql = 'SELECT * FROM ' . tablename ( $this->table_activity ) . ' WHERE `weid`=:weid AND `id`=:aid';
				$activity = pdo_fetch ( $sql, array (':weid' => $_W ['uniacid'], ':aid' => $reply ['aid'] ) );
				
			
				
				$news = array ();
				$news [] = array ('title' => $activity ['name'], 'description' => $reply['news_content'], 'picurl' => $this->getpicurl ( $reply ['new_pic'] ), 'url' => $this->createMobileUrl ( 'activity', array ('id' => $activity ['id'],'rid'=>$reply['id'] ) ) );
				return $this->respNews ( $news );
			}
		}
		return null;
	}
	
	private function getpicurl($url) {
		global $_W;
		return $_W ['attachurl'] . $url;
		
	}
}

