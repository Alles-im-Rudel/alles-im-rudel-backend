<?php

namespace App\Jobs;

use App\Models\InstagramPost;
use App\Notifications\DiscordInstagramPostNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class NotifyNewInstagramPostsJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sent = [];
        $instagramPosts = InstagramPost::query()
            ->whereNull('notified_at')
            ->get();

        foreach ($instagramPosts as $instagramPost) {
            $sent[] = $instagramPost->id;
            Notification::route('discord', env('DISCORD_URL'))
                ->notify(new DiscordInstagramPostNotification($instagramPost));
        }

        InstagramPost::query()
            ->whereIn('id', $sent)
            ->update([
                'notified_at' => now()
            ]);
    }
}
