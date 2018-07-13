<?php

namespace App\Domains\Player\Jobs;

use App\Data\Repositories\redisRepository;
use Lucid\Foundation\Job;

class ShotJob extends Job
{
    private $repository;
    private $gameId;
    private $game;
    private $player;
    protected $position;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($gameId, $position, $player)
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
        $y = $this->position::letterToNumber($this->position->letter());
        $x = $this->position->number();

        $game = $this->getGame();
        $this->game = $game['player'][$this->player]['game'];
        $shipId = $this->game['grid'][$y][$x];

        if ($shipId !== 0) {
            $this->game['attacks'][$y][$x] = -abs($this->game['grid'][$y][$x]);
            $game['player'][$this->player]['game'] = $this->game;
            $this->save($game);

            return [
                'status'   => 'hit',
                'position' => [
                    'x' => $x,
                    'y' => $y,
                ],
                ];
        }

        return ['status'=> 'mis', 'position' => [
            'x' => $x,
            'y' => $y,
        ]];
    }

    public function getGame()
    {
        return $this->repository->getGame($this->gameId, true);
    }

    public function save($game)
    {
        $game['player'][$this->player]['game'] = $this->game;
        $this->repository->save($game);
    }
}
