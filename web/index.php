<?php

require_once __DIR__ . '/vendor/autoload.php';
//POST
$input = file_get_contents('php://input');
$json = json_decode($input);
$event = $json->events[0];

// 給 LINE 用戶端 相關參數
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient('CFvNdr2w47+0oB2QECf7UPB+ttgJCWeXXT+A5sflL+LYmK7nPyncW0pRaAO7DABzNub2MuhamUrtjx39F1nE2sq3pVP0WejYolMKz+dYhb66voJXRuolqJT0wNza3rfT8eLsjxQrB28u0zpD4em2DQdB04t89/1O/w1cDnyilFU=');
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => '0651815f918a41ca3442ed5c8397dbb7']);

//イベントタイプ判別
if ("message" == $event->type) {            //一般的なメッセージ(文字・イメージ・音声・位置情報・スタンプ含む)
    //テキストメッセージにはオウムで返す
    if ("text" == $event->message->type) {
	   	$order = $event->message->text ;
	   if("時間" == $event->message->text ){
		   $newtime=time();
		   $time = "現在時間:".date("Y-m-d H:i:s",$newtime);
		   $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($time);
	   }else{
		$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("請確認你輸入指令");
	   //$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder("https://jpeg.org/images/jpeg-home.jpg","https://jpeg.org/images/jpeg-home.jpg");//圖片
	   }
	}elseif("sticker" == $event->message->type){
		$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\StickerMessageBuilder("1","1");
	}else {
        $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("我看不懂你 @@");
    }
} elseif ("follow" == $event->type) {        //お友達追加時
    $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("よろしくー");
} elseif ("join" == $event->type) {           //グループに入ったときのイベント
    $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('こんにちは よろしくー');
} elseif ('beacon' == $event->type) {         //Beaconイベント
    $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('Godanがいんしたお(・∀・) ');
} else {
    //なにもしない
}
//$response = $bot->replyMessage($event->replyToken, $textMessageBuilder);
$response = $bot->replyMessage($event->replyToken, $textMessageBuilder);
syslog(LOG_EMERG, print_r($event->replyToken, true));
syslog(LOG_EMERG, print_r($response, true));
return;



?>