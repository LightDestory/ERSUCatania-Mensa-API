<?php

namespace App\Model;

class OCR_Handle
{
    private static $instance;

    private const
        REGEX_RULE_NAME = 'rule',
        REGEX_REPLACE_NAME = 'replace',
        TYPOS_LIST_PATH = '/app/typos.json',
        REMOVES_LIST_PATH = '/app/removes.json';

    private $typos_fix_list, $removes_list;

    private $regex = array
    (
        array(self::REGEX_RULE_NAME => '/(PRIMI|SECONDI|PIATTI|CONTORNI|PRANZO|CENA|LUNEDI’|MARTEDI’|MERCOLEDI’|GIOVEDI’|VENERDI’|SABATO|DOMENICA)/i', self::REGEX_REPLACE_NAME => ''),
        array(self::REGEX_RULE_NAME => "/(Men(u|ù) dal [0-9]{1,2}(\.|\\|\/)[0-9]{1,2}(\.|\\|\/)[0-9]{4} al [0-9]{1,2}(\.|\\|\/)[0-9]{1,2}(\.|\\|\/)[0-9]{4})/i", self::REGEX_REPLACE_NAME => ''),
        array(self::REGEX_RULE_NAME => '/ ([A-Z])/', self::REGEX_REPLACE_NAME => "\n$1"),
        array(self::REGEX_RULE_NAME => "/([ ]*\r?\n[ ]*)+/", self::REGEX_REPLACE_NAME => "\n"),
        array(self::REGEX_RULE_NAME => "/\r?\n$/", self::REGEX_REPLACE_NAME => '')
    );

    /**
     * OCR_Handle constructor.
     */
    private function __construct()
    {
        $this->typos_fix_list = json_decode(file_get_contents(storage_path(self::TYPOS_LIST_PATH)), false);
        $this->removes_list = json_decode(file_get_contents(storage_path(self::REMOVES_LIST_PATH)), false);
    }

    /**
     * @return OCR_Handle|null
     */
    public static function getInstance(): ?OCR_Handle
    {
        if (!self::$instance) {
            self::$instance = new OCR_Handle();
        }
        return self::$instance;
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
        $this->replaceTypos($raw);
        $this->removeCrap($raw);
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
     * @return array
     */
    public function parseIntoArray($raw): array
    {
        $refined = $this->applyRegex($raw);
        $refined_array = explode("\n", $refined);
        if ($refined_array[0] === '') {
            $refined_array = array_splice($refined_array, 1);
        }
        return $refined_array;
    }
}
