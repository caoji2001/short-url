<?php
$config_file = dirname(__FILE__).'/../system/config.inc.php';
include($config_file);

if ($db_config) {
    header('Location: ../');
    exit(0);
}

@touch($config_file);

switch($_GET['step']) {
    default:
        $content = '<p>欢迎使用安装程序！</p>';
        $content .= '<p>本程序将帮助你在服务器上配置“短网址服务”</p>';
        $content .= '<p>点击右下角的“下一步”开始</p>';
        $content .= '<div class="row">';
        $content .= '<div class="col-3 offset-9">';
        $content .= '<a class="btn btn-primary" href="?step=database" role="button">下一步</a>';
        $content .= '</div>';
        $content .= '</div>';

        show_page($content);

        break;

    case 'database':
        $content = '<form action="?step=install" method="post">';
        $content .= '<div class="mb-3 row">';
        $content .= '<label for="db_server" class="col-sm-4 col-form-label">数据库地址：</label>';
        $content .= '<div class="col-sm-6">';
        $content .= '<input type="text" class="form-control" name="db_server" id="db_server">';
        $content .= '</div>';
        $content .= '</div>';

        $content .= '<div class="mb-3 row">';
        $content .= '<label for="db_port" class="col-sm-4 col-form-label">数据库端口：</label>';
        $content .= '<div class="col-sm-6">';
        $content .= '<input type="number" class="form-control" name="db_port" id="db_port">';
        $content .= '</div>';
        $content .= '</div>';

        $content .= '<div class="mb-3 row">';
        $content .= '<label for="db_username" class="col-sm-4 col-form-label">数据库用户名：</label>';
        $content .= '<div class="col-sm-6">';
        $content .= '<input type="text" class="form-control" name="db_username" id="db_username" value="root">';
        $content .= '</div>';
        $content .= '</div>';

        $content .= '<div class="mb-3 row">';
        $content .= '<label for="db_password" class="col-sm-4 col-form-label">数据库密码：</label>';
        $content .= '<div class="col-sm-6">';
        $content .= '<input type="password" class="form-control" name="db_password" id="db_password">';
        $content .= '</div>';
        $content .= '</div>';

        $content .= '<div class="mb-3 row">';
        $content .= '<label for="db_name" class="col-sm-4 col-form-label">数据库名：</label>';
        $content .= '<div class="col-sm-6">';
        $content .= '<input type="text" class="form-control" name="db_name" id="db_name" value="dwz">';
        $content .= '</div>';
        $content .= '</div>';

        $content .= '<div class="mb-3 row">';
        $content .= '<label for="username" class="col-sm-4 col-form-label">管理员用户名：</label>';
        $content .= '<div class="col-sm-6">';
        $content .= '<input type="text" class="form-control" name="username" id="username" value="admin">';
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
        $content .= '<button type="submit" class="btn btn-primary">下一步</button>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '</form>';

        show_page($content);

        break;

    case 'install':
        $db_server = $_POST['db_server'];
        $db_port = $_POST['db_port'];
        $db_username = $_POST['db_username'];
        $db_password = $_POST['db_password'];
        $db_name = $_POST['db_name'];
        $username = safe_input($_POST['username']);
        $password = md5($_POST['password']);

        $conn = new mysqli("{$db_server}:{$db_port}", $db_username, $db_password);

        if ($conn->connect_error) {
            show_back('数据库连接失败：' . $conn->connect_error);
            exit(0);
        }

        $selected = $conn->select_db($db_name);
        if (!$selected) {
            if (!($conn->query('CREATE DATABASE ' . $db_name))) {
                show_back('创建数据库失败：' . $conn->error);
                exit(0);
            }

            $selected = $conn->select_db($db_name);
            if (!$selected) {
                show_back('指定的数据库不可用：' . $conn->error);
                exit(0);
            }
        }

        if (!$username || !$password) {
            show_back('输入的信息不完整！');
            exit(0);
        }

        if (strlen($username) < 4 || strlen($username) > 16) {
            show_back('用户名长度应在4至16位之间！');
            exit(0);
        }

        $install_script = file_get_contents(dirname(__FILE__) . '/install.sql');
        $install_script .= "INSERT INTO user (`username`, `password`) VALUES ('$username', '$password')";

        if (!$conn->multi_query($install_script)) {
            show_back('安装过程出现错误：' . $conn->error);
            exit(0);
        }

        $db_config = array(
            'server' => $db_server,
            'port' => $db_port,
            'username' => $db_username,
            'password' => $db_password,
            'name' => $db_name,
        );

        $config_content = '<?php' . PHP_EOL . '/* Auto-generated config file */' . PHP_EOL . '$db_config = ';
        $config_content .= var_export($db_config, true) . ';' . PHP_EOL . '?>';
        file_put_contents($config_file, $config_content);

        $content = '<p>短网址服务已成功安装！</p><p>接下来，请进行重定向配置！</p>';
        $content .= '<div class="row">';
        $content .= '<div class="col-3 offset-9">';
        $content .= '<button type="button" class="btn btn-primary" onclick="location.href=\'../\';">结束</button>';
        $content .= '</div>';
        $content .= '</div>';
        show_page($content);
}

function safe_input($data) {
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function show_page($content) {
    $template = '<!doctype html><html lang="zh-CN"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>短网址服务 - 缩短长链接！</title><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"></head><body><div class="container-fluid px-0"><nav class="navbar bg-light"><div class="container-fluid"><span class="navbar-brand mb-0 h1">短网址服务 - 安装程序</span></div></nav><div class="row py-4"><div class="col-3 d-none d-sm-flex"></div><div class="col-12 col-sm-6">{content}</div><div class="col-3 d-none d-sm-flex"></div></div></div></body></html>';

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