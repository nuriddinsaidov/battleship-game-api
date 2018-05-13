<?php
namespace App\Domains\Game\Jobs;

use Lucid\Foundation\Job;

class GameResultJob extends Job
{

    private $winner;

    /**
     * @param string $winner
     * @param string $reason
     */
    public function __construct($winner)
    {
        $this->winner = $winner;
    }
    /**
     * @return string
     */
    public function winner()
    {
        return (string) $this->winner;
    }


}
