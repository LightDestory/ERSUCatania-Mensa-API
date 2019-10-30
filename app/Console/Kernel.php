<?php

namespace App\Console;

use App\Model\Calendar;
use App\Model\ErsuMensa;
use App\Model\OCR_Handle;
use App\Model\PDF_To_Text;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(static function () {
            $date = Calendar::getInstance()->getMondayDate();
            if (!file_exists(storage_path("/app/{$date['day']}.json"))) {
                $raw_data = PDF_To_Text::getInstance()->parseToText();
                if ($raw_data !== NULL) {
                    $refined_data = OCR_Handle::getInstance()->parseIntoArray($raw_data);
                    ErsuMensa::getInstance()->saveJSON($refined_data);
                }
            }
        })->everyFifteenMinutes();
    }
}
