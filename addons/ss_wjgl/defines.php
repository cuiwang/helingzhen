<?php

if (! defined ( 'IN_IA' )) {
	exit ( 'Access Denied' );
}
define ( 'XSY_RESOURCE_DEBUG', false );
! defined ( 'XSY_RESOURCE_PATH' ) && define ( 'XSY_RESOURCE_PATH', IA_ROOT . '/addons/ss_wjgl/' );
! defined ( 'XSY_RESOURCE_INC' ) && define ( 'XSY_RESOURCE_INC', XSY_RESOURCE_PATH . 'inc/' );
! defined ( 'XSY_RESOURCE_CORE' ) && define ( 'XSY_RESOURCE_CORE', XSY_RESOURCE_INC . 'core/' );
! defined ( 'XSY_RESOURCE_URL' ) && define ( 'XSY_RESOURCE_URL', $_W ['siteroot'] . 'addons/ss_wjgl/' );
! defined ( 'XSY_RESOURCE_STATIC' ) && define ( 'XSY_RESOURCE_STATIC', XSY_RESOURCE_URL . 'template/static/' );
! defined ( 'XSY_RESOURCE_PREFIX' ) && define ( 'XSY_RESOURCE_PREFIX', 'XSY_RESOURCE_' );
! defined ( 'XSY_RESOURCE_ATTACHMENT' ) && define ( 'XSY_RESOURCE_ATTACHMENT', 'addons/ss_wjgl/attachment' );
