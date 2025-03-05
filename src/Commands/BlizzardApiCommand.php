<?php

namespace LeeMarkWood\BlizzardApi\Commands;

use Illuminate\Console\Command;

class BlizzardApiCommand extends Command
{
    public $signature = 'blizzard-api';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
