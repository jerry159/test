<?php

require_once __DIR__ . '/vendor/autoload.php';
//POST
$input = file_get_contents('php://input');
$json = json_decode($input);
$event = $json->events[0];

// Show all information, defaults to INFO_ALL
//phpinfo();
// 給 LINE 用戶端 相關參數
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient('CFvNdr2w47+0oB2QECf7UPB+ttgJCWeXXT+A5sflL+LYmK7nPyncW0pRaAO7DABzNub2MuhamUrtjx39F1nE2sq3pVP0WejYolMKz+dYhb66voJXRuolqJT0wNza3rfT8eLsjxQrB28u0zpD4em2DQdB04t89/1O/w1cDnyilFU=');
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => '0651815f918a41ca3442ed5c8397dbb7']);

//$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('hello');
//$response = $bot->replyMessage('<reply token>', $textMessageBuilder);
//if ($response->isSucceeded()) {
//    echo 'Succeeded!';
//    return;
//}

// Failed
//echo $response->getHTTPStatus . ' ' . $response->getRawBody();

?>