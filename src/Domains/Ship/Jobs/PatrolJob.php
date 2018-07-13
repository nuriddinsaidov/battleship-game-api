<?php

namespace App\Domains\Ship\Jobs;

use Lucid\Foundation\Job;

class PatrolJob extends Job
{
    const ID = 5;
    const SIZE = 1;

    public function getName()
    {
        return 'Patrol';
    }
}
