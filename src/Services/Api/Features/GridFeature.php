<?php

namespace App\Services\Api\Features;

use App\Domains\Game\Jobs\StartGameJob;
use App\Domains\Grid\Jobs\CreateGridJob;
use App\Domains\Http\Jobs\RespondWithJsonJob;
use App\Domains\Player\Jobs\BotPlayerJob;
use App\Domains\Player\Jobs\HumanPlayerJob;
use App\Domains\Player\Jobs\PlaceBotShipJob;
use App\Domains\Ship\Jobs\BattleshipJob;
use App\Domains\Ship\Jobs\CarrierJob;
use App\Domains\Ship\Jobs\CruiserJob;
use App\Domains\Ship\Jobs\PatrolJob;
use App\Domains\Ship\Jobs\ShipsJob;
use App\Domains\Ship\Jobs\SubmarineJob;
use Lucid\Foundation\Feature;

class GridFeature extends Feature
{
    public function handle()
    {
        $player['a'] = $this->run(new HumanPlayerJob());
        $player['b'] = $this->run(new BotPlayerJob());

        $ships = $this->run(ShipsJob::class);

        $emptyGrid = $this->run(CreateGridJob::class, [
            'ships' => $ships,
        ]);

        $player['a']['game'] = $emptyGrid;
        $player['b']['game'] = $this->autoPlaceShips($emptyGrid);

        $player['a']['game']['attacks'] = $emptyGrid['grid'];
        $player['b']['game']['attacks'] = $emptyGrid['grid'];

        $result = $this->run(StartGameJob::class, [
            'player' => $player,
        ]);

        unset($result['player']['b']['game']['grid']);
        unset($result['player']['b']['game']['ships']);

        return $this->run(new RespondWithJsonJob($result));
    }

    public function autoPlaceShips($grid)
    {
        $result = $this->run(PlaceBotShipJob::class, [
            'ships' => [
                new BattleshipJob(),
                new CarrierJob(),
                new CruiserJob(),
                new PatrolJob(),
                new SubmarineJob(),
            ],
            'grid' => $grid,
        ]);

        return $result;
    }
}
