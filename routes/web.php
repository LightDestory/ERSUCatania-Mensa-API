<?php

use App\Model\Day;
use App\Model\OCR_Handle;
use Illuminate\Support\Facades\Route;

Route::get('/', 'Controller@test');
Route::get('/{day}', 'Controller@getDayFullMenu');
Route::get('/{day}/{time}', 'Controller@getDaySpecificMenu');

/*Route::get('/', function () {

    // TESTING CODE. JUST A PLACE TO RUN AND DEBUG FUNCTIONS
*$raw = NULL;
    if (\App\Model\PDF_To_Text::getInstance()->lookForPDFLink())
        if (\App\Model\PDF_To_Text::getInstance()->downloadPDF()) {
            $raw = \App\Model\PDF_To_Text::getInstance()->parseToText();
        }
    $raw = OCR_Handle::getInstance()->parseIntoArray($raw);
    $days = array();
    for ($i = 0; $i < 7; $i++) {
        $tmp = new Day($i);
        $food_type = false;
        for ($j = $i * 24; $j < ($i + 1) * 24; $j++) {
            $type = $j % 3;
            switch ($type) {
                case 0:
                    $food_type = !$food_type;
                    $tmp->addMain($raw[$j], $food_type);
                    break;
                case 1:
                    $tmp->addSecond($raw[$j], $food_type);
                    break;
                case 2:
                    $tmp->addAccompaniment($raw[$j], $food_type);
            }
        }
        $days[$tmp->getName()] = $tmp->getFullMenu();
    }

    // CREATING LIST OF MEALS TO_BE_REMOVED
    $array1 = explode("\n", file_get_contents(storage_path("mensa1_parsed.txt")));
    $array1 = array_splice($array1, 1);
    $array2 = explode("\n", file_get_contents(storage_path("mensa2_parsed.txt")));
    $array2 = array_splice($array2, 1);
    $array1 = array_merge($array1, $array2);
    $unique = array();
    foreach($array1 as $u){
        if(!in_array($u, $unique)){
            $unique[] = $u;
        }
    }
    file_put_contents(storage_path("/app/typos.json"), json_encode($unique, JSON_UNESCAPED_UNICODE));
    return response()->json($days);
});
*/
