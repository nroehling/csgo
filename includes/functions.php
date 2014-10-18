<?php

session_start();

include 'db_connect.php';

function getWebContent() {

    $curl = curl_init("http://csgo.99damage.de/de/matches");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    $page = curl_exec($curl);

    if (curl_errno($curl)) { // check for errors
        echo 'Scraper error: ' . curl_error($curl);
        exit;
    }
    curl_close($curl);

    // Parse the HTML information and return the results.
    $dom = new DOMDocument();
    $dom->loadHtml($page);

    $xpath = new DOMXPath($dom);

    // Get a list of articles from the section page
    $articleList = $xpath->query("//div[@id='content']/a");
    $data = array();
    // Add each article to the Articles array
    foreach ($articleList as $node) {
        $data[] = $node->textContent;

        //$dataHref[] = $node->getAttribute('href');
    }

//    for($a = 0; $a < count($dataHref);$a++) {
//        $curlDetail = curl_init($dataHref[$a]);
//        curl_setopt($curlDetail, CURLOPT_RETURNTRANSFER, TRUE);
//        $pageDetail = curl_exec($curlDetail);
//
//        if (curl_errno($curlDetail)) { // check for errors
//            echo 'Scraper error: ' . curl_error($curlDetail);
//            exit;
//        }
//        curl_close($curlDetail);
//
//        $newdom = new DOMDocument();
//        $newdom->loadHtml($pageDetail);
//
//        $xpathDetail = new DOMXPath($newdom);
//        
//        $information = array();
//        
//        // Get a list of articles from the section page
//        $details = $xpathDetail->query("//div[@class='match_head'] | //div[@class='match_names'] | //div[@class='match_logos']");
//        
//        foreach ($details as $detail) {
//            $information[] = $detail->textContent;
//        }
//        
//        
//        $result[] = $information;
//    }

    return $data;
}

function convertSteamid($steamid) {

    $steamapi = "FEF9776C70EA799DB52328C3F38124FC";

    $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$steamapi&steamids=$steamid";
    $json = file_get_contents($url);
    $json_decoded = json_decode($json);
    $name = $json_decoded->response->players[0]->personaname;
    $image = $json_decoded->response->players[0]->avatar;

    return array('name' => $name, 'imageurl' => $image);
}

function getComments($userid, $mysqli, $steamid) {

    if ($userid == null) {
        $userid = $steamid;
    }

    $query = "SELECT steamid, comment, pageid FROM comments WHERE pageid='$userid'";
    $result = mysqli_query($mysqli, $query);
    while ($row = mysqli_fetch_array($result)) {

        $comment = $row['comment'];
        $steamid = $row['steamid'];
        $data = convertSteamid($steamid);

        echo '<li class="box" >
    <img style="border:1px solid rgb(63,149,218);" src="' .
        $data['imageurl'] . '"/>
     <a href="http://www.my-csgo.de/profil.php?id=' . $steamid . '">' . $data['name'] . '</a> wrote: <br />
    ' . $comment . '
</li>';
    }
}

