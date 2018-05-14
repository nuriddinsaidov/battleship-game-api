<?php
namespace App\Domains\Game\Jobs;

use App\Data\Repositories\redisRepository;
use Lucid\Foundation\Job;
use App\Data\Repositories\GameHistory;

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
