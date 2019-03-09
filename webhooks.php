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
	$idMessage = $events['events'][0]['message']['id']; 
	$fileName = $events['events'][0]['message']['fileName']; 
	$userID = $events['events'][0]['source']['userId'];

	$httpClient = new CurlHTTPClient($access_token);
	$bot = new LINEBot($httpClient, array('channelSecret' => $channelSecret ));	
	
	//$textMessageBuilder = new TextMessageBuilder(json_encode($events));
	//$response = $bot->replyMessage($replyToken,$textMessageBuilder); 

	// ===== Following code is about post the file to a webservice ===
	// ===== This code from https://stackoverflow.com/questions/12667797/using-curl-to-upload-post-data-with-files By Libertese	
	// ===== Change the url to your webservice =====	
	$response = $bot->getMessageContent($idMessage);
	if ($response->isSucceeded()) {
	         $dataBinary = $response->getRawBody(); 
	        file_put_contents($fileName,$dataBinary); // Save file to local host
		
		$url='http://mkss.co.th/fotk/rxfile.php'; //<== change to your webservice url here		

		$eol = "\r\n"; //default line-break for mime type
		$BOUNDARY = md5(time()); //random boundaryid, is a separator for each param on my post curl function
		$BODY=""; //init my curl body
		$BODY.= '--'.$BOUNDARY. $eol; //start param header
		$BODY .= 'Content-Disposition: form-data; name="filename"' . $eol . $eol; // last Content with 2 $eol, in this case is only 1 content.
		$BODY .= "Some Data" . $eol;//param data in this case is a simple post data and 1 $eol for the end of the data
		$BODY.= '--'.$BOUNDARY. $eol; // start 2nd param,
		$BODY.= 'Content-Disposition: form-data; name="filename"; filename='.$fileName.$eol ; //first Content data for post file, remember you only put 1 when you are going to add more Contents, and 2 on the last, to close the Content Instance
		$BODY.= 'Content-Type: application/octet-stream' . $eol; //Same before row
		$BODY.= 'Content-Transfer-Encoding: base64' . $eol . $eol; // we put the last Content and 2 $eol,
		$BODY.= chunk_split(base64_encode(file_get_contents($fileName))) . $eol; // we write the Base64 File Content and the $eol to finish the data,
		$BODY.= '--'.$BOUNDARY .'--' . $eol. $eol; // we close the param and the post width "--" and 2 $eol at the end of our boundary header.

		$ch = curl_init(); //init curl
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(					
						"Content-Type: multipart/form-data; boundary=".$BOUNDARY) //setting our mime type for make it work on $_FILE variable
					);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/1.0 (Windows NT 6.1; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0'); //setting our user agent
		curl_setopt($ch, CURLOPT_URL, $url); //setting our api post url
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); // call return content
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1); //navigate the endpoint
		curl_setopt($ch, CURLOPT_POST, true); //set as post
		curl_setopt($ch, CURLOPT_POSTFIELDS, $BODY); // set our $BODY 

		 $rp = curl_exec($ch); // start curl navigation

		 curl_close($ch);
	
	       
	       $replyData = new TextMessageBuilder($rp);	  	
               $response = $bot->replyMessage($replyToken,$replyData);
		
	}
	// ==========================================================================
	
	//=== Reply Text Message ==
	switch ($typeMessage){
		case 'text':         				
			$userMessage = strtolower($userMessage);		
			switch ($userMessage) {	
				case "hello":
				    $response = $bot->getProfile($userID);		
				    if ($response->isSucceeded()) {
					    $userData = $response->getJSONDecodedBody(); // return array   
				   // $userID=$userData['userId'];
				   // $replyData = new TextMessageBuilder($userID);					    
				   // $response = $bot->replyMessage($replyToken,$replyData);						
				    				    
					    // $userData['userId']
					    // $userData['displayName']
					    // $userData['pictureUrl']
					    // $userData['statusMessage']
					    $rpTxt='Hello '.$userData['displayName'];
					    $replyData = new TextMessageBuilder($rpTxt);	  	
					    $response = $bot->replyMessage($replyToken,$replyData);
				    }		    
				default:
				    break; 	
			}		
			
			break; 		
		default:
			//$textReplyMessage = json_encode($events);
			//$replyData = new TextMessageBuilder($textReplyMessage);         
                	break; 			
	}
	
}
echo "OK";

?>