function getAllMarketItems() {

    $y = file_get_contents('http://steamcommunity.com/market/search/render/?query=appid%3A730&start=0&count=100');
    $sor = array();
    $resy = json_decode($y);
    $total = $resy->total_count;
    $total1 = ($total / 100) + 2;
    $total_count = intval($total1);
    $objects = array();
    for ($i = 0; $i < $total_count; $i++) {



        $start = $i * 100;
        $x = file_get_contents('http://steamcommunity.com/market/search/render/?query=appid%3A730&start=' . $start . '&count=100');
        $res = json_decode($x);

        //get name
        preg_match_all('#item_name\"\s*style=\"color:(.*)\">(.*)</span>#siU', $res->results_html, $sor);

        foreach ($sor[2] as $k => $v) {
            $objects[$k]['name'] = $v;

            if (stripos($v, 'key') !== false || stripos($v, 'weapon ') !== false || stripos($v, 'pass') !== false || stripos($v, 'package') !== false || stripos($v, 'sticker') !== false || stripos($v, 'name tag') !== false || stripos($v, 'ESL One') !== false || stripos($v, 'esports') !== false) {
                $objects[$k]['zustand'] = null;
            } else {
                $start = strpos($v, '(');
                $objects[$k]['zustand'] = substr($v, $start + 1, -1);
            }
        }
        //get urls
        preg_match_all('# href="(.*)">\r\n\t<div\s*class=\"market_listing_row#siU', $res->results_html, $sor);

        foreach ($sor[1] as $k => $v) {
            $objects[$k]['url'] = $v;
        }
        //get price
        preg_match_all('#Starting\s*at:<br/>\r\n\t\t\t\t(.*)\t\t\t</span>#siU', $res->results_html, $sor);

        foreach ($sor[1] as $k => $v) {
            $objects[$k]['price'] = str_replace('&#36;', '', $v);
        }
        //get image
        preg_match_all('#src=\"(.*)"\s*style=\"#siU', $res->results_html, $sor);

        foreach ($sor[1] as $k => $v) {

            if (strpos($sor[1], '/62fx62f') !== false) {
                $objects[$k]['image'] = str_replace('/62fx62f', '/112fx98f', $v);
            } else {
                $objects[$k]['image'] = $v;
            }
        }

        //get color
        preg_match_all('~style=\"color: (#.*);~isU', $res->results_html, $sor);

        foreach ($sor[1] as $k => $v) {
            $objects[$k]['color'] = $v;
        }

        //get quantity
        preg_match_all('#market_listing_num_listings_qty\">(.*)</span>#siU', $res->results_html, $sor);

        foreach ($sor[1] as $k => $v) {
            $objects[$k]['quantity'] = $v;
        }
        //get game
        preg_match_all('#market_listing_game_name\">(.*)</span>#siU', $res->results_html, $sor);

        foreach ($sor[1] as $k => $v) {
            $objects[$k]['game'] = $v;
        }



        for ($a = 0; $a < count($objects); $a++) {
            echo '<div id="stay" class="div" style="border:1px solid ' . $objects[$a]['color'] . ';"><img class="marketitem" src="' . $objects[$a]['image'] . '" style="margin:2px;" alt="' . $objects[$a]['name'] . '"/><div class="balken" style="background:' . $objects[$a]['color'] . '">' . $objects[$a]['zustand'] . '</div></div>';
        }
    }
}

function getPlayerEntries($steamid, $mysqli, $userid) {

    if ($userid != null) {
        $steamid = $userid;
    }

    $query = "SELECT aboutme FROM userlist WHERE steamid='" . $steamid . "' ";
    $stmt = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
    while ($row = mysqli_fetch_array($stmt)) {
        $aboutme = $row['aboutme'];
    }

    echo '<div class="info"><h1>&Uuml;ber mich</h1><p>' . $aboutme . '</p></div>';
}

function getPlayerSum($steamapi, $steamid, $userid) {

    if ($userid != null) {
        $steamid = $userid;
    }

    $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$steamapi&steamids=$steamid";
    $json_object = file_get_contents($url);
    $json_decoded = json_decode($json_object);

    //timestamp umwandeln
    $timestamp = $json_decoded->response->players[0]->lastlogoff;
    $datum = date(" d.m.Y - H:i:s", $timestamp);

    echo '<img id="profilbild" src="' . $json_decoded->response->players[0]->avatarfull . '" style="border:2px solid rgb(63,149,218);margin-top:50px;margin-left:4%;margin-right:4%;float:left;"/>';

    echo '<div class="persona"><h1 style="padding-top:15px;">' . $json_decoded->response->players[0]->personaname . '</h1></br>'
    . '<p >Steamid: ' . $json_decoded->response->players[0]->steamid . '</p></br>'
    . '<p >Letzter Login: ' . $datum . '</p></br>'
    . '<p id="low">Niedrigster gesch&auml;tzer Wert des Inventars: 200euro </p></br>'
    . '<p id="median">Durschnittlicher gesch&auml;tzer Wert des Inventars:' . $_SESSION['$median'] . ' </p></br>'
    . '<p style="line-height:2%;">Steam Profil: </p></br>'
    . '<span id="shadowbox" style="padding:5px;border-radius:5px;border:1px solid rgb(63,149,218);">'
    . '<a style="text-decoration:none;" href="' . $json_decoded->response->players[0]->profileurl . '">' . $json_decoded->response->players[0]->profileurl . '</a></span></br></br>'
    . '<p style="line-height:2%;">My-csgo Profil: </p></br>'
    . '<span id="shadowbox" style="padding:5px;border-radius:5px;border:1px solid rgb(63,149,218);">'
    . '<a style="text-decoration:none;" href="http://www.my-csgo.de/profil.php?id=' . $steamid . '"> http://www.my-csgo.de/profil.php?id=' . $steamid . ' </a></span></div>';
}

