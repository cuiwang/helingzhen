<?php
defined('IN_IA') or exit('Access Denied');
class Zio_domainModuleSite extends WeModuleSite
{
    public function doWebPost()
    {
        global $_W, $_GPC;
        $limit = $this->getLimit();
        if (!$limit['role']) {
            message('无权进行此操作!');
        }
        $id   = intval($_GPC['id']);
        $host = str_replace('www.', '', $_SERVER['HTTP_HOST']);
        if (!empty($id)) {
            $item = pdo_fetch("SELECT * FROM " . tablename("zio_domain") . ' WHERE  id = :id  limit 1 ', array(
                ':id' => $id
            ));
            if (empty($item)) {
                message('无效参数!');
            }
            $ext = iunserializer($item['ext']);
            if (!$_W['isfounder']) {
                if (($item['uniacid'] != $_W['uniacid']) || $ext['right']) {
                    message('无权修参数!');
                }
            }
            if ($item['type'] == 1) {
                $item['domain'] = str_replace($host, '', $item['domain']);
                $item['domain'] = trim($item['domain'], '.');
            }
        } else {
            if (!$limit['en_add']) {
                message('数量超限制或无权进行此操作!');
            }
            $item = array(
                'all' => $limit['type'] > 1 ? 0 : 1,
                'type' => $limit['domain'] > 1 ? 1 : 0
            );
        }
        if (checksubmit('submit')) {
            $data = $_GPC['set'];
            $ext  = $_GPC['ext'];
            if (empty($data['all'])) {
                if (empty($data['entry'])) {
                    message('模块入口不能为空，请选择模块入口！');
                }
            }
            $data['uniacid'] = $_W['uniacid'];
            $domain          = trim($data['domain']);
            if (!preg_match('/[0-9a-z-.]{1,}/i', $domain)) {
                message('域名只能输入英文,数字,下划线组成！');
            }
            if ($data['type'] == 1 && (strpos('.', $domain) === false)) {
                $domain = $data['domain'] . '.' . $host;
            }
            if ($domain == $_W['config']['setting']['domain']['host'] && (!$_W['isfounder'])) {
                message('系统域名非系统创建者不能增加！');
            }
            if (empty($item)) {
                $name = pdo_fetch("SELECT id,domain FROM " . tablename('zio_domain') . " WHERE domain = :name", array(
                    ':name' => base64_encode($domain)
                ));
                if (!empty($name) && $item['id'] != $name['id']) {
                    message('域名已存在');
                }
            }
            if ($_W['isfounder'] && $_GPC['all_account'] == 1) {
                $data['uniacid'] = 0;
            }
            if ($data['all']) {
                $data['title'] = '_公众号模式_';
                $data['entry'] = './index.php?i=' . $_W['uniacid'] . '&';
            } else {
                if ($data['title'] == '_公众号模式_') {
                    $data['title'] = '';
                }
                $ext['redirect']  = 0;
                $ext['domains']   = '';
                $data['redirect'] = 0;
                if (preg_match('|eid=(\d+)|', $data['entry'], $match)) {
                    $data['eid'] = $match[1];
                    $m           = pdo_fetch('select * from ' . tablename('modules_bindings') . ' where eid=:eid', array(
                        ':eid' => $data['eid']
                    ));
                    if (!empty($m)) {
                        $data['title']  = $m['title'];
                        $data['module'] = $m['module'];
                    }
                } else {
                    if (preg_match('|c=([a-z]+)|', $data['entry'], $match)) {
                        $data['module'] = $match[1];
                    } else {
                        $data['module'] = '';
                    }
                }
            }
            $data['domain'] = $domain;
            $url            = str_replace('./', $_W['siteroot'] . 'app/', $data['entry']);
            $data['url']    = str_replace($_SERVER['HTTP_HOST'], $data['domain'], $url);
            $data['ext']    = iserializer($ext);
            if (empty($item['id'])) {
                $data['createtime'] = TIMESTAMP;
                pdo_insert('zio_domain', $data);
            } else {
                pdo_update('zio_domain', $data, array(
                    'id' => $item['id']
                ));
            }
            $this->saveBindSet($data);
            message('域名绑定成功！', $this->createWebUrl('manage'), 'success');
        }
        include $this->template('post');
    }
    public function doWebHelp()
    {
        global $_W, $_GPC;
        if (!$_W['isfounder']) {
            message('无权进行此操作!');
        }
        $host = $_SERVER['HTTP_HOST'];
        $set  = $_W['config']['setting']['domain'];
        if (checksubmit()) {
            $config = array(
                'host' => $_GPC['host'],
                'protect_app' => intval($_GPC['protect_app']),
                'protect_web' => intval($_GPC['protect_web'])
            );
            $this->patchConfig($config, $_GPC['submit'] == 'save');
        }
        include $this->template('help');
    }
    public function doWebManage()
    {
        global $_W, $_GPC;
        $limit  = $this->getLimit();
        $enable = $limit['en_add'];
        $op     = $_GPC['op'];
        if ($op == 'delete') {
            $id   = intval($_GPC['id']);
            $item = pdo_fetch("SELECT * FROM " . tablename("zio_domain") . " WHERE id = :id  limit 1", array(
                ':id' => $id
            ));
            if (!empty($item)) {
                if (($item['uniacid'] == $_W['uniacid']) || (empty($item['uniacid']) && $_W['isfounder'])) {
                    pdo_delete('zio_domain', array(
                        'id' => $item['id']
                    ));
                    $this->saveBindSet($item, true);
                    message('数据删除成功！', $this->createWebUrl('manage'), 'success');
                }
            }
            message('无效参数,数据不存在!');
        } else {
            $pindex    = max(1, intval($_GPC['page']));
            $psize     = 20;
            $condition = ' WHERE uniacid = :uniacid ';
            $params    = array(
                ':uniacid' => $_W['uniacid']
            );
            if ($_W['isfounder']) {
                if (empty($_GPC['all'])) {
                    $condition = ' WHERE (uniacid = :uniacid or uniacid=0) ';
                } else {
                    $condition = ' WHERE  1=1  ';
                    $params    = array();
                }
                $accounts = pdo_fetchall('select uniacid,name from ' . tablename('account_wechats'), array(), 'uniacid');
                $uniacid  = intval($_GPC['uniacid']);
                if (isset($accounts[$uniacid])) {
                    isetcookie('__uniacid', $uniacid, 7 * 86400);
                    header('location: ' . $this->createWebUrl('manage'));
                }
            }
            $query_name = array(
                '绑定域名',
                '模块名称',
                '模块入口'
            );
            $query      = intval($_GPC['query']);
            $key        = trim($_GPC['keyword']);
            if (!empty($key)) {
                $fields = array(
                    'domain',
                    'module',
                    'title'
                );
                $condition .= ' AND ' . $fields[$query] . '  LIKE  :key ';
                $params[':key'] = "%{$key}
			%";
            }
            $sql   = 'SELECT * FROM ' . tablename("zio_domain") . $condition;
            $total = pdo_fetchcolumn(str_replace('*', 'count(*)', $sql), $params);
            $pager = pagination($total, $pindex, $psize);
            $sql .= ' ORDER BY `id` ASC   limit ' . ($pindex - 1) * $psize . ',' . $psize;
            $list = pdo_fetchall($sql, $params);
            foreach ($list as &$item) {
                $item['domain_url']  = str_replace($_SERVER['HTTP_HOST'], $item['domain'], $_W['siteroot']);
                $item['ext']         = iunserializer($item['ext']);
                $item['edit']        = $limit['role'] && ($_W['isfounder'] || empty($item['ext']['right']));
                $item['accountname'] = '当前公众号';
                if (!empty($item['uniacid']) && $_W['uniacid'] != $item['uniacid']) {
                    $name                = isset($accounts[$item['uniacid']]['name']) ? $accounts[$item['uniacid']]['name'] : '公众号' . $item['uniacid'];
                    $item['accountname'] = '<a style="color:white;" title="切换公众号" href="' . $this->createWebUrl('manage', array(
                        'uniacid' => $item['uniacid']
                    )) . "\">{$name}
			</a>";
                }
            }
            unset($item);
            include $this->template('manage');
        }
    }
    public function doWebGroup()
    {
        global $_W, $_GPC;
        if (!$_W['isfounder']) {
            message('无权操作!请用管理员登录处理');
        }
        $limit  = $this->getLimit();
        $enable = $limit['en_add'];
        $op     = $_GPC['op'];
        if ($op == 'delete') {
            $id   = intval($_GPC['id']);
            $item = pdo_fetch('SELECT * FROM ' . tablename('zio_domain_group') . ' WHERE  id = :id  limit 1', array(
                ':id' => $id
            ));
            if (empty($item) || (empty($item['groupid']))) {
                message('无效参数,数据不存在!');
            }
            pdo_delete('zio_domain_group', array(
                'id' => $item['id']
            ));
            message('数据删除成功！', $this->createWebUrl('group'), 'success');
        } else if ($op == 'post') {
            $id   = intval($_GPC['id']);
            $item = pdo_fetch('SELECT * FROM ' . tablename('zio_domain_group') . ' WHERE  id = :id  limit 1', array(
                ':id' => $id
            ));
            if (checksubmit('submit')) {
                $data            = array(
                    'title' => $_GPC['title'],
                    'isaccount' => intval($_GPC['isaccount']),
                    'limit' => intval($_GPC['limit'])
                );
                $data['groupid'] = intval($_GPC['groupid']);
                $data['right']   = intval($_GPC['right1']) + intval($_GPC['right2']);
                $data['type']    = intval($_GPC['type1']) + intval($_GPC['type2']);
                $data['domain']  = intval($_GPC['domain1']) + intval($_GPC['domain2']);
                if (empty($item)) {
                    pdo_insert('zio_domain_group', $data);
                } else {
                    pdo_update('zio_domain_group', $data, array(
                        'id' => $item['id']
                    ));
                }
                message('数据提交成功！', $this->createWebUrl('group'), 'success');
            }
            if (empty($item)) {
                $item = array(
                    'limit' => 0,
                    'type' => 3,
                    'right' => 3
                );
            }
            include $this->template('group_post');
        } else if ($op == 'query') {
            $kwd       = trim($_GPC['keyword']);
            $params    = array();
            $condition = '';
            $type      = intval($_GPC['type']);
            $exist     = pdo_fetchall('SELECT id,title as title FROM ' . tablename('zio_domain_group') . ' where isaccount=:type', array(
                ':type' => $type
            ), 'id');
            if (empty($type)) {
                $list = pdo_fetchall('SELECT id,name as title FROM ' . tablename('uni_group') . ' WHERE uniacid = 0');
            } else {
                $list = pdo_fetchall('SELECT uniacid as id,name as title FROM ' . tablename('uni_account'));
            }
            $ds = array();
            foreach ($list as $item) {
                if (!isset($exist[$item['id']])) {
                    $ds[] = $item;
                }
            }
            include $this->template('query');
        } else {
            $pindex = max(1, intval($_GPC['page']));
            $psize  = 20;
            $sql    = 'SELECT * FROM ' . tablename("zio_domain_group");
            $total  = pdo_fetchcolumn(str_replace('*', 'count(*)', $sql));
            $pager  = pagination($total, $pindex, $psize);
            $sql .= ' ORDER BY `id` DESC   limit ' . ($pindex - 1) * $psize . ',' . $psize;
            $list = pdo_fetchall($sql, $params);
            include $this->template('group_list');
        }
    }
    protected function getLimit()
    {
        global $_W;
        $sql   = 'SELECT * FROM ' . tablename('zio_domain_group') . ' order by isaccount asc,groupid asc';
        $list  = pdo_fetchall($sql);
        $limit = array();
        foreach ($list as $item) {
            if ($item['isaccount']) {
                if (empty($limit) || empty($limit['isaccount'])) {
                    if ($item['groupid'] == $_W['uniacid'] || empty($limit['groupid'])) {
                        $limit = $item;
                        if ($item['groupid'] == $_W['uniacid']) {
                            break;
                        }
                    }
                }
            } else {
                if (empty($limit) || empty($limit['groupid'])) {
                    if (empty($limit) || (isset($_W['uniaccount']['group'][$item['groupid']]) || $_W['uniaccount']['groupid'] == $item['groupid'])) {
                        $limit = $item;
                    }
                }
            }
        }
        if (!empty($limit)) {
            $limit['role'] = true;
            if (!$_W['isfounder']) {
                if ($_W['role'] == 'operator') {
                    $limit['role'] = ($limit['right'] & 2) == 2;
                } else {
                    $limit['role'] = ($limit['right'] & 1) == 1;
                }
            }
            $limit['en_add'] = true;
            if ($limit['limit'] > 0) {
                $c               = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename("zio_domain") . ' WHERE uniacid = :uniacid ', array(
                    ':uniacid' => $_W['uniacid']
                ));
                $limit['en_add'] = $c < $limit['limit'];
            }
            $limit['en_add'] = $limit['en_add'] && $limit['role'];
        } else if ($_W['isfounder']) {
            $limit = array(
                'limit' => 0,
                'en_add' => true,
                'type' => 3,
                'domain' => 3,
                'right' => 3
            );
        }
        return $limit;
    }
    protected function saveBindSet($bind, $delete = false)
    {
        $path = IA_ROOT . '/data/zio_domain/';
        $file = $path . str_replace('.', '_', $bind['domain']) . '.php';
        if (!is_dir($path)) {
            @mkdir($path);
        }
        if ($delete) {
            if (file_exists($file)) {
                @unlink($file);
            }
            return true;
        }
        $ext   = @iunserializer($bind['ext']);
        $write = array(
            'domain' => $bind['domain'],
            'uniacid' => $bind['uniacid'],
            'enable_web' => intval($ext['login']),
            'redirect' => intval($bind['redirect']),
            'url' => $bind['url'],
            'isaccount' => intval($bind['all'])
        );
        if (!empty($ext['domains'])) {
            $write['forward'] = explode('|', $ext['domains']);
            foreach ($write['forward'] as $k => $d) {
                if (!preg_match("/[0-9a-z-.]{1,}/i", $d)) {
                    unset($write['forward'][$k]);
                }
            }
        }
        $context = base64_decode('PD9waHA=') . "\ndefined('IN_IA') or exit('Access Denied');\n";
        $context .= '$set= unserialize(base64_decode(\'' . base64_encode(serialize($write)) . "'));\n";
        $context .= 'return $set;' . "\n";
        return @file_put_contents($file, $context);
    }
    protected function patchConfig($config, $save = true)
    {
        $file    = IA_ROOT . '/data/config.php';
        $context = file_get_contents($file);
        $context = preg_replace('|\n//\+\+[^+]*\+\+\/\/|', '', $context);
        $msg     = '删除设置成功';
        if ($save) {
            $context .= "\n//++--------------- zio_domain 域名绑定配置请不要手工修改  ---------------//\n";
            foreach ($config as $k => $v) {
                $context .= '$config[\'setting\'][\'domain\'][\'' . $k . '\']=\'' . $v . '\';' . "\n";
            }
            $context .= 'if(file_exists(IA_ROOT . "/addons/zio_domain/domain.php"))' . "{\n";
            $context .= '   include IA_ROOT . "/addons/zio_domain/domain.php";' . "}\n";
            $context .= "//----------------- zio_domain 域名绑定配置请不要手工修改  -------------++//";
            $msg = '修改成功';
        }
        $write = @file_put_contents($file, $context);
        if (!$write) {
            $msg = '修改失败,不允许写入';
        }
        message($msg, $this->createWebUrl('help'));
    }
    public function doMobileOauth()
    {
        global $_W;
        $this->checkOpenid($_W['openid']);
        include $this->template('oauth');
    }
    protected function checkOpenid()
    {
        global $_W;
        if (empty($_W['openid'])) {
            $state = 'we7sid-' . $_W['session_id'];
            if (empty($_SESSION['dest_url'])) {
                $_SESSION['dest_url'] = base64_encode(urlencode($_W['siteurl']));
            }
            $siteroot = $_W['siteroot'];
            $set      = 'unisetting:' . $_W['uniacid'];
            if (!empty($_W['cache'][$set]['oauth']['host'])) {
                $siteroot = str_replace('http://' . $_SERVER['HTTP_HOST'], rtrim($_W['cache'][$set]['oauth']['host'], '/'), $siteroot);
            }
            $url      = "{$siteroot}app/index.php?i={$_W['uniacid']}&j={$_W['acid']}&c=auth&a=oauth&scope=snsapi_base";
            $callback = urlencode($url);
            $forward  = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$_W['oauth_account']['key']}&redirect_uri={$callback}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect";
            header('Location: ' . $forward);
            exit();
        }
    }
}
