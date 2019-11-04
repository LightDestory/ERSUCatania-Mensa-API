<?php


namespace App\Model;


class Runtime
{
    private static $instance;

    public const
        SYSTEM_KEY_ID = 'SYSTEM_KEY';

    private function __construct()
    {
    }

    public static function getInstance(): Runtime
    {
        if(!self::$instance) {
            self::$instance = new Runtime();
        }
        return self::$instance;
    }

    public function execute(): void
    {
        $date = Calendar::getInstance()->getMondayDate();
        if (!file_exists(storage_path("/app/{$date['day']}.json"))) {
            $raw_data = PDF_To_Text::getInstance()->parseToText();
            if ($raw_data !== NULL) {
                $refined_data = OCR_Handle::getInstance()->parseIntoArray($raw_data);
                ErsuMensa::getInstance()->saveJSON($refined_data);
            }
        }
    }
}
