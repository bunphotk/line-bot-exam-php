<?php
$access_token = 'Lt6AODbxFcYOtK4gUFSaYnoxnxaHc48+yuEJuMm8VNcKvx2m6sEzvCrHqEcYD2Umi+nVB1dqb5kQeEordqqCyBoOZjdxRJtfGWSwSYemHtmT6xiSq+Ohpm4sB6/1gxYiEBzBIsvcUj5yFBqnpTUI6wdB04t89/1O/w1cDnyilFU=';


$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;
