<?php


namespace App\Model;

use Exception;

class ErsuMensa
{

    private static $instance;

    private const
        VALIDATION_COUNT_ID = 'VALIDATION_COUNT';

    private function __construct()
    {}

    public static function getInstance(): ErsuMensa
    {
        if(!self::$instance){
            self::$instance = new ErsuMensa();
        }
        return self::$instance;
    }

    /**
     * @param $array
     * @return bool
     */
    public function saveJSON($array): bool
    {
        if(!$this->validateArray($array)){
            return false;
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
        try{
            $monday = Calendar::getInstance()->getMondayDate()['day'];
            file_put_contents(storage_path("/app/{$monday}.json"), json_encode($days));
        } catch (Exception $ex){
            Reporter::getInstance()->report(__FUNCTION__, $ex->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @return string|null
     */
    private function getJSON(): ?string
    {
        $monday = Calendar::getInstance()->getMondayDate()['day'];
        $filename = "/app/{$monday}.json";
        if(!file_exists(storage_path($filename))){
            return NULL;
        }
        return file_get_contents(storage_path($filename));
    }

    /**
     * @param $day
     * @return array|null
     */
    public function getFullMenu($day): ?array
    {
        $tmp = $this->getJSON();
        if(!$tmp){
            return NULL;
        }
        $tmp = json_decode($tmp, true);
        return $tmp[$day];
    }

    /**
     * @param $array
     * @return bool
     */
    private function validateArray($array): bool
    {
        if((string)count($array) === env(self::VALIDATION_COUNT_ID)) {
            return true;
        }
        Reporter::getInstance()->report(__FUNCTION__, 'Invalid array segmentation');
        return false;
    }
}
