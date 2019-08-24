<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;
use INSAN\ICS\ICS;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return Mail::send(
            'emails.test-email',
            ['mail_data' => ['name' => env('SENDER_NAME')]],
            function ($message) {
                $message->from(env('SENDER_EMAIL'), env('SENDER_NAME'));
                $message->to('surindersingh@ucreate.co.in');
                $message->subject('Thanks for joining - Email via Postmark');
                $message->addPart($this->getICSFile([], false), 'text/calendar; charset="utf-8"; method=REQUEST');
            }
        );
    }

    private function getICSFile($mail_data, $cancel_invitation)
    {
        $ics_file = new ICS([
            'uid' => uniqid(),
            'sequence' => 0,
            'description' => 'Event Invitation via email.',
            'dtstart' => date('Y-m-d 09:00'),
            'dtend' => date('Y-m-d 10:00'),
            'summary' => 'This is an event invitation sent through email.',
            'location' => 'VR Punjab, S.A.S Nagar, Chandigarh',
            'url' => 'www.example.com',
        ]);
        //Optional
        $ics_file->setOrganizer('Surinder', 'sssurii.dev@gmail.com');
        //Optional
        if ($cancel_invitation) {
            $ics_file->markEventCancel();
        }
        return $ics_file->toString();
    }
}
