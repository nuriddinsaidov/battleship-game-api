<?php

namespace App\Domains\Player\Jobs;

use Lucid\Foundation\Job;

class PlaceBotShipJob extends Job
{
    private $ships;

    private $grid;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ships, $grid)
    {
        $this->grid = $grid;
        $this->ships = $ships;
    }

    /**
     * Execute the job.
     *
     * @return array
     */
    public function handle()
    {
        foreach ($this->ships as $ship) {
            $shipId = $ship::ID;

            if (isset($this->grid['ships'][$shipId])) {
                throw new ShipAlreadyPlacedException();
            }
            $position = $this->checkMatchPosition($ship::SIZE);
            $maxAdditionalElements = $ship::SIZE + $position['x'];

            for ($i = 0; $i < $ship::SIZE; $i++) {
                if (!isset($this->grid['grid'][$position['x']][$position['y']])) {
                    throw new \OutOfBoundsException('Ship does not fit into the grid with such a position/');
                }
                if ($this->grid['grid'][$position['x']][$position['y']] > 0) {
                    throw new \InvalidArgumentException('Ship overlaps with another one, please choose another space.'.$position['y'].'/'.$position['x'].'/'.$ship::SIZE.'/'.$ship::ID);
                }

                $this->grid['grid'][$position['x']][$position['y']] = $ship::ID;
                $this->grid['ships'][$shipId] = $ship->getName();

                if ($maxAdditionalElements >= 10) {
                    $position['x']--;
                } else {
                    $position['x']++;
                }
            }
        }

        return $this->grid;
    }

    public function generatePosition()
    {
        $x = mt_rand(0, 9);
        $y = mt_rand(0, 9);

        return [
          'x' => $x,
          'y' => $y,
        ];
    }

    public function checkMatchPosition($shipSize)
    {
        $pos = $this->generatePosition();
        $x = $pos['x'];
        $y = $pos['y'];

        $maxAdditionalElements = $shipSize + $x;

        $gisShipSize = $shipSize;

        while ($shipSize > 0) {
            if ($this->grid['grid'][$x][$y] > 0) {
                $pos = $this->generatePosition();
                $x = $pos['x'];
                $y = $pos['y'];
                $maxAdditionalElements = $gisShipSize + $x;
                $shipSize = $gisShipSize;
                continue;
            }

            if ($maxAdditionalElements >= 10) {
                $x--;
            } else {
                $x++;
            }

            $shipSize--;
        }

        return $pos;
    }
}
