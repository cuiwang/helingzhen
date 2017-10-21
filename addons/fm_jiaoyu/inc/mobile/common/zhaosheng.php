<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $id = intval($_GPC['schoolid']);

        $school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ", array(':weid' => $weid, ':id' => $id));
        $title = $school['title'];

        if (empty($school)) {
            message('参数错误');
        }
        include $this->template(''.$school['style1'].'/zhaosheng');
?>