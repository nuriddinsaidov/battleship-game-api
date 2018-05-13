<?php
namespace App\Services\Api\Features;

use App\Domains\Game\Jobs\GameStatusJob;
use App\Domains\Http\Jobs\RespondWithJsonJob;
use App\Domains\Player\Jobs\ShotJob;
use App\Domains\Player\Jobs\ShotRecordJob;
use App\Domains\Ship\Jobs\PositionJob;
use Lucid\Foundation\Feature;
use Illuminate\Http\Request;
use App\Domains\Game\Jobs\StatusJob;

class ShotFeature extends Feature
{
    public function handle(Request $request)
    {


        $result['shot'] = $shotStatus = $this->run(ShotJob::class,[
            'gameId' => $request->gameId,
            'position' => new PositionJob($request->y,(int) $request->x),
            'player' => 'b'
        ]);

        $result['game'] = $this->run(GameStatusJob::class,[
            'gameId' => $request->gameId
        ]);

        $this->run(ShotRecordJob::class,[
            'gameId' => $request->gameId,
            'status'  => $shotStatus,
            'x' => $request->x,
            'y' => $request->y,
            'player' => 'b'

        ]);

        return $this->run(new RespondWithJsonJob($result));

    }
}
