<?php


namespace App\Model;

class Day
{
    public const
        MAIN_NAME = 'main',
        SECOND_NAME = 'second',
        ACCOMPANIMENT_NAME = 'accompaniment',
        LUNCH_NAME = 'lunch',
        DINNER_NAME = 'dinner',
        FULL_MODE = 0,
        LUNCH_MODE = 1,
        DINNER_MODE = 2;

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
            self::MAIN_NAME => array
            (
                self::LUNCH_NAME => array(),
                self::DINNER_NAME => array()
            ),
            self::SECOND_NAME => array
            (
                self::LUNCH_NAME => array(),
                self::DINNER_NAME => array()
            ),
            self::ACCOMPANIMENT_NAME => array
            (
                self::LUNCH_NAME => array(),
                self::DINNER_NAME => array()
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
        $this->menu[self::MAIN_NAME][$type][] = $main_course;
    }

    /**
     * @param $second_course
     * @param bool $isLunch
     */
    public function addSecond($second_course, $isLunch = true): void
    {
        $type = $isLunch ? self::LUNCH_NAME : self::DINNER_NAME;
        $this->menu[self::SECOND_NAME][$type][] = $second_course;
    }

    /**
     * @param $accompaniment
     * @param bool $isLunch
     */
    public function addAccompaniment($accompaniment, $isLunch = true): void
    {
        $type = $isLunch ? self::LUNCH_NAME : self::DINNER_NAME;
        $this->menu[self::ACCOMPANIMENT_NAME][$type][] = $accompaniment;
    }

    /**
     * @return array
     */
    public function getFullMenu(): array
    {
        return $this->menu;
    }

    /**
     * @param int $mode
     * @return array
     */
    public function getMainMenu($mode = self::FULL_MODE): array
    {
        switch ($mode) {
            default:
                return $this->menu[self::MAIN_NAME];
            case self::LUNCH_MODE:
                return $this->menu[self::MAIN_NAME][self::LUNCH_NAME];
            case self::DINNER_MODE:
                return $this->menu[self::MAIN_NAME][self::DINNER_NAME];
        }
    }

    /**
     * @param int $mode
     * @return array
     */
    public function getSecondMenu($mode = self::FULL_MODE): array
    {
        switch ($mode) {
            default:
                return $this->menu[self::SECOND_NAME];
            case self::LUNCH_MODE:
                return $this->menu[self::SECOND_NAME][self::LUNCH_NAME];
            case self::DINNER_MODE:
                return $this->menu[self::SECOND_NAME][self::DINNER_NAME];
        }
    }

    /**
     * @param int $mode
     * @return array
     */
    public function getAccompanimentMenu($mode = self::FULL_MODE): array
    {
        switch ($mode) {
            default:
                return $this->menu[self::ACCOMPANIMENT_NAME];
            case self::LUNCH_MODE:
                return $this->menu[self::ACCOMPANIMENT_NAME][self::LUNCH_NAME];
            case self::DINNER_MODE:
                return $this->menu[self::ACCOMPANIMENT_NAME][self::DINNER_NAME];
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
