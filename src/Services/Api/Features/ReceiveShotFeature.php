<?php
namespace App\Services\Api\Features;

use App\Domains\Game\Jobs\StatusJob;
use App\Domains\Http\Jobs\RespondWithJsonJob;
use App\Domains\Player\Jobs\ShotJob;
use App\Domains\Player\Jobs\ShotRecordJob;
use Lucid\Foundation\Feature;
use Illuminate\Http\Request;

class ReceiveShotFeature extends Feature
{
    public function handle(Request $request)
    {

        $result['shotResult'] = $shotStatus = $this->run(ShotJob::class,[
            'gameId' => $request->gameId,
            'x' => $request->x,
            'y' => $request->y,
            'player' => 'a'
        ]);

        $result['gameStatus'] = $this->run(StatusJob::class,[
            'gameId' => $request->gameId
        ]);

        $this->run(ShotRecordJob::class,[
            'gameId' => $request->gameId,
            'status'  => $shotStatus,
            'x' => $request->x,
            'y' => $request->y

        ]);

        return $this->run(new RespondWithJsonJob($result));
    }
}
