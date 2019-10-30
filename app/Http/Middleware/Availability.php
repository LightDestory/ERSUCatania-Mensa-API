<?php


namespace App\Http\Middleware;


use App\Model\Calendar;
use App\Model\Reporter;
use Closure;

class Availability
{
    public function handle($request, Closure $next)
    {
        $date = Calendar::getInstance()->getMondayDate();
        if (!file_exists(storage_path("/app/{$date['day']}.json"))) {
            return response()->json([
                'status' => Reporter::ERROR_STATUS,
                'message' => Reporter::MISSING_MENU_MESSAGE
            ], 404);
        }
        return $next($request);
    }
}
