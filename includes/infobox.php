<?php

    $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$steamapi&steamids=$steamid";
    $json_object = file_get_contents($url);
    $json_decoded = json_decode($json_object);

    $str = $json_decoded->response->players[0]->steamid;

    $urlInv = "http://steamcommunity.com/profiles/" . $str . "/inventory/json/730/2";

    $json_object_inv = file_get_contents($urlInv);
    $json_decoded_inv = json_decode($json_object_inv, true);

    // iterate through Inventory and find ids.
    $rgInventory = $json_decoded_inv[rgInventory];
    $rgInventory = array_values($rgInventory);

    $rgDesc = $json_decoded_inv[rgDescriptions];
    $rgDesc = array_values($rgDesc);

    for ($i = 0; $i < count($rgInventory); $i++) {  //iterate through rgInventory.
        $classidInv = $rgInventory[$i]['classid'];
        $instanceidInv = $rgInventory[$i]['instanceid'];

        for ($j = 0; $j < count($rgDesc); $j++) {   //iterate through rgDesc.
            $classid = $rgDesc[$j]['classid'];
            $instanceid = $rgDesc[$j]['instanceid'];
            
            if($classidInv == $classid && $instanceidInv == $instanceid) {
                $name = $rgDesc[$j]['market_name'];
            }
        }
    }