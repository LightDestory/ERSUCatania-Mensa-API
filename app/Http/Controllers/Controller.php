<?php

namespace App\Http\Controllers;
use App\Model\ErsuMensa;

use App\Model\OCR_Handle;
use App\Model\PDF_To_Text;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function getDayFullMenu($day)
    {
        $r = ErsuMensa::getInstance()->getFullMenu($day);
        return response()->json($r);
    }

    public function getDaySpecificMenu($day, $time)
    {
        $r = ErsuMensa::getInstance()->getFullMenu($day)[$time];
        return response()->json($r);
    }

    public function test()
    {
        $tmp = PDF_To_Text::getInstance()->parseToText();
        if($tmp === NULL){
            return "INVALID DOWNLOAD";
        }
        $refined = OCR_Handle::getInstance()->parseIntoArray($tmp);
        if(ErsuMensa::getInstance()->saveJSON($refined))
            return "GOOD";
        return "NO";
    }
}
