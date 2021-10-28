<?php

namespace App\Notifications;

use App\Models\InstagramPost;
use App\Models\InstagramProfile;
use Awssat\Notifications\Messages\DiscordEmbed;
use Awssat\Notifications\Messages\DiscordMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DiscordInstagramPostNotification extends Notification
{
    use Queueable;

    /**
     * @var \App\Models\InstagramProfile
     */
    private InstagramProfile $instagramProfile;

    /**
     * @var InstagramPost
     */
    private InstagramPost $instagramPost;

    /**
     * @var string|null
     */
    private ?string $instagramImageUrl = null;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(InstagramPost $instagramPost)
    {
        $instagramImage = $instagramPost->instagramImages()->first();
        if ($instagramImage) {
            $this->instagramImageUrl = $instagramImage->image_url;
        } else {
            $instagramVideo = $instagramPost->instagramVideos()->first();
            if ($instagramVideo) {
                $this->instagramImageUrl = $instagramVideo->thumbnail_url;
            }
        }

        /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
        $this->instagramProfile = $instagramPost->instagramProfile()->first();
        $this->instagramPost = $instagramPost;
    }

    /**
     * @return array
     */
    public function via(): array
    {
        return ['discord'];
    }

    /**
     * @param mixed $notifiable
     */
    public function toDiscord($notifiable): DiscordMessage
    {
        return (new DiscordMessage())
            ->embed(function (DiscordEmbed $embed) {
                $embed->image($this->instagramImageUrl)
                    ->description($this->instagramPost->description)
                    ->author(
                        $this->instagramProfile->display_name ?? $this->instagramProfile->name,
                        'https://instagram.com/' . $this->instagramProfile->name
                    )
                    ->color('ce0071')
                    ->footer('Instagram', 'https://instagram.com/static/images/ico/favicon.ico/36b3ee2d91ed.ico');
            });
    }
}
