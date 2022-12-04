<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kolossal\Multiplex\Meta;

class PruneMetaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meta:prune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune meta data except for the last ones';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $total = 0;

        Meta::withoutCurrent()->chunkById(1000, function ($models) use (&$total) {
            $models->each->delete();

            $total += $models->count();
        });

        $this->components->info("Pruned {$total} meta data records.");

        return Command::SUCCESS;
    }
}
