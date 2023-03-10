<?php
$core_file = dirname(__FILE__).'/../../system/core.php';
require_once($core_file);
$MysqliDb_file = dirname(__FILE__).'/../../system/MysqliDb.php';
require_once($MysqliDb_file);

session_start();
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    exit('仅限管理员访问');
}

$db = new MysqliDb (Array (
    'host' => $db_config['server'],
    'username' => $db_config['username'], 
    'password' => $db_config['password'],
    'db'=> $db_config['name'],
    'port' => $db_config['port']));

$db->where('domain', trim($_POST['domain']))->delete('blacklist');

if ($db->getLastErrno() === 0) {
    echo '成功删除黑名单域名！';
} else {
    echo '删除黑名单域名失败：' . $db->getLastError();
}

$db->disconnect();
?>