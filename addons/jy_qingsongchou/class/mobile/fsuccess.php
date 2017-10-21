<?php


$wb_title = empty($this->sys['weibo_content'])?$config['name']:$this->sys['weibo_content'];
$wb_url = empty($this->sys['weibo_url'])?$this->createMobileUrl('detail',array('id'=>$_GPC['id'])):$this->sys['weibo_url'];
$wb_pic = empty($this->sys['weibo_pic'])?$thumb[0]:$this->sys['weibo_pic'];

$qqzon_title = empty($this->sys['qqzon_content'])?$config['name']:$this->sys['qqzon_content'];
$qqzon_url = empty($this->sys['qqzon_url'])?$this->createMobileUrl('detail',array('id'=>$_GPC['id'])):$this->sys['qqzon_url'];
$qqzon_pic = empty($this->sys['qqzon_pic'])?$thumb[0]:$this->sys['qqzon_pic'];

$qqweibo_title = empty($this->sys['qqweibo_content'])?$config['name']:$this->sys['qqweibo_content'];
$qqweibo_url = empty($this->sys['qqweibo_url'])?$this->createMobileUrl('detail',array('id'=>$_GPC['id'])):$this->sys['qqweibo_url'];
$qqweibo_pic = empty($this->sys['qqweibo_pic'])?$thumb[0]:$this->sys['qqweibo_pic'];

$qq_title = empty($this->sys['qq_content'])?$config['name']:$this->sys['qq_content'];
$qq_url = empty($this->sys['qq_url'])?$this->createMobileUrl('detail',array('id'=>$_GPC['id'])):$this->sys['qq_url'];
$qq_pic = empty($this->sys['qq_pic'])?$thumb[0]:$this->sys['qq_pic'];

$_share['weibo'] = "http://v.t.sina.com.cn/share/share.php?title=".$wb_title."&url=".urlencode($wb_url).'&content=utf-8&sourceUrl='.$wb_url.'&pic='.urlencode(tomedia($wb_pic));
$_share['qqzon'] = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?summary='.urlencode($qqzon_title).'&url='.$qqzon_url.'&pics='.tomedia($qqzon_pic);
$_share['qqweibo']= 'http://v.t.qq.com/share/share.php?title='.$qqweibo_title.'&url='.urlencode($qqweibo_url).'&pic='.tomedia($qqweibo_pic);
$_share['qq']='http://connect.qq.com/widget/shareqq/index.html?title='.$qq_title.'&url='.urlencode($qq_url)."&pics=".tomedia($qq_pic);

$dopost = $_GPC['dopost'];

     if($dopost=='success'){
       include $this->template('fsuccess');
     }
     else if($dopost=='pay'){
         if($this->modal=='pc'){
               $this->_TplHtml('支付成功',$_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('id'=>$_GPC['fid'])),2),'success');
         }else{
           include $this->template('paysuccess');
         }

     }else if($dopost=='update'){
       include $this->template('updatesuccess');
     }else if($dopost=='shenhe'){
       include $this->template('shenhe');
     }else if($dopost=='shenheing'){
       include $this->template('shenheing');
     }
     else{
      include $this->template('fdel');
     }


 ?>
