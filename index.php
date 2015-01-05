<?php
header('content-type:text/html; charset=utf-8');
include 'Pinyin.class.php';

$pin = new Pinyin();

$foo = '测试'; 
echo $foo . '=>' . $pin->get($foo) . '<br>';
