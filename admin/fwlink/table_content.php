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

$data = array();

$db = new MysqliDb (Array (
    'host' => $db_config['server'],
    'username' => $db_config['username'], 
    'password' => $db_config['password'],
    'db'=> $db_config['name'],
    'port' => $db_config['port']));

$results = $db->get('fwlink', null, Array('id', 'url'));

if ($db->count > 0) {
    foreach ($results as $result) {
        array_push($data, array(
            'id' => from10_to62($result['id']),
            'url' => $result['url'],
            'operation' => ''));
    }
}

$db->disconnect();

echo json_encode($data);
?>