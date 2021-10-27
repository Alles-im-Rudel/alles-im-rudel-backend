<?php

namespace App\Console\Commands;

use App\Jobs\ImportInstagramPostsJob;
use Illuminate\Console\Command;

class ImportInstagramPostsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:instagram';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the newest instagram posts';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        ImportInstagramPostsJob::dispatch();
        return 0;
    }
}
