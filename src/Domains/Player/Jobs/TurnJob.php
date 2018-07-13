<?php

namespace App\Domains\Player\Jobs;

use App\Data\Repositories\GameHistory;
use Lucid\Foundation\Job;

class TurnJob extends Job
{
    private $history;

    private $player;

    protected $result;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($gameId, $player)
    {
        $this->history = new GameHistory($gameId, $player);
        $this->player = $player;
    }

    /**
     * Execute the job.
     *
     * @return mixed
     */
    public function handle()
    {
        $turn = '';
        if ($this->get() % 2 === 0) {
            $turn = 'a';
        } else {
            $turn = 'b';
        }

        if ($this->player === $turn) {
            $this->result = true;
        }

        if ($this->player !== $turn) {
            throw new \InvalidArgumentException('opponents turn');
        }

        return $this;
    }

    public function checkTurn()
    {
        return $this->result;
    }

    public function get()
    {
        return $this->history->getTurn();
    }

    public function next()
    {
        $this->history->nextPlayer();
    }
}
