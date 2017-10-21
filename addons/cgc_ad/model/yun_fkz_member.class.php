<?php

class yun_fkz_member
{
    public function __construct()
    {
        $this->table_name = 'cgc_share_member';
        $this->columns = '*';
        $this->tb_member = 'jiexi_aaa_member';
        $this->tb_mc_members = 'mc_members';
        $this->jy_member = 'yun_fkz_member';
        
            
    }


	  //防封杀
	  public 	function get_jymember($uid) {		
		global $_W;
		$condition = "a.uniacid=:uniacid and a.uid=:uid and a.deleted=0";
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		$pars[':uid'] = $uid;
		$sql = "select a.*,m.nickname,m.avatar from ".tablename($this->jy_member).' a left join '.tablename($this->tb_mc_members)." m on a.uid=m.uid where $condition";
		$exist = pdo_fetch($sql, $pars);
		return $exist;

	}
    
     
    
     


}