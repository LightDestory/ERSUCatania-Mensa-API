<?php


namespace App\Model;


class Reporter
{
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance(): ?Reporter
    {
        if (!self::$instance) {
            self::$instance = new Reporter();
        }
        return self::$instance;
    }

}
