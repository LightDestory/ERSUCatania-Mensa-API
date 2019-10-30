<?php

namespace App\Model;

use Exception;
use Ilovepdf\ExtractTask;

class PDF_To_Text
{
    private static $instance;
    private const
        ERSU_URL_ID = 'PDF_FILE_URL',
        ERSU_SCRAP_REGEX = '/http:\/\/www\.ersucatania\.gov\.it\/wp-content\/uploads\/{YEAR}\/{MONTH}\/[a-zA-Z_ùuù]+{DAY}\.{MONTH}\.{YEAR}[a-zA-Z_ùuù0-9\.]+/',
        PUBLIC_KEY_ID = 'ILOVEPDF_PUBLIC_KEY',
        SECRET_KEY_ID = 'ILOVEPDF_SECRET_KEY',
        PDF_FILE_PATH_ID = 'PDF_FILE_PATH',
        TEXT_FILE_PATH_ID = 'TEXT_FILE_PATH';

    private $service_handler, $link;

    private function __construct()
    {
        $this->service_handler = new ExtractTask(env(self::PUBLIC_KEY_ID), env(self::SECRET_KEY_ID));
    }

    /**
     * @return PDF_To_Text
     */
    public static function getInstance(): PDF_To_Text
    {
        if (!self::$instance) {
            self::$instance = new PDF_To_Text();
        }
        return self::$instance;
    }

    /**
     * @return string|null
     */
    public function parseToText(): ?string
    {
        if ($this->lookForPDFLink() && $this->downloadPDF()) {
            try {
                $this->service_handler->addFile(storage_path(env(self::PDF_FILE_PATH_ID)));
                $this->service_handler->execute();
                $this->service_handler->download(storage_path('/tmp/'));
                return file_get_contents(storage_path(env(self::TEXT_FILE_PATH_ID)));
            } catch (Exception $ex) {
                Reporter::getInstance()->report(__CLASS__ . __FUNCTION__, $ex->getMessage());
            }
        }
        return NULL;
    }

    /**
     * @return bool
     */
    private function downloadPDF(): bool
    {
        try {
            $tmp_file = file_get_contents($this->link);
            return file_put_contents(storage_path(env(self::PDF_FILE_PATH_ID)), $tmp_file) !== false;
        } catch (Exception $x) {
            Reporter::getInstance()->report(__CLASS__ . __FUNCTION__, $x->getMessage());
            return false;
        }
    }

    /**
     * @return bool
     */

    private function lookForPDFLink(): bool
    {
        $date = Calendar::getInstance()->getMondayDate();
        $regex_tmp = str_replace(['{DAY}', '{MONTH}', '{YEAR}'], $date, self::ERSU_SCRAP_REGEX);
        $html = file_get_contents(env(self::ERSU_URL_ID));
        preg_match($regex_tmp, $html, $this->link);
        if (!empty($this->link)) {
            $this->link = $this->link[0];
            return true;
        }
        Reporter::getInstance()->report(__CLASS__ . __FUNCTION__, 'Unable to find a suitable link');
        return false;
    }
}
