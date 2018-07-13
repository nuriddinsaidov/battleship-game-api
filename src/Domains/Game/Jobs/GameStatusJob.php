<?php

namespace App\Domains\Game\Jobs;

use Lucid\Foundation\Job;

class GameStatusJob extends Job
{
    private $winner;

    private $gameId;
    private $player;

    public function __construct($gameId, $player)
    {
        $this->gameId = $gameId;
        $this->player = $player;
    }

    public function handle()
    {
    }
}
