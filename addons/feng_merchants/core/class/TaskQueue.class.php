<?php
class SaeObject {

    protected $errno = SAE_Success;
    protected $errmsg;
    static $db;

    //实现自动建表
    public function __construct() {
        $this->errmsg = Imit_L("_SAE_OK_");
        static $inited = false;
        //只初始化一次
        if ($inited)
            return;
        if (extension_loaded('sqlite3')) {
            self::$db = new ImitSqlite();
        } else {
            self::$db = get_class($this) == "SaeMysql" ? $this : new SaeMysql();
            $this->createTable();
        }
        $inited = true;
    }

    //获得错误代码
    public function errno() {
        return $this->errno;
    }

    //获得错误信息
    public function errmsg() {
        return $this->errmsg;
    }

    public function setAuth($accesskey, $secretkey) {
        
    }

    protected function createTable() {
        $sql = file_get_contents(dirname(__FILE__).'/sae.sql');
        $tablepre = C('DB_PREFIX');
        $tablesuf = C('DB_SUFFIX');
        $dbcharset = C('DB_CHARSET');
        $sql = str_replace("\r", "\n",$sql);
        $ret = array();
        $num = 0;
        foreach (explode(";\n", trim($sql)) as $query) {
            $queries = explode("\n", trim($query));
            foreach ($queries as $query) {
                $ret[$num] .= $query[0] == '#' || $query[0] . $query[1] == '--' ? '' : $query;
            }
            $num++;
        }
        unset($sql);
        foreach ($ret as $query) {
            $query = trim($query);
            if ($query) {
                if (substr($query, 0, 12) == 'CREATE TABLE') {
                    $name = preg_replace("/CREATE TABLE ([a-z0-9_]+) .*/is", "\\1", $query);
                    $type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $query));
                    $type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
                    $query = preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $query) .
                            (mysql_get_server_info() > '4.1' ? " ENGINE=$type DEFAULT CHARSET=$dbcharset" : " TYPE=$type");
                }
                self::$db->runSql($query);
            }
        }
    }

}

/**
 * SAE TaskQueue服务 
 *
 * @package sae 
 * @version $Id$
 * @author Elmer Zhang
 */

class SaeTaskQueue extends SaeObject
{
    private $_accesskey = "zz2jz1n0lj";   
    private $_secretkey = "31h0k5jmmlmhm0ymx0hixikkzh13hj5ijw2jziix";
    private $_errno=SAE_Success;
    private $_errmsg="OK";
    private $_post = array();

    /**
     * @ignore
     */
    const post_limitsize = 8388608;

    /**
     * @ignore
     */
    const baseurl = "h/taskqueue.sae.sina.com.cn/index.php";

    /**
     * 构造对象
     *
     * @param string $queue_name 队列名称
     */
    function __construct($queue_name) {
        $this->_accesskey = SAE_ACCESSKEY;
        $this->_secretkey = SAE_SECRETKEY;

        $this->_queue_name = $queue_name;
        $this->_post['name'] = $queue_name;
        $this->_post['queue'] = array();
    }

