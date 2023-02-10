<?php
$db = new MysqliDb (Array (
    'host' => $db_config['server'],
    'username' => $db_config['username'], 
    'password' => $db_config['password'],
    'db'=> $db_config['name'],
    'port' => $db_config['port']));

$results = $db->get('blacklist', null, Array('domain'));

if ($db->count > 0) {
    foreach ($results as $result) {
        echo '<tr>';
        echo '<td class="align-middle">' . $result['domain'] . '</td>';

        echo '<td>';
        echo '<button type="button" class="btn btn-outline-danger m-1" data-bs-toggle="modal" data-bs-target="#deleteModal">删除</button>';
        echo '</td>';

        echo '</tr>';
    }
}

$db->disconnect();
?>