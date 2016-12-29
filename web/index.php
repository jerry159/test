<?php

date_default_timezone_set('Asia/Taipei');
//載入LINE BOT SDK
require_once __DIR__ . '/vendor/autoload.php';
//接收資料 
$input = file_get_contents('php://input');
$json = json_decode($input);
$event = $json->events[0];

error_log( print_r($event, TRUE) );

//設定LINE bot 相關參數
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient('sI+voOXTEQss74igmy+TAiWwKzgssW4xHn20K/SfFTt42k5tkrvPi04N13n6B8MXNub2MuhamUrtjx39F1nE2sq3pVP0WejYolMKz+dYhb6X4CeKbxv7rAb05/72fCeRP38QBI/gJpYoV2TvboDPoQdB04t89/1O/w1cDnyilFU=');
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => '0651815f918a41ca3442ed5c8397dbb7']);



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
	  }else{
	   //$textMessageBuilder =  array(array("type"=> "text","text"=> "看不懂你說的，目前提供服務輸入\n '時間'-->可以現在時間\n '目前活動'\n"),array("type"=> "sticker","packageId"=>"1",  "stickerId"=>"1"));
	   $servertext = "看不懂你說的，目前提供服務列表如下\n 請輸入【時間】可以查詢目前時間 \n 請輸入【活動】 \n";	   
	   $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextandStickerMessageBuilder($servertext,"1","1");
	   //$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($servertext );
	   //$stickerMessageBuilder = new \LINE\LINEBot\MessageBuilder\StickerMessageBuilder("1","1");
	   //$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder("https://jpeg.org/images/jpeg-home.jpg","https://jpeg.org/images/jpeg-home.jpg");//圖片
	   $response = $bot->replyMessage($event->replyToken ,$textMessageBuilder );
       if ($response->isSucceeded()) {
			echo 'Succeeded!';
			$response_1 = $bot->pushMessage("to" ,$stickerMessageBuilder );
			
			//if ($response_1->isSucceeded()) {
			//error_log('Succeeded!');
			//return;
			//}
			// Failed
			//error_log("第46行".$response_1->getHTTPStatus . ' ' . $response_1->getRawBody());
			//return;
		}
		error_log("第52行".$response->getHTTPStatus . ' ' . $response->getRawBody());
		return;
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

$response = $bot->replyMessage($event->replyToken ,$textMessageBuilder );
//syslog(LOG_EMERG, print_r($event->replyToken, true));
//syslog(LOG_EMERG, print_r($response, true));
error_log("輸出".print_r($event, TRUE) );
error_log( print_r($response, TRUE) );
return;
?>