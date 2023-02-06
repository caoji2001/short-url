<?php
$core_file = dirname(__FILE__).'/../system/core.php';
require_once($core_file);
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

    $random_number = rand(14776337, 916132832); // [62^4 + 1, 62^5]
    while ($db->where('id', $random_number)->getValue('fwlink', 'count(*)') > 0) {
        $random_number = rand(14776337, 916132832); // [62^4 + 1, 62^5]
    }

    $nice = $db->insert('fwlink', Array('id' => $random_number, 'url' => $input_url));
    if ($nice) {    
        $fwlink = get_site_url() . from10_to62($random_number);

        show_valid_page($fwlink);
    } else {
        show_invalid_page($input_url, '数据库语句执行出错！' . $db->getLastError());
    }
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