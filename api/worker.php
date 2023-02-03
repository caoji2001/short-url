<?php
$config_file = dirname(__FILE__).'/../system/config.inc.php';
require_once($config_file);
$MysqliDb_file = dirname(__FILE__).'/../system/MysqliDb.php';
require_once($MysqliDb_file);

$input_url = $_POST['input_url'];

if (empty($input_url)) {
    show_invalid_page('', '请输入要缩短的长链接！');
} elseif (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $input_url)) {
    show_invalid_page($input_url, '输入的长链接不合法！');
} else {
    $db = new MysqliDb (Array (
        'host' => $db_config['server'],
        'username' => $db_config['username'], 
        'password' => $db_config['password'],
        'db'=> $db_config['name'],
        'port' => $db_config['port']));

    $id = $db->insert('fwlink', Array('url' => $input_url));
    if ($id) {
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
        $fwlink = $protocol . $_SERVER['HTTP_HOST'] . '/' . from10_to62($id);

        show_valid_page($fwlink);
    } else {
        show_invalid_page($input_url, '数据库语句执行出错！' . $db->getLastError());
    }
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