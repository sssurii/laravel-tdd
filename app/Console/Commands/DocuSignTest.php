<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Components\DocuSignAuthorization;
use DocuSign\eSign\Configuration;
use DocuSign\eSign\Client\ApiClient;

class DocuSignTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'docusign:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //
        $config = new Configuration();
        $apiClient = new ApiClient($config);
        $docusign_auth = new DocuSignAuthorization($apiClient);
        $docusign_auth->checkToken();
    }
}
