<?php defined('IN_IA') or exit('Access Denied');?><div class="foot_linksbg">
<div class="foot_links">
<div class="links">
<a href="<?php  echo url('article/link-show/list');?>" target="_blank" title="友情链接"><h3>更多友情链接 >></h3></a>
<div class="links_con">
<?php  if(is_array($links)) { foreach($links as $link) { ?>
<a href="<?php  echo $link['siteurl'];?>" target="_blank" title="<?php  echo $link['title'];?>"><?php  echo $link['title'];?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
<?php  } } ?>
</div>
</div>
<div class="address">
<p><a href="#" title=""><?php  echo $_W['setting']['copyright']['company'];?></a></p>
<p>办公室：<?php  echo $_W['setting']['copyright']['address'];?></p>
</div>
<div class="link_btn">
<ul >
<li><a href="http://wpa.qq.com/msgrd?v=3&uin=<?php  echo $_W['setting']['copyright']['qq'];?>&site=qq&menu=yes" target="_blank"title="" class="link_qq"></a></li>
<li><a href="#" title="" class="link_sina"></a></li>
<li class="link_weixin_li"><a href="javasctipt:void(0);" title="" class="link_weixin"></a>
<div class="link_weixin_ewm">
<img src="<?php  if(!empty($_W['setting']['copyright']['ewm'])) { ?><?php  echo tomedia($_W['setting']['copyright']['ewm']);?><?php  } else { ?>./resource/weidongli/images/ewm.jpg<?php  } ?>" width="129" height="129" alt="">
</div>
</li>
</ul>
</div>
</div>
</div>
<div class="footbg">
<div class="foot">
<p class="z">
<?php  if(empty($_W['setting']['copyright']['footerleft'])) { ?> <em>WEIZAN</em>&nbsp--&copy; 2001-2014 <a href="/" target="_blank"><a href="/" target="_blank">微信</a>
<?php  } else { ?><?php  echo $_W['setting']['copyright']['footerleft'];?><?php  } ?>
</p>
<p class="y">
<?php  if(empty($_W['setting']['copyright']['footerright'])) { ?>
<strong><a href="/" target="_blank">微信CMS</a></strong>
&nbsp;
( <a href="http://www.miitbeian.gov.cn/" target="_blank">粤ICP备XXXXXXX号</a> )&nbsp;
<?php  } else { ?><?php  echo $_W['setting']['copyright']['footerright'];?><?php  } ?> &nbsp; &nbsp; <?php  if(!empty($_W['setting']['copyright']['statcode'])) { ?><?php  echo $_W['setting']['copyright']['statcode'];?><?php  } ?>
</p>
</div>
</div>
</div>
 </div>
 </div>
</body>
</html>