<?php

namespace App\Jobs;

use App\Classes\Instagram\InstagramApi;
use App\Models\InstagramPost;
use App\Models\InstagramProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ImportInstagramPostsJob implements ShouldQueue
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
        NotifyNewInstagramPostsJob::dispatch();
        return;

        $instagramApi = new InstagramApi();
        $data = $instagramApi->getProfile();
        if (!$data || !$data['data']['user']['edge_owner_to_timeline_media']['edges']) {
            Log::error('[InstagramAPI] Cannot read posts.');
            return;
        }

        $posts = $data['data']['user']['edge_owner_to_timeline_media']['edges'];
        foreach ($posts as $postData) {
            $this->storePost($postData['node']);
        }

        NotifyNewInstagramPostsJob::dispatch();
    }

    /**
     * @param array $postData
     */
    private function storePost(array $postData)
    {
        $instagramProfile = InstagramProfile::firstOrCreate([
            'instagram_id' => $postData['owner']['id'],
            'name'         => $postData['owner']['username']
        ]);

        $descriptionContainer = $postData['edge_media_to_caption']['edges'];

        $instagramPost = InstagramPost::updateOrCreate([
            'instagram_id'         => $postData['id'],
            'instagram_profile_id' => $instagramProfile->id
        ], [
            'description' => count($descriptionContainer) > 0
                ? $this->removeEmojis($descriptionContainer[0]['node']['text'])
                : null
        ]);

        $this->storeEdges($instagramPost, $postData);
    }

    /**
     * @param \App\Models\InstagramPost $instagramPost
     * @param array $postData
     */
    private function storeEdges(InstagramPost $instagramPost, array $postData)
    {
        switch ($postData['__typename']) {
            case 'GraphImage':
                $this->storeImage($instagramPost, $postData);
                break;
            case 'GraphVideo':
                $this->storeVideo($instagramPost, $postData);
                break;
            case 'GraphSidecar':
                $this->storeSidecar($instagramPost, $postData);
                break;
        }
    }

    private function storeVideo(InstagramPost $instagramPost, array $postData)
    {
        $thumbnailResource = collect($postData['display_resources'])->last();

        $instagramPost->instagramVideos()->firstOrCreate([
            'instagram_id' => $postData['id']
        ], [
            'video_url'     => $postData['video_url'],
            'thumbnail_url' => $thumbnailResource
                ? $thumbnailResource['src']
                : $postData['thumbnail_src']
        ]);
    }

    /**
     * @param \App\Models\InstagramPost $instagramPost
     * @param array $postData
     */
    private function storeImage(InstagramPost $instagramPost, array $postData)
    {
        $instagramPost->instagramImages()->firstOrCreate([
            'instagram_id' => $postData['id']
        ], [
            'image_url' => $postData['display_url']
        ]);
    }

    /**
     * @param \App\Models\InstagramPost $instagramPost
     * @param array $postData
     */
    private function storeSidecar(InstagramPost $instagramPost, array $postData)
    {
        foreach ($postData['edge_sidecar_to_children']['edges'] as $childData) {
            $this->storeEdges($instagramPost, $childData['node']);
        }
    }

    /**
     * @param string $string
     * @return string|null
     *
     * https://stackoverflow.com/questions/61481567/remove-emojis-from-string
     */
    private function removeEmojis(string $string): string
    {
        // Match Enclosed Alphanumeric Supplement
        $regex_alphanumeric = '/[\x{1F100}-\x{1F1FF}]/u';
        $clear_string = preg_replace($regex_alphanumeric, '', $string);

        // Match Miscellaneous Symbols and Pictographs
        $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clear_string = preg_replace($regex_symbols, '', $clear_string);

        // Match Emoticons
        $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clear_string = preg_replace($regex_emoticons, '', $clear_string);

        // Match Transport And Map Symbols
        $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clear_string = preg_replace($regex_transport, '', $clear_string);

        // Match Supplemental Symbols and Pictographs
        $regex_supplemental = '/[\x{1F900}-\x{1F9FF}]/u';
        $clear_string = preg_replace($regex_supplemental, '', $clear_string);

        // Match Miscellaneous Symbols
        $regex_misc = '/[\x{2600}-\x{26FF}]/u';
        $clear_string = preg_replace($regex_misc, '', $clear_string);

        // Match Dingbats
        $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
        return preg_replace($regex_dingbats, '', $clear_string);
    }
}
