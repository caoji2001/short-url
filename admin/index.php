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
    show_error('数据库连接失败：' . $conn->connect_error);
    exit(0);
}

$sql = 'SELECT `id`, `url` FROM `fwlink`';
$result = $conn->query($sql);

if (!$result) {
    show_back('数据库查询出现错误：' . $conn->error);
    exit(0);
}

$content = '<table class="table table-sm table-hover">';
$content .= '<thead>';
$content .= '<tr>';
$content .= '<th scope="col">#</th>';
$content .= '<th scope="col">URL</th>';
$content .= '<th scope="col">删除</th>';
$content .= '</tr>';
$content .= '</thead>';
$content .= '<tbody class="table-group-divider">';

if ($result->num_rows > 0) {
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
    $site_url = $protocol . $_SERVER['HTTP_HOST'] . '/';

    while ($row = $result->fetch_assoc()) {
        $content .= '<tr>';
        $content .= '<th scope="row" class="align-middle">' . from10_to62($row['id']) . '</th>';
        $content .= '<th class="align-middle">' . $row['url'] . '</th>';
        $content .= '<th>';

        $content .= '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#delete' . $row['id'] . '">删除';
        $content .= '</button>';

        $content .= '<div class="modal fade" id="delete' . $row['id'] . '" tabindex="-1">';
        $content .= '<div class="modal-dialog">';
        $content .= '<div class="modal-content">';
        $content .= '<div class="modal-header">';
        $content .= '<h1 class="modal-title fs-5">删除确认</h1>';
        $content .= '<button type="button" class="btn-close" data-bs-dismiss="modal"></button>';
        $content .= '</div>';
        $content .= '<div class="modal-body">';
        $content .= '你确定要删除短链接<code>' . $site_url . from10_to62($row['id']) . '</code>吗？';
        $content .= '</div>';
        $content .= '<div class="modal-footer">';
        $content .= '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>';
        $content .= '<a class="btn btn-danger" href="./del.php?id=' . $row['id'] . '" role="button">删除</a>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '</div>';
        
        $content .= '</th>';
        $content .= '</tr>';
    }
}

$conn->close();

$content .= '</tbody>';
$content .= '</table>';

show_page($content);

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

function show_page($content) {
    $template = '<!doctype html><html lang="zh-CN"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>短网址服务 - 缩短长链接！</title><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"></head><body><div class="container-fluid px-0"><nav class="navbar bg-light"><div class="container-fluid"><span class="navbar-brand mb-0 h1">短网址服务 - 管理面板</span><a class="btn btn-outline-danger" href="./logout.php" role="button">登出</a></div></nav><div class="row py-4"><div class="col-1 d-none d-sm-flex"></div><div class="col-12 col-sm-10">{content}</div><div class="col-1 d-none d-sm-flex"></div></div></div></body><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script></html>';

    echo str_replace('{content}', $content, $template);
}

function show_error($text) {
    $content = '<p>' . $text . '</p>';

    show_page($content);
}
?>