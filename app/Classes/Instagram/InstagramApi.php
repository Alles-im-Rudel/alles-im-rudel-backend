<?php

namespace App\Classes\Instagram;

use Exception;
use Illuminate\Support\Facades\Http;

class InstagramApi
{
    private string $accountId;

    public function __construct()
    {
        $this->accountId = env('INSTAGRAM_ID');
    }

    /**
     * @param int $postsCount
     * @return array|null
     */
    public function getProfile(int $postsCount = 3): ?array
    {
        try {
            $url = 'https://www.instagram.com/graphql/query/?query_hash=8c2a529969ee035a5063f2fc8602a0fd&variables={"id":' . $this->accountId . ',"first":' . $postsCount . '}';
            $response = Http::get($url);
            $data = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);

            return $this->validateGraphQlResponse($data)
                ? $data
                : null;
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * @param $data
     * @return bool
     */
    private function validateGraphQlResponse($data): bool
    {
        if (!$data || !isset($data['status'])) {
            return false;
        }

        return $data['status'] === 'ok';
    }
}
