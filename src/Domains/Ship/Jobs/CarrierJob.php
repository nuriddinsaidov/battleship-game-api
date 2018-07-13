<?php

namespace App\Domains\Ship\Jobs;

use Lucid\Foundation\Job;

class CarrierJob extends Job
{
    const ID = 1;
    const SIZE = 5;

    public function getName()
    {
        return 'Carrier';
    }
}
