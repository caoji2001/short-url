<?php
$db = new MysqliDb (Array (
    'host' => $db_config['server'],
    'username' => $db_config['username'], 
    'password' => $db_config['password'],
    'db'=> $db_config['name'],
    'port' => $db_config['port']));

$results = $db->get('fwlink', null, Array('id', 'url'));

if ($db->count > 0) {
    foreach ($results as $result) {
        echo '<tr>';
        echo '<td scope="row" class="align-middle">' . from10_to62($result['id']) . '</td>';
        echo '<td class="align-middle">' . $result['url'] . '</td>';

        echo '<td>';
        echo '<button type="button" class="btn btn-outline-warning m-1" data-bs-toggle="modal" data-bs-target="#modifyModal" data-bs-siteurl="' . get_site_url() . '" data-bs-id62="' . from10_to62($result['id']) . '" data-bs-url="' . $result['url'] . '">修改';
        echo '</button>';

        echo '<button type="button" class="btn btn-outline-danger m-1" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-siteurl="' . get_site_url() . '" data-bs-id62="' . from10_to62($result['id']) . '" data-bs-url="' . $result['url'] . '">删除';
        echo '</button>';
        echo '</td>';

        echo '</tr>';
    }
}

$db->disconnect();
?>