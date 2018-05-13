<?php
namespace App\Domains\Player\Jobs;

use Illuminate\Support\Facades\Redis;
use Lucid\Foundation\Job;

class BotPlayerJob extends Job
{
    private $playerId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $redis = Redis::connection();
        $this->playerId = $redis->incr('playerId');
    }


    /**
     * Execute the job.
     *
     * @return array
     */
    public function handle()
    {
        return [
            'type' => 'Bot',
            'id' => $this->playerId
        ];
    }
}
