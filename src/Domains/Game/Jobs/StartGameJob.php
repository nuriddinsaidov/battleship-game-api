<?php
namespace App\Domains\Game\Jobs;

use Illuminate\Support\Facades\Redis;
use Lucid\Foundation\Job;

class StartGameJob extends Job
{
    private $gameId;
    private $player;
    private $redis;

    public function __construct($player)
    {
        $this->redis = Redis::connection();
        $this->gameId = $this->redis->incr('gameId');
        $this->player = $player;
    }


    public function handle(){

        $config = [
            'gameId' =>   $this->gameId,
            'player' => $this->player,

        ];

        $this->redis->set('game_'.$this->gameId, json_encode($config));
        return $config;
    }

}
