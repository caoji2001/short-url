<?php
$core_file = dirname(__FILE__).'/../system/core.php';
require_once($core_file);
$MysqliDb_file = dirname(__FILE__).'/../system/MysqliDb.php';
require_once($MysqliDb_file);

$input_url = trim($_POST['input_url']);

$arr = array('ok' => 0);

if (empty($input_url)) {
    $arr['error_msg'] = '请输入要缩短的长链接！';
} elseif (!preg_match('/https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&\/\/=]*)/', $input_url)) {
    $arr['error_msg'] = '输入的长链接不合法！';
} else {
    $db = new MysqliDb (Array (
        'host' => $db_config['server'],
        'username' => $db_config['username'], 
        'password' => $db_config['password'],
        'db'=> $db_config['name'],
        'port' => $db_config['port']));

    if ($db->where('domain', parse_url($input_url, PHP_URL_HOST))->getValue('blacklist', 'count(*)') > 0) {
        $arr['error_msg'] = '要缩短的链接位于域名黑名单内';
    } else {
        $random_number = rand(14776337, 916132832); // [62^4 + 1, 62^5]
        while ($db->where('id', $random_number)->getValue('fwlink', 'count(*)') > 0) {
            $random_number = rand(14776337, 916132832); // [62^4 + 1, 62^5]
        }

        session_start();
        if (isset($_SESSION['username']) && !$_SESSION['admin']) {
            $nice = $db->insert('fwlink', Array('id' => $random_number, 'url' => $input_url, 'username' => $_SESSION['username']));
        } else {
            $nice = $db->insert('fwlink', Array('id' => $random_number, 'url' => $input_url));
        }

        if ($nice) {
            $arr['ok'] = 1;
            $arr['short_url'] = get_site_url() . from10_to62($random_number);
        } else {
            $arr['error_msg'] = '数据库语句执行出错！' . $db->getLastError();
        }
    }
}

echo json_encode($arr);
?>