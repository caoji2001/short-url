<?php
$core_file = dirname(__FILE__).'/../../system/core.php';
require_once($core_file);
$MysqliDb_file = dirname(__FILE__).'/../../system/MysqliDb.php';
require_once($MysqliDb_file);

session_start();
if (!isset($_SESSION['username'])) {
    exit('请登陆后再访问');
}

$db = new MysqliDb (Array (
    'host' => $db_config['server'],
    'username' => $db_config['username'], 
    'password' => $db_config['password'],
    'db'=> $db_config['name'],
    'port' => $db_config['port']));

$id = from62_to10($_POST['id62']);

if (!$_SESSION['admin'] && $db->where('id', $id)->getValue('fwlink', 'username') != $_SESSION['username']) {
    echo '你没有权限删除该短链接';
} else {
    $db->where('id', $id)->delete('fwlink');
    $db->where('id', $id)->delete('visit');

    if ($db->getLastErrno() === 0) {
        echo '成功删除短链接！';
    } else {
        echo '删除短链接失败：' . $db->getLastError();
    }
}

$db->disconnect();
?>