<?php
namespace App\Domains\Grid\Jobs;

use Lucid\Foundation\Job;

class GenerateGridJob extends Job
{
    private $grid;

    /**
     * Create a new job instance.
     *
     * @param $grid
     */
    public function __construct($grid)
    {
        $this->grid = $grid;
    }

    /**
     * Execute the job.
     *
     * @return string
     */
    public function handle()
    {
        $out = '';
        for ($letter = 1; $letter < count($this->grid); ++$letter) {
            for ($number = 1; $number < count($this->grid[$letter]); ++$number) {
                $out .= $this->grid[$letter][$number];
            }
        }
        return $out;
    }
}
