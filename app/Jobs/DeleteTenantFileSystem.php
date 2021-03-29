<?php

namespace App\Jobs;

use App\Helpers\CommonHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteTenantFileSystem implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $folder_path;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($folder_path)
    {
        $this->folder_path = $folder_path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        CommonHelper::removeDirecotyRecursively($this->folder_path);
    }
}
