<?php
	set_time_limit(0); //解除超时限制	
	$queue = new queue();
	$queue->queueMain();
	exit("success");
