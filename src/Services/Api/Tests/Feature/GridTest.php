<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Api\Features\GridFeature as Grid;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Domains\Ship\Jobs\BattleshipJob;
use App\Domains\Ship\Jobs\CarrierJob;
use App\Domains\Ship\Jobs\CruiserJob;
use App\Domains\Ship\Jobs\PatrolJob;
use App\Domains\Ship\Jobs\SubmarineJob;
use App\Domains\Ship\Jobs\PositionJob;
use App\Services\Api\Features\ShipFeature;

class GridTest extends TestCase
{

    private $grid;
    private $ship;

    public function testCreateGrid() {

        $this->givenAnEmptyGrid();
        $this->thenICanPlaceShips();
        $this->andIsPositionsOfShipsNotTheSame();
        $this->andIsTypeOfTheShipNotMoreThenOneKind();
    }


    protected function givenAnEmptyGrid() {

        $this->grid = new Grid();
        $this->assertEquals(0, array_sum($this->grid->handle()));

    }

    protected function thenICanPlaceShips() {

        $this->ship = new ShipFeature();
        $this->markTestIncomplete('Time to code thenICanPlaceShips');

    }

    protected function andIsPositionsOfShipsNotTheSame() {

        $this->markTestIncomplete('Time to code andIsPositionsOfShipsNotTheSame');

    }

    protected function andIsTypeOfTheShipNotMoreThenOneKind() {

        $this->markTestIncomplete('Time to code andIsTypeOfTheShipNotMoreThenOneKind');

    }


}
