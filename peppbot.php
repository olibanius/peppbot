<?php
//Todo: Importera in de fina orden från en separat fil
$finaOrd = array('fin', 'snäll', 'mysig', 'trevlig', 'effektiv', 'bra', 'på hugget', 'här', 'vild', 'härlig', 'fantastisk', 'en av de bästa', 'den bästaste tjejen', 'en skaplig tjej', 'helt ok', 'underbar', 'typ tokbra', 'helt jävla grym', 'tydligen från en annan planet', 'nedsänd från gud själv', 'en superhjältinna', 'den femte okända musketören', 'frääääsch', 'läcker', 'mighty fine', 'bedårande', 'en pingla', 'som dagg på en sommaräng i motljus och morgondimma', 'för Wakakuu som Batman är för Gotham City', 'spröd som en pepparkaka', 'milfig i medvind', 'förtjust i någon på kontoret', 'glad som en glass', 'mustig', 'förälskad i Navision', 'enligt rykten väldigt nära nu att fråga chans på Postis-Uffe', 'het som en komet!', 'en belevad ung tjej', 'vrålhet', 'lite bakis idag', 'brunstig', 'välbevarad', 'väluppfostrad', 'avundsjuk', 'trollsk', 'som en drönare', 'balsamerad', 'glad för det lilla', 'uppskattar komplimanger och choklad', 'som en groda, lever på hoppet');

if (!(is_file('settings.txt'))) die('settings.txt does not exist');
$ini = parse_ini_file('settings.txt');

$msgs = array();

//Todo: Settings-ify
$people = array('@sandra', '@saris', '@maria');
$supporters = array('@fredrik', '@sven', '@linda');
$extraPepp = array('Håller du inte med', 'Eller vad tycker du', 'Visst stämmer det', 'Klart det är så', 'Och detsamma stämmer väl in på dig', 'För att inte tala om dig');
$msgs[] = $people[array_rand($people)]." är ".$finaOrd[array_rand($finaOrd)].".";

$rnd = rand(1,6);
if ($rnd == 6) {
    $msgs[] = $extraPepp[array_rand($extraPepp)].", ".$supporters[array_rand($supporters)]."?";
}

foreach ($msgs as $msg) {
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
    sleep(5);
}