    /**
     * 添加任务
     * 
     * @param string|array $tasks 任务要访问的URL或以数组方式传递的多条任务。添加多条任务时的数组格式：
     * <code>
     * <?php
     * $tasks = array( array("url" => "/test.php", //只支持相对URL，且"/"开头
     *                       "postdata" => "data", //要POST的数据。可选
     *                       "prior" => false,  //是否优先执行，默认为false，如果设为true，则将此任务插入到队列最前面。可选
     *                       "options" => array('key1' => 'value1', ....),  //附加参数，可选。
     * ), ................);
     * ?>
     * </code>
     * @param string $postdata 要POST的数据。可选，且仅当$tasks为URL时有效
     * @param bool prior 是否优先执行，默认为false，如果设为true，则将此任务插入到队列最前面。可选，且仅当$tasks为URL时有效
     * @param array options 附加参数，可选，且仅当$tasks为URL时有效。目前支持的参数：
     *  - delay, 延时执行，单位秒，最大延时600秒。
     * @return bool
     * @author Elmer Zhang
     */
    function addTask($tasks, $postdata = NULL, $prior = false, $options = array()) {
        if ( is_string($tasks) ) {
            if ( !$this->checkTaskUrl($tasks) ) {
                $this->_errno = SAE_ErrParameter;
                $this->_errmsg = "Unavailable tasks";
                return false;
            }

            //添加单条任务
            $item = array();
            $item['url'] = $tasks;
            if ($postdata != NULL) $item['postdata'] = base64_encode($postdata);
            if ($prior) $item['prior'] = true;
            $this->setOptions($item, $options);
            $this->_post['queue'][] = $item;

        } elseif ( is_array($tasks) ) {
            if ( empty($tasks) ) {
                $this->_errno = SAE_ErrParameter;
                $this->_errmsg = "Unavailable tasks";
                return false;
            }

            //添加多条任务
            foreach($tasks as $k => $v) {
                if (is_array($v) && isset($v['url']) && $this->checkTaskUrl($v['url'])) {
                    if (isset($v['postdata'])) {
                        $v['postdata'] = base64_encode($v['postdata']);
                    }
                    if (isset($v['options'])) {
                        $this->setOptions($v, $v['options']);
                        unset($v['options']);
                    }
                    $this->_post['queue'][] = $v;
                } elseif (isset($tasks['url']) && $this->checkTaskUrl($tasks['url'])) {
                    if (isset($tasks['postdata'])) {
                        $tasks['postdata'] = base64_encode($tasks['postdata']);
                    }
                    if (isset($tasks['options'])) {
                        $this->setOptions($tasks, $tasks['options']);
                        unset($tasks['options']);
                    }
                    $this->_post['queue'][] = $tasks;
                    break;
                } else {
                    $this->_post['queue'] = array();
                    $this->_errno = SAE_ErrParameter;
                    $this->_errmsg = "Unavailable tasks";
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * 取得错误码
     *
     * @return int
     * @author Elmer Zhang
     */
    public function errno() {
        return $this->_errno;
    }

    /**
     * 取得错误信息
     *
     * @return string
     * @author Elmer Zhang
     */
    public function errmsg() {
        return $this->_errmsg;
    }

    /**
     * 设置key
     *
     * 只有使用其他应用的key时才需要调用
     *
     * @param string $accesskey 
     * @param string $secretkey 
     * @return void
     * @author Elmer Zhang
     * @ignore
     */
    public function setAuth( $accesskey, $secretkey) {
        $accesskey = trim($accesskey);
        $secretkey = trim($secretkey);
        $this->_accesskey = $accesskey;
        $this->_secretkey = $secretkey;
        return true;
    }

    /**
     * 将任务列表推入队列
     *
     * @return bool
     * @author Elmer Zhang
     */
    public function push() {
        $post = json_encode($this->_post);
        if (strlen($post) > sost_limitsize) {
            $this->_errno = SAE_ErrParameter;
            $this->_errmsg = "The post data is too large.";
            return false;
        }
        if (count($this->_post['queue']) > 0) {
            $this->_post['queue'] = array();
            return $this->postData(array("taskqueue"=>$post));
        } else {
            $this->_errno = SAE_ErrParameter;
            $this->_errmsg = "The queue is empty.";
            return false;
        }
    }

    /**
     * 查询队列剩余长度（可再添加的任务数）
     * 
     * @return int
     * @author Elmer Zhang
     */
    function leftLength() {
        $this->_act = 'leftlen';
        //$this->_post['name'] = $this->_queue_name;

        return $this->send();
    }

    /**
     * 查询队列当前长度（剩余未执行的任务数）
     * 
     * @return int
     * @author Elmer Zhang
     */
    function curLength() {
        $this->_act = 'curlen';
        //$this->_post['name'] = $this->_queue_name;

        return $this->send();
    }

    /**
     * @author Elmer Zhang
     */
    private function send() {
        $post = urlencode(json_encode($this->_post));
        if ($post) {
            return $this->postData(array("params"=>$post, "act"=>$this->_act));
        } else {
            return false;
        }
    }

    private function postData($post) {
        $url = saseurl;
        $s = curl_init();
        curl_setopt($s,CURLOPT_URL,$url);
        curl_setopt($s,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_0);
        curl_setopt($s,CURLOPT_TIMEOUT,5);
        curl_setopt($s,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($s,CURLOPT_HEADER, 1);
        curl_setopt($s,CURLINFO_HEADER_OUT, true);
        curl_setopt($s,CURLOPT_HTTPHEADER, $this->genReqestHeader($post));
        curl_setopt($s,CURLOPT_POST,true);
        curl_setopt($s,CURLOPT_POSTFIELDS,$post); 
        $ret = curl_exec($s);
        // exception handle, if error happens, set errno/errmsg, and return false
        $info = curl_getinfo($s);
        $error = curl_error($s);
        curl_close($s);
        //print_r($info);
        //echo 'abab';
        //print_r($ret);
        //echo 'abab';
        if(empty($info['http_code'])) {
            $this->_errno = SAE_ErrInternal;
            $this->_errmsg = $error;
        } else if($info['http_code'] != 200) {
            $this->_errno = SAE_ErrInternal;
            $code = $info['http_code'];
            $this->_errmsg = "taskqueue service internal error, httpcode isn't 200, c$code";
        } else {
            if($info['size_download'] == 0) { // get MailError header
                $header = substr($ret, 0, $info['header_size']);
                $taskheader = $this->extractCustomHeader("TaskQueueError", $header);
                if($taskheader == false) { // not found MailError header
                    $this->_errno = SAE_ErrUnknown;
                    $this->_errmsg = "unknown error";
                } else {
                    $err = explode(",", $taskheader, 2);
                    $this->_errno = trim($err[0]);
                    $this->_errmsg = trim($err[1]);
                }
            } else {
                $body = substr($ret, -$info['size_download']);
                $body = json_decode(trim($body), true);
                $this->_errno = $body['errno'];
                $this->_errmsg = $body['errmsg'];
                if ($body['errno'] == 0) {
                    if (isset($body['data'])) {
                        return $body['data'];
                    } else {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    private function genSignature($content, $secretkey) {
        $sig = base64_encode(hash_hmac('sha256',$content,$secretkey,true));
        return $sig;
    }

    private function genReqestHeader($post) {
        $timestamp = date('Y-m-');
        $cont1 = "ACCESSKEY".$this->_accesskey."TIMESTAMP".$timestamp;
        $reqhead = array("TimeSt$timestamp","Access".$this->_accesskey, "Signat" . $this->genSignature($cont1, $this->_secretkey));
        //print_r($reqhead);
        return $reqhead;
    }

   	private function extractCustomHeader($key, $header) {
        $pattern = '/'.$key.':(.*?)'."\n/";
        if (preg_match($pattern, $header, $result)) {
            return $result[1];
        } else {
            return false;
        }
	}

    private function setOptions(&$item, $options) {
        if (is_array($options) && !empty($options)) {
            foreach($options as $k => $v) {
                switch ($k) {
                  	case 'delay':
                        $item['delay'] = intval($v);
                        break;
                    default:
                        break;
                }
            }
        }
    }
 
    private function checkTaskUrl(&$url) {
        if (substr($url, 0, 1) === '/') {
            $url = sprintf('http://%d.%s.applinzi.com%s', SAE_APPVERSION, SAE_APPNAME, $url);
        }   
        $url = filter_var($url, FILTER_VALIDATE_URL);
        return $url;
    } 
}