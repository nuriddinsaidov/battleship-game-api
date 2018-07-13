<?php

namespace Tests\Unit;

use App\Domains\Grid\Jobs\CreateGridJob;
use Tests\TestCase;

class GridTest extends TestCase
{
    private $grid;
    private $ship;

    public function testCreateGrid()
    {
        $this->givenAnEmptyGrid();
        $this->thenICanPlaceShips();
        $this->andIsPositionsOfShipsNotTheSame();
        $this->andIsTypeOfTheShipNotMoreThenOneKind();
    }

    protected function givenAnEmptyGrid()
    {
        $emptyGrid = new CreateGridJob();
        $this->grid = $emptyGrid->handle();
    }

    protected function thenICanPlaceShips()
    {
        $this->markTestIncomplete('Time to code thenICanPlaceShips');
    }

    protected function andIsPositionsOfShipsNotTheSame()
    {
        $this->markTestIncomplete('Time to code andIsPositionsOfShipsNotTheSame');
    }

    protected function andIsTypeOfTheShipNotMoreThenOneKind()
    {
        $this->markTestIncomplete('Time to code andIsTypeOfTheShipNotMoreThenOneKind');
    }
}
