<?php
$config_file = dirname(__FILE__).'/config.inc.php';
include_once($config_file);

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
?>