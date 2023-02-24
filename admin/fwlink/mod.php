<?php
$core_file = dirname(__FILE__).'/../../system/core.php';
require_once($core_file);
$MysqliDb_file = dirname(__FILE__).'/../../system/MysqliDb.php';
require_once($MysqliDb_file);

session_start();
if (!isset($_SESSION['username'])) {
    exit('请登陆后再访问');
}

$id = from62_to10($_POST['id62']);
$input_url = $_POST['url'];

if (empty($input_url)) {
    echo '请输入要缩短的长链接！';
} elseif (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $input_url)) {
    echo '输入的长链接不合法！';
} else {
    $db = new MysqliDb (Array (
        'host' => $db_config['server'],
        'username' => $db_config['username'], 
        'password' => $db_config['password'],
        'db'=> $db_config['name'],
        'port' => $db_config['port']));

    if (!$_SESSION['admin'] && $db->where('id', $id)->getValue('fwlink', 'username') != $_SESSION['username']) {
        echo '你没有权限修改该短链接';
    } else {
        $db->where('id', $id)->update('fwlink', Array('url' => $input_url));

        if ($db->getLastErrno() === 0) {
            echo '成功更改短链接指向！';
        } else {
            echo '修改数据失败：' . $db->getLastError();
        }
    }

    $db->disconnect();
}
?>