<?php

namespace App\Domains\Grid\Jobs;

use App\Data\Repositories\redisRepository;
use Lucid\Foundation\Job;

class GetGridJob extends Job
{
    private $gameId;
    private $player;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($gameId, $player)
    {
        $this->gameId = $gameId;
        $this->player = $player;
    }

    /**
     * Execute the job.
     *
     * @param $repository
     *
     * @return mixed
     */
    public function handle(redisRepository $repository)
    {
        $game = $repository->getGame($this->gameId, true);

        return $game['player'][$this->player]['game'];
    }
}
