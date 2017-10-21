<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc微站定义
 *
 * @author 微赞
 * @url
 */
defined('IN_IA') or exit('Access Denied');

abstract class SupermanCreditmallTask {
    /*
     * 说明：申请任务前调用，可自定义相关处理（如：检查是否有资格申请）
     * 返回值：
     *      true    允许申请任务
     *      array   错误信息数组(调用：global.func.php=>function error)
     */
    public function apply_before($task) {
        //TODO
        return true;
    }

    /*
     * 说明：申请任务后调用，可自定义相关处理（如：展示任务相关说明）
     * 返回值：
     *      无，如有页面跳转等需求可自行设置
     */
    public function apply_after($task) {
        //TODO
        return;
    }

    /*
     * 说明：检查任务进度
     * 返回值：
     *      进度值，如：100%
     */
    public function progress($task) {
        //TODO
        return;
    }

    /*
     * 说明：任务完成
     * 返回值：
     *      进度值，如：100%
     */
    public function complete($task) {
        //TODO
        return;
    }
}