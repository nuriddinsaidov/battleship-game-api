<?php
namespace App\Services\Api\Tests\Features;

use Tests\TestCase;
use App\Services\Api\Features\GameFeature;
use App\Services\Api\Features\PlayerFeature;

class GameFeatureTest extends TestCase
{
    public function test_gamefeature()
    {
       $game = new GameFeature(
           new PlayerFeature(),
           new PlayerFeature()
       );

       var_dump($game->play());
    }
}
