<?php
/**
 * @author codeMonkey09
 * qq:631872807
 */
defined('IN_IA') or exit('Access Denied');


class MON_BrandModuleSite extends WeModuleSite
{

    public $table_brand = "brand";

    public $table_brand_image = "brand_image";

    public $table_brand_note = "brand_note";

    public $table_brand_product = "brand_product";

    public $table_brand_message = "brand_message";

    /**
     * 品牌管理
     */
    public function doWebBrand()
    {
        global $_W, $_GPC;
        $operation = ! empty($_GPC['op']) ? $_GPC['op'] : 'display';
        
        if ($operation == 'edit') { // 添加或编辑
            $id = intval($_GPC['id']);
            if (! empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_brand) . " WHERE id = :id", array(
                    ':id' => $id
                ));
                if (empty($item)) {
                    message('抱歉，品牌删除或不存在！', '', 'error');
                }
            }
            if (checksubmit('bname')) {
                if (empty($_GPC['bname'])) {
                    message('请输入品牌名称!');
                }
                
                 if (empty($_GPC['btnName'])) {
                    message('请输入自定义按钮1名称!');
                }
                

                if (empty($_GPC['btnUrl'])) {
                    message('请输入自定义按钮1 链接地址!');
                }
                
                
                
                if (empty($_GPC['btnName2'])) {
                    message('请输入自定义按钮2名称!');
                }
                
                
                if (empty($_GPC['btnUrl2'])) {
                    message('请输入自定义按钮2 链接地址!');
                }
                
                if (empty($_GPC['btnName3'])) {
                    message('请输入自定义按钮3名称!');
                }
                
                
                if (empty($_GPC['btnUrl3'])) {
                    message('请输入自定义按钮3链接地址!');
                }

                if (empty($_GPC['pic'])) {
                    message('请输入品牌背景！');
                }
                
                if (empty($_GPC['intro'])) {
                    message('请输入品牌介绍');
                }
                
                if (empty($_GPC['tel'])) {
                   // message('请输入联系电话');
                }
                
                $data = array(
                    'weid' => $_W['uniacid'],
                    'bname' => $_GPC['bname'],
                    'intro' => htmlspecialchars_decode($_GPC['intro']),
                    'intro2' => htmlspecialchars_decode($_GPC['intro2']),
                    'video_name' => $_GPC['video_name'],
                    'video_url' => $_GPC['video_url'],
                    'createtime' => TIMESTAMP,
                    'pptname' => $_GPC['pptname'],
                    'ppt1' => $_GPC['ppt1'],
                    'ppt2' => $_GPC['ppt2'],
                    'ppt3' => $_GPC['ppt3'],
                    'pic' => $_GPC['pic'],
                    'tel' => $_GPC['tel'],
                    'btnName'=>$_GPC['btnName'],
                    'btnUrl'=>$_GPC['btnUrl'],
                    'btnName2'=>$_GPC['btnName2'],
                    'btnUrl2'=>$_GPC['btnUrl2'],
                    
                    'btnName3'=>$_GPC['btnName3'],
                    'btnUrl3'=>$_GPC['btnUrl3'],
                    'showMsg'=>$_GPC['showMsg']
                );
                if (! empty($id)) {
                    pdo_update($this->table_brand, $data, array(
                        'id' => $id
                    ));
                } else {
                    pdo_insert($this->table_brand, $data);
                }
                message('更新微品牌成功！', $this->createWebUrl('brand', array(

                    'op' => 'display'
                )), 'success');
            }
        } elseif ($operation == 'display') {
            $pindex = max(1, intval($_GPC['page']));
            
            $psize = 20;
            
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_brand) . " WHERE weid = '{$_W['uniacid']}'  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_brand) . " WHERE weid = '{$_W['uniacid']}'");
            $pager = pagination($total, $pindex, $psize);
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            
            pdo_delete($this->table_brand_image, array(
                'bid' => $id
            ));
            // 删除说明项
            pdo_delete($this->table_brand_note, array(
                'bid' => $id
            ));
            
            // 删除产品
            pdo_delete($this->table_brand_product, array(
                'bid' => $id
            ));
            
            // 删除活动
            pdo_delete($this->table_brand, array(
                'id' => $id
            ));
            
            message('删除成功！', referer(), 'success');
        }


        load()->func('tpl');

        include $this->template('brand');
    }

    public  function  str_murl($url){
        global $_W;
        return $_W['siteroot'].'app'.str_replace('./','/',$url);


    }

    /**
     * 说明项
     */
    public function doWebNote()
    {
        global $_W, $_GPC;
        $operation = ! empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $bid = $_GPC['bid'];
        $id = $_GPC['id'];
        $brand = pdo_fetch("SELECT * FROM " . tablename($this->table_brand) . " WHERE id = :id", array(
            ':id' => $bid
        ));
        
        if ($operation == 'display') { // 说明项
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_brand_note) . " WHERE bid = '$bid'");
        } elseif ($operation == 'edit') { // 说明项
            
            if (! empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_brand_note) . " WHERE id = :id", array(
                    ':id' => $id
                ));
                if (empty($item)) {
                    message('抱歉，说明删除或不存在！', '', 'error');
                }
            }
            if (checksubmit('submit')) {
                if (empty($_GPC['title'])) {
                    message('请输入说明项名称!');
                }
                if (empty($_GPC['note'])) {
                    message('请输入说说明项内容！');
                }
                
                $data = array(
                    
                    'title' => $_GPC['title'],
                    'note' => htmlspecialchars_decode($_GPC['note']),
                    'bid' => $bid
                );
                if (! empty($id)) {
                    pdo_update($this->table_brand_note, $data, array(
                        'id' => $id
                    ));
                } else {
                    pdo_insert($this->table_brand_note, $data);
                }
                message('更新活动说明项成功！', $this->createWebUrl('note', array(

                    'op' => 'display',
                    'bid' => $bid
                )), 'success');
            }
        } elseif ($operation == 'delete') {
            
            pdo_delete($this->table_brand_note, array(
                'id' => $id
            )); // 删除日程表
            
            message('删除成功！', referer(), 'success');
        }
        
        include $this->template("note");
    }

    /**
     * 图片
     */
    public function doWebImage()
    {
        global $_W, $_GPC;
        $operation = ! empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $bid = $_GPC['bid'];
        $id = $_GPC['id'];
        $brand = pdo_fetch("SELECT * FROM " . tablename($this->table_brand) . " WHERE id = :id", array(
            ':id' => $bid
        ));
        
        if ($operation == 'display') { // 日程显示
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_brand_image) . " WHERE bid = '$bid'");
        } elseif ($operation == 'edit') { // 日程添加
            
            if (! empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_brand_image) . " WHERE id = :id", array(
                    ':id' => $id
                ));
                if (empty($item)) {
                    message('抱歉，图片删除或不存在！', '', 'error');
                }
            }
            if (checksubmit('submit')) {
                if (empty($_GPC['title'])) {
                    message('请输入图片名称!');
                }
                if (empty($_GPC['url'])) {
                    message('请上传图片！');
                }
                
                $data = array(
                    
                    'title' => $_GPC['title'],
                    'url' => $_GPC['url'],
                    'bid' => $bid
                );
                if (! empty($id)) {
                    pdo_update($this->table_brand_image, $data, array(
                        'id' => $id
                    ));
                } else {
                    pdo_insert($this->table_brand_image, $data);
                }
                message('更新图片项成功！', $this->createWebUrl('image', array(

                    'op' => 'display',
                    'bid' => $bid
                )), 'success');
            }
        } elseif ($operation == 'delete') {
            
            pdo_delete($this->table_brand_image, array(
                'id' => $id
            )); // 删除日程表
            
            message('删除成功！', referer(), 'success');
        }

        load()->func('tpl');
        include $this->template("image");
    }

    
    /**
     * download
     */
    public function  doWebuserDownload(){
        
        global $_W, $_GPC;
        
        $bid = $_GPC['bid'];
        
        $brand = pdo_fetch("SELECT * FROM " . tablename($this->table_brand) . " WHERE id = :id", array(
            ':id' => $bid
        ));
         
         
        $tx=$brand['name']."微品牌数据";
        $tx = iconv("UTF-8", "GB2312", $tx);
        
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=".$brand['bname'].".xls");
        
        echo  $tx."\n";
        
        //输出内容如下：
        
        echo  iconv("UTF-8", "GB2312", "姓名"."\t");
        echo     iconv("UTF-8", "GB2312", "地址"."\t");
        echo    iconv("UTF-8", "GB2312", "电话"."\t");
      
        
         
        echo  "\n";
        
        
        $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_brand_message) . " WHERE bid = '{$bid}'  ORDER BY createtime DESC");
         
        foreach($list as $item){
            echo  iconv("UTF-8", "GB2312", $item['name']."\t");
            echo    iconv("UTF-8", "GB2312", $item['address']."\t");
        
            echo    iconv("UTF-8", "GB2312", $item['tel']."\t");
          
             
        
            echo  "\n";
        
        }
        
        
        
    }
    /**
     * 图片
     */
    public function doWebProduct()
    {
        global $_W, $_GPC;
        $operation = ! empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $bid = $_GPC['bid'];
        $id = $_GPC['id'];
        $brand = pdo_fetch("SELECT * FROM " . tablename($this->table_brand) . " WHERE id = :id", array(
            ':id' => $bid
        ));
        
        if ($operation == 'display') { // 日程显示
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_brand_product) . " WHERE bid = '$bid'");
        } elseif ($operation == 'edit') { // 日程添加
            
            if (! empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_brand_product) . " WHERE id = :id", array(
                    ':id' => $id
                ));
                if (empty($item)) {
                    message('抱歉，图片删除或不存在！', '', 'error');
                }
            }
            if (checksubmit('submit')) {
                if (empty($_GPC['pname'])) {
                    message('请输入产品名称!');
                }
                if (empty($_GPC['image'])) {
                    message('请上传图片！');
                }
                
                if (empty($_GPC['summary'])) {
                    message('请填写产品摘要！');
                }
                
                if (empty($_GPC['intro'])) {
                    message('请填写产品详情！');
                }
                
                $data = array(
                    
                    'pname' => $_GPC['pname'],
                    'image' => $_GPC['image'],
                    'summary' => $_GPC['summary'],
                    'intro' => htmlspecialchars_decode($_GPC['intro']),
                    'bid' => $bid
                );
                if (! empty($id)) {
                    pdo_update($this->table_brand_product, $data, array(
                        'id' => $id
                    ));
                } else {
                    pdo_insert($this->table_brand_product, $data);
                }
                message('更新产品成功！', $this->createWebUrl('product', array(

                    'op' => 'display',
                    'bid' => $bid
                )), 'success');
            }
        } elseif ($operation == 'delete') {
            
            pdo_delete($this->table_brand_product, array(
                'id' => $id
            )); // 删除日程表
            
            message('删除成功！', referer(), 'success');
        }

        load()->func('tpl');


        include $this->template("product");
    }

    public function doMobileBrand()
    {
        global $_W, $_GPC;
        
        $shareUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $shareUrl = urlencode($shareUrl);
        $bid = $_GPC['bid'];
        $this->updateVisitsCount($bid);
        
        $brand = pdo_fetch("SELECT * FROM " . tablename($this->table_brand) . " WHERE id = :id", array(
            ':id' => $bid
        ));
        
        $images = pdo_fetchall("SELECT * FROM " . tablename($this->table_brand_image) . " WHERE bid = :bid", array(
            ':bid' => $bid
        )); // 图片项
        
        $products = pdo_fetchall("SELECT * FROM " . tablename($this->table_brand_product) . " WHERE bid = :bid", array(
            ':bid' => $bid
        )); // 产品项
        
        $notes = pdo_fetchall("SELECT * FROM " . tablename($this->table_brand_note) . " WHERE bid = :bid", array(
            ':bid' => $bid
        )); // 说明项
        
        include $this->template("mobile_brand");
    }

    /**
     * 图片详细信息
     */
    public function doMobileimageDetail()
    {
        global $_W, $_GPC;
        $bid = $_GPC['bid'];
        $id = $_GPC['id'];
        
        $image = pdo_fetch("SELECT * FROM " . tablename($this->table_brand_image) . " WHERE id = :id", array(
            ':id' => $id
        ));
        
        include $this->template("image_detail");
    }

    /**
     * 长篇详细
     */
    public function doMobileproductDetail()
    {
        global $_W, $_GPC;
        $bid = $_GPC['bid'];
        $id = $_GPC['id'];
        
        $product = pdo_fetch("SELECT * FROM " . tablename($this->table_brand_product) . " WHERE id = :id", array(
            ':id' => $id
        ));
        
        include $this->template("product_detail");
    }

    public function doWebQuery()
    {
        global $_W, $_GPC;
        $kwd = $_GPC['keyword'];
        $sql = 'SELECT * FROM ' . tablename($this->table_brand) . ' WHERE `weid`=:weid AND `bname` LIKE :bname';
        $params = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':bname'] = "%{$kwd}%";
        $ds = pdo_fetchall($sql, $params);
        foreach ($ds as &$row) {
            $r = array();
            $r['bname'] = $row['bname'];
            $r['intro'] = $row['intro'];
            $r['pic'] = $row['pic'];
            $r['id'] = $row['id'];
            $row['entry'] = $r;
        }
        include $this->template('query');
    }

    /**
     * 留言提交
     */
    public function doMobileSubmitMsg()
    {
        global $_W, $_GPC;
        
        $bid = $_GPC['bid'];
        
        $uname = $_GPC['uname'];
        $address=$_GPC['address'];
        
        $tel = $_GPC['tel'];
        
        $content = $_GPC['content'];
        
        $brand = pdo_fetch("SELECT * FROM " . tablename($this->table_brand) . " WHERE id = :id", array(
            ':id' => $bid
        ));
        
        $msg = pdo_fetch("SELECT * FROM " . tablename($this->table_brand_message) . " WHERE bid = :bid and tel=:tel", array(
            ':bid' => $bid,
            ':tel' => $tel
        ));
        
        if (empty($brand)) { // 品牌不存在
            echo "501";
            exit();
        }
        
        if (! empty($msg)) { // 手机已注册
            echo "502";
            exit();
        }
        
        $data = array(
            'bid' => $bid,
            'name' => $uname,
            'tel' => $tel,
            'content' => $content,
            'createtime' => TIMESTAMP,
            'address'=>$address
        )
        ;
        
        pdo_insert($this->table_brand_message, $data);
        
        echo "200";
        
        ;
    }
    
    /**]
     * 用户留言
     */
    public function  doWebuserMsg(){
        
        global $_W, $_GPC;
        $operation = ! empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $bid = $_GPC['bid'];
        $id = $_GPC['id'];
        $brand = pdo_fetch("SELECT * FROM " . tablename($this->table_brand_message) . " WHERE id = :id", array(
            ':id' => $bid
        ));
        
        if ($operation == 'display') { // 说明项
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_brand_message) . " WHERE bid = '$bid'");
        } elseif ($operation == 'edit') { // 说明项
        
            if (! empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_brand_message) . " WHERE id = :id", array(
                    ':id' => $id
                ));
                if (empty($item)) {
                    message('抱歉，说明删除或不存在！', '', 'error');
                }
            }
            
        } elseif ($operation == 'delete') {
        
            pdo_delete($this->table_brand_message, array(
            'id' => $id
            )); // 删除日程表
        
            message('删除成功！', referer(), 'success');
        }
        
        include $this->template("msg");
        
        
    }

    /**
     * 更新浏览次数
     */
    public function updateVisitsCount($id)
    {
        $brand = pdo_fetch("SELECT * FROM " . tablename($this->table_brand) . " WHERE id = :id", array(
            ':id' => $id
        ));
        
        $vCount = intval($brand['visitsCount']) + 1;
        $data = array(
            'visitsCount' => $vCount
        );
        
        if (! empty($id)) {
            pdo_update($this->table_brand, $data, array(
                'id' => $id
            ));
        }
    }
}