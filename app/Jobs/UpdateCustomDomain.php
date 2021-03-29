<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateCustomDomain implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $operation;
    private $domain;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($operation, $domain)
    {
        $this->operation = escapeshellarg($operation);
        $this->domain = escapeshellarg($domain);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $command = escapeshellarg(base_path('nginx_vhost.sh'))." {$this->operation} {$this->domain}";
        $command = escapeshellcmd($command)." 2>&1";
        exec($command, $sh_output, $sh_status);
        $log = [
            'command' => $command,
            'exit status' => $sh_status,
            'output' => $sh_output
        ];
        $sh_status == 0 ? \Log::info($log) : \Log::error($log);
    }
}
