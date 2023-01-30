<?php
$config_file = dirname(__FILE__).'/../system/config.inc.php';
include($config_file);

if (!$db_config) {
    header('Location: ../install/');
    exit(0);
}

session_start();

if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header('Location: ./login.php');
    exit(0);
}

$conn = @new mysqli($db_config['server'], $db_config['username'], $db_config['password'], $db_config['name'], $db_config['port']);

if ($conn->connect_error) {
    show_back('数据库连接失败：' . $conn->connect_error);
    exit(0);
}

$id = safe_input($_GET['id']);
$sql = "DELETE from `fwlink` WHERE `id` = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $stmt->close();
    show_back('成功删除数据！');
} else {
    show_back('无法删除数据：' . $conn->error);
}
$conn->close();

function safe_input($data) {
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function show_page($content) {
    $template = '<!doctype html><html lang="zh-CN"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>短网址服务 - 缩短长链接！</title><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"></head><body><div class="container-fluid px-0"><nav class="navbar bg-light"><div class="container-fluid"><span class="navbar-brand mb-0 h1">短网址服务 - 管理面板</span><a class="btn btn-outline-danger" href="./logout.php" role="button">登出</a></div></nav><div class="row py-4"><div class="col-1 d-none d-sm-flex"></div><div class="col-12 col-sm-10">{content}</div><div class="col-1 d-none d-sm-flex"></div></div></div></body></html>';

    echo str_replace('{content}', $content, $template);
}

function show_back($text) {
    $content = '<p>' . $text . '</p>';
    $content .= '<div class="row">';
    $content .= '<div class="col-3 offset-9">';
    $content .= '<button type="button" class="btn btn-primary" onclick="history.back();">返回</button>';
    $content .= '</div>';
    $content .= '</div>';

    show_page($content);
}
?>