<?php
session_start();

if (!isset($_SESSION['count'])) {
  error_log("SESSION不見了" );
}
$youname ; 
//載入LINE BOT SDK
require_once __DIR__ . '/vendor/autoload.php';
//接收資料 
session_id('example_session_id');
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
			  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("約翰·C·史坦尼斯", "page=0,error"),
			  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("巴拉克·歐巴馬", "page=0,error"),
			  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("亞伯拉罕·林肯", "page=1,OK"),
			  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("尼米茲", "page=0,error")
			);
				
			//$_SESSION['challengequesion'] = $youname . ",page0";	
			$img_url = "https://qiita-image-store.s3.amazonaws.com/0/53041/6fdf1c24-0d22-0ef3-1d09-a8ede16dba62.png";
			$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("問題一","哪一位是美國解放黑奴的總統?", $img_url, $actions);
			$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("這訊息要用手機的賴才看的到哦", $button);
			$response = $bot->replyMessage($event->replyToken ,$msg );
			$_SESSION['count'] = 0;
			error_log("123--->" . $_SESSION['count'] );
			return;
	  }else{
	   $servertext = "我看不懂你說的，目前提供服務列表如下\n 請輸入【時間】可以查詢目前時間 \n 請輸入【活動】 顯示目前動資訊\n請輸入【報名】 顯示目前動資訊\n";	   
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
	error_log("123--->" . $_SESSION['count'] );
    $postback = explode(",",  $event->postback->data);
	$challengequesion_1 = explode(",",  $_SESSION["challengequesion"]);
	if("page=0" == $postback[0] ){
		if($challengequesion_1[1]==$postback[0]){
			$img_url ;
			$question ;	
			if("OK" == $postback[1] ){
				$_SESSION["challengequesion"]= $challengequesion_1[0] .",page1";
				$img_url = "https://qiita-image-store.s3.amazonaws.com/0/53041/6fdf1c24-0d22-0ef3-1d09-a8ede16dba62.png";
				$actions = array(
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("2006", "page=1,error"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("2004", "page=1,error"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("2002", "page=1,OK"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("2001", "page=1,error")
				);		
				$question = "中鋼股票代號是多少?";
			}elseif("error" == $postback[1]){
				$actions = array(
				 new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("約翰·C·史坦尼斯","page=0,error"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("巴拉克·歐巴馬", "page=0,error"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("亞伯拉罕·林肯", "page=0,OK"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("尼米茲", "page=0,error")
				);	
				$img_url = "https://lh6.googleusercontent.com/-f8qMmE_HzBQ/VEUtHp-hOjI/AAAAAAAABOk/ohddJ2bcVWE/s300-no/MaBaSaLu_Google_QR.png";
				$question = "哪一位是美國解放黑奴的總統??\n你選錯了，再來一次!!";
			}
		
			$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("問題一",$question, $img_url, $actions);
			$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("這訊息要用手機的賴才看的到哦", $button);
			$response = $bot->replyMessage($event->replyToken ,$msg );
			return;	
		}else{
			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("請繼續作答");
			$response = $bot->replyMessage($event->replyToken ,$textMessageBuilder );
			return;
		}
	}elseif("page=1" == $postback[0]){
		if($challengequesion_1[1]==$postback[0]){
			$img_url ;
			$question ;	
			if("OK" == $postback[1] ){
				$_SESSION["challengequesion"]= $challengequesion_1[0] .",page2";
				$img_url = "https://qiita-image-store.s3.amazonaws.com/0/53041/6fdf1c24-0d22-0ef3-1d09-a8ede16dba62.png";
				$question = "自然人憑證是哪個英文?";
				$actions = array(
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("MOICA", "page=2,OK"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("GCA", "page=2,error"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("MOEACA", "page=2,error"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("XCA", "page=2,error")
				);
			}elseif("error" == $postback[1]){
				$actions = array(
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("2006", "page=1,error"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("2004", "page=1,error"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("2002", "page=1,OK"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("2001", "page=1,error")
				);		
				$img_url = "https://lh6.googleusercontent.com/-f8qMmE_HzBQ/VEUtHp-hOjI/AAAAAAAABOk/ohddJ2bcVWE/s300-no/MaBaSaLu_Google_QR.png";
				$question = "中鋼股票代號是多少?\n你選錯了，再來一次!!";
			}

			$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("問題二",$question, $img_url, $actions);
			$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("這訊息要用手機的賴才看的到哦", $button);
			$response = $bot->replyMessage($event->replyToken ,$msg );
			return;
		}else{
			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("請繼續作答");
			$response = $bot->replyMessage($event->replyToken ,$textMessageBuilder );
			return;
		}
	}elseif("page=2" == $postback[0]){
		if($challengequesion_1[1]==$postback[0]){
			$img_url ;
			$question ;
			if("OK" == $postback[1] ){
				$_SESSION["challengequesion"]= $challengequesion_1[0] .",page3";
				$img_url = "https://qiita-image-store.s3.amazonaws.com/0/53041/6fdf1c24-0d22-0ef3-1d09-a8ede16dba62.png";
				$question = "請問那一座山在台北市?";
				$actions = array(
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("雪山", "page=3,error"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("阿里山", "page=3,error"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("大屯山", "page=3,OK"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("大霸尖山", "page=3,error")
				);
			}elseif("error" == $postback[1]){
				$img_url = "https://lh6.googleusercontent.com/-f8qMmE_HzBQ/VEUtHp-hOjI/AAAAAAAABOk/ohddJ2bcVWE/s300-no/MaBaSaLu_Google_QR.png";
				$question = "自然人憑證是哪個英文?\n你選錯了，再來一次!!";
				$actions = array(
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("MOICA", "page=2,OK"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("GCA", "page=2,error"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("MOEACA", "page=2,error"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("XCA", "page=2,error")
				);
			
			}
			
			$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("問題三",$question , $img_url, $actions);
			$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("這訊息要用手機的賴才看的到哦", $button);
			$response = $bot->replyMessage($event->replyToken ,$msg );
			return;
		}else{
			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("請繼續作答");
			$response = $bot->replyMessage($event->replyToken ,$textMessageBuilder );
			return;
		}
	}elseif("page=3" == $postback[0] ){
		if($challengequesion_1[1]==$postback[0]){
			$img_url ;
			$question ;
			if("OK" == $postback[1] ){
				$_SESSION["challengequesion"]= $challengequesion_1[0] .",page4";
				$img_url = "https://qiita-image-store.s3.amazonaws.com/0/53041/6fdf1c24-0d22-0ef3-1d09-a8ede16dba62.png";
				$question = "7+1=?";
				 $actions = array(
				  //下列均為互動型action
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("答案是5", "page=4,error"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("答案是6", "page=4,error"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("答案是7", "page=4,error"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("答案是8", "page=4,OK")
				);
				
			}elseif("error" == $postback[1]){
				$actions = array(
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("雪山", "page=3,error"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("阿里山", "page=3,error"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("大屯山", "page=3,OK"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("大霸尖山", "page=3,error")
				);
				$img_url = "https://lh6.googleusercontent.com/-f8qMmE_HzBQ/VEUtHp-hOjI/AAAAAAAABOk/ohddJ2bcVWE/s300-no/MaBaSaLu_Google_QR.png";
				$question = "請問那一座山在台北市?\n你選錯了，再來一次!!";
			}
			
			$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("問題四",$question, $img_url, $actions);
			$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("這訊息要用手機的賴才看的到哦", $button);
			$response = $bot->replyMessage($event->replyToken ,$msg );
			return;
	
	    }else{
			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("請繼續作答");
			$response = $bot->replyMessage($event->replyToken ,$textMessageBuilder );
			return;
		}
	}elseif("page=4" == $postback[0] ){
		 if($challengequesion_1[1]==$postback[0]){ 
			$img_url;
			$question ;
			if("OK" == $postback[1] ){
				$_SESSION["challengequesion"]= $challengequesion_1[0];
				$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("全部答對!!!");
				$response = $bot->replyMessage($event->replyToken ,$textMessageBuilder );
				return;
			
			}elseif("error" == $postback[1]){
				  $actions = array(
				  //下列均為互動型action
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("答案是5", "page=4,error"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("答案是6", "page=4,error"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("答案是7", "page=4,error"),
				  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("答案是8", "page=4,OK")
				);
				$img_url = "https://lh6.googleusercontent.com/-f8qMmE_HzBQ/VEUtHp-hOjI/AAAAAAAABOk/ohddJ2bcVWE/s300-no/MaBaSaLu_Google_QR.png";
				$question = "7+1=?\n你選錯了，再來一次!!";
			}
		
			$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("問題五",$question, $img_url, $actions);
			$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("這訊息要用手機的賴才看的到哦", $button);
			$response = $bot->replyMessage($event->replyToken ,$msg );
			return;
		}else{
			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("請繼續作答");
			$response = $bot->replyMessage($event->replyToken ,$textMessageBuilder );
			return;
		}	
	}
} else {
    //なにもしない
}
$response = $bot->replyMessage($event->replyToken ,$textMessageBuilder );
//error_log("輸出".print_r($event, TRUE) );
//error_log( print_r($response, TRUE) );
return;
?>