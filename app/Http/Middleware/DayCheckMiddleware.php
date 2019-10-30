<?php

namespace App\Http\Middleware;

use App\Model\Day;
use Closure;
use Illuminate\Http\Request;

class DayCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $day = $request->get('day');
        if($day === NULL || !in_array($day, Day::$week_days, true) === false){
            return response()->json(['error' => 'Invalid input data!']);
        }
        return $next($request);
    }
}
