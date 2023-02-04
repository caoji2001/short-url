<?php
$db = new MysqliDb (Array (
    'host' => $db_config['server'],
    'username' => $db_config['username'], 
    'password' => $db_config['password'],
    'db'=> $db_config['name'],
    'port' => $db_config['port']));

$results = $db->get('fwlink', null, Array('id', 'url'));

if ($db->count > 0) {
    $protocol = '';
    if (isset($_SERVER['HTTPS']) &&
        ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        $protocol = 'https://';
    }
    else {
        $protocol = 'http://';
    }
    $siteurl = $protocol . $_SERVER['HTTP_HOST'] . '/';

    foreach ($results as $result) {
        echo '<tr>';
        echo '<td scope="row" class="align-middle">' . from10_to62($result['id']) . '</td>';
        echo '<td class="align-middle">' . $result['url'] . '</td>';

        echo '<td>';
        echo '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modifyModal" data-bs-siteurl="' . $siteurl . '" data-bs-id62="' . from10_to62($result['id']) . '" data-bs-url="' . $result['url'] . '">修改';
        echo '</button>';
        echo '</td>';

        echo '<td>';
        echo '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-siteurl="' . $siteurl . '" data-bs-id62="' . from10_to62($result['id']) . '" data-bs-url="' . $result['url'] . '">删除';
        echo '</button>';
        echo '</td>';

        echo '</tr>';
    }
}

$db->disconnect();

function from10_to62($num) {
    $to = 62;
    $num = intval($num);
    $dict = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $ret = '';
    do {
        $ret = $dict[bcmod($num, $to)] . $ret;
        $num = bcdiv($num, $to);
    } while ($num > 0);
    return $ret;
}
?>