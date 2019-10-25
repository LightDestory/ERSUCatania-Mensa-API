<?php


namespace App\Model;

use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class Reporter
{
    private const
        VALIDATION_COUNT_ID = 'VALIDATION_COUNT';

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

    public function validateArray($array): bool
    {
        if(count($array) === env(self::VALIDATION_COUNT_ID)) {
            return true;
        }
        $this->report(__FUNCTION__, 'Invalid array segmentation');
        return false;
    }

    public function report($caller, $text): void
    {
        Mail::send('emails.alert', ['caller' => $caller, 'text' => $text, 'date' => date('d-m-Y')], static function(Message $message){
            $message->to('123@123.com')->subject("Test");
        });
    }

}
