<?php
namespace App\Domains\Grid\Jobs;

use Lucid\Foundation\Job;

class CreateGridJob extends Job
{
    /**
     * @var Grid
     */
    private $grid;

    private $ships;

    const WATER = 0;

    public function __construct($ships)
    {

        $this->ships = $ships;

    }

    /**
     * Execute the job.
     *
     * @return array
     */
    public function handle()
    {

        return [
            'ships' => $this->getShips(),
            'grid' => $this->getGrid()
        ];

    }

    /**
     * @return \App\Domains\Grid\Jobs\Grid
     */
    public function getGrid()
    {
        $this->grid = [];
        foreach (static::letters() as $i => $letter) {
            foreach (static::numbers() as $j => $number) {
                $this->grid[$i][$j] = static::WATER;
            }
        }
        return $this->grid;
    }

    /**
     * @return array
     */
    public static function letters()
    {
        return range('A', 'J');
    }


    /**
     * @return array
     */
    public static function numbers()
    {
        return range(1, 10);
    }

    public function getShips(){

        return $this->ships;
    }
}
