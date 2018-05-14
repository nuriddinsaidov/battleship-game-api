<?php
namespace App\Services\Api\Features;

use App\Domains\Game\Jobs\GameStatusJob;
use App\Domains\Http\Jobs\RespondWithJsonJob;
use App\Domains\Player\Jobs\ShotJob;
use App\Domains\Player\Jobs\ShotRecordJob;
use App\Domains\Player\Jobs\TurnJob;
use App\Domains\Ship\Jobs\PositionJob;
use Lucid\Foundation\Feature;
use Illuminate\Http\Request;
use App\Domains\Game\Jobs\StatusJob;

class ShotFeature extends Feature
{
    public function handle(Request $request)
    {

        $turn = $this->run(TurnJob::class,[
            'gameId' => $request->gameId,
            'player' => 'a'
        ]);

        $result['status'] = $this->run(GameStatusJob::class,[
            'gameId' => $request->gameId,
            'player' => 'a'
        ]);


        $result['shot'] =  $this->run(ShotJob::class,[
            'gameId' => $request->gameId,
            'position' => new PositionJob($request->y,(int) $request->x),
            'player' => 'b'
        ]);


        $this->run(ShotRecordJob::class,[
            'gameId' => $request->gameId,
            'shot'  => $result['shot'],
            'position' =>  new PositionJob($request->y,(int) $request->x),
            'player' => 'a',
            'turnId' => $turn->get()
        ]);

       // $turn->next();

        return $this->run(new RespondWithJsonJob($result));

    }
}
