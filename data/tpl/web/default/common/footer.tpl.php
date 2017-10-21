<?php defined('IN_IA') or exit('Access Denied');?>			</div>
		</div>
	</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer-base', TEMPLATE_INCLUDEPATH)) : (include template('common/footer-base', TEMPLATE_INCLUDEPATH));?>
