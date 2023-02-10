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
    show_back('请输入要加入黑名单的域名！');
} else {
    $nice = $db->insert('blacklist', Array('domain' => $domain));

    if ($nice) {
        show_back('成功添加黑名单域名！');
    } else {
        show_back('添加黑名单域名失败：' . $db->getLastError());
    }
}

$db->disconnect();

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