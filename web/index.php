<?php
session_start();
date_default_timezone_set('Asia/Taipei');
//載入LINE BOT SDK
require_once __DIR__ . '/vendor/autoload.php';
//接收資料 
$input = file_get_contents('php://input');
$json = json_decode($input);
$event = $json->events[0];

//設定LINE bot 相關參數
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient('CFvNdr2w47+0oB2QECf7UPB+ttgJCWeXXT+A5sflL+LYmK7nPyncW0pRaAO7DABzNub2MuhamUrtjx39F1nE2sq3pVP0WejYolMKz+dYhb66voJXRuolqJT0wNza3rfT8eLsjxQrB28u0zpD4em2DQdB04t89/1O/w1cDnyilFU=');
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => '0651815f918a41ca3442ed5c8397dbb7']);

$servertext = "看不懂你說的，目前提供服務輸入\n '時間'-->可以現在時間\n '目前活動'\n ''";

$response = $bot->replyMessage($event->replyToken, "用戶訊息:".$event);


//進行判斷使用類別
if ("message" == $event->type) {            //一般的なメッセージ(文字・イメージ・音声・位置情報・スタンプ含む)
	
    //テキストメッセージにはオウムで返す
    if ("text" == $event->message->type) {
	   	$order = $event->message->text ;
	   if("時間" == $event->message->text ){
		   $newtime=time();
		   $time = "現在時間:".date("Y-m-d H:i:s",$newtime);
		   $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($time);
	   }elseif("目前活動" == $event->message->text){
		   $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("相關活動請參考此連結 : https://devdocs.line.me/en/#messaging-api");
	   }elseif("課程報名" == $event->message->text){
			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("請輸入你的EMAIL");
			//$response = $bot->replyMessage($event->replyToken, $textMessageBuilder);
			//syslog(LOG_EMERG, print_r($event->replyToken, true));
			//syslog(LOG_EMERG, print_r($response, true));
	  }else{
		$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($servertext );
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