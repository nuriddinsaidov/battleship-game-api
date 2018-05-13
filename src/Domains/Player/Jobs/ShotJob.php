<?php
namespace App\Domains\Player\Jobs;

use Lucid\Foundation\Job;
use App\Data\Repositories\redisRepository;

class ShotJob extends Job
{
    private $repository;
    private $gameId;
    private $x;
    private $y;
    private $game;
    private $player;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($gameId,$position,$player)
    {
        $this->gameId = $gameId;
        $this->repository = new redisRepository();
        $this->position = $position;
        $this->player = $player;

    }

    /**
     * Execute the job.
     *
     * @return array
     */
    public function handle()
    {

        $x = $this->position::letterToNumber($this->position->letter());
        $y = $this->position->number();
        $game = $this->getGame();
        $this->game = $game['player'][$this->player]['game'];
        $shipId = $this->game['grid'][$y][$x];



        if ($shipId !== 0) {
            $this->game['attacks'][$y][$x] = -abs($this->game['grid'][$y][$x]);
            $this->save($game);
            return ['status'=>'hit'];

        }

        return ['status'=>'mis'];
    }

    /*private function isShipSunk($ship)
    {
        $size = $ship;
        $count = 0;
        foreach ($this->grid as $y => $letter) {
            foreach ($letter as $x => $number) {
                if ($this->grid[$y][$x] === -$ship->id()) {
                    ++$count;
                }
            }
        }
        return $count === $size;
    }*/


    public function getGame()
    {
        return $this->repository->getGame($this->gameId,true);
    }

    public function save($game){

        $game['player'][$this->player]['game'] =$this->game;
        $this->repository->save($game);

    }


}
