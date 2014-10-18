<?php

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
$dataHref = array();
// Add each article to the Articles array
foreach ($articleList as $node) {


    $dataHref[] = $node->getAttribute('href');
}

for ($a = 0; $a < count($dataHref); $a++) {
    $curlDetail = curl_init($dataHref[$a]);
    curl_setopt($curlDetail, CURLOPT_RETURNTRANSFER, TRUE);
    $pageDetail = curl_exec($curlDetail);

    if (curl_errno($curlDetail)) { // check for errors
        echo 'Scraper error: ' . curl_error($curlDetail);
        exit;
    }
    curl_close($curlDetail);

    $newdom = new DOMDocument();
    $newdom->loadHtml($pageDetail);

    $xpathDetail = new DOMXPath($newdom);

    $information = array();

    // Get a list of articles from the section page
    $details = $xpathDetail->query("//div[@class='match_logos']/div[@class='lineup'] | //div[@class='match_logos']/div[@class='lineup second'] | //div[@class='match_names'] | //div[@class='match_head']/div[@class='left'] ");

    foreach ($details as $detail) {
        $information[] = $detail->textContent;
        
    }


    $result[] = $information;
}

echo json_encode($result);
