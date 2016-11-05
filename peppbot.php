<?php
//Todo: Importera in de fina orden från en separat fil
$finaOrd = array('fin', 'snäll', 'mysig', 'trevlig', 'effektiv', 'bra', 'på hugget', 'här', 'vild', 'härlig', 'fantastisk', 'en av de bästa', 'den bästaste tjejen', 'en skaplig tjej', 'helt ok', 'underbar', 'typ tokbra', 'helt jävla grym', 'tydligen från en annan planet', 'nedsänd från gud själv', 'en superhjältinna', 'den femte okända musketören', 'frääääsch', 'läcker', 'mighty fine', 'bedårande', 'en pingla', 'som dagg på en sommaräng i motljus och morgondimma', 'för Wakakuu som Batman är för Gotham City', 'spröd som en pepparkaka', 'milfig i medvind', 'förtjust i någon på kontoret', 'glad som en glass', 'mustig', 'förälskad i Navision', 'enligt rykten väldigt nära nu att fråga chans på Postis-Uffe', 'het som en komet!', 'en belevad ung tjej', 'vrålhet', 'lite bakis idag', 'brunstig', 'välbevarad', 'väluppfostrad', 'avundsjuk', 'trollsk', 'som en drönare', 'balsamerad', 'glad för det lilla', 'uppskattande av komplimanger och choklad', 'som en groda, lever på hoppet');

$people = array('Sandra', 'Sarah', 'Maria');
$supporters = array('Fredrik', 'Sven', 'Linda', 'Cissi', 'Millan', 'Johanna');
$extraPepp = array('Håller du inte med', 'Eller vad tycker du', 'Visst stämmer det', 'Klart det är så', 'Och detsamma stämmer väl in på dig', 'För att inte tala om dig', 'Och du är inte så pjåkig själv');
$adjektiv = array('raffiga', 'piffiga', 'fina', 'sköna', 'sexiga', 'färgsprakande', 'mysiga', 'underbara');

if (!(is_file('settings.txt'))) die('settings.txt does not exist');
$ini = parse_ini_file('settings.txt');
        
$msgs = array();

if (isset($argv[1])) {
    $msgs = array($argv[1]);
} else {
    if (in_array(rand(1,6), array(5,6))) { // 1 på 3 att nått händer
        $subject = $people[array_rand($people)];
        $msgs[] = "$subject är ".$finaOrd[array_rand($finaOrd)].".";

        $rnd = rand(1,6);
        if ($rnd == 6) {
            $msgs[] = $extraPepp[array_rand($extraPepp)].", ".$supporters[array_rand($supporters)]."?";
        }

        if ($rnd != 6 && rand(1,6) == 6) { // Om jag inte redan rullat en sexa, rulla igen och få en ny sexa
            $imgUrl = getRandomWakanewsImageUrl();
            $msgs[] = "$imgUrl\nJag gillar den här ".$adjektiv[array_rand($adjektiv)]." saken! Den skulle sitta bra på dig, $subject!";
        }
    }
}

foreach ($msgs as $nr => $msg) {
    $json = "{\"text\": \"$msg\"}";
    $json = str_replace('"', '\"', $json);
    try {
        ob_start();
        $curl = 'curl -X POST --data "payload='.$json.'" '.$ini['slack_uri'];
        passthru($curl);
        $response = ob_get_contents();
        ob_end_clean();

        $retArr = json_decode($response, true);
    } catch (Exception $e) {
        throw($e);
    }
    echo "$nr ".count($msgs)."\n";
    if ($nr+1<count($msgs)) {
        sleep(5);
    }
}

function getRandomWakanewsImageUrl() {
    ob_start();
    $curl = 'curl -X GET https://www.wakakuu.com/se/nyheter.html';
    passthru($curl);
    $response = ob_get_contents();
    ob_end_clean();
    $rnd = rand(1, 24);
    $linkPos = strpos($response, 'productlink');
    for($i=1; $i<=$rnd; $i++) {
        $linkPos = strpos($response, 'productlink', $linkPos+1);
    }
    $searchStr = 'img src="';
    $firstQuotePos = strpos($response, $searchStr, $linkPos)+strlen($searchStr);
    $endQuotePos = strpos($response, '"', $firstQuotePos+1);
    $imgUrl = substr($response, $firstQuotePos, $endQuotePos-$firstQuotePos);
    return $imgUrl;
}
