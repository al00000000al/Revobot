<?php

require __DIR__ . '/vendor/autoload.php';
use Orhanerday\OpenAi\OpenAi;

const OPENAI_API_KEY = '';

$open_ai = new OpenAi(OPENAI_API_KEY);

$chat = $open_ai->chat([
   'model' => 'gpt-3.5-turbo',
   'messages' => [
       [
           "role" => "system",
           "content" => "Ты Революся чат-бот в беседе СэдКэт"
       ],
       [
           "role" => "user",
           "content" => "что лучше ты или чат жпт?"
       ],
   ],
   'temperature' => 1.0,
   'max_tokens' => 100,
   'frequency_penalty' => 0,
   'presence_penalty' => 0,
]);

print_r($chat);
// decode response
$d = json_decode($chat);
// Get Content
echo($d->choices[0]->message->content);
