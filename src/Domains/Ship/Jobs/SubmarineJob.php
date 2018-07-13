<?php

namespace App\Domains\Ship\Jobs;

use Lucid\Foundation\Job;

class SubmarineJob extends Job
{
    const ID = 3;
    const SIZE = 3;

    public function getName()
    {
        return 'Submarine';
    }
}
