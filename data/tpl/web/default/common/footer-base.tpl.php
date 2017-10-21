<?php defined('IN_IA') or exit('Access Denied');?></div>
	<div class="container-fluid footer text-center" role="footer">	
		<div class="friend-link">
			<?php  if(empty($_W['setting']['copyright']['footerright'])) { ?>
				<a href="#">微信开发</a>&nbsp;&nbsp;
				<a href="#">微信应用</a>&nbsp;&nbsp;
				<a href="#">系统论坛</a>&nbsp;&nbsp;
			<?php  } else { ?>
				<?php  echo $_W['setting']['copyright']['footerright'];?>
			<?php  } ?>
		</div>
		<div><?php  if(empty($_W['setting']['copyright']['footerleft'])) { ?>Powered by <a href="#"><b>系统</b></a> v<?php echo IMS_VERSION;?> &copy; 2014-2015 <?php  } else { ?><?php  echo $_W['setting']['copyright']['footerleft'];?><?php  } ?></div>
	</div>
	<?php  if(!empty($_W['setting']['copyright']['statcode'])) { ?><?php  echo $_W['setting']['copyright']['statcode'];?><?php  } ?>
	<?php  if(!empty($_GPC['m']) && !in_array($_GPC['m'], array('keyword', 'special', 'welcome', 'default', 'userapi')) || defined('IN_MODULE')) { ?>
	<script>
		if(typeof $.fn.tooltip != 'function' || typeof $.fn.tab != 'function' || typeof $.fn.modal != 'function' || typeof $.fn.dropdown != 'function') {
			require(['bootstrap']);
		}
	</script>
	<?php  } ?>
	</div>
</body>
</html>
