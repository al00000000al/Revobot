<?php

if(isset($_POST['q']) && isset($_POST['key'])) {
    $ch = curl_init();
    $key = $_POST['key'];
    $data = @json_decode($_POST['q'], true);
    $data2 = json_encode($data);
    curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data2);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer {$key}" // Replace sk-KEY with your actual key
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    echo $response;


} else {
    print('Hello world!');
}
