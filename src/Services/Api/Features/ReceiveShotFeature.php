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

class ReceiveShotFeature extends Feature
{
    public function handle(Request $request)
    {

        $turn = $this->run(TurnJob::class,[
            'gameId' => $request->gameId,
            'player' => 'b'
        ]);

        $result['status'] = $this->run(GameStatusJob::class,[
            'gameId' => $request->gameId,
            'player' => 'a'
        ]);

        $result['shot'] = $shotStatus = $this->run(ShotJob::class,[
            'gameId' => $request->gameId,
            'position' => new PositionJob(),
            'player' => 'b'
        ]);


        $this->run(ShotRecordJob::class,[
            'gameId' => $request->gameId,
            'status'  => $shotStatus,
            'shot'  => $result['shot'],
            'position' => $result['shot']['position'],
            'player' => 'b',
            'turnId' => $turn->get()
        ]);

        $turn->next();

        return $this->run(new RespondWithJsonJob($result));
    }
}
