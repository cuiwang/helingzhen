<?php
/**
 * 线下积分宝模块处理程序
 *
 * @author wenjing
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
define('OD_ROOT', IA_ROOT . '/addons/zm_jfb');

class Zm_jfbModuleProcessor extends WeModuleProcessor {
	public function respond() {
	    
	    global $_W,$_GPC;	

	    
	    $rid = $this->rule;		
	    $uniacid = $_W['uniacid'];						
	    $repeat = 0;						
	    $openid = $this->message['from'];	
	    
	    
	    
	    load()->model('mc');		
	    $uid = mc_openid2uid($openid);								
	    
	    $rsql = "select id from ".tablename('rule')." where id=".$rid;		
	    $rlist = pdo_fetch($rsql);						
	    if(empty($rlist)){			
	        return $this->respText("二维码已失效！");						
	        exit();		
	    }	
	    
	    

	    $ssql = "select msg_title,msg_img,msg_url,msg_con,addrepeat from ".tablename('xjfb_setting')." where weid=".$uniacid;		
	    $slist = pdo_fetch($ssql);								
	    if(!empty($slist['addrepeat'])){						
	        $repeat = $slist['addrepeat'];				
	    }						
	    
	    $jilu_sql = "select id,addtime from ".tablename('xjfb_jifenjilu')." where rid=".$rid." and openid = '".$openid."'  ";				
	    $jilu_list = pdo_fetch($jilu_sql);	

	    
	    if(empty($jilu_list) || $repeat == 1){		
	        $user = pdo_fetch('select nickname,credit1,credit2 from '.tablename('mc_members').' where uid = :uid',array(":uid"=>$uid));
	        
	         
	        $content = $this->message['content'];									
	        $content = str_replace("zm_jfb","",$content);	
	        
	        $jifen_sql = "select j.id,j.jifennumber,j.codetype,j.jftype,j.content from ".tablename('xjfb_jifenlist')." as j left join ".tablename('xjfb_qrcode')." as q on j.id = q.rid where j.ruleid=".$rid." and j.weid =".$uniacid;						
	        $jifen_list = pdo_fetch($jifen_sql);									
	        $content = $jifen_list['jifennumber'];	

	        
	        if($jifen_list['codetype'] == 1){	

	            $cqsql = "SELECT mendian,dianyuan FROM " . tablename('xjfb_jifenlist') . " WHERE weid = :weid and ruleid = :ruleid";
	            $cqlist = pdo_fetch($cqsql,array(':weid'=>$uniacid,':ruleid'=>$rid));
	            
	            																
	            if(empty($user)){					
	                return $this->respText("充值异常,请告知店员重新生成二维码");				
	            }else{		
	                $sql = "select m.number1,m.template,m.tempmsg,m.tempcontent,y.weixin,y.weixin,y.hsyue,y.hsjifen from ".tablename('xjfb_mendian')." as m left join ".tablename('xjfb_yuangong')." as y on m.id = y.mendian where m.weid = :weid and m.id = :id GROUP BY m.id";
	                $mdlist = pdo_fetch($sql,array(':weid'=>$uniacid,':id'=>$cqlist['mendian']));
	                    
	                    
	                if($jifen_list['jftype'] == 0){
	                    $jflist = pdo_fetchcolumn("select SUM(yuenum) as jifen from ".tablename('xjfb_jifenjilu')." where weid = :weid and mendian = :mendian and codetype = 1 and jftype = 0 and yuenum>0",array(":weid"=>$uniacid,":mendian"=>$cqlist['mendian']));
	                    
	                    	
	                    $syjifen = $mdlist['number1'] - $jflist;
	                    
	                    if($mdlist['number1'] <= $jflist || $syjifen < $jifen_list['jifennumber']){
	                        return $this->respText("门店余额不足,联系商户重新扫描！");
	                    }
	                    mc_credit_update($uid,'credit2',$jifen_list['jifennumber'],array($uid,"增加余额".$jifen_list['jifennumber']));
	                }
	                else{
	                    
	                    if($jifen_list['jifennumber'] > $user['credit2'])
	                        return $this->respText("余额不足,您还有".$user['credit2']."余额");
	                    else{
    	                    if($user['credit2'] < $jifen_list['jifennumber']){
    	                        return $this->respText("余额不足,扣除失败!剩余:".$user['credit2']);
    	                    }
    	                    mc_credit_update($uid,'credit2','-'.$jifen_list['jifennumber'],array($uid,"减少余额".$jifen_list['jifennumber']));
	                    }
	                }
	                							
	                
										
					$jilu_data = array(							
					    'weid' => $uniacid,							
					    'title' => '充值',							
					    'yuenum' => $jifen_list['jifennumber'],							
					    'rid' => $rid,							
					    'addtime1' => TIMESTAMP,							
					    'mcid' => $uid,							
					    'mendian' => empty($cqlist)?0:$cqlist['mendian'],							
					    'dianyuan' => empty($cqlist)?0:$cqlist['dianyuan'],														
					    'openid' => $openid,							
					    'jftype' => $jifen_list['jftype'],							
					    'codetype' => 1					
					    
					);					
					pdo_insert('xjfb_jifenjilu',$jilu_data);																	
														
					if($mdlist['hsyue']==1&&$jifen_list['jftype']==1){												
					    pdo_update("xjfb_mendian",array('number1'=>($mdlist['number1'] + $jifen_list['jifennumber']),'numtime'=>TIMESTAMP),array('id'=>$cqlist['mendian']));												
					        
					    $r_data = array(
							'weid' => $uniacid,
							'mendian' => empty($cqlist)?0:$cqlist['mendian'],
							'yuangong' => empty($cqlist)?0:$cqlist['dianyuan'],
							'mcid' => $uid,
							'type' => 1,								
					        'number' => $jifen_list['jifennumber'],								
					        'addtime' => TIMESTAMP,
						);
						pdo_insert('xjfb_recover',$r_data);					
					}
																
					$ssql = "SELECT * FROM " . tablename('xjfb_setting') . " WHERE weid = :weid";					
					$setting = pdo_fetch($ssql,array(':weid'=>$uniacid));	

					$qsql = "SELECT * FROM " . tablename('xjfb_qrcode') . " WHERE weid = :weid and rid = :rid";
					$qlist = pdo_fetch($qsql,array(':weid'=>$uniacid,':rid'=>$rid));
					if($repeat == 0){					
					    pdo_delete("rule",array('id'=>$rid));					
					    pdo_delete("rule_keyword",array('rid'=>$rid));										
					    pdo_delete('xjfb_qrcode',array('rid'=>$rid));
					    pdo_delete('qrcode',array('keyword'=>'zm_jfb'.$jifen_list['id']));
					}																
					
					if(!empty($setting)){
						$str = str_replace('{nickname}',$user['nickname'],$setting['tishi']);
						$str = str_replace('{jifen}',$jifen_list['jifennumber'],$str);						
						$str = str_replace('积分','余额',$str);

						
						if($jifen_list['jftype'] == 0){
						    $keyword = "【".$setting['title']."】余额充值通知";
						    
							$str = str_replace('获得','充值',$str);
							$str = str_replace('{sum}',($user['credit2'] + $jifen_list['jifennumber']),$str);
						}
						else{
						    $keyword = "【".$setting['title']."】余额扣除通知";
						    
							$str = str_replace('获得','扣除',$str);
							$str = str_replace('{sum}',($user['credit2'] - $jifen_list['jifennumber']),$str);
						}
					}else{
						if($jifen_list['jftype'] == 0)
							$str = "亲爱的".$user['nickname'].",为您充值".$jifen_list['jifennumber'].",您有".($user['credit2']+$jifen_list['jifennumber'])."余额";						
						else
							$str = "亲爱的".$user['nickname'].",扣除您".$jifen_list['jifennumber']."余额,您有".($user['credit2']-$jifen_list['jifennumber'])."余额";
					}	
					$this->sendTemplate($mdlist['weixin'], $keyword, $mdlist['tempmsg'], $mdlist['tempcontent'], $mdlist['template']);
					if($_W['account']['level'] >= ACCOUNT_SUBSCRIPTION_VERIFY) {
					    $info = "【{$_W['account']['name']}】通知:\n";
					    $info .=$str;
					
					    $account_api = WeAccount::create();
					
					    $message = array(
					        'touser' => $openid,
					        'msgtype' => 'text',
					        'text' => array('content' => urlencode($info))
					    );
					    $status = $account_api->sendCustomNotice($message);
					    if (is_error($status)) {
					        message('发送失败，原因为' . $status['message']);
					    }
					
					}
					
					if(!empty($slist['msg_title'])){
					    return $this->respNews(array(
					
					        'Title' => $slist['msg_title'],
					
					        'Description' => $slist['msg_con'],
					
					        'PicUrl' => $slist['msg_img'],
					
					        'Url' => $slist['msg_url'],
					
					    ));
					}												
	            }						
	        }else{												
	            if(empty($content)||empty($jifen_list)){										
	                return $this->respText("积分添加异常,请告知店员重新生成二维码");								
	            }else {					
	                
	                $cqsql = "SELECT mendian,dianyuan FROM " . tablename('xjfb_jifenlist') . " WHERE weid = :weid and ruleid = :ruleid";
	                $cqlist = pdo_fetch($cqsql,array(':weid'=>$uniacid,':ruleid'=>$rid));
	                														
	                if(empty($user)){												
	                    return $this->respText("积分添加异常,请告知店员重新生成二维码");										
	                }else{	
	                    $sql = "select m.number,m.template,m.tempmsg,m.tempcontent,y.weixin,y.weixin,y.hsjifen,y.hsyue from ".tablename('xjfb_mendian')." as m left join ".tablename('xjfb_yuangong')." as y on m.id = y.mendian where m.weid = :weid and m.id = :id GROUP BY m.id";
	                    $mdlist = pdo_fetch($sql,array(':weid'=>$uniacid,':id'=>$cqlist['mendian']));
	                    
	                    
	                    if($jifen_list['jftype'] == 0){
                        
	                        $jflist = pdo_fetchcolumn("select SUM(jifen) as jifen from ".tablename('xjfb_jifenjilu')." where weid = :weid and mendian = :mendian and codetype = 0 and jftype = 0 and jifen>0",array(":weid"=>$uniacid,":mendian"=>$cqlist['mendian']));
	                        $syjifen = $mdlist['number'] - $jflist;
	                        
	                        
	                        if( $mdlist['number'] <= $jflist || $syjifen < $jifen_list['jifennumber']){
	                            return $this->respText("门店积分不足,联系商户重新扫描！");
	                        }
	                        
	                        mc_credit_update($uid,'credit1',$jifen_list['jifennumber'],array($uid,"增加积分".$jifen_list['jifennumber']));		
	                    }										
	                    else{	
	                        if($jifen_list['jifennumber'] > $user['credit1'])
	                            return $this->respText("积分不足,您还有".$user['credit1']."积分");
	                        else{
    	                        if($user['credit1'] < $jifen_list['jifennumber']){															
    	                            return $this->respText("积分不足,扣除失败!剩余:".$user['credit1']);														
    	                        }														
    	                        mc_credit_update($uid,'credit1','-'.$jifen_list['jifennumber'],array($uid,"减少积分".$jifen_list['jifennumber']));	
	                        }										
	                    }												
	                    										
	                    $jilu_data = array(								
	                        'weid' => $uniacid,								
	                        'title' => '积分',								
	                        'jifen' => $jifen_list['jifennumber'],								
	                        'rid' => $rid,								
	                        'addtime' => TIMESTAMP,								
	                        'mcid' => $uid,																
	                        'mendian' => empty($cqlist)?0:$cqlist['mendian'],																
	                        'dianyuan' => empty($cqlist)?0:$cqlist['dianyuan'],																
	                        'openid' => $openid,																
	                        'jftype' => $jifen_list['jftype'],																
	                        'content' => $jifen_list['content']						
	                        
	                    );						
	                    pdo_insert('xjfb_jifenjilu',$jilu_data);							
	                    
	                    
						if($mdlist['hsjifen']==1&&$jifen_list['jftype']==1){
						
							pdo_update("xjfb_mendian",array('number'=>($mdlist['number'] + $jifen_list['jifennumber']),'numtime'=>TIMESTAMP),array('id'=>$cqlist['mendian']));														
							$r_data = array(
									'weid' => $uniacid,
									'mendian' => empty($cqlist)?0:$cqlist['mendian'],
									'yuangong' => empty($cqlist)?0:$cqlist['dianyuan'],
									'mcid' => $uid,
									'type' => 0,
									'number' => $jifen_list['jifennumber'],
									'addtime' => TIMESTAMP,
							);
							pdo_insert('xjfb_recover',$r_data);
						
						}	
						
						
						$ssql = "SELECT * FROM " . tablename('xjfb_setting') . " WHERE weid = :weid";						
						$setting = pdo_fetch($ssql,array(':weid'=>$uniacid));

						$qsql = "SELECT * FROM " . tablename('xjfb_qrcode') . " WHERE weid = :weid and rid = :rid";
						$qlist = pdo_fetch($qsql,array(':weid'=>$uniacid,':rid'=>$rid));
							
						
						if($repeat == 0){																			
						    pdo_delete("rule",array('id'=>$rid));						
						    pdo_delete("rule_keyword",array('rid'=>$rid));												
						    pdo_delete('xjfb_qrcode',array('rid'=>$rid));
						    pdo_delete('qrcode',array('keyword'=>'zm_jfb'.$jifen_list['id']));
						}																		
						if(!empty($setting)){														
						    $str = str_replace('{nickname}',$user['nickname'],$setting['tishi']);														
						    $str = str_replace('{jifen}',$jifen_list['jifennumber'],$str);																					
						    if($jifen_list['jftype'] == 0){			
						        $keyword = "【".$setting['title']."】积分增加通知";
						        
						        $str = str_replace('获得','增加',$str);								
						        $str = str_replace('{sum}',($user['credit1'] + $jifen_list['jifennumber']),$str);							
						    }							
						    else{ 						
						        $keyword = "【".$setting['title']."】积分减少通知";
						        
						        $str = str_replace('获得','减少',$str);								
						        $str = str_replace('{sum}',($user['credit1'] - $jifen_list['jifennumber']),$str);							
						    }
						    
						}else{							
						    if($jifen_list['jftype'] == 0)								
						        $str = "亲爱的".$user['nickname'].",您增加了".$jifen_list['jifennumber']."积分,您有".($user['credit1']+$jifen_list['jifennumber'])."积分";							
						    else 								
						        $str = "亲爱的".$user['nickname'].",您减少了".$jifen_list['jifennumber']."积分,您有".($user['credit1']-$jifen_list['jifennumber'])."积分";		
						    				
						}	
						$this->sendTemplate($mdlist['weixin'], $keyword, $mdlist['tempmsg'], $mdlist['tempcontent'], $mdlist['template']);
						if($_W['account']['level'] >= ACCOUNT_SUBSCRIPTION_VERIFY) {
						    $info = "【{$_W['account']['name']}】通知:\n";
						    $info .=$str;
						
						    $account_api = WeAccount::create();
						
						    $message = array(
						        'touser' => $openid,
						        'msgtype' => 'text',
						        'text' => array('content' => urlencode($info))
						    );
						    $status = $account_api->sendCustomNotice($message);
						    if (is_error($status)) {
						        message('发送失败，原因为' . $status['message']);
						    }
						
						}
						
						if(!empty($slist['msg_title'])){
						    return $this->respNews(array(
						
						        'Title' => $slist['msg_title'],
						
						        'Description' => $slist['msg_con'],
						
						        'PicUrl' => $slist['msg_img'],
						
						        'Url' => $slist['msg_url'],
						
						    ));
						}																						
	                }								
	            }						
	        }					
	    }else{							
	        return $this->respText("此二维码已被使用,请联系店员重新生成二维码，&#25240;&#70;&#32764;&#70;&#22825;&#70;&#20351;&#70;&#36164;&#70;&#28304;&#70;&#31038;&#70;&#21306;&#70;&#25552;&#70;&#20379;");					
	    }					
	}
	
	
	
	
	public function sendTemplate($openid,$keyword,$tempmsg,$tempcontent,$template){
	    global $_W;
	    
	    $data = array(
	        'first' => array(
	    
	            'value' => $tempmsg,
	    
	            'color' => '#173177'
	    
	        ),
	        'keyword1' => array(
	    
	            'value' => $keyword,
	    
	            'color' => '#173177'
	    
	        ),
	        'keyword2' => array(
	    
	            'value' => '通知',
	    
	            'color' => '#173177'
	    
	        ),
	        'remark' => array(
	    
	            'value' => $tempcontent,
	    
	            'color' => '#173177'
	    
	        )
	    
	    );
	    
	    load()->classs('weixin.account');
	    
	    $_tpl = new WeiXinAccount($_W['account']);
	    
	    $ret = $_tpl->sendTplNotice($openid,$template,$data,"");
	    
	}
	
	
}