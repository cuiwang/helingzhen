<?php
/**
 * 留言反馈模块微站定义
 *
 * @author 微网融
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Wwr_bookModuleSite extends WeModuleSite {

	public function doMobileBook() {
        global $_GPC,$_W;

        if(checksubmit()) {

            if (empty($_GPC['title'])) {
                message('抱歉，参数错误！', '', 'error');
            }
            $data=array(
                'weid'=>$_W['uniacid'],
                'title'=>$_GPC['title'],
                'truename'=>$_GPC['truename'],
                'mobile'=>$_GPC['mobile'],
                'title'=>$_GPC['title'],
                'content'=>$_GPC['content'],
                'status'=>0,
                'createtime'=>time(),
            );
            if(pdo_insert('wwr_book',$data)){
                message('留言提交成功！',$this->createMobileUrl('book'),'success');
            }else{
                message("留言提交失败！",'','error');
            }
        }
        load()->func('tpl');
		//这个操作被定义用来呈现 功能封面
        $list = $this -> module['config']['huandeng'];

        include $this->template('index');
	}

	public function doWebList() {
        //留言管理
        global $_W,$_GPC;
        load()->func('tpl');
        $ops = array('manage','config');
        $op = in_array($_GPC['op'],$ops)?$_GPC['op']:'manage';

        if($op=='manage'){
        $index=isset($_GPC['page'])?$_GPC['page']:1;
        $pageIndex = $index;
        $pageSize = 10;
        $contion = 'limit '.($pageIndex-1) * $pageSize .','. $pageSize;
        if($op['manage']){
            $total= pdo_fetchcolumn('select count(*) from ' . tablename('wwr_book') . " where `weid`=:unicaid  ",array(':unnicid'=>$_GPC['unicaid']));
            $page = pagination($total,$pageIndex,$pageSize);
            $datalist = pdo_fetchall("select * from ".tablename('wwr_book')." where `weid`=:uniacid order by `id` desc ".$contion,array(':uniacid'=>$_W['uniacid']));

        }
        include $this->template('list');
        }elseif($op=='config'){
            include $this->template('config');
        }
	}

}