<?php

$url = 'https://api.themoviedb.org/3/movie/';

if (!isset($_POST['method'])) {
    die('method required');
}
$method = $_POST['method'];
unset($_POST['method']);
$data = @file_get_contents($url . $method . '?' . http_build_query($_POST));
echo $data;
exit;
