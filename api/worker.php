<?php
$config_file = dirname(__FILE__).'/../system/config.inc.php';
include($config_file);

$input_url = safe_input($_POST['input_url']);
$conn = @new mysqli($db_config['server'], $db_config['username'], $db_config['password'], $db_config['name'], $db_config['port']);

if (empty($input_url)) {
    show_invalid_page('', '请输入要缩短的长链接！');
} elseif (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $input_url)) {
    show_invalid_page($input_url, '输入的长链接不合法！');
} elseif ($conn->connect_error) {
    show_invalid_page($input_url, '数据库连接失败！' . $conn->connect_error);
} else {
    $sql = "INSERT INTO fwlink(`url`) VALUES (?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('s', $input_url);
        $stmt->execute();
        $last_id = $conn->insert_id;
        $stmt->close();

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
        $fwlink = $protocol . $_SERVER['HTTP_HOST'] . '/' . from10_to62($last_id);

        show_valid_page($fwlink);
    } else {
        show_invalid_page($input_url, '数据库语句执行出错！' . $conn->connect_error);
    }
}

$conn->close();

function safe_input($data) {
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

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

function from62_to10($num) {
    $from = 62;
    $num = strval($num);
    $dict = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $len = strlen($num);
    $dec = 0;
    for($i = 0; $i < $len; $i++) {
        $pos = strpos($dict, $num[$i]);
        $dec = bcadd(bcmul($pos, bcpow($from, $len - $i - 1)), $dec);
    }
    return $dec;
}

function show_valid_page($fwlink) {
    $template = '<div class="col-12 col-sm-10 py-2"><input type="text" class="form-control is-valid" id="input_url" value="{fwlink}" readonly><div class="d-block valid-feedback">长链接已缩短！</div></div><div class="col-12 col-sm-2 py-2"><a class="d-block btn btn-primary" href="{fwlink}" target="_blank" role="button">打开</a></div>';

    echo str_replace('{fwlink}', $fwlink, $template);
}

function show_invalid_page($input_url, $content) {
    $template = '<div class="col-12 col-sm-10 py-2"><input type="text" class="form-control is-invalid" id="input_url" value="{input_url}"><div class="d-block invalid-feedback">{content}</div></div><div class="col-12 col-sm-2 py-2"><button type="submit" class="btn btn-primary w-100" onclick="show_url()">缩短</button></div>';

    echo str_replace(array('{input_url}', '{content}'), array($input_url, $content), $template);
}

?>