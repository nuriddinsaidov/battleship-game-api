<?php

namespace App\Domains\Ship\Jobs;

use Lucid\Foundation\Job;

class CruiserJob extends Job
{
    const ID = 4;
    const SIZE = 2;

    public function getName()
    {
        return 'Cruiser';
    }
}
