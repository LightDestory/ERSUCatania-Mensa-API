<?php


namespace App\Model;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class Reporter
{
    public const
        ERROR_REPORTING_MAIL_ID = 'ERROR_REPORTING_MAIL',
        MAIL_REPORTING_ID = 'MAIL_REPORTING',
        ALREADY_REPORT_FILE_FLAG_PATH_ID = 'ALREADY_REPORT_FILE_FLAG_PATH',
        ERROR_STATUS = 'Error',
        SUCCESS_STATUS = 'Success',
        UNAUTHORIZED_MESSAGE = 'Unauthorized',
        SYSTEM_RUN_MESSAGE = 'Unscheduled run executed',
        MISSING_MENU_MESSAGE = 'Il menu di questa settimana non è stato trovato, probabilmente l\'ERSU non lo ha ancora pubblicato.',
        INVALID_ARRAY_SEGMENTATION_MESSAGE = 'L\'array di pietanze non ha superato il controllo numerico.',
        INVALID_DAY_MESSAGE = 'Il giorno inserito non esiste.',
        INVALID_TIME_MESSAGE = 'L\'orario inserito non esiste.',
        SUCCESSFUL_RUNTIME = 'Aggiornamento eseguito';

    private static $instance;

    private function __construct()
    {
    }

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
        if((bool)env(self::MAIL_REPORTING_ID) && !file_exists(storage_path(env(self::ALREADY_REPORT_FILE_FLAG_PATH_ID)))) {
            Mail::send('emails.alert', ['caller' => $caller, 'text' => $text, 'date' => date('d-m-Y')], static function (Message $message) {
                $message->to(env(self::ERROR_REPORTING_MAIL_ID))->subject('ERSUCatania Mensa Reporting');
            });
            file_put_contents(storage_path(env(self::ALREADY_REPORT_FILE_FLAG_PATH_ID)), '1');
        }
    }

}
