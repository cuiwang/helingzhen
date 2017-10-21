<?php
/*
 * @copyright	Copyright (c) 2015 http://www.jakehu.me All rights reserved.
 * @license	    http://www.apache.org/licenses/LICENSE-2.0
 * @link	    http://www.jakehu.me
 * @author	    jakehu <jakehu1991@gmail.com>
 */

//----------------------------------
// 用户会员组管理模块定义
//----------------------------------
//
defined('IN_IA') or exit('Access Denied');

class User_groupsModule extends WeModule {

	public function fieldsFormDisplay($rid = 0) {

	}

	public function fieldsFormValidate($rid = 0) {
		return '';
	}

	public function fieldsFormSubmit($rid) {

	}

	public function ruleDeleted($rid) {

	}

	public function settingsDisplay($settings) {

	}
}