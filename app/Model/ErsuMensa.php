<?php


namespace App\Model;

use Exception;

class ErsuMensa
{

    private const
        VALIDATION_COUNT_ID = 'VALIDATION_COUNT';
    private static $instance;

    private function __construct()
    {
    }

    /**
     * @return ErsuMensa
     */
    public static function getInstance(): ErsuMensa
    {
        if (!self::$instance) {
            self::$instance = new ErsuMensa();
        }
        return self::$instance;
    }

    /**
     * @param $array
     * @return bool
     */
    public function saveJSON($array): void
    {
        if (!$this->validateArray($array)) {
            return;
        }
        $days = array();
        for ($i = 0; $i < 7; $i++) {
            $tmp = new Day($i);
            $food_type = false;
            for ($j = $i * 24; $j < ($i + 1) * 24; $j++) {
                $type = $j % 3;
                switch ($type) {
                    case 0:
                        $food_type = !$food_type;
                        $tmp->addMain($array[$j], $food_type);
                        break;
                    case 1:
                        $tmp->addSecond($array[$j], $food_type);
                        break;
                    case 2:
                        $tmp->addAccompaniment($array[$j], $food_type);
                }
            }
            $days[$tmp->getName()] = $tmp->getMenu();
        }
        try {
            $monday = Calendar::getInstance()->getMondayDate()['day'];
            file_put_contents(storage_path("/app/{$monday}.json"), json_encode($days));
        } catch (Exception $ex) {
            Reporter::getInstance()->report(__FUNCTION__, $ex->getMessage());
            return;
        }
        if(file_exists(storage_path(env(Reporter::ALREADY_REPORT_FILE_FLAG_PATH_ID)))) {
            unlink(storage_path(env(Reporter::ALREADY_REPORT_FILE_FLAG_PATH_ID)));
        }
        Reporter::getInstance()->report(__FUNCTION__, Reporter::SUCCESSFUL_RUNTIME);
    }

    /**
     * @param $array
     */
    /**
     * @param $array
     * @return bool
     */
    private function validateArray($array): bool
    {
        if ((string)count($array) === env(self::VALIDATION_COUNT_ID)) {
            OCR_Handle::getInstance()->learn($array);
            return true;
        }
        Reporter::getInstance()->report(__FUNCTION__, Reporter::INVALID_ARRAY_SEGMENTATION_MESSAGE);
        return false;
    }

    /**
     * @param $day
     * @return array
     */
    public function getDayMenu($day): array
    {
        return $this->getJSON()[$this->normalizeInput($day)];
    }

    private function getJSON(): array
    {
        $date = Calendar::getInstance()->getMondayDate();
        return json_decode(file_get_contents(storage_path("/app/{$date['day']}.json")), true);
    }

    /**
     * @param $input
     * @return string
     */
    private function normalizeInput($input): string
    {
        return preg_replace("/(i'|%c3%ac|ì)/u", 'i', strtolower($input));
    }

    /**
     * @param $day
     * @param $time
     * @return array
     */
    public function getDayTimeMenu($day, $time): array
    {
        return $this->getJSON()[$this->normalizeInput($day)][$this->normalizeInput($time)];
    }

    /**
     * @return array
     */
    public function getWeekMenu(): array
    {
        return $this->getJSON();
    }

    /**
     * @param $day
     * @return bool
     */
    public function isValidDay($day): bool
    {
        if (!in_array($this->normalizeInput($day), Day::$week_days, false)) {
            return false;
        }
        return true;
    }

    /**
     * @param $time
     * @return bool
     */
    public function isValidDayTime($time): bool
    {
        if (!in_array($this->normalizeInput($time), Day::$day_times, false)) {
            return false;
        }
        return true;
    }
}
