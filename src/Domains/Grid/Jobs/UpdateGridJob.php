<?php

namespace App\Domains\Grid\Jobs;

use App\Data\Repositories\redisRepository;
use Lucid\Foundation\Job;

class UpdateGridJob extends Job
{
    private $grid;

    private $ships;

    private $player;

    private $gameId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($grid, $ships, $gameId, $player)
    {
        $this->grid = $grid;
        $this->ships = $ships;
        $this->player = $player;
        $this->gameId = $gameId;
    }

    /**
     * Execute the job.
     *
     * @param $repository
     *
     * @return void
     */
    public function handle(redisRepository $repository)
    {
        $repository->setShip($this->ships, $this->gameId, $this->player);

        $repository->setGrid($this->grid, $this->gameId, $this->player);
    }
}
