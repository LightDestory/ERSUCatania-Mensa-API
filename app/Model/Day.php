<?php


namespace App\Model;

class Day
{
    public const
        MAIN_NAME = 'primi',
        SECOND_NAME = 'secondi',
        ACCOMPANIMENT_NAME = 'contorno',
        LUNCH_NAME = 'pranzo',
        DINNER_NAME = 'cena';

    public static $week_days = array
    (
        "lunedi", "martedi", "mercoledi", "giovedi", "venerdi", "sabato", "domenica"
    );

    private $menu, $name;

    /**
     * Day constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = self::$week_days[$name];
        $this->initializeMenu();
    }

    private function initializeMenu(): void
    {
        $this->menu = array
        (
            self::LUNCH_NAME => array
            (
                self::MAIN_NAME => array(),
                self::SECOND_NAME => array(),
                self::ACCOMPANIMENT_NAME => array()
            ),
            self::DINNER_NAME => array
            (
                self::MAIN_NAME => array(),
                self::SECOND_NAME => array(),
                self::ACCOMPANIMENT_NAME => array()
            )
        );
    }

    /**
     * @param $main_course
     * @param bool $isLunch
     */
    public function addMain($main_course, $isLunch = true): void
    {
        $type = $isLunch ? self::LUNCH_NAME : self::DINNER_NAME;
        $this->menu[$type][self::MAIN_NAME][] = $main_course;
    }

    /**
     * @param $second_course
     * @param bool $isLunch
     */
    public function addSecond($second_course, $isLunch = true): void
    {
        $type = $isLunch ? self::LUNCH_NAME : self::DINNER_NAME;
        $this->menu[$type][self::SECOND_NAME][] = $second_course;
    }

    /**
     * @param $accompaniment
     * @param bool $isLunch
     */
    public function addAccompaniment($accompaniment, $isLunch = true): void
    {
        $type = $isLunch ? self::LUNCH_NAME : self::DINNER_NAME;
        $this->menu[$type][self::ACCOMPANIMENT_NAME][] = $accompaniment;
    }

    /**
     * @return array
     */
    public function getMenu(): array
    {
        return $this->menu;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
