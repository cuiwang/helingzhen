<?php
global $_W,$_GPC;
$taskid = empty($_GET['taskid'])?0:intval($_GET['taskid']);
$table = 'meepo_bbs_task';
$tableuser = 'meepo_bbs_task_user';
$title = '任务大厅';

if(isset($_GPC['u'])){
	if(!is_numeric($_GPC['u'])){
		message('你是傻逼么，参数错误',referer(),error);
	}
}

if(isset($_GPC['view'])){
	$view = trim($_GPC['view']);
	if(!in_array($view, array('member','done'))){
		message('你是傻逼么，参数错误',referer(),error);
	}
}

if($taskid) {
	$task = pdo_fetch("SELECT * FROM ".tablename($table)." WHERE taskid = :taskid ",array(':taskid'=>$taskid));
	if(!$task) {
		message('没有找到相关数据',referer(),error);
	} else {
		$task['image'] = empty($task['image'])?tomedia('image/task.gif'):tomedia($task['image']);
	}
	if($task['starttime'] > time()) {
		message('本任务还没有开始，请耐心等待！',referer(),error);
	}
		
	if($_GET['view'] == 'member') {
		
		$start = intval($_GPC['start'])?intval($_GPC['start']):0;
		$sql = "SELECT s.* , main.dateline FROM "
				.tablename($tableuser)." main LEFT JOIN ".tablename('mc_members')." s ON s.uid = main.uid
				 WHERE main.taskid = :taskid AND main.isignore = :isignore ORDER BY main.dateline DESC limit $start,20";
		$params = array(':taskid'=>$taskid,':isignore'=>0);
		$lists = pdo_fetchall($sql,$params);
		
		foreach ($lists as $value){
			$fuids[] = $value['uid'];
			$list[] = $value;
		}
	} else {
		$sql = "SELECT * FROM ".tablename($tableuser)." WHERE uid = :uid AND taskid = :taskid";
		$params = array(':uid'=>$_W['member']['uid'],':taskid'=>$taskid);
		$usertask = pdo_fetch($sql,$params);
		
		if($usertask) {
			if($task['maxnum'] && $task['maxnum']<=$task['num']) {
				$task['done'] = 1;
			} else {
				$allownext = 0;
				$lasttime = $usertask['dateline'];
				if($task['nexttype'] == 'day') {
					if(date('Ymd', time()) != date('Ymd', $lasttime)) {
						$allownext = 1;
					}
				} elseif ($task['nexttype'] == 'hour') {
					if(date('YmdH', time()) != date('YmdH', $lasttime)) {
						$allownext = 1;
					}
				} elseif ($task['nexttime']) {
					if(time()-$lasttime >= $task['nexttime']) {
						$allownext = 1;
					}
				}
				if($allownext) {
					$task['done'] = 0;
				} else {
					$task['done'] = 1;
				}
			}
			$task['dateline'] = $usertask['dateline'];
			$task['ignore'] = $task['done']?$usertask['isignore']:0;
		}
		
		if($task['done'] && $task['ignore'] && $_GET['op']=='redo') {
			pdo_delete($tableuser,array('uid'=>$_W['member']['uid'],'taskid'=>$taskid));
			message('进行的操作完成了',$this->createMobileUrl('task',array('taskid'=>$taskid)));
		}
		
		$_W['task_maxnum'] = $_W['task_available'] = 0;
		if(empty($task['done'])) {
			$task['maxnum'] = intval($task['maxnum']);
			if($task['maxnum'] && $task['maxnum'] <= $task['num']) {
				$task['done'] = 1;
				$_W['task_maxnum'] = 1;
			} elseif(empty($task['available'])) {
				$task['done'] = 1;
				$_W['task_available'] = 1;
			}
		}
		if(empty($task['done'])) {
			$task['result'] = '';
			$task['guide'] = '';
			$setarr = array(
				'uid' => $_W['member']['uid'],
				'username' => $_W['member']['nickname'],
				'taskid' => $task['taskid'],
				'dateline' => time(),
				'credit' => $task['credit']
			);
				
			if($_GET['op'] == 'ignore') {
				$setarr['isignore'] = 1;
				pdo_insert($tableuser,$setarr);
				message('进行的操作完成了',$this->createMobileUrl('task',array('taskid'=>$taskid)));
			}
			include_once(INC_PATH.'core/task/'.$task['filename']);
			
			if($task['done']) {
				$task['dateline'] = time();
				$sql = "SELECT * FROM ".tablename($tableuser)." WHERE taskid = :taskid AND uid = :uid";
				$params = array(':taskid'=>$setarr['taskid'],':uid'=>$setarr['uid']);
				$is_exit = pdo_fetch($sql,$params);
				if(empty($is_exit)){
					pdo_insert($tableuser,$setarr);
				}else{
					pdo_update($tableuser,$setarr,array('taskid'=>$setarr['taskid'],'uid'=>$setarr['uid']));
				}
				
				
				pdo_query("UPDATE ".tablename($table)." SET num=num+1 WHERE taskid='$task[taskid]'");
				
				if($task['credit']) {
					mc_credit_update($_W['member']['uid'], 'credit1',$task['credit'],array($_W['member']['uid'],'完成'.$task['name'],'赠送'));
					
				}
			}
		} else {
			include_once(INC_PATH.'core/task/'.$task['filename']);
		}
	}
	$actives = array('do' => ' class="active"');
	
} else {
	$done_per = $todo_num = $all_num = 0;
	$usertasks = array();
	$taskids = array();
	$list = pdo_fetchall("SELECT * FROM ".tablename($tableuser)." WHERE uid='{$_W['member']['uid']}'");
	foreach ($list as $value){
		$usertasks[$value['taskid']] = $value;
		$taskids[$value['taskid']] = $value['taskid'];
		$done_num++;
	}
	$tasklist = array();
	$query = '';
	if($_GET['view'] == 'done') {
		if($taskids) {
			$list = pdo_fetchall('SELECT * FROM '.tablename($table)." WHERE taskid IN (".implode(',',$taskids).") ORDER BY displayorder");
			foreach ($list as $value){
				$value['image'] = empty($value['image'])?'image/task.gif':$value['image'];
				$value['done'] = 1;
				$value['ignore'] = $usertasks[$value['taskid']]['isignore'];
				$tasklist[$value['taskid']] = $value;
			}
			
		}
	} else {
		$list = pdo_fetchall('SELECT * FROM '.tablename($table)." WHERE uniacid = '{$_W['uniacid']}' AND available='1' ORDER BY displayorder");
		foreach ($list as $value){
			if((empty($value['maxnum']) || $value['maxnum']>$value['num']) &&
				(empty($value['starttime']) || $value['starttime'] <= time()) &&
				(empty($value['endtime']) || $value['endtime'] >= time())) {
			
					$all_num++;
			
					$allownext = 0;
					$lasttime = $usertasks[$value['taskid']]['dateline'];
					if(empty($lasttime)) {
						$allownext = 1;
					} elseif($value['nexttype'] == 'day') {
						if(date('Ymd', time()) != date('Ymd', $lasttime)) {
							$allownext = 1;
						}
					} elseif ($value['nexttype'] == 'hour') {
						if(date('YmdH', time()) != date('YmdH', $lasttime)) {
							$allownext = 1;
						}
					} elseif ($value['nexttime']) {
						if(time()-$lasttime >= $value['nexttime']) {
							$allownext = 1;
						}
					}
					if($allownext) {
						$todo_num++;
						$value['image'] = empty($value['image'])?'image/task.gif':$value['image'];
						$value['done'] = 0;
						$tasklist[$value['taskid']] = $value;
					}
			}
		}
		
		$done_per = empty($all_num)?100:intval(($all_num-$todo_num)*100/$all_num);
	}
	$taskspacelist = array();
	$list = pdo_fetchall("SELECT * FROM ".tablename($tableuser)." WHERE isignore='0' ORDER BY dateline DESC LIMIT 0,20");
	foreach ($list as $value){
		$user = mc_fetch($value['uid'],array('nickname','avatar'));
		$value['avatar'] = $user['avatar'];
		$sql = "SELECT name FROM ".tablename($table)." WHERE taskid = :taskid";
		$params = array(':taskid'=>$value['taskid']);
		$value['taskname'] = pdo_fetchcolumn($sql,$params);
		if($value['taskname']) {
			$taskspacelist[$value['uid']] = $value;
		}
	}
	if($_GET['view'] == 'done') {
		$actives = array('done' => ' class="assertive"');
	} else {
		$actives = array('task' => ' class="assertive"');
	}
}
$space = mc_fetch($_W['member']['uid']);
include $this->template('default/templates/task/task');
?>