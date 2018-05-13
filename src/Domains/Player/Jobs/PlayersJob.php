<?php
namespace App\Domains\Player\Jobs;

use Lucid\Foundation\Job;

class PlayersJob extends Job
{
    public static $count = 0;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        PlayersJob::$count++;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function __destruct()
    {
        PlayersJob::$count--;
    }
}
