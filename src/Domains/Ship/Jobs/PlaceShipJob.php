<?php
namespace App\Domains\Ship\Jobs;

use Lucid\Foundation\Job;

class PlaceShipJob extends Job
{
    private $ship;

    private $position;

    private $grid;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ship,  $position, $grid)
    {
        $this->position = $position;
        $this->grid = $grid;
        $this->ship = $ship;
    }

    /**
     * Execute the job.
     *
     * @return array
     */
    public function handle()
    {
        $shipId = $this->ship::ID;

        if (isset($this->grid['ships'][$shipId])) {
            throw new ShipAlreadyPlacedException();
        }

        for ($i = 0; $i < $this->ship::SIZE; ++$i) {
            $x = $this->position::letterToNumber($this->position->letter())-$i;
            $y = $this->position->number();
            if (!isset($this->grid['grid'][$x][$y])) {
                throw new \OutOfBoundsException('Ship does not fit into the grid with such a hole and position/'.$x.'/'.$y);
            }
            if ($this->grid['grid'][$x][$y] > 0) {
                throw new \InvalidArgumentException('Ship overlaps with another one, please choose another space.');
            }
            $this->grid['grid'][$x][$y] = $this->ship::ID;
            $this->grid['ships'][$shipId] = $this->ship->getName();
        }

        return $this->grid;
    }

}
