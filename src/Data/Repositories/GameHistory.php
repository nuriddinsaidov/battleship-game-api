<?php

namespace App\Data\Repositories;

use Illuminate\Support\Facades\Redis;

class GameHistory
{
    private $redis;

    private $gameId;

    private $player;

    public function __construct($gameId, $player)
    {
        $this->redis = Redis::connection();
        $this->gameId = $gameId;
        $this->player = $player;
    }

    public function recordShot($shot, $turn)
    {
        $shotList = $this->getShotList();
        $shotList[$turn] = $shot;
        $this->redis->set('game_'.$this->gameId.'_'.$this->player, json_encode($shotList));
    }

    public function getShotList()
    {
        $records = $this->redis->get('game_'.$this->gameId.'_'.$this->player);

        return json_decode($records, true);
    }

    public function nextPlayer()
    {
        $this->redis->incr('gameId_'.$this->gameId.'turn_');
    }

    public function getTurn()
    {
        return $this->redis->get('gameId_'.$this->gameId.'turn_');
    }
}
