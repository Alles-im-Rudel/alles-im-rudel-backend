<?php

namespace App\Console\Commands;

use App\Jobs\NotifyNewInstagramPostsJob;
use Illuminate\Console\Command;

class NotifyNewInstagramPostsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:instagram';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifies about the newest instagram posts';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        NotifyNewInstagramPostsJob::dispatch();
        return 0;
    }
}
