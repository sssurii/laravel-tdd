<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;

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
            }
        );
    }
}
