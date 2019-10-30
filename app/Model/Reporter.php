<?php


namespace App\Model;

use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class Reporter
{
    public const
        ERROR_REPORTING_MAIL_ID = 'ERROR_REPORTING_MAIL';

    private static $instance;

    private function __construct()
    {}

    /**
     * @return Reporter
     */
    public static function getInstance(): Reporter
    {
        if (!self::$instance) {
            self::$instance = new Reporter();
        }
        return self::$instance;
    }

    public function report($caller, $text): void
    {
        Mail::send('emails.alert', ['caller' => $caller, 'text' => $text, 'date' => date('d-m-Y')], static function(Message $message){
            $message->to(env(self::ERROR_REPORTING_MAIL_ID))->subject('ERSUCatania Mensa Reporting');
        });
    }

}
