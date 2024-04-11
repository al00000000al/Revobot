<?php

require __DIR__ . '/../vendor/autoload.php';

use Revobot\Config;
use Revobot\Services\Providers\Tg;
use Revobot\Util\PMC;

require __DIR__ . '/../config.php';

if ($argc < 1) {
    echo "Идентификатор пользователя не передан.\n";
    exit(1);
}

$themes = ['литературы', 'математики', 'pусского языка', 'химии', "физики", "истории", "обществознания", "информатики", "изобразительного искусства", "музыки", "биологии", "геометрии", "географии", "аглийского языка"];

$chat_id = (int)$argv[1];

$input = 'Сгенерируй сложный вопрос на тему ' . $themes[mt_rand(0, count($themes) - 1)] . ' из ОГЭ для викторины на деньги в json формате question, answers => [id, text], correct_number. В ответе пиши только json и ничего больше. Текст вопроса не слишком длинный';

$data = [
    'model' => 'gpt-4-turbo',
    'messages' => [
        [
            'role' => 'user',
            'content' => $input,
        ]
    ],
    'max_tokens' => 500
];

$ch = curl_init(Config::get('openai_api_host'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['q' => json_encode($data), 'key' => Config::get('openai_api_key')]));
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Ошибка cURL: ' . curl_error($ch);
    exit(1);
} else {
    $decodedResponse = json_decode($response, true);
}
curl_close($ch);

if (isset($decodedResponse['choices'][0]['message']['content'])) {
    $answer = $decodedResponse['choices'][0]['message']['content'];
    echo $answer;
    $answer = trim(str_replace(['```json', '```'], '', $answer));
    $data = formatAnswer($answer);
    PMC::set('quiz_question_current_tg' . $chat_id, $data);
    print_r($data);
    Tg::sendMessage($chat_id, $data['question']);
}

function formatAnswer($response)
{
    $data = @json_decode($response, true);
    if (!$data) {
        exit;
    }
    $question = $data['question'] . "\n";
    foreach ($data['answers'] as $ans) {
        $question .= $ans['id'] . ' ' . $ans['text'] . "\n";
    }
    $answer = $data['correct_number'];
    return [
        'question' => $question,
        'answer' => $answer,
    ];
}
