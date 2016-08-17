<?php
header("content-type: text/html; charset=utf-8");

$mem = new Memcached();
$mem->addServer("localhost", 11211);

$arr = $mem->get("mData");

if (!$arr) {
    echo json_encode("404");
} else {
    echo json_encode($arr );
}
