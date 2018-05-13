<?php
namespace App\Domains\Game\Jobs;

use Lucid\Foundation\Job;

class GameStatusJob extends Job
{
    private $currentPlayer;

    private $opponentPlayer;

    private $players;

    private $turn;

    private $winner;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($playerA, $playerB)
    {
        $this->currentPlayer = $playerA->getPlayer();
        $this->opponentPlayer = $playerB->getPlayer();

        $this->players = [
            $this->currentPlayer->id() => $playerA,
            $this->opponentPlayer->id() => $playerB
        ];

        $this->turn = 1;
        $this->winner = null;
    }

    /**
     * @return \App\Domains\Game\Jobs\GameResultJob
     */
    public function handle()
    {
        try {
            $this->tryToStartTheGameOnPlayer($this->currentPlayer);
            $this->tryToStartTheGameOnPlayer($this->opponentPlayer);
            while (!$this->isGameFinished()) {
                $this->playTurn();
            }
        } catch (\Exception $e) {
            $this->reason = $e->getMessage();
        } finally {
            $this->tryToFinishGameOnPlayer($this->currentPlayer);
            $this->tryToFinishGameOnPlayer($this->opponentPlayer);
        }

    }

    public function turn($player){


    }
}
