<?php
defined ( 'IN_IA' ) or exit('Access Denied,your ip is:'.$_SERVER['REMOTE_ADDR'].',We have recorded the source of attack.');
include $this->template("exchangesuccess");