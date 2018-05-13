<?php
namespace App\Domains\Game\Jobs;

use Lucid\Foundation\Job;
use App\Data\Repositories\redisRepository;

class StatusJob extends Job
{

    private $repository;

    private $gameId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($gameId)
    {
        $this->gameId = $gameId;
        $this->repository = new redisRepository();
    }

    /**
     * Execute the job.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->repository->getGame($this->gameId);
    }
}
