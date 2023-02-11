<?php
$core_file = dirname(__FILE__).'/../../system/core.php';
require_once($core_file);
$MysqliDb_file = dirname(__FILE__).'/../../system/MysqliDb.php';
require_once($MysqliDb_file);

session_start();
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header('Location: ./login.php');
    exit(0);
}

$db = new MysqliDb (Array (
    'host' => $db_config['server'],
    'username' => $db_config['username'], 
    'password' => $db_config['password'],
    'db'=> $db_config['name'],
    'port' => $db_config['port']));

$domain = trim($_POST['domain']);

if (empty($domain)) {
    echo '请输入要加入黑名单的域名！';
} else {
    $nice = $db->insert('blacklist', Array('domain' => $domain));

    if ($nice) {
        echo '成功添加黑名单域名！';
    } else {
        echo '添加黑名单域名失败：' . $db->getLastError();
    }
}

$db->disconnect();
?>