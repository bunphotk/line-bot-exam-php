<?php // callback.php

require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

$access_token = 'QL46gru5cqi5vE193hJthYcvKvoTo/VphPqSP98aWchVGoNUkzurRAoBWws/X+Oki+nVB1dqb5kQeEordqqCyBoOZjdxRJtfGWSwSYemHtmnozn9UQZuAJHDMqYa8IT1CmzK3YpuE83p1+V2+t1nfAdB04t89/1O/w1cDnyilFU=';
$channelSecret = 'c972278fed151c817193b66ba6aecd94';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
	     // Reply only when message sent is in 'text' format
	     if ($event['type'] == 'message' ) {
		    if($event['message']['type'] == 'text'){
			// Get text sent
			$text = $event['source']['userId'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			send_dat($data,$url,$access_token);
			    
		    }elseif($event['message']['type'] == 'file'){
			// Get Message id sent
			$msgId = $event['message']['id'];
			// Get File Name
			$fileName = $event['message']['fileName'];
			// Get connent (file) from Line Chat
			$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
			$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);			    			    
			$response = $bot->getMessageContent($msgId);
			if ($response->isSucceeded()) {
			    //$tempfile = tmpfile();
			   $fp = fopen('tmp.txt', 'w');
			   fwrite($fp, $response->getRawBody());
			   fclose($fp);	
			    //fwrite($tempfile, $response->getRawBody());					
			    // Destination URL. Change to your webserverice URL here	
			    $url="http://mkss.co.th/fotk/rxfile.php";	
			    //send_file($tempfile,$url);  	
			    send_file("tmp.txt",$url);  	
			} else {
			    error_log($response->getHTTPStatus() . ' ' . $response->getRawBody());
			}			    
			    
		    }	    
	     }
	}
}
echo "OK";
function send_dat($data,$url,$access_token){
	$post = json_encode($data);
	$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);

	echo $result . "\r\n";	
}

function send_file($file,$url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, array(
		'rxfile' => '@$file',
	));
	$result = curl_exec($ch);
	curl_close($ch);
}
