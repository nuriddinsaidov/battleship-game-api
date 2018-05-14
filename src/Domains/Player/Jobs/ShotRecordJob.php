<?php
namespace App\Domains\Player\Jobs;

use Lucid\Foundation\Job;
use App\Data\Repositories\GameHistory;

class ShotRecordJob extends Job
{
    private $gameId;

    private $shot;

    private $position;

    private $player;

    private $history;

    private $turnId;

    /**
     * Create a new job instance.
     *
     * @param $gameId
     * @param $shot
     * @param $position
     * @param $player
     */
    public function __construct($gameId, $shot, $position, $player, $turnId)
    {

        $this->gameId = $gameId;
        $this->shot = $shot;
        $this->position = $position;
        $this->player = $player;
        $this->turnId = $turnId;
        $this->history = new GameHistory($this->gameId, $this->player);

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->history->recordShot($this->shotMapper(), $this->turnId);
    }

    public function shotMapper(){
        return [
            'position' => [
                'letter' => $this->position->letter(),
                'number' => $this->position->number()
             ],
            'shotStatus' => $this->shot['status']
        ];
    }
}
