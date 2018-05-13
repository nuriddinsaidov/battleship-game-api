<?php
namespace App\Domains\Ship\Jobs;

use Lucid\Foundation\Job;

class BattleshipJob extends Job
{

    const ID = 2;
    const SIZE = 4;

    public function getName(){
        return 'Battleship';
    }
}
