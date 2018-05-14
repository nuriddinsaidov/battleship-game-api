<?php
namespace App\Services\Api\Features;

use App\Domains\Http\Jobs\RespondWithJsonJob;
use App\Domains\Player\Jobs\ShotListJob;
use Lucid\Foundation\Feature;
use Illuminate\Http\Request;

class ShotListFeature extends Feature
{
    public function handle(Request $request)
    {

        $result = $this->run(ShotListJob::class,[
            'gameId' => $request->gameId,
            'player' => $request->player,
        ]);

        return $this->run(new RespondWithJsonJob($result));
    }
}
