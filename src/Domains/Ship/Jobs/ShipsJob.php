<?php

namespace App\Domains\Ship\Jobs;

use Lucid\Foundation\Job;

class ShipsJob extends Job
{
    /**
     * @var Grid
     */
    private $ships;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ships = [];
    }

    /**
     * Execute the job.
     *
     * @return array
     */
    public function handle()
    {
        return $this->ships;
    }
}
