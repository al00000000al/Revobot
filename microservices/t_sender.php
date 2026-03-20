<?php

require_once 'config.php';

// ===== INPUT =====
$key    = $_GET['key'] ?? '';
$token  = $_GET['token'] ?? '';
$method = $_GET['method'] ?? '';

// ===== AUTH =====
if (!hash_equals(TG_SECRET_KEY, $key)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'forbidden']);
    exit;
}

// ===== VALIDATION =====
if (!$token || !$method) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'bad request']);
    exit;
}


// ===== REQUEST TO TG =====
$url = "https://api.telegram.org/bot{$token}/{$method}";

$post = $_POST;

// поддержка файлов
if (!empty($_FILES)) {
    foreach ($_FILES as $name => $file) {
        $post[$name] = new CURLFile(
            $file['tmp_name'],
            $file['type'],
            $file['name']
        );
    }
}

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $post,
    CURLOPT_TIMEOUT        => 20,
]);

$response = curl_exec($ch);

if ($response === false) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => curl_error($ch)
    ]);
    curl_close($ch);
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// ===== OUTPUT =====
http_response_code($httpCode);
header('Content-Type: application/json');
echo $response;
