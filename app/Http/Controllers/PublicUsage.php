<?php


namespace App\Http\Controllers;


use App\Model\ErsuMensa;
use App\Model\Reporter;
use Laravel\Lumen\Routing\Controller as BaseController;

class PublicUsage extends BaseController
{
    public function getWeekMenu()
    {
        return response()->json([
            'status' => Reporter::SUCCESS_STATUS,
            'menu' => ErsuMensa::getInstance()->getWeekMenu()
        ]);
    }

    public function getDayMenu($day)
    {
        if (!ErsuMensa::getInstance()->isValidDay($day)) {
            return response()->json([
                'status' => Reporter::ERROR_STATUS,
                'message' => Reporter::INVALID_DAY_MESSAGE
            ], 404);
        }
        return response()->json([
            'status' => Reporter::SUCCESS_STATUS,
            'menu' => ErsuMensa::getInstance()->getDayMenu($day)
        ]);
    }

    public function getDayTimeMenu($day, $time)
    {
        if (!ErsuMensa::getInstance()->isValidDay($day)) {
            return response()->json([
                'status' => Reporter::ERROR_STATUS,
                'message' => Reporter::INVALID_DAY_MESSAGE
            ], 404);
        }
        if (!ErsuMensa::getInstance()->isValidDayTime($time)) {
            return response()->json([
                'status' => Reporter::ERROR_STATUS,
                'message' => Reporter::INVALID_TIME_MESSAGE
            ], 404);
        }
        return response()->json([
            'status' => Reporter::SUCCESS_STATUS,
            'menu' => ErsuMensa::getInstance()->getDayTimeMenu($day, $time)
        ]);
    }
}
