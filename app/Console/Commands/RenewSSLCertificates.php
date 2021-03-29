<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RenewSSLCertificates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'renew_ssl_certitificates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renew ssl certificates by certbot (letsencrypt)';

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
        $command = "sudo certbot renew 2>&1";
        exec($command, $sh_output, $sh_status);
        $log = [
            'command' => $command,
            'exit status' => $sh_status,
            'output' => $sh_output
        ];
        $sh_status == 0 ? \Log::info($log) : \Log::error($log);
    }
}
