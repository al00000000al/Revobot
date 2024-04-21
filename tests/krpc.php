<?php

$conn = new_rpc_connection('127.0.0.1', 11209);
$user_id = 198239789;
$query_ids = rpc_tl_query(
    $conn,
    [
        ['memcache.get', 'user_timezone_tg' . $user_id],
        ['memcache.get', 'tg_username' . $user_id],
        ['memcache.get', 'money_tg' . $user_id]
    ]
);
var_dump($query_ids);
$response = rpc_tl_query_result($query_ids);
var_dump($response);
