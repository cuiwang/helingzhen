<?php
global $_GPC, $_W;
        $rid = intval($_GPC['rid']);

        @header("content-Type: text/html; charset=utf-8"); //语言强制
        date_default_timezone_set('PRC');//时区设置
        //require_once 'medoo.php';
        $pagenum = 200;//每次200条

        $count = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('haoman_dpm_whyerror'));

//        $count=$database->count('vote_record_memory',array('id[<]'=>2817701));//计算要取得数据总数
        $page_count = ceil($count / $pagenum);//计算循环总页数
        //echo $page_count;exit;
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="红包失败数据.csv"');
        header('Cache-Control: max-age=0');
// 打开PHP文件句柄，php://output 表示直接输出到浏览器
        $fp = fopen('php://output', 'a');
// 输出列名信息
        $head = array('id', 'openid', '金额', '失败原因','时间');
        foreach ($head as $i => $v) {
            // CSV的Excel支持GBK编码，一定要转换，否则乱码
            $head[$i] = iconv('utf-8', 'gbk', $v);
        }
        // 将数据通过fputcsv写到文件句柄
        fputcsv($fp, $head);

        for($i=0;$i<$page_count;$i++){
            $data = pdo_fetchall("select * from " . tablename('haoman_dpm_whyerror') . "limit ".($i)*$pagenum.','.$pagenum);
//            $strsql="select * from vote_record_memory where id<2817701 limit ".($i)*$pagenum.','.$pagenum;
//            $data=$database->query($strsql)->fetchAll();

            foreach($data as $k=>$val){
                $row=array();//初始化行数据
                $row[0]=iconv('utf-8', 'gbk', $val['id']);
                $row[1]=iconv('utf-8', 'gbk', $val['from_user']);
                $row[2]=iconv('utf-8', 'gbk', $val['money']);
                $row[3]=iconv('utf-8', 'gbk', $val['why_error']);
                $row[4]=iconv('utf-8', 'gbk', date('Y-m-d H:i:s', $val['createtime']));
                fputcsv($fp, $row); //按行写入文件
            }

            ob_flush();
            flush();
        }
