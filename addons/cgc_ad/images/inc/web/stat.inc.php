<?php 


		global $_W,$_GPC;

		$weid=$_W['uniacid'];


		if(empty($_GPC['quan_id'])){
           $quan_id = '';
        }else{
          $quan_id = " AND quan_id = ".$_GPC['quan_id'];
          $con= " AND a.quan_id = ".$_GPC['quan_id'];
		}



		if(empty($_GPC['dopost'])){

		//撒钱统计

	  $_puser=pdo_fetchcolumn("SELECT count(t.id) FROM  (SELECT id FROM ".tablename('cgc_ad_adv')." WHERE weid=".$weid.$quan_id." and del=0 group by mid ) t");

		 $_pnum=pdo_fetchcolumn("SELECT count(id) FROM   ".tablename('cgc_ad_adv')." WHERE weid=".$weid.$quan_id." and del=0");

		$_pcount = pdo_fetchcolumn("SELECT SUM(total_amount) FROM ".tablename('cgc_ad_adv')." WHERE weid=".$weid.$quan_id." and status>0 and del=0");





		//抢钱统计

		$_quser =pdo_fetchcolumn("SELECT count(t.id) FROM  (SELECT id FROM ".tablename('cgc_ad_red')." WHERE weid=".$weid.$quan_id." group by mid ) t");

		$_qnum = pdo_fetchcolumn("SELECT count(id) FROM   ".tablename('cgc_ad_red')." WHERE weid=".$weid.$quan_id);

		$_qcount = pdo_fetchcolumn("SELECT SUM(money) FROM ".tablename('cgc_ad_red')." WHERE weid=".$weid.$quan_id); //算作支出总额



		//提现人数

		$_tuser =pdo_fetchcolumn("SELECT count(t.id) FROM  (SELECT id FROM ".tablename('cgc_ad_tx')." WHERE weid=".$weid.$quan_id." group by mid ) t");

		$_tnum = pdo_fetchcolumn("SELECT count(id) FROM   ".tablename('cgc_ad_tx')." WHERE weid=".$weid.$quan_id);

		$_tcount = pdo_fetchcolumn("SELECT SUM(money) FROM ".tablename('cgc_ad_tx')." WHERE weid=".$weid.$quan_id); //已提现



		// 总人数

		$_muser = pdo_fetchcolumn("SELECT count(id) FROM ".tablename('cgc_ad_member')." WHERE weid=".$weid.$quan_id);

    	$_mnum = pdo_fetchcolumn("SELECT count(id) FROM   ".tablename('cgc_ad_member')." WHERE weid=".$weid.$quan_id." and credit>0");

		$_mcount = pdo_fetchcolumn("SELECT SUM(credit) FROM ".tablename('cgc_ad_member')." WHERE weid=".$weid.$quan_id." and credit>0"); //提现总额



		//手续费

		$fee=pdo_fetchcolumn("SELECT sum(fee) FROM ".tablename('cgc_ad_adv')." WHERE weid=".$weid." AND status=1 ".$quan_id);

		//收入总额

		$_total = ((float)$_pcount+(float)$fee);

		//未提现总额

		$_notxian = ((float)$_qcount-(float)$_tcount);

		$data = array(

			 array(

				  '撒钱人数'=>array(

						'num'=>$_puser,

						'dopost'=>'puser'

					),

					'撒钱次数'=>array(

						'num'=>$_pnum,

						'dopost'=>'puser'

					),

					'撒钱总额'=>array(

						'num'=>$_pcount,

						'dopost'=>'puser'

					),

			 ),

			array(

				'已抢人数'=>array(

					'num'=>$_quser,

					'dopost'=>'quser',

				),

				'已抢次数'=>array(

					'num'=>$_qnum,

					'dopost'=>'quser',

				),

				'抢钱总额'=>array(

					'num'=>$_qcount,

					'dopost'=>'quser',

				),

			),

			array(

				'提现人数'=>array(

					'num'=>$_tuser,

					'dopost'=>'tuser',

				),

				'提现次数'=>array(

					'num'=>$_tnum,

					'dopost'=>'tuser',

				),

				'提现总额'=>array(

					'num'=>$_tcount,

					'dopost'=>'tuser',

				),

			),

			array(

				'总人数'=>array(

					'num'=>$_muser,

					'dopost'=>'muser',

				),

				'未提现人数'=>array(

					'num'=>$_mnum,

					'dopost'=>'muser',

				),

				'未提现总额'=>array(

					'num'=>$_mcount,

					'dopost'=>'muser',

				),

			),

			array(

				'收人总额'=>array(

					'num'=>$_total,

					'dopost'=>'',

				),

				'支出总额'=>array(

					'num'=>$_qcount,

					'dopost'=>'',

				),

				'净利润'=>array(

					'num'=>($_total-$_qcount),

					'dopost'=>'',

				),

			),

		);



		$quan=pdo_fetchall("SELECT id,aname FROM ".tablename('cgc_ad_quan')." WHERE weid=".$weid." AND del=0");

		}else{

			  $dopost = $_GPC['dopost'];

				$psize=15;

				$pindex = max(1, intval($_GPC['page']));

				switch ($dopost) {

							case 'puser':

							$title = '撒钱统计详情';

							 $sql = "SELECT a.*,c.headimgurl,c.nickname FROM ".tablename('cgc_ad_adv')." a

							  LEFT JOIN ".tablename('cgc_ad_quan')." b on a.quan_id = b.id

								LEFT JOIN ".tablename('cgc_ad_member')." c on a.mid = c.id

								where a.weid=".$weid." and a.status>0 AND a.del=0 $con

							  LIMIT ".($pindex - 1) * $psize.",{$psize}";

								$sql2 = "SELECT count(id) FROM ".tablename('cgc_ad_adv')." a

								 where a.weid=".$weid." and a.status>0 AND a.del=0 $con";

								 $sql3 = "SELECT SUM(total_amount) FROM ".tablename('cgc_ad_adv')." a

									where a.weid=".$weid." and a.status>0 AND a.del=0 $con";

									$__total = pdo_fetchcolumn($sql3);

							break;

							case 'quser':

							$title = '抢钱统计详情';

							 $sql = "SELECT a.money as total_amount,a.id,a.create_time,c.headimgurl,c.nickname FROM ".tablename('cgc_ad_red')." a

								LEFT JOIN ".tablename('cgc_ad_quan')." b on a.quan_id = b.id

								LEFT JOIN ".tablename('cgc_ad_member')." c on a.mid = c.id

								where a.weid=".$weid." $con

								LIMIT ".($pindex - 1) * $psize.",{$psize}";

								$sql2 = "SELECT count(id) FROM ".tablename('cgc_ad_red')." a

								 where a.weid=".$weid.$con;

								 $sql3 = "SELECT SUM(money) FROM ".tablename('cgc_ad_red')." a

									where a.weid=".$weid.$con;

									$__total = pdo_fetchcolumn($sql3);

							break;

							case 'tuser':

							$title = '提现统计详情';

							 $sql = "SELECT a.money as total_amount,a.id,a.create_time,c.headimgurl,c.nickname FROM ".tablename('cgc_ad_tx')." a

								LEFT JOIN ".tablename('cgc_ad_quan')." b on a.quan_id = b.id

								LEFT JOIN ".tablename('cgc_ad_member')." c on a.mid = c.id
		

								where a.weid=".$weid." and a.status=1 $con

								LIMIT ".($pindex - 1) * $psize.",{$psize}";

								$sql2 = "SELECT count(id) FROM ".tablename('cgc_ad_tx')." a

								 where a.weid=".$weid." and a.status=1 $con" ;

								 $sql3 = "SELECT SUM(money) FROM ".tablename('cgc_ad_tx')." a

									where a.weid=".$weid." and a.status=1 $con";

									$__total = pdo_fetchcolumn($sql3);

							break;

							case 'muser':

								$title = '未提现统计详情';

							$sql = "SELECT a.credit as total_amount,a.id,a.createtime as create_time,c.headimgurl,c.nickname  FROM ".tablename('cgc_ad_member')." a

							 LEFT JOIN ".tablename('cgc_ad_quan')." b on a.quan_id = b.id

							 LEFT JOIN ".tablename('cgc_ad_member')." c on a.id = c.id


							 where a.weid=".$weid." and a.credit>0 $con

							 LIMIT ".($pindex - 1) * $psize.",{$psize}";

							 $sql2 = "SELECT count(id) FROM ".tablename('cgc_ad_member')." a

								where a.weid=".$weid." and a.credit>0 $con";

								$sql3 = "SELECT SUM(credit) FROM ".tablename('cgc_ad_member')." a

								 where a.weid=".$weid." and a.credit>0 $con";

								 $__total = pdo_fetchcolumn($sql3);


							break;

							case 'total':

							break;



				}

				$_list  = pdo_fetchall($sql);

				// var_dump($_list);

				$total = pdo_fetchcolumn($sql2);

				$pager = pagination($total, $pindex, $psize);

		}

		/**

		 * 撒钱统计

		 */

		include $this->template('web/stat');