function priceInv($itemnames) {

    for ($a = 0; $a < count($itemnames); $a++) {
        $market_url = "http://steamcommunity.com/market/priceoverview/?country=EN&currency=3&appid=730&market_hash_name=" . $itemnames[$a];
        $json_object_market = file_get_contents($market_url);
        $json_decoded_market = json_decode($json_object_market, true);

        $value = $json_decoded_market['lowest_price'];
        $valueMid = $json_decoded_market['median_price'];

        $float = floatval($value);
        $floatMid = floatval($valueMid);

        $lowest += $float;
        $median += $floatMid;
    }

    $_SESSION['low'] = $lowest;
    $_SESSION['median'] = $median;
}

function getFriendList($steamapi, $steamid, $userid) {

    $url = "http://api.steampowered.com/ISteamUser/GetFriendList/v0001/?key=$steamapi&steamid=$steamid";
    $json_object = file_get_contents($url);
    $json_decoded = json_decode($json_object);

    echo '<p>friend id: ' . $json_decoded->friendslist->friends[0]->steamid . '</p>';
}

function getInventory($steamapi, $steamid, $userid) {

    if ($userid != null) {
        $steamid = $userid;
    }

    $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$steamapi&steamids=$steamid";
    $json_object = file_get_contents($url);
    $json_decoded = json_decode($json_object);

    $str = $json_decoded->response->players[0]->steamid;

    $urlInv = "http://steamcommunity.com/profiles/" . $str . "/inventory/json/730/2/?trading=1";

    $json_object_inv = file_get_contents($urlInv);
    $json_decoded_inv = json_decode($json_object_inv, true);

    // iterate through Inventory and find ids.
    $rgInventory = $json_decoded_inv[rgInventory];
    $rgInventory = array_values($rgInventory);

    $rgDesc = $json_decoded_inv[rgDescriptions];
    $rgDesc = array_values($rgDesc);

    $itemnames = array();

    for ($i = 0; $i < count($rgInventory); $i++) {  //iterate through rgInventory.
        $classidInv = $rgInventory[$i]['classid'];
        $instanceidInv = $rgInventory[$i]['instanceid'];

        for ($j = 0; $j < count($rgDesc); $j++) {   //iterate through rgDesc.
            $classid = $rgDesc[$j]['classid'];
            $instanceid = $rgDesc[$j]['instanceid'];

            if ($classidInv == $classid && $instanceidInv == $instanceid) {
                $icon_url = $rgDesc[$j]['icon_url'];
                $market_name = $rgDesc[$j]['market_name'];
                $market_name_formatted = str_replace(" ", "%20", $market_name);
                $st_color = $rgDesc[$j]['name_color'];
                $itemnames = array_push($itemnames, $market_name_formatted);

                for ($k = 0; $k < count($rgDesc[$j]['tags']); $k++) {
                    if ($rgDesc[$j]['tags'][$k]['category'] == "Rarity") {
                        $name_color = $rgDesc[$j]['tags'][$k]['color'];
                    } else if ($rgDesc[$j]['tags'][$k]['category'] == "Exterior") {

                        $name_exterior = $rgDesc[$j]['tags'][$k]['name'];
                    }
                }
            }
        }

        if ($st_color == "CF6A32") {
            $name_color = "CF6A32";
        }

        echo '<div class="divinv" style="border:1px solid #' . $name_color . '"><img id="stay" class="weaponinv"  src="http://steamcommunity-a.akamaihd.net/economy/image/' . $icon_url . '" width="112px" height="98px" alt="' . $market_name . "/" . $name_color . '"/><div class="rarityinv" style="background:#' . $name_color . '">' . $name_exterior . '</div></div>';

        $name_exterior = null;
    }

    priceInv($itemnames);

//    $classid = $json_decoded_inv[rgInventory][0][classid];
//    $instanceid = $json_decoded_inv[rgInventory][0][instanceid];
//    
//    $icon_url = $json_decoded_inv[rgDescriptions][$classid.'_'.$instanceid][icon_url];
    //echo '<img src="http://steamcommunity-a.akamaihd.net/economy/image/'.$icon_url.'" width="98px" height="98px"/>';
}

//function priceOverview ($name) {
//    
//    $market_url = "http://steamcommunity.com/market/priceoverview/?country=EN&currency=3&appid=730&market_hash_name=".$name;
//    $json_object_market = file_get_contents($market_url);
//    $json_decoded_market = json_decode($json_object_market);
//    
//    return $json_decoded_market->lowest_price;
//}
?>