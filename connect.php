
<?php

// 資料庫連線

$config['db']['dsn']='mysql:host=localhost; dbname=rd1; charset=utf8';

// 資料庫的帳號密碼 >>> 要依照你的資料做設定
$config['db']['user'] = 'root';
$config['db']['password'] = '';

$db = new PDO(
    $config['db']['dsn'],
    $config['db']['user'],
    $config['db']['password'],
    [
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
);

