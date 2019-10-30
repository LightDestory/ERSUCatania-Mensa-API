<?php

namespace App\Model;

use Exception;

class OCR_Handle
{
    private const
        REGEX_RULE_NAME = 'rule',
        REGEX_REPLACE_NAME = 'replace',
        TYPOS_LIST_FILE_PATH_ID = 'TYPOS_LIST_FILE_PATH',
        REMOVES_LIST_FILE_PATH_ID = 'REMOVES_LIST_FILE_PATH';
    private static $instance;
    private $typos_fix_list, $removes_list;

    private $regex = array
    (
        array(self::REGEX_RULE_NAME => '/(PRIMI|SECONDI|PIATTI|CONTORNI|PRANZO|CENA|LUNEDI’|MARTEDI’|MERCOLEDI’|GIOVEDI’|VENERDI’|SABATO|DOMENICA)/i', self::REGEX_REPLACE_NAME => ''),
        array(self::REGEX_RULE_NAME => "/(Men(u|ù|u') dal [0-9]{1,2}(\.|\\|\/)[0-9]{1,2}(\.|\\|\/)[0-9]{4} al [0-9]{1,2}(\.|\\|\/)[0-9]{1,2}(\.|\\|\/)[0-9]{4})/i", self::REGEX_REPLACE_NAME => ''),
        array(self::REGEX_RULE_NAME => '/ ([A-Z])/', self::REGEX_REPLACE_NAME => "\n$1"),
        array(self::REGEX_RULE_NAME => "/([ ]*\r?\n[ ]*)+/", self::REGEX_REPLACE_NAME => "\n"),
        array(self::REGEX_RULE_NAME => "/\r?\n$/", self::REGEX_REPLACE_NAME => '')
    );

    /**
     * OCR_Handle constructor.
     */
    private function __construct()
    {
        try {
            $this->typos_fix_list = json_decode(file_get_contents(storage_path(env(self::TYPOS_LIST_FILE_PATH_ID))), false);
            $this->removes_list = json_decode(file_get_contents(storage_path(env(self::REMOVES_LIST_FILE_PATH_ID))), false);
        } catch (Exception $ex) {
            Reporter::getInstance()->report(__CLASS__ . __FUNCTION__, $ex->getMessage());
        }
    }

    /**
     * @return OCR_Handle
     */
    public static function getInstance(): OCR_Handle
    {
        if (!self::$instance) {
            self::$instance = new OCR_Handle();
        }
        return self::$instance;
    }

    /**
     * @param $raw
     * @return array
     */
    public function parseIntoArray($raw): array
    {
        $refined = $this->applyRegex($raw);
        $refined_array = explode("\n", $refined);
        $refined_array = array_splice($refined_array, 1);
        return $refined_array;
    }

    /**
     * @param $raw
     * @return string
     */
    private function applyRegex($raw): string
    {
        $norm = $this->normalize($raw);
        foreach ($this->regex as $r) {
            $norm = preg_replace($r[self::REGEX_RULE_NAME], $r[self::REGEX_REPLACE_NAME], $norm);
        }
        return $norm;
    }

    /**
     * @param $raw
     * @return string
     */
    private function normalize($raw): string
    {
        $raw = mb_convert_encoding($raw, 'UTF-8', 'UTF-16LE');
        $raw = preg_replace('/[ ]+/', ' ', $raw);
        $raw = preg_replace('/\//', ' o ', $raw);
        $raw = $this->replaceTypos($raw);
        $raw = $this->removeCrap($raw);
        return $raw;
    }

    /**
     * @param $raw
     * @return string
     */
    private function replaceTypos($raw): string
    {
        foreach ($this->typos_fix_list as $typo) {
            $raw = preg_replace("/\b{$typo}\b/i", $typo, $raw);
        }
        return $raw;
    }

    /**
     * @param $raw
     * @return string
     */
    private function removeCrap($raw): string
    {
        foreach ($this->removes_list as $remove) {
            $raw = str_ireplace($remove, '', $raw);
        }
        return $raw;
    }

    public function learn($array): void
    {
        $learning = [];
        foreach ($array as $dish){
            if(!in_array($dish, $this->typos_fix_list, false)){
                $learning[] = $dish;
            }
        }
        if(count($learning)>0){
            try{
                $this->typos_fix_list = array_merge($this->typos_fix_list, $learning);
                file_put_contents(storage_path(env(self::TYPOS_LIST_FILE_PATH_ID)), json_encode($this->typos_fix_list, JSON_UNESCAPED_UNICODE));
                Reporter::getInstance()->report(__FUNCTION__, 'Nuove pietanze: ' . count($learning));
            } catch (Exception $ex){
                Reporter::getInstance()->report(__FUNCTION__, $ex->getMessage());
            }
        }
    }
}
