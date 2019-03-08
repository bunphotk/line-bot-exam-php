<?php // callback.php

require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder ;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;

$access_token = 'Lt6AODbxFcYOtK4gUFSaYnoxnxaHc48+yuEJuMm8VNcKvx2m6sEzvCrHqEcYD2Umi+nVB1dqb5kQeEordqqCyBoOZjdxRJtfGWSwSYemHtmT6xiSq+Ohpm4sB6/1gxYiEBzBIsvcUj5yFBqnpTUI6wdB04t89/1O/w1cDnyilFU=';
$channelSecret = 'c972278fed151c817193b66ba6aecd94';
$channelId= '1552912759';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	
	$sourceType = $events['events'][0]['source']['type'];
	$typeMessage = $events['events'][0]['message']['type'];
        $userMessage = $events['events'][0]['message']['text']; 
	$replyToken = $events['events'][0]['replyToken'];

	$httpClient = new CurlHTTPClient($access_token);
	$bot = new LINEBot($httpClient, array('channelSecret' => $channelSecret ));	
	
	//$textMessageBuilder = new TextMessageBuilder(json_encode($events));
	//$response = $bot->replyMessage($replyToken,$textMessageBuilder); 
	$reply = new TextMessageBuilder($typeMessage);
	$response = $bot->replyMessage($replyToken,$reply); 
	switch ($typeMessage){
		case 'text':
            		
			break; 
		default:
			$textReplyMessage = json_encode($events);
			$replyData = new TextMessageBuilder($textReplyMessage);         
                	break; 			
	}
}
echo "OK";

?>
