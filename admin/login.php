<?php
$core_file = dirname(__FILE__).'/../system/core.php';
require_once($core_file);
$MysqliDb_file = dirname(__FILE__).'/../system/MysqliDb.php';
require_once($MysqliDb_file);

session_start();
if (isset($_SESSION['username'])) {
    header('Location: ./index.php');
    exit(0);
}

switch(@$_GET['step']) {
    default:
        $content = '<form action="?step=login" method="post">';

        $content .= '<div class="mb-3 row">';
        $content .= '<label for="username" class="col-sm-4 col-form-label">管理员用户名：</label>';
        $content .= '<div class="col-sm-6">';
        $content .= '<input type="text" class="form-control" name="username" id="username">';
        $content .= '</div>';
        $content .= '</div>';

        $content .= '<div class="mb-3 row">';
        $content .= '<label for="password" class="col-sm-4 col-form-label">管理员密码：</label>';
        $content .= '<div class="col-sm-6">';
        $content .= '<input type="password" class="form-control" name="password" id="password">';
        $content .= '</div>';
        $content .= '</div>';

        $content .= '<div class="row">';
        $content .= '<div class="col-3 offset-9">';
        $content .= '<button type="submit" class="btn btn-primary">登陆</button>';
        $content .= '</div>';
        $content .= '</div>';

        $content .= '</form>';

        show_page($content);

        break;

    case 'login':
        $username = $_POST['username'];
        $password = md5($_POST['password']);

        if (!$username || !$password) {
            show_back('输入的信息不完整！');
            exit(0);
        }

        $db = new MysqliDb (Array (
            'host' => $db_config['server'],
            'username' => $db_config['username'], 
            'password' => $db_config['password'],
            'db'=> $db_config['name'],
            'port' => $db_config['port']));

        if ($db->where('username', $username)->where('password', $password)->getValue('user', 'count(*)') === 0) {
            show_back('用户名或密码错误！');
        } else {
            $_SESSION['username'] = $username;
            $_SESSION['admin'] = $db->where('username', $username)->where('password', $password)->getValue('user', 'admin');
            header('Location: ./index.php');
        }

        $db->disconnect();
}

function show_page($content) {
    $template = '<!doctype html><html lang="zh-CN"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>短网址服务 - 缩短长链接！</title><link rel="stylesheet" href="../assets/css/bootstrap.min.css"></head><body><nav class="navbar bg-light"><div class="container-fluid"><span class="navbar-brand mb-0 h1">短网址服务 - 管理面板</span></div></nav><div class="container-fluid"><div class="row py-4"><div class="col-3 d-none d-sm-flex"></div><div class="col-12 col-sm-6">{content}</div><div class="col-3 d-none d-sm-flex"></div></div></div></body></html>';

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