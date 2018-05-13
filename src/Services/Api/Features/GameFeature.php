<?php
namespace App\Services\Api\Features;

use App\Domains\Game\Jobs\GameResultJob;
use App\Domains\Game\Jobs\StartGameJob;
use App\Domains\Grid\Jobs\CreateGridJob;
use App\Domains\Grid\Jobs\GenerateGridJob;
use App\Domains\Player\Jobs\BotPlayerJob;
use App\Domains\Player\Jobs\HumanPlayerJob;
use App\Domains\Player\Jobs\PlaceBotShipJob;
use App\Domains\Ship\Jobs\BattleshipJob;
use App\Domains\Ship\Jobs\CarrierJob;
use App\Domains\Ship\Jobs\CruiserJob;
use App\Domains\Ship\Jobs\PatrolJob;
use App\Domains\Ship\Jobs\PositionJob;
use App\Domains\Ship\Jobs\ShipsJob;
use App\Domains\Ship\Jobs\SubmarineJob;
use Illuminate\Support\Facades\Redis;
use Lucid\Foundation\Feature;
use Illuminate\Http\Request;

class GameFeature extends Feature
{

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle(Request $request){


        $player['a'] = $this->run(new HumanPlayerJob());
        $player['b'] = $this->run(new BotPlayerJob());

        $ships = $this->run(ShipsJob::class);

        $emptyGrid = $this->run(CreateGridJob::class,[
            'ships' => $ships,
        ]);

        $player['a']['game'] = $this->autoPlaceShips($emptyGrid);

        $player['b']['game'] = $this->autoPlaceShips($emptyGrid);

        $player['a']['game']['attacks'] = $emptyGrid['grid'];
        $player['b']['game']['attacks'] = $emptyGrid['grid'];

        $result = $this->run(StartGameJob::class,[
            'player' => $player
        ]);

        unset($result['player']['b']['game']['grid']);
        unset($result['player']['b']['game']['ships']);

        return [
            'gameId' => $result['gameId'],
            'player' => $result['player']['a'],
            'opponent' => $result['player']['b']
        ];

    }

    public function generateGrid($grid){

        $result = $this->run(PlaceShipJob::class,[
            'ship' => new BattleshipJob(),
            'position' => new PositionJob('C', 3),
            'grid' => $grid,
        ]);

        $result = $this->run(PlaceShipJob::class,[
            'ship' => new CarrierJob(),
            'position' => new PositionJob('D', 4),
            'grid' => $result,
        ]);

        $result = $this->run(PlaceShipJob::class,[
            'ship' => new CruiserJob(),
            'position' => new PositionJob('A', 2),
            'grid' => $result,
        ]);

        $result = $this->run(PlaceShipJob::class,[
            'ship' => new PatrolJob(),
            'position' => new PositionJob('B', 2),
            'grid' => $result,
        ]);

        $result = $this->run(PlaceShipJob::class,[
            'ship' => new SubmarineJob(),
            'position' => new PositionJob('I', 2),
            'grid' => $result,
        ]);

        return $result;

    }

    public function autoPlaceShips($grid){

        $result = $this->run(PlaceBotShipJob::class,[
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


    /**
     * @param $player
     *
     * @throws \Exception
     */
    private function tryToStartTheGameOnPlayer(PlayerFeature $player)
    {
        try {
            $this->startGameOnPlayer($player);
        } catch (\InvalidArgumentException $e) {
            $this->declareWinner($player->opponent());
            throw $e;
        } catch (\Exception $e) {
            $this->declareWinner($player->opponent());
            throw $e;
        }
    }

    private function startGameOnPlayer(PlayerFeature $player)
    {
        $this->players[$player->id()]->startGame();
    }
    /**
     * @param $winner
     * @param $reason
     */
    private function declareWinner($winner)
    {
        $this->winner = $winner;
    }
    /**
     * @return bool
     */
    private function isGameFinished()
    {
        return $this->winner !== null && $this->turn < 100 * 2;
    }
    /**
     * @throws \Exception
     */
    private function playTurn()
    {

            $shot = $this->tryToAskForNextShotToPlayer($this->currentPlayer, $this->opponentPlayer);
            $shotResult = $this->tryToShootToPlayer($this->currentPlayer, $this->opponentPlayer, $shot);
            $this->checkIfShotResultIsCorrect($shot, $shotResult);
            $this->tryToInformLastShotResult($shotResult);
            if ($this->isOpponentDefeated()) {
                return $this->declareWinner($this->currentPlayer);
            }
            $this->swapTurns();
    }

    /**
     * @param $currentPlayerId
     * @param $nextPlayerId
     *
     * @return Hole
     *
     * @throws \Exception
     */
    private function tryToAskForNextShotToPlayer($currentPlayerId, $nextPlayerId)
    {
        $currentPlayerIdValue = $currentPlayerId->value();
        try {
            $fire = $this->players[$currentPlayerIdValue]->fire();
            $this->fireEvent($currentPlayerIdValue, 'shots at '.$fire->letter().'-'.$fire->number());
            return $fire;
        } catch (\Exception $e) {
            $this->declareWinner($nextPlayerId);
            throw $e;
        }
    }
    /**
     * @param $currentPlayerId
     * @param $nextPlayerId
     * @param $shot
     *
     * @return array
     *
     * @throws \Exception
     */
    private function tryToShootToPlayer($currentPlayerId, $nextPlayerId, $shot)
    {
        try {
            $result = $this->players[$nextPlayerId->value()]->shotAt($shot);
            $this->fireEvent($nextPlayerId->value(), 'answers with '.$result);
            return $result;
        } catch (\Exception $e) {
            $this->declareWinner($currentPlayerId);
            throw $e;
        }
    }

    /**
     * @param Hole $shot
     * @param $shotResult
     *
     * @return bool
     *
     */
    private function checkIfShotResultIsCorrect($shot, $shotResult)
    {
        $refereeShotResult = $this->players[$this->opponentPlayer->id()]->shot($shot);
        if ($refereeShotResult !== $shotResult) {
            $this->declareWinner($this->currentPlayer);
        }
    }
    /**
     * @param $shotResult
     */
    private function tryToInformLastShotResult($shotResult)
    {
        try {
            $this->players[$this->currentPlayer->id()]->lastShotResult($shotResult);
        } catch (\Exception $e) {
        }
    }
    private function swapTurns()
    {
        $this->currentPlayerId = $this->opponentPlayer;
        $this->opponentPlayerId = $this->currentPlayer->opponent();
        ++$this->turn;
    }
    /**
     * @return bool
     */
    protected function isOpponentDefeated()
    {
        return $this->players[$this->opponentPlayer->id()]->areAllShipsSunk();
    }


    private function tryToFinishGameOnPlayer(PlayerFeature $player)
    {
        try {
            $this->players[$player->id()]->finishGame();
        } catch (\Exception $e) {
        }
    }


    private function fireEvent($playerId, $message)
    {
        DomainEventPublisher::instance()->publish(new Generic($playerId.' '.$message));
    }
}
