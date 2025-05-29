<?php
$memcache = memcache_connect('localhost', 11211);
// $memcache->set("content_key", array(
//     "message" => "",
//     "id" => "123456.654321",
//     "nick" => "",
//     "timestamp" => "",
//     "color" => ""
// ));
echo var_dump($memcache->get("content_key"));
?>