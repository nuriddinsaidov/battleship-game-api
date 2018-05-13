<?php

namespace App\Services\Api\Features;

use App\Domains\Grid\Jobs\GetGridJob;
use App\Domains\Grid\Jobs\UpdateGridJob;
use App\Domains\Http\Jobs\RespondWithJsonJob;
use App\Domains\Ship\Jobs\CruiserJob;
use App\Domains\Ship\Jobs\PatrolJob;
use App\Domains\Ship\Jobs\PlaceShipJob;
use App\Domains\Ship\Jobs\PositionJob;
use App\Domains\Ship\Jobs\BattleshipJob;
use App\Domains\Ship\Jobs\CarrierJob;
use App\Domains\Ship\Jobs\SubmarineJob;
use Lucid\Foundation\Feature;
use Illuminate\Http\Request;

class PlaceShipFeature extends Feature
{
    public function handle(Request $request)
    {

        $ship = $this->getShip($request->shipId);

        $result = $this->run(PlaceShipJob::class, [
            'ship' => $ship,
            'position' => new PositionJob($request->y,(int) $request->x),
            'grid' => $this->run(GetGridJob::class, [
                'gameId' => $request->gameId,
                'player' => 'a'
            ]),
        ]);

        $this->run(UpdateGridJob::class, [
            'grid' => $result['grid'],
            'ships' => $result['ships'],
            'gameId' => $request->gameId,
            'player' => 'a'
        ]);


        return $this->run(new RespondWithJsonJob($result));
    }


    public function getShip($shipId){

        switch ($shipId){
            case '1': return new CarrierJob(); break;
            case '2': return new BattleshipJob(); break;
            case '3': return new SubmarineJob(); break;
            case '4': return new CruiserJob(); break;
            case '5': return new PatrolJob(); break;
        }

    }
}
