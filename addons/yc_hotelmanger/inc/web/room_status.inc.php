<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

global $_GPC;
global $_W;
$hotelid = $_GPC['hotelid'];
$ac = $_GPC['ac'];

if ($ac == 'getDate') {
    if (empty($_GPC['start']) || empty($_GPC['end'])) {
        exit(json_encode(array('result' => 0, 'error' => '请选择时间')));
    }


    $btime = strtotime($_GPC['start']);
    $etime = strtotime($_GPC['end']);
    $days = ceil(($etime - $btime) / 86400);
    $pagesize = 10;
    $totalpage = ceil($days / $pagesize);
    $page = intval($_GPC['page']);

    if ($totalpage < $page) {
        $page = $totalpage;
    } else if ($page <= 1) {
        $page = 1;
    }


    $currentindex = ($page - 1) * $pagesize;
    $start = date('Y-m-d', strtotime(date('Y-m-d') . '+' . $currentindex . ' day'));
    $btime = strtotime($start);
    $etime = strtotime(date('Y-m-d', strtotime($start . ' +' . $pagesize . ' day')));
    $date_array = array();
    $date_array[0]['date'] = $start;
    $date_array[0]['day'] = date('j', $btime);
    $date_array[0]['time'] = $btime;
    $date_array[0]['month'] = date('m', $btime);
    $i = 1;

    while ($i <= $pagesize) {
        $date_array[$i]['time'] = $date_array[$i - 1]['time'] + 86400;
        $date_array[$i]['date'] = date('Y-m-d', $date_array[$i]['time']);
        $date_array[$i]['day'] = date('j', $date_array[$i]['time']);
        $date_array[$i]['month'] = date('m', $date_array[$i]['time']);
        ++$i;
    }

    $params = array();
    $sql = 'SELECT r.* FROM ' . tablename('hotel2_room') . 'as r';
    $sql .= ' WHERE 1 = 1';
    $sql .= ' AND r.hotelid = ' . $hotelid;
    $sql .= ' AND r.weid = ' . $weid;
    $list = pdo_fetchall($sql, $params);

    foreach ($list as $key => $value) {
        $sql = 'SELECT * FROM ' . tablename('hotel2_room_price');
        $sql .= ' WHERE 1 = 1';
        $sql .= ' AND roomid = ' . $value['id'];
        $sql .= ' AND roomdate >= ' . $btime;
        $sql .= ' AND roomdate < ' . ($etime + 86400);
        $item = pdo_fetchall($sql);

        if ($item) {
            $flag = 1;
        } else {
            $flag = 0;
        }

        $list[$key]['price_list'] = array();

        if ($flag == 1) {
            $i = 0;

            while ($i <= $pagesize) {
                $k = $date_array[$i]['time'];

                foreach ($item as $p_key => $p_value) {
                    if ($p_value['roomdate'] == $k) {
                        $list[$key]['price_list'][$k]['status'] = $p_value['status'];

                        if (empty($p_value['num'])) {
                            $list[$key]['price_list'][$k]['num'] = '不限';
                        } else if ($p_value['num'] == -1) {
                            $list[$key]['price_list'][$k]['num'] = '不限';
                        } else {
                            $list[$key]['price_list'][$k]['num'] = $p_value['num'];
                        }

                        $list[$key]['price_list'][$k]['roomid'] = $value['id'];
                        $list[$key]['price_list'][$k]['hotelid'] = $hotelid;
                        $list[$key]['price_list'][$k]['has'] = 1;
                        break;
                    }
                }

                if (empty($list[$key]['price_list'][$k])) {
                    $list[$key]['price_list'][$k]['num'] = '不限';
                    $list[$key]['price_list'][$k]['status'] = 1;
                    $list[$key]['price_list'][$k]['roomid'] = $value['id'];
                    $list[$key]['price_list'][$k]['hotelid'] = $hotelid;
                }


                ++$i;
            }
        } else {
            $i = 0;

            while ($i <= $pagesize) {
                $k = $date_array[$i]['time'];
                $list[$key]['price_list'][$k]['num'] = '不限';
                $list[$key]['price_list'][$k]['status'] = 1;
                $list[$key]['price_list'][$k]['roomid'] = $value['id'];
                $list[$key]['price_list'][$k]['hotelid'] = $hotelid;
                ++$i;
            }
        }
    }

    $data = array();
    $data['result'] = 1;
    ob_start();
    include $this->template('room_status_list');
    $data['code'] = ob_get_contents();
    ob_clean();
    exit(json_encode($data));
}


$startime = time();
$firstday = date('Y-m-01', time());
$endtime = strtotime(date('Y-m-d', strtotime($firstday . ' +1 month -1 day')));
include $this->template('room_status');
