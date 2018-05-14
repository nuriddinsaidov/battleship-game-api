<?php
namespace App\Domains\Player\Jobs;

use Lucid\Foundation\Job;
use App\Data\Repositories\GameHistory;

class ShotListJob extends Job
{

    private $history;

    /**
     * Create a new job instance.
     *
     * @param $gameId
     * @param $player
     */
    public function __construct($gameId, $player)
    {

        $this->history = new GameHistory($gameId, $player);
    }

    /**
     * Execute the job.
     *
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->history->getShotList();
    }
}
