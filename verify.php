<?php
$access_token = 'QL46gru5cqi5vE193hJthYcvKvoTo/VphPqSP98aWchVGoNUkzurRAoBWws/X+Oki+nVB1dqb5kQeEordqqCyBoOZjdxRJtfGWSwSYemHtmnozn9UQZuAJHDMqYa8IT1CmzK3YpuE83p1+V2+t1nfAdB04t89/1O/w1cDnyilFU=';


$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;
