<?php

$itemname = $_POST['itemname'];

$itemname = str_replace(" ", "%20", $itemname);

$market_url = "http://steamcommunity.com/market/priceoverview/?country=EN&currency=3&appid=730&market_hash_name=".$itemname;
$json_object_market = file_get_contents($market_url);
$json_decoded_market = json_decode($json_object_market);

header('Content-Type: application/json; charset=UTF-8');
echo json_encode(array('low' => $json_decoded_market->lowest_price, 'median' => $json_decoded_market->median_price, 'volume' => $json_decoded_market->volume));