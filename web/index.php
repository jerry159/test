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
	   if("時間" == $event->message->text ){
		   $newtime=time();
		   $time = "現在時間:".date("Y-m-d H:i:s",$newtime);
		   $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($time);
	   }elseif("活動" == $event->message->text){
		   $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("相關活動請參考此連結 : https://devdocs.line.me/en/#messaging-api");
	   }elseif("報名" == $event->message->text){
			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("請輸入你的EMAIL");
	  }elseif("你的名字" == $event->message->text){
			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("你好!! 我是名字鋼彈機器人");
	  }elseif("我的名字" == $event->message->text){
			$youname ; 
			if("user" == $event->source->type)
				$response = $bot->getProfile($event->source->userId);
				if ($response->isSucceeded()) {
				$profile = $response->getJSONDecodedBody();
				$youname =  $profile['displayName'];
				//echo $profile['pictureUrl'];
				//echo $profile['statusMessage'];
			}
			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("你好!! 你的名子是".$youname);
			$response = $bot->replyMessage($event->replyToken ,$textMessageBuilder );
		   if ($response->isSucceeded()) {
				echo 'Succeeded!';
			}else{		error_log("第52行".$response->getHTTPStatus . ' ' . $response->getRawBody());}
			return;
	  }elseif("報名" == $event->message->text){
			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("請輸入你的EMAIL");
	  }elseif("挑戰問題" == $event->message->text){
			
			$actions = array(
			  //一般訊息型 action
			  new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder("按鈕1","文字1"),
			  //網址型 action
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder("Google","http://www.google.com"),
			  //下列兩筆均為互動型action
			  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("下一頁", "error"),
			  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("上一頁", "page=1")
			);
			 
			$img_url = "https://qiita-image-store.s3.amazonaws.com/0/53041/6fdf1c24-0d22-0ef3-1d09-a8ede16dba62.png";
			$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("按鈕文字","說明", $img_url, $actions);
			$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("這訊息要用手機的賴才看的到哦", $button);
			$response = $bot->replyMessage($event->replyToken ,$msg );
			return;
	  }else{
	   $servertext = "我看不懂你說的，目前提供服務列表如下\n 請輸入【時間】可以查詢目前時間 \n 請輸入【活動】 顯示目前動資訊\n 請輸入【報名】 顯示目前動資訊\n";	   
	   $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextandStickerMessageBuilder("1","2",$servertext);
	   $response = $bot->replyMessage($event->replyToken ,$textMessageBuilder );
       if ($response->isSucceeded()) {
			echo 'Succeeded!';
		}else{		error_log("第52行".$response->getHTTPStatus . ' ' . $response->getRawBody());}
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
}elseif ('postback' == $event->type) {         //Beaconイベント
    error_log( print_r($event->postback->data, TRUE) );
	if("page=1" == $event->postback->data ){
		$actions = array(
			  //一般訊息型 action
			  new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder("按鈕1","文字1"),
			  //網址型 action
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder("Google","http://www.google.com"),
			  //下列兩筆均為互動型action
			  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("下一頁", "error"),
			  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("上一頁", "page=2")
			);
			 
			$img_url = "https://qiita-image-store.s3.amazonaws.com/0/53041/6fdf1c24-0d22-0ef3-1d09-a8ede16dba62.png";
			$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("問題二","說明", $img_url, $actions);
			$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("這訊息要用手機的賴才看的到哦", $button);
			$response = $bot->replyMessage($event->replyToken ,$msg );
			return;
	}if("page=2" == $event->postback->data ){
		$actions = array(
			  //一般訊息型 action
			  new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder("按鈕1","文字1"),
			  //網址型 action
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder("Google","http://www.google.com"),
			  //下列兩筆均為互動型action
			  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("下一頁", "error"),
			  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("上一頁", "page=3")
			);
			 
			$img_url = "https://qiita-image-store.s3.amazonaws.com/0/53041/6fdf1c24-0d22-0ef3-1d09-a8ede16dba62.png";
			$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("問題二","說明", $img_url, $actions);
			$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("這訊息要用手機的賴才看的到哦", $button);
			$response = $bot->replyMessage($event->replyToken ,$msg );
			return;
	}if("page=3" == $event->postback->data ){
		$actions = array(
			  //一般訊息型 action
			  new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder("按鈕1","文字1"),
			  //網址型 action
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder("Google","http://www.google.com"),
			  //下列兩筆均為互動型action
			  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("下一頁", "error"),
			  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("上一頁", "page=4")
			);
			 
			$img_url = "https://qiita-image-store.s3.amazonaws.com/0/53041/6fdf1c24-0d22-0ef3-1d09-a8ede16dba62.png";
			$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("問題二","說明", $img_url, $actions);
			$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("這訊息要用手機的賴才看的到哦", $button);
			$response = $bot->replyMessage($event->replyToken ,$msg );
			return;
	}if("page=4" == $event->postback->data ){
		$actions = array(
			  //一般訊息型 action
			  new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder("按鈕1","文字1"),
			  //網址型 action
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder("Google","http://www.google.com"),
			  //下列兩筆均為互動型action
			  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("下一頁", "error"),
			  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("上一頁", "page=5")
			);
			 
			$img_url = "https://qiita-image-store.s3.amazonaws.com/0/53041/6fdf1c24-0d22-0ef3-1d09-a8ede16dba62.png";
			$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("問題二","說明", $img_url, $actions);
			$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("這訊息要用手機的賴才看的到哦", $button);
			$response = $bot->replyMessage($event->replyToken ,$msg );
			return;
	}if("page=5" == $event->postback->data ){
		    
			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("全部答對!!!);
			$response = $bot->replyMessage($event->replyToken ,$textMessageBuilder );
			return;
	}
} else {
    //なにもしない
}
$response = $bot->replyMessage($event->replyToken ,$textMessageBuilder );
error_log("輸出".print_r($event, TRUE) );
error_log( print_r($response, TRUE) );
return;
?>