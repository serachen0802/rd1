<?php
require_once("connect.php");
header("content-type: text/html; charset=utf-8");

ignore_user_abort();
set_time_limit(0);
$interval=60;
do{

$a = $db->query("SELECT * FROM `Football`");
$data = $a->fetchAll(PDO::FETCH_ASSOC);

$mem = new Memcached();
$mem->addServer("localhost", 11211);
$result = $mem->set("memData", $data);

sleep($interval);
}while(true);