<?php


namespace App\Data\Repositories;


use Illuminate\Support\Facades\Redis;

class RedisRepository
{

    private $redis;

    private $result;

    public function __construct()
    {
        $this->redis = Redis::connection();
    }

    public function getGame($id, $withGrid =false)
    {
        $game = $this->redis->get('game_'.$id);

        if (!$game) {
            throw new \InvalidArgumentException('Game does not exist');
        }

        $result = json_decode($game, true);

        if(!$withGrid) {
            unset($result['player']['b']['game']['grid']);
            unset($result['player']['b']['game']['ships']);
        }

        if (!$result) {
            throw new \InvalidArgumentException('There was a problem to decode the game');
        }

        return $result;
    }

    public function get($id, $withGrid =false)
    {
        $game = $this->redis->get('game_'.$id);

        if (!$game) {
            throw new \InvalidArgumentException('Game does not exist');
        }

        $this->result = json_decode($game, true);

        if(!$withGrid) {
            unset($this->result['player']['b']['game']['grid']);
            unset($this->result['player']['b']['game']['ships']);
        }

        if (!$this->result) {
            throw new \InvalidArgumentException('There was a problem to decode the game');
        }

        return $this;
    }


    public function player($id)
    {

       return  $this->result['player'][$id];

    }

    public function setConfig($config){

        $this->result['config']=$config;

        $this->save($this->result);

    }

    public function getTurn(){

       return  $this->result['config'];

    }

    public function save($game)
    {
        $this->redis->set('game_'.$game['gameId'], json_encode($game));
    }

    public function delete($game)
    {
        $this->redis->del('game_'.$game->id());
    }

    public function setGrid($grid, $gameId, $player)
    {
        $game = $this->getGame($gameId, true);
        $game['player'][$player]['game']['grid']=$grid;
        $this->save($game);

    }

    public function setShip($ship, $gameId, $player)
    {
        $game = $this->getGame($gameId, true);
        $game['player'][$player]['game']['ships']=$ship;
        $this->save($game);

    }


}