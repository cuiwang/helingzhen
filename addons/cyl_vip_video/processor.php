<?php
/**
 * 便利店模块处理程序
 *
 * @author Gorden
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
include IA_ROOT . "/addons/cyl_vip_video/model.php";
class Cyl_vip_videoModuleProcessor extends WeModuleProcessor{
    public function respond(){
    	global $_W, $_GPC;
    	$content = $this->message['content'];
        if(!$this->inContext) {
			$news = '请发送您想看的电影，电视剧，综艺节目，动漫的名称关键字，例如“速度与激情”，“奇葩说”（忽略引号），如需重新搜索，请再次回复或者点击菜单按钮';	
			$this->beginContext(1800);
			return $this->respText($news);	
			// 如果是按照规则触发到本模块, 那么先输出提示问题语句, 并启动上下文来锁定会话, 以保证下次回复依然执行到本模块
		} else {
						
	        // 这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码 
			if ($content) {
				$where = ' WHERE uniacid = :uniacid '; 			
				$where .= ' AND title LIKE :title ';
				$params[':uniacid'] = $_W['uniacid'];	
				$params[':title'] = "%{$content}%";				
				$sql = ' SELECT * FROM '.tablename('cyl_vip_video_manage').$where.' ORDER BY id DESC ';			
				$search = pdo_fetchall($sql, $params, 'id'); 	
				$list = caiji_list($content);
				$list = array_merge($search,$list);
			}
			if (empty($list)) {
				return $this->respText('未搜索到关键词，请重新输入全称');	
			}			
		    $news = array();
		    $i = 1;		    
	        foreach ($list as $key=>$item) {
	        	if ($item['type'] == '[动漫]') {
	        		$op = 'dongman';
	        	}elseif ($item['type'] == '[电视剧]') {
	        		$op = 'dianshi';
	        	}elseif ($item['type'] == '[综艺]') {
	        		$op = 'zongyi';
	        	}else{
	        		$op = 'dianying';
	        	}
	        	if ($item['title'] && $item['btn'] != '暂无播放资源') {
	        		if ($i <= 8) {
	        			$news[] = array(
		                'title' => strip_tags($item['type'].$item['title']),
		                'description' => strip_tags($item['p1']),
		                'url' => $this->createMobileUrl('detail',array('op'=>$op,'url'=>$item['link'],'id'=>$item['id'])),
		                'picurl' => $item['img'] ? $item['img'] : tomedia($item['thumb'])
		           	 	);
	        		}
	        		$i++;   
	        	}
	        }
	        $this->endContext();	          	
	        
        }
        return $this->respNews($news);
    }
}