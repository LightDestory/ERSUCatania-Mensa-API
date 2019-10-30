<?php


namespace App\Model;

use DateTime;

class Calendar
{
    private const
        CALENDAR_SETTING = 'monday this week';
    private static $instance;
    private $clock;

    private function __construct()
    {
        $this->clock = new DateTime(self::CALENDAR_SETTING);
    }

    public static function getInstance(): Calendar
    {
        if (!self::$instance) {
            self::$instance = new Calendar();
        }
        return self::$instance;
    }


    /**
     * @return array
     */
    public function getMondayDate(): array
    {
        $date = array
        (
            'day' => $this->clock->format('d'),
            'month' => $this->clock->format('m'),
            'year' => $this->clock->format('Y')
        );
        return $date;
    }

}
